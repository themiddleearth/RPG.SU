<?
/*
echo '
<center><h3><font color=#FF0000 face=verdana>���������� ����������� ������ 12 �����</font></h3></center>
<table><tr><td width=200></td><td>
<ol>
<li><a href="?stat&vid=1">12 ��������� ����������� ���������
<li><a href="?stat&vid=2">��������� ��������������������, ���������� �� �����
<li><a href="?stat&vid=3">���������� �� NPC
<li><a href="?stat&vid=6">���������� �� ��������� ����
<li><a href="?stat&vid=7">���������� �� ��������
<li><a href="?stat&vid=10">���������� �� ������
<li><a href="?stat&vid=13">���������� �� ���������
<li><a href="?stat&vid=17">���������� �� �������� ���������
<li><a href="?stat&vid=21">���������� �� ����
</ol>
</td></tr></table>';

if (function_exists("start_debug")) start_debug(); 

/*
�������� vid:
1 - 12 ��������� ����������� ���������
2 - ��������� ��������������������, ���������� �� �����
3 - 5 ��������� ����������� NPC
4 - 5 ����������, ������� NPC
5 - 5 ������� ������������ � ������������� �����
6 - ���������� �� ��������� ����
7 - 5 ���������� � ������� �������
8 - 5 �������� �������
9 - 5 ���������� � ���������� ����
10 - 5 ���������� � ���������� ������
11 - 5 ���������� � ���������� ���������
12 - 5 ���������� � ���������� ���������
13 - 5 ���������� � ���������� ��������� �� ��������
14 - 5 ��������� ������ ����� �������� ���������
15 - 5 ��������� ������ ����� ����������������� ���������
16 - 5 ��������� ������ ����� ������������������ ���������
17 - 5 ������� ������ ����� �������� ���������
18 - 5 ������� ������ ����� ��������� ���������
19 - 5 ������� ������ ����� ����������������� ���������
20 - 5 ������� ������ ����� ���������������� ���������
21 - 5 ������� ����� ������ ��������� � ������������ �������
22 - 5 ������� ����� ������ ������������ � ������������� �������
23 - 5 ������ ����� ������ ������������ � ������������� �������
24 - 5 �������, ������������ ���� � ������ � �����
25 - 5 �������, ������������ ����� � �������� � �������� ������
26 - 5 �������, ������������� ����� � �������� � �������� ������
27 - 5 �������, ���������� �� �����
*/

