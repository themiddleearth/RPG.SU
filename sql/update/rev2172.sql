CREATE TABLE IF NOT EXISTS `game_users_complects` (
  `id` int(10) NOT NULL AUTO_INCREMENT,  
  `user_id` int(11) NOT NULL,  
  `status` int(1) NOT NULL DEFAULT '0',  
  `finish_time` int(15) NOT NULL,
  `str` int(4) NOT NULL DEFAULT '0',
  `ntl` int(4) NOT NULL DEFAULT '0',
  `pie` int(4) NOT NULL DEFAULT '0',
  `vit` int(4) NOT NULL DEFAULT '0',
  `dex` int(4) NOT NULL DEFAULT '0',
  `spd` int(4) NOT NULL DEFAULT '0',
  `lucky` int(4) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`),
   KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Таблица комплектов игроков';

CREATE TABLE IF NOT EXISTS `game_users_complects_prepare` (
  `complect_id` int(11) NOT NULL,  
  `item_id` int(1) NOT NULL,  
   UNIQUE (`complect_id`, `item_id`)   
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Логирование процесса запоминания комплектов игроков';

CREATE TABLE IF NOT EXISTS `game_users_complects_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `complect_id` int(11) NOT NULL,  
  `item_id` int(1) NOT NULL,  
  `used` int(3) NOT NULL DEFAULT '0',    
   PRIMARY KEY (`id`), 
   KEY `item_id` (`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Предметы в комплекте игрока';

ALTER TABLE  `game_users` ADD  `complects` INT( 1 ) NOT NULL DEFAULT  '0';
ALTER TABLE  `game_users_archive` ADD  `complects` INT( 1 ) NOT NULL DEFAULT  '0';
CREATE or REPLACE VIEW `view_active_users` AS select `game_users`.`user_id` AS `user_id`,`game_users`.`user_name` AS `user_name`,`game_users`.`user_pass` AS `user_pass`,`game_users`.`name` AS `name`,`game_users`.`HP` AS `HP`,`game_users`.`HP_MAX` AS `HP_MAX`,`game_users`.`MP` AS `MP`,`game_users`.`MP_MAX` AS `MP_MAX`,`game_users`.`STM` AS `STM`,`game_users`.`STM_MAX` AS `STM_MAX`,`game_users`.`EXP` AS `EXP`,`game_users`.`GP` AS `GP`,`game_users`.`STR` AS `STR`,`game_users`.`NTL` AS `NTL`,`game_users`.`PIE` AS `PIE`,`game_users`.`VIT` AS `VIT`,`game_users`.`DEX` AS `DEX`,`game_users`.`SPD` AS `SPD`,`game_users`.`CW` AS `CW`,`game_users`.`CC` AS `CC`,`game_users`.`race` AS `race`, `game_users`.`complects` AS `complects`, `game_users`.`avatar` AS `avatar`,`game_users`.`MS_ART` AS `MS_ART`,`game_users`.`lucky` AS `lucky`,`game_users`.`clevel` AS `clevel`, `game_users`.`reinc` AS `reinc`, `game_users`.`bound` AS `bound`,`game_users`.`exam` AS `exam`,`game_users`.`MS_VOR` AS `MS_VOR`,`game_users`.`MS_KULAK` AS `MS_KULAK`,`game_users`.`MS_WEAPON` AS `MS_WEAPON`,`game_users`.`MS_LUK` AS `MS_LUK`,`game_users`.`MS_PARIR` AS `MS_PARIR`,`game_users`.`MS_KUZN` AS `MS_KUZN`,`game_users`.`MS_LEK` AS `MS_LEK`,`game_users`.`vsadnik` AS `vsadnik`,`game_users`.`MS_VSADNIK` AS `MS_VSADNIK`,`game_users`.`skill_war` AS `skill_war`,`game_users`.`skill_music` AS `skill_music`,`game_users`.`skill_cook` AS `skill_cook`,`game_users`.`skill_art` AS `skill_art`,`game_users`.`skill_explor` AS `skill_explor`,`game_users`.`skill_craft` AS `skill_craft`,`game_users`.`skill_card` AS `skill_card`,`game_users`.`skill_pet` AS `skill_pet`,`game_users`.`skill_uknow` AS `skill_uknow`,`game_users`.`win` AS `win`,`game_users`.`lose` AS `lose`,`game_users`.`clan_id` AS `clan_id`,`game_users`.`dvij` AS `dvij`,`game_users`.`view_chat` AS `view_chat`,`game_users`.`minestone` AS `minestone`,`game_users`.`minestonetime` AS `minestonetime`,`game_users`.`mineore` AS `mineore`,`game_users`.`mineoretime` AS `mineoretime`,`game_users`.`minewood` AS `minewood`,`game_users`.`minewoodtime` AS `minewoodtime`,`game_users`.`clan_items_old` AS `clan_items_old`,`game_users`.`STR_MAX` AS `STR_MAX`,`game_users`.`NTL_MAX` AS `NTL_MAX`,`game_users`.`PIE_MAX` AS `PIE_MAX`,`game_users`.`VIT_MAX` AS `VIT_MAX`,`game_users`.`DEX_MAX` AS `DEX_MAX`,`game_users`.`SPD_MAX` AS `SPD_MAX`,`game_users`.`x` AS `x`,`game_users`.`y` AS `y`,`game_users`.`sector` AS `sector`,`game_users`.`view_smile` AS `view_smile`,`game_users`.`view_img` AS `view_img`,`game_users`.`hide` AS `hide`,`game_users`.`arcomage` AS `arcomage`,`game_users`.`arcomage_win` AS `arcomage_win`,`game_users`.`arcomage_lose` AS `arcomage_lose`,`game_users`.`maze_win` AS `maze_win`,`game_users`.`HP_MAXX` AS `HP_MAXX`,`game_users`.`stroitel` AS `stroitel`,`game_users`.`sobiratel` AS `sobiratel`,`game_users`.`minemetal` AS `minemetal`,`game_users`.`alchemist` AS `alchemist`,`game_users`.`stroiteltime` AS `stroiteltime`,`game_users`.`alchemisttime` AS `alchemisttime`,`game_users`.`sobirateltime` AS `sobirateltime`,`game_users`.`minemetaltime` AS `minemetaltime`,`game_users`.`MS_SWORD` AS `MS_SWORD`,`game_users`.`MS_AXE` AS `MS_AXE`,`game_users`.`MS_SPEAR` AS `MS_SPEAR`,`game_users`.`lucky_max` AS `lucky_max`,`game_users`.`sklon` AS `sklon`,`game_users`.`MS_THROW` AS `MS_THROW`,`game_users_active_delay`.`delay` AS `delay`,`game_users_active_delay`.`delay_reason` AS `delay_reason` from (`game_users` join (`game_users_active` join `game_users_active_delay`)) where ((`game_users`.`user_id` = `game_users_active`.`user_id`) and (`game_users`.`user_id` = `game_users_active_delay`.`user_id`) and (`game_users_active`.`last_active` > (unix_timestamp() - 300)));
