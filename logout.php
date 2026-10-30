<?php
session_start();

$nom = $_SESSION['usuari']['nom'] ?? 'usuari';

// Buida les dades, elimina la cookie de sessió i destrueix la sessió.
$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', [
        'expires' => time() - 42000,
        'path' => $params['path'],
        'domain' => $params['domain'],
        'secure' => $params['secure'],
        'httponly' => $params['httponly'],
        'samesite' => 'Lax',
    ]);
}
session_destroy();
?>
<!DOCTYPE html>
<html lang="ca">
<head><meta charset="UTF-8"><title>Sortida</title></head>
<body>
    <p>Fins la propera, <?= htmlspecialchars($nom, ENT_QUOTES, 'UTF-8') ?>!</p>
    <a href="index.php">Tornar a entrar</a>
</body>
</html>
