<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['del'] >= 1)
{
	echo '<center>';
	if (isset($delpsg))
	{
		if (isset($_POST['yesdelpsg']))
		{
			list($name)=mysql_fetch_array(myquery("SELECT name FROM game_users WHERE user_id='".$delpsg."' UNION SELECT name FROM game_users_archive WHERE user_id='".$delpsg."'"));
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
					VALUES (
					 '".$char['name']."',
					 'Удалил ПСЖ заявку: персонажа: <b>".$name."</b>',
					 '".time()."',
					 '".$da['mday']."',
					 '".$da['mon']."',
					 '".$da['year']."')")
						 or die(mysql_error());
			myquery("DELETE FROM game_users_psg Where user_id='".$delpsg."'");
			myquery("INSERT INTO game_pm (komu, otkogo, theme, post, time) VALUES ('".$delpsg."', '".$char['user_id']."', 'Удаление заявки ПСЖ', 'Админ ".$char['name']." удалил Вашу ПСЖ заявку', '".time()."')");
			$topic_check=myquery("SELECT id FROM forum_topics WHERE top like 'ПСЖ (удалить По Собственному Желанию)'");
			while (list($topic_id)=mysql_fetch_array($topic_check))
			{
				myquery("DELETE FROM forum_otv WHERE user_id='".$delpsg."' AND text like 'ПСЖ' AND topics_id='".$topic_id."' ");
				myquery("UPDATE forum_topics SET last_date=".time().", last_user='".$char['user_id']."', otv=otv-1 WHERE id='".$topic_id."'");
			}
			echo 'Заявка ПСЖ удалена!<br><br>';
			echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=users_psg">';
		}
		elseif (isset($_POST['nodelpsg']))
		{
			echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=users_psg">';
		}
		else
		{	
			echo ('<b> Вы действительно хотите удалить заявку ПСЖ? 
							<form method="Post">
							<table><tr>
							<td width="60px"><input type="submit" name="yesdelpsg" value="Да, удалить" style="width: 125px"></input></td>
							<td width="60px"><input type="submit" name="nodelpsg" value="Нет, не удалять" style="width: 125px"></input></td>
				  </tr></table></b><br><br>');
		}
	}
	
	$check_psg=myquery("SELECT up.user_id, (CASE WHEN u.name is null THEN ua.name ELSE u.name END)  AS name, up.banned_date
						FROM game_users_psg AS up
						LEFT JOIN game_users AS u ON up.user_id = u.user_id
						LEFT JOIN game_users_archive AS ua ON up.user_id = ua.user_id");
		if (mysql_num_rows($check_psg)>0)
		{
			echo '<h2>Таблица персонажей в ПСЖ</h2>';
			echo '<table border="2" bordercolor="gold" cellspacing="3" cellpadding="0" ><tr valign="top" align="center">
			 <td width="150"><b>Персонаж</b></td>
			 <td width="250"><b>Дата ПСЖ заявки</b></td>
			 <td width="250"><b>Дата удаления персонажа</b></td>
			 <td width="150"><b>Действие</b></td></tr>
			 ';
			while ($psg=mysql_fetch_array($check_psg))
			{
				echo '<tr align="center">';
				echo '<td>'.$psg['name'].'</td>';
				echo '<td>'.date("H:i d.m.Y",($psg['banned_date'])).'</td>';
				echo '<td>'.date("H:i d.m.Y",($psg['banned_date']+28*24*60*60)).'</td>';
				echo '<td><input type="button"  onClick="location.href=\'admin.php?opt=main&option=users_psg&delpsg='.$psg['user_id'].'\'" value="Удалить заявку"></td>';
				echo '</tr>';
			}
			echo '</table>';
		}
	echo '</center>';	
}

if (function_exists("save_debug")) save_debug(); 

?>