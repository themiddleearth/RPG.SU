<?php

//TODO:
// ������� ������ ����-����� �������� ��������� � ��� id ��������� � config.inc.php

// �������� ������:
// ����������� "���������" ���� � ��������� "����"

/*====!!=====
����� ����� (�������: game_npc_option)
(�������� ����� (1,2,3... -> �������� � �������: game_npc_set_option)
(�������������� ����� (1.1,1.2,2.1... -> �������� � �������: game_npc_set_option_value):
1 - �� �������������
2 - �� ����������
3 - ���� ������ ������
4 - ������������� ����
	4.1 - ����������� ��������
	4.2 - ������������ ��������
5 - ���� �� ������� �� ������ ������
	5.1 - ������� �� ������
6 - ��������� �����
	6.1 - ��� ����
	6.2 - ���������� �����
7 - ��� �� ������
	7.1 - ����������� �������
	7.2 - ������������ �������
8 - ��� �������� ������ ������
9 - ����� ���� ����� ������ ������
	9.1 - ����������� ���������
10 - ���� ���� ����� ���� ������
	10.1 - ����������� ���������
11 - ������������� �������������
	11.1 - ��� �������
	11.1 - ����������� �������������
	11.2 - ����������� ��������
12 - ����������� ������������� � ���
	12.1 - ��� �������������
13 - �������������� ���
14 - ���-��������
	14.1 - �������� �����
	14.2 - ���������� x
	14.3 - ���������� y
*/

	function gcd($n, $m)
	{
		$n=abs($n); 
		$m=abs($m);
		if ($n==0 and $m==0)
			return 1; //avoid infinite recursion
		if ($n==$m and $n>=1)
			return $n;
		return $m < $n ? gcd($n - $m, $n) : gcd($n, $m - $n);
	}

	function lcm($n, $m)
	{
		return $m * ($n/gcd($n,$m));
	}

	function lcm_arr($items)
	{
		//Input: An Array of numbers
		//Output: The LCM of the numbers
		while(count($items) >= 2)
		{
			array_push($items, lcm(array_shift($items), array_shift($items)));
		}
		return reset($items);
	}

class Npc
{
	public $npc; // ������ �� game_npc
	public $templ; //������ �� game_npc_template
	private $error;
	
	public function __destruct()
	{
	}
	
