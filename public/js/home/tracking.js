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
        