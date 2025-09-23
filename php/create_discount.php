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
    if (isset($_POST['user_id'], $_POST['code'], $_POST['percentage'], $_POST['expiry_date'])) {
        $user_id = filter_var($_POST['user_id'], FILTER_VALIDATE_INT);
        $code = trim($_POST['code']);
        $percentage = filter_var($_POST['percentage'], FILTER_VALIDATE_FLOAT);
        $expiry_date = $_POST['expiry_date'];

        if ($user_id === false || empty($code) || $percentage === false || empty($expiry_date)) {
            $response['message'] = 'Please fill in all fields correctly.';
        } elseif ($percentage <= 0 || $percentage > 100) {
            $response['message'] = 'Percentage must be between 0 and 100.';
        } else {
            // Check if discount code already exists
            $stmt_check = $conn->prepare("SELECT id FROM discounts WHERE code = ?");
            $stmt_check->bind_param("s", $code);
            $stmt_check->execute();
            $stmt_check->store_result();

            if ($stmt_check->num_rows > 0) {
                $response['message'] = 'This discount code already exists.';
            } else {
                $stmt = $conn->prepare("INSERT INTO discounts (user_id, code, percentage, expiry_date) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("isds", $user_id, $code, $percentage, $expiry_date);

                if ($stmt->execute()) {
                    $response['status'] = 'success';
                    $response['message'] = 'Discount created successfully!';
                } else {
                    $response['message'] = 'An error occurred while creating the discount.';
                }
                $stmt->close();
            }
            $stmt_check->close();
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
