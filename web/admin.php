<?php
//ob_start('ob_gzhandler',9);
require('inc/config.inc.php');
if (function_exists("start_debug")) start_debug(); 
include('inc/lib.inc.php');
require_once('inc/db.inc.php');

if (function_exists("start_debug")) start_debug(); 

//����������� �������� �������
function add_admin_log($char, $mes)
{
	$da = getdate();
	$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
			VALUES (
			 '".$char['name']."',
			  '".$mes."',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());
}

if (isset($_GET['option']) and $_GET['option']=='activate' and isset($_GET['host_admin']) and isset($_GET['validate']) and isset($_GET['user_id']))
{
	$sel = myquery("SELECT * FROM game_admins_ip WHERE host=".$_GET['host_admin']." AND validate='".$_GET['validate']."' AND user_id=".$_GET['user_id']."");
	if (!mysql_num_rows($sel))
	{
		echo '������ ���������';
		die();
	}
	else
	{
		myquery("DELETE FROM game_admins_ip WHERE user_id=".$_GET['user_id']."");
		myquery("INSERT INTO game_admins_ip SET validate='',host=".$_GET['host_admin'].",user_id=".$_GET['user_id']."");
		echo '���� IP ����� ������� �����������';
		die();
	}
}

include("class/class_email.php");
require('inc/lib_session.inc.php');  

if (isset($_GET['opt']) AND $_GET['opt']=='main' AND isset($_GET['option']) AND $_GET['option']=='enter_user' AND isset($_POST['name_v']))
{
    $user_name = $_POST['name_v'];
    $sel = myquery("SELECT * FROM game_users WHERE name='$user_name' LIMIT 1");
    if (!mysql_num_rows($sel))
    {
         $sel = myquery("SELECT * FROM game_users_archive WHERE user_name='$user_name' LIMIT 1");
         if (mysql_num_rows($sel))
         {
             $up = myquery("INSERT INTO game_users SELECT * FROM game_users_archive WHERE user_name='$user_name' LIMIT 1");
             $up = myquery("DELETE FROM game_users_archive WHERE user_name='$user_name'");
         }
    }
    $sel = myquery("SELECT * FROM game_users WHERE name='$user_name'");
    if (!mysql_num_rows($sel))
    {
         //echo '����� �� ������!';
    }
    else
    {
        $user=mysql_fetch_array($sel);
        if ($user['clan_id']==1) 
        {
            //echo '������ �������� �� ����������';
        }
        else
        {
            $sess = md5(uniqid(mt_rand()));
            $user_id = $user['user_id'];
            $_SESSION['user_id'] = $user['user_id'];
            setcookie("rpgsu_login", $user['user_name'],0,"/");
            setcookie("rpgsu_pass",md5($user['user_pass']),0,"/");
            //setcookie("rpgsu_sess", $sess,0,"/");
            //$_COOKIE['rpgsu_sess'] = $sess;
            $_COOKIE['rpgsu_pass'] = md5($user['user_pass']);
            $_COOKIE['rpgsu_login'] = $user['user_name'];
        }
    }
}


$result=myquery("SELECT * FROM game_admins WHERE user_id=".$user_id." LIMIT 1");
if (mysql_num_rows($result) == 0)
{
	setLocation('index.php');
	{if (function_exists("save_debug")) save_debug(); exit;}
}
require('inc/template_header.inc.php');

