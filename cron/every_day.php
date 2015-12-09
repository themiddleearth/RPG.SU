<?php
include("config.inc.php");
require_once("/home/vhosts/rpg.su/web/class/class_item.php");

myquery("SET wait_timeout=1800");

myquery("DELETE FROM game_cron_log WHERE cron='every_day' AND step='final'");
myquery("INSERT INTO game_cron_log (cron,step,timecron) VALUES ('every_day','Начало',".time().")");
$idcronlog = mysql_insert_id();
 
myquery("UPDATE game_cron_log SET step='1. Выявление мультов в game_activity', timecron=".time()." WHERE id=$idcronlog");
$sellastid = myquery("SELECT id FROM game_activity_mult ORDER BY id DESC LIMIT 1");
if ($sellastid!=false AND mysql_num_rows($sellastid)>0)
{
	list($last_id) = mysql_fetch_array($sellastid);
}
else
{
	$last_id = 0;
}
$sel = myquery("SELECT DISTINCT host,host_more FROM game_activity");
while ($ac = mysql_fetch_array($sel))
{
	$sel_name = myquery("SELECT DISTINCT name FROM game_activity WHERE name<>'ban' AND host='".$ac['host']."' AND host_more='".$ac['host_more']."'");
	if (mysql_num_rows($sel_name)>1)
	{
		while (list($name) = mysql_fetch_array($sel_name))
		{
			myquery("INSERT INTO game_activity_mult SELECT * FROM game_activity WHERE name='".$name."' AND host='".$ac['host']."' AND host_more='".$ac['host_more']."' AND id>$last_id");
		}
	}
}
$check=myquery("");
if (mysql_num_rows($check)>0)
{
	while ($result=mysql_fetch_array($check))
	{
		echo '';
	}
}

myquery("UPDATE game_cron_log SET step='2. Перекачка статов к нормальным', timecron=".time()." WHERE id=$idcronlog");
//приведем всех "перекачанных" по харкам игроков к их нормальному состоянию (за исключением тех, кто перекачался у обелиска)
myquery("UPDATE game_users SET STR=STR_MAX WHERE STR<>STR_MAX AND user_id NOT IN (SELECT user_id FROM game_obelisk_users)");
myquery("UPDATE game_users SET NTL=NTL_MAX WHERE NTL<>NTL_MAX AND user_id NOT IN (SELECT user_id FROM game_obelisk_users)");
myquery("UPDATE game_users SET PIE=PIE_MAX WHERE PIE<>PIE_MAX AND user_id NOT IN (SELECT user_id FROM game_obelisk_users)");
myquery("UPDATE game_users SET VIT=VIT_MAX WHERE VIT<>VIT_MAX AND user_id NOT IN (SELECT user_id FROM game_obelisk_users)");
myquery("UPDATE game_users SET DEX=DEX_MAX WHERE DEX<>DEX_MAX AND user_id NOT IN (SELECT user_id FROM game_obelisk_users)");
myquery("UPDATE game_users SET SPD=SPD_MAX WHERE SPD<>SPD_MAX AND user_id NOT IN (SELECT user_id FROM game_obelisk_users)");

myquery("UPDATE game_users_archive SET STR=STR_MAX WHERE STR<>STR_MAX AND user_id NOT IN (SELECT user_id FROM game_obelisk_users)");
myquery("UPDATE game_users_archive SET NTL=NTL_MAX WHERE NTL<>NTL_MAX AND user_id NOT IN (SELECT user_id FROM game_obelisk_users)");
myquery("UPDATE game_users_archive SET PIE=PIE_MAX WHERE PIE<>PIE_MAX AND user_id NOT IN (SELECT user_id FROM game_obelisk_users)");
myquery("UPDATE game_users_archive SET VIT=VIT_MAX WHERE VIT<>VIT_MAX AND user_id NOT IN (SELECT user_id FROM game_obelisk_users)");
myquery("UPDATE game_users_archive SET DEX=DEX_MAX WHERE DEX<>DEX_MAX AND user_id NOT IN (SELECT user_id FROM game_obelisk_users)");
myquery("UPDATE game_users_archive SET SPD=SPD_MAX WHERE SPD<>SPD_MAX AND user_id NOT IN (SELECT user_id FROM game_obelisk_users)");

myquery("UPDATE game_cron_log SET step='2.5 Выдача зарядов скрытности', timecron=".time()." WHERE id=$idcronlog");
myquery("UPDATE game_users gu JOIN game_users_skills gus ON gu.user_id=gus.user_id SET gu.hide_charges=gus.level WHERE gus.skill_id=35 AND gu.reinc>=1");


