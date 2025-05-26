<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - EcoLogix</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }
        .split-container {
            display: flex;
            min-height: 100vh;
        }
        .left-side {
            flex: 1;
            background: linear-gradient(135deg, #104117 0%, #007f2d 100%);
            padding: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            position: relative;
            overflow: hidden;
        }
        .left-side::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.15;
            z-index: 0;
        }
        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 550px;
        }
        .hero-logo {
            width: 200px;
            margin-bottom: 30px;
        }
        .hero-text {
            font-size: 36px;
            font-weight: 700;
            line-height: 1.3;
            margin-bottom: 20px;
        }
        .hero-description {
            font-size: 16px;
            font-weight: 300;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        .eco-features {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 30px;
        }
        .eco-feature {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            gap: 8px;
            backdrop-filter: blur(5px);
        }
        .right-side {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            position: relative;
            background: white;
        }
        .back-link {
            position: absolute;
            top: 20px;
            left: 20px;
            color: #28a745;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .back-link:hover {
            transform: translateX(-5px);
            color: #104117;
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
        .login-title {
            font-size: 28px;
            font-weight: 600;
            color: #104117;
            margin-bottom: 30px;
            text-align: center;
        }
        .form-floating {
            margin-bottom: 20px;
        }
        .form-floating label {
            color: #6c757d;
        }
        .form-control {
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ddd;
            height: 55px;
            font-size: 15px;
        }
        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.25);
        }
        .btn-login {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #28a745, #007f2d);
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            margin-top: 20px;
            height: 50px;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(40, 167, 69, 0.2);
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #007f2d, #104117);
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(40, 167, 69, 0.3);
        }
        .form-check-input:checked {
            background-color: #28a745;
            border-color: #28a745;
        }
        .carbon-footer {
            text-align: center;
            margin-top: 40px;
            color: #6c757d;
            font-size: 14px;
        }
        .role-badge {
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            margin-left: 8px;
            display: inline-block;
        }
        .role-badge-staff {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
            border: 1px solid rgba(40, 167, 69, 0.2);
        }
        .role-badge-admin {
            background-color: rgba(0, 123, 255, 0.1);
            color: #0d6efd;
            border: 1px solid rgba(0, 123, 255, 0.2);
        }
        .role-badge-manager {
            background-color: rgba(255, 193, 7, 0.1);
            color: #ffc107;
            border: 1px solid rgba(255, 193, 7, 0.2);
        }
        .role-badge-super-admin {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border: 1px solid rgba(220, 53, 69, 0.2);
        }
        @media (max-width: 992px) {
            .split-container {
                flex-direction: column;
            }
            .left-side, .right-side {
                flex: none;
                width: 100%;
            }
            .left-side {
                padding: 60px 20px;
                text-align: center;
            }
            .hero-content {
                margin: 0 auto;
            }
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
            <div class="hero-content">
                <img src="{{ asset('ECOLOGIX.png') }}" alt="EcoLogix Logo" class="hero-logo">
                <h1 class="hero-text">Pantau & Kelola Emisi Karbon untuk Masa Depan yang Lebih Hijau</h1>
                <p class="hero-description">EcoLogix membantu perusahaan memonitor, menganalisis, dan menurunkan jejak karbon demi keberlanjutan lingkungan dan kepatuhan terhadap regulasi.</p>
                
                <div class="eco-features">
                    <div class="eco-feature">
                        <i class="bi bi-bar-chart-fill"></i>
                        <span>Analisis Realtime</span>
                    </div>
                    <div class="eco-feature">
                        <i class="bi bi-shield-check"></i>
                        <span>Kepatuhan Regulasi</span>
                    </div>
                    <div class="eco-feature">
                        <i class="bi bi-graph-up"></i>
                        <span>Visualisasi Data</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="right-side">
            <a href="#" class="back-link">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali ke Beranda</span>
            </a>
            <img src="{{ asset('ECOLOGIX.png') }}" alt="Logo" class="logo">
            
            <div class="login-form">
                <h1 class="login-title">Masuk ke Akun Anda</h1>
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="nama@perusahaan.com" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        <label for="email">Email</label>
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Password" required autocomplete="current-password">
                        <label for="password">Password</label>
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-floating mb-3" id="adminTokenGroup" style="display: none;">
                        <input type="password" class="form-control" id="admin_token" name="admin_token" placeholder="Admin Token">
                        <label for="admin_token">Admin Token</label>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">Ingat saya</label>
                        </div>
                        <a href="#" class="text-decoration-none" style="color: #28a745; font-weight: 500;">Lupa password?</a>
                    </div>
                    
                    <button type="submit" class="btn-login">Masuk</button>
                </form>
                
                <div class="divider my-4">
                    <span>atau</span>
                </div>

                <div class="carbon-footer">
                    <p class="mb-1"> 2025 EcoLogix - Platform Manajemen Emisi Karbon</p>
                    <p class="small">Bersama menciptakan masa depan yang berkelanjutan</p>
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