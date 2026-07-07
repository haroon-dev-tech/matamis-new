-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 07, 2026 at 10:51 AM
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
(60, 2, 'test1', 'test1@gmail.com', 'view', 'list_page', 'linked_bs', '/matamis-new/linked-bs/index.php', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'HTTP request activity', '{\"query_string\":\"\"}', '2026-07-07 09:18:38');

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
(5, 5, 2026, 7, '2026-07-05', 16, 30.00, '2026-07-05 16:07:20', '2026-07-05 16:20:21', NULL);

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
(23, 1, 6, 'add', 1, '2026-07-05 17:58:52', '2026-07-05 17:58:52', NULL),
(24, 1, 7, 'add', 2, '2026-07-05 17:58:52', '2026-07-05 17:58:52', NULL);

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
(6, 1, 'Revenue', '1', 1, '2026-07-05 15:59:36', '2026-07-05 15:59:36', NULL),
(7, 1, 'Operating and Administrative Expenses', '2', 2, '2026-07-05 16:00:45', '2026-07-05 16:00:45', NULL),
(8, 2, 'Revenue', '1', 1, '2026-07-05 17:53:47', '2026-07-05 17:53:55', '2026-07-05 17:53:55'),
(9, 2, 'Direct Expenses', '2', 2, '2026-07-05 17:53:47', '2026-07-05 17:54:02', '2026-07-05 17:54:02'),
(10, 2, 'Operating & Administrative Expenses', '3', 3, '2026-07-05 17:53:47', '2026-07-05 17:53:59', '2026-07-05 17:53:59'),
(11, 2, 'Other Expenses', '4', 4, '2026-07-05 17:53:47', '2026-07-05 17:54:04', '2026-07-05 17:54:04'),
(12, 2, 'Other Income', '5', 5, '2026-07-05 17:53:47', '2026-07-05 17:54:07', '2026-07-05 17:54:07');

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
(12, 6, 'Sales', NULL, 1, '2026-07-05 15:59:50', '2026-07-05 15:59:50', NULL),
(13, 6, 'E-commerce', NULL, 2, '2026-07-05 16:00:01', '2026-07-05 16:00:01', NULL),
(14, 7, 'Sales & Wages', NULL, 1, '2026-07-05 16:01:03', '2026-07-05 16:01:03', NULL),
(15, 7, 'Admin Expenses', NULL, 2, '2026-07-05 16:01:20', '2026-07-05 16:01:20', NULL),
(16, 7, 'Legal & Professional Consultancy Service fee', NULL, 3, '2026-07-05 16:01:26', '2026-07-05 16:01:26', NULL),
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
(27, 12, 'Other Income', '5.1', 1, '2026-07-05 17:53:47', '2026-07-05 17:54:07', '2026-07-05 17:54:07');

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
(12, 'settings_roles', 'Settings: Roles', 'Manage roles and permissions', '2026-07-07 07:19:37', '2026-07-07 07:19:37');

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
(2, 4, 1, 1, '2026-07-07 07:27:12'),
(2, 5, 1, 1, '2026-07-07 07:27:12');

-- --------------------------------------------------------

--
-- Table structure for table `somci_entries`
--

