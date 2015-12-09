<?

if (function_exists("start_debug")) start_debug(); 

if($char['name']=='mrHawk')
{
	echo '<center><h2><font color="yellow">Процедуры</font></h2>';
	if (isset($sumitem))
	{
		if (isset($count_item))
		{			
			list($id, $type)=mysql_fetch_array(myquery("SELECT id, type FROM game_items_factsheet WHERE name='".$name_items."'"));
			if ($type==13 OR $type==12 OR $type==21 OR $type==19 OR $type==22 OR $type==97) 
			{
				$check_items=myquery("SELECT count(1) as count, sum(count_item) as sum, user_id FROM game_items WHERE item_id='".$id."' and priznak='".$gde."' Group by user_id Having count>1");
				if (mysql_num_rows($check_items)==0)
				{
					echo 'Нет необходимости ничего складывать!<br>';
				}
				else
				{
					$i=0;
					while($item=mysql_fetch_array($check_items))
					{
						myquery("UPDATE game_items Set count_item='".$item['sum']."' WHERE item_id='".$id."' and priznak='".$gde."' and user_id='".$item['user_id']."' Limit 1");
						myquery("DELETE FROM game_items WHERE item_id='".$id."' and priznak='".$gde."' and user_id='".$item['user_id']."' and count_item<>'".$item['sum']."'");
						$i=$i+$item['count']-1;
					}
					echo 'Удалено '.$i.' '.pluralForm($i,'строчка','строчки','строчек').' в бд!';
				}
			}
			else
			{
				echo 'Предмет не подлежит складыванию!';
			}
		}
		else	
		{
			echo '
			<script type="text/javascript">
			var getFunctionsUrl = "suggest/suggest_items.php?keyword=";
			var startSearch = 3;
			</script><?
			<link href="suggest/suggest.css" rel="stylesheet" type="text/css">
		    <script type="text/javascript" src="suggest/suggest.js"></script>
			<form action="admin.php?opt=main&option=funct&sumitem&count_item" method="post">
			Имя предмета:<input id="keyword" name="name_items" type="text" size="50" onkeyup="handleKeyUp(event)"><div style="display:none;" id="scroll"><div id="suggest"></div></div>
			<br><SELECT name="gde">
			<option value="0">В инвентаре</option>
            <option value="1">На рынке</option>
            <option value="4">В хранилище</option></SELECT><br>
			<input name="submit" type="submit" value="Сложить предмет">
			</form></div><script>init();</script>';
		}
	}
	// Обнуление игроков
	elseif (isset($_GET['script']))
	{				
		$i=0;
		$check = myquery("SELECT u.user_id FROM (SELECT user_id FROM game_users WHERE clevel>5 UNION ALL SELECT user_id FROM game_users_archive WHERE clevel>5) u
		                  JOIN game_users_data gud ON u.user_id=gud.user_id WHERE gud.last_visit>1322611200");
		while (list($id) = mysql_fetch_array($check))
		{
			myquery("UPDATE game_users_map SET map_name=18, map_xpos=35, map_ypos=34 WHERE user_id = '".$id."'");	
			$i++;
		}		
		echo 'Обнулено '.$i.' игроков!';		
	}	
	elseif (isset($_GET['script2']))
	{		
		myquery("UPDATE game_users gu SET gu.lucky=(SELECT (CASE WHEN SUM(gif.dlucky) is not null THEN SUM(gif.dlucky) ELSE 0 END)
				 FROM game_items gi JOIN game_items_factsheet gif ON gi.item_id = gif.id
				 WHERE gu.user_id = gi.user_id and gi.used>0 and gi.priznak = 0 and gif.dlucky>0)
				");
				
		myquery("UPDATE game_users_archive gu SET gu.lucky=(SELECT (CASE WHEN SUM(gif.dlucky) is not null THEN SUM(gif.dlucky) ELSE 0 END)
				 FROM game_items gi JOIN game_items_factsheet gif ON gi.item_id = gif.id
				 WHERE gu.user_id = gi.user_id and gi.used>0 and gi.priznak = 0 and gif.dlucky>0)
				");
				
		myquery("UPDATE game_users gu SET lucky_max = lucky");
		myquery("UPDATE game_users_archive gu SET  lucky_max = lucky");
		
		myquery("DELETE FROM game_obelisk_users WHERE harka like 'LUCKY'");
		
		echo 'Удача пересчитана!';	
	}	
	
	echo '</center>';	
	echo '<ol>';
	echo '<li><a href=admin.php?opt=main&option=funct&sumitem>Складывание предметов</a></li>';						
	echo '<li><a href=admin.php?opt=main&option=funct&script>Обнуление</a></li>';	
	echo '<li><a href=admin.php?opt=main&option=funct&script2>Пересчёт удачи</a></li>';	
	echo '</ol>';
}

if (function_exists("save_debug")) save_debug(); 

?>