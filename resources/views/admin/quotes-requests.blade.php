<section id="quotesPage" class="admin-page hidden">
    <div class="table-container">
        <div class="table-header">
            <h3>Quote Requests</h3>
            <div class="header-actions">
                <button class="btn btn-icon" id="refreshQuotesBtn" title="Refresh">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
        </div>

        <div class="tabs">
            <div class="tab active" data-tab="all">All Requests</div>
            <div class="tab" data-tab="new">New</div>
            <div class="tab" data-tab="reviewed">Reviewed</div>
            <div class="tab" data-tab="quoted">Quoted</div>
            <div class="tab" data-tab="closed">Closed</div>
        </div>

        <div class="table-filters">
            <div class="filter-group">
                <select id="filterService" class="form-control">
                    <option value="">All Services</option>
                    <option value="freight">Freight</option>
                    <option value="storage">Storage</option>
                    <option value="delivery">Delivery</option>
                    <option value="management">Management</option>
                    <option value="international">International</option>
                    <option value="technology">Technology</option>
                </select>
            </div>
            <div class="filter-group">
                <input type="text" id="searchQuotes" class="form-control" placeholder="Search requests...">
            </div>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Service</th>
                        <th>Details</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="quotesGrid">
                    <!-- Quotes will be loaded here -->
                </tbody>
            </table>
        </div>

        <div class="table-footer">
            <div class="pagination-info" id="quotesCount">
                Showing 0 requests
            </div>
        </div>
    </div>
</section>
