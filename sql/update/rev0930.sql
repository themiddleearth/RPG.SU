ALTER TABLE `game_maps` ADD `boy_type6` SMALLINT( 1 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `game_maps` CHANGE `arena` `arena` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `dolina` `dolina` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `not_exp` `not_exp` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `not_gp` `not_gp` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `not_win` `not_win` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `not_lose` `not_lose` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `boy_type1` `boy_type1` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '1',
CHANGE `boy_type2` `boy_type2` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '1',
CHANGE `boy_type3` `boy_type3` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '1',
CHANGE `boy_type4` `boy_type4` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '1',
CHANGE `boy_type5` `boy_type5` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '1',
CHANGE `boy_auto` `boy_auto` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `boy_auto_type` `boy_auto_type` SMALLINT( 1 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `join_auto` `join_auto` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `maze` `maze` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `boy_type6` `boy_type6` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0';