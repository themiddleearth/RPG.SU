<?php
//���� ��� ������� ������ 12 �����

if (defined("TEST_MODE") AND TEST_MODE) die();

include("config.inc.php");

move_teleport(720);

mysql_query("set wait_timeout = 1800");

function DbConnectStat()
{
	$db_stat = mysql_connect('localhost', 'rpgsu_stats', 'EuTh4fsFjdvMMuSY') or die(mysql_error());
	mysql_select_db('rpgsu_stats',$db_stat) or die(mysql_error());
}

DbConnect();

//������ ������ �� ����������:
myquery("DELETE FROM game_cron_log WHERE cron='every_12hour' AND step='final'");
myquery("INSERT INTO game_cron_log (cron,step,timecron) VALUES ('every_12hour','������',".time().")");
$idcronlog = mysql_insert_id();
 
myquery("UPDATE game_cron_log SET step='1���������� ����������', timecron=".time()." WHERE id=$idcronlog");
DbConnectStat();
myquery("TRUNCATE TABLE game_stat_view") or die();


//myquery("DELETE FROM game_stat_view WHERE stat_id=5 AND npc_id<>0");
$game=myquery("SELECT game_stat.npc_id, COUNT( * ) AS npc_kill , game_stat.npc_id AS npc_id
FROM game_stat
LEFT JOIN gamerpgsu.game_npc ON gamerpgsu.game_npc.id = game_stat.npc_id
WHERE gamerpgsu.game_npc.view=1 AND game_stat.stat_id = '5' AND gamerpgsu.game_npc.map_name IN (5,18)
GROUP BY game_stat.npc_id ORDER BY npc_kill DESC limit 5");
while($elf=mysql_fetch_array($game))
{
	myquery("INSERT INTO game_stat_view (stat_id,substat_id,npc_id,npc_kill) VALUES (5,1,'".$elf['npc_id']."','".$elf['npc_kill']."')");
}


myquery("UPDATE game_cron_log SET step='2���������� ����������', timecron=".time()." WHERE id=$idcronlog");


$game=myquery("SELECT game_stat.npc_id, COUNT( * ) AS npc_kill , game_stat.npc_id AS npc_id
FROM game_stat
LEFT JOIN gamerpgsu.game_npc ON gamerpgsu.game_npc.id = game_stat.npc_id
WHERE gamerpgsu.game_npc.view=1 AND game_stat.stat_id = '2' AND gamerpgsu.game_npc.map_name IN (5,18)
GROUP BY game_stat.npc_id ORDER BY npc_kill DESC limit 5");
while($elf=mysql_fetch_array($game))
{
	myquery("INSERT INTO game_stat_view (stat_id,substat_id,npc_id,npc_kill) VALUES (2,1,'".$elf['npc_id']."','".$elf['npc_kill']."')");
	
}


myquery("UPDATE game_cron_log SET step='3���������� ����������', timecron=".time()." WHERE id=$idcronlog");


$game=myquery("SELECT COUNT( * ) AS npc_kill, game_stat.user_id AS user_id
FROM game_stat
LEFT JOIN gamerpgsu.game_npc ON gamerpgsu.game_npc.id = game_stat.npc_id
WHERE gamerpgsu.game_npc.view=1 AND game_stat.stat_id = '5' AND gamerpgsu.game_npc.map_name IN (5,18)
GROUP BY game_stat.user_id ORDER BY npc_kill DESC limit 5");
while($elf=mysql_fetch_array($game))
{
	myquery("INSERT INTO game_stat_view (stat_id,substat_id,user_id,npc_kill) VALUES (5,2,'".$elf['user_id']."','".$elf['npc_kill']."')");
}


myquery("UPDATE game_cron_log SET step='4���������� ����������', timecron=".time()." WHERE id=$idcronlog");

$game=myquery("SELECT COUNT(*) AS npc_kill, game_stat.user_id AS user_id
FROM game_stat
LEFT JOIN gamerpgsu.game_npc ON gamerpgsu.game_npc.id = game_stat.npc_id
WHERE gamerpgsu.game_npc.view =1 AND game_stat.stat_id = '2' AND gamerpgsu.game_npc.map_name IN (5,18)
GROUP BY game_stat.user_id ORDER BY npc_kill DESC limit 5");
while($elf=mysql_fetch_array($game))
{
	myquery("INSERT INTO game_stat_view (stat_id,substat_id,user_id,npc_kill) VALUES (2,2,'".$elf['user_id']."','".$elf['npc_kill']."')");
}

myquery("UPDATE game_cron_log SET step='5���������� ����������', timecron=".time()." WHERE id=$idcronlog");


$game=myquery("SELECT game_stat.town AS town, COUNT(*) AS kol, SUM(game_stat.GP) AS summa
FROM game_stat
WHERE game_stat.town <> '74'
AND game_stat.town <> '8'
AND game_stat.town <> '14'
AND game_stat.town <> '80'
AND game_stat.stat_id = '14'
GROUP BY game_stat.town ORDER BY kol DESC
LIMIT 5 ");
while($elf=mysql_fetch_array($game))
{
	myquery("INSERT INTO game_stat_view (stat_id,substat_id,town_id,kol,summa) VALUES (14,1,'".$elf['town']."','".$elf['kol']."','".$elf['summa']."')");
}

myquery("UPDATE game_cron_log SET step='6���������� ����������', timecron=".time()." WHERE id=$idcronlog");


$game=myquery("SELECT COUNT(*) AS kol, game_stat.user_id AS user_id, SUM(game_stat.GP) AS summa
FROM game_stat
WHERE game_stat.town <> '74'
AND game_stat.town <> '8'
AND game_stat.town <> '14'
AND game_stat.town <> '80'
AND game_stat.stat_id = '14'
GROUP BY game_stat.user_id ORDER BY summa DESC
LIMIT 5 ");
while($elf=mysql_fetch_array($game))
{
	myquery("INSERT INTO game_stat_view (stat_id,substat_id,user_id,kol,summa) VALUES (14,2,'".$elf['user_id']."','".$elf['kol']."','".$elf['summa']."')");
}

myquery("UPDATE game_cron_log SET step='7���������� ����������', timecron=".time()." WHERE id=$idcronlog");


$game=myquery("SELECT COUNT(*) AS kol, game_stat.item_id AS item_id, game_stat.town AS town, SUM( game_stat.GP ) AS summa
FROM game_stat
WHERE game_stat.town <> '74'
AND game_stat.town <> '8'
AND game_stat.town <> '14'
AND game_stat.town <> '80'
AND game_stat.stat_id = '14'
GROUP BY game_stat.item_id ORDER BY kol DESC
LIMIT 5 ");
while($elf=mysql_fetch_array($game))
{
	myquery("INSERT INTO game_stat_view (stat_id,substat_id,town_id,eat_id,kol,summa) VALUES (14,3,'".$elf['town']."','".$elf['item_id']."','".$elf['kol']."','".$elf['summa']."')");
}

myquery("UPDATE game_cron_log SET step='8���������� ����������', timecron=".time()." WHERE id=$idcronlog");

$game=myquery("SELECT COUNT(*) AS kol, game_stat.item_id AS item_id, game_stat.town AS town, SUM( game_stat.GP ) AS summa
FROM game_stat
WHERE game_stat.town <> '74'
AND game_stat.town <> '8'
AND game_stat.town <> '14'
AND game_stat.town <> '80'
AND game_stat.stat_id = '14'
GROUP BY game_stat.item_id ORDER BY summa DESC
LIMIT 5 ");
while($elf=mysql_fetch_array($game))
{
	myquery("INSERT INTO game_stat_view (stat_id,substat_id,town_id,eat_id,kol,summa) VALUES (14,4,'".$elf['town']."','".$elf['item_id']."','".$elf['kol']."','".$elf['summa']."')");
}


myquery("UPDATE game_cron_log SET step='9���������� ����������', timecron=".time()." WHERE id=$idcronlog");


$game=myquery("SELECT game_stat.town AS town, COUNT( * ) AS kol, SUM( game_stat.GP ) AS summa
FROM game_stat
WHERE game_stat.town <> '74'
AND game_stat.town <> '8'
AND game_stat.town <> '14'
AND game_stat.town <> '80'
AND game_stat.stat_id = '9'
GROUP BY game_stat.town ORDER BY kol DESC
LIMIT 5 ");
while($elf=mysql_fetch_array($game))
{
	myquery("INSERT INTO game_stat_view (stat_id,substat_id,town_id,kol,summa) VALUES (9,1,'".$elf['town']."','".$elf['kol']."','".$elf['summa']."')");
}


myquery("UPDATE game_cron_log SET step='10���������� ����������', timecron=".time()." WHERE id=$idcronlog");


$game=myquery("SELECT game_stat.town AS town, COUNT( * ) AS kol, SUM( game_stat.GP ) AS summa
FROM game_stat
WHERE game_stat.town <> '74'
AND game_stat.town <> '8'
AND game_stat.town <> '14'
AND game_stat.town <> '80'
AND game_stat.stat_id = '9'
GROUP BY game_stat.town ORDER BY summa DESC
LIMIT 5 ");
while($elf=mysql_fetch_array($game))
{
	myquery("INSERT INTO game_stat_view (stat_id,substat_id,town_id,kol,summa) VALUES (9,2,'".$elf['town']."','".$elf['kol']."','".$elf['summa']."')");
}

myquery("UPDATE game_cron_log SET step='11���������� ����������', timecron=".time()." WHERE id=$idcronlog");


$game=myquery("SELECT COUNT( * ) AS kol, SUM( game_stat.GP ) AS summa, game_stat.enemy_id AS user_id
FROM game_stat
WHERE game_stat.town <> '74'
AND game_stat.town <> '8'
AND game_stat.town <> '14'
AND game_stat.town <> '80'
AND game_stat.stat_id = '9'
GROUP BY game_stat.enemy_id ORDER BY kol DESC
LIMIT 5 ");
while($elf=mysql_fetch_array($game))
{
	myquery("INSERT INTO game_stat_view (stat_id,substat_id,user_id,kol,summa) VALUES (9,3,'".$elf['user_id']."','".$elf['kol']."','".$elf['summa']."')");
}

myquery("UPDATE game_cron_log SET step='12���������� ����������', timecron=".time()." WHERE id=$idcronlog");


$game=myquery("SELECT COUNT( * ) AS kol, SUM( game_stat.GP ) AS summa, game_stat.enemy_id AS user_id
FROM game_stat
WHERE game_stat.town <> '74'
AND game_stat.town <> '8'
AND game_stat.town <> '14'
AND game_stat.town <> '80'
AND game_stat.stat_id = '9'
GROUP BY game_stat.enemy_id ORDER BY summa DESC
LIMIT 5 ");
while($elf=mysql_fetch_array($game))
{
	myquery("INSERT INTO game_stat_view (stat_id,substat_id,user_id,kol,summa) VALUES (9,4,'".$elf['user_id']."','".$elf['kol']."','".$elf['summa']."')");
}

myquery("UPDATE game_cron_log SET step='13���������� ����������', timecron=".time()." WHERE id=$idcronlog");

$game=myquery("SELECT COUNT( * ) AS kol, SUM( game_stat.GP ) AS summa, game_stat.item_id AS ident
FROM game_stat
WHERE game_stat.town <> '74'
AND game_stat.town <> '8'
AND game_stat.town <> '14'
AND game_stat.town <> '80'
AND game_stat.stat_id = '9'
GROUP BY game_stat.item_id ORDER BY kol DESC
LIMIT 5 ");
while($elf=mysql_fetch_array($game))
{
	myquery("INSERT INTO game_stat_view (stat_id,substat_id,item_name,kol,summa) VALUES (9,5,'".$elf['ident']."','".$elf['kol']."','".$elf['summa']."')");
}

myquery("UPDATE game_cron_log SET step='14���������� ����������', timecron=".time()." WHERE id=$idcronlog");

$game=myquery("SELECT COUNT( * ) AS kol, SUM( game_stat.GP ) AS summa, game_stat.item_id AS ident
FROM game_stat
WHERE game_stat.town <> '74'
AND game_stat.town <> '8'
AND game_stat.town <> '14'
AND game_stat.town <> '80'
AND game_stat.stat_id = '9'
GROUP BY game_stat.item_id ORDER BY summa DESC
LIMIT 5 ");
while($elf=mysql_fetch_array($game))
{
	myquery("INSERT INTO game_stat_view (stat_id,substat_id,item_name,kol,summa) VALUES (9,6,'".$elf['ident']."','".$elf['kol']."','".$elf['summa']."')");
}


myquery("UPDATE game_cron_log SET step='15���������� ����������', timecron=".time()." WHERE id=$idcronlog");

$game=myquery("SELECT COUNT( * ) AS kol, SUM( game_stat.GP ) AS summa, game_stat.shop_id AS shop_id
FROM game_stat
WHERE game_stat.stat_id = '10'
AND game_stat.shop_id != '20'
GROUP BY game_stat.shop_id ORDER BY kol DESC
LIMIT 5 ");
while($elf=mysql_fetch_array($game))
{
	myquery("INSERT INTO game_stat_view (stat_id,substat_id,shop_id,kol,summa) VALUES (10,1,'".$elf['shop_id']."','".$elf['kol']."','".$elf['summa']."')");
}

myquery("UPDATE game_cron_log SET step='16���������� ����������', timecron=".time()." WHERE id=$idcronlog");

$game=myquery("SELECT COUNT( * ) AS kol, SUM( game_stat.GP ) AS summa, game_stat.shop_id AS shop_id
FROM game_stat
WHERE game_stat.stat_id = '10'
AND game_stat.shop_id != '20'
GROUP BY game_stat.shop_id ORDER BY summa DESC
LIMIT 5 ");
while($elf=mysql_fetch_array($game))
{
	myquery("INSERT INTO game_stat_view (stat_id,substat_id,shop_id,kol,summa) VALUES (10,2,'".$elf['shop_id']."','".$elf['kol']."','".$elf['summa']."')");
}

myquery("UPDATE game_cron_log SET step='17���������� ����������', timecron=".time()." WHERE id=$idcronlog");


$game=myquery("SELECT COUNT( * ) AS kol, SUM( game_stat.GP ) AS summa, game_stat.shop_id AS shop_id
FROM game_stat
WHERE game_stat.stat_id = '11'
AND game_stat.shop_id != '20'
GROUP BY game_stat.shop_id ORDER BY kol DESC
LIMIT 5 ");
while($elf=mysql_fetch_array($game))
{
	myquery("INSERT INTO game_stat_view (stat_id,substat_id,shop_id,kol,summa) VALUES (11,1,'".$elf['shop_id']."','".$elf['kol']."','".$elf['summa']."')");
}

myquery("UPDATE game_cron_log SET step='18���������� ����������', timecron=".time()." WHERE id=$idcronlog");

$game=myquery("SELECT COUNT( * ) AS kol, SUM( game_stat.GP ) AS summa, game_stat.shop_id AS shop_id
FROM game_stat
WHERE game_stat.stat_id = '11'
AND game_stat.shop_id != '20'
GROUP BY game_stat.shop_id ORDER BY summa DESC
LIMIT 5 ");
while($elf=mysql_fetch_array($game))
{
	myquery("INSERT INTO game_stat_view (stat_id,substat_id,shop_id,kol,summa) VALUES (11,2,'".$elf['shop_id']."','".$elf['kol']."','".$elf['summa']."')");
}

myquery("UPDATE game_cron_log SET step='19���������� ����������', timecron=".time()." WHERE id=$idcronlog");


$game=myquery("SELECT COUNT( * ) AS kol, SUM( game_stat.GP ) AS summa, game_stat.shop_id AS shop_id
FROM game_stat
WHERE game_stat.stat_id = '13'
AND game_stat.shop_id != '20'
GROUP BY game_stat.shop_id ORDER BY kol DESC
LIMIT 5 ");
while($elf=mysql_fetch_array($game))
{
	myquery("INSERT INTO game_stat_view (stat_id,substat_id,shop_id,kol,summa) VALUES (13,1,'".$elf['shop_id']."','".$elf['kol']."','".$elf['summa']."')");
}

myquery("UPDATE game_cron_log SET step='20���������� ����������', timecron=".time()." WHERE id=$idcronlog");


$game=myquery("SELECT COUNT( * ) AS kol, SUM( game_stat.GP ) AS summa, game_stat.shop_id AS shop_id
FROM game_stat
WHERE game_stat.stat_id = '13'
AND game_stat.shop_id != '20'
GROUP BY game_stat.shop_id ORDER BY summa DESC
LIMIT 5 ");
while($elf=mysql_fetch_array($game))
{
	myquery("INSERT INTO game_stat_view (stat_id,substat_id,shop_id,kol,summa) VALUES (13,2,'".$elf['shop_id']."','".$elf['kol']."','".$elf['summa']."')");
}


myquery("UPDATE game_cron_log SET step='21���������� ����������', timecron=".time()." WHERE id=$idcronlog");


$game=myquery("SELECT COUNT( * ) AS kol, SUM( game_stat.GP ) AS summa, game_stat.shop_id AS shop_id
FROM game_stat
WHERE game_stat.stat_id = '12'
AND game_stat.shop_id != '20'
GROUP BY game_stat.shop_id ORDER BY kol DESC
LIMIT 5 ");
while($elf=mysql_fetch_array($game))
{
	myquery("INSERT INTO game_stat_view (stat_id,substat_id,shop_id,kol,summa) VALUES (12,1,'".$elf['shop_id']."','".$elf['kol']."','".$elf['summa']."')");
}


myquery("UPDATE game_cron_log SET step='22���������� ����������', timecron=".time()." WHERE id=$idcronlog");


$game=myquery("SELECT COUNT( * ) AS kol, SUM( game_stat.GP ) AS summa, game_stat.shop_id AS shop_id
FROM game_stat
WHERE game_stat.stat_id = '12'
AND game_stat.shop_id != '20'
GROUP BY game_stat.shop_id ORDER BY summa DESC
LIMIT 5 ");
while($elf=mysql_fetch_array($game))
{
	myquery("INSERT INTO game_stat_view (stat_id,substat_id,shop_id,kol,summa) VALUES (12,2,'".$elf['shop_id']."','".$elf['kol']."','".$elf['summa']."')");
}

myquery("UPDATE game_cron_log SET step='23���������� ����������', timecron=".time()." WHERE id=$idcronlog");


$game=myquery("SELECT COUNT( * ) AS kol, SUM( game_stat.GP ) AS summa, game_stat.user_id AS user_id
FROM game_stat
WHERE game_stat.stat_id = '10'
AND game_stat.shop_id != '20'
GROUP BY game_stat.user_id ORDER BY kol DESC
LIMIT 5 ");
while($elf=mysql_fetch_array($game))
{
	myquery("INSERT INTO game_stat_view (stat_id,substat_id,user_id,kol,summa) VALUES (10,3,'".$elf['user_id']."','".$elf['kol']."','".$elf['summa']."')");
}


myquery("UPDATE game_cron_log SET step='24���������� ����������', timecron=".time()." WHERE id=$idcronlog");


$game=myquery("SELECT COUNT( * ) AS kol, SUM( game_stat.GP ) AS summa, game_stat.user_id AS user_id
FROM game_stat
WHERE game_stat.stat_id = '10'
AND game_stat.shop_id != '20'
GROUP BY game_stat.user_id ORDER BY summa DESC
LIMIT 5 ");
while($elf=mysql_fetch_array($game))
{
	myquery("INSERT INTO game_stat_view (stat_id,substat_id,user_id,kol,summa) VALUES (10,4,'".$elf['user_id']."','".$elf['kol']."','".$elf['summa']."')");
}


myquery("UPDATE game_cron_log SET step='25���������� ����������', timecron=".time()." WHERE id=$idcronlog");


$game=myquery("SELECT COUNT( * ) AS kol, SUM( game_stat.GP ) AS summa, game_stat.user_id AS user_id
FROM game_stat
WHERE game_stat.stat_id = '11'
AND game_stat.shop_id != '20'
GROUP BY game_stat.user_id ORDER BY kol DESC
LIMIT 5 ");
while($elf=mysql_fetch_array($game))
{
	myquery("INSERT INTO game_stat_view (stat_id,substat_id,user_id,kol,summa) VALUES (11,3,'".$elf['user_id']."','".$elf['kol']."','".$elf['summa']."')");
}

myquery("UPDATE game_cron_log SET step='26���������� ����������', timecron=".time()." WHERE id=$idcronlog");

$game=myquery("SELECT COUNT( * ) AS kol, SUM( game_stat.GP ) AS summa, game_stat.user_id AS user_id
FROM game_stat
WHERE game_stat.stat_id = '11'
AND game_stat.shop_id != '20'
GROUP BY game_stat.user_id ORDER BY summa DESC
LIMIT 5 ");
while($elf=mysql_fetch_array($game))
{
	myquery("INSERT INTO game_stat_view (stat_id,substat_id,user_id,kol,summa) VALUES (11,4,'".$elf['user_id']."','".$elf['kol']."','".$elf['summa']."')");
}

myquery("UPDATE game_cron_log SET step='27���������� ����������', timecron=".time()." WHERE id=$idcronlog");

$game=myquery("SELECT COUNT( * ) AS kol, SUM( game_stat.GP ) AS summa, game_stat.user_id AS user_id
FROM game_stat
WHERE game_stat.stat_id = '12'
AND game_stat.shop_id != '20'
GROUP BY game_stat.user_id ORDER BY kol DESC
LIMIT 5 ");
while($elf=mysql_fetch_array($game))
{
	myquery("INSERT INTO game_stat_view (stat_id,substat_id,user_id,kol,summa) VALUES (12,3,'".$elf['user_id']."','".$elf['kol']."','".$elf['summa']."')");
}

myquery("UPDATE game_cron_log SET step='28���������� ����������', timecron=".time()." WHERE id=$idcronlog");

$game=myquery("SELECT COUNT( * ) AS kol, SUM( game_stat.GP ) AS summa, game_stat.user_id AS user_id
FROM game_stat
WHERE game_stat.stat_id = '12'
AND game_stat.shop_id != '20'
GROUP BY game_stat.user_id ORDER BY summa DESC
LIMIT 5 ");
while($elf=mysql_fetch_array($game))
{
	myquery("INSERT INTO game_stat_view (stat_id,substat_id,user_id,kol,summa) VALUES (12,4,'".$elf['user_id']."','".$elf['kol']."','".$elf['summa']."')");
}

myquery("UPDATE game_cron_log SET step='29���������� ����������', timecron=".time()." WHERE id=$idcronlog");

$game=myquery("SELECT COUNT( * ) AS kol, SUM( game_stat.GP ) AS summa, game_stat.user_id AS user_id
FROM game_stat
WHERE game_stat.stat_id = '13'
AND game_stat.shop_id != '20'
GROUP BY game_stat.user_id ORDER BY kol DESC
LIMIT 5 ");
while($elf=mysql_fetch_array($game))
{
	myquery("INSERT INTO game_stat_view (stat_id,substat_id,user_id,kol,summa) VALUES (13,3,'".$elf['user_id']."','".$elf['kol']."','".$elf['summa']."')");
}

myquery("UPDATE game_cron_log SET step='30���������� ����������', timecron=".time()." WHERE id=$idcronlog");

$game=myquery("SELECT COUNT( * ) AS kol, SUM( game_stat.GP ) AS summa, game_stat.user_id AS user_id
FROM game_stat
WHERE game_stat.stat_id = '13'
AND game_stat.shop_id != '20'
GROUP BY game_stat.user_id ORDER BY summa DESC
LIMIT 5 ");
while($elf=mysql_fetch_array($game))
{
	myquery("INSERT INTO game_stat_view (stat_id,substat_id,user_id,kol,summa) VALUES (13,4,'".$elf['user_id']."','".$elf['kol']."','".$elf['summa']."')");
}



myquery("UPDATE game_cron_log SET step='31���������� ����������', timecron=".time()." WHERE id=$idcronlog");




$game=myquery("SELECT COUNT( * ) AS kol, game_stat.user_id AS user_id
FROM game_stat
WHERE game_stat.stat_id = '16'
GROUP BY game_stat.user_id ORDER BY kol DESC
LIMIT 5 ");
while($elf=mysql_fetch_array($game))
{
	myquery("INSERT INTO game_stat_view (stat_id,substat_id,user_id,kol) VALUES (16,1,'".$elf['user_id']."','".$elf['kol']."')");
}

myquery("UPDATE game_cron_log SET step='32���������� ����������', timecron=".time()." WHERE id=$idcronlog");

$game=myquery("SELECT COUNT( * ) AS kol, game_stat.enemy_id AS user_id
FROM game_stat
WHERE game_stat.stat_id = '16'
GROUP BY game_stat.enemy_id ORDER BY kol DESC
LIMIT 5 ");
while($elf=mysql_fetch_array($game))
{
	myquery("INSERT INTO game_stat_view (stat_id,substat_id,user_id,kol) VALUES (16,2,'".$elf['user_id']."','".$elf['kol']."')");
}


myquery("UPDATE game_cron_log SET step='33���������� ����������', timecron=".time()." WHERE id=$idcronlog");


$game=myquery("SELECT COUNT( * ) AS kol, game_stat.user_id AS user_id
FROM game_stat
WHERE game_stat.stat_id = '7'
GROUP BY game_stat.user_id ORDER BY kol DESC
LIMIT 5 ");
while($elf=mysql_fetch_array($game))
{
	myquery("INSERT INTO game_stat_view (stat_id,substat_id,user_id,kol) VALUES (7,1,'".$elf['user_id']."','".$elf['kol']."')");
}

myquery("UPDATE game_cron_log SET step='34���������� ����������', timecron=".time()." WHERE id=$idcronlog");

$game=myquery("SELECT COUNT( * ) AS kol, game_stat.user_id AS user_id
FROM game_stat
WHERE game_stat.stat_id = '6'
GROUP BY game_stat.user_id ORDER BY kol DESC
LIMIT 5 ");
while($elf=mysql_fetch_array($game))
{
	myquery("INSERT INTO game_stat_view (stat_id,substat_id,user_id,kol) VALUES (6,1,'".$elf['user_id']."','".$elf['kol']."')");
}

myquery("UPDATE game_cron_log SET step='35���������� ����������', timecron=".time()." WHERE id=$idcronlog");


$game=myquery("SELECT COUNT( * ) AS kol, game_stat.clan_id AS clan_id
FROM game_stat
WHERE game_stat.stat_id = '6'
AND game_stat.clan_id!='0'
GROUP BY game_stat.clan_id ORDER BY kol DESC
LIMIT 5 ");
while($elf=mysql_fetch_array($game))
{
	myquery("INSERT INTO game_stat_view (stat_id,substat_id,clan_id,kol) VALUES (6,2,'".$elf['clan_id']."','".$elf['kol']."')");
}

myquery("UPDATE game_cron_log SET step='36���������� ����������', timecron=".time()." WHERE id=$idcronlog");

$game=myquery("SELECT COUNT( * ) AS kol, game_stat.clan_id AS clan_id
FROM game_stat
WHERE game_stat.stat_id = '7'
AND game_stat.clan_id!='0'
GROUP BY game_stat.clan_id ORDER BY kol DESC
LIMIT 5 ");
while($elf=mysql_fetch_array($game))
{
	myquery("INSERT INTO game_stat_view (stat_id,substat_id,clan_id,kol) VALUES (7,2,'".$elf['clan_id']."','".$elf['kol']."')");
}



myquery("UPDATE game_cron_log SET step='37���������� ����������', timecron=".time()." WHERE id=$idcronlog");



$game=myquery("SELECT COUNT( * ) AS kol, game_stat.user_id AS user_id, SUM(game_stat.exp) AS exp
FROM game_stat
WHERE game_stat.stat_id = '7'
GROUP BY game_stat.user_id ORDER BY exp DESC
LIMIT 5 ");
while($elf=mysql_fetch_array($game))
{
	myquery("INSERT INTO game_stat_view (stat_id,substat_id,user_id,kol,summa) VALUES (7,3,'".$elf['user_id']."','".$elf['kol']."','".$elf['exp']."')");
}

myquery("UPDATE game_cron_log SET step='38���������� ����������', timecron=".time()." WHERE id=$idcronlog");

$game=myquery("SELECT COUNT( * ) AS kol, game_stat.user_id AS user_id, SUM(game_stat.gp) AS gp
FROM game_stat
WHERE game_stat.stat_id = '7'
GROUP BY game_stat.user_id ORDER BY gp DESC
LIMIT 5 ");
while($elf=mysql_fetch_array($game))
{
	myquery("INSERT INTO game_stat_view (stat_id,substat_id,user_id,kol,summa) VALUES (7,4,'".$elf['user_id']."','".$elf['kol']."','".$elf['gp']."')");
}

myquery("UPDATE game_cron_log SET step='39���������� ����������', timecron=".time()." WHERE id=$idcronlog");


$game=myquery("SELECT COUNT( * ) AS kol, game_stat.user_id AS user_id
FROM game_stat
WHERE game_stat.stat_id = '7'
AND game_stat.level_user>game_stat.level_enemy
GROUP BY game_stat.user_id ORDER BY kol DESC
LIMIT 5 ");
while($elf=mysql_fetch_array($game))
{
	myquery("INSERT INTO game_stat_view (stat_id,substat_id,user_id,kol) VALUES (7,5,'".$elf['user_id']."','".$elf['kol']."')");
}

myquery("UPDATE game_cron_log SET step='40���������� ����������', timecron=".time()." WHERE id=$idcronlog");


$game=myquery("SELECT COUNT( * ) AS kol, game_stat.user_id AS user_id
FROM game_stat
WHERE game_stat.stat_id = '7'
AND game_stat.level_user<game_stat.level_enemy
GROUP BY game_stat.user_id ORDER BY kol DESC
LIMIT 5 ");
while($elf=mysql_fetch_array($game))
{
	myquery("INSERT INTO game_stat_view (stat_id,substat_id,user_id,kol) VALUES (7,6,'".$elf['user_id']."','".$elf['kol']."')");
}


myquery("UPDATE game_cron_log SET step='41���������� ����������', timecron=".time()." WHERE id=$idcronlog");


$game=myquery("SELECT COUNT( * ) AS kol, game_stat.user_id AS user_id
FROM game_stat
WHERE game_stat.stat_id = '6'
AND game_stat.level_user>game_stat.level_enemy
GROUP BY game_stat.user_id ORDER BY kol DESC
LIMIT 5 ");
while($elf=mysql_fetch_array($game))
{
	myquery("INSERT INTO game_stat_view (stat_id,substat_id,user_id,kol) VALUES (6,3,'".$elf['user_id']."','".$elf['kol']."')");
}

myquery("UPDATE game_cron_log SET step='42���������� ����������', timecron=".time()." WHERE id=$idcronlog");


$game=myquery("SELECT COUNT( * ) AS kol, game_stat.user_id AS user_id
FROM game_stat
WHERE game_stat.stat_id = '6'
AND game_stat.level_user<game_stat.level_enemy
GROUP BY game_stat.user_id ORDER BY kol DESC
LIMIT 5 ");
while($elf=mysql_fetch_array($game))
{
	myquery("INSERT INTO game_stat_view (stat_id,substat_id,user_id,kol) VALUES (6,4,'".$elf['user_id']."','".$elf['kol']."')");
}


myquery("UPDATE game_cron_log SET step='431���������� ����������', timecron=".time()." WHERE id=$idcronlog");



$game=myquery("SELECT COUNT( * ) AS kol, game_stat.user_id AS user_id
FROM game_stat
WHERE game_stat.stat_id = '3'
GROUP BY game_stat.user_id ORDER BY kol DESC
LIMIT 5 ");
while($elf=mysql_fetch_array($game))
{
	myquery("INSERT INTO game_stat_view (stat_id,user_id,kol) VALUES (3,'".$elf['user_id']."','".$elf['kol']."')");
}

DbConnect();
myquery("UPDATE game_cron_log SET step='final', timecron=".time()." WHERE id=$idcronlog");

?>