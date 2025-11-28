document.addEventListener('DOMContentLoaded', function() {
    initNotifications();
    initAlerts();
    initAnimations();
    initFloatingActionButton();
    initThemeToggle();
    initSmoothScroll();
    initRippleEffect();
    initFormEnhancements();
    initTimeTracking();
    initTooltips();
    initCounterAnimations();
    initCardHoverEffects();
    initSearchHighlight();
    initMobileNav();
});

function initNotifications() {
    const badge = document.getElementById('notificationCount');
    if (!badge) return;
    
    fetchUnreadNotifications();
    setInterval(fetchUnreadNotifications, 30000);
}

async function fetchUnreadNotifications() {
    try {
        const response = await fetch('/api/notifications/unread');
        if (!response.ok) return;
        
        const notifications = await response.json();
        const badge = document.getElementById('notificationCount');
        
        if (badge && notifications.length > 0) {
            badge.textContent = notifications.length;
            badge.classList.remove('d-none');
            badge.classList.add('animate-pulse');
        } else if (badge) {
            badge.classList.add('d-none');
        }
    } catch (error) {
        console.debug('Notifications fetch skipped');
    }
}

function initAlerts() {
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'all 0.5s ease-out';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';
            setTimeout(() => {
                const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                bsAlert.close();
            }, 500);
        }, 5000);
    });
}

function initAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fadeInUp');
                entry.target.style.opacity = '1';
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    document.querySelectorAll('.card, .list-group-item, .table tbody tr').forEach(el => {
        el.style.opacity = '0';
        observer.observe(el);
    });
    
    document.querySelectorAll('.progress-bar').forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.width = width;
        }, 300);
    });
}

function initFloatingActionButton() {
    const fabContainer = document.querySelector('.fab-container');
    if (fabContainer) return;
    
    const isLoggedIn = document.querySelector('.navbar-nav');
    if (!isLoggedIn) return;
    
    const fab = document.createElement('div');
    fab.className = 'fab-container';
    fab.innerHTML = `
        <div class="fab-menu">
            <div class="fab-menu-item">
                <span>New Task</span>
                <a href="/tasks/create" class="btn-primary"><i class="bi bi-check2-square"></i></a>
            </div>
            <div class="fab-menu-item">
                <span>New Bill</span>
                <a href="/bills/create" class="btn-success"><i class="bi bi-receipt"></i></a>
            </div>
            <div class="fab-menu-item">
                <span>New Note</span>
                <a href="/notes/create" class="btn-warning"><i class="bi bi-journal-text"></i></a>
            </div>
            <div class="fab-menu-item">
                <span>AI Assistant</span>
                <a href="/ai-assistant" class="btn-info"><i class="bi bi-robot"></i></a>
            </div>
        </div>
        <button class="fab-button" aria-label="Quick Actions">
            <i class="bi bi-plus-lg"></i>
        </button>
    `;
    
    document.body.appendChild(fab);
    
    const fabBtn = fab.querySelector('.fab-button');
    const fabMenu = fab.querySelector('.fab-menu');
    
    fabBtn.addEventListener('click', () => {
        fabMenu.classList.toggle('active');
        fabBtn.querySelector('i').classList.toggle('bi-plus-lg');
        fabBtn.querySelector('i').classList.toggle('bi-x-lg');
    });
    
    document.addEventListener('click', (e) => {
        if (!fab.contains(e.target)) {
            fabMenu.classList.remove('active');
            fabBtn.querySelector('i').classList.add('bi-plus-lg');
            fabBtn.querySelector('i').classList.remove('bi-x-lg');
        }
    });
}

function initThemeToggle() {
    const saved = localStorage.getItem('theme');
    if (saved === 'dark') {
        document.body.setAttribute('data-theme', 'dark');
    }
    
    const navbar = document.querySelector('.navbar-nav.ms-auto, .navbar-nav:last-child');
    if (!navbar) return;
    
    const existing = document.querySelector('.theme-toggle');
    if (existing) return;
    
    const themeToggle = document.createElement('li');
    themeToggle.className = 'nav-item';
    themeToggle.innerHTML = `
        <button class="theme-toggle nav-link" aria-label="Toggle Theme">
            <i class="bi bi-moon-stars"></i>
        </button>
    `;
    
    const firstChild = navbar.firstChild;
    if (firstChild) {
        navbar.insertBefore(themeToggle, firstChild);
    } else {
        navbar.appendChild(themeToggle);
    }
    
    themeToggle.querySelector('button').addEventListener('click', toggleTheme);
    updateThemeIcon();
}

