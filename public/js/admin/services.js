// ==================== SERVICES MODULE ====================
class ServicesModule {
    constructor(admin) {
        this.admin = admin;
        this.csrfToken = admin.csrfToken;
        this.urls = {
            apiIndex: '/admin/services/api/list',
            apiShow: (id) => `/admin/services/api/${id}`,
            store: '/admin/services',
            update: (id) => `/admin/services/${id}`,
            destroy: (id) => `/admin/services/${id}`,
            toggleStatus: (id) => `/admin/services/${id}/toggle-status`,
            bulkAction: '/admin/services/bulk-action',
            export: '/admin/services/export'
        };
    }

    // ==================== INITIALIZATION ====================
    init() {
        console.log('Services module initialized');
        this.initEventListeners();
        this.loadServices();
    }

    // ==================== EVENT LISTENERS ====================
    initEventListeners() {
        // Add service button
        const addBtn = document.getElementById('addServiceBtn');
        if (addBtn) {
            addBtn.addEventListener('click', () => this.showServiceModal());
        }

        // Tab switching for services page
        const tabs = document.querySelectorAll('#servicesPage .tab');
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const tabId = tab.getAttribute('data-tab');
                this.filterServices(tabId);
            });
        });
    }

    // ==================== PAGE MANAGEMENT ====================
    onPageShow() {
        console.log('Services page shown');
        this.loadServices();
    }

    // ==================== DATA FETCHING ====================
    async loadServices() {
        try {
            const response = await fetch(this.urls.apiIndex, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (response.ok) {
                const services = await response.json();
                this.renderServices(services);
            } else {
                console.error('Failed to fetch services');
                this.showEmptyGrid('Failed to load services from server.');
            }
        } catch (error) {
            console.error('Error fetching services:', error);
            this.showEmptyGrid('Network error. Please check your connection.');
        }
    }

    async fetchServiceData(serviceId) {
        const response = await fetch(this.urls.apiShow(serviceId), {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': this.csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (response.ok) {
            return await response.json();
        } else {
            throw new Error('Failed to fetch service');
        }
    }

    // ==================== UI RENDERING ====================
    renderServices(services) {
        const servicesGrid = document.getElementById('servicesGrid');
        if (!servicesGrid) return;

        servicesGrid.innerHTML = '';

        if (!services || services.length === 0) {
            this.showEmptyGrid('No services found. Click "Add New Service" to create one.');
            return;
        }

        services.forEach(service => {
            const serviceItem = document.createElement('div');
            serviceItem.className = 'service-item';
            serviceItem.setAttribute('data-category', service.category);
            serviceItem.setAttribute('data-status', service.status);

            let featuresText = '';
            if (service.features) {
                if (Array.isArray(service.features)) {
                    featuresText = service.features.join(', ');
                } else if (typeof service.features === 'string') {
                    featuresText = service.features;
                }
            }

            const iconClass = service.icon || this.getDefaultIcon(service.name);

            serviceItem.innerHTML = `
                <div class="service-header">
                    <div class="service-icon">
                        <i class="${iconClass}"></i>
                    </div>
                    <div class="service-actions">
                        <button class="btn-action btn-edit" data-action="edit" data-id="${service.id}">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn-action btn-delete" data-action="delete" data-id="${service.id}">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                        <button class="btn-action btn-toggle" data-action="toggle" data-id="${service.id}"
                                title="${service.status === 'active' ? 'Deactivate' : 'Activate'}">
                            <i class="fas fa-power-off ${service.status === 'active' ? 'text-success' : 'text-muted'}"></i>
                        </button>
                    </div>
                </div>
                <div class="service-content">
                    <h4>${service.name}</h4>
                    <p class="service-description">${service.description}</p>
                    <div class="service-meta">
                        <span class="service-category">${service.category}</span>
                        <span class="service-status ${service.status}">
                            <i class="fas fa-circle"></i> ${service.status}
                        </span>
                    </div>
                    ${featuresText ? `
                        <div class="service-features">
                            <small><strong>Features:</strong> ${featuresText}</small>
                        </div>
                    ` : ''}
                    ${service.created_at ? `
                        <div class="service-date">
                            <small><i class="far fa-calendar"></i> ${new Date(service.created_at).toLocaleDateString()}</small>
                        </div>
                    ` : ''}
                </div>
            `;

            // Add event listeners to buttons
            serviceItem.querySelectorAll('[data-action]').forEach(button => {
                const action = button.getAttribute('data-action');
                const id = button.getAttribute('data-id');
                
                button.addEventListener('click', (e) => {
                    e.stopPropagation();
                    
                    switch (action) {
                        case 'edit':
                            this.editService(id);
                            break;
                        case 'delete':
                            this.deleteService(id);
                            break;
                        case 'toggle':
                            this.toggleServiceStatus(id);
                            break;
                    }
                });
            });

            servicesGrid.appendChild(serviceItem);
        });
    }

    showEmptyGrid(message) {
        const servicesGrid = document.getElementById('servicesGrid');
        if (!servicesGrid) return;

        servicesGrid.innerHTML = `
            <div class="empty-state" style="grid-column: 1 / -1; text-align: center; padding: 60px 20px;">
                <i class="fas fa-box-open fa-4x text-muted mb-4"></i>
                <h3 class="mb-3">No Services Found</h3>
                <p class="text-muted mb-4">${message}</p>
                <button class="btn btn-primary btn-lg" data-action="add-service">
                    <i class="fas fa-plus"></i> Add New Service
                </button>
            </div>
        `;

        // Add event listener to add button
        const addButton = servicesGrid.querySelector('[data-action="add-service"]');
        if (addButton) {
            addButton.addEventListener('click', () => this.showServiceModal());
        }
    }

    getDefaultIcon(serviceName) {
        const iconMap = {
            'Freight Forwarding': 'fas fa-plane',
            'Warehousing & Storage': 'fas fa-warehouse',
            'Last-Mile Delivery': 'fas fa-truck',
            'Supply Chain Management': 'fas fa-link',
            'Customs Clearance': 'fas fa-passport',
            'Real-Time Tracking': 'fas fa-satellite',
            'Air Freight': 'fas fa-plane-departure',
            'Sea Freight': 'fas fa-ship',
            'Road Transportation': 'fas fa-truck-moving',
            'Cold Chain Logistics': 'fas fa-snowflake',
            'E-commerce Fulfillment': 'fas fa-shopping-cart',
        };
        return iconMap[serviceName] || 'fas fa-box';
    }

    filterServices(filter) {
        const serviceItems = document.querySelectorAll('.service-item');
        serviceItems.forEach(item => {
            switch (filter) {
                case 'all':
                    item.style.display = 'block';
                    break;
                case 'active':
                    item.style.display = item.getAttribute('data-status') === 'active' ? 'block' : 'none';
                    break;
                case 'inactive':
                    item.style.display = item.getAttribute('data-status') === 'inactive' ? 'block' : 'none';
                    break;
            }
        });
    }

    // ==================== CRUD OPERATIONS ====================
    async deleteService(id) {
        if (!confirm('Are you sure you want to delete this service?')) {
            return;
        }

        try {
            const response = await fetch(this.urls.destroy(id), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                this.showNotification('Service deleted successfully!', 'success');
                await this.loadServices();
            } else {
                const data = await response.json();
                this.showNotification(data.message || 'Failed to delete service', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showNotification('Network error. Please try again.', 'error');
        }
    }

    async toggleServiceStatus(id) {
        try {
            const response = await fetch(this.urls.toggleStatus(id), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                this.showNotification('Service status updated!', 'success');
                await this.loadServices();
            } else {
                const data = await response.json();
                this.showNotification(data.message || 'Failed to update status', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showNotification('Network error. Please try again.', 'error');
        }
    }

    // ==================== MODAL OPERATIONS ====================
    showServiceModal(serviceId = null) {
        const isEdit = serviceId !== null;
        const title = isEdit ? 'Edit Service' : 'Add New Service';

        if (isEdit) {
            this.fetchServiceData(serviceId)
                .then(service => this.showServiceForm(service, title, isEdit))
                .catch(error => {
                    console.error('Error fetching service:', error);
                    this.showNotification('Failed to load service data', 'error');
                });
        } else {
            this.showServiceForm(null, title, false);
        }
    }

    showServiceForm(service, title, isEdit) {
        let featuresString = '';
        if (service && service.features) {
            if (Array.isArray(service.features)) {
                featuresString = service.features.join(', ');
            } else {
                featuresString = service.features;
            }
        }

        const content = `
            <form id="serviceForm">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="modalServiceName" class="form-label">Service Name *</label>
                        <input type="text" id="modalServiceName" class="form-control"
                               value="${service ? service.name : ''}" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="modalServiceCategory" class="form-label">Category *</label>
                        <input type="text" id="modalServiceCategory" class="form-control"
                               value="${service ? service.category : ''}"
                               placeholder="e.g., Shipping, Storage, Delivery" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="modalServiceIcon" class="form-label">Icon</label>
                        <select id="modalServiceIcon" class="form-control">
                            <option value="fas fa-plane" ${service && service.icon === 'fas fa-plane' ? 'selected' : ''}>Air Freight</option>
                            <option value="fas fa-ship" ${service && service.icon === 'fas fa-ship' ? 'selected' : ''}>Sea Freight</option>
                            <option value="fas fa-truck" ${service && service.icon === 'fas fa-truck' ? 'selected' : ''}>Road Freight</option>
                            <option value="fas fa-warehouse" ${service && service.icon === 'fas fa-warehouse' ? 'selected' : ''}>Warehousing</option>
                            <option value="fas fa-box" ${service && service.icon === 'fas fa-box' ? 'selected' : ''}>Packaging</option>
                            <option value="fas fa-passport" ${service && service.icon === 'fas fa-passport' ? 'selected' : ''}>Customs</option>
                            <option value="fas fa-satellite" ${service && service.icon === 'fas fa-satellite' ? 'selected' : ''}>Tracking</option>
                            <option value="fas fa-link" ${service && service.icon === 'fas fa-link' ? 'selected' : ''}>Supply Chain</option>
                            <option value="fas fa-globe" ${service && service.icon === 'fas fa-globe' ? 'selected' : ''}>Global</option>
                            <option value="fas fa-people-carry" ${service && service.icon === 'fas fa-people-carry' ? 'selected' : ''}>Logistics</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="modalServiceStatus" class="form-label">Status *</label>
                        <select id="modalServiceStatus" class="form-control" required>
                            <option value="active" ${service && service.status === 'active' ? 'selected' : ''}>Active</option>
                            <option value="inactive" ${service && service.status === 'inactive' ? 'selected' : ''}>Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="modalServiceDescription" class="form-label">Description *</label>
                    <textarea id="modalServiceDescription" class="form-control" rows="4" required>${service ? service.description : ''}</textarea>
                </div>

                <div class="form-group">
                    <label for="modalServiceFeatures" class="form-label">Features (comma separated)</label>
                    <input type="text" id="modalServiceFeatures" class="form-control"
                           value="${featuresString}"
                           placeholder="Feature 1, Feature 2, Feature 3">
                </div>

                <div class="form-group">
                    <label for="serviceImage" class="form-label">Service Image</label>
                    <input type="file" id="serviceImage" class="form-control" accept="image/*">
                    ${service && service.image ? `
                        <div class="mt-2">
                            <small>Current image:</small><br>
                            <img src="/storage/${service.image}" alt="${service.name}" style="max-width: 100px; max-height: 100px;" class="mt-1">
                        </div>
                    ` : ''}
                </div>
            </form>
        `;

        this.showModal(title, content, () => this.handleServiceSubmit(isEdit, service?.id));
    }

    showModal(title, content, onSubmit = null) {
        const modalContent = `
            <div class="modal-header">
                <h3>${title}</h3>
                <button type="button" class="btn-close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                ${content}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="modalSubmit">Submit</button>
            </div>
        `;

        // Use admin's modal system or create simple one
        if (this.admin && this.admin.showModal) {
            this.admin.showModal(title, modalContent, onSubmit);
        } else {
            // Fallback to simple modal
            const modal = document.createElement('div');
            modal.className = 'modal-overlay';
            modal.innerHTML = `
                <div class="modal-content">
                    ${modalContent}
                </div>
            `;
            document.body.appendChild(modal);
            
            // Handle close buttons
            modal.querySelectorAll('[data-dismiss="modal"]').forEach(btn => {
                btn.addEventListener('click', () => modal.remove());
            });
            
            // Handle submit
            if (onSubmit) {
                modal.querySelector('#modalSubmit').addEventListener('click', async () => {
                    await onSubmit();
                    modal.remove();
                });
            }
        }
    }

    async handleServiceSubmit(isEdit, serviceId = null) {
        const formData = new FormData();

        // Get form values
        const name = document.getElementById('modalServiceName')?.value;
        const category = document.getElementById('modalServiceCategory')?.value;
        const icon = document.getElementById('modalServiceIcon')?.value;
        const status = document.getElementById('modalServiceStatus')?.value;
        const description = document.getElementById('modalServiceDescription')?.value;
        const features = document.getElementById('modalServiceFeatures')?.value;
        const imageFile = document.getElementById('serviceImage')?.files[0];

        // Validate required fields
        if (!name || !category || !status || !description) {
            this.showNotification('Please fill in all required fields (*)', 'warning');
            return false;
        }

        // Prepare form data
        formData.append('name', name);
        formData.append('category', category);
        formData.append('icon', icon);
        formData.append('status', status);
        formData.append('description', description);
        formData.append('features', features);

        if (imageFile) {
            formData.append('image', imageFile);
        }

        // Add CSRF token
        if (this.csrfToken) {
            formData.append('_token', this.csrfToken);
        }

        // If editing, add PUT method
        if (isEdit && serviceId) {
            formData.append('_method', 'PUT');
        }

        try {
            // Show loading state
            const submitBtn = document.getElementById('modalSubmit');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            }

            // Determine endpoint
            let endpoint;
            if (isEdit && serviceId) {
                endpoint = this.urls.update(serviceId);
            } else {
                endpoint = this.urls.store;
            }

            // Send request
            const response = await fetch(endpoint, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (response.ok) {
                this.showNotification(isEdit ? 'Service updated successfully!' : 'Service added successfully!', 'success');
                await this.loadServices();
                return true;
            } else {
                if (data.errors) {
                    let errorMessages = '';
                    for (const [field, messages] of Object.entries(data.errors)) {
                        errorMessages += `${field}: ${messages.join(', ')}\n`;
                    }
                    this.showNotification('Please fix the form errors', 'error');
                } else {
                    this.showNotification(data.message || 'An error occurred. Please try again.', 'error');
                }
                return false;
            }
        } catch (error) {
            console.error('Error:', error);
            this.showNotification('Network error. Please check your connection and try again.', 'error');
            return false;
        } finally {
            const submitBtn = document.getElementById('modalSubmit');
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Submit';
            }
        }
    }

    editService(id) {
        this.showServiceModal(id);
    }

    // ==================== UTILITY METHODS ====================
    showNotification(message, type = 'success') {
        // Use admin's notification system or create simple one
        if (this.admin && this.admin.showNotification) {
            this.admin.showNotification(message, type);
        } else {
            // Fallback to alert
            alert(`${type.toUpperCase()}: ${message}`);
        }
    }

    // ==================== BULK ACTIONS ====================
    async performBulkAction(action) {
        const selectedServices = Array.from(document.querySelectorAll('.service-checkbox:checked'))
            .map(checkbox => checkbox.value);

        if (selectedServices.length === 0) {
            this.showNotification('Please select at least one service', 'warning');
            return;
        }

        try {
            const response = await fetch(this.urls.bulkAction, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: action,
                    ids: selectedServices
                })
            });

            if (response.ok) {
                this.showNotification(`Services ${action} successfully!`, 'success');
                await this.loadServices();
            } else {
                const data = await response.json();
                this.showNotification(data.message || 'Failed to perform bulk action', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showNotification('Network error. Please try again.', 'error');
        }
    }

    exportServices(format = 'csv') {
        window.location.href = `${this.urls.export}?format=${format}`;
    }
}

// ==================== GLOBAL REGISTRATION ====================
// Register as global module
window.ServicesModule = ServicesModule;

// Auto-initialize when services page is active
document.addEventListener('DOMContentLoaded', function() {
    // Check if we're on the services page
    const servicesPage = document.getElementById('servicesPage');
    if (servicesPage && !servicesPage.classList.contains('hidden')) {
        // Initialize services module
        const admin = window.Admin || {
            csrfToken: document.querySelector('meta[name="csrf-token"]')?.content,
            showNotification: (msg, type) => alert(`${type}: ${msg}`)
        };
        
        window.servicesModule = new ServicesModule(admin);
        window.servicesModule.init();
    }
});