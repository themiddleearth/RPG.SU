<?php
$craft_kuznec = getCraftLevel($user_id,12);
if (checkCraftTrain($user_id,12)>0)
{
	$est_molot = myquery("SELECT id,item_uselife FROM game_items WHERE item_uselife>0 AND user_id=$user_id AND used=21 AND priznak=0 AND item_id=".molot_kuzn." LIMIT 1");
	if (mysql_num_rows($est_molot)>0)
	{
        $border_by_level = 90-8*$craft_kuznec;        
        $min_itemuselife = $border_by_level;
		if (isset($_GET['nak']) AND is_numeric($_GET['nak']))
		{
			$nak = (int)$_GET['nak'];
			$result_items = myquery("SELECT game_items.item_uselife as uselife_now,game_items.id,game_items_factsheet.name,game_items_factsheet.item_uselife AS uselife_template,game_items_factsheet.breakdown,game_items.item_uselife_max AS uselife_max_now,game_items_factsheet.type FROM game_items, game_items_factsheet WHERE game_items.user_id=$user_id AND (game_items.used=0 or (game_items.item_uselife>=10 AND game_items_factsheet.type<>24)) and game_items.ref_id=0 and game_items.priznak=0  and game_items_factsheet.type<90 AND game_items_factsheet.type NOT IN (12,13,19,20,21) AND game_items.item_uselife<100 AND game_items.item_id=game_items_factsheet.id AND game_items.id=$nak AND game_items.item_uselife>=$min_itemuselife");
			if ($result_items!=false AND mysql_num_rows($result_items)>0)
			{
				//������� ���������. ������ ����				
				$item = mysql_fetch_array($result_items);
				$breakdown = 0;
				$chance = mt_rand(1,100);
				if ($item['breakdown']==1 and $chance > 1)
				{
					$breakdown = 1;
				}
				if ($item['type']==3) $item['uselife_template']=100;
				list($molot_id,$molot_uselife)=mysql_fetch_array($est_molot);
				$break = ($item['uselife_template']-$item['uselife_now'])/100;
				if ($molot_uselife<=$break)
				{
					$molot = new Item($molot_id);
					$molot->down();
					myquery("UPDATE game_items SET item_uselife=0 WHERE id=$molot_id");
				}
				else
				{
					myquery("UPDATE game_items SET item_uselife=item_uselife-$break WHERE id=$molot_id");
				}
				QuoteTable('open'); 
				echo '<br>'; 				
								
				if ($item['uselife_max_now']>1 OR $breakdown==0)
				{
					myquery("UPDATE game_items SET item_uselife=".$item['uselife_template'].",item_uselife_max=item_uselife_max-$breakdown WHERE id=$nak");
					echo '������� <span style="font-weight:800;color:red;">'.$item['name'].'</span> �������������� �� ���������: '.$item['uselife_template'].'%.';					
					if ($breakdown>0)
					{
						//������� ���� �� ������
						add_exp_for_craft($user_id, 12);
						setCraftTimes($user_id,12,2,2);
						echo ' <br />� �������� ��������� ������������� �� '.$breakdown.'';
					}
				}
				else
				{
					$Item = new Item($item['id']);
					$Item->admindelete();
					echo '��� ������� ������� ������� ��� ��������� ��������, �.�. ��� ������������� ��������� �� 0';
				}
				echo '&nbsp;<br>&nbsp;'; 
				QuoteTable('close');								
				echo '<br><br><br>';
			}
		}        
		
		echo'<span style="font-weight:900;color:red;font-size:13px;">������ ������� ��� �������:</span><br><br>(���� ����� ������� ��������� ������������� ���� �������� � ������� ���������� �� ����� '.$min_itemuselife.'%)<br /><br>';

		$result_items = myquery("SELECT game_items.item_uselife,game_items.id,game_items_factsheet.type,game_items_factsheet.img,game_items_factsheet.name FROM game_items,game_items_factsheet WHERE game_items.user_id=$user_id AND (game_items.used=0 OR (game_items.item_uselife>=10 AND game_items_factsheet.type<>24)) and game_items.ref_id='0' and game_items.priznak=0 and game_items_factsheet.type<90 and game_items_factsheet.type NOT IN (12,13,19,20,21) AND game_items.item_uselife<100 AND game_items.item_id=game_items_factsheet.id AND game_items.item_uselife>=$min_itemuselife ORDER BY game_items.item_uselife");
		if (mysql_num_rows($result_items) > 0)
		{
			while ($items = mysql_fetch_array($result_items))
			{
				echo '<a href="town.php?option='.$option.'&nak='.$items['id'].'&part4&add='.$build_id.'"><img src="http://'.img_domain.'/item/' . $items['img'] . '.gif" border="0" alt="�������� '.$items['name'].'" title="�������� '.$items['name'].'" align="middle"></a> ��������� ��������: '.$items['item_uselife'].'%<br>';
			}
		}
	}
	else
	{
		echo '<br /><br />��� ������ � ����� ���� ����� � ���� <b><font color=red>����� �������</font></b>!';
	}
}
else
{
	echo '<br />���� ���������� � ��������� ���� ������� ����!<br /><br /><br />';
}
?>