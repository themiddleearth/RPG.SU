ALTER TABLE  `game_log` ADD  `ptype` INT NOT NULL DEFAULT  '0' COMMENT  '0 - всем, 1 - игроку, 2 - клану, 3 - склонности' AFTER  `fromm` ;
