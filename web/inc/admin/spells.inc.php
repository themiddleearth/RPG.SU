<?php
if (function_exists("start_debug")) start_debug(); 

if ($char['clan_id']==1 or $char['name']=='mrHawk')
{
	echo '<center>';
	echo '<h1>Заклинания</h1>';
	$link='admin.php?opt=main&option=spells';
	$mage="10, 11, 12, 13";
	
	//список заклинаний
	function list_sp($spl, $k_spl, $mage)
	{		
		$link='admin.php?opt=main&option=spells';
		$sel = myquery('SELECT gs.*, gsk.name as skill_name FROM game_spells gs JOIN game_skills gsk ON gs.skill_id=gsk.id ORDER BY gs.skill_id, gs.level');
		if (mysql_num_rows($sel)>0)
		{
			$mage_id=0;
			$l=0;
			
			while ($sel_sp = mysql_fetch_array($sel, MYSQL_ASSOC))
			{
				if ($mage_id<>$sel_sp['skill_id'])
				{
					if ($mage_id<>0)
					{
						echo '</table><br><br>';
					}
					echo '<b>'.$sel_sp['skill_name'].'</b>';
					echo('<table border="1">');
					echo '<tr align="center"><td width="30">№</td><td width="100">Название</td>
					<td width="180">Магическая школа</td><td width="50">Тип</td>
					<td width="50">Уровень</td><td width="50">Эффект</td>
					<td width="50">Разброс</td><td width="50">Мана</td>
					<td width="250">Действие</td></tr>';
					$mage_id=$sel_sp['skill_id'];
				}
				$l++;
				echo '<form method="POST" action="'.$link.'&chg='.$sel_sp['id'].'">
				<tr align="center">					
				<td align="center">'.$l.'</td>				
				<td align="center"><input type="text" name="name" value="'.$sel_sp['name'].'"></td>';
				echo '<td><select name="spn" size="1">';
				$i=0;
				while ($i != $k_spl)
				{
					$i++;
					if ($spl[$i]['id']== $sel_sp['skill_id'])
					{
						echo '<option selected value="'.$spl[$i]['id'].'">'.$spl[$i]['name'].'</option>';
					}
					else
					{
						echo '<option value="'.$spl[$i]['id'].'">'.$spl[$i]['name'].'</option>';
					}
				}
				echo '</select></td>
				<td align="center"><select name="type">';
				if ($sel_sp['type']==1) echo '<option selected value="1">Атака</option>';
				else echo '<option value="1">Атака</option>';
				if ($sel_sp['type']==2) echo '<option selected value="2">Лечение</option>';
				else echo '<option value="2">Лечение</option>';
				if ($sel_sp['type']==3) echo '<option selected value="3">Защита</option>';
				else echo '<option value="3">Защита</option>';
				echo '</select></td>
				<td align="center"><input type="text" size="5" name="level" value="'.$sel_sp['level'].'"></td>
				<td align="center"><input type="text" size="5" name="effect" value="'.$sel_sp['effect'].'"></td>
				<td align="center"><input type="text" size="5" name="rand" value="'.$sel_sp['rand'].'"></td>
				<td align="center"><input type="text" size="5" name="mana" value="'.$sel_sp['mana'].'"></td>
				<td align="center"><input type="submit" name="save" value="Сохранить"/>&nbsp;&nbsp;&nbsp;
				<input type="submit" name="del" value="Удалить"/></td></tr>
				</form>';				
			}
			echo('</table>');
		}
		//Форма для добавления нового заклинания
		echo '<br><br><b>Добавить заклинание:</b>
		<table border="1">
		<tr align="center"><td width="100">Название</td>
		<td width="180">Магическая школа</td><td width="50">Тип</td>
		<td width="50">Уровень</td><td width="50">Эффект</td>
		<td width="50">Разброс</td><td width="50">Мана</td>
		<td width="250">Действие</td></tr>';			
		echo '<form method="POST" action="'.$link.'">
		<tr align="center">			
		<td align="center"><input type="text" name="name" ></td>';
		echo '<td><select name="spn" size="1">';
		$i=0;
		while ($i != $k_spl)
		{
			$i++;					
			echo '<option value="'.$spl[$i]['id'].'">'.$spl[$i]['name'].'</option>';				
		}
		echo '</select></td>
		<td align="center"><select name="type">
		<option selected value="1">Атака</option>
		<option value="2">Лечение</option>
		<option value="3">Защита</option>
		</select></td>
		<td align="center"><input type="text" size="5" name="level" ></td>
		<td align="center"><input type="text" size="5" name="effect" ></td>
		<td align="center"><input type="text" size="5" name="rand" ></td>
		<td align="center"><input type="text" size="5" name="mana" ></td>
		<td><input type="submit" name="add_spell" value="Добавить"/></td></tr>
		</table></form>';		
	}
	
	$sel = myquery('SELECT id, name FROM game_skills WHERE id in ('.$mage.') ');
	$i=0;
	while ($spls=mysql_fetch_array($sel))
	{
		$i++;
		$spl[$i]['id']=$spls['id'];
		$spl[$i]['name']=$spls['name'];
	}
	$k_spl=$i;
		
	if (isset($_POST['add_spell']))
	{
		if (isset($_POST['name']) and $_POST['name']<>"")
		{
			myquery("INSERT INTO game_spells (name, skill_id, type, level, effect, rand, mana) 
			VALUES ('".$_POST['name']."', '".$_POST['spn']."', '".$_POST['type']."', '".$_POST['level']."', '".$_POST['effect']."', '".$_POST['rand']."', '".$_POST['mana']."')");
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
				 VALUES (
				 '".$char['name']."',
				 'Добавил заклинание: <b>".$_POST['name']."</b>',
				 '".time()."',
				 '".$da['mday']."',
				 '".$da['mon']."',
				 '".$da['year']."')")
					 or die(mysql_error());
			echo '<b>Заклинание добавлено!</b><br>';
		}
		else
		{
			echo '<b>Что-то введено неверно!</b><br>';
		}
	}
	elseif (isset($_GET['chg']))
	{
		$upd = myquery("SELECT * FROM game_spells WHERE id = '".$chg."'");
		while ($upd_row = mysql_fetch_array($upd, MYSQL_ASSOC))
		{
			if  (isset($_POST['save']) and isset($_POST['name']) and $_POST['name']<>"")
			{			
				myquery ("UPDATE game_spells SET skill_id='".$_POST['spn']."', 
				name='".$_POST['name']."', type='".$_POST['type']."', level='".$_POST['level']."',
				effect='".$_POST['effect']."', rand='".$_POST['rand']."', 
				mana='".$_POST['mana']."' WHERE id='".$chg."'");
				$da = getdate();
				$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
					 VALUES (
					 '".$char['name']."',
					 'Изменил заклинание: <b>".$_POST['name']."</b>',
					 '".time()."',
					 '".$da['mday']."',
					 '".$da['mon']."',
					 '".$da['year']."')")
						 or die(mysql_error());
				echo '<b>Заклинание изменено!</b><br>';
			}
			elseif (isset($_POST['del']))
			{				
				myquery ("DELETE FROM game_spells WHERE id='".$chg."'");
				$da = getdate();
				$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
					VALUES (
					'".$char['name']."',
					'Удалил заклинание: <b>".$_POST['name']."</b>',
					'".time()."',
					'".$da['mday']."',
					'".$da['mon']."',
					'".$da['year']."')")
					or die(mysql_error());
				echo '<b>Заклинание удалено!</b><br>';
			}			
		}
	}
	
	list_sp ($spl, $k_spl, $mage);		
	echo '</center>';	
}
if (function_exists("save_debug")) save_debug(); 
?>
