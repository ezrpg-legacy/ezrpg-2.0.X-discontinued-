CREATE TABLE if not exists `<pre>attribute` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `<pre>attribute` (`id`, `title`) VALUES
(1, 'Strength'),
(2, 'Dexterity'),
(3, 'Endurance'),
(4, 'Intelligence'),
(5, 'Education'),
(6, 'Social Standing');