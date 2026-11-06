<?php
/**
 * paginas/pre-cadastro.php — AGX
 *
 * Explanation page shown before the external sign-up form. It tells the
 * visitor whether to turn on the form option "Desejo ser membro associado".
 * Included only by index.php.
 *
 * Parameters (GET):
 *   associar — 'sim' (came from "Associe-se") or 'nao' (player, arbiter
 *              or instructor registration). Any other value, or none,
 *              falls back to 'nao'.
 */

defined('AGX_APP') || exit;

$associar = presignupVariant();

$form_url = galetteUrl('/subscribe');
$img      = 'imgs/associar_' . $associar . '.jpg';

// Real size in the <img> tag avoids layout shift while the screenshot loads
[$img_w, $img_h] = getimagesize(__DIR__ . '/../' . $img);
?>

<!-- --------------------------------------------------------
     PAGE HEADER
-------------------------------------------------------- -->
<section class="agx-pagehead">
  <div class="agx-wrap">
    <p class="agx-crumb"><a href="<?= pageUrl('inicio') ?>"><?= t('nav.home') ?></a> / <a href="<?= pageUrl('contribua') ?>"><?= t('nav.contribute') ?></a> / <?= $page_title ?></p>
    <h1><?= $page_title ?></h1>
    <p class="agx-pagehead-lead"><?= t('presignup.lead') ?></p>
  </div>
</section>

<!-- --------------------------------------------------------
     INSTRUCTIONS AND FORM LINK
-------------------------------------------------------- -->
<section class="agx-wrap agx-section-sm">
  <div class="agx-card agx-card-lg agx-presignup">
    <div class="agx-notice" role="note">
      <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="9"></circle><path d="M12 11v6M12 7.5v.5"></path></svg>
      <p><?= t('presignup.notice_' . $associar) ?></p>
    </div>

    <!-- Screenshot (left) and form button (right); stacked on phones -->
    <div class="agx-presignup-cols">
      <figure class="agx-shot">
        <button type="button" class="agx-shot-frame" data-bs-toggle="modal" data-bs-target="#agx-sample-modal">
          <img src="<?= $img ?>" alt="<?= htmlspecialchars(t('presignup.alt_' . $associar)) ?>" width="<?= $img_w ?>" height="<?= $img_h ?>">
        </button>
        <figcaption class="agx-shot-label"><?= t('presignup.caption') ?></figcaption>
      </figure>

      <div class="agx-presignup-action">
        <a class="agx-btn agx-btn-yellow agx-btn-lg" href="<?= $form_url ?>"><?= t('presignup.btn') ?></a>
      </div>
    </div>
  </div>
</section>

<!-- --------------------------------------------------------
     SAMPLE IMAGE MODAL (opened by clicking the screenshot)
-------------------------------------------------------- -->
<div class="modal fade agx-modal" id="agx-sample-modal" tabindex="-1" aria-labelledby="agx-sample-modal-title" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="agx-sample-modal-title"><?= t('presignup.modal_title') ?></h2>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= t('presignup.modal_close') ?>"></button>
      </div>
      <div class="modal-body">
        <p class="mb-0"><?= t('presignup.modal_text') ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="agx-btn agx-btn-outline" data-bs-dismiss="modal"><?= t('presignup.modal_ok') ?></button>
        <a class="agx-btn agx-btn-yellow" href="<?= $form_url ?>"><?= t('presignup.modal_go') ?></a>
      </div>
    </div>
  </div>
</div>
