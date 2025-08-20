// YouTube Video Protection System JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Initialize video protection for all video containers
    initializeVideoProtection();
});

function initializeVideoProtection() {
    // Find all video containers
    const videoContainers = document.querySelectorAll('.video-protection-container');
    const facebookContainers = document.querySelectorAll('.facebook-protection-container');
    const platformContainers = document.querySelectorAll('.platform-protection-container');
    
    videoContainers.forEach(container => {
        // Add YouTube protection overlays
        addProtectionOverlays(container, 'youtube');
        
        // Add event listeners
        addProtectionEventListeners(container);
    });
    
    facebookContainers.forEach(container => {
        // Add Facebook protection overlays
        addProtectionOverlays(container, 'facebook');
        
        // Add event listeners
        addProtectionEventListeners(container);
    });
    
    platformContainers.forEach(container => {
        // Add Platform protection overlays
        addProtectionOverlays(container, 'platform');
        
        // Add event listeners
        addProtectionEventListeners(container);
    });
}

function addProtectionOverlays(container, platform = 'youtube') {
    // Create protection overlays
    const overlays = [
        {
            className: 'youtube-logo-blocker',
            title: 'Protected YouTube branding area'
        },
        {
            className: 'youtube-bottom-left-blocker',
            title: 'YouTube branding blocked'
        },
        {
            className: 'youtube-bottom-right-blocker',
            title: 'YouTube branding blocked'
        },
        {
            className: 'youtube-brand-overlay-1',
            title: 'YouTube branding blocked'
        },

        {
            className: 'youtube-right-corner-patch-2',
            title: 'Right corner protected'
        }
    ];
    
    overlays.forEach(overlay => {
        const element = document.createElement('div');
        element.className = overlay.className;
        element.title = overlay.title;
        element.setAttribute('data-protection', 'true');
        container.appendChild(element);
    });
    
    // Add permanent black patches (only center bottom)
    const blackPatches = [
        {
            className: 'video-id-blocker'
        },
        {
            className: 'youtube-brand-overlay-2'
        }
    ];
    
    blackPatches.forEach(patch => {
        const element = document.createElement('div');
        element.className = patch.className;
        container.appendChild(element);
    });
    
    // Add security indicator
    const securityIndicator = document.createElement('div');
    securityIndicator.className = 'security-indicator';
    securityIndicator.innerHTML = '🔒';
    container.appendChild(securityIndicator);
}

function addProtectionEventListeners(container) {
    // Get all protection elements
    const protectionElements = container.querySelectorAll('[data-protection="true"]');
    
    protectionElements.forEach(element => {
        // Prevent all interactions
        element.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            showProtectionMessage('YouTube branding protected');
        });
        
        element.addEventListener('mousedown', function(e) {
            e.preventDefault();
            e.stopPropagation();
        });
        
        element.addEventListener('mouseup', function(e) {
            e.preventDefault();
            e.stopPropagation();
        });
        
        element.addEventListener('contextmenu', function(e) {
            e.preventDefault();
            e.stopPropagation();
            showProtectionMessage('Right-click disabled for protection');
        });
        
        element.addEventListener('dblclick', function(e) {
            e.preventDefault();
            e.stopPropagation();
        });
        
        // Prevent drag and drop
        element.addEventListener('dragstart', function(e) {
            e.preventDefault();
            e.stopPropagation();
        });
        
        element.addEventListener('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
        });
    });
    
    // Prevent iframe interactions
    const iframe = container.querySelector('iframe');
    if (iframe) {
        iframe.addEventListener('load', function() {
            // Additional iframe protection
            iframe.style.pointerEvents = 'none';
        });
    }
}

function showProtectionMessage(message) {
    // Create temporary message
    const messageDiv = document.createElement('div');
    messageDiv.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: rgba(0, 0, 0, 0.9);
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        font-size: 14px;
        z-index: 10000;
        pointer-events: none;
        animation: fadeInOut 2s ease-in-out;
    `;
    messageDiv.textContent = message;
    
    // Add animation CSS
    if (!document.querySelector('#protection-animation')) {
        const style = document.createElement('style');
        style.id = 'protection-animation';
        style.textContent = `
            @keyframes fadeInOut {
                0% { opacity: 0; transform: translateY(-10px); }
                20% { opacity: 1; transform: translateY(0); }
                80% { opacity: 1; transform: translateY(0); }
                100% { opacity: 0; transform: translateY(-10px); }
            }
        `;
        document.head.appendChild(style);
    }
    
    document.body.appendChild(messageDiv);
    
    // Remove after animation
    setTimeout(() => {
        if (messageDiv.parentNode) {
            messageDiv.parentNode.removeChild(messageDiv);
        }
    }, 2000);
}

// Function to create protected video embed
function createProtectedVideoEmbed(videoId, containerId, title = 'Protected Video') {
    const container = document.getElementById(containerId);
    if (!container) return;
    
    // Add protection container class
    container.className = 'video-protection-container';
    
    // Create iframe
    const iframe = document.createElement('iframe');
    iframe.src = `https://www.youtube.com/embed/${videoId}?rel=0&modestbranding=1&showinfo=0`;
    iframe.title = title;
    iframe.className = 'w-full h-full border-0';
    iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
    iframe.allowFullScreen = false;
    iframe.loading = 'lazy';
    iframe.sandbox = 'allow-scripts allow-same-origin allow-presentation';
    
    // Clear container and add iframe
    container.innerHTML = '';
    container.appendChild(iframe);
    
    // Initialize protection
    addProtectionOverlays(container);
    addProtectionEventListeners(container);
}

// Export functions for global use
window.VideoProtection = {
    initialize: initializeVideoProtection,
    createProtectedEmbed: createProtectedVideoEmbed,
    showMessage: showProtectionMessage
};
