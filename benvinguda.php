<?php
session_start();
require_once __DIR__ . '/inc/funcions.php';
require_once __DIR__ . '/inc/protegeix.php';
$dades = require __DIR__ . '/inc/dades.php';

$usuari = $_SESSION['usuari'];
$email = $usuari['email'];
$haVotat = jaHaVotat($email);
$oberta = votacioOberta($dades['consulta']['tancament']);
require_once __DIR__ . '/inc/capcalera.php';
?>
<h2>Hola, <?= h($usuari['nom']) ?>!</h2>
<p>Has iniciat sessió <?= h(tempsDesDe((int) $_SESSION['hora_login'])) ?>.</p>
<p><?= h($dades['consulta']['pregunta']) ?></p>
<p>La votació està <?= $oberta ? 'oberta' : 'tancada' ?>.
Tancament: <?= date('d/m/Y \a \l\e\s H:i', strtotime($dades['consulta']['tancament'])) ?>.</p>

<?php if ($usuari['rol'] === 'admin'): ?>
    <p><a href="resultats.php">Consultar els resultats</a></p>
<?php elseif ($haVotat): ?>
    <p>Ja has votat. <a href="resultats.php">Veure els resultats</a></p>
<?php elseif ($oberta): ?>
    <p><a href="votacio.php">Votar ara</a></p>
<?php endif; ?>

<?php require_once __DIR__ . '/inc/peu.php'; ?>
