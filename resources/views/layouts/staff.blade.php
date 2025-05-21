@extends('layouts.app')

@section('title', 'Staff Dashboard')

@section('sidebar')
<ul class="nav flex-column">
  <li class="nav-item">
    <a href="{{ route('staff.dashboard') }}" class="nav-link {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
      <i class="bi bi-house-door me-2"></i> Dashboard
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('staff.data*') || request()->routeIs('staff.emisicarbon*') ? 'active' : '' }} collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#inputDataSubmenu" aria-expanded="false" aria-controls="inputDataSubmenu">
      <i class="bi bi-clipboard-data me-2"></i> Input Data
    </a>
    <div class="collapse {{ request()->routeIs('staff.emisicarbon*') ? 'show' : '' }}" id="inputDataSubmenu">
      <ul class="nav flex-column ms-3">
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('staff.emisicarbon*') ? 'active' : '' }}" href="{{ route('staff.emisicarbon.index') }}">
            <i class="bi bi-cloud-arrow-up me-2"></i> Emisi Karbon
          </a>
        </li>
      </ul>
    </div>
  </li>
  <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('staff.reports*') ? 'active' : '' }}" href="#">
      <i class="bi bi-file-earmark-text me-2"></i> Laporan
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('staff.history*') ? 'active' : '' }}" href="#">
      <i class="bi bi-clock-history me-2"></i> Riwayat Aktivitas
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('staff.profile*') ? 'active' : '' }}" href="#">
      <i class="bi bi-person me-2"></i> Profil Saya
    </a>
  </li>
</ul>
@endsection
