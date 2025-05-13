<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Staff - Ecologix</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #198754, #28a745);
            color: #fff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .register-container {
            max-width: 500px;
            width: 100%;
            background-color: #fff;
            color: #212529;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        .register-header {
            text-align: center;
            padding: 20px;
            background-color: #198754;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <h2>Register Staff</h2>
        </div>
        <div class="p-4">
            <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="../controllers/register_staff.php" class="needs-validation" novalidate>
                <input type="hidden" name="role" value="staff">

                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" name="name" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nomor Telepon</label>
                    <input type="text" class="form-control" name="phone" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" class="form-control" name="password_confirmation" required>
                </div>

                <button type="submit" class="btn btn-success w-100">Register</button>
            </form>

            <div class="text-center mt-3">
                <p>Sudah punya akun? <a href="/login" class="login-link">Login di sini</a></p>
            </div>
        </div>
    </div>
</body>
</html>
