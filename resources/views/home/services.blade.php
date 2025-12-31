
<section id="services" class="page hidden">
    <div class="container">
        <h1 class="page-title">Our Logistics Services</h1>
        <p class="page-subtitle">Comprehensive logistics solutions tailored to your business requirements.</p>

        <div class="services-grid">
            @foreach($services as $service)
                @php
                    // Get image URL - use storage URL if image exists
                    $imageUrl = $service->image 
    ? asset('storage/' . $service->image)
    : $service->display_image; // CORRECT: Accessing as property
                    // Determine button text and link
                    $buttonText = 'Request Quote';
                    $buttonLink = '#contact';
                    
                    if (str_contains(strtolower($service->name), 'tracking')) {
                        $buttonText = 'Track Now';
                        $buttonLink = '#track';
                    }
                    
                    // Get features as array
                    $features = $service->features_array;
                @endphp

                <div class="service-card">
                    <div class="service-img" style="background-image: url('{{ $imageUrl }}');">
                        <!-- Optional: Service icon overlay -->
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
                        
                        <a href="{{ $buttonLink }}" class="btn nav-link" 
                           data-service-id="{{ $service->id }}"
                           data-service-name="{{ $service->name }}"
                           style="margin-top: 15px;">
                            {{ $buttonText }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>