<section id="track" class="page hidden">
            <div class="container">
                <h1 class="page-title">Track Your Shipment</h1>
                <p class="page-subtitle">Enter your tracking number to get real-time updates on your shipment status.</p>

                <div class="track-container">
                    <div class="track-form">
                        <input type="text" id="trackingNumber" placeholder="Enter your tracking number (e.g., LL789456123)">
                        <button class="btn" id="trackBtn">Track Shipment</button>
                    </div>

                    <div class="tracking-results" id="trackingResults">
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
        </section>
