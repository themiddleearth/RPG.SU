<?

if (function_exists("start_debug")) start_debug();

if ($_SERVER['PHP_SELF']!="/act.php")
{
	die();
}

$output_string = '';

if (isset($errror))
{
	switch ($errror)
	{
		case '':
			$output_string.='����� �� ����������! ��������� ��������:<br>1. ����� ����� ��� ��� ��� �� ���� �����.<br>2. � ��� ���������� IP.<br />';
		break;

		case 'ip':
			$output_string.='����� �� ����������!<br>� ��� ���������� IP-������!<br />';
		break;

		case 'level':
			$output_string.='����� �� ����������!<br>� ���� ������� ������� �������!<br />';
		break;

		case 'clan_id':
			$output_string.='����� �� ����������!<br>�� � ����� �����!<br />';
		break;

		case 'lost':
			$output_string.='�� �� ������ ��������������,<br>��� ��� �� ��� '.echo_sex('����������','�����������').' � ���� �����<br />';
		break;

		case 'max_number':
			$output_string.='�� �� ������ ��������������,<br>� ����� �� ������ ����� ��� ��������� ������������ ���������� (20) �������<br />';
		break;

		case 'npc':
			$output_string.='�� �� ������ ��������������,<br>����� ����� � ����� � ����� (NPC)<br />';
		break;

		case 'clan':
			$output_string.='��� �� �������� �����<br />';
		break;

		case 'duel':
			$output_string.='�� �� ������ �������������� � �����<br />';
		break;

		case 'boy':
			$output_string.='���� ��� ��� �������� �� ���� �����<br />';
		break;

		case 'shakal':
			$output_string.='��������� ������� ����! ��� ���� �����������! ��� ����������.<br />';
		break;

		case 'sred_level':
			$output_string.='�� �� ��������� ��� �����<br>��-�� �������������� ��������<br>������ ������� � �����<br />';
		break;

		case 'arcomage_active':
			$output_string.='������ ���������� ��� � ����<br />';
		break;

		case 'arcomage_call_money':
			$output_string.='� ���� ������������ ����� ��� ����<br />';
		break;

		case 'arcomage_player_money':
			$output_string.='� ������ ���������� ������������ ����� ��� ����<br />';
		break;

		case 'full_inv':
			$output_string.='�� �� ������ ������� �������! � ���� ������������ ���������� ����� � ���������<br />';
		break;

		case 'wrong_clan':
			$output_string.='�� �� ������ ������� �������! ���� ������� �� ��� ������ �����"<br />';
		break;

		case 'late':
			$output_string.='� ���� ����� �������������� ������ �� ��������� 3 ����!<br />';
		break;

		case 'max_inv':
			$output_string.='���������� ������� �������!<br />';
		break;

		default:
			$output_string.=urldecode($errror);
		break;
	}
}

/*
if (isset($log))
{
	$del=myquery("delete from game_battles WHERE attacker_id=$user_id");
	$output_string.='��� ������ ��������� ������<br /><br />';
}
*/

