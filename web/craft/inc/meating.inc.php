<?php
//����������� ��� �������

if (($hod==0)AND($timeout==0))
{
	$sel_corpse = myquery("SELECT * FROM craft_resource_user WHERE user_id=$user_id AND col>0 AND res_id=$id_resource_olencorpse");
	$odet_knife = 0;
	$sel = myquery("SELECT id FROM game_items WHERE item_id=$id_item_knife AND used=21 AND user_id=$user_id AND priznak=0 AND item_uselife>0");
	if ($sel!=false AND mysql_num_rows($sel)>0)
	{
		$odet_knife = 1;
	}

	if (!checkCraftTrain($user_id,9))
	{
		echo '<br /><br />�� �� ������ ������� ��������� ��������! �� ������ ������� �� � ������ � ������� ���������.<br />���� ��������� ���������� ���� ���������� ����, ��� ��� � 30 �����.<br /><br />';
	}
	elseif ($sel_corpse!=false AND mysql_num_rows($sel_corpse)>0)
	{
		if ($odet_knife!=1)
		{
			echo '��� �������� ���� ����� ���������� ����� � ���� ����������� ���<br /><br /><br />';
		}
		else
		{
			if (!isset($_GET['meat']))
			{
				if (isset($_GET['mes'])) echo ('<b><font color="#C0FFC0">'.$_GET['mes'].'</font></b><br /><br />');
				$href = "?option=".$option."&part4&add=".$_GET['add'].'&meat';
				echo '<a href="'.$href.'">��������� ���� �����</a><br /><br /><br />';
			}
			else
			{
			   //�������� ������. ����� � ��� �����
				if (isset($_SESSION['captcha']) and isset($_POST['digit']) and $_POST['digit']==$_SESSION['captcha'] and checkCraftTrain($user_id,9))
				{
					unset($_SESSION['captcha']);
					//�������� ����� �����
					craft_setFunc($user_id,8);
					set_delay_reason_id($user_id,33);
					if (domain_name == 'testing.rpg.su' or domain_name=='localhost') 
					{
						$dlit=5;
					}
					else
					{
						$dlit = 120;
					}
					$build_id='meating';
					
					myquery("DELETE FROM craft_build_rab WHERE user_id=$user_id");
					myquery("INSERT INTO craft_build_rab (user_id,build_id,date_rab,dlit) VALUES ($user_id,'$build_id',".time().",$dlit)");
					ForceFunc($user_id,func_craft);
					setLocation("../craft.php");
				}
				else
				{
					echo '��� ������ ������ ����� ��������� ���� ��� <br>� ����� ������ "������ ������ � ����������� ����"<br>';
					echo '<br><img src="../captcha_new/index.php?'.time().'">';
					echo '<form autocomplete="off" action="" method="POST" name="captcha"><br>
					<input type="text" size=6 maxsize=6 name="digit"><br>
					<input type="submit" name="subm" value="������ ������ � ����������� ����">
					</form>';
				}
			}
		}
	}
	else
	{
		echo '��� ������ � ����������� ���� ���� ����� � ����� ���� �����!<br /><br /><br />';
	}
}
elseif ($hod-time()+$timeout<=0)
{
	include(getenv("DOCUMENT_ROOT")."/craft/inc/meating_endtime.inc.php");
}
else
{
	if (($hod>0) AND ($hod<=time()))
	{
		//��� �������� ������  
		echo'�� '.echo_sex('�����','������').' ��������� ���� ����� � ����������� ����'; 
		echo '<br>�� ����� ������ ��������: <font color=ff0000><b><span id="timerr1">'.($hod+$timeout-time()).'</span></b></font> ������</div> 
		<script language="JavaScript" type="text/javascript">
		function tim()
		{
			timer = document.getElementById("timerr1");
			if (timer.innerHTML<=0)
				location.reload();
			else
			{
				timer.innerHTML=timer.innerHTML-1;
				window.setTimeout("tim()",1000);
				if (timer.innerHTML%120==0)
				{
					location.reload();
				}
			}
		}
		tim();
		</script>';
	} 
}
?>     