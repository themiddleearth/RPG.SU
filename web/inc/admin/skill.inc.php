<?php
if (function_exists("start_debug")) start_debug(); 

if ($char['clan_id']==1 or $char['name']=='mrHawk')
{
	
	$link='admin.php?opt=main&option=skill';
	
	//������ �� ���������������
	if (isset($_GET['skl']))
	{
		echo '<center>';
		//������ �������������
		function list_sp()
		{
			$link='admin.php?opt=main&option=skill&skl';
			$sel = myquery('SELECT * FROM game_skills ORDER BY id');
			if (mysql_num_rows($sel)>0)
			{
				$i=0;
				echo('<table border="1">');
				echo '<tr align="center"><td width="30">�</td><td width="50">Id</td><td width="100">��������</td>
				<td width="180">��������</td><td width="100">����. �������</td>
				<td width="100">������������</td><td width="50">������</td>
				<td width="250">��������</td></tr>';
				while ($sel_sp = mysql_fetch_array($sel, MYSQL_ASSOC))
				{
					$i++;
					echo '<form method="POST" action="'.$link.'&chg='.$sel_sp['id'].'">
					<tr align="center">					
					<td align="center">'.$i.'</td>
					<td align="center"><input type="text" size="5" name="id" value="'.$sel_sp['id'].'"></td>
					<td align="center"><input type="text" name="name" value="'.$sel_sp['name'].'"></td>
					<td align="center"><input type="text" name="descr" value="'.$sel_sp['descr'].'"></td>
					<td align="center"><input type="text" size="5" name="level" value="'.$sel_sp['level'].'"></td>
					<td align="center"><input type="text" size="5" name="reinc" value="'.$sel_sp['reinc'].'"></td>
					<td align="center"><input type="text" size="5" name="group" value="'.$sel_sp['sgroup'].'"></td>
					<td align="center"><input type="submit" name="save" value="���������"/>&nbsp;&nbsp;&nbsp;
					<input type="submit" name="del" value="�������"/></td></tr>
					</form>';				
				}
				echo('</table>');
			}			
		}
		if (isset($_GET['add_skill']))
		{
			if (isset($_POST['name']) and $_POST['name']<>"")
			{
				myquery("INSERT INTO game_skills (name, descr, level, reinc, sgroup) 
				VALUES ('".$_POST['name']."', '".$_POST['descr']."', '".$_POST['level']."', '".$_POST['reinc']."', '".$_POST['group']."')");
				$da = getdate();
				$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
					VALUES (
					'".$char['name']."',
					'������� �������������: <b>".$_POST['name']."</b>',
					'".time()."',
					'".$da['mday']."',
					'".$da['mon']."',
					'".$da['year']."')")
						or die(mysql_error());
				echo '<b>������������� ���������!</b><br>';
			}
			else
			{
				//����� ��� ���������� ����� �������������
				echo '<br><br><b>�������� �������������:</b>
				<table border="1">
				<tr align="center"><td width="100">��������</td>
				<td width="180">��������</td><td width="100">����. �������</td>
				<td width="100">������������</td><td width="50">������</td>
				<td width="100">��������</td></tr>';
				echo '<form method="POST">
				<tr align="center">
				<td align="center"><input type="text" name="name" ></td>
				<td align="center"><input type="text" name="descr" ></td>
				<td align="center"><input type="text" size="5" name="level" value="15"></td>
				<td align="center"><input type="text" size="5" name="reinc" value="0"></td>
				<td align="center"><input type="text" size="5" name="group" value="0"></td>
				<td><input type="submit" name="add_spec" value="��������"/></td></tr>
				</table></form>';
			}
		}
		elseif (isset($_GET['chg']))
		{
			$upd = myquery("SELECT * FROM game_skills WHERE id = '".$chg."'");
			while ($upd_row = mysql_fetch_array($upd, MYSQL_ASSOC))
			{
				if  (isset($_POST['save']) and isset($_POST['name']) and $_POST['name']<>"")
				{			
					myquery ("UPDATE game_skills SET id='".$_POST['id']."', name='".$_POST['name']."', 
					descr='".$_POST['descr']."', level='".$_POST['level']."', reinc='".$_POST['reinc']."', 
					sgroup='".$_POST['group']."' WHERE id='".$chg."'");
					$da = getdate();
					$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
						VALUES (
						'".$char['name']."',
						'������� �������������: <b>".$_POST['name']."</b>',
						'".time()."',
						'".$da['mday']."',
						'".$da['mon']."',
						'".$da['year']."')")
							or die(mysql_error());
					echo '<b>������������� ��������!</b><br>';
				}
				elseif (isset($_POST['del']))
				{
					$check=myquery("SELECT * FROM game_users_skills WHERE skill_id='".$chg."'");
					if (mysql_num_rows($check)==0)
					{
						myquery ("DELETE FROM game_skills WHERE id='".$chg."'");
						$da = getdate();
						$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
							VALUES (
							'".$char['name']."',
							'������ �������������: <b>".$_POST['name']."</b>',
							'".time()."',
							'".$da['mday']."',
							'".$da['mon']."',
							'".$da['year']."')")
								or die(mysql_error());
						echo '<b>������������� �������!</b><br>';
					}
					else
					{
						echo '������������� �� ����� ���� �������, ��� ��� ��� ��������� � �������!<br><br>';
					}
				}
				else
				{
					echo '<b>���-�� ������� �������!</b><br>';
				}
			}
		}
		echo '<a href="'.$link.'&skl&add_skill">�������� �������������</a><br/>';
		echo '<a href="'.$link.'">����</a><br><br>';
		list_sp ();
		echo '</center>';			
	}
	
	// ������ �� ��������������� ������
	elseif (isset($_GET['us_id']))
	{
		echo '<center>';
		$link1='admin.php?opt=main&option=skill&us_id='.$_GET['us_id'].'';
		
		//���������� ������ ������������� ������
		function list_user_skills ($id, $us_skl, $k_skl)
		{
			$link1='admin.php?opt=main&option=skill&us_id='.$_GET['us_id'].'';
			$sk = myquery('SELECT * FROM game_users_skills WHERE (game_users_skills.user_id = "'.$id.'")');
			if (mysql_num_rows($sk)>0)
			{
				echo '<b>������������� ������:</b>';
				echo '<table border="1">';
				echo '<tr align="center"><td width="150">�������������</td><td width="100">�������</td><td width="180">��������</td></tr>';
				while ($sk_row = mysql_fetch_array($sk, MYSQL_ASSOC))
				{
					echo '<form method="POST" action="'.$link1.'&us_chg='.$sk_row['skill_id'].'">';
					echo "<tr align='center'><td>";					
					echo '<select name="skn" size="1">';
					$i=0;
					while ($i != $k_skl)
					{
						$i++;
						if ($us_skl[$i]['id']== $sk_row['skill_id'])
						{
							echo '<option selected value="'.$us_skl[$i]['id'].'">'.$us_skl[$i]['name'].'</option>';
						}
						else
						{
							echo '<option value="'.$us_skl[$i]['id'].'">'.$us_skl[$i]['name'].'</option>';
						}
					}
					echo '</select></td>';					
					echo '<td align="center"><input type="text" size="5" name="level" value="'.$sk_row['level'].'"></td>';
					echo '<td align="center"><input type="submit" name="us_save" value="���������"/>&nbsp;&nbsp;&nbsp;';
					echo '<input type="submit" name="us_del" value="�������"/></td></tr>';
					echo '</form>';
				}
				echo('</table>');
			} 
			
			//����� ��� ���������� ����� ���������
			echo ("<br><br><br><b>�������� �������������:</b>");
			echo ('<form method="POST" action="'.$link1.'">
			<table border="1">
			<tr align="center"><td width="120">�������������</td><td width="100">�������</td><td width="100">��������</td></tr>');
			echo "<tr align='center'><td>";
			$check_skl=myquery("SELECT gs.id, gs.name FROM game_skills gs LEFT JOIN game_users_skills gus ON (gs.id=gus.skill_id AND gus.user_id='".$id."') WHERE gus.user_id IS NULL");
			echo '<select name="sl_sk" size="1">';
			$i=0;
			while (list($skl_id, $skl_name)=mysql_fetch_array($check_skl))
			{
				echo '<option value="'.$skl_id.'">'.$skl_name.'</option>';
			}
			echo '</select></td>';
			echo '<td><input type="text" size="5" name="level"></td>';
			echo '<td><input type="submit" name="add_user_skills" value="��������"/></td></tr>';
			echo '</table></form>';
		}
		  
		$sel = myquery('SELECT id, name FROM game_skills');
		$i=0;
		while ($skls=mysql_fetch_array($sel))
		{
			$i++;
			$us_skl[$i]['id']=$skls['id'];
			$us_skl[$i]['name']=$skls['name'];
		}
		$k_skl=$i;

		if (isset($_POST['add_user_skills']))
		{
			add_skill($_GET['us_id'], $_POST['sl_sk'], $_POST['level']);			
			list($name)=mysql_fetch_array(myquery("Select name From game_users Where user_id='".$_GET['us_id']."' UNION ALL Select name From game_users_archive Where user_id='".$_GET['us_id']."'"));		
			list($nm_sk)=mysql_fetch_array(myquery("SELECT name FROM game_skills WHERE id=".$_POST['sl_sk']."")); 
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
				 VALUES (
				 '".$char['name']."',
				 '������� ������������� <b>".$nm_sk."</b> ������: <b>".$name."</b>',
				 '".time()."',
				 '".$da['mday']."',
				 '".$da['mon']."',
				 '".$da['year']."')")
					 or die(mysql_error());
			echo '������������� ��������� ������!<br><br>';
		}
		elseif (isset($_GET['us_chg']))
		{
			list($name)=mysql_fetch_array(myquery("Select name From game_users Where user_id='".$_GET['us_id']."' UNION ALL Select name From game_users_archive Where user_id='".$_GET['us_id']."'"));		
			$upd = myquery("SELECT * FROM game_users_skills WHERE skill_id='".$us_chg."' AND user_id = '".$_GET['us_id']."'");
			while ($upd_row = mysql_fetch_array($upd, MYSQL_ASSOC))
			{
				if  (isset($_POST['us_save']))
				{	
					$lst_n = mysql_fetch_array(myquery("SELECT skill_id FROM game_users_skills WHERE skill_id='".$_POST['skn']."' AND user_id = '".$_GET['us_id']."'"));
					if ($lst_n > 0 AND $us_chg!=$_POST['skn'])	
					{	
							echo '����� ������������� ��� ����!<br><br>';							
					}
					else
					{	
						list($lev)=mysql_fetch_array(myquery("SELECT level FROM game_users_skills WHERE user_id='".$_GET['us_id']."' AND skill_id='".$us_chg."'"));
						add_skill($_GET['us_id'], $us_chg, ($_POST['level']-$lev));						
						list($name)=mysql_fetch_array(myquery("Select name From game_users Where user_id='".$_GET['us_id']."' UNION ALL Select name From game_users_archive Where user_id='".$_GET['us_id']."'"));		
						list($nm_sk)=mysql_fetch_array(myquery("SELECT name FROM game_skills WHERE id=".$_POST['skn']."")); 
						$da = getdate();
						$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
							VALUES (
							'".$char['name']."',
							'������� ������ ������: <b>".$name."</b> �� �������������: <b>".$nm_sk."</b>',
							'".time()."',
							'".$da['mday']."',
							'".$da['mon']."',
							'".$da['year']."')")
							or die(mysql_error());
						echo '������ ������������� ������ ��������!<br><br>';
					}						
				}
				elseif (isset($_POST['us_del']))
				{
					add_skill($_GET['us_id'], $us_chg, -$_POST['level']);					
					list($nm_sk)=mysql_fetch_array(myquery("SELECT name FROM game_skills WHERE id=".$_POST['skn']."")); 
					$da = getdate();
					list($name)=mysql_fetch_array(myquery("Select name From game_users Where user_id='".$_GET['us_id']."' UNION ALL Select name From game_users_archive Where user_id='".$_GET['us_id']."'"));		
					$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
						 VALUES (
						 '".$char['name']."',
						 '������ ������ ������: <b>".$name."</b> �� �������������: <b>".$nm_sk."</b>',
						 '".time()."',
						 '".$da['mday']."',
						 '".$da['mon']."',
						 '".$da['year']."')")
							 or die(mysql_error());
					echo '������ ������������� ������ �������!<br><br>';
				}
			}
		}
		
		list_user_skills ($_GET['us_id'], $us_skl, $k_skl);
		echo '<br><br><a href="'.$link.'">����</a>';
		echo '</center>';	
	}
	
	//��������� �� ����� ������ � ��� id-����
	elseif (isset($_POST['us_name']))
	{
		list($id)=mysql_fetch_array(myquery("Select user_id From game_users Where name='".$_POST['us_name']."' UNION ALL Select user_id From game_users_archive Where name='".$_POST['us_name']."'"));		
		header("Location: ".$link."&us_id=".$id."");
	}
	
	//������� ���� �� ������ �� ��������������� ������
	else
	{
		echo '1. <a href="'.$link.'&skl">������������� �������������</a>';
		echo '<form method="post">
			  2. ��� ������: <input name="us_name" type="text" size="25" id="keyword" onkeyup="handleKeyUp(event)">
					 <div style="display:none;" id="scroll"><div id="suggest"></div></div>
					 <input name="submit" type="submit" value="������������� ������">
					 </form></div><script>init();</script>';		
	}
}
if (function_exists("save_debug")) save_debug(); 
?>
