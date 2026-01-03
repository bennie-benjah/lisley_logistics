<section id="services" class="page hidden">
    <div class="container">
        <h1 class="page-title">Our Logistics Services</h1>
        <p class="page-subtitle">Comprehensive logistics solutions tailored to your business requirements.</p>

        <div class="services-grid">
            @foreach($services as $service)
                @php
                    // Get image URL
                    $imageUrl = $service->image 
                        ? asset('storage/' . $service->image)
                        : $service->display_image;
                    
                    // Determine if this is a tracking service
                    $isTrackingService = str_contains(strtolower($service->name), 'tracking');
                    
                    // Get features as array
                    $features = $service->features_array;
                @endphp

                <div class="service-card">
                    <div class="service-img" style="background-image: url('{{ $imageUrl }}');">
                        <div class="service-icon-overlay">
                            {{-- <i class="{{ $service->icon }}"></i> --}}
                        </div>
                    </div>
                    <div class="service-content">
                        <h3>{{ $service->name }}</h3>
                        <p>{{ $service->description }}</p>
                        
                        @if(!empty($features))
                            <div class="service-features">
                                <ul style="list-style: none;">
                                    @foreach($features as $feature)
                                        <li>{{ trim($feature) }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <!-- Service Action Buttons -->
                        @if($isTrackingService)
                            <!-- Tracking services - available to everyone -->
                            <a href="#track" class="btn nav-link" 
                               style="margin-top: 15px; width: 100%; display: block; text-align: center;">
                                <i class="fas fa-map-marker-alt" style="margin-right: 8px;"></i>
                                Track Now
                            </a>
                        @else
                            <!-- Quote requests - authentication check -->
                            @auth
                                @if(auth()->user()->hasVerifiedEmail())
                                    <!-- Verified user can request quote -->
                                    <a href="#contact" class="btn nav-link request-quote-btn"
                                       data-service-id="{{ $service->id }}"
                                       data-service-name="{{ $service->name }}"
                                       style="margin-top: 15px; width: 100%; display: block; text-align: center;">
                                        <i class="fas fa-file-alt" style="margin-right: 8px;"></i>
                                        Request Quote
                                    </a>
                                @else
                                    <!-- Logged in but not verified -->
                                    <button class="btn" 
                                            onclick="showNotification('Please verify your email to request quotes.', 'warning')"
                                            style="margin-top: 15px; width: 100%;">
                                        <i class="fas fa-envelope" style="margin-right: 8px;"></i>
                                        Verify Email Required
                                    </button>
                                @endif
                            @else
                                <!-- Not logged in - show login button that navigates to #auth section -->
                                <button class="btn nav-link" 
                                        onclick="navigateToAuth()"
                                        style="margin-top: 15px; width: 100%;">
                                    <i class="fas fa-sign-in-alt" style="margin-right: 8px;"></i>
                                    Login to Request Quote
                                </button>
                                
                                {{-- <p style="font-size: 0.85rem; color: #666; margin-top: 5px; text-align: center;">
                                    <a href="#auth" class="nav-link" style="color: #007bff; text-decoration: underline;">
                                        Click here to login or register
                                    </a>
                                </p> --}}
                            @endauth
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>