function toggleTheme() {
    const isDark = document.body.getAttribute('data-theme') === 'dark';
    if (isDark) {
        document.body.removeAttribute('data-theme');
        localStorage.setItem('theme', 'light');
    } else {
        document.body.setAttribute('data-theme', 'dark');
        localStorage.setItem('theme', 'dark');
    }
    updateThemeIcon();
}

function updateThemeIcon() {
    const icon = document.querySelector('.theme-toggle i');
    if (!icon) return;
    
    const isDark = document.body.getAttribute('data-theme') === 'dark';
    icon.className = isDark ? 'bi bi-sun' : 'bi bi-moon-stars';
}

function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

function initRippleEffect() {
    document.querySelectorAll('.btn, .card, .list-group-item').forEach(element => {
        element.addEventListener('click', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const ripple = document.createElement('span');
            ripple.style.cssText = `
                position: absolute;
                width: 0;
                height: 0;
                background: rgba(255, 255, 255, 0.4);
                border-radius: 50%;
                transform: translate(-50%, -50%);
                pointer-events: none;
                left: ${x}px;
                top: ${y}px;
            `;
            
            this.style.position = 'relative';
            this.style.overflow = 'hidden';
            this.appendChild(ripple);
            
            ripple.animate([
                { width: '0', height: '0', opacity: 0.5 },
                { width: '200px', height: '200px', opacity: 0 }
            ], {
                duration: 600,
                easing: 'ease-out'
            }).onfinish = () => ripple.remove();
        });
    });
}

function initFormEnhancements() {
    document.querySelectorAll('.form-control, .form-select').forEach(input => {
        const wrapper = document.createElement('div');
        wrapper.className = 'form-floating-wrapper';
        
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('input-focused');
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('input-focused');
        });
    });
    
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const btn = this.querySelector('button[type="submit"]');
            if (btn && !btn.disabled) {
                const originalHtml = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
                
                setTimeout(() => {
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                }, 10000);
            }
        });
    });
}

function initTimeTracking() {
    updateActiveTimer();
    setInterval(updateActiveTimer, 1000);
}

async function updateActiveTimer() {
    const timerDisplay = document.getElementById('activeTimer');
    if (!timerDisplay) return;
    
    try {
        const response = await fetch('/api/time-tracking/active');
        if (!response.ok) return;
        
        const data = await response.json();
        
        if (data.active) {
            const start = new Date(data.start_time);
            const now = new Date();
            const diff = Math.floor((now - start) / 1000);
            
            const hours = Math.floor(diff / 3600);
            const minutes = Math.floor((diff % 3600) / 60);
            const seconds = diff % 60;
            
            timerDisplay.textContent = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            timerDisplay.classList.add('text-success', 'animate-pulse');
        } else {
            timerDisplay.textContent = '00:00:00';
            timerDisplay.classList.remove('text-success', 'animate-pulse');
        }
    } catch (error) {
        // Silent fail for timer
    }
}

function initTooltips() {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));
}

function initCounterAnimations() {
    const counters = document.querySelectorAll('.counter, .stat-card h3');
    
    counters.forEach(counter => {
        const target = parseInt(counter.textContent.replace(/[^0-9]/g, ''));
        if (isNaN(target) || target === 0) return;
        
        const prefix = counter.textContent.match(/^[^0-9]*/)?.[0] || '';
        const suffix = counter.textContent.match(/[^0-9]*$/)?.[0] || '';
        
        let current = 0;
        const increment = target / 50;
        const duration = 1000;
        const stepTime = duration / 50;
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const timer = setInterval(() => {
                        current += increment;
                        if (current >= target) {
                            counter.textContent = prefix + target.toLocaleString() + suffix;
                            clearInterval(timer);
                        } else {
                            counter.textContent = prefix + Math.floor(current).toLocaleString() + suffix;
                        }
                    }, stepTime);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        
        observer.observe(counter);
    });
}

