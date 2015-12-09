<?php
require('inc/config.inc.php');
include('inc/lib.inc.php');
require_once('inc/db.inc.php');
if (!defined("MODULE_ID"))
{
	define("MODULE_ID", '5');
}
else
{
	die();
}
require('inc/lib_session.inc.php');

if (function_exists("start_debug")) start_debug(); 

$ms_vsadnik=8;
$vsadnik=5;

$ms_vsadnik2=15;
$vsadnik2=9;

$char_vsadnik=get_horse_level($char['vsadnik']);
$ms_vsad=get_vsad_level($char['vsadnik']);

$xpos = $char['map_xpos'];
$ypos = $char['map_ypos'];
$map = $char['map_name'];
list($dolina,$maze) = mysql_fetch_array(myquery("SELECT dolina,maze FROM game_maps WHERE id='".$map."'"));
if ($maze==1)
{
	$selmapmax = myquery("SELECT xpos,ypos FROM game_maze WHERE map_name=$map ORDER BY xpos DESC, ypos DESC LIMIT 1");
	$xmap_max = mysqlresult($selmapmax,0,0);
	$ymap_max = mysqlresult($selmapmax,0,1);  
}
else
{
	$selmapmax = myquery("SELECT xpos,ypos,type FROM game_map WHERE name=$map ORDER BY xpos DESC, ypos DESC LIMIT 1");
	$xmap_max = mysqlresult($selmapmax,0,0);
	$ymap_max = mysqlresult($selmapmax,0,1);  
	$type_map = mysqlresult($selmapmax,0,2); 
}

$CC_overload = $char['CW'];
if ($CC_overload < 0)
{
	$CC_overload = 0;
}


if ($char['PR'] <= 0)
{
	setLocation("act.php?func=main&reason=prana");
	{if (function_exists("save_debug")) save_debug(); exit;}
}

$da = getdate();

//Установим задержку
if ($user_time < $char['delay'])
{	
	$zelye_bodrosti = mysqlresult(myquery("SELECT COUNT(*) FROM game_obelisk_users WHERE user_id=".$user_id." AND type=3 AND time_end>UNIX_TIMESTAMP()"),0,0);
	//Персонаж стопнут
	if (isset($char['block']) and $char['block']==1)
	{
		setLocation("act.php?func=main&reason=block");
		{if (function_exists("save_debug")) save_debug(); exit;}
	}	
	elseif ($dolina==0 and $zelye_bodrosti==0)
	{
		//Задержка, вызванная наличием слабого коня
		// if ( ($char['vsadnik']<63 AND $char['reinc']<>0) )
		// {
			// setLocation("act.php?func=main&reason=koni");
			// {if (function_exists("save_debug")) save_debug(); exit;}
		// }
		//Перевес
		if ($char['CC']<$char['CW'])
		{
			setLocation("act.php?func=main&reason=weight");
			{if (function_exists("save_debug")) save_debug(); exit;}
		}		
	}
}

$del = max($char['DEX'],0) + max($char['STR'],0);
if ($del<=0) $del = 1;
$delay = $user_time + round($CC_overload / ($del) * 1.5);
if ($char['CC']<$char['CW'])
{
	$delay = $delay + ($char['CW']-$char['CC'])*10;    
}

$result_items = mysql_result(myquery("SELECT count(*) from game_wm WHERE user_id=$user_id AND type=1"),0,0);
if ($result_items>0)
{
	$delay = $user_time;
}

