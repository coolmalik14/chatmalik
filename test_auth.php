<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .test-result { padding: 10px; margin: 10px 0; border-radius: 5px; }
        .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .info { background-color: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
    </style>
</head>
<body>
    <h1>Chat Application - Authentication Test</h1>
    
    <?php
    require_once 'config/database.php';
    
    echo '<div class="test-result info">Testing database connection...</div>';
    
    try {
        // Test database connection
        $stmt = $conn->query("SELECT COUNT(*) as count FROM users");
        $result = $stmt->fetch();
        echo '<div class="test-result success">✓ Database connection successful! Users table has ' . $result['count'] . ' records.</div>';
        
        // Check if uploads directories exist
        $dirs = ['uploads/profile', 'uploads/chat_images', 'uploads/voice_messages'];
        foreach ($dirs as $dir) {
            if (is_dir($dir)) {
                echo '<div class="test-result success">✓ Directory exists: ' . $dir . '</div>';
            } else {
                echo '<div class="test-result error">✗ Directory missing: ' . $dir . '</div>';
            }
        }
        
        // Check if default profile image exists
        if (file_exists('uploads/profile/default.png')) {
            echo '<div class="test-result success">✓ Default profile image exists</div>';
        } else {
            echo '<div class="test-result error">✗ Default profile image missing</div>';
        }
        
        // Check handler files
        $handlers = [
            'handlers/login_handler.php',
            'handlers/register_handler.php',
            'handlers/logout.php',
            'handlers/get_users.php'
        ];
        
        foreach ($handlers as $handler) {
            if (file_exists($handler)) {
                echo '<div class="test-result success">✓ Handler exists: ' . $handler . '</div>';
            } else {
                echo '<div class="test-result error">✗ Handler missing: ' . $handler . '</div>';
            }
        }
        
    } catch (PDOException $e) {
        echo '<div class="test-result error">✗ Database connection failed: ' . $e->getMessage() . '</div>';
    }
    ?>
    
    <h2>Quick Test Links</h2>
    <p><a href="register.php">Test Registration Page</a></p>
    <p><a href="login.php">Test Login Page</a></p>
    <p><a href="index.php">Test Main Chat Page</a></p>
    
    <h2>Database Structure Test</h2>
    <?php
    try {
        // Check table structure
        $tables = ['users', 'messages', 'calls'];
        foreach ($tables as $table) {
            $stmt = $conn->query("DESCRIBE $table");
            echo '<div class="test-result success">✓ Table exists: ' . $table . '</div>';
        }
    } catch (PDOException $e) {
        echo '<div class="test-result error">✗ Table structure error: ' . $e->getMessage() . '</div>';
    }
    ?>
    
    <p><em>Delete this file (test_auth.php) after testing is complete.</em></p>
</body>
</html>
