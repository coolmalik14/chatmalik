document.addEventListener('DOMContentLoaded', function() {
    const usersList = document.querySelector('.users-list');
    const chatMessages = document.querySelector('.chat-messages');
    const messageForm = document.getElementById('message-form');
    const messageInput = document.getElementById('message-input');
    const searchInput = document.getElementById('search-user');
    
    // Media elements
    const imageUploadBtn = document.getElementById('image-upload');
    const imageInput = document.getElementById('image-input');
    const voiceRecordBtn = document.getElementById('voice-record');
    const videoCallBtn = document.querySelector('.video-call-btn');
    const voiceCallBtn = document.querySelector('.voice-call-btn');
    
    let selectedUserId = null;
    let lastMessageId = 0;
    let currentUserId = null; // Will store the logged-in user's ID
    let mediaRecorder;
    let audioChunks = [];
    let isRecording = false;

    // Get current user's ID
    fetch('handlers/get_current_user.php')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                currentUserId = data.user_id;
            }
        });

    // Load users list
    function loadUsers(search = '') {
        fetch(`handlers/get_users.php?search=${search}`)
            .then(response => response.json())
            .then(users => {
                usersList.innerHTML = '';
                users.forEach(user => {
                    const userHtml = `
                        <div class="user-item" data-user-id="${user.id}">
                            <img src="uploads/profile/${user.profile_pic}" alt="${user.username}">
                            <div class="user-info">
                                <h4>${user.username}</h4>
                                <p class="status ${user.status.toLowerCase()}">${user.status}</p>
                                ${user.unread_count > 0 ? `<span class="unread-count">${user.unread_count}</span>` : ''}
                            </div>
                        </div>
                    `;
                    usersList.innerHTML += userHtml;
                });
                addUserClickListeners();
            });
    }

    // Load messages for selected user
    function loadMessages(userId) {
        fetch(`handlers/get_messages.php?user_id=${userId}&last_id=${lastMessageId}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success' && data.messages.length > 0) {
                    if (lastMessageId === 0) {
                        chatMessages.innerHTML = ''; // Clear only if it's first load
                    }
                    data.messages.forEach(message => {
                        appendMessage(message);
                        lastMessageId = Math.max(lastMessageId, message.id);
                    });
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }
            });
    }

    // Append a single message to chat
    function appendMessage(message) {
        const isMyMessage = message.sender_id === currentUserId;
        const messageClass = isMyMessage ? 'sent' : 'received';
        let messageContent = '';

        switch(message.message_type) {
            case 'image':
                messageContent = `<img src="${message.media_url}" alt="Shared Image">`;
                break;
            case 'voice':
                messageContent = `
                    <audio controls class="voice-player">
                        <source src="${message.media_url}" type="audio/wav">
                    </audio>`;
                break;
            default:
                messageContent = message.message;
        }

        const messageHtml = `
            <div class="message ${messageClass} ${message.message_type}-message" data-message-id="${message.id}">
                <div class="message-content">
                    ${messageContent}
                </div>
                <small class="message-time">${formatTime(message.created_at)}</small>
            </div>
        `;
        chatMessages.insertAdjacentHTML('beforeend', messageHtml);
    }

    // Format timestamp
    function formatTime(timestamp) {
        const date = new Date(timestamp);
        return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }

    // Add click listeners to user items
    function addUserClickListeners() {
        const userItems = document.querySelectorAll('.user-item');
        userItems.forEach(item => {
            item.addEventListener('click', function() {
                // Remove active class from all users
                userItems.forEach(i => i.classList.remove('active'));
                // Add active class to clicked user
                this.classList.add('active');

                const userId = this.dataset.userId;
                selectedUserId = userId;
                
                // Update chat header
                const username = this.querySelector('h4').textContent;
                const status = this.querySelector('.status').textContent;
                const img = this.querySelector('img').src;
                
                document.getElementById('selected-user-name').textContent = username;
                document.getElementById('selected-user-status').textContent = status;
                document.getElementById('selected-user-img').src = img;
                
                // Clear and load messages
                chatMessages.innerHTML = '';
                lastMessageId = 0;
                loadMessages(userId);

                // Remove unread count
                const unreadCount = this.querySelector('.unread-count');
                if (unreadCount) {
                    unreadCount.remove();
                }
            });
        });
    }

    // Send message
    messageForm.addEventListener('submit', function(e) {
        e.preventDefault();
        if (!selectedUserId || !messageInput.value.trim()) return;

        const formData = new FormData();
        formData.append('receiver_id', selectedUserId);
        formData.append('message', messageInput.value.trim());
        formData.append('message_type', 'text');

        fetch('handlers/send_message.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                appendMessage({
                    id: data.message_id,
                    sender_id: currentUserId,
                    message: messageInput.value.trim(),
                    message_type: 'text',
                    created_at: new Date().toISOString()
                });
                messageInput.value = '';
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
        });
    });

    // Search users
    searchInput.addEventListener('input', function() {
        loadUsers(this.value);
    });

    // Initial load of users
    loadUsers();

    // Periodic updates
    setInterval(() => {
        if (selectedUserId) {
            loadMessages(selectedUserId);
        }
    }, 3000);

    // Image Upload Handler
    imageUploadBtn.addEventListener('click', function() {
        imageInput.click();
    });

    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file && selectedUserId) {
            const formData = new FormData();
            formData.append('image', file);
            formData.append('receiver_id', selectedUserId);
            
            // Show loading message
            const loadingMessage = {
                id: 'loading-' + Date.now(),
                sender_id: currentUserId,
                message: 'Uploading image...',
                message_type: 'text',
                created_at: new Date().toISOString()
            };
            appendMessage(loadingMessage);
            
            fetch('handlers/upload_image.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Remove loading message
                const loadingEl = document.querySelector(`[data-message-id="${loadingMessage.id}"]`);
                if (loadingEl) loadingEl.remove();
                
                if (data.status === 'success') {
                    appendMessage({
                        id: Date.now(),
                        sender_id: currentUserId,
                        message: '',
                        message_type: 'image',
                        media_url: data.image_url,
                        created_at: new Date().toISOString()
                    });
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                } else {
                    alert('Failed to upload image: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error uploading image:', error);
                alert('Error uploading image');
            });
        } else if (!selectedUserId) {
            alert('Please select a user to send image to');
        }
    });

    // Voice Recording Handler
    voiceRecordBtn.addEventListener('click', async function() {
        if (!selectedUserId) {
            alert('Please select a user to send voice message to');
            return;
        }

        if (!isRecording) {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                mediaRecorder = new MediaRecorder(stream);
                
                mediaRecorder.ondataavailable = (e) => {
                    audioChunks.push(e.data);
                };
                
                mediaRecorder.onstop = async () => {
                    const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
                    const formData = new FormData();
                    formData.append('audio', audioBlob, 'voice_message.wav');
                    formData.append('receiver_id', selectedUserId);
                    
                    // Show loading message
                    const loadingMessage = {
                        id: 'loading-' + Date.now(),
                        sender_id: currentUserId,
                        message: 'Uploading voice message...',
                        message_type: 'text',
                        created_at: new Date().toISOString()
                    };
                    appendMessage(loadingMessage);
                    
                    try {
                        const response = await fetch('handlers/upload_voice.php', {
                            method: 'POST',
                            body: formData
                        });
                        
                        const data = await response.json();
                        
                        // Remove loading message
                        const loadingEl = document.querySelector(`[data-message-id="${loadingMessage.id}"]`);
                        if (loadingEl) loadingEl.remove();
                        
                        if (data.status === 'success') {
                            appendMessage({
                                id: Date.now(),
                                sender_id: currentUserId,
                                message: '',
                                message_type: 'voice',
                                media_url: data.voice_url,
                                created_at: new Date().toISOString()
                            });
                            chatMessages.scrollTop = chatMessages.scrollHeight;
                        } else {
                            alert('Failed to upload voice message: ' + data.message);
                        }
                    } catch (error) {
                        console.error('Error uploading voice:', error);
                        alert('Error uploading voice message');
                    }
                    
                    audioChunks = [];
                    stream.getTracks().forEach(track => track.stop());
                };
                
                mediaRecorder.start();
                isRecording = true;
                voiceRecordBtn.innerHTML = '<i class="fas fa-stop"></i>';
                voiceRecordBtn.style.color = '#ff4757';
                voiceRecordBtn.title = 'Stop Recording';
                
            } catch (err) {
                console.error('Error accessing microphone:', err);
                alert('Could not access microphone. Please allow microphone permission.');
            }
        } else {
            mediaRecorder.stop();
            isRecording = false;
            voiceRecordBtn.innerHTML = '<i class="fas fa-microphone"></i>';
            voiceRecordBtn.style.color = '';
            voiceRecordBtn.title = 'Record Voice Message';
        }
    });

    // Video Call Handler
    videoCallBtn.addEventListener('click', function() {
        if (!selectedUserId) {
            alert('Please select a user to call');
            return;
        }
        
        if (window.webrtcManager) {
            window.webrtcManager.startCall(selectedUserId, true);
        } else {
            alert('WebRTC manager not initialized. Please refresh the page.');
        }
    });

    // Voice Call Handler
    voiceCallBtn.addEventListener('click', function() {
        if (!selectedUserId) {
            alert('Please select a user to call');
            return;
        }
        
        if (window.webrtcManager) {
            window.webrtcManager.startCall(selectedUserId, false);
        } else {
            alert('WebRTC manager not initialized. Please refresh the page.');
        }
    });

    // Update online status
    setInterval(() => {
        fetch('handlers/update_status.php')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    loadUsers(searchInput.value);
                }
            });
    }, 10000);
});
