<?php

function ImageItem($img,$item_id,$kleymo=-1,$align="",$title="",$alt="")
{
	//$img - ������ url �������� ��������
	//$item_id - ID �������� �� game_items
	//$kleymo - ������� ������, ���� �� ����������, �� ������� ��������� �������� �� ��
	if ($kleymo==-1)
	{
		list($kleymo)=mysql_fetch_array(myquery("SELECT kleymo FROM game_items WHERE id=$item_id"));
	} 
	if ($kleymo==1 OR $kleymo==2)
	{
		//$url = urlencode('http://'.img_domain.'/item/'.$img.'.gif');
		$url = urlencode($img);
		echo '<img src="http://'.domain_name.'/utils/mixitem.php?img='.$url.'" border="0"';
		if ($align!="") echo ' align="'.$align.'"';
		if ($title!="") echo ' title="'.$title.'"';
		if ($alt!="") echo ' alt="'.$alt.'"';
		echo ' width="50" height="50" >';
	}
	else
	{
		echo '<img src="http://'.img_domain.'/item/'.$img.'.gif" border="0"';
		if ($align!="") echo ' align="'.$align.'"';
		if ($title!="") echo ' title="'.$title.'"';
		if ($alt!="") echo ' alt="'.$alt.'"';
		echo ' width="50" height="50" >';
	}
}

function getCW($user)
{
	$cw = 0;
	//�������� ������ ������� ��� ������ ������� - ��� �������������� ��������
	$cw+=mysql_result(myquery("SELECT SUM(game_items_factsheet.weight) FROM game_items,game_items_factsheet WHERE game_items.user_id=$user AND game_items.priznak=0 AND game_items.item_id=game_items_factsheet.id AND game_items_factsheet.type!=95"),0,0);
	$cw+=mysql_result(myquery(" SELECT sum( craft_resource.weight * craft_resource_user.col ) FROM craft_resource, craft_resource_user WHERE craft_resource_user.user_id =$user AND craft_resource.id = craft_resource_user.res_id "),0,0);
	//inq! �������� �� ������ ������� - ��� ��� ��� �������� ��������
	$cw+=mysql_result(myquery("SELECT SUM(game_items.item_uselife) FROM game_items,game_items_factsheet WHERE game_items.user_id=$user AND game_items_factsheet.type=95 AND game_items.priznak=0 AND game_items.item_id=game_items_factsheet.id"),0,0);
	
	return $cw;
}

function wm_str($type)
{
	$tp_new_str = '';
	$tp_img = '';
	switch ($type)
	{
		case '1':
		{
			$tp_new_str='������ ����������� �������';
			$tp_img = 'svitki/sbesener';
			break;
		}
		case '2':
		{
			$tp_new_str='������ ���������';
			$tp_img = 'svitki/steleport';
			break;
		}
		case '3':
		{
			$tp_new_str='������ ������� �������������';
			$tp_img = 'svitki/sbolvost';
			break;
		}
		case '4':
		{
			$tp_new_str='������ ������ �������������';
			$tp_img = 'svitki/sbolvost';
			break;
		}
		case '5':
		{
			$tp_new_str='������ ����������';
			$tp_img = 'svitki/sbesener';
			break;
		}
	}
	$ar = array(ucfirst($tp_new_str),$tp_img);
	return $ar;
}

