-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 27, 2021 at 01:07 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `omnibus_db`
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
  `booked_at` date NOT NULL,
  `booking_status` varchar(50) NOT NULL DEFAULT 'confirmed',
  `reference_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `booking_tbl`
--

INSERT INTO `booking_tbl` (`id`, `companyID`, `terminalID`, `bustripID`, `customerID`, `origin`, `destination`, `duration`, `departure_time`, `number_of_seats`, `fare_amount`, `total_amount`, `payment_status`, `booked_at`, `booking_status`, `reference_id`) VALUES
(28, 13, 4, 104, 16, 'Baguio', 'Dagupan', '2:30', '15:35:00', 2, 150, 300, 'paid', '2021-12-23', 'cancelled', '298d522f'),
(29, 13, 4, 104, 16, 'Baguio', 'Dagupan', '2:30', '15:35:00', 1, 150, 150, 'not paid', '2021-12-23', 'cancelled', '1ab702ef'),
(33, 13, 1, 108, 16, 'Dagupan', 'Baguio', '2:30', '06:00:00', 2, 200, 400, 'not paid', '2021-12-23', 'cancelled', '3bfdd750'),
(34, 13, 4, NULL, 16, 'Baguio', 'Dagupan', '2:30', '07:23:00', 1, 300, 300, 'not paid', '2021-12-24', 'confirmed', '657d4561'),
(35, 13, 1, 105, 16, 'Dagupan', 'Baguio', '2:30', '06:00:00', 1, 201, 201, 'not paid', '2021-12-27', 'confirmed', '6c4fcc61');

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
  `status` int(11) NOT NULL COMMENT '0 - unavailable\r\n1 - available',
  `protocol` varchar(250) NOT NULL DEFAULT 'Off'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `buses`
--

INSERT INTO `buses` (`id`, `companyID`, `terminalID`, `seat_type`, `total_seat`, `bus_model`, `busNumber`, `plate_number`, `status`, `protocol`) VALUES
(7, 13, 1, '2 - 2', 45, 'Airconditioned', 123332, 'asd2123', 0, 'On'),
(12, 13, 1, '3 - 2', 60, 'Public', 9876, 'po231', 0, 'On');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bus_trip`
--

INSERT INTO `bus_trip` (`id`, `companyID`, `assigned_by`, `trip_date`, `operates_from`, `operates_to`, `fare`, `total_seat`, `taken_seat`, `driverID`, `conductorID`, `tripID`, `routeID`, `busID`, `terminalID`) VALUES
(100, 13, 'Mrk Glang', '2021-12-15', '2021-12-14', '2021-12-24', '155', 30, 0, 7, 8, 23, 19, NULL, 4),
(101, 13, 'Mrk Glang', '2021-12-17', '2021-12-14', '2021-12-24', '150', 60, 0, 7, 8, 23, 19, 12, 4),
(102, 13, 'Mrk Glang', '2021-12-20', '2021-12-14', '2021-12-24', '150', 60, 0, 7, 8, 23, 19, 12, 4),
(103, 13, 'Mrk Glang', '2021-12-22', '2021-12-14', '2021-12-24', '150', 60, 0, 7, 8, 23, 19, 12, 4),
(104, 13, 'Mrk Glang', '2021-12-24', '2021-12-14', '2021-12-24', '150', 60, 0, 7, 8, 23, 19, 12, 4),
(105, 13, 'Mrk Glang', '2022-01-03', '2022-01-02', '2022-01-15', '201', 60, 1, 7, 8, 24, 20, 12, 1),
(106, 13, 'Mrk Glang', '2022-01-04', '2022-01-02', '2022-01-15', '200', 45, 0, 7, 8, 24, 20, 7, 1),
(107, 13, 'Mrk Glang', '2022-01-05', '2022-01-02', '2022-01-15', '200', 45, 0, 7, 8, 24, 20, 7, 1),
(108, 13, 'Mrk Glang', '2022-01-08', '2022-01-02', '2022-01-15', '200', 45, 0, 7, 8, 24, 20, 7, 1),
(109, 13, 'Mrk Glang', '2022-01-10', '2022-01-02', '2022-01-15', '200', 45, 1, 7, 8, 24, 20, 7, 1),
(110, 13, 'Mrk Glang', '2022-01-11', '2022-01-02', '2022-01-15', '200', 45, 0, 7, 8, 24, 20, 7, 1);

-- --------------------------------------------------------

--
-- Table structure for table `company_files`
--

