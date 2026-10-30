<?php
// Només deixa entrar usuaris autenticats i controla 5 minuts d'inactivitat.
if (!isset($_SESSION['usuari'])) {
    header('Location: index.php?error=acces');
    exit;
}

$ara = time();
$ultimaActivitat = (int) ($_SESSION['ultima_activitat'] ?? 0);

if ($ultimaActivitat === 0 || ($ara - $ultimaActivitat) > 300) {
    $_SESSION = [];
    session_destroy();
    header('Location: index.php?error=caducada');
    exit;
}

$_SESSION['ultima_activitat'] = $ara;
