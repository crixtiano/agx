<?php
/**
 * lib/lang.php — AGX
 *
 * Internationalisation (i18n) of the site.
 *
 * The language comes from the URL: ?lang=en is English, anything else
 * (or nothing) is Portuguese. So each language has its own addresses,
 * which search engines can index separately.
 *
 * FUNCTIONS
 *   getLang()       — Returns the active language code.
 *   langQuery()     — Returns the URL parameter that keeps the active language.
 *   htmlLang()      — Returns the value for the <html lang> attribute.
 *   t(key, params)  — Returns the string translated to the active language.
 *
 * AVAILABLE LANGUAGES
 *   pt_BR  — Português (default)
 *   en_US  — English
 *
 * TRANSLATION FILES
 *   lib/lang/pt_BR.php
 *   lib/lang/en_US.php
 *
 * PLACEHOLDERS
 *   Strings may contain placeholders in the form :name.
 *   Example: t('footer.rights', ['year' => 2026])
 *            with string '© :year AGX' → '© 2026 AGX'
 */

/**
 * getLang(): string
 *
 * Returns the active language code from ?lang= in the URL.
 * Only 'en' selects English; any other value, or none, means 'pt_BR'.
 *
 * @return string  'pt_BR' or 'en_US'
 */
function getLang(): string
{
    return ($_GET['lang'] ?? '') === 'en' ? 'en_US' : 'pt_BR';
}

/**
 * langQuery(): array
 *
 * Returns the URL parameter that keeps a link in the given language.
 * Portuguese is the default, so it needs no parameter.
 *
 * @param string|null $lang  'pt_BR' or 'en_US'. Defaults to the active language.
 *
 * @return array  ['lang' => 'en'] for English, [] for Portuguese
 */
function langQuery(?string $lang = null): array
{
    return ($lang ?? getLang()) === 'en_US' ? ['lang' => 'en'] : [];
}

/**
 * htmlLang(): string
 *
 * Returns the BCP 47 tag of the active language, for use in the
 * <html lang> attribute and in hreflang.
 *
 * @return string  'pt-BR' or 'en'
 */
function htmlLang(): string
{
    return getLang() === 'en_US' ? 'en' : 'pt-BR';
}

/**
 * t(): string
 *
 * Returns the translated string for the given key in the active language.
 *
 * Resolution order:
 *   1. Active language file (e.g. en_US.php)
 *   2. Fallback language file (pt_BR.php)
 *   3. The key itself (so missing translations are always visible)
 *
 * Dictionary strings are trusted (they may contain HTML such as links),
 * so they are returned without escaping. Never pass user input as $params.
 *
 * @param string $key     Dot-notation key (e.g. 'nav.about')
 * @param array  $params  Named replacements. Key 'year' replaces ':year' in the string.
 *
 * @return string  Translated (and interpolated) string.
 */
function t(string $key, array $params = []): string
{
    static $strings     = null;
    static $fallback    = null;
    static $loaded_lang = null;

    $lang = getLang();

    /* Reload if language changed or first call */
    if ($loaded_lang !== $lang) {
        $lang_file = __DIR__ . '/lang/' . $lang . '.php';
        $fb_file   = __DIR__ . '/lang/pt_BR.php';

        $strings     = file_exists($lang_file) ? require $lang_file : [];
        $fallback    = file_exists($fb_file)   ? require $fb_file   : [];
        $loaded_lang = $lang;
    }

    $str = $strings[$key] ?? $fallback[$key] ?? $key;

    foreach ($params as $placeholder => $value) {
        $str = str_replace(':' . $placeholder, (string) $value, $str);
    }

    return $str;
}
