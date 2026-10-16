CREATE DATABASE IF NOT EXISTS perpustakaan
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE perpustakaan;

DROP TABLE IF EXISTS buku;

CREATE TABLE buku (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(150) NOT NULL,
    penulis VARCHAR(100) NOT NULL,
    penerbit VARCHAR(100) NOT NULL,
    tahun_terbit INT NOT NULL,
    stok INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO buku (judul, penulis, penerbit, tahun_terbit, stok) VALUES
('Cantik Itu Luka', 'Eka Kurniawan', ' Gramedia Pustaka Utama', 2004, 10),
('Perahu Kertas ', 'Dewi Lestari', 'Bentang Pustaka', 2009, 15),
('Sang Pemimpi', 'Andrea Hirata', ' Bentang Pustaka', 2008, 8),
('Harry Potter and the Prisoner of Azkaban', ' J.K. Rowling', 'Gramedia Pustaka Utama ', 2001, 5),
('Buku Carstenz', 'Doni Nurdiansyah ', ' Penerbit Bukunesia', 2025, 2),
('Keping Ingatan', 'Fitriani Nuraisyah ', ' Bentang Deepublish', 2018, 9);