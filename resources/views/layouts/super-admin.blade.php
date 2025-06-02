@extends('layouts.appsuperadmin')

@section('title', 'Super Admin Dashboard')

@section('content')
<div class="main-content">
  <div class="container-fluid">
    @yield('main-content')
  </div>
</div>
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const toggleSidebarBtn = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.querySelector('.main-content');
    const sidebarToggleIcon = document.getElementById('sidebarToggleIcon');

    // Function to toggle sidebar
    function toggleSidebar() {
      sidebar.classList.toggle('collapsed');
      mainContent.classList.toggle('expanded');
      if (sidebar.classList.contains('collapsed')) {
        sidebarToggleIcon.classList.remove('bi-list');
        sidebarToggleIcon.classList.add('bi-x-lg');
      } else {
        sidebarToggleIcon.classList.remove('bi-x-lg');
        sidebarToggleIcon.classList.add('bi-list');
      }
    }

    // Event listener for the toggle button
    if (toggleSidebarBtn) {
      toggleSidebarBtn.addEventListener('click', toggleSidebar);
    }

    // Initial check for sidebar state on page load (optional)
    // You might want to add logic here to remember user's preference
  });
</script>
@endsection