/*
������������ ����� ��������� �������
1.        ������������� � ������
2.        �������� ����
3.        ������� �� ��������
4.        ������� �� �����
5.        ���� ����
6.        �������� �����
7.        ������� �����
8.        ����� �� ����
9.        ������� �� �����
10.        ������ �������
11.        ������ �������
12.        ��������������� �������
13.        �������������� �������
14.        ���� � �������
15.        ������� ��������
16.        ����� �� ������

function DbConnectStat()
{
	$db_stat = mysql_connect('localhost', 'rpgsu_stats', 'EuTh4fsFjdvMMuSY') or die(mysql_error());
	mysql_select_db('rpgsu_stats',$db_stat) or die(mysql_error());
}

function PrintTable($Param,$Title)
{

	if ($Param=='open')
	{
		echo '
		<table width="800" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
		<td width="10">&nbsp;</td>
		<td valign=top width="385">';
	}

	if ($Param=='cell_open')
	{
	echo '
	<br>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td width="8" height="8"><img src="http://'.img_domain.'/nav/2_01.jpg" width="8" height="8"></td>
			<td background="http://'.img_domain.'/nav/2_02.jpg"></td>
			<td width="8" height="8"><img src="http://'.img_domain.'/nav/2_04.jpg" width="8" height="8"></td>
		</tr>
		<tr>
			<td background="http://'.img_domain.'/nav/2_05.jpg"></td>
			<td bgcolor="#000000"><div align="center">'.$Title.'</div>
				<table width="95%" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
					<tr>
					  <td width="5" height="6"><img src="http://'.img_domain.'/nav/1_07.jpg" width="5" height="6"></td>
					  <td background="http://'.img_domain.'/nav/1_09.jpg"></td>
					  <td width="7" height="6"><img src="http://'.img_domain.'/nav/1_10.jpg" width="7" height="6"></td>
					</tr>
					<tr>
					  <td width="5" background="http://'.img_domain.'/nav/1_17.jpg"></td>
					  <td height="100%" bgcolor="313131">
					  <p align="center">
					  <table width="90%" height="100%"  border="0" align="center" cellpadding="1" cellspacing="1">';
	}

	if ($Param=='cell_close')
	{
					  echo'
					  </table>
					  </p></td>
					  <td width="5" background="http://'.img_domain.'/nav/1_15.jpg"></td>
					</tr>
					<tr>
					  <td width="5" height="8"><img src="http://'.img_domain.'/nav/1_19.jpg" width="5" height="8"></td>
					  <td background="http://'.img_domain.'/nav/1_20.jpg"></td>
					  <td width="7" height="8"><img src="http://'.img_domain.'/nav/1_22.jpg" width="7" height="8"></td>
					</tr>
				</table>
			</td>
			<td background="http://'.img_domain.'/nav/2_07.jpg"></td>
		</tr>
		<tr>
			<td width="8" height="8"><img src="http://'.img_domain.'/nav/2_10.jpg" width="8" height="8"></td>
			<td background="http://'.img_domain.'/nav/2_11.jpg"></td>
			<td width="8" height="8"><img src="http://'.img_domain.'/nav/2_13.jpg" width="8" height="8"></td>
		</tr>
	</table>';
	}

	if ($Param=='medium')
	{
		echo '
		</td>
		<td width="10">&nbsp;</td>
		<td valign=top width="385">';
	}

	if ($Param=='close')
	{
		echo '
		</td>
		<td width="10">&nbsp;</td>
		</tr>
		</table>';
	}
}

if(!isset($vid)) {$vid=1;}

if (isset($stat))
{
echo '<br><center>';

//12 ��������� ����������� ���������
if ($vid==1)
{
	DbConnect();
	echo'
	<table width="780" border="0" cellspacing="0" cellpadding="0">
	<tr>
			<td width="8" height="8"><img src="http://'.img_domain.'/nav/2_01.jpg" width="8" height="8"></td>
			<td background="http://'.img_domain.'/nav/2_02.jpg"></td>
			<td width="8" height="8"><img src="http://'.img_domain.'/nav/2_04.jpg" width="8" height="8"></td>
	</tr>
	<tr>
		<td background="http://'.img_domain.'/nav/2_05.jpg"></td>
		<td bgcolor="#000000"><div align="center"><span class="style1"><font size=1>12 ��������� ����������� ���������:</font></span></div>';
			$nom=0;
			$it=myquery("SELECT * FROM game_items_factsheet WHERE view='1' order by id DESC limit 12");
			while($item=mysql_fetch_array($it))
			{
				$nom++;
				If ($nom==5) $nom=1;
				if ($nom==1)
						echo '<table height="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td>';
				echo'
				<table height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>';
				echo'<td width="5" height="6"><img src="http://'.img_domain.'/nav/1_07.jpg" width="5" height="6"></td>
					<td background="http://'.img_domain.'/nav/1_09.jpg"></td>
					<td width="7" height="6"><img src="http://'.img_domain.'/nav/1_10.jpg" width="7" height="6"></td><tr>
					<td width="5" background="http://'.img_domain.'/nav/1_17.jpg"></td>
					<td width="180" height="100%" bgcolor="313131">
							<p align="center"><font size=1><a href="http://'.domain_name.'/info/?&item='.$item['id'].'">
							<img src="http://'.img_domain.'/item/'.$item['img'].'.gif" align="left" border=0></a>'.$item['name'].'';
							if ($item['race'] != 0) echo '<br><font color=#FFFF80>����: ['.mysqlresult(myquery("SELECT name FROM game_har WHERE id=".$item['race'].""),0,0).']</font>';
							echo '<br>['.$item['oclevel'].']';
							echo'</font></p>
							 </td>
					<td  width="5" background="http://'.img_domain.'/nav/1_15.jpg"></td>
				</tr>
				<tr>
							 <td width="5" height="8"><img src="http://'.img_domain.'/nav/1_19.jpg" width="5" height="8"></td>
							 <td background="http://'.img_domain.'/nav/1_20.jpg"></td>
					<td width="7" height="8"><img src="http://'.img_domain.'/nav/1_22.jpg" width="7" height="8"></td>';
				echo '
				</tr>
				</table>
				</td>';
				if ($nom==4) echo '</table>';
				else echo '<td>';
			}

	echo'
			</td>
			<td width="5" background="http://'.img_domain.'/nav/2_07.jpg"></td>
	</tr>
	<tr>
			<td width="8" height="8"><img src="http://'.img_domain.'/nav/2_10.jpg" width="8" height="8"></td>
			<td background="http://'.img_domain.'/nav/2_11.jpg"></td>
			<td width="8" height="8"><img src="http://'.img_domain.'/nav/2_13.jpg" width="8" height="8"></td>
	</tr>
	</table>';
}






if ($vid==2)
{
	DbConnect();
	PrintTable('open','');
	//6 ��������� ��������������������
	PrintTable('cell_open','6 ��������� ��������������������');

	$game=myquery("(SELECT name,race,user_id from game_users) UNION (SELECT name,race,user_id from game_users_archive) order by user_id DESC limit 6");
	while($elf=mysql_fetch_array($game))
	{
		echo'<tr><td><b>'.$elf['name'].'</b></td><td>'.mysqlresult(myquery("SELECT name FROM game_har WHERE id=".$elf['race'].""),0,0).'</td></tr>';
	}

	PrintTable('cell_close','');
	PrintTable('medium','');
	//���������� �� �����
	PrintTable('cell_open','���������� �� �����');

	$sel_race = myquery("SELECT id,name FROM game_har WHERE disable=0");
	while (list($id,$race) = mysql_fetch_array($sel_race))
	{
		$full=myquery("(SELECT user_id from game_users where race = '$id') UNION (SELECT user_id from game_users_archive where race = '$id')");
		$rows=mysql_num_rows($full);
		echo"<tr><td><b>$race</b></td><td>$rows</td></tr>";
	}

	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==3)
{
	DbConnect();
	PrintTable('open','');
	//5 ��������� ����������� NPC
	echo '<br>
	<table width="780" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td width="8" height="8"><img src="http://'.img_domain.'/nav/2_01.jpg" width="8" height="8"></td>
			<td background="http://'.img_domain.'/nav/2_02.jpg"></td>
			<td width="8"><img src="http://'.img_domain.'/nav/2_04.jpg" width="8" height="8"></td>
		</tr>
		<tr>
			<td width="8" background="http://'.img_domain.'/nav/2_05.jpg"></td>
			<td bgcolor="#000000"><div align="center">5 ��������� ����������� NPC:</div>
				<table width="100%" height="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
					<tr>
					  <td width="5"><img src="http://'.img_domain.'/nav/1_07.jpg" width="5" height="6"></td>
					  <td background="http://'.img_domain.'/nav/1_09.jpg"></td>
					  <td width="5"><img src="http://'.img_domain.'/nav/1_10.jpg" width="7" height="6"></td>
					</tr>
					<tr>
					  <td width="5" background="http://'.img_domain.'/nav/1_17.jpg"></td>
					  <td height="100%" bgcolor="313131">
					  <table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">';
	$game=myquery("SELECT game_npc.*,game_npc_template.* from game_npc,game_npc_template WHERE game_npc.view=1 AND game_npc_template.npc_id=game_npc.npc_id order by id DESC limit 5");
	while($elf=mysql_fetch_array($game))
	{
		echo'<tr><td><b>'.$elf['npc_name'].'</b>&nbsp;</td><td>����� - <font color=#FF8080>'.$elf['npc_max_hp'].'</font>&nbsp;</td><td>������� - <font color=#FF8080>'.$elf['npc_level'].'</font>&nbsp;</td><td>���� - <font color=#FF8080>'.$elf['EXP'].'</font>&nbsp;</td><td>���. - <font color=#FF8080>'.$elf['npc_gold'].'</font>&nbsp;</td><td>����� - <font color=#FF8080>'.@mysqlresult(@myquery("SELECT name FROM game_maps WHERE id=".$elf['map_name'].""),0,0).' </font>&nbsp;</td><td>X - <font color=#FF8080>'.$elf['xpos'].'</font>&nbsp;</td><td>Y - <font color=#FF8080>'.$elf['ypos'].'</font></td></tr>';
						}
	PrintTable('cell_close','');
	PrintTable('close','');
}


if ($vid==3)
{
	DbConnectStat();
	PrintTable('open','');
	//5 ����� ���������� NPC (�� ������ ����� ������� ������)
	PrintTable('cell_open','5 ����� ���������� NPC (�� ������ ����� ������� ������)');
	$game = myquery("SELECT * FROM game_stat_view WHERE stat_id=5 AND npc_id<>0");
	while ($elf = mysql_fetch_array($game))
	{
		$npc = mysql_fetch_array(myquery("SELECT gamerpgsu.game_npc_template.*,gamerpgsu.game_npc.* FROM gamerpgsu.game_npc,gamerpgsu.game_npc_template WHERE gamerpgsu.game_npc.id='".$elf['npc_id']."' AND gamerpgsu.game_npc.npc_id=gamerpgsu.game_npc_template.npc_id"));
		echo'<tr><td><b>'.$npc['npc_name'].'</b></td><td>'.@mysqlresult(@myquery("SELECT name FROM gamerpgsu.game_maps WHERE id=".$npc['map_name'].""),0,0).'</td><td>���� '.$elf['LOSE'].' ���</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('medium','');

	//5 ����� ������� NPC (��� ������ ����� ������� �������)
	PrintTable('cell_open','5 ����� ������� NPC (��� ������ ����� ������� �������)');
	$game = myquery("SELECT * FROM game_stat_view WHERE stat_id=2 AND npc_id<>0");
	while ($elf = mysql_fetch_array($game))
	{
		$npc = mysql_fetch_array(myquery("SELECT gamerpgsu.game_npc_template.*,gamerpgsu.game_npc.* FROM gamerpgsu.game_npc,gamerpgsu.game_npc_template WHERE gamerpgsu.game_npc.id='".$elf['npc_id']."' AND gamerpgsu.game_npc.npc_id=gamerpgsu.game_npc_template.npc_id"));
		echo'<tr><td><b>'.$npc['npc_name'].'</b></td><td>'.@mysqlresult(@myquery("SELECT name FROM gamerpgsu.game_maps WHERE id=".$npc['map_name'].""),0,0).'</td><td>���� '.$elf['WIN'].' ���</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==3)
{
	DbConnectStat();
	PrintTable('open','');
	//5 �������, ���� ����� ��������� NPC
	PrintTable('cell_open','5 �������, ���� ����� ��������� NPC');
	$game = myquery("SELECT * FROM game_stat_view WHERE stat_id=5 AND user_id<>0");
	while ($elf = mysql_fetch_array($game))
	{
		$sel1 = myquery("(SELECT clevel,name,race,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."') UNION (SELECT clevel,name,race,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."')");
		if (mysql_num_rows($sel1))
		{
			$usr = mysql_fetch_array($sel1);
			echo'<tr><td><b>'.$usr['name'].' ['.$usr['clevel'].']</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.img_domain.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>'.mysqlresult(myquery("SELECT name FROM gamerpgsu.game_har WHERE id=".$usr['race'].""),0,0).'</td><td>���� '.$elf['npc_kill'].' �����</td></tr>';
		}
	}
	PrintTable('cell_close','');
	PrintTable('medium','');

	//5 �������, ���� ����� ������������� NPC
	PrintTable('cell_open','5 �������, ���� ����� ������������� NPC');
	$game = myquery("SELECT * FROM game_stat_view WHERE stat_id=2 AND user_id<>0");
	while ($elf = mysql_fetch_array($game))
	{
		$sel1 = myquery("(SELECT clevel,name,race,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."') UNION (SELECT clevel,name,race,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."')");
		if (mysql_num_rows($sel1))
		{
			$usr = mysql_fetch_array($sel1);
			echo'<tr><td><b>'.$usr['name'].' ['.$usr['clevel'].']</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.img_domain.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>'.mysqlresult(myquery("SELECT name FROM gamerpgsu.game_har WHERE id=".$usr['race'].""),0,0).'</td><td>��� ���� '.$elf['npc_kill'].' ���</td></tr>';
		}
	}

	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==6)
{
	DbConnect();
	PrintTable('open','');
	//���������� ������� � ���� �� ��������� 24 ���
	PrintTable('cell_open','���������� ������� � ���� �� ��������� 24 ���');
	$result = myquery("SELECT DISTINCT day, COUNT(*) AS refcount FROM game_activity GROUP BY day ORDER BY day DESC limit 24");
	$i = 0;
	while ($report = mysql_fetch_array($result))
	{
		if ($i == 0)
		{
			echo '<tr><td><font size="1" color="#dddddd">'.$report['day'].'</font></td><td><font size="1" color="#dddddd"><div align="right">'.$report['refcount'].'</div></font></td></tr>';
			$i = 1;
		}
		elseif ($i == 1)
		{
			echo '<tr><td><font size="1" color="#999999">'.$report['day'].'</font></td><td><font size="1" color="#999999"><div align="right">'.$report['refcount'].'</div></font></td></tr>';
			$i = 0;
		}
	}
	PrintTable('cell_close','');
	PrintTable('medium','');
	//���������� ������� � ���� �� ����� �����
	PrintTable('cell_open','���������� ������� � ���� �� ����� �����');
	$result = myquery("SELECT DISTINCT hour, COUNT(*) AS refcount FROM game_activity GROUP BY hour ORDER BY refcount DESC");
	$i = 0;
	while ($report = mysql_fetch_array($result))
	{
		if ($i == 0)
		{
			echo '<tr><td><font size="1" color="#dddddd">'.$report['hour'].'</font></td><td><font size="1" color="#dddddd"><div align="right">'.$report['refcount'].'</div></font></td></tr>';
			$i = 1;
		}
		elseif ($i == 1)
		{
			echo '<tr><td><font size="1" color="#999999">'.$report['hour'].'</font></td><td><font size="1" color="#999999"><div align="right">'.$report['refcount'].'</div></font></td></tr>';
			$i = 0;
		}
	}
	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==7)
{
	DbConnectStat();
	PrintTable('open','');
	//5 ����� ������� �������
	PrintTable('cell_open','5 ����� ������� �������');
	$game=myquery("SELECT * from gamerpgsu.game_tavern order by dohod DESC limit 5");
	while($elf=mysql_fetch_array($game))
	{
		$town=$elf['town'];
		$town_select = myquery("select rustown from gamerpgsu.game_gorod where town='$town'");
		list($rustown)=mysql_fetch_array($town_select);
		$name = @mysqlresult(@myquery("(SELECT name FROM gamerpgsu.game_users WHERE user_id='".$elf['vladel']."') UNION (SELECT name FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['vladel']."')"),0,0);
		echo'<tr><td><b>'.$rustown.'</b></td><td>'.$name.'</td><td>'.$elf['dohod'].'</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('medium','');
	//5 ����� ���������� �������
	PrintTable('cell_open','5 ����� ���������� �������');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=14 AND substat_id=1 ORDER BY kol DESC");
	while($elf=mysql_fetch_array($game))
	{
		$rustown = @mysqlresult(@myquery("SELECT rustown FROM gamerpgsu.game_gorod WHERE town = '".$elf['town_id']."'"),0,0);
		$vladel = @mysqlresult(@myquery("SELECT vladel FROM gamerpgsu.game_tavern WHERE town = '".$elf['town_id']."'"),0,0);
		$name = @mysqlresult(@myquery("(SELECT name FROM gamerpgsu.game_users WHERE user_id='".$vladel."') UNION (SELECT name FROM gamerpgsu.game_users_archive WHERE user_id='".$vladel."')"),0,0);
		echo'<tr><td><b>'.$rustown.'</b></td><td>'.$name.'</td><td>���� '.$elf['kol'].' ��� �� '.$elf['summa'].' ���.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==7)
{
	DbConnectStat();
	echo'
	<table width="400" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
	<td width="10">&nbsp;</td>
	<td valign=top align=center>';

	//5 ����� �������� �������
	PrintTable('cell_open','5 ����� �������� �������');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=14 AND substat_id=2 ORDER BY summa DESC");
	while($elf=mysql_fetch_array($game))
	{
		$sel1 = myquery("SELECT name,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."'");
		if (!mysql_num_rows($sel1))
		$sel1 = myquery("SELECT name,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."'");
		if (mysql_num_rows($sel1))
		{
			$usr = mysql_fetch_array($sel1);
			echo'<tr><td><b>'.$usr['name'].'</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.img_domain.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>���� '.$elf['kol'].' ���</td><td>�� '.$elf['summa'].' ���.</td></tr>';
		}
	}
	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==7)
{
	DbConnectStat();
	PrintTable('open','');
	//5 ����� ���������� ����
	PrintTable('cell_open','5 ����� ���������� ����');
	$game=myquery("SELECT * FROM gamerpgsu.game_stat_view WHERE stat_id=14 AND substat_id=3 ORDER BY kol DESC");
	while($elf=mysql_fetch_array($game))
	{
		$rustown = @mysqlresult(@myquery("SELECT rustown FROM gamerpgsu.game_gorod WHERE town = '".$elf['town_id']."'"),0,0);
		$name = @mysqlresult(@myquery("SELECT item FROM gamerpgsu.game_tavern_shop WHERE id = '".$elf['eat_id']."'"),0,0);
		echo'<tr><td><b>'.$name.'</b></td><td>'.$rustown.'</td><td>����� '.$elf['kol'].' ��� �� '.$elf['summa'].' ���.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('medium','');
	//5 ����� ���������� ����
	PrintTable('cell_open','5 ����� ���������� ����');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=14 AND substat_id=4 ORDER BY summa DESC");
	while($elf=mysql_fetch_array($game))
	{
		$rustown = @mysqlresult(@myquery("SELECT rustown FROM gamerpgsu.game_gorod WHERE town = '".$elf['town_id']."'"),0,0);
		$name = @mysqlresult(@myquery("SELECT item FROM gamerpgsu.game_tavern_shop WHERE id = '".$elf['eat_id']."'"),0,0);
		echo'<tr><td><b>'.$name.'</b></td><td>'.$rustown.'</td><td>����� '.$elf['kol'].' ��� �� '.$elf['summa'].' ���.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==10)
{
	DbConnectStat();
	PrintTable('open','');
	//5 ����� ���������� ������
	PrintTable('cell_open','5 ����� ���������� ������');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=9 AND substat_id=1 ORDER BY kol DESC");
	while($elf=mysql_fetch_array($game))
	{
		$rustown = @mysqlresult(@myquery("SELECT rustown FROM gamerpgsu.game_gorod WHERE town = '".$elf['town_id']."'"),0,0);
		echo'<tr><td><b>'.$rustown.'</b></td><td>������� '.$elf['kol'].' ���������</td><td>�� '.$elf['summa'].' ���.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('medium','');
	//5 ����� ���������� ������
	PrintTable('cell_open','5 ����� ���������� ������');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=9 AND substat_id=2 ORDER BY summa DESC");
	while($elf=mysql_fetch_array($game))
	{
		$rustown = @mysqlresult(@myquery("SELECT rustown FROM gamerpgsu.game_gorod WHERE town = '".$elf['town_id']."'"),0,0);
		echo'<tr><td><b>'.$rustown.'</b></td><td>������� '.$elf['kol'].' ���������</td><td>�� '.$elf['summa'].' ���.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==10)
{
	DbConnectStat();
	PrintTable('open','');
	//5 ����� ���������� ���������
	PrintTable('cell_open','5 ����� ���������� ���������');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=9 AND substat_id=3 ORDER BY kol DESC");
	while($elf=mysql_fetch_array($game))
	{
		$sel1 = myquery("SELECT name,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."'");
		if (!mysql_num_rows($sel1))
		$sel1 = myquery("SELECT name,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."'");
		if (mysql_num_rows($sel1))
		{
			$usr = mysql_fetch_array($sel1);
			echo'<tr><td><b>'.$usr['name'].'</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.img_domain.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>������� '.$elf['kol'].' ���������</td><td>�� '.$elf['summa'].' ���.</td></tr>';
		}
	}
	PrintTable('cell_close','');
	PrintTable('medium','');

	//5 ����� ���������� ���������
	PrintTable('cell_open','5 ����� ���������� ���������');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=9 AND substat_id=4 ORDER BY summa DESC");
	while($elf=mysql_fetch_array($game))
	{
		$sel1 = myquery("SELECT name,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."'");
		if (!mysql_num_rows($sel1))
		$sel1 = myquery("SELECT name,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."'");
		if (mysql_num_rows($sel1))
		{
			$usr = mysql_fetch_array($sel1);
			echo'<tr><td><b>'.$usr['name'].'</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.img_domain.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>������� '.$elf['kol'].' ���������</td><td>�� '.$elf['summa'].' ���.</td></tr>';
		}
	}
	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==10)
{
	DbConnectStat();
	PrintTable('open','');
	//5 ����� ���������� ��������� �� �����
	PrintTable('cell_open','5 ����� ���������� ��������� �� �����');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=9 AND substat_id=5 ORDER BY kol DESC");
	while($elf=mysql_fetch_array($game))
	{
		 echo'<tr><td><b>'.$elf['item_name'].'</b></td><td>������ '.$elf['kol'].' ���</td><td>�� '.$elf['summa'].' ���.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('medium','');

	//5 ����� ���������� ��������� �� �����
	PrintTable('cell_open','5 ����� ������� ��������� �� �����');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=9 AND substat_id=6 ORDER BY summa DESC");
	while($elf=mysql_fetch_array($game))
	{
		echo'<tr><td><b>'.$elf['item_name'].'</b></td><td>������ '.$elf['kol'].' ���</td><td>�� '.$elf['summa'].' ���.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==13)
{
	DbConnectStat();
	PrintTable('open','');
	//5 c���� ���������� ��������� �� ��������
	PrintTable('cell_open','5 c���� ���������� ��������� �� ��������');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=10 AND substat_id=1 ORDER BY kol DESC");
	while($elf=mysql_fetch_array($game))
	{
		$name = mysqlresult(myquery("SELECT name FROM gamerpgsu.game_shop WHERE id='".$elf['shop_id']."'"),0,0);
		echo'<tr><td><b>'.$name.'</b></td><td>������� '.$elf['kol'].' ����.</td><td>�� '.$elf['summa'].' ���.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('medium','');

	//5 ����� ���������� ��������� �� ��������
	PrintTable('cell_open','5 ����� ���������� ��������� �� ��������');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=10 AND substat_id=2 ORDER BY summa DESC");
	while($elf=mysql_fetch_array($game))
	{
		$name = mysqlresult(myquery("SELECT name FROM gamerpgsu.game_shop WHERE id='".$elf['shop_id']."'"),0,0);
		echo'<tr><td><b>'.$name.'</b></td><td>������� '.$elf['kol'].' ����.</td><td>�� '.$elf['summa'].' ���.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==13)
{
	DbConnectStat();
	PrintTable('open','');
	//5 ���������, ������ ����� �������� ��������� (���)
	PrintTable('cell_open','5 ���������, ������ ����� �������� ��������� (���)');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=11 AND substat_id=1 ORDER BY kol DESC");
	while($elf=mysql_fetch_array($game))
	{
		$name = mysqlresult(myquery("SELECT name FROM gamerpgsu.game_shop WHERE id='".$elf['shop_id']."'"),0,0);
		echo'<tr><td><b>'.$name.'</b></td><td>������� '.$elf['kol'].' ����.</td><td>�� '.$elf['summa'].' ���.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('medium','');

	//5 ���������, ������ ����� �������� ��������� (�����)
	PrintTable('cell_open','5 ���������, ������ ����� �������� ��������� (�����)');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=11 AND substat_id=2 ORDER BY summa DESC");
	while($elf=mysql_fetch_array($game))
	{
		$name = mysqlresult(myquery("SELECT name FROM gamerpgsu.game_shop WHERE id='".$elf['shop_id']."'"),0,0);
		echo'<tr><td><b>'.$name.'</b></td><td>������� '.$elf['kol'].' ����.</td><td>�� '.$elf['summa'].' ���.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==13)
{
	DbConnectStat();
	PrintTable('open','');
	//5 ���������, ������ ����� ����������������� ��������� (���)
	PrintTable('cell_open','5 ���������, ������ ����� ����������������� ��������� (���)');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=13 AND substat_id=1 ORDER BY kol DESC");
	while($elf=mysql_fetch_array($game))
	{
		$name = mysqlresult(myquery("SELECT name FROM gamerpgsu.game_shop WHERE id='".$elf['shop_id']."'"),0,0);
		echo'<tr><td><b>'.$name.'</b></td><td>������ '.$elf['kol'].' ����.</td><td>�� '.$elf['summa'].' ���.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('medium','');

	//5 ���������, ������ ����� ����������������� ��������� (�����)
	PrintTable('cell_open','5 ���������, ������ ����� ����������������� ��������� (�����)');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=13 AND substat_id=2 ORDER BY summa DESC");
	while($elf=mysql_fetch_array($game))
	{
		$name = mysqlresult(myquery("SELECT name FROM gamerpgsu.game_shop WHERE id='".$elf['shop_id']."'"),0,0);
		echo'<tr><td><b>'.$name.'</b></td><td>������ '.$elf['kol'].' ����.</td><td>�� '.$elf['summa'].' ���.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==13)
{
	DbConnectStat();
	PrintTable('open','');
	//5 ���������, ������ ����� ���������������� ��������� (���)
	PrintTable('cell_open','5 ���������, ������ ����� ���������������� ��������� (���)');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=12 AND substat_id=1 ORDER BY kol DESC");
	while($elf=mysql_fetch_array($game))
	{
		$name = mysqlresult(myquery("SELECT name FROM gamerpgsu.game_shop WHERE id='".$elf['shop_id']."'"),0,0);
		echo'<tr><td><b>'.$name.'</b></td><td>�����. '.$elf['kol'].' ����.</td><td>�� '.$elf['summa'].' ���.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('medium','');

	//5 ���������, ������ ����� ���������������� ��������� (�����)
	PrintTable('cell_open','5 ���������, ������ ����� ���������������� ��������� (�����)');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=12 AND substat_id=2 ORDER BY summa DESC");
	while($elf=mysql_fetch_array($game))
	{
		$name = mysqlresult(myquery("SELECT name FROM gamerpgsu.game_shop WHERE id='".$elf['shop_id']."'"),0,0);
		echo'<tr><td><b>'.$name.'</b></td><td>�����. '.$elf['kol'].' ����.</td><td>�� '.$elf['summa'].' ���.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==17)
{
	DbConnectStat();
	PrintTable('open','');
	//5 �������, ������ ����� �������� ��������� (���)
	PrintTable('cell_open','5 �������, ������ ����� �������� ��������� (���)');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=10 AND substat_id=3 ORDER BY kol DESC");
	while($elf=mysql_fetch_array($game))
	{
		$usr = mysql_fetch_array(myquery("(SELECT name,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."') UNION (SELECT name,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."')"));
		echo'<tr><td><b>'.$usr['name'].'</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.img_domain.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>����� '.$elf['kol'].' ����.</td><td>�� '.$elf['summa'].' ���.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('medium','');

	//5 �������, ������ ����� �������� ��������� (�����)
	PrintTable('cell_open','5 �������, ������ ����� �������� ��������� (�����)');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=10 AND substat_id=4 ORDER BY summa DESC");
	while($elf=mysql_fetch_array($game))
	{
		$usr = mysql_fetch_array(myquery("(SELECT name,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."') UNION (SELECT name,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."')"));
		echo'<tr><td><b>'.$usr['name'].'</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.img_domain.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>����� '.$elf['kol'].' ����.</td><td>�� '.$elf['summa'].' ���.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==17)
{
	PrintTable('open','');
	//5 �������, ������ ����� ��������� ��������� (���)
	PrintTable('cell_open','5 �������, ������ ����� ��������� ��������� (���)');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=11 AND substat_id=3 ORDER BY kol DESC");
	while($elf=mysql_fetch_array($game))
	{
		$usr = mysql_fetch_array(myquery("(SELECT name,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."') UNION (SELECT name,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."')"));
		echo'<tr><td><b>'.$usr['name'].'</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.img_domain.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>������ '.$elf['kol'].' ����.</td><td>�� '.$elf['summa'].' ���.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('medium','');

	//5 �������, ������ ����� ��������� ��������� (�����)
	PrintTable('cell_open','5 �������, ������ ����� ��������� ��������� (�����)');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=11 AND substat_id=4 ORDER BY summa DESC");
	while($elf=mysql_fetch_array($game))
	{
		$usr = mysql_fetch_array(myquery("(SELECT name,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."') UNION (SELECT name,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."')"));
		echo'<tr><td><b>'.$usr['name'].'</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.img_domain.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>������ '.$elf['kol'].' ����.</td><td>�� '.$elf['summa'].' ���.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==17)
{
	DbConnectStat();
	PrintTable('open','');
	//5 �������, ������ ����� ������������� ��������� (���)
	PrintTable('cell_open','5 �������, ������ ����� ������������� ��������� (���)');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=13 AND substat_id=3 ORDER BY kol DESC");
	while($elf=mysql_fetch_array($game))
	{
		$usr = mysql_fetch_array(myquery("(SELECT name,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."') UNION (SELECT name,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."')"));
		echo'<tr><td><b>'.$usr['name'].'</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.img_domain.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>������ '.$elf['kol'].' ����.</td><td>�� '.$elf['summa'].' ���.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('medium','');

	//5 �������, ������ ����� ������������� ��������� (�����)
	PrintTable('cell_open','5 �������, ������ ����� ������������� ��������� (�����)');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=13 AND substat_id=4 ORDER BY summa DESC");
	while($elf=mysql_fetch_array($game))
	{
		$usr = mysql_fetch_array(myquery("(SELECT name,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."') UNION (SELECT name,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."')"));
		echo'<tr><td><b>'.$usr['name'].'</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.img_domain.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>������ '.$elf['kol'].' ����.</td><td>�� '.$elf['summa'].' ���.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==17)
{
	DbConnectStat();
	PrintTable('open','');
	//5 �������, ������ ����� ���������������� ��������� (���)
	PrintTable('cell_open','5 �������, ������ ����� ���������������� ��������� (���)');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=12 AND substat_id=3 ORDER BY kol DESC");
	while($elf=mysql_fetch_array($game))
	{
		$usr = mysql_fetch_array(myquery("(SELECT name,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."') UNION (SELECT name,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."')"));
		echo'<tr><td><b>'.$usr['name'].'</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.img_domain.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>�����. '.$elf['kol'].' ����.</td><td>�� '.$elf['summa'].' ���.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('medium','');

	//5 �������, ������ ����� ���������������� ��������� (�����)
	PrintTable('cell_open','5 �������, ������ ����� ���������������� ��������� (�����)');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=12 AND substat_id=4 ORDER BY summa DESC");
	while($elf=mysql_fetch_array($game))
	{
		$usr = mysql_fetch_array(myquery("(SELECT name,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."') UNION (SELECT name,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."')"));
		echo'<tr><td><b>'.$usr['name'].'</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.img_domain.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>�����. '.$elf['kol'].' ����.</td><td>�� '.$elf['summa'].' ���.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==21)
{
	DbConnectStat();
	PrintTable('open','');
	//5 �������, ���� ����� ���������� �� ������ �������
	PrintTable('cell_open','5 �������, ���� ����� ���������� �� ������ �������');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=16 AND substat_id=1 ORDER BY kol DESC");
	while($elf=mysql_fetch_array($game))
	{
		$usr = @mysql_fetch_array(@myquery("(SELECT name,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."') UNION (SELECT name,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."')"));
		echo'<tr><td><b>'.$usr['name'].'</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.img_domain.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>��������� - '.$elf['kol'].' ���.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('medium','');

	//5 �������, �� ������� ���� ����� ��������
	PrintTable('cell_open','5 �������, �� ������� ���� ����� ��������');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=16 AND substat_id=2 ORDER BY kol DESC");
	while($elf=mysql_fetch_array($game))
	{
		$usr = @mysql_fetch_array(@myquery("(SELECT name,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."') UNION (SELECT name,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."')"));
		echo'<tr><td><b>'.$usr['name'].'</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.img_domain.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>������ - '.$elf['kol'].' ���.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==21)
{
	DbConnectStat();
	PrintTable('open','');
	//5 �������, ���� ����� ������������ ����� � �������
	PrintTable('cell_open','5 �������, ���� ����� ������������ ����� � �������');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=7 AND substat_id=1 ORDER BY kol DESC");
	while($elf=mysql_fetch_array($game))
	{
		$usr = @mysql_fetch_array(@myquery("(SELECT name,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."') UNION (SELECT name,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."')"));
		echo'<tr><td><b>'.$usr['name'].'</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.img_domain.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>������� ����� - '.$elf['kol'].' ���.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('medium','');

	//5 �������, ���� ����� ������������� ������ �������
	PrintTable('cell_open','5 �������, ���� ����� ������������� ������ �������');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=6 AND substat_id=1 ORDER BY kol DESC");
	while($elf=mysql_fetch_array($game))
	{
		$usr = @mysql_fetch_array(@myquery("(SELECT name,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."') UNION (SELECT name,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."')"));
		echo'<tr><td><b>'.$usr['name'].'</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.img_domain.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>�������� ����� - '.$elf['kol'].' ���.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==21)
{
	DbConnectStat();
	PrintTable('open','');
	//5 ������, ���� ����� ������������ ����� � �������
	PrintTable('cell_open','5 ������, ���� ����� ������������ ����� � �������');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=6 AND substat_id=2 ORDER BY kol DESC");
	while($elf=mysql_fetch_array($game))
	{
		$name = mysqlresult(myquery("(SELECT nazv FROM gamerpgsu.game_clans WHERE clan_id='".$elf['clan_id']."')"),0,0);
		echo'<tr><td><b><img src="http://'.img_domain.'/clan/'.$elf['clan_id'].'.gif">'.$name.'</b></td><td>���� ������� ����� - '.$elf['kol'].' ���.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('medium','');

	//5 ������, ���� ����� ������������� ������ �������
	PrintTable('cell_open','5 ������, ���� ����� ������������� ������ �������');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=7 AND substat_id=2 ORDER BY kol DESC");
	while($elf=mysql_fetch_array($game))
	{
		$name = mysqlresult(myquery("(SELECT nazv FROM gamerpgsu.game_clans WHERE clan_id='".$elf['clan_id']."')"),0,0);
		echo'<tr><td><b><img src="http://'.img_domain.'/clan/'.$elf['clan_id'].'.gif">'.$name.'</b></td><td>���� �������� ����� - '.$elf['kol'].' ���.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==21)
{
	DbConnectStat();
	PrintTable('open','');
	//5 �������, ������ ����� ������������ ����� � ����
	PrintTable('cell_open','5 �������, ������ ����� ������������ ����� � ����');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=7 AND substat_id=3 ORDER BY summa DESC");
	while($elf=mysql_fetch_array($game))
	{
		$usr = @mysql_fetch_array(@myquery("(SELECT name,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."') UNION (SELECT name,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."')"));
		echo'<tr><td><b>'.$usr['name'].'</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.img_domain.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>������� - '.$elf['kol'].' ���.</td><td>������� - '.$elf['summa'].' �����.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('medium','');

	//5 �������, ������ ����� ������������ ������ � ����
	PrintTable('cell_open','5 �������, ������ ����� ������������ ������ � ����');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=7 AND substat_id=4 ORDER BY summa DESC");
	while($elf=mysql_fetch_array($game))
	{
		$usr = @mysql_fetch_array(@myquery("(SELECT name,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."') UNION (SELECT name,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."')"));
		echo'<tr><td><b>'.$usr['name'].'</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.img_domain.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>������� - '.$elf['kol'].' ���.</td><td>��������� - '.$elf['summa'].' ���.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==21)
{
	DbConnectStat();
	PrintTable('open','');
	//5 �������, ������ ����� ������������ ���� � ������� �������� ������
	PrintTable('cell_open','5 �������, ������ ����� ������������ ���� � ������� �������� ������');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=7 AND substat_id=5 ORDER BY kol DESC");
	while($elf=mysql_fetch_array($game))
	{
		$usr = @mysql_fetch_array(@myquery("(SELECT name,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."') UNION (SELECT name,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."')"));
		echo'<tr><td><b>'.$usr['name'].'</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.img_domain.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>������� - '.$elf['kol'].' ���.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('medium','');

	//5 �������, ������ ����� ������������ ���� � ������� �������� ������
	PrintTable('cell_open','5 �������, ������ ����� ������������ ���� � ������� �������� ������');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=7 AND substat_id=6 ORDER BY kol DESC");
	while($elf=mysql_fetch_array($game))
	{
		$usr = @mysql_fetch_array(@myquery("(SELECT name,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."') UNION (SELECT name,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."')"));
		echo'<tr><td><b>'.$usr['name'].'</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.img_domain.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>������� - '.$elf['kol'].' ���.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==21)
{
	DbConnectStat();
	PrintTable('open','');
	//5 �������, ������ ����� ����������� ���� ������� �������� ������
	PrintTable('cell_open','5 �������, ������ ����� ����������� ���� ������� �������� ������');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=6 AND substat_id=3 ORDER BY kol DESC");
	while($elf=mysql_fetch_array($game))
	{
		$usr = @mysql_fetch_array(@myquery("(SELECT name,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."') UNION (SELECT name,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."')"));
		echo'<tr><td><b>'.$usr['name'].'</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.img_domain.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>�������� - '.$elf['kol'].' ���.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('medium','');

	//5 �������, ������ ����� ����������� ���� ������� �������� ������
	PrintTable('cell_open','5 �������, ������ ����� ����������� ���� ������� �������� ������');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=6 AND substat_id=4");
	while($elf=mysql_fetch_array($game))
	{
		$usr = @mysql_fetch_array(@myquery("(SELECT name,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."') UNION (SELECT name,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."')"));
		echo'<tr><td><b>'.$usr['name'].'</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.img_domain.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>�������� - '.$elf['kol'].' ���.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('close','');
}

if ($vid==21)
{
	DbConnectStat();
	PrintTable('open','');
	//5 �������, ���� ����� ���������� �� ���� �� �����
	PrintTable('cell_open','5 �������, ���� ����� ���������� �� ���� �� �����');
	$game=myquery("SELECT * FROM game_stat_view WHERE stat_id=3");
	while($elf=mysql_fetch_array($game))
	{
		$usr = @mysql_fetch_array(@myquery("(SELECT name,clan_id FROM gamerpgsu.game_users WHERE user_id='".$elf['user_id']."') UNION (SELECT name,clan_id FROM gamerpgsu.game_users_archive WHERE user_id='".$elf['user_id']."')"));
		echo'<tr><td><b>'.$usr['name'].'</b> ';
			if($usr['clan_id']!=0) {
				echo'<img src="http://'.img_domain.'/clan/'.$usr['clan_id'].'.gif" border=0>';
			}
			echo'</td><td>������� �� �������� - '.$elf['kol'].' ���.</td></tr>';
	}
	PrintTable('cell_close','');
	PrintTable('medium','');
	PrintTable('close','');
}

echo '</center><br>';
}


if (function_exists("save_debug")) save_debug(); 
/*
?>