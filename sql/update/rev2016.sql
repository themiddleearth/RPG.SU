INSERT INTO `game_npc_option` (`opt_id` ,`name` )
VALUES ('8', 'Áîò êîïèðóåò íàâûêè èãðîêà'), ('9', 'Áîò êîïèðóåò æèçíè èãðîêà'), ('10', 'Áîò êîïèðóåò ìàíó èãðîêà'), ('11', 'Àâòîãåíåðàöèÿ õàðàêòåðèñòèê'), ('12', 'Âîçìîæíîñòü ïðèñîåäèíåíèÿ â áîé'), ('13', 'Ðåãåíåðèðóþùèé áîò');

ALTER TABLE `game_npc` DROP `npc_flag`;

ALTER TABLE `game_ban` CHANGE `user_id` `user_id` INT( 15 ) NOT NULL DEFAULT '0'