<header class="header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <button class="btn menu-toggle d-lg-none">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="logo ms-3">
                    <img src="{{ asset('image/ecologix.png') }}" alt="ECOLOGIX" height="60">
                </div>
            </div>
            <div class="header-right">
                <div class="dropdown">
                    <a href="#" class="notification-bell" id="notificationDropdown" data-bs-toggle="dropdown">
                        <i class="fas fa-bell"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                        <div class="dropdown-header">Notifications</div>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">No new notifications</a>
                    </div>
                </div>
                <div class="dropdown ms-3">
                    <a href="#" class="user-profile d-flex align-items-center" id="userDropdown" data-bs-toggle="dropdown">
                        <img src="https://via.placeholder.com/32" alt="User" class="rounded-circle">
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="#">Profile</a>
                        <a class="dropdown-item" href="#">Settings</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>