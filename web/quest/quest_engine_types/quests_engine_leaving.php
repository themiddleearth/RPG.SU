<?PHP
function the_text($type,$quest_user,$owner_id)
{
	global $char;
	include("inc\standart_vars.inc.php");
	//echo 'reward='.$reward.'<br>';
	$qt=$quest_user['quest_type'];
	$top_id=$quest_user['quest_topic_id'];
	//QuoteTable('open');
	echo '<tr><td align="justify">';
	$text=myquery("SELECT text FROM quest_engine_topics WHERE topic_id=".$top_id." AND owner_id=".$owner_id." AND action_type=".$type." AND quest_type=".$qt."");
	if(mysql_num_rows($text)>0)
	{
		$all = mysql_num_rows($text);
		$r = mt_rand(0,$all-1);
		mysql_data_seek($text,$r);	
		list($text)=mysql_fetch_array($text);
	}
	else 
		$text = "echo '����� C���� �������, ��� ".$type.". � �� �� �������.';";	
	//echo '<font color=#F0F0F0>'.$text.'';
	eval($text);
	//QuoteTable('close');
	echo '<br><br><hr size=2 width=85%><br></tr></td><tr><td><center>';
}

function well_done()
{
	global $quest_user, $char, $owner_id;
	//����� ����� �����
	//echo '<font color=green><br>��� ����� = '.($quest_user['quest_reward']*money_weight).'<BR>';
	if(isset($quest_user['quest_reward_plus']))
		$quest_user['quest_reward']+=$quest_user['quest_reward_plus'];
	
	myquery("UPDATE game_users SET GP=GP+'".$quest_user['quest_reward']."',CW=CW+'".($quest_user['quest_reward']*money_weight)."' WHERE user_id=".$char['user_id']."");		
	setGP($char['user_id'],$quest_user['quest_reward'],81);
	//�������� ������� ����������� �������
	$up=myquery("UPDATE quest_engine_stats SET quest_done=quest_done+1 WHERE user_id=".$char['user_id']."");		
	//���������
	
	the_text(21,$quest_user,$owner_id);
	///*echo '<BR><BR>';
	//QuoteTable('open');		
	echo '<font color=#F0F0F0><a href ="?done=1">1) ������������������������������������! </a><br>';
	//QuoteTable('close');	
	echo '</tr></td>';
	//������ ������ � ������
	myquery("DELETE FROM quest_engine_users WHERE user_id=".$char['user_id']." AND quest_owner_id=".$owner_id." ");
}

function still_have_time()
{
	global $quest_user, $owner_id;
	the_text(24,$quest_user,$owner_id);
	//echo '<BR><BR>';
	//QuoteTable('open');		
	echo '<font color=#F0F0F0><a href ="?done=1">1) ����� ���... </a><br>';
	//QuoteTable('close');
	echo '</tr></td>';
}

function not_in_time()
{
	global $quest_user,  $char, $owner_id;
	$quest_user['quest_reward']=ceil($quest_user['quest_reward']*0.1);
	//����� ����� ����-����
	if(isset($quest_user['quest_reward_plus']))
		$quest_user['quest_reward']=+$quest_user['quest_reward_plus'];
	myquery("UPDATE game_users SET GP=GP+'".$quest_user['quest_reward']."',CW=CW+'".($quest_user['quest_reward']*money_weight)."' WHERE user_id=".$char['user_id']."");				
	setGP($char['user_id'],$quest_user['quest_reward'],81);
	
	the_text(22,$quest_user,$owner_id);
	
	//echo '<BR><BR>';
	//QuoteTable('open');		
	echo '<font color=#F0F0F0><a href ="?done=1">1) ��-��-��. </a><br>';
	//QuoteTable('close');	
	echo '</tr></td>';
	//������ ������ � ������
	myquery("DELETE FROM quest_engine_users WHERE user_id=".$char['user_id']." AND quest_owner_id=".$owner_id." ");
}

function fail()
{
	global $quest_user, $char, $owner_id;
	
	the_text(23,$quest_user,$owner_id);
	
	///*echo '<BR><BR>';
	//QuoteTable('open');		
	echo '<font color=#F0F0F0><a href ="?done=1">1) ���� �� ���. </a><br>';
	//QuoteTable('close');	
	echo '</tr></td>';
	//������ ������ � ������
	myquery("DELETE FROM quest_engine_users WHERE user_id=".$char['user_id']." AND quest_owner_id=".$owner_id." ");
}



