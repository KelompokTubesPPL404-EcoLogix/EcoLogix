@extends('layouts.app')

@section('title', 'Super Admin Dashboard')

@section('sidebar')
<div class="p-3">
  <div class="d-flex align-items-center mb-4 mt-2">
    <div class="rounded-circle bg-white p-2 me-2">
      <i class="bi bi-shield-check text-success fs-5"></i>
    </div>
    <h5 class="text-white mb-0 fw-bold">Super Admin</h5>
  </div>

  <ul class="nav flex-column gap-2">
    <li class="nav-item">
      <a href="{{ route('superadmin.dashboard') }}" class="nav-link rounded-3 d-flex align-items-center {{ request()->routeIs('superadmin.dashboard') ? 'active bg-success-subtle text-success' : 'text-white' }}">
        <div class="rounded-circle {{ request()->routeIs('superadmin.dashboard') ? 'bg-success' : 'bg-white bg-opacity-25' }} p-2 me-3 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px">
          <i class="bi bi-speedometer2 {{ request()->routeIs('superadmin.dashboard') ? 'text-white' : '' }} fs-6"></i>
        </div>
        <span class="fw-medium">Dashboard</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link rounded-3 d-flex align-items-center {{ request()->routeIs('superadmin.perusahaan*') ? 'active bg-success-subtle text-success' : 'text-white' }}" href="{{ route('superadmin.perusahaan.index') }}">
        <div class="rounded-circle {{ request()->routeIs('superadmin.perusahaan*') ? 'bg-success' : 'bg-white bg-opacity-25' }} p-2 me-3 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px">
          <i class="bi bi-building {{ request()->routeIs('superadmin.perusahaan*') ? 'text-white' : '' }} fs-6"></i>
        </div>
        <span class="fw-medium">Perusahaan</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link rounded-3 d-flex align-items-center {{ request()->routeIs('superadmin.manager*') ? 'active bg-success-subtle text-success' : 'text-white' }}" href="{{ route('superadmin.manager.index') }}">
        <div class="rounded-circle {{ request()->routeIs('superadmin.manager*') ? 'bg-success' : 'bg-white bg-opacity-25' }} p-2 me-3 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px">
          <i class="bi bi-person-badge {{ request()->routeIs('superadmin.manager*') ? 'text-white' : '' }} fs-6"></i>
        </div>
        <span class="fw-medium">Manajer</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link rounded-3 d-flex align-items-center {{ request()->routeIs('superadmin.users*') ? 'active bg-success-subtle text-success' : 'text-white' }}" href="#">
        <div class="rounded-circle {{ request()->routeIs('superadmin.users*') ? 'bg-success' : 'bg-white bg-opacity-25' }} p-2 me-3 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px">
          <i class="bi bi-people {{ request()->routeIs('superadmin.users*') ? 'text-white' : '' }} fs-6"></i>
        </div>
        <span class="fw-medium">Pengguna</span>
      </a>
    </li>
    <li class="nav-item mt-2">
      <div class="border-top border-white border-opacity-25 pt-2 mb-2"></div>
      <a class="nav-link rounded-3 d-flex align-items-center {{ request()->routeIs('superadmin.reports*') ? 'active bg-success-subtle text-success' : 'text-white' }}" href="#">
        <div class="rounded-circle {{ request()->routeIs('superadmin.reports*') ? 'bg-success' : 'bg-white bg-opacity-25' }} p-2 me-3 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px">
          <i class="bi bi-bar-chart {{ request()->routeIs('superadmin.reports*') ? 'text-white' : '' }} fs-6"></i>
        </div>
        <span class="fw-medium">Laporan</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link rounded-3 d-flex align-items-center {{ request()->routeIs('superadmin.settings*') ? 'active bg-success-subtle text-success' : 'text-white' }}" href="#">
        <div class="rounded-circle {{ request()->routeIs('superadmin.settings*') ? 'bg-success' : 'bg-white bg-opacity-25' }} p-2 me-3 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px">
          <i class="bi bi-gear {{ request()->routeIs('superadmin.settings*') ? 'text-white' : '' }} fs-6"></i>
        </div>
        <span class="fw-medium">Pengaturan</span>
      </a>
    </li>
  </ul>
</div>

<style>
  .sidebar .nav-link:hover {
    background-color: rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
  }
  
  .sidebar .nav-link {
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
  }
</style>
@endsection
