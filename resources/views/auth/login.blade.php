<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - EcoLogix</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: #f8f9fa;
        }
        .split-container {
            display: flex;
            min-height: 100vh;
        }
        .left-side {
            flex: 1;
            background: linear-gradient(135deg, #4CAF50, #45a049);
            padding: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        .hero-text {
            font-size: 48px;
            font-weight: bold;
            line-height: 1.2;
            max-width: 500px;
        }
        .right-side {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            position: relative;
        }
        .back-link {
            position: absolute;
            top: 20px;
            left: 20px;
            color: #666;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .logo {
            position: absolute;
            top: 20px;
            right: 20px;
            max-width: 150px;
        }
        .login-form {
            max-width: 400px;
            width: 100%;
            margin: auto;
            padding: 20px;
        }
        .form-control {
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ddd;
            margin-bottom: 15px;
        }
        .btn-login {
            width: 100%;
            padding: 12px;
            background: #4CAF50;
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            margin-top: 20px;
        }
        .btn-login:hover {
            background: #45a049;
        }
        .social-login {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }
        .social-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #ddd;
            transition: all 0.3s ease;
        }
        .social-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 20px 0;
            color: #666;
        }
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #ddd;
        }
        .divider span {
            padding: 0 10px;
        }

        @media (max-width: 768px) {
            .split-container {
                flex-direction: column;
            }
            .left-side {
                min-height: 30vh;
            }
            .hero-text {
                font-size: 32px;
            }
        }
    </style>
</head>
<body>
    <div class="split-container">
        <div class="left-side">
            <div class="hero-text">
                Every small action reduces emissions. Trade your carbon footprint for a greener future
            </div>
        </div>
        <div class="right-side">
            <a href="javascript:history.back()" class="back-link">← Back</a>
            <img src="{{ asset('images/ecologix-logo.png') }}" alt="EcoLogix" class="logo">
            
            <div class="login-form">
                <h2 class="mb-4">Account Login</h2>
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form action="{{ route('login.post') }}" method="POST" id="loginForm">
                    @csrf
                    <div class="mb-3">
                        <label>Email address</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    
                    <div class="mb-3" id="adminTokenGroup" style="display: none;">
                        <label for="admin_token">Super Admin Token</label>
                        <input type="password" class="form-control" id="admin_token" name="admin_token" value="{{ old('admin_token') }}">
                        <div class="form-text text-danger">Login sebagai Super Admin memerlukan token keamanan khusus.</div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>
                        <a href="#" style="color: #666; text-decoration: none;">Forgot Password?</a>
                    </div>

                    <button type="submit" class="btn-login">Login</button>
                </form>

                <div class="text-center mt-3">
                    @if (\App\Models\User::where('role', 'super_admin')->count() === 0)
                        <p>Belum ada Super Admin? <a href="{{ route('register.super-admin') }}" style="color: #4CAF50; text-decoration: none;">Daftar Super Admin</a></p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cache DOM elements
            const emailInput = document.getElementById('email');
            const adminTokenGroup = document.getElementById('adminTokenGroup');
            
            // Function to check if email belongs to Super Admin
            async function checkIsSuperAdmin(email) {
                if (!email) return false;
                
                try {
                    const response = await fetch(`/check-super-admin?email=${encodeURIComponent(email)}`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    
                    if (response.ok) {
                        const data = await response.json();
                        return data.isSuperAdmin;
                    }
                    return false;
                } catch (error) {
                    console.error('Error checking super admin:', error);
                    return false;
                }
            }
            
            // Handle email input change
            emailInput.addEventListener('blur', async function() {
                const email = emailInput.value.trim();
                const isSuperAdmin = await checkIsSuperAdmin(email);
                
                if (isSuperAdmin) {
                    adminTokenGroup.style.display = 'block';
                    document.getElementById('admin_token').setAttribute('required', '');
                } else {
                    adminTokenGroup.style.display = 'none';
                    document.getElementById('admin_token').removeAttribute('required');
                }
            });
            
            // Check on page load if email is already filled (e.g. from old input)
            if (emailInput.value.trim()) {
                emailInput.dispatchEvent(new Event('blur'));
            }
        });
    </script>
</body>
</html>