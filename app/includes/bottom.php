<?php
/**
 * includes/bottom.php — AGX
 *
 * Shared bottom of every page: closes <main>, renders the site footer,
 * loads the scripts and closes <body> and <html> opened in top.php.
 * Included only by index.php.
 */

defined('AGX_APP') || exit;
?>
</main>

<!-- --------------------------------------------------------
     SITE FOOTER
-------------------------------------------------------- -->
<footer class="agx-footer">
  <div class="agx-wrap">
    <div class="agx-footer-logo"><img src="imgs/logo-agx.jpg" alt="AGX"></div>
    <span><?= t('footer.rights', ['year' => date('Y'), 'cnpj' => AGX_CNPJ]) ?></span>
    <div class="agx-footer-links">
      <a href="<?= pageUrl('transparencia') ?>"><?= t('nav.transparency') ?></a>
      <a class="agx-icon-link" href="<?= AGX_INSTAGRAM ?>" aria-label="Instagram" title="Instagram"><i class="bi bi-instagram" aria-hidden="true"></i></a>
      <a class="agx-icon-link" href="<?= AGX_FACEBOOK ?>" aria-label="Facebook" title="Facebook"><i class="bi bi-facebook" aria-hidden="true"></i></a>
      <a class="agx-icon-link" href="mailto:<?= AGX_EMAIL ?>" aria-label="<?= t('contact.email') ?>: <?= AGX_EMAIL ?>" title="<?= AGX_EMAIL ?>"><i class="bi bi-envelope" aria-hidden="true"></i></a>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="includes/js/main.js?v=<?= filemtime(__DIR__ . '/js/main.js') ?>"></script>

</body>
</html>
