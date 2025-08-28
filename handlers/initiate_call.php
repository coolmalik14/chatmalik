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
    $caller_id = $_SESSION['user_id'];
    $receiver_id = $data['receiver_id'];
    $call_type = $data['call_type'];
    
    try {
        // Insert new call record
        $stmt = $conn->prepare("
            INSERT INTO calls (caller_id, receiver_id, call_type, status) 
            VALUES (?, ?, ?, 'pending')
        ");
        $stmt->execute([$caller_id, $receiver_id, $call_type]);
        $call_id = $conn->lastInsertId();
        
        // Get caller information
        $stmt = $conn->prepare("SELECT username, profile_pic FROM users WHERE id = ?");
        $stmt->execute([$caller_id]);
        $caller = $stmt->fetch();
        
        // Send WebSocket notification to receiver
        $ws_message = json_encode([
            'type' => 'incoming_call',
            'call_id' => $call_id,
            'caller_id' => $caller_id,
            'caller_name' => $caller['username'],
            'caller_pic' => $caller['profile_pic'],
            'call_type' => $call_type
        ]);
        
        // In a real application, you would send this through WebSocket server
        // For now, we'll just return success
        echo json_encode(['status' => 'success', 'call_id' => $call_id]);
        
    } catch(PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
