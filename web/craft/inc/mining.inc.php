<?php
//�������
function add_resource($id_resource)
{
    global $user_id,$char,$_SESSION;
	if ($id_resource > 0)	
	{
		$res = mysql_fetch_array(myquery("SELECT * FROM craft_resource WHERE id=$id_resource"));
		$Res= new Res($res);
		$check=$Res->add_user(0,$user_id);
		if ($check == 1)
		{
			setCraftTimes($user_id,6,1,1);
			echo '�� �����: '.$res['name'];
			myquery("insert into craft_stat (build_id, gp, res_id, dob, vip, dat, user, type) values (0, 0, $id_resource, 0, 1, ".time().", $user_id, 'z')");
			if (isset($_SESSION['cur_get_mining']))
			{
				$dob = explode('###',$_SESSION['cur_get_mining']);
				$find=0;
				for ($i=0;$i<sizeof($dob);$i++)
				{
					$cur_dob = explode("&&&",$dob[$i]);
					if ($cur_dob[0]==$res['name'])
					{
						$find=1;
						$col=(int)$cur_dob[1]+1;
						$dob[$i]=$res['name'].'&&&'.$col; 
					}
				}
				if ($find==0)
				{
					$dob[]=$res['name'].'&&&1';
				}
				$_SESSION['cur_get_mining'] = implode("###",$dob);
			}
			else
			{
				$str = $res['name'].'&&&1';
				$_SESSION['cur_get_mining'] = $str;
			}
		}
		else
		{
			echo '� ��� ������������ ���������� ����� � ���������!';
		}
	}
}

