<?php
include 'config.php';

header('Content-Type: application/json');

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(['status' => 'error', 'message' => 'Devi essere loggato per eseguire questa azione.']);
    exit;
}

// Get the message ID from the POST request
$message_id = isset($_POST['message_id']) ? filter_var($_POST['message_id'], FILTER_VALIDATE_INT) : null;

if (!$message_id) {
    echo json_encode(['status' => 'error', 'message' => 'ID del messaggio non valido.']);
    exit;
}

$user_id = $_SESSION['id'];

// --- Authorization Check ---
// Verify that the message belongs to the logged-in user before marking it as read
$auth_stmt = $conn->prepare("SELECT id FROM messages WHERE id = ? AND receiver_id = ?");
$auth_stmt->bind_param("ii", $message_id, $user_id);
$auth_stmt->execute();
$result = $auth_stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Non sei autorizzato a modificare questo messaggio.']);
    $auth_stmt->close();
    exit;
}
$auth_stmt->close();

// --- Update the database ---
$update_stmt = $conn->prepare("UPDATE messages SET is_read = 1 WHERE id = ?");
$update_stmt->bind_param("i", $message_id);

if ($update_stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Messaggio segnato come letto.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Errore durante l\'aggiornamento del messaggio.']);
}

$update_stmt->close();
$conn->close();
?>