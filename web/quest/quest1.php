<?php
$dirclass = "../class";
require_once('../inc/config.inc.php');
require_once('../inc/lib.inc.php');
require_once('../inc/db.inc.php');
if (!defined("MODULE_ID"))
{
	define("MODULE_ID", '10');
}
else
{
	die();
}
require_once('../inc/lib_session.inc.php');

//if (isset($_SESSION['quest1_step'])) echo '<hr>'.$_SESSION['quest1_step'].'<hr>';
//if (isset($_SESSION['quest1_state'])) echo '<hr>'.$_SESSION['quest1_state'].'<hr>';

function add_item_to_user($item_id)
{
	global $user_id,$char;
	$Item = new Item();
	$Item->add_user($item_id,$user_id,0);
}

function attack_npc_quest()
{
	global $user_id,$char;
	//�������� ��� � ����� ����������
	myquery("INSERT INTO game_quest_users SET user_id=$user_id,quest_id=1,last_time=".time().",sost=2 ON DUPLICATE KEY UPDATE sost=2");
	//�������� ����-���������
	//$sel111=myquery("select npc_id from game_npc order by npc_id DESC limit 1");
	//list($nid)=mysql_fetch_array($sel111);
	//$n=''.($nid+1).'';

	if (
		$_SESSION['quest1_step']==7 or
		$_SESSION['quest1_step']==51
	   )
	{
		$npc_img = 'Monster-07';
		$npc_name = '�������� ������';
		$npc_race = '�������';
		$npc_hp = round(0.4*$char['HP_MAX']);
		$npc_max_hp = round(0.4*$char['HP_MAX']);
		$npc_mp = round(0.4*$char['MP_MAX']);
		$npc_max_mp =round(0.4*$char['MP_MAX']);
		$npc_str = round(0.4*$char['STR']);
		$npc_dex = round(0.4*$char['DEX']);
		$npc_wis = round(0.4*$char['PIE']);
		$npc_basefit = round(0.4*$char['VIT']);
		$npc_basedef = round(0.4*$char['SPD']);
		$npc_exp = 5;
		$npc_gold = 0;
		$npc_map_name = 0;
		$npc_xpos = 0;
		$npc_ypos = 0;
		$npc_time = 0;
		$npc_ntl = round(0.4*$char['NTL']);
		$npc_level = $char['clevel'];
		$npc_item = '�����';
	}
	if ($_SESSION['quest1_step']==14)
	{
		$npc_img = 'Clear/scorpy';
		$npc_name = '���� � ���������';
		$npc_race = '��������������';
		$npc_hp = 1000;
		if(($_SESSION['quest1_bad_portal']==1))
		{	$npc_hp = 2100;}
		else
		{	$npc_hp = 10;}

		if(($_SESSION['quest1_bad_portal']==1))
		{	$npc_max_hp = 2100;}
		else
		{	$npc_max_hp = 1000;}
		$npc_mp = 1;
		$npc_max_mp = 1;
		$npc_str = 0;
		$npc_dex = 9;
		$npc_wis = 2;
		$npc_basefit = 5;
		$npc_basedef = 0;
		$npc_exp = 15;
		$npc_gold = 0;
		$npc_map_name = 0;
		$npc_xpos = 0;
		$npc_ypos = 0;
		$npc_time = 0;
		$npc_ntl = -5;
		$npc_level = $char['clevel'];
		$npc_item = '�����';
	}
	if ($_SESSION['quest1_step']==24)
	{
		$npc_img = 'blackknight';
		$npc_name = '������ � ��������';
		$npc_race = '�������';
		$npc_hp = round(0.9*$char['HP_MAX']);
		$npc_max_hp = round(0.9*$char['HP_MAX']);
		$npc_mp = round(0.7*$char['MP_MAX']);
		$npc_max_mp =round(0.7*$char['MP_MAX']);
		$npc_str = $char['STR']+2;
		$npc_dex = $char['DEX']-2;
		$npc_wis = $char['PIE']-3;
		$npc_basefit = $char['VIT'];
		$npc_basedef = $char['SPD'];
		$npc_exp = 6;
		$npc_gold = 0;
		$npc_map_name = 0;
		$npc_xpos = 0;
		$npc_ypos = 0;
		$npc_time = 0;
		$npc_ntl = $char['NTL'];
		$npc_level = $char['clevel'];
		$npc_item = '��������';
	}
	if ($_SESSION['quest1_step']==27)
	{
		$npc_img = 'ettim';
		$npc_name = '��� ���������';
		$npc_race = '�������';
		$npc_hp = $char['HP_MAX'];
		$npc_max_hp = $char['HP_MAX'];
		$npc_mp = round(1*$char['MP_MAX']);
		$npc_max_mp = round(1*$char['MP_MAX']);
		$npc_str = round(1*$char['STR']);
		$npc_dex = round(1*$char['DEX']);
		$npc_wis =round(1*$char['PIE']);
		$npc_basefit = round(1*$char['VIT']);
		$npc_basedef = round(1*$char['SPD']);
		$npc_exp = 8;
		$npc_gold = 0;
		$npc_map_name = 0;
		$npc_xpos = 0;
		$npc_ypos = 0;
		$npc_time = 0;
		$npc_ntl = $char['NTL'];
		$npc_level = $char['clevel'];
		$npc_item = '��������';
	}
	if ($_SESSION['quest1_step']==30)
	{
		$npc_img = 'ettim';
		$npc_name = '5 �����������';
		$npc_race = '�������';
		$npc_hp = round(1.5*$char['HP_MAX']);
		$npc_max_hp = round(1.5*$char['HP_MAX']);
		$npc_mp = round(1.5*$char['MP_MAX']);
		$npc_max_mp =round(1.5*$char['MP_MAX']);
		$npc_str = round(1.5*$char['STR']);
		$npc_dex = round(1.5*$char['DEX']);
		$npc_wis = round(1.5*$char['PIE']);
		$npc_basefit = round(1.5*$char['VIT']);
		$npc_basedef = round(1.5*$char['SPD']);
		$npc_exp = 10;
		$npc_gold = 0;
		$npc_map_name = 0;
		$npc_xpos = 0;
		$npc_ypos = 0;
		$npc_time = 0;
		$npc_ntl = round(1.5*$char['NTL']);
		$npc_level = $char['clevel'];
		$npc_item = '������';
	}
	if (
			$_SESSION['quest1_step']==32 or
			$_SESSION['quest1_step']==34 or
			$_SESSION['quest1_step']==36 or
			$_SESSION['quest1_step']==38 or
			$_SESSION['quest1_step']==40 or
			$_SESSION['quest1_step']==42 or
			$_SESSION['quest1_step']==44
		)
	{
		$npc_img = 'blackknight';
		$npc_name = '���������';
		$npc_race = '�������';
		$npc_hp = $char['HP_MAX'];
		$npc_max_hp = $char['HP_MAX'];
		$npc_mp = $char['MP_MAX'];
		$npc_max_mp = $char['MP_MAX'];
		$npc_str = $char['STR'];
		$npc_dex = $char['DEX'];
		$npc_wis = $char['PIE'];
		$npc_basefit = $char['VIT'];
		$npc_basedef = $char['SPD'];
		$npc_exp = 2;
		$npc_gold = 0;
		$npc_map_name = 0;
		$npc_xpos = 0;
		$npc_ypos = 0;
		$npc_time = 0;
		$npc_ntl = $char['NTL'];
		$npc_level =$char['clevel'];
		$npc_item = '�����';
	}
	if ($_SESSION['quest1_step']==44) $npc_item = '�������';

	$ins = myquery("INSERT INTO game_npc_template
	(npc_name, npc_race, npc_img, npc_max_hp, npc_max_mp, npc_str, npc_dex, npc_pie, npc_vit, npc_spd, npc_exp_max, npc_gold, npc_opis, npc_ntl, npc_level, respawn, item, to_delete)
	VALUES
	('$npc_name', '$npc_race', '$npc_img', '$npc_max_hp', '$npc_max_mp', '$npc_str', '$npc_dex', '$npc_wis', '$npc_basefit', '$npc_basedef', '$npc_exp', '$npc_gold', '', '$npc_ntl', '$npc_level', '0', '$npc_item', '1')");
	$npc_id = mysql_insert_id();
	
	$ins = myquery("INSERT INTO game_npc
	(npc_id, HP, MP, EXP, map_name, xpos, ypos, time_kill, stay, view, prizrak, npc_quest_id)
	VALUES
	('$npc_id', '$npc_hp', '$npc_mp', '$npc_exp', '$npc_map_name', '$npc_xpos', '$npc_ypos', '$npc_time', '0', '0', '1', '1')");
	$id = mysql_insert_id();
	attack_npc($char,$id,0);
}

function unset_all()
{
	foreach ($_SESSION AS $key=>$value)
	{
		if (substr($key,0,6)=='quest1')
		{
			unset($_SESSION[$key]);
		}
	}
}

$sel_sost = myquery("SELECT sost,finish FROM game_quest_users WHERE user_id=$user_id AND quest_id=1");
if ($sel_sost!=false AND mysql_num_rows($sel_sost)>0)
{
	$sst = mysql_fetch_array($sel_sost);
	$sost = $sst['sost'];
	if ($sost==99 AND $sst['finish']!=1)
	{
		$sst['finish'] = 1;
		myquery("UPDATE game_quest_users SET finish=1 WHERE user_id=$user_id AND quest_id=1");
	}
	if ($sst['finish']>=1)
	{
		ForceFunc($user_id,5);
		setLocation("../act.php");
	}
}

$new_clevel=get_new_level($char['clevel']);
$get_exp = floor(0.2*$new_clevel);

$last_time = time()-10*60;
if (isset($_SESSION['quest1_exit']) and $_SESSION['quest1_exit']>0)
{
	$sost=0;
	$finish=0;
	set_delay_reason_id($user_id,1);
	if ($_SESSION['quest1_exit']==99)
	{
		myquery("UPDATE game_users SET EXP=EXP+$get_exp,GP=GP+'".$_SESSION['quest1_get_gp']."',CW=CW+'".($_SESSION['quest1_get_gp']*money_weight)."' WHERE user_id=$user_id");
		setGP($user_id,$_SESSION['quest1_get_gp'],59);
		setEXP($user_id,$get_exp,6);
		if ($_SESSION['quest1_take_weapon']==1){add_item_to_user(4);}
		if ($_SESSION['quest1_take_shlem']==1) {add_item_to_user(8);}
		$sost=99;
		$finish=1;
	}
	if ($_SESSION['quest1_exit']==50)
	{
		$_SESSION['quest1_get_gp']=90;
		myquery("UPDATE game_users SET HP=1,MP=1,STM=1 WHERE user_id=$user_id");
		myquery("UPDATE game_users SET EXP=EXP+$get_exp,GP=GP+".$_SESSION['quest1_get_gp'].",CW=CW+".($_SESSION['quest1_get_gp']*money_weight)." WHERE user_id=$user_id");
		setGP($user_id,$_SESSION['quest1_get_gp'],59);
		setEXP($user_id,$get_exp,6);
		add_item_to_user(8);
		$sost=99;
		$finish=1;
	}
	if ($_SESSION['quest1_exit']==1)
	{
		myquery("UPDATE game_users SET HP=1,MP=1,STM=1 WHERE user_id=$user_id");
	}
	unset_all();
	myquery("INSERT INTO game_quest_users (user_id,quest_id,last_time,sost,finish) VALUES ($user_id,1,".time().",$sost,1) ON DUPLICATE KEY UPDATE sost=$sost,last_time=".time().",finish=GREATEST($finish,finish)");
	ForceFunc($user_id,5);
	setLocation("../act.php");
}
if (isset($return))
{
	$_SESSION['quest1_step'] = 0;
	$_SESSION['quest1_state']='0';
	$_SESSION['quest1_search_ohr']=0;
	$_SESSION['quest1_have_key']=0;
	$_SESSION['quest1_need_key']=0;
	$_SESSION['quest1_search_room']=0;
	$_SESSION['quest1_count_money']=0;
	$_SESSION['quest1_bad_portal']=0;
	$_SESSION['quest1_take_shlem']=0;
	$_SESSION['quest1_take_weapon']=0;
	$_SESSION['quest1_ves_plita']=0;
	$_SESSION['quest1_get_gp']=0;
	$_SESSION['quest1_searh_trup']=0;
	$_SESSION['quest1_money_girl']=0;
	$_SESSION['quest1_wind_move']=0;
	$_SESSION['quest1_wind_might']=0;
	$state='0';
}
if (isset($_GET['begin']))
{
	unset_all();
}

echo '<title>���������� :: ����� �������� :: ������� on-line ����</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<meta name="Keywords" content="������� ������� ������ ���� ���������� ����� �������� online game items �������� �������� ��� ������� rpg ����� ����� �� �������"><style type="text/css">@import url("../style/global.css");</style>';


if (isset($_SESSION['quest1_lose']) OR isset($exit_from_quest))
{
	echo '<BR><BR><BR>';
	QuoteTable('open');
	echo '<font size=3 color=#FF0000>�� ��������� �����. �� ������ ����������� ������ ���� ����� ��� ��� ����� 5 �����';
	QuoteTable('close');
	QuoteTable('open');
	$_SESSION['quest1_exit']=2;
	echo'<a href="?exit">����� ��������</a><br>';
	QuoteTable('close');
	unset($_SESSION['quest1_lose']);
	myquery("INSERT INTO game_quest_users (user_id,quest_id,last_time) VALUES ($user_id,1,".time().") ON DUPLICATE KEY UPDATE last_time=".time()."");
	if (isset($exit_from_quest)) exit;
}

$questsel = myquery("SELECT * FROM game_quest WHERE map_name=".$char['map_name']." AND map_xpos=".$char['map_xpos']." AND map_ypos=".$char['map_ypos']." AND min_clevel<=".$char['clevel']." AND max_clevel>=".$char['clevel']." AND id=1");
if (mysql_num_rows($questsel))
{
	$quest = mysql_fetch_array($questsel);
	$check = mysql_result(myquery("SELECT COUNT(*) FROM game_quest_users WHERE user_id=$user_id AND quest_id=1 AND (last_time>=$last_time OR sost>0)"),0,0);
	if ($check==0)
	{
		//myquery("INSERT INTO game_quest_users (user_id,quest_id,last_time) VALUES ($user_id,1,".time().") ON DUPLICATE KEY UPDATE last_time=".time()."");

	set_delay_reason_id($user_id,13);
		OpenTable('title');
		echo '<p align=left>';
		if (!isset($_SESSION['quest1_state'])) $_SESSION['quest1_state']='0';
		if (!isset($_SESSION['quest1_step'])) $_SESSION['quest1_step']=0;
		if (!isset($state)) $state='0';

		if ($_SESSION['quest1_step']==1 and substr($state,0,1)=='0') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==2 and substr($state,0,1)=='1') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==3 and substr($state,0,2)=='12') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==4 and substr($state,0,3)=='123') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==5 and substr($state,0,4)=='1232') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==6 and substr($state,0,5)=='12321') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==7 and isset($boy))
		{
			attack_npc_quest();
			exit;
		}
		if ($_SESSION['quest1_step']==8 and isset($win_bot)) {$_SESSION['quest1_state'] = '1232120'; myquery("UPDATE game_users SET HP=HP_MAX,MP=MP_MAX,STM=STM_MAX WHERE user_id=$user_id");};
		if ($_SESSION['quest1_step']==9 and substr($state,0,6)=='123212') $_SESSION['quest1_state'] = $state;
		//if ($_SESSION['quest1_step']==10 and substr($state,0,7)=='1232121') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==10 and substr($state,0,6)=='123212') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==11 and substr($state,0,7)=='1232125') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==12 and substr($state,0,10)=='1232125202') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==13 and substr($state,0,11)=='12321252023') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==14 and isset($boy))
		{
			attack_npc_quest();
			exit;
		}
		if ($_SESSION['quest1_step']==15 and isset($win_bot))
		{
			$_SESSION['quest1_state'] = '777';
			myquery("UPDATE game_users SET HP=HP_MAX,MP=MP_MAX,STM=STM_MAX WHERE user_id=$user_id");
		}
		if ($_SESSION['quest1_step']==16 and substr($state,0,7)=='1232125') $_SESSION['quest1_state'] = $state;
		/*if ($_SESSION['quest1_step']==17 and isset($win_bot)) {$_SESSION['quest1_state'] = '999999999';myquery("UPDATE game_users SET HP=HP_MAX,MP=MP_MAX,STM=STM_MAX WHERE user_id='$user_id'");};*/
		if ($_SESSION['quest1_step']==19 and substr($state,0,5)=='12323') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==20 and substr($state,0,6)=='123233') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==21 and substr($state,0,7)=='1232332') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==9 and $state=='123241') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==22 and substr($state,0,2)=='13') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==23 and substr($state,0,1)=='3') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==24 and substr($state,0,2)=='31') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==24 and isset($boy))
		{
			attack_npc_quest();
			exit;
		}
		if ($_SESSION['quest1_step']==25 and isset($win_bot)) {$_SESSION['quest1_state'] = '320';myquery("UPDATE game_users SET HP=HP_MAX,MP=MP_MAX,STM=STM_MAX WHERE user_id=$user_id");};
		if ($_SESSION['quest1_step']==26 and substr($state,0,2)=='32') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==27 and substr($state,0,3)=='321') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==27 and isset($boy))
		{
			attack_npc_quest();
			exit;
		}
		if ($_SESSION['quest1_step']==28 and isset($win_bot)) {$_SESSION['quest1_state'] = '32120';myquery("UPDATE game_users SET HP=HP_MAX,MP=MP_MAX,STM=STM_MAX WHERE user_id=$user_id");};
		if ($_SESSION['quest1_step']==29 and substr($state,0,4)=='3212') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==30 and substr($state,0,5)=='32122') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==30 and isset($boy))
		{
			attack_npc_quest();
			exit;
		}
		if ($_SESSION['quest1_step']==31 and isset($win_bot)) {$_SESSION['quest1_state'] = '3212210';myquery("UPDATE game_users SET HP=HP_MAX,MP=MP_MAX,STM=STM_MAX WHERE user_id=$user_id");};
		if ($_SESSION['quest1_step']==32 and isset($boy))
		{
			attack_npc_quest();
			exit;
		}
		if ($_SESSION['quest1_step']==33 and isset($win_bot)) {$_SESSION['quest1_state'] = '1232120';myquery("UPDATE game_users SET HP=HP_MAX,MP=MP_MAX,STM=STM_MAX WHERE user_id=$user_id");};
		if ($_SESSION['quest1_step']==34 and isset($boy))
		{
			attack_npc_quest();
			exit;
		}
		if ($_SESSION['quest1_step']==35 and isset($win_bot)) {$_SESSION['quest1_state'] = '3212222';myquery("UPDATE game_users SET HP=HP_MAX,MP=MP_MAX,STM=STM_MAX WHERE user_id=$user_id");};
		if ($_SESSION['quest1_step']==36 and isset($boy))
		{
			attack_npc_quest();
			exit;
		}
		if ($_SESSION['quest1_step']==37 and isset($win_bot)) {$_SESSION['quest1_state'] = '3212223';myquery("UPDATE game_users SET HP=HP_MAX,MP=MP_MAX,STM=STM_MAX WHERE user_id=$user_id");};
		if ($_SESSION['quest1_step']==38 and isset($boy))
		{
			attack_npc_quest();
		}
		if ($_SESSION['quest1_step']==39 and isset($win_bot)) {$_SESSION['quest1_state'] = '3212224';myquery("UPDATE game_users SET HP=HP_MAX,MP=MP_MAX,STM=STM_MAX WHERE user_id=$user_id");};
		if ($_SESSION['quest1_step']==40 and isset($boy))
		{
			attack_npc_quest();
			exit;
		}
		if ($_SESSION['quest1_step']==41 and isset($win_bot)) {$_SESSION['quest1_state'] = '3212225';myquery("UPDATE game_users SET HP=HP_MAX,MP=MP_MAX,STM=STM_MAX WHERE user_id=$user_id");};
		if ($_SESSION['quest1_step']==42 and isset($boy))
		{
			attack_npc_quest();
			exit;
		}
		if ($_SESSION['quest1_step']==43 and isset($win_bot)) {$_SESSION['quest1_state'] = '3212226';myquery("UPDATE game_users SET HP=HP_MAX,MP=MP_MAX,STM=STM_MAX WHERE user_id=$user_id");};
		if ($_SESSION['quest1_step']==44 and isset($boy))
		{
			attack_npc_quest();
			exit;
		}
		if ($_SESSION['quest1_step']==45 and isset($win_bot)) {$_SESSION['quest1_state'] = '3212227';myquery("UPDATE game_users SET HP=HP_MAX,MP=MP_MAX,STM=STM_MAX WHERE user_id=$user_id");};
		if ($_SESSION['quest1_step']==46 and substr($state,0,1)=='4') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==47 and substr($state,0,2)=='43') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==48 and substr($state,0,3)=='430') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==49 and substr($state,0,4)=='4302') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==50 and substr($state,0,5)=='43020') $_SESSION['quest1_state'] = $state;
		if ($_SESSION['quest1_step']==51 and isset($boy))
		{
			attack_npc_quest();
			exit;
		}
		if ($_SESSION['quest1_step']==52 and isset($win_bot)) {$_SESSION['quest1_state'] = '1232120';myquery("UPDATE game_users SET HP=HP_MAX,MP=MP_MAX,STM=STM_MAX WHERE user_id=$user_id");};

		switch ($_SESSION['quest1_state'])
		{
			case '0':
			{
				$_SESSION['quest1_search_ohr']=0;
				$_SESSION['quest1_have_key']=0;
				$_SESSION['quest1_step']=1;
				$_SESSION['quest1_need_key']=0;
				$_SESSION['quest1_search_room']=0;
				$_SESSION['quest1_count_money']=0;
				$_SESSION['quest1_bad_portal']=0;
				$_SESSION['quest1_take_shlem']=0;
				$_SESSION['quest1_take_weapon']=0;
				$_SESSION['quest1_ves_plita']=0;
				$_SESSION['quest1_get_gp']=0;
				$_SESSION['quest1_searh_trup']=0;
				$_SESSION['quest1_money_girl']=0;
				$_SESSION['quest1_wind_move']=0;
				$_SESSION['quest1_wind_might']=0;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>�������� �� ����, �� ������ ��� ���� ����� ����������� ������ ����� �� ����������� � ������ ������� �������, ������ �������� ������. ��� �������� ���������� �� �����������, ����, �������, ������ �� �������, �, �������, ���-�� ������, �� ���� �� ��������� �� ������. ���� ��������:';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=01">� ������ &quot;��, �� ��� �������?!&quot; �������� � ����� ��������.</a><br>
				2) <a href="?state=02">��������, �������� ���, � ������ &quot;����� �� �������, ���������� ���!&quot;</a><br>
				3) <a href="?state=03">����� ������� ���</a><br>
				4) <a href="?state=04">���������� � ����������� �� �������� ���������</a><br>
				5) <a href="?state=05">������ ������� � ���������� ����</a>';
				QuoteTable('close');
				break;
			};
			case '01':
			{
				$_SESSION['quest1_step']=2;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>�� ��������� ���� ��������. �������, ��� ��� ����, ���� �������, ��������, ��������. ������ ������� ����� ��������� ������� � ��������� ����� �� ������, � ���������, ����������� ������� � ��������� ��������, ������ �� ����� ��������. �� ���������, ��� ���� �� ��� ������� �� �� ����� ������.<br><B>- �� ��� ���?</B> - �������� ������������ ���������� ���������.';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=11">*��������� ���*  - ���, � ���� ��������� ������ ������ �� ��� ������ ������!</a><br>
				2) <a href="?state=12">������������� � ��������, ��� ���, � ��� ������ ����������.</a><br>
				3) <a href="?state=13">� ����� �������! ���������� ��� ���������� ��������� � ���������, ��� ��� ��������!</a><br>
				4) <a href="?state=14">�-�-�� �� � ���, ���� ��������.. �����, ���� ���! *����*</a>';
				QuoteTable('close');
				break;
			}
			case '11':
			{
				$_SESSION['quest1_step']=0;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>�� ��������� ������� � ����� ���� �� ���������� ������ ��������� ����������, �� ��� ��������� ��� ��������� ������. ��������� ���, � ���������, ��� �� ������� - ������� ���� �� ����������� �� ��������, ������ ����� ��� �� ����..';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo '<font size=3 color=#FF0000>�� ��������� �����. �� ������ ����������� ������ ���� ����� ��� ��� ����� ��������� �����';
				QuoteTable('close');
				QuoteTable('open');
				$_SESSION['quest1_exit']=2;
				echo'<a href="?exit">����� ��������</a><br>';
				QuoteTable('close');
				break;
			}
			case '12':
			{
				$_SESSION['quest1_step']=3;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>- <B>����������� ����� ���������� ����!</B> - ������� ������ ��������� � ������ � ��� ����� ����� �����-�� ��������� ������. - <B>���</B>, - ��� �� ������ �� �������, - <B>�������� ������. � ������ ����� ����� ������� � �� ������� ���������� ��������!</B>';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=121">��, ��������, ����������, �������, � ��������.</a><br>
				2) <a href="?state=122">���?! �� ��� �������� ������������ ������! ������ �����������!</a><br>
				3) <a href="?state=123">������, �� �������! *������� ����� ��� ������� �� ������� �����*</a><br>';
				QuoteTable('close');
				break;
			}
			case '121':
			{
				$_SESSION['quest1_step']=0;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>�� ����� ����� �����, � ���� ������ � �������� - �����.';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo '<font size=3 color=#FF0000>�� ��������� �����. �� ������ ����������� ������ ���� ����� ��� ��� ����� ��������� �����';
				QuoteTable('close');
				QuoteTable('open');
				$_SESSION['quest1_exit']=2;
				echo'<a href="?exit">����� ��������</a><br>';
				QuoteTable('close');
				break;
			}
			case '122':
			{
				$_SESSION['quest1_step']=0;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>�� ��������� ������� � ����� ���� �� ���������� ������ ��������� ����������, �� ��� ��������� ��� ��������� ������. ��������� ���, � ���������, ��� �� ������� - ������� ���� �� ����������� �� ��������, ������ ����� ��� �� ����..';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo '<font size=3 color=#FF0000>�� ��������� �����. �� ������ ����������� ������ ���� ����� ��� ��� ����� ��������� �����';
				QuoteTable('close');
				QuoteTable('open');
				$_SESSION['quest1_exit']=2;
				echo'<a href="?exit">����� ��������</a><br>';
				QuoteTable('close');
				break;
			}
			case '123':
			{
				$_SESSION['quest1_step']=4;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>- <B>�������?</B> - ���������� ��������� - <B>����� ��������� �������� ��� ���� ����.</B> - �� ����� ��� �� ����� � �������������; ��� �� ���������� � ���� �� ������ �����-�� ���������, � � ��������� ������ - ������� ���� �� �������. � ������ �������<br><br>���������, �� ������������� ���� �� �������� ������ ��������� ������, ������� ��������� ����� ��� �� ��� �����. �� ������ � ���, ������ ��������, �� � ����� ��� ��������� ���� ����. ������, ��� ��� �� ����� ����� ������������ �������!
';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=1231">����������� �����.</a><br>
				2) <a href="?state=1232">�������� �������� ����������</a><br>
				3) <a href="?state=1233">���������� ������� �� �����, ����� ������� � ���������.</a><br>';
				QuoteTable('close');
				break;
			}
			case '1231':
			{
				$_SESSION['quest1_step']=4;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>�� �������� ������ ��������� ����� �������. ����� ��� �����, ������ ����������. ����, ������� ���� �������� ������ ����������.';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=1231">����������� �����.</a><br>
				2) <a href="?state=1232">�������� �������� ����������</a><br>
				3) <a href="?state=1233">���������� ������� �� �����, ����� ������� � ���������.</a><br>';
				QuoteTable('close');
				break;
			}
			case '1232':
			{
				$_SESSION['quest1_step']=5;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>�� ���� ������� ����� � ���� �� ������� ������� ������ ������� �������� � �������� �� �����. ���� ��� �������� ������� ������� ������������. ';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=12321">��������� ������.</a><br>
				2) <a href="?state=12322">���� ���������� � �������: &quot;������, � ������ ����� �������������!&quot;</a><br>
				3) <a href="?state=12323">���������� ����� �� ���� ������������.</a><br>
				4) <a href="?state=12324">������������ ��������� �������.</a><br>
				5) <a href="?state=12325">�������: &quot;��, ��, ������ �����, �� �� ���� ������, ��� �?! ���� �� ��������� ����, �� ����� 15 ����� ��� ����� ����� ����� ���� � ����������� �� ����� �����������, ������� �������� ���� ���� �������� �� ����������!!!&quot;</a><br>';
				QuoteTable('close');
				break;
			}
			case '12321':
			{
				$_SESSION['quest1_step']=6;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>��������� ���-�� ����������������� � �������� ��������, �������� ����. ����� ����� ������ �� ��������, ����� � ����� ������� � ���-�� ���������� ������, ���������� �� ������� ���������. �������� ��������� ��������� ������� ����� ��������, �� �� ���������� �� ������ ���������. ����� �� �������� ������� �� ���, � ������ ���� ���� ��������� ��� �, �� ����� � ��� �������������� �������, ����� �����..';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=123211">������ �����, ���� �� �������� � ��� ������� � ������� �����, � ����� ������� �������������� ������.</a><br>
				2) <a href="?state=123212">����������� �� ���������</a><br>';
				QuoteTable('close');
				break;
			}
			case '123211':
			{
				$_SESSION['quest1_step']=5;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>��, ������� ������ ������� ���� �� � �����.. ������� �������� � ������� ������, � ���� ��������, �� ������� ���������.';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=12321">��������� ������.</a><br>
				2) <a href="?state=12322">���� ���������� � �������: &quot;������, � ������ ����� �������������!&quot;</a><br>
				3) <a href="?state=12323">���������� ����� �� ���� ������������.</a><br>
				4) <a href="?state=12324">������������ ��������� �������.</a><br>
				5) <a href="?state=12325">�������: &quot;��, ��, ������ �����, �� �� ���� ������, ��� �?! ���� �� ��������� ����, �� ����� 15 ����� ��� ����� ����� ����� ���� � ����������� �� ����� �����������, ������� �������� ���� ���� �������� �� ����������!!!&quot;</a><br>';
				QuoteTable('close');
				break;
			}
			case '123212':
			{
				$_SESSION['quest1_step']=7;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>� ��� ������, ����� ��� ����� ���������� �� ��������, �� �������� �� ����. �� �� ��� ���������, ��� ��� - ���!';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				//$_SESSION['quest1_exit']=2;
				echo'<a href="?boy">������ ��� � ����������</a><br>';
				QuoteTable('close');
				break;
			}
			case '1232120':
			{
				$_SESSION['quest1_step']=9;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>��� �������-���� �������� ���������! �� ������ ��� ��� ����������� �����. ��� ������?';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=1232121">����������� ��� ������.</a><br>
				2) <a href="?state=1232122">����� � ����� �����.</a><br>
				3) <a href="?state=1232123">�������� ����. </a><br>
				4) <a href="?state=1232124">����� ���.</a><br>
				5) <a href="?state=1232125">������.</a><br>';
				QuoteTable('close');
				break;
			}
			case '1232121':
			{
				$_SESSION['quest1_step']=10;
				$_SESSION['quest1_bad_portal']=1;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>�� *�������� ��������*, ����� * �������� ��������*, ��, ���������� *�������� ��������*, ������ ������ *�������� ��������*, ��� � �������.';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				$i=0;
				if ($_SESSION['quest1_have_key']==1){} else {$i++;echo $i.') <a href="?state=1232122">����� � ����� �����.</a><br>';}
				if ($_SESSION['quest1_search_ohr']==1){} else {$i++;echo $i.') <a href="?state=1232123">�������� ����.</a><br>';}
				if ($_SESSION['quest1_take_weapon']==1){} else {$i++;echo $i.') <a href="?state=1232124">����� ���.</a><br>';}
				$i++;
				echo $i.') <a href="?state=1232125">������.</a><br>';
				QuoteTable('close');
				break;
			}
		   /* case '12321211':
			{
				$_SESSION['quest1_step']=9;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>������� ��������� ����� � ���� �������� ������ � ������������� �� �����, �� ����� 6 �����. ';
				//myquery("UPDATE game_users SET GP=GP+6,CW=CW+'".(6*money_weight)."' WHERE user_id='$user_id'");
				$_SESSION['quest1_get_gp']+=6;
				$_SESSION['quest1_search_ohr']=1;
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				$i=0;
				if ($_SESSION['quest1_have_key']==1){} else {$i++;echo $i.') <a href="?state=1232122">����� � ����� �����.</a><br>';}
				if ($_SESSION['quest1_take_weapon']==1){} else {$i++;echo $i.') <a href="?state=1232124">����� ���.</a><br>';}
				$i++;
				echo $i.') <a href="?state=1232125">������.</a><br>';
				QuoteTable('close');
				break;
			}*/
			case '1232122':
			{
				$_SESSION['quest1_step']=9;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>�� ����� � ����� ����� ������� ������ ������ ����� ������ ���� � ��������. ';
				$_SESSION['quest1_have_key']=1;
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				$i=0;
				if ($_SESSION['quest1_bad_portal']==1/*$_SESSION['quest1_searh_trup']==1 OR $_SESSION['quest1_search_ohr']==1*/){} else {$i++;echo $i.') <a href="?state=1232121">����������� ��� ������.</a><br>';}
				if ($_SESSION['quest1_search_ohr']==1){} else {$i++;echo $i.') <a href="?state=1232123">�������� ����.</a><br>';}
				if ($_SESSION['quest1_take_weapon']==1){} else {$i++;echo $i.') <a href="?state=1232124">����� ���.</a><br>';}
				$i++;
				echo $i.') <a href="?state=1232125">������.</a><br>';
				QuoteTable('close');
				break;
			}
			case '1232123':
			{
				$_SESSION['quest1_step']=9;
				if($_SESSION['quest1_bad_portal']==0)
				{
					 QuoteTable('open');
					 echo '<font size=3 color=#F0F0F0>������� ��������� ����� �� �������� ��������� �� ����� 23 ������.';
					 $_SESSION['quest1_get_gp']+=23;
					 $_SESSION['quest1_search_ohr']=1;
					 QuoteTable('close');
				}
				else
				{
					QuoteTable('open');
					echo '<font size=3 color=#F0F0F0>������� ��������� ����� � ���� �������� ������ � ������������� �� �����, �� ����� 6 �����. ';
					$_SESSION['quest1_get_gp']+=6;
					$_SESSION['quest1_search_ohr']=1;
					$_SESSION['quest1_searh_trup']=1;
					QuoteTable('close');
				}
				echo '<br><br><br>';
				QuoteTable('open');
				$i=0;
				if ($_SESSION['quest1_bad_portal']==1/*$_SESSION['quest1_searh_trup']==1 OR $_SESSION['quest1_search_ohr']==1*/){} else {$i++;echo $i.') <a href="?state=1232121">����������� ��� ������.</a><br>';}
				if ($_SESSION['quest1_have_key']==1){} else {$i++;echo $i.') <a href="?state=1232122">����� � ����� �����.</a><br>';}
				if ($_SESSION['quest1_take_weapon']==1){} else {$i++;echo $i.') <a href="?state=1232124">����� ���.</a><br>';}
				$i++;
				echo $i.') <a href="?state=1232125">������.</a><br>';
				QuoteTable('close');
				break;
			}
			case '1232124':
			{
				$_SESSION['quest1_step']=9;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>�� ��������� �������� �� ������� ��� ����������� ���. ��� �, ��������� ������.';
				//add_item_to_user(4);
				$_SESSION['quest1_take_weapon']=1;
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				$i=0;
				if ($_SESSION['quest1_bad_portal']==1/*$_SESSION['quest1_searh_trup']==1 OR $_SESSION['quest1_search_ohr']==1*/){} else {$i++;echo $i.') <a href="?state=1232121">����������� ��� ������.</a><br>';}
				if ($_SESSION['quest1_have_key']==1){} else {$i++;echo $i.') <a href="?state=1232122">����� � ����� �����.</a><br>';}
				if ($_SESSION['quest1_search_ohr']==1){} else {$i++;echo $i.') <a href="?state=1232123">�������� ����.</a><br>';}
				$i++;
				echo $i.') <a href="?state=1232125">������.</a><br>';
				QuoteTable('close');
				break;
			}
			case '1232125':
			{
				$_SESSION['quest1_step']=11;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>�� �������� �� ������������ ���� ��������� � ����� �������, ��� ���-�� �������� ��� �� �����, ����� ������� �� �������� ������. �� ������������ ����, � ������������� �� ����� �������, ������� ������ ��������� � ���� ��� �����������. ��� ���������� � ���.<br>
		- <B>����������, �������� ����! ��� ������� ������ ���� ��� ��� ��������� � ����� �����, ���� �� ���� �� �������� �����! ���� �� ���� ���������, � ����� ���������� ���� � ������ ��������� ������! � ���� ���� ���� �����?</B>
';
				QuoteTable('close');
				echo '<br><br><br>';
				$_SESSION['quest1_money_girl']=200;
				QuoteTable('open');
				if ($_SESSION['quest1_have_key']==1)
				{
					 echo'
					1) <a href="?state=12321252">��, �������, ������ � �������� ���.</a><br>
					2) <a href="?state=12321253">��, ����, �� ������ �������� �� ��������, ��� �� �������������� ��� ������ &quot;�����&quot;?</a><br>
					3) <a href="?state=12321252024">�� ���, ����, � ���� ���� ����� �������! *����� ��  ���������*</a><br>';
				}
				else
				{
					echo'<a href="?state=12321251">�������! *����� � ����� �����*</a><br>';
				}
				QuoteTable('close');
				break;
			}
			case '12321251':
			{
				$_SESSION['quest1_step']=11;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>�� ��������� � ���� ��������� � ����� � ��� ����� ������� ������ ������ ����� ������ ���� � ��������.<br>
		- <B>������ ������, ����������, �������!</B> - ���������� �������.';
				$_SESSION['quest1_have_key']=1;
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=12321252">��, �������, ������ � �������� ���.</a><br>
				2) <a href="?state=12321253">��, ����, �� ������ �������� �� ��������, ��� �� �������������� ��� ������ &quot;�����&quot;?</a><br>
				3) <a href="?state=12321252024">�� ���, ����, � ���� ���� ����� �������! *����� ��  ���������*</a><br>';
				QuoteTable('close');
				break;
			}
			case '12321252':
			{
				if (!isset($key)) $key=0;
				if ($key==0)
				{
					$_SESSION['quest1_step']=11;
					$_SESSION['quest1_need_key']=mt_rand(5,18);
					QuoteTable('open');
					echo '<font size=3 color=#F0F0F0>�� �������� �� ��������, ����� ����������� ����� � ���������, ��� � ���� ��� ��� �������� � �����. �������� ������.';
					QuoteTable('close');
				}
				else
				{
					if($key == $_SESSION['quest1_need_key'])
					{
						QuoteTable('open');
						echo '<font size=3 color=#F0F0F0>�� ���������� ���� � �������� �, �������� �������� �������, ��������� ��� ���������. ����! ��������� ��������� ������, � ���� ����� ��������������� �� ��� �������, ����� ������! �� ���������� ������� � �������� ������� �� ������. �� ���� ������� ��������� �� ����������� �������� ���������, ������� � ������� ����� ������� �� ���������. ����� � ������ ������ ������ �� ���, � ������������� ������� �� ������� ���������� �������� ��������.<br>
		 <B>- ������� ����, '.$char['name'].'! �� ���� �� �������������, ��� ����� ������ ������ ��� ����������! � ������������� � ������ ���� ��������� ������</B>, - ��� ������� ���������� ������, � � �������� �� ������� ��������� ������������ ����� �����, ������������� � ��������������� ����� �������; ���� ��� ������ � ������, � ���.. � ����� �������������� ������ �������� � �������� ������� ��������! �������, ���� ������� ������� �� ������� ���� �� ������ �����. �� ������ ������������ ���, � ���������� ���������, ��������� ������������� ������������.<br>
		<B>- ����� ����</B>, - ���������� ���, - <B>��� ������, ������� � �������</B>, - ��� ����������� ��� ������� ������������� �������, - <B>��� ����� ���, ��� � ���� ����. ��� ��� �������, � ������� �� ������, ��� �� ��� ���� ������!</B> - ��� ��� �� ��������� ��������� ����� � ���������, ��������� ��������. - <B>�� � ����, ���, ��������, �� ��������� ���� ��������</B> <br>
� ����� - �� ��������� �� ���������� &quot;����������&quot; ��� ���� �� �������� &quot;��� ��?&quot;, ������� �������� � ����� ����� ������! �� � �����';
						//myquery("UPDATE game_users SET GP=GP+200,CW=CW+'".(200*money_weight)."' WHERE user_id='$user_id'");
						QuoteTable('close');
					}
					else
					{
						QuoteTable('open');
						$phrase=mt_rand(1,8);
						switch ($phrase)
						{
							case 1: {echo '<font size=3 color=#F0F0F0>���� �� ��������������.';break;}
							case 2: {echo '<font size=3 color=#F0F0F0>����� �� ���������.';break;}
							case 3: {echo '<font size=3 color=#F0F0F0>�������, �� ��� ����.';break;}
							case 4: {echo '<font size=3 color=#F0F0F0>������, ����� ����������� ������.';break;}
							case 5: {echo '<font size=3 color=#F0F0F0>���� ���� �� ��������.';break;}
							case 6: {echo '<font size=3 color=#F0F0F0>���� ������ �� ������� ������.';break;}
							case 7: {echo '<font size=3 color=#F0F0F0>��� ����� �� ���� ����.';break;}
							case 8: {echo '<font size=3 color=#F0F0F0>����������. ����� ����������� ��� ����.';break;}
						};

						QuoteTable('close');
					}
				}
				echo '<br><br><br>';
				QuoteTable('open');
				if($key == $_SESSION['quest1_need_key'])
				{
			  $_SESSION['quest1_get_gp']+=$_SESSION['quest1_money_girl'];
					$_SESSION['quest1_step']=12;
					echo'
					1) <a href="?state=12321252021">����������� ������</a><br>
					2) <a href="?state=12321252022">�������� ������ �������</a><br>
					3) <a href="?state=12321252023">����� � ������</a><br>
					4) <a href="?state=12321252024">� ������ &quot;���� ������, �������!!!"&quot; ���������� ����� �� �������. </a><br>';
				}
				else
				{
				echo'
				1) <a href="?state=12321252&key=1">������ ������ ����</a><br>
				2) <a href="?state=12321252&key=2">������� �������� ����</a><br>
				3) <a href="?state=12321252&key=3">�������� ����������� ����</a><br>
				4) <a href="?state=12321252&key=4">������� �������� ����</a><br>
				5) <a href="?state=12321252&key=5">�����-�� ������������� ����</a><br>
				6) <a href="?state=12321252&key=6">��� ������ ����</a><br>
				7) <a href="?state=12321252&key=7">� ��� ������ ����</a><br>
				8) <a href="?state=12321252&key=8">����� ������ ����</a><br>
				9) <a href="?state=12321252&key=9">��� �������� ����</a><br>
				10) <a href="?state=12321252&key=10">��� ���� �������� ����</a><br>
				11) <a href="?state=12321252&key=11">��� �������� ����</a><br>
				12) <a href="?state=12321252&key=12">��� ���� �������� ����</a><br>
				13) <a href="?state=12321252&key=13">����� ����������� ����</a><br>
				14) <a href="?state=12321252&key=14">������� ������������� ����</a><br>
				15) <a href="?state=12321252&key=15">������ ������ ����</a><br>
				16) <a href="?state=12321252&key=16">� ��� �������� ����</a><br>
				17) <a href="?state=12321252&key=17">������������ ������ ����</a><br>
				18) <a href="?state=12321252&key=18">������� ����������� ����</a><br>
				19) <a href="?state=12321252&key=19">���� �� ����������� ���������</a><br>';
				}
				QuoteTable('close');
				break;
			}
			case '12321252021':
			{
				$_SESSION['quest1_step']=12;
				$_SESSION['quest1_count_money']=1;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>�� ���������� ����� � �������������� ������.';
				$user_time_quest=time()+60;
				echo '<font color="#ff0000">���������:</font> ��� <span id="pendule"></span>&nbsp;<font color="#ff0000">'.'
				<script type="text/javascript" language="JavaScript">
				var a='.abs($user_time_quest - time()).';
				text1="";
				function clock_status()
				{
					if (a<=9) text="&nbsp;"+a;
					if (a<=0) {text1="(������)";text="0";vybor.style.display="block";}
					else {text=a;vybor.style.display="none";};
					if (document.layers) 
					{
						document.layers.pendule.document.write(text);
						document.layers.pendule.document.close();
						document.layers.pend.document.write(text1);
						document.layers.pend.document.close();
					}
					else
					{
						pendule = document.getElementById("pendule");
						pendule.innerHTML = text;
						pend.innerHTML = text1;
						a=a-1;
						window.setTimeout("clock_status()",1000);
					}
				}
				clock_status();
				</script>
				'.' </font>���. <span id="pend"></span><br><br>';
				QuoteTable('close');
				echo '<br><br><br>';
				echo'<span id="vybor">';
				QuoteTable('open');
				echo'
					1) <a href="?state=12321252023">����� � ������</a><br>
					2) <a href="?state=12321252024">� ������ &quot;���� ������, �������!!!"&quot; ���������� ����� �� �������. </a><br>';
				if ($_SESSION['quest1_search_room']==1) {} else echo '3) <a href="?state=12321252022">�������� ������ �������</a><br>';
				QuoteTable('close');
				echo '</span>';
				break;
			}
			case '12321252022':
			{
				$_SESSION['quest1_step']=12;
				$_SESSION['quest1_search_room']=1;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>��������� ������� ������, � ������� ������� ��������� ���� ����������, �� �� ������������� ������, ����� �������, ������������� ������� ������ �������� �����. �������, ���� ������ ������ - ������ ����� ������! ';
				//add_item_to_user(8);
				$_SESSION['quest1_take_shlem']=1;
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo'
					1) <a href="?state=12321252023">����� � ������</a><br>
					2) <a href="?state=12321252024">� ������ &quot;���� ������, �������!!!"&quot; ���������� ����� �� �������. </a><br>';
				if ($_SESSION['quest1_count_money']==1) {} else echo '3) <a href="?state=12321252021">����������� ������</a><br>';
				QuoteTable('close');
				break;
			}
			case '12321252023':
			{
				$_SESSION['quest1_step']=13;
				/*QuoteTable('open');
				if ($_SESSION['quest1_bad_portal']==1)
				{
				echo '<font size=3 color=#F0F0F0>������ �� ���� ����������� � �������, ��� �������� �������� ��������. �� ����������� � ������������� ���� ���������, � ���������� ���� ������!!! � ����� - ����� ��������� ���������� �����! �������, �� ��������� ����� ���������, � ��������������� �� ���������� ����� ��� ����� �� ����������. ��������, � ������������ ������� ���� ���� ��������� �������� ���, ����� �����. ������, �������� �������� ��, ��� ��� ����! ';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo'<a href="?boy">������ ��� � ������</a><br>';
				QuoteTable('close');
				}
				else*/
				{
				$_SESSION['quest1_money_girl']=0;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>������ �� ���� ����������� � �������, ��� �������� ����� "����" � ���� ��� ������. ������� ������, �� ����������, ��� ��������� �� �������� ������, � ����������� - ��� �� ��� �������� ���� �������� �����! �������, ��� ���� �� ��������� �����, ��� ������� ���-�� ������� �� ���, �� ������ ������� ��������� ����������� �������, �� ������, ��� ����� 90 ����� ��� ��� �������������� ����� ���� ������� ������� �� �������, � �� ������ ������ ������ ������ �������. �� � ���, ����� ��, ���� �� ������ ������!';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				 echo'
					1) <a href="?state=1232125202321">�������� �� ��������</a><br>
					2) <a href="?state=1232125202322">�����-��!!! *�������� � ������*</a><br>';
				QuoteTable('close');
				}
				break;
			}
			case '1232125202322':
			{
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>���� �������, �� �� ���� ���� �������� ��������� �������, �� ���� �� ����� ������ �������� ���. �����, ����� ������, ��, � �������, �� ����������! ���������! �������, ��� ���� ������ �������� ������, �� ������� ������ ������! ����� ���-�� ��������.<br> �� ��� ������� �������, � ���� ����� ���� �������, �� ���������� ����� �� ������ ���������, ������� ��� ������� ������������ ����������, � ������� &quot;��� ��� �������!&quot;';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				$_SESSION['quest1_exit']=50;
				echo'<a href="?exit">����� �������!</a><br>';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo '�� ���������� 90 ������� �����<br>';
				list($item_name) = mysql_fetch_array(myquery("SELECT name FROM game_items_factsheet WHERE id=8"));
				echo '�� �������� �������: '.$item_name.'<br>';
				echo '�� �������� '.$get_exp.' ����� �����<br>';
				QuoteTable('close');
				break;
			}
			case '1232125202321':
			{
				$_SESSION['quest1_step']=14;

				if ($_SESSION['quest1_take_shlem']==0 AND $_SESSION['quest1_take_weapon']==0)
				{
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>���������� ���� �������, �� ����� ��� ������ ������ ������. ��� �, ������, ��� ����� ������ �� �������� ���. ��� �������� �� �����?';
				QuoteTable('close');                }
				elseif ($_SESSION['quest1_take_shlem']>=1 AND $_SESSION['quest1_take_weapon']==0)
				{
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>���������� ���� �������, �� ����� ��� ������ ������ � ������� ���� �� ������ �������. ������, ��� ����� ����� ��� ���� ���� ����� ����������. � ������ ��� - ����� ����������. ��� �������� �� �����?';
				QuoteTable('close');
				}
				elseif ($_SESSION['quest1_take_shlem']==0 AND $_SESSION['quest1_take_weapon']>=1)
				{
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>���������� ���� �������, �� ����� ��� ������ ������ � ������ � ����� ���������. ������, ��� ������ ����� ��� ����� ���� ����� ������. � ������ ��� - ����� �����������. ��� �������� �� �����?';
				QuoteTable('close');
				}
				elseif ($_SESSION['quest1_take_shlem']>=1 AND $_SESSION['quest1_take_weapon']>=1)
				{
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>���������� ���� �������, �� ����� ��� ������ ������, ������� ���� �� ������ ������� � ������ � ����� ���������. ������, ��� ���� ������ � ������� �������� ����� ��� ������ ������, ������ � ���� ������ - ��������� ������ ������, � ����� � ����������� �������� - ��� ������ � ������. ��� �������� �� �����?';
				QuoteTable('close');
				}
				elseif (!isset($gotovo) AND !isset($portal))
				{
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>��� ����� ������??';
				QuoteTable('close');
				}
				if (isset($weapon))
				{
					//myquery("DELETE FROM game_items WHERE ident='����������� ���' AND user_id='$user_id' AND used=''");
					$_SESSION['quest1_take_weapon']=2;
					$_SESSION['quest1_ves_plita']+=46;
				}
				if (isset($weapon_m))
				{
					//myquery("DELETE FROM game_items WHERE ident='����������� ���' AND user_id='$user_id' AND used=''");
					$_SESSION['quest1_take_weapon']=1;
					$_SESSION['quest1_ves_plita']-=46;
				}
				if (isset($shlem))
				{
					//myquery("DELETE FROM game_items WHERE ident='������� ����' AND user_id='$user_id' AND used=''");
					$_SESSION['quest1_take_shlem']=2;
					$_SESSION['quest1_ves_plita']+=27;
				}
				if (isset($shlem_m))
				{
					//myquery("DELETE FROM game_items WHERE ident='������� ����' AND user_id='$user_id' AND used=''");
					$_SESSION['quest1_take_shlem']=1;
					$_SESSION['quest1_ves_plita']-=27;
				}
				if (isset($keys))
				{
					$_SESSION['quest1_have_key']=2;
					$_SESSION['quest1_ves_plita']+=37;
				}
				if (isset($keys_m))
				{
					$_SESSION['quest1_have_key']=1;
					$_SESSION['quest1_ves_plita']-=37;
				}
				if (isset($money))
				{
					/*if($_SESSION['quest1_get_gp']>0)*/
					$_SESSION['quest1_get_gp']--;
					$_SESSION['quest1_money_girl']++;
					$_SESSION['quest1_ves_plita']++;

					/*elseif ($char['GP']>0)
					{
						myquery("UPDATE game_users SET GP=GP-1,CW=CW-'".(1*money_weight)."' WHERE user_id='$user_id'");
						$sel = myquery("SELECT * FROM game_users WHERE user_id='$user_id'");
						$char = mysql_fetch_array($sel);
						list($char_map_name,$char_map_xpos,$char_map_ypos) = mysql_fetch_array(myquery("SELECT map_name,map_xpos,map_ypos FROM game_users_map WHERE user_id='$user_id'"));
						list($last_active) = mysql_fetch_array(myquery("SELECT last_active FROM game_users_active WHERE user_id='$user_id'"));
						$char['map_name']=$char_map_name;
						$char['map_xpos']=$char_map_xpos;
						$char['map_ypos']=$char_map_ypos;
						$char['last_active']=$last_active;
					}
					$_SESSION['quest1_ves_plita']+=1;
					else if(($_SESSION['quest1_get_gp']<=0)and ($char['GP']<=0))
					{
						$_SESSION['quest1_ves_plita']-=1;
						echo '<br>';
						QuoteTable('open');
						echo '� ��� ��������� ������.';
						echo '<br>';
						QuoteTable('close');
					}*/
				}
				if (isset($money_m))
				{
					$_SESSION['quest1_get_gp']+=1;
					$_SESSION['quest1_money_girl']-=1;
					$_SESSION['quest1_ves_plita']-=1;
					/*if($_SESSION['quest1_get_gp']=0) $_SESSION['quest1_get_gp']+=1;
					elseif ($char['GP']>0)
					{
						myquery("UPDATE game_users SET GP=GP+1,CW=CW+'".(1*money_weight)."' WHERE user_id='$user_id'");
						$sel = myquery("SELECT * FROM game_users WHERE user_id='$user_id'");
						$char = mysql_fetch_array($sel);
						list($char_map_name,$char_map_xpos,$char_map_ypos) = mysql_fetch_array(myquery("SELECT map_name,map_xpos,map_ypos FROM game_users_map WHERE user_id='$user_id'"));
						list($last_active) = mysql_fetch_array(myquery("SELECT last_active FROM game_users_active WHERE user_id='$user_id'"));
						$char['map_name']=$char_map_name;
						$char['map_xpos']=$char_map_xpos;
						$char['map_ypos']=$char_map_ypos;
						$char['last_active']=$last_active;
					/*}*/
				  /*  $_SESSION['quest1_ves_plita']-=1;
					$_SESSION['quest1_money_girl']-=1;*/

					/*if(($_SESSION['quest1_get_gp']<=0)and ($char['GP']<=0))
					{
						$_SESSION['quest1_ves_plita']-=1;
						echo '<br>';
						QuoteTable('open');
						echo '� ��� ��������� ������.';
						echo '<br>';
						QuoteTable('close');
					}
					}*/
				}
				if (isset($gotovo))
				{
					if ($_SESSION['quest1_ves_plita']<86)
					{
						QuoteTable('open');
						echo '<font size=3 color=#F0F0F0>�� �������� ������� ���� ���� �� �����, � �� ���� ������ � ��� �������� ������...';
						QuoteTable('close');
						echo '<br><br><br>';
						QuoteTable('open');
						echo '<font size=3 color=#FF0000>�� ��������� �����. �� ������ ����������� ������ ���� ����� ��� ��� ����� ��������� �����';
						QuoteTable('close');
						QuoteTable('open');
						$_SESSION['quest1_exit']=1;
						echo'<a href="?exit">����� ��������</a><br>';
						QuoteTable('close');
						break;
					}
					else
					{
						QuoteTable('open');
						echo '<font size=3 color=#F0F0F0>������������, � �������� �������������� � ���������� ��������, �� ������ ���� � �����. ������ �� ���������. ��������� ���������, �� ���� ����������� � �������, ��� �������� �������� ��������. �� ����������� � ������������� ���� ���������, � ���������� ���� ������!!! � ����� - ����� ��������� ���������� �����! �������, �� ��������� ����� ���������, � ��������������� �� ���������� ����� ��� ����� �� ����������. ��������, � ������������ ������� ���� ���� ��������� �������� ���, ����� �����. ������, �������� �������� ��, ��� ��� ����! ';
						QuoteTable('close');
						echo '<br><br><br>';
						QuoteTable('open');
						echo'<a href="?boy">������ ��� � ������</a><br>';
						QuoteTable('close');
						/*QuoteTable('open');
						echo '<font size=3 color=#F0F0F0>������������, � �������� �������������� � ���������� ��������, �� ������ ���� � �����. ������ �� ���������. ��������� ���������, �� ������� � ������. �� ��� ��� �������, � ���� ����� ���� �������, �� ���������� ����� �� ������ ���������, ������� ��� ������� ������������ ����������, � ������� &quot;��� ��� �������!&quot;';
						QuoteTable('close');
						echo '<br><br><br>';
						QuoteTable('open');
						$_SESSION['quest1_exit']=99;
						echo '<font size=3 color=#F0F0F0>����������!<br>';
						echo'<a href="?exit">����� �������!</a><br>';
						QuoteTable('close');*/
						break;
					}
				}
				if (isset($portal))
				{
					QuoteTable('open');
					echo '<font size=3 color=#F0F0F0>���� �������, �� �� ���� ���� �������� ��������� �������, �� ���� �� ����� ������ �������� ���. �����, ����� ������, ��, � �������, �� ����������! ���������! �������, ��� ���� ������ �������� ������, �� ������� ������ ������! ����� ���-�� ��������.<br> �� ��� ������� �������, � ���� ����� ���� �������, �� ���������� ����� �� ������ ���������, ������� ��� ������� ������������ ����������, � ������� &quot;��� ��� �������!&quot;';
					QuoteTable('close');
					echo '<br><br><br>';
					QuoteTable('open');
					$_SESSION['quest1_exit']=50;
					echo'<a href="?exit">����� �������!</a><br>';
					QuoteTable('close');
					echo '<br><br><br>';
					QuoteTable('open');
					echo '�� ���������� 90 ������� �����<br>';
					list($item_name) = mysql_fetch_array(myquery("SELECT name FROM game_items_factsheet WHERE id=8"));
					echo '�� �������� �������: '.$item_name.'<br>';
					echo '�� �������� '.$get_exp.' ����� �����<br>';
					QuoteTable('close');
					break;
				}
				echo '<br><br><br>';
				QuoteTable('open');
				$i=0;
				if ($_SESSION['quest1_take_weapon']==1)
				{
					$i++;
					echo $i.') <a href="?state=1232125202321&weapon">�������� ��� ���������</a><br>';
				}
				if ($_SESSION['quest1_take_weapon']==2)
				{
					$i++;
					echo $i.') <a href="?state=1232125202321&weapon_m">������ ��� ���������</a><br>';
				}
				if ($_SESSION['quest1_have_key']==1)
				{
					$i++;
					echo $i.') <a href="?state=1232125202321&keys">�������� ������ ������</a><br>';
				}
				if ($_SESSION['quest1_have_key']==2)
				{
					$i++;
					echo $i.') <a href="?state=1232125202321&keys_m">������ ������ ������</a><br>';
				}
				if ($_SESSION['quest1_take_shlem']==1)
				{
					$i++;
					echo $i.') <a href="?state=1232125202321&shlem">�������� ������� ����</a><br>';
				}
				if ($_SESSION['quest1_take_shlem']==2)
				{
					$i++;
					echo $i.') <a href="?state=1232125202321&shlem_m">������ ������� ����</a><br>';
				}
				if (/*$char['GP']+*/$_SESSION['quest1_get_gp']>0)
				{
					$i++;
					echo $i.') <a href="?state=1232125202321&money">�������� ������</a><br>';
				}
				if ($_SESSION['quest1_money_girl']>0)
				{
					$i++;
					echo $i.') <a href="?state=1232125202321&money_m">������ ������</a><br>';
				}
				$i++;
				echo '<br>';
				echo $i.') <a href="?state=1232125202321&gotovo">������! ������ ����</a><br>';
				$i++;
				echo $i.') <a href="?state=1232125202321&portal">�����-��!!! *�������� � ������*</a><br>';
				QuoteTable('close');
				break;
			}
			case '12321252024':
			{
				$_SESSION['quest1_step']=15;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>�� ��������� � ��������� ������. ����� ����������� ������ � ������ ������� �� ��� ��������� � -  ��-�� - ����������� �������. ����� ������ ';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo '<font size=3 color=#FF0000>�� ��������� �����. �� ������ ����������� ������ ���� ����� ��� ��� ����� ��������� �����';
				QuoteTable('close');
				QuoteTable('open');
				$_SESSION['quest1_exit']=1;
				echo'<a href="?exit">����� ��������</a><br>';
				QuoteTable('close');
				break;
			}
			case '12321253':
			{
				$_SESSION['quest1_step']=16;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>- <B>� ��� ���� 200 ����� - ���, ��� � ���� ����! ��� ������, �� ��� �� ��������?</B>';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				 echo'
					1) <a href="?state=12321252">��, �������, ������ � �������� ���.</a><br>
					2) <a href="?state=123212531">��, � �� �� ���� ����� �������� ��������.. �������, ����� �������?</a><br>
					3) <a href="?state=12321252024">�� ���, ����, � ���� ���� ����� �������! *����� ��  ���������*</a><br>';
				QuoteTable('close');
				break;
			}
			case '999999999':
			{
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>��������� ����� ��� ��� ��� �� ������� ��� ��������� ��������� � �� ��������� ��� �������� �� ������';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				$_SESSION['quest1_exit']=1;
				echo'<a href="?exit">��������� �����</a><br>';
				QuoteTable('close');
				break;
			}
			case '123212531':
			{
				$_SESSION['quest1_step']=16;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>- <B>�� � ���� ��� �� ����� �����������! �� ������ � �����, � ���� ��������� ����������� �� ����������.. ����� ��������� - 230 ����� ����� ���� ������?!</B>';
				$_SESSION['quest1_money_girl']=230;
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				 echo'
					1) <a href="?state=12321252">��, �������, ������ � �������� ���.</a><br>
					2) <a href="?state=1232125311">����� ��� ������ �� �������� ���� � �������. ���-����, ������� �� ������ �� ������ ���� �����?</a><br>
					3) <a href="?state=12321252024">�� ���, ����, � ���� ���� ����� �������! *����� ��  ���������*</a><br>';
				QuoteTable('close');
				break;
			}
			case '1232125311':
			{
				$_SESSION['quest1_step']=16;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>- <B>���� �������� �� ����� ������!!! ����� �������, ��� � �� ������� ������ ����, �� ������� �������� ��� ������ ����� �����! � ���, ��� �� � ����, ������ ���� ������! �� � �� ����������� �� ����� ����, � ����� ��������� ���� 200 �����! �� ��������?</B>';
				$_SESSION['quest1_money_girl']=200;
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				 echo'
					1) <a href="?state=12321252">��, �������, ������ � �������� ���.</a><br>
					2) <a href="?state=12321252024">�� ���, ����, � ���� ���� ����� �������! *����� ��  ���������*</a><br>';
				QuoteTable('close');
				break;
			}
			case '12322':
			{
				$_SESSION['quest1_step']=4;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>- <B>���?! ������ � ������ ����, ��� ��������� ���� �� ���, �������!! - ������� ��������, ��������� ����� � ������ ��� ����� � �����, � ����� ���� ��� ����� ��������. ���� �� ��������� � ����, �� ����� � ������ �� ����� �����.</B>';
				myquery("UPDATE game_users SET HP=HP-10 WHERE user_id='$user_id'");
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=1231">����������� �����.</a><br>
				2) <a href="?state=1232">�������� �������� ����������</a><br>
				3) <a href="?state=1233">���������� ������� �� �����, ����� ������� � ���������.</a><br>';
				QuoteTable('close');
				break;
			}
			case '12323':
			{
				$_SESSION['quest1_step']=19;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>������, �� �������������� ���������. �� ������� ������� � ������� �������:<br>
		- <B>� ������� �� �� ����� ���������?</B>';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=123231">�� ������� ������, ������ ������� ����!</a><br>
				2) <a href="?state=123232">10 �����, �����, ������.</a><br>
				3) <a href="?state=123233">� ��� ���� ����� 50 �����.</a><br>
				4) <a href="?state=123234">100 �����. ��-�����, ��� �������� ������.</a><br>';
				QuoteTable('close');
				break;
			}
			case '123231':
			{
				$_SESSION['quest1_step']=19;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>- <B>��, ����� ������! ��� ��, ������, �������?!</B>';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=123231">�� ������� ������, ������ ������� ����!</a><br>
				2) <a href="?state=123232">10 �����, �����, ������.</a><br>
				3) <a href="?state=123233">� ��� ���� ����� 50 �����.</a><br>
				4) <a href="?state=123234">100 �����. ��-�����, ��� �������� ������.</a><br>';
				QuoteTable('close');
				break;
			}
			case '123232':
			{
				$_SESSION['quest1_step']=19;
				QuoteTable('open');
				if ($char['GP']>10)
				{
					echo '<font size=3 color=#F0F0F0>       <B> - ��, �����</B>, - �������� ��������. �� ��������� ��� ����� ������� ������ �������.
		<br>- <B>�-��-��-��-��!</B> - ������������ ������� ���. -<B> ������� �� �������, ��� � ����� ��� ��������� ���� �� ������ 10 �����???</B> <br> � �� ������ ���� ������� � �������� �������.' ;
					myquery("UPDATE game_users SET GP=GP-10,CW=CW-'".(10*money_weight)."' WHERE user_id='$user_id'");
					setGP($user_id,-10,59);
					QuoteTable('close');
					echo '<br><br><br>';
					QuoteTable('open');
					echo'
					1) <a href="?state=123231">�� ������� ������, ������ ������� ����!</a><br>
					2) <a href="?state=123232">10 �����, �����, ������.</a><br>
					3) <a href="?state=123233">� ��� ���� ����� 50 �����.</a><br>
					4) <a href="?state=123234">100 �����. ��-�����, ��� �������� ������.</a><br>';
					QuoteTable('close');
				}
				else
				{
					$_SESSION['quest1_step']=4;
					echo '<font size=3 color=#F0F0F0>        <B>���? ��������� ���� ����������, �������?</B> - ������� ��������, ��������� ����� � ������������ ����� ��� ����� ��������. ���� �� ��������� � ����, �� ����� � ������ �� ����� �����. ';
					myquery("UPDATE game_users SET HP=HP-10 WHERE user_id=$user_id");
					QuoteTable('close');
					echo '<br><br><br>';
					QuoteTable('open');
					echo'
					1) <a href="?state=1231">����������� �����.</a><br>
					2) <a href="?state=1232">�������� �������� ����������</a><br>
					3) <a href="?state=1233">���������� ������� �� �����, ����� ������� � ���������.</a><br>';
					QuoteTable('close');
				}
				break;
			}
			case '123233':
			{
				$_SESSION['quest1_step']=20;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>- <B>��� �, ����� ��������� ��� ���: ������-�� � ��� ��� 15 - � �� ��������!</B>';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=1232331">����� ��!</a><br>
				2) <a href="?state=1232332">�����, ������ ������.</a><br>';
				QuoteTable('close');
				break;
			}
			case '1232331':
			{
				$_SESSION['quest1_step']=4;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>- <B>���? �� ����� ������, �������</B> - ������� ��������, ��������� ����� � ������������ ����� ��� ����� ��������. ���� �� ��������� � ����, �� ����� � ������ �� ����� �����. ';
				myquery("UPDATE game_users SET HP=HP-10 WHERE user_id=$user_id");
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=1231">����������� �����.</a><br>
				2) <a href="?state=1232">�������� �������� ����������</a><br>
				3) <a href="?state=1233">���������� ������� �� �����, ����� ������� � ���������.</a><br>';
				QuoteTable('close');
				break;
			}
			case '1232332':
			{
				$_SESSION['quest1_step']=21;
				QuoteTable('open');
				if ($char['GP']>65)
				{
					echo '<font size=3 color=#F0F0F0> - <B>��, ������ ������� � ����� �������</B>, - ������ ��������, ������� �������. - <B>��� �, � ���� ���������. ����������� ���� ������</B>, - �� ������ ������������ � ����� �� ���������. �� ������, ��� �� ���� ����� ����� ���-�� �� ������������. � ��� �� ��������, ��� �������� ����� � ����� ������ ������';
					myquery("UPDATE game_users SET GP=GP-65,CW=CW-'".(65*money_weight)."' WHERE user_id=$user_id");
					setGP($user_id,-65,59);
					QuoteTable('close');
					echo '<br><br><br>';
					QuoteTable('open');
					echo'
					<a href="?state=12323321">����� �����</a><br>';
					QuoteTable('close');
				}
				else
				{
					$_SESSION['quest1_step']=4;
					echo '<font size=3 color=#F0F0F0> - <B>���? ��������� ���� ����������, �������? </B>- ������� ��������, ��������� ����� � ������������ ����� ��� ����� ��������. ���� �� ��������� � ����, �� ����� � ������ �� ����� �����. ';
					myquery("UPDATE game_users SET HP=HP-10 WHERE user_id=$user_id");
					QuoteTable('close');
					echo '<br><br><br>';
					QuoteTable('open');
					echo'
					1) <a href="?state=1231">����������� �����.</a><br>
					2) <a href="?state=1232">�������� �������� ����������</a><br>
					3) <a href="?state=1233">���������� ������� �� �����, ����� ������� � ���������.</a><br>';
					QuoteTable('close');
				}
				break;
			}
			case '12323321':
			{
				$_SESSION['quest1_step']=11;
				$_SESSION['quest1_have_key']=1;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>�� ��������� �� �������� ����� � ����� �������, ��� ���-�� �������� ��� �� �����, ����� ������� �� �������� ������. �� ������������ ����, � ������������� �� ����� �������, ������� ������ ��������� � ���� ��� �����������. ��� ���������� � ���.
	   <br> - <B>����������, �������� ����! ��� ������� ������ ���� ��� ��� ��������� � ����� �����, ���� �� ���� �� �������� �����! ���� �� ���� ���������, � ����� ���������� ���� � ������ ��������� ������! � ���� ���� ���� �����?</B>

';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				if ($_SESSION['quest1_have_key']==1)
				{
					 echo'
					1) <a href="?state=12321252">��, �������, ������ � �������� ���.</a><br>
					2) <a href="?state=12321253">��, ����, �� ������ �������� �� ��������, ��� �� �������������� ��� ������ &quot;�����&quot;?</a><br>
					3) <a href="?state=12321252024">�� ���, ����, � ���� ���� ����� �������! *����� ��  ���������*</a><br>';
				}
				else
				{
					echo'<a href="?state=12321251">�������! *����� � ����� �����*</a><br>';
				}
				QuoteTable('close');
				break;
			}
			case '123234':
			{
				$_SESSION['quest1_step']=21;
				QuoteTable('open');
				if ($char['GP']>100)
				{
					echo '<font size=3 color=#F0F0F0> - <B>����� ��������</B>, - ���������� ��������, ������� �������. - <B>��� �, � ���� ���������. ����������� ���� ������</B>, - �� ������ ������������ � ����� �� ���������. �� ������, ��� �� ���� ����� ����� ���-�� �� ������������. � ��� �� ��������, ��� �������� ����� � ����� ������ ������ ';
					myquery("UPDATE game_users SET GP=GP-100,CW=CW-'".(100*money_weight)."' WHERE user_id=$user_id");
					setGP($user_id,-100,59);
					QuoteTable('close');
					echo '<br><br><br>';
					QuoteTable('open');
					echo'
					<a href="?state=12323321">����� �����</a><br>';
					QuoteTable('close');
				}
				else
				{
					$_SESSION['quest1_step']=4;
					echo '<font size=3 color=#F0F0F0> - <B>���? ��������� ���� ����������, �������?</B> - ������� ��������, ��������� ����� � ������������ ����� ��� ����� ��������. ���� �� ��������� � ����, �� ����� � ������ �� ����� �����. ';
					myquery("UPDATE game_users SET HP=HP-10 WHERE user_id=$user_id");
					QuoteTable('close');
					echo '<br><br><br>';
					QuoteTable('open');
					echo'
					1) <a href="?state=1231">����������� �����.</a><br>
					2) <a href="?state=1232">�������� �������� ����������</a><br>
					3) <a href="?state=1233">���������� ������� �� �����, ����� ������� � ���������.</a><br>';
					QuoteTable('close');
				}
				break;
			}
			case '12324':
			{
				$_SESSION['quest1_step']=9;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>����� �������� ������� � �������, �� ���������� �� ������ �, ������� ����, ������� �����, ������ �������� �� ����. �������� ����������� ������ ����� ����� � � �������������� ��������� ��� ����; ���������������� ����, �� ��� ���� ���� ������� ��� ������ ����. ���������� ��������� �� ��� ��� ������. ��� ������ � ���?';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=123241">���������� ��� ����� �� ���.</a><br>
				2) <a href="?state=1232122">����� � ����� �����.</a><br>
				3) <a href="?state=1232123">�������� ����. </a><br>
				4) <a href="?state=1232124">����� ���.</a><br>
				5) <a href="?state=1232125">������.</a><br>';
				QuoteTable('close');
				break;
			}
			case '123241':
			{
				$_SESSION['quest1_step']=9;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>�� �������� ������� ��������� �� ����� � ��������� ��� ���� ��� ���� ������� � �������� ���, ���� ��� �� ���������� ���������� � ��������� ������ �� ����������� �������. ���� ������ �� �� �������.';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=1232121">����������� ��� ������.</a><br>
				2) <a href="?state=1232122">����� � ����� �����.</a><br>
				3) <a href="?state=1232123">�������� ����. </a><br>
				4) <a href="?state=1232124">����� ���.</a><br>
				5) <a href="?state=1232125">������.</a><br>';
				QuoteTable('close');
				break;
			}
			case '12325':
			{
				$_SESSION['quest1_step']=4;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>-<B> ���?! ��� � - ������ �����?! ��, ������ �� � ���� ��������, �����!!</B> - � ����� ������� �������� ��������� � ��� � ������ � ������� �������� ��������, � ����� �� ��� ������� �� ���, �� � ������. ���� �� ���������, ��������� �������� �����, �� ������� � �������� �� ����� �����. ';
				myquery("UPDATE game_users SET HP=HP-30 WHERE user_id=$user_id");
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=1231">����������� �����.</a><br>
				2) <a href="?state=1232">�������� �������� ����������</a><br>
				3) <a href="?state=1233">���������� ������� �� �����, ����� ������� � ���������.</a><br>';
				QuoteTable('close');
				break;
			}
			case '1233':
			{
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>�� ����� ���� ���� �������, � � ������ ������ ���� ���������. ������� ����� ������ ���, �� ��������� ������ ����� ������� � �����-�� �����, ��������� �� �������, ������ �� �����, ��������������� ������, ��� �������, � ��������� � ���� ��, ����� ���������� ������';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo '<font size=3 color=#FF0000>�� ��������� �����. �� ������ ����������� ������ ���� ����� ��� ��� ����� ��������� �����';
				QuoteTable('close');
				QuoteTable('open');
				$_SESSION['quest1_exit']=1;
				echo'<a href="?exit">����� ��������</a><br>';
				QuoteTable('close');
				break;
			}
			case '13':
			{
				$_SESSION['quest1_step']=22;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>- <B>�������-�������</B>, - ������������ ���������, �� � ��� ������ �� �������� �������������� �����. - <B>� � ����� The_Elf</B>, - ����������� �� �� ���� ���. - <B>� ���</B>, - �� ������ ����� �� �����, �������� �������, - <B>������ Zander � blazevic.. ������� ������������� ����, ����� ������ ��� ������� ����,<I> �����?</I></B> - �� ����� ������� ����� &quot;�����&quot;.';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=131">- *������* � �������� � ��������� ���, � ����� �������! � ���� �� ���������� � ����� ����, �� - �� ��������� ������ ��� �����, ��� ��������� � ��� � �������. - ���� ����� ��� ��� �������� �� ����� ���������, � ����� ���� ����� ��� ����� �����!</a><br>
				2) <a href="?state=132">- ��������, �, ������� ��, �������.. ��������� ��������������</a><br>
				3) <a href="?state=133">- ��� ��������, ������! �������� ������ ���, �������� �� ������������.</a><br>';
				QuoteTable('close');
				break;
			}
			case '131':
			{
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>- <B>�� ���� ����� ���� ���� ��� ������ ��������!</B> - � ����� ������� ��������� ���������� ����� �������� ���� ��� � ������� ��� ������. ����� ������.';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo '<font size=3 color=#FF0000>�� ��������� �����. �� ������ ����������� ������ ���� ����� ��� ��� ����� ��������� �����';
				QuoteTable('close');
				QuoteTable('open');
				$_SESSION['quest1_exit']=1;
				echo'<a href="?exit">����� ��������</a><br>';
				QuoteTable('close');
				break;
			}
			case '132':
			{
				$_SESSION['quest1_step']=3;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>- <B>� �� - ����������� ����� ���������� ����!</B> - ������� ������ ��������� � ������ � ��� ����� ����� �����-�� ��������� ������. -<B> ���</B>, - ��� �� ������ �� �������, -<B> �������� ������. � ������ ����� ����� ������� � �� ������� ���������� ��������!</B>';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=121">��, ��������, ����������, �������, � ��������.</a><br>
				2) <a href="?state=122">���?! �� ��� �������� ������������ ������! ������ �����������!</a><br>
				3) <a href="?state=123">������, �� �������, - ������� ����� ��� ������� �� ������� �����.</a><br>';
				QuoteTable('close');
				break;
			}
			case '133':
			{
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>�����������, ������ ���������. � �� ��� � �������� ������, ����� �� ����� � ���� �� ����������';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo '<font size=3 color=#FF0000>�� ��������� �����. �� ������ ����������� ������ ���� ����� ��� ��� ����� ��������� �����';
				QuoteTable('close');
				QuoteTable('open');
				$_SESSION['quest1_exit']=2;
				echo'<a href="?exit">����� ��������</a><br>';
				QuoteTable('close');
				break;
			}
			case '14':
			{
				$_SESSION['quest1_step']=0;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>�� ����� ����� �����, � ���� ������ � �������� - �����.';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo '<font size=3 color=#FF0000>�� ��������� �����. �� ������ ����������� ������ ���� ����� ��� ��� ����� ��������� �����';
				QuoteTable('close');
				QuoteTable('open');
				$_SESSION['quest1_exit']=2;
				echo'<a href="?exit">����� ��������</a><br>';
				QuoteTable('close');
				break;
			}
			case '2':
			{
				$_SESSION['quest1_step']=0;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>���� ������������� ������ � ������� ��� � ������ �����. ��������� ���, � ���������, ��� �� ������� - ������� ���� �� ����������� �� ��������, ������ ����� ��� �� ����..';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo '<font size=3 color=#FF0000>�� ��������� �����. �� ������ ����������� ������ ���� ����� ��� ��� ����� ��������� �����';
				QuoteTable('close');
				QuoteTable('open');
				$_SESSION['quest1_exit']=1;
				echo'<a href="?exit">����� ��������</a><br>';
				QuoteTable('close');
				break;
			}
			case '3':
			{
				$_SESSION['quest1_step']=23;
				$ra = mt_rand(1,8);
				$_SESSION['quest1_wind_might']=mt_rand(1,5);
				$_SESSION['quest1_luk_kol']=5;
				$_SESSION['quest1_luk_up']=0;
				$_SESSION['quest1_luk_right']=0;
				$_SESSION['quest1_luk_up_need']=0;
				$_SESSION['quest1_luk_right_need']=0;
				//����������� �����
				switch ($ra)
				{
					case 1:
					{
					$_SESSION['quest1_wind_move']="N";
					$_wind = "���� � ������";
					$_SESSION['quest1_luk_up_need']=10+$_SESSION['quest1_wind_might']-$_SESSION['quest1_luk_kol'];
					$_SESSION['quest1_luk_right_need']=0;
					break;
					}
					case 2:{ $_SESSION['quest1_wind_move']="NE"; $_wind = "���� � ������-�������";
					$_SESSION['quest1_luk_up_need']=10+$_SESSION['quest1_wind_might']-$_SESSION['quest1_luk_kol'];
					$_SESSION['quest1_luk_right_need']=$_SESSION['quest1_wind_might']-1;
					break;
					}
					case 3:{ $_SESSION['quest1_wind_move']="E"; $_wind = "���� � �������";
					$_SESSION['quest1_luk_up_need']=10-$_SESSION['quest1_luk_kol'];
					$_SESSION['quest1_luk_right_need']=$_SESSION['quest1_wind_might']-1;
					break;
					}
					case 4:{ $_SESSION['quest1_wind_move']="SE"; $_wind = "���� � ���-�������";
					$_SESSION['quest1_luk_up_need']=10-$_SESSION['quest1_wind_might']-$_SESSION['quest1_luk_kol'];
					$_SESSION['quest1_luk_right_need']=$_SESSION['quest1_wind_might']-1;
					break;
					}
					case 5:{ $_SESSION['quest1_wind_move']="S"; $_wind = "���� � ���";
					$_SESSION['quest1_luk_up_need']=10-$_SESSION['quest1_wind_might']-$_SESSION['quest1_luk_kol'];
					$_SESSION['quest1_luk_right_need']=0;
					break;
					}
					case 6:{ $_SESSION['quest1_wind_move']="SW"; $_wind = "���� � ���-������";
					$_SESSION['quest1_luk_up_need']=10-$_SESSION['quest1_wind_might']-$_SESSION['quest1_luk_kol'];
					$_SESSION['quest1_luk_right_need']=1-$_SESSION['quest1_wind_might'];
					break;
					}
					case 7:{ $_SESSION['quest1_wind_move']="W"; $_wind = "���� � ������";
					$_SESSION['quest1_luk_up_need']=10-$_SESSION['quest1_luk_kol'];
					$_SESSION['quest1_luk_right_need']=1-$_SESSION['quest1_wind_might'];
					break;
					}
					case 8:{ $_SESSION['quest1_wind_move']="NW"; $_wind = "���� � ������-������";
					$_SESSION['quest1_luk_up_need']=10+$_SESSION['quest1_wind_might']-$_SESSION['quest1_luk_kol'];
					$_SESSION['quest1_luk_right_need']=1-$_SESSION['quest1_wind_might'];
					break;
					}
				}

//������ ������� 0
			$_SESSION['quest1_luk_up']=12;


			if ($_SESSION['quest1_luk_up']>$_SESSION['quest1_luk_up_need'])
			{ $vert="<font color=#FFB400><b>����������</b></font> ";}
			if ($_SESSION['quest1_luk_up']<$_SESSION['quest1_luk_up_need'])
			{ $vert="<font color=#3F00FF><b>����������</b></font> ";}
			if ($_SESSION['quest1_luk_up']==$_SESSION['quest1_luk_up_need'])
			{ $vert="<font color=#3F00FF><b>��������</b></font> ";}
			if ($_SESSION['quest1_luk_right']<$_SESSION['quest1_luk_right_need'])
			{ $gor="<b>�����</b>.";}
			if ($_SESSION['quest1_luk_right']>$_SESSION['quest1_luk_right_need'])
			{ $gor="<b>������</b>.";}
			if ($_SESSION['quest1_luk_right']==$_SESSION['quest1_luk_right_need'])
			{ $gor=" <b>����� �� ����������� � ����������</b>.";}
			switch(abs(abs($_SESSION['quest1_luk_up'])-abs($_SESSION['quest1_luk_up_need'])))
			{
				case 0:{$dVer=" ";break;}
				case 1:{$dVer=" <b>����-����</b> ";break;}
				case 2:case 3:{$dVer=" <b>�������</b> ";break;}
				case 4:case 5:case 6:{$dVer=" <b>�����������</b> ";break;}
				case 7:case 8:case 9:case 10:{$dVer=" <b>������</b> ";break;}
				default:{$dVer=" <b>���������</b> ";break;}
			}
			switch(abs(abs($_SESSION['quest1_luk_right'])-abs($_SESSION['quest1_luk_right_need'])))
			{
				case 0: {$dGor=" ";break;}
				case 1:{$dGor=" <b>����-����</b> ";break;}
				case 2:case 3:{$dGor=" <b>�������</b> ";break;}
				case 4:case 5:case 6:{$dGor=" <b>�����������</b> ";break;}
				case 7:case 8:case 9:case 10:{$dGor=" <b>�������</b> ";break;}
				default:{$dGor=" <b>����� �������</b> ";break;}
			}

			/*if(abs($_SESSION['quest1_luk_right']-$_SESSION['quest1_luk_right_need'])==0)
				{$dGor=" ";}
			elseif(abs($_SESSION['quest1_luk_right']-$_SESSION['quest1_luk_right_need'])<7)
				{$dGor=$dVer;}
			elseif(abs($_SESSION['quest1_luk_right']-$_SESSION['quest1_luk_right_need'])>10)
				{$dGor=" <b>����� �������</b> ";}
			else {$dGor=" <b>�������</b> ";}*/

			$_SESSION['quest1_luk_up']=0;


//����� ������� ������� 0

				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>�� ��������� ���� ���, �� �������������, ��� ����� �������� ����� �����. ��� �, �������� ���������� ��������� ���������� �� ������ �� ���, ������ ���������, ������ ��� ��� ������ ��������� ��������. � �����, ������� '.$_wind .', ������ ���� '.$_SESSION['quest1_wind_might'].'. <br> �� ��������� ������� ������, ������ ������ �� 18 ��������, �� <font color=#FF0000>������������<font color=#F0F0F0>. ������'.$dVer.$vert.'� �����'.$dGor.$gor.'<br><font color=#F0F0F0> ���������, �� ����� �������� ����������� �����.';
				QuoteTable('close');
//����� ��������� ����
		QuoteTable('open');
		echo '<br>';
		$vsmes=" "; $gsmes=" ";
		if($_SESSION['quest1_luk_up']>=0) {$vsmes=" ������ �� ";} else {$vsmes=" ������ �� ";}
		if($_SESSION['quest1_luk_right']>0) {$gsmes=" ������� ";} elseif ($_SESSION['quest1_luk_right']<0) {$gsmes=" ������ ";};
		echo '<font size=3 color=#F0F0F0> � �������: <font size=3 color=#FF0000>'.$_SESSION['quest1_luk_kol'].'<font size=3 color=#F0F0F0> �����.';
		echo '<br>';
		echo '��� ������'.$vsmes.'<font size=3 color=#FF0000>'.abs($_SESSION['quest1_luk_up']*1.5).'<font size=3 color=#F0F0F0> ��������.';
		echo '<br>';
		echo '��� ������ �������'.$gsmes.'�� <font size=3 color=#FF0000>'.abs($_SESSION['quest1_luk_right']*5).'<font size=3 color=#F0F0F0> ��������.';

		QuoteTable('close');
//����� ������ ��������� ����
				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=31">������� ������ �� 1,5 �������</a><br>
				2) <a href="?state=32">�������� ������ �� 1,5 �������</a><br>
				3) <a href="?state=33">�������� ������� �� 5 ��������</a><br>
				4) <a href="?state=34">�������� ������ �� 5 ��������</a><br>
				5) <a href="?state=35">��������</a><br>';
				QuoteTable('close');
				break;
			}
			case '31':
			{
			   if($_SESSION['quest1_luk_up']<=30)
			   {
				$_SESSION['quest1_luk_up']++;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>�� ������� ������';

				QuoteTable('close');
				} else {QuoteTable('open'); echo '<font size=3 color=#F0F0F0>���� ��������� ������ ���, �� ��������� ������������!'; QuoteTable('close');}

//����� ��������� ����
		QuoteTable('open');
		echo '<br>';
		$vsmes=" "; $gsmes=" ";
		if($_SESSION['quest1_luk_up']>=0) {$vsmes=" ������ �� ";} else {$vsmes=" ������ �� ";}
		if($_SESSION['quest1_luk_right']>0) {$gsmes=" ������� ";} elseif ($_SESSION['quest1_luk_right']<0) {$gsmes=" ������ ";};
		echo '<font size=3 color=#F0F0F0> � �������: <font size=3 color=#FF0000>'.$_SESSION['quest1_luk_kol'].'<font size=3 color=#F0F0F0> �����.';
		echo '<br>';
		echo '��� ������'.$vsmes.'<font size=3 color=#FF0000>'.abs($_SESSION['quest1_luk_up']*1.5).'<font size=3 color=#F0F0F0> ��������.';
		echo '<br>';
		echo '��� ������ �������'.$gsmes.'�� <font size=3 color=#FF0000>'.abs($_SESSION['quest1_luk_right']*5).'<font size=3 color=#F0F0F0> ��������.';

		QuoteTable('close');
//����� ������ ��������� ����

				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=31">������� ������ �� 1,5 �������</a><br>
				2) <a href="?state=32">�������� ������ �� 1,5 �������</a><br>
				3) <a href="?state=33">�������� ������� �� 5 ��������</a><br>
				4) <a href="?state=34">�������� ������ �� 5 ��������</a><br>
				5) <a href="?state=35">��������</a><br>';
				QuoteTable('close');
				break;
			}
			case '32':
			{
		   if($_SESSION['quest1_luk_up']>=-60)
		   {
				$_SESSION['quest1_luk_up']--;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>�� �������� ������';

				QuoteTable('close');
		   } else {QuoteTable('open'); echo '<font size=3 color=#F0F0F0>���� �� ����, � ��� � ����� ��������!'; QuoteTable('close');}

//����� ��������� ����
		QuoteTable('open');
		echo '<br>';
		$vsmes=" "; $gsmes=" ";
		if($_SESSION['quest1_luk_up']>=0) {$vsmes=" ������ �� ";} else {$vsmes=" ������ �� ";}
		if($_SESSION['quest1_luk_right']>0) {$gsmes=" ������� ";} elseif ($_SESSION['quest1_luk_right']<0) {$gsmes=" ������ ";};
		echo '<font size=3 color=#F0F0F0> � �������: <font size=3 color=#FF0000>'.$_SESSION['quest1_luk_kol'].'<font size=3 color=#F0F0F0> �����.';
		echo '<br>';
		echo '��� ������'.$vsmes.'<font size=3 color=#FF0000>'.abs($_SESSION['quest1_luk_up']*1.5).'<font size=3 color=#F0F0F0> ��������.';
		echo '<br>';
		echo '��� ������ �������'.$gsmes.'�� <font size=3 color=#FF0000>'.abs($_SESSION['quest1_luk_right']*5).'<font size=3 color=#F0F0F0> ��������.';

		QuoteTable('close');
//����� ������ ��������� ����

				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=31">������� ������ �� 1,5 �������</a><br>
				2) <a href="?state=32">�������� ������ �� 1,5 �������</a><br>
				3) <a href="?state=33">�������� ������� �� 5 ��������</a><br>
				4) <a href="?state=34">�������� ������ �� 5 ��������</a><br>
				5) <a href="?state=35">��������</a><br>';
				QuoteTable('close');
				break;
			}
			case '33':
			{
		   if($_SESSION['quest1_luk_right']<=9)
		   {
				$_SESSION['quest1_luk_right']++;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>�� �������� ������ ������';

				QuoteTable('close');
		   } else {QuoteTable('open'); echo '<font size=3 color=#F0F0F0>� ���� ��� ������!'; QuoteTable('close');}
//����� ��������� ����
		QuoteTable('open');
		echo '<br>';
		$vsmes=" "; $gsmes=" ";
		if($_SESSION['quest1_luk_up']>=0) {$vsmes=" ������ �� ";} else {$vsmes=" ������ �� ";}
		if($_SESSION['quest1_luk_right']>0) {$gsmes=" ������� ";} elseif ($_SESSION['quest1_luk_right']<0) {$gsmes=" ������ ";};
		echo '<font size=3 color=#F0F0F0> � �������: <font size=3 color=#FF0000>'.$_SESSION['quest1_luk_kol'].'<font size=3 color=#F0F0F0> �����.';
		echo '<br>';
		echo '��� ������'.$vsmes.'<font size=3 color=#FF0000>'.abs($_SESSION['quest1_luk_up']*1.5).'<font size=3 color=#F0F0F0> ��������.';
		echo '<br>';
		echo '��� ������ �������'.$gsmes.'�� <font size=3 color=#FF0000>'.abs($_SESSION['quest1_luk_right']*5).'<font size=3 color=#F0F0F0> ��������.';

		QuoteTable('close');
//����� ������ ��������� ����

				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=31">������� ������ �� 1,5 �������</a><br>
				2) <a href="?state=32">�������� ������ �� 1,5 �������</a><br>
				3) <a href="?state=33">�������� ������� �� 5 ��������</a><br>
				4) <a href="?state=34">�������� ������ �� 5 ��������</a><br>
				5) <a href="?state=35">��������</a><br>';
				QuoteTable('close');
				break;
			}
			case '34':
			{
		   if($_SESSION['quest1_luk_right']>=-9)
		   {
				$_SESSION['quest1_luk_right']-=1;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>�� �������� ������ �����';

				QuoteTable('close');
		   } else {QuoteTable('open'); echo '<font size=3 color=#F0F0F0>��������� ������������!'; QuoteTable('close');}
//����� ��������� ����
		QuoteTable('open');
		echo '<br>';
		$vsmes=" "; $gsmes=" ";
		if($_SESSION['quest1_luk_up']>=0) {$vsmes=" ������ �� ";} else {$vsmes=" ������ �� ";}
		if($_SESSION['quest1_luk_right']>0) {$gsmes=" ������� ";} elseif ($_SESSION['quest1_luk_right']<0) {$gsmes=" ������ ";};
		echo '<font size=3 color=#F0F0F0> � �������: <font size=3 color=#FF0000>'.$_SESSION['quest1_luk_kol'].'<font size=3 color=#F0F0F0> �����.';
		echo '<br>';
		echo '��� ������'.$vsmes.'<font size=3 color=#FF0000>'.abs($_SESSION['quest1_luk_up']*1.5).'<font size=3 color=#F0F0F0> ��������.';
		echo '<br>';
		echo '��� ������ �������'.$gsmes.'�� <font size=3 color=#FF0000>'.abs($_SESSION['quest1_luk_right']*5).'<font size=3 color=#F0F0F0> ��������.';

		QuoteTable('close');
//����� ������ ��������� ����

				echo '<br><br><br>';
				QuoteTable('open');
				echo'
				1) <a href="?state=31">������� ������ �� 1,5 �������</a><br>
				2) <a href="?state=32">�������� ������ �� 1,5 �������</a><br>
				3) <a href="?state=33">�������� ������� �� 5 ��������</a><br>
				4) <a href="?state=34">�������� ������ �� 5 ��������</a><br>
				5) <a href="?state=35">��������</a><br>';
				QuoteTable('close');
				break;
			}
			case '35':
			{
				$_SESSION['quest1_luk_kol']--;
				//$_SESSION['quest1_luk_up']=max(0,$_SESSION['quest1_luk_up']);
				//$_SESSION['quest1_luk_right']=max(0,$_SESSION['quest1_luk_right']);
				if ($_SESSION['quest1_luk_kol']>0)
				{
					$_SESSION['quest1_step']=23;
					QuoteTable('open');
					echo '<font size=3 color=#F0F0F0>�� ����������. � ������� �������� '.$_SESSION['quest1_luk_kol'].' �����.<br>';
					QuoteTable('close');
					if ($_SESSION['quest1_luk_up']==$_SESSION['quest1_luk_up_need'] AND
						$_SESSION['quest1_luk_right']==$_SESSION['quest1_luk_right_need'])
					{
						QuoteTable('open');
						echo '<font size=3 color=#F0F0F0>��� <font size=3 color=#00CF00>�������<font size=3 color=#F0F0F0> �������� ������ ����������, �� ��������� �������� � ��� ���� � ����� � �����. ���� �� ��� ������ ������, ������� �� ����� �������, � ������, ������� ��� - ������ - ��������� � ���. <br>';
						QuoteTable('close');
						$_SESSION['quest1_step']=24;
						QuoteTable('open');
						echo'
						1) <a href="?state=311">������������</a><br>
						2) <a href="?state=312&boy">�������� � ���</a><br>';
						QuoteTable('close');
					}
					else
					{
//������ �������
						QuoteTable('open');

			if ($_SESSION['quest1_luk_up']>$_SESSION['quest1_luk_up_need'])
			{ $vert="<font color=#FFB400><b>����������</b></font> ";}
				elseif ($_SESSION['quest1_luk_up']<$_SESSION['quest1_luk_up_need'])
			{ $vert="<font color=#3F00FF><b>����������</b></font> ";}
			elseif ($_SESSION['quest1_luk_up']==$_SESSION['quest1_luk_up_need'])
			{ $vert="<font color=#3F00FF><b>��������</b></font> ";}
			if ($_SESSION['quest1_luk_right']<$_SESSION['quest1_luk_right_need'])
			{ $gor="<b>�����</b>.";}
				elseif ($_SESSION['quest1_luk_right']>$_SESSION['quest1_luk_right_need'])
			{ $gor="<b>������</b>.";}
			elseif ($_SESSION['quest1_luk_right']==$_SESSION['quest1_luk_right_need'])
			{ $gor=" <b>����� �� ����������� � ����������</b>.";}
			switch(abs(abs($_SESSION['quest1_luk_up'])-abs($_SESSION['quest1_luk_up_need'])))
			{
				case 0: {$dVer=" ";break;}
				case 1:{$dVer=" <b>����-����</b> ";break;}
				case 2:case 3:{$dVer=" <b>�������</b> ";break;}
				case 4:case 5:case 6:{$dVer=" <b>�����������</b> ";break;}
				case 7:case 8:case 9:case 10:{$dVer=" <b>������</b> ";break;}
				default:{$dVer=" <b>���������</b> ";break;}
			}
			switch(abs(abs($_SESSION['quest1_luk_right'])-abs($_SESSION['quest1_luk_right_need'])))
			{
				case 0: {$dGor=" ";break;}
				case 1:{$dGor=" <b>����-����</b> ";break;}
				case 2:case 3:{$dGor=" <b>�������</b> ";break;}
				case 4:case 5:case 6:{$dGor=" <b>�����������</b> ";break;}
				case 7:case 8:case 9:case 10:{$dGor=" <b>�������</b> ";break;}
				default:{$dGor=" <b>����� �������</b> ";break;}
			}

			/*if(abs($_SESSION['quest1_luk_right']-$_SESSION['quest1_luk_right_need'])<7)
				{$dGor=$dVer;}
			elseif(abs($_SESSION['quest1_luk_right']-$_SESSION['quest1_luk_right_need'])>10)
				{$dGor=" <b>����� �������</b> ";}
			else {$dGor=" <b>�������</b> ";}*/


			echo '<font size=3 color=#F0F0F0>�� <font color=#FF0000>������������<font color=#F0F0F0>. ������'.$dVer.$vert.'� �����'.$dGor.$gor;

//����� ������ �������
//��������� ������� ������������� ��������
				switch ($_SESSION['quest1_wind_move'])
				{
					case "N":case"NE":case"NW":
					{
					$_SESSION['quest1_luk_up_need']=10+$_SESSION['quest1_wind_might']-$_SESSION['quest1_luk_kol'];
					break;
					}
					case "E":case"W":
					{
					$_SESSION['quest1_luk_up_need']=10-$_SESSION['quest1_luk_kol'];
					break;
					}
					case "SE":case"S":case"SW":
					{
					$_SESSION['quest1_luk_up_need']=10-$_SESSION['quest1_wind_might']-$_SESSION['quest1_luk_kol'];
					break;
					}
				}
//����� ��������� ������� ������������� ��������
						$_SESSION['quest1_luk_up']=0;
						$_SESSION['quest1_luk_right']=0;
						QuoteTable('close');

//����� ��������� ����
		QuoteTable('open');
		echo '<br>';
		$vsmes=" "; $gsmes=" ";
		if($_SESSION['quest1_luk_up']>=0) {$vsmes=" ������ �� ";} else {$vsmes=" ������ �� ";}
		if($_SESSION['quest1_luk_right']>0) {$gsmes=" ������� ";} elseif ($_SESSION['quest1_luk_right']<0) {$gsmes=" ������ ";};
		echo '<font size=3 color=#F0F0F0> � �������: <font size=3 color=#FF0000>'.$_SESSION['quest1_luk_kol'].'<font size=3 color=#F0F0F0> �����.';
		echo '<br>';
		echo '��� ������'.$vsmes.'<font size=3 color=#FF0000>'.abs($_SESSION['quest1_luk_up']*1.5).'<font size=3 color=#F0F0F0> ��������.';
		echo '<br>';
		echo '��� ������ �������'.$gsmes.'�� <font size=3 color=#FF0000>'.abs($_SESSION['quest1_luk_right']*5).'<font size=3 color=#F0F0F0> ��������.';

		QuoteTable('close');
//����� ������ ��������� ����

						echo '<br><br><br>';
						QuoteTable('open');
						echo'
						1) <a href="?state=31">������� ������ �� 1,5 �������</a><br>
						2) <a href="?state=32">�������� ������ �� 1,5 �������</a><br>
						3) <a href="?state=33">�������� ������� �� 5 ��������</a><br>
						4) <a href="?state=34">�������� ������ �� 5 ��������</a><br>
						5) <a href="?state=35">��������</a><br>';
						QuoteTable('close');
					}
				}
				else
				{
					QuoteTable('open');
					echo '<font size=3 color=#F0F0F0>� ��� ����������� ������. ���� ��������: <br>';
					$_SESSION['quest1_search_ohr']=0;
					$_SESSION['quest1_have_key']=0;
					$_SESSION['quest1_step']=1;
					$_SESSION['quest1_need_key']=0;
					$_SESSION['quest1_search_room']=0;
					$_SESSION['quest1_count_money']=0;
					$_SESSION['quest1_bad_portal']=0;
					$_SESSION['quest1_take_shlem']=0;
					$_SESSION['quest1_take_weapon']=0;
					$_SESSION['quest1_ves_plita']=0;
					$_SESSION['quest1_get_gp']=0;
					$_SESSION['quest1_searh_trup']=0;
					$_SESSION['quest1_money_girl']=0;
					$_SESSION['quest1_wind_move']=0;
					$_SESSION['quest1_wind_might']=0;
					QuoteTable('close');
					QuoteTable('open');
					echo'
					1) <a href="?state=01">� ������ &quot;��, �� ��� �������?!&quot; �������� � ����� ��������.</a><br>
					2) <a href="?state=02">��������, �������� ���, � ������ &quot;����� �� �������, ���������� ���!&quot;</a><br>
					4) <a href="?state=04">���������� � ����������� �� �������� ���������</a><br>
					5) <a href="?state=05">������ ������� � ���������� ����</a>';
					QuoteTable('close');
				}
				break;
			}
			case '311':
			{
				$_SESSION['quest1_step']=0;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>�� ������� ������� � ���� ���';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo '<font size=3 color=#FF0000>�� ��������� �����. �� ������ ����������� ������ ���� ����� ��� ��� ����� ��������� �����';
				QuoteTable('close');
				QuoteTable('open');
				$_SESSION['quest1_exit']=2;
				echo'<a href="?exit">����� ��������</a><br>';
				QuoteTable('close');
				break;
			}
			case '320':
			{
				$_SESSION['quest1_step']=26;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>�� �������� ������ ��� ����� �� ��������� �������, �� ������ ������ � ��� ��� ������� �� ������.<br>';
				QuoteTable('close');
				QuoteTable('open');
				echo'
				1) <a href="?state=321">��������� � ������.</a><br>
				2) <a href="?state=322&boy">������� ����� � ����</a><br>';
				QuoteTable('close');
				break;
			}
			case '322':
			{
				$_SESSION['quest1_step']=0;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>�� ������� ��� � �����, ������� ��� � ����� � ���������� ���� ����.';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo '<font size=3 color=#FF0000>�� ��������� �����. �� ������ ����������� ������ ���� ����� ��� ��� ����� ��������� �����';
				QuoteTable('close');
				QuoteTable('open');
				$_SESSION['quest1_exit']=2;
				echo'<a href="?exit">����� ��������</a><br>';
				QuoteTable('close');
				break;
			}
			case '321':
			{
				$_SESSION['quest1_step']=27;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>������� �� ����, �� ���������� �� �������� ������� ��� ��������� ������� ������, �������� ��� ����, � ��� �� ���� ����������, ����� �� ����������� �� ���������� ������ ��� �, ��� ����, ��� � ���� <br>';
				QuoteTable('close');
				QuoteTable('open');
				echo'
				1) <a href="?state=3211">�������, ���� ��� �� ����������, � ��� ����.</a><br>
				2) <a href="?state=3212&boy">��������� ��� � ������ ���������</a><br>';
				QuoteTable('close');
				break;
			}
			case '32120':
			{
				$_SESSION['quest1_step']=29;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>�� ����� ������� ������ � �����, �, ������� ��������� ������ ����, ������� ����� ������, ������ � ������ �� ������, ����� ������.<br>
�� ��������� � ��������� ������� ������������, �� ������ ��������, ��� ��� �����, ������, � �������, ����� ������ ������� ���� �� �������� � ������ � �����. ��� ����� � �����
<br>';
				QuoteTable('close');
				QuoteTable('open');
				echo'
				1) <a href="?state=32121">��������� �����, ���� �� ������</a><br>
				2) <a href="?state=32122">����������� �� ����</a><br>';
				QuoteTable('close');
				break;
			}
			case '32121':
			{
				$_SESSION['quest1_step']=0;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>��������������� ����������, �� ������, ��� �� ��� ������ ����� ��������� ��� ���� ���� ������, ��� � ������� ����������� ��������, ��� ��� �� ������������ ������ �� ���������..';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo '<font size=3 color=#FF0000>�� ��������� �����. �� ������ ����������� ������ ���� ����� ��� ��� ����� ��������� �����';
				QuoteTable('close');
				QuoteTable('open');
				$_SESSION['quest1_exit']=2;
				echo'<a href="?exit">����� ��������</a><br>';
				QuoteTable('close');
				break;
			}
			case '32122':
			{
				$_SESSION['quest1_step']=30;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>����������� �� �������� � ������� �����, �� ����� �� ������������ ����������� �������. � �������� �������, �� ����� ������, ������ ���� �������, ��� ����� � � ��������, � ���, � �������, ��� ������. ����������� �� ���������, ��� ��� ������ ��������� ������. ��, ����� �����<br>';
				QuoteTable('close');
				QuoteTable('open');
				echo'
				1) <a href="?state=321221&boy">��������� � ������� � ���������� ���� ����������.</a><br>
				2) <a href="?state=321222">�������� � ������� ������.</a><br>
				3) <a href="?state=321223">������� &quot;�-�, ��������, �, �������, ������ ������&quot; �, ��������� �����, ������ ���������� ������� �� �����.</a><br>';
				QuoteTable('close');
				break;
			}
			case '321223':
			{
				$_SESSION['quest1_step']=0;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>����� �� ����� � ��������������� ����������, �� ������, ��� ��� �� �� ����� ���������� �� ����� ������������, ��� ��� �� ������������ ������ �� ���������..';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo '<font size=3 color=#FF0000>�� ��������� �����. �� ������ ����������� ������ ���� ����� ��� ��� ����� ��������� �����';
				QuoteTable('close');
				QuoteTable('open');
				$_SESSION['quest1_exit']=2;
				echo'<a href="?exit">����� ��������</a><br>';
				QuoteTable('close');
				break;
			}
			case '3212210':
			{
				$_SESSION['quest1_step']=32;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>����� ������� � ��������������� ��� ��������� �� ����������, �������, ������ �� ��� � �������������� ������. ��, ������� ���� �����! � ������� �������� ��� ���� �����. ��������� ������, �� ������� � ���, � ������������ � ��������� ��������� ������ - � ����� ��������, �������� � ������� ������������� � ��������� ����� �� ���. ��, ����� �� ��� ��������� ����� ���� ����������� � ��������!<br>';
				QuoteTable('close');
				QuoteTable('open');
				echo'<a href="?boy">��������� ��������</a><br>';
				QuoteTable('close');
				break;
			}
			case '321222':
			{
				$_SESSION['quest1_step']=34;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>����� ����! ������ ��� ������ �������� ������ �� ������!<br>';
				QuoteTable('close');
				QuoteTable('open');
				echo'<a href="?boy">������ �����!</a><br>';
				QuoteTable('close');
				break;
			}
			case '3212222':
			{
				$_SESSION['quest1_step']=36;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>����� ����! ������ ��� ������ �������� ������ �� ������!<br>';
				QuoteTable('close');
				QuoteTable('open');
				echo'<a href="?boy">���������!</a><br>';
				QuoteTable('close');
				break;
			}
			case '3212223':
			{
				$_SESSION['quest1_step']=38;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>����� ����! ������ ��� ������ �������� ������ �� ������!<br>';
				QuoteTable('close');
				QuoteTable('open');
				echo'<a href="?boy">��� �������� � �������?!</a><br>';
				QuoteTable('close');
				break;
			}
			case '3212224':
			{
				$_SESSION['quest1_step']=40;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>����� ����! ������ ��� ������ �������� ������ �� ������!<br>';
				QuoteTable('close');
				QuoteTable('open');
				echo'<a href="?boy">��, ��� �������� ��� ���������!</a><br>';
				QuoteTable('close');
				break;
			}
			case '3212225':
			{
				$_SESSION['quest1_step']=42;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>����� ����! ������ ��� ������ �������� ������ �� ������!<br>';
				QuoteTable('close');
				QuoteTable('open');
				echo'<a href="?boy">���� �� ������ ��� ������� �� ��� �����</a><br>';
				QuoteTable('close');
				break;
			}
			case '3212226':
			{
				$_SESSION['quest1_step']=44;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>����� ����! ������ ��� ������ �������� ������ �� ������!<br>';
				QuoteTable('close');
				QuoteTable('open');
				echo'<a href="?boy">�� �����, � �������, ������, ����� �� ������!</a><br>';
				QuoteTable('close');
				break;
			}
			case '3212227':
			{
				$_SESSION['quest1_step']=32;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>� ���, �������, ��������� �� �����������, ������� ���� ������, ���� ��������. � ������� �������� ��� ���� �����. ��������� ������, �� ������� � ���, � ������������ � ��������� ��������� ������ - � ����� ��������, �������� � ������� ������������� � ��������� ����� �� ���. ��, ����� �� ��� ��������� ����� ���� ����������� � ��������!<br>';
				QuoteTable('close');
				QuoteTable('open');
				echo'<a href="?boy">��������� ��������</a><br>';
				QuoteTable('close');
				break;
			}
			case '4':
			{
				$_SESSION['quest1_step']=46;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>��������� �� �������� � ����� ���������, �� ����������, ��� �� ����� � ��������� ����� � �����. �� � ������ ����� ���� ����������, ������������ �� ����������. ��� �� ����?<br>';
				QuoteTable('close');
				QuoteTable('open');
				echo'
				1) <a href="?state=41">��������� � ����</a><br>
				2) <a href="?state=42">��������� ����</a><br>
				3) <a href="?state=43">������ ���� ������</a><br>';
				QuoteTable('close');
				break;
			}
			case '41':
			{
				$_SESSION['quest1_step']=0;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>�� ���������, ���� ���� � ������ ������� ��� � �������� �������� ������ ������������, �� ����..';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo '<font size=3 color=#FF0000>�� ��������� �����. �� ������ ����������� ������ ���� ����� ��� ��� ����� ��������� �����';
				QuoteTable('close');
				QuoteTable('open');
				$_SESSION['quest1_exit']=2;
				echo'<a href="?exit">����� ��������</a><br>';
				QuoteTable('close');
				break;
			}
			case '42':
			{
				$_SESSION['quest1_step']=46;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>�� ������� � ����� � ����� �����. ����� ��������, ������ ���������.. ��, ��� �������� ��������, �� � �� � ������������, � � �������� ������.. ��� ������?<br>';
				QuoteTable('close');
				QuoteTable('open');
				echo'
				1) <a href="?state=421">������� �� ���</a><br>
				2) <a href="?state=43">������ ���� ������</a><br>';
				QuoteTable('close');
				break;
			}
			case '421':
			{
				$_SESSION['quest1_step']=0;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>���� �� �������� � �����, ������ ����� ������� �������. �� ���� ����� �������� ��� ������� ������, ������� ��� ����� ������������ � ����.';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo '<font size=3 color=#FF0000>�� ��������� �����. �� ������ ����������� ������ ���� ����� ��� ��� ����� ��������� �����';
				QuoteTable('close');
				QuoteTable('open');
				$_SESSION['quest1_exit']=2;
				echo'<a href="?exit">����� ��������</a><br>';
				QuoteTable('close');
				break;
			}
			case '43':
			{
				$_SESSION['quest1_step']=47;
				QuoteTable('open');
				$_SESSION['quest1_code_zamok']='';
				echo '<font size=3 color=#F0F0F0>�������� ������ �����, �� ����������, ������, �������� ����. �������� � ���, ��� �� ������ �����-�� �������� ����������. � ��� ������ ���� ������: � ������ - ���� ������ ������, ����� �����-�� �������,  ��������� � ��������, ����� ����� �����, � � ��������� - ��������� ���� ������. �� ��������� - ���� �� ������� ������ �� ������� �������, �� ���������� ������ �� �������, � ��� �����. ������, ����� ������ ������ ����������. ������, ��� ������, ��������: "������ ������ �������, ��� ������ ����, ������ �������� ����������!" ��������, ��� ���-�� ������ ���������� �������� � ����. �� ��� �� ��� �������?<br>';
				QuoteTable('close');
				QuoteTable('open');
		echo '<font size=3 color=#F0F0F0>';
				$ar = array("<font color=#FF0000>�������</font>","<font color=#FFB400>���������</font>","<font color=#EDFE30>������</font>","<font color=#00CF00>�������</font>","<font color=#35EEEC>�������</font>","<font color=#3F10CE>�����</font>","<font color=#EE3FEC>����������</font>");
				shuffle($ar);
				$_SESSION['quest1_array_color1']=$ar;
				for ($i=0;$i<count($ar);$i++)
				{
					echo '<font color=#F0F0F0>';
					echo ($i+1).') <a href="?state=4310&i='.$i.'">'.$ar[$i].'</a><br>';
				}
		echo '<font color=#F0F0F0>';
				echo'8) <a href="?state=431"><font color=#F0F0F0>����</a><br>';
				QuoteTable('close');
				break;
			}
			case '431':
			{
				$_SESSION['quest1_step']=0;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>�� ������ �� ������ ������ ��� ���� ������� � ���������� ���� ����.';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo '<font size=3 color=#FF0000>�� ��������� �����. �� ������ ����������� ������ ���� ����� ��� ��� ����� ��������� �����';
				QuoteTable('close');
				QuoteTable('open');
				$_SESSION['quest1_exit']=2;
				echo'<a href="?exit">����� ��������</a><br>';
				QuoteTable('close');
				break;
			}
			case '4310':
			{
				$_SESSION['quest1_step']=47;
				QuoteTable('open');
				$_SESSION['quest1_color1']=$_SESSION['quest1_array_color1'][$i];
				if ($_SESSION['quest1_array_color1'][$i]=="<font color=#FF0000>�������</font>") $_SESSION['quest1_code_zamok'].='1';
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>�� ������� '.$_SESSION['quest1_array_color1'][$i];
				QuoteTable('close');
				echo '<font size=3 color=#F0F0F0>�������� ������ �����, �� ����������, ������, �������� ����. �������� � ���, ��� �� ������ �����-�� �������� ����������. � ��� ������ ���� ������: � ������ - ���� ������ ������, ����� �����-�� �������,  ��������� � ��������, ����� ����� �����, � � ��������� - ��������� ���� ������. �� ��������� - ���� �� ������� ������ �� ������� �������, �� ���������� ������ �� �������, � ��� �����. ������, ����� ������ ������ ����������. ������, ��� ������, ��������: "������ ������ �������, ��� ������ ����, ������ �������� ����������!" ��������, ��� ���-�� ������ ���������� �������� � ����. �� ��� �� ��� �������?<br>';
				QuoteTable('close');
				QuoteTable('open');

				$ar = array("������","����","������","������","����","����","������","�����");
				shuffle($ar);
				$_SESSION['quest1_array_priroda']=$ar;
				for ($i=0;$i<count($ar);$i++)
				{
					echo ($i+1).') <a href="?state=43100&i='.$i.'">'.$ar[$i].'</a><br>';
				}
				echo'9) <a href="?state=431">����</a><br>';
				QuoteTable('close');
				break;
			}
			case '43100':
			{
				$_SESSION['quest1_step']=47;
				QuoteTable('open');
				$_SESSION['quest1_priroda']=$_SESSION['quest1_array_priroda'][$i];
				if ($_SESSION['quest1_array_priroda'][$i]=="����") $_SESSION['quest1_code_zamok'].='1';
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>�� ������� '.$_SESSION['quest1_array_priroda'][$i];
				QuoteTable('close');
				echo '<font size=3 color=#F0F0F0>�������� ������ �����, �� ����������, ������, �������� ����. �������� � ���, ��� �� ������ �����-�� �������� ����������. � ��� ������ ���� ������: � ������ - ���� ������ ������, ����� �����-�� �������,  ��������� � ��������, ����� ����� �����, � � ��������� - ��������� ���� ������. �� ��������� - ���� �� ������� ������ �� ������� �������, �� ���������� ������ �� �������, � ��� �����. ������, ����� ������ ������ ����������. ������, ��� ������, ��������: "������ ������ �������, ��� ������ ����, ������ �������� ����������!" ��������, ��� ���-�� ������ ���������� �������� � ����. �� ��� �� ��� �������?<br>';
				QuoteTable('close');
				QuoteTable('open');

				echo '<font size=3 color=#F0F0F0>';
				$ar = array("<font color=#FF0000>�������</font>","<font color=#FFB400>���������</font>","<font color=#EDFE30>������</font>","<font color=#00CF00>�������</font>","<font color=#35EEEC>�������</font>","<font color=#3F10CE>�����</font>","<font color=#EE3FEC>����������</font>");
				shuffle($ar);
				$_SESSION['quest1_array_color2']=$ar;
				for ($i=0;$i<count($ar);$i++)
				{
					echo '<font color=#F0F0F0>';
					echo ($i+1).') <a href="?state=431000&i='.$i.'">'.$ar[$i].'</a><br>';
				}
		echo '<font color=#F0F0F0>';
				echo'8) <a href="?state=431"><font color=#F0F0F0>����</a><br>';
				QuoteTable('close');
				break;
			}
			case '431000':
			{
				$_SESSION['quest1_step']=47;
				QuoteTable('open');
				$_SESSION['quest1_color2']=$_SESSION['quest1_array_color2'][$i];
				if ($_SESSION['quest1_array_color2'][$i]=="<font color=#FF0000>�������</font>") $_SESSION['quest1_code_zamok'].='1';
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>�� ������� '.$_SESSION['quest1_array_color2'][$i];
				QuoteTable('close');
				echo '<font size=3 color=#F0F0F0>�������� ������ �����, �� ����������, ������, �������� ����. �������� � ���, ��� �� ������ �����-�� �������� ����������. � ��� ������ ���� ������: � ������ - ���� ������ ������, ����� �����-�� �������,  ��������� � ��������, ����� ����� �����, � � ��������� - ��������� ���� ������. �� ��������� - ���� �� ������� ������ �� ������� �������, �� ���������� ������ �� �������, � ��� �����. ������, ����� ������ ������ ����������. ������, ��� ������, ��������: "������ ������ �������, ��� ������ ����, ������ �������� ����������!" ��������, ��� ���-�� ������ ���������� �������� � ����. �� ��� �� ��� �������?<br>';
				QuoteTable('close');
				QuoteTable('open');

				$ar = array("���","���","������","�����","���","�����","�������","������");
				shuffle($ar);
				$_SESSION['quest1_array_weapon']=$ar;
				for ($i=0;$i<count($ar);$i++)
				{
					echo ($i+1).') <a href="?state=4310000&i='.$i.'">'.$ar[$i].'</a><br>';
				}
				echo'9) <a href="?state=431">����</a><br>';
				QuoteTable('close');
				break;
			}
			case '4310000':
			{
				$_SESSION['quest1_step']=47;
				QuoteTable('open');
				$_SESSION['quest1_weapon']=$_SESSION['quest1_array_weapon'][$i];
				if ($_SESSION['quest1_array_weapon'][$i]=="���") $_SESSION['quest1_code_zamok'].='1';
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>�� ������� '.$_SESSION['quest1_array_weapon'][$i];
				QuoteTable('close');
				echo '<font size=3 color=#F0F0F0>�������� ������ �����, �� ����������, ������, �������� ����. �������� � ���, ��� �� ������ �����-�� �������� ����������. � ��� ������ ���� ������: � ������ - ���� ������ ������, ����� �����-�� �������,  ��������� � ��������, ����� ����� �����, � � ��������� - ��������� ���� ������. �� ��������� - ���� �� ������� ������ �� ������� �������, �� ���������� ������ �� �������, � ��� �����. ������, ����� ������ ������ ����������. ������, ��� ������, ��������: "������ ������ �������, ��� ������ ����, ������ �������� ����������!" ��������, ��� ���-�� ������ ���������� �������� � ����. �� ��� �� ��� �������?<br>';
				QuoteTable('close');
				QuoteTable('open');

				echo'1) <a href="?state=432">����������� ��� "'.$_SESSION['quest1_color1'].'"-"'.$_SESSION['quest1_priroda'].'"-"'.$_SESSION['quest1_color2'].'"-"'.$_SESSION['quest1_weapon'].'"</a><br>';
				echo'2) <a href="?state=431">����</a><br>';
				QuoteTable('close');
				break;
			}
			case '432':
			{
				if ($_SESSION['quest1_code_zamok']=='1111')
				{
					$_SESSION['quest1_step']=48;
					QuoteTable('open');
					echo '<font size=3 color=#F0F0F0>�� ����� ���, � ��������� ���-�� ����������, � ������ ����� 10 ����� ����������. �� ������� ������ � ��� �� ������������ � ��������� � ��������� ����� ��� ������� �����. ��, �������, ���� ����� �� �������. �� ������, ��� ������ ����� �����-�� ��������, � �������� �� ������� �����, ��-�� ������� ��������� ������� �����. �������� �� ��������, �� ������������� ������ ���� ����������. ���� ���� ����������';
					QuoteTable('close');
					echo '<br><br><br>';
					QuoteTable('open');
					echo'1) <a href="?state=4301">���������� ����� � ����� � �������</a><br>';
					echo'2) <a href="?state=4302">��������� � �������� ��������</a><br>';
					QuoteTable('close');
				}
				else
				{
					$_SESSION['quest1_step']=46;
					QuoteTable('open');
					echo '<font size=3 color=#F0F0F0>������ �� ����������';
					QuoteTable('close');
					echo '<br><br><br>';
					QuoteTable('open');
					echo'1) <a href="?state=43">������� ��� ���� �������</a><br>';
					echo'2) <a href="?state=431">����</a><br>';
					QuoteTable('close');
				}
				break;
			}
			case '4301':
			{
				$_SESSION['quest1_step']=0;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>������� �����, �� ����� �� ������������ ����������� �������. � �������� ������� �� ����� ������ ������ ���� �������, ��� ����� � � ��������, � ���, � �������, ��� ����� �������, ��� ������. ����������� �� ���������, ��� ��� ������ ��������� ������. �� ���������, ��� ��� � ���� �� �����������';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo '<font size=3 color=#FF0000>�� ��������� �����. �� ������ ����������� ������ ���� ����� ��� ��� ����� ��������� �����';
				QuoteTable('close');
				QuoteTable('open');
				$_SESSION['quest1_exit']=1;
				echo'<a href="?exit">����� ��������</a><br>';
				QuoteTable('close');
				break;
			}
			case '4302':
			{
				$_SESSION['quest1_step']=49;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>� �������� �������, �� ����� ������, ������ ���� �������, ��� ����� � � ��������. � ����� �������� ������, � �������, ���������� �������. ����� ��������� ����� �� ����� ������, �, ������, � �������� ������������ ����� ���������� � �������� �� ����� �� ������ ������. �� ���������, ��� � ��������� ��� �� ����������, �� �������� ������� �����, �������������, ��� ����� �������, ���� ����� �������, �� ����� �� �������. ��������� ������ �������������� ����� �� ����� �� ����� � ����������� � ��� ��� �������, �������, �� ������ ������, �������� ������ ��� �������. ��������� ����������� � ���, ��� ����� ��� �� �����, ����� �� ��������.. � ������ ��� ����� 0.8 �����. �� ������ ������� ������, ������ ��� �����, ������ ����� ���� ��� ������ ����� ������ ������������ ������, � �� ����� ���������� �������� � ���� ���� ��������� � �������� ����������, ��� � � ������, ���� ������� ����� �������� ���������� �����. <br>���� ������� �������, ��� � ����� 100% ���������, �� �� ���� ������� ���� �� ������� 0,22 ����� � �������� 28% ���������, �� ������� - 0,07 ����� � 8% ���������, � �� ������ - 0,01 ���� � 1% ���������. ������!';
				$_SESSION['quest1_kirka']=100;
				$_SESSION['quest1_distance']=800;
				$_SESSION['quest1_last_udar']=0;
				$_SESSION['quest1_prelast_udar']=0;
				$_SESSION['quest1_nom_udar']=0;
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo'1) <a href="?state=43020&step=1">������� �������</a><br>';
				echo'2) <a href="?state=43020&step=2">������� �������</a><br>';
				echo'3) <a href="?state=43020&step=3">������� ������</a><br>';
				echo'4) <a href="?state=43024">������� ����� � ����</a><br>';
				QuoteTable('close');
				break;
			}
			case '43020':
			{
				$_SESSION['quest1_step']=50;
				if (isset($step) AND $step>=1 AND $step<=3)
				{
					$_SESSION['quest1_nom_udar']++;
					if ($step==1)
					{
						 $_SESSION['quest1_kirka']-=28;
						 $_SESSION['quest1_distance']-=220;
					}
					elseif ($step==2)
					{
						 $_SESSION['quest1_kirka']-=8;
						 $_SESSION['quest1_distance']-=70;
					}
					elseif ($step==3)
					{
						 $_SESSION['quest1_kirka']-=1;
						 $_SESSION['quest1_distance']-=10;
					}
					$break = 0;
					$rand = mt_rand(0,100);
					if ($_SESSION['quest1_nom_udar']==11 AND $rand<=3) $break=1;
					if ($_SESSION['quest1_nom_udar']==12 AND $rand<=15) $break=1;
					if ($_SESSION['quest1_nom_udar']==13 AND $rand<=30) $break=1;
					if ($_SESSION['quest1_nom_udar']==14 AND $rand<=50) $break=1;
					if ($_SESSION['quest1_nom_udar']==15 AND $rand<=70) $break=1;
					if ($_SESSION['quest1_nom_udar']==16 AND $rand<=75) $break=1;
					if ($_SESSION['quest1_nom_udar']==17 AND $rand<=80) $break=1;
					if ($_SESSION['quest1_nom_udar']==18 AND $rand<=85) $break=1;
					if ($_SESSION['quest1_nom_udar']==19 AND $rand<=90) $break=1;
					if ($_SESSION['quest1_nom_udar']==20 AND $rand<=99) $break=1;

					if (($_SESSION['quest1_prelast_udar']==$step AND $_SESSION['quest1_last_udar']==$step) OR $break==1)
					{
						QuoteTable('open');
						echo '<font size=3 color=#F0F0F0>�������� ������� ������, � ���� ���� - ���! - ���������� ������ ����. �� ������� ����� �, �������� ����������� � ���������� � ����, ���������.';
						QuoteTable('close');
						echo '<br><br><br>';
						QuoteTable('open');
						echo '<font size=3 color=#FF0000>�� ��������� �����. �� ������ ����������� ������ ���� ����� ��� ��� ����� ��������� �����';
						QuoteTable('close');
						QuoteTable('open');
						$_SESSION['quest1_exit']=1;
						echo'<a href="?exit">����� ��������</a><br>';
						QuoteTable('close');
					}
					elseif ($_SESSION['quest1_kirka']>=0 AND $_SESSION['quest1_distance']<=0)
					{
						$_SESSION['quest1_step']=51;
						QuoteTable('open');
						echo '<font size=3 color=#F0F0F0>��������� ���� � - ���� ����� ������ �� ��������� �������������. ��, ��� �������! ��, ��������� �����, �������� � ������� � ��������� ������������, � ��� ������ ���������, ��� �� �� ����. ���� ��� ������� ��������, ������� � �������� �������� � �������������, ������� ������������� ��������� �������, �������� ����� ����� � ����� � �������, ��������, ������.';
						QuoteTable('close');
						echo '<br><br><br>';
						QuoteTable('open');
						echo'<a href="?state=430201&boy">��������� ���</a><br>';
						QuoteTable('close');
					}
					elseif ($_SESSION['quest1_kirka']<=0)
					{
						QuoteTable('open');
						echo '<font size=3 color=#F0F0F0>�, ���! �� ������� �����! ������, ������ ������ ������ ������������ ������� � ��� ���..';
						QuoteTable('close');
						echo '<br><br><br>';
						QuoteTable('open');
						echo '<font size=3 color=#FF0000>�� ��������� �����. �� ������ ����������� ������ ���� ����� ��� ��� ����� ��������� �����';
						QuoteTable('close');
						QuoteTable('open');
						$_SESSION['quest1_exit']=1;
						echo'<a href="?exit">����� ��������</a><br>';
						QuoteTable('close');
					}
					else
					{
						QuoteTable('open');
						echo '<font size=3 color=#F0F0F0>� �������� �������, �� ����� ������, ������ ���� �������, ��� ����� � � ��������. � ����� �������� ������, � �������, ���������� �������. ����� ��������� ����� �� ����� ������, �, ������, � �������� ������������ ����� ���������� � �������� �� ����� �� ������ ������. �� ���������, ��� � ��������� ��� �� ����������, �� �������� ������� �����, �������������, ��� ����� �������, ���� ����� �������, �� ����� �� �������. ��������� ������ �������������� ����� �� ����� �� ����� � ����������� � ��� ��� �������, �������, �� ������ ������, �������� ������ ��� �������. ��������� ����������� � ���, ��� ����� ��� �� �����, ����� �� ��������.. � ������ ��� ����� 0.8 �����. �� ������ ������� ������, ������, ��� �����, ������ ����� ���� ��� ������ ����� ������ ������������ ������, � �� ����� ���������� �������� � ���� ���� ��������� � �������� ����������. ���� ������� �������, ��� � ����� 100% ���������, �� �� ���� ������� ���� �� ������� 0,22 ����� � �������� 28% ���������, �� ������� - 0,07 ����� � 8% ���������, � �� ������ - 0,01 ���� � 1% ���������. ������!';
						QuoteTable('close');
						echo '<br>';
						if ($_SESSION['quest1_nom_udar']>=2)
						{
				$_SESSION['quest1_prelast_udar'] = $_SESSION['quest1_last_udar'];
						}
						QuoteTable('open');
						echo '���������: ��������� ����� <font color=#FF0000>'.(/*100-*/$_SESSION['quest1_kirka']).'<font color=#F0F0F0>%, ������� ����� <font color=#FF0000>'./*round((800-*/$_SESSION['quest1_distance']/*)/1000,2)*/.'<font color=#F0F0F0> ��.';
						QuoteTable('close');
						echo '<br>';
						$_SESSION['quest1_last_udar']=$step;
						QuoteTable('open');
						echo'1) <a href="?state=43020&step=1">������� �������</a><br>';
						echo'2) <a href="?state=43020&step=2">������� �������</a><br>';
						echo'3) <a href="?state=43020&step=3">������� ������</a><br>';
						echo'4) <a href="?state=43024">������� ����� � ����</a><br>';
						QuoteTable('close');

					}
				}
				break;
			}
			case '43024':
			{
				$_SESSION['quest1_step']=0;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>�� �������� � ����� ����������� ���� � ����� ����� �������.';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo '<font size=3 color=#FF0000>�� ��������� �����. �� ������ ����������� ������ ���� ����� ��� ��� ����� ��������� �����';
				QuoteTable('close');
				QuoteTable('open');
				$_SESSION['quest1_exit']=1;
				echo'<a href="?exit">����� ��������</a><br>';
				QuoteTable('close');
				break;
			}
			case '5':
			{
				$_SESSION['quest1_step']=0;
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>�������� ��������� �������� �������� "������ ������ - ������ ���������", �� ������ �� ����������� �� �������� ��������� � ����� ����� �������.';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				echo '<font size=3 color=#FF0000>�� ��������� �����. �� ������ ����������� ������ ���� ����� ��� ��� ����� ��������� �����';
				QuoteTable('close');
				QuoteTable('open');
				$_SESSION['quest1_exit']=1;
				echo'<a href="?exit">����� ��������</a><br>';
				QuoteTable('close');
				break;
			}
			case '777':
			{
				QuoteTable('open');
				echo '<font size=3 color=#F0F0F0>�������, �� ���� ����� �������� ������ ����� �� ���� � - ��! - ������ ������� �����! �� ��������� ������ �������� ��� ���������� ����� � ������� � ������. �� ��� ��� �������, � ���� ����� ���� �������, �� ���������� ����� �� ������ ���������, ������� ��� ������� ������������ ����������, � ������� &quot;��� ��� �������!&quot;';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				$_SESSION['quest1_exit']=99;
				echo '<font size=3 color=#F0F0F0>����������!<br>';
				echo'<a href="?exit">����� �������!</a><br>';
				QuoteTable('close');
				echo '<br><br><br>';
				QuoteTable('open');
				if ((isset($_SESSION['quest1_get_gp']) AND $_SESSION['quest1_get_gp']!=0)OR(isset($_SESSION['quest1_take_weapon']) AND $_SESSION['quest1_take_weapon']==1)OR(isset($_SESSION['quest1_take_shlem']) AND $_SESSION['quest1_take_shlem']==1))
				{
					if (isset($_SESSION['quest1_get_gp']) AND $_SESSION['quest1_get_gp']!=0)
					{
						echo '�� ���������� '.$_SESSION['quest1_get_gp'].' ������� �����<br>';
					}
					if (isset($_SESSION['quest1_take_weapon']) AND $_SESSION['quest1_take_weapon']==1)
					{
						list($item_name) = mysql_fetch_array(myquery("SELECT name FROM game_items_factsheet WHERE id=4"));
						echo '�� �������� �������: '.$item_name.'<br>';
					}
					if (isset($_SESSION['quest1_take_shlem']) AND $_SESSION['quest1_take_shlem']==1)
					{
						list($item_name) = mysql_fetch_array(myquery("SELECT name FROM game_items_factsheet WHERE id=8"));
						echo '�� �������� �������: '.$item_name.'<br>';
					}
				}
				echo '�� �������� '.$get_exp.' ����� �����<br>';
				QuoteTable('close');
				break;
			}
		}
		echo '</p>';
		OpenTable('close');
	}
	else
	{
		$sost = mysql_fetch_array(myquery("SELECT sost,finish FROM game_quest_users WHERE user_id=$user_id AND quest_id=1 AND (last_time>=$last_time OR sost>0)"));
		if ($sost['sost']==2)
		{
			//������� ������� �� ����� ��� � ����� � ����� �������
			//���� �������� ��������� � ����� �������� ����� ������, ���� finish==0 ��� ��������� �����
			if ($sost['finish']==0)
			{
				myquery("UPDATE game_quest_users SET sost=0,last_time=0 WHERE user_id=$user_id AND quest_id=1");   
			}
			ForceFunc($user_id,5);
			setLocation("../act.php");
		}
	}
}

//OpenTable('title');
//echo '<a href="?return">� ������ ������</a>';
//OpenTable('close');

//OpenTable('title');
//echo '<a href="?exit_from_quest">����� �� ������</a>';
//OpenTable('close');
?>