// ==================== ADMIN CORE ====================
class AdminCore {
    constructor() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        this.modules = {};
        this.currentPage = 'dashboard';
        this.init();
    }

    init() {
        this.initEventListeners();
        // Load the module for the current page if available
        this.loadModule(this.currentPage);
    }

    initEventListeners() {
        document.querySelectorAll('.admin-nav-link').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const page = link.getAttribute('data-page');
                this.showPage(page);

                document.querySelectorAll('.admin-nav-link').forEach(navLink => {
                    navLink.classList.remove('active');
                });
                link.classList.add('active');
            });
        });

        const modalOverlay = document.getElementById('modalOverlay');
        if (modalOverlay) {
            modalOverlay.addEventListener('click', (e) => {
                if (e.target === modalOverlay) {
                    this.hideModal();
                }
            });
        }
    }

    showPage(pageId) {
        this.currentPage = pageId;

        const pageTitles = {
            'dashboard': 'Dashboard Overview',
            'services': 'Services Management',
            'products': 'Products Management',
            'shipments': 'Shipment Management',
            'inventory': 'Inventory Management',
            'orders': 'Order Management',
            'customers': 'Customer Management',
            'reports': 'Reports & Analytics',
            'settings': 'System Settings'
        };

        const pageTitle = document.getElementById('pageTitle');
        if (pageTitle) pageTitle.textContent = pageTitles[pageId] || 'Dashboard';

        document.querySelectorAll('.admin-page').forEach(page => {
            if (page.id === `${pageId}Page`) {
                page.classList.remove('hidden');
                this.loadModule(pageId);
            } else {
                page.classList.add('hidden');
            }
        });
    }

    loadModule(moduleName) {
        if (this.modules[moduleName]) return; // Already loaded

        const className = `${moduleName.charAt(0).toUpperCase() + moduleName.slice(1)}Module`;
        const moduleClass = window[className];

        if (!moduleClass) return; // JS not loaded, skip

        this.modules[moduleName] = new moduleClass(this);
        this.modules[moduleName].init?.();
    }

    showModal(title, content, onSubmit = null) {
    const modalOverlay = document.getElementById('modalOverlay');
    const modalContent = document.getElementById('modalContent');
    if (!modalOverlay || !modalContent) return;

    modalContent.innerHTML = `
        <div class="modal-header">
            <h3>${title}</h3>
        </div>
        <div class="modal-body">${content}</div>
        <div class="modal-actions">
            <button id="modalSubmit" class="btn primary-btn">Save</button>
            <button id="modalClose" class="btn secondary-btn">Cancel</button>
        </div>
    `;

    // Remove old listeners
    const submitBtn = document.getElementById('modalSubmit');
    const closeBtn = document.getElementById('modalClose');

    const cleanup = () => {
        modalOverlay.classList.add('hidden');
        submitBtn.onclick = null;
        closeBtn.onclick = null;
    };

    if (onSubmit) {
        submitBtn.onclick = async () => {
            const result = await onSubmit();
            if (result !== false) cleanup();
        };
    } else {
        submitBtn.onclick = cleanup;
    }

    closeBtn.onclick = cleanup;

    modalOverlay.classList.remove('hidden');
}

    hideModal() {
        const modalOverlay = document.getElementById('modalOverlay');
        if (modalOverlay) modalOverlay.classList.add('hidden');
    }

    showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.textContent = message;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#4CAF50' : '#f44336'};
            color: white;
            padding: 15px 20px;
            border-radius: 5px;
            z-index: 10000;
            animation: slideIn 0.3s ease;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        `;
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    }
}

// Global instance
window.Admin = new AdminCore();
