<?
if (function_exists("start_debug")) start_debug(); 
if ($town!=0)
{
	$userban=myquery("select * from game_ban where user_id='$user_id' and type=2 and time>'".time()."'");
	if (mysql_num_rows($userban))
	{
		$userr = mysql_fetch_array($userban);
		$min = ceil(($userr['time']-time())/60);
		echo '<center><br><br><br>�� ���� �������� ��������� �� '.$min.' �����! ���� ��������� ������������ ������!';
		{if (function_exists("save_debug")) save_debug(); exit;}
	}
	$img='http://'.img_domain.'/race_table/elf/table';
	echo'<table width=100% border="0" cellspacing="0" cellpadding="0"><tr><td width="1" height="1"><img src="'.$img.'_lt.gif"></td><td background="'.$img.'_mt.gif"></td><td width="1" height="1"><img src="'.$img.'_rt.gif"></td></tr>
	<tr><td background="'.$img.'_lm.gif"></td><td style="text-align:center" background="'.$img.'_mm.gif" valign="top" width="100%" height="100%">';
	
	if (!isset($_GET['yes']))
	{
		echo '<center><span style="font-size:13px;font-family:verdana;color:#FF0000;font-weight:800;">� ���� ������ '.$char['win'].' '.pluralForm($char['win'],'������','������','�����').'.<br />';
	}
	
	if (!isset($_GET['select_sklon']))
	{
		if ($char['sklon']==0) echo '&nbsp;������ � ���� ��� ����������.<br />';
		if ($char['sklon']==1) echo '<img src="http://'.img_domain.'/sklon/neutral.gif" border="0" alt="����������� ����������" title="����������� ����������">&nbsp;������ � ���� ����������� ����������.<br />';
		if ($char['sklon']==2) echo '<img src="http://'.img_domain.'/sklon/light.gif" border="0" alt="������� ����������" title="������� ����������">&nbsp;������ � ���� ������� ����������.<br />';
		if ($char['sklon']==3) echo '<img src="http://'.img_domain.'/sklon/dark.gif" border="0" alt="������ ����������" title="������ ����������">&nbsp;������ � ���� ������ ����������.<br />';
		echo '</span></center><br /><br />';
		
		if ($char['sklon']==0)
		{
			echo '�� ������ ������� ���� ���������� �� 100 �����!<br /><br /><br />';
			if ($char['win']>=100)
			{
				$prov = 0;
				if ($char['clan_id']!=0)
				{
					list($clan_sklon) = mysql_result(myquery("SELECT sklon FROM game_clans WHERE clan_id=".$char['clan_id'].""),0,0);
					if ($clan_sklon==0)
					{
						echo '�� �������� � �����, �� ����� ������ ����� ��� �� ��������� ���������� ����� �����. ������� �� �� ������ ������� ���� ����������. ������� ������ ���� ����������� ���������� � �����. ����� ����� �� ������ ��������� � ��� ���� � ������� ���� ����������.<meta http-equiv="refresh" content="4;url=town.php?option='.$option.'">';
						echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
						if (function_exists("save_debug")) save_debug(); 
					}
					$prov = $clan_sklon;
					echo '�� ��� �������� � ����� � ������ ������� ������ ���������� �����. � ������ ����� ����������� ';
					switch ($prov)
					{
						case 1: echo '����������� ����������';break;
						case 2: echo '������� ����������';break;
						case 3: echo '������ ����������';break;
					}
					echo '<br /><br />';
				}
				if (($prov==0) OR ($prov==1))
				{
					echo '<input type="button" value="������� ����������� ����������" onClick="location.href=\'town.php?option='.$option.'&town_id='.$town.'&select_sklon=1\'"><br /><br />';
				}
				if (($prov==0) OR ($prov==2))
				{
					echo '<input type="button" value="������� ������� ����������" onClick="location.href=\'town.php?option='.$option.'&town_id='.$town.'&select_sklon=2\'"><br /><br />';
				}
				if (($prov==0) OR ($prov==3))
				{
					echo '<input type="button" value="������� ������ ����������" onClick="location.href=\'town.php?option='.$option.'&town_id='.$town.'&select_sklon=3\'"><br /><br />';
				}
			}
		}
		else
		{
			echo '�� ������ �������� ���� ���������� �� 50 �����!<br /><br /><br />';
			if ($char['win']>50)
			{
				echo '<input type="button" value="�������� ���� ����������" onClick="location.href=\'town.php?option='.$option.'&town_id='.$town.'&select_sklon\'"><br /><br />';
			}
		}
	}
	else
	{
		$skl = (int)$_GET['select_sklon'];
		if ($char['sklon']==0)
		{
			if ($char['win']>=100)
			{
				$prov = 1;
				if ($char['clan_id']!=0)
				{
					list($clan_sklon) = mysql_result(myquery("SELECT sklon FROM game_clans WHERE clan_id=".$char['clan_id'].""),0,0);
					if ($clan_sklon!=$skl)
					{
						$prov = 0;
					}
				}
				if ($prov==1)
				{
					if (!isset($_GET['yes']))
					{
						echo '�� ������������� ������ ������� ����� ����������, �������� �� ��� 100 �����?<br /><br />';
						switch ($skl)
						{
							case 1: echo '<input type="button" value="��, � ���� ������� ����������� ����������" onClick="location.href=\'town.php?option='.$option.'&town_id='.$town.'&select_sklon=1&yes\'"><br /><br />';break;
							case 2: echo '<input type="button" value="��, � ���� ������� ������� ����������" onClick="location.href=\'town.php?option='.$option.'&town_id='.$town.'&select_sklon=2&yes\'"><br /><br />';break;
							case 3: echo '<input type="button" value="��, � ���� ������� ������ ����������" onClick="location.href=\'town.php?option='.$option.'&town_id='.$town.'&select_sklon=3&yes\'"><br /><br />';break;
						}
						echo '<input type="button" value="���, � �� ���� �������� ����������" onClick="location.href=\'town.php?option='.$option.'&town_id='.$town.'\'"><br /><br />';
					}
					else
					{
						myquery("UPDATE game_users SET sklon=$skl,win=win-100 WHERE user_id=$user_id");
						echo '���������� �� ������ ����� ����������! <meta http-equiv="refresh" content="4;url=town.php?option='.$option.'">';
					}
				}
				else
				{
					echo '�� �������� � ����� � �� ������ ������� ����������, ������������ �� ���������� �����.<meta http-equiv="refresh" content="4;url=town.php?option='.$option.'"';
				}
			}
			else
			{
				echo '��� ������ ���������� ���� ����� �� ����� 100 �����!<meta http-equiv="refresh" content="4;url=town.php?option='.$option.'">';
			}
		}
		else
		{
			if ($char['win']>=50)
			{
				if (!isset($_GET['yes']))
				{
					echo '�� ������������� ������ �������� ���� ����������, �������� �� ��� 50 �����?<br /><br />';
					echo '<input type="button" value="��, � ���� �������� ���� ����������" onClick="location.href=\'town.php?option='.$option.'&town_id='.$town.'&select_sklon&yes\'"><br /><br />';
					echo '<input type="button" value="���, � �� ���� �������� ����������" onClick="location.href=\'town.php?option='.$option.'&town_id='.$town.'\'"><br /><br />';
				}
				else
				{
					myquery("UPDATE game_users SET sklon=0,win=win-50 WHERE user_id=$user_id");
					echo '���������� � ������� ����� ����������! <meta http-equiv="refresh" content="4;url=town.php?option='.$option.'">';
				}
			}
			else
			{
				echo '��� ������ ���������� ���� ����� �� ����� 50 �����!<meta http-equiv="refresh" content="4;url=town.php?option='.$option.'">';
			}
		}    
	}

	echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
}
if (function_exists("save_debug")) save_debug(); 
?>