//�������� �� IP
if (domain_name!='localhost')
{
	$host = mysqlresult(myquery("SELECT host FROM game_users_active WHERE user_id='$user_id'"),0,0);
	$sel = myquery("SELECT * FROM game_admins_ip WHERE host='$host' AND validate='' AND user_id='$user_id' LIMIT 1");
	if (!mysql_num_rows($sel))
	{
		//�������� ������ � ����������
		$validation_string = '';
		mt_srand((double) microtime() * 100000);
		for ($i = 0; $i < 10; $i++)
		{
			$rand_val = mt_rand(65, 100);
			if ($rand_val > 90)
			{
				$rand_val = $rand_val - 91;
				$validation_string .= $rand_val;
			}
			else
			{
				$validation_string .= chr($rand_val);
			}
		}

		$email = mysqlresult(myquery("SELECT email FROM game_users_data WHERE user_id='$user_id'"),0,0);
		$result = myquery("INSERT INTO game_admins_ip (host, validate, user_id) VALUES (".$host.",'$validation_string',".$user_id.")") or die(mysql_error());

		$message = "������� ������ ��� ������������� ����������� ������ IP ������ �������������� - '".number2ip($host)."':\n\n";
		$message .= "http://".domain_name."/admin.php?option=activate&user_id=".$user_id."&host_admin=".$host."&validate=$validation_string\n\n";

		$subject = '���������� :: ����� �������� [��������� ��� ��������������] - '.domain_name.'';

		$e_mail = new emailer();
		$e_mail->email_init();
		$e_mail->to = $email;
		$e_mail->subject = $subject;
		$e_mail->message = $message;
		$e_mail->send_mail();

		echo '<font size=3 color=#FFFF00><br><br><center>���� IP ����� - <b>'.number2ip($host).'</b> - ����������� � ���� ������ IP ������� ���������������';
		echo '<br><br>�� ���� �������� ���� �������������� ���� ������� ������ � ���������� ������ IP ������';
		{if (function_exists("save_debug")) save_debug(); exit;}
	}
}

$adm=mysql_fetch_array($result);
if (!isset($_GET['opt']))
{
	$opt = '';
}
else
{
	$opt = $_GET['opt'];
}

