<?php
$meating_level = getCraftLevel($user_id,9);

$kol_res_in = 0;
$kol_res_out = 1;
$res_id_out = $id_resource_olencorpse;
$res_id_in = 0;
$add_query = '';

mt_srand(make_seed());
$chance_olenkoja = 10+2*$meating_level;
$chance_olenkosti = 25+2*$meating_level;
$chance_olenjily = 30+2*$meating_level;
$max_chance=100;
if ($chance_olenkoja+$chance_olenkosti+$chance_olenjily>100)
{
	$max_chance=$chance_olenkoja+$chance_olenkosti+$chance_olenjily;	
}
$rand = mt_rand(0,$max_chance);
if ($rand>0 AND $rand<=$chance_olenkoja)
{
	//�������� ������ ����
	$kol_res_in = 1;
	$res_id_in = $id_resource_olenkoja;
}
elseif ($rand>$chance_olenkoja AND $rand<=$chance_olenkosti+$chance_olenkoja)
{
	//�������� ������ �����
	$kol_res_in = 1;
	$res_id_in = $id_resource_olenkosti;
}
elseif ($rand>$chance_olenkosti+$chance_olenkoja AND $rand<=$chance_olenjily+$chance_olenkosti+$chance_olenkoja)
{
	//�������� ������ ����
	$kol_res_in = 1;
	$res_id_in = $id_resource_olenjily;
}

//�������� ���������
$res_out = mysql_fetch_array(myquery("SELECT * FROM craft_resource WHERE id=$res_id_out"));
$Res = new Res($res_out);
$Res->add_user(0, $user_id, -1);
$mes='������������ ������: <i>'.$res_out['name'].'</i> � ���������� 1 ��.<br />';
myquery("insert into craft_stat (build_id, gp, res_id, dob, vip, dat, user, type) values (0, 0, $res_id_out, 0, -1, ".time().", $user_id, 'z')");  
$char['CW'] = $char['CW'] - $res_out['weight'];

//������ ���������� ���������
if ($res_id_in > 0)
{
	$res_in = mysql_fetch_array(myquery("SELECT * FROM craft_resource WHERE id=$res_id_in"));
	$Res1 = new Res($res_in);
	$check = $Res1->add_user(0,$user_id);
	if ($check == 1)
	{
		
		add_exp_for_craft($user_id, 9);
		myquery("insert into craft_stat (build_id, gp, res_id, dob, vip, dat, user, type) values (0, 0, $res_id_in, 0, 1, ".time().", $user_id, 'z')");
		setCraftTimes($user_id,9,1,1);
		$mes.='<br />������� ������: <i>'.$res_in['name'].'</i> � ���������� '.$kol_res_in.' ��.';
	}
	else
	{
		$mes.='<br /><b>� ��� ������������ ���������� ����� � ���������!</b><br />';
	}
}
else
{
	$mes.='<br /><b>��������� ������� ������ � ����������� ����.</b><br />';
}

mt_srand(make_seed());
myquery("UPDATE game_items SET item_uselife=item_uselife-".(mt_rand(100,250)/100)." WHERE user_id=$user_id AND used=21 AND priznak=0");
list($id,$uselife) = mysql_fetch_array(myquery("SELECT id,item_uselife FROM game_items WHERE user_id=$user_id AND used=21 AND priznak=0"));
if ($uselife<=0)
{
	$Item = new Item($id);
	$Item->down();    
}

$option = 18;
if (domain_name=='localhost') $option=19;
$url = 'lib/town.php?option='.$option.'&part4&add=22&mes='.$mes;
setLocation($url);
//echo'<meta http-equiv="refresh" content="10;url='.$url.'">';
exit_from_craft($add_query, 1);
?>