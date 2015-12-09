<?

require_once("db.inc.php");
require_once("/home/vhosts/rpg.su/web/inc/lib.inc.php");
require_once("/home/vhosts/rpg.su/web/class/class_item.php");
require_once("/home/vhosts/rpg.su/web/class/class_res.php");
define('img_domain','images.rpg.su');

date_default_timezone_SET('Europe/Moscow');

function send_error($error,$theme='Ошибка в скриптах')
{  
  if ($theme!='Ошибка в скриптах')
  {
	$kol = mysql_result(mysql_query("SELECT COUNT(*) FROM game_pm WHERE komu=612 AND view=0 AND theme='".$theme."'"),0,0);
  }
  else
  {
	$kol = 0;
  }
  
  if ($kol==0)
  {
		myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, clan, time) VALUES ('612', '0', '$theme', '".mysql_real_escape_string($error)."', '0','0',".time().")");
  }
  
  if ($theme!='Ошибка в скриптах')
  {
	$kol = mysql_result(mysql_query("SELECT COUNT(*) FROM game_pm WHERE komu=14475 AND view=0 AND theme='".$theme."'"),0,0);
  }
  else
  {
	$kol = 0;
  }
  if ($kol==0)
	  myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, clan, time) VALUES ('14475', '0', '$theme', '".mysql_real_escape_string($error)."', '0','0',".time().")");
	  
  
}

SET_error_handler('error_handler',E_ALL);

function error_handler($errNo, $errStr, $errFile, $errLine)
{
	$handlers = ob_list_handlers();
	while ( ! empty($handlers) )    {
		ob_end_clean();
		$handlers = ob_list_handlers();
	}
	$error_message = 'ERRNO: '.$errNo.'<br>'.
					 'TEXT: '.$errStr.'<br>'.
		   'LOCATION: '.$errFile.
		   ', line '.$errLine;
	send_error('<span style="color:red;font-weight:400;font-size:12pt">'.$error_message.'</span><br><br><br><pre>'.print_r(debug_backtrace(),true).'</pre>','Ошибка: '.$errNo.' - '.$errFile.', '.$errLine.'');
}

function jump_random_query(&$query)
{
	$all = mysql_num_rows($query);
	if ($all>0)
	{
		$r = mt_rand(0,$all-1);
		mysql_data_seek($query,$r);
		return 1;
	}
	return 0;
}

function start_combat($type, $min_kol)
{		
	create_autocombat($type, $min_kol);
}

function myquery($query)
{
	$backtrace = debug_backtrace();
	$back1 = $backtrace;
	$backtrace = " in : " . $backtrace[0]["file"] . ", on line: " . $backtrace[0]["line"] . "";
	$result = mysql_query($query) or send_error(mysql_errno() . ": <b>" . mysql_error() . $backtrace . "<br /><br /><br />" . $query . "");
	return $result;
}

function move_teleport($tm)
{
	/*$sel_obj = myquery("SELECT id FROM game_obj WHERE moved=1 AND movetime='$tm'");
	if ($sel_obj!=false AND mysql_num_rows($sel_obj)>0)
	{
		while (list($obj) = mysql_fetch_array($sel_obj))
		{
			$sel_cur_map = myquery("SELECT name,to_map_name,to_map_xpos,to_map_ypos,xpos,ypos FROM game_map WHERE town=$obj AND to_map_name>0");
			while ($cur_map = mysql_fetch_array($sel_cur_map))
			{
				$sel_map = myquery("SELECT xpos,ypos FROM game_map WHERE to_map_name=0 AND town=0 AND name=".$cur_map['name']."");
				jump_random_query($sel_map);
				$map = mysql_fetch_assoc($sel_map);
				myquery("UPDATE game_map SET town=0,to_map_name=0,to_map_xpos=0,to_map_ypos=0 WHERE name=".$cur_map['name']." AND xpos=".$cur_map['xpos']." AND ypos=".$cur_map['ypos']."");
				myquery("UPDATE game_map SET town=".$obj.",to_map_name=".$cur_map['to_map_name'].",to_map_xpos=".$cur_map['to_map_xpos'].",to_map_ypos=".$cur_map['to_map_ypos']." WHERE name=".$cur_map['name']." AND xpos=".$map['xpos']." AND ypos=".$map['ypos']."");
			}
		}
	}*/
	return 0;
}

