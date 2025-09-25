<?php
require_once 'config.php';

$response = ['status' => 'error', 'message' => 'An unknown error occurred.'];

// Ensure user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    $response['message'] = 'You must be logged in to send a message.';
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['subject'], $_POST['body'])) {
        $sender_id = $_SESSION['id']; // User's ID
        $subject = trim($_POST['subject']);
        $body = trim($_POST['body']);

        // Find the admin user's ID. For this project, we assume the first admin found is the recipient.
        // A more complex system might have a dedicated support inbox or a specific admin ID.
        $admin_result = $conn->query("SELECT id FROM users WHERE role = 'admin' LIMIT 1");
        if ($admin_result && $admin_result->num_rows > 0) {
            $admin = $admin_result->fetch_assoc();
            $receiver_id = $admin['id'];

            if (empty($subject) || empty($body)) {
                $response['message'] = 'Please fill in all fields.';
            } else {
                $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, subject, body) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("iiss", $sender_id, $receiver_id, $subject, $body);

                if ($stmt->execute()) {
                    $response['status'] = 'success';
                    $response['message'] = 'Message sent successfully to admin!';
                } else {
                    $response['message'] = 'An error occurred while sending the message.';
                }
                $stmt->close();
            }
        } else {
            $response['message'] = 'Could not find an admin account to send the message to.';
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