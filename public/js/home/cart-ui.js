// public/js/home/cart/cart.js
window.Cart = {
    items: JSON.parse(localStorage.getItem('cart')) || [],

    save() {
        localStorage.setItem('cart', JSON.stringify(this.items));
    },

    add(productId) {
        const product = window.PRODUCTS.find(p => p.id === productId);
        if (!product) return;

        const item = this.items.find(i => i.id === productId);

        if (item) item.quantity++;
        else this.items.push({ ...product, quantity: 1 });

        this.save();
        CartUI.updateCount();
    },

    remove(productId) {
        this.items = this.items.filter(i => i.id !== productId);
        this.save();
        CartUI.updateCount();
    }
};
