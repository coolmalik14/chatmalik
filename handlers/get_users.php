<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $current_user_id = $_SESSION['user_id'];
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    
    try {
        $sql = "
            SELECT 
                u.id, 
                u.username, 
                u.profile_pic, 
                u.status,
                u.last_activity,
                (SELECT COUNT(*) FROM messages 
                 WHERE sender_id = u.id 
                 AND receiver_id = ? 
                 AND is_read = FALSE) as unread_count,
                (SELECT m.message FROM messages m
                 WHERE (m.sender_id = u.id AND m.receiver_id = ?) 
                    OR (m.sender_id = ? AND m.receiver_id = u.id)
                 ORDER BY m.created_at DESC LIMIT 1) as last_message,
                (SELECT m.created_at FROM messages m
                 WHERE (m.sender_id = u.id AND m.receiver_id = ?) 
                    OR (m.sender_id = ? AND m.receiver_id = u.id)
                 ORDER BY m.created_at DESC LIMIT 1) as last_message_time,
                (SELECT m.message_type FROM messages m
                 WHERE (m.sender_id = u.id AND m.receiver_id = ?) 
                    OR (m.sender_id = ? AND m.receiver_id = u.id)
                 ORDER BY m.created_at DESC LIMIT 1) as last_message_type
            FROM users u
            WHERE u.id != ?
        ";
        $params = [$current_user_id, $current_user_id, $current_user_id, $current_user_id, $current_user_id, $current_user_id, $current_user_id, $current_user_id];
        
        if (!empty($search)) {
            $sql .= " AND u.username LIKE ?";
            $params[] = "%$search%";
        }
        
        $sql .= " ORDER BY u.status = 'Online' DESC, last_message_time DESC, u.username ASC";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Format last message for display
        foreach ($users as &$user) {
            if ($user['last_message_type'] === 'image') {
                $user['last_message'] = '📷 Photo';
            } elseif ($user['last_message_type'] === 'voice') {
                $user['last_message'] = '🎤 Voice message';
            } elseif ($user['last_message_type'] === 'document') {
                $user['last_message'] = '📄 Document';
            } elseif (empty($user['last_message'])) {
                $user['last_message'] = 'No messages yet';
            } elseif (strlen($user['last_message']) > 30) {
                $user['last_message'] = substr($user['last_message'], 0, 30) . '...';
            }
        }
        
        echo json_encode($users);
    } catch(PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
