<?php
// Questo file deve essere incluso *dopo* config.php, che già avvia la sessione.
// Includiamo il logger per registrare gli eventi di sicurezza.
require_once 'security_logger.php';

// Controlla se l'utente è loggato.
// Qualsiasi ruolo è accettato, basta che l'utente sia autenticato.
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Registra il tentativo di accesso non autorizzato
    log_security_event("Tentativo di accesso all'area clienti da parte di un utente non autenticato.");

    // Se non è un utente loggato, reindirizza alla pagina di login.
    // È buona norma pulire la sessione per evitare stati indefiniti.
    session_unset();
    session_destroy();

    header('Location: login.php?error=login_required');
    exit;
}
?>