/*OpenTable('title');
$owner_name=mysql_result(myquery("SELECT name FROM quest_engine_owners WHERE id=".$owner_id.""),0,0);
echo '<center><font size=4 color=#fce66b><br>'.$owner_name.'</center>';
echo '<hr align=center size=2 width=80%>';
echo '<p align=justify>';
?><TABLE align="center" border="0" cellpadding="3" cellspacing="3" width="70%"><?*/

//��� ��� ��������� - �������, �������� �� ���� ����� � ������� ��� �������������� �����			
$quest_user=mysql_fetch_array(myquery("SELECT * FROM quest_engine_users WHERE user_id=".$char['user_id']." AND quest_owner_id=".$owner_id." ORDER BY quest_finish_time DESC limit 1"));
//�������� �� �����	
if($quest_user['quest_finish_time']-time()>=0)
	$check_time=1;
else 
	$check_time=0;	
	
	//�������� �� �����
	switch ($quest_user['quest_type'])
	{
		case 1:
		{
			//��������
			$check1=1;
			$check=myquery("SELECT id FROM game_items WHERE item_id='$id_item_monstr_balden' AND item_for_quest='$owner_id' AND user_id='$user_id' ");// /*or die(mysql_error())*/;	
			//���������, ������� ����� �� ����� ��� ���� � �����. (������ ���� 1 �����)
			if(mysql_num_rows($check)>0)
				$ncheck=mysql_num_rows($check);
			else 
				$ncheck=0;
			
			//������� �����y - ��� �� ��-������ - ���� ������ ������ ����, �� ���� ��������, ���� ���� ������ ������ - �� ����
			//���� ��� ������ �������� � ��������� ���
			$weight = 0;
			if(mysql_num_rows($check)>0)
			{
				while (list($head_id) = mysql_fetch_array($check))
				{
					myquery("DELETE FROM game_items_opis WHERE item_id = '$head_id'");
					$weight += mysql_result(myquery("SELECT item_uselife FROM game_items WHERE item_for_quest='$owner_id' AND user_id='$user_id' AND item_id=$id_item_monstr_balden"),0,0);
				}
				
				myquery("DELETE FROM game_items WHERE item_for_quest='$owner_id' AND user_id='$user_id' AND item_id=$id_item_monstr_balden");
				//� ����� ������ ��� �����, � ���� �� ������� ++ ��
				myquery("UPDATE game_users SET CW = CW-'$weight' WHERE user_id = ".$user_id."");
			}
			
			//�������� �� ����������
			//���� ��������� ������ ����
			if($ncheck!=0 AND $quest_user['done']==1)
			{									
				//�������� �����
				if($check_time==0)
				{
					not_in_time();					
				}
				else 
				{
					well_done();						
				}				
			}	
			elseif ($ncheck!=0 AND $quest_user['done']==0)
			{
				$check1=0;
			}
			//���� ��������� ������ ��� ��� ������� �������� �� ���� ���������
			if ($ncheck==0 or $check1==0)
			{
				//�������� �����
				if($check_time==0)
				{
					fail();					
					//������ ���� �� ����
					myquery("DELETE FROM game_npc WHERE npc_quest_engine_id=".$char['user_id']." ");
				}
				else 
				{					
					still_have_time();					
				}
			}
			break;		
		}
/**//**/
	case 2:
	{
		//$items=myquery("SELECT curse FROM game_items WHERE user_id='$user_id' AND item_for_quest='$owner_id'AND ident='����� �������' AND type=".qengine_item_type."");
		//$Item = new Item();
		//$find = $Item->find_item($id_item_part_monster);
		//if ($find==0) break;
		//$item_curse = $Item->getOpis();
	
		$items=myquery("SELECT * FROM game_items WHERE user_id='$user_id' AND item_for_quest='$owner_id'AND item_id='$id_item_part_monster' AND priznak=0");
		
		/*$check_items = 0;
		if(strpos($item_curse,$quest_user['par1_name'])===false) {} else {$check_items=1; break;}
		if($check_items==1)
		{
			$pos=strpos($item_curse,':');
			$n=$item_curse[$pos+2];
			if(is_numeric($item_curse[$pos+3])) $n.=$item_curse[$pos+3];
			$n=(int)$n;
		}*/
		
		if(mysql_num_rows($items)==0) $check_items=0;
		else 
		{
			$check_items=1;
			$items=mysql_fetch_array($items);
			$n=$items['item_cost'];
			$part_id = $items['id'];
			//$weight = $items['item_uselife'];
		}
		//esli 4ast tela est'
		if($check_items==1)
		{
			//esli vse vypolneno i vovremia
			if($n>=$quest_user['par1_value'] AND $quest_user['done']==1 AND $check_time==1)
			{
				well_done();
				//������� �����y �����
				$Item = new Item($part_id);
				$Item->admindelete();
				myquery("DELETE FROM game_items_opis WHERE item_id = '$part_id'");
				//� ����� ���
				//myquery("UPDATE game_users SET CW = CW-'$weight' WHERE user_id = ".$user_id."");
			}
			//esli opozdal
			elseif($n>=$quest_user['par1_value'] AND $quest_user['done']==1 AND $check_time==0)
			{
				not_in_time();
				//������� �����y �����
				$Item = new Item($part_id);
				$Item->admindelete();
				myquery("DELETE FROM game_items_opis WHERE item_id = '$part_id'");
				//� ����� ���
				//myquery("UPDATE game_users SET CW = CW-'$weight' WHERE user_id = ".$user_id."");
			}
			//esli vremai est, no ne vse gotovo
			elseif($check_time==1)
			{
				still_have_time();
			}
			elseif($check_time==0)
			{
				fail();
			}
		}
		else
		{
			if($check_time==0)
			{
				fail();
				//������� �����y �����
				$Item = new Item($part_id);
				$Item->admindelete();
				myquery("DELETE FROM game_items_opis WHERE item_id = '$part_id'");
				//� ����� ���
				//myquery("UPDATE game_users SET CW = CW-'$weight' WHERE user_id = ".$user_id."");
			}
			else
			{
				still_have_time();
			}
		}
		break;
	}
	/*case 3:
	{
		$check_exp=0;
		echo 'NEED = '.$quest_user['par1_value'].'<BR> IS = '.$quest_user['par2_value'].'<BR>';
		if($quest_user['par2_value']>=$quest_user['par1_value']) $check_exp=1;
		if($check_time AND $check_exp)
		{
			well_done();
		}
		elseif ($check_time AND !$check_exp)
		{
			still_have_time();
		}
		elseif (!$check_time AND $check_exp)
		{
			not_in_time();
		}
		else
		{
			fail();
		}
		break;
	}
	case 4:
	{
		$check_wins=0;
		if($char['win']>=$quest_user['par1_value']) $check_wins=1;
		if($check_time AND $check_wins)
		{
			well_done();
		}
		elseif ($check_time AND !$check_wins)
		{
			still_have_time();
		}
		elseif (!$check_time AND $check_wins)
		{
			not_in_time();
		}
		else
		{
			fail();
		}
		break;
	}*/
	case 5:
	{
		$check_done=$quest_user['done'];
		/*-------------------*/
		/*function delete_letter($owner_id,$user_id)
				{
					$weight=myquery("SELECT weight FROM game_items WHERE user_id='$user_id' AND item_for_quest='$owner_id' AND ident='����� �������' AND type='".qengine_item_type."'");
						if(mysql_num_rows($weight)>0)
							list($weight)=mysql_fetch_array($weight);
						else $weight=0;
						$weight_up=myquery("update game_users set CW=CW - ".$weight." where user_id=".$user_id."");
						myquery("DELETE FROM  game_items WHERE ident='������ � ��������������' AND type='".qengine_item_type."' AND item_for_quest='$owner_id' AND user_id='$user_id' ") or die(mysql_error());
				}*/
		//--------------------------------------//
		$check_done=$quest_user['done'];
		$items=myquery("SELECT id FROM game_items WHERE item_id='$id_item_letter_complete' AND item_for_quest='$owner_id' AND user_id='$user_id' AND priznak=0") or die(mysql_error());	
		$item_id=mysql_result($items,0,0);
		$Item=new Item($item_id);
		if($check_done>=1) 
		{
			if(mysql_num_rows($items)>0)
			{
				$check_done=1;
				//delete_letter($owner_id,$user_id);
				$Item->admindelete();
			}
			else $check_done=0;
		}
		if($check_time AND $check_done)
		{
			//delete_box($owner_id,$user_id);
			well_done();
		}
		elseif ($check_time AND !$check_done)
		{
			still_have_time();		
		}
		elseif (!$check_time AND $check_done)
		{
			//delete_box($owner_id,$user_id);
			not_in_time();
		}
		else
		{
			//delete_box($owner_id,$user_id);
			//delete_letter($owner_id,$user_id);
			$Item->admindelete();
			fail();
		}
		break;
	}
	case 601:
	{
		if($quest_user['done']==1) $done=1;
		if($quest_user['done']==0 OR $quest_user['done']==2 OR $quest_user['done']==3) $done=0;
		if($check_time AND $done)
		{
			well_done();
		}
		elseif ($check_time AND !$done)
		{
			still_have_time();
		}
		elseif (!$check_time AND $done)
		{
			not_in_time();
		}
		else
		{
			fail();
		}
		break;
	}
	case 7:
	{
		$Item = new Item();
		$find = $Item->find_item($quest_user['par1_value']);
		if ($find==0) $done=0; else $done=1;
		if ($find!=0) $used=$Item->getItem('used'); else $used=0;
		if($used!=0)
		{
			echo '<tr><td align="center">';
			//QuoteTable('open');
			echo '<font color=#F0F0F0>������� ������� �������, ����� � ��� ��� ���������.';
			//QuoteTable('close');
			//echo '<BR><BR><BR>';
			//QuoteTable('open');
			/*cho '</tr></td></table><br><br><hr size=2 width=85%><br></tr></td><tr><td><center>';*/
			echo '<br><br><hr size=2 width=85%><br></tr></td><tr><td><center>';					
			/*echo '<BR><BR><center>';
			QuoteTable('open');*/
			echo '<font color=#F0F0F0><a href ="?done=1">1) ��� ������</a><br>';
			/*QuoteTable('close');
			echo '</center>';*/
			echo '</tr></td>';
			
			//QuoteTable('close');
		}
		elseif($check_time AND $done)
		{
			well_done();
		}
		elseif ($check_time AND !$done)
		{
			still_have_time();
		}
		elseif (!$check_time AND $done)
		{
			not_in_time();
		}
		else
		{
			fail();
		}
		break;
	}
	case 801:
	{
		//�������� � ������� ������� ���� ��������!
		
		$k=0;$zakaz=0;
		$bringed=array();
		for($i=1;$i<5;$i++)
			if($quest_user['par'.$i.'_value']!=0)
			{
				$zakaz++;
				$ind='par';
				$ind.=$i; $ind.='_value';
				$Item = new Item();
				$find = $Item->find_item($quest_user["$ind"],1);
				if ($find!=0)
				{
					$k++;
					$bringed[count($bringed)]=$i;
				}
			}
		if($k>0) $done=1; else $done=0;
		///*echo '<font color=green>��������� = '.$k.' ����<br>';
		//echo '<font color=green>Iterations = '.$zakaz.' ����<br>';*/
		//exit();
		if($check_time AND $done)
		{
			$del=$bringed[array_rand($bringed)];
			$ind='par';
			$ind.=$del; $ind.='_value';
			$Item = new Item();
			$find = $Item->find_item($quest_user["$ind"],1);
			if ($find!=0)
			{
				$quest_user['quest_reward_plus']=$Item->getFact('item_cost');
				$Item->admindelete();
				echo '<tr><td align="center">';
				//QuoteTable('open');
				if($k>1)
					echo '<font color=#F0F0F0>*��� �������� ����� ������� �� ����������� ���� �������� �, �������, �����, ��� '.$Item->getFact('name').' ����� ��� ���� ��������� �������*';
				elseif($zakaz>1)
					echo '<font color=#F0F0F0>*��� �������� �������� �������, ��� '.$Item->getFact('name').' - ������������, ��� �� ��������, �� ������ � ���� �� ����*';
				else
					echo '<font color=#F0F0F0>*���� �� ��������� ������ ���������, '.$Item->getFact('name').' - ��� ��� ��, ��� ��� ���� �����*';
				//QuoteTable('close');
				echo '</tr></td>';

				well_done();
			}
		}
		elseif ($check_time AND !$done)
		{
			still_have_time();
		}
		elseif (!$check_time AND $done)
		{
			$del=$bringed[array_rand($bringed)];
			$ind='par';
			$ind.=$del; $ind.='_value';
			$Item = new Item();
			$find = $Item->find_item($quest_user["$ind"],1);
			if ($find!=0)
			{
				$quest_user['quest_reward_plus']=$Item->getFact('item_cost');
				$Item->admindelete();
				 echo '<tr><td align="center">';
				//QuoteTable('open');
				if($k>1)
					echo '<font color=#F0F0F0>*��� �������� ����� ������� �� ����������� ���� �������� �, �������, �����, ���, ���� �� � �� ������ �������, '.$Item->getFact('name').' ����� ��� ���� ��������� �������*';
				elseif($zakaz>1)
					echo '<font color=#F0F0F0>*��� �������� �������� �������, ��� '.$Item->getFact('name').' - ������������, ��� �� ��������, �� � ��� � �� ��������� � ����������� ��� �����, �� ������ � ���� �� ����*';
				else
					echo '<font color=#F0F0F0>*���� �� ��������� ���� ���������, '.$Item->getFact('name').', �������� �� ��, ��� �� ��������, ������ ��� ��������� ��� ������*';
				   echo '</tr></td>';
				not_in_time();
			}
		}
		else
		{
			fail();
		}

		break;
	}
	case 802:case 803:
	{
		if($quest_user['quest_type']==802)
		{
			$bringed=myquery("SELECT game_items.id AS id, game_items_factsheet.name AS ident FROM game_items,game_items_factsheet WHERE game_items.item_id=game_items_factsheet.id AND game_items.shop_from=".$quest_user['par1_value']." AND game_items_factsheet.type=".$quest_user['par2_value']." AND game_items.user_id='$user_id' AND game_items.used=0 ORDER BY game_items_factsheet.name");
			//echo 'bringed = '.(mysql_num_rows($bringed)).'<br>';
		}
		elseif ($quest_user['quest_type']==803)
			if($quest_user['par2_value']>0)
				$bringed=myquery("SELECT game_items.id AS id, game_items_factsheet.name AS ident FROM game_items,game_items_factsheet WHERE game_items.item_id=game_items_factsheet.id AND game_items.user_id='$user_id' AND game_items.used=0 AND game_items_factsheet.".$quest_user['par1_name'].">=".$quest_user['par1_value']." AND game_items_factsheet.".$quest_user['par2_name'].">=".$quest_user['par2_value']."");
			else 
				$bringed=myquery("SELECT game_items.id AS id, game_items_factsheet.name AS ident FROM game_items,game_items_factsheet WHERE game_items.item_id=game_items_factsheet.id AND game_items.user_id='$user_id' AND game_items.used=0 AND game_items_factsheet.".$quest_user['par1_name'].">=".$quest_user['par1_value']."");
				
		$num=mysql_num_rows($bringed);
		//echo 'num = '.($num).'<br>';
		if($num>=$quest_user['par3_value'])
		{
			if(isset($checked))
			{
				$ids=array();
				for($i=1;$i<=$n;$i++)
				{
					$buf='ids_'.$i.'';
					if(isset($$buf))
						$ids[count($ids)]=$$buf;
					//echo $i.') id= '.$$buf.'<br>';
				}
			}
			if(!isset($checked) OR (isset($checked) AND count($ids)<$quest_user['par3_value']))
			{
				
				//QuoteTable('open');
				echo '<tr><td align="justify">';
				if(!isset($checked))
					echo '<font color=#F0F0F0>*�������� ��������, ������� ����������� ������. ��� ����� ������ '.$quest_user['par3_value'].' ����.*<br>';
				else 
					echo '<font color=#F0F0F0>*�� ������� ������� ���� ���������! ����� '.$quest_user['par3_value'].' ����.*<br>';
				echo '<FORM method="POST" id="mf" action="?checked=1"> <UL>';
				$i=0;
				$k=mysql_num_rows($bringed);
				echo '<INPUT name="n" type="hidden" value='.$k.'>';
				while (($items=mysql_fetch_array($bringed)))
				{
					$i++;							
					echo '<LI><INPUT name="ids_'.$i.'" type="checkbox" value='.$items['id'].' > '.$items['ident'].' ';
				}
				/*QuoteTable('close'); 
				echo '<BR><BR>';
				QuoteTable('open');		*/
				echo '<br><br><hr size=2 width=85%><br></tr></td><tr><td><center>';
				echo '<INPUT name="ik'.$i.'" type="submit" value="�������">
				<INPUT name="no" type="button" value="����������" onClick=location.href="?done">';
				/*echo '<a href ="?checked=1&owner_id='.$owner_id.'&action_type='.$action_type.'">&#9678; ��� ���� � ����! *������ ���������*</a><br>';*/
				//QuoteTable('close');
				echo '</tr></td>';
				echo '</UL></FORM>';
				/*echo '</p>';
				OpenTable('close');
				exit();*/
			}
			else
			{
				//��������, ��� ��� ��������� �������� �� ��� ��������� � ������� ������
				$done=1;
				for($i=0; $i<count($ids);$i++)
				{
					if(mysql_num_rows(myquery("SELECT id FROM game_items WHERE user_id='$user_id' AND id=".$ids[$i].""))<1)
					{
						$done=0;
						break;
					}
				}
				//$done=0;
				//exit();
			}
		}else $done=0;	
				
		if(isset($done))		
		if($check_time AND $done)
		{
			//while ($item=mysql_fetch_array($bringed))
			//$Item = new Item();
			$quest_user['quest_reward_plus']=0;
			for($i=0; $i<count($ids);$i++)
			{
				//$Item->getItem($ids[$i]);
				$Item = new Item($ids[$i]);
				if($i<$quest_user['par3_value'])
					$quest_user['quest_reward_plus']+=$Item->getFact('item_cost');
				$Item->admindelete();
				//list($w)=mysql_fetch_array(myquery("SELECT weight FROM game_items WHERE id=".$ids[$i].""));
				//myquery("DELETE FROM game_items WHERE user_id='$user_id' AND id=".$ids[$i]." AND used=''");
				//echo '<font color=green> ��� = '.$w.' <br>';
				//myquery("UPDATE game_users SET CW=CW-".$w." where user_id='$user_id'") or die(mysql_error());
				//myquery("UPDATE game_users SET CW=(CW-(".$item['weight']."*".$quest_user['par3_value'].")) where user_id='$user_id'") or die(mysql_error());
			}
			
			well_done();
		}
		elseif ($check_time AND !$done)
		{
			still_have_time();		
		}
		elseif (!$check_time AND $done)
		{
			//while ($item=mysql_fetch_array($bringed))
			//$Item = new Item();
			$quest_user['quest_reward_plus']=0;
			for($i=0; $i<count($ids);$i++)
			{
				//$Item->getItem($ids[$i]);
				$Item = new Item($ids[$i]);
				if($i<$quest_user['par3_value'])
					$quest_user['quest_reward_plus']+=$Item->getFact('item_cost');
				$Item->admindelete();
				//list($w)=mysql_fetch_array(myquery("SELECT weight FROM game_items WHERE id=".$ids[$i].""));
				//myquery("DELETE FROM game_items WHERE user_id='$user_id' AND id=".$ids[$i]." AND used=''");
				//echo '<font color=green> ��� = '.$w.' <br>';
				//myquery("UPDATE game_users SET CW=CW-".$w." where user_id='$user_id'") or die(mysql_error());
				//myquery("UPDATE game_users SET CW=(CW-(".$item['weight']."*".$quest_user['par3_value'].")) where user_id='$user_id'") or die(mysql_error());
			}
			not_in_time();
		}
		else
		{
			fail();
		}	
		break;
	}
	case 804:
	{
		$done = 0;
		//$Item = new Item();
		//$find = $Item->find_item($quest_user["par1_value"],1);
		//if ($find!=0)
		//{
		//    if (($Item->getItem('item_uselife')<=$quest_user['par2_value'])AND($Item->getItem('item_uselife')>=$quest_user['par3_value']))
		$bringed=myquery("SELECT id FROM game_items WHERE user_id='$user_id' AND used=0 AND item_id=".$quest_user["par1_value"]." AND item_uselife<=".$quest_user['par2_value']." AND item_uselife>=".$quest_user['par3_value']." LIMIT 1");
		if(mysql_num_rows($bringed)>0)
		{
			$id=mysql_result($bringed,0,0);
			$Item = new Item($id);
			$quest_user['quest_reward_plus']=$Item->getFact('item_cost');
			$done = 1;
		 }
		
		if($check_time AND $done)
		{
			$Item->admindelete();
			well_done();
		}
		elseif ($check_time AND !$done)
		{
			still_have_time();
		}
		elseif (!$check_time AND $done)
		{
			$Item->admindelete();
			not_in_time();
		}
		else
		{
			fail();
		}
		break;
	}
}
/*echo '</p>';
OpenTable('close');*/
echo '</p>';
echo '</table>';
OpenTable('close');


/*if ($_SERVER['REMOTE_ADDR']==debug_ip)
{
	show_debug();
}

if (function_exists("save_debug")) save_debug();*/

//exit();
?>