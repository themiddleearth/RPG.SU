CREATE TABLE `game_npc_option` (
`opt_id` INT NOT NULL AUTO_INCREMENT ,
`name` VARCHAR( 50 ) NOT NULL ,
PRIMARY KEY ( `opt_id` ) 
) ENGINE = MYISAM COMMENT = 'Список опций ботов';

CREATE TABLE `game_npc_set_option` (
`id` INT NOT NULL AUTO_INCREMENT ,
`npc_id` MEDIUMINT( 15 ) UNSIGNED NOT NULL ,
`opt_id` INT UNSIGNED NOT NULL ,
PRIMARY KEY ( `id` ) 
) ENGINE = MYISAM COMMENT = 'Опции ботов';

CREATE TABLE `game_npc_set_option_value` (
`id` INT UNSIGNED NOT NULL ,
`number` SMALLINT (3) UNSIGNED NOT NULL ,
`value` INT( 10 ) UNSIGNED NOT NULL ,
UNIQUE (`id` ,`number`)
) ENGINE = MYISAM COMMENT = 'Значения опций ботов';

INSERT INTO `game_npc_option` (`opt_id` ,`name` )
VALUES ('1', 'Íå ïðîìàõèâàåòñÿ'), ('2', 'Íå çàùèùàåòñÿ'), ('3', 'Áü¸ò îäíîãî èãðîêà'), ('4', 'Ôèêñèðîâàííûé óðîí'), ('5', 'Áü¸ò íà ïðîöåíò îò æèçíåé èãðîêà'), ('6', 'Ïðèçûâàåò áîòîâ'), ('7', 'Áîò ïî óðîâíþ');