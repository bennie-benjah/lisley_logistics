// public/js/home/cart.js
window.CartUI = (function() {
    const cartCountEl = document.getElementById('cartCount');

    function updateCount() {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        if (cartCountEl) {
            const totalQty = cart.reduce((sum, item) => sum + item.quantity, 0);
            cartCountEl.textContent = totalQty;
        }
    }

    function renderCart() {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        const cartContainer = document.getElementById('cartContainer');
        if (!cartContainer) return;

        cartContainer.innerHTML = '';

        cart.forEach(item => {
            const div = document.createElement('div');
            div.className = 'cart-item';
            div.innerHTML = `
                <div>${item.name}</div>
                <div>Qty: ${item.quantity}</div>
                <div>Price: $${item.price.toFixed(2)}</div>
            `;
            cartContainer.appendChild(div);
        });
    }

    return {
        updateCount,
        renderCart
    };
})();
