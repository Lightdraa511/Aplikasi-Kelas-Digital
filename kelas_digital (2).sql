-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 07, 2025 at 01:09 AM
-- Server version: 8.0.30
-- PHP Version: 7.4.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kelas_digital`
--

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id` int NOT NULL,
  `nama_kelas` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nama kelas (contoh: XII IPA 1)',
  `mata_pelajaran` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nama mata pelajaran',
  `deskripsi` text COLLATE utf8mb4_unicode_ci COMMENT 'Deskripsi kelas',
  `guru_id` int NOT NULL COMMENT 'ID guru yang mengajar',
  `periode_akademik_id` int NOT NULL COMMENT 'ID periode akademik',
  `is_active` tinyint(1) DEFAULT '1' COMMENT '1=aktif, 0=nonaktif',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel kelas pembelajaran';

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id`, `nama_kelas`, `mata_pelajaran`, `deskripsi`, `guru_id`, `periode_akademik_id`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 'A1', 'MAT', 'AAA', 2, 5, 1, '2025-07-04 20:20:20', '2025-07-05 03:20:20');

-- --------------------------------------------------------

--
-- Table structure for table `kelas_siswa`
--

CREATE TABLE `kelas_siswa` (
  `id` int NOT NULL,
  `kelas_id` int NOT NULL COMMENT 'ID kelas',
  `siswa_id` int NOT NULL COMMENT 'ID siswa',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel relasi kelas dan siswa';

--
-- Dumping data for table `kelas_siswa`
--

INSERT INTO `kelas_siswa` (`id`, `kelas_id`, `siswa_id`, `created_at`) VALUES
(5, 2, 5, '2025-07-04 20:20:27'),
(6, 2, 7, '2025-07-04 20:20:33'),
(7, 2, 9, '2025-07-04 20:20:38');

-- --------------------------------------------------------

--
-- Table structure for table `pengumpulan_tugas`
--

CREATE TABLE `pengumpulan_tugas` (
  `id` int NOT NULL,
  `tugas_id` int NOT NULL COMMENT 'ID tugas',
  `siswa_id` int NOT NULL COMMENT 'ID siswa',
  `file_jawaban` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Path file jawaban',
  `file_previous` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Path file jawaban sebelumnya (backup)',
  `nilai` int DEFAULT NULL COMMENT 'Nilai tugas (0-100)',
  `feedback` text COLLATE utf8mb4_unicode_ci COMMENT 'Feedback dari guru',
  `is_late` tinyint(1) DEFAULT '0' COMMENT '1=terlambat, 0=tepat waktu',
  `submitted_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu pengumpulan',
  `graded_at` timestamp NULL DEFAULT NULL COMMENT 'Waktu pemberian nilai'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel pengumpulan tugas';

--
-- Dumping data for table `pengumpulan_tugas`
--

