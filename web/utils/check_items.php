<?
$dirclass = "../class";
require('../inc/config.inc.php');
include('../inc/lib.inc.php');
include('../inc/template.inc.php');

$db_stat = mysql_connect('localhost', 'rpgsu_stats', 'EuTh4fsFjdvMMuSY') or die(mysql_error());
$db_game = mysql_connect('localhost', 'gamerpgsu', '7e9dda3b9f31e74dd9d17ee305ee9c51') or die(mysql_error());
mysql_select_db('rpgsu_stats',$db_stat) or die(mysql_error());  
mysql_select_db('gamerpgsu',$db_game) or die(mysql_error());  

$sel = myquery("SELECT * FROM game_items_old WHERE town=3 AND type='Оружие'",$db_stat);
while ($it = mysql_fetch_array($sel))
{
    list($item_id) = mysql_fetch_array(myquery("SELECT id FROM game_items_factsheet WHERE name='".$it['ident']."'",$db_game));
    echo '<br>Предмет - '.$it['ident'].', продавец - '.$it['name'].', item_id='.$item_id.'';
}

echo 'The End';
?>