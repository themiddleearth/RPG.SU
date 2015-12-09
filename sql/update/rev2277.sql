CREATE TABLE IF NOT EXISTS `game_gorod_skills` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gorod_id` int(10) unsigned NOT NULL DEFAULT '0',
  `skill_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `gorod_id` (`gorod_id`,`skill_id`),
  KEY `gorod_id_2` (`gorod_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Специализации в городе';