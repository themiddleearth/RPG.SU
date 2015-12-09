ALTER TABLE  `blog_comm` ADD INDEX (  `user_id` );
ALTER TABLE  `blog_rating` CHANGE  `rate_id`  `rate_id` INT( 50 ) UNSIGNED NOT NULL DEFAULT  '0';

ALTER TABLE  `combat` ADD  `extra` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT  '1';
ALTER TABLE  `combat_actions` ADD  `position` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT  '1';
ALTER TABLE  `combat_users` ADD  `hod_start` INT( 4 ) unsigned NOT NULL DEFAULT '0';
ALTER TABLE  `combat_users_state` CHANGE  `state`  `state` SMALLINT( 2 ) UNSIGNED NOT NULL DEFAULT  '0';
ALTER TABLE  `combat_users_state` CHANGE  `combat_id` `combat_id` INT( 30 ) unsigned NOT NULL DEFAULT '0';

ALTER TABLE  `craft_build_lumberjack` ADD INDEX (  `user_id` );
ALTER TABLE  `craft_build_stonemason` ADD INDEX (  `user_id` );
ALTER TABLE  `craft_resource` CHANGE  `incost`  `incost` DOUBLE( 10, 2 ) NOT NULL DEFAULT  '0',
                              CHANGE  `outcost`  `outcost` DOUBLE( 10, 2 ) NOT NULL DEFAULT  '0';

ALTER TABLE  `craft_resource_market` ADD  `sell_time` INT( 14 ) NOT NULL ;
ALTER TABLE  `craft_stat` ADD INDEX  `user_2` (  `user` );

ALTER TABLE  `forum_kat` DROP INDEX  `id` , ADD INDEX  `id` (  `id` ,  `moder` );
ALTER TABLE  `forum_kat` DROP INDEX  `id_3` , ADD INDEX  `id_3` (  `id` ,  `moder` ,  `clan` );

ALTER TABLE  `forum_setup` CHANGE  `thanks_count`  `thanks_count` INT( 10 ) UNSIGNED NOT NULL ,
                           CHANGE  `thanks_post`  `thanks_post` INT( 10 ) UNSIGNED NOT NULL ,
                           CHANGE  `say_thanks`  `say_thanks` INT( 10 ) UNSIGNED NOT NULL ,
                           CHANGE  `kol_posts`  `kol_posts` INT( 10 ) UNSIGNED NOT NULL;

ALTER TABLE  `game_battles` CHANGE  `contents`  `contents` VARCHAR( 400 ) NOT NULL;

CREATE TABLE IF NOT EXISTS `game_bot_chat_annoy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

CREATE TABLE IF NOT EXISTS `game_bot_chat_resp` (
  `id` varchar(50) NOT NULL COMMENT 'игрок',
  `count` int(11) NOT NULL COMMENT 'сколько раз спрашивал',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

ALTER TABLE  `game_chat_nakaz` DROP INDEX  `town`;
ALTER TABLE  `game_chat_nakaz` ADD INDEX (  `user_id` );

ALTER TABLE  `game_clans` CHANGE  `glava`  `glava` MEDIUMINT( 15 ) UNSIGNED NOT NULL DEFAULT  '0',
                          CHANGE  `zam1`  `zam1` MEDIUMINT( 15 ) UNSIGNED NOT NULL DEFAULT  '0',
                          CHANGE  `zam2`  `zam2` MEDIUMINT( 15 ) UNSIGNED NOT NULL DEFAULT  '0',
                          CHANGE  `zam3`  `zam3` MEDIUMINT( 15 ) UNSIGNED NOT NULL DEFAULT  '0',
                          CHANGE  `reg_time`  `reg_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                          ADD  `unreg_time` TIMESTAMP NOT NULL DEFAULT 0 AFTER  `reg_time`,
                          ADD  `alies` MEDIUMINT( 10 ) UNSIGNED NOT NULL DEFAULT  '0';

CREATE TABLE IF NOT EXISTS `game_items_complect` (
  `id` int(50) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int(50) unsigned NOT NULL,
  `complect_id` int(50) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `item_id` (`item_id`,`complect_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

ALTER TABLE  `game_shop` ADD  `amulet` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT  '0',
                         ADD  `amulet_store_current` INT( 7 ) UNSIGNED NOT NULL DEFAULT  '0',
                         ADD  `amulet_store_max` INT( 7 ) UNSIGNED NOT NULL DEFAULT  '0',
                         ADD  `perch` tinyint(1) unsigned NOT NULL DEFAULT '0',
                         ADD  `perch_store_current` int(7) unsigned NOT NULL DEFAULT '0',
                         ADD  `perch_store_max` int(7) unsigned NOT NULL DEFAULT '0',
                         ADD  `boots` tinyint(1) unsigned NOT NULL DEFAULT '0',
                         ADD  `boots_store_current` int(7) unsigned NOT NULL DEFAULT '0',
                         ADD  `boots_store_max` int(7) unsigned NOT NULL DEFAULT '0',
                         ADD  `shtan` tinyint(1) unsigned NOT NULL DEFAULT '0',
                         ADD  `shtan_store_current` int(7) unsigned NOT NULL DEFAULT '0',
                         ADD  `shtan_store_max` int(7) unsigned NOT NULL DEFAULT '0',
                         ADD  `naruchi` tinyint(1) unsigned NOT NULL DEFAULT '0',
                         ADD  `naruchi_store_current` int(7) unsigned NOT NULL DEFAULT '0',
                         ADD  `naruchi_store_max` int(7) unsigned NOT NULL DEFAULT '0',
                         ADD  `ukrash` tinyint(1) unsigned NOT NULL DEFAULT '0',
                         ADD  `ukrash_store_current` int(7) unsigned NOT NULL DEFAULT '0',
                         ADD  `ukrash_store_max` int(7) unsigned NOT NULL DEFAULT '0',
                         ADD  `magic_books` tinyint(1) unsigned NOT NULL DEFAULT '0',
                         ADD  `magic_books_store_current` int(7) unsigned NOT NULL DEFAULT '0',
                         ADD  `magic_books_store_max` int(7) unsigned NOT NULL DEFAULT '0',
                         ADD  `instrument` int(11) NOT NULL DEFAULT '0',
                         ADD  `instrument_store_max` int(11) NOT NULL DEFAULT '0',
                         ADD  `instrument_store_current` int(11) NOT NULL DEFAULT '0';

CREATE TABLE IF NOT EXISTS `query_log` (
  `timestamp` int(15) unsigned NOT NULL,
  `query` varchar(500) NOT NULL,
  `filename` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `quest_constructor` (
  `id` mediumint(5) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(15) unsigned NOT NULL,
  `item_id` int(50) unsigned NOT NULL,
  `create_time` int(14) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `create_time` (`create_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=40;