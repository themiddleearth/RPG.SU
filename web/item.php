<?php
require('inc/config.inc.php');
include('inc/lib.inc.php');
require_once('inc/db.inc.php');
require('inc/lib_session.inc.php');

if (function_exists("start_debug")) start_debug(); 

// Временно. До перевода от $char[boy] -> $char[func_id] глобально
$f_id=getFunc($user_id);

if($f_id=='1') {if (function_exists("save_debug")) save_debug(); exit;}

if (!empty($_GET['inv_option']))
{
	switch ($_GET['inv_option'])
	{
		case 'equip':			
			$id=(int)$_GET['id'];			
			$Item = new Item();
			$Item->up($id,0);
		break;

		case 'unequip':
			$Item = new Item();
			$Item->down($_GET['id']);
		break;

		case 'kleymo_return':
			$Item = new Item();
			$Item->kleymo_return($_GET['id']);
		break;

		case 'use':
			$Item = new Item();
			$Item->use_item($_GET['id']);
		break;

		case 'drop':
			$Item = new Item();
			$Item->drop($_GET['id']);
		break;
	
		case 'takeres':
            if (!isset($_GET['id']) or !is_numeric($_GET['id']) or !isset($_GET['col']) or $_GET['col'] <= 0 or !is_numeric($_GET['col'])) break;
			$Res = new Res();
			$Res->take(0,(int)$_GET['id'],(int)$_GET['col']);			
		break;

		case 'take':
			list($maze) = mysql_fetch_array(myquery("SELECT maze FROM game_maps WHERE id=".$char['map_name'].""));
			if ($maze==1 AND !isset($_GET['id']))
			{
				$result_items = myquery("SELECT type,effekt FROM game_maze WHERE map_name='" . $char['map_name'] . "' AND xpos=" . $char['map_xpos'] . " AND ypos=" . $char['map_ypos'] . " LIMIT 1");
				$usl = mysql_num_rows($result_items);
				if ($usl>0)
				{
					list($type , $effekt) = mysql_fetch_array($result_items);
					if ($type>=3 AND $type<=10)
					{
						switch($type)
						{
							case 3:
								$update_users = myquery("UPDATE game_users SET GP=GP + $effekt WHERE user_id=$user_id LIMIT 1");
								setGP($user_id,$effekt,5);
								break;
							case 4:
								$update_users = myquery("UPDATE game_users SET GP=GP - $effekt WHERE user_id=$user_id LIMIT 1");
								setGP($user_id,-$effekt,6);
								break;
							case 5:
								$update_users = myquery("UPDATE game_users SET HP=HP - $effekt WHERE user_id=$user_id LIMIT 1");
								break;
							case 6:
								$update_users = myquery("UPDATE game_users SET MP=MP - $effekt WHERE user_id=$user_id LIMIT 1");
								break;
							case 7:
								$update_users = myquery("UPDATE game_users SET STM=STM - $effekt WHERE user_id=$user_id LIMIT 1");
								break;
							case 8:
								$update_users = myquery("UPDATE game_users SET HP=HP + $effekt WHERE user_id=$user_id LIMIT 1");
								break;
							case 9:
								$update_users = myquery("UPDATE game_users SET MP=MP + $effekt WHERE user_id=$user_id LIMIT 1");
								break;
							case 10:
								$update_users = myquery("UPDATE game_users SET STM=STM + $effekt WHERE user_id=$user_id LIMIT 1");
								break;
						}
						set_delay_reason_id($user_id,7);
						myquery("UPDATE game_maze SET type=0,effekt=0 WHERE map_name='" . $char['map_name'] . "' AND xpos=" . $char['map_xpos'] . " AND ypos=" . $char['map_ypos'] . ""); 
						setLocation("act.php?getsunduk=$effekt");
						{if (function_exists("save_debug")) save_debug(); exit;}
					}
				}
			}
			
            if (!isset($_GET['id']) or !is_numeric($_GET['id'])) break;
			if (!isset($_GET['count_item']) or $_GET['count_item']<=0 or $_GET['count_item']!=(int)$_GET['count_item'] or !is_numeric($_GET['count_item'])) break;
			$Item = new Item((int)$_GET['id']);
			$Item->take($_GET['id'], $_GET['count_item']);
		break;
	}
	if (function_exists("save_debug")) save_debug(); 
	if (isset($_GET['house']))
	{
		if (isset($_GET['option']))
		{
			setLocation("lib/hero.php?house&option=".$option."");
		}
		else
		{
			setLocation("lib/hero.php?house");
		}
	}
	else
	{
		if (getFunc($user_id)==2)
		{
			setLocation("craft.php?inv");
		}
		else
		{ 
			setLocation("act.php?func=inv");
		}
	}
}
?>