if ($maze==1)
{
	$new_xpos = $xpos;
	$new_ypos = $ypos;
	if (isset($_GET['toxpos']) AND isset($_GET['toypos']))
	{
		$napr = '';
		$map_maze = mysql_fetch_array(myquery("SELECT * FROM game_maze WHERE xpos=".$xpos." AND ypos=".$ypos." AND map_name=".$map.""));
		if ($_GET['toxpos']==$xpos-1 AND $_GET['toypos']==$ypos) $napr='left';
		if ($_GET['toxpos']==$xpos+1 AND $_GET['toypos']==$ypos) $napr='right';
		if ($_GET['toypos']==$ypos-1 AND $_GET['toxpos']==$xpos) $napr='up';
		if ($_GET['toypos']==$ypos+1 AND $_GET['toxpos']==$xpos) $napr='down';		
		if ($napr=='up' AND $map_maze['move_up']==1) $new_ypos = $ypos-1; 
		if ($napr=='down' AND $map_maze['move_down']==1) $new_ypos = $ypos+1; 
		if ($napr=='left' AND $map_maze['move_left']==1) $new_xpos = $xpos-1; 
		if ($napr=='right' AND $map_maze['move_right']==1) $new_xpos = $xpos+1; 
	}
}
else
{
	//составим массив разрешенных переходов 
	$par = 0;
	if ($ms_vsad>=$ms_vsadnik2 AND $char_vsadnik>=$vsadnik2 AND $char['dvij']>=3)
	{
		$par = 3;
	}
	elseif ($ms_vsad>=$ms_vsadnik AND $char_vsadnik>=$vsadnik AND $char['dvij']>=2)
	{
		$par = 2;
	}
	elseif ($ms_vsad>0 AND $char_vsadnik>0 AND $char['dvij']>=1)
	{
		$par = 1;
	}
	if ($char['map_name']==26) $par=0;
	if ($char['map_name']==33) $par=0;
	if ($char['map_name']==666) $par=0; 
	
	$map_row = array();
	$par++;
	$par++;
	$x0=0;
	$y0=0;
	for ($x=-$par+1;$x<=+$par-1;$x++)
	{
		$new_x = $xpos+$x;
		for ($y=-$par+1;$y<=+$par-1;$y++)
		{
			$delta_y = 0;
			if ($xpos%2!=0)
			{
				if ($new_x>$xpos)
				{
					if ($new_x%2==0)
					{
						$delta_y = -1;
					}
				}
				if ($new_x<$xpos)
				{
					if ($new_x%2==0)
					{
						$delta_y = -1;
					}
				}
			}
			$new_y = $ypos+$y+$delta_y;
			$map_row[$new_x][$new_y]=0;
			if( ( abs($x0-$x) <= ($par-1) ) && ( abs($y0-$y) <= ($par-1) ) and $new_x>=0 and $new_y>=0 and $new_x<=$xmap_max AND $new_y<=$ymap_max)
			{
				if((abs($x-$x0)<1+2*($y-$y0+$par-1)) && (($x0%2==0 && $y<0) || ($x0%2==1 && $y>0)))
				{
					$map_row[$new_x][$new_y]=1;    
				}
				elseif(($y==0) && (abs($x)<$par))
				{
					$map_row[$new_x][$new_y]=1;
				}
				elseif( ( abs($x-$x0)<2*($par-1+1)-2*($y-$y0) ) && ( ($x0%2==0 && $y>0) || ($x0%2==1 && $y<0) ) )
				{
					$map_row[$new_x][$new_y]=1; 	
				}
			}
		}
	} 
	
	$new_xpos = $xpos;
	$new_ypos = $ypos;
	if (isset($_GET['x']) AND isset($_GET['y']))
	{		 
		 if (isset($map_row[(int)$_GET['x']][(int)$_GET['y']]) AND $map_row[(int)$_GET['x']][(int)$_GET['y']]==1)
		 {
			$new_xpos = (int)$_GET['x'];
			$new_ypos = (int)$_GET['y'];
		 }
	}
}
$new_xpos = max(0,min($new_xpos,$xmap_max));
$new_ypos = max(0,min($new_ypos,$ymap_max));
if ($result_items>0 or $char['clevel']==0)
{
	$cm = $char['PR'];
}
else
{
	if ($CC_overload == 0)
	{
		$CC_overload = 1;
	}
	
	$rashod = (6 / max (1, $char['DEX'])) * ($CC_overload / 14);
	$cm = round($char['PR'] - $rashod);
	$cm = (int)$cm;
}
if ($maze==1 AND $cm==$char['PR'])
{
	$cm--;
}
if ($char['map_name']==id_map_tuman)
{
    $cm=$char['PR'] - 0.1*$char['PR_MAX'];
}

$result = myquery("UPDATE game_users SET PR=" . $cm . " WHERE user_id=$user_id");
set_delay_info($user_id,$delay,8);
$result = myquery("UPDATE game_users_map SET map_xpos=$new_xpos, map_ypos=$new_ypos WHERE user_id=$user_id");

if (function_exists("save_debug")) save_debug(); 

setLocation("act.php?func=main");
?>