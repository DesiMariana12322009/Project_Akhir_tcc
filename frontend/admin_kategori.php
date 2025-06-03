<?php
require_once 'config.php';
requireAdmin();

$title = 'Admin - Kelola Kategori';
$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'create') {
        $nama = $_POST['nama'] ?? '';
        
        if (empty($nama)) {
            $error = 'Nama kategori harus diisi!';
        } else {
            $response = makeApiRequest('kategori', 'POST', ['nama' => $nama], getToken());
            if ($response['success']) {
                $success = 'Kategori berhasil ditambahkan!';
            } else {
                $error = $response['data']['message'] ?? 'Gagal menambahkan kategori!';
            }
        }
    }
    
    elseif ($action === 'update') {
        $id = $_POST['id'] ?? '';
        $nama = $_POST['nama'] ?? '';
        
        if (empty($nama)) {
            $error = 'Nama kategori harus diisi!';
        } else {
            $response = makeApiRequest('kategori/' . $id, 'PUT', ['nama' => $nama], getToken());
            if ($response['success']) {
                $success = 'Kategori berhasil diperbarui!';
            } else {
                $error = $response['data']['message'] ?? 'Gagal memperbarui kategori!';
            }
        }
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $response = makeApiRequest('kategori/' . $_GET['delete'], 'DELETE', null, getToken());
    if ($response['success']) {
        $success = 'Kategori berhasil dihapus!';
    } else {
        $error = 'Gagal menghapus kategori!';
    }
}

// Get edit data
$editKategori = null;
if (isset($_GET['edit'])) {
    $response = makeApiRequest('kategori/' . $_GET['edit']);
    if ($response['success']) {
        $editKategori = $response['data'];
    }
}

// Get categories list
$kategoriResponse = makeApiRequest('kategori');
$kategoriList = $kategoriResponse['success'] ? $kategoriResponse['data'] : [];

include 'header.php';
?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="fas fa-tags me-2"></i>
        Kelola Kategori
        <span class="badge bg-primary"><?php echo count($kategoriList); ?></span>
    </h2>
    
    <div>
        <a href="kategori.php" class="btn btn-outline-secondary me-2">
            <i class="fas fa-eye me-2"></i>Lihat Public
        </a>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kategoriModal">
            <i class="fas fa-plus me-2"></i>Tambah Kategori
        </button>
    </div>
</div>

<!-- Alerts -->
<?php if ($error): ?>
    <?php echo showAlert($error, 'danger'); ?>
<?php endif; ?>

<?php if ($success): ?>
    <?php echo showAlert($success, 'success'); ?>
<?php endif; ?>

<div class="row">
    <!-- Kategori Cards -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>
                    Daftar Kategori
                </h5>
            </div>
            
            <div class="card-body p-0">
                <?php if (empty($kategoriList)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-tags fs-1 text-muted mb-3"></i>
                    <h5 class="text-muted">Belum ada kategori</h5>
                    <p class="text-muted">Klik tombol "Tambah Kategori" untuk menambahkan kategori baru</p>
                </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nama Kategori</th>
                                <th>Jumlah Wisata</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($kategoriList as $kategori): ?>
                            <?php
                            // Get wisata count for this category
                            $wisataResponse = makeApiRequest('wisata?kategori=' . urlencode($kategori['nama']));
                            $wisataCount = $wisataResponse['success'] ? count($wisataResponse['data']) : 0;
                            ?>
                            <tr>
                                <td class="fw-bold">#<?php echo $kategori['id']; ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-tag text-primary me-2"></i>
                                        <strong><?php echo htmlspecialchars($kategori['nama']); ?></strong>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        <?php echo $wisataCount; ?> wisata
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="wisata.php?kategori=<?php echo urlencode($kategori['nama']); ?>" 
                                           class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-warning" 
                                                onclick="editKategori(<?php echo htmlspecialchars(json_encode($kategori)); ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="?delete=<?php echo $kategori['id']; ?>" 
                                           class="btn btn-sm btn-outline-danger"
                                           onclick="return confirm('Yakin ingin menghapus kategori ini? Semua wisata dengan kategori ini akan kehilangan kategorinya.')">
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
    </div>
    
    <!-- Statistics -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>
                    Statistik Kategori
                </h6>
            </div>
            <div class="card-body">
                <?php if (!empty($kategoriList)): ?>
                    <?php 
                    $totalWisata = 0;
                    $categoryStats = [];
                    
                    foreach ($kategoriList as $kategori) {
                        $wisataResponse = makeApiRequest('wisata?kategori=' . urlencode($kategori['nama']));
                        $count = $wisataResponse['success'] ? count($wisataResponse['data']) : 0;
                        $totalWisata += $count;
                        $categoryStats[] = ['name' => $kategori['nama'], 'count' => $count];
                    }
                    
                    // Sort by count descending
                    usort($categoryStats, function($a, $b) {
                        return $b['count'] - $a['count'];
                    });
                    ?>
                    
                    <div class="text-center mb-3">
                        <h4 class="text-primary"><?php echo $totalWisata; ?></h4>
                        <p class="text-muted mb-0">Total Wisata</p>
                    </div>
                    
                    <?php foreach ($categoryStats as $stat): ?>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <small class="fw-medium"><?php echo htmlspecialchars($stat['name']); ?></small>
                            <small class="text-muted"><?php echo $stat['count']; ?></small>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar" 
                                 style="width: <?php echo $totalWisata > 0 ? ($stat['count'] / $totalWisata * 100) : 0; ?>%">
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center text-muted">
                        <i class="fas fa-chart-pie fs-2 mb-2"></i>
                        <p class="mb-0">Belum ada data statistik</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>
                    Aksi Cepat
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="admin_wisata.php" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-mountain me-2"></i>Kelola Wisata
                    </a>
                    <a href="admin_users.php" class="btn btn-outline-success btn-sm">
                        <i class="fas fa-users me-2"></i>Kelola Users
                    </a>
                    <a href="admin_admins.php" class="btn btn-outline-warning btn-sm">
                        <i class="fas fa-user-shield me-2"></i>Kelola Admin
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Form -->
<div class="modal fade" id="kategoriModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">
                        <i class="fas fa-plus me-2"></i>Tambah Kategori
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
                    <input type="hidden" name="action" id="formAction" value="create">
                    <input type="hidden" name="id" id="kategoriId">
                    
                    <div class="mb-3">
                        <label for="nama" class="form-label">
                            <i class="fas fa-tag me-2"></i>Nama Kategori *
                        </label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                        <div class="form-text">Contoh: Alam, Budaya, Sejarah, Kuliner, dll.</div>
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
function editKategori(kategori) {
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit me-2"></i>Edit Kategori';
    document.getElementById('formAction').value = 'update';
    document.getElementById('kategoriId').value = kategori.id;
    document.getElementById('nama').value = kategori.nama;
    document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save me-2"></i>Update';
    
    new bootstrap.Modal(document.getElementById('kategoriModal')).show();
}

// Reset form when modal is closed
document.getElementById('kategoriModal').addEventListener('hidden.bs.modal', function() {
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-plus me-2"></i>Tambah Kategori';
    document.getElementById('formAction').value = 'create';
    document.getElementById('kategoriId').value = '';
    document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save me-2"></i>Simpan';
    document.querySelector('#kategoriModal form').reset();
});
</script>

<?php include 'footer.php'; ?>
