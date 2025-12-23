 <section id="productsPage" class="admin-page hidden">
                <div class="table-container">
                    <div class="table-header">
                        <h3>Products Management</h3>
                        <button class="btn" id="addProductBtn">Add New Product</button>
                    </div>
                    
                    <div class="tabs">
                        <div class="tab active" data-tab="all">All Products</div>
                        <div class="tab" data-tab="packaging">Packaging</div>
                        <div class="tab" data-tab="equipment">Equipment</div>
                        <div class="tab" data-tab="tracking">Tracking</div>
                        <div class="tab" data-tab="software">Software</div>
                    </div>
                    
                    <div class="tab-content active" id="tabAllProducts">
                        <div class="management-grid" id="productsGrid">
                            <!-- Products will be populated by JavaScript -->
                        </div>
                    </div>
                </div>
            </section>