<?php
require __DIR__ . '/inc/start.php';

$seite        = 'home';
$beschreibung = 'fabulous teaching – Unterrichtsmaterialien, Rätsel und Ideen aus dem Schulalltag.';

$daten = materialien_laden();
// Vorschau: bis zu drei Materialien – zuerst die mit 'neu' => true, dann die obersten der Liste
$vorschau = $daten['materialien'];
usort($vorschau, fn($a, $b) => (int) !empty($b['neu']) <=> (int) !empty($a['neu']));
$neu = array_slice($vorschau, 0, 3);

// Foto für "Über mich": einfach als assets/bilder/ueber-mich.jpg hochladen
$foto = is_file(__DIR__ . '/assets/bilder/ueber-mich.jpg') ? 'assets/bilder/ueber-mich.jpg' : null;

require __DIR__ . '/inc/kopf.php';
?>

<!-- ============ Begrüssung ============ -->
<section class="held">
  <div class="huelle held-innen">
    <div class="held-text">
      <span class="vorzeile"><?= icon('sparkles') ?> Willkommen</span>
      <h1>Unterricht, der <span>fabelhaft</span> wirkt.</h1>
      <p class="lead">Unterrichtsmaterialien, Rätsel und Ideen aus meinem Schulalltag – zum Stöbern, Herunterladen und Ausprobieren.</p>
      <div class="knopf-reihe">
        <a class="knopf" href="unterrichtsmaterialien.php"><?= icon('book-open') ?> Materialien entdecken</a>
        <a class="knopf knopf-hell" href="raetsellabor.php"><?= icon('puzzle') ?> Zum Rätsellabor</a>
      </div>
    </div>
    <div class="held-bild">
      <?= klecks('held') ?>
      <img src="assets/bilder/logo-640.webp" width="640" height="640" alt="Logo von fabulous teaching: eine Lehrperson am Labortisch mit bunten Reagenzgläsern">
      <span class="blase b1"></span><span class="blase b2"></span><span class="blase b3"></span>
    </div>
  </div>
</section>

<!-- ============ Über mich ============ -->
<section class="abschnitt ueber" id="ueber-mich" aria-labelledby="ueber-titel">
  <div class="huelle ueber-innen">
    <figure class="foto-rahmen">
      <?php if ($foto): ?>
        <img src="<?= e($foto) ?>" alt="Porträt" width="800" height="1000">
      <?php else: ?>
        <div class="foto-platzhalter"><?= icon('user') ?><span>Hier kommt<br>dein Foto hin</span></div>
      <?php endif; ?>
      <figcaption>Das bin ich!</figcaption>
    </figure>

    <div class="ueber-text">
      <span class="vorzeile-klein">Über mich</span>
      <h2 id="ueber-titel">Hallo und herzlich willkommen!</h2>
      <!-- TODO: Platzhalter durch deinen eigenen Text ersetzen -->
      <div class="platzhalter-text">
        <p>Hier steht bald ein kurzer Text über mich: wer ich bin, wo und was ich unterrichte und was mich an meinem Beruf begeistert.</p>
        <p>Und warum es diese Website gibt: um Materialien und Ideen zu teilen, die sich in meinem Unterricht bewährt haben.</p>
      </div>
      <ul class="fakten">
        <li><?= icon('graduation-cap') ?> Lehrperson</li>
        <li><?= icon('flask-conical') ?> Experimentierfreudig</li>
        <li><?= icon('puzzle') ?> Rätselfan</li>
      </ul>
    </div>
  </div>
</section>

<!-- ============ Was du hier findest ============ -->
<section class="abschnitt" aria-labelledby="angebot-titel">
  <div class="huelle">
    <div class="abschnitt-kopf">
      <span class="vorzeile">Was du hier findest</span>
      <h2 id="angebot-titel">Drei Türen ins Klassenzimmer</h2>
    </div>
    <div class="kacheln">
      <a class="kachel" href="unterrichtsmaterialien.php">
        <span class="kachel-symbol" style="background:#e8f6ec"><?= icon('book-open') ?></span>
        <h3>Unterrichtsmaterialien</h3>
        <p>Arbeitsblätter, Werkstätten und Spiele – sortiert nach Fach und Zyklus.</p>
        <span class="pfeil-link">Zu den Materialien <?= icon('arrow-right') ?></span>
      </a>
      <a class="kachel" href="raetsellabor.php">
        <span class="kachel-symbol" style="background:var(--orange-zart)"><?= icon('puzzle') ?></span>
        <h3>Rätsellabor</h3>
        <p>Knobeln, tüfteln und Codes knacken – direkt im Browser.</p>
        <span class="pfeil-link">Ins Rätsellabor <?= icon('arrow-right') ?></span>
      </a>
      <a class="kachel" href="<?= angemeldet() ? 'bereich.php' : 'login.php' ?>">
        <span class="kachel-symbol" style="background:#e6f1fb"><?= icon('key-round') ?></span>
        <h3>Login-Bereich</h3>
        <p>Angemeldet gibt es Zugriff auf zusätzliche, geschützte Materialien.</p>
        <span class="pfeil-link"><?= angemeldet() ? 'Zu meinem Bereich' : 'Anmelden' ?> <?= icon('arrow-right') ?></span>
      </a>
    </div>
  </div>
</section>

<?php if ($neu): ?>
<!-- ============ Neu im Materialschrank ============ -->
<section class="abschnitt" style="padding-top:0" aria-labelledby="neu-titel">
  <div class="huelle">
    <div class="abschnitt-kopf zeile-mit-link">
      <div>
        <span class="vorzeile">Frisch aus dem Labor</span>
        <h2 id="neu-titel" style="margin:0">Aus dem Materialschrank</h2>
      </div>
      <a class="pfeil-link" href="unterrichtsmaterialien.php">Alle Materialien <?= icon('arrow-right') ?></a>
    </div>
    <div class="raster">
      <?php foreach ($neu as $m) { echo material_karte($m, $daten['faecher'], $daten['zyklen']); } ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php require __DIR__ . '/inc/fuss.php'; ?>
