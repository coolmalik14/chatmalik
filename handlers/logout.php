<?php
session_start();
require_once '../config/database.php';

if (isset($_SESSION['user_id'])) {
    // Update user status to offline
    try {
        $stmt = $conn->prepare("UPDATE users SET status = 'Offline' WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
    } catch(PDOException $e) {
        // Log error if needed
    }
}

// Destroy session
session_destroy();

// Redirect to login page
header("Location: ../login.php");
exit;
?>
