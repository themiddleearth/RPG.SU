ALTER TABLE `arcomage_history` ADD `fall` tinyint(1) unsigned NOT NULL DEFAULT '0';

ALTER TABLE `arcomage_history` ADD INDEX ( `user_id` );

ALTER TABLE `arcomage_users` ADD INDEX ( `user_id` ) ;

ALTER TABLE `arcomage_users_cards` ADD INDEX ( `user_id` ) ;

ALTER TABLE `blog_comm` CHANGE `comm_id` `comm_id` INT( 50 ) UNSIGNED NOT NULL AUTO_INCREMENT ;

ALTER TABLE `blog_comm` CHANGE `post_id` `post_id` INT( 50 ) UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `blog_comm` CHANGE `user_id` `user_id` MEDIUMINT( 15 ) UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `blog_love` CHANGE `user_id` `user_id` MEDIUMINT( 15 ) UNSIGNED NOT NULL DEFAULT '0', CHANGE `friend_id` `friend_id` MEDIUMINT( 15 ) UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `blog_post` CHANGE `post_id` `post_id` INT( 50 ) UNSIGNED NOT NULL AUTO_INCREMENT ;

ALTER TABLE `blog_post` CHANGE `user_id` `user_id` MEDIUMINT( 15 ) UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `blog_rating` CHANGE `rate` `rate` INT( 1 ) UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `blog_users` CHANGE `user_id` `user_id` MEDIUMINT( 15 ) UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `blog_users` CHANGE `prosm` `prosm` INT( 40 ) UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `blog_users` CHANGE `status` `status` INT( 1 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `lastcomm` `lastcomm` CHAR( 14 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
CHANGE `lastadd` `lastadd` INT( 15 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `zap` `zap` INT( 40 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `comments` `comments` INT( 40 ) UNSIGNED NOT NULL DEFAULT '0';

DROP TABLE `combat_actions` ;

CREATE TABLE `combat_actions` (
  `combat_id` int(30) unsigned NOT NULL,
  `hod` smallint(5) unsigned NOT NULL,
  `user_id` mediumint(10) unsigned NOT NULL,
  `action_type` tinyint(3) unsigned NOT NULL COMMENT '11 - атака кулаком, 12 - атака оружием, 13 - атака магией, 14 - атака артефактом, 21 - защита щитом, 22 - защита магией, 23 - защита артефактом, 31 - лечение магией, 32 - лечение артефактом, 33 - лечение эликсиром',
  `action_chem` int(11) NOT NULL,
  `action_kogo` mediumint(15) NOT NULL,
  `action_kuda` tinyint(4) NOT NULL,
  `action_proc` tinyint(4) NOT NULL,
  `action_priem` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT 'вариант боевого приема',
  `action_rand` int(9) unsigned NOT NULL COMMENT 'случайное число - порядок действия в расчете хода',
  `action_type_sort` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1 - атака, 2 - защита, 3 - лечение (для сортировки в обсчете хода)',
  KEY `combat_id` (`combat_id`,`hod`,`action_type`,`action_rand`),
  KEY `combat_id_2` (`combat_id`,`hod`,`user_id`),
  KEY `user_id` (`user_id`),
  KEY `combat_id_3` (`combat_id`,`hod`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `combat_locked` (
  `combat_id` int(30) unsigned NOT NULL DEFAULT '0',
  `hod` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`combat_id`,`hod`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

ALTER TABLE `combat_lose_user` ADD INDEX ( `user_id` ) ;

ALTER TABLE `combat_users` ADD `HP_start` mediumint(4) unsigned NOT NULL;

ALTER TABLE `combat_users_exp` ADD INDEX ( `prot_id` ) ;

ALTER TABLE `craft_build_rab` ADD `opt` mediumint(5) unsigned NOT NULL;

ALTER TABLE `craft_build_rab` ADD INDEX ( `user_id` ) ;

ALTER TABLE `craft_build_user` ADD INDEX ( `user_id` ) ;

ALTER TABLE `craft_resource` CHANGE `weight` `weight` DOUBLE( 9, 4 ) UNSIGNED NOT NULL ;

ALTER TABLE `craft_resource_market` ADD INDEX ( `user_id` ) ;

ALTER TABLE `craft_resource_user` CHANGE `user_id` `user_id` INT( 15 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `res_id` `res_id` INT( 2 ) UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `craft_resource_user` CHANGE `col` `col` INT( 11 ) NOT NULL DEFAULT '0';

ALTER TABLE `craft_stat` ADD INDEX ( `dat` ) ;

ALTER TABLE `forum_kat` CHANGE `name` `name` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
CHANGE `text` `text` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ;

ALTER TABLE `forum_kat` ADD `moder` varchar(255) NOT NULL DEFAULT '';

ALTER TABLE `forum_kat` ADD INDEX ( `id` , `main_id` ) ;

ALTER TABLE `forum_name` DROP `rank`;

ALTER TABLE `forum_setup` ADD `thanks_count` int(9) unsigned NOT NULL,
 ADD  `thanks_post` int(9) unsigned NOT NULL,
 ADD  `say_thanks` int(9) unsigned NOT NULL,
 ADD  `kol_posts` int(20) unsigned NOT NULL;
 
 CREATE TABLE `forum_thanks` (
  `topic_id` int(11) unsigned NOT NULL,
  `post_id` int(11) unsigned NOT NULL,
  `user_id` mediumint(15) unsigned NOT NULL,
  PRIMARY KEY (`topic_id`,`post_id`,`user_id`),
  KEY `post_id` (`post_id`,`user_id`),
  KEY `topic_id` (`topic_id`,`user_id`),
  KEY `topic_id_2` (`topic_id`),
  KEY `post_id_2` (`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

ALTER TABLE `forum_topics` ADD INDEX ( `last_date` ) ;

DROP TABLE `game_admins`;

CREATE TABLE `game_admins` (
  `user_id` mediumint(15) unsigned NOT NULL DEFAULT '0',
  `online` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `teleport` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `news` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `zakon` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `help` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `stat` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ban` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `unban` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `pech` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `unpech` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `lab` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `unlab` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `del` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `npc` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `map` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `items` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `gorod` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `shop` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `log_war` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `log_adm` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `mag` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `pm` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `forum` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `spets` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `users` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `tavern` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `search_users` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `medal` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `search_items` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `log_war_today` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `nakaz` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `privat` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `chat` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `koni` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `resource` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `mine` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `bot_combat` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `bot_chat` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `bank` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `quest` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `hide` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  KEY `hide` (`hide`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

ALTER TABLE `game_bank` CHANGE `summa` `summa` DOUBLE( 15, 2 ) UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `game_chat_option` CHANGE `frame` `frame` INT( 5 ) UNSIGNED NOT NULL DEFAULT '220';

ALTER TABLE `game_combats_log_data` CHANGE `action` `action` TINYINT( 1 ) UNSIGNED NOT NULL ,
CHANGE `add_hp` `add_hp` SMALLINT( 4 ) UNSIGNED NOT NULL ,
CHANGE `add_mp` `add_mp` SMALLINT( 4 ) UNSIGNED NOT NULL ,
CHANGE `add_stm` `add_stm` SMALLINT( 4 ) UNSIGNED NOT NULL ,
CHANGE `minus_hp` `minus_hp` SMALLINT( 4 ) UNSIGNED NOT NULL ,
CHANGE `minus_mp` `minus_mp` SMALLINT( 4 ) UNSIGNED NOT NULL ,
CHANGE `minus_stm` `minus_stm` SMALLINT( 4 ) UNSIGNED NOT NULL ,
CHANGE `sort` `sort` TINYINT( 1 ) UNSIGNED NOT NULL ;

CREATE TABLE `game_constants` (
  `name` varchar(30) NOT NULL,
  `value` varchar(200) NOT NULL,
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE `game_houses`;

DROP TABLE `game_houses_add`;

DROP TABLE `game_houses_market`;

DROP TABLE `game_houses_nalog`;

ALTER TABLE `game_items` CHANGE `item_uselife` `item_uselife` DOUBLE( 10, 2 ) NOT NULL DEFAULT '100.00' COMMENT 'Степень изношенности предмета';

DROP TRIGGER IF EXISTS `ageofwar_game`.`set_ref_id_insert`;
DELIMITER //
CREATE TRIGGER `ageofwar_game`.`set_ref_id_insert` BEFORE INSERT ON `ageofwar_game`.`game_items`
 FOR EACH ROW BEGIN
    DECLARE item_type INT;
    SELECT `type` INTO item_type FROM game_items_factsheet WHERE id=NEW.item_id LIMIT 1;
    IF (item_type=12) THEN SET NEW.ref_id=0; END IF;
    IF (item_type=13) THEN SET NEW.ref_id=0; END IF;
    IF (item_type=14) THEN SET NEW.ref_id=0; END IF;
    IF (item_type=19) THEN SET NEW.ref_id=0; END IF;
    IF (item_type=20) THEN SET NEW.ref_id=0; END IF;
    IF (item_type=21) THEN SET NEW.ref_id=0; END IF;
    END
//
DELIMITER ;

ALTER TABLE `game_items_factsheet` CHANGE `type` `type` MEDIUMINT( 8 ) UNSIGNED NOT NULL ,
CHANGE `weight` `weight` DOUBLE( 7, 2 ) NOT NULL DEFAULT '0',
CHANGE `cooldown` `cooldown` INT( 10 ) UNSIGNED NOT NULL COMMENT 'время между зарядками артефакта',
CHANGE `def_type` `def_type` SMALLINT( 1 ) UNSIGNED NOT NULL ;

DROP TABLE `game_nakaz`;

CREATE TABLE `game_nakaz` (
  `user_id` mediumint(15) unsigned NOT NULL DEFAULT '0',
  `nakaz` enum('ban','prison') NOT NULL DEFAULT 'ban',
  `date_nak` int(14) unsigned NOT NULL DEFAULT '0',
  `date_zak` int(10) unsigned NOT NULL DEFAULT '0',
  `adm` mediumint(15) unsigned NOT NULL DEFAULT '0',
  `text` varchar(400) NOT NULL,
  `id` mediumint(15) unsigned NOT NULL AUTO_INCREMENT,
  `id_zakon` mediumint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `user_id_2` (`user_id`,`date_nak`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

ALTER TABLE `game_obelisk_users` ADD `user_name` varchar(50) NOT NULL DEFAULT '';

ALTER TABLE `game_port` CHANGE `town_from` `town_from` INT( 10 ) UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `game_shop` CHANGE `prod` `prod` TINYINT(1) NOT NULL, CHANGE `remont` `remont` TINYINT(1) NOT NULL, CHANGE `ident` `ident` TINYINT(1) NOT NULL, CHANGE `shlem` `shlem` TINYINT(1) NOT NULL, CHANGE `oruj` `oruj` TINYINT(1) NOT NULL, CHANGE `dosp` `dosp` TINYINT(1) NOT NULL, CHANGE `shit` `shit` TINYINT(1) NOT NULL, CHANGE `pojas` `pojas` TINYINT(1) NOT NULL, CHANGE `mag` `mag` TINYINT(1) NOT NULL, CHANGE `ring` `ring` TINYINT(1) NOT NULL, CHANGE `artef` `artef` TINYINT(1) NOT NULL, CHANGE `other` `other` TINYINT(1) NOT NULL, CHANGE `view` `view` TINYINT(1) NOT NULL DEFAULT '1', CHANGE `svitki` `svitki` TINYINT(1) NOT NULL, CHANGE `eliksir` `eliksir` TINYINT(1) NOT NULL DEFAULT '0', CHANGE `schema` `schema` TINYINT(1) NOT NULL DEFAULT '0', CHANGE `luk` `luk` TINYINT(1) NOT NULL DEFAULT '0';

ALTER TABLE `game_turnir_users` ADD `from_boy` tinyint(1) unsigned NOT NULL DEFAULT '0';

ALTER TABLE `game_users_data` CHANGE `last_timeout` `last_timeout` INT( 15 ) UNSIGNED NOT NULL ;

