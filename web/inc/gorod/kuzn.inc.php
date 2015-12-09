<?

if (function_exists("start_debug")) start_debug(); 

$gp = 5;

$userban=myquery("select * from game_ban where user_id=$user_id and type=2 and time>".time()."");
if (mysql_num_rows($userban))
{
	$userr = mysql_fetch_array($userban);
	$min = ceil(($userr['time']-time())/60);
	echo '<center><br><br><br>На тебя наложено ПРОКЛЯТИЕ на '.$min.' минут! Тебе запрещено пользоваться кузницей!';
	{if (function_exists("save_debug")) save_debug(); exit;}
}
if ($town!=0)
{
	$craft_kuznec = getCraftLevel($user_id,12);
    
    $border_by_level = 90-8*$craft_kuznec;   
	$min_itemuselife = max(0,$border_by_level);
	$img='http://'.img_domain.'/race_table/orc/table';
	echo'<table width=100% border="0" cellspacing="0" cellpadding="0" align=center><tr><td width="1" height="1"><img src="'.$img.'_lt.gif"></td><td background="'.$img.'_mt.gif"></td><td width="1" height="1"><img src="'.$img.'_rt.gif"></td></tr>
	<tr><td background="'.$img.'_lm.gif"></td><td style="text-align:center" background="'.$img.'_mm.gif" valign="top">';

	$est_molot = myquery("SELECT id,item_uselife FROM game_items WHERE user_id=$user_id AND used=21 AND priznak=0 AND item_id=".molot_kuzn.""); 
	
	$new_year_lab = array(845,850,857,866,877);
	if (in_array($char['map_name'],$new_year_lab))
	{
		$min_itemuselife = -100;
		$no_kuzn=1;
	}
	
	if (((checkCraftTrain($user_id,12)>0 AND mysql_num_rows($est_molot)>0) or (isset($no_kuzn) and $no_kuzn==1)) AND $char['GP']>=$gp)
	{
		echo '<br /><br /><b>Для работы в общественной кузне необходимо заплатить '.$gp.' монет!</b><br /><br />';
		if (isset($_GET['nak']))
		{
			$nak = (int)$_GET['nak'];
			$result_items = myquery("SELECT game_items.item_uselife as uselife_now,game_items.id,game_items_factsheet.name,game_items_factsheet.item_uselife AS uselife_template,game_items_factsheet.breakdown,game_items.item_uselife_max AS uselife_max_now,game_items_factsheet.type FROM game_items, game_items_factsheet WHERE game_items.user_id=$user_id AND (game_items.used=0 or (game_items.item_uselife>=10 AND game_items_factsheet.type<>24)) and game_items.ref_id=0 and game_items.priznak=0  and game_items_factsheet.type<90 AND game_items_factsheet.type NOT IN (12,13,19,20,21) AND game_items.item_uselife<100 AND game_items.item_id=game_items_factsheet.id AND game_items.id=$nak AND game_items.item_uselife>=$min_itemuselife");
			if ($result_items!=false AND mysql_num_rows($result_items)>0)
			{
				echo '<br><br><br><center>';
				//предмет проверили. дальше идем
				myquery("UPDATE game_users SET GP=GP-".$gp.",CW=CW-".($gp*money_weight)." WHERE user_id=$user_id");
				setGP($user_id,-$gp,65);
				$item = mysql_fetch_array($result_items);
				$item['uselife_max']=100;
				$breakdown = 0;
				$chance = mt_rand(1,100);
				if ($item['breakdown']==1 and $chance > 1)
				{
					$breakdown = 1;
				}
				if (isset($no_kuzn) and $no_kuzn==1)
				{
					$repair = $item['uselife_max'];
				}
				else
				{						
					list($molot_id,$molot_uselife)=mysql_fetch_array($est_molot); 
					$break = ($item['uselife_max']-$item['uselife_now'])/100;
					$repair = $item['uselife_max'];
					if ($molot_uselife<=$break)
					{
						$molot = new Item($molot_id);
						$molot->down();
						myquery("UPDATE game_items SET item_uselife=0 WHERE id=$molot_id");
					}
					else
					{
						myquery("UPDATE game_items SET item_uselife=item_uselife-$break WHERE used=21 AND user_id=$user_id AND priznak=0 AND item_id=".molot_kuzn."");
					}
				}
				QuoteTable('open'); 
				echo '<br>'; 
				
				if ($item['uselife_max_now']>1 OR $breakdown==0)
				{
					myquery("UPDATE game_items SET item_uselife=$repair,item_uselife_max=item_uselife_max-$breakdown WHERE id=$nak");
					echo 'Предмет <span style="font-weight:800;color:red;">'.$item['name'].'</span> отремонтирован до состояния: '.$item['uselife_max'].'%.';					
					if ($breakdown>0)
					{
						add_exp_for_craft($user_id, 12);
						setCraftTimes($user_id,12,1,1);
						echo '<br />У предмета уменьшена долговечность на '.$breakdown.'';
					}
				}
				else
				{
					$Item = new Item($item['id']);
					$Item->admindelete();
					echo 'При попытке ремонта предмет был полностью разрушен, т.к. его долговечность снизилась до 0';
				}
				echo '&nbsp;<br>&nbsp;'; 
				QuoteTable('close');		
				echo '<br><br><br>';
			}
		}        
		
		echo'<span style="font-weight:900;color:red;font-size:13px;">Выбери предмет для починки:</span><br><br>(твой навык кузнеца позволяет ремонтировать тебе предметы с текущей прочностью не менее '.$min_itemuselife.'%)<br /><br>';

		$result_items = myquery("SELECT game_items.item_uselife as uselife_now,game_items.id,game_items_factsheet.name,game_items_factsheet.item_uselife AS uselife_max,game_items_factsheet.breakdown,game_items.item_uselife_max AS uselife_max_now,game_items_factsheet.type,game_items_factsheet.img,game_items.kleymo FROM game_items,game_items_factsheet WHERE game_items.user_id=$user_id AND (game_items.used=0 OR (game_items.item_uselife>=10 AND game_items_factsheet.type<>24)) and game_items.ref_id='0' and game_items.priznak=0 and game_items_factsheet.type<90 and game_items_factsheet.type NOT IN (12,13,19,20,21) AND game_items.item_uselife<100 AND game_items.item_id=game_items_factsheet.id AND game_items.item_uselife>=$min_itemuselife ORDER BY game_items.item_uselife");
		if (mysql_num_rows($result_items) > 0)
		{
			while ($items = mysql_fetch_array($result_items))
			{
				echo '<a href="town.php?option='.$option.'&nak='.$items['id'].'">';
				ImageItem($items['img'],0,$items['kleymo'],"middle","middle","Починить ".$items['name'],"Починить ".$items['name']);
				echo '</a> Прочность предмета: '.$items['uselife_now'].'%<br>';
			}
		}
	}
	elseif ($char['GP']<$gp)
	{
		echo 'У тебя нет денег для оплаты работы в кузне!';
	}
	elseif (mysql_num_rows($est_molot)==0)
	{
		echo 'Для работы в кузне надо взять в руки <b><font color=red>Молот кузнеца</font></b>!';
	}
	else
	{
		echo'У тебя нет специализации кузнеца';
	}	
	echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
}

if (function_exists("save_debug")) save_debug(); 

?>