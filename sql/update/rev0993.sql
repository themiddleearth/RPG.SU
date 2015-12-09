 ALTER TABLE `blog_friends` CHANGE `user_id` `user_id` MEDIUMINT( 15 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `friend_id` `friend_id` MEDIUMINT( 15 ) UNSIGNED NOT NULL DEFAULT '0';

CREATE TABLE IF NOT EXISTS `blog_closed` (
  `user_id` mediumint(15) unsigned NOT NULL default '0',
  `close_id` mediumint(15) unsigned NOT NULL default '0',
  KEY `user_id` (`user_id`),
  KEY `user_id_2` (`user_id`,`close_id`),
  KEY `close_id` (`close_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;