<?php
require_once('lib_attack.inc.php');
require_once('lib_arcomage.inc.php');
require_once('lib_craft.inc.php');
require_once('lib_inv.inc.php');
require_once('lib_admin.inc.php');
require_once('lib_exp.inc.php');
require_once('lib_user.inc.php');

require_once('combat/combat.inc.php');

function get_user($column,$key,$par=0)
{
	if ($par==0)
	{
		$selname = myquery("SELECT `$column` FROM game_users WHERE user_id='$key'");
		if (!mysql_num_rows($selname)) $selname = myquery("SELECT `$column` FROM game_users_archive WHERE user_id='$key'");
	}
	elseif ($par==1)
	{
		$selname = myquery("SELECT `$column` FROM game_users WHERE name='$key'");
		if (!mysql_num_rows($selname)) $selname = myquery("SELECT `$column` FROM game_users_archive WHERE name='$key'");
	}
	if ($selname!=false AND mysql_num_rows($selname)>0)
	{
		return mysql_result($selname,0,0);
	} 
	else
	{
		return "~~~";
	}
}

function mysqlresult($query,$p1=0,$p2=0)
{
	if ($query!=false AND mysql_num_rows($query)>0)
	{
		return mysql_result($query,$p1,$p2);
	}
	else
	{
		return "";
	}
}

function print_sklon($user,$from_chat=0,$ret=0)
{
	if ($from_chat==0)
	{
		if ($ret==0)
		{
			if ($user['clan_id']==1) echo'<img src="http://'.img_domain.'/sklon/admin.gif" border="0" alt="���" title="������ ����������">&nbsp;';
			elseif ($user['sklon']==1) echo'<img src="http://'.img_domain.'/sklon/neutral.gif" border="0" alt="����" title="����������� ����������">&nbsp;';
			elseif ($user['sklon']==2) echo'<img src="http://'.img_domain.'/sklon/light.gif" border="0" alt="����" title="������� ����������">&nbsp;';
			elseif ($user['sklon']==3) echo'<img src="http://'.img_domain.'/sklon/dark.gif" border="0" alt="����" title="������ ����������">&nbsp;';
		}
		else
		{
			if ($user['clan_id']==1) return'<img src="http://'.img_domain.'/sklon/admin.gif" border="0" alt="���" title="������ ����������">&nbsp;';
			elseif ($user['sklon']==1) return'<img src="http://'.img_domain.'/sklon/neutral.gif" border="0" alt="����" title="����������� ����������">&nbsp;';
			elseif ($user['sklon']==2) return'<img src="http://'.img_domain.'/sklon/light.gif" border="0" alt="����" title="������� ����������">&nbsp;';
			elseif ($user['sklon']==3) return'<img src="http://'.img_domain.'/sklon/dark.gif" border="0" alt="����" title="������ ����������">&nbsp;';
		}
	}
	else
	{
		if ($user['clan_id']==1) return '<img width="12" height="12" src="http://'.img_domain.'/sklon/admin.gif" border="0" alt="�" title="������ ����������">&nbsp;';
		elseif ($user['sklon']==1) return '<img width="12" height="12" src="http://'.img_domain.'/sklon/neutral.gif" border="0" alt="�" title="����������� ����������">&nbsp;';
		elseif ($user['sklon']==2) return '<img width="12" height="12" src="http://'.img_domain.'/sklon/light.gif" border="0" alt="�" title="������� ����������">&nbsp;';
		elseif ($user['sklon']==3) return '<img width="12" height="12" src="http://'.img_domain.'/sklon/dark.gif" border="0" alt="�" title="������ ����������">&nbsp;';
	}
}

function getDelayReasonCraft($craft_index)
{
	switch($craft_index)
	{
		case 1: return 9; break;
		case 2: return 4; break;
		case 4: return 28; break;
		case 5: return 29; break;
		case 6: return 30; break;
		case 7: return 31; break;
	}
	return 0;
}

function get_delay_reason($id)
{
	switch ($id)
	{
		case 1:{return '����';}break;
		case 2:{return '� ������';}break;
		case 3:{return '� ��������';}break;
		case 4:{return '����� �����';}break;
		case 5:{return '�� ������';}break;
		case 6:{return '� ��������';}break;
		case 7:{return '������ �������';}break;
		case 8:{return '������������ �� ������';}break;
		case 9:{return '�������� �����������';}break;
		case 10:{return '������ � ��� �����';}break;
		case 11:{return '� ��� � �����';}break;
		case 12:{return '������ ����';}break;
		case 13:{return '�������� �����';}break;
		case 14:{return '� ������� ���';}break;
		case 15:{return '� �����';}break;
		case 16:{return '� ����� ���';}break;
		case 17:{return '� ������������� ���';}break;
		case 18:{return '� ��� ��� ������ ����';}break;
		case 19:{return '���� �������';}break;
		case 20:{return '����� �������';}break;
		case 21:{return '������ �����';}break;
		case 22:{return '��������������';}break;
		case 23:{return '���������';}break;
		case 24:{return '������� ������';}break;
		case 25:{return '���������';}break;
		case 26:{return '������ �����';}break;
		case 27:{return '������� ����������';}break;

		case 28:{return '����� ���';}break;
		case 29:{return '�������� �����';}break;
		case 30:{return '�������� ����';}break;
		case 31:{return '�������� �� ���������';}break;
		case 32:{return '� ����� �������������';}break;
		case 33:{return '�������� � ����������� ����';}break;
		case 34:{return '�������� � �������';}break;

		case 43:{return '� ��������';}break;

		case 44:{return '� ��������� ��������� ���';}break;
		case 45:{return '� ��� � �����';}break;
		case 46:{return '� ��������� ��������� ���';}break;
		case 47:{return '� ��������� �����';}break;
		case 48:{return '� ��� ���';}break;
		case 49:{return '� ��� �����������';}break;
		case 50:{return '� ����������� ���';}break;


		//inq! ������ ������� - �� 50 ������ ��� ����� �������
		case 51:{return '�������� � ��������� ����������';}break;
		case 52:{return '������ �����������';}break;
		default:
		return $id;
		break;
	}
}

