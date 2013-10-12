CREATE TABLE if not exists `<pre>role_permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `<pre>role_permission` (`id`, `role_id`, `permission_id`) VALUES
(1, 2, 1),
(1, 2, 2);