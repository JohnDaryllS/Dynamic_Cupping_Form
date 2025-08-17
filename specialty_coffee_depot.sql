-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 16, 2025 at 04:57 PM
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
-- Database: `specialty_coffee_depot`
--

-- --------------------------------------------------------

--
-- Table structure for table `cupping_forms`
--

CREATE TABLE `cupping_forms` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `submission_date` datetime NOT NULL,
  `form_date` date NOT NULL,
  `form_number` int(11) NOT NULL DEFAULT 1,
  `table_no` varchar(50) NOT NULL,
  `batch_number` int(11) NOT NULL,
  `sample_id` varchar(100) DEFAULT NULL,
  `fragrance_intensity` int(11) NOT NULL DEFAULT 3,
  `fragrance_attributes` text DEFAULT NULL,
  `fragrance_others_text` text DEFAULT NULL,
  `flavor_intensity` int(11) NOT NULL DEFAULT 3,
  `flavor_attributes` text DEFAULT NULL,
  `flavor_others_text` text DEFAULT NULL,
  `body_intensity` int(11) NOT NULL DEFAULT 3,
  `body_type` text DEFAULT NULL,
  `body_others_text` text DEFAULT NULL,
  `acidity_intensity` int(11) NOT NULL DEFAULT 3,
  `acidity_type` text DEFAULT NULL,
  `acidity_others_text` text DEFAULT NULL,
  `sweetness_intensity` int(11) NOT NULL DEFAULT 3,
  `sweetness_type` text DEFAULT NULL,
  `sweetness_others_text` text DEFAULT NULL,
  `general_notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cupping_forms`
--

