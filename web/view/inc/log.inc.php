<?

if (function_exists("start_debug")) start_debug();

echo'<center>������� ��� ������:</center><br>';
echo'<center><font size="1" face="Verdana" color="#ffffff">����� �� ������: <input id="name_v" type="text" size="30">
<input name="" type="button" value="�����" onClick="location.href=\'?log=\'+document.getElementById(\'name_v\').value"><br><br>
����� �� �����: <input id="id_war" type="text" size="15">
<input name="" type="button" value="�����" onClick="location.href=\'?log&war=\'+document.getElementById(\'id_war\').value"><br><br>';

if (!isset($_GET['log'])) $log = ''; else $log = $_GET['log'];
if (!isset($_GET['userid'])) $userid = 0; else $userid = $_GET['userid'];
$userid = (int)$userid;

if (!isset($_GET['war']) and $log=='' and $userid==0)
{
	echo'���� ���� �� ��������� 24 ����: [<a href="?log">'.date('d-m-Y', time()).'</a>]<br><br><div align="left">';
	$tmpboy_id=0;
	$view=myquery("
SELECT game_combats_log.boy,game_combats_log.hod,game_combats_users.user_id,game_users.name,game_combats_log.time
FROM (game_combats_log,game_combats_users) LEFT JOIN (game_users) ON (game_combats_users.user_id=game_users.user_id)
WHERE game_combats_log.time<UNIX_TIMESTAMP()
AND game_combats_log.time>(UNIX_TIMESTAMP()-24*60*60)
AND game_combats_log.boy=game_combats_users.boy
ORDER BY game_combats_log.boy DESC
"); 

	$i=01;
	while ($use=mysql_fetch_array($view))
	{
		if($use['boy']!=$tmpboy_id)
		{
			echo '<br>';
			echo''.$i.'. <a href="?userid='.$userid.'&log='.$log.'&war='.$use['boy'].'">'.date("H:i:s",$use['time']).'</a> <b>'.$use['hod'].'</b> ����� ';
			$i++;
			$tmpboy_id=$use['boy'];
		}
		if($use['name']==NULL)
		{
			$name=mysql_result(myquery("select name from game_users_archive where user_id='".$use['user_id']."'"),0,0);
		}
		else
		{
			$name=$use['name'];
		} 
		echo '[<a href="?log='.$name.'&userid='.$use['user_id'].'">'.$name.'</a>] ';
	}
	/*
	$i=01;
	while ($use=mysql_fetch_array($view))
	{
		echo''.$i.'. <a href="?userid='.$userid.'&log='.$log.'&war='.$use['boy'].'">'.date("H:i:s",$use['time']).'</a> <b>'.$use['hod'].'</b> ����� ';
		$i++;
		$seluser = myquery("SELECT user_id FROM game_combats_users WHERE boy=".$use['boy']."");
		while (list($idd)=mysql_fetch_array($seluser))
		{
			$sel=myquery("select name from game_users where user_id='".$idd."'");
			if (!mysql_num_rows($sel)) $sel=myquery("select name from game_users_archive where user_id='".$idd."'");
			list($name)=mysql_fetch_array($sel);
			echo '[<a href="?log='.$name.'&userid='.$idd.'">'.$name.'</a>] ';
		}
		echo'<br>';
	}
	*/
	echo'</div>';
}

if ($userid != 0 and !isset($_GET['war']))
{
	$sel=myquery("select user_id from game_users where user_id='$userid'");
	if (!mysql_num_rows($sel)) $sel=myquery("select user_id from game_users_archive where user_id='$userid'");
	if (mysql_num_rows($sel)=='1')
	{
		$i=01;
		echo'<div align="left">';
		list($userid)=mysql_fetch_array($sel);
		$view = myquery("
		(SELECT game_users.name AS name, game_combats_users2.user_id AS user_id,game_combats_users2.boy as boy_id,game_combats_log.hod as hod,game_combats_log.time as boy_time
		FROM game_combats_users,game_combats_users as game_combats_users2, game_users,game_combats_log
		WHERE game_combats_users.user_id = $userid AND game_combats_users2.boy=game_combats_users.boy
		AND game_users.user_id = game_combats_users2.user_id AND game_combats_log.boy=game_combats_users2.boy) UNION (
		SELECT game_users_archive.name AS name, game_combats_users2.user_id AS user_id,game_combats_users2.boy as boy_id,game_combats_log.hod as hod,game_combats_log.time as boy_time
		FROM game_combats_users,game_combats_users as game_combats_users2, game_users_archive,game_combats_log
		WHERE game_combats_users.user_id = $userid AND game_combats_users2.boy=game_combats_users.boy
		AND game_users_archive.user_id = game_combats_users2.user_id AND game_combats_log.boy=game_combats_users2.boy)
		ORDER BY boy_time DESC, user_id ASC"); 
		//$view=myquery("select * from game_combats_log where boy IN (SELECT boy FROM game_combats_users WHERE user_id='$userid') ORDER BY time DESC");
		$cur_boy = 0;
		while ($use=mysql_fetch_array($view))
		{
			$boy = $use['boy_id'];
			if ($cur_boy!=$boy)
			{
				echo'<br>'.$i.'. <a href="?userid='.$userid.'&log='.$log.'&war='.$boy.'">'.date("d-m-Y",$use['boy_time']).', '.date("H:i:s",$use['boy_time']).'</a> <b>'.$use['hod'].'</b> ����� ';
				$i++;
				$cur_boy = $boy;
			}
			//$us=myquery("SELECT user_id FROM game_combats_users WHERE boy='$boy' ORDER BY user_id ASC");
			//while (list($idd)=mysql_fetch_array($us))
			//{
			//    $sel=myquery("select name from game_users where user_id='$idd'");
			//    if (!mysql_num_rows($sel)) $sel=myquery("select name from game_users_archive where user_id='$idd'");
			//    list($name)=mysql_fetch_array($sel);
				echo '[<a href="?userid='.$use['user_id'].'&log='.$use['name'].'">'.$use['name'].'</a>] ';
			//}
		 }
	}
	else
	{
		echo'����� �� ������';
	}
}
elseif (preg_match('/^[ _a-z�-��-�0-9]*$/i', $log) and $log!='' and !isset($_GET['war']))
{
	$sel=myquery("select user_id from game_users where name='$log'");
	if (!mysql_num_rows($sel)) $sel=myquery("select user_id from game_users_archive where name='$log'");
	if (mysql_num_rows($sel)=='1')
	{
		$i=01;
		echo'<div align="left">';
		list($userid)=mysql_fetch_array($sel);
		$view = myquery("
		(SELECT game_users.name AS name, game_combats_users2.user_id AS user_id,game_combats_users2.boy as boy_id,game_combats_log.hod as hod,game_combats_log.time as boy_time
		FROM game_combats_users,game_combats_users as game_combats_users2, game_users,game_combats_log
		WHERE game_combats_users.user_id = $userid AND game_combats_users2.boy=game_combats_users.boy
		AND game_users.user_id = game_combats_users2.user_id AND game_combats_log.boy=game_combats_users2.boy
		)
		UNION (
		SELECT game_users_archive.name AS name, game_combats_users2.user_id AS user_id,game_combats_users2.boy as boy_id,game_combats_log.hod as hod,game_combats_log.time as boy_time
		FROM game_combats_users,game_combats_users as game_combats_users2, game_users_archive,game_combats_log
		WHERE game_combats_users.user_id = $userid AND game_combats_users2.boy=game_combats_users.boy
		AND game_users_archive.user_id = game_combats_users2.user_id AND game_combats_log.boy=game_combats_users2.boy
		)
		ORDER BY boy_time DESC, user_id ASC"); 
		//$view=myquery("select * from game_combats_log where boy IN (SELECT boy FROM game_combats_users WHERE user_id='$userid') ORDER BY time DESC");
		$cur_boy = 0;
		while ($use=mysql_fetch_array($view))
		{
			$boy = $use['boy_id'];
			if ($cur_boy!=$boy)
			{
				echo'<br>'.$i.'. <a href="?userid='.$userid.'&log='.$log.'&war='.$boy.'">'.date("d-m-Y",$use['boy_time']).', '.date("H:i:s",$use['boy_time']).'</a> <b>'.$use['hod'].'</b> ����� ';
				$i++;
				$cur_boy = $boy;
			}
			//$us=myquery("SELECT user_id FROM game_combats_users WHERE boy='$boy' ORDER BY user_id ASC");
			//while (list($idd)=mysql_fetch_array($us))
			//{
			//    $sel=myquery("select name from game_users where user_id='$idd'");
			//    if (!mysql_num_rows($sel)) $sel=myquery("select name from game_users_archive where user_id='$idd'");
			//    list($name)=mysql_fetch_array($sel);
				echo '[<a href="?userid='.$use['user_id'].'&log='.$use['name'].'">'.$use['name'].'</a>] ';
			//}
		 }
	}
	else
	{
		echo'����� �� ������';
	}
}


if (isset($_GET['war']) and ($war = (int)$_GET['war']) > 0)
{
	$war = (int)$war;
	echo '<a href="?log=">���� ����</a>: ';
	$sel=myquery("select * from game_combats_log where boy=$war");
	$boy=mysql_fetch_array($sel);
	$us=myquery("SELECT user_id FROM game_combats_users WHERE boy=$war");
	while (list($id)=mysql_fetch_array($us))
	{
		$sel=myquery("select name from game_users where user_id=$id");
		if (!mysql_num_rows($sel)) $sel=myquery("select name from game_users_archive where user_id=$id");
		list($name)=mysql_fetch_array($sel);
		echo '[<a href="?log='.$name.'">'.$name.'</a>] ';
	}
	echo "<br>";
	if ($boy['type']==1) echo' <b>������� ���</b><br>';
	if ($boy['type']==2) echo' <b>�����</b><br>';
	if ($boy['type']==3) echo' <b>����� ���</b><br>';
	if ($boy['type']==4) echo' <b>������������� ���</b><br>';
	if ($boy['type']==5) echo' <b>��� ������ ����</b><br>';
	if ($boy['type']==6) echo' <b>��� �����������</b><br>';
	if ($boy['type']==7) echo' <b>��� ���</b><br>';
	if ($boy['type']==8) echo' <b>��������� �����</b><br>';
	if ($boy['type']==9) echo' <b>��������� ���</b><br>';
	echo '����� �����: '.$boy['hod'].'<br><br> '; 
	$path="/home/vhosts/rpg.su/web/combat/log";
	if (domain_name=='localhost') $path = "../combat/log";
	//$file1 = $path."/".$war.".dat";
	//if (file_exists($file1)) {include($file1);};
	
	echo show_combat_log($war);
	/*
	echo '<br>';
	$file2 = $path."/".$war."_stat.dat";
	if (file_exists($file2)) {include($file2);};
	*/
	if (isset($pass) and $pass=='elfzone')
	{   
		$selchat = myquery("SELECT * FROM game_combats_chat WHERE boy=$war ORDER BY id ASC");
		while ($ch = mysql_fetch_array($selchat))
		{
			echo'<br>'.$ch['chat'].'';
		}
	}
	/*
	if (isset($sys) and isset($pass) and $pass=='elfzone') 
	{
		echo'<br>';
		$file3 = $path."/".$war."_sys.dat";
		if (file_exists($file3)) include($file3);
	}
	*/
}

if (function_exists("save_debug")) save_debug(); 

?>
