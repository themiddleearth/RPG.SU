CREATE TABLE IF NOT EXISTS `game_skills` (
  `id` int(10) NOT NULL AUTO_INCREMENT,  
  `name` varchar(30) NOT NULL,  
  `descr` varchar(300),  
  `level` int(2) NOT NULL DEFAULT '15',    
  `reinc` int(2) NOT NULL DEFAULT '0',
  `sgroup` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)   
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Таблица специализаций';

CREATE TABLE IF NOT EXISTS `game_users_skills` (  
  `id` int(10) NOT NULL AUTO_INCREMENT, 
  `user_id` int(11) NOT NULL,  
  `skill_id` int(10) NOT NULL,  
  `level` int(2) NOT NULL DEFAULT '0',    
   PRIMARY KEY (`id`) 
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Таблица специализаций игроков';

CREATE TABLE IF NOT EXISTS `game_spells` (  
  `id` int(5) NOT NULL AUTO_INCREMENT,    
  `skill_id` int(10) NOT NULL,  
  `name` varchar(30) NOT NULL,  
  `type` varchar(30) NOT NULL,  
  `level` int(2) NOT NULL,    
  `effect` int(2) NOT NULL,    
  `rand` int(2) NOT NULL,    
  `mana` int(3) NOT NULL, 
  PRIMARY KEY (`id`)   
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Таблица заклинаний';

ALTER TABLE `combat_users`
  DROP `MS_KULAK`,
  DROP `MS_WEAPON`,
  DROP `MS_ART`,
  DROP `MS_PARIR`,
  DROP `MS_LUK`,
  DROP `MS_SWORD`,
  DROP `MS_AXE`,
  DROP `MS_SPEAR`,
  DROP `MS_THROW`;
  
ALTER TABLE  `combat_users` ADD  `class_type` SMALLINT( 3 ) NOT NULL DEFAULT  '0',
ADD  `class_level` SMALLINT( 3 ) NOT NULL DEFAULT  '0',
ADD  `MS_WEAPON` SMALLINT( 3 ) NOT NULL DEFAULT  '0',
ADD  `MS_KULAK` SMALLINT( 3 ) NOT NULL DEFAULT  '0',
ADD  `MS_PARIR` SMALLINT( 3 ) NOT NULL DEFAULT  '0',
ADD  `MS_ART` SMALLINT( 3 ) NOT NULL DEFAULT  '0',
ADD  `MS_LUK` SMALLINT( 3 ) NOT NULL DEFAULT  '0',
ADD  `MS_THROW` SMALLINT( 3 ) NOT NULL DEFAULT  '0';

INSERT INTO `game_skills` (`id`, `name`, `descr`, `level`, `reinc`, `sgroup`) VALUES
(1, 'Ìàñòåð ðóáÿùåãî îðóæèÿ', 'Ïîçâîëÿåò îâëàäåòü îðóæèåì ðóáÿùåãî òèïà. Óâåëè÷èâàåò òî÷íîñòü è óðîí,íàíîñèìûé ðóáÿùèì îðóæèåì, íà 1 åäèíèöó.', 15, 0, 1),
(2, 'Ìàñòåð äðîáÿùåãî îðóæèÿ', 'Ïîçâîëÿåò îâëàäåòü îðóæèåì äðîáÿùåãî òèïà. Óâåëè÷èâàåò òî÷íîñòü è óðîí,íàíîñèìûé äðîáÿùèì îðóæèåì, íà 1 åäèíèöó.', 15, 0, 1),
(3, 'Ìàñòåð êîëþùåãî îðóæèÿ', 'Ïîçâîëÿåò îâëàäåòü îðóæèåì êîëþùåãî òèïà. Óâåëè÷èâàåò òî÷íîñòü è óðîí,íàíîñèìûé êîëþùèì îðóæèåì, íà 1 åäèíèöó.', 15, 0, 1),
(10, 'Èñêóññòâî ìàãèè êîëäóíà', 'Ïîçâîëÿåò îâëàäåòü ìàãèåé êîëäóíîâ, ïðèçâàííîé ïîðàæàòü ñâîèõ ïðîòèâíèêîâ.', 15, 0, 1),
(11, 'Èñêóññòâî ìàãèè öåëèòåëÿ', 'Ïîçâîëÿåò îâëàäåòü ìàãèåé öåëèòåëåé, ïðèçâàííîé èñöåëÿòü ëþáûå ðàíû, íàíåñåííûå ïðîòèâíèêîì.', 15, 0, 1),
(12, 'Èñêóññòâî ìàãèè âîëõâà', 'Ïîçâîëÿåò îâëàäåòü ìàãèåé âîëõâîâ, ïðèçâàííîé çàùèùàòü ñåáÿ è ñîðàòíèêîâ îò óäàðîâ ïðîòèâíèêà.', 15, 0, 1),
(13, 'Èñêóññòâî ìàãèè âîëøåáíèêà', 'Ïîçâîëÿåò îâëàäåòü ìàãèåé âîëøåáíèêîâ, â ðàâíîé ñòåïåíè ïðèçâàííîé àòàêîâàòü ñîïåðíèêîâ è ëå÷èòü è çàùèùàòü ñîðàòíèêîâ', 15, 0, 1),
(20, 'Ýêñïåðò àðòåôàêòîâ', 'Ïîçâîëÿåò îâëàäåòü àðòåôàêòàìè. Óâåëè÷èâàåò óðîí, çàùèòó èëè ëå÷åíèå àðòåôàêòîì íà 1 åäèíèöó.', 15, 0, 0),
(21, 'Ìàñòåð êóëà÷íîãî áîÿ', 'Ïîçâîëÿåò îâëàäåòü ìàñòåðñòâîì êóëà÷íîãî áîÿ. Óâåëè÷èâàåò óðîí, íàíîñèìûé ïðè óäàðå êóëàêîì, íà 5 åäèíèö.', 15, 0, 0),
(22, 'Ïàðèðîâàíèå', 'Ïîçâîëÿåò îâëàäåòü ìàñòåðñòâîì ôèçè÷åñêîé çàùèòû. Óìåíüøàåò óðîí, íàíîñèìûé îðóæèåì ïðîòèâíèêà, íà 1 åäèíèöó. Óâåëè÷èâàåò øàíñ óñêîëüçíóòü îò óäàðà îð', 15, 0, 0),
(23, 'Ìàñòåð ñòðåëêîâîãî îðóæèÿ', 'Ïîçâîëÿåò îâëàäåòü îðóæèåì êîëþùåãî òèïà. Óâåëè÷èâàåò óðîí, íàíîñèìûé êîëþùèì îðóæèåì, íà 1 åäèíèöó.', 10, 0, 0),
(24, 'Ìàñòåð ìåòàòåëüíîãî îðóæèÿ', 'Ïîçâîëÿåò îâëàäåòü îðóæèåì ìåòàòåëüíîãî òèïà. Óâåëè÷èâàåò òî÷íîñòü ïîïàäàíèÿ ïî ïðîòèâíèêó ìåòàòåëüíûì îðóæèåì. ', 10, 0, 0),
(9, 'Ýêñïåðò âîèíñêèõ óìåíèé', 'Ïîçâîëÿåò îâëàäåòü îáùèìè ïðèåìàìè âëàäåíèÿ îðóæèåì.', 15, 0, 0),
(25, 'Âåðõîâàÿ åçäà', 'Ïîçâîëÿåò îâëàäåòü ìàñòåðñòâîì âåðõîâîé åçäû è îáóçäàòü ðàçëè÷íûõ åçäîâûõ ñóùåñòâ.', 15, 0, 0);