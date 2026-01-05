// public/js/admin/shipments.js

class ShipmentsManager {
    constructor() {
        this.baseUrl = '/admin/shipments';
        this.init();
    }

    init() {
        console.log('Shipments Manager initialized');
        this.bindEvents();
        this.initializeDateTimeInputs();
    }

    bindEvents() {
        // Add New Shipment Button
        const addBtn = document.getElementById('addShipmentBtn');
        if (addBtn) {
            addBtn.addEventListener('click', () => this.openModal('add'));
        }
        
        // Edit buttons (event delegation)
        document.addEventListener('click', (e) => {
            if (e.target.closest('.edit-btn') || e.target.closest('.btn-edit')) {
                e.preventDefault();
                const button = e.target.closest('.edit-btn, .btn-edit');
                const shipmentId = button.dataset.id;
                if (shipmentId) {
                    this.editShipment(shipmentId);
                }
            }
        });
        
        // Form submission
        const form = document.getElementById('shipmentForm');
        if (form) {
            form.addEventListener('submit', (e) => this.handleFormSubmit(e));
        }
        
        // Modal close buttons
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('close-modal') || 
                e.target.classList.contains('cancel-btn') ||
                e.target.classList.contains('modal')) {
                this.closeModal();
            }
        });
        
        // Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') this.closeModal();
        });
        
        // Tab switching
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', () => this.switchTab(tab.dataset.tab));
        });
    }

    initializeDateTimeInputs() {
        // Set default datetime to now + 3 days for estimated delivery
        const now = new Date();
        const threeDaysLater = new Date(now.getTime() + (3 * 24 * 60 * 60 * 1000));
        
        const estimatedDeliveryInput = document.getElementById('estimated_delivery');
        if (estimatedDeliveryInput) {
            estimatedDeliveryInput.value = this.formatDateTimeLocal(threeDaysLater);
        }
    }

    formatDateTimeLocal(date) {
        const pad = (num) => num.toString().padStart(2, '0');
        return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
    }

    openModal(action = 'add', shipmentData = null) {
        const modal = document.getElementById('shipmentModal');
        const modalTitle = document.getElementById('modalTitle');
        const submitBtn = document.getElementById('submitBtn');
        
        modalTitle.textContent = action === 'add' ? 'Add New Shipment' : 'Edit Shipment';
        submitBtn.textContent = action === 'add' ? 'Create Shipment' : 'Update Shipment';
        
        if (action === 'add') {
            this.resetForm();
        } else if (shipmentData) {
            this.populateForm(shipmentData);
        }
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    async editShipment(shipmentId) {
        try {
            const response = await fetch(`${this.baseUrl}/${shipmentId}/edit`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (!response.ok) throw new Error('Failed to load shipment');
            
            const shipment = await response.json();
            this.openModal('edit', shipment);
            
        } catch (error) {
            console.error('Error loading shipment:', error);
            // alert('Failed to load shipment data');
        }
    }

    populateForm(shipment) {
        // Fill form fields with shipment data
        document.getElementById('shipmentId').value = shipment.id;
        document.getElementById('tracking_number').value = shipment.tracking_number;
        document.getElementById('user_id').value = shipment.user_id;
        document.getElementById('sender_name').value = shipment.sender_name || '';
        document.getElementById('sender_email').value = shipment.sender_email || '';
        document.getElementById('receiver_name').value = shipment.receiver_name;
        document.getElementById('receiver_email').value = shipment.receiver_email;
        document.getElementById('description').value = shipment.description;
        document.getElementById('weight').value = shipment.weight || '';
        document.getElementById('status').value = shipment.status;
        document.getElementById('current_location').value = shipment.current_location || '';
        
        // Format dates for datetime-local inputs
        if (shipment.estimated_delivery) {
            const estDate = new Date(shipment.estimated_delivery);
            document.getElementById('estimated_delivery').value = this.formatDateTimeLocal(estDate);
        }
        
        if (shipment.actual_delivery) {
            const actualDate = new Date(shipment.actual_delivery);
            document.getElementById('actual_delivery').value = this.formatDateTimeLocal(actualDate);
        }
        
        document.getElementById('formMethod').value = 'PUT';
    }

    resetForm() {
        const form = document.getElementById('shipmentForm');
        if (form) {
            form.reset();
            document.getElementById('shipmentId').value = '';
            document.getElementById('formMethod').value = 'POST';
            this.initializeDateTimeInputs();
        }
    }

    async handleFormSubmit(e) {
        e.preventDefault();
        
        const form = e.target;
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        const method = data._method;
        const shipmentId = data.id;
        
        // Convert datetime-local to proper format for Laravel
        if (data.estimated_delivery) {
            data.estimated_delivery = data.estimated_delivery.replace('T', ' ');
        }
        if (data.actual_delivery) {
            data.actual_delivery = data.actual_delivery.replace('T', ' ');
        }
        
        let url = this.baseUrl;
        if (method === 'PUT' && shipmentId) {
            url = `${this.baseUrl}/${shipmentId}`;
        }
        
        const submitBtn = document.getElementById('submitBtn');
        const originalText = submitBtn.textContent;
        
        // Show loading state
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        submitBtn.disabled = true;
        
        try {
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (response.ok) {
                alert(result.message || 'Shipment saved successfully!');
                this.closeModal();
                window.location.reload(); // Reload to show updated data
            } else {
                // Show validation errors
                if (result.errors) {
                    const errorMessages = Object.values(result.errors).flat().join('\n');
                    alert(`Please fix the following errors:\n\n${errorMessages}`);
                } else {
                    alert(result.message || 'Failed to save shipment');
                }
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            }
            
        } catch (error) {
            console.error('Error:', error);
            alert('Network error. Please try again.');
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        }
    }

    switchTab(tabName) {
        // Update active tab
        document.querySelectorAll('.tab').forEach(tab => {
            tab.classList.remove('active');
            if (tab.dataset.tab === tabName) {
                tab.classList.add('active');
            }
        });
        
        // Filter table rows based on status
        this.filterShipmentsByStatus(tabName);
    }

    filterShipmentsByStatus(status) {
        const rows = document.querySelectorAll('#shipmentsTableBody tr[data-id]');
        const noResultsRow = document.querySelector('#shipmentsTableBody tr:not([data-id])');
        
        let visibleCount = 0;
        
        rows.forEach(row => {
            const statusBadge = row.querySelector('.status-badge');
            if (!statusBadge) return;
            
            const rowStatus = Array.from(statusBadge.classList)
                .find(cls => cls.startsWith('status-'))
                ?.replace('status-', '');
            
            if (status === 'all' || rowStatus === status) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Show/hide no results message
        if (noResultsRow) {
            if (visibleCount === 0) {
                noResultsRow.style.display = '';
                noResultsRow.querySelector('td').textContent = `No ${status.replace('_', ' ')} shipments found`;
            } else {
                noResultsRow.style.display = 'none';
            }
        }
    }

    closeModal() {
        const modal = document.getElementById('shipmentModal');
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Global functions
    viewShipment(shipmentId) {
        window.location.href = `${this.baseUrl}/${shipmentId}`;
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.shipmentsManager = new ShipmentsManager();
});

// Global functions for inline onclick attributes
window.viewShipment = function(shipmentId) {
    if (window.shipmentsManager) {
        window.shipmentsManager.viewShipment(shipmentId);
    } else {
        window.location.href = `/admin/shipments/${shipmentId}`;
    }
};