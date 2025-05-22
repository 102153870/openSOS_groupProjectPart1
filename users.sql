-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 22, 2025 at 02:56 PM
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
-- Database: `opensos`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `first_name` varchar(20) DEFAULT NULL,
  `last_name` varchar(20) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `address` varchar(40) DEFAULT NULL,
  `suburb` varchar(40) DEFAULT NULL,
  `state` varchar(3) DEFAULT NULL,
  `postcode` varchar(4) DEFAULT NULL,
  `phone_number` varchar(12) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `role`, `email`, `first_name`, `last_name`, `dob`, `gender`, `address`, `suburb`, `state`, `postcode`, `phone_number`) VALUES
(8, 'OpenSOS', '$2y$10$jYVJnyEdRURuFzi2bf4NFOokuab9c8FDdfBzZSF0qNNdkMLeo9lFy', 'manager', 'opensos404@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 'Test', '$2y$10$Q.QYPotJI3RrxNRuYBY9BObUzt6L/v.Yu9N10O7kqKzjsJxybXU2C', 'user', 'testemail@hotmail.com', 'Test', 'Name', '2015-05-19', 'Female', '12 test ave', 'Suburb', 'nsw', '4444', '88882222'),
(13, 'The Flash', '$2y$10$9aovVKBvlWJ0CE/pNk7WOOF292Esz6q6lKDFyDONzw3A2GJw1LkOu', 'user', 'flash@hotmail.com', 'Barry', 'Allen', '1960-01-01', 'Prefer Not To Say', '1 speedster way', 'Central City', 'nsw', '0000', '11112222'),
(14, 'Rodney', '$2y$10$VTJh7ugpaS20UBYaacCS/exiENtA7qnhXKQyxj0qOSPKnLSaEu34i', 'user', 'rodney@gmail.com', 'Rodney', 'Liaw', '2014-05-07', 'Prefer Not To Say', '171 Rodney st', 'Hawthorn', 'wa', '4444', '77778888'),
(15, 'Ryan', '$2y$10$EiqeMGYnOcxPH2jTWYoE9.5.jdPsSSS0MQ69jV4me9NN9H6UaRukq', 'user', 'ryan@gmail.com', 'Ryan', 'Weber', '1999-08-17', 'Other', '455 Ryan way', 'Geelong', 'act', '2222', '12345678'),
(16, 'Henry', '$2y$10$nHCi32np90Jljjcp/lCfCe7jxQJ2hmjlPOcgjwUH6hjPt44eFGA1i', 'user', 'henry@hotmail.com', 'Henry', 'Low', '2001-02-14', 'Female', '23 Henry ave', 'Stkilda', 'sa', '9999', '45671234');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
