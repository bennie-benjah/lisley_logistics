<header>
    <div class="container header-container">
        <a href="{{ route('home') }}" class="logo">
            Lilsley <span>Logistics</span>
        </a>
        <button class="mobile-menu-btn" id="mobileMenuBtn">
            <i class="fas fa-bars"></i>
        </button>
        <nav id="mainNav">
            <ul>
                <!-- Public links -->
                <li><a href="#home" class="nav-link">Home</a></li>
                <li><a href="#about" class="nav-link">About</a></li>
                <li><a href="#services" class="nav-link">Services</a></li>
                <li><a href="#products" class="nav-link">Products</a></li>
                <li><a href="#track" class="nav-link">Track Shipment</a></li>

                @auth
                    <!-- Cart button for non-admin users -->
                    @unless(auth()->user()->hasRole('admin'))
                    <li class="cart-icon-item"> <!-- Add this class -->
                        <a href="{{ route('cart.index') }}" class="nav-link">
                            <i class="fas fa-shopping-cart"></i> <span id="cartCount">0</span>
                        </a>
                    </li>
                    @endunless

                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}" id="logout-form" class="logout-form">
                        @csrf
                        <button type="submit" class="btn logout-btn">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                @else
                    <!-- Login link for guests -->
                    <li>
                        <a href="#auth" class="nav-link" id="authLink">
                            <i class="fas fa-user"></i> Login
                        </a>
                    </li>
                @endauth
            </ul>
        </nav>
    </div>
</header>