// public/js/home/products.js
window.Products = (function() {
    const productsGrid = document.getElementById('productsGrid');
    let products = window.productsData || [];
    let filteredProducts = [...products];

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
                    <p>${product.description}</p>
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

        // Add event listeners for Add to Cart buttons
        addCartEventListeners();
    }

    function addCartEventListeners() {
        document.querySelectorAll('.add-to-cart:not(:disabled)').forEach(button => {
            button.addEventListener('click', (e) => {
                const productId = parseInt(e.target.getAttribute('data-id'));
                addToCart(productId);
            });
        });
    }

    function addToCart(productId) {
        const product = products.find(p => p.id === productId);
        if (!product) return;

        // Check stock
        if (product.stock_quantity <= 0) {
            alert(`${product.name} is out of stock!`);
            return;
        }

        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        const existingItem = cart.find(item => item.id === productId);

        if (existingItem) {
            // Check if adding more than available stock
            if (existingItem.quantity >= product.stock_quantity) {
                alert(`Cannot add more. Only ${product.stock_quantity} items available in stock.`);
                return;
            }
            existingItem.quantity += 1;
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
        
        // Update cart count
        updateCartCount();
        
        // Show success message
        showNotification(`${product.name} added to cart!`);
    }

    function updateCartCount() {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        
        // Update cart badge if exists
        const cartBadge = document.querySelector('.cart-count, .cart-badge');
        if (cartBadge) {
            cartBadge.textContent = totalItems;
            cartBadge.style.display = totalItems > 0 ? 'flex' : 'none';
        }
    }

    function showNotification(message) {
        // Create or show notification
        let notification = document.querySelector('.cart-notification');
        if (!notification) {
            notification = document.createElement('div');
            notification.className = 'cart-notification';
            document.body.appendChild(notification);
        }
        
        notification.textContent = message;
        notification.classList.add('show');
        
        setTimeout(() => {
            notification.classList.remove('show');
        }, 3000);
    }

    // Filtering functions
    function applyFilters() {
        const selectedCategories = Array.from(document.querySelectorAll('.category-filter:checked'))
            .map(checkbox => checkbox.value);
        
        const priceFilter = document.querySelector('.price-filter:checked')?.value || 'all';
        
        filteredProducts = products.filter(product => {
            // Category filter
            if (selectedCategories.length > 0 && !selectedCategories.includes(product.category)) {
                return false;
            }
            
            // Price filter
            switch(priceFilter) {
                case 'under50':
                    return product.price < 50;
                case '50-200':
                    return product.price >= 50 && product.price <= 200;
                case 'over200':
                    return product.price > 200;
                default:
                    return true;
            }
        });
        
        render();
    }

    function resetFilters() {
        // Check all category checkboxes
        document.querySelectorAll('.category-filter').forEach(checkbox => {
            checkbox.checked = true;
        });
        
        // Set price to 'all'
        document.querySelector('.price-filter[value="all"]').checked = true;
        
        filteredProducts = [...products];
        render();
    }

    // Initialize event listeners for filters
    function initFilters() {
        document.getElementById('applyFilters')?.addEventListener('click', applyFilters);
        document.getElementById('resetFilters')?.addEventListener('click', resetFilters);
        
        // Auto-apply filters when checkboxes change
        document.querySelectorAll('.category-filter, .price-filter').forEach(filter => {
            filter.addEventListener('change', applyFilters);
        });
    }

    // Initialize everything when DOM is loaded
    function init() {
        if (!productsGrid) return;
        
        render();
        initFilters();
        updateCartCount();
        
        // Add some CSS for the notification
        const style = document.createElement('style');
        style.textContent = `
            .cart-notification {
                position: fixed;
                top: 20px;
                right: 20px;
                background: #4CAF50;
                color: white;
                padding: 15px 20px;
                border-radius: 5px;
                z-index: 1000;
                transform: translateX(150%);
                transition: transform 0.3s ease;
            }
            .cart-notification.show {
                transform: translateX(0);
            }
            .product-img img {
                width: 100%;
                height: 200px;
                object-fit: cover;
                border-radius: 8px 8px 0 0;
            }
            .product-meta {
                display: flex;
                justify-content: space-between;
                margin: 10px 0;
            }
            .product-stock {
                color: #666;
                font-size: 0.9em;
            }
            .no-products {
                text-align: center;
                padding: 40px;
                grid-column: 1 / -1;
            }
        `;
        document.head.appendChild(style);
    }

    return {
        init,
        render,
        addToCart,
        applyFilters,
        resetFilters
    };
})();

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.Products.init();
});