myquery("UPDATE game_cron_log SET step='3. Переброс игроков в архив', timecron=".time()." WHERE id=$idcronlog");
//перебросим игроков не появляющихся в игре 3 дня в архив
$cur_time=time()-60*60*24*3;
echo 'Переброс игроков в архив<br>';
$sel = myquery("INSERT INTO game_users_archive SELECT game_users.* FROM game_users,game_users_active WHERE game_users_active.user_id=game_users.user_id AND game_users_active.last_active<'".$cur_time."'");
$del = myquery("DELETE FROM game_users WHERE user_id IN ( SELECT user_id FROM game_users_active WHERE last_active<'".$cur_time."' )");

myquery("UPDATE game_cron_log SET step='4. Очистка истории игровой активности', timecron=".time()." WHERE id=$idcronlog");
//Очистка активности (более 30 дней назад)
$cur_time=time()-60*60*24*90;
echo 'Очистка активности<br>';
$del = myquery("DELETE FROM game_activity WHERE time<'".$cur_time."'");

myquery("UPDATE game_cron_log SET step='5. Очистка удаленных писем', timecron=".time()." WHERE id=$idcronlog");
$cur_time=time()-60*60*24*30;
echo 'Очистка удаленных писем<br>';
$del = myquery("DELETE FROM game_pm_deleted WHERE time<'".$cur_time."'");

myquery("UPDATE game_cron_log SET step='6. Очистка просроченной регистрации', timecron=".time()." WHERE id=$idcronlog");
//Очистка просроченной регистрации игроков (более 48 часов)
echo 'Очистка просроченной регистрации игроков<br>';
$cur_time=time()-60*60*24*2;
$del = myquery("DELETE FROM game_users_reg WHERE rego_time<'".$cur_time."'");

myquery("UPDATE game_cron_log SET step='7. Очистка логов боев', timecron=".time()." WHERE id=$idcronlog");
//Очистка логов боев (более 30 дней назад)
echo 'Очистка логов боев<br>';
$cur_time=time()-60*60*24*30;
myquery("DELETE FROM game_combats_log_data WHERE boy IN (SELECT DISTINCT boy FROM game_combats_log WHERE time<$cur_time)");
myquery("DELETE FROM game_combats_users WHERE boy IN (SELECT DISTINCT boy FROM game_combats_log WHERE time<$cur_time)");
myquery("DELETE FROM game_combats_log WHERE time<$cur_time");
//$del = myquery("DELETE FROM game_combats_log WHERE time<'".$cur_time."'");

myquery("UPDATE game_cron_log SET step='8. Изменение цен у торговцев', timecron=".time()." WHERE id=$idcronlog");
//Изменение цен у торговцев
$sel = myquery("SELECT * FROM game_shop");
while ($shop = mysql_fetch_array($sel))
{
	$cena_pok = mt_rand($shop['cena_pok_min'],$shop['cena_pok_max']);
	$cena_prod = mt_rand($shop['cena_prod_min'],$shop['cena_prod_max']);
	myquery("UPDATE game_shop SET cena_pok=".$cena_pok.", cena_prod=".$cena_prod." WHERE id=".$shop['id']."");
}

echo'Товары на рынках<br>';

