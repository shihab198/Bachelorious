-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 24, 2025 at 08:20 PM
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
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `user_type` enum('owner','seeker') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `full_name`, `phone`, `user_type`, `created_at`) VALUES
(1, 'admin', 'admin@bachelorious.com', '$2a$12$72n1zEtzx3p8i3zCWZ7J4e2wvbjy5qpPNBkMtHb1D1IF9A9BTrqw6', 'admin', '1234567890', 'owner', '2025-06-24 15:57:48'),
(2, 'testname', 'test1@mail.com', '$2y$10$uTEteIbZnjRRj5/0K0Aw2OschPCe49Q1Hva/iRrUm8oWEguHPAVVK', 'test name', '12111121212', 'owner', '2025-06-24 16:02:08'),
(3, 'rakib123', 'rakib123@gmail.com', '$2a$12$72n1zEtzx3p8i3zCWZ7J4e2wvbjy5qpPNBkMtHb1D1IF9A9BTrqw6', 'Rakib Hasan', '01710000001', 'owner', '2025-06-24 16:25:23'),
(4, 'joy_seeker', 'joy.bd@gmail.com', '$2a$12$72n1zEtzx3p8i3zCWZ7J4e2wvbjy5qpPNBkMtHb1D1IF9A9BTrqw6', 'Joy Ahmed', '01710000002', 'seeker', '2025-06-24 16:25:23'),
(5, 'mamun_owner', 'mamun.owner@gmail.com', '$2a$12$72n1zEtzx3p8i3zCWZ7J4e2wvbjy5qpPNBkMtHb1D1IF9A9BTrqw6', 'Mamun Khan', '01710000003', 'owner', '2025-06-24 16:25:23'),
(6, 'tania_bachelor', 'tania.b@gmail.com', '$2a$12$72n1zEtzx3p8i3zCWZ7J4e2wvbjy5qpPNBkMtHb1D1IF9A9BTrqw6', 'Tania Islam', '01710000004', 'seeker', '2025-06-24 16:25:23'),
(7, 'sumon_property', 'sumon.property@gmail.com', '$2a$12$72n1zEtzx3p8i3zCWZ7J4e2wvbjy5qpPNBkMtHb1D1IF9A9BTrqw6', 'Sumon Rahman', '01710000005', 'owner', '2025-06-24 16:25:23'),
(8, 'akash_renter', 'akash.r@gmail.com', '$2a$12$72n1zEtzx3p8i3zCWZ7J4e2wvbjy5qpPNBkMtHb1D1IF9A9BTrqw6', 'Akash Chowdhury', '01710000006', 'seeker', '2025-06-24 16:25:23'),
(9, 'nabila_home', 'nabila.home@gmail.com', '$2a$12$72n1zEtzx3p8i3zCWZ7J4e2wvbjy5qpPNBkMtHb1D1IF9A9BTrqw6', 'Nabila Sultana', '01710000007', 'owner', '2025-06-24 16:25:23'),
(10, 'farhan123', 'farhan123@gmail.com', '$2a$12$72n1zEtzx3p8i3zCWZ7J4e2wvbjy5qpPNBkMtHb1D1IF9A9BTrqw6', 'Farhan Hossain', '01710000008', 'seeker', '2025-06-24 16:25:23'),
(11, 'sajjad_owner', 'sajjad.o@gmail.com', '$2a$12$72n1zEtzx3p8i3zCWZ7J4e2wvbjy5qpPNBkMtHb1D1IF9A9BTrqw6', 'Sajjad Karim', '01710000009', 'owner', '2025-06-24 16:25:23'),
(12, 'meherun_seeker', 'meherun.s@gmail.com', '$2a$12$72n1zEtzx3p8i3zCWZ7J4e2wvbjy5qpPNBkMtHb1D1IF9A9BTrqw6', 'Meherun Nesa', '01710000010', 'seeker', '2025-06-24 16:25:23'),
(13, 'rony_94', 'rony94@gmail.com', '$2a$12$72n1zEtzx3p8i3zCWZ7J4e2wvbjy5qpPNBkMtHb1D1IF9A9BTrqw6', 'Rony Islam', '01710000011', 'seeker', '2025-06-24 16:25:44'),
(14, 'nasrin_hasan', 'nasrin.hasan@gmail.com', '$2a$12$72n1zEtzx3p8i3zCWZ7J4e2wvbjy5qpPNBkMtHb1D1IF9A9BTrqw6', 'Nasrin Hasan', '01710000012', 'seeker', '2025-06-24 16:25:44'),
(15, 'jamal_landlord', 'jamal.landlord@gmail.com', '$2a$12$72n1zEtzx3p8i3zCWZ7J4e2wvbjy5qpPNBkMtHb1D1IF9A9BTrqw6', 'Jamal Uddin', '01710000013', 'owner', '2025-06-24 16:25:44'),
(16, 'elita_salam', 'elita.salam@gmail.com', '$2a$12$72n1zEtzx3p8i3zCWZ7J4e2wvbjy5qpPNBkMtHb1D1IF9A9BTrqw6', 'Elita Salam', '01710000014', 'seeker', '2025-06-24 16:25:44'),
(17, 'sohan_housing', 'sohan.housing@gmail.com', '$2a$12$72n1zEtzx3p8i3zCWZ7J4e2wvbjy5qpPNBkMtHb1D1IF9A9BTrqw6', 'Sohan Hossain', '01710000015', 'owner', '2025-06-24 16:25:44'),
(18, 'mahi_bachelor', 'mahi.bachelor@gmail.com', '$2a$12$72n1zEtzx3p8i3zCWZ7J4e2wvbjy5qpPNBkMtHb1D1IF9A9BTrqw6', 'Mahi Rahman', '01710000016', 'seeker', '2025-06-24 16:25:44'),
(19, 'sadia_owner', 'sadia.owner@gmail.com', '$2a$12$72n1zEtzx3p8i3zCWZ7J4e2wvbjy5qpPNBkMtHb1D1IF9A9BTrqw6', 'Sadia Zaman', '01710000017', 'owner', '2025-06-24 16:25:44'),
(20, 'fahim_98', 'fahim98@gmail.com', '$2a$12$72n1zEtzx3p8i3zCWZ7J4e2wvbjy5qpPNBkMtHb1D1IF9A9BTrqw6', 'Fahim Kabir', '01710000018', 'seeker', '2025-06-24 16:25:44'),
(21, 'shuvo_rent', 'shuvo.rent@gmail.com', '$2a$12$72n1zEtzx3p8i3zCWZ7J4e2wvbjy5qpPNBkMtHb1D1IF9A9BTrqw6', 'Shuvo Ali', '01710000019', 'owner', '2025-06-24 16:25:44'),
(22, 'rokeya_seeker', 'rokeya.seeker@gmail.com', '$2a$12$72n1zEtzx3p8i3zCWZ7J4e2wvbjy5qpPNBkMtHb1D1IF9A9BTrqw6', 'Rokeya Khatun', '01710000020', 'seeker', '2025-06-24 16:25:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
