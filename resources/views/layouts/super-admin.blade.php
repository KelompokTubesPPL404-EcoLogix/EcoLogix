@extends('layouts.app')

@section('title', 'Super Admin Dashboard')

@section('sidebar')
<div class="p-3">
  <div class="d-flex align-items-center mb-4 mt-2">
    <div class="eco-icon-bg rounded-circle p-2 me-3 d-flex align-items-center justify-content-center">
      <i class="bi bi-shield-check text-white fs-5"></i>
    </div>
    <div>
      <h5 class="text-white mb-0 fw-bold">Super Admin</h5>
      <small class="text-white-50">EcoLogix Platform</small>
    </div>
  </div>
  
  <div class="sidebar-menu">
    <div class="sidebar-heading small text-uppercase text-white-50 px-3 py-2">Overview</div>
    
    <ul class="nav flex-column gap-1">
      <li class="nav-item">
        <a href="{{ route('superadmin.dashboard') }}" class="eco-nav-link rounded-3 py-2 px-3 d-flex align-items-center {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
          <div class="icon-container me-3">
            <i class="bi bi-speedometer2"></i>
          </div>
          <span>Dashboard</span>
        </a>
      </li>
    </ul>
    
    <div class="sidebar-heading small text-uppercase text-white-50 px-3 py-2 mt-4">Manajemen</div>
    
    <ul class="nav flex-column gap-1">
      <li class="nav-item">
        <a class="eco-nav-link rounded-3 py-2 px-3 d-flex align-items-center {{ request()->routeIs('superadmin.perusahaan*') ? 'active' : '' }}" href="{{ route('superadmin.perusahaan.index') }}">
          <div class="icon-container me-3">
            <i class="bi bi-building"></i>
          </div>
          <span>Perusahaan</span>
          <span class="ms-auto badge bg-success rounded-pill">{{ \App\Models\Perusahaan::count() }}</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="eco-nav-link rounded-3 py-2 px-3 d-flex align-items-center {{ request()->routeIs('superadmin.manager*') ? 'active' : '' }}" href="{{ route('superadmin.manager.index') }}">
          <div class="icon-container me-3">
            <i class="bi bi-person-badge"></i>
          </div>
          <span>Manajer</span>
          <span class="ms-auto badge bg-info rounded-pill">{{ \App\Models\User::where('role', 'manager')->count() }}</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="eco-nav-link rounded-3 py-2 px-3 d-flex align-items-center {{ request()->routeIs('superadmin.users*') ? 'active' : '' }}" href="#">
          <div class="icon-container me-3">
            <i class="bi bi-people"></i>
          </div>
          <span>Pengguna</span>
        </a>
      </li>
    </ul>
    
    <div class="sidebar-heading small text-uppercase text-white-50 px-3 py-2 mt-4">Analisis</div>
    
    <ul class="nav flex-column gap-1">
      <li class="nav-item">
        <a class="eco-nav-link rounded-3 py-2 px-3 d-flex align-items-center {{ request()->routeIs('superadmin.reports*') ? 'active' : '' }}" href="#">
          <div class="icon-container me-3">
            <i class="bi bi-bar-chart"></i>
          </div>
          <span>Laporan Emisi</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="eco-nav-link rounded-3 py-2 px-3 d-flex align-items-center {{ request()->routeIs('superadmin.analytics*') ? 'active' : '' }}" href="#">
          <div class="icon-container me-3">
            <i class="bi bi-graph-up"></i>
          </div>
          <span>Analisis Tren</span>
        </a>
      </li>
    </ul>
    
    <div class="sidebar-heading small text-uppercase text-white-50 px-3 py-2 mt-4">Pengaturan</div>
    
    <ul class="nav flex-column gap-1">
      <li class="nav-item">
        <a class="eco-nav-link rounded-3 py-2 px-3 d-flex align-items-center {{ request()->routeIs('superadmin.settings*') ? 'active' : '' }}" href="#">
          <div class="icon-container me-3">
            <i class="bi bi-gear"></i>
          </div>
          <span>Pengaturan Sistem</span>
        </a>
      </li>
      <li class="nav-item mt-3">
        <div class="px-3">
          <div class="card bg-success bg-opacity-25 border-0 rounded-3 p-3">
            <div class="d-flex align-items-center mb-2">
              <i class="bi bi-lightbulb-fill text-warning me-2"></i>
              <span class="text-white small fw-bold">Tips</span>
            </div>
            <p class="text-white-50 small mb-0">Gunakan dashboard untuk memantau emisi karbon dari seluruh perusahaan.</p>
          </div>
        </div>
      </li>
    </ul>
  </div>
</div>

<style>
  .sidebar-menu {
    position: relative;
    z-index: 2;
  }
  
  .sidebar-heading {
    font-size: 0.7rem;
    letter-spacing: 1px;
    font-weight: 600;
    margin-bottom: 0.5rem;
  }
  
  .eco-icon-bg {
    background-color: rgba(40, 167, 69, 0.8);
    width: 38px;
    height: 38px;
  }
  
  .eco-nav-link {
    color: white;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
  }
  
  .eco-nav-link .icon-container {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 22px;
  }
  
  .eco-nav-link:hover {
    background-color: rgba(255, 255, 255, 0.15);
    color: white;
    transform: translateX(5px);
  }
  
  .eco-nav-link.active {
    background-color: #28a745;
    color: white;
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
  }
  
  .eco-nav-link.active::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 4px;
    background-color: #fff;
  }
</style>
@endsection
