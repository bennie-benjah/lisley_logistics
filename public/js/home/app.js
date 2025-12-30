// public/js/home/app.js
document.addEventListener('DOMContentLoaded', () => {
    Navigation.init();
    Products.render();
    CartUI.updateCount();
    WhatsApp.init?.();
});
