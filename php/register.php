<?php
require_once 'config.php';

$response = ['status' => 'error', 'message' => 'An unknown error occurred.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['name'], $_POST['email'], $_POST['password'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (empty($name) || empty($email) || empty($password)) {
            $response['message'] = 'Please fill in all fields.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['message'] = 'Invalid email format.';
        } else {
            // Check if email already exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $response['message'] = 'Email already registered.';
            } else {
                // Hash the password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insert the new user
                $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $name, $email, $hashed_password);

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
