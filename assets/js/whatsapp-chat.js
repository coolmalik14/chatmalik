// Enhanced WhatsApp-like Chat Application
document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const usersList = document.querySelector('.users-list');
    const chatMessages = document.querySelector('.chat-messages');
    const messageInput = document.getElementById('message-input');
    const searchInput = document.getElementById('search-user');
    const sidebar = document.querySelector('.sidebar');
    const chatArea = document.querySelector('.chat-area');
    
    // Media elements
    const attachMediaBtn = document.getElementById('attach-media');
    const mediaMenu = document.getElementById('media-menu');
    const attachImageBtn = document.getElementById('attach-gallery');
    const attachDocumentBtn = document.getElementById('attach-document');
    const imageInput = document.getElementById('image-input');
    const documentInput = document.getElementById('document-input');
    const voiceRecordBtn = document.getElementById('voice-record');
    const sendMessageBtn = document.getElementById('send-message');
    const backBtn = document.getElementById('back-to-chats');
    
    // Emoji picker elements
    const emojiPickerBtn = document.getElementById('emoji-picker');
    const emojiPickerContainer = document.getElementById('emoji-picker-container');
    const emojiGrid = document.getElementById('emoji-grid');
    
    // Call elements
    const videoCallBtn = document.querySelector('.video-call-btn');
    const voiceCallBtn = document.querySelector('.voice-call-btn');
    
    // State variables
    let selectedUserId = null;
    let lastMessageId = 0;
    let currentUserId = null;
    let mediaRecorder;
    let audioChunks = [];
    let isRecording = false;
    let typingTimeout;
    let isTyping = false;
    let lastSeen = {};
    
    // Emoji data
    const emojis = {
        smileys: ['😀','😃','😄','😁','😆','😅','😂','🤣','😊','😇','🙂','🙃','😉','😌','😍','🥰','😘','😗','😙','😚','😋','😛','😝','😜','🤪','🤨','🧐','🤓','😎','🤩','🥳','😏','😒','😞','😔','😟','😕','🙁','☹️','😣','😖','😫','😩','🥺','😢','😭','😤','😠','😡','🤬','🤯','😳','🥵','🥶','😱','😨','😰','😥','😓','🤗','🤔','🤭','🤫','🤥','😶','😐','😑','😬','🙄','😯','😦','😧','😮','😲','🥱','😴','🤤','😪','😵','🤐','🥴','🤢','🤮','🤧','😷','🤒','🤕'],
        people: ['👋','🤚','🖐️','✋','🖖','👌','🤏','✌️','🤞','🤟','🤘','🤙','👈','👉','👆','🖕','👇','☝️','👍','👎','👊','✊','🤛','🤜','👏','🙌','👐','🤲','🤝','🙏','✍️','💅','🤳','💪','🦾','🦿','🦵','🦶','👂','🦻','👃','🧠','🫀','🫁','🦷','🦴','👀','👁️','👅','👄','💋','🩸'],
        animals: ['🐶','🐱','🐭','🐹','🐰','🦊','🐻','🐼','🐨','🐯','🦁','🐮','🐷','🐽','🐸','🐵','🙈','🙉','🙊','🐒','🐔','🐧','🐦','🐤','🐣','🐥','🦆','🦅','🦉','🦇','🐺','🐗','🐴','🦄','🐝','🐛','🦋','🐌','🐞','🐜','🦟','🦗','🕷️','🕸️','🦂','🐢','🐍','🦎','🦖','🦕','🐙','🦑','🦐','🦞','🦀','🐡','🐠','🐟','🐬','🐳','🐋','🦈','🐊','🐅','🐆','🦓','🦍','🦧','🐘','🦛','🦏','🐪','🐫','🦒','🦘','🐃','🐂','🐄','🐎','🐖','🐏','🐑','🦙','🐐','🦌','🐕','🐩','🦮','🐕‍🦺','🐈','🐓','🦃','🦚','🦜','🦢','🦩','🕊️','🐇','🦝','🦨','🦡','🦦','🦥','🐁','🐀','🐿️','🦔'],
        food: ['🍎','🍐','🍊','🍋','🍌','🍉','🍇','🍓','🫐','🍈','🍒','🍑','🥭','🍍','🥥','🥝','🍅','🍆','🥑','🥦','🥬','🥒','🌶️','🫑','🌽','🥕','🫒','🧄','🧅','🥔','🍠','🥐','🥯','🍞','🥖','🥨','🧀','🥚','🍳','🧈','🥞','🧇','🥓','🥩','🍗','🍖','🦴','🌭','🍔','🍟','🍕','🫓','🥪','🥙','🧆','🌮','🌯','🫔','🥗','🥘','🫕','🥫','🍝','🍜','🍲','🍛','🍣','🍱','🥟','🦪','🍤','🍙','🍚','🍘','🍥','🥠','🥮','🍢','🍡','🍧','🍨','🍦','🥧','🧁','🍰','🎂','🍮','🍭','🍬','🍫','🍿','🍩','🍪','🌰','🥜','🍯'],
        activities: ['⚽','🏀','🏈','⚾','🥎','🎾','🏐','🏉','🥏','🎱','🪀','🏓','🏸','🏒','🏑','🥍','🏏','🪃','🥅','⛳','🪁','🏹','🎣','🤿','🥊','🥋','🎽','🛹','🛷','⛸️','🥌','🎿','⛷️','🏂','🪂','🏋️','🤼','🤸','⛹️','🤺','🏊','🏄','🏇','🧘','🏃','🚶','🧎','🧍','🤳','💃','🕺','👯','🧗','🤾','🏌️','🏊','🚣','🧜','🧚','🧞','🧟','🧛','🧙','🧝','🧖','🧕','👸','🤴','👰','🤵','👼','🎅','🤶','🦸','🦹','🧙','🧚','🧛','🧜','🧝','🧞','🧟','🦾','🦿'],
        travel: ['🚗','🚕','🚙','🚌','🚎','🏎️','🚓','🚑','🚒','🚐','🛻','🚚','🚛','🚜','🦯','🦽','🦼','🛴','🚲','🛵','🏍️','🛺','🚨','🚔','🚍','🚘','🚖','🚡','🚠','🚟','🚃','🚋','🚞','🚝','🚄','🚅','🚈','🚂','🚆','🚇','🚊','🚉','✈️','🛫','🛬','🛩️','💺','🛰️','🚀','🛸','🚁','🛶','⛵','🚤','🛥️','🛳️','⛴️','🚢','⚓','⛽','🚧','🚦','🚥','🗺️','🗿','🗽','🗼','🏰','🏯','🏟️','🎡','🎢','🎠','⛲','⛱️','🏖️','🏝️','🏜️','🌋','⛰️','🏔️','🗻','🏕️','⛺','🏠','🏡','🏘️','🏚️','🏗️','🏭','🏢','🏬','🏣','🏤','🏥','🏦','🏨','🏪','🏫','🏩','💒','🏛️','⛪','🕌','🕍','🛕','🕋'],
        objects: ['⌚','📱','📲','💻','⌨️','🖥️','🖨️','🖱️','🖲️','🕹️','🗜️','💽','💾','💿','📀','📼','📷','📸','📹','🎥','📽️','🎞️','📞','☎️','📟','📠','📺','📻','🎙️','🎚️','🎛️','🧭','⏱️','⏲️','⏰','🕰️','⌛','⏳','📡','🔋','🔌','💡','🔦','🕯️','🪔','🧯','🛢️','💸','💵','💴','💶','💷','💰','💳','💎','⚖️','🧰','🔧','🔨','⚒️','🛠️','⛏️','🔩','⚙️','🧱','⛓️','🧲','🔫','💣','🧨','🪓','🔪','🗡️','⚔️','🛡️','🚬','⚰️','⚱️','🏺','🔮','📿','🧿','💈','⚗️','🔭','🔬','🕳️','🩹','🩺','💊','💉','🧬','🦠','🧫','🧪','🌡️','🧹','🧺','🧻','🚽','🚰','🚿','🛁','🛀','🧼','🪒','🧽','🧴','🛎️','🔑','🗝️','🚪','🪑','🛋️','🛏️','🛌','🧸','🖼️','🛍️','🛒','🎁','🎈','🎏','🎀','🎊','🎉','🎎','🏮','🎐','🧧','✉️','📩','📨','📧','💌','📥','📤','📦','🏷️','📪','📫','📬','📭','📮','📯','📜','📃','📄','📑','🧾','📊','📈','📉','🗒️','🗓️','📅','📆','🗑️','📇','🗃️','🗳️','🗄️','📋','📁','📂','🗂️','🗞️','📰','📓','📔','📒','📕','📗','📘','📙','📚','📖','🔖','🧷','🔗','📎','🖇️','📐','📏','🧮','📌','📍','✂️','🖊️','🖋️','✒️','🖌️','🖍️','📝','✏️','🔍','🔎','🔏','🔐','🔒','🔓'],
        symbols: ['❤️','🧡','💛','💚','💙','💜','🖤','🤍','🤎','💔','❣️','💕','💞','💓','💗','💖','💘','💝','💟','☮️','✝️','☪️','🕉️','☸️','✡️','🔯','🕎','☯️','☦️','🛐','⛎','♈','♉','♊','♋','♌','♍','♎','♏','♐','♑','♒','♓','🆔','⚛️','🉑','☢️','☣️','📴','📳','🈶','🈚','🈸','🈺','🈷️','✴️','🆚','💮','🉐','㊙️','㊗️','🈴','🈵','🈹','🈲','🅰️','🅱️','🆎','🆑','🅾️','🆘','❌','⭕','🛑','⛔','📛','🚫','💯','💢','♨️','🚷','🚯','🚳','🚱','🔞','📵','🚭','❗','❕','❓','❔','‼️','⁉️','🔅','🔆','〽️','⚠️','🚸','🔱','⚜️','🔰','♻️','✅','🈯','💹','❇️','✳️','❎','🌐','💠','Ⓜ️','🌀','💤','🏧','🚾','♿','🅿️','🈳','🈂️','🛂','🛃','🛄','🛅','🚹','🚺','🚼','🚻','🚮','🎦','📶','🈁','🔣','ℹ️','🔤','🔡','🔠','🆖','🆗','🆙','🆒','🆕','🆓','0️⃣','1️⃣','2️⃣','3️⃣','4️⃣','5️⃣','6️⃣','7️⃣','8️⃣','9️⃣','🔟','🔢','#️⃣','*️⃣','⏏️','▶️','⏸️','⏯️','⏹️','⏺️','⏭️','⏮️','⏩','⏪','⏫','⏬','◀️','🔼','🔽','➡️','⬅️','⬆️','⬇️','↗️','↘️','↙️','↖️','↕️','↔️','↪️','↩️','⤴️','⤵️','🔀','🔁','🔂','🔄','🔃','🎵','🎶','➕','➖','➗','✖️','♾️','💲','💱','™️','©️','®️','〰️','➰','➿','🔚','🔙','🔛','🔝','🔜','✔️','☑️','🔘','🔴','🟠','🟡','🟢','🔵','🟣','⚫','⚪','🟤','🔺','🔻','🔸','🔹','🔶','🔷','🔳','🔲','▪️','▫️','◾','◽','◼️','◻️','🟥','🟧','🟨','🟩','🟦','🟪','⬛','⬜','🟫','🔈','🔇','🔉','🔊','🔔','🔕','📣','📢','👁️‍🗨️','💬','💭','🗯️','♠️','♣️','♥️','♦️','🃏','🎴','🀄','🕐','🕑','🕒','🕓','🕔','🕕','🕖','🕗','🕘','🕙','🕚','🕛','🕜','🕝','🕞','🕟','🕠','🕡','🕢','🕣','🕤','🕥','🕦','🕧']
    };
    
    // Initialize application
    init();
    
    function init() {
        getCurrentUser();
        setupEventListeners();
        loadUsers();
        initializeEmojiPicker();
        startPeriodicUpdates();
        
        // Initialize media handler integration
        setTimeout(() => {
            if (window.mediaHandler) {
                window.mediaHandler.setUserIds(currentUserId, selectedUserId);
            }
        }, 1000);
    }
    
    // Get current user's ID and settings
    function getCurrentUser() {
        fetch('handlers/get_current_user.php')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    currentUserId = data.user_id;
                    loadUserSettings();
                }
            })
            .catch(error => console.error('Error getting current user:', error));
    }
    
    // Load user settings including theme preference
    function loadUserSettings() {
        fetch('handlers/get_theme.php')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success' && data.settings) {
                    const settings = data.settings;
                    
                    // Apply saved theme if different from current
                    if (window.themeManager && settings.theme !== 'auto') {
                        const currentTheme = window.themeManager.getCurrentTheme();
                        if (settings.theme !== currentTheme) {
                            window.themeManager.setTheme(settings.theme);
                        }
                    }
                    
                    // Store settings for later use
                    window.userSettings = settings;
                }
            })
            .catch(error => console.error('Error loading user settings:', error));
    }
    
    // Setup event listeners
    function setupEventListeners() {
        // Message input events
        messageInput.addEventListener('input', handleTyping);
        messageInput.addEventListener('keypress', handleKeyPress);
        sendMessageBtn.addEventListener('click', sendMessage);
        
        // Media events are now handled by media-handler.js
        // Remove duplicate event listeners to avoid conflicts
        
        // Emoji picker events
        emojiPickerBtn.addEventListener('click', toggleEmojiPicker);
        
        // Call events
        videoCallBtn.addEventListener('click', initiateVideoCall);
        voiceCallBtn.addEventListener('click', initiateVoiceCall);
        
        // Search events
        searchInput.addEventListener('input', handleSearch);
        
        // Mobile back button
        if (backBtn) {
            backBtn.addEventListener('click', () => {
                sidebar.classList.remove('active');
                chatArea.classList.remove('chat-active');
            });
        }
        
        // Dropdown events
        setupDropdowns();
        
        // Click outside to close menus
        document.addEventListener('click', handleOutsideClick);
    }
    
    // Setup dropdown menus
    function setupDropdowns() {
        document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
            toggle.addEventListener('click', (e) => {
                e.stopPropagation();
                const dropdown = toggle.closest('.dropdown');
                dropdown.classList.toggle('active');
            });
        });
    }
    
    // Handle outside clicks
    function handleOutsideClick(e) {
        // Close dropdowns
        if (!e.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown').forEach(dropdown => {
                dropdown.classList.remove('active');
            });
        }
        
        // Close media menu
        if (!e.target.closest('.media-btn') && !e.target.closest('.media-menu')) {
            mediaMenu.classList.remove('active');
        }
        
        // Close emoji picker
        if (!e.target.closest('.emoji-btn') && !e.target.closest('.emoji-picker')) {
            emojiPickerContainer.classList.remove('active');
        }
    }
    
    // Load users list
    function loadUsers(search = '') {
        fetch(`handlers/get_users.php?search=${encodeURIComponent(search)}`)
            .then(response => response.json())
            .then(users => {
                if (Array.isArray(users)) {
                    renderUsersList(users);
                }
            })
            .catch(error => console.error('Error loading users:', error));
    }
    
    // Render users list
    function renderUsersList(users) {
        if (users.length === 0) {
            usersList.innerHTML = '<div class="no-users">No users found</div>';
            return;
        }
        
        usersList.innerHTML = '';
        users.forEach(user => {
            const lastMessage = user.last_message || 'No messages yet';
            const messageTime = user.last_message_time ? formatTime(user.last_message_time) : '';
            const isOnline = user.status === 'Online';
            
            const userHtml = `
                <div class="user-item" data-user-id="${user.id}">
                    <img src="uploads/profile/${user.profile_pic}" alt="${user.username}">
                    <div class="user-info">
                        <h4>${user.username}</h4>
                        <p class="last-message">${lastMessage}</p>
                    </div>
                    <div class="user-meta">
                        <span class="message-time">${messageTime}</span>
                        ${user.unread_count > 0 ? `<span class="unread-count">${user.unread_count}</span>` : ''}
                        <div class="status ${isOnline ? 'online' : 'offline'}"></div>
                    </div>
                </div>
            `;
            usersList.insertAdjacentHTML('beforeend', userHtml);
        });
        
        // Add click listeners
        document.querySelectorAll('.user-item').forEach(item => {
            item.addEventListener('click', () => selectUser(item));
        });
    }
    
    // Select user
    function selectUser(userItem) {
        // Remove active class from all users
        document.querySelectorAll('.user-item').forEach(item => {
            item.classList.remove('active');
        });
        
        // Add active class to selected user
        userItem.classList.add('active');
        
        const userId = userItem.dataset.userId;
        selectedUserId = userId;
        
        // Update media handler with new selected user
        if (window.mediaHandler) {
            window.mediaHandler.setUserIds(currentUserId, selectedUserId);
        }
        
        // Update chat header
        const username = userItem.querySelector('h4').textContent;
        const img = userItem.querySelector('img').src;
        const isOnline = userItem.querySelector('.status').classList.contains('online');
        
        document.getElementById('selected-user-name').textContent = username;
        document.getElementById('selected-user-img').src = img;
        
        const statusIndicator = document.getElementById('contact-status-dot');
        const statusText = document.getElementById('selected-user-status');
        
        if (isOnline) {
            statusIndicator.classList.add('online');
            statusText.textContent = 'Online';
        } else {
            statusIndicator.classList.remove('online');
            statusText.textContent = lastSeen[userId] || 'Last seen recently';
        }
        
        // Clear and load messages
        clearWelcomeMessage();
        lastMessageId = 0;
        loadMessages(userId);
        
        // Remove unread count
        const unreadCount = userItem.querySelector('.unread-count');
        if (unreadCount) {
            unreadCount.remove();
        }
        
        // Show chat on mobile
        if (window.innerWidth <= 768) {
            sidebar.classList.add('active');
            chatArea.classList.add('chat-active');
        }
    }
    
    // Clear welcome message
    function clearWelcomeMessage() {
        const welcomeMessage = document.querySelector('.welcome-message');
        if (welcomeMessage) {
            welcomeMessage.remove();
        }
    }
    
    // Load messages
    function loadMessages(userId) {
        fetch(`handlers/get_messages.php?user_id=${userId}&last_id=${lastMessageId}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success' && data.messages.length > 0) {
                    if (lastMessageId === 0) {
                        chatMessages.innerHTML = '';
                    }
                    data.messages.forEach(message => {
                        appendMessage(message);
                        lastMessageId = Math.max(lastMessageId, message.id);
                    });
                    scrollToBottom();
                }
            })
            .catch(error => console.error('Error loading messages:', error));
    }
    
    // Append message
    function appendMessage(message) {
        const isMyMessage = message.sender_id == currentUserId;
        const messageClass = isMyMessage ? 'sent' : 'received';
        
        let messageContent = '';
        switch(message.message_type) {
            case 'image':
                messageContent = `<img src="${message.media_url}" alt="Shared Image" onclick="window.openImageModal('${message.media_url}')" style="max-width: 300px; max-height: 200px; border-radius: 8px; cursor: pointer;">`;
                break;
            case 'voice':
                messageContent = `
                    <div class="voice-message-container">
                        <button class="voice-play-btn" onclick="window.playVoiceMessage(this)">
                            <i class="fas fa-play"></i>
                        </button>
                        <div class="voice-waveform"></div>
                        <span class="voice-duration">0:00</span>
                        <audio src="${message.media_url}" preload="metadata" style="display: none;"></audio>
                    </div>`;
                break;
            case 'document':
                const fileName = message.file_name || 'Document';
                const fileSize = message.file_size ? formatFileSize(message.file_size) : '';
                messageContent = `
                    <div class="document-message">
                        <div class="document-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="document-info">
                            <div class="document-name">${fileName}</div>
                            <div class="document-size">${fileSize}</div>
                        </div>
                        <a href="${message.media_url}" download="${fileName}" class="document-download">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>`;
                break;
            default:
                messageContent = linkify(message.message);
        }
        
        const messageTime = formatTime(message.created_at);
        const messageStatus = isMyMessage ? getMessageStatus(message) : '';
        
        const messageHtml = `
            <div class="message ${messageClass}" data-message-id="${message.id}">
                <div class="message-bubble">
                    <div class="message-content">${messageContent}</div>
                    <div class="message-meta">
                        <span class="message-time">${messageTime}</span>
                        ${messageStatus}
                    </div>
                </div>
            </div>
        `;
        
        chatMessages.insertAdjacentHTML('beforeend', messageHtml);
    }
    
    // Get message status
    function getMessageStatus(message) {
        if (message.is_read) {
            return '<i class="fas fa-check-double message-status read"></i>';
        } else if (message.delivered) {
            return '<i class="fas fa-check-double message-status delivered"></i>';
        } else {
            return '<i class="fas fa-check message-status sent"></i>';
        }
    }
    
    // Format time
    function formatTime(timestamp) {
        const date = new Date(timestamp);
        const now = new Date();
        const diff = now - date;
        
        if (diff < 24 * 60 * 60 * 1000) {
            return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        } else if (diff < 7 * 24 * 60 * 60 * 1000) {
            return date.toLocaleDateString([], { weekday: 'short' });
        } else {
            return date.toLocaleDateString([], { month: 'short', day: 'numeric' });
        }
    }
    
    // Linkify text
    function linkify(text) {
        const urlRegex = /(https?:\/\/[^\s]+)/g;
        return text.replace(urlRegex, '<a href="$1" target="_blank" rel="noopener noreferrer">$1</a>');
    }
    
    // Format file size
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    // Handle typing
    function handleTyping() {
        if (!selectedUserId) return;
        
        if (!isTyping) {
            isTyping = true;
            sendTypingIndicator(true);
        }
        
        clearTimeout(typingTimeout);
        typingTimeout = setTimeout(() => {
            isTyping = false;
            sendTypingIndicator(false);
        }, 1000);
    }
    
    // Send typing indicator
    function sendTypingIndicator(typing) {
        fetch('handlers/typing_indicator.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                receiver_id: selectedUserId,
                typing: typing
            })
        }).catch(error => console.error('Error sending typing indicator:', error));
    }
    
    // Handle key press
    function handleKeyPress(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    }
    
    // Send message
    function sendMessage() {
        const message = messageInput.value.trim();
        if (!message || !selectedUserId) return;
        
        const formData = new FormData();
        formData.append('receiver_id', selectedUserId);
        formData.append('message', message);
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
                    message: message,
                    message_type: 'text',
                    created_at: new Date().toISOString(),
                    is_read: false
                });
                messageInput.value = '';
                scrollToBottom();
                updateUsersList();
            }
        })
        .catch(error => console.error('Error sending message:', error));
    }
    
        // Media functions are now handled by media-handler.js
    
    // Initialize emoji picker
    function initializeEmojiPicker() {
        const categories = document.querySelectorAll('.emoji-category');
        categories.forEach(category => {
            category.addEventListener('click', () => {
                categories.forEach(c => c.classList.remove('active'));
                category.classList.add('active');
                loadEmojis(category.dataset.category);
            });
        });
        
        // Load default category
        loadEmojis('smileys');
    }
    
    // Load emojis
    function loadEmojis(category) {
        const categoryEmojis = emojis[category] || emojis.smileys;
        emojiGrid.innerHTML = '';
        
        categoryEmojis.forEach(emoji => {
            const button = document.createElement('button');
            button.className = 'emoji-item';
            button.textContent = emoji;
            button.addEventListener('click', () => insertEmoji(emoji));
            emojiGrid.appendChild(button);
        });
    }
    
    // Toggle emoji picker
    function toggleEmojiPicker(e) {
        e.stopPropagation();
        emojiPickerContainer.classList.toggle('active');
    }
    
    // Insert emoji
    function insertEmoji(emoji) {
        const cursorPos = messageInput.selectionStart;
        const textBefore = messageInput.value.substring(0, cursorPos);
        const textAfter = messageInput.value.substring(cursorPos);
        
        messageInput.value = textBefore + emoji + textAfter;
        messageInput.focus();
        messageInput.setSelectionRange(cursorPos + emoji.length, cursorPos + emoji.length);
        
        emojiPickerContainer.classList.remove('active');
    }
    
    // Handle search
    function handleSearch() {
        const query = searchInput.value.trim();
        loadUsers(query);
    }
    
    // Initiate video call
    function initiateVideoCall() {
        if (!selectedUserId) {
            showError('Please select a user to call');
            return;
        }
        
        if (window.webrtcManager) {
            window.webrtcManager.startCall(selectedUserId, true);
        } else {
            showError('Video calling not available');
        }
    }
    
    // Initiate voice call
    function initiateVoiceCall() {
        if (!selectedUserId) {
            showError('Please select a user to call');
            return;
        }
        
        if (window.webrtcManager) {
            window.webrtcManager.startCall(selectedUserId, false);
        } else {
            showError('Voice calling not available');
        }
    }
    
    // Scroll to bottom
    function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    // Update users list
    function updateUsersList() {
        loadUsers(searchInput.value);
    }
    
    // Show error
    function showError(message) {
        // Create a simple toast notification
        const toast = document.createElement('div');
        toast.className = 'toast error';
        toast.textContent = message;
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #e74c3c;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            z-index: 10000;
            animation: slideIn 0.3s ease-out;
        `;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
    
    // Start periodic updates
    function startPeriodicUpdates() {
        // Update messages every 2 seconds
        setInterval(() => {
            if (selectedUserId) {
                loadMessages(selectedUserId);
            }
        }, 2000);
        
        // Update users list every 10 seconds
        setInterval(() => {
            updateUsersList();
        }, 10000);
        
        // Update online status every 30 seconds
        setInterval(() => {
            fetch('handlers/update_status.php', { method: 'POST' })
                .catch(error => console.error('Error updating status:', error));
        }, 30000);
    }
    
    // Global functions for media
    window.playVoiceMessage = function(button) {
        const audio = button.parentElement.querySelector('audio');
        const icon = button.querySelector('i');
        
        if (audio.paused) {
            audio.play();
            icon.className = 'fas fa-pause';
        } else {
            audio.pause();
            icon.className = 'fas fa-play';
        }
        
        audio.onended = () => {
            icon.className = 'fas fa-play';
        };
    };
    
    window.openImageModal = function(src) {
        const modal = document.createElement('div');
        modal.className = 'image-modal';
        modal.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            cursor: pointer;
        `;
        
        const img = document.createElement('img');
        img.src = src;
        img.style.cssText = `
            max-width: 90%;
            max-height: 90%;
            border-radius: 8px;
        `;
        
        modal.appendChild(img);
        modal.addEventListener('click', () => modal.remove());
        document.body.appendChild(modal);
    };
});

// Add CSS for toast animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    .voice-message-container {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px;
        background: rgba(255,255,255,0.1);
        border-radius: 20px;
        min-width: 200px;
    }
    
    .voice-play-btn {
        width: 32px;
        height: 32px;
        border: none;
        background: var(--wa-green);
        color: white;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
    }
    
    .voice-waveform {
        flex: 1;
        height: 20px;
        background: repeating-linear-gradient(
            90deg,
            currentColor 0px,
            currentColor 2px,
            transparent 2px,
            transparent 4px
        );
        opacity: 0.5;
        border-radius: 2px;
    }
    
    .voice-duration {
        font-size: 12px;
        opacity: 0.7;
    }
    
    .no-users {
        padding: 20px;
        text-align: center;
        color: var(--wa-text-muted);
        font-style: italic;
    }
`;
document.head.appendChild(style);
