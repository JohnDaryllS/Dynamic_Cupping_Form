-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 21, 2025 at 04:41 PM
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
(9, 3, 'johndaryllramos8@gmail.com', '2025-08-16 14:35:35', '2025-08-14', 2, '3', 1, '', 5, NULL, 'eweqewq', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', '', '2025-08-16 06:35:35'),
(80, 3, 'johndaryllramos8@gmail.com', '2025-08-17 07:46:55', '2025-08-16', 1, '23', 1, '13131', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', '', '2025-08-16 23:46:55'),
(81, 3, 'johndaryllramos8@gmail.com', '2025-08-17 07:46:55', '0000-00-00', 2, '', 1, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', '', '2025-08-16 23:46:55'),
(82, 3, 'johndaryllramos8@gmail.com', '2025-08-17 07:46:55', '0000-00-00', 3, '', 1, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', '', '2025-08-16 23:46:55'),
(83, 3, 'johndaryllramos8@gmail.com', '2025-08-17 07:46:55', '0000-00-00', 4, '', 1, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', '', '2025-08-16 23:46:55'),
(84, 3, 'johndaryllramos8@gmail.com', '2025-08-17 07:46:55', '0000-00-00', 5, '', 1, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', '', '2025-08-16 23:46:55'),
(85, 3, 'johndaryllramos8@gmail.com', '2025-08-17 07:46:55', '2025-08-16', 6, '42', 1, '415', 2, NULL, 'dsadsa', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 'dsadsa', '2025-08-16 23:46:55'),
(86, 3, 'johndaryllramos8@gmail.com', '2025-08-17 07:52:33', '2025-08-16', 1, '321', 1, '412', 2, '[\"green\",\"grain\",\"floral\",\"fruity\",\"sweet\",\"nutty\",\"others\"]', 'sdasdasas', 4, '[\"spices\",\"nutty\",\"caramel\",\"floral\",\"dried_fruit\",\"tropical\",\"berry\",\"pomme\",\"winey\"]', '', 2, '[\"rough\",\"smooth\"]', '', 3, NULL, '', 2, '[\"nutty\"]', '', 'dsadasdaad', '2025-08-16 23:52:33'),
(87, 3, 'johndaryllramos8@gmail.com', '2025-08-17 08:05:09', '2025-08-16', 1, '321', 1, '412', 3, '[\"green\",\"grain\",\"floral\",\"fruity\",\"sweet\",\"nutty\",\"others\"]', 'DSADSADSADSADAS', 3, '[\"nutty\",\"brown_sugar\",\"caramel\"]', '', 3, NULL, '', 3, '[\"ripe_fruit\",\"winey\"]', '', 3, '[\"nutty\"]', '', 'dsadasdsadsa', '2025-08-17 00:05:09'),
(92, 7, 'johndaryllramos9@gmail.com', '2025-08-19 15:06:46', '2025-08-17', 4, '321', 1, '412', 3, '[\"green\",\"grain\",\"floral\",\"fruity\",\"nutty\",\"spices\",\"roasted\",\"others\"]', 'dsadasdtestingtestingtesting', 3, '[\"spices\",\"nutty\",\"sweet\",\"caramel\",\"vanilla\",\"floral\",\"fruity\",\"dried_fruit\",\"drupe\",\"winey\",\"sour\"]', '', 3, '[\"rough\",\"smooth\",\"others\"]', 'ewsdas', 3, '[\"ripe_fruit\",\"vinegar\",\"others\"]', 'DSADSADASDSA', 3, '[\"ripe_fruit\",\"sweet\",\"others\"]', 'dsadasdsa', 'dsadascdsacdsacdsa', '2025-08-19 07:06:46'),
(93, 7, 'johndaryllramos9@gmail.com', '2025-08-19 15:06:46', '2025-08-17', 6, '321', 1, '412', 5, NULL, '', 5, NULL, '', 5, NULL, '', 3, NULL, '', 4, NULL, '', '', '2025-08-19 07:06:46'),
(94, 7, 'johndaryllramos9@gmail.com', '2025-08-19 20:08:48', '2025-08-19', 6, '3', 1, '', 4, '[\"green\",\"grain\",\"floral\",\"fruity\",\"sweet\",\"nutty\",\"spices\",\"roasted\",\"others\"]', 'eweqwecqwe', 5, '[\"spices\",\"brown_spices\",\"nutty\",\"chocolate\",\"brown_sugar\",\"floral\",\"dried_fruit\",\"tropical\",\"winey\"]', '', 4, '[\"rough\"]', '', 3, '[\"ripe_fruit\",\"winey\"]', '', 3, '[\"ripe_fruit\",\"nutty\",\"others\"]', 'ewqcewqceqw', 'cewqcewqceqw', '2025-08-19 12:08:48'),
(95, 3, 'johndaryllramos8@gmail.com', '2025-08-21 16:07:34', '2025-08-21', 6, '3', 1, '', 4, '[\"green\",\"fruity\"]', '', 2, '[\"spices\",\"caramel\",\"floral\",\"dried_fruit\",\"winey\"]', '', 4, NULL, '', 2, NULL, '', 4, NULL, '', '', '2025-08-21 08:07:34'),
(96, 3, 'johndaryllramos8@gmail.com', '2025-08-21 16:07:34', '2025-08-21', 5, '', 1, '', 2, '[\"floral\"]', '', 3, NULL, '', 3, NULL, '', 3, NULL, '', 3, NULL, '', '', '2025-08-21 08:07:34'),
(97, 3, 'johndaryllramos8@gmail.com', '2025-08-21 16:07:34', '2025-08-21', 1, '', 1, '', 4, NULL, '', 4, '[\"nutty\"]', '', 4, NULL, '', 3, NULL, '', 2, NULL, '', '', '2025-08-21 08:07:34'),
(98, 3, 'johndaryllramos8@gmail.com', '2025-08-21 19:04:12', '2025-08-21', 1, '321', 1, '411', 4, '[\"green\",\"grain\",\"floral\",\"fruity\",\"spices\",\"roasted\",\"others\"]', 'dsadasdtestingtestingtesting', 2, '[\"spices\",\"nutty\",\"chocolate\",\"sweet\",\"brown_sugar\",\"vanilla\",\"floral\",\"fruity\",\"dried_fruit\",\"drupe\",\"pomme\",\"winey\",\"sour\",\"others\"]', 'testing', 4, '[\"rough\",\"others\"]', 'testingtesting', 2, '[\"ripe_fruit\",\"winey\",\"others\"]', 'testingtestingtesting', 4, '[\"ripe_fruit\",\"nutty\",\"others\"]', 'testingtestingtestingtestingtesting', 'testingtestingtestingtestingtestingtestingtestingtesting', '2025-08-21 11:04:12'),
(99, 3, 'johndaryllramos8@gmail.com', '2025-08-21 19:04:12', '2025-08-21', 3, '321', 1, '413', 3, '[\"grain\",\"roasted\"]', '', 3, '[\"nutty\",\"caramel\",\"dried_fruit\",\"tropical\",\"sour\"]', '', 3, '[\"rough\"]', '', 3, '[\"ripe_fruit\"]', '', 3, '[\"ripe_fruit\"]', '', '', '2025-08-21 11:04:12'),
(100, 3, 'johndaryllramos8@gmail.com', '2025-08-21 19:04:12', '2025-08-21', 2, '31', 1, '422', 4, '[\"green\",\"grain\",\"spices\",\"roasted\"]', '', 3, '[\"nutty\",\"brown_sugar\",\"caramel\"]', '', 3, '[\"rough\"]', '', 3, '[\"ripe_fruit\"]', '', 3, '[\"nutty\"]', '', '', '2025-08-21 11:04:12'),
(101, 3, 'johndaryllramos8@gmail.com', '2025-08-21 19:04:12', '2025-08-21', 4, '321', 1, '414', 3, '[\"grain\",\"roasted\",\"others\"]', 'dsadasdtestingtestingtesting', 3, '[\"spices\",\"floral\",\"drupe\"]', '', 4, '[\"smooth\"]', '', 3, '[\"ripe_fruit\"]', '', 3, '[\"ripe_fruit\"]', '', '', '2025-08-21 11:04:12'),
(102, 3, 'johndaryllramos8@gmail.com', '2025-08-21 19:04:12', '2025-08-21', 5, 'e2', 1, 'RA245', 3, '[\"green\",\"sweet\",\"roasted\"]', '', 3, '[\"spices\",\"sweet\",\"brown_sugar\"]', '', 3, '[\"rough\"]', '', 3, '[\"ripe_fruit\"]', '', 3, '[\"ripe_fruit\"]', '', '', '2025-08-21 11:04:12'),
(103, 3, 'johndaryllramos8@gmail.com', '2025-08-21 19:04:12', '2025-08-21', 6, '3', 1, 'RA246', 4, '[\"green\",\"spices\"]', '', 3, '[\"nutty\",\"brown_sugar\",\"tropical\",\"berry\"]', '', 4, '[\"rough\"]', '', 4, '[\"ripe_fruit\",\"vinegar\"]', '', 5, '[\"ripe_fruit\",\"nutty\"]', '', 'cdsacdsacdascdas', '2025-08-21 11:04:12'),
(104, 3, 'johndaryllramos8@gmail.com', '2025-08-21 19:06:20', '2025-08-21', 1, '3', 1, 'RA246', 3, '[\"green\",\"grain\",\"floral\",\"spices\"]', '', 3, '[\"sweet\",\"brown_sugar\",\"floral\"]', '', 4, '[\"rough\"]', '', 5, '[\"ripe_fruit\",\"winey\"]', '', 4, '[\"ripe_fruit\",\"nutty\"]', '', '', '2025-08-21 11:06:20'),
(105, 3, 'johndaryllramos8@gmail.com', '2025-08-21 19:06:20', '2025-08-21', 6, '3', 1, '412', 4, '[\"grain\"]', '', 4, '[\"nutty\"]', '', 3, '[\"rough\"]', '', 3, '[\"ripe_fruit\"]', '', 3, '[\"ripe_fruit\",\"nutty\",\"sweet\",\"others\"]', 'testingtestingtestingtestingtesting', '', '2025-08-21 11:06:20');

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
  `role` enum('admin','user') DEFAULT 'user',
  `is_approved` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `role`, `is_approved`, `created_at`) VALUES
(1, '', 'admin@specialtycoffeedepotph.com', 'c0b9f07001f97804f5bce76941e9f403c6db8485ee9121c352400ede71843345', 'admin', 1, '2025-08-19 07:00:05'),
(3, 'John Daryll Sampilingan', 'johndaryllramos8@gmail.com', '932f3c1b56257ce8539ac269d7aab42550dacf8818d075f0bdf1990562aae3ef', 'user', 1, '2025-08-19 07:00:05'),
(4, 'Johnny gayo', 'johnny.gayo@specialtycoffeedepotph.com', '932f3c1b56257ce8539ac269d7aab42550dacf8818d075f0bdf1990562aae3ef', 'user', 1, '2025-08-19 07:00:05'),
(5, 'Felsone Caragao', 'felsonecaragao@gmail.com', '5751a44782594819e4cb8aa27c2c9d87a420af82bc6a5a05bc7f19c3bb00452b', 'user', 1, '2025-08-19 07:00:05'),
(6, 'Princess Ann Sampilingan', 'johndaryllramos23@gmail.com', '1718c24b10aeb8099e3fc44960ab6949ab76a267352459f203ea1036bec382c2', 'user', 1, '2025-08-19 07:00:05'),
(7, 'Felomina Sampilingan', 'johndaryllramos9@gmail.com', 'ef797c8118f02dfb649607dd5d3f8c7623048c9c063d532cc95c5ed7a898a64f', 'user', 1, '2025-08-19 07:01:47');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
