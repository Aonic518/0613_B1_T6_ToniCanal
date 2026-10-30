<?php
session_start();
require_once __DIR__ . '/inc/funcions.php';
require_once __DIR__ . '/inc/protegeix.php';
$dades = require __DIR__ . '/inc/dades.php';

$usuari = $_SESSION['usuari'];
$vots = llegeixVots();
$haVotat = array_key_exists($usuari['email'], $vots);
$oberta = votacioOberta($dades['consulta']['tancament']);

// Només admin, votants que ja han votat o qualsevol quan és tancada.
if ($usuari['rol'] !== 'admin' && !$haVotat && $oberta) {
    afegeixMissatge('Podràs veure els resultats quan hagis votat.');
    header('Location: votacio.php');
    exit;
}

$totals = recompte($vots, $dades['consulta']['opcions']);
$totalVots = array_sum($totals);
$totalVotants = 0;
foreach ($dades['usuaris'] as $persona) {
    if ($persona['rol'] === 'votant') {
        $totalVotants++;
    }
}

require_once __DIR__ . '/inc/capcalera.php';
?>
<h2>Resultats</h2>
<table>
    <thead>
        <tr><th>Opció</th><th>Vots</th><th>Percentatge</th></tr>
    </thead>
    <tbody>
    <?php foreach ($dades['consulta']['opcions'] as $clau => $text): ?>
        <?php $percentatge = $totalVots > 0 ? round(($totals[$clau] / $totalVots) * 100) : 0; ?>
        <tr>
            <td><?= h($text) ?></td>
            <td><?= $totals[$clau] ?></td>
            <td><?= $percentatge ?>%</td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<p>Participació: <?= $totalVots ?> de <?= $totalVotants ?> votants</p>

<?php require_once __DIR__ . '/inc/peu.php'; ?>
