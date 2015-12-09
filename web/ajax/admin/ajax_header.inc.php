<?php
$dirclass="../../class";
require('../../inc/config.inc.php');
include('../../inc/lib.inc.php');
require_once('../../inc/db.inc.php');
require('../../inc/lib_session.inc.php');

$result=myquery("SELECT * FROM game_admins WHERE user_id=".$user_id." LIMIT 1");
if (mysql_num_rows($result) == 0)
{
	die();
}
?>