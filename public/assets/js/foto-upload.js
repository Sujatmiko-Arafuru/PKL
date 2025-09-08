/**
 * Foto Upload JavaScript Functions
 * Handles camera access, gallery upload, and photo processing
 */

let stream = null;
let currentFacingMode = 'user'; // 'user' for front camera, 'environment' for back camera
let capturedImageData = null;

/**
 * Show camera interface
 */
function showCamera() {
    document.getElementById('camera-section').classList.remove('hidden');
    document.getElementById('gallery-section').classList.add('hidden');
    document.getElementById('preview-section').classList.add('hidden');
    
    // Update active state
    document.querySelectorAll('.upload-option').forEach(option => option.classList.remove('active'));
    event.target.closest('.upload-option').classList.add('active');
    
    startCamera();
}

/**
 * Show gallery interface
 */
function showGallery() {
    document.getElementById('camera-section').classList.add('hidden');
    document.getElementById('gallery-section').classList.remove('hidden');
    document.getElementById('preview-section').classList.add('hidden');
    
    // Update active state
    document.querySelectorAll('.upload-option').forEach(option => option.classList.remove('active'));
    event.target.closest('.upload-option').classList.add('active');
}

/**
 * Start camera with error handling
 */
async function startCamera() {
    try {
        // Show loading state
        const video = document.getElementById('video');
        video.style.display = 'none';
        
        const constraints = {
            video: {
                facingMode: currentFacingMode,
                width: { ideal: 1280 },
                height: { ideal: 720 }
            }
        };
        
        stream = await navigator.mediaDevices.getUserMedia(constraints);
        video.srcObject = stream;
        video.style.display = 'block';
        
        console.log('Camera started successfully');
        
        // Add fade-in animation
        video.classList.add('fade-in');
        
    } catch (error) {
        console.error('Error starting camera:', error);
        
        // Show camera error message
        const cameraSection = document.getElementById('camera-section');
        cameraSection.innerHTML = `
            <div class="camera-error">
                <i class="bi bi-camera-video-off"></i>
                <h5>Tidak dapat mengakses kamera</h5>
                <p>Pastikan Anda memberikan izin akses kamera dan menggunakan browser yang mendukung.</p>
                <button type="button" class="btn btn-primary mt-3" onclick="showGallery()">
                    <i class="bi bi-images me-2"></i>Gunakan Galeri
                </button>
            </div>
        `;
    }
}

/**
 * Switch between front and back camera
 */
async function switchCamera() {
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
    }
    
    currentFacingMode = currentFacingMode === 'user' ? 'environment' : 'user';
    await startCamera();
}

/**
 * Stop camera
 */
function stopCamera() {
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
        stream = null;
    }
    document.getElementById('camera-section').classList.add('hidden');
}

/**
 * Capture photo from camera
 */
function capturePhoto() {
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const context = canvas.getContext('2d');
    
    // Set canvas size to match video
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    
    // Draw video frame to canvas
    context.drawImage(video, 0, 0, canvas.width, canvas.height);
    
    // Convert to blob with high quality
    canvas.toBlob(function(blob) {
        capturedImageData = blob;
        showPreview(URL.createObjectURL(blob));
    }, 'image/jpeg', 0.9);
}

/**
 * Handle gallery file selection
 */
function handleGallerySelect(event) {
    const file = event.target.files[0];
    if (file) {
        // Validate file type
        if (!file.type.match('image.*')) {
            showError('File yang dipilih bukan gambar!');
            return;
        }
        
        // Validate file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            showError('Ukuran file terlalu besar! Maksimal 2MB.');
            return;
        }
        
        // Validate image dimensions
        const img = new Image();
        img.onload = function() {
            if (this.width < 300 || this.height < 300) {
                showError('Resolusi foto terlalu rendah. Minimal 300x300 pixel.');
                return;
            }
            
            capturedImageData = file;
            showPreview(URL.createObjectURL(file));
        };
        
        img.onerror = function() {
            showError('File gambar tidak valid atau rusak.');
        };
        
        img.src = URL.createObjectURL(file);
    }
}

/**
 * Show preview of captured/selected photo
 */
function showPreview(imageUrl) {
    document.getElementById('preview-image').src = imageUrl;
    document.getElementById('preview-section').classList.remove('hidden');
    document.getElementById('camera-section').classList.add('hidden');
    document.getElementById('gallery-section').classList.add('hidden');
    
    // Add fade-in animation
    document.getElementById('preview-section').classList.add('fade-in');
}

/**
 * Save photo to server
 */
