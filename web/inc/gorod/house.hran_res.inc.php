<?php
//операции с хранилищем ресурсов
$free = $templ['min_value'] - mysqlresult(myquery("SELECT SUM(craft_resource_market.col*craft_resource.weight) FROM craft_resource,craft_resource_market WHERE craft_resource_market.user_id=$user_id AND craft_resource_market.priznak=1 AND craft_resource_market.town=$town AND craft_resource_market.res_id=craft_resource.id"),0,0);
if (isset($_GET['sel_hran']))
{
	if ($free>0)
	{
		$selec=myquery("select craft_resource_user.id,craft_resource.img3 AS img,craft_resource.name from craft_resource,craft_resource_user where craft_resource_user.user_id=$user_id and craft_resource_user.col>0 and craft_resource.id=craft_resource_user.res_id");
		while ($row=mysql_fetch_array($selec))
		{
			echo'<table border="0" cellpadding="1"><tr><td></td></tr></table><table border="1" cellpadding="0" style="border-collapse: collapse" width="98%" bordercolor="777777" bgcolor="223344"><tr><td width=70 align=center><a href=town.php?option='.$option.'&hran='.$row["id"].'&part4&add='.$build_id.'><img src="http://'.img_domain.'/item/resources/'.$row["img"].'.gif" border="0"></a></td><td><a href=town.php?option='.$option.'&hran='.$row["id"].'&part4&add='.$build_id.'>'.$row["name"].'</a></td></tr></table>';
		}
		echo'Нажми на рисунок, чтобы положить ресурс в '.$templ['name'];
	}
	else
	{
		echo '<br />В здании "'.$templ['name'].'" больше нет свободных мест!<br />';
	}
	
}
elseif (isset($_GET['get']))
{	
	if (isset($_GET['getnow']) and (int)$_GET['getnow']>0)
	{
		$Res = new Res();
		$weight = $Res->take_house(0, $_GET['get'], (int)$_GET['getnow']);
		echo $Res->message.'<br><br>';
		$free+=$weight;	
		$char['CC']-=$weight;		
	}
	else
	{
		$hransel = myquery("SELECT craft_resource_market.col,craft_resource.img3 AS img,craft_resource.name,craft_resource.weight,craft_resource.id AS res_id FROM craft_resource_market,craft_resource WHERE craft_resource_market.user_id=$user_id AND craft_resource_market.town=$town AND craft_resource_market.res_id=craft_resource.id AND craft_resource_market.id=".$_GET['get']." AND craft_resource_market.priznak=1");
		if ($hransel!=false AND mysql_num_rows($hransel)>0)
		{
			$hran = mysql_fetch_array($hransel);
			echo '<center>Взять из хранилища <input size="5" type="text" id="hran_col" value="0"> ед из '.$hran['col'].' ед. ресурса <img src="http://'.img_domain.'/item/resources/'.$hran["img"].'.gif" border="0"></a> '.$hran['name'].'<br /><i>1 единица ресурса '.$hran['name'].' весит '.$hran['weight'].' кг.</i>';
			echo '<br /><br /><input type="button" value="Взять ресурс из хранилища" onclick="location.replace(\'town.php?option='.$option.'&part4&add='.$build_id.'&get='.$_GET['get'].'&getnow=\'+document.getElementById(\'hran_col\').value+\'\')"><br/><br><br>';
		}
	}
}
elseif (isset($_GET['hran']))
{
	if ($free>0)
	{		
		if (isset($_GET['hrannow']) and $_GET['hrannow']>0)
		{
			$it = (int)$_GET['hran'];
			$col = (int)$_GET['hrannow'];
			$Res = new Res();
			$weight = $Res->put_house($it, $col, $town, $free);
			echo $Res->message.'<br><br>';
			$free-=$weight;	
			$char['CC']+=$weight;			
		}
		else
		{
			$hransel = myquery("SELECT craft_resource_user.col,craft_resource.img3 AS img,craft_resource.name,craft_resource.weight,craft_resource.id AS res_id FROM craft_resource_user,craft_resource WHERE craft_resource_user.user_id=$user_id AND craft_resource_user.col>0 AND craft_resource_user.res_id=craft_resource.id AND craft_resource_user.id=".$_GET['hran']."");
			if ($hransel!=false AND mysql_num_rows($hransel)>0)
			{
				$hran = mysql_fetch_array($hransel);
				echo '<center><br />Положить в хранилище <input size="5" type="text" id="hran_col" value="0"> ед. из '.$hran['col'].' ед. ресурса <img src="http://'.img_domain.'/item/resources/'.$hran["img"].'.gif" border="0"></a> '.$hran['name'].'<br /><i>1 единица ресурса '.$hran['name'].' весит '.$hran['weight'].' кг.</i>';
				echo '<br /><br /><input type="button" value="Положить ресурс в хранилище" onclick="location.replace(\'town.php?option='.$option.'&part4&add='.$build_id.'&hran='.$_GET['hran'].'&hrannow=\'+document.getElementById(\'hran_col\').value+\'\')"><br />';
			}
		}		
	}
}

//хранилище
echo '<strong>'.$templ['name'].' (максимум '.$templ['min_value'].' кг ресурсов)</strong><br /><br />';
echo 'Сейчас свободно <b><font color=red>'.max(0,$free).'</font></b> кг для хранения ресурсов.<br />';
echo "В инвентаре свободно место для <b><font color=red>".max(0,($char['CC']-$char['CW']))."</font></b> кг.<br><br>";
echo '<a href="?option='.$option.'&town_id='.$town.'&part4&add='.$build_id.'&sel_hran">Положить ресурсы в '.$templ['name'].'</a><br /><br />';
echo'<SCRIPT language=javascript src="../js/info.js"></SCRIPT><DIV id=hint  style="Z-INDEX: 0; LEFT: 0px; VISIBILITY: hidden; POSITION: absolute; TOP: 0px"></DIV>';
QuoteTable('open','100%');
$sel = myquery("SELECT craft_resource_market.id,craft_resource_market.col,craft_resource.name,craft_resource.img3 As img FROM craft_resource_market,craft_resource WHERE craft_resource_market.priznak=1 AND craft_resource_market.user_id=$user_id AND craft_resource_market.town=$town AND craft_resource_market.res_id=craft_resource.id");
if ($sel!=false AND mysql_num_rows($sel)>0)
{
	while ($hran = mysql_fetch_array($sel))
	{
		echo '<table border="1" cellpadding="0" style="border-collapse: collapse" width="98%" bordercolor="777777" bgcolor="223344" align=center><tr><td>';
		echo '<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr>';
		echo '<td align="center" width="100"><img src="http://'.img_domain.'/item/resources/'.$hran['img'].'.gif" border="0" alt=""></td>';
		echo '<td width="300"><font color="#ffff00">'.$hran['name'].'</font></td>';
		echo '<td width="100"><font color="#FF8080">'.$hran['col'].' ед.</font></td>';
		echo '<td style="text-align:right;padding-right:10px;width:130px;"><input type="button" value="Взять ресурс" onClick=location.replace("town.php?option='.$option.'&town_id='.$town.'&get='.$hran['id'].'&part4&add='.$build_id.'")></td>';
		echo '</tr></table></td></tr></table>';
	}
}
QuoteTable('close');
?>