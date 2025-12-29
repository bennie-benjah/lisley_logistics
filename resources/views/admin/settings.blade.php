<section id="settingsPage" class="admin-page hidden">
                <div class="dashboard-row">
                    <div class="table-container">
                        <div class="table-header">
                            <h3>System Settings</h3>
                        </div>
                        
                        <div class="tabs">
                            <div class="tab active" data-tab="general">General</div>
                            <div class="tab" data-tab="notifications">Notifications</div>
                            <div class="tab" data-tab="users">Users</div>
                        </div>
                        
                        <div class="tab-content active" id="tabGeneral">
                            <form id="generalSettingsForm">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="companyName">Company Name</label>
                                        <input type="text" id="companyName" class="form-control" value="Lilsley Logistics">
                                    </div>
                                    <div class="form-group">
                                        <label for="contactEmail">Contact Email</label>
                                        <input type="email" id="contactEmail" class="form-control" value="admin@lilsleylogistics.com">
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="contactPhone">Contact Phone</label>
                                        <input type="text" id="contactPhone" class="form-control" value="+1 (800) 555-LOGISTICS">
                                    </div>
                                    <div class="form-group">
                                        <label for="timezone">Timezone</label>
                                        <select id="timezone" class="form-control">
                                            <option>UTC-05:00 Eastern Time</option>
                                            <option>UTC-08:00 Pacific Time</option>
                                            <option>UTC+00:00 GMT</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="companyAddress">Company Address</label>
                                    <textarea id="companyAddress" class="form-control" rows="3">123 Logistics Drive, Suite 500, Springfield, ST 12345</textarea>
                                </div>
                                
                                <div class="form-actions">
                                    <button type="submit" class="btn">Save Changes</button>
                                    <button type="button" class="btn btn-cancel">Cancel</button>
                                </div>
                            </form>
                        </div>
                        
                        <div class="tab-content" id="tabUsers">
                            <div class="table-responsive">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Username</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>admin</td>
                                            <td>Admin User</td>
                                            <td>admin@lilsleylogistics.com</td>
                                            <td>Administrator</td>
                                            <td><span class="status delivered">Active</span></td>
                                            <td>
                                                <button class="btn-action btn-edit">Edit</button>
                                                <button class="btn-action btn-delete">Delete</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>manager1</td>
                                            <td>Operations Manager</td>
                                            <td>manager@lilsleylogistics.com</td>
                                            <td>Manager</td>
                                            <td><span class="status delivered">Active</span></td>
                                            <td>
                                                <button class="btn-action btn-edit">Edit</button>
                                                <button class="btn-action btn-delete">Delete</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>staff1</td>
                                            <td>Support Staff</td>
                                            <td>staff@lilsleylogistics.com</td>
                                            <td>Staff</td>
                                            <td><span class="status pending">Inactive</span></td>
                                            <td>
                                                <button class="btn-action btn-edit">Edit</button>
                                                <button class="btn-action btn-delete">Delete</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>