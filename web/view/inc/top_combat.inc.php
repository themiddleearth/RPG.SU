<?

if (function_exists("start_debug")) start_debug(); 

$result = myquery("(SELECT name,user_id,win,clan_id FROM game_users WHERE clan_id<>1 AND clan_id<>4) UNION (SELECT name,user_id,win,clan_id FROM game_users_archive WHERE clan_id<>1 AND clan_id<>4) ORDER BY win DESC LIMIT 10");
echo'<table cellpadding="0" cellspacing="4" border=0><tr><td width="250"><font face="Verdana" size="3" color="#f3f3f3"><b>����� �������</b></font><br></td><td width="50"><font size="2" color="#eeeeee">����</font></td><td width="220"><font size="2" color="#eeeeee">���</font></td><td width="220"><font size="2" color="#eeeeee">�����</font></td></tr>';
for ($i = 1; $player = mysql_fetch_array($result); $i++)
{
echo'<tr><td></td><td><font size="2" color="#bbbbbb">' . $i . '</font></td><td><font size="2" color="#bbbbbb"><a href="http://'.domain_name.'/view/?userid='.$player["user_id"].'" target="_blank"><img src="http://'.img_domain.'/nav/i.gif" border=0 alt="����"></a>';
	if ($player['clan_id']!='0') echo'<img src="http://'.img_domain.'/clan/'.$player['clan_id'].'.gif"> ';
echo'' . $player['name'] . '</font></td><td>' . $player['win'] . '</td></tr>';
}
echo'</table><br>';


