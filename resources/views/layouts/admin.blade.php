@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('sidebar')
<ul class="nav flex-column">
  <li class="nav-item">
    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
      <i class="bi bi-house-door me-2"></i> Dashboard
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}" href="#">
      <i class="bi bi-people me-2"></i> Manajemen Pengguna
    </a>
  </li>
  <li class="nav-item">
    <a href="{{ route('admin.staff.index') }}" class="nav-link {{ request()->routeIs('admin.staff*') ? 'active' : '' }}">
      <i class="bi bi-person-badge me-2"></i> Manajemen Staff
    </a>
  </li>
  <li class="nav-item">
    <a href="{{ route('admin.emisicarbon.index') }}" class="nav-link {{ request()->routeIs('admin.emisicarbon*') ? 'active' : '' }}">
      <i class="bi bi-cloud-fill me-2"></i> Kelola Emisi Karbon
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}" href="#">
      <i class="bi bi-file-earmark-text me-2"></i> Laporan Emisi
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('admin.data*') ? 'active' : '' }}" href="#">
      <i class="bi bi-database me-2"></i> Data Perusahaan
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}" href="#">
      <i class="bi bi-gear me-2"></i> Pengaturan
    </a>
  </li>
</ul>
@endsection
