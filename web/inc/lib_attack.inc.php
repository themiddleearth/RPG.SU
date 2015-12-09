<?php
//���� ����:
//1 - ������� ���
//2 - �����
//3 - ����� ���
//4 - �������� ���
//5 - ��� ������ ����
//6 - ��� �����������
//7 - ��� ���
//12 - ����������� ���

require_once('combat/combat_log.php');
function attack_npc($char,$npc_id,$FROM_aggro_npc=0,$shadow=0)
{
	//������� ������������ ������
	$skill=take_skills($char['user_id']);
	
	if ($shadow==0 AND $FROM_aggro_npc==0)
	{
		$est_plash_monaha_char = mysql_result(myquery("SELECT COUNT(*) FROM game_items WHERE user_id=".$char['user_id']." AND priznak=0 AND used=5 AND item_id=".plash_monaha.""),0,0);
		if ($est_plash_monaha_char==1) return 0;
	}
	
	$user_id = $char['user_id'];
	//NPC ������ ������� "��������" �������
	if (close_combat==1 AND $user_id!=612 AND $FROM_aggro_npc==0)
		return 0;
	
	$current_time = time();
	$online_range = $current_time - 300;

	$boy_exist_flag=0;
	$uid=0;
	$res = myquery("SELECT combat_id FROM combat_users WHERE npc=1 AND user_id=$npc_id LIMIT 1");
	if(mysql_num_rows($res)>0)
	{
		$res_arr=mysql_fetch_array($res);
		include_once(getenv("DOCUMENT_ROOT").'/combat/class_combat.php');
		if (check_boy($res_arr['combat_id'])!=1)
		{
			// ��� ��� � ���-�� ������
			// ���������� uid
			$uid=$res_arr['combat_id'];
			$boy_exist_flag=1;
		}
	}
	//�������� ��� � �����
	list($npc_quest_id) = mysql_fetch_array(myquery("SELECT npc_quest_id FROM game_npc WHERE id=$npc_id"));
	if ($npc_quest_id==1)
	{
		$user_quest = mysql_result(myquery("SELECT COUNT(*) FROM game_quest_users WHERE user_id=$user_id AND quest_id=1"),0,0);
		if ($user_quest==0)
		{
			echo '<br>��� ��������� ���, �� �� ������ ��� ���������<br>';
			{if (function_exists("save_debug")) save_debug(); return 0;}
		}
	}

	//������ �������. ��������, �� �������� �� ���� ��� ��������� ������� �������
	$attack_user_id = $user_id;
	include(getenv("DOCUMENT_ROOT")."/quest/quest_engine_types/quests_engine_start_combat.php");

	$text='';
	if ($char['HP']==0)
	{
		if (function_exists("save_debug")) save_debug();
		return 0;
	}
	
	$join = 1;
    $current_hod = 0;
	$combat_type = 1;
	if ($shadow!=0) $combat_type=10;
	$npc = mysql_fetch_array(myquery("SELECT game_npc.*,game_npc_template.* FROM game_npc,game_npc_template WHERE game_npc.id=$npc_id AND game_npc.npc_id=game_npc_template.npc_id"));
	list($maze,$map,$k_exp,$k_gp) = mysql_fetch_array(myquery("SELECT maze,name,k_exp,k_gp FROM game_maps WHERE id=".$char['map_name'].""));
	if ($maze==1 AND $npc['prizrak']==1)
	{
		//update_maze_npc_for_user($user_id,$npc_id);
		$npc = mysql_fetch_array(myquery("SELECT game_npc.*,game_npc_template.* FROM game_npc,game_npc_template WHERE game_npc.id=$npc_id AND game_npc.npc_id=game_npc_template.npc_id"));
	}
	if($uid==0)
	{
		$join = 0;
		$uid = create_combat($combat_type, $char['map_name'], $char['map_xpos'], $char['map_ypos'], 0, 0, 0, 1);		
		
		$side=$npc_id;
		
		$npc_ms_kulak       = 0;
		$npc_ms_weapon      = 0;
		$npc_ms_art         = 0;
		$npc_ms_parir       = 0;
		$npc_ms_luk         = 0;
		$npc_ms_sword       = 0;
		$npc_ms_axe         = 0;
		$npc_ms_spear       = 0;
		$npc_ms_throw       = 0;
		
		$check_options = myquery("SELECT gnso.opt_id, gnsov.number, gnsov.value
									FROM game_npc_set_option gnso
									LEFT JOIN game_npc_set_option_value gnsov ON gnso.id = gnsov.id
				     			   WHERE gnso.npc_id=".$npc['npc_id']."
								   ORDER BY opt_id, number");									  
		while ($options = mysql_fetch_array($check_options))
		{
			$npc_options[$options['opt_id']][$options['number']]['value'] = $options['value'];			
		}
		
		// ��� �������� ������ ������
		if (isset($npc_options[8]))
		{
			$npc_ms_parir=$skill['MS_PARIR'];
		}
		
		// ��� �������� ����� ������
		if (isset($npc_options[9]))
		{
			$koef = $options[9][1]['value'];
			$npc['HP'] = round($char['HP_MAX']*$koef/100);
			$npc['npc_max_hp'] = $npc['HP'];
		}
		
		// ��� �������� ���� ������
		if (isset($npc_options[10]))
		{
			$koef = $options[10][1]['value'];
			$npc['MP'] = round($char['MP_MAX']*$koef/100);
			$npc['npc_max_mp'] = $npc['MP'];
		}
		
		// ����� ���� �������� �� ������ ������
		if (isset($npc_options[11]))
		{
			$type =  $options[11][1]['value'];
			$koef1 = $options[11][2]['value'];
			$koef2 = $options[11][3]['value'];
			$koef_har=$koef1/100;
			$koef_dev=$koef2/100;
			switch ($type)
			{
				case 1:
					if ($npc['npc_id']!=npc_id_olen)
					{
						$koef_lev=0.5;
					}
					else
					{
						$koef_lev=0;
					}
					
					$npc['npc_pie'] = (mt_rand($char['SPD']*(1-$koef_dev),$char['SPD']*(1+$koef_dev))+$koef_lev*$char['clevel']*3)*$koef_har;;
					$npc['npc_vit'] = (mt_rand($char['VIT']*(1-$koef_dev),$char['VIT']*(1+$koef_dev))+$koef_lev*$char['clevel']*1)*$koef_har;
					$npc['npc_spd'] = (mt_rand($char['PIE']*(1-$koef_dev),$char['PIE']*(1+$koef_dev))+$koef_lev*$char['clevel']*2)*$koef_har;
					if ($char['NTL']>$char['STR'])
					{
						$npc['npc_str'] = (mt_rand($char['NTL']*(1-$koef_dev),$char['NTL']*(1+$koef_dev))+$koef_lev*$char['clevel']*3)*$koef_har;
						$npc['npc_dex'] = mt_rand($char['NTL']*(1-$koef_dev),$char['NTL']*(1+$koef_dev))*$koef_har;
						$npc['npc_ntl'] = (mt_rand($char['NTL']*(1-$koef_dev)/10,$char['NTL']*(1+$koef_dev))/10+$koef_lev*$char['clevel']*3)*$koef_har;
					}
					else
					{
						$npc['npc_str'] = (mt_rand($char['STR']*(1-$koef_dev)/10,$char['STR']*(1+$koef_dev))/10+$koef_lev*$char['clevel']*3)*$koef_har;
						$npc['npc_dex'] = mt_rand($char['STR']*(1-$koef_dev),$char['STR']*(1+$koef_dev))*$koef_har;
						$npc['npc_ntl'] = (mt_rand($char['STR']*(1-$koef_dev),$char['STR']*(1+$koef_dev))+$koef_lev*$char['clevel']*3)*$koef_har;
					}
					$npc_hp0 = $npc['npc_dex']*10+10+1.5*$char['clevel'];
					$npc_mp0 = $npc['npc_ntl']*10+10+1.5*$char['clevel'];
					//TODO ������� ������ ���� � ����� "�������� ���������"
					if ($npc['npc_race']=='�������� ���������')
					{
						$npc['HP'] = $npc_hp0*10;
						$npc['MP'] = $npc_mp0*10;
						$npc['npc_max_hp'] = $npc_hp0*10;
						$npc['npc_max_mp'] = $npc_mp0*10;
						$npc['npc_str'] = $npc['npc_str']*3;
						$npc['npc_dex'] = $npc['npc_dex']*3;
						$npc['npc_spd'] = $npc['npc_spd']*3;
						$npc['npc_vit'] = $npc['npc_vit']*3;
						$npc['npc_ntl'] = $npc['npc_ntl']*3;
						$npc['npc_pie'] = $npc['npc_pie']*3;
					}
					if ($npc['HP']==0)
					{
						$npc['HP'] = 50;
						$npc['MP'] = 50;
						$npc['npc_max_hp'] = 50;
						$npc['npc_max_mp'] = 50;
					}
					$npc['npc_str_deviation'] = 0;
					$npc['npc_dex_deviation'] = 0;
					$npc['npc_spd_deviation'] = 0;
					$npc['npc_vit_deviation'] = 0;
					$npc['npc_ntl_deviation'] = 0;
					$npc['npc_pie_deviation'] = 0;
				break;
				
				case 2:
					$npc['npc_str']                 =$char['NTL']*$koef_har;
					$npc['npc_str_deviation']       =max(0, $char['NTL']*$koef_dev);
					$npc['npc_dex']                 =$char['DEX']*$koef_har;
					$npc['npc_dex_deviation']       =max(0, $char['DEX']*$koef_dev);
					$npc['npc_spd']                 =$char['SPD']*$koef_har;
					$npc['npc_spd_deviation']       =max(0, $char['SPD']*$koef_dev);
					$npc['npc_vit']                 =$char['VIT']*$koef_har;
					$npc['npc_vit_deviation']       =max(0, $char['VIT']*$koef_dev);
					$npc['npc_ntl']                 =$char['STR']*$koef_har;
					$npc['npc_ntl_deviation']       =max(0, $char['STR']*$koef_dev);
					$npc['npc_pie']                 =$char['PIE']*$koef_har;
					$npc['npc_pie_deviation']       =max(0, $char['PIE']*$koef_dev);
				break;
			}
		}		
		
		// ��� ��������������� �������� ��� ������ ������ ���
		if (isset($npc_options[13]))
		{
			$npc['HP'] = $npc['npc_max_hp'];
		    $npc['MP'] = $npc['npc_max_mp'];
		}
			
		if ($npc['HP']==0 OR $npc['HP']>$npc['npc_max_hp']) $npc['HP'] = $npc['npc_max_hp'];
		if ($npc['MP']==0 OR $npc['MP']>$npc['npc_max_mp']) $npc['MP'] = $npc['npc_max_mp'];
		
		myquery("DELETE FROM combat_users WHERE npc=1 AND user_id=$npc_id");
		
		$ins = myquery("INSERT INTO combat_users (
		combat_id,user_id,npc,time_last_active,name,clevel,reinc,side,
		HP,MP,STM,STR,DEX,SPD,VIT,NTL,PIE,HP_MAX,MP_MAX,STM_MAX,
		lucky,pol,avatar,race,HP_start,MS_PARIR
		) VALUES (
		$uid,$npc_id,1,".time().",'".$npc['npc_name']."',".$npc['npc_level'].",0,$side,
		".$npc['HP'].",".$npc['MP'].",10000,
		".mt_rand($npc['npc_str']-$npc['npc_str_deviation'],$npc['npc_str']+$npc['npc_str_deviation']).",
		".mt_rand($npc['npc_dex']-$npc['npc_dex_deviation'],$npc['npc_dex']+$npc['npc_dex_deviation']).",
		".mt_rand($npc['npc_spd']-$npc['npc_spd_deviation'],$npc['npc_spd']+$npc['npc_spd_deviation']).",
		".mt_rand($npc['npc_vit']-$npc['npc_vit_deviation'],$npc['npc_vit']+$npc['npc_vit_deviation']).",
		".mt_rand($npc['npc_ntl']-$npc['npc_ntl_deviation'],$npc['npc_ntl']+$npc['npc_ntl_deviation']).",
		".mt_rand($npc['npc_pie']-$npc['npc_pie_deviation'],$npc['npc_pie']+$npc['npc_pie_deviation']).",		
		".$npc['npc_max_hp'].",".$npc['npc_max_mp'].",10000,
		'".$char['lucky']."','','".$npc['npc_img']."','".$npc['npc_race']."',".$npc['npc_max_hp'].",".$npc_ms_parir.")"); 
		combat_setFunc($npc_id,6,$uid);
		
		$check_call=myquery("SELECT t1.npc_id, t2.value as kogo, t3.value as kol
							FROM game_npc_set_option AS t1
							JOIN game_npc_set_option_value AS t2 ON t1.id = t2.id AND t2.number =1
							JOIN game_npc_set_option_value AS t3 ON t1.id = t3.id AND t3.number =2
							WHERE t1.opt_id =6 and t1.npc_id=".$npc['npc_id']."");
		if (mysql_num_rows($check_call)>0)
		{
			myquery("INSERT INTO game_combats_log_text (combat_id, name, mode) VALUES (".$uid.", '".$npc['npc_name']."', ".$npc['id'].")");
			$text_id = mysql_insert_id();
			myquery("INSERT INTO game_combats_log_data (boy, user_id, hod, action, kto, text_id) VALUES (".$uid.", ".$npc['id'].", 0, 81, ".$npc['id'].", ".$text_id.")");
			while ($call=mysql_fetch_array($check_call))
			{
				npc_call($npc['id'],$call['kogo'],$call['kol'],$uid);				
			}
		}
	}
    else
    {
        $current_hod = mysqlresult(myquery("SELECT hod FROM combat WHERE combat_id=$uid"),0,0);
		$check_option2=myquery("SELECT id FROM game_npc_set_option WHERE opt_id=12 and npc_id=".$npc['npc_id']."");
		if (mysql_num_rows($check_option2)>0)
		{
			list($npc_opt)=mysql_fetch_array($check_option2);
			list($type_npc)=mysql_fetch_array(myquery("SELECT value FROM game_npc_set_option_value WHERE id=$npc_opt"));
			switch ($type_npc)
			{
				case 1:
					$no_boy=1;
				break;
				
				case 2:
					$check_boy=myquery("SELECT * FROM game_combats_log_data WHERE user_id=$user_id and boy=$uid");
					if (mysql_num_rows($check_boy)>0) $no_boy=1;
				break;
			}
		}
    }

	
	if (!isset($no_boy) or $no_boy<>1)
	{
		$side=1;		
			
		if ($join==0)
		{
			$func=5;			
		}
		else
		{
			$func=6;			
		}
				
		if ($shadow==0)
		{
			$delay=12;			
			save_stat($user_id,'','',8,'','',$npc_id,'','','','','');
		}
		else
		{
			$delay=45;			
		}

		//������� ������ � ���
		combat_insert($char,0,$uid,$combat_type,$side,$current_hod,$join,0,0,($k_exp/100),($k_gp/100),$skill,$func,$delay,0,0);
		
		//���������� �������� ������ "������"
		if ($skill['SLAYER']>0)
		{
			$r = mt_rand (1,100);
			if ($r<=$skill['SLAYER']*2+5)
			{
				$effect = $skill['SLAYER']*5;
				insert_fast_effect ($user_id, $npc_id, $uid, ($current_hod+1), 42, $effect);
			}
		}		
		setLocation("http://".domain_name."/combat.php");	
	}
	else
	{
		echo '<b><center>���������, ���� ���������� ������� ���!</center></b>';
	}
}

function npc_call ($npc_id, $kogo, $kol, $uid)
{
	while ($kol>0)
	{
		$templ = mysql_fetch_array(myquery("SELECT * FROM game_npc_template WHERE npc_id=$kogo"));
		myquery("INSERT INTO game_npc SET stay=2,npc_id=$kogo,map_name=672,xpos=0,ypos=0,view=0,dropable=1,HP=".$templ['npc_max_hp'].",MP=".$templ['npc_max_mp'].", EXP=".$templ['npc_exp_max']."");
		$new=mysql_insert_id();
		$npc = mysql_fetch_array(myquery("SELECT game_npc.*,game_npc_template.* FROM game_npc,game_npc_template WHERE game_npc.id=$new AND game_npc.npc_id=game_npc_template.npc_id"));	
		$npc_ms_kulak       = 0;
		$npc_ms_weapon      = 0;
		$npc_ms_art         = 0;
		$npc_ms_parir       = 0;
		$npc_ms_luk         = 0;
		$npc_ms_sword       = 0;
		$npc_ms_axe         = 0;
		$npc_ms_spear       = 0;
		$npc_ms_throw       = 0;
		$ins = myquery("INSERT INTO combat_users (
			combat_id,user_id,npc,time_last_active,name,clevel,reinc,side,
			HP,MP,STM,STR,DEX,SPD,VIT,NTL,PIE,HP_MAX,MP_MAX,STM_MAX,lucky,pol,avatar,race,HP_start,`join`,hod_start
			) VALUES (
			$uid,$new,1,".time().",'".$npc['npc_name']."',".$npc['npc_level'].",0,$npc_id,
			".$npc['HP'].",".$npc['MP'].",10000,
			".mt_rand($npc['npc_str']-$npc['npc_str_deviation'],$npc['npc_str']+$npc['npc_str_deviation']).",
			".mt_rand($npc['npc_dex']-$npc['npc_dex_deviation'],$npc['npc_dex']+$npc['npc_dex_deviation']).",
			".mt_rand($npc['npc_spd']-$npc['npc_spd_deviation'],$npc['npc_spd']+$npc['npc_spd_deviation']).",
			".mt_rand($npc['npc_vit']-$npc['npc_vit_deviation'],$npc['npc_vit']+$npc['npc_vit_deviation']).",
			".mt_rand($npc['npc_ntl']-$npc['npc_ntl_deviation'],$npc['npc_ntl']+$npc['npc_ntl_deviation']).",
			".mt_rand($npc['npc_pie']-$npc['npc_pie_deviation'],$npc['npc_pie']+$npc['npc_pie_deviation']).",			
			".$npc['npc_max_hp'].",".$npc['npc_max_mp'].",10000,
			5,'','".$npc['npc_img']."','".$npc['npc_race']."',".$npc['npc_max_hp'].", 1,1)"); 
			combat_setFunc($new,6,$uid);
			$kol--;
	}
}

function check_attack_type($char,$player,$type,$check_level=1)
{
	$est_kamikadze = mysql_result(myquery("SELECT COUNT(*) FROM game_items WHERE user_id=".$char['user_id']." AND priznak=0 AND used=6 AND item_id=".povyazka_kamikadze.""),0,0);
	if ($check_level==1)
	{
		if ($type==3 OR $type==5)
		{
			//��� ���� "��� ������ ����", "����� ���" ����� �������� �� ������ +3 -2 ������
			$max_level = 3;
			$min_level = 2;
		}
		else
		{
			$max_level = 3;
			$add = floor(($char['clevel']-5)/5);
			$max_level+=$add;
			$min_level = 2;
		}
		if ($est_kamikadze && $char['clevel'] <= $player['clevel'])
		{
		}
		else
		{
			if ($player['clevel']-$max_level <= $char['clevel'] and
				$char['clevel'] <= ($player['clevel']+$min_level) and
				$player['clevel']!=0)
			{
			}
			else
			{
				return '�� �� ��������� �� ������ ����������!';
			} 
		}
	}
	switch ($type)
	{
		case 1:
		{
			if ($char['clan_id']==0 OR $player['clan_id']==0)
			{
				return "���� ��� �������� ������ ��� �������� �������";
			}
		}
		break;
		case 2:
		{
		}
		break;
		case 3:
		{
		}
		break;
		case 4:
		{
			if ($char['clan_id']==0 OR $player['clan_id']==0)
			{
				return "���� ��� �������� ������ ��� �������� �������";
			}
		}
		break;
		case 5:
		{
		}
		break;
		case 6:
		{
			if ($char['sklon']==0 OR $player['sklon']==0)
			{
				return "���� ��� �������� ������ ��� ������� �� �����������";
			}
			if ($char['sklon']==$player['sklon'])
			{
				return "���� ���� ����� �������� ������ �� ������� ������ ����������";
			}
		}
		break;
		case 7:
		{
			if ($char['race']==$player['race'])
			{
				return "���� ���� ����� �������� ������ �� ������� ������ ����";
			}
		}
		break;
	}
	return 1;
}

function check_attack($char,$player,$type=0,$map=0)
{
	global $user_id;
	$check='��� ����������';
	
	if (close_combat==1 AND $user_id!=612) return '��� ��� �������';
	/*if ($map==0) */$map=mysql_fetch_array(myquery("SELECT * FROM game_maps WHERE id='".$char['map_name']."'"));
	if (clans_war==0 OR $player['user_id']==612 OR $map['dolina']==0)
	{
		$est_plash_monaha_char = mysql_result(myquery("SELECT COUNT(*) FROM game_items WHERE user_id=".$char['user_id']." AND priznak=0 AND used=5 AND item_id=".plash_monaha.""),0,0);
		if ($est_plash_monaha_char==1) return '�� ������������� �� ���';
		$est_plash_monaha_player = mysql_result(myquery("SELECT COUNT(*) FROM game_items WHERE user_id=".$player['user_id']." AND priznak=0 AND used=5 AND item_id=".plash_monaha.""),0,0);
		if ($est_plash_monaha_player==1) return '��������� ������������ �� ���';
	}
	if (!isset($map['maps_name'])) $map['maps_name'] = $map['name'];
	
	if (
		$map['boy_type1']==0 AND 
		$map['boy_type2']==0 AND 
		$map['boy_type3']==0 AND 
		$map['boy_type4']==0 AND 
		$map['boy_type5']==0 AND 
		$map['boy_type6']==0 AND 
		$map['boy_type7']==0)
	{
		return '��������� ��� ��� �� �����';
	}

	if ($type>=1 AND $type<=7)
	{
		if ($type==1 AND $map['boy_type1']!=1) return "������ ��� ��� �� ���� ����� ��������";
		elseif ($type==2 AND $map['boy_type2']!=1) return "������ ��� ��� �� ���� ����� ��������";
		elseif ($type==3 AND $map['boy_type3']!=1) return "������ ��� ��� �� ���� ����� ��������";
		elseif ($type==4 AND $map['boy_type4']!=1) return "������ ��� ��� �� ���� ����� ��������";
		elseif ($type==5 AND $map['boy_type5']!=1) return "������ ��� ��� �� ���� ����� ��������";
		elseif ($type==6 AND $map['boy_type6']!=1) return "������ ��� ��� �� ���� ����� ��������";
		elseif ($type==7 AND $map['boy_type7']!=1) return "������ ��� ��� �� ���� ����� ��������";
		//�������� � ����������� �� ���� ���
		$str_check_type = check_attack_type($char,$player,$type,0);  
		if ($str_check_type!=1)
		{
			return $str_check_type;
		}
		elseif ($map['dolina']==0 AND $map['maps_name']!='������� ��������')
		{
			$str_check_type = check_attack_type($char,$player,$type); 
			if ($str_check_type!=1)
			{
				return $str_check_type;
			}
		}
	}
	
	list($host1,$time1) = mysql_fetch_array(myquery("SELECT host,last_active FROM game_users_active WHERE user_id='".$char['user_id']."'"));
	list($host2,$time2) = mysql_fetch_array(myquery("SELECT host,last_active FROM game_users_active WHERE user_id='".$player['user_id']."'"));
	
	$player_func = getFunc($player['user_id']);

	if (domain_name=='localhost') {$host1=1; $host2=2;};
	
	if ($player['user_id']==$char['user_id'])
	{
		//������ ������� �� ����
		return '������ ������� �� ������ ����';
	}
	elseif ((time()-$time2)>300)
	{
		//��������� �� � ����
		return '���������� ��� � ����';
	}
	elseif ($host1==$host2)
	{
		//������ ������� �� ������
		return '������ ������� �� ������';
	}
	elseif ($player['clan_id']==$char['clan_id'] and $char['clan_id']!=0 and $player['clan_id']!=0 and $map['dolina']!=1)
	{
		//������ ������� �� ������ ����������, �� ����� � ������ ������
		return '������ �������� �� ���������� (����� ������ ������)';
	}
	elseif ($player_func==1)
	{
		return '����� ����� � ���';
	}
	elseif ($player_func==2)
	{
		return '����� ����� � ������';
	}
	elseif ($player_func==3)
	{
		return '����� � ��������';
	}
	elseif ($player_func==4)
	{
		return '����� ����� � ���� ������';
	}
	elseif ($player_func==10)
	{
		return '����� �������� �����';
	}
	elseif ($player_func==9)
	{
		return '����� � ������';
	}
	elseif ($player_func==6)
	{
		return '����� ��������� � ���� ����������';
	}
	elseif ($player_func==7)
	{
		return '����� ��������� � ���������';
	}
	elseif (get_delay_reason_id($user_id)==32)
	{
		return '����� ��������� � ������ �������������';
	}
	elseif (($char['clan_id']==0 or $player['clan_id']==0) AND (($player['HP'])<(($player['HP_MAX'])*0.75)))
	{
		//������ ����������
		return '���������� �� �������!';
	}
	elseif (($player['HP'])<(($player['HP_MAX'])*0.2))
	{
		//������ ���������� 2
		return '���������� �� �������!';
	}
	elseif ($player['HP']==0 or $char['HP']==0)
	{
		//������ �������� ������� ��� �� ��������
		return '���-�� �� ��� ����� �����!';
	}
	if (clans_war==0 OR ($map['dolina']==0 AND $map['arena']==0))
	{
		if ($map['dolina']==1)
		{
			return '� ������ ������ ��� ��� ���������!';
		}		
	}

	if ($map['dolina']==1)
	{
		//��� ����������� �� ����� �����
		$check=1;
	}
	elseif ($map['maps_name']=='������� ��������')
	{
		//� ������� �������� ����� �������� ������ �� ���������� � ������� ���� ���������� �� ���� ���
		if ($player['clevel']-3 <= $char['clevel'] and
			$char['clevel'] <= $player['clevel'] and
			$player['clevel']!='0')
		{
			$check=1;
		}
		else
		{
			return '�� �� ��������� �� ������ ����������!';
		}
	}
	else
	{
		$est_kamikadze = mysql_result(myquery("SELECT COUNT(*) FROM game_items WHERE user_id=".$char['user_id']." AND priznak=0 AND used=6 AND item_id=".povyazka_kamikadze.""),0,0);
		if ($est_kamikadze && $char['clevel'] <= $player['clevel'])
		{
			 $check=1;
		}
		else
		{
			$max_level = 3;
			$add = floor(($char['clevel']-5)/5);
			$max_level+=$add;
			$min_level = 2;
			if ($player['clevel']-$max_level <= $char['clevel'] and
				$char['clevel'] <= ($player['clevel']+$min_level) and
				$player['clevel']!=0)
			{
				 $check=1;
			}
			else
			{
				return '�� �� ��������� �� ������ ����������!';
			}
		}
	}
	return ($check);
}

function attack_user($char,$player,$type,$turnir_type=0)
{
	$id = $player['user_id'];
	$user_id = $char['user_id'];
	
	$l_type = $type;
	$map = mysql_fetch_array(myquery("SELECT * FROM game_maps WHERE id=".$char['map_name'].""));
	
	if ($l_type!=8 AND $l_type!=9)
	{
		if (check_attack($char,$player,$l_type,$map)!=1)
		{
			{if (function_exists("save_debug")) save_debug(); return 0;}
		}
	}

	if (clans_war==1 AND $map['dolina']==1 AND clans_war_type>0) $type=clans_war_type;
	if (chaos_war==1) $type=5;
	
	// �������� �� ��, ��� ����� �� �������� �� ������� ����� 2-�� ��� �� ��������� ���
	$kol_attacks = mysql_num_rows(myquery("SELECT count(*) as kol FROM game_combats_log WHERE user1_id = ".$user_id." AND user2_id = ".$id." AND time>=".(time()-60*60)." HAVING kol>=2"));
	
	// ������ ���
	$uid = create_combat($type, $char['map_name'], $char['map_xpos'], $char['map_ypos'], $turnir_type, $user_id, $id);

	$side_char = $char['user_id'];
	$side_player = $player['user_id'];
	switch($type)
	{
		case 1: { $nam=14; } break;
		case 2: { $nam=15; } break;
		case 3: { $nam=16; } break;
		case 4:
		{
			$nam=17;
			$side_char = $char['clan_id'];
			$side_player = $player['clan_id'];    
		}
		break;
		case 5:	{ $nam=18; } break;
		case 6:
		{
			$nam=49;
			$side_char = $char['sklon'];
			$side_player = $player['sklon'];    
		}
		break;
		case 7:
		{
			$nam=48;
			$side_char = $char['race'];
			$side_player = $player['race'];    
		}
		break;
		case 8: { $nam=47; } break;
		case 9: { $nam=46; } break;
	}
	
	$skill=take_skills($char['user_id']);
	$skill1=take_skills($player['user_id']);
	
	//�������� ��� �� �� ������������ ������� "������������"
	$check_effect = myquery("SELECT * FROM game_obelisk_users WHERE user_id='".$player['user_id']."' AND type=6 AND time_end>".time()." ");
	if ($turnir_type==0 AND $map['dolina']!=1 and ($char['vsadnik']<$player['vsadnik'] OR mysql_num_rows($check_effect)>0 OR $kol_attacks > 0) )
	{
		$func1=2;
		$func2=1;		
	}
	else
	{
		$func1=5;
		$func2=5;
		if ($type==4)
		{
			myquery("INSERT INTO game_log (message,date,FROMm,ob) VALUES ('".iconv("Windows-1251","UTF-8//IGNORE","<span style=\"font-weight:900;font-size:14px;color:red;font-family:Verdana,Tahoma,Arial,Helvetica,sans-serif\"> ��������! <img align=\"center\" src=\"http://".img_domain."/clan/".$char['clan_id'].".gif\"> ".mysql_result(myquery("SELECT nazv FROM game_clans WHERE clan_id=".$char['clan_id'].""),0,0)." � <img align=\"center\" src=\"http://".img_domain."/clan/".$player['clan_id'].".gif\"> ".mysql_result(myquery("SELECT nazv FROM game_clans WHERE clan_id=".$player['clan_id'].""),0,0)." �������� ���: ".$map['name']."(".$char['map_xpos']."; ".$char['map_ypos'].") </span>'").",".time().",-1,1)");
		}
		//���� �� ������ ������ ������ ��� ����, ��, ��� ������� ���������������� ������, ������� ��� ������ ������������
		if ($type == 4 and $skill1['HIDE']>0 and mysql_num_rows($check_effect)==0)
		{
			$time_hide = time() + $skill1['HIDE']*3*60;
			myquery("INSERT INTO game_obelisk_users (user_id,time_end,type) VALUES (".$player['user_id'].",".$time_hide.",6)");
		}
	}
	//������ � ���������� ������ ������ ������������
	myquery("DELETE FROM game_obelisk_users WHERE user_id=".$char['user_id']." AND type=6");
	
	$hod = 1;	
	
	//������ � ��� �������
	combat_insert($char,0,$uid,$type,$side_char,$hod,0,0,0,($map['k_exp']/100),($map['k_gp']/100),$skill,$func1,$nam,1);	
	combat_insert($player,0,$uid,$type,$side_player,$hod,0,0,0,($map['k_exp']/100),($map['k_gp']/100),$skill1,$func2,$nam,1);
	
	//���������� �������� ������ "������" ��� 1-��� ������	
	if ($skill['SLAYER']>0)
	{
		$r = mt_rand (1,100);
		if ($r<=$skill['SLAYER']*2+5)
		{
			$effect = $skill['SLAYER']*5;
			insert_fast_effect ($char['user_id'], $player['user_id'], $uid, $hod, 42, $effect);
		}
	}
	
	if ($l_type!=8 AND $l_type!=9) 
	{
		$pismo = iconv("Windows-1251","UTF-8//IGNORE","<span style=\"font-style:italic;font-size:12px;color:gold;\">�� ���� ����� ����� <b>".$char['name']."</b></span>");
		myquery("INSERT INTO game_log (`message`,`date`,`FROMm`,`too`,`ptype`) VALUES ('".mysql_real_escape_string($pismo)."',".time().",-1,$id,1)");
	}
	else
	{
		$pismo = iconv("Windows-1251","UTF-8//IGNORE","<span style=\"font-style:italic;font-size:12px;color:gold;\">���������� ��������� ����� � �������: <b>".$char['name']."</b></span>");
		myquery("INSERT INTO game_log (`message`,`date`,`FROMm`,`too`,`ptype`) VALUES ('".mysql_real_escape_string($pismo)."',".time().",-1,".$player['user_id'].",1)");
		$pismo = iconv("Windows-1251","UTF-8//IGNORE","<span style=\"font-style:italic;font-size:12px;color:gold;\">���������� ��������� ����� � �������: <b>".$player['name']."</b></span>");
		myquery("INSERT INTO game_log (`message`,`date`,`FROMm`,`too`,`ptype`) VALUES ('".mysql_real_escape_string($pismo)."',".time().",-1,".$char['user_id'].",1)");
	}

	if ($l_type!=8 AND $l_type!=9) save_stat($user_id,'','',16,'','','',$player['clan_id'],'','','');
			
	setLocation("http://".domain_name."/combat.php");	
	return '';
}

function check_level5()
{
	global $char,$sred_level;
	if ($char['clevel']>($sred_level+3)) return false;
	if ($char['clevel']<($sred_level-3)) return false;
	return true;
}

function check_level3()
{
	global $char,$sred_level;
	if ($char['clevel']>($sred_level+3)) return false;
	if ($char['clevel']<($sred_level-3)) return false;
	return true;
}

function check_level6()
{
	global $char,$sred_level;
	if ($char['clevel']>($sred_level+3)) return false;
	if ($char['clevel']<($sred_level-3)) return false;
	return true;
}

function check_level7()
{
	global $char,$sred_level;
	if ($char['clevel']>($sred_level+3)) return false;
	if ($char['clevel']<($sred_level-3)) return false;
	return true;
}

function check_join($char,$player,&$join,&$alt,&$svit,$auto=0)
{
	//return 0;
	global $user_id;
	if (checkFunc($user_id,1,1)==0)
	{
		//������ �������������� ���� ��� � ���
		return 0;
	}
	
	$est_plash_monaha_char = mysqlresult(myquery("SELECT COUNT(*) FROM game_items WHERE user_id=".$char['user_id']." AND priznak=0 AND used=5 AND item_id=".plash_monaha.""),0,0);
	if ($est_plash_monaha_char==1) return '�� ������������� �� ���';

	$map = mysql_fetch_array(myquery("SELECT * FROM game_maps WHERE id='".$char['map_name']."'"));
	$svitok = 0;
	list($host) = mysql_fetch_array(myquery("SELECT host FROM game_users_active WHERE user_id=$user_id"));
	list($host_more) = mysql_fetch_array(myquery("SELECT host_more FROM game_users_active_host WHERE user_id=$user_id"));
	list($cur_hod,$type,$npc) = mysql_fetch_array(myquery("SELECT hod,combat_type,npc FROM combat WHERE combat_id=".$player['boy'].""));	
	  
	if ($map['dolina']==0)
	{
		if ($map['boy_type1']==0 AND $map['boy_type2']==0 AND $map['boy_type3']==0 AND $map['boy_type4']==0 AND $map['boy_type5']==0 AND $map['boy_type6']==0 AND $map['boy_type7']==0)
		{
			return '�� ����� ��� ��� ���������';
		}
	}
	else
	{
		if (clans_war==0 AND $auto==0)
		{
			return '� ������ ������ ��� ��� ���������';
		}
		if ($cur_hod>3)
		{
			return '����� 3 ���� � ������ ���� ��������';
		}
	}
	if ($type==8 OR $type==9 OR $type==10)
	{
		//� ��������� ��� �������� ������
		return '� �������� ��� �������� ������';
	}
	elseif ($npc == 1)
	{
		//������ �������� ������� ��� �� ��������
		return '������ �������������� � ���� � ������';
	}
	$comb_func=combat_getFunc($player['user_id']);
	if ($player['boy']==0)
	{
		//�������������� ����� ������ � ������� � ���
		return '����� �� ��������� � ���';
	}
	elseif ($player['HP']==0 or $char['HP']==0)
	{
		//������ �������� ������� ��� �� ��������
		return '�� ��� ����� �����';
	}
	elseif ($comb_func!=5 AND $comb_func!=6)
	{
		//�������������� ����� ������ � ��� �������� �����
		return '������ ��� � ���';
	}
	elseif (mysql_result(myquery("SELECT COUNT(*) FROM combat_lose_user WHERE combat_id=".$player['boy']." AND (user_id=$user_id OR (host='".$host."' AND host_more='".$host_more."'))"),0,0)>0 AND $auto==0)
	{
		//������ �������� �������� � ���
		return '�� ��� '.echo_sex('����������','�����������').' � ���� ���';
	}
	elseif ($cur_hod>6)
	{
		//����� 6 ���� � ��� �������� ������
		return '���� � ��� ������';
	}

	if ($map['id']!=map_coliseum)
	{
		//���� ���� ������ ������������� � ��� - ���������� ��
		$ch_small = mysql_result(myquery("SELECT COUNT(*) FROM game_items WHERE user_id=$user_id AND priznak=0 AND used IN (12,13,14) AND item_id='".svitok_small_item_id."'"),0,0);
		$ch_sred = mysql_result(myquery("SELECT COUNT(*) FROM game_items WHERE user_id=$user_id AND priznak=0 AND used IN (12,13,14) AND item_id='".svitok_sred_item_id."'"),0,0);
	}
	else
	{
		$ch_small = 0;
		$ch_sred = 0;
	}
	
	//echo '$ch_small = '.$ch_small.',$ch_sred='.$ch_sred;

	//�� � ������ ������ �������� ����������� ������������� �� �������
	if ($map['dolina']==0)
	{
		if ($cur_hod>3)
		{
			if ($map['id']==map_coliseum)
			{
				return '� ��� ����� ����� ������ �� ����� 3 ���� ���!';
			}
			elseif ($ch_small==0 AND $ch_sred==0)
			{
				//�������������� � ��� ����� 3 ���� ��� ������� ������
				return '� ���� ��� ������� ������!';
			}
		}
	}
	
	//����� �������� ��� ��� � ����� ���������� � ���� � ������ ������� �� �����
	if ($type==1 AND $char['clan_id']!=0)
	{
		//� ������� ��� ����� �������������� ������ � ����������
		$error='';
		$SELECT=myquery("SELECT view_active_users.clan_id FROM view_active_users,combat_users WHERE view_active_users.clan_id<>'".$player['clan_id']."' AND view_active_users.user_id=combat_users.user_id and combat_users.combat_id='".$player['boy']."' ORDER BY view_active_users.clan_id ASC limit 1");
		list($clan)=mysql_fetch_array($SELECT);
		if ($clan==0)
		{
			return '����� �������������� ������ � ����� �������� �������';
		}

		//���� ���� �������� ������ ���� - ���������� 1, join=1, alt = ����� ALT ���� �������� ������
		//���� ���� �������� ������ ������ ������ - ���������� 1, join=99, alt = �������� �� �����
		//������� ��������� ����������� �������� ����� � ��� ��� �������. ���� ��������� - ������������, ����� �������� �������� �� �������
		if ($cur_hod<=3)
		{
			if ($player['clan_id']==$char['clan_id'])
			{
				$join=1;
				$alt='�������������� � ������� �����';
				return 1;
			}
		}

		$svit = '';
		//�� �������� ������ �������� � ����� ��� �� 7 ����
		if ($ch_sred>0 AND $cur_hod<=6) $svit.=',2,';  
		//�� ������ ������ �������� �� ������� �������� � ����� ����� ����� 3 ����
		if ($ch_small>0 AND $cur_hod>=3 AND $cur_hod<=6 AND $player['clan_id']==$char['clan_id'] ) $svit.=',1,'; 
		if ($svit!='' OR $cur_hod<3)
		{
			$join=99;
			$alt='�������� � ������� �����';
			return 1;
		}
		else
		{
			$join = 0;
			return '�� �� ������ �������������� � ������� �����';
		}
	}
	elseif ($type==2)
	{
		return '� ������ ������ ��������������!';
	}
	elseif ($type==3)
	{
		$error='';
		$sred_level = 0;
		$kol = 0;
		$sel = myquery("SELECT SUM(clevel) AS sumlevel,COUNT(*) AS kol FROM combat_users WHERE combat_id='".$player['boy']."' AND `join`=0 GROUP BY combat_id");
		list($sred_level,$kol) = mysql_fetch_array($sel);
		if ($kol>0)
		{
			$sred_level=round($sred_level/$kol);
			//���� ���� �������� ������ ���� - ���������� 1, join=1, alt = ����� ALT ���� �������� ������
			//���� ���� �������� ������ ������ ������ - ���������� 1, join=99, alt = �������� �� �����
			//������� ��������� ����������� �������� ����� � ��� ��� �������. ���� ��������� - ������������, ����� �������� �������� �� �������
			if ($cur_hod<=3)
			{
				if (check_level3() OR (clans_war!=0 AND $map['dolina']==1))
				{
					$join=1;
					$alt='�������������� � ����� �����';
					return 1;
				}
			}

			$svit = '';
			//�� �������� ������ �������� � ����� ��� �� 7 ����
			if ($ch_sred>0 AND $cur_hod<=6 ) $svit.=',2,'; 
			//�� ������ ������ �������� �� ������� �������� � ����� ����� ����� 3 ���� 
			if ($ch_small>0 AND $cur_hod>=3 AND $cur_hod<=6 AND check_level3() ) $svit.=',1,'; 
			if ($svit!='' OR $cur_hod<3)
			{
				$join=99;
				$alt='�������� � ����� �����';
				return 1;
			}
			else
			{
				$join = 0;
				return '�� �� ������ �������������� � ����� ����� (��.������� = '.$sred_level.')';
			}
		}
		else
		{
			return '� ��� ������ ���';
		}
	}
	elseif ($type==4)
	{
		//� �������� ��� ����� �������������� ������ � ���������� ��� ��������� ��� ����� �������� � ���
		$error='';
		//���� ���� �������� ������ ���� - ���������� 1, join=1, alt = ����� ALT ���� �������� ������
		//���� ���� �������� ������ ������ ������ - ���������� 1, join=99, alt = �������� �� �����
		//������� ��������� ����������� �������� ����� � ��� ��� �������. ���� ��������� - ������������, ����� �������� �������� �� �������
		if ($cur_hod<=3)
		{
			if (($char['clan_id']==$player['clan_id']))
			{
				$join=1;
				$alt='�������������� � �������� �����';
				return 1;
			}
			elseif ($char['clan_id']!=0)
			{
				$join=1;
				$alt='�������� � �������� �����';
				return 1;
			}
		}

		$svit = '';
		//�� �������� ������ �������� � ����� ��� �� 7 ����
		if ($ch_sred>0 AND $cur_hod<=6 ) $svit.=',2,';  
		//�� ������ ������ �������� �� ������� �������� � ����� ����� ����� 3 ����
		if ($ch_small>0 AND $cur_hod>=3 AND $cur_hod<=6 AND ($char['clan_id']==$player['clan_id'] OR $char['clan_id']!=0) ) $svit.=',1,'; 
		if ($svit!='' OR $cur_hod<3)
		{
			$join=99;
			$alt='�������� � �������� �����';
			return 1;
		}
		else
		{
			$join = 0;
			return '�� �� ������ �������������� � �������� �����';
		}
	}
	elseif ($type==5)
	{
		$error='';
		$sred_level = 0;
		$kol = 0;
		$sel = myquery("SELECT SUM(clevel) AS sumlevel,COUNT(*) AS kol FROM combat_users WHERE combat_id='".$player['boy']."' AND `join`=0 GROUP BY combat_id");
		list($sred_level,$kol) = mysql_fetch_array($sel);
		if ($kol==0)
		{
			return '� ��� ��� ������ ���!';
		}
		if ($auto==1)
		{
			//��� ����������������� (����� �����) �������� ���������� �� �������
			return 1;
		}
		$sred_level=round($sred_level/$kol);
		//���� ���� �������� ������ ���� - ���������� 1, join=1, alt = ����� ALT ���� �������� ������
		//���� ���� �������� ������ ������ ������ - ���������� 1, join=99, alt = �������� �� �����
		//������� ��������� ����������� �������� ����� � ��� ��� �������. ���� ��������� - ������������, ����� �������� �������� �� �������
		if ($cur_hod<=3)
		{
			if (check_level5() OR (clans_war!=0 AND $map['dolina']==1))
			{
				$join=1;
				$alt='�������������� � ����� ��� ������ ����';
				return 1;
			}
		}

		$svit = '';
		//�� �������� ������ �������� � ����� ��� �� 7 ����
		if ($ch_sred>0 AND $cur_hod<=6 ) $svit.=',2,';  
		//�� ������ ������ �������� �� ������� �������� � ����� ����� ����� 3 ����
		if ($ch_small>0 AND $cur_hod>=3 AND $cur_hod<=6 AND check_level5() ) $svit.=',1,'; 
		if ($svit!='' OR $cur_hod<3)
		{
			$join=99;
			$alt='�������� � ����� ��� ������ ����';
			return 1;
		}
		else
		{
			$join = 0;
			return '�� �� ������ �������������� � ����� ��� ������ ���� (��.������� = '.$sred_level.')';
		}
	}
	elseif ($type==6)
	{
		$error='';
		$sred_level = 0;
		$kol = 0;
		$sel = myquery("SELECT SUM(clevel) AS sumlevel,COUNT(*) AS kol FROM combat_users WHERE combat_id='".$player['boy']."' AND `join`=0  GROUP BY combat_id");
		list($sred_level,$kol) = mysql_fetch_array($sel);
		if ($kol==0)
		{
			return '� ��� ��� ������ ���!';
		}
		$sred_level=round($sred_level/$kol);
		//���� ���� �������� ������ ���� - ���������� 1, join=1, alt = ����� ALT ���� �������� ������
		//���� ���� �������� ������ ������ ������ - ���������� 1, join=99, alt = �������� �� �����
		//������� ��������� ����������� �������� ����� � ��� ��� �������. ���� ��������� - ������������, ����� �������� �������� �� �������
		if ($cur_hod<=3)
		{
			if (check_level6() OR (clans_war!=0 AND $map['dolina']==1))
			{
				$join=1;
				$alt='�������������� � ����� �����������';
				return 1;
			}
		}

		$svit = '';
		//�� �������� ������ �������� � ����� ��� �� 7 ����  
		if ($map['id']!=map_coliseum)
		{
			if ($ch_sred>0 AND $cur_hod<=6 ) $svit.=',2,';  
			//�� ������ ������ �������� �� ������� �������� � ����� ����� ����� 3 ����
			if ($ch_small>0 AND $cur_hod>=3 AND $cur_hod<=6 AND check_level6() ) $svit.=',1,';
		}
		if ($svit!='' OR $cur_hod<3)
		{
			$join=99;
			$alt='�������� � ����� �����������';
			return 1;
		}
		else
		{
			$join = 0;
			return '�� �� ������ �������������� � ����� ����������� (��.������� = '.$sred_level.')';
		}
	}
	elseif ($type==7)
	{
		$error='';
		$sred_level = 0;
		$kol = 0;
		$sel = myquery("SELECT SUM(clevel) AS sumlevel,COUNT(*) AS kol FROM combat_users WHERE combat_id='".$player['boy']."' AND `join`=0  GROUP BY combat_id");
		list($sred_level,$kol) = mysql_fetch_array($sel);
		if ($kol==0)
		{
			return '� ��� ��� ������ ���!';
		}
		$sred_level=round($sred_level/$kol);
		//���� ���� �������� ������ ���� - ���������� 1, join=1, alt = ����� ALT ���� �������� ������
		//���� ���� �������� ������ ������ ������ - ���������� 1, join=99, alt = �������� �� �����
		//������� ��������� ����������� �������� ����� � ��� ��� �������. ���� ��������� - ������������, ����� �������� �������� �� �������
		if ($cur_hod<=3)
		{
			if (check_level7() OR (clans_war!=0 AND $map['dolina']==1))
			{
				$join=1;
				$alt='�������������� � ����� ���';
				return 1;
			}
		}

		$svit = '';  
		//�� �������� ������ �������� � ����� ��� �� 7 ����  
		if ($ch_sred>0 AND $cur_hod<=6) $svit.=',2,';  
		//�� ������ ������ �������� �� ������� �������� � ����� ����� ����� 3 ����
		if ($ch_small>0 AND $cur_hod>=3 AND $cur_hod<=6 AND check_level7()) $svit.=',1,'; 
		if ($svit!='' OR $cur_hod<3)
		{
			$join=99;
			$alt='�������� � ����� ���';
			return 1;
		}
		else
		{
			$join = 0;
			return '�� �� ������ �������������� � ����� ��� (��.������� = '.$sred_level.')';
		}
	}
	return '';
}

function join_attack_user($char,$player,$svit)
{	
	$user_id=$char['user_id'];
	
	$map = mysql_fetch_array(myquery("SELECT * FROM game_maps WHERE id='".$char['map_name']."'"));
	list($type,$npc)=mysql_fetch_array(myquery("SELECT combat_type, npc FROM combat WHERE combat_id=".$player['boy'].""));
	switch ($type)
	{
		case 1:
			list($side) = mysql_fetch_array(myquery("SELECT side FROM combat_users WHERE combat_id=".$player['boy']." and user_id=".$player['user_id']." and npc=0"));
		break;

		case 2:
			$error='duel';
		break;

		case 3:
			$sred_level = 0;
			$sred_level_1 = 0;
			$side_1 = 0;
			$sred_level_2 = 0;
			$side_2 = 0;
			$kol = 0;
			$sel = myquery("SELECT clevel,side FROM combat_users WHERE combat_id='".$player['boy']."' AND `join`=0 AND svitok=0");
			while ($boyuser = mysql_fetch_array($sel))
			{
				$sred_level+=$boyuser['clevel'];
				$kol++;
				if ($side_1==0 or $side_1==$boyuser['side'])
				{
					$side_1=$boyuser['side'];
					$sred_level_1+=$boyuser['clevel'];
				}
				elseif ($side_2==0 or $side_2==$boyuser['side'])
				{
					$side_2=$boyuser['side'];
					$sred_level_2+=$boyuser['clevel'];
				}
			}
			$sred_level=round($sred_level/$kol);
			if ($sred_level_1<=$sred_level_2) $side=$side_1;
			else $side=$side_2;
		break;

		case 4:
			$side = $char['clan_id'];
		break;

		case 5:
			$side = $char['user_id'];
		break;

		case 6:
			$side = $char['sklon'];
		break;

		case 7:
			$side = $char['race'];
		break;
	}
	if ($svit==2 AND clans_war==0)
	{
		list($side) = mysql_fetch_array(myquery("SELECT side FROM combat_users WHERE combat_id=".$player['boy']." AND user_id=".$player['user_id'].""));
	}
	if (!isset($side))
	{
		list($side) = mysql_fetch_array(myquery("SELECT side FROM combat_users WHERE combat_id=".$player['boy']." AND user_id=".$player['user_id'].""));
	}	
	
	$k_komu = 0;
	$svit_id = 0;
	
	//���������� ������	
	if ($svit==1)
	{
		$svit_id=svitok_small_item_id;		
	}
	elseif ($svit==2)
	{
		$svit_id=svitok_sred_item_id;		
	}	
	
	if ($svit_id<>0)
	{
		$ch = myquery("SELECT id FROM game_items WHERE user_id=$user_id AND priznak=0 AND used IN (12,13,14) AND item_id='".$svit_id."' LIMIT 1");
		if (mysql_num_rows($ch))
		{
			$svitok = mysql_fetch_array($ch);
			$Item = new Item($svitok['id']);
			$Item->use_item();
		}
		$k_komu = $player['user_id'];
	}	
	
	save_stat($char['user_id'],'','',1,'','',$player['user_id'],'','','','','');

	$nam = 0;
	if ($type==1) $nam=14;
	if ($type==2) $nam=15;
	if ($type==3) $nam=16;
	if ($type==4) $nam=17;
	if ($type==5) $nam=18;
	if ($type==6) $nam=49;
	if ($type==7) $nam=48;
	
	//������ � ���������� ������ ������ ������������, ���� ��� �� � �����
	if ($npc == 0)
	{		
		myquery("DELETE FROM game_obelisk_users WHERE user_id=".$char['user_id']." AND type=6");
	}
	
	list($current_hod) = mysql_fetch_array(myquery("SELECT hod FROM combat WHERE combat_id=".$player['boy'].""));
	//������ � ��� ������
	combat_insert($char,0,$player['boy'],$type,$side,$current_hod,1,$svit,$k_komu,($map['k_exp']/100),($map['k_gp']/100),0,6,$nam,1);		
	setLocation("http://".domain_name."/combat.php");
}

function fill_maze_by_npc_for_user($mapid,$userid,$not_boss_exit=0)
{
	$user = mysql_fetch_array(myquery("SELECT * FROM view_active_users WHERE user_id=$userid"));
	list($kol_npc) = mysql_fetch_array(myquery("SELECT count_npc FROM game_maps WHERE id=$mapid"));
	if ($kol_npc>0)
	{
        myquery("DELETE FROM game_npc_template WHERE to_delete=1 AND npc_id IN (SELECT npc_id FROM game_npc WHERE map_name=$mapid AND for_user_id=$userid)");
		myquery("DELETE FROM game_npc WHERE map_name=$mapid AND for_user_id=$userid");
	}
	$pos = myquery("SELECT xpos,ypos FROM game_maze WHERE map_name=$mapid AND (move_up+move_down+move_left+move_right)>=1");
	
	while ($kol_npc>0)
	{
		
		$all = mysql_num_rows($pos);
		$r = mt_rand(0,$all-1);
		mysql_data_seek($pos,$r);
		$position = mysql_fetch_assoc($pos);
		if ($not_boss_exit==1)
        {
		/*
            //��� ���������� ����������:
            //������� �������� ������
            $npc_copy = mysql_fetch_array(myquery("SELECT * FROM game_npc_template WHERE npc_id=$npc_copy"));
            myquery("INSERT INTO game_npc_template (npc_name,npc_race,npc_img,npc_max_hp,npc_max_mp,item,npc_opis,agressive,npc_level,respawn,to_delete) VALUES ('".$npc_copy['npc_name']."','".$npc_copy['npc_race']."','".$npc_copy['npc_img']."','".max($user['HP_MAXX'],$user['HP_MAX'])."','".$user['MP_MAX']."','".$npc_copy['item']."','".$npc_copy['npc_opis']."','2','".$user['clevel']."','".$npc_copy['respawn']."','1')");
            //����� ������ ����
            $npc_copy = mysql_insert_id();
		*/
			myquery("INSERT INTO game_npc (prizrak,for_user_id,map_name,xpos,ypos,HP,MP,view,npc_id,stay) VALUES ('1',$userid,$mapid,".$position['xpos'].",".$position['ypos'].",30,1,0,1058886,4)");			
			$all = mysql_num_rows($pos);
			$r = mt_rand(0,$all-1);
			mysql_data_seek($pos,$r);
			$position = mysql_fetch_assoc($pos);
			myquery("INSERT INTO game_npc (prizrak,for_user_id,map_name,xpos,ypos,HP,MP,view,npc_id,stay, dropable) VALUES ('1',$userid,$mapid,".$position['xpos'].",".$position['ypos'].",30,1,0,1058887,4,1)");
        }
		else
		{
			$selnpc = myquery("SELECT npc_id FROM game_npc_template");
			$all = mysql_num_rows($selnpc);
			$r = mt_rand(0,$all-1);
			mysql_data_seek($selnpc,$r);
			list($npc_copy) = mysql_fetch_array($selnpc);
			myquery("INSERT INTO game_npc (prizrak,for_user_id,map_name,xpos,ypos,HP,MP,view,npc_id) VALUES ('1',$userid,$mapid,".$position['xpos'].",".$position['ypos'].",".max($user['HP_MAXX'],$user['HP_MAX']).",".$user['MP_MAX'].",0,".$npc_copy.")");
		}
		$kol_npc--;
	}
	if ($not_boss_exit==0 and $kol_npc<>0)
	{
		list($max_x,$max_y) = mysql_fetch_array(myquery("SELECT xpos,ypos FROM game_maze WHERE map_name=$mapid ORDER BY xpos DESC, ypos DESC LIMIT 1"));
		myquery("INSERT INTO game_npc (prizrak,for_user_id,map_name,xpos,ypos,view,npc_id) VALUES ('1',$userid,$mapid,$max_x,$max_y,0,".npc_id_boss_labirint.")");
	}
}
?>