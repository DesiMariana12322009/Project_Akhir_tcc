<?php
require_once 'config.php';
requireAdmin();

$title = 'Admin - Kelola Users';
$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'create') {
        $data = [
            'username' => $_POST['username'] ?? '',
            'email' => $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? ''
        ];
        
        if (empty($data['username']) || empty($data['email']) || empty($data['password'])) {
            $error = 'Semua field harus diisi!';
        } else {
            $response = makeApiRequest('users', 'POST', $data, getToken());
            if ($response['success']) {
                $success = 'User berhasil ditambahkan!';
            } else {
                $error = $response['data']['message'] ?? 'Gagal menambahkan user!';
            }
        }
    }
    
    elseif ($action === 'update') {
        $id = $_POST['id'] ?? '';
        $data = [
            'username' => $_POST['username'] ?? '',
            'email' => $_POST['email'] ?? ''
        ];
        
        // Only include password if provided
        if (!empty($_POST['password'])) {
            $data['password'] = $_POST['password'];
        }
        
        if (empty($data['username']) || empty($data['email'])) {
            $error = 'Username dan email harus diisi!';
        } else {
            $response = makeApiRequest('users/' . $id, 'PUT', $data, getToken());
            if ($response['success']) {
                $success = 'User berhasil diperbarui!';
            } else {
                $error = $response['data']['message'] ?? 'Gagal memperbarui user!';
            }
        }
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $response = makeApiRequest('users/' . $_GET['delete'], 'DELETE', null, getToken());
    if ($response['success']) {
        $success = 'User berhasil dihapus!';
    } else {
        $error = 'Gagal menghapus user!';
    }
}

// Get edit data
$editUser = null;
if (isset($_GET['edit'])) {
    $response = makeApiRequest('users/' . $_GET['edit'], 'GET', null, getToken());
    if ($response['success']) {
        $editUser = $response['data'];
    }
}

// Get users list
$usersResponse = makeApiRequest('users', 'GET', null, getToken());
$usersList = $usersResponse['success'] ? $usersResponse['data'] : [];

include 'header.php';
?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="fas fa-users me-2"></i>
        Kelola Users
        <span class="badge bg-primary"><?php echo count($usersList); ?></span>
    </h2>
    
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userModal">
        <i class="fas fa-user-plus me-2"></i>Tambah User
    </button>
</div>

<!-- Alerts -->
<?php if ($error): ?>
    <?php echo showAlert($error, 'danger'); ?>
<?php endif; ?>

<?php if ($success): ?>
    <?php echo showAlert($success, 'success'); ?>
<?php endif; ?>

<!-- Users Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-table me-2"></i>
            Daftar Users
        </h5>
    </div>
    
    <div class="card-body p-0">
        <?php if (empty($usersList)): ?>
        <div class="text-center py-5">
            <i class="fas fa-users fs-1 text-muted mb-3"></i>
            <h5 class="text-muted">Belum ada user</h5>
            <p class="text-muted">Klik tombol "Tambah User" untuk menambahkan user baru</p>
        </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Tanggal Daftar</th>
                        <th>Terakhir Update</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usersList as $user): ?>
                    <tr>
                        <td class="fw-bold">#<?php echo $user['id']; ?></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center" 
                                     style="width: 32px; height: 32px; font-size: 14px;">
                                    <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                                </div>
                                <strong><?php echo htmlspecialchars($user['username']); ?></strong>
                            </div>
                        </td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td>
                            <small class="text-muted">
                                <?php echo date('d/m/Y H:i', strtotime($user['createdAt'])); ?>
                            </small>
                        </td>
                        <td>
                            <small class="text-muted">
                                <?php echo date('d/m/Y H:i', strtotime($user['updatedAt'])); ?>
                            </small>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <button class="btn btn-sm btn-outline-warning" 
                                        onclick="editUser(<?php echo htmlspecialchars(json_encode($user)); ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <a href="?delete=<?php echo $user['id']; ?>" 
                                   class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('Yakin ingin menghapus user ini?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Form -->
<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">
                        <i class="fas fa-user-plus me-2"></i>Tambah User
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
                    <input type="hidden" name="action" id="formAction" value="create">
                    <input type="hidden" name="id" id="userId">
                    
                    <div class="mb-3">
                        <label for="username" class="form-label">
                            <i class="fas fa-user me-2"></i>Username *
                        </label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-2"></i>Email *
                        </label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock me-2"></i>Password
                        </label>
                        <input type="password" class="form-control" id="password" name="password">
                        <div class="form-text" id="passwordHelp">
                            Minimal 6 karakter. Kosongkan jika tidak ingin mengubah password.
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-save me-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editUser(user) {
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-user-edit me-2"></i>Edit User';
    document.getElementById('formAction').value = 'update';
    document.getElementById('userId').value = user.id;
    document.getElementById('username').value = user.username;
    document.getElementById('email').value = user.email;
    document.getElementById('password').value = '';
    document.getElementById('password').removeAttribute('required');
    document.getElementById('passwordHelp').textContent = 'Kosongkan jika tidak ingin mengubah password.';
    document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save me-2"></i>Update';
    
    new bootstrap.Modal(document.getElementById('userModal')).show();
}

// Reset form when modal is closed
document.getElementById('userModal').addEventListener('hidden.bs.modal', function() {
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-user-plus me-2"></i>Tambah User';
    document.getElementById('formAction').value = 'create';
    document.getElementById('userId').value = '';
    document.getElementById('password').setAttribute('required', '');
    document.getElementById('passwordHelp').textContent = 'Minimal 6 karakter. Kosongkan jika tidak ingin mengubah password.';
    document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save me-2"></i>Simpan';
    document.querySelector('#userModal form').reset();
});
</script>

<?php include 'footer.php'; ?>