function get_delay_reason_id($user_id)
{
	$sel_rid = myquery("SELECT delay_reason FROM game_users_active_delay WHERE user_id = '".$user_id."' ");
	if(mysql_num_rows($sel_rid)==0)
	{
		return 0;
	}
	else
	{
		$arr_rid = mysql_fetch_array($sel_rid);
		return $arr_rid['delay_reason'];
	}
}

// example: set_delay_reason_id($user_id,1);
function set_delay_reason_id($user_id,$reason_id)
{
	$sel_race = myquery("UPDATE game_users_active_delay SET delay_reason='".$reason_id."' WHERE user_id='".$user_id."' AND (block=0 OR delay<".time().")");
	return 1;
}

// example: get_delay_id($user_id);
function get_delay_id($user_id)
{
	$sel_race = myquery("SELECT delay FROM game_users_active_delay WHERE user_id='".$user_id."' AND (block=0 OR delay<".time().")");
	if ($sel_race!=false AND mysql_num_rows($sel_race)>0)
	{
		return mysql_result($sel_race,0,0);
	}
	return 0;
}

// example: set_delay_id($user_id,1);
function set_delay_id($user_id,$delay_id)
{
	$sel_race = myquery("UPDATE game_users_active_delay SET delay='".$delay_id."' WHERE user_id='".$user_id."' AND (block=0 OR delay<".time().")");
	return 1;
}

// example: set_delay_plus_id($user_id,1);
function set_delay_plus_id($user_id,$delay_id)
{
	$sel_race = myquery("UPDATE game_users_active_delay SET delay=delay+'".$delay_id."' WHERE user_id='".$user_id."' AND (block=0 OR delay<".time().")");
	return 1;
}

// example: set_delay_info($user_id,1000,1);
function set_delay_info($user_id,$delay_id,$reason_id,$block=0)
{
	if ($block==1)
	{
		$sel_race = myquery("UPDATE game_users_active_delay SET delay='".$delay_id."',delay_reason='".$reason_id."',block='".$block."' WHERE user_id='".$user_id."' ");
	}
	elseif ($block==0)
	{
		$sel_race = myquery("UPDATE game_users_active_delay SET delay='".$delay_id."',delay_reason='".$reason_id."',block='".$block."' WHERE user_id='".$user_id."' AND (block=0 OR delay<".time().") ");
	}
	
	return 1;
}

function setLocation($loc)
{
	if (!headers_sent())
	{
		header("Location:".$loc);
	}
	else
	{
		echo '<script>location.replace(\''.$loc.'\')</script>';
	}
}

define('func_combat',       1);
define('func_craft',        2);
define('func_market',       3);
define('func_arcomage',     4);
define('func_game',         5);
define('func_forum',        6);
define('func_diary',        7);
define('func_chat',         8);
define('func_city',         9);
define('func_quest',       10);
define('func_view',        11);
define('func_chat_combat', 12);  
define('func_quest_engine',13);  

function setFunc($user_id,$func_id)
{
	//user_id:
	//id ������
	//func_id:
	// ���������� ����� ��� a.b. ��� a - ��� �������. b - ������ �������.
	//
	//0.x - ''
	//
	//1.x: - ���
	//1.0 - wait
	//1.1 - .win
	//1.2 - lose
	//1.3 - draw
	//1.4 - otkaz
	//1.5 - net
	//1.6 - boy
	//1.7 - boy_npc
	//1.8 - wait_npc
	//1.9 - podt
	//1.10 - ojid
	//1.11 - duel
	//
	//2.x: - �����
	//2.0 - /inc/craft/craft.inc.php
	//2.1 - ������ ��������
	//2.2 - ������� ( ����� )
	//2.3 - ������������� ( ���� �� ���������� )
	//
	//3.x: - �� ������������
	//3.0 -
	//
	//4.x - ��� �����
	//4.0 - arcomage
	//1 - arcomage_boy
	//2 - arcomage_wait
	//3 - arcomage_lose
	//4 - arcomage_win
	//5 - arcomage_draw
	//6 - arcomage_ojid
	//7 - arcomage_podt
	//8 - arcomage_otkaz
	//9 - arcomage_net
	//
	//5.x - Act + move
	//
	//6.x - Forum
	//
	//7.x - Diary
	//
	//8.x - Chat
	//
	//9.x - �����
	//
	//10.x - �����
	//
	//11.x - /view/
	//
	//12.x - ������ ���
	//
	////inq! 13.x - ������ ������� (�� ����������, �� ������� ����, ����� ����� ���)
	// � ������ � �������� ������ � ���
	// ���� �����

	// �������� ��� � ���, ��� ��������� ������ �� ����� ������ ID � ������ func_id, � ����� ��� ����� �����
	if(!isset($func_id) OR $func_id=='' OR $func_id=='0' OR $func_id=='MODULE_ID')
	{
		return 1;
	}

	myquery("UPDATE game_users_func SET func_id='".$func_id."' WHERE user_id='".$user_id."' ");
	return 1;

}

