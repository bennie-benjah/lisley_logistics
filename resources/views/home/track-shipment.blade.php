<section id="track" class="page hidden">
    <div class="container">
        <h1 class="page-title">Track Your Shipment</h1>
        <p class="page-subtitle">Enter your tracking number to get real-time updates on your shipment status.</p>

        <!-- Authentication Check -->
        @auth
            <!-- User is logged in - show tracking functionality -->
            <div class="track-container">
                <div class="track-form">
                    <form method="POST" action="{{ route('shipments.track.result') }}" id="trackingForm">
                        @csrf
                        <input type="text" 
                               id="trackingNumber" 
                               name="tracking_number"
                               placeholder="Enter your tracking number (e.g., LL789456123)" 
                               required>
                        <button type="submit" class="btn" id="trackBtn">Track Shipment</button>
                    </form>
                </div>

                <!-- Tracking Results Section -->
                <div class="tracking-results" id="trackingResults">
                    <!-- Results will be populated here by JavaScript or server response -->
                    <div class="results-placeholder">
                        <p>Enter a tracking number above to see shipment details.</p>
                    </div>
                    
                    <!-- Example Static Results (hidden by default, shown via JS on form submission) -->
                    <div class="example-results" style="display: none;">
                        <h3>Shipment Status: <span id="statusText">In Transit</span></h3>
                        <p>Tracking Number: <strong id="displayTrackingNumber">LL789456123</strong></p>
                        <p>Estimated Delivery: <strong id="deliveryDate">December 20, 2023</strong></p>
                        <p>Current Location: <strong id="currentLocation">Los Angeles Distribution Center</strong></p>

                        <h4>Tracking Timeline</h4>
                        <div class="tracking-timeline">
                            <div class="timeline-item completed">
                                <h5>Order Processed</h5>
                                <p>December 10, 2023 - 09:15 AM</p>
                                <p>Shipment information received</p>
                            </div>

                            <div class="timeline-item completed">
                                <h5>Picked Up</h5>
                                <p>December 12, 2023 - 02:30 PM</p>
                                <p>Package collected from sender</p>
                            </div>

                            <div class="timeline-item completed">
                                <h5>Departed Facility</h5>
                                <p>December 14, 2023 - 10:45 AM</p>
                                <p>Left New York distribution center</p>
                            </div>

                            <div class="timeline-item">
                                <h5>In Transit</h5>
                                <p>December 15, 2023 - 08:20 AM</p>
                                <p>Arrived at Los Angeles distribution center</p>
                            </div>

                            <div class="timeline-item">
                                <h5>Out for Delivery</h5>
                                <p>Expected December 20, 2023</p>
                                <p>Package will be delivered today</p>
                            </div>

                            <div class="timeline-item">
                                <h5>Delivered</h5>
                                <p>Expected December 20, 2023</p>
                                <p>Package will be delivered to recipient</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @else
            <!-- User is NOT logged in - show login prompt -->
            <div class="auth-required-message">
                <div class="auth-icon">
                    <i class="fas fa-lock" style="font-size: 3rem; color: #007bff; margin-bottom: 20px;"></i>
                </div>
                <h3>Login Required to Track Shipments</h3>
                <p class="auth-description">
                    To track your shipments and view shipment history, please login to your account.
                </p>
                
                <div class="auth-actions">
                    <!-- Option 1: Navigate to your SPA auth page -->
                    <button class="btn nav-link" onclick="navigateToAuth()" style="margin: 10px; padding: 12px 30px;">
                        <i class="fas fa-sign-in-alt" style="margin-right: 8px;"></i>
                        Go to Login Page
                    </button>
                    
                    <!-- Option 2: Show a login modal (alternative approach) -->
                    <button class="btn btn-outline show-login-modal" onclick="navigateToAuth()" style="margin: 10px; padding: 12px 30px;">
                        <i class="fas fa-user-plus" style="margin-right: 8px;"></i>
                        Register New Account
                    </button>
                </div>
                
                <div class="auth-benefits">
                    <h4>Benefits of Logging In:</h4>
                    <ul>
                        <li><i class="fas fa-check" style="color: #28a745;"></i> Track all your shipments in one place</li>
                        <li><i class="fas fa-check" style="color: #28a745;"></i> View detailed shipment history</li>
                        <li><i class="fas fa-check" style="color: #28a745;"></i> Get real-time status updates</li>
                        <li><i class="fas fa-check" style="color: #28a745;"></i> Access to your shipment documents</li>
                        <li><i class="fas fa-check" style="color: #28a745;"></i> Manage multiple tracking numbers</li>
                    </ul>
                </div>
            </div>

        @endauth
    </div>
</section>