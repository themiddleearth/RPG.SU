ALTER TABLE  `game_items_factsheet` ADD  `pr_p` INT( 3 ) NOT NULL DEFAULT  '0' AFTER  `stm_p`;
ALTER TABLE  `craft_resource` ADD  `life_time` INT( 5 ) NOT NULL DEFAULT  '0';
ALTER TABLE  `craft_resource_user` ADD  `dead_time` INT( 15 ) NOT NULL DEFAULT  '0';
ALTER TABLE  `craft_resource_market` ADD  `dead_time` INT( 15 ) NOT NULL DEFAULT  '0';
ALTER TABLE  `game_exchange` ADD  `enable` INT( 1 ) NOT NULL DEFAULT  '1';
ALTER TABLE  `quest_constructor` ADD  `dead_time` INT( 14 ) NOT NULL DEFAULT  '0';
ALTER TABLE  `game_items_factsheet` ADD  `set_id` SMALLINT( 3 ) NOT NULL DEFAULT  '0';
ALTER TABLE  `dungeon_users_data` CHANGE  `current_level`  `level1_success` SMALLINT( 3 ) UNSIGNED NULL DEFAULT  '0';
ALTER TABLE  `dungeon_users_data` CHANGE  `current_quest`  `level1_quest` SMALLINT( 3 ) UNSIGNED NULL DEFAULT  '0';
ALTER TABLE  `dungeon_users_data` CHANGE  `done_quests_num`  `level1_quests_count` SMALLINT( 3 ) UNSIGNED NOT NULL DEFAULT  '0';
ALTER TABLE  `dungeon_users_data` 
ADD  `level2_success` SMALLINT( 3 ) NOT NULL DEFAULT  '0',
ADD  `level2_quest` SMALLINT( 3 ) NOT NULL DEFAULT  '0',
ADD  `level2_quests_count` SMALLINT( 3 ) NOT NULL DEFAULT  '0',
ADD  `level3_success` SMALLINT( 3 ) NOT NULL DEFAULT  '0',
ADD  `level3_quest` SMALLINT( 3 ) NOT NULL DEFAULT  '0',
ADD  `level3_quests_count` SMALLINT( 3 ) NOT NULL DEFAULT  '0';
ALTER TABLE  `dungeon_users_progress` ADD  `quest_id` SMALLINT( 3 ) NOT NULL AFTER  `user_id` ;

ALTER TABLE  `game_combats_users` ADD  `side` SMALLINT NOT NULL DEFAULT  '0',
ADD  `kills` TINYINT NOT NULL DEFAULT  '0';

ALTER TABLE  `game_admins` ADD  `statall` TINYINT NOT NULL DEFAULT  '0';

ALTER TABLE  `game_clans` 
ADD  `gp` INT( 5 ) NOT NULL DEFAULT  '0',
ADD  `autopay` TINYINT NOT NULL DEFAULT  '0',
ADD  `cw_wins` SMALLINT( 3 ) NOT NULL DEFAULT  '0' COMMENT  'Победы в Многокланах' AFTER  `wins`,
CHANGE  `wins`  `wins` INT( 3 ) NOT NULL DEFAULT  '0' COMMENT  'Победы в Великих Битвах';

CREATE TABLE IF NOT EXISTS `game_tutorship` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `pupil_id` int(10) unsigned NOT NULL,
  `confirmed` tinyint unsigned NOT NULL DEFAULT '0',
  `action_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `pupil_id` (`pupil_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Наставничество';

ALTER TABLE  `game_tavern_spletni` ADD  `time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;