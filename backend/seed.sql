
INSERT INTO `kategori` (`id`, `nama`) VALUES
(1, 'Pantai'),
(2, 'Gunung'),
(3, 'Sejarah'),
(4, 'Kuliner'),
(5, 'Alam');


-- Wisata
INSERT INTO `wisata` (`id`, `nama`, `deskripsi`, `lokasi`, `url_gambar`, `kategori`, `createdAt`, `updatedAt`) VALUES
(1, 'Pantai Kuta', 'Pantai terkenal di Bali dengan pasir putih dan ombak yang cocok untuk berselancar.', 'Bali, Indonesia', 'https://example.com/kuta.jpg', 'Pantai', NOW(), NOW()),
(2, 'Gunung Bromo', 'Gunung berapi aktif yang terkenal dengan pemandangan matahari terbitnya.', 'Jawa Timur, Indonesia', 'https://example.com/bromo.jpg', 'Gunung', NOW(), NOW()),
(3, 'Candi Borobudur', 'Candi Buddha terbesar di dunia, sebuah mahakarya arsitektur kuno.', 'Jawa Tengah, Indonesia', 'https://example.com/borobudur.jpg', 'Sejarah', NOW(), NOW()),
(4, 'Pasar Terapung Lok Baintan', 'Pasar tradisional di atas sungai yang menjual berbagai macam barang.', 'Kalimantan Selatan, Indonesia', 'https://example.com/lokbaintan.jpg', 'Kuliner', NOW(), NOW()),
(5, 'Taman Nasional Komodo', 'Habitat asli komodo, kadal terbesar di dunia.', 'Nusa Tenggara Timur, Indonesia', 'https://example.com/komodo.jpg', 'Alam', NOW(), NOW()),
(6, 'Pantai Pink', 'Pantai dengan pasir berwarna merah muda yang unik.', 'Lombok, Indonesia', 'https://example.com/pinkbeach.jpg', 'Pantai', NOW(), NOW()),
(7, 'Gunung Rinjani', 'Gunung berapi tertinggi kedua di Indonesia dengan danau kawah yang indah.', 'Lombok, Indonesia', 'https://example.com/rinjani.jpg', 'Gunung', NOW(), NOW()),
(8, 'Kota Tua Jakarta', 'Kawasan bersejarah dengan bangunan-bangunan peninggalan kolonial Belanda.', 'Jakarta, Indonesia', 'https://example.com/kotatua.jpg', 'Sejarah', NOW(), NOW()),
(9, 'Nasi Goreng Kebon Sirih', 'Tempat makan nasi goreng legendaris di Jakarta.', 'Jakarta, Indonesia', 'https://example.com/nasgorkebonsirih.jpg', 'Kuliner', NOW(), NOW()),
(10, 'Danau Toba', 'Danau vulkanik terbesar di dunia dengan pulau Samosir di tengahnya.', 'Sumatera Utara, Indonesia', 'https://example.com/danautoba.jpg', 'Alam', NOW(), NOW());
