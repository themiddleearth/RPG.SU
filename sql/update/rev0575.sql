 ALTER TABLE `craft_stat` ADD INDEX ( `user`,`type` ) ;
 ALTER TABLE `craft_stat` CHANGE `type` `type` ENUM( 'z', 'p', 'n' ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'z' ;
