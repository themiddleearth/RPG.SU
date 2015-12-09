<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['resource'] >= 1)
{
	echo '<center>';
	//Добавление ресурсов игроку
	if (isset($_GET['user']))
	{
		if (isset($_POST['add_res']) and isset($_POST['name']) and isset($_POST['kol']) and is_numeric($_POST['kol']))
		{	
			list($id)=mysql_fetch_array(myquery("SELECT user_id From game_users Where name='".$_POST['name']."' UNION SELECT user_id From game_users_archive Where name='".$_POST['name']."'"));
			$Res = new Res(0, $_POST['res_id']);
			$Res->add_user(0, $id, $_POST['kol'], 0, 1);
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
			 VALUES (
			 '".$char['name']."',
			 'Добавил <b>".$_POST['kol']."</b> ед. ресурса <b>".mysql_result(myquery("SELECT name FROM craft_resource WHERE id='".$_POST['res_id']."'"),0,0)."</b> игроку <b>".$_POST['name']."</b>',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());			
			echo 'Ресурс добавлен!<br>';
		}
		else
		{
			echo 'Заполните форму для добавления ресурса игроку:<br>';
			echo '
			<form action="admin.php?opt=main&option=resource&user" method="post">			
			<link href="suggest/suggest.css" rel="stylesheet" type="text/css">
		    <script type="text/javascript" src="suggest/suggest.js"></script>
			Имя игрока:<input id="keyword" name="name" type="text" size="20" onkeyup="handleKeyUp(event)"><div style="display:none;" id="scroll"><div id="suggest"></div></div>
			</div><script>init();</script>';
			
			$check_res=myquery("SELECT * FROM craft_resource ORDER BY binary name");
			if (mysql_num_rows($check_res)>0)
			{
				echo '<br>Название ресурса: <select name="res_id">';
				while ($res=mysql_fetch_array($check_res))
				{
					echo '<option value='.$res['id'].'>'.$res['name'].'</option>';
				}
				echo '</select>';
			}
			echo'<br>Количество: <input name="kol" type="text" value="1" maxsize="5" size="5">
			<br><input name="add_res" type="submit" value="Добавить ресурс">
			</form>';
		}
	}
	
	if(!isset($edit) and !isset($new) and !isset($delete))
	{
		echo '<table border="0" cellspacing="3" cellpadding="3" align="center">';
		echo "<tr bgcolor=#333333><td colspan=5 align=center><a href=admin.php?opt=main&option=resource&new>Добавить ресурс</a></td></tr>";
		echo "<tr bgcolor=#333333><td colspan=5 align=center><a href=admin.php?opt=main&option=resource&user>Добавить ресурс игроку</a></td></tr>";
		echo "<tr bgcolor=#333333><td>№</td><td>Название</td><td>Вес</td><td coslpan=2></td></tr>";
		$qw=myquery("SELECT * FROM craft_resource order BY id ASC");
		while($ar=mysql_fetch_array($qw))
		{
			echo'<tr>
			<td>'.$ar['id'].'</td>
			<td><a href=admin.php?opt=main&option=resource&edit='.$ar['id'].'>'.$ar['name'].'</a></td>
			<td>'.$ar['weight'].'</td>
			<td><a href=admin.php?opt=main&option=resource&delete='.$ar['id'].'>Удалить ресурс</a></td>
			<td><img src="http://'.img_domain.'/item/resources/'.$ar['img3'].'.gif"</td>
			</tr>';
		}
		echo'</table>';
	}
	if(isset($edit))
	{
		$qw=myquery("SELECT * FROM craft_resource where id='$edit'");
		$ar=mysql_fetch_array($qw);
		if (!isset($save))
		{			
			echo'<form action="" method="post">
			<table>
			<tr><td>Название: </td><td><input type=text name=name value="'.$ar['name'].'" size=100></td></tr>
			<tr><td>Вес 1 ед. ресурса: </td><td><input type=text name=weight value="'.$ar['weight'].'" size=10></td></tr>
			<tr><td>Цена покупки за 1 ед. ресурса: </td><td><input type=text name=incost value="'.$ar['incost'].'" size=10> монет</td></tr>
			<tr><td>Цена продажи за 1 ед. ресурса: </td><td><input type=text name=outcost value="'.$ar['outcost'].'" size=10> монет</td></tr>
			<tr><td>Добыча прокачивает навык</td><td>';
			echo '<select name=spets>
			<option value=\'\'>Нет навыка</option>
			<option value=sobiratel'; if ($ar['spets']=='sobiratel') echo ' selected'; echo '>Собирательство</option>
			<option value=minestone'; if ($ar['spets']=='minestone') echo ' selected'; echo '>Добыча камня</option>
			<option value=mineore'; if ($ar['spets']=='mineore') echo ' selected'; echo '>Добыча руды</option>
			<option value=minewood'; if ($ar['spets']=='minewood') echo ' selected'; echo '>Добыча дерева</option>
			<option value=minemetal'; if ($ar['spets']=='minemetal') echo ' selected'; echo '>Добыча металла</option>
			</select>';
			echo '</td></tr>
			<tr><td>Для повышения уровня навыка требуется</td><td><input type=text name=need_count_for_level value="'.$ar['need_count_for_level'].'" size=10> действий по добыче</td></tr>
			<tr><td>Каждый уровень навыка снижает время добычи на</td><td><input type=text name=decrease_rab_time value="'.$ar['decrease_rab_time'].'" size=10> секунд</td></tr>
			<tr><td>Каждый уровень навыка повышает шанс добычи на</td><td><input type=text name=increase_chance value="'.$ar['increase_chance'].'" size=10> %</td></tr>
			<tr><td>Картинка 20x20: images\item\resources\</td><td><input type=text name=img1 value="'.$ar['img1'].'" size=30>.gif</td></tr>
			<tr><td>Картинка 30x30: images\item\resources\</td><td><input type=text name=img2 value="'.$ar['img2'].'" size=30>.gif</td></tr>
			<tr><td>Картинка 50x50: images\item\resources\</td><td><input type=text name=img3 value="'.$ar['img3'].'" size=30>.gif</td></tr>
			<tr><td>Время жизни в секундах (0 - бесконечно)</td><td><input type="text" name="life_time" value="'.$ar['life_time'].'" size="7" maxsize="7"></td></tr>
			<tr><td><input name="save" type="submit" value="Сохранить"></td><td><input name="save" type="hidden" value=""></td></tr></table>';
		}
		else
		{
			echo'Ресурс изменен';
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
			 VALUES (
			 '".$char['name']."',
			 'Изменил ресурс: <b>".mysql_result(myquery("SELECT name FROM craft_resource WHERE id=$edit"),0,0)."</b> на <b>".$name."</b>',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());
			$up=myquery("update craft_resource set name='$name',weight='$weight',incost='$incost',outcost='$outcost',img1='$img1',img2='$img2',img3='$img3',spets='$spets', need_count_for_level='$need_count_for_level', decrease_rab_time='$decrease_rab_time', increase_chance='$increase_chance', life_time = '$life_time' where id='$edit'");
			
			//обновим время жизни ресурсов
			if ($ar['life_time']<>$life_time)
			{
				if ($life_time == 0) $dead_time = 0;
				elseif ($ar['life_time'] == 0) $dead_time = time() + $life_time;
				else $dead_time = "dead_time +".($life_time - $ar['life_time']);
				myquery("UPDATE craft_resource_user SET dead_time=".$dead_time." WHERE res_id=$edit");
				myquery("UPDATE craft_resource_market SET dead_time=".$dead_time." WHERE res_id=$edit");
				//Запишем столь важное действие в лог
				$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
			   VALUES (
			   '".$char['name']."',
			   'Обновил время жизни ресурса <b>".$name."</b>',
			   '".time()."',
			   '".$da['mday']."',
			   '".$da['mon']."',
			   '".$da['year']."')")
					   or die(mysql_error());
			}
			
			//Если изменился вес ресурса - обновим вес игроков
			if ($ar['weight']<>$weight)
			{
				$delta = $ar['weight'] - $weight;
				myquery("UPDATE game_users gu JOIN craft_resource_user cru ON gu.user_id = cru.user_id SET gu.CW=gu.CW-'".$delta."'*cru.col WHERE cru.res_id='".$edit."' ");
				myquery("UPDATE game_users_archive gu JOIN craft_resource_user cru ON gu.user_id = cru.user_id SET gu.CW=gu.CW-'".$delta."'*cru.col WHERE cru.res_id='".$edit."' ");
			}
			echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=resource">';
		}
	}


	if(isset($new))
	{
		if (!isset($save))
		{
			echo'<form action="" method="post">
			<table>
			<tr><td>Название: </td><td><input type=text name=name value="" size=100></td></tr>
			<tr><td>Вес 1 ед. ресурса: </td><td><input type=text name=weight value="" size=10></td></tr>
			<tr><td>Цена покупки за 1 ед. ресурса: </td><td><input type=text name=incost value="" size=10> монет</td></tr>
			<tr><td>Цена продажи за 1 ед. ресурса: </td><td><input type=text name=outcost value=""" size=10> монет</td></tr>
			<tr><td>Добыча прокачивает навык</td><td>';
			echo '<select name=spets>
			<option value=\'\'>Нет навыка</option>
			<option value=sobiratel>Собирательство</option>
			<option value=minestone>Добыча камня</option>
			<option value=mineore>Добыча руды</option>
			<option value=minewood>Добыча дерева</option>
			<option value=minemetal>Добыча металла</option>
			</select>';
			echo '</td></tr>
			<tr><td>Для повышения уровня навыка требуется</td><td><input type=text name=need_count_for_level value="" size=10> действий по добыче</td></tr>
			<tr><td>Каждый уровень навыка снижает время добычи на</td><td><input type=text name=decrease_rab_time value="" size=10> секунд</td></tr>
			<tr><td>Каждый уровень навыка повышает шанс добычи на</td><td><input type=text name=increase_chance value="" size=10> %</td></tr>
			<tr><td>Картинка 20x20: images\item\resources\</td><td><input type=text name=img1 value="" size=30>.gif</td></tr>
			<tr><td>Картинка 30x30: images\item\resources\</td><td><input type=text name=img2 value="" size=30>.gif</td></tr>
			<tr><td>Картинка 50x50: images\item\resources\</td><td><input type=text name=img3 value="" size=30>.gif</td></tr>
			<tr><td>Время жизни в секундах (0 - бесконечно)</td><td><input type="text" name="life_time" value="0" size="7" maxsize="7"></td></tr>
			<tr><td><input name="save" type="submit" value="Добавить ресурс"></td><td><input name="save" type="hidden" value=""></td></tr></table>';
		}
		else
		{
			echo'Ресурс добавлен';
			$up=myquery("insert into craft_resource (name,weight,incost,outcost,img1,img2,img3,spets,need_count_for_level,decrease_rab_time,increase_chance,life_time) VALUES ('$name','$weight','$incost','$outcost','$img1','$img2','$img3','$spets','$need_count_for_level','$decrease_rab_time','$increase_chance','$life_time')");
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
			 VALUES (
			 '".$char['name']."',
			 'Добавил новый ресурс: <b>".$name."</b>',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());
			echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=resource">';
		}
	}

	if(isset($delete))
	{
		echo ('<center><b> Вы действительно хотите удалить ресурс? 
		<form method="Post">
		<table><tr>
		<td width="60px"><input type="submit" name="resdel" value="Да" style="width: 45px"></input></td>
		<td width="60px"><input type="submit" name="resnodel" value="Нет" style="width: 45px"></input></td>
		</b></center></tr></table>');
		if (isset($_POST['resdel']))
		{
			echo'<br />Ресурс удален';
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
			 VALUES (
			 '".$char['name']."',
			 'Удалил ресурс: <b>".mysql_result(myquery("SELECT name FROM craft_resource WHERE id=$delete"),0,0)."</b>',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());
			$up=myquery("delete from craft_resource_user where res_id='$delete'");
			$up=myquery("delete from craft_resource where id='$delete'");
		}
		echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=resource">';
	}
	echo '</center>';
}

if (function_exists("save_debug")) save_debug(); 

?>