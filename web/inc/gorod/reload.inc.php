<?

if (function_exists("start_debug")) start_debug(); 


if (domain_name == 'testing.rpg.su') {$price=0; $price_har=0;}
if ($town!=0)
{
  if (isset($_GET['p']))
    $p = $_GET['p'];

	$userban=myquery("select * from game_ban where user_id='$user_id' and type=2 and time>'".time()."'");
	if (mysql_num_rows($userban))
	{
		$userr = mysql_fetch_array($userban);
		$min = ceil(($userr['time']-time())/60);
		echo '<center><br><br><br>�� ���� �������� ��������� �� '.$min.' �����! ���� ��������� ������������ �������!';
		{if (function_exists("save_debug")) save_debug(); exit;}
	}
	$img='http://'.img_domain.'/race_table/human/table';
	echo'<table width=100% border="0" cellspacing="0" cellpadding="0"><tr><td width="1" height="1"><img src="'.$img.'_lt.gif"></td><td background="'.$img.'_mt.gif"></td><td width="1" height="1"><img src="'.$img.'_rt.gif"></td></tr>
	<tr><td background="'.$img.'_lm.gif"></td><td style="text-align:center" background="'.$img.'_mm.gif" valign="top" width="100%" height="100%">';

	//list($count_reload,$count_reload_har) = mysql_fetch_array(myquery("SELECT count_reload,count_reload_har FROM game_users_data WHERE user_id=$user_id"));
    
	//��������� ���� �� ������ ������
	$da=mktime();	
	$no_cost=0;
	if ($no_cost==1)
	{
		$cost = 0;
		$cost_har = 0;
		$cost_prof = 0;		
	}
	else
	{
		$price=20*$char['clevel'];
		$price_har=200*$char['clevel'];
		$count_reload =  0;
		$count_reload_har = 0;
		$cost = $price*($count_reload+1);
		$cost_har = $price_har*($count_reload_har+1);
		$cost_prof=50*$char['clevel'];
	}
    /*
	$da = getdate();
	if ($da['mon']==7 AND $da['mday']==15)
	{
		$cost = 0;
	}
	if ($da['mon']==1 AND $da['mday']>=1 AND $da['mday']<=7)
	{
		$cost = 0;
	}
	if ($da['mon']==12 AND $da['mday']==31)
	{
		$cost = 0;
	}
	*/
	
	//���������� �������������
	if(isset($_GET['do']) AND $_GET['do']=='skill_down' AND isset($_GET['p']))
	{
		echo '<center><br/>';
		QuoteTable('open');
		$check=myquery("SELECT level FROM game_users_skills WHERE user_id=".$user_id." AND skill_id=".$p."");
		if (mysql_num_rows($check)==0)
		{
			echo'<br/><center><font face=verdana color=ff0000 size=2>� ��� ��� ������� ������!</font><meta http-equiv="refresh" content="1;url=town.php?option='.$option.'"><br /><br />';
		}
		else
		{
			list($level)=mysql_fetch_array($check);
			if ( ($char['reinc']==0 AND $p==25 AND $level==1) OR $p==21 OR ($char['reinc']<2 AND $p==32))
			{
				echo'<br/><center><font face=verdana color=ff0000 size=2>�� �� ������ ������� ���� �����!</font><meta http-equiv="refresh" content="1;url=town.php?option='.$option.'"><br /><br />';
			}
			else
			{
				if (isset($_GET['agree']))
				{
					if ($char['GP'] >= $cost)
					{
						
						myquery("UPDATE game_users SET GP=GP-$cost,CW=CW-'".($cost*money_weight)."',exam=exam+1 WHERE user_id=".$user_id."");
						setGP($user_id,-$cost,52);
						myquery("UPDATE game_users_data SET count_reload=count_reload+1 WHERE user_id=".$user_id."");
						add_skill($user_id, $p, -1, 0);																	
						echo'<br/><center><font face=verdana color=white size=2><b>������������� ���������!</b></font><meta http-equiv="refresh" content="1;url=town.php?option='.$option.'"><br /><br />';
					}
					else
					{
						echo'<br/><center><font face=verdana color=ff0000 size=2>� ���� �� ������� �����!</font><meta http-equiv="refresh" content="1;url=town.php?option='.$option.'"><br /><br />';
					}
				}
				else
				{
					echo '<center><b>�� ������������� ������ ������� �����? </b><br />
						  <br /><input type="button" onClick="location.href=\'town.php?option='.$option.'&do=skill_down&p='.$_GET['p'].'&agree\'" value="��, � ���� ������� �����">
						  <br /><br />
						  <input type="button" onClick="location.href=\'town.php?option='.$option.'\'" value="���, � �� ���� ��������� �����"><br /></center>';
				}	  
			}
		}		
		QuoteTable('close');
		echo '<br /></center>';	
	}
	
	//���������� �������������
	elseif(isset($_GET['do']) AND $_GET['do']=='har_down' AND isset($p))
	{
		echo '<center><br/>';
		QuoteTable('open');
		if (isset($_GET['agree']))
		{
			if ($char['GP'] >= $cost_har)
			{
				$har_race = mysql_fetch_array(myquery("SELECT * FROM game_har WHERE id=".$char['race'].""));
				$count_itemhar = mysql_fetch_array(myquery("SELECT SUM(g.dstr) STR,SUM(g.dpie) PIE,SUM(g.ddex) DEX,SUM(g.dvit) VIT,SUM(g.dntl) NTL,SUM(g.dspd) SPD FROM game_items i,game_items_factsheet g WHERE i.item_id=g.id AND i.used>0 AND g.type NOT IN (12,13,19,20,21) AND i.priznak=0 AND i.user_id=$user_id"));				
				list($time_har)=mysql_fetch_array(myquery("SELECT CASE WHEN SUM(value) IS NULL THEN 0 ELSE SUM(value) END FROM game_obelisk_users WHERE user_id=".$char['user_id']." AND harka='".$p."'"));
				if (($char[$p]-$count_itemhar[$p])>$har_race[strtolower($p)]+$time_har)
				{
					if (domain_name == 'testing.rpg.su' or domain_name=='localhost') 
					{
						$par_effect=15;
					}
					else
					{
						$par_effect=15;
					}
					$query_string = "UPDATE game_users SET GP=GP-$cost_har,CW=CW-'".($cost_har*money_weight)."',".$p."=".$p."-1,".$p."_MAX=".$p."_MAX-1,bound=bound+1";
					setGP($user_id,-$cost_har,52);
					myquery("UPDATE game_users_data SET count_reload_har=count_reload_har+1 WHERE user_id=$user_id");
					echo'<br><center><font face=verdana color=white size=2><b>���� �������������� ���������</b></font><meta http-equiv="refresh" content="1;url=town.php?option='.$option.'"><br /><br />';
					if ($p=='NTL') { $query_string.=",MP_MAX=MP_MAX-$par_effect,MP=MP-$par_effect";}
					if ($p=='PIE') { $query_string.=",STM_MAX=STM_MAX-$par_effect,STM=STM-$par_effect";}
					if ($p=='DEX') { $query_string.=",HP_MAX=HP_MAX-$par_effect,HP_MAXX=HP_MAXX-$par_effect,HP=HP-$par_effect,CC=CC-2";}
					$query_string.=" WHERE user_id=$user_id";
					myquery($query_string);
					$char = mysql_fetch_array(myquery("SELECT * FROM game_users WHERE user_id=$user_id"));
					//�������� ��������
					$sel_item = myquery("SELECT id FROM game_items WHERE user_id=$user_id AND priznak=0 AND used>0");
					while (list($item_id) = mysql_fetch_array($sel_item))
					{
						$Item = new Item($item_id);
						if ($Item->check_up()!=1)
						{
							$Item->down();
							echo '� ���� ���� �������: <b>'.$Item->getFact('name').'</b>';
						}
					}
				}
				else
				{
					echo'<br/><center><font face=verdana color=ff0000 size=2>�� �� ������ ������� ��� ��������������</b></font><meta http-equiv="refresh" content="1;url=town.php?option='.$option.'"><br /><br />';
				}
			}
			else
			{
				echo'<br/><center><font face=verdana color=ff0000 size=2>� ��� �� ������� �����!</font><meta http-equiv="refresh" content="1;url=town.php?option='.$option.'"><br /><br />';
			}
		}
		else
		{
			echo '<center><b>�� ������������� ������ ������� �������������? </b><br />
				  <br /><input type="button" onClick="location.href=\'town.php?option='.$option.'&do=har_down&p='.$_GET['p'].'&agree\'" value="��, � ���� ������� ��������������">
				  <br /><br />
				  <input type="button" onClick="location.href=\'town.php?option='.$option.'\'" value="���, � �� ���� ��������� ��������������"><br /></center>';
		}	  
		QuoteTable('close');
		echo '<br /></center>';			
	}
	
	//���������� ���������
	elseif(isset($_GET['do']) AND $do=='prof_down' AND isset($_GET['prof'])) 
	{
		echo '<center><br/>';
		QuoteTable('open');
		if (isset($_GET['agree']))
		{
			if ($char['GP'] >= $cost_prof)
			{
				$pr=$_GET['prof'];
				$test=myquery("Select craft_index from game_users_crafts where user_id=$user_id and profile=1 and craft_index=$pr");
				if (mysql_num_rows($test)>0)
				{
					//myquery("UPDATE game_users_crafts SET profile=0 Where user_id=$user_id and craft_index=$pr");
					myquery("DELETE FROM game_users_crafts Where user_id=$user_id and craft_index=$pr");
					myquery("UPDATE game_users SET GP=GP-$cost_prof,CW=CW-'".($cost_prof*money_weight)."' Where user_id=$user_id");
					setGP($user_id,-$cost_prof,52);
					echo'<br><center><font face=verdana color=white size=2><b>��������� ������</b></font><meta http-equiv="refresh" content="1;url=town.php?option='.$option.'"><br /><br />';
				}
				else
				{
					echo'<br/><center><font face=verdana color=ff0000 size=2>� ���� ��� ����� ���������</b></font><meta http-equiv="refresh" content="1;url=town.php?option='.$option.'"><br /><br/>';
				}
			}
			else
			{
				echo'<br/><center><font face=verdana color=ff0000 size=2>� ���� �� ������� �����!</font><meta http-equiv="refresh" content="1;url=town.php?option='.$option.'"><br /><br/>';
			}

		}
		else
		{
			echo '<center><b>�� ������������� ������ ������ ���������? <br/></b>
			      ����, ��� ���� ���������� ����� ������������ ��������!<br />
				  <br /><input type="button" onClick="location.href=\'town.php?option='.$option.'&do=prof_down&prof='.$prof.'&agree\'" value="��, � ���� ������ ���������">
				  <br /><br />
				  <input type="button" onClick="location.href=\'town.php?option='.$option.'\'" value="���, � �� ���� �������� ���������"><br /></center>';
		}	  
		QuoteTable('close');
		echo '<br /></center>';			
	}
	
	//������� ����
	else
	{
		if ($char['clevel'] >= 5)
		{
			echo'<center><font face=verdana color=ff0000 size=2><b>������ ������� ����</b></font></center><br>';
			echo'<center><font face=verdana color=white size=2><b>�� ����� ������ �� ������ �� ������������ ����� ���������� �� �������� ���� �������, ������������� � ���������. 
			     <br><br></font><font size=2 color="lightblue">���� ��������� ���������� ������ ������: '.$cost.' �����!
				 <br><br>���� ��������� ���������� ����� ��������������: '.$cost_har.' �����!
				 <br><br>���� ��������� ���������� ����� ���������: '.$cost_prof.' �����!<br><br></font><br><br>';
			echo '<table border="2" bordercolor="gold" cellspacing="3" cellpadding="0"><tr valign="top"><td>';
			
			OpenTable('title');
			echo '<table border="0" cellspacing="0" cellpadding="5" width="100%">';
			$har_race = mysql_fetch_array(myquery("SELECT * FROM game_har WHERE id=".$char['race'].""));
            $count_itemhar = mysql_fetch_array(myquery("SELECT SUM(g.dstr) dstr,SUM(g.dpie) dpie,SUM(g.ddex) ddex,SUM(g.dvit) dvit,SUM(g.dntl) dntl,SUM(g.dspd) dspd FROM game_items i,game_items_factsheet g WHERE i.item_id=g.id AND i.used>0 AND g.type NOT IN (12,13,19,20,21) AND i.priznak=0 AND i.user_id=$user_id"));
			list($time_har)=mysql_fetch_array(myquery("SELECT CASE WHEN SUM(value) IS NULL THEN 0 ELSE SUM(value) END FROM game_obelisk_users WHERE user_id=".$char['user_id']." AND harka='STR'"));
			$result=$char['STR']-$count_itemhar['dstr']-$har_race['str']-$time_har;
			if ($result>0) 
			{
				echo '<tr><td height="20" valign="middle">���� ('.$char['STR'].'): </td><td align="right">'.$result.' <a href=town.php?option='.$option.'&do=har_down&p=STR><img src="http://'.img_domain.'/forum/img/warn_minus.gif" border=0></a></td></tr>';
			}
			list($time_har)=mysql_fetch_array(myquery("SELECT CASE WHEN SUM(value) IS NULL THEN 0 ELSE SUM(value) END FROM game_obelisk_users WHERE user_id=".$char['user_id']." AND harka='SPD'"));
			$result=$char['SPD']-$count_itemhar['dspd']-$har_race['spd']-$time_har;
			if ($result>0) 
			{			
				echo '<tr><td height="20" valign="middle">�������� ('.$char['SPD'].'): </td><td align="right">'.$result.' <a href=town.php?option='.$option.'&do=har_down&p=SPD><img src="http://'.img_domain.'/forum/img/warn_minus.gif" border=0></a></td></tr>';
			}
			list($time_har)=mysql_fetch_array(myquery("SELECT CASE WHEN SUM(value) IS NULL THEN 0 ELSE SUM(value) END FROM game_obelisk_users WHERE user_id=".$char['user_id']." AND harka='NTL'"));
			$result=$char['NTL']-$count_itemhar['dntl']-$har_race['ntl']-$time_har;
			if ($result>0) 
			{			
				echo '<tr><td height="20" valign="middle">��������� ('.$char['NTL'].'): </td><td align="right">'.$result.' <a href=town.php?option='.$option.'&do=har_down&p=NTL><img src="http://'.img_domain.'/forum/img/warn_minus.gif" border=0></a></td></tr>';
			}
			list($time_har)=mysql_fetch_array(myquery("SELECT CASE WHEN SUM(value) IS NULL THEN 0 ELSE SUM(value) END FROM game_obelisk_users WHERE user_id=".$char['user_id']." AND harka='PIE'"));
			$result=$char['PIE']-$count_itemhar['dpie']-$har_race['pie']-$time_har;
			if ($result>0) 
			{			
				echo '<tr><td height="20" valign="middle">�������� ('.$char['PIE'].'): </td><td align="right">'.$result.' <a href=town.php?option='.$option.'&do=har_down&p=PIE><img src="http://'.img_domain.'/forum/img/warn_minus.gif" border=0></a></td></tr>';
			}
			list($time_har)=mysql_fetch_array(myquery("SELECT CASE WHEN SUM(value) IS NULL THEN 0 ELSE SUM(value) END FROM game_obelisk_users WHERE user_id=".$char['user_id']." AND harka='VIT'"));
			$result=$char['VIT']-$count_itemhar['dvit']-$har_race['vit']-$time_har;
			if ($result>0) 
			{	
				echo '<tr><td height="20" valign="middle">������ ('.$char['VIT'].'): </td><td align="right">'.$result.' <a href=town.php?option='.$option.'&do=har_down&p=VIT><img src="http://'.img_domain.'/forum/img/warn_minus.gif" border=0></a></td></tr>';
			}
			list($time_har)=mysql_fetch_array(myquery("SELECT CASE WHEN SUM(value) IS NULL THEN 0 ELSE SUM(value) END FROM game_obelisk_users WHERE user_id=".$char['user_id']." AND harka='DEX'"));
			$result=$char['DEX']-$count_itemhar['ddex']-$har_race['dex']-$time_har;
			if ($result>0) 
			{	
				echo '<tr><td height="20" valign="middle">������������ ('.$char['DEX'].'): </td><td align="right">'.$result.' <a href=town.php?option='.$option.'&do=har_down&p=DEX><img src="http://'.img_domain.'/forum/img/warn_minus.gif" border=0></a></td></tr>';
			}
			echo '</table>';
			
			OpenTable('close');
			echo '</td><td>';
			
			//������� ���������� �������������
			$skill_test=myquery("SELECT gs.name, gs.id, gus.level FROM game_users_skills as gus JOIN game_skills as gs on gus.skill_id=gs.id WHERE gus.user_id=".$user_id."");
			if (mysql_num_rows($skill_test)>0)
			{
				OpenTable('title');
				echo '<table border="0" cellspacing="0" cellpadding="5" width="100%">';
				while ($skill=mysql_fetch_array($skill_test))
				{
					if ( ($char['reinc']==0 AND $skill['id']==25 AND $skill['level']==1) OR $skill['id']==21  OR ($char['reinc']<2 AND $skill['id']==32))
					{
						
					}
					else
					{
						echo '<tr><td height="20" valign="middle">'.$skill["name"].': </td><td align="right">'.$skill["level"].' <a href=town.php?option='.$option.'&do=skill_down&p='.$skill["id"].'><img src="http://'.img_domain.'/forum/img/warn_minus.gif" border=0></a></td></tr>';					
					}			
				}
				echo '</table>';
				OpenTable('close');
			}
			
			//������� ���������� ���������
			$prof_test=myquery("SELECT t1.craft_index, t2.name from game_users_crafts as t1 JOIN game_craft_prof as t2 on t1.craft_index=t2.prof_id
				                WHERE t1.user_id=".$user_id." and t1.profile=1 and t1.craft_index not in (1,2)");
			if (mysql_num_rows($prof_test)>0)
			{
				echo '</tr></td><tr valign="top" ><td colspan="2" >';
				OpenTable('title');
				echo '<table border="0" cellspacing="0" cellpadding="5" width="100%">';
				while ($prof=mysql_fetch_array($prof_test))
				{
					echo '<tr><td align="center">'.$prof["name"].' <a href=town.php?option='.$option.'&do=prof_down&prof='.$prof["craft_index"].'><img src="http://'.img_domain.'/forum/img/warn_minus.gif" border=0></a></td></tr>';
				}
				echo '</table>';
				OpenTable('close');
			}
			
			echo '</tr></td></table>';
		}
		else
		{
			echo'<center>��� ����������������� ������� ����� ���� ������ 5-�� ������';
		}
	}

	echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
	

}

if (function_exists("save_debug")) save_debug(); 

?>