

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Ecologix</title>
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
        .position-select {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ddd;
            margin-bottom: 15px;
            background: white;
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
        <a href="/" class="back-link">← Back</a>
        
        <div class="signup-card">
            <div class="signup-header">
                <img src="<?php echo htmlspecialchars(asset('images/ecologix-logo.png')); ?>" alt="Ecologix" class="logo">
                <h2>Account Signup</h2>
                <p>Sign up according to position</p>
            </div>
            
            <div class="signup-form">
                <form method="POST" action="/register">
                    <div class="mb-3">
                        <label>Selected Position</label>
                        <select class="position-select" name="position" required>
                            <option value="">Select your position</option>
                            <option value="manager">Manager</option>
                            <option value="staff">Staff</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Email address</label>
                        <input type="email" class="form-control" name="email" required>
                        <div class="form-text">We'll never share your email with anyone else.</div>
                    </div>

                    <div class="mb-3">
                        <label>Full Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" class="form-control" name="password" required>
                        <div class="form-text">Use 8 or more characters with a mix of letters, numbers & symbols</div>
                    </div>

                    <div class="mb-3">
                        <label>Confirm Password</label>
                        <input type="password" class="form-control" name="password_confirmation" required>
                    </div>

                    <button type="submit" class="btn-signup">Continue</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>