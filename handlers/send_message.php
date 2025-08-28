<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sender_id = $_SESSION['user_id'];
    $receiver_id = $_POST['receiver_id'];
    $message = trim($_POST['message']);
    $message_type = isset($_POST['message_type']) ? $_POST['message_type'] : 'text';
    
    if (empty($message) && $message_type === 'text') {
        echo json_encode(['status' => 'error', 'message' => 'Message cannot be empty']);
        exit;
    }
    
    try {
        $stmt = $conn->prepare("
            INSERT INTO messages (sender_id, receiver_id, message, message_type) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$sender_id, $receiver_id, $message, $message_type]);
        $message_id = $conn->lastInsertId();
        
        echo json_encode([
            'status' => 'success',
            'message_id' => $message_id
        ]);
    } catch(PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