CREATE TABLE `company_files` (
  `id` int(11) NOT NULL,
  `companyID` int(11) NOT NULL,
  `file_Name` varchar(255) NOT NULL,
  `size` int(11) NOT NULL,
  `verified` int(11) NOT NULL COMMENT '0 - pending\r\n1 - verified'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `company_files`
--

INSERT INTO `company_files` (`id`, `companyID`, `file_Name`, `size`, `verified`) VALUES
(8, 13, 'GUILANG_MARK_ARJAY_SIA101_ACTIVITY2.pdf', 255910, 1),
(9, 13, 'EyeOfEnder.png', 840, 1),
(10, 13, 'Flutter Module Creation.pdf', 1127100, 1);

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
  `s_pass` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customer_booking_details`
--

INSERT INTO `customer_booking_details` (`id`, `companyID`, `bookingID`, `first_name`, `last_name`, `age`, `gender`, `seat_number`, `valid_ID`, `vaccination_card`, `s_pass`) VALUES
(52, 13, 28, 'Mark', 'Guilang', 21, 'Male', 41, 'EyeOfEnder47.png', 'EyeOfEnder48.png', 'EyeOfEnder49.png'),
(53, 13, 28, 'Markwew', 'Guilangewe', 22, 'Female', 43, 'EyeOfEnder50.png', 'EyeOfEnder51.png', 'EyeOfEnder52.png'),
(54, 13, 29, 'John', 'Guil', 25, 'Male', 59, 'EyeOfEnder53.png', 'EyeOfEnder54.png', 'EyeOfEnder55.png'),
(55, 13, 33, 'Mark', 'Guilang', 22, 'Male', 42, 'EyeOfEnder56.png', 'EyeOfEnder57.png', 'EyeOfEnder58.png'),
(56, 13, 33, 'Makos', 'Guilang', 22, 'Female', 44, 'EyeOfEnder59.png', 'EyeOfEnder60.png', 'EyeOfEnder61.png'),
(57, 13, 34, 'Mark', 'Cruz', 23, 'Male', 28, 'EyeOfEnder62.png', 'EyeOfEnder63.png', 'EyeOfEnder64.png'),
(58, 13, 35, 'Mark', 'Guilang', 21, 'Male', 31, 'EyeOfEnder65.png', 'EyeOfEnder66.png', 'EyeOfEnder67.png');

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
  `status` int(11) NOT NULL COMMENT '0 - unavailable\r\n1 - available\r\n'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `usertype`, `companyID`, `fullname`, `address`, `contactNumber`, `email`, `role`, `password`, `verification_code`, `email_verified_at`, `status`) VALUES
(7, 0, 13, 'Juan Dela Cruz', 'Rosales, Pangasinan', '09292929292', 'der@gmail.com', 'Driver', '', '', NULL, 0),
(8, 0, 13, 'Mrk Glangskie', '123 Plae Ground Stret', '09876543456', 'derk@gmail.com', 'Conductor', '', '', NULL, 0),
(10, 6, 13, 'Mark Guilang', 'Zone1', '09296050070', 'guilangmarkarjay@gmail.com', 'Conductor', '202cb962ac59075b964b07152d234b70', '234171', '2021-12-27 00:57:30', 0);

-- --------------------------------------------------------

--
-- Table structure for table `guidelines`
--

CREATE TABLE `guidelines` (
  `id` int(11) NOT NULL,
  `companyID` int(11) NOT NULL,
  `guideline` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `guidelines`
--

INSERT INTO `guidelines` (`id`, `companyID`, `guideline`) VALUES
(1, 13, 'Wear face mask'),
(18, 13, 'SUbmit valid ID');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `routes`
--

INSERT INTO `routes` (`id`, `pointA`, `pointB`, `duration`, `companyID`, `pointA_terminalID`, `pointB_terminalID`) VALUES
(19, 'Baguio', 'Dagupan', '02:30', 13, 4, 1),
(20, 'Dagupan', 'Baguio', '02:30', 13, 1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `seat_configuration`
--

CREATE TABLE `seat_configuration` (
  `id` int(11) NOT NULL,
  `companyID` int(11) NOT NULL,
  `seat_type` varchar(255) NOT NULL,
  `total_seat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `seat_configuration`
--

INSERT INTO `seat_configuration` (`id`, `companyID`, `seat_type`, `total_seat`) VALUES
(1, 13, '2 - 1', 29),
(2, 13, '2 - 1', 30),
(3, 13, '3 - 2', 60),
(4, 13, '2 - 2', 45),
(5, 13, '3x2', 61);

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
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `terminal`
--

INSERT INTO `terminal` (`id`, `companyID`, `terminal_name`, `street_address`, `city`, `province`, `terminal_connumber`, `tmaster_count`, `status`) VALUES
(1, 13, 'Dagupan terminal', 'Zone1', 'Dagupan', 'Pangasinan', '09876556655', 3, 1),
(4, 13, 'Baguio Terminal', 'Zone1', 'Baguio', 'Benguet', '09876545676', 1, 1);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `trip`
--

INSERT INTO `trip` (`id`, `origin`, `destination`, `duration`, `departure_time`, `assigned_by`, `companyID`, `terminalID`, `routeID`) VALUES
(23, 'Baguio', 'Dagupan', '2:30', '15:35:00', 'Mrk Guilang', 13, 4, 19),
(24, 'Dagupan', 'Baguio', '2:30', '06:00:00', 'Mrk Guilang', 13, 1, 20),
(25, 'Dagupan', 'Baguio', '2:30', '07:00:00', 'Mrk Guilang', 13, 1, 20);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `type`, `count`) VALUES
(1, 'customer', 9),
(3, 'admin', 1),
(4, 'company_terminalMaster', 10),
(5, 'company_admin', 4),
(6, 'company_conductor', 2);

