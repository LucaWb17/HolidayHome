<?php
// Avvia la sessione se non è già attiva, usando le configurazioni sicure di config.php
// NOTA: si presuppone che config.php sia già stato incluso prima di questo file.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Controlla se l'utente è loggato.
// Qualsiasi ruolo è accettato, basta che l'utente sia autenticato.
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Se non è un utente loggato, reindirizza alla pagina di login.
    // È buona norma pulire la sessione per evitare stati indefiniti.
    session_unset();
    session_destroy();

    header('Location: login.php?error=login_required');
    exit;
}
?>