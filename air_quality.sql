-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 26, 2026 at 05:14 PM
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
-- Database: `air_quality`
--

-- --------------------------------------------------------

--
-- Table structure for table `data_kualitas_udara`
--

CREATE TABLE `data_kualitas_udara` (
  `id_data` int(11) NOT NULL,
  `id_lokasi` int(11) DEFAULT NULL,
  `waktu_pengukuran` datetime NOT NULL,
  `pm25` decimal(5,2) NOT NULL,
  `aqi` int(11) NOT NULL,
  `kategori` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `data_kualitas_udara`
--

INSERT INTO `data_kualitas_udara` (`id_data`, `id_lokasi`, `waktu_pengukuran`, `pm25`, `aqi`, `kategori`) VALUES
(1, 1, '2026-11-26 02:00:00', 16.90, 77, 'Sedang'),
(2, 2, '2026-11-26 02:00:00', 16.90, 77, 'Sedang'),
(3, 3, '2026-11-26 02:00:00', 16.90, 77, 'Sedang'),
(4, 4, '2026-11-26 02:00:00', 16.90, 77, 'Sedang'),
(5, 5, '2026-11-26 02:00:00', 16.90, 77, 'Sedang'),
(6, 6, '2026-11-26 02:00:00', 16.90, 77, 'Sedang'),
(7, 7, '2026-11-26 02:00:00', 16.90, 77, 'Sedang'),
(8, 8, '2026-11-26 02:00:00', 16.90, 77, 'Sedang'),
(9, 10, '2026-11-26 02:00:00', 16.90, 77, 'Sedang'),
(10, 11, '2026-11-26 02:00:00', 16.90, 77, 'Sedang'),
(11, 12, '2026-11-26 02:00:00', 16.90, 77, 'Sedang'),
(12, 13, '2026-11-26 02:00:00', 12.20, 60, 'Sedang'),
(13, 14, '2026-11-26 02:00:00', 16.90, 77, 'Sedang'),
(14, 9, '2026-11-26 02:00:00', 16.90, 77, 'Sedang'),
(15, 16, '2026-11-26 02:00:00', 21.00, 64, 'Sedang'),
(16, 17, '2026-11-26 02:00:00', 15.40, 65, 'Sedang'),
(17, 18, '2026-11-26 02:00:00', 3.90, 50, 'Baik'),
(18, 19, '2026-11-26 02:00:00', 15.40, 57, 'Sedang'),
(19, 21, '2026-11-26 02:00:00', 63.30, 163, 'Tidak Sehat'),
(20, 24, '2026-11-26 02:00:00', 63.30, 163, 'Tidak Sehat'),
(21, 22, '2026-11-26 02:00:00', 63.30, 163, 'Tidak Sehat'),
(22, 23, '2026-11-26 02:00:00', 63.30, 163, 'Tidak Sehat'),
(23, 20, '2026-11-26 02:00:00', 3.60, 53, 'Sedang');

-- --------------------------------------------------------

--
-- Table structure for table `lokasi_pantau`
--

CREATE TABLE `lokasi_pantau` (
  `id_lokasi` int(11) NOT NULL,
  `nama_stasiun` varchar(100) NOT NULL,
  `wilayah` varchar(50) NOT NULL,
  `status` varchar(20) DEFAULT 'Aktif',
  `region` varchar(50) NOT NULL DEFAULT 'Sukabumi'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lokasi_pantau`
--

INSERT INTO `lokasi_pantau` (`id_lokasi`, `nama_stasiun`, `wilayah`, `status`, `region`) VALUES
(1, 'Stasiun Pusat Cikole', 'Cikole', 'Aktif', 'Sukabumi'),
(2, 'Sensor Timur Citamiang', 'Citamiang', 'Aktif', 'Sukabumi'),
(3, 'Pantau Warudoyong', 'Warudoyong', 'Aktif', 'Sukabumi'),
(4, 'Stasiun Gunungpuyuh', 'Gunungpuyuh', 'Aktif', 'Sukabumi'),
(5, 'Pos Selatan Baros', 'Baros', 'Aktif', 'Sukabumi'),
(6, 'Area Industri Lembursitu', 'Lembursitu', 'Aktif', 'Sukabumi'),
(7, 'Sensor Cibeureum', 'Cibeureum', 'Aktif', 'Sukabumi'),
(8, 'Stasiun Batas Sukaraja', 'Sukaraja', 'Aktif', 'Sukabumi'),
(9, 'Pantau Barat Cisaat', 'Cisaat', 'Tidak Aktif', 'Sukabumi'),
(10, 'Pos Dingin Kadudampit', 'Kadudampit', 'Aktif', 'Sukabumi'),
(11, 'Pos Pantau Cibadak', 'Cibadak', 'Aktif', 'Sukabumi'),
(12, 'Sensor Industri Cicurug', 'Cicurug', 'Aktif', 'Sukabumi'),
(13, 'Stasiun Pesisir Palabuhanratu', 'Palabuhanratu', 'Aktif', 'Sukabumi'),
(14, 'Pos Pantau Parungkuda', 'Parungkuda', 'Aktif', 'Sukabumi'),
(15, 'Sensor Wilayah Nagrak', 'Nagrak', 'Tidak Aktif', 'Sukabumi'),
(16, 'Stasiun Khatulistiwa Pontianak', 'Pontianak', 'Aktif', 'Kalimantan'),
(17, 'Pos Pantau Sungai Martapura', 'Banjarmasin', 'Aktif', 'Kalimantan'),
(18, 'Sensor Gambut Palangka Raya', 'Palangka Raya', 'Aktif', 'Kalimantan'),
(19, 'Stasiun Tepian Mahakam', 'Samarinda', 'Aktif', 'Kalimantan'),
(20, 'Pos Pesisir Balikpapan', 'Balikpapan', 'Aktif', 'Kalimantan'),
(21, 'Stasiun Margonda Beji', 'Margonda', 'Aktif', 'Depok'),
(22, 'Sensor Industri Cimanggis', 'Cimanggis', 'Aktif', 'Depok'),
(23, 'Pos Pantau Sawangan', 'Sawangan', 'Aktif', 'Depok'),
(24, 'Stasiun Pusat Pancoran Mas', 'Pancoran Mas', 'Aktif', 'Depok'),
(25, 'Pos Batas Cinere', 'Cinere', 'Tidak Aktif', 'Depok');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `data_kualitas_udara`
--
ALTER TABLE `data_kualitas_udara`
  ADD PRIMARY KEY (`id_data`),
  ADD KEY `id_lokasi` (`id_lokasi`);

--
-- Indexes for table `lokasi_pantau`
--
ALTER TABLE `lokasi_pantau`
  ADD PRIMARY KEY (`id_lokasi`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `data_kualitas_udara`
--
ALTER TABLE `data_kualitas_udara`
  MODIFY `id_data` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `lokasi_pantau`
--
ALTER TABLE `lokasi_pantau`
  MODIFY `id_lokasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `data_kualitas_udara`
--
ALTER TABLE `data_kualitas_udara`
  ADD CONSTRAINT `data_kualitas_udara_ibfk_1` FOREIGN KEY (`id_lokasi`) REFERENCES `lokasi_pantau` (`id_lokasi`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
