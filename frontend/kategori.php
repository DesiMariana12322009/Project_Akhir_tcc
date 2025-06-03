<?php
require_once 'config.php';
$title = 'Kategori - Wisata Indonesia';

// Get categories
$kategoriResponse = makeApiRequest('kategori');
$kategoriList = $kategoriResponse['success'] ? $kategoriResponse['data'] : [];

include 'header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="fas fa-tags me-2"></i>
        Kategori Wisata
        <span class="badge bg-primary"><?php echo count($kategoriList); ?></span>
    </h2>
    
    <?php if (isAdmin()): ?>
    <a href="admin_kategori.php" class="btn btn-outline-primary">
        <i class="fas fa-plus me-2"></i>Kelola Kategori
    </a>
    <?php endif; ?>
</div>

<?php if (empty($kategoriList)): ?>
<div class="text-center py-5">
    <i class="fas fa-tags fs-1 text-muted mb-3"></i>
    <h4 class="text-muted">Belum ada kategori tersedia</h4>
    <p class="text-muted">Kategori wisata akan ditampilkan di sini</p>
    <?php if (isAdmin()): ?>
    <a href="admin_kategori.php" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Tambah Kategori
    </a>
    <?php endif; ?>
</div>
<?php else: ?>
<div class="row">
    <?php foreach ($kategoriList as $kategori): ?>
    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
        <div class="card h-100 shadow-sm border-0 category-card">
            <div class="card-body text-center">
                <div class="category-icon mb-3">
                    <i class="fas fa-tag text-primary fs-1"></i>
                </div>
                
                <h5 class="card-title">
                    <?php echo htmlspecialchars($kategori['nama']); ?>
                </h5>
                
                <?php
                // Get count of wisata in this category
                $wisataResponse = makeApiRequest('wisata?kategori=' . urlencode($kategori['nama']));
                $wisataCount = $wisataResponse['success'] ? count($wisataResponse['data']) : 0;
                ?>
                
                <p class="card-text text-muted">
                    <i class="fas fa-map-marker-alt me-1"></i>
                    <?php echo $wisataCount; ?> destinasi
                </p>
                
                <a href="wisata.php?kategori=<?php echo urlencode($kategori['nama']); ?>" 
                   class="btn btn-primary w-100">
                    <i class="fas fa-eye me-2"></i>
                    Lihat Wisata
                </a>
            </div>
            
            <?php if (isAdmin()): ?>
            <div class="card-footer bg-light">
                <div class="btn-group w-100" role="group">
                    <a href="admin_kategori.php?edit=<?php echo $kategori['id']; ?>" 
                       class="btn btn-sm btn-outline-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="admin_kategori.php?delete=<?php echo $kategori['id']; ?>" 
                       class="btn btn-sm btn-outline-danger"
                       onclick="return confirm('Yakin ingin menghapus kategori ini?')">
                        <i class="fas fa-trash"></i> Hapus
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Category Statistics -->
<?php if (!empty($kategoriList)): ?>
<div class="row mt-5">
    <div class="col-12">
        <div class="card bg-light">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fas fa-chart-bar me-2"></i>
                    Statistik Kategori
                </h5>
                
                <div class="row">
                    <?php 
                    $totalWisata = 0;
                    foreach ($kategoriList as $kategori): 
                        $wisataResponse = makeApiRequest('wisata?kategori=' . urlencode($kategori['nama']));
                        $count = $wisataResponse['success'] ? count($wisataResponse['data']) : 0;
                        $totalWisata += $count;
                    ?>
                    <div class="col-md-3 mb-3">
                        <div class="text-center">
                            <h6 class="mb-1"><?php echo htmlspecialchars($kategori['nama']); ?></h6>
                            <div class="progress mb-2" style="height: 8px;">
                                <div class="progress-bar bg-primary" 
                                     style="width: <?php echo $totalWisata > 0 ? ($count / $totalWisata * 100) : 0; ?>%">
                                </div>
                            </div>
                            <small class="text-muted"><?php echo $count; ?> wisata</small>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="text-center mt-3">
                    <h4 class="text-primary mb-0">
                        <i class="fas fa-map-marked-alt me-2"></i>
                        Total: <?php echo $totalWisata; ?> Wisata
                    </h4>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Floating Action Button for Admin -->
<?php if (isAdmin()): ?>
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050;">
    <a href="admin_kategori.php" class="btn btn-success btn-lg rounded-circle shadow">
        <i class="fas fa-plus"></i>
    </a>
</div>
<?php endif; ?>

<style>
.category-card {
    transition: all 0.3s ease;
}

.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15) !important;
}

.category-icon {
    transition: all 0.3s ease;
}

.category-card:hover .category-icon {
    transform: scale(1.1);
}
</style>

<?php include 'footer.php'; ?>
