-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 08, 2026 at 09:06 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sis_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role` enum('admin','faculty','student') NOT NULL,
  `activity` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `reference_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `role`, `activity`, `description`, `reference_id`, `created_at`) VALUES
(1, 18, 'faculty', 'Saved Scores', 'Prelim grades were updated.', 16, '2026-07-08 04:50:11'),
(2, 18, 'faculty', 'Printed Class List', 'IT101 - Introduction to Computing', 16, '2026-07-08 05:07:25'),
(3, 18, 'faculty', 'Printed Class List', 'IT101 - Introduction to Computing', 16, '2026-07-08 05:12:16'),
(4, 18, 'faculty', 'Printed Class List', 'IT101 - Introduction to Computing', 16, '2026-07-08 05:12:22'),
(5, 18, 'faculty', 'Printed Final Grade Report', 'IT101 - Introduction to Computing', 16, '2026-07-08 05:13:16'),
(6, 18, 'faculty', 'Printed Final Grade Report', 'IT101 - Introduction to Computing', 16, '2026-07-08 05:16:35'),
(7, 18, 'faculty', 'Saved Scores', 'Prelim grades were updated.', 16, '2026-07-08 05:21:24'),
(8, 18, 'faculty', 'Printed Class List', 'IT101 - Introduction to Computing', 16, '2026-07-08 07:05:19');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `course_code` varchar(20) NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `course_code`, `course_name`, `description`, `status`) VALUES
(1, 'IT', 'Information Technology', '', 'active'),
(2, 'BSIT', 'Bachelor of Science in Information Technology', '', 'active'),
(3, 'HRS', 'Hotel and Restaurant Services', '', 'active'),
(4, 'BSHM', 'Bachelor of Science in Hospitality Management', '', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `course_sections`
--

CREATE TABLE `course_sections` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `year_level` tinyint(4) NOT NULL,
  `section_name` varchar(20) NOT NULL,
  `display_order` int(11) NOT NULL DEFAULT 1,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_sections`
--

INSERT INTO `course_sections` (`id`, `course_id`, `year_level`, `section_name`, `display_order`, `status`, `created_at`) VALUES
(1, 1, 1, 'A', 1, 'active', '2026-07-01 13:34:08'),
(3, 1, 1, 'B', 2, 'active', '2026-07-01 13:35:27');

-- --------------------------------------------------------

--
-- Table structure for table `curriculum`
--

CREATE TABLE `curriculum` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `year_level` tinyint(4) NOT NULL,
  `trimester` tinyint(4) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `curriculum`
--

INSERT INTO `curriculum` (`id`, `course_id`, `subject_id`, `year_level`, `trimester`, `is_active`, `created_at`) VALUES
(51, 1, 12, 1, 1, 1, '2026-06-15 03:39:40'),
(52, 1, 10, 1, 1, 1, '2026-06-15 03:39:40'),
(53, 1, 11, 1, 1, 1, '2026-06-15 03:39:40'),
(54, 1, 15, 1, 1, 1, '2026-06-15 03:39:40'),
(55, 1, 13, 1, 1, 1, '2026-06-15 03:39:40'),
(56, 1, 14, 1, 1, 1, '2026-06-15 03:39:40'),
(57, 1, 16, 1, 1, 1, '2026-06-15 03:39:40'),
(58, 1, 17, 1, 1, 1, '2026-06-15 03:39:40'),
(62, 2, 12, 3, 1, 1, '2026-06-18 00:46:52'),
(63, 2, 18, 3, 1, 1, '2026-06-18 00:46:52'),
(64, 2, 10, 3, 1, 1, '2026-06-18 00:46:52'),
(65, 2, 29, 1, 1, 1, '2026-06-22 02:16:22'),
(67, 1, 19, 1, 2, 1, '2026-06-30 04:55:15'),
(68, 1, 22, 1, 2, 1, '2026-06-30 04:55:15'),
(69, 1, 25, 1, 2, 1, '2026-06-30 04:55:15'),
(70, 1, 26, 1, 2, 1, '2026-06-30 04:55:15'),
(71, 1, 29, 2, 1, 1, '2026-06-30 05:02:13');

-- --------------------------------------------------------

--
-- Table structure for table `enrolled_subjects`
--

CREATE TABLE `enrolled_subjects` (
  `id` int(11) NOT NULL,
  `enrollment_id` int(11) NOT NULL,
  `curriculum_id` int(11) NOT NULL,
  `status` enum('enrolled','completed','dropped') DEFAULT 'enrolled'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `school_year` varchar(20) NOT NULL,
  `year_level` tinyint(4) NOT NULL,
  `section_id` int(11) DEFAULT NULL,
  `trimester` tinyint(4) NOT NULL,
  `status` enum('enrolled','completed','cancelled') DEFAULT 'enrolled',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`id`, `student_id`, `course_id`, `school_year`, `year_level`, `section_id`, `trimester`, `status`, `created_at`) VALUES
(16, 3, 1, '2026-2027', 1, 1, 1, 'enrolled', '2026-06-30 04:57:43'),
(19, 4, 4, '2026-2027', 3, NULL, 1, 'enrolled', '2026-07-01 16:21:28'),
(20, 5, 1, '2026-2027', 1, 3, 1, 'enrolled', '2026-07-01 16:22:41'),
(21, 4, 1, '2026-2027', 1, 1, 1, 'enrolled', '2026-07-02 06:32:10');

-- --------------------------------------------------------

--
-- Table structure for table `enrollment_subjects`
--

CREATE TABLE `enrollment_subjects` (
  `id` int(11) NOT NULL,
  `enrollment_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollment_subjects`
--

INSERT INTO `enrollment_subjects` (`id`, `enrollment_id`, `subject_id`, `created_at`) VALUES
(48, 16, 12, '2026-06-30 04:57:43'),
(49, 16, 10, '2026-06-30 04:57:43'),
(50, 16, 11, '2026-06-30 04:57:43'),
(51, 16, 15, '2026-06-30 04:57:43'),
(53, 16, 14, '2026-06-30 04:57:43'),
(54, 16, 16, '2026-06-30 04:57:43'),
(55, 16, 17, '2026-06-30 04:57:43'),
(57, 16, 13, '2026-06-30 05:35:37'),
(59, 19, 26, '2026-07-01 16:21:28'),
(60, 19, 16, '2026-07-01 16:21:28'),
(61, 20, 12, '2026-07-01 16:22:41'),
(62, 20, 10, '2026-07-01 16:22:41'),
(63, 20, 11, '2026-07-01 16:22:41'),
(64, 20, 15, '2026-07-01 16:22:41'),
(65, 20, 13, '2026-07-01 16:22:41'),
(66, 20, 14, '2026-07-01 16:22:41'),
(67, 20, 16, '2026-07-01 16:22:41'),
(68, 20, 17, '2026-07-01 16:22:41'),
(69, 21, 12, '2026-07-02 06:32:10'),
(70, 21, 10, '2026-07-02 06:32:10'),
(71, 21, 11, '2026-07-02 06:32:10'),
(72, 21, 15, '2026-07-02 06:32:10'),
(73, 21, 13, '2026-07-02 06:32:10'),
(74, 21, 14, '2026-07-02 06:32:10'),
(75, 21, 17, '2026-07-02 06:32:10');

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `employee_number` varchar(30) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `gender` enum('male','female') NOT NULL,
  `contact_number` varchar(30) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`id`, `user_id`, `employee_number`, `email`, `first_name`, `middle_name`, `last_name`, `gender`, `contact_number`, `address`, `status`, `created_at`, `updated_at`) VALUES
(4, 18, NULL, 'deguzmanmarkfrancisp@sample.sjb.edu.ph', 'Mark Francis', 'Perez', 'De Guzman', 'male', NULL, NULL, 'active', '2026-06-19 01:23:35', '2026-07-08 05:12:55'),
(5, 20, NULL, 'jamespotter@gmail.com', 'James', NULL, 'Potah', 'male', NULL, NULL, 'active', '2026-06-27 05:43:40', '2026-06-27 05:43:40');

-- --------------------------------------------------------

--
-- Table structure for table `faculty_courses`
--

CREATE TABLE `faculty_courses` (
  `id` int(11) NOT NULL,
  `faculty_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty_courses`
--

INSERT INTO `faculty_courses` (`id`, `faculty_id`, `course_id`) VALUES
(25, 4, 1),
(24, 4, 2),
(23, 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `faculty_subjects`
--

CREATE TABLE `faculty_subjects` (
  `id` int(11) NOT NULL,
  `faculty_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `year_level` tinyint(4) NOT NULL,
  `section_id` int(11) DEFAULT NULL,
  `school_year` varchar(20) NOT NULL,
  `trimester` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty_subjects`
--

INSERT INTO `faculty_subjects` (`id`, `faculty_id`, `subject_id`, `course_id`, `year_level`, `section_id`, `school_year`, `trimester`, `created_at`) VALUES
(16, 4, 13, 1, 1, 1, '2026-2027', 1, '2026-07-02 02:54:26'),
(17, 4, 14, 1, 1, 1, '2026-2027', 1, '2026-07-02 02:54:26'),
(18, 4, 14, 1, 1, 3, '2026-2027', 1, '2026-07-02 02:54:26');

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE `grades` (
  `id` int(11) NOT NULL,
  `enrollment_subject_id` int(11) NOT NULL,
  `prelim_grade` decimal(5,2) DEFAULT NULL,
  `midterm_grade` decimal(5,2) DEFAULT NULL,
  `final_grade` decimal(5,2) DEFAULT NULL,
  `overall_grade` decimal(5,2) DEFAULT NULL,
  `grading_status` enum('Pending','In Progress','Completed') NOT NULL DEFAULT 'Pending',
  `remarks` enum('Pending','Passed','Failed','Incomplete','Dropped') DEFAULT 'Pending',
  `graded_by` int(11) DEFAULT NULL,
  `graded_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grades`
--

INSERT INTO `grades` (`id`, `enrollment_subject_id`, `prelim_grade`, `midterm_grade`, `final_grade`, `overall_grade`, `grading_status`, `remarks`, `graded_by`, `graded_at`, `created_at`, `updated_at`) VALUES
(38, 48, NULL, NULL, NULL, NULL, 'Pending', 'Pending', NULL, NULL, '2026-06-30 04:57:43', '2026-06-30 04:57:43'),
(39, 49, NULL, NULL, NULL, NULL, 'Pending', 'Pending', NULL, NULL, '2026-06-30 04:57:43', '2026-06-30 04:57:43'),
(40, 50, NULL, NULL, NULL, NULL, 'Pending', 'Pending', NULL, NULL, '2026-06-30 04:57:43', '2026-06-30 04:57:43'),
(41, 51, NULL, NULL, NULL, NULL, 'Pending', 'Pending', NULL, NULL, '2026-06-30 04:57:43', '2026-06-30 04:57:43'),
(43, 53, NULL, NULL, NULL, NULL, 'Pending', 'Pending', NULL, NULL, '2026-06-30 04:57:43', '2026-06-30 04:57:43'),
(44, 54, NULL, NULL, NULL, NULL, 'Pending', 'Pending', NULL, NULL, '2026-06-30 04:57:43', '2026-06-30 04:57:43'),
(45, 55, NULL, NULL, NULL, NULL, 'Pending', 'Pending', NULL, NULL, '2026-06-30 04:57:43', '2026-06-30 04:57:43'),
(47, 57, 34.00, NULL, NULL, NULL, 'Pending', 'Pending', NULL, NULL, '2026-06-30 05:35:37', '2026-07-08 05:21:24'),
(49, 59, NULL, NULL, NULL, NULL, 'Pending', 'Pending', NULL, NULL, '2026-07-01 16:21:28', '2026-07-01 16:21:28'),
(50, 60, NULL, NULL, NULL, NULL, 'Pending', 'Pending', NULL, NULL, '2026-07-01 16:21:28', '2026-07-01 16:21:28'),
(51, 61, NULL, NULL, NULL, NULL, 'Pending', 'Pending', NULL, NULL, '2026-07-01 16:22:41', '2026-07-01 16:22:41'),
(52, 62, NULL, NULL, NULL, NULL, 'Pending', 'Pending', NULL, NULL, '2026-07-01 16:22:41', '2026-07-01 16:22:41'),
(53, 63, NULL, NULL, NULL, NULL, 'Pending', 'Pending', NULL, NULL, '2026-07-01 16:22:41', '2026-07-01 16:22:41'),
(54, 64, NULL, NULL, NULL, NULL, 'Pending', 'Pending', NULL, NULL, '2026-07-01 16:22:41', '2026-07-01 16:22:41'),
(55, 65, NULL, NULL, NULL, NULL, 'Pending', 'Pending', NULL, NULL, '2026-07-01 16:22:41', '2026-07-01 16:22:41'),
(56, 66, NULL, NULL, NULL, NULL, 'Pending', 'Pending', NULL, NULL, '2026-07-01 16:22:41', '2026-07-01 16:22:41'),
(57, 67, NULL, NULL, NULL, NULL, 'Pending', 'Pending', NULL, NULL, '2026-07-01 16:22:41', '2026-07-01 16:22:41'),
(58, 68, NULL, NULL, NULL, NULL, 'Pending', 'Pending', NULL, NULL, '2026-07-01 16:22:41', '2026-07-01 16:22:41'),
(59, 69, NULL, NULL, NULL, NULL, 'Pending', 'Pending', NULL, NULL, '2026-07-02 06:32:10', '2026-07-02 06:32:10'),
(60, 70, NULL, NULL, NULL, NULL, 'Pending', 'Pending', NULL, NULL, '2026-07-02 06:32:10', '2026-07-02 06:32:10'),
(61, 71, NULL, NULL, NULL, NULL, 'Pending', 'Pending', NULL, NULL, '2026-07-02 06:32:10', '2026-07-02 06:32:10'),
(62, 72, NULL, NULL, NULL, NULL, 'Pending', 'Pending', NULL, NULL, '2026-07-02 06:32:10', '2026-07-02 06:32:10'),
(63, 73, 24.67, NULL, NULL, NULL, 'Pending', 'Pending', NULL, NULL, '2026-07-02 06:32:10', '2026-07-08 04:50:11'),
(64, 74, NULL, NULL, NULL, NULL, 'Pending', 'Pending', NULL, NULL, '2026-07-02 06:32:10', '2026-07-02 06:32:10'),
(65, 75, NULL, NULL, NULL, NULL, 'Pending', 'Pending', NULL, NULL, '2026-07-02 06:32:10', '2026-07-02 06:32:10');

-- --------------------------------------------------------

--
-- Table structure for table `grading_components`
--

CREATE TABLE `grading_components` (
  `id` int(11) NOT NULL,
  `grading_scheme_id` int(11) NOT NULL,
  `component_name` varchar(100) NOT NULL,
  `component_type` enum('Quiz','Activity','Assignment','Project','Laboratory','Seatwork','Recitation','Performance Task','Exam','Others') NOT NULL,
  `max_score` decimal(8,2) NOT NULL,
  `weight` decimal(5,2) NOT NULL,
  `display_order` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grading_components`
--

INSERT INTO `grading_components` (`id`, `grading_scheme_id`, `component_name`, `component_type`, `max_score`, `weight`, `display_order`, `created_at`) VALUES
(12, 6, 'Prelim Exam', 'Exam', 50.00, 30.00, 1, '2026-07-06 06:01:35'),
(14, 6, 'Activity #1', 'Quiz', 15.00, 10.00, 2, '2026-07-08 03:22:21');

-- --------------------------------------------------------

--
-- Table structure for table `grading_schemes`
--

CREATE TABLE `grading_schemes` (
  `id` int(11) NOT NULL,
  `faculty_subject_id` int(11) NOT NULL,
  `period` enum('Prelim','Midterm','Finals') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grading_schemes`
--

INSERT INTO `grading_schemes` (`id`, `faculty_subject_id`, `period`, `created_at`) VALUES
(5, 18, 'Prelim', '2026-07-02 04:11:58'),
(6, 16, 'Prelim', '2026-07-06 06:01:35'),
(7, 16, 'Midterm', '2026-07-06 06:44:33');

-- --------------------------------------------------------

--
-- Table structure for table `school_years`
--

CREATE TABLE `school_years` (
  `id` int(11) NOT NULL,
  `school_year` varchar(20) NOT NULL,
  `is_active` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `student_number` varchar(30) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `gender` enum('male','female') NOT NULL,
  `birth_date` date DEFAULT NULL,
  `contact_number` varchar(30) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `student_type` enum('new','old','transferee','returnee') DEFAULT 'new',
  `status` enum('active','inactive','graduated','dropped') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `user_id`, `student_number`, `email`, `course_id`, `first_name`, `middle_name`, `last_name`, `gender`, `birth_date`, `contact_number`, `address`, `photo`, `student_type`, `status`, `created_at`, `updated_at`) VALUES
(1, 12, NULL, 'markfrancis.deguzman@jetstar.com', 2, 'Mark Francis', NULL, 'De Guzman', 'male', '1997-12-10', NULL, NULL, NULL, 'new', 'inactive', '2026-06-18 00:36:35', '2026-06-23 01:35:33'),
(3, 14, '15-01086', 'deguzmanmarkfrancisp@gmail.com', 1, 'Mark Francis', NULL, 'De Guzman', 'male', '1997-12-01', '09568014944', '169 B. Monday Street, Brgy. Poblacion, Mandaluyong City', NULL, 'old', 'active', '2026-06-18 00:58:16', '2026-07-01 12:22:21'),
(4, 19, NULL, 'joeanalyn07@gmail.com', 1, 'Joeanalyn', 'Diaz', 'Grande', 'male', '1999-06-12', NULL, NULL, NULL, 'new', 'inactive', '2026-06-22 11:32:47', '2026-07-02 06:31:57'),
(5, 21, NULL, 'jamespotah@gmail.com', 1, 'James', NULL, 'Pott', 'male', '1997-12-10', NULL, NULL, NULL, 'new', 'active', '2026-07-01 16:22:26', '2026-07-01 16:22:26');

-- --------------------------------------------------------

--
-- Table structure for table `student_scores`
--

CREATE TABLE `student_scores` (
  `id` int(11) NOT NULL,
  `grading_component_id` int(11) NOT NULL,
  `enrollment_subject_id` int(11) NOT NULL,
  `score` decimal(8,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_scores`
--

INSERT INTO `student_scores` (`id`, `grading_component_id`, `enrollment_subject_id`, `score`, `created_at`, `updated_at`) VALUES
(5, 12, 57, 40.00, '2026-07-06 06:01:43', '2026-07-08 04:50:11'),
(6, 12, 73, 30.00, '2026-07-06 06:01:43', '2026-07-08 04:50:11'),
(7, 14, 57, 15.00, '2026-07-08 04:50:11', '2026-07-08 05:21:24'),
(8, 14, 73, 10.00, '2026-07-08 04:50:11', '2026-07-08 04:50:11');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `subject_code` varchar(30) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `units` int(11) NOT NULL DEFAULT 3,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `subject_code`, `subject_name`, `units`, `description`, `status`, `created_at`, `updated_at`) VALUES
(10, 'GE101', 'Understanding the Self', 3, '', 'active', '2026-06-16 01:17:29', '2026-06-16 01:17:29'),
(11, 'GE102', 'Readings in the Philippine History', 3, '', 'active', '2026-06-16 01:17:52', '2026-06-16 01:17:52'),
(12, 'FIL101', 'Sining ng Pakikipagtalastasan', 3, '', 'active', '2026-06-16 01:18:10', '2026-06-15 01:38:05'),
(13, 'IT101', 'Introduction to Computing', 3, '', 'active', '2026-06-16 01:18:22', '2026-06-16 01:18:22'),
(14, 'IT102', 'C Programming', 3, '', 'active', '2026-06-15 01:20:03', '2026-06-15 01:20:03'),
(15, 'GE103', 'Math in the Modern World', 3, '', 'active', '2026-06-15 01:20:36', '2026-06-15 01:20:36'),
(16, 'NSTP101', 'National Service Training Program I', 3, '', 'active', '2026-06-15 01:20:57', '2026-06-15 01:20:57'),
(17, 'PE101', 'Gymnastics', 2, '', 'active', '2026-06-15 01:23:50', '2026-06-15 01:25:11'),
(18, 'FIL102', 'Panitikang Filipino', 3, '', 'active', '2026-06-15 01:24:12', '2026-06-15 03:46:07'),
(19, 'IT103', 'Computer Programming II', 3, '', 'active', '2026-06-15 01:24:36', '2026-06-15 01:24:36'),
(20, 'GE104', 'The Contemporary World', 3, '', 'active', '2026-06-15 01:25:02', '2026-06-15 01:25:02'),
(21, 'GE105', 'Purposive Communication', 3, '', 'active', '2026-06-15 01:28:07', '2026-06-15 01:28:07'),
(22, 'IT104', 'Data Structures and Algorithms', 3, '', 'active', '2026-06-15 01:28:28', '2026-06-15 01:28:28'),
(25, 'IT105', 'Information Management', 3, '', 'active', '2026-06-15 01:29:37', '2026-06-15 01:29:37'),
(26, 'NSTP102', 'National Service Training Program II', 3, '', 'active', '2026-06-15 01:30:00', '2026-06-15 01:30:00'),
(27, 'PE102', 'Rhythmics', 2, '', 'active', '2026-06-15 01:30:26', '2026-06-15 01:30:26'),
(28, 'ITSM201', 'IT Management', 3, '', 'active', '2026-06-18 00:37:21', '2026-06-18 00:37:21'),
(29, 'CAP101', 'Capstone Project 1', 3, '', 'active', '2026-06-22 02:06:00', '2026-06-22 02:06:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','faculty','student') NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `first_name`, `middle_name`, `last_name`, `password`, `role`, `status`, `photo`, `created_at`) VALUES
(1, 'admin@sjb.edu', 'Admin', '', 'SJB', '$2y$10$THfKxU2bxLOb8rZKr.JHzeNNvmwLqP6cs1nDEldCar7kc6ogBkzzS', 'admin', 'active', 'user_1.png', '2026-06-08 11:42:06'),
(12, 'markfrancis.deguzman@jetstar.com', NULL, NULL, NULL, '$2y$10$aGqu9WLJZ2IdZdw0KTx2f.bGpEXn.IBmHGnR0/KlDIPm7sXoIMzl2', 'student', 'inactive', NULL, '2026-06-18 00:36:35'),
(14, 'deguzmanmarkfrancisp@gmail.com', NULL, NULL, NULL, '$2y$10$9KYzKZqh831a.aX2qMdatuY8qHU3sUwc7tDJsqRF.Qy4xDp81rwgm', 'student', 'active', 'user_14.jpg', '2026-06-18 00:58:16'),
(18, 'deguzmanmarkfrancisp@sample.sjb.edu.ph', 'Mark Francis', 'Perez', 'De Guzman', '$2y$10$wN6Jb.mZqDDpTWYaTCm8SezCLUk3NyQZ/gxA1UDZCj5eOkJNfqCwK', 'faculty', 'active', 'user_18.png', '2026-06-19 01:23:35'),
(19, 'joeanalyn07@gmail.com', NULL, NULL, NULL, '$2y$10$OkyYh/PR4Z1w0i5Dv3IHXu7IF4h6Fuzzx13Iqx8F3F2hzlk.YdyHq', 'student', 'inactive', NULL, '2026-06-22 11:32:47'),
(20, 'jamespotter@gmail.com', NULL, NULL, NULL, '$2y$10$.gfa6fgH.8FVfDb15VUaB.sxF7atQOsPh4Kb8BOy/L8OijyuNzjIS', 'faculty', 'active', NULL, '2026-06-27 05:43:40'),
(21, 'jamespotah@gmail.com', NULL, NULL, NULL, '$2y$10$mz0L726H9VwT0REbzNneo.BAjgX5qO.OxNa22JUhr5LG1xi8zzVoW', 'student', 'active', NULL, '2026-07-01 16:22:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `role` (`role`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `course_code` (`course_code`);

--
-- Indexes for table `course_sections`
--
ALTER TABLE `course_sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `curriculum`
--
ALTER TABLE `curriculum`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_curriculum` (`course_id`,`subject_id`,`year_level`,`trimester`),
  ADD KEY `fk_curriculum_subject` (`subject_id`);

--
-- Indexes for table `enrolled_subjects`
--
ALTER TABLE `enrolled_subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `enrollment_id` (`enrollment_id`),
  ADD KEY `curriculum_id` (`curriculum_id`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_enrollment_section` (`section_id`);

--
-- Indexes for table `enrollment_subjects`
--
ALTER TABLE `enrollment_subjects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_enrollment_subject` (`enrollment_id`,`subject_id`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `employee_number` (`employee_number`);

--
-- Indexes for table `faculty_courses`
--
ALTER TABLE `faculty_courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_faculty_course` (`faculty_id`,`course_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `faculty_subjects`
--
ALTER TABLE `faculty_subjects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_faculty_subject` (`faculty_id`,`subject_id`,`course_id`,`year_level`,`section_id`,`school_year`,`trimester`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `fk_faculty_section` (`section_id`),
  ADD KEY `idx_faculty_id` (`faculty_id`);

--
-- Indexes for table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `enrollment_subject_id` (`enrollment_subject_id`),
  ADD KEY `fk_grade_user` (`graded_by`);

--
-- Indexes for table `grading_components`
--
ALTER TABLE `grading_components`
  ADD PRIMARY KEY (`id`),
  ADD KEY `grading_scheme_id` (`grading_scheme_id`);

--
-- Indexes for table `grading_schemes`
--
ALTER TABLE `grading_schemes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_scheme` (`faculty_subject_id`,`period`);

--
-- Indexes for table `school_years`
--
ALTER TABLE `school_years`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `school_year` (`school_year`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `student_number` (`student_number`),
  ADD KEY `fk_students_course` (`course_id`);

--
-- Indexes for table `student_scores`
--
ALTER TABLE `student_scores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_score` (`grading_component_id`,`enrollment_subject_id`),
  ADD KEY `enrollment_subject_id` (`enrollment_subject_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subject_code` (`subject_code`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `course_sections`
--
ALTER TABLE `course_sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `curriculum`
--
ALTER TABLE `curriculum`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `enrolled_subjects`
--
ALTER TABLE `enrolled_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `enrollment_subjects`
--
ALTER TABLE `enrollment_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `faculty_courses`
--
ALTER TABLE `faculty_courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `faculty_subjects`
--
ALTER TABLE `faculty_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `grading_components`
--
ALTER TABLE `grading_components`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `grading_schemes`
--
ALTER TABLE `grading_schemes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `school_years`
--
ALTER TABLE `school_years`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `student_scores`
--
ALTER TABLE `student_scores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `course_sections`
--
ALTER TABLE `course_sections`
  ADD CONSTRAINT `course_sections_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`);

--
-- Constraints for table `curriculum`
--
ALTER TABLE `curriculum`
  ADD CONSTRAINT `fk_curriculum_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_curriculum_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `enrolled_subjects`
--
ALTER TABLE `enrolled_subjects`
  ADD CONSTRAINT `enrolled_subjects_ibfk_1` FOREIGN KEY (`enrollment_id`) REFERENCES `enrollments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `enrolled_subjects_ibfk_2` FOREIGN KEY (`curriculum_id`) REFERENCES `curriculum` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `fk_enrollment_section` FOREIGN KEY (`section_id`) REFERENCES `course_sections` (`id`);

--
-- Constraints for table `faculty`
--
ALTER TABLE `faculty`
  ADD CONSTRAINT `fk_faculty_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `faculty_courses`
--
ALTER TABLE `faculty_courses`
  ADD CONSTRAINT `faculty_courses_ibfk_1` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `faculty_courses_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `faculty_subjects`
--
ALTER TABLE `faculty_subjects`
  ADD CONSTRAINT `faculty_subjects_ibfk_1` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `faculty_subjects_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_faculty_section` FOREIGN KEY (`section_id`) REFERENCES `course_sections` (`id`);

--
-- Constraints for table `grades`
--
ALTER TABLE `grades`
  ADD CONSTRAINT `fk_grade_enrollment_subject` FOREIGN KEY (`enrollment_subject_id`) REFERENCES `enrollment_subjects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_grade_user` FOREIGN KEY (`graded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `grading_components`
--
ALTER TABLE `grading_components`
  ADD CONSTRAINT `grading_components_ibfk_1` FOREIGN KEY (`grading_scheme_id`) REFERENCES `grading_schemes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `grading_schemes`
--
ALTER TABLE `grading_schemes`
  ADD CONSTRAINT `grading_schemes_ibfk_1` FOREIGN KEY (`faculty_subject_id`) REFERENCES `faculty_subjects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `fk_students_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_scores`
--
ALTER TABLE `student_scores`
  ADD CONSTRAINT `student_scores_ibfk_1` FOREIGN KEY (`grading_component_id`) REFERENCES `grading_components` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_scores_ibfk_2` FOREIGN KEY (`enrollment_subject_id`) REFERENCES `enrollment_subjects` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
