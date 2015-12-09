<?php
if (function_exists("start_debug")) start_debug(); 

if ($char['clan_id']==1 or $char['name']=='mrHawk')
{
	echo '<center>';
	$link='admin.php?opt=main&option=race';

	//Выведем список рас на экран
	function list_tab()
	{
		$link='admin.php?opt=main&option=race';
		$result = myquery('SELECT name, disable, id FROM game_har');
		if (mysql_num_rows($result)>0)
		{
			echo('<table border="1">');
			echo '<tr align="center"><td width="50">Id</td><td width="100">Раса</td><td width="100">Статус</td><td width="180">Действие</td></tr>';
			while ($result_row = mysql_fetch_array($result, MYSQL_ASSOC))
			{
				echo '<tr align="center">';
				echo '<td>'.$result_row['id'].'</td>';
				echo '<td>'.$result_row['name'].'</td>';
				echo '<td>';
				if ($result_row['disable']==0)
					{echo 'Доступна';}
				else
					{echo 'Заблокирована';}
				echo '</td>';
				echo '<td>'.'<a href="'.$link.'&edt='.$result_row['id'].'">Редактировать</a>&nbsp;&nbsp;&nbsp;<a href="'.$link.'&del='.$result_row['id'].'"> Удалить</a>'.'</td>';
				echo "</tr>";
			}
			echo('</table>');
		}
	}
	//Добавляем расу
	if (isset($_GET['new']))
	{
		if (isset($_POST['new_race']))
		{
			if (isset($_POST['name']) and $_POST['name']<>"" and isset($_POST['race']) and $_POST['race']<>"" and isset($_POST['hp']) and $_POST['hp']<>"" 
				and isset($_POST['mp']) and $_POST['mp']<>"" and isset($_POST['stm']) and $_POST['stm']<>"" and isset($_POST['str']) and $_POST['str']<>""
				and isset($_POST['ntl']) and $_POST['ntl']<>"" and isset($_POST['pie']) and $_POST['pie']<>"" and isset($_POST['vit']) and $_POST['vit']<>""
				and isset($_POST['dex']) and $_POST['dex']<>"" and isset($_POST['spd']) and $_POST['spd']<>"" and isset($_POST['gp']) and $_POST['gp']<>"" 
				and isset($_POST['disable'])
				)
			{
				myquery("INSERT INTO game_har (name, race, hp, hp_max, mp, mp_max, stm, stm_max, str, ntl, pie, vit, dex, spd, gp, disable)
							 VALUES ('".$_POST['name']."', '".$_POST['race']."', '".$_POST['hp']."', '".$_POST['hp']."', '".$_POST['mp']."', '".$_POST['mp']."', 
							 '".$_POST['stm']."', '".$_POST['stm']."', '".$_POST['str']."', '".$_POST['ntl']."', '".$_POST['pie']."', '".$_POST['vit']."', 
							 '".$_POST['dex']."', '".$_POST['spd']."', '".$_POST['gp']."', '".$_POST['disable']."')");
				$da = getdate();
				$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
					 VALUES (
					 '".$char['name']."',
					 'Добавил расу : <b>".$_POST['name']."</b>',
					 '".time()."',
					 '".$da['mday']."',
					 '".$da['mon']."',
					 '".$da['year']."')")
						 or die(mysql_error());
				echo 'Раса добавлена!'; 
			}
			else
			{
				echo 'Что-то введено неверно!';
			}
		}
		else
		{
			echo 'Введите параметры для новой расы';
			echo ('
			<form method="POST">
			<table border="2">
			<tr align="center"><td width="100">Название</td><td width="200">Характеристика</td></tr>
			<tr><td> Название (рус): </td><td><input type="text" name="name"/></td></tr>
			<tr><td> Название (англ):</td><td><input type="text" name="race"/></td></tr>
			<tr><td> HP: </td><td><input type="text" name="hp"/></td></tr>
			<tr><td> MP: </td><td><input type="text" name="mp"/></td></tr>
			<tr><td> STM: </td><td><input type="text" name="stm"/></td></tr>
			<tr><td> Cила: </td><td><input type="text" name="str"/></td></tr>
			<tr><td> Интеллект: </td><td><input type="text" name="ntl"/></td></tr>
			<tr><td> Ловкость: </td><td><input type="text" name="pie"/></td></tr>
			<tr><td> Защита: </td><td><input type="text" name="vit"/></td></tr>
			<tr><td> Выносливость: </td><td><input type="text" name="dex"/></td></tr>
			<tr><td> Мудрость: </td><td><input type="text" name="spd"/></td></tr>
			<tr><td> Деньги: </td><td><input type="text" name="gp"/></td></tr>
			<tr><td> Статус: </td><td>	<select name="disable" size="1"><option value="0">Доступна</option><option value="1">Заблокирована</option></select></td></tr> 
			</table><br>
			<input type="submit" name="new_race" value="Добавить расу" />
			</form>
			');
		}
		echo '<br><br>';
	}

	//Удаляем расу
	elseif (isset($_GET['del']))
	{
		$check=myquery("SELECT * FROM game_users WHERE race='".$_GET['del']."' UNION ALL SELECT * FROM game_users_archive WHERE race='".$_GET['del']."'");
		if (mysql_num_rows($check)==0)	
		{
			myquery("DELETE FROM game_har WHERE id='".$_GET['del']."'");
			echo 'Раса удалена!<br><br>'; 		
		}
		else
		{
			echo 'Раса не может быть удалена. Есть игроки данной расы!<br><br>'; 		
		}
	}

	//Редактируем расу
	
	elseif (isset($_GET['edt']))
	{
		if (isset($_POST['edit_race']))
		{
			if (isset($_POST['name']) and $_POST['name']<>"" and isset($_POST['race']) and $_POST['race']<>"" and isset($_POST['hp']) and $_POST['hp']<>"" 
				and isset($_POST['mp']) and $_POST['mp']<>"" and isset($_POST['stm']) and $_POST['stm']<>"" and isset($_POST['str']) and $_POST['str']<>""
				and isset($_POST['ntl']) and $_POST['ntl']<>"" and isset($_POST['pie']) and $_POST['pie']<>"" and isset($_POST['vit']) and $_POST['vit']<>""
				and isset($_POST['dex']) and $_POST['dex']<>"" and isset($_POST['spd']) and $_POST['spd']<>"" and isset($_POST['gp']) and $_POST['gp']<>"" 
				and isset($_POST['disable'])
				)
			{
				$check=myquery("SELECT * FROM game_har WHERE id=".$_GET['edt']."");
				$race=mysql_fetch_array($check);
				
				myquery("UPDATE game_har SET name='".$_POST['name']."', race='".$_POST['race']."', hp='".$_POST['hp']."', hp_max='".$_POST['hp']."', mp='".$_POST['mp']."', 
				mp_max='".$_POST['mp']."', stm='".$_POST['stm']."', stm_max='".$_POST['stm']."', str='".$_POST['str']."', ntl='".$_POST['ntl']."', pie='".$_POST['pie']."', 
				vit='".$_POST['vit']."', dex='".$_POST['dex']."', spd='".$_POST['spd']."', gp='".$_POST['gp']."', disable='".$_POST['disable']."'
				WHERE id='".$_GET['edt']."'");
				
				//Обновим харки всех игроков данной расы
				myquery("UPDATE game_users SET HP=HP+'".$_POST['hp']."'-'".$race['hp']."',
						HP_MAX=HP_MAX+'".$_POST['hp']."'-'".$race['hp']."',
						HP_MAXX=HP_MAXX+'".$_POST['hp']."'-'".$race['hp']."',
						MP=MP+'".$_POST['mp']."'-'".$race['mp']."',
						MP_MAX=MP_MAX+'".$_POST['mp']."'-'".$race['mp']."',
						STM=STM+'".$_POST['stm']."'-'".$race['stm']."',
						STM_MAX=STM_MAX+'".$_POST['stm']."'-'".$race['stm']."',
						STR=STR+'".$_POST['str']."'-'".$race['str']."',
						STR_MAX=STR_MAX+'".$_POST['str']."'-'".$race['str']."',
						NTL=NTL+'".$_POST['ntl']."'-'".$race['ntl']."',
						NTL_MAX=NTL_MAX+'".$_POST['ntl']."'-'".$race['ntl']."',
						PIE=PIE+'".$_POST['pie']."'-'".$race['pie']."',
						PIE_MAX=PIE_MAX+'".$_POST['pie']."'-'".$race['pie']."',
						VIT=VIT+'".$_POST['vit']."'-'".$race['vit']."',
						VIT_MAX=VIT_MAX+'".$_POST['vit']."'-'".$race['vit']."',
						DEX=DEX+'".$_POST['dex']."'-'".$race['dex']."',
						DEX_MAX=DEX_MAX+'".$_POST['dex']."'-'".$race['dex']."',
						SPD=SPD+'".$_POST['spd']."'-'".$race['spd']."',
						SPD_MAX=SPD_MAX+'".$_POST['spd']."'-'".$race['spd']."'
						WHERE race=".$_GET['edt']."");
				myquery("UPDATE game_users_archive SET HP=HP+'".$_POST['hp']."'-'".$race['hp']."',
						HP_MAX=HP_MAX+'".$_POST['hp']."'-'".$race['hp']."',
						HP_MAXX=HP_MAXX+'".$_POST['hp']."'-'".$race['hp']."',
						MP=MP+'".$_POST['mp']."'-'".$race['mp']."',
						MP_MAX=MP_MAX+'".$_POST['mp']."'-'".$race['mp']."',
						STM=STM+'".$_POST['stm']."'-'".$race['stm']."',
						STM_MAX=STM_MAX+'".$_POST['stm']."'-'".$race['stm']."',
						STR=STR+'".$_POST['str']."'-'".$race['str']."',
						STR_MAX=STR_MAX+'".$_POST['str']."'-'".$race['str']."',
						NTL=NTL+'".$_POST['ntl']."'-'".$race['ntl']."',
						NTL_MAX=NTL_MAX+'".$_POST['ntl']."'-'".$race['ntl']."',
						PIE=PIE+'".$_POST['pie']."'-'".$race['pie']."',
						PIE_MAX=PIE_MAX+'".$_POST['pie']."'-'".$race['pie']."',
						VIT=VIT+'".$_POST['vit']."'-'".$race['vit']."',
						VIT_MAX=VIT_MAX+'".$_POST['vit']."'-'".$race['vit']."',
						DEX=DEX+'".$_POST['dex']."'-'".$race['dex']."',
						DEX_MAX=DEX_MAX+'".$_POST['dex']."'-'".$race['dex']."',
						SPD=SPD+'".$_POST['spd']."'-'".$race['spd']."',
						SPD_MAX=SPD_MAX+'".$_POST['spd']."'-'".$race['spd']."'
						WHERE race=".$_GET['edt']."");		
				$da = getdate();
				$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
					 VALUES (
					 '".$char['name']."',
					 'Изменил расу : <b>".$_POST['name']."</b>',
					 '".time()."',
					 '".$da['mday']."',
					 '".$da['mon']."',
					 '".$da['year']."')")
						 or die(mysql_error());
				echo 'Раса изменена!'; 
			}
			else
			{
				echo 'Что-то введено неверно!';
			}
		}
		else
		{
			$check=myquery("SELECT * FROM game_har WHERE id=".$_GET['edt']."");
			if (mysql_num_rows($check)==1)
			{
				$race=mysql_fetch_array($check);
				echo 'Введите параметры расы';
				echo '<form method="POST" action=""'.$link.'&edt='.$_GET['edt'].'"">
				<table border="2">
				<tr align="center"><td width="100">Название</td><td width="200">Характеристика</td></tr>
				<tr><td> Название (рус): </td><td><input type="text" name="name" value="'.$race['name'].'"/></td></tr>
				<tr><td> Название (англ):</td><td><input type="text" name="race" value="'.$race['race'].'"/></td></tr>
				<tr><td> HP: </td><td><input type="text" name="hp" value="'.$race['hp'].'"/></td></tr>
				<tr><td> MP: </td><td><input type="text" name="mp" value="'.$race['mp'].'"/></td></tr>
				<tr><td> STM: </td><td><input type="text" name="stm" value="'.$race['stm'].'"/></td></tr>
				<tr><td> Cила: </td><td><input type="text" name="str" value="'.$race['str'].'"/></td></tr>
				<tr><td> Интеллект: </td><td><input type="text" name="ntl" value="'.$race['ntl'].'"/></td></tr>
				<tr><td> Ловкость: </td><td><input type="text" name="pie" value="'.$race['pie'].'"/></td></tr>
				<tr><td> Защита: </td><td><input type="text" name="vit" value="'.$race['vit'].'"/></td></tr>
				<tr><td> Выносливость: </td><td><input type="text" name="dex" value="'.$race['dex'].'"/></td></tr>
				<tr><td> Мудрость: </td><td><input type="text" name="spd" value="'.$race['spd'].'"/></td></tr>
				<tr><td> Деньги: </td><td><input type="text" name="gp" value="'.$race['gp'].'"/></td></tr>
				<tr><td> Статус: </td><td><select name="disable" size="1">';
				
				if ($race['disable']==0) $s="selected"; 
				else $s="";
				echo '<option '.$s.' value="0">Доступна</option>';
				if ($race['disable']==1) $s="selected"; 
				else $s="";
				echo '<option '.$s.' value="1">Заблокирована</option>';
				echo '</select></td></tr> 
				</table><br>
				<input type="submit" name="edit_race" value="Изменить расу" />
				</form>';
			}
		}
		echo '<br><br>';
	}
	else
	{
		echo '<a href="'.$link.'&new" >Добавить новую расу</a><br/><br>';
	}
	list_tab();
	echo '</center>';

}
if (function_exists("save_debug")) save_debug(); 
?>
