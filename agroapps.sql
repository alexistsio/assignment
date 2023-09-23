-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Φιλοξενητής: 127.0.0.1
-- Χρόνος δημιουργίας: 23 Σεπ 2023 στις 18:34:13
-- Έκδοση διακομιστή: 10.4.13-MariaDB
-- Έκδοση PHP: 7.4.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Βάση δεδομένων: `agroapps`
--

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `alx_access_token`
--

CREATE TABLE `alx_access_token` (
  `access_token` varchar(255) NOT NULL,
  `owner_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Άδειασμα δεδομένων του πίνακα `alx_access_token`
--

INSERT INTO `alx_access_token` (`access_token`, `owner_id`) VALUES
('09743ee1de939fbd7783ea5f5a91ac41', 18),
('0a87305ede3f209d908e2306bf937bd4', 17),
('16dbf64bea2076f5e2ec44a123f91329', 17),
('533a1118126ddc8e8b9a8b9af32e5c10', 17),
('73d80703c96b17208587408a45b9a923', 17),
('79fbee38ca7a48da4d9a8cd257b29829', 17),
('9121c52553f8109551ce76e1cc088818', 17),
('a4cf8ef175bc79b8754db3d91cc83054', 17),
('af85ce9f0adab4db16e3935e18349979', 17),
('b0537718cc508ada47c10bb82919ef86', 17),
('b3ca6349b4ab60d9c05859b46156db39', 17),
('b40be69cba7e835c7e829129729e318a', 17),
('d86fed5e7c77c04890bea8ed26a769dc', 17),
('d9ad42dfde0200e7a439114627409d93', 17);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `alx_category`
--

CREATE TABLE `alx_category` (
  `category_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Άδειασμα δεδομένων του πίνακα `alx_category`
--

INSERT INTO `alx_category` (`category_id`, `name`) VALUES
(1, 'Εστίαση'),
(2, 'Ένδυση/Υπόδηση'),
(3, 'Έπιπλα');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `alx_owner`
--

CREATE TABLE `alx_owner` (
  `owner_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(320) NOT NULL,
  `password` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Άδειασμα δεδομένων του πίνακα `alx_owner`
--

INSERT INTO `alx_owner` (`owner_id`, `name`, `email`, `password`) VALUES
(17, 'alexis', 'alexis.tsionoglou@gmail.com', '$2y$11$ca1d1884875bb952ead67u/NfdG6lUa.rMWoAPYwnxd0g8H9Er1Re'),
(18, 'alexis', 'alexis.tsio@gyahoo.gr', '$2y$11$9b807ab2802cd95974194ubRndCM5J.lbLbhETeQ6ygWnIzf.PYI6');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `alx_shop`
--

CREATE TABLE `alx_shop` (
  `shop_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `open_hours` text NOT NULL,
  `city` varchar(150) NOT NULL,
  `address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Άδειασμα δεδομένων του πίνακα `alx_shop`
--

INSERT INTO `alx_shop` (`shop_id`, `owner_id`, `category_id`, `description`, `open_hours`, `city`, `address`) VALUES
(1, 17, 2, 'Κατάστημα ένδυσης', 'Καθημερινα 9:00 - 21:00', 'Κατερίνη', 'Αρτεσκού 22'),
(2, 17, 2, 'menswomens', 'kathe mera', 'katerini', 'ioannou kosma 1'),
(3, 17, 2, 'menswomens2', 'kathe mera', 'katerini', 'ioannou kosma 1'),
(4, 18, 2, 'menswomens3', 'kathe mera', 'katerini', 'ioannou kosma 1');

--
-- Ευρετήρια για άχρηστους πίνακες
--

--
-- Ευρετήρια για πίνακα `alx_access_token`
--
ALTER TABLE `alx_access_token`
  ADD PRIMARY KEY (`access_token`);

--
-- Ευρετήρια για πίνακα `alx_category`
--
ALTER TABLE `alx_category`
  ADD PRIMARY KEY (`category_id`);

--
-- Ευρετήρια για πίνακα `alx_owner`
--
ALTER TABLE `alx_owner`
  ADD PRIMARY KEY (`owner_id`) USING BTREE,
  ADD KEY `email` (`email`);

--
-- Ευρετήρια για πίνακα `alx_shop`
--
ALTER TABLE `alx_shop`
  ADD PRIMARY KEY (`shop_id`),
  ADD KEY `owner_id` (`owner_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `city` (`city`);

--
-- AUTO_INCREMENT για άχρηστους πίνακες
--

--
-- AUTO_INCREMENT για πίνακα `alx_category`
--
ALTER TABLE `alx_category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT για πίνακα `alx_owner`
--
ALTER TABLE `alx_owner`
  MODIFY `owner_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT για πίνακα `alx_shop`
--
ALTER TABLE `alx_shop`
  MODIFY `shop_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
