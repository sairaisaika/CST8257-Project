-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 03, 2019 at 10:26 AM
-- Server version: 10.1.41-MariaDB-0+deb9u1
-- PHP Version: 7.0.33-0+deb9u6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `CST8257`
--

-- --------------------------------------------------------

--
-- Table structure for table `Accessibility`
--

CREATE TABLE `Accessibility` (
  `Accessibility_Code` varchar(16) NOT NULL,
  `Description` varchar(127) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `Album`
--

CREATE TABLE `Album` (
  `Album_Id` int(11) NOT NULL,
  `Title` varchar(256) NOT NULL,
  `Description` varchar(3000) DEFAULT NULL,
  `Date_Updated` date NOT NULL,
  `Owner_Id` varchar(16) NOT NULL,
  `Accessibility_Code` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `Comment`
--

CREATE TABLE `Comment` (
  `Comment_Id` int(11) NOT NULL,
  `Author_Id` varchar(16) NOT NULL,
  `Picture_Id` int(11) NOT NULL,
  `Comment_Text` varchar(3000) NOT NULL,
  `Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `Friendship`
--

CREATE TABLE `Friendship` (
  `Friend_RequesterId` varchar(16) NOT NULL,
  `Friend_RequesteeId` varchar(16) NOT NULL,
  `Status` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `FriendshipStatus`
--

CREATE TABLE `FriendshipStatus` (
  `Status_Code` varchar(16) NOT NULL,
  `Description` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `Picture`
--

CREATE TABLE `Picture` (
  `Picture_Id` int(11) NOT NULL,
  `Album_Id` int(11) NOT NULL,
  `FileName` varchar(255) NOT NULL,
  `Tile` varchar(256) NOT NULL,
  `Description` varchar(3000) DEFAULT NULL,
  `Date_Added` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE `User` (
  `UserId` varchar(16) NOT NULL,
  `Name` varchar(256) NOT NULL,
  `Phone` varchar(16) DEFAULT NULL,
  `Password` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Accessibility`
--
ALTER TABLE `Accessibility`
  ADD PRIMARY KEY (`Accessibility_Code`);

--
-- Indexes for table `Album`
--
ALTER TABLE `Album`
  ADD PRIMARY KEY (`Album_Id`),
  ADD KEY `Album_User_FK` (`Owner_Id`),
  ADD KEY `Accessibility_Code` (`Accessibility_Code`);

--
-- Indexes for table `Comment`
--
ALTER TABLE `Comment`
  ADD PRIMARY KEY (`Comment_Id`),
  ADD KEY `Comment_User_FK` (`Author_Id`),
  ADD KEY `Comment_Picture_FK` (`Picture_Id`);

--
-- Indexes for table `Friendship`
--
ALTER TABLE `Friendship`
  ADD KEY `Friendship_User_FK` (`Friend_RequesterId`),
  ADD KEY `Friendship_User_FK2` (`Friend_RequesteeId`),
  ADD KEY `Friendship_FriendshipStatus` (`Status`);

--
-- Indexes for table `FriendshipStatus`
--
ALTER TABLE `FriendshipStatus`
  ADD PRIMARY KEY (`Status_Code`);

--
-- Indexes for table `Picture`
--
ALTER TABLE `Picture`
  ADD PRIMARY KEY (`Picture_Id`),
  ADD KEY `Picture_Album_FK` (`Album_Id`);

--
-- Indexes for table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`UserId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Album`
--
ALTER TABLE `Album`
  MODIFY `Album_Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Comment`
--
ALTER TABLE `Comment`
  MODIFY `Comment_Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Picture`
--
ALTER TABLE `Picture`
  MODIFY `Picture_Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Album`
--
ALTER TABLE `Album`
  ADD CONSTRAINT `Album_Accessibility_FK` FOREIGN KEY (`Accessibility_Code`) REFERENCES `Accessibility` (`Accessibility_Code`),
  ADD CONSTRAINT `Album_User_FK` FOREIGN KEY (`Owner_Id`) REFERENCES `User` (`UserId`);

--
-- Constraints for table `Comment`
--
ALTER TABLE `Comment`
  ADD CONSTRAINT `Comment_Picture_FK` FOREIGN KEY (`Picture_Id`) REFERENCES `Picture` (`Picture_Id`),
  ADD CONSTRAINT `Comment_User_FK` FOREIGN KEY (`Author_Id`) REFERENCES `User` (`UserId`);

--
-- Constraints for table `Friendship`
--
ALTER TABLE `Friendship`
  ADD CONSTRAINT `Friendship_FriendshipStatus` FOREIGN KEY (`Status`) REFERENCES `FriendshipStatus` (`Status_Code`),
  ADD CONSTRAINT `Friendship_User_FK` FOREIGN KEY (`Friend_RequesterId`) REFERENCES `User` (`UserId`),
  ADD CONSTRAINT `Friendship_User_FK2` FOREIGN KEY (`Friend_RequesteeId`) REFERENCES `User` (`UserId`);

--
-- Constraints for table `Picture`
--
ALTER TABLE `Picture`
  ADD CONSTRAINT `Picture_Album_FK` FOREIGN KEY (`Album_Id`) REFERENCES `Album` (`Album_Id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
