@extends('layouts.app')

@section('title', 'Manager Dashboard')

@section('sidebar')
<div class="p-3">
  <div class="d-flex align-items-center mb-4 mt-2">
    <div class="eco-icon-bg rounded-circle p-2 me-3 d-flex align-items-center justify-content-center">
      <i class="bi bi-briefcase-fill text-white fs-5"></i>
    </div>
    <div>
      <h5 class="text-white mb-0 fw-bold">Manager Panel</h5>
      <small class="text-white-50">{{ Auth::user()->perusahaan->nama_perusahaan ?? 'EcoLogix' }}</small>
    </div>
  </div>
  
  <div class="sidebar-menu">
    <div class="sidebar-heading small text-uppercase text-white-50 px-3 py-2">Menu Utama</div>
    
    <ul class="nav flex-column gap-1">
      <li class="nav-item">
        <a href="{{ route('manager.dashboard') }}" class="eco-nav-link rounded-3 py-2 px-3 d-flex align-items-center {{ request()->routeIs('manager.dashboard') ? 'active' : '' }}">
          <div class="icon-container me-3">
            <i class="bi bi-speedometer2"></i>
          </div>
          <span>Dashboard</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="eco-nav-link rounded-3 py-2 px-3 d-flex align-items-center {{ request()->routeIs('manager.admin') ? 'active' : '' }}" href="{{ route('manager.admin.index') }}">
          <div class="icon-container me-3">
            <i class="bi bi-people"></i>
          </div>
          <span>Manajemen Admin</span>
        </a>
      </li>
    </ul>
    
    <div class="sidebar-heading small text-uppercase text-white-50 px-3 py-2 mt-4">Karbon & Emisi</div>
    
    <ul class="nav flex-column gap-1">
      <li class="nav-item">
        <a class="eco-nav-link rounded-3 py-2 px-3 d-flex align-items-center {{ request()->routeIs('manager.leaderboard') ? 'active' : '' }}" href="{{ route('manager.leaderboard') }}">
          <div class="icon-container me-3">
            <i class="bi bi-trophy"></i>
          </div>
          <span>Leaderboard Perusahaan</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="eco-nav-link rounded-3 py-2 px-3 d-flex align-items-center {{ request()->routeIs('manager.kompensasi*') ? 'active' : '' }}" href="{{ route('manager.kompensasi.index') }}">
          <div class="icon-container me-3">
            <i class="bi bi-arrow-left-right"></i>
          </div>
          <span>Kompensasi Emisi</span>
        </a>
      </li>
      {{-- <li class="nav-item">
        <a class="eco-nav-link rounded-3 py-2 px-3 d-flex align-items-center {{ request()->routeIs('manager.carbon_credit*') ? 'active' : '' }}" href="{{ route('manager.pembelian-carbon-credit.index') }}">
          <div class="icon-container me-3">
            <i class="bi bi-currency-exchange"></i>
          </div>
          <span>Carbon Credit</span>
        </a>
      </li> --}}
    </ul>
    
    <div class="sidebar-heading small text-uppercase text-white-50 px-3 py-2 mt-4">Konfigurasi</div>
    
    <ul class="nav flex-column gap-1">
      <li class="nav-item">
        <a class="eco-nav-link rounded-3 py-2 px-3 d-flex align-items-center {{ request()->routeIs('manager.faktor-emisi*') ? 'active' : '' }}" href="{{ route('manager.faktor-emisi.index') }}">
          <div class="icon-container me-3">
            <i class="bi bi-rulers"></i>
          </div>
          <span>Faktor Emisi</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="eco-nav-link rounded-3 py-2 px-3 d-flex align-items-center {{ request()->routeIs('manager.penyedia-carbon-credit*') ? 'active' : '' }}" href="{{ route('manager.penyedia-carbon-credit.index') }}">
          <div class="icon-container me-3">
            <i class="bi bi-building"></i>
          </div>
          <span>Penyedia Carbon Credit</span>
        </a>
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
