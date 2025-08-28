// Comprehensive Media Handler for WhatsApp Clone
class MediaHandler {
    constructor() {
        this.mediaRecorder = null;
        this.audioChunks = [];
        this.isRecording = false;
        this.selectedUserId = null;
        this.currentUserId = null;
        this.init();
    }
    
    init() {
        this.setupEventListeners();
        this.setupMediaElements();
    }
    
    // Set user IDs from main chat
    setUserIds(currentUserId, selectedUserId) {
        this.currentUserId = currentUserId;
        this.selectedUserId = selectedUserId;
    }
    
    setupEventListeners() {
        // Wait for DOM to be ready
        document.addEventListener('DOMContentLoaded', () => {
            this.bindMediaEvents();
        });
        
        // If DOM is already ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.bindMediaEvents());
        } else {
            this.bindMediaEvents();
        }
    }
    
    bindMediaEvents() {
        // Media attachment button
        const attachMediaBtn = document.getElementById('attach-media');
        if (attachMediaBtn) {
            attachMediaBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.toggleMediaMenu();
            });
        }
        
        // Gallery/Image upload
        const attachGalleryBtn = document.getElementById('attach-gallery');
        const imageInput = document.getElementById('image-input');
        if (attachGalleryBtn && imageInput) {
            attachGalleryBtn.addEventListener('click', (e) => {
                e.preventDefault();
                imageInput.click();
            });
            
            imageInput.addEventListener('change', (e) => {
                this.handleImageUpload(e);
            });
        }
        
        // Document upload
        const attachDocumentBtn = document.getElementById('attach-document');
        const documentInput = document.getElementById('document-input');
        if (attachDocumentBtn && documentInput) {
            attachDocumentBtn.addEventListener('click', (e) => {
                e.preventDefault();
                documentInput.click();
            });
            
            documentInput.addEventListener('change', (e) => {
                this.handleDocumentUpload(e);
            });
        }
        
        // Voice recording
        const voiceRecordBtn = document.getElementById('voice-record');
        if (voiceRecordBtn) {
            voiceRecordBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.toggleVoiceRecording();
            });
        }
        
        // Close media menu when clicking outside
        document.addEventListener('click', (e) => {
            const mediaMenu = document.getElementById('media-menu');
            const attachMediaBtn = document.getElementById('attach-media');
            
            if (mediaMenu && !mediaMenu.contains(e.target) && e.target !== attachMediaBtn) {
                mediaMenu.classList.remove('active');
            }
        });
    }
    
    setupMediaElements() {
        // Create media menu if it doesn't exist
        const mediaMenu = document.getElementById('media-menu');
        if (!mediaMenu) {
            console.warn('Media menu not found in DOM');
        }
    }
    
    // Toggle media attachment menu
    toggleMediaMenu() {
        const mediaMenu = document.getElementById('media-menu');
        if (mediaMenu) {
            mediaMenu.classList.toggle('active');
        }
    }
    
    // Handle image upload
    handleImageUpload(event) {
        const file = event.target.files[0];
        if (!file) return;
        
        if (!this.selectedUserId) {
            this.showError('Please select a user to send image to');
            return;
        }
        
        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!allowedTypes.includes(file.type)) {
            this.showError('Please select a valid image file (JPEG, PNG, GIF)');
            return;
        }
        
        // Validate file size (max 5MB)
        if (file.size > 5 * 1024 * 1024) {
            this.showError('Image size must be less than 5MB');
            return;
        }
        
        this.uploadFile(file, 'image', 'handlers/upload_image.php');
        
        // Clear input
        event.target.value = '';
        
        // Close media menu
        const mediaMenu = document.getElementById('media-menu');
        if (mediaMenu) {
            mediaMenu.classList.remove('active');
        }
    }
    
    // Handle document upload
    handleDocumentUpload(event) {
        const file = event.target.files[0];
        if (!file) return;
        
        if (!this.selectedUserId) {
            this.showError('Please select a user to send document to');
            return;
        }
        
        // Validate file type
        const allowedExtensions = ['pdf', 'doc', 'docx', 'txt', 'xls', 'xlsx', 'ppt', 'pptx'];
        const fileExtension = file.name.split('.').pop().toLowerCase();
        
        if (!allowedExtensions.includes(fileExtension)) {
            this.showError('Please select a valid document file (PDF, DOC, DOCX, TXT, XLS, XLSX, PPT, PPTX)');
            return;
        }
        
        // Validate file size (max 10MB)
        if (file.size > 10 * 1024 * 1024) {
            this.showError('Document size must be less than 10MB');
            return;
        }
        
        this.uploadFile(file, 'document', 'handlers/upload_document.php');
        
        // Clear input
        event.target.value = '';
        
        // Close media menu
        const mediaMenu = document.getElementById('media-menu');
        if (mediaMenu) {
            mediaMenu.classList.remove('active');
        }
    }
    
    // Generic file upload function
    uploadFile(file, type, endpoint) {
        const formData = new FormData();
        formData.append(type, file);
        formData.append('receiver_id', this.selectedUserId);
        
        // Show loading message
        const loadingMessageId = this.showLoadingMessage(`Uploading ${type}...`);
        
        fetch(endpoint, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            // Remove loading message
            this.removeLoadingMessage(loadingMessageId);
            
            if (data.status === 'success') {
                this.handleUploadSuccess(data, type);
            } else {
                this.showError(`Failed to upload ${type}: ${data.message}`);
            }
        })
        .catch(error => {
            this.removeLoadingMessage(loadingMessageId);
            console.error(`Error uploading ${type}:`, error);
            this.showError(`Error uploading ${type}. Please try again.`);
        });
    }
    
    // Handle successful upload
    handleUploadSuccess(data, type) {
        let messageContent = '';
        
        switch(type) {
            case 'image':
                messageContent = `<img src="${data.image_url}" alt="Shared Image" onclick="window.openImageModal('${data.image_url}')" style="max-width: 300px; max-height: 200px; border-radius: 8px; cursor: pointer;">`;
                break;
            case 'document':
                const fileSize = this.formatFileSize(data.file_size);
                messageContent = `
                    <div class="document-message">
                        <div class="document-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="document-info">
                            <div class="document-name">${data.file_name}</div>
                            <div class="document-size">${fileSize}</div>
                        </div>
                        <a href="${data.document_url}" download="${data.file_name}" class="document-download">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                `;
                break;
            case 'voice':
                messageContent = this.createVoiceMessageHTML(data.voice_url);
                break;
        }
        
        // Add message to chat
        if (window.appendMessage) {
            window.appendMessage({
                id: Date.now(),
                sender_id: this.currentUserId,
                message: '',
                message_type: type,
                media_url: data[type + '_url'],
                created_at: new Date().toISOString()
            });
        } else if (window.chatApp && window.chatApp.appendMessage) {
            window.chatApp.appendMessage({
                id: Date.now(),
                sender_id: this.currentUserId,
                message_content: messageContent,
                message_type: type,
                created_at: new Date().toISOString()
            });
        } else {
            // Fallback: directly add to chat
            this.addMessageToChat(messageContent, type);
        }
        
        this.scrollToBottom();
    }
    
    // Voice recording functionality
    async toggleVoiceRecording() {
        if (!this.selectedUserId) {
            this.showError('Please select a user to send voice message to');
            return;
        }
        
        const voiceBtn = document.getElementById('voice-record');
        
        if (!this.isRecording) {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                this.mediaRecorder = new MediaRecorder(stream);
                
                this.audioChunks = [];
                
                this.mediaRecorder.ondataavailable = (event) => {
                    if (event.data.size > 0) {
                        this.audioChunks.push(event.data);
                    }
                };
                
                this.mediaRecorder.onstop = () => {
                    const audioBlob = new Blob(this.audioChunks, { type: 'audio/wav' });
                    this.uploadVoiceMessage(audioBlob);
                    
                    // Stop all tracks
                    stream.getTracks().forEach(track => track.stop());
                };
                
                this.mediaRecorder.start();
                this.isRecording = true;
                
                // Update button appearance
                if (voiceBtn) {
                    voiceBtn.classList.add('recording');
                    voiceBtn.innerHTML = '<i class="fas fa-stop"></i>';
                    voiceBtn.title = 'Stop Recording';
                }
                
                this.showSuccess('Recording started... Click again to stop');
                
            } catch (error) {
                console.error('Error accessing microphone:', error);
                this.showError('Could not access microphone. Please allow microphone permission and try again.');
            }
        } else {
            // Stop recording
            if (this.mediaRecorder && this.mediaRecorder.state === 'recording') {
                this.mediaRecorder.stop();
            }
            
            this.isRecording = false;
            
            // Reset button appearance
            if (voiceBtn) {
                voiceBtn.classList.remove('recording');
                voiceBtn.innerHTML = '<i class="fas fa-microphone"></i>';
                voiceBtn.title = 'Record Voice Message';
            }
        }
    }
    
    // Upload voice message
    uploadVoiceMessage(audioBlob) {
        const formData = new FormData();
        formData.append('audio', audioBlob, 'voice_message.wav');
        formData.append('receiver_id', this.selectedUserId);
        
        const loadingMessageId = this.showLoadingMessage('Uploading voice message...');
        
        fetch('handlers/upload_voice.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            this.removeLoadingMessage(loadingMessageId);
            
            if (data.status === 'success') {
                this.handleUploadSuccess(data, 'voice');
            } else {
                this.showError('Failed to upload voice message: ' + data.message);
            }
        })
        .catch(error => {
            this.removeLoadingMessage(loadingMessageId);
            console.error('Error uploading voice message:', error);
            this.showError('Error uploading voice message. Please try again.');
        });
    }
    
    // Create voice message HTML
    createVoiceMessageHTML(voiceUrl) {
        return `
            <div class="voice-message-container">
                <button class="voice-play-btn" onclick="window.playVoiceMessage(this)">
                    <i class="fas fa-play"></i>
                </button>
                <div class="voice-waveform"></div>
                <span class="voice-duration">0:00</span>
                <audio src="${voiceUrl}" preload="metadata" style="display: none;"></audio>
            </div>
        `;
    }
    
    // Utility functions
    showLoadingMessage(message) {
        const messageId = 'loading-' + Date.now();
        const chatMessages = document.querySelector('.chat-messages');
        
        if (chatMessages) {
            const loadingDiv = document.createElement('div');
            loadingDiv.className = 'message sent loading';
            loadingDiv.setAttribute('data-message-id', messageId);
            loadingDiv.innerHTML = `
                <div class="message-bubble">
                    <div class="message-content">
                        <i class="fas fa-spinner fa-spin"></i> ${message}
                    </div>
                </div>
            `;
            chatMessages.appendChild(loadingDiv);
            this.scrollToBottom();
        }
        
        return messageId;
    }
    
    removeLoadingMessage(messageId) {
        const loadingMessage = document.querySelector(`[data-message-id="${messageId}"]`);
        if (loadingMessage) {
            loadingMessage.remove();
        }
    }
    
    addMessageToChat(content, type) {
        const chatMessages = document.querySelector('.chat-messages');
        if (chatMessages) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `message sent ${type}-message`;
            messageDiv.innerHTML = `
                <div class="message-bubble">
                    <div class="message-content">${content}</div>
                    <div class="message-meta">
                        <span class="message-time">${this.getCurrentTime()}</span>
                        <i class="fas fa-check message-status"></i>
                    </div>
                </div>
            `;
            chatMessages.appendChild(messageDiv);
            this.scrollToBottom();
        }
    }
    
    scrollToBottom() {
        const chatMessages = document.querySelector('.chat-messages');
        if (chatMessages) {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    }
    
    getCurrentTime() {
        return new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }
    
    formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    showError(message) {
        this.showNotification(message, 'error');
    }
    
    showSuccess(message) {
        this.showNotification(message, 'success');
    }
    
    showNotification(message, type = 'info') {
        // Remove existing notifications
        const existingNotifications = document.querySelectorAll('.media-notification');
        existingNotifications.forEach(notification => notification.remove());
        
        const notification = document.createElement('div');
        notification.className = `media-notification ${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas fa-${type === 'error' ? 'exclamation-circle' : type === 'success' ? 'check-circle' : 'info-circle'}"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Show notification
        setTimeout(() => notification.classList.add('show'), 100);
        
        // Hide and remove notification
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 4000);
    }
}

// Global functions for media playback
window.playVoiceMessage = function(button) {
    const audio = button.parentElement.querySelector('audio');
    const icon = button.querySelector('i');
    const durationSpan = button.parentElement.querySelector('.voice-duration');
    
    if (audio.paused) {
        // Pause all other voice messages
        document.querySelectorAll('.voice-message-container audio').forEach(otherAudio => {
            if (otherAudio !== audio && !otherAudio.paused) {
                otherAudio.pause();
                const otherButton = otherAudio.parentElement.querySelector('.voice-play-btn i');
                if (otherButton) otherButton.className = 'fas fa-play';
            }
        });
        
        audio.play();
        icon.className = 'fas fa-pause';
        
        // Update duration
        audio.addEventListener('loadedmetadata', () => {
            if (durationSpan && audio.duration) {
                const duration = Math.floor(audio.duration);
                const minutes = Math.floor(duration / 60);
                const seconds = duration % 60;
                durationSpan.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
            }
        });
        
    } else {
        audio.pause();
        icon.className = 'fas fa-play';
    }
    
    audio.onended = () => {
        icon.className = 'fas fa-play';
    };
};

window.openImageModal = function(imageSrc) {
    // Remove existing modal
    const existingModal = document.querySelector('.image-modal');
    if (existingModal) {
        existingModal.remove();
    }
    
    const modal = document.createElement('div');
    modal.className = 'image-modal';
    modal.innerHTML = `
        <div class="modal-backdrop" onclick="this.parentElement.remove()">
            <div class="modal-content" onclick="event.stopPropagation()">
                <button class="modal-close" onclick="this.closest('.image-modal').remove()">
                    <i class="fas fa-times"></i>
                </button>
                <img src="${imageSrc}" alt="Full size image">
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
};

// Initialize media handler
window.mediaHandler = new MediaHandler();

// CSS for notifications and media elements
const mediaStyles = document.createElement('style');
mediaStyles.textContent = `
    .media-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        background: var(--wa-bg-secondary, #ffffff);
        color: var(--wa-text-primary, #000000);
        padding: 12px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 10000;
        transform: translateX(100%);
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        max-width: 300px;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }
    
    .media-notification.show {
        transform: translateX(0);
    }
    
    .media-notification.error {
        border-left: 4px solid #e74c3c;
    }
    
    .media-notification.success {
        border-left: 4px solid #27ae60;
    }
    
    .media-notification.info {
        border-left: 4px solid #3498db;
    }
    
    .notification-content {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .notification-content i {
        font-size: 16px;
    }
    
    .media-notification.error .notification-content i {
        color: #e74c3c;
    }
    
    .media-notification.success .notification-content i {
        color: #27ae60;
    }
    
    .media-notification.info .notification-content i {
        color: #3498db;
    }
    
    .voice-message-container {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px;
        background: rgba(255,255,255,0.1);
        border-radius: 20px;
        min-width: 200px;
        max-width: 250px;
    }
    
    .voice-play-btn {
        width: 32px;
        height: 32px;
        border: none;
        background: var(--wa-green, #00a884);
        color: white;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        transition: all 0.2s;
    }
    
    .voice-play-btn:hover {
        transform: scale(1.1);
    }
    
    .voice-waveform {
        flex: 1;
        height: 20px;
        background: repeating-linear-gradient(
            90deg,
            currentColor 0px,
            currentColor 2px,
            transparent 2px,
            transparent 6px
        );
        opacity: 0.5;
        border-radius: 2px;
    }
    
    .voice-duration {
        font-size: 12px;
        opacity: 0.8;
        min-width: 35px;
    }
    
    .document-message {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        background: rgba(255,255,255,0.1);
        border-radius: 8px;
        max-width: 280px;
    }
    
    .document-icon {
        width: 40px;
        height: 40px;
        background: var(--wa-green, #00a884);
        color: white;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }
    
    .document-info {
        flex: 1;
        min-width: 0;
    }
    
    .document-name {
        font-weight: 500;
        font-size: 14px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .document-size {
        font-size: 12px;
        opacity: 0.7;
        margin-top: 2px;
    }
    
    .document-download {
        width: 32px;
        height: 32px;
        background: rgba(255,255,255,0.2);
        color: inherit;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .document-download:hover {
        background: rgba(255,255,255,0.3);
        transform: scale(1.1);
    }
    
    .image-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 10001;
    }
    
    .modal-backdrop {
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
    
    .modal-content {
        position: relative;
        max-width: 90%;
        max-height: 90%;
        cursor: default;
    }
    
    .modal-content img {
        max-width: 100%;
        max-height: 100%;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.5);
    }
    
    .modal-close {
        position: absolute;
        top: -40px;
        right: 0;
        background: rgba(255,255,255,0.2);
        color: white;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    
    .modal-close:hover {
        background: rgba(255,255,255,0.3);
    }
    
    @media (max-width: 768px) {
        .media-notification {
            top: 10px;
            right: 10px;
            left: 10px;
            transform: translateY(-100%);
        }
        
        .media-notification.show {
            transform: translateY(0);
        }
        
        .modal-content {
            max-width: 95%;
            max-height: 95%;
        }
        
        .modal-close {
            top: -35px;
            right: 5px;
        }
    }
`;
document.head.appendChild(mediaStyles);
