window.init = function () {
  setupEventListeners();
  renderProducts(products);
  updateCartCount();
  showPage('home');
  initWhatsAppChat();
  initFAQ();
  initBackToTop();
};
