<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Theme Demo - Light & Dark Mode</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/whatsapp-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .demo-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }
        
        .theme-demo-header {
            text-align: center;
            padding: 40px 20px;
            background: var(--wa-bg-secondary);
            border-radius: 12px;
            box-shadow: var(--wa-shadow-medium);
            margin-bottom: 30px;
        }
        
        .theme-demo-header h1 {
            font-size: 2.5rem;
            color: var(--wa-text-primary);
            margin-bottom: 10px;
        }
        
        .theme-demo-header p {
            color: var(--wa-text-secondary);
            font-size: 1.2rem;
        }
        
        .theme-controls {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 30px 0;
            flex-wrap: wrap;
        }
        
        .theme-btn {
            padding: 12px 24px;
            border: 2px solid var(--wa-green);
            background: var(--wa-green);
            color: white;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        
        .theme-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--wa-shadow-medium);
        }
        
        .theme-btn.secondary {
            background: transparent;
            color: var(--wa-green);
        }
        
        .theme-btn.secondary:hover {
            background: var(--wa-green);
            color: white;
        }
        
        .demo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
            margin: 40px 0;
        }
        
        .demo-card {
            background: var(--wa-bg-secondary);
            border-radius: 12px;
            padding: 25px;
            box-shadow: var(--wa-shadow-medium);
            transition: all 0.3s;
        }
        
        .demo-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--wa-shadow-heavy);
        }
        
        .demo-card h3 {
            color: var(--wa-text-primary);
            margin-bottom: 15px;
            font-size: 1.3rem;
        }
        
        .color-palette {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }
        
        .color-item {
            text-align: center;
            padding: 15px;
            border-radius: 8px;
            color: white;
            font-size: 12px;
            font-weight: 500;
        }
        
        .color-primary { background: var(--wa-bg-primary); }
        .color-secondary { background: var(--wa-bg-secondary); }
        .color-panel { background: var(--wa-bg-panel); }
        .color-chat { background: var(--wa-bg-chat); }
        .color-green { background: var(--wa-green); }
        .color-teal { background: var(--wa-teal); }
        
        .feature-showcase {
            background: var(--wa-bg-panel);
            border-radius: 12px;
            padding: 30px;
            margin: 30px 0;
        }
        
        .feature-showcase h2 {
            color: var(--wa-text-primary);
            text-align: center;
            margin-bottom: 30px;
        }
        
        .features-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .feature-item {
            background: var(--wa-bg-secondary);
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid var(--wa-green);
        }
        
        .feature-item h4 {
            color: var(--wa-text-primary);
            margin-bottom: 10px;
        }
        
        .feature-item p {
            color: var(--wa-text-secondary);
            font-size: 14px;
        }
        
        .keyboard-shortcuts {
            background: var(--wa-message-out);
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .keyboard-shortcuts h4 {
            margin-bottom: 15px;
        }
        
        .shortcut-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 5px 0;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }
        
        .shortcut-item:last-child {
            border-bottom: none;
        }
        
        .shortcut-key {
            background: rgba(255,255,255,0.2);
            padding: 4px 8px;
            border-radius: 4px;
            font-family: monospace;
            font-size: 12px;
        }
        
        @media (max-width: 768px) {
            .demo-container {
                margin: 10px;
                padding: 15px;
            }
            
            .theme-demo-header h1 {
                font-size: 2rem;
            }
            
            .theme-controls {
                flex-direction: column;
                align-items: center;
            }
            
            .demo-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }
    </style>
