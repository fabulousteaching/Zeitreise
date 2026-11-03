<?php
require __DIR__ . '/inc/start.php';
sitzung_starten();   // Für das Login-Formular wird eine Sitzung gebraucht

$weiter = sicheres_ziel($_POST['weiter'] ?? $_GET['weiter'] ?? null);

if (angemeldet()) {
    header('Location: ' . $weiter);
    exit;
}

const VERSUCHE_DATEI = __DIR__ . '/daten/login-versuche.json';
const MAX_VERSUCHE   = 8;      // Fehlversuche …
const SPERRZEIT      = 900;    // … innerhalb von 15 Minuten

/** Fehlversuche pro Gerät (IP, nur als Prüfsumme gespeichert). */
function versuche(bool $hinzufuegen = false): int
{
    $schluessel = hash('sha256', ($_SERVER['REMOTE_ADDR'] ?? '') . '|ft');
    $jetzt = time();
    $alle = [];
    $fh = @fopen(VERSUCHE_DATEI, 'c+');
    if (!$fh) {
        return 0;
    }
    flock($fh, LOCK_EX);
    $inhalt = stream_get_contents($fh);
    $alle = json_decode($inhalt ?: '[]', true) ?: [];
    foreach ($alle as $k => $zeiten) {
        $alle[$k] = array_values(array_filter($zeiten, fn($t) => $t > $jetzt - SPERRZEIT));
        if (!$alle[$k]) { unset($alle[$k]); }
    }
    if ($hinzufuegen) {
        $alle[$schluessel][] = $jetzt;
    }
    ftruncate($fh, 0);
    rewind($fh);
    fwrite($fh, json_encode($alle));
    flock($fh, LOCK_UN);
    fclose($fh);
    return count($alle[$schluessel] ?? []);
}

$konten     = benutzer_laden();
$einrichten = $konten === [];   // Noch kein Konto? Dann zuerst eines anlegen.
$fehler     = '';
$name       = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mb_strtolower(trim((string) ($_POST['name'] ?? '')), 'UTF-8');
    $pw   = (string) ($_POST['passwort'] ?? '');

    if (!csrf_pruefen()) {
        $fehler = 'Das Formular ist abgelaufen. Bitte noch einmal versuchen.';
    } elseif ($einrichten) {
        // ---------- Erstes Konto anlegen ----------
        $pw2 = (string) ($_POST['passwort2'] ?? '');
        if (!preg_match('/^[\p{Ll}0-9._@-]{3,60}$/u', $name)) {
            $fehler = 'Der Benutzername braucht 3–60 Zeichen (Buchstaben, Zahlen, . _ - @).';
        } elseif (mb_strlen($pw) < 10) {
            $fehler = 'Das Passwort muss mindestens 10 Zeichen lang sein.';
        } elseif ($pw !== $pw2) {
            $fehler = 'Die beiden Passwörter stimmen nicht überein.';
        } else {
            $inhalt = "<?php\n// Konten für den Login-Bereich. Passwörter sind verschlüsselt (password_hash).\nreturn "
                . var_export([$name => password_hash($pw, PASSWORD_DEFAULT)], true) . ";\n";
            // x = nur anlegen, wenn die Datei noch nicht existiert
            $fh = @fopen(BENUTZER_DATEI, 'x');
            if ($fh && fwrite($fh, $inhalt) !== false) {
                fclose($fh);
                session_regenerate_id(true);
                $_SESSION['benutzer'] = $name;
                header('Location: bereich.php?willkommen=1');
                exit;
            }
            $fehler = 'Das Konto konnte nicht gespeichert werden. Ist der Ordner «daten» beschreibbar?';
        }
    } else {
        // ---------- Anmelden ----------
        if (versuche() >= MAX_VERSUCHE) {
            $fehler = 'Zu viele Fehlversuche. Bitte in 15 Minuten noch einmal versuchen.';
        } else {
            // Auch bei unbekanntem Namen ein Passwort prüfen, damit die Antwortzeit nichts verrät
            $hash = $konten[$name] ?? '$2y$10$a/56g4sjgnoyaUnanQVpi.krb2tR/BZaOFDsfHPZ89aTlhJnzk.mW';
            $ok   = password_verify($pw, $hash);
            if ($ok && isset($konten[$name])) {
                session_regenerate_id(true);
                $_SESSION['benutzer'] = $name;
                header('Location: ' . $weiter);
                exit;
            }
            versuche(true);
            usleep(random_int(300000, 700000));
            $fehler = 'Benutzername oder Passwort ist falsch.';
        }
    }
}

$seite        = 'login';
$titel        = $einrichten ? 'Konto einrichten' : 'Login';
$beschreibung = 'Anmeldung zum geschützten Bereich von fabulous teaching.';
require __DIR__ . '/inc/kopf.php';
?>

<section class="login-flaeche">
  <div class="login-karte">
    <img class="logo" src="assets/bilder/logo-192.webp" width="88" height="88" alt="">

    <?php if ($einrichten): ?>
      <h1>Konto einrichten</h1>
      <p class="unterzeile">Es gibt noch kein Konto. Lege jetzt dein eigenes an – danach ist diese Einrichtung gesperrt.</p>
    <?php else: ?>
      <h1>Login</h1>
      <p class="unterzeile">Melde dich an, um geschützte Materialien zu sehen.</p>
    <?php endif; ?>

    <?php if ($fehler): ?>
      <div class="meldung meldung-fehler" role="alert"><?= icon('x') ?><span><?= e($fehler) ?></span></div>
    <?php elseif (isset($_GET['abgemeldet'])): ?>
      <div class="meldung meldung-ok" role="status"><?= icon('shield-check') ?><span>Du bist abgemeldet. Bis bald!</span></div>
    <?php endif; ?>

    <form method="post" action="login.php" novalidate>
      <?= csrf_feld() ?>
      <input type="hidden" name="weiter" value="<?= e($weiter) ?>">
      <div class="feld">
        <label for="name">Benutzername</label>
        <input id="name" name="name" type="text" value="<?= e($name) ?>" autocomplete="username" autocapitalize="none" spellcheck="false" required>
      </div>
      <div class="feld">
        <label for="passwort">Passwort</label>
        <input id="passwort" name="passwort" type="password" autocomplete="<?= $einrichten ? 'new-password' : 'current-password' ?>" required>
        <?php if ($einrichten): ?><small>Mindestens 10 Zeichen.</small><?php endif; ?>
      </div>
      <?php if ($einrichten): ?>
        <div class="feld">
          <label for="passwort2">Passwort wiederholen</label>
          <input id="passwort2" name="passwort2" type="password" autocomplete="new-password" required>
        </div>
      <?php endif; ?>
      <button class="knopf knopf-breit" type="submit">
        <?= icon($einrichten ? 'key-round' : 'log-in') ?> <?= $einrichten ? 'Konto anlegen' : 'Anmelden' ?>
      </button>
    </form>

    <?php if (!$einrichten): ?>
      <p class="login-fuss">Noch keinen Zugang? Melde dich per E-Mail bei mir.</p>
    <?php endif; ?>
  </div>
</section>

<?php require __DIR__ . '/inc/fuss.php'; ?>
