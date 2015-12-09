<?php

if (function_exists("start_debug")) start_debug(); 

if ($_SERVER['PHP_SELF']=="/act.php" OR $_SERVER['PHP_SELF']=="/craft.php" OR ($_SERVER['PHP_SELF']=="/lib/hero.php" AND isset($_GET['house'])))
{
}
else
{
	die($_SERVER['PHP_SELF']);
}

$js_dir="";
$from_house = false;
$from_craft = false;
if (isset($_GET['house']))
{
	$js_dir = "../";
	$dirclass = '../class';
	include('../inc/config.inc.php');
	include('../inc/lib.inc.php');
	require_once('../inc/db.inc.php');
	include('../inc/lib_session.inc.php');
	include('../inc/functions.php');
	?>
	<html>
	<head>
	<title>Средиземье :: Эпоха сражений :: RPG online игра по трилогии "Властелин колец"</title>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
	<meta name="description" content="Многопользовательская RPG OnLine игра по трилогии Дж.Р.Р.Толкиена 'ВЛАСТЕЛИН КОЛЕЦ' - лучшая ролевая игра на постсоветском пространстве">
	<meta name="Keywords" content="Средиземье Властелин колец Толкиен Lord of the Rings rpg фэнтези ролевая онлайн игра Эпоха сражений online game поединки бои гильдии кланы магия бк таверна">
	<script language="JavaScript" type="text/javascript" src="../js/cookies.js"></script>
	<style type="text/css">@import url("../style/global.css");</style>
	</head>
	<?
	echo'
	<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
	<TR><TD width=80><IMG height=43 src="http://'.img_domain.'/nav1/1.gif" width=80></TD>
	<TD vAlign=top width=18 background="http://'.img_domain.'/nav1/spacer.gif">
	<IMG height=43 src="http://'.img_domain.'/nav1/2.gif" width=18></TD>
	<TD vAlign=top width=22 background="http://'.img_domain.'/nav1/3.gif">
	<IMG height=43 src="http://'.img_domain.'/nav1/4.gif" width=22></TD>
	<TD width="100%" background="http://'.img_domain.'/nav1/3.gif" align=center vAlign=center>

	<table cellSpacing=0 cellPadding=0 width="100%" border=0><tr>
	<td align="center"><a href="http://'.domain_name.'/lib/town.php?option='.$option.'&part4" target="game">В личное землевладение</a></td><td><IMG height=43 src="http://'.img_domain.'/nav1/5.gif" width=22><IMG height=43 src="http://'.img_domain.'/nav1/4.gif" width=22></td>
	<td align="center"><a href="http://'.domain_name.'/lib/town.php?option='.$option.'">В жилой квартал</a></td><td><IMG height=43 src="http://'.img_domain.'/nav1/5.gif" width=22><IMG height=43 src="http://'.img_domain.'/nav1/4.gif" width=22></td>
	<td><a href="http://'.domain_name.'/lib/town.php" target="game">В город</a></td></tr></table>
	</TD>
	<TD vAlign=top width=22 background="http://'.img_domain.'/nav1/3.gif">
	<IMG height=43 src="http://'.img_domain.'/nav1/5.gif" width=22></TD>
	<TD vAlign=top width=18 background="http://'.img_domain.'/nav1/spacer.gif"><IMG height=43 src="http://'.img_domain.'/nav1/6.gif" width=18></TD>
	<TD width=80><IMG height=43 src="http://'.img_domain.'/nav1/7.gif" width=80></TD>
	</TR>
	</TABLE>';
	if (getFunc($user_id)==9)
	{
		$from_house = true;
	}
}
else
{
require_once('inc/template_header.inc.php');
}
if (getFunc($user_id)==2)
{
	$from_craft = true;
}

function count_all_exp(&$EXP_NEW,&$gp)
{
	GLOBAL $char, $user_id;
	// Формула накопленного опыта
	$level=$char['clevel'];
	$i=1;
	$gp = 0;
	$EXP_NEW=$char['EXP']+get_exp_from_level($level);
	for($i;$i<=$level;$i++)
	{
		if ($i == 10)
		{
			$gp+=300;
		}
		elseif ($i == 20)
		{
			$gp+=500;
		}
		elseif ($i == 30)
		{
			$gp+=1000;
		}
		elseif ($i == 40)
		{
			$gp+=1500;
		}
		else
		{
			if ($i<10)
			{
				$gp+=50;
			}
			else
			{
				$gp+=floor(($i-1)/10)*2*50;
			}
		}
	}
	return '';
}

$dostup = -1; // -1 - еще не проверяли
              // 0 - доступ к обнулению закрыт
              // 1 - бесплатное обнуление
              // 2 - обнуление за опыт

function check_obnul($param)
{
	GLOBAL $char, $user_id, $dostup;
    if ($char['clevel']<5) return;
	if ($dostup==-1)
	{
        $dostup = 0;
        if ($char['clevel']>=5)
        {
		    $obnul = mysql_result(myquery("SELECT obnul FROM game_users_data WHERE user_id=$user_id"),0,0);
            if ($obnul>0)
            {
                $dostup = 1;
            }
            else
            {
                $dostup = 2;
            }
		    //проверим, не сидит ли игрок на каторге
		    $prison_check=mysql_num_rows(myquery("SELECT * FROM game_prison WHERE user_id='$user_id'"));
		    //если игрок на каторге, не дадим ему ссылку на форум
		    if($prison_check>0)
		    {
			    $dostup = 0;
		    }
        }
	}
	if ($dostup>0)
	{
		if (isset($_POST['make_obnul2']) AND $param == 0)
		{
			do_obnul($user_id,$dostup);
			echo '<div style="padding:10px;align:center;font-weight:700;color:#FFFF00;font-family:Verdana,Tahoma,Arial,sans-serif;font-size:12px;">Твой персонаж удачно обнулен! Поздравляю! Теперь ты можешь заново развить своего героя!</div>'; 
			$result = myquery("SELECT * FROM game_users WHERE user_id=$user_id LIMIT 1");
			$char = mysql_fetch_array($result);
			list($char_map_name,$char_map_xpos,$char_map_ypos) = mysql_fetch_array(myquery("SELECT map_name,map_xpos,map_ypos FROM game_users_map WHERE user_id='$user_id'"));
			list($IP) = mysql_fetch_array(myquery("SELECT work_IP FROM game_users_data WHERE user_id='$user_id'"));
			$char['map_name']=$char_map_name;
			$char['map_xpos']=$char_map_xpos;
			$char['map_ypos']=$char_map_ypos;
			$char['last_active']=$_SESSION['user_time'];
		}
		elseif ($param == 1 AND !isset($_POST['make_obnul']))
		{
			echo '<script language="JavaScript" type="text/javascript">
			function show_hide_obnul()
			{
				div = document.getElementById("obn");
				if (div.style.display=="none")
				{
					div.style.display = "block";
				}
				else
				{
					div.style.display = "none";
				}
			}
			</script>';
			echo '<div><a href="#" onClick="show_hide_obnul();">Тебе доступно обнуление персонажа</a></div>';
			echo '<div id="obn" style="display:none;">';
			QuoteTable('open');
			echo '<div style="padding:10px;align:center;font-weight:400;color:#FF8080;font-family:Verdana,Tahoma,Arial,sans-serif;font-size:12px;">Тебе доступно "обнуление" своего персонажа. После "обнуления" твой уровень и характеристики будут сброшены до начального уровня (0 уровня). Все предметы будут с тебя сняты. Если у тебя есть конь, то тебе вернут половину его стоимости, а самого коня удалят. У тебя будет вычтено то количество золотых монет, которое ты будешь получать в дальнейшем при повышении уровней';
			//$obnul = mysqlresult(myquery("SELECT obnul FROM game_users_data WHERE user_id=$user_id"),0,0); 
			if ($dostup==2)//Платный обнул
			{
				$allexp = 0;
				$gp=0;
				count_all_exp($allexp,$gp);
                $shtraf=floor($allexp*0.1);
				echo '<div style="padding:10px;align:center;font-weight:400;color:#00FFFF;font-family:Verdana,Tahoma,Arial,sans-serif;font-size:12px;">За обнуление ты '.echo_sex('должен','должна').' будешь заплатить: '.$shtraf.' единиц опыта</div>';
			}
			echo '<br /><br /><center><form action="" method="post" name="form_obnul"><input type="submit" name="make_obnul" value="ДА, я хочу сделать обнуление своего персонажа" style="padding:5px;font-size:13px;color:white;font-weight:900;font-family:Verdana;"></form>';
			echo '</div>';
			QuoteTable('close');
			echo '</div>';
			echo '<br />';
		}
		elseif ($param == 1 AND isset($_POST['make_obnul']))
		{
			QuoteTable('open');
			echo '<div style="padding:10px;align:center;font-weight:400;color:#FF8080;font-family:Verdana,Tahoma,Arial,sans-serif;font-size:12px;">Тебе доступно "обнуление" своего персонажа. После "обнуления" твой уровень и характеристики будут сброшены до начального уровня (0 уровня). Все предметы будут с тебя сняты. Если у тебя есть конь, то тебе вернут половину его стоимости, а самого коня удалят. У тебя будет вычтено то количество золотых монет, которое ты будешь получать в дальнейшем при повышении уровней.';
            //$obnul = mysqlresult(myquery("SELECT obnul FROM game_users_data WHERE user_id=$user_id"),0,0); 
            if ($dostup==2)//Платный обнул
            {
                $allexp = 0;
                $gp=0;
                count_all_exp($allexp,$gp);
                $shtraf=floor($allexp*0.1);
				echo '<div style="padding:10px;align:center;font-weight:400;color:#00FFFF;font-family:Verdana,Tahoma,Arial,sans-serif;font-size:12px;">За обнуление ты '.echo_sex('должен','должна').' будешь заплатить: '.$shtraf.' единиц опыта</div>';
			}
			echo '<center><div style="font-weight:700;font-size:16px;color:red;height:55px;"><br>Ты '.echo_sex('решил','решила').' сделать обнуление персонажа. <br />Действительно ли ты хочешь это сделать?<br></div>';
			echo '<form action="" method="post" name="form_obnul" class="button"><input type="submit" name="make_obnul2" value="ДА, я действительно хочу сделать обнуление своего персонажа" style="padding:5px;font-size:13px;color:white;font-weight:900;font-family:Verdana;"></form>';
			echo '</div>';
			QuoteTable('close');
			echo '<br />';
		}
	}
}

//if ($char['clevel']>=5) check_obnul(0);

//------------------1. Характеристики персонажа---------------------------------

echo'<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td valign="top" width="172"> ';

