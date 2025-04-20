<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GreenLedger - Registrasi Super Admin</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(to right, #198754, #28a745);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            color: #fff;
        }
        
        .login-header img {
            max-width: 200px;
            height: auto; 
            width: 100%;
            transition: transform 0.3s ease;
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
        }

        .register-container {
            width: 100%;
            max-width: 500px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            padding: 20px;
            color: #212529;
        }

        .register-header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
        }

        .register-header img:hover {
            transform: scale(1.05);
        }

        .card-header {
            background-color: #198754;
            border-bottom: none;
            text-align: center;
            color: #fff;
            font-size: 1.5rem;
            font-weight: bold;
            border-radius: 10px 10px 0 0;
            padding: 15px;
        }

        .form-control:focus {
            border-color: #198754;
            box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
        }

        .btn-success {
            background: #198754;
            border: none;
            font-weight: bold;
        }

        .btn-success:hover {
            background: #145d36;
        }

        .footer-text {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9rem;
            color: #ccc;
        }

        @media (max-width: 576px) {
            .register-container {
                padding: 15px;
            }

            .card-header {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <img src="{{ asset('img/logo.png') }}" alt="Logo">  
        </div>

        <div class="card">
            <div class="card-header">
                Registrasi Super Admin
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register.super_admin.submit') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="nama_super_admin" class="form-label">Nama Super Admin</label>
                        <input type="text" 
                               class="form-control @error('nama_super_admin') is-invalid @enderror" 
                               name="nama_super_admin" 
                               value="{{ old('nama_super_admin') }}" 
                               required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required>
                    </div>

                    <div class="mb-3">
                        <label for="no_telepon" class="form-label">Nomor Telepon</label>
                        <input type="text" 
                               class="form-control @error('no_telepon') is-invalid @enderror" 
                               name="no_telepon" 
                               value="{{ old('no_telepon') }}" 
                               required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               name="password" 
                               required>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" 
                               class="form-control" 
                               name="password_confirmation" 
                               required>
                    </div>

                    <button type="submit" class="btn btn-success w-100">Daftar</button>
                </form>

                <div class="text-center mt-3">
                    <a href="{{ route('login') }}" class="text-success">Kembali ke Login</a>
                </div>
            </div>
        </div>

        <div class="footer-text">
            &copy; {{ date('Y') }} GreenLedger. All rights reserved.
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>