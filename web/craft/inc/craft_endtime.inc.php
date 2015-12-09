<?php
//выплата работающему
$select=myquery("select * from craft_build_user where id=$build_id");
if ($select!=FALSE AND mysql_num_rows($select)>0)
{   
	echo 'Ты '.echo_sex('заработал','заработала').':<br><b>';
	$ress=mysql_fetch_array($select);
	$dohod=$ress['dohod'];
	$build_id=(int)$ress['id'];
	$build_gold=(int)$ress['gold'];
	$build_type=(int)$ress['type'];
	$build_vladel=(int)$ress['user_id'];
	$a=explode("|",$dohod);

	if ($build_type<=0) return;
	list($admin_build) = mysql_fetch_array(myquery("SELECT admin FROM craft_build WHERE id=$build_type"));
	
	if ($build_gold>='1')
	{
		$select=myquery("select gp from game_users where user_id=$build_vladel");
		$uus=mysql_fetch_array($select);

		if ($uus['gp']>=$build_gold)
		{
			echo "$build_gold золотых!<br><br>";
			myquery("update game_users set gp=gp+$build_gold,CW=CW+".(money_weight*$build_gold)." where user_id=$user_id");
			setGP($user_id,$build_gold,1);
			myquery("update game_users set gp=gp-$build_gold,CW=CW+".(money_weight*$build_gold)." where user_id=$build_vladel");
			setGP($build_vladel,-$build_gold,2);
			
			//Статистика
			myquery("insert into craft_stat (build_id, gp, res_id, dob, vip, dat, user, type) values ($build_id, $build_gold, '', '', '', ".time().", $user_id, 'z')");
		}
		else
		{
			echo "У хозяина не хватило денег чтобы расплатиться!<br><br>";
			myquery("update game_users set gp=0,CW=CW-".(money_weight*$uus['gp'])." where user_id=$build_vladel");
			setGP($build_vladel,-$uus['gp'],2);
		}
	}

	for ($i=0;$i<count($a);$i++)
	{
		$b=explode("-",$a[$i]);
		if (sizeof($b)!=2) continue;
		$select=myquery("select * from craft_resource where id=$b[0]");
		$build=mysql_fetch_array($select);    

		//вычитание у владельца        
		$selo=myquery("select * from craft_resource_user where res_id=$b[0] and user_id=$build_vladel");
		$resko=mysql_fetch_array($selo);
		if (mysql_num_rows($selo) and $resko['col']>=$b[1])
		{
			if ($admin_build!=1)
			{
				myquery("update craft_resource_user set col=GREATEST(0,col-$b[1]) where user_id=$build_vladel and res_id=$b[0]");
				myquery("UPDATE game_users SET CW=CW-".($build['weight']*$b[1])." WHERE user_id=$build_vladel"); 
			}

			$chance = 100;
			mt_srand(make_seed());
			$r = mt_rand(0,100);
			$inc = 0;
			if ($build['spets']!='') 
			{             
				$craft_index = get_craft_index($build['spets']);
				$spetstime = getCraftTimes($user_id,$craft_index);
				$spetslevel = CraftSpetsTimeToLevel($craft_index,$spetstime+1);
				if ($craft_index==1)
				{
					if (getCraftLevel($user_id,$craft_index)<19)                                 
						$chance = 50;
					else
						$chance = min(100,50+(getCraftLevel($user_id,$craft_index)-18)*2);		
					if ($r>$chance)
					{
						// промашка при сборе. Навык не увеличиваем
					}
					else
					{
						$inc = 1;    
					}
				} 
				else
				{
					$inc = 1;
				}
			}
			$check = 0;
			if ($inc == 1 AND $build['spets']=='sobiratel')
			{				
				$Res= new Res(0, $b[0]);
				$check=$Res->add_user(0,$user_id);
				if ($check == 1)
				{
					add_exp_for_craft($user_id, 1);
					setCraftTimes($user_id,1,1,1);
					//Статистика
					echo "<span style=\"font-family:Tahoma,Verdana,helvetica;font-size:11px;color:#80FF80;\">$build[name] - $b[1] ед.</span>";
					myquery("INSERT INTO craft_stat (build_id, gp, res_id, dob, vip, dat, user, type) VALUES ($build_id, 0, $b[0], 0, $b[1], ".time().", $user_id, 'z')");
				}
			}			
			if ($check == 0)
			{
				echo "<span style=\"font-family:Tahoma,Verdana,helvetica;font-size:11px;color:#80FF80;\">$build[name] - тебе не удалось собрать этот ресурс</span>";
				myquery("INSERT INTO craft_stat (build_id, gp, res_id, dob, vip, dat, user, type) VALUES ($build_id, '0', '".$b[0]."', '0', '0', ".time().", $user_id, 'n')");
			}
			echo '<br>';
		}
		else
		{
			echo'Владелец не смог с тобой расплатиться ресурсом: '.$build['name'].'!<br>';
		}

	}
	echo'</b>';
	

	//выплата владельцу
	$select1=myquery("select * from craft_build where id=$build_type");
	$re=mysql_fetch_array($select1);
	$dohod1=$re['res_dob'];
	$aa=explode("|",$dohod1);
	for ($i=0;$i<count($aa);$i++)
	{
		$bb=explode("-",$aa[$i]);
		if (sizeof($bb)!=2) continue;
		$select2=myquery("select * from craft_resource where id=$bb[0]");
		$build=mysql_fetch_array($select2);
        $Res= new Res($build);
		$check=$Res->add_user(0,$build_vladel);
		if ($check==1 and $admin_build!=1)
		{
			myquery("insert into craft_stat (build_id, gp, res_id, dob, vip, dat, user, type) values ($build_id, '', $bb[0], $bb[1], '', ".time().", $build_vladel, 'p')");
		}		
	}
}
?>