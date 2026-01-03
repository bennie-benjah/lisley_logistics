<section id="products" class="page hidden">
    <div class="container">
        <h1 class="page-title">Logistics Products & Equipment</h1>
        <p class="page-subtitle">High-quality logistics products to support your shipping and warehousing operations.</p>

        <div class="product-page">
            <!-- Filter Section -->
            <div class="product-filters">
                <h3>Filter Products</h3>

                <!-- Filter form -->
                <form method="GET" action="{{ request()->url() }}" id="productFiltersForm">
                    <div class="filter-group">
                        <h4>Category</h4>
                        <div class="filter-options" id="categoryFilters">
                            @foreach($categories as $category)
                                <label>
                                    <input type="checkbox" 
                                           name="categories[]" 
                                           class="category-filter" 
                                           value="{{ $category['slug'] }}"
                                           {{ in_array($category['slug'], request('categories', [])) ? 'checked' : 'checked' }}>
                                    {{ $category['name'] }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="filter-group">
                        <h4>Price Range</h4>
                        <div class="filter-options">
                            <label>
                                <input type="radio" 
                                       name="price_range" 
                                       class="price-filter" 
                                       value="all" 
                                       {{ request('price_range', 'all') == 'all' ? 'checked' : '' }}> 
                                All Prices
                            </label>
                            <label>
                                <input type="radio" 
                                       name="price_range" 
                                       class="price-filter" 
                                       value="under50" 
                                       {{ request('price_range') == 'under50' ? 'checked' : '' }}> 
                                Under $50
                            </label>
                            <label>
                                <input type="radio" 
                                       name="price_range" 
                                       class="price-filter" 
                                       value="50-200" 
                                       {{ request('price_range') == '50-200' ? 'checked' : '' }}> 
                                $50 - $200
                            </label>
                            <label>
                                <input type="radio" 
                                       name="price_range" 
                                       class="price-filter" 
                                       value="over200" 
                                       {{ request('price_range') == 'over200' ? 'checked' : '' }}> 
                                Over $200
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn" id="applyFilters">Apply Filters</button>
                    <a href="{{ request()->url() }}" class="btn btn-outline" id="resetFilters">Reset Filters</a>
                </form>
            </div>

            <!-- Products Grid -->
            <div class="products-grid">
                @if($products->count() > 0)
                    @foreach($products as $product)
                        @php
                            // Get image URL
                            $imageUrl = $product->image_url ?? asset('images/default-product.jpg');
                            
                            // Get product features
                            $features = [];
                            if ($product->stock_quantity > 0) {
                                $features[] = "In Stock: {$product->stock_quantity} units";
                            }
                            if ($product->category) {
                                $features[] = "Category: {$product->category->name}";
                            }
                            $features[] = "Price: $" . number_format($product->price, 2);
                            
                            // Determine button text
                            $buttonText = 'Add to Cart';
                            if ($product->stock_quantity <= 0) {
                                $buttonText = 'Out of Stock';
                            }
                        @endphp

                        <div class="product-card">
                            <div class="product-img" style="background-image: url('{{ $imageUrl }}');">
                                @if($product->stock_quantity <= 0)
                                    <div class="product-badge out-of-stock">Out of Stock</div>
                                @elseif($product->price < 50)
                                    <div class="product-badge discount">Best Value</div>
                                @endif
                            </div>
                            <div class="product-content">
                                <h3>{{ $product->name }}</h3>
                                <p>{{ Str::limit($product->description, 120) }}</p>
                                
                                @if(!empty($features))
                                    <div class="product-features">
                                        <ul style="list-style: none; padding: 0; margin: 10px 0;">
                                            @foreach($features as $feature)
                                                <li style="padding: 3px 0; font-size: 0.9rem; color: #666;">
                                                    <i class="fas fa-check-circle" style="color: #28a745; margin-right: 8px;"></i>
                                                    {{ trim($feature) }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                
                                @auth
                                    @if(auth()->user()->hasVerifiedEmail())
                                        @if($product->stock_quantity > 0)
                                            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="add-to-cart-form">
                                                @csrf
                                                <button type="submit" class="btn" 
                                                        style="margin-top: 15px; width: 100%;">
                                                    <i class="fas fa-shopping-cart" style="margin-right: 8px;"></i>
                                                    {{ $buttonText }}
                                                </button>
                                            </form>
                                        @else
                                            <button class="btn" disabled style="margin-top: 15px; width: 100%;">
                                                <i class="fas fa-times-circle" style="margin-right: 8px;"></i>
                                                {{ $buttonText }}
                                            </button>
                                        @endif
                                    @else
                                        <!-- Logged in but not verified -->
                                        <button class="btn" 
                                                onclick="showNotification('Please verify your email to purchase products.', 'warning')"
                                                style="margin-top: 15px; width: 100%;">
                                            <i class="fas fa-envelope" style="margin-right: 8px;"></i>
                                            Verify Email Required
                                        </button>
                                    @endif
                                @else
                                    <!-- Not logged in - SPA navigation to auth page -->
                                    <button class="btn nav-link" 
                                            onclick="navigateToAuth()"
                                            style="margin-top: 15px; width: 100%;">
                                        <i class="fas fa-sign-in-alt" style="margin-right: 8px;"></i>
                                        Login to Purchase
                                    </button>
                                    
                                    {{-- <p style="font-size: 0.85rem; color: #666; margin-top: 5px; text-align: center;">
                                        <a href="#auth" class="nav-link" style="color: #007bff; text-decoration: underline;">
                                            Click here to login or register
                                        </a>
                                    </p> --}}
                                @endauth
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="no-products" style="grid-column: 1 / -1; text-align: center; padding: 60px 20px;">
                        <p style="font-size: 1.2rem; color: #666; margin-bottom: 20px;">
                            No products found matching your filters.
                        </p>
                        <a href="{{ request()->url() }}" class="btn">Reset Filters</a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Pagination -->
        @if(method_exists($products, 'hasPages') && $products->hasPages())
            <div class="pagination">
                {{ $products->withQueryString()->links() }}
            </div>
        @endif
    </div>
</section>

<!-- Login Required Modal (optional) -->
<div id="loginModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h3>Login Required</h3>
        <p>You need to login to add products to your cart.</p>
        
        <div style="margin: 20px 0;">
            <button class="btn nav-link" onclick="navigateToAuth(); closeModal('loginModal')" 
                    style="display: block; width: 100%; margin-bottom: 10px; text-align: center;">
                <i class="fas fa-sign-in-alt"></i> Go to Login Page
            </button>
            
            <p style="text-align: center; margin: 10px 0; color: #666;">or</p>
            
            <a href="{{ route('login') }}" class="btn btn-outline" 
               style="display: block; width: 100%; text-align: center;">
                <i class="fas fa-external-link-alt"></i> Open Separate Login Page
            </a>
        </div>
        
        <div class="modal-footer">
            <p style="text-align: center;">Don't have an account? 
                <a href="#auth" class="nav-link" onclick="closeModal('loginModal')" style="color: #007bff;">
                    Register here
                </a>
            </p>
        </div>
    </div>
</div>