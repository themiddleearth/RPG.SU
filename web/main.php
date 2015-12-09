<?
include('inc/config.inc.php');
include('inc/lib.inc.php');
require_once('inc/db.inc.php');
// Константа NO_FUNC_CHECK введена для того, чтобы при загрузке main.php
// не проверялся func и не делался setLocation 
if (!defined("NO_FUNC_CHECK"))
{
	define("NO_FUNC_CHECK", '1');
}
else
{
// А тут мы никогда не должны оказаться
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
<title>Средиземье :: Эпоха сражений :: RPG online игра по трилогии "Властелин колец"</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<meta name="description" content="Многопользовательская RPG OnLine игра по трилогии Дж.Р.Р.Толкиена 'ВЛАСТЕЛИН КОЛЕЦ' - лучшая ролевая игра на постсоветском пространстве">
<meta name="Keywords" content="Средиземье, Властелин, колец, Толкиен, Lord, of, the, Rings, rpg, фэнтези, ролевая, онлайн, игра, Эпоха, сражений, online, game, поединки, бои, гильдии, кланы, магия, бк, таверна, игра, играть, игрушки, интернет, internet, fantasy, меч, топор, магия, кулак, удар, блок, атака, защита, Бойцовский, Клуб, бой, битва, отдых, обучение, развлечение, виртуальная, реальность, рыцарь, маг, знакомства, чат, лучший, форум, свет, тьма, bk, games, клан, банк, магазин, клан">
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