<?php
/**
 * paginas/contribua.php — AGX
 *
 * Ways to contribute: membership (plans and categories), donation via Pix,
 * player registration, volunteering and partnerships.
 * Included only by index.php.
 */

defined('AGX_APP') || exit;

// Monthly dues: 1% of the minimum wage, rounded up to the next multiple
// of R$ 5 (Estatuto, art. 8 §5). 1% / 5 = 1/500, hence the division.
// The yearly fee equals 10 monthly payments (Estatuto, art. 15 §4).
$mensalidade = (int) (ceil(SALARIO_MINIMO / 500) * 5);
$anuidade    = $mensalidade * 10;

$beneficios = ['contrib.benefit1', 'contrib.benefit2', 'contrib.benefit3', 'contrib.benefit4'];
$categorias = ['contrib.cat_effective', 'contrib.cat_founder', 'contrib.cat_benefactor', 'contrib.cat_honorary'];

// "Saiba mais" balloons (Bootstrap popover) on some category cards:
// card key => placeholders for its '<key>_more' text. The Benefactor
// threshold is 2 yearly fees, so it follows SALARIO_MINIMO too.
$saiba_mais = [
    'contrib.cat_founder'    => [],
    'contrib.cat_benefactor' => ['two_fees' => $anuidade * 2, 'example' => $anuidade * 2 + 5],
];

// Player, arbiter and instructor registrations go through the
// explanation page first, in its "not a member" variant.
$pre_cadastro_nao = pageUrl('pre-cadastro', '', ['associar' => 'nao']);

/* Other ways to take part: anchor id, key prefix, footer links (href => [label key, Bootstrap icon]) and SVG content
   (clipboard, graduation cap and megaphone from Lucide, ISC license) */
$participar = [
    ['atleta',     'contrib.athlete',   [$pre_cadastro_nao => ['contrib.athlete_link', 'bi-person-badge']],
     '<path d="M8 21h8M12 17v4M7 4h10v4a5 5 0 0 1-10 0V4zM7 6H4a3 3 0 0 0 3 4M17 6h3a3 3 0 0 1-3 4"></path>'],
    ['arbitro',    'contrib.arbiter',   [$pre_cadastro_nao => ['contrib.arbiter_link', 'bi-clipboard-check']],
     '<rect x="8" y="2" width="8" height="4" rx="1"></rect><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><path d="m9 14 2 2 4-4"></path>'],
    ['instrutor',  'contrib.instructor', [$pre_cadastro_nao => ['contrib.instructor_link', 'bi-mortarboard']],
     '<path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"></path><path d="M22 10v6"></path><path d="M6 12.5V16a6 3 0 0 0 12 0v-3.5"></path>'],
    ['voluntario', 'contrib.volunteer', ['mailto:' . AGX_EMAIL . '?subject=' . rawurlencode(t('contrib.volunteer_subject')) => ['contrib.volunteer_link', 'bi-envelope']],
     '<path d="M12 21s-7-4.4-9-9a5 5 0 0 1 9-3 5 5 0 0 1 9 3c-2 4.6-9 9-9 9z"></path>'],
    ['parceiro',   'contrib.partner',   ['mailto:' . AGX_EMAIL . '?subject=' . rawurlencode(t('contrib.partner_subject')) => ['contrib.partner_link', 'bi-envelope']],
     '<path d="M3 21V8l9-5 9 5v13M8 21v-7h8v7M3 21h18"></path>'],
    ['divulgue',   'contrib.share',     [AGX_INSTAGRAM => ['contrib.share_instagram', 'bi-instagram'],
                                         AGX_FACEBOOK  => ['contrib.share_facebook', 'bi-facebook']],
     '<path d="m3 11 18-5v12L3 14v-3z"></path><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"></path>'],
];
?>

<!-- --------------------------------------------------------
     PAGE HEADER
-------------------------------------------------------- -->
<section class="agx-pagehead">
  <div class="agx-wrap">
    <p class="agx-crumb"><a href="<?= pageUrl('inicio') ?>"><?= t('nav.home') ?></a> / <?= t('nav.contribute') ?></p>
    <h1><?= t('contrib.title') ?></h1>
    <p class="agx-pagehead-lead"><?= t('contrib.lead') ?></p>
  </div>
</section>

<!-- --------------------------------------------------------
     WAYS TO CONTRIBUTE (in-page shortcuts)
-------------------------------------------------------- -->
<nav class="agx-wrap agx-pills" aria-label="<?= t('contrib.nav_label') ?>">
  <a class="agx-pill agx-pill-primary" href="#associe"><?= t('contrib.pill_join') ?></a>
  <a class="agx-pill" href="#doe"><?= t('contrib.pill_donate') ?></a>
  <a class="agx-pill" href="#atleta"><?= t('contrib.pill_athlete') ?></a>
  <a class="agx-pill" href="#voluntario"><?= t('contrib.pill_volunteer') ?></a>
  <a class="agx-pill" href="#parceiro"><?= t('contrib.pill_partner') ?></a>
