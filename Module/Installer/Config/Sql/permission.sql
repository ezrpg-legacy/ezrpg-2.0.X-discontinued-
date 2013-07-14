CREATE TABLE if not exists `<pre>permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `<pre>permission` (`id`, `title`) VALUES
(1, 'hello'),
(2, 'kitty');