OpenTable('title');
echo'<table width="172" border="0" cellspacing="0" cellpadding="0"><tr><td>';
echo '<div align="center"><font color="#FFFF00"><b>Раса: <font color=ff0000>' . mysql_result(myquery("SELECT name FROM game_har WHERE id=".$char['race'].""),0,0) . '</font></b><br><font color="#FFFF00"><b>Твой ID: <font color=ff0000>' . $char['user_id'] . '</font></b><br><br>
<img src="http://'.img_domain.'/avatar/' . $char['avatar'] . '" border="0" alt="' . $char['name'] . '"></b>
</div><td>';
echo'</td></tr></table>';
OpenTable('close');

echo '<table cellpadding="2" cellspacing="0" border="0" width=100%>

<tr><td><b>Уровень:</b></td><td align=right>'.$char['clevel'].'</td></tr>
<tr><td><b>Реинкарнация:</b></td><td align=right>'.$char['reinc'].'</td></tr>
</table>';
$sel = myquery("SELECT * FROM game_users_brak WHERE (status=1 AND (user1='".$char["user_id"]."' OR user2='".$char["user_id"]."'))");
if (mysql_num_rows($sel))
{
	OpenTable('title');
	$usr = mysql_fetch_array($sel);
	if ($char['user_id']==$usr['user1'])
		$usr_id = $usr['user2'];
	else
		$usr_id = $usr['user1'];

	$selec = myquery("SELECT name FROM game_users WHERE user_id='".$usr_id."'");
	if (!mysql_num_rows($selec)) $selec = myquery("SELECT name FROM game_users_archive WHERE user_id='".$usr_id."'");
	list($name1) = mysql_fetch_array($selec);
	list($last_active1) = mysql_fetch_array(myquery("SELECT last_active FROM game_users_active WHERE user_id='$usr_id'"));

	echo'<table width="172" border="0" cellspacing="0" cellpadding="0">';
	echo '<tr><td><FONT color="#FFFF00" size=1 face="Tahoma"><img src="http://'.img_domain.'/item/ring/9.gif" width=30 height=30 align="right">Ты состоишь в браке с игроком '.$name1.'</FONT></td></tr>';
	if ((time()-$last_active1)<=300)
	{
		if (isset($teleport) AND $teleport==$usr_id AND ($user_time >= $char['delay'] OR $char['block']!=1))
		{
			$sel = myquery("SELECT map_name,map_xpos,map_ypos FROM game_users_map WHERE user_id='$usr_id'");
			list($map,$posx,$posy) = mysql_fetch_array($sel);
			if ($map == $char['map_name'])
			{
				list($maze)=mysql_fetch_array(myquery("SELECT maze FROM game_maps WHERE id=$map"));
				if ($maze==1)
				{
					echo 'Магия лабиринта заблокировала возможность перемещения';
				}
				else
				{
				$up = myquery("UPDATE game_users_map SET map_name='$map',map_xpos='$posx',map_ypos='$posy' WHERE user_id='".$char['user_id']."'");
				echo'Ты '.echo_sex('переместился','переместилась').' к '.$name1.'';
			}
		}
		}
		echo'<tr><td><a href="act.php?func=hero&teleport='.$usr_id.'">Телепорт к '.$name1.'</a></td></tr>';
	}
	echo'</table>';
	OpenTable('close');
}


OpenTable('title');
echo'<table cellpadding="0" cellspacing="0" border="0" width="172"><tr><td>
<font color="#FFFF00"><b>Характеристики:</b></font><br><br>
<table cellpadding="2" cellspacing="0" border="0" width=100%>

<tr><td><img src="http://'.img_domain.'/har/sil.gif" alt="Сила"> Сила: </td><td align=right>' . $char['STR'] . ''; if ($char['STR']>$char['STR_MAX']) echo '(+'.($char['STR']-$char['STR_MAX']).')'; echo '</td></tr>
<tr><td><img src="http://'.img_domain.'/har/int.gif" alt="Интеллект"> Интеллект: </td><td align=right>' . $char['NTL'] . ''; if ($char['NTL']>$char['NTL_MAX']) echo '(+'.($char['NTL']-$char['NTL_MAX']).')'; echo '</td></tr>
<tr><td><img src="http://'.img_domain.'/har/lov.gif" alt="Ловкость"> Ловкость: </td><td align=right>' . $char['PIE'] . ''; if ($char['PIE']>$char['PIE_MAX']) echo '(+'.($char['PIE']-$char['PIE_MAX']).')'; echo '</td></tr>
<tr><td><img src="http://'.img_domain.'/har/vit.gif" alt="Защита"> Защита: </td><td align=right>' . $char['VIT'] . ''; if ($char['VIT']>$char['VIT_MAX']) echo '(+'.($char['VIT']-$char['VIT_MAX']).')'; echo '</td></tr>
<tr><td><img src="http://'.img_domain.'/har/dex.gif" alt="Выносливость"> Выносливость: </td><td align=right>' . $char['DEX'] . ''; if ($char['DEX']>$char['DEX_MAX']) echo '(+'.($char['DEX']-$char['DEX_MAX']).')'; echo '</td></tr>
<tr><td><img src="http://'.img_domain.'/har/mud.gif" alt="Мудрость"> Мудрость: </td><td align=right>' . $char['SPD'] . ''; if ($char['SPD']>$char['SPD_MAX']) echo '(+'.($char['SPD']-$char['SPD_MAX']).')'; echo '</td></tr>
<tr><td><img src="http://'.img_domain.'/har/ud.gif" alt="Удача"> Удача: </td><td align=right>' . $char['lucky'] . ''; if ($char['lucky']>$char['lucky_max']) echo '(+'.($char['lucky']-$char['lucky_max']).')'; echo '</td></tr>

<tr><td>&nbsp;</td></tr>
<tr><td><img src="http://'.img_domain.'/har/win1.gif" alt="Побед"> Побед: </td><td align=right>' . $char['win'] . '</td></tr>';
//<tr><td><img src="http://'.img_domain.'/har/lose1.gif" alt="Поражений"> Поражений: </td><td align=right>' . $char['lose'] . '</td></tr>
echo'<tr><td><img src="http://'.img_domain.'/har/win2.gif" alt="Побед в Две Башни"> Побед в Две Башни: </td><td align=right>' . $char['arcomage_win'] . '</td></tr>
<tr><td><img src="http://'.img_domain.'/har/lose2.gif" alt="Поражений в Две Башни"> Поражений в Две Башни: </td><td align=right>' . $char['arcomage_lose'] . '</td></tr>
';
if ($char['maze_win']>0)
{
	echo'
	<tr><td>&nbsp;</td></tr>
	<tr style="color:#FF00FF;font-weight:700;"><td><img src="http://'.img_domain.'/har/los.jpg" alt="Пройдено Лабиринтов">Пройдено Лабиринтов: </td><td style="text-align:right;">' . $char['maze_win'] . '</td></tr>
	';
}

echo '<tr><td>&nbsp;</td></tr>
<tr><td>Свободных характеристик: </td><td align=right>' . $char['bound'] . '</td></tr>

<tr><td>Свободных навыков:</td><td align=right> ' . $char['exam'] . '</td></tr>
</table>';
echo'</td></tr></table>';
OpenTable('close');

OpenTable('title');

echo '<table cellpadding="1" cellspacing="1" align="center" border="0" width="172">';
echo '<tr><td colspan=2><font size=1 color="#FFFF00"><b>Специализации:</b></font></td></tr>';
$sel_skill = myquery("SELECT gs.name, gus.* FROM game_users_skills gus, game_skills gs WHERE user_id=$user_id AND gus.skill_id=gs.id ORDER BY gs.sgroup DESC, gs.name");
if (mysql_num_rows($sel_skill)>0)
{
	
	while ($sk = mysql_fetch_array($sel_skill))
	{
		echo '<tr><td align="left">'.$sk['name'].': </td><td align="right">' . $sk['level'].'</td></tr>';
	}
}

echo'</table>';

echo '<table cellpadding="1" cellspacing="1" align="center" border="0" width="172">';
echo '<tr><td colspan=2><font size=1 color="#FFFF00"><b>Умения:</b></font></td></tr>';
$sel_craft = myquery("SELECT * FROM game_users_crafts WHERE user_id=$user_id AND (profile=1 OR craft_index<=2)");
if (mysql_num_rows($sel_craft)>0)
{
	
	while ($cr = mysql_fetch_array($sel_craft))
	{
		$craft_level = CraftSpetsTimeToLevel($cr['craft_index'],$cr['times']);
		echo '<tr><td align="left">'.get_craft_name($cr['craft_index']).': </td><td align="right" title="Уровень(удач.рез.)">' . $craft_level . ' ( '.$cr['times'].' )</td></tr>';
	}
}
$guild_test = myquery("SELECT * From game_users_guild Where user_id=$user_id");
if (mysql_num_rows($guild_test)==1) 
{
	$guild = mysql_fetch_array($guild_test);
	echo '<tr><td align="left">Наёмник: </td><td align="right" title="Уровень(удач.рез.)">' . $guild['guild_lev'] . ' ( '.$guild['guild_times'].' )</td></tr>';
}
echo'</table>';

//------------------2. Основное окно персонажа---------------------------------

OpenTable('close');
echo'<td valign="top" width="100%" height="100%">';

OpenTable('title');

$new_clevel = get_new_level($char['clevel']);

echo'<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0"><tr><td> ';

echo '
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">	
	<script type="text/javascript" src="../js/tabs/addclasskillclass.js"></script>
	<script type="text/javascript" src="../js/tabs/attachevent.js"></script>
	<script type="text/javascript" src="../js/tabs/addcss.js"></script>
	<script type="text/javascript" src="../js/tabs/tabtastic.js"></script>
	
	<div id="pagecontent">
	<ul class="tabset_tabs">
		<li class="firstchild"><a href="#invent" class="preActive active">Инвентарь</a></li><li><a class="preActive postActive" href="#skills">Умения</a></li>
	</ul>';
	
//------------------2.1 Инвентарь персонажа---------------------------------
	
echo '<div id="invent" class="tabset_content tabset_content_active">';
echo'<img src="http://'.img_domain.'/nav/xar.gif" align=right><br>';

//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
echo'<table border="0"><tr><td>';


echo'<SCRIPT language=javascript src="'.$js_dir.'js/info.js"></SCRIPT><DIV id=hint  style="Z-INDEX: 100; LEFT: 0px; VISIBILITY: hidden; POSITION: absolute; TOP: 0px"></DIV>';

echo'
<div id="PopupDiv" style="position:absolute; visibility:hide;"></div>
<script language="JavaScript">
var closeb=\'<img src="http://'.img_domain.'/close.gif" border=0>\';
</script><script language="JavaScript" src="'.$js_dir.'js/popup.js"></script>';