function delete_user($user, $name="")
{
	$user = (int)$user;
	if ($user>0)
	{
		if ($name=="")
		{
			list($name)=mysql_fetch_array(myquery("Select name FROM game_users WHERE user_id='".$user."' UNION ALL Select name FROM game_users_archive WHERE user_id='".$user."'"));
		}
		
		myquery("DELETE FROM game_activity WHERE name='".$name."'");
		myquery("DELETE FROM game_activity_mult WHERE name='".$name."'");
		myquery("DELETE FROM game_mag WHERE name='".$name."'");
		
		myquery("DELETE FROM arcomage_call WHERE user_id='".$user."'");
		myquery("DELETE FROM arcomage_history WHERE user_id='".$user."'");
		myquery("DELETE FROM arcomage_users WHERE user_id='".$user."'");
		myquery("DELETE FROM arcomage_users_cards WHERE user_id='".$user."'");		
       
        myquery("DELETE FROM blog_closed WHERE user_id='".$user."' OR close_id='".$user."'");		
		myquery("DELETE FROM blog_friends WHERE user_id='".$user."' OR friend_id='".$user."'");		
		myquery("DELETE FROM blog_love WHERE user_id='".$user."' OR friend_id='".$user."'");		
		myquery("DELETE FROM blog_comm WHERE post_id in (select post_id FROM blog_post WHERE user_id='".$user."')");
		myquery("DELETE FROM blog_post WHERE user_id='".$user."'");
		myquery("DELETE FROM blog_rating WHERE user_id='".$user."'");
		myquery("DELETE FROM blog_users WHERE user_id='".$user."'");
		myquery("UPDATE blog_comm SET user_id=0 WHERE user_id='".$user."'");
		
        myquery("DELETE FROM combat_actions WHERE user_id='".$user."'");
		myquery("DELETE FROM combat_lose_user WHERE user_id='".$user."'");
		myquery("DELETE FROM combat_users WHERE user_id='".$user."'");
		myquery("DELETE FROM combat_users_exp WHERE user_id='".$user."' or prot_id='".$user."'");
		myquery("DELETE FROM combat_users_state WHERE user_id='".$user."'");
		
        myquery("DELETE FROM craft_build_rab WHERE user_id='".$user."'");
		myquery("DELETE FROM craft_build_user WHERE user_id='".$user."'");
        myquery("DELETE FROM craft_build_founder WHERE user_id='".$user."'");       
		myquery("DELETE FROM craft_resource_market WHERE user_id='".$user."'");
		myquery("DELETE FROM craft_resource_user WHERE user_id='".$user."'");
        myquery("DELETE FROM craft_stat WHERE user='".$user."'");
		myquery("DELETE FROM craft_user_func WHERE user_id='".$user."'");
		myquery("UPDATE craft_build_lumberjack SET user_id=0 WHERE user_id='".$user."'");
        myquery("UPDATE craft_build_mining SET user_id=0 WHERE user_id='".$user."'");
        myquery("UPDATE craft_build_stonemason SET user_id=0 WHERE user_id='".$user."'");
		
        myquery("DELETE FROM dungeon_quests_done WHERE user_id='".$user."'");
		myquery("DELETE FROM dungeon_users_data WHERE user_id='".$user."'");
		myquery("DELETE FROM dungeon_users_progress WHERE user_id='".$user."'");
		
        myquery("DELETE FROM forum_read WHERE user_id='".$user."'");
        myquery("DELETE FROM forum_setup WHERE user_id='".$user."'");
        myquery("DELETE FROM forum_thanks WHERE user_id='".$user."'");
		$topic_check=myquery("SELECT id FROM forum_topics WHERE top like 'ПСЖ (удалить По Собственному Желанию)'");
		while (list($topic_id)=mysql_fetch_array($topic_check))
		{
			myquery("DELETE FROM forum_otv WHERE user_id='".$user."' AND text like 'ПСЖ' AND topics_id='".$topic_id."' ");
		}
				
		myquery("DELETE FROM game_ban WHERE user_id='".$user."'");
		myquery("DELETE FROM game_bank WHERE user_id='".$user."'");
		myquery("DELETE FROM game_bank_db_kr WHERE user_id='".$user."'");
		myquery("DELETE FROM game_chat_ignore WHERE user_id='".$user."' or ignore_id='".$user."'");
		myquery("DELETE FROM game_chat_log WHERE user_id='".$user."'");
		myquery("DELETE FROM game_chat_nakaz WHERE user_id='".$user."'");
		myquery("DELETE FROM game_chat_option WHERE user_id='".$user."'");
		myquery("DELETE FROM game_combats_users WHERE user_id='".$user."'");
		myquery("DELETE FROM game_gift WHERE user_to='".$user."'");
		myquery("DELETE FROM game_invite WHERE user_id='".$user."'");
		myquery("DELETE FROM game_items_opis WHERE item_id in (select id FROM game_items WHERE user_id='".$user."')");
		myquery("DELETE FROM game_items WHERE user_id='".$user."'");
		myquery("DELETE FROM quest_constructor WHERE user_id='".$user."'");
		
		myquery("DELETE FROM game_medal_users WHERE user_id='".$user."'");
		myquery("DELETE FROM game_nakaz WHERE user_id='".$user."'");
        myquery("DELETE FROM game_npc WHERE for_user_id='".$user."'");
		myquery("DELETE FROM game_npc_guild_log WHERE user_id='".$user."'");
		myquery("DELETE FROM game_obelisk_users WHERE user_id='".$user."'");
		myquery("DELETE FROM game_pm WHERE komu='".$user."'");
		myquery("DELETE FROM game_pm WHERE otkogo='".$user."'");
		myquery("DELETE FROM game_pm_deleted WHERE komu='".$user."'");
		myquery("DELETE FROM game_pm_deleted WHERE otkogo='".$user."'");
		myquery("DELETE FROM game_pm_folder WHERE user_id='".$user."'");
		myquery("DELETE FROM game_port_bil WHERE user_id='".$user."'");
		myquery("DELETE FROM game_prison WHERE user_id='".$user."'");
		myquery("DELETE FROM game_quest_users WHERE user_id='".$user."'");
		myquery("DELETE FROM game_stats_timemarker WHERE user_id='".$user."'");		
        myquery("DELETE FROM game_turnir_users WHERE user_id='".$user."'");		
		myquery("DELETE FROM game_users_skills WHERE user_id='".$user."'");
		myquery("DELETE FROM game_users_active WHERE user_id='".$user."'");
		myquery("DELETE FROM game_users_active_delay WHERE user_id='".$user."'");
		myquery("DELETE FROM game_users_active_host WHERE user_id='".$user."'");
		myquery("DELETE FROM game_users_brak WHERE user1='".$user."'");
		myquery("DELETE FROM game_users_brak WHERE user2='".$user."'");
        myquery("DELETE FROM game_users_crafts WHERE user_id='".$user."'");
		myquery("DELETE FROM game_users_guild WHERE user_id=".$user."");
		myquery("DELETE FROM game_users_clan_reg WHERE user_id='".$user."'");
		myquery("DELETE FROM game_users_data WHERE user_id='".$user."'");
		myquery("DELETE FROM game_users_event WHERE user_id='".$user."'");
        myquery("DELETE FROM game_users_horses WHERE user_id='".$user."'");
		myquery("DELETE FROM game_users_func WHERE user_id='".$user."'");
		myquery("DELETE FROM game_users_intro WHERE user_id='".$user."'");
		myquery("DELETE FROM game_users_map WHERE user_id='".$user."'");
		myquery("DELETE FROM game_users_maze WHERE user_id='".$user."'");
		myquery("DELETE FROM game_users_npc WHERE user_id='".$user."'");
		myquery("DELETE FROM game_users_stat_exp WHERE user_id='".$user."'");
		myquery("DELETE FROM game_users_stat_gp WHERE user_id='".$user."'");
		myquery("DELETE FROM game_users_songs WHERE user_id='".$user."'");
		myquery("DELETE FROM game_users_reincarnation WHERE user_id='".$user."'");		
        myquery("DELETE FROM game_lr_services_hist WHERE user_id=".$user."");
		myquery("DELETE FROM game_users_hunter WHERE user_id=".$user."");
		myquery("DELETE FROM game_users_psg WHERE user_id='".$user."'");
		
		myquery("UPDATE game_tavern SET vladel=612 WHERE vladel='".$user."'");
		myquery("UPDATE game_items SET kleymo=0,kleymo_nomer=0,kleymo_id=0 WHERE kleymo=2 and kleymo_id=".$user."");
		
        myquery("DELETE FROM houses_market WHERE user_id=".$user."");
        myquery("DELETE FROM houses_nalog WHERE user_id=".$user."");
        myquery("DELETE FROM houses_users WHERE user_id=".$user."");
		
		myquery("DELETE FROM game_admins WHERE user_id=".$user."");
		myquery("DELETE FROM game_admins_ip WHERE user_id=".$user."");
        
		myquery("DELETE FROM game_users WHERE user_id=".$user."");
		myquery("DELETE FROM game_users_archive WHERE user_id=".$user."");
	}
	
	return 1;
}
?>