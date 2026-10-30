<?php
session_start();

require_once __DIR__ . '/inc/funcions.php';
$dades = require __DIR__ . '/inc/dades.php';

// Si ja hi ha una sessió iniciada, anem a la pàgina privada.
if (isset($_SESSION['usuari'])) {
    header('Location: benvinguda.php');
    exit;
}

// Mostra la informació de la visita anterior i després actualitza la cookie.
$visitaAnterior = $_COOKIE['darrera_visita'] ?? '';
$timestamp = filter_var($visitaAnterior, FILTER_VALIDATE_INT);
if ($timestamp === false || $timestamp <= 0 || $timestamp > time()) {
    $textVisita = 'Benvingut! És la teva primera visita.';
} else {
    $textVisita = 'La teva darrera visita va ser ' . tempsDesDe($timestamp) . '.';
}
setcookie('darrera_visita', (string) time(), [
    'expires' => time() + 30 * 24 * 60 * 60,
    'path' => '/',
    'httponly' => true,
    'samesite' => 'Lax',
]);

// Recupera l'email recordat només si té un format vàlid.
$emailRecordat = $_COOKIE['email_recordat'] ?? '';
if (!is_string($emailRecordat) || !filter_var($emailRecordat, FILTER_VALIDATE_EMAIL)) {
    $emailRecordat = '';
}

$errors = [
    'credencials' => 'Credencials no vàlides.',
    'acces' => 'Primer has d’iniciar sessió.',
    'caducada' => 'La sessió ha caducat per inactivitat.',
];
$error = $errors[$_GET['error'] ?? ''] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $contrasenya = $_POST['contrasenya'] ?? '';
    $recorda = isset($_POST['recorda']);

    if ($recorda && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        setcookie('email_recordat', $email, [
            'expires' => time() + 30 * 24 * 60 * 60,
            'path' => '/',
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    } else {
        setcookie('email_recordat', '', [
            'expires' => time() - 3600,
            'path' => '/',
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    }

    $usuari = buscaUsuari($dades['usuaris'], $email);

    if ($usuari !== null && $usuari['contrasenya'] === $contrasenya) {
        session_regenerate_id(true);
        unset($usuari['contrasenya']); // No desem la contrasenya a la sessió.
        $_SESSION['usuari'] = $usuari;
        $_SESSION['hora_login'] = time();
        $_SESSION['ultima_activitat'] = time();

        header('Location: benvinguda.php');
        exit;
    }

    header('Location: index.php?error=credencials');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inici de sessió</title>
</head>
<body>
    <h1>Votacions de l'institut</h1>
    <p><?= h($textVisita) ?></p>

    <?php if ($error !== ''): ?>
        <p><?= h($error) ?></p>
    <?php endif; ?>

    <form method="post" action="index.php">
        <label>Email:
            <input type="email" name="email" required value="<?= h($emailRecordat) ?>">
        </label>
        <br><br>
        <label>Contrasenya:
            <input type="password" name="contrasenya" required>
        </label>
        <br><br>
        <label>
            <input type="checkbox" name="recorda" <?= $emailRecordat !== '' ? 'checked' : '' ?>>
            Recorda el meu email
        </label>
        <br><br>
        <button type="submit">Entrar</button>
    </form>
</body>
</html>
