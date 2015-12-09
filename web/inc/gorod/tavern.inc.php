<?
if (function_exists("start_debug")) start_debug(); 

if ($town!=0)
{	
	if (isset($town_id) AND $town_id!=$town)
	{                                                                                                                                         
		echo'Ты находишься в другом городе!<br><br><br>&nbsp;&nbsp;&nbsp;<input type="button" value="Выйти в город" onClick=location.href="town.php">&nbsp;&nbsp;&nbsp;';
	}
	$userban=myquery("SELECT * FROM game_ban WHERE user_id='$user_id' and type=2 and time>'".time()."'");

	$info=myquery("SELECT * FROM game_tavern WHERE town='$town' limit 1");
	$info=mysql_fetch_array($info);

	$vladelec = '';
	if ($info['vladel']>0)
	{
		$selname = myquery("(SELECT name FROM game_users WHERE user_id='".$info['vladel']."') UNION (SELECT name FROM game_users_archive WHERE user_id='".$info['vladel']."') ");
		if ($selname!=false AND mysql_num_rows($selname)>0)
		{
			list($vladelec) = mysql_fetch_array($selname);
		}
	}

	$hp_cost = 5;
	$mp_cost = 2;
	$stm_cost = 3;
	$mess_cost = 80;

	//проверим на квест (движок)
	$quest_user=myquery("SELECT * FROM quest_engine_users WHERE user_id='$user_id' AND quest_type=5 AND done=0 AND par1_value=".$char['map_name']." AND par2_value=".$town." AND par3_value=".$char['map_xpos']." AND par4_value=".$char['map_ypos']." ");
	if(mysql_num_rows($quest_user) OR isset($quest_answer))
	{
		$quest_user=mysql_fetch_array($quest_user);
		include("../quest/quest_engine_types/quest_engine_tavern.php");
	}

	// Изменение своей таверны
	if (($info['vladel']==$user_id or $char['clan_id']==1) and isset($_POST['update_tavern']))
	{
		$news=htmlspecialchars(mysql_escape_string($_POST['tavernnews']));
		if (isset($_POST['msg']))
		{
			$msg='1';
		}
		else
		{
			$msg='0';
		}	
		$hp = (int)$_POST['hp'];
		$mp = (int)$_POST['mp'];
		$stm = (int)$_POST['stm'];
		$gp = ($hp/1000*$hp_cost) + ($mp/1000*$mp_cost) + ($stm/1000*$stm_cost);
		if ($hp<0 or $mp<0 or $stm<0)
		{
			echo '<span style="color:lightgreen;"><center><b>Запасы не могут быть отрицательными!</b></center></span><br>';
		}
		elseif ($char['GP']>=$gp)
		{
			save_gp($user_id, -$gp, 54);
			myquery("UPDATE game_tavern SET hp_store=hp_store+'".$hp."', mp_store=mp_store+'".$mp."', stm_store=stm_store+'".$stm."', info='".$news."', msg='".$msg."' WHERE town='".$town."'");
			echo '<span style="color:lightgreen;"><center><b>Данные таверны обновлены!</b></center></span><br>';
			$info['hp_store']+=$hp;
			$info['mp_store']+=$mp;
			$info['stm_store']+=$stm;
			$info['info']=$_POST['tavernnews'];
			$info['msg']=$msg;		
		}
		else
		{
			echo '<span style="color:lightgreen;"><center><b>У Вас недостаточно денег для пополнения запасов!</b></center></span><br>';
		}
	}
	// Пополнение запасов другой таверны
	elseif (isset($_POST['store']) and (int)$_GET['tavern']>0)
	{
		$tav_id = (int)$_GET['tavern'];
		$check = myquery("SELECT * FROM game_tavern WHERE vladel = '".$user_id."' and town='".$tav_id."'");
		if (mysql_num_rows($check)>0)
		{
			$hp = (int)$_POST['hp'];
			$mp = (int)$_POST['mp'];
			$stm = (int)$_POST['stm'];
			$gp = ($hp/1000*$hp_cost) + ($mp/1000*$mp_cost) + ($stm/1000*$stm_cost) + $mess_cost;
			if ($hp<0 or $mp<0 or $stm<0)
			{
				echo '<span style="color:lightgreen;"><center><b>Запасы не могут быть отрицательными!</b></center></span><br>';
			}
			elseif ($char['GP']>=$gp)
			{
				save_gp($user_id, -$gp, 54);
				myquery("UPDATE game_tavern SET hp_store=hp_store+'".$hp."', mp_store=mp_store+'".$mp."', stm_store=stm_store+'".$stm."' WHERE town='".$tav_id."'");
				echo '<span style="color:lightgreen;"><center><b>Запасы Вашей таверны пополнены</b></center></span><br>';					
			}
			else
			{
				echo '<span style="color:lightgreen;"><center><b>У Вас недостаточно денег для пополнения запасов!</b></center></span><br>';
			}
		}
		else
		{
			echo '<span style="color:lightgreen;"><center><b>Вы не можете пополнить запас этой таверны!</b></center></span><br>';
		}
	}
	//Добавление/Редактирование еды
	elseif (($info['vladel']==$user_id or $char['clan_id']==1) and (isset($_POST['addeda']) OR isset($_POST['editeda'])) and $_POST['item'] <> '')
	{
		$item=htmlspecialchars(mysql_real_escape_string($_POST['item']));
		$hp = (int)$_POST['hp'];
		$mp = (int)$_POST['mp'];
		$st = (int)$_POST['st'];
		$gp = (double)$_POST['gp'];	
		if ($info['hp_store']<$hp) $error = 'Не хватает запасов HP на складе';
		elseif ($info['mp_store']<$mp) $error = 'Не хватает запасов MP на складе';
		elseif ($info['stm_store']<$st) $error = 'Не хватает запасов STM на складе';
		elseif ($hp<0) $error = 'Еда не может отравлять!';
		elseif ($mp<0) $error = 'Еда не может отравлять!';
		elseif ($st<0) $error = 'Еда не может отравлять!';		
		elseif ($gp>=2000) $error = 'Большая цена!';
		elseif ($gp<=0) $error = 'Слишком маленькая цена!';
		else
		{
			if (isset($_POST['addeda']))
			{
				$insert=myquery("INSERT INTO game_tavern_shop (town,item,hp,mp,stm,gp) VALUES ('$town','$item','$hp','$mp','$st','$gp')");
				echo '<span style="color:lightgreen;"><center><b>Новое блюдо добавлено!</b></center></span><br>';
			}
			elseif (isset($_POST['editeda']))
			{
				$update=myquery("UPDATE game_tavern_shop SET item='".$item."',hp='".$hp."',mp='".$mp."',stm='".$st."',gp='".$gp."' WHERE id=".$_GET['editeat']."");
				echo '<span style="color:lightgreen;"><center><b>Блюдо отредактировано!</b></center></span><br>';
			}
		}
		if (isset($error)) echo '<span style="color:lightgreen;"><center><b>'.$error.'</b></center></span><br>';
	}

	elseif (isset($_GET['deleat']) and ($info['vladel']==$user_id or $char['clan_id']==1))
	{
		$del = (int)$_GET['deleat'];
		$rights = 1;
		if ($char['clan_id']!=1)
		{
			$check=myquery("SELECT * FROM game_tavern_shop WHERE town = '".$town."' and id = '".$del."' ");
			$rights = mysql_num_rows($check);
		}
		if ($rights > 0)
		{
			$delete=myquery("DELETE FROM game_tavern_shop WHERE id='".$del."'");
		}
		echo '<span style="color:lightgreen;"><center><b>Блюдо удалено!</b></center></span><br>';
	}
	// Удаление сплетни
	elseif (isset($_GET['del_spl']) and ($info['vladel']==$user_id or $char['clan_id']==1))
	{
		$delete=myquery("DELETE FROM game_tavern_spletni WHERE id='".(int)$_GET['del_spl']."'");
		echo '<span style="color:lightgreen;"><center><b>Сплетня удалена!</b></center></span><br>';	
	}
	// Добавление сплетни
	elseif (isset($_POST['spletni']) and $_POST['spletni']!='')
	{
		$spletni=htmlspecialchars($_POST['spletni']);
		$spletni=replace_enter($_POST['spletni']);
		$result=myquery("INSERT INTO game_tavern_spletni (town, spletni, name) VALUES ('".$town."', '".$spletni."', '".$char['name']."')");
		echo '<span style="color:lightgreen;"><center><b>Сплетня добавлена!</b></center></span><br>';
	}
	// Игрок ест
	elseif (isset($_GET['eat']) and isset($_POST['eat']) and isset($_POST['food_kol']))
	{
		echo '<center>';
		$kol = (int)$_POST['food_kol'];
		$i = 0;
		if ($kol > 0)
		{
			// Сформируем массив с данными по блюдам таверны
			$select=myquery("SELECT * FROM game_tavern_shop WHERE town='".$town."' and gp>0  and hp<".$info['hp_store']." and hp<".$info['mp_store']." and stm<".$info['stm_store']." ");			
			while ($row=mysql_fetch_array($select))
			{				
				$mas_f[$row['id']]['hp'] = $row['hp'];
				$mas_f[$row['id']]['mp'] = $row['mp'];
				$mas_f[$row['id']]['stm'] = $row['stm'];
				$mas_f[$row['id']]['gp'] = $row['gp'];
				if (mysql_num_rows($userban))
				{
					$row['gp']=$row['gp']*5;			
				}
			}
			
			$gp = 0; $hp = 0; $mp = 0; $stm = 0;
			// Запустим цикл по каждому представленному блюду
			for ($i=0; $i<$kol; $i++)
			{ 
				$id='id'.$i;
				$col='col'.$i;
				$tdhp='tdhp'.$i;
				$tdmp='tdmp'.$i;
				$tdstm='tdstm'.$i;
				$tdgp='tdgp'.$i;
				if ($_POST[$col]>0)
				{
					if ($mas_f[$_POST[$id]]['gp']*$_POST[$col] <= $char['GP'] AND $mas_f[$_POST[$id]]['hp']*$_POST[$col] <= $info['hp_store'] 
					AND $mas_f[$_POST[$id]]['mp']*$_POST[$col] <= $info['mp_store'] AND $mas_f[$_POST[$id]]['stm']*$_POST[$col] <= $info['stm_store'])
					{
						$gp += $mas_f[$_POST[$id]]['gp']*$_POST[$col];
						$hp += $mas_f[$_POST[$id]]['hp']*$_POST[$col];
						$mp += $mas_f[$_POST[$id]]['mp']*$_POST[$col];
						$stm += $mas_f[$_POST[$id]]['stm']*$_POST[$col];
						$upd=myquery("UPDATE game_tavern_shop SET kol=kol+'".$_POST[$col]."' WHERE id='".$_POST[$id]."'");
					}
				}
			}
			// Если игрок что-то съел, то обновим его данные
			if ($gp > 0)
			{
				$char['HP']=min($char['HP']+$hp, $char['HP_MAX']);
				$char['MP']=min($char['MP']+$mp, $char['MP_MAX']);
				$char['STM']=min($char['STM']+$stm, $char['STM_MAX']);
				$char['PR']=min($char['PR']+$stm, $char['PR_MAX']);				
				$char['GP']-=$gp;
				$info['hp_store']-=$hp;
				$info['mp_store']-=$mp;
				$info['stm_store']-=$stm;
				$upd=myquery("UPDATE game_users SET HP='".$char['HP']."',MP='".$char['MP']."',STM='".$char['STM']."',PR='".$char['PR']."',GP='".$char['GP']."',CW=CW-'".($gp*money_weight)."' WHERE user_id='$user_id'");
				setGP($user_id,-$gp,55);				
				
				// Рассчитаем доход владельца таверны
				$vurychka = $gp;
				$sebestoimost = ($hp/1000*$hp_cost) + ($mp/1000*$mp_cost) + ($stm/1000*$stm_cost);
				$dohod = $vurychka-$sebestoimost;
				$np_pribyl = 0;
				if ($dohod>0)
				{
					$np_pribyl = round($dohod*0.24,6);
				}
				$dohod_vladelec = round($vurychka-$np_pribyl,2);
				$dohod_taverna = round($dohod-$np_pribyl,2);
				if ($user_id==$info['vladel']) 
				{
					$upd=myquery("UPDATE game_tavern SET dohod=dohod+(CASE WHEN ".$dohod_taverna."<0 THEN '".$dohod_taverna."' ELSE 0 END),hp_store=hp_store-'".$hp."',mp_store=mp_store-'".$mp."',stm_store=stm_store-'".$stm."' WHERE town='".$town."'");
					$upd=myquery("UPDATE game_users set GP=GP+'$dohod_vladelec',CW=CW+'".($dohod_vladelec*money_weight)."' WHERE user_id='".$info['vladel']."'");
					$upd=myquery("UPDATE game_users_archive SET GP=GP+'$dohod_vladelec',CW=CW+'".($dohod_vladelec*money_weight)."' WHERE user_id='".$info['vladel']."'");
					setGP($info['vladel'],$dohod_vladelec,56);	
					$info['dohod'] += min($dohod_taverna, 0);
				}
				else
				{
					$upd=myquery("UPDATE game_tavern set dohod=dohod+'".$dohod."',hp_store=hp_store-'".$hp."',mp_store=mp_store-'".$mp."',stm_store=stm_store-'".$stm."' WHERE town='".$town."'");
					$upd=myquery("UPDATE game_users set GP=GP+'".($gp-$np_pribyl)."',CW=CW+'".(($gp-$np_pribyl)*money_weight)."' WHERE user_id='".$info['vladel']."'");
					$upd=myquery("UPDATE game_users_archive set GP=GP+'".($gp-$np_pribyl)."',CW=CW+'".(($gp-$np_pribyl)*money_weight)."' WHERE user_id='".$info['vladel']."'");
					setGP($info['vladel'],($gp-$np_pribyl),56);	
					$info['dohod'] += $dohod;				
				}				
				echo '<span style="color:lightgreen;"><center><b>Вы вкусно пообедали!</b></center></span><br>';
			}
			else
			{
				echo '<span style="color:lightgreen;"><center><b>Что-то пошло не так, и Ваш желудок стался пуст!</b></center></span><br>';			
			}
		}
		echo '</center>';		
	}	

	$check = myquery("SELECT gt.*, gg.rustown FROM game_tavern gt JOIN game_gorod gg ON gt.town = gg.town WHERE gt.vladel = '".$user_id."' and gt.town<>'".$town."' ");
	if (isset($_GET['moder']) and ($char['clan_id'] == 1 or $info['vladel'] == $user_id or mysql_num_rows($check) > 0))
	{
		echo'<center><br><table border="1" bgcolor="223344" width="100%"><tr align="center"><td width="100%"></td></tr>';
		// Владелец текущей таверны
		if ($char['clan_id'] == 1 or $info['vladel'] == $user_id)
		{		
			echo '<tr align="center"><td><font color="white"><b>Запасы на складе:</b><br> HP-<b>'.$info['hp_store'].'</b> ед., MP-<b>'.$info['mp_store'].'</b> ед., STM-<b>'.$info['stm_store'].'</b> ед.</font></td></tr>';
			echo '<tr align="center"><td>';
			
			// Форма для добавления/редактирования блюда		
			echo '<form action="town.php?option='.$option.'&moder" method="post">
			<b>Добавление новой еды:</b><br>
			Название <input type="text" name="item" size="50" maxsize="50"><br>
			HP: <input type="text" name="hp" size="5" maxsize="5"> 
			MP: <input type="text" name="mp" size="5" maxsize="5"> 
			STM: <input type="text" name="st" size="5" maxsize="5">
			GP: <input type="text" name="gp" size="5" maxsize="5">
			<br><input type="submit" name="addeda" value="Добавить"></form>';
			echo '</td></tr><tr align="center"><td>';
			
			//Редактирование параметров таверны
			if($info['msg']==1) $checked="checked";
			else $checked="";
			echo '<b>Редактирование параметров таверны:</b>';
			echo '<form action="town.php?option='.$option.'&moder" method="post">
			<table border="1">
			<tr><td>Редактирование главной новости:</td><td><textarea name="tavernnews" cols="45" class="input" rows="5">'.$info['info'].'</textarea></td></tr>
			<tr><td>Пополнить запасы на складе:
				<br><i>(Стоимость 1000 ед. HP - '.$hp_cost.' монет)</i>
				<br><i>(Стоимость 1000 ед. MP - '.$mp_cost.' монеты)</i>
				<br><i>(Стоимость 1000 ед. STM - '.$stm_cost.' монеты)</i>
			</td>
			<td><input type="text" name="hp" size="7" maxsize="7" value=0> - HP<br> 
				<input type="text" name="mp" size="7" maxsize="7" value=0> - MP<br> 
				<input type="text" name="stm" size="7" maxsize="7" value=0> - STM 
			</td></tr>
			<tr><td>Включить отправку уведомлений:</td><td><input name="msg" type="checkbox" '.$checked.'></td></tr>
			</table>
			<input type="submit" name="update_tavern" value="Обновить данные"></form>';	
			echo '</td></tr><tr align="center"><td>';
			
			// Редактирование меню
			echo '<b>Редактирование меню:</b>';
			$select=myquery("SELECT gts.* FROM game_tavern_shop gts WHERE gts.town='".$town."' ORDER BY gp DESC");
			if (mysql_num_rows($select) > 0)
			{
				$i=0;
				echo '<table border="1"><tr align="center">';				
				echo '<td width="250"><b>Блюдо</b></td>';					
				echo '<td width="100"><b>Жизнь</b></td>';		
				echo '<td width="100"><b>Мана</b></td>';		
				echo '<td width="100"><b>Энергия</b></td>';		
				echo '<td width="100"><b>Стоимость</b></td>';
				echo '<td width="80"><b>Количество заказов</b></td>';
				echo '<td><b>Действие 1</b></td>';		
				echo '<td><b>Действие 2</b></td>';		
				echo '</tr>';
				while ($row=mysql_fetch_array($select))
				{
					echo '<form action="town.php?option='.$option.'&editeat='.$row['id'].'&moder" method="post">';
					echo '<tr align="center">';
					echo '<td><input type="text" name="item" size="50" maxsize="50" value="'.$row['item'].'"</td>';			
					echo '<td><input type="text" name="hp" size="5" maxsize="5" value="'.$row['hp'].'"</td>';				
					echo '<td><input type="text" name="mp" size="5" maxsize="5" value="'.$row['mp'].'"</td>';				
					echo '<td><input type="text" name="st" size="5" maxsize="5" value="'.$row['stm'].'"</td>';				
					echo '<td><input type="text" name="gp" size="5" maxsize="5" value="'.$row['gp'].'"</td>';				
					echo '<td>'.$row['kol'].'</td>';				
					echo '<td><input type="submit" name="editeda" value="Сохранить"</td>';				
					echo '</form>';
					echo '<td><a href="town.php?option='.$option.'&deleat='.$row['id'].'&moder">Удалить</a></td>';			
					echo '</tr>';
					
				}
				echo '</table>';
			}			
		}
		
		// Владелец других таверн
		if (mysql_num_rows($check) > 0)
		{
			echo '<tr align="center"><td>';
			echo'<br><center><font size=2 color=#FF6600>Ты владеешь другой таверной. Ты можешь попросить твоего коллегу - владельца этой таверны <font color=#FF0000><b>'.$vladelec.'</b></font>
			- попросить отправить гонца в cвою таверну для пополнения запасов. За эти услуги ты '.echo_sex('должен','должна').' будешь заплатить <b>'.$mess_cost.'</b> монет.<br><br>';		
			while($tav = mysql_fetch_array($check))
			{
				echo '<br><font color=#FF00FF size=2>Таверна в городе: '.$tav['rustown'].'<font><br><font size=1 color=#80FF80><b>Запасы на складе:</b>
				HP = '.$tav['hp_store'].', MP = '.$tav['mp_store'].', STM = '.$tav['stm_store'].'';
				echo'
				<form autocomplete="off" method="POST" action="town.php?option='.$option.'&tavern='.$tav['town'].'&moder">
				<table border="0" width="60%" bgcolor="000000" cellpadding="0" cellspacing="1">
				<tr bgcolor=001122>
				<td valign="middle" align="left">Пополнить запасы HP на </td><td><input type="text" name="hp" size="7" maxsize="7" value="0"></td><td> единиц (стоимость за 1000 единиц = '.$hp_cost.' монет)</td></tr><tr bgcolor=001122>
				<td valign="middle" align="left">Пополнить запасы MP на </td><td><input type="text" name="mp" size="7" maxsize="7" value="0"></td><td> единиц (стоимость за 1000 единиц = '.$mp_cost.' монет)</td></tr><tr bgcolor=001122>
				<td valign="middle" align="left">Пополнить запасы STM на </td><td><input type="text" name="stm" size="7" maxsize="7" value="0"></td><td> единиц (стоимость за 1000 единиц = '.$stm_cost.' монет)</td></tr><tr><tr></tr>
				</table>
				<input name="store" type="submit" value="Пополнить запасы моей таверны">
				</form><br>';
			}		
		}
		
		echo '</td></tr><tr align="center"><td><a href="town.php?option='.$option.'">Выход</a></td></tr>';
		echo '</table>';
	}
	//Главное меню
	else
	{
		if ($char['clan_id'] == 1 or $info['vladel'] == $user_id or mysql_num_rows($check) > 0)
		{
			echo '<br><a href="town.php?option='.$option.'&moder">Управление тавернами</a><br><br>';
		}	
		echo'<center><font size=2 face=verdana color=000000><b><span style="color:white;">ВАШИ: Здоровье = '.$char['HP'].'/'.$char['HP_MAX'].' :: Мана = '.$char['MP'].'/'.$char['MP_MAX'].' :: Энергия = '.$char['STM'].'/'.$char['STM_MAX'].' :: Прана = '.$char['PR'].'/'.$char['PR_MAX'].'</span>';
		echo'<table border="0" width="98%" bgcolor="000000" cellpadding="0" cellspacing="1">
		<tr><td><table border="0" width="100%" bgcolor="223344" cellpadding="0" cellspacing="1">
		<tr bgcolor=001122><td colspan="2" valign="top" align=center>';

		echo(nl2br($info['info']));
		echo'<br>Владелец: <font color=ff0000><b>'.$vladelec.'<br>';
		echo'Общий доход составляет: '.$info['dohod'].' золотых';
		echo'</td></tr>';

		?>
		<script language="JavaScript" type="text/javascript">	
		function change_kol(n, val)
		{		
			var el = Number(document.getElementById("id"+n).innerHTML);						
			if (el+val>=0)
			{			
				document.getElementById("id"+n).innerHTML = el+val;				
				document.getElementById("col"+n).value = el+val;				
				var hp = Number(document.getElementById("tdhp"+n).innerHTML);
				var mp = Number(document.getElementById("tdmp"+n).innerHTML);
				var stm = Number(document.getElementById("tdstm"+n).innerHTML);
				var gp = Number(document.getElementById("tdgp"+n).innerHTML);
				document.getElementById("hp_all").value = Math.round((Number(document.getElementById("hp_all").value) + hp * val)*100)/100;
				document.getElementById("mp_all").value = Math.round((Number(document.getElementById("mp_all").value) + mp * val)*100)/100;
				document.getElementById("stm_all").value = Math.round((Number(document.getElementById("stm_all").value) + stm * val)*100)/100;
				document.getElementById("gp_all").value = Math.round((Number(document.getElementById("gp_all").value) + gp * val)*100)/100;
			}
		}				
		</script>
		<?
		
		echo'<tr><form name="menu" action="town.php?option='.$option.'&eat" method="post"><td colspan="2" align="center">';
		$select=myquery("SELECT * FROM game_tavern_shop WHERE town='".$town."' and gp>0  and hp<".$info['hp_store']." and hp<".$info['mp_store']." and stm<".$info['stm_store']." ORDER BY gp DESC");
		if (mysql_num_rows($select) > 0)
		{
			$i=0;
			echo '<table id="menu" border="1"><tr align="center">';				
			echo '<td width="250"><b>Блюдо</b></td>';		
			echo '<td width="150"><b>Заказ</b></td>';		
			echo '<td width="100"><b>Жизнь</b></td>';		
			echo '<td width="100"><b>Мана</b></td>';		
			echo '<td width="100"><b>Энергия</b></td>';		
			echo '<td width="100"><b>Стоимость</b></td>';					
			echo '</tr>';
			while ($row=mysql_fetch_array($select))
			{
				$id='id'.$i;
				$col='col'.$i;
				$tdhp='tdhp'.$i;
				$tdmp='tdmp'.$i;
				$tdstm='tdstm'.$i;
				$tdgp='tdgp'.$i;
				echo '<tr>';			
				echo '<td><b><font color="lightgreen">'.stripslashes($row['item']).'</font></b></td>';			
				echo '<td align="center" style="font-weight: bold;"><input type="button" value="-" onclick="change_kol('.$i.', -1)">&nbsp;&nbsp;&nbsp;<b id="'.$id.'">0</b>&nbsp;&nbsp;&nbsp;<input type="button" value="+" onclick="change_kol('.$i.', 1)"><input type="hidden" name="'.$id.'" value="'.$row['id'].'"><input type="hidden" id="'.$col.'" name="'.$col.'" value="0"></td>';
				if ($row['hp']>0) $color = "font-weight: bold; color: red;"; else $color="";
				echo '<td id="'.$tdhp.'" align="center" style="'.$color.'">'.$row['hp'].'</td>';
				if ($row['mp']>0) $color = "font-weight: bold; color: 5998d9;"; else $color="";
				echo '<td id="'.$tdmp.'" align="center" style="'.$color.'">'.$row['mp'].'</td>';
				if ($row['stm']>0) $color = "font-weight: bold; color: yellow;"; else $color="";
				echo '<td id="'.$tdstm.'" align="center" style="'.$color.'">'.$row['stm'].'</td>';			
				if (mysql_num_rows($userban))
				{
					$row['gp']=$row['gp']*5;			
				}
				echo '<td id="'.$tdgp.'" align="center" style="font-weight: bold;">'.$row['gp'].'</td>';			
				echo '</tr>';
				$i++;
			}
			echo '<tr>';
			echo '<td align="center"><b>Итого заказ:</b></td><td>&nbsp;</td>';
			echo '<td align="center"><input type="text" size="6" value="0" id="hp_all" readonly></td>';
			echo '<td align="center"><input type="text" size="6" value="0" id="mp_all" readonly></td>';
			echo '<td align="center"><input type="text" size="6" value="0" id="stm_all" readonly></td>';
			echo '<td align="center"><input type="text" size="6" value="0" id="gp_all" readonly></td>';
			echo '</tr></table>';		
			echo '<input type="hidden" name="food_kol" value="'.$i.'">';
			echo '<input name="eat" type="submit" value="Съесть" style="COLOR: #СССССС; FONT-SIZE: 9pt; FONT-FAMILY: Verdana; BACKGROUND-COLOR: #000000"></td></form></tr>';		
		}
		else
		{
			echo '<br><br><center><b>Таверна пуста!<b></center><br><br>';
		}
		echo'</tr></table>';	
		
		echo'<center><br><table border="0" bgcolor="223344" width="100%"><tr align="center"><td width="70%">
			 <table border="1" width="98%">';	
		// Сплетни
		$splet=myquery("SELECT * FROM game_tavern_spletni WHERE town='".$town."' order by id DESC LIMIT 10");
		while($row=mysql_fetch_array($splet))
		{
			echo'<tr><td align="center">'.stripslashes($row["spletni"]);
			if ($info['vladel']==$user_id or $char['clan_id']==1) echo '&nbsp;&nbsp;&nbsp;<input type="button" onClick=location.replace("?town_id='.$town.'&option='.$option.'&del_spl='.$row['id'].'") value="Удалить">';		
			echo '<br><b><font color=ff0000>'.$row["name"].'</font></b>';
			if ($row["time"] != '0000-00-00 00:00:00')
			{
				echo '('.$row['time'].')';
			}
			echo '</td></tr>';
		};
		echo'</table></td><td align="center" valign=center>';
		echo'<form action="" method="post">
		<textarea name="spletni" cols="20" class="input" rows="15"></textarea><br>
		<input name="town_id" type="hidden" value="'.$town.'">
		<input name="add_spletni" type="submit" value="Оставить сплетню" style="COLOR: #СССССС; FONT-SIZE: 9pt; FONT-FAMILY: Verdana; BACKGROUND-COLOR: #000000">&nbsp;&nbsp;&nbsp;</form></td></tr></table></table>';
		echo '</td></tr></table></table></center>';
	}		
}

if (function_exists("save_debug")) save_debug(); 

?>