if (getFunc($user_id)==2)
{
	$from_craft = true;
}

//Активация защиты от нападений
if (isset($_POST['get_hide']) and isset($_POST['kol_charges']) and is_numeric($_POST['kol_charges']) and $_POST['kol_charges']<=$char['hide_charges'])
{
	$kol_charges = max(0,(int)$_POST['kol_charges']);
	if ($kol_charges>0)
	{
		$time_hide = $kol_charges*6*60;
		$add_effect = 0;
		$check_hide = myquery("SELECT time_end FROM game_obelisk_users WHERE user_id = ".$user_id." and type=6 ");
		if (mysql_num_rows($check_hide)>0) 
		{
			list($time) = mysql_fetch_array($check_hide);
			if ($time<time())
			{
				myquery("DELETE FROM game_obelisk_users WHERE user_id = ".$user_id." and type=6");
			}
			else
			{
				$add_effect = 1;
			}
		}
		if ($add_effect == 1)
		{
			myquery("UPDATE game_obelisk_users SET time_end = time_end + ".$time_hide." WHERE user_id = ".$user_id." and type=6");
		}
		else
		{
			myquery("INSERT INTO game_obelisk_users (user_id,time_end,type) VALUES (".$user_id.",".(time()+$time_hide).",6)");
		}
		
		echo 'Вы недоступны для нападений в течение '.($time_hide/60).' '.pluralForm($time_hide/60,'минуты','минут','минут').'!<br>';
		myquery("UPDATE game_users SET hide_charges=hide_charges-".$kol_charges." WHERE user_id='".$user_id."'");
		$char['hide_charges']-=$kol_charges;
	}
	else
	{
		echo 'Что-то введено не так!';
	}
}

//Снимаем все предметы
if (isset($_GET['all_down']))
{
	$Item = new Item();
	$result=$Item->all_down($char['user_id']);	
	if ($from_house)
	{							
		setLocation("hero.php?house&option=".$option."");							
	}
	else
	{							
		setLocation("act.php?func=inv");	
	}
}

//Начинаем запоминание комплекта
elseif (isset($_GET['start_complect']))
{
	QuoteTable('open');	
	$check=myquery("SELECT status FROM game_users_complects WHERE user_id='".$char['user_id']."'");
	$kol=mysql_num_rows($check);
	$no_complect=0;
	if ($kol>$char['complects'])
	{
		echo 'Вы не можете запомнить ещё один комплект!';
	}
	else
	{
		while (list($status)=mysql_fetch_array($check))
		{
			if ($status==0)
			{
				$no_complect=1;
			}
		}
		if ($no_complect==1)
		{
			echo 'У Вас уже идёт процесс запоминания комплекта!';
		}
		else
		{
			$Item = new Item();
			$result=$Item->all_down($char['user_id']);
			myquery("INSERT INTO game_users_complects (user_id, finish_time) VALUES ('".$char['user_id']."', '".(time()+60*60*2)."') ");
			echo 'Вы успешно начали процесс запоминания комплекта!';
		}
	}
	QuoteTable('close');
}