if (isset($lek))
{
	$prov=myquery("SELECT game_users.name FROM game_users,game_users_func WHERE game_users.user_id='$lek' 
	AND game_users.user_id IN (SELECT user_id FROM game_users_map WHERE  map_xpos='".$char['map_xpos']."' and map_ypos='".$char['map_ypos']."' and map_name='".$char['map_name']."')
	AND game_users.user_id=game_users_func.user_id
	AND game_users_func.func_id!='1'
	limit 1
	");
	if (mysql_num_rows($prov) and $char['MS_LEK']>0)
	{
		$us=mysql_fetch_array($prov);
		if (!isset($save))
		{
			$output_string.='<form action="" method="post">';

			$output_string.='<table cellpadding="0" cellspacing="5" border="0">';
			$output_string.='<tr><td>������ '.$char['MS_LEK'].' �������</td></tr>';
			$output_string.='<tr><td>�������� ������: <b>'.$us['name'].'</b><br>';

			$output_string.='<select name="lekar">';

			$i=1;
			while ($i<=$char['MS_LEK'])
			{
				$hp=0;
				for ($cikl=1; $cikl<=$i; $cikl++)
				{
					$hp=$hp+$cikl;
				}
				$hp=$hp*3;
				$mana=$i*5;
				$energy=$i*5;
				$output_string.='<option value='.$i.'>�������� '.$hp.' ����� �� '.$energy.' ������� � '.$mana.' ����</option>';
				$i++;
			}
			$output_string.='</select>';

			$output_string.='<br><br><input name="save" type="submit" value="��������"><input name="save" type="hidden" value=""></td></tr></table></form>';
		}
		else
		{

			if (isset($lekar))
			{
				$i=$lekar;
				$hp=0;
				for ($cikl=1; $cikl<=$i; $cikl++)
				{
					$hp=$hp+$cikl;
				}
				$hp=$hp*3;
				$mana=$i*5;
				$energy=$i*5;

				if ($char['MP']>=$mana AND $char['STM']>=$energy)
				{
					$output_string.='�� '.echo_sex('�������','��������').' � '.$us['name'].' '.$hp.' ����� �� '.$energy.' ������� � '.$mana.' ����';
					$upd=myquery("update game_users set MP=MP-$mana where name='".$char['name']."' limit 1");
					$upd=myquery("update game_users set STM=STM-$energy where name='".$char['name']."' limit 1");
					$upd=myquery("update game_users set HP=HP+$hp where name='".$us['name']."' limit 1");
					$upd=myquery("update game_users set HP=HP_MAX where name='".$us['name']."' AND HP>HP_MAX limit 1");
				}
				else
				{
					$output_string.='�� ������� ���� ��� �������';
				}
			}
		}
	}
	else
	{
		$output_string.='������ ��� �� ���� �����';
	}
}

//���������� �� ����
/*
if (isset($vor))
{
	if ($char['clan_id']!=1)
	   $prov=myquery("SELECT game_users.* FROM game_users,game_users_func WHERE game_users.name='$vor'
	   AND game_users.user_id IN (SELECT user_id FROM game_users_map WHERE  map_xpos='".$char['map_xpos']."' and map_ypos='".$char['map_ypos']."' and map_name='".$char['map_name']."')
	   AND game_users.user_id=game_users_func.user_id
	   AND game_users_func.func_id!='1'
	   limit 1");
	else
		$prov=myquery("select * from game_users where name='$vor' limit 1");

	if (mysql_num_rows($prov) and $char['MS_VOR']>0)
	{
		$user=mysql_fetch_array($prov);
		$output_string.='<table cellpadding="0" cellspacing="5" border="0">';
		$output_string.='<tr><td>��� '.$char['MS_VOR'].' �������</td></tr>';
		$output_string.='<tr><td><br>������� ��������:</td></tr><tr><td>';
		$lim=1;
		$sel=myquery("select id from game_items where user_id=".$user['user_id']." and used!=0 and priznak=0");
		while($it=mysql_fetch_array($sel))
		{
			$Item = new Item($it['id']);
			if ($Item->getFact('type')>=90) continue;
			if ($lim>$char['MS_VOR']) continue;
			$lim++;
			$Item->hint(0,0,'<a ');
			$output_string.='<img src="http://'.img_domain.'/item/'.$Item->getFact('img').'.gif" width="30" height="30" border="0"></a>';
		}
		$output_string.='</td></tr>';

		if ($char['MS_VOR']>=8)
		{
			$output_string.='<tr><td><br>�������� � �������:</td></tr><tr><td>';
			$sel=myquery("select id from game_items where user_id=".$user['user_id']." and used=0 and priznak=0");
			while($it=mysql_fetch_array($sel))
			{
				$Item = new Item($it['id']);
				if ($Item->getFact('type')>=90) continue;
				$Item->hint(0,0,'<a ');
				$output_string.='<img src="http://'.img_domain.'/item/'.$Item->getFact('img').'.gif" width="30" height="30" border="0"></a>';
			}
			$output_string.='</td></tr>';
		}
		$output_string.='</table>';
	}
	else
	{
		$output_string.='������ ��� �� ���� �����';
	}
}
*/

if (isset($menu))
{
	$prov=myquery("select game_users.*,game_users_func.func_id from game_users,game_users_map,game_users_func where game_users.user_id='$menu' and game_users.user_id=game_users_map.user_id AND game_users_map.map_xpos='".$char['map_xpos']."' and game_users_map.map_ypos='".$char['map_ypos']."' and game_users_map.map_name='".$char['map_name']."' AND game_users.user_id=game_users_func.user_id");
	if (mysql_num_rows($prov))
	{
		$up=mysql_fetch_array($prov);
		$output_string.='<table cellpadding="0" cellspacing="5" border="0">';
		$output_string.='<tr><td colspan=2 align="center"><b>';

		if ($up['clan_id']<>0) $output_string.='<a href="http://'.domain_name.'/view/?clan='.$up['clan_id'].'" target="_blank"><img src="http://'.img_domain.'/clan/'.$up['clan_id'].'.gif" border=0></a> ';
		$output_string.='<font face=verdana size=2 color=ff0000>'.$up['name'].'</font> ('.mysql_result(myquery("SELECT name FROM game_har WHERE id=".$up['race'].""),0,0).' '.$up['clevel'].' ������)</b><br> ��������� ��������: <b>'.get_delay_reason(get_delay_reason_id($up['user_id'])).'</b>';
		if ($up['vsadnik']>20) $output_string.='<br><font face=verdana size=2 color=ff0000><b>�������!</b></font>';
		$output_string.='<br> ��������� ��������:</td></tr>';

		$output_string.='<tr><td><a href="http://'.domain_name.'/view/?userid='.$up["user_id"].'" target="_blank"">����������</a></td></tr>';
		if ($up['clan_id']!='0') $output_string.='<tr><td><a href="http://'.domain_name.'/view/?clan='.$up['clan_id'].'" target="_blank"">���������� � �����</a></td></tr>';
		if ($up['name'] != $char['name']) $output_string.='<tr><td><a href="?func=pm&pm=write&komu='.$up["name"].'">������� ���������</a></td></tr>';

		$num=0;
		$map = mysql_fetch_array(myquery("SELECT * FROM game_maps WHERE id = '".$char['map_name']."'"));
		//if ($char['MS_LEK']!='0' and ($up['func_id']!='1')) $output_string.='<tr><td><a href="?func=main&lek='.$up['user_id'].'">������ (������������� '.$char['MS_LEK'].' ������)</a></td></tr>';
		//if ($char['MS_VOR']!='0') $output_string.='<tr><td><a href="?func=main&vor='.$up['name'].'">��������� (������������� '.$char['MS_VOR'].' ������)</a></td></tr>';
		$output_string.='</table>';
	}
	else
	{
		$output_string.='<b><font face=verdana size=1 color=ff0000>����� ������� �� ������ �����</font></b>';
	}
}


if (isset($_GET['npc_info']))
{
	$npc_info=(int)$_GET['npc_info'];
    if ($npc_info>0)
    {
	    $Npc_object = new Npc($npc_info);
	    $output_string.=$Npc_object->create_output();
    }
}

/*
if (!isset($npc_info) and !isset($menu) and !isset($errror) and !isset($lek) and !isset($vor))
{
	$result_battles = myquery("SELECT type, map_name, map_xpos, map_ypos, contents, post_time FROM game_battles WHERE attacker_id=$user_id ORDER BY post_time DESC LIMIT 5");
	echo '5 ��������� ������ ���������</font><br /><br />
	<table cellpadding="0" cellspacing="5" border="0">';
	if ($result_battles!=false AND mysql_num_rows($result_battles) > 0)
	{
		while ($battle = mysql_fetch_array($result_battles))
		{
			echo '<tr><td><font color=#C0FFC0>'.date("H:i",$battle['post_time']).'</font></td><td>'.$battle['contents'].'</td></tr>';
		}
		echo '<tr><td colspan="2" align="center"><a href="act.php?func=main&log">�������� ���</a></td></tr>';
	}
	echo '</table>';

}
*/
if ($output_string!='')
{
	QuoteTable('open');
	echo $output_string;
	QuoteTable('close');
}

if (function_exists("save_debug")) save_debug();

?>