<?php

include('inc/config.inc.php');
include('inc/lib.inc.php');
require_once('inc/db.inc.php');
if (!defined("NO_FUNC_CHECK"))
{
	define("NO_FUNC_CHECK", '1');
}
else
{
// � ��� �� ������� �� ������ ���������
	die();
}
include('inc/lib_session.inc.php');
include('inc/functions.php');


if (function_exists("start_debug")) start_debug();

if (!isset($_REQUEST['exit']))
{
    include('inc/template_header.inc.php');
    echo'
    <style>
    BODY {
      background: black url("http://'.img_domain.'/nav/story-content-bg2.gif");
      background-repeat: repeat;
    }
    </style>';
    echo '<meta http-equiv="refresh" content="2;url=logout.php?exit">';
    echo "<body><center><br><br><b><font color=\"yellow\">�� ����� �� ����. ������� � ��� ��� ���!</font></b></center></body></html>";
}
else
{
    $result = myquery("UPDATE game_users_active SET last_active=".(time()-PHPRPG_SESSION_EXPIRY).",chat_active=0 WHERE user_id=$user_id LIMIT 1");
    session_unset();
    session_destroy();
    if (!headers_sent())
    {
        setcookie("rpgsu_login",0,time()-84000,"/");
        setcookie("rpgsu_pass",0,time()-84000,"/");
        setcookie("rpgsu_sess",0,time()-84000,"/");
    }
    include('inc/template_header.inc.php');
    echo "<body onLoad='parent.location.href=\"index.php\"'></body></html>";
}
{if (function_exists("save_debug")) save_debug(); exit;}
?>