function initCardHoverEffects() {
    document.querySelectorAll('.card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
        });
        
        card.addEventListener('mousemove', function(e) {
            if (window.innerWidth < 768) return;
            
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            const rotateX = (y - centerY) / 20;
            const rotateY = (centerX - x) / 20;
            
            this.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-4px)`;
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) translateY(0)';
        });
    });
}

function initSearchHighlight() {
    const searchParams = new URLSearchParams(window.location.search);
    const searchTerm = searchParams.get('search') || searchParams.get('q');
    
    if (searchTerm) {
        highlightText(document.body, searchTerm);
    }
}

function highlightText(element, term) {
    const regex = new RegExp(`(${term})`, 'gi');
    const walker = document.createTreeWalker(element, NodeFilter.SHOW_TEXT);
    const textNodes = [];
    
    while (walker.nextNode()) {
        if (walker.currentNode.textContent.match(regex)) {
            textNodes.push(walker.currentNode);
        }
    }
    
    textNodes.forEach(node => {
        const span = document.createElement('span');
        span.innerHTML = node.textContent.replace(regex, '<mark class="bg-warning">$1</mark>');
        node.parentNode.replaceChild(span, node);
    });
}

function initMobileNav() {
    if (window.innerWidth > 991) return;
    
    const existingNav = document.querySelector('.mobile-nav');
    if (existingNav) return;
    
    const isLoggedIn = document.querySelector('.navbar');
    if (!isLoggedIn) return;
    
    const mobileNav = document.createElement('nav');
    mobileNav.className = 'mobile-nav';
    mobileNav.innerHTML = `
        <div class="mobile-nav-items">
            <a href="/dashboard" class="mobile-nav-item ${isCurrentPath('/dashboard') ? 'active' : ''}">
                <i class="bi bi-house-door"></i>
                <span>Home</span>
            </a>
            <a href="/tasks" class="mobile-nav-item ${isCurrentPath('/tasks') ? 'active' : ''}">
                <i class="bi bi-check2-square"></i>
                <span>Tasks</span>
            </a>
            <a href="/bills" class="mobile-nav-item ${isCurrentPath('/bills') ? 'active' : ''}">
                <i class="bi bi-receipt"></i>
                <span>Bills</span>
            </a>
            <a href="/ai-assistant" class="mobile-nav-item ${isCurrentPath('/ai-assistant') ? 'active' : ''}">
                <i class="bi bi-robot"></i>
                <span>AI</span>
            </a>
            <a href="/profile" class="mobile-nav-item ${isCurrentPath('/profile') ? 'active' : ''}">
                <i class="bi bi-person"></i>
                <span>Profile</span>
            </a>
        </div>
    `;
    
    document.body.appendChild(mobileNav);
}

function isCurrentPath(path) {
    return window.location.pathname === path || window.location.pathname.startsWith(path + '/');
}

function showToast(message, type = 'info') {
    let container = document.querySelector('.toast-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'toast-container position-fixed top-0 end-0 p-3';
        container.style.zIndex = '9999';
        document.body.appendChild(container);
    }
    
    const icons = {
        success: 'check-circle',
        danger: 'exclamation-triangle',
        warning: 'exclamation-circle',
        info: 'info-circle'
    };
    
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0`;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-${icons[type]} me-2"></i>
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    container.appendChild(toast);
    const bsToast = new bootstrap.Toast(toast, { autohide: true, delay: 4000 });
    bsToast.show();
    
    toast.addEventListener('hidden.bs.toast', () => toast.remove());
}

function confirmDelete(message = 'Are you sure you want to delete this item?') {
    return new Promise((resolve) => {
        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.innerHTML = `
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-exclamation-triangle text-warning me-2"></i>Confirm Action</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0">${message}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirmBtn">Delete</button>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
        
        modal.querySelector('#confirmBtn').addEventListener('click', () => {
            bsModal.hide();
            resolve(true);
        });
        
        modal.addEventListener('hidden.bs.modal', () => {
            modal.remove();
            resolve(false);
        });
    });
}

function formatCurrency(amount, currency = 'USD') {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: currency
    }).format(amount);
}

function formatDate(date, format = 'short') {
    const d = new Date(date);
    const options = format === 'long' 
        ? { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }
        : { year: 'numeric', month: 'short', day: 'numeric' };
    return d.toLocaleDateString('en-US', options);
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function throttle(func, limit) {
    let inThrottle;
    return function(...args) {
        if (!inThrottle) {
            func.apply(this, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

document.querySelectorAll('form[data-confirm]').forEach(form => {
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        const confirmed = await confirmDelete(this.dataset.confirm);
        if (confirmed) {
            this.submit();
        }
    });
});

window.showToast = showToast;
window.confirmDelete = confirmDelete;
window.formatCurrency = formatCurrency;
window.formatDate = formatDate;
window.debounce = debounce;
window.throttle = throttle;
