<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Ecologix</title>
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
            <img src="<?php echo htmlspecialchars(asset('image/ecologix.png')); ?>" alt="Ecologix" class="logo">
            
            <div class="login-form">
                <h2 class="mb-4">Account Login</h2>
                
                <?php if(isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <?php echo htmlspecialchars($_SESSION['error']); ?>
                        <?php unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>
                
                <form action="{{ route('login.post') }}" method="POST" id="loginForm">
                    @csrf
                    <div class="mb-3">
                        <label>Email address</label>
                        <input type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" class="form-control" name="password" required>
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
                        <a href="#" style="color: #666; text-decoration: none;">Forgot Your Password?</a>
                    </div>

                    <button type="submit" class="btn-login">Login</button>
                </form>

                </form>

                <div class="text-center mt-3">
                    <p>Don't have an account? <a href="/auth/register" style="color: #4CAF50; text-decoration: none;">Sign up here</a></p>
                </div>

                <div class="divider">
                    <span>or</span>
                </div>

                <div class="social-login">
                    <a href="/auth/google" class="social-btn">
                        <img src="/image/Google.png" alt="Google" width="24">
                    </a>
                    <a href="/auth/facebook" class="social-btn">
                        <img src="/image/Facebook.png" alt="Facebook" width="24">
                    </a>
                    <a href="/auth/twitter" class="social-btn">
                        <img src="/image/Twitter.png" alt="Twitter" width="24">
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>