<?php
//��������
function obval_chance($tree)
{
	global $char;
	$rand = 0;
	$lucky = min(5,$char['lucky']);
	switch ($tree['nomer'])
	{
		case 1: $rand = max(0,0+$tree['chance']-5*$tree['klin']-$lucky); break;
		case 2: $rand = max(5,5+$tree['chance']-5*$tree['klin']-$lucky); break;
		case 3: $rand = max(10,10+$tree['chance']-5*$tree['klin']-$lucky); break;
		case 4: $rand = max(15,15+$tree['chance']-5*$tree['klin']-$lucky); break;
		case 5: $rand = max(20,20+$tree['chance']-5*$tree['klin']-$lucky); break;
	} 
	return $rand; 
}

function check_obval($tree)
{
	global $char;	
	$rand = obval_chance($tree);
	mt_srand(make_seed());
	$ch = mt_rand(0,100);
	if ($ch<=$rand)
	{
		//�����
		craft_DelFunc($char['user_id']);
		myquery("DELETE FROM craft_build_rab WHERE user_id=".$char['user_id']."");		
		myquery("UPDATE craft_build_lumberjack SET klin=0,state=0,user_id=0,end_time=0,chance=0,reserve_time=0,reserve_user_id=0 WHERE id=".$tree['id']."");
		set_craft_delay($char['user_id'], 1);
		echo '<b>��������� �����! ������ ����������!</b><br><br>';
		return 1;
	}
	else
	{
		myquery("UPDATE craft_build_lumberjack SET chance=chance+5 WHERE id=".$tree['id'].""); 
	}
	return 0;
}         

function break_axe(&$action1)
{
	global $user_id;
	myquery("UPDATE game_items SET item_uselife=item_uselife-2 WHERE priznak=0 AND user_id=$user_id AND used=21");
	list($id_item,$cur_uselife) = mysql_fetch_array(myquery("SELECT id,item_uselife FROM game_items WHERE priznak=0 AND user_id=$user_id AND used=21"));
	if ($cur_uselife<=0)
	{
		$Item = new Item($id_item);
		$Item->down();
		craft_DelFunc($user_id);
		$action1 = '� ���� ������ �����!';
	} 
}                      

