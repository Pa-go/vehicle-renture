-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 20, 2026 at 04:42 AM
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
(1, 14, 0, 0.00, 'Cash', 'Success', '2026-03-14 18:53:14'),
(2, 15, 0, 44500.00, 'Card', 'Success', '2026-03-18 06:32:33'),
(3, 15, 0, 4900.00, 'Card', 'Success', '2026-03-18 08:00:05'),
(4, 15, 1001, 44500.00, 'UPI', 'Success', '2026-03-19 04:21:07'),
(5, 15, 10, 4900.00, 'Cash', 'Success', '2026-03-19 04:22:06');

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
(14, 'xaden', 45, '1234567891', 'gfzcxml,. awzesxdrcftvgbhjn km,l.', 'lll@gmail.com', '$2y$10$LBCSrAZ1LM0TQd6XabNhT.C2me41JSK.bxghiwdvaoosOx/HgNnuq', '2026-04-04'),
(15, 'riya', 19, '9221445654', '10,ramesh niwas, kopar road', 'riyapatkar08@gmail.com', '$2y$10$R2TveyslBmeTQ6.cZjra4uXJmdCY2BKfH9OutbQ9Ok.PJf/dfOD1u', '2007-02-08');

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
(13, 15, 'Tata Sieaara', 'TATA', '2023', 'Car', 'MH05GD4657', 'Petrol', 'Yellow', 4500.00, 500.00, 4000.00, '11. a modern premium SUV, blending nostalgia with advanced tech, priced from 11.49 lakh. It features a 1.5L Turbo/NA petrol engine, offering 360° cameras, ADAS, a 19-inch wheel option, and a distinctive, spacious design reminiscent of the iconic original.', NULL, 'ADAS: Level 2 Advanced Driver Assistance Systems. Camera: 360-degree camera with 4Sight blind-spot monitoring. Airbags: Six airbags as standard. Brakes: Disc brakes on all four', 'Unavailable', 'uploads/1773895708_b982ad07.jpeg', '2026-03-19 04:48:28', '', 'Used'),
(14, 15, 'Urban cruiser', 'TOYOTA', '2025', 'Car', 'MH02FD7594', 'Petrol', 'Blue', 2500.00, 300.00, 2200.00, 'The Toyota Urban Cruiser is a compact SUV designed for city driving, featuring a 1.5-liter petrol engine, 4-cylinder performance, and options for manual or automatic transmission. Known for its high driving position, reliable performance (103 bhp), and modern, spacious interior, it offers a practical, stylish, and efficient daily driving experience.', NULL, '360-degree camera, panoramic sunroof, ventilated leather seats, head-up display, 9-inch touchscreen, and advanced safety options like 6-7 airbags, ABS with EBD, and VSC.', 'Available', 'uploads/1773895960_f2422976.jpeg', '2026-03-19 04:52:40', '', 'Used'),
(15, 15, 'Seltos', 'KIA', '2025', 'Car', 'MH02FA5023', 'Diesel', 'Grey', 3000.00, 0.00, 3000.00, 'Kia Seltos is a premium 5-seater compact SUV with a bold design, featuring of 1.5L diesel engine. It boasts a futuristic Trinity Panoramic Display, ADAS Level 2 safety, 64-color ambient lighting, ventilated seats, and a panoramic sunroof, making it a feature-packed, comfortable daily driver.', NULL, '6 Airbags, ABS with EBD, Electronic Stability Control (ESC), Hill-Start Assist Control (HAC), and all-wheel disc brakes.', 'Available', 'uploads/1773896182_323f3512.jpeg', '2026-03-19 04:56:22', '', 'Used'),
(16, 15, 'Super Splendor', 'Hero', '2022', 'Bike', 'MH05EF6542', 'Petrol', 'black', 1000.00, 100.00, 900.00, 'The Hero Super Splendor is a popular, 125cc commuter motorcycle in India, renowned for its reliability, fuel efficiency, and practical design for daily riding. It is marketed as a &quot;no-nonsense&quot; workhorse, frequently used by office-goers and for family transportation due to its comfortable, long seat and\r\nrobust build.', NULL, 'Features i3S (idle stop-start system) for better fuel efficiency, LED headlamps (XTEC), and a USB charger.', 'Available', 'uploads/1773901760_87849c54.jpeg', '2026-03-19 06:29:20', '', 'Used');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
