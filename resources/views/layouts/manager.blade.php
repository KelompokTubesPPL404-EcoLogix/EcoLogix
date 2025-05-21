@extends('layouts.app')

@section('title', 'Manager Dashboard')

@section('sidebar')
<ul class="nav flex-column">
  <li class="nav-item">
    <a href="{{ route('manager.dashboard') }}" class="nav-link {{ request()->routeIs('manager.dashboard') ? 'active' : '' }}">
      <i class="bi bi-house-door me-2"></i> Dashboard
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('manager.admin*') || request()->routeIs('manager.staff*') ? 'active' : '' }}" href="#">
      <i class="bi bi-people me-2"></i> Manajemen Tim
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('manager.reports*') ? 'active' : '' }}" href="#">
      <i class="bi bi-bar-chart me-2"></i> Laporan Emisi
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('manager.kompensasi*') ? 'active' : '' }}" href="#">
      <i class="bi bi-arrow-left-right me-2"></i> Kompensasi Emisi
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('manager.carbon_credit*') ? 'active' : '' }}" href="#">
      <i class="bi bi-currency-exchange me-2"></i> Carbon Credit
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('manager.faktor-emisi*') ? 'active' : '' }}" href="{{ route('manager.faktor-emisi.index') }}">
      <i class="bi bi-rulers me-2"></i> Faktor Emisi
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('manager.penyedia-carbon-credit*') ? 'active' : '' }}" href="{{ route('manager.penyedia-carbon-credit.index') }}">
      <i class="bi bi-building me-2"></i> Penyedia Carbon Credit
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('manager.settings*') ? 'active' : '' }}" href="#">
      <i class="bi bi-gear me-2"></i> Pengaturan
    </a>
  </li>
</ul>
@endsection
