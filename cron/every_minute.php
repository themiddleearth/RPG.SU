<?php
//Крон для запуска каждую минуту
include("config.inc.php");

//ID карты Туманные Горы
define('id_map_tuman',0);

$maze_id = "(691,692,804,".id_map_tuman.")";

echo 'Обновление энергии<br>';
$event_cycles = 1;
$result_stamina_up = myquery("UPDATE game_users,game_users_map,game_users_func,game_users_active
SET game_users.STM = LEAST (game_users.STM+CEILING(7*game_users.DEX/3)*$event_cycles, game_users.STM_MAX)
WHERE game_users.STM < game_users.STM_MAX
AND game_users.user_id=game_users_func.user_id
AND game_users_func.func_id!='1'
AND game_users.user_id=game_users_map.user_id
AND game_users_map.map_name NOT IN ".$maze_id."
AND game_users_map.map_name<838
AND game_users_active.user_id=game_users.user_id
AND game_users_active.last_active>=(UNIX_TIMESTAMP()-300)
");

echo 'Обновление праны<br>';
$event_cycles = 1;
$result_stamina_up = myquery("UPDATE game_users,game_users_map,game_users_func,game_users_active
SET game_users.PR = LEAST (game_users.PR+CEILING(7*game_users.DEX/3)*$event_cycles, game_users.PR_MAX)
WHERE game_users.PR < game_users.PR_MAX
AND game_users.user_id=game_users_func.user_id
AND game_users_func.func_id!='1'
AND game_users.user_id=game_users_map.user_id
AND game_users_map.map_name NOT IN ".$maze_id."
AND game_users_map.map_name<838
AND game_users_active.user_id=game_users.user_id
AND game_users_active.last_active>=(UNIX_TIMESTAMP()-300)
");

echo 'Обновление жизни<br>';
$event_cycles = 1;
$result_health_up = myquery("UPDATE game_users,game_users_map,game_users_active
SET game_users.HP = LEAST(game_users.HP+ROUND(game_users.HP_MAX/10)*$event_cycles, game_users.HP_MAX)
WHERE game_users.DEX>=0
AND game_users.HP < game_users.HP_MAX
AND game_users.user_id=game_users_map.user_id
AND game_users_map.map_name NOT IN ".$maze_id."
AND game_users_active.user_id=game_users.user_id
AND game_users_map.map_name<838
AND game_users_active.last_active>=(UNIX_TIMESTAMP()-300)
");

echo 'Обновление маны<br>';
$event_cycles = 0.5;
$result_mana_up = myquery("UPDATE game_users,game_users_map,game_users_active
SET game_users.MP = LEAST(game_users.MP+ROUND(game_users.MP_MAX/20)*$event_cycles, game_users.MP_MAX)
WHERE game_users.NTL>=0
AND game_users.MP < game_users.MP_MAX
AND game_users.user_id=game_users_map.user_id
AND game_users_map.map_name NOT IN ".$maze_id."
AND game_users_active.user_id=game_users.user_id
AND game_users_active.last_active>=(UNIX_TIMESTAMP()-300)
AND game_users_map.map_name<838
");
	
move_teleport(1);
?>