<?php
//Крон для запуска каждые 2 часа

include("config.inc.php");

move_teleport(120);

myquery("DELETE FROM game_cron_log WHERE cron='every_2hour' AND step='final'");
myquery("INSERT INTO game_cron_log (cron,step,timecron) VALUES ('every_2hour','Начало',".time().")");
$idcronlog = mysql_insert_id();
 
myquery("UPDATE game_cron_log SET step='1. Очистка комплектов', timecron=".time()." WHERE id=$idcronlog");
myquery("DELETE FROM game_users_complects, game_users_complects_prepare USING game_users_complects, game_users_complects_prepare
		 WHERE game_users_complects.id=game_users_complects_prepare.complect_id 
		 AND game_users_complects.status=0 AND game_users_complects.finish_time<'".time()."'");

myquery("UPDATE game_cron_log SET step='final', timecron=".time()." WHERE id=$idcronlog");
?>