function getFunc($user_id)
{
	$sel_rid = myquery("SELECT func_id FROM game_users_func WHERE user_id = '".$user_id."' ");
	if(mysql_num_rows($sel_rid)==0)
	{
		myquery("INSERT INTO game_users_func (`user_id`,`func_id`) VALUES ('".$user_id."','5')");
		return 5;
	}
	else
	{
		$arr_rid = mysql_fetch_array($sel_rid);
		return $arr_rid['func_id'];
	}
}

function getRedirectFunc($user_id)
{
	$arr_func=array("act.php","combat.php","craft.php","non_exist.php","arcomage.php","act.php","/forum/index.php","/diary/","/chat/","/lib/town.php","/quest/quest1.php","non_exist.php","non_exist.php","/quest/quests_engine_chek.php","/quest/quest_artur.php","/quest/quest_destroyer.php","/quest/quest_demon.php","/quest/quest_zombi.php","/quest/quest_dungeon.php");
	$func=getFunc($user_id);
	return $arr_func[$func];
}

function checkFunc($user_id,$func_id,$only_check=0)
{
	//���������� 0 ���� ���� ������� ��������
	
	// �������� ��� ����
	if($func_id==8)
	{
		return 1;
	}
	// �������� ��� view
	if($func_id==11)
	{
		return 1;
	}
	// ���� ��� ��� MODULE_ID
	if($func_id=="MODULE_ID")
	{
		return 1;
	}
	$old_func_id=getFunc($user_id);
	// � �������� ������ ������
	if($old_func_id==$func_id)
	{
		return 1;
	}
	// � ��� ����� ���������� ������ �� ����
	if($func_id==1 AND $old_func_id!=5)
	{
		return 0;
	}
	// ��������� �� ��� -> ������ ���, �� �� ��������� func
	if($func_id==12 AND $old_func_id==1)
	{
		return 1;
	}
	
	// ��������� �� ������ -> �����, �� �� ��������� func
	if(($func_id==2) AND ($old_func_id==9))
	{
		return 1;
	}

	// ���������: ������� <-> ����
        // ������� -> ����� � ������� -> �������
	if(($func_id==5 AND $old_func_id==3) OR ($func_id==3 AND $old_func_id==5) OR 
	   ($func_id==6 AND $old_func_id==3) OR ($func_id==7 AND $old_func_id==3))
	{
		return 1;
	}

	// ��������� �� ������ � ������ � �������� � ���(!) -> ����� � �������, �� �� ��������� func
	// ��� ��� ����� ��� - ����� -> ����� -> ���� - � ��� ��������� ����� ����� ������
	if(($func_id==6 OR $func_id==7) AND ($old_func_id==2 OR $old_func_id==9 OR $old_func_id==10 OR $old_func_id==4))
	{
		return 1;
	}


	// ����� � ������� --> ����
	if(($old_func_id==6 OR $old_func_id==7 OR $old_func_id==9) AND $func_id==5)
	{
		if($only_check==0)
		{
			setFunc($user_id,$func_id);
		}
		return 1;
	}
	// ����� � ������� � ����� --> ������� � ����� � �����
	if(($old_func_id==6 OR $old_func_id==7) AND ($func_id==6 OR $func_id==7 OR $func_id==9))
	{
		if($only_check==0)
		{
			setFunc($user_id,$func_id);
		}
		return 1;
	}
	// ���� --> ...
	// � ��������� ��� � ������ ��� �� ������� �� ������ �������
	if(($old_func_id==5) AND ($func_id!=8 AND $func_id!=12))
	{
		if($only_check==0)
		{
			setFunc($user_id,$func_id);
		}
		return 1;
	}
	// ��������� ������ ������
	return 0;
}

function ForceFunc($user_id,$func_id)
{
	setFunc($user_id,$func_id);
	return 1;
}

