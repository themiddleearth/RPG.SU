CREATE TABLE IF NOT EXISTS `game_exchange` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `in_id` int(10) unsigned NOT NULL,
  `in_kol` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `in_gp` mediumint(5) unsigned NOT NULL DEFAULT '0',
  `out_id` int(10) unsigned NOT NULL,
  `out_kol` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `out_gp` mediumint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `in_id` (`in_id`),
  KEY `out_id` (`out_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Обменный пункт';

CREATE TABLE IF NOT EXISTS `game_exchange_groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(10) unsigned NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `item_type` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `kol` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`),
  KEY `item_id` (`item_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Группы обменного пункта';

CREATE TABLE IF NOT EXISTS `game_exchange_log` (
  `exchange_id` int(10) NOT NULL,
  `user_id` int(11) NOT NULL,  
  `times` mediumint(5) unsigned NOT NULL DEFAULT '1',
  `last_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE (`user_id`, `exchange_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Логирование использования обменного пункта';

CREATE TABLE IF NOT EXISTS `game_gambling` (  
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,  
  `prize_id` int(10) NOT NULL,
  `prize_type` int(10) NOT NULL,
  `last_time` int(15),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `prize_id` (`prize_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Логирование использования шатра азарта';

ALTER TABLE  `game_users` 
ADD `injury` SMALLINT NOT NULL DEFAULT '0',
ADD `injury_time` INT( 15 ) NOT NULL DEFAULT '0';

ALTER TABLE  `game_users_archive` 
ADD `injury` SMALLINT NOT NULL DEFAULT '0',
ADD `injury_time` INT( 15 ) NOT NULL DEFAULT '0';

CREATE or REPLACE VIEW `view_active_users` AS select `game_users`.`user_id` AS `user_id`,`game_users`.`user_name` AS `user_name`,`game_users`.`user_pass` AS `user_pass`,`game_users`.`name` AS `name`,`game_users`.`HP` AS `HP`,`game_users`.`HP_MAX` AS `HP_MAX`,`game_users`.`MP` AS `MP`,`game_users`.`MP_MAX` AS `MP_MAX`,`game_users`.`STM` AS `STM`,`game_users`.`STM_MAX` AS `STM_MAX`,`game_users`.`PR` AS `PR`,`game_users`.`PR_MAX` AS `PR_MAX`,`game_users`.`EXP` AS `EXP`,`game_users`.`GP` AS `GP`,`game_users`.`STR` AS `STR`,`game_users`.`NTL` AS `NTL`,`game_users`.`PIE` AS `PIE`,`game_users`.`VIT` AS `VIT`,`game_users`.`DEX` AS `DEX`,`game_users`.`SPD` AS `SPD`,`game_users`.`CW` AS `CW`,`game_users`.`CC` AS `CC`,`game_users`.`race` AS `race`, `game_users`.`complects` AS `complects`, `game_users`.`avatar` AS `avatar`,`game_users`.`lucky` AS `lucky`,`game_users`.`clevel` AS `clevel`, `game_users`.`reinc` AS `reinc`, `game_users`.`bound` AS `bound`,`game_users`.`exam` AS `exam`,`game_users`.`vsadnik` AS `vsadnik`,`game_users`.`win` AS `win`,`game_users`.`lose` AS `lose`,`game_users`.`clan_id` AS `clan_id`,`game_users`.`dvij` AS `dvij`,`game_users`.`view_chat` AS `view_chat`,`game_users`.`minestone` AS `minestone`,`game_users`.`minestonetime` AS `minestonetime`,`game_users`.`mineore` AS `mineore`,`game_users`.`mineoretime` AS `mineoretime`,`game_users`.`minewood` AS `minewood`,`game_users`.`minewoodtime` AS `minewoodtime`,`game_users`.`clan_items_old` AS `clan_items_old`,`game_users`.`STR_MAX` AS `STR_MAX`,`game_users`.`NTL_MAX` AS `NTL_MAX`,`game_users`.`PIE_MAX` AS `PIE_MAX`,`game_users`.`VIT_MAX` AS `VIT_MAX`,`game_users`.`DEX_MAX` AS `DEX_MAX`,`game_users`.`SPD_MAX` AS `SPD_MAX`,`game_users`.`x` AS `x`,`game_users`.`y` AS `y`,`game_users`.`sector` AS `sector`,`game_users`.`view_smile` AS `view_smile`,`game_users`.`view_img` AS `view_img`,`game_users`.`hide` AS `hide`,`game_users`.`arcomage` AS `arcomage`,`game_users`.`arcomage_win` AS `arcomage_win`,`game_users`.`arcomage_lose` AS `arcomage_lose`,`game_users`.`maze_win` AS `maze_win`,`game_users`.`HP_MAXX` AS `HP_MAXX`,`game_users`.`stroitel` AS `stroitel`,`game_users`.`sobiratel` AS `sobiratel`,`game_users`.`minemetal` AS `minemetal`,`game_users`.`alchemist` AS `alchemist`,`game_users`.`stroiteltime` AS `stroiteltime`,`game_users`.`alchemisttime` AS `alchemisttime`,`game_users`.`sobirateltime` AS `sobirateltime`,`game_users`.`minemetaltime` AS `minemetaltime`,`game_users`.`lucky_max` AS `lucky_max`,`game_users`.`sklon` AS `sklon`, `game_users`.`hide_charges` AS `hide_charges`, `game_users`.`injury` AS `injury`, `game_users`.`injury_time` AS `injury_time`,`game_users_active_delay`.`delay` AS `delay`,`game_users_active_delay`.`delay_reason` AS `delay_reason`, `game_users_active_delay`.`block` AS `block` from (`game_users` join (`game_users_active` join `game_users_active_delay`)) where ((`game_users`.`user_id` = `game_users_active`.`user_id`) and (`game_users`.`user_id` = `game_users_active_delay`.`user_id`) and (`game_users_active`.`last_active` > (unix_timestamp() - 300)));

ALTER TABLE `combat_users` 
ADD `injury` SMALLINT( 3 ) NOT NULL DEFAULT '0' AFTER `lucky`;

CREATE TABLE IF NOT EXISTS `game_items_type` (  
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,  
  `counts` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Типы предметов';


INSERT INTO `game_items_type` (`id`, `name`, `counts`) VALUES
(1, 'Îðóæèå', 0),
(2, 'Êîëüöî', 0),
(3, 'Àðòåôàêò', 0),
(4, 'Ùèò', 0),
(5, 'Äîñïåõ', 0),
(6, 'Øëåì', 0),
(7, 'Ìàãèÿ', 0),
(8, 'Ïîÿñ', 0),
(9, 'Îæåðåëüå', 0),
(10, 'Ïåð÷àòêè', 0),
(11, 'Îáóâü', 0),
(12, 'Ñâèòêè', 1),
(13, 'Ýëèêñèðû', 1),
(14, 'Ïîíîæè', 0),
(15, 'Íàðó÷è', 0),
(16, 'Óêðàøåíèÿ', 0),
(17, 'Ìàãè÷åñêèå êíèãè', 0),
(18, 'Ëóêè', 0),
(19, 'Ìåòàòåëüíîå îðóæèå', 1),
(20, 'Ñõåìà èçãîòîâëåíèÿ âåùè', 0),
(21, 'Ñòðåëû', 1),
(22, 'Ðóíû', 1),
(23, 'Êîìïëåêòû', 0),
(24, 'Èíñòðóìåíò', 0),
(95, 'Êâåñòîâûé ïðåäìåò', 0),
(97, 'Ïðî÷åå', 1),
(98, '×àñòü óáèòîãî ìîíñòðà', 0),
(99, 'WM', 0);
 