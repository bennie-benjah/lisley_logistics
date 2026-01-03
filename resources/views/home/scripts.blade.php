<script>
// Navigation function for SPA
function navigateToAuth() {
    // Hide current page
    document.querySelectorAll('.page').forEach(page => {
        page.classList.add('hidden');
    });
    
    // Show auth page
    const authPage = document.getElementById('auth');
    if (authPage) {
        authPage.classList.remove('hidden');
        authPage.classList.add('active');
        
        // Update active nav link
        document.querySelectorAll('.nav-link').forEach(link => {
            link.classList.remove('active');
        });
        document.getElementById('authLink').classList.add('active');
        
        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
    } else {
        // Fallback to regular login page if SPA auth section doesn't exist
        window.location.href = '{{ route("login") }}';
    }
}

// Notification function
function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existingNotification = document.querySelector('.custom-notification');
    if (existingNotification) {
        existingNotification.remove();
    }
    
    // Create notification
    const notification = document.createElement('div');
    notification.className = `custom-notification ${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'}"></i>
            <span>${message}</span>
            <button class="close-notification">&times;</button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Show notification
    setTimeout(() => {
        notification.classList.add('show');
    }, 10);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 300);
    }, 5000);
    
    // Close button
    notification.querySelector('.close-notification').addEventListener('click', () => {
        notification.classList.remove('show');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 300);
    });
}
</script>
{{-- <script src="{{ asset('js/home/products.data.js') }}"></script>
<script src="{{ asset('js/home/whatsapp.data.js') }}"></script> --}}

<script src="{{ asset('js/home/state.js') }}"></script>
<script src="{{ asset('js/home/dom.js') }}"></script>
<script src="{{ asset('js/home/auth.js') }}"></script>


<script src="{{ asset('js/home/products.js') }}"></script>
<script src="{{ asset('js/home/cart.js') }}"></script>
<script src="{{ asset('js/home/filters.js') }}"></script>
<script src="{{ asset('js/home/tracking.js') }}"></script>
<script src="{{ asset('js/home/faq.js') }}"></script>
<script src="{{ asset('js/home/backToTop.js') }}"></script>
<script src="{{ asset('js/home/whatsapp.js') }}"></script>

<script src="{{ asset('js/home/navigation.js') }}"></script>
<script src="{{ asset('js/home/init.js') }}"></script>
<script src="{{ asset('js/home/app.js') }}"></script>
