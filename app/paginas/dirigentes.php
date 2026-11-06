<?php
/**
 * paginas/dirigentes.php — AGX
 *
 * Leadership page: Executive Board, Fiscal Council and Legal Counsel,
 * each as its own block of same-size cards. Included only by index.php.
 */

defined('AGX_APP') || exit;

/* Groups: title key, avatar style modifier and members (name, role key) */
$grupos = [
    ['title' => 'board.exec', 'avatar' => 'agx-avatar-exec', 'members' => [
        ['Cristiano Meira Magalhães',        'board.role_president'],
        ['Israel Garcês Pedrosa',            'board.role_vice'],
        ['Diesley da Silva Fernandes',       'board.role_secretary'],
        ['Tarcísio Meira de Oliveira',       'board.role_treasurer'],
    ]],
    ['title' => 'board.fiscal', 'avatar' => '', 'members' => [
        ['Tarcísio Oliveira Teles',          'board.role_councillor'],
        ['Luciano Bismark Ferreira Pereira', 'board.role_councillor'],
        ['Hadson Silva Lima',                'board.role_councillor'],
        ['Joseberto Ribeiro Cruz',           'board.role_alternate'],
    ]],
    ['title' => 'board.legal', 'avatar' => 'agx-avatar-legal', 'members' => [
        ['Claudio Oton Teixeira Fernandes',  'board.role_lawyer'],
    ]],
];

/**
 * initials(): string
 *
 * Returns the initials shown in the avatar while there are no photos:
 * the first letter of the first and of the last name. Uses /u regexes
 * instead of mbstring, so it works without that extension.
 *
 * @param string $name  Full name, already capitalized (e.g. 'Hadson Silva Lima')
 *
 * @return string  Two letters (e.g. 'HL')
 */
function initials(string $name): string
{
    preg_match_all('/(?<!\S)\S/u', trim($name), $m);
    $letters = $m[0];
    return $letters[0] . end($letters);
}
?>

<!-- --------------------------------------------------------
     PAGE HEADER
-------------------------------------------------------- -->
<section class="agx-pagehead">
  <div class="agx-wrap">
    <p class="agx-crumb"><a href="<?= pageUrl('inicio') ?>"><?= t('nav.home') ?></a> / <?= t('nav.board') ?></p>
    <h1><?= t('nav.board') ?></h1>
    <p class="agx-pagehead-lead"><?= t('board.lead') ?></p>
  </div>
</section>

<!-- --------------------------------------------------------
     LEADERSHIP GROUPS
-------------------------------------------------------- -->
<section class="agx-wrap agx-section-sm">
  <?php foreach ($grupos as $grupo): ?>
    <div class="agx-group">
      <h2 class="agx-group-title"><?= t($grupo['title']) ?></h2>
      <div class="agx-grid agx-grid-people">
        <?php foreach ($grupo['members'] as [$name, $role]): ?>
          <div class="agx-person">
            <div class="agx-avatar <?= $grupo['avatar'] ?>" aria-hidden="true"><?= htmlspecialchars(initials($name)) ?></div>
            <div class="agx-person-info">
              <p class="agx-person-name"><?= htmlspecialchars($name) ?></p>
              <p class="agx-person-role"><?= t($role) ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endforeach; ?>
</section>
