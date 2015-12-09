<?php

if (function_exists("start_debug")) start_debug(); 

if ($_SERVER['PHP_SELF']!="/act.php")
{
	die();
}

if (isset($option) AND $option == 'chat')
{
   if (!empty($voice))
		{
			$voice = htmlspecialchars($voice);
			$voice = strip_tags($voice);
			$voice = mysql_real_escape_string($voice);
			$userban=myquery("select * from game_ban where user_id='".$char['user_id']."' and type=2 and time>'".time()."'");
			if (mysql_num_rows($userban))
			{
				echo 'На тебя наложено проклятие. Тебе запрещено разговаривать.';
				{if (function_exists("save_debug")) save_debug(); exit;}
			}
			$result = myquery("INSERT game_chat (name, map_name, map_xpos, map_ypos, contents, post_time) VALUES ('".$char['name']."', '".$char['map_name']."', ".$char['map_xpos'].", ".$char['map_ypos'].", '$voice', '" . time() . "')");
		}
}

//Отработаем переход
if (isset($_GET['chage']) and $_GET['chage']=='yes')
{
	$sel=myquery("select * from game_map where town!=0 and xpos='".$char['map_xpos']."' and ypos='".$char['map_ypos']."' and name='".$char['map_name']."' and to_map_name!=0");
	if (mysql_num_rows($sel))
	{
	    $gorod=mysql_fetch_array($sel);
		$sel=myquery("select town,name,text,clan,user,race,time,gp,id,timestart,exit_lab from game_obj where id='".$gorod['town']."'");
	    if(mysql_num_rows($sel)>0)
		{
			$user_race=mysql_result(myquery("SELECT name FROM game_har WHERE id=".$char['race'].""),0,0);
			list($town,$name,$text,$clan,$user,$race,$time,$gp,$obj_id,$timestart,$exit_lab)=mysql_fetch_array($sel);
			if($clan==0 or $clan=='') $clan=$char['clan_id'];
			if($user=='') $user=$char['user_id'];
			$result_items = myquery("SELECT * from game_wm WHERE user_id=$user_id AND type=5");
			if ($race=='' or mysql_num_rows($result_items)>0 ) $race = $user_race;
			
			$pass_time = true;
			
			if($gp=='') $gp='0';
			$a=explode(",",$clan);
			$b=explode(",",$user);
			
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
					$tme='ok';
				}
				else
				{
					$tme='no';
					$pass_time = false;
				}
			}
			elseif ($tme!='no')
			{
				$tme='ok';
			}

			$item_id_need = 0;
			$sel_nom = myquery("SELECT DISTINCT nomer FROM game_obj_require WHERE obj_id=$obj_id");
			if (mysql_num_rows($sel_nom))
			{
				$flag = 0;
				$str = '<i>Для прохода выставлены условия: <br>';
				$vsego_nom = mysql_num_rows($sel_nom);
				$cur_nom = 0;
				while (list($nom)=mysql_fetch_array($sel_nom))
				{
					$cur_nom++;
					$sel_cond = myquery("SELECT * FROM game_obj_require WHERE nomer=$nom AND obj_id=$obj_id");
					$vsego_cond = mysql_num_rows($sel_cond);
					$cur_cond = 0;
					$true_cond = 0;
					while ($cond = mysql_fetch_array($sel_cond))
					{
						$cur_cond++;
						switch ($cond['type'])
						{
							case 1:
								$str.='Уровень игрока ';
								$par = 'clevel';
							break;
							case 2:
								$str.='Количество наличных денег ';
								$par = 'GP';
							break;
							case 3:
								$str.='Наличие предмета ';
							break;
							case 34:
								$str.='Одетый предмет ';
							break;
							case 4:
								$par = 'vsadnik';
								$str.='Наличие коня ';
							break;
							case 5:
								$par = 'HP_MAX';
								$str.='Макс. здоровье ';
							break;
							case 6:
								$par = 'MP_MAX';
								$str.='Макс. мана ';
							break;
							case 7:
								$par = 'STM_MAX';
								$str.='Макс. энергия ';
							break;
							case 8:
								$par = 'STR';
								$str.='Сила игрока ';
							break;
							case 9:
								$par = 'NTL';
								$str.='Интеллект игрока ';
							break;
							case 10:
								$par = 'PIE';
								$str.='Ловкость игрока ';
							break;
							case 11:
								$par = 'SPD';
								$str.='Мудрость игрока ';
							break;
							case 12:
								$par = 'DEX';
								$str.='Выносливость игрока ';
							break;
							case 33:
								$par = 'VIT';
								$str.='Защита игрока ';
							break;                                
							case 19:
								$par = 'win';
								$str.='Количество побед ';
							break;
							case 20:
								$par = 'lose';
								$str.='Количество поражений ';
							break;
							case 21:
								$par = 'arcomage_win';
								$str.='Выиграно в Две Башни ';
							break;
							case 22:
								$par = 'arcomage_lose';
								$str.='Проиграно в Две Башни ';
							break;
							case 23:
								$par = 'maze_win';
								$str.='Пройдено лабиринтов ';
							break;                                
							case 101:
								$par = 'sklon';
								$str.='Склонность игрока ';
							break;
						}
						if ($cond['type']==3)
						{
							list($id_item) = mysql_fetch_array(myquery("SELECT id FROM game_items_factsheet WHERE name='".$cond['value']."'"));
							$item_id_need = $id_item;
							$str.=' - '.$cond['value'];
							$check_item = myquery("SELECT * FROM game_items WHERE user_id=$user_id AND priznak=0 AND item_id=$id_item");
							if (mysql_num_rows($check_item)>0) $true_cond++;
						}
						elseif ($cond['type']==34)
						{
							list($name_item) = mysql_fetch_array(myquery("SELECT name FROM game_items_factsheet WHERE id=".$cond['value'].""));
							$str.=' - '.$name_item;
							$check_item = myquery("SELECT COUNT(*) FROM game_items WHERE user_id=$user_id AND priznak=0 AND item_id='".$cond['value']."' AND used>0");
							if (mysql_num_rows($check_item)>0) $true_cond++;
						}
						elseif ($cond['type']==4)
						{
							list($name_horse) = mysql_fetch_array(myquery("SELECT nazv FROM game_vsadnik WHERE id=".$cond['value'].""));
							$str.=' - '.$name_horse;
							if ($char['vsadnik']==$cond['value']) $true_cond++;
						}
						elseif ($cond['type']==101)
						{
							if ($cond['value']==1)
							{
								$str.=' - нейтральная';
							}
							if ($cond['value']==2)
							{
								$str.=' - светлая';
							}
							if ($cond['value']==3)
							{
								$str.=' - темная';
							}
							if ($char['sklon']==$cond['value']) $true_cond++;
						}
						elseif ($cond['type']==100)
						{
							if (isset($_REQUEST['keyword']))
							{
								if (strtolower(trim($_REQUEST['keyword']))==strtolower(trim($cond['value'])))
								{
									$true_cond++;
									$pass = $cond['value'];
								}
								else
								{
									$str.= 'Ты '.echo_sex('указал','указала').' неправильное кодовое слово!';
								}
							}
							else
							{
								$ask_pass = 1;
							}
						}
						else
						{
							switch ($cond['condition'])
							{
								case 1:
									$str.=' <=';
									if ($char[$par]<=$cond['value']) $true_cond++;
								break;
								case 2:
									$str.=' <';
									if ($char[$par]<$cond['value']) $true_cond++;
								break;
								case 3:
									$str.=' =';
									if ($char[$par]==$cond['value']) $true_cond++;
								break;
								case 4:
									$str.=' >=';
									if ($char[$par]>=$cond['value']) $true_cond++;
								break;
								case 5:
									$str.=' >';
									if ($char[$par]>$cond['value']) $true_cond++;
								break;
								case 6:
									$str.=' <>';
									if ($char[$par]!=$cond['value']) $true_cond++;
								break;
							}
							$str.=' '.$cond['value'];
						}
						if ($cur_cond<$vsego_cond) $str.=' <strong>И</strong> ';
					}
					if ($cur_nom<$vsego_nom) $str.='<br><strong>ИЛИ</strong><br>';
					if ($true_cond==$vsego_cond OR ((isset($ask_pass)) AND ($true_cond+1==$vsego_cond))) $flag = 1;
				}
				$condition_text='<p>'.$str.'</i></p><br />';
				if ($flag==0) $tme='net';
			}

			while (list($val,$id)=each($a))
			{
				if($char['clan_id']==$id and $user_race==$race)
				{
					while (list($val,$id)=each($b))
					{
						if($char['user_id']==$id)
						{
							if (!isset($ask_pass) and $tme=='ok')
							{
								
								//$mes = 'X-'.$char['map_xpos'].' Y-'.$char['map_xpos'].' Chage='.$_GET['chage'];
								//$pismo = iconv("Windows-1251","UTF-8//IGNORE","<span style=\"font-style:italic;font-size:12px;color:gold;\">".$mes."</b></span>");
								//myquery("INSERT INTO game_log (`message`,`date`,`FROMm`,`too`,`ptype`) VALUES ('".mysql_real_escape_string($pismo)."',".time().",-1,0,0)");	
								
								if ($char['GP'] >= $gp or $gp==0)
								{
									if ($gp>0)
									{
										$result_usermap = myquery("UPDATE game_users SET GP=GP-$gp,CW=CW-'".($gp*money_weight)."' WHERE user_id='".$char['user_id']."' LIMIT 1");
										setGP($user_id,-$gp,18);
									}
									$result_usermap = myquery("UPDATE game_users_map SET map_name='".$gorod['to_map_name']."', map_xpos='".$gorod['to_map_xpos']."', map_ypos='".$gorod['to_map_ypos']."' WHERE user_id='".$char['user_id']."' LIMIT 1");
									//Закрываем портал в Туманные Горы после входа в него
									if ($gorod['town']==id_portal_tuman)
									{
										myquery("UPDATE game_map SET town=0, to_map_name=0, to_map_xpos=0, to_map_ypos=0 WHERE xpos='".$char['map_xpos']."' and ypos='".$char['map_ypos']."' and name='".$char['map_name']."'");
										//СПАВНИМ БОТОВ В ТУМАННЫХ ГОРАХ
										myquery("UPDATE game_npc SET time_kill=0 WHERE map_name=".id_map_tuman."");
										myquery("DELETE FROM game_npc WHERE npc_id=".id_npc_nepruha." AND map_name=".id_map_tuman."");
										//Ставим сундуки в конце коридора
										myquery("DELETE FROM game_items WHERE item_id=".item_id_sunduk." AND priznak=2 AND map_name=".id_map_tuman."");
										myquery("INSERT INTO game_items (item_id,priznak,map_name,map_xpos,map_ypos) VALUES (".item_id_sunduk.",2,".id_map_tuman.",7,6)");
										myquery("INSERT INTO game_items (item_id,priznak,map_name,map_xpos,map_ypos) VALUES (".item_id_sunduk.",2,".id_map_tuman.",0,5)");
										myquery("INSERT INTO game_items (item_id,priznak,map_name,map_xpos,map_ypos) VALUES (".item_id_sunduk.",2,".id_map_tuman.",7,4)");
										myquery("INSERT INTO game_items (item_id,priznak,map_name,map_xpos,map_ypos) VALUES (".item_id_sunduk.",2,".id_map_tuman.",0,3)");
										myquery("INSERT INTO game_items (item_id,priznak,map_name,map_xpos,map_ypos) VALUES (".item_id_sunduk.",2,".id_map_tuman.",7,2)");
										myquery("INSERT INTO game_items (item_id,priznak,map_name,map_xpos,map_ypos) VALUES (".item_id_sunduk.",2,".id_map_tuman.",0,1)");
										myquery("INSERT INTO game_items (item_id,priznak,map_name,map_xpos,map_ypos) VALUES (".item_id_sunduk.",2,".id_map_tuman.",7,0)");
										myquery("INSERT INTO game_items (item_id,priznak,map_name,map_xpos,map_ypos) VALUES (".item_id_sunduk.",2,".id_map_tuman.",0,7)");
									}
									//проверка на использование черного ключа
									if ($item_id_need==id_black_key)
									{
										$seldel_id = myquery("SELECT id FROM game_items WHERE item_id=".id_black_key." AND user_id=$user_id AND priznak=0 AND used=0 LIMIT 1");
										if (mysql_num_rows($seldel_id))
										{
											$del_id = mysqlresult($seldel_id,0,0);
											$Item = new Item($del_id);
											$Item->admindelete();
										}
									}
									if ($char['map_name']!=$gorod['to_map_name'] AND $char['map_name']!=id_map_tuman AND $gorod['to_map_name']!=id_map_tuman)
									{
										list($maze_to)=mysql_fetch_array(myquery("SELECT maze FROM game_maps WHERE id=".$gorod['to_map_name'].""));
										if ($maze_to==1)
										{
											$new_year_lab = array(838,839,840,841,842);
											$not_boss = 0;
											$not_npc=0;
											if (in_array($gorod['to_map_name'],$new_year_lab))
											{
												if ($char['map_name']!=18 and $char['map_name']!=5 and $gorod['to_map_name']-$char['map_name']!=1)
												{												
													$not_npc=1;
												}
												$not_boss = 1;
											}
											if ($gorod['to_map_name']==838 and ($char['map_name']==18 or $char['map_name']==5)) //Новый Год 2011. Удалим все подарки при непервом заходе в лабиринт
											{
												$present=myquery("DELETE FROM game_items WHERE user_id='".$char['user_id']."' and item_id=1248");														
											}
											if ($not_npc==0)
											{
												fill_maze_by_npc_for_user($gorod['to_map_name'],$user_id,$not_boss);
											}
										}
										list($maze_from,$exp_win,$gp_win,$maze_name)=mysql_fetch_array(myquery("SELECT maze,exp_maze,gp_maze,name FROM game_maps WHERE id=".$char['map_name'].""));
										$already = mysql_result(myquery("SELECT COUNT(*) FROM game_users_maze WHERE user_id=$user_id AND maze_id=".$char['map_name'].""),0,0);
										if ($maze_from==1 AND $exit_lab==1 AND $already==0)
										{
											myquery("UPDATE game_users SET maze_win=maze_win+1,EXP=EXP+$exp_win,GP=GP+$gp_win WHERE user_id=$user_id");
											$theme = 'Игроком пройден лабиринт: '.$maze_name;
											$message = 'Игрок [b]'.$char['name'].'[/b] прошел лабиринт: [i]'.$maze_name.'[/i]. Время выхода из лабиринта: '.date("d.m.Y H:i:s",time()).'.';
											myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, clan, time) VALUES ('28591', '$user_id', '$theme', '$message', '0','0',".time().")");
											myquery("INSERT INTO game_users_maze (user_id,maze_id) VALUES ($user_id,".$char['map_name'].")");
											setGP($user_id,$gp_win,28);
											setEXP($user_id,$exp_win,3);
										}
									}										    
									$char['map_name'] = $gorod['to_map_name'];
									$char['map_xpos'] = $gorod['to_map_xpos'];
									$char['map_ypos'] = $gorod['to_map_ypos'];
									$char['GP'] = $char['GP'] - $gp;
								}
								else
								{
									echo'Плата за проход <font color=ff0000><b>'.$gp.'</b></font> золотых! У тебя их НЕТ!';
								}
							}							
						}
					}
				}
			}
	    }
	}  	
}



