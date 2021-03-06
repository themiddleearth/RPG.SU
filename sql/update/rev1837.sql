CREATE TABLE `game_lr_services` (
`serv_id` INT( 12 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
`name` VARCHAR( 70 ) NOT NULL COMMENT 'Название услуги',
`cost` SMALLINT( 4 ) UNSIGNED NOT NULL DEFAULT '1' COMMENT 'Стоимость в ЛР',
PRIMARY KEY ( `serv_id` )
) ENGINE = MYISAM COMMENT = 'Услуги за ЛР';

CREATE TABLE `game_lr_services_hist` (
`id` INT NOT NULL AUTO_INCREMENT ,
`user_id` MEDIUMINT( 15 ) UNSIGNED NOT NULL ,
`serv_id` INT( 6 ) UNSIGNED NOT NULL ,
`lr` SMALLINT( 4 ) UNSIGNED NOT NULL ,
PRIMARY KEY ( `id` ) 
) ENGINE = MYISAM COMMENT = 'История покупок за ЛР';

insert into game_lr_services (serv_id,name,cost) VALUES ('1','Ñìåíà èãðîâîãî èìåíè','10'), ('2','Ìåäàëü Ñëàâû','5'), ('3','Ïåðåíîñ äîìà','10'), ('4', 'Çàáûòü ïðîôåññèþ', '2');