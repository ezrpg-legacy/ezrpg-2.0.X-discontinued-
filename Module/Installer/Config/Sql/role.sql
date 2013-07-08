CREATE TABLE IF NOT EXISTS `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `role` (`id`, `title`) VALUES
(1, 'root'),
(2, 'administrator'),
(3, 'moderator'),
(4, 'member');