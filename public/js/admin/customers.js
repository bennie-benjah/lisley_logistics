// customers.js
class CustomersModule {
    constructor(admin) {
        this.admin = admin;
        this.csrfToken = admin.csrfToken;
        this.urls = {
            apiIndex: '/admin/customers/api/list',
            // ... other endpoints
        };
    }

    init() {
        console.log('Customers module initialized');
        this.initEventListeners();
        this.loadCustomers();
    }

    // ... module-specific methods
}

window.CustomersModule = CustomersModule;

// Auto-initialize
document.addEventListener('DOMContentLoaded', function() {
    const customersPage = document.getElementById('customersPage');
    if (customersPage && !customersPage.classList.contains('hidden')) {
        const admin = window.Admin || { csrfToken: document.querySelector('meta[name="csrf-token"]')?.content };
        window.customersModule = new CustomersModule(admin);
        window.customersModule.init();
    }
});