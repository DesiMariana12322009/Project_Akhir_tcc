<?php
require_once 'config.php';

$wisataId = $_GET['id'] ?? null;

if (!$wisataId) {
    header('Location: wisata.php');
    exit();
}

// Get wisata detail
$wisataResponse = makeApiRequest('wisata/' . $wisataId);

if (!$wisataResponse['success']) {
    header('Location: wisata.php');
    exit();
}

$wisata = $wisataResponse['data'];
$title = htmlspecialchars($wisata['nama']) . ' - Detail Wisata';

include 'header.php';
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="index.php" class="text-decoration-none">
                <i class="fas fa-home"></i> Home
            </a>
        </li>
        <li class="breadcrumb-item">
            <a href="wisata.php" class="text-decoration-none">Wisata</a>
        </li>
        <li class="breadcrumb-item active">
            <?php echo htmlspecialchars($wisata['nama']); ?>
        </li>
    </ol>
</nav>

<div class="row">
    <!-- Main Content -->
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <!-- Image -->
            <?php if (!empty($wisata['url_gambar'])): ?>
            <img src="<?php echo htmlspecialchars($wisata['url_gambar']); ?>" 
                 class="card-img-top" style="height: 400px; object-fit: cover;" 
                 alt="<?php echo htmlspecialchars($wisata['nama']); ?>">
            <?php else: ?>
            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                 style="height: 400px;">
                <i class="fas fa-image text-muted" style="font-size: 4rem;"></i>
            </div>
            <?php endif; ?>
            
            <div class="card-body">
                <!-- Title -->
                <h1 class="card-title mb-3">
                    <?php echo htmlspecialchars($wisata['nama']); ?>
                </h1>
                
                <!-- Location -->
                <p class="text-muted mb-3 fs-5">
                    <i class="fas fa-map-marker-alt me-2 text-danger"></i>
                    <?php echo htmlspecialchars($wisata['lokasi']); ?>
                </p>
                
                <!-- Category -->
                <?php if (!empty($wisata['kategori'])): ?>
                <div class="mb-3">
                    <span class="badge bg-success fs-6 px-3 py-2">
                        <i class="fas fa-tag me-2"></i>
                        <?php echo htmlspecialchars($wisata['kategori']); ?>
                    </span>
                </div>
                <?php endif; ?>
                
                <!-- Description -->
                <div class="mb-4">
                    <h5 class="mb-3">
                        <i class="fas fa-info-circle me-2 text-primary"></i>
                        Deskripsi
                    </h5>
                    <p class="text-justify lh-lg">
                        <?php echo nl2br(htmlspecialchars($wisata['deskripsi'])); ?>
                    </p>
                </div>
                
                <!-- Timestamps -->
                <?php if (!empty($wisata['createdAt']) || !empty($wisata['updatedAt'])): ?>
                <div class="border-top pt-3">
                    <div class="row text-muted small">
                        <?php if (!empty($wisata['createdAt'])): ?>
                        <div class="col-sm-6">
                            <i class="fas fa-calendar-plus me-2"></i>
                            Dibuat: <?php echo date('d M Y H:i', strtotime($wisata['createdAt'])); ?>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($wisata['updatedAt'])): ?>
                        <div class="col-sm-6">
                            <i class="fas fa-calendar-edit me-2"></i>
                            Diperbarui: <?php echo date('d M Y H:i', strtotime($wisata['updatedAt'])); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Admin Actions -->
        <?php if (isAdmin()): ?>
        <div class="card mt-4 border-warning">
            <div class="card-header bg-warning text-dark">
                <h6 class="mb-0">
                    <i class="fas fa-cog me-2"></i>
                    Panel Admin
                </h6>
            </div>
            <div class="card-body">
                <div class="btn-group w-100" role="group">
                    <a href="admin_wisata.php?edit=<?php echo $wisata['id']; ?>" 
                       class="btn btn-outline-warning">
                        <i class="fas fa-edit me-2"></i>
                        Edit Wisata
                    </a>
                    <a href="admin_wisata.php?delete=<?php echo $wisata['id']; ?>" 
                       class="btn btn-outline-danger"
                       onclick="return confirm('Yakin ingin menghapus wisata ini?')">
                        <i class="fas fa-trash me-2"></i>
                        Hapus Wisata
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Action Buttons -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="fas fa-share-alt me-2"></i>
                    Aksi
                </h6>
                
                <div class="d-grid gap-2">
                    <button class="btn btn-primary" onclick="shareWisata()">
                        <i class="fas fa-share me-2"></i>
                        Bagikan
                    </button>
                    
                    <button class="btn btn-outline-secondary" onclick="printWisata()">
                        <i class="fas fa-print me-2"></i>
                        Print
                    </button>
                    
                    <a href="wisata.php" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Quick Info -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="fas fa-info me-2"></i>
                    Informasi Singkat
                </h6>
                
                <div class="list-group list-group-flush">
                    <div class="list-group-item px-0 d-flex justify-content-between">
                        <span class="text-muted">ID Wisata:</span>
                        <span class="fw-bold">#<?php echo $wisata['id']; ?></span>
                    </div>
                    
                    <div class="list-group-item px-0 d-flex justify-content-between">
                        <span class="text-muted">Nama:</span>
                        <span class="fw-bold text-end" style="max-width: 60%;">
                            <?php echo htmlspecialchars($wisata['nama']); ?>
                        </span>
                    </div>
                    
                    <div class="list-group-item px-0 d-flex justify-content-between">
                        <span class="text-muted">Lokasi:</span>
                        <span class="fw-bold text-end" style="max-width: 60%;">
                            <?php echo htmlspecialchars($wisata['lokasi']); ?>
                        </span>
                    </div>
                    
                    <?php if (!empty($wisata['kategori'])): ?>
                    <div class="list-group-item px-0 d-flex justify-content-between">
                        <span class="text-muted">Kategori:</span>
                        <span class="badge bg-success">
                            <?php echo htmlspecialchars($wisata['kategori']); ?>
                        </span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Related Categories -->
        <?php if (!empty($wisata['kategori'])): ?>
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="fas fa-tags me-2"></i>
                    Wisata Serupa
                </h6>
                
                <a href="wisata.php?kategori=<?php echo urlencode($wisata['kategori']); ?>" 
                   class="btn btn-outline-success w-100">
                    <i class="fas fa-search me-2"></i>
                    Lihat Wisata <?php echo htmlspecialchars($wisata['kategori']); ?>
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Scripts -->
<script>
function shareWisata() {
    if (navigator.share) {
        navigator.share({
            title: '<?php echo addslashes($wisata['nama']); ?>',
            text: '<?php echo addslashes(substr($wisata['deskripsi'], 0, 100)); ?>...',
            url: window.location.href
        });
    } else {
        // Fallback: Copy to clipboard
        navigator.clipboard.writeText(window.location.href).then(function() {
            alert('Link berhasil disalin ke clipboard!');
        });
    }
}

function printWisata() {
    window.print();
}
</script>

<!-- Print Styles -->
<style media="print">
@media print {
    .navbar, .breadcrumb, footer, .btn, .card-footer {
        display: none !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
}
</style>

<?php include 'footer.php'; ?>
