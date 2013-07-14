CREATE TABLE if not exists `<pre>player` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Player ID',
  `title` varchar(20) DEFAULT NULL COMMENT 'Player name/alias',
  `username` varchar(40) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `salt` text NOT NULL,
  `lastActive` datetime DEFAULT NULL COMMENT 'When player was last active',
  `registered` datetime DEFAULT NULL COMMENT 'When the playered registered',
  `active` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Whether the player is active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;