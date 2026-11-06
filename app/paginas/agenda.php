<?php
/**
 * paginas/agenda.php — AGX
 *
 * Events calendar. There are no events yet, so the page shows only the
 * empty state pointing to Instagram. Included only by index.php.
 */

defined('AGX_APP') || exit;
?>

<!-- --------------------------------------------------------
     PAGE HEADER
-------------------------------------------------------- -->
<section class="agx-pagehead">
  <div class="agx-wrap">
    <p class="agx-crumb"><a href="<?= pageUrl('inicio') ?>"><?= t('nav.home') ?></a> / <?= t('nav.agenda') ?></p>
    <h1><?= t('nav.agenda') ?></h1>
  </div>
</section>

<!-- --------------------------------------------------------
     EVENTS (EMPTY STATE)
-------------------------------------------------------- -->
<section class="agx-wrap agx-section-sm">
  <div class="agx-card agx-card-lg">
    <div class="agx-empty">
      <div class="agx-empty-icon">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"></rect><path d="M3 10h18M8 3v4M16 3v4"></path></svg>
      </div>
      <p class="agx-empty-title"><?= t('agenda.empty_title') ?></p>
      <p class="agx-muted agx-small"><?= t('agenda.empty_text') ?></p>
      <a class="agx-icon-link agx-icon-link-lg" href="<?= AGX_INSTAGRAM ?>" aria-label="<?= t('agenda.follow') ?>" title="<?= t('agenda.follow') ?>"><i class="bi bi-instagram" aria-hidden="true"></i></a>
    </div>
  </div>
</section>
