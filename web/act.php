<?
include('inc/config.inc.php');
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

include('inc/lib_session.inc.php');
include('inc/functions.php');

setFunc($user_id,5);

if (function_exists("start_debug")) start_debug();

if ($_SERVER['PHP_SELF']!="/act.php")
{
	die();
}

if (isset($_GET['func']))
{
	$func = $_GET['func'];
}
else
{
	$func = 'main';
}

require_once('inc/template_header.inc.php');

function pay_ref($user_id,$gp_amount)
{

		// реферальные
		$ref_pay = myquery("SELECT * FROM game_invite WHERE invite_id='".$user_id."' ");
		if (mysql_num_rows($ref_pay)>0)
		{
			$arr_ref_pay=mysql_fetch_array($ref_pay);
			$up=myquery("update game_users SET GP=GP+".$gp_amount.",CW=CW+'".($gp_amount*money_weight)."' where user_id='".$arr_ref_pay['user_id']."'");
			setGP($arr_ref_pay['user_id'],$gp_amount,3);
			$up=myquery("update game_users_archive SET GP=GP+".$gp_amount.",CW=CW+'".($gp_amount*money_weight)."' where user_id='".$arr_ref_pay['user_id']."'");

		}


}

// Init google gears
echo ("<script type=\"text/javascript\">var MANIFEST_FILENAME = \"http://".img_domain."/gears/sz.json\";</script>\n");
//echo ("<script type=\"text/javascript\"></script>\n");
?>
<script type="text/javascript" language="JavaScript">
function upchat()
{
	ch_fr = top.window.frames.chat;
	if (ch_fr)
	{
		bt = ch_fr.document.getElementById("chat_text");
		if (bt)
		{
			if (bt.style.display!="block")
			{
				bt.style.display="block";
			}
		}
		bt = ch_fr.document.getElementById("combat_text");
		if (bt)
		{
			if (bt.style.display!="none")
			{
				bt.style.display="none";
			}
		}
		bt = ch_fr.document.getElementById("arcomage_text");
		if (bt)
		{
			if (bt.style.display!="none")
			{
				bt.style.display="none";
			}
		}
		bt = ch_fr.document.getElementById("select_game_chat");
		if (bt)
		{
			if (bt.style.display!="none")
			{
				bt.style.display="none";
			}
		}
		bt = ch_fr.document.getElementById("select_combat_chat");
		if (bt)
		{
			if (bt.style.display!="none")
			{
				bt.style.display="none";
			}
		}
		bt = ch_fr.document.getElementById("select_arcomage_chat");
		if (bt)
		{
			if (bt.style.display!="none")
			{
				bt.style.display="none";
			}
		}
	}
}
upchat();
</script>
<?

