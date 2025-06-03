-- Active: 1737465415761@@127.0.0.1@3306@galeri_db
CREATE TABLE IF NOT EXISTS `wisata` (
  `id` INTEGER NOT NULL auto_increment ,
  `nama` VARCHAR(255),
  `deskripsi` TEXT,
  `lokasi` VARCHAR(255),
  `url_gambar` VARCHAR(255),
  `kategori` VARCHAR(255),
  `createdAt` DATETIME NOT NULL,
  `updatedAt` DATETIME NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `users` (
  `id` INTEGER NOT NULL auto_increment ,
  `username` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `createdAt` DATETIME NOT NULL,
  `updatedAt` DATETIME NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `kategori` (
  `id` INTEGER NOT NULL auto_increment ,
  `nama` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `admin` (
  `id` INTEGER NOT NULL auto_increment ,
  `username` VARCHAR(255) NOT NULL UNIQUE,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `createdAt` DATETIME NOT NULL,
  `updatedAt` DATETIME NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- CREATE TABLE IF NOT EXISTS `user_favorit_wisata` (
--   `id` INTEGER NOT NULL auto_increment,
--   `user_id` INTEGER NOT NULL,
--   `wisata_id` INTEGER NOT NULL,
--   `createdAt` DATETIME NOT NULL,
--   `updatedAt` DATETIME NOT NULL,
--   PRIMARY KEY (`id`),
--   FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
--   FOREIGN KEY (`wisata_id`) REFERENCES `wisata`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;