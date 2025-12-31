// customers.js
class CustomersModule {
    constructor(admin) {
        this.admin = admin;
        this.csrfToken = admin.csrfToken;
        this.urls = {
            data: '/admin/customers/data',
            stats: '/admin/customers/stats',
            update: (id) => `/admin/customers/${id}`,
            destroy: (id) => `/admin/customers/${id}`
        };
        this.customerChart = null;
        this.init();
    }

    init() {
        console.log('Customers module initialized');
        this.bindEvents();
        this.loadCustomers();
        this.loadStats();
    }

    bindEvents() {
    // Add customer button
    const addBtn = document.getElementById('addCustomerBtn');
    if (addBtn) {
        addBtn.addEventListener('click', () => this.showAddModal());
    }

    // Search functionality
    const searchInput = document.getElementById('searchCustomers');
    if (searchInput) {
        searchInput.addEventListener('input', (e) => this.searchCustomers(e.target.value));
    }

    // Refresh button
    const refreshBtn = document.getElementById('refreshCustomersBtn');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', () => {
            this.loadCustomers();
            this.loadStats();
        });
    }
}

    async loadCustomers() {
        try {
            const searchInput = document.getElementById('searchCustomers');
            const search = searchInput ? searchInput.value : '';

            const url = `${this.urls.data}?search=${encodeURIComponent(search)}`;
            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const customers = await response.json();
            this.renderCustomers(customers);

        } catch (error) {
            console.error('Error loading customers:', error);
            this.showError('Failed to load customers');
        }
    }

    async loadStats() {
        try {
            const response = await fetch(this.urls.stats, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const stats = await response.json();
            this.renderChart(stats);

        } catch (error) {
            console.error('Error loading stats:', error);
        }
    }

    renderCustomers(customers) {
        const tableBody = document.getElementById('customersTableBody');
        if (!tableBody) return;

        tableBody.innerHTML = '';

        if (!customers || customers.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <h4>No Customers Found</h4>
                        <p class="text-muted">No customers match your search criteria.</p>
                    </td>
                </tr>
            `;
            return;
        }

        customers.forEach(customer => {
            // Format recent shipments
            let shipmentsHtml = '';
            if (customer.recent_shipments && customer.recent_shipments.length > 0) {
                shipmentsHtml = customer.recent_shipments.map(shipment => `
                    <div class="shipment-item">
                        <span class="tracking-badge">${shipment.tracking_number}</span>
                        <span class="status-badge ${shipment.status_color}">${shipment.status_text}</span>
                    </div>
                `).join('');
            } else {
                shipmentsHtml = '<span class="text-muted">No shipments</span>';
            }

            // Format recent quotes
            let quotesHtml = '';
            if (customer.recent_quotes && customer.recent_quotes.length > 0) {
                quotesHtml = customer.recent_quotes.map(quote => `
                    <div class="quote-item">
                        <span>${quote.service}</span>
                        <span>${quote.status_badge}</span>
                    </div>
                `).join('');
            } else {
                quotesHtml = '<span class="text-muted">No quotes</span>';
            }

            const row = document.createElement('tr');
            row.className = 'customer-row';

            row.innerHTML = `
                <td>#${customer.id}</td>
                <td>
                    <div class="customer-info">
                        <strong>${customer.name}</strong>
                        <small class="text-muted">Joined ${customer.created_at}</small>
                    </div>
                </td>
                <td>
                    <div class="contact-info">
                        <div><i class="fas fa-envelope"></i> ${customer.email}</div>
                        ${customer.phone ? `<div><i class="fas fa-phone"></i> ${customer.phone}</div>` : ''}
                    </div>
                </td>
                <td>
    <div class="shipments-info">
        <div class="count-badge">
            <i class="fas fa-shipping-fast"></i>
            ${customer.shipments_count || 0}
        </div>
        <div class="shipments-list">
            ${customer.recent_shipments && customer.recent_shipments.length > 0 ? 
                customer.recent_shipments.map(shipment => `
                    <div class="shipment-item">
                        <span class="tracking-badge">${shipment.tracking_number || 'N/A'}</span>
                        <span class="status-badge ${shipment.status_color || 'secondary'}">
                            ${shipment.status_text || 'Pending'}
                        </span>
                    </div>
                `).join('') : 
                '<span class="text-muted">No shipments</span>'}
        </div>
    </div>
</td>
                <td>
    <div class="quotes-info">
        <div class="count-badge">
            <i class="fas fa-file-invoice-dollar"></i>
            ${customer.quotes_count || 0}
        </div>
        <div class="quotes-list">
            ${customer.recent_quotes && customer.recent_quotes.length > 0 ? 
                customer.recent_quotes.map(quote => `
                    <div class="quote-item">
                        <span>${quote.service || 'Service'}</span>
                        <span>${quote.status_badge || '<span class="badge">New</span>'}</span>
                    </div>
                `).join('') : 
                '<span class="text-muted">No quotes</span>'}
        </div>
    </div>
</td>
                <td>
    <div class="customer-stats">
        <div class="stat-item">
            <small class="text-success">
                <i class="fas fa-truck"></i> ${customer.active_shipments || 0} Active
            </small>
        </div>
        <div class="stat-item">
            <small class="text-warning">
                <i class="fas fa-clock"></i> ${customer.pending_quotes || 0} Pending
            </small>
        </div>
    </div>
</td>
                <td>
                    <div class="action-buttons">
                        <button class="btn-icon btn-view" data-id="${customer.id}" title="View Details">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn-icon btn-edit" data-id="${customer.id}" title="Edit Customer">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-icon btn-delete" data-id="${customer.id}" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            `;

            // Add event listeners
            const viewBtn = row.querySelector('.btn-view');
            if (viewBtn) {
                viewBtn.addEventListener('click', () => this.viewCustomer(customer));
            }

            const editBtn = row.querySelector('.btn-edit');
            if (editBtn) {
                editBtn.addEventListener('click', () => this.editCustomer(customer.id));
            }

            const deleteBtn = row.querySelector('.btn-delete');
            if (deleteBtn) {
                deleteBtn.addEventListener('click', () => this.deleteCustomer(customer.id));
            }

            tableBody.appendChild(row);
        });
    }

    renderChart(stats) {
        const ctx = document.getElementById('customerChart');
        if (!ctx) return;

        // Destroy existing chart if it exists
        if (this.customerChart) {
            this.customerChart.destroy();
        }

        this.customerChart = new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: {
                labels: stats.labels,
                datasets: [{
                    label: 'New Customers',
                    data: stats.data,
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }

    searchCustomers(query) {
        // Debounce the search
        clearTimeout(this.searchTimeout);
        this.searchTimeout = setTimeout(() => {
            this.loadCustomers();
        }, 300);
    }

    viewCustomer(customer) {
        const modalContent = `
            <div class="customer-detail-view">
                <div class="customer-header">
                    <h4>${customer.name}</h4>
                    <span class="customer-id">Customer #${customer.id}</span>
                </div>

                <div class="detail-section">
                    <h5>Contact Information</h5>
                    <div class="contact-details">
                        <div><strong>Email:</strong> ${customer.email}</div>
                        <div><strong>Phone:</strong> ${customer.phone || 'Not provided'}</div>
                        <div><strong>Joined:</strong> ${customer.created_at}</div>
                    </div>
                </div>

                <div class="detail-section">
                    <h5>Shipment Statistics</h5>
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-value">${customer.shipments_count}</div>
                            <div class="stat-label">Total Shipments</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value text-success">${customer.active_shipments}</div>
                            <div class="stat-label">Active</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value text-primary">${customer.delivered_shipments}</div>
                            <div class="stat-label">Delivered</div>
                        </div>
                    </div>
                </div>

                <div class="detail-section">
                    <h5>Quote Statistics</h5>
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-value">${customer.quotes_count}</div>
                            <div class="stat-label">Total Quotes</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value text-warning">${customer.pending_quotes}</div>
                            <div class="stat-label">Pending</div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        if (this.admin && this.admin.showModal) {
            this.admin.showModal('Customer Details', modalContent, () => {
                return true;
            });
        }
    }

    editCustomer(customerId) {
        console.log('Edit customer:', customerId);
        // Implement edit functionality
    }

    async deleteCustomer(customerId) {
        if (!confirm('Are you sure you want to delete this customer? This action cannot be undone.')) {
            return;
        }

        try {
            const response = await fetch(this.urls.destroy(customerId), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const result = await response.json();

            if (result.success) {
                if (this.admin.showNotification) {
                    this.admin.showNotification('Customer deleted successfully', 'success');
                }
                this.loadCustomers();
                this.loadStats();
            }

        } catch (error) {
            console.error('Error deleting customer:', error);
            if (this.admin.showNotification) {
                this.admin.showNotification('Failed to delete customer', 'error');
            }
        }
    }

    showAddModal() {
        const content = `
            <form id="addCustomerForm">
                <div class="form-group">
                    <label for="customerName">Name *</label>
                    <input type="text" id="customerName" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="customerEmail">Email *</label>
                    <input type="email" id="customerEmail" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="customerPhone">Phone</label>
                    <input type="tel" id="customerPhone" class="form-control">
                </div>
                <div class="form-group">
                    <label for="customerPassword">Password *</label>
                    <input type="password" id="customerPassword" class="form-control" required>
                </div>
                <input type="hidden" name="role" value="customer">
            </form>
        `;

        if (this.admin && this.admin.showModal) {
            this.admin.showModal('Add New Customer', content, async () => {
                // Implement form submission
                const name = document.getElementById('customerName').value;
                const email = document.getElementById('customerEmail').value;
                const phone = document.getElementById('customerPhone').value;
                const password = document.getElementById('customerPassword').value;

                if (!name || !email || !password) {
                    if (this.admin.showNotification) {
                        this.admin.showNotification('Please fill in all required fields', 'warning');
                    }
                    return false;
                }

                // Add customer API call here
                return true;
            });
        }
    }

    showError(message) {
        const tableBody = document.getElementById('customersTableBody');
        if (tableBody) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-4 text-danger">
                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                        <p>${message}</p>
                        <button class="btn btn-sm" onclick="location.reload()">Retry</button>
                    </td>
                </tr>
            `;
        }
    }
}

// Export for use
window.CustomersModule = CustomersModule;
