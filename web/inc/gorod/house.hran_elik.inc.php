<?php
//�������� � ���������� �����
$free = $templ['min_value'] - mysql_result(myquery("SELECT Sum(count_item) FROM game_items WHERE user_id=$user_id AND town=$town AND priznak=4 AND item_id IN (SELECT id FROM game_items_factsheet WHERE type=13)"),0,0);
if (isset($_GET['sel_hran']))
{
	if ($free>0)
	{
		$selec=myquery("select game_items.id,game_items_factsheet.img,game_items_factsheet.name from game_items,game_items_factsheet where game_items.user_id='$user_id' and game_items.used=0 and game_items.item_for_quest=0 and game_items.priznak=0 and game_items.item_id=game_items_factsheet.id and game_items_factsheet.type=13");
		while ($row=mysql_fetch_array($selec))
		{
			echo'<table border="0" cellpadding="1"><tr><td></td></tr></table><table border="1" cellpadding="0" style="border-collapse: collapse" width="98%" bordercolor="777777" bgcolor="223344"><tr><td width=70 align=center><a href=town.php?option='.$option.'&hran='.$row["id"].'&part4&add='.$build_id.'><img src="http://'.img_domain.'/item/'.$row["img"].'.gif" border="0"></a></td><td><a href=town.php?option='.$option.'&hran='.$row["id"].'&part4&add='.$build_id.'>'.$row["name"].'</a></td></tr></table>';
		}
		echo'����� �� �������, ����� �������� ������� � '.$templ['name'];
	}
	else
	{
		echo '<br />� ������ "'.$templ['name'].'" ������ ��� ��������� ����!<br />';
	}
	
}
elseif (isset($_GET['get'])) //����� �������
{
	$getel_check = myquery("SELECT count_item From game_items Where user_id=$user_id and id=".$_GET['get']."");
	if ($getel_check!=false AND mysql_num_rows($getel_check)>0)
	{
		list($getel)=mysql_fetch_array($getel_check);
		if (isset($_GET['getnow']) and $_GET['getnow']>0)
		{
			$col_el = max(0,(int)$_GET['getnow']);
			$col_el = min($col_el,$getel);
			$Item = new Item($_GET['get']); 
			if ($Item->getFact('weight')*$col_el>($char['CC']-$char['CW']))
			{
				echo '��� ���������� ����� � ���������!';
			}
			elseif ($Item->getItem('user_id')==$user_id AND $Item->getItem('priznak')==4)
			{
				$Item->move_item_to_user(0,$user_id, $col_el);
				$free=$free+$col_el;
			}
		}
		else
		{
			list($el_name)=mysql_fetch_array(myquery("SELECT t1.name From game_items_factsheet as t1
													  Join game_items as t2 On t1.id=t2.item_id
													  Where t2.id=".$_GET['get'].""));
			echo '<center><br />����� ������� <b>'.$el_name.'</b> � ���������� <input size="5" type="text" id="get_col" value="0"> ��.</a>';
			echo '<br><i>� ��� ���� '.$getel.' '.pluralForm($getel,'�������','��������','���������').'</i>';
			echo '<br /><br /><input type="button" value="����� ������� �� ���������" onClick="location.replace(\'town.php?option='.$option.'&part4&add='.$build_id.'&get='.$_GET['get'].'&getnow=\'+document.getElementById(\'get_col\').value+\'\')"><br />';
		}
	}
	else
	{
		echo 'aa';
	}
}

elseif (isset($_GET['hran'])) //�������� �������
{
	$hranel_check = myquery("SELECT count_item From game_items Where user_id=$user_id and id=".$_GET['hran']."");
	if ($hranel_check!=false AND mysql_num_rows($hranel_check)>0)
	{
		list($hranel)=mysql_fetch_array($hranel_check);
		if (isset($_GET['hrannow']) and $_GET['hrannow']>0)
		{
			$col_el = max(0,(int)$_GET['hrannow']);
			$col_el = min($col_el,$hranel);
			$Item = new Item($_GET['hran']); 
			if ($free>=$col_el)
			{
				$Item = new Item($_GET['hran']);
				if ($Item->getItem('user_id')==$user_id AND $Item->getFact('type')==13 AND $Item->getItem('item_for_quest')==0 AND $Item->getItem('priznak')==0 AND $Item->getItem('used')==0)
				{
					$Item->move_item_to_market($town,0,0,0,4,$col_el);
					$free=$free-$col_el;
				}
			}
			else
			{
				echo '������������ ����� � ���������!';
			}
		}
		else
		{
			list($el_name)=mysql_fetch_array(myquery("SELECT t1.name From game_items_factsheet as t1
													  Join game_items as t2 On t1.id=t2.item_id
													  Where t2.id=".$_GET['hran'].""));
			echo '<center><br />�������� ������� <b>'.$el_name.'</b> � ���������� <input size="5" type="text" id="hran_col" value="0"> ��.</a>';
			echo '<br><i>� ��� ���� '.$hranel.' '.pluralForm($hranel,'�������','��������','���������').'</i>';
			echo '<br /><br /><input type="button" value="�������� ������� � ���������" onClick="location.replace(\'town.php?option='.$option.'&part4&add='.$build_id.'&hran='.$_GET['hran'].'&hrannow=\'+document.getElementById(\'hran_col\').value+\'\')"><br />';
		}
	}
}

//���������
echo '<strong>'.$templ['name'].' (�������� '.$templ['min_value'].' ���������)</strong><br /><br />';
echo '������ �������� <b><font color=red>'.$free.'</font></b> ���� ��� �������� ���������.<br />';
echo "� ��������� �������� ����� ��� <b><font color=red>".max(0,($char['CC']-$char['CW']))."</font></b> ��.<br><br>";
echo '<a href="?option='.$option.'&town_id='.$town.'&part4&add='.$build_id.'&sel_hran">�������� �������� � '.$templ['name'].'</a><br /><br />';
echo'<SCRIPT language=javascript src="../js/info.js"></SCRIPT><DIV id=hint  style="Z-INDEX: 0; LEFT: 0px; VISIBILITY: hidden; POSITION: absolute; TOP: 0px"></DIV>';
QuoteTable('open','100%');
$sel = myquery("SELECT game_items.id,game_items.item_id from game_items left join game_items_factsheet on game_items.item_id=game_items_factsheet.id WHERE game_items.user_id=$user_id AND town=$town AND game_items.used=0 AND game_items.priznak=4 and game_items_factsheet.type=13 ORDER BY game_items.item_id");
$type_el=0;
//$sel = myquery("SELECT *  FROM game_items WHERE priznak=4 AND user_id=$user_id AND item_id IN (SELECT id FROM game_items_factsheet WHERE type=13)");
if ($sel!=false AND mysql_num_rows($sel)>0)
{
	while ($hran = mysql_fetch_array($sel))
	{
		echo'
		<table border="1" cellpadding="0" style="border-collapse: collapse" width="98%" bordercolor="777777" bgcolor="223344"><table border="1" cellpadding="0" style="border-collapse: collapse" width="98%" bordercolor="777777" bgcolor="223344" align=center><tr><td>';
		echo '<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width=100 align="center">';

		$Item = new Item($hran['id']);
		$Item->hint(0,0,'<a ');
		echo '<img src="http://'.img_domain.'/item/'.$Item->getFact('img').'.gif" border="0" alt=""></a>';

		//echo'<br><font color="#ffff00">'.$Item->getFact('name').'</font>';
		echo'</td><td valign="top" width="220">
		<div align="left">';
		QuoteTable('open', '100%');
		echo'<center><b>'.$Item->getFact('name').'</b></center><br>';
		//$Item->info(0,1,1,'100%');
		if ($Item->fact['curse']!='')
		{
			$str     = $Item->fact['curse'];
			$order   = array("\r\n", "\n", "\r");
			$replace = '<br />';
			$newstr = str_replace($order, $replace, $str);
			echo $newstr.'<br>';
		}
		echo '��� ��������: '.$Item->getItem('weight').'<br>';
		echo '<div>���������� ���������: <font color="#FF8080"><b>'.$Item->getItem('count_item').'</b></font>';
		QuoteTable('close');
		echo '</td><td style="text-align:right;padding-right:10px;width:130px;"><input type="button" value="����� �������" onClick=location.replace("town.php?option='.$option.'&town_id='.$town.'&get='.$hran['id'].'&part4&add='.$build_id.'")>';
		
		echo'</td></tr></table></td></tr></table>';

	}
}
QuoteTable('close');
?>