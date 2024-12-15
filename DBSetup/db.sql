-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 30, 2024 at 03:37 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";



--
-- Database: `vbs`
--

CREATE DATABASE IF NOT EXISTS `vbs` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `vbs`;

-- --------------------------------------------------------

--
-- Table structure for table `allergies`
--

CREATE TABLE `allergies` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(50) NOT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `allergies`
--

INSERT INTO `allergies` (`id`, `name`, `category`, `notes`) VALUES
(1, 'Peanuts', 'Food', 'Common severe allergy to nuts'),
(2, 'Tree Nuts', 'Food', 'Includes walnuts, almonds, hazelnuts, etc.'),
(3, 'Milk', 'Food', 'Lactose intolerance or milk protein allergy'),
(4, 'Eggs', 'Food', 'Egg white or yolk allergies'),
(5, 'Wheat', 'Food', 'Includes gluten sensitivity'),
(6, 'Soy', 'Food', 'Soybeans and soy-based products'),
(7, 'Fish', 'Food', 'Common in oily or white fish like cod, salmon, etc.'),
(8, 'Shellfish', 'Food', 'Includes prawns, shrimp, crab, and lobster'),
(9, 'Pollen', 'Environmental', 'Grass, tree, or weed pollen causing hay fever'),
(10, 'Dust Mites', 'Environmental', 'Triggered by microscopic dust mites'),
(11, 'Mould', 'Environmental', 'Airborne mould spores causing respiratory issues'),
(12, 'Animal Dander', 'Environmental', 'Cats, dogs, and other pets'),
(13, 'Latex', 'Environmental', 'Includes balloons and gloves'),
(14, 'Bee Stings', 'Insect', 'Can cause anaphylaxis in severe cases'),
(15, 'Wasp Stings', 'Insect', 'Similar to bee stings but different venom'),
(16, 'Ant Bites', 'Insect', 'Includes common UK garden ants'),
(17, 'Penicillin', 'Drug', 'Common antibiotic allergy'),
(18, 'Aspirin', 'Drug', 'Anti-inflammatory and pain-relief medication'),
(19, 'Ibuprofen/Nurofen', 'Drug', 'NSAID drug allergy'),
(20, 'Fragrances', 'Contact', 'Includes perfumes and deodorants'),
(21, 'Nickel', 'Contact', 'Metal commonly found in jewellery'),
(22, 'Chlorine', 'Contact', 'Found in swimming pools or cleaning agents'),
(23, 'No Allegy/Medical Condition', 'None', 'No Allegy/Medical Condition');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `att_day` varchar(5) NOT NULL,
  `att_cid` int(11) NOT NULL,
  `att_session` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `checkin`
--

