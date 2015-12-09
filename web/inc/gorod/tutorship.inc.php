<?
/*
Значения поля confirmed в таблице game_tutorship:
0 - Поданная заявка на ученичество
1 - Подтверждённая заявка на ученичество
2 - Ученик прошёл 1-ую реинкарнацию
*/
if (function_exists("start_debug")) start_debug(); 

if ($town!=0)
{
	//Определим константы
	$cost_newp=100;      //Стоимость наставничества
	$cost_declinep=500;  //Стоимость отказа от ученика
	$cost_declinet=1000; //Стоимость отказа от наставника
	$pupil_level=14;     //Максимальный уровень, когда игрок может стать учеником	
	
	$userban=myquery("select * from game_ban where user_id='$user_id' and type=2 and time>'".time()."'");
	if (mysql_num_rows($userban))
	{
		$userr = mysql_fetch_array($userban);
		$min = ceil(($userr['time']-time())/60);
		echo '<center><br><br><br>На тебя наложено ПРОКЛЯТИЕ на '.$min.' минут! Тебе запрещено пользоваться алтарем!';
		{if (function_exists("save_debug")) save_debug(); exit;}
	}	
		
	$img='http://'.img_domain.'/race_table/human/table';
	echo'<table width=100% border="0" cellspacing="0" cellpadding="0"><tr><td width="1" height="1"><img src="'.$img.'_lt.gif"></td><td background="'.$img.'_mt.gif"></td><td width="1" height="1"><img src="'.$img.'_rt.gif"></td></tr>
	<tr><td background="'.$img.'_lm.gif"></td><td style="text-align:center" background="'.$img.'_mm.gif" valign="top" width="100%" height="100%">';
	echo '<center>';
	
	if (isset($_POST['addtutor']))	//Подача заявки на Наставничество
	{
		if ($char['GP']<$cost_newp)
		{
			echo '<font face=verdana color=ff0000 size=2>У Вас недостаточно денег для приглашения Ученика!</font>';
		}
		elseif ($char['reinc']<1)
		{
			echo '<font face=verdana color=ff0000 size=2>Вы не прошли ещё ни одной реинкарнации и не можете ещё быть Наставником!</font>';
		}
		elseif (!is_numeric($_POST['in_id']) or $_POST['in_id'] == 0 or $_POST['in_name']=='')
		{
			echo '<font face=verdana color=ff0000 size=2>Ученик не найден!</font>';
		}
		elseif (mysql_num_rows(myquery("SELECT * FROM game_users WHERE user_id = '".$_POST['in_id']."' and name = '".$_POST['in_name']."' and clevel<15 and clan_id <> 1  UNION ALL 
		                                SELECT * FROM game_users_archive WHERE user_id = '".$_POST['in_id']."' and name = '".$_POST['in_name']."' and clevel<15 and clan_id <> 1")) == 0)
		{
			echo '<font face=verdana color=ff0000 size=2>Ученик не найден!</font>';
		}		
		elseif (mysql_num_rows(myquery("SELECT * FROM game_tutorship WHERE user_id = '".$user_id."' and pupil_id = '".$_POST['in_id']."' ")) > 0)
		{
			echo '<font face=verdana color=ff0000 size=2>Вы уже подали такую заявку!</font>';
		}	
		else
		{
			echo 'Вы действительно хотите, чтобы игрок <b>'.$_POST['in_name'].'</b> стал Вашим Учеником?';
			echo '<br><a href="town.php?option='.$option.'&addtutor&in_id='.$_POST['in_id'].'&in_name='.$_POST['in_name'].'">Да, подать заявку</a>';
			echo '<br><a href="town.php?option='.$option.'">Нет, отменить заявку</a>';			
		}		
		echo '<br><br>';
	}
	elseif (isset($_GET['addtutor']))//Сохранение в бд заявки на Наставничество
	{
		if ($char['GP']<$cost_newp)
		{
			echo '<font face=verdana color=ff0000 size=2>У Вас недостаточно денег для приглашения Ученика!</font>';
		}		
		elseif ($user_id == $_GET['in_id'])
		{
			echo '<font face=verdana color=ff0000 size=2>Нельзя быть собственным Учеником!</font>';
		}
		elseif ($char['reinc']<1)
		{
			echo '<font face=verdana color=ff0000 size=2>Вы не прошли ещё ни одной реинкарнации и не можете ещё быть Наставником!</font>';
		}
		elseif (!is_numeric($_GET['in_id']) or $_GET['in_id'] == 0)
		{
			echo '<font face=verdana color=ff0000 size=2>Ученик не найден!</font>';
		}
		elseif (mysql_num_rows(myquery("SELECT * FROM game_users WHERE user_id = '".$_GET['in_id']."' and name = '".$_GET['in_name']."' and clevel<15 and clan_id <> 1 UNION ALL 
		                                SELECT * FROM game_users_archive WHERE user_id = '".$_GET['in_id']."' and name = '".$_GET['in_name']."' and clevel<15 and clan_id <> 1")) == 0)
		{
			echo '<font face=verdana color=ff0000 size=2>Ученик не найден!</font>';
		}			
		elseif (mysql_num_rows(myquery("SELECT * FROM game_tutorship WHERE user_id = '".$user_id."' and pupil_id = '".$_GET['in_id']."' ")) > 0)
		{
			echo '<font face=verdana color=ff0000 size=2>Вы уже подали такую заявку!</font>';
		}		
		else //Все проверки пройдены!
		{
			myquery("INSERT INTO game_tutorship (user_id, pupil_id) VALUES ('".$user_id."', '".$_GET['in_id']."') ");
			$theme = 'Гильдия Наставников';
			$post = 'Игрок <b>'.$char['name'].'</b> хочет стать Вашим Наставником!';
			myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time, folder) VALUES ('".$_GET['in_id']."', '0', '".$theme."', '".$post."','0','".time()."',1)");	
			echo '<b>Вы успешно подали заявку на Наставничество для игрока <u>'.$_GET['in_name'].'</u></b>';
		}
		echo '<br><br>';
	}
	elseif (isset($_GET['settutor']))//Закрепление Ученика за Наставником
	{
		if ($char['clevel']>14)
		{
			echo '<font face=verdana color=ff0000 size=2>Ваш уровень не разрешает стать Учеником!</font>';
		}
		else
		{
			$check = myquery("SELECT gt.* FROM game_tutorship gt WHERE gt.id = '".$_GET['settutor']."' AND gt.pupil_id = ".$user_id." AND gt.confirmed = 0");	 		
			if (mysql_num_rows($check) == 0)
			{
				echo '<font face=verdana color=ff0000 size=2>Что-то введено неверно!</font>';
			}
			else
			{
				$tutor = mysql_fetch_array ($check);
				$tutor_name = get_user ('name', $tutor['user_id']);
				if (!isset($_GET['yes']))
				{
					echo 'Вы действительно хотите, чтобы <b>'.$tutor_name.'</b> стал Вашим Наставником??';
					echo '<br><a href="town.php?option='.$option.'&settutor='.$_GET['settutor'].'&yes">Да, я готов стать Учеником</a>';
					echo '<br><a href="town.php?option='.$option.'">Нет, я ещё не готов принять решение</a>';			
				}
				else //Все проверки пройдены!
				{
					myquery("UPDATE game_tutorship SET confirmed = 1 WHERE id = '".$_GET['settutor']."' ");
					$theme = 'Гильдия Наставников';
					$post = 'Игрок <b>'.$char['name'].'</b> стал Вашим Учеником!';
					myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time, folder) VALUES ('".$tutor['user_id']."', '0', '".$theme."', '".$post."','0','".time()."',1)");	
					save_gp($tutor['user_id'], -$cost_newp, 111, 2);
					echo '<b>Вы стали Учеником игрока <u>'.$tutor_name.'</u></b>';
				}				
			}
		}
		echo '<br><br>';
	}
	elseif (isset($_GET['unsettutor']))//Отмена предложения о Наставничестве
	{
		$check = myquery("SELECT gt.* FROM game_tutorship gt WHERE gt.id = '".$_GET['unsettutor']."' AND (gt.pupil_id = ".$user_id." OR gt.user_id = ".$user_id.") AND gt.confirmed = 0");	 		
		if (mysql_num_rows($check) == 0)
		{
			echo '<font face=verdana color=ff0000 size=2>Что-то введено неверно!</font>';
		}
		else
		{
			$tutor = mysql_fetch_array ($check);
			$tutor_name = get_user ('name', $tutor['user_id']);
			$pupil_name = get_user ('name', $tutor['pupil_id']);
			if (!isset($_GET['yes']))
			{
				echo 'Вы действительно хотите, отменить заявку о Наставничестве для Ученика <b>'.$pupil_name.'</b> и Наставника <b>'.$tutor_name.'</b>??';
				echo '<br><a href="town.php?option='.$option.'&unsettutor='.$_GET['unsettutor'].'&yes">Да, отменить заявку</a>';
				echo '<br><a href="town.php?option='.$option.'">Нет, оставить заявку заявку</a>';			
			}
			else //Все проверки пройдены!
			{
				myquery("DELETE FROM game_tutorship WHERE id = '".$_GET['unsettutor']."' ");
				if ($user_id == $tutor['user_id'])
				{
					$theme = 'Гильдия Наставников';
					$post = 'Игрок <b>'.$pupil_name.'</b> не хочет быть Вашим Наставником!';
					$target = $tutor['pupil_id'];
				}
				else
				{
					$theme = 'Гильдия Наставников';					
					$post = 'Игрок <b>'.$pupil_name.'</b> не хочет быть Вашим Учеником!';
					$target = $tutor['user_id'];
				}
				myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time, folder) VALUES ('".$target."', '0', '".$theme."', '".$post."','0','".time()."',1)");					
				echo '<b>Вы успешно отменили заявку о Наставничестве для Ученика <u>'.$pupil_name.'</u> и Наставника <u>'.$tutor_name.'</u></b>';
			}				
		}
		echo '<br><br>';
	}
	elseif (isset($_GET['deltutor']))//Отказ от Наставника
	{
		$check = myquery("SELECT user_id FROM game_tutorship WHERE id = '".$_GET['deltutor']."' AND pupil_id = ".$user_id." ");
		if ($char['GP']<$cost_declinet)
		{
			echo '<font face=verdana color=ff0000 size=2>У Вас недостаточно денег для отказа от Наставника!</font>';
		}
		elseif (mysql_num_rows($check) == 0)
		{
			echo '<font face=verdana color=ff0000 size=2>Наставник не найден!</font>';
		}								
		elseif (!isset($_GET['yes']))
		{
			echo 'Вы действительно хотите отказаться от своего Наставника?';
			echo '<br><a href="town.php?option='.$option.'&deltutor='.$_GET['deltutor'].'&yes">Да, отказаться от Наставника</a>';
			echo '<br><a href="town.php?option='.$option.'">Нет, отменить операцию</a>';			
		}
		else //Все проверки пройдены!
		{
			myquery("DELETE FROM game_tutorship WHERE id = '".$_GET['settutor']."' ");
			list($target) = mysql_fetch_array($check);
			$theme = 'Гильдия Наставников';
			$post = 'Игрок <b>'.$char['name'].'</b> больше не является Вашим Учеником!';
			myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time, folder) VALUES ('".$target."', '0', '".$theme."', '".$post."','0','".time()."',1)");
			save_gp($user_id, -$cost_declinet, 111);
			echo '<b>Вы отказались от своего Наставника, заплатив '.$cost_declinet.' монет!</b>';
		}
		echo '<br><br>';
	}
	elseif (isset($_GET['delpupil']))//Отказ от Ученика
	{
		$check = myquery("SELECT pupil_id FROM game_tutorship WHERE id = '".$_GET['delpupil']."' AND user_id = ".$user_id." ");
		if ($char['GP']<$cost_declinep)
		{
			echo '<font face=verdana color=ff0000 size=2>У Вас недостаточно денег для отказа от Ученика!</font>';
		}
		elseif (mysql_num_rows($check) == 0)
		{
			echo '<font face=verdana color=ff0000 size=2>Ученик не найден!</font>';
		}								
		elseif (!isset($_GET['yes']))
		{
			echo 'Вы действительно хотите отказаться от своего Ученика?';
			echo '<br><a href="town.php?option='.$option.'&delpupil='.$_GET['delpupil'].'&yes">Да, отказаться от Ученика</a>';
			echo '<br><a href="town.php?option='.$option.'">Нет, отменить операцию</a>';			
		}
		else //Все проверки пройдены!
		{
			myquery("DELETE FROM game_tutorship WHERE id = '".$_GET['delpupil']."' ");
			list($target) = mysql_fetch_array($check);
			$theme = 'Гильдия Наставников';
			$post = 'Игрок <b>'.$char['name'].'</b> больше не является Вашим Наставником!';
			myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time, folder) VALUES ('".$target."', '0', '".$theme."', '".$post."','0','".time()."',1)");
			save_gp($user_id, -$cost_declinep, 111);
			echo '<b>Вы отказались от своего Ученика, заплатив '.$cost_declinep.' монет!</b>';
		}
		echo '<br><br>';
	}
	echo'<font face=verdana color=ff0000 size=2><b>Гильдия Наставников</b></font><br/><br/>
		 <b><font face=verdana color=white size=2>Приветствуем тебя! Гильдия Наставников преследует 2 цели:
		 <br>Во-первых, с помощью неё юные Искатели приключений могут выбрать себе достойного Наставника, чтобы перенять у него всё самое важное и необходимое, для того, чтобы стать истинным Героем Средиземья.
		 <br>Во-вторых, опытные Игроки могут найти себе Последователей для передачи им знаний и умений. Ведь успешность каждого Наставника определяется достижениями его Учеников!</font></b><br/><br/>';	
		 
	//Покажем правила Гильдии Наставников
	if (isset($_GET['rule']))
	{
		echo '<ol>
			  <li> Учеником может стать любой игрок до 15-ого уровня.</li>
			  <li> Наставником может стать любой игрок, прошедший хотя бы 1 реинкарнацию.</li>
			  <li> У одного Наставника может быть не более 3-ёх Учеников. Стоимость наставничества - '.$cost_newp.' монет.</li>
			  <li> Наставник получает 1 ЛР за каждую чётную реинкарнацию Учеников.</li>
			  <li> При достижении Учеником 1-ой реинкаранции он перестаёт быть Учеником, хотя его достижения влияют на бывшего Наставника.</li>
			  <li> Наставник может отказаться от Ученика. Стоимость отказа от Ученика - '.$cost_declinep.' монет.</li>				  
			  <li> Ученик может отказаться от Наставника. Стоимость отказа от Наставника - '.$cost_declinet.' монет.</li>				  
			  </ol>';
			  echo '<a href="town.php?option='.$option.'">Скрыть правила</a>';
	}
	else
	{
		echo '<a href="town.php?option='.$option.'&rule">Прочитать правила Гильдии Наставников</a>';
	}
	echo '<br/><br/>';
	$check_tutor = myquery("SELECT gt.*, IFNULL(gu.name, gua.name) as name FROM game_tutorship gt LEFT JOIN game_users gu ON gt.user_id = gu.user_id LEFT JOIN game_users_archive gua ON gt.user_id = gua.user_id WHERE gt.pupil_id = ".$user_id." ORDER BY action_time");	 		
	if (mysql_num_rows($check_tutor) > 0 )
	{
		while ($tutor = mysql_fetch_array($check_tutor) )
		{
			//У игрока есть Наставник
			if ($tutor['confirmed'] == 1 or $tutor['confirmed'] == 2)
			{
				echo 'Вашим Наставником является <b>'.$tutor['name'].'</b> (<a href="town.php?option='.$option.'&deltutor='.$tutor['id'].'">Отказаться от Наставника</a>)<br>';
			}
			//Игроку предлагают стать учеником
			else
			{
				echo 'Вашим Наставником хочет стать <b>'.$tutor['name'].'</b> (<a href="town.php?option='.$option.'&settutor='.$tutor['id'].'">Принять предложение</a>)&nbsp;
				(<a href="town.php?option='.$option.'&unsettutor='.$tutor['id'].'">Отклонить предложение</a>)<br>';
			}
		}
	}		
		
	$kol_wait = 0;
	$kol_current = 0;
	$kol_prev = 0;
	
	$check_pupil = myquery("SELECT gt.*, IFNULL(gu.name, gua.name) as name FROM game_tutorship gt LEFT JOIN game_users gu ON gt.pupil_id = gu.user_id LEFT JOIN game_users_archive gua ON gt.pupil_id = gua.user_id WHERE gt.user_id = ".$user_id." ORDER BY confirmed, action_time");	 			
	if (mysql_num_rows($check_pupil) > 0 )
	{			
		echo '<br><table border="1"><tr align="center">
			  <td width="350"><b>Ожидаемые ученики</b></td>
			  <td width="350"><b>Текущие ученики</b></td>
			  <td width="350"><b>Бывшие ученики</b></td>
			  </tr><tr>
			 ';
		while ($tutor = mysql_fetch_array($check_pupil) )
		{			
			//Поданные заявки игрока на Наставничество
			if ($tutor['confirmed'] == 0)
			{
				if ($kol_wait == 0) echo '<td>';
				$kol_wait++;
				echo $kol_wait.') <b>'.$tutor['name'].'</b> (<a href="town.php?option='.$option.'&unsettutor='.$tutor['id'].'">Отменить заявку</a>)<br>';
			}
			//Текущие Ученики игрока
			elseif ($tutor['confirmed'] == 1)
			{				
				if ($kol_current == 0) 
				{
					if ($kol_wait == 0) echo '<td>&nbsp;</td>';
					else '</td>';
					echo '<td>';
				}
				$kol_current++;
				echo $kol_current.') <b>'.$tutor['name'].'</b> (<a href="town.php?option='.$option.'&delpupil='.$tutor['id'].'">Отказаться от Ученика</a>)<br>';
			}
			//Бывшие Ученики игрока
			elseif ($tutor['confirmed'] == 2)
			{				
				if ($kol_prev == 0) 
				{
					if ($kol_current == 0) 
					{
						if ($kol_wait == 0) echo '<td>&nbsp;</td>';					
						echo '<td>&nbsp;</td>';					
					}
					else '</td>';
					echo '<td>';
				}
				$kol_prev++;
				echo $kol_prev.') <b>'.$tutor['name'].'</b><br>';
			}
		}			
		echo '</td>';
		if ($kol_prev == 0) 
		{
			if ($kol_current == 0) echo '<td>&nbsp;</td>';	
			echo '<td>&nbsp;</td>';	
		}
		echo '</tr></table>';
		echo '<br>';
	}	

	//Подача заявки на Наставничество
	if ($char['reinc'] > 0 and $kol_wait+$kol_current <= 3)
	{
		?>
		<script type="text/javascript" src="../js/jquery.js"></script>
		<script type="text/javascript" src="../suggest_new/jquery.autocomplete.js"></script>
		<link href="../suggest_new/suggest.css" rel="stylesheet" type="text/css">
		<script type="text/javascript">
		$(document).ready(function() {
			$('#in_name').autocomplete({
				serviceUrl: "../suggest_new/suggest.php?users",
				minChars: 3,
				matchSubset: 1,
				autoFill: true,			
				width: 150,
				id: '#in_id'
			});
		});
		</script>
		<?
		echo 'Введите имя игрока, которого хотели бы видеть своим Учеником:';	
		echo '<form name="input_form" id="input_form" action="town.php?option='.$option.'" method="POST" >	
			  <br><input id="in_name" name="in_name" type="text" size="20" value="" autocomplete="off">
			  <input id="in_id" name="in_id" type="hidden" size="20" value="0">
			  <input type="submit" name="addtutor" value="Подать заявку">
			  </form>';
	}
	echo '</center>';
	echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
}
if (function_exists("save_debug")) save_debug(); 

?>