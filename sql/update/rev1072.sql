ALTER TABLE `game_maps`
  DROP `boy_auto`,
  DROP `boy_auto_type`,
  DROP `join_auto`;
  
ALTER TABLE `game_maps` ADD `boy_type7` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `game_npc` ADD `npc_win` INT( 10 ) UNSIGNED NOT NULL ;

DROP TABLE `combat`, `combat_chat`, `combat_history`, `combat_lose_user`, `combat_new_user`, `combat_shed`, `combat_users`, `combat_users_exp`, `combat_user_func`;

-- --------------------------------------------------------

--
-- ��������� ������� `combat`
--

CREATE TABLE IF NOT EXISTS `combat` (
  `combat_id` int(30) unsigned NOT NULL auto_increment COMMENT 'id ���',
  `hod` smallint(5) unsigned NOT NULL COMMENT '������� ��� ���',
  `combat_type` tinyint(1) unsigned NOT NULL default '1' COMMENT '��� ���',
  `time_last_hod` int(14) unsigned NOT NULL default '0' COMMENT 'timestamp ���������� ������� ����',
  `map_name` smallint(3) unsigned NOT NULL default '0' COMMENT '���������� ���',
  `map_xpos` tinyint(3) unsigned NOT NULL default '0',
  `map_ypos` tinyint(3) unsigned NOT NULL default '0',
  `start_time` int(14) unsigned NOT NULL default '0' COMMENT '����� ������ ���',
  PRIMARY KEY  (`combat_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- ��������� ������� `combat_actions`
--

CREATE TABLE IF NOT EXISTS `combat_actions` (
  `combat_id` int(30) unsigned NOT NULL,
  `hod` smallint(5) unsigned NOT NULL,
  `user_id` mediumint(10) unsigned NOT NULL,
  `action_type` tinyint(3) unsigned NOT NULL COMMENT '11 - ����� �������, 12 - ����� �������, 13 - ����� ������, 14 - ����� ����������, 21 - ������ �����, 22 - ������ ������, 23 - ������ ����������, 31 - ������� ������, 32 - ������� ����������, 33 - ������� ���������',
  `action_chem` int(11) NOT NULL,
  `action_kogo` mediumint(15) NOT NULL,
  `action_kuda` tinyint(4) NOT NULL,
  `action_proc` tinyint(4) NOT NULL,
  `action_priem` tinyint(2) unsigned NOT NULL default '0' COMMENT '������� ������� ������',
  `action_rand` int(9) unsigned NOT NULL COMMENT '��������� ����� - ������� �������� � ������� ����',
  KEY `combat_id` (`combat_id`,`hod`,`action_type`,`action_rand`),
  KEY `combat_id_2` (`combat_id`,`hod`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- ��������� ������� `combat_lose_user`
--

CREATE TABLE IF NOT EXISTS `combat_lose_user` (
  `combat_id` int(20) unsigned NOT NULL default '0',
  `user_id` int(20) unsigned NOT NULL default '0',
  `host` int(15) NOT NULL default '0',
  `host_more` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`combat_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- ��������� ������� `combat_users`
--

CREATE TABLE IF NOT EXISTS `combat_users` (
  `user_id` mediumint(15) unsigned NOT NULL COMMENT 'id ������',
  `npc` tinyint(1) unsigned NOT NULL default '0' COMMENT '������� ������ �� NPC',
  `time_last_active` int(14) unsigned NOT NULL default '0' COMMENT 'timestamp ���������� �������� ������',
  `join` tinyint(1) unsigned NOT NULL default '0' COMMENT '0 - ����� ��� ����� � ���, 1 - ����� ������������� � ��� � ������� ��� �� �� �����',
  `name` varchar(50) NOT NULL,
  `clevel` smallint(3) unsigned NOT NULL default '0',
  `clan_id` mediumint(10) unsigned NOT NULL default '0',
  `combat_id` int(30) unsigned NOT NULL,
  `eliksir` tinyint(1) unsigned NOT NULL default '0' COMMENT '������� ������������� �������� � ���',
  `call_clan` tinyint(1) unsigned NOT NULL default '0' COMMENT '������� ������ ������� ����� � ���',
  `side` smallint(3) NOT NULL default '0' COMMENT '����, �� �������� ������������ ��������',
  `svitok` tinyint(1) unsigned NOT NULL default '0' COMMENT '����� ������ �� �������� ����� ����� � ���',
  `k_komu` mediumint(15) unsigned NOT NULL default '0' COMMENT 'id ������, � �������� ������������� �������',
  `HP` mediumint(4) NOT NULL,
  `HP_MAX` mediumint(4) NOT NULL,
  `MP` mediumint(4) NOT NULL,
  `MP_MAX` mediumint(4) NOT NULL,
  `STM` mediumint(4) NOT NULL,
  `STM_MAX` mediumint(4) NOT NULL,
  `STR` mediumint(4) NOT NULL,
  `DEX` mediumint(4) NOT NULL,
  `SPD` mediumint(4) NOT NULL,
  `VIT` mediumint(4) NOT NULL,
  `NTL` mediumint(4) NOT NULL,
  `PIE` mediumint(4) NOT NULL,
  `lucky` mediumint(4) NOT NULL,
  `MS_KULAK` smallint(3) unsigned NOT NULL,
  `MS_WEAPON` smallint(3) unsigned NOT NULL,
  `MS_ART` smallint(3) unsigned NOT NULL,
  `MS_PARIR` smallint(3) unsigned NOT NULL,
  `MS_LUK` smallint(3) unsigned NOT NULL,
  `MS_SWORD` smallint(3) unsigned NOT NULL,
  `MS_AXE` smallint(3) unsigned NOT NULL,
  `MS_SPEAR` smallint(3) unsigned NOT NULL,
  `pol` enum('','male','female') NOT NULL COMMENT '��� ������',
  `avatar` varchar(50) NOT NULL,
  `pass` tinyint(1) unsigned NOT NULL default '0' COMMENT '������� �������� ���� (�� ������������)',
  `sklon` tinyint(1) unsigned NOT NULL default '0' COMMENT '���������� ������',
  `not_exp` tinyint(1) unsigned NOT NULL default '0' COMMENT '�� ������ ���� �� ���',
  `not_gp` tinyint(1) unsigned NOT NULL default '0' COMMENT '�� ������ ����� �� ���',
  `race` varchar(30) NOT NULL,
  PRIMARY KEY  (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- ��������� ������� `combat_users_exp`
--

CREATE TABLE IF NOT EXISTS `combat_users_exp` (
  `user_id` mediumint(15) unsigned NOT NULL default '0',
  `combat_id` int(20) unsigned NOT NULL default '0',
  `exp` int(10) unsigned NOT NULL default '0',
  `gp` double(6,2) unsigned NOT NULL default '0.00',
  `prot_id` mediumint(15) unsigned NOT NULL default '0',
  UNIQUE KEY `user_id` (`user_id`,`combat_id`,`prot_id`),
  KEY `combat_id` (`combat_id`),
  KEY `user_id_2` (`user_id`),
  KEY `combat_id_2` (`combat_id`,`prot_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- ��������� ������� `combat_users_state`
--

CREATE TABLE IF NOT EXISTS `combat_users_state` (
  `user_id` mediumint(15) unsigned NOT NULL COMMENT 'id ������',
  `state` smallint(2) unsigned NOT NULL,
  `combat_id` int(30) unsigned NOT NULL COMMENT 'id ���',
  `hod` smallint(3) unsigned NOT NULL COMMENT '��� ���, �� ������� ����� ����-�������-�����',
  PRIMARY KEY  (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;