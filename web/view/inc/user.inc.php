<script type="text/javascript">
/* URL to the PHP page called for receiving suggestions for a keyword*/
var getFunctionsUrl = "../suggest/suggest.php?keyword=";
</script>
<?

if (function_exists("start_debug")) start_debug(); 

echo'<link href="../suggest/suggest.css" rel="stylesheet" type="text/css">';
echo'<script type="text/javascript" src="../suggest/suggest.js"></script>';
echo'<div id="content" onclick="hideSuggestions();"><center>������� ��� ������:</center><br>';
echo'<center><font size="1" face="Verdana" color="#ffffff">�����: <input type="text" size="25" id="keyword" onkeyup="handleKeyUp(event)"><div style="display:none;" id="scroll"><div id="suggest"></div></div>
<input name="but" type="button" value="�����" onClick="location.href=\'?name=\'+document.getElementById(\'keyword\').value"><br><br></div><script>init();</script>';

function show_user($user)
{
  global $keeper, $guest, $admin;
		$user_data = mysql_fetch_array(myquery("SELECT * FROM game_users_data WHERE user_id='".$user['user_id']."'"));

		$klan=myquery("SELECT * FROM game_clans WHERE clan_id='".$user['clan_id']."'");
		$klan=mysql_fetch_array($klan);

		echo'<center><font face=verdana size=2 color=ff0000><b>' . $user['name'] . '</font> ('.mysql_result(myquery("SELECT name FROM game_har WHERE id=".$user['race'].""),0,0).' '.$user['clevel'].' ������)</b>';
		if ($user_data['sex']=="male") echo'<br><font color=#80FFFF>(������)</font>';
		if ($user_data['sex']=="female") echo'<br><font color=#FF00FF>(�������)</font>';
		if ($user_data['dr_date']>0 or $user_data['dr_month']>0 or $user_data['dr_year']>0)
		{
			echo '<br>���� ��������: '.$user_data['dr_date'].'.'.$user_data['dr_month'].'.'.$user_data['dr_year'].'';
		}
		echo'</center>';

		$user_date = date('d.m.Y : H:i:s', $user_data['last_visit']);
		echo'<center><b>��������� ��� ����� ���: <font color=ff0000>"'.$user_date.'"</font>';
		if ($user_data['rego_time']>0)
		{
			$user_date = date('d.m.Y : H:i:s', $user_data['rego_time']);
			echo'<center><b>�������� ���������������: <font color=ff0000>"'.$user_date.'"</font>';
		}
		$vozrast_year = floor($user_data['vozrast']/12);
		$vozrast_mes = $user_data['vozrast']-$vozrast_year*12;
		$vozrast = ''.$vozrast_year.' �. '.$vozrast_mes.' ���.';
		echo'<center><b>������� ������: <font color=ff0000>"'.$vozrast.'"</font>';

		if ($user['vsadnik']>20)
		{
			$konn=myquery("SELECT gv.* FROM game_vsadnik gv JOIN game_users_horses guh ON guh.horse_id=gv.id WHERE guh.user_id='".$user['user_id']."' limit 1");
			$kon=mysql_fetch_array($konn);
			echo'<center><br><b>�������! ����� �� <font color=ff0000>"'.$kon['nazv'].'"</font>';
		}		
		echo'<br>';
		echo'<br><font color="White" size=2><b><center>������������ ������:</font> <font color=#FF0000 size=2>'.$user['reinc'].'</center></b>';
		
		echo '<br>
		<br><font color="White" size=2 face=verdana>���������� ������: ';
		if ($user['clan_id']==1) echo '<img src="http://'.img_domain.'/sklon/admin.gif" border="0">&nbsp;&nbsp;<span style="color:#D0D0D0;font-weight:800;">������</span>';
		elseif ($user['sklon']==0) echo '&nbsp;&nbsp;��� ����������';
		elseif ($user['sklon']==1) echo '<img src="http://'.img_domain.'/sklon/neutral.gif" border="0">&nbsp;&nbsp;<span style="color:#D0D0D0;font-weight:800;">�����������</span>';
		elseif ($user['sklon']==2) echo '<img src="http://'.img_domain.'/sklon/light.gif" border="0">&nbsp;&nbsp;<span style="color:#FFFFC0;font-weight:800;">�������</span>';
		elseif ($user['sklon']==3) echo '<img src="http://'.img_domain.'/sklon/dark.gif" border="0">&nbsp;&nbsp;<span style="color:#969696;font-weight:800;">������</span>';
		echo'<center><table border="0" cellpadding="0" cellspacing="0">';
		if ($klan['clan_id']!=0)
		{
			list($rating,$zvanie) = mysql_fetch_array(myquery("SELECT clan_rating,clan_zvanie FROM game_users_data WHERE user_id='".$user['user_id']."'"));
			echo'<tr><td colspan="3" valign="top">';
			echo'<font color=#FF0000 size=2><b><center>���� ������: <a href="http://'.domain_name.'/view/?clan='.$klan['clan_id'].'">'.$klan['nazv'].'</a></center></b></font><br>';
			if ($rating!='0')
			   echo '<center><font color=ffff00>   ������� � ����� - <b>'.$rating.'</b></font><br>';
			if ($zvanie!='')
			   echo '<center><font color=ffff00>   ������ � ����� - <b>'.$zvanie.'</b></font><br>';
			echo '<br></td></tr>';
		}


		/*
		$sel_mag = myquery("SELECT * FROM game_mag WHERE name='".$user['name']."' AND town>0");
		while ($mag = mysql_fetch_array($sel_mag))
		{
			if ($mag['town']>0)
			{
				$sel_town = myquery("SELECT rustown FROM game_gorod WHERE town = '".$mag['town']."'");
				list($rustown) = mysql_fetch_array($sel_town);
				echo'<tr><td colspan="4" valign="top" align="center"><b><font color="ff00ff">��� ������</font> '.$rustown.'</b> ('.$mag['status'].')<br></td></tr>';
			}
		}
		$sel_house = myquery("SELECT * FROM game_houses WHERE user_id=".$user['user_id']." LIMIT 1");
		if ($sel_house!=false AND mysql_num_rows($sel_house)>0)
		{
			$house = mysql_fetch_array($sel_house);
			if ($house['town_id']>0 AND $house['type']>0)
			{
				$sel_town = myquery("SELECT rustown FROM game_gorod WHERE town = '".$house['town_id']."'");
				list($rustown) = mysql_fetch_array($sel_town);
				if ($house['buildtime']<time())
				{
					echo'<tr><td colspan="4" valign="top" align="center"><b><font color="ff00ff">��� ������ � ������</font> '.$rustown.'</b><br></td></tr>';
				}
				else
				{
					echo'<tr><td colspan="4" valign="top" align="center"><b><font color="ff00ff">��� ������ � ������</font> '.$rustown.'</b> (��������)<br></td></tr>';
				}
			}
	   }
	   */	
		
		
		
		if($user_data['user_rating']>0)
		{
			echo'<tr height="25"><td colspan="4" valign="middle" align="center"><b><font color="#80FF80">������������ ������� ������</font>: '.$user_data['user_rating'].'</b></td></tr>';
		}
		if($user_data['karma']>0)
		{
			echo'<tr height="25"><td colspan="4" valign="middle" align="center"><b><font color="#80FF80">����� ������</font>: '.$user_data['karma'].'</b></td></tr>';
		}
		if($user_data['karma']<0)
		{
			echo'<tr height="25"><td colspan="4" bgcolor="white" valign="middle" align="center"><b><font color="#000000">����� ������: '.$user_data['karma'].'</font></b></td></tr>';
		}
		
// ������������� ����� - ���������� �� �������������
	if($keeper OR $admin)
	{
		$sel_mult = mysql_result(myquery("SELECT count(*) FROM game_activity_mult WHERE name='".$user['name']."'"),0,0);
		if($sel_mult>0)
		{
				echo'<tr><td colspan="4" valign="top" align="center"><b><font color="ff00ff">����� ���������� � �������������!</b><br></td></tr>';
		}
	}


		echo'<tr><td valign="top" align=center><img src="http://'.img_domain.'/avatar/'.$user['avatar'].'" align=center></td>
        <td align="center" valign=top>';

		echo'<table border="0" cellpadding="2" cellspacing="0">';
		if ($user['HP_MAX'] == 0)
		{
			$bar_percentage = 0;
		}
		else
		{
			$bar_percentage = number_format($user['HP'] / $user['HP_MAX'] * 100, 0);
		}
		if ($bar_percentage >= '100')
		{
			$append_string = '<img src="http://'.img_domain.'/bar/bar_green.gif" width="100" height="7" border="0">';
		}
		elseif ($bar_percentage <= '0')
		{
			$append_string = '<img src="http://'.img_domain.'/bar/bar_empty.gif" width="100" height="7" border="0">';
		}
		else
		{
			$append_string = '<img src="http://'.img_domain.'/bar/bar_empty.gif" width="' . (100 - $bar_percentage) . '" height="7" border="0"><img src="http://'.img_domain.'/bar/bar_green.gif" width="' . $bar_percentage . '" height="7" border="0">';
		}

		echo '<tr><td>��������: ' . $user['HP'] . ' / ' . $user['HP_MAX'] . '</td></tr>
		<tr><td colspan="2"><div align="right"><img src="http://'.img_domain.'/bar/bar_side.gif" width="1" height="7" border="0">'
		. $append_string . '<img src="http://'.img_domain.'/bar/bar_side.gif" width="1" height="7" border="0"><br><img
		src="http://'.img_domain.'/nav/x.gif" width="0" height="0" vspace="2" border="0"></div></td></tr>';


		if ($user['MP_MAX'] == 0)
		{
			$bar_percentage = 0;
		}
		else
		{
			$bar_percentage = number_format($user['MP'] / $user['MP_MAX'] * 100, 0);
		}
		if ($bar_percentage >= '100')
		{
			$append_string = '<img src="http://'.img_domain.'/bar/bar_orange.gif" width="100" height="7" border="0">';
		}
		elseif ($bar_percentage <= '0')
		{
			$append_string = '<img src="http://'.img_domain.'/bar/bar_empty.gif" width="100" height="7" border="0">';
		}
		else
		{
			$append_string = '<img src="http://'.img_domain.'/bar/bar_empty.gif" width="' . (100 - $bar_percentage) . '" height="7" border="0"><img src="http://'.img_domain.'/bar/bar_orange.gif" width="' . $bar_percentage . '" height="7" border="0">';
		}
		echo '<tr><td>����: ' . $user['MP'] . ' / ' . $user['MP_MAX'] . '</td></tr>
		<tr><td colspan="2"><div align="right"><img src="http://'.img_domain.'/bar/bar_side.gif" width="1" height="7" border="0">'. $append_string . '<img src="http://'.img_domain.'/bar/bar_side.gif" width="1" height="7" border="0"><br><img src="http://'.img_domain.'/nav/x.gif" width="0" height="0" vspace="2" border="0"></div></td></tr>';

		if ($user['STM_MAX'] == 0)
		{
			$bar_percentage = 0;
		}
		else
		{
			$bar_percentage = number_format($user['STM'] / $user['STM_MAX'] * 100, 0);
		}
		if ($bar_percentage >= '100')
		{
			$append_string = '<img src="http://'.img_domain.'/bar/bar_yellow.gif" width="100" height="7" border="0">';
		}
		elseif ($bar_percentage <= '0')
		{
			$append_string = '<img src="http://'.img_domain.'/bar/bar_empty.gif" width="100" height="7" border="0">';
		}
		else
		{
			$append_string = '<img src="http://'.img_domain.'/bar/bar_empty.gif" width="' . (100 - $bar_percentage) . '" height="7" border="0"><img src="http://'.img_domain.'/bar/bar_yellow.gif" width="' . $bar_percentage . '" height="7" border="0">';
		}
		echo '<tr><td>�������: ' . $user['STM'] . ' / ' . $user['STM_MAX'] . '</td></tr><tr><td colspan="2"><div align="right"><img src="http://'.img_domain.'/bar/bar_side.gif" width="1" height="7" border="0">'
		. $append_string . '<img src="http://'.img_domain.'/bar/bar_side.gif" width="1" height="7" border="0"><br><img src="http://'.img_domain.'/nav/x.gif" width="0" height="0" vspace="2" border="0"></div></td></tr>';
		
		if ($user['PR_MAX'] == 0)
		{
			$bar_percentage = 0;
		}
		else
		{
			$bar_percentage = number_format($user['PR'] / $user['PR_MAX'] * 100, 0);
		}
		if ($bar_percentage >= '100')
		{
			$append_string = '<img src="http://'.img_domain.'/bar/bar_red.gif" width="100" height="7" border="0">';
		}
		elseif ($bar_percentage <= '0')
		{
			$append_string = '<img src="http://'.img_domain.'/bar/bar_empty.gif" width="100" height="7" border="0">';
		}
		else
		{
			$append_string = '<img src="http://'.img_domain.'/bar/bar_empty.gif" width="' . (100 - $bar_percentage) . '" height="7" border="0"><img src="http://'.img_domain.'/bar/bar_red.gif" width="' . $bar_percentage . '" height="7" border="0">';
		}
		echo '<tr><td>�����: ' . $user['PR'] . ' / ' . $user['PR_MAX'] . '</td></tr><tr><td colspan="2"><div align="right"><img src="http://'.img_domain.'/bar/bar_side.gif" width="1" height="7" border="0">'
		. $append_string . '<img src="http://'.img_domain.'/bar/bar_side.gif" width="1" height="7" border="0"><br><img src="http://'.img_domain.'/nav/x.gif" width="0" height="0" vspace="2" border="0"></div></td></tr>';
		
		echo'</table>';

		echo'</td><td align="left" valign="top">
		<table border="0" cellpadding="0" cellspacing="0">
		<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img align="middle" src="http://'.img_domain.'/har/sil.gif" alt="����"> ����: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>' . $user['STR'] . ''; if ($user['STR']>$user['STR_MAX']) echo '(+'.($user['STR']-$user['STR_MAX']).')'; echo '</td></tr>
		<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img align="middle" src="http://'.img_domain.'/har/int.gif" alt="���������"> ���������: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td align="right">' . $user['NTL'] . ''; if ($user['NTL']>$user['NTL_MAX']) echo '(+'.($user['NTL']-$user['NTL_MAX']).')'; echo '</td></tr>
		<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img align="middle" src="http://'.img_domain.'/har/lov.gif" alt="��������"> ��������: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td align="right">' . $user['PIE'] . ''; if ($user['PIE']>$user['PIE_MAX']) echo '(+'.($user['PIE']-$user['PIE_MAX']).')'; echo '</td></tr>
		<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img align="middle" src="http://'.img_domain.'/har/vit.gif" alt="������"> ������: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td align="right">' . $user['VIT'] . ''; if ($user['VIT']>$user['VIT_MAX']) echo '(+'.($user['VIT']-$user['VIT_MAX']).')'; echo '</td></tr>
		<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img align="middle" src="http://'.img_domain.'/har/dex.gif" alt="������������"> ������������: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td align="right">' . $user['DEX'] . ''; if ($user['DEX']>$user['DEX_MAX']) echo '(+'.($user['DEX']-$user['DEX_MAX']).')'; echo '</td></tr>
		<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img align="middle" src="http://'.img_domain.'/har/mud.gif" alt="��������"> ��������: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td align="right">' . $user['SPD'] . ''; if ($user['SPD']>$user['SPD_MAX']) echo '(+'.($user['SPD']-$user['SPD_MAX']).')'; echo '</td></tr>
		<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img align="middle" src="http://'.img_domain.'/har/ud.gif" alt="�����"> �����: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td align="right">' . $user['lucky'] . ''; if ($user['lucky']>$user['lucky_max']) echo '(+'.($user['lucky']-$user['lucky_max']).')'; echo '</td></tr>
		</table></td></tr>
		<tr><td colspan="3" valign="top" align=center><br /><b>����� ���</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		$prov=mysql_result(myquery("SELECT count(*) FROM game_wm WHERE user_id=".$user['user_id']." AND type=1"),0,0);
		if ($prov>0)
		{
			echo min($user['CW'],$user['CC']);
		}
		else
		{
			echo $user['CW'];
		}
		echo ' / '.$user['CC'].'<br />&nbsp; </td></tr>
        <tr><td colspan="2 width="500">';
		$user_id1=$user['user_id'];

        PrintInv($user_id1,1);

		echo'</td>

		<td valign="top"><br />
		<table border="0" cellpadding="0" cellspacing="0">
		<tr><td colspan="2" width="81%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<FONT color=#ffff00 size=1><B>�������������:</B></FONT></td></tr>';

		$sel_skill = myquery("SELECT gs.name, gus.* FROM game_users_skills gus, game_skills gs WHERE user_id=".$user['user_id']." AND gus.skill_id=gs.id ORDER BY gs.sgroup DESC, gs.name");
		if (mysql_num_rows($sel_skill)>0)
			{
				while ($sk = mysql_fetch_array($sel_skill))
					{
					echo '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$sk['name'].': </td><td>' . $sk['level'].' </td></tr>';
					}
			}
		
/*		if ($user['MS_KULAK']!='0') echo '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img align="middle" src="http://'.img_domain.'/har/kulak.gif" alt="��������� ��������� ���"> �������� ���: </td><td>' . $user['MS_KULAK'] . '</td></tr>';
		if ($user['MS_WEAPON']!='0') echo '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img align="middle" src="http://'.img_domain.'/har/weapon.gif" alt="������� �������� ������"> ������� �������� ������: </td><td>' . $user['MS_WEAPON'] . '</td></tr>';
		if ($user['MS_LUK']!='0') echo '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img align="middle" src="http://'.img_domain.'/har/luk.gif" alt="������ ����������� ������"> ������ ������.������: </td><td>' . $user['MS_LUK'] . '</td></tr>';
		if ($user['MS_THROW']!='0') echo '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img align="middle" src="http://'.img_domain.'/har/throw.gif" alt="������ ������������ ������"> ������ �����.������: </td><td>' . $user['MS_THROW'] . '</td></tr>';
		if ($user['MS_SWORD']!='0') echo '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img align="middle" src="http://'.img_domain.'/har/rub.gif" alt="������ �������� ������"> ������ �����.������: </td><td>' . $user['MS_SWORD'] . '</td></tr>';
		if ($user['MS_AXE']!='0') echo '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img align="middle" src="http://'.img_domain.'/har/drob.gif" alt="������ ��������� ������"> ������ ����.������: </td><td>' . $user['MS_AXE'] . '</td></tr>';
		if ($user['MS_SPEAR']!='0') echo '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img align="middle" src="http://'.img_domain.'/har/kol.gif" alt="������ �������� ������"> ������ �����.������: </td><td>' . $user['MS_SPEAR'] . '</td></tr>';
		if ($user['MS_ART']!='0') echo '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img align="middle" src="http://'.img_domain.'/har/art.gif" alt="������� ����������"> ����. ����������: </td><td>' . $user['MS_ART'] . '</td></tr>';
		if ($user['MS_VSADNIK']!='0') echo '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img align="middle" src="http://'.img_domain.'/har/ezda.jpg" alt="�������� ����"> �������� ����: </td><td>' . $user['MS_VSADNIK'] . '</td></tr>';
		if ($user['MS_KUZN']!='0') echo '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img align="middle" src="http://'.img_domain.'/har/kuzn.jpg" alt="������"> ������: </td><td>' . $user['MS_KUZN'] . '</td></tr>';
		if ($user['MS_PARIR']!='0') echo '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img align="middle" src="http://'.img_domain.'/har/parir.gif" alt="������ �����������"> �����������: </td><td>' . $user['MS_PARIR'] . '</td></tr>';
		if ($user['MS_LEK']!='0') echo '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img align="middle" src="http://'.img_domain.'/har/lekar.gif" alt="������"> ������: </td><td>' . $user['MS_LEK'] . '</td></tr>';
		if ($user['MS_VOR']!='0') echo '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img align="middle" src="http://'.img_domain.'/har/vor.gif" alt="���"> ���: </td><td>' . $user['MS_VOR'] . '</td></tr>';

		echo'<tr><td colspan="2">&nbsp;</td></tr><tr><td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<FONT color=#ffff00 size=1><B><font face="Verdana">������</font>:</B></FONT><font size="1">&nbsp;</font></td></tr>';

		if ($user['skill_war']!='0') echo '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;����: </td><td>' . $user['skill_war'] . ' </td></tr>';
		if ($user['skill_music']!='0') echo '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;����: </td><td>' . $user['skill_music'] . ' </td></tr>';
		if ($user['skill_cook']!='0') echo '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;���������: </td><td>' . $user['skill_cook'] . ' </td></tr>';
		if ($user['skill_art']!='0') echo '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;������: </td><td>' . $user['skill_art'] . ' </td></tr>';
		if ($user['skill_explor']!='0') echo '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;�������: </td><td>' . $user['skill_explor'] . ' </td></tr>';
		if ($user['skill_craft']!='0') echo '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;������: </td><td>' . $user['skill_craft'] . ' </td></tr>';
		if ($user['skill_card']!='0') echo '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;���: </td><td>' . $user['skill_card'] . ' </td></tr>';
		if ($user['skill_pet']!='0') echo '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;�����: </td><td>' . $user['skill_pet'] . ' </td></tr>';
		if ($user['skill_uknow']!='0') echo '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;���������: </td><td>' . $user['skill_uknow'] . ' </td>';
*/
		echo'<tr><td colspan="2">&nbsp;</td></tr><tr><td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<FONT color=#ffff00 size=1><B><font face="Verdana">������</font>:</B></FONT><font size="1">&nbsp;</font></td></tr>';

		$sel_craft = myquery("SELECT * FROM game_users_crafts WHERE user_id=".$user['user_id']." AND (profile=1 OR craft_index<=2)");
		if (mysql_num_rows($sel_craft)>0)
		{
			while ($cr = mysql_fetch_array($sel_craft))
			{
				$craft_level = CraftSpetsTimeToLevel($cr['craft_index'],$cr['times']);
				echo '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.get_craft_name($cr['craft_index']).': </td><td title="�������">' . $craft_level . ' </td></tr>';
			}
		}
		$guild_test = myquery("SELECT * FROM game_users_guild WHERE user_id=".$user['user_id']."");
		if (mysql_num_rows($guild_test)==1) 
		{
			$guild = mysql_fetch_array($guild_test);
			echo '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;������: </td><td title="�������">' . $guild['guild_lev'] . ' </td></tr>';
		}
		echo'<tr><td colspan=2>&nbsp;</td></tr>
		<tr><td colspan=2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<FONT color=#ffff00 size=1><B><font face="Verdana">����������</font>:</B></FONT><font size="1">&nbsp;</font></td></tr>

		<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img align="middle" src="http://'.img_domain.'/har/win1.gif" alt="�����"> �����: </td><td>' . $user['win'] . '</td></tr>';
		//<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img align="middle" src="http://'.img_domain.'/har/lose1.gif" alt="���������"> ���������: </td><td>' . $user['lose'] . '</td></tr>
		echo'<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img align="middle" src="http://'.img_domain.'/har/win2.gif" alt="����� � ��� �����"> ����� � ��� �����: </td><td>' . $user['arcomage_win'] . '</td></tr>
		<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img align="middle" src="http://'.img_domain.'/har/lose2.gif" alt="��������� � ��� �����"> ��������� � ��� �����: </td><td>' . $user['arcomage_lose'] . '</td></tr>';
		if ($user['maze_win']>0)
		{
			echo'
			<tr><td colspan=2>&nbsp;</td></tr>
			<tr style="color:#FF00FF;"><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img align="middle" src="http://'.img_domain.'/har/win.jpg" alt="�������� ����������"> �������� ����������: </td><td style="font-weight:700;">' . $user['maze_win'] . '</td></tr>'; 
		}

		echo'
        <tr><td colspan=2>&nbsp;</td></tr>
        <tr><td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://'.domain_name.'/view/?log='.$user['name'].'">�������� ��� ����</a></td></tr>';

		list($last) = mysql_fetch_array(myquery("SELECT last_active FROM game_users_active WHERE user_id='".$user['user_id']."'"));
		if ((time()-$last)<=300)
		{
			echo'<tr><td colspan=2>&nbsp;</td></tr>
			<tr><td colspan=2><FONT color=#ffff00 size=1>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<B><font face="Verdana">������ ��������� � ����:</font></B></FONT><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '.get_delay_reason(get_delay_reason_id($user['user_id'])).'</td></tr>';
		}
		else
		{
			echo'<tr><td colspan=2>&nbsp;</td></tr>
			<tr><td colspan=2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<FONT color=#ffff00 size=1><B><font face="Verdana">������ �� � ����</font></B></FONT></td></tr>';
		}

		$sel = myquery("SELECT * FROM game_users_brak WHERE ((user1='".$user["user_id"]."' OR user2='".$user["user_id"]."') AND status=1)");
		if (mysql_num_rows($sel))
		{
			$usr = mysql_fetch_array($sel);
			if ($user['user_id']==$usr['user1'])
				$usr_id = $usr['user2'];
			else
				$usr_id = $usr['user1'];
			$sel = myquery("SELECT name FROM game_users WHERE user_id='".$usr_id."'");
			if (!mysql_num_rows($sel)) $sel = myquery("SELECT name FROM game_users_archive WHERE user_id='".$usr_id."'");
			list($name1) = mysql_fetch_array($sel);

			echo'<tr><td>&nbsp;</td></tr>
			<tr><td><FONT color=#FF00FF size=1 face="Tahoma"><img src="http://'.img_domain.'/wedding/wedring2.gif" width=50 height=50 align="right">������� � ����� � <b><font size=2>'.$name1.'</font></b><br>���� ��������������� '.$usr['datareg'].'</FONT></td></tr>';
		}
		echo'</table></td></tr>';

		$user_id=$user['user_id'];
		$SELECT = myquery("SELECT game_medal.nazv AS nazv, game_medal.opis AS opis,game_medal.image AS image,game_medal_users.zachto AS zachto FROM game_medal_users JOIN game_medal ON game_medal.id = game_medal_users.medal_id WHERE user_id=$user_id ORDER BY game_medal.id");
		if (mysql_num_rows($SELECT))
		{
			echo'<tr><td colspan="3" valign="top"><FONT color=#ffff00 size=1><B><font face="Verdana">&nbsp;&nbsp;&nbsp;������ ������</font>:</B></FONT>&nbsp;&nbsp;';
			QuoteTable('open');			
			while ($med=mysql_fetch_array($SELECT))
			{
				?>
				<a  onmousemove=movehint(event) onmouseover="showhint('<font color=ff0000><b><?echo ''.$med['nazv'].''?></b></font>','<font color=000000><?echo ''.$med['opis'].'<hr>'.replace_enter(htmlspecialchars($med['zachto'])).''; echo '</font>';?></font>',0,1,event)" onmouseout="showhint('','',0,0,event)">
				<img src="<?echo 'http://'.img_domain.'/medal/'.$med['image'].''?>"></a>
				<?
			}
			QuoteTable('close');
			echo'</td></tr>';
		}

		$SELECT = myquery("SELECT * FROM game_gift WHERE user_to=$user_id AND che=1 AND time_send>=".(time()-15*24*60*60)."");
		if (mysql_num_rows($SELECT))
		{
			echo'<tr><td colspan="3" valign="top"><FONT color=#ffff00 size=1><B><font face="Verdana">&nbsp;&nbsp;&nbsp;��������</font>:</B></FONT><br>';
		QuoteTable('open');
			while ($card=mysql_fetch_array($SELECT))
			{
				list($user_from) = mysql_fetch_array(myquery("(SELECT name FROM game_users WHERE user_id=".$card['user_from'].") UNION (SELECT name FROM game_users_archive WHERE user_id=".$card['user_from'].")"));
			?><a  onmousemove=movehint(event) onmouseover="showhint('<font color=ff0000><b><?echo ' �� '.$user_from.''?></b></font>','<font color=000000><?echo ''.replace_enter($card['gift_text']).'<br><br><hr><font size=1>����������: '.date("d.m.Y H:i",$card['time_send']).'</font>';?></font>',0,1,event)" onmouseout="showhint('','',0,0,event)">
			<img src="<?echo 'http://'.img_domain.'/gift/gallery/'.$card['gift_img'].''?>"></a>&nbsp;
			<?
			}
		QuoteTable('close');
			echo'</td></tr>';
		}

		echo '<tr><td colspan="3" valign="top"><FONT color=#ffff00 size=1><B><font face="Verdana">&nbsp;&nbsp;&nbsp;������ ����������</font>:</B></FONT><br>
		&nbsp;&nbsp;<b>������:</b> '.$user_data['STATUS'].'<br>
		&nbsp;&nbsp;<b>�����:</b> '.$user_data['gorod'].'<br>
		&nbsp;&nbsp;<b>�����:</b>';
QuoteTable('open');
if (!isset($user_data['hobbi']) OR $user_data['hobbi']=='') $user_data['hobbi']='���������� �� ���������';
echo' '.nl2br($user_data['hobbi']).'';
QuoteTable('close');
echo'&nbsp;&nbsp;<b>����������:</b>'; 
QuoteTable('open');
if (!isset($user_data['info']) OR $user_data['info']=='') $user_data['info']='���������� �� ���������';
echo' '.nl2br($user_data['info']).'';
QuoteTable('close');
		echo'</td></tr>';

		$sel=myquery("SELECT * FROM game_nakaz WHERE user_id='".$user['user_id']."' order by date_nak asc");
		if (mysql_num_rows($sel))
		{
			echo'<tr><td colspan="3" valign="top"><FONT color=#ffff00 size=1><B><font face="Verdana"><br>&nbsp;&nbsp;&nbsp;���������</font>:</B></FONT><br>';
			QuoteTable('open');
			echo '<table cellspacing=1 cellpadding=1>';
			while($nak=mysql_fetch_array($sel))
			{
				echo '<tr>';
				echo'<td>'.date("d.m.Y H:i:s",$nak['date_nak']).'</td>';
				if($nak['nakaz']=='prison')
				{
					echo'<td>������� �� <b><font color=#ff0000>'.$nak['date_zak'].'</font></b> �������� ����������� ������.</td>';
				}
				else
				{
					
					if ($nak['date_zak']=='0')
					{
						echo'<td><FONT color=#ffff00 size=1><b>������� !!!</b></font></td>';
					}
					else
					{
						$bantime = '';
						if ($nak['date_zak']/3600<1) $bantime = ''.round($nak['date_zak']/60,0).' ���.';
						elseif ($nak['date_zak']/86400<1) 
						{
							$hour = floor($nak['date_zak']/3600);
							$minute = round(($nak['date_zak']-$hour*3600)/60,0);
							$bantime = ''.$hour.' ���. '.$minute.' ���.';
						}
						else 
						{
							$day = floor($nak['date_zak']/86400); 
							$nakaz['date_zak'] = $nak['date_zak']-$day*86400;
							$hour = floor($nak['date_zak']/3600);
							$minute = round(($nak['date_zak']-$hour*3600)/60,0);
							$bantime = ''.$day.' ��. '.$hour.' ���. '.$minute.' ���.';
						}
						echo'<td>�� <b><font color=#ff0000>'.$bantime.'</font></b></td>';
					}
				}
				echo'<td> ('.mysqlresult(myquery("SELECT name FROM game_zakon WHERE id=".$nak['id_zakon'].""),0,0).') </td>';
				if ($nak['text']!='') echo'<td>�������: '.$nak['text'].'</td>';
				echo'</tr>';
			}
			echo '</table>';
			QuoteTable('close');
			echo'</td></tr>';
		}
		echo'</table>';
}

