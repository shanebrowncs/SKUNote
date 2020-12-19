SET NAMES utf8mb4;

DROP TABLE IF EXISTS `divisions`;
CREATE TABLE `divisions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `divisions` (`id`, `name`) VALUES
(1,	'default');

DROP TABLE IF EXISTS `notetable`;
CREATE TABLE `notetable` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sku` int(11) NOT NULL,
  `user` varchar(128) NOT NULL,
  `note` varchar(512) NOT NULL,
  `date` datetime NOT NULL,
  `division` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `division` (`division`),
  CONSTRAINT `notetable_ibfk_1` FOREIGN KEY (`division`) REFERENCES `divisions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
