ALTER TABLE `quest_engine_owners` ADD `enter` VARCHAR( 100 ) NOT NULL ,
ADD `about` TEXT NOT NULL ;

ALTER TABLE `quest_engine_users` ADD `quest_start_time` INT( 20 ) UNSIGNED DEFAULT '0' NOT NULL AFTER `quest_reward` ;

CREATE TABLE IF NOT EXISTS `quest_engine_stats` (
  `user_id` mediumint(15) unsigned NOT NULL default '0',
  `quest_num` mediumint(3) unsigned NOT NULL default '0',
  `quest_first` int(15) unsigned NOT NULL default '0',
  `quest_last` int(15) unsigned NOT NULL default '0',
  `quests_done` mediumint(6) unsigned NOT NULL default '0',
  PRIMARY KEY  (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;