-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2021. Jún 20. 08:23
-- Kiszolgáló verziója: 10.4.19-MariaDB
-- PHP verzió: 7.4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `terembeosztas`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `tb_beosztas`
--

CREATE TABLE `tb_beosztas` (
  `osztaly_id` int(10) UNSIGNED NOT NULL,
  `nap` tinyint(4) NOT NULL,
  `idopont` tinyint(4) NOT NULL,
  `pozicio` tinyint(4) NOT NULL,
  `terem_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `tb_osztaly`
--

CREATE TABLE `tb_osztaly` (
  `osztaly_id` int(10) UNSIGNED NOT NULL,
  `evfolyam` tinyint(4) NOT NULL DEFAULT 9,
  `betujel` varchar(1) NOT NULL,
  `nev` varchar(20) NOT NULL,
  `rovid_nev` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `tb_terem`
--

CREATE TABLE `tb_terem` (
  `terem_id` int(10) UNSIGNED NOT NULL,
  `nev` varchar(20) NOT NULL,
  `rovid_nev` varchar(5) NOT NULL,
  `megjegyzes` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `tb_osztaly`
--
ALTER TABLE `tb_osztaly`
  ADD PRIMARY KEY (`osztaly_id`);

--
-- A tábla indexei `tb_terem`
--
ALTER TABLE `tb_terem`
  ADD PRIMARY KEY (`terem_id`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `tb_osztaly`
--
ALTER TABLE `tb_osztaly`
  MODIFY `osztaly_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `tb_terem`
--
ALTER TABLE `tb_terem`
  MODIFY `terem_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
