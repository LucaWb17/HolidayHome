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
    if (isset($_POST['receiver_id'], $_POST['subject'], $_POST['body'])) {
        $sender_id = $_SESSION['id']; // Admin's ID
        $receiver_id = filter_var($_POST['receiver_id'], FILTER_VALIDATE_INT);
        $subject = trim($_POST['subject']);
        $body = trim($_POST['body']);

        if ($receiver_id === false || empty($subject) || empty($body)) {
            $response['message'] = 'Please fill in all fields.';
        } else {
            $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, subject, body) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiss", $sender_id, $receiver_id, $subject, $body);

            if ($stmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = 'Message sent successfully!';
            } else {
                $response['message'] = 'An error occurred while sending the message.';
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
