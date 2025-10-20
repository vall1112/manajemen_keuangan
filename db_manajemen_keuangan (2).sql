-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 20, 2025 at 04:55 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_manajemen_keuangan`
--

-- --------------------------------------------------------

--
-- Table structure for table `bills`
--

CREATE TABLE `bills` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(50) NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `payment_type_id` bigint(20) UNSIGNED NOT NULL,
  `school_year_id` bigint(20) UNSIGNED NOT NULL,
  `total_tagihan` decimal(10,2) NOT NULL,
  `tanggal_tagih` date NOT NULL,
  `jatuh_tempo` date DEFAULT NULL,
  `status` enum('Pending','Belum Dibayar','Lunas') NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bills`
--

INSERT INTO `bills` (`id`, `kode`, `student_id`, `payment_type_id`, `school_year_id`, `total_tagihan`, `tanggal_tagih`, `jatuh_tempo`, `status`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 'KEG25D00788', 154, 1, 5, 1000000.00, '2025-09-05', '2025-12-31', 'Lunas', NULL, '2025-09-04 23:42:58', '2025-09-30 22:34:33'),
(2, 'KEG25D00745', 156, 1, 5, 2000000.00, '2025-09-05', '2025-07-31', 'Lunas', NULL, '2025-09-04 23:48:30', '2025-09-05 00:01:51'),
(3, 'KEG25E00745', 156, 2, 5, 800000.00, '2025-09-05', '2025-12-31', 'Lunas', NULL, '2025-09-05 01:30:59', '2025-09-30 22:33:33'),
(4, 'SPP25E00745', 156, 6, 5, 200000.00, '2025-09-05', '2025-08-07', 'Lunas', NULL, '2025-09-05 01:31:16', '2025-09-30 22:31:46'),
(5, 'KEG25D00728', 167, 1, 5, 500000.00, '2025-09-07', '2025-09-07', 'Lunas', NULL, '2025-09-07 02:45:02', '2025-09-30 22:30:37'),
(6, 'SPP25G33091', 161, 8, 5, 200000.00, '2025-09-07', '2025-08-01', 'Lunas', NULL, '2025-09-07 07:42:56', '2025-09-30 22:26:33'),
(7, 'KEG25D11053', 2, 1, 5, 1500000.00, '2025-10-01', '2025-10-31', 'Belum Dibayar', NULL, '2025-09-30 22:35:46', '2025-09-30 22:35:46'),
(8, 'KEG25G00745', 156, 4, 5, 500000.00, '2025-10-08', '2025-11-08', 'Lunas', NULL, '2025-10-08 09:26:28', '2025-10-08 09:55:41'),
(9, 'SPP25E00728', 167, 6, 5, 200000.00, '2025-10-09', '2025-11-09', 'Lunas', NULL, '2025-10-09 03:59:52', '2025-10-09 05:22:19'),
(10, 'KEG25E00728', 167, 2, 5, 800000.00, '2025-10-09', '2025-10-24', 'Belum Dibayar', NULL, '2025-10-09 04:51:18', '2025-10-09 04:51:18');

-- --------------------------------------------------------

--
-- Table structure for table `classrooms`
--

CREATE TABLE `classrooms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_kelas` varchar(255) NOT NULL,
  `major_id` bigint(20) UNSIGNED DEFAULT NULL,
  `wali_kelas_id` bigint(20) UNSIGNED DEFAULT NULL,
  `jumlah_anak` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `classrooms`
--

INSERT INTO `classrooms` (`id`, `nama_kelas`, `major_id`, `wali_kelas_id`, `jumlah_anak`, `created_at`, `updated_at`) VALUES
(1, 'XII RPL', 5, 36, 24, '2025-09-04 23:06:07', '2025-09-04 23:08:43'),
(2, 'XII TKJ A', 2, 39, 28, '2025-09-04 23:06:07', '2025-09-04 23:12:44'),
(3, 'XII TKJ B', 2, 24, 26, '2025-09-04 23:06:07', '2025-09-04 23:12:57'),
(4, 'XII TPM', 4, 20, 18, '2025-09-04 23:06:07', '2025-09-04 23:14:23'),
(5, 'XII TITL', 1, 18, 32, '2025-09-04 23:06:07', '2025-09-04 23:18:23'),
(6, 'XII TKR', 3, 12, 42, '2025-09-04 23:06:07', '2025-09-04 23:18:14'),
(7, 'XI TITL', 1, 33, 29, '2025-09-04 23:06:07', '2025-09-04 23:14:41'),
(8, 'XI TKJ', 2, 30, 33, '2025-09-04 23:06:07', '2025-09-04 23:18:42'),
(9, 'XI TKR', 3, 25, 17, '2025-09-04 23:06:07', '2025-09-04 23:18:03'),
(10, 'XI TPM', 4, 47, 7, '2025-09-04 23:06:07', '2025-09-04 23:19:12'),
(11, 'XI RPL', 5, 49, 21, '2025-09-04 23:06:07', '2025-09-04 23:14:56'),
(12, 'X TITL', 1, 19, 32, '2025-09-04 23:06:07', '2025-09-04 23:15:33'),
(13, 'X TKJ', 2, 38, 28, '2025-09-04 23:06:07', '2025-09-04 23:16:49'),
(14, 'X TKR', 3, 26, 20, '2025-09-04 23:06:07', '2025-09-04 23:19:58'),
(15, 'X TPM', 4, 29, 20, '2025-09-04 23:06:07', '2025-09-04 23:19:28'),
(16, 'X RPL', 5, 16, 20, '2025-09-04 23:06:07', '2025-09-04 23:06:07');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `majors`
--

CREATE TABLE `majors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(10) NOT NULL,
  `nama_jurusan` varchar(255) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `majors`
--

INSERT INTO `majors` (`id`, `kode`, `nama_jurusan`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 'TITL', 'Teknik Instalasi Tenaga Listrik', 'Fokus pada instalasi, perawatan, dan pengelolaan sistem kelistrikan', '2025-09-03 03:44:10', '2025-09-03 03:44:10'),
(2, 'TKJ', 'Teknik Komputer dan Jaringan', 'Fokus pada instalasi, konfigurasi, dan pemeliharaan komputer & jaringan', '2025-09-03 03:44:21', '2025-09-03 03:44:21'),
(3, 'TKR', 'Teknik Kendaraan Ringan', 'Fokus pada perbaikan, perawatan, dan manajemen kendaraan ringan', '2025-09-03 03:44:38', '2025-09-03 03:44:38'),
(4, 'TPm', 'Teknik Pemesinan', 'Fokus pada pembuatan, permesinan, dan pemeliharaan alat-alat industri', '2025-09-03 03:44:50', '2025-09-03 03:44:50'),
(5, 'RPL', 'Rekayasa Perangkat Lunak', 'Fokus pada pengembangan aplikasi, software, dan pemrograman', '2025-09-03 03:45:15', '2025-09-03 03:45:15');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2023_12_12_072543_create_permission_tables', 1),
(6, '2025_08_30_072509_create_settings_table', 1),
(7, '2025_08_31_040033_create_teachers_table', 2),
(8, '2025_08_31_130141_create_classrooms_table', 3),
(9, '2025_08_31_153302_create_students_table', 4),
(10, '2025_09_01_161732_create_payment_types_table', 5),
(11, '2025_09_01_171630_create_school_years_table', 6),
(13, '2025_09_01_183023_create_bills_table', 7),
(14, '2025_09_02_015157_create_transactions_table', 8),
(15, '2025_09_02_124821_add_username_to_users_table', 9),
(16, '2025_09_02_132356_update_dibayar_sisa_nullable_in_bills_table', 10),
(17, '2025_09_02_140848_add_bg_landingpage_to_settings_table', 10),
(18, '2025_09_02_145020_add_user_id_to_students_table', 11),
(20, '2025_09_03_011413_create_savings_table', 12),
(22, '2025_09_03_100816_create_majors_table', 13),
(23, '2025_09_03_104302_add_major_id_to_classrooms_table', 14),
(24, '2025_09_03_104601_add_foreign_key_major_id_to_classrooms_table', 15),
(25, '2025_09_02_150055_create_savings_table', 16),
(26, '2025_09_03_155738_remove_tanggal_from_savings_table', 16),
(27, '2025_09_03_183558_add_student_id_to_users_table', 16),
(28, '2025_09_04_034617_remove_tanggal_from_savings_table', 16),
(29, '2025_09_04_035237_add_student_id_to_users_table', 16),
(30, '2025_09_04_063530_seed_payment_types_table', 16),
(31, '2025_09_04_142445_add_bg_logosekolah_to_setting_table', 16),
(32, '2025_09_04_143305_add_kode_to_bills_table', 16),
(33, '2025_09_04_154605_add_kode_to_payment_types_table', 17),
(34, '2025_09_04_182204_migrate_students_table', 18),
(35, '2025_09_05_091454_add_kode_to_transactions_table', 19),
(36, '2025_09_05_094433_add_user_id_to_savings_and_transactions_table', 20),
(37, '2025_09_07_143109_add_jatuh_tempo_to_bills_table', 21),
(38, '2025_09_07_151513_add_status_to_users_table', 22),
(39, '2025_10_01_045028_create_saving_balances_table', 23),
(40, '2025_10_08_155858_add_payment_type_to_transactions_table', 24);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` char(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` char(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', '1'),
(2, 'App\\Models\\User', '5'),
(3, 'App\\Models\\User', '10'),
(3, 'App\\Models\\User', '11'),
(3, 'App\\Models\\User', '2'),
(3, 'App\\Models\\User', '3'),
(3, 'App\\Models\\User', '4'),
(3, 'App\\Models\\User', '8');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_types`
--

CREATE TABLE `payment_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(10) NOT NULL,
  `nama_jenis` varchar(255) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_types`
--

INSERT INTO `payment_types` (`id`, `kode`, `nama_jenis`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 'D', 'KEGIATAN 1 TAHUN TAHAP 1', 'Pembayaran tahap 1', '2025-09-04 08:56:57', '2025-09-04 11:37:54'),
(2, 'E', 'KEGIATAN 1 TAHUN TAHAP 2', 'Pembayaran tahap 2', '2025-09-04 08:57:24', '2025-09-04 11:37:45'),
(3, 'F', 'KEGIATAN 1 TAHUN TAHAP 3', 'Pembayaran tahap 3', '2025-09-04 08:57:53', '2025-09-04 11:37:37'),
(4, 'G', 'KEGIATAN 1 TAHUN TAHAP 4', 'Pembayaran tahap 4', '2025-09-04 08:58:13', '2025-09-04 11:37:28'),
(5, 'H', 'KEGIATAN 1 TAHUN TAHAP 5', 'Pembayaran tahap 5', '2025-09-04 08:58:29', '2025-09-04 11:37:19'),
(6, 'E', 'SPP BULAN JULI', NULL, '2025-09-04 09:01:15', '2025-09-04 09:05:42'),
(7, 'F', 'SPP BULAN AGUSTUS', NULL, '2025-09-04 09:01:46', '2025-09-04 09:01:46'),
(8, 'G', 'SPP BULAN SEPTEMBER', NULL, '2025-09-04 09:02:00', '2025-09-04 09:02:09'),
(9, 'H', 'SPP BULAN OKTOBER', NULL, '2025-09-04 09:02:27', '2025-09-04 09:02:27'),
(10, 'I', 'SPP BULAN NOVEMBER', NULL, '2025-09-04 09:03:19', '2025-09-04 09:03:19'),
(11, 'J', 'SPP BULAN DESEMBER', NULL, '2025-09-04 09:03:36', '2025-09-04 09:03:36'),
(12, 'K', 'SPP BULAN JANUARI', NULL, '2025-09-04 09:04:06', '2025-09-04 09:04:06'),
(13, 'L', 'SPP BULAN FEBRUARI', NULL, '2025-09-04 09:04:15', '2025-09-04 09:04:15'),
(14, 'M', 'SPP BULAN MARET', NULL, '2025-09-04 09:04:32', '2025-09-04 09:04:32'),
(15, 'N', 'SPP BULAN APRIL', NULL, '2025-09-04 09:04:42', '2025-09-04 09:04:57'),
(16, 'O', 'SPP BULAN MEI', NULL, '2025-09-04 09:05:07', '2025-09-04 09:05:07'),
(17, 'P', 'SPP BULAN JUNI', NULL, '2025-09-04 09:05:52', '2025-09-04 09:05:52');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'dashboard', 'api', '2025-08-30 11:37:12', '2025-08-30 11:37:12'),
(2, 'master', 'api', '2025-08-30 11:37:12', '2025-08-30 11:37:12'),
(3, 'master-user', 'api', '2025-08-30 11:37:12', '2025-08-30 11:37:12'),
(4, 'master-role', 'api', '2025-08-30 11:37:12', '2025-08-30 11:37:12'),
(5, 'website', 'api', '2025-08-30 11:37:12', '2025-08-30 11:37:12'),
(6, 'setting', 'api', '2025-08-30 11:37:12', '2025-08-30 11:37:12'),
(7, 'master-teacher', 'api', '2025-08-31 15:48:12', '2025-08-31 15:48:12'),
(8, 'master-classroom', 'api', '2025-08-31 15:47:28', '2025-08-31 15:47:28'),
(9, 'master-student', 'api', '2025-08-31 15:47:28', '2025-08-31 15:47:28'),
(10, 'master-payment', 'api', '2025-09-01 16:31:31', '2025-09-01 16:31:31'),
(11, 'master-school-year', 'api', '2025-09-01 17:22:10', '2025-09-01 17:22:10'),
(12, 'bill', 'api', '2025-09-01 18:45:10', '2025-09-01 18:45:10'),
(14, 'transaction', 'api', '2025-09-02 02:05:12', '2025-09-02 02:05:12'),
(15, 'savings-deposit', 'api', '2025-09-02 23:58:06', '2025-09-02 23:58:06'),
(16, 'savings-pull', 'api', '2025-09-02 23:58:06', '2025-09-02 23:58:06'),
(17, 'savings-history', 'api', '2025-09-02 23:58:53', '2025-09-02 23:58:53'),
(18, 'savings', 'api', '2025-09-03 00:00:49', '2025-09-03 00:00:49'),
(19, 'master-major', 'api', '2025-09-03 10:14:18', '2025-09-03 10:14:18'),
(20, 'user-bill', 'api', '2025-09-04 19:09:08', '2025-09-04 19:09:08'),
(21, 'user-savings', 'api', '2025-09-04 19:09:08', '2025-09-04 19:09:08'),
(22, 'user-dashboard', 'api', '2025-09-04 19:16:37', '2025-09-04 19:16:37'),
(23, 'finance', 'api', '2025-09-05 08:12:49', '2025-09-05 08:12:49'),
(24, 'bendahara-dashboard', 'api', '2025-09-05 20:17:16', '2025-09-05 20:17:16');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `full_name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Administrator', 'api', '2025-08-30 11:37:12', '2025-08-30 11:37:12'),
(2, 'bendahara', 'Bendahara', 'api', '2025-08-31 09:32:36', '2025-09-04 23:22:42'),
(3, 'siswa', 'Siswa', 'api', '2025-08-31 09:32:54', '2025-09-04 23:21:48'),
(4, 'guru', 'Guru', 'api', '2025-08-31 09:33:12', '2025-09-04 23:21:36');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 4),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(12, 2),
(14, 1),
(14, 2),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(18, 2),
(19, 1),
(20, 3),
(21, 3),
(22, 3),
(22, 4),
(23, 1),
(23, 2),
(24, 2);

-- --------------------------------------------------------

--
-- Table structure for table `savings`
--

CREATE TABLE `savings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `nominal` decimal(15,2) NOT NULL,
  `jenis` enum('Setor','Tarik') NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `savings`