	public function __construct($id)
	{
		global $user_id;
		$this->error = 0;
		$this->npc = mysql_fetch_array(myquery("SELECT * FROM game_npc WHERE id=$id"));
		if (!isset($this->npc['npc_id']) OR $this->npc['npc_id']=='') 
		{
			$this->error = 1;	
			$theme = "����� ������ NPC � ������ npc_id";
			$backtrace = debug_backtrace();
			$error_message = '<pre>'.print_r($backtrace,true).'</pre>';			
			myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, clan, time) VALUES ('612', '$user_id', '".mysql_real_escape_string($theme)."', '".mysql_real_escape_string($error_message)."', '0','0',".time().")");
			
			myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, clan, time) VALUES ('28591', '$user_id', '".mysql_real_escape_string($theme)."', '".mysql_real_escape_string($error_message)."', '0','0',".time().")");
			
		}
		else
		{
			$this->templ = mysql_fetch_array(myquery("SELECT * FROM game_npc_template WHERE npc_id=".$this->npc['npc_id'].""));
		}
	}

	public function drop_loot($user_id) // ���� ���� � ���� ��� ��� ��������
	{		
		if ($this->error==1) return;
		if ($this->npc['dropable']==1)
		{
			$seldrop = myquery("SELECT * FROM game_npc_drop WHERE npc_id=".$this->templ['npc_id'].";");
			if ($seldrop!=false AND mysql_num_rows($seldrop))
			{
				$sum_chance = 0;
				$massiv = array(0);
				$m_last = 1;

				$mes = "";
				$lcm = array();
				$str = "";

				$r = 0;
				$no_loot=0;
				
				//���������� ��������� "�������"				
				if ($this->templ['npc_id']==npc_id_olen)
				{
					if (checkCraftTrain($user_id,8))
					{
						$r = $r + 2 * getCraftLevel($user_id,8);
					}
					else
					{
						$no_loot=1;
					}
				}
				
				
				while ($chance = mysql_fetch_array($seldrop))
					$lcm[] = $chance['random_max'];

				mysql_data_seek($seldrop, 0);
				$lcm = lcm_arr($lcm);

				while ($chance = mysql_fetch_array($seldrop))
				{
					$this_chance = ($chance['random'] + $r) * $lcm / gcd($lcm, $chance['random_max']);
					$massiv = array_merge($massiv, array_fill($m_last, $this_chance, $chance));
					$m_last += $this_chance;
				}

				if ($m_last < $lcm + 1)
					$massiv = array_merge($massiv, array_fill($m_last, $lcm + 1 - $m_last, array('items_id' => 0)));

				$drop = $massiv[mt_rand(1, $lcm)];
				if ($drop['items_id'] != 0 and $no_loot==0)
				{
					$it_user_id = 0;
					$priznak = 2;
					$kol_predmetov = mt_rand($drop['mincount'],$drop['maxcount']);
					$map_name = 0;
					$map_xpos = 0;
					$map_ypos = 0;
					for ($cikl = 1; $cikl <= $kol_predmetov; $cikl++)
					{
						$add_result = array(0);
						if ($drop['kuda']==1)
						{
							$it_user_id = $user_id;
							$priznak=0;
							//�������� ���
							if($drop['drop_type']==1)
								$item = mysql_fetch_array(myquery("SELECT * FROM game_items_factsheet WHERE id=".$drop['items_id'].""));
							//���� ������
							elseif ($drop['drop_type']==2)
								$item = mysql_fetch_array(myquery("SELECT * FROM craft_resource WHERE id=".$drop['items_id'].""));
							list($CW,$CC) = mysql_fetch_array(myquery("SELECT CW,CC FROM view_active_users WHERE user_id=$it_user_id"));
							$prov = mysqlresult(myquery("SELECT COUNT(*) FROM game_wm WHERE user_id=$it_user_id AND type=1"),0,0);
							if (($CC-$CW)<$item['weight'] AND $prov==0)
							{
								//��������� ��������. �� ����� ���!
								$it_user_id=0;
								$priznak=2;
								list($map_name,$map_xpos,$map_ypos) = mysql_fetch_array(myquery("SELECT map_name,map_xpos,map_ypos FROM game_users_map WHERE user_id=$it_user_id"));
								$drop['kuda'] = 0;
							}
						}
						//���� �������
						if($drop['drop_type']==1)
						{
							if ($priznak==0)
							{
								$Item = new Item();
								$add_result = $Item->add_user($drop['items_id'],$it_user_id,0,0,1);
								// �� ���������� �������� ���� - �� ������ � ���������.
								if ($add_result[0] == 0)
									break;
							}
							else
							{
								if (!isset($item)) $item = mysql_fetch_array(myquery("SELECT * FROM game_items_factsheet WHERE id=".$drop['items_id'].""));
								myquery("INSERT INTO game_items (user_id,item_id,priznak,ref_id,item_uselife,item_uselife_max,item_cost,map_name,map_xpos,map_ypos) VALUES ('$it_user_id','".$drop['items_id']."','$priznak',0,'".$item['item_uselife']."','".$item['item_uselife_max']."','".$item['item_cost']."','$map_name','$map_xpos','$map_ypos')");
							}
						}
						//���� ������
						elseif ($drop['drop_type']==2)
						{							
							if ($drop['kuda']==1)
							{
								$Res = new Res($item, 0);
								$Res->add_user(0, $it_user_id, 1);								
								if ($this->templ['npc_id']==npc_id_olen)
								{
									myquery("INSERT INTO craft_stat (build_id, gp, res_id, dob, vip, dat, user, type) values (0, 0, ".$drop['items_id'].", 0, 1, ".time().", $it_user_id, 'z')");
									setCraftTimes($it_user_id, 8, 1, 1);
									add_exp_for_craft($it_user_id, 8);
								}
							}
							elseif ($drop['kuda']==0)
							{
								$Res = new Res(0, $drop['items_id']);
								$Res->add_map(0, 0, 1, 0, $map_name, $map_xpos, $map_ypos);
								myquery("INSERT INTO craft_resource_market (user_id,town,col,price,res_id,opis,map_name,map_xpos,map_ypos) VALUES (0,0,1,0,".$drop['items_id'].",'','".$map_name."','".$map_xpos."','".$map_ypos."')");
							}
						}
						if ($it_user_id>0)
						{
							
							if($drop['drop_type']==1)
							{
								$mes = '<font color=\"#eeeeee\">����� �������� ������� �� '.echo_sex('������','�������').' ���������� �� ���� �������: <b>'.$item['name'].'</b>.�� ������ '.echo_sex('��������','���������').' �������� ������� � '.echo_sex('�������','��������').' ��� � ���� ���������!</font>';
							}
							elseif($drop['drop_type']==2)							
							{								
								$mes = '<font color=\"#eeeeee\">����� �������� ������� �� '.echo_sex('�������','��������').', ��� �� ������� �� ���: <b>'.$item['name'].'<b/>. �� ������ �� ������ '.echo_sex('��������','���������').' �������� ������ � '.echo_sex('�������','��������').' ��� � ���� ���������!</font>';
							}						   
						   $result = myquery("INSERT game_battles SET attacker_id=".$user_id.", target_id=0, map_name=".$map_name.", map_xpos=".$map_xpos.", map_ypos=".$map_ypos.", contents='".$mes."', post_time=".time()."");
						}
					}
				}
			}
		}
	}
    
	public function check_hunter($user_id) // ����� "������ ���������"
	{
		$npc_level=$this->templ['npc_level'];
		list($map)=mysql_fetch_array(myquery("select map_name from game_users_map where user_id=$user_id"));
		$check=myquery("Select * From game_users_hunter Where level=$npc_level and level>times and map=$map and user_id=$user_id");
		if (mysql_num_rows($check)>0)
		{
			myquery("Update game_users_hunter Set times=times+1 Where map=$map and user_id=$user_id");
		}
	}
	
	public function check_aggro($char)  // ������������� ������������ ���� �� ������
	{
		if ($this->error==1) return;
		if ($this->npc['prizrak']==1)
		{
			if ($this->npc['for_user_id']!=0 AND $this->npc['for_user_id']!=$char['user_id']) return;
            //if ($this->templ['npc_level']<($char['clevel']-3)) return;
			//if ($this->templ['npc_level']>($char['clevel']+3)) return;
		}
		$check_level=myquery("SELECT t2.value FROM game_npc_set_option as t1
							  Join game_npc_set_option_value as t2 on t1.id=t2.id
							  WHERE t1.opt_id=7 and t1.npc_id=".$this->templ['npc_id']." 
							  Order By t2.number
							");
		if (mysql_num_rows($check_level)==2) 
		{
			list($min_level)=mysql_fetch_array($check_level);
			list($max_level)=mysql_fetch_array($check_level);
			if ($char['clevel']<$min_level or $char['clevel']>$max_level) return;
		}
		$level_for_aggressive = 9999;
		//�������� �� ������������� ����
		if (
		($this->templ['agressive']=='2' AND $char['clevel']<($this->templ['npc_level']+$level_for_aggressive) AND $char['clevel']>($this->templ['npc_level']-$level_for_aggressive)) 
		or 
		($this->templ['agressive']=='1' AND $char['clevel']>($this->templ['npc_level']+$this->templ['agressive_level']))
        )
		{
			attack_npc($char,$this->npc['id'],1);
		}
	}
	
	public function show_around()  // ����������� ���� �� �����
	{
		global $char;
		if ($this->error==1) return;
		if ($this->npc['prizrak']==1)
		{
			if ($this->npc['for_user_id']!=0 AND $this->npc['for_user_id']!=$char['user_id']) return;
            //if ($this->templ['npc_level']<($char['clevel']-3)) return;
			//if ($this->templ['npc_level']>($char['clevel']+3)) return;
		}
		$npc_string = '<img id="npc'.$this->npc['id'].'" src="http://'.img_domain.'/npc/' . $this->templ['npc_img'] . '.gif" border="0" alt="' . $this->templ['npc_name'] . '" onclick="location.href=\'act.php?func=npc&option=npc&id='.$this->npc['id'].'\'"><br><center><font size="1">' . $this->templ['npc_name'] . '</font></center>';
		echo '<td valign="top"><table cellpadding="0" cellspacing="2" border="0"><tr><td>';
		?>
		<a onmousemove=movehint(event) onmouseover="showhint('<font color=ff0000><b><?
		echo '<center><font color=#0000FF>'.$this->templ['npc_name'].'</font>';
		?></b></font>','<?
		echo '<font color=000000>';
		echo '�����: '.$this->npc["HP"].'/'.$this->templ["npc_max_hp"].'<br>';
		echo '����: '.$this->npc["MP"].'/'.$this->templ["npc_max_mp"].'<br>';
		echo '�������: '.$this->templ["npc_level"].'<br>';
		echo '����: '.$this->templ["npc_str"].'&plusmn;'.$this->templ["npc_str_deviation"].'<br>';
		echo '��������: '.$this->templ["npc_pie"].'&plusmn;'.$this->templ["npc_pie_deviation"].'<br>';
		echo '������: '.$this->templ["npc_vit"].'&plusmn;'.$this->templ["npc_vit_deviation"].'<br>';
		echo '������������: '.$this->templ["npc_dex"].'&plusmn;'.$this->templ["npc_dex_deviation"].'<br>';
		echo '��������: '.$this->templ["npc_spd"].'&plusmn;'.$this->templ["npc_spd_deviation"].'<br>';
		echo '���������: '.$this->templ["npc_ntl"].'&plusmn;'.$this->templ["npc_ntl_deviation"].'<br>';
		echo '���� �� ������: '.$this->npc["EXP"].'<br>';
		echo '������ �� ������: '.$this->templ["npc_gold"].'<br>';
		echo '����� �����������: '.$this->templ["respawn"].' ������<br>';
		echo '</font>';
		?>',0,1,event)" onmouseout="showhint('','',0,0,event)">
		<?
			echo $npc_string;
		?>
		</a>
		<?
		echo '<td valign="top">';
		
		//����������� �� ������ �������
		if($this->npc['npc_quest_engine_id']==0)
		{
			if($this->templ['agressive']>='0')
			{
				echo '<a href="act.php?func=npc&option=npc&id='.$this->npc['id'].'"><img src="http://'.img_domain.'/nav/action_attacknpc.gif" width="20" height="20" border="0" alt="���������" title="���������"></a>';
			}
			elseif($this->templ['agressive'] == '-1')
			{
				echo '<a href="/quest/quests_engine_chek.php?talk&npc_id='.$this->npc['id'].'"><img src="http://'.img_domain.'/nav/babble.gif" width="20" height="20" border="0" alt="����������" title="����������"></a>';
			}
		}
		else
		{
			//������� ���, ����� �� ���������� ���� �� ����� �������� ������ ������ (�� ��������� ����� �������)
			list($fin_time)=mysql_fetch_array(myquery("SELECT quest_finish_time FROM quest_engine_users WHERE quest_type=1 AND par1_value='".$this->npc['id']."'"));
			if($this->npc['npc_quest_engine_id']!=$user_id AND $fin_time>time()) 
			{
				$reas='�������������&nbsp;�&nbsp;�������,&nbsp;��&nbsp;�������������,&nbsp;���&nbsp;�����&nbsp;���&nbsp;c�����&nbsp;��&nbsp;�������';
				echo '<img src="http://'.img_domain.'/nav/action_notattack.gif" width="20" height="20" border="0" alt='.$reas.' title='.$reas.' border=0>';
			}
			else 
			{
				if ($fin_time<=time())
				{
					if($this->npc['EXP']==0)
					{
						$exp=ceil($this->templ["npc_max_hp"]*0.5);
						$gold=round(max(1,$this->templ["npc_max_hp"]/150),1);
						$exp_up=myquery("UPDATE game_npc SET EXP=".$exp.", GP=".$gold." WHERE npc_id='".$this->npc['id']."'");
					}
				} 
				echo '<a href="act.php?func=npc&option=npc&id='.$this->npc['id'].'"><img src="http://'.img_domain.'/nav/action_attacknpc.gif" width="20" height="20" border="0" alt="���������" title="���������"></a>';
			}
		}
		
		echo '<br><a href="act.php?func=main&npc_info='.$this->npc['id'].'"><img src="http://'.img_domain.'/nav/i.gif" alt="���������� ����������" title="���������� ����������" border=0></a>
		</td></tr></table></td>';
	}
	
	public function can_attack($char)
	{
		if ($this->error==1) return;
		
		if (($this->npc['time_kill']+$this->templ['respawn'])<time() AND $this->templ['agressive']>='0' AND $this->npc['map_name']==$char['map_name'] AND $this->npc['xpos']==$char['map_xpos'] AND $this->npc['ypos']==$char['map_ypos'])
		{
			return true;
		}
		return false;   
	}
	
	public function npc_for_level($char) //��� ��������� �� ����������� ������� ������
	{
		$check_level=myquery("SELECT t2.value FROM game_npc_set_option as t1
							  Join game_npc_set_option_value as t2 on t1.id=t2.id
							  WHERE t1.opt_id=7 and t1.npc_id=".$this->templ['npc_id']." 
							  Order By t2.number
							");
		if (mysql_num_rows($check_level)==2) 
		{
			list($min_level)=mysql_fetch_array($check_level);
			list($max_level)=mysql_fetch_array($check_level);
			if ($char['clevel']<$min_level or $char['clevel']>$max_level) 
			{
				return false;   
			}
		}
		return true;
	}
	
	public function create_output()
	{
		if ($this->error==1) return;
		$output_string=' 
		<table cellpadding="0" cellspacing="5" border="0">
		<tr><td colspan=2 align="center"><b><font face=verdana size=2 color=ff0000>'.$this->templ["npc_name"].'</font></b></td></tr>

		<tr><td><font color=#80FFFF>�����</font></td><td><div align="left">'.$this->npc["HP"].'/'.$this->templ["npc_max_hp"].' </div></td></tr>
		<tr><td><font color=#80FFFF>����</font></td><td><div align="left">'.$this->npc["MP"].'/'.$this->templ["npc_max_mp"].' </div></td></tr>
		<tr><td><font color=#80FFFF>�������</font></td><td><div align="left">'.$this->templ["npc_level"].' </div></td></tr>
		<tr><td><font color=#80FFFF>����</font></td><td><div align="left">'.$this->templ["npc_str"].'&plusmn;'.$this->templ["npc_str_deviation"].'</div></td></tr>
		<tr><td><font color=#80FFFF>��������</font></td><td><div align="left">'.$this->templ["npc_pie"].'&plusmn;'.$this->templ["npc_pie_deviation"].'</div></td></tr>
		<tr><td><font color=#80FFFF>������</font></td><td><div align="left">'.$this->templ["npc_vit"].'&plusmn;'.$this->templ["npc_vit_deviation"].'</div></td></tr>
		<tr><td><font color=#80FFFF>������������</font></td><td><div align="left">'.$this->templ["npc_dex"].'&plusmn;'.$this->templ["npc_dex_deviation"].'</div></td></tr>
		<tr><td><font color=#80FFFF>��������</font></td><td><div align="left">'.$this->templ["npc_spd"].'&plusmn;'.$this->templ["npc_spd_deviation"].'</div></td></tr>
		<tr><td><font color=#80FFFF>���������</font></td><td><div align="left">'.$this->templ["npc_ntl"].'&plusmn;'.$this->templ["npc_ntl_deviation"].'</div></td></tr>
		<tr><td><font color=#80FFFF>����� �����������</font></td><td><div align="left">'.$this->templ["respawn"].' ������</div></td></tr>
		<tr><td><font color=#80FFFF>���� �� ������</font></td><td><div align="left">'.$this->npc["EXP"].'</div></td></tr>';
		if  ($this->templ['agressive']=='-1') $output_string.='<tr><td><font color=#80FFFF>��� �������</font></td><td><div align="left">�� ������� (�� ���� ������� ������)</div></td></tr>';
		if  ($this->templ['agressive']=='0') $output_string.='<tr><td><font color=#80FFFF>��� �������</font></td><td><div align="left">��������� (�� �������� �� �������)</div></td></tr>';
		if  ($this->templ['agressive']=='1') $output_string.='<tr><td><font color=#80FFFF>��� �������</font></td><td><div align="left">������� (�������� �� ������� >'.($this->templ['npc_level']+$this->templ['agressive_level']).' ������)</div></td></tr>';
		if  ($this->templ['agressive']=='2') $output_string.='<tr><td><font color=#80FFFF>��� �������</font></td><td><div align="left">������� (�������� �� ���� ������� ��� �������)</div></td></tr>';
		if ($this->templ['npc_gold']>0) $output_string.='<tr><td><font color=#80FFFF>������ �� ������</font></td><td><div align="left">'.$this->templ["npc_gold"].'</div></td></tr>';
		 $output_string.='<tr><td><font color=#80FFFF>�������� NPC</font></td><td><div align="justify">'.$this->templ["npc_opis"].'</div></td></tr>';
		 $output_string.='</table>';
		 return $output_string;
	}
	
	public function on_dead() // �������� ��� �������� ���� � ���
	{
		if ($this->error==1) return;
		if ($this->npc['stay']==0 AND $this->npc['prizrak']!=1 AND $this->templ['to_delete']!=1)
		{
			$try = 0;
            $npc_map = $this->npc['map_name'];
			while (1==1)
			{
				list($maze) = mysql_fetch_array(myquery("SELECT maze FROM game_maps WHERE id=$npc_map"));
				if ($maze==1)
				{
					$battle_map_query = myquery("SELECT xpos,ypos FROM game_maze where map_name=$npc_map ORDER BY xpos DESC, ypos DESC LIMIT 1");
				}
				else
				{
                    if ($this->npc['npc_id']==npc_id_mrachn_hranitel)
                    {
                        //������� ��������� ��������� ��� �� 1 ��� �� 2 ������ ��������
                        $npc_map = mt_rand(691,692);
                        $battle_map_query = myquery("SELECT xpos,ypos FROM game_map where name=$npc_map ORDER BY xpos DESC, ypos DESC LIMIT 1");
                    }
                    else
                    {
					    $battle_map_query = myquery("SELECT xpos,ypos FROM game_map where name=$npc_map ORDER BY xpos DESC, ypos DESC LIMIT 1");
                    }
				}
				$battle_map_result = mysql_fetch_array($battle_map_query, MYSQL_ASSOC);

				$xrandmap = mt_rand(0, $battle_map_result['xpos']);
				$yrandmap = mt_rand(0, $battle_map_result['ypos']);
				$che = myquery("SELECT COUNT(*) FROM game_npc WHERE xpos=$xrandmap AND ypos=$yrandmap AND map_name=$npc_map");
				if (mysql_result($che,0,0)==0 or $try==10)
				{
					$xrandmapview = mt_rand(0,4)-2;
					$yrandmapview = mt_rand(0,4)-2;
					break;
				}
				else
				{
					$try++;
				}
			}
            $time_kill = time();
            if ($npc_map==id_map_tuman)
            {
                $time_kill = time()+365*24*60*60;
            }
			myquery("update game_npc set LOSE=LOSE+1,kill_last_hour=kill_last_hour+1,HP=".$this->templ['npc_max_hp'].",MP=".$this->templ['npc_max_mp'].", time_kill=".$time_kill.",xpos='".$xrandmap."',ypos='".$yrandmap."',xpos_view='".$xrandmapview."',ypos_view='".$yrandmapview."',map_name=$npc_map where id=".$this->npc['id']."");
            if ($this->templ['npc_id']==npc_id_mrachn_hranitel)
            {
                myquery("UPDATE game_npc_template SET respawn=".mt_rand(24*60*60,32*60*60)." WHERE npc_id=".npc_id_mrachn_hranitel."");
            }
		}
		else
		{
			if ($this->npc['stay']>=2 and $this->npc['stay']<=4)
			{
				myquery("Delete From game_npc Where id=".$this->npc['id']."");
				if ($this->npc['stay']==3) myquery("Delete From game_npc_template Where npc_id=".$this->npc['npc_id']."");
			}
			elseif ($this->npc['prizrak']!=1 AND $this->templ['to_delete']!=1)
			{
				//������� ����, �� ��������� �� �����, �.�. ��� �� ������������
				myquery("UPDATE game_npc SET LOSE=LOSE+1,kill_last_hour=kill_last_hour+1,HP=".$this->templ['npc_max_hp'].",MP=".$this->templ['npc_max_mp'].", time_kill=".time()." WHERE id=".$this->npc['id']."");
			}
			else
			{
				//��� ���-�������, �� ��������� ����� ��������
				myquery("delete from game_npc where id=".$this->npc['id']."");
				if ($this->templ['to_delete']==1)
				{
					$kol = mysql_result(myquery("SELECT COUNT(*) FROM game_npc WHERE npc_id=".$this->npc['npc_id'].""),0,0);
					if ($kol==0)
					{
						//������� ������ ����
						myquery("DELETE FROM game_npc_template WHERE npc_id=".$this->npc['npc_id']."");
					}
				}
			} 
		}
	}
	
	public function teleport($user_id) // ���� ���-��������, �� ������� �������������� ������
	{
		$check_teleport=myquery("Select id From game_npc_set_option Where npc_id='".$this->npc['npc_id']."' and opt_id=14");
		$count=mysql_num_rows($check_teleport);
		if ($count==1)
		{
			list($number)=mysql_fetch_array($check_teleport);
			$find_map=myquery("SELECT value FROM game_npc_set_option_value Where id=$number Order by number");
			list($map)=mysql_fetch_array($find_map);
			list($x_pos)=mysql_fetch_array($find_map);
			list($y_pos)=mysql_fetch_array($find_map);
			list($user_name)=mysql_fetch_array(myquery("SELECT name FROM game_users WHERE user_id=$user_id"));
			myquery("UPDATE game_users_map Set map_name=$map, map_xpos=$x_pos, map_ypos=$y_pos Where user_id=$user_id");
			myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES 
			(28591, '0', '".$user_name." ������ ���������� ��������', '".$user_name." ������ ���������� ��������','0','".time()."'),
			(2694, '0', '".$user_name." ������ ���������� ��������', '".$user_name." ������ ���������� ��������','0','".time()."'),
			(22811, '0', '".$user_name." ������ ���������� ��������', '".$user_name." ������ ���������� ��������','0','".time()."')
			");
		}
	}
}
?>
