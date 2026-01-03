// public/js/home/app.js

// Main Application Initialization
document.addEventListener('DOMContentLoaded', function() {
    console.log('App initialized');
    
    // Initialize modules safely
    initNavigation();
    initProducts();
    initCart();
    initWhatsApp();
    
    // Set up global event listeners
    setupGlobalListeners();
});

// ---------------- MODULE INITIALIZERS ----------------
function initNavigation() {
    try {
        if (typeof Navigation !== 'undefined' && typeof Navigation.init === 'function') {
            Navigation.init();
            console.log('Navigation initialized');
        } else {
            console.warn('Navigation module not found');
        }
    } catch (error) {
        console.error('Error initializing Navigation:', error);
    }
}

function initProducts() {
    // Check if we're on a page that needs products
    const productsGrid = document.getElementById('productsGrid');
    if (!productsGrid) {
        console.log('No products grid found, skipping products init');
        return;
    }
    
    // Wait for products data to be available
    const waitForProducts = (attempts = 0) => {
        if (attempts > 10) { // Max 10 attempts (2 seconds)
            console.error('Products data not loaded after timeout');
            showProductsError();
            return;
        }
        
        if (typeof window.productsData !== 'undefined' && 
            typeof window.Products !== 'undefined' && 
            typeof window.Products.init === 'function') {
            
            console.log('Products data available, initializing...');
            try {
                window.Products.init();
            } catch (error) {
                console.error('Error initializing Products:', error);
                showProductsError();
            }
            
        } else {
            // Try again in 200ms
            setTimeout(() => waitForProducts(attempts + 1), 200);
        }
    };
    
    waitForProducts();
}

function showProductsError() {
    const productsGrid = document.getElementById('productsGrid');
    if (productsGrid) {
        productsGrid.innerHTML = `
            <div class="error-message">
                <p>Unable to load products. Please refresh the page.</p>
                <button class="btn" onclick="window.location.reload()">Refresh Page</button>
            </div>
        `;
    }
}

function initCart() {
    try {
        if (typeof CartUI !== 'undefined' && typeof CartUI.updateCount === 'function') {
            CartUI.updateCount();
            console.log('Cart initialized');
        } else {
            // Try to update cart count from Products module
            if (typeof window.Products !== 'undefined' && typeof window.Products.updateCartCount === 'function') {
                window.Products.updateCartCount();
            } else {
                // Fallback: simple cart count update
                updateCartCountFallback();
            }
        }
    } catch (error) {
        console.error('Error initializing Cart:', error);
    }
}

function updateCartCountFallback() {
    try {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        const totalItems = cart.reduce((sum, item) => sum + (item.quantity || 0), 0);
        const badge = document.querySelector('.cart-count, .cart-badge');
        if (badge) {
            badge.textContent = totalItems;
            badge.style.display = totalItems > 0 ? 'flex' : 'none';
        }
    } catch (error) {
        console.error('Error updating cart count:', error);
    }
}

function initWhatsApp() {
    try {
        if (typeof WhatsApp !== 'undefined' && typeof WhatsApp.init === 'function') {
            WhatsApp.init();
            console.log('WhatsApp initialized');
        }
    } catch (error) {
        console.error('Error initializing WhatsApp:', error);
    }
}

// ---------------- GLOBAL EVENT LISTENERS ----------------
function setupGlobalListeners() {
    // Add to cart button listener (global fallback)
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-to-cart') || e.target.closest('.add-to-cart')) {
            e.preventDefault();
            const button = e.target.classList.contains('add-to-cart') ? e.target : e.target.closest('.add-to-cart');
            const productId = button.getAttribute('data-id');
            
            if (productId && typeof window.Products !== 'undefined' && typeof window.Products.addToCart === 'function') {
                window.Products.addToCart(parseInt(productId));
            } else {
                alert('Add to cart functionality not available. Please refresh the page.');
            }
        }
    });
    
    // Filter buttons (global fallback)
    document.addEventListener('click', function(e) {
        if (e.target.id === 'applyFilters' || e.target.closest('#applyFilters')) {
            e.preventDefault();
            if (typeof window.Products !== 'undefined' && typeof window.Products.applyFilters === 'function') {
                window.Products.applyFilters();
            }
        }
        
        if (e.target.id === 'resetFilters' || e.target.closest('#resetFilters')) {
            e.preventDefault();
            if (typeof window.Products !== 'undefined' && typeof window.Products.resetFilters === 'function') {
                window.Products.resetFilters();
            }
        }
    });
    
    // Page visibility change (reinitialize products when page becomes visible)
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden && document.getElementById('productsGrid')) {
            setTimeout(() => {
                if (typeof window.Products !== 'undefined' && typeof window.Products.init === 'function') {
                    window.Products.init();
                }
            }, 100);
        }
    });
}

// ---------------- ERROR HANDLING ----------------
window.addEventListener('error', function(e) {
    console.error('Global JavaScript error:', e.error);
    
    // Don't show alert for Products-related errors
    if (e.message && e.message.includes('Products')) {
        console.log('Products-related error, handling gracefully');
        return;
    }
});

// Make sure products data is available
if (typeof window.productsData === 'undefined') {
    window.productsData = [];
}

if (typeof window.categoriesData === 'undefined') {
    window.categoriesData = [];
}