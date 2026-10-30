<?php
$usuariActual = $_SESSION['usuari'];
$missatge = llegeixMissatge();
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Votacions de l'institut</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 850px; margin: 30px auto; padding: 0 16px; }
        nav, .missatge { padding: 12px; background: #eee; margin-bottom: 18px; }
        nav a { margin-right: 14px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #aaa; padding: 8px; text-align: left; }
    </style>
</head>
<body>
<header>
    <h1>Votacions de l'institut</h1>
    <nav>
        Hola, <?= h($usuariActual['nom']) ?> |
        <a href="benvinguda.php">Inici</a>
        <?php if ($usuariActual['rol'] === 'votant'): ?>
            <a href="votacio.php">Votar</a>
        <?php endif; ?>
        <a href="resultats.php">Resultats</a>
        <a href="logout.php">Tancar sessió</a>
    </nav>
</header>
<?php if ($missatge !== null): ?>
    <p class="missatge"><?= h($missatge) ?></p>
<?php endif; ?>
<main>
