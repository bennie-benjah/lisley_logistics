<section id="auth" class="page hidden">
    <div class="container">
        <h1 class="page-title">Account Access</h1>
        <p class="page-subtitle">Login or create an account to manage your logistics needs.</p>

        <div class="auth-container">
            <div class="auth-form-container">

                <!-- Main tabs for login/signup -->
                <div class="auth-tabs">
                    <button class="auth-tab active" id="loginTab">Login</button>
                    <button class="auth-tab" id="signupTab">Sign Up</button>
                </div>

                {{-- LOGIN FORM --}}
                <div class="auth-form active" id="loginForm">
                    @if(session('status'))
                        <div class="auth-message">{{ session('status') }}</div>
                    @endif

                    {{-- <div class="user-type-selector" id="loginUserType">
                        <button type="button" class="user-type-option active" data-user-type="customer">Customer</button>
                        <button type="button" class="user-type-option" data-user-type="staff">Staff</button>
                    </div> --}}

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group">
                            <label for="loginEmail">Email</label>
                            <input type="email" id="loginEmail" name="email" class="form-control" required autofocus>
                        </div>

                        <div class="form-group">
                            <label for="loginPassword">Password</label>
                            <div class="password-container">
                                <input type="password" id="loginPassword" name="password" class="form-control" required>
                                <button type="button" class="password-toggle" id="loginPasswordToggle">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="login-options">
                            <div class="form-check">
                                <input type="checkbox" name="remember" id="rememberMe">
                                <label for="rememberMe">Remember me</label>
                            </div>
                            <a href="#forgotPassword" id="forgotPasswordLink">Forgot Password?</a>
                        </div>

                        <button type="submit" class="btn" style="width:100%;">Login</button>
                    </form>
                </div>

                {{-- REGISTER FORM --}}
                <div class="auth-form" id="signupForm">
                    {{-- <div class="user-type-selector" id="signupUserType">
                        <button type="button" class="user-type-option active" data-user-type="customer">Customer</button>
                        <button type="button" class="user-type-option" data-user-type="staff">Staff</button>
                    </div> --}}

                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="form-row">
                            <div class="form-group">
                                <label for="signupFirstName">First Name</label>
                                <input type="text" id="signupFirstName" name="name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="signupLastName">Last Name</label>
                                <input type="text" id="signupLastName" name="last_name" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="signupEmail">Email</label>
                            <input type="email" id="signupEmail" name="email" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="signupPhone">Phone</label>
                            <input type="tel" id="signupPhone" name="phone" class="form-control" required>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="signupPassword">Password</label>
                                <div class="password-container">
                                    <input type="password" id="signupPassword" name="password" class="form-control" required>
                                    <button type="button" class="password-toggle" id="signupPasswordToggle">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="signupConfirmPassword">Confirm Password</label>
                                <div class="password-container">
                                    <input type="password" id="signupConfirmPassword" name="password_confirmation" class="form-control" required>
                                    <button type="button" class="password-toggle" id="signupConfirmPasswordToggle">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn" style="width:100%; margin-top:20px;">Register</button>
                    </form>
                </div>

                {{-- FORGOT PASSWORD --}}
                <div class="auth-form" id="forgotPasswordForm">
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="form-group">
                            <label for="forgotEmail">Email</label>
                            <input type="email" name="email" id="forgotEmail" class="form-control" required>
                        </div>
                        <button type="submit" class="btn" style="width:100%;">Send Password Reset Link</button>
                        <div class="form-footer">
                            <a href="#login" id="backToLoginFromForgot">Back to Login</a>
                        </div>
                    </form>
                </div>

                {{-- RESET PASSWORD --}}
                <div class="auth-form" id="resetPasswordForm">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token ?? '' }}">
                        <div class="form-group">
                            <label for="resetEmail">Email</label>
                            <input type="email" name="email" id="resetEmail" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="resetPassword">New Password</label>
                            <input type="password" name="password" id="resetPassword" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="resetPasswordConfirmation">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="resetPasswordConfirmation" class="form-control" required>
                        </div>

                        <button type="submit" class="btn" style="width:100%;">Reset Password</button>
                        <div class="form-footer">
                            <a href="#login" id="backToLoginFromReset">Back to Login</a>
                        </div>
                    </form>
                </div>

                {{-- CONFIRM PASSWORD --}}
                <div class="auth-form" id="confirmPasswordForm">
                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf
                        <div class="form-group">
                            <label for="confirmPassword">Password</label>
                            <input type="password" name="password" id="confirmPassword" class="form-control" required>
                        </div>
                        <button type="submit" class="btn" style="width:100%;">Confirm Password</button>
                        <div class="form-footer">
                            <a href="#login" id="backToLoginFromConfirm">Back to Login</a>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</section>