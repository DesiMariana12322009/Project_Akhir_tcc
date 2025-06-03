<?php
require_once 'config.php';
$title = 'Home - Wisata Indonesia';

// Get featured wisata
$wisataResponse = makeApiRequest('wisata');
$wisataList = $wisataResponse['success'] ? $wisataResponse['data'] : [];

// Get categories
$kategoriResponse = makeApiRequest('kategori');
$kategoriList = $kategoriResponse['success'] ? $kategoriResponse['data'] : [];

include 'header.php';
?>

<!-- Hero Section -->
<div class="bg-primary text-white py-5 mb-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-3">
                    <i class="fas fa-mountain me-3"></i>
                    Jelajahi Wisata Indonesia
                </h1>
                <p class="lead mb-4">
                    Temukan destinasi wisata terbaik di seluruh nusantara. 
                    Dari pantai eksotis hingga pegunungan yang menawan.
                </p>
                <a href="wisata.php" class="btn btn-light btn-lg">
                    <i class="fas fa-search me-2"></i>
                    Mulai Jelajahi
                </a>
            </div>
            <div class="col-lg-6">
                <div class="text-center">
                    <i class="fas fa-globe-asia display-1 opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Section -->
<div class="row mb-5">
    <div class="col-md-4">
        <div class="card text-center border-0 shadow-sm h-100">
            <div class="card-body">
                <i class="fas fa-map-marker-alt text-primary fs-1 mb-3"></i>
                <h3 class="text-primary"><?php echo count($wisataList); ?></h3>
                <p class="text-muted mb-0">Destinasi Wisata</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center border-0 shadow-sm h-100">
            <div class="card-body">
                <i class="fas fa-tags text-success fs-1 mb-3"></i>
                <h3 class="text-success"><?php echo count($kategoriList); ?></h3>
                <p class="text-muted mb-0">Kategori Wisata</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center border-0 shadow-sm h-100">
            <div class="card-body">
                <i class="fas fa-users text-info fs-1 mb-3"></i>
                <h3 class="text-info">1000+</h3>
                <p class="text-muted mb-0">Wisatawan Terdaftar</p>
            </div>
        </div>
    </div>
</div>

<!-- Featured Wisata -->
<?php if (!empty($wisataList)): ?>
<div class="mb-5">
    <h2 class="text-center mb-4">
        <i class="fas fa-star text-warning me-2"></i>
        Destinasi Unggulan
    </h2>
    
    <div class="row">
        <?php 
        $featuredWisata = array_slice($wisataList, 0, 6);
        foreach ($featuredWisata as $wisata): 
        ?>
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <?php if (!empty($wisata['url_gambar'])): ?>
                <img src="<?php echo htmlspecialchars($wisata['url_gambar']); ?>" 
                     class="card-img-top" style="height: 200px; object-fit: cover;" 
                     alt="<?php echo htmlspecialchars($wisata['nama']); ?>">
                <?php else: ?>
                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                     style="height: 200px;">
                    <i class="fas fa-image text-muted fs-1"></i>
                </div>
                <?php endif; ?>
                
                <div class="card-body">
                    <h5 class="card-title">
                        <?php echo htmlspecialchars($wisata['nama']); ?>
                    </h5>
                    <p class="card-text text-muted">
                        <i class="fas fa-map-marker-alt me-1"></i>
                        <?php echo htmlspecialchars($wisata['lokasi']); ?>
                    </p>
                    <p class="card-text">
                        <?php echo htmlspecialchars(substr($wisata['deskripsi'], 0, 100)) . '...'; ?>
                    </p>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="wisata_detail.php?id=<?php echo $wisata['id']; ?>" 
                       class="btn btn-primary w-100">
                        <i class="fas fa-eye me-2"></i>
                        Lihat Detail
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <div class="text-center">
        <a href="wisata.php" class="btn btn-outline-primary btn-lg">
            <i class="fas fa-list me-2"></i>
            Lihat Semua Wisata
        </a>
    </div>
</div>
<?php endif; ?>

<!-- Categories -->
<?php if (!empty($kategoriList)): ?>
<div class="mb-5">
    <h2 class="text-center mb-4">
        <i class="fas fa-th-large text-primary me-2"></i>
        Kategori Wisata
    </h2>
    
    <div class="row justify-content-center">
        <?php foreach ($kategoriList as $kategori): ?>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
            <a href="wisata.php?kategori=<?php echo urlencode($kategori['nama']); ?>" 
               class="text-decoration-none">
                <div class="card text-center border-0 shadow-sm h-100 hover-shadow">
                    <div class="card-body">
                        <i class="fas fa-tag text-primary fs-2 mb-3"></i>
                        <h6 class="card-title text-dark">
                            <?php echo htmlspecialchars($kategori['nama']); ?>
                        </h6>
                    </div>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Call to Action -->
<?php if (!isLoggedIn()): ?>
<div class="bg-light py-5 rounded">
    <div class="text-center">
        <h3 class="mb-3">Bergabunglah dengan Komunitas Wisata</h3>
        <p class="text-muted mb-4">
            Daftar sekarang dan dapatkan akses ke informasi wisata terlengkap
        </p>
        <a href="register.php" class="btn btn-primary btn-lg me-3">
            <i class="fas fa-user-plus me-2"></i>
            Daftar Sekarang
        </a>
        <a href="login.php" class="btn btn-outline-primary btn-lg">
            <i class="fas fa-sign-in-alt me-2"></i>
            Masuk
        </a>
    </div>
</div>
<?php endif; ?>

<style>
.hover-shadow {
    transition: all 0.3s ease;
}
.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15) !important;
}
</style>

<?php include 'footer.php'; ?>
