<?php
//����� ������������ ��� �������� �� 1�� ���� ����������� ��������� �����
function generate_npc_name()
{
	$parts=array('�����','����','����','�����','����','���','����','�����','������','��','���','����','����','���','�����','�����','����','���','���','����','����','����','�����','����','���','���','�����','����','����','���','����','���','���','���','���','��','����','���','���','��','���','����','���','�����','���','����','���','�����','�����','����','�����','���','��','���','����','���','�����','���','���','���','���','���','����','���','����','���','���','���','���','���','���','���','���','���','���','���','���','����','���','���','���','���','����','���','����','���','���','����','���','���','���','����','���','���','����','���','����','�����','���','���','���','���','������','����','���','����','���','���','����','���','���','���','�����','���','���','���','��','����','���','���','����','����','�����','���','���','���','���','����','��','����','��');
	//����� � ������
	$length=mt_rand(2,6);
	$name='';
	$k=0;
	while ($k<$length AND strlen($name)<=10)
	{
		$new=$parts[array_rand($parts,1)];
		if((strlen($name)+strlen($new))<=10)
			$name.=$new;
		else break;
		$buf=$name;
		$ch=1;$k=0;		
		//������� ������, ��. �� �����.
		while ($ch==1)
		{
			$ch=0;
			$letters=array("�","�","�","�","�","�");
			for($i=0;$i<6;$i++)
			{
				if(strpos($buf,$letters[$i])>0)
				{ 
					$buf[strpos($buf,$letters[$i])]='_';
					$ch=1; $k++;
				}
			}
		}
	}
	$name=ucfirst($name);	
	return $name;
}