if ($local_func_id==6)
{
	$odet_kirka = 0;
	$geksa = (int)(fmod($rab['eliksir'],10)); 
	$level = floor($rab['eliksir']/10);   
	$sel = myquery("SELECT id FROM game_items WHERE item_id=$id_item_kirka AND used=21 AND user_id=$user_id AND priznak=0 AND item_uselife>0");
	if ($sel!=false AND mysql_num_rows($sel)>0)
	{
		$odet_kirka = 1;
	}
	if (!checkCraftTrain($user_id,6))
	{
		echo '<br /><br />�� �� ������ ������� ��������� ��������! �� ������ ������� �� � ������ � ������� ���������.<br />���� ��������� ���������� ���� ���������� ����, ��� ��� � 30 �����.<br /><br />';
		exit_from_craft();
	}
	elseif ($odet_kirka==0 AND $geksa!=0)
	{
		if (isset($_GET['home']))
		{
			myquery("UPDATE craft_build_rab SET eliksir=".($level*10)." WHERE user_id=$user_id");
			echo '<br />�� '.echo_sex('�����','�����').' � ����������� �����';
			echo '<meta http-equiv="refresh" content="2;url=craft.php">';
			exit;
		}
		else
		{
			echo '<br /><br />��� ������ � ������� ���� ����� � ���� ������� �����<br /><br />';
			echo '<br /><br /><a href="?home"> [ ����� � ����������� ����� ] </a>';  
		}
	}
	else
	{
		$sel = myquery("SELECT * FROM craft_build_mining WHERE user_id=$user_id");
		if ($sel==false OR mysql_num_rows($sel)==0)
		{
			//��������� � �������, �� ������� �� ������
			//��� ����������� ������ � ������ ����� �� ������� ��������� ����� ���������� ���� eliksir � ������� craft_build_rab (eliksir �� ���� ����: 1 - ����� ������, 2 - ����� �����)
			echo '<span style="font-weight:900;font-size:13px;font-family:Arial;color:#FFFF00">������ '.$level.' ������. ����� �'.$geksa.'</span><br /><ul>';
			$cur_geksa = mysql_fetch_array(myquery("SELECT * FROM craft_build_mining WHERE build_id=$build_id AND geksa=$geksa AND level=$level"));
			if ($geksa == 0)
			{
				//��������� � ������ ������ �����. ���������� ������� �� ������ ������ � �������� �� 8 ���� ��� ������
				if ($level>0)
				{
					if (isset($_GET['id']))
					{
						$id_geks = (int)$_GET['id'];
						$check = myquery("SELECT geksa FROM craft_build_mining WHERE build_id=$build_id AND id=$id_geks AND geksa_state=0 AND user_id=0");
						if ($check!=false AND mysql_num_rows($check)>0)
						{
							list($g) = mysql_fetch_array($check);
							$new_geksa = $level*10+$g;
							myquery("UPDATE craft_build_rab SET eliksir=$new_geksa WHERE user_id=$user_id");
							echo'�� '.echo_sex('�������','�������').' � ����� �'.$g;
						}
						echo'<meta http-equiv="refresh" content="2;url=craft.php">';
						exit; 
					}
					elseif (!isset($_GET['up']) AND !isset($_GET['down']) AND !isset($_GET['make_hod']))
					{
						$sel_geksa = myquery("SELECT * FROM craft_build_mining WHERE build_id=$build_id AND level=$level AND geksa>0");
						while ($geks = mysql_fetch_array($sel_geksa))
						{
							echo '<div style="padding-left:30%;text-align:left;">';
							echo '<li>����� �'.$geks['geksa'].' - ';
							if ($geks['geksa_state']==1)
							{
								echo '����� ��������';
							}
							elseif ($geks['user_id']>0)
							{
								list($n) = mysql_fetch_array(myquery("SELECT name FROM game_users WHERE user_id=".$geks['user_id'].""));
								echo '[������ ������� - '.$n.']';
							}
							else
							{
								$obval = max(0,($level-1)*5+$geks['geksa_obval']-min(5,$char['lucky'])); 
								echo '<a href="?id='.$geks['id'].'">[ ������� � ����� ]</a>';
								echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;���� ������ - '.$obval.'%';
							} 
							echo '</li>'; 
							echo '</div><br />'; 
						}
						echo '<br /><br />';
					}
				}
				echo '</ul>';
				
				//hod � ������� �� - ��� ����������� ������� �� ���� ������� � �������� ������
				if ($level<6)
				{
					//������ ����
					list($hod_state) = mysql_fetch_array(myquery("SELECT hod FROM craft_build_mining WHERE build_id=$build_id AND geksa=0 AND level=".($level+1).""));
					if ($hod_state<100)
					{
						if (isset($_GET['make_hod']) AND $cur_geksa['user_id']==0 AND $broken_instrument==0)
						{
							if (!isset($_POST['digit']))
							{
								echo '��� ������ ������ ����� ��������� ���� ��� <br>� ����� ������ "������ ������"<br>';
								echo '<br><img src="captcha_new/index.php?'.time().'">';
								echo '<form autocomplete="off" action="craft.php?make_hod" method="POST" name="captcha"><br>
								<input id="input_digit" type="text" size=6 maxsize=6 name="digit"><br /><br />
								<input type="submit" name="subm" value="������ ������">
								</form><br />
								<script>
								el = document.getElementById(\'input_digit\');
								el.focus();
								</script>';
							}
							else
							{
								if (isset($_SESSION['captcha']) AND $_POST['digit']==$_SESSION['captcha'])
								{
									unset($_SESSION['captcha']);
									$char_mining = getCraftLevel($user_id,6);
									if (domain_name == 'testing.rpg.su' or domain_name=='localhost') 
									{
										$end_time = 20;
									}
									else
									{
										$end_time = time()+max(120,5*60-10*$char_mining);
									}
									//$end_time = time()+max(120,5*60-10*$char_mining);
									myquery("UPDATE craft_build_mining SET end_time=$end_time,user_id=$user_id WHERE id=".$cur_geksa['id']."");
									echo'�� '.echo_sex('�����','������').' ������ � �����.';       
								}
								else
								{
									echo '������ ������������ ���.';
								}
								setLocation("craft.php"); 
							} 
						}
						elseif (!isset($_GET['exit']) AND !isset($_GET['up']) AND !isset($_GET['down']))
						{
							echo '<br />�� ��������� ������� ������� �������� ��� �� '.$hod_state.'%<br />';
							if ($cur_geksa['user_id']!=0)
							{
								echo '������� ����� ������.';
							}
							elseif ($broken_instrument==0)
							{
								echo '<a href="?make_hod">[ ������ ��� ������ ]</a>';
							}
						}
					}
					else
					{
						if (isset($_GET['down']))
						{
							myquery("UPDATE craft_build_rab SET eliksir=eliksir+10 WHERE user_id=$user_id");
							echo'<br />�� '.echo_sex('���������','����������').' �� ������ ������� �������<meta http-equiv="refresh" content="2;url=craft.php">';
						}
						else
						{
							echo '<a href="?down">[ ���������� �� ������ ������� ]</a>';
						}
					}
				}
				if ($level>0)
				{
					//������ �����
					$hod_state = $cur_geksa['hod'];
					if ($hod_state==100)
					{
						if (isset($_GET['up']))
						{
							myquery("UPDATE craft_build_rab SET eliksir=eliksir-10 WHERE user_id=$user_id");
							echo'<br />�� '.echo_sex('��������','���������').' �� ������� ������� �������<meta http-equiv="refresh" content="2;url=craft.php">';
						}
						elseif (!isset($_GET['make_hod']))
						{
							echo '<a href="?up">[ ��������� �� ������� ������� ]</a>';
						}
					}
					else
					{
						echo '<br />������ �� ������� ������� �������';
					}
				}
				else
				{
					if (isset($_GET['exit']))
					{
						echo'<br />�� '.echo_sex('�����','�����').' �� ������� �� ������ �������� ������<meta http-equiv="refresh" content="4;url=act.php?func=main">';
						exit_from_craft();
					}
					elseif (!isset($_GET['down']))
					{
						//����� �� �����
						echo '<a href="?exit">[ ����� �� ����� ]</a>';
					}
				}
			}
			else
			{
				//� ����� �� ���� �� ������
				if ($cur_geksa['user_id']==0 AND $cur_geksa['geksa_state']==0)
				{
					$id_geks = $cur_geksa['id'];
					if (isset($_GET['dob']) AND $broken_instrument==0)
					{
						$char_mining = getCraftLevel($user_id,6);
						if ($char_mining>=($level-1)*3)
						{
							if (!isset($_POST['digit']))
							{
								echo '��� ������ ������ ����� ��������� ���� ��� <br>� ����� ������ "������ ������"<br>';
								echo '<br><img src="captcha_new/index.php?'.time().'">';
								echo '<form autocomplete="off" action="craft.php?dob" method="POST" name="captcha"><br>
								<input id="input_digit" type="text" size=6 maxsize=6 name="digit"><br /><br />
								<input type="submit" name="subm" value="������ ������">
								</form><br />
								<script>
								el = document.getElementById(\'input_digit\');
								el.focus();
								</script>';
							}
							else
							{
								if (isset($_SESSION['captcha']) AND $_POST['digit']==$_SESSION['captcha'] and checkCraftTrain($user_id,6))
								{
									$char_mining = getCraftLevel($user_id,6);
									if (domain_name == 'testing.rpg.su' or domain_name=='localhost') 
									{
										$end_time = 5;
									}
									else
									{
										$end_time = time()+max(120,5*60-10*$char_mining);
									}							
									myquery("UPDATE craft_build_mining SET end_time=$end_time,user_id=$user_id WHERE id=$id_geks");
									echo'�� '.echo_sex('�����','������').' ������ � �����.';
									$obval = max(0,($level-1)*5+$cur_geksa['geksa_obval']-min(5,$char['lucky']));
									mt_srand(make_seed());
									$rand = mt_rand(1,100);
									if ($rand<=$obval)
									{
										echo'�� '.echo_sex('�����','������').' ��� ����� �����. ���� ������ � ������� �� ���� ��� ���������<meta http-equiv="refresh" content="4;url=act.php?func=main">';
										myquery("UPDATE craft_build_mining SET geksa_state=1 WHERE id=$id_geks");
										$kol_obvalov = mysqlresult(myquery("SELECT COUNT(*) FROM craft_build_mining WHERE build_id=$build_id AND level=$level AND geksa_state=1"),0,0);
										//��� ����������� ������ � ������ ����� �� ������� ��������� ����� ���������� ���� eliksir � ������� craft_build_rab (eliksir �� ���� ����: 1 - ����� ������, 2 - ����� �����)
										//$msg = mysql_real_escape_string("����� �����. build=id=$build_id,level=$level,kol_obalov=$kol_obvalov");
										//myquery("INSERT DELAYED INTO game_pm (komu, otkogo, theme, post, view, time) VALUES ('612', '".$user_id."', '����� �����. ��������', '$msg','0','".time()."')");

										//���������� ���� ������� � ���� �����
										$number_shahta = (int)("".$cur_geksa['level'].$cur_geksa['geksa']);
										$sel_usid = myquery("SELECT user_id FROM craft_build_rab WHERE build_id=$build_id AND eliksir=$number_shahta AND user_id<>$user_id");
										while (list($usid) = mysql_fetch_array($sel_usid))
										{
											//$msg = mysql_real_escape_string("����� �����. build=id=$build_id,level=$level,kol_obalov=$kol_obvalov,number_shahta=$number_shahta");
											//myquery("INSERT DELAYED INTO game_pm (komu, otkogo, theme, post, view, time) VALUES ('612', '".$usid."', '����� �����. ������ ������', '$msg','0','".time()."')");
											exit_from_craft("",1,$usid);
										}

										if ($kol_obvalov>7)
										{
											//$msg = mysql_real_escape_string("����� ������. build=id=$build_id,level=$level,kol_obalov=$kol_obvalov");
											//myquery("INSERT DELAYED INTO game_pm (komu, otkogo, theme, post, view, time) VALUES ('612', '".$user_id."', '����� ������', '$msg','0','".time()."')");
											//������� ���� �������!
											myquery("UPDATE craft_build_mining SET hod=0,geksa_obval=0,user_id=0,end_time=0 WHERE build_id=$build_id AND level=$level");
											//���������� ���� ������� �� ���� ������ �����
											$number_shahta_min = (int)("".$cur_geksa['level']."0");
											$number_shahta_max = (int)("".$cur_geksa['level']."8");
											$sel_usid = myquery("SELECT user_id FROM craft_build_rab WHERE build_id=$build_id AND eliksir>=$number_shahta_min AND eliksir<=$number_shahta_max AND user_id<>$user_id");
											while (list($usid) = mysql_fetch_array($sel_usid))
											{
												//$msg = mysql_real_escape_string("����� ������. build=id=$build_id,level=$level,kol_obalov=$kol_obvalov,number_shahta_min=$number_shahta_min,number_shahta_max=$number_shahta_max");
												//myquery("INSERT DELAYED INTO game_pm (komu, otkogo, theme, post, view, time) VALUES ('612', '".$usid."', '����� ������. ������', '$msg','0','".time()."')");
												exit_from_craft("",1,$usid);
											}
										}
										exit_from_craft("",1,$char['user_id'],1);
									}
									else
									{
									}
								}
								else
								{
									echo '<br /><br />������ ������������ ���.';
								}
								unset($_SESSION['captcha']);
								unset($_POST['digit']);
								setLocation("craft.php"); 
							} 
/*
*/
						}   
					}
					elseif (isset($_GET['klin']))
					{
						$check = myquery("SELECT * FROM craft_resource_user WHERE user_id=$user_id AND res_id=$id_resource_brevno AND col>0 LIMIT 1");
						if ($check!=false AND mysql_num_rows($check)>0)
						{
							myquery("UPDATE craft_build_mining SET geksa_obval=geksa_obval-2 WHERE id=$id_geks");
							$res_user = mysql_fetch_array($check);
							if ($res_user['col']==1)
							{
								myquery("DELETE FROM craft_resource_user WHERE id=".$res_user['id']."");
							}
							else
							{
								myquery("UPDATE craft_resource_user SET col=GREATEST(0,col-1) WHERE id=".$res_user['id']."");    
							}
							myquery("insert into craft_stat (build_id, gp, res_id, dob, vip, dat, user, type) values (0, 0, ".$res_user['id'].", 0, -1, ".time().", $user_id, 'z')"); 
							$res = mysql_fetch_array(myquery("SELECT weight FROM craft_resource WHERE id=$id_resource_brevno"));
							myquery("UPDATE game_users SET CW=CW-".$res['weight']." WHERE user_id=$user_id");
							echo'�� '.echo_sex('�������','��������').' ����� �����';
						}
						echo '<meta http-equiv="refresh" content="2;url=craft.php">';
						exit;
					}
					elseif (isset($_GET['home']))
					{
						myquery("UPDATE craft_build_rab SET eliksir=".($level*10)." WHERE user_id=$user_id");
						echo '<br />�� '.echo_sex('�����','�����').' � ����������� �����';
						echo '<meta http-equiv="refresh" content="2;url=craft.php">';
						exit;
					}
					else
					{
						$obval = max(0,($level-1)*5+$cur_geksa['geksa_obval']-min(5,$char['lucky'])); 
						echo '����������� ������ ����� - '.$obval.'%<br />';
						$char_mining = getCraftLevel($user_id,6);
						if ($char_mining>=($level-1)*3 AND $broken_instrument==0)
						{
							echo '<a href="?dob">������ ������ ����</a>';
						}
						else
						{
							if ($char_mining<($level-1)*3)
							{
								echo '<br /><br />��� ������ � ������ ��������� '.(($level-1)*3).' ������� ������ �������� (� ���� ������ '.$char_mining.' �������)';
							}
							else
							{
								echo '<br /><br />��� ������ � ������� ��������� �����';
							}
						}

						if ($cur_geksa['geksa_obval']>0)
						{
							$check = myquery("SELECT * FROM craft_resource_user WHERE user_id=$user_id AND res_id=$id_resource_brevno AND col>0 LIMIT 1");
							if ($check!=false AND mysql_num_rows($check)>0)
							{
								echo '&nbsp;&nbsp;&nbsp;<a href="?klin">[ �������� ����� ����� ������� ]</a>';
							}
						}
						
						echo '<br /><br /><a href="?home"> [ ����� � ����������� ����� ] </a>';
					}
				}
				else
				{
					if (isset($_GET['home']))
					{
						myquery("UPDATE craft_build_rab SET eliksir=".($level*10)." WHERE user_id=$user_id");
						echo '<br />�� '.echo_sex('�����','�����').' � ����������� �����';
						echo '<meta http-equiv="refresh" content="2;url=craft.php">';
						exit;
					}
					else
					{
						if ($cur_geksa['geksa_state']==1)
						{
							echo '<br /><br />����� ��������';
						}
						else
						{
							echo '<br /><br />����� ������';
						}
						echo '<br /><br /><a href="?home"> [ ����� � ����������� ����� ] </a>'; 
					}
				}
			}
		}
		else
		{
			$mine = mysql_fetch_array($sel);
			if ($mine['end_time']>time())
			{
				//������ ������
				if ($mine['geksa'] == 0 )
				{
					echo'�� '.echo_sex('�����','������').' ������������� ���� �� ������ ������� �������<br />';
				}
				else
				{
					echo'�� '.echo_sex('�����','������').' ������� ���� � ����� �'.$mine['geksa'].''; 
				}
				echo '<br>�� ����� ������ ��������: <font color=ff0000><b><span id="timerr1">'.($mine['end_time']-time()).'</span></b></font> ������</div> 
				<script language="JavaScript" type="text/javascript">
				function tim()
				{
					timer = document.getElementById("timerr1");
					if (timer.innerHTML<=0)
						location.reload();
					else
					{
						timer.innerHTML=timer.innerHTML-1;
						window.setTimeout("tim()",1000);
						if (timer.innerHTML%120==0)
						{
							location.reload();
						}
					}
				}
				tim();
				</script>';
				echo'<br /><br /><br /><br /><a href="?craft&act=no">���������� ������</a>'; 
			}
			else
			{
				//������ �������
				if ($mine['geksa']==0 AND $mine['level']<6)
				{
					//����� ������ ������
					//������� ���� �� ������
					add_exp_for_craft($user_id, 6);
					echo '������ �������, ���� ������� ��������� ��� �� ����.������� ������� ��� �� 10%';
					myquery("UPDATE craft_build_mining SET hod=LEAST(hod+10,100) WHERE geksa=0 AND level=".($mine['level']+1)." AND build_id=$build_id");
					myquery("UPDATE craft_build_mining SET end_time=0,user_id=0 WHERE id=".($mine['id'])."");
					$hodlevel = mysqlresult(myquery("SELECT hod FROM craft_build_mining WHERE geksa=0 AND level=".($mine['level']+1)." AND build_id=$build_id"),0,0);
					if ($hodlevel==100)
					{
						myquery("UPDATE craft_build_mining SET geksa_state=0 WHERE level=".($mine['level']+1)." AND build_id=$build_id");
					}
				}
				elseif ($mine['geksa']>0)
				{
					//������� ���� � �����
					$r_neud=100;
					$r_ugol=0;
					$r_jelezo=0;
					$r_med=0;
					$r_mithril=0;
					$r_silver=0;
					$r_gold=0;
					$r_saphir=0;
					$r_izumrud=0;
					$r_rubin=0;
					$r_almaz=0;
					switch($mine['level'])
					{
						case 1:
						{
							$r_neud=50;
							$r_ugol=35;
							$r_jelezo=9;
							$r_med=5;
							$r_mithril=0;
							$r_silver=1;
							$r_gold=0;
							$r_saphir=0;
							$r_izumrud=0;
							$r_rubin=0;
							$r_almaz=0;
						}
						break;
						
						case 2:
						{
							$r_neud=40;
							$r_ugol=31;
							$r_jelezo=12;
							$r_med=9;
							$r_mithril=5;
							$r_silver=2;
							$r_gold=1;
							$r_saphir=0;
							$r_izumrud=0;
							$r_rubin=0;
							$r_almaz=0;
						}
						break;
						
						case 3:
						{
							$r_neud=30;
							$r_ugol=22;
							$r_jelezo=18;
							$r_med=14;
							$r_mithril=10;
							$r_silver=3;
							$r_gold=2;
							$r_saphir=1;
							$r_izumrud=0;
							$r_rubin=0;
							$r_almaz=0;
						}
						break;
						
						case 4:
						{
							$r_neud=20;
							$r_ugol=24;
							$r_jelezo=18;
							$r_med=17;
							$r_mithril=13;
							$r_silver=4;
							$r_gold=3;
							$r_saphir=0;
							$r_izumrud=1;
							$r_rubin=0;
							$r_almaz=0;
						}
						break;
						
						case 5:
						{
							$r_neud=10;
							$r_ugol=26;
							$r_jelezo=20;
							$r_med=19;
							$r_mithril=15;
							$r_silver=5;
							$r_gold=4;
							$r_saphir=0;
							$r_izumrud=0;
							$r_rubin=1;
							$r_almaz=0;
						}
						break;
						
						case 6:
						{
							$r_neud=0;
							$r_ugol=28;
							$r_jelezo=20;
							$r_med=20;
							$r_mithril=20;
							$r_silver=6;
							$r_gold=5;
							$r_saphir=0;
							$r_izumrud=0;
							$r_rubin=0;
							$r_almaz=1;
						}
						break;						
					}					

					//�������� ��������� �����
					myquery("UPDATE game_items SET item_uselife=item_uselife-2 WHERE user_id=$user_id AND priznak=0 AND used=21");
					list($cur_item_uselife,$id_item_kirka) = mysql_fetch_array(myquery("SELECT item_uselife,id FROM game_items WHERE user_id=$user_id AND priznak=0 AND used=21"));
					if ($cur_item_uselife<=0)
					{
						//����� �������
						//$Item = new Item($id_item_kirka);
						//$Item->down();
						echo '� ���� ��������� �����! �� �� ������� ������ ��������!';                    
					}
					mt_srand(make_seed());
					$r = mt_rand(0,100);
					if ($r<=$r_neud)
					{
						//��������� �������
						echo '�� �� '.echo_sex('����','������').' ������ ������';
					}
					else
					{
						//������� ���� �� ������
						add_exp_for_craft($user_id, 6);
						$id_resource = 0;
						if ($r<=($r_neud+$r_ugol))
						{
							//������ ����
							$id_resource = $id_resource_coal;
						}
						elseif ($r<=($r_neud+$r_ugol+$r_jelezo))
						{
							//������ �������� ����
							$id_resource = $id_resource_iron_ore;
						}
						elseif ($r<=($r_neud+$r_ugol+$r_jelezo+$r_med))
						{
							//������ ������ ����
							$id_resource = $id_resource_copper_ore;
						}
						elseif ($r<=($r_neud+$r_ugol+$r_jelezo+$r_med+$r_mithril))
						{
							//������ �������
							$id_resource = $id_resource_mithril_ore;
						}
						elseif ($r<=($r_neud+$r_ugol+$r_jelezo+$r_med+$r_mithril+$r_silver))
						{
							//������ �������
							$id_resource = $id_resource_silver_nugget;
						}
						elseif ($r<=($r_neud+$r_ugol+$r_jelezo+$r_med+$r_mithril+$r_silver+$r_gold))
						{
							//������ ������
							$id_resource = $id_resource_gold_nugget;
						}
						elseif ($r<=($r_neud+$r_ugol+$r_jelezo+$r_med+$r_mithril+$r_silver+$r_gold+$r_saphir))
						{
							//������ �������
							$id_resource = id_resource_saphire;
						}
						elseif ($r<=($r_neud+$r_ugol+$r_jelezo+$r_med+$r_mithril+$r_silver+$r_gold+$r_saphir+$r_izumrud))
						{
							//������ ��������
							$id_resource = $id_resource_izumrud;
						}
						elseif ($r<=($r_neud+$r_ugol+$r_jelezo+$r_med+$r_mithril+$r_silver+$r_gold+$r_saphir+$r_izumrud+$r_rubin))
						{
							//������ ������
							$id_resource = $id_resource_rubin;
						}
						elseif ($r<=($r_neud+$r_ugol+$r_jelezo+$r_med+$r_mithril+$r_silver+$r_gold+$r_saphir+$r_izumrud+$r_rubin+$r_almaz))
						{
							//������ ������
							$id_resource = $id_resource_almaz;
						}
						add_resource($id_resource);
					}
					myquery("UPDATE craft_build_mining SET end_time=0,user_id=0,geksa_obval=geksa_obval+2 WHERE id=".$mine['id']."");
				}
				echo '<meta http-equiv="refresh" content="4;url=craft.php">'; 
			}
		}
		
		if (isset($_SESSION['cur_get_mining']))
		{
			echo '<br /><br /><br /><br /><br />������ � ������� ������ ������ � �������<br /><br /><table cellspacing=3 celpadding=3>';
			$dob = explode('###',$_SESSION['cur_get_mining']);
			for ($i=0;$i<sizeof($dob);$i++)
			{
				$cur_dob = explode("&&&",$dob[$i]);
				echo '<tr><td>'.$cur_dob[0].'</td><td>'.$cur_dob[1].' ��</td></tr>';
			}
			echo '</table>';
		}
		if ($broken_instrument==1)
		{
			echo '<br /><br /><br /><br />� ���� ��� � ����� �����, ��� ��� ��������� ���������. �� �� ������� �������� � �������!';
		}
	}
}  
?>
