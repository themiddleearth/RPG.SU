<?php
//��������� ����������
if (($hod==0)AND($timeout==0))
{
	if (!checkCraftTrain($user_id,11))
	{
		echo '<br /><br />�� �� ������ ������� ��������� ����������! �� ������ ������� �� � ������ � ������� ���������.<br />���� ��������� ���������� ���� ���������� ����, ��� ��� � 30 �����.<br /><br />';
	}
	else
	{
		//������ ������
		if (isset($_POST['schema_id']))
		{
			$check_item = myquery("SELECT * FROM game_items WHERE item_id=".$id_item_orujeinik." AND user_id=$user_id AND priznak=0 AND used=21 AND item_uselife>0");
			if (mysql_num_rows($check_item)>0)
			{
				//�������� ������� �����
				$check_schema = myquery("SELECT * FROM game_items WHERE user_id=$user_id AND priznak=0 AND used=0 AND id=".$_POST['schema_id']."");
				if (mysql_num_rows($check_schema)>0)
				{
					$item = mysql_fetch_array($check_schema);
					list($item_new_id,$schema_level) = mysql_fetch_array(myquery("SELECT indx,oclevel FROM game_items_factsheet WHERE id=".$item['item_id'].""));
					$make = 1;
					$weight_out = 0;
					
					$sel_res = myquery("SELECT game_items_schema.col,craft_resource_user.col AS user,craft_resource.weight FROM game_items_schema,craft_resource_user,craft_resource WHERE game_items_schema.item_id=".$item['item_id']." AND craft_resource_user.res_id=game_items_schema.res_id AND craft_resource.id=game_items_schema.res_id AND craft_resource_user.user_id=$user_id");
					while ($res = mysql_fetch_array($sel_res))
					{
						$weight_out+=$res['col']*$res['weight'];
						if ($res['col']>$res['user'])
						{
							$make = 0;
						}
					}
					if ($make==1)
					{
						$uslov = 1;
						$dlit = 0;
						$oruj_level = getCraftLevel($user_id,11);
						if ($schema_level==1)
						{
							if ($char['clevel']<8)
							{
								echo '<br /><br />�� �� ������ ������� ������� �� �����. ���� ������� ������ ���������� 8 ������!<br /><br />';
								$uslov = 0;
							}
							$dlit = 120*60;
						}
						if ($schema_level==2)
						{
							if ($char['clevel']<12)
							{
								echo '<br /><br />�� �� ������ ������� ������� �� �����. ���� ������� ������ ���������� 12 ������!<br /><br />';
								$uslov = 0; 
							}
							if ($oruj_level<55)
							{
								echo '<br /><br />�� �� ������ ������� ������� �� �����. ���� ������� "����������" ������ ���������� 55 ������!<br /><br />';
								$uslov = 0; 
							}
							$dlit = 180*60;
						}
						if ($schema_level==3)
						{
							if ($char['clevel']<15)
							{
								echo '<br /><br />�� �� ������ ������� ������� �� �����. ���� ������� ������ ���������� 15 ������!<br /><br />';
								$uslov = 0; 
							}
							if ($oruj_level<85)
							{
								echo '<br /><br />�� �� ������ ������� ������� �� �����. ���� ������� "����������" ������ ���������� 85 ������!<br /><br />';
								$uslov = 0; 
							}
							$dlit = 240*60;
						}
						if ($schema_level==4)
						{
							if ($char['clevel']<19)
							{
								echo '<br /><br />�� �� ������ ������� ������� �� �����. ���� ������� ������ ���������� 19 ������!<br /><br />';
								$uslov = 0; 
							}
							if ($oruj_level<115)
							{
								echo '<br /><br />�� �� ������ ������� ������� �� �����. ���� ������� "����������" ������ ���������� 115 ������!<br /><br />';
								$uslov = 0; 
							}
							$dlit = 300*60;
						}
						if ($schema_level==5)
						{
							if ($char['clevel']<22)
							{
								echo '<br /><br />�� �� ������ ������� ������� �� �����. ���� ������� ������ ���������� 22 ������!<br /><br />';
								$uslov = 0; 
							}
							if ($oruj_level<145)
							{
								echo '<br /><br />�� �� ������ ������� ������� �� �����. ���� ������� "����������" ������ ���������� 145 ������!<br /><br />';
								$uslov = 0; 
							}
							$dlit = 420*60;
						}
						if ($uslov == 1)
						{
							list($weight_in) = mysqlresult(myquery("SELECT weight FROM game_items_factsheet WHERE id=".$item_new_id.""),0,0);
							//�������� ������. ����� � ��� �����
							if ($char['CW']-$weight_out+$weight_in<=$char['CC'])
							{
								if (isset($_POST['digit']) OR isset($_POST['begin']))
								{
									if (isset($_SESSION['captcha']) and isset($_POST['digit']) and $_POST['digit']==$_SESSION['captcha'] and checkCraftTrain($user_id,11))
									{
										unset($_SESSION['captcha']);
										//������ �����
										$Item = new Item($item['id']);
										$Item->admindelete();
										//������ �������
										$weight = 0;
										$sel_res = myquery("SELECT game_items_schema.*,craft_resource_user.col AS user,craft_resource.weight FROM game_items_schema,craft_resource_user,craft_resource WHERE game_items_schema.item_id=".$item['item_id']." AND game_items_schema.res_id=craft_resource_user.res_id AND craft_resource.id=craft_resource_user.res_id AND craft_resource_user.user_id=$user_id");
										while ($res = mysql_fetch_array($sel_res))
										{
											$weight+=$res['weight']*$res['col'];
											if ($res['user']==$res['col'])
											{
												myquery("DELETE FROM craft_resource_user WHERE res_id=".$res['res_id']." AND user_id=$user_id");
											}
											else
											{
												myquery("UPDATE craft_resource_user SET col=GREATEST(0,col-".$res['col'].") WHERE res_id=".$res['res_id']." AND user_id=$user_id");
											}
											myquery("insert into craft_stat (build_id, gp, res_id, dob, vip, dat, user, type) values (0, 0, ".$res['res_id'].", 0, -".$res['col'].", ".time().", $user_id, 'z')"); 
										}
										myquery("UPDATE game_users SET CW=CW-$weight WHERE user_id=$user_id");    
										craft_setFunc($user_id,11);
										set_delay_reason_id($user_id,31);
										if (domain_name == 'testing.rpg.su' or domain_name=='localhost') 
										{
											$dlit=5;
										}
										else
										{
											$dlit = max(600,$dlit-$oruj_level*120);
										}
										$build_id='oruj';
										myquery("DELETE FROM craft_build_rab WHERE user_id=$user_id");
										myquery("INSERT INTO craft_build_rab (user_id,build_id,date_rab,dlit,eliksir,`add`,opt) VALUES ($user_id,'$build_id',".time().",$dlit,$item_new_id,".$Item->fact['quantity'].",".$_GET['add'].")");
										ForceFunc($user_id,func_craft);
										setLocation("../craft.php");
									}
									else
									{
										echo '�� �� ������ ������ ������ � ��������� ���������� (������ ������������ ���)!<br><br><br><br>'; 
									}
								}
								else
								{
									echo '��� ������ ������ ����� ��������� ���� ��� <br>� ����� ������ "������ ������ � ����������"<br>';
									echo '<br><img src="../captcha_new/index.php?'.time().'">';
									$action = $_SERVER["REQUEST_URI"];
									echo '<form autocomplete="off" action="'.$action.'" method="POST" name="captcha"><br>
									<input type="text" size=6 maxsize=6 name="digit"><br>
									<input type="hidden" name="schema_id" value="'.$_POST['schema_id'].'">
									<input type="submit" name="subm" value="������ ������ � ����������">
									</form>';
								}
							}
							else
							{
								echo '<br /><br />� ���� ������������ ���������� ����� � ���������!<br /><br />';
							}
						}
						else
						{
							echo '<br /><br />�� ��������� ���������� ����� �������� ��������!<br /><br />';
						}
					}
					else
					{
						echo '<br /><br />� ���� �� ������� �������� ��� �������� �������� �� �����!<br /><br />';
					}
				}
				else
				{
					echo '<br /><br />� ���� �����-�� ����� ����� ;-)!<br /><br />';
				}
			}
			else
			{
				echo '<br /><br />����� ������� ������ � ��������� ���������� ���������� ����� � ����� ������� "����� ����������"<br /><br />';
			}
			echo '<br /><br /><center><a href="?option='.$_GET['option'].'&part4&add='.$_GET['add'].'">��������� � ��������� ����������</a></center><br /><br />';       
		}
		else
		{
			if (isset($_GET['mes'])) echo ('<b><font color="#C0FFC0">'.$_GET['mes'].'</font></b><br /><br />');
			$sel_item = myquery("SELECT game_items.id,game_items.item_id,game_items_factsheet.name,game_items_factsheet.img,game_items.kleymo FROM game_items,game_items_factsheet WHERE game_items.item_id=game_items_factsheet.id AND game_items.priznak=0 AND game_items.user_id=$user_id AND game_items_factsheet.type=20");
			if (mysql_num_rows($sel_item)>0)
			{
				echo '<table border=2 cellspacing=2 cellpadding=1>';
				while ($it = mysql_fetch_array($sel_item))
				{
					echo '<tr><td valign="middle">';
					ImageItem($it['img'],$it['id'],$it['kleymo'],"middle",$it['name'],$it['name']);
					echo '&nbsp;&nbsp;'.$it['name'].'</td><td valign="middle">';
					$sel_schema = myquery("SELECT craft_resource.*,game_items_schema.col,craft_resource_user.col AS col_user FROM game_items_schema,craft_resource LEFT JOIN (craft_resource_user) ON (craft_resource_user.res_id=craft_resource.id AND craft_resource_user.user_id=$user_id) WHERE game_items_schema.item_id=".$it['item_id']." AND craft_resource.id=game_items_schema.res_id");
					$make = 0;
					$vsego = 0;
					while ($schema = mysql_fetch_array($sel_schema))
					{
						echo '<img src="http://'.img_domain.'/item/resources/'.$schema['img3'].'.gif" align="middle">&nbsp;&nbsp;'.$schema['name'].' = '.$schema['col'].' ��.<br />';
						if ($schema['col_user']>=$schema['col'])
						{
							$make++;
						}
						$vsego++;
					}
					echo '</td><td valign="middle">';
					if ($vsego==$make)
					{
						echo '<form method="POST" action=""><input type="hidden" name="schema_id" value="'.$it['id'].'"><input type="submit" name="subm" value="������� ������� �� �����"></form>';
					}
					else
					{
						echo '�� �� ������ ������� �������!';
					}
					echo '</td></tr>';	
				}
				echo '</table>';
			}
			else
			{
				echo '<br />� ���� ��� �� ����� ����� ������������ ���������!<br />';
			}
		}
	}
}
elseif ($hod-time()+$timeout<=0)
{
	//��������� ������
	list($weight,$item_name) = mysql_fetch_array(myquery("SELECT weight,name FROM game_items_factsheet WHERE id=".$rab['eliksir'].""));
	$prov=mysql_result(myquery("select count(*) from game_wm where user_id=$user_id and type=1"),0,0);
	if (($char['CW']+$weight*$rab['add'])<=$char['CC'] OR $prov>0)
	{
		//������� ���� �� ������
		add_exp_for_craft($user_id, 11);
		$Item = new Item();
		for ($i=1;$i<=$rab['add'];$i++)
		{
			$Item->add_user($rab['eliksir'],$user_id,1);
		}
		setCraftTimes($user_id,11,1,1);
		$mes='�� ������� '.echo_sex('������','�������').' �� ����� ������� <i>'.$item_name.'</i> � ���-��: '.$rab['add'].' ��.';
	}
	else
	{
		$mes='��������� ������� �������� ��������. ��� ���������� ����� � ���������!';
	}
	//�������� ��������� ������ ����������
	myquery("UPDATE game_items SET item_uselife=item_uselife-".(mt_rand(100,300)/100)." WHERE user_id=$user_id AND used=21 AND priznak=0");
	list($nabor_id,$nabor) = mysql_fetch_array(myquery("SELECT id,item_uselife FROM game_items WHERE user_id=$user_id AND used=21 AND priznak=0"));
	if ($nabor<=0)
	{
		$Item = new Item($nabor_id);
		$Item->down();    
	}
	$option = 18;
	if (domain_name=='localhost') $option=19;

	$url = "lib/town.php?option=$option&part4&add=".$rab['opt'].'&mes='.$mes;
	setLocation($url);
	//echo'<meta http-equiv="refresh" content="10;url='.$url.'">';
	exit_from_craft("");
	//echo '<br /><br /><center><a href="'.$url.'">��������� � ��������� ����������</a></center><br /><br />';       
}
else
{
	if (($hod>0) AND ($hod<=time()))
	{
		//��� �������� ������
		$item_name = mysqlresult(myquery("SELECT name FROM game_items_factsheet WHERE id=".$rab['eliksir'].""),0,0);
		echo'�� '.echo_sex('�����','������').' ������� �� �������� ��������  <b><font color=red>'.$item_name.'</font></b><br /><br />'; 

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