myquery("UPDATE game_cron_log SET step='9. Возврат товаров с рынка', timecron=".time()." WHERE id=$idcronlog");
//вернем просроченные неклеймёные товары с рынков в рюкзаки игроков
$time_for_check = time()- 60*24*60*60;
$pg=myquery("SELECT * FROM game_items where priznak=1 and sell_time<'$time_for_check' ORDER BY sell_time DESC");
while($item = mysql_fetch_array($pg))
{
	$town = $item['town'];
	$is_clan_town = @mysql_result(@myquery("SELECT COUNT(*) FROM game_clans WHERE town='$town'"),0,0);
	if($is_clan_town==0)
	{
		//Предмет не покупали. Просто возвращаем его владельцу
		$userid =  $item['user_id'];
		$it = $item['id'];
		$item_id = $item['item_id'];
		list($type,$ident,$weight) = mysql_fetch_array(myquery("SELECT type,name,weight FROM game_items_factsheet WHERE id=".$item['item_id'].""));
		if ($type=='12' or $type=='13' or $type=='19' or $type=='21' or $type=='22' or $type=='97')
		{
			$check=myquery("Select count_item from game_items where priznak=0 and ref_id=0 and user_id=$userid and item_id=$item_id");
			if (mysql_num_rows($check)>0)
			{
				myquery("UPDATE game_items SET count_item=count_item+".$item['count_item']." where priznak=0 and ref_id=0 and user_id=$userid and item_id=$item_id");
				myquery("Delete From game_items WHERE id=".$item['id']."");
			}
			else
			{
				myquery("UPDATE game_items SET priznak=0,sell_time=0,ref_id=0,item_cost=0,post_to=0,post_var=0,used=0 WHERE id=".$item['id']."");
			}
		}
		else
		{
			myquery("UPDATE game_items SET priznak=0,sell_time=0,ref_id=0,item_cost=0,post_to=0,post_var=0,used=0 WHERE id=".$item['id']."");
		}
		myquery("update game_users set CW=CW+'$weight' where user_id='$userid'");
		myquery("update game_users_archive set CW=CW+'$weight' where user_id='$userid'");
		$town_select = myquery("select rustown from game_gorod where town='".$item['town']."'");
		list($rustown)=mysql_fetch_array($town_select);
		
		$ma=myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES ('$userid', 'Автосообщение', 'Рынок', 'Срок аренды закончился. Твой предмет ".$ident." снят с продажи на рынке в ".$rustown.", так как на твой предмет так и не нашлось покупателя! Он возвращен в твой инвентарь','0','".time()."')");
		echo 'Предмет '.$ident.' никем не куплен. Владелец '.$userid.'<br>';
	}
}

//вернем просроченные клеймёные товары с рынков в рюкзаки игроков
// $time_for_check = time()- 20*24*60*60;
// $pg=myquery("SELECT * FROM game_items where priznak=1 and sell_time<'$time_for_check' and kleymo>0 ORDER BY sell_time DESC");
// while($item = mysql_fetch_array($pg))
// {
	// $town = $item['town'];
	// $is_clan_town = @mysql_result(@myquery("SELECT COUNT(*) FROM game_clans WHERE town='$town'"),0,0);
	// if($is_clan_town==0)
	// {
		// Предмет не покупали. Просто возвращаем его владельцу
		// $userid =  $item['user_id'];
		// $it = $item['id'];
		// $item_id = $item['item_id'];
		// list($type,$ident,$weight) = mysql_fetch_array(myquery("SELECT type,name,weight FROM game_items_factsheet WHERE id=".$item['item_id'].""));
		// if ($type=='12' or $type=='13' or $type=='19' or $type=='21' or $type=='22' or $type=='97')
		// {
			// $check=myquery("Select count_item from game_items where priznak=0 and ref_id=0 and user_id=$userid and item_id=$item_id");
			// if (mysql_num_rows($check)>0)
			// {
				// myquery("UPDATE game_items SET count_item=count_item+".$item['count_item']." where priznak=0 and ref_id=0 and user_id=$userid and item_id=$item_id");
				// myquery("Delete From game_items WHERE id=".$item['id']."");
			// }
			// else
			// {
				// myquery("UPDATE game_items SET priznak=0,sell_time=0,ref_id=0,item_cost=0,post_to=0,post_var=0,used=0 WHERE id=".$item['id']."");
			// }
		// }
		// else
		// {
			// myquery("UPDATE game_items SET priznak=0,sell_time=0,ref_id=0,item_cost=0,post_to=0,post_var=0,used=0 WHERE id=".$item['id']."");
		// }
		// myquery("update game_users set CW=CW+'$weight' where user_id='$userid'");
		// myquery("update game_users_archive set CW=CW+'$weight' where user_id='$userid'");
		// $town_select = myquery("select rustown from game_gorod where town='".$item['town']."'");
		// list($rustown)=mysql_fetch_array($town_select);
		
		// $ma=myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES ('$userid', 'Автосообщение', 'Рынок', 'Срок аренды закончился. Твой предмет ".$ident." снят с продажи на рынке в ".$rustown.", так как на твой предмет так и не нашлось покупателя! Он возвращен в твой инвентарь','0','".time()."')");
		// echo 'Предмет '.$ident.' никем не куплен. Владелец '.$userid.'<br>';
	// }
// }

