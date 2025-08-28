// Enhanced Theme Management System
class ThemeManager {
    constructor() {
        this.currentTheme = this.getStoredTheme() || this.getSystemTheme() || 'light';
        this.toggleButtons = [];
        this.init();
    }
    
    init() {
        this.applyTheme(this.currentTheme);
        this.setupToggleButtons();
        this.setupSystemThemeListener();
        this.saveThemeToServer(this.currentTheme);
    }
    
    // Get theme from localStorage
    getStoredTheme() {
        return localStorage.getItem('chat-theme');
    }
    
    // Get system preference
    getSystemTheme() {
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            return 'dark';
        }
        return 'light';
    }
    
    // Apply theme to document
    applyTheme(theme) {
        // Remove existing theme classes
        document.body.classList.remove('light-theme', 'dark-theme');
        
        // Add new theme class
        document.body.classList.add(`${theme}-theme`);
        document.documentElement.setAttribute('data-theme', theme);
        
        this.currentTheme = theme;
        this.updateToggleIcons();
        this.updateMetaThemeColor(theme);
        localStorage.setItem('chat-theme', theme);
        
        // Trigger custom event for theme change
        window.dispatchEvent(new CustomEvent('themeChanged', { detail: { theme } }));
    }
    
    // Update meta theme color for mobile browsers
    updateMetaThemeColor(theme) {
        let metaThemeColor = document.querySelector('meta[name="theme-color"]');
        if (!metaThemeColor) {
            metaThemeColor = document.createElement('meta');
            metaThemeColor.name = 'theme-color';
            document.head.appendChild(metaThemeColor);
        }
        
        const colors = {
            light: '#f8f9fa',
            dark: '#2a3942'
        };
        
        metaThemeColor.content = colors[theme] || colors.light;
    }
    
    // Setup all theme toggle buttons
    setupToggleButtons() {
        // Find all theme toggle buttons
        const selectors = [
            '#theme-toggle',
            '[data-theme-toggle]',
            '.theme-toggle'
        ];
        
        selectors.forEach(selector => {
            document.querySelectorAll(selector).forEach(button => {
                if (!this.toggleButtons.includes(button)) {
                    this.toggleButtons.push(button);
                    button.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        this.toggleTheme();
                    });
                }
            });
        });
        
        // Update initial icons
        this.updateToggleIcons();
    }
    
    // Toggle between light and dark theme
    toggleTheme() {
        const newTheme = this.currentTheme === 'light' ? 'dark' : 'light';
        this.applyTheme(newTheme);
        this.saveThemeToServer(newTheme);
        this.showThemeChangeNotification(newTheme);
    }
    
    // Update all toggle button icons and text
    updateToggleIcons() {
        this.toggleButtons.forEach(button => {
            const icon = button.querySelector('i');
            const text = button.querySelector('.toggle-text');
            
            if (icon) {
                // Remove existing classes
                icon.classList.remove('fa-sun', 'fa-moon');
                
                // Add appropriate class
                if (this.currentTheme === 'dark') {
            icon.classList.add('fa-sun');
        } else {
            icon.classList.add('fa-moon');
                }
            }
            
            if (text) {
                text.textContent = this.currentTheme === 'dark' ? 'Light Mode' : 'Dark Mode';
            }
            
            // Update title attribute
            button.title = `Switch to ${this.currentTheme === 'dark' ? 'light' : 'dark'} mode`;
        });
    }
    
    // Listen for system theme changes
    setupSystemThemeListener() {
        if (window.matchMedia) {
            const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
            mediaQuery.addEventListener('change', (e) => {
                // Only follow system theme if user hasn't manually set a preference
                if (!this.getStoredTheme()) {
                    const newTheme = e.matches ? 'dark' : 'light';
                    this.applyTheme(newTheme);
                    this.saveThemeToServer(newTheme);
                }
            });
        }
    }
    
    // Save theme preference to server
    saveThemeToServer(theme) {
        // Only save if user is logged in
        if (typeof currentUserId !== 'undefined' && currentUserId) {
            fetch('handlers/save_theme.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ theme: theme })
            }).catch(error => {
                console.log('Theme save failed (offline mode):', error);
            });
        }
    }
    
    // Show theme change notification
    showThemeChangeNotification(theme) {
        // Remove any existing notifications
        const existingNotification = document.querySelector('.theme-notification');
        if (existingNotification) {
            existingNotification.remove();
        }
        
        const notification = document.createElement('div');
        notification.className = 'theme-notification';
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas fa-${theme === 'dark' ? 'moon' : 'sun'}"></i>
                <span>${theme === 'dark' ? 'Dark' : 'Light'} mode activated</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.classList.add('show');
        }, 100);
        
        // Animate out and remove
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 2000);
    }
    
    // Get current theme
    getCurrentTheme() {
        return this.currentTheme;
    }
    
    // Set specific theme
    setTheme(theme) {
        if (theme === 'light' || theme === 'dark') {
            this.applyTheme(theme);
            this.saveThemeToServer(theme);
        }
    }
    
    // Auto-detect and apply system theme
    useSystemTheme() {
        const systemTheme = this.getSystemTheme();
        this.applyTheme(systemTheme);
        localStorage.removeItem('chat-theme'); // Remove stored preference
        this.saveThemeToServer('auto');
    }
}

