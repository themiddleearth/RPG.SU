<?php

//���������� ����������
$teplo = 10;
$nas_coal = 30;
$nas_samorodok = 50;

if (($hod==0)AND($timeout==0))
{
	$sel_founder = myquery("SELECT * FROM craft_build_founder WHERE user_id=$user_id");
	if ($sel_founder==false OR mysql_num_rows($sel_founder)==0)
	{
		$founder = Array();
		$founder['user_id']=$user_id;
		$founder['nas']=0;
		$founder['teplo']=0;
		$founder['res_id']=0;
		$founder['col_coal']=0;
		$founder['col_water']=0;
		$founder['col_res']=0;
		$founder['state']=0;
	}
	else
	{
		$founder = mysql_fetch_array($sel_founder);
	}
	$href = "?option=".$option."&part4&add=".$_GET['add']."";

	if (!isset($_GET['found_act']))
	{
		if (isset($_GET['mes'])) echo ('<b><font color="#C0FFC0">'.$_GET['mes'].'</font></b><br /><br />');
		
		echo '������� ��� ������ � ���������:<br /><br />
		<b>����� ��������� ������ �����:</b><br />
		1) ������������ ����������� ��� ��������� ���������� ����� � ����.<br />
		����� � ���� ������������ � ������� ���������� ���� � ���� � ����.<br />
		1 ����� = +'.$teplo.' �����<br />
		1 ���� = -'.$teplo.' �����<br />
		2) ������������ ������������ ������� �� ����� 51%.<br />
		������������ ������� ������������ ���������� ����� � ���������� �����.<br />
		1 ���� = +'.$nas_coal.'% ������������ �������<br />
		1 ��������� = +'.$nas_samorodok.'% ������������ �������<br /> <br /><br />
		<b>������� ����������� ������� ����� � ���� ��� ���������:</b><br />
		�������� ����: ������� �� 59 �� 70 �����<br />
		������ ����: ������� �� 49 �� 60 �����<br />
		����������� ����: ������� �� 69 �� 80 �����<br />
		����������� ���������: ������� �� 29 �� 40 �����<br />
		������� ���������: ������� �� 39 �� 50 �����<br /><br />
		��� ������ ��������� ����� � ����� ������� "���� ���������"<br /><br /><br />';

		if (!checkCraftTrain($user_id,10))
		{
			echo '<br /><br />�� �� ������ ������� ��������� ���������! �� ������ ������� �� � ������ � ������� ���������.<br />���� ��������� ���������� ���� ���������� ����, ��� ��� � 30 �����.<br /><br />';
		}
		else
		{
			echo '<a href="'.$href.'&found_act=1">�������� �������� ��� ���������� � �����</a><br />';
			echo '<a href="'.$href.'&found_act=2">�������� ����� � ����</a><br />';
			echo '<a href="'.$href.'&found_act=3">�������� ���� �����</a><br />';
			echo '<a href="'.$href.'&found_act=4">�������</a><br />';
		}
	}
	else
	{
		$href.="&found_act=".$_GET['found_act'];
		if ($_GET['found_act']==1)
		{
			if (isset($_GET['add_res_id']))
			{
				$add_res_id = (int)$_GET['add_res_id'];
				$check = myquery("SELECT * FROM craft_resource_user WHERE id=$add_res_id AND user_id=$user_id AND col>0 AND res_id IN (".$id_resource_for_founder.")");
				if (mysql_num_rows($check))
				{
					$res_user = mysql_fetch_array($check);
					$check2 = myquery("SELECT * FROM craft_build_founder WHERE user_id=$user_id");
					$stop = 0;
					if (mysql_num_rows($check2)>0)
					{
						$stop = 1;
						$res_founder = mysql_fetch_array($check2);
						if ($res_founder['res_id']==0 AND $res_founder['col_coal']==0 AND $res_founder['col_water']==0)
						{
							myquery("DELETE FROM craft_build_founder WHERE user_id=$user_id");
							$stop = 0;
						}
						else
						{
							if ($res_founder['res_id']!=$res_user['res_id'] AND $res_founder['res_id']!=0)
							{
								$stop = 2;
							}
						}
					}
					if (($stop==0) OR ($stop==1))
					{
						$res = mysql_fetch_array(myquery("SELECT * FROM craft_resource WHERE id=".$res_user['res_id'].""));
						myquery("UPDATE craft_resource_user SET col=GREATEST(0,col-1) WHERE id=$add_res_id AND user_id=$user_id");
						myquery("DELETE FROM craft_resource_user WHERE id=$add_res_id AND user_id=$user_id and col=0");
						myquery("UPDATE game_users SET CW=CW-".($res['weight'])." WHERE user_id=$user_id");	
						myquery("insert into craft_stat (build_id, gp, res_id, dob, vip, dat, user, type) values (0, 0, $add_res_id, 0, -1, ".time().", $user_id, 'z')");  
						$nas = 0;
						switch ($res_user['res_id'])
						{
							case $id_resource_iron_ore: $nas=$nas_coal; break;
							case $id_resource_copper_ore: $nas=$nas_coal; break;
							case $id_resource_mithril_ore: $nas=$nas_coal; break;
							case $id_resource_silver_nugget: $nas=$nas_samorodok; break;
							case $id_resource_gold_nugget: $nas=$nas_samorodok; break;
						}
						$founder['nas']+=$nas;
						$founder['nas']=min(100,$founder['nas']);
						if ($stop==0)
						{
							myquery("INSERT INTO craft_build_founder (user_id,res_id,col_res,nas) VALUES ($user_id,".$res_user['res_id'].",1,$nas)");
						}
						else
						{
							myquery("UPDATE craft_build_founder SET col_res=col_res+1,nas=LEAST(100,nas+$nas),res_id=".$res_user['res_id']." WHERE user_id=$user_id");
						}
						echo '<br />� ��������� ��������� 1 ��. ������� <b><font color=red>'.$res['name'].'</font></b>';
					}
					else
					{
						echo '<br />� ��������� ������ ��������� ������������ ������ ���� ����/����������<br /><br />';
					}
				}
				else
				{
					echo '<br />���� ������ ������ �������� � ���������!<br /><br />';
				}
			}
			$sel_res_founder = myquery("SELECT craft_resource.img1,craft_resource.img2,craft_resource.img3,craft_resource.name,craft_resource_user.* FROM craft_resource_user,craft_resource WHERE craft_resource_user.user_id=$user_id AND craft_resource_user.col>0 AND craft_resource_user.res_id IN (".$id_resource_for_founder.") AND craft_resource.id=craft_resource_user.res_id");
			if (mysql_num_rows($sel_res_founder)==0)
			{
				echo '<br /><br />� ���� ��� ��������, ������� ����� ���� ������������!<br /><br />';
			}                   
			else                                                                                                                                   
			{
				echo '<table>';
				while ($res = mysql_fetch_array($sel_res_founder))
				{
					echo '<tr><td><a href="'.$href.'&add_res_id='.$res['id'].'"><img border="0" src="http://'.img_domain.'/item/resources/'.$res['img3'].'.gif"></a></td><td><a href="'.$href.'&add_res_id='.$res['id'].'">�������� � ���������</a> 1 ��. �� '.$res['col'].' ��. ������� <b><font color=red>'.$res['name'].'</font></b></td><tr>';					
				}
				echo '</table>';
			}  
		}
		elseif ($_GET['found_act']==2)
		{
			if (isset($_GET['add_res_id']))
			{
				$add_res_id = (int)$_GET['add_res_id'];
				$check = myquery("SELECT * FROM craft_resource_user WHERE id=$add_res_id AND user_id=$user_id AND col>0 AND res_id IN (".$id_resource_coal.")");
				if (mysql_num_rows($check))
				{
					$res_user = mysql_fetch_array($check);
					$res = mysql_fetch_array(myquery("SELECT * FROM craft_resource WHERE id=".$res_user['res_id'].""));
					myquery("UPDATE craft_resource_user SET col=GREATEST(0,col-1) WHERE id=$add_res_id AND user_id=$user_id");
					myquery("DELETE FROM craft_resource_user WHERE id=$add_res_id AND user_id=$user_id and col=0");
					myquery("UPDATE game_users SET CW=CW-".($res['weight'])." WHERE user_id=$user_id");    
					$founder['teplo']+=$teplo;
					myquery("INSERT INTO craft_build_founder (user_id,teplo,col_coal) VALUES ($user_id,GREATEST(0,$teplo),1) ON DUPLICATE KEY UPDATE teplo=teplo+GREATEST(0,$teplo),col_coal=col_coal+1");
					myquery("insert into craft_stat (build_id, gp, res_id, dob, vip, dat, user, type) values (0, 0, $add_res_id, 0, -1, ".time().", $user_id, 'z')");       
					echo '<br />� ���� ��������� '.($founder['col_coal']+1).' ��. ������� <b><font color=red>'.$res['name'].'</font></b>';
				}
				else
				{
					echo '<br />� ���� ��� ������� "�����"!<br /><br />';
				}
			}
			$sel_res_founder = myquery("SELECT craft_resource.img1,craft_resource.img2,craft_resource.img3,craft_resource.name,craft_resource_user.* FROM craft_resource_user,craft_resource WHERE craft_resource_user.user_id=$user_id AND craft_resource_user.col>0 AND craft_resource_user.res_id IN (".$id_resource_coal.") AND craft_resource.id=craft_resource_user.res_id");
			if (mysql_num_rows($sel_res_founder)==0)
			{
				echo '<br /><br />� ���� ��� ������� "�����"!<br /><br />';
			}                   
			else                                                                                                                                   
			{
				echo '<table>';
				while ($res = mysql_fetch_array($sel_res_founder))
				{
					echo '<tr><td><a href="'.$href.'&add_res_id='.$res['id'].'"><img border="0" src="http://'.img_domain.'/item/resources/'.$res['img3'].'.gif"></a></td><td><a href="'.$href.'&add_res_id='.$res['id'].'">�������� ����� � ����</a> 1 ��. �� '.$res['col'].' ��. ������� <b><font color=red>'.$res['name'].'</font></b></td><tr>';                    
				}
				echo '</table>';
			}  
		}
		elseif ($_GET['found_act']==3)
		{
			if (isset($_GET['add_res_id']))
			{
				$add_res_id = (int)$_GET['add_res_id'];
				$check = myquery("SELECT * FROM craft_resource_user WHERE id=$add_res_id AND user_id=$user_id AND col>0 AND res_id IN (".$id_resource_water.")");
				if (mysql_num_rows($check))
				{
					$res_user = mysql_fetch_array($check);
					$res = mysql_fetch_array(myquery("SELECT * FROM craft_resource WHERE id=".$res_user['res_id'].""));
					myquery("UPDATE craft_resource_user SET col=GREATEST(0,col-1) WHERE id=$add_res_id AND user_id=$user_id");
					myquery("DELETE FROM craft_resource_user WHERE id=$add_res_id AND user_id=$user_id and col=0");
					myquery("UPDATE game_users SET CW=CW-".($res['weight'])." WHERE user_id=$user_id");    
					$colwater = 1;
					if ($founder['teplo']<$teplo)
					{
						$colwater = 0;
						$founder['teplo'] = 0;
					}
					else
					{
						$founder['teplo']=$founder['teplo']-$teplo;
					}
					myquery("INSERT INTO craft_build_founder (user_id,teplo,col_water) VALUES ($user_id,GREATEST(0,-$teplo),$colwater) ON DUPLICATE KEY UPDATE teplo=GREATEST(0,teplo-$teplo),col_water=col_water+$colwater");
					myquery("insert into craft_stat (build_id, gp, res_id, dob, vip, dat, user, type) values (0, 0, $add_res_id, 0, -1, ".time().", $user_id, 'z')");       
					echo '<br />���� �������� 1 ��. ������� <b><font color=red>'.$res['name'].'</font></b>';
				}
				else
				{
					echo '<br />� ���� ��� ������� "����"!<br /><br />';
				}
			}
			$sel_res_founder = myquery("SELECT craft_resource.img1,craft_resource.img2,craft_resource.img3,craft_resource.name,craft_resource_user.* FROM craft_resource_user,craft_resource WHERE craft_resource_user.user_id=$user_id AND craft_resource_user.col>0 AND craft_resource_user.res_id IN (".$id_resource_water.") AND craft_resource.id=craft_resource_user.res_id");
			if (mysql_num_rows($sel_res_founder)==0)
			{
				echo '<br /><br />� ���� ��� ������� "����"!<br /><br />';
			}                   
			else                                                                                                                                   
			{
				echo '<table>';
				while ($res = mysql_fetch_array($sel_res_founder))
				{
					echo '<tr><td><a href="'.$href.'&add_res_id='.$res['id'].'"><img border="0" src="http://'.img_domain.'/item/resources/'.$res['img3'].'.gif"></a></td><td><a href="'.$href.'&add_res_id='.$res['id'].'">�������� ����</a> 1 ��. �� '.$res['col'].' ��. ������� <b><font color=red>'.$res['name'].'</font></b></td><tr>';                    
				}
				echo '</table>';
			}  
		}
		elseif ($_GET['found_act']==4)
		{
			if ($founder['res_id']==0)
			{
				echo '<br /><br />� ��� �� '.echo_sex('��������','���������').' ������������ � ���������?<br /><br />';
			}
			else
			{
				$check_item = myquery("SELECT * FROM game_items WHERE item_id=".$id_item_founder." AND user_id=$user_id AND priznak=0 AND used=21 AND item_uselife>0");
				if (mysql_num_rows($check_item)>0)
				{
					//�������� ������. ����� � ��� �����
					if (isset($_POST['digit']) OR isset($_POST['begin']))
					{
						if (isset($_SESSION['captcha']) and isset($_POST['digit']) and $_POST['digit']==$_SESSION['captcha'] and checkCraftTrain($user_id,10))
						{
							unset($_SESSION['captcha']);							
							$fault = 1;
							switch ($founder['res_id'])
							{
								case $id_resource_iron_ore:
								{
									if ($founder['nas']>=51 AND $founder['teplo']>=59 AND $founder['teplo']<70)
									{
										$fault = 0;
									}
								}
								break;
								case $id_resource_copper_ore:
								{
									if ($founder['nas']>=51 AND $founder['teplo']>=49 AND $founder['teplo']<60)
									{
										$fault = 0;
									}
								}
								break;
								case $id_resource_silver_nugget:
								{
									if ($founder['nas']>=51 AND $founder['teplo']>=29 AND $founder['teplo']<40)
									{
										$fault = 0;
									}
								}
								break;
								case $id_resource_mithril_ore:
								{
									if ($founder['nas']>=51 AND $founder['teplo']>=69 AND $founder['teplo']<80)
									{
										$fault = 0;
									}
								}
								break;
								case $id_resource_gold_nugget:
								{
									if ($founder['nas']>=51 AND $founder['teplo']>=39 AND $founder['teplo']<50)
									{
										$fault = 0;
									}
								}
								break;
							}
							$add_query = "";
							if ($fault==0)
							{
								if (domain_name == 'testing.rpg.su' or domain_name=='localhost') 
								{
									$dlit=5;
								}
								else
								{
									$dlit = max(120,600-getCraftLevel($user_id,10)*20);
								}
								craft_setFunc($user_id,10);
								set_delay_reason_id($user_id,34);
								$build_id='founder';
								myquery("DELETE FROM craft_build_rab WHERE user_id=$user_id");
								myquery("INSERT INTO craft_build_rab (user_id,build_id,date_rab,dlit) VALUES ($user_id,'$build_id',".time().",$dlit)");
								ForceFunc($user_id,func_craft);
								setLocation("../craft.php");
							}
							else
							{
								$mes='�������, �� ����������� '.echo_sex('�������������','��������������').' ����� ���� ��� ������������ �������, ��� ��������� ��������� �������.';
								myquery("UPDATE craft_build_founder SET state=0,nas=0,teplo=0,res_id=0,col_res=0,col_coal=0,col_water=0 WHERE user_id=$user_id"); 
								if (domain_name=='localhost') $option=19;
								else $option = 18;								
								$url = 'town.php?option='.$option.'&part4&add=18&mes='.$mes;
								setLocation($url);
								exit_from_craft($add_query,1);
							}
						}
						else
						{
							echo '�� �� ������ ������ ������ �� ���������, ��� ��� ����� �������� ���!<br><br><br><br>'; 
						}
					}
					else
					{
						echo '��� ������ ������ ����� ��������� ���� ��� <br>� ����� ������ "������ ������ �� ���������"<br>';
						echo '<br><img src="../captcha_new/index.php?'.time().'">';
						$action = $href;
						echo '<form autocomplete="off" action="'.$action.'" method="POST" name="captcha"><br>
						<input type="text" size=6 maxsize=6 name="digit"><br>
						<input type="submit" name="subm" value="������ ������ �� ���������">
						</form>';
					}
				}
				else
				{
					echo '<br /><br />����� ������� ������ �� ��������� ���������� ����� � ����� ������� "���� ���������"<br /><br />';
				}
			}
		} 
		echo '<br /><br /><center><a href="?option='.$_GET['option'].'&part4&add='.$_GET['add'].'">��������� � ���������</a></center><br /><br />';       
	}
	if ($founder['res_id']>0)
	{		
		echo '<br /><br />� ���������� ����� ��������� '.$founder['col_res'].' ��. ������� <b><font color=red>'.mysqlresult(myquery("SELECT name FROM craft_resource WHERE id=".$founder['res_id'].""),0,0).'</font></b>';
	}
	echo '<br /><br />������������ ������� � ���������� �����: '.$founder['nas'].', ���������� ����� � ���� '.$founder['teplo'].'.<br /><br />';
} 
elseif ($hod-time()+$timeout<=0)
{
	//��������� ������	
	$founder = mysql_fetch_array(myquery("SELECT * FROM craft_build_founder WHERE user_id=$user_id"));	
	$add_query = "";
	//������� ���� �� ������
	add_exp_for_craft($user_id, 10);
	if ($founder['state']==2)
	{
		//��������� ������� ������
		$res_id_bullion = 0;
		switch ($founder['res_id'])
		{
			case $id_resource_iron_ore:
			{
				$res_id_bullion = $id_resource_iron_bullion;
			}
			break;
			case $id_resource_copper_ore:
			{
				$res_id_bullion = $id_resource_copper_bullion;
			}
			break;
			case $id_resource_silver_nugget:
			{
				$res_id_bullion = $id_resource_silver_bullion;
			}
			break;
			case $id_resource_mithril_ore:
			{
				$res_id_bullion = $id_resource_mithril_bullion;
			}
			break;
			case $id_resource_gold_nugget:
			{
				$res_id_bullion = $id_resource_gold_bullion;
			}
			break;
		}
		$res_in = mysql_fetch_array(myquery("SELECT * FROM craft_resource WHERE id='".$res_id_bullion."' "));
		$Res= new Res($res_in);
		$check=$Res->add_user(0,$user_id);
		if ($check == 1)
		{			
			myquery("UPDATE game_items SET item_uselife=item_uselife-".(mt_rand(400,600)/100)." WHERE user_id=$user_id AND used=21 AND priznak=0");
			list($id_item,$cur_uselife) = mysql_fetch_array(myquery("SELECT id,item_uselife FROM game_items WHERE priznak=0 AND user_id=$user_id AND used=21"));
			if ($cur_uselife<=0)
			{
				$Item = new Item($id_item);
				$Item->down();
			} 
			$mes='������� ������: <i>'.$res_in['name'].'</i> � ���������� 1 ��.';
			setCraftTimes($user_id,10,3,1);
			myquery("insert into craft_stat (build_id, gp, res_id, dob, vip, dat, user, type) values (0, 0, $res_id_bullion, 0, 1, ".time().", $user_id, 'z')");
		}
		else
		{
			$mes='��������� ������� ������ �� ���������. �������, ������� �� � ���� ����� ��� ����� �������� � ���������!';
		}
		myquery("DELETE FROM craft_build_founder WHERE user_id=$user_id");
	}
	else
	{
		mt_srand(make_seed());
		$new_nas = mt_rand(1,$founder['nas']-10);
		mt_srand(make_seed());
		$new_teplo = mt_rand(1,100);
		$new_state = $founder['state']+1;
		$mes='��������� '.$new_state.' �� 3 ������ ���������.';
		myquery("UPDATE craft_build_founder SET nas=$new_nas,teplo=$new_teplo,state=state+1 WHERE user_id=$user_id");
	}	
	//������� ��������
	$option = 18;
	if (domain_name=='localhost') $option=19;
	$url = 'lib/town.php?option='.$option.'&part4&add=18&mes='.$mes;
	setLocation($url);
	exit_from_craft($add_query,1);
}
else
{
	if (($hod>0) AND ($hod<=time()))
	{
		//��� �������� ������
		$founder = mysql_fetch_array(myquery("SELECT craft_build_founder.*,craft_resource.name FROM craft_build_founder,craft_resource WHERE craft_build_founder.user_id=$user_id AND craft_resource.id=craft_build_founder.res_id"));  
		echo'�� '.echo_sex('�����','������').' ������� � ���������� ����<br /><br />�� ������������� ������ '.$founder['name'].'<br /><br />������ ��������� - '.($founder['state']+1).' �� 3<br /><br />'; 

		echo '<br>�� ����� ������ ��������: <font color=ff0000><b><span id="timerr1">'.($hod+$timeout-time()).'</span></b></font> ������</div> 
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
	} 
}
?>