function get_GP_reason($id)
{
	switch ($id)
	{
		case 1:{return '��������� �� ������';}break;
		case 2:{return '������ ��������� ����� ���������';}break;
		case 3:{return '����� ��� ����������� ������ ������ �� ������ - "�����������"';}break;
		case 4:{return '��������� ������ ��� �����������';}break;
		case 5:{return '������ ������ � �������';}break;
		case 6:{return '������� ������� ������� � �������';}break;
		case 7:{return '������ �� ������������� �������� � ��������';}break;
		case 8:{return '������� �������� ��������';}break;
		case 9:{return '������� �������� � ��������';}break;
		case 10:{return '������� ��������� � ��������';}break;
		case 11:{return '������ �������� � ��������';}break;
		case 12:{return '������� �������� �� �����';}break;
		case 13:{return '������� �������� �� �����';}break;
		case 14:{return '����� �� ����������� �������� �� �����';}break;
		case 15:{return '������ �� ������������� ������';}break;
		case 16:{return '������� ������';}break;
		case 17:{return '������� ������';}break;
		case 18:{return '������ �� ����������� ���������';}break;
		case 19:{return '������� ����� ��� �������� ���� �� ��������� ��������������';}break;
		case 20:{return '��������� ������ ��������������';}break;
		case 21:{return '��������� ������';}break;
		case 22:{return '������ ��� ����� � ���';}break;
		case 23:{return '�������� ����� � ��������';}break;
		case 24:{return '������� ����� � ��������';}break;
		case 25:{return '������ ��� ������� � ���';}break;
		case 26:{return '���������� ����� ��� ������ � ��� (�������)';}break;
		case 27:{return '������ �� ������ � ��� (�������)';}break;
		case 28:{return '������ �� ����������� ����������';}break;
		case 29:{return '��������� ����� ��� ���������';}break;
		case 30:{return '����� ����� �� ���� ������� ���� � �����';}break;
		case 31:{return '������ ����� � �������� ����� � �����';}break;
		case 32:{return '����� �� �������� �������� �����';}break;
		case 33:{return '������ ����� ����� ��������������';}break;
		case 34:{return '������ ��� ������� ����� � ������ � ������������� ������ � ���';}break;
		case 35:{return '�������/������� ����� ����� ����� ��������� ����� �����';}break;
		case 36:{return '�������/������� ���� ����� ��������� ����� �����';}break;
		case 37:{return '�������� ��� ������������� ���������� ����� �����';}break;
		case 38:{return '������ ������ �� ��������� (������������ ��������)';}break;
		case 39:{return '����� �� ����� ������ � ����';}break;
		case 40:{return '������ ������ �� ����';}break;
		case 41:{return '������ ����������� ������ �����';}break;
		case 42:{return '������� ������ � �������';}break;
		case 43:{return '������� ������ � �������';}break;
		case 44:{return '������ �� ������� ������ � ������';}break;
		case 45:{return '������� ����� �� ���������� �������� ��� ���������';}break;
		case 46:{return '������ �������� ��������';}break;
		case 47:{return '������ ����� ��������������� ������ ������� ��������� �� ���������';}break;
		case 48:{return '������� ������� �� �����';}break;
		case 49:{return '������� ������� �� �����';}break;
		case 50:{return '������ ������ ����� �� �����';}break;
		case 51:{return '������� ������ � ������� �����';}break;
		case 52:{return '������ �� ������������� ������ ������� ����';}break;
		case 53:{return '������� ����� �� ����';}break;
		case 54:{return '����� �� ���������� ������� �������';}break;
		case 55:{return '������� ��� � �������';}break;
		case 56:{return '������ �� �������';}break;
		case 57:{return '������ ����� �� ���������� ����� ������� ����� ������� ����������';}break;
		case 58:{return '��������� ��� ��������� ������� ��������';}break;
		case 59:{return '��������� ����� �� ����� "�������� ���������� ����������"';}break;
		case 60:{return '���������� ����� � ��������� ������ ������� ��������';}break;
		case 61:{return '�������������� ������� ������ ��� ����������� �� ������� ���� � ����������� ��������';}break;
		case 62:{return '������� ��� ��� �������';}break;
		case 63:{return '����� �� ����������� ��������������� ����������';}break;
		case 64:{return '����� �� �������� ������� ���������';}break;
		case 65:{return '����� �� ����������� ��������������� ������';}break;
		case 66:{return '����� �� ����������� ��������������� ������������ ������������';}break;
		//������ ������� - �� 80 ������ ��� ����� �������
		case 81:{return '������� �� ���������� �������';}break;
		case 82:{return '����� �� ��������� ��������';}break;
		case 83:{return '����� �� ������ ������ � ��������';}break;	
		case 101:{return '��������� ����� �� ����� "��� � ������"';}break;
		case 102:{return '��������� ����� �� ����� "�����������"';}break;
		case 103:{return '��������� ����� �� ����� "����-�����"';}break;
		case 104:{return '��������� ����� �� ����� "�����"';}break;
		case 104:{return '��������� ����� �� ����� "���������� ������� �����"';}break;
		case 105:{return '������� ����� � �����';}break;
		case 106:{return '������ �������� � ������� ��������� �� ���������';}break;
		case 107:{return '������ ���� � ����� ������';}break;
		case 108:{return '������ ����� ����� �����';}break;
		case 109:{return '��������/�������� �������� � ���';}break;
		case 110:{return '������������ �������� �� ������ � �������� ������';}break;
		case 111:{return '��������� ����� � ����� � ���������� � ������� �����������';}break;
		case 112:{return '������/���������� ����� �� �������� �����';}break;
		case 113:{return '����� ��������� � ������ ���������';}break;
		default:
		return '';
		break;
	}
}

function setGP($user,$gp,$reason)
{	
	if ($gp!=0) myquery("INSERT DELAYED INTO game_users_stat_gp (user_id,gp,reason,timestamp) VALUES ($user,$gp,$reason,".time().")");
}

function save_gp ($user_id, $gp, $reason_id, $type=1)
{
	myquery("Update game_users Set GP=GP+'".$gp."' Where user_id='".$user_id."'");
	if ($type==2)
	{
		myquery("Update game_users_archive Set GP=GP+'".$gp."' Where user_id='".$user_id."'");
	}
	setGP($user_id,$gp,$reason_id);
}

function get_EXP_reason($id)
{
	switch ($id)
	{
		case 1:{return '������ ��� ����� � ���';}break;
		case 2:{return '������ ��� ������� � ���';}break;
		case 3:{return '����������� ���������';}break;
		case 4:{return '��������� ����� ��������������';}break;
		case 5:{return '���������� ������� � ������� ��������� �� ���������';}break;
		case 6:{return '����������� ������ "�������� ���������� ����������"';}break;
		case 7:{return '';}break;
		case 8:{return '����������� ������ "��� � ������"';}break;
		case 9:{return '����������� ������ "�����������"';}break;
		case 10:{return '����������� ������ "����-�����"';}break;
		case 11:{return '����������� ������ "�����"';}break;
		case 12:{return '����������� ������ "���������� ������� �����"';}break;
		case 13:{return '����������� ������ "������ ���������"';}break;
		case 14:{return '�������� �������� �� ������� ����';}break;
		case 15:{return '���������� ��������� �������� ����';}break;
		case 16:{return '��������� ������������';}break;
		case 17:{return '��������� ������ ��������';}break;
		case 18:{return '��������/�������� �������� � ���';}break;
		default:
		return '';
		break;
	}
}