myquery("UPDATE game_cron_log SET step='10. Возврат ресурсов с рынка', timecron=".time()." WHERE id=$idcronlog");
//вернем просроченные ресурсы с рынков в рюкзаки игроков
$time_for_check = time()- 20*24*60*60;
$pg=myquery("SELECT * FROM craft_resource_market where priznak=0 and sell_time<'$time_for_check' ORDER BY sell_time DESC");
while($item = mysql_fetch_array($pg))
{
    $town = $item['town'];
    $is_clan_town = @mysql_result(@myquery("SELECT COUNT(*) FROM game_clans WHERE town='$town'"),0,0);
    if($is_clan_town==0)
    {
        //Ресурс не покупали. Просто возвращаем его владельцу
        $userid =  $item['user_id'];
        $it = $item['res_id'];

        list($ident,$weight) = mysql_fetch_array(myquery("SELECT name,weight FROM craft_resource WHERE id=".$item['res_id'].""));

        $weight = $weight*$item['col'];
        myquery("INSERT INTO craft_resource_user (user_id,res_id,col) VALUES ('".$item['user_id']."','".$item['res_id']."','".$item['col']."') ON DUPLICATE KEY UPDATE col=col+'".$item['col']."'");
        myquery("update game_users set CW=CW+'$weight' where user_id='$userid'");
        myquery("update game_users_archive set CW=CW+'$weight' where user_id='$userid'");
        myquery("delete from craft_resource_market where id=".$item['id']."");
        $town_select = myquery("select rustown from game_gorod where town='".$item['town']."'");
        list($rustown)=mysql_fetch_array($town_select);
        
        $ma=myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES ('$userid', 'Автосообщение', 'Рынок', 'Срок аренды закончился. Твой ресурс ".$ident." снят с продажи на рынке в ".$rustown.", так как на твой ресурс так и не нашлось покупателя! Он возвращен в твой инвентарь','0','".time()."')");
        echo 'Ресурс '.$ident.' никем не куплен. Владелец '.$userid.'<br>';
    }
}

myquery("UPDATE game_cron_log SET step='11. Письма тавернщикам', timecron=".time()." WHERE id=$idcronlog");
echo'Письма по тавернам<br>';
//отправим письма владельцам таверен
$sel = myquery("SELECT * FROM game_tavern");
while ($tav = mysql_fetch_array($sel))
{
	if ($tav['msg']!='0')
		{
			$town = $tav['town'];
			$map = mysql_fetch_array(myquery("SELECT * FROM game_map WHERE town='$town' AND to_map_name='' LIMIT 1"));
			if ($map['name']!=0 )
				{
					$map_name = mysql_result(myquery("SELECT name FROM game_maps WHERE id='".$map['name']."'"),0,0);
					$rustown = mysql_result(myquery("SELECT rustown FROM game_gorod WHERE town='$town'"),0,0);
					$tavern = 'городе '.$rustown.' ('.$map_name.' '.$map['xpos'].', '.$map['ypos'].')';
					$result=myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES ('".$tav['vladel']."', '0', 'Состояние запасов в твоей таверне', 'На текущий момент у тебя на складах твоей таверны в ".$tavern." находятся запасы: HP = ".$tav['hp_store']." единиц, MP = ".$tav['mp_store']." единиц, STM = ".$tav['stm_store']." единиц. Текущий доход таверны - ".$tav['dohod']." монет', '0','".time()."')");
				}
		}
}

