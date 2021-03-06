ALTER TABLE `game_users_horses` ADD `town` mediumint(10) UNSIGNED NOT NULL DEFAULT  '0' AFTER `user_id`; 

ALTER TABLE `combat_users` ADD `missed_actions` INT( 5 ) NOT NULL DEFAULT '0' AFTER `hod_start`;

ALTER TABLE `game_combats_log` ADD `user1_id` INT( 15 ) NOT NULL DEFAULT '0',
ADD `user2_id` INT( 15 ) NOT NULL DEFAULT '0';

ALTER TABLE  `game_combats_log_text` ADD  `combat_id` INT( 15 ) NOT NULL FIRST;

ALTER TABLE `game_combats_log_text` DROP INDEX `name` ,
ADD UNIQUE `name` ( `name` , `mode` , `kuda` , `combat_id` );

ALTER TABLE `game_items` ADD `personal` INT( 15 ) NOT NULL DEFAULT '0';

CREATE TABLE IF NOT EXISTS `game_items_deleted` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `user_name` varchar(50) NOT NULL,  
  `item_id` int(10) unsigned NOT NULL,
  `reason` tinyint(3) unsigned NOT NULL,
  `action_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `item_id` (`item_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Удалённые предметы';