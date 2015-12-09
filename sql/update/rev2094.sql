CREATE TABLE IF NOT EXISTS `game_users_reincarnation` (
  `user_id` int(11) NOT NULL,
  `reincarnation_date` int(15) NOT NULL,
  UNIQUE (`user_id`, `reincarnation_date`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Таблица реинкарнаций';