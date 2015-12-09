<?php
//ЛЕСОПИЛКА - ОКОНЧАНИЕ
$add_query = '';
$sawmill_level = getCraftLevel($user_id,7);
$add_doska = 1;
$add_strela = 2;
$add_topor = 1;
$add_kopye = 1;
if ($sawmill_level>=4)
{
	$add_doska++; $add_kopye++; $add_strela++; $add_strela++; $add_topor++;
}
if ($sawmill_level>=9)
{
	$add_doska++; $add_kopye++; $add_strela++; $add_strela++; $add_topor++;
}
if ($sawmill_level>=14)
{
	$add_doska++; $add_kopye++; $add_strela++; $add_strela++; $add_topor++;
}
if ($sawmill_level>=19)
{
	$add_doska++; $add_kopye++; $add_strela++; $add_strela++; $add_topor++;
}

$kol_res_in = 0;
$kol_res_out = 1;
$res_id_out = 0;
$res_id_in = 0;
switch ($rab['eliksir'])
{
	case '1':
	{
		//распилка бревна на доски
		setCraftTimes($user_id,7,1,1);
		$kol_res_in = $add_doska;
		$res_id_in = $id_resource_doska;
		$res_id_out = $id_resource_brevno;
	}
	break;
	
	case '2':
	{
		//приготовление черенков стрел из досок
		$kol_res_in = $add_strela;
		$res_id_in = $id_resource_strela;
		$res_id_out = $id_resource_doska;
	}
	break;
	
	case '3':
	{
		//приготовление рукоятей топоров из досок
		$kol_res_in = $add_topor;
		$res_id_in = $id_resource_topor;
		$res_id_out = $id_resource_doska;
	}
	break;
	
	case '4':
	{
		//приготовление древок копий из досок
		$kol_res_in = $add_kopye;
		$res_id_in = $id_resource_kopye;
		$res_id_out = $id_resource_doska;
	}
	break;
	
	default: exit; break;
}  

//Забираем расходник	
$res_out = mysql_fetch_array(myquery("SELECT * FROM craft_resource WHERE id=$res_id_out"));
$Res = new Res($res_out);
$Res->add_user(0, $user_id, -1);
$mes='Израсходован ресурс: <i>'.$res_out['name'].'</i> в количестве 1 ед. <br/>';
myquery("insert into craft_stat (build_id, gp, res_id, dob, vip, dat, user, type) values (0, 0, $res_id_out, 0, -1, ".time().", $user_id, 'z')");  
$char['CW'] = $char['CW'] - $res_out['weight'];

$res_in = mysql_fetch_array(myquery("SELECT * FROM craft_resource WHERE id=$res_id_in"));
$Res1 = new Res($res_in);
$check = $Res1->add_user(0,$user_id,$kol_res_in);
if ($check == 1)
{	
	add_exp_for_craft($user_id, 7);
	myquery("insert into craft_stat (build_id, gp, res_id, dob, vip, dat, user, type) values (0, 0, $res_id_in, 0, 1, ".time().", $user_id, 'z')");
	setCraftTimes($user_id,7,1,1);
	$mes.='Получен ресурс: <i>'.$res_in['name'].'</i> в количестве '.$kol_res_in.' ед.';
}
else
{
	$mes.='<b>У Вас недостаточно свободного места в инвентаре!</b>';
}

//Поломаем предмет
mt_srand(make_seed());
myquery("UPDATE game_items SET item_uselife=item_uselife-".(mt_rand(100,250)/100)." WHERE user_id=$user_id AND used=21 AND priznak=0");
list($id,$uselife) = mysql_fetch_array(myquery("SELECT id,item_uselife FROM game_items WHERE user_id=$user_id AND used=21 AND priznak=0"));
if ($uselife<=0)
{
	$Item = new Item($id);
	$Item->down();    
}

if ($rab['add']>0)	
{
	$option = 18;
	if (domain_name=='localhost') $option=19;
	$url = 'lib/town.php?option='.$option.'&part4&add='.$rab['add'].'&mes='.$mes;
}
else
{
	$url = 'act.php?func=main&act=01&sawmill&mes='.$mes;
}
setLocation($url);
exit_from_craft($add_query,1);
if ($_SERVER['REMOTE_ADDR']==debug_ip)
{
	show_debug();
}
{if (function_exists("save_debug")) save_debug(); exit;}
?>