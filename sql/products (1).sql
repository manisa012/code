-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 13, 2025 at 09:54 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shopping`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `subCategory` int(11) DEFAULT NULL,
  `productName` varchar(255) DEFAULT NULL,
  `productCompany` varchar(255) DEFAULT NULL,
  `productPrice` int(11) DEFAULT NULL,
  `productPriceBeforeDiscount` int(11) DEFAULT NULL,
  `productDescription` longtext DEFAULT NULL,
  `productImage1` varchar(255) DEFAULT NULL,
  `productImage2` varchar(255) DEFAULT NULL,
  `productImage3` varchar(255) DEFAULT NULL,
  `shippingCharge` int(11) DEFAULT NULL,
  `productAvailability` varchar(255) DEFAULT NULL,
  `postingDate` timestamp NULL DEFAULT current_timestamp(),
  `updationDate` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category`, `subCategory`, `productName`, `productCompany`, `productPrice`, `productPriceBeforeDiscount`, `productDescription`, `productImage1`, `productImage2`, `productImage3`, `shippingCharge`, `productAvailability`, `postingDate`, `updationDate`) VALUES
(24, 10, 17, 'Acer Nitro 16 Gaming Laptop', 'ACER', 1100, 1200, '<br>', 'apple-iphone-6-1.jpeg', 'apple-iphone-6-1.jpeg', 'apple-iphone-6-1.jpeg', 1, 'In Stock', '2025-02-11 15:57:28', NULL),
(25, 9, 16, 'asd', 'asd', 11000, 0, 'asdas<br>', 'apple-iphone-6-3.jpeg', 'apple-iphone-6-2.jpeg', 'apple-iphone-6-1.jpeg', 1123, 'In Stock', '2025-02-13 08:35:52', NULL),
(26, 10, 17, 'qd', 'qwd', 123321, 123, 'asd<br>', 'apple-iphone-6-1 (1).jpeg', 'apple-iphone-6-2.jpeg', 'apple-iphone-6-1.jpeg', 0, 'Out of Stock', '2025-02-13 08:41:29', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
