<?php
require_once 'config.php';

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
                    // Password is correct, start the session
                    $_SESSION['loggedin'] = true;
                    $_SESSION['id'] = $id;
                    $_SESSION['name'] = $name;
                    $_SESSION['role'] = $role;

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
