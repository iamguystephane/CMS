-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 26, 2025 at 06:38 PM
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
-- Database: `cms`
--

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` int(11) NOT NULL,
  `studentID` int(11) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `email` varchar(500) NOT NULL,
  `names` varchar(500) NOT NULL,
  `department` varchar(500) NOT NULL,
  `priority` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`id`, `studentID`, `description`, `created_at`, `email`, `names`, `department`, `priority`) VALUES
(12, 7, 'I don\'t like This lecturer', '2025-03-26 17:30:42', 'forlemustephane@gmail.com', 'Forlemu', 'Maritime', 'Low');

-- --------------------------------------------------------

--
-- Table structure for table `complaint_messages`
--

CREATE TABLE `complaint_messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `admin_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaint_messages`
--

INSERT INTO `complaint_messages` (`id`, `sender_id`, `receiver_id`, `message`, `created_at`, `admin_id`) VALUES
(29, 5, 7, 'What don\'t you like about them?', '2025-03-26 17:31:17', 5),
(30, 7, 5, 'Well, they don\'t lecture well!', '2025-03-26 17:31:33', 5),
(31, 5, 7, 'Ohh, I see! Sorry.', '2025-03-26 17:31:47', 5),
(32, 10, 7, 'Why not?', '2025-03-26 17:32:27', 10),
(33, 7, 10, 'No particular reason!', '2025-03-26 17:32:42', 10);

-- --------------------------------------------------------

--
-- Table structure for table `complaint_replies`
--

CREATE TABLE `complaint_replies` (
  `id` int(11) NOT NULL,
  `complaint_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `reply` text NOT NULL,
  `replied_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `studentID` int(11) NOT NULL,
  `sender` varchar(10) DEFAULT NULL,
  `message` text NOT NULL,
  `adminID` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `studentID`, `sender`, `message`, `adminID`, `created_at`) VALUES
(20, 7, '', 'sdasdasdas', 7, '2025-03-26 09:19:46'),
(21, 7, '', 'sdasdasdas', 7, '2025-03-26 09:19:47');

-- --------------------------------------------------------

--
-- Table structure for table `sign up`
--

CREATE TABLE `sign up` (
  `ID` int(3) NOT NULL,
  `Names` varchar(50) DEFAULT NULL,
  `Department` varchar(20) DEFAULT NULL,
  `Level` varchar(20) DEFAULT NULL,
  `Password` varchar(200) DEFAULT NULL,
  `role` varchar(20) DEFAULT NULL,
  `Email` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sign up`
--

INSERT INTO `sign up` (`ID`, `Names`, `Department`, `Level`, `Password`, `role`, `Email`) VALUES
(5, 'Mc Brain', 'Engineering and Tech', 'Level 100', '$2y$10$.Zzdyy5gEBg94uhUFNm59.bvh8t1Yey7LgGHemUfAYpSWlVwOdYS6', 'Admin', 'mcbrain@gmail.com'),
(7, 'Forlemu Stephane', 'Maritime', 'Level 300', '$2y$10$myEGps7MRo4UswzmAT4g7.MwWhRrzCcaIlt/2WJHHtYt3l1A9SddO', 'Student', 'forlemustephane@gmail.com'),
(9, 'Guy Stephane', 'Engineering', 'Level 300', '$2y$10$VQjXZbKUfVjxxD68Hk.UN.am3VrvmvOaAc0KtskzPNIjD2q6KO5ra', 'Student', 'gstephane138@gmail.com'),
(10, 'John Doe', 'Engineering and Tech', 'Level 200', '$2y$10$YPeJTyW6WdymysdNB8GRCORkqc0v6U1MQog/hjd2nQ8N4I.P9/lTG', 'Admin', 'johndoe@gmail.com'),
(11, 'Mary Watson', 'Engineering and Tech', 'Level 200', '$2y$10$8nslSIwHxZcB6PQOZe7HDu6xJ0TvmMILdewuhyVPiSmUlB69NQyNq', 'Student', 'marywatson@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `studentID` (`studentID`);

--
-- Indexes for table `complaint_messages`
--
ALTER TABLE `complaint_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`),
  ADD KEY `fk_admin` (`admin_id`);

--
-- Indexes for table `complaint_replies`
--
ALTER TABLE `complaint_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `complaint_id` (`complaint_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `studentID` (`studentID`),
  ADD KEY `adminID` (`adminID`);

--
-- Indexes for table `sign up`
--
ALTER TABLE `sign up`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `complaint_messages`
--
ALTER TABLE `complaint_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `complaint_replies`
--
ALTER TABLE `complaint_replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `sign up`
--
ALTER TABLE `sign up`
  MODIFY `ID` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `complaints_ibfk_1` FOREIGN KEY (`studentID`) REFERENCES `sign up` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `complaint_messages`
--
ALTER TABLE `complaint_messages`
  ADD CONSTRAINT `complaint_messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `sign up` (`ID`),
  ADD CONSTRAINT `complaint_messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `sign up` (`ID`),
  ADD CONSTRAINT `fk_admin` FOREIGN KEY (`admin_id`) REFERENCES `sign up` (`ID`);

--
-- Constraints for table `complaint_replies`
--
ALTER TABLE `complaint_replies`
  ADD CONSTRAINT `complaint_replies_ibfk_1` FOREIGN KEY (`complaint_id`) REFERENCES `complaints` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `complaint_replies_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `sign up` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`studentID`) REFERENCES `sign up` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`adminID`) REFERENCES `sign up` (`ID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
