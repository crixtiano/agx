<?php
/**
 * lib/galette.php — AGX
 *
 * Reads the public documents published on the Galette platform
 * (GALETTE_URL . '/public/documents'), so the Transparência page can
 * list them without anyone copying links by hand.
 *
 * Galette has no API for this, so the HTML page is parsed. The page groups
 * documents by type (one tab per type); every document has a file name,
 * an optional description, an upload date and a download link
 * (/document/get/<id>). Parsing uses regexes on that fixed markup, so no
 * PHP extension beyond the defaults is needed.
 *
 * CACHE
 *   The result is stored as JSON in agx/cache/ for GALETTE_DOCS_TTL seconds.
 *   If Galette cannot be reached, the last stored list keeps being shown
 *   (and is retried only after another GALETTE_DOCS_TTL).
 *
 * PAGINATION
 *   Today the public list is not paginated (Galette loads every public
 *   document at once). Should page links (/public/documents/page/N) ever
 *   appear, they are followed too, up to GALETTE_MAX_PAGES pages.
 *
 * Expects the constants GALETTE_URL and GALETTE_DOCS_TTL (index.php).
 */

const GALETTE_MAX_PAGES = 20;

/**
 * galetteDocuments(): array|null
 *
 * Returns the public documents of Galette, from the cache when it is
 * fresh, otherwise fetched again. Falls back to a stale cache when the
 * fetch fails.
 *
 * @return array|null  ['types' => [slug => name], 'docs' => [doc, ...]] where
 *                     each doc is ['id', 'type', 'name', 'description', 'date', 'url'],
 *                     or NULL when nothing could be fetched and there is no cache.
 */
function galetteDocuments(): ?array
{
    $cache_file = __DIR__ . '/../../cache/galette_documents.json';
    $cached     = null;

    if (is_file($cache_file)) {
        $cached = json_decode((string) file_get_contents($cache_file), true);
        if (is_array($cached) && time() - filemtime($cache_file) < GALETTE_DOCS_TTL) {
            return $cached;
        }
    }

    $fresh = galetteFetchDocuments();

    if ($fresh !== null) {
        galetteWriteCache($cache_file, $fresh);
        return $fresh;
    }

    if (is_array($cached)) {
        // Galette is down or its markup changed: keep showing the old list
        // and only retry after another TTL.
        @touch($cache_file);
        return $cached;
    }

    return null;
}

/**
 * galetteFetchDocuments(): array|null
 *
 * Downloads the public documents page (and any further pages linked
 * from it) and parses every document.
 *
 * @return array|null  Same shape as galetteDocuments(), or NULL on failure
 */
function galetteFetchDocuments(): ?array
{
    $types   = [];
    $docs    = [];
    $queue   = [1];
    $visited = [];

    while ($queue && count($visited) < GALETTE_MAX_PAGES) {
        $page = array_shift($queue);
        if (isset($visited[$page])) {
            continue;
        }
        $visited[$page] = true;

        $url  = GALETTE_URL . '/public/documents' . ($page > 1 ? '/page/' . $page : '');
        $html = galetteHttpGet($url);
        if ($html === null) {
            if ($page === 1) {
                return null;
            }
            break;
        }

        $parsed = galetteParseDocuments($html);
        if ($parsed === null) {
            if ($page === 1) {
                error_log('AGX galette: unexpected markup at ' . $url);
                return null;
            }
            break;
        }

        $types += $parsed['types'];
        foreach ($parsed['docs'] as $doc) {
            $docs[$doc['id']] = $doc;   // keyed by id, so repeated pages add nothing
        }

        if (preg_match_all('#/public/documents/page/(\d+)#', $html, $m)) {
            foreach ($m[1] as $n) {
                $queue[] = (int) $n;
            }
        }
    }

    return ['types' => $types, 'docs' => array_values($docs)];
}

/**
 * galetteHttpGet(): string|null
 *
 * GET request with a short timeout. Redirects are not followed: a Galette
 * redirect here means "login required", never a document list.
 *
 * @param string $url  Absolute URL on GALETTE_URL
 *
 * @return string|null  Response body on HTTP 200, NULL otherwise
 */
