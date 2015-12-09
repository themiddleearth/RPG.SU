ALTER TABLE `game_maps`
  DROP `boy_auto`,
  DROP `boy_auto_type`,
  DROP `join_auto`;
  
ALTER TABLE `game_maps` ADD `boy_type7` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `game_npc` ADD `npc_win` INT( 10 ) UNSIGNED NOT NULL ;

DROP TABLE `combat`, `combat_chat`, `combat_history`, `combat_lose_user`, `combat_new_user`, `combat_shed`, `combat_users`, `combat_users_exp`, `combat_user_func`;

-- --------------------------------------------------------

--
-- Структура таблицы `combat`
--

CREATE TABLE IF NOT EXISTS `combat` (
  `combat_id` int(30) unsigned NOT NULL auto_increment COMMENT 'id боя',
  `hod` smallint(5) unsigned NOT NULL COMMENT 'текущий ход боя',
  `combat_type` tinyint(1) unsigned NOT NULL default '1' COMMENT 'тип боя',
  `time_last_hod` int(14) unsigned NOT NULL default '0' COMMENT 'timestamp последнего расчета хода',
  `map_name` smallint(3) unsigned NOT NULL default '0' COMMENT 'координаты боя',
  `map_xpos` tinyint(3) unsigned NOT NULL default '0',
  `map_ypos` tinyint(3) unsigned NOT NULL default '0',
  `start_time` int(14) unsigned NOT NULL default '0' COMMENT 'время начало боя',
  PRIMARY KEY  (`combat_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Структура таблицы `combat_actions`
--

CREATE TABLE IF NOT EXISTS `combat_actions` (
  `combat_id` int(30) unsigned NOT NULL,
  `hod` smallint(5) unsigned NOT NULL,
  `user_id` mediumint(10) unsigned NOT NULL,
  `action_type` tinyint(3) unsigned NOT NULL COMMENT '11 - атака кулаком, 12 - атака оружием, 13 - атака магией, 14 - атака артефактом, 21 - защита щитом, 22 - защита магией, 23 - защита артефактом, 31 - лечение магией, 32 - лечение артефактом, 33 - лечение эликсиром',
  `action_chem` int(11) NOT NULL,
  `action_kogo` mediumint(15) NOT NULL,
  `action_kuda` tinyint(4) NOT NULL,
  `action_proc` tinyint(4) NOT NULL,
  `action_priem` tinyint(2) unsigned NOT NULL default '0' COMMENT 'вариант боевого приема',
  `action_rand` int(9) unsigned NOT NULL COMMENT 'случайное число - порядок действия в расчете хода',
  KEY `combat_id` (`combat_id`,`hod`,`action_type`,`action_rand`),
  KEY `combat_id_2` (`combat_id`,`hod`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Структура таблицы `combat_lose_user`
--

CREATE TABLE IF NOT EXISTS `combat_lose_user` (
  `combat_id` int(20) unsigned NOT NULL default '0',
  `user_id` int(20) unsigned NOT NULL default '0',
  `host` int(15) NOT NULL default '0',
  `host_more` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`combat_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Структура таблицы `combat_users`
--

CREATE TABLE IF NOT EXISTS `combat_users` (
  `user_id` mediumint(15) unsigned NOT NULL COMMENT 'id игрока',
  `npc` tinyint(1) unsigned NOT NULL default '0' COMMENT 'признак записи по NPC',
  `time_last_active` int(14) unsigned NOT NULL default '0' COMMENT 'timestamp последнего действия игрока',
  `join` tinyint(1) unsigned NOT NULL default '0' COMMENT '0 - игрок уже ходит в бою, 1 - игрок присоединился к бою и текущий ход он не ходит',
  `name` varchar(50) NOT NULL,
  `clevel` smallint(3) unsigned NOT NULL default '0',
  `clan_id` mediumint(10) unsigned NOT NULL default '0',
  `combat_id` int(30) unsigned NOT NULL,
  `eliksir` tinyint(1) unsigned NOT NULL default '0' COMMENT 'признак использования эликсира в бою',
  `call_clan` tinyint(1) unsigned NOT NULL default '0' COMMENT 'признак вызова игроков клана в бой',
  `side` smallint(3) NOT NULL default '0' COMMENT 'поле, по которому определяются союзники',
  `svitok` tinyint(1) unsigned NOT NULL default '0' COMMENT 'номер свитка по которому игрок вошел в бой',
  `k_komu` mediumint(15) unsigned NOT NULL default '0' COMMENT 'id игрока, к которому присоединился свитком',
  `HP` mediumint(4) NOT NULL,
  `HP_MAX` mediumint(4) NOT NULL,
  `MP` mediumint(4) NOT NULL,
  `MP_MAX` mediumint(4) NOT NULL,
  `STM` mediumint(4) NOT NULL,
  `STM_MAX` mediumint(4) NOT NULL,
  `STR` mediumint(4) NOT NULL,
  `DEX` mediumint(4) NOT NULL,
  `SPD` mediumint(4) NOT NULL,
  `VIT` mediumint(4) NOT NULL,
  `NTL` mediumint(4) NOT NULL,
  `PIE` mediumint(4) NOT NULL,
  `lucky` mediumint(4) NOT NULL,
  `MS_KULAK` smallint(3) unsigned NOT NULL,
  `MS_WEAPON` smallint(3) unsigned NOT NULL,
  `MS_ART` smallint(3) unsigned NOT NULL,
  `MS_PARIR` smallint(3) unsigned NOT NULL,
  `MS_LUK` smallint(3) unsigned NOT NULL,
  `MS_SWORD` smallint(3) unsigned NOT NULL,
  `MS_AXE` smallint(3) unsigned NOT NULL,
  `MS_SPEAR` smallint(3) unsigned NOT NULL,
  `pol` enum('','male','female') NOT NULL COMMENT 'пол игрока',
  `avatar` varchar(50) NOT NULL,
  `pass` tinyint(1) unsigned NOT NULL default '0' COMMENT 'признак пропуска хода (не используется)',
  `sklon` tinyint(1) unsigned NOT NULL default '0' COMMENT 'склонность игрока',
  `not_exp` tinyint(1) unsigned NOT NULL default '0' COMMENT 'не давать опыт за бой',
  `not_gp` tinyint(1) unsigned NOT NULL default '0' COMMENT 'не давать денег за бой',
  `race` varchar(30) NOT NULL,
  PRIMARY KEY  (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Структура таблицы `combat_users_exp`
--

CREATE TABLE IF NOT EXISTS `combat_users_exp` (
  `user_id` mediumint(15) unsigned NOT NULL default '0',
  `combat_id` int(20) unsigned NOT NULL default '0',
  `exp` int(10) unsigned NOT NULL default '0',
  `gp` double(6,2) unsigned NOT NULL default '0.00',
  `prot_id` mediumint(15) unsigned NOT NULL default '0',
  UNIQUE KEY `user_id` (`user_id`,`combat_id`,`prot_id`),
  KEY `combat_id` (`combat_id`),
  KEY `user_id_2` (`user_id`),
  KEY `combat_id_2` (`combat_id`,`prot_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Структура таблицы `combat_users_state`
--

CREATE TABLE IF NOT EXISTS `combat_users_state` (
  `user_id` mediumint(15) unsigned NOT NULL COMMENT 'id игрока',
  `state` smallint(2) unsigned NOT NULL,
  `combat_id` int(30) unsigned NOT NULL COMMENT 'id боя',
  `hod` smallint(3) unsigned NOT NULL COMMENT 'ход боя, на котором игрок умер-выиграл-ничья',
  PRIMARY KEY  (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;