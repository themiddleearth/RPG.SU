<?
/*
�������� ���� confirmed � ������� game_tutorship:
0 - �������� ������ �� �����������
1 - ������������� ������ �� �����������
2 - ������ ������ 1-�� ������������
*/
if (function_exists("start_debug")) start_debug(); 

if ($town!=0)
{
	//��������� ���������
	$cost_newp=100;      //��������� ��������������
	$cost_declinep=500;  //��������� ������ �� �������
	$cost_declinet=1000; //��������� ������ �� ����������
	$pupil_level=14;     //������������ �������, ����� ����� ����� ����� ��������	
	
	$userban=myquery("select * from game_ban where user_id='$user_id' and type=2 and time>'".time()."'");
	if (mysql_num_rows($userban))
	{
		$userr = mysql_fetch_array($userban);
		$min = ceil(($userr['time']-time())/60);
		echo '<center><br><br><br>�� ���� �������� ��������� �� '.$min.' �����! ���� ��������� ������������ �������!';
		{if (function_exists("save_debug")) save_debug(); exit;}
	}	
		
	$img='http://'.img_domain.'/race_table/human/table';
	echo'<table width=100% border="0" cellspacing="0" cellpadding="0"><tr><td width="1" height="1"><img src="'.$img.'_lt.gif"></td><td background="'.$img.'_mt.gif"></td><td width="1" height="1"><img src="'.$img.'_rt.gif"></td></tr>
	<tr><td background="'.$img.'_lm.gif"></td><td style="text-align:center" background="'.$img.'_mm.gif" valign="top" width="100%" height="100%">';
	echo '<center>';
	
	if (isset($_POST['addtutor']))	//������ ������ �� ��������������
	{
		if ($char['GP']<$cost_newp)
		{
			echo '<font face=verdana color=ff0000 size=2>� ��� ������������ ����� ��� ����������� �������!</font>';
		}
		elseif ($char['reinc']<1)
		{
			echo '<font face=verdana color=ff0000 size=2>�� �� ������ ��� �� ����� ������������ � �� ������ ��� ���� �����������!</font>';
		}
		elseif (!is_numeric($_POST['in_id']) or $_POST['in_id'] == 0 or $_POST['in_name']=='')
		{
			echo '<font face=verdana color=ff0000 size=2>������ �� ������!</font>';
		}
		elseif (mysql_num_rows(myquery("SELECT * FROM game_users WHERE user_id = '".$_POST['in_id']."' and name = '".$_POST['in_name']."' and clevel<15 and clan_id <> 1  UNION ALL 
		                                SELECT * FROM game_users_archive WHERE user_id = '".$_POST['in_id']."' and name = '".$_POST['in_name']."' and clevel<15 and clan_id <> 1")) == 0)
		{
			echo '<font face=verdana color=ff0000 size=2>������ �� ������!</font>';
		}		
		elseif (mysql_num_rows(myquery("SELECT * FROM game_tutorship WHERE user_id = '".$user_id."' and pupil_id = '".$_POST['in_id']."' ")) > 0)
		{
			echo '<font face=verdana color=ff0000 size=2>�� ��� ������ ����� ������!</font>';
		}	
		else
		{
			echo '�� ������������� ������, ����� ����� <b>'.$_POST['in_name'].'</b> ���� ����� ��������?';
			echo '<br><a href="town.php?option='.$option.'&addtutor&in_id='.$_POST['in_id'].'&in_name='.$_POST['in_name'].'">��, ������ ������</a>';
			echo '<br><a href="town.php?option='.$option.'">���, �������� ������</a>';			
		}		
		echo '<br><br>';
	}
	elseif (isset($_GET['addtutor']))//���������� � �� ������ �� ��������������
	{
		if ($char['GP']<$cost_newp)
		{
			echo '<font face=verdana color=ff0000 size=2>� ��� ������������ ����� ��� ����������� �������!</font>';
		}		
		elseif ($user_id == $_GET['in_id'])
		{
			echo '<font face=verdana color=ff0000 size=2>������ ���� ����������� ��������!</font>';
		}
		elseif ($char['reinc']<1)
		{
			echo '<font face=verdana color=ff0000 size=2>�� �� ������ ��� �� ����� ������������ � �� ������ ��� ���� �����������!</font>';
		}
		elseif (!is_numeric($_GET['in_id']) or $_GET['in_id'] == 0)
		{
			echo '<font face=verdana color=ff0000 size=2>������ �� ������!</font>';
		}
		elseif (mysql_num_rows(myquery("SELECT * FROM game_users WHERE user_id = '".$_GET['in_id']."' and name = '".$_GET['in_name']."' and clevel<15 and clan_id <> 1 UNION ALL 
		                                SELECT * FROM game_users_archive WHERE user_id = '".$_GET['in_id']."' and name = '".$_GET['in_name']."' and clevel<15 and clan_id <> 1")) == 0)
		{
			echo '<font face=verdana color=ff0000 size=2>������ �� ������!</font>';
		}			
		elseif (mysql_num_rows(myquery("SELECT * FROM game_tutorship WHERE user_id = '".$user_id."' and pupil_id = '".$_GET['in_id']."' ")) > 0)
		{
			echo '<font face=verdana color=ff0000 size=2>�� ��� ������ ����� ������!</font>';
		}		
		else //��� �������� ��������!
		{
			myquery("INSERT INTO game_tutorship (user_id, pupil_id) VALUES ('".$user_id."', '".$_GET['in_id']."') ");
			$theme = '������� �����������';
			$post = '����� <b>'.$char['name'].'</b> ����� ����� ����� �����������!';
			myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time, folder) VALUES ('".$_GET['in_id']."', '0', '".$theme."', '".$post."','0','".time()."',1)");	
			echo '<b>�� ������� ������ ������ �� �������������� ��� ������ <u>'.$_GET['in_name'].'</u></b>';
		}
		echo '<br><br>';
	}
	elseif (isset($_GET['settutor']))//����������� ������� �� �����������
	{
		if ($char['clevel']>14)
		{
			echo '<font face=verdana color=ff0000 size=2>��� ������� �� ��������� ����� ��������!</font>';
		}
		else
		{
			$check = myquery("SELECT gt.* FROM game_tutorship gt WHERE gt.id = '".$_GET['settutor']."' AND gt.pupil_id = ".$user_id." AND gt.confirmed = 0");	 		
			if (mysql_num_rows($check) == 0)
			{
				echo '<font face=verdana color=ff0000 size=2>���-�� ������� �������!</font>';
			}
			else
			{
				$tutor = mysql_fetch_array ($check);
				$tutor_name = get_user ('name', $tutor['user_id']);
				if (!isset($_GET['yes']))
				{
					echo '�� ������������� ������, ����� <b>'.$tutor_name.'</b> ���� ����� �����������??';
					echo '<br><a href="town.php?option='.$option.'&settutor='.$_GET['settutor'].'&yes">��, � ����� ����� ��������</a>';
					echo '<br><a href="town.php?option='.$option.'">���, � ��� �� ����� ������� �������</a>';			
				}
				else //��� �������� ��������!
				{
					myquery("UPDATE game_tutorship SET confirmed = 1 WHERE id = '".$_GET['settutor']."' ");
					$theme = '������� �����������';
					$post = '����� <b>'.$char['name'].'</b> ���� ����� ��������!';
					myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time, folder) VALUES ('".$tutor['user_id']."', '0', '".$theme."', '".$post."','0','".time()."',1)");	
					save_gp($tutor['user_id'], -$cost_newp, 111, 2);
					echo '<b>�� ����� �������� ������ <u>'.$tutor_name.'</u></b>';
				}				
			}
		}
		echo '<br><br>';
	}
	elseif (isset($_GET['unsettutor']))//������ ����������� � ��������������
	{
		$check = myquery("SELECT gt.* FROM game_tutorship gt WHERE gt.id = '".$_GET['unsettutor']."' AND (gt.pupil_id = ".$user_id." OR gt.user_id = ".$user_id.") AND gt.confirmed = 0");	 		
		if (mysql_num_rows($check) == 0)
		{
			echo '<font face=verdana color=ff0000 size=2>���-�� ������� �������!</font>';
		}
		else
		{
			$tutor = mysql_fetch_array ($check);
			$tutor_name = get_user ('name', $tutor['user_id']);
			$pupil_name = get_user ('name', $tutor['pupil_id']);
			if (!isset($_GET['yes']))
			{
				echo '�� ������������� ������, �������� ������ � �������������� ��� ������� <b>'.$pupil_name.'</b> � ���������� <b>'.$tutor_name.'</b>??';
				echo '<br><a href="town.php?option='.$option.'&unsettutor='.$_GET['unsettutor'].'&yes">��, �������� ������</a>';
				echo '<br><a href="town.php?option='.$option.'">���, �������� ������ ������</a>';			
			}
			else //��� �������� ��������!
			{
				myquery("DELETE FROM game_tutorship WHERE id = '".$_GET['unsettutor']."' ");
				if ($user_id == $tutor['user_id'])
				{
					$theme = '������� �����������';
					$post = '����� <b>'.$pupil_name.'</b> �� ����� ���� ����� �����������!';
					$target = $tutor['pupil_id'];
				}
				else
				{
					$theme = '������� �����������';					
					$post = '����� <b>'.$pupil_name.'</b> �� ����� ���� ����� ��������!';
					$target = $tutor['user_id'];
				}
				myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time, folder) VALUES ('".$target."', '0', '".$theme."', '".$post."','0','".time()."',1)");					
				echo '<b>�� ������� �������� ������ � �������������� ��� ������� <u>'.$pupil_name.'</u> � ���������� <u>'.$tutor_name.'</u></b>';
			}				
		}
		echo '<br><br>';
	}
	elseif (isset($_GET['deltutor']))//����� �� ����������
	{
		$check = myquery("SELECT user_id FROM game_tutorship WHERE id = '".$_GET['deltutor']."' AND pupil_id = ".$user_id." ");
		if ($char['GP']<$cost_declinet)
		{
			echo '<font face=verdana color=ff0000 size=2>� ��� ������������ ����� ��� ������ �� ����������!</font>';
		}
		elseif (mysql_num_rows($check) == 0)
		{
			echo '<font face=verdana color=ff0000 size=2>��������� �� ������!</font>';
		}								
		elseif (!isset($_GET['yes']))
		{
			echo '�� ������������� ������ ���������� �� ������ ����������?';
			echo '<br><a href="town.php?option='.$option.'&deltutor='.$_GET['deltutor'].'&yes">��, ���������� �� ����������</a>';
			echo '<br><a href="town.php?option='.$option.'">���, �������� ��������</a>';			
		}
		else //��� �������� ��������!
		{
			myquery("DELETE FROM game_tutorship WHERE id = '".$_GET['settutor']."' ");
			list($target) = mysql_fetch_array($check);
			$theme = '������� �����������';
			$post = '����� <b>'.$char['name'].'</b> ������ �� �������� ����� ��������!';
			myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time, folder) VALUES ('".$target."', '0', '".$theme."', '".$post."','0','".time()."',1)");
			save_gp($user_id, -$cost_declinet, 111);
			echo '<b>�� ���������� �� ������ ����������, �������� '.$cost_declinet.' �����!</b>';
		}
		echo '<br><br>';
	}
	elseif (isset($_GET['delpupil']))//����� �� �������
	{
		$check = myquery("SELECT pupil_id FROM game_tutorship WHERE id = '".$_GET['delpupil']."' AND user_id = ".$user_id." ");
		if ($char['GP']<$cost_declinep)
		{
			echo '<font face=verdana color=ff0000 size=2>� ��� ������������ ����� ��� ������ �� �������!</font>';
		}
		elseif (mysql_num_rows($check) == 0)
		{
			echo '<font face=verdana color=ff0000 size=2>������ �� ������!</font>';
		}								
		elseif (!isset($_GET['yes']))
		{
			echo '�� ������������� ������ ���������� �� ������ �������?';
			echo '<br><a href="town.php?option='.$option.'&delpupil='.$_GET['delpupil'].'&yes">��, ���������� �� �������</a>';
			echo '<br><a href="town.php?option='.$option.'">���, �������� ��������</a>';			
		}
		else //��� �������� ��������!
		{
			myquery("DELETE FROM game_tutorship WHERE id = '".$_GET['delpupil']."' ");
			list($target) = mysql_fetch_array($check);
			$theme = '������� �����������';
			$post = '����� <b>'.$char['name'].'</b> ������ �� �������� ����� �����������!';
			myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time, folder) VALUES ('".$target."', '0', '".$theme."', '".$post."','0','".time()."',1)");
			save_gp($user_id, -$cost_declinep, 111);
			echo '<b>�� ���������� �� ������ �������, �������� '.$cost_declinep.' �����!</b>';
		}
		echo '<br><br>';
	}
	echo'<font face=verdana color=ff0000 size=2><b>������� �����������</b></font><br/><br/>
		 <b><font face=verdana color=white size=2>������������ ����! ������� ����������� ���������� 2 ����:
		 <br>��-������, � ������� �� ���� �������� ����������� ����� ������� ���� ���������� ����������, ����� �������� � ���� �� ����� ������ � �����������, ��� ����, ����� ����� �������� ������ ����������.
		 <br>��-������, ������� ������ ����� ����� ���� �������������� ��� �������� �� ������ � ������. ���� ���������� ������� ���������� ������������ ������������ ��� ��������!</font></b><br/><br/>';	
		 
	//������� ������� ������� �����������
	if (isset($_GET['rule']))
	{
		echo '<ol>
			  <li> �������� ����� ����� ����� ����� �� 15-��� ������.</li>
			  <li> ����������� ����� ����� ����� �����, ��������� ���� �� 1 ������������.</li>
			  <li> � ������ ���������� ����� ���� �� ����� 3-�� ��������. ��������� �������������� - '.$cost_newp.' �����.</li>
			  <li> ��������� �������� 1 �� �� ������ ������ ������������ ��������.</li>
			  <li> ��� ���������� �������� 1-�� ������������ �� �������� ���� ��������, ���� ��� ���������� ������ �� ������� ����������.</li>
			  <li> ��������� ����� ���������� �� �������. ��������� ������ �� ������� - '.$cost_declinep.' �����.</li>				  
			  <li> ������ ����� ���������� �� ����������. ��������� ������ �� ���������� - '.$cost_declinet.' �����.</li>				  
			  </ol>';
			  echo '<a href="town.php?option='.$option.'">������ �������</a>';
	}
	else
	{
		echo '<a href="town.php?option='.$option.'&rule">��������� ������� ������� �����������</a>';
	}
	echo '<br/><br/>';
	$check_tutor = myquery("SELECT gt.*, IFNULL(gu.name, gua.name) as name FROM game_tutorship gt LEFT JOIN game_users gu ON gt.user_id = gu.user_id LEFT JOIN game_users_archive gua ON gt.user_id = gua.user_id WHERE gt.pupil_id = ".$user_id." ORDER BY action_time");	 		
	if (mysql_num_rows($check_tutor) > 0 )
	{
		while ($tutor = mysql_fetch_array($check_tutor) )
		{
			//� ������ ���� ���������
			if ($tutor['confirmed'] == 1 or $tutor['confirmed'] == 2)
			{
				echo '����� ����������� �������� <b>'.$tutor['name'].'</b> (<a href="town.php?option='.$option.'&deltutor='.$tutor['id'].'">���������� �� ����������</a>)<br>';
			}
			//������ ���������� ����� ��������
			else
			{
				echo '����� ����������� ����� ����� <b>'.$tutor['name'].'</b> (<a href="town.php?option='.$option.'&settutor='.$tutor['id'].'">������� �����������</a>)&nbsp;
				(<a href="town.php?option='.$option.'&unsettutor='.$tutor['id'].'">��������� �����������</a>)<br>';
			}
		}
	}		
		
	$kol_wait = 0;
	$kol_current = 0;
	$kol_prev = 0;
	
	$check_pupil = myquery("SELECT gt.*, IFNULL(gu.name, gua.name) as name FROM game_tutorship gt LEFT JOIN game_users gu ON gt.pupil_id = gu.user_id LEFT JOIN game_users_archive gua ON gt.pupil_id = gua.user_id WHERE gt.user_id = ".$user_id." ORDER BY confirmed, action_time");	 			
	if (mysql_num_rows($check_pupil) > 0 )
	{			
		echo '<br><table border="1"><tr align="center">
			  <td width="350"><b>��������� �������</b></td>
			  <td width="350"><b>������� �������</b></td>
			  <td width="350"><b>������ �������</b></td>
			  </tr><tr>
			 ';
		while ($tutor = mysql_fetch_array($check_pupil) )
		{			
			//�������� ������ ������ �� ��������������
			if ($tutor['confirmed'] == 0)
			{
				if ($kol_wait == 0) echo '<td>';
				$kol_wait++;
				echo $kol_wait.') <b>'.$tutor['name'].'</b> (<a href="town.php?option='.$option.'&unsettutor='.$tutor['id'].'">�������� ������</a>)<br>';
			}
			//������� ������� ������
			elseif ($tutor['confirmed'] == 1)
			{				
				if ($kol_current == 0) 
				{
					if ($kol_wait == 0) echo '<td>&nbsp;</td>';
					else '</td>';
					echo '<td>';
				}
				$kol_current++;
				echo $kol_current.') <b>'.$tutor['name'].'</b> (<a href="town.php?option='.$option.'&delpupil='.$tutor['id'].'">���������� �� �������</a>)<br>';
			}
			//������ ������� ������
			elseif ($tutor['confirmed'] == 2)
			{				
				if ($kol_prev == 0) 
				{
					if ($kol_current == 0) 
					{
						if ($kol_wait == 0) echo '<td>&nbsp;</td>';					
						echo '<td>&nbsp;</td>';					
					}
					else '</td>';
					echo '<td>';
				}
				$kol_prev++;
				echo $kol_prev.') <b>'.$tutor['name'].'</b><br>';
			}
		}			
		echo '</td>';
		if ($kol_prev == 0) 
		{
			if ($kol_current == 0) echo '<td>&nbsp;</td>';	
			echo '<td>&nbsp;</td>';	
		}
		echo '</tr></table>';
		echo '<br>';
	}	

	//������ ������ �� ��������������
	if ($char['reinc'] > 0 and $kol_wait+$kol_current <= 3)
	{
		?>
		<script type="text/javascript" src="../js/jquery.js"></script>
		<script type="text/javascript" src="../suggest_new/jquery.autocomplete.js"></script>
		<link href="../suggest_new/suggest.css" rel="stylesheet" type="text/css">
		<script type="text/javascript">
		$(document).ready(function() {
			$('#in_name').autocomplete({
				serviceUrl: "../suggest_new/suggest.php?users",
				minChars: 3,
				matchSubset: 1,
				autoFill: true,			
				width: 150,
				id: '#in_id'
			});
		});
		</script>
		<?
		echo '������� ��� ������, �������� ������ �� ������ ����� ��������:';	
		echo '<form name="input_form" id="input_form" action="town.php?option='.$option.'" method="POST" >	
			  <br><input id="in_name" name="in_name" type="text" size="20" value="" autocomplete="off">
			  <input id="in_id" name="in_id" type="hidden" size="20" value="0">
			  <input type="submit" name="addtutor" value="������ ������">
			  </form>';
	}
	echo '</center>';
	echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
}
if (function_exists("save_debug")) save_debug(); 

?>