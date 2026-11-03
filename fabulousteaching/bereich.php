<?php
require __DIR__ . '/inc/start.php';
login_erforderlich();

$seite = 'login';
$titel = 'Mein Bereich';
$wer   = angemeldet();

$daten      = materialien_laden();
$geschuetzt = array_values(array_filter($daten['materialien'], fn($m) => !empty($m['nur_angemeldet'])));

require __DIR__ . '/inc/kopf.php';
?>

<section class="seitenkopf">
  <div class="huelle">
    <div class="seitenkopf-karte kritzel">
      <div class="deko" aria-hidden="true"><?= icon('folder-lock') ?></div>
      <div class="seitenkopf-inhalt">
        <h1>Hallo, <?= e($wer) ?>!</h1>
        <p>Willkommen in deinem Bereich. Hier findest du alle Materialien, die nur nach dem Login verfügbar sind.</p>
      </div>
    </div>
  </div>
</section>

<section class="abschnitt" style="padding-top:28px">
  <div class="huelle">
    <?php if (isset($_GET['willkommen'])): ?>
      <div class="meldung meldung-ok" role="status"><?= icon('shield-check') ?><span>Dein Konto ist eingerichtet und du bist angemeldet.</span></div>
    <?php endif; ?>

    <div class="bereich-raster">
      <div class="box">
        <h2><?= icon('folder-lock') ?> Geschützte Materialien</h2>
        <?php if ($geschuetzt): ?>
          <ul class="liste">
            <?php foreach ($geschuetzt as $m):
              $fach = $daten['faecher'][$m['fach'] ?? '']['name'] ?? ''; ?>
              <li>
                <div>
                  <strong><?= e($m['titel']) ?></strong>
                  <span><?= e($fach) ?><?= !empty($m['typ']) ? ' · ' . e($m['typ']) : '' ?></span>
                </div>
                <?php if (!empty($m['datei'])): ?>
                  <a class="knopf knopf-klein" href="download.php?datei=<?= rawurlencode((string) $m['datei']) ?>"><?= icon('download') ?> Herunterladen</a>
                <?php else: ?>
                  <span class="knopf knopf-klein aus" aria-disabled="true">Folgt bald</span>
                <?php endif; ?>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <p>Zurzeit gibt es keine geschützten Materialien.</p>
        <?php endif; ?>
      </div>

      <div class="box">
        <h2><?= icon('user') ?> Konto</h2>
        <p>Angemeldet als <strong><?= e($wer) ?></strong>.</p>
        <form method="post" action="logout.php">
          <?= csrf_feld() ?>
          <button class="knopf knopf-klein knopf-hell" type="submit"><?= icon('log-out') ?> Abmelden</button>
        </form>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/inc/fuss.php'; ?>