-- --------------------------------------------------------

--
-- Table structure for table `user_admin`
--

CREATE TABLE `user_admin` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_admin`
--

INSERT INTO `user_admin` (`id`, `name`, `email`, `password`) VALUES
(1, 'admin', 'admin@gmail.com', '21232f297a57a5a743894a0e4a801fc3');

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
  `fileName` varchar(255) NOT NULL,
  `verification_code` varchar(255) NOT NULL,
  `email_verified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_customer`
--

INSERT INTO `user_customer` (`id`, `usertype`, `fullname`, `address`, `connumber`, `email`, `birthdate`, `password`, `fileName`, `verification_code`, `email_verified_at`) VALUES
(2, 1, 'Marks Guilangs', 'urasss', '09090909090', 'asdw@gmail.com', '2021-07-10', '202cb962ac59075b964b07152d234b70', '', '', NULL),
(11, 1, 'arkay', 'Rosales', '09123434434', 'arjay@gmail.com', '2021-06-28', '827ccb0eea8a706c4c34a16891f84e7b', '', '', NULL),
(13, 1, 'qwe', 'qwe', '09876543234', 'qwe@gmail.com', '2021-07-28', '76d80224611fc919a5d54f0ff9fba446', '', '', NULL),
(15, 1, 'MarkGuilang', 'Rosales', '09876565656', 'markkk@gmail.com', '2021-07-30', '4297f44b13955235245b2497399d7a93', '', '', NULL),
(16, 1, 'mark', 'Rosales', '09876545678', 'guilangmarkarjay@gmail.com', '1999-11-29', '81dc9bdb52d04dc20036dbd8313ed055', '', '123456', '2021-10-12 20:34:55'),
(17, 1, 'arjay', 'Rosales', '09876543456', 'asd@gmail.com', '2021-08-10', '202cb962ac59075b964b07152d234b70', '', '', NULL),
(18, 1, 'Mark Arjay Guilang', 'Rosales', '09292929292', 'guilamave@gmail.com', '1999-11-29', '202cb962ac59075b964b07152d234b70', '', '226307', '2021-10-13 15:52:07'),
(20, 1, 'Keanu ', 'Urdaneta', '09876545678', 'keanoneil142000@gmail.com', '1999-11-13', '81dc9bdb52d04dc20036dbd8313ed055', '', '166447', '2021-10-13 21:25:57'),
(21, 1, 'Arj', '123232', '09876543456', 'dospizufyu@vusra.com', '2021-11-10', '202cb962ac59075b964b07152d234b70', '', '', NULL);

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
  `fileCount` int(11) NOT NULL,
  `file_verified` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_partner_admin`
--

