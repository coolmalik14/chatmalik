<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($data['theme'])) {
    $user_id = $_SESSION['user_id'];
    $theme = $data['theme'];
    
    // Validate theme value
    if (!in_array($theme, ['light', 'dark', 'auto'])) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid theme value']);
        exit;
    }
    
    try {
        // Insert or update user theme preference
        $stmt = $conn->prepare("
            INSERT INTO user_settings (user_id, theme, updated_at) 
            VALUES (?, ?, NOW()) 
            ON DUPLICATE KEY UPDATE 
            theme = VALUES(theme), 
            updated_at = NOW()
        ");
        $stmt->execute([$user_id, $theme]);
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Theme preference saved',
            'theme' => $theme
        ]);
        
    } catch(PDOException $e) {
        echo json_encode([
            'status' => 'error', 
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
