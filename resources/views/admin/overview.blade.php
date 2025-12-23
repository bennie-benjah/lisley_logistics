<section id="dashboardPage" class="admin-page">
                <!-- Stats Cards -->
                <div class="dashboard-cards">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-icon shipments">
                                <i class="fas fa-shipping-fast"></i>
                            </div>
                            <div class="card-trend positive">+12.5%</div>
                        </div>
                        <div class="card-content">
                            <h3>1,247</h3>
                            <p>Total Shipments</p>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <div class="card-icon revenue">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div class="card-trend positive">+8.2%</div>
                        </div>
                        <div class="card-content">
                            <h3>$42,580</h3>
                            <p>Monthly Revenue</p>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <div class="card-icon warehouse">
                                <i class="fas fa-warehouse"></i>
                            </div>
                            <div class="card-trend negative">-3.1%</div>
                        </div>
                        <div class="card-content">
                            <h3>2,458</h3>
                            <p>Warehouse Items</p>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <div class="card-icon customers">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="card-trend positive">+5.7%</div>
                        </div>
                        <div class="card-content">
                            <h3>348</h3>
                            <p>Active Customers</p>
                        </div>
                    </div>
                </div>
                
                <!-- Charts & Recent Shipments -->
                <div class="dashboard-row">
                    <div class="chart-container">
                        <div class="chart-header">
                            <h3>Shipment Volume (Last 30 Days)</h3>
                            <select class="form-control" style="width: auto; padding: 5px 10px;">
                                <option>Last 30 Days</option>
                                <option>Last 90 Days</option>
                                <option>This Year</option>
                            </select>
                        </div>
                        <div class="chart-wrapper">
                            <canvas id="shipmentChart"></canvas>
                        </div>
                    </div>
                    
                    <div class="table-container">
                        <div class="table-header">
                            <h3>Recent Shipments</h3>
                            <a href="#" class="btn">View All</a>
                        </div>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Tracking #</th>
                                        <th>Destination</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>LL789456123</td>
                                        <td>New York, USA</td>
                                        <td><span class="status in-transit">In Transit</span></td>
                                        <td>
                                            <button class="btn-action btn-view">View</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>LL789456124</td>
                                        <td>London, UK</td>
                                        <td><span class="status delivered">Delivered</span></td>
                                        <td>
                                            <button class="btn-action btn-view">View</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>LL789456125</td>
                                        <td>Tokyo, Japan</td>
                                        <td><span class="status pending">Pending</span></td>
                                        <td>
                                            <button class="btn-action btn-view">View</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>LL789456126</td>
                                        <td>Sydney, Australia</td>
                                        <td><span class="status in-transit">In Transit</span></td>
                                        <td>
                                            <button class="btn-action btn-view">View</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>LL789456127</td>
                                        <td>Berlin, Germany</td>
                                        <td><span class="status cancelled">Cancelled</span></td>
                                        <td>
                                            <button class="btn-action btn-view">View</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>