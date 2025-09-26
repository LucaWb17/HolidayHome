<?php
require_once 'config.php';

// Verifica il token CSRF prima di procedere
verify_csrf_token();

$response = ['status' => 'error', 'message' => 'An unknown error occurred.'];

// Ensure user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    $response['message'] = 'You must be logged in to upload a new picture.';
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

if (isset($_FILES['profile_image'])) {
    $file = $_FILES['profile_image'];

    // --- File Validation ---
    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $response['message'] = 'Error during file upload. Please try again.';
    } else {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 5 * 1024 * 1024; // 5 MB

        if (!in_array($file['type'], $allowed_types)) {
            $response['message'] = 'Invalid file type. Only JPG, PNG, and GIF are allowed.';
        } elseif ($file['size'] > $max_size) {
            $response['message'] = 'File is too large. Maximum size is 5 MB.';
        } else {
            // --- File Processing ---
            $upload_dir = '../uploads/avatars/';
            // Create a unique filename to prevent overwrites
            $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $unique_filename = uniqid('avatar_', true) . '.' . $file_extension;
            $upload_path = $upload_dir . $unique_filename;

            if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                // --- Update Database and Delete Old File ---
                $user_id = $_SESSION['id'];
                $db_path = 'uploads/avatars/' . $unique_filename;

                // 1. Get the old image path before updating
                $old_path_stmt = $conn->prepare("SELECT profile_image_path FROM users WHERE id = ?");
                $old_path_stmt->bind_param("i", $user_id);
                $old_path_stmt->execute();
                $old_path_stmt->bind_result($old_image_path);
                $old_path_stmt->fetch();
                $old_path_stmt->close();

                // 2. Update the database with the new path
                $stmt = $conn->prepare("UPDATE users SET profile_image_path = ? WHERE id = ?");
                $stmt->bind_param("si", $db_path, $user_id);

                if ($stmt->execute()) {
                    // 3. If DB update is successful, delete the old file
                    if ($old_image_path && $old_image_path !== 'uploads/avatars/default.png' && file_exists('../' . $old_image_path)) {
                        unlink('../' . $old_image_path);
                    }
                    $response['status'] = 'success';
                    $response['message'] = 'Profile picture updated successfully!';
                    $response['new_image_path'] = $db_path;
                } else {
                    $response['message'] = 'Failed to update database record.';
                    // Clean up the uploaded file if the DB update fails
                    unlink($upload_path);
                }
                $stmt->close();
            } else {
                $response['message'] = 'Failed to move uploaded file.';
            }
        }
    }
} else {
    $response['message'] = 'No file was uploaded.';
}

$conn->close();
header('Content-Type: application/json');
echo json_encode($response);
?>