//Заканчиваем запоминание комплекта
elseif (isset($_GET['save_complect']))
{
	QuoteTable('open');
	$check=myquery("SELECT id FROM game_users_complects WHERE user_id='".$char['user_id']."' AND status=0");
	if (mysql_num_rows($check)==1)
	{
		list($complect)=mysql_fetch_array($check);
		$check1=myquery("SELECT item_id FROM game_items WHERE user_id='".$char['user_id']."' AND used>0 AND used not in (12, 13, 14)" );
		if (mysql_num_rows($check1)>0)
		{
			myquery("INSERT INTO game_users_complects_items (complect_id, item_id) (SELECT '".$complect."', gi.item_id FROM game_users_complects_prepare as gucp JOIN game_items gi ON (gucp.item_id=gi.id AND gi.used=0) WHERE gucp.complect_id='".$complect."' AND gi.priznak=0) ");
			myquery("INSERT INTO game_users_complects_items (complect_id, item_id, used) (SELECT '".$complect."', gi.item_id, gi.used FROM game_items gi WHERE gi.user_id='".$char['user_id']."' AND gi.used>0 AND gi.used not in (12, 13, 14) AND gi.priznak=0) ");
			myquery("DELETE FROM game_users_complects_prepare WHERE complect_id='".$complect."' ");
			
			//Рассчитаем требуемый уровень игрока
			list($level)=mysql_fetch_array(myquery("SELECT max(oclevel) FROM game_users_complects_items guci JOIN game_items_factsheet gif ON guci.item_id=gif.id WHERE guci.complect_id='".$complect."'"));
			
			//Рассчитаем харки игрока без вещей и изменим статус комплекта
			list($str,$pie,$ntl,$vit,$dex,$spd,$lucky)=mysql_fetch_array(myquery("SELECT SUM(dstr) as str, SUM(dpie) as pie, SUM(dntl) as ntl, SUM(dvit) as vit, SUM(ddex) as dex, SUM(dspd) as spd, SUM(dlucky) as lucky FROM game_items gi JOIN game_items_factsheet gis ON gi.item_id=gis.id WHERE gi.user_id='".$char['user_id']."' AND gi.used>0 AND gi.priznak=0"));
			myquery("UPDATE game_users_complects SET clevel='".$level."', str='".($char['STR']-$str)."', ntl='".($char['NTL']-$ntl)."', pie='".($char['PIE']-$pie)."', 
			vit='".($char['VIT']-$vit)."', dex='".($char['DEX']-$dex)."', spd='".($char['SPD']-$spd)."', lucky='".($char['lucky']-$lucky)."', status=1 
			WHERE id='".$complect."' ");
			echo 'Комплект успешно запомнен!';
		}
		else
		{
			echo 'На Вас ничего не одето!';
		}
	}
	else
	{
		echo 'Вы не запоминали никакой комплект!';
	}
	QuoteTable('close');
}

//Одеваем комплект
elseif (isset($_GET['up_complect']))
{	
	$Item = new Item();
	$result=$Item->all_down($char['user_id']);
	QuoteTable('open');	
	//Проверка на характеристики
	$check_compl=myquery("SELECT * FROM game_users_complects WHERE id='".$_GET['up_complect']."' AND user_id='".$char['user_id']."'");
	if (mysql_num_rows($check_compl)==1)
	{
		$compl=mysql_fetch_array($check_compl);
		if ($char['clevel']<$compl['clevel'])
		{
			echo 'Ваш уровень меньше, чем нужно!';
		}
		else
		{			
			$char1=mysql_fetch_array(myquery("SELECT STR, NTL, PIE, VIT, DEX, SPD, lucky FROM view_active_users WHERE user_id='".$char['user_id']."'"));			
			if ($char1['STR']<$compl['str'] or $char1['NTL']<$compl['ntl'] or $char1['PIE']<$compl['pie'] or $char1['VIT']<$compl['vit'] or $char1['DEX']<$compl['dex'] or
				$char1['SPD']<$compl['spd'] or $char1['lucky']<$compl['lucky'])
			{
				echo 'Ваши начальные характеристики меньше, чем нужно!';
			}
			else
			{
				//Проверка на предметы
				$check_items=myquery("SELECT gif.name, (v1.kol - ifnull(v2.kol, 0)) as kol FROM 
				(SELECT item_id, count(item_id) as kol FROM game_users_complects_items WHERE complect_id='".$compl['id']."' GROUP BY item_id) as v1
				JOIN game_items_factsheet gif ON v1.item_id = gif.id
				LEFT JOIN (SELECT item_id, count(item_id) as kol FROM game_items WHERE user_id='".$char['user_id']."' AND priznak=0 AND item_uselife>0 AND ref_id=0 GROUP BY item_id) as v2 
				ON (v1.item_id=v2.item_id) WHERE v1.kol>v2.kol OR v2.kol IS NULL");
				if (mysql_num_rows($check_items)>0)
				{
					echo 'У вас нет всех необходимых предметов:';
					while ($it = mysql_fetch_array($check_items))
					{
						echo '<br>'.$it['name'].' - '.$it['kol'].' шт.';
					}
				}
				else
				{
					$find_items=myquery("SELECT item_id, used FROM game_users_complects_items WHERE complect_id='".$compl['id']."' AND used>0");
					while (list($id,$used)=mysql_fetch_array($find_items))
					{
						list($it_id)=mysql_fetch_array(myquery("SELECT id FROM game_items WHERE user_id='".$char['user_id']."' AND priznak=0 AND item_uselife>0 AND used=0 AND ref_id=0 AND item_id='".$id."' LIMIT 1"));
						$Item = new Item($it_id);
						$Item->up(0, $used, 0);						
						if ($from_house)
						{							
							setLocation("hero.php?house&option=".$option."");							
						}
						else
						{							
							setLocation("act.php?func=inv");	
						}						
					}
					echo 'Комплект одет!';
				}
			}
		}
	}
	QuoteTable('close');
}

//Удаляем комплект
elseif (isset($_GET['del_complect']))
{
	$check=myquery("SELECT status FROM game_users_complects WHERE user_id='".$char['user_id']."' AND id='".$_GET['del_complect']."'");
	if (mysql_num_rows($check)==1)
	{
		list($status)=mysql_fetch_array($check);
		if ($status==1)
		{	
			myquery("DELETE FROM game_users_complects_items WHERE complect_id='".$_GET['del_complect']."'");
		}
		elseif ($status==0)
		{
			myquery("DELETE FROM game_users_complects_prepare WHERE complect_id='".$_GET['del_complect']."'");
		}
		myquery("DELETE FROM game_users_complects WHERE id='".$_GET['del_complect']."' AND user_id='".$char['user_id']."'");
		QuoteTable('open');
		echo 'Комплект успешно удалён!';
		QuoteTable('close');
	}
}

//Используем предмет
if (isset($_GET['option']) AND $_GET['option']=='eliksir' AND isset($_GET['id']) AND $_GET['id']>0 AND !$from_house AND !$from_craft)
{   
	$Item = new Item();
	$Item->use_item($_GET['id']);
	QuoteTable('open');
	echo $Item->message;
	QuoteTable('close');
}


//Заряжаем артефакт
if (isset($_GET['option']) AND $_GET['option']=='charge' AND !$from_house AND !$from_craft AND isset($_GET['id']) AND $_GET['id']>0)
{   
	$Item = new Item($_GET['id']);
	$zar = $Item->getFact('item_uselife')-$Item->getItem('count_item');
	if ($Item->getFact('type')==3 AND $zar>0)
	{
		$sel_last_event = myquery("SELECT timestamp FROM game_users_event WHERE user_id=$user_id AND event=1");
		if ($sel_last_event!=false AND mysql_num_rows($sel_last_event)>0)
		{
			list($last_event) = mysql_fetch_array($sel_last_event);
		}
		else
		{
			$last_event = 0;
		}
		if (($last_event+$Item->getFact('cooldown'))<time())
		{
			if ($Item->item['item_uselife_max']>1 OR $Item->fact['breakdown']==0)
			{
				//формула зарядки артефакта - 10 маны и 5 энергии на 1 заряд
				if ($char['STM_MAX']>$char['MP_MAX'])
				{
					$mp = 10;
					$stm = 5;
				}
				else
				{
					$mp = 5;
					$stm = 10;
				}
				
				$kol_mp = floor($char['MP']/$mp);
				$kol_stm = floor($char['STM']/$stm);
				$kol = min($kol_mp,$kol_stm);
				$kol = min($kol,$zar);
				if ($kol>0)
				{
					$minus_mp = $mp*$kol;
					$minus_stm = $stm*$kol;
					$update = myquery("UPDATE game_users SET MP=MP-$minus_mp,STM=STM-$minus_stm WHERE user_id=$user_id");
					$update = myquery("UPDATE game_items SET count_item = count_item+$kol WHERE id = '".$_GET['id']."'");
					$char = myquery("SELECT * FROM game_users WHERE user_id=$user_id");
					$char = mysql_fetch_array($char);
					list($char_map_name,$char_map_xpos,$char_map_ypos) = mysql_fetch_array(myquery("SELECT map_name,map_xpos,map_ypos FROM game_users_map WHERE user_id='$user_id'"));
					list($last_active) = mysql_fetch_array(myquery("SELECT last_active FROM game_users_active WHERE user_id='$user_id'"));
					$char['map_name']=$char_map_name;
					$char['map_xpos']=$char_map_xpos;
					$char['map_ypos']=$char_map_ypos;
					$char['last_active']=$last_active;
					if ($last_event==0)
					{
						myquery("INSERT INTO game_users_event (user_id,event,timestamp) VALUES ($user_id,1,".time().")");
					}
					else
					{
						myquery("UPDATE game_users_event SET timestamp=".time()." WHERE user_id=$user_id AND event=1");
					}
					QuoteTable('open');
					echo 'Артефакт заряжен на '.$kol.' '.pluralForm($kol,'заряд','заряда','зарядов').'!';
					QuoteTable('close');
				}
			}
			else
			{
				QuoteTable('open');
				echo 'Магия артефакта была полностью израсходована. Артефакт рассыпался в твоих руках в пыль!';
				$Item->admindelete();
				QuoteTable('close');
			}
		}
		else
		{
			$razn = ($last_event+$Item->getFact('cooldown'))-time();
			$min = floor($razn/60);
			$sec = $razn-$min*60;
			QuoteTable('open');
			echo 'Ты уже '.echo_sex('заряжал','заряжала').' артефакт '.date("d.m.Y H:i:s",$last_event).'. Тебе надо набраться сил. Попробуй еще раз через '.$min.' мин. '.$sec.' сек.';
			QuoteTable('close');
		}
	}
}

if (!empty($reason))
{
	if ($_SERVER['PHP_SELF']=="/act.php")
	{
		include('inc/template_reason.inc.php');
	}
	else
	{
		include('../inc/template_reason.inc.php');
	}
}

//Выбрасываем предметы
if (isset($_GET['dropitm']) AND !$from_house AND !$from_craft)
{		
	$itmid = (int)$_GET['dropitm'];
	$selitm = myquery("SELECT * FROM game_items WHERE user_id=$user_id AND id=$itmid");
	if ($selitm!=false AND mysql_num_rows($selitm)>0)
	{
		$itm = mysql_fetch_array($selitm);
		$itms = mysql_fetch_array(myquery("SELECT * FROM game_items_factsheet WHERE id='".$itm['item_id']."'"));
		if (!isset($_POST['submit']))
		{
			QuoteTable('open');
			echo 'Укажите количество выбрасываемых предметов:<br>';
			echo '<form action="act.php?func=inv&dropitm='.$itmid.'" method="POST">';
			echo '<img src=http://'.img_domain.'/item/'.$itms['img'].'.gif border=0 width=50 height=50> '.$itms['name'].' - есть '.$itm['count_item'].' ед.<br><br>';
			echo 'Выбросить: <input type="text" name="kol" value="1" size=5 maxsize=5> ед.    ';
			echo '<input type="submit" name="submit" value="Выбросить">';
			echo '</form>';
			QuoteTable('close');
		}
		else
		{
			$kol_itm = (int)$_POST['kol'];
			if ($kol_itm<=$itm['count_item'] AND $kol_itm>0 AND $kol_itm==$_POST['kol'])
			{
				if ($kol_itm<$itm['count_item'])
				{
					myquery("UPDATE game_items SET count_item=GREATEST(0,count_item-".$kol_itm.") WHERE user_id=$user_id AND id=$itmid");
				}
				else
				{
					myquery("DELETE FROM game_items WHERE user_id=$user_id AND id=$itmid");
				}
				myquery("UPDATE game_users SET CW=CW-".($kol_itm*$itms['weight'])." WHERE user_id=$user_id");
				$seldrop = myquery("SELECT * FROM game_items WHERE town=0 AND map_name=".$char['map_name']." AND map_xpos=".$char['map_xpos']." AND map_ypos=".$char['map_ypos']." AND item_id='".$itm['item_id']."' LIMIT 1");
				if ($seldrop!=false AND mysql_num_rows($seldrop)>0 AND $kol_itm>0)
				{
					$drop = mysql_fetch_array($seldrop);
					myquery("UPDATE game_items SET count_item=count_item+'".$kol_itm."' WHERE id=".$drop['id']."");	
				}
				else
				{
				//************************************************
					myquery("INSERT INTO game_items (id,user_id,item_id,ref_id,item_uselife,item_cost,map_name,map_xpos,map_ypos,
					item_for_quest,town,sell_time,priznak,post_to,post_var,used,item_uselife_max,for_town,
					shop_from,kleymo,kleymo_nomer,kleymo_id,count_item,dead_time) 
					VALUES (null,0,'".$itm['item_id']."','".$itm['ref_id']."','".$itm['item_uselife']."','".$itm['item_cost']."',
					".$char['map_name'].",".$char['map_xpos'].",".$char['map_ypos'].",
					'".$itm['item_for_quest']."',0,0,2,0,0,0,'".$itm['item_uselife_max']."',0,
					0,0,0,0,'".$kol_itm."','".$itm['dead_time']."')");
				}
				QuoteTable('open');
				echo 'Выброшено '.$kol_itm.' ед. из '.$itm['count_item'].' ед '.$itms['name'].'';
				QuoteTable('close');
				echo '<br />';
			}
		}
	}
}
	
//Выбрасываем ресурсы
if (isset($_GET['dropres']) AND !$from_house AND !$from_craft)
{
	$resid = (int)$_GET['dropres'];					
	$ress = mysql_fetch_array(myquery("SELECT * FROM craft_resource WHERE id=$resid"));
	if (!isset($_POST['submit']))
	{
		$selres = myquery("SELECT * FROM craft_resource_user WHERE user_id=$user_id AND res_id=$resid");
		if ($selres!=false AND mysql_num_rows($selres)>0)
		{
			$res = mysql_fetch_array($selres);
			QuoteTable('open');
			echo 'Укажи кол-во ресурса, которые ты хочешь выбросить:<br>';
			echo '<form action="act.php?func=inv&dropres='.$resid.'" method="POST">';
			echo '<img src=http://'.img_domain.'/item/resources/'.$ress['img3'].'.gif border=0 width=50 height=50> '.$ress['name'].' - есть '.$res['col'].' ед.<br><br>';
			echo 'Выбросить: <input type="text" name="kol" value="1" size=5 maxsize=5> ед.    ';
			echo '<input type="submit" name="submit" value="Выбросить">';
			echo '</form>';
			QuoteTable('close');
		}
	}
	elseif (isset($_POST['kol']) and $_POST['kol'] > 0 and is_numeric($_POST['kol']))
	{
		$kol = (int)$_POST['kol'];
		$Res = new Res($ress, 0);
		$Res->drop($kol);
		QuoteTable('open');
		echo $Res->message;
		QuoteTable('close');			
	}
}
	
$sel_used_items = myquery("SELECT id,item_id,used FROM game_items WHERE user_id=$user_id AND priznak=0 AND used>0");
$used_items = array();
while ($it = mysql_fetch_array($sel_used_items))
{
	$used_items[$it['used']]['id']=$it['id'];
	$used_items[$it['used']]['item_id']=$it['item_id'];
}
	
PrintInv($user_id,0);

$check=myquery("SELECT * FROM game_users_complects WHERE user_id='".$user_id."'");
$kol=mysql_num_rows($check);
$i=0;
while ($comp=mysql_fetch_array($check))
{
	$i++;
	echo '<b>Комплект '.$i.'</b>';	
	if (!$from_house)
	{
		$link = "act.php?func=inv";
	}
	else
	{
		$link = "hero.php?func=inv&house&option=".$option;
	}
	if (!isset($_GET['show_complect']))
	{
		$link .= "&show_complect=".$comp['id'];
	}
	echo '<a href='.$link.'><img src="http://'.img_domain.'/info/info_inv.jpg"></img></a><br>';
	//Комплект ещё не готов
	if ($comp['status']==0)
	{
		$link = "act.php?func=inv&save_complect";
		if (!$from_house)
		{
			$link = "act.php?func=inv&save_complect";
		}
		else
		{
			$link = "hero.php?func=inv&save_complect&house&option=".$option;
		}
		echo '<a href='.$link.'>Сохранить комплект</a><br>';
	}
	//Комплект готовый
	else
	{		
		$cant_up = 0;
		$no_items = 0;
		if ($char['clevel']<$comp['clevel'])
		{			
			$cant_up = 1;
		}
		else
		{
			//Проверка на предметы
			$need_items=myquery("SELECT gif.name, (v1.kol - ifnull(v2.kol, 0)) as kol FROM 
			(SELECT item_id, count(item_id) as kol FROM game_users_complects_items WHERE complect_id='".$comp['id']."' GROUP BY item_id) as v1
			JOIN game_items_factsheet gif ON v1.item_id = gif.id
			LEFT JOIN (SELECT item_id, count(item_id) as kol FROM game_items WHERE user_id='".$char['user_id']."' AND priznak=0 AND item_uselife>0 AND ref_id=0 GROUP BY item_id) as v2 
			ON (v1.item_id=v2.item_id) WHERE v1.kol>v2.kol OR v2.kol IS NULL");
			if (mysql_num_rows($need_items)>0)
			{				
				$cant_up = 1;
				$no_items = 1;
			}
		}
		if ($cant_up == 0)
		{			
			if (!$from_house)
			{
				$link = "act.php?func=inv&up_complect=".$comp['id'];
			}
			else
			{
				$link = "hero.php?func=inv&up_complect=".$comp['id']."&house&option=".$option;
			}
			echo '<a href='.$link.'>Надеть комплект</a><br>';			
		}
		else
		{
			echo 'Комплект одеть нельзя!<br>';
		}
	}
	if (!$from_house)
	{
		$link = "act.php?func=inv&del_complect=".$comp['id'];
	}
	else
	{
		$link = "hero.php?func=inv&del_complect=".$comp['id']."&house&option=".$option;
	}
	echo '<a href='.$link.'>Удалить комплект</a><br>';		
	
	//Просмотр комплекта
	if (isset($_GET['show_complect']) and $_GET['show_complect']==$comp['id'])
	{
		QuoteTable('open');
		if ($comp['status']==0)
		{
			$check_items=myquery("SELECT gif.name, count(gucp.item_id) as kol FROM game_users_complects_prepare gucp JOIN game_items gi ON gucp.item_id=gi.id JOIN game_items_factsheet gif ON gi.item_id=gif.id WHERE gucp.complect_id='".$comp['id']."' GROUP BY gif.name ORDER BY gif.name");	
			if (mysql_num_rows($check_items)==0)
			{
				echo 'Вы ещё не использовали предметы!';
			}
			else
			{				
				echo '<br><font face="verdana" color="lightgreen" size="2">Вещи, используемые в комплекте:</font><ol>';
				while ($it=mysql_fetch_array($check_items))
				{
					echo '<li>'.$it['name'].' - '.$it['kol'].' шт.</li><br>';
				}
				echo '</ol>';
			}
		}
		else
		{
			// Покажем недостающие предметы
			if ($cant_up != 0)
			{
				if ($char['clevel']<$comp['clevel'])
				{
					echo '<br><font face="verdana" color="red" size="2">Ваш уровень меньше необходимого!</font><br><br>';
				}
				if ($no_items == 1)
				{
					echo '<br><font face="verdana" color="red" size="2">У Вас не хватает следующих вещей:</font><ol>';
					while (list($it_name, $it_kol) = mysql_fetch_array($need_items))
					{
						echo '<li>'.$it_name.' - '.$it_kol.' шт.</li><br>';
					}
					echo '</ol>';
				}
			}
			
			$check_items=myquery("SELECT gif.name, (CASE WHEN guci.used>0 THEN 1 ELSE 0 END) as used, count(guci.item_id) as kol FROM game_users_complects_items guci JOIN game_items_factsheet gif ON guci.item_id=gif.id WHERE guci.complect_id='".$comp['id']."' GROUP BY gif.name, guci.used ORDER BY used DESC, gif.name");
			$part=0;
			while ($it=mysql_fetch_array($check_items))
			{
				if ($part==0 and $it['used']>0)
				{
					echo '<font face="verdana" color="lightgreen" size="2">Вещи комплекта:</font>';
					$part=1;
					echo '<ol>';
				}
				elseif ($part==1 and $it['used']==0)
				{
					$part=2;
					echo '</ol>';
					echo '<br><font face="verdana" color="lightgreen" size="2">Вещи, требуемые для комплекта:</font>';
					echo '<ol>';				
				}
				echo '<li>'.$it['name'].' - '.$it['kol'].' шт.</li><br>';
			}
			echo '</ol>';
			echo '<br><font face="verdana" color="lightgreen" size="2">Характеристики, требуемые для комплекта:</font>';
			echo '<ol>';
			echo '<li>Уровень: '.$comp['clevel'].'</li><br>';
			echo '<li>Сила: '.$comp['str'].'</li><br>';
			echo '<li>Интеллект: '.$comp['ntl'].'</li><br>';
			echo '<li>Ловкость: '.$comp['pie'].'</li><br>';
			echo '<li>Защита: '.$comp['vit'].'</li><br>';
			echo '<li>Выносливость: '.$comp['dex'].'</li><br>';
			echo '<li>Мудрость: '.$comp['spd'].'</li><br>';
			echo '<li>Удача: '.$comp['lucky'].'</li><br>';
			echo '</ol>';
		}
		QuoteTable('close');
	}
	echo '<br>';
}

//Выведем ссылки для запоминания нового комплекта
if ($kol<$char['complects'])
{	
	$i++;
	echo '<b>Комплект '.$i.'</b><br>';	
	if (!$from_house)
	{
		$link = "act.php?func=inv&start_complect";
	}
	else
	{
		$link = "hero.php?func=inv&start_complect&house&option=".$option;
	}
	if ($from_house)
	{
		$link.='&house&option='.$option;   
	}
	echo '<a href='.$link.'>Запомнить новый комплект</a><br><br>';		
	$kol++;
}

if (!$from_house)
{
	$link = "act.php?func=inv&all_down";
}
else
{
	$link = "hero.php?func=inv&all_down&house&option=".$option;
}
if ($from_house)
{
	$link.='&house&option='.$option;   
}
echo '<a href='.$link.'>Снять все предметы</a>';	

echo '</td><td valign="top">';

$result_ves = myquery("SELECT CW, CC FROM game_users WHERE user_id=$user_id LIMIT 1");
$items = mysql_fetch_array($result_ves);

if ((!isset($_GET['make_amulet']) AND !isset($_GET['make_svitok'])) OR $from_house OR $from_craft)
{
	QuoteTable('open');
	echo '<b><center> Общий вес:  '.$items['CW'].' / '.$items['CC'].'</b></center>';
	$result_items = myquery("SELECT DISTINCT game_items_factsheet.type FROM game_items,game_items_factsheet WHERE game_items.item_id=game_items_factsheet.id AND game_items.user_id=$user_id AND game_items.priznak=0 AND game_items.used=0 and game_items_factsheet.type<99 and game_items_factsheet.type!=12 AND game_items_factsheet.type!=13 ORDER BY game_items_factsheet.type");
	if (mysql_num_rows($result_items))
	{
		while($result=mysql_fetch_array($result_items))
		{
			$typ=$result['type'];
			echo '<a name="anchor'.$typ.'" href="#anchor'.$typ.'" onClick=\'expand( "d'.$typ.'", "d'.$typ.'", "d'.$typ.'", "http://'.domain_name.'/funct.php?item='.$typ.''.(($from_house) ? '&house&option='.$option.'' : '').'" );\'><li><b>'.type_str($result['type']).'</b></li></a>'; 
			echo '<div id="d'.$typ.'"'; echo"style='display: none;'"; echo'><i>Загрузка</i></div>';
		}
	}
	QuoteTable('close');

	echo '<br />';

	$result_items = mysql_result(myquery("SELECT COUNT(*) from game_items LEFT JOIN game_items_factsheet ON game_items_factsheet.id=game_items.item_id WHERE game_items.user_id=$user_id AND game_items.priznak=0 AND game_items.used=0 AND game_items.count_item>0 and game_items_factsheet.type=12"),0,0);
	if ($result_items > 0)
	{
		QuoteTable('open');
		echo '<a name="anchor12" href="#anchor12" onClick=\'expand( "d12", "d12", "d12", "http://'.domain_name.'/funct.php?item=12'.(($from_house) ? '&house&option='.$option.'' : '').'" );\'><li><b>Свитки</b></li></a>';
		echo '<div id="d12"'; echo"style='display: none;'"; echo'><i>Загрузка</i></div>';
		QuoteTable('close');
		echo '<br />';
	}	
	
	$result_items = mysql_result(myquery("SELECT COUNT(*) from game_items LEFT JOIN game_items_factsheet ON game_items_factsheet.id=game_items.item_id WHERE game_items.user_id=$user_id AND game_items.priznak=0 AND game_items.used=0 AND game_items.count_item>0 and game_items_factsheet.type=13"),0,0);
	if ($result_items > 0)
	{
		QuoteTable('open');
		//отображение эликов
		echo '<a name="anchor13" href="#anchor13" onClick=\'expand( "d13", "d13", "d13", "http://'.domain_name.'/funct.php?item=13'.(($from_house) ? '&house&option='.$option.'' : '').'" );\'><li><b>Эликсиры</b></li></a>';
		echo '<div id="d13"'; echo"style='display: none;'"; echo'><i>Загрузка</i></div>';
		QuoteTable('close');
		echo '<br />';
	}

	//wm
	if (!$from_house AND !$from_craft)
	{
		//заклейменные вещи
		$is_glava = false;
		$sel_clan_items = false;
		if (mysql_num_rows(myquery("SELECT clan_id FROM game_clans WHERE glava=$user_id AND raz=0"))>0)
		{
			$is_glava = true;
			$sel_clan_items = myquery("SELECT game_items.id,game_items_factsheet.img,game_items_factsheet.name FROM game_items,game_items_factsheet WHERE game_items.item_id=game_items_factsheet.id AND game_items.kleymo=1 AND game_items.kleymo_id=".$char['clan_id']." AND (game_items.user_id<>".$char['user_id']." OR (game_items.user_id=".$char['user_id']." AND game_items.priznak NOT IN (0))) ORDER BY game_items.kleymo_nomer ASC");
		}
		$sel_user_items = myquery("SELECT game_items.id,game_items_factsheet.img,game_items_factsheet.name FROM game_items,game_items_factsheet WHERE game_items.item_id=game_items_factsheet.id AND game_items.kleymo=2 AND game_items.kleymo_id=".$char['user_id']." AND (game_items.user_id<>".$char['user_id']." OR (game_items.user_id=".$char['user_id']." AND game_items.priznak NOT IN (0))) ORDER BY game_items.kleymo_nomer ASC");  
		
		if (($sel_clan_items!=false AND mysql_num_rows($sel_clan_items)>0)OR($sel_user_items!=false AND mysql_num_rows($sel_user_items)>0))
		{
			QuoteTable('open');
			if ($sel_clan_items!=false AND mysql_num_rows($sel_clan_items)>0)
			{
				echo '<a name="anchor228" href="#anchor228" onClick=\'expand( "d228", "d228", "d228", "funct.php?item=228'.((isset($_GET['house'])) ? '&house&option='.$option.'' : '').'" );\'><li><b>Клановые клейменные предметы</b></li></a>';
				echo '<div id="d228"'; echo"style='display: none;'"; echo'><i>Загрузка</i></div>';
			}
			if ($sel_user_items!=false AND mysql_num_rows($sel_user_items)>0)
			{
				echo '<a name="anchor229" href="#anchor229" onClick=\'expand( "d229", "d229", "d229", "funct.php?item=229'.((isset($_GET['house'])) ? '&house&option='.$option.'' : '').'" );\'><li><b>Личные клейменные предметы</b></li></a>';
				echo '<div id="d229"'; echo"style='display: none;'"; echo'><i>Загрузка</i></div>';
			}
			QuoteTable('close');
		}
		
		$result_items = myquery("SELECT * FROM game_wm WHERE user_id=$user_id ORDER BY type");
		if (mysql_num_rows($result_items) > 0)
		{
			QuoteTable('open');
			echo '<table cellpadding="0" cellspacing="4" border="0">';
			while ($items = mysql_fetch_array($result_items))
			{
				$ar = wm_str($items['type']);
				$ss='&#149; '.$ar[0].' активирован';
				if ($items['type']==2)
				{
					$ss='';//&#149; <a href="act.php?tel&func=inv">Свиток телепорта</a>';
					if (!isset($_POST['see_teleport']))
					{
						$ss.='<center><form action="" method="post" autocomplete="off">';
						$ss.='Позиция: <input name="map_xpos" type="text" value="'.$char['map_xpos'].'" size="2" maxlength="2">
						<input name="map_ypos" type="text" value="'.$char['map_ypos'].'" size="2" maxlength="2">
						<input name="see_teleport" type="submit" value="Телепортироваться">';
					}
					else
					{
						$prov=myquery("select count(*) from game_map where xpos='".(int)$_POST['map_xpos']."' and ypos='".(int)$_POST['map_ypos']."' and name=".$char['map_name'].";");
						if (@mysql_result($prov,0,0)>0)
						{
							$ss.='Ты '.echo_sex('телепортировался','телепортировалась').'';
							$result=myquery("update game_users_map set map_xpos='".(int)$_POST['map_xpos']."',map_ypos='".(int)$_POST['map_ypos']."' where user_id='".$user_id."';");
						}
						else
						{
							$ss.='Такой гексы не существует';
						}
					}
				 }
				if ($items['type']==3)
				{
					$ss='';//&#149; <a href="act.php?full&func=inv">Использовать свиток полного восстановления</a>';
					if (!isset($_POST['see_full']))
					{
						$ss.='<center><form action="" method="post">';
						$ss.='<input name="see_full" type="submit" value="Полностью восcтановиться">';
					}
					else
					{
						$ss.='Ты полностью '.echo_sex('восстановлен','восстановлена').'';
						$result=myquery("update game_users set HP=HP_MAX,MP=MP_MAX,STM=STM_MAX,PR=PR_MAX where user_id='$user_id'");
					}
				}
				if ($items['type']==4)
				{
					$ss='';//&#149; <a href="act.php?mal&func=inv">Использовать свиток частичного восстановления</a>';
					if (!isset($_POST['see_mal']))
					{
						$ss.='<center><form action="" method="post">';
						$ss.='<input name="see_mal" type="submit" value="Частично восcтановиться">';
					}
					else
					{
						$ss.='Ты частично '.echo_sex('восстановлен','восстановлена').'';
						$result=myquery("update game_users set HP=HP+HP_MAX/2,MP=MP+MP_MAX/2,STM=STM+STM_MAX/2,PR=PR+PR_MAX/2 where user_id='$user_id'");
					}
				}
				echo '<tr><td><img src="http://'.img_domain.'/item/' . $ar[1] . '.gif" width="32" height="32" border="0" alt=""></td>
				<td>'.$ss.'</td>
				</tr>';
			}
			echo '</table>';
			QuoteTable('close');
			echo '<br />';
		}
	}

	if (!$from_house)
	{
		include(getenv('DOCUMENT_ROOT').'/craft/inv.inc.php');
		if (!$from_craft) include ('wm.php');
	}
}
elseif (isset($_GET['make_amulet']))
{
	$count_rune=0;
	if (isset($_POST['save']))
	{ 
		$result_items = myquery("SELECT game_items.id as idd, game_items.count_item, game_items_factsheet.* FROM game_items,game_items_factsheet WHERE game_items.user_id='".$user_id."' AND game_items.used=0 AND game_items.priznak=0 and game_items_factsheet.type=22 AND game_items.item_id=game_items_factsheet.id and game_items.ref_id=0 ORDER BY game_items_factsheet.name");
		$kol=0;
		while ($items1 = mysql_fetch_array($result_items))
		{
			if (($_POST[$items1['idd']])>0) $kol=$kol+$_POST[$items1['idd']];
			if ($_POST[$items1['idd']]>$items1['count_item']) $count_rune=1;
		}
		if ($count_rune==1)
		{
			echo '<b>У тебя недостаточно Рун для создания предмета</b>';
		}
		elseif ($kol!=7)
		{
			echo '<b>Для создания Рунного предмета необходимо использовать 7 Рун</b>';
		}
		else
		{
			$minus_weight=0;
			$ar = array();
			$ar['str']=0;$ar['dex']=0;$ar['vit']=0;$ar['spd']=0;$ar['ntl']=0;$ar['pie']=0;$ar['lucky']=0;$ar['hp_p']=0;$ar['mp_p']=0;$ar['stm_p']=0;
			$result_items = myquery("SELECT game_items.id as idd, game_items.count_item, game_items_factsheet.* FROM game_items,game_items_factsheet WHERE game_items.user_id=$user_id AND game_items.used=0 AND game_items.priznak=0 and game_items_factsheet.type=22 AND game_items.item_id=game_items_factsheet.id and game_items.ref_id=0 ORDER BY game_items_factsheet.name");
			while ($items1 = mysql_fetch_array($result_items))
			{
				if (($_POST[$items1['idd']])>0)
				{
					$ar['str']+=$items1['dstr']*$_POST[$items1['idd']];    
					$ar['dex']+=$items1['ddex']*$_POST[$items1['idd']];    
					$ar['vit']+=$items1['dvit']*$_POST[$items1['idd']];    
					$ar['spd']+=$items1['dspd']*$_POST[$items1['idd']];    
					$ar['ntl']+=$items1['dntl']*$_POST[$items1['idd']];    
					$ar['pie']+=$items1['dpie']*$_POST[$items1['idd']];    
					$ar['lucky']+=$items1['dlucky']*$_POST[$items1['idd']];    
					$ar['hp_p']+=$items1['hp_p']*$_POST[$items1['idd']];    
					$ar['mp_p']+=$items1['mp_p']*$_POST[$items1['idd']];    
					$ar['stm_p']+=$items1['stm_p']*$_POST[$items1['idd']];  
					$minus_weight+=$items1['weight']*$_POST[$items1['idd']];  
				}
			}
			$ar_type = array(7,2,8,6,5);
			$type = $ar_type[mt_rand(0,4)];
			$name = '';
			$img = '';
			$img_big = '';
			switch ($type)
			{
				case 7: 
				{
					$name = 'Редкая Магия';
					$img = 'constr/R-magia';
					$img_big = '';
				} 
				break;
				case 2: 
				{
					$name='Редкое Кольцо'; 
					$img = 'constr/R-ring';
					$img_big = '';
				}
				break;
				case 8: 
				{
					$name='Редкий Пояс'; 
					$img = 'constr/R-poyas';
					$img_big = '';
				}
				break;
				case 6: 
				{
					$name='Редкий Шлем'; 
					$img = 'constr/R-shlem';
					$img_big = '';
				}
				break;
				case 5:
				{
					$name='Редкий Доспех'; 
					$img = 'constr/R-dospeh';
					$img_big = '';
				}
				break;
			}
			$max = max($ar['str'],$ar['dex'],$ar['vit'],$ar['spd'],$ar['ntl'],$ar['pie'],$ar['lucky']);
			$kol_har = 0;
			if ($ar['str']==$max)
			{
				$name_har=' Силы';
				$kol_har++;
			}
			if ($ar['ntl']==$max)
			{
				$name_har=' Интеллекта';
				$kol_har++;
			}
			if ($ar['spd']==$max)
			{
				$name_har=' Мудрости';
				$kol_har++;
			}
			if ($ar['pie']==$max)
			{
				$name_har=' Ловкости';
				$kol_har++;
			}
			if ($ar['dex']==$max)
			{
				$name_har=' Выносливости';
				$kol_har++;
			}
			if ($ar['vit']==$max)
			{
				$name_har=' Защиты';
				$kol_har++;
			}
			if ($ar['lucky']==$max)
			{
				$name_har=' Удачи';
				$kol_har++;
			}
			if ($kol_har==1)
			{
				$name.=$name_har;
			}		
			
			//Удалим рунные предметы, оставшиеся без хозяина
			// myquery("DELETE FROM quest_constructor WHERE NOT EXISTS (SELECT user_id FROM game_items gi WHERE quest_constructor.item_id = gi.id) ");
			// $selhave = myquery("SELECT * FROM quest_constructor ORDER BY create_time ASC");
			// $count=mysql_num_rows($selhave);
			// удаляем старый предмет
			// while ($count>=5)
			// {				
				// $old_it = mysql_fetch_assoc($selhave);
				// $ItemDel = new Item($old_it['item_id']);
				// $ItemDel->admindelete();
				// $pismo = "Твой предмет ".$ItemDel->fact['name']." был разрушен, т.к. был создан новый [b]Редкий Предмет[/b]! <br />Магические силы Мира не могут удерживать более 5 Редких Предметов и твой предмет, как самый старый из всех существующих, был разрушен, оказавшись без подпитки магической энергией Средиземья!";
				// $theme = "Твой предмет ".$ItemDel->fact['name']." был разрушен";
				// myquery("DELETE FROM game_items_factsheet WHERE id=".$ItemDel->fact['id']."");
				// myquery("INSERT INTO game_pm (komu,theme,post,time) VALUES (".$old_it['user_id'].",'".$theme."','".$pismo."',UNIX_TIMESTAMP())");
				// myquery("DELETE FROM quest_constructor WHERE id=".$old_it['id']."");
				// $count=$count-1;
			// }
			// Создаём новый предмет
			$life_time = 60*60*24*30;
			$curse = "Очень редкий предмет, который создается силой Магии 7 разных Рун Амулета";
			$up=myquery("INSERT INTO game_items_factsheet (name,type,weight,curse,img,dstr,dntl,dpie,dvit,ddex,dspd,dlucky,hp_p,mp_p,stm_p,view,redkost,imgbig,personal,breakdown,item_uselife_max,life_time)
				         VALUES ('".$name."',".$type.",1,'".$curse."','".$img."','".$ar['str']."','".$ar['ntl']."','".$ar['pie']."','".$ar['vit']."','".$ar['dex']."','".$ar['spd']."','".$ar['lucky']."',
				                 '".$ar['hp_p']."','".$ar['mp_p']."','".$ar['stm_p']."',1,'K','".$img_big."',1,1,10,'".$life_time."')");
			$item_fact_id = mysql_insert_id();
			$Item = new Item();
			$ar = $Item->add_user($item_fact_id,$user_id,0);
			myquery("INSERT INTO quest_constructor SET user_id=".$user_id.",item_id=".$ar[1].",create_time=UNIX_TIMESTAMP(),dead_time=UNIX_TIMESTAMP()+".$life_time." ");
			echo '<br /><br /><center><h2>Поздравляю!</h2><br /><i>Ты успешно '.echo_sex('создал','создала').':</i><br /><br />';
			$Item->info($ar[1]);
			$result_items = myquery("SELECT game_items.id as idd, game_items.count_item, game_items_factsheet.* FROM game_items,game_items_factsheet WHERE game_items.user_id=$user_id AND game_items.used=0 AND game_items.priznak=0 and game_items_factsheet.type=22 AND game_items.item_id=game_items_factsheet.id and game_items.ref_id=0 ORDER BY game_items_factsheet.name");
			while ($items1 = mysql_fetch_array($result_items))
			{
				if (($_POST[$items1['idd']])>0 and ($items1['count_item']-$_POST[$items1['idd']])>0)
				{
					myquery("UPDATE game_items SET count_item=count_item-".$_POST[$items1['idd']]." WHERE id=".$items1['idd']."");
				}
				elseif (($_POST[$items1['idd']])>0 and ($items1['count_item']-$_POST[$items1['idd']])==0)
				{
					myquery("DELETE FROM game_items WHERE id=".$items1['idd']."");
				}
			
			}
			myquery("UPDATE game_users SET CW=CW-".$minus_weight." WHERE user_id=".$this->char['user_id']."");                
		}
	}	
	else
	{
		QuoteTable('open');
		echo '<center><br /><b>Создание Рунного Предмета из частей:</b><br /><br />Выбери части для сборки Рунного Предмета (7 рун!):</center><br />';
		$result_items = myquery("SELECT game_items.id, game_items.count_item, game_items_factsheet.name FROM game_items,game_items_factsheet WHERE game_items.user_id=$user_id AND game_items.used=0 AND game_items.priznak=0 and game_items_factsheet.type=22 AND game_items.item_id=game_items_factsheet.id and game_items.ref_id=0 ORDER BY game_items_factsheet.name");
		if ($result_items!=false AND mysql_num_rows($result_items)>0)
		{
			echo '<form name="constr" method="post" action="act.php?func=inv&make_amulet">
			<table cellpadding="0" cellspacing="2" border="0" width="360">';
			while ($items = mysql_fetch_array($result_items))
			{
				$Item = new Item($items['id']);
				echo '<tr>
				<td>';
				$Item->hint(0,1,'<span '); 
				ImageItem($Item->fact['img'],0,$Item->item['kleymo']);
				echo '</span>
				</td>
				<td>
				'.$items['name'].'
				</td>
				<td>
				<input type="textbox" name='.$items['id'].' value="0" size="1" maxlength="1" border="5" > 
				</td>
				<td>
				(Есть: '.$items['count_item'].' '.pluralForm($items['count_item'],'руна','руны','рун').')
				</td>
				</tr>';
			}
			echo '</table>
			<center><input type="submit" name="save" value="Собрать амулет из отмеченных частей">
			</form>';
		}
		QuoteTable('close');
	}
}
elseif (isset($_GET['make_svitok']))
{
     $minus_weight = 5*mysqlresult(myquery("SELECT weight FROM game_items_factsheet WHERE id=".item_id_part_svitok_hranitel.""),0,0);
     list($result_items) = mysql_fetch_array(myquery("SELECT game_items.count_item FROM game_items WHERE game_items.user_id=$user_id AND game_items.priznak=0 and item_id=".item_id_part_svitok_hranitel.""));
	 if ($result_items<5)
	 {
		echo '<br /><br />&nbsp;&nbsp;&nbsp;&nbsp;<b>Для сборки Свитка Хранителя необходимо 5 частей</b><br /><br />';
		echo ''.$result_items.'aa';
	 }
	 else
	 {
		$Item = new Item();
        $ar = $Item->add_user(item_id_svitok_hranitel,$user_id,0);
        if ($ar[0]>0)
        {
             echo '<br /><br /><center><h2>Поздравляю!</h2><br /><i>Ты успешно '.echo_sex('создал','создала').': Свиток Хранителя</i><br /><br />';
			$Item->info($ar[1]);
			if ($result_items==5) myquery("DELETE FROM game_items WHERE game_items.user_id=$user_id AND game_items.priznak=0 and item_id=".item_id_part_svitok_hranitel."");
			else myquery("Update game_items Set count_item=count_item-5 WHERE game_items.user_id=$user_id AND game_items.priznak=0 and item_id=".item_id_part_svitok_hranitel.""); 
            myquery("UPDATE game_users SET CW=CW-$minus_weight WHERE user_id=$user_id");                
        }
	 }
}
elseif (isset($_GET['make_svitok_ice']))
{
	 $minus_weight = 10*mysqlresult(myquery("SELECT weight FROM game_items_factsheet WHERE id=".item_id_part_svitok_ice_portal.""),0,0);
     $result_items = myquery("SELECT Sum(game_items.count_item) as Count FROM game_items WHERE game_items.user_id=$user_id AND game_items.priznak=0 and item_id=".item_id_part_svitok_ice_portal."");
	 $result_items = mysql_fetch_array($result_items);
	 if ($result_items['count']<10)
	 {
		echo '<br /><br />&nbsp;&nbsp;&nbsp;&nbsp;<b>Для сборки Свитка Снежного Портала необходимо 10 частей</b><br /><br />';
	 }
	 else
	 {
		$Item = new Item();
        $ar = $Item->add_user(item_id_svitok_ice_portal,$user_id,0);
        if ($ar[0]>0)
        {
             echo '<br /><br /><center><h2>Поздравляю!</h2><br /><i>Ты успешно '.echo_sex('создал','создала').':</i><br /><br />';
			$Item->info($ar[1]);
			if ($result_items['count']=5) myquery("DELETE FROM game_items WHERE game_items.user_id=$user_id AND game_items.priznak=0 and item_id=".item_id_part_svitok_ice_portal.")");
			else myquery("DELETE game_items Set count_item=count_item-5 WHERE game_items.user_id=$user_id AND game_items.priznak=0 and item_id=".item_id_part_svitok_ice_portal.")"); 
            myquery("UPDATE game_users SET CW=CW-$minus_weight WHERE user_id=$user_id");                
        }
	 }
}



if (isset($_SESSION['error_inv']) and $_SESSION['error_inv']=='error_ident')
{
	QuoteTable('open');
	echo '<span class="ERROR">Не идентифицировано</span>';
	QuoteTable('close');
	$_SESSION['error_inv'] = '';
};
if (isset($_SESSION['error_inv']) and $_SESSION['error_inv']=='error_broken')
{
	QuoteTable('open');
	echo '<span class="ERROR">Предмет сломан (или в нем закончились заряды)</span>';
	QuoteTable('close');
	$_SESSION['error_inv'] = '';
};
if (isset($_SESSION['error_inv']) and $_SESSION['error_inv']=='error_stat')
{
	QuoteTable('open');
	$printerror='Нужно: '.$_SESSION['error_stat'];
	echo'<table cellpadding="0" cellspacing="4" border="0"><tr><td valign="center">'.$printerror.'</td></tr></table>';
	QuoteTable('close');
	$_SESSION['error_inv'] = '';
}
if (isset($identify_id))
{
	$Item = new Item();
	$Item->info($identify_id,1);
}

echo '</td></tr></table>';

?>
<script type="text/javascript" language="JavaScript">
p = new Array();
function ge(a)
{
	if( document.all )
		return document.all[a];
	else
		return document.getElementById( a );
}
function load(pp,str)
{
	if( p[pp] )
		return;
	p[pp] = 1;
	<?
	if (!$from_house)
		echo 'parent.game.xssa.location.href = str;';
	else
		echo 'this.xssa.location.href = str;';
	?>
 }
function expand(a,b,pp,str)
{
	if( ge( b ).style )
		dsp = ge( b ).style.display;
	else
		dsp = ge( b ).display;
	if( dsp == 'none' )
	{
		if( ge( b ).style )
			dsp = ge( b ).style.display = 'block';
		else
			dsp = ge( b ).display = '';
	}
	else
	{
		if( ge( b ).style )
			ge( b ).style.display = 'none';
		else
			ge( b ).display = 'none';
	}
	load(pp,str)
}
</script>
<?
echo'<iframe style="width:0px;height:0px;border:1px;" name="xssa" id="frame_xssa" src=""></iframe>';

echo'</td></tr></table>';
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

//if ($char['clevel']>=5) check_obnul(1);

//Проверим, действуют ли на игрока какие-либо эликсиры:
$check_elik=myquery("Select * From game_obelisk_users Where user_id=$user_id Order by type, time_end");
if (mysql_num_rows($check_elik)>0)
{
	echo '<br><b><font color="#ffff00">На Вас действуют следующие временные эффекты:</font></b>';
	while ($elik=mysql_fetch_array($check_elik))
	{
		switch ($elik['type'])
		{
			case 2:
					switch ($elik['harka'])
					{
						case "HP_MAX":
								$mes="Уровень жизней изменён ";
								break;
						case "MP_MAX":
								$mes="Уровень маны изменён ";
								break;
						case "STM_MAX":
								$mes="Уровень энергии изменён ";
								break;
						case "STM":
								$mes="Уровень энергии изменён ";
								break;
						case "PR_MAX":
								$mes="Максимальный уровень праны изменён ";
								break;
						case "PR":
								$mes="Уровень праны изменён ";
								break;		
						case "CC":
								$mes="Максимальный вес изменён ";
								break;
						case "STR":
								$mes="Сила изменена ";
								break;
						case "SPD":
								$mes="Мудрость изменена ";
								break;
						case "NTL":
								$mes="Интеллект изменён ";
								break;
						case "PIE":
								$mes="Ловкость изменена ";
								break;
						case "PIE":
								$mes="Ловкость изменена ";
								break;		
						case "VIT":
								$mes="Защита изменена ";
								break;	
						case "DEX":
								$mes="Выносливость изменена ";
								break;	
						case "LUCKY":
								$mes="Удача изменена ";
								break;	
					}
					$mes=$mes."на <b>".$elik['value']."</b> ".pluralForm($elik['value'],'единицу','единицы','единиц')." до <b>".date("H:i d.m.Y",$elik['time_end'])."</b>";
					break;
			case 3:
					$mes="Вы не обращаете внимания на перевес <b>".date("H:i d.m.Y",$elik['time_end'])."</b>";
					break;
			case 4:
					$mes="Вы видите невидимых до <b>".date("H:i d.m.Y",$elik['time_end'])."</b>";
					break;
			case 5:
					$mes="Вы невидимы до <b>".date("H:i d.m.Y",$elik['time_end'])."</b>";
					break;
			case 6:
					$mes="Вы неуязвимы для нападений других игроков до <b>".date("H:i d.m.Y",$elik['time_end'])."</b>";
					break;
		}
		if (isset ($mes)) echo '<br>'.$mes;
	}
	echo "<br>";
}

echo'</td></tr>';
echo '</div>';

//------------------2.2 Умения персонажа---------------------------------action="act.php?func=inv&start_complect"

echo '<tr><td>';	
echo '<div id="skills" class="tabset_content">';
$no_skill = 1;
echo '<b>';

echo '<font size="2" color="lightblue">';
//Форма активации защиты от нападений
if ($char['hide_charges']>0)
{	
	$no_skill = 0;	
	echo '<form method="POST">
	<br>У вас есть '.$char['hide_charges'].' '.pluralForm($char['hide_charges'],'заряд','заряда','зарядов').' для активирации защиты от нападений. 
	<br><input type="submit" value="Активировать" name="get_hide"> <input type="text" name="kol_charges" value="'.$char['hide_charges'].'" size="2" maxsize="2"> зарядов	
	</form>';
}

if ($no_skill==1)
{
	echo '<br><br>У Вас нет никаких умений!';
}
echo '</b></font></div>';
echo'</td></tr></table>';
OpenTable('close');
echo '</div>';

echo'</td><td valign="top" width="200"><table border=0 width=172 cellspacing="0" cellpadding="0"><tr><td>';

if ($from_house OR $from_craft)
{
	echo '<table cellpadding=0 cellspacing=0 border=0 width=218 background="http://'.img_domain.'/nav1/bar2.gif"><tr><td align=center>';  
	OpenTable('title','89%');
	echo'<table border=0 width=100% align=center>
	<tr><td><font face=Verdana size=2 color="white"><b><center>'.$char['name'].'  <span title="Твой уровень">['.$char['clevel'].']</span><span title="Реинкарнация">['.$char['reinc'].']</span>
	</center></b></font></td></tr></table>';
	
	echo '<table cellpadding="1" cellspacing="0" width="100%" border="0">';

	if ($char['HP_MAX'] == 0)
	{
		$bar_percentage = 0;
	}
	else
	{
		$bar_percentage = number_format($char['HP'] / $char['HP_MAX'] * 100, 0);
	}

	if ($bar_percentage >= '100')
	{
		$append_string = '<img src="http://'.img_domain.'/bar/bar_green.gif" width="100" height="7" border="0">';
	}
	elseif ($bar_percentage <= '0')
	{
		$append_string = '<img src="http://'.img_domain.'/bar/bar_empty.gif" width="100" height="7" border="0">';
	}
	else
	{
		$append_string = '<img src="http://'.img_domain.'/bar/bar_empty.gif" width="' . (100 - $bar_percentage) . '" height="7" border="0"><img src="http://'.img_domain.'/bar/bar_green.gif" width="' . $bar_percentage . '" height="7" border="0">';
	}

	echo '
	<tr>
	<td align="left" valign="middle"><font face="Verdana" size="1">Здоровье</font></td>
	<td align="right"><font face="Verdana" size="1">' . $char['HP'] . ' / ' . $char['HP_MAX'] . '</font>';
	if ($char['HP_MAX']<$char['HP_MAXX'])
	{
		echo '<span title="Ты '.echo_sex('получил','получила').' травму!" style="font-weight:800;font-size:10px;color:red;">(-'.($char['HP_MAXX']-$char['HP_MAX']).')</span>';
	}
	echo'<br>
	<img src="http://'.img_domain.'/bar/bar_side.gif" width="1" height="7" border="0">'
	. $append_string . '<img src="http://'.img_domain.'/bar/bar_side.gif" width="1" height="7" border="0"><br>
	<img src="http://'.img_domain.'/nav/x.gif" width="0" height="0" vspace="2" border="0"></td>
	</tr>';

	if ($char['MP_MAX'] == 0)
	{
		$bar_percentage = 0;
	}
	else
	{
		$bar_percentage = number_format($char['MP'] / $char['MP_MAX'] * 100, 0);
	}
	if ($bar_percentage >= '100')
	{
		$append_string = '<img src="http://'.img_domain.'/bar/bar_orange.gif" width="100" height="7" border="0">';
	}
	elseif ($bar_percentage <= '0')
	{
		$append_string = '<img src="http://'.img_domain.'/bar/bar_empty.gif" width="100" height="7" border="0">';
	}
	else
	{
		$append_string = '<img src="http://'.img_domain.'/bar/bar_empty.gif" width="' . (100 - $bar_percentage) . '" height="7" border="0"><img src="http://'.img_domain.'/bar/bar_orange.gif" width="' . $bar_percentage . '" height="7" border="0">';
	}
	echo '<tr>
	<td align="left" valign="middle"><font face="Verdana" size="1">Мана</font></td>
	<td align="right"><font face="Verdana" size="1">' . $char['MP'] . ' / ' . $char['MP_MAX'] . '</font><br>
	<img src="http://'.img_domain.'/bar/bar_side.gif" width="1" height="7" border="0">'
	. $append_string . '<img src="http://'.img_domain.'/bar/bar_side.gif" width="1" height="7" border="0"><br>
	<img src="http://'.img_domain.'/nav/x.gif" width="0" height="0" vspace="2" border="0"></td>
	</tr>';

	if ($char['STM_MAX'] == 0)
	{
		$bar_percentage = 0;
	}
	else
	{
		$bar_percentage = number_format($char['STM'] / $char['STM_MAX'] * 100, 0);
	}
	if ($bar_percentage >= '100')
	{
		$append_string = '<img src="http://'.img_domain.'/bar/bar_yellow.gif" width="100" height="7" border="0">';
	}
	elseif ($bar_percentage <= '0')
	{
		$append_string = '<img src="http://'.img_domain.'/bar/bar_empty.gif" width="100" height="7" border="0">';
	}
	else
	{
		$append_string = '<img src="http://'.img_domain.'/bar/bar_empty.gif" width="' . (100 - $bar_percentage) . '" height="7" border="0"><img src="http://'.img_domain.'/bar/bar_yellow.gif" width="' . $bar_percentage . '" height="7" border="0">';
	}
	echo '<tr>
	<td align="left" valign="middle"><font face="Verdana" size="1">Энергия</font></td>
	<td align="right"><font face="Verdana" size="1">' . $char['STM'] . ' / ' . $char['STM_MAX'] . '</font><br>
	<img src="http://'.img_domain.'/bar/bar_side.gif" width="1" height="7" border="0">'
	. $append_string . '<img src="http://'.img_domain.'/bar/bar_side.gif" width="1" height="7" border="0"><br>
	<img src="http://'.img_domain.'/nav/x.gif" width="0" height="0" vspace="2" border="0"></td>
	</tr>';


	if ($char['PR_MAX'] == 0)
	{
		$bar_percentage = 0;
	}
	else
	{
		$bar_percentage = number_format($char['PR'] / $char['PR_MAX'] * 100, 0);
	}
	if ($bar_percentage >= '100')
	{
		$append_string = '<img src="http://'.img_domain.'/bar/bar_red.gif" width="100" height="7" border="0">';
	}
	elseif ($bar_percentage <= '0')
	{
		$append_string = '<img src="http://'.img_domain.'/bar/bar_empty.gif" width="100" height="7" border="0">';
	}
	else
	{
		$append_string = '<img src="http://'.img_domain.'/bar/bar_empty.gif" width="' . (100 - $bar_percentage) . '" height="7" border="0"><img src="http://'.img_domain.'/bar/bar_red.gif" width="' . $bar_percentage . '" height="7" border="0">';
	}
	echo '<tr>
	<td align="left" valign="middle"><font face="Verdana" size="1">Прана</font></td>
	<td align="right"><font face="Verdana" size="1">' . $char['PR'] . ' / ' . $char['PR_MAX'] . '</font><br>
	<img src="http://'.img_domain.'/bar/bar_side.gif" width="1" height="7" border="0">'
	. $append_string . '<img src="http://'.img_domain.'/bar/bar_side.gif" width="1" height="7" border="0"><br>
	<img src="http://'.img_domain.'/nav/x.gif" width="0" height="0" vspace="2" border="0"></td>
	</tr>';

	$clevel=$char['clevel'];
	$new_clevel=get_new_level($clevel);

	$bar_percentage = number_format($char['EXP'] / $new_clevel * 100, 0);
	if ($bar_percentage >= 100)
	{
		$append_string = '<img src="http://'.img_domain.'/bar/bar_blue.gif" width="100" height="7" border="0">';
	}
	elseif ($bar_percentage <= 0)
	{
		$append_string = '<img src="http://'.img_domain.'/bar/bar_empty.gif" width="100" height="7" border="0">';
	}
	else
	{
		$append_string = '<img src="http://'.img_domain.'/bar/bar_empty.gif" width="' . (100 - $bar_percentage) . '" height="7" border="0"><img src="http://'.img_domain.'/bar/bar_blue.gif" width="' . $bar_percentage . '" height="7" border="0">';
	}
	echo '<tr>
	<td align="left" valign="middle" title="Текущий опыт/Опыт до уровня"><font face="Verdana" size="1">Опыт</font></td>
	<td align="right" title="Текущий опыт/Опыт до уровня"><font face="Verdana" size="1">' . $char['EXP'] . ' / ' . $new_clevel . '</font><br>
	<img src="http://'.img_domain.'/bar/bar_side.gif" width="1" height="7" border="0">'
	. $append_string . '<img src="http://'.img_domain.'/bar/bar_side.gif" width="1" height="7" border="0"><br>
	<img src="http://'.img_domain.'/nav/x.gif" width="0" height="0" vspace="2" border="0"></td>
	</tr>';

	echo '
	<tr><td align="left"><font face="Verdana" size="1">Деньги</font></td><td align="right"><font face="Verdana" size="1"><img src="http://'.img_domain.'/nav/gold.gif" width="10" height="10" border="0">'.trim($char['GP']).'</font></td></tr>';

	echo '</table>';
	OpenTable('close');
	echo'<img src="http://'.img_domain.'/nav1/bar1.gif"></td></tr></table>';
}
else
{
	include('inc/template_stats.inc.php');
}
echo'</td></tr></table></td></tr></table>';
set_delay_reason_id($user_id,22);


if (function_exists("save_debug")) save_debug(); 

?>