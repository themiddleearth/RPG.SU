<?PHP
//������������ ��� ������� � ������ ������ ��������� ����
	function new_word($type, $num, $pos)
	{
		global $quest_user;
		switch ($type)
		{
			case "oh":case "ent_w": 
			{
				//$text="���-���"; 
				$words=myquery("SELECT word FROM quest_engine_words WHERE type='".$type."'");
				$all = mysql_num_rows($words);
				$r = mt_rand(0,$all-1);
				mysql_data_seek($words,$r);					
				list($text)=mysql_fetch_array($words);
				break;
			}
			//case "ent_w": $text="������ ������"; break;
			case "obr": 
			{
				//$text="���"; 
				global $user_id;
				list($sex)=mysql_fetch_array(myquery("SELECT sex FROM game_users_data WHERE user_id=".$user_id.""));
				if($sex=="male")
					$words=myquery("SELECT word FROM quest_engine_words WHERE SUBSTRING(word,1,1)='m' AND type='obr'");			
				else
					$words=myquery("SELECT word FROM quest_engine_words WHERE SUBSTRING(word,1,1)='f' AND type='obr'");			
				$all = mysql_num_rows($words);
				$r = mt_rand(0,$all-1);
				mysql_data_seek($words,$r);	
				list($text)=mysql_fetch_array($words);
				$text=substr($text,2);
				break;
			}
			
			case "gold": 
			{
				if($quest_user['quest_reward']%10==1)
					$text=$num.' �������';
				else 	
					$text=$num.' �������';			
				break;
			}	
			case "exp":
			{
				if($num%10==0 OR $num%10>=5)							
					$text=$num.' ����� �����';
				elseif($num%10==1) $text=$num.' ���� �����';
					else $text=$num.' ���� �����';	
				break;	
			}
			case "wins":
			{
				if($num%10==0 OR $user['par1_value']%10>=5)							
					$text=$num.' �����';
				elseif($num%10==1) $text=$num.' ������';
				else $text=$num.' ������';			
				break;
			}
			case "map_name":
			{
				$text=$num;
				if(substr_count($num,'��e'))  $text.='e';
				break;				
			}
			case "items":
			{
				switch ($num)
				{
					case 1:$text="������� ������";break;
					case 2:$text="������� ������";break;
					case 3:$text="������� ���������";break;
					case 4:$text="������� ����";break;
					case 5:$text="������� �������";break;
					case 6:$text="������� �����";break;
					case 7:$text="������� �����";break;
					case 8:$text="������� �����";break;
					case 9:$text="������� ��������";break;
					case 10:$text="������� ��������";break;
					case 11:$text="������� �����";break;
					case 12:$text="������� ��������� :)";break;		
					default:$text="����������� ��� (".$num.")";break;
				}
				break;
			}
			default: return ""; break;
		}
		if($pos==1)
		{
			$text[0]=strtoupper($text[0]);
		}
		return $text;
	}
//������ ���	
function parameter_rus_name($npc_par_name)
{
	switch($npc_par_name)
	{
		case 'npc_max_hp': return ', ������������ �������� �������� �� ������ ';break;
		case'npc_max_mp':return ', ������������ ���� �������� �� ������ ';break;
		case 'npc_str':return ', ���� �������� �� ������ ';break;
		case 'npc_dex':return ', ������������ (� �� ��������) �������� �� ������ ';break;
		case 'npc_wis':return ', �������� (� �� ��������) �������� �� ������ ';break;
		case 'npc_basefit':return ', ������ (� �� ������������) �������� �� ������ ';break;
		case 'npc_basedef':return ', �������� (� �� ������) �������� �� ������ ';break;
		case 'npc_exp':return ', ����� �� �������� ���� �� ������ ';break;
		case 'npc_gold':return ', ������ �� �������� ���� �� ������ ';break;
		case 'npc_ntl':return ', ��������� �������� �� ������ ';break;
		case 'npc_level':return ', ������� �������� �� ������ ';break;
		default: return ''; break;
	}
}
//------------------------------------------------------
function item_par_name($name)
{
	switch ($name)
	{
		case 'dstr': return '����'; break;
		case 'ddex': return '������������ (��������)'; break;
		case 'dpie': return '�������� (������)'; break;
		case 'dvit': return '������ (������������)'; break;
		case 'dspd': return '��������'; break;
		case 'dntl': return '���������'; break;
	}		
}
//---------------------------------------------------
/*function item_type_name($type)
{
	switch ($type)
	{
		case 1:return"������� ������";break;
		case 2:return"������� ������";break;
		case 3:return"������� ���������";break;
		case 4:return"������� ����";break;
		case 5:return"������� �������";break;
		case 6:return"������� �����";break;
		case 7:return"������� �����";break;
		case 8:return"������� �����";break;
		case 9:return"������� ��������";break;
		case 10:return"������� ��������";break;
		case 11:return"������� �������";break;
		case 12:return"������� ��������� :)";break;		
	}
}*/
//--------------------------------
?>