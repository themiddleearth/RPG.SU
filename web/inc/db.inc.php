<?php 

// Необходимо заполнить поля login/password/db

define('PHPRPG_DB_HOST', 'localhost');
define('PHPRPG_DB_NAME', 'ageofwar_game');
define('PHPRPG_DB_USER', 'root');
define('PHPRPG_DB_PASS', '');

// Необходимо заполнить поля login/password

define('PHPRPG_STAT_DB_HOST', 'localhost');
define('PHPRPG_STAT_DB_NAME', 'rpgsu_stats');
define('PHPRPG_STAT_DB_USER', 'root');
define('PHPRPG_STAT_DB_PASS', '');

define('debug_ip','127.0.0.1');

DbConnect();

function DbConnect()
{
	$mysqli = mysql_connect(PHPRPG_DB_HOST, PHPRPG_DB_USER, PHPRPG_DB_PASS) or die(mysql_error());
	mysql_select_db(PHPRPG_DB_NAME) or die(mysql_error());
}

DbConnect();

?>