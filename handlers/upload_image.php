<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $sender_id = $_SESSION['user_id'];
    $receiver_id = $_POST['receiver_id'];
    
    $file = $_FILES['image'];
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($file_extension, $allowed)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid file type']);
        exit;
    }
    
    $upload_dir = '../uploads/chat_images/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $filename = uniqid() . '.' . $file_extension;
    $filepath = $upload_dir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        try {
            $stmt = $conn->prepare("
                INSERT INTO messages (sender_id, receiver_id, message, message_type, media_url) 
                VALUES (?, ?, ?, 'image', ?)
            ");
            $relative_path = 'uploads/chat_images/' . $filename;
            $stmt->execute([$sender_id, $receiver_id, '', $relative_path]);
            
            echo json_encode([
                'status' => 'success',
                'image_url' => $relative_path
            ]);
        } catch(PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to upload file']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
