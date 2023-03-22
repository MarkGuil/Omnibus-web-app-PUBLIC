-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 22, 2023 at 10:40 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `omni`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking_tbl`
--

CREATE TABLE `booking_tbl` (
  `id` int(11) NOT NULL,
  `companyID` int(11) NOT NULL,
  `terminalID` int(11) DEFAULT NULL,
  `bustripID` int(11) DEFAULT NULL,
  `customerID` int(11) DEFAULT NULL,
  `origin` varchar(255) NOT NULL,
  `destination` varchar(255) NOT NULL,
  `duration` varchar(20) NOT NULL,
  `departure_time` time NOT NULL,
  `number_of_seats` int(11) NOT NULL,
  `fare_amount` int(11) NOT NULL,
  `total_amount` int(11) NOT NULL,
  `payment_status` varchar(20) NOT NULL,
  `payer_email` varchar(255) DEFAULT NULL,
  `payment_used` varchar(255) DEFAULT NULL,
  `booked_at` date NOT NULL,
  `booking_status` varchar(50) NOT NULL DEFAULT 'confirmed',
  `reference_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `buses`
--

CREATE TABLE `buses` (
  `id` int(11) NOT NULL,
  `companyID` int(11) NOT NULL,
  `terminalID` int(11) DEFAULT NULL,
  `seat_type` varchar(20) NOT NULL,
  `total_seat` int(11) NOT NULL,
  `bus_model` varchar(255) NOT NULL,
  `busNumber` int(11) NOT NULL,
  `plate_number` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '0 - unavailable1 - available',
  `protocol` varchar(250) NOT NULL DEFAULT 'Off'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bus_trip`
--

CREATE TABLE `bus_trip` (
  `id` int(11) NOT NULL,
  `companyID` int(11) NOT NULL,
  `assigned_by` varchar(100) NOT NULL,
  `trip_date` date NOT NULL,
  `operates_from` date DEFAULT NULL,
  `operates_to` date DEFAULT NULL,
  `fare` varchar(11) NOT NULL,
  `total_seat` int(11) NOT NULL,
  `taken_seat` int(11) NOT NULL DEFAULT 0,
  `driverID` int(11) DEFAULT NULL,
  `conductorID` int(11) DEFAULT NULL,
  `tripID` int(11) DEFAULT NULL,
  `routeID` int(11) DEFAULT NULL,
  `busID` int(11) DEFAULT NULL,
  `terminalID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `company_files`
--

CREATE TABLE `company_files` (
  `id` int(11) NOT NULL,
  `companyID` int(11) NOT NULL,
  `file_Name` varchar(255) NOT NULL,
  `size` int(11) NOT NULL,
  `verified` int(11) NOT NULL DEFAULT 0 COMMENT '0 - pending1 - verified'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_booking_details`
--

CREATE TABLE `customer_booking_details` (
  `id` int(11) NOT NULL,
  `companyID` int(11) NOT NULL,
  `bookingID` int(11) DEFAULT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `age` int(11) DEFAULT NULL,
  `gender` varchar(20) NOT NULL,
  `seat_number` int(11) NOT NULL,
  `valid_ID` varchar(255) NOT NULL,
  `vaccination_card` varchar(255) NOT NULL,
  `s_pass` varchar(255) NOT NULL,
  `file_a_status` varchar(255) NOT NULL DEFAULT 'pending',
  `file_b_status` varchar(255) NOT NULL DEFAULT 'pending',
  `file_c_status` varchar(255) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `usertype` int(11) NOT NULL,
  `companyID` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `contactNumber` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `verification_code` varchar(245) NOT NULL,
  `email_verified_at` datetime DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '0 - unavailable1 - available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guidelines`
--

CREATE TABLE `guidelines` (
  `id` int(11) NOT NULL,
  `companyID` int(11) NOT NULL,
  `guideline` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `in_between`
--

CREATE TABLE `in_between` (
  `id` int(11) NOT NULL,
  `companyID` int(11) DEFAULT NULL,
  `routeID` int(11) DEFAULT NULL,
  `origin` varchar(255) NOT NULL,
  `in_between_address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_bussiness_info`
--

CREATE TABLE `payment_bussiness_info` (
  `id` int(11) NOT NULL,
  `companyID` int(11) DEFAULT NULL,
  `paypal_email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `routes`
--

CREATE TABLE `routes` (
  `id` int(11) NOT NULL,
  `pointA` varchar(500) DEFAULT NULL,
  `pointB` varchar(500) DEFAULT NULL,
  `duration` varchar(255) NOT NULL,
  `companyID` int(11) DEFAULT NULL,
  `pointA_terminalID` int(11) DEFAULT NULL,
  `pointB_terminalID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seat_configuration`
--

CREATE TABLE `seat_configuration` (
  `id` int(11) NOT NULL,
  `companyID` int(11) NOT NULL,
  `seat_type` varchar(255) NOT NULL,
  `total_seat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `terminal`
--

CREATE TABLE `terminal` (
  `id` int(11) NOT NULL,
  `companyID` int(11) NOT NULL,
  `terminal_name` varchar(255) NOT NULL,
  `street_address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `province` varchar(255) NOT NULL,
  `terminal_connumber` varchar(255) NOT NULL,
  `tmaster_count` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trip`
--

CREATE TABLE `trip` (
  `id` int(11) NOT NULL,
  `origin` varchar(25) NOT NULL,
  `destination` varchar(255) NOT NULL,
  `duration` varchar(255) NOT NULL,
  `departure_time` time NOT NULL,
  `assigned_by` varchar(50) NOT NULL,
  `companyID` int(11) NOT NULL,
  `terminalID` int(11) NOT NULL,
  `routeID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `type`, `count`) VALUES
(1, 'customer', 18),
(3, 'admin', 1),
(4, 'company_terminalMaster', 16),
(5, 'company_admin', 15),
(6, 'company_conductor', 16);

-- --------------------------------------------------------

--
-- Table structure for table `user_admin`
--

CREATE TABLE `user_admin` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_customer`
--

CREATE TABLE `user_customer` (
  `id` int(11) NOT NULL,
  `usertype` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `connumber` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `birthdate` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `verification_code` varchar(255) NOT NULL,
  `email_verified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_partner_admin`
--

CREATE TABLE `user_partner_admin` (
  `id` int(11) NOT NULL,
  `usertype` int(11) NOT NULL,
  `companyName` varchar(255) NOT NULL,
  `companyAddress` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `contactNumber` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `verification_code` varchar(255) NOT NULL,
  `email_verified_at` datetime DEFAULT NULL,
  `fileCount` int(11) NOT NULL DEFAULT 0,
  `file_verified` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_partner_tmaster`
--

CREATE TABLE `user_partner_tmaster` (
  `id` int(11) NOT NULL,
  `companyID` int(11) NOT NULL,
  `terminalID` int(11) DEFAULT NULL,
  `usertype` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `connumber` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `street_address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `province` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `verification_code` varchar(255) NOT NULL,
  `email_verified_at` datetime DEFAULT NULL,
  `account_status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking_tbl`
--
ALTER TABLE `booking_tbl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_tbl_ibfk_1` (`companyID`),
  ADD KEY `booking_tbl_ibfk_2` (`terminalID`),
  ADD KEY `booking_tbl_ibfk_3` (`customerID`),
  ADD KEY `booking_tbl_ibfk_4` (`bustripID`);

--
-- Indexes for table `buses`
--
ALTER TABLE `buses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `companyID` (`companyID`),
  ADD KEY `buses_ibfk_2` (`terminalID`);

--
-- Indexes for table `bus_trip`
--
ALTER TABLE `bus_trip`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bus_trip_ibfk_3` (`busID`),
  ADD KEY `bus_trip_ibfk_4` (`conductorID`),
  ADD KEY `bus_trip_ibfk_5` (`driverID`),
  ADD KEY `bus_trip_ibfk_1` (`companyID`),
  ADD KEY `bus_trip_ibfk_6` (`tripID`),
  ADD KEY `bus_trip_ibfk_2` (`terminalID`),
  ADD KEY `bus_trip_ibfk_7` (`routeID`);

--
-- Indexes for table `company_files`
--
ALTER TABLE `company_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `companyID` (`companyID`);

--
-- Indexes for table `customer_booking_details`
--
ALTER TABLE `customer_booking_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bookingID` (`bookingID`),
  ADD KEY `companyID` (`companyID`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `companyID` (`companyID`);

--
-- Indexes for table `guidelines`
--
ALTER TABLE `guidelines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `companyID` (`companyID`);

--
-- Indexes for table `in_between`
--
ALTER TABLE `in_between`
  ADD PRIMARY KEY (`id`),
  ADD KEY `routeID` (`routeID`);

--
-- Indexes for table `payment_bussiness_info`
--
ALTER TABLE `payment_bussiness_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `routes`
--
ALTER TABLE `routes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `companyID` (`companyID`),
  ADD KEY `routes_ibfk_2` (`pointA_terminalID`);

--
-- Indexes for table `seat_configuration`
--
ALTER TABLE `seat_configuration`
  ADD PRIMARY KEY (`id`),
  ADD KEY `companyID` (`companyID`);

--
-- Indexes for table `terminal`
--
ALTER TABLE `terminal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `companyID` (`companyID`);

--
-- Indexes for table `trip`
--
ALTER TABLE `trip`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trip_ibfk_1` (`companyID`),
  ADD KEY `trip_ibfk_3` (`routeID`),
  ADD KEY `trip_ibfk_2` (`terminalID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_admin`
--
ALTER TABLE `user_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_customer`
--
ALTER TABLE `user_customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_partner_admin`
--
ALTER TABLE `user_partner_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_partner_tmaster`
--
ALTER TABLE `user_partner_tmaster`
  ADD PRIMARY KEY (`id`),
  ADD KEY `companyID` (`companyID`),
  ADD KEY `terminalID` (`terminalID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking_tbl`
--
ALTER TABLE `booking_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `buses`
--
ALTER TABLE `buses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bus_trip`
--
ALTER TABLE `bus_trip`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `company_files`
--
ALTER TABLE `company_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_booking_details`
--
ALTER TABLE `customer_booking_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guidelines`
--
ALTER TABLE `guidelines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `in_between`
--
ALTER TABLE `in_between`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_bussiness_info`
--
ALTER TABLE `payment_bussiness_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `routes`
--
ALTER TABLE `routes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `seat_configuration`
--
ALTER TABLE `seat_configuration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `terminal`
--
ALTER TABLE `terminal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trip`
--
ALTER TABLE `trip`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_admin`
--
ALTER TABLE `user_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_customer`
--
ALTER TABLE `user_customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_partner_admin`
--
ALTER TABLE `user_partner_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_partner_tmaster`
--
ALTER TABLE `user_partner_tmaster`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking_tbl`
--
ALTER TABLE `booking_tbl`
  ADD CONSTRAINT `booking_tbl_ibfk_1` FOREIGN KEY (`companyID`) REFERENCES `user_partner_admin` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `booking_tbl_ibfk_2` FOREIGN KEY (`terminalID`) REFERENCES `terminal` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `booking_tbl_ibfk_3` FOREIGN KEY (`customerID`) REFERENCES `user_customer` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `booking_tbl_ibfk_4` FOREIGN KEY (`bustripID`) REFERENCES `bus_trip` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION;

--
-- Constraints for table `buses`
--
ALTER TABLE `buses`
  ADD CONSTRAINT `buses_ibfk_1` FOREIGN KEY (`companyID`) REFERENCES `user_partner_admin` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `buses_ibfk_2` FOREIGN KEY (`terminalID`) REFERENCES `terminal` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION;

--
-- Constraints for table `bus_trip`
--
ALTER TABLE `bus_trip`
  ADD CONSTRAINT `bus_trip_ibfk_1` FOREIGN KEY (`companyID`) REFERENCES `user_partner_admin` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `bus_trip_ibfk_2` FOREIGN KEY (`terminalID`) REFERENCES `terminal` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `bus_trip_ibfk_3` FOREIGN KEY (`busID`) REFERENCES `buses` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `bus_trip_ibfk_4` FOREIGN KEY (`conductorID`) REFERENCES `employees` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `bus_trip_ibfk_5` FOREIGN KEY (`driverID`) REFERENCES `employees` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `bus_trip_ibfk_6` FOREIGN KEY (`tripID`) REFERENCES `trip` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `bus_trip_ibfk_7` FOREIGN KEY (`routeID`) REFERENCES `routes` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `company_files`
--
ALTER TABLE `company_files`
  ADD CONSTRAINT `company_files_ibfk_1` FOREIGN KEY (`companyID`) REFERENCES `user_partner_admin` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `customer_booking_details`
--
ALTER TABLE `customer_booking_details`
  ADD CONSTRAINT `customer_booking_details_ibfk_1` FOREIGN KEY (`companyID`) REFERENCES `user_partner_admin` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `customer_booking_details_ibfk_2` FOREIGN KEY (`bookingID`) REFERENCES `booking_tbl` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`companyID`) REFERENCES `user_partner_admin` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `guidelines`
--
ALTER TABLE `guidelines`
  ADD CONSTRAINT `guidelines_ibfk_1` FOREIGN KEY (`companyID`) REFERENCES `user_partner_admin` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `in_between`
--
ALTER TABLE `in_between`
  ADD CONSTRAINT `in_between_ibfk_1` FOREIGN KEY (`routeID`) REFERENCES `routes` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `routes`
--
ALTER TABLE `routes`
  ADD CONSTRAINT `routes_ibfk_1` FOREIGN KEY (`companyID`) REFERENCES `user_partner_admin` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `routes_ibfk_2` FOREIGN KEY (`pointA_terminalID`) REFERENCES `terminal` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `seat_configuration`
--
ALTER TABLE `seat_configuration`
  ADD CONSTRAINT `seat_configuration_ibfk_1` FOREIGN KEY (`companyID`) REFERENCES `user_partner_admin` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `terminal`
--
ALTER TABLE `terminal`
  ADD CONSTRAINT `terminal_ibfk_1` FOREIGN KEY (`companyID`) REFERENCES `user_partner_admin` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `trip`
--
ALTER TABLE `trip`
  ADD CONSTRAINT `trip_ibfk_1` FOREIGN KEY (`companyID`) REFERENCES `user_partner_admin` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `trip_ibfk_2` FOREIGN KEY (`terminalID`) REFERENCES `terminal` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `trip_ibfk_3` FOREIGN KEY (`routeID`) REFERENCES `routes` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `user_partner_tmaster`
--
ALTER TABLE `user_partner_tmaster`
  ADD CONSTRAINT `user_partner_tmaster_ibfk_1` FOREIGN KEY (`companyID`) REFERENCES `user_partner_admin` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `user_partner_tmaster_ibfk_2` FOREIGN KEY (`terminalID`) REFERENCES `terminal` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
