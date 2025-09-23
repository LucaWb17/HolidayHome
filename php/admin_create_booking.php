<?php
require_once 'config.php';

$response = ['status' => 'error', 'message' => 'An unknown error occurred.'];

// Ensure user is logged in and is an admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    $response['message'] = 'Unauthorized action.';
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['name'], $_POST['email'], $_POST['check_in'], $_POST['check_out'], $_POST['guests'])) {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $check_in = $_POST['check_in'];
        $check_out = $_POST['check_out'];
        $guests = filter_var($_POST['guests'], FILTER_VALIDATE_INT);

        if (empty($name) || empty($email) || empty($check_in) || empty($check_out) || $guests === false || $guests < 1) {
            $response['message'] = 'Please fill in all fields correctly.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['message'] = 'Invalid email format.';
        } elseif (strtotime($check_in) >= strtotime($check_out)) {
            $response['message'] = 'Check-out date must be after the check-in date.';
        } else {
            // Check if the user exists, if not, user_id will be null
            $user_id = null;
            $stmt_user = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt_user->bind_param("s", $email);
            $stmt_user->execute();
            $stmt_user->bind_result($found_user_id);
            if ($stmt_user->fetch()) {
                $user_id = $found_user_id;
            }
            $stmt_user->close();

            // Admin-created bookings are confirmed by default
            $status = 'confirmed';

            $stmt = $conn->prepare("INSERT INTO bookings (user_id, name, email, check_in, check_out, guests, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("issssis", $user_id, $name, $email, $check_in, $check_out, $guests, $status);

            if ($stmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = 'Booking created successfully!';
            } else {
                $response['message'] = 'An error occurred while creating the booking.';
            }
            $stmt->close();
        }
    } else {
        $response['message'] = 'Missing required booking information.';
    }
} else {
    $response['message'] = 'Invalid request method.';
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
