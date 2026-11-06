<?php
/**
 * paginas/transparencia.php — AGX
 *
 * Transparency page: documents table fed by the public documents of
 * Galette (lib/galette.php), filtered by the document types defined
 * there (see includes/js/main.js). Included only by index.php.
 */

defined('AGX_APP') || exit;

require_once __DIR__ . '/../lib/galette.php';

$galette = galetteDocuments();

// Newest upload first; ties keep Galette's order
$documentos = $galette['docs'] ?? [];
usort($documentos, fn($a, $b) => strcmp($b['date'], $a['date']));
?>

<!-- --------------------------------------------------------
     PAGE HEADER
-------------------------------------------------------- -->
<section class="agx-pagehead">
  <div class="agx-wrap">
    <p class="agx-crumb"><a href="<?= pageUrl('inicio') ?>"><?= t('nav.home') ?></a> / <?= t('nav.transparency') ?></p>
    <h1><?= t('nav.transparency') ?></h1>
    <p class="agx-pagehead-lead"><?= t('transp.lead') ?></p>
  </div>
</section>

<!-- --------------------------------------------------------
     DOCUMENTS TABLE
-------------------------------------------------------- -->
<section class="agx-wrap agx-section-sm">
  <div class="agx-card agx-card-lg">
    <div class="agx-table-head">
      <h2><?= t('transp.docs') ?></h2>
      <?php if ($documentos): ?>
        <div class="agx-filter" role="group" aria-label="<?= t('transp.filter_label') ?>" data-doc-filter>
          <button type="button" class="active" data-type="all" aria-pressed="true"><?= t('transp.type_all') ?></button>
          <?php foreach ($galette['types'] as $slug => $name): ?>
            <button type="button" data-type="<?= htmlspecialchars($slug) ?>" aria-pressed="false"><?= htmlspecialchars($name) ?></button>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <?php if ($galette === null): ?>
      <p class="agx-muted mb-0"><?= t('transp.unavailable', ['url' => galetteUrl('/public/documents')]) ?></p>
    <?php elseif (!$documentos): ?>
      <p class="agx-muted mb-0"><?= t('transp.empty') ?></p>
    <?php else: ?>
      <div class="table-responsive">
        <table class="agx-table">
          <thead>
            <tr>
              <th><?= t('transp.col_doc') ?></th>
              <th><?= t('transp.col_type') ?></th>
              <th><?= t('transp.col_date') ?></th>
              <th class="text-end"><?= t('transp.col_file') ?></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($documentos as $doc): ?>
              <tr data-doc-type="<?= htmlspecialchars($doc['type']) ?>">
                <td>
                  <?= htmlspecialchars($doc['description'] !== '' ? $doc['description'] : $doc['name']) ?>
                  <?php if ($doc['description'] !== ''): ?>
                    <span class="agx-doc-file"><?= htmlspecialchars($doc['name']) ?></span>
                  <?php endif; ?>
                </td>
                <td class="agx-muted"><?= htmlspecialchars($galette['types'][$doc['type']] ?? $doc['type']) ?></td>
                <td class="agx-muted text-nowrap"><?= $doc['date'] !== '' ? date(t('common.date_format'), strtotime($doc['date'])) : '' ?></td>
                <td class="text-end"><a class="fw-semibold" href="<?= htmlspecialchars($doc['url']) ?>"><?= htmlspecialchars(strtoupper(pathinfo($doc['name'], PATHINFO_EXTENSION)) ?: t('transp.download')) ?></a></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
  <p class="agx-muted agx-small mt-4 mb-0"><?= t('transp.questions', ['email' => AGX_EMAIL]) ?></p>
</section>
