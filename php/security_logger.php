<?php
/**
 * Funzione per registrare eventi di sicurezza in un file di log protetto.
 *
 * @param string $message Il messaggio da registrare.
 */
function log_security_event($message) {
    // Definisci il percorso del file di log. Assicurati che la directory 'logs' esista e sia scrivibile.
    $log_file = __DIR__ . '/../logs/security.log';

    // Ottieni l'indirizzo IP dell'utente in modo sicuro
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN_IP';

    // Formatta il messaggio di log con timestamp, IP e il messaggio dell'evento
    $log_entry = sprintf(
        "[%s] [IP: %s] %s" . PHP_EOL,
        date('Y-m-d H:i:s'),
        $ip_address,
        $message
    );

    // Scrivi nel file di log in modalità 'append'
    // Usa LOCK_EX per prevenire scritture concorrenti che potrebbero corrompere il file
    file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
}
?>