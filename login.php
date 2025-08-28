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
    <title>Login - Chat Application</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/whatsapp-style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-box">
            <div class="auth-image">
                <h2>Welcome Back!</h2>
               <center> <h2 style="color:blue">Malik</h2></center>
            </div>
            <div class="auth-form">
                <h2>Sign In</h2>
                <?php
                if(isset($_GET['error'])) {
                    echo '<div class="error-message"><i class="fas fa-exclamation-circle"></i>' . htmlspecialchars($_GET['error']) . '</div>';
                }
                if(isset($_GET['success'])) {
                    echo '<div class="success-message"><i class="fas fa-check-circle"></i>' . htmlspecialchars($_GET['success']) . '</div>';
                }
                ?>
                <form action="handlers/login_handler.php" method="POST" id="login-form">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" name="email" id="email" placeholder="Enter your email" required>
                        <i class="fas fa-envelope input-icon"></i>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" placeholder="Enter your password" required>
                        <i class="fas fa-lock input-icon"></i>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    <button type="submit" class="auth-btn">Sign In</button>
                    <div class="auth-links">
                        <p><a href="forgot-password.php">Forgot Password?</a></p>
                        <p>Don't have an account? <a href="register.php">Sign Up</a></p>
                    </div>
                    <div class="theme-toggle-container" style="text-align: center; margin-top: 15px;">
                        <button type="button" class="theme-toggle" id="theme-toggle" title="Toggle theme" style="background: none; border: none; color: var(--wa-text-secondary); cursor: pointer; padding: 8px; border-radius: 50%; transition: all 0.3s;">
                            <i class="fas fa-moon"></i>
                        </button>
                    </div>
                    <div class="social-auth">
                        <p>Or continue with</p>
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
        // Toggle password visibility
        document.querySelector('.fa-lock').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                this.classList.remove('fa-lock');
                this.classList.add('fa-lock-open');
            } else {
                passwordInput.type = 'password';
                this.classList.remove('fa-lock-open');
                this.classList.add('fa-lock');
            }
        });
    </script>
</body>
</html>