myquery("UPDATE game_cron_log SET step='12. Начисление задолженности по кланам', timecron=".time()." WHERE id=$idcronlog");
//начисление и проверка задолженности по кланам
$da = getdate();
$lastday = 28;
if (checkdate($da['mon'],31,$da['year'])) $lastday = 31;
elseif (checkdate($da['mon'],30,$da['year'])) $lastday = 30;
elseif (checkdate($da['mon'],29,$da['year'])) $lastday = 29;
if ($da['mday']==$lastday)
{
	//начисление задолженности
	$sel = myquery("SELECT * FROM game_clans WHERE raz=0");
	while ($clan = mysql_fetch_array($sel))
	{
		$kol = 0;
		$summa = 0;
		$seluser = myquery("(SELECT clevel FROM game_users WHERE clan_id=".$clan['clan_id'].") UNION (SELECT clevel FROM game_users_archive WHERE clan_id=".$clan['clan_id'].")");
		while (list($level)=mysql_fetch_array($seluser))
		{
			$kol++;
			if ($level<10) $summa+=0;
			elseif ($level<20) $summa+=70;
			elseif ($level<30) $summa+=130;
			elseif ($level<40) $summa+=240;
			else $summa+=350;
		}
		if ($kol<=10) $summa=round($summa/2,2);
		elseif ($kol<=20) $summa=round($summa*0.75,2);
		//Увеличение налога, если у клана есть город
		$test_gorod=myquery("SELECT * FROM game_gorod WHERE clan=".$clan['clan_id']."");
		if (mysql_num_rows($test_gorod)>0) $summa=round($summa*(1+0.5*mysql_num_rows($test_gorod)),2);
			
		myquery("INSERT INTO game_clans_taxes (clan_id,month,year,summa) VALUE (".$clan['clan_id'].",".$da['mon'].",".$da['year'].",".$summa.")");
		//Рассылка уведомлений о начисленном клановом налоге глава клана и автооплата налога
		$check = myquery("SELECT gct.id, gc.clan_id, gc.nazv, gc.glava, gct.summa as nalog, (CASE WHEN gc.gp>=gct.summa and gc.autopay = 1 THEN 1 ELSE 0 END) as autopay FROM game_clans_taxes gct JOIN game_clans gc ON gct.clan_id = gc.clan_id WHERE gct.month = ".$da['mon']." and gct.year = ".$da['year']." and gct.summa>0");
		while ($clan = mysql_fetch_array($check))
		{
			$theme = 'Начислен налог за содержание клана '.$clan['nazv'];
			$post = $theme.' в размере '.$clan['nalog'].' '.pluralForm($clan['nalog'],'монеты','монет','монет').'.';
			if ($clan['autopay'] == 1) 
			{				
				list($vozrast,$user_clan_all) = mysql_fetch_array(myquery("SELECT SUM(vozrast), COUNT(*) FROM game_clans_vozrast WHERE clan_id=".$clan['clan_id']." AND month=".$da['mon']." AND year = ".$da['year'].""));
				$rating = floor(8*($vozrast/($user_clan_all*30)));
				if ($rating<1) $rating = 1;
				if ($rating>8) $rating = 8;
				$post.='<br>Налог оплачен автоматически. <br> Рейтинг клана увеличен на '.$rating.' ед.';
				myquery("UPDATE game_clans_taxes SET summa=0, flag=1, time_pay=".time().", rating=".$rating." WHERE id=".$clan['id']." ");
			}
			myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time, folder) VALUES ('".$clan['glava']."', '0', '".$theme."', '".$post."','0','".time()."',1)");					
		}
	}
}
if ($da['mday']==10)
{
	//проверка оплаты задолженности
	$sel = myquery("SELECT * FROM game_clans WHERE raz=0");
	while ($clan = mysql_fetch_array($sel))
	{
		$rat = 0;
		$add = 0;
		$selcheck = myquery("SELECT * FROM game_clans_taxes WHERE clan_id=".$clan['clan_id']." AND flag=0");
		while ($tax = mysql_fetch_array($selcheck))
		{
			$rat+=5+$add;
			$add+=2;    
		}    
		if ($rat>0)
		{
			myquery("UPDATE game_clans SET raring=raring-$rat WHERE clan_id=".$clan['clan_id']."");
		}
	}
}

echo'Все<br>';

myquery("UPDATE game_cron_log SET step='13. Очистка таблицы неудачных входов в игру', timecron=".time()." WHERE id=$idcronlog");
//очистка таблицы неудачных логинов
myquery("TRUNCATE TABLE game_login");