function setEXP($user,$exp,$reason)
{
	//���������� ����� ��� ������������ �� �����. ������ ���������
	//***********************************************************************************************************************************
	//reason:
	//1 - ���� �� �������� ����
	//2 - ���� �� �������� ������
	//3 - ���� �� ����������� ���������
	//4 - ��������� ����� ����� ���������
	//5 - �� ���������� ������� � ������� ��������� �� ���������
	//6 - �� ����������� ������ "�������� ���������� ����������"
	//7 -
	if ($exp!=0) myquery("INSERT DELAYED INTO game_users_stat_exp (user_id,exp,reason,timestamp) VALUES ($user,$exp,$reason,".time().")");
}

function save_exp ($user_id, $exp, $reason_id, $type=1)
{
	myquery("Update game_users Set EXP=EXP+'".$exp."' Where user_id='".$user_id."'");
	if ($type==2)
	{
		myquery("Update game_users_archive Set EXP=EXP+'".$exp."' Where user_id='".$user_id."'");
	}
	setEXP($user_id,$exp,$reason_id);
}

function echo_sex($male,$female,$par="")
{
	global $pol;
	if ($par=="" OR $par==NULL)
	{
		$l_pol = $pol;
	}
	else
	{
		$l_pol = $par;
	}
	if ($l_pol=='female')
	{
		return $female;
	}
	else
	{
		return $male;
	}
}

function pluralForm($n, $form1, $form2, $form5)
{
	$n = abs($n) % 100;
	$n1 = $n % 10;
	if ($n > 10 && $n < 20) return $form5;
	if ($n1 > 1 && $n1 < 5) return $form2;
	if ($n1 == 1) return $form1;
	return $form5;
}

function show_page($page,$allpage,$href)
{
	static $sp_num_on_page = 0;
	if ($allpage>1)
	{
		if($page>1) {
			echo '<a href="'.$href.'&amp;page='.($page-1).'">&lt;����.&gt;</a>&nbsp;&nbsp;';
		}
		$mark1 = 0;
		$mark2 = 0;
		for ($i=1;$i<=$allpage;$i++)
		{
			//echo '<span style="display:none;"><a href="'.$href.'&amp;page='.$i.'">'.$i.'</a></span>';
			if (($i>$page+2 AND $i<$allpage-2) OR ($i>2 AND $i<$page-2))
			{
				if ($i<$page AND $mark1==0)
				{
					echo '......, ';
					$mark1 = 1;
				}
				if ($i>$page AND $mark2==0)
				{
					echo '......, ';
					$mark2 = 1;
				}
				continue;
			}
			if ($i==$page)
			   if ($i==$allpage) echo '<b>'.$i.'</b>.' ;
			   else echo '<b>'.$i.'</b>, ';
			elseif ($i<>$allpage) echo '<a href="'.$href.'&amp;page='.$i.'">'.$i.'</a>, ';
			else echo '<a href="'.$href.'&amp;page='.$i.'">'.$i.'</a>.';
		}
		if($page<$allpage) {
			echo '&nbsp;&nbsp;<a href="'.$href.'&amp;page='.($page+1).'">&lt;����.&gt;</a>';
		}
		?>
		<script language="JavaScript" type="text/javascript">
		function loc(href,n)
		{
			p = document.getElementById('newpage' + n).value;
			location.href=href+'&page='+p;
		}
		</script>
		<?
		echo '&nbsp;&nbsp;<select id="newpage'.$sp_num_on_page.'" onChange="loc(\''.$href.'\','.$sp_num_on_page.')">';
		$d=50;
		if ($allpage<=20) $d=5;
		if ($allpage<=40) $d=10;
		if ($allpage<=60) $d=15;
		if ($allpage<=100) $d=20;
		if ($allpage<=120) $d=30;
		if ($allpage<=160) $d=40;
		for ($i=1;$i<=$allpage;$i++)
		{
			if ($i>20)
			{
				if ($i%$d!=0) continue;
			}
			echo '<option value='.$i.'';
			if ($page==$i) echo ' selected';
			echo '>'.$i.'</option>';
		}
		echo '</select>';
		$sp_num_on_page++;
	}
}

function ip2number($ip)
{
	$ip_array = explode(".",$ip);
	if (gettype($ip_array)=="array")
	{
		if (sizeof($ip_array)==4)
		{
			return $ip_array[3]+256*$ip_array[2]+256*256*$ip_array[1]+256*256*256*$ip_array[0];
		}
		else
		{
			return 0;
		}
	}
	else
	{
		return 0;
	}
}

function number2ip($number)
{
	$temp = $number;
	$ip='';
	$ip0 = floor($temp/256/256/256);
	$temp-=($ip0*256*256*256);
	$ip1 = floor($temp/256/256);
	$temp-=($ip1*256*256);
	$ip2 = floor($temp/256);
	$temp-=($ip2*256);
	$ip3=$temp;
	$ip.=$ip0.'.'.$ip1.'.'.$ip2.'.'.$ip3;
	return $ip;
}

