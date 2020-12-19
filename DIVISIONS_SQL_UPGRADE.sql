CREATE TABLE `divisions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `divisions` (`id`, `name`) VALUES (NULL, 'default');

ALTER TABLE `notetable` ADD COLUMN `division` INT(11) NOT NULL AFTER `date`;

SELECT @divid := min(id) FROM `divisions`;
UPDATE `notetable` SET `division`=@divid WHERE 1;

ALTER TABLE `notetable` ADD FOREIGN KEY (`division`) REFERENCES divisions(id);