function delete_house($user_id,$build_id=0,$town=0)
{
	//Если $build_id=0 - удаляем все постройки из-за налогов и удаляем саму землю
	//Иначе удаляем конкретное $build_id здание из houses_users из-за ремонта
	//При удалении дома - из-за ремонта - остальные здания не удаляем. 
	//возвращаем предметы из хранилищ в его инвентарь (кроме эликсиров)
	$weight = 0;
	
	// Если сломан основной дом, то уничтожаем все здания в городе
	if ($build_id>=1 AND $build_id<=4)
	{
		$build_id = 0;
	}
	
	if ($build_id == 0)//Удаляем дом
	{
		$selitems = myquery("SELECT SUM(game_items_factsheet.weight) AS weight FROM game_items,game_items_factsheet WHERE game_items.priznak=4 AND game_items.item_id=game_items_factsheet.id AND game_items.user_id=".$user_id." AND game_items_factsheet.type<>13 AND (game_items.town=".$town." OR ".$town."=0) GROUP BY game_items.user_id");
		myquery("UPDATE game_items SET priznak=0 WHERE priznak=4 AND user_id=".$user_id." AND (game_items.town=".$town." OR ".$town."=0) ");
		$weight = 0;
		if (mysql_num_rows($selitems)) 
		{
			$weight += mysql_result($selitems,0,0);			
		} 
	}
	//возвращаем эликсиры из хранилищ в его инвентарь
	if ($build_id==0 OR ($build_id>=13 AND $build_id<=16))//Удаляем Хранилища Эликсиров
	{
		$selitems = myquery("SELECT SUM(game_items_factsheet.weight) AS weight FROM game_items,game_items_factsheet WHERE game_items.priznak=4 AND game_items.item_id=game_items_factsheet.id AND game_items.user_id=$user_id AND game_items_factsheet.type=13 AND (game_items.town=".$town." OR ".$town."=0) GROUP BY game_items.user_id");
		myquery("UPDATE game_items SET priznak=0 WHERE priznak=4 AND user_id=$user_id AND (game_items.town=".$town." OR ".$town."=0) ");
		$weight = 0;
		if (mysql_num_rows($selitems)) 
		{
			$weight += mysql_result($selitems,0,0);			
		} 
	}
	//возвращаем ресурсы из хранилища
	if ($build_id==0 OR ($build_id>=9 AND $build_id<=12))//Удаляем хранилище ресурсов
	{
		$hransel = myquery("SELECT crm.id, crm.col FROM craft_resource_market crm WHERE crm.user_id=".$user_id." AND crm.priznak=1 AND (crm.town=".$town." OR ".$town."=0)");
		$weight=0;
		if ($hransel!=false AND mysql_num_rows($hransel)>0)
		{
			while ($hran = mysql_fetch_array($hransel))
			{
				$Res = new Res(0, 0, $user_id);
				$Res->take_house(0, $hran['id'], (int)$hran['col']);
			}
		}				
	}
	
	//удаляем и снимаем коня
	if ($build_id==0 OR ($build_id>=6 AND $build_id<=8))//Удаляем стойла
	{
		myquery("DELETE FROM game_users_horses WHERE user_id=".$user_id." AND used=0 AND (town=".$town." OR ".$town."=0) ");	   
	}
	
	// Обновим инвентарь игрока
	if ($weight > 0) 
	{
		myquery("UPDATE game_users SET CW=CW+".$weight." WHERE user_id=".$user_id."");
		myquery("UPDATE game_users_archive SET CW=CW+".$weight." WHERE user_id=".$user_id.""); 
	}
	
	// Удаляем дома/постройки игрока за задолженность			
	if ($build_id==0)
	{
		//Удаляем все постройки+дом
		myquery("DELETE FROM houses_market WHERE user_id=".$user_id." AND (town_id=".$town." OR ".$town."=0)");				
		myquery("DELETE FROM houses_users WHERE user_id=".$user_id." AND (town_id=".$town." OR ".$town."=0)");
		if (mysql_num_rows(myquery("SELECT * FROM houses_users WHERE user_id=".$user_id."")) == 0)
		{
			myquery("DELETE FROM houses_nalog WHERE user_id=".$user_id."");
		}
	}
	else
	{
		//Удаляем конкретное здание/постройку 
		myquery("DELETE FROM houses_market WHERE user_id=$user_id AND build_id=$build_id AND (town_id=".$town." OR ".$town."=0) ");
		myquery("DELETE FROM houses_users WHERE user_id=$user_id AND build_id=$build_id AND (town_id=".$town." OR ".$town."=0) ");
	}
				
	//Вышлем игроку уведомление
	if ($build_id==0)
	{
		$theme = 'Все Ваши постройки были уничтожены!';
		$post = 'В связи с длительным отсутствием ремонта все Ваши постройки были уничтожены!';
	}	
	else
	{
		list($name) = mysql_fetch_array(myquery("SELECT name FROM houses_templates WHERE id = ".$build_id." "));
		$theme = 'Ваша постройка <b>'.$name.'</b> была уничтожена!';
		$post = 'В связи с длительным отсутствием ремонта Ваша постройка <b>'.$name.'</b> была уничтожена!';
	}
	myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time, folder) VALUES ('".$user_id."', '0', '".$theme."', '".$post."','0','".time()."',1)");	
}

