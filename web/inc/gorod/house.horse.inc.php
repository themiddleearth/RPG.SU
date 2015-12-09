<?php
if (isset($_GET['id']))
{
	if (isset($_GET['untake']))
	{
		//слезаем с коня
        $ves_minus = 0;
		$vsad = 0;
        $check=myquery("SELECT horse_id FROM game_users_horses WHERE user_id=".$user_id." AND used=1");
		if (mysql_num_rows($check)>0)
		{
			list($horse_id)=mysql_fetch_array($check);
			list($ves_minus,$vsad) = mysql_fetch_array(myquery("SELECT ves,vsad FROM game_vsadnik WHERE id=".$horse_id.""));						
		}
		myquery("UPDATE game_users SET vsadnik=vsadnik-".($vsad*vsad).",CC=CC-".$ves_minus." WHERE user_id=".$user_id."");
		myquery("UPDATE game_users_horses SET town=$town, used=0 WHERE id=".$_GET['id']."");
	}
	if (isset($_GET['take']))
	{
		//залезаем на коня
		$check_skill=myquery("SELECT level FROM game_users_skills WHERE user_id=".$user_id." AND skill_id=25");
		if (mysql_num_rows($check_skill)==1)
		{
			list($skill)=mysql_fetch_array($check_skill);
		}
		else
		{	
			$skill=0;
		}
		$id_horse = mysqlresult(myquery("SELECT horse_id FROM game_users_horses WHERE id=".$_GET['id'].""),0,0);
		list($ves,$vsad) = mysql_fetch_array(myquery("SELECT ves, vsad FROM game_vsadnik WHERE id=".$id_horse.""));
		if ($skill>=$vsad)
		{		
			$check=myquery("SELECT horse_id FROM game_users_horses WHERE user_id=".$user_id." AND used=1");
			if (mysql_num_rows($check)>0)
			{
				list($horse_id)=mysql_fetch_array($check);
				list($ves_minus,$vsad_minus) = mysql_fetch_array(myquery("SELECT ves,vsad FROM game_vsadnik WHERE id=".$horse_id.""));
				$ves-=$ves_minus;
				$vsad-=$vsad_minus;
			}
			myquery("UPDATE game_users SET vsadnik=vsadnik+".($vsad*vsad).",CC=CC+".$ves." WHERE user_id=".$user_id."");
			myquery("UPDATE game_users_horses SET town=0, used=1 WHERE id=".$_GET['id']."");
			myquery("UPDATE game_users_horses SET town=$town, used=0 WHERE user_id=".$user_id." AND used=1 AND id<>".$_GET['id']."");
		}
		else
		{
			echo '<b>Ваш уровень верховой езды не позволяет оседлать питомца!</b><br><br>';
		}
	}
	if (isset($_GET['eat']))
	{
		//кормим коня
		$kon = mysql_fetch_array(myquery("SELECT * FROM game_users_horses WHERE id=".$_GET['id'].""));
		if ($kon['golod']>0)
		{
			switch ($kon['golod'])
			{
				case 0: $state= 'сытое'; $k = 0; break;
				case 1: $state= 'слегка голодное'; $k = 1; break;
				case 2: $state= 'голодное'; $k = 2; break;
				case 3: $state= 'очень голодное'; $k = 3; break;
				case 4: $state= 'обессиленное'; $k = 4; break;
				default: $state= 'умирающее'; $k = 10; break;
			} 
			$koni = mysql_fetch_array(myquery("select * from game_vsadnik where id='".$kon['horse_id']."'"));
			$gp_eat = round($k*$koni['price_eat']*0.75,2);
			if ($char['GP']>=$gp_eat)
			{
				$up=myquery("UPDATE game_users SET GP=GP-$gp_eat,CW=CW-'".($gp_eat*money_weight)."' WHERE user_id=".$user_id." LIMIT 1");
				setGP($user_id,-$gp_eat,62);
				myquery("UPDATE game_users_horses SET golod=0 WHERE id=".$_GET['id']."");
			}
		}
	}
}
echo '<center><b><font color="white" size="2">'.$templ['name'].'</font></b></center><br /><br />';
echo 'У тебя имеются питомцы:<br />';
$max_horse = 1;
if ($build_id==6) $max_horse = 2;
if ($build_id==7) $max_horse = 3;
if ($build_id==8) $max_horse = 4;
$sel = myquery("SELECT game_users_horses.*,game_vsadnik.nazv,game_vsadnik.price_eat,game_vsadnik.life_horse FROM game_users_horses,game_vsadnik WHERE game_users_horses.user_id=$user_id AND game_users_horses.horse_id=game_vsadnik.id AND (game_users_horses.town=$town OR game_users_horses.used=1)");
echo '<table width="90%" border="1" cellspacing="2" cellpadding="5">
<tr><td style="text-align:center;font-weight:800;color:white">Животное</td><td style="text-align:center;font-weight:800;color:white">Осталось жизни</td><td style="text-align:center;font-weight:800;color:white">Состояние голода</td><td>&nbsp;</td></tr>';
while ($row = mysql_fetch_array($sel))
{
	switch ($row['golod'])
	{
		case 0: $state= 'сытое'; $k = 0; break;
		case 1: $state= 'слегка голодное'; $k = 1; break;
		case 2: $state= 'голодное'; $k = 2; break;
		case 3: $state= 'очень голодное'; $k = 3; break;
		case 4: $state= 'обессиленное'; $k = 4; break;
		default: $state= 'умирающее'; $k = 10; break;
	}
	$gp = round($k*$row['price_eat']*0.75,2); 
	echo '<tr><td>'.$row['nazv'].'';
	if ($row['used']==1)
	{
		echo '<br /><i>(оседлан)</i>';
	}
	echo '</td><td>'.($row['life_horse']-$row['life']).' / '.$row['life_horse'].' игровых мес.</td><td>'.$state.'</td><td>';
	//кнопки действия
	$count_horses = mysql_result(myquery("SELECT COUNT(*) FROM game_users_horses WHERE town=$town AND user_id=".$user_id.""),0,0);
	if ($row['golod']>0)
	{
		echo '<a href="town.php?option='.$option.'&part4&add='.$build_id.'&id='.$row['id'].'&eat">Накормить (цена: '.$gp.' монет)</a><br /><br />';
	}
	if ($row['used']==0)
	{
		echo '<a href="town.php?option='.$option.'&part4&add='.$build_id.'&id='.$row['id'].'&take">Оседлать</a>';
	}
	elseif ($count_horses<$max_horse)
	{
		echo '<a href="town.php?option='.$option.'&part4&add='.$build_id.'&id='.$row['id'].'&untake">Оставить в здании '.$templ['name'].'</a>';
	}
	echo '&nbsp;</td></tr>';
}
echo '</table><br /><br /><br />';
echo 'Количество питомцев, которое ты можешь содержать - '.$max_horse.'<br /><br /><br />';
?>