<?php
/**
 * Wird von jeder Seite als Erstes geladen:
 * Einstellungen, Sitzung (Login) und kleine Hilfsfunktionen.
 */

declare(strict_types=1);

// ---------- Einstellungen ----------
const SEITENNAME      = 'fabulous teaching';
const RAETSELLABOR_URL = 'https://raetsellabor.fabulousteaching.ch';
const BENUTZER_DATEI  = __DIR__ . '/../daten/benutzer.php';
const MATERIAL_DATEI  = __DIR__ . '/../daten/materialien.php';
const GESCHUETZT_ORDNER = __DIR__ . '/../daten/dateien';

require_once __DIR__ . '/icons.php';
require_once __DIR__ . '/bausteine.php';

// ---------- Sitzung ----------
$https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');

session_name('ft_sitzung');
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'secure'   => $https,
    'httponly' => true,
    'samesite' => 'Lax',
]);

/** Startet die Sitzung (setzt ein Cookie). Nur dort aufrufen, wo es nötig ist: Login. */
function sitzung_starten(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

// Wer schon ein Sitzungs-Cookie hat (angemeldet), bekommt seine Sitzung zurück.
// Alle anderen bleiben ohne Cookie unterwegs.
if (isset($_COOKIE[session_name()])) {
    sitzung_starten();
}

header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('X-Frame-Options: SAMEORIGIN');

// ---------- Hilfsfunktionen ----------

/** Text sicher für HTML ausgeben. */
function e(?string $text): string
{
    return htmlspecialchars((string) $text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Name der angemeldeten Person oder null. */
function angemeldet(): ?string
{
    return isset($_SESSION['benutzer']) ? (string) $_SESSION['benutzer'] : null;
}

/** Leitet zur Login-Seite weiter, wenn niemand angemeldet ist. */
function login_erforderlich(): void
{
    if (angemeldet() === null) {
        $ziel = $_SERVER['REQUEST_URI'] ?? '/bereich.php';
        header('Location: login.php?weiter=' . rawurlencode($ziel));
        exit;
    }
}

/** Schutz gegen gefälschte Formulare (CSRF). */
function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_feld(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

function csrf_pruefen(): bool
{
    $gesendet = $_POST['csrf'] ?? '';
    return is_string($gesendet) && hash_equals(csrf_token(), $gesendet);
}

/** Alle Konten: ['name' => 'passwort-hash', ...] */
function benutzer_laden(): array
{
    if (!is_file(BENUTZER_DATEI)) {
        return [];
    }
    $liste = include BENUTZER_DATEI;
    return is_array($liste) ? $liste : [];
}

/** Nur interne Weiterleitungen zulassen (z. B. "/bereich.php"). */
function sicheres_ziel(?string $ziel): string
{
    $ziel = (string) $ziel;
    if ($ziel === '' || $ziel[0] !== '/' || str_starts_with($ziel, '//') || str_contains($ziel, '\\')) {
        return 'bereich.php';
    }
    return $ziel;
}

/** Unterrichtsmaterialien aus daten/materialien.php */
function materialien_laden(): array
{
    $daten = include MATERIAL_DATEI;
    return [
        'faecher'     => $daten['faecher'] ?? [],
        'zyklen'      => $daten['zyklen'] ?? [],
        'materialien' => $daten['materialien'] ?? [],
    ];
}
