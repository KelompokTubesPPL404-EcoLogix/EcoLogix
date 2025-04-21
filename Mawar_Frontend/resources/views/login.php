<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Ecologix</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            overflow-x: hidden;
        }
        .container-fluid {
            min-height: 100vh;
            padding: 0;
        }
        .row {
            min-height: 100vh;
            margin: 0;
        }
        .left-side {
            background: linear-gradient(135deg, #198754, #28a745);
            padding: 60px;
            display: flex;
            align-items: center;
        }
        .left-content {
            color: white;
            max-width: 600px;
        }
        .left-content h1 {
            font-size: 3.5rem;
            font-weight: 700;
            line-height: 1.2;
        }
        .right-side {
            background: white;
            padding: 40px 60px;
            display: flex;
            flex-direction: column;
        }
        .logo {
            position: absolute;
            top: 20px;
            right: 40px;
            width: 120px;
        }
        .form-container {
            max-width: 400px;
            width: 100%;
            margin-top: 40px;
        }
        .form-control {
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            margin-bottom: 20px;
        }
        .btn-login {
            background-color: #198754;
            color: white;
            padding: 12px;
            border-radius: 8px;
            border: none;
            width: 100%;
            margin-top: 10px;
        }
        .btn-login:hover {
            background-color: #157347;
        }
        .forgot-password {
            text-align: right;
            margin-top: -15px;
            margin-bottom: 20px;
        }
        .forgot-password a {
            color: #198754;
            text-decoration: none;
            font-size: 14px;
        }
        .register-link {
            color: #198754;
            text-decoration: none;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 left-side">
                <div class="left-content">
                    <h1>Every small action reduces emissions. Trade your carbon footprint for a greener future</h1>
                </div>
            </div>
            
            <div class="col-md-6 right-side">
                <img src="/assets/images/ecologix-logo.png" alt="Ecologix" class="logo">
                
                <div class="form-container">
                    <h2 class="mb-4">Account Login</h2>
                    
                    <form method="POST" action="/login">
                        <div class="mb-3">
                            <label class="form-label">Email address</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>

                        <div class="forgot-password">
                            <a href="http://localhost:3000/forgot_password.php">Forgot Your Password?</a>
                        </div>

                        <button type="submit" class="btn btn-login">Login</button>
                    </form>

                    <div class="text-center mt-3">
                        <p>Don't have an account? <a href="http://localhost:3000/manager_register.php" class="register-link">Sign up here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
