<?

if (function_exists("start_debug")) start_debug(); 

include('inc/craft/craft.inc.php');
$select=myquery("select * from craft_build where id='".$build_type."'");
$building=mysql_fetch_array($select);
$col=$building['col'];
if (isset($_POST['digit']) OR isset($_POST['begin']))
{
	$select=myquery("select * from craft_build_rab where build_id=".$build['id']." AND (date_rab+dlit)>".time()."");
	$num=mysql_num_rows($select);
	$rab=mysql_fetch_array($select);

	$razresheno = 1;

	//�������� ����
	if ($building['race']!=0 and $building['race']!=$char['race'])
	{
		echo'���� ���� �� ����� ����� ��������';
		$razresheno = 0;
	}


	//�������� ������
	if ($building['clevel']!=0 and $building['clevel']>$char['clevel'])
	{
		echo'��������� '.$building['clevel'].' �������';
		$razresheno = 0;
	}

	//�������� ������������ �������� � �����
	if ($building['item']>0)
	{
		$selitem = myquery("SELECT * FROM game_items WHERE user_id=$user_id AND used!=0 AND ref_id=0 AND item_id='".$building['item']."' AND priznak=0");
		if (mysql_num_rows($selitem)>0)
		{
		}
		else
		{
			echo'��������� � ����� ������� '.mysqlresult(myquery("SELECT name FROM game_items_factsheet WHERE id=".$building['item'].""),0,0).'';
			echo '<br>'.$building['item'];
			$razresheno = 0;
		}
	}
	
	//�������� ��������� ����
	if ($num<$col and $rab['user_id']!=$user_id and $build_user!=$user_id and $razresheno==1 and isset($_SESSION['captcha']) and isset($_POST['digit']) and $_POST['digit']==$_SESSION['captcha'])
	{
		unset($_SESSION['captcha']);
		$dlitel = $building['rab_time'];
		$a = explode("|",$building['res_dob']);
		if (domain_name == 'testing.rpg.su' or domain_name=='localhost') 
		{
			$dlitel=2;
		}
		else
		{
			for ($i=0;$i<sizeof($a);$i++)
			{
				$b = explode("-",$a[$i]);
				$res_id = $b[0];
				if ($res_id>0)
				{
					$res = mysql_fetch_array(myquery("SELECT * FROM craft_resource WHERE id=$res_id"));
					if ($res['spets']!='')
					{
						$lev = getCraftLevel($user_id,get_craft_index($res['spets']));
						if ($lev>0)
						{
							$dlitel-=$lev*$res['decrease_rab_time'];
						}
					}
				}
			}
			$dlitel = max(2*60,$dlitel);
		}
		//����� ������ �� ������ ���� ����� 2 ����� �������
		myquery("DELETE FROM craft_build_rab WHERE user_id=$user_id");
		$eliksir = 99;
		if ($building['include']=='mining')
		{
			$eliksir=0;
		}
		$date_rab = time();
		if ($building['include']=='sawmill')
		{
			$date_rab=0;
			$dlitel=0;
			$eliksir=0; 
		}
		myquery("INSERT INTO craft_build_rab (user_id, build_id, date_rab, dlit, eliksir) VALUES ('".$char['user_id']."', '".$build['id']."', '".$date_rab."', '".$dlitel."',$eliksir)");
		$_SESSION['craft_code']='';
		$craft_index=1;
		$refresh=5;  
		if ($building['include']!='') 
		{
			$craft_index=get_craft_index($building['include']);
			$refresh=0;
		}
		else
		{
			echo '�� '.echo_sex('���������','����������').' �� ������';
		}
		$id_reason = getDelayReasonCraft($craft_index);
		if (isset($_SESSION['cur_get_mining'])) unset($_SESSION['cur_get_mining']);
		craft_setFunc($user_id,$craft_index);
		set_delay_reason_id($user_id,$id_reason);
		// ��������� ����������� ������. ��� � ����� ������ ���������� �� ������ �����, �� ��� ��.
		// ������ �� ����� ��, ��� �� � ���������� ��� � ���������.
		ForceFunc($user_id,func_craft);
		if (isset($_GET['mes']))
		{
			setLocation("../craft.php?mes=".$mes);
		}
		else
		{
			setLocation("../craft.php");
		}
	}
	elseif (!isset($_SESSION['captcha']) OR !isset($_POST['digit']) OR $_POST['digit']!=$_SESSION['captcha'])
	{
		echo '�� '.echo_sex('����','�����').' ������������ ���';
	}
	elseif ($razresheno==1)
	{
		echo '��� ����';
	}
	else
	{
		echo '!';
	}
}
else
{
	$razresheno = 1;

	//�������� ����
	if ($building['race']!=0 and $building['race']!=$char['race'])
	{
		echo'���� ���� �� ����� ����� ��������';
		$razresheno = 0;
	}


	//�������� ������
	if ($building['clevel']!=0 and $building['clevel']>$char['clevel'])
	{
		echo'��������� '.$building['clevel'].' ������� ������';
		$razresheno = 0;
	}

	//�������� ������������ �������� � �����
	if ($building['item']>0)
	{
		$selitem = myquery("SELECT * FROM game_items WHERE user_id=$user_id AND used!=0 AND ref_id=0 AND item_id='".$building['item']."' AND priznak=0");
		if (mysql_num_rows($selitem)>0)
		{
		}
		else
		{
			echo'��������� � ����� ������� '.mysqlresult(myquery("SELECT name FROM game_items_factsheet WHERE id=".$building['item'].""),0,0).'';
			$razresheno = 0;
		}
	}
	
	if ($building['include']=='lumberjack')
	{
		if (!checkCraftTrain($user_id,4))
		{
			echo '<br /><br />�� �� ������ ������� ��������� ��������! �� ������ ������� �� � ������ � ������� ���������.<br />���� ��������� ���������� ���� ���������� ���� ��� ��� � 30 �����.<br /><br />';
			$razresheno = 0;
		}
	}
	if ($building['include']=='stonemason')
	{
		if (!checkCraftTrain($user_id,5))
		{
			echo '<br /><br />�� �� ������ ������� ��������� ����������! �� ������ ������� �� � ������ � ������� ���������.<br />���� ��������� ���������� ���� ���������� ���� ��� ��� � 30 �����.<br /><br />';
			$razresheno = 0;
		}
	}
	
	
	if ($razresheno==1)
	{
		echo '��� ������ ������ ����� ��������� ���� ��� <br>� ����� ������ "���������� �� ������"<br>';
		echo '<br><img src="captcha_new/index.php?'.time().'">';
		echo '<form autocomplete="off" action="act.php?func=main&act=02" method="POST" name="captcha"><br>
		<input id="input_digit" type="text" size=6 maxsize=6 name="digit"><br /><br />
		<input type="submit" name="subm" value="���������� �� ������">
		</form>
		<script>
		el = document.getElementById(\'input_digit\');
		el.focus();
		</script>';
	}
}

if (function_exists("save_debug")) save_debug(); 

?>