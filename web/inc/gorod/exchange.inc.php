<?
if (function_exists("start_debug")) start_debug(); 
 
$page_max = 0; //Определение того, нужно ли выводить пейджинг на экран
$page_href = 'town.php?option='.$option.'&';

if ($town!=0)
{
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
	
	echo '<center>
			 <font face=verdana color=ff0000 size=2><b>Обменный пункт</b></font><br/><br/>
			 <b><font face=verdana color=white size=2>Здравствуй, Путник!<br/>Здесь ты можешь совершить выгодные торговые операции, обменяв ненужные предметы и ресурсы на нечто полезное!</font><br/><br/><br/></b>';	
	
	//Функции для осуществления поиска предложений	
	?>
	<script type="text/javascript" src="../js/jquery.js"></script>
	<script type="text/javascript" src="../suggest_new/jquery.autocomplete.js"></script>
	<link href="../suggest_new/suggest.css" rel="stylesheet" type="text/css">
	<script type="text/javascript">
	$(document).ready(function() {
		$('#in_name').autocomplete({
			serviceUrl: "../suggest_new/suggest.php?itemres",
			minChars: 3,
			matchSubset: 1,
			autoFill: true,			
			width: 150,
			id: '#in_id',
			type_id: '#in_type'
		});
	});
	$(document).ready(function() {
		$('#out_name').autocomplete({
			serviceUrl: "../suggest_new/suggest.php?itemres",
			minChars: 3,
			matchSubset: 1,
			autoFill: true,			
			width: 150,
			id: '#out_id',
			type_id: '#out_type'
		});
	});
	</script>
	
	<?
	//Осуществление обмена
	if (isset($_GET['exchange']) and is_numeric($_GET['exchange']) and $_GET['exchange']>0)
	{
		if (isset($_GET['yes']))
		{
			$check=myquery("SELECT * FROM game_exchange WHERE id = '".$_GET['exchange']."' and enable = 1");
			if (mysql_num_rows($check)>0)
			{
				$result=mysql_fetch_array($check);
				$test = 1;
				if ($result['in_gp'] > 0 and $char['GP']<$result['in_gp'])
				{
					echo '<b>У Вас недостаточно денег для осуществления обмена!</b><br>';
					$test = 0;
				}
				elseif ($result['in_id'] > 0)
				{
					//Проверка: хватает ли игроку необходимых предметов для обмена
					$query_item = "SELECT gif.name, geg.kol*'".$result['in_kol']."' as kol FROM game_exchange_groups geg
					               LEFT JOIN (SELECT gi.item_id, sum(CASE WHEN git.counts = 1 THEN gi.count_item ELSE 1 END) as cc FROM game_items gi 
											  JOIN game_items_factsheet gif ON gi.item_id = gif.id
											  JOIN game_items_type git ON gif.type=git.id
								              WHERE gi.user_id = '".$char['user_id']."' and gi.used = 0 and gi.priznak = 0 
										      GROUP BY item_id ) i
								   ON geg.item_id = i.item_id
								   JOIN game_items_factsheet gif ON geg.item_id = gif.id
								   WHERE geg.group_id = '".$result['in_id']."' and geg.item_type = 0 and 
								        (i.item_id is null or geg.kol*'".$result['in_kol']."' > i.cc)
								   ";
					$check_items = myquery($query_item);
					if (mysql_num_rows($check_items)>0)
					{
						$test = 0;
						echo '<b>У Вас нет необходимых предметов для обмена:</b><br>';
						while ($items = mysql_fetch_array($check_items))
						{
							echo $items['name'].' - '.$items['kol'].' шт.<br>';
						}
						echo '<br>';
					}
					//Проверка: хватает ли игроку необходимых ресурсов  для обмена
					$query_res = "SELECT cr.name, geg.kol*'".$result['in_kol']."' as kol FROM game_exchange_groups geg
					               LEFT JOIN craft_resource_user cru ON geg.item_id = cru.res_id and cru.user_id = '".$char['user_id']."' 
								   JOIN craft_resource cr ON geg.item_id = cr.id
								   WHERE geg.group_id = '".$result['in_id']."' and geg.item_type = 1 and 
								        (cru.res_id is null or geg.kol*'".$result['in_kol']."' > cru.col)
								 ";
					$check_res = myquery($query_res);
					if (mysql_num_rows($check_res)>0)
					{
						$test = 0;
						echo '<b>У Вас нет необходимых ресурсов для обмена:</b><br>';
						while ($res = mysql_fetch_array($check_res))
						{
							echo $res['name'].' - '.$res['kol'].' шт.<br>';
						}
						echo '<br>';
					}
				}
				//Все проверки пройдены - можно производить обмен
				if ($test == 1)
				{
					//Забираем у игрока деньги
					if ($result['in_gp'] > 0)
					{
						save_gp($char['user_id'], -$result['in_gp'], 110, 1);
					}
					
					if ($result['in_id'] > 0)
					{
						//Забираем у игрока предметы
						$query_item = "SELECT gi.id, gi.item_id, git.counts, geg.kol*'".$result['in_kol']."' as kol_need,
						               (CASE WHEN git.counts = 1 THEN gi.count_item ELSE 1 END) as kol_have						               
						               FROM game_exchange_groups geg
									   JOIN game_items gi ON gi.item_id = geg.item_id
									   JOIN game_items_factsheet gif ON gi.item_id = gif.id
									   JOIN game_items_type git ON gif.type=git.id
									   WHERE geg.group_id = '".$result['in_id']."' and geg.item_type = 0 and 
									         gi.user_id = '".$char['user_id']."' and gi.used = 0 and gi.priznak = 0";

						$check_item = myquery($query_item);
						if (mysql_num_rows($check_item)>0)
						{
							$it = 0;							
							while ($item = mysql_fetch_array($check_item))
							{
								if ($it<>$item['item_id'])
								{
									$it = $item['item_id'];
									$it_kol = 0;
									$remove = max(1, $item['kol_need']*$item['counts']);
									$count = min($item['kol_have'], $item['kol_need']);
								}
								$it_kol = $it_kol + $count;
								if ($it_kol<=$item['kol_need'])
								{
									$Item = new Item($item['id']);
									$Item->admindelete(0,$remove);
								}
							}
						}
						
						//Забираем у игрока ресурсы
						$query_res = "SELECT sum(cr.weight*geg.kol*'".$result['in_kol']."') as kol 
						              FROM game_exchange_groups geg
									  JOIN craft_resource cr ON geg.item_id = cr.id
									  WHERE geg.group_id = '".$result['in_id']."' and geg.item_type = 1";
						
						list($weight) = mysql_fetch_array(myquery($query_res));
						if ($weight <> "")
						{
							myquery("UPDATE craft_resource_user cru JOIN game_exchange_groups geg ON cru.res_id = geg.item_id
							         SET cru.col = cru.col - geg.kol*'".$result['in_kol']."'
									 WHERE geg.group_id = '".$result['in_id']."' and geg.item_type = 1 and cru.user_id = '".$char['user_id']."'");
							myquery("DELETE FROM craft_resource_user WHERE user_id = '".$char['user_id']."' and col = 0");	
							myquery("UPDATE game_users SET CW=CW-'".$weight."' WHERE user_id = '".$char['user_id']."' ");
						}					
					}
					
					if ($result['out_id'] > 0)
					{
						//Выдаём игроку предметы
						$check = myquery("SELECT item_id, kol*'".$result['out_kol']."' as kol FROM game_exchange_groups WHERE group_id = '".$result['out_id']."' and item_type = 0");
						if (mysql_num_rows($check)>0)
						{						
							while ($item = mysql_fetch_array($check))
							{
								$Item = new Item();								
								$Item->add_user($item['item_id'],$char['user_id'],0,0,0,$item['kol']);	
							}
						}
						
						//Выдаём игроку ресурсы
						$check = myquery("SELECT item_id, kol*'".$result['out_kol']."' as kol FROM game_exchange_groups WHERE group_id = '".$result['out_id']."' and item_type = 1");
						if (mysql_num_rows($check)>0)
						{						
							while ($item = mysql_fetch_array($check))
							{
								$Res = new Res(0, $item['item_id']);								
								$Res->add_user(0,$char['user_id'],$item['kol']);	
							}
						}						
					}
					
					//Выдаём игроку деньги
					if ($result['out_gp'] > 0)
					{
						save_gp($char['user_id'], $result['out_gp'], 110, 1);
					}
					echo '<b>Обмен успешно произведён!<b/><br>';
					
					//Занесём в лог использование шатра
					myquery("INSERT INTO game_exchange_log (user_id, exchange_id) VALUES ('".$user_id."', '".$result['id']."') ON DUPLICATE KEY UPDATE times=times+1 ");
				}	
				echo '<br><br>';
			}
			else
			{
				echo '<b>К сожалению, выбранного предолжения не существует!</b><br><br>';
			}
		}
		else
		{
			echo 'Вы действительно хотите совершить обмен по данному предложению?';
			echo '<br><a href="town.php?option='.$option.'&exchange='.$_GET['exchange'].'&yes">Да, совершить обмен</a>';
			echo '<br><a href="town.php?option='.$option.'">Нет, вернуться к поиску предложений</a><br>';
		}
	}	
	
	//Формирование запроса к БД, для поиска предложений по заданным условиям
	list($pg) = mysql_fetch_array(myquery("SELECT count(*) FROM game_exchange WHERE enable = 1"));
	if ($pg > 0) 
	{
		//Форма поиска предложений	
		if (!isset($_GET['exchange']) or isset($_GET['yes']))
		{
			echo '<div id="search_form" style="display: block;">';		    
			echo 'Введите параметры поиска предложения по обмену:';	
			echo '<form name="input_form" id="input_form" action="town.php?option='.$option.'" method="POST" >	
				  <table><tr>
				  <td>Название входного предмета/ресурса:</td><td><input id="in_name" name="in_name" type="text" size="20" value="" autocomplete="off">
				  <input id="in_id" name="in_id" type="hidden" size="20" value="0">
				  <input id="in_type" name="in_type" type="hidden" size="20" value="0"></td></tr>
				  <td>Название выходного предмета/ресурса:</td><td><input id="out_name" name="out_name" type="text" size="20" value="" autocomplete="off">
				  <input id="out_id" name="out_id" type="hidden" size="20" value="0">
				  <input id="out_type" name="out_type" type="hidden" size="20" value="0"></td></tr>
				  </table>
				  <input type="submit" name="find" value="Поиск">
				  </form>';
		}
	
		$query = "SELECT {out fields} FROM {select clause} {join clause}";
		$out_fields = "ge.id, ge.in_gp, (CASE WHEN ge.in_id = 0 THEN '0' WHEN cr1.name is null THEN gif1.name ELSE cr1.name END) as in_name, 
					   (CASE WHEN ge.in_id = 0 THEN '0' ELSE ge.in_kol*geg1.kol END) as in_kol, ge.out_gp, 
					   (CASE WHEN ge.out_id = 0 THEN '0' WHEN cr2.name is null THEN gif2.name ELSE cr2.name END) as out_name, 
					   (CASE WHEN ge.out_id = 0 THEN '0' ELSE ge.out_kol*geg2.kol END) as out_kol";
		$query = str_replace("{out fields}", $out_fields, $query);
		$join_clause = "LEFT JOIN game_exchange_groups geg1 ON ge.in_id = geg1.group_id
						LEFT JOIN game_items_factsheet gif1 ON geg1.item_type=0 and geg1.item_id=gif1.id
						LEFT JOIN craft_resource cr1 ON geg1.item_type=1 and geg1.item_id=cr1.id
						LEFT JOIN game_exchange_groups geg2 ON ge.out_id = geg2.group_id
						LEFT JOIN game_items_factsheet gif2 ON geg2.item_type=0 and geg2.item_id=gif2.id
						LEFT JOIN craft_resource cr2 ON geg2.item_type=1 and geg2.item_id=cr2.id";
		$query = str_replace("{join clause}", $join_clause, $query);		
		if (isset($_POST['find']))
		{
			$max_count = 50;
			$select_clause = "(SELECT e.* FROM game_exchange e";
			if (isset($_POST['in_id']) and is_numeric($_POST['in_id']) and $_POST['in_id']>0)
			{
				$select_clause .= " JOIN (SELECT DISTINCT group_id FROM game_exchange_groups WHERE item_id = '".$_POST['in_id']."' and item_type = '".$_POST['in_type']."') 
									geg1 ON e.in_id = geg1.group_id ";
			}
			if (isset($_POST['out_id']) and is_numeric($_POST['out_id']) and $_POST['out_id']>0)
			{
				$select_clause .= " JOIN (SELECT DISTINCT group_id FROM game_exchange_groups WHERE item_id = '".$_POST['out_id']."' and item_type = '".$_POST['out_type']."') 
									geg2 ON e.out_id = geg2.group_id ";
			}
			$select_clause .= " WHERE e.enable = 1 ORDER BY id DESC LIMIT 0, ".$max_count.") ge";
			$query = str_replace("{select clause}", $select_clause, $query);
		}
		elseif (isset($_GET['exchange']) and is_numeric($_GET['exchange']) and $_GET['exchange']>0)
		{
			$select_clause = "(SELECT * FROM game_exchange WHERE id = '".$_GET['exchange']."' and enable = 1) ge";
			$query = str_replace("{select clause}", $select_clause, $query);
		}
		else
		{
			$page_count = 20;		
			$page_max = ceil($pg/$page_count);
			if (isset($_GET['page']) and is_numeric($_GET['page']) and $_GET['page']>=1) $page = $_GET['page'];
			else $page = 1;
			if ($page>$page_max) $page = $page_max;
			$select_clause = "(SELECT * FROM game_exchange WHERE enable = 1 ORDER BY id DESC LIMIT ".(($page-1)*$page_count).", ".$page_count.") ge";
			$query = str_replace("{select clause}", $select_clause, $query);	
		}
		
		if ($page_max>1)
		{
			echo '<br>Страница: ';
	        show_page($page,$page_max,$page_href);
		}
		
		$check = myquery($query);
		if (mysql_num_rows($check)>0)
		{
			//Формирование массива с отобранными предложениями
			$suggest_id = 0;
			$i = 0;
			while ($suggest = mysql_fetch_array($check))
			{
				if ($suggest_id != $suggest['id'])
				{
					$suggest_id = $suggest['id'];
					$i++;
					$mas[$i]['id'] = $suggest_id;
					$mas[$i]['in_gp'] = $suggest['in_gp'];
					$mas[$i]['out_gp'] = $suggest['out_gp'];
					$k_in = 0;
					$k_out = 0;
					$in_name = '';
					$out_name = '';
				}			
				if ($suggest['in_name']<>'0' and $suggest['in_name']<>$in_name)
				{				
					$in_name=$suggest['in_name'];
					$k_in++;
					$mas[$i]['in'][$k_in]['name'] = $suggest['in_name'];
					$mas[$i]['in'][$k_in]['kol'] = $suggest['in_kol'];			
				}
				if ($suggest['out_name']<>'0' and $suggest['out_name']<>$out_name)
				{				 
					$out_name=$suggest['out_name'];
					$k_out++;
					$mas[$i]['out'][$k_out]['name'] = $suggest['out_name'];
					$mas[$i]['out'][$k_out]['kol'] = $suggest['out_kol'];				
				}
			}
			
			//Вывод данных о доступных предложениях на экран
			$i = 1;
			echo '<br><br><table border="2" bordercolor="gold" cellspacing="3" cellpadding="0" ><tr valign="top" align="center">
						  <td width="50"><b>№</b></td>
						  <td width="400"><b>Входные условия</b></td>
						  <td width="400"><b>Выходные условия</b></td>
						  <td width="100"><b>Действие</b></td>					  
						  </tr>';
			while (isset($mas[$i]))
			{
				$k_in = 1;
				$k_out = 1;
				echo '<tr>';
				echo '<td align="center">'.$i.'</td>';			
				echo '<td>';
				if ($mas[$i]['in_gp']>0) echo $mas[$i]['in_gp'].' монет<br>';		
				while (isset($mas[$i]['in'][$k_in]))
				{				
					echo $mas[$i]['in'][$k_in]['name'].' - '.$mas[$i]['in'][$k_in]['kol'].' шт.<br>';
					$k_in++;
				}
				echo '</td>';
				echo '<td>';
				if ($mas[$i]['out_gp']>0) echo $mas[$i]['out_gp'].' монет<br>';			
				while (isset($mas[$i]['out'][$k_out]))
				{				
					echo $mas[$i]['out'][$k_out]['name'].' - '.$mas[$i]['out'][$k_out]['kol'].' шт.<br>';
					$k_out++;
				}
				echo '</td>';
				echo '<td align="center"><a href="town.php?option='.$option.'&exchange='.$mas[$i]['id'].'">Обменять</a></td>';
				echo '</tr>';
				$i++;
			}
			echo '</table>';
			
			if ($page_max>1)
			{
				echo '<br>Страница: ';
				show_page($page,$page_max,$page_href);
			}
			
		}
		else
		{
			echo '<b>В Обменном пункте нет предложений, удовлетворяющих заданным условиям!</b>';
		}
	}
	else
	{
		echo '<b>К сожалению, в Обменном пункте ещё нет предложений!</b>';
	}
	echo '</div>';	
	echo '</center>';		
	echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
}
if (function_exists("save_debug")) save_debug(); 

?>