CREATE TABLE `checkin` (
  `check_cid` int(11) NOT NULL,
  `check_day` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `checkout`
--

CREATE TABLE `checkout` (
  `co_cid` int(11) NOT NULL,
  `co_day` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cls`
--

CREATE TABLE `cls` (
  `clsname` varchar(50) NOT NULL,
  `clsgrpid` int(11) NOT NULL,
  `cls_tid` int(11) NOT NULL,
  `cls_cid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE `config` (
  `vbs_id` int(11) NOT NULL,
  `church_name` varchar(255) NOT NULL,
  `web` varchar(255) NOT NULL,
  `vbs_title` varchar(255) NOT NULL,
  `vbs_year` year(4) NOT NULL,
  `vbs_active` char(1) DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `grp`
--

CREATE TABLE `grp` (
  `grpid` int(11) NOT NULL,
  `gname` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grp`
--

INSERT INTO `grp` (`grpid`, `gname`) VALUES
(1, 'Beginner'),
(2, 'Junior'),
(3, 'Senior');

-- --------------------------------------------------------

--
-- Table structure for table `reg_entries`
--

CREATE TABLE `reg_entries` (
  `cid` int(11) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `age` int(11) NOT NULL,
  `school` varchar(255) NOT NULL,
  `allergies` text NOT NULL,
  `ec_relation` varchar(255) NOT NULL,
  `ec_fullname` varchar(255) NOT NULL,
  `ec_email` varchar(255) NOT NULL,
  `ec_phone` varchar(20) NOT NULL,
  `ec_addr` text NOT NULL,
  `stgrpid` int(11) NOT NULL,
  `regdate` date NOT NULL,
  `child_clsid` int(11) DEFAULT NULL,
  `qr_gen` varchar(1) NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
--

CREATE TABLE `teacher` (
  `tid` int(11) NOT NULL,
  `tname` varchar(255) NOT NULL,
  `at_name` varchar(255) NOT NULL,
  `tgrpid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$7BOhH6dzhzW08myZ7fThR.VKBuvBr1aYK1gjNL9UCfJbqRRMDrmYW');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `allergies`
--
ALTER TABLE `allergies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD KEY `att_cid` (`att_cid`);

--
-- Indexes for table `checkin`
--
ALTER TABLE `checkin`
  ADD KEY `check_cid` (`check_cid`);

--
-- Indexes for table `checkout`
--
ALTER TABLE `checkout`
  ADD KEY `co_cid` (`co_cid`);

--
-- Indexes for table `cls`
--
ALTER TABLE `cls`
  ADD UNIQUE KEY `cls_cid_2` (`cls_cid`),
  ADD KEY `clsgrpid` (`clsgrpid`),
  ADD KEY `cls_tid` (`cls_tid`),
  ADD KEY `cls_cid` (`cls_cid`);

--
-- Indexes for table `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`vbs_id`);

--
-- Indexes for table `grp`
--
ALTER TABLE `grp`
  ADD PRIMARY KEY (`grpid`),
  ADD KEY `grpid` (`grpid`);

--
-- Indexes for table `reg_entries`
--
ALTER TABLE `reg_entries`
  ADD PRIMARY KEY (`cid`),
  ADD KEY `stgrpid` (`stgrpid`),
  ADD KEY `child_clsid` (`child_clsid`);

--
-- Indexes for table `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`tid`),
  ADD KEY `tgrpid` (`tgrpid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `allergies`
--
ALTER TABLE `allergies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `config`
--
ALTER TABLE `config`
  MODIFY `vbs_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `grp`
--
ALTER TABLE `grp`
  MODIFY `grpid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `reg_entries`
--
ALTER TABLE `reg_entries`
  MODIFY `cid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=275;

--
-- AUTO_INCREMENT for table `teacher`
--
ALTER TABLE `teacher`
  MODIFY `tid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`att_cid`) REFERENCES `reg_entries` (`cid`);

--
-- Constraints for table `checkin`
--
ALTER TABLE `checkin`
  ADD CONSTRAINT `checkin_ibfk_1` FOREIGN KEY (`check_cid`) REFERENCES `reg_entries` (`cid`);

--
-- Constraints for table `checkout`
--
ALTER TABLE `checkout`
  ADD CONSTRAINT `checkout_ibfk_1` FOREIGN KEY (`co_cid`) REFERENCES `reg_entries` (`cid`);

--
-- Constraints for table `cls`
--
ALTER TABLE `cls`
  ADD CONSTRAINT `cls_ibfk_1` FOREIGN KEY (`clsgrpid`) REFERENCES `grp` (`grpid`),
  ADD CONSTRAINT `cls_ibfk_2` FOREIGN KEY (`cls_tid`) REFERENCES `teacher` (`tid`),
  ADD CONSTRAINT `cls_ibfk_3` FOREIGN KEY (`cls_cid`) REFERENCES `reg_entries` (`cid`);

--
-- Constraints for table `teacher`
--
ALTER TABLE `teacher`
  ADD CONSTRAINT `teacher_ibfk_1` FOREIGN KEY (`tgrpid`) REFERENCES `grp` (`grpid`);
COMMIT;