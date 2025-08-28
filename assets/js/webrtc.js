// WebRTC Implementation for Video/Audio Calling
class WebRTCManager {
    constructor() {
        this.localStream = null;
        this.remoteStream = null;
        this.peerConnection = null;
        this.isCallActive = false;
        this.isVideoCall = false;
        this.currentCallId = null;
        
        this.configuration = {
            iceServers: [
                { urls: 'stun:stun.l.google.com:19302' },
                { urls: 'stun:stun1.l.google.com:19302' }
            ]
        };
        
        this.init();
    }
    
    init() {
        // Get DOM elements
        this.callModal = document.querySelector('.call-modal');
        this.videoContainer = document.querySelector('.video-container');
        this.localVideo = document.getElementById('local-video');
        this.remoteVideo = document.getElementById('remote-video');
        this.callerImg = document.getElementById('caller-img');
        this.callerName = document.getElementById('caller-name');
        
        // Bind event listeners
        this.bindEvents();
    }
    
    bindEvents() {
        // Call control buttons
        document.querySelector('.accept-call')?.addEventListener('click', () => this.acceptCall());
        document.querySelector('.decline-call')?.addEventListener('click', () => this.declineCall());
        document.querySelector('.end-call')?.addEventListener('click', () => this.endCall());
        
        // Video controls
        document.querySelector('.toggle-video')?.addEventListener('click', () => this.toggleVideo());
        document.querySelector('.toggle-audio')?.addEventListener('click', () => this.toggleAudio());
    }
    
