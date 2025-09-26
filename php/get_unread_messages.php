<?php
include 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in', 'unread_count' => 0]);
    exit;
}

$user_id = $_SESSION['id'];

// Count unread messages for the logged-in user
$stmt = $conn->prepare("SELECT COUNT(id) as unread_count FROM messages WHERE receiver_id = ? AND is_read = 0");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

echo json_encode(['status' => 'success', 'unread_count' => (int)$data['unread_count']]);

$conn->close();
?>