// Initialize theme manager when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.themeManager = new ThemeManager();
    
    // Add keyboard shortcut for theme toggle (Ctrl+Shift+T)
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.shiftKey && e.key === 'T') {
            e.preventDefault();
            window.themeManager.toggleTheme();
        }
    });
});

// Add CSS for theme notifications and transitions
const themeStyles = document.createElement('style');
themeStyles.textContent = `
    /* Theme notification styles */
    .theme-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        background: var(--wa-bg-secondary);
        color: var(--wa-text-primary);
        padding: 12px 20px;
        border-radius: 8px;
        box-shadow: var(--wa-shadow-medium);
        z-index: 10000;
        transform: translateX(100%);
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-left: 4px solid var(--wa-green);
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        min-width: 200px;
    }
    
    .theme-notification.show {
        transform: translateX(0);
    }
    
    .notification-content {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        font-weight: 500;
    }
    
    .notification-content i {
        color: var(--wa-green);
        font-size: 16px;
    }
    
    /* Theme transition for smooth switching */
    * {
        transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease !important;
    }
    
    /* Theme toggle button enhancements */
    .theme-toggle,
    #theme-toggle,
    [data-theme-toggle] {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .theme-toggle:hover,
    #theme-toggle:hover,
    [data-theme-toggle]:hover {
        transform: scale(1.1);
    }
    
    .theme-toggle:active,
    #theme-toggle:active,
    [data-theme-toggle]:active {
        transform: scale(0.95);
    }
    
    /* Mobile responsive notification */
    @media (max-width: 768px) {
        .theme-notification {
            top: 10px;
            right: 10px;
            left: 10px;
            right: 10px;
            transform: translateY(-100%);
            min-width: auto;
        }
        
        .theme-notification.show {
            transform: translateY(0);
        }
    }
    
    /* Dark theme specific enhancements */
    .dark-theme {
        --wa-shadow-light: 0 1px 3px rgba(0, 0, 0, 0.3);
        --wa-shadow-medium: 0 2px 8px rgba(0, 0, 0, 0.4);
        --wa-shadow-heavy: 0 4px 16px rgba(0, 0, 0, 0.5);
    }
    
    /* Light theme specific enhancements */
    .light-theme {
        --wa-shadow-light: 0 1px 3px rgba(11, 20, 26, 0.13);
        --wa-shadow-medium: 0 2px 8px rgba(11, 20, 26, 0.15);
        --wa-shadow-heavy: 0 4px 16px rgba(11, 20, 26, 0.2);
    }
    
    /* Theme toggle animation */
    @keyframes themeToggle {
        0% { transform: rotate(0deg); }
        50% { transform: rotate(180deg); }
        100% { transform: rotate(360deg); }
    }
    
    .theme-toggle.toggling i,
    #theme-toggle.toggling i,
    [data-theme-toggle].toggling i {
        animation: themeToggle 0.6s ease-in-out;
    }
`;
document.head.appendChild(themeStyles);