<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['quest'] >= 1)
{
	if(!isset($edit) and !isset($new) and !isset($delete))
	{
		$pm=myquery("SELECT COUNT(*) FROM bookgame");
		if (!isset($page)) $page=1;
		$page=(int)$page;
		$line=25;
		$allpage=ceil(mysql_result($pm,0,0)/$line);
		if ($page>$allpage) $page=$allpage;
		if ($page<1) $page=1;

		echo "<table border=0 cellspacing=3 cellpadding=3>";
		echo "<tr bgcolor=#333333><td colspan=4 align=center><a href=admin.php?opt=main&option=bookgame&new>�������� �����-����</a></td></tr>";
		echo "<tr bgcolor=#333333><td>ID</td><td>��������</td><td></td></tr>";
		$qw=myquery("SELECT * FROM bookgame order BY id ASC limit ".(($page-1)*$line).", $line");
		while($ar=mysql_fetch_array($qw))
		{
			echo'<tr>
			<td><a href=admin.php?opt=main&option=bookgame&edit='.$ar['id'].'>'.$ar['id'].'</a></td>
			<td>'.$ar['name'].'</td>
			<td><a href=admin.php?opt=main&option=bookgame&delete='.$ar['id'].'>������� ������</a></td>
			</tr>';
		}
		echo'</table>';
		$href = '?opt=main&option=bookgame&';
		echo'<center>��������: ';
		show_page($page,$allpage,$href);
	}

	if(isset($edit))
	{
		if (!isset($save))
		{
			$qw=myquery("SELECT * FROM bookgame where id='$edit'");
			$ar=mysql_fetch_array($qw);
			echo'<form action="" method="post">
			��������: <input type="text" name="name" size=50 value="'.$ar['name'].'"><br><br>
			<input name="save" type="submit" value="���������"><input name="save" type="hidden" value="">';
			
			//������� ������ ������� �����
			echo '<br /><br /><br /><br /><a href="admin.php?opt=main&option=bookgamepage&book='.$ar['id'].'">������������� �������� �����</a>';
		}
		else
		{
			echo'������ ��������';
			$up=myquery("update bookgame set name='".htmlspecialchars($name)."' where id='$edit'");
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
			 VALUES (
			 '".$char['name']."',
			 '������� �������� �����-���� $edit �� : <b>".htmlspecialchars($name)."</b>',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());
			echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=bookgame">';
		}
	}


	if(isset($new))
	{
		if (!isset($save))
		{
			echo'<form action="" method="post">
			��������: <input name=name size=50 value=""><br><br>
			<input name="save" type="submit" value="�������� ������"><input name="save" type="hidden" value="">';
		}
		else
		{
			echo'������ ���������';
			$up=myquery("insert into bookgame (name) VALUES ('".htmlspecialchars($name)."')");
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
			 VALUES (
			 '".$char['name']."',
			 '������� �����-���� : <b>".htmlspecialchars($name)."</b>',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());
			echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=bookgame">';
		}
	}

	if(isset($delete))
	{
		echo'������ �������';
		$text = mysqlresult(myquery("SELECT name FROM bookgame WHERE id='$delete'"),0,0);
		$da = getdate();
		$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
		 VALUES (
		 '".$char['name']."',
		 '������ �����-���� : <b>".htmlspecialchars($text)."</b>',
		 '".time()."',
		 '".$da['mday']."',
		 '".$da['mon']."',
		 '".$da['year']."')")
			 or die(mysql_error());
		$up=myquery("delete from bookgame where id='$delete'");
		echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=bookgame">';
	}

}
if (function_exists("save_debug")) save_debug(); 
?>