//Работа с налогами и ремонтами дома
if ($da['mday']==1)
{
    myquery("UPDATE game_cron_log SET step='14. Удаление неотремонтированных зданий', timecron=".time()." WHERE id=$idcronlog");
	//проверим и удаляем неотремонтированные здания
	$sel = myquery("SELECT build_id, user_id, town_id FROM houses_users WHERE type>1 AND ((doska_repair>doska AND doska>0) OR (stone_repair>stone AND stone>0)) ");
	while ($ch=mysql_fetch_array($sel))
	{
		$user_id = $ch['user_id'];
		delete_house($user_id, $ch['build_id'], $ch['town_id']);
	}

	myquery("UPDATE game_cron_log SET step='15. Удаление домов должников', timecron=".time()." WHERE id=$idcronlog");
	//проверим сумму просрочки
    $sel = myquery("SELECT SUM(houses_nalog.nalog-houses_nalog.pay) AS summa, houses_nalog.user_id, houses_users.square AS square FROM houses_nalog,houses_users WHERE houses_users.user_id=houses_nalog.user_id AND houses_users.type=1 GROUP BY houses_nalog.user_id HAVING summa>=(square*700)");	
	while ($ch=mysql_fetch_array($sel))
	{
		$user_id = $ch['user_id'];
		delete_house($user_id, 0, 0);
	}
		
	myquery("UPDATE game_cron_log SET step='16. Начисление налога', timecron=".time()." WHERE id=$idcronlog");
	myquery("INSERT INTO houses_nalog (user_id,nalog,nalog_time)
			SELECT hu.user_id, @nalog := SUM(hu.square)*50, ".mktime(23,59,59,$da['mon'],$da['mday'],$da['year'])."
			FROM houses_users hu WHERE hu.type=1 GROUP BY user_id
			ON DUPLICATE KEY UPDATE houses_nalog.nalog=houses_nalog.nalog+@nalog");
	
	myquery("UPDATE game_cron_log SET step='17. Начисление ремонта', timecron=".time()." WHERE id=$idcronlog");
	myquery("UPDATE houses_users SET stone_repair=stone_repair+stone*0.1, doska_repair=doska_repair+doska*0.1");		
	
	myquery("UPDATE game_cron_log SET step='18. Рассылка уведомлений о налоге', timecron=".time()." WHERE id=$idcronlog");    
	//Разошлём уведомления о том, что начислен налог на дом
    myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time, folder)
	         SELECT hu.user_id, 0, 'Начислен налог на дом', concat('Сумма Вашего налога за дом составляет <b>', round(SUM(hn.nalog-hn.pay),2), '</b> монет'), '0','".time()."', 1			 
	         FROM houses_nalog hn, houses_users hu WHERE hu.user_id=hn.user_id AND hu.type=1 GROUP BY hu.user_id");		
	
	myquery("UPDATE game_cron_log SET step='19. Очищение месячных посещений', timecron=".time()." WHERE id=$idcronlog");
	myquery("UPDATE game_users_data SET month_visits=0");    
}

myquery("UPDATE game_cron_log SET step='20. Удаление ботов', timecron=".time()." WHERE id=$idcronlog");
myquery("DELETE FROM game_npc WHERE for_user_id NOT IN (SELECT user_id FROM game_users) AND for_user_id>0");

myquery("UPDATE game_cron_log SET step='21. Удаление шаблонов ботов', timecron=".time()." WHERE id=$idcronlog");
$sel_templ = myquery("SELECT npc_id FROM game_npc_template WHERE to_delete>0");
while ($templ = mysql_fetch_array($sel_templ))
{
	$count = mysql_result(myquery("SELECT COUNT(*) FROM game_npc WHERE npc_id=".$templ['npc_id'].""),0,0);
	if ($count==0)
	{
		myquery("DELETE FROM game_npc_template WHERE npc_id=".$templ['npc_id']."");
	}
}

myquery("UPDATE game_cron_log SET step='22. Очистка логов боев 2', timecron=".time()." WHERE id=$idcronlog");
myquery("DELETE FROM game_combats_log_data WHERE boy NOT IN (SELECT DISTINCT boy FROM game_combats_log)");


myquery("UPDATE game_cron_log SET step='23.Очищение статистики денег и опыта', timecron=".time()." WHERE id=$idcronlog");
myquery("DELETE FROM game_users_stat_gp WHERE game_users_stat_gp.timestamp<unix_timestamp(NOW() - INTERVAL 6 MONTH)");
myquery("DELETE FROM game_users_stat_exp WHERE game_users_stat_exp.timestamp<unix_timestamp(NOW() - INTERVAL 6 MONTH)");

myquery("UPDATE game_cron_log SET step='24. Управление лошадьми', timecron=".time()." WHERE id=$idcronlog");
myquery("UPDATE game_users_horses SET life=GREATEST(0,life-1), golod=golod+1 WHERE used=1");

