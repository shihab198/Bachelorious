-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 24, 2025 at 08:11 PM
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
-- Database: `bachelorious`
--

-- --------------------------------------------------------

--
-- Table structure for table `properties`
--

CREATE TABLE `properties` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `type` enum('house','room','seat') NOT NULL,
  `address` text NOT NULL,
  `city` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `size` int(11) NOT NULL,
  `bedrooms` int(11) DEFAULT 1,
  `bathrooms` int(11) DEFAULT 1,
  `available_from` date NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `is_approved` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `properties`
--

INSERT INTO `properties` (`id`, `user_id`, `title`, `description`, `type`, `address`, `city`, `price`, `size`, `bedrooms`, `bathrooms`, `available_from`, `image_path`, `is_approved`, `created_at`) VALUES
(1, 2, 'bachelor room in mirpur', 'nice room', 'room', 'housing , mirpur', 'dhaka', 6000.00, 100, 1, 1, '2025-07-01', 'uploads/1750781037_premium_photo-1661877303180-19a028c21048.jpeg', 1, '2025-06-24 16:03:57'),
(2, 1, 'Bachelor Room in Dhanmondi', 'Spacious room ideal for bachelors with attached bath.', 'room', 'House #12, Road #7A, Dhanmondi', 'Dhaka', 6000.00, 120, 1, 1, '2025-07-01', 'uploads/dhanmondi1.jpg', 1, '2025-06-24 16:25:23'),
(3, 3, 'Seat Available in Mirpur', 'Shared seat in bachelor mess, near Metro Station.', 'seat', 'Section 10, Mirpur', 'Dhaka', 3000.00, 80, 1, 1, '2025-07-05', 'uploads/mirpur_seat.jpg', 1, '2025-06-24 16:25:23'),
(4, 5, 'Single Room for Rent in Uttara', '1 room with balcony, 4th floor, bachelor-friendly.', 'room', 'Sector 11, Uttara', 'Dhaka', 7000.00, 100, 1, 1, '2025-07-10', 'uploads/uttara_room.jpg', 1, '2025-06-24 16:25:23'),
(5, 7, 'Shared Room in Mohammadpur', 'Room shared with 1, attached toilet, bachelor mess.', 'seat', 'Mohammadia Housing, Mohammadpur', 'Dhaka', 3500.00, 90, 1, 1, '2025-07-02', 'uploads/mohammadpur.jpg', 0, '2025-06-24 16:25:23'),
(6, 9, 'Bachelor Flat in Bashundhara', '2-bed flat for bachelors, 5 min from NSU.', 'house', 'Block C, Bashundhara R/A', 'Dhaka', 15000.00, 700, 2, 2, '2025-07-15', 'uploads/bashundhara_flat.jpg', 1, '2025-06-24 16:25:23'),
(7, 1, 'Room for Rent in Badda', 'Bachelor room with common bath, ground floor.', 'room', 'Middle Badda', 'Dhaka', 5500.00, 90, 1, 1, '2025-07-01', 'uploads/badda.jpg', 0, '2025-06-24 16:25:23'),
(8, 3, '2-Seats Available in Tejgaon', 'Shared seats with table, clean and airy.', 'seat', 'Tejgaon Industrial Area', 'Dhaka', 2800.00, 70, 1, 1, '2025-07-03', 'uploads/tejgaon.jpg', 1, '2025-06-24 16:25:23'),
(9, 5, 'Bachelor Friendly Room in Banani', '3rd floor, small balcony, single room.', 'room', 'Road #11, Banani', 'Dhaka', 8000.00, 110, 1, 1, '2025-07-12', 'uploads/banani.jpg', 1, '2025-06-24 16:25:23'),
(10, 7, 'Cheap Seat at Farmgate', 'Budget-friendly seat in a shared room for 3.', 'seat', 'Green Road, Farmgate', 'Dhaka', 2500.00, 60, 1, 1, '2025-07-04', 'uploads/farmgate.jpg', 0, '2025-06-24 16:25:23'),
(11, 9, 'Bachelor House in Mohammadpur', 'Entire 2-bedroom unit for bachelors only.', 'house', 'Shyamoli, Mohammadpur', 'Dhaka', 12000.00, 600, 2, 2, '2025-07-08', 'uploads/mohammadpur_house.jpg', 1, '2025-06-24 16:25:23'),
(12, 13, 'Bachelor Studio Flat in Jatrabari', 'Small studio for single bachelor.', 'house', 'Matuail, Jatrabari', 'Dhaka', 9000.00, 400, 1, 1, '2025-07-01', 'uploads/jatrabari_studio.jpg', 1, '2025-06-24 16:25:44'),
(13, 15, 'Single Room in Malibagh', 'Bachelor room with basic facilities.', 'room', 'Taltola, Malibagh', 'Dhaka', 5500.00, 85, 1, 1, '2025-07-05', 'uploads/malibagh_room.jpg', 1, '2025-06-24 16:25:44'),
(14, 17, 'Flat for Bachelors in Kalabagan', 'Entire 2-bed flat suitable for bachelor groups.', 'house', 'Lake Circus, Kalabagan', 'Dhaka', 16000.00, 750, 2, 2, '2025-07-08', 'uploads/kalabagan_flat.jpg', 0, '2025-06-24 16:25:44'),
(15, 13, 'Shared Room in Rampura', 'Seat in a room with common bath.', 'seat', 'West Rampura', 'Dhaka', 3200.00, 70, 1, 1, '2025-07-03', 'uploads/rampura_seat.jpg', 1, '2025-06-24 16:25:44'),
(16, 15, 'Room for Rent at Shahbagh', 'Ground floor room, suitable for bachelors.', 'room', 'Shahbagh, Dhaka University Area', 'Dhaka', 5800.00, 95, 1, 1, '2025-07-02', 'uploads/shahbagh.jpg', 1, '2025-06-24 16:25:44'),
(17, 17, 'Mess Room in Agargaon', 'Shared bachelor mess room.', 'seat', 'Near IDB Bhaban, Agargaon', 'Dhaka', 2700.00, 60, 1, 1, '2025-07-04', 'uploads/agargaon.jpg', 0, '2025-06-24 16:25:44'),
(18, 15, 'Cheap Room in Jatrabari', 'Bachelor room with kitchen access.', 'room', 'Dania, Jatrabari', 'Dhaka', 4800.00, 80, 1, 1, '2025-07-06', 'uploads/jatrabari.jpg', 1, '2025-06-24 16:25:44'),
(19, 17, 'Bachelor Friendly Flat in Shantinagar', '2nd floor, tiled, well-ventilated.', 'house', 'Shantinagar Circle', 'Dhaka', 14000.00, 600, 2, 2, '2025-07-09', 'uploads/shantinagar.jpg', 1, '2025-06-24 16:25:44'),
(20, 15, 'Shared Seat in Banasree', 'Shared seat in quiet bachelor mess.', 'seat', 'Block D, Banasree', 'Dhaka', 2900.00, 75, 1, 1, '2025-07-07', 'uploads/banasree_seat.jpg', 0, '2025-06-24 16:25:44'),
(21, 19, 'Flat for Rent in Khilgaon', 'Bachelor flat with 2 bedrooms.', 'house', 'Tilpapara, Khilgaon', 'Dhaka', 12500.00, 650, 2, 2, '2025-07-11', 'uploads/khilgaon.jpg', 1, '2025-06-24 16:25:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `properties`
--
ALTER TABLE `properties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `properties`
--
ALTER TABLE `properties`
  ADD CONSTRAINT `properties_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