CREATE TABLE `somci_entries` (
  `id` int(10) UNSIGNED NOT NULL,
  `branch_id` int(10) UNSIGNED NOT NULL,
  `period_year` smallint(5) UNSIGNED NOT NULL,
  `period_month` tinyint(3) UNSIGNED NOT NULL,
  `line_item_key` varchar(80) NOT NULL,
  `amount` decimal(18,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `somci_entries`
--

INSERT INTO `somci_entries` (`id`, `branch_id`, `period_year`, `period_month`, `line_item_key`, `amount`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 4, 2026, 7, 'sales', 25.00, '2026-07-03 11:12:37', '2026-07-05 06:29:23', '2026-07-05 06:29:23'),
(2, 4, 2026, 7, 'e_commerce_online_sale', 65.00, '2026-07-03 11:12:37', '2026-07-05 06:29:23', '2026-07-05 06:29:23'),
(3, 4, 2026, 7, 'sales_discounts', 41.00, '2026-07-03 11:12:37', '2026-07-05 06:29:23', '2026-07-05 06:29:23'),
(4, 4, 2026, 7, 'cost_of_sales', 85.00, '2026-07-03 11:12:37', '2026-07-05 06:29:23', '2026-07-05 06:29:23'),
(5, 4, 2026, 7, 'salary_wages', 25.00, '2026-07-03 11:12:37', '2026-07-05 06:29:23', '2026-07-05 06:29:23'),
(6, 4, 2026, 7, 'admin_expenses', 87.00, '2026-07-03 11:12:37', '2026-07-05 06:29:23', '2026-07-05 06:29:23'),
(7, 4, 2026, 7, 'legal_professional_consultancy', 19.00, '2026-07-03 11:12:37', '2026-07-05 06:29:23', '2026-07-05 06:29:23'),
(8, 4, 2026, 7, 'office_misc_expenses', 25.00, '2026-07-03 11:12:37', '2026-07-05 06:29:23', '2026-07-05 06:29:23'),
(9, 4, 2026, 7, 'trade_license_legal_expenses', 8.00, '2026-07-03 11:12:37', '2026-07-05 06:29:23', '2026-07-05 06:29:23'),
(10, 4, 2026, 7, 'office_rent_expenses', 24.00, '2026-07-03 11:12:37', '2026-07-05 06:29:23', '2026-07-05 06:29:23'),
(11, 4, 2026, 7, 'utility_expenses', 74.00, '2026-07-03 11:12:37', '2026-07-05 06:29:23', '2026-07-05 06:29:23'),
(12, 4, 2026, 7, 'printing_stationery', 85.00, '2026-07-03 11:12:37', '2026-07-05 06:29:23', '2026-07-05 06:29:23'),
(13, 4, 2026, 7, 'meals_refreshments_general', 36.00, '2026-07-03 11:12:37', '2026-07-05 06:29:23', '2026-07-05 06:29:23'),
(14, 4, 2026, 7, 'staff_medical_expenses', 2.00, '2026-07-03 11:12:37', '2026-07-05 06:29:23', '2026-07-05 06:29:23'),
(15, 4, 2026, 7, 'travel_transportation_expenses', 15.00, '2026-07-03 11:12:37', '2026-07-05 06:29:23', '2026-07-05 06:29:23'),
(16, 4, 2026, 7, 'employees_visa_expenses', 87.00, '2026-07-03 11:12:37', '2026-07-05 06:29:23', '2026-07-05 06:29:23'),
(17, 4, 2026, 7, 'advertisement_marketing_expenses', 45.00, '2026-07-03 11:12:37', '2026-07-05 06:29:23', '2026-07-05 06:29:23'),
(18, 4, 2026, 7, 'repair_maintenance_expenses', 15.00, '2026-07-03 11:12:37', '2026-07-05 06:29:23', '2026-07-05 06:29:23'),
(19, 4, 2026, 7, 'delivery_charges_expenses', 26.00, '2026-07-03 11:12:37', '2026-07-05 06:29:23', '2026-07-05 06:29:23'),
(20, 4, 2026, 7, 'directors_remuneration', 58.00, '2026-07-03 11:12:37', '2026-07-05 06:29:23', '2026-07-05 06:29:23'),
(21, 4, 2026, 7, 'bank_charges', 54.00, '2026-07-03 11:12:37', '2026-07-05 06:29:23', '2026-07-05 06:29:23'),
(22, 4, 2026, 7, 'wps_charges', 22.00, '2026-07-03 11:12:37', '2026-07-05 06:29:23', '2026-07-05 06:29:23'),
(23, 4, 2026, 7, 'fines_mukhalfa', 48.00, '2026-07-03 11:12:37', '2026-07-05 06:29:23', '2026-07-05 06:29:23'),
(24, 4, 2026, 7, 'interest_on_loans', 11.00, '2026-07-03 11:12:37', '2026-07-05 06:29:23', '2026-07-05 06:29:23'),
(25, 4, 2026, 7, 'depreciation', 12.00, '2026-07-03 11:12:37', '2026-07-05 06:29:23', '2026-07-05 06:29:23'),
(26, 4, 2026, 7, 'other_income', 13.00, '2026-07-03 11:12:37', '2026-07-05 06:29:23', '2026-07-05 06:29:23'),
(27, 4, 2026, 7, 'corporate_tax', 14.00, '2026-07-03 11:12:37', '2026-07-05 06:29:23', '2026-07-05 06:29:23');

-- --------------------------------------------------------

--
-- Table structure for table `somfp_entries`
--

CREATE TABLE `somfp_entries` (
  `id` int(10) UNSIGNED NOT NULL,
  `branch_id` int(10) UNSIGNED NOT NULL,
  `period_year` smallint(5) UNSIGNED NOT NULL,
  `period_month` tinyint(3) UNSIGNED NOT NULL,
  `line_item_key` varchar(80) NOT NULL,
  `amount` decimal(18,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `somfp_entries`
--

INSERT INTO `somfp_entries` (`id`, `branch_id`, `period_year`, `period_month`, `line_item_key`, `amount`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 2026, 7, 'property_equipment', 25.00, '2026-07-02 10:32:33', '2026-07-05 06:29:27', '2026-07-05 06:29:27'),
(2, 1, 2026, 7, 'trade_receivables', 1.00, '2026-07-02 10:32:33', '2026-07-05 06:29:27', '2026-07-05 06:29:27'),
(3, 1, 2026, 7, 'inventory', 2.00, '2026-07-02 10:32:33', '2026-07-05 06:29:27', '2026-07-05 06:29:27'),
(4, 1, 2026, 7, 'security_deposit', 3.00, '2026-07-02 10:32:33', '2026-07-05 06:29:27', '2026-07-05 06:29:27'),
(5, 1, 2026, 7, 'employee_advances', 4.00, '2026-07-02 10:32:33', '2026-07-05 06:29:27', '2026-07-05 06:29:27'),
(6, 1, 2026, 7, 'prepayments', 5.00, '2026-07-02 10:32:33', '2026-07-05 06:29:27', '2026-07-05 06:29:27'),
(7, 1, 2026, 7, 'cash_equivalents', 6.00, '2026-07-02 10:32:33', '2026-07-05 06:29:27', '2026-07-05 06:29:27'),
(8, 1, 2026, 7, 'cash_vanissa', 7.00, '2026-07-02 10:32:33', '2026-07-05 06:29:27', '2026-07-05 06:29:27'),
(9, 1, 2026, 7, 'cash_omotola', 8.00, '2026-07-02 10:32:33', '2026-07-05 06:29:27', '2026-07-05 06:29:27'),
(10, 1, 2026, 7, 'bank', 9.00, '2026-07-02 10:32:33', '2026-07-05 06:29:27', '2026-07-05 06:29:27'),
(11, 1, 2026, 7, 'input_vat', 10.00, '2026-07-02 10:32:33', '2026-07-05 06:29:27', '2026-07-05 06:29:27'),
(12, 1, 2026, 7, 'net_income', 11.00, '2026-07-02 10:32:33', '2026-07-05 06:29:27', '2026-07-05 06:29:27'),
(13, 1, 2026, 7, 'drawings', 12.00, '2026-07-02 10:32:33', '2026-07-05 06:29:27', '2026-07-05 06:29:27'),
(14, 1, 2026, 7, 'opening_balance_equity', 13.00, '2026-07-02 10:32:33', '2026-07-05 06:29:27', '2026-07-05 06:29:27'),
(15, 1, 2026, 7, 'retained_earnings', 14.00, '2026-07-02 10:32:33', '2026-07-05 06:29:27', '2026-07-05 06:29:27'),
(16, 1, 2026, 7, 'shareholder_current_account', 15.00, '2026-07-02 10:32:33', '2026-07-05 06:29:27', '2026-07-05 06:29:27'),
(17, 1, 2026, 7, 'investment_associates', 16.00, '2026-07-02 10:32:33', '2026-07-05 06:29:27', '2026-07-05 06:29:27'),
(18, 1, 2026, 7, 'accounts_payable', 17.00, '2026-07-02 10:32:33', '2026-07-05 06:29:27', '2026-07-05 06:29:27'),
(19, 1, 2026, 7, 'machine_rent_payable', 18.00, '2026-07-02 10:32:33', '2026-07-05 06:29:27', '2026-07-05 06:29:27'),
(20, 1, 2026, 7, 'loan_infusion', 19.00, '2026-07-02 10:32:33', '2026-07-05 06:29:27', '2026-07-05 06:29:27'),
(21, 1, 2026, 7, 'loan_mr_saeed', 20.00, '2026-07-02 10:32:33', '2026-07-05 06:29:27', '2026-07-05 06:29:27'),
(22, 1, 2026, 7, 'salaries_wages_payable', 21.00, '2026-07-02 10:32:34', '2026-07-05 06:29:27', '2026-07-05 06:29:27'),
(23, 1, 2026, 7, 'transguard_payable', 0.00, '2026-07-02 10:32:34', '2026-07-05 06:29:27', '2026-07-05 06:29:27'),
(24, 1, 2026, 7, 'other_payables', 0.00, '2026-07-02 10:32:34', '2026-07-05 06:29:27', '2026-07-05 06:29:27'),
(25, 5, 2026, 7, 'property_equipment', 25.00, '2026-07-05 06:50:38', '2026-07-05 06:50:38', NULL),
(26, 5, 2026, 7, 'trade_receivables', 15.00, '2026-07-05 06:50:38', '2026-07-05 06:50:38', NULL),
(27, 5, 2026, 7, 'inventory', 26.00, '2026-07-05 06:50:38', '2026-07-05 06:50:38', NULL),
(28, 5, 2026, 7, 'security_deposit', 57.00, '2026-07-05 06:50:38', '2026-07-05 06:50:38', NULL),
(29, 5, 2026, 7, 'employee_advances', 15.00, '2026-07-05 06:50:38', '2026-07-05 06:50:38', NULL),
(30, 5, 2026, 7, 'prepayments', 36.00, '2026-07-05 06:50:38', '2026-07-05 06:50:38', NULL),
(31, 5, 2026, 7, 'cash_equivalents', 87.00, '2026-07-05 06:50:38', '2026-07-05 06:50:38', NULL),
(32, 5, 2026, 7, 'cash_vanissa', 25.00, '2026-07-05 06:50:38', '2026-07-05 06:50:38', NULL),
(33, 5, 2026, 7, 'cash_omotola', 15.00, '2026-07-05 06:50:38', '2026-07-05 06:50:38', NULL),
(34, 5, 2026, 7, 'bank', 64.00, '2026-07-05 06:50:38', '2026-07-05 06:50:38', NULL),
(35, 5, 2026, 7, 'input_vat', 25.00, '2026-07-05 06:50:38', '2026-07-05 06:50:38', NULL),
(36, 5, 2026, 7, 'net_income', 25.00, '2026-07-05 06:50:38', '2026-07-05 06:50:38', NULL),
(37, 5, 2026, 7, 'drawings', 45.00, '2026-07-05 06:50:38', '2026-07-05 06:50:38', NULL),
(38, 5, 2026, 7, 'opening_balance_equity', 48.00, '2026-07-05 06:50:38', '2026-07-05 06:50:38', NULL),
(39, 5, 2026, 7, 'retained_earnings', 15.00, '2026-07-05 06:50:38', '2026-07-05 06:50:38', NULL),
(40, 5, 2026, 7, 'shareholder_current_account', 20.00, '2026-07-05 06:50:38', '2026-07-05 06:50:38', NULL),
(41, 5, 2026, 7, 'investment_associates', 12.00, '2026-07-05 06:50:38', '2026-07-05 06:50:38', NULL),
(42, 5, 2026, 7, 'accounts_payable', 87.00, '2026-07-05 06:50:38', '2026-07-05 06:50:38', NULL),
(43, 5, 2026, 7, 'machine_rent_payable', 52.00, '2026-07-05 06:50:38', '2026-07-05 06:50:38', NULL),
(44, 5, 2026, 7, 'loan_infusion', 63.00, '2026-07-05 06:50:38', '2026-07-05 06:50:38', NULL),
(45, 5, 2026, 7, 'loan_mr_saeed', 15.00, '2026-07-05 06:50:38', '2026-07-05 06:50:38', NULL),
(46, 5, 2026, 7, 'salaries_wages_payable', 25.00, '2026-07-05 06:50:38', '2026-07-05 06:50:38', NULL),
(47, 5, 2026, 7, 'transguard_payable', 12.00, '2026-07-05 06:50:38', '2026-07-05 06:50:38', NULL),
(48, 5, 2026, 7, 'other_payables', 54.00, '2026-07-05 06:50:38', '2026-07-05 06:50:38', NULL);

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
-- Indexes for table `somci_entries`
--
ALTER TABLE `somci_entries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_somci_branch_period_item` (`branch_id`,`period_year`,`period_month`,`line_item_key`),
  ADD KEY `idx_somci_period` (`period_year`,`period_month`);

--
-- Indexes for table `somfp_entries`
--
ALTER TABLE `somfp_entries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_branch_period_item` (`branch_id`,`period_year`,`period_month`,`line_item_key`),
  ADD KEY `idx_period` (`period_year`,`period_month`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `linked_is_formula_terms`
--
ALTER TABLE `linked_is_formula_terms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `linked_is_heads`
--
ALTER TABLE `linked_is_heads`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `linked_is_line_items`
--
ALTER TABLE `linked_is_line_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `linked_is_templates`
--
ALTER TABLE `linked_is_templates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=181;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `somci_entries`
--
ALTER TABLE `somci_entries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `somfp_entries`
--
ALTER TABLE `somfp_entries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

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
-- Constraints for table `somci_entries`
--
ALTER TABLE `somci_entries`
  ADD CONSTRAINT `somci_entries_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `somfp_entries`
--
ALTER TABLE `somfp_entries`
  ADD CONSTRAINT `somfp_entries_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE;

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
