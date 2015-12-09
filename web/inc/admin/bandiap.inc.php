<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['ban'] >= 2)
{
  function number2ip_print($number)
  {
    $temp = $number;
    $ip0 = floor($temp/256/256/256);
    $temp-=($ip0*256*256*256);
    $ip1 = floor($temp/256/256);
    $temp-=($ip1*256*256);
    $ip2 = floor($temp/256);
    $temp-=($ip2*256);
    $ip3=$temp;

    return sprintf("%3d.%3d.%3d.%3d",$ip0, $ip1, $ip2, $ip3);
  }



	echo '<center>';
	if (isset($_GET['new']))
	{
		if (isset($_GET['add']))
		{
			if (isset($_POST['min_ip']) and isset($_POST['max_ip']) and isset($_POST['time']) and isset($_POST['reason']) and $_POST['time']>=0)
			{
				$min=ip2number($_POST['min_ip']);
				$max=ip2number($_POST['max_ip']);
				if ($min==0 or $max==0 or $min >= $max)
				{
					echo 'Ip введены неверно!';
				}
				else
				{
					if ($_POST['time']==0) 
						$ban=-1;
					else
						$ban=time()+$_POST['time']*60;

          $da = getdate();
					$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
						VALUES (
						 '".$char['name']."',
						 'Забанил диапазон: <b>".$_POST['min_ip']."-".$_POST['max_ip']."</b>',
						 '".time()."',
						 '".$da['mday']."',
						 '".$da['mon']."',
						 '".$da['year']."')")
							 or die(mysql_error());
					myquery("Insert Into game_ban (user_id,time,ip,adm,za,type) values (".$min.",".$ban.",".$max.",'".$char['name']."','".$_POST['reason']."','1')");
					echo 'Диапазон забанен!';
				}
			}
			else
				echo 'Исходные данные введены неверно!';

      echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=bandiap">';
		}
		else
		{
			echo '<b>Забанить диапазон</b><br><br>';
			echo '<form method="post" action="admin.php?opt=main&option=bandiap&new&add">';
			echo '<table cellspacing="5" cellpadding="0" border="0">';
			echo '<tr align="center"><td width="150">Нижняя граница:</td><td width="300"><input name="min_ip" type="text" size=20></td></tr>';
			echo '<tr align="center"><td>Верхняя граница:</td><td><input name="max_ip" type="text" size=20></td></tr>';
			echo '<tr align="center"><td>Время бана (минуты):</td><td><input name="time" type="text" value="0" size=20></td></tr>';
			echo '<tr align="center"><td>Комментарий:</td><td><textarea name="reason" cols="70" class="input" rows="8"></textarea></td></tr>';	
			echo '</table>';
			echo '<br><br><input name="" type="submit" value="Забанить">';			
			echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="" type="button" value="Назад" onClick="location.href=\'admin.php?opt=main&option=bandiap\'">';
			echo '</form>';
			echo '<br><br><i>При времени бана "0", диапазон банится навсегда!</i>';
		}
	}
	elseif (isset($_GET['del']))
	{
		list($min_ip, $max_ip) = mysql_fetch_array(myquery("Select user_id, ip From game_ban Where id = ".$_GET['del'].";"));
		$min=number2ip($min_ip);
		$max=number2ip($max_ip);
		$da = getdate();
		$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
				VALUES (
				 '".$char['name']."',
				 'Удалил бан диапазона: <b>".$min." - ".$max."</b>',
				 '".time()."',
				 '".$da['mday']."',
				 '".$da['mon']."',
				 '".$da['year']."')")
					 or die(mysql_error());
		myquery("DELETE FROM game_ban WHERE id=".$_GET['del'].";");
		echo 'Бан диапазона удалён!';
		echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=bandiap">';
	}
	else
	{
		echo "<a href=admin.php?opt=main&option=bandiap&new>Забанить диапозон</a></br/>";
		$check_bans=myquery("Select * From game_ban Where type=1 and (time>".time()." or time=-1) Order by id desc");
		if (mysql_num_rows($check_bans)>0)
		{
			echo '<br><br><b>Список забаненых диапозонов</b><br><br>';
			echo '<table border="1"><tr align="center">
			<td width="50">Админ</td>
			<td width="200">Диапозон</td>			
			<td width="120">Время окончания</td>
			<td width="250">Причина</td>
			<td width="70">Действие</td>
			</td>';
			while($ban=mysql_fetch_array($check_bans))
			{
				echo '<tr align="center">';
				echo '<td>'.$ban['adm'].'</td>';
				echo '<td><pre>'.number2ip_print($ban['user_id']).' - '.number2ip_print($ban['ip']).'</pre></td>';
				if ($ban['time']!=-1) echo '<td>'.date("H:i d.m.Y",$ban['time']).'</td>';
				else echo'<td><b>Навсегда</b></td>';
				echo '<td>'.$ban['za'].'</td>';
				echo '<td><input type="button"  style="width: 80px" onClick="location.href=\'admin.php?opt=main&option=bandiap&del='.$ban['id'].'\'" value="Удалить"></td>';
				echo '</tr>';
			}
			echo '</table><br>';
		}
	}
	echo '</center>';
}

if (function_exists("save_debug")) save_debug(); 

?>