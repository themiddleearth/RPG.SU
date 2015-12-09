<?php
if (function_exists("start_debug")) start_debug(); 

if ($char['clan_id']==1 or $char['name']=='mrHawk')
{
	echo '<center>';
	$link='admin.php?opt=main&option=cron_st';
    echo '<h2><font color="yellow">Статистика кронов</font></h2>';
	//Выведем список кронов на экран
	function list_tab()
	{
		$link='admin.php?opt=main&option=cron_st';
		$result = myquery('SELECT gcl.* FROM ( SELECT cron, max(timecron) as timecron FROM game_cron_log GROUP BY cron) v1 
		JOIN game_cron_log gcl ON v1.cron = gcl.cron AND v1.timecron = gcl.timecron ORDER BY cron');
		echo('<table border="1">');
		echo '<tr align="center" style="font-weight: bold;"><td width="50">№</td>
			<td width="100">Крон</td><td width="100">Статус</td><td width="100">Выполнено</td>
			<td width="100">Статистика</td></tr>';
		$i=0;
		while ($result_row = mysql_fetch_array($result, MYSQL_ASSOC))
		{
			$i++;
			echo '<tr align="center">';
			echo '<td>'.$i.'</td>';
			echo '<td>'.$result_row['cron'].'</td>';
			echo '<td>'.$result_row['step'].'</td>';
			echo '<td>'.date("d.m.Y G:i:s", $result_row['timecron']).'</td>';
			echo '<td><a href="'.$link.'&show='.$result_row['cron'].'">Просмотр</a></td>';
			echo '</tr>';
		}		
		echo('</table>');	
		echo '<br><a href="'.$link.'">Обновить</a>';
	}	
	
	//Смотрим статистику
	if (isset($_GET['show']))
	{
		echo '<a href="'.$link.'">Меню</a><br><br>';
		$query = 'SELECT * FROM game_cron_log WHERE cron="'.$_GET['show'].'" ORDER BY timecron DESC';
		$query1 = 'SELECT COUNT(*) FROM game_cron_log WHERE cron="'.$_GET['show'].'" ORDER BY timecron DESC';
		if (!isset($page)) $page=1;
		$page=(int)$page;
		$line=25;
		$result = myquery($query1);
		$allpage=ceil(@mysql_result($result,0,0)/$line);
		if ($page>$allpage) $page=$allpage;
		if ($page<1) $page=1;
		$query.=" limit ".(($page-1)*$line).", $line";
		$res = myquery($query);
		if (mysql_num_rows($res)>0)
		{
			echo 'Крон: <b>'.$_GET['show'].'</b><br><br>';
			echo('<table border="1">');
			echo '<tr align="center" style="font-weight: bold;"><td width="150">Время</td><td width="300">Действие</td></tr>';
			while ($res_row = mysql_fetch_array($res, MYSQL_ASSOC))
			{
				echo '<tr align="center">';
				echo '<td>'.date("d.m.Y G:i:s", $res_row['timecron']).'</td>';
				echo '<td>'.$res_row['step'].'</td>';
				echo '</tr>';
			}
			echo('</table>');
			echo '<br><a href="'.$link.'&show='.$_GET['show'].'">Обновить</a><br><br>';	
			$href="admin.php?opt=main&option=cron_st&show=".$_GET['show'];
			echo'<center>Страница: ';
			show_page($page,$allpage,$href);
		}
	}
	else
	{
		list_tab();
	}
	echo '</center>';

}
if (function_exists("save_debug")) save_debug(); 
?>
