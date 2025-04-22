<!-- Updated sidebar HTML -->
<div class="sidebar">
    <div class="sidebar-header">
        <button class="btn btn-success menu-toggle">
            <i class="fas fa-bars"></i>
        </button>
    </div>
    
    <div class="sidebar-menu">
        <ul class="nav flex-column">
            <li class="nav-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <a class="nav-link text-white" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-home"></i>
                    <span class="menu-text">Dashboard</span>
                </a>
            </li>
            <li class="nav-item {{ request()->is('admin/emissions*') ? 'active' : '' }}">
                <a class="nav-link text-white" href="{{ route('admin.emissions') }}">
                    <i class="fas fa-chart-line"></i>
                    <span class="menu-text">Kelola Emisi karbon</span>
                </a>
            </li>
            <li class="nav-item {{ request()->is('admin/credits*') ? 'active' : '' }}">
                <a class="nav-link text-white" href="{{ route('admin.credits') }}">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="menu-text">Pembelian Carbon credit</span>
                </a>
            </li>
            <li class="nav-item {{ request()->is('admin/notifications*') ? 'active' : '' }}">
                <a class="nav-link text-white" href="{{ route('admin.notifications') }}">
                    <i class="fas fa-bell"></i>
                    <span class="menu-text">Notification Center</span>
                </a>
            </li>
            <li class="nav-item {{ request()->is('admin/reports*') ? 'active' : '' }}">
                <a class="nav-link text-white" href="{{ route('admin.reports') }}">
                    <i class="fas fa-file-alt"></i>
                    <span class="menu-text">Report</span>
                </a>
            </li>
        </ul>
    </div>
</div>