INSERT INTO `pengumpulan_tugas` (`id`, `tugas_id`, `siswa_id`, `file_jawaban`, `file_previous`, `nilai`, `feedback`, `is_late`, `submitted_at`, `graded_at`) VALUES
(2, 8, 5, '5a169d5f0ac186875735b5abad9a5f73.pdf', '8383bd676ccab03d252dad254531ac45.pdf', NULL, NULL, 0, '2025-07-04 20:32:25', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `periode_akademik`
--

CREATE TABLE `periode_akademik` (
  `id` int NOT NULL,
  `tahun_ajaran` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Format: YYYY/YYYY (contoh: 2024/2025)',
  `semester` enum('Ganjil','Genap') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Ganjil atau Genap',
  `is_active` tinyint(1) DEFAULT '0' COMMENT '1=periode aktif, 0=nonaktif (hanya 1 yang boleh aktif)',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel periode akademik';

--
-- Dumping data for table `periode_akademik`
--

INSERT INTO `periode_akademik` (`id`, `tahun_ajaran`, `semester`, `is_active`, `created_at`, `updated_at`) VALUES
(5, '2024/2025', 'Ganjil', 1, '2025-07-04 20:17:56', '2025-07-04 20:18:00');

-- --------------------------------------------------------

--
-- Table structure for table `tugas`
--

CREATE TABLE `tugas` (
  `id` int NOT NULL,
  `kelas_id` int NOT NULL COMMENT 'ID kelas',
  `judul` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Judul tugas',
  `deskripsi` text COLLATE utf8mb4_unicode_ci COMMENT 'Deskripsi tugas',
  `file_materi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Path file materi (opsional)',
  `deadline` datetime NOT NULL COMMENT 'Batas waktu pengumpulan',
  `max_poin` int DEFAULT '100' COMMENT 'Nilai maksimal (default 100)',
  `status` enum('draft','published','closed') COLLATE utf8mb4_unicode_ci DEFAULT 'draft' COMMENT 'Status tugas',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel tugas';

--
-- Dumping data for table `tugas`
--

INSERT INTO `tugas` (`id`, `kelas_id`, `judul`, `deskripsi`, `file_materi`, `deadline`, `max_poin`, `status`, `created_at`, `updated_at`) VALUES
(8, 2, 'VV', 'SS', '07b2c54dbe457a3ef5180deba21074f0.pdf', '2025-07-05 11:22:00', 100, 'published', '2025-07-04 20:21:20', '2025-07-05 03:21:20');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `nisn_nip` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'NISN untuk siswa, NIP untuk guru, username untuk admin',
  `nama_lengkap` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nama lengkap pengguna',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Password yang di-hash',
  `role` enum('super_admin','guru','siswa') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Role pengguna',
  `is_active` tinyint(1) DEFAULT '1' COMMENT '1=aktif, 0=nonaktif',
  `force_change_password` tinyint(1) DEFAULT '1' COMMENT '1=harus ubah password, 0=tidak',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel pengguna sistem';

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nisn_nip`, `nama_lengkap`, `password`, `role`, `is_active`, `force_change_password`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Super Administrator', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'super_admin', 1, 0, '2025-06-27 23:24:30', '2025-06-27 23:24:30'),
(2, '1990123456789012', 'Budi Santoso, S.Pd', '$2y$10$A.68OUpCyCDr53Bqr5DmXOnQjoXkatS/LWkKyhFi59OYCQwpJLg5a', 'guru', 1, 0, '2025-06-27 23:24:30', '2025-07-04 20:19:57'),
(3, '1985067891234567', 'Siti Nurhaliza, S.Si', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'guru', 1, 1, '2025-06-27 23:24:30', '2025-06-27 23:24:30'),
(4, '1988032145678901', 'Ahmad Fauzi, S.Pd', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'guru', 1, 1, '2025-06-27 23:24:30', '2025-06-27 23:24:30'),
(5, '0123456789', 'Andi Pratama', '$2y$10$N3zQi0Pwh7nyje2Nh5PSb.AwLIsOzryWx0cybJFEVcVKaIvT6r32C', 'siswa', 1, 0, '2025-06-27 23:24:30', '2025-06-27 17:22:25'),
(6, '0123456790', 'Sari Indah', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'siswa', 1, 1, '2025-06-27 23:24:30', '2025-06-27 23:24:30'),
(7, '0123456791', 'Deni Kurniawan', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'siswa', 1, 1, '2025-06-27 23:24:30', '2025-06-27 23:24:30'),
(8, '0123456792', 'Maya Sari', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'siswa', 1, 1, '2025-06-27 23:24:30', '2025-06-27 23:24:30'),
(9, '0123456793', 'Riko Saputra', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'siswa', 1, 1, '2025-06-27 23:24:30', '2025-06-27 23:24:30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_kelas_guru` (`guru_id`),
  ADD KEY `fk_kelas_periode` (`periode_akademik_id`),
  ADD KEY `idx_active` (`is_active`),
  ADD KEY `idx_guru_periode` (`guru_id`,`periode_akademik_id`);

--
-- Indexes for table `kelas_siswa`
--
ALTER TABLE `kelas_siswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_kelas_siswa` (`kelas_id`,`siswa_id`),
  ADD KEY `fk_kelas_siswa_kelas` (`kelas_id`),
  ADD KEY `fk_kelas_siswa_siswa` (`siswa_id`);

--
-- Indexes for table `pengumpulan_tugas`
--
ALTER TABLE `pengumpulan_tugas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_tugas_siswa` (`tugas_id`,`siswa_id`),
  ADD KEY `fk_pengumpulan_tugas` (`tugas_id`),
  ADD KEY `fk_pengumpulan_siswa` (`siswa_id`),
  ADD KEY `idx_late` (`is_late`),
  ADD KEY `idx_submitted` (`submitted_at`),
  ADD KEY `idx_siswa_submitted` (`siswa_id`,`submitted_at`);

--
-- Indexes for table `periode_akademik`
--
ALTER TABLE `periode_akademik`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_periode` (`tahun_ajaran`,`semester`),
  ADD KEY `idx_active` (`is_active`),
  ADD KEY `idx_tahun` (`tahun_ajaran`);

--
-- Indexes for table `tugas`
--
ALTER TABLE `tugas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tugas_kelas` (`kelas_id`),
  ADD KEY `idx_deadline` (`deadline`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_kelas_status` (`kelas_id`,`status`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nisn_nip` (`nisn_nip`),
  ADD KEY `idx_nisn_nip` (`nisn_nip`),
  ADD KEY `idx_role` (`role`),
  ADD KEY `idx_active` (`is_active`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `kelas_siswa`
--
ALTER TABLE `kelas_siswa`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `pengumpulan_tugas`
--
ALTER TABLE `pengumpulan_tugas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `periode_akademik`
--
ALTER TABLE `periode_akademik`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tugas`
--
ALTER TABLE `tugas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `kelas`
--
ALTER TABLE `kelas`
  ADD CONSTRAINT `fk_kelas_guru` FOREIGN KEY (`guru_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_kelas_periode` FOREIGN KEY (`periode_akademik_id`) REFERENCES `periode_akademik` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kelas_siswa`
--
ALTER TABLE `kelas_siswa`
  ADD CONSTRAINT `fk_kelas_siswa_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_kelas_siswa_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pengumpulan_tugas`
--
ALTER TABLE `pengumpulan_tugas`
  ADD CONSTRAINT `fk_pengumpulan_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_pengumpulan_tugas` FOREIGN KEY (`tugas_id`) REFERENCES `tugas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tugas`
--
ALTER TABLE `tugas`
  ADD CONSTRAINT `fk_tugas_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
