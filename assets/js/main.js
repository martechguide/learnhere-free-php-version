// Main JavaScript for Learn Here Free PHP version

document.addEventListener('DOMContentLoaded', function() {
    // Grid/List view toggle
    const gridViewBtn = document.getElementById('grid-view');
    const listViewBtn = document.getElementById('list-view');
    const batchesGrid = document.getElementById('batches-grid');
    
    if (gridViewBtn && listViewBtn && batchesGrid) {
        gridViewBtn.addEventListener('click', function() {
            batchesGrid.className = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6';
            gridViewBtn.className = 'p-2 rounded-md bg-blue-100 text-blue-600 hover:bg-blue-200';
            listViewBtn.className = 'p-2 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100';
        });
        
        listViewBtn.addEventListener('click', function() {
            batchesGrid.className = 'grid grid-cols-1 gap-4';
            listViewBtn.className = 'p-2 rounded-md bg-blue-100 text-blue-600 hover:bg-blue-200';
            gridViewBtn.className = 'p-2 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100';
        });
    }
    
    // Add loading states to buttons
    const buttons = document.querySelectorAll('button[type="submit"]');
    buttons.forEach(button => {
        button.addEventListener('click', function() {
            if (!this.disabled) {
                this.disabled = true;
                this.innerHTML = '<span class="loading"></span> Loading...';
            }
        });
    });
    
    // Add hover effects to cards
    const cards = document.querySelectorAll('.bg-white.rounded-lg.shadow-sm.border');
    cards.forEach(card => {
        card.classList.add('card-hover');
    });
    
    // Smooth scrolling for anchor links
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Add focus ring to interactive elements
    const interactiveElements = document.querySelectorAll('button, a, input, select, textarea');
    interactiveElements.forEach(element => {
        element.classList.add('focus-ring');
    });
    
    // Add fade-in animation to main content
    const mainContent = document.querySelector('main');
    if (mainContent) {
        mainContent.classList.add('fade-in');
    }
    
    // Video player enhancements
    const videoIframes = document.querySelectorAll('iframe[src*="youtube"]');
    videoIframes.forEach(iframe => {
        // Add loading indicator
        iframe.addEventListener('load', function() {
            this.style.opacity = '1';
        });
        iframe.style.opacity = '0';
        iframe.style.transition = 'opacity 0.3s ease';
    });
    
    // Mobile menu toggle (if needed)
    const mobileMenuBtn = document.querySelector('[data-mobile-menu]');
    const mobileMenu = document.querySelector('[data-mobile-menu-target]');
    
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    }
    
    // Search functionality (if search input exists)
    const searchInput = document.querySelector('input[type="search"]');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const searchableElements = document.querySelectorAll('[data-searchable]');
            
            searchableElements.forEach(element => {
                const text = element.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    element.style.display = '';
                } else {
                    element.style.display = 'none';
                }
            });
        });
    }
    
    // Toast notifications
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
            type === 'success' ? 'bg-green-500 text-white' :
            type === 'error' ? 'bg-red-500 text-white' :
            'bg-blue-500 text-white'
        }`;
        toast.textContent = message;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
    
    // Expose toast function globally
    window.showToast = showToast;
    
    // Form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('border-red-500');
                } else {
                    field.classList.remove('border-red-500');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                showToast('Please fill in all required fields', 'error');
            }
        });
    });
    
    // Lazy loading for images
    const images = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                observer.unobserve(img);
            }
        });
    });
    
    images.forEach(img => imageObserver.observe(img));
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + K for search
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            const searchInput = document.querySelector('input[type="search"]');
            if (searchInput) {
                searchInput.focus();
            }
        }
        
        // Escape to close modals
        if (e.key === 'Escape') {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                if (!modal.classList.contains('hidden')) {
                    modal.classList.add('hidden');
                }
            });
        }
    });
    
    console.log('Learn Here Free PHP version loaded successfully!');
});
