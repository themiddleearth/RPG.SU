<?php

if (function_exists("start_debug")) start_debug();

if ($char['hide']==0 OR $char['clan_id']==1)
{

    echo '<table><tr><td>';

    include('craft/buildings.inc.php');

    if (isset($_GET['use_svitok_hranitel']) AND is_numeric($_GET['use_svitok_hranitel']) AND $char['map_name']==691)
    {
        $sel_check = myquery("SELECT * FROM game_items WHERE id=".$_GET['use_svitok_hranitel']." AND item_id=".item_id_svitok_hranitel." AND user_id=$user_id AND priznak=0 AND used=0");
        if (mysql_num_rows($sel_check)>0)
        {
            $Item = new Item($_GET['use_svitok_hranitel']);
            $Item->admindelete();
            
            myquery("UPDATE game_map SET town=".id_portal_tuman.", to_map_name=".id_map_tuman.", to_map_xpos=0, to_map_ypos=0 WHERE xpos=19 and ypos=19 and name='".$char['map_name']."'");
            
            QuoteTable('open');
            echo '����������� ������ ���������, �� ����������� ������ � �������� ����!';
            QuoteTable('close');
        }
    }
    
    //������� ������� � ������ �� ����� (����� ���� ������ ���� - ��� 1 �����, ��� 1 ������)
    $sel=myquery("select * from game_map where town!=0 and xpos='".$char['map_xpos']."' and ypos='".$char['map_ypos']."' and name='".$char['map_name']."'");
    if (mysql_num_rows($sel))
    {
       while ($gorod=mysql_fetch_array($sel))
       {
           $sel=myquery("select town,name,text,clan,user,race,time,gp,id,timestart,exit_lab from game_obj where id='".$gorod['town']."'");
           if(($gorod['to_map_name']!=0)AND(mysql_num_rows($sel)>0))
           {
                list($town,$name,$text,$clan,$user,$race,$time,$gp,$obj_id,$timestart,$exit_lab)=mysql_fetch_array($sel);
                $user_race = mysql_result(myquery("SELECT name FROM game_har WHERE id=".$char['race'].""),0,0);
				if($clan==0 or $clan=='') $clan=$char['clan_id'];
                if($user=='') $user=$char['user_id'];
                $result_items = myquery("SELECT * from game_wm WHERE user_id=$user_id AND type=5");
				if($race=='' or mysql_num_rows($result_items)>0) $race = $user_race;

                $pass_time = true;
                
                if($gp=='') $gp='0';
                $a=explode(",",$clan);
                $b=explode(",",$user);

                $condition_text = '';
                if ($timestart!='')
                {
                    $d = explode(" ",$timestart);
                    $dat = explode(".",$d[0]);
                    $tim = explode(":",$d[1]);
                    $timestamp_open = mktime($tim[0],$tim[1],0,$dat[1],$dat[0],$dat[2]);
                    if(time() < $timestamp_open)
                    {
                        $tme='no';
                    }
                    else
                    {
                        $tme='ok';
                    }
                }
                else
                {
                    $tme='ok';
                }

                if ($time!='' and $tme!='no')
                {
                    $d = explode(" ",$time);
                    $dat = explode(".",$d[0]);
                    $tim = explode(":",$d[1]);
                    $timestamp_open = mktime($tim[0],$tim[1],0,$dat[1],$dat[0],$dat[2]);
                    if(time() <= $timestamp_open)
                    {
                        $condition_text.='<div style="color:red;font-weight:800;">������ ��������� '.$time.'</div><br>';
                        $tme='ok';
                    }
                    else
                    {
                        $tme='no';
                        $pass_time = false;
                    }
                }
                elseif ($tme!='no')
                {
                    $tme='ok';
                }

                $item_id_need = 0;
                $sel_nom = myquery("SELECT DISTINCT nomer FROM game_obj_require WHERE obj_id=$obj_id");
                if (mysql_num_rows($sel_nom))
                {
                    $flag = 0;
                    $str = '<i>��� ������� ���������� �������: <br>';
                    $vsego_nom = mysql_num_rows($sel_nom);
                    $cur_nom = 0;
                    while (list($nom)=mysql_fetch_array($sel_nom))
                    {
                        $cur_nom++;
                        $sel_cond = myquery("SELECT * FROM game_obj_require WHERE nomer=$nom AND obj_id=$obj_id");
                        $vsego_cond = mysql_num_rows($sel_cond);
                        $cur_cond = 0;
                        $true_cond = 0;
                        while ($cond = mysql_fetch_array($sel_cond))
                        {
                            $cur_cond++;
                            switch ($cond['type'])
                            {
                                case 1:
                                    $str.='������� ������ ';
                                    $par = 'clevel';
                                break;
                                case 2:
                                    $str.='���������� �������� ����� ';
                                    $par = 'GP';
                                break;
                                case 3:
                                    $str.='������� �������� ';
                                break;
                                case 34:
                                    $str.='������ ������� ';
                                break;
                                case 4:
                                    $par = 'vsadnik';
                                    $str.='������� ���� ';
                                break;
                                case 5:
                                    $par = 'HP_MAX';
                                    $str.='����. �������� ';
                                break;
                                case 6:
                                    $par = 'MP_MAX';
                                    $str.='����. ���� ';
                                break;
                                case 7:
                                    $par = 'STM_MAX';
                                    $str.='����. ������� ';
                                break;
                                case 8:
                                    $par = 'STR';
                                    $str.='���� ������ ';
                                break;
                                case 9:
                                    $par = 'NTL';
                                    $str.='��������� ������ ';
                                break;
                                case 10:
                                    $par = 'PIE';
                                    $str.='�������� ������ ';
                                break;
                                case 11:
                                    $par = 'SPD';
                                    $str.='�������� ������ ';
                                break;
                                case 12:
                                    $par = 'DEX';
                                    $str.='������������ ������ ';
                                break;
                                case 33:
                                    $par = 'VIT';
                                    $str.='������ ������ ';
                                break;                                
                                case 19:
                                    $par = 'win';
                                    $str.='���������� ����� ';
                                break;
                                case 20:
                                    $par = 'lose';
                                    $str.='���������� ��������� ';
                                break;
                                case 21:
                                    $par = 'arcomage_win';
                                    $str.='�������� � ��� ����� ';
                                break;
                                case 22:
                                    $par = 'arcomage_lose';
                                    $str.='��������� � ��� ����� ';
                                break;
                                case 23:
                                    $par = 'maze_win';
                                    $str.='�������� ���������� ';
                                break;                                
                                case 101:
                                    $par = 'sklon';
                                    $str.='���������� ������ ';
                                break;
                            }
                            if ($cond['type']==3)
                            {
                                list($id_item) = mysql_fetch_array(myquery("SELECT id FROM game_items_factsheet WHERE name='".$cond['value']."'"));
                                $item_id_need = $id_item;
                                $str.=' - '.$cond['value'];
                                $check_item = myquery("SELECT * FROM game_items WHERE user_id=$user_id AND priznak=0 AND item_id=$id_item");
                                if (mysql_num_rows($check_item)>0) $true_cond++;
                            }
                            elseif ($cond['type']==34)
                            {
                                list($name_item) = mysql_fetch_array(myquery("SELECT name FROM game_items_factsheet WHERE id=".$cond['value'].""));
                                $str.=' - '.$name_item;
                                $check_item = myquery("SELECT COUNT(*) FROM game_items WHERE user_id=$user_id AND priznak=0 AND item_id='".$cond['value']."' AND used>0");
                                if (mysql_num_rows($check_item)>0) $true_cond++;
                            }
                            elseif ($cond['type']==4)
                            {
                                list($name_horse) = mysql_fetch_array(myquery("SELECT nazv FROM game_vsadnik WHERE id=".$cond['value'].""));
                                $str.=' - '.$name_horse;
                                if ($char['vsadnik']==$cond['value']) $true_cond++;
                            }
                            elseif ($cond['type']==101)
                            {
                                if ($cond['value']==1)
                                {
                                    $str.=' - �����������';
                                }
                                if ($cond['value']==2)
                                {
                                    $str.=' - �������';
                                }
                                if ($cond['value']==3)
                                {
                                    $str.=' - ������';
                                }
                                if ($char['sklon']==$cond['value']) $true_cond++;
                            }
                            elseif ($cond['type']==100)
                            {
                                if (isset($_REQUEST['keyword']))
                                {
                                    if (strtolower(trim($_REQUEST['keyword']))==strtolower(trim($cond['value'])))
                                    {
                                        $true_cond++;
                                        $pass = $cond['value'];
                                    }
                                    else
                                    {
                                        $str.= '�� '.echo_sex('������','�������').' ������������ ������� �����!';
                                    }
                                }
                                else
                                {
                                    $str.= '<br /><form autocomplete="off" action="" method="POST">���� ���� ������ ���������� ������� �����:
                                    <br />������� �����: <input type="text" size="25" maxsize="50" value="" name="keyword">&nbsp;&nbsp;<input type="submit" value="������ �����"></form>';
                                    $ask_pass = 1;
                                }
                            }
                            else
                            {
                                switch ($cond['condition'])
                                {
                                    case 1:
                                        $str.=' <=';
                                        if ($char[$par]<=$cond['value']) $true_cond++;
                                    break;
                                    case 2:
                                        $str.=' <';
                                        if ($char[$par]<$cond['value']) $true_cond++;
                                    break;
                                    case 3:
                                        $str.=' =';
                                        if ($char[$par]==$cond['value']) $true_cond++;
                                    break;
                                    case 4:
                                        $str.=' >=';
                                        if ($char[$par]>=$cond['value']) $true_cond++;
                                    break;
                                    case 5:
                                        $str.=' >';
                                        if ($char[$par]>$cond['value']) $true_cond++;
                                    break;
                                    case 6:
                                        $str.=' <>';
                                        if ($char[$par]!=$cond['value']) $true_cond++;
                                    break;
                                }
                                $str.=' '.$cond['value'];
                            }
                            if ($cur_cond<$vsego_cond) $str.=' <strong>�</strong> ';
                        }
                        if ($cur_nom<$vsego_nom) $str.='<br><strong>���</strong><br>';
                        if ($true_cond==$vsego_cond OR ((isset($ask_pass)) AND ($true_cond+1==$vsego_cond))) $flag = 1;
                    }
                    $condition_text.='<p>'.$str.'</i></p><br />';
                    if ($flag==0) $tme='net';
                }

                while (list($val,$id)=each($a))
                {
                    if($char['clan_id']==$id and $user_race==$race)
                    {
                        while (list($val,$id)=each($b))
                        {
                            if($char['user_id']==$id)
                            {                               
                                if ($pass_time)
                                {
                                    echo nl2br($text).'<br>';
                                    echo $condition_text;
                                }
								
                                if (($char['GP'] >= $gp or $gp==0) and !isset($ask_pass) and $tme=='ok')
                                {
                                    echo'<a href="act.php?chage=yes';
                                    if (isset($pass))
                                    {
                                        echo '&keyword='.$pass;
                                    }
                                    echo '">'.$name.'</a><br>';
                                    if ($gp!='0') echo'����� �� ������ <font color=ff0000><b>'.$gp.'</b></font> �������!';
                                }
                            }
                        }
                    }
                }
           }
           else
           {
                $sel=myquery("select * from game_gorod where town='".$gorod['town']."'");
                $gorod=mysql_fetch_array($sel);
                $clan = $gorod['clan'];
                $race = $gorod['race'];
                if($gorod['clan']==0 or $gorod['clan']=='') $clan=$char['clan_id'];
                if($gorod['race']==0) $race=$char['race'];
                $dostup=1;
                $race1 = myquery("SELECT race FROM game_har WHERE id=".$char['race']."");
                if (mysql_num_rows($race1))
                {
                    list($race1) = mysql_fetch_array($race1);
                    $race1 = 'enter_'.$race1;
                    if (!isset($gorod[$race1])) $dostup=1;
                    elseif ($gorod[$race1]!='1') $dostup=0;
                }
                if ($gorod['rustown']!='' AND $dostup!=1)
                {
                    echo '<font face="Tahoma" size="3">'.$gorod['rustown'].'</font><br><font color=#FF0000><b>������ � ���� ����� ��� ����� ���� ������!</b></font>';
                }

                if(($char['clan_id']==$clan or $clan==0) and $char['race']==$race)
                {
			echo'<font face="Tahoma" size="3">';
			if ($gorod['rustown']!='' AND $dostup==1)
			{
				$clan_nalog=0;
				if ($gorod['clan']>1)
				{
					$test_nalog = myquery("SELECT id, summa FROM game_clans_taxes WHERE clan_id=".$clan." Order By id DESC Limit 1");
					list($id,$clan_nalog)=mysql_fetch_array($test_nalog);
				}
				if ($user_time < $char['delay'] AND (!isset($char['block']) OR $char['block']==1))
					echo ('<font color="yellow">����� "'.$gorod['rustown'].'"</font>');
				elseif ($clan_nalog==0)
					echo ('<a href="lib/town.php">����� � ����� "'.$gorod['rustown'].'"</a>');
				else 
					echo ('����� <b><span style="color:yellow">'.$gorod['rustown'].'</span stylet></b> ������� ����� ���� �� ��� ������������� �� ��������������. � ����� � ��������� ������� �� ��������� ����� ����� ������ ������. ��������� ������� ������ ����� ���� ��� �������� ����� � <i>"���������� ������"</i>.');
			}
			echo ('</font><br>');
                }
		echo ' '.stripslashes(nl2br($gorod['opis'])).'';
            }
       }
    }

    echo '</td></tr><tr><td>';

    //�������� �� ������
    //������� ����� ��������� ������������ ������ � ����� �������� - 5 �����
    $last_time = time()-10*60;
    $questsel = myquery("SELECT * FROM game_quest WHERE map_name=".$char['map_name']." AND map_xpos=".$char['map_xpos']." AND map_ypos=".$char['map_ypos']." AND min_clevel<=".$char['clevel']." AND max_clevel>=".$char['clevel']." LIMIT 1");
    if (mysql_num_rows($questsel))
    {
	    $quest = mysql_fetch_array($questsel);
	    $check = mysql_result(myquery("SELECT COUNT(*) FROM game_quest_users WHERE user_id=$user_id AND quest_id=".$quest['id']." AND (last_time>=$last_time OR finish>=1)"),0,0);
	    if($char['map_name']==666)
        {
    	    $check=1;
        }
        if ($check==0)
        {
            if (isset($_SESSION['quest1_step']) AND $quest['id']==1)
            {
                if (
                $_SESSION['quest1_step']==8 OR
                $_SESSION['quest1_step']==15 OR
                $_SESSION['quest1_step']==25 OR
                $_SESSION['quest1_step']==28 OR
                $_SESSION['quest1_step']==31 OR
                $_SESSION['quest1_step']==33 OR
                $_SESSION['quest1_step']==35 OR
                $_SESSION['quest1_step']==37 OR
                $_SESSION['quest1_step']==39 OR
                $_SESSION['quest1_step']==41 OR
                $_SESSION['quest1_step']==43 OR
                $_SESSION['quest1_step']==45 OR
                $_SESSION['quest1_step']==52
                )
                {
                   unset($_SESSION['quest1_lose']);
			       echo '<br><div align=right><a href="quest/'.$quest['filename'].'?win_bot" target="game">���������� �����</a></div>';
                }
                elseif (
                $_SESSION['quest1_step']==7 OR
                $_SESSION['quest1_step']==14 OR
                $_SESSION['quest1_step']==24 OR
                $_SESSION['quest1_step']==27 OR
                $_SESSION['quest1_step']==30 OR
                $_SESSION['quest1_step']==32 OR
                $_SESSION['quest1_step']==34 OR
			    $_SESSION['quest1_step']==36 OR
                $_SESSION['quest1_step']==38 OR
                $_SESSION['quest1_step']==40 OR
                $_SESSION['quest1_step']==42 OR
                $_SESSION['quest1_step']==44 OR
                $_SESSION['quest1_step']==51
                )
                {
            	    $_SESSION['quest1_lose']=1;
				    echo '<br><div align=right><a href="quest/'.$quest['filename'].'?win_bot" target="game">���������� �����</a></div>';
                }
                else
                {
                    //������� �����
                    echo $quest['begin'];
				    echo '<br><div align=right><a href="quest/'.$quest['filename'].'?begin" target="game">'.$quest['vhod'].'</a></div>';
                }
            }
            elseif (isset($_SESSION['quest2_step']) AND $quest['id']==21)
            {
                if
                (
                    $_SESSION['quest2_step']==202 OR
                    $_SESSION['quest2_step']==204 OR
                    $_SESSION['quest2_step']==206 OR
                    $_SESSION['quest2_step']==208 OR
                    $_SESSION['quest2_step']==210 OR
                    $_SESSION['quest2_step']==212
                )
                   echo '<br><div align=right><a href="quest/'.$quest['filename'].'?win_bot" target="game">���������� �����</a></div>';
                elseif (
                $_SESSION['quest2_step']==201 OR
                $_SESSION['quest2_step']==203 OR
                $_SESSION['quest2_step']==205 OR
                $_SESSION['quest2_step']==207 OR
                $_SESSION['quest2_step']==209 OR
                $_SESSION['quest2_step']==211
                )
                {
            	    $_SESSION['quest2_lose']=1;
               	    echo '<br><div align=right><a href="quest/'.$quest['filename'].'?win_bot" target="game">���������� �����</a></div>';
                }
                else
                {
                    //������� �����
                    echo $quest['begin'];
			        echo '<br><div align=right><a href="quest/'.$quest['filename'].'?begin" target="game">'.$quest['vhod'].'</a></div>';
		        }
            }
            else
            {
                //������� �����
                echo $quest['begin'];
                echo '<br><div align=right><a href="quest/'.$quest['filename'].'?begin" target="game">'.$quest['vhod'].'</a></div>';
            }
        }
        else
        {
            if ($quest['id']!=1 AND $quest['id']!=21)
            {
     	        //������� �����
                echo $quest['begin'];
                //����� ������
                if($char['map_name']==666)
                {
                    //echo '<div style="postion:absolute;">';
                    //$rand_x = mt_rand(0,300);
                    //$rand_y = mt_rand(0,300);
                    $checksum = md5(mt_rand(0,1000)+$user_id+time());
                    $_SESSION['katorga_checksum_href'] = $checksum;
                    $_SESSION['right_knopka']=mt_rand(1,4);
                    //if ($char['map_xpos']==1 AND $char['map_ypos']==1)
                    //    echo '<div style="position:relative;top:'.$rand_x.'px;left:'.$rand_y.'px;"><a href="quest/'.$quest['filename'].'?id='.$checksum.'" target="game">'.$quest['vhod'].'</a></div>';
                    //else
                    echo '<form action="quest/'.$quest['filename'].'?id='.$checksum.'" method="post">';
                    for ($ind=1;$ind<=4;$ind++)
                    {
                        if ($_SESSION['right_knopka']==$ind)
                        {
                            echo '<br /><br /><input type="submit" name="prison_button'.$ind.'" value="������� +1 ������">';
                        }
                        else
                        {
                            echo '<br /><br /><input type="submit" name="prison_button'.$ind.'" value="������� -2 �������">';
                        }
                    }
                    echo '</form>';
                    //echo '<a href="quest/'.$quest['filename'].'?id='.$checksum.'" target="game">'.$quest['vhod'].'</a>';
                    //echo '</div>';
                }
                else
                    echo '<br><div align=right><a href="quest/'.$quest['filename'].'" target="game">'.$quest['vhod'].'</a></div>';
            }
        }
    }

    echo '</td></tr><tr><td>';

    //������ �������� �� ��������� ������
    include("quest/quest_engine_types/quest_engine_outside_print.php");

    //������� �������� �� �����
    $select=myquery("select * from game_shop where map='".$char['map_name']."' and pos_x='".$char['map_xpos']."' and pos_y='".$char['map_ypos']."' ");
    if (mysql_num_rows($select))
    {
        while ($shop=mysql_fetch_array($select))
        {
            echo "<b><font color=\"cccccc\">$shop[text]</font></b><br>";
            echo $shop['privet'];
		    echo '<br><div align=right><a href="shop/shop.php">'.$shop['vhod'].'</a></div>';
	    }
    }

    echo '</td></tr><tr><td>';

    //������� ����� �� �����
    $select=myquery("select * from game_mine where map='".$char['map_name']."' and pos_x='".$char['map_xpos']."' and pos_y='".$char['map_ypos']."' ");
    if (mysql_num_rows($select))
    {
        while ($shop=mysql_fetch_array($select))
        {
            echo "<b><font color=\"cccccc\">$shop[text]</font></b><br>";
            echo $shop['privet'];
            echo '<br><div align=right><a href="mine.php?option=vhod">����� � ����� "'.$shop['name'].'"</a></div>';
        }
    }
	
	//������� ������ ���   
	if (($char['map_name']==18 and $char['map_xpos']==26 and $char['map_ypos']==24) or ($char['map_name']==5 and $char['map_xpos']==22 and $char['map_ypos']==19))
	{		
		//��������� ��������� �������� �����
		$delay_song=60*60*1;
		$delay_move=60*2;
		echo '</td></tr><tr><td>';
		$img='http://'.img_domain.'/quest/singing_forest.gif';
		echo '<img src="'.$img.'">';
		echo '</td><td valign="top">';
		echo '������ ��� ������ ��������� �������� ������������ ������� �������. ����� ��� ������ ����� � ����������� ��������, ������ ����������� ���� �������, ������� ������� �������, �� �����, ������ ������� ������������ ����� ��������� ������. ����� �� �� ���� ���� ����, ��� ������� �������� ����� ���� ������ �����. �������������, ��� ������ ���� ��� ������� �����, �����, ����������, �������� � ����� ������ ����� ����������� ���� ����������. ������� ������� ����� ����� ��������, �� � ����� ��������� ������������ ������� �� ����� ��������� �����!';		
		$check=myquery("SELECT song_date FROM game_users_songs WHERE user_id='".$char['user_id']."' AND '".time()."'-song_date<'".$delay_song."'");
		if (mysql_num_rows($check)>0)
		{
			list($song_date)=mysql_fetch_array($check);
			echo '<br><br>� ���������, �� ��� ������������ ���������, ����� ����� ����� �����.<br>������������� � <b>'.date("H:i d.m.Y",($song_date+$delay_song)).'</b>';
		}
		elseif (!isset($_POST['song']))
		{
			echo '<br><br>���� ������ ����� ����� � ����� ����������, ������� ��������� ���� ��� � ������� ������ "����� �����"<br>';
			echo '<br><img src="captcha_new/index.php?'.time().'">';
			echo '<form autocomplete="off" method="POST" name="captcha"><br>
			<input id="input_song" type="text" size=6 maxsize=6 name="song"><br /><br />
			<input type="submit" name="subm" value="����� �����">
			</form><br />
			<script>
			el = document.getElementById(\'input_song\');
			el.focus();
			</script>';
		}
		elseif (isset($_SESSION['captcha']) AND $_POST['song']==$_SESSION['captcha'])
		{
			unset($_SESSION['captcha']);
			$song_data=myquery("SELECT song_date, prize FROM game_users_songs WHERE user_id='".$char['user_id']."'");  
			$prize=0;
			$prize_name="";
			if (mysql_num_rows($song_data)==0)
			{
				$prize=1;
				myquery("INSERT INTO game_users_songs (user_id, song_date, prize) VALUES ('".$char['user_id']."', ".time().", '1')");  
			}
			else
			{
				list($s_date, $s_prize)=mysql_fetch_array($song_data);
				if (time()>$delay_song+$s_date)
				{
					$prize=$s_prize+1;
					if ($prize==5) $new_prize=0;
					else $new_prize=$prize;
					myquery("UPDATE game_users_songs SET prize='".$new_prize."', song_date=".time()." WHERE user_id='".$char['user_id']."'");
				}
			}
			switch ($prize)
			{
				case 0: {echo '<br><br>� ���������, ���-�� ����� �� ��� � ����� ����� �� �������!'; break; }
				case 1: {$prize_name="�������� ��������"; break; }
				case 2: {$prize_name="������ ��������"; break; }
				case 3: {$prize_name="���������� ��������"; break; }
				case 4: {$prize_name="������� ��������"; break; }
				case 5: {$prize_name="���������� ��������"; break; }  
			}
			if ($prize_name<>"")
			{
				$check_item=myquery("SELECT id FROM game_items_factsheet WHERE name like '".$prize_name."'");
				if (mysql_num_rows($check_item)>0)
				{
					list($id)=mysql_fetch_array($check_item);
					$Item = new Item();
					$ar = $Item->add_user($id,$user_id);
					set_delay_info($char['user_id'], time() + $delay_move,1,1);
					echo '<br><br><i>�� ���������� ����� �� ��������� ������� <b>"'.$prize_name.'".</b></i><br>�������� ��� ������������ �����, �� �� ������ ���������� � ����� ��� 2 ������';
				}
			}
		}
		else
		{
			echo '<br><br>��� ����, ����� ����� ����� � ����� ����������, ���������� ������� ������ ���!';
		}
	}		
		
	echo '</td></tr><tr><td>';
    //������� �������� �� �����
    $obelisk_query = "select * from game_obelisk where map_name=".$char['map_name']." and map_xpos=".$char['map_xpos']." and map_ypos=".$char['map_ypos']." AND user_id=0 AND time_begin<=".time()." AND time_begin>0 AND type NOT IN (SELECT harka FROM game_obelisk_users WHERE type=0 AND time_end>=".time().") LIMIT 1";
    if (isset($_GET['obelisk']))
    {
        $prov = myquery($obelisk_query);
        if (mysql_num_rows($prov))
        {
            $obelisk = mysql_fetch_array($prov);
            $har['STR']='���� <b>����</b> �����������';
            $har['NTL']='���� <b>���������</b> ����������';
            $har['PIE']='���� <b>��������</b> �����������';
            $har['VIT']='���� <b>������</b> �����������';
            $har['DEX']='���� <b>������������</b> �����������';
            $har['SPD']='���� <b>��������</b> �����������';
            if (isset($har[$obelisk['type']]))
            {
                $str = $obelisk['type'];
                $add = floor($char[$str]*0.1);
                $harka = $har[$str];
                echo '�� '.echo_sex('���������','����������').' ������ � "'.$obelisk['name'].'"<br />
                � ����� �� '.echo_sex('������������','�������������').', ��� ��� �� �������� ��� ������ ���� ��������� ���������<br />
                ������� ������, ��� �� ������ ���������, �� ����� '.echo_sex('���������','����������').', ��� '.$harka.' �� '.$add.' '.pluralForm($add,'�������','�������','������').'.<br />
                � ��� �� '.echo_sex('�������','��������').' ����� �����, ������� ��� �� ��������:<br />
                - ���� ������, ��� ���� �������� ����� �������� ���� ������ � ������� ������ ���!<br />';
                myquery("UPDATE game_obelisk SET time_begin=0,time_end=0,user_id=$user_id,count_use=count_use+1 WHERE id=".$obelisk['id']."");
                myquery("INSERT INTO `game_obelisk_users` (`user_id` ,`harka` ,`time_end` ,`user_name` ,`value` ,`type` ) VALUES ($user_id,'$str',".(time()+24*60*60).",'".$char['name']."','$add',0)");
                myquery("UPDATE game_users SET $str=$str+$add WHERE user_id=$user_id");
            }
        }
    }
    $select=myquery($obelisk_query);
    if (mysql_num_rows($select))
    {
        while ($obelisk=mysql_fetch_array($select))
        {
            echo '<div>';
            switch ($obelisk['type'])
            {
                case 'STR':
                    $harka = '����';
                    echo "<img src=\"http://".img_domain."/obelisk/obelisk_krasniy.gif\" align=\"left\">";
                break;
                case 'DEX':
                    $harka = '������������';
                    echo "<img src=\"http://".img_domain."/obelisk/obelisk_siniy.gif\" align=\"left\">";
                break;
                case 'SPD':
                    $harka = '��������';
                    echo "<img src=\"http://".img_domain."/obelisk/obelisk_fiolet.gif\" align=\"left\">";
                break;
                case 'NTL':
                    $harka = '���������';
                    echo "<img src=\"http://".img_domain."/obelisk/obelisk_dark.gif\" align=\"left\">";
                break;
                case 'PIE':
                    $harka = '��������';
                    echo "<img src=\"http://".img_domain."/obelisk/obelisk_jeltiy.gif\" align=\"left\">";
                break;
                case 'VIT':
                    $harka = '������';
                    echo "<img src=\"http://".img_domain."/obelisk/obelisk_temniy.gif\" align=\"left\">";
                break;
            }
            echo "<b><font color=\"cccccc\">".$obelisk['name']."</font></b><br>";
            echo '<p align="justify">'.$obelisk['opis'].'</p>';
            echo '<br><div align=left><a href="?func=main&obelisk" target="game">���������� ������ ����� "'.$obelisk['name'].'"</a></div></div><br /><br />';
        }
    }

    echo '</td></tr></table>';
}
if (function_exists("save_debug")) save_debug();

?>