<?php

//Обнулим игрока
function user_reset ($user_id, $race=-1, $exp=0)
{
	//Возьмём расовые харки
	if ($race=-1) list($race)=mysql_fetch_array(myquery("SELECT race FROM game_users WHERE user_id=$user_id UNION SELECT race FROM game_users_archive WHERE user_id=$user_id"));
	$result=myquery("select * from game_har where id='".$race."'");
	$row=mysql_fetch_array($result);
	$hp_maxn=$row["hp_max"];
	$mp_maxn=$row["mp_max"];
	$stm_maxn=$row["stm_max"];
	$strn=$row["str"];
	$ntln=$row["ntl"];
	$pien=$row["pie"];
	$vitn=$row["vit"];
	$dexn=$row["dex"];
	$spdn=$row["spd"];
	
	//Снимем все вещи
	$upd=myquery("UPDATE game_items SET used=0 WHERE user_id='".$user_id."' and priznak=0");
	
	$cc=0;
	//Подсчитаем вес предметов у игрока
	$check_weight=myquery(" SELECT SUM( gis.weight * (CASE WHEN gis.type in (12, 13, 19, 21, 22, 95) THEN count_item ELSE 1 END)  ) w
							FROM game_items gi JOIN game_items_factsheet gis ON gi.item_id = gis.id
							WHERE gi.priznak =0 AND gi.user_id ='".$user_id."'
							HAVING w IS NOT NULL");
	if (mysql_num_rows($check_weight)>0)
	{
		list($item)=mysql_fetch_array($check_weight);
		$cc=$cc+$item;
	}	
	//Подсчитаем вес предметов у игрока
	$check_weight=myquery(" SELECT SUM( cr.weight*cru.col ) w
							FROM craft_resource_user cru JOIN craft_resource cr ON cru.res_id = cr.id
							WHERE cru.user_id ='".$user_id."'
							HAVING w IS NOT NULL");
	if (mysql_num_rows($check_weight)>0)
	{
		list($res)=mysql_fetch_array($check_weight);
		$cc=$cc+$res;
	}	
	
	//Cделаем игрока 0 уровня со стандартными харками и без специализаций
	//Деньги не обнуляются!
	$up = myquery("DELETE FROM game_users_skills WHERE user_id='".$user_id."'");
	$up = myquery("UPDATE game_users SET clevel =0, EXP=(CASE WHEN ".$exp."=0 THEN 0 ELSE EXP END), bound=0, exam=0, CW='$cc',
		HP='$hp_maxn', HP_MAX='$hp_maxn', HP_MAXX='$hp_maxn', MP='$mp_maxn', MP_MAX='$mp_maxn', STM='$stm_maxn', STM_MAX='$stm_maxn', PR=50, PR_MAX=50, STR='$strn', NTL='$ntln', PIE='$pien', 
		VIT='$vitn', DEX='$dexn', SPD='$spdn', STR_MAX='$strn', NTL_MAX='$ntln', PIE_MAX='$pien', VIT_MAX='$vitn', DEX_MAX='$dexn', SPD_MAX='$spdn', CC=40, lucky=0, lucky_max=0,  
	    dvij=1, vsadnik=0, hide_charges=0 WHERE user_id='$user_id'");
	$up = myquery("UPDATE game_users_archive SET clevel =0, EXP=(CASE WHEN ".$exp."=0 THEN 0 ELSE EXP END), bound=0, exam=0,  CW='$cc',
		HP='$hp_maxn', HP_MAX='$hp_maxn', HP_MAXX='$hp_maxn', MP='$mp_maxn', MP_MAX='$mp_maxn', STM='$stm_maxn', STM_MAX='$stm_maxn', PR=50, PR_MAX=50, STR='$strn', NTL='$ntln', PIE='$pien', 
		VIT='$vitn', DEX='$dexn', SPD='$spdn', STR_MAX='$strn', NTL_MAX='$ntln', PIE_MAX='$pien', VIT_MAX='$vitn', DEX_MAX='$dexn', SPD_MAX='$spdn', CC=40, lucky=0, lucky_max=0,  
	    dvij=1, vsadnik=0, hide_charges=0 WHERE user_id='$user_id'");		
}

//"Забудем" крафтовые профессии игрока
function user_craft_reset ($user_id)
{
	myquery("UPDATE game_users_crafts SET profile=0 Where user_id='".$user_id."'");
}

function add_skill ($user_id, $skill_id, $skill_level, $k = 100)
{
	$check=myquery("SELECT level FROM game_users_skills WHERE user_id=".$user_id." AND skill_id=".$skill_id."");
	//Если навык повышаем
	if ($skill_level>0)
	{
		if (mysql_num_rows($check)==0)
		{
			myquery("INSERT INTO game_users_skills (user_id, skill_id, level) VALUES (".$user_id.", ".$skill_id.", ".$skill_level.")");
		}
		else
		{
			myquery("UPDATE game_users_skills SET level=level+".$skill_level." WHERE user_id=".$user_id." AND skill_id=".$skill_id."");
		}
	}
	//Если навык понижаем
	elseif ($skill_level<0)
	{
		list($level)=mysql_fetch_array($check);
		if ($level+$skill_level<=0)
		{
			myquery("DELETE FROM game_users_skills WHERE user_id=".$user_id." AND skill_id=".$skill_id."");
		}
		else
		{
			myquery("UPDATE game_users_skills SET level=level+".$skill_level." WHERE user_id=".$user_id." AND skill_id=".$skill_id."");
		}
		//Проверка на необходимость удаления коня
		if ($skill_id==25)
		{
			$sel=myquery("SELECT * FROM game_vsadnik gv JOIN game_users_horses guh ON gv.id=guh.horse_id WHERE guh.user_id=".$user_id." AND guh.used=1");
			$row=mysql_fetch_array($sel);
			if ($row['vsad']>$level+$skill_level)
			{
				return_horse($user_id, $k);
			}
		}
	}
	//Проверка на необходимость изменения уровня всадника игрока
	if ($skill_id==25)
	{
		myquery("UPDATE game_users SET vsadnik=vsadnik+".$skill_level." WHERE user_id=".$user_id."");
		myquery("UPDATE game_users_archive SET vsadnik=vsadnik+".$skill_level." WHERE user_id=".$user_id."");
	}
	elseif ($skill_id==35)
	{
		myquery("UPDATE game_users SET hide_charges=GREATEST(hide_charges+".$skill_level.",0) WHERE user_id=".$user_id."");
		myquery("UPDATE game_users_archive SET hide_charges=GREATEST(hide_charges+".$skill_level.",0) WHERE user_id=".$user_id."");
	}
	elseif ($skill_id==36)
	{
		myquery("UPDATE game_users SET PR=PR+".($skill_level*50).", PR_MAX=PR_MAX+".($skill_level*50)." WHERE user_id=".$user_id."");
		myquery("UPDATE game_users_archive SET PR=PR+".($skill_level*50).", PR_MAX=PR_MAX+".($skill_level*50)." WHERE user_id=".$user_id."");
	}
}

function add_skill_system ($user_id, $reinc, $level)
{
	//Выдаём кулачку
	$slevel=min(15,$level*3);
	add_skill ($user_id,21,$slevel);
	
	//Выдаём верховую
	if ($reinc==0 AND $level>=5)
	{
		$slevel=1;
		add_skill ($user_id,25,$slevel);
	}
		
	//Выдадим Защиту Валар
	if ($reinc<2 AND $level>=5)
	{
		$slevel=15*(2-$reinc);
		add_skill ($user_id,32,$slevel);
	}
}


//Составим массив специализаций игрока
function take_skills($user_id)
{
	$skill['class_type']=0;
	$skill['class_level']=0;
	$skill['MS_WEAPON']=0;
	$skill['MS_KULAK']=0;
	$skill['MS_PARIR']=0;
	$skill['MS_ART']=0;
	$skill['MS_LUK']=0;
	$skill['MS_THROW']=0;
	$skill['NPC_DEFENCE']=0;
	$skill['MS_BERSERK']=0;
	$skill['MS_PRUDENCE']=0;
	$skill['MS_VAMPIRE']=0;
	$skill['MS_SPIKES']=0;
	$skill['MS_EXP']=0;
	$skill['MS_GP']=0;
	$skill['SLAYER']=0;
	$skill['PALADIN']=0;
	$skill['PALADIN']=0;
	$skill['HIDE']=0;
	$check=myquery("SELECT gs.id, gs.sgroup, gus.level FROM game_users_skills gus JOIN game_skills gs ON gus.skill_id=gs.id WHERE gus.user_id='".$user_id."'");
	if (mysql_num_rows($check)>0)
	{
		while ($us=mysql_fetch_array($check))
		{
			if ($us['sgroup']==1)
			{
				$skill['class_type']=$us['id'];
				$skill['class_level']=$us['level'];
			}
			else
			{
				switch ($us['id'])
				{
					case 9: {$skill['MS_WEAPON']=$us['level']; break;}
					case 20: {$skill['MS_ART']=$us['level']; break;}
					case 21: {$skill['MS_KULAK']=$us['level']; break;}
					case 22: {$skill['MS_PARIR']=$us['level']; break;}
					case 23: {$skill['MS_LUK']=$us['level']; break;}
					case 24: {$skill['MS_THROW']=$us['level']; break;}					
					case 26: {$skill['MS_BERSERK']=$us['level']; break;}
					case 27: {$skill['MS_PRUDENCE']=$us['level']; break;}
					case 28: {$skill['MS_VAMPIRE']=$us['level']; break;}
					case 29: {$skill['MS_SPIKES']=$us['level']; break;}
					case 30: {$skill['MS_EXP']=$us['level']; break;}
					case 31: {$skill['MS_GP']=$us['level']; break;}
					case 32: {$skill['NPC_DEFENCE']=$us['level']; break;}
					case 33: {$skill['SLAYER']=$us['level']; break;}
					case 34: {$skill['PALADIN']=$us['level']; break;}
					case 35: {$skill['HIDE']=$us['level']; break;}
				}
			}
		}
	}
	return $skill;
}

//Количество денег для определённого уровня игрока
function get_gold_sum ($level)
{
	$i=0;
	$allgp=0;
	for($i=0;$i<=$level-1;$i++)
	{
		if ($i >= 0 and $i < 9) $gp=50;        
		if ($i == 9) $gp=300;

		if ($i >= 10 and $i < 19) $gp=100;
		if ($i == 19) $gp=500;

		if ($i >= 20 and $i < 29) $gp=200;
		if ($i == 29) $gp=1000;

		if ($i >= 30 and $i < 39) $gp=300;
		if ($i == 39) $gp=1500; 
		
		$allgp+=$gp;                
	}
	return $allgp;
}

//Количество навыков для определённого уровня реинкарнации
function get_skills_number ($reinc, $level=0)
{
	$kol=0;
	//Навыки за реинкарнации
	if ($reinc>0)
	{
		$kol=13;
		for ($i=1;$i<$reinc;$i++)
		{	
			for ($j=$i+15;$j<=40;$j++)
			{
				if ($j%3==0) $kol++;
			}
		}
	}
	
	//Навыки за левела
	if ($level>0)
	{
		if ($reinc==0) $start=0;
		else $start=$reinc+15;
		while ($level>$start)
		{
			if ($level%3==0) $kol+=1;
			$level--;
		}
	}
	
	return $kol;
}

//Количество характеристик для определённого уровня реинкарнации
function get_harks_number ($level, $reinc=0)
{
	$kol=$level*2+floor($level/10);
	return $kol;
}

//Удалим лошадь игрока и вернём величину её стоимости
function return_horse ($user_id, $k = 100)
{
	$sel = myquery("SELECT game_vsadnik.cena, game_vsadnik.ves, game_vsadnik.vsad FROM game_vsadnik, game_users_horses WHERE game_vsadnik.id=game_users_horses.horse_id AND game_users_horses.user_id=".$user_id." AND game_users_horses.used=1");
	if (mysql_num_rows($sel)!=0) 
	{
		list($gp,$wei,$lev)=mysql_fetch_array($sel);
		$gp = round($gp*$k/100);
		myquery("DELETE FROM game_users_horses WHERE user_id=".$user_id." AND used=1");	
		myquery("UPDATE game_users SET gp=gp+".$gp.", CC=CC-".$wei.", vsadnik=vsadnik-".($lev*vsad)." WHERE user_id='".$user_id."'");
		myquery("UPDATE game_users_archive SET gp=gp+".$gp.", CC=CC-".$wei.", vsadnik=vsadnik-".($lev*vsad)." WHERE user_id='".$user_id."'");
		if ($gp > 0)
		{
			setGP($user_id,$gp,53);
		}
	}
}

//Получим уровень лошади игрока от текущего уровня всадника
function get_horse_level ($level)
{
	$i=0;
	while ($level>20)
	{
		$i++;
		$level=$level-vsad;
	}
	return $i;
}

//Получим уровень верховой езды игрока
function get_vsad_level ($level)
{
	$level=$level % vsad;
	return $level;
}

//Обновим уровень всадника игрока
function vsadnik_update ($user_id, $type=0)
{
	$check1=myquery("SELECT level FROM game_users_skills WHERE user_id=".$user_id." AND skill_id=25");
	if (mysql_num_rows($check1)>0)
	{
		list($lev1)=mysql_fetch_array($check1);
	}
	else
	{
		$lev1=0;
	}
	$check2=myquery("SELECT gv.vsad FROM game_vsadnik as gv JOIN game_users_horses as guh ON guh.horse_id=gv.id WHERE guh.user_id=".$user_id." AND guh.used=1");
	if (mysql_num_rows($check2)>0)
	{
		list($lev2)=mysql_fetch_array($check2);
	}
	else
	{
		$lev2=0;
	}	
	$vsadnik=$lev1+$lev2*vsad;
	if ($type==0 or $type==1)
	{
		myquery("UPDATE game_users SET vsadnik=".$vsadnik." WHERE user_id=".$user_id."");
	}
	if ($type==0 or $type==2)
	{
		myquery("UPDATE game_users_archive SET vsadnik=".$vsadnik." WHERE user_id=".$user_id."");
	}
}

function set_reincarnation ($user_id, $reinc=0)
{
	$up = myquery("INSERT INTO game_users_reincarnation (user_id, reincarnation_date) VALUES ('".$user_id."', ".(time()+$reinc).") ");
}

//Повысим харки игрока взависимости от его временных
function add_time_harks($user_id)
{
	$check=myquery("SELECT harka, SUM(value) as value FROM game_obelisk_users WHERE user_id='".$user_id."' and type=2 GROUP BY harka");
	if (mysql_num_rows($check)>0)
	{
		$i=0;
		while ($har=mysql_fetch_array($check))
		{
			if ($i==0) $query=$har['harka']."=".$har['harka']."+".$har['value'];
			else $query=$query.", ".$har['harka']."=".$har['harka']."+".$har['value'];
			$i++;
		}
		myquery("UPDATE game_users SET ".$query." WHERE user_id='".$user_id."'");
		myquery("UPDATE game_users_archive SET ".$query." WHERE user_id='".$user_id."'");
	}
}

function make_full_obnyl ($id, $exp=0)
{
	$us=mysql_fetch_array(myquery("SELECT * FROM game_users WHERE user_id='".$id."' UNION ALL SELECT * From game_users_archive Where user_id='".$id."'"));
	
	//Подсчитаем количество навыков и харок
	$add_nav=get_skills_number($us['reinc'], $us['clevel']);
	$add_har=get_harks_number($us['clevel'], $us['reinc']);
	
	//Удалим коня 
	return_horse($us['user_id']);	
	
	//Обнулим данные игрока
	user_reset($us['user_id'], $us['race'], $exp);		
	
	//Выдадим игроку специализации
	add_skill_system($id,$us['reinc'],$us['clevel']);		

	//Вспомним про временные харки игрока
	add_time_harks($us['user_id']);
	
	$up = myquery("UPDATE game_users SET clevel='".$us['clevel']."', bound=$add_har, exam=$add_nav WHERE user_id='".$id."'");
	$up = myquery("UPDATE game_users_archive SET clevel='".$us['clevel']."', bound=$add_har, exam=$add_nav WHERE user_id='".$id."'");	
}

function add_lr ($user_id, $lr)
{
	myquery("UPDATE game_users_data SET user_rating=user_rating+'".$lr."' WHERE user_id= '".$user_id."'");
}

?>