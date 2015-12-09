 CREATE TABLE `game_users_active_delay` (
`user_id` INT NOT NULL ,
`delay` INT UNSIGNED NOT NULL ,
`delay_reason` INT NOT NULL ,
PRIMARY KEY ( `user_id` )
) ENGINE = MYISAM COMMENT = 'Текущие состояния игроков в игре';
INSERT INTO `game_users_active_delay` (
`user_id` ,
`delay` ,
`delay_reason`
)
SELECT user_id,0,0 FROM view_active_users;
ALTER TABLE `game_users`
  DROP `delay`,
  DROP `delay_reason`;
DROP VIEW `view_active_users`;
CREATE VIEW view_active_users AS  SELECT game_users.*,game_users_active_delay.delay,game_users_active_delay.delay_reason from (`game_users`) join (`game_users_active`,`game_users_active_delay`) 
where ((`game_users`.`user_id` = `game_users_active`.`user_id`) and (`game_users`.`user_id` = `game_users_active_delay`.`user_id`) and (`game_users_active`.`last_active` > (unix_timestamp() - 300)));