if ($local_func_id==4)
{
	$sel = myquery("SELECT * FROM craft_build_rab WHERE user_id=$user_id");
	$est_items = mysqlresult(myquery("SELECT COUNT(*) FROM game_items WHERE item_id=".$id_item_topor." AND user_id=$user_id AND used=21 AND priznak=0"),0,0);
	//��������� ����� ������
	$char_lumberjack = getCraftLevel($user_id,4);
	if (domain_name == 'testing.rpg.su' or domain_name=='localhost') 
	{
		$end_time = time()+5;
	}
	else
	{
		$end_time = time()+max(120,5*60-$char_lumberjack*10);
	}
	if ($sel!=false and mysql_num_rows($sel)>0 and $broken_instrument!=1 and $est_items==1)
	{
		$sel_lumber = myquery("SELECT * FROM craft_build_lumberjack WHERE user_id=$user_id AND build_id=$build_id LIMIT 1");
		if ($sel_lumber==false OR mysql_num_rows($sel_lumber)==0)
		{
			//����� ��� �� ����� �����
			echo '<span style="font-weight:900;font-size:13px;font-family:Arial;color:#FFFF00">���������</span><br />';
			$ustroil = 0;
			
			if (isset($_GET['id']) AND $broken_instrument==0)
			{
				$id = (int)$_GET['id'];
				$check = myquery("SELECT * FROM craft_build_lumberjack WHERE id=".$id." AND user_id=0 AND build_id=$build_id");
				if ($check!=false AND mysql_num_rows($check)>0)
				{
					$lumber = mysql_fetch_array($check);
					$obval=0;
					if ($lumber['state']>=0 and $lumber['state']<=80)
					{
						$obval=check_obval($lumber);						
					}
					if ($obval==0)
					{					
						myquery("UPDATE craft_build_lumberjack SET user_id=$user_id,end_time=$end_time,reserve_user_id=0,reserve_time=0 WHERE id=$id");
						myquery("UPDATE craft_build_lumberjack SET reserve_user_id=0,reserve_time=0 WHERE reserve_user_id=$user_id");
						$ustroil = 1;
					}
					echo '<meta http-equiv="refresh" content="3;url=craft.php">'; 
				}
			}
			if (isset($_GET['brevn']) AND isset($_GET['tree']))
			{
				$br = (int)$_GET['brevn'];
				$tr = (int)$_GET['tree'];
				$check = @myquery("SELECT * FROM craft_build_lumberjack WHERE state=200 AND build_id=$build_id AND brevn".$br.">0 AND (user_id=0 OR user_id=$user_id) AND id=$tr");
				if ($check!=false AND mysql_num_rows($check)>0)
				{
					$res = mysql_fetch_array(myquery("SELECT * FROM craft_resource WHERE id=$id_resource_brevno"));
					$Res= new Res($res);
					$check=$Res->add_user(0,$user_id);
					if ($check == 1)
					{			
						myquery("UPDATE craft_build_lumberjack SET brevn".$br."=0 WHERE id=$tr");
						myquery("INSERT INTO craft_stat (build_id, gp, res_id, dob, vip, dat, user, type) VALUES (0, 0, $id_resource_brevno, 0, 1, ".time().", $user_id, 'z')");
					}
					else
					{
						echo '� ��� ������������ ����� � ���������!';
					}
					$check_null = @myquery("SELECT * FROM craft_build_lumberjack WHERE state=200 AND build_id=$build_id AND brevn1=0 AND brevn2=0 AND brevn3=0 AND brevn4=0 AND brevn5=0 AND brevn6=0 AND (user_id=0 OR user_id=$user_id) AND id=$tr");  
					if ($check_null!=false AND mysql_num_rows($check_null)>0)
					{
						myquery("UPDATE craft_build_lumberjack SET state=0,user_id=0,end_time=0,klin=0,chance=0 WHERE id=$tr");
					}  
				}
			}
			if (isset($_GET['klin']))
			{
				$check = myquery("SELECT id FROM game_items WHERE priznak=0 AND used=0 AND user_id=$user_id AND item_uselife>0 AND item_id=$id_item_klin LIMIT 1");
				if ($check!=false AND mysql_num_rows($check)>0)
				{
					$tree_id = (int)$_GET['klin'];
					list($item_id) = mysql_fetch_array($check);
					$KLIN = new Item($item_id);
					$KLIN->admindelete();
					myquery("UPDATE craft_build_lumberjack SET klin=klin+1 WHERE id=$tree_id");
				}
			}
			if (!checkCraftTrain($user_id,4))
			{
				echo '<br /><br />�� �� ������ ������� ��������� ��������! �� ������ ������� �� � ������ � ������� ���������.<br />���� ��������� ���������� ���� ���������� ����, ��� ��� � 30 �����.<br /><br />';
			}
			else
			{
				$sel_tree = myquery("SELECT * FROM craft_build_lumberjack WHERE build_id=$build_id");
				$ind = 0;
				while ($tree=mysql_fetch_array($sel_tree))
				{
					$ind++;
					echo '<div style="padding-left:20%;text-align:left;">'.$ind;
					$type_tree = '';
					switch ($tree['nomer'])
					{
						case 1: $type_tree = '����� ������ � ��������� ��������� '. obval_chance($tree).'%'; break;
						case 2: $type_tree = '������� ������ � ��������� ��������� '. obval_chance($tree).'%'; break;
						case 3: $type_tree = '������� ������ � ��������� ��������� '. obval_chance($tree).'%'; break;
						case 4: $type_tree = '�������� ������ � ��������� ��������� '. obval_chance($tree).'%'; break;
						case 5: $type_tree = '���������� ������ � ��������� ��������� '. obval_chance($tree).'%'; break;
					}  
					echo '.&nbsp;&nbsp;'.$type_tree.' - ';
					if ($tree['state']<100)
					{
						echo '. ������ �������� �� '.$tree['state'].'%';
					}
					elseif ($tree['state']<200)
					{
						echo '. ���������� ������ ���������� �� '.($tree['state']-100).'%';
					}
					else
					{
						if ($tree['reserve_user_id']==0 OR $tree['reserve_user_id']==$user_id)
						{
							$kol = $tree['nomer']+1;
							for ($i=1;$i<=$kol;$i++)
							{
								if ($tree['brevn'.$i]>0)
								{
									echo '<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;������������ ������ - <a href="?tree='.$tree['id'].'&brevn='.$i.'">[ ����� ������ ]</a>';
								}
							}
						}
					}
					echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
					if ($tree['user_id']>0 AND $user_id!=$tree['user_id'])
					{
						list($n) = mysql_fetch_array(myquery("SELECT name FROM game_users WHERE user_id=".$tree['user_id'].""));
						echo '[������ ������� - '.$n.']';
					}
					elseif ($tree['reserve_user_id']>0 AND $user_id!=$tree['reserve_user_id'])
					{
						list($n) = mysql_fetch_array(myquery("SELECT name FROM game_users WHERE user_id=".$tree['reserve_user_id'].""));
						echo '[������ ������� - '.$n.']';
					}
					elseif ($ustroil==0 AND $tree['state']<200)
					{
						if ($broken_instrument==0 AND ($tree['reserve_time']<time() OR ($tree['reserve_time']>=time() AND $tree['reserve_user_id']==$user_id)))
						{
							
							echo '<a href="?id='.$tree['id'].'">[ ';
							if ($tree['reserve_user_id']==$user_id)
							{
								echo '���������� ������';
							}
							else
							{
								echo '������ �����';
							}
							echo ' ]</a>';
						}
						if ($tree['klin']>0)
						{
							echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;������� ������ - '.$tree['klin'];
						}
						$check = myquery("SELECT * FROM game_items WHERE priznak=0 AND used=0 AND user_id=$user_id AND item_id=$id_item_klin AND item_uselife>0");
						if ($check!=false AND mysql_num_rows($check)>0 AND $tree['state']<100)
						{
							echo '&nbsp;&nbsp;&nbsp;<a href="?klin='.$tree['id'].'">[ ������� ���� ]</a>';
						}
					}
					echo '</div>'; 
				}
				echo '<meta http-equiv="refresh" content="60">';
				$check = myquery("SELECT id FROM craft_build_lumberjack WHERE reserve_user_id=$user_id AND user_id=0 AND reserve_time>0 AND reserve_time<".time()."");
				if (mysql_num_rows($check)>0)
				{
					myquery("UPDATE craft_build_lumberjack SET user_id=$user_id,end_time=$end_time,reserve_user_id=0,reserve_time=0 WHERE reserve_user_id=$user_id AND user_id=0");
					setLocation("craft.php");    
				}
			}   
		}    
		else
		{                                  
			$lumber = mysql_fetch_array($sel_lumber);
			if ($lumber['end_time']>time())
			{
				//��� �������� ������  
				if ($lumber['state']<100)
				{                                             
					echo'�� '.echo_sex('�����','������').' ������ ������. (���������� - '.$lumber['state'].'%)'; 
				}
				else
				{
					echo'�� '.echo_sex('�����','������').' ���������� ����������� ������. (���������� - '.($lumber['state']-100).'%)'; 
				}      
		
				echo '<br>�� ����� ������ ��������: <font color=ff0000><b><span id="timerr1">'.($lumber['end_time']-time()).'</span></b></font> ������</div> 
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
			else
			{
				//������� ���� �� ������
				add_exp_for_craft($user_id, 4);
				 
				//������ �������
				$action1 = '';
				if($lumber['state']==0)
				{
					$action1 = '�� '.echo_sex('��������','���������').' ����� ������ �� 20%';
					$action2 = '���������� ����� ������';  
					myquery("UPDATE craft_build_lumberjack SET state=20,end_time=0,user_id=0,reserve_user_id=$user_id,reserve_time=".(time()+60)." WHERE id=".$lumber['id']."");  
					break_axe($action1);
				}
				if($lumber['state']==20)
				{
					$action1 = '�� '.echo_sex('��������','���������').' ����� ������ �� 40%';
					$action2 = '���������� ����� ������';                            
					myquery("UPDATE craft_build_lumberjack SET state=40,end_time=0,user_id=0,reserve_user_id=$user_id,reserve_time=".(time()+60)." WHERE id=".$lumber['id']."");                          
					break_axe($action1);
				}
				if($lumber['state']==40)
				{
					$action1 = '�� '.echo_sex('��������','���������').' ����� ������ �� 60%';
					$action2 = '���������� ����� ������';                            
					myquery("UPDATE craft_build_lumberjack SET state=60,end_time=0,user_id=0,reserve_user_id=$user_id,reserve_time=".(time()+60)." WHERE id=".$lumber['id']."");                          
					break_axe($action1);
				}
				if($lumber['state']==60)
				{
					$action1 = '�� '.echo_sex('��������','���������').' ����� ������ �� 80%';
					$action2 = '���������� ����� ������';                            
					myquery("UPDATE craft_build_lumberjack SET state=80,end_time=0,user_id=0,reserve_user_id=$user_id,reserve_time=".(time()+60)." WHERE id=".$lumber['id']."");                          
					break_axe($action1);
				}
				if($lumber['state']==80)
				{
					$action1 = '�� '.echo_sex('��������','���������').' ����� ������ �� 100%';
					$action2 = '������ ����� ����������� ������ �� ������';                            
					myquery("UPDATE craft_build_lumberjack SET state=100,end_time=0,user_id=0,reserve_user_id=$user_id,reserve_time=".(time()+60).",chance=0 WHERE id=".$lumber['id'].""); 
					setCraftTimes($user_id,4,1,1);
					break_axe($action1);           
				}
				if($lumber['state']==100)
				{
					$action1 = '�� '.echo_sex('��������','���������').' ����� ����������� ������ �� ������ �� 20%';
					$action2 = '���������� ����� ����������� ������';                            
					myquery("UPDATE craft_build_lumberjack SET state=120,end_time=0,user_id=0,reserve_user_id=$user_id,reserve_time=".(time()+60)." WHERE id=".$lumber['id']."");                          
					break_axe($action1);
				}
				if($lumber['state']==120)
				{
					$action1 = '�� '.echo_sex('��������','���������').' ����� ����������� ������ �� ������ �� 40%';
					$action2 = '���������� ����� ����������� ������';                            
					myquery("UPDATE craft_build_lumberjack SET state=140,end_time=0,user_id=0,reserve_user_id=$user_id,reserve_time=".(time()+60)." WHERE id=".$lumber['id']."");                          
					break_axe($action1);
				}
				if($lumber['state']==140)
				{
					$action1 = '�� '.echo_sex('��������','���������').' ����� ����������� ������ �� ������ �� 60%';
					$action2 = '���������� ����� ����������� ������';                            
					myquery("UPDATE craft_build_lumberjack SET state=160,end_time=0,user_id=0,reserve_user_id=$user_id,reserve_time=".(time()+60)." WHERE id=".$lumber['id']."");                          
					break_axe($action1);
				}
				if($lumber['state']==160)
				{
					$action1 = '�� '.echo_sex('��������','���������').' ����� ����������� ������ �� ������ �� 80%';
					$action2 = '���������� ����� ����������� ������';                            
					myquery("UPDATE craft_build_lumberjack SET state=180,end_time=0,user_id=0,reserve_user_id=$user_id,reserve_time=".(time()+60)." WHERE id=".$lumber['id']."");                          
					break_axe($action1);
				}
				if($lumber['state']==180)
				{
					$action1 = '�� '.echo_sex('��������','���������').' ����� ����������� ������ �� ������ �� 100%';
					$action2 = '��������� ������ �� �����';                            
					myquery("UPDATE craft_build_lumberjack SET state=200,user_id=0,end_time=0,brevn1=0,brevn2=0,brevn3=0,brevn4=0,brevn5=0,brevn6=0,klin=0,reserve_user_id=$user_id WHERE id=".$lumber['id']."");
					for ($i=1;$i<=($lumber['nomer']+1);$i++)
					{
						myquery("UPDATE craft_build_lumberjack SET brevn".$i."=1 WHERE id=".$lumber['id'].""); 
					}                         
					setCraftTimes($user_id,4,1,1);                       
					break_axe($action1);
				}
				echo $action1;
				echo '<meta http-equiv="refresh" content="3;url=craft.php">';
			}
		}
	}    
	if ($broken_instrument==1)
	{
		echo '<br /><br /><br /><br />� ���� ��� � ����� ������, ��� �� ��������� ��������. �� �� ������� �������� �� ����������!';
	}
	elseif ($est_items==0)
	{
		echo '<br /><br /><br /><br />� ���� ��� � ����� ������. �� �� ������� �������� �� ����������!';
	}
}  
?>