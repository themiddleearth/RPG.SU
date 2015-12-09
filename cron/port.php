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

define('PHPRPG_SESSION_EXPIRY', '10000');
define('PHPRPG_EPOCH', '994737600');

ereg("([^\\/]*)$", $_SERVER['PHP_SELF'], $php_self);
define('PHP_SELF', $php_self[1]);

$db = mysql_connect(PHPRPG_DB_HOST, PHPRPG_DB_USER, PHPRPG_DB_PASS) or die(mysql_error());
mysql_select_db(PHPRPG_DB_NAME) or die(mysql_error());

$sel = myquery("SELECT * FROM game_port_bil");
while ($bil = mysql_fetch_array($sel))
{
        $id = $bil['bil'];
        $bilet_sel = myquery("SELECT * FROM game_port WHERE id=$id LIMIT 1");
        $bilet = mysql_fetch_array($bilet_sel);
        if (date("H:i",time())>$bilet['dlit'])
        {
                $id = $bil['user_id'];
                $sel_map = myquery("SELECT map_name FROM game_users WHERE user_id=$id");
                if (!mysql_num_rows($sel_map)) $sel_map = myquery("SELECT map_name FROM game_users_archive WHERE user_id=$id");
                list($map) = mysql_fetch_array($sel_map);
				$map_name = @mysql_result(@myquery("SELECT name FROM game_maps WHERE id='$map'"),0,0);
                if ($map_name!='Море')
                {
					$del = myquery("DELETE FROM game_port_bil WHERE user_id='".$id."'");
                }
        }
}
?>