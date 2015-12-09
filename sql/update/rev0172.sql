ALTER TABLE `game_clans` ADD `sklon` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `game_users` ADD `sklon` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `game_users_archive` ADD `sklon` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `combat_users` ADD `sklon` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0';

DROP VIEW `view_active_users`;
CREATE VIEW view_active_users AS SELECT game_users.* from (`game_users` join `game_users_active`) 
where ((`game_users`.`user_id` = `game_users_active`.`user_id`) and (`game_users_active`.`last_active` > (unix_timestamp() - 300)))