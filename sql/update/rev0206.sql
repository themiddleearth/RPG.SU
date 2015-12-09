ALTER TABLE `game_obj` ADD `timestart` VARCHAR( 16 ) NOT NULL ;
ALTER TABLE `game_obj` CHANGE `time` `time` CHAR( 16 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
CHANGE `timestart` `timestart` CHAR( 16 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL; 