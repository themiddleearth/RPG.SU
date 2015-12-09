<?php

if (function_exists("start_debug")) start_debug();

if (preg_match('/.inc.php/', $_SERVER['PHP_SELF']))
{
	setLocation('index.php');
}
else
{

function PrintFavNpc()
{
	global $char;
	//��������� ����
	$npc_online = time();
	$sel = myquery("SELECT
	game_npc_template.npc_name AS npc_name,
	game_npc.EXP AS npc_exp,
	game_maps.name AS npc_map_name,
	game_npc.map_name AS npc_map,
	game_npc.xpos AS npc_xpos,
	game_npc.ypos AS npc_ypos,
	game_npc.xpos_view AS npc_xpos_view,
	game_npc.ypos_view AS npc_ypos_view
	FROM game_users_npc, game_npc, game_maps, game_npc_template
	WHERE game_npc.npc_id=game_npc_template.npc_id AND game_users_npc.user_id = ".$char['user_id']." AND game_npc.view=1 AND game_npc.map_name IN (700,5,18) 
	  AND game_npc.id = game_users_npc.npc_id AND game_maps.id=game_npc.map_name AND game_npc.time_kill+game_npc_template.respawn<".$npc_online." 
	ORDER BY  (CASE WHEN game_npc.map_name = ".$char['map_name']." THEN 0 ELSE 1 END), game_users_npc.npc_order");

	if ($sel!=false AND mysql_num_rows($sel)>0)
	{
		echo '<table width="100%" border=0>';
		while ($row = mysql_fetch_array($sel))
		{
			echo'<tr>
			<td>'.substr($row["npc_name"],0,9).'</td>
			<td>'.substr($row["npc_map_name"],0,4).'</td>
			<td><nobr>';
			if ($row["npc_exp"]<=200)
			{
				echo $row["npc_xpos"];
				echo '</td><td align="right"><font color=#FF7DFF>';
				echo $row["npc_ypos"];
			}
			else
			{
				echo ''.$row["npc_xpos"]+1*$row["npc_xpos_view"].'&plusmn;2';
				echo '</td><td align="right"><font color=#FF7DFF>';
				echo ''.$row["npc_ypos"]+1*$row["npc_ypos_view"].'&plusmn;2';
			}
			echo'</td>';
			if ($_SESSION['user_id']==612 OR $_SESSION['user_id']==28591 OR $_SESSION['user_id']==36051 OR $_SESSION['user_id']==1 OR domain_name=='localhost')
			{
				echo '<td><a href="?func=main&teleport_map_name='.$row["npc_map"].'&teleport_map_xpos='.$row["npc_xpos"].'&teleport_map_ypos='.$row["npc_ypos"].'"><img src="http://'.img_domain.'/nav/show.gif" border="0"></a></td>';
			}
			echo '</tr>';
		}
		echo '</table>';
	}
}	

echo '<table cellpadding=0 cellspacing=0 border=0 width=218 background="http://'.img_domain.'/nav1/bar2.gif"><tr><td align="center">';

$sel=myquery("select * from game_port_bil where user_id=".$char['user_id']." and stat='0'");
if (mysql_num_rows($sel) > 0)
{
	$q=mysql_fetch_array($sel);

	$sell=myquery("select * from game_port where id=".$q['bil']."");
	$qq=mysql_fetch_array($sell);
	//OpenTable('title','89%');
	$kuda='<font color=#FFFF80>'.@mysql_result(@myquery("SELECT rustown FROM game_gorod WHERE town='".$qq['town_kuda']."'"),0,0);
	$map = @mysql_fetch_array(@myquery("SELECT * FROM game_map WHERE town='".$qq['town_kuda']."' and to_map_name=0"));
	$map_name = @mysql_result(@myquery("SELECT name FROM game_maps WHERE id='".$map['name']."'"),0,0);
	$kuda.='</font> ('.$map_name.' '.$map['xpos'].','.$map['ypos'].')';
	echo'<table border=0 width=87% align=center><tr><td><center><font size=1>������!!! <br>�� '.echo_sex('�����','������').' ����� � <font color=ff0000>'.$kuda.'</font>!<br>�������� ������� �� ����� ����� �: <font color=ff0000>'.$qq['time'].'</font><br>������:  <font color=ff0000>'.date("H:i").'</font><br>�� ��������� � ��������!<br>������ �� ������������</font>';
	echo'</td></tr></table><img src="http://'.img_domain.'/nav1/bar1.gif">';

	//OpenTable('close');
}



$sel_quest_npc = myquery("SELECT * FROM game_quest_users WHERE user_id=$user_id AND (quest_id>=2 AND quest_id<=7)");
while ($q = mysql_fetch_array($sel_quest_npc))
{
	$npc_quest = mysql_fetch_array(myquery("SELECT game_npc_template.npc_name,game_npc.npc_quest_end_time,game_npc.npc_quest_guild FROM game_npc,game_npc_template WHERE game_npc.id=".$q['sost']." AND game_npc_template.npc_id=game_npc.npc_id"));
	$end_time = $npc_quest['npc_quest_end_time']-time();
	if ($end_time>0)
	{
		//OpenTable('title','89%');
		$min = floor($end_time/60);
		$sec = $end_time-$min*60;
		echo '<table border=0 width=87% align=center><tr><td><font color=#F0F0F0><div align="center" style="font-weight:normal;">������� �� �������� ������� "'.$npc_quest['npc_name'].'" ��������� ��� <font color=#FF0000>'.$min.'</font>  ���. <font color=#FF0000>'.$sec.'</font> ���.</font>';
		//OpenTable('close');
		echo'</td></tr></table><img src="http://'.img_domain.'/nav1/bar1.gif">';
	}
}

OpenTable('title','89%');
echo'<table border=0 width=100% align=center><tr><td><font face=Verdana size=2 color="white"><b><center>'.$char['name'].'  <span title="���� �������">['.$char['clevel'].']</span><span title="������������"> ['.$char['reinc'].']</span>';

//���������� � ������
if ($char['injury']>0)
{
	$injury = ceil($char['injury']/13);
	$title_img = '������� ����� ������������� ���������� '.$char['injury'].' '.pluralForm($char['injury'],'�������','�������','������').'. � ��� � ��� ����� ������������ '.$injury.' �������!';
	echo '&nbsp;<img src="http://'.img_domain.'/nav/ball_red.jpg" title="'.$title_img.'" alt="'.$title_img.'">';
}	
echo '</center></b></font>';


echo '<table cellpadding="1" cellspacing="0" width="100%" border="0">';
if ($char['HP_MAX'] == 0)
{
	$bar_percentage = 0;
}
else
{
	$bar_percentage = number_format($char['HP'] / $char['HP_MAX'] * 100, 0);
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
echo '
<tr>
<td align="left" valign="middle"><font face="Verdana" size="1">��������</font></td>
<td align="right"><font face="Verdana" size="1">' . $char['HP'] . ' / ' . $char['HP_MAX'] . '</font>';
if ($char['HP_MAX']<$char['HP_MAXX'])
{
	echo '<span title="�� '.echo_sex('�������','��������').' ������!" style="font-weight:800;font-size:10px;color:red;">(-'.($char['HP_MAXX']-$char['HP_MAX']).')</span>';
}
echo'<br>
<img src="http://'.img_domain.'/bar/bar_side.gif" width="1" height="7" border="0">'
. $append_string . '<img src="http://'.img_domain.'/bar/bar_side.gif" width="1" height="7" border="0"><br>
<img src="http://'.img_domain.'/nav/x.gif" width="0" height="0" vspace="2" border="0"></td>
</tr>';

if ($char['MP_MAX'] == 0)
{
	$bar_percentage = 0;
}
else
{
	$bar_percentage = number_format($char['MP'] / $char['MP_MAX'] * 100, 0);
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
echo '<tr>
<td align="left" valign="middle"><font face="Verdana" size="1">����</font></td>
<td align="right"><font face="Verdana" size="1">' . $char['MP'] . ' / ' . $char['MP_MAX'] . '</font><br>
<img src="http://'.img_domain.'/bar/bar_side.gif" width="1" height="7" border="0">'
. $append_string . '<img src="http://'.img_domain.'/bar/bar_side.gif" width="1" height="7" border="0"><br>
<img src="http://'.img_domain.'/nav/x.gif" width="0" height="0" vspace="2" border="0"></td>
</tr>';

if ($char['STM_MAX'] == 0)
{
	$bar_percentage = 0;
}
else
{
	$bar_percentage = number_format($char['STM'] / $char['STM_MAX'] * 100, 0);
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
echo '<tr>
<td align="left" valign="middle"><font face="Verdana" size="1">�������</font></td>
<td align="right"><font face="Verdana" size="1">' . $char['STM'] . ' / ' . $char['STM_MAX'] . '</font><br>
<img src="http://'.img_domain.'/bar/bar_side.gif" width="1" height="7" border="0">'
. $append_string . '<img src="http://'.img_domain.'/bar/bar_side.gif" width="1" height="7" border="0"><br>
<img src="http://'.img_domain.'/nav/x.gif" width="0" height="0" vspace="2" border="0"></td>
</tr>';

if ($char['PR'] == 0)
{
	$bar_percentage = 0;
}
else
{
	$bar_percentage = number_format($char['PR'] / $char['PR_MAX'] * 100, 0);
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
echo '<tr>
<td align="left" valign="middle"><font face="Verdana" size="1">�����</font></td>
<td align="right"><font face="Verdana" size="1">' . $char['PR'] . ' / ' . $char['PR_MAX'] . '</font><br>
<img src="http://'.img_domain.'/bar/bar_side.gif" width="1" height="7" border="0">'
. $append_string . '<img src="http://'.img_domain.'/bar/bar_side.gif" width="1" height="7" border="0"><br>
<img src="http://'.img_domain.'/nav/x.gif" width="0" height="0" vspace="2" border="0"></td>
</tr>';

$clevel=$char['clevel'];
$new_clevel=get_new_level($clevel);

if ($char['STM_MAX'] == 0)
{
	$bar_percentage = 0;
}
else
{
	$bar_percentage = number_format($char['EXP'] / $new_clevel * 100, 0);
}
if ($bar_percentage >= 100)
{
	$append_string = '<img src="http://'.img_domain.'/bar/bar_blue.gif" width="100" height="7" border="0">';
}
elseif ($bar_percentage <= 0)
{
	$append_string = '<img src="http://'.img_domain.'/bar/bar_empty.gif" width="100" height="7" border="0">';
}
else
{
	$append_string = '<img src="http://'.img_domain.'/bar/bar_empty.gif" width="' . (100 - $bar_percentage) . '" height="7" border="0"><img src="http://'.img_domain.'/bar/bar_blue.gif" width="' . $bar_percentage . '" height="7" border="0">';
}
echo '<tr>
<td align="left" valign="middle" title="������� ����/���� �� ������"><font face="Verdana" size="1">����</font></td>
<td align="right" title="������� ����/���� �� ������"><font face="Verdana" size="1">' . $char['EXP'] . ' / ' . $new_clevel . '</font><br>
<img src="http://'.img_domain.'/bar/bar_side.gif" width="1" height="7" border="0">'
. $append_string . '<img src="http://'.img_domain.'/bar/bar_side.gif" width="1" height="7" border="0"><br>
<img src="http://'.img_domain.'/nav/x.gif" width="0" height="0" vspace="2" border="0"></td>
</tr>';

echo '
<tr><td align="left"><font face="Verdana" size="1">������</font></td><td align="right"><font face="Verdana" size="1"><img src="http://'.img_domain.'/nav/gold.gif" width="10" height="10" border="0">'.trim($char['GP']).'</font></td></tr>';

echo '</table>';

$combat_func = combat_getFunc($user_id);
//��������� ������
//if ($char['MS_LEK']!='0' and $combat_func!=5 and $combat_func!=6) echo'<br><table cellpadding="1" cellspacing="0" width="100%" border="0"><tr><td><a href="?func=main&lek='.$char['user_id'].'">&nbsp;&nbsp;������ ���� (���.'.$char['MS_LEK'].' ��.)</a></td></tr></table>';
OpenTable('close');


echo'</td></tr></table><img src="http://'.img_domain.'/nav1/bar1.gif"><table border=0 width=90% align=center><tr><td>';

OpenTable('title','103%');
echo '<link id="luna-tab-style-sheet" type="text/css" rel="stylesheet" href="style/tabs/tabpane.css" />';

  //������� �������� ��� ����������� ��������� ��� ��������� �����
  //����������
  echo '
  <table class="adminform" width="100%" border=0>
  <tr><td width="100%" valign="top">

  <script type="text/javascript" src="style/tabs/tabpane.js"></script>
  <div class="tab-page" style="95%" id="modules-cpanel1"><script type="text/javascript">var tabPane0 = new WebFXTabPane( document.getElementById( "modules-cpanel1" ), 1 )</script>

  <div class="tab-page" id="module04"><h6 class="tab">����</h6><script type="text/javascript">tabPane0.addTabPage( document.getElementById( "module04" ) );</script>';

  if ($char['clan_id'] == 0)
  {    
	if ($user_data['last_clan_move'] == 0 or $user_data['vozrast'] - $user_data['last_clan_move'] >= 10) 
	{
		echo("�� �� �������� � �����!");
	}
	else
	{
		echo("�� ������� ����� �������� � ���� ����� <b>".(10-$user_data['vozrast']+$user_data['last_clan_move'])."</b> ".pluralForm((10-$user_data['vozrast']+$user_data['last_clan_move']),'����','���','����')."!");
	}
  }
  else
  {
    $klan=myquery("select * from game_clans where clan_id=".$char['clan_id']." and raz='0'");
    if(mysql_num_rows($klan))
    {
	$clans=mysql_fetch_array($klan);

	echo '<img src="http://'.img_domain.'/clan/'.$clans['clan_id'].'.gif" align=top>&nbsp;<font face=Verdana size=1 color="CCCCCC"><b>'.$clans['nazv'].'</b></font><br>';
	$per=myquery("select view_active_users.*,game_users_map.*,game_maps.name as mapname from view_active_users,game_users_map,game_maps where view_active_users.clan_id=".$char['clan_id']." and view_active_users.user_id<>$user_id and view_active_users.user_id=game_users_map.user_id and game_maps.id=game_users_map.map_name order by name ASC");
	if (mysql_num_rows($per))
	{
		echo '���� ������:<br>';
		while($elf=mysql_fetch_array($per))
		{
			$map_name0 = $elf['map_name'];
			$map_xpos0 = $elf['map_xpos'];
			$map_ypos0 = $elf['map_ypos'];
			if ($user_id==612 OR $user_id==36051 OR $user_id==1 OR $user_id==28591 OR domain_name=='localhost')
			{
				echo '<a href="?func='.$func.'&teleport_map_name='.$map_name0.'&teleport_map_xpos='.$map_xpos0.'&teleport_map_ypos='.$map_ypos0.'"><img src="http://'.img_domain.'/nav/show.gif" border="0"></a>';
			}
			echo''.$elf['name'].' <font color=ff0000> ('.$elf['mapname'].' '.$map_xpos0.', '.$map_ypos0.')</font>';
			if (in_array($elf['delay_reason'],array(2,11,12,14,15,16,17,18,49,48))) echo' <br><font size=1 color=ff0000 face=verdana>'.get_delay_reason($elf['delay_reason']).'</font>';
			echo '<br>';
		}
	}
	else
	{
  		echo'<font color=ff0000>��������� ���</font><br>';
  	}
  	echo'<center><a href="act.php?func=pm&pm=write_clan_online&cl='.$clans['clan_id'].'">�������� ������</a><br>
  		<a href="act.php?func=pm&pm=write_clan&cl='.$clans['clan_id'].'">�������� ����</a></center>';
    }
  }

  // ����
  echo'</div><div class="tab-page" id="module05"><h6 class="tab">����</h6><script type="text/javascript">tabPane0.addTabPage( document.getElementById( "module05" ) );</script>';

	PrintFavNpc();

  //����
  echo'</div><div class="tab-page" id="module06"><h6 class="tab">����</h6><script type="text/javascript">tabPane0.addTabPage( document.getElementById( "module06" ) );</script>';
  $check=myquery("SELECT horse_id FROM game_users_horses WHERE user_id=".$user_id." AND used=1");
	if (mysql_num_rows($check)>0)	
	{
		list($horse_id)=mysql_fetch_array($check);
		echo'<center><font color="#ffff00">'.mysql_result(myquery("SELECT gv.nazv FROM game_vsadnik gv WHERE gv.id=".$horse_id.""),0,0).'</font>';
		$sel_golod = myquery("SELECT game_users_horses.golod,game_users_horses.life,game_vsadnik.life_horse FROM game_users_horses,game_vsadnik WHERE game_users_horses.user_id=$user_id AND game_users_horses.horse_id=".$horse_id." AND game_vsadnik.id=".$horse_id."");
		if ($sel_golod!=false AND mysql_num_rows($sel_golod)>0)
		{
			$horse = mysql_fetch_array($sel_golod);
			switch ($horse['golod'])
			{
				case 0: {
					$state='�����'; 
					$append_string = '<img src="http://'.img_domain.'/bar/bar_green.gif" width="' . (100/6) . '" height="7" border="0"><img src="http://'.img_domain.'/bar/bar_blue.gif" width="' . (100/6) . '" height="7" border="0"><img src="http://'.img_domain.'/bar/bar_yellow.gif" width="' . (100/6) . '" height="7" border="0"><img src="http://'.img_domain.'/bar/bar_orange.gif" width="' . (100/6) . '" height="7" border="0"><img src="http://'.img_domain.'/bar/bar_red.gif" width="' . (100/6) . '" height="7" border="0"><img src="http://'.img_domain.'/bar/bar_red.gif" width="' . (100/6) . '" height="7" border="0">';
				} break;
				case 1: {
					$state='������ ��������'; 
					$append_string = '<img src="http://'.img_domain.'/bar/bar_empty.gif" width="' . (100/6) . '" height="7" border="0"><img src="http://'.img_domain.'/bar/vert.gif" width="1" height="14" border="0"><img src="http://'.img_domain.'/bar/bar_blue.gif" width="' . (100/6) . '" height="7" border="0"><img src="http://'.img_domain.'/bar/bar_yellow.gif" width="' . (100/6) . '" height="7" border="0"><img src="http://'.img_domain.'/bar/bar_orange.gif" width="' . (100/6) . '" height="7" border="0"><img src="http://'.img_domain.'/bar/bar_red.gif" width="' . (100/6) . '" height="7" border="0"><img src="http://'.img_domain.'/bar/bar_red.gif" width="' . (100/6) . '" height="7" border="0">';
				}; break;
				case 2: {
					$state='��������'; 
					$append_string = '<img src="http://'.img_domain.'/bar/bar_empty.gif" width="' . (100/6) . '" height="7" border="0"><img src="http://'.img_domain.'/bar/bar_empty.gif" width="' . (100/6) . '" height="7" border="0"><img src="http://'.img_domain.'/bar/vert.gif" width="1" height="14" border="0"><img src="http://'.img_domain.'/bar/bar_yellow.gif" width="' . (100/6) . '" height="7" border="0"><img src="http://'.img_domain.'/bar/bar_orange.gif" width="' . (100/6) . '" height="7" border="0"><img src="http://'.img_domain.'/bar/bar_red.gif" width="' . (100/6) . '" height="7" border="0"><img src="http://'.img_domain.'/bar/bar_red.gif" width="' . (100/6) . '" height="7" border="0">';
				}; break;
				case 3: {
					$state='����� ��������'; 
					$append_string = '<img src="http://'.img_domain.'/bar/bar_empty.gif" width="' . (100/6) . '" height="7" border="0"><img src="http://'.img_domain.'/bar/bar_empty.gif" width="' . (100/6) . '" height="7" border="0"><img src="http://'.img_domain.'/bar/bar_empty.gif" width="' . (100/6) . '" height="7" border="0"><img src="http://'.img_domain.'/bar/vert.gif" width="1" height="14" border="0"><img src="http://'.img_domain.'/bar/bar_orange.gif" width="' . (100/6) . '" height="7" border="0"><img src="http://'.img_domain.'/bar/bar_red.gif" width="' . (100/6) . '" height="7" border="0"><img src="http://'.img_domain.'/bar/bar_red.gif" width="' . (100/6) . '" height="7" border="0">';
				}; break;
				case 4: {
					$state='������������'; 
					$append_string = '<img src="http://'.img_domain.'/bar/bar_empty.gif" width="' . (100/6) . '" height="7" border="0"><img src="http://'.img_domain.'/bar/bar_empty.gif" width="' . (100/6) . '" height="7" border="0"><img src="http://'.img_domain.'/bar/bar_empty.gif" width="' . (100/6) . '" height="7" border="0"><img src="http://'.img_domain.'/bar/bar_empty.gif" width="' . (100/6) . '" height="7" border="0"><img src="http://'.img_domain.'/bar/vert.gif" width="1" height="14" border="0"><img src="http://'.img_domain.'/bar/bar_red.gif" width="' . (100/6) . '" height="7" border="0"><img src="http://'.img_domain.'/bar/bar_red.gif" width="' . (100/6) . '" height="7" border="0">';
				}; break;
				default: {
					$state='���������'; 
					$append_string = '<img src="http://'.img_domain.'/bar/bar_empty.gif" width="' . (100/6) . '" height="7" border="0"><img src="http://'.img_domain.'/bar/bar_empty.gif" width="' . (100/6) . '" height="7" border="0"><img src="http://'.img_domain.'/bar/bar_empty.gif" width="' . (100/6) . '" height="7" border="0"><img src="http://'.img_domain.'/bar/bar_empty.gif" width="' . (100/6) . '" height="7" border="0"><img src="http://'.img_domain.'/bar/bar_empty.gif" width="' . (100/6) . '" height="7" border="0"><img src="http://'.img_domain.'/bar/vert.gif" width="1" height="14" border="0"><img src="http://'.img_domain.'/bar/bar_red.gif" width="' . (100/6) . '" height="7" border="0">';
				}; break;
			}
			echo '<br /><span title="'.$state.'"><img src="http://'.img_domain.'/bar/bar_side.gif" width="1" height="7" border="0">'
. $append_string . '<img src="http://'.img_domain.'/bar/bar_side.gif" width="1" height="7" border="0"></span>';

			echo '<br />��������� ������ �������: <b><font color="#ffff00">'.$state.'</font></b>';
			echo '<br />�������: '.max(0,$horse['life_horse']-$horse['life']).' / '.$horse['life_horse'];
		}
	}    
	
	echo'</div>

	</td></tr></table>';

	echo '</td></tr></table>';

OpenTable('close');

?>
</td></tr></table>


<table cellpadding=0 cellspacing=0 border=0 width=218 background="http://<?=img_domain;?>/nav1/bar2.gif"><tr><td align="center">


<img src="http://<?=img_domain;?>/nav1/bar1.gif">
<script type="text/javascript">
var chatWindow;
function open_chat()
{
	if (!chatWindow || chatWindow.closed)
	{
		chatWindow = window.open("","chatWindow","status,scrollbars,toolbar,resizable=1,width="+screen.availWidth+",height="+screen.availHeight);
		content = "<html><title>���������� :: ����� �������� :: ���</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1251\"><meta name=\"Keywords\" content=\"������� ������� ������ ���� ���������� ����� �������� online game items �������� �������� ��� ������� rpg ����� ����� �� �������\"><style type=\"text/css\">@import url(\"style/global.css\");</style><frameset id=\"frame_set\" rows=\"0,*\" frameborder=\"0\" border=\"0\" ><frame src=\"\" name=\"menu\" scrolling=\"NO\" marginwidth=\"0\" marginheight=\"0\" NORESIZE><frame src=\"chat/chat.php?full\" name=\"chat\" scrolling=\"no\" marginwidth=\"0\" marginheight=\"0\" frameborder=\"yes\"></frameset><noframes><body></body></noframes>";
		chatWindow.document.write(content);
		chatWindow.document.close();
	}
	else
	{
		if (chatWindow.focus) {
			chatWindow.focus();
		}
	}
}
</script>

<?
OpenTable('title','89%');
echo'
<table width="182" border=0 align=center>
<tr><td valign="top">

<script type="text/javascript" src="style/tabs/tabpane.js"></script>
<div class="tab-page" style="width:182px;" id="modules-cpanel2"><script type="text/javascript">var tabPane2 = new WebFXTabPane( document.getElementById( "modules-cpanel2" ), 1 )</script>

<div class="tab-page" style="width:182px;" id="module13"><h6 class="tab">����</h6><script type="text/javascript">tabPane2.addTabPage( document.getElementById( "module13" ) );</script>';

//��������, �� ����� �� ����� �� �������
$prison_check=mysql_num_rows(myquery("SELECT * FROM game_prison WHERE user_id=$user_id"));
$q=myquery("SELECT COUNT(*) FROM game_pm WHERE komu=$user_id AND view=0") or die(mysql_error());
$q=mysql_result($q, 0, 0);
//���� ����� �� �������, �� ����� ��� ������ �� �����
if($prison_check>0)
	echo '<font color=#FF0000>��������� �������</font><br>';
else
	echo '<a href="http://'.domain_name.'/forum/">��� ����������</a><br>';
echo '<a href="act.php?func=pm&new">';
if ($q>0)
{
	//$chat_mess = '<br><center>� ���� <font size=3 color=#FF0000><b><u>'.$q.'</u></b></font> ������������� ������ ���������!</center><br>';
	echo '<img src="http://'.img_domain.'/pm/new_pm.gif" border="0">';
	echo ''.$q.' '.pluralForm($q, '����� ������', '����� ������', '����� �����').'';
}
else
{
	echo '������ �����';
}
echo '</a><br>';

if($prison_check>0)
	echo '<font color=#FF0000>��� ����������</font><br>';
else
	echo '<a href="#" onclick="open_chat()">��� � ����� ����</a><br>';

echo '<a href="http://'.domain_name.'/view/?view=npc&map='.$char['map_name'].'" target="_blank">����� ���������</a><br>';
//echo '<a href="newmap.php" target="_blank">������������� �����</a><br>';
echo '<a href="http://'.domain_name.'/view/?zakon" target="_blank">������</a><br>';


echo'</div>

<div class="tab-page" style="width:182px;" id="module14"><h6 class="tab">�����</h6><script type="text/javascript">tabPane2.addTabPage( document.getElementById( "module14" ) );</script>';


echo '<a href="act.php?func=setup">������ ���������</a><br>';
echo '<a href="http://'.domain_name.'/view/?journal='.$user_id.'" target="_blank">������ �������</a><br>';
echo '<a href="http://'.domain_name.'/view/?log='.$char['name'].'" target="_blank">���� ����</a><br>';
echo '<a href="act.php?func=npc_fav">��������� ����</a><br>';
echo '<a href="act.php?func=gift">��������</a><br>';
//if (mysql_num_rows(myquery("Select worker_access From workers Where worker_id='".$user_id."'"))==1) echo '<a href="act.php?func=tasks">����������� �����</a><br>';


echo'</div>


<div class="tab-page" style="width:182px;" id="module15"><h6 class="tab">������</h6><script type="text/javascript">tabPane2.addTabPage( document.getElementById( "module15" ) );</script>';
echo '<a href="http://'.domain_name.'/view/?help" target="_blank">������</a><br>';
//���� ����� �� �������, �� ����� ��� ������ �� ��������
if($prison_check>0)
	echo '<font color=#FF0000>�������� ����������</font><br>';
else
	echo '<a href="http://'.domain_name.'/diary/">��������</a><br>';
echo '<a href="http://'.domain_name.'/info/" target="_blank">������������</a><br>';
echo '<a href="http://'.domain_name.'/view/?clan" target="_blank">����� C���������</a><br>';
echo '<a href="http://'.domain_name.'/view/?top" target="_blank">������� �������</a><br>';

echo'</div>

</td></tr></table>';

OpenTable('close');
echo '</tr></td></table><table cellpadding=0 cellspacing=0 border=0 width=218 background="http://'.img_domain.'/nav1/bar2.gif" height=100%><tr><td></td></tr></table></td></tr></table>';

}

if (function_exists("save_debug")) save_debug();

?>