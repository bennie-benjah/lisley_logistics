<script>
        // Product Data
        const products = [
            {
                id: 1,
                name: "Heavy-Duty Shipping Boxes (Set of 10)",
                category: "packaging",
                price: 34.99,
                image: "https://images.unsplash.com/photo-1598300042247-d088f8ab3a91?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1770&q=80",
                description: "Durable corrugated boxes for secure shipping of various items."
            },
            {
                id: 2,
                name: "Bubble Wrap Roll (12\" x 50ft)",
                category: "packaging",
                price: 18.50,
                image: "https://images.unsplash.com/photo-1513475382585-d06e58bcb0e0?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1770&q=80",
                description: "Protective bubble wrap for cushioning fragile items during transit."
            },
            {
                id: 3,
                name: "Standard Wooden Pallet",
                category: "equipment",
                price: 24.99,
                image: "https://images.unsplash.com/photo-1600687615241-6e1e16d2d2a3?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1770&q=80",
                description: "Standard 48\" x 40\" wooden pallet for warehouse storage and shipping."
            },
            {
                id: 4,
                name: "Hand Pallet Truck",
                category: "equipment",
                price: 899.99,
                image: "https://images.unsplash.com/photo-1578575437130-527eed3abbec?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1770&q=80",
                description: "Manual pallet jack for moving loaded pallets in warehouses."
            },
            {
                id: 5,
                name: "GPS Tracking Device",
                category: "tracking",
                price: 149.99,
                image: "https://images.unsplash.com/photo-1591799264318-7e6ef8ddb7ea?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1674&q=80",
                description: "Real-time GPS tracker with long battery life for shipment monitoring."
            },
            {
                id: 6,
                name: "Fleet Management Software",
                category: "software",
                price: 299.99,
                image: "https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1770&q=80",
                description: "Software suite for managing logistics operations and vehicle fleets."
            },
            {
                id: 7,
                name: "Shipping Labels (Roll of 500)",
                category: "packaging",
                price: 42.75,
                image: "https://images.unsplash.com/photo-1560472354-b33ff0c44a43?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1825&q=80",
                description: "Professional adhesive shipping labels for thermal printers."
            },
            {
                id: 8,
                name: "Warehouse Barcode Scanner",
                category: "software",
                price: 189.99,
                image: "https://images.unsplash.com/photo-1581094794329-c8112a89af12?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1770&q=80",
                description: "Wireless barcode scanner for inventory management and tracking."
            }
        ];

        // Shopping Cart
        let cart = JSON.parse(localStorage.getItem('cart')) || [];

        // WhatsApp Chat Data
        const whatsappBotResponses = {
            "Track my shipment": "To track your shipment, please provide your tracking number. You can also visit our Track Shipment page for real-time updates.",
            "Get a quote": "I'd be happy to help you get a quote! Please let me know what services you're interested in (freight forwarding, warehousing, etc.) and I'll connect you with our sales team.",
            "Customer support": "I can help with customer support. Please describe your issue and I'll connect you with the right team member.",
            "Other inquiry": "I'm here to help! Please let me know what you need assistance with.",
            "default": "Thank you for your message. Our support team will respond to you shortly. You can also call us at +1 210 967 8545 -lilsley logitics for immediate assistance."
        };

        // DOM Elements
        const pageContainer = document.getElementById('pageContainer');
        const pages = document.querySelectorAll('.page');
        const navLinks = document.querySelectorAll('.nav-link');
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mainNav = document.getElementById('mainNav');
        const cartCount = document.getElementById('cartCount');
        const productsGrid = document.getElementById('productsGrid');
        const cartItems = document.getElementById('cartItems');
        const cartSummary = document.getElementById('cartSummary');
        const emptyCartMessage = document.getElementById('emptyCartMessage');
        const checkoutBtn = document.getElementById('checkoutBtn');
        const trackingResults = document.getElementById('trackingResults');
        const trackBtn = document.getElementById('trackBtn');
        const applyFiltersBtn = document.getElementById('applyFilters');
        const resetFiltersBtn = document.getElementById('resetFilters');
        const whatsappToggle = document.getElementById('whatsappToggle');
        const whatsappChatBox = document.getElementById('whatsappChatBox');
        const whatsappBody = document.getElementById('whatsappBody');
        const whatsappInput = document.getElementById('whatsappInput');
        const sendMessageBtn = document.getElementById('sendMessageBtn');
        const quickReplies = document.getElementById('quickReplies');
        const backToTop = document.getElementById('backToTop');
        const faqQuestions = document.querySelectorAll('.faq-question');

        // Initialize the application
        function init() {
            // Set up event listeners
            setupEventListeners();

            // Load products
            renderProducts();

            // Update cart count
            updateCartCount();

            // Show home page by default
            showPage('home');

            // Initialize WhatsApp chat
            initWhatsAppChat();

            // Initialize FAQ accordion
            initFAQ();

            // Initialize back to top button
            initBackToTop();
        }

        // Set up event listeners
        function setupEventListeners() {
            // Navigation links
            navLinks.forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    const pageId = link.getAttribute('href').substring(1);
                    showPage(pageId);

                    // Update active nav link
                    navLinks.forEach(navLink => navLink.classList.remove('active'));
                    link.classList.add('active');

                    // Close mobile menu if open
                    if (mainNav.classList.contains('active')) {
                        mainNav.classList.remove('active');
                    }
                });
            });

            // Mobile menu toggle
            mobileMenuBtn.addEventListener('click', () => {
                mainNav.classList.toggle('active');
            });

            // Track shipment button
            if (trackBtn) {
                trackBtn.addEventListener('click', () => {
                    const trackingNumber = document.getElementById('trackingNumber').value;
                    if (trackingNumber) {
                        trackingResults.classList.add('active');
                        document.getElementById('displayTrackingNumber').textContent = trackingNumber;
                    } else {
                        alert('Please enter a tracking number');
                    }
                });
            }

            // Apply product filters
            if (applyFiltersBtn) {
                applyFiltersBtn.addEventListener('click', applyProductFilters);
            }

            // Reset product filters
            if (resetFiltersBtn) {
                resetFiltersBtn.addEventListener('click', resetProductFilters);
            }

            // Checkout button
            if (checkoutBtn) {
                checkoutBtn.addEventListener('click', () => {
                    if (cart.length > 0) {
                        alert('Thank you for your order! This is a demo - in a real application, you would be redirected to a secure checkout page.');
                        cart = [];
                        localStorage.setItem('cart', JSON.stringify(cart));
                        updateCartCount();
                        renderCart();
                    }
                });
            }

            // Close mobile menu when clicking outside
            document.addEventListener('click', (e) => {
                if (!e.target.closest('nav') && !e.target.closest('.mobile-menu-btn') && mainNav.classList.contains('active')) {
                    mainNav.classList.remove('active');
                }
            });
        }
