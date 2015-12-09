ALTER TABLE  `game_exchange` CHANGE  `in_kol`  `in_kol` INT( 5 ) UNSIGNED NOT NULL DEFAULT  '1';
ALTER TABLE  `game_exchange` CHANGE  `out_kol`  `out_kol` INT( 5 ) UNSIGNED NOT NULL DEFAULT  '1';
ALTER TABLE  `game_exchange_groups` CHANGE  `kol`  `kol` INT( 5 ) UNSIGNED NOT NULL DEFAULT  '1';
ALTER TABLE  `game_clans` CHANGE  `cw_wins`  `cw_wins` INT( 5 ) NOT NULL DEFAULT  '0' COMMENT  'Победы в Многокланах';
ALTER TABLE `arcomage` ADD `user1_name` VARCHAR( 20 ) NOT NULL AFTER `user1`;
ALTER TABLE `arcomage` ADD `user2_name` VARCHAR( 20 ) NOT NULL AFTER `user2`;