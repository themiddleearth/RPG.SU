ALTER TABLE arcomage_history ADD INDEX arcomage_id_3 (arcomage_id);

ALTER TABLE blog_comm ADD INDEX comm_id (comm_id,user_id),
  ADD INDEX comm_id_2 (comm_id,post_id),
  ADD INDEX comm_id_3 (comm_id,post_id,user_id);
  
ALTER TABLE blog_friends ADD INDEX user_id_2 (user_id,friend_id),
  ADD INDEX friend_id (friend_id);
  
ALTER TABLE blog_love ADD INDEX friend_id (friend_id),
  ADD INDEX user_id_2 (user_id,friend_id);
  
 ALTER TABLE `blog_rating` CHANGE `user_id` `user_id` MEDIUMINT( 15 ) UNSIGNED NOT NULL DEFAULT '0' ;
 
 ALTER TABLE `game_npc` ADD `npc_alive` TINYINT( 4 ) NOT NULL DEFAULT '1';
 
  ALTER TABLE `game_npc_drop` ADD `random_all` INT( 3 ) UNSIGNED NOT NULL ;
  
  CREATE TABLE game_users_func (
  user_id int(11) NOT NULL,
  func_id tinyint(4) NOT NULL,
  PRIMARY KEY  (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