function savePhoto() {
    if (!capturedImageData) {
        showError('Tidak ada foto yang dipilih!');
        return;
    }
    
    // Show loading state
    const saveBtn = document.querySelector('.preview-btn.success');
    const originalText = saveBtn.innerHTML;
    saveBtn.classList.add('loading');
    saveBtn.innerHTML = '<span>Menyimpan...</span>';
    
    // Create a File object from the captured data
    const file = capturedImageData instanceof File ? capturedImageData : new File([capturedImageData], 'foto_peminjam.jpg', { type: 'image/jpeg' });
    
    // Set the file to the hidden input
    const dataTransfer = new DataTransfer();
    dataTransfer.items.add(file);
    document.getElementById('photo-file').files = dataTransfer.files;
    
    // Submit the form
    document.getElementById('photo-form').submit();
}

/**
 * Retake photo
 */
function retakePhoto() {
    capturedImageData = null;
    document.getElementById('preview-section').classList.add('hidden');
    
    // Show the appropriate section based on previous selection
    const cameraActive = document.querySelector('.upload-option:first-child').classList.contains('active');
    const galleryActive = document.querySelector('.upload-option:last-child').classList.contains('active');
    
    if (cameraActive) {
        showCamera();
    } else if (galleryActive) {
        showGallery();
    }
}

/**
 * Show error message
 */
function showError(message) {
    // Remove existing error messages
    const existingErrors = document.querySelectorAll('.error-message');
    existingErrors.forEach(error => error.remove());
    
    // Create new error message
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.innerHTML = `
        <i class="bi bi-exclamation-triangle me-2"></i>
        ${message}
        <button type="button" class="btn-close float-end" onclick="this.parentElement.remove()"></button>
    `;
    
    // Insert at the top of the card body
    const cardBody = document.querySelector('.card-body');
    cardBody.insertBefore(errorDiv, cardBody.firstChild);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (errorDiv.parentNode) {
            errorDiv.remove();
        }
    }, 5000);
}

/**
 * Show success message
 */
function showSuccess(message) {
    // Remove existing success messages
    const existingSuccess = document.querySelectorAll('.success-message');
    existingSuccess.forEach(success => success.remove());
    
    // Create new success message
    const successDiv = document.createElement('div');
    successDiv.className = 'success-message';
    successDiv.innerHTML = `
        <i class="bi bi-check-circle me-2"></i>
        ${message}
        <button type="button" class="btn-close float-end" onclick="this.parentElement.remove()"></button>
    `;
    
    // Insert at the top of the card body
    const cardBody = document.querySelector('.card-body');
    cardBody.insertBefore(successDiv, cardBody.firstChild);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (successDiv.parentNode) {
            successDiv.remove();
        }
    }, 5000);
}

/**
 * Clean up when page is unloaded
 */
window.addEventListener('beforeunload', function() {
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
    }
});

/**
 * Initialize page when DOM is loaded
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('Foto upload form loaded');
    
    // Check if device supports camera
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        console.log('Camera not supported on this device');
        const cameraOption = document.querySelector('.upload-option:first-child');
        if (cameraOption) {
            cameraOption.style.display = 'none';
        }
    }
    
    // Add keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            // Stop camera if active
            if (stream) {
                stopCamera();
            }
        }
    });
    
    // Add touch gestures for mobile
    let touchStartY = 0;
    let touchEndY = 0;
    
    document.addEventListener('touchstart', function(e) {
        touchStartY = e.changedTouches[0].screenY;
    });
    
    document.addEventListener('touchend', function(e) {
        touchEndY = e.changedTouches[0].screenY;
        handleSwipe();
    });
    
    function handleSwipe() {
        const swipeThreshold = 50;
        const diff = touchStartY - touchEndY;
        
        if (Math.abs(diff) > swipeThreshold) {
            if (diff > 0) {
                // Swipe up - could be used for additional actions
                console.log('Swipe up detected');
            } else {
                // Swipe down - could be used for additional actions
                console.log('Swipe down detected');
            }
        }
    }
});

/**
 * Utility function to check if device is mobile
 */
function isMobile() {
    return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
}

/**
 * Utility function to check if device has camera
 */
async function hasCamera() {
    try {
        const devices = await navigator.mediaDevices.enumerateDevices();
        return devices.some(device => device.kind === 'videoinput');
    } catch (error) {
        console.error('Error checking camera availability:', error);
        return false;
    }
}

/**
 * Utility function to get camera permissions
 */
async function requestCameraPermission() {
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ video: true });
        stream.getTracks().forEach(track => track.stop());
        return true;
    } catch (error) {
        console.error('Camera permission denied:', error);
        return false;
    }
}
