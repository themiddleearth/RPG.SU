<?php

function create_combat($type, $map, $xpos, $ypos, $turnir_type=0, $user_id = 0, $player_id = 0, $npc = 0)
{
	$time = time();
	$ins = myquery("INSERT INTO combat (hod,combat_type,map_name,map_xpos,map_ypos,start_time,time_last_hod,turnir_type) values (1,".$type.",'".$map."','".$xpos."','".$ypos."',".$time.",".$time.",".$turnir_type.")");
	$uid = mysql_insert_id();	
	
	myquery("DELETE FROM game_combats_log_data WHERE boy=".$uid."");	
	myquery("DELETE FROM game_combats_log_text WHERE combat_id=".$uid."");
	if ($npc == 0)
	{
		myquery("DELETE FROM game_combats_log WHERE boy=".$uid."");			
		if ($player_id > 0)
		{	
			myquery("INSERT INTO game_combats_log (boy,hod,time,type,map_name,map_xpos,map_ypos, user1_id, user2_id) VALUES (".$uid.",1,".$time.",".$type.",'".$map."','".$xpos."','".$ypos."','".$user_id."','".$player_id."') ");	
		}
		else
		{
			myquery("INSERT INTO game_combats_log (boy,hod,time,type,map_name,map_xpos,map_ypos) VALUES (".$uid.",1,".$time.",".$type.",'".$map."','".$xpos."','".$ypos."') ");	
		}
	}
	return $uid;
}

