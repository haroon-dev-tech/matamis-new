-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 09, 2026 at 10:46 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `matamis`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `user_name` varchar(150) DEFAULT NULL,
  `user_email` varchar(150) DEFAULT NULL,
  `event_type` varchar(50) NOT NULL,
  `action` varchar(50) NOT NULL,
  `module_key` varchar(100) DEFAULT NULL,
  `route_path` varchar(255) NOT NULL,
  `request_method` varchar(10) NOT NULL,
  `ip_address` varchar(64) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `metadata_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata_json`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `user_name`, `user_email`, `event_type`, `action`, `module_key`, `route_path`, `request_method`, `ip_address`, `user_agent`, `description`, `metadata_json`, `created_at`) VALUES
(1, NULL, NULL, NULL, 'view', 'view_page', NULL, '/matamis-new/login.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-07 08:53:36'),
(2, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'dashboard', '/matamis-new/dashboard.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-07 08:53:39'),
(3, 1, 'System Administrator', 'admin@gmail.com', 'auth', 'logout', 'authentication', '/matamis-new/logout.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'User logged out.', NULL, '2026-07-07 08:56:25'),
(4, NULL, NULL, NULL, 'view', 'view_page', NULL, '/matamis-new/login.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-07 08:56:25'),
(5, 1, 'System Administrator', 'admin@gmail.com', 'auth', 'login_success', 'authentication', '/matamis-new/login.php', 'POST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'User logged in successfully.', NULL, '2026-07-07 08:56:29'),
(6, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'dashboard', '/matamis-new/dashboard.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-07 08:56:29'),
(7, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'dashboard', '/matamis-new/dashboard.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-07 08:58:02'),
(8, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'glance', '/matamis-new/glance/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-07 08:58:09'),
(9, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'glance', '/matamis-new/glance/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5&year_from=2026&year_to=2026&month_from=0&month_to=0&applied=1&tab=comprehensive-income\"}', '2026-07-07 08:58:11'),
(10, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'glance', '/matamis-new/glance/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5&year_from=2026&year_to=2026&month_from=0&month_to=0&applied=1&tab=financial-position\"}', '2026-07-07 08:58:13'),
(11, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'dashboard', '/matamis-new/dashboard.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-07 08:58:16'),
(12, 1, 'System Administrator', 'admin@gmail.com', 'auth', 'logout', 'authentication', '/matamis-new/logout.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'User logged out.', NULL, '2026-07-07 08:58:41'),
(13, NULL, NULL, NULL, 'view', 'view_page', NULL, '/matamis-new/login.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-07 08:58:41'),
(14, 2, 'test1', 'test1@gmail.com', 'auth', 'login_success', 'authentication', '/matamis-new/login.php', 'POST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'User logged in successfully.', NULL, '2026-07-07 08:58:57'),
(15, 2, 'test1', 'test1@gmail.com', 'view', 'view_page', 'dashboard', '/matamis-new/dashboard.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-07 08:58:57'),
(16, 2, 'test1', 'test1@gmail.com', 'access_denied', 'deny', 'dashboard', '/matamis-new/dashboard.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'Permission denied for requested resource.', '{\"required_permission\":\"dashboard\",\"required_mode\":\"read\"}', '2026-07-07 08:58:57'),
(17, 2, 'test1', 'test1@gmail.com', 'view', 'view_page', NULL, '/matamis-new/forbidden.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-07 08:58:57'),
(18, 2, 'test1', 'test1@gmail.com', 'auth', 'logout', 'authentication', '/matamis-new/logout.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'User logged out.', NULL, '2026-07-07 09:01:15'),
(19, NULL, NULL, NULL, 'view', 'view_page', NULL, '/matamis-new/login.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-07 09:01:15'),
(20, 2, 'test1', 'test1@gmail.com', 'auth', 'login_success', 'authentication', '/matamis-new/login.php', 'POST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'User logged in successfully.', NULL, '2026-07-07 09:01:23'),
(21, 2, 'test1', 'test1@gmail.com', 'view', 'list_page', 'linked_is', '/matamis-new/linked-is/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-07 09:01:23'),
(22, 2, 'test1', 'test1@gmail.com', 'view', 'form_page', 'companies', '/matamis-new/companies/create.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-07 09:01:33'),
(23, 2, 'test1', 'test1@gmail.com', 'access_denied', 'deny', 'companies', '/matamis-new/companies/create.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'Permission denied for requested resource.', '{\"required_permission\":\"companies\",\"required_mode\":\"write\"}', '2026-07-07 09:01:33'),
(24, 2, 'test1', 'test1@gmail.com', 'view', 'view_page', NULL, '/matamis-new/forbidden.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-07 09:01:33'),
(25, 2, 'test1', 'test1@gmail.com', 'view', 'view_page', 'dashboard', '/matamis-new/dashboard.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-07 09:01:36'),
(26, 2, 'test1', 'test1@gmail.com', 'access_denied', 'deny', 'dashboard', '/matamis-new/dashboard.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'Permission denied for requested resource.', '{\"required_permission\":\"dashboard\",\"required_mode\":\"read\"}', '2026-07-07 09:01:36'),
(27, 2, 'test1', 'test1@gmail.com', 'view', 'list_page', 'linked_is', '/matamis-new/linked-is/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-07 09:01:36'),
(28, 2, 'test1', 'test1@gmail.com', 'view', 'list_page', 'linked_is', '/matamis-new/linked-is/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-07 09:03:51'),
(29, 2, 'test1', 'test1@gmail.com', 'view', 'list_page', 'linked_is', '/matamis-new/linked-is/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-07 09:03:54'),
(30, 2, 'test1', 'test1@gmail.com', 'auth', 'logout', 'authentication', '/matamis-new/logout.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'User logged out.', NULL, '2026-07-07 09:04:01'),
(31, NULL, NULL, NULL, 'view', 'view_page', NULL, '/matamis-new/login.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-07 09:04:01'),
(32, 1, 'System Administrator', 'admin@gmail.com', 'auth', 'login_success', 'authentication', '/matamis-new/login.php', 'POST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'User logged in successfully.', NULL, '2026-07-07 09:04:04'),
(33, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'dashboard', '/matamis-new/dashboard.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-07 09:04:04'),
(34, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'linked_is', '/matamis-new/linked-is/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-07 09:05:50'),
(35, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'settings_users', '/matamis-new/settings/users.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-07 09:05:54'),
(36, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'somci', '/matamis-new/somci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-07 09:06:02'),
(37, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'settings_users', '/matamis-new/settings/users.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-07 09:06:06'),
(38, 1, 'System Administrator', 'admin@gmail.com', 'view', 'form_page', 'settings_users', '/matamis-new/settings/user_edit.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'HTTP request activity', '{\"query_string\":\"id=2\"}', '2026-07-07 09:06:09'),
(39, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'settings_users', '/matamis-new/settings/users.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-07 09:06:11'),
(40, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'settings_roles', '/matamis-new/settings/roles.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-07 09:06:12'),
(41, 1, 'System Administrator', 'admin@gmail.com', 'view', 'form_page', 'settings_roles', '/matamis-new/settings/role_edit.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'HTTP request activity', '{\"query_string\":\"id=2\"}', '2026-07-07 09:06:14'),
(42, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'linked_bs', '/matamis-new/linked-bs/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-07 09:06:25'),
(43, 1, 'System Administrator', 'admin@gmail.com', 'auth', 'logout', 'authentication', '/matamis-new/logout.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'User logged out.', NULL, '2026-07-07 09:06:32'),
(44, NULL, NULL, NULL, 'view', 'view_page', NULL, '/matamis-new/login.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-07 09:06:32'),
(45, 2, 'test1', 'test1@gmail.com', 'auth', 'login_success', 'authentication', '/matamis-new/login.php', 'POST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'User logged in successfully.', NULL, '2026-07-07 09:06:55'),
(46, 2, 'test1', 'test1@gmail.com', 'view', 'list_page', 'linked_is', '/matamis-new/linked-is/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-07 09:06:55'),
(47, 2, 'test1', 'test1@gmail.com', 'view', 'form_page', 'companies', '/matamis-new/companies/create.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-07 09:16:21'),
(48, 2, 'test1', 'test1@gmail.com', 'access_denied', 'deny', 'companies', '/matamis-new/companies/create.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'Permission denied for requested resource.', '{\"required_permission\":\"companies\",\"required_mode\":\"write\"}', '2026-07-07 09:16:21'),
(49, 2, 'test1', 'test1@gmail.com', 'view', 'view_page', NULL, '/matamis-new/forbidden.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-07 09:16:21'),
(50, 2, 'test1', 'test1@gmail.com', 'view', 'list_page', 'linked_is', '/matamis-new/linked-is/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-07 09:17:57'),
(51, 2, 'test1', 'test1@gmail.com', 'auth', 'logout', 'authentication', '/matamis-new/logout.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'User logged out.', NULL, '2026-07-07 09:17:58'),
(52, NULL, NULL, NULL, 'view', 'view_page', NULL, '/matamis-new/login.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-07 09:17:58'),
(53, 2, 'test1', 'test1@gmail.com', 'auth', 'login_success', 'authentication', '/matamis-new/login.php', 'POST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'User logged in successfully.', NULL, '2026-07-07 09:18:06'),
(54, 2, 'test1', 'test1@gmail.com', 'view', 'list_page', 'linked_is', '/matamis-new/linked-is/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-07 09:18:06'),
(55, 2, 'test1', 'test1@gmail.com', 'view', 'list_page', 'linked_bs', '/matamis-new/linked-bs/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-07 09:18:08'),
(56, 2, 'test1', 'test1@gmail.com', 'view', 'list_page', 'linked_is', '/matamis-new/linked-is/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-07 09:18:09'),
(57, 2, 'test1', 'test1@gmail.com', 'view', 'view_page', 'linked_is', '/matamis-new/linked-is/structure.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-07 09:18:16'),
(58, 2, 'test1', 'test1@gmail.com', 'view', 'list_page', 'linked_is', '/matamis-new/linked-is/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-07 09:18:21'),
(59, 2, 'test1', 'test1@gmail.com', 'view', 'form_page', 'linked_is', '/matamis-new/linked-is/entry.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-07 09:18:22'),
(60, 2, 'test1', 'test1@gmail.com', 'view', 'list_page', 'linked_bs', '/matamis-new/linked-bs/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-07 09:18:38'),
(61, 2, 'test1', 'test1@gmail.com', 'view', 'list_page', 'linked_bs', '/matamis-new/linked-bs/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-07 11:38:16'),
(62, 2, 'test1', 'test1@gmail.com', 'view', 'list_page', 'linked_bs', '/matamis-new/linked-bs/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-08 04:07:59'),
(63, 2, 'test1', 'test1@gmail.com', 'auth', 'logout', 'authentication', '/matamis-new/logout.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'User logged out.', NULL, '2026-07-08 04:11:30'),
(64, NULL, NULL, NULL, 'view', 'view_page', NULL, '/matamis-new/login.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-08 04:11:30'),
(65, 1, 'System Administrator', 'admin@gmail.com', 'auth', 'login_success', 'authentication', '/matamis-new/login.php', 'POST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'User logged in successfully.', NULL, '2026-07-08 04:11:39'),
(66, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'dashboard', '/matamis-new/dashboard.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-08 04:11:39'),
(67, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'linked_is', '/matamis-new/linked-is/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-08 04:14:48'),
(68, 1, 'System Administrator', 'admin@gmail.com', 'view', 'form_page', 'linked_is', '/matamis-new/linked-is/entry.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:16:41'),
(69, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'linked_is', '/matamis-new/linked-is/structure.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:22:12'),
(70, 1, 'System Administrator', 'admin@gmail.com', 'write', 'update', 'linked_is', '/matamis-new/linked-is/structure.php', 'POST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:22:17'),
(71, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'linked_is', '/matamis-new/linked-is/structure.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:22:17'),
(72, 1, 'System Administrator', 'admin@gmail.com', 'write', 'update', 'linked_is', '/matamis-new/linked-is/structure.php', 'POST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:22:20'),
(73, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'linked_is', '/matamis-new/linked-is/structure.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:22:20'),
(74, 1, 'System Administrator', 'admin@gmail.com', 'write', 'update', 'linked_is', '/matamis-new/linked-is/structure.php', 'POST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:23:39'),
(75, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'linked_is', '/matamis-new/linked-is/structure.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:23:39'),
(76, 1, 'System Administrator', 'admin@gmail.com', 'write', 'update', 'linked_is', '/matamis-new/linked-is/structure.php', 'POST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:23:51'),
(77, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'linked_is', '/matamis-new/linked-is/structure.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:23:51'),
(78, 1, 'System Administrator', 'admin@gmail.com', 'write', 'update', 'linked_is', '/matamis-new/linked-is/structure.php', 'POST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:24:09'),
(79, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'linked_is', '/matamis-new/linked-is/structure.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:24:09'),
(80, 1, 'System Administrator', 'admin@gmail.com', 'write', 'update', 'linked_is', '/matamis-new/linked-is/structure.php', 'POST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:24:21'),
(81, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'linked_is', '/matamis-new/linked-is/structure.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:24:21'),
(82, 1, 'System Administrator', 'admin@gmail.com', 'write', 'update', 'linked_is', '/matamis-new/linked-is/structure.php', 'POST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:24:41'),
(83, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'linked_is', '/matamis-new/linked-is/structure.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:24:41'),
(84, 1, 'System Administrator', 'admin@gmail.com', 'view', 'form_page', 'linked_is', '/matamis-new/linked-is/entry.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:25:13'),
(85, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'linked_is', '/matamis-new/linked-is/structure.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:25:34'),
(86, 1, 'System Administrator', 'admin@gmail.com', 'write', 'update', 'linked_is', '/matamis-new/linked-is/structure.php', 'POST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:25:48'),
(87, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'linked_is', '/matamis-new/linked-is/structure.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:25:48'),
(88, 1, 'System Administrator', 'admin@gmail.com', 'write', 'update', 'linked_is', '/matamis-new/linked-is/structure.php', 'POST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:25:57'),
(89, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'linked_is', '/matamis-new/linked-is/structure.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:25:57'),
(90, 1, 'System Administrator', 'admin@gmail.com', 'write', 'update', 'linked_is', '/matamis-new/linked-is/structure.php', 'POST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:26:04'),
(91, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'linked_is', '/matamis-new/linked-is/structure.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:26:04'),
(92, 1, 'System Administrator', 'admin@gmail.com', 'write', 'update', 'linked_is', '/matamis-new/linked-is/structure.php', 'POST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:27:22'),
(93, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'linked_is', '/matamis-new/linked-is/structure.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:27:22'),
(94, 1, 'System Administrator', 'admin@gmail.com', 'write', 'update', 'linked_is', '/matamis-new/linked-is/structure.php', 'POST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:28:18'),
(95, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'linked_is', '/matamis-new/linked-is/structure.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:28:18'),
(96, 1, 'System Administrator', 'admin@gmail.com', 'write', 'update', 'linked_is', '/matamis-new/linked-is/structure.php', 'POST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:28:26'),
(97, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'linked_is', '/matamis-new/linked-is/structure.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:28:26'),
(98, 1, 'System Administrator', 'admin@gmail.com', 'write', 'update', 'linked_is', '/matamis-new/linked-is/structure.php', 'POST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:28:34'),
(99, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'linked_is', '/matamis-new/linked-is/structure.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:28:34'),
(100, 1, 'System Administrator', 'admin@gmail.com', 'write', 'update', 'linked_is', '/matamis-new/linked-is/structure.php', 'POST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:28:42'),
(101, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'linked_is', '/matamis-new/linked-is/structure.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:28:42'),
(102, 1, 'System Administrator', 'admin@gmail.com', 'write', 'update', 'linked_is', '/matamis-new/linked-is/structure.php', 'POST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:28:49'),
(103, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'linked_is', '/matamis-new/linked-is/structure.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:28:49'),
(104, 1, 'System Administrator', 'admin@gmail.com', 'write', 'update', 'linked_is', '/matamis-new/linked-is/structure.php', 'POST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:29:07'),
(105, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'linked_is', '/matamis-new/linked-is/structure.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:29:07'),
(106, 1, 'System Administrator', 'admin@gmail.com', 'write', 'update', 'linked_is', '/matamis-new/linked-is/structure.php', 'POST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:29:15'),
(107, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'linked_is', '/matamis-new/linked-is/structure.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:29:15'),
(108, 1, 'System Administrator', 'admin@gmail.com', 'write', 'update', 'linked_is', '/matamis-new/linked-is/structure.php', 'POST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:29:22'),
(109, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'linked_is', '/matamis-new/linked-is/structure.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:29:22'),
(110, 1, 'System Administrator', 'admin@gmail.com', 'write', 'update', 'linked_is', '/matamis-new/linked-is/structure.php', 'POST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:29:28'),
(111, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'linked_is', '/matamis-new/linked-is/structure.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:29:28'),
(112, 1, 'System Administrator', 'admin@gmail.com', 'write', 'update', 'linked_is', '/matamis-new/linked-is/structure.php', 'POST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:29:35'),
(113, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'linked_is', '/matamis-new/linked-is/structure.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:29:35'),
(114, 1, 'System Administrator', 'admin@gmail.com', 'write', 'update', 'linked_is', '/matamis-new/linked-is/structure.php', 'POST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:29:42'),
(115, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'linked_is', '/matamis-new/linked-is/structure.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:29:42'),
(116, 1, 'System Administrator', 'admin@gmail.com', 'write', 'update', 'linked_is', '/matamis-new/linked-is/structure.php', 'POST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:29:49'),
(117, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'linked_is', '/matamis-new/linked-is/structure.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:29:49'),
(118, 1, 'System Administrator', 'admin@gmail.com', 'write', 'update', 'linked_is', '/matamis-new/linked-is/structure.php', 'POST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:29:56'),
(119, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'linked_is', '/matamis-new/linked-is/structure.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:29:56'),
(120, 1, 'System Administrator', 'admin@gmail.com', 'write', 'update', 'linked_is', '/matamis-new/linked-is/structure.php', 'POST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:30:03'),
(121, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'linked_is', '/matamis-new/linked-is/structure.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:30:03'),
(122, 1, 'System Administrator', 'admin@gmail.com', 'write', 'update', 'linked_is', '/matamis-new/linked-is/structure.php', 'POST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:30:10'),
(123, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'linked_is', '/matamis-new/linked-is/structure.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:30:10'),
(124, 1, 'System Administrator', 'admin@gmail.com', 'write', 'update', 'linked_is', '/matamis-new/linked-is/structure.php', 'POST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:30:17'),
(125, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'linked_is', '/matamis-new/linked-is/structure.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:30:17'),
(126, 1, 'System Administrator', 'admin@gmail.com', 'write', 'update', 'linked_is', '/matamis-new/linked-is/structure.php', 'POST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:31:17'),
(127, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'linked_is', '/matamis-new/linked-is/structure.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:31:17'),
(128, 1, 'System Administrator', 'admin@gmail.com', 'write', 'update', 'linked_is', '/matamis-new/linked-is/structure.php', 'POST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:31:25'),
(129, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'linked_is', '/matamis-new/linked-is/structure.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:31:25'),
(130, 1, 'System Administrator', 'admin@gmail.com', 'write', 'update', 'linked_is', '/matamis-new/linked-is/structure.php', 'POST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:31:33'),
(131, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'linked_is', '/matamis-new/linked-is/structure.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:31:33'),
(132, 1, 'System Administrator', 'admin@gmail.com', 'write', 'update', 'linked_is', '/matamis-new/linked-is/structure.php', 'POST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:31:40'),
(133, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'linked_is', '/matamis-new/linked-is/structure.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:31:40'),
(134, 1, 'System Administrator', 'admin@gmail.com', 'write', 'update', 'linked_is', '/matamis-new/linked-is/structure.php', 'POST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:31:49'),
(135, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'linked_is', '/matamis-new/linked-is/structure.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:31:49'),
(136, 1, 'System Administrator', 'admin@gmail.com', 'write', 'update', 'linked_is', '/matamis-new/linked-is/structure.php', 'POST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:31:57'),
(137, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'linked_is', '/matamis-new/linked-is/structure.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:31:57'),
(138, 1, 'System Administrator', 'admin@gmail.com', 'write', 'update', 'linked_is', '/matamis-new/linked-is/structure.php', 'POST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:32:10'),
(139, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'linked_is', '/matamis-new/linked-is/structure.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:32:10'),
(140, 1, 'System Administrator', 'admin@gmail.com', 'write', 'update', 'linked_is', '/matamis-new/linked-is/structure.php', 'POST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:37:32'),
(141, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'linked_is', '/matamis-new/linked-is/structure.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:37:32'),
(142, 1, 'System Administrator', 'admin@gmail.com', 'view', 'form_page', 'linked_is', '/matamis-new/linked-is/entry.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:37:34'),
(143, 1, 'System Administrator', 'admin@gmail.com', 'create', 'create_record', 'linked_is', '/matamis-new/linked-is/entry.php', 'POST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-08 04:40:09'),
(144, 1, 'System Administrator', 'admin@gmail.com', 'view', 'form_page', 'linked_is', '/matamis-new/linked-is/entry.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5&branch_id=5&year=2026&month=7&entry_date=2026-07-05\"}', '2026-07-08 04:40:09'),
(145, 1, 'System Administrator', 'admin@gmail.com', 'view', 'form_page', 'linked_is', '/matamis-new/linked-is/entry.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5&branch_id=6&year=2026&month=7&entry_date=2026-07-05\"}', '2026-07-08 04:40:22');
INSERT INTO `activity_logs` (`id`, `user_id`, `user_name`, `user_email`, `event_type`, `action`, `module_key`, `route_path`, `request_method`, `ip_address`, `user_agent`, `description`, `metadata_json`, `created_at`) VALUES
(146, 1, 'System Administrator', 'admin@gmail.com', 'view', 'form_page', 'linked_is', '/matamis-new/linked-is/entry.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5&branch_id=5&year=2026&month=7&entry_date=2026-07-05\"}', '2026-07-08 04:40:25'),
(147, 1, 'System Administrator', 'admin@gmail.com', 'view', 'form_page', 'linked_is', '/matamis-new/linked-is/entry.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5&branch_id=5&year=2026&month=7&entry_date=2026-07-08\"}', '2026-07-08 04:40:53'),
(148, 1, 'System Administrator', 'admin@gmail.com', 'view', 'form_page', 'linked_is', '/matamis-new/linked-is/entry.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5&branch_id=5&year=2026&month=7&entry_date=2026-07-08\"}', '2026-07-08 04:40:58'),
(149, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'observations', '/matamis-new/observations/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-08 11:06:42'),
(150, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'observations', '/matamis-new/observations/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 04:20:00'),
(151, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'somci', '/matamis-new/somci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 04:55:50'),
(152, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'linked_is', '/matamis-new/linked-is/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 04:56:27'),
(153, 1, 'System Administrator', 'admin@gmail.com', 'view', 'form_page', 'linked_is', '/matamis-new/linked-is/entry.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-09 04:56:32'),
(154, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'somci', '/matamis-new/somci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 05:24:00'),
(155, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'soci', '/matamis-new/soci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 05:40:57'),
(156, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'soci', '/matamis-new/soci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 06:36:06'),
(157, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'soci', '/matamis-new/soci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5&branch_id=5&year=2026&month=7\"}', '2026-07-09 06:36:19'),
(158, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'soci', '/matamis-new/soci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5&branch_id=6&year=2026&month=7\"}', '2026-07-09 06:36:22'),
(159, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'somci', '/matamis-new/somci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 06:36:26'),
(160, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'somci', '/matamis-new/somci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 07:40:56'),
(161, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'somci', '/matamis-new/somci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 07:41:26'),
(162, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'somci', '/matamis-new/somci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=6&branch_id=5&year=2026&month=7\"}', '2026-07-09 07:41:43'),
(163, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'somci', '/matamis-new/somci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5&branch_id=7&year=2026&month=7\"}', '2026-07-09 07:41:45'),
(164, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'somci', '/matamis-new/somci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5&branch_id=6&year=2026&month=7\"}', '2026-07-09 07:41:48'),
(165, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'somci', '/matamis-new/somci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5&branch_id=5&year=2026&month=7\"}', '2026-07-09 07:41:51'),
(166, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'soci', '/matamis-new/soci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 07:42:24'),
(167, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'somci', '/matamis-new/somci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 07:42:30'),
(168, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'somci', '/matamis-new/somci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 07:46:24'),
(169, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'somci', '/matamis-new/somci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5&branch_id=0&month_from=5&year_from=2026&month_to=7&year_to=2026\"}', '2026-07-09 07:46:35'),
(170, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'somci', '/matamis-new/somci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5&branch_id=0&month_from=5&year_from=2026&month_to=7&year_to=2026\"}', '2026-07-09 07:47:59'),
(171, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'somci', '/matamis-new/somci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5&branch_id=5&month_from=5&year_from=2026&month_to=7&year_to=2026\"}', '2026-07-09 07:48:17'),
(172, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'somci', '/matamis-new/somci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5&branch_id=6&month_from=5&year_from=2026&month_to=7&year_to=2026\"}', '2026-07-09 07:48:20'),
(173, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'somci', '/matamis-new/somci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5&branch_id=0&month_from=5&year_from=2026&month_to=7&year_to=2026\"}', '2026-07-09 07:48:22'),
(174, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'somci', '/matamis-new/somci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=6&branch_id=0&month_from=5&year_from=2026&month_to=7&year_to=2026\"}', '2026-07-09 07:48:26'),
(175, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'somci', '/matamis-new/somci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=6&branch_id=0&month_from=5&year_from=2026&month_to=7&year_to=2026\"}', '2026-07-09 07:48:27'),
(176, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'somci', '/matamis-new/somci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=6&branch_id=7&month_from=5&year_from=2026&month_to=7&year_to=2026\"}', '2026-07-09 07:48:30'),
(177, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'somci', '/matamis-new/somci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5&branch_id=7&month_from=5&year_from=2026&month_to=7&year_to=2026\"}', '2026-07-09 07:48:31'),
(178, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'somci', '/matamis-new/somci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5&branch_id=0&month_from=5&year_from=2026&month_to=7&year_to=2026\"}', '2026-07-09 07:48:31'),
(179, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'soci', '/matamis-new/soci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 07:48:41'),
(180, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'soci', '/matamis-new/soci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5&branch_id=0&year=2026&month=5\"}', '2026-07-09 07:48:50'),
(181, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'soci', '/matamis-new/soci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5&branch_id=0&year=2026&month=5\"}', '2026-07-09 07:48:53'),
(182, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'soci', '/matamis-new/soci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5&branch_id=0&year=2026&month=5\"}', '2026-07-09 07:48:55'),
(183, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'soci', '/matamis-new/soci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 07:48:59'),
(184, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'soci', '/matamis-new/soci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5&branch_id=0&year=2026&month=5\"}', '2026-07-09 07:50:20'),
(185, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'soci', '/matamis-new/soci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5&branch_id=0&year=2026&month=5\"}', '2026-07-09 07:50:24'),
(186, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'soci', '/matamis-new/soci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5&branch_id=0&year=2026&month=7\"}', '2026-07-09 07:50:33'),
(187, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'soci', '/matamis-new/soci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5&branch_id=0&year=2026&month=7\"}', '2026-07-09 07:51:41'),
(188, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'soci', '/matamis-new/soci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5&branch_id=0&year=2026&month=6\"}', '2026-07-09 07:51:45'),
(189, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'soci', '/matamis-new/soci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-09 07:51:46'),
(190, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'sofp', '/matamis-new/sofp/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 07:51:57'),
(191, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'somci', '/matamis-new/somci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 07:51:59'),
(192, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'somfp', '/matamis-new/somfp/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 07:52:15'),
(193, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'somfp', '/matamis-new/somfp/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 07:52:40'),
(194, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'sofp', '/matamis-new/sofp/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 07:54:19'),
(195, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'sofp', '/matamis-new/sofp/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 08:11:56'),
(196, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'linked_bs', '/matamis-new/linked-bs/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 08:11:59'),
(197, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'sofp', '/matamis-new/sofp/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 08:12:01'),
(198, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'soci', '/matamis-new/soci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 08:12:02'),
(199, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'somfp', '/matamis-new/somfp/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 08:12:05'),
(200, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'glance', '/matamis-new/glance/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 08:15:02'),
(201, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'glance', '/matamis-new/glance/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5&year_from=2026&year_to=2026&month_from=0&month_to=0&applied=1&tab=comprehensive-income\"}', '2026-07-09 08:16:00'),
(202, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'glance', '/matamis-new/glance/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"tab=comprehensive-income&applied=1&company_id=5&branch_id=0&year_from=2026&year_to=2026&month_from=7&month_to=7\"}', '2026-07-09 08:16:09'),
(203, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'glance', '/matamis-new/glance/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5&year_from=2026&year_to=2026&month_from=7&month_to=7&applied=1&tab=financial-position\"}', '2026-07-09 08:16:16'),
(204, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'linked_bs', '/matamis-new/linked-bs/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 08:16:17'),
(205, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'somfp', '/matamis-new/somfp/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 08:16:19'),
(206, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'somci', '/matamis-new/somci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 08:16:20'),
(207, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'glance', '/matamis-new/glance/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 08:16:25'),
(208, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'linked_bs', '/matamis-new/linked-bs/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 08:17:28'),
(209, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'linked_bs', '/matamis-new/linked-bs/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 08:18:45'),
(210, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'glance', '/matamis-new/glance/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 08:18:47'),
(211, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'glance', '/matamis-new/glance/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 08:31:50'),
(212, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'glance', '/matamis-new/glance/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5&year_from=2026&year_to=2026&month_from=0&month_to=0&applied=1&tab=linked-is\"}', '2026-07-09 08:31:52'),
(213, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'glance', '/matamis-new/glance/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5&year_from=2026&year_to=2026&month_from=0&month_to=0&applied=1&tab=linked-is\"}', '2026-07-09 08:33:29'),
(214, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'glance', '/matamis-new/glance/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"tab=linked-is&applied=1&company_id=5&branch_id=0&month_from=2&year_from=2026&month_to=4&year_to=2026\"}', '2026-07-09 08:33:37'),
(215, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'glance', '/matamis-new/glance/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"tab=linked-is&applied=1&company_id=5&branch_id=0&month_from=2&year_from=2026&month_to=7&year_to=2026\"}', '2026-07-09 08:33:41'),
(216, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'linked_bs', '/matamis-new/linked-bs/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 08:36:47'),
(217, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'linked_is', '/matamis-new/linked-is/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 08:36:55'),
(218, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'linked_bs', '/matamis-new/linked-bs/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 08:37:01'),
(219, 1, 'System Administrator', 'admin@gmail.com', 'view', 'form_page', 'linked_bs', '/matamis-new/linked-bs/entry.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"company_id=5\"}', '2026-07-09 08:37:02'),
(220, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'somfp', '/matamis-new/somfp/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 08:37:11'),
(221, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'somci', '/matamis-new/somci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 08:37:14'),
(222, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'sofp', '/matamis-new/sofp/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 08:37:22'),
(223, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'soci', '/matamis-new/soci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 08:37:23'),
(224, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'somfp', '/matamis-new/somfp/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 08:37:24'),
(225, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'somci', '/matamis-new/somci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 08:37:25'),
(226, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'somci', '/matamis-new/somci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 08:39:28'),
(227, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'somci', '/matamis-new/somci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 08:39:32'),
(228, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'somci', '/matamis-new/somci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 09:37:49'),
(229, 1, 'System Administrator', 'admin@gmail.com', 'view', 'list_page', 'somci', '/matamis-new/somci/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 09:38:03'),
(230, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'settings_roles', '/matamis-new/settings/roles.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 09:38:08'),
(231, 1, 'System Administrator', 'admin@gmail.com', 'view', 'form_page', 'settings_roles', '/matamis-new/settings/role_edit.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"id=1\"}', '2026-07-09 09:38:11'),
(232, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'settings_roles', '/matamis-new/settings/roles.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 09:38:25'),
(233, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'settings_roles', '/matamis-new/settings/roles.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 10:08:13'),
(234, 1, 'System Administrator', 'admin@gmail.com', 'view', 'form_page', 'settings_roles', '/matamis-new/settings/role_edit.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"id=1\"}', '2026-07-09 10:08:19'),
(235, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'settings_roles', '/matamis-new/settings/roles.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 10:08:25'),
(236, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'settings_logs', '/matamis-new/settings/logs.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 10:08:42'),
(237, 1, 'System Administrator', 'admin@gmail.com', 'access_denied', 'deny', 'settings_logs', '/matamis-new/settings/logs.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'Permission denied for requested resource.', '{\"required_permission\":\"settings_logs\",\"required_mode\":\"read\"}', '2026-07-09 10:08:42'),
(238, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', NULL, '/matamis-new/forbidden.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 10:08:42'),
(239, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', NULL, '/matamis-new/forbidden.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 10:16:24'),
(240, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'settings_roles', '/matamis-new/settings/roles.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 10:16:26'),
(241, 1, 'System Administrator', 'admin@gmail.com', 'view', 'form_page', 'settings_roles', '/matamis-new/settings/role_edit.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"id=1\"}', '2026-07-09 10:16:33'),
(242, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'settings_roles', '/matamis-new/settings/roles.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 10:16:38'),
(243, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'settings_roles', '/matamis-new/settings/roles.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 10:41:17'),
(244, 1, 'System Administrator', 'admin@gmail.com', 'view', 'view_page', 'settings_logs', '/matamis-new/settings/logs.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 OPR/132.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-09 10:41:20');

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `is_head_office` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `company_id`, `name`, `location`, `is_head_office`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'dadasd', 'adads', 1, '2026-07-02 07:11:05', '2026-07-05 06:29:27', '2026-07-05 06:29:27'),
(2, 2, 'sfsf', 'sfsf', 1, '2026-07-02 07:11:23', '2026-07-05 06:29:25', '2026-07-05 06:29:25'),
(3, 3, 'cc', 'czzczc', 1, '2026-07-02 07:13:52', '2026-07-05 06:29:29', '2026-07-05 06:29:29'),
(4, 4, 'adad', 'adad', 1, '2026-07-02 07:16:23', '2026-07-05 06:29:23', '2026-07-05 06:29:23'),
(5, 5, 'DIFC', 'UAE', 1, '2026-07-05 06:44:16', '2026-07-05 06:44:16', NULL),
(6, 5, 'Al Barsha', 'UAE', 0, '2026-07-05 06:44:16', '2026-07-05 06:44:16', NULL),
(7, 6, 'Head Office', 'UAE', 1, '2026-07-05 06:45:27', '2026-07-05 06:45:27', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `trade_license` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `logo_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `user_id`, `name`, `trade_license`, `address`, `logo_path`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'test', 'adad', 'adadad', NULL, '2026-07-02 07:11:05', '2026-07-05 06:29:27', '2026-07-05 06:29:27'),
(2, 1, 'fsf', 'sfsf', 'sfs', NULL, '2026-07-02 07:11:23', '2026-07-05 06:29:25', '2026-07-05 06:29:25'),
(3, 1, 'vvvvvv', 'vvvvv', 'vvvvv', '/uploads/company-logos/logo_cfc6b201300f918a.png', '2026-07-02 07:13:52', '2026-07-05 06:29:29', '2026-07-05 06:29:29'),
(4, 1, 'dada', 'adad', 'adad', '/uploads/company-logos/logo_56562191858de3c9.png', '2026-07-02 07:16:23', '2026-07-05 06:29:23', '2026-07-05 06:29:23'),
(5, 1, 'Afrodiva Exotic Hair Boutique Ltd.', '12345', 'UAE', '/uploads/company-logos/logo_fe26867bd1e80d57.png', '2026-07-05 06:44:16', '2026-07-05 06:44:16', NULL),
(6, 1, 'Test Company', '00000', 'UAE', NULL, '2026-07-05 06:45:27', '2026-07-05 06:45:27', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `company_observations`
--

CREATE TABLE `company_observations` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `head` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `risk` text DEFAULT NULL,
  `recommendations` text DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `company_observations`
--

INSERT INTO `company_observations` (`id`, `company_id`, `head`, `details`, `risk`, `recommendations`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, 'sfs', 'sfsf', 'sfsf', NULL, 'sfsfd', '2026-07-03 04:27:52', '2026-07-05 06:29:25', '2026-07-05 06:29:25'),
(2, 4, 'sadasd', 'adad', 'adads', 'adsads', 'In Progress', '2026-07-03 09:03:03', '2026-07-05 06:29:23', '2026-07-05 06:29:23');

-- --------------------------------------------------------

--
-- Table structure for table `linked_bs_entries`
--

CREATE TABLE `linked_bs_entries` (
  `id` int(10) UNSIGNED NOT NULL,
  `branch_id` int(10) UNSIGNED NOT NULL,
  `period_year` smallint(5) UNSIGNED NOT NULL,
  `period_month` tinyint(3) UNSIGNED NOT NULL,
  `entry_date` date DEFAULT NULL,
  `line_item_id` int(10) UNSIGNED NOT NULL,
  `amount` decimal(18,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `linked_bs_formula_terms`
--

CREATE TABLE `linked_bs_formula_terms` (
  `id` int(10) UNSIGNED NOT NULL,
  `template_id` int(10) UNSIGNED NOT NULL,
  `head_id` int(10) UNSIGNED NOT NULL,
  `operation` enum('add','subtract') NOT NULL DEFAULT 'add',
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `linked_bs_formula_terms`
--

INSERT INTO `linked_bs_formula_terms` (`id`, `template_id`, `head_id`, `operation`, `sort_order`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, 'add', 1, '2026-07-07 04:29:14', '2026-07-07 04:29:14', NULL),
(2, 1, 2, 'add', 2, '2026-07-07 04:29:14', '2026-07-07 04:29:14', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `linked_bs_heads`
--

CREATE TABLE `linked_bs_heads` (
  `id` int(10) UNSIGNED NOT NULL,
  `template_id` int(10) UNSIGNED NOT NULL,
  `label` varchar(255) NOT NULL,
  `head_number` varchar(20) DEFAULT NULL,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `linked_bs_heads`
--

INSERT INTO `linked_bs_heads` (`id`, `template_id`, `label`, `head_number`, `sort_order`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Assets', '1', 1, '2026-07-07 04:29:14', '2026-07-07 04:29:14', NULL),
(2, 1, 'Equity & Liabilities', '2', 2, '2026-07-07 04:29:14', '2026-07-07 04:29:14', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `linked_bs_line_items`
--

CREATE TABLE `linked_bs_line_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `head_id` int(10) UNSIGNED NOT NULL,
  `label` varchar(255) NOT NULL,
  `item_number` varchar(20) DEFAULT NULL,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `linked_bs_line_items`
--

INSERT INTO `linked_bs_line_items` (`id`, `head_id`, `label`, `item_number`, `sort_order`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Property and Equipment', '1.1', 1, '2026-07-07 04:29:14', '2026-07-07 04:29:14', NULL),
(2, 1, 'Trade & Other Receivables', '1.2', 2, '2026-07-07 04:29:14', '2026-07-07 04:29:14', NULL),
(3, 1, 'Inventory', '1.3', 3, '2026-07-07 04:29:14', '2026-07-07 04:29:14', NULL),
(4, 1, 'Security Deposit', '1.4', 4, '2026-07-07 04:29:14', '2026-07-07 04:29:14', NULL),
(5, 1, 'Employee Advances', '1.5', 5, '2026-07-07 04:29:14', '2026-07-07 04:29:14', NULL),
(6, 1, 'Prepayments & Prepaid Expenses', '1.6', 6, '2026-07-07 04:29:14', '2026-07-07 04:29:14', NULL),
(7, 1, 'Cash and Cash Equivalents', '1.7', 7, '2026-07-07 04:29:14', '2026-07-07 04:29:14', NULL),
(8, 1, 'Bank', '1.8', 8, '2026-07-07 04:29:14', '2026-07-07 04:29:14', NULL),
(9, 1, 'InPut VAT', '1.9', 9, '2026-07-07 04:29:14', '2026-07-07 04:29:14', NULL),
(10, 2, 'Net Income', '2.1', 1, '2026-07-07 04:29:14', '2026-07-07 04:29:14', NULL),
(11, 2, 'Drawings', '2.2', 2, '2026-07-07 04:29:14', '2026-07-07 04:29:14', NULL),
(12, 2, 'Opening Balance Equity', '2.3', 3, '2026-07-07 04:29:14', '2026-07-07 04:29:14', NULL),
(13, 2, 'Retained Earnings', '2.4', 4, '2026-07-07 04:29:14', '2026-07-07 04:29:14', NULL),
(14, 2, 'Shareholder Current Account', '2.5', 5, '2026-07-07 04:29:14', '2026-07-07 04:29:14', NULL),
(15, 2, 'Investment from Associates', '2.6', 6, '2026-07-07 04:29:14', '2026-07-07 04:29:14', NULL),
(16, 2, 'Accounts Payable (A/P)', '2.7', 7, '2026-07-07 04:29:14', '2026-07-07 04:29:14', NULL),
(17, 2, 'Machine Rent Payable', '2.8', 8, '2026-07-07 04:29:14', '2026-07-07 04:29:14', NULL),
(18, 2, 'Loan from Infusion', '2.9', 9, '2026-07-07 04:29:14', '2026-07-07 04:29:14', NULL),
(19, 2, 'Loan From Mr Saeed', '2.10', 10, '2026-07-07 04:29:14', '2026-07-07 04:29:14', NULL),
(20, 2, 'Salaries & Wages Payable', '2.11', 11, '2026-07-07 04:29:14', '2026-07-07 04:29:14', NULL),
(21, 2, 'Transguard Payable', '2.12', 12, '2026-07-07 04:29:14', '2026-07-07 04:29:14', NULL),
(22, 2, 'Other Payables', '2.13', 13, '2026-07-07 04:29:14', '2026-07-07 04:29:14', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `linked_bs_templates`
--

CREATE TABLE `linked_bs_templates` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT 'Linked BS',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `linked_bs_templates`
--

INSERT INTO `linked_bs_templates` (`id`, `company_id`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 5, 'Linked BS', '2026-07-07 04:29:14', '2026-07-07 04:29:14', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `linked_is_entries`
--

CREATE TABLE `linked_is_entries` (
  `id` int(10) UNSIGNED NOT NULL,
  `branch_id` int(10) UNSIGNED NOT NULL,
  `period_year` smallint(5) UNSIGNED NOT NULL,
  `period_month` tinyint(3) UNSIGNED NOT NULL,
  `entry_date` date DEFAULT NULL,
  `line_item_id` int(10) UNSIGNED NOT NULL,
  `amount` decimal(18,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `linked_is_entries`
--

INSERT INTO `linked_is_entries` (`id`, `branch_id`, `period_year`, `period_month`, `entry_date`, `line_item_id`, `amount`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 5, 2026, 7, '2026-07-05', 12, 25.00, '2026-07-05 16:07:20', '2026-07-05 16:20:21', NULL),
(2, 5, 2026, 7, '2026-07-05', 13, 150.00, '2026-07-05 16:07:20', '2026-07-05 16:20:21', NULL),
(3, 5, 2026, 7, '2026-07-05', 14, 10.00, '2026-07-05 16:07:20', '2026-07-05 16:20:21', NULL),
(4, 5, 2026, 7, '2026-07-05', 15, 20.00, '2026-07-05 16:07:20', '2026-07-05 16:20:21', NULL),
(5, 5, 2026, 7, '2026-07-05', 16, 30.00, '2026-07-05 16:07:20', '2026-07-05 16:20:21', NULL),
(11, 5, 2026, 7, '2026-07-05', 28, 87643.89, '2026-07-08 04:40:09', '2026-07-08 04:40:09', NULL),
(12, 5, 2026, 7, '2026-07-05', 29, 0.00, '2026-07-08 04:40:09', '2026-07-08 04:40:09', NULL),
(13, 5, 2026, 7, '2026-07-05', 30, 0.00, '2026-07-08 04:40:09', '2026-07-08 04:40:09', NULL),
(14, 5, 2026, 7, '2026-07-05', 31, 0.00, '2026-07-08 04:40:09', '2026-07-08 04:40:09', NULL),
(15, 5, 2026, 7, '2026-07-05', 32, 130481.00, '2026-07-08 04:40:09', '2026-07-08 04:40:09', NULL),
(16, 5, 2026, 7, '2026-07-05', 33, 13053.26, '2026-07-08 04:40:09', '2026-07-08 04:40:09', NULL),
(17, 5, 2026, 7, '2026-07-05', 34, -108489.00, '2026-07-08 04:40:09', '2026-07-08 04:40:09', NULL),
(18, 5, 2026, 7, '2026-07-05', 35, 0.00, '2026-07-08 04:40:09', '2026-07-08 04:40:09', NULL),
(19, 5, 2026, 7, '2026-07-05', 36, 85.67, '2026-07-08 04:40:09', '2026-07-08 04:40:09', NULL),
(20, 5, 2026, 7, '2026-07-05', 37, 2000.00, '2026-07-08 04:40:09', '2026-07-08 04:40:09', NULL),
(21, 5, 2026, 7, '2026-07-05', 38, 0.00, '2026-07-08 04:40:09', '2026-07-08 04:40:09', NULL),
(22, 5, 2026, 7, '2026-07-05', 39, 0.00, '2026-07-08 04:40:09', '2026-07-08 04:40:09', NULL),
(23, 5, 2026, 7, '2026-07-05', 40, 0.00, '2026-07-08 04:40:09', '2026-07-08 04:40:09', NULL),
(24, 5, 2026, 7, '2026-07-05', 41, 0.00, '2026-07-08 04:40:09', '2026-07-08 04:40:09', NULL),
(25, 5, 2026, 7, '2026-07-05', 42, 1193.50, '2026-07-08 04:40:09', '2026-07-08 04:40:09', NULL),
(26, 5, 2026, 7, '2026-07-05', 43, 265.51, '2026-07-08 04:40:09', '2026-07-08 04:40:09', NULL),
(27, 5, 2026, 7, '2026-07-05', 44, 4.83, '2026-07-08 04:40:09', '2026-07-08 04:40:09', NULL),
(28, 5, 2026, 7, '2026-07-05', 45, 511.33, '2026-07-08 04:40:09', '2026-07-08 04:40:09', NULL),
(29, 5, 2026, 7, '2026-07-05', 46, 1509.75, '2026-07-08 04:40:09', '2026-07-08 04:40:09', NULL),
(30, 5, 2026, 7, '2026-07-05', 47, 1200.00, '2026-07-08 04:40:09', '2026-07-08 04:40:09', NULL),
(31, 5, 2026, 7, '2026-07-05', 48, 20.88, '2026-07-08 04:40:09', '2026-07-08 04:40:09', NULL),
(32, 5, 2026, 7, '2026-07-05', 49, 12.33, '2026-07-08 04:40:09', '2026-07-08 04:40:09', NULL),
(33, 5, 2026, 7, '2026-07-05', 50, 50000.00, '2026-07-08 04:40:09', '2026-07-08 04:40:09', NULL),
(34, 5, 2026, 7, '2026-07-05', 51, 0.00, '2026-07-08 04:40:09', '2026-07-08 04:40:09', NULL),
(35, 5, 2026, 7, '2026-07-05', 52, 0.00, '2026-07-08 04:40:09', '2026-07-08 04:40:09', NULL),
(36, 5, 2026, 7, '2026-07-05', 53, 0.00, '2026-07-08 04:40:09', '2026-07-08 04:40:09', NULL),
(37, 5, 2026, 7, '2026-07-05', 54, 0.00, '2026-07-08 04:40:09', '2026-07-08 04:40:09', NULL),
(38, 5, 2026, 7, '2026-07-05', 55, 12500.00, '2026-07-08 04:40:09', '2026-07-08 04:40:09', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `linked_is_formula_terms`
--

CREATE TABLE `linked_is_formula_terms` (
  `id` int(10) UNSIGNED NOT NULL,
  `template_id` int(10) UNSIGNED NOT NULL,
  `head_id` int(10) UNSIGNED NOT NULL,
  `operation` enum('add','subtract') NOT NULL DEFAULT 'add',
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `linked_is_formula_terms`
--

INSERT INTO `linked_is_formula_terms` (`id`, `template_id`, `head_id`, `operation`, `sort_order`, `created_at`, `updated_at`, `deleted_at`) VALUES
(16, 2, 8, 'add', 1, '2026-07-05 17:53:47', '2026-07-05 17:53:55', '2026-07-05 17:53:55'),
(17, 2, 9, 'subtract', 2, '2026-07-05 17:53:47', '2026-07-05 17:54:02', '2026-07-05 17:54:02'),
(18, 2, 10, 'subtract', 3, '2026-07-05 17:53:47', '2026-07-05 17:53:59', '2026-07-05 17:53:59'),
(19, 2, 11, 'subtract', 4, '2026-07-05 17:53:47', '2026-07-05 17:54:04', '2026-07-05 17:54:04'),
(20, 2, 12, 'add', 5, '2026-07-05 17:53:47', '2026-07-05 17:54:07', '2026-07-05 17:54:07'),
(25, 1, 13, 'add', 1, '2026-07-08 04:37:32', '2026-07-08 04:37:32', NULL),
(26, 1, 14, 'subtract', 2, '2026-07-08 04:37:32', '2026-07-08 04:37:32', NULL),
(27, 1, 15, 'subtract', 3, '2026-07-08 04:37:32', '2026-07-08 04:37:32', NULL),
(28, 1, 16, 'subtract', 4, '2026-07-08 04:37:32', '2026-07-08 04:37:32', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `linked_is_heads`
--

CREATE TABLE `linked_is_heads` (
  `id` int(10) UNSIGNED NOT NULL,
  `template_id` int(10) UNSIGNED NOT NULL,
  `label` varchar(255) NOT NULL,
  `head_number` varchar(20) DEFAULT NULL,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `linked_is_heads`
--

INSERT INTO `linked_is_heads` (`id`, `template_id`, `label`, `head_number`, `sort_order`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Revenue', '1', 1, '2026-07-05 15:31:46', '2026-07-05 15:58:46', '2026-07-05 15:58:46'),
(2, 1, 'Direct Expenses', '2', 2, '2026-07-05 15:31:46', '2026-07-05 15:58:51', '2026-07-05 15:58:51'),
(3, 1, 'Operating & Administrative Expenses', '3', 3, '2026-07-05 15:31:46', '2026-07-05 15:58:54', '2026-07-05 15:58:54'),
(4, 1, 'Other Expenses', '4', 4, '2026-07-05 15:31:46', '2026-07-05 15:59:05', '2026-07-05 15:59:05'),
(5, 1, 'Other Income', '5', 5, '2026-07-05 15:31:46', '2026-07-05 15:59:08', '2026-07-05 15:59:08'),
(6, 1, 'Revenue', '1', 1, '2026-07-05 15:59:36', '2026-07-08 04:22:17', '2026-07-08 04:22:17'),
(7, 1, 'Operating and Administrative Expenses', '2', 2, '2026-07-05 16:00:45', '2026-07-08 04:22:20', '2026-07-08 04:22:20'),
(8, 2, 'Revenue', '1', 1, '2026-07-05 17:53:47', '2026-07-05 17:53:55', '2026-07-05 17:53:55'),
(9, 2, 'Direct Expenses', '2', 2, '2026-07-05 17:53:47', '2026-07-05 17:54:02', '2026-07-05 17:54:02'),
(10, 2, 'Operating & Administrative Expenses', '3', 3, '2026-07-05 17:53:47', '2026-07-05 17:53:59', '2026-07-05 17:53:59'),
(11, 2, 'Other Expenses', '4', 4, '2026-07-05 17:53:47', '2026-07-05 17:54:04', '2026-07-05 17:54:04'),
(12, 2, 'Other Income', '5', 5, '2026-07-05 17:53:47', '2026-07-05 17:54:07', '2026-07-05 17:54:07'),
(13, 1, 'Revenue', NULL, 1, '2026-07-08 04:23:39', '2026-07-08 04:23:39', NULL),
(14, 1, 'Cost of Sales', NULL, 2, '2026-07-08 04:25:48', '2026-07-08 04:25:48', NULL),
(15, 1, 'Operating and Administrative Expenses', NULL, 3, '2026-07-08 04:28:18', '2026-07-08 04:28:18', NULL),
(16, 1, 'Other Expenses', NULL, 4, '2026-07-08 04:31:17', '2026-07-08 04:31:17', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `linked_is_line_items`
--

CREATE TABLE `linked_is_line_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `head_id` int(10) UNSIGNED NOT NULL,
  `label` varchar(255) NOT NULL,
  `item_number` varchar(20) DEFAULT NULL,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `linked_is_line_items`
--

INSERT INTO `linked_is_line_items` (`id`, `head_id`, `label`, `item_number`, `sort_order`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Sales', '1.1', 1, '2026-07-05 15:31:46', '2026-07-05 15:58:46', '2026-07-05 15:58:46'),
(2, 1, 'E-Commerce\'s/Online Sale', '1.2', 2, '2026-07-05 15:31:46', '2026-07-05 15:58:46', '2026-07-05 15:58:46'),
(3, 1, 'Sales Discounts', '1.3', 3, '2026-07-05 15:31:46', '2026-07-05 15:58:46', '2026-07-05 15:58:46'),
(4, 2, 'Cost of Sales', '2.1', 1, '2026-07-05 15:31:46', '2026-07-05 15:58:51', '2026-07-05 15:58:51'),
(5, 3, 'Salary & wages', '3.1', 1, '2026-07-05 15:31:46', '2026-07-05 15:58:54', '2026-07-05 15:58:54'),
(6, 3, 'Admin. Expenses', '3.2', 2, '2026-07-05 15:31:46', '2026-07-05 15:58:54', '2026-07-05 15:58:54'),
(7, 3, 'Office Rent Expenses', '3.3', 3, '2026-07-05 15:31:46', '2026-07-05 15:58:54', '2026-07-05 15:58:54'),
(8, 3, 'Utility Expenses', '3.4', 4, '2026-07-05 15:31:46', '2026-07-05 15:58:54', '2026-07-05 15:58:54'),
(9, 4, 'Bank Charges', '4.1', 1, '2026-07-05 15:31:46', '2026-07-05 15:59:05', '2026-07-05 15:59:05'),
(10, 4, 'Depreciation', '4.2', 2, '2026-07-05 15:31:46', '2026-07-05 15:59:05', '2026-07-05 15:59:05'),
(11, 5, 'Other Income', '5.1', 1, '2026-07-05 15:31:46', '2026-07-05 15:59:08', '2026-07-05 15:59:08'),
(12, 6, 'Sales', NULL, 1, '2026-07-05 15:59:50', '2026-07-08 04:22:17', '2026-07-08 04:22:17'),
(13, 6, 'E-commerce', NULL, 2, '2026-07-05 16:00:01', '2026-07-08 04:22:17', '2026-07-08 04:22:17'),
(14, 7, 'Sales & Wages', NULL, 1, '2026-07-05 16:01:03', '2026-07-08 04:22:20', '2026-07-08 04:22:20'),
(15, 7, 'Admin Expenses', NULL, 2, '2026-07-05 16:01:20', '2026-07-08 04:22:20', '2026-07-08 04:22:20'),
(16, 7, 'Legal & Professional Consultancy Service fee', NULL, 3, '2026-07-05 16:01:26', '2026-07-08 04:22:20', '2026-07-08 04:22:20'),
(17, 8, 'Sales', '1.1', 1, '2026-07-05 17:53:47', '2026-07-05 17:53:55', '2026-07-05 17:53:55'),
(18, 8, 'E-Commerce\'s/Online Sale', '1.2', 2, '2026-07-05 17:53:47', '2026-07-05 17:53:55', '2026-07-05 17:53:55'),
(19, 8, 'Sales Discounts', '1.3', 3, '2026-07-05 17:53:47', '2026-07-05 17:53:55', '2026-07-05 17:53:55'),
(20, 9, 'Cost of Sales', '2.1', 1, '2026-07-05 17:53:47', '2026-07-05 17:54:02', '2026-07-05 17:54:02'),
(21, 10, 'Salary & wages', '3.1', 1, '2026-07-05 17:53:47', '2026-07-05 17:53:59', '2026-07-05 17:53:59'),
(22, 10, 'Admin. Expenses', '3.2', 2, '2026-07-05 17:53:47', '2026-07-05 17:53:59', '2026-07-05 17:53:59'),
(23, 10, 'Office Rent Expenses', '3.3', 3, '2026-07-05 17:53:47', '2026-07-05 17:53:59', '2026-07-05 17:53:59'),
(24, 10, 'Utility Expenses', '3.4', 4, '2026-07-05 17:53:47', '2026-07-05 17:53:59', '2026-07-05 17:53:59'),
(25, 11, 'Bank Charges', '4.1', 1, '2026-07-05 17:53:47', '2026-07-05 17:54:04', '2026-07-05 17:54:04'),
(26, 11, 'Depreciation', '4.2', 2, '2026-07-05 17:53:47', '2026-07-05 17:54:04', '2026-07-05 17:54:04'),
(27, 12, 'Other Income', '5.1', 1, '2026-07-05 17:53:47', '2026-07-05 17:54:07', '2026-07-05 17:54:07'),
(28, 13, 'Sales', NULL, 1, '2026-07-08 04:23:51', '2026-07-08 04:23:51', NULL),
(29, 13, 'E-Commerce\'s/Online Sale', NULL, 2, '2026-07-08 04:24:09', '2026-07-08 04:24:09', NULL),
(30, 13, 'Other Income', NULL, 3, '2026-07-08 04:24:21', '2026-07-08 04:24:21', NULL),
(31, 13, 'Sales Discounts', NULL, 4, '2026-07-08 04:24:41', '2026-07-08 04:24:41', NULL),
(32, 14, 'Opening Stock', NULL, 1, '2026-07-08 04:25:57', '2026-07-08 04:25:57', NULL),
(33, 14, 'Purchases', NULL, 2, '2026-07-08 04:26:04', '2026-07-08 04:26:04', NULL),
(34, 14, 'Closing Stock', NULL, 3, '2026-07-08 04:27:22', '2026-07-08 04:27:22', NULL),
(35, 15, 'Salary & wages', NULL, 1, '2026-07-08 04:28:26', '2026-07-08 04:28:26', NULL),
(36, 15, 'Admin. Expenses', NULL, 2, '2026-07-08 04:28:34', '2026-07-08 04:28:34', NULL),
(37, 15, 'Legal & Professional Consultancy Service fee', NULL, 3, '2026-07-08 04:28:42', '2026-07-08 04:28:42', NULL),
(38, 15, 'Office Misc. Expenses', NULL, 4, '2026-07-08 04:28:49', '2026-07-08 04:28:49', NULL),
(39, 15, 'Trade License & Legal Expenses', NULL, 5, '2026-07-08 04:29:07', '2026-07-08 04:29:07', NULL),
(40, 15, 'Office Rent Expenses', NULL, 6, '2026-07-08 04:29:15', '2026-07-08 04:29:15', NULL),
(41, 15, 'Utility Expenses', NULL, 7, '2026-07-08 04:29:22', '2026-07-08 04:29:22', NULL),
(42, 15, 'Printing & Stationery', NULL, 8, '2026-07-08 04:29:28', '2026-07-08 04:29:28', NULL),
(43, 15, 'Meals & Refreshments General', NULL, 9, '2026-07-08 04:29:35', '2026-07-08 04:29:35', NULL),
(44, 15, 'Staff Medical Expenses', NULL, 10, '2026-07-08 04:29:42', '2026-07-08 04:29:42', NULL),
(45, 15, 'Travel & Transportation Expenses', NULL, 11, '2026-07-08 04:29:49', '2026-07-08 04:29:49', NULL),
(46, 15, 'Employee\'s Visa Expenses.', NULL, 12, '2026-07-08 04:29:56', '2026-07-08 04:29:56', NULL),
(47, 15, 'Advertisement & Marketing Expenses', NULL, 13, '2026-07-08 04:30:03', '2026-07-08 04:30:03', NULL),
(48, 15, 'Repair Maintenance Expenses', NULL, 14, '2026-07-08 04:30:10', '2026-07-08 04:30:10', NULL),
(49, 15, 'Delivery Charges expenses', NULL, 15, '2026-07-08 04:30:17', '2026-07-08 04:30:17', NULL),
(50, 16, 'Directors’ Remuneration', NULL, 1, '2026-07-08 04:31:25', '2026-07-08 04:31:25', NULL),
(51, 16, 'Bank Charges', NULL, 2, '2026-07-08 04:31:33', '2026-07-08 04:31:33', NULL),
(52, 16, 'WPS Charges', NULL, 3, '2026-07-08 04:31:40', '2026-07-08 04:31:40', NULL),
(53, 16, 'Interest on Loans', NULL, 4, '2026-07-08 04:31:49', '2026-07-08 04:31:49', NULL),
(54, 16, 'Fines & Mukhalfa', NULL, 5, '2026-07-08 04:31:57', '2026-07-08 04:31:57', NULL),
(55, 16, 'Depreciation', NULL, 6, '2026-07-08 04:32:10', '2026-07-08 04:32:10', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `linked_is_templates`
--

CREATE TABLE `linked_is_templates` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT 'Linked IS',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `linked_is_templates`
--

INSERT INTO `linked_is_templates` (`id`, `company_id`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 5, 'Linked IS', '2026-07-05 15:31:46', '2026-07-05 15:31:46', NULL),
(2, 6, 'Linked IS', '2026-07-05 17:53:47', '2026-07-05 17:53:47', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `perm_key` varchar(150) NOT NULL,
  `label` varchar(150) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `perm_key`, `label`, `description`, `created_at`, `updated_at`) VALUES
(1, 'dashboard', 'Dashboard', 'Access dashboard', '2026-07-07 07:19:37', '2026-07-07 07:19:37'),
(2, 'companies', 'Companies', 'Manage companies and branches', '2026-07-07 07:19:37', '2026-07-07 07:19:37'),
(3, 'observations', 'Observations', 'Manage observations & recommendations', '2026-07-07 07:19:37', '2026-07-07 07:19:37'),
(4, 'linked_is', 'Linked IS', 'Manage linked income statement entries', '2026-07-07 07:19:37', '2026-07-07 07:19:37'),
(5, 'linked_bs', 'Linked BS', 'Manage linked balance sheet entries', '2026-07-07 07:19:37', '2026-07-07 07:19:37'),
(6, 'somfp', 'SOMFP', 'Manage SOMFP entries and reports', '2026-07-07 07:19:37', '2026-07-07 07:19:37'),
(7, 'somci', 'SOMCI', 'Manage SOMCI entries and reports', '2026-07-07 07:19:37', '2026-07-07 07:19:37'),
(8, 'sofp', 'SOFP', 'Access overall statement of financial position', '2026-07-07 07:19:37', '2026-07-07 07:19:37'),
(9, 'soci', 'SOCI', 'Access overall statement of comprehensive income', '2026-07-07 07:19:37', '2026-07-07 07:19:37'),
(10, 'glance', 'Glance', 'Access glance picture insights', '2026-07-07 07:19:37', '2026-07-07 07:19:37'),
(11, 'settings_users', 'Settings: Users', 'Manage users and role assignment', '2026-07-07 07:19:37', '2026-07-07 07:19:37'),
(12, 'settings_roles', 'Settings: Roles', 'Manage roles and permissions', '2026-07-07 07:19:37', '2026-07-07 07:19:37'),
(205, 'settings_logs', 'Settings: Logs', 'View activity and audit logs', '2026-07-09 10:16:25', '2026-07-09 10:16:25');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Admin', 'Full access', '2026-07-07 07:19:37', '2026-07-07 07:19:37', NULL),
(2, 'Write-Only', 'Read Only', '2026-07-07 07:25:22', '2026-07-07 07:25:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `role_id` int(10) UNSIGNED NOT NULL,
  `permission_id` int(10) UNSIGNED NOT NULL,
  `can_read` tinyint(1) NOT NULL DEFAULT 0,
  `can_write` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`role_id`, `permission_id`, `can_read`, `can_write`, `created_at`) VALUES
(1, 1, 1, 1, '2026-07-07 07:19:37'),
(1, 2, 1, 1, '2026-07-07 07:19:37'),
(1, 3, 1, 1, '2026-07-07 07:19:37'),
(1, 4, 1, 1, '2026-07-07 07:19:37'),
(1, 5, 1, 1, '2026-07-07 07:19:37'),
(1, 6, 1, 1, '2026-07-07 07:19:37'),
(1, 7, 1, 1, '2026-07-07 07:19:37'),
(1, 8, 1, 1, '2026-07-07 07:19:37'),
(1, 9, 1, 1, '2026-07-07 07:19:37'),
(1, 10, 1, 1, '2026-07-07 07:19:37'),
(1, 11, 1, 1, '2026-07-07 07:19:37'),
(1, 12, 1, 1, '2026-07-07 07:19:37'),
(1, 205, 1, 1, '2026-07-09 10:16:25'),
(2, 4, 1, 1, '2026-07-07 07:27:12'),
(2, 5, 1, 1, '2026-07-07 07:27:12');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'System Administrator', 'admin@gmail.com', '$2y$10$hDynYwcdYZGsBv7sgjp.A.FLGrWif2qWAh/SWOgkKw.b1fAfsBt6O', '2026-07-02 04:17:49', '2026-07-02 04:18:04', NULL),
(2, 'test1', 'test1@gmail.com', '$2y$10$CSZKOvwNvnqa.2P0PE1jruRcUIxycJ8PcepiofMP9xwZC3rcFskCm', '2026-07-07 07:25:46', '2026-07-07 07:25:46', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`user_id`, `role_id`, `created_at`) VALUES
(1, 1, '2026-07-07 07:23:09'),
(2, 2, '2026-07-07 07:25:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_activity_logs_user` (`user_id`),
  ADD KEY `idx_activity_logs_event` (`event_type`),
  ADD KEY `idx_activity_logs_created` (`created_at`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_id` (`company_id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `company_observations`
--
ALTER TABLE `company_observations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_company_observations_company` (`company_id`);

--
-- Indexes for table `linked_bs_entries`
--
ALTER TABLE `linked_bs_entries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_linked_bs_entry` (`branch_id`,`period_year`,`period_month`,`line_item_id`),
  ADD KEY `line_item_id` (`line_item_id`),
  ADD KEY `idx_linked_bs_period` (`period_year`,`period_month`);

--
-- Indexes for table `linked_bs_formula_terms`
--
ALTER TABLE `linked_bs_formula_terms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_linked_bs_formula_head` (`template_id`,`head_id`),
  ADD KEY `head_id` (`head_id`);

--
-- Indexes for table `linked_bs_heads`
--
ALTER TABLE `linked_bs_heads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_linked_bs_heads_template` (`template_id`,`sort_order`);

--
-- Indexes for table `linked_bs_line_items`
--
ALTER TABLE `linked_bs_line_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_linked_bs_items_head` (`head_id`,`sort_order`);

--
-- Indexes for table `linked_bs_templates`
--
ALTER TABLE `linked_bs_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_linked_bs_company` (`company_id`);

--
-- Indexes for table `linked_is_entries`
--
ALTER TABLE `linked_is_entries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_linked_is_entry` (`branch_id`,`period_year`,`period_month`,`line_item_id`),
  ADD KEY `line_item_id` (`line_item_id`),
  ADD KEY `idx_linked_is_period` (`period_year`,`period_month`);

--
-- Indexes for table `linked_is_formula_terms`
--
ALTER TABLE `linked_is_formula_terms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_formula_head` (`template_id`,`head_id`),
  ADD KEY `head_id` (`head_id`);

--
-- Indexes for table `linked_is_heads`
--
ALTER TABLE `linked_is_heads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_linked_is_heads_template` (`template_id`,`sort_order`);

--
-- Indexes for table `linked_is_line_items`
--
ALTER TABLE `linked_is_line_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_linked_is_items_head` (`head_id`,`sort_order`);

--
-- Indexes for table `linked_is_templates`
--
ALTER TABLE `linked_is_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_linked_is_company` (`company_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `perm_key` (`perm_key`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`role_id`,`permission_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=245;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `company_observations`
--
ALTER TABLE `company_observations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `linked_bs_entries`
--
ALTER TABLE `linked_bs_entries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `linked_bs_formula_terms`
--
ALTER TABLE `linked_bs_formula_terms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `linked_bs_heads`
--
ALTER TABLE `linked_bs_heads`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `linked_bs_line_items`
--
ALTER TABLE `linked_bs_line_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `linked_bs_templates`
--
ALTER TABLE `linked_bs_templates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `linked_is_entries`
--
ALTER TABLE `linked_is_entries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `linked_is_formula_terms`
--
ALTER TABLE `linked_is_formula_terms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `linked_is_heads`
--
ALTER TABLE `linked_is_heads`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `linked_is_line_items`
--
ALTER TABLE `linked_is_line_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `linked_is_templates`
--
ALTER TABLE `linked_is_templates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=206;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `branches`
--
ALTER TABLE `branches`
  ADD CONSTRAINT `branches_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `companies`
--
ALTER TABLE `companies`
  ADD CONSTRAINT `companies_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `company_observations`
--
ALTER TABLE `company_observations`
  ADD CONSTRAINT `company_observations_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `linked_bs_entries`
--
ALTER TABLE `linked_bs_entries`
  ADD CONSTRAINT `linked_bs_entries_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `linked_bs_entries_ibfk_2` FOREIGN KEY (`line_item_id`) REFERENCES `linked_bs_line_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `linked_bs_formula_terms`
--
ALTER TABLE `linked_bs_formula_terms`
  ADD CONSTRAINT `linked_bs_formula_terms_ibfk_1` FOREIGN KEY (`template_id`) REFERENCES `linked_bs_templates` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `linked_bs_formula_terms_ibfk_2` FOREIGN KEY (`head_id`) REFERENCES `linked_bs_heads` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `linked_bs_heads`
--
ALTER TABLE `linked_bs_heads`
  ADD CONSTRAINT `linked_bs_heads_ibfk_1` FOREIGN KEY (`template_id`) REFERENCES `linked_bs_templates` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `linked_bs_line_items`
--
ALTER TABLE `linked_bs_line_items`
  ADD CONSTRAINT `linked_bs_line_items_ibfk_1` FOREIGN KEY (`head_id`) REFERENCES `linked_bs_heads` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `linked_bs_templates`
--
ALTER TABLE `linked_bs_templates`
  ADD CONSTRAINT `linked_bs_templates_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `linked_is_entries`
--
ALTER TABLE `linked_is_entries`
  ADD CONSTRAINT `linked_is_entries_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `linked_is_entries_ibfk_2` FOREIGN KEY (`line_item_id`) REFERENCES `linked_is_line_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `linked_is_formula_terms`
--
ALTER TABLE `linked_is_formula_terms`
  ADD CONSTRAINT `linked_is_formula_terms_ibfk_1` FOREIGN KEY (`template_id`) REFERENCES `linked_is_templates` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `linked_is_formula_terms_ibfk_2` FOREIGN KEY (`head_id`) REFERENCES `linked_is_heads` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `linked_is_heads`
--
ALTER TABLE `linked_is_heads`
  ADD CONSTRAINT `linked_is_heads_ibfk_1` FOREIGN KEY (`template_id`) REFERENCES `linked_is_templates` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `linked_is_line_items`
--
ALTER TABLE `linked_is_line_items`
  ADD CONSTRAINT `linked_is_line_items_ibfk_1` FOREIGN KEY (`head_id`) REFERENCES `linked_is_heads` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `linked_is_templates`
--
ALTER TABLE `linked_is_templates`
  ADD CONSTRAINT `linked_is_templates_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
