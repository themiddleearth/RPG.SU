<?PHP		

	echo '<align=left>';
	$npc=mysql_fetch_array(myquery("SELECT * FROM quest_engine_owners WHERE id=".$quest_user['quest_owner_id'].""));
	$npc_map=mysql_result(myquery("SELECT name FROM game_maps WHERE id=".$npc['map_name'].""),0,0);
	$ot_kogo=''.$npc['name'].' ('.$npc_map.' '.$npc['map_xpos'].','.$npc['map_ypos'].')';
	
	if($quest_user['quest_finish_time']-time()>0)
		$time=1;
	else $time=0;	
	switch ($quest_user['quest_type'])
	{		
		case 1:
		{			
			get_stat($time,$quest_user['done'],$status,$color);			
			
			$help='������ ������� ������� &quot;����������&quot; ����� ����� ��� �������� ��������������� ����. ���� ����� �� ���������, � ����� � ������ ������ ���������, �������� �� ����, ����������, ������ Inquisitor_I.';
			break;
		}
		case 2:
		{			
			get_stat($time,$quest_user['done'],$status,$color);			
			if($quest_user['par3_name']=='') $quest_user['par3_value']='';
			if($quest_user['par4_name']=='') $quest_user['par4_value']='';
			/*$a=parameter_rus_name($quest_user['par2_name']);
			$b=parameter_rus_name($quest_user['par3_name']);
			$c=parameter_rus_name($quest_user['par4_name']);*/
			
			//$zd="������: ".new_word("gold",$reward,0)." �����: ".$time." ���:".$npc_name." ".$npc_race."";
			$help='������ ����� ���� ������� ������� &quot;����������&quot; ����� ����� ��� �������� ��������������� ����. ���������� ������ ����������� � �������� ���������������� �������� (�.�. ���� 10 ��������, �� ������ ����� � ��������� �� 10 �� �����, � ���� ������ � �������� &quot;10 ����&quot;. ���� ����� �� ���������, � ����� � ������ ������ ���������, �������� �� ����, ����������, ������ Inquisitor_I.';
			break;
		}
		/*case 3:
		{						
			if($quest_user['par2_value']>=$quest_user['par1_value']) $done=1; else $done=0;
			get_stat($time,$done,$status,$color);			
			
			if($quest_user['par1_value']%10==0 OR $quest_user['par1_value']%10>=5)							
				$exp='�����';
			elseif($quest_user['par1_value']%10==1) $exp='����';
			else $exp='����';	
			
			//$zadanie='���, ��� ��� ����� ������� - ��� ���������� '.$quest_user['par1_value'].') '.$exp.' ����� � ��������� � ��������� �� '.strftime("%e-%m-%Y%t%T",$quest_user['quest_finish_time']).' �� ��������� ��������! �� �����������!';
			$help='� ������� ������� �� �� ���������� �����, ������� �� ������ ����� �� ������ ����������� � ���������, � ������ �� ����������, ��� �� ������ ���������� �� ���������� ���������� �������. � ������ �����-���� ���������, �������� �� ����, ����������, ������ Inquisitor_I.';
			break;
		}
		case 4:
		{						
			if($char['win']>=$quest_user['par1_value']) $done=1; else $done=0;
			get_stat($time,$done,$status,$color);			
			
			if($quest_user['par1_value']%10==0 OR $quest_user['par1_value']%10>=5)							
				$pobed='�����';
			elseif($quest_user['par1_value']%10==1) $pobed='������';
			else $pobed='������';			
			
			//$zadanie='����� ��������� ��� �������, ��� ���� ������� '.$quest_user['par1_value'].') '.$pobed.' � ��������� � ��������� �� '.strftime("%e-%m-%Y%t%T",$quest_user['quest_finish_time']).' �� ��������� ��������! ������, �� ���� �����!';
			$help='� ������� ������� �� ���������� �����, ������� �� ������ ����� �� ������ ����������� � ���������. � ������ �����-���� ���������, �������� �� ����, ����������, ������ Inquisitor_I.';
			break;
		}*/
		case 5:
		{			
			if($quest_user['done']==1) {$done=1; $time=1;}
			if($quest_user['done']==2) {$done=1; $time=0;}
			if($quest_user['done']==0) {$done=0;}
			get_stat($time,$done,$status,$color);	
			//list($rustowun)=mysql_fetch_array(myquery("SELECT rustown FROM game_gorod WHERE town=".$quest_user['par2_value'].""));
			//list($map_name)=mysql_fetch_array(myquery("SELECT name FROM game_maps WHERE id=".$quest_user['par1_value'].""));	
			//if(substr_count($map_name,'��e'))	$map_name.='e';
			//$zadanie=$npc['name'].' �������� ��� ��������� ����� �������, ������������ �� ��������, � ����� '.$rustowun.', ����������� � '.$map_name.'. ��� ��� ���� ����� ���������� � ������� � ������ ��������� - �� ������ ���������, ��� ������ ��� ���, ������� ������� � ������ ��� ������, ������� ����� ����������������� � ���������� �������. ������� � ��� �� ��� - �� '.strftime("%e-%m-%Y%t%T",$time).', ����� ������ ������� ��� �� ������.';
			$help='� ������� ������� �����, �� ������� �� ������ ��������� �� ����� ����������, � �� ����� ����������� � ���������. � ������ �����-���� ���������, �������� �� ����, ����������, ������ Inquisitor_I.';
			break;
		}
		case 601:
		{
			if($quest_user['done']==1) $done=1;
			if($quest_user['done']==2 OR $quest_user['done']==0 OR $quest_user['done']==3) $done=0;			
			if($quest_user['done']==2) $time=0;
			get_stat($time,$done,$status,$color);				
			list($map_name)=mysql_fetch_array(myquery("SELECT name FROM game_maps WHERE id=".$quest_user['par1_value'].""));			
			if(substr_count($map_name,'��e'))	$map_name.='e';
			list($rustowun)=mysql_fetch_array(myquery("SELECT rustown FROM game_gorod WHERE town=".$quest_user['par2_value'].""));
			//$zadanie=$npc['name'].' �������� ��� ��������� ����� �������, ������������ �� ��������, � ����� '.$rustowun.', ����������� � '.$map_name.'. ��� ��� ���� ����� ���������� � ������� � ������ ��������� - �� ������ ���������, ��� ������ ��� ���, ������� ������� � ������ ��� ������, ������� ����� ����������������� � ���������� �������. ������� � ��� �� ��� - �� '.strftime("%e-%m-%Y%t%T",$time).', ����� ������ ������� ��� �� ������.';
			$help='������� ����� ���� ������, �� ����) (�������� ���: ���� ���� 9�9, ��������� �� 9 ��������� 3�3. ��� ����� �� 1 �� 9 (� ��� �����). ���� ������ ������ (������).���� �������� � ������ ������ ����� (�����) ���, ����� �� ���� ���������� ����: �� ������������ � ���������� ���� � � ��������� 3�3. � ������ �����-���� ���������, �������� �� ����, ����������, ������ Inquisitor_I.';
			break;
		}
		case 7:
		{
			//$bringed=myquery("SELECT * FROM game_items WHERE ident IN (SELECT name FROM game_items_factsheet WHERE id=".$quest_user['par1_value'].") AND user_id='$user_id'");
			$bringed=myquery("SELECT * FROM game_items WHERE item_id=".$quest_user['par1_value']." AND user_id='$user_id'");
			if(mysql_num_rows($bringed)>0) $done=1; else $done=0;
			get_stat($time,$done,$status,$color);
			
			list($name)=mysql_fetch_array(myquery("SELECT name FROM game_items_factsheet WHERE id=".$quest_user['par1_value']." "));
			//$zadanie=''.$npc['name'].' ������ ��� �������� ��� '.$name.'. ������ ���������� �� ������ �� '.strftime("%e-%m-%Y%t%T",$quest_user['quest_finish_time']).', ������ � ���� ������ ��� ���� ������� � ������ ������!';
			$help='����-���. � ������ �����-���� ���������, �������� �� ����, ����������, ������ Inquisitor_I.';
			break;
		}
		case 801:
		{
			$k=0;$zakaz=0;
				$bringed=array();				
				for($j=1;$j<5;$j++)
					if($quest_user['par'.$j.'_value']!=0)
					{					
						$zakaz++;	
						$ind='par';
						$ind.=$j; $ind.='_value';		
						//$result=myquery("SELECT * FROM game_items WHERE ident IN (SELECT name FROM game_items_factsheet WHERE id=".$quest_user["$ind"].") AND used=0 AND user_id=".$user_id."");						
						$result=myquery("SELECT * FROM game_items WHERE item_id=".$quest_user["$ind"]." AND used=0 AND user_id=".$user_id."");						
						$items=mysql_num_rows($result);						
						if($items>0) 
						{
							$k++;
							$bringed[count($bringed)]=$j;
						}
					}
			if($k>0) $done=1; else $done=0;
			
			get_stat($time,$done,$status,$color);
			
			/*$name='';
			for($j=0;$j<4;$j++)
			{
				if(!isset($quest_user['par'.$j.'_value'])) $quest_user['par'.$j.'_value']=0;
			    list($cname)=mysql_fetch_array(myquery("SELECT name FROM game_items_factsheet WHERE id=".$quest_user['par'.$j.'_value'].""));			
			    if(!empty($cname))
					$name.=$cname.' ��� ';																
			}
			$name=substr($name,0,strlen($name)-4);*/
			//$zadanie=''.$npc['name'].' ������ ��� �������� ��� �����-������ �� ��������� ���������: '.$name.'. ������ ���������� �� ������ �� '.strftime("%e-%m-%Y%t%T",$quest_user['quest_finish_time']).', ������ � ���� ������ ��� ���� ������� � ������ ������!';
			$help='������, ��� �������� ��� �������, ����� �� ��������� �����. � ������ �����-���� ���������, �������� �� ����, ����������, ������ Inquisitor_I.';
			break;
		}
		case 802:
		{
			
			$bringed=myquery("SELECT * FROM game_items WHERE shop_from=".$quest_user['par1_value']." AND type=".$quest_user['par2_value']." AND user_id='$user_id'");
			if(mysql_num_rows($bringed)>0) $done=1; else $done=0;
			get_stat($time,$done,$status,$color);
			//list($shop_name)=mysql_fetch_array(myquery("SELECT name FROM game_shop WHERE id=".$quest_user['par1_value'].""));
			//$zd='�������, �  ������� �� ����� '.$shop_name.' ���� � ������� '.item_type_name($quest_user['par2_value']).'! '.$npc['name'].' ������ ��������� ��� '.$quest_user['par3_value'].' ��������� ����� ���� �� ����� ��������.';
			
	        //$zadanie=''.$zd.'. ������ ���������� �� ������ �� '.strftime("%e-%m-%Y%t%T",$quest_user['quest_finish_time']).', ������ � ���� ������ ��� ���� ������� � ������ ������! ';
			$help='������, ��� �������� ������� ���, ����� ������ �������� �� ������ ������. �� ���� ���� ���� � ������� ����� ������� ��� �����������, �� ����, ��� �� ����������� ������ �� ��������, ������� �������� ��� ������� �������. � ������ �����-���� ���������, �������� �� ����, ����������, ������ Inquisitor_I.';
			break;
		}
		case 803:
		{
			if($quest_user['par2_value']>0)
						$bringed=myquery("SELECT * FROM game_items WHERE user_id='$user_id' AND used=0 AND ".$quest_user['par1_name'].">=".$quest_user['par1_value']." AND ".$quest_user['par2_name'].">=".$quest_user['par2_value']."");
					else 
						$bringed=myquery("SELECT * FROM game_items WHERE user_id='$user_id' AND used=0 AND ".$quest_user['par1_name'].">=".$quest_user['par1_value']."");
			if(mysql_num_rows($bringed)>0) $done=1; else $done=0;	
			get_stat($time,$done,$status,$color);
			
			//$zd='���������, ������� �������� '.item_par_name($quest_user['par1_name']).' �� '.$quest_user['par1_value'].'';																
			//					if(isset($quest_user['par2_value']) AND $quest_user['par2_value']!=0)
			//						$zd.=' � '.item_par_name($quest_user['par2_name']).' �� '.$quest_user['par2_value'].'';
			//					$zd.=' � ���������� '.$quest_user['par3_value'].' ����.';
			
			//$zadanie=''.$npc['name'].' ������ ��������� ��� '.$zd.'. ������ ���������� �� ������ �� '.strftime("%e-%m-%Y%t%T",$quest_user['quest_finish_time']).', ������ � ���� ������ ��� ���� ������� � ������ ������! ';
			$help='������, ��� �������� ������� ���, ����� ������ �������� �� ������ ������. �� ���� ���� ���� � ������� ����� ������� ��� �����������, �� ����, ��� �� ����������� ������ �� ��������, ������� �������� ��� ������� �������. � ������ �����-���� ���������, �������� �� ����, ����������, ������ Inquisitor_I.';
			break;
		}
		case 804:
		{
			$bringed=myquery("SELECT weight FROM game_items WHERE user_id='$user_id' AND used=0 AND item_id=".$quest_user["par1_value"]." AND item_uselife<=".$quest_user['par2_value']." AND item_uselife>=".$quest_user['par3_value']."");
			if(mysql_num_rows($bringed)>0) $done=1; else $done=0;
			get_stat($time,$done,$status,$color);
			list($wname)=mysql_fetch_array(myquery("SELECT name from game_items_factsheet WHERE id=".$quest_user["par1_value"].""));
			//$zadanie=''.$npc['name'].' ������ ��������� ��� '.$wname.', � ���������� �� ������, ��� '.$quest_user["par2_value"].'%, �� �� ������, ��� '.$quest_user["par3_value"].'%. ������� � ��� �� ��� - �� '.strftime("%e-%m-%Y%t%T",$time).', ����� ������ ������� ��� �� ������.';
			$help='������, ������� �� ������ �����, �� ������ ���� �� ��� �����. � ������ �����-���� ���������, �������� �� ����, ����������, ������ Inquisitor_I.';
			break;
		}
	}	
	echo '<UL TYPE=circle>';
	echo '<LI><a name="q'.$i.'"><b>��� ������� �����:</b> '.$ot_kogo.'</a>';
	echo '<LI><b>������:</b> <font color=#'.$color.'>'.$status.'</font>';
	echo '<LI><b>�������: </b>';
	/*1*///echo '<font color=#00FF00 size=3>topic_id='.($quest_user['quest_topic_id']).'</font><br>';
	/*1*///echo '<font color=#00FF00 size=3>owner_id='.($quest_user['quest_owner_id']).'</font><br>';
	/*1*///echo '<font color=#00FF00 size=3>quest_type='.($quest_user['quest_type']).'</font><br>';
	//$time=min(0,$quest_user['quest_finish_']);
	

	
	$text=myquery("SELECT text FROM quest_engine_topics WHERE topic_id=".$quest_user['quest_topic_id']." AND owner_id=".$quest_user['quest_owner_id']." AND action_type=40 AND quest_type=".$quest_user['quest_type']."");
	//if(!mysql_num_rows($text)) die('NO TOPICS');
	$its_journal=1;
	include("../quest/quest_engine_types/inc/standart_vars.inc.php");	
	
	if(mysql_num_rows($text)>0)
	{
		$all = mysql_num_rows($text);
		$r = mt_rand(0,$all-1);
		mysql_data_seek($text,$r);			
		list($text)=mysql_fetch_array($text);
	}
	else $text = "echo '������ � �������. � �� �� �������.';";
	//testing
	switch ($quest_user['quest_type'])
	{
		case 1: $text="echo '������: ".new_word('gold',$reward,0)." <br>�����: $time <br>���: $npc_name $npc_race';"; break;
		case 2: $text="echo '������: ".new_word('gold',$reward,0)." <br>�����: $time <br>�����: $part_name - $num ��<br>����: ��� $par2_rus_name $par2_value $par3_rus_name $par3_value $par4_rus_name $par4_value';"; break;
		case 5 :$text="echo '������: ".new_word('gold',$reward,0)." <br>�����: $time <br>�����: $rustowun, ".new_word("map_name",$map_name,1)."';"; break; 
		case 601:$text="echo '������: ".new_word('gold',$reward,0)." <br>�����: $time <br>�����: ".new_word("map_name",$map_name,1)." $x $y';"; break; 
		case 7:$text="echo '������: ".new_word('gold',$reward,0)." <br>�����: $time <br>�������: $name';"; break; 
		case 801:$text="echo '������: ".new_word('gold',$reward,0)." <br>�����: $time <br>��������: $name';"; break; 
		case 802:$text="echo '������: ".new_word('gold',$reward,0)." <br>�����: $time <br>��������: � $shop_name ".new_word("items",$type_id,0)." $num ����';"; break;
	}
	//end testing
	
	eval($text);
	//echo $text;
	echo '<LI><b>����������:</b> '.$help.'';
	echo '<LI><a href="?journal='.$journal.'">��������</a>';
	echo '</UL>';
	//echo '</q>';

?>