
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


CREATE DATABASE IF NOT EXISTS `rentcar` DEFAULT CHARACTER SET utf8mb4 ;
USE `rentcar`;


DROP TABLE IF EXISTS `agency`;
CREATE TABLE `agency` (
  `id_agency` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4  DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;


TRUNCATE TABLE `agency`;


INSERT INTO `agency` (`id_agency`, `name`, `address`, `image`) VALUES
(1, 'BTZ Agency- Wild Car Rental', '27 Route de Pitoys, 64600 Anglet', ''),
(2, 'BDX Agency- Wild Car Rental', '20 av de la Marne, 33000 Bordeaux', ''),
(3, 'LDN Agency- Wild Car Rental', '45 abbey road - 4800 London', '');


DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `id_category` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `category` varchar(100) NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4  DEFAULT NULL,
  `price` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;


TRUNCATE TABLE `category`;


INSERT INTO `category` (`id_category`, `category`, `description`, `price`) VALUES
(1, 'A+', 'Super Luxury and limos', 320),
(2, 'A', 'High level', 150),
(3, 'B', 'Standard', 65),
(4, 'C', 'Economy', 20);


DROP TABLE IF EXISTS `rent`;
CREATE TABLE `rent` (
  `id_rent` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `vehicle_id` int NOT NULL,
  `reduction_percent` float DEFAULT NULL,
  `date_creation` date NOT NULL,
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  `total_amount` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;




DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id_user` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4,
  `role` enum('admin','user') CHARACTER SET utf8mb4  NOT NULL DEFAULT 'user',
  `creation_date` date DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;


TRUNCATE TABLE `user`;


INSERT INTO `user` (`id_user`, `firstname`, `lastname`, `birthdate`, `address`, `role`, `creation_date`, `email`, `password`) VALUES
(1, 'laurence', 'Idiart', NULL, NULL, 'admin', NULL, 'laurence.idiart@gmail.com', 'lolo'),
(2, 'anne', 'Claverie', NULL, NULL, 'admin', NULL, 'anne.claverie@gmail.com', 'annepassword'),
(3, 'thierry', 'Hirschland', NULL, NULL, 'admin', NULL, 'thierry.hirschland@laposte.net', 'thpassword'),
(4, 'liudmyla', 'Duvivier', NULL, NULL, 'admin', NULL, 'liudmyla.duvivier@gmail.com', 'liudmylapassword'),
(5, 'mickey', 'Mouse', '1932-04-01', '1024, 5th avenue - New York City, USA', 'user', '1934-04-02', 'mickey.mouse@gmail.com', 'mimi'),
(6, 'mimi', 'Souris', '1935-12-25', '3 bd de la mer, 64100 Biarritz', 'user', '2021-04-02', 'mimi.souris@gmail.com', 'mickey');



DROP TABLE IF EXISTS `vehicle`;
CREATE TABLE `vehicle` (
  `id_vehicle` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `category_id` int NOT NULL,
  `agency_id` int NOT NULL,
  `brand` varchar(255) NOT NULL,
  `model` varchar(255) NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4  DEFAULT NULL,
  `color` varchar(255) CHARACTER SET utf8mb4  DEFAULT NULL,
  `gear_box` enum('Manual','Auto') CHARACTER SET utf8mb4  DEFAULT 'Manual',
  `energy` enum('Electric','Gasoil','Petrol','Solar') CHARACTER SET utf8mb4  NOT NULL DEFAULT 'Gasoil',
  `description` varchar(255) CHARACTER SET utf8mb4  DEFAULT NULL,
  `date_entry` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;



TRUNCATE TABLE `vehicle`;


INSERT INTO `vehicle` (`id_vehicle`, `category_id`, `agency_id`, `brand`, `model`, `image`, `color`, `gear_box`, `energy`, `description`, `date_entry`) VALUES
(1, 1, 1, 'Bugatti', 'Type 17', 'https://stickers-muraux-enfant.fr/5148-large_default/sticker-autocollant-voiture-bugatti-veyron-bleu-sport-130x83-cm-bugatti-veyron-b.jpg', 'Red', 'Manual', '', '8 soupapes - 1910 - collector', '2021-04-05 09:21:43'),
(2, 2, 1, 'Peugeot', '208 C++', 'https://cdn.motor1.com/images/mgl/PpkJG/s3/peugeot-308-rendering.jpg', 'Red', 'Manual', '', 'Cabriolet - 4 places - pas de coffre', '2021-04-05 09:21:43'),
(3, 2, 1, 'Volkwagen', 'Golf 9S -16V', 'https://offers.volkswagen.be/sites/vw_offers/files/styles/car_overview/public/offer/overview-main/polo-default.png?itok=YKuT2avZ', 'Dark Black', 'Manual', '', 'Modèle Sport', '2021-04-05 09:21:43'),
(4, 2, 1, 'Audi', 'Q12', 'https://www.turbo.fr/sites/default/files/2020-09/audi-a3-sportback-40-tfsi-e-2020.jpg', 'White', 'Auto', '', 'modèle familial', '2021-04-05 09:21:43');



ALTER TABLE `rent`
  ADD CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `vehicle_id` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicle` (`id_vehicle`);


ALTER TABLE `vehicle`
  ADD CONSTRAINT `agency_id` FOREIGN KEY (`agency_id`) REFERENCES `agency` (`id_agency`),
  ADD CONSTRAINT `category_id` FOREIGN KEY (`category_id`) REFERENCES `category` (`id_category`);
COMMIT;



