<?php
//Крон для запуска каждые 10 минут

include("config.inc.php");
require_once("/home/vhosts/rpg.su/web/class/class_item.php");

move_teleport(10);

myquery("DELETE FROM game_cron_log WHERE cron='every_10minute' AND step='final'");
myquery("INSERT INTO game_cron_log (cron,step,timecron) VALUES ('every_10minute','Начало',".time().")");
$idcronlog = mysql_insert_id();

myquery("UPDATE game_cron_log SET step='1. Проверка игроков по обелискам', timecron=".time()." WHERE id=$idcronlog");
//проверка по обелискам
//type = 0 - обелиски взятые на карте
//type = 1 - зелье глубин, среднее зелье глубин
//type = 2 - сваренные зелья повышающие харки
//type = 3 - зелье бодрости
//type = 4 - зелье зоркости
//type = 5 - зелье невидимости
$sel = myquery("SELECT * FROM game_obelisk_users WHERE user_id>0 AND time_end<".time()."");
while ($ob = mysql_fetch_array($sel))
{
	if ($ob['type']==0 OR $ob['type']==2)
	{
		if ($ob['value']==0)
		{
			myquery("UPDATE game_users SET ".$ob['harka']."=".$ob['harka']."_MAX WHERE user_id = ".$ob['user_id']."");
			myquery("UPDATE game_users_archive SET ".$ob['harka']."=".$ob['harka']."_MAX WHERE user_id = ".$ob['user_id']."");
		}
		else
		{
			myquery("UPDATE game_users SET ".$ob['harka']."=".$ob['harka']."-".$ob['value']." WHERE user_id = ".$ob['user_id']."");
			myquery("UPDATE game_users_archive SET ".$ob['harka']."=".$ob['harka']."-".$ob['value']." WHERE user_id = ".$ob['user_id']."");
		}
	}
	elseif ($ob['type']==1)
	{
		//перекачка HP_MAX, возвращаем его к HP_MAXX
		myquery("UPDATE game_users SET HP_MAX=HP_MAXX WHERE user_id = ".$ob['user_id']."");
		myquery("UPDATE game_users_archive SET HP_MAX=HP_MAXX WHERE user_id = ".$ob['user_id']."");
	}
	myquery("DELETE FROM game_obelisk_users WHERE id=".$ob['id']."");
}

myquery("UPDATE game_cron_log SET step='2. Очистка чатов', timecron=".time()." WHERE id=$idcronlog");

echo 'Очистка чатов<br>';
$online_range = time() - 600;
$result_delete = myquery("DELETE FROM game_chat WHERE post_time < $online_range");
$result_delete = myquery("DELETE FROM game_battles WHERE post_time < $online_range");

$time1 = time()-30*60;
myquery("DELETE FROM game_log WHERE date<$time1");

myquery("UPDATE game_cron_log SET step='3. Перемещение обелисков', timecron=".time()." WHERE id=$idcronlog");

//перемещение обелисков
$sel = myquery("SELECT * FROM game_obelisk");
while ($obel = mysql_fetch_array($sel))
{
	$map_name = $obel['map_name'];
	list($max_xpos,$max_ypos) = mysql_fetch_array(myquery("SELECT xpos,ypos FROM game_map WHERE name=$map_name ORDER BY xpos DESC, ypos DESC LIMIT 1"));
	$map_xpos = mt_rand(1,$max_xpos-3);
	$map_ypos = mt_rand(1,$max_ypos-3);
	myquery("UPDATE game_obelisk SET map_xpos=$map_xpos, map_ypos=$map_ypos WHERE id=".$obel['id']."");
}


myquery("UPDATE game_cron_log SET step='4. Удаление билетов в порт опоздавших', timecron=".time()." WHERE id=$idcronlog");

myquery("DELETE FROM game_port_bil WHERE buydate<".(time()-120)." AND stat=0");

myquery("UPDATE game_cron_log SET step='5. Оказание помощи новичкам', timecron=".time()." WHERE id=$idcronlog");

