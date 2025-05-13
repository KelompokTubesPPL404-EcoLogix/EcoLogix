<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function asset($path) {
    return '/assets/' . ltrim($path, '/');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration - Ecologix</title>
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
            font-family: 'Segoe UI', sans-serif;
        }
        .register-container {
            max-width: 500px;
            width: 100%;
            background-color: rgba(255, 255, 255, 0.98);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        .register-header {
            text-align: center;
            padding: 30px 20px;
            background: linear-gradient(to right, #198754, #28a745);
            color: #fff;
            position: relative;
        }
        .back-button {
            position: absolute;
            left: 20px;
            top: 20px;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 14px;
        }
        .back-button:hover {
            color: rgba(255, 255, 255, 0.8);
        }
        .form-control, .form-select {
            padding: 12px;
            border-radius: 10px;
            border: 1px solid #ced4da;
            transition: all 0.3s ease;
            height: auto;
        }
        .form-control:focus, .form-select:focus {
            border-color: #198754;
            box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
        }
        .input-group-text {
            cursor: pointer;
            background: none;
            border: none;
            color: #198754;
        }
        .btn-success {
            padding: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(25, 135, 84, 0.3);
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <a href="/" class="back-button">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <h2><i class="fas fa-user-plus me-2"></i>Admin Registration</h2>
            <p class="mb-0">Create your admin account</p>
        </div>

        <div class="p-4">
            <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?php 
                        echo htmlspecialchars($_SESSION['error']); 
                        unset($_SESSION['error']);
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form method="POST" action="/auth/register" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label class="form-label">Position</label>
                    <select class="form-select" name="position" required>
                        <option value="">Select position...</option>
                        <option value="admin">Administrator</option>
                        <option value="staff">Staff Member</option>
                        <option value="manager">Manager</option>
                    </select>
                    <div class="invalid-feedback">Please select a position</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" class="form-control" name="email" required>
                    <div class="invalid-feedback">Please enter a valid email address</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" class="form-control" name="name" required>
                    <div class="invalid-feedback">Please enter your full name</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" name="password" id="password" 
                               pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$" required>
                        <span class="input-group-text" onclick="togglePassword('password')">
                            <i class="fas fa-eye" id="password-toggle"></i>
                        </span>
                    </div>
                    <div class="form-text">Minimum 8 characters, at least one letter and one number</div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Confirm Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" name="password_confirmation" 
                               id="confirm-password" required>
                        <span class="input-group-text" onclick="togglePassword('confirm-password')">
                            <i class="fas fa-eye" id="confirm-password-toggle"></i>
                        </span>
                    </div>
                </div>

                <button type="submit" class="btn btn-success w-100 mb-3">
                    <i class="fas fa-user-plus me-2"></i>Create Account
                </button>
            </form>

            <div class="text-center mt-3">
                <p class="mb-0">Already have an account? 
                    <a href="/login" class="text-success fw-bold text-decoration-none">Login here</a>
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(inputId + '-toggle');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        // Form validation
        (() => {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            forms.forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
</body>
</html>
