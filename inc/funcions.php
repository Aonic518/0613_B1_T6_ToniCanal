<?php
require_once __DIR__ . '/vots.php';

// Busca un usuari pel seu correu.
function buscaUsuari(array $usuaris, string $email): ?array {
    foreach ($usuaris as $usuari) {
        if ($usuari['email'] === $email) {
            return $usuari;
        }
    }
    return null;
}

// Converteix un timestamp en un text de temps transcorregut.
function tempsDesDe(int $moment): string {
    $segons = max(0, time() - $moment);

    if ($segons < 60) {
        return 'fa menys d’un minut';
    }
    if ($segons < 3600) {
        $n = intdiv($segons, 60);
        return 'fa ' . $n . ' ' . ($n === 1 ? 'minut' : 'minuts');
    }
    if ($segons < 86400) {
        $n = intdiv($segons, 3600);
        return 'fa ' . $n . ' ' . ($n === 1 ? 'hora' : 'hores');
    }

    $n = intdiv($segons, 86400);
    return 'fa ' . $n . ' ' . ($n === 1 ? 'dia' : 'dies');
}

// Comprova si encara es pot votar.
function votacioOberta(string $tancament): bool {
    $data = strtotime($tancament);
    return $data !== false && time() < $data;
}

// Comprova si el correu ja apareix al fitxer de vots.
function jaHaVotat(string $email): bool {
    $vots = llegeixVots();
    return array_key_exists($email, $vots);
}

// Retorna el recompte de totes les opcions.
function recompte(array $vots, array $opcions): array {
    $resultat = array_fill_keys(array_keys($opcions), 0);
    foreach ($vots as $opcio) {
        if (array_key_exists($opcio, $resultat)) {
            $resultat[$opcio]++;
        }
    }
    return $resultat;
}

// Desa un missatge temporal a la sessió.
function afegeixMissatge(string $text): void {
    $_SESSION['missatge_flash'] = $text;
}

// Llegeix el missatge temporal i l'esborra.
function llegeixMissatge(): ?string {
    $missatge = $_SESSION['missatge_flash'] ?? null;
    unset($_SESSION['missatge_flash']);
    return is_string($missatge) ? $missatge : null;
}

// Escapa text abans de mostrar-lo en HTML.
function h(string $text): string {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}
