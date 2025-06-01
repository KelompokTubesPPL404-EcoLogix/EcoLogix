@extends('layouts.app')

@section('title', 'Staff Dashboard')

@section('sidebar')
<div class="p-3">
  <div class="d-flex align-items-center mb-4 mt-2">
    <div class="eco-icon-bg rounded-circle p-2 me-3 d-flex align-items-center justify-content-center">
      <i class="bi bi-person-fill text-white fs-5"></i>
    </div>
    <div>
      <h5 class="text-white mb-0 fw-bold">Staff Panel</h5>
      <small class="text-white-50">{{ Auth::user()->perusahaan->nama_perusahaan ?? 'EcoLogix' }}</small>
    </div>
  </div>
  
  <div class="sidebar-menu">
    <div class="sidebar-heading small text-uppercase text-white-50 px-3 py-2">Menu Utama</div>
    
    <ul class="nav flex-column gap-1">
      <li class="nav-item">
        <a href="{{ route('staff.dashboard') }}" class="eco-nav-link rounded-3 py-2 px-3 d-flex align-items-center {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
          <div class="icon-container me-3">
            <i class="bi bi-speedometer2"></i>
          </div>
          <span>Dashboard</span>
        </a>
      </li>
      
      <li class="nav-item">
        <a class="eco-nav-link rounded-3 py-2 px-3 d-flex align-items-center justify-content-between {{ request()->routeIs('staff.data*') || request()->routeIs('staff.emisicarbon*') ? 'active' : '' }}" 
           href="#" 
           data-bs-toggle="collapse" 
           data-bs-target="#inputDataSubmenu" 
           aria-expanded="{{ request()->routeIs('staff.emisicarbon*') ? 'true' : 'false' }}" 
           aria-controls="inputDataSubmenu">
          <div class="d-flex align-items-center">
            <div class="icon-container me-3">
              <i class="bi bi-clipboard-data"></i>
            </div>
            <span>Input Data</span>
          </div>
          <i class="bi bi-chevron-down small"></i>
        </a>
        <div class="collapse {{ request()->routeIs('staff.emisicarbon*') ? 'show' : '' }}" id="inputDataSubmenu">
          <ul class="nav flex-column gap-1 mt-1 ms-4 border-start border-light border-opacity-25 ps-2">
            <li class="nav-item">
              <a class="eco-nav-link rounded-3 py-2 px-3 d-flex align-items-center {{ request()->routeIs('staff.emisicarbon*') ? 'active' : '' }}" href="{{ route('staff.emisicarbon.index') }}">
                <div class="icon-container me-3">
                  <i class="bi bi-cloud-arrow-up"></i>
                </div>
                <span>Emisi Karbon</span>
              </a>
            </li>
          </ul>
        </div>
      </li>
    </ul>
    
    <div class="sidebar-heading small text-uppercase text-white-50 px-3 py-2 mt-4">Laporan & Data</div>
    
    <ul class="nav flex-column gap-1">
      <li class="nav-item">
        <a class="eco-nav-link rounded-3 py-2 px-3 d-flex align-items-center {{ request()->routeIs('staff.reports*') ? 'active' : '' }}" href="#">
          <div class="icon-container me-3">
            <i class="bi bi-file-earmark-text"></i>
          </div>
          <span>Laporan</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="eco-nav-link rounded-3 py-2 px-3 d-flex align-items-center {{ request()->routeIs('staff.history*') ? 'active' : '' }}" href="#">
          <div class="icon-container me-3">
            <i class="bi bi-clock-history"></i>
          </div>
          <span>Riwayat Aktivitas</span>
        </a>
      </li>
    </ul>
    
    <div class="sidebar-heading small text-uppercase text-white-50 px-3 py-2 mt-4">Pengaturan</div>
    
    <ul class="nav flex-column gap-1">
      <li class="nav-item">
        <a class="eco-nav-link rounded-3 py-2 px-3 d-flex align-items-center {{ request()->routeIs('staff.profile*') ? 'active' : '' }}" href="#">
          <div class="icon-container me-3">
            <i class="bi bi-person"></i>
          </div>
          <span>Profil Saya</span>
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
    background-color: rgba(1, 157, 32, 0.8); /* BERUBAH: Hijau baru */
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
    background-color: #019D20; /* BERUBAH: Hijau baru */
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
    background-color: #fff; /* Aksen putih, terlihat bagus */
  }
    .eco-nav-link {
    color: white;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    text-decoration: none !important; /* TAMBAHKAN BARIS INI untuk menghilangkan underline */
  }

  .eco-nav-link:hover {
    background-color: rgba(255, 255, 255, 0.15);
    color: white;
    transform: translateX(5px);
    text-decoration: none !important; /* PASTIKAN JUGA ADA DI HOVER untuk konsistensi */
  }

  .eco-nav-link.active {
    /* background-color: #28a745; LAMA */
    background-color: #019D20; /* BARU: Warna hijau kustom Anda */
    color: white;
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
    text-decoration: none !important; /* PASTIKAN JUGA ADA DI ACTIVE untuk konsistensi */
  }
</style>
@endsection
