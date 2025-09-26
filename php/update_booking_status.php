<?php
require_once 'config.php';

// Verifica il token CSRF prima di procedere
verify_csrf_token();

$response = ['status' => 'error', 'message' => 'An unknown error occurred.'];

// Ensure user is logged in and is an admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    $response['message'] = 'Unauthorized action.';
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['booking_id'], $_POST['new_status'])) {
        $booking_id = filter_var($_POST['booking_id'], FILTER_VALIDATE_INT);
        $new_status = $_POST['new_status'];
        $allowed_statuses = ['confirmed', 'cancelled'];

        if ($booking_id === false) {
            $response['message'] = 'Invalid booking ID.';
        } elseif (!in_array($new_status, $allowed_statuses)) {
            $response['message'] = 'Invalid status provided.';
        } else {
            $stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
            $stmt->bind_param("si", $new_status, $booking_id);

            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    $response['status'] = 'success';
                    $response['message'] = 'Booking status updated successfully.';
                } else {
                    $response['message'] = 'Booking not found or status is already updated.';
                }
            } else {
                $response['message'] = 'Failed to update booking status.';
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
