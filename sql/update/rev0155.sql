ALTER TABLE `game_port`
  DROP `kuda`,
  DROP `kuda_x`,
  DROP `kuda_y`;

 ALTER TABLE `game_port` CHANGE `town` `town_from` INT( 10 ) NOT NULL DEFAULT '0';

  ALTER TABLE `game_port` CHANGE `time` `time` CHAR( 5 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
CHANGE `dlit` `dlit` CHAR( 5 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0';

ALTER TABLE `game_port` ADD `town_kuda` INT( 10 ) UNSIGNED NOT NULL DEFAULT '0';