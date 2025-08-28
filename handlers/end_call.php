<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    
    try {
        if (isset($data['call_id'])) {
            // End specific call by ID
            $call_id = $data['call_id'];
            $status = isset($data['status']) ? $data['status'] : 'ended';
            
            $stmt = $conn->prepare("
                UPDATE calls 
                SET status = ?, 
                    ended_at = CURRENT_TIMESTAMP 
                WHERE id = ? 
                AND (caller_id = ? OR receiver_id = ?)
                AND status IN ('pending', 'ongoing')
            ");
            $stmt->execute([$status, $call_id, $user_id, $user_id]);
            
        } elseif (isset($data['receiver_id'])) {
            // End call by receiver ID (legacy support)
            $other_user_id = $data['receiver_id'];
            
            $stmt = $conn->prepare("
                UPDATE calls 
                SET status = 'ended', 
                    ended_at = CURRENT_TIMESTAMP 
                WHERE (caller_id = ? AND receiver_id = ?) 
                   OR (caller_id = ? AND receiver_id = ?)
                   AND status IN ('pending', 'ongoing')
            ");
            $stmt->execute([$user_id, $other_user_id, $other_user_id, $user_id]);
        }
        
        // Send WebSocket notification about call end
        $ws_message = json_encode([
            'type' => 'call_ended',
            'user_id' => $user_id
        ]);
        
        // In a real application, you would send this through WebSocket server
        echo json_encode(['status' => 'success']);
        
    } catch(PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