$map = mysql_fetch_array(myquery("SELECT * FROM game_maps WHERE id='".$char['map_name']."'"));
$map_save = $map;
$add_har = 0;
$add_nav = 0;
$add_gp = 0;
$col_up = 0;
$clevel = $char['clevel'];
if ($clevel < 40 && $char['map_name'] != 666)
{
	$new_clevel = get_new_level($clevel);
    $all_exp = $char['EXP'];
    $col_up = 0;
    $minus_exp = 0;
    $add_clevel = 0;
    $up_newbie = 0;
	$add_vsadnik = 0;
	$add_kulak = 0;
    while ($all_exp > $new_clevel)
    {
        $col_up++;
        $minus_exp+=$new_clevel;
        $add_clevel++;
        if (($char['clevel']+$add_clevel)<=5) $up_newbie++;
        $all_exp-=$new_clevel;
        $clevel++;
        $new_clevel = get_new_level($clevel);
        $new_level = $char['clevel']+$add_clevel;
		if ($new_level >= 0 and $new_level < 10) { $add_gp+=50; }
        elseif ($new_level == 10) {$add_gp+=300; $add_har+=1;}
        elseif ($new_level > 10 and $new_level < 20) { $add_gp+=100; }
        elseif ($new_level == 20) {$add_gp+=500; $add_har+=1;}
        elseif ($new_level > 20 and $new_level < 30){ $add_gp+=200; }
        elseif ($new_level == 30) {$add_gp+=1000; $add_har+=1;}
        elseif ($new_level > 30 and $new_level < 40){ $add_gp+=300; }
        elseif ($new_level == 40) {$add_gp+=1500; $add_har+=1; $result_exp=$all_exp; $all_exp=0;}
		else { $all_exp=0; };
		$add_har+=2;
		if ($clevel%3==0) $add_nav+=1;
		if ($char['reinc']==0 and $clevel<6) $add_kulak+=3;
		if ($char['reinc']==0 and $clevel==5) $add_vsadnik=1;		
    }
}
if ($col_up>0)
{
	// Очищение заявок на то, чтобы игрок стал Учеником
	if ($char['reinc'] == 0 and $char['clevel']<15 and $clevel>=15)
	{
		myquery("DELETE FROM game_tutorship WHERE pupil_id = ".$user_id." and confirmed = 0");
	}
	
	// Игроку добавление денег, увеличение уровня, реинкарнация
	if ($clevel == 40) 
	{		
		$after_reinc_level=15+$char['reinc'];		
	}
	
	if ($clevel <> 40 or $after_reinc_level == 40)
	{
		//Обновим персонажа при достижении обычного уровня
		$up = myquery("UPDATE game_users SET clevel = $clevel,EXP=EXP-$minus_exp, bound=bound+$add_har, exam=exam+$add_nav, GP=GP+$add_gp,CW=CW+'".($add_gp*money_weight)."' WHERE user_id='$user_id'");
		
		if ($add_kulak>0)
		{
			add_skill($user_id,21,$add_kulak);			
		}
		
		if ($add_vsadnik>0)
		{
			add_skill($user_id,25,$add_vsadnik);
		}
		
		if ($char['reinc']==0 and $clevel>=5 and $char['clevel']<5)
		{
			$slevel=15*(2-$char['reinc']);
			add_skill($user_id,32,$slevel);
		}
		
		$char['clevel'] = $clevel;        
	    echo'<br><center><b><font face=verdana size=2 color=ff0000>Ты '.echo_sex('развился','развилась').' до '.$char['clevel'].' уровня!
	    <br>Ты получаешь: '.$add_gp.' золотых, '.$add_nav.pluralForm($add_nav,' дополнительный навык',' дополнительных навыка',' дополнительных навыков').' и '.$add_har.pluralForm($add_har,' дополнительную характеристику',' дополнительные характеристики',' дополнительных характеристик').'!</font></b></center>';
	}
	else
	{
		//Обновим персонажа при достижении 40-ого уровня
		
		// Обработка ученичества
		if ($char['reinc'] == 0)
		{
			myquery("UPDATE game_tutorship SET confirmed = 2 WHERE pupil_id = ".$user_id." and confirmed = 1");
			
		}
		elseif (($char['reinc']+1) % 2 == 0)
		{
			$check_tutor = myquery("SELECT user_id FROM game_tutorship WHERE pupil_id = ".$user_id." and confirmed = 2");
			if (mysql_num_rows($check_tutor)>0)
			{
				list($tutor_id) = mysql_fetch_array($check_tutor);
				myquery("UPDATE game_users_data SET user_rating=user_rating+1 WHERE user_id = ".$tutor_id." ");
				$theme = 'Гильдия Наставников';
				$post = 'Ваш Ученик <b>'.$char['name'].'</b> реинкарнировался и принёс Вам 1 ЛР!';
				myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time, folder) VALUES ('".$tutor_id."', '0', '".$theme."', '".$post."','0','".time()."',1)");
			}			
		}		
		
		$char['reinc'] = $char['reinc'] + 1;
		$char['clevel'] = $after_reinc_level;
		
		//Подсчитаем количество навыков и харок
		$add_nav=get_skills_number($char['reinc']);
		$add_har=get_harks_number($after_reinc_level, $char['reinc']);
		
		//Удалим коня
		return_horse($char['user_id']);	
		
		//Обнулим данные игрока
		user_reset($user_id, $char['race']);		
		
		//Забудем все профессии игрока
		user_craft_reset($user_id);
		
		//Выдадим игроку специализации
		add_skill_system($user_id,$char['reinc'],$char['clevel']);	
	
		//Обновим данные игрока		
		$up = myquery("UPDATE game_users SET clevel=$after_reinc_level, reinc=reinc+1, EXP=$result_exp, bound=$add_har, exam=$add_nav, GP=GP+$add_gp, CW=CW+'".($add_gp*money_weight)."'
		WHERE user_id='$user_id'");		
		
		//Повысим временные харки игрока
		add_time_harks($user_id);
		
		//Выдадим игроку 1 эликсир бодрости
		$check_elik=myquery("SELECT id FROM game_items_factsheet WHERE name like 'Эликсир бодрости'");
		while (list($id)=mysql_fetch_array($check_elik))
		{
			$Item = new Item();
			$ar = $Item->add_user($id,$user_id,0);
		}
		
		//Выдадим игроку 1 ЛР
		add_lr($user_id, 1);
		
		//Запишем данные о реинкаранции игрока
		set_reincarnation($user_id, $char['reinc']);
		
		//Выведем сообщение о пройдённой реинкаранции		
		echo'<br><center><b><font face=verdana size=2 color=ff0000>Ты '.echo_sex('прошёл','прошла').' '.($char['reinc']).' реинкарнацию! 
	    <br>Ты продолжаешь игру с '.$after_reinc_level.' уровня!</font></b></center>';		
	}
	
	setGP($user_id,$add_gp,21);

    if ($char['map_name']==700)
    {
		pay_ref($user_id,$up_newbie*10);
    }
    

	if (($char['clevel']-$add_clevel)<5 AND $char['clevel']>=5 AND $char['map_name']==700)
	{
		pay_ref($user_id,50);
        myquery("UPDATE game_users_data SET obnul=1 WHERE user_id=$user_id");
		include("lib/newbie.php");
		{if (function_exists("save_debug")) save_debug(); exit;}
	}
    else
    {
        echo '<meta http-equiv="refresh" content="6;url=act.php">';        
    }
}
elseif ($char['clevel']>=5 AND $char['map_name']==700)
{
    include("lib/newbie.php");
    {if (function_exists("save_debug")) save_debug(); exit;}
}


