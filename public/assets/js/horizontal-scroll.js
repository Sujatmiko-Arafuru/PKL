/**
 * Horizontal Scroll Enhancement for Progress Pengembalian Table
 * File ini berisi fitur tambahan untuk meningkatkan pengalaman scroll horizontal
 */

document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi fitur scroll horizontal
    initHorizontalScroll();
    
    // Tambahkan event listener untuk window resize
    window.addEventListener('resize', function() {
        initHorizontalScroll();
    });
});

function initHorizontalScroll() {
    const scrollContainers = document.querySelectorAll('.progress-pengembalian-table');
    
    scrollContainers.forEach(container => {
        if (!container) return;
        
        const table = container.querySelector('.table');
        if (!table) return;
        
        // Tambahkan scroll indicator
        addScrollIndicator(container);
        
        // Tambahkan scroll buttons jika diperlukan
        addScrollButtons(container);
        
        // Tambahkan keyboard navigation
        addKeyboardNavigation(container);
        
        // Tambahkan touch/swipe support untuk mobile
        addTouchSupport(container);
        
        // Tambahkan scroll progress indicator
        addScrollProgress(container);
    });
}

function addScrollIndicator(container) {
    // Hapus indicator yang sudah ada
    const existingIndicator = container.querySelector('.scroll-indicator');
    if (existingIndicator) {
        existingIndicator.remove();
    }
    
    // Buat scroll indicator
    const indicator = document.createElement('div');
    indicator.className = 'scroll-indicator';
    indicator.style.display = 'none';
    container.appendChild(indicator);
    
    // Tampilkan indicator saat scroll
    container.addEventListener('scroll', function() {
        const scrollLeft = this.scrollLeft;
        const scrollWidth = this.scrollWidth;
        const clientWidth = this.clientWidth;
        
        if (scrollWidth > clientWidth) {
            indicator.style.display = 'block';
            
            // Update posisi indicator berdasarkan scroll progress
            const scrollProgress = scrollLeft / (scrollWidth - clientWidth);
            indicator.style.background = `linear-gradient(90deg, #20B2AA 0%, #008B8B ${scrollProgress * 100}%, #e9ecef ${scrollProgress * 100}%, #e9ecef 100%)`;
        } else {
            indicator.style.display = 'none';
        }
    });
}

function addScrollButtons(container) {
    // Hapus buttons yang sudah ada
    const existingButtons = container.querySelectorAll('.scroll-btn');
    existingButtons.forEach(btn => btn.remove());
    
    const table = container.querySelector('.table');
    if (!table || table.scrollWidth <= container.clientWidth) return;
    
    // Buat left scroll button
    const leftBtn = document.createElement('button');
    leftBtn.className = 'scroll-btn scroll-btn-left';
    leftBtn.innerHTML = '<i class="bi bi-chevron-left"></i>';
    leftBtn.style.cssText = `
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        z-index: 20;
        background: rgba(32, 178, 170, 0.9);
        border: none;
        color: white;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    `;
    
    // Buat right scroll button
    const rightBtn = document.createElement('button');
    rightBtn.className = 'scroll-btn scroll-btn-right';
    rightBtn.innerHTML = '<i class="bi bi-chevron-right"></i>';
    rightBtn.style.cssText = `
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        z-index: 20;
        background: rgba(32, 178, 170, 0.9);
        border: none;
        color: white;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    `;
    
    // Event listeners untuk buttons
    leftBtn.addEventListener('click', () => {
        container.scrollBy({
            left: -200,
            behavior: 'smooth'
        });
    });
    
    rightBtn.addEventListener('click', () => {
        container.scrollBy({
            left: 200,
            behavior: 'smooth'
        });
    });
    
    // Hover effects
    leftBtn.addEventListener('mouseenter', () => {
        leftBtn.style.background = 'rgba(32, 178, 170, 1)';
        leftBtn.style.transform = 'translateY(-50%) scale(1.1)';
    });
    
    leftBtn.addEventListener('mouseleave', () => {
        leftBtn.style.background = 'rgba(32, 178, 170, 0.9)';
        leftBtn.style.transform = 'translateY(-50%) scale(1)';
    });
    
    rightBtn.addEventListener('mouseenter', () => {
        rightBtn.style.background = 'rgba(32, 178, 170, 1)';
        rightBtn.style.transform = 'translateY(-50%) scale(1.1)';
    });
    
    rightBtn.addEventListener('mouseleave', () => {
        rightBtn.style.background = 'rgba(32, 178, 170, 0.9)';
        rightBtn.style.transform = 'translateY(-50%) scale(1)';
    });
    
    // Tambahkan buttons ke container
    container.appendChild(leftBtn);
    container.appendChild(rightBtn);
    
    // Update visibility buttons berdasarkan scroll position
    updateScrollButtonsVisibility(container);
    container.addEventListener('scroll', () => updateScrollButtonsVisibility(container));
}

