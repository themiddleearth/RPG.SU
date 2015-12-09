<?Php
$dirclass = "../class";
require_once('../inc/config.inc.php');
require_once('../inc/lib.inc.php');
require_once('../inc/db.inc.php');
require_once('../inc/lib_session.inc.php');
?>
<html>
<head>
<title>���������� :: ����� �������� :: RPG online ����</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<meta name="description" content="��������������������� RPG OnLine ���� �� �������� ��.�.�.�������� '��������� �����' - ����� ������� ���� �� ������������� ������������">
<meta name="Keywords" content="���������� ����� �������� ��������� ����� ������� Lord of the Rings rpg ������� ������� ������ ���� online game �������� ��� ������� ����� ����� �� �������">
<style type="text/css">@import url("../style/global.css");</style>
</head>
<?
if($char['map_name']==18 AND $char['map_xpos']==26 AND $char['map_ypos']==21)
{
	OpenTable('title');
	echo'<br><center><font size=4 face=verdana color=#fce66b>����� ���������� � ���������� ������</font><br><br>';
	echo '<hr align=center size=2 width=80%>';
	
	//������ � ���� �������_����������=>����������� ����� �������
	$clevel_for_level=array(1=>6,2=>10,3=>15);
	//������ � ���� ������_����������=>��_�����_������_����������
	$map_id_level=array(1=>691,2=>692,3=>804);
	//�� ������� "������� � ����������"
	$propusk_id=propusk_item_id;
	
	$timeout = 10*60*60;
	
	//�������� �� ������������� ��������
	$id_propusk = 0;
	if(isset($with_propusk))
	{
		$propusk=myquery("SELECT id FROM game_items WHERE user_id=".$user_id." AND item_id=".$propusk_id." AND priznak=0 AND used=0 AND ref_id=0");
		if(mysql_num_rows($propusk)>0)
		{
			list($id_propusk)=mysql_fetch_array($propusk);
			$with_propusk = 1;
		}
		else 
			$with_propusk=0;
	}
	else 
		$with_propusk=0;

	//�������� ������ �����
	$dungeon_user=myquery("SELECT last_visit FROM dungeon_users_data WHERE user_id=".$user_id."");
	if(mysql_num_rows($dungeon_user)<=0)
	{
		$ins=myquery("INSERT INTO dungeon_users_data (user_id) VALUES (".$user_id.")");
		$last_visit=0;
	}else
		list($last_visit) = mysql_fetch_array($dungeon_user);

	if($with_propusk>0)
	{
		$Item = new Item($id_propusk);
		$Item->admindelete();

		$set_access=myquery("UPDATE dungeon_users_data SET last_visit=0 WHERE user_id=".$user_id."");
		$last_visit = 0;
	}
	
	
	if (($last_visit+$timeout)<=time())
	{
		echo '<br><a href="?level=1" target="game">����� �� ������ ������� ����������</a><font color=orange> (�������� � '.$clevel_for_level[1].' ������)</font><br>';
		echo '<br><a href="?level=2" target="game">����� �� ������ ������� ����������</a> <font color=orange> (�������� � '.$clevel_for_level[2].' ������)</font><br>';
		echo '<br><a href="?level=3" target="game">����� �� ������ ������� ����������</a> <font color=orange> (�������� � '.$clevel_for_level[3].' ������)</font><br>';
		if (isset($_GET['level']))
		{
			$level = (int)$_GET['level'];
			
			if($char['clevel']<$clevel_for_level[$level])
			{
				echo '<table cellpadding="0" cellspacing="0" width="80%" border="0"><tr><td><center>';
				echo '<br><font color=#ff4433><hr align=center size=2 width=50%><br>
				�����-��������� �� ���������� ���� � ��� ����������, ��� ��� �� '.echo_sex('������','������').' ���� �� ����� '.($clevel_for_level[$level]).' ������ ��� ������� ����.</font><br><br>';
				echo '</center></tr></td></table><hr align=center size=2 width=50%>';
			}			
			else
			switch ($level)		
			{
				case 1: case 2: case 3:
					$move=myquery("UPDATE game_users_map SET map_name='".($map_id_level[$level])."', map_xpos=0, map_ypos=0 WHERE user_id=".$user_id."");
					echo '<br><font size=4 color=green><hr align=center size=2 width=50%><br>�� ����������� � ����������!</font><br><br><hr align=center size=2 width=50%>';
					echo '<br><a href="../act.php" target="game">��������� �������</a><br><br>';
					echo '<meta http-equiv="refresh" content="2;url=../act.php">';
					OpenTable('close');
					include("../inc/template_footer.inc.php");
					exit();
				break;
				default:
				echo '<table cellpadding="0" cellspacing="0" width="80%" border="0"><tr><td><center>';
				echo '<br><font color=#ff4433><hr align=center size=2 width=50%><br>
				�����-��������� � �������� ���������� ��������, ��� �� '.echo_sex('�����','������').' ������� � �����, ������� ������ �� �������������� ������ ����������.</b></font><br><br>';
				echo '</center></tr></td></table><hr align=center size=2 width=50%>';
				break;
			}
		}
		echo '<br><a href="../act.php" target="game">���������</a><br><br>';
	}
	else
	{
		$mins=ceil(($last_visit+$timeout-time())/60);
		$mins.=' '.pluralForm($mins,'������','������','�����');

		echo '<br><font color=#ff4433>
		�� �� ������� ����� � ���������� ��� '.$mins.'!</font><br>';
		$propusk=myquery("SELECT COUNT(*) FROM game_items WHERE user_id=".$user_id." AND item_id=".$propusk_id." AND ref_id=0 AND priznak=0");
		if(mysql_num_rows($propusk)>0)
		{
			$propusk=mysql_result($propusk,0,0);
		}else $propusk=0;
		if($propusk>0)
		echo '<br><a href="?with_propusk=1" target="game">������������ �������</a><br>';
		echo '<br><a href="../act.php" target="game">���������</a><br><br>';
	}
	
	OpenTable('close');
	include("../inc/template_footer.inc.php");
}else 
	echo  '<meta http-equiv="refresh" content="0;url=../act.php">';
?>