function type_str($type)
{
	$tp_new_str = '';
	switch ($type)
	{
		case 1:
		{
			$tp_new_str='������';
			break;
		}

		case 2:
		{
			$tp_new_str='������';
			break;
		}

		case 3:
		{
			$tp_new_str='��������';
			break;
		}

		case 4:
		{
			$tp_new_str='���';
			break;
		}

		case 5:
		{
			$tp_new_str='������';
			break;
		}

		case 6:
		{
			$tp_new_str='����';
			break;
		}

		case 7:
		{
			$tp_new_str='�����';
			break;
		}

		case 8:
		{
			$tp_new_str='����';
			break;
		}

		case 9:
		{
			$tp_new_str='��������';
			break;
		}

		case 10:
		{
			$tp_new_str='��������';
			break;
		}

		case 11:
		{
			$tp_new_str='�����';
			break;
		}

		case 12:
		{
			$tp_new_str='������';
			break;
		}

		case 13:
		{
			$tp_new_str='�������';
			break;
		}

		case 14:
		{
			$tp_new_str='������';
			break;
		}

		case 15:
		{
			$tp_new_str='������';
			break;
		}

		case 16:
		{
			$tp_new_str='���������';
			break;
		}

		case 17:
		{
			$tp_new_str='���.�����';
			break;
		}

		case 18:
		{
			$tp_new_str='���';
			break;
		}

		case 19:
		{
			$tp_new_str='�����.�������';
			break;
		}

		case 20:
		{
			$tp_new_str='����� ������������ ��������';
			break;
		}

		case 21:
		{
			$tp_new_str='������';
			break;
		}

		case 22:
		{
			$tp_new_str='���� �������';
			break;
		}

		case 23:
		{
			$tp_new_str='�������� ���������';
			break;
		}
		case 24:
		{
			$tp_new_str='����������';
			break;
		}

		case 98:
		{
			$tp_new_str='����� ���� ������� (����� ������� ��������� �� ���������)';
			break;
		}
		
		case 97:
		{
			$tp_new_str='������';
			break;
		}
		
		case 95:
		{
			$tp_new_str='��������� ��������';
			break;
		}
		
		default:
		{
			$tp_new_str='�����������('.$type.')';
			break;
		}
	}
	return $tp_new_str;
}

