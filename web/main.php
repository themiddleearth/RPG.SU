<?
include('inc/config.inc.php');
include('inc/lib.inc.php');
require_once('inc/db.inc.php');
// ��������� NO_FUNC_CHECK ������� ��� ����, ����� ��� �������� main.php
// �� ���������� func � �� ������� setLocation 
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

if ($_SERVER['PHP_SELF']!="/main.php")
{
	die();
}
?>
<title>���������� :: ����� �������� :: RPG online ���� �� �������� "��������� �����"</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<meta name="description" content="��������������������� RPG OnLine ���� �� �������� ��.�.�.�������� '��������� �����' - ������ ������� ���� �� ������������� ������������">
<meta name="Keywords" content="����������, ���������, �����, �������, Lord, of, the, Rings, rpg, �������, �������, ������, ����, �����, ��������, online, game, ��������, ���, �������, �����, �����, ��, �������, ����, ������, �������, ��������, internet, fantasy, ���, �����, �����, �����, ����, ����, �����, ������, ����������, ����, ���, �����, �����, ��������, �����������, �����������, ����������, ������, ���, ����������, ���, ������, �����, ����, ����, bk, games, ����, ����, �������, ����">
<script language="JavaScript" type="text/javascript" src="js/cookies.js"></script>
<style type="text/css">@import url("style/global.css");</style>
<?
if ($char['view_chat']==1 AND $char['map_name']!=666)
{
	$select=myquery("select * from game_chat_option where user_id='$user_id'");
	$chato=mysql_fetch_array($select);
	if ($chato['frame']<220) $chato['frame']=220;
	echo '<frameset id="frame_set" rows="*,'.$chato['frame'].'" frameborder="0" border="0" >';
	echo '<frame src="act.php" name="game" scrolling="auto" marginwidth="0" marginheight="0">';
    echo '<frame src="chat/chat.php" name="chat" scrolling="no" marginwidth="0" marginheight="0" frameborder="0">';
	echo '</frameset><noframes><body></body></noframes>';
}
else
{
    echo '<frameset id="frame_set" rows="*,0" frameborder="0" border="0" >';
    echo '<frame src="act.php" name="game" scrolling="auto" marginwidth="0" marginheight="0">';
    echo '<frame src="" name="chat" scrolling="no" marginwidth="0" marginheight="0" frameborder="0">';
    echo '</frameset><noframes><body></body></noframes>';
}
mysql_close();
?>