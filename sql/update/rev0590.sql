 CREATE TABLE `combat_actions` (
`combat_id` INT UNSIGNED NOT NULL ,
`hod` SMALLINT UNSIGNED NOT NULL ,
`user_id` INT UNSIGNED NOT NULL ,
`action_type` TINYINT UNSIGNED NOT NULL ,
`action_chem` INT NOT NULL ,
`action_kogo` INT NOT NULL ,
`action_kuda` TINYINT NOT NULL ,
`action_proc` TINYINT NOT NULL ,
`action_HP` INT NOT NULL ,
`action_MP` INT NOT NULL ,
`action_STM` INT NOT NULL ,
`action_item` INT NOT NULL ,
`action_subtype` TINYINT NOT NULL DEFAULT '1'
) ENGINE = InnoDB COMMENT = 'Действия игроков в бою в пределах отдельных ходов' ;
ALTER TABLE `combat_actions` ADD `action_rand` INT NOT NULL AFTER `action_item` ;
 ALTER TABLE `combat_actions` ADD INDEX `combat_id` ( `combat_id` , `user_id`, `action_rand` );