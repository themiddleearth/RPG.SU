<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['zakon'] >= 1)
{
	include_once('style/tinyMCE/tinyMCE_header.php');

	if(!isset($_GET['edit']) and !isset($_GET['new']) and !isset($_GET['delete']))
	{
		echo "<table border=0 cellspacing=3 cellpadding=3 align=left>";
		echo "<tr bgcolor=#333333><td colspan=4 align=center><a href=admin.php?opt=main&option=zakon&new>�������� �����</a></td></tr>";
		echo "<tr bgcolor=#333333><td>�����</td><td>��������</td><td>��������</td><td></td></tr>";
		$qw=myquery("SELECT * FROM game_zakon order BY id ASC");
		while($ar=mysql_fetch_array($qw))
		{
			echo'<tr>
			<td>'.$ar['id'].'</td>
			<td><a href=admin.php?opt=main&option=zakon&edit='.$ar['id'].'>'.$ar['name'].'</a></td><td>'.$ar['text'].'</td>
			<td><a href=admin.php?opt=main&option=zakon&delete='.$ar['id'].'>������� �����</a></td>
			</tr>';
		}
		echo'</table>';
	}

	if(isset($_GET['edit']))
	{
		if (!isset($_POST['save']))
		{
			$qw=myquery("SELECT * FROM game_zakon where id=".(int)$_GET['edit']."");
			$ar=mysql_fetch_array($qw);
			echo'<form action="" method="post"><font color=ff0000><b>����� �'.$ar['id'].'</b></font> <input type=text name=name value="'.$ar['name'].'" size=80><br>
			<textarea id="elm1" name="elm1" rows="15" cols="80" style="width: 100%">
			'.$ar['text'].'
			</textarea><br><br>
			������� ����� ���� � �������: <input type=text name=time value="'.$ar['time'].'" size=10><br>
			(� ������ �������� ����� ���� ����� ��������� � 2(3,4,5....) ����. <br>��������� 0 - ���� ��� ��������� �� ������ ���� ����)<br><br><br>
			<input name="save" type="submit" value="���������"><input name="save" type="hidden" value="">';
		}
		else
		{
			echo'����� �������';
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
			 VALUES (
			 '".$char['name']."',
			 '������� �����: <b>".$_POST['name']."</b>',
			 ".time().",
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());
			$up=myquery("update game_zakon set name='".$_POST['name']."', text='".$_POST['elm1']."', time='".$_POST['time']."' where id=".(int)$_GET['edit']."");
			echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=zakon">';
		}
	}


	if(isset($_GET['new']))
	{
		if (!isset($_POST['save']))
		{
			echo'<form action="" method="post"><input type=text name=id value="" size=10><br>
			<input type=text name=name value="" size=80><br>
			<textarea id="elm1" name="elm1" rows="15" cols="80" style="width: 100%">
			</textarea>
			<br><br>
			������� ����� ���� � �������: <input type=text name=time value="" size=10><br>
			(� ������ �������� ����� ���� ����� ��������� � 2(3,4,5....) ����. <br>��������� 0 - ���� ��� ��������� �� ������ ���� ����)<br><br><br>
			<input name="save" type="submit" value="�������� �����"><input name="save" type="hidden" value="">';
		}
		else
		{
			echo'����� ��������';
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
			 VALUES (
			 '".$char['name']."',
			 '������� �����: <b>".$name."</b>',
			 ".time().",
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());
			if (isset($id) and $id!='')
				$up=myquery("insert into game_zakon (id,name,text,time) VALUES ($id,'".$_POST['name']."','".$_POST['elm1']."','".$_POST['time']."')") or die(mysql_error());
			else
				$up=myquery("insert into game_zakon (name,text,time) VALUES ('".$_POST['name']."','".$_POST['elm1']."','".$_POST['time']."')");
			echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=zakon">';
		}
	}

	if(isset($_GET['delete']))
	{
		echo'����� ������';
			list($name) = mysql_fetch_array(myquery("SELECT name FROM game_zakon WHERE id='".(int)$_GET['delete']."'"));
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
			 VALUES (
			 '".$char['name']."',
			 '������ �����: <b>".$name."</b>',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());
		$up=myquery("delete from game_zakon where id=".$_GET['delete']."");
		echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=zakon">';
	}

}

if (function_exists("save_debug")) save_debug(); 

?>