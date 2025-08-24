-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 07, 2025 at 08:11 AM
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
  `table_no` varchar(50) NOT NULL,
  `batch_number` int(11) NOT NULL,
  `fragrance_aroma` decimal(4,2) NOT NULL,
  `dry` int(11) NOT NULL,
  `break_value` int(11) NOT NULL,
  `quality1` varchar(100) DEFAULT NULL,
  `quality2` varchar(100) DEFAULT NULL,
  `fragrance_notes` text DEFAULT NULL,
  `flavor` decimal(4,2) NOT NULL,
  `flavor_notes` text DEFAULT NULL,
  `aftertaste` decimal(4,2) NOT NULL,
  `aftertaste_notes` text DEFAULT NULL,
  `acidity` decimal(4,2) NOT NULL,
  `acidity_intensity` int(11) NOT NULL,
  `acidity_notes` text DEFAULT NULL,
  `body` decimal(4,2) NOT NULL,
  `body_level` int(11) NOT NULL,
  `body_notes` text DEFAULT NULL,
  `uniformity` int(11) NOT NULL,
  `uniformity_notes` text DEFAULT NULL,
  `clean_cup` int(11) NOT NULL,
  `clean_cup_notes` text DEFAULT NULL,
  `overall` decimal(4,2) NOT NULL,
  `overall_notes` text DEFAULT NULL,
  `balance` decimal(4,2) NOT NULL,
  `balance_notes` text DEFAULT NULL,
  `sweetness` int(11) NOT NULL,
  `sweetness_notes` text DEFAULT NULL,
  `defective_cups` int(11) DEFAULT NULL,
  `defect_intensity` int(11) DEFAULT NULL,
  `defect_points` int(11) DEFAULT NULL,
  `total_score` decimal(5,2) NOT NULL,
  `final_score` decimal(5,2) NOT NULL,
  `comments` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cupping_forms`
--

INSERT INTO `cupping_forms` (`id`, `user_id`, `user_name`, `submission_date`, `form_date`, `table_no`, `batch_number`, `fragrance_aroma`, `dry`, `break_value`, `quality1`, `quality2`, `fragrance_notes`, `flavor`, `flavor_notes`, `aftertaste`, `aftertaste_notes`, `acidity`, `acidity_intensity`, `acidity_notes`, `body`, `body_level`, `body_notes`, `uniformity`, `uniformity_notes`, `clean_cup`, `clean_cup_notes`, `overall`, `overall_notes`, `balance`, `balance_notes`, `sweetness`, `sweetness_notes`, `defective_cups`, `defect_intensity`, `defect_points`, `total_score`, `final_score`, `comments`) VALUES
(7, 3, 'johndaryllramos8@gmail.com', '2025-04-14 17:16:35', '2025-04-14', '34A', 1, 9.50, 5, 1, 'sweet', 'badass', 'eqwe', 9.75, 'dsadsa', 10.00, 'dsadas', 9.75, 4, 'dsadasd', 9.75, 3, 'dsdas', 9, 'dsdasd', 10, 'dsadsa', 9.50, 'dsadasdasdadas', 9.75, 'dsada', 10, 'wewqeqw', 4, 3, 12, 97.00, 85.00, 'testing testing'),
(8, 5, 'felsonecaragao@gmail.com', '2025-04-14 18:06:07', '2025-04-14', '22', 6, 9.75, 5, 5, 'q1', 'q2', 'fa', 9.50, 'fs', 9.75, 'at', 9.75, 1, 'ac', 10.00, 5, 'bd', 9, 'umt', 10, '', 9.75, 'on', 9.75, 'balance', 10, '', 2, 2, 4, 97.25, 93.25, 'notes/comments'),
(9, 6, 'johndaryllramos23@gmail.com', '2025-04-16 21:26:51', '2025-04-16', '32C', 5, 9.75, 5, 5, 'X1', 'X12', 'SD', 9.50, 'SDAS', 10.00, 'DSAD', 6.00, 2, 'DSA', 9.75, 4, 'DSADAS', 9, 'DSADAS', 9, 'dsdsadas', 9.50, 'dsadas', 8.50, 'dsdasdasdsadsa', 9, 'dsadsaas', 2, 2, 4, 90.00, 86.00, 'fdsfdsfdsf testing');

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
-- Constraints for table `cupping_forms`
--
ALTER TABLE `cupping_forms`
  ADD CONSTRAINT `cupping_forms_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  ADD CONSTRAINT `remember_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
