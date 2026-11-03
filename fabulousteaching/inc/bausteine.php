<?php
/**
 * Wiederverwendbare Bausteine: Aquarell-Klecks und Materialkarte.
 */

/** Oranger Aquarell-Klecks wie im Logo (als Hintergrund hinter Bildern). */
function klecks(string $id = 'k1', string $farbe = '#f7a15c'): string
{
    $id = e($id);
    $farbe = e($farbe);
    return <<<SVG
<svg class="klecks" viewBox="0 0 400 400" aria-hidden="true" focusable="false">
  <defs>
    <filter id="{$id}-f" x="-25%" y="-25%" width="150%" height="150%">
      <feTurbulence type="fractalNoise" baseFrequency="0.012" numOctaves="3" seed="7" result="n"/>
      <feDisplacementMap in="SourceGraphic" in2="n" scale="52" xChannelSelector="R" yChannelSelector="G" result="d"/>
      <feGaussianBlur in="d" stdDeviation="1.2"/>
    </filter>
    <filter id="{$id}-f2" x="-25%" y="-25%" width="150%" height="150%">
      <feTurbulence type="fractalNoise" baseFrequency="0.018" numOctaves="2" seed="21" result="n"/>
      <feDisplacementMap in="SourceGraphic" in2="n" scale="38" xChannelSelector="G" yChannelSelector="R"/>
    </filter>
    <radialGradient id="{$id}-g" cx="45%" cy="40%" r="62%">
      <stop offset="0" stop-color="#fdd2ab"/>
      <stop offset=".7" stop-color="{$farbe}"/>
      <stop offset="1" stop-color="#f3934b"/>
    </radialGradient>
  </defs>
  <g filter="url(#{$id}-f)" opacity=".45">
    <path fill="{$farbe}" d="M205 22c70 4 128 36 150 94s16 112-14 160-84 96-152 94-118-34-146-88S16 166 42 112 135 18 205 22z"/>
  </g>
  <g filter="url(#{$id}-f2)">
    <path fill="url(#{$id}-g)" d="M200 48c60 0 112 26 134 78s12 100-12 144-72 86-132 84-104-30-130-78S30 168 52 120 140 48 200 48z"/>
  </g>
</svg>
SVG;
}

/**
 * Eine Materialkarte.
 * $m = ein Eintrag aus daten/materialien.php
 */
function material_karte(array $m, array $faecher, array $zyklen): string
{
    $fachSchluessel = (string) ($m['fach'] ?? '');
    $fach   = $faecher[$fachSchluessel] ?? ['name' => 'Allgemein', 'icon' => 'file-text', 'farbe' => '#f7a15c'];
    $stufen = array_map('intval', $m['zyklen'] ?? []);
    $schutz = !empty($m['nur_angemeldet']);
    $wer    = angemeldet();

    // Suchtext für die Filterfunktion
    $suche = mb_strtolower(implode(' ', [
        $m['titel'] ?? '', $m['beschreibung'] ?? '', $fach['name'], $m['typ'] ?? '',
    ]), 'UTF-8');

    // Knopf: Link, Download, Login oder "folgt"
    if (!empty($m['link'])) {
        $knopf = '<a class="knopf knopf-klein" href="' . e($m['link']) . '" target="_blank" rel="noopener">Öffnen ' . icon('external-link') . '</a>';
    } elseif (empty($m['datei'])) {
        $knopf = '<span class="knopf knopf-klein aus" aria-disabled="true">Folgt bald</span>';
    } elseif ($schutz && !$wer) {
        $weiter = rawurlencode($_SERVER['REQUEST_URI'] ?? '/unterrichtsmaterialien.php');
        $knopf = '<a class="knopf knopf-klein knopf-hell" href="login.php?weiter=' . $weiter . '">' . icon('lock') . ' Anmelden zum Download</a>';
    } elseif ($schutz) {
        $knopf = '<a class="knopf knopf-klein" href="download.php?datei=' . rawurlencode((string) $m['datei']) . '">' . icon('download') . ' Herunterladen</a>';
    } else {
        $knopf = '<a class="knopf knopf-klein" href="downloads/' . rawurlencode((string) $m['datei']) . '" download>' . icon('download') . ' Herunterladen</a>';
    }

    $bild = !empty($m['bild'])
        ? '<img src="' . e($m['bild']) . '" alt="" loading="lazy">'
        : icon($fach['icon']);

    $links = '';
    if (!empty($m['neu']))      { $links .= '<span class="badge badge-neu">Neu</span>'; }
    if (!empty($m['beispiel'])) { $links .= '<span class="badge badge-beispiel">Beispiel</span>'; }
    $rechts = $schutz ? '<span class="badge" title="Nur nach dem Login">' . icon($wer ? 'lock-open' : 'lock') . ' Login</span>' : '';

    $meta = '';
    if (!empty($m['typ']))    { $meta .= '<li>' . e($m['typ']) . '</li>'; }
    if (!empty($m['umfang'])) { $meta .= '<li>' . e($m['umfang']) . '</li>'; }
    foreach ($stufen as $z) {
        if (isset($zyklen[$z])) { $meta .= '<li>' . e($zyklen[$z]) . '</li>'; }
    }

    ob_start(); ?>
<article class="karte" style="--f:<?= e($fach['farbe']) ?>" data-fach="<?= e($fachSchluessel) ?>" data-zyklen="<?= e(implode(' ', $stufen)) ?>" data-suche="<?= e($suche) ?>">
  <div class="karte-bild">
    <?= $bild ?>
    <?php if ($links): ?><div class="abzeichen links"><?= $links ?></div><?php endif; ?>
    <?php if ($rechts): ?><div class="abzeichen rechts"><?= $rechts ?></div><?php endif; ?>
  </div>
  <div class="karte-text">
    <span class="karte-fach"><?= e($fach['name']) ?></span>
    <h3><?= e($m['titel'] ?? '') ?></h3>
    <p><?= e($m['beschreibung'] ?? '') ?></p>
    <?php if ($meta): ?><ul class="karte-meta"><?= $meta ?></ul><?php endif; ?>
    <?= $knopf ?>
  </div>
</article>
<?php
    return (string) ob_get_clean();
}
