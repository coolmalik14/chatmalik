<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Media Upload Test - Fixed Version</title>
    <link rel="stylesheet" href="assets/css/whatsapp-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .test-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: var(--wa-bg-secondary, #ffffff);
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .test-header {
            text-align: center;
            padding: 20px;
            background: linear-gradient(135deg, #00a884, #128c7e);
            color: white;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        
        .test-section {
            background: var(--wa-bg-panel, #f8f9fa);
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .test-button {
            background: var(--wa-green, #00a884);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            margin: 10px;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        .test-button:hover {
            background: var(--wa-green-dark, #008069);
            transform: translateY(-2px);
        }
        
        .test-result {
            padding: 15px;
            border-radius: 8px;
            margin: 10px 0;
        }
        
        .success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }
        
        .error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        
        .info {
            background: #d1ecf1;
            color: #0c5460;
            border-left: 4px solid #17a2b8;
        }
        
        .media-preview {
            margin: 15px 0;
            padding: 15px;
            background: var(--wa-bg-secondary, #ffffff);
            border-radius: 8px;
            border: 1px solid var(--wa-border-light, #e0e0e0);
        }
        
        .status-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }
        
        .status-card {
            padding: 15px;
            background: var(--wa-bg-secondary, #ffffff);
            border-radius: 8px;
            text-align: center;
            border: 2px solid transparent;
            transition: all 0.3s;
        }
        
        .status-card.working {
            border-color: #28a745;
        }
        
        .status-card.fixed {
            border-color: #17a2b8;
        }
        
        .status-card.testing {
            border-color: #ffc107;
        }
        
        .hidden-inputs {
            display: none;
        }
    </style>
</head>
<body class="light-theme">
    <div class="test-container">
        <div class="test-header">
            <h1><i class="fas fa-tools"></i> Media Upload Test - Fixed Version</h1>
            <p>Testing voice notes, image uploads, and document sharing</p>
        </div>
        
        <div class="status-grid">
            <div class="status-card fixed">
                <h3><i class="fas fa-microphone"></i></h3>
                <h4>Voice Notes</h4>
                <p>Fixed & Enhanced</p>
            </div>
            <div class="status-card fixed">
                <h3><i class="fas fa-image"></i></h3>
                <h4>Image Upload</h4>
                <p>Fixed & Enhanced</p>
            </div>
            <div class="status-card fixed">
                <h3><i class="fas fa-file"></i></h3>
                <h4>Document Upload</h4>
                <p>Fixed & Enhanced</p>
            </div>
            <div class="status-card working">
                <h3><i class="fas fa-video"></i></h3>
                <h4>Video Calls</h4>
                <p>WebRTC Ready</p>
            </div>
        </div>
        
        <div class="test-section">
            <h2><i class="fas fa-microphone"></i> Voice Recording Test</h2>
            <p>Test the enhanced voice recording functionality:</p>
            <button class="test-button" onclick="testVoiceRecording()">
                <i class="fas fa-microphone"></i> Start Voice Recording Test
            </button>
            <div id="voice-test-result"></div>
        </div>
        
        <div class="test-section">
            <h2><i class="fas fa-image"></i> Image Upload Test</h2>
            <p>Test image upload with validation and preview:</p>
            <button class="test-button" onclick="document.getElementById('test-image').click()">
                <i class="fas fa-image"></i> Select Image to Upload
            </button>
            <input type="file" id="test-image" class="hidden-inputs" accept="image/*">
            <div id="image-test-result"></div>
        </div>
        
        <div class="test-section">
            <h2><i class="fas fa-file"></i> Document Upload Test</h2>
            <p>Test document upload with file type validation:</p>
            <button class="test-button" onclick="document.getElementById('test-document').click()">
                <i class="fas fa-file"></i> Select Document to Upload
            </button>
            <input type="file" id="test-document" class="hidden-inputs" accept=".pdf,.doc,.docx,.txt,.xls,.xlsx,.ppt,.pptx">
            <div id="document-test-result"></div>
        </div>
        
        <div class="test-section">
            <h2><i class="fas fa-video"></i> Camera & Microphone Access Test</h2>
            <p>Test camera and microphone permissions:</p>
            <button class="test-button" onclick="testCameraAccess()">
                <i class="fas fa-video"></i> Test Camera Access
            </button>
            <button class="test-button" onclick="testMicrophoneAccess()">
                <i class="fas fa-microphone"></i> Test Microphone Access
            </button>
            <div id="media-access-result"></div>
        </div>
        
        <div class="test-section">
            <h2><i class="fas fa-check-circle"></i> System Status</h2>
            <div id="system-status">
                <div class="test-result info">
                    <i class="fas fa-spinner fa-spin"></i> Checking system status...
                </div>
            </div>
        </div>
        
        <div class="test-section">
            <h2><i class="fas fa-bug"></i> Issues Fixed</h2>
            <div class="test-result success">
                <h4>✅ Voice Recording Issues Fixed:</h4>
                <ul>
                    <li>Multiple conflicting event listeners removed</li>
                    <li>Proper MediaRecorder implementation</li>
                    <li>Better error handling and user feedback</li>
                    <li>Visual recording indicators</li>
                </ul>
            </div>
            
            <div class="test-result success">
                <h4>✅ Image Upload Issues Fixed:</h4>
                <ul>
                    <li>File type validation implemented</li>
                    <li>File size limits enforced</li>
                    <li>Proper image preview and modal</li>
                    <li>Loading states and error handling</li>
                </ul>
            </div>
            
            <div class="test-result success">
                <h4>✅ Document Upload Issues Fixed:</h4>
                <ul>
                    <li>SQL query bug in handler fixed</li>
                    <li>File type validation for documents</li>
                    <li>Download functionality implemented</li>
                    <li>File size display and limits</li>
                </ul>
            </div>
            
            <div class="test-result success">
                <h4>✅ General Improvements:</h4>
                <ul>
                    <li>Unified media handler system</li>
                    <li>Better user notifications</li>
                    <li>Improved error messages</li>
                    <li>Mobile-responsive design</li>
                </ul>
            </div>
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="index.php" class="test-button" style="text-decoration: none;">
                <i class="fas fa-comments"></i> Test in Real Chat
            </a>
            <a href="whatsapp-demo.php" class="test-button" style="text-decoration: none; background: #6c757d;">
                <i class="fas fa-home"></i> Back to Demo
            </a>
        </div>
    </div>
    
    <script src="assets/js/media-handler.js"></script>
    <script>
        let mediaRecorder;
        let audioChunks = [];
        let isRecording = false;
        
        // System status check
        document.addEventListener('DOMContentLoaded', function() {
            checkSystemStatus();
            setupTestHandlers();
        });
        
        function checkSystemStatus() {
            const statusDiv = document.getElementById('system-status');
            let checks = [];
            
            // Check MediaRecorder support
            if (typeof MediaRecorder !== 'undefined') {
                checks.push('<div class="test-result success">✓ MediaRecorder API supported</div>');
            } else {
                checks.push('<div class="test-result error">✗ MediaRecorder API not supported</div>');
            }
            
            // Check getUserMedia support
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                checks.push('<div class="test-result success">✓ getUserMedia API supported</div>');
            } else {
                checks.push('<div class="test-result error">✗ getUserMedia API not supported</div>');
            }
            
            // Check File API support
            if (window.File && window.FileReader && window.FileList && window.Blob) {
                checks.push('<div class="test-result success">✓ File API supported</div>');
            } else {
                checks.push('<div class="test-result error">✗ File API not supported</div>');
            }
            
            // Check if running on HTTPS or localhost
            if (location.protocol === 'https:' || location.hostname === 'localhost') {
                checks.push('<div class="test-result success">✓ Secure context (HTTPS or localhost)</div>');
            } else {
                checks.push('<div class="test-result error">✗ Not in secure context (HTTPS required for media access)</div>');
            }
            
            statusDiv.innerHTML = checks.join('');
        }
        
        function setupTestHandlers() {
            // Image upload test
            document.getElementById('test-image').addEventListener('change', function(e) {
                const file = e.target.files[0];
                const resultDiv = document.getElementById('image-test-result');
                
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        resultDiv.innerHTML = `
                            <div class="test-result success">
                                <h4>✓ Image loaded successfully!</h4>
                                <p><strong>File:</strong> ${file.name}</p>
                                <p><strong>Size:</strong> ${formatFileSize(file.size)}</p>
                                <p><strong>Type:</strong> ${file.type}</p>
                                <div class="media-preview">
                                    <img src="${e.target.result}" style="max-width: 200px; max-height: 150px; border-radius: 5px;">
                                </div>
                            </div>
                        `;
                    };
                    reader.readAsDataURL(file);
                } else {
                    resultDiv.innerHTML = '<div class="test-result error">No file selected</div>';
                }
            });
            
            // Document upload test
            document.getElementById('test-document').addEventListener('change', function(e) {
                const file = e.target.files[0];
                const resultDiv = document.getElementById('document-test-result');
                
                if (file) {
                    const allowedTypes = ['pdf', 'doc', 'docx', 'txt', 'xls', 'xlsx', 'ppt', 'pptx'];
                    const fileExtension = file.name.split('.').pop().toLowerCase();
                    
                    if (allowedTypes.includes(fileExtension)) {
                        resultDiv.innerHTML = `
                            <div class="test-result success">
                                <h4>✓ Document validated successfully!</h4>
                                <p><strong>File:</strong> ${file.name}</p>
                                <p><strong>Size:</strong> ${formatFileSize(file.size)}</p>
                                <p><strong>Type:</strong> ${file.type}</p>
                                <p><strong>Extension:</strong> ${fileExtension}</p>
                            </div>
                        `;
                    } else {
                        resultDiv.innerHTML = `
                            <div class="test-result error">
                                <h4>✗ Invalid file type!</h4>
                                <p>Only PDF, DOC, DOCX, TXT, XLS, XLSX, PPT, PPTX files are allowed.</p>
                            </div>
                        `;
                    }
                } else {
                    resultDiv.innerHTML = '<div class="test-result error">No file selected</div>';
                }
            });
        }
        
        async function testVoiceRecording() {
            const resultDiv = document.getElementById('voice-test-result');
            const button = event.target;
            
            if (!isRecording) {
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                    mediaRecorder = new MediaRecorder(stream);
                    
                    audioChunks = [];
                    
                    mediaRecorder.ondataavailable = (e) => {
                        audioChunks.push(e.data);
                    };
                    
                    mediaRecorder.onstop = () => {
                        const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
                        const audioUrl = URL.createObjectURL(audioBlob);
                        
                        resultDiv.innerHTML = `
                            <div class="test-result success">
                                <h4>✓ Voice recording completed!</h4>
                                <p><strong>Duration:</strong> ${audioChunks.length} chunks</p>
                                <p><strong>Size:</strong> ${formatFileSize(audioBlob.size)}</p>
                                <div class="media-preview">
                                    <audio controls style="width: 100%; margin: 10px 0;">
                                        <source src="${audioUrl}" type="audio/wav">
                                    </audio>
                                </div>
                            </div>
                        `;
                        
                        stream.getTracks().forEach(track => track.stop());
                    };
                    
                    mediaRecorder.start();
                    isRecording = true;
                    button.innerHTML = '<i class="fas fa-stop"></i> Stop Recording';
                    button.style.background = '#dc3545';
                    
                    resultDiv.innerHTML = `
                        <div class="test-result info">
                            <i class="fas fa-microphone"></i> Recording in progress... Click stop when done.
                        </div>
                    `;
                    
                } catch (error) {
                    resultDiv.innerHTML = `
                        <div class="test-result error">
                            <h4>✗ Recording failed!</h4>
                            <p><strong>Error:</strong> ${error.message}</p>
                            <p>Please allow microphone access and try again.</p>
                        </div>
                    `;
                }
            } else {
                mediaRecorder.stop();
                isRecording = false;
                button.innerHTML = '<i class="fas fa-microphone"></i> Start Voice Recording Test';
                button.style.background = '#00a884';
            }
        }
        
        async function testCameraAccess() {
            const resultDiv = document.getElementById('media-access-result');
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
                resultDiv.innerHTML = '<div class="test-result success">✓ Camera and microphone access granted!</div>';
                stream.getTracks().forEach(track => track.stop());
            } catch (error) {
                resultDiv.innerHTML = `<div class="test-result error">✗ Camera access failed: ${error.message}</div>`;
            }
        }
        
        async function testMicrophoneAccess() {
            const resultDiv = document.getElementById('media-access-result');
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                resultDiv.innerHTML = '<div class="test-result success">✓ Microphone access granted!</div>';
                stream.getTracks().forEach(track => track.stop());
            } catch (error) {
                resultDiv.innerHTML = `<div class="test-result error">✗ Microphone access failed: ${error.message}</div>`;
            }
        }
        
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
    </script>
</body>
</html>
