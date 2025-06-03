<?php
require_once 'config.php';
requireAdmin();

$title = 'Admin - Kelola Wisata';
$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'create') {
        $data = [
            'nama' => $_POST['nama'] ?? '',
            'lokasi' => $_POST['lokasi'] ?? '',
            'deskripsi' => $_POST['deskripsi'] ?? '',
            'kategori' => $_POST['kategori'] ?? '',
            'url_gambar' => $_POST['url_gambar'] ?? ''
        ];
        
        if (empty($data['nama']) || empty($data['lokasi']) || empty($data['deskripsi'])) {
            $error = 'Nama, lokasi, dan deskripsi harus diisi!';
        } else {
            $response = makeApiRequest('wisata', 'POST', $data, getToken());
            if ($response['success']) {
                $success = 'Wisata berhasil ditambahkan!';
            } else {
                $error = $response['data']['message'] ?? 'Gagal menambahkan wisata!';
            }
        }
    }
    
    elseif ($action === 'update') {
        $id = $_POST['id'] ?? '';
        $data = [
            'nama' => $_POST['nama'] ?? '',
            'lokasi' => $_POST['lokasi'] ?? '',
            'deskripsi' => $_POST['deskripsi'] ?? '',
            'kategori' => $_POST['kategori'] ?? '',
            'url_gambar' => $_POST['url_gambar'] ?? ''
        ];
        
        if (empty($data['nama']) || empty($data['lokasi']) || empty($data['deskripsi'])) {
            $error = 'Nama, lokasi, dan deskripsi harus diisi!';
        } else {
            $response = makeApiRequest('wisata/' . $id, 'PUT', $data, getToken());
            if ($response['success']) {
                $success = 'Wisata berhasil diperbarui!';
            } else {
                $error = $response['data']['message'] ?? 'Gagal memperbarui wisata!';
            }
        }
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $response = makeApiRequest('wisata/' . $_GET['delete'], 'DELETE', null, getToken());
    if ($response['success']) {
        $success = 'Wisata berhasil dihapus!';
    } else {
        $error = 'Gagal menghapus wisata!';
    }
}

// Get edit data
$editWisata = null;
if (isset($_GET['edit'])) {
    $response = makeApiRequest('wisata/' . $_GET['edit']);
    if ($response['success']) {
        $editWisata = $response['data'];
    }
}

// Get wisata list
$wisataResponse = makeApiRequest('wisata');
$wisataList = $wisataResponse['success'] ? $wisataResponse['data'] : [];

// Get categories
$kategoriResponse = makeApiRequest('kategori');
$kategoriList = $kategoriResponse['success'] ? $kategoriResponse['data'] : [];

include 'header.php';
?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="fas fa-cog me-2"></i>
        Kelola Wisata
        <span class="badge bg-primary"><?php echo count($wisataList); ?></span>
    </h2>
    
    <div>
        <a href="wisata.php" class="btn btn-outline-secondary me-2">
            <i class="fas fa-eye me-2"></i>Lihat Public
        </a>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#wisataModal">
            <i class="fas fa-plus me-2"></i>Tambah Wisata
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