function save_stat($par_user_id="",$par_npc_id="",$par_town="",$par_stat_id="",$par_shop_id="",$par_item="",$par_enemy_id="",$par_gp="",$par_clan_id="",$par_exp="",$par_level_user="",$par_level_enemy="")
{

	if (domain_name=='testing.rpg.su') return;
	if (domain_name=='localhost')
	{
		$db_stat = mysql_connect(PHPRPG_DB_HOST, PHPRPG_DB_USER, PHPRPG_DB_PASS) or die(mysql_error());
	}
	else
	{
		$db_stat = mysql_connect('localhost', 'rpgsu_stats', 'EuTh4fsFjdvMMuSY') or die(mysql_error());
	}
	mysql_select_db('rpgsu_stats',$db_stat) or die(mysql_error());
	/*
	$da = getdate();
	$table_name = "stat_".$da['year'].'_'.$da['mon'];
	$find = 0;
	$db_list = mysql_list_tables('rpgsu_stats') or die(mysql_error());
	$i = 0;
	$cnt = mysql_num_rows($db_list) or die(mysql_error());
	while ($i < $cnt)
	{
		if (mysql_tablename($db_list, $i) == $table_name)
		{
			$find = 1;
		}
		$i++;
	}
	if ($find==0)
	{
		$str_query  = "   CREATE TABLE $table_name (
						  user_id mediumint(15) NOT NULL default '0',
						  npc_id mediumint(8) NOT NULL default '0',
						  town int(10) NOT NULL default '0',
						  stat_id int(3) unsigned NOT NULL default '0',
						  shop_id mediumint(9) NOT NULL default '0',
						  item_id int(10) NOT NULL default '0',
						  enemy_id int(15) NOT NULL default '',
						  gp decimal(15,2) unsigned default '0.00',
						  clan_id mediumint(10) NOT NULL default '0',
						  exp mediumint(8) NOT NULL default '0',
						  id int(15) unsigned NOT NULL auto_increment,
						  `time` int(14) NOT NULL default '0',
						  level_user tinyint(3) unsigned NOT NULL default '0',
						  level_enemy tinyint(3) unsigned NOT NULL default '0',
						  PRIMARY KEY  (id),
						  KEY stat_id (stat_id,level_user,level_enemy)
						) ENGINE=MyISAM";
		myquery($str_query,$db_stat) or die(mysql_error());
	}
	$sel_item = myquery("SELECT id FROM game_stat_item WHERE name='$par_item'",$db_stat) or die(mysql_error());
	{
		if ($sel_item!=false AND mysql_num_rows($sel_item)>0)
		{
			list($item_id) = mysql_fetch_array($sel_item);
		}
		else
		{
			myquery("INSERT INTO item (name) VALUES ('$par_item')",$db_stat) or die(mysql_error());
			$item_id = mysql_insert_id();
		}
	}
	*/
	$item_id = $par_item;
	if (gettype($item_id)=='integer')
	{
		list($item_id) = mysql_fetch_array(myquery("SELECT name FROM game_items_factsheet WHERE id=$item_id"));
	}
	$table_name = 'game_stat';
	$str_query = "INSERT DELAYED INTO $table_name (user_id,npc_id,town,stat_id,shop_id,item_id,enemy_id,gp,clan_id,exp,level_user,level_enemy,time) VALUES ('$par_user_id','$par_npc_id','$par_town','$par_stat_id','$par_shop_id','$item_id','$par_enemy_id','$par_gp','$par_clan_id','$par_exp','$par_level_user','$par_level_enemy',".time().")";
	myquery($str_query);
	DbConnect();
}

function OpenTable($openTableParam, $quoteTableWidth = '100%', $quoteTableHeight = 0)
{
	if ($openTableParam == 'title')
	{
		echo '<table cellpadding="0" cellspacing="2" width="'.$quoteTableWidth.'" border="0"';
		if ($quoteTableHeight!=0) echo ' height="'.$quoteTableHeight.'"';
		echo '><tr><td valign="top" class=m background="http://'.img_domain.'/nav/image_01.jpg" align=left>';
	}
	elseif ($openTableParam == 'close')
	{
		echo '</td></tr></table>';
	}
	else
	{
		echo '������ OpenTable!';
	}
}

function QuoteTable($quoteTableParam, $quoteTableWidth = '')
{
	if ($quoteTableParam == 'open')
	{
		if ($quoteTableWidth!='')
		{
			$quoteTableWidth = ' width="' . $quoteTableWidth . '"';
		}
		echo '<table cellpadding="0" cellspacing="0"' . $quoteTableWidth . ' border="0">
		<tr><td style="width:15px;"><img src="http://'.img_domain.'/nav/quote_ul.gif" width="15" height="7" border="0" alt=""></td>
		<td background="http://'.img_domain.'/nav/quote_tp.gif"><img src="http://'.img_domain.'/nav/x.gif" width="1" height="1" border="0"></td>
		<td style="width:15px;"><img src="http://'.img_domain.'/nav/quote_ur.gif" width="15" height="7" border="0" alt=""></td>
		</tr><tr><td style="width:15px;" background="http://'.img_domain.'/nav/quote_lt.gif"><img src="http://'.img_domain.'/nav/x.gif" width="1" height="1" border="0"></td><td>';
	}
	elseif ($quoteTableParam == 'close')
	{
		echo '</td><td style="width:15px;" background="http://'.img_domain.'/nav/quote_rt.gif"><img src="http://'.img_domain.'/nav/x.gif" width="1" height="1" border="0"></td>
		</tr>
		<tr><td style="width:15px;"><img src="http://'.img_domain.'/nav/quote_dl.gif" width="15" height="7" border="0" alt=""></td>
		<td background="http://'.img_domain.'/nav/quote_bt.gif"><img src="http://'.img_domain.'/nav/x.gif" width="1" height="1" border="0"></td>
		<td style="width:15px;"><img src="http://'.img_domain.'/nav/quote_dr.gif" width="15" height="7" border="0" alt=""></td>
		</tr></table>';
	}
	else
	{
		echo '������ QuoteTable!';
	}
}

