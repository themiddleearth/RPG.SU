<?
if (function_exists("start_debug")) start_debug(); 

echo ('<style type="text/css">@import url("../style/global.css");</style>');
echo ('<img src="http://'.img_domain.'/gorod/bank/old_items.jpg"><br><hr>');
echo'<SCRIPT language=javascript src="/js/info.js"></SCRIPT><DIV id=hint  style="Z-INDEX: 0; LEFT: 0px; VISIBILITY: hidden; POSITION: absolute; TOP: 0px"></DIV>';

$quest_item_ids = "406,407,408,409,410";
if ($char['clan_id']==1)
	$quest_item_ids = "0";


$day_on_market = 60;

$is_clan_town = mysql_result(myquery("SELECT COUNT(*) FROM game_clans WHERE town='".$town."'"),0,0);

if ($is_clan_town != 0)
	$time_for_check = 0;
else
	$time_for_check = time() - $day_on_market * 24 * 60 * 60;

$dostup = "";
$dostup_clan=1;

$userban=myquery("select * from game_ban where user_id='".$user_id."' and type=2 and time>'".time()."'");
if (mysql_num_rows($userban))
{
	$userr = mysql_fetch_array($userban);
	$min = ceil(($userr['time'] - time()) / 60);
	$dostup = 'На тебя наложено ПРОКЛЯТИЕ на '.$min.' минут! Тебе запрещено пользоваться рынком!';
}

$race = myquery("SELECT race FROM game_har WHERE id='".$char['race']."'");
if (mysql_num_rows($race))
{
	list($race) = mysql_fetch_array($race);
	$race = 'torg_'.$race;
	$sel = myquery("SELECT * FROM game_gorod WHERE town='$town'");
	$gorod = mysql_fetch_array($sel);
	if (!isset($gorod[$race]))
		$dostup = "";
	elseif ($gorod[$race]!='1')
		$dostup = 'В этом городе твоей расе запрещен вход на рынок!';
}

if ($is_clan_town>0)
{
	if ($char['clan_items_old'] == '0')
	{
		$dostup = 'Глава твоего клана поставил тебе запрет на доступ к рынку клана!';
	}
	if ($char['clan_items_old'] == '2')
	{
		$dostup_clan = 2;
	}
}

if (!isset($_GET['do']))
	$do = '';
else
	$do = $_GET['do'];

switch($do)
{
	case 'confirm':
	case 'confirmres':
	case 'new_item';
		if($char['clevel'] <= 4)
			$dostup = "Продавать предметы на рынке разрешено только после 4-го уровня";
		break;
}

