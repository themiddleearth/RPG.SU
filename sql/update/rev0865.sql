ALTER TABLE `game_users`
  DROP `hod`,
  DROP `boy`,
  DROP `func`;
ALTER TABLE `game_users_archive`
  DROP `hod`,
  DROP `boy`,
  DROP `func`;
DROP VIEW `view_active_users`;
CREATE VIEW view_active_users AS  SELECT game_users.*,game_users_active_delay.delay,game_users_active_delay.delay_reason from (`game_users`) join (`game_users_active`,`game_users_active_delay`) 
where ((`game_users`.`user_id` = `game_users_active`.`user_id`) and (`game_users`.`user_id` = `game_users_active_delay`.`user_id`) and (`game_users_active`.`last_active` > (unix_timestamp() - 300)));