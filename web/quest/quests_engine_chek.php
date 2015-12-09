<?Php
$dirclass = "../class";
require_once('../inc/config.inc.php');
require_once('../inc/lib.inc.php');
require_once('../inc/db.inc.php');
if (!defined("MODULE_ID"))
{
	define("MODULE_ID", '13');
}
require_once('../inc/lib_session.inc.php');
require_once('quest_engine_types/inc/quest_define.inc.php');
include("../lib/menu.php");

if (function_exists("start_debug")) start_debug();

// ����������
echo '<title>���������� :: ����� �������� :: ������� on-line ����</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<style type="text/css">@import url("../style/global.css");</style>';

//action_type �� ��������� �����
if(isset($action_type))
	$action_type=0;
//������ ����������
if(isset($mode))
{
	$action_type=0;
	switch ($mode)
	{
		case 601: //ForceFunc($user_id,10);
		set_delay_reason_id($user_id,52);
		include ('quest_engine_types/quest_engine_sudoku.php');
	}
}
else
//���� ��� ���� �����
{
	// ��������, � ��� �� ����
	if (isset($_GET['npc_id']) AND gettype($_GET['npc_id']) == "integer")
	{
		$owner = myquery("SELECT quest_engine_owners.id FROM quest_engine_owners,game_npc WHERE game_npc.id=quest_owners.npc_id AND game_npc.id=".$_GET['npc_id']." AND game_npc.map_name=".$char['map_name']." and game_npc.xpos=".$char['xpos']." and game_npc.ypos=".$char['ypos']."");
	}
	else
	{
		setFunc($user_id,1);
		echo '<meta http-equiv="refresh" content="0;url=../act.php?main">';
		die();
	};
	if(mysql_num_rows($owner)!=1)
	{
		//���� ����� �� ����� � ���, �������� ��� ������� � ����
		OpenTable('title');
		echo '<p align=left>';
		QuoteTable('open');
		echo '<font color=#FF0000>����� ������ ����, ��� ��� �� ���� �����!';
		QuoteTable('close');
		echo '</p>';
		ForceFunc($user_id,5);
		set_delay_reason_id($user_id,1);
		setLocation("../act.php?func=main");
		OpenTable('close');
		if ($_SERVER['REMOTE_ADDR']==debug_ip) {show_debug();}
		if (function_exists("save_debug")) save_debug();
		exit();
	}else 
	{
		//���� ����� ����� � ���, ���������� ������� "�������� � ���"
		set_delay_reason_id($user_id,51);	
	}
	// ���� ����� ���� ����� � ��� - ������, �����, ���� �� ���� - ��������
	$owner_id=mysql_result($owner,0,0);
	$q_result = myquery("SELECT quest_owner_id FROM quest_engine_users WHERE user_id=".$user_id." AND quest_owner_id=".$owner_id." ");
	if(mysql_num_rows($q_result)==1)
		$action_type=12;
	else
		$action_type=11;
//}

	//������ ����������� ����� �������
	if(!isset($_SESSION['for_quest']['correct_types']))
			$_SESSION['for_quest']['correct_types']=array('1','2','5','601','7','801','802','803','804');

	if($action_type==11 OR $action_type==12)
	{
		require_once('quest_engine_types/inc/quest_define.inc.php');
		include("quest_engine_types/inc/standart_func.lib.php");
		
		OpenTable('title');
		$owner_name=mysql_result(myquery("SELECT name FROM quest_engine_owners WHERE id=".$owner_id.""),0,0);
		echo '<center><font size=4 color=#fce66b><br>'.$owner_name.'</center>';
		echo '<hr align=center size=2 width=80%>';
		echo '<p align=justify>';
		?><TABLE align="center" border="0" cellpadding="3" cellspacing="3" width="70%"><?
	}
			
	switch ($action_type)//
	{
		// ���������� action_type ���������������� ����
		// 1.1 - ��������� ������
		case 11:
		{	
			// ���� �������� �������
			//����� rep_num ��������� � ������, � ���� �������� �����������������, ���� ����� ��� �� ��������� � ����������
			//�.�. ��� ���������� �������� �� �� �������� �� "������� ��������" ���������
			if(isset($_SESSION['for_quest']['rep_num']) AND (!isset($rep_num) OR ($rep_num<$_SESSION['for_quest']['rep_num'] AND $rep_num>0)))
			{
				$rep_num=$_SESSION['for_quest']['rep_num'];
			}
			if(!isset($rep_num)) $rep_num=0;
			//if($rep_num>=0) ������� ��� �������
			if(!isset($done) AND $rep_num>=0 AND $rep_num<=2 OR $rep_num==77 OR $rep_num==666)
			{
				include	('quest_engine_types/quests_engine_getting.php');
			}
			else
			{
				//��������� �������� "����"
				ForceFunc($user_id,5);
				set_delay_reason_id($user_id,1);
				//����� � ��
				if (isset($_SESSION['for_quest']))unset($_SESSION['for_quest']);	        
				//��������� ���
				setLocation("../act.php?func=main");
			}
			break;
		}
		//1.2 - ����� ������
		case 12:
		{
			//��������, ��� �� � �����
			if(!isset($done))
			{
				include ('quest_engine_types/quests_engine_leaving.php');
			}
			else
			{
				//��������� �������� "����"
				ForceFunc($user_id,5);
				set_delay_reason_id($user_id,1);
				if (isset($_SESSION['for_quest'])) unset($_SESSION['for_quest']);
				//����� � ��
				//��������� ���
				setLocation("../act.php?func=main");
			}
			break;
		}
	}
}

include("../inc/template_footer.inc.php");

if ($_SERVER['REMOTE_ADDR']==debug_ip) {show_debug();}
if (function_exists("save_debug")) save_debug();

?>
