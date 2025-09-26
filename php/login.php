<?php
require_once 'config.php';
require_once 'security_logger.php';

// Verifica il token CSRF prima di procedere
verify_csrf_token();

$response = ['status' => 'error', 'message' => 'An unknown error occurred.'];

// --- Protezione da Brute-Force ---
$ip_address = $_SERVER['REMOTE_ADDR'];
$max_attempts = 5;
$lockout_time = 15 * 60; // 15 minuti in secondi

// 1. Controlla se l'IP è attualmente bloccato
$stmt = $conn->prepare("SELECT COUNT(id) FROM login_attempts WHERE ip_address = ? AND attempt_time > (NOW() - INTERVAL 15 MINUTE)");
$stmt->bind_param("s", $ip_address);
$stmt->execute();
$stmt->bind_result($attempts_count);
$stmt->fetch();
$stmt->close();

if ($attempts_count >= $max_attempts) {
    log_security_event("Blocco per brute-force attivato per IP: " . $ip_address);
    $response['message'] = 'Troppi tentativi di login falliti. Riprova tra 15 minuti.';
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email'], $_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (empty($email) || empty($password)) {
            $response['message'] = 'Please fill in all fields.';
        } else {
            $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($id, $name, $hashed_password, $role);
                $stmt->fetch();

                if (password_verify($password, $hashed_password)) {
                    // Login riuscito: resetta i tentativi falliti per questo IP
                    $delete_stmt = $conn->prepare("DELETE FROM login_attempts WHERE ip_address = ?");
                    $delete_stmt->bind_param("s", $ip_address);
                    $delete_stmt->execute();
                    $delete_stmt->close();

                    // La password è corretta. Rigenera l'ID di sessione per prevenire il session fixation.
                    session_regenerate_id(true);

                    // Imposta le variabili di sessione
                    $_SESSION['loggedin'] = true;
                    $_SESSION['id'] = $id;
                    $_SESSION['name'] = $name;
                    $_SESSION['role'] = $role;

                    // Controlla se la password necessita di essere ri-hashata con l'algoritmo più recente
                    if (password_needs_rehash($hashed_password, PASSWORD_ARGON2ID)) {
                        $new_hash = password_hash($password, PASSWORD_ARGON2ID);
                        $rehash_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                        $rehash_stmt->bind_param("si", $new_hash, $id);
                        $rehash_stmt->execute();
                        $rehash_stmt->close();
                    }

                    log_security_event("Login riuscito per l'utente: " . $email . " (ID: " . $id . ")");
                    $response['status'] = 'success';
                    $response['message'] = 'Login successful!';
                    $response['role'] = $role; // Send role to redirect accordingly
                } else {
                    // Login fallito: registra il tentativo
                    $insert_stmt = $conn->prepare("INSERT INTO login_attempts (ip_address) VALUES (?)");
                    $insert_stmt->bind_param("s", $ip_address);
                    $insert_stmt->execute();
                    $insert_stmt->close();
                    log_security_event("Tentativo di login fallito (password errata) per l'utente: " . $email);
                    $response['message'] = 'Incorrect email or password.';
                }
            } else {
                // Login fallito (utente non trovato): registra il tentativo
                $insert_stmt = $conn->prepare("INSERT INTO login_attempts (ip_address) VALUES (?)");
                $insert_stmt->bind_param("s", $ip_address);
                $insert_stmt->execute();
                $insert_stmt->close();
                log_security_event("Tentativo di login fallito (utente non trovato) per l'email: " . $email);
                $response['message'] = 'Incorrect email or password.';
            }
            $stmt->close();
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
