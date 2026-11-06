<?php
/**
 * includes/top.php — AGX
 *
 * Shared top of every page: DOCTYPE, <head>, site header with the
 * main menu and language switcher, and the opening of <main>.
 * Included only by index.php.
 *
 * EXPECTED VARIABLES (set by index.php):
 *  $page_title — Translated page name. '' for the home page.
 *                The include builds "AGX — {$page_title}".
 *  $nav_ativo  — Key of the active page in the menu ('' for none).
 *
 * WARNING: this file opens tags that are closed in bottom.php.
 *  Never include one without the other.
 */

defined('AGX_APP') || exit;

$page_title     = $page_title ?? '';
$nav_ativo      = $nav_ativo ?? '';
$title_completo = $page_title !== '' ? 'AGX — ' . $page_title : 'AGX — ' . t('site.name');

/* Main menu: page key => label key ("Contribua" is rendered apart, as a button) */
$menu = [
    'quem-somos'    => 'nav.about',
    'dirigentes'    => 'nav.board',
    'transparencia' => 'nav.transparency',
    'agenda'        => 'nav.agenda',
    'contato'       => 'nav.contact',
];
?>
<!DOCTYPE html>
<html lang="<?= htmlLang() ?>">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($title_completo) ?></title>
<meta name="description" content="<?= htmlspecialchars(t('site.description')) ?>">
<?php /* link preview (WhatsApp, Facebook, LinkedIn, X...); og:image must be absolute */ ?>
<meta property="og:type" content="website">
<meta property="og:site_name" content="AGX — <?= htmlspecialchars(t('site.name')) ?>">
<meta property="og:title" content="<?= htmlspecialchars($title_completo) ?>">
<meta property="og:description" content="<?= htmlspecialchars(t('site.description')) ?>">
<meta property="og:url" content="<?= currentPageUrl(getLang(), true) ?>">
<meta property="og:image" content="<?= siteUrl('imgs/compartilhar.png') ?>">
<meta property="og:image:type" content="image/png">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="<?= htmlspecialchars(t('site.name')) ?>">
<meta property="og:locale" content="<?= getLang() ?>">
<meta property="og:locale:alternate" content="<?= getLang() === 'pt_BR' ? 'en_US' : 'pt_BR' ?>">
<meta name="twitter:card" content="summary_large_image">
<?php if ($nav_ativo !== ''): /* language versions of this page (not on 404) */ ?>
<link rel="canonical" href="<?= currentPageUrl(getLang(), true) ?>">
<link rel="alternate" hreflang="pt-BR" href="<?= currentPageUrl('pt_BR', true) ?>">
<link rel="alternate" hreflang="en" href="<?= currentPageUrl('en_US', true) ?>">
<link rel="alternate" hreflang="x-default" href="<?= currentPageUrl('pt_BR', true) ?>">
<?php endif; ?>
<link rel="icon" href="favicon.ico" sizes="16x16 24x24 32x32 48x48">
<link rel="icon" type="image/png" sizes="512x512" href="imgs/icone512x512.png">
<link rel="apple-touch-icon" sizes="180x180" href="imgs/apple-touch-icon.png">
<link rel="preload" href="includes/fonts/outfit-latin.woff2" as="font" type="font/woff2" crossorigin>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="includes/css/style.css?v=<?= filemtime(__DIR__ . '/css/style.css') ?>" rel="stylesheet">
</head>
<body>

<!-- --------------------------------------------------------
     SITE HEADER
-------------------------------------------------------- -->
<header class="agx-header">
  <nav class="navbar navbar-expand-lg agx-wrap" aria-label="<?= t('nav.label') ?>">
    <a class="agx-brand" href="<?= pageUrl('inicio') ?>">
      <img src="imgs/logo-agx.jpg" alt="AGX">
      <span><?= t('site.name') ?></span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#agx-menu" aria-controls="agx-menu" aria-expanded="false" aria-label="<?= t('nav.toggle') ?>">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="agx-menu">
      <ul class="navbar-nav agx-nav">
        <?php foreach ($menu as $key => $label): ?>
          <li class="nav-item">
            <a class="nav-link<?= $nav_ativo === $key ? ' active' : '' ?>" href="<?= pageUrl($key) ?>"<?= $nav_ativo === $key ? ' aria-current="page"' : '' ?>><?= t($label) ?></a>
          </li>
        <?php endforeach; ?>
        <li class="nav-item">
          <a class="nav-link agx-cta" href="<?= pageUrl('contribua') ?>"<?= $nav_ativo === 'contribua' ? ' aria-current="page"' : '' ?>><?= t('nav.contribute') ?></a>
        </li>
        <li class="nav-item agx-lang">
          <a href="<?= currentPageUrl('pt_BR') ?>" hreflang="pt-BR" lang="pt-BR" aria-label="Português"<?= getLang() === 'pt_BR' ? ' class="active" aria-current="true"' : '' ?>>PT</a>
          <span aria-hidden="true">|</span>
          <a href="<?= currentPageUrl('en_US') ?>" hreflang="en" lang="en" aria-label="English"<?= getLang() === 'en_US' ? ' class="active" aria-current="true"' : '' ?>>EN</a>
        </li>
        <li class="nav-item agx-member">
          <a href="<?= galetteUrl('/') ?>" aria-label="<?= t('nav.members') ?>" title="<?= t('nav.members') ?>">
            <span class="agx-icon agx-icon-navy"><i class="bi bi-person" aria-hidden="true"></i></span>
            <span class="d-lg-none"><?= t('nav.members') ?></span>
          </a>
        </li>
      </ul>
    </div>
  </nav>
</header>

<main class="agx-main">
