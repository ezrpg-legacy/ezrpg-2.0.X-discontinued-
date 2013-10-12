CREATE TABLE IF NOT EXISTS `<pre>route` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(255) NOT NULL,
  `base` varchar(255) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'literal',
  `module` varchar(255) NOT NULL,
  `params` text,
  `permission` TEXT DEFAULT NULL,
  `role` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `<pre>route` (`id`, `path`, `base`, `action`, `type`, `module`, `params`, `permission`, `role`) VALUES
(1, 'admin', NULL, NULL, 'literal', 'admin', NULL, 'canAdminCP', NULL),
(2, 'admin/player', 'admin', NULL, 'literal', 'player', NULL, 'canAdminCP,canAdminPlayers', NULL),
(3, 'admin/player/listing', 'admin', 'listing', 'literal', 'player', NULL, 'canAdminCP,canAdminPlayers', NULL),
(4, 'index(.*)', NULL, NULL, 'regex', 'index', 'act', NULL, NULL),
(5, 'error(/+.*)', NULL, 'index', 'regex', 'error', 'type', NULL, NULL),
(6, 'player/([a-z]+)', NULL, 'view', 'regex', 'player', 'username', NULL, NULL),
(7, 'login', NULL, NULL, 'literal', 'login', NULL, NULL, NULL),
(8, 'register', NULL, NULL, 'literal', 'register', NULL, NULL, NULL),
(9, 'home', NULL, NULL, 'literal', 'home', NULL, NULL, NULL),
(10, 'logout', NULL, 'logoutt', 'literal', 'login', NULL, NULL, NULL),
(11, 'admin/config', 'admin', NULL, 'literal', 'config', NULL, 'canAdminCP,canAdminConfg', NULL),
(12, 'admin/config/route', 'admin', 'route', 'literal', 'config', NULL, 'canAdminCP,canAdminConfig,canAdminRoute', NULL),
(13, 'admin/config/route(/+.*)', 'admin', 'editroute', 'regex', 'config', 'type', 'canAdminCP,canAdminConfig,canAdminRoute', NULL);