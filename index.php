<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WhatsApp Clone - Chat Application</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/whatsapp-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="light-theme">
    <div class="whatsapp-container">
        <div class="chat-app">
            <!-- Sidebar with user list -->
            <div class="sidebar">
                <!-- Header -->
                <div class="sidebar-header">
                    <div class="user-profile">
                        <div class="profile-img-container">
                            <img src="uploads/profile/<?php echo $_SESSION['profile_pic']; ?>" alt="Profile" class="profile-img">
                            <div class="online-indicator"></div>
                        </div>
                        <div class="user-info">
                            <h3><?php echo $_SESSION['username']; ?></h3>
                            <p class="user-status">Available</p>
                        </div>
                    </div>
                    <div class="header-actions">
                        <button class="action-btn" id="new-chat" title="New Chat">
                            <i class="fas fa-comment-alt"></i>
                        </button>
                        <div class="dropdown">
                            <button class="action-btn dropdown-toggle" title="Menu">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a href="#" id="profile-settings"><i class="fas fa-user"></i> Profile</a>
                                <a href="#" class="theme-toggle" data-theme-toggle><i class="fas fa-moon"></i> <span class="toggle-text">Dark Mode</span></a>
                                <a href="#" id="settings"><i class="fas fa-cog"></i> Settings</a>
                                <div class="dropdown-divider"></div>
                                <a href="handlers/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Search -->
                <div class="search-container">
                    <div class="search-input-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" id="search-user" placeholder="Search or start new chat" class="search-input">
                        <button class="search-filter" id="search-filter">
                            <i class="fas fa-filter"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Chat List -->
                <div class="chat-list">
                    <div class="chat-list-header">
                        <div class="chat-filter-tabs">
                            <button class="filter-tab active" data-filter="all">All</button>
                            <button class="filter-tab" data-filter="unread">Unread</button>
                            <button class="filter-tab" data-filter="groups">Groups</button>
                    </div>
                </div>
                <div class="users-list">
                    <!-- Users will be loaded here dynamically -->
                    </div>
                </div>
            </div>

            <!-- Main chat area -->
            <div class="chat-area">
                <!-- Chat Header -->
                <div class="chat-header">
                    <div class="chat-header-info">
                        <div class="back-btn" id="back-to-chats">
                            <i class="fas fa-arrow-left"></i>
                        </div>
                        <div class="contact-info">
                            <div class="contact-avatar">
                        <img src="uploads/profile/default.png" alt="" id="selected-user-img">
                                <div class="contact-status-indicator" id="contact-status-dot"></div>
                            </div>
                            <div class="contact-details">
                            <h4 id="selected-user-name">Select a user to start chat</h4>
                                <span id="selected-user-status" class="contact-status">Click on a contact to start chatting</span>
                                <span id="typing-indicator" class="typing-indicator" style="display: none;">
                                    <i class="fas fa-circle"></i>
                                    <i class="fas fa-circle"></i>
                                    <i class="fas fa-circle"></i>
                                    typing...
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="chat-actions">
                        <button class="action-btn" id="search-messages" title="Search Messages">
                            <i class="fas fa-search"></i>
                        </button>
                        <button class="action-btn voice-call-btn" title="Voice Call">
                            <i class="fas fa-phone"></i>
                        </button>
                        <button class="action-btn video-call-btn" title="Video Call">
                            <i class="fas fa-video"></i>
                        </button>
                        <div class="dropdown">
                            <button class="action-btn dropdown-toggle" title="More">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a href="#" id="view-contact"><i class="fas fa-user"></i> View Contact</a>
                                <a href="#" id="media-gallery"><i class="fas fa-images"></i> Media</a>
                                <a href="#" id="clear-chat"><i class="fas fa-trash"></i> Clear Chat</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Messages Container -->
                <div class="messages-container">
                    <div class="messages-background"></div>
                    <div class="chat-messages" id="chat-messages">
                        <div class="welcome-message">
                            <div class="welcome-content">
                                <i class="fas fa-comments"></i>
                                <h3>Welcome to Chat</h3>
                                <p>Select a contact to start messaging</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Message Input -->
                <div class="message-input-container">
                    <div class="input-wrapper">
                        <button class="media-btn" id="attach-media" title="Attach Media">
                            <i class="fas fa-paperclip"></i>
                                </button>
                        <div class="text-input-wrapper">
                            <input type="text" id="message-input" placeholder="Type a message" class="message-input">
                            <button class="emoji-btn" id="emoji-picker" title="Emoji">
                                <i class="fas fa-smile"></i>
                                </button>
                        </div>
                        <button class="voice-btn" id="voice-record" title="Voice Message">
                            <i class="fas fa-microphone"></i>
                        </button>
                        <button class="send-btn" id="send-message" title="Send Message">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                    
                    <!-- Media Attachment Menu -->
                    <div class="media-menu" id="media-menu">
                        <div class="media-option" id="attach-image">
                            <div class="media-icon camera">
                                <i class="fas fa-camera"></i>
                            </div>
                            <span>Camera</span>
                        </div>
                        <div class="media-option" id="attach-gallery">
                            <div class="media-icon gallery">
                                <i class="fas fa-image"></i>
                            </div>
                            <span>Gallery</span>
                        </div>
                        <div class="media-option" id="attach-document">
                            <div class="media-icon document">
                                <i class="fas fa-file"></i>
                            </div>
                            <span>Document</span>
                        </div>
                    </div>
                    
                    <!-- Hidden File Inputs -->
                    <input type="file" id="image-input" accept="image/*" style="display: none;">
                    <input type="file" id="document-input" accept=".pdf,.doc,.docx,.txt" style="display: none;">
                    
                    <!-- Emoji Picker -->
                    <div class="emoji-picker" id="emoji-picker-container">
                        <div class="emoji-categories">
                            <button class="emoji-category active" data-category="smileys">😊</button>
                            <button class="emoji-category" data-category="people">👋</button>
                            <button class="emoji-category" data-category="animals">🐱</button>
                            <button class="emoji-category" data-category="food">🍕</button>
                            <button class="emoji-category" data-category="activities">⚽</button>
                            <button class="emoji-category" data-category="travel">✈️</button>
                            <button class="emoji-category" data-category="objects">💡</button>
                            <button class="emoji-category" data-category="symbols">❤️</button>
                        </div>
                        <div class="emoji-grid" id="emoji-grid">
                            <!-- Emojis will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Call Modal -->
    <div class="call-modal">
        <div class="call-box">
            <div class="caller-info">
                <img src="uploads/profile/default.png" alt="" id="caller-img">
                <h3 id="caller-name">User Name</h3>
                <p class="call-status">Incoming call...</p>
            </div>
            <div class="call-actions">
                <button class="call-action-btn accept-call">
                    <i class="fas fa-phone"></i>
                </button>
                <button class="call-action-btn decline-call">
                    <i class="fas fa-phone-slash"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Video Call Container -->
    <div class="video-container">
        <video class="main-video" id="remote-video" autoplay playsinline></video>
        <video class="pip-video" id="local-video" autoplay playsinline muted></video>
        <div class="video-controls">
            <button class="video-control-btn toggle-video">
                <i class="fas fa-video"></i>
            </button>
            <button class="video-control-btn toggle-audio">
                <i class="fas fa-microphone"></i>
            </button>
            <button class="video-control-btn end-call">
                <i class="fas fa-phone-slash"></i>
            </button>
        </div>
    </div>

    <script src="assets/js/webrtc.js"></script>
    <script src="assets/js/media-handler.js"></script>
    <script src="assets/js/whatsapp-chat.js"></script>
    <script src="assets/js/theme.js"></script>
</body>
</html>