if (!empty($reason))
{
	include('inc/template_reason.inc.php');
}
if(isset($_GET['prison_action']))
{	
	include('quest/inc/print.inc.php');
}

echo '<table cellpadding="0" cellspacing="0" border="0" width="100%" class=m background="http://'.img_domain.'/nav/image_01.jpg"><tr><td valign="top">';
$refresh = 30;
if ($user_id==2694)
{
	$refresh=120;
}
echo '<meta http-equiv="refresh" content="'.$refresh.';url=act.php?func=main">';

echo '<img src="http://'.img_domain.'/nav/game.gif" align=right>';

echo '<table cellpadding="0" cellspacing="0" border="0">
  <tr><td valign="top">';
include('inc/template_nav2.inc.php');
echo '</td><td valign="top" width="100%">';

if ($char['map_name']==map_sea_id)
{
	$sel=myquery("select * from game_port_bil where user_id='".$char['user_id']."' and stat='1'");
	if ($sel!=false AND mysql_num_rows($sel))
	{
		$q=mysql_fetch_array($sel);

		$sell=myquery("select * from game_port where id='".$q['bil']."'");
		$qq=mysql_fetch_array($sell);

		$kuda='<font color=#FFFF80>'.@mysql_result(@myquery("SELECT rustown FROM game_gorod WHERE town='".$qq['town_kuda']."'"),0,0);
		$map = @mysql_fetch_array(@myquery("SELECT * FROM game_map WHERE town='".$qq['town_kuda']."' and to_map_name=0"));
		$map_name = @mysql_result(@myquery("SELECT name FROM game_maps WHERE id='".$map['name']."'"),0,0);
		$kuda.='</font> ('.$map_name.' '.$map['xpos'].','.$map['ypos'].')';
		
		echo'<b>Ты плывешь в <font color=ff0000>'.$kuda.'</font>!<br>Прибытие ровно в: <font color=ff0000>'.$qq['dlit'].'</font><br>Сейчас:  <font color=ff0000>'.date("H:i").'</font>';

		$da = getdate($q['buydate']);
		$tm_bil = explode(":",$qq['dlit']);
		$datestamp = mktime($tm_bil[0],$tm_bil[1],0,$da['mon'],$da['mday'],$da['year']);
		if (time()>=$datestamp)
		{
			echo'<br><br><font color=ff0000 size=3><b>Ты '.echo_sex('прибыл','прибыла').'!!!</b></font>';
			$up=myquery("update game_users_map set map_name='".$map['name']."', map_xpos='".$map['xpos']."', map_ypos='".$map['ypos']."' where user_id='".$char['user_id']."'");
			$up=myquery("delete from game_port_bil where user_id='".$char['user_id']."'");
		}
	}
	else
	{
		myquery("UPDATE game_users_map SET map_name=18 WHERE user_id=$user_id");
	}
}

//перенос из боевого режима
include('inc/template_choose.inc.php');
//QuoteTable('open');
//include('inc/template_chat.inc.php');
//QuoteTable('close');
include("inc/template_around.inc.php");
include('inc/template_dropped.inc.php');

if (isset($_GET['getsunduk']) AND isset($_SESSION['getsunduk']))
{
	echo '<br /><br />';
	QuoteTable('open');
	echo $_SESSION['getsunduk'];
	QuoteTable('close');
	echo '<br /><br />';
}

//QuoteTable('open');
include('spt.php');
//QuoteTable('close');
echo '</td></tr></table>';

echo '</td><td width="172" valign="top">';
include('inc/template_stats.inc.php');
echo '</td></tr></table>';
if($char['delay_reason']!=8) set_delay_reason_id($user_id,1);
if (function_exists("save_debug")) save_debug(); 
?>