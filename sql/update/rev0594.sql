ALTER TABLE `game_items_factsheet` ADD `can_up` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '1';
ALTER TABLE `game_quest_users` ADD `finish` TINYINT( 1 ) UNSIGNED NOT NULL ;
ALTER TABLE `game_items` ADD `shop_from` INT( 10 ) UNSIGNED DEFAULT '0' NOT NULL ;
ALTER TABLE `game_npc` ADD `npc_quest_engine_id` INT( 15 ) UNSIGNED DEFAULT '0' NOT NULL ;
CREATE TABLE `quest_engine_owners` (
  `id` smallint(3) NOT NULL default '0',
  `name` varchar(100) NOT NULL default '',
  `map_name` smallint(6) NOT NULL default '0',
  `map_xpos` tinyint(5) NOT NULL default '0',
  `map_ypos` tinyint(5) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `position` (`map_name`,`map_xpos`,`map_ypos`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `quest_engine_topics` (
  `id` int(7) unsigned NOT NULL auto_increment,
  `topic_id` int(6) unsigned NOT NULL default '0',
  `owner_id` int(3) unsigned NOT NULL default '0',
  `quest_type` int(5) unsigned NOT NULL default '0',
  `action_type` int(5) unsigned NOT NULL default '0',
  `text` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `small_circ` (`owner_id`,`action_type`),
  KEY `big_circ` (`owner_id`,`quest_type`,`action_type`,`topic_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `quest_engine_users` (
  `id` int(7) unsigned NOT NULL auto_increment,
  `user_id` int(15) unsigned NOT NULL default '0',
  `quest_type` int(5) unsigned NOT NULL default '0',
  `quest_owner_id` mediumint(3) unsigned NOT NULL default '0',
  `quest_topic_id` int(6) unsigned NOT NULL default '0',
  `quest_reward` int(10) unsigned NOT NULL default '0',
  `quest_finish_time` int(20) unsigned NOT NULL default '0',
  `par1_name` varchar(255) NOT NULL default '',
  `par1_value` int(15) NOT NULL default '0',
  `par2_name` varchar(255) NOT NULL default '',
  `par2_value` int(15) NOT NULL default '0',
  `par3_name` varchar(255) NOT NULL default '',
  `par3_value` int(15) NOT NULL default '0',
  `par4_name` varchar(255) NOT NULL default '',
  `par4_value` int(15) NOT NULL default '0',
  `done` int(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`),
  KEY `user_n_owner` (`user_id`,`quest_owner_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `quest_engine_words` (
  `id` int(6) NOT NULL auto_increment,
  `type` set('oh','ent_w','obr') NOT NULL default '',
  `word` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;