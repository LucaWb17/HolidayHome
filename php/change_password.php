<?php
require_once 'config.php';
require_once 'security_logger.php';

// Verifica il token CSRF prima di procedere
verify_csrf_token();

$response = ['status' => 'error', 'message' => 'An unknown error occurred.'];

// Ensure user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    $response['message'] = 'You must be logged in to change your password.';
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['current_password'], $_POST['new_password'], $_POST['confirm_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        $user_id = $_SESSION['id'];

        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            $response['message'] = 'Please fill in all password fields.';
        } elseif ($new_password !== $confirm_password) {
            $response['message'] = 'New password and confirmation do not match.';
        } else {
            // --- Rafforzamento Politica Password ---
            $password_err = '';
            if (strlen($new_password) < 12) {
                $password_err = 'La nuova password deve essere lunga almeno 12 caratteri.';
            } elseif (!preg_match('/[A-Z]/', $new_password)) {
                $password_err = 'La nuova password deve contenere almeno una lettera maiuscola.';
            } elseif (!preg_match('/[a-z]/', $new_password)) {
                $password_err = 'La nuova password deve contenere almeno una lettera minuscola.';
            } elseif (!preg_match('/[0-9]/', $new_password)) {
                $password_err = 'La nuova password deve contenere almeno un numero.';
            } elseif (!preg_match('/[\W_]/', $new_password)) { // \W corrisponde a qualsiasi carattere non alfanumerico
                $password_err = 'La nuova password deve contenere almeno un carattere speciale.';
            }

            if (!empty($password_err)) {
                $response['message'] = $password_err;
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
            // --- Fine Rafforzamento ---

            // Fetch the current hashed password from the database
            $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->bind_result($hashed_password);
            $stmt->fetch();
            $stmt->close();

            // Verify the current password
            if (password_verify($current_password, $hashed_password)) {
                // Hash the new password using the most secure algorithm
                $new_hashed_password = password_hash($new_password, PASSWORD_ARGON2ID);

                // Update the password in the database
                $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                $update_stmt->bind_param("si", $new_hashed_password, $user_id);

                if ($update_stmt->execute()) {
                    log_security_event("Password cambiata con successo per l'utente ID: " . $user_id);
                    $response['status'] = 'success';
                    $response['message'] = 'Password changed successfully!';
                } else {
                    $response['message'] = 'An error occurred while updating your password.';
                }
                $update_stmt->close();
            } else {
                $response['message'] = 'Incorrect current password.';
            }
        }
    } else {
        $response['message'] = 'Missing required fields.';
    }
} else {
    $response['message'] = 'Invalid request method.';
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