function StartTiming()
{
	$micro_time = microtime();
	$time_parts = explode(" ",$micro_time);
	$start_time = $time_parts[1] . substr($time_parts[0],1);
	return $start_time;
}

function StopTiming($startTime)
{
	$micro_time = microtime();
	$time_parts = explode(" ",$micro_time);
	$end_time = $time_parts[1] . substr($time_parts[0],1);
	$exec_time = round($end_time - $startTime, 5)*1000;
	return $exec_time;
}

function HostIdentify()
{
	$str_return = ip2number($_SERVER['REMOTE_ADDR']);
	return $str_return;
}

function HostIdentifyMore()
{
	global $HTTP_CLIENT_IP,$HTTP_VIA, $HTTP_X_COMING_FROM, $HTTP_X_FORWARDED_FOR, $HTTP_X_FORWARDED, $HTTP_COMING_FROM, $HTTP_FORWARDED_FOR, $HTTP_FORWARDED;

   $str_return = '';

   if ($HTTP_CLIENT_IP)
   {
		$b = preg_match("/^([0-9]{1,3}\.){3,3}[0-9]{1,3}/", $HTTP_CLIENT_IP, $array);
		if ($b && (count($array) >= 1))
		{
			$str_return = gethostbyaddr($array[0]);
			$str_return = $array[0];
		}
		else
		{
			$str_return = $HTTP_VIA . '_' . $HTTP_CLIENT_IP;
		}
	}
	elseif ($HTTP_X_FORWARDED_FOR)
	{
		$b = preg_match("/^([0-9]{1,3}\.){3,3}[0-9]{1,3}/", $HTTP_X_FORWARDED_FOR, $array);
		if ($b && (count($array) >= 1))
		{
			$str_return = gethostbyaddr($array[0]);
			$str_return = $array[0];
		}
		else
		{
			$str_return = $HTTP_VIA . '_' . $HTTP_X_FORWARDED_FOR;
		}
	}
	elseif ($HTTP_X_FORWARDED)
	{
		$b = preg_match("/^([0-9]{1,3}\.){3,3}[0-9]{1,3}/", $HTTP_X_FORWARDED, $array);
		if ($b && (count($array) >= 1))
		{
			$str_return = gethostbyaddr($array[0]);
			$str_return = $array[0];
		}
		else
		{
			$str_return = $HTTP_VIA . '_' . $HTTP_X_FORWARDED;
		}
	}
	elseif ($HTTP_FORWARDED_FOR)
	{
		$b = preg_match("/^([0-9]{1,3}\.){3,3}[0-9]{1,3}/", $HTTP_FORWARDED_FOR, $array);
		if ($b && (count($array) >= 1))
		{
			$str_return = gethostbyaddr($array[0]);
			$str_return = $array[0];
		}
		else
		{
			$str_return = $HTTP_VIA . '_' . $HTTP_FORWARDED_FOR;
		}
	}
	elseif ($HTTP_FORWARDED)
	{
		$b = preg_match("/^([0-9]{1,3}\.){3,3}[0-9]{1,3}/", $HTTP_FORWARDED, $array);
		if ($b && (count($array) >= 1))
		{
			$str_return = gethostbyaddr($array[0]);
			$str_return = $array[0];
		}
		else
		{
			$str_return = $HTTP_VIA . '_' . $HTTP_FORWARDED;
		}
	}
	elseif ($HTTP_VIA)
	{
		$str_return = $HTTP_VIA . '_' . $HTTP_X_COMING_FROM . '_' . $HTTP_COMING_FROM;
	}
	elseif($HTTP_X_COMING_FROM || $HTTP_COMING_FROM)
	{
		$str_return = $HTTP_X_COMING_FROM . '_' . $HTTP_COMING_FROM;
	}

	return $str_return;
}

function GetGameCalendar_Year($param_data_year,$param_data_month,$param_data_day)
{
	$data_year = (int)$param_data_year;
	$data_month = (int)$param_data_month;
	$data_day = (int)$param_data_day;
	$a = 0;
	if (floor($data_year/4)==($data_year/4)) $a = 1;
	$data_year = $data_year-2000;
	$data_year = ($data_year - floor($data_year/4) - 1)*365+(floor($data_year/4)+1)*366-1;
	if ($data_month == 1) $data_month = $data_day;
	elseif ($data_month == 2) $data_month = $data_day+31;
	elseif ($data_month == 3) $data_month = $data_day+59+$a;
	elseif ($data_month == 4) $data_month = $data_day+90+$a;
	elseif ($data_month == 5) $data_month = $data_day+120+$a;
	elseif ($data_month == 6) $data_month = $data_day+151+$a;
	elseif ($data_month == 7) $data_month = $data_day+181+$a;
	elseif ($data_month == 8) $data_month = $data_day+212+$a;
	elseif ($data_month == 9) $data_month = $data_day+243+$a;
	elseif ($data_month == 10) $data_month = $data_day+273+$a;
	elseif ($data_month == 11) $data_month = $data_day+304+$a;
	elseif ($data_month == 12) $data_month = $data_day+334+$a;

	$data_year = $data_year + $data_month - 1657;
	If (floor($data_year/12)==($data_year/12))
	{
		$data_month = 12;
		$data_year = floor($data_year/12)-1;
	}
	else
	{
		$data_month = $data_year - floor($data_year/12)*12;
		$data_year = floor($data_year/12);
	}
	return $data_year;
}

