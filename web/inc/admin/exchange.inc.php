<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['shop'] >= 1)
{
	echo '
	<script type="text/javascript">
	/* URL to the PHP page called for receiving suggestions for a keyword*/
	
	var getFunctionsUrl = "suggest/suggest_items.php?keyword=";
	var startSearch = 1;
	
	function switch_type(type)
	{
		if(type.selectedIndex==0)
		{
			getFunctionsUrl = "suggest/suggest_items.php?keyword=";
		}
		if(type.selectedIndex==1)
		{
			getFunctionsUrl = "suggest/suggest_resource.php?keyword=";
		}
	}
	</script>
	';
	
	function new_item_group($link)
	{
		echo '<br><br>Введите параметры предмета группы:';
		echo '<link href="suggest/suggest.css" rel="stylesheet" type="text/css">';
		echo '<script type="text/javascript" src="suggest/suggest.js"></script>';
		echo '<div id="content" onclick="hideSuggestions();"><b>';
		echo '<table><form action="'.$link.'" method="POST">';
		echo '<tr><td>Название предмета:</td><td><input id="keyword" name="keyword" type="text" size="50" onkeyup="handleKeyUp(event)">
			  <div style="display:none;" id="scroll"><div id="suggest"></div></div></td></tr>';
		echo '<tr><td>Тип предмета:</td><td><select name="type" onChange="switch_type(this)">
			  <option value="0" selected>Предмет</option>
			  <option value="1">Ресурс</option>
			  </select></td></tr>';				
		echo '<tr><td>Количество: </td><td><input type="text" size="5" maxsize="5" name="kol" value="1"></td></tr></table>';
		echo '<input type="submit" name="save" value="Добавить">';
		echo '</form></div><script>init();</script>';
	}
	
	function add_item_group ($group_id, $item_name, $item_type, $kol)
	{
		if ($item_type == 0)
		{
			$check = myquery("SELECT id FROM game_items_factsheet WHERE name='".$item_name."'");
		}
		else
		{	
			$check = myquery("SELECT id FROM craft_resource WHERE name='".$item_name."'");
		}
		if (mysql_num_rows($check)>0)
		{
			list($id)=mysql_fetch_array($check);
			if ($group_id == -999)
			{
				list($group_id)=mysql_fetch_array(myquery("SELECT (CASE WHEN max(group_id) IS NULL THEN 0 ELSE max(group_id) END)+1 FROM game_exchange_groups"));
			}
			myquery("INSERT INTO game_exchange_groups (group_id, item_id, item_type, kol) VALUES ('".$group_id."', '".$id."', '".$item_type."', '".$kol."') ");
			return $group_id;	
		}
		return 0;
	}
	
	echo '<center>';
	echo '<b>Обменный пункт</b>';
	$link = "admin.php?opt=main&option=exchange";
	//Работа с группами товаров обменного пункта
	if (isset($_GET['groups']))
	{
		echo ' -> <b>Управление группами</b>';
		//Создание новой группы
		if (isset($_GET['new']))
		{
			echo ' -> <b>Создание новой группы</b>';
			if (isset($_POST['save']) and $_POST['keyword']<>"" and is_numeric($_POST['kol'])>0 and $_POST['kol']>0)
			{				
				$res = add_item_group (-999, $_POST['keyword'], $_POST['type'], $_POST['kol'] );				
				if ($res > 0)
				{						
					$mes = 'Предмет <b>'.$_POST['keyword'].'</b> добавлен в группу <b>'.$res.'</b>';
					add_admin_log($char, $mes);
					$link_new = $link."&groups&edit=".$res;
					setLocation($link_new);
				}
				else
				{
					echo '<br><br><b>Что-то введено неверно!</b>';
				}
			}
			else
			{				
				$add_link=$link.'&groups&new';
				new_item_group($add_link);
				
			}
			echo '<br><br><a href="'.$link.'&groups">Назад (Управление группами)</a>';
		}
		//Редактирование группы
		elseif (isset($_GET['edit']))
		{
			$add_link=$link.'&groups&edit='.$_GET['edit'];
			echo ' -> <b>Редактирование группы № '.$_GET['edit'].'</b>';
			if (isset($_POST['save']) and $_POST['keyword']<>"" and is_numeric($_POST['kol'])>0 and $_POST['kol']>0)
			{
				$res = add_item_group ($_GET['edit'], $_POST['keyword'], $_POST['type'], $_POST['kol'] );				
				if ($res > 0)
				{											
					$mes = 'Предмет <b>'.$_POST['keyword'].'</b> добавлен в группу <b>'.$res.'</b>';
					add_admin_log($char, $mes);
					echo '<br><br><b>Предмет добавлен в группу!</b>';
				}
				else
				{
					echo '<br><br><b>Что-то введено неверно!</b>';
				}
			}
			elseif (isset($_GET['delete']))
			{
				if (isset($_GET['yes'])) 
				{
					list($name)=mysql_fetch_array(myquery("SELECT (CASE WHEN g3.item_type=0 THEN gif.name ELSE cr.name END) as name FROM game_exchange_groups g3 
				          LEFT JOIN game_items_factsheet gif ON g3.item_type=0 and g3.item_id=gif.id
						  LEFT JOIN craft_resource cr ON g3.item_type=1 and g3.item_id=cr.id WHERE g3.id = '".$_GET['delete']."' "));
					$mes = 'Предмет <b>'.$name.'</b> удалён из группы <b>'.$_GET['delete'].'</b>';
					add_admin_log($char, $mes);
					myquery("DELETE FROM game_exchange_groups WHERE id='".$_GET['delete']."'");
					echo '<br><br><b>Предмет удалён!</b>';
				}
				else
				{
					echo '<br><br>Вы точно хотите удалить предмет группы № '.$_GET['delete'].' ?';
					echo '<br><a href="'.$add_link.'&delete='.$_GET['delete'].'&yes">Да, удалить предмет</a>';
					echo '<br><a href="'.$add_link.'">Нет, вернуться к редактированию группы</a>';
				}
			}		
			new_item_group($add_link);
			$check=myquery("SELECT g3.id, g3.group_id, (CASE WHEN g3.item_type=0 THEN gif.name ELSE cr.name END) as name, g3.item_type, g3.kol 
				          FROM game_exchange_groups g3 
				          LEFT JOIN game_items_factsheet gif ON g3.item_type=0 and g3.item_id=gif.id
						  LEFT JOIN craft_resource cr ON g3.item_type=1 and g3.item_id=cr.id 
						  WHERE g3.group_id = '".$_GET['edit']."' ORDER BY g3.item_type, name");
			if (mysql_num_rows($check)>0)
			{
				echo '<br><br><table border="2" bordercolor="gold" cellspacing="3" cellpadding="0" ><tr valign="top" align="center">
		              <td width="50"><b>№</b></td>
					  <td width="300"><b>Название предмета</b></td>
		              <td width="100"><b>Тип предмета</b></td>
					  <td width="100"><b>Количество</b></td>
					  <td width="100"><b>Действие</b></td>					  
					  </tr>';
				while ($result=mysql_fetch_array($check))
				{
					echo '<tr align="center">';
					echo '<td>'.$result['id'].'</td>';
					echo '<td>'.$result['name'].'</td>';
					if ($result['item_type'] == 0) $type = 'Предмет'; else $type = 'Ресурс';
					echo '<td>'.$type.'</td>';
					echo '<td>'.$result['kol'].'</td>';
					echo '<td><a href="'.$add_link.'&delete='.$result['id'].'">Удалить</a></td>';					
					echo '</tr>';
				}
				echo '</table>';
			}
			echo '<br><br><a href="'.$link.'&groups">Назад (Управление группами)</a>';
		}
		elseif (isset($_GET['del']))
		{
			$check = mysql_num_rows(myquery("SELECT * FROM game_exchange WHERE in_id ='".$_GET['del']."' or out_id ='".$_GET['del']."'  "));
			if ($check==0)
			{
				if (isset($_GET['yes'])) 
				{
					$mes = 'Группа <b>'.$_GET['del'].'</b> удалена';
					add_admin_log($char, $mes);
					myquery("DELETE FROM game_exchange_groups WHERE group_id='".$_GET['del']."'");
					echo '<br><br><b>Группа удалена!</b>';
				}
				else
				{
					echo '<br><br>Вы точно хотите удалить группу № '.$_GET['del'].' ?';
					echo '<br><a href="'.$link.'&groups&del='.$_GET['del'].'&yes">Да, удалить группу</a>';					
				}
			}
			else
			{
				echo '<br><br>Группа № '.$_GET['del'].' уже используется в Обменном Пункте. Необходимо изначально удалить её из всех сделок.';
			}
			echo '<br><br><a href="'.$link.'&groups">Назад (Управление группами)</a>';
		}		
		else
		{
			//Меню управление группами
			echo '<br>1. <a href="'.$link.'&groups&new">Создать группу</a>';
			echo '<br>2. <a href="'.$link.'&groups&all">Посмотреть все группы</a>';
			echo '<br>3. Посмотреть N последних групп';
			echo '<form action="'.$link.'&groups" method="POST"><input type="text" size="5" maxsize="5" value="5" name="n"> <input type="submit" name="show" value="Показать"></form>';
			echo '4. Найти группу';
			echo '<link href="suggest/suggest.css" rel="stylesheet" type="text/css">';
			echo '<script type="text/javascript" src="suggest/suggest.js"></script>';
			echo '<div id="content" onclick="hideSuggestions();"><b>';
			echo '<table><form action="'.$link.'&groups" method="POST">';
			echo '<tr><td>Номер группы: </td><td><input type="text" size="5" maxsize="5" name="group_id"></td>';
			echo '<td>Название предмета:</td><td><input id="keyword" name="keyword" type="text" size="50" onkeyup="handleKeyUp(event)">
			  <div style="display:none;" id="scroll"><div id="suggest"></div></div></td>';
			echo '<td>Тип предмета:</td><td><select name="type" onChange="switch_type(this)">
			  <option value="0" selected>Предмет</option>
			  <option value="1">Ресурс</option>
			  </select></td></tr>';		
			echo '<input type="hidden" id="keyword_id" name="keyword_id">';
			echo '</table><input type="submit" name="find" value="Поиск">';
			echo '</form></div><script>init();</script>';
			
			if (isset($_GET['all']))
			{
				$query="SELECT g3.group_id, (CASE WHEN g3.item_type=0 THEN gif.name ELSE cr.name END) as name, g3.item_type, g3.kol 
				          FROM game_exchange_groups g3 
				          LEFT JOIN game_items_factsheet gif ON g3.item_type=0 and g3.item_id=gif.id
						  LEFT JOIN craft_resource cr ON g3.item_type=1 and g3.item_id=cr.id ORDER BY g3.group_id, g3.item_type, name";
			}
			elseif (isset($_POST['find']))
			{
				$first=0;
				$query="SELECT g3.group_id, (CASE WHEN g3.item_type=0 THEN gif.name ELSE cr.name END) as name, g3.item_type, g3.kol 
				          FROM game_exchange_groups g3 
				          LEFT JOIN game_items_factsheet gif ON g3.item_type=0 and g3.item_id=gif.id
						  LEFT JOIN craft_resource cr ON g3.item_type=1 and g3.item_id=cr.id";
				if ($_POST['keyword']<>"")
				{
					if ($_POST['type'] == 0) list($id)=mysql_fetch_array(myquery("SELECT id FROM game_items_factsheet WHERE name = '".$_POST['keyword']."' "));
					else list($id)=mysql_fetch_array(myquery("SELECT id FROM craft_resource WHERE name = '".$_POST['keyword']."' "));
					$query.=" JOIN game_exchange_groups g4 ON g3.group_id = g4.group_id AND g4.item_id='".$id."' AND g4.item_type='".$_POST['type']."' ";
				}
				if ($_POST['group_id']<>"")
				{
					if ($first==0) { $query.=" WHERE "; $first=1;}
					else { $query.=" AND"; }
					$query.="g3.group_id='".$_POST['group_id']."'";
				}
				$query.=" ORDER BY g3.group_id, g3.item_type, name";
			}
			else
			{
				if (isset($_POST['show']) and is_numeric($_POST['n']) and $_POST['n']>0) $n = $_POST['n'];
				else $n = 5;
				$query="SELECT g3.group_id, (CASE WHEN g3.item_type=0 THEN gif.name ELSE cr.name END) as name, g3.item_type, g3.kol 
				          FROM (SELECT DISTINCT g1.group_id FROM game_exchange_groups g1 ORDER BY group_id DESC LIMIT 0 , ".$n." )g2
                          JOIN game_exchange_groups g3 ON g2.group_id = g3.group_id 
                          LEFT JOIN game_items_factsheet gif ON g3.item_type=0 and g3.item_id=gif.id
						  LEFT JOIN craft_resource cr ON g3.item_type=1 and g3.item_id=cr.id ORDER BY g3.group_id, g3.item_type, name";
			}
			$check=myquery($query);
			if (mysql_num_rows($check)>0)
			{
				$group_id = -999;
				echo '<br><br><table border="2" bordercolor="gold" cellspacing="3" cellpadding="0" ><tr valign="top" align="center">
		              <td width="150"><b>Номер группы</b></td>
		              <td width="400"><b>Состав группы</b></td>
					  <td width="200"><b>Действия</b></td>
					  </tr>';				
				while ($result=mysql_fetch_array($check))
				{
					if ($result['group_id']<>$group_id)
					{
						if ($group_id <> -999)
						{
							echo '</td><td align="center"><a href="'.$link.'&groups&edit='.$group_id.'">Редактировать</a> 
							      <a href="'.$link.'&groups&del='.$group_id.'">Удалить</a></td></tr>';
						}
						$group_id = $result['group_id'];
						echo '<tr>';
						echo '<td align="center">'.$group_id.'</td>';
						echo '<td>'.$result['name'].' - '.$result['kol'].' шт.';
					}
					else
					{
						echo '<br>'.$result['name'].' - '.$result['kol'].' шт.';
					}
				}
				echo '</td><td align="center"><a href="'.$link.'&groups&edit='.$group_id.'">Редактировать</a> 
				     <a href="'.$link.'&groups&del='.$group_id.'">Удалить</a></td></tr></table>';
			}
			echo '<br>';
		}
		echo '<br><a href="'.$link.'">Назад (Обменный пункт)</a>';
	}
	//Главное меню
	else
	{
		//Создаём новое предложение по обмену
		if (isset($_POST['add']))
		{
			if ($_POST['in_id']>=0 and $_POST['in_kol']>=1 and $_POST['in_gp']>=0 and $_POST['out_id']>=0 and $_POST['out_kol']>=1 and $_POST['out_gp']>=0)
			{ 
				if (($_POST['in_id']==0 and $_POST['in_gp']==0) or ($_POST['out_id']==0 and $_POST['out_gp']==0))
				{
					echo '<br><br><b>Нет смысла создавать такое предложение по обмену! Что-то введено неверно!</b>';
				}
				elseif ($_POST['in_id'] == $_POST['out_id'])
				{
					echo '<br><br><b>Ошибка: Входная группа равна Выходной группе!</b>';
				}
				elseif ($_POST['in_id']>0 and mysql_num_rows(myquery("SELECT * FROM game_exchange_groups WHERE group_id = '".$_POST['in_id']."' "))==0 )
				{
					echo '<br><br><b>Входной группы не существует!</b>';
				}
				elseif ($_POST['out_id']>0 and mysql_num_rows(myquery("SELECT * FROM game_exchange_groups WHERE group_id = '".$_POST['out_id']."' "))==0 )
				{
					echo '<br><br><b>Выходной группы не существует!</b>';
				}
				else
				{					
					$mes = 'Создано предложение по обмену для групп <b>'.$_POST['in_id'].'</b> и <b>'.$_POST['out_id'].'</b>';
					add_admin_log($char, $mes);
					myquery("INSERT INTO game_exchange (in_id, in_kol, in_gp, out_id, out_kol, out_gp,enable) VALUES ('".$_POST['in_id']."', '".$_POST['in_kol']."', '".$_POST['in_gp']."', '".$_POST['out_id']."', '".$_POST['out_kol']."', '".$_POST['out_gp']."', '".$_POST['enable']."') ");
					echo '<br><br><b>Предложение по обмену создано!</b>';
				}
			}
			else
			{
				echo '<br><br><b>Что-то введено неверно!</b>';
			}
		}
		//Редактирование предложения по обмену
		elseif (isset($_GET['update']))
		{			
			if ($_POST['in_kol']>=1 and $_POST['in_gp']>=0 and $_POST['out_kol']>=1 and $_POST['out_gp']>=0)
			{
				if (($_POST['in_id']==0 and $_POST['in_gp']==0) or ($_POST['out_id']==0 and $_POST['out_gp']==0))
				{
					echo '<br><br><b>Нет смысла создавать такое предложение по обмену! Что-то введено неверно!</b>';
				}
				else
				{
					$mes = 'Изменено предложение по обмену для групп <b>'.$_POST['in_id'].'</b> и <b>'.$_POST['out_id'].'</b>';
					add_admin_log($char, $mes);					
					myquery("UPDATE game_exchange SET in_kol='".$_POST['in_kol']."', in_gp='".$_POST['in_gp']."', out_kol='".$_POST['out_kol']."', out_gp='".$_POST['out_gp']."', enable='".$_POST['enable']."' WHERE id='".$_GET['update']."' ");
					echo '<br><br><b>Предложение по обмену изменено!</b>';
				}
			}
			else
			{
				echo '<br><br><b>Что-то введено неверно!</b>';
			}
		}
		//Удаление предложения по обмену
		elseif (isset($_GET['delete']))
		{
			if (isset($_GET['yes'])) 
			{				
				$mes = 'Предложение по обмену № <b>'.$_GET['delete'].'</b> удалено';
				add_admin_log($char, $mes);
				myquery("DELETE FROM game_exchange_log WHERE exchange_id='".$_GET['delete']."'");
				myquery("DELETE FROM game_exchange WHERE id='".$_GET['delete']."'");
				echo '<br><br><b>Предложение по обмену удалено!</b>';
			}
			else
			{
				echo '<br><br>Вы точно хотите удалить предложение по обмену № '.$_GET['delete'].' ?';
				echo '<br><a href="'.$link.'&delete='.$_GET['delete'].'&yes">Да, удалить предложение по обмену</a>';				
			}
		}	
		elseif (isset($_GET['enableall']))
		{		
			myquery("UPDATE game_exchange SET enable=1");
		}
		//Меню
		echo '<br><br>1. <a href="'.$link.'&groups">Управление группами</a>';
		echo '<br>2. <a href="'.$link.'&all">Показать все предложения по обмену</a>';
		echo '<br>3. Посмотреть N последних предложений по обмену';
		echo '<form action="'.$link.'" method="POST"><input type="text" size="5" maxsize="5" value="5" name="n"> <input type="submit" name="show1" value="Показать"></form>';
		echo '4. Посмотреть предложения по обмену для конкретных групп';	
		echo '<form action="'.$link.'" method="POST"><table>
		<tr><td>Номер предложения: <input type="text" size="5" maxsize="5" name="id"></td>
		<td>Входная группа: <input type="text" size="5" maxsize="5" name="in_id"></td>
		<td>Выходная группа: <input type="text" size="5" maxsize="5" name="out_id"></td>
		</tr></table><input type="submit" name="show2" value="Показать"></form>';	
				
		//Форма нового предложения по обмену
		echo '<br><br>5. Введите данные для создания нового обмена:';
		echo '<table border="1"><form method="POST">';
		echo '<tr><td><b>Входная группа</b></td>';
		echo '<td>Номер группы: <input type="text" size="5" maxsize="5" name="in_id"></td>';
		echo '<td>Количество: <input type="text" size="3" maxsize="3" name="in_kol" value="1"></td>';
		echo '<td>Деньги: <input type="text" size="7" maxsize="7" name="in_gp" value="0"></td></tr>';
		echo '<tr><td><b>Выходная группа</b></td>';
		echo '<td>Номер группы: <input type="text" size="5" maxsize="5" name="out_id"></td>';
		echo '<td>Количество: <input type="text" size="3" maxsize="3" name="out_kol" value="1"></td>';
		echo '<td>Деньги: <input type="text" size="7" maxsize="7" name="out_gp" value="0"></td></tr>';
		echo '</table>';
		echo '<select name="enable">
		      <option value="1" selected>Доступ открыт</option>
		      <option value="0">Доступ закрыт</option>			  
			  </select>';
		echo '<input type="submit" name="add" value="Создать"></form>';
		
		echo '<br><br>6. <a href="'.$link.'&enableall">Открыть доступ ко всем предложениям</a><br>';
		
		// Построение sql-запроса
		$query = "SELECT {out fields} FROM {select clause} {join clause}";
		$out_fields = "ge.id, ge.in_gp, ge.in_id, ge.in_kol as in_k, (CASE WHEN ge.in_id = 0 THEN '0' WHEN cr1.name is null THEN gif1.name ELSE cr1.name END) as in_name, 
					   (CASE WHEN ge.in_id = 0 THEN '0' ELSE ge.in_kol*geg1.kol END) as in_kol, ge.out_gp, ge.out_kol as out_k,
					   ge.out_id, (CASE WHEN ge.out_id = 0 THEN '0' WHEN cr2.name is null THEN gif2.name ELSE cr2.name END) as out_name, 
					   (CASE WHEN ge.out_id = 0 THEN '0' ELSE ge.out_kol*geg2.kol END) as out_kol, ge.enable";
		$query = str_replace("{out fields}", $out_fields, $query);
		$join_clause = "LEFT JOIN game_exchange_groups geg1 ON ge.in_id = geg1.group_id
						LEFT JOIN game_items_factsheet gif1 ON geg1.item_type=0 and geg1.item_id=gif1.id
						LEFT JOIN craft_resource cr1 ON geg1.item_type=1 and geg1.item_id=cr1.id
						LEFT JOIN game_exchange_groups geg2 ON ge.out_id = geg2.group_id
						LEFT JOIN game_items_factsheet gif2 ON geg2.item_type=0 and geg2.item_id=gif2.id
						LEFT JOIN craft_resource cr2 ON geg2.item_type=1 and geg2.item_id=cr2.id";
		$query = str_replace("{join clause}", $join_clause, $query);
		if (isset($_GET['all']))
		{
			$select_clause = "(SELECT * FROM game_exchange ORDER BY id DESC) ge";
			$query = str_replace("{select clause}", $select_clause, $query);
		}
		elseif (isset($_POST['show2']))
		{			
			$select_clause = "(SELECT e.* FROM game_exchange e WHERE ('".$_POST['id']."' = \"\" or e.id='".$_POST['id']."') AND 
					          ('".$_POST['in_id']."' = \"\" or e.in_id='".$_POST['in_id']."') AND ('".$_POST['out_id']."' = \"\" or e.out_id='".$_POST['out_id']."')) ge";
			$query = str_replace("{select clause}", $select_clause, $query);		
		}		
		else
		{
			if (isset($_POST['show1']) and is_numeric($_POST['n']) and $_POST['n']>0) $n = $_POST['n'];
			else $n = 5;
			$select_clause = "(SELECT * FROM game_exchange ORDER BY id DESC LIMIT 0 , ".$n.") ge";
			$query = str_replace("{select clause}", $select_clause, $query);			
		}
		if (isset($query))
		{			
			$check=myquery($query);
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
						$mas[$i]['in_id'] = $suggest['in_id'];
						$mas[$i]['out_id'] = $suggest['out_id'];
						$mas[$i]['in_kol'] = $suggest['in_k'];
						$mas[$i]['out_kol'] = $suggest['out_k'];
						$mas[$i]['in_gp'] = $suggest['in_gp'];
						$mas[$i]['out_gp'] = $suggest['out_gp'];
						$mas[$i]['enable'] = $suggest['enable'];
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
				
				
				$i = 1;
				echo '<br><br><table border="2" bordercolor="gold" cellspacing="3" cellpadding="0" ><tr valign="top" align="center">
					  <td width="50"><b>Номер</b></td>
					  <td width="250"><b>Входная группа</b></td>
					  <td width="100"><b>Количество</b></td>
					  <td width="80"><b>Деньги</b></td>
					  <td width="250"><b>Выходная группа</b></td>
					  <td width="100"><b>Количество</b></td>
					  <td width="80"><b>Деньги</b></td>
					  <td width="100"><b>Доступ</b></td>					  
					  <td width="120"><b>Действие 1</b></td>
					  <td width="120"><b>Действие 2</b></td>
					  </tr>';				
				while (isset($mas[$i]))
				{
					$k_in = 1;
					$k_out = 1;
					echo '<form action="'.$link.'&update='.$mas[$i]['id'].'" method="POST">';
					echo '<tr align="center" valign="top">';
					echo '<td>'.$mas[$i]['id'].'</td>';								
					
					// Входная группа
					echo '<td>Группа: <b><u>'.$mas[$i]['in_id'].'</b></u>';	
					while (isset($mas[$i]['in'][$k_in]))
					{				
						echo '<br>';
						echo $mas[$i]['in'][$k_in]['name'].' - '.$mas[$i]['in'][$k_in]['kol'].' шт.';
						$k_in++;
					}
					echo '</td>';
					echo '<td><input type="text" name="in_kol" size="5" maxsize="5" value="'.$mas[$i]['in_kol'].'"></td>';
					echo '<td><input type="text" name="in_gp" size="7" maxsize="7" value="'.$mas[$i]['in_gp'].'"></td>';
					
					// Выходная группа
					echo '<td>Группа: <b><u>'.$mas[$i]['out_id'].'</b></u>';
					while (isset($mas[$i]['out'][$k_out]))
					{				
						echo '<br>';
						echo $mas[$i]['out'][$k_out]['name'].' - '.$mas[$i]['out'][$k_out]['kol'].' шт.';
						$k_out++;
					}
					echo '<td><input type="text" name="out_kol" size="5" maxsize="5" value="'.$mas[$i]['out_kol'].'"></td>';
					echo '<td><input type="text" name="out_gp" size="7" maxsize="7" value="'.$mas[$i]['out_gp'].'"></td>';
									
					// Дополнительные параметры
					echo '<td><select name="enable">';
					if ($mas[$i]['enable'] == 1) $selected = "selected";
					else $selected = "";
					echo '<option value="1" '.$selected.'>Доступ открыт</option>';
					if ($mas[$i]['enable'] == 0) $selected = "selected";
					else $selected = "";
					echo '<option value="0" '.$selected.'>Доступ закрыт</option>';
					echo '</select></td>';
					echo '<td><input type="submit" name="save" value="Сохранить"></td>';				
					echo '<td><a href="'.$link.'&delete='.$mas[$i]['id'].'">Удалить</a></td>';
					echo '</tr>';	
					echo '<input type="hidden" name="in_id" value="'.$mas[$i]['in_id'].'"><input type="hidden" name="out_id" value="'.$mas[$i]['out_id'].'">';				
					echo '</form>';
					$i++;
				}
				echo '</td></tr></table>';
			}
		}		
	}
	
	echo '</center>';
}

if (function_exists("save_debug")) save_debug(); 

?>