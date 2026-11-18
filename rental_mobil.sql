-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 13 Bulan Mei 2026 pada 11.11
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rental_mobil`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `bookings`
--

CREATE TABLE `bookings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `car_id` bigint(20) UNSIGNED NOT NULL,
  `booking_code` varchar(50) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_price` decimal(15,2) NOT NULL,
  `dp_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('confirmed','pending_dp','plan') NOT NULL DEFAULT 'plan',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `bookings`
--

INSERT INTO `bookings` (`id`, `customer_id`, `car_id`, `booking_code`, `start_date`, `end_date`, `total_price`, `dp_amount`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'BK001', '2026-05-01', '2026-05-05', 2500000.00, 1000000.00, 'confirmed', 'Booking keluarga', NULL, NULL),
(2, 2, 1, 'BK002', '2026-05-07', '2026-05-10', 1800000.00, 500000.00, 'pending_dp', 'Belum lunas DP', NULL, NULL),
(3, 3, 1, 'BK003', '2026-05-12', '2026-05-18', 3200000.00, 1500000.00, 'confirmed', 'Perjalanan dinas', NULL, NULL),
(4, 4, 2, 'BK004', '2026-05-01', '2026-05-01', 1200000.00, 500000.00, 'confirmed', 'Acara keluarga', NULL, NULL),
(5, 5, 2, 'BK005', '2026-05-05', '2026-05-06', 1000000.00, 0.00, 'pending_dp', 'Belum DP', NULL, NULL),
(6, 6, 2, 'BK006', '2026-05-11', '2026-05-14', 1500000.00, 700000.00, 'confirmed', 'Keperluan kantor', NULL, NULL),
(7, 7, 3, 'BK007', '2026-05-22', '2026-05-24', 2000000.00, 1000000.00, 'confirmed', 'Wisata keluarga', NULL, NULL),
(8, 8, 4, 'BK008', '2026-05-27', '2026-05-30', 4000000.00, 2000000.00, 'confirmed', 'Trip luar kota', NULL, NULL),
(9, 9, 5, 'BK009', '2026-05-16', '2026-05-18', 3500000.00, 0.00, 'plan', 'Masih rencana', NULL, NULL),
(10, 10, 7, 'BK010', '2026-05-19', '2026-05-21', 1300000.00, 500000.00, 'confirmed', 'Perjalanan pribadi', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `cars`
--

CREATE TABLE `cars` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `plate_number` varchar(50) NOT NULL,
  `total_unit` int(11) NOT NULL DEFAULT 1,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('available','maintenance') NOT NULL DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `cars`
--

INSERT INTO `cars` (`id`, `name`, `plate_number`, `total_unit`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Toyota Avanza', 'B 1234 ABC', 6, 'avanza.png', 'available', NULL, NULL),
(2, 'Daihatsu Xenia', 'B 2345 DEF', 4, 'xenia.png', 'available', NULL, NULL),
(3, 'Toyota Innova Reborn', 'B 3456 GHI', 3, 'innova.png', 'available', NULL, NULL),
(4, 'Toyota Fortuner', 'B 4567 JKL', 2, 'fortuner.png', 'available', NULL, NULL),
(5, 'Mitsubishi Pajero Sport', 'B 5678 MNO', 2, 'pajero.png', 'available', NULL, NULL),
(6, 'Toyota Hiace', 'B 6789 PQR', 2, 'hiace.png', 'available', NULL, NULL),
(7, 'Honda Brio', 'B 7890 STU', 3, 'brio.png', 'available', NULL, NULL),
(8, 'Toyota Avanza', 'B 1587 ABC', 6, 'avanza.png', 'available', NULL, NULL),
(9, 'Toyota Avanza', 'B 1567 ABC', 6, 'avanza.png', 'available', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `customers`
--

INSERT INTO `customers` (`id`, `name`, `phone`, `address`, `created_at`, `updated_at`) VALUES
(1, 'Budi Santoso', '081234567890', 'Jakarta', NULL, NULL),
(2, 'Andi Wijaya', '081234567891', 'Bandung', NULL, NULL),
(3, 'Rina Wulandari', '081234567892', 'Surabaya', NULL, NULL),
(4, 'Fajar Setiawan', '081234567893', 'Bekasi', NULL, NULL),
(5, 'Lina Marina', '081234567894', 'Depok', NULL, NULL),
(6, 'Rahmat Hidayat', '081234567895', 'Bogor', NULL, NULL),
(7, 'Siti Aisyah', '081234567896', 'Tangerang', NULL, NULL),
(8, 'Doddy Kurniawan', '081234567897', 'Semarang', NULL, NULL),
(9, 'Michael T.', '081234567898', 'Yogyakarta', NULL, NULL),
(10, 'Putri Ayu', '081234567899', 'Malang', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
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
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2026_05_12_071942_create_customers_table', 1),
(6, '2026_05_12_072007_create_cars_table', 1),
(7, '2026_05_12_072029_create_bookings_table', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bookings_customer_id_foreign` (`customer_id`),
  ADD KEY `bookings_car_id_foreign` (`car_id`);

--
-- Indeks untuk tabel `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indeks untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `cars`
--
ALTER TABLE `cars`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_car_id_foreign` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
