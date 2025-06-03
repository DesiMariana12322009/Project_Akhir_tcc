<?php
require_once 'config.php';
$title = 'Register - Wisata Indonesia';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $isAdmin = isset($_POST['admin_register']);
    
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'Semua field harus diisi!';
    } elseif ($password !== $confirm_password) {
        $error = 'Password dan konfirmasi password tidak sama!';
    } elseif (strlen($password) < 6) {
        $error = 'Password harus minimal 6 karakter!';
    } else {
        $endpoint = $isAdmin ? 'admin/register' : 'register';
        
        $response = makeApiRequest($endpoint, 'POST', [
            'username' => $username,
            'email' => $email,
            'password' => $password
        ]);
        
        if ($response['success']) {
            $success = 'Registrasi berhasil! Silakan login.';
            header('refresh:3;url=login.php');
        } else {
            $error = $response['data']['message'] ?? 'Registrasi gagal! Silakan coba lagi.';
        }
    }
}

include 'header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-success text-white text-center py-4">
                <h3 class="mb-0">
                    <i class="fas fa-user-plus me-2"></i>
                    Register
                </h3>
            </div>
            
            <div class="card-body p-4">
                <?php if ($error): ?>
                    <?php echo showAlert($error, 'danger'); ?>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <?php echo showAlert($success, 'success'); ?>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">
                            <i class="fas fa-user me-2"></i>Username
                        </label>
                        <input type="text" class="form-control form-control-lg" 
                               id="username" name="username" 
                               value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                               required>
                        <div class="form-text">Username harus unik dan mudah diingat</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-2"></i>Email
                        </label>
                        <input type="email" class="form-control form-control-lg" 
                               id="email" name="email" 
                               value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                               required>
                        <div class="form-text">Email harus valid dan belum terdaftar</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock me-2"></i>Password
                        </label>
                        <input type="password" class="form-control form-control-lg" 
                               id="password" name="password" required>
                        <div class="form-text">Password minimal 6 karakter</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">
                            <i class="fas fa-lock me-2"></i>Konfirmasi Password
                        </label>
                        <input type="password" class="form-control form-control-lg" 
                               id="confirm_password" name="confirm_password" required>
                        <div class="form-text">Ulangi password yang sama</div>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" 
                               id="admin_register" name="admin_register"
                               <?php echo isset($_POST['admin_register']) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="admin_register">
                            <i class="fas fa-crown me-2 text-warning"></i>
                            Daftar sebagai Admin
                        </label>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-user-plus me-2"></i>
                            Register
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="card-footer text-center py-3">
                <p class="mb-0 text-muted">
                    Sudah punya akun? 
                    <a href="login.php" class="text-decoration-none">
                        <i class="fas fa-sign-in-alt me-1"></i>
                        Login di sini
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
// Password confirmation validation
document.getElementById('confirm_password').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;
    
    if (password !== confirmPassword) {
        this.setCustomValidity('Password tidak sama');
    } else {
        this.setCustomValidity('');
    }
});
</script>

<?php include 'footer.php'; ?>
