<?php
// TAHAP 1: INISIALISASI SESSION
// Memulai session PHP untuk menyimpan data user seperti token dan role
session_start();

// TAHAP 2: KONFIGURASI API
// Mendefinisikan URL dasar API yang akan digunakan untuk semua request
define('API_BASE_URL', 'https://tugas-tekweb.uc.r.appspot.com/api');

// TAHAP 3: FUNGSI UTAMA UNTUK MELAKUKAN REQUEST API
// Helper function to make API requests
function makeApiRequest($endpoint, $method = 'GET', $data = null, $token = null) {
    // TAHAP 3a: PERSIAPAN URL
    // Menggabungkan base URL dengan endpoint yang diminta
    $url = API_BASE_URL . '/' . ltrim($endpoint, '/');
    
    // TAHAP 3b: INISIALISASI CURL
    // Membuat curl handle untuk melakukan HTTP request
    $ch = curl_init();
    
    // TAHAP 3c: KONFIGURASI CURL DASAR
    // Mengatur URL target, return response sebagai string, method HTTP, dan timeout
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    // TAHAP 3d: PERSIAPAN HEADERS
    // Menyiapkan headers untuk request, termasuk Content-Type dan Authorization jika ada token
    $headers = ['Content-Type: application/json'];
    if ($token) {
        $headers[] = 'Authorization: Bearer ' . $token;
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    // TAHAP 3e: MENAMBAHKAN DATA JIKA ADA (UNTUK POST/PUT/PATCH)
    // Mengirim data dalam format JSON jika method memerlukan body
    if ($data && in_array($method, ['POST', 'PUT', 'PATCH'])) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    
    // TAHAP 3f: EKSEKUSI REQUEST DAN AMBIL RESPONSE
    // Menjalankan request dan mengambil response serta status code
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    // TAHAP 3g: RETURN RESPONSE TERSTRUKTUR
    // Mengembalikan response dalam format yang konsisten dengan data, status, dan flag success
    return [
        'data' => json_decode($response, true),
        'status' => $httpCode,
        'success' => $httpCode >= 200 && $httpCode < 300
    ];
}

// TAHAP 4: FUNGSI MANAJEMEN TOKEN
// Get current user token from session
function getToken() {
    // Mengambil token dari session, return null jika tidak ada
    return $_SESSION['token'] ?? null;
}

// TAHAP 5: FUNGSI PENGECEKAN STATUS LOGIN
// Check if user is logged in
function isLoggedIn() {
    // Memeriksa apakah user sudah login dengan mengecek keberadaan token di session
    return !empty($_SESSION['token']);
}

// TAHAP 6: FUNGSI PENGECEKAN ROLE ADMIN
// Check if user is admin
function isAdmin() {
    // Memeriksa apakah user memiliki role admin
    return !empty($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// TAHAP 7: FUNGSI PROTEKSI HALAMAN (AUTHENTICATION)
// Redirect to login if not authenticated
function requireAuth() {
    // Memaksa redirect ke login jika user belum login
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}

// TAHAP 8: FUNGSI PROTEKSI HALAMAN ADMIN (AUTHORIZATION)
// Redirect to login if not admin
function requireAdmin() {
    // Memaksa redirect ke login jika user bukan admin
    if (!isAdmin()) {
        header('Location: login.php');
        exit();
    }
}

// TAHAP 9: FUNGSI UNTUK MENAMPILKAN ALERT
// Show alert message
function showAlert($message, $type = 'info') {
    // Mengembalikan HTML alert Bootstrap dengan pesan dan tipe yang dapat di-dismiss
    return '<div class="alert alert-' . $type . ' alert-dismissible fade show" role="alert">
                ' . htmlspecialchars($message) . '
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>';
}

/*
RINGKASAN TAHAPAN KESELURUHAN:
1. Inisialisasi session untuk menyimpan data user
2. Konfigurasi URL API dasar
3. Fungsi utama untuk komunikasi API dengan tahapan:
   a. Persiapan URL
   b. Inisialisasi CURL
   c. Konfigurasi dasar CURL
   d. Persiapan headers HTTP
   e. Penambahan data untuk request body
   f. Eksekusi request dan pengambilan response
   g. Return response dalam format terstruktur
4. Fungsi manajemen token dari session
5. Pengecekan status login user
6. Pengecekan role admin user
7. Proteksi halaman dengan autentikasi
8. Proteksi halaman khusus admin dengan autorisasi
9. Fungsi utilitas untuk menampilkan pesan alert dengan Bootstrap CSS
*/
?>
