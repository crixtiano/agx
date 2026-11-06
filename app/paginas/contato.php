<?php
/**
 * paginas/contato.php — AGX
 *
 * Contact page: e-mail, social networks and the members area.
 * Included only by index.php.
 */

defined('AGX_APP') || exit;

/* Contact cards: href, label, value and Bootstrap Icons class */
$contatos = [
    [AGX_INSTAGRAM,         'Instagram',          '@somosagx',               'bi-instagram'],
    [AGX_FACEBOOK,          'Facebook',           '/agx.org.br',             'bi-facebook'],
    ['mailto:' . AGX_EMAIL, t('contact.email'),   AGX_EMAIL,                 'bi-envelope'],];
?>

<!-- --------------------------------------------------------
     PAGE HEADER
-------------------------------------------------------- -->
<section class="agx-pagehead">
  <div class="agx-wrap">
    <p class="agx-crumb"><a href="<?= pageUrl('inicio') ?>"><?= t('nav.home') ?></a> / <?= t('nav.contact') ?></p>
    <h1><?= t('nav.contact') ?></h1>
    <p class="agx-pagehead-lead"><?= t('contact.lead') ?></p>
  </div>
</section>

<!-- --------------------------------------------------------
     CONTACT CARDS
-------------------------------------------------------- -->
<section class="agx-wrap agx-section-sm">
  <div class="agx-grid agx-grid-md">
    <?php foreach ($contatos as [$href, $label, $value, $icon]): ?>
      <a class="agx-contact" href="<?= $href ?>">
        <div class="agx-icon agx-icon-navy">
          <i class="bi <?= $icon ?>" aria-hidden="true"></i>
        </div>
        <div>
          <p class="agx-muted agx-xsmall mb-0"><?= $label ?></p>
          <p class="fw-semibold mb-0"><?= $value ?></p>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
</section>
