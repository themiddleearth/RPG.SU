<?
echo '<center>';
$edit = $_GET['edit'];
if (isset($_GET['delopt']))
{
	list($id)=mysql_fetch_array(myquery("Select id From game_npc_set_option Where opt_id=".$_GET['delopt']." and npc_id=".$edit.""));
	myquery("Delete From game_npc_set_option_value Where id=$id");
	myquery("Delete From game_npc_set_option Where id=$id");
	echo '����� �������!';
	echo '<meta http-equiv="refresh" content="2;url=?opt=main&option=npc_template&edit='.$edit.'&npcopt">';
}
elseif (isset($_POST['opt_id']))
{
	$opt_id = $_POST['opt_id'];
	switch ($opt_id)
	{
		// ������������� ����
		case 4:
			if (isset($_POST['min']) and isset($_POST['max']) and $_POST['min']>=0 and $_POST['min']<=$_POST['max'])
			{
				myquery("Delete From game_npc_set_option_value Where id in (Select id From game_npc_set_option Where (opt_id=4 or opt_id=5) and npc_id=$edit)");
				myquery("Delete From game_npc_set_option Where (opt_id=4 or opt_id=5) and npc_id=$edit");
				myquery("Insert Into game_npc_set_option (npc_id, opt_id) Values ($edit, $opt_id)");
				list($new)=mysql_fetch_array(myquery("Select max(id) From game_npc_set_option"));
				myquery("Insert Into game_npc_set_option_value (id, number, value) Values ($new, '1', ".$_POST['min']."), ($new, '2', ".$_POST['max'].")");
				echo '����� ���������!';
				echo '<meta http-equiv="refresh" content="2	;url=?opt=main&option=npc_template&edit='.$edit.'&npcopt">';
			}
			else
			{
				echo '������� �������������� ���������:';
				echo '
				<form method="post" action="admin.php?opt=main&option=npc_template&edit='.$itemc['npc_id'].'&npcopt">
				<table border="2" bordercolor="gold" cellspacing="3" cellpadding="0" ><tr valign="top" align="center">
				<td width="300"><b>��������</b></td><td width="150"><b>��������</b></td></tr>
				<tr align="center"><td>����������� ����:</td><td><input type="text" size="10" maxlength="10" name="min"></td></tr>
				<tr align="center"><td>������������ ����:</td><td><input type="text" size="10" maxlength="10" name="max"></td></tr>
				</table>
				<br/><input type="hidden" value='.$opt_id.' name="opt_id"><input type="submit" value="�������� ���������"></form>';;
			}								
			break;
		// ���� �� ������� �� ������ ������
		case 5:
			if (isset($_POST['value']) and $_POST['value']>0)
			{
				myquery("Delete From game_npc_set_option_value Where id in (Select id From game_npc_set_option Where (opt_id=4 or opt_id=5) and npc_id=$edit)");
				myquery("Delete From game_npc_set_option Where (opt_id=4 or opt_id=5) and npc_id=$edit");
				myquery("Insert Into game_npc_set_option (npc_id, opt_id) Values ($edit, $opt_id)");
				$new=mysql_insert_id();
				myquery("Insert Into game_npc_set_option_value (id, number, value) Values ($new, '1', ".$_POST['value'].")");
				echo '����� ���������!';
				echo '<meta http-equiv="refresh" content="2	;url=?opt=main&option=npc_template&edit='.$edit.'&npcopt">';
			}
			else
			{
				echo '������� �������������� ���������:';
				echo '
				<form method="post" action="admin.php?opt=main&option=npc_template&edit='.$itemc['npc_id'].'&npcopt">
				<table border="2" bordercolor="gold" cellspacing="3" cellpadding="0" ><tr valign="top" align="center">
				<td width="300"><b>��������</b></td><td width="150"><b>��������</b></td></tr>
				<tr align="center"><td>% �� ������ ������:</td><td><input type="text" size="10" maxlength="10" name="value"></td></tr>
				</table>
				<br/><input type="hidden" value='.$opt_id.' name="opt_id"><input type="submit" value="�������� ���������"></form>';;
			}		
			break;
		// ��������� �����
		case 6:
			if (isset($_POST['kol']) and isset($_POST['idf']) and $_POST['kol']>0)
			{
				myquery("Insert Into game_npc_set_option (npc_id, opt_id) Values ($edit, $opt_id)");
				$new=mysql_insert_id();
				myquery("Insert Into game_npc_set_option_value (id, number, value) Values ($new, '1', ".$_POST['idf']."), ($new, '2', ".$_POST['kol'].")");
				echo '����� ���������!';
				echo '<meta http-equiv="refresh" content="2	;url=?opt=main&option=npc_template&edit='.$edit.'&npcopt">';
			}
			else
			{
				echo '������� �������������� ���������:';
				echo '
				<form method="post" action="admin.php?opt=main&option=npc_template&edit='.$itemc['npc_id'].'&npcopt&opt_id">
				<table border="2" bordercolor="gold" cellspacing="3" cellpadding="0" ><tr valign="top" align="center">
				<td width="300"><b>��������</b></td><td width="150"><b>��������</b></td></tr>
				<tr align="center"><td>����������� ���:</td><td>';
				$npc_list=myquery("Select npc_id, npc_name From game_npc_template Order By binary npc_name");
				echo '<select name="idf">';
				while ($npc=mysql_fetch_array($npc_list))
				{
					echo '<option value='.$npc['npc_id'].'>'.$npc['npc_name'].'</option>';
				}
				echo '</select>';
				echo '</td></tr>
				<tr align="center"><td>���������� �����:</td><td><input type="text" size="10" maxlength="10" name="kol"></td></tr>
				</table>
				<br/><input type="hidden" value='.$opt_id.' name="opt_id"><input type="submit" value="�������� ���������"></form>';;
			}		
			break;
		// ��� �� ������
		case 7:
			if (isset($_POST['min']) and isset($_POST['max']) and $_POST['min']>=0 and $_POST['max']<=40 and $_POST['min']<=$_POST['max'])
			{
				myquery("Delete From game_npc_set_option_value Where id in (Select id From game_npc_set_option Where opt_id=7 and npc_id=$edit)");
				myquery("Delete From game_npc_set_option Where opt_id=7 and npc_id=$edit");
				myquery("Insert Into game_npc_set_option (npc_id, opt_id) Values ($edit, $opt_id)");
				$new=mysql_insert_id();
				myquery("Insert Into game_npc_set_option_value (id, number, value) Values ($new, '1', ".$_POST['min']."), ($new, '2', ".$_POST['max'].")");
				echo '����� ���������!';
				echo '<meta http-equiv="refresh" content="2	;url=?opt=main&option=npc_template&edit='.$edit.'&npcopt">';
			}
			else
			{
				echo '������� �������������� ���������:';
				echo '
				<form method="post" action="admin.php?opt=main&option=npc_template&edit='.$itemc['npc_id'].'&npcopt">
				<table border="2" bordercolor="gold" cellspacing="3" cellpadding="0" ><tr valign="top" align="center">
				<td width="300"><b>��������</b></td><td width="150"><b>��������</b></td></tr>
				<tr align="center"><td>����������� �������:</td><td><input type="text" size="10" maxlength="10" name="min"></td></tr>
				<tr align="center"><td>������������ �������:</td><td><input type="text" size="10" maxlength="10" name="max"></td></tr>
				</table>
				<br/><input type="hidden" value='.$opt_id.' name="opt_id"><input type="submit" value="�������� ���������"></form>';;
			}								
			break;
		// ����� ���� ������� �� ������ ������
		case 9:
			if (isset($_POST['koef']) and $_POST['koef']>0)
			{
				myquery("Insert Into game_npc_set_option (npc_id, opt_id) Values ($edit, $opt_id)");
				$new=mysql_insert_id();
				myquery("Insert Into game_npc_set_option_value (id, number, value) Values ($new, '1', ".$_POST['koef'].")");
				echo '����� ���������!';
				echo '<meta http-equiv="refresh" content="2	;url=?opt=main&option=npc_template&edit='.$edit.'&npcopt">';
			}
			else
			{
				echo '������� �������������� ���������:';
				echo '
				<form method="post" action="admin.php?opt=main&option=npc_template&edit='.$itemc['npc_id'].'&npcopt&opt_id">
				<table border="2" bordercolor="gold" cellspacing="3" cellpadding="0" ><tr valign="top" align="center">
				<td width="300"><b>��������</b></td><td width="150"><b>��������</b></td></tr>
				<tr align="center"><td>����������� ������:</td><td><input type="text" size="10" maxlength="10" name="koef"></td></tr>
				</table>
				<br/><input type="hidden" value='.$opt_id.' name="opt_id"><input type="submit" value="�������� ���������"></form>';;
			}								
			break;
		// ���� ���� ������� �� ���� ������
		case 10:
			if (isset($_POST['koef']) and $_POST['koef']>0)
			{
				myquery("Insert Into game_npc_set_option (npc_id, opt_id) Values ($edit, $opt_id)");
				$new=mysql_insert_id();
				myquery("Insert Into game_npc_set_option_value (id, number, value) Values ($new, '1', ".$_POST['koef'].")");
				echo '����� ���������!';
				echo '<meta http-equiv="refresh" content="2	;url=?opt=main&option=npc_template&edit='.$edit.'&npcopt">';
			}
			else
			{
				echo '������� �������������� ���������:';
				echo '
				<form method="post" action="admin.php?opt=main&option=npc_template&edit='.$itemc['npc_id'].'&npcopt&opt_id">
				<table border="2" bordercolor="gold" cellspacing="3" cellpadding="0" ><tr valign="top" align="center">
				<td width="300"><b>��������</b></td><td width="150"><b>��������</b></td></tr>
				<tr align="center"><td>����������� ����:</td><td><input type="text" size="10" maxlength="10" name="koef"></td></tr>
				</table>
				<br/><input type="hidden" value='.$opt_id.' name="opt_id"><input type="submit" value="�������� ���������"></form>';;
			}								
			break;
		// ������������� �������������
		case 11:
			if (isset($_POST['koef_har']) and $_POST['koef_har']>0 and isset($_POST['koef_dev']) and $_POST['koef_dev']>=0 and isset($_POST['type']))
			{
				myquery("Insert Into game_npc_set_option (npc_id, opt_id) Values ($edit, $opt_id)");
				$new=mysql_insert_id();
				myquery("Insert Into game_npc_set_option_value (id, number, value) Values ($new, '1', ".$_POST['type']."), ($new, '2', ".$_POST['koef_har']."), ($new, '3', ".$_POST['koef_dev'].")");
				echo '����� ���������!';
				echo '<meta http-equiv="refresh" content="2	;url=?opt=main&option=npc_template&edit='.$edit.'&npcopt">';
			}
			else
			{
				echo '������� �������������� ���������:';
				echo '
				<form method="post" action="admin.php?opt=main&option=npc_template&edit='.$itemc['npc_id'].'&npcopt&opt_id">
				<table border="2" bordercolor="gold" cellspacing="3" cellpadding="0" ><tr valign="top" align="center">
				<td width="300"><b>��������</b></td><td width="150"><b>��������</b></td></tr>
				<tr align="center"><td>��� ����:</td><td colspan=2><select name="type">
					<option value="1">������ ���� "����-���"</option>
					<option value="2">������ ���� �����</option>
				 </select></tr>
				<tr align="center"><td>����������� �������������:</td><td><input type="text" size="10" maxlength="10" name="koef_har"></td></tr>
				<tr align="center"><td>����������� ��������:</td><td><input type="text" size="10" maxlength="10" name="koef_dev"></td></tr>
				</table>
				<br/><input type="hidden" value='.$opt_id.' name="opt_id"><input type="submit" value="�������� ���������"></form>';;
			}								
			break;
		// ����������� ��������� �� ����	
		case 12:
			if (isset($_POST['type']))
			{
				myquery("Insert Into game_npc_set_option (npc_id, opt_id) Values ($edit, $opt_id)");
				$new=mysql_insert_id();
				myquery("Insert Into game_npc_set_option_value (id, number, value) Values ($new, '1', ".$_POST['type'].")");
				echo '����� ���������!';
				echo '<meta http-equiv="refresh" content="2	;url=?opt=main&option=npc_template&edit='.$edit.'&npcopt">';
			}
			else
			{
				echo '������� �������������� ���������:';
				echo '
				<form method="post" action="admin.php?opt=main&option=npc_template&edit='.$itemc['npc_id'].'&npcopt&opt_id">
				<table border="2" bordercolor="gold" cellspacing="3" cellpadding="0" ><tr valign="top" align="center">
				<td width="300"><b>��������</b></td><td width="150"><b>��������</b></td></tr>
				<tr align="center"><td>��� ����:</td><td colspan=2><select name="type">
					<option value="1">������ ������� �� ���� �� ��������� �������� ���</option>
					<option value="2">������ ������� �� ���� �� ��������� �������� ���, ���� ����� ��� �������� ���</option>
				 </select></tr>
				</table>
				<br/><input type="hidden" value='.$opt_id.' name="opt_id"><input type="submit" value="�������� ���������"></form>';;
			}								
			break;			
		// ���-��������
		case 14:
			if (isset($_POST['map_name']) and isset($_POST['x_pos']) and isset($_POST['y_pos']) and $_POST['x_pos']>0 and $_POST['y_pos']>0)
			{
				myquery("Insert Into game_npc_set_option (npc_id, opt_id) Values ($edit, $opt_id)");
				$new=mysql_insert_id();
				myquery("Insert Into game_npc_set_option_value (id, number, value) Values ($new, '1', '".$_POST['map_name']."'), ($new, '2', ".$_POST['x_pos']."), ($new, '3', ".$_POST['y_pos'].")");
				echo '����� ���������!';
				echo '<meta http-equiv="refresh" content="2	;url=?opt=main&option=npc_template&edit='.$edit.'&npcopt">';
			}
			else
			{
				echo '������� �������������� ���������:';
				echo '
				<form method="post" action="admin.php?opt=main&option=npc_template&edit='.$itemc['npc_id'].'&npcopt&opt_id">
				<table border="2" bordercolor="gold" cellspacing="3" cellpadding="0" ><tr valign="top" align="center">
				<td width="300"><b>��������</b></td><td width="150"><b>��������</b></td></tr>
				<tr align="center"><td>�������� �����:</td><td>';
				$map_list=myquery("Select id, name From game_maps Order By binary name");
				echo '<select name="map_name">';
				while ($map=mysql_fetch_array($map_list))
				{
					echo '<option value='.$map['id'].'>'.$map['name'].'</option>';
				}
				echo '</select>';
				echo '</td></tr>
				<tr align="center"><td>���������� x:</td><td><input type="text" size="3" maxlength="3" name="x_pos" value="0"></td></tr>
				<tr align="center"><td>���������� y:</td><td><input type="text" size="3" maxlength="3" name="y_pos" value="0"></td></tr>
				</table>
				<br/><input type="hidden" value='.$opt_id.' name="opt_id"><input type="submit" value="�������� ���������"></form>';;
			}								
			break;											
		default:
			myquery("Insert Into game_npc_set_option (npc_id, opt_id) Values (".$edit.", ".$opt_id.")");
			echo '����� ���������!';
			echo '<meta http-equiv="refresh" content="1	;url=?opt=main&option=npc_template&edit='.$edit.'&npcopt">';
			break;
	}
	
}
else
{
	$check=myquery("Select * From game_npc_set_option Where npc_id=".$edit." Order by id");
	if (mysql_num_rows($check)>0)
	{
		echo '<b>������� ����� ����<b><br/><br/>';
		echo '<table border="2" bordercolor="gold" cellspacing="3" cellpadding="0" ><tr valign="top" align="center">
			  <td width="30"><b>�����</b></td>
			  <td width="350"><b>�����</b></td>	
			  <td width="150"><b>��������</b></td></tr>
			 ';
		$i=1; 
		while ($stat=mysql_fetch_array($check))
		{
			list($name)=mysql_fetch_array(myquery("Select name From game_npc_option Where opt_id=".$stat['opt_id'].""));
			echo '<tr align="center">';
			echo '<td>'.$i.'</td>';
			echo '<td>'.$name.'';
			$check2=myquery("Select * From game_npc_set_option_value Where id=".$stat['id']."");
			if (mysql_num_rows($check2)>0)
			{
				while ($stat1=mysql_fetch_array($check2))
				{
					if ($stat['opt_id']==6 and $stat1['number']==1) 
					{
						list($npc_name)=mysql_fetch_array(myquery("Select npc_name From game_npc_template Where npc_id=".$stat1['value'].""));
						$stat1['value']=$npc_name;
					}
					if ($stat['opt_id']==14 and $stat1['number']==1) 
					{
						list($map_name)=mysql_fetch_array(myquery("Select name From game_maps Where id=".$stat1['value'].""));
						$stat1['value']=$map_name;
					}
					echo '<br/>�������� '.$stat1['number'].': '.$stat1['value'].'';								
				}
			}
			echo '</td>';
			echo '<td><a href="admin.php?opt=main&option=npc_template&edit='.$itemc['npc_id'].'&npcopt&delopt='.$stat['opt_id'].'">������� �����</a></td></tr>';
			$i++;
		} 
		echo '</table><br/><br/>';
	}
	echo '�������� ����� ����:';
	echo '<form method="post" action="admin.php?opt=main&option=npc_template&edit='.$itemc['npc_id'].'&npcopt">';
	$opt_list=myquery("Select t1.* From game_npc_option as t1 Left Join game_npc_set_option as t2 on t1.opt_id=t2.opt_id and t2.npc_id=$edit Where t2.id is null Order by t1.opt_id");
	echo '<select name="opt_id">';
	while ($opt=mysql_fetch_array($opt_list))
	{
		echo '<option value='.$opt['opt_id'].'>'.$opt['name'].'</option>';
	}
	echo '</select>';
	echo '<br/><br/><input type="submit" value="��������"></form>';
	echo '</form>';
	echo '<br/><b><i>��� ������������ �������� � ����� ������. <br>�������� 100 � �������� ����������� ��� 1!</i></b>';
	echo '</center>';
}
?>			