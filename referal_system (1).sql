-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 19, 2024 at 04:31 PM
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
-- Database: `referal_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `referrals`
--

CREATE TABLE `referrals` (
  `id` int(11) NOT NULL,
  `referrer_id` int(11) NOT NULL,
  `referred_user_id` int(11) NOT NULL,
  `points_earned` int(11) NOT NULL,
  `referral_level` int(11) DEFAULT 1,
  `referral_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `referrals`
--

INSERT INTO `referrals` (`id`, `referrer_id`, `referred_user_id`, `points_earned`, `referral_level`, `referral_date`) VALUES
(6, 4, 5, 100, 1, '2024-09-18 14:20:08'),
(7, 4, 5, 50, 2, '2024-09-18 14:20:47'),
(8, 5, 6, 100, 1, '2024-09-18 14:20:47'),
(9, 4, 7, 100, 1, '2024-09-19 11:11:41');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `referral_code` varchar(10) NOT NULL,
  `referrer_id` int(11) DEFAULT NULL,
  `points` int(11) DEFAULT 0,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `referral_code`, `referrer_id`, `points`, `password`) VALUES
(4, 'abuzer', 'admin@gmail.com', 'E09FBA79', NULL, 250, '$2y$10$Zpdtdap8ZXDkr2YLROZdtuwrPDBBGDMFj8CtZjlKfk98F6towe41m'),
(5, 'abdu', 'abdu@gmail.com', '268B0CC9', 4, 100, '$2y$10$EY7uTkeDphxCHEO4MtG8jeIKkHdN4ddmDoR2WGHm9twnsB9t7TPKS'),
(6, 'avel', 'abdu1@gmail.com', '05ACB5E3', 5, 0, '$2y$10$M3CzdQgFJuehQPJPynBDKupHohxZCP2APqSPLGY27zON.FLTKW58a'),
(7, 'abdu2', 'abdu2@gmail.com', '5995E664', 4, 0, '$2y$10$2CaZwxQz1sEMtqTbsoJAYO9aJtLvhQEcA7xPzg0eVNNL.5eJIy6Ym');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `referrals`
--
ALTER TABLE `referrals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `referral_code` (`referral_code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `referrals`
--
ALTER TABLE `referrals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