INSERT INTO `cupping_forms` (`id`, `user_id`, `user_name`, `submission_date`, `form_date`, `form_number`, `table_no`, `batch_number`, `sample_id`, `fragrance_intensity`, `fragrance_attributes`, `fragrance_others_text`, `flavor_intensity`, `flavor_attributes`, `flavor_others_text`, `body_intensity`, `body_type`, `body_others_text`, `acidity_intensity`, `acidity_type`, `acidity_others_text`, `sweetness_intensity`, `sweetness_type`, `sweetness_others_text`, `general_notes`, `created_at`) VALUES
(1, 3, 'johndaryllramos8@gmail.com', '2025-08-14 12:38:21', '2025-08-14', 1, '3', 1, 'RA242', 2, '[\"green\",\"grain\",\"fruity\",\"nutty\",\"roasted\",\"others\"]', 'DSADSADSADSADAS', 2, '[\"spices\",\"brown_spices\",\"nutty\",\"sweet\",\"caramel\",\"floral\",\"berry\",\"pomme\",\"winey\",\"others\"]', 'DSADSADASDAS', 4, '[\"smooth\",\"others\"]', 'DSADASDASDSAASD', 3, '[\"ripe_fruit\",\"winey\",\"others\"]', 'DSADSADASDSA', 3, '[\"ripe_fruit\",\"others\"]', 'DSADSADSADSA', 'DSADSADASDSADSADSADSADASDSA', '2025-08-14 04:38:21'),
(2, 3, 'johndaryllramos8@gmail.com', '2025-08-14 23:21:38', '2025-08-14', 1, '3', 1, 'RA242', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', '', '2025-08-14 15:21:38'),
(3, 3, 'johndaryllramos8@gmail.com', '2025-08-14 23:21:38', '2025-08-14', 2, '3', 1, 'RA213', 4, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', '', '2025-08-14 15:21:38'),
(4, 3, 'johndaryllramos8@gmail.com', '2025-08-14 23:21:38', '2025-08-14', 3, '3', 1, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', '', '2025-08-14 15:21:38'),
(5, 3, 'johndaryllramos8@gmail.com', '2025-08-14 23:21:38', '2025-08-14', 4, '3', 1, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', '', '2025-08-14 15:21:38'),
(6, 3, 'johndaryllramos8@gmail.com', '2025-08-14 23:21:38', '2025-08-14', 5, '3', 1, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', '', '2025-08-14 15:21:38'),
(7, 3, 'johndaryllramos8@gmail.com', '2025-08-14 23:21:38', '2025-08-14', 6, '3', 1, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', '', '2025-08-14 15:21:38'),
(8, 3, 'johndaryllramos8@gmail.com', '2025-08-16 14:35:35', '2025-08-14', 1, '3', 1, 'RA242', 2, NULL, 'dsadsadasdsa', 3, NULL, '', 2, NULL, '', 3, NULL, 'hello testing', 3, NULL, '', 'ewqewqeqweqw', '2025-08-16 06:35:35'),
(9, 3, 'johndaryllramos8@gmail.com', '2025-08-16 14:35:35', '2025-08-14', 2, '3', 1, '', 5, NULL, 'eweqewq', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', '', '2025-08-16 06:35:35'),
(10, 3, 'johndaryllramos8@gmail.com', '2025-08-16 14:35:35', '2025-08-14', 3, '3', 1, 'RA242', 4, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', '', '2025-08-16 06:35:35'),
(11, 3, 'johndaryllramos8@gmail.com', '2025-08-16 14:35:35', '2025-08-14', 4, '3', 1, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 5, NULL, '', 4, NULL, '', '', '2025-08-16 06:35:35'),
(12, 3, 'johndaryllramos8@gmail.com', '2025-08-16 14:35:35', '2025-08-14', 5, '3', 1, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', '', '2025-08-16 06:35:35'),
(13, 3, 'johndaryllramos8@gmail.com', '2025-08-16 14:35:35', '2025-08-14', 6, '3', 1, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, 'testing', 3, NULL, 'ewqeqww', '', '2025-08-16 06:35:35'),
(14, 3, 'johndaryllramos8@gmail.com', '2025-08-16 15:25:04', '2025-08-16', 1, '31', 1, '31', 2, '[\"green\"]', '', 1, NULL, '', 2, NULL, '', 1, NULL, '', 4, NULL, '', '', '2025-08-16 07:25:04'),
(15, 3, 'johndaryllramos8@gmail.com', '2025-08-16 15:25:04', '2025-08-16', 2, '2', 1, '123', 3, NULL, '', 3, NULL, '', 3, NULL, '', 4, NULL, '', 2, NULL, '', 'ewqewqeqweqw', '2025-08-16 07:25:04'),
(16, 3, 'johndaryllramos8@gmail.com', '2025-08-16 15:25:04', '2025-08-16', 3, '36', 1, '53', 1, NULL, 'ewqewqeqw', 1, NULL, '', 1, NULL, '', 1, NULL, '', 1, NULL, '', '', '2025-08-16 07:25:04'),
(17, 3, 'johndaryllramos8@gmail.com', '2025-08-16 15:25:04', '2025-08-16', 4, '3', 1, '11', 2, NULL, '', 4, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', '', '2025-08-16 07:25:04'),
(18, 3, 'johndaryllramos8@gmail.com', '2025-08-16 15:25:04', '2025-08-16', 5, '8', 1, '232', 1, NULL, '', 1, NULL, '', 1, NULL, '', 1, NULL, '', 1, NULL, '', '', '2025-08-16 07:25:04'),
(19, 3, 'johndaryllramos8@gmail.com', '2025-08-16 15:25:04', '2025-08-16', 6, '1', 2, '422', 3, NULL, '', 1, NULL, '', 3, NULL, '', 3, NULL, '', 4, '[\"ripe_fruit\"]', '', '', '2025-08-16 07:25:04'),
(20, 3, 'johndaryllramos8@gmail.com', '2025-08-16 17:09:45', '2025-08-16', 1, '', 1, '', 5, NULL, '', 5, NULL, '', 5, NULL, '', 5, NULL, '', 5, NULL, '', '', '2025-08-16 09:09:45'),
(21, 3, 'johndaryllramos8@gmail.com', '2025-08-16 17:09:45', '0000-00-00', 2, '', 1, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', '', '2025-08-16 09:09:45'),
(22, 3, 'johndaryllramos8@gmail.com', '2025-08-16 17:09:45', '0000-00-00', 3, '', 1, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', '', '2025-08-16 09:09:45'),
(23, 3, 'johndaryllramos8@gmail.com', '2025-08-16 17:09:45', '0000-00-00', 4, '', 1, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', '', '2025-08-16 09:09:45'),
(24, 3, 'johndaryllramos8@gmail.com', '2025-08-16 17:09:45', '0000-00-00', 5, '', 1, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', '', '2025-08-16 09:09:45'),
(25, 3, 'johndaryllramos8@gmail.com', '2025-08-16 17:09:45', '2025-08-16', 6, '3', 1, '', 1, NULL, '', 1, NULL, '', 1, NULL, '', 1, NULL, '', 1, NULL, '', '', '2025-08-16 09:09:45'),
(26, 3, 'johndaryllramos8@gmail.com', '2025-08-16 17:10:07', '0000-00-00', 1, '', 1, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', '', '2025-08-16 09:10:07'),
(27, 3, 'johndaryllramos8@gmail.com', '2025-08-16 17:10:07', '0000-00-00', 2, '', 1, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', '', '2025-08-16 09:10:07'),
(28, 3, 'johndaryllramos8@gmail.com', '2025-08-16 17:10:07', '0000-00-00', 3, '', 1, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', '', '2025-08-16 09:10:07'),
(30, 3, 'johndaryllramos8@gmail.com', '2025-08-16 17:10:07', '0000-00-00', 5, '', 1, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', '', '2025-08-16 09:10:07'),
(31, 3, 'johndaryllramos8@gmail.com', '2025-08-16 17:10:07', '2025-08-16', 6, '3', 1, '', 1, NULL, '', 1, NULL, '', 1, NULL, '', 1, NULL, '', 1, NULL, '', '', '2025-08-16 09:10:07'),
(34, 3, 'johndaryllramos8@gmail.com', '2025-08-16 21:44:53', '2025-08-16', 3, '', 1, '', 1, NULL, '', 1, NULL, '', 2, NULL, '', 3, NULL, '', 2, NULL, '', '', '2025-08-16 13:44:53'),
(42, 3, 'johndaryllramos8@gmail.com', '2025-08-16 22:02:16', '0000-00-00', 5, '', 1, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', '', '2025-08-16 14:02:16'),
(44, 3, 'johndaryllramos8@gmail.com', '2025-08-16 22:28:39', '2025-08-16', 1, '3', 1, '422', 2, NULL, 'ewqeqweqweqw testing', 3, NULL, ' testing testing', 3, NULL, '', 3, NULL, '', 3, NULL, ' testing', '', '2025-08-16 14:28:39'),
(45, 3, 'johndaryllramos8@gmail.com', '2025-08-16 22:28:39', '2025-08-16', 2, '2', 1, 'RA242', 3, NULL, 'testing', 2, NULL, '', 2, NULL, 'testingtestingtesting', 1, NULL, 'testingtesting', 2, NULL, 'testingtesting', 'ewqewqdsadq', '2025-08-16 14:28:39'),
(46, 3, 'johndaryllramos8@gmail.com', '2025-08-16 22:28:39', '2025-08-16', 3, '5', 1, 'RA242', 3, NULL, 'ewqeqw', 3, NULL, 'testing', 3, NULL, 'testing', 3, NULL, 'ewqevtesting', 3, NULL, 'testing', 'testingtestingtestingtesting', '2025-08-16 14:28:39'),
(47, 3, 'johndaryllramos8@gmail.com', '2025-08-16 22:28:39', '2025-08-16', 4, '8', 1, '422s', 3, NULL, 'testing', 3, NULL, '', 3, NULL, 'testingtesting', 3, NULL, 'testingtestingtesting', 3, NULL, 'testing', 'testingtestingtestingtesting', '2025-08-16 14:28:39'),
(48, 3, 'johndaryllramos8@gmail.com', '2025-08-16 22:28:39', '2025-08-16', 5, '2', 1, 'RA242', 3, NULL, 'dsadasdtestingtestingtesting', 3, NULL, 'testingtesting', 2, NULL, 'testingtesting', 3, NULL, 'testingtesting', 3, NULL, 'ewqesdsad testingtestingtesting', 'ewqewqeqtestingtestingtesting', '2025-08-16 14:28:39'),
(49, 3, 'johndaryllramos8@gmail.com', '2025-08-16 22:28:39', '2025-08-16', 6, '3', 1, '', 2, NULL, '', 2, NULL, 'testing', 2, NULL, 'testingtesting', 3, NULL, 'testingtesting', 3, NULL, 'testingtestingtestingtestingtesting', 'testingtestingtestingtesting', '2025-08-16 14:28:39'),
(50, 3, 'johndaryllramos8@gmail.com', '2025-08-16 22:42:48', '2025-08-16', 1, '2', 1, 'RA242', 2, '[\"fruity\"]', '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', '', '2025-08-16 14:42:48'),
(51, 3, 'johndaryllramos8@gmail.com', '2025-08-16 22:42:48', '0000-00-00', 2, '', 1, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', '', '2025-08-16 14:42:48'),
(52, 3, 'johndaryllramos8@gmail.com', '2025-08-16 22:42:48', '0000-00-00', 3, '', 1, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', '', '2025-08-16 14:42:48'),
(53, 3, 'johndaryllramos8@gmail.com', '2025-08-16 22:42:48', '0000-00-00', 4, '', 1, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', '', '2025-08-16 14:42:48'),
(54, 3, 'johndaryllramos8@gmail.com', '2025-08-16 22:42:48', '0000-00-00', 5, '', 1, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', '', '2025-08-16 14:42:48'),
(55, 3, 'johndaryllramos8@gmail.com', '2025-08-16 22:42:48', '2025-08-16', 6, '3', 1, '', 2, '[\"nutty\"]', '', 4, NULL, '', 2, NULL, '', 3, NULL, '', 3, NULL, '', '', '2025-08-16 14:42:48'),
(56, 3, 'johndaryllramos8@gmail.com', '2025-08-16 22:45:10', '2025-08-16', 1, '2357', 1, '', 2, '[\"others\"]', 'EWQEWQ', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 'EWQEWQ', '2025-08-16 14:45:10'),
(57, 3, 'johndaryllramos8@gmail.com', '2025-08-16 22:45:10', '0000-00-00', 2, '', 1, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', '', '2025-08-16 14:45:10'),
(58, 3, 'johndaryllramos8@gmail.com', '2025-08-16 22:45:10', '0000-00-00', 3, '', 1, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', '', '2025-08-16 14:45:10'),
(59, 3, 'johndaryllramos8@gmail.com', '2025-08-16 22:45:10', '0000-00-00', 4, '', 1, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', '', '2025-08-16 14:45:10'),
(60, 3, 'johndaryllramos8@gmail.com', '2025-08-16 22:45:10', '0000-00-00', 5, '', 1, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', '', '2025-08-16 14:45:10'),
(61, 3, 'johndaryllramos8@gmail.com', '2025-08-16 22:45:10', '2025-08-16', 6, '32', 1, '', 2, '[\"spices\"]', '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 'EWQEQW', '2025-08-16 14:45:10'),
(62, 3, 'johndaryllramos8@gmail.com', '2025-08-16 22:54:57', '2025-08-16', 1, '232', 1, 'RA242', 3, '[\"fruity\"]', '', 3, '[\"others\"]', 'DSDAS', 3, '[\"smooth\"]', '', 3, '[\"others\"]', 'DSADAS', 3, '[\"others\"]', 'DSADAS', 'DSADASDSA', '2025-08-16 14:54:57'),
(63, 3, 'johndaryllramos8@gmail.com', '2025-08-16 22:54:57', '0000-00-00', 2, '', 1, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', '', '2025-08-16 14:54:57'),
(64, 3, 'johndaryllramos8@gmail.com', '2025-08-16 22:54:57', '0000-00-00', 3, '', 1, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', '', '2025-08-16 14:54:57'),
(65, 3, 'johndaryllramos8@gmail.com', '2025-08-16 22:54:57', '0000-00-00', 4, '', 1, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', '', '2025-08-16 14:54:57'),
(66, 3, 'johndaryllramos8@gmail.com', '2025-08-16 22:54:57', '0000-00-00', 5, '', 1, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', '', '2025-08-16 14:54:57'),
(67, 3, 'johndaryllramos8@gmail.com', '2025-08-16 22:54:57', '2025-08-16', 6, '22', 1, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', '', '2025-08-16 14:54:57');

-- --------------------------------------------------------

--
-- Table structure for table `remember_tokens`
--

CREATE TABLE `remember_tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token_hash` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `remember_tokens`
--

INSERT INTO `remember_tokens` (`id`, `user_id`, `token_hash`, `expires_at`, `created_at`) VALUES
(1, 1, 'c85aa24e74761a91281a870479fa26926f6d37e866a4ace58bbcb91a43b90c63', '2025-05-14 12:53:45', '2025-04-14 10:53:45'),
(2, 5, 'a7729e9c5370aa069928f7078deaedeb4adb13efe684a5c51cbceeb3613eda50', '2025-05-14 12:56:48', '2025-04-14 10:56:48');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `role`) VALUES
(1, '', 'admin@specialtycoffeedepotph.com', 'c0b9f07001f97804f5bce76941e9f403c6db8485ee9121c352400ede71843345', 'admin'),
(3, 'John Daryll Sampilingan', 'johndaryllramos8@gmail.com', '932f3c1b56257ce8539ac269d7aab42550dacf8818d075f0bdf1990562aae3ef', 'user'),
(4, 'Johnny gayo', 'johnny.gayo@specialtycoffeedepotph.com', '932f3c1b56257ce8539ac269d7aab42550dacf8818d075f0bdf1990562aae3ef', 'user'),
(5, 'Felsone Caragao', 'felsonecaragao@gmail.com', '5751a44782594819e4cb8aa27c2c9d87a420af82bc6a5a05bc7f19c3bb00452b', 'user'),
(6, 'Princess Ann Sampilingan', 'johndaryllramos23@gmail.com', '1718c24b10aeb8099e3fc44960ab6949ab76a267352459f203ea1036bec382c2', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cupping_forms`
--
ALTER TABLE `cupping_forms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

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
-- AUTO_INCREMENT for table `cupping_forms`
--
ALTER TABLE `cupping_forms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  ADD CONSTRAINT `remember_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