function GetGameCalendar_Month($param_data_year,$param_data_month,$param_data_day)
{
	$data_year = (int)$param_data_year;
	$data_month = (int)$param_data_month;
	$data_day = (int)$param_data_day;
	$a = 0;
	if (floor($data_year/4)==($data_year/4)) $a = 1;
	$data_year = $data_year-2000;
	$data_year = ($data_year - floor($data_year/4) - 1)*365+(floor($data_year/4)+1)*366-1;
	if ($data_month == 1) $data_month = $data_day;
	elseif ($data_month == 2) $data_month = $data_day+31;
	elseif ($data_month == 3) $data_month = $data_day+59+$a;
	elseif ($data_month == 4) $data_month = $data_day+90+$a;
	elseif ($data_month == 5) $data_month = $data_day+120+$a;
	elseif ($data_month == 6) $data_month = $data_day+151+$a;
	elseif ($data_month == 7) $data_month = $data_day+181+$a;
	elseif ($data_month == 8) $data_month = $data_day+212+$a;
	elseif ($data_month == 9) $data_month = $data_day+243+$a;
	elseif ($data_month == 10) $data_month = $data_day+273+$a;
	elseif ($data_month == 11) $data_month = $data_day+304+$a;
	elseif ($data_month == 12) $data_month = $data_day+334+$a;

	$data_year = $data_year + $data_month - 1657;
	If (floor($data_year/12)==($data_year/12))
	{
		$data_month = 12;
		$data_year = floor($data_year/12)-1;
	}
	else
	{
		$data_month = $data_year - floor($data_year/12)*12;
		$data_year = floor($data_year/12);
	}
	return $data_month;
}

function CheckUser($param_user_name)
{
	$sel = myquery("(SELECT * FROM game_users WHERE name='$param_user_name') UNION (SELECT * FROM game_users_archive WHERE name='$param_user_name') LIMIT 1");
	$user = mysql_fetch_array($sel);

	$str = $user['STR_MAX'];
	$ntl = $user['NTL_MAX'];
	$pie = $user['PIE_MAX'];
	$vit = $user['VIT_MAX'];
	$dex = $user['DEX_MAX'];
	$spd = $user['SPD_MAX'];
	$clevel = $user['clevel'];
	$race = $user['race'];

	$vsego = $str+$ntl+$pie+$vit+$dex+$spd+$user['bound'];

	$sel_race = myquery("SELECT * FROM game_har WHERE id = '".$race."' LIMIT 1");
	$user_race = mysql_fetch_array($sel_race);
	$summa_race = $user_race['str']+$user_race['ntl']+$user_race['pie']+$user_race['vit']+$user_race['dex']+$user_race['spd'];
	$summa = $summa_race;

	$sel_items = myquery("SELECT * FROM game_items WHERE user_id='".$user['user_id']."'");
	while ($user_items = mysql_fetch_array($sel_items))
	{
	  if ($user_items['used']!=0)
	  {
		  $item_fact = mysql_fetch_array(myquery("SELECT * FROM game_items_factsheet WHERE id=".$user_items['item_id'].""));
		  $summa=$summa+$item_fact['dstr'];
		  $summa=$summa+$item_fact['dntl'];
		  $summa=$summa+$item_fact['dpie'];
		  $summa=$summa+$item_fact['dvit'];
		  $summa=$summa+$item_fact['ddex'];
		  $summa=$summa+$item_fact['dspd'];
	  }
	}

	$har_level=0;
	for ($i=1;$i<=$clevel;$i++)
	{
		if ($i==11 OR $i==21 OR $i==31)
		{
			$har_level=$har_level+3;
		}
		else
		{
			$har_level=$har_level+2;
		}
	}

	$summa=$summa+$har_level;

	$razn1 = $vsego-$summa;

	if ($param_user_name=='blazevic' OR $param_user_name=='�����' OR $param_user_name=='The_Elf' OR $param_user_name=='������2' OR $param_user_name=='ban' OR $param_user_name=='Zander')
	{
		$razn1 = 0;
		$razn2 = 0;
	}

	if ($razn1!=0)
	{
		return $razn1;
	}
	else
	{
		return 0;
	}

}

function replace_enter($str)
{
	$str_return = str_replace(chr(13).''.chr(10),'<br>',$str);
	return  $str_return;
}

function return_enter($str)
{
	$str_return = str_replace('<br>',chr(13).''.chr(10),$str);
	return  $str_return;
}

function add_chat($mes, $user_id=612)
{
	$pismo = iconv("Windows-1251","UTF-8//IGNORE","<span style=\"font-style:italic;font-size:12px;color:gold;\">".$mes."</span>");
	myquery("INSERT INTO game_log (`message`,`date`,`FROMm`,`too`,`ptype`) VALUES ('".mysql_real_escape_string($pismo)."',".time().",-1,'".$user_id."',1)");
}

/*
function jump_random_query(&$query)
{
	$all = mysql_num_rows($query);
	if ($all>0)
	{
		$r = mt_rand(0,$all-1);
		mysql_data_seek($query,$r);
		return 1;
	}
	return 0;
}
*/
?>