 CREATE TABLE `dungeon_quests` (
`id` SMALLINT( 5 ) UNSIGNED NOT NULL ,
`quest_level` SMALLINT( 3 ) UNSIGNED NOT NULL ,
`quest_id` SMALLINT( 3 ) UNSIGNED NOT NULL ,
`name` VARCHAR( 100 ) NOT NULL ,
`description` VARCHAR( 1000 ) NOT NULL
) ENGINE = InnoDB COMMENT = 'Шаблоны квестов для Подземелий Мории' ;

 ALTER TABLE `dungeon_quests` ADD PRIMARY KEY ( `id` )  ;
 
 ALTER TABLE `dungeon_quests` ADD INDEX ( `quest_level` )  ;
 
 ALTER TABLE `dungeon_quests` ADD INDEX `quest_level_2` ( `quest_level` , `quest_id` ) ;
 
 CREATE TABLE `dungeon_quests_res` (
`id` MEDIUMINT( 10 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
`quest_id` SMALLINT( 5 ) UNSIGNED NOT NULL ,
`res_id` MEDIUMINT( 2 ) UNSIGNED NOT NULL ,
`res_kol` SMALLINT( 4 ) UNSIGNED NOT NULL ,
PRIMARY KEY ( `id` )
) ENGINE = InnoDB  COMMENT = 'Ресурсы для шаблонов квестов для Подземелий Мории' ;

ALTER TABLE `dungeon_quests_res` ADD INDEX ( `quest_id` ) ;
 
ALTER TABLE `dungeon_quests` CHANGE `id` `id` SMALLINT( 5 ) UNSIGNED NOT NULL AUTO_INCREMENT  ;
  
ALTER TABLE `dungeon_quests` DROP INDEX `quest_level_2`;

ALTER TABLE `dungeon_quests` ADD UNIQUE `quest_level_2` ( `quest_level` , `quest_id` );

 ALTER TABLE `dungeon_quests_res` CHANGE `res_kol` `col` SMALLINT( 4 ) UNSIGNED NOT NULL  ;
 
 ALTER TABLE `dungeon_quests_res` ADD UNIQUE `quest_id_2` ( `quest_id` , `res_id` );
 
 RENAME TABLE `game_npc` TO `game_npc_template` ;  
 ALTER TABLE `game_npc_template` DROP `npc_hp`;
 ALTER TABLE `game_npc_template` DROP `npc_mp`;
 ALTER TABLE `game_npc_template` DROP `npc_map_name`;
 ALTER TABLE `game_npc_template` DROP `npc_xpos`;
 ALTER TABLE `game_npc_template` DROP `npc_ypos`;
 ALTER TABLE `game_npc_template` DROP `npc_time`;
 ALTER TABLE `game_npc_template` DROP `straj`;
 ALTER TABLE `game_npc_template` DROP `view`;
 ALTER TABLE `game_npc_template` DROP `npc_ypos_view`;
 ALTER TABLE `game_npc_template` DROP `npc_xpos_view`;
 ALTER TABLE `game_npc_template` DROP `npc_kill`;
 ALTER TABLE `game_npc_template` DROP `npc_win`;
 ALTER TABLE `game_npc_template` DROP `npc_exp`;
 ALTER TABLE `game_npc_template` DROP `prizrak`;
 ALTER TABLE `game_npc_template` DROP `gorod_id`;
 ALTER TABLE `game_npc_template` DROP `gorod_img`;
 ALTER TABLE `game_npc_template` DROP `gorod_x`;
 ALTER TABLE `game_npc_template` DROP `gorod_y`;
 ALTER TABLE `game_npc_template` DROP `npc_kill_last_hour`;
 ALTER TABLE `game_npc_template` DROP `npc_alive`;
 ALTER TABLE `game_npc_template` DROP `npc_for_user_id`;
 
 CREATE TABLE IF NOT EXISTS `game_npc` (
  `id` mediumint(20) unsigned NOT NULL auto_increment,
  `HP` mediumint(8) unsigned NOT NULL default '0' COMMENT 'Жизнь бота',
  `MP` mediumint(8) unsigned NOT NULL default '0' COMMENT 'Мана бота',
  `stay` tinyint(1) unsigned NOT NULL default '0' COMMENT '1 - бот стоит на месте после смерти',
  `npc_id` mediumint(15) unsigned NOT NULL default '0' COMMENT 'id template бота',
  `map_name` smallint(3) unsigned NOT NULL default '0' COMMENT 'карта',
  `xpos` smallint(5) unsigned NOT NULL default '0' COMMENT 'позиция X',
  `ypos` smallint(5) unsigned NOT NULL default '0' COMMENT 'позиция Y',
  `time_kill` int(14) unsigned NOT NULL default '0' COMMENT 'время последнего убийства бота',
  `view` tinyint(1) unsigned NOT NULL default '1' COMMENT '1 - бот виден на карте местности в /view/',
  `npc_xpos_view` smallint(5) unsigned NOT NULL default '0',
  `npc_ypos_view` smallint(5) unsigned NOT NULL default '0',
  `LOSE` int(10) unsigned NOT NULL default '0' COMMENT 'проиграл',
  `WIN` int(10) unsigned NOT NULL default '0' COMMENT 'выиграл',
  `EXP` smallint(5) unsigned NOT NULL default '0' COMMENT 'текущая EXP за бота',
  `prizrak` tinyint(1) unsigned NOT NULL default '0' COMMENT '1 - призрак, виден только одному игроку',
  `kill_last_hour` mediumint(7) unsigned NOT NULL default '0' COMMENT 'кол-во убийств бота за последний час',
  `for_user_id` mediumint(15) unsigned NOT NULL default '0' COMMENT 'ID игрока для которого предназначен бот',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Таблица ботов в игре' AUTO_INCREMENT=1 ;

ALTER TABLE `game_npc_template` CHANGE `npc_basedef` `npc_spd` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `game_npc_template` CHANGE `npc_basedef_deviation` `npc_spd_deviation` SMALLINT( 5 ) NOT NULL DEFAULT '0';
ALTER TABLE `game_npc_template` CHANGE `npc_basefit` `npc_vit` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `game_npc_template` CHANGE `npc_basefit_deviation` `npc_vit_deviation` SMALLINT( 5 ) NOT NULL DEFAULT '0';
ALTER TABLE `game_npc_template` CHANGE `npc_wis` `npc_pie` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '0' ;
ALTER TABLE `game_npc_template` CHANGE `npc_wis_deviation` `npc_pie_deviation` SMALLINT( 5 ) NOT NULL DEFAULT '0';
ALTER TABLE `game_npc_template` DROP `npc_status`;
ALTER TABLE `game_npc_template` CHANGE `level_user` `agressive_level` MEDIUMINT( 8 ) NOT NULL DEFAULT '0';
ALTER TABLE `game_npc` CHANGE `npc_xpos_view` `xpos_view` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `game_npc` CHANGE `npc_ypos_view` `ypos_view` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `game_npc` ADD `dropable` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0' COMMENT '1 - из бота выпадает дроп templatа';
ALTER TABLE `game_npc` CHANGE `xpos_view` `xpos_view` TINYINT( 2 ) NOT NULL DEFAULT '0',
CHANGE `ypos_view` `ypos_view` TINYINT( 2 ) NOT NULL DEFAULT '0';
ALTER TABLE `game_npc` ADD `npc_quest_guild` INT( 10 ) UNSIGNED NOT NULL ,
ADD `npc_quest_item` MEDIUMINT( 8 ) UNSIGNED NOT NULL ,
ADD `npc_quest_end_time` INT( 15 ) UNSIGNED NOT NULL ,
ADD `npc_quest_id` MEDIUMINT( 15 ) UNSIGNED NOT NULL ,
ADD `npc_quest_engine_id` INT( 15 ) UNSIGNED NOT NULL ;
ALTER TABLE `game_npc_template`
  DROP `npc_quest_guild`,
  DROP `npc_quest_item`,
  DROP `npc_quest_end_time`,
  DROP `npc_quest_id`,
  DROP `npc_quest_engine_id`;
ALTER TABLE `game_npc` ADD `GP` DECIMAL( 6, 2 ) NOT NULL COMMENT 'используется в движке квестов';
ALTER TABLE `game_npc_template` ADD `to_delete` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0' COMMENT '1 - значит надо в кроне удалить этот шаблон (шаблон создавался для служебных целей)';

ALTER TABLE `game_npc_drop` ADD `mincount` MEDIUMINT( 5 ) UNSIGNED NOT NULL DEFAULT '1' COMMENT 'минимальное кол-во выпадающих предметов',
ADD `maxcount` MEDIUMINT( 5 ) UNSIGNED NOT NULL DEFAULT '1' COMMENT 'максимальное кол-во выпадающих предметов';

 ALTER TABLE `game_npc_template` DROP `to_delete`  ;
 ALTER TABLE `game_npc` ADD `npc_flag` SMALLINT( 2 ) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0 - ничего, 1 - при начале боя создать харки от харок игрока';
 ALTER TABLE `game_npc_template` ADD `to_delete` TINYINT( 1 ) UNSIGNED NOT NULL COMMENT '1 - при убийстве последнего бота из game_npc надо удалить и этот шаблон';
 
 DROP VIEW `view_map_info`;
 
 CREATE VIEW `view_map_info` AS 
 select 
 `combat`.`combat_id` AS `combid`,
 `game_shop`.`id` AS `shopid`,
 `game_npc`.`id` AS `npcid`,
 `craft_build_user`.`id` AS `userid`,
 `game_users_map`.`user_id` AS `user_id`,
 `game_gorod`.`rustown` AS `rustown`,
 `game_map`.`name` AS `name`,
 `game_map`.`xpos` AS `xpos`,
 `game_map`.`ypos` AS `ypos`,
 `game_map`.`move_up` AS `move_up`,
 `game_map`.`move_ur` AS `move_ur`,
 `game_map`.`move_dr` AS `move_dr`,
 `game_map`.`move_dn` AS `move_dn`,
 `game_map`.`move_dl` AS `move_dl`,
 `game_map`.`move_ul` AS `move_ul`,
 `game_map`.`type` AS `type`,
 `game_map`.`subtype` AS `subtype`,
 `game_map`.`town` AS `town`,
 `game_map`.`to_map_name` AS `to_map_name`,
 `game_map`.`to_map_xpos` AS `to_map_xpos`,
 `game_map`.`to_map_ypos` AS `to_map_ypos` 
 from 
 (
	(
		(
			(
				(
					(
						`game_map` 
						left join `combat` 
							on
								(
									(
										(`game_map`.`name` = `combat`.`map_name`) and 
										(`game_map`.`xpos` = `combat`.`map_xpos`) and 
										(`game_map`.`ypos` = `combat`.`map_ypos`) and 
										(`combat`.`time_last_hod` >= (unix_timestamp() - 300))
									)
								)
					) 
					left join `game_gorod` 
						on
							(
								(`game_map`.`town` = `game_gorod`.`town`)
							)
				) 
				left join `game_shop` 
					on
						(
							(
								(`game_map`.`name` = `game_shop`.`map`) and 
								(`game_map`.`xpos` = `game_shop`.`pos_x`) and 
								(`game_map`.`ypos` = `game_shop`.`pos_y`)
							)
						)
			) 
			left join (`game_npc` join `game_npc_template`)
				on
					(
						(
							(`game_npc`.`npc_id` = `game_npc_template`.`npc_id`) and
							(`game_map`.`name` = `game_npc`.`map_name`) and 
							(`game_map`.`xpos` = `game_npc`.`xpos`) and 
							(`game_map`.`ypos` = `game_npc`.`ypos`) and 
							(`game_npc_template`.`npc_exp_max` <= 200) and 
							(`game_npc`.`prizrak` = _latin1'0') and 
							(`game_npc`.`view` = 1) and 
							((`game_npc`.`time_kill` + `game_npc_template`.`respawn`) < unix_timestamp())
						)
					)
	) 
	left join `craft_build_user` 
		on
			(
				(
					(`game_map`.`name` = `craft_build_user`.`map`) and 
					(`game_map`.`xpos` = `craft_build_user`.`x`) and 
					(`game_map`.`ypos` = `craft_build_user`.`y`)
				)
			)
 ) 
 left join (`game_users_map` join `view_active_users`) 
	on
		(
			(
				(`game_map`.`name` = `game_users_map`.`map_name`) and 
				(`game_map`.`xpos` = `game_users_map`.`map_xpos`) and 
				(`game_map`.`ypos` = `game_users_map`.`map_ypos`) and 
				(`game_users_map`.`user_id` = `view_active_users`.`user_id`)
			)
		)
 );
 
 ALTER TABLE `combat_actions` ADD `action_type_sort` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0' COMMENT '1 - атака, 2 - защита, 3 - лечение (для сортировки в обсчете хода)';
 
  ALTER TABLE `combat_actions` ADD INDEX `combat_id_3` ( `combat_id` , `hod` ); 