<?php
/**
 * Liefert geschützte Dateien aus daten/dateien/ – nur nach dem Login.
 * Aufruf: download.php?datei=arbeitsblatt.pdf
 */
require __DIR__ . '/inc/start.php';
login_erforderlich();

$name = basename((string) ($_GET['datei'] ?? ''));
$pfad = realpath(GESCHUETZT_ORDNER . '/' . $name);
$ordner = realpath(GESCHUETZT_ORDNER);

if ($name === '' || $name[0] === '.' || !$pfad || !$ordner || !str_starts_with($pfad, $ordner . DIRECTORY_SEPARATOR) || !is_file($pfad)) {
    http_response_code(404);
    $seite = 'materialien';
    $titel = 'Datei nicht gefunden';
    require __DIR__ . '/inc/kopf.php';
    echo '<section class="abschnitt"><div class="huelle"><div class="leer">' . icon('file-text')
        . '<h3>Datei nicht gefunden</h3><p>Diese Datei gibt es (noch) nicht.</p>'
        . '<a class="knopf knopf-klein knopf-hell" href="bereich.php">Zurück zu meinem Bereich</a></div></div></section>';
    require __DIR__ . '/inc/fuss.php';
    exit;
}

$typen = [
    'pdf'  => 'application/pdf',
    'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'zip'  => 'application/zip',
    'png'  => 'image/png',
    'jpg'  => 'image/jpeg',
    'mp3'  => 'audio/mpeg',
];
$endung = strtolower(pathinfo($pfad, PATHINFO_EXTENSION));

header('Content-Type: ' . ($typen[$endung] ?? 'application/octet-stream'));
header('Content-Length: ' . filesize($pfad));
header('Content-Disposition: attachment; filename="' . str_replace('"', '', $name) . '"; filename*=UTF-8\'\'' . rawurlencode($name));
header('Cache-Control: private, no-store');
readfile($pfad);
exit;
