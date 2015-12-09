 ALTER TABLE `game_obj` ADD `moved` TINYINT( 1 )  UNSIGNED NOT NULL COMMENT 'Признак передвигающегося телепорта',
ADD `movetime` ENUM( '0', '1', '3', '10', '60', '120', '180', '240', '360', '720' ) DEFAULT '0' NOT NULL COMMENT 'Таймаут передвижения телепорта' ;

ALTER TABLE `game_obj` ADD INDEX `moved` ( `moved` , `movetime` );
 
ALTER TABLE `game_map` ADD INDEX ( `town` )  ;
  
ALTER TABLE `game_map` ADD INDEX `town_2` ( `town` , `to_map_name` ) ;

ALTER TABLE `game_maps` CHANGE `id` `id` INT( 6 ) UNSIGNED NOT NULL AUTO_INCREMENT  ;
 
CREATE TABLE `game_users_maze` (
`user_id` MEDIUMINT( 15 ) UNSIGNED NOT NULL DEFAULT '0',
`maze_id` INT( 6 ) UNSIGNED NOT NULL DEFAULT '0',
INDEX ( `user_id` , `maze_id` )
) ENGINE = MYISAM ;