<?
/*
������ ��������:
1 - ��� ������
2 - ����������
3 - ����� 1
4 - ����� 2
5 - ����� 3
6 - ����
7 - ���������
8 - ����������� �����
9 - ������
10 - �������
11 - ��������������

��� ������:
1 - �� ������ ��������
2 - �� ���� ��������
3 - �� ��������������� ��������
4 - �� ��������������� ���������
*/

if (function_exists("start_debug")) start_debug(); 

if (domain_name == 'testing.rpg.su') {$price=0; $price_har=0;}
if ($town!=0)
{
	$userban=myquery("select * from game_ban where user_id='$user_id' and type=2 and time>'".time()."'");
	if (mysql_num_rows($userban))
	{
		$userr = mysql_fetch_array($userban);
		$min = ceil(($userr['time']-time())/60);
		echo '<center><br><br><br>�� ���� �������� ��������� �� '.$min.' �����! ���� ��������� ������������ �������!';
		{if (function_exists("save_debug")) save_debug(); exit;}
	}
	
	$cost=10; //��������� ����
	
	function choose_prize ($random, $arr, $type, $kol, $start) //���������, ��� �� ������ ���������
	{
		$name='';
		/*if ($type==1)
		{
			$res_test=myquery("Select id from craft_resource where craft_resource_type=$arr  
														   Join game_items On game_items.item_id=game_items_factsheet.id
														   Where game_items_factsheet.type=$attr"));
			
		}
		elseif ($type==2)
		{
			list($kol_all_item)=mysql_fetch_array(myquery("Select count(game_items.id) from game_items_factsheet.name 
														   Join game_items On game_items.item_id=game_items_factsheet.id
														   Where game_items_factsheet.type=$attr"));
						   
		    $item=myquery("Select game_items_factsheet.name, count(game_items.id) from game_items_factsheet.name 
						   Join game_items On game_items.item_id=game_items_factsheet.id
						   Where game_items_factsheet.type=$attr");
		    while (list($name_item, $kol_item)=mysql_fetch_array($item))
			{
				$start=$start+round($kol*$kol_item/$kol_all_item);
				if ($random<=$start) {$name=$name_item;}
			}
		}
		else*/if ($type==3)
		{
			$kol_all_res=0;
			$i=0;
			foreach ($arr as $value) 
			{
				$res_user=0;
				$res_market=0;
				$res_user_test=myquery("Select * from craft_resource_user where res_id=$value");
			    $res_market_test=myquery("Select * from craft_resource_market where res_id=$value");
			    if (mysql_num_rows($res_user_test)>0) list($res_user)=mysql_fetch_array(myquery("Select sum(col) from craft_resource_user where res_id=$value"));
			    if (mysql_num_rows($res_market_test)>0) list($res_market)=mysql_fetch_array(myquery("Select sum(col) from craft_resource_market where res_id=$value"));
			    $res[$i]=$res_user+$res_market;
			    $kol_all_res=$kol_all_res+$res[$i];
			    $i++;
			}
			$i=0;
			if ($kol_all_res>0)
			{
				foreach ($arr as $value) 
				{
					$start=$start+round($kol*$res[$i]/$kol_all_res);
					if ($random<=$start) {list($name)=mysql_fetch_array(myquery("Select name from craft_resource where id=$value")); break;}
					$i++;
				}
			}
		}
		elseif ($type==4)
		{
			$kol_all_item=0;
			$i=0;
			foreach ($arr as $value) 
			{
			   $item1=0;
			   $item_test=myquery("Select * from game_items where item_id=$value");
			   if (mysql_num_rows($item_test)>0) list($item1)=mysql_fetch_array(myquery("Select sum(count_item) from game_items where item_id=$value"));
			   $item[$i]=$item1;
			   $kol_all_item=$kol_all_item+$item[$i];
			   $i++;
			}
			$i=0;
			if ($kol_all_item>0)
			{
				foreach ($arr as $value) 
				{
					$start=$start+round($kol*$item[$i]/$kol_all_item);
					//if ($random<=$start) {list($name)=mysql_fetch_array(myquery("Select name from game_items_factsheet where id=$value")); break;}
					$i++;
				}
			}
		}
		return $name;
	}
		
	$img='http://'.img_domain.'/race_table/human/table';
	echo'<table width=100% border="0" cellspacing="0" cellpadding="0"><tr><td width="1" height="1"><img src="'.$img.'_lt.gif"></td><td background="'.$img.'_mt.gif"></td><td width="1" height="1"><img src="'.$img.'_rt.gif"></td></tr>
	<tr><td background="'.$img.'_lm.gif"></td><td style="text-align:center" background="'.$img.'_mm.gif" valign="top" width="100%" height="100%">';
	
	if (isset($play))	//����� ������������� ��� �����, ��� �� ����� �������������� ��������
	{
		if ($char['clevel']<12)
		{
			echo'<center><font face=verdana color=ff0000 size=2>���� ������, ��������� 12-��� ������, ����� ��������� ���� ����� � ����� ������!</font><meta http-equiv="refresh" content="4;url=town.php?option='.$option.'"><br />';
		}
		elseif ($char['GP']<$cost)
		{
			echo'<center><font face=verdana color=ff0000 size=2>� ��� ��� ����� ��� ����!</font><meta http-equiv="refresh" content="4;url=town.php?option='.$option.'"><br />';
		}
		elseif ($char['CC']-$char['CW']<0.01)
		{
			echo'<center><font face=verdana color=ff0000 size=2>� ��� ������������ ����� � ��������� ��� ����!</font><meta http-equiv="refresh" content="4;url=town.php?option='.$option.'"><br />';
		}
		else
		{
			$random=mt_rand(1,10000);
			if ($random<=3500)
			{ 	$name='�����'; $type='res';	}
			elseif ($random<=3523)
			{ 	$name='������ ����������� ����';    $type='item';}
			elseif ($random<=3546)
			{	$name='������ ����������� �����';   $type='item';}
			elseif ($random<=3573)
			{	$name='���������� ������� �������';	$type='item';} 
			elseif ($random<=3600)
			{	$name='���� �������';    			$type='item';}
			elseif ($random<=3631)
			{	$name='���������� ������� �������'; $type='item';}
			elseif ($random<=3662)
			{	$name='������ �������'; 			$type='item';}
			elseif ($random<=3678)
			{	$name='���� �����'; 				$type='item';}
			elseif ($random<=3774)
			{	$name='��������������';				$type='item';}
			elseif ($random<=3841)
			{	$name='������� �����������';  	    $type='item';}
			elseif ($random<=3975)
			{	$name='��������'; 				    $type='item';}
			elseif ($random<=4109)
			{	$name='������� ������';				$type='item';}
			elseif ($random<=4202)
			{	$name='�������� ����';				$type='item';}
			elseif ($random<=4211)
			{	$name='���� ��������� �������';		$type='item';}
			elseif ($random<=4310)
			{	$name='��� �������';				$type='item';}
			elseif ($random<=4394)
			{	$name='������ ��������';			$type='item';}
			elseif ($random<=4496)
			{	$name='������ ������';		    	$type='item';}
			elseif ($random<=4620)
			{	$name='������ ��������';			$type='item';}
			elseif ($random<=5120)
			{ 	$name='���� �����';                 $type='res';}
			elseif ($random<=5220)
			{ 	$name='�������� ����';              $type='item';}
			elseif ($random<=5258)
			{ 	$name='������� �������';         $type='item';}
			elseif ($random<=5358)
			{ 	$name='�������� ������';            $type='item';}
			elseif ($random<=5458)
			{ 	$name='������ ��������';            $type='item';}
			elseif ($random<=5508)
			{ 	$name='���� ����';                  $type='item';}
			elseif ($random<=6008)
			{ 	$name='����� ������ ������������� � ���';    $type='item';}
			elseif ($random<=6258)
			{ 	$name='������� ������ ������������� � ���';  $type='item';}
			elseif ($random<=6358)
			{ 	
				$arr=array("11", "51");
				$name=choose_prize($random, $arr, 3, 100, 6259);  
				$type='res';
			}
			elseif ($random<=6410)
			{ 	
				$arr=array("52", "53", "54", "55");
				$name=choose_prize($random, $arr, 3, 50, 6359);
				$type='res';
			}
			elseif ($random<=6515)
			{ 	
				$arr=array("63", "64", "65", "66", "67");
				$name=choose_prize($random, $arr, 3, 100, 6411);  
				$type='res';
			}
			elseif ($random<=6723)
			{ 	
				$arr=array("31", "32", "33", "34", "35", "36", "37", "38", "39", "40", "41", "42", "43", "44", "45");
				$name=choose_prize($random, $arr, 3, 200, 6516);  
				$type='res';
			}
			elseif ($random<=9426)
			{ 	
				$arr=array("49", "50", "56", "60");
				$name=choose_prize($random, $arr, 3, 2700, 6724);  
				$type='res';
			}
			elseif ($random<=9531)
			{ 	
				$arr=array("69", "70", "71", "72", "73", "74", "75", "76", "77", "78", "79", "80", "81", "82", "83");
				$name=choose_prize($random, $arr, 3, 100, 9427);  
				$type='res';
			}
			elseif ($random<=9591)
			{ 	
				$arr=array("85", "86", "87", "88", "89", "90", "91", "92", "93", "94", "95", "96", "97", "98", "99");
				$name=choose_prize($random, $arr, 3, 50, 9532);  
				$type='res';
			}
			/*elseif ($random<=9592)
			{ 	$name='������';  $type='res';}*/
			elseif ($random<=9698)
			{ 	
				$arr=array("479", "480", "481", "482", "483", "485", "486", "487");
				$name=choose_prize($random, $arr, 4, 100, 9592);  
				$type='item';
			}
			elseif ($random<=10000)
			{ 	
				$arr=array("319", "321", "322");
				$name=choose_prize($random, $arr, 4, 100, 9699);  
				$type='item';
			}
			if (!isset($name) or !isset($type) or ($name==''))
			{	$name='�����';				$type='res';}
			
			
			//������ �����
			if ($type=='res')
			{
				$check_res=myquery("Select * From craft_resource Where name like '$name'");
				if (mysql_num_rows($check_res)==0)
				{
					$no_prize=1;
				}
				else 
				{
					$ress = mysql_fetch_array($check_res);
					$id = $ress['id'];
					$prize_type = 0;
					$Res = new Res($ress, 0);
					$Res->add_user(0, $user_id);
				}				
			}
			elseif ($type=='item')
			{
				list($id)=mysql_fetch_array(myquery("Select id From game_items_factsheet Where name like '$name'"));
				if (!isset($id) or $id=='')
				{
					$no_prize=1;
				}
				else
				{
					$Item = new Item();
					$ar = $Item->add_user($id,$user_id);
					$prize_type = 1;
				}
			}			
			
			if (isset($no_prize) and $no_prize==1)
			{
				echo'<center><font face=verdana color=ff0000 size=2>� ���������, ���� '.$name.' �� ������!</font><meta http-equiv="refresh" content="4;url=town.php?option='.$option.'"><br />';
			}
			else
			{
				myquery("Update game_users Set GP=GP-'".$cost."' Where user_id='".$user_id."'");
				setGP($user_id,-$cost,107); 
				myquery("INSERT INTO game_gambling (user_id, prize_id, prize_type, last_time) VALUES ('".$user_id."', '".$id."', '".$prize_type."', '".time()."') ");
				echo'<center><font face=verdana color=white size=2><br/><b>��� ����: '.$name.'</b><br/><br/>
				     <input type="button"  style="width: 150px" onClick="location.href=\'town.php?option='.$option.'&play\'" value="������� ��� ���"><br/><br/>
					 </center>';
			}
			
		}
		
	}
	else
	{
		echo'<center>
			 <font face=verdana color=ff0000 size=2><b>���� ������</b></font><br/><br/>
			 <b><font face=verdana color=white size=2>����������, ������! ����� �� ������ ��������� �� ����������� ���� � ������� � ������������� ����, � ������� ��� �����������! <br/>�������� <u>'.$cost.'</u> '.pluralForm($cost,'������','������','�����').', �� ��������� ��������� ����! <br/>�� ��� �����, ��� ��� �����?</font><br/><br/><br/></b>';	
		if ($char['clevel']>=12)
		{
			echo '<input type="button"  style="width: 150px" onClick="location.href=\'town.php?option='.$option.'&play\'" value="������� � ����"><br/><br/></center>';
		}
		else
		{
			echo '<font face=verdana color=ff0000 size=2>���� ������, ��������� 12-��� ������, ����� ��������� ���� ����� � ����� ������!</font>';
		}
	}
	echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
}
if (function_exists("save_debug")) save_debug(); 

?>