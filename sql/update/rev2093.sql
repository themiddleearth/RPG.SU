CREATE TABLE IF NOT EXISTS `game_users_psg` (
  `user_id` int(11) NOT NULL,
  `banned_date` int(15) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Таблица ПСЖ';