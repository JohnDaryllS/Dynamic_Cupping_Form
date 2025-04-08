-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 08, 2025 at 08:03 AM
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
(1, 4, '', '2025-04-07 07:15:25', '2025-04-07', '23', 1, 6.00, 3, 3, '', '', '', 6.00, '', 6.00, '', 8.50, 3, '', 6.00, 3, '', 10, '', 10, '', 10.00, '', 10.00, '', 9, '', 1, 2, 2, 81.50, 79.50, 'wewewqe'),
(2, 3, '', '2025-04-07 07:15:52', '2025-04-07', '22', 1, 6.00, 3, 3, '', '', '', 8.75, '', 8.75, '', 6.00, 3, '', 6.00, 3, '', 10, '', 10, '', 6.00, '', 6.00, '', 10, '321312', 0, 0, 0, 77.50, 77.50, '32312321312312');

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
(3, 'John Daryll Sampilingan', 'johndaryllramos8@gmail.com', '1718c24b10aeb8099e3fc44960ab6949ab76a267352459f203ea1036bec382c2', 'user'),
(4, 'Johnny gayo', 'johnny.gayo@specialtycoffeedepotph.com', '932f3c1b56257ce8539ac269d7aab42550dacf8818d075f0bdf1990562aae3ef', 'user');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cupping_forms`
--
ALTER TABLE `cupping_forms`
  ADD CONSTRAINT `cupping_forms_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
