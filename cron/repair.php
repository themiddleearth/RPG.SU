<?
define('domain_name', $_SERVER['HTTP_HOST']);
if (domain_name=='localhost')
{
    define('PHPRPG_DB_HOST', 'localhost');
    define('PHPRPG_DB_NAME', 'ageofwar_game');
    define('PHPRPG_DB_USER', 'root');
    define('PHPRPG_DB_PASS', '');
}
else
{
    define('PHPRPG_DB_HOST', 'localhost');
    define('PHPRPG_DB_NAME', 'gamerpgsu');
    define('PHPRPG_DB_USER', 'gamerpgsu');
    define('PHPRPG_DB_PASS', 'wYpxNsczNPVtr4Pd');
}
if (domain_name=='localhost')
{
    myquery ("set character_set_client='cp1251'");
    myquery ("set character_set_results='cp1251'");
    myquery ("set collation_connection='cp1251_general_ci'");
}

$db = mysql_connect(PHPRPG_DB_HOST, PHPRPG_DB_USER, PHPRPG_DB_PASS) or die(mysql_error());
mysql_select_db(PHPRPG_DB_NAME) or die(mysql_error());

$s = myquery("REPAIR TABLE game_users");
?>