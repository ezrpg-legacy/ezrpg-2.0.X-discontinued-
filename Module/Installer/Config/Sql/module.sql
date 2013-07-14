CREATE TABLE if not exists `<pre>module` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `<pre>module` (`id`, `title`) VALUES
(1, 'Index'),
(2, 'Home'),
(3, 'Login'),
(4, 'Register');