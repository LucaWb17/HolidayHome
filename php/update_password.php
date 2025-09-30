<?php
require_once 'config.php';
require_once 'security_logger.php';

// Verify the CSRF token
verify_csrf_token();

$response = ['status' => 'error', 'message' => 'Si è verificato un errore sconosciuto.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['token'], $_POST['new_password'], $_POST['confirm_password'])) {
        $token = $_POST['token'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // --- Password Policy Validation ---
        if ($new_password !== $confirm_password) {
            $response['message'] = 'Le password non coincidono.';
        } elseif (strlen($new_password) < 12) {
            $response['message'] = 'La nuova password deve essere lunga almeno 12 caratteri.';
        } elseif (!preg_match('/[A-Z]/', $new_password)) {
            $response['message'] = 'La nuova password deve contenere almeno una lettera maiuscola.';
        } elseif (!preg_match('/[a-z]/', $new_password)) {
            $response['message'] = 'La nuova password deve contenere almeno una lettera minuscola.';
        } elseif (!preg_match('/[0-9]/', $new_password)) {
            $response['message'] = 'La nuova password deve contenere almeno un numero.';
        } elseif (!preg_match('/[\W_]/', $new_password)) {
            $response['message'] = 'La nuova password deve contenere almeno un carattere speciale.';
        } else {
            // --- Token Validation ---
            $token_hash = hash('sha256', $token);

            // Find the user by the hashed token and check if the token is still valid
            $stmt = $conn->prepare("SELECT id, password_reset_expires FROM users WHERE password_reset_token = ?");
            $stmt->bind_param("s", $token_hash);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($user_id, $expires_str);
                $stmt->fetch();

                $now = new DateTime();
                $expires = new DateTime($expires_str);

                if ($now > $expires) {
                    log_security_event("Tentativo di reset password con token scaduto per l'utente ID: " . $user_id);
                    $response['message'] = 'Il link di reset è scaduto. Richiedine uno nuovo.';
                } else {
                    // Token is valid and not expired, proceed with password update
                    $hashed_password = password_hash($new_password, PASSWORD_ARGON2ID);

                    // Update password and clear the reset token fields
                    $update_stmt = $conn->prepare("UPDATE users SET password = ?, password_reset_token = NULL, password_reset_expires = NULL WHERE id = ?");
                    $update_stmt->bind_param("si", $hashed_password, $user_id);

                    if ($update_stmt->execute()) {
                        log_security_event("Password reimpostata con successo per l'utente ID: " . $user_id);
                        $response['status'] = 'success';
                        $response['message'] = 'Password aggiornata con successo! Ora puoi effettuare il login con la nuova password.';
                    } else {
                        log_security_event("Errore DB durante l'aggiornamento della password per l'utente ID: " . $user_id);
                        $response['message'] = 'Impossibile aggiornare la password. Riprova.';
                    }
                    $update_stmt->close();
                }
            } else {
                log_security_event("Tentativo di reset password con token non valido o già utilizzato.");
                $response['message'] = 'Token di reset non valido. Potrebbe essere già stato utilizzato o non essere corretto.';
            }
            $stmt->close();
        }
    } else {
        $response['message'] = 'Campi mancanti.';
    }
} else {
    $response['message'] = 'Metodo di richiesta non valido.';
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>