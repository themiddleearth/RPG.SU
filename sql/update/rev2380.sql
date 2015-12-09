ALTER TABLE  `combat_users` 
ADD  `MS_BERSERK` SMALLINT( 3 ) NOT NULL DEFAULT  '0',
ADD  `MS_PRUDENCE` SMALLINT( 3 ) NOT NULL DEFAULT  '0',
ADD  `MS_VAMPIRE` SMALLINT( 3 ) NOT NULL DEFAULT  '0',
ADD  `MS_SPIKES` SMALLINT( 3 ) NOT NULL DEFAULT  '0',
ADD  `NPC_DEFENCE` SMALLINT( 3 ) NOT NULL DEFAULT  '0',
DROP `svitok`,
CHANGE `not_exp` `k_exp` INT( 5 ) UNSIGNED NOT NULL DEFAULT '100',
CHANGE `not_gp` `k_gp` INT( 5 ) UNSIGNED NOT NULL DEFAULT '100';

ALTER TABLE  `game_maps` 
CHANGE `not_exp` `k_exp` INT( 5 ) UNSIGNED NOT NULL DEFAULT '100',
CHANGE `not_gp` `k_gp` INT( 5 ) UNSIGNED NOT NULL DEFAULT '100';

ALTER TABLE  `combat` 
ADD  `npc` SMALLINT( 1 ) NOT NULL DEFAULT  '0';

ALTER TABLE  `game_users` ADD `hide_charges` TINYINT( 1 ) NOT NULL DEFAULT  '0';

ALTER TABLE  `game_users_archive` ADD `hide_charges` TINYINT( 1 ) NOT NULL DEFAULT  '0';

CREATE or REPLACE VIEW `view_active_users` AS select `game_users`.`user_id` AS `user_id`,`game_users`.`user_name` AS `user_name`,`game_users`.`user_pass` AS `user_pass`,`game_users`.`name` AS `name`,`game_users`.`HP` AS `HP`,`game_users`.`HP_MAX` AS `HP_MAX`,`game_users`.`MP` AS `MP`,`game_users`.`MP_MAX` AS `MP_MAX`,`game_users`.`STM` AS `STM`,`game_users`.`STM_MAX` AS `STM_MAX`,`game_users`.`EXP` AS `EXP`,`game_users`.`GP` AS `GP`,`game_users`.`STR` AS `STR`,`game_users`.`NTL` AS `NTL`,`game_users`.`PIE` AS `PIE`,`game_users`.`VIT` AS `VIT`,`game_users`.`DEX` AS `DEX`,`game_users`.`SPD` AS `SPD`,`game_users`.`CW` AS `CW`,`game_users`.`CC` AS `CC`,`game_users`.`race` AS `race`, `game_users`.`complects` AS `complects`, `game_users`.`avatar` AS `avatar`,`game_users`.`lucky` AS `lucky`,`game_users`.`clevel` AS `clevel`, `game_users`.`reinc` AS `reinc`, `game_users`.`bound` AS `bound`,`game_users`.`exam` AS `exam`,`game_users`.`vsadnik` AS `vsadnik`,`game_users`.`win` AS `win`,`game_users`.`lose` AS `lose`,`game_users`.`clan_id` AS `clan_id`,`game_users`.`dvij` AS `dvij`,`game_users`.`view_chat` AS `view_chat`,`game_users`.`minestone` AS `minestone`,`game_users`.`minestonetime` AS `minestonetime`,`game_users`.`mineore` AS `mineore`,`game_users`.`mineoretime` AS `mineoretime`,`game_users`.`minewood` AS `minewood`,`game_users`.`minewoodtime` AS `minewoodtime`,`game_users`.`clan_items_old` AS `clan_items_old`,`game_users`.`STR_MAX` AS `STR_MAX`,`game_users`.`NTL_MAX` AS `NTL_MAX`,`game_users`.`PIE_MAX` AS `PIE_MAX`,`game_users`.`VIT_MAX` AS `VIT_MAX`,`game_users`.`DEX_MAX` AS `DEX_MAX`,`game_users`.`SPD_MAX` AS `SPD_MAX`,`game_users`.`x` AS `x`,`game_users`.`y` AS `y`,`game_users`.`sector` AS `sector`,`game_users`.`view_smile` AS `view_smile`,`game_users`.`view_img` AS `view_img`,`game_users`.`hide` AS `hide`,`game_users`.`arcomage` AS `arcomage`,`game_users`.`arcomage_win` AS `arcomage_win`,`game_users`.`arcomage_lose` AS `arcomage_lose`,`game_users`.`maze_win` AS `maze_win`,`game_users`.`HP_MAXX` AS `HP_MAXX`,`game_users`.`stroitel` AS `stroitel`,`game_users`.`sobiratel` AS `sobiratel`,`game_users`.`minemetal` AS `minemetal`,`game_users`.`alchemist` AS `alchemist`,`game_users`.`stroiteltime` AS `stroiteltime`,`game_users`.`alchemisttime` AS `alchemisttime`,`game_users`.`sobirateltime` AS `sobirateltime`,`game_users`.`minemetaltime` AS `minemetaltime`,`game_users`.`lucky_max` AS `lucky_max`,`game_users`.`sklon` AS `sklon`, `game_users`.`hide_charges` AS `hide_charges`,`game_users_active_delay`.`delay` AS `delay`,`game_users_active_delay`.`delay_reason` AS `delay_reason`, `game_users_active_delay`.`block` AS `block` from (`game_users` join (`game_users_active` join `game_users_active_delay`)) where ((`game_users`.`user_id` = `game_users_active`.`user_id`) and (`game_users`.`user_id` = `game_users_active_delay`.`user_id`) and (`game_users_active`.`last_active` > (unix_timestamp() - 300)));
 
