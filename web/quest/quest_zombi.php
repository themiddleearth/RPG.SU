<?php
$dirclass = "../class";
require_once('../inc/config.inc.php');
require_once('../inc/lib.inc.php');
require_once('../inc/db.inc.php');
if (!defined("MODULE_ID"))
{
	define("MODULE_ID", '17');
}
else
{
	die();
}
require_once('../inc/lib_session.inc.php');
$quest_id=26;
$book_id=4;

$gp_start = 30;

//флаги квеста:

$print_text = true;
$alt_text = '';

function before_print()
{
	global $book_id,$user_id,$print_text,$alt_text;
	if (!isset($_GET['page'])) return;
}

include("quest_bookgame.inc.php");


echo '<br /><br /><br /><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="?exit">Выйти из квеста</a>';

?>