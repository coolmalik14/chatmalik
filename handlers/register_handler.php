<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validation
    $errors = [];
    
    if (empty($username)) {
        $errors[] = 'Username is required';
    } elseif (strlen($username) < 3) {
        $errors[] = 'Username must be at least 3 characters long';
    }
    
    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format';
    }
    
    if (empty($password)) {
        $errors[] = 'Password is required';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters long';
    }
    
    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match';
    }
    
    if (!empty($errors)) {
        $error_message = implode(', ', $errors);
        $content_type = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
        if (strpos($content_type, 'application/json') !== false || isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => $error_message]);
        } else {
            header("Location: ../register.php?error=" . urlencode($error_message));
        }
        exit;
    }
    
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);
    
    try {
        // Check if username already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->rowCount() > 0) {
            $error_msg = 'Username already exists';
            $content_type = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
            if (strpos($content_type, 'application/json') !== false || isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                header('Content-Type: application/json');
                echo json_encode(['status' => 'error', 'message' => $error_msg]);
            } else {
                header("Location: ../register.php?error=" . urlencode($error_msg));
            }
            exit;
        }
        
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $error_msg = 'Email already exists';
            $content_type = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
            if (strpos($content_type, 'application/json') !== false || isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                header('Content-Type: application/json');
                echo json_encode(['status' => 'error', 'message' => $error_msg]);
            } else {
                header("Location: ../register.php?error=" . urlencode($error_msg));
            }
            exit;
        }
        
        // Handle profile picture upload
        $profile_pic = 'default.png';
        if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $file_extension = strtolower(pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION));
            
            if (!in_array($file_extension, $allowed)) {
                $error_msg = 'Invalid file type. Please upload JPG, JPEG, PNG, or GIF files only';
                $content_type = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
                if (strpos($content_type, 'application/json') !== false || isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                    header('Content-Type: application/json');
                    echo json_encode(['status' => 'error', 'message' => $error_msg]);
                } else {
                    header("Location: ../register.php?error=" . urlencode($error_msg));
                }
                exit;
            }
            
            $upload_dir = '../uploads/profile/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $profile_pic = uniqid() . '.' . $file_extension;
            if (!move_uploaded_file($_FILES['profile_pic']['tmp_name'], $upload_dir . $profile_pic)) {
                $profile_pic = 'default.png';
            }
        }
        
        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, profile_pic) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $email, $password_hashed, $profile_pic]);
        
        $success_msg = 'Registration successful! You can now login';
        $content_type = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
        if (strpos($content_type, 'application/json') !== false || isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'success', 'message' => $success_msg]);
        } else {
            header("Location: ../login.php?success=" . urlencode($success_msg));
        }
        
    } catch(PDOException $e) {
        $error_msg = 'Database error occurred. Please try again';
        $content_type = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
        if (strpos($content_type, 'application/json') !== false || isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => $error_msg]);
        } else {
            header("Location: ../register.php?error=" . urlencode($error_msg));
        }
    }
} else {
    header("Location: ../register.php?error=Invalid request method");
}
?>