INSERT INTO `game_skills` (`id`, `name`, `descr`, `level`, `reinc`, `sgroup`) VALUES
(26, 'Áåðñåðê', 'Ïîçâîëÿåò íàíåñòè äîïîëíèòåëüíûé óðîí ïî âûáðàííîìó ïðîòèâíèêó.', 15, 1, 0),
(27, 'Ðàñ÷¸òëèâîñòü', 'Ïîçâîëÿåò óìåíüøèòü çàòðàòû ìàíû è ýíåðãèè, ñâÿçàííûå ñ äåéñòâèÿìè â áîþ.', 15, 1, 0),
(28, 'Âàìïèðèçì', 'Ïîçâîëÿåò âîññòàíîâèòü ÷àñòü çäîðîâüÿ îò íàíåñ¸ííîãî ñìåðòåëüíîãî óäàðà', 15, 1, 0),
(29, 'Øèïû', 'Ïîçâîëÿåò íàíåñòè äîïîëíèòåëüíûé óðîí ïî ïðîòèâíèêó, íàí¸ñøåìó óäàð.', 15, 1, 0),
(30, 'Ìàñòåð îïûòà', 'Ïîçâîëÿåò óâåëè÷èòü êîëè÷åñòâî îïûòà, ïîëó÷àåìîãî â áîþ.', 15, 1, 0),
(31, 'Ìàñòåð äåíåã', 'Ïîçâîëÿåò óâåëè÷èòü êîëè÷åñòâî äåíåã, ïîëó÷àåìûõ â áîþ.', 15, 1, 0),
(32, 'Çàùèòà Âàëàð', 'Ïîçâîëÿåò óìåíüøèòü óðîí áîòîâ â áîþ.', 15, 1, 0),
(33, 'Óáèéöà', 'Ïîçâîëÿåò íàíåñòè äîïîëíèòåëüíûé óðîí ïðè íàïàäåíèè íà ïðîòèâíèêà.', 15, 1, 0),
(34, 'Ïàëàäèí', 'Ïîçâîëÿåò ïîëó÷èòü äîïîëíèòåëüíóþ çàùèòó íà ïåðâîì õîäó áîÿ.', 15, 1, 0),
(35, 'Íåóÿçâèìîñòü', 'Ïîçâîëÿåò îãðàäèòü ñåáÿ îò íåæåëàòåëüíîãî íàïàäåíèÿ èãðîêîâ.', 15, 1, 0);