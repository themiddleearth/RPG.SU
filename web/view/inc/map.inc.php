<?

if (function_exists("start_debug")) start_debug(); 

if (!isset($_GET['view'])) $view = ''; else  $view = $_GET['view'];
if (!isset($_GET['sort'])) $sort = 'npc_name'; else $sort = $_GET['sort'];
$old_map = $map = $_GET['map'];

echo'<SCRIPT language=javascript src="http://'.domain_name.'/js/info.js"></SCRIPT><DIV id=hint  style="Z-INDEX: 0; LEFT: 0px; VISIBILITY: hidden; POSITION: absolute; TOP: 0px"></DIV>';

echo'<br><center>
<form name="form1" action="" method="GET">
<select name="view">';
if ($view=='shop')
		echo'<option value=shop selected>Торговцы</option>';
else
		echo'<option value=shop>Торговцы</option>';

if ($view=='gorod')
		echo'<option value=gorod selected>Города</option>';
else
		echo'<option value=gorod>Города</option>';

if ($view=='npc')
		echo'<option value=npc selected>NPC (боты)</option>';
else
		echo'<option value=npc>NPC (боты)</option>';

if ($view=='pr')
		echo'<option value=pr selected>Переходы</option>';
else
		echo'<option value=pr>Переходы</option>';

if ($view=='craft')
		echo'<option value=craft selected>Шахты</option>';
else
		echo'<option value=craft>Шахты</option>';
		
echo '</select>

<select name="map">';
$result = myquery("SELECT * FROM game_maps WHERE maze<>1 ORDER BY name");
while($map=mysql_fetch_array($result))
{
	echo '<option value="'.$map['id'].'"'; if($old_map==$map['id']) echo 'selected'; echo'>'.$map['name'].'</option>';
}
echo '</select>';

echo ' <input type="submit" value="&nbsp;&nbsp;&nbsp;ок&nbsp;&nbsp;&nbsp;"></form><br><br>';

@$map = $_REQUEST['map'];

if (!preg_match('/^[a-z]*$/i', $view))
{
	$view='npc';
}

$map = (int)$_GET['map'];
$sel_map = myquery("SELECT name FROM game_maps WHERE id=$map");
if ($sel_map==false OR mysql_num_rows($sel_map)==0)
{
	die("Карта не найдена");
}
$map_name = mysql_result($sel_map,0,0);

