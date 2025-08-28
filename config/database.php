<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "chat_app_db";

try {
    $conn = new PDO("mysql:host=$host", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if not exists
    $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
    $conn->exec($sql);
    
    // Connect to the specific database
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create users table
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT PRIMARY KEY AUTO_INCREMENT,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        profile_pic VARCHAR(255) DEFAULT 'default.png',
        status ENUM('Online', 'Offline') DEFAULT 'Offline',
        last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $conn->exec($sql);
    
    // Create messages table with support for media
    $sql = "CREATE TABLE IF NOT EXISTS messages (
        id INT PRIMARY KEY AUTO_INCREMENT,
        sender_id INT NOT NULL,
        receiver_id INT NOT NULL,
        message TEXT,
        message_type ENUM('text', 'image', 'voice', 'document') NOT NULL DEFAULT 'text',
        media_url VARCHAR(255),
        file_name VARCHAR(255),
        file_size INT,
        is_read BOOLEAN DEFAULT FALSE,
        delivered BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (sender_id) REFERENCES users(id),
        FOREIGN KEY (receiver_id) REFERENCES users(id),
        INDEX idx_conversation (sender_id, receiver_id, created_at),
        INDEX idx_unread (receiver_id, is_read)
    )";
    $conn->exec($sql);
    
    // Create calls table for video/voice calls
    $sql = "CREATE TABLE IF NOT EXISTS calls (
        id INT PRIMARY KEY AUTO_INCREMENT,
        caller_id INT NOT NULL,
        receiver_id INT NOT NULL,
        call_type ENUM('video', 'audio') NOT NULL,
        status ENUM('pending', 'ongoing', 'ended', 'missed') NOT NULL,
        started_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        ended_at TIMESTAMP NULL,
        FOREIGN KEY (caller_id) REFERENCES users(id),
        FOREIGN KEY (receiver_id) REFERENCES users(id)
    )";
    $conn->exec($sql);
    
    // Create typing indicators table
    $sql = "CREATE TABLE IF NOT EXISTS typing_indicators (
        id INT PRIMARY KEY AUTO_INCREMENT,
        sender_id INT NOT NULL,
        receiver_id INT NOT NULL,
        typing BOOLEAN DEFAULT FALSE,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (sender_id) REFERENCES users(id),
        FOREIGN KEY (receiver_id) REFERENCES users(id),
        UNIQUE KEY unique_typing (sender_id, receiver_id)
    )";
    $conn->exec($sql);
    
    // Create user settings table
    $sql = "CREATE TABLE IF NOT EXISTS user_settings (
        id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT NOT NULL,
        theme ENUM('light', 'dark') DEFAULT 'light',
        notifications BOOLEAN DEFAULT TRUE,
        sound_enabled BOOLEAN DEFAULT TRUE,
        last_seen_privacy ENUM('everyone', 'contacts', 'nobody') DEFAULT 'everyone',
        profile_pic_privacy ENUM('everyone', 'contacts', 'nobody') DEFAULT 'everyone',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id),
        UNIQUE KEY unique_user_settings (user_id)
    )";
    $conn->exec($sql);
    
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
