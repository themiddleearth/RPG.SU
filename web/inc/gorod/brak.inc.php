<?

if (function_exists("start_debug")) start_debug(); 

if ($town!=0)
{
	if (isset($POST['town_id']) AND $POST['town_id']!=$town)
	{
	echo'�� ���������� � ������ ������!<br><br><br>&nbsp;&nbsp;&nbsp;<input type="button" value="����� � �����" onClick=location.href="town.php">&nbsp;&nbsp;&nbsp;';
	}
$userban=myquery("select * from game_ban where user_id=$user_id and type=2 and time>'".time()."'");
if (mysql_num_rows($userban))
{
	$userr = mysql_fetch_array($userban);
	$min = ceil(($userr['time']-time())/60);
	echo '<center><br><br><br>�� ���� �������� ��������� �� '.$min.' �����! ���� ��������� �������� � ���� ��������������!';
	{if (function_exists("save_debug")) save_debug(); exit;}
}

$gp1 = 100;
$gp2 = 500;
$gp3 = 250;
$da = getdate();
if (($da['mon']==1 AND $da['mday']<=31)or($da['mon']==2 AND $da['mday']<=14)or($da['mon']==3 AND $da['mday']==8)or($da['mon']==7 AND $da['mday']==15))
{
	$gp1 = 0;
	$gp2 = 0;
	$gp3 = 0;
}

echo'<link href="../suggest/suggest.css" rel="stylesheet" type="text/css">';
$img='http://'.img_domain.'/race_table/orc/table';
echo'<table width=100% border="0" cellspacing="0" cellpadding="0" align=center><tr><td width="1" height="1"><img src="'.$img.'_lt.gif"></td><td background="'.$img.'_mt.gif"></td><td width="1" height="1"><img src="'.$img.'_rt.gif"></td></tr>
<tr><td background="'.$img.'_lm.gif"></td><td style="text-align:center;" background="'.$img.'_mm.gif" valign="top">';

if (!isset($_GET['brakopt']))
{
  echo'<img src="http://'.img_domain.'/wedding/screen1.jpg"><br>';
  echo'���� ��������������:<br>';

  echo '<br><font face="verdana">����������� ���� � ����� ��������������, ����� ����������! �������, �� '.echo_sex('������','������').' � ���, ��������� ���� ������, ����� �������� ���� ������ ���������� �����. �� ���� �� '.echo_sex('���������','����������').', ���, � ���������, '.echo_sex('������','��������').', �������� ����, �� � � ���� ������� ������ �� ������ ����... ����� � ���� ��������������, ������� �� ��� ������� ��� ������ ������! ��� ��������� �� ������������� ����� - 100 ������� - �������������� ��� ���� � ������� ��� ����������� ������, � ������� ������� �� ������ ������� ���� ����� � ������� ���������, ��� ������� ��� ������ �� ����� � 500 �������</br>';
  $img='http://'.img_domain.'/race_table/orc/table';

  echo '<br><a href="?option='.$option.'&brakopt=reg">����� � ���� ��������������</a><br>';
}
else
{
	if ($_GET['brakopt']=='reg')
	{
	$check1 = myquery("SELECT * FROM game_users_brak WHERE (user1='".$char['user_id']."' OR user2='".$char['user_id']."') LIMIT 1");
	$check2 = myquery("SELECT * FROM game_users_brak WHERE (user2='".$char['user_id']."' AND status=0) LIMIT 1");
	$check3 = myquery("SELECT * FROM game_users_brak WHERE (user1='".$char['user_id']."' AND status=0) LIMIT 1");
	$check4 = myquery("SELECT * FROM game_users_brak WHERE ((user1='".$char['user_id']."' OR user2='".$char['user_id']."') AND status=1) LIMIT 1");
	$check5 = myquery("SELECT * FROM game_users_brak WHERE ((user1='".$char['user_id']."' OR user2='".$char['user_id']."') AND status='".$char['user_id']."') LIMIT 1");
	$check6 = myquery("SELECT * FROM game_users_brak WHERE ((user1='".$char['user_id']."' OR user2='".$char['user_id']."') AND status>1  AND status<>'".$char['user_id']."') LIMIT 1");
		echo'
		<table width=100% border="0" cellspacing="0" cellpadding="0" align=center><tr><td>';

		if (!mysql_num_rows($check1))  echo'<center><img src="http://'.img_domain.'/wedding/screen2.jpg" width="470"><br>';
		elseif (mysql_num_rows($check2))  echo'<center><img src="http://'.img_domain.'/wedding/screen5.jpg"><br>';
		elseif (mysql_num_rows($check3))  echo'<center><img src="http://'.img_domain.'/wedding/screen9.jpg"><br>';
		elseif (mysql_num_rows($check4))  echo'<center><img src="http://'.img_domain.'/wedding/screen11.jpg"><br>';
		elseif (mysql_num_rows($check5))  echo'<center><img src="http://'.img_domain.'/wedding/screen1314.jpg"><br>';

		echo '</td></tr><tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>';
		$sost = '<...�������������...>';
		if (!mysql_num_rows($check1)) $sost = '�� �� ��������������� � �����';
		if (mysql_num_rows($check3)) $sost = '����� ������ ������ �� ����������� �����';
		if (mysql_num_rows($check2)) $sost = '�� ���� ��� ������ ������ �� ����������� �����';
		if (mysql_num_rows($check4)) $sost = '�� �������� � ������������������ �����';
		if (mysql_num_rows($check5)) $sost = '�� ���� ��� ������ ������ �� ���������� �������';
		if (mysql_num_rows($check6)) $sost = '����� ������ ������ �� ���������� �������';
		echo '<tr><td><font size=2 face="verdana,tahoma"><center>���� ���������: <u><font color="#00FFFF">'.$sost.'</font></u></center></font></td></tr>';
		echo '<tr><td>&nbsp;</td></tr>';
		if (!mysql_num_rows($check1))
		echo '<tr><td>��� ������������, ��� �� '.echo_sex('����','������').' ����� � ���� �������� ����, ����� ��������� �����, � ����� �������� ����� ��, ���� ���� � ����� ���� - ���� ������, ���� ������ ��������! ��� ��������� ��������� ���� � ������������� ������ ���, � ����������� ������ ������ � ������, ���� ������ �����!<br></td></tr><tr><td><font color="#FF0066" size=2 face="verdana,tahoma"><center><a href="?option='.$option.'&brakopt=newreg">������ ������ �� ����������� ������ �����</a></center></font></td></tr>';
		if (mysql_num_rows($check2))
		{
		$usr = mysql_fetch_array($check2);
		$selec = myquery("SELECT name FROM game_users WHERE user_id='".$usr['user1']."'");
		if (!mysql_num_rows($selec)) $selec = myquery("SELECT name FROM game_users_archive WHERE user_id='".$usr['user1']."'");
		list($name1) = mysql_fetch_array($selec);
		echo '<tr><td>'.echo_sex('��������','��������').' �� �� ��������� ���� � <b><font color="#FF0066">'.$name1.'</font></b>, � ��� �� ������ ������ ��� ������� � ���������, � ��� �� ������������ �������, �� ������ �� �� ����� ��������� � ��������, � ��� ������ '.echo_sex('��������','���������').' �����, ����� ���������� � ���������? �� ������� ������, ��� ��� �� ������ �����, � ������� ����� �� ���������� ���� ���������� ������<br></td></tr><tr><td><font color="#FF0066" size=2 face="verdana,tahoma"><center><a href="?option='.$option.'&brakopt=confirmreg">����������� �������� �� �������������� c ������� "'.$name1.'"</a></center></font></td></tr>';
		}
		if (mysql_num_rows($check3))
		{
		$usr = mysql_fetch_array($check3);
		$selec = myquery("SELECT name FROM game_users WHERE user_id='".$usr['user2']."'");
		if (!mysql_num_rows($selec)) $selec = myquery("SELECT name FROM game_users_archive WHERE user_id='".$usr['user2']."'");
		list($name1) = mysql_fetch_array($selec);
		echo '<tr><td>�� �������� �����! ���� ����� �������� ����, ��� ������ ���� ������ - ������ ��� ���������� � �������� ��������������! �� ������ ���� ���� �� ���������, ��� ����� �������.<br></td></tr><tr><td><font color="#FF0066" size=2 face="verdana,tahoma"><center><a href="?option='.$option.'&brakopt=delreg">������� ������ �� �������������� c ������� "'.$name1.'"</a></center></font></td></tr>';
		}
		if (mysql_num_rows($check4))
		echo '<tr><td>�� '.echo_sex('�����','������').' ����������? ��� �� ��������� �������� ����� ��������� ������? � ����� ������ �� ���� �������, ���� �� '.echo_sex('������','�������').' ��� ������ ������ �� ������?<br></td></tr><tr><td><font color="#FF0066" size=2 face="verdana,tahoma"><center><a href="?option='.$option.'&brakopt=razvod">������ ������ �� ���������� �������</a></center></font></td></tr>';
		if (mysql_num_rows($check5))
		echo '<tr><td>������, �� ������ '.echo_sex('�����','������').'?!� �������� ����� ���������, � ������� �� ��� ����� �������� ����� � '.$gp2.' �����<br></td></tr><tr><td><font color="#FF0066" size=2 face="verdana,tahoma"><center><a href="?option='.$option.'&brakopt=confirmrazvod">����������� �������� �� ���������� �������</a></center></font></td></tr>';
		if (mysql_num_rows($check1) AND !mysql_num_rows($check4) AND !mysql_num_rows($check5) AND !mysql_num_rows($check2) AND !mysql_num_rows($check3))
		echo '<tr><td><center><b><font face="arial" color="#FF0066" size=2>������, �� ��� ���� ��� ��������� ����� � ����� �����</font><b></center></td></tr>';
		echo '<tr><td>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr></table>';
	}
	if ($_GET['brakopt']=='delreg')
	{
		echo'<center><img src="http://'.img_domain.'/wedding/screen10.jpg"><br>';
		echo '<center><b><font face="arial" color="#FF0066" size=2>���� �������, �� ���� ��� �� ������ �������� ������ �����������, �� � ��������� ��� ������������� ������ '.echo_sex('���','����').'. ��� �, �������, ������ �� ������� ��������������.</font><b></center>';
		$up = myquery("DELETE FROM game_users_brak WHERE (user1 = '".$char['user_id']."' AND status=0)");
	}
	if ($_GET['brakopt']=='newreg')
	{
		if ($char['GP']>=$gp1)
		{
		  if (!isset($_GET['name']))
		  {
			echo'<center><img src="http://'.img_domain.'/wedding/screen3.jpg" height="300"><br>';
			echo'<div id="content" onclick="hideSuggestions();">��� �� '.echo_sex('���','��').', � ��� �� ������ ������ ��� ������� � ���������, � ��� �� ������������ �������, �� ������ �� �� ����� ��������� � ��������, � ��� ������ '.echo_sex('��������','���������').' �����, ����� ���������� � ���������? ���� �� ��������� ���� ��, ����� � ���������? ��� �������� � ����� ���� ������ � ���� ����� ������?';
		  echo '<br><center>����� ��� ������ ����������:<font size="1" face="Verdana" color="#ffffff"><input type="text" size="15" id="keyword" onkeyup="handleKeyUp(event)"><div style="display:none;" id="scroll"><div id="suggest"></div></div><br><br>
  <input name="" type="button" value="������ ������ �� ����������� �����" onClick="location.href=\'?option='.$option.'&brakopt=newreg&name=\'+document.getElementById(\'keyword\').value"></center></div><script>init();</script>';
		  }
		  else
		  {
		  if ($_GET['name']!=$char['name'])
		  {
			echo'<center><img src="http://'.img_domain.'/wedding/screen4.jpg"><br>';
			echo '<center>�� ��� �, ��� ������ ������, ����� �� ������ '.echo_sex('�������','��������').' �� �� ����� ���������� �������� ��� ���� ��� ��� � �� ����� ������� � ������ ������ <b><font color="#FF0066">'.$name.'</font></b>)� �� � ������ ������� �� ����������, ���� ��� ����� ������� �����, ������� ������ ������ ��������, ����� ������ ������������� ! ����� ������������� � ����� ����� ����� ����������� ������� ����� ������ ��� ���� ����� ���������� ��������������� � ��� ����� ����� ����������� ������. � ���� �������� ����� �� ������ ������ ����� - '.$gp1.' �������';
			$up = myquery("UPDATE game_users SET GP=GP-$gp1,CW=CW-'".($gp1*money_weight)."' WHERE user_id='".$char['user_id']."'");
			setGP($user_id,-$gp1,33);
			$sel = myquery("SELECT user_id FROM game_users WHERE name='".$_GET['name']."'");
			if (!mysql_num_rows($sel)) $sel = myquery("SELECT user_id FROM game_users_archive WHERE name='".$_GET['name']."'"); 
			list($user_id1) = mysql_fetch_array($sel);
			$up = myquery("INSERT INTO game_users_brak (user1,user2,status) VALUES ('".$char['user_id']."','$user_id1','0')");
		  }
		  else
		  {
			echo '<center>�� ����� ����� � ���� ������� ����� � ������� '.$gp1.' �������</center>';
			setGP($user_id,-$gp1,33);
			$up = myquery("UPDATE game_users SET GP=GP-$gp1,CW=CW-'".($gp1*money_weight)."' WHERE user_id='".$char['user_id']."'");
		  }
		  }
		}
		else
		{
		echo '� ���� ������������ ������� ��� ������ ����� ������ ����� (���� ���������� ����� ��� ���� '.$gp1.' �������). ������� �����, ����� ������� �������� ���� ������';
		}
	}
	if ($_GET['brakopt']=='confirmreg')
	{
	$check2 = myquery("SELECT * FROM game_users_brak WHERE (user2='".$char['user_id']."' AND status=0) LIMIT 1");
	if (mysql_num_rows($check2))
	{
		if (!isset($otvet))
		{
			$user_id1 = mysql_fetch_array($check2);
			$sel = myquery("SELECT name FROM game_users WHERE user_id='".$user_id1['user1']."'");
			if (!mysql_num_rows($sel)) $sel = myquery("SELECT name FROM game_users_archive WHERE user_id='".$user_id1['user1']."'");
			list($name1)=mysql_fetch_array($sel);
			echo'<center><img src="http://'.img_domain.'/wedding/screen5.jpg"><br>';
			echo '���� ���������� ����, ���� ������� ������ ��� ������� ��� ����� ���� ��� �������� - ��������� ���� �����! �����, ��� �� ������� �����, �� �������� ������� ��� ���� ���������� �����. ������ �� ������ ������� ������� �������� ��� ������� ���-�� ������';
			echo '<center>';
			echo ''.echo_sex('��������','��������').' �� �� �� ����������� ����� � �������: <b><font color="#FF0066">'.$name1.'</font></b><br><br>
			<input name="" type="button" value="��, ��������������� ��� ����" onClick="location.href=\'?option='.$option.'&town_id='.$town.'&brakopt=confirmreg&otvet=1\'"><br><br>
			<input name="" type="button" value="���, � ������ ����������� �����" onClick="location.href=\'?option='.$option.'&town_id='.$town.'&brakopt=confirmreg&otvet=0\'"><br><br></center>';

		}
		else
		{
		if ($otvet=='0')
		{
			echo'<center><img src="http://'.img_domain.'/wedding/screen8.jpg"><br>';
			echo '<center><b><font face="arial" color="#FF0066" size=2>��� �� ����������, �� �� ��������� ��������, ��� �� '.echo_sex('�����','������').' ���������� �� ���������� �����. ��� �, ������, ��� ������ ������ ���� - ���� ����� ��� �������, ���� ���� �� �������� �� '.echo_sex('�������','�������').' ����� ��� - � �� ������� ���� �������� ������!</font><b></center>';
			$up = myquery("DELETE FROM game_users_brak WHERE (user2 = '".$char['user_id']."' AND status=0)");
		}
		elseif ($otvet=='1')
		{
			echo'<center><img src="http://'.img_domain.'/wedding/screen6.jpg"><br>';
			echo '<center><b><font face="arial" color="#FF0066" size=2>������ ����, ����� � ����� ������ ��������� ������������ �� ����� � �����! � �� ��������� �� ���� ������  ������ ����� � ������������, � �� �� �������� ��� ������ ����� � �����, �� �������, �� �����, �� ���������, �� ���� ������! ����������� ���! </font><b></center>';
			$cur_time=date("d.m.Y",time());
			$up = myquery("UPDATE game_users_brak SET status=1,datareg='$cur_time' WHERE (user2 = '".$char['user_id']."' AND status=0)");
		}

		}
	}
	}

	if ($_GET['brakopt']=='razvod')
	{
		if ($char['GP']>=$gp2)
		{
		   $check4 = myquery("SELECT * FROM game_users_brak WHERE ((user1='".$char['user_id']."' OR user2='".$char['user_id']."') AND status=1) LIMIT 1");
		   $usr = mysql_fetch_array($check4);
		   if ($usr['user1']==$char['user_id']) $usr2 = $usr['user2'];
		   elseif ($usr['user2']==$char['user_id']) $usr2 = $usr['user1'];
		   $sel = myquery("SELECT name FROM game_users WHERE user_id='$usr2'");
		   if (!mysql_num_rows($sel)) $sel = myquery("SELECT name FROM game_users_archive WHERE user_id='$usr2'");
		   list($name)=mysql_fetch_array($sel);
		   echo'<center><img src="http://'.img_domain.'/wedding/screen12.jpg"><br>';
		   echo '<center>����, ���� ����� <b><font color="#FF0066">'.$name.'</font></b> ����������, �� ������� ���� �� �� �����, ������� ������ ����. ����� ������������� � ����� ����� ����� ����������� ������� ����� ������ ��� ���� ����� ���������� ����������. � ������� �� ��� ����� �������� ����� �� ������ ������ ����� - '.$gp3.' �������';
			$up = myquery("UPDATE game_users_brak SET status='$usr2' WHERE id='".$usr['id']."'");
	   }
		else
		{
		echo '� ���� ������������ ������� ��� ������ ����� ������ ����� (���������� ����� ��� ���� '.$gp2.' �������). ������� �����, ����� ������� �������� ���� ������';
		}
	}
	if ($_GET['brakopt']=='confirmrazvod')
	{
	$check5 = myquery("SELECT * FROM game_users_brak WHERE ((user1='".$char['user_id']."' OR user2='".$char['user_id']."') AND status='".$char['user_id']."') LIMIT 1");
	if (mysql_num_rows($check5))
	{
		if (!isset($otvet))
		{
		   $usr = mysql_fetch_array($check5);
		   if ($usr['user1']==$char['user_id']) $usr2 = $usr['user2'];
		   elseif ($usr['user2']==$char['user_id']) $usr2 = $usr['user1'];
		   $sel = myquery("SELECT name FROM game_users WHERE user_id='$usr2'");
		   if (!mysql_num_rows($sel)) $sel = myquery("SELECT name FROM game_users_archive WHERE user_id='$usr2'");
		   list($name)=mysql_fetch_array($sel);
		   echo'<center><img src="http://'.img_domain.'/wedding/screen1314.jpg"><br>';
			echo '<center>'.echo_sex('��������','���������').' �� �� ����������� �������, ��������, �����������, ���� � �������: <b><font color="#FF0066">'.$name.'</font></b><br><br>
			<input name="" type="button" value="��, �������� ��� ������" onClick="location.href=\'?option='.$option.'&town_id='.$town.'&brakopt=confirmrazvod&otvet=1\'"><br><br>
			<input name="" type="button" value="���, � ������ �������" onClick="location.href=\'?option='.$option.'&town_id='.$town.'&brakopt=confirmrazvod&otvet=0\'"><br><br></center>';

		}
		else
		{
		if ($otvet=='1')
		{
		   $usr = mysql_fetch_array($check5);
		   echo'<center><img src="http://'.img_domain.'/wedding/screen15.jpg"><br>';
			echo '<center><b><font face="arial" color="#FF0066" size=2>�� ����� '.echo_sex('��������','��������').'... �� ���������� ������� ���� ��������� ���� � �� ��������� �������� ������ � ������� � ������� �� ��� �������� ����� �� ������ - '.$gp3.' �����!</font><b></center>'; 
			$up = myquery("DELETE FROM game_users_brak WHERE ((user1 = '".$char['user_id']."' OR user2 = '".$char['user_id']."') AND status='".$char['user_id']."')");
			$up = myquery("UPDATE game_users SET GP=GP-$gp3,CW=CW-'".($gp3*money_weight)."' WHERE user_id='".$usr['user1']."'");
			$up = myquery("UPDATE game_users_archive SET GP=GP-$gp3,CW=CW-'".($gp3*money_weight)."' WHERE user_id='".$usr['user1']."'");
			setGP($usr['user1'],-$gp3,33); 
			$up = myquery("UPDATE game_users SET GP=GP-$gp3,CW=CW-'".($gp3*money_weight)."' WHERE user_id='".$usr['user2']."'");
			$up = myquery("UPDATE game_users_archive SET GP=GP-$gp3,CW=CW-'".($gp3*money_weight)."' WHERE user_id='".$usr['user2']."'");
			setGP($usr['user2'],-$gp3,33); 
		}
		elseif ($otvet=='0')
		{
			echo '<center><b><font face="arial" color="#FF0066" size=2>� ���������! � ����� �� ���-���� �����</font><b></center>';
			$up = myquery("UPDATE game_users_brak SET status=1 WHERE ((user1 = '".$char['user_id']."' OR user2 = '".$char['user_id']."') AND status='".$char['user_id']."')");
		}

		}
	}
	}

}
echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';

}

if (function_exists("save_debug")) save_debug(); 

?>