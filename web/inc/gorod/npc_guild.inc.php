<?php

if (function_exists("start_debug")) start_debug(); 

if ($town!=0)
{
	if (isset($town_id) AND $town_id!=$town)
	{
	echo'�� ���������� � ������ ������!<br><br><br>&nbsp;&nbsp;&nbsp;<input type="button" value="�����" onClick=location.href="act.php">&nbsp;&nbsp;&nbsp;';
	}

	$userban=myquery("select * from game_ban where user_id='$user_id' and type=2 and time>'".time()."'");
	if (mysql_num_rows($userban))
	{
		$userr = mysql_fetch_array($userban);
		$min = ceil(($userr['time']-time())/60);
		echo '<center><br><br><br>�� ���� �������� ��������� �� '.$min.' �����! ���� ��������� ������� � �������!';
		{if (function_exists("save_debug")) save_debug(); exit;}
	}

	//�������� ���� �������
	echo'<b>������� ��������� �� ���������</b>';
	
	// �������� �� ������� ���������
	$test = myquery("SELECT * From game_users_guild Where user_id='$user_id'");
	
	$f=0; //����
	$prof_cost=1000; //��������� ��������� ���������
	if (mysql_num_rows($test)==1) $guild = mysql_fetch_array($test); //���������� ����������� ������� � ������
	else $guild['guild_lev']=0;
	
	if (isset($_GET['quest_now'])) 
	{
		$f=1;
		echo '<p align="left">����� ������� ����������� �� ��� �������.<br />
		<i>- ��� ����� ����� �������� �� ���������. � ������� �� ���� ������������ �� ����������� ������ ������ ��������. ��, </i>- ���� ����� �������� ������ �������, <i>- �� ���������� � ��� �������� ���������...� ��������� ������. <b>'.$prof_cost.'</b> ����� � �� �������������� � ���.</i></p><br />
		<input type="submit" value="��������� ������" onclick="location.replace(\'town.php?option='.$option.'&part1&new_guild\')"></input>&nbsp;&nbsp;&nbsp;
		<input type="submit" value="������������ � ����" onclick="location.replace(\'town.php?option='.$option.'&quest_later\')"></input>';
	}
	
	if (isset($_GET['new_guild'])) 
	{
		$test = myquery("SELECT * From game_users_guild Where user_id='$user_id'");
		if (mysql_num_rows($test)==0)
		{
			$test2 = myquery("SELECT * From game_users Where user_id=$user_id and GP>$prof_cost");
			if (mysql_num_rows($test2)==0)
			{
				echo '<p align="left">����� ������� ������������ ����������:</br>
					 <i>- �� ���� �������� �������? �� ������ ��� � ���� ������, �� �� ���� ������ ������������ ��� �����!</p>';
					 $f=1;
			}	
			else
			{
				myquery("INSERT INTO game_users_guild (user_id) VALUES ($user_id)");
				myquery("UPDATE game_users SET GP=GP-$prof_cost,CW=CW-".($prof_cost*money_weight)." WHERE user_id=$user_id");
				setGP($user_id,-$prof_cost,106); 
				echo '<br /><br /><b>����������� � ����������� ����������� � ������� � ��������� ����� ���������!</b>';
				$f=1;
				$guild=0;
			}
		}
	}
	
	if (isset($_GET['quest_later'])) 
	{
		$f=1;
	}
	
	if (mysql_num_rows($test)==0 and $f!=1 and $town==12)
	{
		echo '<center><br />���� �� ���� ��������� �� ��������� ��������, ��� � ����� ������� ��������� ���� ��� ��� ����� �����������<br /><br />
		<ul><li><a href="town.php?option='.$option.'&quest_now">���������� � ������ ������� ���������</a></li><br />
		<li><a href="town.php?option='.$option.'&quest_later">��������������� ������������ ��������</a></li></ul></center>';
	}
	
	
	// �������� ������� �������
	echo'<table border="0" cellpadding="8" cellspacing="1" style="border-collapse: collapse" width="96%" bgcolor="111111"><tr><td></td></tr></table>';
		
	$sel = myquery("SELECT game_npc.*,game_npc_template.* FROM game_npc,game_npc_template WHERE game_npc.npc_quest_guild='$town' AND game_npc.npc_quest_end_time>".time()." AND game_npc_template.npc_id=game_npc.npc_id");
	if (mysql_num_rows($sel)>0)
	{
		$npc = mysql_fetch_array($sel);
		$npc_exp = floor($npc['npc_exp_max']*1.2);
		$end_time = $npc['npc_quest_end_time']-time();
		echo'� ������ ������ � ����� ������� ��������� ����� �� ���� ��������:<br><br>';
		QuoteTable('open');
		$player=$npc;
		$quest_id=$npc['npc_quest_id'];

		echo'<font face="Verdana" size="2" color="#f3f3f3"><b>'.$player['npc_name'].'</b></font></div>';
		echo '<img src="http://'.img_domain.'/npc/'.$player['npc_img'].'.gif" border="0" align="left">';

		echo '<table cellpadding="2" cellspacing="0" width="100%" border="0">';
		
		echo '<tr>
		<td>����</td><td><div align="right">'.$player['npc_str'].'&plusmn;'.$player['npc_str_deviation'].'</td></tr>
		<tr><td>��������</td><td><div align="right">'.$player['npc_pie'].'&plusmn;'.$player['npc_pie_deviation'].'</td></tr>
		<tr><td>������</td><td><div align="right">'.$player['npc_vit'].'&plusmn;'.$player['npc_vit_deviation'].'</td></tr>
		<tr><td>������������</td><td><div align="right">'.$player['npc_dex'].'&plusmn;'.$player['npc_dex_deviation'].'</td></tr>
		<tr><td>��������</td><td><div align="right">' . $player['npc_spd'] . '&plusmn;'.$player['npc_spd_deviation'].'</td></tr>
		<tr><td>���������</td><td><div align="right">'.$player['npc_ntl'].'&plusmn;'.$player['npc_ntl_deviation'].'</td></tr>
		<tr><td>���� �� ������</td><td><div align="right">' . $player['npc_exp_max'] . '</td></tr></table>';
		QuoteTable('close');

		QuoteTable('open');
		echo'<font size=2 color=#F0F0F0><div align="justify">�� ������ ����� � ��� ������� �� �������� ����� �������! ������� � ��� ����� ����, ��� ������ ������� � ������� ��� ��� ������ (��� ����� ������ ����� ����) ��� �������������� ���������� �������! � �������� ������ �� ��������� ����� ������ �� ����� ���� <b><font color=#FF0000>'.$npc_exp.'</font></b> ����� �����, � ����� ��������� �������� ������� ���� �� ����� ���������. �� �� ������ ������������ ����, ��� �������� �� ������ ������ - ������ ������� �������� ����������� - ������� �������� ��� �������������� �������� ����� �������. � ���! ����, � ���� ������ 1 ������� � ���� ������� ����� �������� �� ����, ���������� �� �� � �������� ����, ��� �������� �� ������ ������.';
		QuoteTable('close');

		QuoteTable('open');
		$min = floor($end_time/60);
		$sec = $end_time-$min*60;
		echo '<font size=2 color=#FFFF80>������� ������������� ��� '.$min.'  ���. '.$sec.' ���.</font>';
		QuoteTable('close');

		$quest_users = mysql_result(myquery("SELECT COUNT(*) FROM game_quest_users WHERE quest_id='$quest_id' AND sost='".$npc['npc_id']."'"),0,0);
		QuoteTable('open');
		if ($quest_users==0) echo '<font size=2 color=#FFFF80>��� �� ���� ����� �� ��������� �� ���� �������.</font>';
		else echo '<font size=2 color=#FFFF80>�� ���� �������� ��� �������� '.$quest_users.' ������.</font>';
		QuoteTable('close');

		$sel_quest = myquery("SELECT sost FROM game_quest_users WHERE user_id='$user_id' AND quest_id='$quest_id'");
		if (mysql_num_rows($sel_quest)>0)
		{
			list($sost) = mysql_fetch_array($sel_quest);

			if ($sost==$npc['npc_id'])
			{
				$sel_item = myquery("SELECT item_uselife FROM game_items WHERE user_id='$user_id' AND used=0 AND item_for_quest='$quest_id' AND priznak=0");
				if (mysql_num_rows($sel_item)>0)
				{
					if (!isset($take_head))
					{
						QuoteTable('open');
						echo'<font size=2 color=#F0F0F0><div align="center">����������! �� '.echo_sex('����','�����').' ������� "'.$npc['npc_name'].'" � �� �������� ��� ��������!<br>';
						echo'<form action="" method="post"><input type="submit" name="take" value="������ ����� ���� �������"><input type="hidden" name="take_head"><input name="town_id" type="hidden" value="'.$town.'">';
						QuoteTable('close');
					}
					else
					{
						QuoteTable('open');
						$it = mysql_fetch_array($sel_item);
						$proc = $it['item_uselife']/100;
						$npc_exp = floor($npc_exp*$proc*(100+$guild['guild_lev'])/100);
						echo'<div align="center"><font size=2 color=#F0F0F0>����������! ��� ���� "'.$npc_exp.'" ����� ����� �� ������� �������!<br>';
						echo'<br>*** <font size=2 color=#00FF00>�� ��������� � �������� ����� <font color=#FF0000>'.$npc_exp.'</font> ����� </font> ***';
						myquery("UPDATE game_npc SET npc_quest_guild=0,npc_quest_end_time=0,npc_quest_id=0 WHERE npc_quest_guild=$town");
						myquery("UPDATE game_users SET EXP=EXP+$npc_exp WHERE user_id=$user_id");
						setEXP($user_id,$npc_exp,5);
						myquery("DELETE FROM game_items WHERE item_for_quest=$quest_id");
						myquery("DELETE FROM game_quest_users WHERE quest_id=$quest_id");
						myquery("INSERT INTO game_npc_guild_log (user_id,user_name,time_end,vremya,exp) VALUES ($user_id,'".$char['name']."',".time().",'".date("d-m-Y H:i",time())."',$npc_exp)");
						
						//������ ��������
						$r = mt_rand(1,100);
						$base_chance=25;
						if ($r<$base_chance+$guild['guild_lev']*3)
						{
							$r = mt_rand(1,100);
							if ($r<=30) {$prize_name="�������� ��������";}
							elseif ($r<=55) {$prize_name="������ ��������";}
							elseif ($r<=75) {$prize_name="���������� ��������";}
							elseif ($r<=90) {$prize_name="������� ��������";}
							else {$prize_name="���������� ��������";}
							if (isset($prize_name) and $prize_name<>"")
							{
								$check_item=myquery("SELECT id FROM game_items_factsheet WHERE name like '".$prize_name."'");
								if (mysql_num_rows($check_item)>0)
								{
									list($id)=mysql_fetch_array($check_item);
									$Item = new Item();
									$ar = $Item->add_user($id,$user_id);
									echo'<br>*** <font size=2 color=#00FF00>�� �������� ������� <b>'.$prize_name.'</b></font> ***';
								}
							}
						}						
								
						// ��������� ������ ���������, ���� ���� ����
						if (isset($guild['guild_times']) and $guild['guild_times']>=0)
						{
							$guild['guild_times']++;
							myquery("UPDATE game_users_guild SET guild_times=guild_times+1 WHERE user_id=$user_id");
							$i=$guild['guild_times'];
							$j=0;
							do 
							{
								$j++;
								$i=$i-$j*5-5;
							} while ($i > 0);
							if ($i==0 and $j<26) 
							{
								$guild['guild_lev']++;
								myquery("UPDATE game_users_guild SET guild_lev=guild_lev+1 WHERE user_id=$user_id");
							}
						}
						
						QuoteTable('close');
					}
				}
				else
				{
					QuoteTable('open');
					echo'<font size=2 color=#F0F0F0><div align="justify">�� ��� '.echo_sex('����','�����').' ������� �� �������� �������, � �� ���� ��� �� ��� ���������! ������� � ��� ����� ����, ��� ������ ������� � ������� ��� ��� ������ (��� ����� ������ ����� ����) � �������� �������������� ���������� �������!';
					QuoteTable('close');
				}
			}
			else
			{
				QuoteTable('open');
				echo'<font size=2 color=#F0F0F0><div align="justify">�� ��� '.echo_sex('����','�����').' ������� �� �������� �������, �� �� '.echo_sex('����','������').' ����� ���. ��� �� ����� ����� �����! ������ �������� ������� � ������ ������� �������!';
				QuoteTable('close');
			}
		}
		else
		{
			if (!isset($take_quest))
			{
				QuoteTable('open');
				echo'<form action="" method="post"><input type="submit" name="take" value="����� ������� �� �������� ������� "'.$npc_exp.'""><input type="hidden" name="take_quest"><input name="town_id" type="hidden" value="'.$town.'">';
				QuoteTable('close');
			}
			else
			{
				QuoteTable('open');
				echo'<font size=2 color=#F0F0F0><div align="justify">����! �� ��������� ��������! ����� � ����� ������� ����! � �� ������ - �� '.echo_sex('������','������').' �������� ��� �������������� '.echo_sex('������','������').'! (����� �� ������ �� ��������)!';
				myquery("INSERT INTO game_quest_users(quest_id,user_id,sost) VALUES ('$quest_id','$user_id','".$npc['npc_id']."')");
				QuoteTable('close');
			}
		}
	}
	else
	{
		$sel_npc_other = myquery("SELECT game_npc.*,game_npc_template.npc_name FROM game_npc,game_npc_template WHERE game_npc.npc_quest_id>0 AND game_npc.npc_quest_end_time>".time()." AND game_npc.npc_id=game_npc_template.npc_id");
		if (mysql_num_rows($sel_npc_other)>0)
		{
			echo'<hr>������, �� ������ � ����� ������� ������� ��� ��� ���� ������. ������� �����!<hr>';
			//if (isset($guild)) $info_cost=200-$guild['guild_lev']*7.6;
			$info_cost=15;
			if (!isset($know_quest_where))
			{
				QuoteTable('open');
				echo'<font size=2 color=#F0F0F0><div align="justify">�� �� ������ ���������� � ����� ����������� � ����� �������� ������� ��� �� ��������� �������. ������ ��� ����� ������ ��� ���� '.$info_cost.' '.pluralForm($info_cost,'������','������','�����').'';
				QuoteTable('close');
				QuoteTable('open');
				echo'<form action="" method="post"><input type="submit" name="take_info" value="��������� �� ����������"><input type="hidden" name="know_quest_where"><input name="town_id" type="hidden" value="'.$town.'">';
				QuoteTable('close');
			}
			else
			{
				if ($char['GP']>=$info_cost)
				{
					QuoteTable('open');
					echo'<font size=2 color=#F0F0F0><div align="justify">������� ��� �������������� �������� ������ ��������������� ������:<br><br><center><b>��� ������ ���� ����������:</b></center><br><br>';
					myquery("UPDATE game_users SET GP=GP-'".$info_cost."',CW=CW-'".($info_cost*money_weight)."' WHERE user_id='".$user_id."'");
					setGP($user_id,-$info_cost,47);
					echo'<table width=100% cellspacing=2 cellpadding=2>';
					echo '<tr><th>�����</th><th>������</th><th>���.�����</th></tr>';
					while ($npc_other = mysql_fetch_array($sel_npc_other))
					{
						$town_id = $npc_other['npc_quest_guild'];
						$end_time = $npc_other['npc_quest_end_time']-time();
						$npc_name = $npc_other['npc_name'];
						$min = floor($end_time/60);
						$sec = $end_time-$min*60;
						list($rustown) = mysql_fetch_array(myquery("SELECT rustown FROM game_gorod WHERE town='$town_id'"));
						$map = mysql_fetch_array(myquery("SELECT * FROM game_map WHERE town='$town_id' AND to_map_name=0"));
						list($map_name) = mysql_fetch_array(myquery("SELECT name FROM game_maps WHERE id='".$map['name']."'"));
						echo '<tr><td>'.$rustown.'</td><td>'.$npc_name.'</td><td>'.$min.' ���. '.$sec.' ���.</td></tr>';
						//echo '<tr><td>���-�� � '.$map_name.'</td><td>'.$npc_name.'</td><td>'.$min.' ���. '.$sec.' ���.</td></tr>';
					}
					echo'</table>';
					QuoteTable('close');
				}
				else
				{
					QuoteTable('open');
					echo'<font size=2 color=#F0F0F0><div align="justify">� ���� ��� '.$info_cost.' ����� ��� ������ ����� �����.';
					QuoteTable('close');
				}
			}
		}
		else
		{
			echo'<hr>������, �� ������ ������ ������� ��������� �� ��������� ������� � ����� � ��������� �� ����� ������� ����� ��������. ������� �����!<hr>';
		}
	}
}

if (function_exists("save_debug")) save_debug(); 

?>