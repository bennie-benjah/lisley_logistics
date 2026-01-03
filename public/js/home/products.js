// public/js/home/products.js

// Products Module - Self-contained module pattern
const ProductsModule = (function() {
    // DOM Elements
    let productsGrid = null;
    let categoryFiltersContainer = null;
    
    // Data
    let products = [];
    let categories = [];
    let filteredProducts = [];
    
    // ---------------- INITIALIZATION ----------------
    function init() {
        // Cache DOM elements
        productsGrid = document.getElementById('productsGrid');
        categoryFiltersContainer = document.getElementById('categoryFilters');
        
        // Get data from window object (passed from Blade)
        products = window.productsData || [];
        categories = window.categoriesData || [];
        filteredProducts = [...products];
        
        // Only initialize if we're on a products page
        if (!productsGrid) {
            console.log('Not on products page, skipping products init');
            return;
        }
        
        // Initialize everything
        renderCategories();
        render();
        initFilters();
        updateCartCount();
        
        console.log('Products module initialized');
    }
    
    // ---------------- RENDER CATEGORIES ----------------
    function renderCategories() {
        if (!categoryFiltersContainer || categories.length === 0) return;
    
        categoryFiltersContainer.innerHTML = '';
        categories.forEach(cat => {
            const label = document.createElement('label');
            label.innerHTML = `
                <input type="checkbox" class="category-filter" value="${cat.slug}" checked> ${cat.name}
            `;
            categoryFiltersContainer.appendChild(label);
        });
    
        // Reattach filter listeners
        document.querySelectorAll('.category-filter').forEach(checkbox => {
            checkbox.removeEventListener('change', applyFilters);
            checkbox.addEventListener('change', applyFilters);
        });
    }
    
    // ---------------- RENDER PRODUCTS ----------------
    function render(productsToRender = filteredProducts) {
        if (!productsGrid) return;
        productsGrid.innerHTML = '';
    
        if (productsToRender.length === 0) {
            productsGrid.innerHTML = `
                <div class="no-products">
                    <p>No products found matching your filters.</p>
                    <button class="btn" id="resetFiltersBtn">Reset Filters</button>
                </div>
            `;
            document.getElementById('resetFiltersBtn')?.addEventListener('click', resetFilters);
            return;
        }
    
        productsToRender.forEach(product => {
            const productCard = document.createElement('div');
            productCard.className = 'product-card';
            productCard.setAttribute('data-category', product.category);
            productCard.setAttribute('data-price', product.price);
    
            productCard.innerHTML = `
                <div class="product-img">
                    <img src="${product.image}" alt="${product.name}" onerror="this.src='/images/default-product.jpg'">
                </div>
                <div class="product-content">
                    <h3>${product.name}</h3>
                    <p>${product.description.substring(0, 100)}${product.description.length > 100 ? '...' : ''}</p>
                    <div class="product-meta">
                        <div class="product-price">$${parseFloat(product.price).toFixed(2)}</div>
                        <div class="product-stock">Stock: ${product.stock_quantity}</div>
                    </div>
                    <button class="btn add-to-cart" data-id="${product.id}" ${product.stock_quantity === 0 ? 'disabled' : ''}>
                        ${product.stock_quantity === 0 ? 'Out of Stock' : 'Add to Cart'}
                    </button>
                </div>
            `;
            productsGrid.appendChild(productCard);
        });
    
        addCartEventListeners();
    }
    
    // ---------------- CART FUNCTIONS ----------------
    function addCartEventListeners() {
        document.querySelectorAll('.add-to-cart:not(:disabled)').forEach(button => {
            button.addEventListener('click', e => {
                const productId = parseInt(e.target.getAttribute('data-id'));
                addToCart(productId);
            });
        });
    }
    
    function addToCart(productId) {
        const product = products.find(p => p.id === productId);
        if (!product || product.stock_quantity <= 0) {
            return alert(`${product?.name || 'Product'} is out of stock!`);
        }
    
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        const existing = cart.find(item => item.id === productId);
    
        if (existing) {
            if (existing.quantity >= product.stock_quantity) {
                return alert(`Cannot add more. Only ${product.stock_quantity} available.`);
            }
            existing.quantity += 1;
        } else {
            cart.push({ 
                id: product.id, 
                name: product.name, 
                price: product.price, 
                image: product.image, 
                quantity: 1 
            });
        }
    
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartCount();
        showNotification(`${product.name} added to cart!`);
    }
    
    function updateCartCount() {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        const badge = document.querySelector('.cart-count, .cart-badge');
        if (badge) {
            badge.textContent = totalItems;
            badge.style.display = totalItems > 0 ? 'flex' : 'none';
        }
    }
    
    function showNotification(message) {
        let notification = document.querySelector('.cart-notification');
        if (!notification) {
            notification = document.createElement('div');
            notification.className = 'cart-notification';
            document.body.appendChild(notification);
        }
        notification.textContent = message;
        notification.classList.add('show');
        setTimeout(() => notification.classList.remove('show'), 3000);
    }
    
    // ---------------- FILTER FUNCTIONS ----------------
    function applyFilters() {
        const selectedCategories = Array.from(document.querySelectorAll('.category-filter:checked')).map(c => c.value);
        const priceFilter = document.querySelector('.price-filter:checked')?.value || 'all';
    
        filteredProducts = products.filter(product => {
            // Category filter
            if (selectedCategories.length > 0 && !selectedCategories.includes(product.category)) {
                return false;
            }
    
            // Price filter
            switch (priceFilter) {
                case 'under50': return product.price < 50;
                case '50-200': return product.price >= 50 && product.price <= 200;
                case 'over200': return product.price > 200;
                default: return true;
            }
        });
    
        render();
    }
    
    function resetFilters() {
        document.querySelectorAll('.category-filter').forEach(c => c.checked = true);
        const allPriceRadio = document.querySelector('.price-filter[value="all"]');
        if (allPriceRadio) allPriceRadio.checked = true;
        filteredProducts = [...products];
        render();
    }
    
    function initFilters() {
        const applyBtn = document.getElementById('applyFilters');
        const resetBtn = document.getElementById('resetFilters');
        
        if (applyBtn) {
            applyBtn.removeEventListener('click', applyFilters);
            applyBtn.addEventListener('click', applyFilters);
        }
        
        if (resetBtn) {
            resetBtn.removeEventListener('click', resetFilters);
            resetBtn.addEventListener('click', resetFilters);
        }
        
        document.querySelectorAll('.price-filter').forEach(radio => {
            radio.removeEventListener('change', applyFilters);
            radio.addEventListener('change', applyFilters);
        });
    }
    
    // ---------------- PUBLIC API ----------------
    return {
        init: init,
        render: render,
        addToCart: addToCart,
        applyFilters: applyFilters,
        resetFilters: resetFilters,
        updateCartCount: updateCartCount
    };
})();

// Export to window object
window.Products = ProductsModule;

// Auto-initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    if (typeof window.Products !== 'undefined') {
        window.Products.init();
    }
});