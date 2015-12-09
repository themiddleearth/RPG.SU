<?php 

// Необходимо заполнить поля login/password/db

define('PHPRPG_DB_HOST', 'localhost');
define('PHPRPG_DB_NAME', '');
define('PHPRPG_DB_USER', '');
define('PHPRPG_DB_PASS', '');

function DbConnect()
{
	$mysqli = mysql_connect(PHPRPG_DB_HOST, PHPRPG_DB_USER, PHPRPG_DB_PASS) or die(mysql_error());
	mysql_select_db(PHPRPG_DB_NAME) or die(mysql_error());
}

DbConnect();

?>