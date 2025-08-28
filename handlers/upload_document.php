<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['document'])) {
    $sender_id = $_SESSION['user_id'];
    $receiver_id = $_POST['receiver_id'];
    
    $file = $_FILES['document'];
    $allowed = ['pdf', 'doc', 'docx', 'txt', 'xls', 'xlsx', 'ppt', 'pptx'];
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    // Check file type
    if (!in_array($file_extension, $allowed)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid file type. Allowed: PDF, DOC, DOCX, TXT, XLS, XLSX, PPT, PPTX']);
        exit;
    }
    
    // Check file size (max 10MB)
    if ($file['size'] > 10 * 1024 * 1024) {
        echo json_encode(['status' => 'error', 'message' => 'File too large. Maximum size is 10MB']);
        exit;
    }
    
    $upload_dir = '../uploads/documents/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $filename = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file['name']);
    $filepath = $upload_dir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        try {
            $stmt = $conn->prepare("
                INSERT INTO messages (sender_id, receiver_id, message, message_type, media_url, file_name, file_size) 
                VALUES (?, ?, ?, 'document', ?, ?, ?)
            ");
            $relative_path = 'uploads/documents/' . $filename;
            $stmt->execute([$sender_id, $receiver_id, '', $relative_path, $file['name'], $file['size']]);
            
            echo json_encode([
                'status' => 'success',
                'document_url' => $relative_path,
                'file_name' => $file['name'],
                'file_size' => $file['size']
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