if (!isset($_GET['name'])) $name = ''; else  $name = $_GET['name'];
if ((isset($_GET['userid'])) AND $_GET['userid'] != 0)
{
  $userid = (int)$_GET['userid'];
	$sel=myquery("SELECT * FROM game_users WHERE user_id='$userid'");
	if (!mysql_num_rows($sel)) $sel=myquery("SELECT * FROM game_users_archive WHERE user_id='$userid'");
	if(mysql_num_rows($sel)=='1')
	{
		echo'<SCRIPT language=javascript src="http://'.domain_name.'/js/info.js"></SCRIPT><DIV id=hint  style="Z-INDEX: 100; LEFT: 0px; VISIBILITY: hidden; POSITION: absolute; TOP: 0px"></DIV>';
		$user=mysql_fetch_array($sel);
		list($delay,$delay_reason) = mysql_fetch_array(myquery("SELECT delay,delay_reason FROM game_users_active_delay WHERE user_id='$userid'"));
		$user['delay']=$delay;
		$user['delay_reason']=$delay_reason;
		show_user($user);
	}

}
elseif (preg_match('/^[ _a-z�-�A-Z�-�0-9]*$/i', $name))
{
	$name = mysql_escape_string($name);
	$sel=myquery("SELECT * FROM game_users WHERE name='$name'");
	if (!mysql_num_rows($sel)) $sel=myquery("SELECT * FROM game_users_archive WHERE name='$name'");
	if(mysql_num_rows($sel)=='1')
	{
		echo'<SCRIPT language=javascript src="http://'.domain_name.'/js/info.js"></SCRIPT><DIV id=hint  style="Z-INDEX: 100; LEFT: 0px; VISIBILITY: hidden; POSITION: absolute; TOP: 0px"></DIV>';
		$user=mysql_fetch_array($sel);
		show_user($user);
	}
	else
	{
		echo'<center>����� �� ������</center>';
	}
}

if (function_exists("save_debug")) save_debug(); 

?>