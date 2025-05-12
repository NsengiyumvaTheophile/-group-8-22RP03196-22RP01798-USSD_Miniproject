-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 12, 2025 at 11:43 PM
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
-- Database: `ussd_miniproject`
--

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `sender_phone` varchar(20) NOT NULL,
  `receiver_phone` varchar(20) NOT NULL,
  `amount` float NOT NULL,
  `fee` float DEFAULT 0,
  `status` varchar(20) DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `sender_phone`, `receiver_phone`, `amount`, `fee`, `status`, `created_at`) VALUES
(1, '', '0780888087', 100, 10, 'confirmed', '2025-05-12 18:52:17'),
(2, '', '0780888087', 200, 10, 'confirmed', '2025-05-12 19:08:36'),
(3, '', '0780888087', 100, 10, 'confirmed', '2025-05-12 19:18:35'),
(4, '+250780888084', '0780888084', 200, 10, 'confirmed', '2025-05-12 19:21:19'),
(5, '', '0780888087', 200, 10, 'confirmed', '2025-05-12 19:33:30'),
(6, '+250780888084', '0780888087', 200, 10, 'confirmed', '2025-05-12 19:56:12');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `pin` varchar(10) DEFAULT '1234',
  `balance` float DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `phone`, `pin`, `balance`, `created_at`) VALUES
(1, '', '1234', 360, '2025-05-12 18:50:35'),
(2, '0780888087', '1234', 800, '2025-05-12 18:52:17'),
(3, '+250780888084', '1234', 580, '2025-05-12 18:56:47'),
(4, '+250780888087', '1234', 1000, '2025-05-12 18:59:00'),
(5, '0780888084', '1234', 200, '2025-05-12 19:21:19'),
(6, '+250780888089', '1234', 1000, '2025-05-12 19:58:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
