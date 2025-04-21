<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Admin - Ecologix</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #198754, #28a745);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .register-container {
            max-width: 500px;
            width: 100%;
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            backdrop-filter: blur(10px);
        }
        .register-header {
            text-align: center;
            padding: 30px 20px;
            background: linear-gradient(to right, #198754, #28a745);
            color: #fff;
        }
        .register-header h2 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .form-control {
            padding: 12px;
            border-radius: 10px;
            border: 1px solid #ced4da;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #198754;
            box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
        }
        .form-label {
            font-weight: 500;
            color: #2c3e50;
        }
        .input-group {
            position: relative;
        }
        .input-group-text {
            background-color: transparent;
            border: none;
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            cursor: pointer;
        }
        .btn-success {
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(25, 135, 84, 0.3);
        }
        .alert {
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .login-link {
            color: #198754;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .login-link:hover {
            color: #28a745;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <h2><i class="fas fa-user-plus me-2"></i>Register Admin</h2>
        </div>
        <div class="p-4">
            <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?php echo htmlspecialchars($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/register" class="needs-validation" novalidate>
                <input type="hidden" name="level" value="admin">

                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-user me-2"></i>Nama Lengkap</label>
                    <input type="text" class="form-control" name="nama" required>
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-envelope me-2"></i>Email</label>
                    <input type="email" class="form-control" name="username" required>
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-phone me-2"></i>Nomor Telepon</label>
                    <input type="tel" class="form-control" name="phone" required>
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-lock me-2"></i>Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" name="password" id="password" required>
                        <span class="input-group-text" onclick="togglePassword('password')">
                            <i class="fas fa-eye" id="password-toggle"></i>
                        </span>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label"><i class="fas fa-lock me-2"></i>Konfirmasi Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" name="password_confirmation" id="confirm-password" required>
                        <span class="input-group-text" onclick="togglePassword('confirm-password')">
                            <i class="fas fa-eye" id="confirm-password-toggle"></i>
                        </span>
                    </div>
                </div>

                <button type="submit" class="btn btn-success w-100 mb-3">
                    <i class="fas fa-user-plus me-2"></i>Register
                </button>
            </form>

            <div class="text-center mt-3">
                <p>Sudah punya akun? <a href="/login" class="login-link">Login di sini</a></p>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(inputId + '-toggle');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Form validation
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
</body>
</html>
