<?

if (function_exists("start_debug")) start_debug(); 

$cost = 15;
$wait_time = 60*60;
$da = getdate();

if ($town!=0)
{
    if (isset($town_id) AND $town_id!=$town)
    {
		echo'�� ���������� � ������ ������!<br><br><br>&nbsp;&nbsp;&nbsp;<input type="button" value="�����" onClick=location.href="act.php">&nbsp;&nbsp;&nbsp;';
    }

	$userban=myquery("select * from game_ban where user_id='$user_id' and type=2 and time>'".time()."'");
	if (mysql_num_rows($userban))
	{
		$userr = mysql_fetch_array($userban);
		$min = ceil(($userr['time']-time())/60);
		echo '<center><br><br><br>�� ���� �������� ��������� �� '.$min.' �����! ���� ��������� �������� ������!';
		{if (function_exists("save_debug")) save_debug(); exit;}
	}

	$img='http://'.img_domain.'/race_table/human/table';
	$width='100%';
	$height='100%';

	echo'<table width=100% border="0" cellspacing="0" cellpadding="0"><tr><td width="1" height="1"><img src="'.$img.'_lt.gif"></td><td background="'.$img.'_mt.gif"></td><td width="1" height="1"><img src="'.$img.'_rt.gif"></td></tr>
	<tr><td background="'.$img.'_lm.gif"></td><td style="text-align:center" background="'.$img.'_mm.gif" valign="top" width="'.$width.'" height="'.$height.'">';

	//�������� �����
	echo '<center>';
	echo '<b>����� ���������� � ������� ������!</b><br>';
	
	//����� ������ �� �������
	if (isset($_POST['pay']) and is_numeric($_POST['kol']) and (int)$_POST['kol']>0)
	{
        echo '<br>';
		QuoteTable('open');
		$kol=min($char['injury'],(int)$_POST['kol']);
		$price = $kol*$cost;
		if ($char['GP']>=$price and $price>0)
		{
			myquery("UPDATE game_users SET injury=injury-'".$kol."',GP=GP-'".$price."',CW=CW-".($price*money_weight)." WHERE user_id='".$user_id."'");
			setGP($user_id,-$price,44);
			$char['injury'] = $char['injury'] - $kol;
			echo '��������: <b>'.$kol.'</b> '.pluralForm($kol,'�������','�������','������').' ������������� �� '.$price.' �����';
		}
		else
		{
			echo '� ���� ������������ ����� ��� ������� <b>'.$kol.'</b> '.pluralForm($kol,'�������','������','������').' �������������';
		}
        QuoteTable('close');
	}
	//����� �������� ���������� ��������� �������
	elseif (isset($_GET['heal']))
	{
		if (time()-$wait_time > $char['injury_time'])
		{
			myquery("UPDATE game_users SET injury=injury-1, injury_time='".time()."' WHERE user_id = '".$user_id."'");
			echo '<br><b>���������� �������� ��������� ������� ��������!</b><br>';
			$char['injury']--;
			$char['injury_time']=time();
		}
		else
		{
			echo '<br>��-��-��! �������� �������??? ���� ��� ���� ��������� ���������� �������� ���������!!!<br>';
		}
	}
	
	//�������� ���� ������
	if ($char['injury']==0)
	{
		echo '<br>� ����� ��� � �������. �� �� ���������� � ���� �������. ������� �� ���, ����� ���� ����������� ��� ������!';
	}
	else 
	{
		echo '<br><i>������� ����� ������������� ���������� <b>'.$char['injury'].'</b> '.pluralForm($char['injury'],'�������','�������','������').'</i> 
			  <br>� ������, ���� ������ � ���? �� ���������, ��� ���������. 
              � ���� ����������� ������ �����, ��������� �������� ��� ��������� ������������ ���� ����! �� ��� ���� ��������� � ���������, �������, 
			  ���� ������, ����������� �� ��������� ��������������. ������, <b>'.$cost.'</b> '.pluralForm($cost,'������','������','�����').' �� ������ ���� ��������. 
			  ��� �� ���� ������ ���� ����������, �� �� ����, ��� ��� � ���, ��� ��� ����� �������! ���� ���������  ������� ���� �� ������ �������. 
			  ����� ���. � � ���� ����� ����� � ����.';
		echo '</center>';
		
		echo'<br><br><form action="" method="POST">
			 <span style="width:100%;text-align:right;">1) ��������� ������ �� �������: 
			 <input type="text" size="5" maxsize="5" name="kol" value="'.$char['injury'].'"> 
			 '.pluralForm($char['injury'],'������� �������������','������ �������������','������ �������������').'
			 <br><input type="submit" name="pay" value="������ �������"></span>
			 </form>';
		if (time()-$wait_time > $char['injury_time'])
		{
			echo '2) <a href="town.php?option='.$option.'&heal">������ ���������� ��������� �������</a>';
		}
		else
		{
			echo '2) ��������� ���������� ��������� ������� ����� ����� ������ ������ � <b>'.date("H:i d.m.Y",($char['injury_time']+$wait_time)).'</b>';
		}
		echo '<br><br>';
	}

	echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
}

if (function_exists("save_debug")) save_debug(); 

?>