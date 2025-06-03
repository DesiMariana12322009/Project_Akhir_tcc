<?php
require_once 'config.php';
$title = 'Wisata - Wisata Indonesia';

// Get search parameters
$search = $_GET['search'] ?? '';
$kategori = $_GET['kategori'] ?? '';

// Build query parameters for search
$queryParams = [];
if ($search) $queryParams[] = 'search=' . urlencode($search);
if ($kategori) $queryParams[] = 'kategori=' . urlencode($kategori);

$queryString = !empty($queryParams) ? '?' . implode('&', $queryParams) : '';

// Get wisata data - use search endpoint if there are search parameters, otherwise use regular endpoint
if ($search || $kategori) {
    $wisataResponse = makeApiRequest('wisata/search' . $queryString);
    // Handle the new search response format
    if ($wisataResponse['success'] && isset($wisataResponse['data']['data'])) {
        $wisataList = $wisataResponse['data']['data'];
    } else {
        $wisataList = [];
    }
} else {
    $wisataResponse = makeApiRequest('wisata');
    $wisataList = $wisataResponse['success'] ? $wisataResponse['data'] : [];
}

// Get categories for filter
$kategoriResponse = makeApiRequest('kategori');
$kategoriList = $kategoriResponse['success'] ? $kategoriResponse['data'] : [];

include 'header.php';
?>

