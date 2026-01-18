-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 18, 2026 at 05:36 PM
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
-- Database: `renture`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `dob` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `age`, `contact`, `address`, `email`, `password`, `dob`) VALUES
(1, 'a', 18, '7894561230', 'sjalaskj', 'abc@gmail.com', '$2y$10$WgQO4Ug5wx7sras3hAO5PuuSLUdrhRU8hvEfB4E60OBe6QHolMGT.', '6005-05-04'),
(2, 'b', 19, '1324567980', 'sakdlam', 'bca@gmail.com', '$2y$10$EEXoR.4QHTwSDQz.szaFNu7VEsfUiBUIJVin4LtOdheWmzHeUvEFO', '1956-04-07'),
(3, 'c', 46, '86532185632', 'djksnwopakl', 'aaa@gmail.com', '$2y$10$lfMSXazfaWm8Xk5b2sLdM.7KpbtshPbtfsR2z4zgMyNNWTbA3.KTC', '7896-08-07'),
(4, 'e', 45, '856123865', 'djslkzas;lk', 'qqq@gmail.com', '$2y$10$JhMpBvOqdr4EZDdoL95R/..UcvbTVk9oFrsToxa4SsdwlpyuGzrvO', '1659-04-07'),
(5, 'alex', 46, '7894567894', 'akldjakljsl', 'alex@gmail.com', '$2y$10$u9xsfvvv2/oVbvVmT6hZRO8UbzCTUvAhWiXfe8NnTJFkvXckNSHJy', '1919-09-07'),
(6, 'abc', 19, '78945261235', 'aosdhao', 'eee@gmail.com', '$2y$10$MSJ4zn7OjwY44mEo7QESeeJxdH/ACMks8.XXQFz1zwtr.w24z8k96', '1986-05-22');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
