<?php
require_once 'config.php';

// Verifica il token CSRF prima di procedere
verify_csrf_token();

$response = ['status' => 'error', 'message' => 'An unknown error occurred.'];

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

                    $response['status'] = 'success';
                    $response['message'] = 'Login successful!';
                    $response['role'] = $role; // Send role to redirect accordingly
                } else {
                    $response['message'] = 'Incorrect email or password.';
                }
            } else {
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
