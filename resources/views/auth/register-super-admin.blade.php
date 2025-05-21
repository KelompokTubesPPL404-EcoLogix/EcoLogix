<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Super Admin - EcoLogix</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: #f8f9fa;
        }
        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .signup-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .signup-header {
            background: #4CAF50;
            padding: 30px;
            text-align: center;
            color: white;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 20px;
        }
        .signup-form {
            padding: 40px;
        }
        .form-control {
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ddd;
            margin-bottom: 15px;
        }
        .btn-signup {
            width: 100%;
            padding: 12px;
            background: #4CAF50;
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            margin-top: 20px;
        }
        .btn-signup:hover {
            background: #45a049;
        }
        .back-link {
            display: inline-flex;
            align-items: center;
            color: #666;
            text-decoration: none;
            margin-bottom: 20px;
            gap: 5px;
        }
        .back-link:hover {
            color: #4CAF50;
        }
        .form-text {
            color: #666;
            font-size: 0.9rem;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('login') }}" class="back-link">← Back to Login</a>
        
        <div class="signup-card">
            <div class="signup-header">
                <img src="{{ asset('images/ecologix-logo.png') }}" alt="EcoLogix" class="logo">
                <h2>Super Admin Registration</h2>
                <p>Register as the application owner</p>
            </div>
            
            <div class="signup-form">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register.super-admin.post') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label>Full Name</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama') }}" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label>Email address</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                        <div class="form-text">This email will be used for login as Super Admin</div>
                    </div>

                    <div class="mb-3">
                        <label>Phone Number</label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" required>
                    </div>

                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <div class="form-text">Use 8 or more characters with a mix of letters, numbers & symbols</div>
                    </div>

                    <div class="mb-3">
                        <label>Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>

                    <button type="submit" class="btn-signup">Register as Super Admin</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>