INSERT INTO `user_partner_admin` (`id`, `usertype`, `companyName`, `companyAddress`, `fullname`, `position`, `contactNumber`, `email`, `password`, `verification_code`, `email_verified_at`, `fileCount`, `file_verified`) VALUES
(11, 5, 'Amyanan Motors', 'Zone1', 'Keano Neil', 'Manager', '09876567890', 'keanoneil142000@gmail.com', '202cb962ac59075b964b07152d234b70', '164021', '2021-10-26 20:25:33', 1, 1),
(13, 5, 'Solid West', 'Rosales, Pangasinan', 'Mrk Glang', 'Manager', '09876543234', 'guilangmarkarjay@gmail.com', '202cb962ac59075b964b07152d234b70', '862594', '2021-11-02 17:46:45', 3, 1);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_partner_tmaster`
--

INSERT INTO `user_partner_tmaster` (`id`, `companyID`, `terminalID`, `usertype`, `fullname`, `connumber`, `email`, `street_address`, `city`, `province`, `password`, `verification_code`, `email_verified_at`, `account_status`) VALUES
(11, 13, 1, 4, 'Mark', '09876543456', 'gorkusekka@vusra.com', '142 ameyz stret', 'Rosales', 'Pangasinan', '', '123263', NULL, '0'),
(20, 13, 1, 4, 'Arjay', '09876553553', 'weraf61252@mykcloud.com', '2321', 'Rosales', 'Pangasinan', '', '350392', NULL, '0'),
(21, 13, 4, 4, 'Mark Guilang', '09876555553', 'guilangmarkarjay@gmail.com', 'Plae', 'Rosales', 'Pangasinan', '81dc9bdb52d04dc20036dbd8313ed055', '243416', '2021-12-18 01:15:43', '0');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `buses`
--
ALTER TABLE `buses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `bus_trip`
--
ALTER TABLE `bus_trip`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT for table `company_files`
--
ALTER TABLE `company_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `customer_booking_details`
--
ALTER TABLE `customer_booking_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `guidelines`
--
ALTER TABLE `guidelines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `routes`
--
ALTER TABLE `routes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `seat_configuration`
--
ALTER TABLE `seat_configuration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `terminal`
--
ALTER TABLE `terminal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `trip`
--
ALTER TABLE `trip`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_admin`
--
ALTER TABLE `user_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_customer`
--
ALTER TABLE `user_customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `user_partner_admin`
--
ALTER TABLE `user_partner_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user_partner_tmaster`
--
ALTER TABLE `user_partner_tmaster`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking_tbl`
--
ALTER TABLE `booking_tbl`
  ADD CONSTRAINT `booking_tbl_ibfk_1` FOREIGN KEY (`companyID`) REFERENCES `user_partner_admin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `booking_tbl_ibfk_2` FOREIGN KEY (`terminalID`) REFERENCES `terminal` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `booking_tbl_ibfk_3` FOREIGN KEY (`customerID`) REFERENCES `user_customer` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `booking_tbl_ibfk_4` FOREIGN KEY (`bustripID`) REFERENCES `bus_trip` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION;

--
-- Constraints for table `buses`
--
ALTER TABLE `buses`
  ADD CONSTRAINT `buses_ibfk_1` FOREIGN KEY (`companyID`) REFERENCES `user_partner_admin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `buses_ibfk_2` FOREIGN KEY (`terminalID`) REFERENCES `terminal` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION;

--
-- Constraints for table `bus_trip`
--
ALTER TABLE `bus_trip`
  ADD CONSTRAINT `bus_trip_ibfk_1` FOREIGN KEY (`companyID`) REFERENCES `user_partner_admin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
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
  ADD CONSTRAINT `company_files_ibfk_1` FOREIGN KEY (`companyID`) REFERENCES `user_partner_admin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `customer_booking_details`
--
ALTER TABLE `customer_booking_details`
  ADD CONSTRAINT `customer_booking_details_ibfk_1` FOREIGN KEY (`companyID`) REFERENCES `user_partner_admin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `customer_booking_details_ibfk_2` FOREIGN KEY (`bookingID`) REFERENCES `booking_tbl` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`companyID`) REFERENCES `user_partner_admin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `guidelines`
--
ALTER TABLE `guidelines`
  ADD CONSTRAINT `guidelines_ibfk_1` FOREIGN KEY (`companyID`) REFERENCES `user_partner_admin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `routes`
--
ALTER TABLE `routes`
  ADD CONSTRAINT `routes_ibfk_1` FOREIGN KEY (`companyID`) REFERENCES `user_partner_admin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `routes_ibfk_2` FOREIGN KEY (`pointA_terminalID`) REFERENCES `terminal` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `seat_configuration`
--
ALTER TABLE `seat_configuration`
  ADD CONSTRAINT `seat_configuration_ibfk_1` FOREIGN KEY (`companyID`) REFERENCES `user_partner_admin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `terminal`
--
ALTER TABLE `terminal`
  ADD CONSTRAINT `terminal_ibfk_1` FOREIGN KEY (`companyID`) REFERENCES `user_partner_admin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `trip`
--
ALTER TABLE `trip`
  ADD CONSTRAINT `trip_ibfk_1` FOREIGN KEY (`companyID`) REFERENCES `user_partner_admin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `trip_ibfk_2` FOREIGN KEY (`terminalID`) REFERENCES `terminal` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `trip_ibfk_3` FOREIGN KEY (`routeID`) REFERENCES `routes` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `user_partner_tmaster`
--
ALTER TABLE `user_partner_tmaster`
  ADD CONSTRAINT `user_partner_tmaster_ibfk_1` FOREIGN KEY (`companyID`) REFERENCES `user_partner_admin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_partner_tmaster_ibfk_2` FOREIGN KEY (`terminalID`) REFERENCES `terminal` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
