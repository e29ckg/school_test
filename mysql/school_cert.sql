-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 05, 2023 at 05:40 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `school_cert`
--

-- --------------------------------------------------------

--
-- Table structure for table `quiz`
--

CREATE TABLE `quiz` (
  `q_id` int(11) NOT NULL,
  `q_name` varchar(255) NOT NULL,
  `q_num` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `quiz`
--

INSERT INTO `quiz` (`q_id`, `q_name`, `q_num`) VALUES
(6, 'ข้อสอบหลัก', 20);

-- --------------------------------------------------------

--
-- Table structure for table `quiz_anser`
--

CREATE TABLE `quiz_anser` (
  `qa_id` int(11) NOT NULL,
  `sch_id` int(11) NOT NULL,
  `q_id` int(11) NOT NULL,
  `q_no` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schools`
--

CREATE TABLE `schools` (
  `sch_id` int(11) NOT NULL,
  `sch_no` int(11) DEFAULT NULL,
  `sch_name` varchar(255) DEFAULT NULL,
  `award` varchar(255) DEFAULT NULL,
  `pic` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `schools`
--

INSERT INTO `schools` (`sch_id`, `sch_no`, `sch_name`, `award`, `pic`) VALUES
(1, 1, 'โรงเรียนประจวบวิทยาลัย', 'รอผล', 'school_1_101543.jpg'),
(2, 2, 'โรงเรียนทับสะแกวิทยา', 'รอผล', 'school_2_101636.jpg'),
(4, 3, 'โรงเรียนกุยบุรีวิทยา', 'รอผล', 'school_4_101714.png'),
(5, 4, 'โรงเรียนอ่าวน้อยวิทยานิคม', 'รอผล', 'school_5_101841.png'),
(6, 5, 'โรงเรียนอรุณวิทยา', 'รอผล', 'school_6_101925.jpg'),
(7, 7, 'โรงเรียนชัยเกษมวิทยา', 'รอผล', 'school_7_102004.png'),
(8, 8, 'โรงเรียนบางสะพานน้อยวิทยาคม', 'รอผล', 'school_8_102054.jpg'),
(9, 9, 'โรงเรียนยางชุมวิทยา', 'รอผล', 'school_9_102217.jpg'),
(10, 10, 'โรงเรียนห้วยยางวิทยา', 'รอผล', 'school_10_102311.jpg'),
(11, 11, 'โรงเรียนบางสะพานวิทยา', 'รอผล', 'school_11_102349.png');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `std_id` int(11) NOT NULL,
  `sch_id` int(11) DEFAULT NULL,
  `std_name` varchar(255) DEFAULT NULL,
  `std_st` varchar(255) DEFAULT NULL,
  `std_no` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`std_id`, `sch_id`, `std_name`, `std_st`, `std_no`) VALUES
(9, 1, 'นายพงศิจิตต์ อินทราพงษ์', 'อาจารย์', 3),
(10, 2, 'นายไกรวิชญ์  สุธาพจน์', 'ผู้เข้าแข่งขัน', 1),
(11, 1, 'นางสาวพรลภัส ชูพินิจสกุลวงค์', 'ผู้เข้าแข่งขัน', 2),
(12, 1, 'นายธนวินท์ ทิม ชูลซ์', 'ผู้เข้าแข่งขัน', 1),
(13, 2, 'นางสาวณัฐณิชา  งามเยี่ยม', 'ผู้เข้าแข่งขัน', 2),
(14, 2, 'นางสาวพิชญาภา  อังสวัสดิ์', 'อาจารย์', 3),
(15, 2, 'ว่าที่ร้อยตรีหญิงนภัทร์  ตรีสุวรรณ', 'อาจารย์', 4),
(16, 4, 'เด็กหญิงสุภิชญา  สมศิริ', 'ผู้เข้าแข่งขัน', 1),
(17, 4, 'เด็กหญิงฐิตาภรณ์ แซ่พู', 'ผู้เข้าแข่งขัน', 2),
(18, 4, 'นายประมินทร์  เสพธรรม', 'อาจารย์', 3),
(19, 4, 'เด็กหญิงชาลิณี  แสงรุ้ง', 'อาจารย์', 4),
(20, 5, 'นายวันชัย แซ่ลี้', 'ผู้เข้าแข่งขัน', 1),
(21, 5, 'นายนันทิพัฒน์ สกุลลิ้ม', 'ผู้เข้าแข่งขัน', 2),
(22, 5, 'นางสาวสุกัญญา อยู่คำ', 'อาจารย์', 3),
(23, 5, 'นางสาวจ๊ะเอ๋', 'อาจารย์', 4),
(24, 6, 'นายปาณัสม์ ชนะ', 'ผู้เข้าแข่งขัน', 1),
(25, 6, 'นางสาวกุลนิษฐ์ แรงกสิกรณ์', 'ผู้เข้าแข่งขัน', 2),
(26, 6, 'นางสาวธัญญาภรณ์ ใหม่คามิ', 'อาจารย์', 3),
(27, 6, 'นางสาวกนกวรรณ ทับปิง ', 'อาจารย์', 4),
(28, 7, 'นายเจษฎาภรณ์ หวานทอง', 'ผู้เข้าแข่งขัน', 1),
(29, 7, 'นายศุภกร เพื่อมเสม', 'ผู้เข้าแข่งขัน', 2),
(30, 7, 'นางสาววิมล รอดสุกา', 'อาจารย์', 3),
(31, 8, 'นายชัยวัฒน์  ภากร', 'ผู้เข้าแข่งขัน', 1),
(32, 8, 'นายธนกฤต  ล้อมน้อย', 'ผู้เข้าแข่งขัน', 2),
(33, 8, 'นายกิตติพันธ์  บุตรเจียมใจ', 'อาจารย์', 3),
(34, 8, 'นางสาวสรัลชนา  ทองล่อง', 'อาจารย์', 4),
(35, 9, 'นายธนภัทร  เกิดพร้อม', 'ผู้เข้าแข่งขัน', 1),
(36, 9, 'นางสาวชุติกาญจน์  ร่วมชาติ', 'ผู้เข้าแข่งขัน', 2),
(37, 9, 'ว่าที่ร้อยตรี พงษ์สิงห์  ดวงนิราส', 'อาจารย์', 3),
(38, 10, 'นางสาววรัสญา พูลเงิน', 'ผู้เข้าแข่งขัน', 1),
(39, 10, 'นางสาวสุณิษา ทอดสนิท', 'ผู้เข้าแข่งขัน', 2),
(40, 10, 'นายสมภพ อุสาหะ ', 'อาจารย์', 3),
(41, 11, 'นางสาวจีรพร สุวรรณหกาญจน์', 'ผู้เข้าแข่งขัน', 1),
(42, 11, 'นางสาวอุษณิษา เมฆพันธ์', 'ผู้เข้าแข่งขัน', 2),
(43, 11, 'นางสาวฌัชฌา พลีไพล', 'อาจารย์', 3),
(44, 11, 'นางสาวธีราภรณ์ จันทร์แฝก', 'อาจารย์', 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `quiz`
--
ALTER TABLE `quiz`
  ADD PRIMARY KEY (`q_id`);

--
-- Indexes for table `quiz_anser`
--
ALTER TABLE `quiz_anser`
  ADD PRIMARY KEY (`qa_id`);

--
-- Indexes for table `schools`
--
ALTER TABLE `schools`
  ADD PRIMARY KEY (`sch_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`std_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `quiz`
--
ALTER TABLE `quiz`
  MODIFY `q_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `quiz_anser`
--
ALTER TABLE `quiz_anser`
  MODIFY `qa_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `schools`
--
ALTER TABLE `schools`
  MODIFY `sch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `std_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
