<?php
require __DIR__ . '/inc/start.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_pruefen()) {
    header('Location: ' . (angemeldet() ? 'bereich.php' : 'login.php'));
    exit;
}

$_SESSION = [];
$p = session_get_cookie_params();
setcookie(session_name(), '', [
    'expires'  => time() - 3600,
    'path'     => $p['path'],
    'secure'   => $p['secure'],
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_destroy();

header('Location: login.php?abgemeldet=1');
exit;