</head>
<body class="light-theme">
    <div class="demo-container">
        <div class="theme-demo-header">
            <h1>🌙 Theme System Demo</h1>
            <p>Experience the power of light and dark mode switching</p>
        </div>
        
        <div class="theme-controls">
            <button class="theme-btn" onclick="setTheme('light')">
                <i class="fas fa-sun"></i>
                Light Mode
            </button>
            <button class="theme-btn secondary" onclick="setTheme('dark')">
                <i class="fas fa-moon"></i>
                Dark Mode
            </button>
            <button class="theme-btn secondary" onclick="toggleTheme()">
                <i class="fas fa-adjust"></i>
                Toggle Theme
            </button>
            <button class="theme-btn secondary" onclick="useSystemTheme()">
                <i class="fas fa-desktop"></i>
                System Theme
            </button>
        </div>
        
        <div class="demo-grid">
            <div class="demo-card">
                <h3>🎨 Color Palette</h3>
                <p style="color: var(--wa-text-secondary); margin-bottom: 20px;">
                    Dynamic colors that adapt to the current theme
                </p>
                <div class="color-palette">
                    <div class="color-item color-primary">Primary</div>
                    <div class="color-item color-secondary">Secondary</div>
                    <div class="color-item color-panel">Panel</div>
                    <div class="color-item color-chat">Chat BG</div>
                    <div class="color-item color-green">Green</div>
                    <div class="color-item color-teal">Teal</div>
                </div>
            </div>
            
            <div class="demo-card">
                <h3>💬 Message Preview</h3>
                <div style="background: var(--wa-bg-chat); padding: 20px; border-radius: 8px; margin: 15px 0;">
                    <div style="background: var(--wa-message-out); color: var(--wa-text-primary); padding: 10px 15px; border-radius: 8px; border-bottom-right-radius: 2px; margin-bottom: 10px; max-width: 70%; margin-left: auto;">
                        <div>Hey! How's the new theme system? 😊</div>
                        <div style="font-size: 11px; opacity: 0.7; text-align: right; margin-top: 5px;">
                            12:34 PM <i class="fas fa-check-double" style="color: #34b7f1;"></i>
                        </div>
                    </div>
                    <div style="background: var(--wa-message-in); color: var(--wa-text-primary); padding: 10px 15px; border-radius: 8px; border-bottom-left-radius: 2px; max-width: 70%; box-shadow: var(--wa-shadow-light);">
                        <div>It looks amazing! The dark mode is so smooth 🌙</div>
                        <div style="font-size: 11px; opacity: 0.7; margin-top: 5px;">12:35 PM</div>
                    </div>
                </div>
            </div>
            
            <div class="demo-card">
                <h3>⚙️ Theme Features</h3>
                <ul style="color: var(--wa-text-secondary); line-height: 1.6;">
                    <li>✓ Instant theme switching</li>
                    <li>✓ Persistent user preferences</li>
                    <li>✓ System theme detection</li>
                    <li>✓ Smooth transitions</li>
                    <li>✓ Mobile responsive</li>
                    <li>✓ Keyboard shortcuts</li>
                    <li>✓ Visual notifications</li>
                </ul>
            </div>
            
            <div class="demo-card">
                <h3>🔧 Technical Details</h3>
                <div style="color: var(--wa-text-secondary); font-size: 14px;">
                    <p><strong>Storage:</strong> LocalStorage + Database</p>
                    <p><strong>Detection:</strong> CSS Media Queries</p>
                    <p><strong>Transitions:</strong> CSS Variables</p>
                    <p><strong>Persistence:</strong> User Settings Table</p>
                    <p><strong>Compatibility:</strong> All Modern Browsers</p>
                </div>
            </div>
        </div>
        
        <div class="feature-showcase">
            <h2>🚀 Advanced Features</h2>
            <div class="features-list">
                <div class="feature-item">
                    <h4>Auto Theme Detection</h4>
                    <p>Automatically detects and applies your system's preferred color scheme</p>
                </div>
                <div class="feature-item">
                    <h4>Persistent Preferences</h4>
                    <p>Your theme choice is saved and restored across sessions</p>
                </div>
                <div class="feature-item">
                    <h4>Smooth Transitions</h4>
                    <p>Beautiful animations when switching between themes</p>
                </div>
                <div class="feature-item">
                    <h4>Mobile Optimized</h4>
                    <p>Perfect theme experience on all devices and screen sizes</p>
                </div>
                <div class="feature-item">
                    <h4>Visual Feedback</h4>
                    <p>Toast notifications confirm theme changes</p>
                </div>
                <div class="feature-item">
                    <h4>Multiple Triggers</h4>
                    <p>Theme toggle available in multiple locations</p>
                </div>
            </div>
        </div>
        
        <div class="keyboard-shortcuts">
            <h4>⌨️ Keyboard Shortcuts</h4>
            <div class="shortcut-item">
                <span>Toggle Theme</span>
                <span class="shortcut-key">Ctrl + Shift + T</span>
            </div>
        </div>
        
        <div style="text-align: center; margin: 40px 0;">
            <h2 style="color: var(--wa-text-primary); margin-bottom: 20px;">Ready to Experience?</h2>
            <div style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">
                <a href="login.php" class="theme-btn">
                    <i class="fas fa-sign-in-alt"></i>
                    Try Login Page
                </a>
                <a href="register.php" class="theme-btn secondary">
                    <i class="fas fa-user-plus"></i>
                    Try Register Page
                </a>
                <a href="whatsapp-demo.php" class="theme-btn secondary">
                    <i class="fas fa-home"></i>
                    Back to Demo
                </a>
            </div>
        </div>
    </div>
    
    <script src="assets/js/theme.js"></script>
    <script>
        function setTheme(theme) {
            if (window.themeManager) {
                window.themeManager.setTheme(theme);
            }
        }
        
        function toggleTheme() {
            if (window.themeManager) {
                window.themeManager.toggleTheme();
            }
        }
        
        function useSystemTheme() {
            if (window.themeManager) {
                window.themeManager.useSystemTheme();
            }
        }
        
        // Demo theme switching every 5 seconds (optional)
        let autoDemo = false;
        
        function startAutoDemo() {
            if (autoDemo) return;
            autoDemo = true;
            
            setInterval(() => {
                if (autoDemo && window.themeManager) {
                    window.themeManager.toggleTheme();
                }
            }, 5000);
        }
        
        // Uncomment to enable auto demo
        // setTimeout(startAutoDemo, 3000);
    </script>
</body>
</html>
