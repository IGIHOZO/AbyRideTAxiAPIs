-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 12, 2022 at 10:59 PM
-- Server version: 10.3.34-MariaDB-cll-lve
-- PHP Version: 7.4.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pyyquokz_abyride_taxi`
--

-- --------------------------------------------------------

--
-- Table structure for table `carmodels`
--

CREATE TABLE `carmodels` (
  `ModelId` int(11) NOT NULL,
  `Model` varchar(30) NOT NULL,
  `Status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `carmodels`
--

INSERT INTO `carmodels` (`ModelId`, `Model`, `Status`) VALUES
(2, 'Abarth', 1),
(3, 'Alfa Romeo', 1),
(4, 'Aston Martin', 1),
(5, 'Audi', 1),
(6, 'Bentley', 1),
(7, 'BMW', 1),
(8, 'Bugatti', 1),
(9, 'Cadillac', 1),
(10, 'Chevrolet', 1),
(11, 'Chrysler', 1),
(12, 'Citro?n', 1),
(13, 'Dacia', 1),
(14, 'Daewoo', 1),
(15, 'Daihatsu', 1),
(16, 'Dodge', 1),
(17, 'Donkervoort', 1),
(18, 'DS', 1),
(19, 'Ferrari', 1),
(20, 'Fiat', 1),
(21, 'Fisker', 1),
(22, 'Ford', 1),
(23, 'Honda', 1),
(24, 'Hummer', 1),
(25, 'Hyundai', 1),
(26, 'Infiniti', 1),
(27, 'Iveco', 1),
(28, 'Jaguar', 1),
(29, 'Jeep', 1),
(30, 'Kia', 1),
(31, 'KTM', 1),
(32, 'Lada', 1),
(33, 'Lamborghini', 1),
(34, 'Lancia', 1),
(35, 'Land Rover', 1),
(36, 'Landwind', 1),
(37, 'Lexus', 1),
(38, 'Lotus', 1),
(39, 'Maserati', 1),
(40, 'Maybach', 1),
(41, 'Mazda', 1),
(42, 'McLaren', 1),
(43, 'Mercedes-Benz', 1),
(44, 'MG', 1),
(45, 'Mini', 1),
(46, 'Mitsubishi', 1),
(47, 'Morgan', 1),
(48, 'Nissan', 1),
(49, 'Opel', 1),
(50, 'Peugeot', 1),
(51, 'Porsche', 1),
(52, 'Renault', 1),
(53, 'Rolls-Royce', 1),
(54, 'Rover', 1),
(55, 'Saab', 1),
(56, 'Seat', 1),
(57, 'Skoda', 1),
(58, 'Smart', 1),
(59, 'SsangYong', 1),
(60, 'Subaru', 1),
(61, 'Suzuki', 1),
(62, 'Tesla', 1),
(63, 'Toyota', 1),
(64, 'Volkswagen', 1),
(65, 'Volvo', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cars`
--

CREATE TABLE `cars` (
  `CarId` int(11) NOT NULL,
  `UserId` int(11) NOT NULL,
  `Seats` int(11) NOT NULL,
  `ModuleId` int(11) NOT NULL,
  `Status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `DocumentId` int(11) NOT NULL,
  `Name` varchar(30) NOT NULL,
  `Status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`DocumentId`, `Name`, `Status`) VALUES
(1, 'Driving Licence', 1),
(2, 'Vehicle Licence', 1),
(3, 'Insurance Papers', 1);

-- --------------------------------------------------------

--
-- Table structure for table `drivers`
--

CREATE TABLE `drivers` (
  `DriverId` int(11) NOT NULL,
  `UserId` int(11) NOT NULL,
  `File` varchar(512) NOT NULL,
  `DocumentId` int(11) NOT NULL,
  `Status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserId` int(11) NOT NULL,
  `FullName` varchar(50) NOT NULL,
  `Picture` varchar(50) DEFAULT NULL,
  `Address` varchar(50) NOT NULL,
  `Email` varchar(30) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Card` varchar(30) DEFAULT NULL,
  `Phone` varchar(30) NOT NULL,
  `Type` int(11) NOT NULL,
  `Status` int(11) NOT NULL,
  `InsertedDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `DeletedDate` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carmodels`
--
ALTER TABLE `carmodels`
  ADD PRIMARY KEY (`ModelId`);

--
-- Indexes for table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`CarId`),
  ADD KEY `CarTypeId` (`ModuleId`),
  ADD KEY `UserId` (`UserId`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`DocumentId`);

--
-- Indexes for table `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`DriverId`),
  ADD KEY `UserId` (`UserId`),
  ADD KEY `DocumentId` (`DocumentId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carmodels`
--
ALTER TABLE `carmodels`
  MODIFY `ModelId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `cars`
--
ALTER TABLE `cars`
  MODIFY `CarId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `DocumentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `drivers`
--
ALTER TABLE `drivers`
  MODIFY `DriverId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserId` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cars`
--
ALTER TABLE `cars`
  ADD CONSTRAINT `cars_ibfk_1` FOREIGN KEY (`ModuleId`) REFERENCES `carmodels` (`ModelId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cars_ibfk_2` FOREIGN KEY (`UserId`) REFERENCES `users` (`UserId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `drivers`
--
ALTER TABLE `drivers`
  ADD CONSTRAINT `drivers_ibfk_1` FOREIGN KEY (`UserId`) REFERENCES `users` (`UserId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `drivers_ibfk_2` FOREIGN KEY (`DocumentId`) REFERENCES `documents` (`DocumentId`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
