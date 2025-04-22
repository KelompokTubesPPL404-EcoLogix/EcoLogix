@extends('layouts.app')

@section('title', 'Login Perusahaan')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Login Perusahaan</h3>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('perusahaan.login.submit') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="email_perusahaan" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email_perusahaan" name="email_perusahaan" value="{{ old('email_perusahaan') }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_perusahaan" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password_perusahaan" name="password_perusahaan" required>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection