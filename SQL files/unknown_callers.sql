-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2022. Jan 28. 17:48
-- Kiszolgáló verziója: 10.4.18-MariaDB
-- PHP verzió: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `unknown_callers`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `called_numbers`
--

CREATE TABLE `called_numbers` (
  `called_number_id` int(10) NOT NULL,
  `calling_code` int(8) NOT NULL DEFAULT 36,
  `prefix` int(2) NOT NULL,
  `numbers` int(10) NOT NULL,
  `name` varchar(50) COLLATE utf8_hungarian_ci DEFAULT NULL,
  `notes` varchar(100) COLLATE utf8_hungarian_ci DEFAULT NULL,
  `user_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- A tábla adatainak kiíratása `called_numbers`
--

INSERT INTO `called_numbers` (`called_number_id`, `calling_code`, `prefix`, `numbers`, `name`, `notes`, `user_id`) VALUES
(17, 36, 30, 2778219, NULL, NULL, 1),
(19, 36, 70, 9999999, NULL, NULL, 1),
(21, 36, 20, 3333333, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `calling_numbers`
--

CREATE TABLE `calling_numbers` (
  `calling_number_id` int(10) NOT NULL,
  `calling_code` int(8) NOT NULL DEFAULT 36,
  `prefix` int(2) NOT NULL,
  `numbers` int(10) NOT NULL,
  `name` varchar(50) COLLATE utf8_hungarian_ci DEFAULT NULL,
  `identity` enum('known','unknown') COLLATE utf8_hungarian_ci NOT NULL DEFAULT 'unknown',
  `type` enum('unknown','trustworthy','gambling','debt_collection','advertising','survey','harassment','telemarketer','ping_call','political') COLLATE utf8_hungarian_ci NOT NULL,
  `notes` varchar(100) COLLATE utf8_hungarian_ci DEFAULT NULL,
  `user_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- A tábla adatainak kiíratása `calling_numbers`
--

INSERT INTO `calling_numbers` (`calling_number_id`, `calling_code`, `prefix`, `numbers`, `name`, `identity`, `type`, `notes`, `user_id`) VALUES
(1, 36, 20, 1234567, NULL, 'unknown', 'unknown', NULL, 1),
(100013, 36, 1, 1111111, NULL, 'unknown', 'unknown', NULL, 1),
(100015, 36, 30, 4444444, NULL, 'unknown', 'unknown', NULL, 1);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `call_types`
--

CREATE TABLE `call_types` (
  `type` enum('unknown','trustworthy','gambling','debt_collection','advertising','survey','harassment','telemarketer','ping_call','political') COLLATE utf8_hungarian_ci NOT NULL,
  `is_safe` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- A tábla adatainak kiíratása `call_types`
--

INSERT INTO `call_types` (`type`, `is_safe`) VALUES
('unknown', 0),
('trustworthy', 1),
('gambling', 0),
('debt_collection', 0),
('advertising', 0),
('survey', 0),
('harassment', 0),
('telemarketer', 0),
('ping_call', 0),
('political', 0);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `country_calling_codes`
--

CREATE TABLE `country_calling_codes` (
  `calling_code` int(8) NOT NULL,
  `country` varchar(50) COLLATE utf8_hungarian_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- A tábla adatainak kiíratása `country_calling_codes`
--

INSERT INTO `country_calling_codes` (`calling_code`, `country`) VALUES
(36, 'Hungary'),
(40, 'Romania'),
(43, 'Austria'),
(380, 'Ukraine'),
(381, 'Serbia'),
(385, 'Croatia'),
(386, 'Slovenia'),
(421, 'Slovakia	');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `incoming_calls`
--

CREATE TABLE `incoming_calls` (
  `call_id` int(10) NOT NULL,
  `calling_number_id` int(10) NOT NULL,
  `called_number_id` int(10) NOT NULL,
  `date_time` datetime NOT NULL,
  `state` enum('accepted','denied','missed') COLLATE utf8_hungarian_ci NOT NULL DEFAULT 'missed',
  `notes` varchar(100) COLLATE utf8_hungarian_ci DEFAULT NULL,
  `user_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- A tábla adatainak kiíratása `incoming_calls`
--

INSERT INTO `incoming_calls` (`call_id`, `calling_number_id`, `called_number_id`, `date_time`, `state`, `notes`, `user_id`) VALUES
(18, 100013, 19, '2022-01-06 06:02:00', 'missed', NULL, 1),
(20, 100015, 21, '2022-01-07 05:43:00', 'missed', NULL, 1);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `prefixes_hu`
--

CREATE TABLE `prefixes_hu` (
  `prefix` int(2) NOT NULL,
  `name` varchar(50) COLLATE utf8_hungarian_ci DEFAULT NULL,
  `type` enum('city','telecom company','other','') COLLATE utf8_hungarian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- A tábla adatainak kiíratása `prefixes_hu`
--

INSERT INTO `prefixes_hu` (`prefix`, `name`, `type`) VALUES
(1, 'Budapest', 'city'),
(20, 'Telenor', 'telecom company'),
(21, 'VoIP', 'other'),
(30, 'Telekom', 'telecom company'),
(50, 'DIGI mobil', 'telecom company'),
(70, 'Vodafone', 'telecom company');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `users`
--

CREATE TABLE `users` (
  `user_id` int(10) NOT NULL,
  `name` varchar(100) COLLATE utf8_hungarian_ci NOT NULL,
  `password` varchar(100) COLLATE utf8_hungarian_ci NOT NULL,
  `permissions` enum('','administrator','user','guest') COLLATE utf8_hungarian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- A tábla adatainak kiíratása `users`
--

INSERT INTO `users` (`user_id`, `name`, `password`, `permissions`) VALUES
(1, 'admin', 'password', 'administrator'),
(2, 'admin', 'jelszó', 'administrator');

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `called_numbers`
--
ALTER TABLE `called_numbers`
  ADD PRIMARY KEY (`called_number_id`),
  ADD KEY `called_number_id` (`called_number_id`,`calling_code`,`prefix`),
  ADD KEY `calling_code` (`calling_code`),
  ADD KEY `prefix` (`prefix`),
  ADD KEY `user_id` (`user_id`);

--
-- A tábla indexei `calling_numbers`
--
ALTER TABLE `calling_numbers`
  ADD PRIMARY KEY (`calling_number_id`),
  ADD KEY `calling_number_id` (`calling_number_id`,`calling_code`,`prefix`),
  ADD KEY `calling_code` (`calling_code`),
  ADD KEY `prefix` (`prefix`),
  ADD KEY `type` (`type`),
  ADD KEY `user_id` (`user_id`);

--
-- A tábla indexei `call_types`
--
ALTER TABLE `call_types`
  ADD PRIMARY KEY (`type`),
  ADD KEY `type` (`type`);

--
-- A tábla indexei `country_calling_codes`
--
ALTER TABLE `country_calling_codes`
  ADD PRIMARY KEY (`calling_code`),
  ADD KEY `calling_code` (`calling_code`);

--
-- A tábla indexei `incoming_calls`
--
ALTER TABLE `incoming_calls`
  ADD PRIMARY KEY (`call_id`),
  ADD KEY `call_id` (`call_id`,`calling_number_id`,`called_number_id`),
  ADD KEY `called_number_id` (`called_number_id`),
  ADD KEY `calling_number_id` (`calling_number_id`),
  ADD KEY `user_id` (`user_id`);

--
-- A tábla indexei `prefixes_hu`
--
ALTER TABLE `prefixes_hu`
  ADD PRIMARY KEY (`prefix`),
  ADD KEY `prefix` (`prefix`);

--
-- A tábla indexei `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `called_numbers`
--
ALTER TABLE `called_numbers`
  MODIFY `called_number_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT a táblához `calling_numbers`
--
ALTER TABLE `calling_numbers`
  MODIFY `calling_number_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100016;

--
-- AUTO_INCREMENT a táblához `incoming_calls`
--
ALTER TABLE `incoming_calls`
  MODIFY `call_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT a táblához `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `called_numbers`
--
ALTER TABLE `called_numbers`
  ADD CONSTRAINT `called_numbers_ibfk_1` FOREIGN KEY (`calling_code`) REFERENCES `country_calling_codes` (`calling_code`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `called_numbers_ibfk_2` FOREIGN KEY (`prefix`) REFERENCES `prefixes_hu` (`prefix`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `called_numbers_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `calling_numbers`
--
ALTER TABLE `calling_numbers`
  ADD CONSTRAINT `calling_numbers_ibfk_1` FOREIGN KEY (`calling_code`) REFERENCES `country_calling_codes` (`calling_code`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `calling_numbers_ibfk_2` FOREIGN KEY (`prefix`) REFERENCES `prefixes_hu` (`prefix`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `calling_numbers_ibfk_3` FOREIGN KEY (`type`) REFERENCES `call_types` (`type`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `calling_numbers_ibfk_4` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `incoming_calls`
--
ALTER TABLE `incoming_calls`
  ADD CONSTRAINT `incoming_calls_ibfk_1` FOREIGN KEY (`called_number_id`) REFERENCES `called_numbers` (`called_number_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `incoming_calls_ibfk_2` FOREIGN KEY (`calling_number_id`) REFERENCES `calling_numbers` (`calling_number_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `incoming_calls_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
