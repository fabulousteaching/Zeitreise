<?php
/**
 * Seitenkopf mit Navigation. Vor dem Einbinden setzen:
 *   $seite        – 'home' | 'materialien' | 'raetsellabor' | 'login'
 *   $titel        – Titel im Browser-Tab (optional)
 *   $beschreibung – Kurzbeschreibung für Suchmaschinen (optional)
 */

$seite        = $seite ?? '';
$tabTitel     = isset($titel) ? $titel . ' · ' . SEITENNAME : SEITENNAME;
$beschreibung = $beschreibung ?? 'Unterrichtsmaterialien, Rätsel und Ideen für den Unterricht.';
$wer          = angemeldet();

$menue = [
    'home'         => ['Home', 'index.php'],
    'materialien'  => ['Unterrichtsmaterialien', 'unterrichtsmaterialien.php'],
    'raetsellabor' => ['Rätsellabor', 'raetsellabor.php'],
];
?>
<!DOCTYPE html>
<html lang="de-CH" class="no-js">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($tabTitel) ?></title>
<meta name="description" content="<?= e($beschreibung) ?>">
<meta name="theme-color" content="#f7a15c">
<meta property="og:title" content="<?= e($tabTitel) ?>">
<meta property="og:description" content="<?= e($beschreibung) ?>">
<meta property="og:image" content="https://fabulousteaching.ch/assets/bilder/logo-teilen.jpg">
<link rel="icon" type="image/png" href="favicon.png">
<link rel="apple-touch-icon" href="apple-touch-icon.png">
<link rel="preload" href="assets/fonts/fredoka-latin-600-normal.woff2" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="assets/fonts/nunito-latin-400-normal.woff2" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="assets/css/stil.css?v=1">
<script src="assets/js/main.js?v=1" defer></script>
</head>
<body class="seite-<?= e($seite) ?>">
<a class="skip" href="#inhalt">Zum Inhalt springen</a>

<header class="kopf">
  <div class="kopf-innen">
    <a class="marke" href="index.php" aria-label="<?= e(SEITENNAME) ?> – zur Startseite">
      <img class="marke-logo" src="assets/bilder/logo-192.webp" width="56" height="56" alt="">
      <span class="marke-text">fabulous <b>teaching</b></span>
    </a>

    <button class="menue-knopf" type="button" aria-expanded="false" aria-controls="hauptmenue">
      <span class="zu"><?= icon('menu') ?></span>
      <span class="auf"><?= icon('x') ?></span>
      <span class="sr">Menü</span>
    </button>

    <nav class="hauptmenue" id="hauptmenue" aria-label="Hauptmenü">
      <ul>
        <?php foreach ($menue as $menueSchluessel => [$menueName, $menueLink]): ?>
          <li><a href="<?= e($menueLink) ?>"<?= $seite === $menueSchluessel ? ' aria-current="page"' : '' ?>><?= e($menueName) ?></a></li>
        <?php endforeach; ?>
      </ul>
      <div class="konto">
        <?php if ($wer): ?>
          <a class="knopf knopf-klein knopf-dunkel" href="bereich.php"<?= $seite === 'login' ? ' aria-current="page"' : '' ?>><?= icon('user') ?> Mein Bereich</a>
        <?php else: ?>
          <a class="knopf knopf-klein knopf-dunkel" href="login.php"<?= $seite === 'login' ? ' aria-current="page"' : '' ?>><?= icon('log-in') ?> Login</a>
        <?php endif; ?>
      </div>
    </nav>
  </div>
</header>

<main id="inhalt">
