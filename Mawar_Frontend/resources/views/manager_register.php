<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Ecologix</title>
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
            background-image: url('path/to/texture.jpg');
            background-blend-mode: multiply;
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
        .back-link {
            color: #666;
            text-decoration: none;
            margin-bottom: 30px;
            display: inline-block;
        }
        .logo {
            position: absolute;
            top: 20px;
            right: 40px;
            width: 120px;
        }
        .form-container {
            max-width: 450px;
            width: 100%;
        }
        .page-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        .page-subtitle {
            color: #666;
            margin-bottom: 30px;
        }
        .form-label {
            color: #333;
            font-weight: 500;
            margin-bottom: 8px;
        }
        .form-control, .form-select {
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            margin-bottom: 20px;
        }
        .btn-continue {
            background-color: #198754;
            color: white;
            padding: 12px;
            border-radius: 8px;
            border: none;
            width: 100%;
            margin-top: 10px;
        }
        .btn-continue:hover {
            background-color: #157347;
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
                <a href="/" class="back-link">← Back</a>
                <img src="/assets/images/ecologix-logo.png" alt="Ecologix" class="logo">
                
                <div class="form-container">
                    <h1 class="page-title">Account Signup</h1>
                    <p class="page-subtitle">sign up according to position.</p>
                    
                    <form method="POST" action="/register">
                        <div class="mb-3">
                            <label class="form-label">Selected Position</label>
                            <select class="form-select" name="role" required onchange="redirectToRegister(this.value)">
                                <option value="" selected disabled>Select your position</option>
                                <option value="admin">Admin</option>
                                <option value="staff">Staff</option>
                                <option value="manager">Manager</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>

                        <button type="submit" class="btn btn-continue">Continue</button>
                    </form>

                    <div class="text-center mt-3">
                        <p>Sudah punya akun? <a href="http://localhost:3000/login.php" class="login-link">Login di sini</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<script>
function redirectToRegister(role) {
    if (role) {
        window.location.href = `/${role}_register`;
    }
}
</script>
