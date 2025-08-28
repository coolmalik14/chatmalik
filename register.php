<?php
session_start();
if(isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Chat Application</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/whatsapp-style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-box">
            <div class="auth-image">
            <h2>Welcome Back!</h2>
            <center> <h2 style="color:yellow">Malik</h2></center>
         </div>
            <div class="auth-form">
                <h2>Create Account</h2>
                <?php
                if(isset($_GET['error'])) {
                    echo '<div class="error-message"><i class="fas fa-exclamation-circle"></i>' . htmlspecialchars($_GET['error']) . '</div>';
                }
                if(isset($_GET['success'])) {
                    echo '<div class="success-message"><i class="fas fa-check-circle"></i>' . htmlspecialchars($_GET['success']) . '</div>';
                }
                ?>
                <form action="handlers/register_handler.php" method="POST" enctype="multipart/form-data" id="register-form">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" placeholder="Choose a username" required>
                        <i class="fas fa-user input-icon"></i>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" name="email" id="email" placeholder="Enter your email" required>
                        <i class="fas fa-envelope input-icon"></i>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" placeholder="Create a password" required>
                        <i class="fas fa-lock input-icon"></i>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm your password" required>
                        <i class="fas fa-lock input-icon"></i>
                    </div>
                    <div class="form-group">
                        <label for="profile_pic">Profile Picture (Optional)</label>
                        <div class="file-input-wrapper">
                            <input type="file" name="profile_pic" id="profile_pic" accept="image/*">
                            <div class="file-preview">
                                <img id="preview-image" src="uploads/profile/default.png" alt="Preview">
                            </div>
                        </div>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" id="terms" name="terms" required>
                        <label for="terms">I agree to the Terms & Conditions</label>
                    </div>
                    <button type="submit" class="auth-btn">Create Account</button>
                    <div class="auth-links">
                        <p>Already have an account? <a href="login.php">Sign In</a></p>
                    </div>
                    <div class="theme-toggle-container" style="text-align: center; margin-top: 15px;">
                        <button type="button" class="theme-toggle" id="theme-toggle" title="Toggle theme" style="background: none; border: none; color: var(--wa-text-secondary); cursor: pointer; padding: 8px; border-radius: 50%; transition: all 0.3s;">
                            <i class="fas fa-moon"></i>
                        </button>
                    </div>
                    <div class="social-auth">
                        <p>Or register with</p>
                        <div class="social-buttons">
                            <button type="button" class="social-btn">
                                <i class="fab fa-google"></i>
                            </button>
                            <button type="button" class="social-btn">
                                <i class="fab fa-facebook-f"></i>
                            </button>
                            <button type="button" class="social-btn">
                                <i class="fab fa-twitter"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="assets/js/auth.js"></script>
    <script src="assets/js/theme.js"></script>
    <script>
        // Password visibility toggle
        document.querySelectorAll('.fa-lock').forEach(icon => {
            icon.addEventListener('click', function() {
                const input = this.parentElement.querySelector('input');
                if (input.type === 'password') {
                    input.type = 'text';
                    this.classList.remove('fa-lock');
                    this.classList.add('fa-lock-open');
                } else {
                    input.type = 'password';
                    this.classList.remove('fa-lock-open');
                    this.classList.add('fa-lock');
                }
            });
        });

        // Image preview
        document.getElementById('profile_pic').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-image').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });

        // Password match validation
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            
            if (password !== confirmPassword) {
                this.setCustomValidity('Passwords do not match');
            } else {
                this.setCustomValidity('');
            }
        });
    </script>
</body>
</html>
