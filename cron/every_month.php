<?php
//Крон для запуска 1 числа каждого месяца

include("config.inc.php");

myquery("DELETE FROM game_cron_log WHERE cron='every_month' AND step='final'");
myquery("INSERT INTO game_cron_log (cron,step,timecron) VALUES ('every_month','Начало',".time().")");
$idcronlog = mysql_insert_id();
 
myquery("UPDATE game_cron_log SET step='', timecron=".time()." WHERE id=$idcronlog");
myquery("UPDATE game_cron_log SET step='final', timecron=".time()." WHERE id=$idcronlog");
?>