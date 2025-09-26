<?php
include 'config.php';

header('Content-Type: application/json');

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(['status' => 'error', 'message' => 'Devi essere loggato per cancellare i messaggi.']);
    exit;
}

// Get the message ID from the POST request
$message_id = isset($_POST['message_id']) ? filter_var($_POST['message_id'], FILTER_VALIDATE_INT) : null;

if (!$message_id) {
    echo json_encode(['status' => 'error', 'message' => 'ID del messaggio non valido.']);
    exit;
}

$user_id = $_SESSION['id'];

// Check if the message exists and if the user is authorized to delete it
$stmt = $conn->prepare("SELECT sender_id, receiver_id FROM messages WHERE id = ?");
$stmt->bind_param("i", $message_id);
$stmt->execute();
$result = $stmt->get_result();
$message = $result->fetch_assoc();
$stmt->close();

if (!$message) {
    echo json_encode(['status' => 'error', 'message' => 'Messaggio non trovato.']);
    exit;
}

// The user can delete the message if they are the sender or the receiver.
// For this implementation, we will allow deletion by either party.
// A more advanced implementation might "hide" it for one user instead of deleting it.
if ($message['sender_id'] != $user_id && $message['receiver_id'] != $user_id) {
    echo json_encode(['status' => 'error', 'message' => 'Non sei autorizzato a cancellare questo messaggio.']);
    exit;
}

// Delete the message from the database
$delete_stmt = $conn->prepare("DELETE FROM messages WHERE id = ?");
$delete_stmt->bind_param("i", $message_id);

if ($delete_stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Messaggio cancellato con successo.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Errore durante la cancellazione del messaggio.']);
}

$delete_stmt->close();
$conn->close();
?>