//Закидываем игрока в бой. В функцию должен передаваться либо массив с данными игрока либо его айди.
function combat_insert ($char=0, $user_id=0, $combat_id, $type, $side, $hod=1, $join=0, $svit=0, $k_komu=0, $k_map_exp=1, $k_map_gp=1, $skill=0, $func=5, $delay, $no_rejoin=0, $full_stats=0)
{
	if ($char==0 and $user_id==0) return 0;
	if ($char==0) 
	{
		$check_user = myquery("SELECT * FROM game_users WHERE user_id=".$user_id."");
		if (mysql_num_rows($check_user) > 0)
		{
			$char = mysql_fetch_array($check_user);
		}	
		else
		{
			$char=mysql_fetch_array(myquery("SELECT * FROM game_users_archive WHERE user_id=".$user_id.""));
		}
	}
	$user_id = $char['user_id'];
	$injury = ceil($char['injury']/13); //Определим травму игрока
	
	list($pol) = mysql_fetch_array(myquery("SELECT sex FROM game_users_data WHERE user_id=".$user_id.""));
	if ($skill==0) $skill=take_skills($user_id);
	
	myquery("DELETE FROM combat_users WHERE user_id=".$user_id."");
	myquery("DELETE FROM combat_users_exp WHERE user_id=".$user_id."");
	
	//Восстановим жизни, ману, энергию, если надо
	if ($full_stats==1)
	{
		$char['HP']=$char['HP_MAX'];
		$char['MP']=$char['MP_MAX'];
		$char['STM']=$char['STM_MAX'];
	}
	
	$k_exp = skill_exp_effect ($skill['MS_EXP'], $type) * $k_map_exp;
	$k_gp = skill_gp_effect ($skill['MS_GP'], $type) * $k_map_gp;
	
	if ($svit>0)
	{
		$k_exp = 0;
		if ($svit>1)
		{
			$k_gp = 0;
		}
	}	
	
	myquery("INSERT INTO combat_users (
		combat_id,user_id,npc,time_last_active,name,clevel,reinc,side,
		HP,MP,STM,STR,DEX,SPD,VIT,NTL,PIE,HP_MAX,MP_MAX,STM_MAX,lucky,injury,
		k_komu,k_exp,k_gp,pol,avatar,sklon,race,clan_id,`join`,HP_start,hod_start,
		class_type,class_level,MS_WEAPON,MS_KULAK,MS_PARIR,MS_ART,MS_LUK,MS_THROW,MS_BERSERK,MS_PRUDENCE,MS_VAMPIRE,MS_SPIKES,NPC_DEFENCE) 
		VALUES (".$combat_id.",".$user_id.",0,".time().",'".$char['name']."',".$char['clevel'].",".$char['reinc'].",".$side.",
		".$char['HP'].",".$char['MP'].",".$char['STM'].",".$char['STR'].",".$char['DEX'].",".$char['SPD'].",".$char['VIT'].",".$char['NTL'].",".$char['PIE'].",			
		".$char['HP_MAX'].",".$char['MP_MAX'].",".$char['STM_MAX'].",'".$char['lucky']."','".$injury."',
		".$k_komu.",".$k_exp.",".$k_gp.",'".$pol."','".$char['avatar']."','".$char['sklon']."',
		'".mysql_result(myquery("SELECT name FROM game_har WHERE id=".$char['race'].""),0,0)."',".$char['clan_id'].",".$join.",".$char['HP_MAX'].",'".$hod."',
		".$skill['class_type'].",".$skill['class_level'].",".$skill['MS_WEAPON'].",".$skill['MS_KULAK'].",".$skill['MS_PARIR'].",".$skill['MS_ART'].",".$skill['MS_LUK'].",".$skill['MS_THROW'].",
		".$skill['MS_BERSERK'].",".$skill['MS_PRUDENCE'].",".$skill['MS_VAMPIRE'].",".$skill['MS_SPIKES'].",".$skill['NPC_DEFENCE'].")
		");
	
	combat_setFunc($user_id,$func,$combat_id);	
	set_delay_reason_id($user_id,$delay);

	//Отработаем действие навыка "Паладин"
	if ($skill['PALADIN']>0)
	{
		$r = mt_rand (1,100);
		if ($r<=$skill['PALADIN']+5)
		{
			if ($hod == 0) $hod = 1;
			$effect = $skill['PALADIN']*5;
			insert_fast_effect ($user_id, $user_id, $combat_id, ($hod+$join), 41, $effect);
		}
	}	
	
	if ($no_rejoin==1)
	{
		list($host) = mysql_fetch_array(myquery("SELECT host FROM game_users_active WHERE user_id=".$user_id.""));
		list($host_more) = mysql_fetch_array(myquery("SELECT host_more FROM game_users_active_host WHERE user_id=".$user_id.""));
		myquery("INSERT INTO combat_lose_user (combat_id,user_id,host,host_more) VALUES (".$combat_id.",".$user_id.",".$host.",'".$host_more."')");
	}
	
	//Проверим - надо ли вести лог боя
	$check1=0;
	$check2=0;
	if ($hod==1)
	{
		$check=myquery("SELECT * FROM combat_users WHERE npc=1 AND combat_id=".$combat_id."");
		if (mysql_num_rows($check)==0) { $check1=1; }
	}
	else
	{
		$check=myquery("SELECT * FROM game_combats_users WHERE boy=".$combat_id."");
		if (mysql_num_rows($check)>0) { $check2=1; }
	}
	if ($check1==1 or $check2==1)
	{
		myquery("INSERT INTO game_combats_users (boy, user_id, side) VALUES (".$combat_id.", ".$user_id.", ".$side.")");
	}
		
	$sel_arco = myquery("SELECT * FROM arcomage_users WHERE user_id=".$user_id."");
	if (mysql_num_rows($sel_arco)>0)
	{
		$arco = mysql_fetch_array($sel_arco);
		myquery("DELETE FROM arcomage WHERE id='".$arco['arcomage_id']."'");
		myquery("DELETE FROM arcomage_users_cards WHERE arcomage_id='".$arco['arcomage_id']."'");
		myquery("DELETE FROM arcomage_history WHERE arcomage_id='".$arco['arcomage_id']."'");
		myquery("DELETE FROM arcomage_users WHERE arcomage_id='".$arco['arcomage_id']."'");
	}
	myquery("DELETE FROM arcomage_call WHERE user_id=".$user_id."");
	
	ForceFunc($user_id,1);	
}

