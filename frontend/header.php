<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Wisata Indonesia'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-map-marked-alt me-2"></i>
                Wisata Indonesia
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">
                            <i class="fas fa-home me-1"></i>Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="wisata.php">
                            <i class="fas fa-mountain me-1"></i>Wisata
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="kategori.php">
                            <i class="fas fa-tags me-1"></i>Kategori
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <?php if (isLoggedIn()): ?>
                        <?php if (isAdmin()): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-cog me-1"></i>Admin
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="admin_wisata.php">Kelola Wisata</a></li>
                                    <li><a class="dropdown-item" href="admin_kategori.php">Kelola Kategori</a></li>
                                    <li><a class="dropdown-item" href="admin_users.php">Kelola Users</a></li>
                                    <li><a class="dropdown-item" href="admin_admins.php">Kelola Admin</a></li>
                                </ul>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <span class="navbar-text me-3">
                                <i class="fas fa-user me-1"></i>
                                <?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?>
                            </span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">
                                <i class="fas fa-sign-out-alt me-1"></i>Logout
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">
                                <i class="fas fa-sign-in-alt me-1"></i>Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">
                                <i class="fas fa-user-plus me-1"></i>Register
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container mt-4">
