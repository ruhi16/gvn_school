-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 14, 2026 at 12:10 PM
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
-- Database: `gvn_school`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `exam_grades`
--

CREATE TABLE `exam_grades` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `from_percentage` int(11) DEFAULT NULL,
  `to_percentage` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `exam_grades`
--

INSERT INTO `exam_grades` (`id`, `name`, `description`, `from_percentage`, `to_percentage`, `order_id`, `school_id`, `session_id`, `is_active`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 'A+', 'Excellent', 90, 100, 1, 1, 1, 1, 'This is a sample exam grade.', '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(2, 'A', 'Very Good', 80, 89, 2, 1, 1, 1, 'This is a sample exam grade.', '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(3, 'B+', 'Good', 70, 79, 3, 1, 1, 1, 'This is a sample exam grade.', '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(4, 'B', 'Above Average', 60, 69, 4, 1, 1, 1, 'This is a sample exam grade.', '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(5, 'C+', 'Average', 45, 59, 5, 1, 1, 1, 'This is a sample exam grade.', '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(6, 'C', 'Below Average', 25, 44, 6, 1, 1, 1, 'This is a sample exam grade.', '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(7, 'D', 'Poor', 0, 24, 7, 1, 1, 1, 'This is a sample exam grade.', '2026-09-14 01:30:53', '2026-09-14 01:30:53');

-- --------------------------------------------------------

--
-- Table structure for table `exam_marks_entries`
--

CREATE TABLE `exam_marks_entries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `shreny_id` int(11) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL,
  `exam_name_id` int(11) DEFAULT NULL,
  `exam_type_id` int(11) DEFAULT NULL,
  `exam_part_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `student_cr_id` int(11) DEFAULT NULL,
  `obtained_marks` int(11) DEFAULT NULL,
  `is_finalized` tinyint(1) NOT NULL DEFAULT 0,
  `is_issued` tinyint(1) NOT NULL DEFAULT 0,
  `order_id` int(11) DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exam_modes`
--

CREATE TABLE `exam_modes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `exam_modes`
--

INSERT INTO `exam_modes` (`id`, `name`, `description`, `order_id`, `school_id`, `session_id`, `is_active`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 'Written', 'Written Exam', 1, 1, 1, 1, 'This is a sample exam mode.', '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(2, 'Oral', 'Oral Exam', 2, 1, 1, 1, 'This is a sample exam mode.', '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(3, 'Practical', 'Practical Exam', 3, 1, 1, 1, 'This is a sample exam mode.', '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(4, 'Project', 'Project Exam', 4, 1, 1, 1, 'This is a sample exam mode.', '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(5, 'Assignment', 'Assignment Exam', 5, 1, 1, 1, 'This is a sample exam mode.', '2026-09-14 01:30:53', '2026-09-14 01:30:53');

-- --------------------------------------------------------

--
-- Table structure for table `exam_names`
--

CREATE TABLE `exam_names` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `exam_names`
--

INSERT INTO `exam_names` (`id`, `name`, `description`, `order_id`, `school_id`, `session_id`, `is_active`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 'First Term Exam', 'T1', 1, 1, 1, 1, 'This is a sample exam name.', '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(2, 'Half Yearly Exam', 'HY', 2, 1, 1, 1, 'This is a sample exam name.', '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(3, 'Second Term Exam', 'T2', 3, 1, 1, 1, 'This is a sample exam name.', '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(4, 'Annual Exam', 'AE', 4, 1, 1, 1, 'This is a sample exam name.', '2026-09-14 01:30:53', '2026-09-14 01:30:53');

-- --------------------------------------------------------

--
-- Table structure for table `exam_parts`
--

CREATE TABLE `exam_parts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `exam_parts`
--

INSERT INTO `exam_parts` (`id`, `name`, `description`, `order_id`, `school_id`, `session_id`, `is_active`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 'P1', 'Part 1 Exam', 1, 1, 1, 1, 'This is a sample exam part.', '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(2, 'P2', 'Part 2 Exam', 2, 1, 1, 1, 'This is a sample exam part.', '2026-09-14 01:30:53', '2026-09-14 01:30:53');

-- --------------------------------------------------------

--
-- Table structure for table `exam_promotion_rules`
--

CREATE TABLE `exam_promotion_rules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exam_result_promotions`
--

CREATE TABLE `exam_result_promotions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exam_script_distributions`
--

CREATE TABLE `exam_script_distributions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `exam_name_id` int(11) DEFAULT NULL,
  `exam_type_id` int(11) DEFAULT NULL,
  `exam_part_id` int(11) DEFAULT NULL,
  `shreny_id` int(11) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `allotted_date` date DEFAULT NULL,
  `submited_date` date DEFAULT NULL,
  `is_finalized` tinyint(1) DEFAULT NULL,
  `is_issued` tinyint(1) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exam_shreny_part_fm_pms`
--

CREATE TABLE `exam_shreny_part_fm_pms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `shreny_id` int(11) DEFAULT NULL,
  `exam_name_id` int(11) DEFAULT NULL,
  `exam_type_id` int(11) DEFAULT NULL,
  `exam_part_id` int(11) DEFAULT NULL,
  `exam_mode_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `full_marks` int(11) DEFAULT NULL,
  `pass_marks` int(11) DEFAULT NULL,
  `time_alloted` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `exam_shreny_part_fm_pms`
--

INSERT INTO `exam_shreny_part_fm_pms` (`id`, `name`, `description`, `shreny_id`, `exam_name_id`, `exam_type_id`, `exam_part_id`, `exam_mode_id`, `subject_id`, `full_marks`, `pass_marks`, `time_alloted`, `order_id`, `school_id`, `session_id`, `is_active`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 'Exam type configuration', NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-09-14 03:37:53', '2026-09-14 03:37:53'),
(2, 'Exam part configuration', NULL, NULL, 1, 1, 1, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-09-14 03:37:55', '2026-09-14 03:37:57'),
(3, 'Exam type configuration', NULL, NULL, 1, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-09-14 03:38:01', '2026-09-14 03:38:01'),
(4, 'Exam part configuration', NULL, NULL, 1, 2, 1, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-09-14 03:38:03', '2026-09-14 03:38:06'),
(5, 'Exam part configuration', NULL, NULL, 1, 2, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-09-14 03:38:04', '2026-09-14 03:38:08'),
(8, 'Shreny subject configuration', NULL, 1, 1, 2, 2, 1, 1, 30, 50, 10, NULL, NULL, NULL, 1, NULL, '2026-09-14 03:38:26', '2026-09-14 03:52:48'),
(9, 'Shreny subject configuration', NULL, 1, 1, 1, 1, 2, 1, 40, 10, 120, NULL, NULL, NULL, 1, NULL, '2026-09-14 03:38:30', '2026-09-14 03:52:39'),
(10, 'Shreny subject configuration', NULL, 1, 1, 2, 1, 2, 1, 40, 12, 123, NULL, NULL, NULL, 1, NULL, '2026-09-14 03:38:31', '2026-09-14 03:52:44'),
(11, 'Shreny subject configuration', NULL, 1, 1, 1, 1, 2, 2, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-09-14 03:38:56', '2026-09-14 03:38:56'),
(12, 'Shreny subject configuration', NULL, 1, 1, 2, 1, 2, 2, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-09-14 03:38:56', '2026-09-14 03:38:56'),
(13, 'Shreny subject configuration', NULL, 1, 1, 2, 2, 1, 2, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-09-14 03:38:56', '2026-09-14 03:38:56'),
(14, 'Shreny subject configuration', NULL, 1, 1, 1, 1, 2, 3, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-09-14 03:38:57', '2026-09-14 03:38:57'),
(15, 'Shreny subject configuration', NULL, 1, 1, 2, 1, 2, 3, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-09-14 03:38:57', '2026-09-14 03:38:57'),
(16, 'Shreny subject configuration', NULL, 1, 1, 2, 2, 1, 3, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-09-14 03:38:57', '2026-09-14 03:38:57'),
(17, 'Shreny subject configuration', NULL, 2, 1, 1, 1, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-09-14 03:38:58', '2026-09-14 03:38:58'),
(18, 'Shreny subject configuration', NULL, 2, 1, 2, 1, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-09-14 03:38:58', '2026-09-14 03:38:58'),
(19, 'Shreny subject configuration', NULL, 2, 1, 2, 2, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-09-14 03:38:58', '2026-09-14 03:38:58'),
(20, 'Shreny subject configuration', NULL, 2, 1, 1, 1, 2, 2, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-09-14 03:38:58', '2026-09-14 03:38:58'),
(21, 'Shreny subject configuration', NULL, 2, 1, 2, 1, 2, 2, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-09-14 03:38:58', '2026-09-14 03:38:58'),
(22, 'Shreny subject configuration', NULL, 2, 1, 2, 2, 1, 2, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-09-14 03:38:58', '2026-09-14 03:38:58'),
(23, 'Shreny subject configuration', NULL, 2, 1, 1, 1, 2, 3, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-09-14 03:39:00', '2026-09-14 03:39:00'),
(24, 'Shreny subject configuration', NULL, 2, 1, 2, 1, 2, 3, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-09-14 03:39:00', '2026-09-14 03:39:00'),
(25, 'Shreny subject configuration', NULL, 2, 1, 2, 2, 1, 3, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-09-14 03:39:00', '2026-09-14 03:39:00');

-- --------------------------------------------------------

--
-- Table structure for table `exam_shreny_subject_grades`
--

CREATE TABLE `exam_shreny_subject_grades` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `exam_name_id` int(11) DEFAULT NULL,
  `shreny_id` int(11) DEFAULT NULL,
  `subject_type_id` int(11) DEFAULT NULL,
  `exam_grade_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exam_types`
--

CREATE TABLE `exam_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `exam_types`
--

INSERT INTO `exam_types` (`id`, `name`, `description`, `order_id`, `school_id`, `session_id`, `is_active`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 'Formative Exam', 'FE', 1, 1, 1, 1, 'This is a sample exam type.', '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(2, 'Summative Exam', 'SE', 2, 1, 1, 1, 'This is a sample exam type.', '2026-09-14 01:30:53', '2026-09-14 01:30:53');

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
-- Table structure for table `framework_sessions`
--

CREATE TABLE `framework_sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
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
(4, '2026_09_08_015412_create_schools_table', 1),
(5, '2026_09_08_015427_create_sessions_table', 1),
(6, '2026_09_08_015502_create_shrenies_table', 1),
(7, '2026_09_08_015508_create_sections_table', 1),
(8, '2026_09_08_015524_create_subjects_table', 1),
(9, '2026_09_08_015546_create_shreny_sections_table', 1),
(10, '2026_09_08_015554_create_shreny_subjects_table', 1),
(11, '2026_09_08_015610_create_student_dbs_table', 1),
(12, '2026_09_08_015614_create_student_crs_table', 1),
(13, '2026_09_08_025458_create_teachers_table', 1),
(14, '2026_09_08_025537_create_teacher_subjects_table', 1),
(15, '2026_09_08_025602_create_shreny_teachers_table', 1),
(16, '2026_09_10_085647_create_exam_names_table', 1),
(17, '2026_09_10_090046_create_exam_types_table', 1),
(18, '2026_09_10_090105_create_exam_parts_table', 1),
(19, '2026_09_10_090111_create_exam_modes_table', 1),
(20, '2026_09_10_090212_create_exam_grades_table', 1),
(21, '2026_09_10_090319_create_exam_promotion_rules_table', 1),
(22, '2026_09_10_090725_create_exam_shreny_subject_grades_table', 1),
(23, '2026_09_10_091144_create_exam_shreny_part_fm_pms_table', 1),
(24, '2026_09_10_091218_create_exam_marks_entries_table', 1),
(25, '2026_09_10_091230_create_exam_result_promotions_table', 1),
(26, '2026_09_12_000000_add_unique_roll_number_to_student_crs_table', 1),
(27, '2026_09_13_074210_create_exam_script_distributions_table', 1);

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
-- Table structure for table `schools`
--

CREATE TABLE `schools` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `short_name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `dise_code` varchar(255) DEFAULT NULL,
  `udise_code` varchar(255) DEFAULT NULL,
  `school_type` varchar(255) DEFAULT NULL,
  `vill` varchar(255) DEFAULT NULL,
  `post_office` varchar(255) DEFAULT NULL,
  `police_station` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `block` varchar(255) DEFAULT NULL,
  `pincode` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `schools`
--

INSERT INTO `schools` (`id`, `name`, `short_name`, `description`, `dise_code`, `udise_code`, `school_type`, `vill`, `post_office`, `police_station`, `district`, `block`, `pincode`, `is_active`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 'Green Vally Nursery School', NULL, NULL, '123456', '654321', 'Public', 'Manikchak', 'Manikchak', 'Lalgola', 'Murshidabad', 'Lalgola', '742148', 1, 'This is a sample school.', '2026-09-14 01:30:53', '2026-09-14 01:30:53');

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `name`, `description`, `order_id`, `school_id`, `session_id`, `is_active`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 'A', 'This is the first section.', 1, 1, 1, 1, 'This is a sample section.', '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(2, 'B', 'This is the second section.', 2, 1, 1, 1, 'This is a sample section.', '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(3, 'C', 'This is the third section.', 3, 1, 1, 1, 'This is a sample section.', '2026-09-14 01:30:53', '2026-09-14 01:30:53');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `name`, `description`, `start_date`, `end_date`, `order_id`, `status`, `school_id`, `is_active`, `remarks`, `created_at`, `updated_at`) VALUES
(1, '2026', NULL, '2023-01-01', '2024-12-31', 1, 'active', 1, 1, 'This is the academic session for the year 2023-2024.', '2026-09-14 01:30:53', '2026-09-14 01:30:53');

-- --------------------------------------------------------

--
-- Table structure for table `shrenies`
--

CREATE TABLE `shrenies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shrenies`
--

INSERT INTO `shrenies` (`id`, `name`, `description`, `order_id`, `school_id`, `session_id`, `is_active`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 'Baby Land', 'This is the first shreny.', 1, 1, 1, 1, 'This is a sample shreny.', '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(2, 'LKG', 'This is the first shreny.', 2, 1, 1, 1, 'This is a sample shreny.', '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(3, 'UKG', 'This is the first shreny.', 3, 1, 1, 1, 'This is a sample shreny.', '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(4, 'Class 1', 'This is the first shreny.', 4, 1, 1, 1, 'This is a sample shreny.', '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(5, 'Class 2', 'This is the first shreny.', 5, 1, 1, 1, 'This is a sample shreny.', '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(6, 'Class 3', 'This is the first shreny.', 6, 1, 1, 1, 'This is a sample shreny.', '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(7, 'Class 4', 'This is the first shreny.', 7, 1, 1, 1, 'This is a sample shreny.', '2026-09-14 01:30:53', '2026-09-14 01:30:53');

-- --------------------------------------------------------

--
-- Table structure for table `shreny_sections`
--

CREATE TABLE `shreny_sections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `shreny_id` int(11) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shreny_sections`
--

INSERT INTO `shreny_sections` (`id`, `shreny_id`, `section_id`, `order_id`, `school_id`, `session_id`, `is_active`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, 1, 1, 'This is a sample shreny-section association.', '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(2, 2, 1, 2, 1, 1, 1, 'This is a sample shreny-section association.', '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(3, 3, 1, 3, 1, 1, 1, 'This is a sample shreny-section association.', '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(4, 4, 1, 4, 1, 1, 1, 'This is a sample shreny-section association.', '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(5, 5, 1, 5, 1, 1, 1, 'This is a sample shreny-section association.', '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(6, 6, 1, 6, 1, 1, 1, 'This is a sample shreny-section association.', '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(7, 7, 1, 7, 1, 1, 1, 'This is a sample shreny-section association.', '2026-09-14 01:30:53', '2026-09-14 01:30:53');

-- --------------------------------------------------------

--
-- Table structure for table `shreny_subjects`
--

CREATE TABLE `shreny_subjects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `shreny_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shreny_subjects`
--

INSERT INTO `shreny_subjects` (`id`, `shreny_id`, `subject_id`, `order_id`, `school_id`, `session_id`, `is_active`, `remarks`, `created_at`, `updated_at`) VALUES
(6, 2, 1, NULL, NULL, NULL, 1, NULL, '2026-09-14 01:42:04', '2026-09-14 01:42:04'),
(7, 2, 2, NULL, NULL, NULL, 1, NULL, '2026-09-14 01:42:05', '2026-09-14 01:42:05'),
(8, 2, 3, NULL, NULL, NULL, 1, NULL, '2026-09-14 01:42:06', '2026-09-14 01:42:06'),
(9, 2, 10, NULL, NULL, NULL, 1, NULL, '2026-09-14 01:42:23', '2026-09-14 01:42:23'),
(10, 2, 11, NULL, NULL, NULL, 1, NULL, '2026-09-14 01:42:24', '2026-09-14 01:42:24'),
(11, 3, 1, NULL, NULL, NULL, 1, NULL, '2026-09-14 01:42:26', '2026-09-14 01:42:26'),
(12, 3, 2, NULL, NULL, NULL, 1, NULL, '2026-09-14 01:42:26', '2026-09-14 01:42:26'),
(13, 3, 3, NULL, NULL, NULL, 1, NULL, '2026-09-14 01:42:27', '2026-09-14 01:42:27'),
(14, 3, 10, NULL, NULL, NULL, 1, NULL, '2026-09-14 01:42:28', '2026-09-14 01:42:28'),
(15, 3, 11, NULL, NULL, NULL, 1, NULL, '2026-09-14 01:42:29', '2026-09-14 01:42:29'),
(16, 3, 4, NULL, NULL, NULL, 1, NULL, '2026-09-14 01:42:30', '2026-09-14 01:42:30'),
(17, 4, 1, NULL, NULL, NULL, 1, NULL, '2026-09-14 01:42:37', '2026-09-14 01:42:37'),
(18, 4, 2, NULL, NULL, NULL, 1, NULL, '2026-09-14 01:42:37', '2026-09-14 01:42:37'),
(19, 4, 3, NULL, NULL, NULL, 1, NULL, '2026-09-14 01:42:38', '2026-09-14 01:42:38'),
(20, 4, 4, NULL, NULL, NULL, 1, NULL, '2026-09-14 01:42:39', '2026-09-14 01:42:39'),
(21, 4, 10, NULL, NULL, NULL, 1, NULL, '2026-09-14 01:42:41', '2026-09-14 01:42:41'),
(22, 4, 11, NULL, NULL, NULL, 1, NULL, '2026-09-14 01:42:41', '2026-09-14 01:42:41'),
(23, 5, 1, NULL, NULL, NULL, 1, NULL, '2026-09-14 01:42:54', '2026-09-14 01:42:54'),
(24, 5, 2, NULL, NULL, NULL, 1, NULL, '2026-09-14 01:42:55', '2026-09-14 01:42:55'),
(25, 5, 3, NULL, NULL, NULL, 1, NULL, '2026-09-14 01:42:56', '2026-09-14 01:42:56'),
(26, 5, 4, NULL, NULL, NULL, 1, NULL, '2026-09-14 01:42:57', '2026-09-14 01:42:57'),
(27, 5, 10, NULL, NULL, NULL, 1, NULL, '2026-09-14 01:42:57', '2026-09-14 01:42:57'),
(28, 5, 11, NULL, NULL, NULL, 1, NULL, '2026-09-14 01:42:58', '2026-09-14 01:42:58'),
(29, 5, 5, NULL, NULL, NULL, 1, NULL, '2026-09-14 01:42:59', '2026-09-14 01:42:59'),
(30, 6, 1, NULL, NULL, NULL, 1, NULL, '2026-09-14 01:43:04', '2026-09-14 01:43:04'),
(31, 6, 2, NULL, NULL, NULL, 1, NULL, '2026-09-14 01:43:04', '2026-09-14 01:43:04'),
(32, 6, 3, NULL, NULL, NULL, 1, NULL, '2026-09-14 01:43:05', '2026-09-14 01:43:05'),
(33, 6, 4, NULL, NULL, NULL, 1, NULL, '2026-09-14 01:43:06', '2026-09-14 01:43:06'),
(35, 6, 10, NULL, NULL, NULL, 1, NULL, '2026-09-14 01:43:08', '2026-09-14 01:43:08'),
(36, 6, 11, NULL, NULL, NULL, 1, NULL, '2026-09-14 01:43:09', '2026-09-14 01:43:09'),
(37, 6, 6, NULL, NULL, NULL, 1, NULL, '2026-09-14 01:43:10', '2026-09-14 01:43:10'),
(38, 6, 9, NULL, NULL, NULL, 1, NULL, '2026-09-14 01:43:15', '2026-09-14 01:43:15'),
(39, 7, 1, NULL, NULL, NULL, 1, NULL, '2026-09-14 01:43:18', '2026-09-14 01:43:18'),
(40, 7, 2, NULL, NULL, NULL, 1, NULL, '2026-09-14 01:43:26', '2026-09-14 01:43:26'),
(41, 7, 3, NULL, NULL, NULL, 1, NULL, '2026-09-14 01:43:26', '2026-09-14 01:43:26'),
(42, 7, 4, NULL, NULL, NULL, 1, NULL, '2026-09-14 01:43:28', '2026-09-14 01:43:28'),
(43, 5, 9, NULL, NULL, NULL, 1, NULL, '2026-09-14 01:43:30', '2026-09-14 01:43:30'),
(45, 7, 6, NULL, NULL, NULL, 1, NULL, '2026-09-14 01:43:35', '2026-09-14 01:43:35'),
(46, 7, 9, NULL, NULL, NULL, 1, NULL, '2026-09-14 01:43:42', '2026-09-14 01:43:42'),
(47, 7, 10, NULL, NULL, NULL, 1, NULL, '2026-09-14 01:43:43', '2026-09-14 01:43:43'),
(48, 7, 11, NULL, NULL, NULL, 1, NULL, '2026-09-14 01:43:44', '2026-09-14 01:43:44'),
(49, 1, 1, NULL, NULL, NULL, 1, NULL, '2026-09-14 01:44:07', '2026-09-14 01:44:07'),
(50, 1, 2, NULL, NULL, NULL, 1, NULL, '2026-09-14 01:44:08', '2026-09-14 01:44:08'),
(51, 1, 3, NULL, NULL, NULL, 1, NULL, '2026-09-14 01:44:09', '2026-09-14 01:44:09');

-- --------------------------------------------------------

--
-- Table structure for table `shreny_teachers`
--

CREATE TABLE `shreny_teachers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `shreny_id` int(11) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `teacher_type` enum('class_teacher','subject_teacher','other') DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_crs`
--

CREATE TABLE `student_crs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `studentdb_id` int(11) DEFAULT NULL,
  `curr_shreny_id` int(11) DEFAULT NULL,
  `curr_section_id` int(11) DEFAULT NULL,
  `curr_roll_no` int(11) DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `is_promoted` tinyint(1) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_crs`
--

INSERT INTO `student_crs` (`id`, `studentdb_id`, `curr_shreny_id`, `curr_section_id`, `curr_roll_no`, `school_id`, `session_id`, `order_id`, `is_promoted`, `is_active`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 1, 1, 1, 1, 3, 1, 1, NULL, '2026-09-14 04:39:46', '2026-09-14 04:39:46'),
(2, 1, 1, 1, 2, 1, 1, 1, 1, 1, NULL, '2026-09-14 04:39:46', '2026-09-14 04:39:46'),
(3, 2, 1, 1, 3, 1, 1, 2, 1, 1, NULL, '2026-09-14 04:39:46', '2026-09-14 04:39:46');

-- --------------------------------------------------------

--
-- Table structure for table `student_dbs`
--

CREATE TABLE `student_dbs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `dp_img_ref` varchar(255) DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `fname` varchar(255) DEFAULT NULL,
  `mname` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `dob_cert_img_ref` varchar(255) DEFAULT NULL,
  `aadhaar_id` varchar(255) DEFAULT NULL,
  `aadhaar_img_ref` varchar(255) DEFAULT NULL,
  `pen_id` varchar(255) DEFAULT NULL,
  `apper_id` varchar(255) DEFAULT NULL,
  `village` varchar(255) DEFAULT NULL,
  `post_office` varchar(255) DEFAULT NULL,
  `police_station` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `block` varchar(255) DEFAULT NULL,
  `pincode` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT 'West Bengal',
  `nationality` varchar(255) DEFAULT 'Indian',
  `mobile_1` varchar(255) DEFAULT NULL,
  `mobile_2` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `adm_shreny_id` int(11) DEFAULT NULL,
  `adm_section_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_dbs`
--

INSERT INTO `student_dbs` (`id`, `name`, `dp_img_ref`, `gender`, `fname`, `mname`, `dob`, `dob_cert_img_ref`, `aadhaar_id`, `aadhaar_img_ref`, `pen_id`, `apper_id`, `village`, `post_office`, `police_station`, `district`, `block`, `pincode`, `state`, `nationality`, `mobile_1`, `mobile_2`, `email`, `adm_shreny_id`, `adm_section_id`, `order_id`, `school_id`, `session_id`, `is_active`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 'Reshma Khatun', 'student-dbs/1/dp/1.jpg', 'Female', 'Father Name 1', 'Mother Name 1', '2013-07-31', 'student-dbs/1/dob/1.pdf', '821127076382', 'student-dbs/1/aadhaar/1.pdf', 'PEN116135', 'APP448073', 'Azimganj', 'PO-7dgmM', 'PS-MWN94', 'Bardhaman', 'Lalgola', '742140', 'West Bengal', 'Indian', '987617481', '876512010', 'student1@example.com', 1, 1, 1, 1, 1, 1, NULL, '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(2, 'Touhida Yesmin', 'student-dbs/1/dp/2.jpg', 'Female', 'Father Name 2', 'Mother Name 2', '2015-06-24', 'student-dbs/1/dob/2.pdf', '407771941847', 'student-dbs/1/aadhaar/2.pdf', 'PEN685732', 'APP495279', 'Raghunathganj', 'PO-QZ7iA', 'PS-cbJSQ', 'Hooghly', 'Baharampur', '742104', 'West Bengal', 'Indian', '987633865', '876556234', 'student2@example.com', 1, 1, 2, 1, 1, 1, NULL, '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(3, 'Ayan Sk', 'student-dbs/1/dp/3.jpg', 'Male', 'Father Name 3', 'Mother Name 3', '2012-09-14', 'student-dbs/1/dob/3.pdf', '820465299564', 'student-dbs/1/aadhaar/3.pdf', 'PEN600494', 'APP521789', 'Azimganj', 'PO-uKQJV', 'PS-TvunB', 'Nadia', 'Baharampur', '742143', 'West Bengal', 'Indian', '987662500', '876543346', 'student3@example.com', 1, 1, 3, 1, 1, 1, NULL, '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(4, 'Rabi Ghosh', 'student-dbs/1/dp/4.jpg', 'Male', 'Father Name 4', 'Mother Name 4', '2016-10-18', 'student-dbs/1/dob/4.pdf', '332814063165', 'student-dbs/1/aadhaar/4.pdf', 'PEN565899', 'APP850472', 'Jiaganj', 'PO-ARxAa', 'PS-3Iqlg', 'Nadia', 'Bhagwangola', '742144', 'West Bengal', 'Indian', '987689107', '876525965', 'student4@example.com', 2, 1, 4, 1, 1, 1, NULL, '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(5, 'Tomal Kundu', 'student-dbs/1/dp/5.jpg', 'Other', 'Father Name 5', 'Mother Name 5', '2015-11-16', 'student-dbs/1/dob/5.pdf', '693921323359', 'student-dbs/1/aadhaar/5.pdf', 'PEN799159', 'APP979209', 'Murshidabad', 'PO-kstCm', 'PS-5aNLw', 'Hooghly', 'Bhagwangola', '742157', 'West Bengal', 'Indian', '987693803', '876526988', 'student5@example.com', 2, 1, 5, 1, 1, 1, NULL, '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(6, 'Aritro Das', 'student-dbs/1/dp/6.jpg', 'Other', 'Father Name 6', 'Mother Name 6', '2019-01-04', 'student-dbs/1/dob/6.pdf', '468992439984', 'student-dbs/1/aadhaar/6.pdf', 'PEN673032', 'APP343884', 'Jiaganj', 'PO-byALr', 'PS-WGf0O', 'Nadia', 'Murshidabad-Jiaganj', '742199', 'West Bengal', 'Indian', '987639631', '876561695', 'student6@example.com', 2, 1, 6, 1, 1, 1, NULL, '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(7, 'Babu Dey', 'student-dbs/1/dp/7.jpg', 'Other', 'Father Name 7', 'Mother Name 7', '2016-08-09', 'student-dbs/1/dob/7.pdf', '764428164639', 'student-dbs/1/aadhaar/7.pdf', 'PEN459301', 'APP213009', 'Murshidabad', 'PO-LdxyP', 'PS-cihqI', 'Hooghly', 'Baharampur', '742138', 'West Bengal', 'Indian', '987654974', '876563875', 'student7@example.com', 3, 1, 7, 1, 1, 1, NULL, '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(8, 'Bimol Pal', 'student-dbs/1/dp/8.jpg', 'Male', 'Father Name 8', 'Mother Name 8', '2015-05-01', 'student-dbs/1/dob/8.pdf', '372024122535', 'student-dbs/1/aadhaar/8.pdf', 'PEN771092', 'APP547425', 'Lalgola', 'PO-AKuki', 'PS-k7TIj', 'Nadia', 'Murshidabad-Jiaganj', '742149', 'West Bengal', 'Indian', '987638741', '876524581', 'student8@example.com', 3, 1, 8, 1, 1, 1, NULL, '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(9, 'Sabina', 'student-dbs/1/dp/9.jpg', 'Other', 'Father Name 9', 'Mother Name 9', '2014-09-04', 'student-dbs/1/dob/9.pdf', '862897187282', 'student-dbs/1/aadhaar/9.pdf', 'PEN312014', 'APP408712', 'Lalgola', 'PO-iLqp5', 'PS-yhuR9', 'Murshidabad', 'Baharampur', '742165', 'West Bengal', 'Indian', '987644034', '876550131', 'student9@example.com', 3, 1, 9, 1, 1, 1, NULL, '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(10, 'Raktim', 'student-dbs/1/dp/10.jpg', 'Other', 'Father Name 10', 'Mother Name 10', '2009-09-29', 'student-dbs/1/dob/10.pdf', '965781496937', 'student-dbs/1/aadhaar/10.pdf', 'PEN819060', 'APP610741', 'Jiaganj', 'PO-eGAIH', 'PS-3tyNU', 'Bardhaman', 'Lalgola', '742172', 'West Bengal', 'Indian', '987655818', '876527818', 'student10@example.com', 4, 1, 10, 1, 1, 1, NULL, '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(11, 'Ratan Das', 'student-dbs/1/dp/11.jpg', 'Other', 'Father Name 11', 'Mother Name 11', '2020-08-24', 'student-dbs/1/dob/11.pdf', '578930664958', 'student-dbs/1/aadhaar/11.pdf', 'PEN971540', 'APP305140', 'Raghunathganj', 'PO-c5V4j', 'PS-RfMfJ', 'Nadia', 'Lalgola', '742193', 'West Bengal', 'Indian', '987614074', '876540594', 'student11@example.com', 5, 1, 11, 1, 1, 1, 'Needs review', '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(12, 'Babu Karmakar', 'student-dbs/1/dp/12.jpg', 'Male', 'Father Name 12', 'Mother Name 12', '2019-01-13', 'student-dbs/1/dob/12.pdf', '257375114483', 'student-dbs/1/aadhaar/12.pdf', 'PEN832805', 'APP915258', 'Lalgola', 'PO-EYf9s', 'PS-ircC9', 'Hooghly', 'Bhagwangola', '742195', 'West Bengal', 'Indian', '987668355', '876593655', 'student12@example.com', 4, 1, 12, 1, 1, 1, NULL, '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(13, 'Santanu Dey', 'student-dbs/1/dp/13.jpg', 'Other', 'Father Name 13', 'Mother Name 13', '2018-06-24', 'student-dbs/1/dob/13.pdf', '626315949056', 'student-dbs/1/aadhaar/13.pdf', 'PEN692147', 'APP597803', 'Azimganj', 'PO-MUh19', 'PS-0eo8e', 'Nadia', 'Murshidabad-Jiaganj', '742165', 'West Bengal', 'Indian', '987647883', '876532551', 'student13@example.com', 5, 1, 13, 1, 1, 1, NULL, '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(14, 'Suman Ghosh', 'student-dbs/1/dp/14.jpg', 'Female', 'Father Name 14', 'Mother Name 14', '2013-07-14', 'student-dbs/1/dob/14.pdf', '493038565407', 'student-dbs/1/aadhaar/14.pdf', 'PEN588738', 'APP702723', 'Raghunathganj', 'PO-RuYHV', 'PS-ss81K', 'Hooghly', 'Lalgola', '742185', 'West Bengal', 'Indian', '987668344', '876561787', 'student14@example.com', 4, 1, 14, 1, 1, 1, NULL, '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(15, 'Kartik Pal', 'student-dbs/1/dp/15.jpg', 'Female', 'Father Name 15', 'Mother Name 15', '2014-04-20', 'student-dbs/1/dob/15.pdf', '516842663177', 'student-dbs/1/aadhaar/15.pdf', 'PEN109873', 'APP207059', 'Jiaganj', 'PO-4TT6x', 'PS-gVXJF', 'Murshidabad', 'Murshidabad-Jiaganj', '742190', 'West Bengal', 'Indian', '987662156', '876565788', 'student15@example.com', 5, 1, 15, 1, 1, 1, NULL, '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(16, 'santu Mal', 'student-dbs/1/dp/16.jpg', 'Female', 'Father Name 16', 'Mother Name 16', '2016-12-03', 'student-dbs/1/dob/16.pdf', '737897047007', 'student-dbs/1/aadhaar/16.pdf', 'PEN454244', 'APP817392', 'Murshidabad', 'PO-aGScK', 'PS-Z6yp7', 'Nadia', 'Murshidabad-Jiaganj', '742192', 'West Bengal', 'Indian', '987646591', '876539682', 'student16@example.com', 6, 1, 16, 1, 1, 1, 'Needs review', '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(17, 'Raju Kadia', 'student-dbs/1/dp/17.jpg', 'Male', 'Father Name 17', 'Mother Name 17', '2010-10-03', 'student-dbs/1/dob/17.pdf', '769673759261', 'student-dbs/1/aadhaar/17.pdf', 'PEN160860', 'APP169139', 'Azimganj', 'PO-ruOBV', 'PS-kJ5jz', 'Nadia', 'Baharampur', '742165', 'West Bengal', 'Indian', '987610209', '876571565', 'student17@example.com', 6, 1, 17, 1, 1, 1, NULL, '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(18, 'Poran Roy', 'student-dbs/1/dp/18.jpg', 'Female', 'Father Name 18', 'Mother Name 18', '2014-01-08', 'student-dbs/1/dob/18.pdf', '864660834386', 'student-dbs/1/aadhaar/18.pdf', 'PEN156346', 'APP612700', 'Lalgola', 'PO-yGOGQ', 'PS-B39XZ', 'Hooghly', 'Lalgola', '742127', 'West Bengal', 'Indian', '987666484', '876596984', 'student18@example.com', 7, 1, 18, 1, 1, 1, NULL, '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(19, 'Rupa Khatun', 'student-dbs/1/dp/19.jpg', 'Other', 'Father Name 19', 'Mother Name 19', '2012-12-14', 'student-dbs/1/dob/19.pdf', '244553367050', 'student-dbs/1/aadhaar/19.pdf', 'PEN299020', 'APP657395', 'Azimganj', 'PO-a8EIY', 'PS-Ey7iQ', 'Murshidabad', 'Bhagwangola', '742114', 'West Bengal', 'Indian', '987695739', '876532088', 'student19@example.com', 7, 1, 19, 1, 1, 1, NULL, '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(20, 'Tithi Pal', 'student-dbs/1/dp/20.jpg', 'Male', 'Father Name 20', 'Mother Name 20', '2019-10-30', 'student-dbs/1/dob/20.pdf', '405061603735', 'student-dbs/1/aadhaar/20.pdf', 'PEN729846', 'APP220943', 'Raghunathganj', 'PO-bM4vG', 'PS-ZEMMR', 'Murshidabad', 'Bhagwangola', '742153', 'West Bengal', 'Indian', '987657039', '876554593', 'student20@example.com', 7, 1, 20, 1, 1, 1, NULL, '2026-09-14 01:30:53', '2026-09-14 01:30:53');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `short_name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `subject_type_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `name`, `short_name`, `description`, `subject_type_id`, `order_id`, `school_id`, `session_id`, `is_active`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 'Bengali', 'BENG', NULL, NULL, 1, 1, 1, 1, 'This is a sample subject.', '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(2, 'English', 'ENGL', NULL, NULL, 2, 1, 1, 1, 'This is a sample subject.', '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(3, 'Mathematics', 'MATH', NULL, NULL, 3, 1, 1, 1, 'This is a sample subject.', '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(4, 'General Knowledge', 'GK', NULL, NULL, 4, 1, 1, 1, 'This is a sample subject.', '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(5, 'Three in one', 'EVS', NULL, NULL, 5, 1, 1, 1, 'This is a sample subject.', '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(6, 'Environmental Science', 'EVS', NULL, NULL, 6, 1, 1, 1, 'This is a sample subject.', '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(7, 'History & Civics', 'Hist', NULL, NULL, 7, 1, 1, 1, 'This is a sample subject.', '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(8, 'Geography & Culture', 'Geo', NULL, NULL, 8, 1, 1, 1, 'This is a sample subject.', '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(9, 'Computer Science', 'CS', NULL, NULL, 9, 1, 1, 1, 'This is a sample subject.', '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(10, 'Physical Education', 'PE', NULL, NULL, 10, 1, 1, 1, 'This is a sample subject.', '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(11, 'Art & Work Education', 'Work', NULL, NULL, 11, 1, 1, 1, 'This is a sample subject.', '2026-09-14 01:30:53', '2026-09-14 01:30:53');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `desc` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `high_qual` enum('Secondary','Higer Secondary','Bachelor','Master','PhD') DEFAULT NULL,
  `high_qual_subject` varchar(255) DEFAULT NULL,
  `prof_qual` enum('BEd','Med','Ph Ed','Other') DEFAULT NULL,
  `prof_qual_subject` varchar(255) DEFAULT NULL,
  `vill` varchar(255) DEFAULT NULL,
  `post_office` varchar(255) DEFAULT NULL,
  `police_station` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `block` varchar(255) DEFAULT NULL,
  `pincode` varchar(255) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `name`, `desc`, `email`, `mobile`, `high_qual`, `high_qual_subject`, `prof_qual`, `prof_qual_subject`, `vill`, `post_office`, `police_station`, `district`, `block`, `pincode`, `order_id`, `school_id`, `session_id`, `is_active`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 'Beau Bruen', NULL, NULL, '+1.515.819.5308', NULL, NULL, 'BEd', 'General Methods', NULL, NULL, NULL, NULL, NULL, '82381-9409', 7, 38, 2013, 1, NULL, '2026-09-14 01:30:54', '2026-09-14 01:30:54'),
(2, 'Dr. Lily Pouros I', NULL, 'shanahan.kaley@example.net', NULL, NULL, NULL, NULL, NULL, NULL, 'Suite 463', NULL, NULL, NULL, '14459', NULL, 34, 2018, 0, NULL, '2026-09-14 01:30:54', '2026-09-14 01:30:54'),
(3, 'Dr. Aurelio Daniel', 'Nemo voluptas fugiat non consequatur sed blanditiis.', NULL, NULL, NULL, 'Mathematics', NULL, 'Physical Health', 'Kleinhaven', 'Suite 048', NULL, NULL, 'ratione', NULL, 9, 52, NULL, 1, NULL, '2026-09-14 01:30:54', '2026-09-14 01:30:54'),
(4, 'Maudie Lowe', NULL, 'istoltenberg@example.org', '512.298.4130', 'PhD', NULL, 'Other', NULL, 'Port Dell', 'Suite 163', 'Neal Landing', NULL, NULL, '25348-9227', 1, 34, NULL, 1, NULL, '2026-09-14 01:30:54', '2026-09-14 01:30:54'),
(5, 'Mrs. Dasia Schmitt', 'Voluptates placeat nihil possimus sapiente voluptate.', 'turner.kieran@example.org', '(351) 651-0998', NULL, NULL, NULL, 'General Methods', NULL, 'Suite 039', 'Mayer Trail', NULL, 'repellat', NULL, 2, 11, 1992, 1, 'In in ut fuga sed quia enim et.', '2026-09-14 01:30:54', '2026-09-14 01:30:54'),
(6, 'Myrna Durgan', 'Sed consectetur reiciendis dolore non delectus.', 'turcotte.arnulfo@example.com', '+17733651749', NULL, NULL, NULL, NULL, 'New Randall', 'Apt. 667', NULL, 'Nebraska', NULL, NULL, 2, 22, NULL, 1, 'Numquam soluta ut ut ea expedita consequatur.', '2026-09-14 01:30:54', '2026-09-14 01:30:54'),
(7, 'Sydnie Runte', NULL, 'emard.holly@example.com', '(520) 642-1977', 'PhD', 'History', 'Med', 'General Methods', NULL, 'Suite 147', 'Breana Loop', NULL, 'eos', '67771-1745', 5, 93, NULL, 1, NULL, '2026-09-14 01:30:54', '2026-09-14 01:30:54'),
(8, 'Mr. Salvatore McKenzie MD', NULL, 'owiegand@example.net', '+13159478665', 'Higer Secondary', 'English', 'Med', NULL, 'Maudport', 'Apt. 467', NULL, NULL, NULL, NULL, 9, 21, 1992, 1, 'Consectetur est voluptas aut pariatur totam odio.', '2026-09-14 01:30:54', '2026-09-14 01:30:54'),
(9, 'Mr. Kameron Wuckert', NULL, 'dorian04@example.net', '561-505-4419', 'Bachelor', NULL, 'Ph Ed', NULL, 'Consueloville', NULL, NULL, 'Kentucky', NULL, NULL, 7, 17, NULL, 1, NULL, '2026-09-14 01:30:54', '2026-09-14 01:30:54'),
(10, 'Taryn Bins', 'Aliquid praesentium ut reiciendis voluptatibus.', 'josephine.huels@example.org', '813-909-8306', NULL, NULL, NULL, 'Child Psychology', 'Ritchieport', NULL, NULL, NULL, NULL, NULL, 1, 38, NULL, 1, NULL, '2026-09-14 01:30:54', '2026-09-14 01:30:54'),
(11, 'Camilla Kilback', 'Accusamus qui voluptatem repudiandae rerum ipsa asperiores.', NULL, NULL, NULL, NULL, NULL, 'Physical Health', 'Harrisonchester', 'Apt. 663', 'Lynch Corners', NULL, 'dolorum', NULL, NULL, 30, 2009, 0, NULL, '2026-09-14 01:30:54', '2026-09-14 01:30:54'),
(12, 'Dr. Brody Welch', NULL, 'owunsch@example.com', '+1 (573) 291-8242', 'Bachelor', NULL, NULL, NULL, 'Wildermanberg', NULL, NULL, NULL, NULL, NULL, NULL, 90, NULL, 1, NULL, '2026-09-14 01:30:54', '2026-09-14 01:30:54'),
(13, 'Prof. Santina Boehm V', NULL, NULL, '(830) 963-8607', NULL, NULL, NULL, NULL, NULL, NULL, 'Shields Bridge', 'Missouri', NULL, '44660', 9, 53, NULL, 1, NULL, '2026-09-14 01:30:54', '2026-09-14 01:30:54'),
(14, 'Helen Bernier I', NULL, 'charris@example.org', NULL, 'Higer Secondary', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 71, 2012, 1, 'Voluptatem qui reiciendis accusamus ut aut omnis esse.', '2026-09-14 01:30:54', '2026-09-14 01:30:54'),
(15, 'Darby Hegmann', 'Doloremque deserunt eligendi occaecati qui.', NULL, NULL, NULL, NULL, 'Med', NULL, 'Kuhlmanshire', 'Suite 648', NULL, NULL, NULL, NULL, 9, 72, 1990, 1, 'Est fugiat natus facilis doloremque occaecati in qui laudantium.', '2026-09-14 01:30:54', '2026-09-14 01:30:54'),
(16, 'Dante Purdy II', NULL, 'fweimann@example.com', NULL, 'Secondary', NULL, 'Ph Ed', NULL, 'West Allentown', 'Suite 370', NULL, 'Oklahoma', NULL, NULL, 4, 87, NULL, 1, NULL, '2026-09-14 01:30:54', '2026-09-14 01:30:54'),
(17, 'Dr. Justen Gutkowski DVM', 'Qui culpa omnis aliquid porro.', NULL, '(941) 870-9629', NULL, NULL, 'BEd', 'Physical Health', 'Rueckertown', 'Suite 215', 'Cara Isle', NULL, NULL, '98778-4885', NULL, 38, NULL, 1, 'Modi neque asperiores aut neque laudantium.', '2026-09-14 01:30:54', '2026-09-14 01:30:54'),
(18, 'Tyshawn Cole', NULL, NULL, '843.658.5638', 'Master', NULL, NULL, 'Child Psychology', 'East Marilou', NULL, NULL, 'New Mexico', NULL, NULL, NULL, 91, 1974, 1, 'Qui eius cumque quam et asperiores omnis.', '2026-09-14 01:30:54', '2026-09-14 01:30:54'),
(19, 'Devin Bayer', 'Nihil animi nisi aut omnis voluptates explicabo.', 'kokuneva@example.org', NULL, NULL, 'Chemistry', 'Other', 'Child Psychology', NULL, 'Suite 828', NULL, 'Washington', 'nihil', '16837-5361', NULL, 26, NULL, 1, 'Doloremque nesciunt voluptatibus sed aperiam qui ducimus minima dicta.', '2026-09-14 01:30:54', '2026-09-14 01:30:54'),
(20, 'Kevon Rice', 'Deleniti beatae illo quaerat atque ut cum minus.', NULL, NULL, 'PhD', NULL, 'Med', NULL, 'Millerfort', 'Apt. 573', 'Aracely Prairie', NULL, NULL, NULL, NULL, 4, NULL, 1, 'Quo et rerum eum et est soluta itaque.', '2026-09-14 01:30:54', '2026-09-14 01:30:54');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_subjects`
--

CREATE TABLE `teacher_subjects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `subject_type` enum('main_subject','secondary_subject','additional_subject','other') DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','staff','teacher','student','visitor') NOT NULL DEFAULT 'visitor',
  `dp_url` varchar(255) DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `dp_url`, `school_id`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin User', 'admin@school.com', NULL, '$2y$12$bdjkgh8k2Skz4lzLgJn.3OZexugCh72MR.HUkLd9ryedyVcnr9y6y', 'admin', NULL, 1, 1, 'YE4VAABkwnBMymkICyCsjAdD5d9piXRqqOIWFyWfZRBSJm3Rm87ZQXtEYs95', '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(2, 'Teacher User', 'teacher@school.com', NULL, '$2y$12$jFuxPznbR8k.F2AtqdIOjuNPCzTVwk/5YNPEWC6e9esaCBA1GgXhS', 'teacher', NULL, 1, 1, NULL, '2026-09-14 01:30:53', '2026-09-14 01:30:53'),
(3, 'Student User', 'student@school.com', NULL, '$2y$12$VDdcV3nQwXQYAceV6lYQv.OgmeByQY1Z8P4F3xAR41F2Rcj2TSaeq', 'student', NULL, 1, 1, NULL, '2026-09-14 01:30:53', '2026-09-14 01:30:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `exam_grades`
--
ALTER TABLE `exam_grades`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exam_marks_entries`
--
ALTER TABLE `exam_marks_entries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exam_modes`
--
ALTER TABLE `exam_modes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exam_names`
--
ALTER TABLE `exam_names`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exam_parts`
--
ALTER TABLE `exam_parts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exam_promotion_rules`
--
ALTER TABLE `exam_promotion_rules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exam_result_promotions`
--
ALTER TABLE `exam_result_promotions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exam_script_distributions`
--
ALTER TABLE `exam_script_distributions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exam_shreny_part_fm_pms`
--
ALTER TABLE `exam_shreny_part_fm_pms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exam_shreny_subject_grades`
--
ALTER TABLE `exam_shreny_subject_grades`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exam_types`
--
ALTER TABLE `exam_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `framework_sessions`
--
ALTER TABLE `framework_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `framework_sessions_user_id_index` (`user_id`),
  ADD KEY `framework_sessions_last_activity_index` (`last_activity`);

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
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `schools`
--
ALTER TABLE `schools`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shrenies`
--
ALTER TABLE `shrenies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shreny_sections`
--
ALTER TABLE `shreny_sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shreny_subjects`
--
ALTER TABLE `shreny_subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shreny_teachers`
--
ALTER TABLE `shreny_teachers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_crs`
--
ALTER TABLE `student_crs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_crs_shreny_section_roll_unique` (`curr_shreny_id`,`curr_section_id`,`curr_roll_no`);

--
-- Indexes for table `student_dbs`
--
ALTER TABLE `student_dbs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teacher_subjects`
--
ALTER TABLE `teacher_subjects`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `exam_grades`
--
ALTER TABLE `exam_grades`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `exam_marks_entries`
--
ALTER TABLE `exam_marks_entries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exam_modes`
--
ALTER TABLE `exam_modes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `exam_names`
--
ALTER TABLE `exam_names`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `exam_parts`
--
ALTER TABLE `exam_parts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `exam_promotion_rules`
--
ALTER TABLE `exam_promotion_rules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exam_result_promotions`
--
ALTER TABLE `exam_result_promotions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exam_script_distributions`
--
ALTER TABLE `exam_script_distributions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exam_shreny_part_fm_pms`
--
ALTER TABLE `exam_shreny_part_fm_pms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `exam_shreny_subject_grades`
--
ALTER TABLE `exam_shreny_subject_grades`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exam_types`
--
ALTER TABLE `exam_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `schools`
--
ALTER TABLE `schools`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `shrenies`
--
ALTER TABLE `shrenies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `shreny_sections`
--
ALTER TABLE `shreny_sections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `shreny_subjects`
--
ALTER TABLE `shreny_subjects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `shreny_teachers`
--
ALTER TABLE `shreny_teachers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_crs`
--
ALTER TABLE `student_crs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `student_dbs`
--
ALTER TABLE `student_dbs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `teacher_subjects`
--
ALTER TABLE `teacher_subjects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
