<?
$dirclass = "../class";
require('../inc/config.inc.php');
include('../inc/lib.inc.php');
include('../inc/template.inc.php');
DbConnect();

//������� ������� ���

echo '<form action="" name="frm" method="post">��� ������: 
<input type="text" name="name" value=""><input type="submit" name="submit" value="����������"><br /><br /><br /><br /><br /><input type="submit" name="submit_all" value="���������� ����">';

if (isset($_POST['submit']) OR isset($_POST['submit_all']))
{
    if (isset($_POST['submit']))
    {
	    $sel = myquery("SELECT user_id FROM game_users WHERE name='".$_POST['name']."'");
	    if ($sel==false AND mysql_num_rows($sel)==0) $sel = myquery("SELECT user_id FROM game_users_archive WHERE name='".$_POST['name']."'");
    }
    else
    {
        $sel = myquery("(SELECT user_id FROM game_users) UNION (SELECT user_id FROM game_users_archive)");
    }
    $kol = 0;
	while ($user = mysql_fetch_array($sel))
	{
		$weight = 0;
		$i = 0;
		//�������� ������ ������� ��� ������ ������� - ��� �������������� ��������
		$sel_items = myquery("SELECT game_items_factsheet.weight,game_items_factsheet.name,game_items.count_item,game_items_factsheet.type FROM game_items,game_items_factsheet WHERE game_items.user_id=".$user['user_id']." AND game_items.priznak=0 AND game_items.item_id=game_items_factsheet.id AND game_items_factsheet.type!=95");
		if ($sel_items!=false and mysql_num_rows($sel_items)>0)
		{
			while (list($w,$ident,$kol,$type)=mysql_fetch_array($sel_items))
			{
				$i++;
				if ($type==13 OR $type==12 OR $type==21 OR $type==19 OR $type==22 OR $type==95)
				{
					$w = $w*$kol;
				}
				$weight+=$w;
				if (isset($_POST['submit']))
				{
					echo  '<br>'.$i.'. '.$ident.' - '.$w.'.('.$weight.') ';
				    if ($type==13 OR $type==12 OR $type==21 OR $type==19 OR $type==22 OR $type==95)
				    {
					    echo '[���������� ��������� - '.$kol.']';
				    }
                }
			}
		}
		$sel_items = myquery("SELECT * FROM craft_resource_user WHERE user_id=".$user['user_id']."");
		if ($sel_items!=false and mysql_num_rows($sel_items)>0)
		{
			while ($res=mysql_fetch_array($sel_items))
			{
				$i++;
				$ress = mysql_fetch_array(myquery("SELECT * FROM craft_resource WHERE id=".$res['res_id'].""));
				$weight=$weight+$ress['weight']*$res['col'];
				if (isset($_POST['submit']))
				{
					echo '<br>'.$i.'. '.$ress['name'].' - '.$res['col'].'*'.$ress['weight'].'='.($ress['weight']*$res['col']).'.('.$weight.')';
				}
			}
		}
		//inq! �������� ��� ������ �������. ��������, ����� ���������� ���������� ���� ������ � �������� �� ��� ��������.
		//�� UNION �� ������ - ������ ���� �����.
		$sel_items = myquery("SELECT game_items.item_uselife,game_items_factsheet.name FROM game_items,game_items_factsheet WHERE game_items.user_id=".$user['user_id']." AND game_items.priznak=0 AND game_items.item_id=game_items_factsheet.id AND game_items_factsheet.type=95");
		if ($sel_items!=false and mysql_num_rows($sel_items)>0)
		{
			while (list($w,$ident)=mysql_fetch_array($sel_items))
			{
				$weight+=$w;
				if (isset($_POST['submit']))
				{
					echo '<br>'.$ident.' - '.$w.'.('.$weight.')';
				}
			}
		}
		myquery("UPDATE game_users SET CW=$weight WHERE user_id=".$user['user_id']."");
		myquery("UPDATE game_users_archive SET CW=$weight WHERE user_id=".$user['user_id']."");
        $kol++;
        if (isset($_POST['submit'])) echo  '<br />����� ��� = '.$weight.'<br />';
	} 

	echo '<br><br><br>���������. ��������� '.$kol.' �������';
}
?>