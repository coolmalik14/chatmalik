<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit;
}

try {
    // Update current user's status timestamp
    $stmt = $conn->prepare("UPDATE users SET status = 'Online' WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    
    // Set users to offline if they haven't updated their status in the last 30 seconds
    $stmt = $conn->prepare("
        UPDATE users 
        SET status = 'Offline' 
        WHERE status = 'Online' 
        AND id != ? 
        AND TIMESTAMPDIFF(SECOND, last_activity, NOW()) > 30
    ");
    $stmt->execute([$_SESSION['user_id']]);
    
    echo json_encode(['status' => 'success']);
} catch(PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
