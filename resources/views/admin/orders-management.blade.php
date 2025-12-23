<section id="ordersPage" class="admin-page hidden">
                <div class="table-container">
                    <div class="table-header">
                        <h3>Order Management</h3>
                        <div class="header-actions">
                            <input type="date" class="form-control" style="width: auto; padding: 8px;">
                            <button class="btn">Export</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Payment</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="ordersTableBody">
                                <!-- Orders will be populated by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>