if (isset($do_exit))
{
	include("lib/newbie.php?do_exit");
	{if (function_exists("save_debug")) save_debug(); exit;}
}

if ($func=='main' OR $func=='inv' OR $func=='hero' OR $func=='online' OR $func=='setup' OR $func=='pm' OR $func=='npc_fav' OR $func=='help_newbie')
{
	if (isset($userban) and $userban['type']==3)
	{
		OpenTable('title');
		echo '
		<table border=0><tr><td align=center><b><font color=#FF0000 face=Verdana size=3>ВНИМАНИЕ!!! Администраторами игры ТЕБЕ вынесено предупреждение сроком на '.ceil(($userban['time']-time())/60).' минут!<br>Требуем от ТЕБЯ соблюдения законов игры! В противном случае в след.раз ТЫ будешь '.echo_sex('отправлен','отправлена').' в бан и не сможешь играть!</font></b></td></tr><tr><td align=center><font color=#00FF00 face=Verdana size=3><br><br>'.$userban['za'].'</td></tr><tr><td align=right><b><font color=#FF0000 face=Verdana size=3><br><br>Администрация проекта &quot;Средиземье&quot;<b></td></tr></table>';
		OpenTable('close');
	}
}

switch($func)
{
	case 'main':
	include('lib/menu.php');
	include('lib/main.php');
	break;

	case 'battle':
	include('lib/menu.php');
	include('lib/main.php');
	break;

	case 'inv':
	include('lib/menu.php');
	include('lib/hero.php');
	break;

	case 'hero':
	include('lib/menu.php');
	include('lib/hero.php');
	break;

	case 'online':
	include('lib/menu.php');
	include('lib/online.php');
	break;

	case 'action':
	include('lib/menu.php');
	include('lib/action.php');
	break;

	case 'npc':
	include('lib/menu.php');
	include('lib/action_npc.php');
	break;

	case 'setup':
	include('lib/menu.php');
	include('lib/options.php');
	break;

	case 'pm':
	include('lib/menu.php');
	include('lib/pm.php');
	break;

	case 'shop':
	include('lib/menu.php');
	include('lib/shop.php');
	break;

	case 'npc_fav':
	include('lib/menu.php');
	include('lib/npc_fav.php');
	break;
	
	case 'gift':
	include('lib/menu.php');
	include('lib/gift.php');
	break;

	case 'help_newbie':
	include('lib/menu.php');
	include('lib/help_newbie.php');
	break;

	case 'boy':
	include('lib/menu.php');
	include('lib/boy.php');
	break;

	case 'spell_book':
	include('lib/spell_book.php');
	break;

	case 'jaloba':
	include('lib/menu.php');
	include('lib/jaloba.php');
	break;

	// Окно Журнала, где в будущем будет располагаться общая статистика для пользователя в игре
	// по задумке в том числе и игровые события
	case 'jurnal':
	include('lib/menu.php');
	include('lib/jurnal.php');
	break;	
}

include("inc/template_footer.inc.php");

show_debug($char['name']);

if (function_exists("save_debug")) save_debug();
mysql_close();
?>