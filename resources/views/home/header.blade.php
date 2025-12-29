<header>
    <div class="container header-container">
        <a href="{{ route('home') }}" class="logo">
            Lilsley <span>Logistics</span>
        </a>

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
                    <li>
                        <a href="{{ route('cart.index') }}" class="nav-link">
                            <i class="fas fa-shopping-cart"></i> <span id="cartCount">0</span>
                        </a>
                    </li>
                    @endunless
                    
                    <!-- Dashboard link based on role -->
                    <li>
                        @if(auth()->user()->hasRole('admin'))
                            <a href="{{ route('admin.dashboard') }}" class="nav-link">
                                <i class="fas fa-tachometer-alt"></i> Admin Panel
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="nav-link">
                                <i class="fas fa-user-circle"></i> My Account
                            </a>
                        @endif
                    </li>
                    
                    <!-- Logout -->
                    <li>
                        <form method="POST" action="{{ route('logout') }}" id="logout-form">
                            @csrf
                            <a href="{{ route('logout') }}" 
                               class="nav-link"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </form>
                    </li>
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