<?php
require __DIR__ . '/inc/start.php';

$seite        = 'raetsellabor';
$titel        = 'Rätsellabor';
$beschreibung = 'Das Rätsellabor: Knobeln, tüfteln und Codes knacken.';

require __DIR__ . '/inc/kopf.php';
?>

<section class="seitenkopf">
  <div class="huelle">
    <div class="seitenkopf-karte kritzel">
      <div class="deko" aria-hidden="true"><?= icon('puzzle') ?></div>
      <div class="seitenkopf-inhalt">
        <h1>Rätsellabor</h1>
        <p>Hier wird geknobelt, kombiniert und experimentiert – mit Köpfchen statt mit Reagenzglas.</p>
      </div>
    </div>
  </div>
</section>

<section class="abschnitt">
  <div class="huelle labor">
    <div class="labor-text">
      <h2>Willkommen im Labor</h2>
      <!-- TODO: Text zum Rätsellabor anpassen -->
      <p>Das Rätsellabor ist ein eigener Bereich mit Rätseln und Knobelaufgaben. Es läuft auf einer eigenen Adresse und öffnet sich in einem neuen Tab.</p>
      <ul>
        <li><?= icon('puzzle') ?><span><strong>Knobeln</strong><br>Rätsel lösen, Muster erkennen, um die Ecke denken.</span></li>
        <li><?= icon('key-round') ?><span><strong>Codes knacken</strong><br>Hinweise kombinieren und das nächste Schloss öffnen.</span></li>
        <li><?= icon('sparkles') ?><span><strong>Für die ganze Klasse</strong><br>Alleine, zu zweit oder im Team.</span></li>
      </ul>
    </div>

    <aside class="tuer" aria-labelledby="tuer-titel">
      <div class="tuer-symbol"><?= icon('flask-conical') ?></div>
      <h2 id="tuer-titel">Bereit für das erste Rätsel?</h2>
      <p>Die Labortür steht offen.</p>
      <a class="knopf" href="<?= e(RAETSELLABOR_URL) ?>" target="_blank" rel="noopener">Rätsellabor öffnen <?= icon('external-link') ?></a>
      <span class="adresse"><?= e(preg_replace('#^https?://#', '', RAETSELLABOR_URL)) ?></span>
    </aside>
  </div>
</section>

<?php require __DIR__ . '/inc/fuss.php'; ?>