if($dostup != "")
{
	if ($do != "")
		echo('<table border="1" cellpadding="0" style="border-collapse: collapse" width="98%" bordercolor="777777" bgcolor="223344">
		<tr><td align=center><a href="town.php?option=7&do=new_item">Выставить на продажу</a> | <a href="town.php?option=7&do=view">Просмотреть</a></td></tr></table>');

	$img = 'http://'.img_domain.'/race_table/elf/table';
	echo('<table width=100% border="0" cellspacing="0" cellpadding="0"><tr><td width="1" height="1"><img src="'.$img.'_lt.gif"></td><td background="'.$img.'_mt.gif"></td><td width="1" height="1"><img src="'.$img.'_rt.gif"></td></tr>
		<tr><td background="'.$img.'_lm.gif"></td><td background="'.$img.'_mm.gif" valign="top" width="100%" height="100%"><br><center>'.$dostup.'<br />&nbsp;
		</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>');
}
else
{
	echo('<table border="1" cellpadding="0" style="border-collapse: collapse" width="98%" bordercolor="777777" bgcolor="223344">
	<tr><td align=center><a href="town.php?option=7&do=new_item">Выставить на продажу</a> | <a href="town.php?option=7&do=view">Просмотреть</a></td></tr></table>');
	if ($do == "")
		$do = 'view';

	switch($do)
	{
	// Выбор типа предметов на рынке
	case 'view';
		if ($dostup_clan==2)
		{
			$str_query = "SELECT DISTINCT game_items_factsheet.type FROM game_items,game_items_factsheet where game_items.user_id=$user_id and game_items.priznak=1 and game_items.town='$town' and game_items_factsheet.type<=97 and game_items.sell_time>'$time_for_check' AND game_items.post_to=0 AND game_items.item_id = game_items_factsheet.id ORDER BY game_items_factsheet.type";
		}
		else
		{
			$str_query = "SELECT DISTINCT game_items_factsheet.type FROM game_items,game_items_factsheet where game_items.priznak=1 and game_items.town='$town' and game_items_factsheet.type<=97 and game_items.sell_time>'$time_for_check' AND game_items.post_to=0 AND game_items.item_id = game_items_factsheet.id ORDER BY game_items_factsheet.type";
		}
		$pg = myquery($str_query);
		$sel_res = mysql_result(myquery("SELECT COUNT(*) FROM craft_resource_market WHERE priznak=0 AND town=$town AND price>0 AND col>0 AND sell_time>'$time_for_check'"),0,0);
		
		if (mysql_num_rows($pg) > 0 or $sel_res > 0)
		{
			echo '<center><font face=verdana size=2>Выбери тип предмета:</font><br><table width="200" cellspacing=3 cellpadding=1 border=1>';
			while (list($typ)=mysql_fetch_array($pg))
				echo '<tr height="20"><td align="center"><a href="http://'.domain_name.'/lib/town.php?option='.$option.'&do=viewtype&type='.$typ.'">'.type_str($typ).'</a></td></tr>';
			
			if ($sel_res  > 0)
				echo '<tr height="20"><td align="center"><a href="http://'.domain_name.'/lib/town.php?option='.$option.'&do=res">Ресурсы</a></td></tr>';
		}
		else
		{
			echo '<center><font face=verdana size=2>К сожалению, рынок пуст!</font></center>';
		}
		break;

	// Просмотр выбранного типа предметов на рынке
	case 'viewtype':
		echo "В инвентаре свободно место для <b>".max(0,($char['CC']-$char['CW']))."</b> кг.";
		$type = (int)$_GET['type'];
		
		if (!isset($_GET['page']) or $_GET['page']<1)
			$page = 1;
		else
			$page = (int)$_GET['page'];

		$line = 15;

		$player_kuzn = 0;
		if ($type != 13 &&  // Элики
			$type != 19 && // Метательное
			$type != 20 && // Схемы
			$type != 21 && // Стрелы
			$type != 22 && // Руна амулета
			$type != 97) // Прочее
		{
			$player_kuzn = mysqlresult(myquery("SELECT COUNT(*) FROM `game_users_crafts` WHERE  `user_id`=".$char['user_id']." AND `craft_index`=12 AND `profile`=1;"),0,0);
			if ($player_kuzn)
				$kuzn_border_by_level = 90-8*getCraftLevel($char['user_id'], 12);
		}
		
		if ($dostup_clan==2)
		{
			$pg = myquery("SELECT COUNT(*) FROM game_items WHERE user_id='".$user_id."' and priznak=1 and town='".$town."' and sell_time>'".$time_for_check."' AND post_to=0 and item_id IN (select id from game_items_factsheet where type='".$type."' AND item_id NOT IN (".$quest_item_ids."));");
		}
		else
		{
			$pg = myquery("SELECT COUNT(*) FROM game_items WHERE priznak=1 and town='".$town."' and sell_time>'".$time_for_check."' AND post_to=0 and item_id IN (select id from game_items_factsheet where type='".$type."' AND item_id NOT IN (".$quest_item_ids."));");
		}
		$allpage = ceil(mysql_result($pg,0,0) / $line);
		if ($allpage == 0) echo ('<meta http-equiv="refresh" content="1;url=town.php?option='.$option.'">');
		else
		{
			if ($page > $allpage) $page = $allpage;
			
			$href = '?option='.$option.'&do=viewtype&type='.(int)$_GET['type'].'&';

			echo('<center>Страница: ');
			show_page($page,$allpage,$href);

			echo('<br/><table width="98%" cellpadding="0" cellspacing="0" border="0" style="margin: 1px;"><tr><td align="center" valign="top" style="text-align: center; padding: 1px; word-spacing: 0;">');
			$n = 0;
			if ($dostup_clan==2)
			{
				$select=myquery("SELECT game_users_archive.name AS n1, game_users.name AS n2, game_items.* FROM game_items LEFT JOIN game_users ON game_items.user_id = game_users.user_id LEFT JOIN game_users_archive ON game_items.user_id = game_users_archive.user_id WHERE game_items.user_id='".$user_id."' and priznak=1 AND town='".$town."' AND sell_time>'".$time_for_check."' AND post_to =0 AND item_id IN (SELECT id FROM game_items_factsheet WHERE TYPE='".$type."')AND item_id NOT IN ('".$quest_item_ids."')ORDER BY sell_time DESC LIMIT ".(($page-1)*$line).", ".$line.";");
			}
			else
			{
				$select=myquery("SELECT game_users_archive.name AS n1, game_users.name AS n2, game_items.* FROM game_items LEFT JOIN game_users ON game_items.user_id = game_users.user_id LEFT JOIN game_users_archive ON game_items.user_id = game_users_archive.user_id WHERE priznak=1 AND town='".$town."' AND sell_time>'".$time_for_check."' AND post_to =0 AND item_id IN (SELECT id FROM game_items_factsheet WHERE TYPE='".$type."') AND item_id NOT IN ('".$quest_item_ids."') ORDER BY sell_time DESC LIMIT ".(($page-1)*$line).", ".$line.";");
			}
			while ($items=mysql_fetch_array($select))
			{
				$n++;
				$Item = new Item($items['id']);
				echo('
				<table cellpadding="0" cellspacing="0" style="float: left; padding: 0px; margin: 3px;" width="240px">
				<tr>
					<td style="width:7px;"><img src="http://'.img_domain.'/nav/quote/quote_ul.gif" width="7" height="7" border="0" alt=""></td>
					<td background="http://'.img_domain.'/nav/quote/quote_tp.gif"><img src="http://'.img_domain.'/nav/x.gif" width="1" height="1" border="0"></td>
					<td style="width:7px;"><img src="http://'.img_domain.'/nav/quote/quote_ur.gif" width="7" height="7" border="0" alt=""></td>
				</tr>
				<tr>
				<td style="width:7px;" background="http://'.img_domain.'/nav/quote/quote_lt.gif"><img src="http://'.img_domain.'/nav/x.gif" width="1" height="1" border="0"></td>
				<td style="padding: 2px;">
				<center><font color="lightgray"><b><a href=town.php?option='.$option.'&do=purchase&it='.$items["id"].'>'.$Item->fact['name'].'</a></b></font></center>
				<hr color="#5A5A5A" style="clear: both;"/>
				<a href=town.php?option='.$option.'&do=purchase&it='.$items["id"].'>');
				$Item->hint(0,0,'<span style="float: left;" ');
				ImageItem($Item->fact['img'], 0, $Item->item['kleymo'],"", $Item->fact['name'], $Item->fact['name']);

				if ($items['n1'] != '')
					$sname = preg_replace("/\\s/","&nbsp;",$items['n1']);
				elseif ($items['n2'] != '')
					$sname = preg_replace("/\\s/","&nbsp;",$items['n2']);
				else
					$sname = "<i>неизветсен</i>";

				echo ('</span></a>');
				echo ('<span style="margin-left: 10px; float: left;">Продавец:</span><span style="float: right;"><font face="verdana" color="#ff0000"><b>'.$sname.'</b></font></span><br/>');
				echo ('<span style="margin-left: 10px; float: left;">Цена:</span> <span style="float: right;"><font face="verdana" color="#FFFF00"><b>'.$items["item_cost"].'</b></font> '.pluralForm($items['item_cost'],'монета','монеты','монет').'</span><br/>');

				if ($Item->counted_item())
					echo ('<span style="margin-left: 10px; float: left;">Количество предметов:</span> <span style="float: right;"><font face="verdana" color="#FFFF00"><b>'.$items["count_item"].'</b></font></span><br/>');

				if (!$is_clan_town)
				{
					$left_time = $items['sell_time'] + $day_on_market * 24 * 60 * 60;
					$left_time = date('d.m.Y : H:i:s', $left_time);
					echo ('<span style="margin-left: 10px; float: left;">Будет снят с рынка:</span><span style="float: right;"><font face="verdana">'.$left_time.'</font></span><br/>');
					if ($player_kuzn)
					{
						$color = "#FF0000";
						if ($Item->item['item_uselife'] == 100)
							$color = "#00FF00";
						elseif ($Item->item['item_uselife'] >= $kuzn_border_by_level)
							$color = "#FFFF00";
						echo ('<hr color="#5A5A5A" style="clear: both;"/><span style="margin-left: 2px; float: left;">Прочность:</span> <span style="float: right;"><font face="verdana" color="'.$color.'">'.$Item->item['item_uselife'].'%</font></span>
							<br style="clear: both;"/><span style="margin-left: 2px; float: left;">Долговечность:</span> <span style="float: right;"><font face="verdana">'.$Item->item['item_uselife_max'].' / '.$Item->fact['item_uselife_max'].'</font></span>');
					}
				}
				else
				{
					if ($player_kuzn)
					{
						$color = "#FF0000";
						if ($Item->item['item_uselife'] == 100)
							$color = "#00FF00";
						elseif ($Item->item['item_uselife'] >= $kuzn_border_by_level)
							$color = "#FFFF00";
						echo ('<hr color="#5A5A5A" style="clear: both;"/><span style="margin-left: 2px; float: left;">Прочность:</span> <span style="float: right;"><font face="verdana" color="'.$color.'">'.$Item->item['item_uselife'].'%</font></span>
							<br style="clear: both;"/><span style="margin-left: 2px; float: left;">Долговечность:</span> <span style="float: right;"><font face="verdana">'.$Item->item['item_uselife_max'].' / '.$Item->fact['item_uselife_max'].'</font></span>');
					}
					echo ('<br style="clear: both;"/><hr color="#5A5A5A"/><span style="margin-left:2px; float: left;">Описание:</span><span style="float: right; text-align: right;">'.$Item->getOpis()."</span>");
				}


				echo ('</td>
				<td style="width:7px;" background="http://'.img_domain.'/nav/quote/quote_rt.gif"><img src="http://'.img_domain.'/nav/x.gif" width="1" height="1" border="0"></td>
			</tr>
			<tr>
				<td style="width:7px;"><img src="http://'.img_domain.'/nav/quote/quote_dl.gif" width="7" height="7" border="0" alt=""></td> 
				<td background="http://'.img_domain.'/nav/quote/quote_bt.gif"><img src="http://'.img_domain.'/nav/x.gif" width="1" height="1" border="0"></td>
				<td style="width:7px;"><img src="http://images.rpg.su/nav/quote/quote_dr.gif" width="7" height="7" border="0" alt=""></td> 
			</tr>
		</table>');
				if ($is_clan_town && $n == 3)
				{
					$n = 0;
					echo ('<br style="clear: both;">');
				}
			}

			echo('</td></tr></table>');
			echo('Нажми на рисунок, чтобы купить предмет.<br/>');

			$href = '?option='.$option.'&do=viewtype&type='.$_GET['type'].'&';
			echo'Страница: ';
			show_page($page,$allpage,$href);
			$all=mysql_result($pg,0,0);
			echo'</font><br>На рынке '.$all.' '.pluralForm($all,'предмет','предмета','предметов').' этого типа.';

			if ($is_clan_town)
			{
				$max = 600;
				$pg = myquery("SELECT COUNT(*) FROM game_items where priznak=1 and town='$town' AND post_to=0");
				$count_now = mysql_result($pg,0,0);
				echo('<br/>Всего вещей на рынке: '.$count_now.'шт. Вместимость рынка: 600 вещей.');
			}
		}
		echo'</center>';
		break;

	// Просмотр выбранного ресурсов на рынке
	case 'res':
		echo "В инвентаре свободно место для <b>".max(0,($char['CC']-$char['CW']))."</b> кг.";
		$select = myquery("SELECT SUM(`col`*`weight`) AS `sum` FROM `craft_resource_market` RIGHT JOIN `craft_resource` ON `craft_resource_market`.`res_id` = `craft_resource`.`id` WHERE  `town` = ".$town.";");
		$sum_weight = mysql_fetch_array($select);
		$sum_weight = round($sum_weight['sum'], 2);

		if (!isset($_GET['page']))
			$page = 1;
		else
			$page = (int)$_GET['page'];

		if ($page<1)
			$page=1;

		$line = 15;

		if ($dostup_clan==2)
		{
			$pg = myquery("SELECT COUNT(*) FROM craft_resource_market where priznak=0 AND user_id=$user_id and town='$town' and price>0 and col>0 AND sell_time>'$time_for_check';");
		}
		else
		{
			$pg = myquery("SELECT COUNT(*) FROM craft_resource_market where priznak=0 AND town='$town' and price>0 and col>0 AND sell_time>'$time_for_check';");
		}
		$allpage = ceil(mysql_result($pg,0,0) / $line);
		
		if ($allpage == 0) echo ('<meta http-equiv="refresh" content="1;url=town.php?option='.$option.'">');
		else
		{
			if ($page > $allpage) $page = $allpage;
			
			$href = '?option='.$option.'&do=res&';
			echo('<center>Страница: ');
			show_page($page,$allpage,$href);

			echo('<br/><table width="98%" cellpadding="0" cellspacing="0" border="0" style="margin: 1px;"><tr><td align="center" valign="top" style="text-align: center; padding: 1px; word-spacing: 0;">');
			$n = 0;

			if ($dostup_clan==2)
			{
				$select = myquery("SELECT craft_resource_market.id, game_users_archive.name AS n1, game_users.name AS n2, craft_resource.name, craft_resource_market.town, craft_resource_market.col, craft_resource_market.price, craft_resource.img1, craft_resource.weight, craft_resource_market.opis, craft_resource_market.sell_time FROM craft_resource_market LEFT JOIN game_users ON craft_resource_market.user_id = game_users.user_id LEFT JOIN game_users_archive ON craft_resource_market.user_id = game_users_archive.user_id RIGHT JOIN craft_resource ON craft_resource_market.res_id = craft_resource.id WHERE craft_resource_market.user_id=$user_id and town =  '$town' AND price>0 AND col>0 ORDER BY res_id limit ".(($page-1)*$line).", $line;");
			}
			else
			{
				$select = myquery("SELECT craft_resource_market.id, game_users_archive.name AS n1, game_users.name AS n2, craft_resource.name, craft_resource_market.town, craft_resource_market.col, craft_resource_market.price, craft_resource.img1, craft_resource.weight, craft_resource_market.opis, craft_resource_market.sell_time FROM craft_resource_market LEFT JOIN game_users ON craft_resource_market.user_id = game_users.user_id LEFT JOIN game_users_archive ON craft_resource_market.user_id = game_users_archive.user_id RIGHT JOIN craft_resource ON craft_resource_market.res_id = craft_resource.id WHERE town =  '$town' AND price>0 AND col>0 ORDER BY res_id limit ".(($page-1)*$line).", $line;");
			}
			while ($row = mysql_fetch_array($select))
			{
				$n++;

				echo('
						<table cellpadding="0" cellspacing="0" style="float: left; padding: 0px; margin: 3px;" width="240px">
						<tr>
						<td style="width:7px;"><img src="http://'.img_domain.'/nav/quote/quote_ul.gif" width="7" height="7" border="0" alt=""></td>
						<td background="http://'.img_domain.'/nav/quote/quote_tp.gif"><img src="http://'.img_domain.'/nav/x.gif" width="1" height="1" border="0"></td>
						<td style="width:7px;"><img src="http://'.img_domain.'/nav/quote/quote_ur.gif" width="7" height="7" border="0" alt=""></td>
						</tr>
						<tr>
						<td style="width:7px;" background="http://'.img_domain.'/nav/quote/quote_lt.gif"><img src="http://'.img_domain.'/nav/x.gif" width="1" height="1" border="0"></td>
						<td style="padding: 2px;"><a href=town.php?option='.$option.'&do=purchase_res&it='.$row["id"].'>
						<span onmousemove="movehint(event)" onmouseover="showhint(\'<center><font color=#0000FF>Ресурс</font>\',\'<font color=000000><br />'.$row['name'].'<br/><br/></font>\',0,1,event)" onmouseout="showhint(\'\',\'\',0,0,event)" >
						<img style="float: left;" src="http://'.img_domain.'/item/resources/'.$row["img1"].'.gif" border="0" height="50" width="50" title="'.$row['name'].'" alt="'.$row['name'].'"></span></a>');
				if ($row['n1'] != '')
					$sname = preg_replace("/\\s/","&nbsp;",$row['n1']);
				elseif ($row['n2'] != '')
					$sname = preg_replace("/\\s/","&nbsp;",$row['n2']);
				else
					$sname = "<i>неизветсен</i>";

				echo ('<span style="margin-left: 10px; float: left;">Продавец:</span><span style="float: right;"><font face="verdana" color="#ff0000"><b>'.$sname.'</b></font></span><br/>
				<span style="margin-left: 10px; float: left;">Цена:</span> <span style="float: right;"><font face="verdana" color="#FFFF00"><b>'.$row["price"].'</b></font> '.pluralForm($row["price"],'монета','монеты','монет').'</span><br/>
				<span style="margin-left: 10px; float: left;">Количество:</span><span style="float: right;"><font face="verdana" color="#FFFF00"><b>'.$row["col"].'</b></font></span><br/>');

				if (!$is_clan_town)
				{
					$left_time = $row['sell_time'] + $day_on_market * 24 * 60 * 60;
					$left_time = date('d.m.Y : H:i:s', $left_time);
					echo ('<span style="margin-left: 10px; float: left;">Будет снят с рынка:</span><span style="float: right;"><font face="verdana">'.$left_time.'</font></span><br/>');
				}
				else
					echo ('<br style="clear: both;"><hr color="#5A5A5A"><span style="margin-left:2px; float: left;">Описание:</span><span style="float: right; text-align: right;">'.$row['opis']."</span>");

				echo('</td>
				<td style="width:7px;" background="http://'.img_domain.'/nav/quote/quote_rt.gif"><img src="http://'.img_domain.'/nav/x.gif" width="1" height="1" border="0"></td>
				</tr>
				<tr>
					<td style="width:7px;"><img src="http://'.img_domain.'/nav/quote/quote_dl.gif" width="7" height="7" border="0" alt=""></td> 
					<td background="http://'.img_domain.'/nav/quote/quote_bt.gif"><img src="http://'.img_domain.'/nav/x.gif" width="1" height="1" border="0"></td>
					<td style="width:7px;"><img src="http://images.rpg.su/nav/quote/quote_dr.gif" width="7" height="7" border="0" alt=""></td> 
				</tr>
				</table>');
				if ($is_clan_town && $n == 3)
				{
					$n = 0;
					echo ('<br style="clear: both;">');
				}
			}
			echo('</td></tr></table>');
			echo('Нажми на рисунок, чтобы купить ресурс.<br/>');
			echo'Страница: ';
			show_page($page,$allpage,$href);
			$all=mysql_result($pg,0,0);
			echo'</font><br>';

			$all = mysql_result($pg, 0, 0);
			if ($is_clan_town)
			{
				echo ("Всего на рынке ".$sum_weight."кг ресурсов. Вместимость: 10 000 кг.");
			}
		}
		echo'</center>';
		break;

	// Выбор предмета для продажи
	case 'new_item';
		// Общая таблица-рамка
		echo('<br/><table width="98%" cellpadding="0" cellspacing="0" border="0" style="margin: 1px;"><tr><td align="center" valign="top" style="text-align: center; padding: 1px; word-spacing: 0;">');

		// Предметы в инвентаре
		$selec=myquery("select game_items.id,game_items_factsheet.img,game_items_factsheet.name from game_items,game_items_factsheet where game_items.user_id='$user_id' and (game_items.ref_id=0 OR game_items_factsheet.type IN (12,13,14)) and game_items.used=0 and game_items_factsheet.type<=97 and game_items.item_for_quest=0 and game_items.priznak=0 and game_items.item_id=game_items_factsheet.id and game_items.personal=0 ORDER BY game_items_factsheet.type ASC, game_items.id ASC;");
		while ($row=mysql_fetch_array($selec))
		{
			echo('
	<table cellpadding="0" cellspacing="0" style="float: left; padding: 0px; margin: 3px;">
		<tr>
			<td style="width:7px;"><img src="http://'.img_domain.'/nav/quote/quote_ul.gif" width="7" height="7" border="0" alt=""></td>
			<td background="http://'.img_domain.'/nav/quote/quote_tp.gif"><img src="http://'.img_domain.'/nav/x.gif" width="1" height="1" border="0"></td>
			<td style="width:7px;"><img src="http://'.img_domain.'/nav/quote/quote_ur.gif" width="7" height="7" border="0" alt=""></td> 
		</tr>
		<tr>
			<td style="width:7px;" background="http://'.img_domain.'/nav/quote/quote_lt.gif"><img src="http://'.img_domain.'/nav/x.gif" width="1" height="1" border="0"></td>
			<td style="padding: 2px;"><a href=town.php?option='.$option.'&do=confirm&it='.$row["id"].'>');
			$Item = new Item($row['id']);
			$Item->hint(0,0,'<span ');
			ImageItem($Item->fact['img'],0,$Item->item['kleymo'],"",$Item->fact['name'],$Item->fact['name']);
			echo ('</span></a></td>
			<td style="width:7px;" background="http://'.img_domain.'/nav/quote/quote_rt.gif"><img src="http://'.img_domain.'/nav/x.gif" width="1" height="1" border="0"></td>
		</tr>
		<tr>
			<td style="width:7px;"><img src="http://'.img_domain.'/nav/quote/quote_dl.gif" width="7" height="7" border="0" alt=""></td> 
			<td background="http://'.img_domain.'/nav/quote/quote_bt.gif"><img src="http://'.img_domain.'/nav/x.gif" width="1" height="1" border="0"></td>
			<td style="width:7px;"><img src="http://images.rpg.su/nav/quote/quote_dr.gif" width="7" height="7" border="0" alt=""></td> 
		</tr>
	</table>');
        	}

		// Ресурсы в инвентаре
		$selec=myquery("select craft_resource_user.*,craft_resource.name,craft_resource.img3 from craft_resource_user,craft_resource where craft_resource_user.user_id='$user_id' and craft_resource_user.col>0 and craft_resource.id=craft_resource_user.res_id ORDER BY `craft_resource_user`.`res_id` ASC");
		while ($row=mysql_fetch_array($selec))
		{
			echo('
	<table cellpadding="0" cellspacing="0" style="float: left; padding: 0px; margin: 3px;">
		<tr>
			<td style="width:7px;"><img src="http://'.img_domain.'/nav/quote/quote_ul.gif" width="7" height="7" border="0" alt=""></td>
			<td background="http://'.img_domain.'/nav/quote/quote_tp.gif"><img src="http://'.img_domain.'/nav/x.gif" width="1" height="1" border="0"></td>
			<td style="width:7px;"><img src="http://'.img_domain.'/nav/quote/quote_ur.gif" width="7" height="7" border="0" alt=""></td> 
		</tr>
		<tr>
			<td style="width:7px;" background="http://'.img_domain.'/nav/quote/quote_lt.gif"><img src="http://'.img_domain.'/nav/x.gif" width="1" height="1" border="0"></td>
			<td style="padding: 2px;"><a href=town.php?option='.$option.'&do=confirmres&it='.$row["id"].'>
			<span onmousemove="movehint(event)" onmouseover="showhint(\'<center><font color=#0000FF>Ресурс</font>\',\'<font color=000000><br />'.$row['name'].'<br/><br/></font>\',0,1,event)" onmouseout="showhint(\'\',\'\',0,0,event)" >
			<img src="http://'.img_domain.'/item/resources/'.$row["img3"].'.gif" border="0" height="50" width="50" title="'.$row['name'].'" alt="'.$row['name'].'"></span></a></td>
			<td style="width:7px;" background="http://'.img_domain.'/nav/quote/quote_rt.gif"><img src="http://'.img_domain.'/nav/x.gif" width="1" height="1" border="0"></td>
		</tr>
		<tr>
			<td style="width:7px;"><img src="http://'.img_domain.'/nav/quote/quote_dl.gif" width="7" height="7" border="0" alt=""></td> 
			<td background="http://'.img_domain.'/nav/quote/quote_bt.gif"><img src="http://'.img_domain.'/nav/x.gif" width="1" height="1" border="0"></td>
			<td style="width:7px;"><img src="http://images.rpg.su/nav/quote/quote_dr.gif" width="7" height="7" border="0" alt=""></td> 
		</tr>
	</table>');
		}

		echo('</td></tr></table>');
		echo('Нажми на рисунок, чтобы выставить предмет на продажу.');
		echo('<br><br><input type="button" value="Выйти" onClick=location.replace("town.php?option='.$option.'")>');

        	break;

	// Выбор цены и количества для продаваемого предмета
	case 'confirm':
		//Ограничение по заполненности рынка.
		if ($is_clan_town)
		{
			$max = 600;
			$pg=myquery("SELECT COUNT(*) FROM game_items where priznak=1 and town='$town' AND post_to=0");
		}
		else
		{
			$max = 60;
			$pg=myquery("SELECT COUNT(*) FROM game_items where priznak=1 and town='$town' and sell_time>'$time_for_check' AND post_to=0");
		}
		$count_now = mysql_result($pg, 0, 0);

		if ($count_now < $max)
		{
			$it=(int)$_GET['it'];
			$Item = new Item($it);
			if (!isset($_POST['see']))
			{
				$ar = $Item->confirm_market($is_clan_town);
			}
			else       
			{
				if (isset($_POST['cena']))
				{
					$cena = (double)$_POST['cena'];
					if (!isset($_POST['colitems']))
						$kol = 1;
					else
						// Проверка на максимум пачки внутри $Item->sell_market
						$kol = (int)$_POST['colitems'];					
					if ($is_clan_town>0)
					{
						$ar = $Item->sell_market($town,$cena,0,0,0,$kol,0);
						$Item->setOpis($_POST['opis']);
					}
					else
					{
						$ar = $Item->sell_market($town,$cena,0,0,0,$kol);
						$Item->delOpis();
					}
				}
				echo'<meta http-equiv="refresh" content="3;url=town.php?option='.$option.'">';
			}
		}
		else
			echo 'Все места на рынке для продажи заняты!';

		echo '<br><input type="button" value="Выйти" onClick=location.replace("town.php?option='.$option.'")>';
		break;

	case 'confirmres':
		if (!isset($_POST['sell']))
		{
			echo ('<form action="" method="post">');
			$it = (int)$_GET['it'];
			$sel = myquery("select * from craft_resource_user where id='$it' and user_id='$user_id'");
			while ($items = mysql_fetch_array($sel))
			{
				$res = mysql_fetch_array(myquery("SELECT * FROM craft_resource WHERE id=".$items['res_id'].""));
				echo('<table border="0" cellpadding="1"><tr><td></td></tr></table><table border="1" cellpadding="0" style="border-collapse: collapse" width="98%" bordercolor="777777" bgcolor="223344" align=center><tr><td>');
				echo('<table cellpadding="0" cellspacing="4" border="0"><tr><td valign="left"><div align="center"><img src="http://'.img_domain.'/item/resources/' . $res['img3'] . '.gif" width="50" height="50" border="0" alt=""><br><font color="#ffff00">' . $res['name'] . '</font></div></td><td valign="top"><div align="left"><img src="http://'.img_domain.'/nav/x.gif" width="0" height="0" hspace="40" border="0"><br>
				Вес за 1 ед: ' .round($res['weight'], 3). '<br><br>
				<br>Количество:<input name="col" type="text" size=4 value=1> единиц из <b><font color=red>'.$items['col'].'</font></b><br><br>
				<br>За сумму:<input name="cena" type="text" size=4 value=1> золотых (за все кол-во)<br><br>
				<font color="#ffff00"><b><u>За аренду торгового места на рынке ты '.echo_sex('должен','должна').' будешь заплатить 8% от цены предмета!</u></b></font><br><br>');

                		if ($is_clan_town)
					echo('Описание:<br><textarea name="opis_res" cols="15" rows="3"></textarea><br>');
                		echo('<input name="sell" type="submit" value=Выставить>');
				echo('</div></td></tr></table>');
				echo('</td></tr></table></form>');
			}
			echo'<br><input type="button" value="Выйти" onClick=location.replace("town.php?option='.$option.'&do=new_item")>';
		}
		else
		{
			$it = (int)$_GET['it'];
			$col = (int)$_POST['col'];
			$cena = (int)$_POST['cena'];
			if ($col <= 0)
			{
				echo'Введено неправильное количество<meta http-equiv="refresh" content="1;url=town.php?option='.$option.'">';
			}
			elseif ($cena<=0 or $cena>9999)
			{
				echo ('Введена неправильная сумма<meta http-equiv="refresh" content="1;url=town.php?option='.$option.'">');
			}
			else
			{
				if ($is_clan_town) 
				{
					$max_weight = 10000;
					$opis_res = $_POST['opis_res'];
				}
				else 
				{
					$max_weight = 1000;
					$opis_res = '';	
				}				
				$Res = new Res();
				$Res->sell_market($it, $town, $col, $cena, $opis_res, $max_weight);
				echo $Res->message;
				echo '<meta http-equiv="refresh" content="3;url=town.php?option='.$option.'">';
			}			
		}
		break;

	case 'purchase':
		$it = (int)$_GET['it'];
		$Item = new Item($it);

		if ($Item->item['town'] != $town)
			break;
		if ($dostup_clan==2 and $Item->item['user_id'] != $user_id)
		{
			echo 'Хотели купить неположенный предмет? Ну-ну!';
			break;
		}
			
		$ar = $Item->buy_market();

		if ($ar[0] > 0)
		{
			$town_select = myquery("select rustown from game_gorod where town='$town'");
			list($rustown)=mysql_fetch_array($town_select);
			$userid=$Item->getItem('user_id');

			$selname = myquery("SELECT name FROM game_users WHERE user_id=$userid");
			if (!mysql_num_rows($selname))
				$selname = myquery("SELECT name FROM game_users_archive WHERE user_id=$userid"); 
			list($name) = mysql_fetch_array($selname); 
			myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time, folder) VALUES ('$user_id', '0', 'Рынок: Ты ".echo_sex('купил','купила')." предмет ".mysql_real_escape_string($ar[1])." у игрока ".mysql_real_escape_string($name)."', 'Ты ".echo_sex('купил','купила')." предмет ".mysql_real_escape_string($ar[1])." выставленный на продажу на рынке в ".mysql_real_escape_string($rustown)." у игрока ".mysql_real_escape_string($name)." за ".$ar[0]." ".pluralForm($ar[0],'монету','монеты','монет').". Комментарий к предмету - ".mysql_real_escape_string($ar[2])."','0','".time()."',4)");
			if ($user_id <> $userid)
			{
				myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time, folder) VALUES ('$userid', '0', 'Рынок: Твоя вещь ".mysql_real_escape_string($ar[1])." куплена игроком ".mysql_real_escape_string($char['name'])."', 'Твоя вещь ".mysql_real_escape_string($ar[1]).", выставленная на продажу на рынке в ".mysql_real_escape_string($rustown).", куплена ".mysql_real_escape_string($char['name'])." за ".$ar[0]." ".pluralForm($ar[0],'монету','монеты','монет').". Комментарий к предмету - ".mysql_real_escape_string($ar[2])."','0','".time()."',4)");
			}
			save_stat($user_id,'',$town,9,'',$ar[1],$userid,$ar[0],'','','','');
			echo('<br /><br /><font color="#FFFF00">Предмет <b>'.$Item->fact['name'].'</b> куплен</font>');
		}
		if ($dostup_clan==2)
		{
			$str_query = "SELECT DISTINCT game_items_factsheet.type FROM game_items,game_items_factsheet where game_items.user_id=$user_id and game_items.priznak=1 and game_items.town='$town' and game_items_factsheet.type<=97 and game_items.sell_time>'$time_for_check' AND game_items.post_to=0 AND game_items.item_id = game_items_factsheet.id and game_items_factsheet.type=".$Item->fact['type']."";
		}
		else
		{
			$str_query = "SELECT DISTINCT game_items_factsheet.type FROM game_items,game_items_factsheet where game_items.priznak=1 and game_items.town='$town' and game_items_factsheet.type<=97 and game_items.sell_time>'$time_for_check' AND game_items.post_to=0 AND game_items.item_id = game_items_factsheet.id and game_items_factsheet.type=".$Item->fact['type']."";
		}
		$test=myquery($str_query);
		if (mysql_num_rows($test)>0)
		{
			echo ('<meta http-equiv="refresh" content="1;url=town.php?option='.$option.'&do=viewtype&type='.$Item->fact['type'].'">');
		}
		else
		{
			echo ('<meta http-equiv="refresh" content="1;url=town.php?option='.$option.'">');
		}
		break;

	case 'purchase_res':
		$it = (int)$_GET['it'];
		$Res = new Res ();
		$Res->buy_market(0, $it, $dostup_clan);	
		echo $Res->message;		
		if ($dostup_clan==2)
		{
			$sct = "select * from craft_resource_market where priznak=0 AND town='$town' and user_id=$user_id";
		}
		else
		{
			$sct = "select * from craft_resource_market where priznak=0 AND town='$town'";
		}
		$test=myquery($sct);
		if (mysql_num_rows($test)>0)
		{
			$url = '<meta http-equiv="refresh" content="1;url=town.php?option='.$option.'&do=res">';
		}
		else
		{
			$url = '<meta http-equiv="refresh" content="1;url=town.php?option='.$option.'">';
		}
		
		echo $url;		
		break;
	}
}
?>