--

INSERT INTO `savings` (`id`, `user_id`, `student_id`, `nominal`, `jenis`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 1, 167, 200000.00, 'Setor', NULL, '2025-09-30 22:12:43', '2025-09-30 22:12:43'),
(2, 1, 156, 1000000.00, 'Setor', NULL, '2025-09-30 22:13:26', '2025-09-30 22:13:26'),
(3, 1, 167, 150000.00, 'Tarik', NULL, '2025-09-30 22:14:44', '2025-09-30 22:14:44'),
(4, 1, 167, 10000.00, 'Setor', NULL, '2025-09-30 22:15:16', '2025-09-30 22:15:16'),
(5, 1, 167, 5000.00, 'Tarik', NULL, '2025-09-30 22:47:02', '2025-09-30 22:47:02'),
(6, 1, 167, 5000.00, 'Tarik', NULL, '2025-09-30 22:50:08', '2025-09-30 22:50:08'),
(7, 5, 156, 500000.00, 'Tarik', 'Pembayaran KEGIATAN 1 TAHUN TAHAP 4', '2025-10-08 09:55:41', '2025-10-08 09:55:41'),
(8, 1, 167, 200000.00, 'Tarik', 'Pembayaran SPP BULAN JULI', '2025-10-09 05:22:20', '2025-10-09 05:22:20'),
(9, 1, 167, 200000.00, 'Tarik', 'Pembayaran SPP BULAN JULI', '2025-10-09 05:24:45', '2025-10-09 05:24:45');

-- --------------------------------------------------------

--
-- Table structure for table `saving_balances`
--

CREATE TABLE `saving_balances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `saldo` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `saving_balances`
--

INSERT INTO `saving_balances` (`id`, `student_id`, `saldo`, `created_at`, `updated_at`) VALUES
(1, 167, -350000.00, '2025-09-30 22:12:43', '2025-10-09 05:24:45'),
(2, 156, 500000.00, '2025-09-30 22:13:26', '2025-10-08 09:55:41');

-- --------------------------------------------------------

--
-- Table structure for table `school_years`
--

CREATE TABLE `school_years` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tahun_ajaran` varchar(20) NOT NULL,
  `semester` enum('Ganjil','Genap') DEFAULT NULL,
  `status` enum('Aktif','Tidak Aktif') NOT NULL DEFAULT 'Tidak Aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `school_years`
--

INSERT INTO `school_years` (`id`, `tahun_ajaran`, `semester`, `status`, `created_at`, `updated_at`) VALUES
(1, '2021/2022', NULL, 'Tidak Aktif', '2025-09-01 11:22:47', '2025-09-04 12:04:38'),
(2, '2022/2023', NULL, 'Tidak Aktif', '2025-09-01 20:05:26', '2025-09-04 11:51:00'),
(3, '2023/2024', NULL, 'Tidak Aktif', '2025-09-02 01:10:23', '2025-09-02 01:10:23'),
(4, '2024/2025', NULL, 'Aktif', '2025-09-02 01:10:23', '2025-09-04 11:51:28'),
(5, '2025/2026', NULL, 'Aktif', '2025-09-02 01:10:23', '2025-09-02 01:10:23'),
(6, '2026/2027', NULL, 'Aktif', '2025-09-02 01:10:23', '2025-09-02 01:10:23');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `app` varchar(255) NOT NULL,
  `school` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `logo` varchar(255) NOT NULL,
  `bg_auth` varchar(255) NOT NULL,
  `logo_sekolah` varchar(255) DEFAULT NULL,
  `bg_landing_page` varchar(255) DEFAULT NULL,
  `pemerintah` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `telepon` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `uuid`, `app`, `school`, `description`, `logo`, `bg_auth`, `logo_sekolah`, `bg_landing_page`, `pemerintah`, `alamat`, `telepon`, `email`, `created_at`, `updated_at`) VALUES
