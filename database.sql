-- Database: pelanggaran_siswa

CREATE DATABASE pelanggaran_siswa;
USE pelanggaran_siswa;

-- Tabel users (admin dan guru piket)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'guru') NOT NULL
);

-- Tabel siswa
CREATE TABLE siswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    kelas VARCHAR(20) NOT NULL,
    jenis_kelamin ENUM('L', 'P') NOT NULL
);

-- Tabel jenis pelanggaran
CREATE TABLE jenis_pelanggaran (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_pelanggaran VARCHAR(100) NOT NULL
);

-- Tabel pelanggaran
CREATE TABLE pelanggaran (
    id INT AUTO_INCREMENT PRIMARY KEY,
    siswa_id INT NOT NULL,
    tanggal DATE NOT NULL,
    jenis_id INT NOT NULL,
    catatan TEXT,
    FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE,
    FOREIGN KEY (jenis_id) REFERENCES jenis_pelanggaran(id) ON DELETE CASCADE
);