function generate_npc($npc_name,$npc_race)
{
	global $char;

	//�������� ��� ������� - ������� ��������� �� ��� ���������
	$imgs=myquery("SELECT npc_img FROM game_npc_template");	
	$all = mysql_num_rows($imgs);
	$r = mt_rand(0,$all-1);
	mysql_data_seek($imgs,$r);	
	list($npc_img) = mysql_fetch_array($imgs);
		
	$npc_level = $char['clevel'];
	
	//��� ������ ������, � �� ����� � ������� ���������� ����� � �������� ����������, ������ ���������� ��-�������))
	//�������, � ����������� ���������)
	if($char['STR']>$char['NTL'])
		$k=mt_rand(250,300)/100;
	else	
		$k=mt_rand(350,400)/100;
	$npc_max_hp = $k* $char['HP_MAX'];
	if($char['STR']>$char['NTL'])
		$k=mt_rand(130,150)/10;
	else	
		$k=mt_rand(40,60)/10;;
	$npc_max_mp = $k*abs($char['MP_MAX']);
	
	//���������� ������ � ����
	$npc_str = round($char['HP_MAX'] / 3) + $char['VIT'];
	//��� ������������
	$k=mt_rand(180,280);
	$npc_dex = abs($char['DEX'])*$k/100;        
	//��� ��������
	$k=mt_rand(180,280);
	$npc_pie = abs($char['PIE'])*$k/100;
	//��� ������
	$k=mt_rand(70,150);
	$npc_vit = round(abs($char['STR'])*$k / 100);
	//��� ��������
	$k=mt_rand(35,55);
	$npc_spd = abs($char['SPD'])*$k/100;
	$k=mt_rand(35,55);        
	//���, ��� �� �������, ��������� ;)
	$npc_ntl = round(abs($char['NTL'])*$k / 100);
	
	$npc_exp = 0;
	$npc_gold = 0;
	$npc_map_name = $char['map_name'];
	
	//c���������� ����������
	$current_map_x_y = myquery("SELECT xpos,ypos FROM game_map where name='".$char['map_name']."' ORDER BY xpos DESC, ypos DESC LIMIT 1");
	list($x,$y)=mysql_fetch_array($current_map_x_y);
	
	$npc_xpos = mt_rand(0,$x);
	$npc_ypos = mt_rand(0,$y);     

	$npc_xpos_v = $npc_xpos + mt_rand(-2,2);
	$npc_ypos_v = $npc_ypos + mt_rand(-2,2);
	
	if($npc_xpos_v<0) $npc_xpos_v=0;
	elseif ($npc_xpos_v>$x) $npc_xpos_v=$x;
	if($npc_ypos_v<0) $npc_ypos_v=0;
	elseif ($npc_ypos_v>$y) $npc_ypos_v=$y;
	   
	$npc_time = time();                       
	$npc_item = ' ������ �������������� ';        
	$npc_quest_engine_id=$char['user_id'];
	
	//������� ������
	$ins = myquery("INSERT INTO game_npc_template
	(npc_name, npc_race, npc_img, npc_max_hp, npc_max_mp, npc_str, npc_dex, npc_pie, npc_vit, npc_spd, npc_exp, npc_gold, time_kill, npc_opis, npc_ntl, npc_level, respawn, item, to_delete)
	VALUES
	('$npc_name', '$npc_race', '$npc_img', '$npc_max_hp', '$npc_max_mp', '$npc_str', '$npc_dex', '$npc_pie', '$npc_vit', '$npc_spd', '$npc_exp', '$npc_gold', '$npc_time', '������ �������� ������. ���� �� �� �����, ��� � ��� ������, ����� � �� ���������.', '$npc_ntl', '$npc_level', '15', '$npc_item', 1)");
	
	$npc_id = mysql_insert_id();
	
	$ins = myquery("INSERT INTO game_npc
	(npc_id, HP, MP, EXP, map_name, xpos, ypos, time_kill, stay, view, prizrak, xpos_view, ypos_view, npc_quest_engine_id)
	VALUES
	($npc_id, '$npc_max_hp', '$npc_max_mp', '$npc_exp', '$npc_map_name', '$npc_xpos', '$npc_ypos', '$npc_time', '0', '0', '1', '$npc_xpos_v', '$npc_ypos_v', '$npc_quest_engine_id')");
	
	return mysql_insert_id();	
}

function parameter_value($npc_par_name)
{
	global $char;
	switch($npc_par_name)
	{
		case 'npc_max_mp':return round(mt_rand(0.7*$char['MP_MAX'],1.5*$char['MP_MAX']));break;
		case 'npc_str':return round(mt_rand(0.7*$char['STR'],1.5*$char['STR']));break;
		case 'npc_dex':return round(mt_rand(0.7*$char['DEX'],1.5*$char['DEX']));break;
		case 'npc_pie':return round(mt_rand(0.7*$char['PIE'],1.5*$char['PIE']));break;
		case 'npc_vit':return round(mt_rand(0.7*$char['VIT'],1.5*$char['VIT']));break;
		case 'npc_spd':return round(mt_rand(0.7*$char['SPD'],1.5*$char['SPD']));break;
		//������ � ���� ���� �� ����� ������������� �����������
		//case 'npc_exp':return (mt_rand(50,300));break;
		//case 'npc_gold':return round(mt_rand(0.5,1.5),1);break;
		case 'npc_ntl':return round(mt_rand(0.7*$char['NTL'],1.5*$char['NTL']));break;
		case 'npc_level':return round(mt_rand(0.7*$char['clevel'],1.5*$char['clevel']));break;
	}
}

function item_shop_type($type)
{
	switch ($type)
	{
		case 1:return"oruj";break;
		case 2:return"ring";break;
		case 3:return"artef";break;
		case 4:return"shit";break;
		case 5:return"dosp";break;
		case 6:return"shlem";break;
		case 7:return"mag";break;
		case 8:return"pojas";break;
		case 9:return"amulet";break;
		case 10:return"perchatki";break;
		case 11:return"boots";break;
		case 12:return"bottle";break;
		default:return	"Wrong item type!";break;
	}
}

function session_for_quest($quest_type,$quest_owner_id,$quest_topic_id,$quest_reward,$quest_finish_time,$par1_name,$par1_value,$par2_name,$par2_value,$par3_name,$par3_value,$par4_name,$par4_value)
{
	//���� ������ ��� ����������� � quest_engine_chek.php
	$_SESSION['for_quest']['quest_type']=$quest_type; 
	$_SESSION['for_quest']['quest_owner_id']=$quest_owner_id;
	$_SESSION['for_quest']['quest_topic_id']=$quest_topic_id;
	$_SESSION['for_quest']['quest_reward']=$quest_reward;
	$_SESSION['for_quest']['quest_finish_time']=$quest_finish_time;
	$_SESSION['for_quest']['par1_name']=$par1_name; 
	$_SESSION['for_quest']['par1_value']=$par1_value; 
	$_SESSION['for_quest']['par2_name']=$par2_name; 
	$_SESSION['for_quest']['par2_value']=$par2_value; 
	$_SESSION['for_quest']['par3_name']=$par3_name; 
	$_SESSION['for_quest']['par3_value']=$par3_value; 
	$_SESSION['for_quest']['par4_name']=$par4_name; 
	$_SESSION['for_quest']['par4_value']=$par4_value;
}

//!----------------------------------- ------------------------------------!
//�������� �� �����. �.�. ������ ����� ����� � ����������� �������� � �����������, ��� ���� ���������
	if(!isset($_SESSION['for_quest']['give']) AND !isset($_SESSION['for_quest']['quest_type']))
	{		
		$_SESSION['for_quest']['give']=0;
		//������ ���������� ����� ������������ ������, ����� ������ ���������� ������ � ������� ������ � ���� ������������������
		$qe_stats=myquery("SELECT quest_num,quest_last,quest_first FROM quest_engine_stats WHERE user_id=".$user_id."");
		//���� ���� ��� �� ������ � ��� �������, ������
		if(mysql_num_rows($qe_stats)<=0)
		{
			myquery("INSERT INTO quest_engine_stats (user_id,quest_num,quest_first,quest_last,quests_done) VALUES (".$user_id.",0,0,0,0)");
			$qe_stats=myquery("SELECT quest_num,quest_last,quest_first FROM quest_engine_stats WHERE user_id=".$user_id."");
		}
		
		list($q_num,$last_time,$first_time)=mysql_fetch_array($qe_stats);
		//������ ��� �������� 3 ������ �� 24 ����, �� ����� ��� � 3� ������� ������
		//�������� ��������� ������� ����������������, ���������� ����� ���������� - ��� ������������
		if($q_num>=3 OR $q_num==0)//0 - ���� ��� ����� 1�� ����� �����
		{
			//if(time()-$first_time>=24*60*60)
			if(time()-$first_time>=10)
			{				
				//���� �����
				$_SESSION['for_quest']['give']=2;
			}else 
			{
				//�� ���� �����
				$_SESSION['for_quest']['give']=0;
			}
		}else 
		{
			//if(time()-$last_time>=3*60*60)
			if(time()-$last_time>=20)
			{
				//���� �����
				$_SESSION['for_quest']['give']=1;
			}else 
			{
				//�� ���� �����
				$_SESSION['for_quest']['give']=0;
			}
		}
	}

//���� �����  �� ���, ����� ����������� ����� ������� ��������
if($_SESSION['for_quest']['give']==0 AND $rep_num!=0)
	$rep_num=666;

//����� �� ���������� ������ ��� ��� standart_vars.inc
$char_name='<font color=red>'.$char["name"].'</font>';
$char_race=mysql_result(myquery("SELECT name FROM game_har WHERE id=".$char['race'].""),0,0);
//�������, ��� ����������
switch ($rep_num)
{
	//���������� �����
	case '666':
	{
		$_SESSION['for_quest']['rep_num']=666;
		echo '<tr><td align="center"><TABLE align="center" border="0" cellpadding="0" cellspacing="0"><tr><td><p align=justify>';
		//�������� �� ���������� ������, ���� �� �������� ��� ���������� ���
		if(!isset($_SESSION['for_quest']['goodbuy_id']))
		{
			$text=myquery("SELECT id,text FROM quest_engine_topics WHERE owner_id=".$owner_id." AND action_type=12");
			if(mysql_num_rows($text)>0)
			{
				$all = mysql_num_rows($text);
				$r = mt_rand(0,$all-1);
				mysql_data_seek($text,$r);	
				list($_SESSION['for_quest']['goodbuy_id'],$text)=mysql_fetch_array($text);
			}
			else 
				$text = "echo '����� ��������. � �� �� �������.';";
		}
		else 
		{	
			$text=myquery("SELECT text FROM quest_engine_topics WHERE id=".$_SESSION['for_quest']['goodbuy_id']." AND owner_id=".$owner_id." AND action_type=12");
			if(mysql_num_rows($text)>0)
				$text=mysql_result($text,0,0);
			else 
				$text = "echo '����� ��������. � �� �� �������.';";			
		}
		
		eval($text);
		echo '</tr></td></table><br><br><hr size=2 width=85%><br></tr></td><tr><td><center>';
		echo '<font color=#F0F0F0><a href ="?rep_num=-1">*����������� � �����* </a><br>';
		echo '</tr></td>';
		break;
	}
	//����� �� ����� �������� �����-���� �������?
	case '0':
	{
		$_SESSION['for_quest']['rep_num']=0;
		echo '<tr><td align="center"><TABLE align="center" border="0" cellpadding="0" cellspacing="0"><tr><td><p align=justify>';
		//������ ���, ��� ������� ���� ��� ��� ��� ����
		if($_SESSION['for_quest']['give']==0)
			$act_type=14;
		elseif($_SESSION['for_quest']['give']>0)
			$act_type=11;

		//�������� �� ���������� ������, ���� �� �������� ��� ���������� ���		
		if(!isset($_SESSION['for_quest']['hello_id']))
		{
			$text=myquery("SELECT id,text FROM quest_engine_topics WHERE owner_id=".$owner_id." AND action_type=".$act_type."");
			if(mysql_num_rows($text)>0)
			{
				$all = mysql_num_rows($text);
				$r = mt_rand(0,$all-1);
				mysql_data_seek($text,$r);	
				list($_SESSION['for_quest']['hello_id'],$text)=mysql_fetch_array($text);
			}
			else 
				if($act_type==11) $text = "echo '����� ����������� (1). � �� �� �������.';"; 
				else $text = "echo '����� � ���� ���� ��� ��� ���� ������� (1). � �� �� �������.';";
			
		}else 
		{	
			$text=myquery("SELECT text FROM quest_engine_topics WHERE id=".$_SESSION['for_quest']['hello_id']." AND owner_id=".$owner_id." AND action_type=".$act_type."");
			if(mysql_num_rows($text)>0)
				$text=mysql_result($text,0,0);
			else 
				if($act_type==11) $text = "echo '����� ����������� (2). � �� �� �������.';"; 
				else $text = "echo '����� � ���� ���� ��� ��� ���� ������� (2). � �� �� �������.';";			
		}
		
		eval($text);
		echo '</tr></td></table><br><br><hr size=2 width=85%><br></tr></td><tr><td><center>';
				
		if($_SESSION['for_quest']['give']>0)
		{
			//$action_type ��� ����� � quests_check
			//� ��������� �������� �������� ���������� ��� ������� ����� ������.
			echo '<font color=#F0F0F0><a href ="?rep_num=1&test=1"> ��� 1. ����� �������� �������.</a><br>';
			echo '<font color=#F0F0F0><a href ="?rep_num=1&test=2"> ��� 2. ����� ��������� �����.</a><br>';
			//��� ���� �����������
			//echo '<font color=#F0F0F0><a href ="?rep_num=1&ans=1&test=3"> ��� 3. ������� �����.</a><br>';
			//echo '<font color=#F0F0F0><a href ="?rep_num=1&ans=1&test=4"> ��� 4. ������� �����.</a><br>';
			echo '<font color=#F0F0F0><a href ="?rep_num=1&test=5"> ��� 5. ������� �������.</a><br>';
			echo '<font color=#F0F0F0><a href ="?rep_num=1&test=601"> ��� 6-01. �����������: �-�� ������.</a><br>';
			echo '<font color=#F0F0F0><a href ="?rep_num=1&test=602"> ��� 6-02. �����������: �������������.</a><br>';
			echo '<font color=#F0F0F0><a href ="?rep_num=1&test=7"> ��� 7. �������� "���".</a><br>';
			echo '<font color=#F0F0F0><a href ="?rep_num=1&test=801"> ��� 801. �������� ������� �������.</a><br>';
			echo '<font color=#F0F0F0><a href ="?rep_num=1&test=802"> ��� 802. �������� � ��������� ��������� - �� �������� � ����.</a><br>';
			echo '<font color=#F0F0F0><a href ="?rep_num=1&test=803"> ��� 803. �������� � ��������� ��������� �� ���-���.</a><br>';
			echo '<font color=#F0F0F0><a href ="?rep_num=1&test=804"> ��� 804. �������� � ��������� ��������� - �� % ������� ������.</a><br>';
			echo '<br><br>';
			
			echo '<font color=#F0F0F0><a href ="?rep_num=1">*���������* </a><br>';
			echo '<font color=#F0F0F0><a href ="?rep_num=666">*����������* </a><br>';
		}//������ ���� ��� - �� ������ ������ �����
		elseif ($_SESSION['for_quest']['give']==0)
		{
			echo '<font color=#F0F0F0><a href ="?rep_num=-1">*����*</a><br>';
		}
		echo '</tr></td>';
		break;
	}
	//������, ����� ������ �������. ���� ��������� - ��������� ������� ������ �������� ����� �����
	case '1':
	{		
		//���� ���� �����, ����� ��� �� ���� �����
		if(isset($_SESSION['for_quest']['give']) AND $_SESSION['for_quest']['give']==0)
		{
			//��������� ���
			setLocation("?rep_num=-1");
			if ($_SERVER['REMOTE_ADDR']==debug_ip) {show_debug();}
			if (function_exists("save_debug")) save_debug();
			exit();
		}else
		//�����//���� �����
		{
			$_SESSION['for_quest']['rep_num']=1;
			//���� ��� ��� �� ������
			if(!isset($_SESSION['for_quest']['quest_type']))
			{	
				$ok=0;
				//������� �� �����
				//���������� ������������������ �������
				if($_SESSION['for_quest']['give']==1)
					$up=myquery("UPDATE quest_engine_stats SET quest_num=quest_num+1, quest_last=".time()." WHERE user_id=".$user_id."");
				//����� ������������������ �������	
				elseif ($_SESSION['for_quest']['give']==2)
					$up=myquery("UPDATE quest_engine_stats SET quest_num=1, quest_last=".time().",quest_first=".time()." WHERE user_id=".$user_id."");
				//������ �������� �����
				while ($ok==0)	
				{
					if(isset($test)) 
						$q_type=$test;
					else
						$q_type=$_SESSION['for_quest']['correct_types'][array_rand($_SESSION['for_quest']['correct_types'],1)];		
				
					//������� ����� ������ (������)
					$topic_id=myquery("SELECT topic_id FROM quest_engine_topics WHERE owner_id=".$owner_id." AND action_type=13 AND quest_type=".$q_type."");
					if(mysql_num_rows($topic_id)>0)
					{
						$all = mysql_num_rows($topic_id);
						$r = mt_rand(0,$all-1);
						mysql_data_seek($topic_id,$r);	
						list($topic_id)=mysql_fetch_array($topic_id);			
					}else $topic_id=-1; 
					//������� ��������� ����� (�������, ��� ��� �����, ����� ������� ���� ��������� ������ N ������� � ���� �������, ��� �� ������, � �� � ����, ��� �� ����������� �� �����.
					$_SESSION['for_quest']['quest_start_time']=time();
				
					//����������� � ����� ������
					switch($q_type)
					{
						//���� ��� - ����� ������������� �������
						case 1:
						{						
							//��������� ����� ������� ���� ������ 10 �������
							$time=$_SESSION['for_quest']['quest_start_time']+600;
							$reward=200;
													
							$npc_name=generate_npc_name();
							$_SESSION['for_quest']['npc_name']=$npc_name;
							$npc_race='������ �������';
							$_SESSION['for_quest']['npc_race']=$npc_race;
							//���� ��� 0, ������ �������� ����� ���������� ��� ��������� ����
							$npc_id=0;
							
							session_for_quest($q_type,$owner_id,$topic_id,$reward,$time,'npc_id',$npc_id,$npc_name,0,$npc_race,0,'npc_health',100);				
							$ok=1;						
							break;	
						}
						//����� ��������� �������� 
						case 2:
						{																						
							$pars_num=mt_rand(1,3);
							echo '<br>'.$pars_num.'<br>';
							$pars=array('npc_max_hp','npc_max_mp','npc_str','npc_dex','npc_pie','npc_vit','npc_spd',/*'npc_exp','npc_gold',*/'npc_ntl'/*,'npc_level'*/);
							
							$check=0;
							//��������� � ����� ��������� ��������
							for($i=1;$i<=$pars_num;$i++)
							{
								if($i==1 OR ($check==-777))
								{
									while(1)
									{
										$par_name[$i]=$pars[array_rand($pars,1)];
										$pars=array_diff($pars,array($par_name[$i]));
										$par_value[$i]=parameter_value($par_name[$i]);
										if(isset($bots))
											mysql_free_result($bots);
											
										$query="SELECT game_npc.id,game_npc_template.npc_name FROM game_npc,game_npc_template WHERE game_npc.view=1 AND game_npc.npc_quest_engine_id=0 AND game_npc_template.npc_id=game_npc.npc_id AND game_npc_template.".$par_name[1].">=".$par_value[1]."";
										if($i>1) $query.=" AND game_npc_template.".$par_name[2].">=".$par_value[2]."";
										if($i>2) $query.=" AND game_npc_template.".$par_name[3].">=".$par_value[3]."";
										
										$bots=myquery($query);
										if(mysql_num_rows($bots)>0) 
										{
											$check=-777;
											break;
										}
										if($check>=150)
										{
											$check=-666;
											break;
										}
										$check++;
									}
									
									if ($check==-666) 	
									{
										if($i==1)
										{
											$par2_name='npc_max_hp';                
											$par2_value=$char['HP_MAX']*1.3;//?
											$check=-777;
										}else 
										{
											$par_name[$i]='';                
											$par_value[$i]='';
										}
									}
									if($check==-777)
										$par_rus_name[$i]=parameter_rus_name($par_name[$i]);
									
								}
							}
							
							//�������������� ���������������� ����������
							if(!isset($par_name[2])) $par_name[2]='';
							if(!isset($par_value[2])) $par_value[2]=0;
							if(!isset($par_rus_name[2])) $par_rus_name[2]='';
							if(!isset($par_name[3])) $par_name[3]='';
							if(!isset($par_value[3])) $par_value[3]=0;
							if(!isset($par_rus_name[3])) $par_rus_name[3]='';
							
							//��������� ���-�� �������
							$num=mt_rand(5,10);		
							
							//��������� ����� ������� ���� ������ �� 2 ������ �� ����
							$time=$_SESSION['for_quest']['quest_start_time']+$num*120;
							//�������� ������� - 260?
							$reward=$num*2*13; 
							//��� �������� �� ������ ������ ���� 2
							$other_quests=myquery("SELECT par1_name FROM quest_engine_users WHERE user_id='$user_id' AND quest_type=2");
							$i=0;
							$names=array();
							while (list($name)=mysql_fetch_array($other_quests))
							{
								$names[$i]=$name;
								$i++;
							}
							$names=array_diff(array("����","����","������� ����","������","�����","������","������","������� ��������","������� ������","��������","�������","������������ �����"),$names);						
							$part_name=$names[array_rand($names,1)];	
																		
							session_for_quest($q_type,$owner_id,$topic_id,$reward,$time,$part_name,$num,$par_name[1],$par_value[1],$par_name[2],$par_value[2],$par_name[3],$par_value[3]);
							
							//����� �� ������������ ������ ����� � ������ �������
							if($par_name[2]=='') $par_value[2]='';
							if($par_name[3]=='') $par_value[3]='';

							$ok=1;
							break;
						}
						
						//���� � ������ ������ �� ������������	
						/*case 3:
						{
							//��������� ����� ������� ���� ������ 20 �������
							$time=$_SESSION['for_quest']['quest_start_time']+1200;
							$reward=1000;
							//��������� ����
							if($char['clevel']<10 AND $char['clevel']>5)
							{
								$exp=2000+3000*($char['clevel']-5)/5;
							}
							if($char['clevel']<35 AND $char['clevel']>=10)
							{
								$exp=($char['clevel']-5)*1000;
							}
							if($char['clevel']<40 AND $char['clevel']>=35)
							{
								$exp=30000+10000*($char['clevel']-35)/5;
							}
							if($char['clevel']>=40)
							{
								$exp=$char['clevel']*1000;
							}						
							$exp=$exp+$exp*mt_rand(-100,100)/1000;
							$exp=ceil($exp/3);
							session_for_quest($q_type,$owner_id,$topic_id,$reward,$time,'EXP_NEED',$exp,'EXP_IS',0,'',0,'',0);	
							break;
						}
						//������� � ����� - ������
						case 4:
						{
							//��������� ����� ������� ���� ������ 10 �������
							$time=$_SESSION['for_quest']['quest_start_time']+600;
							$reward=1000;
							//���� ��� ������ ���
							$wins=mt_rand(3,8);
							$wins+=$char['win'];
							session_for_quest($q_type,$owner_id,$topic_id,$reward,$time,'win',$wins,'',0,'',0,'',0);				
							break;
						}			*/		
						//������� � �����
						case 5:
						{
							//������� ����� (�� ������ �����): 18 - ��, 5 - ���. ���� ����� ������ �� ������ ������, ����� ������������ ��.
							//��������, ������� �����. ���� ��������.
							
							$map_ids=myquery("SELECT id,name FROM game_maps WHERE id!=".$char['map_name']." AND id IN (18,5)");							
							$all = mysql_num_rows($map_ids);
							$r = mt_rand(0,$all-1);
							mysql_data_seek($map_ids,$r);
							list($map_id,$map_name)=mysql_fetch_array($map_ids);
							
							//������� �����, �����, ����� � ���� �� ���� �������
							$town_ids=myquery("SELECT xpos,ypos,town FROM game_map WHERE town!=0 AND to_map_name=0 AND name=".$map_id."");
							$check=0;						
							while ($check==0)
							{			
								//������� �����
								$all = mysql_num_rows($town_ids);
								$r = mt_rand(0,$all-1);
								mysql_data_seek($town_ids,$r);
								list($xpos,$ypos,$town_id)=mysql_fetch_array($town_ids);
								//��������, ��� �� � ���� �������
								$towns=myquery("SELECT * FROM quest_engine_users WHERE user_id=".$user_id." AND quest_type=5 AND par1_value=".$map_id." AND par2_value=".$town_id." ");
								if(mysql_num_rows($towns)==0) $check=1; else $check=0;							
							}			
							$rustowun=mysql_result(myquery("SELECT rustown FROM game_gorod WHERE town=".$town_id.""),0,0);
							//��������� ����� ������� ���� ������ 50 �������
							$time=$_SESSION['for_quest']['quest_start_time']+3000;
							$reward=250;
							
							session_for_quest($q_type,$owner_id,$topic_id,$reward,$time,'map_name',$map_id,'town_name',$town_id,'xpos',$xpos,'ypos',$ypos);
						
							if(substr_count($map_name,'��e'))	$map_name.='e';	
							$ok=1;					
							break;
						}
						//�����������: ������					
						case 601: 
						{
							//��������� ����� � ����� - ���� ����������� �� �����
							$current_map_x_y = myquery("SELECT xpos,ypos FROM game_map where name='".$char['map_name']."' ORDER BY xpos DESC, ypos DESC LIMIT 1");
							list($mx,$my)=mysql_fetch_array($current_map_x_y);
													
							do
							{
								$x=mt_rand(0,$mx);
								$y=mt_rand(0,$my);
							}while ( ($x>$char['map_xpos']-$mx*0.25) AND ($x<$char['map_xpos']+$mx*0.25) AND ($y>$char['map_ypos']-$mx*0.25)  AND ($y<$char['map_ypos']+$mx*0.25));
							
							$map_id=$char['map_name'];
							$map_name=mysql_result(myquery("SELECT name FROM game_maps WHERE id=".$char['map_name'].""),0,0);
							
							//��������� ����� ������� ���� ������ 15 �������
							$time=$_SESSION['for_quest']['quest_start_time']+1200;
							$reward=200;
							
							session_for_quest($q_type,$owner_id,$topic_id,$reward,$time,'empty',$map_id,'empty',$x,'empty',$y,'errors',0);
	
							if(substr_count($map_name,'��e'))	$map_name.='e';
							$ok=1;
							break;
						}
						//����������� "�������������"
						case 602:
						{
							echo "���� �� ������.";	
							break;
						}
						//�� "���"
						case 7:
						{	
							//���������� ������ (�� ���� ������ ��������)
							//���� ����, ������� �� ��������� � ������������� ���������, ������ ���� � ����, �� �� � ������� ������
							//�� 12 - ����, ������� �������, � ����� - ��������, ������ � �.�.
							//�������� �� ������� �������� �� PocetPlane
							$items=myquery("
							(SELECT id,type,name FROM game_items_factsheet WHERE 
							id NOT IN (SELECT game_items_factsheet.id FROM game_items_factsheet JOIN game_shop_items 
							ON game_items_factsheet.id=game_shop_items.items_id JOIN game_shop ON game_shop.id=game_shop_items.shop_id WHERE game_shop.prod!='0' AND game_shop.map!=20) 
							AND personal=0 AND view='1' AND type<12
							AND (id IN (SELECT item_id FROM game_items))
							AND id NOT IN (SELECT item_id FROM game_items WHERE user_id='$user_id')
							)");
							$i=0;
							
							while (1) 					
							{			
								$i++;	
								if($i>150) 
								{
									$_SESSION['for_quest']['correct_types']=array_diff($_SESSION['for_quest']['correct_types'],array('7'));
									$ok=0;
									break;
								}				
								$all = mysql_num_rows($items);
								$r = mt_rand(0,$all-1);
								mysql_data_seek($items,$r);								
								list($id,$t,$name)=mysql_fetch_array($items);
								//�������� ���
								if($t<=0 OR $t>12)
									continue;
								
								//�������� �� ������ ������, ��� �� ���� ���� � �������
								//�������� �� ������� �������� �� PocetPlane	
								$t=item_shop_type($t);
								$prod=myquery("SELECT game_shop.id FROM game_shop JOIN game_shop_items ON game_shop.id=game_shop_items.shop_id WHERE game_shop_items.items_id=".$id." AND game_shop.prod!='0' AND game_shop.".$t."!='0' AND game_shop.map!=20");
								if(mysql_num_rows($prod)>0) 
									continue;
								else 
								{ 
									$ok=1; 
									break;
								}
							}
							if($ok==1)
							{
								//�������� ����������� ��������	
								//���� �� �� � ������� � �������, �� ������ � � �����������
								$online2=mysql_num_rows(myquery("SELECT id FROM game_items WHERE item_id='".$id."' AND priznak='0' AND user_id IN (SELECT user_id FROM game_users_active WHERE last_active>".time()."-600)"));
								$market=mysql_num_rows(myquery("SELECT id FROM game_items WHERE item_id='".$id."' AND town>0 AND priznak='1'"));
								if($char['clan_id']>0)
									$in_clan=mysql_num_rows(myquery("SELECT id FROM game_items WHERE item_id='".$id."' AND priznak='0' AND user_id IN (SELECT user_id FROM game_users WHERE clan_id=".$char['clan_id'].")"));
								else 
									$in_clan=0;	
								
								$ptime=60*(125-1.07*(0.5*$char['clevel']+12*$online2+3*$market+6*$in_clan));
								$time=$_SESSION['for_quest']['quest_start_time']+max($ptime,16*60);
								$reward=200;
								session_for_quest($q_type,$owner_id,$topic_id,$reward,$time,'id',$id,'',0,'',0,'',0);
								$ok=1;
							}
							break;
						}
						//��������
						case 801:case 802:case 803:case 804:
						{	
							$subtype=$q_type%10;
							switch ($subtype)
							{
								//1 �������
								case 1:
								{
									//�� 12 - ����, ������� �������, � ����� - ��������, ������ � �.�.
									//������������� ������ 
									$items=myquery("SELECT game_items_factsheet.id,game_items_factsheet.type
									FROM game_items_factsheet JOIN game_shop_items ON game_items_factsheet.id=game_shop_items.items_id
									JOIN game_shop ON game_shop_items.shop_id=game_shop.id
									WHERE game_items_factsheet.item_cost>=240 AND game_items_factsheet.item_cost<=99999 
									AND game_items_factsheet.personal=0 AND game_items_factsheet.type<12 AND game_items_factsheet.view='1'
									AND game_shop.prod!='0' AND game_shop.map!=20");
									$i=0;
									
									while (1)
									{				
										$i++;
										if($i>150)
										{
											
											$_SESSION['for_quest']['correct_types']=array_diff($_SESSION['for_quest']['correct_types'],array('801'));
											$ok=0;
											break;
										}					
										$all = mysql_num_rows($items);
										$r = mt_rand(0,$all-1);
										mysql_data_seek($items,$r);											
										list($id,$t)=mysql_fetch_array($items);
										if($t<=0 OR $t>12) continue;
										$t=item_shop_type($t);
										$prod=myquery("SELECT game_shop.id FROM game_shop JOIN game_shop_items ON game_shop.id=game_shop_items.shop_id WHERE game_shop_items.items_id=".$id." AND game_shop.prod!='0' AND game_shop.".$t."!='0' AND game_shop.map!=20");
										if(mysql_num_rows($prod)>0)
										{ 
											$ok=1; 
											break;	
										}
									}
									if($ok==1)
									{
										$k=mt_rand(1,4);
										$ids=array($id,0,0,0);
										if($k>1)
										{									
											for($i=1;$i<$k;$i++)
											{
												$all = mysql_num_rows($items);
												$r = mt_rand(0,$all-1);
												mysql_data_seek($items,$r);	
												list($ids[$i])=mysql_fetch_array($items);						
											}
											$ids=array_unique($ids);									
										}
										$name='';
										for($i=0;$i<4;$i++)
										{
											if(!isset($ids[$i])) $ids[$i]=0;
											$cname=mysql_result(myquery("SELECT name FROM game_items_factsheet WHERE id=".$ids[$i].""),0,0);			
											if(!empty($cname))
											$name.=$cname.' ��� ';																
										}
										$name=substr($name,0,strlen($name)-4);
										
										$time=$_SESSION['for_quest']['quest_start_time']+900;
										$reward=200;
										session_for_quest($q_type,$owner_id,$topic_id,$reward,$time,'id1',$ids[0],'id2',$ids[1],'id3',$ids[2],'id4',$ids[3]);
										$ok=1;
									}
									break;
								}
								//H ��������� �� �������� � ����							
								case 2:
								{
									//������� �������� - ��� �������� ������ ���� ��������� ���� ����� ��������� �������� �� ����
									$shops=myquery("SELECT * FROM game_shop WHERE prod='1' AND (map=18 OR map=5) AND
									(shlem='1' OR oruj='1' OR dosp='1' OR shit='1' OR pojas='1' OR ring='1')");
									$types=array(); $i=0; $j=0;
									$ok=1;
									while (count($types)==0)
									{
										$j++;
										if($j>150) 
										{
											$_SESSION['for_quest']['correct_types']=array_diff($_SESSION['for_quest']['correct_types'],array('802'));
											$ok=0;
											break;
										}			
										$all = mysql_num_rows($shops);
										$r = mt_rand(0,$all-1);
										mysql_data_seek($shops,$r);	
										$shop=mysql_fetch_array($shops);
										$shop_id=$shop['id'];
										//�������� ������ �� ��������� ����� ���������
										$is=mysql_num_rows(myquery("SELECT * FROM game_shop_items JOIN game_items_factsheet ON game_shop_items.items_id=game_items_factsheet.id WHERE shop_id=".$shop_id." AND game_items_factsheet.type=6"));
										if($shop['shlem']==1 AND $is>0) {$types[$i]=6; $i++;}
										
										$is=mysql_num_rows(myquery("SELECT * FROM game_shop_items JOIN game_items_factsheet ON game_shop_items.items_id=game_items_factsheet.id WHERE shop_id=".$shop_id." AND game_items_factsheet.type=1"));
										if($shop['oruj']==1 AND $is>0) {$types[$i]=1; $i++;}
										
										$is=mysql_num_rows(myquery("SELECT * FROM game_shop_items JOIN game_items_factsheet ON game_shop_items.items_id=game_items_factsheet.id WHERE shop_id=".$shop_id." AND game_items_factsheet.type=5"));
										if($shop['dosp']==1 AND $is>0) {$types[$i]=5; $i++;}
										
										$is=mysql_num_rows(myquery("SELECT * FROM game_shop_items JOIN game_items_factsheet ON game_shop_items.items_id=game_items_factsheet.id WHERE shop_id=".$shop_id." AND game_items_factsheet.type=4"));
										if($shop['shit']==1 AND $is>0) {$types[$i]=4; $i++;}
										
										$is=mysql_num_rows(myquery("SELECT * FROM game_shop_items JOIN game_items_factsheet ON game_shop_items.items_id=game_items_factsheet.id WHERE shop_id=".$shop_id." AND game_items_factsheet.type=8"));
										if($shop['pojas']==1 AND $is>0) {$types[$i]=8; $i++;}
										
										$is=mysql_num_rows(myquery("SELECT * FROM game_shop_items JOIN game_items_factsheet ON game_shop_items.items_id=game_items_factsheet.id WHERE shop_id=".$shop_id." AND game_items_factsheet.type=2"));
										if($shop['ring']==1 AND $is>0) {$types[$i]=2; $i++;}										
									}
									if($ok==1)
									{
										//������� ��������� ���
										$type_id=$types[array_rand($types)];
										//��������� ����������
										$num=mt_rand(3,6);
										
										$shop_name=$shop['name'];
										//������� ����� //����� ���� ���������� ������, �� ����� ����� ��������!
										if($shop['map']==$char['map_name'])
										{
											$reward=200;
											$time=$_SESSION['for_quest']['quest_start_time']+900;
										}
										else 
										{
											$reward=300;
											$time=$_SESSION['for_quest']['quest_start_time']+900+3600+2700;
										}
										session_for_quest($q_type,$owner_id,$topic_id,$reward,$time,'shop_id',$shop_id,0,$type_id,'num',$num,'empty',0);
										$ok=1;
									}
									break;
								}
								//� ��������� �� ���-���
								case 3:
								{
									$stats=array("dstr","dntl","dpie","dvit","ddex","dspd");
									$i=0;
									//��������� ������ ��������
									while(1)
									{
										$i++;
										if($i>150)
										{
											$_SESSION['for_quest']['correct_types']=array_diff($_SESSION['for_quest']['correct_types'],array('803'));
											$ok=0;
											break;
										}
										$stat1_name=$stats[array_rand($stats)];
										$stat1_value=mt_rand(1,2);	
										
										//��������������
										$items=myquery("SELECT game_items_factsheet.id,game_items_factsheet.type 
										FROM game_items_factsheet JOIN game_shop_items ON game_items_factsheet.id=game_shop_items.items_id
										JOIN game_shop ON game_shop_items.shop_id=game_shop.id
										WHERE game_items_factsheet.".$stat1_name.">=".$stat1_value." 
										AND ( game_items_factsheet.type<12)
										AND game_items_factsheet.item_cost<=200
										AND game_items_factsheet.view='1' AND game_items_factsheet.personal=0 
										AND game_shop.prod!='0'");
										
										if(mysql_num_rows($items)==0) continue;
										$k=0;									
										while (list($id,$t)=mysql_fetch_array($items))
										{
											if($t<=0 OR $t>12) continue;
											$t=item_shop_type($t);
											$prod=myquery("SELECT game_shop.id FROM game_shop JOIN game_shop_items ON game_shop.id=game_shop_items.shop_id WHERE game_shop_items.items_id=".$id." AND game_shop.prod>0 AND game_shop.".$t.">0");
											if(mysql_num_rows($prod)>0) { $k=1; break;	}
										}
										if($k==0) continue;
										elseif(mysql_num_rows($items)>0) { $ok=1; break;}
									}
									if($ok==1)
									{
										//���������,���� ��,�,���� ����,����� ��������
										if(mt_rand(1,2)==2)
										{
											$i=0;
											//��������� 2� ��������
											while(1)
											{
												$i++;
												if($i>150)
												{
													$stat2_name="";
													$stat2_value=0;
													break;										
												}
												
												$stat2_name=$stat1_name;
												while ($stat2_name==$stat1_name)
													$stat2_name=$stats[array_rand($stats)];
												$stat2_value=mt_rand(1,2);
												
												//��������������
												$items=myquery("SELECT game_items_factsheet.id,game_items_factsheet.type 
												FROM game_items_factsheet JOIN game_shop_items ON game_items_factsheet.id=game_shop_items.items_id
												JOIN game_shop ON game_shop_items.shop_id=game_shop.id
												WHERE game_items_factsheet.".$stat1_name.">=".$stat1_value." AND game_items_factsheet.".$stat2_name.">=".$stat2_value." 
												AND ( game_items_factsheet.type>0 AND game_items_factsheet.type<12)
												AND game_items_factsheet.item_cost<=200
												AND game_items_factsheet.view='1' AND game_items_factsheet.personal=0 
												AND game_shop.prod>0");
												
												if(mysql_num_rows($items)==0) continue;
												$k=0;									
												while (list($id,$t)=mysql_fetch_array($items))
												{
													if($t<=0 OR $t>12) continue;
													$t=item_shop_type($t);
													$prod=myquery("SELECT game_shop.id FROM game_shop JOIN game_shop_items ON game_shop.id=game_shop_items.shop_id WHERE game_shop_items.items_id=".$id." AND game_shop.prod>0 AND game_shop.".$t.">0");
													if(mysql_num_rows($prod)>0) { $k=1; break;	}
												}
												if($k=0) continue;
												elseif(mysql_num_rows($items)>0) break;
											}	
										}
										
										$num=mt_rand(3,6);
										
										$name=''.item_par_name($stat1_name).' �� '.$stat1_value.'';																
										if(isset($stat2_value) AND $stat2_value!=0)
											$name.=' � '.item_par_name($stat2_name).' �� '.$stat2_value.'';
										else { $stat2_value=0; $stat2_name="";}
										
										$time=$_SESSION['for_quest']['quest_start_time']+900;
										$reward=200;
										
										session_for_quest($q_type,$owner_id,$topic_id,$reward,$time,$stat1_name,$stat1_value,$stat2_name,$stat2_value,'num',$num,'empty',0);
										$ok=1;
									}
									break;
								}
								//�� % ������� ������
								case 4:
								{
														
									$ok=1;	
																
									//��������������
									$items=myquery("SELECT game_items_factsheet.id
									FROM game_items_factsheet JOIN game_shop_items ON game_items_factsheet.id=game_shop_items.items_id
									JOIN game_shop ON game_shop_items.shop_id=game_shop.id
									WHERE game_items_factsheet.type=1 AND game_items_factsheet.oclevel<=".$char['clevel']."
									AND game_items_factsheet.personal=0 AND game_items_factsheet.view='1' 
									AND game_shop.prod>0");
									
									if(mysql_num_rows($items)==0) 
									{
											$_SESSION['for_quest']['correct_types']=array_diff($_SESSION['for_quest']['correct_types'],array('804'));
											$ok=0;
									}
									if($ok==1)
									{
										$i=0;
										//��� - ������ ������
										$t=1;
										$t=item_shop_type($t);
										while (1)
										{
											$i++;
											if($i>150)
											{
												$_SESSION['for_quest']['correct_types']=array_diff($_SESSION['for_quest']['correct_types'],array('804'));
												$ok=0;
												break;
											}		
											
											$all = mysql_num_rows($items);
											$r = mt_rand(0,$all-1);
											mysql_data_seek($items,$r);	
											$id=mysql_result($items,0,0);
											$prod=myquery("SELECT game_shop.id FROM game_shop JOIN game_shop_items ON game_shop.id=game_shop_items.shop_id WHERE game_shop_items.items_id=".$id." AND game_shop.prod>0 AND game_shop.".$t.">0");
											if(mysql_num_rows($prod)>0) {$ok=1; break;}
										}
									
										if($ok==1)
										{
											$top=mt_rand(30,70);
											$buttom=round($top*mt_rand(30,80)/100);
											$wname=mysql_result(myquery("SELECT name FROM game_items_factsheet WHERE id=".$id.""),0,0);		
											$time=$_SESSION['for_quest']['quest_start_time']+900;
											$reward=200;
											session_for_quest($q_type,$owner_id,$topic_id,$reward,$time,'id',$id,'top',$top,'buttom',$buttom,'empty',0);
											$ok=1;
										}
									}
									break;
								}
							}						
							break;
						}
						default:
							echo '�� ������!';
							break;
					}				
			}
		}
			//���� ������ ��� ���� 
			if(isset($_SESSION['for_quest']['quest_type']))
			{
				//echo '������ �����������';
				$quest_user=array();
				$quest_user=$_SESSION['for_quest'];
				include("quest_engine_types/inc/standart_vars.inc.php");
				//unset($quest_user);
				
				//QuoteTable('open');
				echo '<tr><td align="center"><TABLE align="center" border="0" cellpadding="0" cellspacing="0"><tr><td><p align=justify>';
				/*1*///echo 'Topic ID = '.$_SESSION['for_quest']['quest_topic_id'].' Owner id = '.$_SESSION['for_quest']['quest_owner_id'].' Quest Type = '.$_SESSION['for_quest']['quest_type'].'';
				$text=myquery("SELECT text FROM quest_engine_topics WHERE topic_id=".$_SESSION['for_quest']['quest_topic_id']." AND owner_id=".$_SESSION['for_quest']['quest_owner_id']." AND action_type=13 AND quest_type=".$_SESSION['for_quest']['quest_type']."");
				//$text=myquery("SELECT text FROM quest_engine_topics WHERE topic_id=".$topic_id." AND owner_id=".$owner_id." AND action_type=13 AND quest_type=".$q_type."");				
				if(mysql_num_rows($text)>0)
					list($text)=mysql_fetch_array($text);
				else 
					$text = "echo '����� �������. � �� �� �������.';";				
				
				//��� ��� �����!
				switch ($quest_user['quest_type'])
				{
					case 1: $text="echo '������: ".new_word('gold',$reward,0)." <br>�����: $time <br>���: $npc_name $npc_race';"; break;
					case 2: $text="echo '������: ".new_word('gold',$reward,0)." <br>�����: $time <br>�����: $part_name - $num ��<br>����: ��� $par2_rus_name $par2_value $par3_rus_name $par3_value $par4_rus_name $par4_value';"; break;
					case 5 :$text="echo '������: ".new_word('gold',$reward,0)." <br>�����: $time <br>�����: $rustowun, ".new_word("map_name",$map_name,1)."';"; break; 
					case 601:$text="echo '������: ".new_word('gold',$reward,0)." <br>�����: $time <br>�����: ".new_word("map_name",$map_name,1)." $x $y';"; break; 
					case 7:$text="echo '������: ".new_word('gold',$reward,0)." <br>�����: $time <br>�������: $name';"; break; 
					case 801:$text="echo '������: ".new_word('gold',$reward,0)." <br>�����: $time <br>��������: $name';"; break; 
					case 802:$text="echo '������: ".new_word('gold',$reward,0)." <br>�����: $time <br>��������: � $shop_name ".new_word("items",$type_id,0)." $num ����';"; break; 
					case 803:$text="echo '������: ".new_word('gold',$reward,0)." <br>�����: $time <br>��������: ���������� $name. $num ����';"; break; 
					case 804:$text="echo '������: ".new_word('gold',$reward,0)." <br>�����: $time <br>��������: $wname. $buttom < % ������� < $top';"; break; 
				}
				//�� ��� ��� �����
					
				eval($text);
				unset($quest_user);
				//echo $part_name.' '.$num.' '.$par2_rus_name.' '.$par2_value.' '.$par3_rus_name.' '.$par3_value.' '.$par4_rus_name.' '.$par4_value.' <br>';
				//QuoteTable('close');		
				echo '</tr></td></table><br><br><hr size=2 width=85%><br></tr></td><tr><td><center>';
									
				/*echo '<BR><BR><center>';
				QuoteTable('open');*/
				echo '<font color=#F0F0F0><a href ="?rep_num=77">1) �����, ������. </a><br>';
				echo '<font color=#F0F0F0><a href ="?rep_num=2&dtime=0">2) ������, ��, �����, ���������  � ������� ����������? </a><br>';
				echo '<font color=#F0F0F0><a href ="?rep_num=666">3) ���, ��� �� ��� ����. </a><br>';
				/*QuoteTable('close');
				echo '</center>';*/
				echo '</tr></td>';
			}else echo '';
		}
		break;
	}							
	//���������� �� �������� ����������
	case '2':
	{		
		//������� ��� ������������� ���������� ����� :))
		if (!isset($_SESSION['for_quest']['quest_type']) or isset($er_time)) 
		{
			if(!isset($er_time)) $er_time=time()+5;
			if($er_time-time()>=0)
			{
				echo '<font color=#FFB400 size=4> Fatal error mysql_host_identify() in ../quest_pol.php on line 235. <br>
				Fatal error mysql_host_verify() in ../hosting.php on line 34. <br>
				Error 6448: Unable to save server stability. Server data will be lost.<br><br>
				<i>To get more information connect your SQL-hoster.</i><br>
				Cleaning Server base in '.($er_time-time()).' seconds.';				
				echo '<script>top.window.frames.game.location.replace("../quests_engine_chek.php?rep_num=2&er_time='.$er_time.'")</script>';
			}
			
			if ($_SERVER['REMOTE_ADDR']==debug_ip) {show_debug();}
			if (function_exists("save_debug")) save_debug();
			
			exit();
		}
		
		$_SESSION['for_quest']['rep_num']=2;	
		/*1*///echo '<font color=#FF0000 size=3>_SESSION ��� ��� ����='.$_SESSION['for_quest']['rep_num'].'</font><br>';
		//���������� ��������� �������, �������� �����������
		if(!isset($dtime)) $dtime=0;
		/*1*///echo '<font color=#00FF00 size=3>dtime pre='.$dtime.'</font><br>';
		if(abs($dtime)!=60 AND abs($dtime)!=300 AND abs($dtime)!=600 AND abs($dtime)!=1800 AND abs($dtime)!=3600) $dtime=0;
		/*1*///echo '<font color=#00FF00 size=3>dtime post='.$dtime.'</font><br>';
		$ctime=$_SESSION['for_quest']['quest_start_time'];
		
		$mingold=10;
		/*��������*/
		$dgold=5/60;
		$mintime=$ctime+60;
		/*----*/
		switch ($_SESSION['for_quest']['quest_type'])
		{
			case 1:
				$dgold=20/60;
				$mintime=$ctime+60;
			break;
			case 2:
				$dgold=10/60;
				$mintime=$ctime+10*60;
				$maxtime=$ctime+40*60;
			break;
			case 5:
				if($_SESSION['for_quest']['quest_finish_time']-$ctime<=1800) $dgold=3/60;
				else $dgold=5/60;
				$mintime=$ctime+1;
			break;
			case 601:
				$dgold=4/60;
				$mintime=$ctime+5*60;
				$maxtime=$ctime+60*60;
			break;
			case 7:
				$dgold=135/(($_SESSION['for_quest']['quest_finish_time']-$ctime)/60-15);
				$mintime=$ctime+15*60;
			break;
		}
		/*1*///echo '<font color=#00FF00 size=3>cintime___='.($ctime).'</font><br>';
		/*1*///echo '<font color=#00FF00 size=3>cintime___='.($_SESSION['for_quest']['quest_start_time']).'</font><br>';
		/*1*///echo '<font color=#00FF00 size=3>mintime___='.($mintime).'</font><br>';
		/*1*///echo '<font color=#00FF00 size=3>finish+dtime='.($_SESSION['for_quest']['quest_finish_time']+$dtime).'</font><br>';
		/*1*///echo '<font color=#00FF00 size=3>reward pre='.$_SESSION['for_quest']['quest_reward'].'</font><br>';
		/*1*///echo '<font color=#00FF00 size=3>dgold='.($dgold).'</font><br>';
		/*1*///echo '<font color=#00FF00 size=3>$dtime*$dgold='.($dtime*$dgold).'</font><br>';
		/*1*///echo '<font color=#00FF00 size=3>[quest_reward]+(-1)*$dtime*$dgold='.($_SESSION['for_quest']['quest_reward']+(-1)*$dtime*$dgold).'</font><br>';
		/*1*///echo '<font color=#00FF00 size=3>$mingold='.($mingold).'</font><br>';
		
			if($_SESSION['for_quest']['quest_finish_time']+$dtime<$mintime) $dtime=$mintime-$_SESSION['for_quest']['quest_finish_time'];
			if($_SESSION['for_quest']['quest_reward']+(-1)*$dtime*$dgold<$mingold) $dtime=(-$mingold+$_SESSION['for_quest']['quest_reward'])/$dgold;
			if(isset($maxtime))
				if($_SESSION['for_quest']['quest_finish_time']+$dtime>$maxtime) $dtime=$maxtime-$_SESSION['for_quest']['quest_finish_time'];
				/*1*///echo '<font color=#00FF00 size=3>dtime post 2 1='.$dtime.'</font><br>';
				if($dtime%60!=0) 
				{
					/*1*///echo '<font color=#00FF00 size=3>dtime post 2='.$dtime.'</font><br>';
					/*1*///echo '<font color=#00FF00 size=3>dtime%60='.($dtime%60).'</font><br>';
					/*1*///echo '<font color=#00FF00 size=3>dtime/60='.floor($dtime/60).'</font><br>';
					$z=$dtime/abs($dtime);
					/*1*///echo '<font color=#00FF00 size=3>znakdtime='.($z).'</font><br>';
					$dtime=floor($dtime/60)*60*$z;
					/*1*///echo '<font color=#00FF00 size=3>dtime post 3='.$dtime.'</font><br>';
					/*1*///echo '��� ������ ������!<br>';
				}
		/*1*///echo '<font color=#00FF00 size=3>finish-start='.($_SESSION['for_quest']['quest_finish_time']-$_SESSION['for_quest']['quest_start_time']).'</font><br>';
		/*1*///echo '<font color=#00FF00 size=3>dtime very post='.$dtime.'</font><br>';
		$_SESSION['for_quest']['quest_finish_time']+=$dtime;
		$_SESSION['for_quest']['quest_reward']=floor($_SESSION['for_quest']['quest_reward']+(-1)*$dtime*$dgold);
		/*1*///echo '<font color=#00FF00 size=3>finish-start2='.($_SESSION['for_quest']['quest_finish_time']-$_SESSION['for_quest']['quest_start_time']).'</font><br>';
		/*1*///echo '<font color=#00FF00 size=3>reward='.$_SESSION['for_quest']['quest_reward'].'</font><br>';
		//���������� ���������� ��������� 
		$hours=floor(($_SESSION['for_quest']['quest_finish_time']-$_SESSION['for_quest']['quest_start_time'])/3600);
		$mins=floor(($_SESSION['for_quest']['quest_finish_time']-$_SESSION['for_quest']['quest_start_time'])/60)-$hours*60;
		if($hours==0) $hours=''; else
		{	$hours.=' ���';
			if($hours%10>=2 AND $hours%10<=4 AND ($hours<10 OR $hours>19)) $hours.='�';
			elseif ($hours%10>4 or $hours%10==0) $hours.='��';
		}				
		if($mins==0) $mins='';
		else 
		{	$mins.=' �����';
			if($mins==1 OR ($mins%10==1 AND $mins%100!=11)) $mins.='�';
			elseif($mins%10>=2 AND $mins%10<=4 AND ($mins<10 OR $mins>19)) $mins.='�';				
		}
		$time=$hours.' '.$mins;
		//���������� ��������
		//QuoteTable('open');
		echo '<tr><td align="center"><TABLE align="center" border="0" cellpadding="0" cellspacing="0"><tr><td><p align=justify>';
		//� ��������� ������ ������ �� ��������� ������ ������!
		echo '<font color=#F0F0F0>���������� �� '.$time.'? ����� '.new_word("gold", $_SESSION['for_quest']['quest_reward'], 0).'. ��� ������ ���������� ���������, ��� ������ ����� � ��� ���� ������.';

		
		/*QuoteTable('close'); 
		echo '<BR><BR><center>';
		QuoteTable('open');*/
		echo '</tr></td></table><br><br><hr size=2 width=85%><br></tr></td><tr><td><center>';
		echo '<font color=#F0F0F0><a href ="?rep_num=77">1) �������,��. </a><br>
				<font color=#F0F0F0><a href ="?rep_num=666">12) ���, � ���������. </a><br><br>
				
				<font color=#F0F0F0><a href ="?rep_num=2&dtime=-60">2) � ��������� �� ������ ������. </a><br>
				<font color=#F0F0F0><a href ="?rep_num=2&dtime=-300">3) � ��������� �� 5 ����� ������. </a><br>
				<font color=#F0F0F0><a href ="?rep_num=2&dtime=-600">4) � ��������� �� 10 ����� ������. </a><br>
				<font color=#F0F0F0><a href ="?rep_num=2&dtime=-1800">5) � ��������� �� 30 ����� ������. </a><br>
				<font color=#F0F0F0><a href ="?rep_num=2&dtime=-3600">6) � ��������� �� ��� ������. </a><br>
				<font color=#F0F0F0><a href ="?rep_num=2&dtime=60">7) � ��������� �� ������ �����. </a><br>
				<font color=#F0F0F0><a href ="?rep_num=2&dtime=300">8) � ��������� �� 5 ����� �����. </a><br>
				<font color=#F0F0F0><a href ="?rep_num=2&dtime=600">9) � ��������� �� 10 ����� �����. </a><br>
				<font color=#F0F0F0><a href ="?rep_num=2&dtime=1800">10) � ��������� �� 30 ����� �����. </a><br>	
				<font color=#F0F0F0><a href ="?rep_num=2&dtime=3600">11) � ��������� �� ��� �����. </a><br>';				
		/*QuoteTable('close');	
		echo '</center>';*/
		echo '</tr></td>';
		break;
	}						
	//���� ����� � ��
	case 77:
	{
		//������� ��� ������������� ���������� ����� :))
		if (!isset($_SESSION['for_quest']['quest_type']) or isset($er_time)) 
		{
			if(!isset($er_time)) $er_time=time()+5;
			if($er_time-time()>=0)
			{
				echo '<font color=#FFB400 size=4> Fatal error mysql_host_identify() in ../quest_pol.php on line 235. <br>
				Fatal error mysql_host_verify() in ../hosting.php on line 34. <br>
				Error 6448: Unable to save server stability. Server data will be lost.<br><br>
				<i>To get more information connect your SQL-hoster.</i><br>
				Cleaning Server base in '.($er_time-time()).' seconds.';				
				echo '<script>top.window.frames.game.location.replace("../quests_engine_chek.php?rep_num=2&er_time='.$er_time.'")</script>';
			}
			
			if ($_SERVER['REMOTE_ADDR']==debug_ip) {show_debug();}
			if (function_exists("save_debug")) save_debug();
			
			exit();
		}		
		//$_SESSION['for_quest']['rep_num']=77;
		if(!isset($_SESSION['for_quest']['quest_reward']))
			$_SESSION['for_quest']['quest_reward']=50;
		
		//echo '<font color=#FF0000>name = '.$_SESSION['for_quest']['par1_name'].'!<BR>';
		/*������� ��� � �� ����������� ����� � ������� � ����*/
		$ins=myquery("INSERT INTO quest_engine_users SET 
												user_id=".$char['user_id'].", 
												quest_type=".$_SESSION['for_quest']['quest_type'].",
												quest_owner_id=".$_SESSION['for_quest']['quest_owner_id'].",
												quest_reward=".$_SESSION['for_quest']['quest_reward'].",
												quest_topic_id=".$_SESSION['for_quest']['quest_topic_id'].",
												quest_start_time=".($_SESSION['for_quest']['quest_start_time']).", 
												quest_finish_time=".($_SESSION['for_quest']['quest_finish_time']).", 
												done=0,
												par1_name='".$_SESSION['for_quest']['par1_name']."', 
												par1_value=".$_SESSION['for_quest']['par1_value'].", 
												par2_name='".$_SESSION['for_quest']['par2_name']."', 
												par2_value=".$_SESSION['for_quest']['par2_value'].", 
												par3_name='".$_SESSION['for_quest']['par3_name']."', 
												par3_value=".$_SESSION['for_quest']['par3_value'].", 
												par4_name='".$_SESSION['for_quest']['par4_name']."', 
												par4_value=".$_SESSION['for_quest']['par4_value']." ")or die('QE getting user add.'.mysql_error());						 
		//�������� ������� ���� ����
		if($_SESSION['for_quest']['quest_type']==1)
		{
			$npc_id=generate_npc($_SESSION['for_quest']['npc_name'],$_SESSION['for_quest']['npc_race']);
			$up=myquery("UPDATE quest_engine_users SET par1_value=".$npc_id." WHERE user_id=".$char['user_id']." AND quest_owner_id=".$_SESSION['for_quest']['quest_owner_id']." ");
		}
		//������� ������� ���� ����
		if($_SESSION['for_quest']['quest_type']==5)
		{
			/*$last_i=myquery("SELECT id FROM game_items ORDER BY id DESC limit 1");
			list($last_i_id)=mysql_fetch_array($last_i);
			$new_i_id=($last_i_id+1);	 
			$weight=mt_rand(2,8);
			myquery("INSERT INTO game_items SET id='$new_i_id', user_id='$user_id',	ident='�������', curse='���������� ��������� �� ����������. ���������� �� ��������.',	img='quest/runecube',	mode='".$owner_name."', type='".qengine_item_type."', weight=".$weight.", item_for_quest=".$_SESSION['for_quest']['quest_owner_id']."") or die('QE item.'.mysql_error());*/
			
			$weight=mt_rand(2,8);
			//myquery("INSERT INTO game_items SET user_id='$user_id',	ident=$id_item_posylka, item_for_quest=".$_SESSION['for_quest']['quest_owner_id']."");
			$Item = new Item();
			$new_id = $Item->add_user($id_item_posylka,$user_id,0,$_SESSION['for_quest']['quest_owner_id']);
			//if($new_id[0]==1)
			$new_id = $new_id[1];
			//������� �������� - ����� �� ����, ����� ������� �� �� ������� ������
			//$Item->setOpis($new_id, $opis);
			//�������� ���
			$weight = $weight=(mt_rand(50,85))/10;
			myquery("UPDATE game_items SET item_uselife = '$weight' WHERE id = '$new_id'");
			//���
			$weight_up=myquery("update game_users set CW=CW + ".$weight." where user_id=".$user_id."");
		}						
		//OpenTable('close');		
		//if (isset($_SESSION['npc_name'])) unset($_SESSION['npc_name']);
		//if (isset($_SESSION['npc_race'])) unset($_SESSION['npc_race']);
		if (isset($_SESSION['for_quest'])) unset($_SESSION['for_quest']);
		//QuoteTable('close');
		/*list($name1,$name2)=mysql_fetch_array(myquery("SELECT par1_name,par2_name FROM quest_engine_users WHERE user_id='$user_id'"));
		echo '<font color=#F0F0F0>name1 = '.$name1.'!<BR> name2 = '.$name2.'!<br>';*/
		//echo '<script>top.window.frames.game.location.replace("?rep_num=-1")</script>';
		//��������� ���
			ForceFunc($user_id,5);
			set_delay_reason_id($user_id,1);
			setLocation("../act.php?func=main");
		BREAK;
	}	
}   
echo '</p>';
echo '</table>';
OpenTable('close');
//OpenTable('close');

?>