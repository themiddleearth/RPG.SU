<?

if (function_exists("start_debug")) start_debug(); 

if ($town!=0)
{
	if (isset($_POST['town_id']) AND $_POST['town_id'] != $town)
	{
		echo'�� ���������� � ������ ������!<br><br><br>&nbsp;&nbsp;&nbsp;<input type="button" value="����� � �����" onClick=location.href="town.php">&nbsp;&nbsp;&nbsp;';
	}
	else
	{
		echo'<link href="../suggest/suggest.css" rel="stylesheet" type="text/css">';
		echo'<center><font face=verdana size=2>';
		$userban=myquery("select * from game_ban where user_id='$user_id' and type=2 and time>'".time()."'");
		if (mysql_num_rows($userban))
		{
			$userr = mysql_fetch_array($userban);
			$min = ceil(($userr['time']-time())/60);
			echo '<center><br><br><br>�� ���� �������� ��������� �� '.$min.' �����! ���� ��������� ������������ �������� �������!';
		}
	echo'<img src="http://'.img_domain.'/gorod/bank/sklad.jpg">';
	
		if (!isset($_GET['do'])) $do=''; else $do= $_GET['do'];

		if ($do=='view')
		{
			echo'<SCRIPT language=javascript src="../js/info.js"></SCRIPT><DIV id=hint  style="Z-INDEX: 0; LEFT: 0px; VISIBILITY: hidden; POSITION: absolute; TOP: 0px"></DIV>';
			if ($char['name']=='blazevic' OR $char['name']=='The_Elf')
			{
				$pg=myquery("SELECT id FROM game_items where priznak=3 and town='$town' and post_to>0 AND post_var<=1 AND sell_time>".(time()-4*24*60*60)." ORDER BY sell_time");
			}
			else
			{
				$pg=myquery("SELECT id FROM game_items where priznak=3 and town='$town' and post_to=$user_id AND post_var<=1 AND sell_time>".(time()-4*24*60*60)." ORDER BY sell_time");
			}
			while ($items=mysql_fetch_array($pg))
			{
				echo'<table border="0" cellpadding="1"><tr><td></td></tr></table>
				<table border="1" cellpadding="0" style="border-collapse: collapse" width="98%" bordercolor="777777" bgcolor="223344"><table border="1" cellpadding="0" style="border-collapse: collapse" width="98%" bordercolor="777777" bgcolor="223344" align=center><tr><td>';
				echo '<table cellpadding="0" cellspacing="4" border="0"><tr><td width=100 valign="left"><div align="center">';
				$Item = new Item($items['id']);
				$Item->hint(0,1,'<span ');
				ImageItem($Item->fact['img'],0,$Item->item['kleymo']);
				echo '</span>';

				$left_time = $Item->getItem('sell_time')+4*24*60*60;
				$left_time = date('d.m.Y : H:i:s', $left_time);

				$selname = myquery("SELECT name FROM game_users WHERE user_id=".$Item->getItem('user_id')."");
				if (!mysql_num_rows($selname)) $selname = myquery("SELECT name FROM game_users_archive WHERE user_id=".$Item->getItem('user_id').""); 
				list($name_from) = mysql_fetch_array($selname); 
				$selname = myquery("SELECT name FROM game_users WHERE user_id=".$Item->getItem('post_to')."");
				if (!mysql_num_rows($selname)) $selname = myquery("SELECT name FROM game_users_archive WHERE user_id=".$Item->getItem('post_to').""); 
				list($name_to) = mysql_fetch_array($selname); 
				
				echo'<br><font color="#ffff00">' . $Item->getFact('name') . '</font></div><center>';
				echo'�������� ��:<br><b>'.$left_time.'</b>';
				echo'</center></td><td width=150 valign="top"><div align="left"><img src="http://'.img_domain.'/nav/x.gif" width="0" height="0" hspace="40" border="0"><br>
				�����������: <font size=1 face=verdana color=ff0000><b>'.$name_from.'</b></font><br>
				����������: <font size=1 face=verdana color=ff0000><b>'.$name_to.'</b></font><br><br>';
				
				$Item->info(0,0,1);
	 
				echo '<br><br><input type="button" value="������� �������" onClick=location.replace("town.php?option='.$option.'&town_id='.$town.'&do=buy&it='.$items['id'].'")>';
				echo'</div></td><td valign=top>��������:<br>'.$Item->getOpis().'';

				if ($char['name'] == 'The_Elf' OR $char['name'] == 'blazevic')
					echo'<br><br><br><input type="button" value="������� ���������" onClick=location.replace("town.php?option='.$option.'&town_id='.$town.'&do=del&it='.$items['id'].'")>';
				echo'</td></tr></table></td></tr></table>';
			}
		}


		elseif ($do=='del')
		{
			$sel=myquery("select user_id from game_items where priznak=3 and id='$it'");
			list($userid)=mysql_fetch_array($sel);

			if ($char['name']=='The_Elf' or $char['name']=='blazevic' or $char['user_id']==$userid)
			{
				$Item = new Item($items['id']);
				$Item->del_market(0,$userid);
				$town_select = myquery("select rustown from game_gorod where town='$town'");
				list($rustown)=mysql_fetch_array($town_select);
				$ma=myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES ('$userid', '0', '�������� ������', '���� ������� ".$Item->getFact('name')." ���� �� ������ �������� ������ � ������ ".$rustown."! �� ��������� � ���� ���������','0','".time()."')");
				echo'<br/>���� ���������� ���������<br/><br/><meta http-equiv="refresh" content="1;url=town.php?option='.$option.'">';
			}
		}

		elseif ($do=='buy')
		{
			$it=(int)$it;
			$Item = new Item($it);
			if ($Item->getItem('post_to')==$user_id)
			{
				$ar = $Item->buy_market(0,1);
				if ($ar[0]>0)
				{ 
					$town_select = myquery("select rustown from game_gorod where town='$town'");
					list($rustown)=mysql_fetch_array($town_select);
					$userid=$Item->getItem('user_id');
					
					$selname = myquery("SELECT name FROM game_users WHERE user_id=$userid");
					if (!mysql_num_rows($selname)) $selname = myquery("SELECT name FROM game_users_archive WHERE user_id=$userid"); 
					list($name) = mysql_fetch_array($selname); 
					
					$ma=myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES ('$userid', '0', '�������� ������', '���� ������� ".$ar[1]." ������������ �� ����� �������� ������ � ".$rustown." ������� ����������� ".$char['name'].". ����������� � ������� - ".$ar[2]."','0','".time()."')");
					$ma=myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES ('$user_id', '0', '�������� ������', '�� ".echo_sex('�������','��������')." � ".echo_sex('������','������')." ������� ������� ".$ar[1]." �� ������ �������� ������ � ".$rustown." ����������� ������� ".$name.". ����������� � ������� - ".$ar[2]."','0','".time()."')");
					echo'<br/>������� ������<br/><br/>';
				}
			}
			else
			{
				echo '<br/>������� ������������� �� ����!<br/><br/>';
			}
			echo'<meta http-equiv="refresh" content="1;url=town.php?option='.$option.'">';
		}

		elseif ($do=='new_item')
		{
			if($char['GP']>'0')
			{
				if($char['clevel']>='4')
				{
					$selec=myquery("select game_items.id,game_items_factsheet.img,game_items_factsheet.name,game_items.kleymo from game_items,game_items_factsheet where game_items.user_id='$user_id' and game_items.ref_id='0' and game_items_factsheet.type not IN (12,13,19,21,22,97,98,99) and game_items.used=0 and game_items.priznak=0 and game_items.item_id=game_items_factsheet.id and game_items.personal=0 Order By Binary game_items_factsheet.name");
					while ($row=mysql_fetch_array($selec))
					{
						echo'<table border="0" cellpadding="1"><tr><td></td></tr></table><table border="1" cellpadding="0" style="border-collapse: collapse" width="98%" bordercolor="777777" bgcolor="223344"><tr><td width=70 align=center><a href=town.php?option='.$option.'&do=confirm&it='.$row["id"].'>';
						ImageItem($row['img'],0,$row['kleymo']);
						echo '</a></td><td>'.$row["name"].'</td></tr></table>';
					}
					echo'<br/>����� �� ������� ����� ��������� ��� ����� �������� ������<br/><br/>';
				}
				else
				{
					echo '<br/>������������ �������� �������� ������ ��������� ������ ����� 4-�� ������<br/><br/>';
				}
			}
			else
			{
				echo '<br/>������������ �������� �������� ������ ��������� ��� ������� ����� � ��������. � ���� �� ����� ���!<br/><br/>';
			}
		}


		elseif ($do=='confirm')
		{
			if($char['clevel']>=4 AND $char['GP']>0)
			{
				if ($char['map_name']==5)
				{
					$bel_cost = 5;
					$sz_cost = 50;
					$magic_sz_cost = 150;    
					$magic_bel_cost = 15;   
				}
				else
				{
					$bel_cost = 50;
					$sz_cost = 5; 
					$magic_sz_cost = 15;   
					$magic_bel_cost = 150;   
				}
				if (!isset($_POST['see']))
				{
					echo'<div id="content" onclick="hideSuggestions();"><form action="" method="post">';
					$it=(int)$_GET['it'];
					$Item = new Item($it);
					if ($Item->getItem('user_id')==$user_id and ($Item->getItem('ref_id')==0 OR $Item->getFact('type')==12 OR $Item->getFact('type')==13 OR $Item->getFact('type')==14) and $Item->getItem('used')==0 and $Item->getFact('type')<90 and $Item->getItem('item_for_quest')==0 and $Item->getItem('priznak')==0 and $Item->getItem('personal')==0)
					{
						
						echo '<table border="0" cellpadding="1"><tr><td></td></tr></table><table border="1" cellpadding="0" style="border-collapse: collapse" width="98%" bordercolor="777777" bgcolor="223344" align=center><tr><td>';
						echo '<table cellpadding="0" cellspacing="4" border="0"><tr><td valign="left"><div align="center">';
						ImageItem($Item->fact['img'],0,$Item->item['kleymo']);
						echo '<br><font color="#ffff00">' . $Item->getFact('name') . '</font></div></td><td valign="top"><div align="left"><img src="http://'.img_domain.'/nav/x.gif" width="0" height="0" hspace="40" border="0"><br>';
						$Item->info(0,0,1);
						echo '�������� ������ ��������:<br>';
						echo '<input type="radio" name="sposob" value="1">������� ����� - ';
						echo '�������� ����� ����������
						<select name="destination">
						<option value="5">��������� ('.$bel_cost.' �����)</option>
						<option value="18">���������� ('.$sz_cost.' �����)</option>
						</select>';
						echo '<br><input type="radio" name="sposob" value="2">���������� ����� - ';
						echo '�������� ����� ����������
						<select name="destination_magic">
						<option value="5">��������� ('.$magic_bel_cost.' �����)</option>
						<option value="18">���������� ('.$magic_sz_cost.' �����)</option>
						</select>';
						echo '<br>���� <input name="name" type="text" id="keyword" size="30" maxsize="50" onkeyup="handleKeyUp(event)"><div style="display:none;" id="scroll"><div id="suggest"></div></div>';
						echo '<br>��������:<br><textarea name="opis" cols="45" rows="3"></textarea>
						<br>

						<input name="" type="submit" value="��������� �������">';
						echo'</div></td></tr></table>';
						echo'</td></tr><input name="see" type="hidden" value=""></table>';
					}
					echo '</form></div><script>init();</script>';
				}
				elseif ((isset($_POST['sposob'])) and (isset($_POST['name'])) and (isset($_POST['destination'])))
				{
					$gp = 0;
					$post_var = 2;
					$no_post = 0;
					if ($_POST['sposob']=="1")
					{
						if ($_POST['destination']==5) $gp=$bel_cost; 
						if ($_POST['destination']==18) $gp=$sz_cost; 
					}
					elseif ($_POST['sposob']=="2")
					{
						if ($_POST['destination_magic']==5) $gp=$magic_bel_cost; 
						if ($_POST['destination_magic']==18) $gp=$magic_sz_cost; 
						$post_var = 1;
					}
					$post = myquery("SELECT user_id FROM game_users WHERE name='".$_POST['name']."'");
					if (!mysql_num_rows($post)) $post = myquery("SELECT user_id FROM game_users_archive WHERE name='".$_POST['name']."'");
					if (!mysql_num_rows($post))
					{
						echo'<br/>������ ������ �� ����������<br/><br/><meta http-equiv="refresh" content="1;url=town.php?option='.$option.'">';
					}
					else
					{
						list($post_to) = mysql_fetch_array($post);
						if ($post_var == 1)
						{
							list($map_to) = mysql_fetch_array(myquery("SELECT map_name FROM game_users_map WHERE user_id=$post_to"));
							if ($map_to!=$_POST['destination_magic'])
							{
								echo'<br/>������ ��� �� ��������� ����� ����� ��������<br/><br/><meta http-equiv="refresh" content="2;url=town.php?option='.$option.'">';
								$no_post=1;
							}
						}
						if ($_POST['name']!=$char['name'])
						{
							if ($no_post==0)
							{
								if ($char['map_name']==$_POST['destination'])
								{
									$town_dest = $town;
								}
								else
								{
									list($id_option_gorod) = mysql_fetch_array(myquery("SELECT id FROM game_gorod_option WHERE link='post_items.inc.php'"));
									$sel_gorod = myquery("SELECT gorod_id FROM game_gorod_set_option WHERE option_id=$id_option_gorod");
									while (list($gor) = mysql_fetch_array($sel_gorod))
									{
										$map_new = myquery("SELECT * FROM game_map WHERE town=$gor AND name=".$_POST['destination']." AND to_map_name=0");
										if (mysql_num_rows($map_new))
										{
											$town_dest = $gor;
										}
									}
								}
								$Item = new Item($it);
								$Item->sell_market($town_dest,0,$gp,$post_to,$post_var);
								$Item->setOpis($_POST['opis']);
								echo'<meta http-equiv="refresh" content="3;url=town.php?option='.$option.'">';
							}
						}
						else
							echo'<br/>������ ���� ������ ��������� �������? ��-��.<meta http-equiv="refresh" content="1;url=town.php?option='.$option.'"><br/><br/>';
					}
				}
			}
		}
		else
		{
			QuoteTable('open');
			echo '<center><b>������� ������������� �������� ������ ����������:</b></center><br>
			1. ��������� �����: <br/>�) � �������� ����� �����: <br/>������� �������� - 5 �����, ���������� �������� - 15 �����; <br/>�) ��������� ����� �������: <br/>������� �������� - 50 �����, ���������� �������� - 150 �����.<br/>
			2. ���� �������� ����� �� ������� �������� ������ - 4 ������� ������ (4 ���). �� ��������� ����� ������� ���������!<br>
			3. ���� ������� �������� ������� - �� 1 ���� ����� ��������.<br>
			4. ���� ���������� �������� ������� - �� 3 ����� ����� ��������. (���������� ������� ������������ ��������������� � ��������� ����������)<br>
			5. ���� � ������ ������������ ����� � ��������� - ���� ������� ����� ��������� � �������� ������ �� ��� �����, �� ������� ���������� �������.
			';
			QuoteTable('close');
			echo '<a href="town.php?option='.$option.'&do=new_item">��������� �������</a> | ';
			echo '<a href="town.php?option='.$option.'&do=view">�������� �������</a>';
		}
	}
}

if (function_exists("save_debug")) save_debug(); 

?>