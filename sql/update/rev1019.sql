ALTER TABLE `game_shop` ADD `kleymo` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT 0;
ALTER TABLE `game_items` ADD `kleymo` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0 - без клейма, 1 - клеймо клана, 2 - личное клеймо';
ALTER TABLE `game_items` ADD `kleymo_nomer` SMALLINT( 4 ) UNSIGNED NOT NULL COMMENT 'Порядковый номер клеймения';
ALTER TABLE `game_items` ADD `kleymo_id` MEDIUMINT( 15 ) UNSIGNED NOT NULL COMMENT 'user_id или clan_id клейма';
ALTER TABLE `game_items` ADD INDEX ( `kleymo` , `kleymo_id` ) ;
ALTER TABLE `game_items` ADD INDEX ( `kleymo` , `kleymo_nomer` , `kleymo_id` ) ;