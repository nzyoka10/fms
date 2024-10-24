-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 24, 2024 at 06:02 AM
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
-- Database: `fms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `full_name`, `email`, `phone`, `address`, `created_at`, `updated_at`) VALUES
(1, 'Juma Hunderson', 'hunter@app.com', '0789256714', '51 Street, Limuru', '2024-09-28 13:09:02', '2024-10-10 15:47:05'),
(2, 'Mike Aguero Jr', 'mike@app.fms', '0712671267', '20th, Malawi sub county', '2024-09-28 13:14:52', '2024-10-16 09:59:32'),
(3, 'Lamar Test', 'exampl@info.com', '0736348911', '23 Street, Nairobi', '2024-09-28 13:22:31', '2024-09-29 05:28:03'),
(4, 'Limuru Loresho', 'loresho@mail.com', '0789256714', '20 Street, Juja', '2024-09-28 13:29:59', '2024-10-10 15:47:42'),
(5, 'Jayden Uhuru ', 'jayden@mail.fms', '0736348911', '100 Kijabe, Nairobi', '2024-09-28 15:10:30', '2024-09-28 15:15:50'),
(6, 'Super Metro', 'super@app.com', '07911782123', '51 Street, Waiyaki', '2024-09-29 05:25:58', '2024-09-29 05:25:58'),
(7, 'Griffin Kioko', 'kioko@app.com', '0789125826', '87 Street, Kinoo', '2024-09-29 06:24:15', '2024-09-29 06:24:15'),
(8, 'Juma Ali Noor', 'noor@app.com', '0736348911', '20 Street, Katani', '2024-09-29 11:46:20', '2024-10-10 15:24:46'),
(9, 'Constance Minoo', 'minoo@app.com', '254789452731', '14 Street, Kalawa', '2024-10-05 17:02:09', '2024-10-10 15:48:48'),
(10, 'Gage Kamau', 'kamaa@app.com', '254756127812', '20 Street, Limuru', '2024-10-10 15:52:51', '2024-10-10 15:52:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
