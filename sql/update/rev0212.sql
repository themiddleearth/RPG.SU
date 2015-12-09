ALTER TABLE `game_npc` CHANGE `agressive` `agressive` ENUM( '-1', '0', '1', '2' ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0' COMMENT '-1 - мирный';
CREATE TABLE `game_npc_movetask` (
`task_id` INT NOT NULL AUTO_INCREMENT ,
`npc_id` INT NOT NULL ,
`to_map_id` TINYINT NOT NULL ,
`to_map_x` TINYINT NOT NULL ,
`to_map_y` TINYINT NOT NULL ,
`to_user_id` INT NOT NULL ,
`item_id` INT NOT NULL ,
`status` TINYINT NOT NULL DEFAULT '0',
PRIMARY KEY ( `task_id` )
) ENGINE = INNODB ;