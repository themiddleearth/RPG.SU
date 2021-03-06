CREATE TABLE `game_eliksir_dlit` (
`elik_id` INT NOT NULL AUTO_INCREMENT ,
`dlit` INT( 10 ) UNSIGNED NOT NULL ,
PRIMARY KEY ( `elik_id` ) 
) ENGINE = MYISAM COMMENT = 'Время действия эликсиров';

CREATE TABLE `game_eliksir_alchemist` (
`elik_id` INT NOT NULL AUTO_INCREMENT ,
`alchemist` SMALLINT( 3 ) UNSIGNED NOT NULL ,
`clevel` SMALLINT( 3 ) UNSIGNED NOT NULL ,
`maxtime` INT( 10 ) UNSIGNED NOT NULL ,
`mintime` INT( 10 ) UNSIGNED NOT NULL ,
PRIMARY KEY ( `elik_id` ) 
) ENGINE = MYISAM COMMENT = 'Параметры алхимии эликсиров';

CREATE TABLE `game_eliksir_res` (
`elik_id` INT NOT NULL,
`res_id` SMALLINT( 3 ) UNSIGNED NOT NULL ,
`kol` SMALLINT( 3 ) UNSIGNED NOT NULL ,
UNIQUE (`elik_id` ,`res_id`)
) ENGINE = MYISAM COMMENT = 'Ресурсы эликсиров';

INSERT INTO game_lr_services (`serv_id` ,`name` ,`cost` )
VALUES ('5', 'Ñìåíà ðàñû', '12'), ('6', 'Ïåðå÷èñëèòü ðåéòèíã íà ñ÷¸ò êëàíà', '0'), ('7', 'Ñîçäàíèå óíèêàëüíîé âåùè', '2');

CREATE TABLE `game_craft_prof` (
`prof_id` INT NOT NULL AUTO_INCREMENT ,
`name` VARCHAR( 20 ) NOT NULL ,
PRIMARY KEY ( `prof_id` ) 
) ENGINE = MYISAM COMMENT = 'Таблица профессий';

INSERT INTO `game_craft_prof` (`prof_id` ,`name` )
VALUES ('1', 'Ñîáèðàòåëü'), ('2', 'Àëõèìèê'), ('4', 'Ëåñîðóá'), ('5', 'Êàìåíîò¸ñ'), ('6', 'Ðóäîêîï'), ('7', 'Ïëîòíèê'), ('8', 'Îõîòíèê'), ('9', 'Ñêîðíÿê'), ('10', 'Ëèòåéùèê'), ('11', 'Îðóæåéíèê'), ('12', 'Êóçíåö');

ALTER TABLE `game_obelisk_users` CHANGE `harka` `harka` CHAR( 10 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;