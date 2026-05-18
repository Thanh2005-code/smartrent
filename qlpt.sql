-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 18, 2026 lúc 09:44 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `qlpt`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `districts`
--

CREATE TABLE `districts` (
  `ID` int(10) NOT NULL,
  `Name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `districts`
--

INSERT INTO `districts` (`ID`, `Name`) VALUES
(1, 'Phường Bến Thủy'),
(2, 'Phường Trung Đô'),
(3, 'Phường Trường Thi'),
(4, 'Phường Hưng Dũng'),
(5, 'Phường Hồng Sơn');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `motel`
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
-- Đang đổ dữ liệu cho bảng `motel`
--

INSERT INTO `motel` (`ID`, `title`, `description`, `price`, `area`, `count_view`, `address`, `lating`, `images`, `user_id`, `category_id`, `district_id`, `utilities`, `created_at`, `phone`, `approve`, `status`) VALUES
(1, 'Phòng trọ khép kín gần ĐH Vinh', 'Phòng rộng rãi, an ninh tốt, cách cổng chính ĐH Vinh 200m.', 1500000, 20, 151, 'Số 10 Bạch Liêu', NULL, 'phong1.jpg', 1, 1, 1, 'Wifi, Điều hòa, Nóng lạnh', '2026-05-15 17:28:43', '0987654321', 1, 0),
(2, 'Chung cư mini cao cấp', 'Full nội thất, chỉ việc xách vali vào ở, giờ giấc tự do.', 3500000, 35, 303, 'Số 5 Nguyễn Du', NULL, 'phong2.jpg', 2, 2, 3, 'Giường, Tủ, Máy giặt, Điều hòa', '2026-05-15 17:28:43', '0912345678', 1, 0),
(3, 'Nhà nguyên căn hẻm ô tô', 'Thích hợp ở ghép nhóm 4-5 người, điện nước giá dân.', 5000000, 80, 51, 'Ngõ 20 Phượng Hoàng', NULL, 'phong3.jpg', 3, 3, 2, 'Bếp, Sân phơi, Chỗ để xe', '2026-05-15 17:28:43', '0933334444', 1, 0),
(4, 'Phòng trọ giá rẻ cho sinh viên', 'Khu trọ an ninh, chủ nhà thân thiện, không chung chủ.', 1200000, 15, 82, 'Số 8 Phong Đình Cảng', NULL, 'phong4.jpg', 1, 1, 4, 'Wifi, Giường cá nhân', '2026-05-15 17:28:43', '0987654321', 1, 0),
(5, 'Căn hộ Studio hiện đại', 'Mới xây, thiết kế hiện đại, view đẹp. Đang chờ Admin duyệt.', 2500000, 25, 20, 'Số 12 Lê Lợi', NULL, 'phong5.jpg', 4, 2, 5, 'Full đồ, Thang máy, Khóa vân tay', '2026-05-15 17:28:43', '0999888777', 0, 0),
(6, 'Phòng khép kín đầy đủ tiện nghi', NULL, 2000000, 40, 0, NULL, NULL, 'room_1779130857_de9bec84.jpg', 6, 1, 3, 'Có Wifi, Điều hòa, Khép kín', '2026-05-18 19:00:57', NULL, 0, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user`
--

CREATE TABLE `user` (
  `ID` int(10) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Role` int(11) DEFAULT 0,
  `Phone` varchar(255) DEFAULT NULL,
  `Avatar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `user`
--

INSERT INTO `user` (`ID`, `Name`, `Username`, `Email`, `Password`, `Role`, `Phone`, `Avatar`) VALUES
(1, 'Trần Thị Thanh', 'thanhadmin', 'thanh@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 2, '0987654321', 'avatar_thanh.jpg'),
(2, 'Trần Thị Ánh', 'anhuser', 'anh@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 0, '0912345678', 'uploads/avatars/u2_260eb8c33be73a80.jpg'),
(3, 'Trần Thị Thùy', 'thuyuser', 'thuy@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 0, '0933334444', 'avatar_thuy.jpg'),
(4, 'Vương Đình Quang', 'quangvd', 'quangvd@vinhuni.edu.vn', 'e10adc3949ba59abbe56e057f20f883e', 1, '0999888777', 'avatar_quang.jpg'),
(5, 'Nguyễn Văn Khách', 'khachthue', 'khach@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 0, '0909090909', 'avatar_khach.jpg'),
(6, 'Nguyễn Thị Thủy', 'thuy', 'thanhthuyyyyyy194@gmail.com', 'f0070dd5f8ee4fc57840867cf2bc0d80', 1, '0352516343', NULL);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `districts`
--
ALTER TABLE `districts`
  ADD PRIMARY KEY (`ID`);

--
-- Chỉ mục cho bảng `motel`
--
ALTER TABLE `motel`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `district_id` (`district_id`);

--
-- Chỉ mục cho bảng `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `districts`
--
ALTER TABLE `districts`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `motel`
--
ALTER TABLE `motel`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `user`
--
ALTER TABLE `user`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `motel`
--
ALTER TABLE `motel`
  ADD CONSTRAINT `motel_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`ID`),
  ADD CONSTRAINT `motel_ibfk_2` FOREIGN KEY (`district_id`) REFERENCES `districts` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
