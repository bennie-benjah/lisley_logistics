<section id="shipmentsPage" class="admin-page hidden">
    <div class="table-container">
        <div class="table-header">
            <h3>Shipment Management</h3>
            <button class="btn" id="addShipmentBtn">Add New Shipment</button>
        </div>
        
        <div class="tabs">
            <div class="tab active" data-tab="all">All Shipments</div>
            <div class="tab" data-tab="pending">Pending</div>
            <div class="tab" data-tab="in_transit">In Transit</div>
            <div class="tab" data-tab="delivered">Delivered</div>
            <div class="tab" data-tab="delayed">Delayed</div>
        </div>
        
        <div class="tab-content active" id="tabAllShipments">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Tracking #</th>
                            <th>Customer</th>
                            <th>Receiver</th>
                            <th>Description</th>
                            <th>Weight (kg)</th>
                            <th>Status</th>
                            <th>Est. Delivery</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="shipmentsTableBody">
                        @foreach($shipments ?? [] as $shipment)
                        <tr data-id="{{ $shipment->id }}">
                            <td>
                                <strong>{{ $shipment->tracking_number }}</strong>
                                @if($shipment->is_delayed)
                                    <span class="badge badge-urgent">Delayed</span>
                                @endif
                            </td>
                            <td>
                                <div class="customer-cell">
                                    <div class="customer-name">{{ $shipment->user->name ?? 'N/A' }}</div>
                                    <div class="customer-email">{{ $shipment->user->email ?? '' }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="receiver-cell">
                                    <div class="receiver-name">{{ $shipment->receiver_name }}</div>
                                    <div class="receiver-email">{{ $shipment->receiver_email }}</div>
                                </div>
                            </td>
                            <td>{{ Str::limit($shipment->description, 50) }}</td>
                            <td>{{ $shipment->weight ? number_format($shipment->weight, 2) : 'N/A' }}</td>
                            <td>
                                <span class="status-badge status-{{ $shipment->status }}">
                                    <i class="{{ $shipment->icon }}"></i>
                                    {{ $shipment->status_text }}
                                </span>
                            </td>
                            <td>{{ $shipment->formatted_estimated_delivery }}</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-icon btn-view" onclick="viewShipment({{ $shipment->id }})" title="View">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn-icon btn-edit edit-btn" data-id="{{ $shipment->id }}" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.shipments.destroy', $shipment->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon btn-delete" onclick="return confirm('Delete this shipment?')" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        
                        @if(!isset($shipments) || count($shipments ?? []) === 0)
                        <tr>
                            <td colspan="8" class="text-center">No shipments found</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- Modal Structure -->
<div id="shipmentModal" class="modal hidden">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Add New Shipment</h3>
            <span class="close-modal">&times;</span>
        </div>
        <div class="modal-body">
            <form id="shipmentForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="id" id="shipmentId">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="tracking_number">Tracking Number *</label>
                        <input type="text" id="tracking_number" name="tracking_number" required 
                               placeholder="e.g., LL789456123">
                    </div>
                    
                    <div class="form-group">
                        <label for="user_id">Customer *</label>
                        <select id="user_id" name="user_id" required>
                            <option value="">Select Customer</option>
                            @foreach($customers ?? [] as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->email }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="sender_name">Sender Name</label>
                        <input type="text" id="sender_name" name="sender_name" 
                               placeholder="Sender's full name">
                    </div>
                    
                    <div class="form-group">
                        <label for="sender_email">Sender Email</label>
                        <input type="email" id="sender_email" name="sender_email" 
                               placeholder="sender@example.com">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="receiver_name">Receiver Name *</label>
                        <input type="text" id="receiver_name" name="receiver_name" required 
                               placeholder="Receiver's full name">
                    </div>
                    
                    <div class="form-group">
                        <label for="receiver_email">Receiver Email *</label>
                        <input type="email" id="receiver_email" name="receiver_email" required 
                               placeholder="receiver@example.com">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="description">Description *</label>
                        <textarea id="description" name="description" rows="2" required 
                                  placeholder="Package description"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="weight">Weight (kg)</label>
                        <input type="number" id="weight" name="weight" step="0.01" 
                               placeholder="e.g., 5.5">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="status">Status *</label>
                        <select id="status" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="in_transit">In Transit</option>
                            <option value="out_for_delivery">Out for Delivery</option>
                            <option value="delivered">Delivered</option>
                            <option value="delayed">Delayed</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="current_location">Current Location</label>
                        <input type="text" id="current_location" name="current_location" 
                               placeholder="e.g., Nairobi Distribution Center">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="estimated_delivery">Estimated Delivery</label>
                        <input type="datetime-local" id="estimated_delivery" name="estimated_delivery">
                    </div>
                    
                    <div class="form-group">
                        <label for="actual_delivery">Actual Delivery (if delivered)</label>
                        <input type="datetime-local" id="actual_delivery" name="actual_delivery">
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-outline cancel-btn">Cancel</button>
                    <button type="submit" class="btn" id="submitBtn">Create Shipment</button>
                </div>
            </form>
        </div>
    </div>
</div>