<!-- Wisata Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-table me-2"></i>
            Daftar Wisata
        </h5>
    </div>
    
    <div class="card-body p-0">
        <?php if (empty($wisataList)): ?>
        <div class="text-center py-5">
            <i class="fas fa-mountain fs-1 text-muted mb-3"></i>
            <h5 class="text-muted">Belum ada wisata</h5>
            <p class="text-muted">Klik tombol "Tambah Wisata" untuk menambahkan wisata baru</p>
        </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Gambar</th>
                        <th>Nama</th>
                        <th>Lokasi</th>
                        <th>Kategori</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($wisataList as $wisata): ?>
                    <tr>
                        <td class="fw-bold">#<?php echo $wisata['id']; ?></td>
                        <td>
                            <?php if (!empty($wisata['url_gambar'])): ?>
                            <img src="<?php echo htmlspecialchars($wisata['url_gambar']); ?>" 
                                 class="rounded" style="width: 50px; height: 50px; object-fit: cover;"
                                 alt="<?php echo htmlspecialchars($wisata['nama']); ?>">
                            <?php else: ?>
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                 style="width: 50px; height: 50px;">
                                <i class="fas fa-image text-muted"></i>
                            </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?php echo htmlspecialchars($wisata['nama']); ?></strong>
                            <br>
                            <small class="text-muted">
                                <?php echo htmlspecialchars(substr($wisata['deskripsi'], 0, 50)) . '...'; ?>
                            </small>
                        </td>
                        <td><?php echo htmlspecialchars($wisata['lokasi']); ?></td>
                        <td>
                            <?php if (!empty($wisata['kategori'])): ?>
                            <span class="badge bg-success">
                                <?php echo htmlspecialchars($wisata['kategori']); ?>
                            </span>
                            <?php else: ?>
                            <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <small class="text-muted">
                                <?php echo date('d/m/Y', strtotime($wisata['createdAt'])); ?>
                            </small>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="wisata_detail.php?id=<?php echo $wisata['id']; ?>" 
                                   class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-warning" 
                                        onclick="editWisata(<?php echo htmlspecialchars(json_encode($wisata)); ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <a href="?delete=<?php echo $wisata['id']; ?>" 
                                   class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('Yakin ingin menghapus wisata ini?')">
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
<div class="modal fade" id="wisataModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">
                        <i class="fas fa-plus me-2"></i>Tambah Wisata
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
                    <input type="hidden" name="action" id="formAction" value="create">
                    <input type="hidden" name="id" id="wisataId">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama" class="form-label">
                                    <i class="fas fa-mountain me-2"></i>Nama Wisata *
                                </label>
                                <input type="text" class="form-control" id="nama" name="nama" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="lokasi" class="form-label">
                                    <i class="fas fa-map-marker-alt me-2"></i>Lokasi *
                                </label>
                                <input type="text" class="form-control" id="lokasi" name="lokasi" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kategori" class="form-label">
                                    <i class="fas fa-tag me-2"></i>Kategori
                                </label>
                                <select class="form-select" id="kategori" name="kategori">
                                    <option value="">Pilih Kategori</option>
                                    <?php foreach ($kategoriList as $kat): ?>
                                    <option value="<?php echo htmlspecialchars($kat['nama']); ?>">
                                        <?php echo htmlspecialchars($kat['nama']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="url_gambar" class="form-label">
                                    <i class="fas fa-image me-2"></i>URL Gambar
                                </label>
                                <input type="url" class="form-control" id="url_gambar" name="url_gambar">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">
                            <i class="fas fa-align-left me-2"></i>Deskripsi *
                        </label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" 
                                  rows="5" required></textarea>
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
function editWisata(wisata) {
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit me-2"></i>Edit Wisata';
    document.getElementById('formAction').value = 'update';
    document.getElementById('wisataId').value = wisata.id;
    document.getElementById('nama').value = wisata.nama;
    document.getElementById('lokasi').value = wisata.lokasi;
    document.getElementById('kategori').value = wisata.kategori || '';
    document.getElementById('url_gambar').value = wisata.url_gambar || '';
    document.getElementById('deskripsi').value = wisata.deskripsi;
    document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save me-2"></i>Update';
    
    new bootstrap.Modal(document.getElementById('wisataModal')).show();
}

// Reset form when modal is closed
document.getElementById('wisataModal').addEventListener('hidden.bs.modal', function() {
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-plus me-2"></i>Tambah Wisata';
    document.getElementById('formAction').value = 'create';
    document.getElementById('wisataId').value = '';
    document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save me-2"></i>Simpan';
    document.querySelector('#wisataModal form').reset();
});
</script>

<?php include 'footer.php'; ?>
