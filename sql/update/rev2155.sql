ALTER TABLE  `game_items_factsheet` ADD  `life_time` INT( 5 ) NOT NULL DEFAULT  '0';
ALTER TABLE  `game_items` ADD  `dead_time` INT( 15 ) NOT NULL DEFAULT  '0';
ALTER TABLE  `game_items_factsheet` ADD  `can_use` INT( 1 ) NOT NULL DEFAULT  '0' AFTER  `can_up`;
CREATE TABLE IF NOT EXISTS `game_users_songs` (
  `user_id` int(11) NOT NULL,  
  `song_date` int(15) NOT NULL,
  `prize` int(1) NOT NULL,
  UNIQUE (`user_id`, `song_date`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `game_items_factsheet` (`name`, `type`, `quantity`, `indx`, `deviation`, `mode`, `weight`, `curse`, `img`, `item_uselife`, `item_cost`, `ostr`, `ontl`, `opie`, `ovit`, `odex`, `ospd`, `oclevel`, `dstr`, `dntl`, `dpie`, `dvit`, `ddex`, `dspd`, `sv`, `race`, `hp_p`, `mp_p`, `stm_p`, `cc_p`, `view`, `redkost`, `imgbig`, `personal`, `cooldown`, `type_weapon`, `type_weapon_need`, `def_type`, `def_index`, `breakdown`, `item_uselife_max`, `magic_def_index`, `in_two_hands`, `olucky`, `dlucky`, `can_up`, `can_use`, `clan_id`, `kol_per_user`, `life_time`) VALUES
('Ýëèêñèð Áàðäà', 13, 0, 0, 0, '', 0.00, '', 'eliksir/bard_elik', 100.00, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 0, 0, 0, 0, 0, '1', '', 'eliksir/bard_elik', 1, 0, 0, 0, 0, 0, 0, 100, 0, 0, 0, 0, 0, 0, 0, 0, 3600),
('Æåëåçíàÿ øêàòóëêà', 97, 0, 0, 0, '', 0.00, '', 'other/shkatulka_iron', 100.00, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 0, 0, 0, 0, 0, '1', '', '', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 14400),
('Ìåäíàÿ øêàòóëêà', 97, 0, 0, 0, '', 0.00, '', 'other/shkatulka_med', 100.00, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 0, 0, 0, 0, 0, '1', '', '', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 14400),
('Ñåðåáðÿíàÿ øêàòóëêà', 97, 0, 0, 0, '', 0.00, '', 'other/shkatulka_ser', 100.00, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 0, 0, 0, 0, 0, '1', '', '', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 14400),
('Çîëîòàÿ øêàòóëêà', 97, 0, 0, 0, '', 0.00, '', 'other/shkatulka_gold', 100.00, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 0, 0, 0, 0, 0, '1', '', '', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 14400),
('Ìèôðèëîâàÿ øêàòóëêà', 97, 0, 0, 0, '', 0.00, '', 'other/shkatulka_mifril', 100.00, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 0, 0, 0, 0, 0, '1', '', '', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 14400);