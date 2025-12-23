<section id="servicesPage" class="admin-page hidden">
                <div class="table-container">
                    <div class="table-header">
                        <h3>Services Management</h3>
                        <button class="btn" id="addServiceBtn">Add New Service</button>
                    </div>
                    
                    <div class="tabs">
                        <div class="tab active" data-tab="all">All Services</div>
                        <div class="tab" data-tab="active">Active</div>
                        <div class="tab" data-tab="inactive">Inactive</div>
                    </div>
                    
                    <div class="tab-content active" id="tabAll">
                        <div class="management-grid" id="servicesGrid">
                            <!-- Services will be populated by JavaScript -->
                        </div>
                    </div>
                </div>
            </section>