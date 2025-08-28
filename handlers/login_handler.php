<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['profile_pic'] = $user['profile_pic'];
            
            // Update user status to online
            $stmt = $conn->prepare("UPDATE users SET status = 'Online' WHERE id = ?");
            $stmt->execute([$user['id']]);
            
            // Check if request expects JSON response
            $content_type = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
            if (strpos($content_type, 'application/json') !== false || isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                header('Content-Type: application/json');
                echo json_encode(['status' => 'success']);
                exit;
            } else {
                header("Location: ../index.php");
                exit;
            }
        } else {
            $content_type = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
            if (strpos($content_type, 'application/json') !== false || isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                header('Content-Type: application/json');
                echo json_encode(['status' => 'error', 'message' => 'Invalid email or password']);
                exit;
            } else {
                header("Location: ../login.php?error=Invalid email or password");
                exit;
            }
        }
    } catch(PDOException $e) {
        $content_type = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
        if (strpos($content_type, 'application/json') !== false || isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
            exit;
        } else {
            header("Location: ../login.php?error=Database error occurred");
            exit;
        }
    }
} else {
    header("Location: ../login.php?error=Invalid request method");
}
?>