//Начинаем автобой
function create_autocombat($type=4, $min_kol)
{
	$map="5, 18, 4, 6, 8, 10, 12, 13, 16";
	$check=myquery("SELECT v1.user_id as us1_id, v1.clan_id as us1_clan_id, v1.vsadnik as us1_vsadnik, v1.sklon as us1_sklon, v1.race as us1_race, v1.name as us1_name,
	v1.map_name as us1_map_name, v1.map_xpos as us1_map_xpos, v1.map_ypos as us1_map_ypos, 
	v2.user_id as us2_id, v2.clan_id as us2_clan_id, v2.vsadnik as us2_vsadnik, v2.sklon as us2_sklon, v2.race as us2_race, v2.name as us2_name
	FROM (
	SELECT va1.user_id, va1.name, va1.clevel, va1.clan_id, va1.sklon, va1.race, va1.vsadnik, gum1.map_name, gum1.map_xpos, gum1.map_ypos 
	FROM view_active_users AS va1
	JOIN game_users_map gum1 ON va1.user_id = gum1.user_id
	JOIN game_users_func guf1 ON va1.user_id = guf1.user_id
	WHERE va1.clevel >=18 AND va1.clan_id >1 AND gum1.map_name IN (".$map.") AND guf1.func_id NOT IN ( '1',  '2',  '4',  '6',  '7' )
	) AS v1 JOIN (
	SELECT va1.user_id, va1.name, va1.clevel, va1.clan_id, va1.sklon, va1.race, va1.vsadnik, gum1.map_name, gum1.map_xpos, gum1.map_ypos 
	FROM view_active_users AS va1
	JOIN game_users_map gum1 ON va1.user_id = gum1.user_id
	JOIN game_users_func guf1 ON va1.user_id = guf1.user_id
	WHERE va1.clevel >=18 AND va1.clan_id >1 AND gum1.map_name IN (".$map.") AND guf1.func_id NOT IN ( '1',  '2',  '4',  '6',  '7' )
	) AS v2 ON ( v1.clan_id <> v2.clan_id AND abs(v1.clevel*1.0 - v2.clevel*1.0)<=3 AND (v1.map_name = v2.map_name OR (v1.map_name<>5 AND v2.map_name<>5)))	
	");
	$kol=mysql_num_rows($check);
	if ($kol>$min_kol)
	{
		$r2= mt_rand (0, $kol-1);
		mysql_data_seek($check, $r2);
		$boy=mysql_fetch_array($check);
		$time_hod = time();
		
		//Вытащим первого игрока с расовки/клановки
		if ($boy['us1_map_name']<>5 and $boy['us1_map_name']<>18)
		{			
			$boy['us1_map_name']=18;
			$boy['us1_map_xpos']=25;
			$boy['us1_map_ypos']=21;
			myquery("UPDATE game_users_map SET map_name='".$boy['us1_map_name']."', map_xpos='".$boy['us1_map_xpos']."', map_ypos='".$boy['us1_map_ypos']."' WHERE user_id='".$boy['us1_id']."' ");
		}
		//Перемещаем второго игрока к первому
		$boy['us2_map_name']=$boy['us1_map_name'];
		$boy['us2_map_xpos']=$boy['us1_map_xpos'];
		$boy['us2_map_ypos']=$boy['us1_map_ypos'];
		myquery("UPDATE game_users_map SET map_name='".$boy['us1_map_name']."', map_xpos='".$boy['us1_map_xpos']."', map_ypos='".$boy['us1_map_ypos']."' WHERE user_id='".$boy['us2_id']."' ");
		
		//Создаём бой
		$uid = create_combat($type, $boy['us1_map_name'], $boy['us1_map_xpos'], $boy['us1_map_ypos']);
		
		//Установка параметров боя
		//Определение состояния игроков в бою
		if ($boy['us2_vsadnik']>$boy['us1_vsadnik'])
		{
			$func1=2;
			$func2=1;
		}
		else
		{
			$func1=5;
			$func2=5;
		}		
		
		$side_char = $boy['us1_id'];
		$side_player = $boy['us2_id']; 
		switch($type)
		{
			case 1: { $nam=14; } break;
			case 2: { $nam=15; } break;
			case 3: { $nam=16; } break;
			case 4:
			{
				$nam=17;
				$side_char = $boy['us1_clan_id'];
				$side_player = $boy['us2_clan_id'];    
			}
			break;
			case 5:	{ $nam=18; } break;
			case 6:
			{
				$nam=49;
				$side_char = $boy['us1_sklon'];
				$side_player = $boy['us2_sklon'];    
			}
			break;
			case 7:
			{
				$nam=48;
				$side_char = $boy['us1_race'];
				$side_player = $boy['us2_race'];    
			}
			break;
			case 8: { $nam=47; } break;
			case 9: { $nam=46; } break;
			case 12: { $nam=50; } break;
		}

		$full_stats=1;
		$k_map = 1;
		//Кидаем в бой игроков
		combat_insert(0,$boy['us1_id'],$uid,$type,$side_char,1,0,0,0,$k_map,$k_map,0,$func1,$nam,1,$full_stats);	
		combat_insert(0,$boy['us2_id'],$uid,$type,$side_player,1,0,0,0,$k_map,$k_map,0,$func2,$nam,1,$full_stats);
	
		//Напишем в чат о начале боя+известим игроков на которых бой инициирован
		$map = mysql_fetch_array(myquery("SELECT * FROM game_maps WHERE id=".$boy['us1_map_name'].""));
		myquery("INSERT INTO game_log (message,date,FROMm,ob) VALUES ('".iconv("Windows-1251","UTF-8//IGNORE","<span style=\"font-weight:900;font-size:14px;color:red;font-family:Verdana,Tahoma,Arial,Helvetica,sans-serif\"> ВНИМАНИЕ! <img align=\"center\" src=\"http://".img_domain."/clan/".$boy['us1_clan_id'].".gif\"> ".mysql_result(myquery("SELECT nazv FROM game_clans WHERE clan_id=".$boy['us1_clan_id'].""),0,0)." и <img align=\"center\" src=\"http://".img_domain."/clan/".$boy['us2_clan_id'].".gif\"> ".mysql_result(myquery("SELECT nazv FROM game_clans WHERE clan_id=".$boy['us2_clan_id'].""),0,0)." начинают бой: ".$map['name']."(".$boy['us1_map_xpos']."; ".$boy['us1_map_ypos'].") </span>'").",".time().",-1,1)");		
		$pismo = iconv("Windows-1251","UTF-8//IGNORE","<span style=\"font-style:italic;font-size:12px;color:gold;\">Начат многоклановый бой с игроком <b>".$boy['us2_name']."</b></span>");
		myquery("INSERT INTO game_log (`message`,`date`,`FROMm`,`too`,`ptype`) VALUES ('".mysql_real_escape_string($pismo)."',".time().",-1,'".$boy['us1_id']."',1)");
		$pismo = iconv("Windows-1251","UTF-8//IGNORE","<span style=\"font-style:italic;font-size:12px;color:gold;\">Начат многоклановый бой с игроком <b>".$boy['us1_name']."</b></span>");
		myquery("INSERT INTO game_log (`message`,`date`,`FROMm`,`too`,`ptype`) VALUES ('".mysql_real_escape_string($pismo)."',".time().",-1,'".$boy['us2_id']."',1)");	
					
		$result="Бой начат!";
	}
	else
	{
		$result="Бой не может быть начат!";
	}
	return $result;
}

//Создаём хаотический бой
function create_chaoscombat($min_users = 6, $check_time = 1)
{
	$name = "Битва Хаоса";
	$check_time = 1; $min_users = 6;
	$map = 24; $xpos = 0; $ypos = 0;
	$type = 12; $func = 5; $nam = 50; $full_stats=1; $k_map = 1;		
	
	$select=myquery("SELECT time FROM game_obj WHERE town like '".$name."' and time is not null");			
	if (mysql_num_rows($select) > 0)
	{	
		list($time) = mysql_fetch_array($select);
		$d = explode(" ",$time);
		$dat = explode(".",$d[0]);
		$tim = explode(":",$d[1]);
		$timestamp_open = mktime($tim[0],$tim[1],0,$dat[1],$dat[0],$dat[2]);
		$diff = time() - $timestamp_open;
		if (($diff > 0 and $diff < 600) or $check_time == 0)
		{					
			$check = myquery("SELECT * FROM combat WHERE combat_type = 12");
			if (mysql_num_rows($check) == 0)
			{
				$select = myquery("SELECT user_id FROM game_users_map WHERE map_name = ".$map." ");
				$kol_users = mysql_num_rows($select);
				if ($kol_users >= $min_users)
				{
					// Создаём бой Хаоса				
					$ins = myquery("INSERT INTO combat (hod,combat_type,map_name,map_xpos,map_ypos,start_time,time_last_hod,turnir_type) VALUES (1,".$type.",'".$map."','".$xpos."','".$ypos."',".time().",".time().",0)");
					$uid = mysql_insert_id();		
					myquery("INSERT INTO game_combats_log (boy,hod,time,type,map_name,map_xpos,map_ypos) VALUES (".$uid.",1,".time().",".$type.",'".$map."','".$xpos."','".$ypos."') ");
					while (list($id) = mysql_fetch_array($select) )
					{
						combat_insert(0,$id,$uid,$type,$id,1,0,0,0,$k_map,$k_map,0,$func,$nam,1,$full_stats);	
					}				
					myquery("INSERT INTO game_log (message,date,FROMm,ob) VALUES ('".iconv("Windows-1251","UTF-8//IGNORE","<span style=\"font-weight:900;font-size:14px;color:red;font-family:Verdana,Tahoma,Arial,Helvetica,sans-serif\"> ВНИМАНИЕ! Начат Хаотический Бой!</span>'").",".time().",-1,1)");				
					echo 'Хаотический бой начат!<br>';
				}
				else
				{
					echo 'Для проведения Битвы Хаоса недостаточно игроков. Сейчас на Арене Хаоса: '.$kol_users.' игроков!<br>';	
				}
			}					
			else
			{
				echo 'Хаотический бой не может быть начат, т.к. 2 хаотических боя не могут проходить одновременно!<br>';
			}
		}
		else
		{
			echo 'Условия по времени для начала боя некорректны!<br>';	
		}
	}
	else
	{
		echo 'Переход для Битвы Хаоса не найден!<br>';	
	}
}


function insert_fast_effect ($user_id, $target=0, $combat_id, $hod, $action_type, $effect)
{
	switch ($action_type)
	{
		case 41: {$action_rand=3; break;}
		case 42: {$action_rand=5; break;}
	}
	myquery("INSERT INTO combat_actions (combat_id, hod, user_id, action_type, action_chem, action_kogo, action_kuda, action_proc, action_rand, action_type_sort) 
	VALUES (".$combat_id.", ".$hod.", ".$user_id.", ".$action_type.", ".$effect.", ".$target.", 1, 0, ".$action_rand.", 5)");
}

//Повышение получаемого опыта игроком в зависимости от уровня навыка "Мастер опыта"
function skill_exp_effect ($skill, $type)
{
	if ($skill == 0)
	{
		$k = 100;
	}
	else
	{
		$k_combat = 0;
		switch ($type)
		{
			case 1: {$k_combat = 3; break; }
			case 4: {$k_combat = 8; break; }
		}
		$k = 100 + $skill*$k_combat;
	}
	return $k;
}

//Повышение получаемых денег игроком в зависимости от уровня навыка "Мастер денег"
function skill_gp_effect ($skill, $type)
{
	if ($skill == 0)
	{
		$k = 100;
	}
	else
	{
		$k_combat = 0;
		switch ($type)
		{			
			case 1: {$k_combat = 2; break; }
		}
		$k = 100 + $skill*$k_combat;
	}
	return $k;
}

function combat_setFunc($user_id,$func_id,$combat_id,$hod=0)
{
	myquery("INSERT combat_users_state (user_id,state,combat_id,hod) VALUES ('$user_id','".$func_id."','".$combat_id."',$hod) ON DUPLICATE KEY UPDATE state='".$func_id."',combat_id='".$combat_id."',hod=$hod");
	return 1;
}

function combat_getFunc($user_id,&$combat_id=0)
{
	$sel_rid = myquery("SELECT state,combat_id FROM combat_users_state WHERE user_id = '".$user_id."' ");
	if(mysql_num_rows($sel_rid)==0)
	{
		return 0; 
	}
	else
	{
		$arr_rid = mysql_fetch_array($sel_rid);
		$combat_id = $arr_rid['combat_id'];
		return $arr_rid['state'];
	}
}

function combat_delFunc($user_id)
{
	myquery("DELETE FROM combat_users_state WHERE user_id='$user_id'");
	return 1;
}

function ClearCombat($combat_id)
{
	myquery("DELETE FROM combat_users WHERE combat_id=$combat_id");
	myquery("DELETE FROM combat_actions WHERE combat_id=$combat_id");
	myquery("DELETE FROM combat_users_exp WHERE combat_id=$combat_id");
	myquery("DELETE FROM combat WHERE combat_id=$combat_id");
	myquery("DELETE FROM combat_locked WHERE combat_id=$combat_id");
	myquery("DELETE FROM combat_users_state WHERE combat_id=$combat_id AND state NOT IN (3,4,7,8,9)");
}

function ClearCombatUser($user_id)
{
	myquery("DELETE FROM combat_users WHERE user_id=$user_id");
	myquery("DELETE FROM combat_actions WHERE user_id=$user_id");
	myquery("DELETE FROM combat_users_exp WHERE user_id=$user_id");
}
	
//Функция проверяет есть ли среди игроков-участников боя активные, если нет - то всем игрокам ставится вылет по тайму и бой удаляется
function check_boy($combat_id)
{
	$close_combat=60*60*24*2;
	global $user_id;
	if (!isset($user_id)) $user_id=0;
	$kol_out = mysql_result(myquery("SELECT COUNT(*) FROM combat_users WHERE combat_id=$combat_id AND npc=0 AND time_last_active<".(time()-$close_combat).""),0,0);
	$kol = mysql_result(myquery("SELECT COUNT(*) FROM combat_users WHERE combat_id=$combat_id AND npc=0"),0,0);
	if ($kol==0) return 0;
	if ($kol==$kol_out)
	{
		//ставим всем игрокам state=8 и очищаем бой
		$Combat = new Combat($combat_id,$user_id);		
		$Combat->clear_combat();
		return 1;
	}
	return 0;
}
?>