//���������� �� �����
$sel_race = myquery("SELECT * FROM game_har WHERE disable=0 ORDER BY name");
while ($race = mysql_fetch_array($sel_race))
{
	$rac = '';
	switch ($race['race'])
	{
		case 'Elf':
			$rac = '������';
		break;
		case 'Orc':
			$rac = '�����';
		break;
		case 'Nazgul':
			$rac = '��������';
		break;
		case 'Hobbit':
			$rac = '��������';
		break;
		case 'Human':
			$rac = '�����';
		break;
		case 'Gnom':
			$rac = '������';
		break;
		case 'Goblin':
			$rac = '��������';
		break;
		case 'Troll':
			$rac = '�������';
		break;
	}
	$result = myquery("	
	(SELECT gu.clan_id, gu.name, gu.clevel, gu.user_id,  gu.exp, count(gur.reincarnation_date) as reinc
	FROM game_users gu
	LEFT JOIN game_users_reincarnation gur On gu.user_id=gur.user_id
	WHERE clan_id<>1 AND clan_id<>4 AND gu.race=".$race['id']."
	GROUP BY gu.clan_id, gu.name, gu.clevel, gu.user_id,  gu.exp)
	UNION
	(SELECT gu.clan_id, gu.name, gu.clevel, gu.user_id,  gu.exp, count(gur.reincarnation_date) as reinc
	FROM game_users_archive gu
	LEFT JOIN game_users_reincarnation gur On gu.user_id=gur.user_id
	WHERE clan_id<>1 AND clan_id<>4 AND gu.race=".$race['id']."
	GROUP BY gu.clan_id, gu.name, gu.clevel, gu.user_id,  gu.exp)
	ORDER BY reinc DESC, clevel DESC, exp DESC 
	LIMIT 10");
	echo '<table cellpadding="0" cellspacing="4" border=0><tr><td width="250"><font face="Verdana" size="3" color="#f3f3f3"><b>10 ������ '.$rac.'</b></font><br></td><td width="50"><font size="2" color="#eeeeee">����</font></td><td width="220"><font size="2" color="#eeeeee">���</font></td><td width="220"><font size="2" color="#eeeeee">������������ (�������)</font></td></tr>';
	for ($i = 1; $player = mysql_fetch_array($result); $i++)
	{
		echo'<tr><td></td><td><font size="2" color="#bbbbbb">' . $i . '</font></td><td><font size="2" color="#bbbbbb"><a href="http://'.domain_name.'/view/?userid='.$player["user_id"].'" target="_blank"><img src="http://'.img_domain.'/nav/i.gif" border=0 alt="����"></a>';
			if ($player['clan_id']!='0') echo'<img src="http://'.img_domain.'/clan/'.$player['clan_id'].'.gif"> ';
		echo'' . $player['name'] . '</font></td><td>'.$player['reinc'].' ('.$player['clevel'].')</td></tr>';
	  }
	echo '</table><br>';
}

//���� �� ����������
$result = myquery("(SELECT * FROM game_users WHERE sklon=1 AND clan_id<>1 ORDER BY win DESC  LIMIT 10) UNION (SELECT * FROM game_users_archive WHERE sklon=1 AND clan_id<>1 ORDER BY win DESC  LIMIT 10) ORDER BY win DESC LIMIT 10");
echo '<table cellpadding="0" cellspacing="4" border=0><tr><td width="250"><font face="Verdana" size="3" color="#f3f3f3"><b>10 ������ ������� ����������� ����������</b></font><br></td><td width="50"><font size="2" color="#eeeeee">����</font></td><td width="220"><font size="2" color="#eeeeee">���</font></td><td width="220"><font size="2" color="#eeeeee">���-�� �����</font></td></tr>';
for ($i = 1; $player = mysql_fetch_array($result); $i++)
{
	echo'<tr><td></td><td><font size="2" color="#bbbbbb">' . $i . '</font></td><td><font size="2" color="#bbbbbb"><a href="http://'.domain_name.'/view/?userid='.$player["user_id"].'" target="_blank"><img src="http://'.img_domain.'/nav/i.gif" border=0 alt="����"></a>';
		if ($player['clan_id']!='0') echo'<img src="http://'.img_domain.'/clan/'.$player['clan_id'].'.gif"> ';
	echo'' . $player['name'] . '</font></td><td>' . $player['win'] . '</td></tr>';
  }
echo '</table><br>';

$result = myquery("(SELECT * FROM game_users WHERE sklon=2 AND clan_id<>1 ORDER BY win DESC  LIMIT 10) UNION (SELECT * FROM game_users_archive WHERE sklon=2 AND clan_id<>1 ORDER BY win DESC  LIMIT 10) ORDER BY win DESC LIMIT 10");
echo '<table cellpadding="0" cellspacing="4" border=0><tr><td width="250"><font face="Verdana" size="3" color="#f3f3f3"><b>10 ������ ������� ������� ����������</b></font><br></td><td width="50"><font size="2" color="#eeeeee">����</font></td><td width="220"><font size="2" color="#eeeeee">���</font></td><td width="220"><font size="2" color="#eeeeee">���-�� �����</font></td></tr>';
for ($i = 1; $player = mysql_fetch_array($result); $i++)
{
	echo'<tr><td></td><td><font size="2" color="#bbbbbb">' . $i . '</font></td><td><font size="2" color="#bbbbbb"><a href="http://'.domain_name.'/view/?userid='.$player["user_id"].'" target="_blank"><img src="http://'.img_domain.'/nav/i.gif" border=0 alt="����"></a>';
		if ($player['clan_id']!='0') echo'<img src="http://'.img_domain.'/clan/'.$player['clan_id'].'.gif"> ';
	echo'' . $player['name'] . '</font></td><td>' . $player['win'] . '</td></tr>';
  }
echo '</table><br>';

$result = myquery("(SELECT * FROM game_users WHERE sklon=3 AND clan_id<>1 ORDER BY win DESC  LIMIT 10) UNION (SELECT * FROM game_users_archive WHERE sklon=3 AND clan_id<>1 ORDER BY win DESC  LIMIT 10) ORDER BY win DESC LIMIT 10");
echo '<table cellpadding="0" cellspacing="4" border=0><tr><td width="250"><font face="Verdana" size="3" color="#f3f3f3"><b>10 ������ ������� ������ ����������</b></font><br></td><td width="50"><font size="2" color="#eeeeee">����</font></td><td width="220"><font size="2" color="#eeeeee">���</font></td><td width="220"><font size="2" color="#eeeeee">���-�� �����</font></td></tr>';
for ($i = 1; $player = mysql_fetch_array($result); $i++)
{
	echo'<tr><td></td><td><font size="2" color="#bbbbbb">' . $i . '</font></td><td><font size="2" color="#bbbbbb"><a href="http://'.domain_name.'/view/?userid='.$player["user_id"].'" target="_blank"><img src="http://'.img_domain.'/nav/i.gif" border=0 alt="����"></a>';
		if ($player['clan_id']!='0') echo'<img src="http://'.img_domain.'/clan/'.$player['clan_id'].'.gif"> ';
	echo'' . $player['name'] . '</font></td><td>' . $player['win'] . '</td></tr>';
  }
echo '</table><br>';


if (function_exists("save_debug")) save_debug(); 

?>