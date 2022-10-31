-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.11 - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL Version:             11.0.0.5919
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

USE `sportdb`;

TRUNCATE table `brand`;

LOAD DATA LOCAL INFILE '../brand.csv'
INTO TABLE `brand`
FIELDS TERMINATED BY ';'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
( `id`,
  `name`
)SET id = NULL;

SELECT COUNT(*) AS "Pocet riadkov produkcnej databazy sportdb.brand" FROM `sportdb`.`brand`;

-- testovacia databaza

USE `sportdbtest`;

TRUNCATE table `brand`;

LOAD DATA LOCAL INFILE '../brand.csv'
INTO TABLE `brand`
FIELDS TERMINATED BY ';'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
( `id`,
  `name`
)SET id = NULL;

SELECT COUNT(*) AS "Pocet riadkov testovacej databazy sportdb.brand" FROM `sportdb`.`brand`;
