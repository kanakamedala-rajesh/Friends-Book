CREATE TABLE `cs_profiles` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `first_name` varchar(255) NOT NULL default '',
  `last_name` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `password` varchar(40) NOT NULL default '',
  `salt` varchar(10) NOT NULL default '',
  `status` enum('active','passive') NOT NULL default 'active',
  `role` tinyint(4) unsigned NOT NULL default '1',
  `about` varchar(255) NOT NULL,
  `date_reg` datetime NOT NULL default '0000-00-00 00:00:00',
  `date_nav` datetime NOT NULL default '0000-00-00 00:00:00',
  `color` varchar(6) NOT NULL,
  `rate` float NOT NULL,
  `rate_count` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `cs_profiles` (`name`, `first_name`, `last_name`, `email`, `password`, `salt`, `status`, `role`, `about`, `date_reg`, `rate`, `rate_count`) VALUES
('test user', 'test user first name', 'test user last name', 'user@user.com', 'b88c654d6c68fc37f4dda1d29935235eea9a845b', 'testing', 'active', 1, 'Few words about test user', '2012-01-14 00:00:00', 0, 0),
('moderator', 'moderator first name', 'moderator last name', 'moderator@moderator.com', 'b88c654d6c68fc37f4dda1d29935235eea9a845b', 'testing', 'active', 4, 'Few words about moderator', '2012-01-14 00:00:00', 0, 0),
('admin', 'admin first name', 'admin last name', 'admin@admin.com', 'b88c654d6c68fc37f4dda1d29935235eea9a845b', 'testing', 'active', 5, 'Few words about admin', '2012-01-14 00:00:00', 0, 0);


CREATE TABLE `cs_messages` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `sender` int(11) unsigned NOT NULL,
  `recipient` int(11) unsigned NOT NULL default '0',
  `message` VARCHAR(255) NOT NULL,
  `when` int(11) NOT NULL default '0',
  `room` int(5) unsigned NOT NULL default '0',
  `type` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY (`id`) 
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE `cs_profiles_vote_track` (
  `pid` int(11) unsigned NOT NULL default '0',
  `ip` varchar(20) default NULL,
  `date` datetime default NULL,
  KEY `uip` (`ip`,`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE `cs_rooms` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `title` VARCHAR(255) NOT NULL,
  `owner` int(11) unsigned NOT NULL,
  `when` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`) 
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `cs_rooms` (`id`, `title`, `owner`, `when`) VALUES
(NULL, 'Room1', 3, 1338293810),
(NULL, 'Room2', 3, 1338293811),
(NULL, 'Room3', 3, 1338293812);