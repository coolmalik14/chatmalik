<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $user_id = $_SESSION['user_id'];
    $other_user_id = $_GET['user_id'];
    $last_id = isset($_GET['last_id']) ? $_GET['last_id'] : 0;
    
    try {
        $stmt = $conn->prepare("
            SELECT m.*, u.username as sender_name 
            FROM messages m 
            JOIN users u ON m.sender_id = u.id 
            WHERE ((m.sender_id = ? AND m.receiver_id = ?) 
            OR (m.sender_id = ? AND m.receiver_id = ?))
            AND m.id > ?
            ORDER BY m.created_at ASC
        ");
        $stmt->execute([$user_id, $other_user_id, $other_user_id, $user_id, $last_id]);
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Mark messages as read
        $stmt = $conn->prepare("
            UPDATE messages 
            SET is_read = TRUE 
            WHERE sender_id = ? AND receiver_id = ? AND is_read = FALSE
        ");
        $stmt->execute([$other_user_id, $user_id]);
        
        echo json_encode([
            'status' => 'success',
            'messages' => $messages
        ]);
    } catch(PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
