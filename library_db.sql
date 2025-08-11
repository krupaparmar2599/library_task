-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 11, 2025 at 08:53 AM
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
-- Database: `library_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `genre` varchar(100) DEFAULT NULL,
  `published_year` int(11) DEFAULT NULL,
  `is_issued` tinyint(4) DEFAULT 0,
  `is_active` tinyint(4) DEFAULT 1,
  `is_delete` tinyint(4) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `genre`, `published_year`, `is_issued`, `is_active`, `is_delete`, `created_at`, `updated_at`) VALUES
(1, 'Powerfull mind', 'Updated Author', 'Updated Genre', 2000, 0, 1, 0, '2025-08-08 06:03:57', '2025-08-08 06:20:32'),
(2, 'Dont believe everything you think', 'auther', 'Fiction', 1990, 0, 1, 0, '2025-08-08 06:21:14', '2025-08-08 06:21:14'),
(3, 'Dont believe everything you think', 'auther', 'Fiction', 1990, 0, 1, 1, '2025-08-08 06:21:52', '2025-08-08 06:25:02');

-- --------------------------------------------------------

--
-- Table structure for table `management`
--

CREATE TABLE `management` (
  `id` int(11) NOT NULL,
  `book_id` int(11) DEFAULT NULL,
  `member_id` int(11) DEFAULT NULL,
  `issue_date` date DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `is_active` tinyint(4) DEFAULT 1,
  `is_delete` tinyint(4) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `management`
--

INSERT INTO `management` (`id`, `book_id`, `member_id`, `issue_date`, `return_date`, `due_date`, `is_active`, `is_delete`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2025-08-08', '2025-08-10', '2025-08-15', 1, 0, '2025-08-08 07:10:49', '2025-08-08 07:14:41'),
(2, 1, 2, '2025-08-01', NULL, '2025-08-07', 1, 0, '2025-08-08 07:15:49', '2025-08-08 07:19:13'),
(3, 1, 2, '2025-08-08', NULL, '2025-08-15', 1, 0, '2025-08-08 07:28:14', '2025-08-08 07:28:14'),
(4, 1, 3, '2025-08-08', NULL, '2025-08-15', 1, 0, '2025-08-08 07:32:18', '2025-08-08 07:32:18'),
(5, 1, 3, '2025-08-08', NULL, '2025-08-15', 1, 0, '2025-08-08 07:41:22', '2025-08-08 07:41:22'),
(6, 2, 1, '2025-08-08', NULL, '2025-08-15', 1, 0, '2025-08-08 08:11:13', '2025-08-08 08:11:13'),
(7, 2, 1, '2025-08-08', NULL, '2025-08-15', 1, 0, '2025-08-08 08:14:31', '2025-08-08 08:14:31'),
(8, 2, 1, '2025-08-08', NULL, '2025-08-15', 1, 0, '2025-08-08 08:15:48', '2025-08-08 08:15:48'),
(9, 1, 1, '2025-08-08', '2025-08-10', '2025-08-15', 1, 0, '2025-08-11 06:34:37', '2025-08-11 06:50:33'),
(10, 1, 1, '2025-08-08', '2025-08-10', '2025-08-15', 1, 0, '2025-08-11 06:34:58', '2025-08-11 06:50:33');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `is_active` tinyint(4) DEFAULT 1,
  `is_delete` tinyint(4) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `name`, `email`, `mobile`, `password`, `token`, `last_login`, `is_active`, `is_delete`, `created_at`, `updated_at`) VALUES
(1, 'Krupa Parmar', 'krupa.new@example.com', '9876543210', NULL, NULL, NULL, 1, 0, '2025-08-08 06:43:23', '2025-08-08 06:52:15'),
(2, 'Meera', 'mk@example.com', '1234567990', NULL, NULL, NULL, 1, 0, '2025-08-08 06:47:32', '2025-08-08 06:47:32'),
(3, 'Dhara', 'dj@example.com', '1288567990', NULL, NULL, NULL, 1, 1, '2025-08-08 06:49:00', '2025-08-08 06:53:10'),
(4, 'Dhara', 'dj@example.com', '1288567990', '$2y$10$5jw51OpJLaG228nKogqLLuRJHe19r7rhmEFhFfsi38tLxM2x./gH2', 'd09e53b8c5fedc9f28fbbdd5c6fdcc972370ca21371bdb66245d62414c5c618d', '2025-08-11 11:52:40', 1, 0, '2025-08-11 05:54:35', '2025-08-11 06:22:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `management`
--
ALTER TABLE `management`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `management`
--
ALTER TABLE `management`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `management`
--
ALTER TABLE `management`
  ADD CONSTRAINT `management_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`),
  ADD CONSTRAINT `management_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
