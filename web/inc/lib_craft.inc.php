<?php
function get_craft_index($craft_name)
{
	switch ($craft_name)
	{
		case 'sobiratel': return 1; break;
		case 'alchemist': return 2; break;
		case 'stroitel': return 3; break;
		case 'lumberjack': return 4; break;
		case 'stonemason': return 5; break;
		case 'mining': return 6; break;
		case 'sawmill': return 7; break;
		case 'hunter': return 8; break;
		case 'meating': return 9; break;
		case 'founder': return 10; break;
		case 'orujeinik': return 11; break;
        case 'kuznec': return 12; break;
		default: return 0; break;
	}
}

function get_craft_name($craft_index)
{
	switch ($craft_index)
	{
		case 1: return 'Собиратель'; break;
		case 2: return 'Алхимик'; break;
		case 3: return 'Строитель'; break;
		case 4: return 'Лесоруб'; break;
		case 5: return 'Каменотес'; break;
		case 6: return 'Рудокоп'; break;
		case 7: return 'Плотник'; break;
		case 8: return 'Охотник'; break;
		case 9: return 'Скорняк'; break;
		case 10: return 'Литейщик'; break;
		case 11: return 'Оружейник'; break;
        case 12: return 'Кузнец'; break;
		default: return ''; break;
	}
}

function getCraftLevel($user_id,$craft_index)
{
	$craft_times = getCraftTimes($user_id,$craft_index);
	return floor(CraftSpetsTimeToLevel($craft_index,$craft_times));
}

//Опыт за крафт
function add_exp_for_craft($user_id, $prof_id, $times=1)
{
	$exp=exp_for_craft($prof_id);	
	if ($exp>0) save_exp($user_id, ($exp*$times), 16);	
}

function exp_for_craft($prof_id)
{
	$exp=0;
	switch ($prof_id)
	{
		case 1: {$exp=20; break;}
		case 2: {$exp=200; break;} 
		case 4: {$exp=100; break;}
		case 5: {$exp=100; break;}
		case 6: {$exp=100; break;}
		case 7: {$exp=100; break;}
		case 8: {$exp=30; break;}
		case 9: {$exp=100; break;}
		case 10: {$exp=200; break;}
		case 11: {$exp=200; break;}
		case 12: {$exp=10; break;}		
	}
	return $exp;
}

function getCraftTimes($user_id,$craft_index)
{
	$sel = myquery("SELECT times FROM game_users_crafts WHERE user_id=$user_id AND craft_index=$craft_index");
	if ($sel!=false AND mysql_num_rows($sel)>0)
	{
		return mysqlresult($sel,0,0);
	}
	else
	{
		return 0;
	}
}

function setCraftTimes($user_id,$craft_index,$craft_times,$inc=0)
{
	$sel= myquery("SELECT profile FROM game_users_crafts WHERE user_id=$user_id AND craft_index=$craft_index");
	if (mysql_num_rows($sel))
	{
		list($profile) = mysql_fetch_array($sel);
		if (($profile==1)OR($craft_index==1)OR($craft_index==2))
		{
			if ($inc!=0)
			{
				myquery("UPDATE game_users_crafts SET times=times+$craft_times,last_time=UNIX_TIMESTAMP() WHERE user_id=$user_id AND craft_index=$craft_index");
			} 
			else
			{
				myquery("UPDATE game_users_crafts SET times=$craft_times,last_time=UNIX_TIMESTAMP() WHERE user_id=$user_id AND craft_index=$craft_index");
			}  
		}
		else
		{
			myquery("UPDATE game_users_crafts SET last_time=UNIX_TIMESTAMP() WHERE user_id=$user_id AND craft_index=$craft_index");
		}
	} 
	else
	{
		myquery("INSERT INTO game_users_crafts SET user_id=$user_id,craft_index=$craft_index,last_time=UNIX_TIMESTAMP()");
	}   
}

