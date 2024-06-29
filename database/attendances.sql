-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 20, 2024 at 10:30 AM
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
-- Database: `attendances`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendances`
--

CREATE TABLE `attendances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `date` date NOT NULL DEFAULT '2024-06-19',
  `checkin` time DEFAULT NULL,
  `checkout` time DEFAULT NULL,
  `on_break` time DEFAULT NULL,
  `off_break` time DEFAULT NULL,
  `total_hours` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('123456789|127.0.0.1', 'i:2;', 1718777253),
('123456789|127.0.0.1:timer', 'i:1718777253;', 1718777253),
('spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:8:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:9:\"role-list\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:11:\"role-create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:9:\"role-edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:11:\"role-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:9:\"user-list\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:11:\"user-create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:9:\"user-edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:11:\"user-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}}s:5:\"roles\";a:2:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:5:\"Admin\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:4:\"User\";s:1:\"c\";s:3:\"web\";}}}', 1718947203);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_05_20_052917_create_permission_tables', 1),
(5, '2024_05_20_052941_create_products_table', 1),
(6, '2024_06_17_091242_create_personal_access_tokens_table', 1),
(7, '2024_06_17_094319_create_attandance_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2),
(2, 'App\\Models\\User', 3),
(2, 'App\\Models\\User', 4),
(2, 'App\\Models\\User', 5),
(2, 'App\\Models\\User', 6),
(2, 'App\\Models\\User', 7),
(2, 'App\\Models\\User', 9),
(2, 'App\\Models\\User', 10),
(2, 'App\\Models\\User', 11),
(2, 'App\\Models\\User', 12);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('Jaydevmori2003@gmail.com', '$2y$12$dgy.g.tBPc5Bzm7ts5Qc9.xIZRDK0oNupA0bGzx7NQvNgMsIVZYfu', '2024-06-20 01:14:36');

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
(1, 'role-list', 'web', '2024-06-18 22:53:15', '2024-06-18 22:53:15'),
(2, 'role-create', 'web', '2024-06-18 22:53:15', '2024-06-18 22:53:15'),
(3, 'role-edit', 'web', '2024-06-18 22:53:15', '2024-06-18 22:53:15'),
(4, 'role-delete', 'web', '2024-06-18 22:53:15', '2024-06-18 22:53:15'),
(5, 'user-list', 'web', '2024-06-18 22:53:15', '2024-06-18 22:53:15'),
(6, 'user-create', 'web', '2024-06-18 22:53:15', '2024-06-18 22:53:15'),
(7, 'user-edit', 'web', '2024-06-18 22:53:15', '2024-06-18 22:53:15'),
(8, 'user-delete', 'web', '2024-06-18 22:53:15', '2024-06-18 22:53:15');

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

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 2, 'API TOKEN', '77a2dd04968bf322fb32adc82177a4faef64d0a7ee9e4dee3451571ff6c7cf03', '[\"*\"]', '2024-06-18 23:15:31', NULL, '2024-06-18 23:14:06', '2024-06-18 23:15:31'),
(2, 'App\\Models\\User', 3, 'API TOKEN', 'b10aa950e40a845c397df4aed4608575d45acb0f5dc2db4137d0bf675f72212d', '[\"*\"]', '2024-06-19 00:26:46', NULL, '2024-06-19 00:24:17', '2024-06-19 00:26:46'),
(3, 'App\\Models\\User', 4, 'API TOKEN', '11f0bdb077518009d7a467304bcd2b23608de66d4952bbd40754e6a7cd056461', '[\"*\"]', '2024-06-19 01:12:12', NULL, '2024-06-19 00:28:32', '2024-06-19 01:12:12'),
(4, 'App\\Models\\User', 4, 'API TOKEN', '3ac8c8fe05d5d6f048e6b47c5e60dffab4f79a6f03e6efdd7e6577ef98b22f49', '[\"*\"]', '2024-06-19 01:14:53', NULL, '2024-06-19 01:14:23', '2024-06-19 01:14:53'),
(5, 'App\\Models\\User', 4, 'API TOKEN', 'b67d665a50441a1282984b2da45bf0ae4a725226c60c310c541b7f9ac6b03536', '[\"*\"]', '2024-06-19 01:23:39', NULL, '2024-06-19 01:21:07', '2024-06-19 01:23:39'),
(6, 'App\\Models\\User', 5, 'API TOKEN', '8144f25ab0b106ac4e9cd51fca6c4cbe9d1b3d05b9244a1d62b25d368e829172', '[\"*\"]', '2024-06-19 03:47:02', NULL, '2024-06-19 01:25:14', '2024-06-19 03:47:02'),
(7, 'App\\Models\\User', 5, 'API TOKEN', '4374cafe43c5aec49168d85ca3e93c66e6c70b0998be36f67bc5d7b0e3d4d331', '[\"*\"]', '2024-06-19 01:42:41', NULL, '2024-06-19 01:42:30', '2024-06-19 01:42:41'),
(8, 'App\\Models\\User', 1, 'API TOKEN', '1be5017776fd97d2adfc95e801f131e1e6faacafa0b24560f9ec2d24be3f7de4', '[\"*\"]', '2024-06-19 01:47:56', NULL, '2024-06-19 01:46:16', '2024-06-19 01:47:56'),
(9, 'App\\Models\\User', 2, 'API TOKEN', 'e30f4b7dc615594813f3dd944cc280f1faaf493f531c034bf7d15bbb05357ceb', '[\"*\"]', '2024-06-19 01:49:06', NULL, '2024-06-19 01:48:55', '2024-06-19 01:49:06'),
(10, 'App\\Models\\User', 6, 'API TOKEN', '5b667a4eeabbf012b65b42b2da3453a8cf0b2a972db9824ad035d28d6ba8d2aa', '[\"*\"]', '2024-06-19 04:05:54', NULL, '2024-06-19 03:53:31', '2024-06-19 04:05:54'),
(11, 'App\\Models\\User', 6, 'API TOKEN', 'dffa6e5cbfb1a4e1d99bec045d61e72e812e4123daa69bc8cfb0cccae27ac8b8', '[\"*\"]', '2024-06-19 04:09:04', NULL, '2024-06-19 04:08:47', '2024-06-19 04:09:04'),
(12, 'App\\Models\\User', 2, 'API TOKEN', '93c56174b578b78a296ec50ca7cbd63cd14aedd1fb5d7e59ec975c52e1d96de6', '[\"*\"]', NULL, NULL, '2024-06-19 04:10:58', '2024-06-19 04:10:58'),
(13, 'App\\Models\\User', 2, 'API TOKEN', 'e9880b8ee5b491983c85f95fd490a5e539f2478695f8a00264b4e4de0d551703', '[\"*\"]', '2024-06-19 04:12:07', NULL, '2024-06-19 04:11:50', '2024-06-19 04:12:07'),
(14, 'App\\Models\\User', 6, 'API TOKEN', '79d1142a77a6858c6270881cd7a5307965d9eee4bc1827b9032fee315b20506e', '[\"*\"]', '2024-06-19 04:19:06', NULL, '2024-06-19 04:13:14', '2024-06-19 04:19:06'),
(15, 'App\\Models\\User', 2, 'API TOKEN', '764519815cffc917751f3153e0fb2afdc51135063680e768d4e83b304b83bb40', '[\"*\"]', '2024-06-19 04:20:03', NULL, '2024-06-19 04:19:25', '2024-06-19 04:20:03'),
(16, 'App\\Models\\User', 6, 'API TOKEN', '4c47f601977d5a8847ae3eb5ec1a59613ee9d6356c860e0d1fea03c8ce361068', '[\"*\"]', '2024-06-19 04:21:14', NULL, '2024-06-19 04:20:56', '2024-06-19 04:21:14'),
(17, 'App\\Models\\User', 7, 'API TOKEN', '6f3cb16284b0b82ab5ee31abe09882938a4bed0ac7d8b4291422f9e15aad57e2', '[\"*\"]', '2024-06-19 04:28:53', NULL, '2024-06-19 04:27:09', '2024-06-19 04:28:53'),
(18, 'App\\Models\\User', 4, 'API TOKEN', 'b33a99b20eaa9d49c5ae46959058a471fdf2821af6d292737d7041a6500e4a95', '[\"*\"]', '2024-06-19 04:30:52', NULL, '2024-06-19 04:29:52', '2024-06-19 04:30:52'),
(19, 'App\\Models\\User', 4, 'API TOKEN', 'a1ebdef7829fa63fe23302ec482f94f6be5885b9107ec61ab5db9be7dfdbb0d4', '[\"*\"]', '2024-06-19 04:31:41', NULL, '2024-06-19 04:31:19', '2024-06-19 04:31:41'),
(20, 'App\\Models\\User', 9, 'API TOKEN', 'cd6ecbf9ef601aff91172640b2b5f262a405c65b2cae7c9c81af8bc54363f8a0', '[\"*\"]', '2024-06-19 04:44:53', NULL, '2024-06-19 04:43:32', '2024-06-19 04:44:53'),
(21, 'App\\Models\\User', 5, 'API TOKEN', '208063a702d3a2bacaa204a31d9bc25a84fd305d617267ca6c3b85bc86963aaf', '[\"*\"]', '2024-06-19 04:46:18', NULL, '2024-06-19 04:46:03', '2024-06-19 04:46:18'),
(22, 'App\\Models\\User', 6, 'API TOKEN', 'd51f69249c4ac76c3dd3b6062d084115aceb66b03fbebc567c438c358fda992b', '[\"*\"]', '2024-06-19 04:49:30', NULL, '2024-06-19 04:49:17', '2024-06-19 04:49:30'),
(23, 'App\\Models\\User', 7, 'API TOKEN', '872b16ed53e1234e6fa17fa0a23a471f05650b0f2eae0af56bea1d76ebd1bbdb', '[\"*\"]', '2024-06-19 04:50:15', NULL, '2024-06-19 04:49:48', '2024-06-19 04:50:15'),
(24, 'App\\Models\\User', 9, 'API TOKEN', '3aa71c2e51f5356f998f617323eacf40e8dff4f695a034a40ad789cd784fa633', '[\"*\"]', '2024-06-19 04:55:39', NULL, '2024-06-19 04:55:29', '2024-06-19 04:55:39'),
(25, 'App\\Models\\User', 10, 'API TOKEN', '30a2ac864711cc715f0a472c2102300c8a770782c600291c38d8c7f5ea08a338', '[\"*\"]', '2024-06-19 04:59:57', NULL, '2024-06-19 04:58:12', '2024-06-19 04:59:57'),
(26, 'App\\Models\\User', 9, 'API TOKEN', '92e2a9b94c2e0d707d2f3918f7699e05fedb67bf9667a46389c4193dbc6f8ba7', '[\"*\"]', '2024-06-19 05:01:40', NULL, '2024-06-19 05:00:30', '2024-06-19 05:01:40'),
(27, 'App\\Models\\User', 9, 'API TOKEN', 'c42f77bc0c54fbc27da3b84e67bf939cc93aa87c8dd21b4ef692ca468ddce895', '[\"*\"]', '2024-06-19 05:05:30', NULL, '2024-06-19 05:02:25', '2024-06-19 05:05:30'),
(28, 'App\\Models\\User', 9, 'API TOKEN', 'd8c116ce61d12ee649ac2b78818964435bde7dd2602cf088ea751868c888f284', '[\"*\"]', '2024-06-19 05:18:34', NULL, '2024-06-19 05:07:25', '2024-06-19 05:18:34'),
(29, 'App\\Models\\User', 10, 'API TOKEN', '57e8985b1ad2b8309bdfca9e21a7ea9b450718ac4f9a580ae0847d96b3ac9581', '[\"*\"]', '2024-06-19 05:19:32', NULL, '2024-06-19 05:19:21', '2024-06-19 05:19:32'),
(30, 'App\\Models\\User', 2, 'API TOKEN', 'c56c216ae75b1ad0765caf55b5c6e4a9433fb7affd3a84949ef4dabac1a041c9', '[\"*\"]', '2024-06-19 05:20:06', NULL, '2024-06-19 05:19:53', '2024-06-19 05:20:06'),
(31, 'App\\Models\\User', 11, 'API TOKEN', '3f684a6e30bd909b770eb79a343cb28b7cd777e13f9202eb35f0b158fb98e780', '[\"*\"]', '2024-06-19 05:24:37', NULL, '2024-06-19 05:22:59', '2024-06-19 05:24:37'),
(32, 'App\\Models\\User', 2, 'API TOKEN', '952b6e4335da735e61ffcd67e529ea5edf6a9ba360f54e562b82eae020fca376', '[\"*\"]', '2024-06-19 23:56:31', NULL, '2024-06-19 23:55:07', '2024-06-19 23:56:31'),
(33, 'App\\Models\\User', 2, 'API TOKEN', 'b7e0e1a56f391fa141ba0db66290a1e1bb2b092850776d655f605116c7e05ccf', '[\"*\"]', '2024-06-20 01:16:24', NULL, '2024-06-20 01:16:06', '2024-06-20 01:16:24');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `detail` text NOT NULL,
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
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'web', '2024-06-18 22:53:15', '2024-06-18 22:53:15'),
(2, 'User', 'web', '2024-06-18 23:18:13', '2024-06-18 23:18:13');

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
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(5, 2),
(6, 1),
(7, 1),
(8, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('9UhX7btz6EkIXB3Rsa29WZUap3ChbypckqypjN28', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiakNZWElmb1VFaU5vakZhUDlIM3hNY3puU1U4QzB0Um9hVzk3a1dzMyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC91c2VycyI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czo0OiJhdXRoIjthOjE6e3M6MjE6InBhc3N3b3JkX2NvbmZpcm1lZF9hdCI7aToxNzE4ODcyMTE2O319', 1718872119);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `phone`, `email`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', '9979404044', 'contact@flipcodesolutions.com', '$2y$12$sGafd1vW37GYUAZn8xYvwe62zted6/xWn.ja//FHLFVpUtkwuUwDu', NULL, '2024-06-18 22:53:15', '2024-06-20 02:58:21'),
(2, 'Demo', '9999999999', 'Demo@gmail.com', '$2y$12$KINLAA5G2uzKiiHCaaiCA.rToLnE1ncEq/vIXRhxp4qIUImb633qK', NULL, '2024-06-18 23:13:51', '2024-06-18 23:13:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendances`
--
ALTER TABLE `attendances`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
