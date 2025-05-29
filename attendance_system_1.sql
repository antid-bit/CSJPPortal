-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 28, 2025 at 02:46 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `attendance_system-2`
--

-- --------------------------------------------------------

--
-- Table structure for table `addstudent`
--

CREATE TABLE `addstudent` (
  `id` int(11) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `year_level_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `addstudent`
--

INSERT INTO `addstudent` (`id`, `user_id`, `year_level_id`, `course_id`, `created_at`) VALUES
(1, 'S22-0089', 3, 1, '2025-05-22 14:30:29'),
(3, 'S22-0055', 3, 2, '2025-05-22 14:46:02'),
(5, 'S22-0078', 3, 3, '2025-05-22 15:00:46'),
(7, 'S22-0066', 3, 4, '2025-05-22 15:20:13'),
(9, 'S22-0067', 3, 5, '2025-05-22 15:46:11'),
(11, 'S22-0045', 3, 1, '2025-05-27 20:57:35'),
(12, 'S22-0099', 3, 2, '2025-05-27 22:41:46'),
(14, 'S22-0076', 3, 2, '2025-05-28 01:14:35'),
(16, 'S22-0032', 3, 2, '2025-05-28 03:17:53'),
(17, 's23-0078', 3, 2, '2025-05-28 03:19:58'),
(18, 'S23-0089', 3, 3, '2025-05-28 03:47:06'),
(19, 'S23-0077', 3, 5, '2025-05-28 05:33:16');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` varchar(20) NOT NULL,
  `admin_name` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `course_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `admin_name`, `password`, `course_id`) VALUES
('S22-0022', 'admin3', '2001-10-08', 3),
('S22-0045', 'Admin4', '2000-11-11', 4),
('S22-0055', 'admin1', '2000-10-09', 1),
('S22-0056', 'admin2', '2001-01-01', 2),
('S24-0024', 'admin5', '2000-04-04', 5);

-- --------------------------------------------------------

--
-- Table structure for table `attendance_log`
--

CREATE TABLE `attendance_log` (
  `id` int(11) NOT NULL,
  `student_id` varchar(10) NOT NULL,
  `subject` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `status` enum('Present','Late','Absent') NOT NULL,
  `course_id` int(11) NOT NULL DEFAULT 2
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `course_id` int(11) NOT NULL,
  `course_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_id`, `course_name`) VALUES
(1, 'BSCS'),
(2, 'BSAIS'),
(3, 'CHMT'),
(4, 'BSBA'),
(5, 'BSENTREP');

-- --------------------------------------------------------

--
-- Table structure for table `course_schedule`
--

CREATE TABLE `course_schedule` (
  `schedule_id` int(11) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `year_level_id` int(11) DEFAULT NULL,
  `teacher_id` varchar(100) DEFAULT NULL,
  `day_of_week` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday') NOT NULL,
  `time_slot` varchar(50) NOT NULL,
  `subject_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_schedule`
--

INSERT INTO `course_schedule` (`schedule_id`, `course_id`, `year_level_id`, `teacher_id`, `day_of_week`, `time_slot`, `subject_id`) VALUES
(0, 1, 3, 'S22-0060', 'Monday', '11:00-12:30', 1),
(0, 3, 3, 'S22-0060', 'Friday', '1:00-2:30', 2);

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `room_name` varchar(50) NOT NULL,
  `course_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_name`, `course_id`) VALUES
(21, 'CL3', 1),
(22, '203', 2),
(23, '401', 3),
(24, '203', 2),
(25, '401', 2),
(26, 'CL3', 2),
(27, '203', 2),
(28, '201', 4),
(29, 'CL3', 4),
(30, 'CL2', 5);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `subject_name` varchar(255) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `year_level_id` int(11) DEFAULT NULL,
  `teacher_id` varchar(100) DEFAULT NULL,
  `subject_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`subject_name`, `course_id`, `year_level_id`, `teacher_id`, `subject_id`) VALUES
('GEE3 Panitikan', 1, NULL, NULL, 1),
('Thesis 1', 3, NULL, NULL, 2);

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `teacher_id` varchar(50) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `birthday` varchar(20) NOT NULL,
  `course_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`teacher_id`, `lastname`, `firstname`, `email`, `phone`, `birthday`, `course_id`) VALUES
('S22-0080', 'teacher', 'teacher1', 'teacher1@gmail.com', '', '2001-08-08', 1),
('S22-0085', 'teacher', 'teacher2', 'teacher2@gmail.com', '', '2001-09-09', 2),
('S22-0090', 'teacher', 'teacher3', 'teacher3@gmail.com', '', '2001-07-07', 3),
('S22-0095', 'teacher', 'teacher4', 'teacher4@gmail.com', '', '2002-03-03', 4),
('S22-1010', 'teacher', 'teacher5', 'teacher5@gmail.com', '', '2003-05-05', 5);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` varchar(100) NOT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `year_level_id` int(11) DEFAULT NULL,
  `role` enum('admin','student','teacher') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `lastname`, `firstname`, `birthday`, `course_id`, `year_level_id`, `role`) VALUES
('S22-0060', 'Benabese', 'Bev', '2001-10-09', 1, 3, 'student'),
('S22-0059', NULL, NULL, '2001-07-22', 3, 3, 'student'),
('S22-0065', NULL, NULL, '2002-04-04', 2, 3, 'student'),
('S22-0070', NULL, NULL, '2002-01-01', 4, 3, 'student'),
('S22-0075', NULL, NULL, '2002-02-02', 5, 3, 'student'),
('S22-0089', 'Dom', 'Nico', '2002-10-09', 1, 0, 'student');

-- --------------------------------------------------------

--
-- Table structure for table `year_levels`
--

CREATE TABLE `year_levels` (
  `year_level_id` int(11) NOT NULL,
  `year_level` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addstudent`
--
ALTER TABLE `addstudent`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `attendance_log`
--
ALTER TABLE `attendance_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_id`);

--
-- Indexes for table `course_schedule`
--
ALTER TABLE `course_schedule`
  ADD PRIMARY KEY (`subject_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`subject_id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`teacher_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `year_levels`
--
ALTER TABLE `year_levels`
  ADD PRIMARY KEY (`year_level_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addstudent`
--
ALTER TABLE `addstudent`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `attendance_log`
--
ALTER TABLE `attendance_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `course_schedule`
--
ALTER TABLE `course_schedule`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `subject_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `admins_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
