-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 20, 2026 at 01:00 PM
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
-- Database: `db_starlink`
--

-- --------------------------------------------------------

--
-- Table structure for table `makanan`
--

CREATE TABLE `makanan` (
  `id_makanan` int(11) NOT NULL,
  `nama_makanan` varchar(100) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `stok` int(11) DEFAULT NULL,
  `status_check` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `makanan`
--

INSERT INTO `makanan` (`id_makanan`, `nama_makanan`, `harga`, `stok`, `status_check`) VALUES
(120, 'Nasi Goreng Starlink Special', 25000, 50, 1),
(121, 'Nasi Ayam Geprek Level 1-5', 18000, 40, 1),
(122, 'Nasi Ayam Bakar Taliwang', 28000, 30, 1),
(123, 'Nasi Gila Jakarta', 22000, 35, 1),
(124, 'Mie Goreng Jawa Kecombrang', 20000, 45, 1),
(125, 'Kwetiau Goreng Sapi', 27000, 25, 1),
(126, 'Soto Ayam Lamongan', 15000, 40, 1),
(127, 'Rawon Daging Surabaya', 30000, 20, 1),
(128, 'Bakso Urat Granat', 18000, 50, 1),
(129, 'Gado-Gado Spesial', 17000, 30, 1),
(130, 'Kentang Goreng Bolognese', 15000, 60, 1),
(131, 'Cireng Salju Bumbu Rujak', 12000, 100, 1),
(132, 'Tahu Walik Krispi', 12000, 80, 1),
(133, 'Dimsum Ayam (4 Pcs)', 18000, 40, 1),
(134, 'Pisang Goreng Keju Susu', 15000, 50, 1),
(135, 'Roti Bakar Matcha Oreo', 16000, 30, 1),
(136, 'Singkong Goreng Merekah', 12000, 40, 1),
(137, 'Nachos with Cheese Dip', 20000, 25, 1),
(138, 'Chicken Wings BBQ', 25000, 20, 1),
(139, 'Es Kopi Susu Gula Aren', 18000, 100, 1),
(140, 'Cafe Latte Hot/Ice', 22000, 60, 1),
(141, 'Caramel Macchiato', 25000, 40, 1),
(142, 'Hazelnut Choco Coffee', 23000, 45, 1),
(143, 'Matcha Latte Premium', 20000, 50, 1),
(144, 'Red Velvet Latte', 20000, 50, 1),
(145, 'Thai Tea Spesial', 15000, 80, 1),
(146, 'Es Teh Tarik', 15000, 70, 1),
(147, 'Lemonade Squash Fresh', 17000, 40, 1),
(148, 'Mango Smoothies', 22000, 30, 1),
(149, 'Lychee Tea with Jelly', 18000, 50, 1),
(150, 'Air Mineral Botol', 5000, 120, 1),
(151, 'Beef Burger Double Cheese', 35000, 25, 1),
(152, 'Spaghetti Carbonara Creamy', 32000, 30, 1),
(153, 'Fish and Chips Tartar Sauce', 38000, 20, 1),
(154, 'Chicken Parmigiana', 40000, 15, 1),
(155, 'Mac and Cheese Baked', 28000, 25, 1),
(156, 'Sate Ayam Madura (10 Tusuk)', 25000, 40, 1),
(157, 'Iga Bakar Madu Spesial', 55000, 15, 1),
(158, 'Mie Ayam Jamur Pangsit', 18000, 50, 1),
(159, 'Nasi Bebek Goreng Madura', 32000, 20, 1),
(160, 'Penyetan Empal Suwir', 27000, 25, 1),
(161, 'Blue Lagoon Starlink', 22000, 40, 1),
(162, 'Virgin Mojito Mint', 20000, 45, 1),
(163, 'Strawberry Sunrise', 22000, 35, 1),
(164, 'Purple Magic Lemonade', 23000, 30, 1),
(165, 'Kiwi Berry Sparkling', 25000, 25, 1),
(166, 'Molten Lava Cake with Vanilla Ice', 25000, 15, 1),
(167, 'Affogato Coffee Double Shot', 20000, 30, 1),
(168, 'Pancake Maple Syrup', 18000, 20, 1),
(169, 'Croissant Butter Toasted', 15000, 40, 1),
(170, 'Fruit Salad Honey Yogurt', 22000, 20, 1),
(171, 'Telur Ceplok/Dadar', 5000, 100, 1),
(172, 'Nasi Putih Extra', 6000, 200, 1),
(173, 'Sambal Matah/Bawang Extra', 3000, 100, 1),
(174, 'Kerupuk Udang Kaleng', 2000, 150, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pembelian`
--

CREATE TABLE `pembelian` (
  `id_pembelian` int(11) NOT NULL,
  `id_supplier` int(11) NOT NULL,
  `id_makanan` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `total_harga` int(11) NOT NULL,
  `tanggal_pembelian` date DEFAULT curdate(),
  `tanggal` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembelian`
--

INSERT INTO `pembelian` (`id_pembelian`, `id_supplier`, `id_makanan`, `jumlah`, `total_harga`, `tanggal_pembelian`, `tanggal`) VALUES
(19, 1, 120, 10, 250000, '2026-04-14', '0000-00-00'),
(20, 1, 174, 20, 400000, '2026-04-15', '0000-00-00'),
(21, 2, 121, 15, 255000, '2026-04-16', '0000-00-00'),
(22, 1, 122, 30, 660000, '2026-04-17', '0000-00-00'),
(23, 2, 123, 10, 120000, '2026-04-18', '0000-00-00'),
(24, 1, 124, 50, 250000, '2026-04-19', '0000-00-00'),
(25, 1, 125, 20, 360000, '2026-04-20', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id_supplier` int(11) NOT NULL,
  `nama_supplier` varchar(100) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `kontak` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id_supplier`, `nama_supplier`, `alamat`, `kontak`) VALUES
(1, 'PT Indomie', 'Gersng no 12 -surabaya', '0812334641239'),
(2, 'pt kapal api', 'Gersng no 12 -surabaya', '0812334641278'),
(3, 'PT Indofood CBP', 'Jl. Ancol Barat No.10, Jakarta', '081234567890'),
(4, 'PT Wings Surya', 'Jl. Kalibutuh No.189, Surabaya', '081233445566'),
(5, 'PT Santos Jaya Abadi (Kapal Api)', 'Jl. Gilang No.159, Sidoarjo', '081122334455'),
(6, 'CV Maju Mundur Logistik', 'Jl. Ahmad Yani No.20, Surabaya', '085677889900'),
(7, 'Distributor Sembako Jaya', 'Pasar Turi Baru Blok B, Surabaya', '087855443322'),
(8, 'PT Nestle Indonesia', 'Perkantoran Hijau Arkadia, Jakarta', '081299001122'),
(9, 'PT Coca-Cola Bottling', 'Jl. Rungkut Industri No.12, Surabaya', '081344556677'),
(10, 'UD Sayur Segar Pacet', 'Desa Kemiri, Pacet, Mojokerto', '085233114422'),
(11, 'PT Sosro Indonesia', 'Jl. Sultan Agung No.1, Bekasi', '081199887766'),
(12, 'Supplier Ayam Potong Berkah', 'Pasar Wonokromo, Surabaya', '081277665544'),
(13, 'PT Ultra Jaya Milk', 'Jl. Raya Gadobangkong, Bandung', '081244552211'),
(14, 'Sumber Makmur Sidoarjo', 'Perum Gading Fajar, Sidoarjo', '081944332211'),
(15, 'Distributor Telur Omega', 'Krian, Sidoarjo', '085733445566'),
(16, 'Agen LPG 3Kg Bersubsidi', 'Jl. Gayungsari No.45, Surabaya', '082133445500'),
(17, 'PT Yakult Indonesia', 'Kawasan Industri Ngoro, Mojokerto', '081244001133'),
(18, 'PT Yakult Indonesia', 'Kawasan Industri Ngoro, Mojokerto', '081244001133'),
(19, 'Agen LPG 3Kg Bersubsidi', 'Jl. Gayungsari No.45, Surabaya', '082133445500'),
(20, 'Distributor Telur Omega', 'Krian, Sidoarjo', '085733445566'),
(21, 'Sumber Makmur Sidoarjo', 'Perum Gading Fajar, Sidoarjo', '081944332211'),
(22, 'PT Ultra Jaya Milk', 'Jl. Raya Gadobangkong, Bandung', '081244552211'),
(23, 'Supplier Ayam Potong Berkah', 'Pasar Wonokromo, Surabaya', '081277665544'),
(24, 'PT Sosro Indonesia', 'Jl. Sultan Agung No.1, Bekasi', '081199887766'),
(25, 'UD Sayur Segar Pacet', 'Desa Kemiri, Pacet, Mojokerto', '085233114422'),
(26, 'PT Coca-Cola Bottling', 'Jl. Rungkut Industri No.12, Surabaya', '081344556677'),
(27, 'PT Indomie', 'Gersing no 12 - Surabaya', '0812334641239'),
(28, 'PT Kapal Api', 'Gersing no 12 - Surabaya', '0812334641278');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('Admin','Staff','Manager') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `role`) VALUES
(1, 'admin', 'admin123', 'Admin'),
(2, 'admin123', 'admin123', 'Admin'),
(3, 'staff123', 'staff123', 'Staff'),
(4, 'manager123', 'manager123', 'Manager');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `makanan`
--
ALTER TABLE `makanan`
  ADD PRIMARY KEY (`id_makanan`);

--
-- Indexes for table `pembelian`
--
ALTER TABLE `pembelian`
  ADD PRIMARY KEY (`id_pembelian`),
  ADD KEY `id_supplier` (`id_supplier`),
  ADD KEY `pembelian_ibfk_2` (`id_makanan`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id_supplier`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `makanan`
--
ALTER TABLE `makanan`
  MODIFY `id_makanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=175;

--
-- AUTO_INCREMENT for table `pembelian`
--
ALTER TABLE `pembelian`
  MODIFY `id_pembelian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id_supplier` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pembelian`
--
ALTER TABLE `pembelian`
  ADD CONSTRAINT `pembelian_ibfk_1` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id_supplier`),
  ADD CONSTRAINT `pembelian_ibfk_2` FOREIGN KEY (`id_makanan`) REFERENCES `makanan` (`id_makanan`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