myquery("UPDATE game_cron_log SET step='25. Удаление игроков ПСЖ', timecron=".time()." WHERE id=$idcronlog");
$users_psg=myquery("SELECT user_id FROM game_users_psg WHERE banned_date<=".(time()-60*60*24*28)."");
$i=0;
while (list($id)=mysql_fetch_array($users_psg))
{	
	$check_items=myquery("select id, user_id from game_items where user_id='".$id."' and ((kleymo=2 and kleymo_id<>'".$id."') or kleymo=1)");
	if (mysql_num_rows($check_items)>0)
	{
		while($item=mysql_fetch_array($check_items))
		{
			$Item = new Item();
			$Item->kleymo_return_from($item['id'],$item['user_id']);
		}
	}	
	delete_user($id);
	$i++;	
}

myquery("UPDATE game_cron_log SET step='26. Удаление игроков', timecron=".time()." WHERE id=$idcronlog");
$i=0;
$sel = myquery("SELECT game_users_archive.user_id, game_users_archive.name FROM game_users_archive,game_users_data WHERE game_users_archive.user_id=game_users_data.user_id AND game_users_archive.clevel=0 AND game_users_archive.exp<1000 AND game_users_data.last_visit>=".(time()-7*24*60*60)."");
while (list($id, $name) = mysql_fetch_array($sel))
{
	delete_user($id, $name);
	$i++;
}

if ($da['mday']==1 and ($da['mon']==3 or $da['mon']==6 or $da['mon']==9 or $da['mon']==12))
{
	myquery("UPDATE game_cron_log SET step='27. Обновление сезонных предметов', timecron=".time()." WHERE id=$idcronlog");
	$next = $da['mon'];
	$cur = $next-3 ;
	if ($cur<=0) $cur = $cur+12;
	$mas[3][1]=1249;	$mas[3][2]=1250;	$mas[3][3]=1251;
	$mas[6][1]=1266;	$mas[6][2]=1267;	$mas[6][3]=1268;
	$mas[9][1]=1237;	$mas[9][2]=1238;	$mas[9][3]=1239;
	$mas[12][1]=1272;	$mas[12][2]=1273;	$mas[12][3]=1274;
	
	$check=myquery("SELECT id, user_id FROM game_items WHERE used>0 AND priznak=0 AND item_id in ('".$mas[$cur][1]."', '".$mas[$cur][2]."', '".$mas[$cur][3]."')");
	if (mysql_num_rows($check)>0)
	{
		while ($item=mysql_fetch_array($check))
		{
			$Item = new Item();
			$Item->down($item['id'], $item['user_id']);
		}
	}	
	list($weight)=mysql_fetch_array(myquery("SELECT weight FROM game_items_factsheet WHERE id = '".$mas[$cur][1]."' "));
	myquery("UPDATE game_users gu SET gu.CW=gu.CW - '".$weight."'*(SELECT count(*) 
			 FROM game_items gi WHERE gu.user_id = gi.user_id and gi.item_id in ('".$mas[$cur][1]."', '".$mas[$cur][2]."', '".$mas[$cur][3]."') and gi.priznak = 0)
			");
	myquery("UPDATE game_users_archive gu SET gu.CW=gu.CW - '".$weight."'*(SELECT count(*) 
			 FROM game_items gi WHERE gu.user_id = gi.user_id and gi.item_id in ('".$mas[$cur][1]."', '".$mas[$cur][2]."', '".$mas[$cur][3]."') and gi.priznak = 0)
			");
	myquery("DELETE FROM game_items WHERE item_id in ('".$mas[$cur][1]."', '".$mas[$cur][2]."', '".$mas[$cur][3]."')");
	
	myquery("UPDATE game_npc_drop SET items_id = '".$mas[$next][1]."' WHERE items_id = '".$mas[$cur][1]."' and drop_type = 1");
	myquery("UPDATE game_npc_drop SET items_id = '".$mas[$next][2]."' WHERE items_id = '".$mas[$cur][2]."' and drop_type = 1");
	myquery("UPDATE game_npc_drop SET items_id = '".$mas[$next][3]."' WHERE items_id = '".$mas[$cur][3]."' and drop_type = 1");	
}

myquery("UPDATE game_cron_log SET step='28. Очищение логов', timecron=".time()." WHERE id=$idcronlog");
myquery("DELETE FROM `game_items_deleted` WHERE action_time<adddate( now( ) , INTERVAL -3 MONTH) ");

myquery("UPDATE game_cron_log SET step='final', timecron=".time()." WHERE id=$idcronlog");
?>