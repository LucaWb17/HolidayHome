<?php
require_once 'config.php';

// Verifica il token CSRF prima di procedere
verify_csrf_token();

$response = ['status' => 'error', 'message' => 'An unknown error occurred.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['name'], $_POST['email'], $_POST['password'], $_POST['phone'])) {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $phone = trim($_POST['phone']);

        if (empty($name) || empty($email) || empty($password) || empty($phone)) {
            $response['message'] = 'Please fill in all fields.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['message'] = 'Invalid email format.';
        } else {
            // --- Rafforzamento Politica Password ---
            $password_err = '';
            if (strlen($password) < 12) {
                $password_err = 'La password deve essere lunga almeno 12 caratteri.';
            } elseif (!preg_match('/[A-Z]/', $password)) {
                $password_err = 'La password deve contenere almeno una lettera maiuscola.';
            } elseif (!preg_match('/[a-z]/', $password)) {
                $password_err = 'La password deve contenere almeno una lettera minuscola.';
            } elseif (!preg_match('/[0-9]/', $password)) {
                $password_err = 'La password deve contenere almeno un numero.';
            } elseif (!preg_match('/[\W_]/', $password)) { // \W corrisponde a qualsiasi carattere non alfanumerico
                $password_err = 'La password deve contenere almeno un carattere speciale.';
            }

            if (!empty($password_err)) {
                $response['message'] = $password_err;
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
            // --- Fine Rafforzamento ---

            // Check if email already exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $response['message'] = 'Email already registered.';
            } else {
                // Hash the password using the most secure algorithm currently available
                $hashed_password = password_hash($password, PASSWORD_ARGON2ID);

                // Insert the new user
                $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $name, $email, $hashed_password, $phone);

                if ($stmt->execute()) {
                    $response['status'] = 'success';
                    $response['message'] = 'Registration successful! You can now log in.';
                } else {
                    $response['message'] = 'An error occurred during registration.';
                }
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
