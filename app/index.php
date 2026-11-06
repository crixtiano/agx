<?php
/**
 * index.php — AGX
 *
 * Front controller of the public site. Every request is assembled as:
 *
 *   includes/top.php         (HTML head, header and menu)
 *   paginas/<page>.php       (the middle page, chosen by ?p=)
 *   includes/bottom.php      (footer and scripts)
 *
 * Parameters (GET):
 *   p    — Page key (e.g. 'transparencia'). Defaults to 'inicio'.
 *   lang — 'en' for English; Portuguese otherwise (see lib/lang.php).
 *
 * SECURITY: the value of ?p= is only used as a lookup key in $paginas.
 * The file that gets included always comes from that fixed list, never
 * from the URL. Unknown keys render paginas/404.php with HTTP status 404.
 */

require_once __DIR__ . '/lib/lang.php';

/* ----------------------------------------------------------------
   Site configuration
---------------------------------------------------------------- */
// Includes and pages check this constant, so they cannot be opened
// directly by URL (e.g. /paginas/contribua.php).
const AGX_APP = true;

const AGX_CNPJ       = '69.466.981/0001-63';
const AGX_EMAIL       = 'contato@agx.org.br';
const AGX_INSTAGRAM   = 'https://www.instagram.com/somosagx/';
const AGX_FACEBOOK    = 'https://www.facebook.com/agx.org.br/';

// Galette (members area, sign-up form and documents). Links to it are built
// with galetteUrl(), so Galette opens in the site's active language.
// Its public documents are shown on Transparência (lib/galette.php),
// cached for GALETTE_DOCS_TTL seconds.
const GALETTE_URL      = 'https://inscritos.agx.org.br';
const GALETTE_DOCS_TTL = 900;

// Monthly dues are derived from the minimum wage (Estatuto, art. 8 §5),
// so only these two values need to change each year.
const SALARIO_MINIMO     = 1621;
const SALARIO_MINIMO_ANO = 2026;

/* ----------------------------------------------------------------
   Page whitelist: key => file in paginas/ and title key ('' = home)
---------------------------------------------------------------- */
$paginas = [
    'inicio'        => ['file' => 'inicio.php',        'title' => ''],
    'quem-somos'    => ['file' => 'quem-somos.php',    'title' => 'nav.about'],
    'dirigentes'    => ['file' => 'dirigentes.php',    'title' => 'nav.board'],
    'transparencia' => ['file' => 'transparencia.php', 'title' => 'nav.transparency'],
    'agenda'        => ['file' => 'agenda.php',        'title' => 'nav.agenda'],
    'contato'       => ['file' => 'contato.php',       'title' => 'nav.contact'],
    'contribua'     => ['file' => 'contribua.php',     'title' => 'nav.contribute'],
    'pre-cadastro'  => ['file' => 'pre-cadastro.php',  'title' => 'presignup.title_'],   // + variant, see below
];

/**
 * presignupVariant(): string
 *
 * Returns the variant of the pre-cadastro page from ?associar=.
 * Only 'sim' is recognized; any other value, or none, means 'nao'.
 *
 * @return string  'sim' or 'nao'
 */
function presignupVariant(): string
{
    return ($_GET['associar'] ?? '') === 'sim' ? 'sim' : 'nao';
}

/**
 * pageUrl(): string
 *
 * Builds the link to a page of the site. The home page is plain
 * 'index.php'; every other page is 'index.php?p=<key>'. In English,
 * '&lang=en' is added, so the visitor stays in the same language.
 *
 * @param string      $key     Page key from $paginas
 * @param string      $anchor  Optional fragment, without '#'
 * @param array       $query   Optional extra parameters (e.g. ['associar' => 'sim'])
 * @param string|null $lang    Language of the link ('pt_BR' or 'en_US'). Defaults to the active one.
 *
 * @return string  Relative URL, already safe for an href attribute
 */
function pageUrl(string $key, string $anchor = '', array $query = [], ?string $lang = null): string
{
    if ($key !== 'inicio') {
        $query = ['p' => $key] + $query;
    }
    $query += langQuery($lang);
    $url = 'index.php' . ($query ? '?' . http_build_query($query, '', '&amp;', PHP_QUERY_RFC3986) : '');
    return $anchor !== '' ? $url . '#' . rawurlencode($anchor) : $url;
}

/**
 * currentPageUrl(): string
 *
 * Link to the page being shown, in the given language. Used by the
 * PT | EN switcher and by the hreflang/canonical tags. On a 404 page
 * it points to the home page.
 *
 * @param string $lang      'pt_BR' or 'en_US'
 * @param bool   $absolute  true for a full address (https://host/...), needed by hreflang
 *
 * @return string  URL, already safe for an href attribute
 */
function currentPageUrl(string $lang, bool $absolute = false): string
{
    global $nav_ativo;

    $key   = $nav_ativo !== '' ? $nav_ativo : 'inicio';
    $query = $key === 'pre-cadastro' ? ['associar' => presignupVariant()] : [];
    $url   = pageUrl($key, '', $query, $lang);

    return $absolute ? siteUrl($url) : $url;
}

/**
 * siteUrl(): string
 *
 * Full address (https://host/dir/...) of a path relative to the site
 * root. Used where relative links are not accepted: hreflang, canonical
 * and the Open Graph image.
 *
 * @param string $path  Path relative to the site root, already safe for an attribute
 *
 * @return string  Absolute URL, already safe for an href attribute
 */
function siteUrl(string $path = ''): string
{
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $dir    = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
    return $scheme . '://' . htmlspecialchars(($_SERVER['HTTP_HOST'] ?? 'localhost') . $dir) . '/' . $path;
}

/**
 * galetteUrl(): string
 *
 * Builds a link to a Galette page with ?ui_pref_lang set to the site's
 * active language (pt_BR or en_US), so Galette opens in that language.
 *
 * @param string $path  Galette path, starting with '/' (e.g. '/', '/subscribe')
 *
 * @return string  Absolute URL, already safe for an href attribute
 */
function galetteUrl(string $path = '/'): string
{
    return GALETTE_URL . $path . '?ui_pref_lang=' . getLang();
}

$p = $_GET['p'] ?? 'inicio';
$p = is_string($p) ? $p : '';

if (isset($paginas[$p])) {
    $pagina_arquivo = $paginas[$p]['file'];
    $title_key      = $p === 'pre-cadastro' ? $paginas[$p]['title'] . presignupVariant() : $paginas[$p]['title'];
    $page_title     = $title_key !== '' ? t($title_key) : '';
    $nav_ativo      = $p;
} else {
    http_response_code(404);
    $pagina_arquivo = '404.php';
    $page_title     = t('err404.title');
    $nav_ativo      = '';
}

require __DIR__ . '/includes/top.php';
require __DIR__ . '/paginas/' . $pagina_arquivo;
require __DIR__ . '/includes/bottom.php';
