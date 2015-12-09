ALTER TABLE `game_shop` ADD `kleymo` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT 0;
ALTER TABLE `game_items` ADD `kleymo` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0 - ��� ������, 1 - ������ �����, 2 - ������ ������';
ALTER TABLE `game_items` ADD `kleymo_nomer` SMALLINT( 4 ) UNSIGNED NOT NULL COMMENT '���������� ����� ���������';
ALTER TABLE `game_items` ADD `kleymo_id` MEDIUMINT( 15 ) UNSIGNED NOT NULL COMMENT 'user_id ��� clan_id ������';
ALTER TABLE `game_items` ADD INDEX ( `kleymo` , `kleymo_id` ) ;
ALTER TABLE `game_items` ADD INDEX ( `kleymo` , `kleymo_nomer` , `kleymo_id` ) ;