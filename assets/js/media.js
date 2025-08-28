document.addEventListener('DOMContentLoaded', function() {
    const imageUpload = document.getElementById('image-upload');
    const voiceRecordBtn = document.querySelector('.voice-record-btn');
    const videoCallBtn = document.querySelector('.video-call-btn');
    const voiceCallBtn = document.querySelector('.voice-call-btn');
    const callModal = document.getElementById('callModal');
    const acceptCallBtn = document.getElementById('acceptCall');
    const endCallBtn = document.getElementById('endCall');
    
    let mediaRecorder;
    let audioChunks = [];
    let localStream;
    let peerConnection;
    let isRecording = false;

    // Image Upload Handler
    imageUpload.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const formData = new FormData();
            formData.append('image', file);
            formData.append('receiver_id', selectedUserId);
            
            fetch('handlers/upload_image.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    appendMessage({
                        message: `<img src="${data.image_url}" alt="Shared Image">`,
                        sender_id: currentUserId,
                        message_type: 'image'
                    });
                }
            });
        }
    });

    // Voice Recording
    voiceRecordBtn.addEventListener('click', async function() {
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
                    formData.append('audio', audioBlob);
                    formData.append('receiver_id', selectedUserId);
                    
                    const response = await fetch('handlers/upload_voice.php', {
                        method: 'POST',
                        body: formData
                    });
                    
                    const data = await response.json();
                    if (data.status === 'success') {
                        appendMessage({
                            message: `
                                <audio controls class="voice-player">
                                    <source src="${data.voice_url}" type="audio/wav">
                                </audio>
                            `,
                            sender_id: currentUserId,
                            message_type: 'voice'
                        });
                    }
                    
                    audioChunks = [];
                };
                
                mediaRecorder.start();
                isRecording = true;
                voiceRecordBtn.classList.add('recording');
            } catch (err) {
                console.error('Error accessing microphone:', err);
                alert('Could not access microphone');
            }
        } else {
            mediaRecorder.stop();
            isRecording = false;
            voiceRecordBtn.classList.remove('recording');
        }
    });

    // Video Call Handling
    async function initializeCall(isVideo = true) {
        try {
            localStream = await navigator.mediaDevices.getUserMedia({
                audio: true,
                video: isVideo
            });
            
            const configuration = {
                iceServers: [
                    { urls: 'stun:stun.l.google.com:19302' }
                ]
            };
            
            peerConnection = new RTCPeerConnection(configuration);
            
            localStream.getTracks().forEach(track => {
                peerConnection.addTrack(track, localStream);
            });
            
            peerConnection.ontrack = (event) => {
                const remoteVideo = document.querySelector('#remoteVideo video');
                if (remoteVideo.srcObject !== event.streams[0]) {
                    remoteVideo.srcObject = event.streams[0];
                }
            };
            
            return true;
        } catch (err) {
            console.error('Error initializing call:', err);
            alert('Could not access camera/microphone');
            return false;
        }
    }

    videoCallBtn.addEventListener('click', async function() {
        if (await initializeCall(true)) {
            // Send call request to server
            fetch('handlers/initiate_call.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    receiver_id: selectedUserId,
                    call_type: 'video'
                })
            });
            
            showCallModal('Calling...', true);
        }
    });

    voiceCallBtn.addEventListener('click', async function() {
        if (await initializeCall(false)) {
            fetch('handlers/initiate_call.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    receiver_id: selectedUserId,
                    call_type: 'audio'
                })
            });
            
            showCallModal('Calling...', false);
        }
    });

    function showCallModal(status, isVideo) {
        callModal.classList.add('active');
        document.getElementById('callStatus').textContent = status;
        document.getElementById('remoteVideo').style.display = isVideo ? 'block' : 'none';
    }

    endCallBtn.addEventListener('click', function() {
        if (localStream) {
            localStream.getTracks().forEach(track => track.stop());
        }
        if (peerConnection) {
            peerConnection.close();
        }
        callModal.classList.remove('active');
        
        fetch('handlers/end_call.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                receiver_id: selectedUserId
            })
        });
    });

    // WebSocket connection for real-time call signaling
    const ws = new WebSocket('ws://' + window.location.hostname + ':8080');
    
    ws.onmessage = async function(event) {
        const data = JSON.parse(event.data);
        
        switch(data.type) {
            case 'incoming_call':
                showCallModal('Incoming call from ' + data.caller_name, data.call_type === 'video');
                break;
                
            case 'call_accepted':
                document.getElementById('callStatus').textContent = 'Connected';
                // Handle WebRTC connection
                break;
                
            case 'call_ended':
                endCallBtn.click();
                break;
        }
    };
});