(1, '6b3583b0-dab6-44fa-95e3-7bbac73d6270', 'SIKAZ', 'SMK AL-AZHAR MENGANTI GRESIK', '-', '/storage/setting/iWf03567gjwm7w7g4nLNGd6qKXH0a8sm8An7yikk.png', '/storage/setting/fImTfk7x5bg3SNa4gKSKDHWm0TmaZBUZSSeU2jud.jpg', '/storage/setting/PfNSlodCYn7thgD7qa8RL6Sin7uAqRoLESQv3lJQ.png', '/storage/setting/gbF6IxIWykp4PEesPMkDweX5IlcVoczylIl9u02g.jpg', 'Pemerintah Provinsi Jawa Timur', '-', '-', '-', '2025-08-30 11:37:14', '2025-10-16 04:18:45');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `nis` varchar(255) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `tempat_lahir` varchar(255) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `alamat` text NOT NULL,
  `classroom_id` bigint(20) UNSIGNED DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telepon` varchar(20) NOT NULL,
  `status` enum('Aktif','Prakerin','Alumni','Keluar') NOT NULL DEFAULT 'Aktif',
  `nama_ayah` varchar(255) DEFAULT NULL,
  `telepon_ayah` varchar(20) DEFAULT NULL,
  `nama_ibu` varchar(255) DEFAULT NULL,
  `telepon_ibu` varchar(20) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `nama`, `nis`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `classroom_id`, `email`, `telepon`, `status`, `nama_ayah`, `telepon_ayah`, `nama_ibu`, `telepon_ibu`, `foto`, `created_at`, `updated_at`) VALUES
(1, 'Abdul Aziz Fuadi', '1106/046.016', 'L', 'Bojonegoro', '2008-04-02', 'Jl. Tengger Raya RT 06 RW 02, Desa Kandangan, Kec. Benowo, Kab. Surabaya', 6, 'azizfuadi@gmail.com', '083114821478', 'Prakerin', 'Syukur', '083114821478', 'Duwik Umami', '083125238370', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(2, 'Achmad Aziz Aldiansyah', '1105/045.016', 'L', 'Sidoarjo', '2007-11-08', 'Dusun Bringkang RT 02 RW 01, Desa Bringkang, Kec. Menganti, Kab. Gresik', 6, 'aldialdiansyah@gmail.com', '08989168660', 'Aktif', 'Hariyanto', '089679064853', 'Alfiyah', '085336050150', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(3, 'Achmad Indra Setiawan', '1110/050.016', 'L', 'Surabaya', '2008-01-19', 'Banjarsugihan Baru RT 08 RW 04, Kec. Tandes, Surabaya', 6, 'indrasetiawan@gmail.com', '0895397106432', 'Prakerin', 'Sugeng', '0895397106432', 'Siah', '0895397106000', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(4, 'Ahmad Ayong Ziaul Haq', '1108/048.016', 'L', 'Gresik', '2008-01-17', 'Desa Setro Dusun Pengampon RT 11 RW 06, Kec. Menganti, Kab. Gresik', 6, 'ayonghaq@gmail.com', '089512085166', 'Aktif', 'Subroto', '0895622340921', 'Elik', '0895622340921', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(5, 'Ahmad Fahmi', '1109/049.016', 'L', 'Gresik', '2008-08-01', 'RT 06 RW 02, Kedamean, Kab. Gresik', 6, 'fahmiahmad@gmail.com', '085608455087', 'Aktif', 'Iwan', '081515506595', 'Sriani', '085600000000', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(6, 'Ahmad Surya Jaya', '1111/051.016', 'L', 'Gresik', '2007-08-22', 'Dusun Kendayaan RT 002 RW 07 Desa Lampah, Kec. Kedamean, Kab. Gresik', 6, 'suryajaya@gmail.com', '085776712285', 'Aktif', 'Sulikan', '085745963422', 'Sunarmi', '085700000000', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(7, 'Akmal Alfa Rizqi', '1112/052.016', 'L', 'Karanganyar', '2007-04-24', 'Perum Oma Green Land RT 01 RW 12, Desa Hulaan, Kec. Menganti, Kab. Gresik', 6, 'akmalrizqi@gmail.com', '081230324274', 'Aktif', 'Soeprianto', '08123024870', 'Unik Setiyo Hapsari', '085732056330', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(8, 'Amrullah Baihaqi Al Anam', '1113/053.016', 'L', 'Surabaya', '2008-04-19', 'Ds. Pengalangan RT 09 RW 03, Menganti, Gresik', 6, 'amrullahanam@gmail.com', '085648351018', 'Prakerin', 'Sulianam', '081230384339', 'Nur Hayati', '085648351018', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(9, 'Andika Sabian Fransisco', '1114/054.016', 'L', 'Gresik', '2008-01-21', 'Dusun Sidowungu RT 14 RW 04, Desa Sidowungu, Kec. Menganti, Kab. Gresik', 6, 'andikafransisco@gmail.com', '083192927526', 'Aktif', 'Sumartono', '085806737180', 'Mas Ruro', '085800000000', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(10, 'Ardhita Putra Wardana', '1115/055.016', 'L', 'Gresik', '2008-04-29', 'Dusun Pengampon RT 09 RW 05, Menganti, Gresik', 6, 'ardhitawardana@gmail.com', '0895383944114', 'Aktif', 'Budiono', '08136754334', 'Pita Kustiawati', '0895383944114', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(11, 'Ardy Wahyu Ridho Ariefianto', '1116/056.016', 'L', 'Gresik', '2007-08-07', 'Dusun Watupasang RT 12 RW 05 Desa Kedamean Kec Kedamean Kab Gresik', 6, 'ardywahyu@gmail.com', '085648924837', 'Aktif', 'Siswanto', '081559734512', 'Nurwatini', '085700000111', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(12, 'Arga Andika Firmansyah', '1117/057.016', 'L', 'Gresik', '2007-05-08', 'Dusun Setro RT.08 RW.04 Desa Setro Kec.Menganti Kab.Gresik', 6, 'argaandika@gmail.com', '089515993386', 'Aktif', 'Joko Mulyo', '0895623468068', 'Eka Budi Diah Lestari', '089677019074', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(13, 'Ariel Irwansyah Aditia Pradana', '1118/058.016', 'L', 'Surabaya', '2007-11-29', 'Babatan Gang 3d RT.6 RW.1 Kecamatan Wiyung', 6, 'arielirwansyah@gmail.com', '083833227170', 'Aktif', 'Suyono', '081239433827', 'Liftining Wahyuni', '081239433827', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(14, 'Ariyanto', '1119/059.016', 'L', 'Gresik', '2008-06-12', 'Dusun Pengampon RT. 11 RW. 06 Desa Setro Kec. Menganti Kab. Gresik', 6, 'ariyanto@gmail.com', '085806007574', 'Aktif', 'Sumarli', '085706478447', 'Fuji Rahayu', '085706478447', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(15, 'Fahri Arianto', '1120/060.016', 'L', 'Menganti Gresik', '2008-03-01', 'Sidojangkung RT.6 RW.2', 6, 'fahriarianto@gmail.com', '083143073086', 'Aktif', 'Kuswandi', '085800000111', 'Suas Sari', '083112016802', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(16, 'Farhan Candra Irfansa', '1121/061.016', 'L', 'Gresik', '2008-07-09', 'Dusun Sidojangkung RT.03 RW.01', 6, 'farhancandra@gmail.com', '085654448233', 'Aktif', 'Nurul Mustofa', '087865533280', 'Trisukarti', '085700000222', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(17, 'Ilham Al Gozali', '1123/063.016', 'L', 'Surabaya', '2006-11-11', 'Kalianak Timur Gg Belakang 6 RT 2 RW 7 Surabaya', 6, 'ilhamalgozali@gmail.com', '08898989794260', 'Aktif', 'Kasiyadhi', '081200000333', 'Suyatmi', '083830238366', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(18, 'Inengah Dimas Wijaksono', '1122/062.016', 'L', 'Surabaya', '2008-02-09', 'Dusun Gempol Kurung RT 06 RW 02 Desa Gempol Kurung Kec Menganti Kab Gresik', 6, 'dimaswijaksono@gmail.com', '085645783452', 'Keluar', 'I Nengah Sudarma', '088217525010', 'Kristin Anggraini', '085704002627', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(19, 'Ivan Alfian Nur Isnen', '1124/064.016', 'L', 'Tegal', '2008-08-06', 'Desa Sumput RT 08 RW 02 Kec.Driyorejo Kab. Gresik', 6, 'ivanalfian@gmail.com', '082334528807', 'Prakerin', 'Surip Rismanto', '082334528807', 'Purwati', '082334528807', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(20, 'Izzun Ni\'am', '1125/065.016', 'L', 'Sidoarjo', '2007-10-06', 'Jl. Pandean RT. 013 RW. 004 Desa Ngingas, Kec. Waru, Kab. Sidoarjo', 6, 'izzunniam@gmail.com', '085731116133', 'Prakerin', 'Abdur Rahman', '08566001528', 'Lailatul Munadifah', '081217802017', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(21, 'Julfan Hariyanto', '1257/086.016', 'L', 'Gresik', '2008-11-11', 'Sumput RT 11 RW 02, Driyorejo, Gresik', 6, 'julfanhariyanto@gmail.com', '085784473679', 'Prakerin', 'Hamam Nasrudin', '085784473679', 'Tuni', '085784473679', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(22, 'Lukmannul Hakim', '1126/066.016', 'L', 'Gresik', '2007-12-30', 'Dusun Kedamean RT 08 RW 04 Desa Kedamean Kec. Kedamean Kab. Gresik', 6, 'lukmannulhakim@gmail.com', '085859829877', 'Aktif', 'Supi\'i', '081332536204', 'Yuniati', '0895335380647', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(23, 'Marfeldaniansyah', '1127/067.016', 'L', 'Gresik', '2008-06-22', 'Dusun Gempol RT.03 RW.04', 6, 'marfeldaniansyah@gmail.com', '083830555059', 'Aktif', 'Lukman Hakim', '085749224001', 'Sri Nonik', '085763796297', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(24, 'M. Zamson Wahyudi', '1140/080.016', 'L', 'Surabaya', '2008-02-10', 'Dusun Balongdinding RT. 25 RW. 05 Desa Sidowungu Kec. Menganti Kab. Gresik', 6, 'zamsonwahyudi@gmail.com', '08999009883', 'Aktif', 'M Kastalani', '0895705878028', 'Listyowati', '083134898068', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(25, 'Moch Asyraf Al-Habib', '1128/068.016', 'L', 'Surabaya', '2008-02-27', 'Kauman Asri RT. 01 RW. 06 Benowo Kec. Pakal Kab. Surabaya', 6, 'asyrafhabib@gmail.com', '089529877914', 'Aktif', 'Abdul Hasib', '081216358569', 'Puji Rahayu', '082243448370', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(26, 'Moch Rizky Aditya Firmasnyah', '1129/069.016', 'L', 'Gresik', '2007-07-30', 'Puri Safira Regency, Menganti, Gresik', 6, 'rizkyaditya@gmail.com', '085785507221', 'Aktif', 'Sugianto', '025735121164', 'Yulaidah', '085972137312', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(27, 'Moch Rizky Nur Fajar Ramadani', '1130/070.016', 'L', 'Surabaya', '2007-09-27', 'Jeruk Gang VI RT 05 RW 02 Kec. Lakarsantri, Surabaya', 6, 'rizkynurfajar@gmail.com', '081252939581', 'Aktif', 'Sadi', '082334627060', 'Sumarlik', '082233401900', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(28, 'Mochamad Naufal Alamsyah', '1132/072.016', 'L', 'Pulukan', '2007-11-15', 'Dusun Sidomulyo RT.4 RW.9 Desa Hulaan Kec. Menganti Kab. Gresik', 6, 'naufalalamsyah@gmail.com', '085648806973', 'Prakerin', 'Supriyadi', '0839122831213', 'Dwi Ratna Wati', '085648806973', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(29, 'Mochammad Aliyansah', '1131/071.016', 'L', 'Gresik', '2007-09-08', 'Dusun Tulung RT. 03 RW. 02 Desa Tulung Kec. Kedamean Kab. Gresik', 6, 'mohammadaliyansah@gmail.com', '082332132876', 'Aktif', 'Akir', '082332132876', 'Marti Dwi Astutik', '082331232876', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(30, 'Mohamad Fajar Arianto', '1134/074.016', 'L', 'Gresik', '2008-04-30', 'Mojotengah RT 12 RW 04 Kec. Menganti Kab. Gresik', 6, 'fajararianto@gmail.com', '081233936692', 'Aktif', 'Abdul Maliki', '081233936691', 'Atih Dwi Jayanti', '081233936691', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(31, 'Mohammad Ramzy Itisham', '1135/075.016', 'L', 'Surabaya', '2008-06-17', 'Manukan Kasman No100 RT 01 RW 10 Kec.Tandes Kabupaten Surabaya', 6, 'ramzy@gmail.com', '085745427253', 'Prakerin', 'Heri Nur Wachit', '085745427253', 'Titin Tri Yulianti', '085745427253', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(32, 'Muhammad Gerry Hermawan', '1137/077.016', 'L', 'Gresik', '2008-02-09', 'Dusun Ngepong RT.12 RW 05 Desa Doro Ngepung Kec. Damean Kab. Gresik', 6, 'gerry@gmail.com', '083832506769', 'Aktif', 'Nuri', '085101229403', 'Kotipa', '085101229403', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(33, 'Muhammad Khoirurroziqin', '1138/078.016', 'L', 'Surabaya', '2008-01-03', 'Perumahan Omah Indah Menganti Block G6 No 37 RT26 RW 09 Kec.Menganti Kab.Gresik', 6, 'risky@gmail.com', '082139411390', 'Aktif', 'Bonny Gantino S.E', '0821411390', 'Nur Alaela', '082100519893', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(34, 'Muhammad Rifaldi', '1139/079.016', 'L', 'Gresik', '2008-03-17', 'Johor Baru Dka RT 06 RW 07', 6, 'aldi@gmail.com', '085707037090', 'Aktif', 'Jauhari', '082233360409', 'Seniwati', '085707037090', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(35, 'Muhammad Syafak Muhajirin', '1133/073.016', 'L', 'Sidoarjo', '2008-03-15', 'Dusun Dongol, Desa Tempel, Kec. Krian Kab. Sidoarjo', 6, 'syafak@gmail.com', '083159811207', 'Prakerin', 'Mubin', '083159811207', 'Jannah', '083159811207', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(36, 'Mukti Nugroho S', '1141/081.016', 'L', 'Surabaya', '2008-01-29', 'Desa Sidowungu RT 16 RW 04, Kecamatan Menganti, Kabupaten Gresik', 6, 'mukti@gmail.com', '085806646990', 'Aktif', 'Kusmono', '-', 'Eko Prasetyo Ningtyas', '-', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(37, 'Pradita Putra Riyanto', '1142/082.016', 'L', 'Pekalongan', '2007-10-07', 'Desa Sidowungu RT.08 RW.2 Kec.Menganti Kab.Gresik', 6, 'putra@gmail.com', '0895622641558', 'Aktif', 'Andi Riyanto', '08983640254', 'Ajeng Purwi Anggraini', '089521338360', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(38, 'Raka Bagus Pramuja', '1143/083.016', 'L', 'Surabaya', '2007-08-03', 'Desa Sidojangkung RT.03 RW.01 Kec. Menganti Kab. Gresik', 6, 'raka@gmail.com', '081335811970', 'Aktif', 'Ramelan', '081554251671', 'Nika Sari Ayoeningtyas', '081330453650', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(39, 'Rama Andika Pratama', '1144/084.016', 'L', 'Blitar', '2007-09-16', 'Perum Puri Asta Kencana Blok. D3/11 RT.18 RW.05 Desa Boteng Kec. Menganti Kab. Gresik', 6, 'rama@gmail.com', '085731401590', 'Aktif', 'Warsito', '085735839684', 'Harmuji', '085735839684', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(40, 'Riki Ardiansyah', '1145/085.016', 'L', 'Gresik', '2007-06-06', 'Dusun Pranti RT.04 RW.05 Desa Pranti Kec.Menganti Kab.Gresik', 6, 'riki@gmail.com', '085782525385', 'Aktif', 'Sholikan', '085644785986', 'Ningyah', '087864933985', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(41, 'Tri Budi Pangestu', '1146/086.016', 'L', 'Surabaya', '2007-09-26', 'Desa Bringkang RW09 Graha Omah Indah Menganti Blok E4/7 Jl Rambutan', 6, 'budi@gmail.com', '089650565826', 'Aktif', 'Gatot Darmanto', '085850565827', 'Susiani', '0895417789716', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(42, 'Muhammad Slamet Apriliyono', '1259/088.016', 'L', 'Surabaya', '2007-04-30', 'Gubeng Kertajaya 13-C/2 RT.004 RW.006 Desa Airlangga, Kec. Gubeng, Surabaya', 6, 'slamet@gmail.com', '082132246370', 'Prakerin', 'Sugiyono', '083837981798', 'Winovia Alistari Romagoran', '082132246370', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(43, 'Alif Febriansyah', '1147/029.049', 'L', 'Gresik', '2008-02-18', 'Perum Sumput Asri RT 21 RW 06 Kec. Driyorejo Kab. Gresik', 5, 'aliffebriansyah@gmail.com', '085785380866', 'Aktif', 'Agus Fatchur Rahman', '081200010001', 'Siti Komimah', '081200010002', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(44, 'Andreaw Jordan Rifai', '1148/030.049', 'L', 'Surabaya', '2007-09-10', 'Oma Indah Menganti RT 24 RW 09 Desa Bringkang Kec. Menganti Kab. Gresik', 5, 'jordanrifai@gmail.com', '08819765706', 'Aktif', 'Imam Rifai', '081200010003', 'Emy Kusuma Wahyuni', '081200010004', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(45, 'Bagus Permana Putra', '1149/031.049', 'L', 'Sidoarjo', '2007-12-17', 'RT 1 RW 1 Desa Simo Angin, Kec. Wonoayu Kab. Sidoarjo', 5, 'baguspermana@gmail.com', '085748646059', 'Prakerin', 'Sumedi', '081200010005', 'Ratna Purwati', '081200010006', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(46, 'Dani Ariski', '1150/032.049', 'L', 'Gresik', '2007-06-15', 'Dusun Guplong RT 02 RW 01 Desa Sooko Kec. Wringinanom Kab. Gresik', 5, 'daniariski@gmail.com', '085810878289', 'Prakerin', 'Samuji', '081200010007', 'Sumarning', '081200010008', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(47, 'Dhimas Romadhon', '1151/033.049', 'L', 'Surabaya', '2007-10-03', 'Perumahan Graha Menganti 2 Blok I2-15, Dusun Kemorogan, Desa Pranti, Gresik', 5, 'dhimasromadhon@gmail.com', '088804824033', 'Aktif', 'Suyitno', '081200020001', 'Tumiratin', '081200020002', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(48, 'Faiz Ramdhan Ardiansyah', '1152/034.049', 'L', 'Gresik', '2008-09-29', 'Desa Katimoho RT 09 RW 03 Kec. Kedamean Kab. Gresik', 5, 'faizramdhan@gmail.com', '085546434608', 'Aktif', 'Abdul Azis', '081200020003', 'Siti Fitriawati', '081200020004', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(49, 'Fajar Bagas Pratama', '1153/035.049', 'L', 'Gresik', '2008-06-13', 'Desa Hendrosari RT 08 RW 01 Kec. Menganti Kab. Gresik', 5, 'fajarbagas@gmail.com', '083135950909', 'Prakerin', 'Nur Kholis', '081200020005', 'Riyatin', '081200020006', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(50, 'Farel Muklisatya P', '1154/036.049', 'L', 'Surabaya', '2008-07-01', 'Dusun Karang Turi RT 26 RW 08 Desa Menganti Kec. Menganti Kab. Gresik', 5, 'farelmuklisatya@gmail.com', '087843902903', 'Aktif', 'Imamul Muklisin', '087862037577', 'Retno Ninuk Yustina', '081907465298', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(51, 'Galang Satya Ardiansyah', '1155/037.049', 'L', 'Gresik', '2008-02-26', 'Dusun Boteng RT 09 RW 03 Kec. Menganti Kab. Gresik', 5, 'galangsatya@gmail.com', '087860627653', 'Aktif', 'Rahmat Santosa', '085235457979', 'Yuni Kusbiastutik', '085967180321', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(52, 'Hanif Amrullah', '1156/038.049', 'L', 'Gresik', '2008-02-23', 'Dusun Glintung RT 07 RW 05 Desa Kepatihan, Kec. Menganti, Kab. Gresik', 5, 'hanifamrullah@gmail.com', '0881027307208', 'Aktif', 'Musolin', '085102402292', 'Nur Mujayana', '081234567890', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(53, 'Hendra Hermawan', '1157/039.049', 'L', 'Gresik', '2007-11-30', 'Dusun Gantang Baru RT 05 RW 02 Kec. Menganti Kab. Gresik', 5, 'hendrahermawan@gmail.com', '0895336136550', 'Aktif', 'Waryono', '082230071245', 'Yuliani', '08973458415', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(54, 'Izzbikavick Achmad N', '1158/040.049', 'L', 'Surabaya', '2007-08-20', 'Sumberejo 1 Pakal Surabaya', 5, 'izzbikavick@gmail.com', '087894963726', 'Aktif', 'Nasrulloh', '083833582054', 'Lilik Setyana', '081230220707', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(55, 'M. Arif Ramadhani', '1164/046.049', 'L', 'Gresik', '2008-09-19', 'Dusun Boteng RT 09 RW 03 Desa Boteng Kec. Menganti Kab. Gresik', 5, 'ariframadhani@gmail.com', '081515595710', 'Aktif', 'Ramelan', '088989864107', 'Titin Triawan', '085859824705', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(56, 'M. Aris Syarifudin', '1160/042.049', 'L', 'Bojonegoro', '2007-12-13', 'Desa Sumberejo1 RT 01 RW 01, Pakal', 5, 'arisyarifudin@gmail.com', '085853268978', 'Aktif', 'M. Muis', '0895393001178', 'Choirul Hidayati', '0895338032966', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(57, 'M. Farid Nur Khasan', '1166/048.049', 'L', 'Gresik', '2007-12-17', 'Ds. Hendrosari RT 07 RW 03, Kec. Menganti, Kab. Gresik', 5, 'faridkhasan@gmail.com', '085606205677', 'Prakerin', 'Malikhan', '085806358142', 'Asyaroh', '085806358142', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(58, 'Ma\'ruf Hakiki', '1161/043.049', 'L', 'Gresik', '2008-01-17', 'Dusun Glintung RT 02 RW 04, Desa Kepatihan, Kec. Menganti, Kab. Gresik', 5, 'marufhakiki@gmail.com', '08817011635', 'Aktif', 'Priono', '088991089394', 'Tutik', '081234567891', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(59, 'Mauludin Ihya Nawa', '1162/044.049', 'L', 'Gresik', '2007-04-05', 'Dusun Dukuhan RT 10 RW 04, Desa Menganti, Kec. Menganti, Kab. Gresik', 5, 'ihyanawa@gmail.com', '085780526064', 'Aktif', 'Imam Nawawi', '085780526064', 'Kholifah', '085648129208', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(60, 'Moch Rico Firnando', '1163/045.049', 'L', 'Gresik', '2007-04-01', 'Dusun Bringkang RT 08 RW 04, Desa Bringkang, Kec. Menganti, Kab. Gresik', 5, 'ricofirnando@gmail.com', '089654336549', 'Aktif', 'Sunardi', '085807240977', 'Ismawati', '085731389534', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(61, 'Mohammad Rayhan', '1159/041.049', 'L', 'Gresik', '2008-05-11', 'Dusun Glintung RT 07 RW 05, Desa Kepatihan, Kec. Menganti, Kab. Gresik', 5, 'rayhan@gmail.com', '089676350494', 'Aktif', 'Eneng', '085231086098', 'Rateni', '089533526181', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(62, 'Muhammad Barel Avansa', '1165/047.049', 'L', 'Nganjuk', '2007-10-09', 'Desa Bringkang RT 08 RW 04, Kec. Menganti, Kab. Gresik', 5, 'barelavansa@gmail.com', '083856225771', 'Aktif', 'Sugeng Dwiarto', '082139293912', 'Umi Kalsum', '081336860145', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(63, 'Muhammad Luqmanul Hakim', '1167/049.049', 'L', 'Gresik', '2008-02-26', 'Dusun Karanganyar RT 010 RW 004, Desa Karangankidul, Kec. Benjeng, Kab. Gresik', 5, 'luqmanhakim@gmail.com', '085336322056', 'Prakerin', 'Wage', '081343891938', 'Gani', '082170734954', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(64, 'Muhammad Mashudi Abdullah', '1168/050.049', 'L', 'Gresik', '2007-10-28', 'Desa Katimoho RT 01 RW 01, Menganti', 5, 'mashudiabdullah@gmail.com', '085791140958', 'Aktif', 'Sulkan', '081236844055', 'Lidia Rustiana', '082238951533', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(65, 'Muhammad Nabil Zakaria', '1169/051.049', 'L', 'Gresik', '2007-10-22', 'Desa Katimoho RT 07 RW 02, Menganti', 5, 'nabilzakaria@gmail.com', '081515508328', 'Aktif', 'Nur Hasan', '081515774473', 'Umi Zukaini', '081515774473', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(66, 'Muhammad Rifqi Setiawan', '1170/052.049', 'L', 'Gresik', '2007-08-28', 'Dusun Glintung RT 01 RW 04, Desa Kepatihan, Kec. Menganti, Kab. Gresik', 5, 'rifqi.setiawan@gmail.com', '085807278604', 'Aktif', 'Maruki', '083857888902', 'Sulistiyowati', '081359689672', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(67, 'Muhammad Zaidaanul Hamdi', '1171/053.049', 'L', 'Sidoarjo', '2007-02-06', 'Perum Swan Menganti Park Blok H-53, Desa Palem Watu, RT 01 RW 09', 5, 'zaidan.hamdi@gmail.com', '081259515273', 'Aktif', 'Mohammad Ifham', '082245442034', 'Dini Lukmawati', '081230974988', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(68, 'Muhammad Zakief Akbar', '1172/054.049', 'L', 'Surabaya', '2007-05-13', 'Jl. Mutiara 3, Desa Petiken, Kec. Driyorejo', 5, 'zakief.akbar@gmail.com', '085733032613', 'Aktif', 'Moch Tamsi', '-', 'Sumiah', '-', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(69, 'Mukhammad Ichya Ulumudin', '1173/055.049', 'L', 'Sidoarjo', '2008-05-05', 'Dusun Legundi RT 03 RW 01, Desa Krikilan, Kec. Driyorejo, Kab. Gresik', 5, 'ichya.ulumudin@gmail.com', '081249462404', 'Prakerin', 'Rhyant Prameswhara', '085645044400', 'Nanin Muslikah', '081249462404', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(70, 'Rheza Aditya Pratama', '1174/056.049', 'L', 'Gresik', '2008-04-27', 'Desa Pacuh RT 01, Kec. Balongpanggang, Kab. Gresik', 5, 'rheza.pratama@gmail.com', '085648212463', 'Prakerin', 'Iskan', '085648212463', 'Candra Maya Shinta', '085648212463', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(71, 'Sayyiddina Hasan Azkar', '1175/057.049', 'L', 'Malang', '2007-09-19', 'Perumnas Sumput Asri, Jl. Pisang Blok H-12, Desa Sumput, Kec. Driyorejo, Kab. Gresik', 5, 'azkar.hasan@gmail.com', '089692376442', 'Aktif', 'Anton Virgo Wibowo', '089692376442', 'Siti Aisyah', '089677811751', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(72, 'Vicry Maulana', '1176/058.049', 'L', 'Gresik', '2008-02-01', 'Dusun Glintung RT 03 RW 04, Desa Kepatihan, Kab. Gresik', 5, 'vicry.maulana@gmail.com', '089520073767', 'Aktif', 'Suparlan', '08993967056', 'Amini', '089521441204', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(73, 'Yogi Tri Priyono', '1177/059.049', 'L', 'Gresik', '2008-02-22', 'Dusun Tulung RT 05 RW 03, Desa Tulung, Kec. Kedamean, Kab. Gresik', 5, 'yogi.priyono@gmail.com', '081515496316', 'Aktif', 'Sriono', '085648967012', 'Diami', '081515774473', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(74, 'Ya Rohmanul Aminin', '1178/060.049', 'L', 'Lamongan', '2008-03-28', 'Dusun Mabang Penompo RT 01 RW 01, Desa Sukosari, Kec. Mantup, Kab. Lamongan', 5, 'rohmana.minin@gmail.com', '085748023539', 'Prakerin', 'Moch. Safa\'at', '085748023539', 'Siti Nuriyati', '085748023540', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(75, 'Ahmad Galuh Kuswardani', '1087/016.009', 'L', 'Gresik', '2007-03-23', 'Dusun Sidowareg RT.16 RW.05 Desa Sidojangkung, Menganti, Gresik', 4, 'galuhkuswardani@gmail.com', '0859183964097', 'Aktif', 'Nurul Anwar', '089685461680', 'Kuswati', '083820901549', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(76, 'Ahmad Rehan Ardiansyah', '1088/017.009', 'L', 'Gresik', '2007-02-14', 'Dusun Pengampon, Desa Setro RT.11 RW.06, Gresik', 4, 'rehanardiansyah@gmail.com', '085649662479', 'Aktif', 'Panuri', '081233373304', 'Fitriyanik', '081615552034', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(77, 'Ahmad Syahrul Fauzan', '1089/018.009', 'L', 'Gresik', '2007-10-23', 'Dusun Balong Dinding RT.20 RW.05 Desa Sidowungu, Menganti, Gresik', 4, 'syahrulfauzan@gmail.com', '081515460198', 'Aktif', 'Arif', '0815515460198', 'Titik Subekti Ningsih', '0895336179770', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(78, 'Akhmad Dzuhdi Mauladani', '1090/019.009', 'L', 'Gresik', '2008-03-13', 'Dusun Gempol RT.01 RW.04 Desa Lampah, Kedamean, Gresik', 4, 'dzuhdimauladani@gmail.com', '082330397287', 'Aktif', 'Imam Khusairi', '081357555192', 'Nanik', '081357555192', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(79, 'Aryo Damar Fauzan', '1091/020.009', 'L', 'Surabaya', '2007-04-06', 'Perumahan Eco Park P7, Dusun Glundung, Desa Pranti, Menganti, Gresik', 4, 'aryofauzan@gmail.com', '085648275848', 'Aktif', 'Ary Dhuta', '083870993144', 'Icik Salmiah', '085648275848', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(80, 'Chisabhi Pramadina Rama', '1092/021.009', 'L', 'Surabaya', '2007-09-22', 'Perumahan Swan Park RT.01 RW.09 Desa Palemwatu, Menganti, Gresik', 4, 'chisabhirama@gmail.com', '085895371624', 'Aktif', 'Empep Permasa', '085746091007', 'Gentari Triwinarni', '081342424427', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(81, 'Dwi Ferdiansyah', '1093/022.009', 'L', 'Gresik', '2008-08-10', 'Dusun Glintung RT.03 RW.04 Desa Kepatihan, Menganti, Gresik', 4, 'dwiferdiansyah@gmail.com', '089675771843', 'Aktif', 'Kemi', '089509731103', 'Arti', '089509731103', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(82, 'Dwi Ilyas Ramadhan', '1094/023.009', 'L', 'Gresik', '2007-09-17', 'Dusun Balong Dinding RT.18 RW.05 Menganti, Gresik', 4, 'ilyasramadhan@gmail.com', '089514663510', 'Aktif', 'Arsun', '089514663510', 'Munjianah', '089514663510', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(83, 'Erwin Kurniawan', '1095/024.009', 'L', 'Surabaya', '2007-03-19', 'Dusun Gempol RT.04 RW.05 Desa Lampah, Kedamean, Gresik', 4, 'erwinkurniawan@gmail.com', '085746013226', 'Aktif', 'Almarhum Achmadi', '081935048523', 'Srini', '085745013226', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(84, 'Fajar Maulana', '1096/025.009', 'L', 'Gresik', '2007-02-25', 'Desa Katimoho RT.07 RW.03 Kedamean, Gresik', 4, 'fajarmaulana@gmail.com', '085785678274', 'Aktif', 'Soleh', '085607054992', 'Siti Faridah', '085607054992', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(85, 'Ilham Febriansyah', '1097/026.009', 'L', 'Gresik', '2008-02-17', 'Dsn Lampah Kedamean RT.02 RW.01, Gresik', 4, 'ilhamfebriansyah@gmail.com', '085808403130', 'Aktif', 'Ruslan', '081234567890', 'Tutik', '081298765432', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(86, 'M Akbar Pratama', '1098/027.009', 'L', 'Gresik', '2008-10-01', 'Dusun Rayung RT.07 RW.02 Desa Turirejo, Kedamean, Gresik', 4, 'akbarpratama@gmail.com', '085608446286', 'Aktif', 'Fatkhul Haris', '08563294779', 'Siti Rosidah', '085608446286', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(87, 'Muhamad Arif', '1099/028.009', 'L', 'Surabaya', '2007-12-14', 'Karangturi RT.22 RW.07 Menganti, Gresik', 4, 'muhamadarif@gmail.com', '083893320682', 'Aktif', 'Abdusalam', '081234500000', 'Mutmainah', '081234511111', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(88, 'Muhamad Kelvin Roy Ferdinan', '1100/029.009', 'L', 'Gresik', '2008-05-26', 'Dusun Sidojangkung RT.07 RW.02 Desa Sidojangkung, Menganti, Gresik', 4, 'kelvinferdinan@gmail.com', '082331957620', 'Aktif', 'Rupii', '082139236652', 'Yulianah', '081335656371', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(89, 'Muhammad Fajar Amrullah', '1101/030.009', 'L', 'Gresik', '2007-12-10', 'Dusun Kalangan RT.01 RW.02 Desa Karangandong, Driyorejo, Gresik', 4, 'fajaramrullah@gmail.com', '085895702308', 'Aktif', 'Imam Subaweh', '085101319172', 'Sumartik', '081298765400', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(90, 'Reno Febryan', '1102/031.009', 'L', 'Gresik', '2008-02-05', 'Ds Tanjung Dsn Tempel RT 01 RW 03 Kec Kedamean Kab Gresik', 4, 'renofebryan@gmail.com', '085748325647', 'Aktif', 'Solihun', '081200000111', 'Yuyun Fitriawati', '081200000222', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(91, 'Susanto', '1103/032.009', 'L', 'Surabaya', '2007-09-07', 'Desa Sidojangkung, Dusun Sidowarwg RT 16 RW 05, Gresik', 4, 'susanto@gmail.com', '088235482076', 'Aktif', 'Karisun', '081200000333', 'Sulastri', '081200000444', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(92, 'Wisnu Adi Saputra', '1104/033.009', 'L', 'Lamongan', '2008-03-29', 'Desa Banyuurip Kec. Menganti Kab. Gresik', 4, 'wisnuadisaputra@gmail.com', '085746729429', 'Aktif', 'Slamet', '081200000555', 'Khoiriah', '081200000666', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(93, 'Angga Rizky Rahmadani', '1231/099.066', 'L', 'Gresik', '2007-03-17', 'Dusun Wedoro RT. 03 RW. 01 Desa Wedoroanom Kec. Driyorejo Kab. Gresik', 3, 'anggarizky@gmail.com', '081216079420', 'Aktif', 'Bambang Raharjo', '081234567890', 'Siti Mawarni', '081298765432', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(94, 'Fakhriyyah Syurofa Annabila', '1233/101.066', 'P', 'Mojokerto', '2008-05-06', 'Banjaran RT.04 RW.01 Kec. Driyorejo Kab. Gresik', 3, 'nabilaannabila@gmail.com', '085706789524', 'Aktif', 'Hendri Prakoso', '081234556677', 'Nurhayati', '081233344455', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(95, 'Ilma Rofiah', '1234/102.066', 'P', 'Gresik', '2008-07-27', 'Dusun Petal RT.12 RW.04 Desa Domas Kec. Menganti Kab. Gresik', 3, 'ilmarofiah@gmail.com', '0895419901400', 'Aktif', 'Slamet Riyadi', '082199887766', 'Dewi Lestari', '082133355577', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(96, 'Ivana Khoiron Nisa', '1235/103.066', 'P', 'Kediri', '2008-01-30', 'Sepat Lidah Kulon RT.02 RW.03 Kec. Lakarsantri Kab. Surabaya', 3, 'ivanakhoironnisa@gmail.com', '088971129899', 'Aktif', 'Rudi Hartono', '081355667788', 'Ani Lestari', '081299887766', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(97, 'Kamila Zakiytun Nikmah', '1236/104.066', 'P', 'Gresik', '2008-05-14', 'Dusun Tempel RT.04 RW.03 Desa Tanjung Kec. Kedamean Kab. Gresik', 3, 'kamilazakiytunnikmah@gmail.com', '082245709543', 'Prakerin', 'Arif Prasetyo', '081377788899', 'Dwi Anggraini', '082144556677', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(98, 'Lintang Arkhaprya Dirgantara Hadikoesoemo', '1237/105.066', 'L', 'Surabaya', '2008-06-17', 'Perumahan Oma Indah Menganti Blok H10-19 RT.34 RW.09 Kec. Menganti Kab. Gresik', 3, 'lintangarkhaprya@gmail.com', '089613308585', 'Aktif', 'Yudi Santoso', '081322334455', 'Ratna Dewi', '081377766655', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(99, 'Meisya Azza Alivia', '1238/106.066', 'P', 'Gresik', '2008-05-15', 'Sidowungu RT.08 RW.02 Kec. Menganti Kab. Gresik', 3, 'meisyaazzaalivia@gmail.com', '081216005742', 'Aktif', 'Eko Prabowo', '081355667799', 'Sari Indah', '081266778899', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(100, 'Miftahul Faizin', '1239/107.066', 'L', 'Gresik', '2007-06-16', 'Dusun Bongso Wetan RT.13 RW.06 Desa Pengalangan Kec. Menganti Kab. Gresik', 3, 'miftahulfaizin@gmail.com', '08990938449', 'Aktif', 'Andi Saputra', '081344556677', 'Lina Kartika', '081233322211', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(101, 'Muhammad Alghivanny Widiansyah', '1240/108.066', 'L', 'Sleman', '2008-12-17', 'Perumahan Jade Hamlet Ivory 2 Blok D No.01, Sleman', 3, 'muhammadalghivanny@gmail.com', '08989289517', 'Aktif', 'Agus Setiawan', '081399887766', 'Dwi Puspitasari', '081288877655', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(102, 'Muhammad Faris Salim', '1241/109.066', 'L', 'Gresik', '2007-05-26', 'Kampung Pakal Madya RT.03 RW.02 Kec. Pakal, Surabaya', 3, 'muhammadfarissalim@gmail.com', '089525359117', 'Aktif', 'Imam Riyadi', '081344556688', 'Siti Rahmah', '081355667799', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(103, 'Muhammad Itsnin', '1242/110.066', 'L', 'Surabaya', '2007-12-03', 'Tengger Raya RT.06 RW.02 Desa Kandangan, Kec. Benowo, Kab. Surabaya', 3, 'muhammaditsnin@gmail.com', '089514605103', 'Prakerin', 'Yusuf Hidayat', '081355667700', 'Fitri Handayani', '082144556699', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(104, 'Nur Mohamad Reyhan Eka Yuswanto', '1244/112.066', 'L', 'Bojonegoro', '2008-03-19', 'Jl. Tengger Raya 6/45 RT.06 RW.02 Kel. Kandangan Kec. Benowo, Kab. Surabaya, Jawa Timur', 3, 'nurreyhanyuswanto@gmail.com', '085755011717', 'Prakerin', 'Arman Yusup', '082133344455', 'Sulastri', '081299887700', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(105, 'Priska Amelia Amanda', '1245/113.066', 'P', 'Gresik', '2007-06-03', 'Sidowungu, Menganti, Gresik RT.12 RW.03', 3, 'priskaameliaamanda@gmail.com', '089529878175', 'Aktif', 'Bambang Setiawan', '081344556611', 'Yuliana Dewi', '081377799922', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(106, 'R. Dzulfikar Rosyad Putra', '1246/114.066', 'P', 'Gresik', '2007-08-27', 'Perum Cacad Veteran No.69 RT.04 RW.05, Kec. Pakal, Surabaya, Jawa Timur', 3, 'dzulfikarrosyadputra@gmail.com', '085337468714', 'Aktif', 'Agok Eka Putra', '081331412139', 'Iin Rahmawati', '081355667700', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(107, 'Ramadhan Yoga Pratama', '1247/115.066', 'L', 'Jombang', '2007-09-30', 'Omah Indah Menganti, Jl. Delima Blok F1/32A RT.29 RW.09, Gresik', 3, 'ramadhanyogapratama@gmail.com', '085608976838', 'Aktif', 'Dedi Herawanto', '087701085800', 'Wanda Mariana', '081949866638', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(108, 'Rizky Pradita Alamsyah', '1248/116.066', 'L', 'Gresik', '2007-06-23', 'Dusun Kalimalang RT.02 RW.03 Desa Pranti, Kec. Menganti, Kab. Gresik', 3, 'rizkypraditaalamsyah@gmail.com', '085853940432', 'Aktif', 'Alm. Wahono', NULL, 'Atik Kristiyawati', '082234784983', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(109, 'Rizky Umar Khadafi', '1249/117.066', 'L', 'Surabaya', '2008-02-02', 'Desa Sidowungu RT.06 RW.02, Menganti, Gresik', 3, 'rizkyumarkhadafi@gmail.com', '081336045903', 'Keluar', 'Alm. Fuad Handoyo', NULL, 'Ririyana', '0895350000004', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(110, 'Rizqika Nur Ramadhini', '1222/091.066', 'P', 'Surabaya', '2007-09-27', 'Perumahan Tanjungan Asri, Jl. Cempaka C4/23 RT.18 RW.03, Kec. Driyorejo, Kab. Gresik', 3, 'rizqikanurramadhini@gmail.com', '0881036425927', 'Aktif', 'Aris Marianto', '081553899277', 'Desi Suknita', '0884837576', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(111, 'Siti Nur Hidayah', '1251/119.066', 'P', 'Surabaya', '2007-08-29', 'Jl. Jojoran 1 No.55, Kec. Gubeng, Kota Surabaya', 3, 'sitinhidayah@gmail.com', '087851892566', 'Prakerin', 'Mian (Alm)', NULL, 'Tarmi', '087851892566', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(112, 'Shafira Aurellia Habibah', '1250/118.066', 'P', 'Surabaya', '2007-12-03', 'Jl. Intan 03 No.16 RT.01 RW.17, Kota Baru, Driyorejo', 3, 'shafiraaurellia@gmail.com', '081280927617', 'Aktif', 'Priyono Herbudi', '082244454027', 'Liziyah Novisari', '082141545051', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(113, 'Sulung Putri Nur Agus', '1252/120.066', 'P', 'Gresik', '2007-05-30', 'Dusun Tlogobedah RT.04 RW.02, Kec. Menganti, Kab. Gresik', 3, 'sulungputrinuragus@gmail.com', '0881026276070', 'Aktif', 'Agus Dwi Cahyono', '081326144118', 'Tutuk Nur Jamilah', '088805448867', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(114, 'Tegar Wijanarko', '1253/121.066', 'L', 'Gresik', '2008-04-11', 'Dusun Ngemplak Wonoayu Ceper RT.06 RW.02, Desa Mojotengah, Kec. Menganti, Kab. Gresik', 3, 'tegarwijanarko@gmail.com', '08970972729', 'Aktif', 'Sariyadi', '082132903825', 'Siti Mukaromah', '089678582523', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(115, 'Tiara Syakina Nur Azizah', '1254/122.066', 'P', 'Surabaya', '2007-08-17', 'Lakarsantri RT.003 RW.002 Surabaya', 3, 'tiarasyakina@gmail.com', '0895386137618', 'Aktif', 'Sabar Samporno', '081332004439', 'Susana', '089612300918', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(116, 'Zahwa Khoirun Nisa Azzahra', '1255/123.066', 'P', 'Gresik', '2008-01-01', 'Jl. Mawar No.123, Gresik', 3, 'zahwanisaazzahra@gmail.com', '081234567890', 'Prakerin', 'Doni', '089213891212', 'Siti', '081263772132', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(117, 'Zaki Usman', '1256/124.066', 'L', 'Surabaya', '2008-03-13', 'Dk. Gemol 2 RT.03 RW.03, Kel. Jajartunggal, Kec. Wiyung, Surabaya', 3, 'zakiusman@gmail.com', '085815173779', 'Prakerin', 'Khozin', '085815173779', 'Purwanti', '082313489721', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(118, 'Rafa Lina Rizki Febrianti', '1367/158.066', 'P', 'Gresik', '2008-02-17', 'Dusun Mojotengah RT 13 RW 06, Kec. Menganti, Kab. Gresik', 3, 'rafa@gmail.com', '89676907177', 'Aktif', 'Muhajirin', '0823128312783', 'Herlina', '0812392183192', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(119, 'Afgandi Zabriel Rizq Rabbi', '3578180112070001', 'L', 'Surabaya', '2007-12-01', 'Sepat Lidah Kulon RT. 05 RW. 03, Lakarsantri, Surabaya', 2, 'afgandizabriel@gmail.com', '081333712016', 'Prakerin', 'Rudi Santoso', '081234567940', 'Lina Kurnia', '081234567941', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(120, 'Abdur Rozaq Nur Zaqi', '3525131205080002', 'L', 'Gresik', '2008-05-12', 'Dusun Boteng RT 8 RW 3, Desa Boteng, Kec. Menganti, Kab. Gresik', 2, 'abdurrozaqnurzaqi@gmail.com', '085706661670', 'Aktif', 'Fahri Santoso', '081234567890', 'Lina Permata', '081234567891', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(121, 'Aisyah Dwi Rahmadanti', '3578186009070003', 'P', 'Surabaya', '2007-09-20', 'Dusun Desa Lakarsantri Rt02 Rw03, Kec. Lakarsantri, Kab. Surabaya', 2, 'aisyahdwi@gmail.com', '085706420446', 'Aktif', 'Rudi Pratama', '081234567892', 'Siti Aminah', '081234567893', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(122, 'Alfishal Fandi Bastian', '3578212610070003', 'L', 'Surabaya', '2007-10-26', 'Perum Graha Naila Blok B4 No.10', 2, 'alfishalfandi@gmail.com', '087788025505', 'Prakerin', 'Hadi Wijaya', '081234567946', 'Lita Permana', '081234567947', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(123, 'Bintang Sakti Air Langga Putra', '3525132908080000', 'L', 'Surabaya', '2008-08-29', 'Dusun Kecipik RT.03 RW.01, Desa Boteng, Kec. Menganti, Kab. Gresik', 2, 'bintangsakti@gmail.com', '+6281381992828', 'Prakerin', 'Joko Susilo', '081234567948', 'Maya Lestari', '081234567949', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(124, 'Candra Putra Fahminudin', '3525112702070001', 'L', 'Gresik', '2007-02-27', 'Jl Raya Morowudi Kulon Rt1 Rw4 No 71, Morowudi, Kec. Cerme, Kab. Gresik', 2, 'candraputra@gmail.com', '082140792064', 'Aktif', 'Budi Santoso', '081234567894', 'Rina Wijaya', '081234567895', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(125, 'Eva Kumala Rahmasari', '3525136005080001', 'P', 'Gresik', '2008-05-20', 'Desa Mojotengah RT:11 RW:05, Kec. Menganti, Kab. Gresik', 2, 'evakumala@gmail.com', '085649856885', 'Aktif', 'Dedi Pratama', '081234567896', 'Tina Sari', '081234567897', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(126, 'Fina Eka Rahmawati', '3525134709070006', 'P', 'Gresik', '2007-09-07', 'Dusun Pengampon Rt.11 Rw.06, Desa Setro, Kec. Menganti, Kab. Gresik', 2, 'finaeka@gmail.com', '083835136972', 'Aktif', 'Hadi Wijaya', '081234567898', 'Lita Permana', '081234567899', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(127, 'Gadis Flagellatha Agrul', '3506202805140001', 'P', 'Kediri', '2008-02-15', 'Perumahan Menganti Indah Blok C 02 Nomer 26 Rt 37', 2, 'gadisflagel@gmail.com', '083846510787', 'Aktif', 'Joko Susilo', '081234567900', 'Maya Lestari', '081234567901', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(128, 'Habiburrahman El Syirashi', '3525080507070003', 'L', 'Gresik', '2007-05-07', 'Dusun Tanjung Krajan Rt. 01 Rw 01, Desa Tanjung, Kec. Kedamean, Kab. Gresik', 2, 'habibsyirashi@gmail.com', '081334029900', 'Prakerin', 'Andi Wijaya', '081234567936', 'Tina Hartono', '081234567937', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(129, 'Haidar Hafiz Hidayatullah', '3525130603080002', 'L', 'Gresik', '2008-03-06', 'Desa Sidowungu RT.11 RW.03, Kecamatan Menganti, Kab. Gresik', 2, 'haidarhafiz@gmail.com', '089688375088', 'Prakerin', 'Budi Santoso', '081234567938', 'Rina Lestari', '081234567939', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(130, 'Indung Nurul Faidah', '3525136305070001', 'P', 'Gresik', '2007-05-23', 'Dusun Laban Kulon RT.10 RW.05, Desa Laban, Kec. Menganti, Kab. Gresik', 2, 'indungnurul@gmail.com', '085784284288', 'Aktif', 'Agus Setiawan', '081234567902', 'Dewi Sari', '081234567903', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(131, 'Khoirotun Dhea Afifahtul Mujijah', '3525136305080002', 'P', 'Gresik', '2008-05-23', 'Dusun Mojotengah, Desa Mojotengah RT 11 RW 05, Kec. Menganti, Kab. Gresik', 2, 'dheakhoirotun@gmail.com', '+6285707967703', 'Aktif', 'Hendra Wijaya', '081234567904', 'Lia Putri', '081234567905', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(132, 'Marsa Nabila Dara Puspita', '3626084403080002', 'P', 'Gresik', '2008-03-04', 'Desa Sidoraharjo Rt 6 Rw 2, Kedamean, Gresik', 2, 'marsanabila@gmail.com', '085731824178', 'Prakerin', 'Rizky Pratama', '081234567934', 'Sinta Wardani', '081234567935', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(133, 'Moch Dimas Bayu Rahmadhan', '3578232709070001', 'L', 'Surabaya', '2007-09-27', 'Golden Berry Blok EB-18', 2, 'dimasbayu@gmail.com', '081231267143', 'Aktif', 'Iwan Santoso', '081234567906', 'Nina Lestari', '081234567907', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(134, 'Muhammad Nizar Fa Iz', '3525081912070003', 'L', 'Gresik', '2007-12-19', 'Desa Kedamean RT. 011 RW. 004, Kec. Kedamean, Kab. Gresik', 2, 'nizafaiz@gmail.com', '085707073194', 'Aktif', 'Joko Permana', '081234567908', 'Rina Sari', '081234567909', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(135, 'Naufal Fatih Athaya', '3578032204070002', 'L', 'Surabaya', '2007-04-22', 'Graha Puncak Anom Sari B1 Nomer 47, Desa Wedoroanom, Kec. Driyorejo, Kab. Gresik', 2, 'naufalfatih@gmail.com', '082143266313', 'Aktif', 'Kurniawan Setia', '081234567910', 'Tika Widya', '081234567911', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(136, 'Novia Arvita Sari', '3525137011070001', 'P', 'Gresik', '2007-11-30', 'Desa Hendrosari RT 7 RW 3, Dusun Hendrosalam, Kec. Menganti, Kab. Gresik', 2, 'noviarvita@gmail.com', '088231690885', 'Aktif', 'Lukman Hakim', '081234567912', 'Sisca Putri', '081234567913', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(137, 'Olivia Rachel Amanda', '3578104707070009', 'P', 'Surabaya', '2007-07-07', 'Dusun Sukoanyar RT. 02 RW. 02, Desa Sukoanyar, Kec. Cerme, Kab. Gresik', 2, 'oliviarachel@gmail.com', '081331901380', 'Aktif', 'Marto Jaya', '081234567914', 'Umi Kalsum', '081234567915', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(138, 'Rahadian Arya Arif Wijaya', '3578300330120001', 'L', 'Surabaya', '2007-05-19', 'Pakal Madya No.37', 2, 'rahadianarya@gmail.com', '083861536904', 'Aktif', 'Rudi Hartono', '081234567916', 'Sri Wulan', '081234567917', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(139, 'Raihan Muzakka', '3525082810070001', 'L', 'Gresik', '2007-10-28', 'Dusun Bunton RT. 12 RW. 05, Desa Turirejo, Kec. Kedamean, Kab. Gresik', 2, 'raihanmuzakka@gmail.com', '085706781903', 'Aktif', 'Nugroho Widodo', '081234567918', 'Lina Setiani', '081234567919', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(140, 'Ryuzin Alexa', '3525082510080004', 'L', 'Gresik', '2008-10-25', 'Katimoho RT 4 RW 2, Kedamean, Gresik', 2, 'ryuzinalexa@gmail.com', '085730768979', 'Aktif', 'Pandu Nugraha', '081234567920', 'Sinta Dewi', '081234567921', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(141, 'Safriliya Maulina', '3525136402080003', 'P', 'Gresik', '2008-02-24', 'Dusun Bongso Kulon RT. 01 RW. 01, Desa Pengalangan, Kec. Menganti, Kab. Gresik', 2, 'safriliyamaulina@gmail.com', '082334669195', 'Aktif', 'Arif Rahman', '081234567922', 'Heni Wati', '081234567923', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(142, 'Sebastian Diaz Sampurna', '3525132505070004', 'L', 'Gresik', '2007-05-25', 'Desa Bringkang, Perumahan Oma Indah Menganti RW 09 RT 22 Blok B4/26, Kecamatan Menganti', 2, 'sebastdiaz@gmail.com', '085755787203', 'Aktif', 'Tono Wibowo', '081234567924', 'Rina Kurnia', '081234567925', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(143, 'Shylla Ayu Salsabila', '3525136102080001', 'P', 'Gresik', '2008-02-21', 'Desa Bringkang Rt 01 Rw 01, Kec Menganti, Kab Gresik', 2, 'shyllaayu@gmail.com', '089606801597', 'Aktif', 'Bambang Surya', '081234567926', 'Tuti Handayani', '081234567927', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(144, 'Siti Khumayrah', '3525084506080001', 'P', 'Gresik', '2008-06-05', 'Dsn. Doro, Ds. Ngepung Rt. 13 Rw. 05, Kec. Kedamean, Kab. Gresik', 2, 'sitikhumayrah@gmail.com', '083835554369', 'Aktif', 'Rudi Santoso', '081234567928', 'Lina Kurnia', '081234567929', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(145, 'Yalyd Firlan Ernest Kurniawan', '3525130409070001', 'L', 'Gresik', '2007-09-04', 'Dusun Pranti RT 01 RW 06, Desa Pranti, Kec. Menganti, Kab. Gresik', 2, 'yalydkurniawan@gmail.com', '083830375609', 'Keluar', 'Dwi Prasetyo', '081234567930', 'Sari Lestari', '081234567931', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(146, 'Zahra Aura Jingga', '3525136707080003', 'P', 'Gresik', '2008-06-27', 'Dusun Karang Turi RT. 25 RW. 08, Desa Menganti, Kec. Menganti, Kab. Gresik', 2, 'zahraaura@gmail.com', '085808874767', 'Aktif', 'Eko Pratama', '081234567932', 'Rina Hidayat', '081234567933', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(147, 'Alfiana Nur Sadevi', '00719366542', 'P', 'Lamongan', '2007-01-02', 'Desa Bringkang, Kec. Menganti, Kab. Gresik', 1, 'alfiana@gmail.com', '6289531234567', 'Aktif', 'Sutrisno Wijaya', '6285212345678', 'Sulastri Dewi', '6287812345678', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(148, 'Azzahra Mutiara Auliya', '3525085404070001', 'P', 'Gresik', '2007-04-14', 'Dsn Kembangan RT 16 RW 06, Ds Turirejo, Kec Kedamean, Kab Gresik, Prov Jawa Timur', 1, 'azzahramutiaraauliya@gmail.com', '085789445526', 'Prakerin', 'Rudi Santoso', '081234567950', 'Lina Kurnia', '081234567951', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(149, 'Bagas Fawaz Wahyudi', '0089092060', 'L', 'Surabaya', '2008-02-29', 'Perumahan Green Menganti blok A2/08 RT16 RW 8 Kelurahan Drancang, Menganti, Gresik', 1, 'bagas@gmail.com', '628566485189', 'Aktif', 'Hendro Santoso', '6285212345678', 'Rini Kartika', '6287712345678', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(150, 'Bunga Auliya Agustina', '0083085020', 'P', 'Gresik', '2008-08-29', 'Dusun Gempol RT.03 RW.04 Desa Lampah, Kedamean, Gresik', 1, 'bunga@gmail.com', '6285608669658', 'Aktif', 'Slamet Widodo', '6285312345678', 'Dewi Anggraini', '6287812345678', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(151, 'Elsya Syahrani Aulia Putri', '0077456300', 'P', 'Gresik', '2007-08-04', 'Dusun Pranti RT 01 RW 04 Desa Pranti, Menganti, Gresik', 1, 'elsya@gmail.com', '6285101695107', 'Aktif', 'Rahmat Hidayat', '6285712345678', 'Lestari Wulandari', '6289112345678', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(152, 'Indriani', '0083244230', 'P', 'Gresik', '2008-06-06', 'Dusun Sidowareg RT 16 RW 05 Desa Sidojangkung, Menganti, Gresik', 1, 'indriani@gmail.com', '6288235683425', 'Aktif', 'Sugeng Prabowo', '6285219876543', 'Yuliana Sari', '6287719876543', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(153, 'Jibran Romawina Al Zabir', '3507201306070001', 'L', 'Lamongan', '2007-06-13', 'Desa Putat Lor RT.005 RW.002, Kec. Menganti, Kab. Gresik', 1, 'jibranromawinaalzabir@gmail.com', '081331883565', 'Prakerin', 'Hadi Santoso', '081234567952', 'Rina Lestari', '081234567953', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(154, 'Moch Zulfa Al Audy', '0078830364', 'L', 'Surabaya', '2007-03-23', 'Setro Wetan RT.01 RW.01 Desa Setro, Menganti, Gresik', 1, 'audy@gmail.com', '6283117584955', 'Aktif', 'Agus Suryanto', '6285212348888', 'Murni Astuti', '6287812348888', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(155, 'Muhammad Azka An-nabil Firdaus', '352513140920007', 'L', 'Gresik', '2007-04-15', 'Desa Putat Lor Rt 4 Rw 2, Kec. Menganti, Kab. Gresik', 1, 'muhammadazkaannabilfirdaus@gmail.com', '081337364797', 'Prakerin', 'Iswahyudi', '081234567954', 'Ismi Habibi Alhaq', '081234567955', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(156, 'Muhammad Mihros Qolby Al-amin', '0074524972', 'L', 'Surabaya', '2007-08-09', 'Perumahan Graha Puncak Anomsari A3/22 RT.15 RW.05 Wedoroanom, Driyorejo, Gresik', 1, 'mmazpk1@gmail.com', '6288102631575', 'Aktif', 'Ahmad Syafii', '6285212221111', 'Wati', '6287888881111', NULL, '2025-09-04 23:06:37', '2025-09-05 01:34:56'),
(157, 'Muhammad Raffi Ramadhan', '3078708654', 'L', 'Surabaya', '2007-10-02', 'Dusun Kutil RT.01 RW.01 Desa Gempol Kurung, Menganti, Gresik', 1, 'raffi@gmail.com', '6281243175531', 'Aktif', 'Yuda Prasetyo', '6285217774444', 'Tutik Puspitasari', '6287815552222', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(158, 'Nabilah Galuh Candra Kirana', '0071531442', 'P', 'Gresik', '2007-07-07', 'Gang Masjid RT.02 RW.02 Desa Bringkang, Menganti, Gresik', 1, 'nabilah@gmail.com', '6285815140275', 'Aktif', 'Rudi Santoso', '6285222221111', 'Tijah Kurniasih', '6287812347777', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(159, 'Nita Nur Fadilah', '0085935160', 'P', 'Gresik', '2008-07-06', 'Dusun Kendayaan RT.03 RW.07 Desa Lampah, Kedamean, Gresik', 1, 'nita@gmail.com', '6281288458890', 'Aktif', 'Trisno', '6285233339999', 'Mia', '62858749853772', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(160, 'Novida Zahra Aulia', '0086791364', 'P', 'Gresik', '2008-02-25', 'Dusun Pranti RT.05 RW.05 Desa Pranti, Menganti, Gresik', 1, 'novida@gmail.com', '628577592468', 'Aktif', 'Rejo Pranoto', '6285666663333', 'Sutianah', '6287774442222', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(161, 'Nur Habib Ramadhan', '3309131509070002', 'L', 'Boyolali', '2007-09-15', 'Perumahan Golden Berry blok DD 10', 1, 'habib@gmail.com', '081325133671', 'Aktif', 'Ismail Pratama', '6285211112233', 'Mariana Putri', '6287811112233', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(162, 'Nur Laily Rahma Dhini', '3525135609070003', 'P', 'Gresik', '2007-09-26', 'Dusun Bongso Wetan RT.014 RW.008 Desa Pengalangan Kec. Menganti Kab. Gresik', 1, 'dhini@gmail.com', '082122983652', 'Aktif', 'Sugeng Raharjo', '6285211113344', 'Wanti Kusuma', '6287811113344', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(163, 'Nurul Izzah Lailatul Maghfiroh', '3517036906070004', 'P', 'Jombang', '2007-06-29', 'Desa Boteng RT. 12 RW. 04 Kec. Menganti Kab. Gresik', 1, 'izzah@gmail.com', '083129752807', 'Aktif', 'Ahmad Rochim', '6285211114455', 'Siti Munawaroh', '6287811114455', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37');
INSERT INTO `students` (`id`, `nama`, `nis`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `classroom_id`, `email`, `telepon`, `status`, `nama_ayah`, `telepon_ayah`, `nama_ibu`, `telepon_ibu`, `foto`, `created_at`, `updated_at`) VALUES
(164, 'Putri Amelia', '3525136604070001', 'P', 'Gresik', '2007-04-15', 'Dusun Sidowungu RT.13 RW.04 Desa Mboro Kec. Menganti Kab. Gresik', 1, 'amelia@gmail.com', '087863094791', 'Aktif', 'Rowidin Saputra', '6285211115566', 'Sumainah Dewi', '6287811115566', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(165, 'Raditya Surya Lesmana', '0074319293', 'L', 'Surabaya', '2007-04-26', 'Sidowungu RT.16 RW.4 Menganti Kab. Gresik', 1, 'radit@gmail.com', '085859302536', 'Aktif', 'Agus Prasetyo', '6285212223344', 'Sri Lestari', '6287812223344', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(166, 'Retika Putri Megasari', '0083198153', 'P', 'Kediri', '2008-03-01', 'Dusun Bongso Wetan RT.22 RW.08 Desa Pengalangan Kec. Menganti Kab. Gresik', 1, 'tika@gmail.com', '08989734532', 'Aktif', 'Heri Santoso', '6285213334455', 'Ruhmawati Dewi', '6287813334455', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(167, 'Rival Nanda Diansyah', '0072873627', 'L', 'Surabaya', '2007-12-11', 'JL. Made Selatan RT.01 RW.03', 1, 'rival@example.com', '088217809899', 'Aktif', 'Masdari Hidayat', '6285214445566', 'Erna Susanti', '6287814445566', 'students/A8ttV7GfZ0XOMc3WjEFbGevroG45GB7JHFB9apiH.png', '2025-09-04 23:06:37', '2025-09-07 02:45:58'),
(168, 'Salwa Nabilatis Syadza', '352513531007006', 'P', 'Gresik', '2007-10-13', 'Randupadangan RT.21 RW.07, Kec. Menganti, Kab. Gresik', 1, 'salwanabilatissyadza@gmail.com', '085746694561', 'Prakerin', 'Didik Santoso', '081234567956', 'Yeni Fatimah', '081234567957', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(169, 'Seril Nasukha', '3524166610070001', 'P', 'Lamongan', '2007-10-26', 'Dsn Beludsarirejo, Ds Mojosari, Kec Mantup, Kab Lamongan', 1, 'serilnasukha@gmail.com', '082132934461', 'Prakerin', 'Nurul Huda', '081234567958', 'Sunifah', '081234567959', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37'),
(170, 'Zahrakirana Budi Febriyanti', '0086229888', 'P', 'Tuban', '2008-02-04', 'Perum Puri Menganti Indah RT.38 RW.12 Desa Menganti, Kab. Gresik', 1, 'zahra@gmail.com', '088989498707', 'Aktif', 'Budi Utomo', '6285215556677', 'Arsi Kurniawati', '6287815556677', NULL, '2025-09-04 23:06:37', '2025-09-04 23:06:37');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nip` varchar(255) DEFAULT NULL,
  `nama` varchar(255) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `tempat_lahir` varchar(255) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `no_telepon` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `jabatan` varchar(255) NOT NULL,
  `mata_pelajaran` varchar(255) DEFAULT NULL,
  `status` enum('Aktif','Tidak Aktif','Cuti') NOT NULL DEFAULT 'Aktif',
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `user_id`, `nip`, `nama`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `no_telepon`, `email`, `alamat`, `jabatan`, `mata_pelajaran`, `status`, `foto`, `created_at`, `updated_at`) VALUES
(1, NULL, '872929449269789443', 'Drs. Nuripan, M.Pd.', 'L', 'Gresik', '2000-01-01', '081111111111', 'guru1@gmail.com', 'Gresik', 'Kepala Sekolah', 'null', 'Aktif', 'teachers/FoyKN6JYKmcENI6n76oWClWNd1SpxMBhtnoQPSz7.jpg', '2025-09-04 23:05:00', '2025-09-05 01:07:40'),
(2, NULL, '325572716927802599', 'Nur Qomari, M.Pd.', 'L', 'Gresik', '2000-01-01', '082222222222', 'guru2@gmail.com', 'Gresik', 'Wakil Kepala Sekolah', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(3, NULL, '820617609836683862', 'Sutrisno, M.Pd.', 'L', 'Gresik', '2000-01-01', '083333333333', 'guru3@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(4, NULL, '241227592367987930', 'Drs. Sudirdjo', 'L', 'Gresik', '2000-01-01', '084444444444', 'guru4@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(5, NULL, '461280981678929321', 'Drs. Dwi Jatmiko', 'L', 'Gresik', '2000-01-01', '085555555555', 'guru5@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(6, NULL, '368218944117813332', 'M. Hasin, S. Ag.', 'L', 'Gresik', '2000-01-01', '086666666666', 'guru6@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(7, NULL, '672726166445488151', 'Wasis Supeno, S. Pd, MT.', 'L', 'Gresik', '2000-01-01', '087777777777', 'guru7@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(8, NULL, '383744041710533320', 'Kokoh Indranto, ST.', 'L', 'Gresik', '2000-01-01', '088888888888', 'guru8@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(9, NULL, '459081869174544916', 'Adi Swandana, S. Pd.', 'L', 'Gresik', '2000-01-01', '089999999999', 'guru9@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(10, NULL, '302481679811565149', 'Drs. Sokip', 'L', 'Gresik', '2000-01-01', '081010101010', 'guru10@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(11, NULL, '994127308415276381', 'Akhmad Ikhsan, M. Fil. I.', 'L', 'Gresik', '2000-01-01', '089765435643', 'guru11@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(12, NULL, '112738920567834902', 'Wandik Mashudi, S. Pd.', 'L', 'Gresik', '2000-01-01', '089765435643', 'guru12@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(13, NULL, '887621459230158732', 'Herlina Septiyorini, S. Si.', 'P', 'Gresik', '2000-01-01', '089765435643', 'guru13@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(14, NULL, '563098127459002837', 'Ghofur, S. Pd. I.', 'L', 'Gresik', '2000-01-01', '089765435643', 'guru14@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(15, NULL, '745823910265873491', 'Mufarohah, S. Pd. I.', 'P', 'Gresik', '2000-01-01', '089765435643', 'guru15@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(16, NULL, '268392157483920145', 'Pasiadi, S. Pd. I.', 'L', 'Gresik', '2000-01-01', '089765435643', 'guru16@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(17, NULL, '917382645019283746', 'Ervin Dwi Priyanto, S. Pd.', 'L', 'Gresik', '2000-01-01', '089765435643', 'guru17@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(18, NULL, '385920176459283746', 'Muchlis Warsito, S. Pd.', 'L', 'Gresik', '2000-01-01', '089765435643', 'guru18@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(19, NULL, '509283746592837465', 'Yusfita Prawitasari, S. Pd.', 'P', 'Gresik', '2000-01-01', '089765435643', 'guru19@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(20, NULL, '673920581746592837', 'Tulus Budi Hartoyo, M. Pd. 1', 'L', 'Gresik', '2000-01-01', '089765435643', 'guru20@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(21, NULL, '938471029384756192', 'Nurul Huda S, Pd. I.', 'L', 'Gresik', '2000-01-01', '089765435643', 'guru21@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(22, NULL, '192837465092837465', 'Rizky Aditya, S. E.', 'L', 'Gresik', '2000-01-01', '089765435643', 'guru22@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(23, NULL, '746592837465092837', 'Abdul Rochman, S. Pd.', 'L', 'Gresik', '2000-01-01', '089765435643', 'guru23@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(24, NULL, '593847102938475610', 'Abdul Rochim, S. Pd.', 'L', 'Gresik', '2000-01-01', '089765435643', 'guru24@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(25, NULL, '849201938475610293', 'M. Reza Pahlevi, S. Pd.', 'L', 'Gresik', '2000-01-01', '089765435643', 'guru25@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(26, NULL, '304958102938475610', 'Nor Oktaviyanti, S. Pd.', 'P', 'Gresik', '2000-01-01', '089765435643', 'guru26@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(27, NULL, '657483920174659283', 'Putri Suhandari, S. Pd.', 'P', 'Gresik', '2000-01-01', '089765435643', 'guru27@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(28, NULL, '120394857610293847', 'Miftakhul Hijriyah, S. Kom.', 'P', 'Gresik', '2000-01-01', '089765435643', 'guru28@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(29, NULL, '574839201938475610', 'Lailatul Miftachurriyah, S. Pd.', 'P', 'Gresik', '2000-01-01', '089765435643', 'guru29@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(30, NULL, '987654321098765432', 'Nur Izatul Khumairoh, S. E.', 'P', 'Gresik', '2000-01-01', '089765435643', 'guru30@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(31, NULL, '109283746592837465', 'Masdar Hilmi, S. E.', 'L', 'Gresik', '2000-01-01', '089765435643', 'guru31@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(32, NULL, '837465920183746592', 'Naning Yuliani, M. Psi.', 'P', 'Gresik', '2000-01-01', '089765435643', 'guru32@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(33, NULL, '647382910283746592', 'M. Syahrun Nizam, S. H.', 'L', 'Gresik', '2000-01-01', '089765435643', 'guru33@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(34, NULL, '283746591028374659', 'Eka Agustina Maulida, S. Mat.', 'P', 'Gresik', '2000-01-01', '089765435643', 'guru34@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(35, NULL, '485920183746592837', 'Laili Rohmah, S. Pd.', 'P', 'Gresik', '2000-01-01', '089765435643', 'guru35@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(36, NULL, '918273645091827364', 'Jakfad Sodik, S. Pd.', 'L', 'Gresik', '2000-01-01', '089765435643', 'guru36@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(37, NULL, '384756192837465092', 'Cindi Nuriana, S. S.', 'P', 'Gresik', '2000-01-01', '089765435643', 'guru37@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(38, NULL, '506192837465092837', 'Ayu Diah Arianti, S. Pd.', 'P', 'Gresik', '2000-01-01', '089765435643', 'guru38@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(39, NULL, '719283746591028374', 'Mufidatun Nuriftahiah, S. Pd.', 'P', 'Gresik', '2000-01-01', '089765435643', 'guru39@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(40, NULL, '928374659102837465', 'Nur Ainiyatun Nabilah, S. Psi.', 'P', 'Gresik', '2000-01-01', '089765435643', 'guru40@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(41, NULL, '384750192837465092', 'Faris Irfanto S. E.', 'L', 'Gresik', '2000-01-01', '089765435643', 'guru41@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(42, NULL, '560192837465092837', 'Eka Laila Juli Anita S. Pd.', 'P', 'Gresik', '2000-01-01', '089765435643', 'guru42@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(43, NULL, '748291037465910283', 'Fatimatul Khusna S. Pd.', 'P', 'Gresik', '2000-01-01', '089765435643', 'guru43@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(44, NULL, '294857610928374659', 'Dinda Darma Wanti S. Pd.', 'P', 'Gresik', '2000-01-01', '089765435643', 'guru44@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(45, NULL, '918374650192837465', 'Rofif Nursofi S. Pd.', 'L', 'Gresik', '2000-01-01', '089765435643', 'guru45@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(46, NULL, '304958712938475610', 'M. Sokib S. Pd.', 'L', 'Gresik', '2000-01-01', '089765435643', 'guru46@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(47, NULL, '657483920193847561', 'Siti Nur Aisyah S. Pd.', 'P', 'Gresik', '2000-01-01', '089765435643', 'guru47@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(48, NULL, '294857610283746591', 'Novita S. Pd.', 'P', 'Gresik', '2000-01-01', '089765435643', 'guru48@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(49, NULL, '918374659203847561', 'Widya Ayu Lestari S. Pd.', 'P', 'Gresik', '2000-01-01', '089765435643', 'guru49@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(50, NULL, '374650192837465092', 'Luthfiyah Anggraini S. Pd.', 'P', 'Gresik', '2000-01-01', '089765435643', 'guru50@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(51, NULL, '561928374659102837', 'Ustadzah Syamsiyah', 'L', 'Gresik', '2000-01-01', '089765435643', 'guru51@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(52, NULL, '748291037465092837', 'Ustad Hibbin', 'L', 'Gresik', '2000-01-01', '089765435643', 'guru52@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(53, NULL, '293847561092837465', 'Ustadzah Umi', 'L', 'Gresik', '2000-01-01', '089765435643', 'guru53@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00'),
(54, NULL, '918374650192837462', 'Ustad Rega', 'L', 'Gresik', '2000-01-01', '089765435643', 'guru54@gmail.com', 'Gresik', 'Guru Mapel', NULL, 'Aktif', NULL, '2025-09-04 23:05:00', '2025-09-04 23:05:00');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `kode` varchar(255) NOT NULL,
  `bill_id` bigint(20) UNSIGNED NOT NULL,
  `nominal` decimal(10,2) NOT NULL,
  `metode_pembayaran` enum('Pembayaran melalui tabungan','Pembayaran melalui uang cash') NOT NULL,
  `status` enum('Pending','Berhasil','Gagal') NOT NULL DEFAULT 'Pending',
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `kode`, `bill_id`, `nominal`, `metode_pembayaran`, `status`, `catatan`, `created_at`, `updated_at`) VALUES
(1, 5, 'TRX-20250905-0001', 2, 2000000.00, '', 'Berhasil', NULL, '2025-09-05 00:01:51', '2025-09-05 00:01:51'),
(2, 1, 'TRX-20251001-0001', 6, 200000.00, '', 'Berhasil', NULL, '2025-09-30 22:26:33', '2025-09-30 22:26:33'),
(3, 1, 'TRX-20251001-0002', 5, 500000.00, '', 'Berhasil', NULL, '2025-09-30 22:30:37', '2025-09-30 22:30:37'),
(4, 1, 'TRX-20251001-0003', 4, 200000.00, '', 'Berhasil', NULL, '2025-09-30 22:31:46', '2025-09-30 22:31:46'),
(5, 1, 'TRX-20251001-0004', 3, 800000.00, '', 'Berhasil', NULL, '2025-09-30 22:33:33', '2025-09-30 22:33:33'),
(6, 1, 'TRX-20251001-0005', 1, 1000000.00, '', 'Berhasil', NULL, '2025-09-30 22:34:33', '2025-09-30 22:34:33'),
(7, 5, 'TRX-20251008-0001', 8, 500000.00, 'Pembayaran melalui tabungan', 'Berhasil', NULL, '2025-10-08 09:55:41', '2025-10-08 09:55:41'),
(9, 4, 'TRX-20251009-0001', 9, 200000.00, 'Pembayaran melalui tabungan', 'Berhasil', NULL, '2025-10-09 04:28:41', '2025-10-09 05:24:45');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `uuid` char(36) NOT NULL,
  `student_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `status` enum('Pending','Aktif','Tidak Aktif') NOT NULL DEFAULT 'Pending',
  `photo` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `uuid`, `student_id`, `name`, `email`, `status`, `photo`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', '7cd09c0a-349c-4a4e-9ed2-d7c314ff3edf', NULL, 'Admin', 'admin@gmail.com', 'Aktif', 'photo/CjwXttG3Tk01F37epUcyWnpEJsXIn4NrXRFBDS6e.png', '$2y$12$g2hP/crMiDyQCxQsEexQXe/ly8O9r1xAoaicqq4eIYEYIDEhR8uvq', NULL, '2025-08-30 11:37:14', '2025-09-05 00:53:04'),
(2, 'adi123', '85e01326-f400-4df2-bf41-26a8e1ef5451', 154, 'Adi', 'adizulfa@gmail.com', 'Aktif', NULL, '$2y$12$p6eSgodQPq3LnwIfYtJJT.tmjFT9JFJSwPmAzFPFuMRGlNMo9U93y', NULL, '2025-09-02 07:24:48', '2025-09-04 23:25:02'),
(3, 'amin123', '85113008-f8b9-4779-96f3-72031ff97a79', 156, 'Amin', 'amin@gmail.com', 'Aktif', NULL, '$2y$12$rPT5lxbf6nS85U4cK0SEnuFrYBk/58XZgha81JhyJvR/McN6Pij/W', NULL, '2025-09-03 12:05:51', '2025-09-04 23:25:13'),
(4, 'nanda123', '060b32d0-f051-48d1-a87f-410fe299b6b6', 167, 'Rival', 'vall@gmail.com', 'Aktif', NULL, '$2y$12$nckTxKi9Eadn4tdQ5knoPukeZW3RehkA4JKlA1VSjDvMYTHht6TWi', NULL, '2025-09-04 23:25:52', '2025-09-04 23:25:52'),
(5, 'bendahara123', '956fd247-24f8-4371-91a1-5effaaad9f88', NULL, 'Bendahara', 'bendahara@gmail.com', 'Aktif', 'photo/xrM11ie0HuNjwXCVWeH2dGvFmcorYU0zpdHrTQcT.png', '$2y$12$rxQPVPFFTAqDRNct4DLJ4u0eg05PDPAVR3AWUJDaetegh5hhoGQHu', NULL, '2025-09-05 00:45:50', '2025-09-05 01:00:06'),
(8, 'habib', '51812b42-8e2f-485a-b78d-95ee8a804f11', 157, 'Habib', 'mmazpk1@gmail.com', 'Aktif', NULL, '$2y$12$Ehr15fNC5nJb9q7wKaRMPuCRa0//sEOrvQYp0OMIyygompXMTJ0M2', NULL, '2025-09-09 20:18:32', '2025-09-09 20:38:08'),
(10, 'raffi', '249e5490-7ebe-45a1-a11c-8f811a78726b', 161, 'raffi', 'coba84605@gmail.com', 'Aktif', NULL, '$2y$12$xIyt4O1oir5duZ82XlTXNetEnnFwt5u7lTM6XfX2ekeSJQs3P0p3q', NULL, '2025-10-19 19:31:56', '2025-10-19 19:50:31'),
(11, 'tika', 'f55d2f8d-651f-413a-af2c-97bb852a6899', 166, 'habib', 'adizulfa02@gmail.com', 'Aktif', NULL, '$2y$12$xSnNc6f1yGNLZBp30YiZ0eQo10Of7rlrDI/.4GvA4bhqbhje4Sxge', NULL, '2025-10-19 19:48:53', '2025-10-19 19:49:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bills`
--
ALTER TABLE `bills`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bills_kode_unique` (`kode`),
  ADD KEY `bills_siswa_id_foreign` (`student_id`),
  ADD KEY `bills_jenis_pembayaran_id_foreign` (`payment_type_id`),
  ADD KEY `bills_tahun_ajaran_id_foreign` (`school_year_id`);

--
-- Indexes for table `classrooms`
--
ALTER TABLE `classrooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `classrooms_wali_kelas_id_foreign` (`wali_kelas_id`),
  ADD KEY `classrooms_major_id_foreign` (`major_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `majors`
--
ALTER TABLE `majors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `majors_kode_unique` (`kode`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payment_types`
--
ALTER TABLE `payment_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `savings`
--
ALTER TABLE `savings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `savings_siswa_id_foreign` (`student_id`),
  ADD KEY `savings_user_id_foreign` (`user_id`);

--
-- Indexes for table `saving_balances`
--
ALTER TABLE `saving_balances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `saving_balances_student_id_foreign` (`student_id`);

--
-- Indexes for table `school_years`
--
ALTER TABLE `school_years`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_uuid_unique` (`uuid`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `students_nis_unique` (`nis`),
  ADD UNIQUE KEY `students_email_unique` (`email`),
  ADD KEY `students_classroom_id_foreign` (`classroom_id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `teachers_nip_unique` (`nip`),
  ADD UNIQUE KEY `nip` (`nip`),
  ADD KEY `fk_user` (`user_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transactions_kode_unique` (`kode`),
  ADD KEY `transactions_tagihan_id_foreign` (`bill_id`),
  ADD KEY `transactions_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_uuid_unique` (`uuid`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD KEY `users_student_id_foreign` (`student_id`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bills`
--
ALTER TABLE `bills`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `classrooms`
--
ALTER TABLE `classrooms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `majors`
--
ALTER TABLE `majors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `payment_types`
--
ALTER TABLE `payment_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `savings`
--
ALTER TABLE `savings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `saving_balances`
--
ALTER TABLE `saving_balances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `school_years`
--
ALTER TABLE `school_years`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=171;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bills`
--
ALTER TABLE `bills`
  ADD CONSTRAINT `bills_jenis_pembayaran_id_foreign` FOREIGN KEY (`payment_type_id`) REFERENCES `payment_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bills_siswa_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bills_tahun_ajaran_id_foreign` FOREIGN KEY (`school_year_id`) REFERENCES `school_years` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `classrooms`
--
ALTER TABLE `classrooms`
  ADD CONSTRAINT `classrooms_major_id_foreign` FOREIGN KEY (`major_id`) REFERENCES `majors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `classrooms_wali_kelas_id_foreign` FOREIGN KEY (`wali_kelas_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `savings`
--
ALTER TABLE `savings`
  ADD CONSTRAINT `savings_siswa_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `savings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `saving_balances`
--
ALTER TABLE `saving_balances`
  ADD CONSTRAINT `saving_balances_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_classroom_id_foreign` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `teachers`
--
ALTER TABLE `teachers`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `teachers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_tagihan_id_foreign` FOREIGN KEY (`bill_id`) REFERENCES `bills` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
