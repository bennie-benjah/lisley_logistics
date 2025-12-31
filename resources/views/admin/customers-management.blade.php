<section id="customersPage" class="admin-page hidden">
    <div class="customers-container">
        <!-- Chart section on top -->
        <div class="chart-container">
            <div class="chart-header">
                <h3>Customer Growth</h3>
                <button class="btn btn-icon" id="refreshCustomersBtn" title="Refresh">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
            <div class="chart-wrapper">
                <canvas id="customerChart"></canvas>
            </div>
        </div>

        <!-- Customer table below the chart -->
        <div class="table-container">
            <div class="table-header">
                <div class="table-title">
                    <h3>Customer List</h3>
                    <div class="table-search">
                        <input type="text" id="searchCustomers" class="form-control" 
                               placeholder="Search customers...">
                    </div>
                </div>
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
                            <th>Quotes</th>
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