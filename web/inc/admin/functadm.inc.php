<?

if (function_exists("start_debug")) start_debug(); 

if ($char['name'] == 'blazevic' OR $char['name']=='The_Elf' OR $char['name']=='Zander'  OR $char['name']=='Victor' OR $char['name']=='High_Elf' OR $char['name']=='Stream_Dan' OR $char['name']=='mrHawk' OR (domain_name == 'testing.rpg.su' and $char['name']=='bruser'))
{
	echo '<center><h2><font color="yellow">������� ��� �������</font></h2>';
	if (isset($_GET['kleymo']))
	{		
		echo '<h3>������� ��� �������� ���� ������</h3>';
		if (isset($_GET['user']))
		{
			$name = $_POST['name'];
			list($id)=mysql_fetch_array(myquery("SELECT user_id FROM game_users WHERE name='".$name."' Union SELECT user_id FROM game_users_archive WHERE name='".$name."'"));
			$check_items=myquery("SELECT id, user_id FROM game_items WHERE user_id='".$id."' and ((kleymo=2 and kleymo_id<>'".$id."') or kleymo=1)");
			if (mysql_num_rows($check_items)==0)
			{
				echo '�������� ����� �� �������!<br>';
			}
			else
			{
				$i=0;
				while($item=mysql_fetch_array($check_items))
				{
					$Item = new Item();
					$i=$i+$Item->kleymo_return_FROM($item['id'],$item['user_id']);
				}
				echo '���������� ���������� '.$i.' ���������!';
			}
		}
		else
		{
			echo'<form action="admin.php?opt=main&option=functadm&kleymo&user" method="post">
			��� ������: <input name="name" type="text" size="25" id="keyword" onkeyup="handleKeyUp(event)">
			<div style="display:none;" id="scroll"><div id="suggest"></div></div>
			<br/><input name="submit" type="submit" value="������� �������� ���� ������">
		    </form></div><script>init();</script>';
		}
	}
	elseif (isset($_GET['itemsdown']))
	{
		echo '<h3>����� ��� �������� � ������</h3>';
		if (isset($_GET['user']))
		{
			$name = $_POST['name'];			
			list($id)=mysql_fetch_array(myquery("SELECT user_id FROM game_users WHERE name='".$name."' Union SELECT user_id FROM game_users_archive WHERE name='".$name."'"));
			$Item = new Item();
			$result=$Item->all_down($id);
			if ($result==1)
			{				
				echo '� ������ ����� ��� ����!';
			}
			else
			{
				echo '� ������ ������ �������!';
			}
		}
		else	
		{
			echo '
			<script type="text/javascript">
			var getFunctionsUrl = "suggest/suggest.php?keyword=";
			var startSearch = 3;
			</script><?
			<link href="suggest/suggest.css" rel="stylesheet" type="text/css">
		    <script type="text/javascript" src="suggest/suggest.js"></script>
		    <form action="admin.php?opt=main&option=functadm&itemsdown&user" method="post">
			��� ������:<input id="keyword" name="name" type="text" size="20" onkeyup="handleKeyUp(event)"><div style="display:none;" id="scroll"><div id="suggest"></div></div>
			<br><input name="submit" type="submit" value="������� ������">
			</form></div><script>init();</script>';
		}
	}	
	elseif (isset($_GET['obnyl']))
	{
		echo '<h3>��������� ������</h3>';
		if (isset($_GET['user']))
		{
			$name = $_POST['name'];			
			list($id)=mysql_fetch_array(myquery("SELECT user_id FROM game_users WHERE name='".$name."' Union SELECT user_id FROM game_users_archive WHERE name='".$name."'"));
			make_full_obnyl($id, 1);			
			echo '<br>����� ������!';
		}
		else	
		{
			echo '
			<script type="text/javascript">
			var getFunctionsUrl = "suggest/suggest.php?keyword=";
			var startSearch = 3;
			</script><?
			<link href="suggest/suggest.css" rel="stylesheet" type="text/css">
		    <script type="text/javascript" src="suggest/suggest.js"></script>
		    <form action="admin.php?opt=main&option=functadm&obnyl&user" method="post">
			��� ������:<input id="keyword" name="name" type="text" size="20" onkeyup="handleKeyUp(event)"><div style="display:none;" id="scroll"><div id="suggest"></div></div>
			<br><input name="submit" type="submit" value="�������� ������">
			</form></div><script>init();</script>';
		}
	}
	elseif (isset($_GET['autocombat']))
	{		
		$type=4;
		$min_kol=1;
		$mes=create_autocombat($type, $min_kol);
		echo $mes;		
	}
	elseif (isset($_GET['vsadnik_update']))
	{
		echo '<h3>����������� �������� ������</h3>';
		if (isset($_GET['user']))
		{
			$name = $_POST['name'];			
			if ($name <> "")
			{
				$check=myquery("SELECT user_id FROM game_users WHERE name='".$name."'");
				if (mysql_num_rows($check)==0)
				{
					list($id)=mysql_fetch_array(myquery("SELECT user_id FROM game_users_archive WHERE name='".$name."'"));
					$type=2;
				}
				else
				{
					list($id)=mysql_fetch_array($check);
					$type=1;
				}
				$i=1;
				vsadnik_update($id, $type);
			}
			else
			{
				$i=0;
				$check=myquery("SELECT user_id FROM game_users WHERE clevel>0");
				while (list($id)=mysql_fetch_array($check))
				{
					$i++;
					vsadnik_update($id, 1);
				}
				$check=myquery("SELECT user_id FROM game_users_archive WHERE clevel>0");
				while (list($id)=mysql_fetch_array($check))
				{
					$i++;
					vsadnik_update($id, 2);
				}
				
				myquery("UPDATE game_users SET vsadnik=0 WHERE clevel=0");
				myquery("UPDATE game_users_archive SET vsadnik=0 WHERE clevel=0");				
			}
			echo '����������� �������: '.$i;
		}
		else	
		{
			echo '
			<script type="text/javascript">
			var getFunctionsUrl = "suggest/suggest.php?keyword=";
			var startSearch = 3;
			</script><?
			<link href="suggest/suggest.css" rel="stylesheet" type="text/css">
		    <script type="text/javascript" src="suggest/suggest.js"></script>
		    <form action="admin.php?opt=main&option=functadm&vsadnik_update&user" method="post">
			��� ������:<input id="keyword" name="name" type="text" size="20" onkeyup="handleKeyUp(event)"><div style="display:none;" id="scroll"><div id="suggest"></div></div>
			<br><input name="submit" type="submit" value="����������� �������� ������">
			</form></div><script>init();</script>';
			echo '<i>���� �� ������ ��� - ������������� ��� ������!</i><br><br>';
		}
	}	
	elseif (isset($_GET['clan_nalog']))
	{
		$da = getdate();
		if (isset($_POST['go']) and $_POST['clan_id']>=0 and $_POST['mon']>0 and $_POST['year']>0)
		{
			$sel=myquery("SELECT * FROM game_clans WHERE raz=0 and (clan_id = '".$_POST['clan_id']."' or 0 = '".$_POST['clan_id']."') and clan_id<>1");
			if (mysql_num_rows($sel)>0)
			{
				while ($clan = mysql_fetch_array($sel))
				{
					$kol = 0;
					$summa = 0;
					$seluser = myquery("(SELECT clevel FROM game_users WHERE clan_id=".$clan['clan_id'].") UNION (SELECT clevel FROM game_users_archive WHERE clan_id=".$clan['clan_id'].")");
					while (list($level)=mysql_fetch_array($seluser))
					{
						$kol++;
						if ($level<10) $summa+=0;
						elseif ($level<20) $summa+=70;
						elseif ($level<30) $summa+=130;
						elseif ($level<40) $summa+=240;
						else $summa+=350;
					}
					if ($kol<=10) $summa=round($summa/2,2);
					elseif ($kol<=20) $summa=round($summa*0.75,2);
					//���������� ������, ���� � ����� ���� �����
					$test_gorod=myquery("SELECT * FROM game_gorod WHERE clan=".$clan['clan_id']."");
					if (mysql_num_rows($test_gorod)>0) $summa=round($summa*(1+0.5*mysql_num_rows($test_gorod)),2);				
	
					myquery("INSERT INTO game_clans_taxes (clan_id,month,year,summa) VALUE (".$clan['clan_id'].",".$_POST['mon'].",".$_POST['year'].",".$summa.")");
					echo '����� ��������� ��� �����: <b>'.$clan['nazv'].'</b><br>';
				}
			}
		}
		else
		{			
			echo '������� ���������:<br><form action="admin.php?opt=main&option=functadm&clan_nalog" method="post">
			����� �����: <input type="text" value="0" size="3" maxsize="3" name="clan_id"><br>
			����� �������: <input type="text" value="'.$da['mon'].'" size="3" maxsize="3" name="mon"><br>
			��� �������: <input type="text" value="'.$da['year'].'" size="3" maxsize="3" name="year"><br>
			<input type="submit" name="go" value="������"><br>
			 <i>(0 � ������ ����� - ������ ��� ���� ������)</i>
			</form>';
		}
	}
	elseif (isset($_GET['chaoscombat']))
	{		
		if (isset($_POST['kol_users']) and isset($_POST['check_time']))
		{
			create_chaoscombat ($_POST['kol_users'], $_POST['check_time']);	
		}
		else
		{			
			echo '������� ���������:<br><form action="admin.php?opt=main&option=functadm&chaoscombat" method="post">
			����������� ����� ������� � ���: <input name="kol_users" value="0" size="3" maxsize="3" type="text"><br>
			������������� �������� �� �������: <SELECT name="check_time">
			<option value="0">�������� �� ��������</option>
            <option value="1">�������� ��������</option></SELECT><br>			
			<input type="submit" name="go" value="������ ����� �����"><br>			 
			</form>';
		}
	}
	
	echo '</center><ol>';
	echo '<li><a href=admin.php?opt=main&option=functadm&kleymo>������� �������� ����� ������</a></li>';
	echo '<li><a href=admin.php?opt=main&option=functadm&itemsdown>������� ������</a></li>';
	echo '<li><a href=admin.php?opt=main&option=functadm&obnyl>�������� ������</a></li>';
	echo '<li><a href=admin.php?opt=main&option=functadm&autocombat>�������</a></li>';
	echo '<li><a href=admin.php?opt=main&option=functadm&vsadnik_update>����������� �������� ������</a></li>';
	echo '<li><a href=admin.php?opt=main&option=functadm&clan_nalog>���������� �������� �����</a></li>';
	echo '<li><a href=admin.php?opt=main&option=functadm&chaoscombat>����� �����</a></li>';
	echo '</ol>';		
}

if (function_exists("save_debug")) save_debug(); 

?>