function PrintInv($userid,$from_view)
{
	global $option;
	/*
	list($race) = mysql_fetch_array(myquery("(SELECT race FROM game_users WHERE user_id='$userid') UNION (SELECT race FROM game_users_archive WHERE user_id='$userid')"));
	switch($race)
	{
		case '����':
		{
			$img['file']='inv/gnom.gif';
			$img['width']=330;
			$img['height']=466;
			//������� ������ � ������ ����
			$img[1]['posx']=89;
			$img[1]['posy']=95;
			//������� ����� �1
			$img[2]['posx']=14;
			$img[2]['posy']=95;
			//������� ��������� �1
			$img[3]['posx']=14;
			$img[3]['posy']=270;
			//������� ���� ��� ������ � ����� ����
			$img[4]['posx']=239;
			$img[4]['posy']=205;
			//������� �������
			$img[5]['posx']=173;
			$img[5]['posy']=188;
			//������� �����
			$img[6]['posx']=173;
			$img[6]['posy']=59;
			//������� �����
			$img[7]['posx']=277;
			$img[7]['posy']=414;
			//������� �����
			$img[8]['posx']=173;
			$img[8]['posy']=244;
			//������� �������
			$img[9]['posx']=173;
			$img[9]['posy']=130;
			//������� ��������
			$img[10]['posx']=89;
			$img[10]['posy']=148;
			//������� �������
			$img[11]['posx']=173;
			$img[11]['posy']=328;
			//������� ������� 1
			$img[12]['posx']=3;
			$img[12]['posy']=414;
			//������� ������� 2
			$img[13]['posx']=55;
			$img[13]['posy']=414;
			//������� ������� 3
			$img[14]['posx']=105;
			$img[14]['posy']=414;
			//������� ������� 4
			$img[15]['posx']=156;
			$img[15]['posy']=414;
			//������� ������� 5
			$img[16]['posx']=207;
			$img[16]['posy']=414;
			//������� ��������� �2
			$img[17]['posx']=14;
			$img[17]['posy']=323;
			//������� ������ �2
			$img[18]['posx']=14;
			$img[18]['posy']=147;
			//������� ������ �3
			$img[19]['posx']=14;
			$img[19]['posy']=201;
			break;
		}
		case '���':
		{
			$img['file']='inv/orc.gif';
			$img['width']=330;
			$img['height']=466;
			//������� ������ � ������ ����
			$img[1]['posx']=82;
			$img[1]['posy']=79;
			//������� ����� �1
			$img[2]['posx']=14;
			$img[2]['posy']=95;
			//������� ��������� �1
			$img[3]['posx']=14;
			$img[3]['posy']=270;
			//������� ���� ��� ������ � ����� ����
			$img[4]['posx']=257;
			$img[4]['posy']=191;
			//������� �������
			$img[5]['posx']=189;
			$img[5]['posy']=128;
			//������� �����
			$img[6]['posx']=189;
			$img[6]['posy']=4;
			//������� �����
			$img[7]['posx']=277;
			$img[7]['posy']=414;
			//������� �����
			$img[8]['posx']=189;
			$img[8]['posy']=184;
			//������� �������
			$img[9]['posx']=189;
			$img[9]['posy']=72;
			//������� ��������
			$img[10]['posx']=82;
			$img[10]['posy']=132;
			//������� �������
			$img[11]['posx']=189;
			$img[11]['posy']=308;
			//������� ������� 1
			$img[12]['posx']=3;
			$img[12]['posy']=414;
			//������� ������� 2
			$img[13]['posx']=55;
			$img[13]['posy']=414;
			//������� ������� 3
			$img[14]['posx']=105;
			$img[14]['posy']=414;
			//������� ������� 4
			$img[15]['posx']=156;
			$img[15]['posy']=414;
			//������� ������� 5
			$img[16]['posx']=207;
			$img[16]['posy']=414;
			//������� ��������� �2
			$img[17]['posx']=14;
			$img[17]['posy']=323;
			//������� ������ �2
			$img[18]['posx']=14;
			$img[18]['posy']=147;
			//������� ������ �3
			$img[19]['posx']=14;
			$img[19]['posy']=201;
			break;
		}
		case '������':
		{
			$img['file']='inv/nazgul.gif';
			$img['width']=330;
			$img['height']=466;
			//������� ������ � ������ ����
			$img[1]['posx']=85;
			$img[1]['posy']=83;
			//������� ����� �1
			$img[2]['posx']=14;
			$img[2]['posy']=95;
			//������� ��������� �1
			$img[3]['posx']=14;
			$img[3]['posy']=270;
			//������� ���� ��� ������ � ����� ����
			$img[4]['posx']=214;
			$img[4]['posy']=149;
			//������� �������
			$img[5]['posx']=155;
			$img[5]['posy']=198;
			//������� �����
			$img[6]['posx']=156;
			$img[6]['posy']=66;
			//������� �����
			$img[7]['posx']=277;
			$img[7]['posy']=414;
			//������� �����
			$img[8]['posx']=156;
			$img[8]['posy']=263;
			//������� �������
			$img[9]['posx']=155;
			$img[9]['posy']=133;
			//������� ��������
			$img[10]['posx']=85;
			$img[10]['posy']=136;
			//������� �������
			$img[11]['posx']=194;
			$img[11]['posy']=329;
			//������� ������� 1
			$img[12]['posx']=3;
			$img[12]['posy']=414;
			//������� ������� 2
			$img[13]['posx']=55;
			$img[13]['posy']=414;
			//������� ������� 3
			$img[14]['posx']=105;
			$img[14]['posy']=414;
			//������� ������� 4
			$img[15]['posx']=156;
			$img[15]['posy']=414;
			//������� ������� 5
			$img[16]['posx']=207;
			$img[16]['posy']=414;
			//������� ��������� �2
			$img[17]['posx']=14;
			$img[17]['posy']=323;
			//������� ������ �2
			$img[18]['posx']=14;
			$img[18]['posy']=147;
			//������� ������ �3
			$img[19]['posx']=14;
			$img[19]['posy']=201;
			break;
		}
		case '������':
		{
			$img['file']='inv/hobbit.gif';
			$img['width']=330;
			$img['height']=466;
			//������� ������ � ������ ����
			$img[1]['posx']=79;
			$img[1]['posy']=102;
			//������� ����� �1
			$img[2]['posx']=14;
			$img[2]['posy']=95;
			//������� ��������� �1
			$img[3]['posx']=14;
			$img[3]['posy']=270;
			//������� ���� ��� ������ � ����� ����
			$img[4]['posx']=249;
			$img[4]['posy']=231;
			//������� �������
			$img[5]['posx']=179;
			$img[5]['posy']=155;
			//������� �����
			$img[6]['posx']=179;
			$img[6]['posy']=30;
			//������� �����
			$img[7]['posx']=277;
			$img[7]['posy']=414;
			//������� �����
			$img[8]['posx']=179;
			$img[8]['posy']=212;
			//������� �������
			$img[9]['posx']=179;
			$img[9]['posy']=99;
			//������� ��������
			$img[10]['posx']=79;
			$img[10]['posy']=155;
			//������� �������
			$img[11]['posx']=179;
			$img[11]['posy']=314;
			//������� ������� 1
			$img[12]['posx']=3;
			$img[12]['posy']=414;
			//������� ������� 2
			$img[13]['posx']=55;
			$img[13]['posy']=414;
			//������� ������� 3
			$img[14]['posx']=105;
			$img[14]['posy']=414;
			//������� ������� 4
			$img[15]['posx']=156;
			$img[15]['posy']=414;
			//������� ������� 5
			$img[16]['posx']=207;
			$img[16]['posy']=414;
			//������� ��������� �2
			$img[17]['posx']=14;
			$img[17]['posy']=323;
			//������� ������ �2
			$img[18]['posx']=14;
			$img[18]['posy']=147;
			//������� ������ �3
			$img[19]['posx']=14;
			$img[19]['posy']=201;
			break;
		}
		case '�������':
		{
			$img['file']='inv/humman.gif';
			$img['width']=330;
			$img['height']=466;
			//������� ������ � ������ ����
			$img[1]['posx']=102;
			$img[1]['posy']=195;
			//������� ����� �1
			$img[2]['posx']=14;
			$img[2]['posy']=95;
			//������� ��������� �1
			$img[3]['posx']=14;
			$img[3]['posy']=270;
			//������� ���� ��� ������ � ����� ����
			$img[4]['posx']=247;
			$img[4]['posy']=195;
			//������� �������
			$img[5]['posx']=180;
			$img[5]['posy']=136;
			//������� �����
			$img[6]['posx']=180;
			$img[6]['posy']=16;
			//������� �����
			$img[7]['posx']=277;
			$img[7]['posy']=414;
			//������� �����
			$img[8]['posx']=180;
			$img[8]['posy']=192;
			//������� �������
			$img[9]['posx']=180;
			$img[9]['posy']=81;
			//������� ��������
			$img[10]['posx']=102;
			$img[10]['posy']=142;
			//������� �������
			$img[11]['posx']=180;
			$img[11]['posy']=338;
			//������� ������� 1
			$img[12]['posx']=3;
			$img[12]['posy']=414;
			//������� ������� 2
			$img[13]['posx']=55;
			$img[13]['posy']=414;
			//������� ������� 3
			$img[14]['posx']=105;
			$img[14]['posy']=414;
			//������� ������� 4
			$img[15]['posx']=156;
			$img[15]['posy']=414;
			//������� ������� 5
			$img[16]['posx']=207;
			$img[16]['posy']=414;
			//������� ��������� �2
			$img[17]['posx']=14;
			$img[17]['posy']=323;
			//������� ������ �2
			$img[18]['posx']=14;
			$img[18]['posy']=147;
			//������� ������ �3
			$img[19]['posx']=14;
			$img[19]['posy']=201;
			break;
		}
		case '������':
		{
			$img['file']='inv/goblin.gif';
			$img['width']=330;
			$img['height']=466;
			//������� ������ � ������ ����
			$img[1]['posx']=78;
			$img[1]['posy']=95;
			//������� ����� �1
			$img[2]['posx']=14;
			$img[2]['posy']=95;
			//������� ��������� �1
			$img[3]['posx']=14;
			$img[3]['posy']=270;
			//������� ���� ��� ������ � ����� ����
			$img[4]['posx']=261;
			$img[4]['posy']=212;
			//������� �������
			$img[5]['posx']=186;
			$img[5]['posy']=148;
			//������� �����
			$img[6]['posx']=186;
			$img[6]['posy']=33;
			//������� �����
			$img[7]['posx']=277;
			$img[7]['posy']=414;
			//������� �����
			$img[8]['posx']=186;
			$img[8]['posy']=204;
			//������� �������
			$img[9]['posx']=186;
			$img[9]['posy']=92;
			//������� ��������
			$img[10]['posx']=78;
			$img[10]['posy']=148;
			//������� �������
			$img[11]['posx']=186;
			$img[11]['posy']=317;
			//������� ������� 1
			$img[12]['posx']=3;
			$img[12]['posy']=414;
			//������� ������� 2
			$img[13]['posx']=55;
			$img[13]['posy']=414;
			//������� ������� 3
			$img[14]['posx']=105;
			$img[14]['posy']=414;
			//������� ������� 4
			$img[15]['posx']=156;
			$img[15]['posy']=414;
			//������� ������� 5
			$img[16]['posx']=207;
			$img[16]['posy']=414;
			//������� ��������� �2
			$img[17]['posx']=14;
			$img[17]['posy']=323;
			//������� ������ �2
			$img[18]['posx']=14;
			$img[18]['posy']=147;
			//������� ������ �3
			$img[19]['posx']=14;
			$img[19]['posy']=201;
			break;
		}
		case '������':
		{
			$img['file']='inv/troll.gif';
			$img['width']=330;
			$img['height']=466;
			//������� ������ � ������ ����
			$img[1]['posx']=88;
			$img[1]['posy']=230;
			//������� ����� �1
			$img[2]['posx']=14;
			$img[2]['posy']=95;
			//������� ��������� �1
			$img[3]['posx']=14;
			$img[3]['posy']=270;
			//������� ���� ��� ������ � ����� ����
			$img[4]['posx']=249;
			$img[4]['posy']=70;
			//������� �������
			$img[5]['posx']=150;
			$img[5]['posy']=148;
			//������� �����
			$img[6]['posx']=150;
			$img[6]['posy']=29;
			//������� �����
			$img[7]['posx']=277;
			$img[7]['posy']=414;
			//������� �����
			$img[8]['posx']=150;
			$img[8]['posy']=208;
			//������� �������
			$img[9]['posx']=150;
			$img[9]['posy']=91;
			//������� ��������
			$img[10]['posx']=88;
			$img[10]['posy']=177;
			//������� �������
			$img[11]['posx']=150;
			$img[11]['posy']=334;
			//������� ������� 1
			$img[12]['posx']=3;
			$img[12]['posy']=414;
			//������� ������� 2
			$img[13]['posx']=55;
			$img[13]['posy']=414;
			//������� ������� 3
			$img[14]['posx']=105;
			$img[14]['posy']=414;
			//������� ������� 4
			$img[15]['posx']=156;
			$img[15]['posy']=414;
			//������� ������� 5
			$img[16]['posx']=207;
			$img[16]['posy']=414;
			//������� ��������� �2
			$img[17]['posx']=14;
			$img[17]['posy']=323;
			//������� ������ �2
			$img[18]['posx']=14;
			$img[18]['posy']=147;
			//������� ������ �3
			$img[19]['posx']=14;
			$img[19]['posy']=201;
			break;
		}
		default:
		{
			$img['file']='inv/elf.gif';
			$img['width']=330;
			$img['height']=466;
			//������� ������ � ������ ����
			$img[1]['posx']=90;
			$img[1]['posy']=184;
			//������� ����� �1
			$img[2]['posx']=14;
			$img[2]['posy']=95;
			//������� ��������� �1
			$img[3]['posx']=14;
			$img[3]['posy']=270;
			//������� ���� ��� ������ � ����� ����
			$img[4]['posx']=224;
			$img[4]['posy']=210;
			//������� �������
			$img[5]['posx']=167;
			$img[5]['posy']=147;
			//������� �����
			$img[6]['posx']=195;
			$img[6]['posy']=25;
			//������� �����
			$img[7]['posx']=277;
			$img[7]['posy']=414;
			//������� �����
			$img[8]['posx']=167;
			$img[8]['posy']=204;
			//������� �������
			$img[9]['posx']=180;
			$img[9]['posy']=93;
			//������� ��������
			$img[10]['posx']=90;
			$img[10]['posy']=131;
			//������� �������
			$img[11]['posx']=200;
			$img[11]['posy']=343;
			//������� ������� 1
			$img[12]['posx']=3;
			$img[12]['posy']=414;
			//������� ������� 2
			$img[13]['posx']=55;
			$img[13]['posy']=414;
			//������� ������� 3
			$img[14]['posx']=105;
			$img[14]['posy']=414;
			//������� ������� 4
			$img[15]['posx']=156;
			$img[15]['posy']=414;
			//������� ������� 5
			$img[16]['posx']=207;
			$img[16]['posy']=414;
			//������� ��������� �2
			$img[17]['posx']=14;
			$img[17]['posy']=323;
			//������� ������ �2
			$img[18]['posx']=14;
			$img[18]['posy']=147;
			//������� ������ �3
			$img[19]['posx']=14;
			$img[19]['posy']=201;
			break;
		}
	};
	*/
	$img['file']='inv/inv.gif';
	$img['width']=226;
	$img['height']=382;
	//������� ������ � ������ ����
	$img[1]['posx']=62;
	$img[1]['posy']=166;
	//������� ������ �1
	$img[2]['posx']=10;
	$img[2]['posy']=10;
	//������� ���������
	$img[3]['posx']=10;
	$img[3]['posy']=166;
	//������� ���� ��� ������ � ����� ����
	$img[4]['posx']=166;
	$img[4]['posy']=166;
	//������� �������
	$img[5]['posx']=114;
	$img[5]['posy']=114;
	//������� �����
	$img[6]['posx']=114;
	$img[6]['posy']=10;
	//������� �����
	$img[7]['posx']=10;
	$img[7]['posy']=218;
	//������� �����
	$img[8]['posx']=114;
	$img[8]['posy']=166;
	//������� ��������
	$img[9]['posx']=114;
	$img[9]['posy']=62;
	//������� ��������
	$img[10]['posx']=166;
	$img[10]['posy']=114;
	//������� �������
	$img[11]['posx']=114;
	$img[11]['posy']=270;
	//������� ������� 1
	$img[12]['posx']=166;
	$img[12]['posy']=218;
	//������� ������� 2
	$img[13]['posx']=166;
	$img[13]['posy']=270;
	//������� �������3
	$img[14]['posx']=166;
	$img[14]['posy']=322;
	//������� ��������� 1
	$img[15]['posx']=10;
	$img[15]['posy']=270;
	//������� ��������� 2
	$img[16]['posx']=10;
	$img[16]['posy']=322;
	//������� ������
	$img[17]['posx']=114;
	$img[17]['posy']=218;
	//������� �������
	$img[18]['posx']=62;
	$img[18]['posy']=114;
	//������� ������ �2
	$img[19]['posx']=10;
	$img[19]['posy']=62;
	//������� ������ �3
	$img[20]['posx']=10;
	$img[20]['posy']=114;
	//������� �����������
	$img[21]['posx']=62;
	$img[21]['posy']=218;

    if ($from_view!=1)
    {
	?>
	<table><tr><td valign="top">
	<?
    };
	echo '
	<div style="position:relative;">
	<img src="http://'.img_domain.'/'.$img['file'].'" border=0 width='.$img['width'].' height='.$img['height'].' alt="" title="">
	';
	$it_sel = myquery("SELECT * FROM game_items WHERE user_id='$userid' AND used>0 AND used<=21");
	while($it = mysql_fetch_array($it_sel))
	{
        $used = $it['used'];
        echo '
        <span style="position:absolute; left:'.$img[$used]['posx'].'; top:'.$img[$used]['posy'].'; ">';
		$Item = new Item($it['id']);
		if($from_view!=1)
		{
			$str_begin = '<a href="http://'.domain_name.'/item.php?inv_option=unequip&id='.$it['id'].''.((isset($_GET['house'])) ? '&house&option='.$option.'' : '').'"';
			$Item->hint($it['id'],1,$str_begin);
			$alt="����� �������";
		}
		else
		{
			$alt=''.$Item->getFact('name').'';
		}
		ImageItem($Item->img,0,$Item->item['kleymo'],"",$alt,$alt);
		if($from_view!=1)
		{
			echo '</a>';
		}
        echo '</span>';
	}

	echo'</div>';
}


?>