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

$rand_map = mt_rand(1,20);

if ($rand_map<=6)
{
    $map_name = @mysql_result(@myquery("SELECT id FROM game_maps WHERE name='����������'"),0,0);
    $map_xpos = mt_rand(0,53);
    $map_ypos = mt_rand(0,49);
}
elseif ($rand_map<=15)
{
    $map_name = @mysql_result(@myquery("SELECT id FROM game_maps WHERE name='���������'"),0,0);
    $map_xpos = mt_rand(0,45);
    $map_ypos = mt_rand(0,39);
}
else
{
    $map_name = @mysql_result(@myquery("SELECT id FROM game_maps WHERE name='������� ��������'"),0,0);
    $map_xpos = mt_rand(0,10);
    $map_ypos = mt_rand(0,5);
}


$money = mt_rand(150,200);

$up = myquery("INSERT INTO game_items (ident,user_id,weight,img,item_cost,map_name,map_xpos,map_ypos,type) VALUES ('������ � �����������','0','60','other/sunduk','$money','$map_name','$map_xpos','$map_ypos','�����')");
?>