$sel_maze =  myquery("SELECT maze FROM game_maps WHERE id=$map");
$maze = 0;
if ($sel_maze!=false and mysql_num_rows($sel_maze)>0)
{
	list($maze) = mysql_fetch_array($sel_maze);
}
if ($maze!=1) 
{
	if($view=='npc')
	{
		if (!isset($_GET['napr'])) $napr="ASC"; else $napr = $_GET['napr'];
		if (!isset($_GET['sort']))  $sort = 'binary npc_name'; else $sort = $_GET['sort'];

    if (!in_array($sort,array('npc_max_hp','npc_max_mp','npc_level','npc_str','npc_dex','npc_pie','npc_vit','npc_spd','npc_ntl','npc_exp_max','npc_gold'))) $sort = 'binary npc_name';
		if ($sort=='npc_xpos' OR $sort=='npc_ypos') $sort='binary npc_name';
		$sortir = ''.$sort.' '.$napr.'';
		$npc_online = time();
		$npc=myquery("select game_npc.*,game_npc_template.* from game_npc,game_npc_template where game_npc.map_name='$map' and game_npc.view=1 AND game_npc.time_kill+game_npc_template.respawn<unix_timestamp() AND game_npc_template.npc_id=game_npc.npc_id ORDER BY $sortir");
		if(mysql_num_rows($npc))
		{
			 ?>
			 <script type="text/javascript">
			 function showhide(id)
			 {
				 elem = document.getElementById("opis"+id);
				 if (elem.style.display=="block")
				 {
					 elem.style.display="none";
				 }
				 else
				 {
					 elem.style.display="block";
				 }
			 }
			 </script>
			 <style>
			 .opis
			 {
				 cursor: url('http://images.rpg.su/nav/hand.cur'), pointer;
			 }
			 </style>
			 <?
			echo '<table border="0"><tr><td align="center" colspan="15"><hr color=555555 size=1 width=100%>Игровые боты: NPC<br></td></tr>';
			if ($napr=="ASC") $napr="DESC";
			else $napr="ASC";
			echo '<tr>
			<td><a href = "?view=npc&sort=npc_name&map='.$map.'&napr='.$napr.'">Имя</a></td>
			<td><a href = "?view=npc&sort=npc_max_hp&map='.$map.'&napr='.$napr.'">Жизни</a></td>
			<td><a href = "?view=npc&sort=npc_max_mp&map='.$map.'&napr='.$napr.'">Мана</a></td>
			<td><a href = "?view=npc&sort=npc_level&map='.$map.'&napr='.$napr.'">Ур.</a></td>
			<td><a href = "?view=npc&sort=npc_str&map='.$map.'&napr='.$napr.'">Сила</a></td>
			<td><a href = "?view=npc&sort=npc_dex&map='.$map.'&napr='.$napr.'">Вын-ть</a></td>
			<td><a href = "?view=npc&sort=npc_pie&map='.$map.'&napr='.$napr.'">Ловк.</a></td>
			<td><a href = "?view=npc&sort=npc_vit&map='.$map.'&napr='.$napr.'">Защита</a></td>
			<td><a href = "?view=npc&sort=npc_spd&map='.$map.'&napr='.$napr.'">Мудр.</a></td>
			<td><a href = "?view=npc&sort=npc_ntl&map='.$map.'&napr='.$napr.'">Интел.</a></td>
			<td><a href = "?view=npc&sort=npc_exp_max&map='.$map.'&napr='.$napr.'">Опыт</a></td>
			<td><a href = "?view=npc&sort=npc_gold&map='.$map.'&napr='.$napr.'">Мон.</a></td>
			<td align="right">Х</td>
			<td align="right">Y</td>
			<td></td>

			</tr>';
			while($row=mysql_fetch_array($npc))
			{
				
				echo'<tr>
				<td><font color=#EFEFEF>'.$row["npc_name"].'</td>
				<td><font color=#FF4646>'.$row["npc_max_hp"].'</td>
				<td><font color=#8EC0FD>'.$row["npc_max_mp"].'</td>
				<td><font color=#FFFB53>'.$row["npc_level"].'</td>
				<td><font color=#B7FFB7>'.$row["npc_str"].'&plusmn;'.$row["npc_str_deviation"].'</td>
				<td><font color=#FFFFB0>'.$row["npc_dex"].'&plusmn;'.$row["npc_dex_deviation"].'</td>
				<td><font color=#FF9FCF>'.$row["npc_pie"].'&plusmn;'.$row["npc_pie_deviation"].'</td>
				<td><font color=#FFA87D>'.$row["npc_vit"].'&plusmn;'.$row["npc_vit_deviation"].'</td>
				<td><font color=#B6A2EA>'.$row["npc_spd"].'&plusmn;'.$row["npc_spd_deviation"].'</td>
				<td><font color=#F9C093>'.$row["npc_ntl"].'&plusmn;'.$row["npc_ntl_deviation"].'</td>
				<td><font color=#8EC0FD>'.$row["EXP"].' ('.$row["npc_exp_max"].')</td>
				<td><font color=#FFFB53>'.$row["npc_gold"].'</td>
				<td align="right"><font color=#EEEEEE>';
				if ($row["npc_exp_max"]<=200)
				{
					echo $row["xpos"];
					echo '</td><td align="right"><font color=#FF7DFF>';
					echo $row["ypos"];
				}
				else  
				{
					echo ''.$row["xpos"]+1*$row["xpos_view"].'&plusmn;2';
					echo '</td><td align="right"><font color=#FF7DFF>';
					echo ''.$row["ypos"]+1*$row["ypos_view"].'&plusmn;2';
				}
				echo '</td><td>';
				if ($row['npc_opis']!='')
				{
					echo '<span class="opis" onclick="showhide('.$row["id"].')">Описание</span>';
				}
				echo '</td></tr>';
				if ($row['npc_opis']!='')
				{
					echo '<tr height="0"><td colspan="14" align="center" style="color:#C0FFC0;"><div id="opis'.$row['id'].'" style="display:none;">'.$row['npc_opis'].'</div></td></tr>';
				}
			}
			echo'</table>';
		}
		else
		{
			echo'Ничего не найдено';
		}
	}


	if($view=='shop')
	{
		echo '<table width=500 border=0 align=center><tr><td><hr color=555555 size=1 width=100%>Торговцы:<br></td></tr>';
		echo '<tr><td><b><font color=#FFFF00>Имя</font></b></td><td><b><font color=#FFFF00>Описание</font></b></td><td width="140"><b><font color=#FFFF00>X,Y</font></b></td></tr>';
		$shop=myquery("select * from game_shop where map=".$map." and view=1 ORDER BY BINARY name");
		if(mysql_num_rows($shop))
		{
			while($row=mysql_fetch_array($shop))
			{
				echo'<tr><td>'; 
	?><span onmousemove="movehint(event)" onmouseover="showhint('<font color=ff0000><b><?
				echo '<center><font color=#800000>'.$row["name"].'</font>';
				?></b></font>','<?
				echo '<font color=000000>';
				echo '<b><u>Функции:</u></b><br>';
				if ($row['prod']==1) echo 'Продажа вещей<br>';
				if ($row['remont']==1) echo 'Ремонт вещей<br>';
				if ($row['ident']==1) echo 'Идентификация вещей<br>';
				if ($row['kleymo']==1) echo 'Заклеймение вещей<br>';
				if ($row['prod']==1)
				{
					echo '<hr><font color=#0000FF>';
					if ($row['shlem']==1) echo 'Продает шлемы<br>';
					if ($row['oruj']==1) echo 'Продает оружие<br>';
					if ($row['dosp']==1) echo 'Продает доспехи<br>';
					if ($row['shit']==1) echo 'Продает щиты<br>';
					if ($row['pojas']==1) echo 'Продает пояса<br>';
					if ($row['mag']==1) echo 'Продает магию<br>';
					if ($row['ring']==1) echo 'Продает кольца<br>';
					if ($row['artef']==1) echo 'Продает артефакты<br>';
					if ($row['svitki']==1) echo 'Продает свитки<br>';
					if ($row['eliksir']==1) echo 'Продает эликсиры<br>';	
					if ($row['schema']==1) echo 'Продает схемы предметов<br>';
					if ($row['luk']==1) echo 'Продает луки<br>';
				echo '</font>';
				}
				echo '</font>';
				?>',0,1,event)" onmouseout="showhint('','',0,0,event)">
				<?php
				
				echo $row["name"].'</span></td><td>'.$row["privet"].'</td><td width="140">'.$map_name.' ('.$row["pos_x"].', '.$row["pos_y"].')';
				echo'</td></tr>';
			}
		}
		else
		{
			echo'<br>Ничего не найдено<br>';
		}
		echo '</table>';
	}


	if($view=='pr')
	{
		echo '<table width=500 border=0 align=center><tr><td><hr color=555555 size=1 width=100%>Переходы:<br></td></tr>';
		echo '<tr><td><b><font color=#FFFF00>Название</font></b></td><td width="140"><b><font color=#FFFF00>x, y</font></b></td><td><b><font color=#FFFF00>Описание</font></b></td></tr>';
		$shop=myquery("select * from game_map where name='".(int)$_GET['map']."' and town!=0 and to_map_name!=''");
		while($row=mysql_fetch_array($shop))
		{
			$sh=myquery("select name,text,clan,user,race,time,gp,timestart,view from game_obj where id='".$row["town"]."'");
			list($name,$text,$clann,$user,$race,$time,$gp,$timestart,$view_obj)=mysql_fetch_array($sh);
			if ($timestart!='')
			{
				$d = explode(" ",$timestart);
				$dat = explode(".",$d[0]);
				$tim = explode(":",$d[1]);
				$timestamp_open = mktime($tim[0],$tim[1],0,$dat[1],$dat[0],$dat[2]);
				if(time() < $timestamp_open)
				{
					$tme='no';
				}
				else
				{
					$tme='ok';
				}
			}
			else
			{
				$tme='ok';
			}
			
			if ($time!='' and $tme!='no')
			{
				$d = explode(" ",$time);
				$dat = explode(".",$d[0]);
				$tim = explode(":",$d[1]);
				$timestamp_open = mktime($tim[0],$tim[1],0,$dat[1],$dat[0],$dat[2]);
				if(time() <= $timestamp_open)
				{
					//echo'<span style="color:red;font-weight:800;">Проход закроется '.$time.'</span><br>';
					$tme='ok';
				}
				else
				{
					$tme='no';
				}
			}
			elseif ($tme!='no')
			{
				$tme='ok';
			}
			if ($user!='')
			{
			}
			elseif ($tme=='ok' and $view_obj==1)
			{
				echo'<tr><td>'.$name.'</td><td>'.$map_name.' ('.$row["xpos"].', '.$row["ypos"].')</td><td>';
				if ($clann!='') echo'Вход ограничен для кланов<br>';
				if ($user!='') echo'Вход ограничен для игроков<br>';
				if ($race!='') echo'Вход только для расы '.$race.'<br>';
				if ($time!='') echo'Врямя закрытия прохода '.$time.'<br>';
				if ($gp!=0) echo'Плата за вход '.$gp.' золотых<br>';
				if ($clann=='' and $user=='' and $race=='' and $gp=='' and $time=='' ) echo'Свободный проход<br>';
				echo'</td></tr>';
			}
		}
		echo '</table>';
	}


	if($view=='gorod')
	{
		echo '<table width=500 border=0 align=center><tr><td><hr color=555555 size=1 width=100%>Города:<br></td></tr>';		
		$check_gorod=myquery("SELECT gg.*, gm.xpos, gm.ypos, ggo.name as opt, gs.name as skill
		                        FROM game_map gm JOIN game_gorod gg ON gg.town = gm.town
								LEFT JOIN game_gorod_set_option ggso ON ggso.gorod_id=gg.town
								LEFT JOIN game_gorod_option ggo ON ggo.id=ggso.option_id
								LEFT JOIN game_gorod_skills ggs ON gg.town = ggs.gorod_id
								LEFT JOIN game_skills gs ON ggs.skill_id = gs.id
							   WHERE gm.name='".$map."' and gm.town!=0 and gm.to_map_name='' and gm.to_map_xpos='0' and gm.to_map_ypos='0' 							    
							     AND gg.view='1' and gg.rustown!='' 
								ORDER BY BINARY rustown, opt, skill");
		if (mysql_num_rows($check_gorod)==0)
		{
			echo 'Городов на карте не найдено!';
		}
		else
		{
			//Формирование массива с данными о городах
			$cur_gorod = 0;
			$i = 0;			
			while ($gorod=mysql_fetch_array($check_gorod))
			{
				if ($cur_gorod <> $gorod['town'])
				{
					$cur_gorod = $gorod['town'];
					$i++;
					$k_opt = 0; $k_sk = 0; $k_har = 0; $stop = 0;
					$option = ''; $skill = ''; $first_skill = '';
					$mas[$i]['name'] = $gorod['rustown'];
					$mas[$i]['xpos'] = $gorod['xpos'];
					$mas[$i]['ypos'] = $gorod['ypos'];
					if ($gorod['STR']==1) { $k_har++; $mas[$i]['har'][$k_har]='Сила'; }
					if ($gorod['NTL']==1) { $k_har++; $mas[$i]['har'][$k_har]='Интеллект'; }
					if ($gorod['PIE']==1) { $k_har++; $mas[$i]['har'][$k_har]='Ловкость'; }
					if ($gorod['VIT']==1) { $k_har++; $mas[$i]['har'][$k_har]='Защита'; }
					if ($gorod['DEX']==1) { $k_har++; $mas[$i]['har'][$k_har]='Выносливость'; }
					if ($gorod['SPD']==1) { $k_har++; $mas[$i]['har'][$k_har]='Мудрость'; }
				}
				if ($gorod['opt']<>'' and $gorod['opt']<>$option)
				{
					$option = $gorod['opt'];
					$k_opt++;
					$mas[$i]['option'][$k_opt]=$option;
				}
				if ($first_skill == $gorod['skill']) $stop = 1;
				if ($gorod['skill']<>'' and $gorod['skill']<>$skill and $stop==0)
				{					
					if ($skill == '') $first_skill = $gorod['skill'];
					$skill = $gorod['skill'];
					$k_sk++;
					$mas[$i]['skill'][$k_sk]=$skill;
				}
			}
			
			//Вывод данных о городах на экран
			$i = 1;
			echo '<table border="1" align="center">
				  <tr valign="top" align="center">
				  <td width="350"><b>Город</b></td>
				  <td width="150"><b>Характеристики</b></td>
				  <td width="250"><b>Здания</b></td>
				  <td width="250"><b>Специализации</b></td></tr>
				 ';
			while (isset($mas[$i]))
			{
				$k_opt = 1; $k_sk = 1; $k_har = 1;
				echo '<tr align="center" valign = "top">';
				echo '<td>'.$mas[$i]['name'].' (X-'.$mas[$i]['xpos'].', Y-'.$mas[$i]['ypos'].')</td>';
				echo '<td>';
				while (isset($mas[$i]['har'][$k_har]))
				{
					echo $mas[$i]['har'][$k_har].'<br>';
					$k_har++;
				}
				if ($k_opt==1) echo '';
				echo '</td>';
				
				echo '<td>';
				while (isset($mas[$i]['option'][$k_opt]))
				{
					echo $mas[$i]['option'][$k_opt].'<br>';
					$k_opt++;
				}
				if ($k_opt==1) echo '';
				echo '</td>';
				
				echo '<td>';
				while (isset($mas[$i]['skill'][$k_sk]))
				{
					echo $mas[$i]['skill'][$k_sk].'<br>';
					$k_sk++;
				}
				if ($k_sk==1) echo '';
				echo '</td>';
				echo '</tr>';
				$i++;
			}
			echo '</table>';
		}
	}

	if ($view=='craft')
	{
		$craft = myquery("SELECT craft_build_user.*,craft_build.* FROM craft_build_user,craft_build WHERE craft_build_user.map=$map AND craft_build_user.type=craft_build.id ORDER BY BINARY craft_build.name");    
		echo '<table width=500 border=0 align=center><tr><td><hr color=555555 size=1 width=100%>Шахты:<br></td></tr>';
		echo '<tr><td><b><font color=#FFFF00>Имя</font></b></td><td><b><font color=#FFFF00>Кол-во рабочих мест</font></b></td><td><b><font color=#FFFF00>Требование предмета</font></b></td><td width="140"><b><font color=#FFFF00>X,Y</font></b></td></tr>';
		if(mysql_num_rows($craft))
		{
			while($row=mysql_fetch_array($craft))
			{
				echo'<tr><td>'.$row["name"].'</span></td><td>'.$row["col"].'</td><td>'.mysqlresult(myquery("SELECT name FROM game_items_factsheet WHERE id=".$row['item'].""),0,0).'</td><td width="140">'.$map_name.' ('.$row["x"].', '.$row["y"].')</td></tr>';
			}
		}
		else
		{
			echo'<tr><td colspan=4>Ничего не найдено</td></tr>';
		}
		echo '</table>';
	}

}

if (function_exists("save_debug")) save_debug(); 

?>