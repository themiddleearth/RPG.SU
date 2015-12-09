 CREATE TABLE `game_stats_timemarker` (
`id` INT NOT NULL AUTO_INCREMENT ,
`user_id` INT UNSIGNED NOT NULL ,
`time_stamp` INT UNSIGNED NOT NULL ,
`reason` INT UNSIGNED NOT NULL ,
PRIMARY KEY ( `id` )
) ENGINE = InnoDB;
 ALTER TABLE `game_stats_timemarker` ADD INDEX `time_stamp` ( `time_stamp` , `reason` ) ;