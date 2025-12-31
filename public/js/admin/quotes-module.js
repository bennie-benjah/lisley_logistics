// public/js/quotes-module.js

class QuotesModule {
    constructor(admin) {
        this.admin = admin;
        this.csrfToken = admin.csrfToken;
        this.urls = {
            data: '/admin/quotes/data',
            stats: '/admin/quotes/stats',
            updateStatus: (id) => `/admin/quotes/${id}/status`,
            destroy: (id) => `/admin/quotes/${id}`,
            bulkAction: '/admin/quotes/bulk-action'
        };
        this.selectedQuotes = new Set();
        this.init();
    }

    init() {
        // Use the correct page ID - should be 'quotesPage' based on your AdminCore
        this.pageElement = document.getElementById('quotesPage');

        if (!this.pageElement) {
            console.error('Quotes page not found! Looking for #quotesPage');
            return;
        }

        console.log('QuotesModule initialized');
        this.bindEvents();
        this.loadQuotes();
    }

    bindEvents() {
        // Tabs
        const tabs = this.pageElement.querySelectorAll('.tab');
        tabs.forEach(tab => {
            tab.addEventListener('click', (e) => this.onTabClick(e));
        });

        // Filters
        const filterService = this.pageElement.querySelector('#filterService');
        if (filterService) {
            filterService.addEventListener('change', () => this.loadQuotes());
        }

        const searchInput = this.pageElement.querySelector('#searchQuotes');
        if (searchInput) {
            searchInput.addEventListener('input', () => this.loadQuotes());
        }

        const refreshBtn = this.pageElement.querySelector('#refreshQuotesBtn');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', () => {
                this.loadQuotes();
            });
        }

        // Bulk actions - THESE WERE OUTSIDE THE METHOD!
        const selectAll = document.getElementById('selectAllQuotes');
        if (selectAll) {
            selectAll.addEventListener('change', (e) => this.toggleSelectAll(e));
        }

        const applyBulk = document.getElementById('applyBulkAction');
        if (applyBulk) {
            applyBulk.addEventListener('click', () => this.applyBulkAction());
        }
    }

    async loadQuotes() {
        try {
            const params = new URLSearchParams();

            // Get active tab
            const activeTab = this.pageElement.querySelector('.tab.active');
            if (activeTab && activeTab.dataset.tab !== 'all') {
                params.append('status', activeTab.dataset.tab);
            }

            // Get filters
            const service = this.pageElement.querySelector('#filterService')?.value;
            if (service) params.append('service', service);

            const search = this.pageElement.querySelector('#searchQuotes')?.value;
            if (search) params.append('search', search);

            const url = `${this.urls.data}?${params.toString()}`;
            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const quotes = await response.json();
            this.renderQuotes(quotes);

        } catch (error) {
            console.error('Error loading quotes:', error);
            this.showError('Failed to load quote requests');
        }
    }

    renderQuotes(quotes) {
        const grid = this.pageElement.querySelector('#quotesGrid');
        if (!grid) {
            console.error('quotesGrid element not found in page');
            return;
        }

        grid.innerHTML = '';

        if (!quotes || quotes.length === 0) {
            grid.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h4>No Quote Requests Found</h4>
                        <p class="text-muted">No quote requests match your current filters.</p>
                    </td>
                </tr>
            `;
            const countElement = this.pageElement.querySelector('#quotesCount');
            if (countElement) countElement.textContent = '0';
            return;
        }

        const countElement = this.pageElement.querySelector('#quotesCount');
        if (countElement) countElement.textContent = quotes.length;

        quotes.forEach(quote => {
            const row = document.createElement('tr');
            row.dataset.id = quote.id;
            row.className = 'quote-row';

            // Format phone if exists
            let phoneHtml = '';
            if (quote.phone) {
                phoneHtml = `<div><i class="fas fa-phone"></i> ${quote.phone}</div>`;
            }

            // Format company if exists
            let companyHtml = '';
            if (quote.company) {
                companyHtml = `<br><small class="text-muted">${quote.company}</small>`;
            }

            // Format budget if exists
            let budgetHtml = '';
            if (quote.budget) {
                budgetHtml = `<br><small><strong>Budget:</strong> $${quote.budget}</small>`;
            }

            row.innerHTML = `
                <td>
                    <div class="quote-name">
                        <strong>${quote.name}</strong>
                        ${companyHtml}
                    </div>
                </td>
                <td>
                    <div class="quote-contact">
                        <div><i class="fas fa-envelope"></i> ${quote.email}</div>
                        ${phoneHtml}
                    </div>
                </td>
                <td>
                    <span class="service-badge">${quote.service_name}</span>
                </td>
                <td>
                    <div class="quote-details">
                        ${quote.details ? (quote.details.substring(0, 100) + (quote.details.length > 100 ? '...' : '')) : 'No details provided'}
                        ${budgetHtml}
                    </div>
                </td>
                <td>
                    <small class="text-muted">${quote.created_at}</small>
                </td>
                <td>
                    ${quote.status_badge}
                </td>
                <td>
                    <div class="action-buttons">
                        <button class="btn-icon btn-view" data-id="${quote.id}" title="View Details">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn-icon btn-edit-status" data-id="${quote.id}" title="Change Status">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                </td>
            `;

            // Add event listeners
            const viewBtn = row.querySelector('.btn-view');
            if (viewBtn) {
                viewBtn.addEventListener('click', () => this.viewQuote(quote));
            }

            const editBtn = row.querySelector('.btn-edit-status');
            if (editBtn) {
                editBtn.addEventListener('click', () => this.showStatusModal(quote.id));
            }

            grid.appendChild(row);
        });
    }

    async showStatusModal(quoteId) {
        const content = `
            <div class="status-form">
                <label for="newStatus">Select Status:</label>
                <select id="newStatus" class="form-control">
                    <option value="new">New</option>
                    <option value="reviewed">Reviewed</option>
                    <option value="quoted">Quoted</option>
                    <option value="closed">Closed</option>
                </select>
            </div>
        `;

        if (this.admin && this.admin.showModal) {
            this.admin.showModal('Update Quote Status', content, async () => {
                const newStatus = document.getElementById('newStatus').value;
                await this.updateQuoteStatus(quoteId, newStatus);
                return true;
            });
        }
    }

    async updateQuoteStatus(quoteId, status) {
        try {
            const response = await fetch(this.urls.updateStatus(quoteId), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify({ status })
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const result = await response.json();

            if (result.success) {
                if (this.admin.showNotification) {
                    this.admin.showNotification(result.message, 'success');
                }
                this.loadQuotes();
            }

        } catch (error) {
            console.error('Error updating status:', error);
            if (this.admin.showNotification) {
                this.admin.showNotification('Failed to update status', 'error');
            }
        }
    }

    async deleteQuote(quoteId) {
        if (!confirm('Are you sure you want to delete this quote request?')) {
            return;
        }

        try {
            const response = await fetch(this.urls.destroy(quoteId), {
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
                    this.admin.showNotification(result.message, 'success');
                }
                this.loadQuotes();
            }

        } catch (error) {
            console.error('Error deleting quote:', error);
            if (this.admin.showNotification) {
                this.admin.showNotification('Failed to delete quote', 'error');
            }
        }
    }

    onTabClick(e) {
        const tab = e.target;
        if (tab.classList.contains('active')) return;

        // Update active tab
        this.pageElement.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
        tab.classList.add('active');

        // Load quotes for this tab
        this.loadQuotes();
    }

    viewQuote(quote) {
        const modalContent = `
            <div class="quote-detail-view">
                <h4>${quote.name}</h4>
                <div class="detail-group">
                    <label>Email:</label>
                    <p>${quote.email}</p>
                </div>
                <div class="detail-group">
                    <label>Phone:</label>
                    <p>${quote.phone || 'Not provided'}</p>
                </div>
                ${quote.company ? `
                <div class="detail-group">
                    <label>Company:</label>
                    <p>${quote.company}</p>
                </div>
                ` : ''}
                <div class="detail-group">
                    <label>Service:</label>
                    <p>${quote.service_name}</p>
                </div>
                ${quote.budget ? `
                <div class="detail-group">
                    <label>Budget:</label>
                    <p>$${quote.budget}</p>
                </div>
                ` : ''}
                <div class="detail-group">
                    <label>Details:</label>
                    <p style="white-space: pre-wrap;">${quote.details || 'No details provided'}</p>
                </div>
                <div class="detail-group">
                    <label>Submitted:</label>
                    <p>${quote.created_at}</p>
                </div>
                <div class="detail-group">
                    <label>Status:</label>
                    <p>${quote.status_badge}</p>
                </div>
            </div>
        `;

        if (this.admin && this.admin.showModal) {
            this.admin.showModal('Quote Details', modalContent, () => {
                return true;
            });
        }
    }

    showError(message) {
        const grid = this.pageElement.querySelector('#quotesGrid');
        if (grid) {
            grid.innerHTML = `
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

// Export for use in other files
window.QuotesModule = QuotesModule;