<!-- Search and Filter Section -->
<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title">
            <i class="fas fa-search me-2"></i>
            Cari & Filter Wisata
        </h5>
        
        <form method="GET" class="row g-3" id="searchForm">
            <div class="col-md-6">
                <label for="search" class="form-label">Cari Wisata</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" class="form-control" id="search" name="search" 
                           placeholder="Masukkan nama wisata atau deskripsi..."
                           value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <div class="form-text">Cari berdasarkan nama wisata atau deskripsi</div>
            </div>
            
            <div class="col-md-4">
                <label for="kategori" class="form-label">Kategori</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-tag"></i>
                    </span>
                    <select class="form-select" id="kategori" name="kategori">
                        <option value="">Semua Kategori</option>
                        <?php foreach ($kategoriList as $kat): ?>
                        <option value="<?php echo htmlspecialchars($kat['nama']); ?>"
                                <?php echo $kategori === $kat['nama'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($kat['nama']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>Cari
                    </button>
                    <?php if ($search || $kategori): ?>
                    <a href="wisata.php" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-times me-1"></i>Reset
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
        
        <?php if ($search || $kategori): ?>
        <div class="mt-3 p-3 bg-light rounded">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Hasil pencarian untuk: 
                        <?php if ($search): ?>
                            <span class="badge bg-primary mx-1">
                                <i class="fas fa-search me-1"></i>
                                "<?php echo htmlspecialchars($search); ?>"
                            </span>
                        <?php endif; ?>
                        <?php if ($kategori): ?>
                            <span class="badge bg-success mx-1">
                                <i class="fas fa-tag me-1"></i>
                                <?php echo htmlspecialchars($kategori); ?>
                            </span>
                        <?php endif; ?>
                        <span class="text-primary fw-bold">(<?php echo count($wisataList); ?> hasil)</span>
                    </small>
                </div>
                <a href="wisata.php" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-times me-1"></i> Reset Pencarian
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Results Section -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>
        <i class="fas fa-mountain me-2"></i>
        Daftar Wisata
        <span class="badge bg-primary"><?php echo count($wisataList); ?></span>
    </h2>
    
    <?php if (isAdmin()): ?>
    <a href="admin_wisata.php" class="btn btn-outline-primary">
        <i class="fas fa-plus me-2"></i>Tambah Wisata
    </a>
    <?php endif; ?>
</div>

<?php if (empty($wisataList)): ?>
<div class="text-center py-5">
    <div class="card border-0 shadow-sm mx-auto" style="max-width: 500px;">
        <div class="card-body">
            <i class="fas fa-search fs-1 text-muted mb-3"></i>
            <?php if ($search || $kategori): ?>
                <h4 class="text-muted">Tidak ada wisata ditemukan</h4>
                <p class="text-muted mb-3">
                    Tidak ditemukan wisata yang sesuai dengan pencarian Anda.
                </p>
                <div class="d-grid gap-2 d-md-block">
                    <button type="button" class="btn btn-outline-primary" onclick="clearSearch()">
                        <i class="fas fa-eraser me-2"></i>Hapus Filter
                    </button>
                    <a href="wisata.php" class="btn btn-primary">
                        <i class="fas fa-eye me-2"></i>Lihat Semua Wisata
                    </a>
                </div>
            <?php else: ?>
                <h4 class="text-muted">Belum ada wisata tersedia</h4>
                <p class="text-muted mb-3">
                    Saat ini belum ada data wisata yang tersedia.
                </p>
                <?php if (isAdmin()): ?>
                <a href="admin_wisata.php" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Tambah Wisata Pertama
                </a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php else: ?>
<div class="row">
    <?php foreach ($wisataList as $wisata): ?>
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card h-100 shadow-sm border-0">
            <?php if (!empty($wisata['url_gambar'])): ?>
            <img src="<?php echo htmlspecialchars($wisata['url_gambar']); ?>" 
                 class="card-img-top" style="height: 250px; object-fit: cover;" 
                 alt="<?php echo htmlspecialchars($wisata['nama']); ?>">
            <?php else: ?>
            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                 style="height: 250px;">
                <i class="fas fa-image text-muted fs-1"></i>
            </div>
            <?php endif; ?>
            
            <div class="card-body d-flex flex-column">
                <h5 class="card-title">
                    <?php echo htmlspecialchars($wisata['nama']); ?>
                </h5>
                
                <p class="card-text text-muted mb-2">
                    <i class="fas fa-map-marker-alt me-2"></i>
                    <?php echo htmlspecialchars($wisata['lokasi']); ?>
                </p>
                
                <?php if (!empty($wisata['kategori'])): ?>
                <p class="card-text mb-2">
                    <span class="badge bg-success">
                        <i class="fas fa-tag me-1"></i>
                        <?php echo htmlspecialchars($wisata['kategori']); ?>
                    </span>
                </p>
                <?php endif; ?>
                
                <p class="card-text flex-grow-1">
                    <?php 
                    $description = htmlspecialchars($wisata['deskripsi']);
                    echo strlen($description) > 120 ? substr($description, 0, 120) . '...' : $description; 
                    ?>
                </p>
                
                <div class="mt-auto">
                    <a href="wisata_detail.php?id=<?php echo $wisata['id']; ?>" 
                       class="btn btn-primary w-100">
                        <i class="fas fa-eye me-2"></i>
                        Lihat Detail
                    </a>
                </div>
            </div>
            
            <?php if (isAdmin()): ?>
            <div class="card-footer bg-light">
                <div class="btn-group w-100" role="group">
                    <a href="admin_wisata.php?edit=<?php echo $wisata['id']; ?>" 
                       class="btn btn-sm btn-outline-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="admin_wisata.php?delete=<?php echo $wisata['id']; ?>" 
                       class="btn btn-sm btn-outline-danger"
                       onclick="return confirm('Yakin ingin menghapus wisata ini?')">
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

<!-- Floating Action Button for Admin -->
<?php if (isAdmin()): ?>
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050;">
    <a href="admin_wisata.php" class="btn btn-primary btn-lg rounded-circle shadow" 
       title="Tambah Wisata Baru">
        <i class="fas fa-plus"></i>
    </a>
</div>
<?php endif; ?>

<!-- JavaScript for enhanced search functionality -->
<script>
// Function to clear search filters
function clearSearch() {
    document.getElementById('search').value = '';
    document.getElementById('kategori').value = '';
    document.getElementById('searchForm').submit();
}

// Auto-submit form when category changes
document.getElementById('kategori').addEventListener('change', function() {
    document.getElementById('searchForm').submit();
});

// Add enter key support for search input
document.getElementById('search').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        document.getElementById('searchForm').submit();
    }
});

// Add loading state to search button
document.getElementById('searchForm').addEventListener('submit', function() {
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mencari...';
    submitBtn.disabled = true;
});

// Highlight search terms in results
document.addEventListener('DOMContentLoaded', function() {
    const searchTerm = '<?php echo addslashes($search); ?>';
    if (searchTerm) {
        highlightSearchTerm(searchTerm);
    }
});

function highlightSearchTerm(term) {
    if (!term) return;
    
    const cards = document.querySelectorAll('.card-title, .card-text');
    cards.forEach(function(element) {
        const regex = new RegExp(`(${term})`, 'gi');
        element.innerHTML = element.innerHTML.replace(regex, '<mark class="bg-warning">$1</mark>');
    });
}
</script>

<?php include 'footer.php'; ?>