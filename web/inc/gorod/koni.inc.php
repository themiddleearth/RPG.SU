<?
if (function_exists("start_debug")) start_debug(); 

if ($town!=0)
{
	if (isset($town_id) AND $town_id!=$town)
	{
	echo'�� ���������� � ������ ������!<br><br><br>&nbsp;&nbsp;&nbsp;<input type="button" value="�����" onClick=location.href="town.php">&nbsp;&nbsp;&nbsp;';
	}
$userban=myquery("select * from game_ban where user_id=$user_id and type=2 and time>'".time()."'");
if (mysql_num_rows($userban))
{
	$userr = mysql_fetch_array($userban);
	$min = ceil(($userr['time']-time())/60);
	echo '<br><br><br>�� ���� �������� ��������� �� '.$min.' �����! ���� ��������� ������������ ��������!';
	{if (function_exists("save_debug")) save_debug(); exit;}
}
$img='http://'.img_domain.'/race_table/orc/table';
echo'<table width=100% border="0" cellspacing="0" cellpadding="0" align=center><tr><td width="1" height="1"><img src="'.$img.'_lt.gif"></td><td background="'.$img.'_mt.gif"></td><td width="1" height="1"><img src="'.$img.'_rt.gif"></td></tr>
<tr><td background="'.$img.'_lm.gif"></td><td style="text-align:center" background="'.$img.'_mm.gif" valign="top">';
echo'�������:<br>';

$img='http://'.img_domain.'/race_table/orc/table';
if (isset($_GET['buy']))
{
	$sel=myquery("select * from game_vsadnik where town='".$town."' and id='".$_GET['buy']."'");
	$row=mysql_fetch_array($sel);
	
	$est_horses = 0;
	$max_horse = 1;
	$est_horses3 = 0;
	$est_horses4 = 0;
	$count_horses = mysql_result(myquery("SELECT COUNT(*) FROM game_users_horses WHERE user_id=".$user_id.""),0,0);
	$check_horses = myquery("SELECT build_id FROM houses_users WHERE build_id IN (6,7,8) AND buildtime<".time()." AND user_id=".$user_id."");
	$est_horses=mysql_num_rows($check_horses);
	if ($est_horses>0)
	{
		while (list($build_id)=mysql_fetch_array($check_horses))
		{
			if ($build_id==6) $max_horse=$max_horse+1;
			elseif ($build_id==7) $max_horse=$max_horse+2;
			elseif ($build_id==8) $max_horse=$max_horse+3;
		}
	}
	else
	{
		$est_horses = 1;
	}
	
	$check_skill=myquery("SELECT level FROM game_users_skills WHERE user_id=".$user_id." AND skill_id=25");
	if (mysql_num_rows($check_skill)==1)
	{
		list($skill)=mysql_fetch_array($check_skill);
	}
	else
	{	
		$skill=0;
	}
	
	if ($char['GP'] >= $row['cena'] AND $skill >= $row['vsad'] AND mysql_num_rows($sel) AND $count_horses<$max_horse)
	{
		if (!isset($_POST['see']))
		{
			echo '<form name=koni action="" method=post>';
			echo '<font color=#00FFFF><h3>'.$row['nazv'].' (��. ��������: '.$row['vsad'].', ������� ��������� +'.$row['ves'].', ����: '.$row['cena'].' �������';			
			echo ').</h5></font>';
			echo '<br>';
			if ($max_horse>1)
			{
				echo '� ���� �� �������� '.$count_horses.' �� '.$max_horse.' ��������<br />';
			}
			if ($row['img']!='') echo '<img src=http://'.img_domain.'/vsd/'.$row['img'].'.jpg>';
			echo '<p align=justify>'.$row['opis'].'</p>';
			echo '<input type="submit" value="������"><input type="hidden" name="see"><input name="town_id" type="hidden" value="'.$town.'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			echo '<input type="button" value="�����" onClick=location.replace("town.php?option='.$option.'")>';
			echo '</form>';
		}
		else
		{
			$already_horses = mysqlresult(myquery("SELECT COUNT(*) FROM game_users_horses WHERE user_id=$user_id AND horse_id=".$row['id'].""),0,0);
			if ($already_horses==0)
			{
				echo'<img src="http://'.img_domain.'/gorod/rohan/k.jpg"><br>';
				$ves=$row['ves'];
				$vsad=$row['vsad'];
				$cena=$row['cena'];
				$check=myquery("SELECT horse_id FROM game_users_horses WHERE user_id=".$user_id." AND used=1");
				if (mysql_num_rows($check)>0)
				{
					list($horse_id)=mysql_fetch_array($check);
					list($ves_minus,$vsad_minus) = mysql_fetch_array(myquery("SELECT ves,vsad FROM game_vsadnik WHERE id=".$horse_id.""));
					$ves-=$ves_minus;
					$vsad-=$vsad_minus;
				}
				$up=myquery("UPDATE game_users SET vsadnik=vsadnik+".($vsad*vsad).", GP=GP-".$cena.",CW=CW-'".($cena*money_weight)."', CC=CC+$ves WHERE user_id=$user_id LIMIT 1");
				myquery("INSERT INTO game_users_horses (user_id,horse_id,life,golod,used) VALUES (".$user_id.",".$row['id'].",".($row['life_horse']-1).",0,1)");
				setGP($user_id,-$cena,42);
				echo'<center>�������!';
				echo '<meta http-equiv="refresh" content="2;url=town.php?option='.$option.'">';
			}
			else
			{
				echo '� ���� ��� ���� ����� �������. ������ �������� ���� ���������� ��������<br /><br />';
			}
		}
	}
	elseif ($skill<$row['vsad'])
	{
		echo '� ��� ������������� ������� �������� ����!';
	}
	elseif ($char['GP'] < $row['cena'])
	{
		echo '� ��� ������������ �����';
	}
	elseif ($max_horse<=$count_horses)
	{
		echo '�� �� ������ ��������� ����� ��� '.$max_horse.' '.pluralForm($max_horse,'�������','��������','��������').'';
	}
	else
	{
		echo'���-�� ������ �� ���, � �� �� ������ ������ ������!<br>';
	}
	echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
	{if (function_exists("save_debug")) save_debug(); exit;}
}

if (isset($_GET['sell']))
{
	echo'<img src="http://'.img_domain.'/gorod/rohan/k.jpg"><br>';
	
	$check=myquery("SELECT horse_id FROM game_users_horses WHERE user_id=".$user_id." AND used=1");
	if (mysql_num_rows($check)==1)
	{
		list($horse_id)=mysql_fetch_array($check);
		if (isset($_GET['sellnow']))
		{
			$sel=myquery("select * from game_vsadnik where id='".$horse_id."'");
			$rowww=mysql_fetch_array($sel);
		
			if ($rowww['town']==$town)
			{
				$g=ceil($rowww['cena']/2);
			}
			else
			{
				$g=ceil($rowww['cena']/4);
			}
			$c=$rowww['ves'];
			$up=myquery("UPDATE game_users SET vsadnik=vsadnik-".($rowww['vsad']*vsad).", GP=GP+".$g.",CW=CW+'".($g*money_weight)."', CC=CC-".$c." WHERE user_id=".$user_id." LIMIT 1");
			myquery("DELETE FROM game_users_horses WHERE user_id=".$user_id." AND used=1");
			setGP($user_id,$g,43);
			echo'<center>�������!';
			echo '<meta http-equiv="refresh" content="2;url=town.php?option='.$option.'">';
		}
		else
		{
			echo '<br /><br />�� ������������� ������ ������� ���� ��������?<br /><br /><br /><a href="town.php?option='.$option.'&sell&sellnow">��, � ���� ������� ��������</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="town.php?option='.$option.'">���, � �� ���� ��������� ��������</a><br /><br />';
		}
	}
	$img='http://'.img_domain.'/race_table/orc/table';
	echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
	{if (function_exists("save_debug")) save_debug(); exit;}
}

if (isset($_GET['eat']))
{
	echo'<img src="http://'.img_domain.'/gorod/rohan/k.jpg"><br>';
	$check=myquery("SELECT horse_id FROM game_users_horses WHERE user_id=".$user_id." AND used=1");
	if (mysql_num_rows($check)==1)
	{
		list($horse_id)=mysql_fetch_array($check);	
		$golod = mysql_result(myquery("SELECT golod FROM game_users_horses WHERE user_id=$user_id AND horse_id=".$horse_id.""),0,0);
		if ($golod>0)
		{
			switch ($golod)
			{
				case 0: $state= '�����'; $k = 0; break;
				case 1: $state= '������ ��������'; $k = 1; break;
				case 2: $state= '��������'; $k = 2; break;
				case 3: $state= '����� ��������'; $k = 3; break;
				case 4: $state= '������������'; $k = 4; break;
				default: $state= '���������'; $k = 10; break;
			}
			
			$row = mysql_fetch_array(myquery("select * from game_vsadnik where id='".$horse_id."'"));
			if ($row['town']<>$town)
			{
				$k=$k*1.5;
			}
			if ($char['clevel']<12) $gp=0;
			else $gp = $k*$row['price_eat'];
			if (!isset($_GET['do']))
			{
				if ($char['GP']<$gp)
				{
						echo '� ���� ������������ ����� ��� ������� ��� ��� ������ �������.<br />';
				}
				elseif ($gp==0)
				{
					echo '���� ������� �������� �������������. ��� ��������� ����������� ��� <b>'.$state.'</b>. �� ������ ��������� ��������� ���.
						 <br />���� �� �� ������ ������� ������ ������� - �� ����� ������ �����!<br /><br />
					     ';
					echo '<a href="town.php?option='.$option.'&eat&do">��������� ��������</a>';	 
				}
				else 
				{
					echo '���� ������� �������� �������������. ��� ��������� ����������� ��� <b>'.$state.'</b>. ��������� ��� ��� ������ ������� ����������: '.$gp.' '.pluralForm($k,'������','������','�����').'.<br />���� �� �� ������ ������� ������ ������� - �� ����� ������ �����!<br /><br />';
					echo '<a href="town.php?option='.$option.'&eat&do">��������� '.$gp.' '.pluralForm($gp,'������','������','�����').' �� �������� ���������</a>';
				}
			}
			elseif ($char['GP']>=$gp)
			{
				if ($gp!=0)
				{
					$up=myquery("UPDATE game_users SET GP=GP-$gp,CW=CW-'".($gp*money_weight)."' WHERE user_id=$user_id LIMIT 1");
					setGP($user_id,-$gp,62);
				}
				myquery("UPDATE game_users_horses SET golod=0 WHERE horse_id=".$horse_id." AND user_id=$user_id");
				echo'<center>������� ���������!';
				echo '<meta http-equiv="refresh" content="3;url=town.php?option='.$option.'">';
			}
		}
	}
	echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
	{if (function_exists("save_debug")) save_debug(); exit;}
}

$check=myquery("SELECT horse_id FROM game_users_horses WHERE user_id=".$user_id." AND used=1");
if (mysql_num_rows($check)==1)
{
	list($horse_id)=mysql_fetch_array($check);
	echo'<img src="http://'.img_domain.'/gorod/rohan/k.jpg"><br>';
	$sel=myquery("select * from game_vsadnik where id='".$horse_id."'");
	$ro=mysql_fetch_array($sel);
	if ($ro['town']==$town)
	{
		echo'<br />� ���� ��� ���� ����! <br><br /><br />'.$ro['nazv'].' (��. ��������: '.$ro['vsad'].', ������� ��������� +'.$ro['ves'].', ����: '.$ro['cena'].' �������)<br>����� ���� �� ������ <br><br /><a href="town.php?option='.$option.'&sell">������� '.$ro['nazv'].'  (�� '.ceil($ro['cena']/2).' �����)</a>?';
	}
	else
	{
		echo'<br />� ���� ��� ���� ����! <br /><br /><br />'.$ro['nazv'].' (��. ��������: '.$ro['vsad'].', ������� ��������� +'.$ro['ves'].', ����: '.$ro['cena'].' �������)<br>�� �� ��� '.echo_sex('�������','��������').' �� � ���! <br><br /><br />����� ���� �� ������ <br><br /><a href="town.php?option='.$option.'&sell">������� '.$ro['nazv'].'  (�� '.ceil($ro['cena']/4).' �����)</a>?';
	}
	echo '<br /><br /><br />';
	$selgolod = myquery("SELECT golod FROM game_users_horses WHERE user_id=".$user_id." AND horse_id=".$horse_id."");
	if ($selgolod!=false AND mysql_num_rows($selgolod)>0)
	{
		$golod = mysql_result($selgolod,0,0);
		if ($golod>0)
		{
			echo '���� ������� �������� �������������! &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="town.php?option='.$option.'&eat">��������� ��������</a>';
		}
	}

	echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
	{if (function_exists("save_debug")) save_debug(); exit;}
}


echo'<img src="http://'.img_domain.'/gorod/rohan/k.jpg" width=480><br>';
echo'<table border=0>';
$sel=myquery("select * from game_vsadnik where town='$town'");
while ($row=mysql_fetch_array($sel))
{
	echo'<tr><td><a href="town.php?option='.$option.'&buy='.$row['id'].'">'.$row['nazv'].' (��. ��������: '.$row['vsad'].', ������� ��������� +'.$row['ves'].', ����: '.$row['cena'].' �������';
	echo ')</a></td><td></td></td>';
}
echo'</table>';
echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';

}

if (function_exists("save_debug")) save_debug(); 

?>