ALTER TABLE `craft_build` ADD  `include` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

CREATE TABLE IF NOT EXISTS `craft_build_founder` (
  `user_id` mediumint(15) unsigned NOT NULL,
  `nas` int(5) unsigned NOT NULL,
  `teplo` int(5) unsigned NOT NULL,
  `col_coal` smallint(3) unsigned NOT NULL default '0',
  `col_water` smallint(3) unsigned NOT NULL default '0',
  `col_res` smallint(3) unsigned NOT NULL default '0',
  `res_id` mediumint(2) unsigned NOT NULL default '0',
  `state` tinyint(1) unsigned NOT NULL COMMENT '0 - еще не плавим, 1-3 - стадия плавления',
  PRIMARY KEY  (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `craft_build_lumberjack` (
  `id` mediumint(10) unsigned NOT NULL auto_increment,
  `build_id` mediumint(5) unsigned NOT NULL default '0',
  `nomer` smallint(2) unsigned NOT NULL default '0',
  `user_id` mediumint(15) unsigned NOT NULL default '0',
  `state` int(5) unsigned NOT NULL default '0',
  `end_time` int(15) unsigned NOT NULL default '0',
  `brevn1` tinyint(1) unsigned NOT NULL default '0',
  `brevn2` tinyint(1) unsigned NOT NULL default '0',
  `brevn3` tinyint(1) unsigned NOT NULL default '0',
  `brevn4` tinyint(1) unsigned NOT NULL default '0',
  `brevn5` tinyint(1) unsigned NOT NULL default '0',
  `brevn6` tinyint(1) unsigned NOT NULL default '0',
  `klin` smallint(2) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `build_id` (`build_id`),
  KEY `build_id_2` (`build_id`,`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ;

CREATE TABLE IF NOT EXISTS `craft_build_mining` (
  `id` int(20) unsigned NOT NULL auto_increment,
  `build_id` int(5) unsigned NOT NULL default '0',
  `user_id` mediumint(15) unsigned NOT NULL default '0',
  `level` tinyint(1) unsigned NOT NULL default '0' COMMENT 'Уровень шахты',
  `geksa` tinyint(1) unsigned NOT NULL default '0' COMMENT 'Номер гексы - от 0(центральная) до 8',
  `hod` smallint(3) unsigned NOT NULL default '0' COMMENT 'Состояние прорытости хода на след.уровень',
  `geksa_obval` smallint(3) unsigned NOT NULL default '0' COMMENT 'Дополнительный шанс обвала гексы',
  `geksa_state` tinyint(1) unsigned NOT NULL default '0' COMMENT '1 - гекса завалена, 0 - гекса рабочая',
  `end_time` int(15) unsigned NOT NULL default '0' COMMENT 'Время окончания работы',
  PRIMARY KEY  (`id`),
  KEY `build_id` (`build_id`),
  KEY `user_id` (`user_id`),
  KEY `build_id_2` (`build_id`,`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `craft_build_stonemason` (
  `id` mediumint(10) unsigned NOT NULL auto_increment,
  `build_id` mediumint(5) unsigned NOT NULL default '0',
  `nomer` smallint(2) unsigned NOT NULL default '0',
  `user_id` mediumint(15) unsigned NOT NULL default '0',
  `state` int(5) unsigned NOT NULL default '0',
  `end_time` int(15) unsigned NOT NULL default '0',
  `brevn1` tinyint(1) unsigned NOT NULL default '0',
  `brevn2` tinyint(1) unsigned NOT NULL default '0',
  `brevn3` tinyint(1) unsigned NOT NULL default '0',
  `brevn4` tinyint(1) unsigned NOT NULL default '0',
  `brevn5` tinyint(1) unsigned NOT NULL default '0',
  `brevn6` tinyint(1) unsigned NOT NULL default '0',
  `klin` smallint(2) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `build_id` (`build_id`),
  KEY `build_id_2` (`build_id`,`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `game_users_crafts` (
  `id` int(30) unsigned NOT NULL auto_increment,
  `user_id` mediumint(15) unsigned NOT NULL COMMENT 'id игрока',
  `craft_index` smallint(3) unsigned NOT NULL COMMENT 'id крафтовой профессии. указаны в craft.php',
  `times` mediumint(12) unsigned NOT NULL COMMENT 'кол-во удачных выполненных действий',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `user_id` (`user_id`,`craft_index`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Таблица содержит данные о прокачке игроками крафтовых профессий';

CREATE TABLE IF NOT EXISTS `game_users_horses` (
  `id` int(30) unsigned NOT NULL auto_increment,
  `user_id` mediumint(15) unsigned NOT NULL default '0',
  `horse_id` int(5) unsigned NOT NULL default '0',
  `life` int(5) unsigned NOT NULL default '0',
  `golod` tinyint(1) unsigned NOT NULL default '0',
  `used` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`),
  KEY `user_id_2` (`user_id`,`horse_id`),
  KEY `user_id_3` (`user_id`,`used`),
  KEY `used` (`used`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

ALTER TABLE `game_vsadnik` ADD `life_horse` INT( 5 ) UNSIGNED NOT NULL ,
ADD  `price_eat` DOUBLE( 10, 2 ) UNSIGNED NOT NULL;


CREATE TABLE IF NOT EXISTS `houses_market` (
  `id` int(50) unsigned NOT NULL auto_increment,
  `user_id` mediumint(15) unsigned NOT NULL,
  `sotka` smallint(2) unsigned NOT NULL,
  `build_id` smallint(5) unsigned NOT NULL,
  `price` double(15,2) unsigned NOT NULL,
  `town_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `town` (`town_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ;

CREATE TABLE IF NOT EXISTS `houses_nalog` (
  `id` int(50) unsigned NOT NULL auto_increment,
  `user_id` mediumint(15) unsigned NOT NULL,
  `nalog` double(18,8) unsigned NOT NULL default '0.00000000',
  `nalog_time` int(15) unsigned NOT NULL default '0',
  `pay` double(15,2) unsigned NOT NULL default '0.00',
  `pay_time` int(15) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `user_id_4` (`user_id`,`nalog_time`),
  KEY `user_id` (`user_id`),
  KEY `user_id_3` (`user_id`,`nalog`,`pay`),
  KEY `user_id_2` (`user_id`,`nalog`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ;


CREATE TABLE IF NOT EXISTS `houses_templates` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `square` double(6,2) unsigned NOT NULL default '0.00' COMMENT 'занимаемая площадь',
  `type` smallint(3) unsigned NOT NULL default '0' COMMENT '1 - дом, 2 - доппостройки',
  `min_value` mediumint(6) unsigned NOT NULL default '0' COMMENT 'для "конюшен" - уровни лошадей, для хранилищ - кол-во мест, для домов - кол-во мест, ',
  `max_value` mediumint(6) unsigned NOT NULL default '0',
  `buildtime` mediumint(10) unsigned NOT NULL COMMENT 'время на строительство постройки в секундах',
  `buildcost` double(15,2) unsigned NOT NULL COMMENT 'цена за постройку',
  `stone` smallint(3) unsigned NOT NULL COMMENT 'кол-во каменных блоков для строительства',
  `doska` smallint(3) unsigned NOT NULL COMMENT 'кол-во досок для строительства',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ;


CREATE TABLE IF NOT EXISTS `houses_templates_need` (
  `id` smallint(7) unsigned NOT NULL auto_increment,
  `build_id` smallint(5) unsigned NOT NULL,
  `need` varchar(30) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `build_id` (`build_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ;


CREATE TABLE IF NOT EXISTS `houses_users` (
  `id` int(30) unsigned NOT NULL auto_increment,
  `user_id` mediumint(15) unsigned NOT NULL,
  `type` smallint(1) unsigned NOT NULL COMMENT '1 - свободная земля во владении игрока, 2 - основной дом, 3 - доп.постройки в доме',
  `town_id` int(5) unsigned NOT NULL COMMENT 'id города в котором стоит постройка',
  `square` smallint(4) unsigned NOT NULL COMMENT 'общая площадь земли у игрока',
  `buildtime` int(15) unsigned NOT NULL COMMENT 'время окончания строительства постройки',
  `build_id` smallint(5) unsigned NOT NULL COMMENT 'id постройки из templates',
  `stone` smallint(3) unsigned NOT NULL default '0' COMMENT 'вложено каменных блоков для строительства',
  `doska` smallint(3) unsigned NOT NULL default '0' COMMENT 'вложено досок для строительства',
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`,`type`),
  KEY `user_id_2` (`user_id`),
  KEY `user_id_4` (`user_id`,`town_id`),
  KEY `user_id_3` (`user_id`,`type`,`town_id`),
  KEY `user_id_5` (`user_id`,`town_id`,`buildtime`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;


INSERT INTO `houses_templates_need` (`id`, `build_id`, `need`) VALUES
(1, 2, '1'),
(3, 3, '2'),
(4, 4, '3'),
(5, 5, '1'),
(6, 5, '2'),
(7, 5, '3'),
(8, 5, '4'),
(9, 6, '2'),
(10, 6, '3'),
(11, 6, '1'),
(12, 6, '4'),
(13, 7, '2,6'),
(14, 7, '3,6'),
(15, 7, '4,6'),
(16, 8, '7,3'),
(17, 8, '7,4'),
(18, 9, '2'),
(19, 9, '1'),
(20, 9, '3'),
(21, 9, '4'),
(22, 10, '9,4'),
(23, 10, '3,9'),
(24, 10, '2,9'),
(25, 11, '3,10'),
(26, 11, '4,10'),
(27, 12, '11,4'),
(28, 14, '2,13'),
(29, 14, '13,4'),
(30, 14, '3,13'),
(31, 15, '4,14'),
(32, 15, '3,14'),
(33, 16, '15,4'),
(34, 18, '3'),
(35, 19, '3'),
(36, 20, '4'),
(37, 18, '4'),
(38, 19, '4'),
(39, 17, '2'),
(40, 17, '1'),
(41, 17, '4'),
(42, 17, '3'),
(44, 21, '1'),
(45, 21, '2'),
(46, 21, '3'),
(47, 21, '4'),
(48, 23, '2'),
(49, 23, '3'),
(50, 23, '4'),
(51, 24, '3,23'),
(52, 24, '4,23'),
(53, 25, '4,24'),
(61, 22, '3'),
(60, 22, '2'),
(59, 22, '1'),
(62, 22, '4'),
(63, 13, '1'),
(64, 13, '2'),
(65, 13, '3'),
(66, 13, '4');



INSERT INTO `houses_templates` (`id`, `name`, `square`, `type`, `min_value`, `max_value`, `buildtime`, `buildcost`, `stone`, `doska`) VALUES
(1, 'Îäíîýòàæíûé äîì', 1.00, 1, 10, 10, 86400, 200.00, 40, 20),
(2, 'Áîëüøîé äîì', 2.00, 1, 20, 20, 86400, 300.00, 80, 40),
(3, 'Êîòòåäæ', 3.00, 1, 30, 30, 86400, 400.00, 120, 60),
(4, 'Ïîìåñòüå', 4.00, 1, 40, 40, 86400, 500.00, 160, 80),
(5, 'Êóçíèöà', 1.00, 2, 0, 0, 43200, 100.00, 20, 10),
(6, 'Ñòîéëî', 1.00, 2, 5, 9, 43200, 100.00, 10, 20),
(7, 'Êîíþøíÿ', 2.00, 2, 9, 13, 43200, 100.00, 20, 40),
(8, 'Çàãîí', 3.00, 2, 13, 255, 43200, 100.00, 40, 80),
(9, 'Ìàëîå õðàíèëèùå ðåñóðñîâ', 1.00, 2, 100, 100, 43200, 100.00, 30, 30),
(10, 'Ñðåäíåå õðàíèëèùå ðåñóðñîâ', 2.00, 2, 200, 200, 43200, 100.00, 60, 60),
(11, 'Áîëüøîå õðàíèëèùå ðåñóðñîâ', 3.00, 2, 300, 300, 43200, 100.00, 90, 90),
(12, 'Ñêëàä ðåñóðñîâ', 4.00, 2, 600, 600, 43200, 100.00, 180, 180),
(13, 'Ìàëîå õðàíèëèùå ýëèêñèðîâ', 1.00, 2, 100, 100, 43200, 100.00, 30, 30),
(14, 'Ñðåäíåå õðàíèëèùå ýëèêñèðîâ', 2.00, 2, 200, 200, 43200, 100.00, 60, 60),
(15, 'Áîëüøîå õðàíèëèùå ýëèêñèðîâ', 3.00, 2, 300, 300, 43200, 100.00, 90, 90),
(16, 'Ñêëàä ýëèêñèðîâ', 4.00, 2, 600, 600, 43200, 100.00, 180, 180),
(17, 'Ëåñîïèëêà', 1.00, 2, 0, 0, 43200, 100.00, 10, 20),
(18, 'Ëèòåéíàÿ ìàñòåðñêàÿ', 1.00, 2, 0, 0, 43200, 100.00, 20, 20),
(19, 'Îðóæåéíàÿ ìàñòåðñêàÿ', 1.00, 2, 0, 0, 43200, 100.00, 30, 30),
(21, 'Àëõèìè÷åñêàÿ ëàáîðàòîðèÿ', 1.00, 2, 0, 0, 43200, 100.00, 20, 20),
(22, 'Ðàçäåëî÷íûé öåõ', 1.00, 2, 0, 0, 43200, 100.00, 30, 10);


INSERT INTO `craft_build` (`id`, `name`, `col`, `cost`, `res_need`, `res_dob`, `lev_need`, `dom`, `race`, `clevel`, `item`, `create_time`, `rab_time`, `opis`, `admin`, `chance`, `include`) VALUES
(27, 'Ëåñîïîâàë', 10, 0, '', '', 0, '', 0, 0, 376, 10, 300, '', 0, 100, 'lumberjack'),
(28, 'Êàìåíîëîìíÿ', 10, 0, '', '', 0, '', 0, 0, 377, 10, 300, '', 0, 100, 'stonemason'),
(29, 'Ðóäíèê', 9999, 0, '', '', 0, '', 0, 0, 378, 10, 300, '', 0, 100, 'mining'),
(30, 'Ëåñîïèëêà', 9999, 0, '', '', 0, '', 0, 0, 379, 10, 300, '', 0, 100, 'sawmill');

ALTER TABLE `houses_users` CHANGE `type` `type` SMALLINT( 1 ) UNSIGNED NOT NULL COMMENT '1 - свободная земля во владении игрока, 2 - основной дом, 3 - доп.постройки в доме, 4 - дом выставлен на рынок';


CREATE TABLE IF NOT EXISTS `game_items_schema` (
  `id` int(20) unsigned NOT NULL auto_increment,
  `item_id` mediumint(8) unsigned NOT NULL default '0',
  `res_id` mediumint(2) unsigned NOT NULL default '0',
  `col` smallint(4) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `item_id_3` (`item_id`,`res_id`),
  KEY `item_id_2` (`item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

ALTER TABLE `game_shop` 
ADD `svitki_store_current` INT( 7 ) UNSIGNED NOT NULL DEFAULT '0',
ADD `svitki_store_max` INT( 7 ) UNSIGNED NOT NULL DEFAULT '0',
ADD `eliksir` CHAR( 1 ) NOT NULL DEFAULT '0',
ADD `eliksir_store_current` INT( 7 ) UNSIGNED NOT NULL DEFAULT '0',
ADD `eliksir_store_max` INT( 7 ) UNSIGNED NOT NULL DEFAULT '0',
ADD `schema` CHAR( 1 ) NOT NULL DEFAULT '0',
ADD `schema_store_current` INT( 7 ) UNSIGNED NOT NULL DEFAULT '0',
ADD `schema_store_max` INT( 7 ) UNSIGNED NOT NULL DEFAULT '0',
ADD `luk` CHAR( 1 ) NOT NULL DEFAULT '0',
ADD `luk_store_current` INT( 7 ) UNSIGNED NOT NULL DEFAULT '0',
ADD `luk_store_max` INT( 7 ) UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `craft_build_rab` ADD `add` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '1';

ALTER TABLE `game_items` ADD `count_item` INT( 6 ) UNSIGNED NOT NULL DEFAULT '1' COMMENT 'количество предметов';

ALTER TABLE `game_users` ADD `MS_THROW` TINYINT( 2 ) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'навык метательного оружия';
ALTER TABLE `game_users_archive` ADD `MS_THROW` TINYINT( 2 ) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'навык метательного оружия';
ALTER TABLE `combat_users` ADD `MS_THROW` TINYINT( 2 ) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'навык метательного оружия';

DROP VIEW `view_active_users`;
CREATE VIEW view_active_users AS  SELECT game_users.*,game_users_active_delay.delay,game_users_active_delay.delay_reason from (`game_users`) join (`game_users_active`,`game_users_active_delay`) 
where ((`game_users`.`user_id` = `game_users_active`.`user_id`) and (`game_users`.`user_id` = `game_users_active_delay`.`user_id`) and (`game_users_active`.`last_active` > (unix_timestamp() - 300)));

ALTER TABLE `combat` ADD `turnir_type` TINYINT( 1 ) NOT NULL DEFAULT '0' COMMENT '1 - кулачный бой, 2 - бой с оружием, 3 - магический бой, 0 или 4 - полный бой';

ALTER TABLE `game_obelisk_users` CHANGE `add` `add` DOUBLE( 7, 2 ) UNSIGNED NOT NULL DEFAULT '0';

DROP TABLE `game_turnir_users`;

CREATE TABLE `game_turnir_users` (
  `turnir_id` int(15) unsigned NOT NULL,
  `user_id` mediumint(15) unsigned NOT NULL,
  `side` tinyint(1) unsigned NOT NULL,
  `id` int(70) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`),
  KEY `turnir_id` (`turnir_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

ALTER TABLE `game_gorod` ADD `MS_THROW` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0';

DROP TABLE `game_turnir`;

CREATE TABLE `game_turnir` (
  `id` int(15) unsigned NOT NULL auto_increment,
  `type` tinyint(1) unsigned NOT NULL,
  `level_min` int(4) unsigned NOT NULL,
  `kol` tinyint(1) unsigned NOT NULL,
  `timeout` smallint(1) unsigned NOT NULL,
  `level_max` int(4) unsigned NOT NULL,
  `format` tinyint(1) unsigned NOT NULL,
  `timestamp` int(15) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

ALTER TABLE `craft_build_lumberjack` ADD `reserve_user_id` MEDIUMINT( 15 ) UNSIGNED NOT NULL ;
ALTER TABLE `craft_build_lumberjack` ADD `reserve_time` INT( 15 ) UNSIGNED NOT NULL ;
ALTER TABLE `craft_build_stonemason` ADD `reserve_user_id` MEDIUMINT( 15 ) UNSIGNED NOT NULL ;
ALTER TABLE `craft_build_stonemason` ADD `reserve_time` INT( 15 ) UNSIGNED NOT NULL ;
ALTER TABLE `craft_resource_user` DROP INDEX `user_id_2`;
ALTER TABLE `craft_resource_user` ADD UNIQUE (`user_id` ,`res_id`);

ALTER TABLE `game_turnir` ADD `map` INT( 6 ) UNSIGNED NOT NULL ;

ALTER TABLE `game_turnir_users` 
ADD UNIQUE `turnir_id_2` ( `turnir_id` , `user_id` );

ALTER TABLE `craft_build_lumberjack` ADD `chance` SMALLINT( 2 ) UNSIGNED NOT NULL ;
ALTER TABLE `craft_build_stonemason` ADD `chance` SMALLINT( 2 ) UNSIGNED NOT NULL ;

ALTER TABLE `houses_users` ADD `stone_repair` SMALLINT( 3 ) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'треб. камней для ремонта',
ADD `doska_repair` SMALLINT( 3 ) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'треб. досок для ремонта';

ALTER TABLE `craft_stat` CHANGE `vip` `vip` INT( 4 ) NOT NULL DEFAULT '0';

ALTER TABLE `game_users_crafts` ADD `profile` TINYINT( 1 ) UNSIGNED NOT NULL COMMENT 'признак профильной профессии';
ALTER TABLE `game_users_crafts` ADD `last_time` INT( 14 ) UNSIGNED NOT NULL COMMENT 'время последней попытки';

ALTER TABLE `combat_users` CHANGE `side` `side` MEDIUMINT( 21 ) NOT NULL DEFAULT '0' COMMENT 'поле, по которому определяются союзники';