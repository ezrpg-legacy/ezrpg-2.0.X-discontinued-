CREATE TABLE IF NOT EXISTS `<pre>route` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(255) NOT NULL,
  `base` varchar(255) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `module` varchar(255) NOT NULL,
  `params` text,
  `permission` TEXT DEFAULT NULL,
  `role` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `<pre>route` (`id`, `path`, `base`, `action`, `type`, `module`, `params`, `permission`) VALUES
(1, 'admin', NULL, NULL, NULL, 'admin', NULL, 'canAdminCP'),
(2, 'admin/player', 'admin', NULL, NULL, 'player', NULL, 'canAdminCP,canAdminPlayers'),
(3, 'admin/player/listing', 'admin', 'listing', NULL, 'player', NULL, 'canAdminCP,canAdminPlayers'),
(4, 'index(.*)', NULL, NULL, 'regex', 'index', 'act', NULL),
(5, 'error(/+.*)', NULL, 'index', 'regex', 'error', 'type', NULL),
(6, 'player/([a-z]+)', NULL, 'view', 'regex', 'player', 'username', NULL),
(7, 'login', NULL, NULL, NULL, 'login', NULL, NULL),
(8, 'register', NULL, NULL, NULL, 'register', NULL, NULL),
(9, 'home', NULL, NULL, NULL, 'home', NULL, NULL),
(10, 'logout', NULL, 'logoutt', NULL, 'login', NULL, NULL);