switch (@$opt)
{
	default:
	echo'<frameset rows="*" cols="240,*" framespacing="0" frameborder="NO" border="0">
	<frame src="admin.php?opt=menu" name="left" scrolling="no" noresize>
	<frame src="admin.php?opt=main" name="main">
	</frameset>
	<noframes><body></body></noframes></html>';
	break;

	case 'menu':
	$img='http://'.img_domain.'/race_table/human/table';
	echo'<table width=100% border="0" cellspacing="0" cellpadding="0"><tr><td width="1" height="1"><img src="'.$img.'_lt.gif"></td><td background="'.$img.'_mt.gif"></td><td width="1" height="1"><img src="'.$img.'_rt.gif"></td></tr>
<tr><td background="'.$img.'_lm.gif"></td><td background="'.$img.'_mm.gif" valign="top" width="100%" height="100%">';


	echo'

<table class="adminform" width="100%" border=0>
<tr><td width="250" valign="top">

<link id="luna-tab-style-sheet" type="text/css" rel="stylesheet" href="style/tabs/tabpane.css" />
<script type="text/javascript" src="style/tabs/tabpane.js"></script>
<div class="tab-page" id="modules-cpanel"><script type="text/javascript">var tabPane1 = new WebFXTabPane( document.getElementById( "modules-cpanel" ), 1 )</script>
<div class="tab-page" id="module33"><h2 class="tab">����</h2><script type="text/javascript">tabPane1.addTabPage( document.getElementById( "module33" ) );</script>';

if ($adm['online'] >= 1)
{
  echo'&nbsp;&nbsp;<a href="?opt=main&option=ip&sort=host" target="main">������</a><br>';
}
if ($adm['teleport'] >= 1)
{
  echo'&nbsp;&nbsp;<a href="?opt=main&option=teleport" target="main">��������</a><br>';
}

if ($adm['news'] >= 1)
{
  echo'&nbsp;&nbsp;<a href="?opt=main&option=news" target="main">�������� ��������</a><br>';
}

if ($adm['zakon'] >= 1)
{
  echo'&nbsp;&nbsp;<a href="?opt=main&option=zakon" target="main">������ C���������</a><br>';
}

if ($adm['help'] >= 1)
{
  echo'&nbsp;&nbsp;<a href="?opt=main&option=help" target="main">������</a><br>';
}

if ($adm['stat'] >= 1)
{
  echo'&nbsp;&nbsp;<a href="?opt=main&option=stat" target="main">���������� IP</a><br>';
}

if ($adm['forum'] >= 2)
{
  echo'&nbsp;&nbsp;<a href="?opt=main&option=forum" target="main">���������� �������</a><br><br>';
}

if ($adm['pm'] >= 1)
{
  echo'&nbsp;&nbsp;<a href="?opt=main&option=pm" target="main">�����</a><br>';
}
if ($adm['log_war'] >= 1)
{
  echo'&nbsp;&nbsp;<a href="?opt=main&option=log_war" target="main">������� ��� ����</a><br>';
}

if ($adm['log_war_today'] >= 1)
{
  echo'&nbsp;&nbsp;<a href="?opt=main&option=log_war2" target="main">���� ���� �� �������</a><br>';
}

if ($adm['statall'] >= 1)
{
  echo'&nbsp;&nbsp;<a href="?opt=main&option=statall" target="main">���������� ����</a><br>';
}
if ($adm['log_adm'] >= 1)
{
  echo'&nbsp;&nbsp;<a href="?opt=main&option=log_adm" target="main">��� �������� �������</a><br>';
}

if ($adm['mag'] >= 1)
{
  echo'&nbsp;&nbsp;<a href="?opt=main&option=mag" target="main">���������� ����</a><br>';
}



if ($adm['chat'] >= 1)
{
  echo'<br>&nbsp;&nbsp;<a href="?opt=main&option=chat" target="main">���</a><br>';
}

if ($adm['bot_combat'] >= 1)
{
  echo'&nbsp;&nbsp;<a href="?opt=main&option=bot_combat" target="main">��� (���)</a><br>';
}

if ($adm['bot_chat'] >= 1)
{
  echo'&nbsp;&nbsp;<a href="?opt=main&option=bot_chat" target="main">��� (���)</a><br>';
}

/* ������� ������ ��� ����� �������: */
if($char['clan_id']==1 or $char['name']=='mrHawk')
{
  echo'<br>&nbsp;&nbsp;<a href="?opt=main&option=dom" target="main">���������� �������</a><br>';
  echo'&nbsp;&nbsp;<a href="?opt=main&option=const" target="main">��������� ��������</a><br>';

}


/* ������� ������ ��� ���������: */
if ($char['name'] == 'blazevic' OR $char['name']=='The_Elf' OR $char['name']=='Zander'  OR $char['name']=='Victor' OR $char['name']=='High_Elf' OR $char['name']=='Stream_Dan' OR $char['name']=='mrHawk' or (domain_name == 'testing.rpg.su' and $char['name']=='bruser'))
{
	echo'<br><br>������� ��� ����������<br>';
	echo'&nbsp;&nbsp;<a href="?opt=main&option=prava" target="main">������� ����</a><br>';
	echo'&nbsp;&nbsp;<a href="?opt=main&option=functadm" target="main">�������</a><br>';
}

if (domain_name == 'testing.rpg.su' or domain_name == 'localhost')
{
	echo'<br><br>������� ��� ������������<br>';
	echo'&nbsp;&nbsp;<a href="?opt=main&option=functtest" target="main">�������</a><br>';
}

/* ������� ������ ��� �������: */
if ($char['name'] == 'blazevic' OR $char['name']=='The_Elf' OR $char['name']=='mrHawk' or domain_name == 'localhost')
{
	echo'<br><br>������� ��� �������<br>';
	echo'&nbsp;&nbsp;<a href="?opt=main&option=funct" target="main">���������</a><br>';
	echo'&nbsp;&nbsp;<a href="?opt=main&option=cron_st" target="main">���������� �����</a><br>';
}

echo'</div>

<div class="tab-page" id="module19"><h2 class="tab">������.</h2><script type="text/javascript">tabPane1.addTabPage( document.getElementById( "module19" ) );</script>';

if ($adm['npc'] >= 1)
{
  echo'&nbsp;&nbsp;<a href="?opt=main&option=npc" target="main">������� ����</a><br>';
  echo'&nbsp;&nbsp;<a href="?opt=main&option=npc_template" target="main">������� �����</a><br><br>';
}
if ($adm['npc'] >= 3)
{
  //echo'&nbsp;&nbsp;<a href="?opt=main&option=npc_drop" target="main">���� � �����</a><br>';
}

if ($adm['items'] >= 1)
{
  echo'&nbsp;&nbsp;<a href="?opt=main&option=items" target="main">��������</a><br>';
  echo'&nbsp;&nbsp;<a href="?opt=main&option=eliksir" target="main">��������</a><br>';
}

if ($adm['search_items'] >= 1)
{
  echo'&nbsp;&nbsp;<a href="?opt=main&option=search" target="main">����� ���������</a><br>';
}

if ($adm['resource'] >= 1)
{
	echo'&nbsp;&nbsp;<a href="?opt=main&option=resource" target="main">�������</a><br><br>';
}

if ($adm['map'] >= 1)
{
  echo'&nbsp;&nbsp;<a href="lib/map_editor.php" target="main">�������� ����</a><br>';
  echo'&nbsp;&nbsp;<a href="?opt=main&option=maze" target="main">�������� ����������</a><br>';
}

if ($adm['gorod'] >= 1)
{
  echo'&nbsp;&nbsp;<a href="?opt=main&option=gorod" target="main">������</a><br>';
  echo'&nbsp;&nbsp;<a href="?opt=main&option=port" target="main">�����</a><br>';
}

if ($adm['tavern'] >= 1)
{
  echo'&nbsp;&nbsp;<a href="?opt=main&option=tavern" target="main">�������</a><br>';
}

if ($adm['shop'] >= 1)
{
  echo'&nbsp;&nbsp;<a href="?opt=main&option=shop" target="main">��������</a><br>';
  echo'&nbsp;&nbsp;<a href="?opt=main&option=exchange" target="main">�������� �����</a><br>';
}

if ($adm['mine'] >= 1)
{
	echo'&nbsp;&nbsp;<a href="?opt=main&option=craft" target="main">��������� ������</a><br>';
	echo'&nbsp;&nbsp;<a href="?opt=main&option=obelisk" target="main">��������</a><br>';
}

if ($adm['spets'] >= 1)
{
  echo'<br>&nbsp;&nbsp;<a href="?opt=main&option=skill" target="main">�������������</a><br>';
  echo'&nbsp;&nbsp;<a href="?opt=main&option=spells" target="main">����������</a><br>';
}

if ($char['clan_id']==1 or $char['name']=='mrHawk')
{	
	echo'<br>&nbsp;&nbsp;<a href="?opt=main&option=craft_user" target="main">����� �������</a><br>';
	echo'&nbsp;&nbsp;<a href="?opt=main&option=house" target="main">���� � ���������</a><br>';	
	echo'<br>&nbsp;&nbsp;<a href="?opt=main&option=race" target="main">����</a><br>';
}

if ($adm['koni'] >= 1)
{
  echo'&nbsp;&nbsp;<a href="?opt=main&option=koni" target="main">����</a><br>';
}
if ($adm['lr'] >= 1)
{
  echo'&nbsp;&nbsp;<a href="?opt=main&option=lr_services" target="main">������ �� ��</a><br>';
}

if ($adm['quest'] >= 1)
{
  //������ �������
  echo'<br>&nbsp;&nbsp;<a href="?opt=main&option=quest_topics" target="main">������ ��� �������</a><br>';
  echo'&nbsp;&nbsp;<a href="?opt=main&option=quest" target="main">������</a><br>';
  echo'&nbsp;&nbsp;<a href="?opt=main&option=quest_moria" target="main">������ ���������� �����</a><br>';
  echo'&nbsp;&nbsp;<a href="?opt=main&option=bookgame" target="main">�����-����</a><br>';
}
echo'</div>


<div class="tab-page" id="module20"><h2 class="tab">������</h2><script type="text/javascript">tabPane1.addTabPage( document.getElementById( "module20" ) );</script>';

if ($adm['ban'] >= 1)
{
echo'&nbsp;&nbsp;<a href="?opt=main&option=ban" target="main">��������� ���</a><br>';
}

if ($adm['unban'] >= 1)
{
echo'&nbsp;&nbsp;<a href="?opt=main&option=unban" target="main">����� ���</a><br>';
}

if ($adm['ban'] >= 2)
{
echo'&nbsp;&nbsp;<a href="?opt=main&option=bandiap" target="main">���� �� ���������</a><br><br>';
}

if ($adm['pech'] >= 1)
{
echo'&nbsp;&nbsp;<a href="?opt=main&option=pech" target="main">��������� ������</a><br>';
}

if ($adm['unpech'] >= 1)
{
echo'&nbsp;&nbsp;<a href="?opt=main&option=unpech" target="main">����� ������</a><br>';
}

if ($adm['nakaz'] >= 1)
{
echo'&nbsp;&nbsp;<a href="?opt=main&option=nakaz" target="main">��������� �������</a><br>';
}
/*
if ($adm['lab'] >= 1)
{
echo'&nbsp;&nbsp;<a href="?opt=main&option=lab" target="main">��������� � ��������</a><br>';
}

if ($adm['unlab'] >= 1)
{
echo'&nbsp;&nbsp;<a href="?opt=main&option=unlab" target="main">�� ���������</a><br>';
}
*/
if ($adm['del'] >= 1)
{
echo'<br>&nbsp;&nbsp;<a href="?opt=main&option=show_reg" target="main">����������� �� ����</a><br>';
echo'&nbsp;&nbsp;<a href="?opt=main&option=users_psg" target="main">��������� � ���</a><br>';
echo'&nbsp;&nbsp;<a href="?opt=main&option=users_del" target="main">������� ���������</a><br>';
}  

if ($adm['bank'] >= 1)
{
echo'<br>&nbsp;&nbsp;<a href="?opt=main&option=gp_stat" target="main">���������� �����</a><br>';
echo'&nbsp;&nbsp;<a href="?opt=main&option=bank" target="main">������ � �����</a><br><br>';
}

if ($adm['medal'] >= 1)
{
echo'&nbsp;&nbsp;<a href="?opt=main&option=medal" target="main">������ ��� �������</a><br>';
}
if ($adm['users'] >= 1)
{
echo'&nbsp;&nbsp;<a href="?opt=main&option=brak" target="main">�������� ���������</a><br>';
echo'<br>&nbsp;&nbsp;<a href="?opt=main&option=users" target="main">�������� ������</a><br>';
}

if ($adm['users'] == 2)
{
echo'&nbsp;&nbsp;<a href="?opt=main&option=enter_user" target="main">����� ��� ������ �������</a><br>';
}

if ($adm['search_users'] >= 1)
{
echo'&nbsp;&nbsp;<a href="?opt=main&option=search_users" target="main">����� �������</a><br>';
}
echo'</div></td></tr></table>';

echo'<table width="100%" border="0" cellspacing="1" cellpadding="0" align="center">';

echo'<tr><td colspan=2><center>10 ��������� �����������������</center></td></tr>';
$game=myquery("SELECT * from game_users order by user_id DESC limit 10");
echo'<table border=0 align=center>';
while ($elf = mysql_fetch_array($game))
{
	echo'<tr bgcolor="#333333"><td>'.$elf['user_id'].'</td><td width="90%" height="15"><div align="center"><font size="1" face="Verdana">'.$elf['name'].'</b></font></div></td></tr>';
}
echo'</table>';

echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
break;


/* ������� */
case 'main':
  if (!isset($_GET['page']))
    $page=1;
  else
    $page=(int)$_GET['page'];

	$img='http://'.img_domain.'/race_table/human/table';
	echo'<table width=100% border="0" cellspacing="0" cellpadding="0"><tr><td width="1" height="1"><img src="'.$img.'_lt.gif"></td><td background="'.$img.'_mt.gif"></td><td width="1" height="1"><img src="'.$img.'_rt.gif"></td></tr><tr><td background="'.$img.'_lm.gif"></td><td background="'.$img.'_mm.gif" valign="top" width="100%" height="100%">';
	echo'
	<script type="text/javascript">
	/* URL to the PHP page called for receiving suggestions for a keyword*/
	var getFunctionsUrl = "suggest/suggest.php?keyword=";
	</script>';
	echo'<link href="style/admin/tables.css" rel="stylesheet" type="text/css">';
	echo'<link href="suggest/suggest.css" rel="stylesheet" type="text/css">';
	echo'<script type="text/javascript" src="suggest/suggest.js"></script>';

	if (!isset($_GET['option'])) $_GET['option']='ip';
	switch ($_GET['option'])
	{
		case 'prison':
		include('inc/admin/prison.inc.php');
		break;

		case 'unprison':
		include('inc/admin/unprison.inc.php');
		break;
		
		case 'ip':
		include('inc/admin/ip.inc.php');
		break;

		case 'teleport':
		include('inc/admin/teleport.inc.php');
		break;

		case 'news':
		include('inc/admin/news.inc.php');
		break;

		case 'del_news':
		include('inc/admin/del_news.inc.php');
		break;

		case 'edit_news':
		include('inc/admin/edit_news.inc.php');
		break;

		case 'add_news':
		include('inc/admin/add_news.inc.php');
		break;

		case 'zakon':
		include('inc/admin/zakon.inc.php');
		break;

		case 'help':
		include('inc/admin/help.inc.php');
		break;

		case 'stat':
		include('inc/admin/stat.inc.php');
		break;

		case 'ban':
		include('inc/admin/ban.inc.php');
		break;

		case 'bandiap':
		include('inc/admin/bandiap.inc.php');
		break;
		
		case 'pech':
		include('inc/admin/pech.inc.php');
		break;
/*
                ��� ��� �� ��������

		case 'lab':
		include('inc/admin/lab.inc.php');
		break;

		case 'unlab':
		include('inc/admin/unlab.inc.php');
		break;
*/
		case 'race':
		include('inc/admin/race.inc.php');
		break;
		
		case 'skill':
		include('inc/admin/skill.inc.php');
		break;
		
		case 'spells':
		include('inc/admin/spells.inc.php');
		break;
		
		case 'craft_user':
		include('inc/admin/craft_user.inc.php');
		break;
		
		case 'house':
		include('inc/admin/house.inc.php');
		break;

		case 'unban':
		include('inc/admin/unban.inc.php');
		break;

		case 'unpech':
		include('inc/admin/unpech.inc.php');
		break;
		case 'npc':
		include('inc/admin/npc.inc.php');
		break;

		case 'log':
		include('inc/admin/log.inc.php');
		break;

		case 'log_war':
		include('inc/admin/log_comb.inc.php');
		break;

		case 'log_war2':
		include('inc/admin/log_comb2.inc.php');
		break;

		case 'statall':
		include('inc/admin/statall.inc.php');
		break;
		
		case 'log_adm':
		include('inc/admin/log_adm.inc.php');
		break;

		case 'users_psg':
		include('inc/admin/psg.inc.php');
		break;	
			
		case 'users_del':
		include('inc/admin/del.inc.php');
		break;

		case 'prava':
		  if ($char['name'] == 'blazevic' OR 
                      $char['name']=='The_Elf' OR 
                      $char['name']=='Zander'  OR 
                      $char['name']=='Victor' OR 
                      $char['name']=='High_Elf' OR 
                      $char['name']=='Stream_Dan' OR
					  $char['name']=='mrHawk')
		  {
			include('inc/admin/prava.inc.php');
		  }
		break;
		
		case 'funct':
		include('inc/admin/funct.inc.php');
		break;
		
		case 'cron_st':
		include('inc/admin/cron_st.inc.php');
		break;

		case 'bank':
		include('inc/admin/bank.inc.php');
		break;

		case 'quest':
		include('inc/admin/quest.inc.php');
		break;

		case 'bookgame':
		include('inc/admin/bookgame.inc.php');
		break;

		case 'bookgamepage':
		include('inc/admin/bookgamepage.inc.php');
		break;

		//������ �������
		case 'quest_topics':
		include('inc/admin/quest_engine_admin.inc.php');
		break;
		
		case 'items':
		include('inc/admin/items.inc.php');
		break;
		
		case 'eliksir':
		include('inc/admin/eliksir.inc.php');
		break;
		
		case 'users':
		include('inc/admin/users.inc.php');
		break;

		case 'gorod':
		include('inc/admin/gorod.inc.php');
		break;

		case 'gorod_option':
		include('inc/admin/gorod_option.inc.php');
		break;

		case 'shop':
		include('inc/admin/shop.inc.php');
		break;
		
		case 'exchange':
		include('inc/admin/exchange.inc.php');
		break;

		case 'pm':
		include('inc/admin/pm.inc.php');
		break;

		case 'tavern':
		include('inc/admin/tavern.inc.php');
		break;

		case 'forum':
		include('inc/admin/forum.inc.php');
		break;

		case 'search':
		include('inc/admin/search_item.inc.php');
		break;

		case 'search_users':
		include('inc/admin/search_user.inc.php');
		break;

		case 'medal':
		include('inc/admin/medal.inc.php');
		break;

		case 'medal_game':
		include('inc/admin/medal_game.inc.php');
		break;

		case 'mag':
		include('inc/admin/mag.inc.php');
		break;

		case 'nakaz':
		include('inc/admin/nakaz.inc.php');
		break;

		case 'koni':
		include('inc/admin/koni.inc.php');
		break;

		case 'chat':
		include('inc/admin/chat.inc.php');
		break;

		case 'resource':
		include('inc/admin/resource.inc.php');
		break;

		case 'mine':
		include('inc/admin/mine.inc.php');
		break;

		case 'bot_combat':
		include('inc/admin/bot_combat.inc.php');
		break;

		case 'bot_chat':
		include('inc/admin/bot_chat.inc.php');
		break;

		// ����������
		case 'gp_stat':
		include('inc/admin/gp_stat.inc.php');
		break;

		case 'port':
		include('inc/admin/port.inc.php');
		break;

		case 'obelisk':
		include('inc/admin/obelisk.inc.php');
		break;

		case 'dom':
		include('inc/gorod/dom.inc.php');
		break;

		case 'maze':
		include('inc/admin/maze.inc.php');
		break;

		case 'activate':
		include('inc/admin/activate.inc.php');
		break;

		case 'craft':
		include('inc/admin/craft.inc.php');
		break;

		case 'brak':
		include('inc/admin/brak.inc.php');
		break;

		case 'show_reg':
		include('inc/admin/show_reg.inc.php');
		break;

		case 'npc_template':
		include('inc/admin/npc_template.inc.php');
		break;
		
		case 'lr_services':
		include('inc/admin/lr_services.inc.php');
		break;
		
		case 'functadm':
		include('inc/admin/functadm.inc.php');
		break;
		
		case 'functtest':
		include('inc/admin/functtest.inc.php');
		break;
		
		case 'quest_moria':
		include('inc/admin/quest_moria.inc.php');
		break;

		case 'enter_user':
		include('inc/admin/enter_user.inc.php');
		break;

		case 'const':
		include('inc/admin/const.inc.php');
		break;

	}
	echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
}
mysql_close();

show_debug($char['name']);

if (function_exists("save_debug")) save_debug(); 

?>