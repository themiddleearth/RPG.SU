<?php
$eliksir = CreateArrayForCraftEliksir();
add_exp_for_craft($user_id, 2);
if ($rab['eliksir']>=0 AND $rab['eliksir']<sizeof($eliksir))
{
	//����� ������������
	$i = $rab['eliksir'];
	$change_weight = 0;
	//��������� �������
	$Item = new Item();
	$Item->add_user($eliksir[$i]['item_id'], $user_id);	
	myquery("delete from craft_build_rab where user_id=$user_id"); 
	setCraftTimes($user_id,2,1,1);
	$mes='�� ������� '.echo_sex('����������','�����������').' �����: <font color=red size=2>'.$eliksir[$i]['name'].'</b></font>';	
}
else
{
	$mes='����� �� '.echo_sex('������','������').' ������� �������. �� � ���� ���-�� �� ����������, � �� ������ �� '.echo_sex('������','�������').'.';
}
if (isset($_GET['house']))
{
	$option = 18;
	if (domain_name=='localhost') $option=19;
	$url = 'lib/town.php?option='.$option.'&part4&add=21&mes='.$mes;
	setLocation($url);
}
else
{
	$url = 'quest/alchemist.php?begin&mes='.$mes;
	setLocation($url);
}
?>
