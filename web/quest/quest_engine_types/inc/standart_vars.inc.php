<?PHP
	$char_name='<font color=red>'.$char["name"].'</font>';
	$char_race=mysql_result(myquery("SELECT name FROM game_har WHERE id=".$char['race'].""),0,0);
	$reward='<font color=yellow>'.$quest_user["quest_reward"].'</font>';
	
	//time
	if(!isset($its_journal))
	{
		$hours=floor(($quest_user['quest_finish_time']-$quest_user['quest_start_time'])/3600);
		$mins=floor(($quest_user['quest_finish_time']-$quest_user['quest_start_time'])/60)-$hours*60;
	}else 
	{
		$hours=max(0,floor(($quest_user['quest_finish_time']-time())/3600));
		$mins=max(0,floor(($quest_user['quest_finish_time']-time())/60)-$hours*60);
	}
	if($hours==0) $hours=''; else
	{	$hours.=' ���';
		if($hours%10>=2 AND $hours%10<=4 AND ($hours<10 OR $hours>19)) $hours.='�';
		elseif ($hours%10>4 or $hours%10==0) $hours.='��';
	}				
	if($mins==0 AND $hours!=0) $mins='';
	else 
	{	$mins.=' �����';
		if($mins==1 OR ($mins%10==1 AND $mins%100!=11)) $mins.='�';
		elseif($mins%10>=2 AND $mins%10<=4 AND ($mins<10 OR $mins>19)) $mins.='�';				
	}
	$time='<font color=#aaffff>'.$hours.' '.$mins.'</font>';
	//			
	switch ($quest_user['quest_type'])
	{
		case 1: 
			$npc_name=$quest_user['par2_name'];//��� ������� 
			$npc_race=$quest_user['par3_name']; //���� �������
		break;
		
		case 2: 
			$part_name=$quest_user['par1_name']; //�������� ��������� ����� �������. 
			$num=$quest_user['par1_value'];  //�����, ����������� ���-�� ������. 
			$par2_rus_name=parameter_rus_name($quest_user['par2_name']); //�������� ������� ��������� � ������� ", ���� �������� �� ������". 
			$par2_value=$quest_user['par2_value']; //�����, �������� ������� ���������.
			$par3_rus_name=parameter_rus_name($quest_user['par3_name']);
			if($par3_rus_name!='')
				$par3_value=$quest_user['par3_value'];
			else 
				$par3_value='';
			$par4_rus_name=parameter_rus_name($quest_user['par4_name']);
			if($par4_rus_name!='')
				$par4_value=$quest_user['par4_value'];
			else 
				$par4_value='';
		break;
		
		case 3: 
			$exp=$quest_user['par1_value'];//����������� ���-�� �����.    
		break;
		
		case 4: 
			$wins=$quest_user['par1_value'];//����������� ���-�� �����.  
		break;
		
		case 5: 
			list($map_name)=mysql_fetch_array(myquery("SELECT name FROM game_maps WHERE id=".$quest_user['par1_value'].""));// - �������� �����. 
			list($rustowun)=mysql_fetch_array(myquery("SELECT rustown FROM game_gorod WHERE town=".$quest_user['par2_value'].""));//- �������� ������.
		break;
		
		case 601: 
			list($map_name)=mysql_fetch_array(myquery("SELECT name FROM game_maps WHERE id=".$quest_user['par1_value'].""));// - �������� �����.
			$x=$quest_user['par2_value'];
			$y=$quest_user['par3_value'];// - �����. ���������� �������.
		break;
		
		case 7: 
			list($name)=mysql_fetch_array(myquery("SELECT name FROM game_items_factsheet WHERE id=".$quest_user['par1_value'].""));
			//�������� ������� ��������
		break;
		
		case 801: 
			$ids=array($quest_user['par1_value'],$quest_user['par2_value'],$quest_user['par3_value'],$quest_user['par4_value']);
			$name='';
			for($j=0;$j<4;$j++)
			{
				if(!isset($ids[$j])) $ids[$j]=0;
				list($cname)=mysql_fetch_array(myquery("SELECT name FROM game_items_factsheet WHERE id=".$ids[$j].""));								
				if(!empty($cname))
				$name.=$cname.' ��� ';																
			}
			$name=substr($name,0,strlen($name)-4);
			unset($cname);
			unset($ids);
			unset($j);
		break;
		
		case 802: 
			$shop_name=mysql_result(myquery("SELECT name FROM game_shop WHERE id=".$quest_user['par1_value'].""),0,0);// - ��� ��������. 
			$type_id=$quest_user['par2_value'];// ����� ��� �������� ������� ���� � ������� "������� ������". 
			$num=$quest_user['par3_value']; //- ����������� ���-�� ���������.    
		break;
		
		case 803: 
			$name='';
			$name=''.item_par_name($quest_user['par1_name']).' �� '.$quest_user['par1_value'].'';												
			if($quest_user['par2_value']!=0)
				$name.=' � '.item_par_name($quest_user['par2_name']).' �� '.$quest_user['par2_value'].'';//- ����������� ��������� � ������� "���� �� 2 � �������� �� 1". 
			$num=$quest_user['par3_value']; //- ����������� ���-�� ���������. 
		break;
		
		case 804: 
			list($wname)=mysql_fetch_array(myquery("SELECT name FROM game_items_factsheet WHERE id=".$quest_user['par1_value']."")); //- �������� ������������ ������. 
			$top=$quest_user['par2_value']; //- �����, ������� ������� �������� ���������. 
			$buttom=$quest_user['par3_value']; //- �����, ����� ������� �������� ���������.
		break;
	}
?>