<?php
include 'config.php';

header('Content-Type: application/json');

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(['status' => 'error', 'message' => 'Devi essere loggato per modificare il profilo.']);
    exit;
}

// Get user ID from session
$user_id = $_SESSION['id'];

// Get data from POST request
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');

// --- Validation ---
if (empty($name) || empty($email)) {
    echo json_encode(['status' => 'error', 'message' => 'Nome e email sono obbligatori.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Formato email non valido.']);
    exit;
}

// Check if the new email is already in use by another user
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
$stmt->bind_param("si", $email, $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Questa email è già in uso da un altro account.']);
    $stmt->close();
    exit;
}
$stmt->close();

// --- Update the database ---
$update_stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?");
$update_stmt->bind_param("sssi", $name, $email, $phone, $user_id);

if ($update_stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Profilo aggiornato con successo.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Errore durante l\'aggiornamento del profilo.']);
}

$update_stmt->close();
$conn->close();
?>