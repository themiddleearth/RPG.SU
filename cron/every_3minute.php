<?php
//���� ��� ������� ������ 3 ������

include("config.inc.php");

move_teleport(3);
$j=0;

myquery("DELETE FROM game_cron_log WHERE cron='every_3minute' AND step='final'");
myquery("INSERT INTO game_cron_log (cron,step,timecron) VALUES ('every_3minute','������',".time().")");
$idcronlog = mysql_insert_id();

$j++;
myquery("UPDATE game_cron_log SET step='".$j.". �������� ���������� ������� � ��������� �����������', timecron=".time()." WHERE id=$idcronlog");
$sel_post = myquery("SELECT * FROM game_items WHERE post_to>0 AND post_var=1 AND priznak=3");
while ($post = mysql_fetch_array($sel_post))
{
	list($ident,$weight) = mysql_fetch_array(myquery("SELECT name,weight FROM game_items_factsheet WHERE id=".$post['item_id'].""));
	$post_sel = myquery("SELECT * FROM game_users WHERE user_id=".$post['post_to']."");
	if (!mysql_num_rows($post_sel)) $post_sel = myquery("SELECT * FROM game_users_archive WHERE user_id=".$post['post_to']."");
	$post_user = mysql_fetch_array($post_sel);
	$char_sel = myquery("SELECT * FROM game_users WHERE user_id=".$post['user_id']."");
	if (!mysql_num_rows($char_sel)) $char_sel = myquery("SELECT * FROM game_users_archive WHERE user_id=".$post['user_id']."");
	$char = mysql_fetch_array($char_sel);
	$prov=mysql_result(myquery("select count(*) from game_wm where user_id=".$post['post_to']." AND type=1"),0,0);
	if (($post_user['CW']+$weight)<=$post_user['CC'] OR $prov>0)
	{
		
		myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES (".$post_user['user_id'].", '0', '���� ������ ���������� �������', '� ���� ��������� ������� ���������� ������� ���������� ������� <".$ident.">. ����������� - ".$char['name']."', '0',".time().")") or die(mysql_error());
		$str_query = "INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES (".$char['user_id'].", '0', '����������� � �������� �������', '���� ������� <".$ident."> ������� ���������� �������� - ".$post_user['name']."', '0',".time().")";
		//echo $str_query;
		myquery($str_query) or die(mysql_error());
		myquery("UPDATE game_items SET user_id=".$post_user['user_id'].",priznak=0,ref_id=0,post_to=0,post_var=0,sell_time=0,town=0,map_name=0,map_xpos=0,map_ypos=0,used=0 WHERE id=".$post['id']."");
		myquery("update game_users set CW=CW + $weight where user_id=".$post_user['user_id'].""); 
		myquery("update game_users_archive set CW=CW + $weight where user_id=".$post_user['user_id'].""); 
	}
	else
	{
		myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES (".$char['user_id'].", '0', '������ �������� �������', '���� ������� <".$ident.">, ������������ ������ ".$post_user['name'].", �� ����� ���� ����������, �.�. � ����� ������ ���������� ���������. ������� ���������� � ���� ���������.', '0',".time().")") or die(mysql_error());
		myquery("UPDATE game_items SET user_id=".$char['user_id'].",priznak=0,ref_id=0,post_to=0,post_var=0,sell_time=0,town=0,map_name=0,map_xpos=0,map_ypos=0,used=0 WHERE id=".$post['id']."");
		myquery("update game_users set CW=CW + $weight where user_id=".$char['user_id'].""); 
		myquery("update game_users_archive set CW=CW + $weight where user_id=".$char['user_id'].""); 
	}
}

$j++;
myquery("UPDATE game_cron_log SET step='".$j.". ���������� ���������� ������� �� ������', timecron=".time()." WHERE id=$idcronlog");
myquery("UPDATE craft_build_lumberjack SET user_id=0 WHERE user_id NOT IN (SELECT user_id FROM view_active_users)");
myquery("UPDATE craft_build_stonemason SET user_id=0 WHERE user_id NOT IN (SELECT user_id FROM view_active_users)");
myquery("UPDATE craft_build_lumberjack SET reserve_user_id=0,reserve_time=0 WHERE user_id NOT IN (SELECT user_id FROM view_active_users)");
myquery("UPDATE craft_build_stonemason SET reserve_user_id=0,reserve_time=0 WHERE user_id NOT IN (SELECT user_id FROM view_active_users)");
myquery("UPDATE craft_build_mining SET user_id=0 WHERE user_id NOT IN (SELECT user_id FROM view_active_users)");

$j++;
$r = mt_rand (1, 100);
if ($r<=4)
{
	myquery("UPDATE game_cron_log SET step='".$j.". �������������', timecron=".time()." WHERE id=$idcronlog");
	start_combat(4, 3);
}

$j++;
myquery("UPDATE game_cron_log SET step='".$j.". ������� ������ ����', timecron=".time()." WHERE id=$idcronlog");

require_once("/home/vhosts/rpg.su/web/inc/define.inc.php");
//require_once("/home/vhosts/rpg.su/web/inc/lib.inc.php");
require_once("/home/vhosts/rpg.su/web/class/class_timer.php");
require_once("/home/vhosts/rpg.su/web/class/class_item.php");
require_once("/home/vhosts/rpg.su/web/combat/class_combat.php");
require_once("/home/vhosts/rpg.su/web/inc/combat/combat.inc.php");

$close_combat=60*60*24*2;
$sel = myquery("SELECT combat_id FROM combat WHERE time_last_hod<".(time()-$close_combat)."");
while (list($combat_id) = mysql_fetch_array($sel))
{
	check_boy($combat_id);
}

myquery("UPDATE game_cron_log SET step='final', timecron=".time()." WHERE id=$idcronlog");
?>