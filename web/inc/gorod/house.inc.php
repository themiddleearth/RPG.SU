<?
if (function_exists("start_debug")) start_debug(); 
if ($town!=0)
{
	?>
	<style type="text/css">
	.table_h1
	{
		border-top: 1px solid gold; 
		border-left: 1px solid gold; 
		border-right: 1px solid gold; 
		text-align: �enter;
		color: white;
		text-transform: capitalize; 
		font-weight: 900;
		font-size: 12px;  
	}
	.table_h2
	{
		border-bottom: 1px groove gold; 
		text-align: �enter;
		color: white;
		text-transform: capitalize; 
		font-weight: normal;
		font-size: 11px;  
	}
	</style>
	<?

	function check_break_dop($user_id,$town,$id)
	{
		$check = 1;
		switch($id)
		{
			case 6: case 7: case 8:
			{
				$chk_stoilo = myquery("SELECT COUNT(*) FROM game_users_horses WHERE user_id=$user_id AND town=$town AND used=0");
				$sst = mysql_result($chk_stoilo,0,0);			
				if ($sst>0)
				{
					echo '� ���� ���� � ������� ����. ����� ��������� ��������� ���� ������� �������!<br /><br /><br />';
					$check = 0;
				}				
			}
			break;
			case 9: case 10: case 11: case 12:
			{
				$sel_horses = myquery("SELECT craft_resource_market.id,craft_resource_market.col,craft_resource.name,craft_resource.img3 As img FROM craft_resource_market,craft_resource WHERE craft_resource_market.priznak=1 AND craft_resource_market.user_id=$user_id AND craft_resource_market.town=$town AND craft_resource_market.res_id=craft_resource.id");
				$est =  mysql_num_rows($sel_horses);
				if ($est>0)
				{
					echo '� ���� ���� � ��������� �������. ������� ������ �� �� ���������!<br /><br /><br />';
					$check = 0;
				}
			}
			break;
			case 13: case 14: case 15: case 16:
			{
				$sel_horses = myquery("SELECT * FROM game_items WHERE priznak=4 AND user_id=$user_id AND town=$town AND item_id IN (SELECT id FROM game_items_factsheet WHERE type=13)");
				$est =  mysql_num_rows($sel_horses);
				if ($est>0)
				{
					echo '� ���� ���� � ��������� ��������. ������� ������ �� �� ���������!<br /><br /><br />';
					$check = 0;
				}
			}
			break;
		}                                
		return $check;
	}
	
	function get_day_format($alltime)
	{
		//���������� �� $alltime � �������� ������ � ������� � ���� � ����� � ����� � ������
		$days = floor($alltime/(60*60*24));
		$alltime-=$days*60*60*24;
		$hours = floor($alltime/(60*60));
		$alltime-=$hours*60*60;
		$minute = floor($alltime/60);
		$sec = $alltime-$minute*60;
		return $days.' ��. '.$hours.' �. '.$minute.' ���. '.$sec.' ���.';
	}
	function take_res($count,$id_resource,$str,$build_id)
	{
		//�������� � ������ ������� "�����" � "�������� ����" ��� ������������� ������
		global $user_id;
		$count = (int)$count;
		$check = myquery("SELECT col FROM craft_resource_user WHERE user_id=".$user_id." AND res_id=".$id_resource." AND col>=".$count."");
		if ($check!=false AND mysql_num_rows($check)>0)
		{
			list($col_res) = mysql_fetch_array($check);
			if ($col_res==$count)
			{
				myquery("DELETE FROM craft_resource_user WHERE user_id=$user_id AND res_id=".$id_resource."");
			}
			else
			{
				myquery("UPDATE craft_resource_user SET col=GREATEST(0,col-".$count.") WHERE user_id=$user_id AND res_id=".$id_resource."");
				$col_res = $count;
			}
			$res = mysql_fetch_array(myquery("SELECT weight FROM craft_resource WHERE id=".$id_resource.""));
			myquery("UPDATE game_users SET CW=CW-".($col_res*$res['weight'])." WHERE user_id=$user_id");
			myquery("UPDATE houses_users SET $str=$str+".$col_res." WHERE user_id=$user_id AND build_id=$build_id");
			return $col_res;
		}
		return 0;
	}
	
	$img='http://'.img_domain.'/race_table/hobbit/table';
	echo'<table width=100% border="0" cellspacing="0" cellpadding="0" align=center><tr><td width="1" height="1"><img src="'.$img.'_lt.gif"></td><td background="'.$img.'_mt.gif"></td><td width="1" height="1"><img src="'.$img.'_rt.gif"></td></tr>
	<tr><td background="'.$img.'_lm.gif"></td><td background="'.$img.'_mm.gif" valign="top">';
	
	$square_cost = 700;  //��������� ������� 1 ����� �����
    $square_sell = 70;  //��������� ������� 1 ����� �����  
	$all_square = 100;  //������������ ���-�� ����� � ������
	$tax_sotka = 0.1; //������ ������ �������� ��� ������� ����� �� ��������� �����
	$tax_house = 0.15; //������ ������ �������� ��� ������� ���� �� ��������� �����
	
	$zanyato = (int)mysqlresult(myquery("SELECT SUM(square) FROM houses_users WHERE town_id=$town"),0,0); //���-�� ����������� ����� ����� � ������
	$free_square = max(0,$all_square-$zanyato); //���-�� ��������� ��� �� ��������� ����� �����
	
	echo '<br /><div style="width:100%;text-align:center;font-family:Verdana,Tagoma;font-size:12px;font-weight:900;color:#3D1BC7;">����� �������</div><br />';
	
	if (isset($_GET['part1'])) //��������� ����� ������������   
	{
		if ($char['clevel']<=10)
		{
			echo '<center>������ �������� ������ >10 ������<br />';
		}
		
		else
		{
			$im_square = 0;
						
            if (isset($_GET['buy_square']))
            {
                //������� 1 ����� ����� � ������
                if ($free_square>0)
                {
                    if ($char['GP']>=$square_cost)
                    {
                        $free_square--;
                        myquery("UPDATE game_users SET GP=GP-$square_cost,CW=CW-".($square_cost*money_weight)." WHERE user_id=$user_id");
                        setGP($user_id,-$square_cost,34); 
                        $sel = myquery("SELECT id FROM houses_users WHERE user_id=$user_id AND town_id=$town AND type=1 LIMIT 1");
                        if (mysql_num_rows($sel)>0)
                        {
                            list($id_zap) = mysql_fetch_array($sel);
                            myquery("UPDATE houses_users SET square=square+1 WHERE id=$id_zap");
                        }
                        else
                        {
                            myquery("INSERT INTO houses_users (user_id,type,square,town_id) VALUES ($user_id,1,1,$town)");
                        }
                    }
                    else
                    {
                        echo '<center><b>� ���� ������������ ����� ��� ������� �����</b><br><br>';
                    }   
                }
            }
            if (isset($_GET['s_square']))
            {
				//������� 1 ����� ����� � ������
                $im_square_sell = (int)mysqlresult(myquery("SELECT SUM(sotka) FROM houses_market WHERE town_id=$town AND user_id=$user_id"),0,0);
                $sell_square_here = (int)mysqlresult(myquery("SELECT square FROM houses_users WHERE town_id=$town AND user_id=$user_id AND type=1")); 
                $im_square_houses = (int)mysqlresult(myquery("SELECT SUM(houses_templates.square) FROM houses_users,houses_templates WHERE houses_users.town_id=$town AND houses_users.user_id=$user_id AND houses_users.build_id=houses_templates.id AND houses_users.type>1"),0,0); 
                if ($sell_square_here-$im_square_sell-$im_square_houses>0)  
                {
                    $free_square++;
                    myquery("UPDATE game_users SET GP=GP+$square_sell,CW=CW+".($square_sell*money_weight)." WHERE user_id=$user_id");
                    setGP($user_id,+$square_sell,105); 
                    $sel = myquery("SELECT id FROM houses_users WHERE user_id=$user_id AND town_id=$town AND type=1 LIMIT 1");
                    list($id_zap) = mysql_fetch_array($sel);
                    myquery("UPDATE houses_users SET square=square-1 WHERE id=$id_zap");
                    $s1 = myquery("SELECT SUM(square) FROM houses_users WHERE town_id=$town AND user_id=$user_id");
                    list($s2) = mysql_fetch_array($s1);
                    if  (mysql_num_rows($s1)==1 and $s2==0) myquery("Delete From houses_users WHERE town_id=$town AND user_id=$user_id"); 
                }
            }
                
			$im_square = (int)mysqlresult(myquery("SELECT square FROM houses_users WHERE town_id=$town AND user_id=$user_id AND type=1"),0,0);   // ���-�� ����� ����� � ������ �����
			$im_square_sell = (int)mysqlresult(myquery("SELECT SUM(sotka) FROM houses_market WHERE town_id=$town AND user_id=$user_id"),0,0);  // ���-�� ����� ������ �� ��������� �����
			$im_square_houses = (int)mysqlresult(myquery("SELECT SUM(houses_templates.square) FROM houses_users,houses_templates WHERE houses_users.town_id=$town AND houses_users.user_id=$user_id AND houses_users.build_id=houses_templates.id AND houses_users.type>1"),0,0);  // ���-�� ����� ������ ��� �����������
			$im_square_free = $im_square - $im_square_sell - $im_square_houses; //���-�� ��������� ����� ������
			echo '<center>��� ��������� ������ ���������� �����, ����� � ������.<br /><br />';
			echo '� ������ �������� �������� '.$free_square.' �� '.$all_square.' '.pluralForm($all_square,'�����','�����','�����').' �����. <br />������ � ���� �� �������� '.$im_square.' '.pluralForm($im_square,'�����','�����','�����').' �����, �� ���: <br />- ��������� = '.$im_square_free.' '.pluralForm($im_square_free,'�����','�����','�����').' �����;<br />- ��� ����������� = '.$im_square_houses.' '.pluralForm($im_square_houses,'�����','�����','�����').' �����;<br />- ���������� �� ������� = '.$im_square_sell.' '.pluralForm($im_square_sell,'�����','�����','�����').' �����;<br /><br />';
			if ($free_square>0)
            {
				echo '�� ������ ������ �����. <br />��������� ������� 1 ����� ����� ���������� '.$square_cost.' '.pluralForm($square_cost,'������','������','�����').'.<br /><br /><input type="button" value="������ 1 ����� �����" onclick="location.replace(\'town.php?option='.$option.'&part1&buy_square\')"><br /><br />';
            }
				
            $sell_square_here = (int)mysqlresult(myquery("SELECT square FROM houses_users WHERE town_id=$town AND user_id=$user_id AND type=1")); 
            if ($sell_square_here-$im_square_sell-$im_square_houses>0) 
            {  
                echo '�� ������ ������� �����. <br />��������� ������� 1 ����� ����� ���������� '.$square_sell.' '.pluralForm($square_sell,'������','������','�����').'.<br /><br />
                <input type="submit" value="������� 1 ����� �����" onclick="location.replace(\'town.php?option='.$option.'&part1&s_square\')"></input>';
            } 
		}
	}
	elseif (isset($_GET['part2'])) //��������� ����� ������������
	{
		if ($char['clevel']>10)
		{
			echo '<center>����� ���������� �� ��������� ����� �����, �� ������� �� ������ ������� � ������ ����� � ��������� � ����� ������<br /><br />';
			//������ �������
			$im_square = (int)mysqlresult(myquery("SELECT square FROM houses_users WHERE town_id=$town AND user_id=$user_id AND type=1"),0,0); //���-�� ����� ����� ������
			$im_square_sell = (int)mysqlresult(myquery("SELECT SUM(sotka) FROM houses_market WHERE town_id=$town AND user_id=$user_id"),0,0);  //���-�� ����� ������ �� ��������� ����� ������������
			$im_square_houses = (int)mysqlresult(myquery("SELECT SUM(houses_templates.square) FROM houses_users,houses_templates WHERE houses_users.town_id=$town AND houses_users.user_id=$user_id AND houses_users.build_id=houses_templates.id AND houses_users.type>1"),0,0); //���-�� ����� ������ ��� ��� �����������
			$im_square_free = $im_square - $im_square_sell - $im_square_houses;  //���-�� ��������� ����� ������
			if (isset($_POST['sell_sotka']) AND $_POST['sotka_sell']>0)
			{
				//���������� ����� �� �������
				$sotka_sell = (int)$_POST['sotka_sell'];
				$price = (int)$_POST['price'];
				$nalog = round($price*$tax_sotka,2);
				if ($char['GP']<$nalog)
				{
					echo '<br />� ���� ������������ �������� ����� ����� ��������� ����������� ����!<br />';
				}
				elseif ($im_square_free<$sotka_sell)
				{
					echo '<br />� ���� ��� � ������� ������ ���-�� ��������� �����<br />';
				}
				elseif ($price==0)
				{
					echo '<br />���������� ��������� ���� ��� �������!<br />';
				}
				else
				{
					myquery("UPDATE game_users SET GP=GP-$nalog,CW=CW-".($nalog*money_weight)." WHERE user_id=$user_id");
					setGP($user_id,$nalog,37);
					myquery("INSERT INTO houses_market (user_id,sotka,price,town_id) VALUES ($user_id,$sotka_sell,$price,$town)");
					$im_square_free-=$sotka_sell;
				}
			}
			if (isset($_GET['buysotka']) AND isset($_GET['kol']))
			{
				//������� �����
				$kol = (int)$_GET['kol'];
				$buysotka = (int)$_GET['buysotka'];
				if ($buysotka>0 AND $kol>0)
				{
					$im_square = 0;
					//�������� �����
					$selcheck = myquery("SELECT * FROM houses_market WHERE id=$buysotka AND sotka>=$kol AND town_id=$town");
					if (mysql_num_rows($selcheck)>0)
					{
						$sell = mysql_fetch_array($selcheck);
						$price = round($kol*($sell['price']/$sell['sotka']),2);
						if ($user_id!=$sell['user_id'])
						{
							$selcheck = myquery("SELECT square FROM houses_users WHERE user_id=".$sell['user_id']." AND square=".$kol." AND town_id=$town AND type=1");
							if (mysql_num_rows($selcheck)>0)
							{
								//� �������� ������ ��� ����� � ���� ������. ������� ��� ������ �� ��������
								myquery("DELETE FROM houses_users WHERE town_id=$town AND user_id=".$sell['user_id']."");
							}
							else
							{
								myquery("UPDATE houses_users SET square=square-$kol WHERE user_id=".$sell['user_id']." AND town_id=$town AND type=1");
							}
							$selcheck = myquery("SELECT id FROM houses_users WHERE user_id=$user_id AND town_id=$town AND type=1");
							if (mysql_num_rows($selcheck)>0)
							{
								myquery("UPDATE houses_users SET square=square+$kol WHERE town_id=$town AND user_id=$user_id AND type=1");
							}
							else
							{
								myquery("INSERT INTO houses_users (user_id,square,type,town_id) VALUES ($user_id,$kol,1,$town)");
							}
							myquery("UPDATE game_users SET GP=GP-$price,CW=CW-".($price*money_weight)." WHERE user_id=$user_id");
							setGP($user_id,-$price,35);
							myquery("UPDATE game_users SET GP=GP+$price,CW=CW+".($price*money_weight)." WHERE user_id=".$sell['user_id']."");
							myquery("UPDATE game_users_archive SET GP=GP+$price,CW=CW+".($price*money_weight)." WHERE user_id=".$sell['user_id']."");
							setGP($sell['user_id'],+$price,35);
						}
						if ($kol==$sell['sotka'])
						{
							myquery("DELETE FROM houses_market WHERE id=$buysotka");
						}
						else
						{
							myquery("UPDATE houses_market SET sotka=sotka-$kol, price=price-$price WHERE id=$buysotka");
						}
						echo '<br />�� ����� '.$kol.' '.pluralForm($kol,'�����','�����','�����').' ����� �� '.$price.' ���.<br />';
						myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES ('".$sell['user_id']."', '0', '��������� ����� �����', '����� ".$char['name']." ����� ".$kol." ".pluralForm($kol,'�����','�����','�����')." ����� ����� �� ".$price." ".pluralForm($price,'������','������','�����')."','0','".time()."')");
						$im_square_free+=$kol;
					}
					
				}
			}
			if (isset($_POST['sell_house']))
			{
				$selhouse = myquery("SELECT id FROM houses_users WHERE user_id=$user_id AND type=2");
				if (mysql_num_rows($selhouse)>0)
				{
					list($build_id_house) = mysql_fetch_array($selhouse);
					$check = 1;
					$sel_dop = myquery("SELECT build_id FROM houses_users WHERE user_id=$user_id AND town_id=$town AND type=3");
					while (list($build_id)=mysql_fetch_array($sel_dop))
					{
						if (check_break_dop($user_id,$town,$build_id)!=1)
						{
							$check = 0;
						}
					}
					$sel_horses = myquery("SELECT * FROM game_items WHERE priznak=4 AND user_id=$user_id AND town=$town AND item_id IN (SELECT id FROM game_items_factsheet WHERE type<>13)");
					$est =  mysql_num_rows($sel_horses);
					if ($est>0)
					{
						echo '� ���� ���� � ��������� ��������. ������� ������ �� �� ���������!<br />';
						$check = 0;
					}
					elseif ($price==0)
					{
						echo '<br />���������� ��������� ���� ��� �������!<br />';
						$check = 0;
					}
					if ($check==1)
					{
						//���������� ��� �� �������
						$price = (int)$_POST['price'];
						$nalog = round($price*$tax_house,2);
						if ($char['GP']<$nalog)
						{
							echo '<br />� ���� ������������ �������� ����� ����� ��������� ����������� ����!<br />';
						}
						else
						{
							myquery("UPDATE game_users SET GP=GP-$nalog,CW=CW-".($nalog*money_weight)." WHERE user_id=$user_id");
							setGP($user_id,$nalog,37);
							myquery("INSERT INTO houses_market (user_id,build_id,price,town_id) VALUES ($user_id,$build_id_house,$price,$town)");
							myquery("DELETE FROM houses_users WHERE user_id=$user_id AND type=3 AND town=".$town."");
							myquery("UPDATE houses_users SET type=4 WHERE id=$build_id_house");
						}
					}
				}
			}
			if (isset($_GET['buyhouse']))
			{
				$sel_dom = myquery("SELECT COUNT(*) FROM houses_users WHERE user_id=$user_id AND town_id=$town AND type=2");
				if (mysql_result($sel_dom,0,0)>0)
				{
					echo '� ���� ��� ���� ���. ������ ������ ������!<br />';
				}
				else
				{
					$sel = myquery("SELECT houses_templates.name,houses_templates.square FROM houses_users,houses_market,houses_templates WHERE houses_market.id=".$_GET['buyhouse']." AND houses_users.id=houses_market.build_id AND houses_templates.id=houses_users.build_id");
					if (mysql_num_rows($sel))
					{
						list($h) = mysql_fetch_array($sel);
						if ($im_square_free<$h['square'])
						{
							echo '�� ������ ������ � ���� ������ '.$h['square'].' '.pluralForm($h['square'],'�����','�����','�����').'  �����, ����� ������ ���� ���!<br />';
						}
						else
						{
							//��������� �������
							$sell = mysql_fetch_array(myquery("SELECT * FROM houses_market WHERE id=".$_GET['buyhouse'].""));
							if ($char['GP']>=$sell['price'])
							{
								myquery("DELETE FROM houses_market WHERE id=".$_GET['buyhouse']."");
								myquery("UPDATE game_users SET GP=GP-".$sell['price'].",CW=CW-".($sell['price']*money_weight)." WHERE user_id=$user_id");
								setGP($user_id,-$sell['price'],36);
								myquery("UPDATE game_users SET GP=GP+".$sell['price'].",CW=CW+".($sell['price']*money_weight)." WHERE user_id=".$sell['user_id']."");
								myquery("UPDATE game_users_archive SET GP=GP+".$sell['price'].",CW=CW+".($sell['price']*money_weight)." WHERE user_id=".$sell['user_id']."");
								setGP($user_id,+$sell['price'],36);
								myquery("UPDATE houses_users SET type=2,user_id=$user_id WHERE id=".$sell['build_id']."");
								$sqr=(int)mysqlresult(myquery("SELECT houses_templates.square FROM houses_templates,houses_users WHERE houses_templates.id=houses_users.build_id AND houses_users.id=".$sell['build_id'].""),0,0);
								$sqrchk=myquery("SELECT square FROM houses_users WHERE user_id=$user_id AND town_id=$town AND type=1");
								$sqrchk2=(int)mysqlresult(myquery("SELECT square FROM houses_users WHERE user_id=".$sell['user_id']." AND town_id=$town AND type=1"),0,0);
								if (mysql_num_rows($sqrchk)>0)
								{
									myquery("UPDATE houses_users SET square=square+$sqr WHERE town_id=$town AND user_id=$user_id AND type=1");
								}
								else
								{
									myquery("INSERT INTO houses_users (user_id,square,type,town_id) VALUES ($user_id,$sqr,1,$town)");
								}
								if ($sqrchk2-$sqr>0)
								{
									myquery("UPDATE houses_users SET square=square-$sqr WHERE town_id=$town AND user_id=".$sell['user_id']." AND type=1");
								}
								else
								{
									myquery("DELETE FROM houses_users WHERE town_id=$town AND user_id=".$sell['user_id']."");
								}
								echo '������ ���������!<br />';
							}
							else
							{
								echo '� ���� ������������ �������� ����� ��� ������� ����!<br />';
							}
						}
					}
				}	
			}
			$sel_sqaure = myquery("(SELECT houses_market.*,game_users.name,game_users.clevel,game_users.clan_id,game_har.name AS race FROM houses_market,game_users,game_har WHERE houses_market.user_id=game_users.user_id AND game_har.id=game_users.race AND houses_market.build_id=0 AND houses_market.town_id=$town) UNION (SELECT houses_market.*,game_users_archive.name,game_users_archive.clevel,game_users_archive.clan_id,game_har.name AS race FROM houses_market,game_users_archive,game_har WHERE houses_market.user_id=game_users_archive.user_id AND game_har.id=game_users_archive.race AND houses_market.build_id=0 AND houses_market.town_id=$town)");
			$sel_build = myquery("(SELECT houses_market.*,houses_templates.square,houses_users.build_id,houses_templates.name As template_name,game_users.name,game_users.clevel,game_users.clan_id,game_har.name AS race FROM houses_market,game_users,game_har,houses_users,houses_templates WHERE houses_users.user_id=game_users.user_id AND houses_templates.id=houses_users.build_id AND houses_market.build_id=houses_users.id AND game_users.user_id=houses_market.user_id AND game_har.id=game_users.race AND houses_market.town_id=$town AND houses_market.build_id>0) UNION (SELECT houses_market.*,houses_templates.square,houses_users.build_id,houses_templates.name AS template_name,game_users_archive.name,game_users_archive.clevel,game_users_archive.clan_id,game_har.name AS race FROM houses_market,game_users_archive,game_har,houses_users,houses_templates WHERE houses_users.user_id=game_users_archive.user_id AND houses_templates.id=houses_users.build_id AND houses_market.build_id=houses_users.id AND game_users_archive.user_id=houses_market.user_id AND game_har.id=game_users_archive.race AND houses_market.town_id=".$town." AND houses_market.build_id>0)");
			if (($sel_build!=false AND mysql_num_rows($sel_build)>0)OR($sel_sqaure!=false AND mysql_num_rows($sel_sqaure)>0))
			{
				echo '<center>� ������ ������ ���������� �� �������<br /><br />';
				echo '<table cellspacing=2 cellpadding=2 style="border: 2px groove gold;" width="90%"><tr style="font-size:12px;color:white;font-weight:900;"><td>��������</td><td>���/�����</td><td>��������� (���.)</td><td>&nbsp;</td></tr>';
				if ($sel_sqaure!=false AND mysql_num_rows($sel_sqaure)>0)
				{
					while ($prod = mysql_fetch_array($sel_sqaure))
					{
						echo '<tr><td>'.$prod['name'].'<a href="http://'.domain_name.'/view/?userid='.$prod['user_id'].'" target="_blank"><img src="http://'.img_domain.'/nav/i.gif" border="0"></a> ('.$prod['race'].' '.$prod['clevel'].' ������)';
						if ($prod['clan_id']!=0)
						{
							echo '<br />����: <img src="http://'.img_domain.'/clan/'.$prod['clan_id'].'.gif" border="0">';
						}
						echo '</td><td>';
						echo ''.$prod['sotka'].' '.pluralForm($prod['sotka'],'�����','�����','�����').' �����';
						echo '</td><td>'.$prod['price'].'</td><td>';
						echo '<input type="text" size="5" id="kol_sotka'.$prod['id'].'" value="0"> ����� <input type="button" value="������" onclick="location.replace(\'town.php?option='.$option.'&part2&buysotka='.$prod['id'].'&kol=\'+document.getElementById(\'kol_sotka'.$prod['id'].'\').value+\'\')">';
						echo '</td></tr>';
					}
				}
				if ($sel_build!=false AND mysql_num_rows($sel_build)>0)
				{ 
					while ($prod = mysql_fetch_array($sel_build))
					{
						echo '<tr><td>'.$prod['name'].'<a href="http://'.domain_name.'/view/?userid='.$prod['user_id'].'" target="_blank"><img src="http://'.img_domain.'/nav/i.gif" border="0"></a> ('.$prod['race'].' '.$prod['clevel'].' ������)';
						if ($prod['clan_id']!=0)
						{
							echo '<br />����: <img src="http://'.img_domain.'/clan/'.$prod['clan_id'].'.gif" border="0">';
						}
						echo '</td><td>';
						echo $prod['template_name'].'(�������� '.$prod['square'].' '.pluralForm($prod['square'],'�����','�����','�����').'  �����)'; 
						echo '</td><td>'.$prod['price'].'</td><td>';
						echo '<input type="button" value="������" onclick="location.replace(\'town.php?option='.$option.'&part2&buyhouse='.$prod['id'].'\')">';
						echo '</td></tr>';
					}
				}
				echo '</table>';
			}
			if ($im_square_free>0)
			{
				echo '<div style="width:100%;height:0px;border: 3px #005800 groove;"></div><br />�� �������� '.$im_square_free.' '.pluralForm($im_square_free,'������','�������','�������').' �����. �� ����� ��������� �� ������� �� ��������� �����!<br /><br />
				<form action="town.php?option='.$option.'&part2" method="post">
				��������� �� ������� <input type="text" size="5" name="sotka_sell" value="0"> �� '.$im_square_free.' '.pluralForm($im_square_free,'�����','�����','�����').' �����<br />';
				echo '�� ����: <input type="text" size="5" name="price" value="0"> �����.<br />
				(�� ������������� ����� ��� ����������� ����� �� ������� ���� ����� ��������� '.($tax_sotka*100).'% ����������� ������)<br />
				<br /><input type="submit" name="sell_sotka" value="��������� ����� �� �������"><br /><br /></form>';
			}
			$selhouse = myquery("SELECT houses_templates.*,houses_users.id AS houses_users_id FROM houses_users,houses_templates WHERE houses_users.user_id=$user_id AND houses_users.town_id=$town AND houses_users.buildtime<=".time()." AND houses_users.buildtime>0 AND houses_users.type=2 AND houses_users.build_id=houses_templates.id");
			if ($selhouse!=false AND mysql_num_rows($selhouse)>0)
			{
				$house = mysql_fetch_array($selhouse);
				echo '<div style="width:100%;height:0px;border: 3px #005800 groove;"></div><br />� ���� �������� '.$house['name'].', ������� �������� '.$house['square'].' '.pluralForm($house['square'],'�����','�����','�����').' �����.<br />�� ������ ��� ��������� �� ������� �� ��������� ����� �����.<br /><br />����� �������� ���������� ������� ��� ����/��������/������� �� �������� � �������.<br /><b><font color=red>��� ������� ����� ������� ��� �������������� ���������!</font></b><br />    
				<form action="town.php?option='.$option.'&part2" method="post">
				��������� �� ������� '.$house['name'].' �� ����: <input type="text" size="8" name="price" value="0"> �����.<br />
				(�� ������������� ����� ��� ����������� ���� �� ������� ���� ����� ��������� '.($tax_house*100).'% ������������ ������)<br />
				<br /><input type="hidden" value="1" name="sell_house"><input type="submit" value="��������� ��� �� �������"><br /><br /></form>';
			}
		}
		else
		{
			echo '<center>������ �������� ������ >10 ������';
		}
	}
	elseif (isset($_GET['part3'])) //����� 
	{
		$sel = myquery("(SELECT houses_users.*,houses_templates.name AS house_name,houses_templates.buildtime AS need_time,game_users.name,game_users.clevel,game_users.clan_id,game_har.name AS race,game_users.sklon FROM houses_templates,houses_users,game_users,game_har WHERE houses_users.build_id=houses_templates.id AND game_users.user_id=houses_users.user_id AND game_har.id=game_users.race AND houses_users.town_id=$town AND houses_users.type IN (2,4)) UNION  (SELECT houses_users.*,houses_templates.name AS house_name,houses_templates.buildtime AS need_time,game_users_archive.name,game_users_archive.clevel,game_users_archive.clan_id,game_har.name AS race,game_users_archive.sklon FROM houses_templates,houses_users,game_users_archive,game_har WHERE houses_users.build_id=houses_templates.id AND game_users_archive.user_id=houses_users.user_id AND game_har.id=game_users_archive.race AND houses_users.town_id=$town AND houses_users.type IN (2,4))");
		$build_user = array();
		if ($sel!=false AND mysql_num_rows($sel)>0)
		{
			echo '<div style="width:100%;height:0px;border: 3px #005800 groove;"></div><center><br />� ������ ������ ���������:<br /><br />';
			echo '<table cellspacing=2 cellpadding=2 style="border: 2px groove gold;" width="95%"><tr style="font-size:12px;color:white;font-weight:900;"><td>��������</td><td>���</td><td>������</td></tr>';
			while ($citizen = mysql_fetch_array($sel))
			{
				$build_user[] = $citizen['user_id'];
				echo '<tr><td><a href="http://'.domain_name.'/view/?userid='.$citizen['user_id'].'" target="_blank"><img src="http://'.img_domain.'/nav/i.gif" border="0"></a>';
				if ($citizen['clan_id']!=0)
				{
					echo '<img src="http://'.img_domain.'/clan/'.$citizen['clan_id'].'.gif" border="0">';
				}
				if ($citizen['sklon']!=0)
				{
					if ($citizen['sklon']==1) echo '<img src="http://'.img_domain.'/sklon/neutral.gif" border="0">';
					if ($citizen['sklon']==2) echo '<img src="http://'.img_domain.'/sklon/light.gif" border="0">';
					if ($citizen['sklon']==3) echo '<img src="http://'.img_domain.'/sklon/dark.gif" border="0">';
				}
				echo $citizen['name'].'&nbsp;('.$citizen['race'].' '.$citizen['clevel'].' ������)';
				echo '</td><td>';
				echo $citizen['house_name'];
				echo '</td><td>';
				if ($citizen['buildtime']>time())
				{
					$allbuildtime = $citizen['need_time'];
					$procent = 0;
					if ($allbuildtime>0)
					{
						$procent = 100-round((($citizen['buildtime']-time())/$allbuildtime),2)*100;
					}
					echo '�������� ('.$procent.'%), ��� '.get_day_format($citizen['buildtime']-time());
				}
				else
				{
					if ($citizen['buildtime']!=0)
					{
						echo '��������: '.date("d.m.Y",$citizen['buildtime']);
						if ($citizen['type']==4)
						{
							echo '<br />(���������)';
						}
					}
				}
			}
			echo '</td></table>';
		}
		else
		{
			echo '<center>� ������ ������ ��� ����������� �����';
		}
		$sel = myquery("(SELECT houses_users.*,game_users.name,game_users.clevel,game_users.clan_id,game_har.name AS race,game_users.sklon FROM houses_users,game_users,game_har WHERE game_users.user_id=houses_users.user_id AND game_har.id=game_users.race AND houses_users.town_id=$town AND houses_users.type=1) UNION  (SELECT houses_users.*,game_users_archive.name,game_users_archive.clevel,game_users_archive.clan_id,game_har.name AS race,game_users_archive.sklon FROM houses_users,game_users_archive,game_har WHERE game_users_archive.user_id=houses_users.user_id AND game_har.id=game_users_archive.race AND houses_users.town_id=$town AND houses_users.type=1)");
		if ($sel!=false AND mysql_num_rows($sel)>0)
		{
			$i = 0;
			$output_string = '';
			$output_string.='<br /><div style="width:100%;height:0px;border: 3px #005800 groove;"></div><center><br />� ������ ������ ������� ������:<br /><br />';
			$output_string.='<table cellspacing=2 cellpadding=2 style="border: 2px groove gold;" width="95%"><tr style="font-size:12px;color:white;font-weight:900;"><td>��������</td><td>�����</td></tr>';
			while ($citizen = mysql_fetch_array($sel))
			{
				if (in_array($citizen['user_id'],$build_user))
				{
					continue;
				}
				$i++;
				$output_string.='<tr><td><a href="http://'.domain_name.'/view/?userid='.$citizen['user_id'].'" target="_blank"><img src="http://'.img_domain.'/nav/i.gif" border="0"></a>';
				if ($citizen['clan_id']!=0)
				{
					$output_string.='<img src="http://'.img_domain.'/clan/'.$citizen['clan_id'].'.gif" border="0">';
				}
				if ($citizen['sklon']!=0)
				{
					if ($citizen['sklon']==1) $output_string.='<img src="http://'.img_domain.'/sklon/neutral.gif" border="0">';
					if ($citizen['sklon']==2) $output_string.='<img src="http://'.img_domain.'/sklon/light.gif" border="0">';
					if ($citizen['sklon']==3) $output_string.='<img src="http://'.img_domain.'/sklon/dark.gif" border="0">';
				}
				$output_string.=$citizen['name'].'&nbsp;('.$citizen['race'].' '.$citizen['clevel'].' ������)';
				$output_string.='</td><td>';
				$output_string.=$citizen['square'];
				$output_string.='</td></tr>';
			}
			$output_string.='</table><br />';
			if ($i>0)
			{
				echo $output_string;
			}
		}
	}
	elseif (isset($_GET['part4'])) //������ �������������  
	{
		echo '<center>';
		$sel = myquery("SELECT * FROM houses_users WHERE user_id=$user_id AND town_id=$town AND type=1 LIMIT 1");
		if ($sel==false OR mysql_num_rows($sel)==0)
		{
			echo '� ���� ��� ��� ������� �������������<br />������� ���� ����� �� ��������� ����� ��� ���������<br />';
		}
		else
		{
			set_delay_id($user_id,32);
			$sel = myquery("SELECT * FROM houses_users WHERE user_id=$user_id AND town_id=$town AND type IN (2,4) LIMIT 1");
			$start_build_home = 0;
			if ($sel==false OR mysql_num_rows($sel)==0 OR isset($_GET['upgradehouse']))
			{
				if (isset($_GET['buildhome']))
				{
					$can_build = true;
					$im_square = (int)mysqlresult(myquery("SELECT square FROM houses_users WHERE town_id=$town AND user_id=$user_id AND type=1"),0,0);
					$im_square_sell = (int)mysqlresult(myquery("SELECT SUM(sotka) FROM houses_market WHERE town_id=$town AND user_id=$user_id"),0,0);
					$im_square_houses = (int)mysqlresult(myquery("SELECT SUM(houses_templates.square) FROM houses_users,houses_templates WHERE houses_users.town_id=$town AND houses_users.user_id=$user_id AND houses_users.build_id=houses_templates.id AND houses_users.type>1"),0,0); 
					$im_square_free = $im_square - $im_square_sell - $im_square_houses;
					$templ = mysql_fetch_array(myquery("SELECT * FROM houses_templates WHERE id=".$_GET['buildhome'].""));
					$check = myquery("SELECT build_id FROM houses_users WHERE user_id=$user_id AND town_id=$town AND type=2");
					$minus_square = $templ['square'];
					if (mysql_num_rows($check)>0)
					{
						//������ �������
						list($build_id_home) = mysql_fetch_array($check);
						$have_home_square = mysqlresult(myquery("SELECT square FROM houses_templates WHERE id=$build_id_home"),0,0);
						$im_square_free+=$have_home_square;
						$minus_square-=$have_home_square;
					}
					if ($templ['square']>$im_square_free)
					{
						echo '� ���� ������������ ��������� ����� ��� ������������� ����<br />';   
						$can_build = false;
					}
					if ($can_build)
					{
						$sel_need = myquery("SELECT * FROM houses_templates_need WHERE build_id=".$_GET['buildhome']."");
						if (mysql_num_rows($sel_need)>0)
						{
							$can_build = false;
							while ($need = mysql_fetch_array($sel_need))
							{
								$check = myquery("SELECT * FROM houses_users WHERE buildtime<=".time()." AND user_id=$user_id AND town_id=$town AND build_id IN (".$need['need'].")");
								if (mysql_num_rows($check)==sizeof(explode(',',$need['need'])))
								{
									$can_build = true;
									break;
								}
							}
						}
					}
					if ($can_build)
					{
						if (mysql_num_rows($check)>0)
						{
							//������� ����
							myquery("UPDATE houses_users SET stone=0,doska=0,buildtime=0,build_id=".$_GET['buildhome']." WHERE user_id=$user_id AND type=2 AND town_id=$town");
							if (isset($_GET['upgradehouse'])) unset($_GET['upgradehouse']);
						}
						else
						{
							//����� ���
							myquery("INSERT INTO houses_users (user_id,type,town_id,build_id) VALUES ($user_id,2,$town,".$_GET['buildhome'].")");
						}
						$start_build_home=1; 
						$sel = myquery("SELECT * FROM houses_users WHERE user_id=$user_id AND town_id=$town AND type=2 LIMIT 1"); 
						//myquery("UPDATE houses_users SET square=square-$minus_square WHERE user_id=$user_id AND town_id=$town AND type=1");
					}   
				}   
			}
			if (($sel==false OR mysql_num_rows($sel)==0 OR isset($_GET['upgradehouse']))AND($start_build_home==0))
			{
				//��� ��� �� ��������. ������� ���� ������ ���� ���� ��� ���������
				if (isset($_GET['upgradehouse']))
				{
					list($build_id_home) = mysqlresult(myquery("SELECT build_id FROM houses_users WHERE user_id=$user_id AND town_id=$town AND type=2"),0,0);
					$sel_templ = myquery("SELECT * FROM houses_templates WHERE type=1 AND instead=$build_id_home ORDER BY buildcost ASC");
				}
				else
				{
					$sel_templ = myquery("SELECT * FROM houses_templates WHERE type=1 ORDER BY buildcost ASC");
				}
				echo '<center>�� ������ ������ ��������� ������ �� ��������� ����� ����:<br /><br />';
				echo '<table cellspacing=2 cellpadding=2 style="border: 2px groove gold;" width="90%"><tr style="font-size:12px;color:white;font-weight:900;"><td>��������</td><td>�������� ������� (���.)</td><td>������� ������� ����������� ������</td><td>���� ��� ��������� � ���������</td><td>����� �������������</td><td>��������� �������������</td><td>&nbsp;</td></tr>';
				while ($templ = mysql_fetch_array($sel_templ))
				{
					$rest_time = get_day_format($templ['buildtime']);
					$add_templ = '';
					$sel_need = myquery("SELECT * FROM houses_templates_need WHERE build_id=".$templ['id']."");
					if (mysql_num_rows($sel_need)>0)
					{
						while ($need = mysql_fetch_array($sel_need))
						{
							$sel_add = myquery("SELECT name FROM houses_templates WHERE id IN (".$need['need'].") ORDER BY BINARY name");
							$add_need = '';
							while (list($add_name)=mysql_fetch_array($sel_add))
							{
								$add_need.=$add_name.',';
							}    
							if (strlen($add_need)>0)
							{
								$add_templ.= substr($add_need,0,strlen($add_need)-1); 
								$add_templ.='&nbsp;&nbsp;���';
							}
						}
					}
					if (strlen($add_templ)>0)
					{
						$add_templ = substr($add_templ,0,strlen($add_templ)-15); 
					}
					echo '<tr align="center"><td>'.$templ['name'].'</td><td>'.$templ['square'].'</td><td>'.$add_templ.'</td><td>'.$templ['min_value'].'</td><td>'.$rest_time.'</td><td>'.$templ['buildcost'].'</td><td>';
					$can_build = true;
					$sel_need = myquery("SELECT * FROM houses_templates_need WHERE build_id=".$templ['id']."");
					if (mysql_num_rows($sel_need)>0)
					{
						$can_build = false;
						while ($need = mysql_fetch_array($sel_need))
						{
							$check = myquery("SELECT * FROM houses_users WHERE buildtime<=".time()." AND user_id=$user_id AND town_id=$town AND build_id IN (".$need['need'].")");
							if (mysql_num_rows($check)==sizeof(explode(',',$need['need'])))
							{
								$can_build = true;
								break;
							}
						}
					}
					if ($can_build)
					{
						if (isset($_GET['upgradehouse']))
						{
							echo '<input type="button" value="��������� ���� ���" onclick="location.replace(\'town.php?option='.$option.'&part4&buildhome='.$templ['id'].'&upgradehouse\')">';
						}
						else
						{
							echo '<input type="button" value="������ ������������� ����� ����" onclick="location.replace(\'town.php?option='.$option.'&part4&buildhome='.$templ['id'].'\')">';
						}
					}
					else
					{
						echo '&nbsp;';
					}
					echo '</td></tr>';
				}
				echo '</table>';
			}
			else
			{
				set_delay_reason_id($user_id,32);
				$myhouse = mysql_fetch_array($sel);
				if ($myhouse['type']==4)
				{
					echo '�� �������� ���� ��� �� �������!<br />';
				}
				else
				{
					$templ = mysql_fetch_array(myquery("SELECT * FROM houses_templates WHERE id=".$myhouse['build_id'].""));
					if (isset($_GET['startbuildhome']) AND $myhouse['buildtime']==0)
					{
						if ($myhouse['stone']>=$templ['stone'] AND $myhouse['doska']>=$templ['doska'])
						{
							if ($char['GP']>=$templ['buildcost'])
							{
								$buildtime = time()+$templ['buildtime'];
								myquery("UPDATE houses_users SET buildtime=".$buildtime." WHERE type=2 AND user_id=$user_id AND town_id=$town");
								$myhouse['buildtime'] = $buildtime;
								myquery("UPDATE game_users SET GP=GP-".$templ['buildcost'].",CW=CW-".($templ['buildcost']*money_weight)." WHERE user_id=$user_id");
								setGP($user_id,-$templ['buildcost'],34);
							}
							else
							{
								echo '<b>� ��� ������������ ����� ��� �������������!</b><br><br>';
							}
						}
					}
					$est_dolg = round(mysqlresult(myquery("SELECT COUNT(*) FROM houses_nalog WHERE user_id=".$user_id." AND nalog>pay"),0,0),2);					
					if ($est_dolg>2 AND !isset($_GET['pay']))
					{
						if (!isset($_GET['inv']) AND !isset($_GET['dom']) AND !isset($_GET['add']) AND !isset($_GET['buildadd'])) 
						{
							echo '<a href=?option='.$option.'&part4&nalog>������ ������ �� �����</a><br /><br />';
						}
						echo '�� ������ ������������ ������������� ������ �� �����. ������ � ������� ������������� ����� ������ ������ ����� ������ ���� �������.';
					}
					else
					{
						$buildnowhouse = 0;
						if ($est_dolg>0)
						{							
							if (!isset($_GET['inv']) AND !isset($_GET['dom']) AND !isset($_GET['add']) AND !isset($_GET['buildadd'])) 
							{
								echo '<a href=?option='.$option.'&part4&nalog>������ ������ �� �����</a><br /><br />';
							}													
						}
						
						if ($myhouse['buildtime']==0) 
						{
							include('../inc/craft/craft.inc.php');
							QuoteTable('open','80%');							
							
							$kol_doska = 0;
							$kol_block = 0;
							$put_doska = 0;
							$put_block = 0;							
							
							if (isset($_GET['addhome_doska']) AND (int)$_GET['addhome_doska']>0)
							{									
								$check_doska = myquery("SELECT col FROM craft_resource_user WHERE user_id=".$user_id." AND res_id=".$id_resource_doska."");
								if (mysql_num_rows($check_doska) > 0)
								{
									$kol_doska = mysqlresult($check_doska,0,0);
								}
								$put_doska = max( min($kol_doska, (int)$_GET['addhome_doska'], ($templ['doska']-$myhouse['doska'])), 0 );
								$myhouse['doska']+=$put_doska;
							}
							
							if (isset($_GET['addhome_stone']) AND (int)$_GET['addhome_stone']>0) 
							{
								$check_block = myquery("SELECT col FROM craft_resource_user WHERE user_id=".$user_id." AND res_id=".$id_resource_blok."");
								if (mysql_num_rows($check_block) > 0)
								{
									$kol_block = mysqlresult($check_block,0,0);
								}
								$put_block = max( min($kol_block, (int)$_GET['addhome_stone'], ($templ['stone']-$myhouse['stone'])), 0);
								$myhouse['stone']+=$put_block;
							}
							
							if ($put_block > 0 OR $put_doska > 0)
							{
								myquery("UPDATE houses_users SET stone = stone + ".$put_block.", doska = doska + ".$put_doska." WHERE id=".$myhouse['id']."");
							}								
							
							if ($put_block > 0)
							{
								$Res = new Res (0, $id_resource_blok);
								$Res->add_user(0, $user_id, -$put_block);
							}
							if ($put_doska > 0)
							{
								$Res = new Res (0, $id_resource_doska);
								$Res->add_user(0, $user_id, -$put_doska);
							}	
								
							echo '<center>�� ������� '.$templ['name'].'<br />';
							echo '����� ��� ��������� ������������� � ��������� ���� ������� '.$templ['stone'].' '.pluralForm($templ['stone'],'�������� ����','�������� �����','�������� ������').' � '.$templ['doska'].' '.pluralForm($templ['doska'],'�����','�����','�����').'.<br />';
							echo '������ �� '.echo_sex('������','�������').' � ������������� '.$myhouse['stone'].' '.pluralForm($myhouse['stone'],'�������� ����','�������� �����','�������� ������').' � '.$myhouse['doska'].' '.pluralForm($myhouse['doska'],'�����','�����','�����').'.<br /><br /><br />';
										
							if ($myhouse['stone']>=$templ['stone'] AND $myhouse['doska']>=$templ['doska'])
							{
								//�������� ������� ����������
								echo '<br />�������� ������� ����������. ����� �������� ������������� ����. ������������� ������ '.get_day_format($templ['buildtime']).'<br />�� ������������� ���������� ��������� '.$templ['buildcost'].' ���.<br /><br /><br /><input type="button" value="������ ������������� ����� ����" onclick="location.replace(\'town.php?option='.$option.'&part4&startbuildhome\')"><br /><br />';
							}
							else
							{
								$selres = myquery("SELECT col,res_id FROM craft_resource_user WHERE (res_id=".$id_resource_doska." OR res_id=".$id_resource_blok.") AND user_id=".$user_id." AND col>0");
								while ($res = mysql_fetch_array($selres))
								{
									if ($res['res_id']==$id_resource_blok and $myhouse['stone']<$templ['stone'])
									{
										echo '<br />������� � ������������� ����: <input type="text" size="5" maxsize="3" id="res_stone" name="stoneres" value="'.min(($templ['stone']-$myhouse['stone']), $res['col']).'"> �� '.$res['col'].' ������ �������� ������&nbsp;&nbsp;&nbsp;<input type="button" value="������� �������� �����" onclick="location.replace(\'town.php?option='.$option.'&part4&addhome_stone=\'+document.getElementById(\'res_stone\').value+\'\')"><br />';
									}
									if ($res['res_id']==$id_resource_doska and $myhouse['doska']<$templ['doska'])
									{
										echo '<br />������� � ������������� ����: <input type="text" size="5" maxsize="3" id="res_doska" name="doskares" value="'.min(($templ['doska']-$myhouse['doska']), $res['col']).'"> �� '.$res['col'].' ������ �����&nbsp;&nbsp;&nbsp;<input type="button" value="������� �����" onclick="location.replace(\'town.php?option='.$option.'&part4&addhome_doska=\'+document.getElementById(\'res_doska\').value+\'\')"><br />';
									}
								}
							}
							QuoteTable('close');
							echo '<br /><br /><br />';   
							if ($myhouse['type']==2)
							{
								$buildnowhouse = 1;
							}
						}
						if ($myhouse['buildtime']>time() AND !isset($_GET['break']))
						{
							echo '�� ��� �� '.echo_sex('��������','���������').' ���� ���. � ���� ���������� ����� ���������.<br />';
							echo '<br /><br />������ �� '.echo_sex('�����','������').' ��� ��������������.<br />';
							$allbuildtime = $templ['buildtime'];
							$procent = 0;
							if ($allbuildtime>0)
							{
								$procent = 100-round((($myhouse['buildtime']-time())/$allbuildtime),2)*100;
							}
							$alltime = $myhouse['buildtime']-time();
							$rest_time = get_day_format($alltime);
							echo $templ['name'].' ����� �� '.$procent.'%. �� ����� ������������� ��������: '.$rest_time.'<br /><br /><a href="?option='.$option.'&part4&break='.$myhouse['id'].'">�������� �������������</a><br /><br /><br />';
							$buildnow = 1; 
						}
						if (isset($_GET['break']))
						{
							$house_id = (int)$_GET['break'];
							$check = myquery("SELECT build_id,buildtime,type FROM houses_users WHERE user_id=$user_id AND town_id=$town AND id=$house_id");
							if ($check!=false AND mysql_num_rows($check)>0)
							{
								list($build_id,$build_time)=mysql_fetch_array($check);
								list($build_name,$build_type,$build_id)=mysql_fetch_array(myquery("SELECT name,type,id FROM houses_templates WHERE id=$build_id"));
								if (isset($_GET['breaknow']))
								{
									if ($build_type==1)
									{
										if ($build_id==1)
										{
											myquery("DELETE FROM houses_users WHERE user_id=$user_id AND town_id=$town AND id=$house_id"); 
										}
										else
										{
											myquery("UPDATE houses_users SET build_id=".($build_id-1).",buildtime=UNIX_TIMESTAMP(),stone=0,doska=0 WHERE id=$house_id");
										}    
									}
									else
									{
										myquery("DELETE FROM houses_users WHERE user_id=$user_id AND town_id=$town AND id=$house_id");
									}
									setLocation("town.php?option=".$option."&part4"); 
								}
								else
								{
									if ($build_time>time())
									{
										echo '�� ������������� ������ �������� ������������� ������: "'.$build_name.'" ???<br /><br /><br /><input type="button" value="��, � ���� �������� �������������" onclick="location.replace(\'town.php?option='.$option.'&part4&breaknow&break='.$house_id.'\')"><br /><br /><br /><input type="button" value="���, � �� ���� �������� �������������" onclick="location.replace(\'town.php?option='.$option.'&part4\')">';
									}
									else
									if ($build_type==2)
									{
										//������ ������������
										if (check_break_dop($user_id,$town,$build_id)==1)
										{
											echo '�� ������������� ������ ������ ������: "'.$build_name.'"?<br /><br /><br /><input type="button" value="��, � ���� ������� ��� ������" onclick="location.replace(\'town.php?option='.$option.'&part4&breaknow&break='.$house_id.'\')"><br /><br /><br /><input type="button" value="���, � �� ���� ������� ��� ������" onclick="location.replace(\'town.php?option='.$option.'&part4\')">';
										}
									}
									else
									{
										//������ ���
										$check = 1;
										$sel_dop = myquery("SELECT build_id FROM houses_users WHERE user_id=$user_id AND town_id=".$town." AND type=3");
										while (list($build_id)=mysql_fetch_array($sel_dop))
										{
											if (check_break_dop($user_id,$town,$build_id)!=1)
											{
												$check = 0;
											}
										}
										$sel_horses = myquery("SELECT * FROM game_items WHERE priznak=4 AND user_id=$user_id AND town=".$town." AND item_id IN (SELECT id FROM game_items_factsheet WHERE type<>13)");
										$est =  mysql_num_rows($sel_horses);
										if ($est>0)
										{
											echo '� ���� ���� � ��������� ��������. ������� ������ �� �� ���������!<br />';
											$check = 0;
										}
										if ($check==1)
										{
											echo '�� ������������� ������ ������ ���: "'.$build_name.'" ???<br /><br /><br /><input type="button" value="��, � ���� ������� ���� ���" onclick="location.replace(\'town.php?option='.$option.'&part4&breaknow&break='.$house_id.'\')"><br /><br /><br /><input type="button" value="���, � �� ���� ������� ���� ���" onclick="location.replace(\'town.php?option='.$option.'&part4\')">';
										}
									}
								} 
							}
							else
							{
								setLocation("town.php?option=".$option."&part4");
							}
						}
						elseif (isset($_GET['dom']))
						{
							include("../inc/gorod/house.hran_item.inc.php");
						}
						elseif (isset($_GET['inv']) and $buildnowhouse==0)
						{
							//�������������� � ����
//                            echo '�������� ����������!';
							setLocation("hero.php?house&option=".$option."");
							//echo '<iframe style="width:100%;height:460px;border:0px;" name="house_inv" src="hero.php?house&option='.$option.'"></iframe>';
						}
						elseif (isset($_GET['add']))
						{
							$build_id = (int)$_GET['add'];
							$check = myquery("SELECT * FROM houses_users WHERE user_id=$user_id AND town_id=$town AND build_id=$build_id");
							if ($check==false OR mysql_num_rows($check)==0)
							{
								setLocation("town.php?option=".$option."&part4");      
							}
							$house_add = mysql_fetch_array($check);
							if (($house_add['buildtime']>time()) OR ($house_add['buildtime']==0))
							{
								setLocation("town.php?option=".$option."&part4");      
							}
							$templ = mysql_fetch_array(myquery("SELECT * FROM houses_templates WHERE id=$build_id"));
							//������� � ������������
							//������ 
							//��������� - �������� �����. ���������� ���� - � ����������� �� ���� ����
							//������� - ��� �����, ��������� ����� �������� 5 � ����
							//����� - ��� ������� ����� � 11 ������� �������
							//����������� ��� - �������� ���� craft/inc/meating.inc.php
							//��������� - �������� ���� craft/inc/founder.inc.php
							//��������� - �������� ���� craft/inc/sawmill.inc.php
							if ($build_id>=9 AND $build_id<=12)
							{
								//��������� ��������
								include('../inc/gorod/house.hran_res.inc.php');
							}
							if ($build_id>=13 AND $build_id<=16)
							{
								//��������� ���������
								include('../inc/gorod/house.hran_elik.inc.php');
							}
							if ($build_id==5)
							{
								//������ �����
								include('../inc/gorod/house.kuzn.inc.php');
							}
							if ($build_id==6 OR $build_id==7 OR $build_id==8)
							{
								//������� (������/�����)
								include('../inc/gorod/house.horse.inc.php');
							}
							if ($build_id==18)
							{
								//���������� !!!
								include('../inc/gorod/house.founder.inc.php');
							}
							if ($build_id==17)
							{
								//���������
								include('../inc/gorod/house.sawmill.inc.php');
							}
							if ($build_id==19)
							{
								//��������� ����������  !!!
								include('../inc/gorod/house.oruj.inc.php');
							}
							if ($build_id==22)
							{
								//����������� ���
								include('../inc/gorod/house.meating.inc.php');
							}
							if ($build_id==21)
							{
								//������������ �����������
								include('../inc/gorod/house.alchemist.inc.php');
							}
						}
						elseif (isset($_GET['buildadd']))
						{
							if (isset($_GET['buildnow']))
							{
								$b = (int)$_GET['buildnow'];
								$now_build = mysqlresult(myquery("SELECT COUNT(*) FROM houses_users WHERE build_id<>$b AND user_id=$user_id AND town_id=".$town." AND (buildtime>".time()." OR buildtime=0) AND type>1"),0,0);
							}
							else
							{
								$now_build = mysqlresult(myquery("SELECT COUNT(*) FROM houses_users WHERE user_id=$user_id AND town_id=".$town." AND (buildtime>".time()." OR buildtime=0) AND type>1"),0,0);
							}
							if ($now_build>0)
							{
								setLocation("town.php?option=".$option."&part4"); 
							}
							if (isset($_GET['buildnow']))
							{
								$build_id = (int)$_GET['buildnow'];
								//�������� ������������� ������
								$start_build_add = 0;
								$check = myquery("SELECT * FROM houses_users WHERE user_id=$user_id AND town_id=$town AND build_id=$build_id");
								if ($check==false OR mysql_num_rows($check)==0)
								{
									//������ �������� �������������
									$house_add = mysql_fetch_array(myquery("SELECT * FROM houses_templates WHERE id=$build_id"));
									$can_build = true;
									$im_square = (int)mysqlresult(myquery("SELECT square FROM houses_users WHERE town_id=$town AND user_id=$user_id AND type=1"),0,0);
									$im_square_sell = (int)mysqlresult(myquery("SELECT SUM(sotka) FROM houses_market WHERE town_id=$town AND user_id=$user_id"),0,0);
									$im_square_houses = (int)mysqlresult(myquery("SELECT SUM(houses_templates.square) FROM houses_users,houses_templates WHERE houses_users.town_id=$town AND houses_users.user_id=$user_id AND houses_users.build_id=houses_templates.id AND houses_users.type>1"),0,0); 
									$im_square_free = $im_square - $im_square_sell - $im_square_houses;
									$sel_need = myquery("SELECT * FROM houses_templates_need WHERE build_id=".$house_add['id']."");
									$osvoboj_square = 0;
									if ($sel_need!=false AND mysql_num_rows($sel_need)>0)
									{
										$can_build = false;
										while ($need = mysql_fetch_array($sel_need))
										{
											$check = myquery("SELECT * FROM houses_users WHERE buildtime<=".time()." AND user_id=$user_id AND town_id=$town AND build_id IN (".$need['need'].")");
											if (mysql_num_rows($check)==sizeof(explode(',',$need['need'])))
											{
												$can_build = true;
												$sel_temp = myquery("SELECT * FROM houses_templates WHERE id IN (".$need['need'].")");
												while ($temp = mysql_fetch_array($sel_temp))
												{
													if ($temp['type']==2)
													{
														$osvoboj_square+=$temp['square'];
													}
												}
											}
										}
									}
									$im_square_free+=$osvoboj_square;
									if ($house_add['square']>$im_square_free)
									{
										$can_build = false;
									}
									$check_else = myquery("SELECT * FROM houses_users WHERE user_id=$user_id AND type=3 AND town_id=$town AND buildtime=0 AND build_id<>$build_id");
									if ($check_else!=false AND mysql_num_rows($check_else)>0)
									{
										$can_build = false;
									}
									if ($can_build)
									{    
										$sel_need = myquery("SELECT * FROM houses_templates_need WHERE build_id=".$house_add['id']."");
										if ($sel_need!=false AND mysql_num_rows($sel_need)>0)
										{
											$can_build = false;
											while ($need = mysql_fetch_array($sel_need))
											{
												$check = myquery("SELECT * FROM houses_users WHERE buildtime<=".time()." AND user_id=$user_id AND town_id=$town AND build_id IN (".$need['need'].")");
												if (mysql_num_rows($check)==sizeof(explode(',',$need['need'])))
												{
													$can_build = true;
													$sel_temp = myquery("SELECT * FROM houses_templates WHERE id IN (".$need['need'].")");
													while ($temp = mysql_fetch_array($sel_temp))
													{
														if ($temp['type']==2)
														{
															//������ ������������, ������� ��������� ��� ������������� ���� ������������
															myquery("DELETE FROM houses_users WHERE user_id=$user_id AND town_id=$town AND build_id=".$temp['id']."");
														}
													}
												}
											}
										}
										$start_build_add = 1;
										myquery("INSERT INTO houses_users (user_id,town_id,type,build_id) VALUES ($user_id,$town,3,$build_id)");
										$check = myquery("SELECT * FROM houses_users WHERE user_id=$user_id AND town_id=$town AND build_id=$build_id");
									}
									else
									{
										echo '<br />�� �� ������ ������ ������������� ������: '.$house_add['name'].'<br />';
									}
								}
								if (mysql_num_rows($check)>0 OR $start_build_add==1)
								{
									$house_add = mysql_fetch_array($check);
									$templ = mysql_fetch_array(myquery("SELECT * FROM houses_templates WHERE id=$build_id"));
									if (isset($_GET['startbuildadd']) AND $house_add['buildtime']==0)
									{
										if ($house_add['stone']>=$templ['stone'] AND $house_add['doska']>=$templ['doska'])
										{
											if ($char['GP']>=$templ['buildcost'])
											{
												$buildtime = time()+$templ['buildtime'];
												myquery("UPDATE houses_users SET buildtime=".$buildtime." WHERE build_id=$build_id AND user_id=$user_id AND town_id=$town");
												$house_add['buildtime'] = $buildtime;
												myquery("UPDATE game_users SET GP=GP-".$templ['buildcost'].",CW=CW-".($templ['buildcost']*money_weight)." WHERE user_id=$user_id");
												setGP($user_id,-$templ['buildcost'],34);
												setLocation("town.php?option=".$option."&part4");
											}
										}
									}
									if ($house_add['buildtime']==0)
									{										
										include('../inc/craft/craft.inc.php');
										//���������� � ������ �������
										$kol_doska = 0;
										$kol_block = 0;
										$put_doska = 0;
										$put_block = 0;
																				
										if (isset($_GET['add_doska']) AND (int)$_GET['add_doska']>0)
										{									
											$check_doska = myquery("SELECT col FROM craft_resource_user WHERE user_id=".$user_id." AND res_id=".$id_resource_doska."");
											if (mysql_num_rows($check_doska) > 0)
											{
												$kol_doska = mysqlresult($check_doska,0,0);
											}											
											$put_doska = max( min($kol_doska, (int)$_GET['add_doska'], ($templ['doska']-$house_add['doska'])), 0 );
											$house_add['doska']+=$put_doska;											
										}										
										if (isset($_GET['add_stone']) AND (int)$_GET['add_stone']>0) 
										{											
											$check_block = myquery("SELECT col FROM craft_resource_user WHERE user_id=".$user_id." AND res_id=".$id_resource_blok."");
											if (mysql_num_rows($check_block) > 0)
											{
												$kol_block = mysqlresult($check_block,0,0);
											}
											$put_block = max( min($kol_block, (int)$_GET['add_stone'], ($templ['stone']-$house_add['stone'])), 0);
											$house_add['stone']+=$put_block;
										}
										
										if ($put_block > 0 OR $put_doska > 0)
										{											
											myquery("UPDATE houses_users SET stone = stone + ".$put_block.", doska = doska + ".$put_doska." WHERE id=".$house_add['id']."");
										}								
										
										if ($put_block > 0)
										{
											$Res = new Res (0, $id_resource_blok);
											$Res->add_user(0, $user_id, -$put_block);
										}
										if ($put_doska > 0)
										{
											$Res = new Res (0, $id_resource_doska);
											$Res->add_user(0, $user_id, -$put_doska);
										}
										echo '<center>�� ������� �������������� ������ - "'.$templ['name'].'"<br />';
										echo '����� ��� ��������� ������������� � ��������� ���� ������� '.$templ['stone'].' '.pluralForm($templ['stone'],'�������� ����','�������� �����','�������� ������').' � '.$templ['doska'].' '.pluralForm($templ['doska'],'�����','�����','�����').'.<br />';
										echo '������ �� '.echo_sex('������','�������').' � ������������� '.$house_add['stone'].' '.pluralForm($house_add['stone'],'�������� ����','�������� �����','�������� ������').' � '.$house_add['doska'].' '.pluralForm($house_add['doska'],'�����','�����','�����').'.<br /><br /><br />';
										if ($house_add['stone']>=$templ['stone'] AND $house_add['doska']>=$templ['doska'])
										{
											//�������� ������� ����������
											echo '<br />�������� ������� ����������. ����� �������� ������������� �������������� ���������. ������������� ������ '.get_day_format($templ['buildtime']).'<br />�� ������������� ���������� ��������� '.$templ['buildcost'].' ���.<br /><br /><br /><input type="button" value="������ ������������� ���� ���������" onclick="location.replace(\'town.php?option='.$option.'&part4&buildadd&buildnow='.$build_id.'&startbuildadd\')"><br /><br />';
										}
										else
										{
											$selres = myquery("SELECT col,res_id FROM craft_resource_user WHERE (res_id=".$id_resource_doska." OR res_id=".$id_resource_blok.") AND user_id=$user_id AND col>0");
											while ($res = mysql_fetch_array($selres))
											{
												if ($res['res_id']==$id_resource_blok and $house_add['stone']<$templ['stone'])
												{
													echo '<br />������� � ������������� ���������: <input type="text" size="5" maxsize="3" id="res_stone" name="stoneres" value="'.min(($templ['stone']-$house_add['stone']), $res['col']).'"> �� '.$res['col'].' ������ �������� ������&nbsp;&nbsp;&nbsp;<input type="button" value="������� �������� �����" onclick="location.replace(\'town.php?option='.$option.'&part4&buildadd&buildnow='.$build_id.'&add_stone=\'+document.getElementById(\'res_stone\').value+\'\')"><br />';
												}
												if ($res['res_id']==$id_resource_doska and $house_add['doska']<$templ['doska'])
												{
													echo '<br />������� � ������������� ���������: <input type="text" size="5" maxsize="3" id="res_doska" name="doskares" value="'.min(($templ['doska']-$house_add['doska']), $res['col']).'"> �� '.$res['col'].' ������ �����&nbsp;&nbsp;&nbsp;<input type="button" value="������� �����" onclick="location.replace(\'town.php?option='.$option.'&part4&buildadd&buildnow='.$build_id.'&add_doska=\'+document.getElementById(\'res_doska\').value+\'\')"><br />';
												}
											}
										}   
									}
								}
							}
							else
							{
								echo'<SCRIPT language=javascript src="../js/info.js"></SCRIPT>
								<DIV id=hint style="Z-INDEX: 0; LEFT: 0px; VISIBILITY: hidden; POSITION: absolute; TOP: 0px"></DIV>';
								if (isset($_GET['upgradeadd']))
								{
									$sel_add = myquery("SELECT * FROM houses_templates WHERE type=2 AND instead = '".$_GET['upgradeadd']."' ORDER BY buildcost ASC");
								}
								else
								{
									
									$check_groups=myquery("SELECT DISTINCT ht2.build_group FROM houses_templates ht2 JOIN houses_users hu3 ON hu3.build_id=ht2.id WHERE hu3.user_id='".$user_id."' and hu3.town_id='".$town."' AND ht2.build_group<>0");
									if (mysql_num_rows($check_groups)>0)
									{
										$i=0;
										$groups="AND ht.build_group NOT IN (";
										while (list($gr_id)=mysql_fetch_array($check_groups))
										{
											if ($i>0) { $groups=$groups.', '.$gr_id; }
											else { $groups=$groups.' '.$gr_id; }
											$i++;
										}
										$groups=$groups.' )';
									}
									else
									{
										$groups=" ";
									}									
									$sel_add = myquery("SELECT ht.* FROM houses_templates ht
									LEFT JOIN houses_users hu1 ON ( ht.id = hu1.build_id AND hu1.user_id ='".$user_id."' AND hu1.town_id ='".$town."' ) 
									LEFT JOIN houses_users hu2 ON ( ht.instead = hu2.build_id AND hu2.user_id ='".$user_id."' AND hu2.town_id ='".$town."' AND hu2.build_id<>0) 
									WHERE ht.type =2 AND hu1.id IS NULL AND (hu2.id IS NOT NULL OR (ht.instead =0 ".$groups." ))
									ORDER BY ht.id ASC");
								}
								echo '<b>�� ������ ��������� �� ����� ����� ��������� �������������� ���������:</b><br />';
								echo '<table cellspacing=1 cellpadding=1 style="border:1px solid white" width="100%">';
								echo '<tr><td class="table_h2">��������</td><td class="table_h2">������</td><td class="table_h2">��������� �������������</td><td class="table_h2">����� �������������</td><td class="table_h2">���������� �������</td></tr>';
								while ($house_add = mysql_fetch_array($sel_add))
								{
									$can_build = true;
									$im_square = (int)mysqlresult(myquery("SELECT square FROM houses_users WHERE town_id=$town AND user_id=$user_id AND type=1"),0,0);
									$im_square_sell = (int)mysqlresult(myquery("SELECT SUM(sotka) FROM houses_market WHERE town_id=$town AND user_id=$user_id"),0,0);
									$im_square_houses = (int)mysqlresult(myquery("SELECT SUM(houses_templates.square) FROM houses_users,houses_templates WHERE houses_users.town_id=$town AND houses_users.user_id=$user_id AND houses_users.build_id=houses_templates.id AND houses_users.type>1"),0,0); 
									$im_square_free = $im_square - $im_square_sell - $im_square_houses;
									$sel_need = myquery("SELECT * FROM houses_templates_need WHERE build_id=".$house_add['id']."");
									$osvoboj_square = 0;
									$need_templates = '';
									if ($sel_need!=false AND mysql_num_rows($sel_need)>0)
									{
										$can_build = false;
										while ($need = mysql_fetch_array($sel_need))
										{
											$sel_temp = myquery("SELECT * FROM houses_templates WHERE id IN (".$need['need'].")");
											while ($temp = mysql_fetch_array($sel_temp))
											{
												$need_templates.=$temp['name'].' + ';
											}
											$need_templates = substr($need_templates,0,strlen($need_templates)-3).'.';
											$need_templates.='<br /><i>���</i><br />';
											$check = myquery("SELECT * FROM houses_users WHERE buildtime<=".time()." AND user_id=$user_id AND town_id=$town AND build_id IN (".$need['need'].")");
											if (mysql_num_rows($check)==sizeof(explode(',',$need['need'])))
											{
												$can_build = true;
												$sel_temp = myquery("SELECT * FROM houses_templates WHERE id IN (".$need['need'].")");
												while ($temp = mysql_fetch_array($sel_temp))
												{
													if ($temp['type']==2)
													{
														$osvoboj_square+=$temp['square'];
													}
												}
											}
										}
										$need_templates = substr($need_templates,0,strlen($need_templates)-16);
									}
									$im_square_free+=$osvoboj_square;
									if ($house_add['square']>$im_square_free)
									{
										$can_build = false;
									}
									$check_else = myquery("SELECT * FROM houses_users WHERE user_id=$user_id AND type=3 AND town_id=$town AND buildtime=0 AND build_id<>".$house_add['id']."");
									if ($check_else!=false AND mysql_num_rows($check_else)>0)
									{
										$can_build = false;
									}
									echo '<tr><td>';
									if ($can_build)
									{
										echo '<input type="button" value="���������" onclick="location.replace(\'town.php?option='.$option.'&part4&buildadd&buildnow='.$house_add['id'].'\')">';
									}
									else
									{
										echo '&nbsp;';
									}
									echo '</td><td>';
									if ($need_templates=='')
									{
										echo $house_add['name'];
									}
									else
									{
										?><span onmousemove=movehint(event) onmouseover="showhint('<center><font color=ff0000><b>��� ������������� ��������� ������� ����������� ������:</b></font>','<font color=#000080><?php echo $need_templates; ?></font>',0,1,event)" onmouseout="showhint('','',0,0,event)"><?php echo $house_add['name'].'</span>';
									}
									echo '</td><td>'.$house_add['buildcost'].'</td><td>'.get_day_format($house_add['buildtime']).'</td><td>'.$house_add['square'].' '.pluralForm($house_add['square'],'�����','�����','�����').'</td><td>';
									echo '</td></tr>';
								}
								echo '</table><br />';
							}
						}
						// ������� �����������
						elseif (isset($_GET['upgradeadd']))
						{
							echo'<SCRIPT language=javascript src="../js/info.js"></SCRIPT>
							<DIV id=hint style="Z-INDEX: 0; LEFT: 0px; VISIBILITY: hidden; POSITION: absolute; TOP: 0px"></DIV>';							
							$sel_add = myquery("SELECT * FROM houses_users hu JOIN houses_templates ht ON hu.build_id=ht.instead WHERE hu.user_id='".$user_id."' AND hu.type=3 AND ht.type=2 AND hu.town_id='".$town."' AND hu.buildtime>0 AND hu.id='".$_GET['upgradeadd']."' ORDER BY buildcost ASC");
							echo '�� ������ �������� ���� �������������� ���������. ��� �� ������:<br />';
							echo '<table cellspacing=1 cellpadding=1 style="border:1px solid white" width="100%">';
							echo '<tr><td class="table_h2">&nbsp;</td><td class="table_h2">������</td><td class="table_h2">��������� �������������</td><td class="table_h2">����� �������������</td><td class="table_h2">���������� �������</td></tr>';
							while ($house_add = mysql_fetch_array($sel_add))
							{
								$can_build = true;
								$im_square = (int)mysqlresult(myquery("SELECT square FROM houses_users WHERE town_id=$town AND user_id=$user_id AND type=1"),0,0);
								$im_square_sell = (int)mysqlresult(myquery("SELECT SUM(sotka) FROM houses_market WHERE town_id=$town AND user_id=$user_id"),0,0);
								$im_square_houses = (int)mysqlresult(myquery("SELECT SUM(houses_templates.square) FROM houses_users,houses_templates WHERE houses_users.town_id=$town AND houses_users.user_id=$user_id AND houses_users.build_id=houses_templates.id AND houses_users.type>1"),0,0); 
								$im_square_free = $im_square - $im_square_sell - $im_square_houses;
								$sel_need = myquery("SELECT * FROM houses_templates_need WHERE build_id=".$house_add['id']."");
								$osvoboj_square = 0;
								$need_templates = '';
								if ($sel_need!=false AND mysql_num_rows($sel_need)>0)
								{
									$can_build = false;
									while ($need = mysql_fetch_array($sel_need))
									{
										$sel_temp = myquery("SELECT * FROM houses_templates WHERE id IN (".$need['need'].")");
										while ($temp = mysql_fetch_array($sel_temp))
										{
											$need_templates.=$temp['name'].' + ';
										}
										$need_templates = substr($need_templates,0,strlen($need_templates)-3).'.';
										$need_templates.='<br /><i>���</i><br />';
										$check = myquery("SELECT * FROM houses_users WHERE buildtime<=".time()." AND user_id=$user_id AND town_id=$town AND build_id IN (".$need['need'].")");
										if (mysql_num_rows($check)==sizeof(explode(',',$need['need'])))
										{
											$can_build = true;
											$sel_temp = myquery("SELECT * FROM houses_templates WHERE id IN (".$need['need'].")");
											while ($temp = mysql_fetch_array($sel_temp))
											{
												if ($temp['type']==2)
												{
													$osvoboj_square+=$temp['square'];
												}
											}
										}
									}
									$need_templates = substr($need_templates,0,strlen($need_templates)-16);
								}
								$im_square_free+=$osvoboj_square;
								if ($house_add['square']>$im_square_free)
								{
									$can_build = false;
								}
								$check_else = myquery("SELECT * FROM houses_users WHERE user_id=$user_id AND type=3 AND town_id=$town AND buildtime=0 AND build_id<>".$house_add['id']."");
								if ($check_else!=false AND mysql_num_rows($check_else)>0)
								{
									$can_build = false;
								}
								echo '<tr><td>';
								if ($can_build)
								{
									echo '<input type="button" value="���������" onclick="location.replace(\'town.php?option='.$option.'&part4&buildadd&buildnow='.$house_add['id'].'\')">';
									unset ($_GET['upgradeadd']);
								}
								else
								{
									echo '&nbsp;';
								}
								echo '</td><td>';
								if ($need_templates=='')
								{
									echo $house_add['name'];
								}
								else
								{
									?><span onmousemove=movehint(event) onmouseover="showhint('<center><font color=ff0000><b>��� ������������� ��������� ������� ����������� ������:</b></font>','<font color=#000080><?php echo $need_templates; ?></font>',0,1,event)" onmouseout="showhint('','',0,0,event)"><?php echo $house_add['name'].'</span>';
								}
								echo '</td><td>'.$house_add['buildcost'].'</td><td>'.get_day_format($house_add['buildtime']).'</td><td>'.$house_add['square'].' '.pluralForm($house_add['square'],'�����','�����','�����').'</td><td>';
								echo '</td></tr>';
							}
							echo '</table><br />';						
						}
						// ��������� �����������
						elseif (isset($_GET['downgrade']))
						{
							$house_id = (int)$_GET['downgrade'];
							$check = myquery("SELECT ht.id, ht.instead, ht.name FROM houses_users hu JOIN houses_templates ht ON hu.build_id=ht.id WHERE user_id='".$user_id."' AND town_id='".$town."' AND hu.id='".$house_id."'");
							if ($check!=false AND mysql_num_rows($check)==1)
							{
								list($build_id,$instead,$build_name)=mysql_fetch_array($check);								
								if (isset($_GET['downgradenow']))
								{
									list($doska,$stone)=mysql_fetch_array(myquery("SELECT stone, doska FROM houses_templates WHERE id='".$instead."'"));
									myquery("UPDATE houses_users SET build_id='".$instead."',buildtime=UNIX_TIMESTAMP()-1,stone='".$stone."',doska='".$doska."' WHERE id='".$house_id."'");									
									setLocation("town.php?option=".$option."&part4"); 
									break;
								}
								else
								{									
									echo '�� ������������� ������ ��������� ������: "'.$build_name.'"?<br /><br />
										<br /><input type="button" value="��, � ���� ��������� ��� ������" onclick="location.replace(\'town.php?option='.$option.'&part4&downgradenow&downgrade='.$house_id.'\')"><br /><br />
										<br /><input type="button" value="���, � �� ���� ��������� ��� ������" onclick="location.replace(\'town.php?option='.$option.'&part4\')">';
									/*
									$check2=myquery("SELECT need FROM houses_users hu JOIN houses_templates_need htn ON hu.build_id=htn.build_id WHERE hu.user_id='".$user_id."' AND htn.need like '%".$build_id."%'  AND htn.need not like '%".$instead."%' ");
									if (mysql_num_rows($check2)==0)
									{
										echo '�� ������������� ������ ��������� ������: "'.$build_name.'"?<br /><br />
										<br /><input type="button" value="��, � ���� ��������� ��� ������" onclick="location.replace(\'town.php?option='.$option.'&part4&downgradenow&downgrade='.$house_id.'\')"><br /><br />
										<br /><input type="button" value="���, � �� ���� ��������� ��� ������" onclick="location.replace(\'town.php?option='.$option.'&part4\')">';
									}
									else
									{
										echo '�� �� ������ ����������� ������ ������, �.�. ��� ����� ������ ����������!';
									}		*/							
								} 
							}
							else
							{
								setLocation("town.php?option=".$option."&part4");
							}
						}
						elseif (isset($_GET['remont']))
						{
							include('../inc/craft/craft.inc.php');						
							if (isset($_POST['save']))
							{
								$weight = 0;
								$kol_doska = 0;
								$kol_block = 0;
								$put_doska = 0;
								$put_block = 0;
								
								if (isset($_POST['doska_repair']) AND (int)$_POST['doska_repair']>0)
								{									
									$check_doska = myquery("SELECT col FROM craft_resource_user WHERE user_id=".$user_id." AND res_id=".$id_resource_doska."");
									if (mysql_num_rows($check_doska) > 0)
									{
										$kol_doska = mysqlresult($check_doska,0,0);
									}
									$put_doska = min($kol_doska, (int)$_POST['doska_repair'] );
								}
								
								if (isset($_POST['stone_repair']) AND (int)$_POST['stone_repair']>0) 
								{
									$check_block = myquery("SELECT col FROM craft_resource_user WHERE user_id=".$user_id." AND res_id=".$id_resource_blok."");
									if (mysql_num_rows($check_block) > 0)
									{
										$kol_block = mysqlresult($check_block,0,0);
									}
									$put_block = min($kol_block, (int)$_POST['stone_repair'] );
								}
								
								$get_data = myquery("SELECT stone_repair stone, doska_repair doska FROM houses_users WHERE user_id = ".$user_id." AND id=".((int)$_GET['remont'])."");
								list($need_block, $need_doska) = mysql_fetch_array($get_data);
								
								$put_block = min ($put_block, $need_block);
								$put_doska = min ($put_doska, $need_doska);
								if ($put_block > 0 OR $put_doska > 0)
								{
									myquery("UPDATE houses_users SET stone_repair = stone_repair - ".$put_block.", doska_repair = doska_repair - ".$put_doska." WHERE id=".((int)$_GET['remont'])."");
								}								
								
								if ($put_block > 0)
								{
									$Res = new Res (0, $id_resource_blok);
									$Res->add_user(0, $user_id, -$put_block);
								}
								if ($put_doska > 0)
								{
									$Res = new Res (0, $id_resource_doska);
									$Res->add_user(0, $user_id, -$put_doska);
								}															
								setLocation("town.php?option=".$option."&part4");
							}
							else
							{
								echo '<br /><br /><center>������</center><br /><br />';  
								$selho = myquery("SELECT houses_users.*,houses_templates.name FROM houses_users,houses_templates WHERE houses_users.user_id=$user_id AND houses_users.town_id=".$town."  AND houses_users.id=".((int)$_GET['remont'])." AND houses_templates.id=houses_users.build_id AND (houses_users.doska_repair>0 OR houses_users.stone_repair>0)");
								if (mysql_num_rows($selho))
								{
									$ho = mysql_fetch_array($selho);
									echo '��� ������� <b>'.$ho['name'].'</b> ����������:<br /><br />';
									echo '<form action="town.php?option='.$option.'&part4&remont='.$_GET['remont'].'" method="post">';
									echo '<table  cellspacing=3>';
									if ($ho['doska_repair']>0)
									{
										$kol_res = mysqlresult(myquery("SELECT col FROM craft_resource_user WHERE user_id=$user_id AND res_id=$id_resource_doska"),0,0);
										echo '<tr><td rowspan=3>�����</td>';
										echo '<td>��������� ��� �������</td>';
										echo '<td align="center">'.$ho['doska_repair'].'</td></tr>';    
										echo '<tr><td>������� � �����</td>';
										echo '<td align="center">'.$kol_res.'</td></tr>';    
										echo '<tr><td>������� � ������</td>';
										echo '<td align="center"><input type="text" size="8" name="doska_repair" value="'.min($kol_res,$ho['doska_repair']).'"></td></tr><tr><td colspan="4" style="height:5px;background-color:#585858;border:1px groove;">';    
									}
									if ($ho['stone_repair']>0)
									{
										$kol_res = mysqlresult(myquery("SELECT col FROM craft_resource_user WHERE user_id=$user_id AND res_id=$id_resource_blok"),0,0);
										echo '<tr><td rowspan=3>�������� �����</td>';
										echo '<td>��������� ��� �������</td>';
										echo '<td align="center">'.$ho['stone_repair'].'</td></tr>';    
										echo '<tr><td>������� � �����</td>';
										echo '<td align="center">'.$kol_res.'</td></tr>';    
										echo '<tr><td>������� � ������</td>';
										echo '<td align="center"><input type="text" size="8" name="stone_repair" value="'.min($kol_res,$ho['stone_repair']).'"></td></tr>';    
									}
									echo '</table>';
									echo '<br /><br /><input type="submit" name="save" value="���������������"></form>';
								}
								else
								{
									setLocation("town.php?option=".$option."&part4");
								}
							}
						}
						else
						{							
							if (isset($_GET['repairall']))
							{								
								include('../inc/craft/craft.inc.php');
								$need_blok = 0;
								$need_doska = 0;
								$put_blok = 0;
								$put_doska = 0;
								$check_doska = myquery("SELECT col FROM craft_resource_user WHERE user_id=".$user_id." AND res_id=".$id_resource_doska."");
								if (mysql_num_rows($check_doska) == 0)
								{
									$kol_doska = 0;
								}
								else
								{
									$kol_doska = mysqlresult($check_doska,0,0);
								}
								$check_blok = myquery("SELECT col FROM craft_resource_user WHERE user_id=".$user_id." AND res_id=".$id_resource_blok."");
								if (mysql_num_rows($check_blok) == 0)
								{
									$kol_blok = 0;
								}
								else
								{
									$kol_blok = mysqlresult($check_blok,0,0);
								}
								
								$get_data = myquery("SELECT id, stone_repair stone, doska_repair doska, (CASE WHEN town_id = ".$town." THEN 1 ELSE 0 END) as town FROM houses_users WHERE user_id = ".$user_id." AND (stone_repair > 0 OR doska_repair > 0) ORDER BY town desc");
								while ($need = mysql_fetch_array($get_data))
								{
									$put_now_blok = 0;
									$put_now_doska = 0;
									$need_blok+=$need['stone'];
									$need_doska+=$need['doska'];
									if ($kol_blok > 0 and $need['stone'] > 0)
									{
										$put_now_blok = min ($kol_blok, $need['stone']);
										$put_blok += $put_now_blok;
										$kol_blok -= $put_now_blok;
									}
									if ($kol_doska > 0 and $need['doska'] > 0)
									{
										$put_now_doska = min ($kol_doska, $need['doska']);
										$put_doska += $put_now_doska;
										$kol_doska -= $put_now_doska;
									}		
									if ($put_now_blok > 0 OR $put_now_doska > 0)
									{
										myquery("UPDATE houses_users SET stone_repair = stone_repair - ".$put_now_blok.", doska_repair = doska_repair - ".$put_now_doska." WHERE id = ".$need['id']."");
									}
								}
								
								if ($put_blok > 0)
								{
									$Res = new Res (0, $id_resource_blok);
									$Res->add_user(0, $user_id, -$put_blok);
								}
								if ($put_doska > 0)
								{
									$Res = new Res (0, $id_resource_doska);
									$Res->add_user(0, $user_id, -$put_doska);
								}
								$mes = '<h3><font color="yellow">
								������ ���������!<br>
								����������� ��� �������: ����� - '.$need_doska.' ��., ������ - '.$need_blok.' ��.<br>
								������� � ������: ����� - '.$put_doska.' ��., ������ - '.$put_blok.' ��.<br>
								</h3></font>';
								echo '<b>'.$mes.'</b>';
							}
							
							if ($buildnowhouse==0)
							{
								echo '<strong>�� ���������� � ����� ��������������.<br />';
								$im_square = (int)mysqlresult(myquery("SELECT square FROM houses_users WHERE town_id=$town AND user_id=$user_id AND type=1"),0,0);   // ���-�� ����� ����� � ������ �����
								$im_square_sell = (int)mysqlresult(myquery("SELECT SUM(sotka) FROM houses_market WHERE town_id=$town AND user_id=$user_id"),0,0);  // ���-�� ����� ������ �� ��������� �����
								$im_square_houses = (int)mysqlresult(myquery("SELECT SUM(houses_templates.square) FROM houses_users,houses_templates WHERE houses_users.town_id=$town AND houses_users.user_id=$user_id AND houses_users.build_id=houses_templates.id AND houses_users.type>1"),0,0);  // ���-�� ����� ������ ��� �����������
								$im_square_free = $im_square - $im_square_sell - $im_square_houses; //���-�� ��������� ����� ������
								$check_house=myquery("SELECT hu.*, ht.name, ht.instead, ht.buildtime AS add_buildtime, ht.square AS add_square, (CASE WHEN ht2.id IS NULL THEN '0' ELSE '1' END) as can_upgr FROM houses_users hu JOIN houses_templates ht ON hu.build_id=ht.id LEFT JOIN houses_templates ht2 ON ht.id=ht2.instead WHERE hu.user_id='".$user_id."' AND hu.town_id='".$town."' AND hu.type in (2,3) ORDER BY hu.type, hu.buildtime");
								echo '������ � ���� �� �������� '.$im_square.' '.pluralForm($im_square,'�����','�����','�����').' �����, �� ���: <br />- ��������� = '.$im_square_free.' '.pluralForm($im_square_free,'�����','�����','�����').' �����;<br />- ��� ����������� = '.$im_square_houses.' '.pluralForm($im_square_houses,'�����','�����','�����').' �����;<br />- ���������� �� ������� = '.$im_square_sell.' '.pluralForm($im_square_sell,'�����','�����','�����').' �����;<br />';
								if (mysql_num_rows($check_house)>0)
								{
									$now_build = mysqlresult(myquery("SELECT COUNT(*) FROM houses_users WHERE user_id=".$user_id." AND town_id=".$town." AND (buildtime>".time()." OR buildtime=0) AND type>1"),0,0);
									if ($now_build>0)
									{
										echo '<br />� ������ ������ ������� ������������� ������<br />';
									}
									else
									{
										echo '<br /><a href=?option='.$option.'&part4&buildadd>������������� �������������� ������</a><br>';
									}
									$need_repair = 0;
									echo '</strong><br /><i>����� ������� ��������� ���������:</i><br /><br />';
									echo '<table border="1"><tr valign="top" align="center">
									 <td width="300"><b>���������</b></td>
									 <td width="100"><b>������ �����</b></td>
									 <td width="450"><b>��������</b></td>								 
									 ';
									while ($hous=mysql_fetch_array($check_house))
									{
										$primer=0;

										echo '<tr>';
										if ($hous['type']==2)
										{
											echo '<td><a href="?option='.$option.'&part4&dom">'.$hous['name'].'</a></td>';
											$primer=1;
										}
										elseif ($hous['buildtime']==0)
										{
											echo '<td><a href="town.php?option='.$option.'&part4&buildadd&buildnow='.$hous['build_id'].'">'.$hous['name'].'</a></td>';
										}
										elseif ($hous['buildtime']<time())
										{
											echo '<td><a href="town.php?option='.$option.'&part4&add='.$hous['build_id'].'">'.$hous['name'].'</a></td>';
										}
										else
										{
											echo '<td>'.$hous['name'].'</td>';
										}
										echo '<td align="center">'.$hous['add_square'].'</td>';
										if ($hous['buildtime']==0)
										{
											echo '<td>��� �� ������ ������������� ������<br>
												  <a href="town.php?option='.$option.'&part4&break='.$hous['id'].'">�������� �������������</a></td>';
										}
										elseif ($hous['buildtime']<time())
										{
											echo '<td>';
											if ($hous['can_upgr'] == '1') 
											{
												if ($hous['type']==2)
												{
													echo '|&nbsp;&nbsp;<a href="town.php?option='.$option.'&part4&upgradehouse='.$hous['build_id'].'">��������</a>&nbsp;&nbsp;';
												}
												else
												{
													echo '|&nbsp;&nbsp;<a href="town.php?option='.$option.'&part4&upgradeadd='.$hous['id'].'">��������</a>&nbsp;&nbsp;';
												}
											}
											if ($hous['instead'] <> '0') 
											{
												echo '|&nbsp;&nbsp;<a href="town.php?option='.$option.'&part4&downgrade='.$hous['id'].'">���������</a>&nbsp;&nbsp;';
											}
											if ($hous['stone_repair']>0 OR $hous['doska_repair']>0)
											{
												$need_repair = 1;
												echo '|&nbsp;&nbsp;<a href="town.php?option='.$option.'&part4&remont='.$hous['id'].'">������</a>&nbsp;&nbsp;';
											}
											echo '|&nbsp;&nbsp;<a href="town.php?option='.$option.'&part4&break='.$hous['id'].'">������ ���������</a>';
											echo '</td>';
										}
										else
										{
											$allbuildtime = $hous['add_buildtime'];
											$procent = 0;
											if ($allbuildtime>0)
											{
												$procent = 100-round((($hous['buildtime']-time())/$allbuildtime),2)*100;
											}
											$alltime = $hous['buildtime']-time();
											$rest_time = get_day_format($alltime);
											echo '<td>��������� ��������� �� ('.$procent.'%). ���������� ����� '.$rest_time.'<br /><a href="town.php?option='.$option.'&part4&break='.$hous['id'].'">�������� �������������</a></td>';
										}
										echo '</tr>';
										if ($primer==1)
										{
											echo '<tr>';
											echo '<td><a href="?option='.$option.'&part4&inv&house">����������� �������</a></td>';
											echo '<td align="center">0.00</td><td>&nbsp;</td></tr>';
										}										
									}	
									echo '</table>';
								}
								if ($need_repair == 1)
								{
									echo '<br><a href=?option='.$option.'&part4&repairall>������������� ��</a>';
								}
							}						
						}
					}
				}
			}
			if (isset($_GET['nalog']))
			{
				if (isset($_GET['pay']))
				{
					$sel = myquery("SELECT * FROM houses_nalog WHERE user_id=$user_id AND id=".$_GET['pay']."");
					if ($sel!=false AND mysql_num_rows($sel)>0)
					{
						$pay = mysql_fetch_array($sel);
						if ($pay['nalog']!=$pay['pay'])
						{
							$ost_nalog = $pay['nalog']-$pay['pay'];
							if ($char['GP']>=$ost_nalog)
							{
								myquery("UPDATE game_users SET GP=GP-".$ost_nalog.",CW=CW-".($ost_nalog*money_weight)." WHERE user_id=$user_id");
								setGP($user_id,-$ost_nalog,38);
								myquery("UPDATE houses_nalog SET pay=pay+".$ost_nalog.",pay_time=".time()." WHERE id=".$_GET['pay']."");
								echo '<br />������ �������.<br />';
							}
							else
							{
								echo '<br />� ���� �� ������� ����� ��� ������ ������!<br />';
							}   
						}
					}   
				}
				echo '<br /><br />������� ���������� � ������ ������������ ��������:<br /><br />';
				echo '<table cellspacing="0" cellpadding="0" width="100%">
				<tr><td colspan=2 align="center" class="table_h1">���������</td><td align="center" colspan=3 class="table_h1">��������</td><td style="border-right: 1px groove gold;border-top: 1px groove gold;">&nbsp;</td></tr>
				<tr><td align="center" class="table_h2" style="border-left: 1px groove gold;">����</td><td align="center" class="table_h2" style="border-right: 1px groove gold;" align="center">�����</td><td align="center" class="table_h2" style="border-left: 1px groove gold;">����</td><td align="center" class="table_h2" align="center">�����</td><td class="table_h2" style="width:100px;border-right: 1px groove gold;">� ������</td><td class="table_h2" style="width:100px;border-right: 1px groove gold;">&nbsp;</td></tr>';
				$sel = myquery("SELECT * FROM houses_nalog WHERE user_id=$user_id ORDER BY nalog_time DESC");
				while ($nalog = mysql_fetch_array($sel))
				{
					echo '<tr><td align="center" class="table_h2" style="border-left: 1px groove gold;">'.(($nalog['nalog_time']==0) ? "&nbsp;" : date("d.m.Y",$nalog['nalog_time'])).'</td><td align="center" class="table_h2" style="border-right: 1px groove gold;">'.round($nalog['nalog'],2).'</td><td align="center" class="table_h2" style="border-left: 1px groove gold;">'.(($nalog['pay_time']==0) ? "&nbsp;" : date("d.m.Y",$nalog['pay_time'])).'</td><td align="center" class="table_h2">'.round($nalog['pay'],2).'</td><td align="center" class="table_h2">'.round($nalog['nalog']-$nalog['pay'],2).'</td><td class="table_h2" style="border-right: 1px groove gold;">';
					if (round($nalog['nalog'],2)!=round($nalog['pay'],2))
						echo '<input type="button" value="��������" onclick="location.replace(\'town.php?option='.$option.'&part4&nalog&pay='.$nalog['id'].'\')">';
					else
						echo '&nbsp;';
					echo '</td></tr>';    
				}
				echo '</table>';
			}
			
		}
		echo '</center>';
	}
	else //��������� �����������
	{
		QuoteTable('open',"100%");
		echo '<center><ul>
		<li><a href="town.php?option='.$option.'&part1">��������� ����� ������������</a></li><br />
		<li><a href="town.php?option='.$option.'&part2">��������� ����� ������������</a></li><br />
		<li><a href="town.php?option='.$option.'&part3">�����</a></li><br />
		<li><a href="town.php?option='.$option.'&part4">������ �������������</a></li></ul>';
		QuoteTable('close');
	}
	
	OpenTable('title',"100%");
	echo '<center><br /><a href="town.php?option='.$option.'&part4">� ������ �������������</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="town.php?option='.$option.'">� ����� �������</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="town.php">� �����</a><br />&nbsp';
	OpenTable('close');
	
	echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
}

if (function_exists("save_debug")) save_debug(); 

?>