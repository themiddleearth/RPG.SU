<?
define('domain_name', $_SERVER['HTTP_HOST']);
if (domain_name=='localhost')
{
    define('PHPRPG_DB_HOST', 'localhost');
    define('PHPRPG_DB_NAME', '');
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

if ($rand_map<=5)
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
elseif ($rand_map<=18)
{
    $map_name = @mysql_result(@myquery("SELECT id FROM game_maps WHERE name='������� ��������'"),0,0);
    $map_xpos = mt_rand(0,10);
    $map_ypos = mt_rand(0,5);
}


$r = mt_rand(1,11);

if ($r==1)
{
$up = myquery("INSERT INTO game_items (ident,user_id,weight,img,map_name,map_xpos,map_ypos,type) VALUES ('����� ����','0','0','other/water','$map_name','$map_xpos','$map_ypos','������')");
}
elseif ($r==2)
{
$up = myquery("INSERT INTO game_items (ident,user_id,weight,img,map_name,map_xpos,map_ypos,type) VALUES ('����� ����','0','0','other/meat','$map_name','$map_xpos','$map_ypos','������')");
}
elseif ($r==3)
{
$up = myquery("INSERT INTO game_items (ident,user_id,weight,img,map_name,map_xpos,map_ypos,type) VALUES ('���������� �������','0','0','other/mana','$map_name','$map_xpos','$map_ypos','������')");
}
elseif ($r<=5)
{
$up = myquery("INSERT INTO game_items (ident,user_id,weight,img,map_name,map_xpos,map_ypos,type) VALUES ('������� � ������� ������','0','0','other/bottle2','$map_name','$map_xpos','$map_ypos','������')");
}
elseif ($r<=7)
{
$up = myquery("INSERT INTO game_items (ident,user_id,weight,img,map_name,map_xpos,map_ypos,type) VALUES ('������� � �������� ������','0','0','other/bottle3','$map_name','$map_xpos','$map_ypos','������')");
}
elseif ($r<=9)
{
$up = myquery("INSERT INTO game_items (ident,user_id,weight,img,map_name,map_xpos,map_ypos,type) VALUES ('����������� ������','0','0','other/bottle4','$map_name','$map_xpos','$map_ypos','������')");
}
elseif ($r<=11)
{
$up = myquery("INSERT INTO game_items (ident,user_id,weight,img,map_name,map_xpos,map_ypos,type) VALUES ('������ � ���������','0','0','other/bottle5','$map_name','$map_xpos','$map_ypos','������')");
}
?>