function checkCraftTrain($user_id,$craft_index)
{
	//если не профильная профессия, проверяем last_time, если он менее 30 минут возвращаем 0
	//иначе возвращаем 1
	$sel = myquery("SELECT last_time,profile FROM game_users_crafts WHERE user_id=$user_id AND craft_index=$craft_index");
	if (!mysql_num_rows($sel)) 
	{
		return true;
	}
	else
	{
		list($last_time,$profile) = mysql_fetch_array($sel);
		if ($profile==1)
		{
			return true;
		}
		else
		{
			if ((time()-$last_time)>(30*60))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	}
	return true;
}

function CraftSpetsTimeToLevel($craft_index,$time)
{
	switch ($craft_index)
	{
		case 1:
		{
			//собиратель
			$level = floor(sqrt($time/4)+0.5);
			if ($level>50)
			{
				return 50;
			}
			else
			{
				return $level;
			}
		}
		break;
		
		case 2:
		{
			//алхимик
			return max($time,floor(sqrt(7/4*$time))); 
		}
		break;
		
		case 4:
		{
			//лесоруб
			if ($time<10) return 0;
			$i=10;
			$level=0;
			while ($time>=$i)
			{	
				$level++;
				$i=$i+10*($level+1);
			}
			//$level = floor(sqrt($time/50)); 
			//if ($level>25) $level=25;
			return $level;
		}
		break;
		
		case 5:
		{
			//каменотес
			if ($time<10) return 0;
			$i=10;
			$level=0;
			while ($time>=$i)
			{	
				$level++;
				$i=$i+10*($level+1);
			}
			//$level = floor(sqrt($time/50)); 
			//if ($level>25) $level=25;
			return $level;
		}
		break;
		
		case 6:
		{
			//рудокоп
			if ($time<50) return 0;
			return floor(sqrt($time/50));  
		}
		break;
		
		case 7:
		{
			//плотник
			if ($time<50) return 0;
			return floor(sqrt($time/50));
		}
		break;
		
		case 8:
		{
			//охотник
			if ($time<100) return 0;
			$level = floor(sqrt($time/50)); 
			if ($level>25) $level=25;
			return $level;
		}
		break;

		case 9:
		{
			//скорняк
			if ($time<100) return 0;
			$level = floor(sqrt($time/50)); 
			//if ($level>25) $level=25;
			return $level;
		}
		break;
		
		case 10:
		{
			//литейщик
			if ($time<50) return 0;
			return floor(sqrt($time/25));  
		}
		break;
		
		case 11:	
		{
			//оружейник
			//if ($time<50) return 0;
			$level = floor(sqrt(7*$time)*2);
			if ($time<$level) $level = $time;
			return $level; 
		}
		break;
		
        case 12:    
        {
            //кузнец
            $level = floor(sqrt($time/10));
            if ($time<$level) $level = $time;
            return $level; 
        }
        break;
        
		default:
		{
			return 0;
		}
		break;
	}
}

function CreateArrayForCraftEliksir()
{
	global $char;
	$eliksir = array();
	$check=myquery("Select game_items_factsheet.name, game_items_factsheet.weight, game_items_factsheet.img, game_items_factsheet.hp_p, game_items_factsheet.mp_p, game_items_factsheet.stm_p, game_items_factsheet.dstr, game_items_factsheet.ddex, game_items_factsheet.dvit, game_items_factsheet.dspd, game_items_factsheet.dntl, game_items_factsheet.dpie, game_items_factsheet.dlucky, game_items_factsheet.cc_p, game_eliksir_alchemist.* 
				   From game_items_factsheet Join game_eliksir_alchemist On game_items_factsheet.id=game_eliksir_alchemist.elik_id 	
				  ");
    $i=0;
	while ($elik=mysql_fetch_array($check))
	{
		list($dlit)=mysql_fetch_array(myquery("Select dlit from game_eliksir_dlit where elik_id=".$elik['elik_id'].""));
		$check2=myquery("Select * From game_eliksir_res Where elik_id=".$elik['elik_id']."");
		if (mysql_num_rows($check2)>0)
		{
			$eliksir[$i]['item_id']=$elik['elik_id'];
			$eliksir[$i]['name']=$elik['name'];
			$eliksir[$i]['weight']=$elik['weight'];
			$eliksir[$i]['img']=$elik['img'];
			$eliksir[$i]['hp']=$elik['hp_p'];
			$eliksir[$i]['mp']=$elik['mp_p'];
			$eliksir[$i]['stm']=$elik['stm_p'];
			$eliksir[$i]['str']=$elik['dstr'];
			$eliksir[$i]['vit']=$elik['dvit'];
			$eliksir[$i]['spd']=$elik['dspd'];
			$eliksir[$i]['ntl']=$elik['dntl'];
			$eliksir[$i]['pie']=$elik['dpie'];
			$eliksir[$i]['dex']=$elik['ddex'];
			$eliksir[$i]['lucky']=$elik['dlucky'];
			$eliksir[$i]['cc']=$elik['cc_p'];
			$eliksir[$i]['alchemist']=$elik['alchemist'];
			$eliksir[$i]['clevel']=$elik['clevel'];
			$eliksir[$i]['time']=max($elik['mintime'], $elik['maxtime']-getCraftLevel($char['user_id'],2)*60);
			$eliksir[$i]['dlit']=$dlit;
			$j=0;
			while ($res=mysql_fetch_array($check2))
			{
				$eliksir[$i]['resource'][$j]['id']=$res['res_id'];
				$eliksir[$i]['resource'][$j]['kol']=$res['kol'];
				$j++;
			}
			$i++;
		}
	}  
	return $eliksir;
}
?>