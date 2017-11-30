SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

DROP TABLE IF EXISTS `assets`;
CREATE TABLE IF NOT EXISTS `assets` (
  `id`  varchar(255) NOT NULL,
  `creation` timestamp,
  `lastChange` timestamp,
  `data` TEXT, PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;