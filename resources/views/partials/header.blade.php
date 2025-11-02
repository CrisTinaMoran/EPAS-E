<!-- Lobby Navbar - Now using same structure as top navbar -->
<header class="top-navbar lobby-navbar">
    <!-- Left side - Logo and Title -->
    <div class="navbar-left">
        <a class="navbar-brand" href="{{ route('lobby') }}">
            <div class="navbar-logo-container">
                <img src="{{ dynamic_asset('assets/EPAS-E.png') }}" alt="EPAS-E LMS">
                <div class="navbar-title-container">
                    <h2>EPAS-E</h2>
                    <p>Electronic Products Assembly and Servicing</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Right side actions - Rounded container -->
    <div class="navbar-right">
        <!-- Home Icon -->
        <div class="navbar-item">
            <a class="icon-button" href="{{ route('lobby') }}">
                <i class="fa-solid fa-house"></i>
            </a>
        </div>

        <!-- About Icon -->
        <div class="navbar-item">
            <a class="icon-button" href="{{ route('about') }}">
                <i class="fa-solid fa-circle-info"></i>
            </a>
        </div>

        <!-- Contact Icon -->
        <div class="navbar-item">
            <a class="icon-button" href="{{ route('contact') }}">
                <i class="fa-solid fa-phone"></i>
            </a>
        </div>

        <!-- Login Dropdown -->
        <div class="navbar-item">
            <button class="icon-button" id="login-dropdown-btn">
                <i class="fas fa-sign-in-alt"></i>
            </button>
            <div class="dropdown" id="login-dropdown">
                <div class="dropdown-content">
                    <a class="dropdown-item login-option admin" href="{{ route('private.login') }}">
                        <i class="fas fa-user-shield"></i>
                        <div>
                            <strong>Admin/Instructor Login</strong>
                            <small class="d-block">System Administration and Teaching Portal</small>
                        </div>
                    </a>
                    <a class="dropdown-item login-option student" href="{{ route('login') }}">
                        <i class="fas fa-user-graduate"></i>
                        <div>
                            <strong>Student Login</strong>
                            <small class="d-block">Learning Portal</small>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item login-option" href="{{ route('register') }}">
                        <i class="fas fa-user-plus"></i>
                        <div>
                            <strong>Student Registration</strong>
                            <small class="d-block">Create new account</small>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>


