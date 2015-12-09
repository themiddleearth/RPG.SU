<?

if (function_exists("start_debug")) start_debug();

$userban=myquery("select * from game_ban where user_id='$user_id' and type=2 and time>'".time()."'");
if (mysql_num_rows($userban))
{
	$userr = mysql_fetch_array($userban);
	$min = ceil(($userr['time']-time())/60);
	echo '<center><br><br><br>На тебя наложено ПРОКЛЯТИЕ на '.$min.' минут! Тебе запрещено заниматься у тренеров!';
	{if (function_exists("save_debug")) save_debug(); exit;}
}

$img='http://'.img_domain.'/race_table/gnom/table';

echo'<table width=100% border="0" cellspacing="0" cellpadding="0"><tr><td width="1" height="1"><img src="'.$img.'_lt.gif"></td><td background="'.$img.'_mt.gif"></td><td width="1" height="1"><img src="'.$img.'_rt.gif"></td></tr>
<tr><td background="'.$img.'_lm.gif"></td><td background="'.$img.'_mm.gif" valign="top" width="100%" height="100%">';

if ($town!=0)
{
	list($town_now) = mysql_fetch_array(myquery("SELECT town FROM game_map WHERE name=".$char['map_name']." AND xpos=".$char['map_xpos']." AND ypos=".$char['map_ypos'].""));
	if ($town_now!=$town) exit;
	$town_now = mysql_fetch_array(myquery("SELECT * FROM game_gorod WHERE town=$town"));
	$race = mysql_fetch_array(myquery("SELECT * FROM game_har WHERE id=".$char['race'].""));

	$ren = array();
	$ren['STR']='har_1';
	$ren['SPD']='har_2';
	$ren['NTL']='har_3';
	$ren['PIE']='har_4';
	$ren['VIT']='har_5';
	$ren['DEX']='har_6';

	$ar['ren']['har_1']='STR';
	$ar['town']['har_1']='STR';
	$ar['name']['har_1']='СИЛА';
	$ar['ren']['har_2']='SPD';
	$ar['town']['har_2']='SPD';
	$ar['name']['har_2']='МУДРОСТЬ';
	$ar['ren']['har_3']='NTL';
	$ar['town']['har_3']='NTL';
	$ar['name']['har_3']='ИНТЕЛЛЕКТ';
	$ar['ren']['har_4']='PIE';
	$ar['town']['har_4']='PIE';
	$ar['name']['har_4']='ЛОВКОСТЬ';
	$ar['ren']['har_5']='VIT';
	$ar['town']['har_5']='VIT';
	$ar['name']['har_5']='ЗАЩИТА';
	$ar['ren']['har_6']='DEX';
	$ar['town']['har_6']='DEX';
	$ar['name']['har_6']='ВЫНОСЛИВОСТЬ';

	//ar[max] - максимальные значения прокачки навыков и специализаций, иначе по умолчанию максимум = 15
	//ar[ren] - перевод параметра $_GET в поле game_users
	//ar[town] - перевод параметра $_GET в поле game_gorod

	function check_training($par)
	{
		global $char,$town_now,$ar;
		$max_ms = 15;
		if (isset($_POST['upp']) and is_numeric($_POST['upp']) and (int)$_POST['upp']>0)
		{
			$upp=(int)$_POST['upp'];
		}
		else 
		{
			$upp='1';
		}
		switch ($ar['ren'][$par])
		{
			case 'STR': {if ($char['bound']>0 AND $char['bound']>=$upp AND $town_now[$ar['town'][$par]]==1) {return 1;}} break;
			case 'SPD': {if ($char['bound']>0 AND $char['bound']>=$upp AND $town_now[$ar['town'][$par]]==1) {return 1;}} break;
			case 'NTL': {if ($char['bound']>0 AND $char['bound']>=$upp AND $town_now[$ar['town'][$par]]==1) {return 1;}} break;
			case 'PIE': {if ($char['bound']>0 AND $char['bound']>=$upp AND $town_now[$ar['town'][$par]]==1) {return 1;}} break;
			case 'VIT': {if ($char['bound']>0 AND $char['bound']>=$upp AND $town_now[$ar['town'][$par]]==1) {return 1;}} break;
			case 'DEX': {if ($char['bound']>0 AND $char['bound']>=$upp AND $town_now[$ar['town'][$par]]==1) {return 1;}} break;			
		}
		return 0;
	}

	function training_har($par)
	{
		global $char,$ar,$town_now,$option;
		if (isset($_POST['upp']) and is_numeric($_POST['upp']) and (int)$_POST['upp']>0)
		{
			$upp=(int)$_POST['upp'];			
			if (domain_name == 'testing.rpg.su' or domain_name=='localhost') 
			{
				$par_effect=15*$upp;
			}
			else
			{
				$par_effect=15*$upp;
			}
			$check = check_training($par);
			$query_string = '';
			if ($check==1) //повышаем характеристики
			{
				$par_new = $ar['ren'][$par];
				$query_string = "UPDATE game_users SET bound=bound-$upp,$par_new=$par_new+$upp,".$par_new."_MAX=".$par_new."_MAX+$upp";
				if ($par_new=='NTL') { $query_string.=",MP_MAX=MP_MAX+$par_effect";}
				if ($par_new=='PIE') { $query_string.=",STM_MAX=STM_MAX+$par_effect";}
				if ($par_new=='DEX') { $query_string.=",HP_MAX=HP_MAX+$par_effect,HP_MAXX=HP_MAXX+$par_effect,CC=CC+2*$upp";}
				$query_string.=" WHERE user_id=".$char['user_id']."";
			}			
			if ($query_string!='')
			{
				myquery($query_string);
				echo'<center><b><font face=verdana size=2 color=ff0000>';
				if ($check==1)
				{
					echo'Вы повысили характеристику '.$ar['name'][$par];
				}			
			}
			else
			{
				echo'<center><b><font face=verdana size=2 color=ff0000>';
				echo'Вы не можете повысить характеристику!';				
			}
		}
		else
		{
			echo'<center><b><font face=verdana size=2 color=ff0000>';
			echo'Вы неверно ввели параметры!';			
		}
		echo '</font></b></center><meta http-equiv="refresh" content="2;url=town.php?option='.$option.'">';
	}	
	

	if (isset($_GET['do']))
	{      
        training_har($_GET['do']);
		echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
		{if (function_exists("save_debug")) save_debug(); exit;}
	}
	elseif (isset($_GET['up']))
	{      
        echo'<center><b><font face=verdana size=2 color=ff0000>';
		if (isset($_POST['upp']) and is_numeric($_POST['upp']) and (int)$_POST['upp']>0 and is_numeric($_GET['skill']) and (int)$_GET['skill']>0)
		{
			$upp=(int)$_POST['upp'];			
			$skill_id=(int)$_GET['skill'];
			$check1=myquery("SELECT gs.*, (CASE WHEN gus.level IS NULL THEN 0 ELSE gus.level END) as lev FROM game_gorod_skills ggs JOIN game_skills gs ON ggs.skill_id=gs.id LEFT JOIN game_users_skills gus ON (ggs.skill_id=gus.skill_id AND gus.user_id=".$char['user_id'].") WHERE gorod_id=".$town." AND ggs.skill_id=".$skill_id." ");
			if (mysql_num_rows($check1)==0) //повышаем характеристики
			{
				echo 'Данную специализацию нельзя прокачать в данном городе!';
			}
			elseif ($char['exam']<$upp)
			{
				echo 'У Вас недостаточно очков специализаций для данного действия!';
			}
			else
			{
				$skill=mysql_fetch_array($check1);
				if ($skill['reinc']>$char['reinc'])
				{
					echo 'Ваша реинкарнация не позволяет выучить данную специализацию!';
				}
				elseif ($skill['level']<=$skill['lev'])
				{
					echo 'Вы уже полностью изучили данную специализацию!';
				}
				else
				{
					$no_train=0;
					if ($skill['sgroup']==1)
					{
						$check2=myquery("SELECT gs.id FROM game_users_skills gus JOIN game_skills gs ON gus.skill_id=gs.id WHERE gus.user_id=".$user_id." AND gus.skill_id<>".$skill_id." AND gs.sgroup=1");
						$no_train=mysql_num_rows($check2);
					}
					if ($no_train==0)
					{
						$upp=min(($skill['level']-$skill['lev']),$upp);
						add_skill($user_id,$skill_id,$upp);
						myquery("UPDATE game_users SET exam=exam-".$upp." WHERE user_id=".$user_id."");
						echo 'Вы успешно прокачали специализацию!';
					}
					else
					{
						echo 'Вы не можете выучить ещё одну основную специализацию!';
					}
				}	
			}
		}
		else
		{
			echo'<center><b><font face=verdana size=2 color=ff0000>';
			echo'Вы неверно ввели параметры!';			
		}
		echo '</font></b></center><br><br><meta http-equiv="refresh" content="2;url=town.php?option='.$option.'">';		
	}

	//Блок вывода характеристик
	echo'<table border=0 cellspacing="1" cellpadding="2"><tr><td valign=top>';
	OpenTable('title');
	
	echo'<table cellpadding="1" cellspacing="1" border="0" width="260"><tr><td>
	<font color="#FFFF00"><b>Характеристики:</font> (<font color="#FF0000">' . $char['bound'] . '</font>)</b><br><br>
	<table cellpadding="0" cellspacing="0" border="0" width="260"><tr><td>';
	
	function print_har($par)
	{
		global $town_now,$ar,$char,$option;
		if ($town_now[$ar['town'][$par]]>0)
		{
			echo '<form method="POST" action="town.php?option='.$option.'&do='.$par.'" >';
			echo '<tr><td width="190">'.$ar['name'][$par].'</td><td width="110"><div align="right">(' . $char[$ar['ren'][$par]] . ') ';
			if(check_training($par)>0)
			{
				echo'&nbsp;&nbsp;&nbsp;<input type="text" name="upp" value="1" size="2" max="3"><input type="image" name="save" size="1" src="http://'.img_domain.'/nav/up.gif" border=0 >';
			}
			echo '</div></td></tr></form>'; 	
		}
	}

	print_har($ren['STR']);
	print_har($ren['NTL']);
	print_har($ren['PIE']);
	print_har($ren['VIT']);
	print_har($ren['DEX']);
	print_har($ren['SPD']);

	echo'</div></td></tr></table>';
	echo'</td></tr></table>';
	OpenTable('close');

	//Блок вывода специализаций
	OpenTable('title');
	echo' <table width="260" border="0" cellspacing="1" cellpadding="1"><tr><td height="21">';
	echo' <font color="#FFFF00"><b>Cпециализации:</font> (<font color="#FF0000">' . $char['exam'] . '</font>)';	
	
	$check=myquery("SELECT gs.*, (CASE WHEN gus.level IS NULL THEN 0 ELSE gus.level END) as lev FROM game_gorod_skills ggs JOIN game_skills gs ON ggs.skill_id=gs.id LEFT JOIN game_users_skills gus ON (ggs.skill_id=gus.skill_id AND gus.user_id=".$char['user_id'].") WHERE gorod_id=".$town." ORDER BY gs.sgroup DESC, gs.name");
	if (mysql_num_rows($check)>0)
	{
		echo' <table cellpadding="1" cellspacing="0" align="center" border="0" width="260">';
		echo'<tr><td colspan=2>&nbsp;</td></tr>';
		$i=0;
		while ($skill=mysql_fetch_array($check))
		{
			echo '<form method="POST" action="town.php?option='.$option.'&up&skill='.$skill['id'].'">';			
			if ($skill['sgroup']==1) echo '<tr><td width="200"><b>'.$skill['name'].'</b></td>';
			else echo '<tr><td width="200">'.$skill['name'].'</td>';
			echo '<td width="100"><div align="right"> ('.$skill['lev'].') ';
			if ($skill['level']>$skill['lev'] AND $skill['reinc']<=$char['reinc'])
			{
				echo'&nbsp;&nbsp;&nbsp;<input type="text" name="upp" value="1" size="2" max="3"><input type="image" name="save" size="1" src="http://'.img_domain.'/nav/up1.gif" border=0 >';
			}
			echo'</div></td></tr></form>';
			$descr[$i]['name']=$skill['name'];
			$descr[$i]['descr']=$skill['descr'];
			$i++;
		}
		echo'</div></td></table></td>';
	}

	echo'</td></tr></table>';
	OpenTable('close');
	echo '</td><td width="100%" valign=top>';
	OpenTable('title');
	echo '<div style="padding: 5px 5px; font-size:12px;"><span style="color:red;font_weight:900">';
   
    //Блок описания характеристик
	echo'</span><table width="100%" border="0" cellspacing="0" cellpadding="2">';
	echo '<tr><td><b>Характеристики:</b><br></td></tr>';
	$par = $ren['STR'];
	if ($town_now[$ar['town'][$par]]>0)
		echo '<tr><td>Cила - Влияет на атаку</td></tr>';
	$par = $ren['PIE'];
	if ($town_now[$ar['town'][$par]]>0)
		echo '<tr><td>Ловкость - Влияет на количество энергии и уворачивание от ударов</td></tr>';
	$par = $ren['VIT'];
	if ($town_now[$ar['town'][$par]]>0)
		echo '<tr><td>Защита - Влияет на защиту от атак оружием</td></tr>';
	$par = $ren['DEX'];
	if ($town_now[$ar['town'][$par]]>0)
		echo '<tr><td>Выносливость - Влияет на количество жизней и перенос вещей</td></tr>';
	$par = $ren['SPD'];
	if ($town_now[$ar['town'][$par]]>0)
		echo '<tr><td>Мудрость - Влияет на удачное использование заклинаний!</td></tr>';
	$par = $ren['NTL'];
	if ($town_now[$ar['town'][$par]]>0)
		echo '<tr><td>Интеллект - Влияет на атаку магией и количество маны.</td></tr>';
	
	//Блок описания специализаций
	echo '<tr><td><b><br>Специализации:</b><br></td></tr>';
	if (isset($descr))
	{
		for ($j=0;$j<$i;$j++)
		{
			echo '<tr><td>'.$descr[$j]['name'].' - '.$descr[$j]['descr'].'</td></tr>';
		}
	}
	
	echo '</table>';
	echo '</div>';
	OpenTable('close');
	echo'</tr></table>';
}
echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';

if (function_exists("save_debug")) save_debug();

?>