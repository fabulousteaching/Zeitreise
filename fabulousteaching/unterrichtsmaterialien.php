<?php
require __DIR__ . '/inc/start.php';

$seite        = 'materialien';
$titel        = 'Unterrichtsmaterialien';
$beschreibung = 'Unterrichtsmaterialien zum Herunterladen – sortiert nach Fach und Zyklus.';

$daten       = materialien_laden();
$faecher     = $daten['faecher'];
$zyklen      = $daten['zyklen'];
$materialien = $daten['materialien'];

// Wie viele Materialien pro Fach?
$anzahl = array_fill_keys(array_keys($faecher), 0);
foreach ($materialien as $m) {
    if (isset($anzahl[$m['fach'] ?? ''])) {
        $anzahl[$m['fach']]++;
    }
}

require __DIR__ . '/inc/kopf.php';
?>

<section class="seitenkopf">
  <div class="huelle">
    <div class="seitenkopf-karte kritzel">
      <div class="deko" aria-hidden="true"><?= icon('book-open') ?></div>
      <div class="seitenkopf-inhalt">
        <h1>Unterrichtsmaterialien</h1>
        <p>Arbeitsblätter, Werkstätten, Spiele und Ideen zum Herunterladen. Wähle ein Fach oder suche direkt nach einem Thema.</p>
        <form class="suche" role="search" onsubmit="return false">
          <?= icon('search') ?>
          <label class="sr" for="suchfeld">Materialien durchsuchen</label>
          <input id="suchfeld" type="search" placeholder="Thema, Fach oder Stichwort …" autocomplete="off">
        </form>
      </div>
    </div>
  </div>
</section>

<section class="abschnitt" style="padding-top:8px" aria-label="Materialien">
  <div class="huelle" id="materialien">

    <div class="faecher" role="group" aria-label="Nach Fach filtern">
      <button class="fach-kachel" type="button" data-fach="" aria-pressed="true" style="--f:#f7a15c">
        <span class="fach-symbol"><?= icon('sparkles') ?></span>
        <span class="fach-name">Alle Fächer</span>
        <span class="fach-anzahl"><?= count($materialien) ?> Materialien</span>
      </button>
      <?php foreach ($faecher as $schluessel => $f): ?>
        <button class="fach-kachel" type="button" data-fach="<?= e($schluessel) ?>" aria-pressed="false" style="--f:<?= e($f['farbe']) ?>">
          <span class="fach-symbol"><?= icon($f['icon']) ?></span>
          <span class="fach-name"><?= e($f['name']) ?></span>
          <span class="fach-anzahl"><?= $anzahl[$schluessel] ?> <?= $anzahl[$schluessel] === 1 ? 'Material' : 'Materialien' ?></span>
        </button>
      <?php endforeach; ?>
    </div>

    <div class="filterleiste">
      <div class="chips" role="group" aria-label="Nach Zyklus filtern">
        <span class="chips-titel">Stufe:</span>
        <button class="chip" type="button" data-zyklus="" aria-pressed="true">Alle</button>
        <?php foreach ($zyklen as $nr => $name): ?>
          <button class="chip" type="button" data-zyklus="<?= (int) $nr ?>" aria-pressed="false"><?= e($name) ?></button>
        <?php endforeach; ?>
      </div>
      <p class="treffer" aria-live="polite"><span id="treffer-zahl"><?= count($materialien) ?></span> Materialien</p>
    </div>

    <div class="raster" id="raster">
      <?php foreach ($materialien as $m) { echo material_karte($m, $faecher, $zyklen); } ?>
    </div>

    <div class="leer" id="leer" hidden>
      <?= icon('search') ?>
      <h3>Nichts gefunden</h3>
      <p>Zu dieser Auswahl gibt es (noch) kein Material.</p>
      <button class="knopf knopf-klein knopf-hell" type="button" id="filter-zurueck">Filter zurücksetzen</button>
    </div>

  </div>
</section>

<?php require __DIR__ . '/inc/fuss.php'; ?>
