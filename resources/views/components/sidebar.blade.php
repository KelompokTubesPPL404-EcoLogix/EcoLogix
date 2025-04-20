<div class="sidebar bg-success">
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
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item {{ request()->is('admin/emissions*') ? 'active' : '' }}">
                <a class="nav-link text-white" href="{{ route('admin.emissions') }}">
                    <i class="fas fa-chart-line"></i>
                    <span>Kelola Emisi karbon</span>
                </a>
            </li>
            <li class="nav-item {{ request()->is('admin/credits*') ? 'active' : '' }}">
                <a class="nav-link text-white" href="{{ route('admin.credits') }}">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Pembelian Carbon credit</span>
                </a>
            </li>
            <li class="nav-item {{ request()->is('admin/notifications*') ? 'active' : '' }}">
                <a class="nav-link text-white" href="{{ route('admin.notifications') }}">
                    <i class="fas fa-bell"></i>
                    <span>Notification Center</span>
                </a>
            </li>
            <li class="nav-item {{ request()->is('admin/reports*') ? 'active' : '' }}">
                <a class="nav-link text-white" href="{{ route('admin.reports') }}">
                    <i class="fas fa-file-alt"></i>
                    <span>Report</span>
                </a>
            </li>
        </ul>
    </div>
</div>