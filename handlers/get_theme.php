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
    
    try {
        // Get user's theme preference
        $stmt = $conn->prepare("
            SELECT theme, notifications, sound_enabled, last_seen_privacy, profile_pic_privacy
            FROM user_settings 
            WHERE user_id = ?
        ");
        $stmt->execute([$user_id]);
        $settings = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($settings) {
            echo json_encode([
                'status' => 'success',
                'settings' => $settings
            ]);
        } else {
            // Return default settings if none exist
            echo json_encode([
                'status' => 'success',
                'settings' => [
                    'theme' => 'light',
                    'notifications' => true,
                    'sound_enabled' => true,
                    'last_seen_privacy' => 'everyone',
                    'profile_pic_privacy' => 'everyone'
                ]
            ]);
        }
        
    } catch(PDOException $e) {
        echo json_encode([
            'status' => 'error', 
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
