<STYLE TYPE="text/css">
<!--
.���������1 {
	color: #FFFF00;
	text-decoration: underline;
	font-weight: normal;
	text-align: center;
	width: 100%;
}
.���������2 {
	color: #FFFFFF;
	font-weight: bold;
	text-align: center;
	width: 100%;
}
-->
</STYLE>
<?

if (function_exists("start_debug")) start_debug(); 

$da = getdate();
$current_month = GetGameCalendar_Year($da['year'],$da['mon'],$da['mday'])*12+GetGameCalendar_Month($da['year'],$da['mon'],$da['mday']);
if ($town!=0)
{
	echo'<link href="../suggest/suggest.css" rel="stylesheet" type="text/css">';
	echo'</head><center><font size=2 face=verdana color=ff0000>';

	$user_id = $char['user_id'];

	$userban=myquery("select * from game_ban where user_id=$user_id and type=2 and time>'".time()."'");
	if (mysql_num_rows($userban))
	{
		$userr = mysql_fetch_array($userban);
		$min = ceil(($userr['time']-time())/60);
		echo '<center><br><br><br>�� ���� �������� ��������� �� '.$min.' �����! ���� ��������� ������������ ����������!';
		{if (function_exists("save_debug")) save_debug(); exit;}
	}

	$bank_user=myquery("select * from game_bank where user_id='$user_id'");
	$bank=mysql_fetch_array($bank_user);

	if (!isset($_GET['do']))
    $do = '';
  else
    $do = $_GET['do'];

	echo '<font color=#F4F4F4 fave=Verdana,Tahoma,Arial size=2>';

	if ($do==1)
	{
		//������� ������ � ����
		if (!isset($_POST['see']))
		{
			echo'<form action="" method="post"><img src="http://'.img_domain.'/gorod/bank/1.jpg"><table border="1" cellpadding="0" style="border-collapse: collapse" width="98%" bordercolor="777777" bgcolor="223344">';
			echo'<tr><td><center><b><font color=#FFFF66>���������� �������� �����! � ���� '.$char['GP'].' �������� �������</td></tr></table>';
			echo'<table border="0" cellpadding="8" cellspacing="1" style="border-collapse: collapse" width="96%" bgcolor="111111"><tr><td></td></tr></table>';
			echo'<table border="1" cellpadding="0" style="border-collapse: collapse" width="98%" bordercolor="777777" bgcolor="223344"><tr><td align="center">
			����� �����: <input name="money" type="text" size="10" maxlength="10">&nbsp;&nbsp;&nbsp;<input type="submit" value="���������"></td></tr><tr><td align="center"><input type="button" value="����� � ���������� ������" onClick=location.replace("town.php?option='.$option.'")>
			</tr></td><input name="see" type="hidden" value=""><input name="town_id" type="hidden" value="'.$town.'"></table></form>';
		}
		else
		{
			if ($_POST['town_id']==$town)
			{
				$money=(int)$_POST['money'];
				if ($money>0 and $money<=9999999999)
				{
					$prov=myquery("select user_id from game_users where gp>=$money and user_id='$user_id'");
					if (mysql_num_rows($prov))
					{
						$check = mysql_result(myquery("SELECT COUNT(*) FROM game_bank WHERE user_id='$user_id'"),0,0);
						if ($check!=0)
						{
							$result=myquery("update game_bank set summa=summa+'$money' where user_id='$user_id'");
						}
						else
						{
							$result=myquery("insert into game_bank (user_id, summa) values ('$user_id', '$money')");
						}
						$result=myquery("update game_users set GP=GP-'$money',CW=CW-'".($money*money_weight)."' where user_id='$user_id'");
						setGP($user_id,-$money,30);
						echo'<br><br><font color=ff0000><b>�� '.echo_sex('����','������').' '.$money.' ������� �� ������� ����</b></font><br><meta http-equiv="refresh" content="1;url=town.php?option='.$option.'">';
						$result=myquery("insert into game_bank_log (user_id_from, name_from, summa,time) values ('$user_id', '".$char['name']."','$money',".time().")");
					}
					else echo '��������� ������<meta http-equiv="refresh" content="5;url=town.php?option='.$option.'">';
				}
				else
					echo '������� ������������ �����<meta http-equiv="refresh" content="5;url=town.php?option='.$option.'">';
			}
			else
				echo '�� ���������� �� � ��� ������<meta http-equiv="refresh" content="5;url=town.php">';
		}
	}

	if ($do==2)
	{
		//������� ������ �� �����
		if (!isset($_POST['see']))
		{
			echo'<form action="" method="post"><img src="http://'.img_domain.'/gorod/bank/1.jpg"><table border="1" cellpadding="0" style="border-collapse: collapse" width="98%" bordercolor="777777" bgcolor="223344">';
			echo'<tr><td><center><b><font color=#FFFF66>�� ����� ������� ����� '.$bank['summa'].' �������!</td></tr></table>';
			echo'<table border="0" cellpadding="8" cellspacing="1" style="border-collapse: collapse" width="96%" bgcolor="111111"><tr><td></td></tr></table>';
			echo'<table border="1" cellpadding="0" style="border-collapse: collapse" width="98%" bordercolor="777777" bgcolor="223344"><tr><td align="center">
			������� ����� ������� ������ �����: <input name="money" type="text" size="10" maxlength="10">&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="�����"></td></tr><tr><td align="center"><input type="button" value="����� � ���������� ������" onClick=location.replace("town.php?option='.$option.'")>
			</tr></td><input name="see" type="hidden" value=""><input name="town_id" type="hidden" value="'.$town.'"></table></form>';
		}
		else
		{
			if ($_POST['town_id']==$town)
			{
				$money=(int)$_POST['money'];
				if ($money>0 and $money<=9999999999)
				{
					$prov=myquery("select * from game_bank where summa>=$money and user_id='$user_id'");
					if (mysql_num_rows($prov))
					{
						$result=myquery("update game_bank set summa=summa-'$money' where user_id='$user_id'");
						$result=myquery("update game_users set GP=GP+'$money',CW=CW+'".($money*money_weight)."' where user_id='$user_id'");
						setGP($user_id,$money,31);
						echo'<br><br><font color=ff0000><b>�� '.echo_sex('����','�����').' '.$money.' �������</b></font><br><meta http-equiv="refresh" content="1;url=town.php?option='.$option.'">';
						$result=myquery("insert into game_bank_log (user_id_to, name_to, summa,time) values ('$user_id', '".$char['name']."','$money',".time().")");
					}
					else echo '��������� ������<meta http-equiv="refresh" content="1;url=town.php?option='.$option.'">';
				}
				else
					echo '������� ������������ �����<meta http-equiv="refresh" content="5;url=town.php?option='.$option.'"';
			}
			else
				echo '�� ���������� �� � ��� ������<meta http-equiv="refresh" content="5;url=town.php">';
		}
	}


	if ($do==3)
	{
		$prov = myquery("SELECT * FROM game_bank_db_kr WHERE vid=1 AND user_id=$user_id AND game_month_end>=$current_month");
		//�������� ������
		if (!isset($_POST['see']))
		{
			echo'<div id="content" onclick="hideSuggestions();"><form action="" method="post" autocomplete="off"><img src="http://'.img_domain.'/gorod/bank/1.jpg"><table border="1" cellpadding="0" style="border-collapse: collapse" width="98%" bordercolor="777777" bgcolor="223344">';
			echo'<tr><td><center><b><font color=#FFFF66>� ���� �� ������� ����� '.$bank['summa'].' �������!';
			if($char['clevel']<=5) 
			{
				echo'<br />�� �� ������ '.$char['clevel'].' ������, ���� �� ��������� ���������� ������';
				echo'</td></tr></table>
				<input type="button" value="����� � ���������� ������" onClick=location.replace("town.php?option='.$option.'")';
			}
			elseif (mysql_num_rows($prov)) 
			{
				echo'<br />� ���� ���� ������������ ������! ���� �� ��������� ���������� ������';
				echo'</td></tr></table>
				<input type="button" value="����� � ���������� ������" onClick=location.replace("town.php?option='.$option.'")';
			}
			else
			{ 
				echo'</td></tr></table>';
				echo'<table border="1" cellpadding="0" style="border-collapse: collapse" width="98%" bordercolor="777777" bgcolor="223344">';
				echo'<tr><td align="center"><span class="���������1">�� ������� ����� ��������� �������� ����� � ������� 5% �� ����� (�������� ����� ������/������ ����� ���������)</span></td></tr></table>';
				echo'<table border="2" cellpadding="0" width="98%" bordercolor="#C0FFFF" bgcolor="223344">
				<tr><td>����� ����� ��� ���������: </td><td><input id="money" name="money" type="text" size="10" maxlength="10"></td></tr>
				<tr><td>����: </td><td><input name="name" type="text" id="keyword" size="30" maxsize="50" onkeyup="handleKeyUp(event)"><div style="display:none;" id="scroll"><div id="suggest"></div></div></td></tr>
				<tr><td>����� ��������� ��� ����������: </td><td><textarea name="messaga" cols="30" rows="6"></textarea></td></tr>
				<tr><td colspan="2" align="center"><input type="submit" value="���������"> &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="����� � ���������� ������" onClick=location.replace("town.php?option='.$option.'")>
				</tr></td><input name="see" type="hidden" value=""><input name="town_id" type="hidden" value="'.$town.'"></table></form></div><script>init();document.getElementById("money").focus();</script>';
			}
		}
		else
		{
			if ($_POST['town_id']==$town)
			{
				if (!mysql_num_rows($prov))
				{
					$money=(double)$_POST['money'];
					if ($money>0 and $money<=9999999999 and $char['clevel']>8)
					{
						$name_komu = $_POST['name'];
						$name=myquery("select user_id from game_users where name='$name_komu'");
						if (!mysql_num_rows($name)) $name=myquery("select user_id from game_users_archive where name='$name_komu'");
						if (mysql_num_rows($name))
						{
							$kom = round($money*0.05,2);
							//$kom = $kom*(1-0.05*$char['MS_TORG']);
							list($nam)=mysql_fetch_array($name);
							if ($char['clan_id']>0)
							{
								$checkclan = myquery("SELECT * FROM game_clans WHERE clan_id=".$char['clan_id']." AND (glava=$nam OR zam1=$nam OR zam2=$nam OR zam3=$nam) AND (glava=$user_id OR zam1=$user_id OR zam2=$user_id OR zam3=$user_id)");
								if (mysql_num_rows($checkclan)>0)
								{
									$kom=0;
								}
							}
							$prov=myquery("select user_id from game_bank where summa>=('".($money+$kom)."') and user_id='$user_id'");
							if (mysql_num_rows($prov))
							{
								list($host1) = mysql_fetch_array(myquery("SELECT host FROM game_users_active WHERE user_id='$user_id'"));
								list($host2) = mysql_fetch_array(myquery("SELECT host FROM game_users_active WHERE user_id='$nam'"));
								//if($host1!=$host2)
								//{
								$sel=myquery("select user_id from game_bank where user_id='$nam'");
								if(mysql_num_rows($sel))
								{
									$mes=''.$char['name'].' ���������� �� ���� ���� �� ���������������  ����������� ����� '.$money.' �������.';
									if (!empty($_POST['messaga']))
									{
										$mes.="<br><br><hr>����������� � ��������:<br /><br />".mysql_real_escape_string($_POST['messaga']);
									}
									$result=myquery("update game_bank set summa=summa+'$money' where user_id='$nam'");
									$result=myquery("update game_bank set summa=summa-'$money'-'$kom' where user_id='$user_id'");

									$result=myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES ('$nam', '0', '��������������� ����������� ����', '$mes','0','".time()."')");

									$result=myquery("insert into game_bank_log (user_id_from, name_from, user_id_to, name_to, summa,host_from,host_to,time) values ('$user_id', '".$char['name']."','$nam','$name_komu','$money','$host1','$host2','".time()."')");
									echo'<br><font color=ff0000><b>�� '.echo_sex('��������','���������').' '.$money.' �������</b></font><br>';
									echo'<br><font color=ff0000><b>� ���� �������� �������� ����� -  '.$kom.' �������</b></font><br><meta http-equiv="refresh" content="2;url=town.php?option='.$option.'">';
								}
								else echo '� ������� ������ ��� �������� ������� ������<meta http-equiv="refresh" content="3;url=town.php?option='.$option.'">';
								//}
								//else echo '����������� �������<meta http-equiv="refresh" content="3;url=town.php?option='.$option.'">';
							}
							else echo '��������� ������ �� ����� ������� ����� (� ������ ������������ �����)<meta http-equiv="refresh" content="3;url=town.php?option='.$option.'">';
						}
						else echo '������ �� ����������<meta http-equiv="refresh" content="3;url=town.php?option='.$option.'">';
					}
					else
						echo '������� ������������ �����, ������ ���������� ������ ������� �� 9 ������<meta http-equiv="refresh" content="5;url=town.php?option='.$option.'"';          
				}
				else
					echo '������ ���������� ������ ��� ������������ �������<meta http-equiv="refresh" content="5;url=town.php?option='.$option.'"'; 
			}         
			else
				echo '�� ���������� �� � ��� ������<meta http-equiv="refresh" content="5;url=town.php">';
		}
	}

	if ($do==5)
	{
		die('�� �������� �� ������ ������� �������!');
		$max_kredit = $char['clevel']*200;
		//����� ������
		if (!isset($_POST['see']))
		{
			echo'<form action="" method="post"><img src="http://'.img_domain.'/gorod/bank/1.jpg"><table border="1" cellpadding="0" style="border-collapse: collapse" width="98%" bordercolor="777777" bgcolor="223344">';
			echo'<tr><td><center><b><font color=#FFFF66>� ���� �� ������� ����� '.$bank['summa'].' �������!';
			if($char['clevel']<=7) echo' �� �� ������ '.$char['clevel'].' ������, ���� �� ��������� ����� �������';
			echo'</td></tr></table>';
			echo'<table border="1" cellpadding="0" style="border-collapse: collapse" width="98%" bordercolor="777777" bgcolor="223344">';
			echo'<tr><td align="center"><span class="���������2">�� ���� �������������� ���� � ����� ��������� ������ �����!</span></td></tr></table>';
			$prov = myquery("SELECT * FROM game_bank_db_kr WHERE vid=1 AND user_id=$user_id AND game_month_end>=$current_month");
			if (mysql_num_rows($prov))
			{
			$kredit = mysql_fetch_array($prov);
			$summa_begin = $kredit['summa_begin'];
			$month_begin = $kredit['game_month_begin'];
			$month_end = $kredit['game_month_end'];
			$summa_end = $kredit['summa_end'];
			$procent = $summa_end-$summa_begin;
			$delta1 = $month_end - $month_begin;
			$delta2 = $current_month - $month_begin;
			$current_kredit = $summa_begin + round($procent*($delta2/$delta1),2);
			echo'<table border="2" cellpadding="0" width="98%" bordercolor="#C0FFFF" bgcolor="223344"><tr><td>
			� ���� ��� ������� ������. �� ������ ��� �������?</td></tr><tr><td>
			�� ������� ������: ����� ������� = '.$summa_begin.', ����������� �������� ='.round($procent*($delta2/$delta1),2).' �����.
			</td></tr>
			<tr><td colspan="2">����� �������� ������ ����� ��� ����� �������</td></tr>
			<tr><td colspan="2" align="center"><input type="submit" value="�������� ������">';
			}
			else
			{
			echo'<table border="2" cellpadding="0" width="98%" bordercolor="#C0FFFF" bgcolor="223344"><tr><td>
			�� ����� ���� �� ������ ����� ������?: </td><td>
			<select name="kredit_srok">
			<option value="0"></option>
			<option value="1">�� 6 ������� �������(������ 15% �������)</option>
			<option value="2">�� 1 ������� ���(������ 25% �������)</option>
			<option value="3">�� 2 ������� ����(������ 35% �������)</option>
			<option value="4">�� 3 ������� ����(������ 45% �������)</option>
			</select>
			</td></tr>
			<tr><td colspan="2" class="���������1">
			(��������! 1 ������� ����� = 1 ��� ��������� ���������!)<br />
			(��������! �� �������� �������� ����� � ���� ����� �������� ����� � ������� 100 �����)<br />
			(��������! �� ��������� ������� ����� ��� ������ 7 ������� ������� � ���� ����� ����� ������� �������� �������������. ���� � ���� �� ��� ������ �� ����� ������� ����� ��� �������� ������� - � ���� ����� ����� � ������� ���� ��� ��������� ����� �������! ����� �� ������ ��������� �� �������!)<br />
			</td></tr>
			<tr><td>������������ ����� ���������� ���� �������:</td><td>'.$max_kredit.' �����</td></tr>
			<tr><td>����� ����� �� ��������:</td><td><input type="text" value="0" maxsize=15 size=15 name="money"></td></tr>
			<tr><td colspan="2" align="center"><input type="submit" value="����� ������">';
			}
			echo ' &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="����� � ���������� ������" onClick=location.replace("town.php?option='.$option.'")>
			</tr></td><input name="see" type="hidden" value=""><input name="town_id" type="hidden" value="'.$town.'"></table></form>';
		}
		else
		{
			if ($_POST['town_id']==$town)
			{
				$prov = myquery("SELECT * FROM game_bank_db_kr WHERE vid=1 AND user_id=$user_id AND game_month_end>=$current_month");
				if (!mysql_num_rows($prov))
				{
					$money=(int)$_POST['money'];
					if ($money>0 and $money<=9999999999 and $char['clevel']>=8 AND isset($_POST['money']) AND isset($_POST['kredit_srok']) AND $_POST['kredit_srok']>0 AND $_POST['kredit_srok']<5)
					{
						if ($money>$max_kredit) $money=$max_kredit;
						$kom = 100;
						//$kom = $kom*(1-0.05*$char['MS_TORG']);
						switch ($_POST['kredit_srok'])
						{
							case 1:
								$money_end=$money+$money*0.15/2;
								$time_end = time()+6*24*60*60;
								$game_month_end = $current_month+6;
							break;
							
							case 2:
								$money_end=$money+$money*0.25;
								$time_end = time()+12*24*60*60;
								$game_month_end = $current_month+12;
							break;
							
							case 3:
								$money_end=$money+$money*0.35+$money*0.35;
								$time_end = time()+24*24*60*60;
								$game_month_end = $current_month+24;
							break;
							
							case 4:
								$money_end=$money+$money*0.45+$money*0.45+$money*0.45;
								$time_end = time()+36*24*60*60;
								$game_month_end = $current_month+36;
							break;
						}
						$prov=myquery("select user_id from game_bank where summa>=$kom and user_id='$user_id'");
						if (mysql_num_rows($prov))
						{
							myquery("INSERT INTO game_bank_db_kr (user_id,vid,summa_begin,time_begin,summa_end,time_end,game_month_end,game_month_begin) VALUES ($user_id,1,$money,".time().",$money_end,$time_end,$game_month_end,$current_month)");
							myquery("update game_bank set summa=summa-$kom where user_id=$user_id");
							myquery("update game_bank set summa=summa+$money where user_id=$user_id");
							echo '<br /><br /><br /><span class="���������2">����������! ��������� ����� '.$money.' ����� ������� ���������� �� ���� ������� ���� � �����!</span><meta http-equiv="refresh" content="3;url=town.php?option='.$option.'">';
						}
						else echo '<br /><br /><br />��������� ������ �� ����� ������� ����� ��� ������ ����� �� �������� �������� �����<meta http-equiv="refresh" content="3;url=town.php?option='.$option.'">';
					}
					else
						echo '<br /><br /><br />������� ������������ ����� ��� ��������� ������<meta http-equiv="refresh" content="5;url=town.php?option='.$option.'"';          
				}
				else
				{
					$kredit = mysql_fetch_array($prov);
					$summa_begin = $kredit['summa_begin'];
					$month_begin = $kredit['game_month_begin'];
					$month_end = $kredit['game_month_end'];
					$summa_end = $kredit['summa_end'];
					$procent = $summa_end-$summa_begin;
					$delta1 = $month_end - $month_begin;
					$delta2 = $current_month - $month_begin;
					$current_kredit = $summa_begin + round($procent*($delta2/$delta1),2);
					$prov1=myquery("select user_id from game_bank where summa>=$current_kredit and user_id='$user_id'");
					if (mysql_num_rows($prov1))
					{
						myquery("UPDATE game_bank SET summa=summa-$current_kredit WHERE user_id=$user_id");
						myquery("DELETE FROM game_bank_db_kr WHERE id=".$kredit['id']."");
						echo '<br /><br /><br /><span class="���������2">���� ������ ������� �������! ������� ��� '.echo_sex('���������','����������').' � ��� ����!</span><meta http-equiv="refresh" content="3;url=town.php?option='.$option.'">';
					}
					else
						echo '<br /><br /><br />� ���� ��� ����� '.$current_kredit.' �� ����� ������� �����<meta http-equiv="refresh" content="5;url=town.php?option='.$option.'"';          
				} 
		   }
			else
				echo '<br /><br /><br />�� ���������� �� � ��� ������<meta http-equiv="refresh" content="5;url=town.php">';
		}
	}

	if ($do==6)
	{
		die('�� �������� �� ��������� ������ �� ���������!');
		//������� �����
		if (!isset($_POST['see']))
		{
			echo'<img src="http://'.img_domain.'/gorod/bank/1.jpg">
			<table border="1" cellpadding="0" style="border-collapse: collapse" width="98%" bordercolor="777777" bgcolor="223344">';
			echo'<tr><td><center><b><font color=#FFFF66>� ���� �� ������� ����� '.$bank['summa'].' �������!';
			$prov = myquery("SELECT * FROM game_bank_db_kr WHERE vid=1 AND user_id=$user_id AND game_month_end>=$current_month");
			if($char['clevel']<=7) 
			{
				echo'<br />�� �� ������ '.$char['clevel'].' ������, ���� �� ��������� ������ ������';
				echo'</td></tr></table>
				<input type="button" value="����� � ���������� ������" onClick=location.replace("town.php?option='.$option.'")';
			}
			elseif (mysql_num_rows($prov)) 
			{
				echo'<br />� ���� ���� ������������ ������! ���� �� ��������� ������ ������';
				echo'</td></tr></table>
				<input type="button" value="����� � ���������� ������" onClick=location.replace("town.php?option='.$option.'")';
			}
			else
			{
				$prov = myquery("SELECT * FROM game_bank_db_kr WHERE vid=2 AND user_id=$user_id AND game_month_end<=$current_month"); 
				if (mysql_num_rows($prov))
				{
					echo '<form name="form2" action="" method="post">
					<table border="2" cellpadding="0" width="98%" bordercolor="#C0FFFF" bgcolor="223344"><tr><td>
					� ���� ���� ������, ������� �� ������ ��������:</td></tr>';
					while ($vklad = mysql_fetch_array($prov))
					{
						echo '<tr><td>';
						echo '<input type="radio" name="vklad_down" value="' . $vklad['id'] . '">����� ������: '.$vklad['summa_begin'].', ����������� ��������: '.($vklad['summa_end']-$vklad['summa_begin']).', �����: '.$vklad['summa_end'].'</td></tr>';
					}
					echo '<tr><td align="center"><input type="submit" value="������� ����� � ��������">';
					echo '<input name="see" type="hidden" value=""><input name="town_id" type="hidden" value="'.$town.'"></td></tr></table></form><br />';
				}            
				echo'<form action="" method="post">
				<table border="1" cellpadding="0" style="border-collapse: collapse" width="98%" bordercolor="777777" bgcolor="223344">';
				echo'<tr><td align="center"><span class="���������2">�� ���� �������������� ���� � ����� �����!</span></td></tr></table>';
				echo'<table border="2" cellpadding="0" width="98%" bordercolor="#C0FFFF" bgcolor="223344"><tr><td>
				�� ����� ���� �� ������ �������� ������� �����?: </td><td>
				<select name="vklad_srok">
				<option value="0"></option>
				<option value="1">�� 1 ������� ���(������ 5% �������)</option>
				<option value="2">�� 2 ������� ���(������ 7% �������)</option>
				<option value="3">�� 3 ������� ����(������ 8% �������)</option>
				<option value="4">�� 5 ������� ���(������ 10% �������)</option>
				<option value="5">�� 8 ������� ���(������ 15% �������)</option>
				</select>
				</td></tr>
				<tr><td colspan="2" class="���������1">
				(��������! 1 ������� ����� = 1 ��� ��������� ���������!)<br />
				(��������! �� ���������� ������ � ���� ����� �������� ����� � ������� 100 �����)<br />
				(��������! �� ������� �� ��������� �������������� �����������! ��� ��������� ������ �� '.echo_sex('������','������').' �������� ����� �����!)<br />
				</td></tr>
				<tr><td>�� ����� ����� �� �������� �����:</td><td><input type="text" value="0" maxsize=15 size=15 name="money"></td></tr>
				<tr><td colspan="2" align="center"><input type="submit" value="������� �����">';
				echo ' &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="����� � ���������� ������" onClick=location.replace("town.php?option='.$option.'")>
				</tr></td><input name="see" type="hidden" value=""><input name="town_id" type="hidden" value="'.$town.'"></table></form></td></tr></table>';
			}
		}
		else
		{
			if ($_POST['town_id']==$town)
			{
				$prov = myquery("SELECT * FROM game_bank_db_kr WHERE vid=1 AND user_id=$user_id AND game_month_end>=$current_month");
				if (!mysql_num_rows($prov))
				{
					if (isset($_POST['vklad_down']) AND $_POST['vklad_down']>0)
					{
						//�������� ���� �����
						$prov = myquery("SELECT * FROM game_bank_db_kr WHERE vid=2 AND user_id=$user_id AND game_month_end<=$current_month AND id=".$_POST['vklad_down'].""); 
						if (mysql_num_rows($prov))
						{
							$vklad = mysql_fetch_array($prov);
							$add = $vklad['summa_end'];
							myquery("DELETE FROM game_bank_db_kr WHERE id=".$_POST['vklad_down']."");
							myquery("UPDATE game_bank SET summa=summa+$add WHERE user_id=$user_id");
							echo '<br /><br /><br /><span class="���������2">���� ����� ��������� �� ���� ������� ����! ������� ��� '.echo_sex('���������','����������').' � ��� ����!</span><meta http-equiv="refresh" content="3;url=town.php?option='.$option.'">';
						}
						else
							echo '<br /><br /><br />������ ��� ��������� ������<meta http-equiv="refresh" content="5;url=town.php?option='.$option.'"'; 
					}
					else
					{
						//������ ����� �����
						$money=(int)$_POST['money'];
						if ($money>0 and $money<=9999999999 and $char['clevel']>=8 AND isset($_POST['money']) AND isset($_POST['vklad_srok']) AND $_POST['vklad_srok']>0 AND $_POST['vklad_srok']<6)
						{
							$kom = 100;
							//$kom = $kom*(1-0.05*$char['MS_TORG']);
							switch ($_POST['vklad_srok'])
							{
								case 1:
									$money_end=$money+$money*0.05;
									$time_end = time()+12*24*60*60;
									$game_month_end = $current_month+12;
								break;
								
								case 2:
									$money_end=$money+$money*0.07+$money*0.07;
									$time_end = time()+24*24*60*60;
									$game_month_end = $current_month+24;
								break;
								
								case 3:
									$money_end=$money+$money*0.08+$money*0.08+$money*0.08;
									$time_end = time()+36*24*60*60;
									$game_month_end = $current_month+36;
								break;
								
								case 4:
									$money_end=$money+$money*0.1+$money*0.1+$money*0.1+$money*0.1+$money*0.1;
									$time_end = time()+60*24*60*60;
									$game_month_end = $current_month+60;
								break;

								case 5:
									$money_end=$money+$money*0.15+$money*0.15+$money*0.15+$money*0.15+$money*0.15+$money*0.15+$money*0.15+$money*0.15;
									$time_end = time()+96*24*60*60;
									$game_month_end = $current_month+96;
								break;
	}
							$prov=myquery("select user_id from game_bank where summa>=".($kom+$money)." and user_id='$user_id'");
							if (mysql_num_rows($prov))
							{
								myquery("INSERT INTO game_bank_db_kr (user_id,vid,summa_begin,time_begin,summa_end,time_end,game_month_end,game_month_begin) VALUES ($user_id,2,$money,".time().",$money_end,$time_end,$game_month_end,$current_month)");
								myquery("update game_bank set summa=summa-".($kom+$money)." where user_id=$user_id");
								echo '<br /><br /><br /><span class="���������2">�������! ���� ����� � ������� '.$money.' ����� ������� ������ � ��� � ����!</span><meta http-equiv="refresh" content="3;url=town.php?option='.$option.'">';
							}
							else echo '<br /><br /><br />��������� ������ �� ����� ������� ����� ��� �������� ��������� ����� ������ (� ������ ����� ����� �� �������� ������)<meta http-equiv="refresh" content="3;url=town.php?option='.$option.'">';
						}
						else
							echo '<br /><br /><br />������� ������������ ����� ��� ��������� ������<meta http-equiv="refresh" content="5;url=town.php?option='.$option.'"'; 
					  }         
				}
				else
					echo '<br /><br /><br />� ���� ������� ������������ ������. ����� �� ���� �� �����������!<meta http-equiv="refresh" content="5;url=town.php?option='.$option.'"';          
		   }
			else
				echo '<br /><br /><br />�� ���������� �� � ��� ������<meta http-equiv="refresh" content="5;url=town.php">';
		}
	}

	if ($do==4)
	{
		OpenTable('title');
		echo'<br><center>';
		QuoteTable('open');
		//��������� ������� ����
		if($char['GP']>=10)
		{
			myquery("INSERT IGNORE INTO game_bank (user_id,summa) VALUES ('$user_id','0')");
			myquery("UPDATE game_users SET GP=GP-10,CW=CW-'".(10*money_weight)."' WHERE user_id='$user_id'");
			setGP($user_id,10,32);
			echo'���������� � ��������� � ����� ����� ������ �������� �����!';
		}
		else
		{
			echo'� ���� ��� 10 �����. �� �� ����� ��������� ������� ����� ����� ��������';
		}
		echo'<center><br><br><br>&nbsp;&nbsp;&nbsp;<input type="button" value="����� � ���������� ������" onClick=location.href="town.php?option='.$option.'">&nbsp;&nbsp;&nbsp;';
		QuoteTable('close');
		echo'<br>';
		OpenTable('close');
	}


	if($do=='')
	{
    echo ("!!!!!");
		echo'<img src="http://'.img_domain.'/gorod/bank/1.jpg"><table border="1" cellpadding="0" style="border-collapse: collapse" width="98%" bordercolor="777777" bgcolor="223344">';
		echo'<tr><td><center><font color=#FFFF00 size=2 face=Verdana,Tahoma,Arial><b>��������������� ����������� ����</td></tr></table><center>';

		if(!mysql_num_rows($bank_user))
		{
			OpenTable('title');
			echo'<br>';
			QuoteTable('open');
			echo'<center> �� ��� �� ������ � ����� ����� ���� ������� ����. ������ ��� ������� ������? ��������� ������ ���������� 10 �����.<br><br>';
			echo'<input type="button" value="��, � ���� ������� ����� ������� ����" onClick=location.href="town.php?option='.$option.'&do=4">';
			QuoteTable('close');
			echo'<br>';
			OpenTable('close');
		}
		else
		{
			OpenTable('title');
			echo'<br>';
			QuoteTable('open',"100%");
			echo '<center><font color=#F4F4F4 fave=Verdana,Tahoma,Arial size=2>';

			echo'<br>� ���� ��� ������ � ����� ����� ������� ���� �'.$user_id.'';
			echo'<br>����� �� ����� ������� �����: '.$bank['summa'].' �����';
			echo'<br>==========================================';
			/*echo'<br>�������: ';
			$str_kredit='� ���� ��� ����������� ��������';
			$prov = myquery("SELECT * FROM game_bank_db_kr WHERE user_id=$user_id AND vid=1 AND game_month_end>=$current_month");
			if (mysql_num_rows($prov))
			{
				$kredit = mysql_fetch_array($prov);
				$summa_begin = $kredit['summa_begin'];
				$month_begin = $kredit['game_month_begin'];
				$month_end = $kredit['game_month_end'];
				$summa_end = $kredit['summa_end'];
				$procent = $summa_end-$summa_begin;
				$delta1 = $month_end - $month_begin;
				$delta2 = $current_month - $month_begin;
				$procent = round($procent*($delta2/$delta1),2);
				$str_kredit = "<br /><span class=\"���������2\">� ���� �������� ������ �� �����: ".$kredit['summa_begin'].". ����� ��������� �� ������ ����������: ".($kredit['summa_end']-$kredit['summa_begin'])." ( �� ������� ������ ����������� �������� = $procent �����). ���� ��������� ������� - ����� ".($kredit['game_month_end']-$current_month)." ������� �������!</span>";
			}
			echo $str_kredit;
			echo'<br>������� ������: ';
			$str_kredit='� ���� ��� ������� �������';
			$prov = myquery("SELECT * FROM game_bank_db_kr WHERE user_id=$user_id AND vid=2");
			if (mysql_num_rows($prov))
			{
				$str_kredit="";
				while ($kredit = mysql_fetch_array($prov))
				{
					$summa_begin = $kredit['summa_begin'];
					$month_begin = $kredit['game_month_begin'];
					$month_end = $kredit['game_month_end'];
					$summa_end = $kredit['summa_end'];
					$procent = $summa_end-$summa_begin;
					$delta1 = $month_end - $month_begin;
					$delta2 = $current_month - $month_begin;
					$procent = round($procent*($delta2/$delta1),2);
					$str_kredit = "<br /><span class=\"���������2\">� ���� �������� ����� �� �����: ".$kredit['summa_begin'].". ����� ��������� �� ������ ����������: ".($kredit['summa_end']-$kredit['summa_begin']).".";
					if (($kredit['game_month_end']-$current_month)<=0) $str_kredit.='�� ������ ������� ���� �����</span>';
					else $str_kredit.="���� �������� ������ - ����� ".($kredit['game_month_end']-$current_month)." ������� �������!</span>";
					$str_kredit.="<br />";
				}
			}
			echo $str_kredit;*/
			echo'<br>';
			echo'<br>�� ������<ol><div align="left">';
			echo'<li><a href="town.php?option='.$option.'&do=1">��������� ����� �� ������� �����</a>';
			echo'<li><a href="town.php?option='.$option.'&do=2">����� ����� � �������� �����</a>';
			echo'<li><a href="town.php?option='.$option.'&do=3">��������� ����� � �������� ����� �� ������� ���� ������� ������</a>';
			//echo'<li><a href="town.php?option='.$option.'&do=5">����� ������</a>';
			//echo'<li><a href="town.php?option='.$option.'&do=6">������� ������� �����</a>';
			echo'</div>';
			QuoteTable('close');
			echo'<br>';
			OpenTable('close');
		}
	}
	echo'<br /><br /><br /><div style="font-size:small;text-align:right;width:100%">����������� ��� ������� ������ "��������"</div>';
}

if (function_exists("save_debug")) save_debug(); 

?>