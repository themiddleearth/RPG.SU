<?
require_once(dirname(__FILE__).'/inc/quest_define.inc.php');

//��� ���� 1
list($q_e_id)=mysql_fetch_array(myquery("SELECT npc_quest_engine_id FROM game_npc WHERE id='$user_id'"));
//���� ��� ��������� ������
if($q_e_id>0)
{
	//������, ��� ����� ����� �� ������� � ����� ����������
	list($owner_id,$fin_time,$opis)=mysql_fetch_array(myquery("SELECT quest_owner_id,quest_finish_time,par2_name FROM quest_engine_users WHERE quest_type=1 AND par1_value='$npc_id' "));
	//����� ������ ����� �� �������, ���� �� ���� �����
	if($q_e_id==$char['user_id'])
	{
		myquery("UPDATE quest_engine_users SET done=1 WHERE quest_type=1 AND par1_value='$npc_id' ");
		$Item = new Item();
		$new_id = $Item->add_user($id_item_monstr_balden,$user_id,0,$owner_id);
		//if($new_id[0]==1)
		$new_id = $new_id[1];
		//������� ��������
		$Item->setOpis($new_id, $opis);
		//�������� ���
		$weight = $weight=(mt_rand(50,85))/10;
		myquery("UPDATE game_items SET item_uselife = '$weight' WHERE id = '$new_id'");
		//���
		myquery("UPDATE game_users SET CW = CW+'$weight' WHERE user_id = ".$char['user_id']."");
		//������ ���� �� ����
		kill_quest_npc($npc_id);
	}
	//���� ����� ��� ���������
	elseif($fin_time<time())
	{
		//������ ���� �� ����
		kill_quest_npc($npc_id);
	}
}

//��� ���� 2
$q=myquery("SELECT * FROM quest_engine_users WHERE quest_type=2 AND user_id='$user_win' ");
while ($quest=mysql_fetch_array($q))
{
	//player byl - eto tut NPC
	if( ($npc[$quest['par2_name']]>=$quest['par2_value'] AND $quest['par3_name']=='' AND $quest['par4_name']=='') or
		($npc[$quest['par2_name']]>=$quest['par2_value'] AND $npc[$quest['par3_name']]>=$quest['par3_value'] AND $quest['par4_name']=='') or
		($npc[$quest['par2_name']]>=$quest['par2_value'] AND $npc[$quest['par3_name']]>=$quest['par3_value'] AND $npc[$quest['par4_name']]>=$quest['par4_value']))
	{
		//echo '������� ����!';
		//esli ne prosro4eno
		if($quest['quest_finish_time']>time())
		{
			
			//proverim, est' li nugnye kvestovye predmety
			/*$items=myquery("SELECT game_items_opis.* FROM game_items_opis,game_items WHERE game_items_opis.item_id=game_items.id AND game_items.user_id=$user_id AND game_items.item_id=$id_item_part_monster AND game_items.priznak=0 LIMIT 1");
			$check=0;
			while(list($item_opis)=mysql_fetch_array($items))
			{
				if(strpos($item_opis['opis'],$quest['par1_name'])===false) {} else {$check=1; break;}
			}*/
			$items=myquery("SELECT * FROM game_items WHERE user_id='$user_id' AND item_id='$id_item_part_monster' AND priznak=0 AND item_for_quest=".$quest['quest_owner_id']."");
			$weight = max(0.01, $npc['npc_max_hp']/200);
			list($to_opis)=mysql_fetch_array(myquery("SELECT npc_name FROM game_npc WHERE id='$npc_id'"));
			echo '�� '.echo_sex('��������','���������').' '.$quest['par1_name'].'!';
			if(mysql_num_rows($items)==0)
			{
				//echo '�����!';
				//�������� �������
				$Item = new Item();
				$ar = $Item->add_user($id_item_part_monster,$user_id,0,$quest['quest_owner_id']);
				if($ar[0]==1)
				{                	
					myquery("UPDATE game_items SET item_uselife='$weight', item_cost=1 WHERE id=".$ar[1]."");
					$Item->setOpis($ar[1],$to_opis);
					//c ����� ����� ��� ���������
					myquery("UPDATE game_users SET CW = CW+'$weight' WHERE user_id = ".$user_id."");
				}
			}
			else 
			{
				//echo '������!';
				//�������� �������
				$item = mysql_fetch_array($items);
				if($item['item_cost']+1==$quest['par1_value'])
					myquery("UPDATE quest_engine_users SET done=1 WHERE quest_type=2 AND user_id='$user_id' AND quest_owner_id=".$quest['quest_owner_id']."") or die(mysql_error());
				myquery("UPDATE game_items SET item_cost=item_cost+1, item_uselife=item_uselife+'$weight' WHERE user_id='$user_id' AND item_for_quest=".$quest['quest_owner_id']."") or die(mysql_error());	
				$Opis = new Item($item['id']);
				$opis_buf = $Opis->getOpis();
				$opis_buf.= ", ".$to_opis;
				$Opis->setOpis($item['id'],$opis_buf);
				
				//� ����� ����� ��� ��������� �����������
				myquery("UPDATE game_users SET CW = CW+'$weight' WHERE user_id = ".$user_id."");
			}
			
			
			/*if($check==0)
			{
				//$name=''.$quest['par1_name'].' �������: 1 �����.';
				$Item = new Item();
				$ar = $Item->add_user($id_item_part_monster,$user_id,0,$quest['quest_owner_id']);
				if($ar[0]==1)
				{
					$weight = $npc['npc_max_hp']/100;
					myquery("UPDATE game_items SET item_uselife='$weight', item_cost=1 WHERE id=".$ar[1]."");
				}
				//if ($ar[0]==1)
				//{
				//    $Item->setOpis($ar[1],$name);
				//}
			}
			else
			{
				$item_curse = $item_opis['opis'];
				$pos=strpos($item_curse,':');
				$n=$item_curse[$pos+2];
				if(is_numeric($item_curse[$pos+3])) $n.=$item_curse[$pos+3];
				$n=(int)$n;
				$n++;
				if($n==$quest['par1_value'])
				myquery("UPDATE quest_engine_users SET done=1 WHERE quest_type=2 AND user_id='$user_id' AND quest_owner_id=".$quest['quest_owner_id']."") or die(mysql_error());
				$name=''.$quest['par1_name'].' �������: '.$n.' '.pluralForm($n,'�����','�����','����').'.';
				$Item = new Item();
				$Item->setOpis($item_opis['item_id'],$name);
			}*/
			
			//�������� ��� ������. ���� ��������� break, ����� ������ 1 �����.
			//break;
		}
	}
}
?>