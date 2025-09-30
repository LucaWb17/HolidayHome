<?php
require_once 'config.php';
require_once 'email_handler.php';
require_once 'security_logger.php';

// Verify the CSRF token
verify_csrf_token();

$response = ['status' => 'error', 'message' => 'Si è verificato un errore sconosciuto.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email'])) {
        $email = trim($_POST['email']);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['message'] = 'Formato email non valido.';
        } else {
            // Check if the user exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                // User exists, generate a reset token
                $token = bin2hex(random_bytes(32)); // Generate a secure random token
                $token_hash = hash('sha256', $token); // Hash the token for storage

                // Set token expiration (e.g., 1 hour from now)
                $expires = new DateTime('now');
                $expires->add(new DateInterval('PT1H'));
                $expires_str = $expires->format('Y-m-d H:i:s');

                // Store the hashed token and its expiration date in the database
                $update_stmt = $conn->prepare("UPDATE users SET password_reset_token = ?, password_reset_expires = ? WHERE email = ?");
                $update_stmt->bind_param("sss", $token_hash, $expires_str, $email);

                if ($update_stmt->execute()) {
                    // Send the password reset email
                    // IMPORTANT: In a real production environment, ensure the domain is not hardcoded.
                    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
                    $reset_link = $protocol . "://" . $_SERVER['HTTP_HOST'] . "/reset_password.php?token=" . $token;

                    try {
                        send_password_reset_email($email, $reset_link);
                        log_security_event("Richiesta di reset password inviata per l'email: " . $email);
                        // Always show a generic success message to prevent user enumeration
                        $response['status'] = 'success';
                        $response['message'] = 'Se l\'indirizzo email è nel nostro sistema, riceverai un link per il reset della password.';
                    } catch (Exception $e) {
                        log_security_event("Errore durante l'invio dell'email di reset password per: " . $email . " - Errore: " . $e->getMessage());
                        $response['message'] = 'Impossibile inviare l\'email di reset. Riprova più tardi.';
                    }
                } else {
                    log_security_event("Errore DB durante l'aggiornamento del token di reset per l'email: " . $email);
                    $response['message'] = 'Si è verificato un errore nel database.';
                }
                $update_stmt->close();

            } else {
                // Email not found, but we give a generic message to prevent user enumeration
                log_security_event("Tentativo di reset password per email non registrata: " . $email);
                $response['status'] = 'success'; // Still return success to prevent email snooping
                $response['message'] = 'Se l\'indirizzo email è nel nostro sistema, riceverai un link per il reset della password.';
            }
            $stmt->close();
        }
    } else {
        $response['message'] = 'Campo email mancante.';
    }
} else {
    $response['message'] = 'Metodo di richiesta non valido.';
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>