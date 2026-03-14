-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 14, 2026 at 09:26 PM
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
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `transaction_status` varchar(20) DEFAULT 'Success',
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `tenant_id`, `vehicle_id`, `amount`, `payment_method`, `transaction_status`, `booking_date`) VALUES
(1, 14, 0, 0.00, 'Cash', 'Success', '2026-03-14 18:53:14');

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
(6, 'abc', 19, '78945261235', 'aosdhao', 'eee@gmail.com', '$2y$10$MSJ4zn7OjwY44mEo7QESeeJxdH/ACMks8.XXQFz1zwtr.w24z8k96', '1986-05-22'),
(7, 'g', 45, '7894561235', 'djks', 'rrr@gmail.com', '$2y$10$VFwShjwKk6RT4OV8.E8VhO0RLw7Le0g3pR6yYZCM0mqKZ/o17frWe', '2026-01-19'),
(8, 'q', 45, '7896541235', 'wdasvsd', 'www@gmail.com', '$2y$10$i0fp3hyZa4m5FUss2P9f1eQumUYjMJiXhk645AR3EkShymLkgzQNm', '2026-01-21'),
(9, 'o', 93, '7894561235', 'ejkldsm', 'ggg@gmail.com', '$2y$10$5p.7U.pSISLOEuiHAPveeel.a1LetE72Jj123eCiGKGIY54gzKuUS', '2026-01-20'),
(14, 'xaden', 45, '1234567891', 'gfzcxml,. awzesxdrcftvgbhjn km,l.', 'lll@gmail.com', '$2y$10$LBCSrAZ1LM0TQd6XabNhT.C2me41JSK.bxghiwdvaoosOx/HgNnuq', '2026-04-04');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `vehicle_name` varchar(100) DEFAULT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `model` varchar(100) DEFAULT NULL,
  `vehicle_type` varchar(20) DEFAULT NULL,
  `plate_number` varchar(50) DEFAULT NULL,
  `fuel_type` varchar(50) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `discount` decimal(10,2) DEFAULT NULL,
  `final_price` decimal(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `vehicle_condition` varchar(50) DEFAULT NULL,
  `features` text DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `vehicle_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `condition` varchar(50) NOT NULL,
  `v_condition` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `owner_id`, `vehicle_name`, `brand`, `model`, `vehicle_type`, `plate_number`, `fuel_type`, `color`, `price`, `discount`, `final_price`, `description`, `vehicle_condition`, `features`, `status`, `vehicle_image`, `created_at`, `condition`, `v_condition`) VALUES
(1, 0, 'bmw', 'bmv', '789', 'Car', '865312', 'Diesel', 'black', 854.00, 20.00, 854.00, 'EZStdfxcvhjbknkm,l', 'New', 'azwserxdtcfvghb', 'Available', NULL, '2026-01-19 00:31:26', '', NULL),
(2, 0, 'bmw', 'bmw', 'bmw', 'Car', '4561', 'Diesel', 'black ', 700.00, 10.00, 690.00, '', 'New', 'abs ', 'Sold', NULL, '2026-01-19 04:51:20', '', NULL),
(3, 0, 'bn', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'uploads/1773481471_Screenshot 2026-02-17 212606.png', '2026-03-14 09:44:32', '', NULL),
(4, 0, 'abc ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'uploads/1773508642_Screenshot 2026-02-17 212606.png', '2026-03-14 17:17:22', '', NULL),
(5, 14, 'a', 'hhjj', '123', 'Bike', '34567uio', 'Diesel', 'rfsdczx', 7863.00, 80.00, 7783.00, 'x6tdcf7guinhjomkp,\'.', NULL, '', 'Sold', 'uploads/1773510289_Screenshot 2026-02-17 212606.png', '2026-03-14 17:44:49', '', 'New'),
(6, 14, 'abc ', 'qwerty', '123', 'Car', '34567uio', 'Hybrid', 'rfsdczx', 786.00, 0.00, 786.00, 'cfygvbhjukml,;./', NULL, '', 'Sold', 'uploads/1773510344_Screenshot 2026-02-17 212606.png', '2026-03-14 17:45:44', '', 'New'),
(7, 14, 'jagur', 'asda', 'dsfjkm', 'Bike', 'qwsd', 'Petrol', 'dscx', 0.01, 5623.00, -5622.99, 'xrtdbhjk.', NULL, '/xdrctfvgybhunjkml,;', 'Pending', 'uploads/1773511039_Screenshot 2026-02-08 230705.png', '2026-03-14 17:57:19', '', 'New');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
