-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 23, 2026 at 08:14 PM
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
-- Database: `qlpt`
--

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `fullname`, `email`, `subject`, `message`, `created_at`, `user_id`) VALUES
(3, 'Nguyễn Thị Thanh', 'Th@gmail.com', 'hi', 'Xin chào', '2026-05-23 17:58:28', 9);

-- --------------------------------------------------------

--
-- Table structure for table `districts`
--

CREATE TABLE `districts` (
  `ID` int(10) NOT NULL,
  `Name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `districts`
--

INSERT INTO `districts` (`ID`, `Name`) VALUES
(1, 'Phường Bến Thủy'),
(2, 'Phường Trung Đô'),
(3, 'Phường Trường Thi'),
(4, 'Phường Hưng Dũng'),
(5, 'Phường Hồng Sơn');

-- --------------------------------------------------------

--
-- Table structure for table `motel`
--

CREATE TABLE `motel` (
  `ID` int(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `area` int(11) DEFAULT NULL,
  `count_view` int(11) DEFAULT 0,
  `address` varchar(255) DEFAULT NULL,
  `lating` varchar(255) DEFAULT NULL,
  `images` varchar(255) DEFAULT NULL,
  `user_id` int(10) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `district_id` int(11) DEFAULT NULL,
  `utilities` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `phone` varchar(255) DEFAULT NULL,
  `approve` int(11) DEFAULT 0,
  `status` tinyint(4) DEFAULT 0 COMMENT '0: Con trong, 1: Da thue'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `motel`
--

INSERT INTO `motel` (`ID`, `title`, `description`, `price`, `area`, `count_view`, `address`, `lating`, `images`, `user_id`, `category_id`, `district_id`, `utilities`, `created_at`, `phone`, `approve`, `status`) VALUES
(8, 'Căn hộ mini đầy đủ tiện ích', NULL, 3000000, 50, 0, NULL, NULL, 'room_1779557055_b81462fd.jpg', 7, 3, 2, 'Có Wifi, Điều hòa, Chỗ để xe', '2026-05-23 17:24:15', NULL, 1, 0),
(9, 'Chung cư đầy đủ tiện nghi', NULL, 4000000, 30, 0, NULL, NULL, 'room_1779557106_525f1e81.jpg', 7, 3, 3, 'Có Wifi, Điều hòa, Chỗ để xe, Khép kín', '2026-05-23 17:25:06', NULL, 1, 0),
(10, 'Phòng khéo kín thoải mái', NULL, 2000000, 19, 0, NULL, NULL, 'room_1779557161_60a7e9b9.jpg', 7, 1, 5, 'Có Wifi, Chỗ để xe, Khép kín', '2026-05-23 17:26:01', NULL, 1, 0),
(11, 'Nhà nguyên căn tiện ích xanh', NULL, 7000000, 60, 2, NULL, NULL, 'room_1779557244_8fabadee.jpg', 7, 2, 4, 'Có Wifi, Điều hòa, Chỗ để xe', '2026-05-23 17:27:24', NULL, 1, 0),
(12, 'Phòng khép kín đầy đủ tiện nghi', 'có wf', 1200000, 30, 0, '', '', 'room_1779559243_1324ddf5.jpg', 10, 1, 1, 'Có Wifi', '2026-05-23 18:00:43', '0', 1, 0),
(13, 'Chung cư mini', '', 3000000, 30, 0, '18/Nguyễn Văn Trỗi', '', 'room_1779559243_1324ddf5.jpg', 9, 1, 1, 'Có Wifi, Điều hòa, Khép kín', '2026-05-23 18:01:58', '357362696', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `ID` int(10) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Role` int(11) DEFAULT 0,
  `Phone` varchar(255) DEFAULT NULL,
  `Avatar` varchar(255) DEFAULT NULL,
  `reset_done` int(1) DEFAULT 0,
  `forgot_password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`ID`, `Name`, `Username`, `Email`, `Password`, `Role`, `Phone`, `Avatar`, `reset_done`, `forgot_password`) VALUES
(1, 'Trần Thị Thanh', 'thanhadmin', 'thanh@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 2, '0987654321', 'uploads/avatars/u1_aa80f9d73efec5f3.jpg', 0, NULL),
(2, 'Trần Thị Ánh', 'anhuser', 'anh@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 0, '0912345678', 'uploads/avatars/u2_260eb8c33be73a80.jpg', 0, NULL),
(7, 'Hồ Thảo linh', 'Linh', 'tlinh110905@gmail.com', '7f1fc3dabeea16fea60c42665b3b33bf', 1, '0352516343', NULL, 0, NULL),
(8, 'Trần Thị Lan Anh', 'Lanh', 'Lanh@gmail.com', '80eeb5345bbf5d97f03f91c96d24a49f', 0, '0352516343', NULL, 0, '1'),
(9, 'Nguyễn Thị Thanh', 'tha', 'Th@gmail.com', 'f0070dd5f8ee4fc57840867cf2bc0d80', 0, '048547666', 'uploads/avatars/u9_7727fc64308b14b0.jpg', 0, NULL),
(10, 'Trần Thị Thủy', 'Thuy', 'Thuy@gmail.com', '353519750965136863f0234c688b7ef7', 1, '03857362', 'uploads/avatars/u10_9eba800c250e14da.jpg', 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `districts`
--
ALTER TABLE `districts`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `motel`
--
ALTER TABLE `motel`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `district_id` (`district_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `districts`
--
ALTER TABLE `districts`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `motel`
--
ALTER TABLE `motel`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `motel`
--
ALTER TABLE `motel`
  ADD CONSTRAINT `motel_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`ID`),
  ADD CONSTRAINT `motel_ibfk_2` FOREIGN KEY (`district_id`) REFERENCES `districts` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
