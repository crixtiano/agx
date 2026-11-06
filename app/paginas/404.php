<?php
/**
 * paginas/404.php — AGX
 *
 * Shown by index.php for any ?p= value that is not in the page
 * whitelist. index.php sets the HTTP 404 status before including it.
 */

defined('AGX_APP') || exit;
?>

<!-- --------------------------------------------------------
     PAGE HEADER
-------------------------------------------------------- -->
<section class="agx-pagehead">
  <div class="agx-wrap">
    <p class="agx-crumb">404</p>
    <h1><?= t('err404.title') ?></h1>
    <p class="agx-pagehead-lead"><?= t('err404.text') ?></p>
  </div>
</section>

<!-- --------------------------------------------------------
     BACK TO HOME
-------------------------------------------------------- -->
<section class="agx-wrap agx-section-sm">
  <a class="agx-btn agx-btn-navy" href="<?= pageUrl('inicio') ?>"><?= t('err404.back') ?></a>
</section>
