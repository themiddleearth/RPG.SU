<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['statall'] >= 1)
{
	echo '<center>';
	if (isset($_GET['gambling']))
	{
		echo 'Находится в разработке!';
		echo '<br><br><a href=admin.php?opt=main&option=statall&gambling>Обновить</a>';
		echo '<br><a href=admin.php?opt=main&option=statall>Назад</a>';
	}
	elseif (isset($_GET['exchange']))
	{
		echo 'Находится в разработке!';
		echo '<br><br><a href=admin.php?opt=main&option=statall&exchange>Обновить</a>';
		echo '<br><a href=admin.php?opt=main&option=statall>Назад</a>';
	}
	elseif (isset($_GET['rune'])) //Статистика по рунным предметам
	{
		// Формирование исходных данных
		$kol_key = 0;
		$check_key = myquery("SELECT SUM(gi.count_item) as kol FROM game_items_factsheet gif JOIN game_items gi ON gif.id = gi.item_id 							   
							   WHERE gif.name like 'Ключ от сундука' HAVING kol is not null");
	    if (mysql_num_rows($check_key) > 0)
		{
			list($kol_key) = mysql_fetch_array($check_key);			
		}		
		
		$kol_rune = 0;
		$check_rune = myquery("SELECT gif.name, SUM(gi.count_item) as kol FROM game_items_factsheet gif JOIN game_items gi ON gif.id = gi.item_id 							   
							    WHERE gif.type = 22 GROUP BY gif.name");
	    if (mysql_num_rows($check_rune) > 0)
		{
			$i = 0;
			while (list($rname, $kol) = mysql_fetch_array($check_rune))
			{
				$i++;
				$kol_rune+=$kol;
				$mas_r[$i]['name'] = $rname;
				$mas_r[$i]['kol'] = $kol;				
			}
		}		
		
		$kol_items = 0;
		$check_items = myquery("SELECT IFNULL(gu.name, gua.name) as name, COUNT(*) as kol FROM quest_constructor qc JOIN game_items gi ON qc.item_id = gi.id
							    LEFT JOIN game_users gu ON gu.user_id = gi.user_id
							    LEFT JOIN game_users_archive gua ON gua.user_id = gi.user_id							   
							    GROUP BY gu.name, gua.name");
		if (mysql_num_rows($check_items) > 0)
		{
			$i = 0;
			while (list($iname, $kol) = mysql_fetch_array($check_items))
			{
				$i++;
				$kol_items+=$kol;
				$mas_i[$i]['name'] = $iname;
				$mas_i[$i]['kol'] = $kol;				
			}
		}							
		echo '<table border = "1">';
		echo '<tr><td width="300">Количество Рунных предметов в игре:</td><td width="100">'.$kol_items.'</td><td width="300">';
		$i = 1;
		while (isset($mas_i[$i]['name']))
		{
			echo $mas_i[$i]['name'].' - '.$mas_i[$i]['kol'].'<br>';
			$i++;
		}
		if ($i == 1) echo '&nbsp;';
		echo '</td></tr>';
		
		echo '<tr><td>Количество Рун в игре:</td><td>'.$kol_rune.'</td><td>';
		$i = 1;
		while (isset($mas_r[$i]['name']))
		{
			echo $mas_r[$i]['name'].' - '.$mas_r[$i]['kol'].'<br>';
			$i++;
		}
		if ($i == 1) echo '&nbsp;';
		echo '</td></tr>';
		
		echo '<tr><td>Количество Ключей Ключника в игре:</td><td>'.$kol_key.'</td><td>&nbsp;</td></tr>';		
		echo '</table>';
		echo '<br><br><a href=admin.php?opt=main&option=statall&rune>Обновить</a>';
		echo '<br><a href=admin.php?opt=main&option=statall>Назад</a>';
	}
	elseif (isset($_GET['moriares']))
	{
		$check = myquery("SELECT dq.quest_level, cr.name, sum(dqr.col) as col FROM dungeon_quests dq JOIN dungeon_quests_res dqr ON dq.id = dqr.quest_id JOIN craft_resource cr ON dqr.res_id = cr.id 
						  GROUP BY dq.quest_level, cr.name ORDER BY dq.quest_level, name");
		if (mysql_num_rows($check)>0)
		{
			$level = 0;
			while ($res = mysql_fetch_array($check))
			{
				if ($level == 0)
				{
					echo '<b>Подземелья Мории. Уровень '.$res['quest_level'].'</b>';
					echo '<table border="1"><tr align="center">
						  <td><b>Ресурс</b></td>
						  <td><b>Количество</b></td>
						  </tr>
					     ';
					$level = $res['quest_level'];
				}
				elseif ($level <> $res['quest_level'])
				{
					echo '</table><br><br><b>Подземелья Мории. Уровень '.$res['quest_level'].'</b>';
					echo '<table border="1"><tr align="center">
						  <td><b>Ресурс</b></td>
						  <td><b>Количество</b></td>
						  </tr>
					     ';
					$level = $res['quest_level'];
				}
				echo '<tr>';
				echo '<td>'.$res['name'].'</td>';
				echo '<td>'.$res['col'].'</td>';
				echo '</tr>';
			}
			echo '</table>';
		}
		else
		{
			echo 'Заданий в Мории не найдено!';
		}
		echo '<br><br><a href=admin.php?opt=main&option=statall&moriares>Обновить</a>';
		echo '<br><a href=admin.php?opt=main&option=statall>Назад</a>';
	}
	else
	{
		echo '<h2>Меню</h2>';
		echo '<ol>';
		echo '<li><a href=admin.php?opt=main&option=statall&gambling>Статистика по Шатру Азарта</a></li>';				
		echo '<li><a href=admin.php?opt=main&option=statall&exchange>Статистика по Обменнику</a></li>';				
		echo '<li><a href=admin.php?opt=main&option=statall&rune>Статистика по Рунным предметам</a></li>';						
		echo '<li><a href=admin.php?opt=main&option=statall&moriares>Статистика по ресурсам в подземельях Мории</a></li>';						
		echo '</ol>';
	}
	echo '</center>';
}

if (function_exists("save_debug")) save_debug(); 

?>