<?php
/**
 * paginas/inicio.php — AGX
 *
 * Home page: hero with the large logo, "next event" and "members" cards,
 * and the donation block. Included only by index.php.
 */

defined('AGX_APP') || exit;
?>

<!-- --------------------------------------------------------
     HERO
-------------------------------------------------------- -->
<section class="agx-hero">
  <div class="agx-wrap">
    <img class="agx-hero-logo" src="imgs/logo-agx-hero.webp" alt="<?= t('home.hero_alt') ?>">
    <p class="agx-hero-name"><?= t('site.name') ?></p>
  </div>
</section>

<!-- --------------------------------------------------------
     HERO BAND (navy, below the large logo)
-------------------------------------------------------- -->
<section class="agx-hero-band">
  <div class="agx-wrap">
    <div class="agx-bar"></div>
    <h1><?= t('home.hero_title') ?></h1>
    <p class="agx-hero-lead"><?= t('home.hero_lead') ?></p>
  </div>
</section>

<!-- --------------------------------------------------------
     MAIN BUTTONS (below the band)
-------------------------------------------------------- -->
<div class="agx-wrap agx-section-sm agx-actions">
  <a class="agx-btn agx-btn-outline" href="<?= pageUrl('quem-somos') ?>"><?= t('home.btn_about') ?></a>
  <a class="agx-btn agx-btn-navy" href="<?= pageUrl('pre-cadastro', '', ['associar' => 'sim']) ?>"><?= t('home.btn_join') ?></a>
</div>

<!-- --------------------------------------------------------
     NEXT EVENT AND MEMBERS CARDS
-------------------------------------------------------- -->
<section class="agx-wrap agx-section-sm">
  <div class="agx-grid">
    <div class="agx-card agx-card-lg">
      <p class="agx-eyebrow"><?= t('home.next_event') ?></p>
      <p class="agx-card-title"><?= t('home.next_event_title') ?></p>
      <p class="agx-muted"><?= t('home.next_event_text') ?></p>
      <a class="agx-icon-link agx-icon-link-lg" href="<?= AGX_INSTAGRAM ?>" aria-label="Instagram @somosagx" title="Instagram @somosagx"><i class="bi bi-instagram" aria-hidden="true"></i></a>
    </div>
    <div class="agx-card agx-card-lg">
      <p class="agx-eyebrow"><?= t('home.member') ?></p>
      <p><?= t('home.member_text') ?></p>
      <a class="agx-btn agx-btn-navy" href="<?= galetteUrl('/') ?>"><?= t('home.member_btn') ?></a>
    </div>
  </div>
</section>

<!-- --------------------------------------------------------
     DONATION
-------------------------------------------------------- -->
<section class="agx-wrap agx-section" id="doacoes">
  <div class="agx-donate">
    <div class="agx-donate-text">
      <h2><?= t('home.donate_title') ?></h2>
      <p><?= t('home.donate_text') ?></p>
      <a href="<?= pageUrl('contribua') ?>"><?= t('home.donate_more') ?></a>
    </div>
    <div class="agx-donate-box">
      <p class="agx-muted agx-small mb-0"><?= t('pix.label') ?></p>
      <p class="agx-pix-key"><?= AGX_CNPJ ?></p>
      <button type="button" class="agx-btn agx-btn-navy agx-btn-block" data-copy="<?= preg_replace('/\D/', '', AGX_CNPJ) ?>" data-copied-label="<?= t('pix.copied') ?>"><?= t('pix.copy') ?></button>
    </div>
  </div>
</section>
