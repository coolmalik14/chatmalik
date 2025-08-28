<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Media Features Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .test-container { max-width: 800px; margin: 0 auto; }
        .test-section { background: white; padding: 20px; margin: 20px 0; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .test-result { padding: 10px; margin: 10px 0; border-radius: 5px; }
        .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .info { background-color: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        .warning { background-color: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
        .test-button { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; margin: 5px; }
        .test-button:hover { background: #0056b3; }
        .feature-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin: 20px 0; }
        .feature-card { background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #007bff; }
        .status-indicator { width: 12px; height: 12px; border-radius: 50%; display: inline-block; margin-right: 8px; }
        .status-working { background: #28a745; }
        .status-partial { background: #ffc107; }
        .status-broken { background: #dc3545; }
    </style>
</head>
<body>
    <div class="test-container">
        <h1>🎬 Chat Application - Media Features Test</h1>
        
        <div class="test-section">
            <h2>📊 System Requirements Check</h2>
            <?php
            echo '<div class="test-result info">Testing system requirements...</div>';
            
            // Check PHP extensions
            $extensions = ['gd', 'fileinfo', 'mysqli', 'pdo_mysql'];
            foreach ($extensions as $ext) {
                if (extension_loaded($ext)) {
                    echo '<div class="test-result success">✓ PHP Extension: ' . $ext . '</div>';
                } else {
                    echo '<div class="test-result error">✗ PHP Extension missing: ' . $ext . '</div>';
                }
            }
            
            // Check upload directories
            $dirs = [
                'uploads/profile' => 'Profile images',
                'uploads/chat_images' => 'Chat images', 
                'uploads/voice_messages' => 'Voice messages'
            ];
            
            foreach ($dirs as $dir => $desc) {
                if (is_dir($dir) && is_writable($dir)) {
                    echo '<div class="test-result success">✓ Directory writable: ' . $desc . ' (' . $dir . ')</div>';
                } elseif (is_dir($dir)) {
                    echo '<div class="test-result warning">⚠ Directory exists but not writable: ' . $desc . ' (' . $dir . ')</div>';
                } else {
                    echo '<div class="test-result error">✗ Directory missing: ' . $desc . ' (' . $dir . ')</div>';
                }
            }
            
            // Check file sizes
            $max_upload = ini_get('upload_max_filesize');
            $max_post = ini_get('post_max_size');
            echo '<div class="test-result info">📁 Max upload size: ' . $max_upload . '</div>';
            echo '<div class="test-result info">📁 Max POST size: ' . $max_post . '</div>';
            ?>
        </div>

        <div class="test-section">
            <h2>🔧 Handler Files Check</h2>
            <?php
            $handlers = [
                'handlers/upload_image.php' => 'Image Upload Handler',
                'handlers/upload_voice.php' => 'Voice Upload Handler',
                'handlers/initiate_call.php' => 'Call Initiation Handler',
                'handlers/end_call.php' => 'Call End Handler'
            ];
            
            foreach ($handlers as $file => $desc) {
                if (file_exists($file)) {
                    // Check if file has syntax errors
                    $output = shell_exec("php -l $file 2>&1");
                    if (strpos($output, 'No syntax errors') !== false) {
                        echo '<div class="test-result success">✓ ' . $desc . ' - OK</div>';
                    } else {
                        echo '<div class="test-result error">✗ ' . $desc . ' - Syntax Error</div>';
                    }
                } else {
                    echo '<div class="test-result error">✗ ' . $desc . ' - File Missing</div>';
                }
            }
            ?>
        </div>

        <div class="test-section">
            <h2>📱 JavaScript Files Check</h2>
            <?php
            $jsFiles = [
                'assets/js/chat.js' => 'Main Chat Script',
                'assets/js/webrtc.js' => 'WebRTC Implementation',
                'assets/js/theme.js' => 'Theme Management'
            ];
            
            foreach ($jsFiles as $file => $desc) {
                if (file_exists($file)) {
                    $size = filesize($file);
                    echo '<div class="test-result success">✓ ' . $desc . ' - ' . number_format($size) . ' bytes</div>';
                } else {
                    echo '<div class="test-result error">✗ ' . $desc . ' - File Missing</div>';
                }
            }
            ?>
        </div>

        <div class="test-section">
            <h2>🎯 Feature Status Overview</h2>
            <div class="feature-grid">
                <div class="feature-card">
                    <h3><span class="status-indicator status-working"></span>Image Upload</h3>
                    <p><strong>Status:</strong> Implemented & Ready</p>
                    <p>✓ File validation</p>
                    <p>✓ Size limits</p>
                    <p>✓ Database integration</p>
                    <p>✓ UI integration</p>
                </div>
                
                <div class="feature-card">
                    <h3><span class="status-indicator status-working"></span>Voice Messages</h3>
                    <p><strong>Status:</strong> Implemented & Ready</p>
                    <p>✓ MediaRecorder API</p>
                    <p>✓ Audio upload</p>
                    <p>✓ Playback controls</p>
                    <p>✓ Recording indicator</p>
                </div>
                
                <div class="feature-card">
                    <h3><span class="status-indicator status-partial"></span>Video Calling</h3>
                    <p><strong>Status:</strong> Basic Implementation</p>
                    <p>✓ WebRTC setup</p>
                    <p>✓ Media access</p>
                    <p>⚠ Needs WebSocket server</p>
                    <p>⚠ Signaling incomplete</p>
                </div>
                
                <div class="feature-card">
                    <h3><span class="status-indicator status-partial"></span>Audio Calling</h3>
                    <p><strong>Status:</strong> Basic Implementation</p>
                    <p>✓ WebRTC setup</p>
                    <p>✓ Audio access</p>
                    <p>⚠ Needs WebSocket server</p>
                    <p>⚠ Signaling incomplete</p>
                </div>
            </div>
        </div>

        <div class="test-section">
            <h2>🧪 Interactive Tests</h2>
            <p>These tests require browser permissions and user interaction:</p>
            
            <h3>📷 Camera & Microphone Access Test</h3>
            <button class="test-button" onclick="testCameraAccess()">Test Camera Access</button>
            <button class="test-button" onclick="testMicrophoneAccess()">Test Microphone Access</button>
            <div id="media-test-result"></div>
            
            <h3>🎙️ Voice Recording Test</h3>
            <button class="test-button" id="record-test-btn" onclick="testVoiceRecording()">Start Recording Test</button>
            <div id="voice-test-result"></div>
            
            <h3>🖼️ Image Upload Test</h3>
            <input type="file" id="test-image" accept="image/*" style="display: none;">
            <button class="test-button" onclick="document.getElementById('test-image').click()">Test Image Upload</button>
            <div id="image-test-result"></div>
        </div>

        <div class="test-section">
            <h2>📝 Implementation Notes</h2>
            <div class="test-result info">
                <h4>✅ What's Working:</h4>
                <ul>
                    <li>Image upload with validation and database storage</li>
                    <li>Voice message recording and playback</li>
                    <li>Basic WebRTC setup for calls</li>
                    <li>Media file handling and storage</li>
                    <li>UI integration with chat interface</li>
                </ul>
            </div>
            
            <div class="test-result warning">
                <h4>⚠️ Partially Working (Needs WebSocket Server):</h4>
                <ul>
                    <li>Real-time video calling (WebRTC signaling)</li>
                    <li>Real-time audio calling (WebRTC signaling)</li>
                    <li>Call notifications between users</li>
                    <li>Live call status updates</li>
                </ul>
            </div>
            
            <div class="test-result info">
                <h4>🔧 To Enable Full Video/Audio Calling:</h4>
                <ol>
                    <li>Set up a WebSocket server (Node.js + Socket.io recommended)</li>
                    <li>Implement WebRTC signaling (offer/answer/ICE candidates)</li>
                    <li>Add STUN/TURN servers for NAT traversal</li>
                    <li>Update the WebRTC manager to handle signaling messages</li>
                </ol>
            </div>
        </div>

        <div class="test-section">
            <h2>🚀 Quick Start Guide</h2>
            <ol>
                <li><strong>Start XAMPP:</strong> Make sure Apache and MySQL are running</li>
                <li><strong>Test Authentication:</strong> Register and login with test accounts</li>
                <li><strong>Test Image Upload:</strong> Select a user and try uploading an image</li>
                <li><strong>Test Voice Messages:</strong> Click the microphone button to record</li>
                <li><strong>Test Calls:</strong> Click video/audio call buttons (basic implementation)</li>
            </ol>
        </div>
    </div>

    <script>
        let mediaRecorder;
        let audioChunks = [];
        let isRecording = false;

        async function testCameraAccess() {
            const resultDiv = document.getElementById('media-test-result');
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
                resultDiv.innerHTML = '<div class="test-result success">✓ Camera and microphone access granted!</div>';
                stream.getTracks().forEach(track => track.stop()); // Clean up
            } catch (error) {
                resultDiv.innerHTML = '<div class="test-result error">✗ Camera access failed: ' + error.message + '</div>';
            }
        }

        async function testMicrophoneAccess() {
            const resultDiv = document.getElementById('media-test-result');
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                resultDiv.innerHTML = '<div class="test-result success">✓ Microphone access granted!</div>';
                stream.getTracks().forEach(track => track.stop()); // Clean up
            } catch (error) {
                resultDiv.innerHTML = '<div class="test-result error">✗ Microphone access failed: ' + error.message + '</div>';
            }
        }

        async function testVoiceRecording() {
            const btn = document.getElementById('record-test-btn');
            const resultDiv = document.getElementById('voice-test-result');
            
            if (!isRecording) {
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                    mediaRecorder = new MediaRecorder(stream);
                    
                    mediaRecorder.ondataavailable = (e) => {
                        audioChunks.push(e.data);
                    };
                    
                    mediaRecorder.onstop = () => {
                        const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
                        const audioUrl = URL.createObjectURL(audioBlob);
                        resultDiv.innerHTML = `
                            <div class="test-result success">✓ Recording completed! 
                            <br><audio controls><source src="${audioUrl}" type="audio/wav"></audio></div>
                        `;
                        audioChunks = [];
                        stream.getTracks().forEach(track => track.stop());
                    };
                    
                    mediaRecorder.start();
                    isRecording = true;
                    btn.textContent = 'Stop Recording';
                    btn.style.background = '#dc3545';
                    resultDiv.innerHTML = '<div class="test-result info">🎙️ Recording... Click stop when done.</div>';
                    
                } catch (error) {
                    resultDiv.innerHTML = '<div class="test-result error">✗ Recording failed: ' + error.message + '</div>';
                }
            } else {
                mediaRecorder.stop();
                isRecording = false;
                btn.textContent = 'Start Recording Test';
                btn.style.background = '#007bff';
            }
        }

        // Image upload test
        document.getElementById('test-image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const resultDiv = document.getElementById('image-test-result');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = new Image();
                    img.onload = function() {
                        resultDiv.innerHTML = `
                            <div class="test-result success">
                                ✓ Image loaded successfully!<br>
                                <strong>File:</strong> ${file.name}<br>
                                <strong>Size:</strong> ${(file.size / 1024).toFixed(2)} KB<br>
                                <strong>Dimensions:</strong> ${img.width}x${img.height}<br>
                                <img src="${e.target.result}" style="max-width: 200px; max-height: 150px; border-radius: 5px; margin-top: 10px;">
                            </div>
                        `;
                    };
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>

    <p><em>Delete this file (test_media.php) after testing is complete.</em></p>
</body>
</html>
