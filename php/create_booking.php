<?php
require_once 'config.php';

$response = ['status' => 'error', 'message' => 'An unknown error occurred.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic validation
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
            // Check for overlapping bookings
            $overlap_stmt = $conn->prepare("SELECT id FROM bookings WHERE status = 'confirmed' AND ? < check_out AND ? > check_in");
            $overlap_stmt->bind_param("ss", $check_in, $check_out);
            $overlap_stmt->execute();
            $overlap_stmt->store_result();

            if ($overlap_stmt->num_rows > 0) {
                $response['message'] = 'Sorry, some of the selected dates are already booked. Please choose different dates.';
                $overlap_stmt->close();
            } else {
                $overlap_stmt->close();
                $user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
                $status = 'pending'; // Default status

                $stmt = $conn->prepare("INSERT INTO bookings (user_id, name, email, check_in, check_out, guests, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("issssis", $user_id, $name, $email, $check_in, $check_out, $guests, $status);

                if ($stmt->execute()) {
                    $response['status'] = 'success';
                    $response['message'] = 'Booking request sent successfully! We will contact you soon to confirm.';
                } else {
                    $response['message'] = 'An error occurred while processing your booking. Please try again.';
                }
                $stmt->close();
            }
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
