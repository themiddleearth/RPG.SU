<?
if (function_exists("start_debug")) start_debug(); 

$result = myquery("
(SELECT gu.clan_id, gu.name, gu.clevel, gu.user_id,  gu.exp, count(gur.reincarnation_date) as reinc
FROM game_users gu
LEFT JOIN game_users_reincarnation gur On gu.user_id=gur.user_id
WHERE clan_id<>1 AND clan_id<>4
GROUP BY gu.clan_id, gu.name, gu.clevel, gu.user_id,  gu.exp)
UNION
(SELECT gu.clan_id, gu.name, gu.clevel, gu.user_id,  gu.exp, count(gur.reincarnation_date) as reinc
FROM game_users_archive gu
LEFT JOIN game_users_reincarnation gur On gu.user_id=gur.user_id
WHERE clan_id<>1 AND clan_id<>4
GROUP BY gu.clan_id, gu.name, gu.clevel, gu.user_id,  gu.exp)
ORDER BY reinc DESC, clevel DESC, exp DESC 
LIMIT 10");
echo '<table cellpadding="0" cellspacing="4" border=0><tr><td width="250"><font face="Verdana" size="3" color="#f3f3f3"><b>10 лучших</b></font><br></td><td width="50"><font size="2" color="#eeeeee">Ранг</font></td><td width="220"><font size="2" color="#eeeeee">Ник</font></td><td width="220"><font size="2" color="#eeeeee">Реинкарнация (Уровень)</font></td></tr>';
for ($i = 1; $player = mysql_fetch_array($result); $i++)
{
	echo'<tr><td></td><td><font size="2" color="#bbbbbb">' . $i . '</font></td><td><font size="2" color="#bbbbbb"><a href="http://'.domain_name.'/view/?userid='.$player["user_id"].'" target="_blank"><img src="http://'.img_domain.'/nav/i.gif" border=0 alt="Инфо"></a>';
		if ($player['clan_id']!='0') echo'<img src="http://'.img_domain.'/clan/'.$player['clan_id'].'.gif"> ';
	echo'' . $player['name'] . '</font></td><td>'.$player['reinc'].' ('.$player['clevel'].')</td></tr>';
  }
echo '</table><br>';



$result = myquery("SELECT DISTINCT user_id, COUNT(*) AS items FROM game_items WHERE user_id!=0 AND priznak=0 AND user_id NOT IN (SELECT user_id FROM game_users WHERE clan_id=1) GROUP BY user_id ORDER BY items DESC LIMIT 10");
echo '<table cellpadding="0" cellspacing="4" border=0><tr><td width="250"><font face="Verdana" size="3" color="#f3f3f3"><b>Самые выносливые</b></font><br></td><td width="50"><font size="2" color="#eeeeee">Ранг</font></td><td width="220"><font size="2" color="#eeeeee">Ник</font></td><td width="220"><font size="2" color="#eeeeee">Вещей в инвентаре</font></td></tr>';
for ($i = 1; $player = mysql_fetch_array($result); $i++)
{
	$result_name = myquery("SELECT name,clan_id FROM game_users WHERE user_id=" . $player['user_id'] . " LIMIT 1");
	if (!mysql_num_rows($result_name))    $result_name = myquery("SELECT name,clan_id FROM game_users_archive WHERE user_id=" . $player['user_id'] . " LIMIT 1");
	list($greedy_player,$clan_id) = mysql_fetch_array($result_name);
	echo '<tr><td></td><td><font size="2" color="#bbbbbb">' . $i . '</font></td><td><font size="2" color="#bbbbbb"><a href="http://'.domain_name.'/view/?userid='.$player['user_id'].'" target="_blank"><img src="http://'.img_domain.'/nav/i.gif" border=0 alt="Инфо"></a>';
	if ($clan_id!='0') echo'<img src="http://'.img_domain.'/clan/'.$clan_id.'.gif"> ';
	echo '' . $greedy_player . '</a></font></td><td><font size="2" color="#bbbbbb">' . $player['items'] . '</font></td></tr>';
}
echo '</table><br>';

$result = myquery("(SELECT name,user_id,arcomage_win,clan_id FROM game_users WHERE clan_id<>1 AND clan_id<>4) UNION (SELECT name,user_id,arcomage_win,clan_id FROM game_users_archive WHERE clan_id<>1 AND clan_id<>4) ORDER BY arcomage_win DESC LIMIT 10");
echo'<table cellpadding="0" cellspacing="4" border=0><tr><td width="250"><font face="Verdana" size="3" color="#f3f3f3"><b>Самые умные</b></font><br></td><td width="50"><font size="2" color="#eeeeee">Ранг</font></td><td width="220"><font size="2" color="#eeeeee">Ник</font></td><td width="220"><font size="2" color="#eeeeee">Побед в Две Башни</font></td></tr>';
for ($i = 1; $player = mysql_fetch_array($result); $i++)
{
echo'<tr><td></td><td><font size="2" color="#bbbbbb">' . $i . '</font></td><td><font size="2" color="#bbbbbb"><a href="http://'.domain_name.'/view/?userid='.$player["user_id"].'" target="_blank"><img src="http://'.img_domain.'/nav/i.gif" border=0 alt="Инфо"></a>';
	if ($player['clan_id']!='0') echo'<img src="http://'.img_domain.'/clan/'.$player['clan_id'].'.gif"> ';
echo'' . $player['name'] . '</font></td><td>' . $player['arcomage_win'] . '</td></tr>';
}
echo'</table><br>';

if (function_exists("save_debug")) save_debug(); 
?>