<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['gorod'] >= 1)
{
echo '<center>����������� �������:<br>';

if (isset($_GET['new']))
{
	if (!isset($save))
	{
		echo'<form action="" method="post"><table border=0>
		<tr><td>��������:</td><td><input type=text name=town value=""></td></tr>
		<tr><td>������� ��������:</td><td><input type=text name=rustown value=""></td></tr>
		<tr><td>����� �����:</td><td><input type=text name=vhod value="" size=99></td></tr>
		<tr><td>������ ��� �����:</td><td><input type=text name=clan value="" size=3></td></tr>
		<tr><td>������ ��� ����:</td><td>
		<select name=race><option value=0></option>';
		$selrace = myquery("SELECT * FROM game_har WHERE disable=0");
		while ($race = mysql_fetch_array($selrace))
		{
			echo '<option value='.$race['id'].'>'.$race['name'].'</option>';
		}
		echo '</select></td></tr>
		<tr><td>�������� ����� ������:</td><td><textarea name=opis cols=70 class=input rows=4></textarea></td></tr>

		<tr><td>����:</td><td><input type=text name=color value=""></td></tr>

		<tr><td>�����:</td><td><textarea name=style cols=70 class=input rows=8></textarea></td></tr>
		<tr><td>������ ������:</td><td><textarea name=center cols=70 class=input rows=20></textarea></td></tr>';

		echo'<tr><td>�����:</td><td><select name="options[]" size=10 multiple><option value="0">��� �����</option>';
		$selopt=myquery("select * from game_gorod_option order by name");
		while ($opt=mysql_fetch_array($selopt))
		{
			echo '<option value='.$opt['id'].'>'.$opt['name'].'</option>';
		}
		echo '</select></td></tr>';

		echo '<tr><td>�������:</td><td><textarea name="news" cols=70 class=input rows=5></textarea></td></tr>


		<tr><td></td></tr>
		<tr><td><center><font color=ff0000 size=2 face=verdana>�������������� ������� � ������</font></center></td></tr>';


		echo'<tr><td align=right>����</td><td><input name="STR" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>���������</td><td><input name="NTL" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>��������</td><td><input name="PIE" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>������</td><td><input name="VIT" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>������������</td><td><input name="DEX" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>��������</td><td><input name="SPD" type="checkbox" value="1"><td></td></tr>

		<tr><td></td></tr>';
	
		echo '<tr><td></td></tr>';
		echo'<tr><td><center><font color=ff0000 size=2 face=verdana>������������� ������� � ������</font></center></td>
		<td><select name="skills[]" size=10 multiple>';
		$selskills=myquery("select * from game_skills order by sgroup desc, name");
		while ($skl=mysql_fetch_array($selskills))
		{
			echo '<option value='.$skl['id'];			
			echo '>'.$skl['name'].'</option>';
		}
		echo'</select></td></tr>';
		
			echo'<tr><td><center><font color=ff0000 size=2 face=verdana>� ����� ����� ��������</font></center></td></tr>';

		echo'<tr><td align=right>�����</td><td><input name="enter_elf" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>����</td><td><input name="enter_orc" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>�������</td><td><input name="enter_nazgul" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>�������</td><td><input name="enter_hobbit" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>����</td><td><input name="enter_human" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>�����</td><td><input name="enter_gnome" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>�������</td><td><input name="enter_goblin" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>������</td><td><input name="enter_troll" type="checkbox" value="1"><td></td></tr>';

		echo'<tr><td><center><font color=ff0000 size=2 face=verdana>� ������ ���� ��������� ��������</font></center></td></tr>';

		echo'<tr><td align=right>�����</td><td><input name="torg_elf" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>����</td><td><input name="torg_orc" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>�������</td><td><input name="torg_nazgul" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>�������</td><td><input name="torg_hobbit" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>����</td><td><input name="torg_human" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>�����</td><td><input name="torg_gnome" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>�������</td><td><input name="torg_goblin" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>������</td><td><input name="torg_troll" type="checkbox" value="1"><td></td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td align=right>��� ������</td><td>
		<select name="side1">
		<option value="0">������ �����</option>
		<option value="1">������� �����</option>
		<option value="2" selected>����������� �����</option>
		</td></tr>';

		echo'<tr><td align=right>����� � view.rpg.su</td><td><input name="view" type="checkbox" value="1"></td></tr>';

		echo'<tr><td colspan=2 align=center><input name="save" type="submit" value="��������"><input name="save" type="hidden" value=""></td></tr>';
		echo'</table></form>';
	}
	else
	{
		echo'����� ��������';
		if (isset($view) and $view=='1') $v='1';
		if (!isset($view)) $v='0';

		if (isset($enter_elf) and $enter_elf=='1') $enter_elf1='1';
		if (!isset($enter_elf)) $enter_elf1='0';
		if (isset($enter_orc) and $enter_orc=='1') $enter_orc1='1';
		if (!isset($enter_orc)) $enter_orc1='0';
		if (isset($enter_nazgul) and $enter_nazgul=='1') $enter_nazgul1='1';
		if (!isset($enter_nazgul)) $enter_nazgul1='0';
		if (isset($enter_hobbit) and $enter_hobbit=='1') $enter_hobbit1='1';
		if (!isset($enter_hobbit)) $enter_hobbit1='0';
		if (isset($enter_human) and $enter_human=='1') $enter_human1='1';
		if (!isset($enter_human)) $enter_human1='0';
		if (isset($enter_gnome) and $enter_gnome=='1') $enter_gnome1='1';
		if (!isset($enter_gnome)) $enter_gnome1='0';
		if (isset($enter_troll) and $enter_troll=='1') $enter_troll1='1';
		if (!isset($enter_troll)) $enter_troll1='0';
		if (isset($enter_goblin) and $enter_goblin=='1') $enter_goblin1='1';
		if (!isset($enter_goblin)) $enter_goblin1='0';


		if (isset($torg_elf) and $torg_elf=='1') $torg_elf1='1';
		if (!isset($torg_elf)) $torg_elf1='0';
		if (isset($torg_orc) and $torg_orc=='1') $torg_orc1='1';
		if (!isset($torg_orc)) $torg_orc1='0';
		if (isset($torg_nazgul) and $torg_nazgul=='1') $torg_nazgul1='1';
		if (!isset($torg_nazgul)) $torg_nazgul1='0';
		if (isset($torg_hobbit) and $torg_hobbit=='1') $torg_hobbit1='1';
		if (!isset($torg_hobbit)) $torg_hobbit1='0';
		if (isset($torg_human) and $torg_human=='1') $torg_human1='1';
		if (!isset($torg_human)) $torg_human1='0';
		if (isset($torg_gnome) and $torg_gnome=='1') $torg_gnome1='1';
		if (!isset($torg_gnome)) $torg_gnome1='0';
		if (isset($torg_troll) and $torg_troll=='1') $torg_troll1='1';
		if (!isset($torg_troll)) $torg_troll1='0';
		if (isset($torg_goblin) and $torg_goblin=='1') $torg_goblin1='1';
		if (!isset($torg_goblin)) $torg_goblin1='0';

		if (isset($STR) and $STR=='1') $STR1='1';
		if (!isset($STR)) $STR1='0';

		if (isset($NTL) and $NTL=='1') $NTL1='1';
		if (!isset($NTL)) $NTL1='0';

		if (isset($PIE) and $PIE=='1') $PIE1='1';
		if (!isset($PIE)) $PIE1='0';

		if (isset($VIT) and $VIT=='1') $VIT1='1';
		if (!isset($VIT)) $VIT1='0';

		if (isset($DEX) and $DEX=='1') $DEX1='1';
		if (!isset($DEX)) $DEX1='0';

		if (isset($SPD) and $SPD=='1') $SPD1='1';
		if (!isset($SPD)) $SPD1='0';		

		$update=myquery("INSERT INTO game_gorod (name,rustown,clan,race,vhod,opis,style,color,center,news,STR,NTL,PIE,VIT,DEX,SPD,view,torg_Elf,torg_Orc,torg_Nazgul,torg_Hobbit,torg_Human,torg_Gnom,torg_Goblin,torg_Troll,enter_Elf,enter_Orc,enter_Nazgul,enter_Hobbit,enter_Human,enter_Gnom,enter_Goblin,enter_Troll,side)
		VALUES ('$town','$rustown','$clan','$race','$vhod','$opis','$style','$color','$center','$news','$STR1','$NTL1','$PIE1','$VIT1','$DEX1','$SPD1','$v','$torg_elf1','$torg_orc1','$torg_nazgul1','$torg_hobbit1','$torg_human1','$torg_gnome1','$torg_goblin1','$torg_troll1','$enter_elf1','$enter_orc1','$enter_nazgul1','$enter_hobbit1','$enter_human1','$enter_gnome1','$enter_goblin1','$enter_troll1','$side1')");

		$gorod_id = mysql_insert_id();
		if (isset($_REQUEST["options"]))
		{
			for ($i=0; $i<count($_REQUEST["options"]); $i++)
			{
				if ($_REQUEST["options"][$i]>0)
				{
					myquery("INSERT INTO game_gorod_set_option (gorod_id,option_id) VALUES ($gorod_id,'".$_REQUEST["options"][$i]."')");
				}
			}
		}
		if (isset($_REQUEST["skills"]))
		{
			for ($i=0; $i<count($_REQUEST["skills"]); $i++)
			{
				if ($_REQUEST["skills"][$i]>0)
				{
					myquery("INSERT INTO game_gorod_skills (gorod_id,skill_id) VALUES ($gorod_id,'".$_REQUEST["skills"][$i]."')");
				}
			}
		}
	   
		$da = getdate();
		$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
		 VALUES (
		 '".$char['name']."',
		 '������� ����� �����: <b>".$rustown."(".$town.")</b>',
		 '".time()."',
		 '".$da['mday']."',
		 '".$da['mon']."',
		 '".$da['year']."')")
			 or die(mysql_error());
	 echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=gorod">';
	}
}


if (isset($del_g))
{
	echo'����� ������<br><br>';
		$rustown = mysql_result(myquery("SELECT rustown FROM game_gorod WHERE town='$del_g'"),0,0);
		$da = getdate();
		$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
		 VALUES (
		 '".$char['name']."',
		 '������ �����: <b>".$rustown."</b>',
		 '".time()."',
		 '".$da['mday']."',
		 '".$da['mon']."',
		 '".$da['year']."')")
			 or die(mysql_error());
	$update=myquery("delete from game_gorod where town='$del_g'");
	 echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=gorod">';
}

if (isset($del_p))
{
	echo'������ ������<br><br>';
		$da = getdate();
		$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
		 VALUES (
		 '".$char['name']."',
		 '������ ������: <b>".$del_p."</b>',
		 '".time()."',
		 '".$da['mday']."',
		 '".$da['mon']."',
		 '".$da['year']."')")
			 or die(mysql_error());
	$update=myquery("delete from game_obj where town='$del_p'");
	 echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=gorod">';
}


if (isset($_GET['edit']))
{
	if (!isset($_POST['save']))
	{

		$sel=myquery("select * from game_gorod where town='".$_GET['edit']."'");
		$shop=mysql_fetch_array($sel);

		echo'<br><style type="text/css">'.$shop['style'].'</style>'.$shop['center'].'<br><br>';



		echo'<form action="" method="post"><table border=0>
		<tr><td>��������:</td><td><input type=text name=town value='.$shop['name'].'></td></tr>
		<tr><td>������� ��������:</td><td><input type=text name=rustown value="'.$shop['rustown'].'"></td></tr>
		<tr><td>����� �����:</td><td><input type=text name=vhod value="'.$shop['vhod'].'" size=99></td></tr>
		<tr><td>������ ��� �����:</td><td><input type=text name=clan value="'.$shop['clan'].'" size=3></td></tr>
		<tr><td>������ ��� ����:</td><td>';
		echo '<select name=race><option value=0></option>';
		$selrace = myquery("SELECT * FROM game_har WHERE disable=0");
		while ($race = mysql_fetch_array($selrace))
		{
			echo '<option value='.$race['id'].'';
			if ($race['id']==$shop['race']) echo ' selected';
			echo '>'.$race['name'].'</option>';
		}
		echo '</select><tr><td>�������� ����� ������:</td><td><textarea name=opis cols=70 class=input rows=4>'.$shop['opis'].'</textarea></td></tr>
		<tr><td>����:</td><td><input type=text name=color value='.$shop['color'].'></td></tr>

		<tr><td>�����:</td><td><textarea name=style cols=70 class=input rows=8>'.$shop['style'].'</textarea></td></tr>
		<tr><td>������ ������:</td><td><textarea name=center cols=70 class=input rows=20>'.$shop['center'].'</textarea></td></tr>';

		echo'<tr><td>�����:</td><td><select name="options[]" size=10 multiple><option value="0">��� �����</option>';
		$selopt=myquery("select * from game_gorod_option order by name");
		while ($opt=mysql_fetch_array($selopt))
		{
			echo '<option value='.$opt['id'];
			$check = myquery("SELECT * FROM game_gorod_set_option WHERE option_id='".$opt['id']."' AND gorod_id='".$_GET['edit']."'");
			if (mysql_num_rows($check)) echo ' selected';
			echo '>'.$opt['name'].'</option>';
		}
		echo'</select></td></tr>';
		echo'<tr><td>�������:</td><td><textarea name="news" cols=70 class=input rows=5>'.$shop['news'].'</textarea></td></tr>

		<tr><td>���� � ������:</td><td>
		<select name="game">';

			if ($shop['game_file']!='') echo '<option value="'.$shop['game_file'].'" selected>'.$shop['game_name'].'</option>';

			echo'
			<option value="arcanoid.swf">�������� (102940 ����)</option>
			<option value="battleships.swf">������� ��� (377909 ����)</option>
			<option value="cubebuster.swf">������������ ������ (317946 ����)</option>
			<option value="gyroball.swf">������� (102128 ����)</option>
			<option value="IQtest.swf">IQ ���� (474307 ����)</option>
			<option value="mahjonggwm.swf">Mahjongg (276634 ����)</option>
			<option value="Solitaire.swf">������� (196304 ����)</option>
			<option value="Tetris.swf">������ (455273 ����)</option>
			<option value="AirFight.SWF">��������� ��� (121339 ����)</option>
			<option value="Ball.SWF">�������� 2 (40387 ����)</option>
			<option value="Bandit.SWF">��������� ������ (56445 ����)</option>
			<option value="Bart Fignt.SWF">�������� ��������� (41966 ����)</option>
			<option value="Bobbi.SWF">������ ����� (97935 ����)</option>
			<option value="Burnsie.SWF">����� �������� �� ������������ ������� (33770 ����)</option>
			<option value="Busy.SWF">����������� ������ (118111 ����)</option>
			<option value="Car 1.SWF">����� COTSEY&nbsp;SPY&nbsp;HUNTER (170318 ����)</option>
			<option value="Car 2.SWF">����� (149833 ����)</option>
			<option value="Catogochi.SWF">��������� (111780 ����)</option>
			<option value="Colpac.SWF">��������� 2 (33563 ����)</option>
			<option value="Crystal.SWF">������ ���������� (147833 ����)</option>
			<option value="Fight.swf">��������� (88237 ����)</option>
			<option value="Flying Elefant.SWF">�������� ������� (120501 ����)</option>
			<option value="Football.SWF">������ (45590 ����)</option>
			<option value="Golf.SWF">����� (107373 ����)</option>
			<option value="Gomer.SWF">���� ������� (15013 ����)</option>
			<option value="Gruppa.SWF">����������� ����-���� (234886 ����)</option>
			<option value="Hunting.SWF">����� (114235 ����)</option>
			<option value="Japan.SWF">�������� ���� (318352 ����)</option>
			<option value="Nery Pout.SWF">������� ��� ���� � �������� (113892 ����)</option>
			<option value="Oracul.SWF">������� � �������� (66931 ����)</option>
			<option value="Paceman 1.SWF">���-��� 1 (116408 ����)</option>
			<option value="Paceman 2.SWF">���-��� 2 (42334 ����)</option>
			<option value="Pomni 1.SWF">����������� 1 (58429 ����)</option>
			<option value="Pomni 2.SWF">����������� 2 (62440 ����)</option>
			<option value="Qest.SWF">����� (273175 ����)</option>
			<option value="Ruletca.SWF">������� (121298 ����)</option>
			<option value="Safari.SWF">������ (233070 ����)</option>
			<option value="Sciner.SWF">����� �������� (157317 ����)</option>
			<option value="SeaFight.SWF">������� ��� 2 (160872 ����)</option>
			<option value="Shooter.SWF">��������� (2023776 ����)</option>
			<option value="Sniper.SWF">������� (77745 ����)</option>
			<option value="Socks.SWF">Socks (261310 ����)</option>
			<option value="Tetris 2.SWF">������ 3 (31743 ����)</option>
			<option value="Tetris 3.SWF">������ 4 (146976 ����)</option>
			<option value="Worm.SWF">������ (71846 ����)</option>
			<option value="XvsO.SWF">��������-������ (81695 ����)</option>
			</select>
		</td></tr>

		<tr><td></td></tr>
		<tr><td><center><font color=ff0000 size=2 face=verdana>�������������� ������� � ������</font></center></td></tr>';

		echo'<tr><td align=right>����</td><td><input name="STR" type="checkbox" value="1"'; if($shop['STR']==1) echo'checked'; echo'></td></tr>';
		echo'<tr><td align=right>���������</td><td><input name="NTL" type="checkbox" value="1"'; if($shop['NTL']==1) echo'checked'; echo'></td></tr>';
		echo'<tr><td align=right>��������</td><td><input name="PIE" type="checkbox" value="1"'; if($shop['PIE']==1) echo'checked'; echo'></td></tr>';
		echo'<tr><td align=right>������</td><td><input name="VIT" type="checkbox" value="1"'; if($shop['VIT']==1) echo'checked'; echo'></td></tr>';
		echo'<tr><td align=right>������������</td><td><input name="DEX" type="checkbox" value="1"'; if($shop['DEX']==1) echo'checked'; echo'></td></tr>';
		echo'<tr><td align=right>��������</td><td><input name="SPD" type="checkbox" value="1"'; if($shop['SPD']==1) echo'checked'; echo'></td></tr>

		<tr><td></td></tr>';		
		
		echo'<tr><td><center><font color=ff0000 size=2 face=verdana>������������� ������� � ������</font></center></td>
		<td><select name="skills[]" size=10 multiple>';
		$selskills=myquery("select * from game_skills order by sgroup DESC, name");
		while ($skl=mysql_fetch_array($selskills))
		{
			echo '<option value='.$skl['id'];
			$check = myquery("SELECT * FROM game_gorod_skills WHERE skill_id='".$skl['id']."' AND gorod_id='".$_GET['edit']."';");
			if (mysql_num_rows($check)) echo ' selected';
			echo '>'.$skl['name'].'</option>';
		}
		echo'</select></td></tr>';

		echo'
		<tr><td><center><font color=ff0000 size=2 face=verdana>� ����� ����� ��������</font></center></td></tr>';

		echo'<tr><td align=right>�����</td><td><input name="enter_elf" type="checkbox" value="1"'; if($shop['enter_Elf']==1) echo'checked'; echo'><td></td></tr>';
		echo'<tr><td align=right>����</td><td><input name="enter_orc" type="checkbox" value="1"'; if($shop['enter_Orc']==1) echo'checked'; echo'><td></td></tr>';
		echo'<tr><td align=right>�������</td><td><input name="enter_nazgul" type="checkbox" value="1"'; if($shop['enter_Nazgul']==1) echo'checked'; echo'><td></td></tr>';
		echo'<tr><td align=right>�������</td><td><input name="enter_hobbit" type="checkbox" value="1"'; if($shop['enter_Hobbit']==1) echo'checked'; echo'><td></td></tr>';
		echo'<tr><td align=right>����</td><td><input name="enter_human" type="checkbox" value="1"'; if($shop['enter_Human']==1) echo'checked'; echo'><td></td></tr>';
		echo'<tr><td align=right>�����</td><td><input name="enter_gnome" type="checkbox" value="1"'; if($shop['enter_Gnom']==1) echo'checked'; echo'><td></td></tr>';
		echo'<tr><td align=right>�������</td><td><input name="enter_goblin" type="checkbox" value="1"'; if($shop['enter_Goblin']==1) echo'checked'; echo'><td></td></tr>';
		echo'<tr><td align=right>������</td><td><input name="enter_troll" type="checkbox" value="1"'; if($shop['enter_Troll']==1) echo'checked'; echo'><td></td></tr>';

		echo'<tr><td><center><font color=ff0000 size=2 face=verdana>� ������ ����� ��������� ��������</font></center></td></tr>';

		echo'<tr><td align=right>�����</td><td><input name="torg_elf" type="checkbox" value="1"'; if($shop['torg_Elf']==1) echo'checked'; echo'><td></td></tr>';
		echo'<tr><td align=right>����</td><td><input name="torg_orc" type="checkbox" value="1"'; if($shop['torg_Orc']==1) echo'checked'; echo'><td></td></tr>';
		echo'<tr><td align=right>�������</td><td><input name="torg_nazgul" type="checkbox" value="1"'; if($shop['torg_Nazgul']==1) echo'checked'; echo'><td></td></tr>';
		echo'<tr><td align=right>�������</td><td><input name="torg_hobbit" type="checkbox" value="1"'; if($shop['torg_Hobbit']==1) echo'checked'; echo'><td></td></tr>';
		echo'<tr><td align=right>����</td><td><input name="torg_human" type="checkbox" value="1"'; if($shop['torg_Human']==1) echo'checked'; echo'><td></td></tr>';
		echo'<tr><td align=right>�����</td><td><input name="torg_gnome" type="checkbox" value="1"'; if($shop['torg_Gnom']==1) echo'checked'; echo'><td></td></tr>';
		echo'<tr><td align=right>�������</td><td><input name="torg_goblin" type="checkbox" value="1"'; if($shop['torg_Goblin']==1) echo'checked'; echo'><td></td></tr>';
		echo'<tr><td align=right>������</td><td><input name="torg_troll" type="checkbox" value="1"'; if($shop['torg_Troll']==1) echo'checked'; echo'><td></td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td align=right>��� ������</td><td>
		<select name="side1">
		<option value="0"';if ($shop['side']=='0') echo ' selected'; echo'>������ �����</option>
		<option value="1"';if ($shop['side']=='1') echo ' selected'; echo'>������� �����</option>
		<option value="2"';if ($shop['side']=='2') echo ' selected'; echo'>����������� �����</option>
		</td></tr>';

		echo'<tr><td align=right>����� � view.rpg.su</td><td><input name="view" type="checkbox" value="1"'; if($shop['view']==1) echo'checked'; echo'></td></tr>';

		echo'<tr><td colspan=2 align=center><input name="save" type="submit" value="���������"><input name="save" type="hidden" value=""></td></tr>';


		echo'</table></form>';
		}
		else
		{
		echo'����� �������';

if (isset($_POST['view']) and $_POST['view']=='1') $v='1';
if (!isset($_POST['view'])) $v='0';


		if (isset($_POST['enter_elf']) and $_POST['enter_elf']=='1') $_POST['enter_elf1']='1';
		if (!isset($_POST['enter_elf'])) $_POST['enter_elf1']='0';
		if (isset($_POST['enter_orc']) and $_POST['enter_orc']=='1') $_POST['enter_orc1']='1';
		if (!isset($_POST['enter_orc'])) $_POST['enter_orc1']='0';
		if (isset($_POST['enter_nazgul']) and $_POST['enter_nazgul']=='1') $_POST['enter_nazgul1']='1';
		if (!isset($_POST['enter_nazgul'])) $e_POST['nter_nazgul1']='0';
		if (isset($_POST['enter_hobbit']) and $_POST['enter_hobbit']=='1') $_POST['enter_hobbit1']='1';
		if (!isset($_POST['enter_hobbit'])) $_POST['enter_hobbit1']='0';
		if (isset($_POST['enter_human']) and $_POST['enter_human']=='1') $_POST['enter_human1']='1';
		if (!isset($_POST['enter_human'])) $_POST['enter_human1']='0';
		if (isset($_POST['enter_gnome']) and $_POST['enter_gnome']=='1') $_POST['enter_gnome1']='1';
		if (!isset($_POST['enter_gnome'])) $_POST['enter_gnome1']='0';
		if (isset($_POST['enter_troll']) and $_POST['enter_troll']=='1') $_POST['enter_troll1']='1';
		if (!isset($_POST['enter_troll'])) $_POST['enter_troll1']='0';
		if (isset($_POST['enter_goblin']) and $_POST['enter_goblin']=='1') $_POST['enter_goblin1']='1';
		if (!isset($_POST['enter_goblin'])) $_POST['enter_goblin1']='0';


		if (isset($torg_elf) and $torg_elf=='1') $torg_elf1='1';
		if (!isset($torg_elf)) $torg_elf1='0';
		if (isset($torg_orc) and $torg_orc=='1') $torg_orc1='1';
		if (!isset($torg_orc)) $torg_orc1='0';
		if (isset($torg_nazgul) and $torg_nazgul=='1') $torg_nazgul1='1';
		if (!isset($torg_nazgul)) $torg_nazgul1='0';
		if (isset($torg_hobbit) and $torg_hobbit=='1') $torg_hobbit1='1';
		if (!isset($torg_hobbit)) $torg_hobbit1='0';
		if (isset($torg_human) and $torg_human=='1') $torg_human1='1';
		if (!isset($torg_human)) $torg_human1='0';
		if (isset($torg_gnome) and $torg_gnome=='1') $torg_gnome1='1';
		if (!isset($torg_gnome)) $torg_gnome1='0';
		if (isset($torg_troll) and $torg_troll=='1') $torg_troll1='1';
		if (!isset($torg_troll)) $torg_troll1='0';
		if (isset($torg_goblin) and $torg_goblin=='1') $torg_goblin1='1';
		if (!isset($torg_goblin)) $torg_goblin1='0';


if (isset($STR) and $STR=='1') $STR1='1';
if (!isset($STR)) $STR1='0';

if (isset($NTL) and $NTL=='1') $NTL1='1';
if (!isset($NTL)) $NTL1='0';

if (isset($PIE) and $PIE=='1') $PIE1='1';
if (!isset($PIE)) $PIE1='0';

if (isset($VIT) and $VIT=='1') $VIT1='1';
if (!isset($VIT)) $VIT1='0';

if (isset($DEX) and $DEX=='1') $DEX1='1';
if (!isset($DEX)) $DEX1='0';

if (isset($SPD) and $SPD=='1') $SPD1='1';
if (!isset($SPD)) $SPD1='0';

if (isset($game))
{
		if ($game=="arcanoid.swf")
		{
		$game_file = "arcanoid.swf";
		$game_name="�������� (102940 ����)";
		}
		elseif ($game=="battleships.swf")
		{
		$game_file = "battleships.swf";
		$game_name="������� ��� (377909 ����)";
		}
		elseif ($game=="cubebuster.swf")
		{
		$game_file = "cubebuster.swf";
		$game_name="������������ ������ (317946 ����)";
		}
		elseif ($game=="gyroball.swf")
		{
		$game_file = "gyroball.swf";
		$game_name="������� (102128 ����)";
		}
		elseif ($game=="IQtest.swf")
		{
		$game_file = "IQtest.swf";
		$game_name="IQ ���� (474307 ����)";
		}
		elseif ($game=="mahjonggwm.swf")
		{
		$game_file = "mahjonggwm.swf";
		$game_name="Mahjongg (276634 ����)";
		}
		elseif ($game=="Solitaire.swf")
		{
		$game_file = "Solitaire.swf";
		$game_name="������� (196304 ����)";
		}
		elseif ($game=="Tetris.swf")
		{
		$game_file = "Tetris.swf";
		$game_name="������ (455273 ����)";
		}
		elseif ($game=="AirFight.SWF")
		{
		$game_file = "AirFight.SWF";
		$game_name="��������� ��� (121339 ����)";
		}
		elseif ($game=="Ball.SWF")
		{
		$game_file = "Ball.SWF";
		$game_name="�������� 2 (40387 ����)";
		}
		elseif ($game=="Bandit.SWF")
		{
		$game_file = "Bandit.SWF";
		$game_name="��������� ������ (56445 ����)";
		}
		elseif ($game=="Bart Fignt.SWF")
		{
		$game_file = "Bart Fignt.SWF";
		$game_name="�������� ��������� (41966 ����)";
		}
		elseif ($game=="Bobbi.SWF")
		{
		$game_file = "Bobbi.SWF";
		$game_name="������ ����� (97935 ����)";
		}
		elseif ($game=="Burnsie.SWF")
		{
		$game_file = "Burnsie.SWF";
		$game_name="����� �������� �� ������������ ������� (33770 ����)";
		}
		elseif ($game=="Busy.SWF")
		{
		$game_file = "Busy.SWF";
		$game_name="����������� ������ (118111 ����)";
		}
		elseif ($game=="Car 1.SWF")
		{
		$game_file = "Car 1.SWF";
		$game_name="����� COTSEY&nbsp;SPY&nbsp;HUNTER (170318 ����)";
		}
		elseif ($game=="Car 2.SWF")
		{
		$game_file = "Car 2.SWF";
		$game_name="����� (149833 ����)";
		}
		elseif ($game=="Catogochi.SWF")
		{
		$game_file = "Catogochi.SWF";
		$game_name="��������� (111780 ����)";
		}
		elseif ($game=="Colpac.SWF")
		{
		$game_file = "Colpac.SWF";
		$game_name="��������� 2 (33563 ����)";
		}
		elseif ($game=="Crystal.SWF")
		{
		$game_file = "Crystal.SWF";
		$game_name="������ ���������� (147833 ����)";
		}
		elseif ($game=="Fight.swf")
		{
		$game_file = "Fight.swf";
		$game_name="��������� (88237 ����)";
		}
		elseif ($game=="Flying Elefant.SWF")
		{
		$game_file = "Flying Elefant.SWF";
		$game_name="�������� ������� (120501 ����)";
		}
		elseif ($game=="Football.SWF")
		{
		$game_file = "Football.SWF";
		$game_name="������ (45590 ����)";
		}
		elseif ($game=="Golf.SWF")
		{
		$game_file = "Golf.SWF";
		$game_name="����� (107373 ����)";
		}
		elseif ($game=="Gomer.SWF")
		{
		$game_file = "Gomer.SWF";
		$game_name="���� ������� (15013 ����)";
		}
		elseif ($game=="Gruppa.SWF")
		{
		$game_file = "Gruppa.SWF";
		$game_name="����������� ����-���� (234886 ����)";
		}
		elseif ($game=="Hunting.SWF")
		{
		$game_file = "Hunting.SWF";
		$game_name="����� (114235 ����)";
		}
		elseif ($game=="Japan.SWF")
		{
		$game_file = "Japan.SWF";
		$game_name="�������� ���� (318352 ����)";
		}
		elseif ($game=="Nery Pout.SWF")
		{
		$game_file = "Nery Pout.SWF";
		$game_name="������� ��� ���� � �������� (113892 ����)";
		}
		elseif ($game=="Oracul.SWF")
		{
		$game_file = "Oracul.SWF";
		$game_name="������� � �������� (66931 ����)";
		}
		elseif ($game=="Paceman 1.SWF")
		{
		$game_file = "Paceman 1.SWF";
		$game_name="���-��� 1 (116408 ����)";
		}
		elseif ($game=="Paceman 2.SWF")
		{
		$game_file = "Paceman 2.SWF";
		$game_name="���-��� 2 (42334 ����)";
		}
		elseif ($game=="Pomni 1.SWF")
		{
		$game_file = "Pomni 1.SWF";
		$game_name="����������� 1 (58429 ����)";
		}
		elseif ($game=="Pomni 2.SWF")
		{
		$game_file = "Pomni 2.SWF";
		$game_name="����������� 2 (62440 ����)";
		}
		elseif ($game=="Qest.SWF")
		{
		$game_file = "Qest.SWF";
		$game_name="����� (273175 ����)";
		}
		elseif ($game=="Ruletca.SWF")
		{
		$game_file = "Ruletca.SWF";
		$game_name="������� (121298 ����)";
		}
		elseif ($game=="Safari.SWF")
		{
		$game_file = "Safari.SWF";
		$game_name="������ (233070 ����)";
		}
		elseif ($game=="Sciner.SWF")
		{
		$game_file = "Sciner.SWF";
		$game_name="����� �������� (157317 ����)";
		}
		elseif ($game=="SeaFight.SWF")
		{
		$game_file = "SeaFight.SWF";
		$game_name="������� ��� 2 (160872 ����)";
		}
		elseif ($game=="Shooter.SWF")
		{
		$game_file = "Shooter.SWF";
		$game_name="��������� (2023776 ����)";
		}
		elseif ($game=="Sniper.SWF")
		{
		$game_file = "Sniper.SWF";
		$game_name="������� (77745 ����)";
		}
		elseif ($game=="Socks.SWF")
		{
		$game_file = "Socks.SWF";
		$game_name="Socks (261310 ����)";
		}
		elseif ($game=="Tetris 2.SWF")
		{
		$game_file = "Tetris 2.SWF";
		$game_name="������ 3 (31743 ����)";
		}
		elseif ($game=="Tetris 3.SWF")
		{
		$game_file = "Tetris 3.SWF";
		$game_name="������ 4 (146976 ����)";
		}
		elseif ($game=="Worm.SWF")
		{
		$game_file = "Worm.SWF";
		$game_name="������ (71846 ����)";
		}
		elseif ($game=="XvsO.SWF")
		{
		$game_file = "XvsO.SWF";
		$game_name="��������-������ (81695 ����)";
		}
	else
		{
		$game_file = "";
		$game_name="";
		}
}
else
{
		$game_file = "";
		$game_name="";
}

$up=myquery("update game_gorod set
name='$town',
rustown='$rustown',
vhod='$vhod',
opis='".$opis."',
style='$style',
color='$color',
center='$center',
news='".$news."',
clan='$clan',
race='$race',
view='$v',

STR='$STR1',
NTL='$NTL1',
PIE='$PIE1',
VIT='$VIT1',
DEX='$DEX1',
SPD='$SPD1',

game_file='$game_file',
game_name='$game_name',

torg_Elf='$torg_elf1',
torg_Orc='$torg_orc1',
torg_Nazgul='$torg_nazgul1',
torg_Hobbit='$torg_hobbit1',
torg_Human='$torg_human1',
torg_Gnom='$torg_gnome1',
torg_Goblin='$torg_goblin1',
torg_Troll='$torg_troll1',

side='$side1',

enter_Elf='".$_POST['enter_elf1']."',
enter_Orc='".$_POST['enter_orc1']."',
enter_Nazgul='".$_POST['enter_nazgul1']."',
enter_Hobbit='".$_POST['enter_hobbit1']."',
enter_Human='".$_POST['enter_human1']."',
enter_Gnom='".$_POST['enter_gnome1']."',
enter_Goblin='".$_POST['enter_goblin1']."',
enter_Troll='".$_POST['enter_troll1']."'

where town='".$_GET['edit']."'") or die(mysql_error());

		myquery("DELETE FROM game_gorod_set_option WHERE gorod_id=".$_GET['edit']."");
		if (isset($_REQUEST["options"]))
		{
			for ($i=0; $i<count($_REQUEST["options"]); $i++)
			{
				if ($_REQUEST["options"][$i]>0)
				{
					myquery("INSERT INTO game_gorod_set_option (gorod_id,option_id) VALUES ($edit,'".$_REQUEST["options"][$i]."')");
				}
			}
		}
		myquery("DELETE FROM game_gorod_skills WHERE gorod_id=$edit");
		if (isset($_REQUEST["skills"]))
		{
			for ($i=0; $i<count($_REQUEST["skills"]); $i++)
			{
				if ($_REQUEST["skills"][$i]>0)
				{
					myquery("INSERT INTO game_gorod_skills (gorod_id,skill_id) VALUES ($edit,'".$_REQUEST["skills"][$i]."')");
				}
			}
		}
		$da = getdate();
		$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
		 VALUES (
		 '".$char['name']."',
		 '������� �����: <b>".$rustown."</b>',
		 '".time()."',
		 '".$da['mday']."',
		 '".$da['mon']."',
		 '".$da['year']."')")
			 or die(mysql_error());
	 echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=gorod">';
		}	
}


if(!isset($edit) and !isset($new) and !isset($new_obj) and !isset($edit_obj))
{
	echo'<a href="?opt=main&option=gorod&new">�������� �����</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="?opt=main&option=gorod_option">������������� ����� �������</a></center>';
	echo'<table border=0 width=70% align=center>';
	$sel=myquery("SELECT gg.*, gms.name as map_name, gm.xpos, gm.ypos, (CASE  WHEN rustown = '' THEN 1 ELSE 0 END) as ord FROM game_gorod gg LEFT JOIN game_map gm ON (gg.town = gm.town AND gm.town!=0 AND to_map_name = 0) LEFT JOIN game_maps gms ON gm.name=gms.id ORDER BY ord, binary rustown ASC");	 
	$i=0;
	while($shop=mysql_fetch_array($sel))
	{
		$i++;
		if ($i==1) {echo '<tr bgcolor=#000040>';}
		else {$i=0; echo '<tr bgcolor=#00002B>';};
		echo '<td align="right">'.$shop['name'].'</td>';
		echo '<td align="right">'.$shop['rustown'].'</td>';
		echo '<td align="center">'.$shop['map_name'].' (X-'.$shop['xpos'].', Y-'.$shop['ypos'].')</td>';
		echo '<td><a href="?opt=main&option=gorod&edit='.$shop['town'].'">�������������</a>, <a href="?opt=main&option=gorod&del_g='.$shop['town'].'">�������</a></td></tr>';
	}	
	echo'</table>';
}


echo'<br><hr>';
echo '<center>������� �� �����:<br>';

if (isset($new_obj))
{
	if (!isset($save))
	{
		echo'<form action="" method="post"><table border=0>
		<tr><td>��������:</td><td><input type=text name=town></td></tr>
		<tr><td>������ �����:</td><td><input type=text name=name></td></tr>
		<tr><td>�������� ����� ������:</td><td><textarea name=text cols=70 class=input rows=4></textarea></td></tr>
		<tr><td>���� ������ ������ (����������� id ������ ����� �������):</td><td><input type=text name=clan size=70></td></tr>
		<tr><td>���� ������ ������� (����������� id ������� ����� �������)</td><td><input type=text name=userr size=70></td></tr>
		<tr><td>���� ������ ���� (���� ������������)</td><td><input type=text name=race></td></tr>
		<tr><td>����� �� ����</td><td><input type=text name=gp size=3> �������</td></tr>
		<tr><td>����� �������� </td><td><input type=text name=timestart value="'.date("d.m.Y H:i").'" size=20> ������: 21.03.2005 15:43 (���� ����� - ������� ������)</td></tr>
		<tr><td>����� �������� </td><td><input type=text name=time value="'.date("d.m.Y H:i").'" size=20> ������: 21.03.2005 15:43 (���� ����� - ������� ������)</td></tr>
		<tr><td align=right>����� � view.rpg.su</td><td><input name="view" type="checkbox" value="1" checked></td></tr>
		<tr><td align=right>������ ������������</td><td><input name="moved" type="checkbox" value="1"></td></tr>
		<tr><td align=right>������� �����������</td><td><select name="movetime">
		<option value="0">�� ����������</option>
		<option value="1">1 ������</option>
		<option value="3">3 ������</option>
		<option value="10">10 �����</option>
		<option value="60">1 ���</option>
		<option value="120">2 ����</option>
		<option value="180">3 ����</option>
		<option value="240">4 ����</option>
		<option value="360">6 �����</option>
		<option value="720">12 �����</option>
		</select></td></tr>';
		echo'<tr><td colspan=2 align=center><input name="save" type="submit" value="��������"><input name="save" type="hidden" value=""></td></tr>';
		echo'</table></form>';
	}
	else
	{
		echo'������ '.$town.' ��������';
		if (isset($view) and $view=='1') $v='1';
		if (!isset($view)) $v='0';
		if (isset($moved) and $moved=='1') $m='1';
		if (!isset($moved)) $m='0';

		$update=myquery("INSERT INTO game_obj (town,name,text,clan,user,race,time,gp,timestart,view,moved,movetime)
		VALUES ('$town','$name','$text','$clan','$userr','$race','$time','$gp','$timestart','$v','$m','$movetime')");
		$da = getdate();
		$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
		 VALUES (
		 '".$char['name']."',
		 '������� ������: <b>".$town."</b> (".$name.")',
		 '".time()."',
		 '".$da['mday']."',
		 '".$da['mon']."',
		 '".$da['year']."')")
			 or die(mysql_error());
		echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=gorod">';
	}
}

if (isset($edit_obj))
{
	if (isset($_GET['del_cond']))
	{
		myquery("DELETE FROM game_obj_require WHERE id=".$_GET['del_cond']."");
	}
	if (!isset($_POST['save']) AND !isset($_POST['add_cond']) AND !isset($_POST['save_cond']))
	{
		$sel=myquery("select * from game_obj where id='$edit_obj'");
		$shop=mysql_fetch_array($sel);
		echo'<form action="" method="post"><input name="edit_obj" type="hidden" value="'.$edit_obj.'"><table border=0>
		<tr><td>��������:</td><td><input type=text name=town value="'.$shop['town'].'"></td></tr>';
		$check = myquery("SELECT gms.name, gm.xpos, gm.ypos FROM game_map gm JOIN game_maps gms ON gm.name=gms.id WHERE gm.town!=0 and gm.town = '".$shop['id']."' and to_map_name <> 0");
		if (mysql_num_rows($check)>0)
		{
			$map_obj = mysql_fetch_array($check);
			echo '<tr><td>������������:</td><td>'.$map_obj['name'].' (X-'.$map_obj['xpos'].', Y-'.$map_obj['ypos'].')</td></tr>';
		}
		echo '<tr><td>������ �����:</td><td><input type=text name=name value="'.$shop['name'].'"></td></tr>
		<tr><td>�������� ����� ������:</td><td><textarea name=text cols=70 class=input rows=4>'.$shop['text'].'</textarea></td></tr>
		<tr><td>���� ������ ������ (����������� id ������ ����� �������):</td><td><input type=text name=clan value="'.$shop['clan'].'" size=70></td></tr>
		<tr><td>���� ������ ������� (����������� id ������� ����� �������)</td><td><input type=text name=userr value="'.$shop['user'].'" size=70></td></tr>
		<tr><td>���� ������ ���� (���� ������������)</td><td><input type=text name=race value="'.$shop['race'].'"></td></tr>
		<tr><td>����� �� ����</td><td><input type=text name=gp value="'.$shop['gp'].'" size=3> �������</td></tr>
		<tr><td>����� �������� </td><td><input type=text name=timestart value="'.$shop['timestart'].'" size=20> ������: 21.03.2005 15:43 (���� ����� - ������� ������)</td></tr>
		<tr><td>����� �������� </td><td><input type=text name=time value="'.$shop['time'].'" size=20> ������: 21.03.2005 15:43 (���� ����� - ������� ������)</td></tr>
		<tr><td align=right>����� � view.rpg.su</td><td><input name="view" type="checkbox" value="1"';
		if ($shop['view']==1) echo ' checked';
		echo '></td></tr>
		<tr><td align=right>������ ������������</td><td><input name="moved" type="checkbox" value="1"';
		if ($shop['moved']==1) echo ' checked';
		echo '></td></tr>
		<tr><td align=right>������� �����������</td><td><select name="movetime">
		<option value="0"'; if ($shop['movetime']==0) echo ' selected'; echo'>�� ����������</option>
		<option value="1"'; if ($shop['movetime']==1) echo ' selected'; echo'>1 ������</option>
		<option value="3"'; if ($shop['movetime']==3) echo ' selected'; echo'>3 ������</option>
		<option value="10"'; if ($shop['movetime']==10) echo ' selected'; echo'>10 �����</option>
		<option value="60"'; if ($shop['movetime']==60) echo ' selected'; echo'>1 ���</option>
		<option value="120"'; if ($shop['movetime']==120) echo ' selected'; echo'>2 ����</option>
		<option value="180"'; if ($shop['movetime']==180) echo ' selected'; echo'>3 ����</option>
		<option value="240"'; if ($shop['movetime']==240) echo ' selected'; echo'>4 ����</option>
		<option value="360"'; if ($shop['movetime']==360) echo ' selected'; echo'>6 �����</option>
		<option value="720"'; if ($shop['movetime']==720) echo ' selected'; echo'>12 �����</option>
		</select></td></tr>';
		$sel_require = myquery("SELECT DISTINCT nomer FROM game_obj_require WHERE obj_id=".$shop['id']."");
		if (mysql_num_rows($sel_require))
		{
			echo '<tr><td colspan="2" align="center">�������� ������� ��� �������</td></tr><tr><td colspan="2" align="center">';
			
			echo '<table cellspacing="1" border="1" cellpadding="4">';
			while (list($nom)=mysql_fetch_array($sel_require))
			{
				$sel_cond = myquery("SELECT * FROM game_obj_require WHERE nomer=$nom AND obj_id=".$shop['id']."");
				echo '<tr><td rowspan="'.(mysql_num_rows($sel_cond)+1).'" align="center" valign="middle">'.$nom.'</td></tr>';
				while ($cond = mysql_fetch_array($sel_cond))
				{
					echo '<tr><td>';
					switch ($cond['type'])
					{
						case 1:
							echo '������� ������ ';
						break;
						case 2:
							echo '���������� �������� ����� ';
						break;
						case 3:
							echo '������� �������� ';
						break;
						case 34:
							echo '������ ������� ';
						break;
						case 4:
							echo '������� ���� ';
						break;
						case 5:
							echo 'HP MAX ';
						break;
						case 6:
							echo 'MP MAX ';
						break;
						case 7:
							echo 'STM MAX ';
						break;
						case 8:
							echo '���� ������ ';
						break;
						case 9:
							echo '��������� ������ ';
						break;
						case 10:
							echo '�������� ������ ';
						break;
						case 11:
							echo '�������� ������ ';
						break;
						case 12:
							echo '������������ ������ ';
						break;
						case 13:
							echo '������������� "�������� ����������" ';
						break;
						case 14:
							echo '������������� "���" ';
						break;
						case 15:
							echo '������������� "�������� �������" ';
						break;
						case 16:
							echo '������������� "�������� ���" ';
						break;
						case 17:
							echo '������������� "�����������" ';
						break;
						case 18:
							echo '������������� "������" ';
						break;
						case 19:
							echo '���������� ����� ';
						break;
						case 20:
							echo '���������� ��������� ';
						break;
						case 21:
							echo '�������� � ��� ����� ';
						break;
						case 22:
							echo '��������� � ��� ����� ';
						break;
						case 23:
							echo '�������� ���������� ';
						break;
						case 24:
							echo '����� "����" ';
						break;
						case 25:
							echo '����� "���������" ';
						break;
						case 26:
							echo '����� "������" ';
						break;
						case 27:
							echo '����� "���" ';
						break;
						case 28:
							echo '����� "���������" ';
						break;
						case 29:
							echo '����� "����" ';
						break;
						case 30:
							echo '����� "������" ';
						break;
						case 31:
							echo '����� "�����" ';
						break;
						case 32:
							echo '����� "�������" ';
						break;
						case 32:
							echo '������ ������ ';
						break;
						case 100:
							echo '������� ����� ';
						break;
						case 101:
							echo '���������� ������ (1 - �������, 2 - ����, 3 - ����)';
						break;
					}
					switch ($cond['condition'])
					{
						case 1:
							echo '<=';
						break;
						case 2:
							echo '<';
						break;
						case 3:
							echo '=';
						break;
						case 4:
							echo '>=';
						break;
						case 5:
							echo '>';
						break;
						case 6:
							echo '<>';
						break;
					}
					echo ' '.$cond['value'].'</td><td><a href="admin.php?opt=main&option=gorod&edit_obj='.$_GET['edit_obj'].'&del_cond='.$cond['id'].'">������� �������</a></td></tr>';    
				}
				echo '</tr>';
			}
			echo '</table>';
			
			echo '</td></tr>';
		}
		echo'<tr><td colspan=2 align=center></td></tr>';
		echo'<tr><td colspan=2 align=center><input name="add_cond" type="submit" value="�������� ������� ��� �������"></td></tr>';
		echo'<tr><td colspan=2 align=center></td></tr>';
		echo'<tr><td colspan=2 align=center><input name="save" type="submit" value="���������"></td></tr>';
		echo'</table></form>';
	}
	elseif (isset($_POST['add_cond']))
	{
		?>
		<script type="text/javascript">
		function check_visible()
		{
			type = document.getElementById("type");
			items = document.getElementById("items");
			horse = document.getElementById("horse");
			value = document.getElementById("value");
			condition = document.getElementById("condition");
			if (type.value=="3" || type.value=="34")
			{
				items.style.display="block";    
				horse.style.display="none";    
				value.style.display="none";
				condition.value="3";
				condition.disabled=true;
			}
			else
			if (type.value=="4")
			{
				items.style.display="none";    
				horse.style.display="block";    
				value.style.display="none";    
				condition.value="3";
				condition.disabled=true;
			}
			else
			{
				items.style.display="none";    
				horse.style.display="none";    
				value.style.display="block";    
				condition.disabled=false;
			}
			return;
		}
		</script>
		<?
		echo '<form action="" method="post" autocomplete="off"><input name="edit_obj" type="hidden" value="'.$edit_obj.'"><table border=0>';
		echo '<tr><td>����� �������</td><td><input type="text" name="nomer" value="" size="3" maxsize="3"></td></tr>';        
		echo '<tr><td>��� �������</td><td><select id="type" name="type" onchange="check_visible();">
		<option value="1">������� ������</option>
		<option value="2">���������� �������� �����</option>
		<option value="3">������� ��������</option>
		<option value="34">������ �������</option>
		<option value="4">������� ����</option>
		<option value="5">HP MAX</option>
		<option value="6">MP MAX</option>
		<option value="7">STM MAX</option>
		<option value="8">���� ������</option>
		<option value="9">��������� ������</option>
		<option value="10">�������� ������</option>
		<option value="11">�������� ������</option>
		<option value="12">������������ ������</option>
		<option value="33">������ ������</option>
		<option value="13">������������� "�������� ����������"</option>
		<option value="14">������������� "���"</option>
		<option value="15">������������� "�������� �������"</option>
		<option value="16">������������� "�������� ���"</option>
		<option value="17">������������� "�����������"</option>
		<option value="18">������������� "������"</option>
		<option value="19">���������� �����</option>
		<option value="20">���������� ���������</option>
		<option value="21">�������� � ��� �����</option>
		<option value="22">��������� � ��� �����</option>
		<option value="23">�������� ����������</option>
		<option value="24">����� "����"</option>
		<option value="25">����� "���������"</option>
		<option value="26">����� "������"</option>
		<option value="27">����� "���"</option>
		<option value="28">����� "���������"</option>
		<option value="29">����� "����"</option>
		<option value="30">����� "������"</option>
		<option value="31">����� "�����"</option>
		<option value="32">����� "�������"</option>
		<option value="100">������� �����</option>
		<option value="101">���������� ������ (1 - �������, 2 - ����, 3 - ����)</option>
		</select>
		</td></tr>';        
		echo '<tr><td>���� �������</td><td><select id="condition" name="condition">
		<option value="1"><=</option>
		<option value="2"><</option>
		<option value="3">=</option>
		<option value="4">>=</option>
		<option value="5">></option>
		<option value="6"><></option>
		</select>
		</td></tr>';        
		echo '<tr><td>�������� �������</td><td>';
		$sel_item = myquery("SELECT game_items_factsheet.id,game_items_factsheet.name,game_items_factsheet.type,game_items_factsheet.race,game_har.name AS race_name FROM game_items_factsheet LEFT JOIN (game_har) ON (game_har.id=game_items_factsheet.race) ORDER BY game_items_factsheet.type,game_items_factsheet.name");
		echo '<select id="items" name="items" style="display:none;">';
		while ($item = mysql_fetch_array($sel_item))
		{
			echo '<option value="'.$item['name'].'">'.$item['name'].' ('.$item['type'].' '.(($item['race_name']==NULL) ? '' : $item['race_name'] ).')</option>';
		}
		echo '</select>';
		$sel_item = myquery("SELECT id,nazv FROM game_vsadnik ORDER BY nazv");
		echo '<select id="horse" name="horse" style="display:none;">';
		while ($item = mysql_fetch_array($sel_item))
		{
			echo '<option value="'.$item['id'].'">'.$item['nazv'].'</option>';
		}
		echo '</select>';
		echo '<input id="value" style="display:block;" type="text" name="value" value="" size="30" maxsize="30"></td></tr>';        
		echo'<tr><td colspan=2 align=center></td></tr>';
		echo'<tr><td colspan=2 align=center><input name="save_cond" type="submit" value="���������"></td></tr>';
		echo'</table></form><br><br>
		����������: ��� ������� � ���������� ������� ����������� ������ "�". ��� ������� � ������� �������� ����������� ������� "���". ��������, ���� ����� ������ ��� ������ ��� ������� ���� 30 ������ ��� ��� ������� � ������� 50 ���� � 20 ����������. ����� ���� �������� 3 �������. ������� 1 - ����� ������� "1", ������� = "������� ���� 30", ������� 2 - ����� ������� "2", ������� = "���� = 50", ������� 3 - ����� ������� "2", ������� = "��������� = 20".';
	}                                                         
	elseif (isset($_POST['save_cond']))
	{
		echo'��������� ������� ��� �������';
		 if ($_POST['type']==3 OR $_POST['type']==34)
		 {
			$_POST['condition']="=";
			$_POST['value']=$_POST['items'];
		 }
		 if ($_POST['type']==4)
		 {
			$_POST['condition']="=";
			$_POST['value']=$_POST['horse'];
		 }
		$up=myquery("INSERT INTO game_obj_require (`obj_id`,`nomer`,`type`,`condition`,`value`) VALUES ('".$_POST['edit_obj']."','".$_POST['nomer']."','".$_POST['type']."','".$_POST['condition']."','".$_POST['value']."')");
		echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=gorod&edit_obj='.$_POST['edit_obj'].'">';
	}
	elseif (isset($_POST['save']))
	{
		echo'������ '.$town.' �������';
		if (isset($view) and $view=='1') $v='1';
		if (!isset($view)) $v='0';
		if (isset($moved) and $moved=='1') $m='1';
		if (!isset($moved)) $m='0';

		$up=myquery("update game_obj set
		town='$town',
		name='$name',
		text='$text',
		clan='$clan',
		user='$userr',
		race='$race',
		time='$time',
		timestart='$timestart',
		view='$v',
		moved='$m',
		movetime='$movetime',
		gp='$gp' where id='$edit_obj'");
		$da = getdate();
		$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
		VALUES (
		'".$char['name']."',
		'������� ������: <b>".$town."</b>',
		'".time()."',
		'".$da['mday']."',
		'".$da['mon']."',
		'".$da['year']."')")
		 or die(mysql_error());
		echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=gorod">';
	}
}


if(!isset($edit) and !isset($new) and !isset($edit_obj) and !isset($new_obj))
{
	echo'<a href="?opt=main&option=gorod&new_obj">�������� ������</a></center>';
	echo'<table border=0 width=70% align=center>';
	$sel=myquery("SELECT go.*, gms.name, gm.xpos, gm.ypos FROM game_obj go JOIN game_map gm ON go.id = gm.town JOIN game_maps gms ON gm.name=gms.id WHERE gm.town!=0 and to_map_name <> 0 ORDER BY town DESC");		
	$i=0;
	while($shop=mysql_fetch_array($sel))
	{
		$i++;
		if ($i==1) {echo '<tr bgcolor=#580058>';}
		else {$i=0; echo '<tr bgcolor=#585800>';};
		echo '<td align="right">'.$shop['town'].'</td>';
		echo '<td align="center">'.$shop['name'].' (X-'.$shop['xpos'].', Y-'.$shop['ypos'].')</td>';
		echo '<td><a href="?opt=main&option=gorod&edit_obj='.$shop['id'].'">�������������</a>, <a href="?opt=main&option=gorod&del_p='.$shop['town'].'">�������</a></td></tr>';
	}
	echo'</table>';
}

echo'<center><a href="?opt=main&option=gorod">�������</a>';
}

if (function_exists("save_debug")) save_debug(); 

?>