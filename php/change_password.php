<?php
require_once 'config.php';

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
        } elseif (strlen($new_password) < 6) {
            $response['message'] = 'New password must be at least 6 characters long.';
        } else {
            // Fetch the current hashed password from the database
            $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->bind_result($hashed_password);
            $stmt->fetch();
            $stmt->close();

            // Verify the current password
            if (password_verify($current_password, $hashed_password)) {
                // Hash the new password
                $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Update the password in the database
                $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                $update_stmt->bind_param("si", $new_hashed_password, $user_id);

                if ($update_stmt->execute()) {
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
