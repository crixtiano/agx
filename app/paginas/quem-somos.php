<?php
/**
 * paginas/quem-somos.php — AGX
 *
 * About page: who AGX is, six areas of action (based on Estatuto, art. 4,
 * but not a copy of it) and how the association is governed.
 * Included only by index.php.
 */

defined('AGX_APP') || exit;

/* Fronts of action: label key prefix => SVG path content (stroke icons;
   map pin and handshake from Lucide, ISC license) */
$frentes = [
    'about.f2' => '<path d="M8 21h8M12 17v4M7 4h10v4a5 5 0 0 1-10 0V4zM7 6H4a3 3 0 0 0 3 4M17 6h3a3 3 0 0 1-3 4"></path>',
    'about.f1' => '<path d="M4 20V9l8-5 8 5v11M9 20v-6h6v6"></path>',
    'about.f6' => '<path d="M3 6l9-3 9 3-9 3-9-3zM7 8v6c0 1.5 2.2 3 5 3s5-1.5 5-3V8"></path>',
    'about.f5' => '<path d="M20 10c0 4.99-5.54 10.19-7.4 11.8a1 1 0 0 1-1.2 0C9.54 20.19 4 14.99 4 10a8 8 0 0 1 16 0"></path><circle cx="12" cy="10" r="3"></circle>',
    'about.f4' => '<path d="m11 17 2 2a1 1 0 1 0 3-3"></path><path d="m14 14 2.5 2.5a1 1 0 1 0 3-3l-3.88-3.88a3 3 0 0 0-4.24 0l-.88.88a1 1 0 1 1-3-3l2.81-2.81a5.79 5.79 0 0 1 7.06-.87l.47.28a2 2 0 0 0 1.42.25L21 4"></path><path d="m21 3 1 11h-2"></path><path d="M3 3 2 14l6.5 6.5a1 1 0 1 0 3-3"></path><path d="M3 4h8"></path>',
    'about.f3' => '<circle cx="9" cy="8" r="3"></circle><circle cx="17" cy="9" r="2.5"></circle><path d="M3 20c0-3.3 2.7-6 6-6s6 2.7 6 6M15 20c0-2 .8-3.8 2-5 2.2 0 4 1.8 4 4"></path>',
];

/* Footer line of some cards: 'agenda' = link to the events calendar,
   'soon' = plain "coming soon" note, 'partner' = link to the contact page */
$rodape = [
    'about.f2' => 'agenda',
    'about.f6' => 'agenda',
    'about.f1' => 'soon',
    'about.f5' => 'soon',
    'about.f4' => 'partner',
];
?>

<!-- --------------------------------------------------------
     PAGE HEADER
-------------------------------------------------------- -->
<section class="agx-pagehead">
  <div class="agx-wrap">
    <p class="agx-crumb"><a href="<?= pageUrl('inicio') ?>"><?= t('nav.home') ?></a> / <?= t('nav.about') ?></p>
    <h1><?= t('nav.about') ?></h1>
    <p class="agx-pagehead-lead"><?= t('about.lead') ?></p>
  </div>
</section>

<!-- --------------------------------------------------------
     FRONTS OF ACTION
-------------------------------------------------------- -->
<section class="agx-wrap agx-section-sm">
  <p class="agx-intro"><?= t('about.text') ?></p>
  <div class="agx-grid agx-grid-equal">
    <?php foreach ($frentes as $key => $icon): ?>
      <div class="agx-card agx-card-flex">
        <div class="agx-icon">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><?= $icon ?></svg>
        </div>
        <h3><?= t($key . '_title') ?></h3>
        <p class="agx-muted agx-small mb-0"><?= t($key . '_text') ?></p>
        <?php if (($rodape[$key] ?? '') === 'agenda'): ?>
          <p class="agx-card-foot agx-card-more"><a class="agx-quiet-link" href="<?= pageUrl('agenda') ?>"><i class="bi bi-calendar-event" aria-hidden="true"></i> <?= t('about.agenda_link') ?></a></p>
        <?php elseif (($rodape[$key] ?? '') === 'soon'): ?>
          <p class="agx-card-foot agx-card-more"><span class="agx-quiet-link"><i class="bi bi-hourglass-split" aria-hidden="true"></i> <?= t('about.soon') ?></span></p>
        <?php elseif (($rodape[$key] ?? '') === 'partner'): ?>
          <p class="agx-card-foot agx-card-more"><a class="agx-quiet-link" href="<?= pageUrl('contato') ?>"><i class="bi bi-chat-dots" aria-hidden="true"></i> <?= t('about.partner_link') ?></a></p>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- --------------------------------------------------------
       HOW WE WORK
  -------------------------------------------------------- -->
  <div class="agx-panel-navy">
    <h3><?= t('about.how_title') ?></h3>
    <div class="agx-grid agx-grid-xs">
      <?php for ($i = 1; $i <= 4; $i++): ?>
        <div>
          <p class="agx-hl"><?= t('about.how' . $i . '_title') ?></p>
          <p class="agx-small mb-0"><?= t('about.how' . $i . '_text') ?></p>
          <?php if ($i === 4): ?>
            <p class="agx-card-more"><a class="agx-quiet-link" href="<?= pageUrl('transparencia') ?>"><i class="bi bi-file-earmark-text" aria-hidden="true"></i> <?= t('about.how4_link') ?></a></p>
          <?php endif; ?>
        </div>
      <?php endfor; ?>
    </div>
  </div>
</section>
