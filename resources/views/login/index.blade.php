@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="split-screen">
    <div class="left-side">
        <img src="{{ asset('images/ecologix-logo.png') }}" alt="Ecologix" class="logo">
    </div>
    <div class="right-side">
        <div class="overlay"></div>
        <nav class="nav-container">
            <a href="{{ route('home') }}" class="nav-link">Home</a>
            <a href="{{ route('contact') }}" class="nav-link">Contact</a>
            <a href="{{ route('about') }}" class="nav-link">About</a>
            <a href="{{ route('login') }}" class="login-btn">Login</a>
        </nav>
    </div>
</div>
@endsection

@push('styles')
<style>
    body {
        margin: 0;
        padding: 0;
        overflow: hidden;
    }
    .split-screen {
        display: flex;
        height: 100vh;
    }
    .left-side {
        flex: 0 0 30%;
        background: #4CAF50;
        padding: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        z-index: 2;
    }
    .right-side {
        flex: 0 0 70%;
        background-image: url('../assets/images/forest.jpg');
        background-size: cover;
        background-position: center;
        position: relative;
    }
    .logo {
        width: 200px;
        height: auto;
        filter: brightness(0) invert(1);
    }
    .nav-container {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        padding: 2rem;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 2rem;
        z-index: 2;
    }
    .nav-link {
        color: white;
        text-decoration: none;
        font-size: 1.1rem;
        font-weight: 500;
        transition: all 0.3s ease;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
    }
    .nav-link:hover {
        color: rgba(255, 255, 255, 0.8);
        transform: translateY(-2px);
    }
    .login-btn {
        border: 2px solid white;
        padding: 0.5rem 2rem;
        border-radius: 5px;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
    }
    .login-btn:hover {
        background: white;
        color: #4CAF50;
        transform: translateY(-2px);
    }
    .overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.4);
        z-index: 1;
    }
</style>
@endpush