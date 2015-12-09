CREATE TABLE IF NOT EXISTS `combat_user_func` (
  `user_id` int(11) NOT NULL,
  `func_id` tinyint(4) NOT NULL,
  `func_sub_id` tinyint(4) NOT NULL COMMENT 'Поле пока не используется',
  `time_stamp` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Координатор для Combat-а';