    async startCall(receiverId, isVideo = false) {
        try {
            this.isVideoCall = isVideo;
            
            // Get user media
            this.localStream = await navigator.mediaDevices.getUserMedia({
                video: isVideo,
                audio: true
            });
            
            if (this.localVideo) {
                this.localVideo.srcObject = this.localStream;
            }
            
            // Create peer connection
            this.createPeerConnection();
            
            // Add local stream to peer connection
            this.localStream.getTracks().forEach(track => {
                this.peerConnection.addTrack(track, this.localStream);
            });
            
            // Send call initiation to server
            const response = await fetch('handlers/initiate_call.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    receiver_id: receiverId,
                    call_type: isVideo ? 'video' : 'audio'
                })
            });
            
            const data = await response.json();
            if (data.status === 'success') {
                this.currentCallId = data.call_id;
                this.showCallInterface('Calling...', isVideo);
                
                // Create and send offer
                const offer = await this.peerConnection.createOffer();
                await this.peerConnection.setLocalDescription(offer);
                
                // In a real implementation, send offer through WebSocket
                console.log('Call offer created:', offer);
            } else {
                throw new Error(data.message);
            }
            
        } catch (error) {
            console.error('Error starting call:', error);
            alert('Failed to start call: ' + error.message);
            this.cleanup();
        }
    }
    
    createPeerConnection() {
        this.peerConnection = new RTCPeerConnection(this.configuration);
        
        // Handle remote stream
        this.peerConnection.ontrack = (event) => {
            console.log('Received remote stream');
            this.remoteStream = event.streams[0];
            if (this.remoteVideo) {
                this.remoteVideo.srcObject = this.remoteStream;
            }
        };
        
        // Handle ICE candidates
        this.peerConnection.onicecandidate = (event) => {
            if (event.candidate) {
                console.log('New ICE candidate:', event.candidate);
                // In a real implementation, send candidate through WebSocket
            }
        };
        
        // Handle connection state changes
        this.peerConnection.onconnectionstatechange = () => {
            console.log('Connection state:', this.peerConnection.connectionState);
            if (this.peerConnection.connectionState === 'connected') {
                this.updateCallStatus('Connected');
            } else if (this.peerConnection.connectionState === 'disconnected') {
                this.endCall();
            }
        };
    }
    
    showCallInterface(status, isVideo) {
        this.isCallActive = true;
        
        if (this.callModal) {
            this.callModal.style.display = 'flex';
            const statusEl = this.callModal.querySelector('.call-status');
            if (statusEl) statusEl.textContent = status;
        }
        
        if (isVideo && this.videoContainer) {
            this.videoContainer.style.display = 'block';
        }
    }
    
    hideCallInterface() {
        if (this.callModal) {
            this.callModal.style.display = 'none';
        }
        if (this.videoContainer) {
            this.videoContainer.style.display = 'none';
        }
        this.isCallActive = false;
    }
    
    updateCallStatus(status) {
        const statusEl = this.callModal?.querySelector('.call-status');
        if (statusEl) statusEl.textContent = status;
    }
    
    async acceptCall() {
        try {
            // Get user media
            this.localStream = await navigator.mediaDevices.getUserMedia({
                video: this.isVideoCall,
                audio: true
            });
            
            if (this.localVideo) {
                this.localVideo.srcObject = this.localStream;
            }
            
            // Create peer connection and add stream
            this.createPeerConnection();
            this.localStream.getTracks().forEach(track => {
                this.peerConnection.addTrack(track, this.localStream);
            });
            
            // In a real implementation, handle the offer from the caller
            // and create an answer
            
            this.updateCallStatus('Connected');
            
        } catch (error) {
            console.error('Error accepting call:', error);
            alert('Failed to accept call: ' + error.message);
            this.declineCall();
        }
    }
    
    declineCall() {
        if (this.currentCallId) {
            // Send decline to server
            fetch('handlers/end_call.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    call_id: this.currentCallId,
                    status: 'declined'
                })
            });
        }
        
        this.cleanup();
        this.hideCallInterface();
    }
    
    endCall() {
        if (this.currentCallId) {
            // Send end call to server
            fetch('handlers/end_call.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    call_id: this.currentCallId,
                    status: 'ended'
                })
            });
        }
        
        this.cleanup();
        this.hideCallInterface();
    }
    
    toggleVideo() {
        if (this.localStream) {
            const videoTrack = this.localStream.getVideoTracks()[0];
            if (videoTrack) {
                videoTrack.enabled = !videoTrack.enabled;
                const btn = document.querySelector('.toggle-video');
                if (btn) {
                    btn.innerHTML = videoTrack.enabled ? 
                        '<i class="fas fa-video"></i>' : 
                        '<i class="fas fa-video-slash"></i>';
                }
            }
        }
    }
    
    toggleAudio() {
        if (this.localStream) {
            const audioTrack = this.localStream.getAudioTracks()[0];
            if (audioTrack) {
                audioTrack.enabled = !audioTrack.enabled;
                const btn = document.querySelector('.toggle-audio');
                if (btn) {
                    btn.innerHTML = audioTrack.enabled ? 
                        '<i class="fas fa-microphone"></i>' : 
                        '<i class="fas fa-microphone-slash"></i>';
                }
            }
        }
    }
    
    cleanup() {
        // Stop local stream
        if (this.localStream) {
            this.localStream.getTracks().forEach(track => track.stop());
            this.localStream = null;
        }
        
        // Close peer connection
        if (this.peerConnection) {
            this.peerConnection.close();
            this.peerConnection = null;
        }
        
        // Clear video elements
        if (this.localVideo) this.localVideo.srcObject = null;
        if (this.remoteVideo) this.remoteVideo.srcObject = null;
        
        this.remoteStream = null;
        this.currentCallId = null;
        this.isCallActive = false;
    }
    
    // Handle incoming call (to be called by WebSocket or polling)
    handleIncomingCall(callData) {
        this.currentCallId = callData.call_id;
        this.isVideoCall = callData.call_type === 'video';
        
        if (this.callerName) this.callerName.textContent = callData.caller_name;
        if (this.callerImg) this.callerImg.src = `uploads/profile/${callData.caller_pic}`;
        
        this.showCallInterface(`Incoming ${callData.call_type} call...`, this.isVideoCall);
    }
}

// Initialize WebRTC manager when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.webrtcManager = new WebRTCManager();
});
