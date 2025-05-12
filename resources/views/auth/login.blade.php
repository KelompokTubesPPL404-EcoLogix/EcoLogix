<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Manajemen Carbon Credit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Login</h4>
                    </div>
                    <div class="card-body">
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
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3" id="adminTokenGroup" style="display: none;">
                                <label for="admin_token" class="form-label">Token Super Admin</label>
                                <input type="password" class="form-control" id="admin_token" name="admin_token" value="{{ old('admin_token') }}">
                                <div class="form-text text-danger">Login sebagai Super Admin memerlukan token keamanan khusus.</div>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Ingat Saya</label>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        @if (\App\Models\User::where('role', 'super_admin')->count() === 0)
                            <p class="mb-0">Belum ada Super Admin? <a href="{{ route('register.super-admin') }}">Daftar Super Admin</a></p>
                        @endif
                    </div>
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