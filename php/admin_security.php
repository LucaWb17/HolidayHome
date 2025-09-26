<?php
// Questo file deve essere incluso *dopo* config.php, che già avvia la sessione.
// Includiamo il logger per registrare gli eventi di sicurezza.
require_once 'security_logger.php';

// Controlla se l'utente è loggato e se ha il ruolo di 'admin'
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // Registra il tentativo di accesso non autorizzato
    $user_id = $_SESSION['id'] ?? 'non autenticato';
    log_security_event("Tentativo di accesso non autorizzato alla pagina admin da parte dell'utente ID: " . $user_id);

    // Se non è un admin loggato, distrugge la sessione per sicurezza
    session_unset();
    session_destroy();

    // Reindirizza alla pagina di login
    header('Location: login.php?error=unauthorized');
    exit;
}
?>