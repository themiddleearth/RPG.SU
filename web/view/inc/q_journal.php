<?  
include("../quest/quest_engine_types/inc/standart_func.lib.php");

function q_journal_moria ($user_id, $level, $quest_id)
{
	$check_moria=myquery("SELECT t1.res_num, t2.name FROM dungeon_users_progress as t1 JOIN craft_resource as t2 ON t1.res_id=t2.id WHERE t1.user_id=".$user_id." and quest_id=".$quest_id." and t1.res_num>0");	
	echo '<font color="yellow"><h3><b>���������� ����� ('.$level.' �������)</h3></b>';	
	echo '<br><font color="red"><b>�������� �����:</b>';
	while ($res=mysql_fetch_array($check_moria))
	{
		echo "<br>".$res['name'].": ".$res['res_num']."";
	}
	echo'</font>';
}

function get_stat($time,$done,&$stat,&$col)
{
	if($done AND $time)
		{
			$stat='[���������]';
			$col='0CFF00';
		}
		elseif($done AND !$time)
		{
			$stat='[���������, �� ����������]';
			$col='F26521';
		}
		elseif(!$done AND $time)
		{
			$stat='[� ��������]';
			$col='0000FF';
		}
		elseif(!$done AND !$time)
		{
			$stat='[���������]';
			$col='FF0000';
		}
}
		  
