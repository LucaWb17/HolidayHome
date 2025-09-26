<?php
// Impostazioni di sicurezza per la sessione
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', isset($_SERVER['HTTPS']) ? 1 : 0); // Usa '1' se sei in produzione con HTTPS
ini_set('session.use_strict_mode', 1);

// Avvia la sessione se non è già attiva
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Configurazione del database
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'villa_paradiso');

// Imposta la modalità di report degli errori di MySQLi per lanciare eccezioni
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Crea una connessione al database
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    // Imposta il charset a utf8mb4 per supportare un'ampia gamma di caratteri
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    // Log dell'errore (in un ambiente di produzione, scrivi su un file di log protetto)
    error_log('Errore di connessione al database: ' . $e->getMessage());

    // Mostra un messaggio generico all'utente
    // In un'API JSON, risponderesti con un JSON, qui terminiamo l'esecuzione per sicurezza.
    http_response_code(500);
    die("Errore interno del server. Si prega di riprovare più tardi.");
}
?>
