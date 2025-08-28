<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($data['receiver_id'])) {
    $sender_id = $_SESSION['user_id'];
    $receiver_id = $data['receiver_id'];
    $typing = isset($data['typing']) ? $data['typing'] : false;
    
    try {
        if ($typing) {
            // Insert or update typing indicator
            $stmt = $conn->prepare("
                INSERT INTO typing_indicators (sender_id, receiver_id, typing, updated_at) 
                VALUES (?, ?, 1, NOW()) 
                ON DUPLICATE KEY UPDATE typing = 1, updated_at = NOW()
            ");
            $stmt->execute([$sender_id, $receiver_id]);
        } else {
            // Remove typing indicator
            $stmt = $conn->prepare("
                UPDATE typing_indicators 
                SET typing = 0, updated_at = NOW() 
                WHERE sender_id = ? AND receiver_id = ?
            ");
            $stmt->execute([$sender_id, $receiver_id]);
        }
        
        echo json_encode(['status' => 'success']);
        
    } catch(PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
