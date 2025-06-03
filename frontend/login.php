<?php
require_once 'config.php';
$title = 'Login - Wisata Indonesia';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $isAdmin = isset($_POST['admin_login']);
    
    if (empty($username) || empty($password)) {
        $error = 'Username dan password harus diisi!';
    } else {
        $endpoint = $isAdmin ? 'admin/login' : 'login';
        
        $response = makeApiRequest($endpoint, 'POST', [
            'username' => $username,
            'password' => $password
        ]);
        
        if ($response['success'] && !empty($response['data']['token'])) {
            $_SESSION['token'] = $response['data']['token'];
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $isAdmin ? 'admin' : 'user';
            
            $success = 'Login berhasil! Mengalihkan...';
            header('refresh:2;url=index.php');
        } else {
            $error = $response['data']['message'] ?? 'Login gagal! Periksa username dan password.';
        }
    }
}

include 'header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-primary text-white text-center py-4">
                <h3 class="mb-0">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    Login
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
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock me-2"></i>Password
                        </label>
                        <input type="password" class="form-control form-control-lg" 
                               id="password" name="password" required>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" 
                               id="admin_login" name="admin_login"
                               <?php echo isset($_POST['admin_login']) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="admin_login">
                            <i class="fas fa-crown me-2 text-warning"></i>
                            Login sebagai Admin
                        </label>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Login
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="card-footer text-center py-3">
                <p class="mb-0 text-muted">
                    Belum punya akun? 
                    <a href="register.php" class="text-decoration-none">
                        <i class="fas fa-user-plus me-1"></i>
                        Daftar di sini
                    </a>
                </p>
            </div>
        </div>
        
        <!-- Demo Credentials -->
        <div class="mt-4">
            <div class="card bg-light">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-info-circle me-2 text-info"></i>
                        Demo Credentials
                    </h6>
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <strong>User:</strong><br>
                                Username: testuser<br>
                                Password: user123
                            </small>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">
                                <strong>Admin:</strong><br>
                                Username: admin<br>
                                Password: admin123
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
