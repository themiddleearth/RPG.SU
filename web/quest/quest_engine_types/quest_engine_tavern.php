<?PHP
require_once('inc/quest_define.inc.php');
include("inc/standart_func.lib.php");

$quest_user=mysql_fetch_array(myquery("SELECT * FROM quest_engine_users WHERE user_id='$user_id' AND quest_type=5 AND done=0 AND par1_value=".$char['map_name']." AND par2_value=".$town." AND par3_value=".$char['map_xpos']." AND par4_value=".$char['map_ypos']." "));

include("inc/standart_vars.inc.php");

echo '<TABLE bgcolor="#223344" align="center" width=100%><tr><td><center>&nbsp;';
if(!isset($quest_answer))
{	
	
    QuoteTable('open');
    echo '<TABLE bgcolor="#223344" align="center" width=100%><tr><Td bgcolor="#223344"><div align=left><font color=#BAFBB5 size=3><BR><center>����...</center><HR align="center" noshade size="2" width="80%"><BR><BR>';	
    $text=myquery("SELECT text FROM quest_engine_topics WHERE topic_id=".$quest_user['quest_topic_id']." AND owner_id=".$quest_user['quest_owner_id']." AND action_type=31 AND quest_type=".$quest_user['quest_type']."");
    //list($text)=mysql_fetch_array($text);
    if(mysql_num_rows($text)>0)
	{
		list($text)=mysql_fetch_array($text);
	}
	else $text = "echo '�������� � �������, �� ������ �� ������ �������? � �� �� �������.';";
    eval($text);

    //	echo '�� ����� � �������. � ���� �� ��� �������, ��� ��������������� ������ ��� ������� �������. ��� ��������� ���� ��������� ����� �����, � ������� ����� ����� �������� ������ � ��������� ����. ����� ������, �� ��� ��������. ������� ����� �����, ������ ������� ��������, �� ����� ������� �� ������ �����. � ��������� ���� �������: "����������, �.�. ���������� ������������ ������������������ ���������� �� ������� � ���������� ���������� �������� ������������� �������������� � ���������� ��������������� � ������������ ���������� � ������� � �� ������������������ ��������� � ����������� �� ������������� ��������� ��-���: ����� � ���(������.). <b>� ������ ������������ �������� ��������� ����������� � ������������ ������� �� �������� �������� � �������� ���������� ��� ����� �������. �������� ��������� ������� ��� ������������ �������, ����� �������� ��������� �� ����� ��������� � ��������� ���������� ����������������� ����������� � ����������� � ������ ������ ������� ��������� ����������, � ������ ������������ "�������". ��������������� ���������� ���������� ������ ���������������� ����������� �� ���������� ������������������ ������.</b> " �������� ��-�� ������ �� ������ ����� ������� �� ���� ����� �� ������������ �� ��������� � ������� � ��� � ���������������, �� �� �� �������� ��� �������.';	

    echo '</td></tr><tr><td bgcolor="#223344" align="center"><br><br><br><HR align="center" noshade size="2" width="80%"><br><br>';

    echo '<font color=yellow size=4>��������:';	
    echo '<form action="" method="post"><input name="town_id" type="hidden" value="'.$town.'"><input name="quest_answer" type="hidden" value=1>
	<input name="answer" type="submit" value="��, ��� �. *������ �����*" style="COLOR: #������; FONT-SIZE: 9pt; FONT-FAMILY: Verdana; BACKGROUND-COLOR: #000000"><br>
	</form></div></td></tr></table>';	
    QuoteTable('close');
}
elseif ($quest_answer==1)
{	
	$sending=myquery("SELECT id,item_uselife FROM game_items WHERE user_id='$user_id' AND item_id='$id_item_posylka' AND item_for_quest=".$quest_user['quest_owner_id']."");
	if(mysql_num_rows($sending)>0)
	{
	    if($quest_user['quest_finish_time']<time())
	    {
		    //������, ��������o	
		    QuoteTable('open');
		    echo '<TABLE bgcolor="#223344" align="center" ><tr><Td bgcolor="#223344"><div align=left><font color=#FBB5B5 size=3><BR><center>����...</center><HR align="center" noshade size="2" width="80%"><BR><BR>';
		    
		    $text=myquery("SELECT text FROM quest_engine_topics WHERE topic_id=".$quest_user['quest_topic_id']." AND owner_id=".$quest_user['quest_owner_id']." AND action_type=33 AND quest_type=".$quest_user['quest_type']."");
	        //list($text)=mysql_fetch_array($text);
	        if(mysql_num_rows($text)>0)
			{
				list($text)=mysql_fetch_array($text);
			}
		else $text = "echo '�������� � �������, ������� ����������. � �� �� �������.';";
	        eval($text);
		    //echo ' - <b>�� ��������, ������� ��� �����! ��-�� ��� � ��������� ���������, �� ������� ����������� ����������� ��������� ������ �������� � �������������� ����������, ������������������ �� �������� ������������� �����������, ��������� ������������ � ��������� ����������, ��������������� ����������� � �������������� �������������� ��������� ������, �������� ����������� ������� ���������� ������ �����, ��� ������������ � ��������� ���������� �����������, � ����� �������� ������������ ���������� � ������� ����������� ���������!!!</b> - ����������� �������� ����������. <br><br> ��, ��� �� �����, �� ������ � ��� ����� � ������ ������ � �������������, ��� �� ���������, ���� � �� �������.';	
		    echo '</td></tr><tr><td bgcolor="#223344" align="center"><br><br><br><HR align="center" noshade size="2" width="80%"><br>';
	        
		    myquery("UPDATE quest_engine_users SET done=2 WHERE user_id='$user_id' AND quest_type=5 AND done=0 AND par1_value=".$char['map_name']." AND par2_value=".$town." AND par3_value=".$char['map_xpos']." AND par4_value=".$char['map_ypos']." ");
	    }
	    else 
	    {
		    QuoteTable('open');
		    echo '<TABLE bgcolor="#223344" align="center" ><tr><Td bgcolor="#223344"><div align=left><font color=#BAFBB5 size=3><BR><center>����...</center><HR align="center" noshade size="2" width="80%"><BR><BR>';
		    
		    $text=myquery("SELECT text FROM quest_engine_topics WHERE topic_id=".$quest_user['quest_topic_id']." AND owner_id=".$quest_user['quest_owner_id']." AND action_type=32 AND quest_type=".$quest_user['quest_type']."");
		    //list($text)=mysql_fetch_array($text);
	        if(mysql_num_rows($text)>0)
			{
				list($text)=mysql_fetch_array($text);
			}
			else $text = "echo '�������� � �������, ������� ���������. � �� �� �������.';";
		    eval($text);
		    //echo ' - <b>��� �������!</b> - ������ ����������. - <b>��� ��� �� �����, ��� � ��, ��� ������ � ������������� ������� �� ������������ ����� ����������, � ����� �������� ����������� �������. ���� � ������ ����������� ���� ���� ��������������, ���� ���������������������.  ������ �������� ����������� ������ � ��������������� (���)� ���������������������� (���).  ��������� ��������� �������� ����� ����������� ��������� ����������. � ����������� ������� ������ ��� ���� ���������. ���� �� ��� ��������� � ������ �����������, ������ �������� ���������� ������������ ������. ������ ���������� ��� ������������� ������ �������. � ������� � �������������� ������ ��������������� ����������� ������. �������������, ���������� � ��������� ��� ������������� ���������� ����������� ����� ����� �������� ���������� ���������. ����������, ���������� � ����� ��������� ��������� �������, �������� ����� ������� ���������� � ����������. ����� �������, ��� ������� �� ��������������� ����������� ���� � ������ ���� ����������������������, ������ �� ������� �������� �����-�� ���� �� ������� ��������� � ������,  �������, ������ ��� �����. ������������� ��� ������� �� ���������, ������������� �������, ���������� � ������� �������� � �� 77 �� ���������� ��������� �����������. � ��� ������ ��� ������ � ������ (�) � ������(�), ��� ���������� � ������� (�) � ����� (�). � ������� � � � ��������� ������, ��� � � �!</b< <br><br> �� ������ � ��� ����� � ������ ������ � �������������, ��� �� ���������  �������.';	
		    echo '</td></tr><tr><td bgcolor="#223344" align="center"><br><br><br><HR align="center" noshade size="2" width="80%"><br>';
	
	
		    /*myquery("DELETE FROM game_items WHERE user_id='$user_id' AND item_id=$id_posylka AND item_for_quest=".$quest_user['quest_owner_id']."");
	        $Item = new Item();
	        $Item->add_user($id_item_letter,$user_id,0,$quest_user['quest_owner_id']);*/
		    myquery("UPDATE quest_engine_users SET done=1 WHERE user_id='$user_id' AND quest_type=5 AND done=0 AND par1_value=".$char['map_name']." AND par2_value=".$town." AND par3_value=".$char['map_xpos']." AND par4_value=".$char['map_ypos']." ");
	
	    }
	    
	    //������� ������� � ����� ������
	    list($sending_id,$weight)=mysql_fetch_array($sending);
	    $Item = new Item();
	    $Item = new Item($sending_id);
		$Item->admindelete();
		//� ����� ���
		//myquery("UPDATE game_users SET CW = CW-'$weight' WHERE user_id = ".$user_id."");
	       
	    $new_id = $Item->add_user($id_item_letter_complete,$user_id,0,$quest_user['quest_owner_id']);
	    //if($new_id[0]==1)
	    $new_id = $new_id[1];
	    //�������� ���
	    $weight = $weight=(mt_rand(10,30))/100;
	    myquery("UPDATE game_items SET item_uselife = '$weight' WHERE id = '$new_id'");
	    //���
	    myquery("UPDATE game_users SET CW = CW+'$weight' WHERE user_id = ".$char['user_id']."");
	        
	    echo '<br><br>';
	    echo '<form action="" method="post"><input name="town_id" type="hidden" value="'.$town.'"><BLOCKQUOTE><input name="quest_fin" type="submit" value="��������� � ����� �����" style="COLOR: #������; FONT-SIZE: 9pt; FONT-FAMILY: Verdana; BACKGROUND-COLOR: #000000"></BLOCKQUOTE></form></td></tr></table>';
	    QuoteTable('close');	
	}else 
	{
		QuoteTable('open');
		echo '<TABLE bgcolor="#223344" align="center" ><tr><Td bgcolor="#223344"><div align=left><font color=#FBB5B5 size=3><BR><center>����...</center><HR align="center" noshade size="2" width="80%"><BR><BR>';
		echo '�� � ���� �� ��� �������!';
		echo '</td></tr><tr><td bgcolor="#223344" align="center"><br><br><br><HR align="center" noshade size="2" width="80%"><br>';
	}
}
echo '</center></tr></td></table>';
 
?>