echo '<CENTER>';
if (isset($_GET['journal'])) $journal = (int)$_GET['journal']; else $journal = 0;
$user_id = $journal;
$result = myquery("SELECT view_active_users.*, game_users_map.map_name, game_users_map.map_xpos,game_users_map.map_ypos  FROM view_active_users,game_users_map WHERE game_users_map.user_id=view_active_users.user_id AND view_active_users.user_id=$user_id");
if($result==false OR mysql_num_rows($result)==0)
{
	$result = myquery("SELECT game_users.*, game_users_map.map_name, game_users_map.map_xpos,game_users_map.map_ypos,game_users_active_delay.delay,game_users_active_delay.delay_reason  FROM game_users,game_users_map,game_users_active_delay WHERE game_users.user_id=game_users_active_delay.user_id AND game_users_map.user_id=game_users.user_id AND game_users.user_id=$user_id");
}
$char = mysql_fetch_assoc($result);
$user_host=HostIdentify();
$hj=myquery("SELECT host FROM game_users_active WHERE user_id='$user_id' LIMIT 1");
if (mysql_num_rows($hj))
{
	list($host_journal)=mysql_fetch_array($hj);
}
else 
{
	$host_journal=-1;	
}
echo '<HR SIZE=1 WIDTH=440 ALIGN=center NOSHADE><BR><BR>';    		
echo '<img src="http://'.img_domain.'/nav/jor_head.jpg">';
if($user_host!=$host_journal)
{
	echo '<font color=red size=5><br><br>���������� ����������� ����� ������ �������';
}          	
else 
{	 
	//������� ������ � ����������� �����
	$check_moria = myquery("SELECT level1_quest, level2_quest, level3_quest FROM dungeon_users_data WHERE user_id=".$user_id." AND (level1_quest > 0 OR level2_quest > 0 OR level3_quest > 0)");
	if (mysql_num_rows($check_moria)>0)
	{
		list($quest1, $quest2, $quest3) = mysql_fetch_array($check_moria);
		if ($quest1 > 0)
		{
			q_journal_moria ($user_id, 1, $quest1);
		}
		if ($quest2 > 0)
		{
			q_journal_moria ($user_id, 2, $quest2);
		}
		if ($quest3 > 0)
		{
			q_journal_moria ($user_id, 3, $quest3);
		}		
		echo '<br><hr size=3 width=30% align=center noshade>';
	}
	
	//������z ������ � ������ ������ ���������
	$check_hunter=myquery("SELECT t1.level-t1.times as kol, t1.level, t2.name FROM game_users_hunter as t1 JOIN game_maps as t2 On t1.map=t2.id WHERE t1.user_id=".$user_id." and t1.times<t1.level");
	if (mysql_num_rows($check_hunter)>0)
	{
		echo '<font color="yellow"><h3><b>����� "������ ���������"</h3></b></font>';
		echo '<font color="red">';
		while ($hunter=mysql_fetch_array($check_hunter))
		{
			echo "<b>".$hunter['name']."</b>: ��� ���������� ��� ��������� ".$hunter['kol']." ".pluralForm($hunter['kol'],'���������','����������','����������')." ".$hunter['level']." ������<br>";
		}
		echo'</font>';
		echo '<HR SIZE=3 WIDTH=40% ALIGN=center NOSHADE>';
	}
	
	//������� ������� ��������� �� ���������
	$check_guild=myquery("SELECT t3.rustown, t4.npc_name, t2.npc_quest_end_time
						    FROM game_quest_users AS t1
						    JOIN game_npc AS t2 ON t2.npc_quest_id = t1.quest_id
						    JOIN game_gorod AS t3 ON t2.npc_quest_guild = t3.town
					    	JOIN game_npc_template AS t4 ON t2.npc_id = t4.npc_id
						   WHERE t1.user_id=".$user_id." and t2.npc_quest_end_time>".time()."
						");
	if (mysql_num_rows($check_guild)>0)
	{
		echo '<font color="yellow"><h3><b>������� ��������� �� ���������</h3></b></font>';
		echo '<font color="red">';
		while ($guild=mysql_fetch_array($check_guild))
		{
			$end_time = $guild['npc_quest_end_time']-time();
			$min = floor($end_time/60);
			$sec = $end_time-$min*60;
			echo "<b>".$guild['rustown']."</b>: ���������� ����� ������� <b>".$guild['npc_name']."</b> � ������� ".$min." ".pluralForm($min,'������','�����','�����')." � ".$sec." ".pluralForm($sec,'�������','������','������')."<br>";
		}
		echo'</font>';
		echo '<HR SIZE=3 WIDTH=40% ALIGN=center NOSHADE>';
	}
	
	if ($char['clevel']<5)
	{
		$from_jurnal = 1;
		include(getenv("DOCUMENT_ROOT")."/inc/template_intro.inc.php");
		unset($from_jurnal);
	}
/*	echo '<font size=4 color=#EFFB33>������� ������:</font><br><BR>';
	$quests=myquery("SELECT quest_owner_id FROM quest_engine_users WHERE user_id=".$user_id."");
	if(!mysql_num_rows($quests)) echo '<font color=red><br>��� �������.';
	else 
	{
		echo '<table border=0 align=left colspec=100>';
		$i=1;
		echo '<OL>';
		while (list($id)=mysql_fetch_array($quests))
		{
			echo '<tr><td width=150></td><td>';
			list($name)=mysql_fetch_array(myquery("SELECT name FROM quest_engine_owners WHERE id=".$id.""));
			echo '<LI> <a href="#q'.$i.'"> '.$name.'</a>';
			echo '</td></tr>';
			$i++;
		}	          		
		echo '</OL>';	          		
		echo '</table>';	          		
		echo '<BR><BR><BR><HR SIZE=3 WIDTH=40% ALIGN=center NOSHADE><BR><BR>';
		echo '</CENTER>'; 
		$quests=myquery("SELECT * FROM quest_engine_users WHERE user_id='$user_id'");
		$i=1;
		while ($quest_user=mysql_fetch_array($quests))
		{
			include("q_typeing.php");
			echo '<BR><BR><HR SIZE=3 WIDTH=40% ALIGN=center NOSHADE><BR><BR>';
			$i++;
		}
	}*/
echo '<BR><a href="?journal='.$journal.'">��������</a>';
}
echo '</center>';
if (function_exists("save_debug")) save_debug();
?>