ALTER TABLE  `game_users_complects` ADD  `clevel` INT( 2 ) NOT NULL DEFAULT  '0' AFTER  `finish_time`;
ALTER TABLE  `houses_templates` ADD `build_group` INT( 1 ) NOT NULL DEFAULT  '0';
UPDATE houses_templates SET build_group = 1 WHERE id in (6,7,8);
UPDATE houses_templates SET build_group = 2 WHERE id in (9, 10, 11, 12);
UPDATE houses_templates SET build_group = 3 WHERE id in (13, 14, 15, 16);