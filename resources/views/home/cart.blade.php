<section id="cart" class="page hidden">
            <div class="container">
                <h1 class="page-title">Your Shopping Cart</h1>
                <p class="page-subtitle">Review your selected products and proceed to checkout.</p>

                <div class="cart-container">
                    <div class="cart-items" id="cartItems">
                        <!-- Cart items will be populated by JavaScript -->
                        <p id="emptyCartMessage">Your cart is currently empty. <a href="#products">Browse products</a> to add items.</p>
                    </div>

                    <div class="cart-summary" id="cartSummary" style="display: none;">
                        <h3>Order Summary</h3>
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span id="subtotal">$0.00</span>
                        </div>
                        <div class="summary-row">
                            <span>Shipping</span>
                            <span id="shipping">$12.99</span>
                        </div>
                        <div class="summary-row">
                            <span>Tax</span>
                            <span id="tax">$0.00</span>
                        </div>
                        <div class="summary-row summary-total">
                            <span>Total</span>
                            <span id="total">$0.00</span>
                        </div>

                        <button class="btn" id="checkoutBtn" style="width: 100%; margin-top: 20px;">Proceed to Checkout</button>
                    </div>
                </div>
            </div>
        </section>