function updateScrollButtonsVisibility(container) {
    const leftBtn = container.querySelector('.scroll-btn-left');
    const rightBtn = container.querySelector('.scroll-btn-right');
    
    if (!leftBtn || !rightBtn) return;
    
    const scrollLeft = container.scrollLeft;
    const scrollWidth = container.scrollWidth;
    const clientWidth = container.clientWidth;
    
    // Left button
    if (scrollLeft <= 0) {
        leftBtn.style.opacity = '0.5';
        leftBtn.style.pointerEvents = 'none';
    } else {
        leftBtn.style.opacity = '1';
        leftBtn.style.pointerEvents = 'auto';
    }
    
    // Right button
    if (scrollLeft >= scrollWidth - clientWidth - 5) {
        rightBtn.style.opacity = '0.5';
        rightBtn.style.pointerEvents = 'none';
    } else {
        rightBtn.style.opacity = '1';
        rightBtn.style.pointerEvents = 'auto';
    }
}

function addKeyboardNavigation(container) {
    container.addEventListener('keydown', function(e) {
        const scrollAmount = 200;
        
        switch(e.key) {
            case 'ArrowLeft':
                e.preventDefault();
                this.scrollBy({
                    left: -scrollAmount,
                    behavior: 'smooth'
                });
                break;
            case 'ArrowRight':
                e.preventDefault();
                this.scrollBy({
                    left: scrollAmount,
                    behavior: 'smooth'
                });
                break;
            case 'Home':
                e.preventDefault();
                this.scrollTo({
                    left: 0,
                    behavior: 'smooth'
                });
                break;
            case 'End':
                e.preventDefault();
                this.scrollTo({
                    left: this.scrollWidth,
                    behavior: 'smooth'
                });
                break;
        }
    });
    
    // Buat container focusable
    container.setAttribute('tabindex', '0');
}

function addTouchSupport(container) {
    let startX = 0;
    let startY = 0;
    let scrollLeft = 0;
    let isScrolling = false;
    
    container.addEventListener('touchstart', function(e) {
        startX = e.touches[0].pageX - container.offsetLeft;
        startY = e.touches[0].pageY - container.offsetTop;
        scrollLeft = container.scrollLeft;
        isScrolling = false;
    });
    
    container.addEventListener('touchmove', function(e) {
        if (!startX) return;
        
        const x = e.touches[0].pageX - container.offsetLeft;
        const y = e.touches[0].pageY - container.offsetTop;
        const walkX = (x - startX) * 2;
        const walkY = Math.abs(y - startY);
        
        // Hanya scroll horizontal jika gerakan horizontal lebih besar dari vertical
        if (Math.abs(walkX) > walkY) {
            e.preventDefault();
            container.scrollLeft = scrollLeft - walkX;
            isScrolling = true;
        }
    });
    
    container.addEventListener('touchend', function(e) {
        startX = 0;
        startY = 0;
        scrollLeft = 0;
    });
}

function addScrollProgress(container) {
    // Buat progress bar
    const progressBar = document.createElement('div');
    progressBar.className = 'scroll-progress-bar';
    progressBar.style.cssText = `
        position: absolute;
        top: 0;
        left: 0;
        height: 3px;
        background: #20B2AA;
        transition: width 0.3s ease;
        z-index: 15;
    `;
    
    container.appendChild(progressBar);
    
    // Update progress bar
    container.addEventListener('scroll', function() {
        const scrollLeft = this.scrollLeft;
        const scrollWidth = this.scrollWidth;
        const clientWidth = this.clientWidth;
        
        if (scrollWidth > clientWidth) {
            const progress = (scrollLeft / (scrollWidth - clientWidth)) * 100;
            progressBar.style.width = progress + '%';
        } else {
            progressBar.style.width = '0%';
        }
    });
}

// Export functions untuk penggunaan global jika diperlukan
window.HorizontalScroll = {
    init: initHorizontalScroll,
    addScrollIndicator,
    addScrollButtons,
    addKeyboardNavigation,
    addTouchSupport,
    addScrollProgress
};