$sel = myquery("SELECT user_id,name FROM view_active_users WHERE clevel<5 and clan_id<>1");
while ($cha = mysql_fetch_array($sel))
{
	$r = mt_rand(1,1);
	if ($r==1)
	{
		$fp=fopen("http://www.rpg.su/chat/nub.txt", 'r');
		if ($fp!==false)
		{
			$stroka = mt_rand(1,30);
			$ctroka = 0;
			while (!feof($fp))
			{
				$ctroka++;
				$chat_mess = fgets($fp);
				if ($ctroka==$stroka)
				{
					$chat_mess=trim($chat_mess);
					$chat_mess='<font color=yellow>'.$chat_mess.'</font>';
					$chat_mess=iconv("Windows-1251","UTF-8//IGNORE",$chat_mess);
					$message = $chat_mess;
					$update=myquery("insert into game_log 
					(town,message,date,fromm,too,ptype) 
					values 
					('0','".$message."','".time()."','-1','".iconv("Windows-1251","UTF-8//IGNORE",$cha['user_id'])."',1)");
					echo 'Нафаня оказал помощь новичку: '.$cha['name'].'<br>';
					break;
				}
			}
		}
	}
}

myquery("UPDATE game_cron_log SET step='6. Удаление временных предметов', timecron=".time()." WHERE id=$idcronlog");
$t = time();

$check_items = myquery("SELECT * FROm game_items WHERE dead_time > 0 and dead_time < '".$t."'");
if (mysql_num_rows($check_items) > 0)
{
	$check = myquery("SELECT id, user_id FROM game_items WHERE dead_time > 0 and dead_time < '".$t."' and priznak = 0 and used>0");
	while ($it = mysql_fetch_array($check))
	{
		$Item = new Item($it['id'], $it['user_id']);
		$Item->down();
	}
	myquery("UPDATE game_users gu
			   JOIN (SELECT gi.user_id, sum(gif.weight * GREATEST(1, gi.count_item*git.counts) ) as w
					   FROM game_items gi
					   JOIN game_items_factsheet gif ON gi.item_id = gif.id
					   JOIN game_items_type git ON gif.type=git.id
					  WHERE gi.dead_time > 0 and gi.dead_time < '".$t."' and gi.priznak = 0 and gif.weight>0
				   GROUP BY user_id) v
				 ON gu.user_id = v.user_id
				SET CW=gu.CW-v.w");
	myquery("UPDATE game_users_archive gu
			   JOIN (SELECT gi.user_id, sum(gif.weight * GREATEST(1, gi.count_item*git.counts) ) as w
					   FROM game_items gi
					   JOIN game_items_factsheet gif ON gi.item_id = gif.id
					   JOIN game_items_type git ON gif.type=git.id
					  WHERE gi.dead_time > 0 and gi.dead_time < '".$t."' and gi.priznak = 0 and gif.weight>0
				   GROUP BY user_id) v
				 ON gu.user_id = v.user_id
				SET CW=gu.CW-v.w");			
	myquery("DELETE FROM game_items WHERE dead_time<'".$t."' AND dead_time>0");			
	myquery("DELETE FROM quest_constructor WHERE dead_time<'".$t."' AND dead_time>0");
}

myquery("UPDATE game_cron_log SET step='7. Удаление временных ресурсов', timecron=".time()." WHERE id=$idcronlog");
$t = time();
myquery("UPDATE game_users gu
           JOIN (SELECT cru.user_id, sum(cr.weight * cru.col) as w
                   FROM craft_resource_user cru
                   JOIN craft_resource cr ON cru.res_id = cr.id
                  WHERE cru.dead_time > 0 and cru.dead_time < '".$t."'
               GROUP BY user_id) v
             ON gu.user_id = v.user_id
            SET CW=gu.CW-v.w");
myquery("UPDATE game_users_archive gu
           JOIN (SELECT cru.user_id, sum(cr.weight * cru.col) as w
                   FROM craft_resource_user cru
                   JOIN craft_resource cr ON cru.res_id = cr.id
                  WHERE cru.dead_time > 0 and cru.dead_time < '".$t."'
               GROUP BY user_id) v
             ON gu.user_id = v.user_id
            SET CW=gu.CW-v.w");	
myquery("DELETE FROM craft_resource_user WHERE dead_time<'".$t."' AND dead_time>0");
myquery("DELETE FROM craft_resource_market WHERE dead_time<'".$t."' AND dead_time>0");				

myquery("UPDATE game_cron_log SET step='8. Очищение статистики песен', timecron=".time()." WHERE id=$idcronlog");
myquery("DELETE FROM game_users_songs WHERE song_date<'".time()."'-60*60*2");

myquery("UPDATE game_cron_log SET step='9. Проведение Битвы Хаоса', timecron=".time()." WHERE id=$idcronlog");
// Первый параметр - минимальное число игроков, необходимое для проведения битвы хаоса
// Второй параметр - необходимость проверки по времени
create_chaoscombat (6, 1);	

myquery("UPDATE game_cron_log SET step='final', timecron=".time()." WHERE id=$idcronlog");

?>