// --- AUTH FORM SWITCHING ---
function initAuthFormSwitching() {
    const authForms = {
        loginTab: 'loginForm',
        signupTab: 'signupForm',
        forgotPasswordLink: 'forgotPasswordForm',
        backToLoginFromForgot: 'loginForm',
        backToLoginFromReset: 'loginForm',
        backToLoginFromConfirm: 'loginForm',
        switchToSignup: 'signupForm',
        switchToLogin: 'loginForm'
    };

    Object.keys(authForms).forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('click', e => {
                e.preventDefault();

                // Hide all forms
                document.querySelectorAll('.auth-form').forEach(f => f.classList.remove('active'));

                // Show selected form
                document.getElementById(authForms[id]).classList.add('active');

                // Toggle active tab styles
                if (id === 'loginTab' || id === 'signupTab') {
                    document.getElementById('loginTab').classList.toggle('active', id === 'loginTab');
                    document.getElementById('signupTab').classList.toggle('active', id === 'signupTab');
                }
            });
        }
    });
}

// Call this after DOMContentLoaded
document.addEventListener('DOMContentLoaded', initAuthFormSwitching);

        // Initialize FAQ accordion functionality
        function initFAQ() {
            faqQuestions.forEach(question => {
                question.addEventListener('click', () => {
                    // Toggle active class on question
                    question.classList.toggle('active');

                    // Toggle active class on answer
                    const answer = question.nextElementSibling;
                    answer.classList.toggle('active');
                });
            });
        }

        // Initialize back to top button
        function initBackToTop() {
            window.addEventListener('scroll', () => {
                if (window.scrollY > 300) {
                    backToTop.classList.add('visible');
                } else {
                    backToTop.classList.remove('visible');
                }
            });

            backToTop.addEventListener('click', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }

        // Initialize WhatsApp chat functionality
        function initWhatsAppChat() {
            // Toggle chat box visibility
            whatsappToggle.addEventListener('click', () => {
                whatsappChatBox.classList.toggle('active');
                if (whatsappChatBox.classList.contains('active')) {
                    whatsappInput.focus();
                }
            });

            // Send message when button is clicked
            sendMessageBtn.addEventListener('click', sendWhatsAppMessage);

            // Send message when Enter key is pressed
            whatsappInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    sendWhatsAppMessage();
                }
            });

            // Quick reply buttons
            document.querySelectorAll('.whatsapp-quick-reply').forEach(button => {
                button.addEventListener('click', (e) => {
                    const reply = e.target.getAttribute('data-reply');
                    whatsappInput.value = reply;
                    sendWhatsAppMessage();
                });
            });

            // Close chat when clicking outside
            document.addEventListener('click', (e) => {
                if (!e.target.closest('.whatsapp-widget') && whatsappChatBox.classList.contains('active')) {
                    whatsappChatBox.classList.remove('active');
                }
            });
        }

        // Send WhatsApp message
        function sendWhatsAppMessage() {
            const message = whatsappInput.value.trim();
            if (!message) return;

            // Add user message to chat
            addMessageToChat(message, 'user');

            // Clear input
            whatsappInput.value = '';

            // Simulate bot response after a delay
            setTimeout(() => {
                let botResponse = whatsappBotResponses.default;

                // Check for quick reply matches
                for (const [key, value] of Object.entries(whatsappBotResponses)) {
                    if (message.toLowerCase().includes(key.toLowerCase())) {
                        botResponse = value;
                        break;
                    }
                }

                addMessageToChat(botResponse, 'bot');

                // If user asked about tracking, suggest visiting track page
                if (message.toLowerCase().includes('track') || message.toLowerCase().includes('shipment')) {
                    setTimeout(() => {
                        addMessageToChat("Would you like me to take you to our Track Shipment page?", 'bot');

                        // Add a special quick action
                        const actionDiv = document.createElement('div');
                        actionDiv.className = 'whatsapp-quick-replies';
                        actionDiv.innerHTML = `
                            <div class="whatsapp-quick-reply" id="goToTrackPage">Go to Track Shipment Page</div>
                        `;
                        whatsappBody.appendChild(actionDiv);

                        document.getElementById('goToTrackPage').addEventListener('click', () => {
                            showPage('track');
                            whatsappChatBox.classList.remove('active');
                        });
                    }, 1000);
                }

                // If user asked about refunds, suggest refund policy page
                if (message.toLowerCase().includes('refund') || message.toLowerCase().includes('return')) {
                    setTimeout(() => {
                        addMessageToChat("You can find detailed information about our return and refund policies on our Refund Policy page.", 'bot');

                        // Add a special quick action
                        const actionDiv = document.createElement('div');
                        actionDiv.className = 'whatsapp-quick-replies';
                        actionDiv.innerHTML = `
                            <div class="whatsapp-quick-reply" id="goToRefundPage">Go to Refund Policy</div>
                        `;
                        whatsappBody.appendChild(actionDiv);

                        document.getElementById('goToRefundPage').addEventListener('click', () => {
                            showPage('refund');
                            whatsappChatBox.classList.remove('active');
                        });
                    }, 1000);
                }
            }, 1000);
        }

        // Add message to WhatsApp chat
        function addMessageToChat(message, sender) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `whatsapp-message ${sender}`;

            const now = new Date();
            const timeString = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

            messageDiv.innerHTML = `
                <div class="message-bubble">
                    ${message}
                    <div class="message-time">${timeString}</div>
                </div>
            `;

            whatsappBody.appendChild(messageDiv);

            // Scroll to bottom of chat
            whatsappBody.scrollTop = whatsappBody.scrollHeight;
        }

        // Show specific page and hide others
        function showPage(pageId) {
            pages.forEach(page => {
                if (page.id === pageId) {
                    page.classList.remove('hidden');
                } else {
                    page.classList.add('hidden');
                }
            });

            // Special handling for cart page
            if (pageId === 'cart') {
                renderCart();
            }

            // Scroll to top when changing pages
            window.scrollTo(0, 0);
        }

        // Render products on products page
        function renderProducts() {
            if (!productsGrid) return;

            productsGrid.innerHTML = '';

            products.forEach(product => {
                const productCard = document.createElement('div');
                productCard.className = 'product-card';
                productCard.setAttribute('data-category', product.category);
                productCard.setAttribute('data-price', product.price);

                productCard.innerHTML = `
                    <div class="product-img" style="background-image: url('${product.image}')"></div>
                    <div class="product-content">
                        <h3>${product.name}</h3>
                        <p>${product.description}</p>
                        <div class="product-price">$${product.price.toFixed(2)}</div>
                        <button class="btn add-to-cart" data-id="${product.id}">Add to Cart</button>
                    </div>
                `;

                productsGrid.appendChild(productCard);
            });

            // Add event listeners to "Add to Cart" buttons
            document.querySelectorAll('.add-to-cart').forEach(button => {
                button.addEventListener('click', (e) => {
                    const productId = parseInt(e.target.getAttribute('data-id'));
                    addToCart(productId);
                });
            });
        }

        // Apply product filters
        function applyProductFilters() {
            const selectedCategories = Array.from(document.querySelectorAll('.category-filter:checked')).map(cb => cb.value);
            const selectedPrice = document.querySelector('input[name="price"]:checked').value;

            document.querySelectorAll('.product-card').forEach(card => {
                const category = card.getAttribute('data-category');
                const price = parseFloat(card.getAttribute('data-price'));

                let categoryMatch = selectedCategories.includes(category);
                let priceMatch = false;

                switch(selectedPrice) {
                    case 'under50':
                        priceMatch = price < 50;
                        break;
                    case '50-200':
                        priceMatch = price >= 50 && price <= 200;
                        break;
                    case 'over200':
                        priceMatch = price > 200;
                        break;
                    default:
                        priceMatch = true;
                }

                if (categoryMatch && priceMatch) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Reset product filters
        function resetProductFilters() {
            document.querySelectorAll('.category-filter').forEach(cb => cb.checked = true);
            document.querySelector('input[name="price"][value="all"]').checked = true;
            document.querySelectorAll('.product-card').forEach(card => card.style.display = 'block');
        }

        // Add product to cart
        function addToCart(productId) {
            const product = products.find(p => p.id === productId);
            if (!product) return;

            const existingItem = cart.find(item => item.id === productId);

            if (existingItem) {
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

            // Save to localStorage
            localStorage.setItem('cart', JSON.stringify(cart));

            // Update cart count
            updateCartCount();

            // Show confirmation
            alert(`${product.name} added to cart!`);

            // If on cart page, update display
            if (!document.getElementById('cart').classList.contains('hidden')) {
                renderCart();
            }
        }

        // Update cart count in header
        function updateCartCount() {
            if (!cartCount) return;

            const totalItems = cart.reduce((total, item) => total + item.quantity, 0);
            cartCount.textContent = totalItems;
        }

        // Render cart items
        function renderCart() {
            if (!cartItems || !cartSummary) return;

            cartItems.innerHTML = '';

            if (cart.length === 0) {
                emptyCartMessage.style.display = 'block';
                cartSummary.style.display = 'none';
                return;
            }

            emptyCartMessage.style.display = 'none';
            cartSummary.style.display = 'block';

            let subtotal = 0;

            cart.forEach(item => {
                const itemTotal = item.price * item.quantity;
                subtotal += itemTotal;

                const cartItem = document.createElement('div');
                cartItem.className = 'cart-item';
                cartItem.innerHTML = `
                    <div class="cart-item-img" style="background-image: url('${item.image}')"></div>
                    <div class="cart-item-name">
                        <h4>${item.name}</h4>
                    </div>
                    <div class="cart-item-price">$${item.price.toFixed(2)}</div>
                    <div class="cart-item-quantity">
                        <button class="quantity-btn" data-id="${item.id}" data-action="decrease">-</button>
                        <span>${item.quantity}</span>
                        <button class="quantity-btn" data-id="${item.id}" data-action="increase">+</button>
                    </div>
                    <div class="cart-item-total">$${itemTotal.toFixed(2)}</div>
                    <button class="btn remove-item" data-id="${item.id}" style="background-color: #dc3545;">Remove</button>
                `;

                cartItems.appendChild(cartItem);
            });

            // Calculate totals
            const shipping = 12.99;
            const tax = subtotal * 0.08; // 8% tax
            const total = subtotal + shipping + tax;

            // Update summary
            document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
            document.getElementById('shipping').textContent = `$${shipping.toFixed(2)}`;
            document.getElementById('tax').textContent = `$${tax.toFixed(2)}`;
            document.getElementById('total').textContent = `$${total.toFixed(2)}`;

            // Add event listeners to cart buttons
            document.querySelectorAll('.quantity-btn').forEach(button => {
                button.addEventListener('click', (e) => {
                    const productId = parseInt(e.target.getAttribute('data-id'));
                    const action = e.target.getAttribute('data-action');
                    updateCartQuantity(productId, action);
                });
            });

            document.querySelectorAll('.remove-item').forEach(button => {
                button.addEventListener('click', (e) => {
                    const productId = parseInt(e.target.getAttribute('data-id'));
                    removeFromCart(productId);
                });
            });
        }

        // Update cart item quantity
        function updateCartQuantity(productId, action) {
            const item = cart.find(item => item.id === productId);
            if (!item) return;

            if (action === 'increase') {
                item.quantity += 1;
            } else if (action === 'decrease') {
                item.quantity -= 1;
                if (item.quantity <= 0) {
                    cart = cart.filter(item => item.id !== productId);
                }
            }

            // Save to localStorage
            localStorage.setItem('cart', JSON.stringify(cart));

            // Update UI
            updateCartCount();
            renderCart();
        }

        // Remove item from cart
        function removeFromCart(productId) {
            cart = cart.filter(item => item.id !== productId);

            // Save to localStorage
            localStorage.setItem('cart', JSON.stringify(cart));

            // Update UI
            updateCartCount();
            renderCart();
        }

        // Initialize the application when DOM is loaded
        document.addEventListener('DOMContentLoaded', init);
        
    </script>
