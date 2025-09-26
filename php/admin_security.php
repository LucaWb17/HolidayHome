<?php
// Avvia la sessione se non è già attiva
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Controlla se l'utente è loggato e se ha il ruolo di 'admin'
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // Se non è un admin loggato, distrugge la sessione per sicurezza
    session_unset();
    session_destroy();

    // Reindirizza alla pagina di login
    header('Location: login.php?error=unauthorized');
    exit;
}
?>