</nav>

<!-- --------------------------------------------------------
     MEMBERSHIP
-------------------------------------------------------- -->
<section class="agx-wrap agx-section-sm" id="associe">
  <div class="agx-card agx-card-xl agx-split">
    <div class="agx-split-main">
      <p class="agx-eyebrow"><?= t('contrib.join_eyebrow') ?></p>
      <h2><?= t('contrib.join_title') ?></h2>
      <p class="agx-muted"><?= t('contrib.join_text') ?></p>
      <ul class="agx-checks">
        <?php foreach ($beneficios as $key): ?>
          <li>
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12l5 5L20 7"></path></svg>
            <span><?= t($key) ?></span>
          </li>
        <?php endforeach; ?>
      </ul>
      <p class="agx-muted agx-small mb-0"><?= t('contrib.join_who') ?></p>
    </div>
    <div class="agx-split-side">
      <div class="agx-price-grid">
        <div class="agx-price">
          <p><?= t('contrib.monthly') ?></p>
          <p class="agx-price-value">R$ <?= $mensalidade ?></p>
          <p><?= t('contrib.per_month') ?></p>
        </div>
        <div class="agx-price agx-price-navy">
          <p><?= t('contrib.yearly') ?></p>
          <p class="agx-price-value">R$ <?= $anuidade ?></p>
          <p class="agx-price-note"><?= t('contrib.yearly_note') ?></p>
        </div>
      </div>
      <p class="agx-muted agx-xsmall mb-0"><?= t('contrib.fee_note', ['year' => SALARIO_MINIMO_ANO]) ?></p>
      <a class="agx-btn agx-btn-yellow" href="<?= pageUrl('pre-cadastro', '', ['associar' => 'sim']) ?>"><?= t('contrib.join_btn') ?></a>
      <a class="text-center fw-semibold agx-small" href="<?= galetteUrl('/') ?>"><?= t('contrib.join_login') ?></a>
    </div>
  </div>

  <!-- --------------------------------------------------------
       MEMBER CATEGORIES
  -------------------------------------------------------- -->
  <div class="agx-grid agx-grid-sm mt-3">
    <?php foreach ($categorias as $key): ?>
      <div class="agx-card agx-card-flex">
        <h3 class="agx-h3-sm"><?= t($key) ?></h3>
        <p class="agx-muted agx-small mb-0"><?= t($key . '_text') ?></p>
        <?php if (isset($saiba_mais[$key])): ?>
          <p class="agx-card-more">
            <a class="agx-quiet-link" tabindex="0" role="button" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-placement="top" data-bs-html="true"
               data-bs-title="<?= htmlspecialchars(t($key)) ?>" data-bs-content="<?= htmlspecialchars(t($key . '_more', $saiba_mais[$key])) ?>"><i class="bi bi-info-circle" aria-hidden="true"></i> <?= t('contrib.more') ?></a>
          </p>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- --------------------------------------------------------
     DONATION
-------------------------------------------------------- -->
<section class="agx-wrap agx-section" id="doe">
  <div class="agx-donate">
    <div class="agx-donate-text">
      <p class="agx-eyebrow agx-eyebrow-dark"><?= t('contrib.donate_eyebrow') ?></p>
      <h2><?= t('contrib.donate_title') ?></h2>
      <p><?= t('contrib.donate_text') ?></p>
      <p class="agx-small mb-0"><?= t('contrib.donate_material', ['email' => AGX_EMAIL]) ?></p>
    </div>
    <div class="agx-donate-box">
      <p class="agx-muted agx-xsmall mb-0"><?= t('pix.label') ?></p>
      <p class="agx-pix-key"><?= AGX_CNPJ ?></p>
      <button type="button" class="agx-btn agx-btn-navy agx-btn-block" data-copy="<?= preg_replace('/\D/', '', AGX_CNPJ) ?>" data-copied-label="<?= t('pix.copied') ?>"><?= t('pix.copy') ?></button>
      <p class="agx-muted agx-xsmall mt-3 mb-0"><?= t('pix.payee') ?></p>
    </div>
  </div>
</section>

<!-- --------------------------------------------------------
     OTHER WAYS TO TAKE PART
-------------------------------------------------------- -->
<section class="agx-wrap agx-section">
  <h2><?= t('contrib.others_title') ?></h2>
  <div class="agx-grid">
    <?php foreach ($participar as [$id, $key, $links, $icon]): ?>
      <div class="agx-card agx-card-flex" id="<?= $id ?>">
        <div class="agx-icon agx-icon-lg">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><?= $icon ?></svg>
        </div>
        <h3 class="agx-h3-lg"><?= t($key . '_title') ?></h3>
        <p class="agx-muted"><?= t($key . '_text') ?></p>
        <div class="agx-card-foot agx-card-more agx-card-links">
          <?php foreach ($links as $href => [$label, $bi]): ?>
            <a class="agx-quiet-link" href="<?= $href ?>"><i class="bi <?= $bi ?>" aria-hidden="true"></i> <?= t($label) ?></a>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>
