 <section id="customersPage" class="admin-page hidden">
                <div class="dashboard-row">
                    <div class="chart-container">
                        <div class="chart-header">
                            <h3>Customer Growth</h3>
                        </div>
                        <div class="chart-wrapper">
                            <canvas id="customerChart"></canvas>
                        </div>
                    </div>
                    
                    <div class="table-container">
                        <div class="table-header">
                            <h3>Customer List</h3>
                            <button class="btn" id="addCustomerBtn">Add Customer</button>
                        </div>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Customer ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Shipments</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="customersTableBody">
                                    <!-- Customers will be populated by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>