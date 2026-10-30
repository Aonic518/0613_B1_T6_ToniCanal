<?php
session_start();
require_once __DIR__ . '/inc/funcions.php';
require_once __DIR__ . '/inc/protegeix.php';
$dades = require __DIR__ . '/inc/dades.php';

$usuari = $_SESSION['usuari'];
// Les comprovacions es fan en l'ordre indicat a l'enunciat.
if ($usuari['rol'] !== 'votant') {
    afegeixMissatge('Només els votants poden votar.');
    header('Location: benvinguda.php');
    exit;
}
if (!votacioOberta($dades['consulta']['tancament'])) {
    afegeixMissatge('La votació està tancada.');
    header('Location: resultats.php');
    exit;
}
if (jaHaVotat($usuari['email'])) {
    afegeixMissatge('Ja has votat. Gràcies!');
    header('Location: resultats.php');
    exit;
}

$errorVot = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $opcio = $_POST['opcio'] ?? '';

    if (!is_string($opcio) || !array_key_exists($opcio, $dades['consulta']['opcions'])) {
        $errorVot = 'Tria una de les opcions.';
    } else {
        guardaVot($usuari['email'], $opcio);
        afegeixMissatge('Vot registrat. Gràcies per participar!');
        header('Location: resultats.php');
        exit;
    }
}

require_once __DIR__ . '/inc/capcalera.php';
?>
<h2>Votació</h2>
<p><?= h($dades['consulta']['pregunta']) ?></p>

<?php if ($errorVot !== ''): ?>
    <p><?= h($errorVot) ?></p>
<?php endif; ?>

<form method="post" action="votacio.php">
    <?php foreach ($dades['consulta']['opcions'] as $clau => $text): ?>
        <label>
            <input type="radio" name="opcio" value="<?= h($clau) ?>">
            <?= h($text) ?>
        </label>
        <br>
    <?php endforeach; ?>
    <br>
    <button type="submit">Enviar vot</button>
</form>

<?php require_once __DIR__ . '/inc/peu.php'; ?>
