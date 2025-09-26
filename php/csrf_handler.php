<?php
/**
 * Gestore per la protezione da Cross-Site Request Forgery (CSRF).
 */

/**
 * Genera un token CSRF se non ne esiste già uno nella sessione.
 *
 * @return string Il token CSRF.
 */
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verifica il token CSRF inviato tramite un form POST.
 *
 * Termina l'esecuzione dello script se il token non è valido o mancante,
 * rispondendo con un JSON di errore.
 */
function verify_csrf_token() {
    // La protezione CSRF si applica solo alle richieste che modificano lo stato, tipicamente POST.
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        // Per altre richieste, non facciamo nulla.
        return;
    }

    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token'])) {
        http_response_code(403);
        echo json_encode(['status' => 'error', 'message' => 'Token CSRF mancante. Richiesta bloccata.']);
        exit;
    }

    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        http_response_code(403);
        echo json_encode(['status' => 'error', 'message' => 'Token CSRF non valido. Richiesta bloccata.']);
        exit;
    }

    // Opzionale ma consigliato: invalida il token dopo l'uso per prevenire replay attacks.
    // unset($_SESSION['csrf_token']);
}
?>