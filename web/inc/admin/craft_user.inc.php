<?php
if (function_exists("start_debug")) start_debug(); 

if ($char['clan_id']==1 or $char['name']=='mrHawk')
{
	$link='admin.php?opt=main&option=craft_user';
	
	//������ � �����������
	if (isset($_GET['prof']))
	{
		echo '<center>';
		
		//���������� ������ ��������� ���������
		function craft_list()
		{
			$link='admin.php?opt=main&option=craft_user&prof';
			$sel = myquery('SELECT name, prof_id FROM game_craft_prof');
			if (mysql_num_rows($sel)>0)
			{			
				echo'<table border="1">';
				echo'<tr align="center"><td width="100">ID ���������</td>
				<td width="150">��������</td><td width="180">��������</td></tr>';
				while ($sel_pr = mysql_fetch_array($sel, MYSQL_ASSOC))
				{					
					echo '<form method="POST" action="'.$link.'&chg='.$sel_pr['prof_id'].'">
					<tr align="center">					
					<td align="center">'.$sel_pr['prof_id'].'</td>
					<td align="center"><input type="text" name="name" value="'.$sel_pr['name'].'"></td>					
					<td align="center"><input type="submit" name="save" value="���������"/>&nbsp;&nbsp;&nbsp;
					<input type="submit" name="del" value="�������"/></td></tr>
					</form>';				
				}
				echo('</table>');			
			}
		}
		
		//���������� ����� ��������� ���������
		if (isset($_GET['add_prof']))
		{
			if (isset($_POST['add_p']) and $_POST['name']<>'')
			{
				myquery("INSERT INTO game_craft_prof (name) VALUES ('".$_POST['name']."')");
				$da = getdate();
				$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
					VALUES (
					'".$char['name']."',
					'������� ���������: <b>".$_POST['name']."</b>',
					'".time()."',
					'".$da['mday']."',
					'".$da['mon']."',
					'".$da['year']."')")
						or die(mysql_error());
				echo '<b>��������� ���������!</b><br>';				
			}
			else
			{
				//����� ��� ���������� ����� ���������
				echo '<br><br><b>�������� ���������:</b>
				<table border="1">
				<tr align="center"><td width="100">��������</td>				
				<td width="100">��������</td></tr>';			
				echo '<form method="POST">
					<tr align="center">									
					<td><input type="text" name="name" /></td>
					<td><input type="submit" name="add_p" value="��������"/></td></tr>
					</table></form>';
			}
		}
		elseif (isset($_GET['chg']))
		{
			$upd = myquery("SELECT * FROM game_craft_prof WHERE prof_id = '".$chg."'");
			while ($upd_row = mysql_fetch_array($upd, MYSQL_ASSOC))
			{
				if  (isset($_POST['save']) and isset($_POST['name']) and $_POST['name']<>"")
				{			
					myquery ("UPDATE game_craft_prof SET name='".$_POST['name']."' WHERE prof_id='".$chg."'");					
					$da = getdate();
					$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
						VALUES (
						'".$char['name']."',
						'������� ���������: <b>".$_POST['name']."</b>',
						'".time()."',
						'".$da['mday']."',
						'".$da['mon']."',
						'".$da['year']."')")
							or die(mysql_error());
					echo '<b>��������� ��������!</b><br>';
				}
				elseif (isset($_POST['del']))
				{
					$check=myquery("SELECT user_id FROM game_users_crafts WHERE craft_index='".$chg."'");
					if (mysql_num_rows($check)==0)
					{
						myquery ("DELETE FROM game_craft_prof WHERE prof_id='".$chg."'");
						$da = getdate();
						$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
							VALUES (
							'".$char['name']."',
							'������ ���������: <b>".$_POST['name']."</b>',
							'".time()."',
							'".$da['mday']."',
							'".$da['mon']."',
							'".$da['year']."')")
								or die(mysql_error());
						echo '<b>��������� �������!</b><br>';
					}
					else
					{
						echo '��������� �� ����� ���� �������, ��� ��� ��� ��������� � �������!<br><br>';
					}
				}
				else
				{
					echo '<b>���-�� ������� �������!</b><br>';
				}
			}
		}
		echo '<a href="'.$link.'&prof&add_prof">�������� ���������</a><br/>';
		echo '<a href="'.$link.'">����</a><br><br>';		
		craft_list();
		echo '</center>';
	}
	
	
	// ������ � ������� ������
	elseif (isset($_GET['us_id']))
	{
		echo '<center>';
		$link1='admin.php?opt=main&option=craft_user&us_id='.$_GET['us_id'].'';
		
		//���������� ������ ��������� ������
		function list_user_craft ($id, $us_prof, $k_pr)
		{
			$link1='admin.php?opt=main&option=craft_user&us_id='.$_GET['us_id'].'';
			$pr = myquery('SELECT * FROM game_users_crafts WHERE (game_users_crafts.user_id = "'.$id.'")');
			if (mysql_num_rows($pr)>0)
			{
				echo '<b>��������� ������ ������:</b>';
				echo '<table border="1">';
				echo '<tr align="center"><td width="150">���������</td><td width="220">���������� ��������</td><td width="150">������������</td><td width="200">��������� �������</td><td width="180">��������</td></tr>';
				while ($pr_row = mysql_fetch_array($pr, MYSQL_ASSOC))
				{
					echo '<form method="POST" action="'.$link1.'&us_chg='.$pr_row['craft_index'].'">';
					echo "<tr align='center'><td>";					
					echo '<select name="sln" size="1">';
					$i=0;
					while ($i != $k_pr)
					{
						$i++;
						if ($us_prof[$i]['id']== $pr_row['craft_index'])
						{
							echo '<option selected value="'.$us_prof[$i]['id'].'">'.$us_prof[$i]['name'].'</option>';
						}
						else
						{
							echo '<option value="'.$us_prof[$i]['id'].'">'.$us_prof[$i]['name'].'</option>';
						}
					}
					echo '</select></td>';					
					echo '<td align="center"><input type="text" size="5" name="times" value="'.$pr_row['times'].'"></td><td>';
					if ($pr_row['profile']=='1')
					{
						echo('<select name="prfl" size="1">
							<option selected value="1">����������</option>
							<option value="0">������������</option>
							</select>');
					}
					else
					{
						echo('<select name="prfl" size="1">
							<option value="1">����������</option>
							<option selected value="0">������������</option>
							</select>');
					}
					echo '</td>';
					echo '<td>'.date("d/m/y G:i:s", $pr_row['last_time']).'</td>';
					echo '<td align="center"><input type="submit" name="us_save" value="���������"/>&nbsp;&nbsp;&nbsp;';
					echo '<input type="submit" name="us_del" value="�������"/></td></tr>';
					echo '</form>';
				}
				echo('</table>');
			} 
			
			//����� ��� ���������� ����� ���������
			echo ("<br><br><br><b>�������� ���������:</b>");
			echo ('<form method="POST" action="'.$link1.'">
			<table border="1">
			<tr align="center"><td width="120">���������</td><td width="120">���������� �������</td><td width="150">������������</td><td width="100">��������</td></tr>');
			echo "<tr align='center'><td>";
			$check_prof=myquery("SELECT gcp.prof_id, gcp.name FROM game_craft_prof gcp LEFT JOIN game_users_crafts guc ON (gcp.prof_id=guc.craft_index AND guc.user_id='".$id."') WHERE guc.user_id IS NULL");
			echo '<select name="sl_pr" size="1">';
			$i=0;
			while (list($prof_id, $prof_name)=mysql_fetch_array($check_prof))
			{
				echo '<option value="'.$prof_id.'">'.$prof_name.'</option>';
			}
			echo '</select></td>';
			echo '<td><input type="text" size="5" name="times"></td>';
			echo '<td><select name="pr_in" size="1">
			<option selected value="1">����������</option>
			<option value="0">������������</option>
			</select></td>';
			echo '<td><input type="submit" name="add_user_craft" value="��������"/></td></tr>';
			echo '</table></form>';
		}
		  
		$sel = myquery('SELECT name, prof_id FROM game_craft_prof');
		$i=0;
		while ($prf=mysql_fetch_array($sel))
		{
			$i++;
			$us_prof[$i]['id']=$prf['prof_id'];
			$us_prof[$i]['name']=$prf['name'];
		}
		$k_pr=$i;

		if (isset($_POST['add_user_craft']) and $_POST['times']>0)
		{
			myquery("INSERT INTO game_users_crafts (user_id, craft_index, profile, times, last_time ) 
			VALUES ('".$_GET['us_id']."', '".$_POST['sl_pr']."', '".$_POST['pr_in']."', '".$_POST['times']."','".time()."')");
			list($name)=mysql_fetch_array(myquery("Select name From game_users Where user_id='".$_GET['us_id']."' UNION ALL Select name From game_users_archive Where user_id='".$_GET['us_id']."'"));		
			list($nm_pr)=mysql_fetch_array(myquery("SELECT name FROM game_craft_prof WHERE prof_id=".$_POST['sl_pr']."")); 
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
				 VALUES (
				 '".$char['name']."',
				 '������� ��������� <b>".$nm_pr."</b> ������: <b>".$name."</b>',
				 '".time()."',
				 '".$da['mday']."',
				 '".$da['mon']."',
				 '".$da['year']."')")
					 or die(mysql_error());
			echo '��������� ��������� ������!<br><br>';
		}
		elseif (isset($_GET['us_chg']))
		{
			list($name)=mysql_fetch_array(myquery("Select name From game_users Where user_id='".$_GET['us_id']."' UNION ALL Select name From game_users_archive Where user_id='".$_GET['us_id']."'"));		
			$upd = myquery("SELECT * FROM game_users_crafts WHERE craft_index='".$us_chg."' AND user_id = '".$_GET['us_id']."'");
			while ($upd_row = mysql_fetch_array($upd, MYSQL_ASSOC))
			{
				if  (isset($_POST['us_save']) and $_POST['times']>0)
				{	
					$lst_n = mysql_fetch_array(myquery("SELECT craft_index FROM game_users_crafts WHERE craft_index='".$_POST['sln']."' AND user_id = '".$_GET['us_id']."'"));
					if ($lst_n > 0 AND $us_chg!=$_POST['sln'])	
						{	
						echo '����� ��������� ��� ����!<br><br>';							
						}
					else
						{
						myquery ("UPDATE game_users_crafts SET craft_index='".$_POST['sln']."', profile='".$_POST['prfl']."', times='".$_POST['times']."' WHERE craft_index='".$us_chg."' AND user_id = '".$_GET['us_id']."'");
						list($nm_pr)=mysql_fetch_array(myquery("SELECT name FROM game_craft_prof WHERE prof_id=".$_POST['sln']."")); 
						$da = getdate();
						$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
							VALUES (
							'".$char['name']."',
							'������� ������ ������: <b>".$name."</b> �� ���������: <b>".$nm_pr."</b>',
							'".time()."',
							'".$da['mday']."',
							'".$da['mon']."',
							'".$da['year']."')")
								or die(mysql_error());
						echo '��������� ������ ������ ��������!<br><br>';
						}
				}
				elseif (isset($_POST['us_del']))
				{
					myquery ("DELETE FROM game_users_crafts WHERE craft_index='".$us_chg."' AND user_id = '".$_GET['us_id']."'");
					list($nm_pr)=mysql_fetch_array(myquery("SELECT name FROM game_craft_prof WHERE prof_id=".$_POST['sln']."")); 
					$da = getdate();
					$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
						 VALUES (
						 '".$char['name']."',
						 '������ ������ ������: <b>".$name."</b> �� ���������: <b>".$nm_pr."</b>',
						 '".time()."',
						 '".$da['mday']."',
						 '".$da['mon']."',
						 '".$da['year']."')")
							 or die(mysql_error());
					echo '��������� ������ ������ �������!<br><br>';
				}
			}
		}
		
		list_user_craft ($_GET['us_id'], $us_prof, $k_pr);
		echo '<br><br><a href="'.$link.'">����</a>';
		echo '</center>';	
	}
	
	//��������� �� ����� ������ � ��� id-����
	elseif (isset($_POST['us_name']))
	{
		list($id)=mysql_fetch_array(myquery("Select user_id From game_users Where name='".$_POST['us_name']."' UNION ALL Select user_id From game_users_archive Where name='".$_POST['us_name']."'"));		
		header("Location: ".$link."&us_id=".$id."");
	}
	
	//������� ���� �� ������ � ������� ������
	else
	{
		echo '1. <a href="'.$link.'&prof">������������� ��������� ���������</a>';
		echo '<form method="post">
			  2. ��� ������: <input name="us_name" type="text" size="25" id="keyword" onkeyup="handleKeyUp(event)">
					 <div style="display:none;" id="scroll"><div id="suggest"></div></div>
					 <input name="submit" type="submit" value="����� ������">
					 </form></div><script>init();</script>';		
	}
}

if (function_exists("save_debug")) save_debug(); 
 ?>