function galetteHttpGet(string $url): ?string
{
    $context = stream_context_create(['http' => [
        'method'          => 'GET',
        'timeout'         => 6,
        'follow_location' => 0,
        'ignore_errors'   => true,
        'header'          => "User-Agent: agx.org.br site\r\nAccept: text/html\r\n",
    ]]);

    $body = @file_get_contents($url, false, $context);
    $status = isset($http_response_header[0]) && preg_match('#\s(\d{3})\s#', $http_response_header[0] . ' ', $m) ? (int) $m[1] : 0;

    if ($body === false || $status !== 200) {
        error_log('AGX galette: GET ' . $url . ' failed (HTTP ' . $status . ')');
        return null;
    }
    return $body;
}

/**
 * galetteParseDocuments(): array|null
 *
 * Extracts types and documents from one Galette public documents page.
 * Only the document id is taken from the link; the download URL is rebuilt
 * from GALETTE_URL, so a changed page can never inject another address.
 *
 * @param string $html  Page body
 *
 * @return array|null  ['types' => [...], 'docs' => [...]], or NULL when the
 *                     page does not look like the documents list at all
 */
function galetteParseDocuments(string $html): ?array
{
    if (strpos($html, 'document-type-') === false) {
        // An empty list has no tabs; tell it apart from an unrelated page
        return strpos($html, '/public/documents') !== false ? ['types' => [], 'docs' => []] : null;
    }

    // Type names come from the tab menu: <a href="#document-type-SLUG" ...>Name <p>(n documents)</p></a>
    $types = [];
    if (preg_match_all('#<a href="\#document-type-([a-z0-9_-]+)"[^>]*>(.*?)<p\b#s', $html, $m, PREG_SET_ORDER)) {
        foreach ($m as [, $slug, $name]) {
            $types[$slug] = galetteText($name);
        }
    }

    // One block per tab: <div id="document-type-SLUG" class="ui tab ...">
    $docs  = [];
    $parts = preg_split('#<div id="document-type-([a-z0-9_-]+)"#', $html, -1, PREG_SPLIT_DELIM_CAPTURE);
    for ($i = 1; $i < count($parts) - 1; $i += 2) {
        $slug  = $parts[$i];
        $items = preg_split('#<div class="item">#', $parts[$i + 1]);
        array_shift($items);

        foreach ($items as $item) {
            if (!preg_match('#href="[^"]*/document/get/(\d+)"[^>]*>(.*?)</a>#s', $item, $link)) {
                continue;
            }
            $description = preg_match('#<div class="description">(.*?)</div>#s', $item, $d) ? galetteText($d[1]) : '';
            $date        = preg_match('#<span class="date[^"]*">(.*?)</span>#s', $item, $t) ? galetteText($t[1]) : '';

            $docs[] = [
                'id'          => (int) $link[1],
                'type'        => $slug,
                'name'        => galetteText($link[2]),
                'description' => $description,
                'date'        => preg_match('#^\d{4}-\d{2}-\d{2}#', $date, $ymd) ? $ymd[0] : '',
                'url'         => GALETTE_URL . '/document/get/' . (int) $link[1],
            ];
            $types[$slug] = $types[$slug] ?? $slug;
        }
    }

    return ['types' => $types, 'docs' => $docs];
}

/**
 * galetteText(): string
 *
 * Turns an HTML fragment into plain text: strips tags, decodes entities
 * and collapses whitespace. The result must still be escaped on output.
 *
 * @param string $fragment  HTML fragment
 *
 * @return string  Plain text
 */
function galetteText(string $fragment): string
{
    $text = html_entity_decode(strip_tags($fragment), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    return trim(preg_replace('/\s+/u', ' ', $text));
}

/**
 * galetteWriteCache(): void
 *
 * Writes the list to the cache file atomically (temp file + rename).
 * Failing to write is not fatal: the site just fetches again next time.
 *
 * @param string $file  Cache file path
 * @param array  $data  List to store
 */
function galetteWriteCache(string $file, array $data): void
{
    $dir = dirname($file);
    if (!is_dir($dir) && !@mkdir($dir, 0775, true)) {
        error_log('AGX galette: cannot create cache dir ' . $dir);
        return;
    }
    $tmp = $file . '.' . getmypid() . '.tmp';
    if (@file_put_contents($tmp, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) === false || !@rename($tmp, $file)) {
        @unlink($tmp);
        error_log('AGX galette: cannot write cache ' . $file);
    }
}
