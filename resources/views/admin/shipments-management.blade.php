<section id="shipmentsPage" class="admin-page hidden">
                <div class="table-container">
                    <div class="table-header">
                        <h3>Shipment Management</h3>
                        <button class="btn" id="addShipmentBtn">Add New Shipment</button>
                    </div>
                    
                    <div class="tabs">
                        <div class="tab active" data-tab="all">All Shipments</div>
                        <div class="tab" data-tab="pending">Pending</div>
                        <div class="tab" data-tab="in-transit">In Transit</div>
                        <div class="tab" data-tab="delivered">Delivered</div>
                    </div>
                    
                    <div class="tab-content active" id="tabAllShipments">
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Tracking #</th>
                                        <th>Customer</th>
                                        <th>Origin</th>
                                        <th>Destination</th>
                                        <th>Ship Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="shipmentsTableBody">
                                    <!-- Shipments will be populated by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>