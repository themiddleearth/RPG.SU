<?php
/*
//������ � �����
//CC - ������������ ����������� ���������
//CW - ������� ����������� ���������

������ �������:
1) ������� ������� � ����� - take();
2) ��������� ������� �� ����� - drop();
3) ���������� ���������� �� ���� �������� � ��������� ������ - info();
4) ���������� ���������� �� ���� �������� � ���� ����������� �������� - hint();
5) ���������������� ������� � �������� - identify();
6) ������� ������� �������� - sell();
7) ������ ������� � �������� - buy();
8) �������� ������� � �������� - repair();
9) ������������ ������� - use_item();
10) ��������� ������� �� ����� - sell_market();
11) ������ ������� �� ����� - buy_market();
12) ����� ������� - up();
13) ����� ������� - down();
14) ������� ������� - delete();



�������� used: - � ����� ����� ��������� ��������� �������
0 - �����
1 - ����
2 - ������ 1
3 - ��������
4 - ��� ��� ���� 2
5 - ������
6 - ���� 
7 - ����� 
8 - ���� 
9 - ��������
10 - ��������
11 - �����
12 - ������� ��� ������ 1
13 - ������� ��� ������ 2
14 - ������� ��� ������ 3
15 - ��������� 1
16 - ��������� 2
17 - ������
18 - ������
19 - ������ 2
20 - ������ 3
21 - ����������
22 - ���� ��� _����_ ����������


type - ��� ��������
1 - ������
2 - ������
3 - ��������
4 - ���
5 - ������
6 - ����
7 - �����
8 - ����
9 - ��������
10 - ��������
11 - �����
12 - ������
13 - ��������
14 - ������
15 - ������
16 - ���������
17 - ���.�����
18 - ����
19 - ���.������
20 - ����� ������������ ����
21 - ������
22 - ���� �������
23 - ���������
24 - ����������

95 = qengine_item_type - ������� ��� ������ �������
97 - ������ (�������, ������� � �.�. �� �����)
98 - ����� ������� ������� (����� ������� ��������� �� ��������)
99 - wm

type_weapon
0 -  ��� ���� (MS_WEAPON)
1 -  �������� (MS_KULAK)
2 -  ���������� (MS_LUK)
3 -  ������� (MS_SWORD)
4 -  �������� (MS_AXE)
5 -  ������� (MS_SPEAR)

def_type (����� ���� � 
�������[15](������ �����), 
�������[5](������ ����), 
�����[6](������ ������), 
������[14]](������ ����), 
�������[11]](������ ���))
0 -  ������
1 -  ��������
2 -  ���������� (��������)
3 -  ���� (������������)

wm
1 - ������ ����������� �������
2 - ������ ���������
3 - ������ ������� �������������
4 - ������ ������ �������������
5 - ������ ����������


��� ���� 20 - ����� ������������ ��������:
oclevel = ������� ����� (�� 1 �� 5)
indx = id ���������������� ��������

��� ���� 21 - ������
quantity - id ���� � ������� ������ ����� ��������������

game_items.count_item = ���-�� �������/���������/�����/���.���������
*/


class Item
{
	public $item;
	public $fact;
	private $char;
	public $message;
	private $tax_market = 0.08;//����� �� �����
			
	public function __construct($id=0, $user_id=0)
	{
		global $char;
		if ($user_id > 0)
		{
			$result = myquery("SELECT view_active_users.*, game_users_map.map_name, game_users_map.map_xpos,game_users_map.map_ypos  FROM view_active_users,game_users_map WHERE game_users_map.user_id=view_active_users.user_id AND view_active_users.user_id=$user_id");
			if($result==false OR mysql_num_rows($result)==0)
			{
				$result = myquery("SELECT game_users.*, game_users_map.map_name, game_users_map.map_xpos, game_users_map.map_ypos, game_users_active_delay.delay, game_users_active_delay.delay_reason FROM game_users,game_users_map,game_users_active_delay WHERE game_users.user_id=game_users_active_delay.user_id AND game_users_map.user_id=game_users.user_id AND game_users.user_id=$user_id");
				if($result==false OR mysql_num_rows($result)==0)
				{
					$result = myquery("SELECT game_users.*, game_users_map.map_name, game_users_map.map_xpos, game_users_map.map_ypos, game_users_active_delay.delay, game_users_active_delay.delay_reason FROM game_users_archive game_users,game_users_map,game_users_active_delay WHERE game_users.user_id=game_users_active_delay.user_id AND game_users_map.user_id=game_users.user_id AND game_users.user_id=$user_id");
				}
			}
			$this->char = mysql_fetch_assoc($result);
		}
		else
		{
			$this->char = $char;
		}
		$this->message = '';
		if ($id>0)
		{
			$this->init_item($id);
		}
	}
	
	private function init_fact($id)
	{
		if ((int)$id<=0) exit;
		$this->fact = mysql_fetch_assoc(myquery("SELECT * FROM game_items_factsheet WHERE id=$id"));
		$this->fact['weight'] = (double)$this->fact['weight'];
	}
	
	private function init_item($id)
	{
		if ((int)$id<=0) exit;
		$this->item = mysql_fetch_assoc(myquery("SELECT * FROM game_items WHERE id=$id"));
		$this->init_fact($this->item['item_id']);
	}
	
	public function test()
	{
		$pismo = iconv("Windows-1251","UTF-8//IGNORE","<span style=\"font-style:italic;font-size:12px;color:gold;\">��������!</span>");
		myquery("INSERT INTO game_log (`message`,`date`,`FROMm`,`too`,`ptype`) VALUES ('".mysql_real_escape_string($pismo)."',".time().",-1,28591,1)");
	}
	
	public function setChar($char_id = -1)
	{
		if ($char_id == -1)
		{
			global $char;
			$this->char = $char;
		}
		else
		{
			$result = myquery("SELECT view_active_users.*, game_users_map.map_name, game_users_map.map_xpos,game_users_map.map_ypos  FROM view_active_users,game_users_map WHERE game_users_map.user_id=view_active_users.user_id AND view_active_users.user_id=$char_id");
			if($result==false OR mysql_num_rows($result)==0)
			{
				$result = myquery("SELECT game_users.*, game_users_map.map_name, game_users_map.map_xpos,game_users_map.map_ypos,game_users_active_delay.delay,game_users_active_delay.delay_reason  FROM game_users,game_users_map,game_users_active_delay WHERE game_users.user_id=game_users_active_delay.user_id AND game_users_map.user_id=game_users.user_id AND game_users.user_id=$char_id");
			}
			$this->char = mysql_fetch_assoc($result);
			$this->char['user_id']=$char_id;
			$this->char['func_id']=getFunc($char_id);
		}
	}

	public function find_item($item_id,$not_used=0)
	{
		if ($not_used==1)
		{
			$find = myquery("SELECT id FROM game_items WHERE item_id=$item_id AND user_id=".$this->char['user_id']." AND priznak=0 AND used=0 LIMIT 1");
		}
		else
		{
			$find = myquery("SELECT id FROM game_items WHERE item_id=$item_id AND user_id=".$this->char['user_id']." AND priznak=0 LIMIT 1");
		}
		if ($find==false OR mysql_num_rows($find)==0)
		{
			return 0;
		}
		$this->init_item(mysql_result($find,0,0));
		return 1;
	}
	
	public function __get($index)
	{
		if (isset($this->fact[$index]))
		{
			return $this->fact[$index];
		}
		elseif (isset($this->item[$index]))
		{
			return $this->item[$index];
		}
		return $this->$index;
	}
	
	public function getItem($index)
	{
		if (isset($this->item[$index]))
		{
			return $this->item[$index];
		}
		return $this->$index;
	}
	
	public function getFact($index)
	{
		if (isset($this->fact[$index]))
		{
			return $this->fact[$index];
		}
		return $this->$index;
	}
	
	public function getOpis()
	{
		$opis = '';
		
		$sel = myquery("SELECT opis FROM game_items_opis WHERE item_id=".$this->item['id']."");
		if ($sel!=false AND mysql_num_rows($sel)>0)
		{
			list($opis) = mysql_fetch_array($sel);
		}
		
		return $opis;
	}

	public function delOpis($item_id = 0)
	{
		if ($item_id == 0)
			$item_id = $this->item['id'];
		myquery("DELETE FROM game_items_opis WHERE item_id=".$this->item['id'].";");
	}
	
	public function setOpis($opis,$item_id = 0)
	{
		if ($item_id == 0)
			$item_id = $this->item['id'];
		myquery("INSERT INTO game_items_opis (item_id,opis) VALUE ('$item_id','$opis') ON DUPLICATE KEY UPDATE opis='$opis'");
	}
	
	public function __toString()
	{
		return $this->fact['name'];
	}
	
	public function __destruct()
	{
	}

	public function counted_item()
	{                       
		if ($this->fact['type']==13 OR $this->fact['type']==12 OR $this->fact['type']==21 OR $this->fact['type']==19 OR $this->fact['type']==22 OR $this->fact['type']==97) return true;
		return false;
	}  

	public function max_count()
	{
		if (!$this->counted_item())
			return 1;
		if ($this->fact['weight']!=0)	
		return min(50, 50.0 / $this->fact['weight']);
		return 50;
	}
	
	//������ ������� �� ����������� ������������� � ����������� ������	
	public function damage_item($eff = 1, $chance = 25, $reason = 1)
	{
		if ($this->fact['breakdown']==1 and !$this->counted_item())
		{
			$r=mt_rand(1,100);
			if ($r<$chance) 
			{
				if ($this->item['item_uselife_max']<=1) 
				{
					$this->admindelete(0, 1, $reason);
					return -1;
				}
				else
				{
					$this->item['item_uselife_max']--;
					myquery("UPDATE game_items SET item_uselife_max=item_uselife_max-1 WHERE id='".$this->item['id']."'");
					return 0;
				}
			}
		}
		return 1;
	}

	
	public function take($id=0, $count_item=1)
	{
		global $_SESSION;
		global $user_time;
		//1. �������� ��������� �� ������ ������� �� ����� ����� � ������� � ����� � ���� priznak=2
		//2. ������ �������� user_id, ������������� priznak=0,map_name=0,map_xpos=0,map_ypos=0
		if ($id>0)
		{
			$this->init_item($id);
		}
		if ($this->item['map_name']==$this->char['map_name'] AND $this->item['map_xpos']==$this->char['map_xpos'] AND $this->item['map_ypos']==$this->char['map_ypos'] AND $this->item['priznak']==2)
		{
			if ($this->fact['name']=='����� ������� ����������')
			{
				myquery("DELETE FROM game_items WHERE id=".$this->item['id']."");
				$rand = mt_rand(10,20);
				$sel = myquery("SELECT game_items.id,game_items.item_id,game_items.count_item,game_items_factsheet.item_uselife FROM game_items,game_items_factsheet WHERE game_items.user_id=".$this->char['user_id']." AND game_items.used=3 AND game_items.priznak=0 AND game_items.item_id=game_items_factsheet.id");
				if ($sel!=false)
				{
					list($id_art,$ident_art,$cur_zar,$max_zar) = mysql_fetch_array($sel);
					$new_zar = min($max_zar,$cur_zar+$rand);
					myquery("UPDATE game_items SET count_item=$new_zar WHERE id=$id_art");
					$rand = $new_zar-$cur_zar;
				}
				else
				{
					$rand = 0;
				}
				$_SESSION['getsunduk']='�� '.echo_sex('�������','��������').' �������� �� '.$rand.' �������';
				setLocation("act.php?getsunduk");
				{if (function_exists("save_debug")) save_debug(); exit;}
			}
			elseif ($this->fact['name']=='������ � �����������')
			{
				myquery("DELETE FROM game_items WHERE id=".$this->item['id']."");
				myquery("UPDATE game_users SET GP=GP + ".$this->item['item_cost']." WHERE user_id=".$this->char['user_id']."");
				$_SESSION['getsunduk']='�� '.echo_sex('�����','�����').' ������ � '.$this->item['item_cost'].' ��������';
				setGP($this->char['user_id'],$this->item['item_cost'],5);
				setLocation("act.php?getsunduk");
				{if (function_exists("save_debug")) save_debug(); exit;}
			}
			elseif ($this->fact['name']=='����� ����')
			{
				myquery("DELETE FROM game_items WHERE id=".$this->item['id']."");
				$stm = mt_rand(20,90);
				if (($stm+$this->char['STM'])>$this->char['STM_MAX']) $stm = $this->char['STM_MAX'] - $this->char['STM'];
				myquery("UPDATE game_users SET STM=STM + $stm WHERE user_id=".$this->char['user_id']." LIMIT 1");
				$_SESSION['getsunduk']='�� '.echo_sex('�����','������').' ���� � ���� ������� ���������� �� '.$stm.' ������';
				setLocation("act.php?getsunduk");
				{if (function_exists("save_debug")) save_debug(); exit;}
			}
			elseif ($this->fact['name']=='����� ����')
			{
				myquery("DELETE FROM game_items WHERE id=".$this->item['id']."");
				$stm = mt_rand(20,90);
				if (($stm+$this->char['HP'])>$this->char['HP_MAX']) $stm = $this->char['HP_MAX'] - $this->char['HP'];
				myquery("UPDATE game_users SET HP=HP + $stm WHERE user_id=".$this->char['user_id']." LIMIT 1");
				$_SESSION['getsunduk']='�� '.echo_sex('����','�����').' ���� � ���� ����� ���������� �� '.$stm.' ������';
				setLocation("act.php?getsunduk");
				{if (function_exists("save_debug")) save_debug(); exit;}
			}
			elseif ($this->fact['name']=='���������� �������')
			{
				myquery("DELETE FROM game_items WHERE id=".$this->item['id']."");
				$stm = mt_rand(10,70);
				if (($stm+$this->char['MP'])>$this->char['MP_MAX']) $stm = $this->char['MP_MAX'] - $this->char['MP'];
				myquery("UPDATE game_users SET MP=MP + $stm WHERE user_id=".$this->char['user_id']." LIMIT 1");
				$_SESSION['getsunduk']='�� '.echo_sex('�����','������').' ���������� ������� � ���� ���� ���������� �� '.$stm.' ������';
				setLocation("act.php?getsunduk");
				{if (function_exists("save_debug")) save_debug(); exit;}
			}
			elseif ($this->fact['name']=='������� � ������� ������')
			{
				myquery("DELETE FROM game_items WHERE id=".$this->item['id']."");
				$hp = mt_rand(10,50);
				$mp = mt_rand(20,60);
				$stm = mt_rand(30,70);

				$r = mt_rand(1,4);
				if ($r==1)
				{
					$hp=-$hp;
					if (($this->char['HP']+$hp)<0) $hp = -$this->char['HP'];
				}
				else
				{
					if (($hp+$this->char['HP'])>$this->char['HP_MAX']) $hp = $this->char['HP_MAX'] - $this->char['HP'];
				}

				$r = mt_rand(1,4);
				if ($r==1)
				{
					$mp=-$mp;
					if (($this->char['MP']+$mp)<0) $mp = -$this->char['MP'];
				}
				else
				{
					if (($mp+$this->char['MP'])>$this->char['MP_MAX']) $mp = $this->char['MP_MAX'] - $this->char['MP'];
				}

				$r = mt_rand(1,4);
				if ($r==1)
				{
					$stm=-$stm;
					if (($this->char['STM']+$stm)<0) $stm = -$this->char['STM'];
				}
				else
				{
					if (($stm+$this->char['STM'])>$this->char['STM_MAX']) $stm = $this->char['STM_MAX'] - $this->char['STM'];
				}

				myquery("UPDATE game_users SET HP=HP+$hp,MP=MP+$mp,STM=STM+$stm WHERE user_id=".$this->char['user_id']." LIMIT 1");
				$_SESSION['getsunduk']='�� '.echo_sex('�����','������').' ������� � ������� ������ � ���� ���� ���������� �� '.$mp.' ������, ���� ������� ���������� �� '.$stm.' ������, ���� ����� ���������� �� '.$hp.' ������';
				setLocation("act.php?getsunduk");
				{if (function_exists("save_debug")) save_debug(); exit;}
			}
			elseif ($this->fact['name']=='������� � �������� ������')
			{
				myquery("DELETE FROM game_items WHERE id=".$this->item['id']."");
				$hp = mt_rand(10,40);
				$mp = mt_rand(20,40);
				$stm = mt_rand(10,40);

				$r = mt_rand(1,4);
				if ($r==1)
				{
					$hp=-$hp;
					if (($this->char['HP']+$hp)<0) $hp = -$this->char['HP'];
				}
				else
				{
					if (($hp+$this->char['HP'])>$this->char['HP_MAX']) $hp = $this->char['HP_MAX'] - $this->char['HP'];
				}

				$r = mt_rand(1,4);
				if ($r==1)
				{
					$mp=-$mp;
					if (($this->char['MP']+$mp)<0) $mp = -$this->char['MP'];
				}
				else
				{
					if (($mp+$this->char['MP'])>$this->char['MP_MAX']) $mp = $this->char['MP_MAX'] - $this->char['MP'];
				}

				$r = mt_rand(1,4);
				if ($r==1)
				{
					$stm=-$stm;
					if (($this->char['STM']+$stm)<0) $stm = -$this->char['STM'];
				}
				else
				{
					if (($stm+$this->char['STM'])>$this->char['STM_MAX']) $stm = $this->char['STM_MAX'] - $this->char['STM'];
				}

				myquery("UPDATE game_users SET HP=HP+$hp,MP=MP+$mp,STM=STM+$stm WHERE user_id=".$this->char['user_id']." LIMIT 1");
				$_SESSION['getsunduk']='�� '.echo_sex('�����','������').' ������� � �������� ������ � ���� ���� ���������� �� '.$mp.' ������, ���� ������� ���������� �� '.$stm.' ������, ���� ����� ���������� �� '.$hp.' ������';
				setLocation("act.php?getsunduk");
				{if (function_exists("save_debug")) save_debug(); exit;}
			}
			elseif ($this->fact['name']=='����������� ������')
			{
				myquery("DELETE FROM game_items WHERE id=".$this->item['id']."");
				$hp = mt_rand(50,90);
				$mp = mt_rand(50,90);
				$stm = mt_rand(50,90);

				$r = mt_rand(1,4);
				if ($r==1)
				{
					$hp=-$hp;
					if (($this->char['HP']+$hp)<0) $hp = -$this->char['HP'];
				}
				else
				{
					if (($hp+$this->char['HP'])>$this->char['HP_MAX']) $hp = $this->char['HP_MAX'] - $this->char['HP'];
				}

				$r = mt_rand(1,4);
				if ($r==1)
				{
					$mp=-$mp;
					if (($this->char['MP']+$mp)<0) $mp = -$this->char['MP'];
				}
				else
				{
					if (($mp+$this->char['MP'])>$this->char['MP_MAX']) $mp = $this->char['MP_MAX'] - $this->char['MP'];
				}

				$r = mt_rand(1,4);
				if ($r==1)
				{
					$stm=-$stm;
					if (($this->char['STM']+$stm)<0) $stm = -$this->char['STM'];
				}
				else
				{
					if (($stm+$this->char['STM'])>$this->char['STM_MAX']) $stm = $this->char['STM_MAX'] - $this->char['STM'];
				}

				myquery("UPDATE game_users SET HP=HP+$hp,MP=MP+$mp,STM=STM+$stm WHERE user_id=".$this->char['user_id']." LIMIT 1");
				$_SESSION['getsunduk']='�� '.echo_sex('�����','������').' ����������� ������ � ���� ���� ���������� �� '.$mp.' ������, ���� ������� ���������� �� '.$stm.' ������, ���� ����� ���������� �� '.$hp.' ������';
				setLocation("act.php?getsunduk");
				{if (function_exists("save_debug")) save_debug(); exit;}
			}
			elseif ($this->fact['name']=='������ � ���������')
			{
				myquery("DELETE FROM game_items WHERE id=".$this->item['id']."");
				$hp = mt_rand(40,50);
				$mp = mt_rand(40,50);
				$stm = mt_rand(40,50);

				$r = mt_rand(1,4);
				if ($r==1)
				{
					$hp=-$hp;
					if (($this->char['HP']+$hp)<0) $hp = -$this->char['HP'];
				}
				else
				{
					if (($hp+$this->char['HP'])>$this->char['HP_MAX']) $hp = $this->char['HP_MAX'] - $this->char['HP'];
				}

				$r = mt_rand(1,4);
				if ($r==1)
				{
					$mp=-$mp;
					if (($this->char['MP']+$mp)<0) $mp = -$this->char['MP'];
				}
				else
				{
					if (($mp+$this->char['MP'])>$this->char['MP_MAX']) $mp = $this->char['MP_MAX'] - $this->char['MP'];
				}

				$r = mt_rand(1,4);
				if ($r==1)
				{
					$stm=-$stm;
					if (($this->char['STM']+$stm)<0) $stm = -$this->char['STM'];
				}
				else
				{
					if (($stm+$this->char['STM'])>$this->char['STM_MAX']) $stm = $this->char['STM_MAX'] - $this->char['STM'];
				}

				myquery("UPDATE game_users SET HP=HP+$hp,MP=MP+$mp,STM=STM+$stm WHERE user_id=".$this->char['user_id']." LIMIT 1");
				$_SESSION['getsunduk']='�� '.echo_sex('�����','������').' ������ � ��������� � ���� ���� ���������� �� '.$mp.' ������, ���� ������� ���������� �� '.$stm.' ������, ���� �������� ���������� �� '.$hp.' ������';
				setLocation("act.php?getsunduk");
				{if (function_exists("save_debug")) save_debug(); exit;}
			}
			elseif ($this->fact['name']=='������ ��������')
			{
				//myquery("DELETE FROM game_items WHERE id=".$this->item['id']."");
				//��������� ������� � ��������� ����� �� �������
				$check = myquery("SELECT id FROM game_items WHERE item_id=".item_id_key_constructor." AND user_id=".$this->char['user_id']." AND priznak=0 LIMIT 1");
				if (!mysql_num_rows($check))
				{
					$_SESSION['getsunduk'] = '��� ����, ����� ������� <b>������ ��������</b>, ���� ����� � ��������� <b>���� �� ������� ��������</b>!';  
				}
				else
				{
					//������� ����
					list($id_key) = mysql_fetch_array($check);
					$ItemDel = new Item($id_key);
					$ItemDel->admindelete();
					
					//���������� ������ �� ����� �����
					$sel = myquery("SELECT xpos,ypos FROM game_maze WHERE map_name=".$this->item['map_name']." AND (move_up+move_right+move_down+move_left>=2)");					
					$all = mysql_num_rows($sel);
					$r = mt_rand(0,$all-1);
					mysql_data_seek($sel,$r);	
					$pos = mysql_fetch_assoc($sel);
					myquery("UPDATE game_items SET map_xpos=".$pos['xpos'].",map_ypos=".$pos['ypos']." WHERE id=".$this->item['id']."");
					
					$item_name = "������";
					if ($this->char['map_name']==691 OR $this->char['map_name']==692 OR $this->char['map_name']==804)
					{
						if ($this->char['map_name']==691)
						{
							$drop = array(
							569,//����� ������� 1
							570,//����� ������� 2
							571,//����� ������� 3
							572,//����� ������� 4
							573,//����� ������� 5
							574,//����� ������� 6
							575,//����� ������� 7
							320,//����� ������� �������������� ������� (+200 STM)
							322,//����� ������� �������������� �������� (+200 HP)
							321 //����� ������� �������������� ���� (+200 MP)
							);
						}
						if ($this->char['map_name']==692)
						{
							$drop = array(
							576,//����� ������� 8
							577,//����� ������� 9
							578,//����� ������� 10
							579,//����� ������� 11
							580,//����� ������� 12
							581,//����� ������� 13
							582,//����� ������� 14
							321,//������� ������� �������������� ���� (+200 MP)
							358,//������� ������� �������������� ������� (+800 STM)
							322 //������� ������� �������������� �������� (+200 HP)
							);
						}
						if ($this->char['map_name']==804)
						{
							$drop = array(
							576,//����� ������� 8
							577,//����� ������� 9
							578,//����� ������� 10
							579,//����� ������� 11
							580,//����� ������� 12
							581,//����� ������� 13
							582,//����� ������� 14
							321,//������� ������� �������������� ���� (+200 MP)
							358,//������� ������� �������������� ������� (+800 STM)
							322 //������� ������� �������������� �������� (+200 HP)
							);
						}
						mt_srand(make_seed());
						$ran = mt_rand(0,9);
						$item_id = $drop[$ran];
						$ar = $this->add_user($item_id,$this->char['user_id'],1);
						if ($ar[0]==0)
						{
							$_SESSION['getsunduk']='�� '.echo_sex('������','�������').' ������ ��������, �� � ����� ��������� �� ������� ����� ��� �������, � ������ ��������!';
						}
						else
						{
							$item_name = mysqlresult(myquery("SELECT name FROM game_items_factsheet WHERE id=$item_id"),0,0);
							$_SESSION['getsunduk']='�� '.echo_sex('������','�������').' ������ �������� � '.echo_sex('�����','�����').' � ��� <b>'.$item_name.'</b>';
						}
					}
				}
				setLocation("act.php?getsunduk");
				{if (function_exists("save_debug")) save_debug(); exit;}
			}
            elseif ($this->fact['id']==item_id_sunduk) //������ � �������� �����
            {
                //myquery("DELETE FROM game_items WHERE id=".$this->item['id']."");
                //��������� ������� � ��������� ����� �� �������
                $check = myquery("SELECT id FROM game_items WHERE item_id=".item_id_old_key." AND user_id=".$this->char['user_id']." AND priznak=0 LIMIT 1");
                if (!mysql_num_rows($check))
                {
                    $_SESSION['getsunduk'] = '��� ����, ����� ������� <b>������</b>, ���� ����� � ��������� <b><a href=\'info/?item='.item_id_old_key.'\' target=_blank>������ ����</a></b>!';  
                }
                else
                {
                    //������� ����
                    list($id_key) = mysql_fetch_array($check);
                    $ItemDel = new Item($id_key);
                    $ItemDel->admindelete();
                    
                    //������� ������ 
                    myquery("DELETE FROM game_items WHERE id=".$this->item['id']."");
                    
                    $item_name = "������";
                    if ($this->char['map_name']==id_map_tuman)
                    {
                        $chance = mt_rand(0,100);
                        $item_id = 0;
                        $id_svitok = 0;
                        $char = $this->char;
                        if ($char['map_xpos']==7 AND $char['map_ypos']==0)
                        {
                            $id_svitok = item_id_svitok_light_usil;
                            $item_name = "������ ������� ��������";
                        }
                        elseif ($char['map_xpos']==0 AND $char['map_ypos']==1)
                        {
                            $id_svitok = item_id_svitok_light_usil;
                            $item_name = "������ ������� ��������";
                        }
                        elseif ($char['map_xpos']==7 AND $char['map_ypos']==2)
                        {
                            $id_svitok = item_id_svitok_medium_usil;
                            $item_name = "������ �������� ��������";
                        }
                        elseif ($char['map_xpos']==0 AND $char['map_ypos']==3)
                        {
                            $id_svitok = item_id_svitok_medium_usil;
                            $item_name = "������ �������� ��������";
                        }
                        elseif ($char['map_xpos']==7 AND $char['map_ypos']==4)
                        {
                            $id_svitok = item_id_svitok_hard_usil;
                            $item_name = "������ �������� ��������";
                        }
                        elseif ($char['map_xpos']==0 AND $char['map_ypos']==5)
                        {
                            $id_svitok = item_id_svitok_hard_usil;
                            $item_name = "������ �������� ��������";
                        }
                        elseif ($char['map_xpos']==7 AND $char['map_ypos']==6)
                        {
                            $id_svitok = item_id_svitok_absolut_usil;
                            $item_name = "������ ����������� ��������";
                        }
                        elseif ($char['map_xpos']==0 AND $char['map_ypos']==7)
                        {
                            $id_svitok = item_id_svitok_absolut_usil;
                            $item_name = "������ ����������� ��������";
                        }
                        
                        if ($chance<=50)
                        {
                            $item_id = $id_svitok;
                        }
                        elseif ($chance<=75)
                        {
                            $user_id = $char['user_id'];
                            $ress = mysql_fetch_array(myquery("SELECT * FROM craft_resource WHERE id=".id_resource_saphire.""));
                            $kol = 1;
                            $weight = $kol*$ress['weight'];
                            $prov = mysqlresult(myquery("SELECT count(*) FROM game_wm WHERE user_id=$user_id AND type=1"),0,0);
                            if ($char['CW']+$weight<=$char['CC'] or $prov>0)
                            {
                                $delay = $user_time + $weight;
                                $UPDATE_users = myquery("UPDATE game_users SET CW=(CW + $weight) WHERE user_id=$user_id LIMIT 1");
                                set_delay_info($user_id,$delay,7);
                                myquery("INSERT INTO craft_resource_user (user_id,res_id,col) VALUES ($user_id,".id_resource_saphire.",1) ON DUPLICATE KEY UPDATE col=col+1");
                                $item_name = "������";
                                $_SESSION['getsunduk']='�� '.echo_sex('������','�������').' ������ � '.echo_sex('�����','�����').' � ��� <b>'.$item_name.'</b>';
                            }
                            else
                            {
                                $_SESSION['getsunduk']='�� '.echo_sex('������','�������').' ������, �� � ����� ��������� �� ������� ����� ��� �������, � ������ ��������!';
                            }
                        }
                        else
                        {
							$user_id = $char['user_id'];
							list($amber, $wei) = mysql_fetch_array(myquery("SELECT id, weight FROM craft_resource WHERE name='������'"));
							if (isset($amber) and $char['CW']+$wei>$char['CC'])
							{
								$_SESSION['getsunduk']='�� '.echo_sex('������','�������').' ������, �� � ����� ��������� �� ������� ����� ��� �������, � ������ ��������!';
							}
							elseif (isset($amber))
							{
								myquery("INSERT INTO craft_resource_user (user_id,res_id,col) VALUES ($user_id,$amber,1) ON DUPLICATE KEY UPDATE col=col+1");
								$_SESSION['getsunduk']='�� '.echo_sex('������','�������').' ������ � '.echo_sex('�����','�����').' � ��� <b>������</b>';
								myquery("UPDATE game_users Set CW=CW+$wei WHERE user_id=$user_id");
							}
                            /*$id_svitok = item_id_svitok_light_usil;
                            $item_name = "������ ������� ��������";*/
                        }
                        if ($item_id>0) 
                        {
                            $ar = $this->add_user($item_id,$this->char['user_id'],1);
                            if ($ar[0]==0)
                            {
                                $_SESSION['getsunduk']='�� '.echo_sex('������','�������').' ������, �� � ����� ��������� �� ������� ����� ��� �������, � ������ ��������!';
                            }
                            else
                            {
                                $item_name = mysqlresult(myquery("SELECT name FROM game_items_factsheet WHERE id=$item_id"),0,0);
                                $_SESSION['getsunduk']='�� '.echo_sex('������','�������').' ������ � '.echo_sex('�����','�����').' � ��� <b>'.$item_name.'</b>';
                            }
                        }
                        if ($char['map_xpos']==0 AND $char['map_ypos']==7)
                        {
                            myquery("UPDATE game_users_map SET map_name = 18, map_xpos=26, map_ypos=21 WHERE user_id = ".$char['user_id']." ");
                        }
                    }
                }
                setLocation("act.php?getsunduk");
                {if (function_exists("save_debug")) save_debug(); exit;}
            }
			else
			{
				if ($this->fact['personal'] == 1)
				{
					$personal = $this->char['user_id'];
				}
				else
				{
					$personal = $this->item['personal'];
				}
				
				if ($this->fact['clan_id']>0 AND $this->fact['clan_id']!=$this->char['clan_id'])
				{
					setLocation("act.php?errror=wrong_clan");
					{if (function_exists("save_debug")) save_debug(); exit;}
				}
				else
				{
					if ($count_item<=$this->item['count_item']) 
					{															
						$prov=mysql_result(myquery("SELECT count(*) FROM game_wm WHERE user_id=".$this->char['user_id']." AND type=1"),0,0);
						if ($this->char['CW']+$this->fact['weight']*$count_item<=$this->char['CC'] or $prov>0)
						{
							$kol_inv = @mysql_result(@myquery("SELECT SUM(`count_item`) FROM game_items WHERE user_id=".$this->char['user_id']." AND item_id=".$this->fact['id']." AND priznak=0"),0,0);
							if (($this->fact['kol_per_user'] == 0) || ($kol_inv+$count_item <= $this->fact['kol_per_user']) )
							{
								$ref_id = 1;								
								if ($this->counted_item() )
								{
									$ref_id = 0;
									$check = myquery("SELECT id FROM game_items WHERE user_id=".$this->char['user_id']." AND priznak=0 AND used=0 AND item_id=".$this->fact['id']."");
									if (mysql_num_rows($check)==0)
									{	
										if ($count_item==$this->item['count_item'])
										{
											$update_items = myquery("UPDATE game_items SET user_id=".$this->char['user_id'].", ref_id=0, map_name=0, map_xpos=0, map_ypos=0, priznak=0, personal = ".$personal." WHERE id=".$this->item['id']."");
										}
										else
										{
											$update_items = myquery("INSERT INTO game_items (user_id,item_id,item_uselife,item_cost,item_for_quest,item_uselife_max,count_item,dead_time, personal) 
											VALUES (".$this->char['user_id'].",'".$this->item['item_id']."','".$this->item['item_uselife']."','".$this->item['item_cost']."',
											'".$this->item['item_for_quest']."','".$this->item['item_uselife_max']."','".$count_item."',".$this->item['dead_time'].", ".$personal.")");
											myquery("UPDATE game_items SET count_item=count_item-".$count_item." WHERE id=".$this->item['id']."");
										}
									}
									else
									{
										$update_items = myquery("UPDATE game_items SET count_item=count_item+".$count_item." WHERE user_id=".$this->char['user_id']." AND priznak=0 AND item_id=".$this->fact['id']." AND used=0");
										if ($count_item==$this->item['count_item'])
										{											
											myquery("DELETE FROM game_items WHERE id=".$this->item['id']."");
										}
										else
										{											
											myquery("UPDATE game_items SET count_item=count_item-".$count_item." WHERE id=".$this->item['id']."");
										}
									}
								}
								else
								{
									$update_items = myquery("UPDATE game_items SET user_id=".$this->char['user_id'].", ref_id='".$ref_id."', map_name=0, map_xpos=0, map_ypos=0,priznak=0, personal = ".$personal." WHERE id=".$this->item['id']."");
								}
								//$delay = $user_time + $this->fact['weight']; //������� �������� ��������
								$update_users = myquery("UPDATE game_users SET CW=CW + ".$this->fact['weight']*$count_item." WHERE user_id=".$this->char['user_id']." LIMIT 1");
								//set_delay_id($this->char['user_id'],$delay);
							}
							else
							{
								setLocation("act.php?errror=max_inv");
								{if (function_exists("save_debug")) save_debug(); exit;}
							}

						}
						else
						{
							setLocation("act.php?errror=full_inv");
							{if (function_exists("save_debug")) save_debug(); exit;}
						}
					}
				}
			}
		}
	}  
	
	public function drop($id=0)
	{
		//1. �������� ��������� �� ������ ������� � ������ � ����� � ���� priznak=0 � item_for_quest=0
		//2. ������ �������� user_id, ������������� priznak=2,map_name,map_xpos,map_ypos,ref_id=1 (��� ������� ������ ������������� �� �����)
		//3. ���� ��� ������, �������, ���� ��� ������ "������" - �� � count_item �������� �� ����� ����������. ���� ��� ������� ����� � ��������� count_item ��� ���������� ������ ��������� ������� ��������� ��� count_item=1
		
		if ($id>0)
		{
			$this->init_item($id);
		}
		$res=$this->damage_item(1,25,5);
		if ($res<>-1)
		{
			if ($this->item['personal']==0 AND $this->item['used']==0 AND $this->item['item_for_quest']==0 AND $this->item['user_id']==$this->char['user_id'] AND $this->fact['type']!=95)
			{
				$weight = min($this->char['CW'],$this->fact['weight']);
				myquery("UPDATE game_users SET CW=CW-".$weight." WHERE user_id=".$this->item['user_id']." LIMIT 1");
				myquery("UPDATE game_users_archive SET CW=CW-".$weight." WHERE user_id=".$this->item['user_id']." LIMIT 1");
				if ($this->counted_item())
				{
					if ($this->fact['type']==22)
					{
						$ref_id=1;
					}
					else
					{
						$ref_id=0;
					}
					if ($this->item['count_item']==1)
					{
						myquery("UPDATE game_items SET user_id=0, map_name='".$this->char['map_name']."', map_xpos=".$this->char['map_xpos'].", map_ypos=".$this->char['map_ypos'].",priznak=2,ref_id=".$ref_id.",item_cost=0 WHERE id=".$this->item['id']." LIMIT 1");
					}
					else
					{
						myquery("INSERT INTO game_items SET user_id=0, map_name='".$this->char['map_name']."', map_xpos=".$this->char['map_xpos'].", map_ypos=".$this->char['map_ypos'].",priznak=2,ref_id=".$ref_id.",item_cost=0,count_item=1,item_id=".$this->item['item_id'].", dead_time=".$this->item['dead_time']."");
						myquery("UPDATE game_items SET count_item=count_item-1 WHERE id=".$this->item['id']."");
					}
				}
				else
				{
					myquery("UPDATE game_items SET user_id=0, map_name='".$this->char['map_name']."', map_xpos=".$this->char['map_xpos'].", map_ypos=".$this->char['map_ypos'].",priznak=2,ref_id=1,item_cost=0 WHERE id=".$this->item['id']." LIMIT 1");
				}
			}
		}
	}
	
	public function info($id=0,$check_up=0, $from_town=0,$quote_width=0)
	{
		//������� ��������� ����������, ������ ���� ������� ������������ � ������������ ��� ���������� ������������� ����� ��� �������� ��������
		if ($id>0)
		{
			$this->init_item($id);
		}
		if (($this->item['user_id']==$this->char['user_id'] AND $this->item['priznak']==0) OR ($from_town>0))
		{
			if ($quote_width==0)
				QuoteTable('open');
			else
				QuoteTable('open',$quote_width);
			if (($this->item['ref_id']!=1) OR ($from_town>0))
			{
				if ($from_town==0)
				{
					echo '<table cellpadding="0" cellspacing="4" border="0"><tr><td valign="left"><div align="center">';
					ImageItem($this->fact['img'],0,$this->item['kleymo']);
					echo'<br><font color="#ffff00">'.$this->name.'</font>';
					if ($this->fact['race']<>0) echo'<br>������ ��� ����: <font color="#ff0000"><b>'.mysql_result(myquery("SELECT name FROM game_har WHERE id=".$this->fact['race'].""),0,0).'</b></font>';

					echo'</div></td><td valign="top">';
					echo'<div align="left"><img src="http://'.img_domain.'/nav/x.gif" width="0" height="0" hspace="40" border="0"><br>';
				}
				if ($this->fact['view']=='1' OR $this->item['user_id']==$this->char['user_id'])
				{
					if ($this->fact['type']!=99) echo'���: '.type_str($this->fact['type']).'<br>';
					if ($this->fact['in_two_hands']==1) echo '���������<br />';
					if ($this->fact["type"]==1 OR $this->fact["type"]==18)
					{
						switch ($this->fact["type_weapon"])
						{
							case 0:{echo '����� ������: ��� ������<br />';}break;
							case 1:{echo '����� ������: ��������<br />';}break;
							case 2:{echo '����� ������: ����������<br />';}break;
							case 3:{echo '����� ������: �������<br />';}break;
							case 4:{echo '����� ������: ��������<br />';}break;
							case 5:{echo '����� ������: �������<br />';}break;
							case 6:{echo '����� ������: �����������<br />';}break;
						}
					}
					if ($this->fact['curse']!='')
					{
						$str     = $this->fact['curse'];
						$order   = array("\r\n", "\n", "\r");
						$replace = '<br />';
						$newstr = str_replace($order, $replace, $str);
						echo $newstr.'<br>';
					}
					if ($this->fact['indx']<>0)
						if ($this->fact['type'] == 1 or $this->fact['type'] == 3 or $this->fact['type'] == 19 or $this->fact['type'] == 21) echo '����: ' . $this->fact['indx'] . '&nbsp;&plusmn;&nbsp;' . $this->fact['deviation'] . '<br>';
					if ($this->fact['type'] == 4) echo '������: ' . $this->fact['indx'] . '<br>';
					if ($this->fact['type'] == 3) echo '���-�� �������: '.$this->item['count_item'].'<br>';
					if (!$this->counted_item() AND $this->fact['type'] != 95 AND $this->fact['type'] != 20)
					{
						$use=$this->item['item_uselife'];
						echo '���������: '.$use.'%';
					}
					if (!$this->counted_item() AND $this->fact['breakdown']==1)
					{
						echo '<br />�������������: '.$this->item['item_uselife_max'].'/'.$this->fact['item_uselife_max'];
					}
					
					echo '<br />';

					if ($this->fact['weight']>0) 
					{
						if ($from_town==1 AND $this->counted_item())
						{
							echo'���: ' . $this->fact['weight']*$this->item['count_item'] . '<br><br>';
						}
						else
						{
							echo'���: ' . $this->fact['weight'] . '<br><br>';
						}
					}

					if (!$this->counted_item() AND $this->fact['type']!=20)
					{
						echo '������� ��������:<br>';
						if ($this->fact['dstr']<>'0') echo '���� ��: '.$this->fact['dstr'].'<br>';
						if ($this->fact['dntl']<>'0') echo '��������� ��: '.$this->fact['dntl'].'<br>';
						if ($this->fact['dpie']<>'0') echo '�������� ��: '.$this->fact['dpie'].'<br>';
						if ($this->fact['dvit']<>'0') echo '������ ��: '.$this->fact['dvit'].'<br>';
						if ($this->fact['ddex']<>'0') echo '������������ ��: '.$this->fact['ddex'].'<br>';
						if ($this->fact['dspd']<>'0') echo '�������� ��: '.$this->fact['dspd'].'<br>';
						if ($this->fact['dlucky']<>'0') echo '����� ��: '.$this->fact['dlucky'].'<br>';
						if ($this->fact['hp_p']<>'0') echo '����� ��: '.$this->fact['hp_p'].'<br>';
						if ($this->fact['mp_p']<>'0') echo '���� ��: '.$this->fact['mp_p'].'<br>';
						if ($this->fact['stm_p']<>'0') echo '������� ��: '.$this->fact['stm_p'].'<br>';
						if ($this->fact['pr_p']<>'0') echo '����� ��: '.$this->fact['pr_p'].'<br>';
						if ($this->fact['cc_p']<>'0') echo '������� ��������� ��: '.$this->fact['cc_p'].'<br>';
					}
					else
					{
						if ($this->fact['type']==20)
						{
							$item_name = mysqlresult(myquery("SELECT name FROM game_items_factsheet WHERE id=".$this->fact['indx'].""),0,0);
							echo '����� ��� ������������ ��������: <b><font color=red>'.$item_name.'</font></b><br /><br />����������:<br />';
							switch ($this->fact['oclevel'])
							{
								case 1:
								{
									echo '������� ������ = 8<br />������� "����������" = 0<br />����� = 120 ���.<br />';
								}
								break;
								case 2:
								{
									echo '������� ������ = 12<br />������� "����������" = 55<br />����� = 180 ���.<br />';
								}
								break;
								case 3:
								{
									echo '������� ������ = 16<br />������� "����������" = 85<br />����� = 240 ���.<br />';
								}
								break;
								case 4:
								{
									echo '������� ������ = 20<br />������� "����������" = 115<br />����� = 300 ���.<br />';
								}
								break;
								case 5:
								{
									echo '������� ������ = 24<br />������� "����������" = 145<br />����� = 420 ���.<br />';
								}
								break;
							}
							$sel_schema = myquery("SELECT game_items_schema.*,craft_resource.name FROM game_items_schema,craft_resource WHERE game_items_schema.item_id=".$this->fact['id']." AND game_items_schema.res_id=craft_resource.id");
							while ($schema = mysql_fetch_array($sel_schema))
							{
								echo '<br />������: <b>'.$schema['name'].'</b>, ����������: <b>'.$schema['col'].'</b>';	
							}                          
						}
						elseif ($this->fact['type']==21)
						{
							$item_name = mysqlresult(myquery("SELECT name FROM game_items_factsheet WHERE id=".$this->fact['quantity'].""),0,0);
							echo '<br />������������ � ���������: <b><font color=red>'.$item_name.'</font></b><br /><br />';
						}
											}
					if ($this->fact['can_up']==0)
					{
						echo '<br /><br />������ ������� ����� ������.';
					}
					if ($this->fact['clan_id']>0)
					{
						$nazv = mysqlresult(myquery("SELECT nazv FROM game_clans WHERE clan_id=".$this->fact['clan_id'].""),0,0);
						echo'<br>������ ��� �����: <b>'.$nazv.'</b><br>';
					}
					if ($this->fact['id']==license_item_id)
					{
						list($rustown) = mysql_fetch_array(myquery("SELECT rustown FROM game_gorod WHERE town=".$this->item['for_town'].""));
						echo '<br /><br />�������� �� ������������� ������������ � ������ <b>'.$rustown.'</b>';
					}
					if ($this->item['kleymo']==1)
					{
						echo '<br /><i><u>������� ��������� ������ <img src="http://'.img_domain.'/clan/'.$this->item['kleymo_id'].'.gif"><br />������ ������� ����� ���� ��������� � ��������� ����� ����� � ����� �����</u></i><br />';
					}
					if ($this->item['kleymo']==2)
					{
						$name_user = get_user("name",$this->item['kleymo_id']);
						echo '<br /><i><u>������� ��������� ������� <b>'.$name_user.'</b><br />������ ������� ����� ���� ��������� � ��������� ��������� ������ � ����� �����</u></i><br />';
					}
				}
				if ($from_town==0)
				{
					echo'</div></td></tr></table>';
				}
			}
			else
			{
				echo '<span class="ERROR">�� ����������������</span>';
			}
			QuoteTable('close');
		}
	}

	public function hint($id=0,$check_up=0,$str_begin="<span ",$FROM_template=0)
	{                                    
		//������� ���������-����������, ������ ���� ������� ������������ � ������������ ��� ���������� ������������� ����� ��� �������� ��������
		if ($id>0)
		{
			if ($FROM_template==0)
			{
				$this->init_item($id);
			}
			else
			{
				$this->init_fact($id);    
			}
		}
		if ($check_up!=0)
		{
			$can_up = $this->check_up();
		}
		echo $str_begin;
		//��� ����
		?> onmousemove=movehint(event) onmouseover="showhint('<?php
		;
		if ($this->item['ref_id']==1 AND $this->item['priznak']==0 AND $this->fact["type"]!=95 AND !$this->counted_item() and ($this->item['user_id']==$this->item['kleymo_id'] or $this->item['kleymo']==0))			
		{
			echo '<b><font color=#FF0000>�� ����������������</font></b>';
		}
		else
		{
			echo '<center><font color=#0000FF>'.htmlspecialchars($this->fact['name']).'</font>';
			if ($this->fact['curse']!='')
			{
				$str     = htmlspecialchars($this->fact['curse']);
				$order   = array("\r\n", "\n", "\r");
				$replace = '<br />';
				$newstr = str_replace($order, $replace, $str);
				echo '<hr><font color=#000000>'.$newstr.'</font>';
			}
		}
		?>','<?php echo '<font color=000000>';
		if ($this->item['ref_id']==1 AND $this->item['priznak']==0 AND $this->fact["type"]!=95 AND !$this->counted_item() and ($this->item['user_id']==$this->item['kleymo_id'] or $this->item['kleymo']==0))
		{
			echo '<b><font color=#FF0000>�� ����������������</font></b>';
		}
		else
		{
			if ($FROM_template==0) 
			{
				if ($this->item['dead_time']>0)
				{
					echo '<i><u>������� ����� ��������� � '.date("H:i d.m.Y",$this->item['dead_time']).'</u></i><br /><br />';
				}
				if ($this->item['personal']>0)
				{
					$name_user = get_user("name",$this->item['personal']);
					echo '<i><u>������ ������� ������: <b>'.$name_user.'</b></u></i><br /><br />';
				} 
				elseif ($this->fact['personal']==2)
				{
					echo '<i><u>������� ������ ������ ����� ��������!</u></i><br /><br />';
				}
				if ($this->item['kleymo']==1)
				{
					echo '<i><u>������� ��������� ������ <img src=http://'.img_domain.'/clan/'.$this->item['kleymo_id'].'.gif><br />������ ������� ����� ���� ��������� � ��������� ����� ����� � ����� �����</u></i><br /><br />';
				}
				elseif ($this->item['kleymo']==2)
				{
					$name_user = get_user("name",$this->item['kleymo_id']);
					echo '<i><u>������� ��������� ������� <b>'.$name_user.'</b><br />������ ������� ����� ���� ��������� � ��������� ��������� ������ � ����� �����</u></i><br /><br />';
				}
			}
			if ($this->fact['view']==1 OR $this->item['user_id']==$this->char['user_id'] or $this->item['kleymo_id']==$this->char['user_id'])
			{
				if ($this->fact['race']<>0) echo'<br />������ ��� ����: <font color=ff0000><b>'.mysqlresult(myquery("SELECT name FROM game_har WHERE id=".$this->fact['race'].""),0,0).'</b></font>';
				if ($this->fact["type"]!=99 AND $this->fact["type"]!=95 AND $this->fact["type"]!=98) echo '<br />���: '.type_str($this->fact["type"]).'';
				if ($this->fact['in_two_hands']==1) echo '<br />���������';
				if ($this->fact["type"]==1 OR $this->fact["type"]==18)
				{
					switch ($this->fact["type_weapon"])
					{
						case 1:{echo '<br />����� ������: ��������';}break;
						case 2:{echo '<br />����� ������: ����������';}break;
						case 3:{echo '<br />����� ������: �������';}break;
						case 4:{echo '<br />����� ������: ��������';}break;
						case 5:{echo '<br />����� ������: �������';}break;
						case 6:{echo '<br />����� ������: �����������';}break;
					}
				}
				if ($this->fact['indx']<>0)
					if ($this->fact['type'] == 1 or $this->fact['type'] == 3 or $this->fact['type'] == 19 or $this->fact['type'] == 21) echo '<br />����: ' . $this->fact['indx'] . '&nbsp;&plusmn;&nbsp;' . $this->fact['deviation'] . '';
				if ($this->fact['type'] == 4) echo '<br />������: ' . $this->fact['indx'] . '';
				if ($FROM_template==0) 
				{
					if ($this->fact['type'] == 3) echo '<br />���-�� �������: '.$this->item['count_item'].'';
					if (!$this->counted_item() AND $this->fact['type'] != 95 AND $this->fact['type'] != 20)
					{
						$use=$this->item['item_uselife'];
						echo '<br />���������: '.$use.'%';
					}
					if (!$this->counted_item() AND $this->fact['breakdown']==1)
					{
						echo '<br />�������������: '.$this->item['item_uselife_max'].'/'.$this->fact['item_uselife_max'];
					}
				}

				if ($this->fact['weight'] >0 ) 
				{
					if ($FROM_template==0 AND $this->counted_item())
					{
						echo'<br /><br />���: '.$this->fact['weight']*$this->item['count_item'].'';
					}
					else
					{
						echo'<br /><br />���: '.$this->fact['weight'].'';
					}
				}
					   
				if ($this->fact['can_up']==0)
				{
					echo '<br /><br />������ ������� ����� ������.';
				}
				if ($this->fact['clan_id']>0)
				{
					$nazv = mysqlresult(myquery("SELECT nazv FROM game_clans WHERE clan_id=".$this->fact['clan_id'].""),0,0);
					echo'<br>������ ��� �����: <b>'.$nazv.'</b><br>';
				}
				if ($this->fact['id']==license_item_id AND $FROM_template==0)
				{
					list($rustown) = mysql_fetch_array(myquery("SELECT rustown FROM game_gorod WHERE town=".$this->item['for_town'].""));
					echo '<br />�������� �� ������������� ������������ � ������ <b>'.$rustown.'</b><br />';
				}
				if (!$this->counted_item() AND $this->fact['type']!=20)
				{           
					echo'<br /><b>������� ��������:</b>'; 
					if ($this->fact['dstr']<>'0')  echo '<br />���� ��: '.$this->fact['dstr'].'';
					if ($this->fact['dntl']<>'0')  echo '<br />��������� ��: '.$this->fact['dntl'].'';
					if ($this->fact['dpie']<>'0')  echo '<br />�������� ��: '.$this->fact['dpie'].'';
					if ($this->fact['dvit']<>'0')  echo '<br />������ ��: '.$this->fact['dvit'].'';
					if ($this->fact['ddex']<>'0')  echo '<br />������������ ��: '.$this->fact['ddex'].'';
					if ($this->fact['dspd']<>'0')  echo '<br />�������� ��: '.$this->fact['dspd'].'';
					if ($this->fact['dlucky']<>'0')  echo '<br />����� ��: '.$this->fact['dlucky'].'';
					if ($this->fact['hp_p']<>'0')  echo '<br />����� ��: '.$this->fact['hp_p'].'';
					if ($this->fact['mp_p']<>'0')  echo '<br />���� ��: '.$this->fact['mp_p'].'';
					if ($this->fact['stm_p']<>'0') echo '<br />������� ��: '.$this->fact['stm_p'].'';
					if ($this->fact['pr_p']<>'0') echo '<br />����� ��: '.$this->fact['pr_p'].'';
					if ($this->fact['cc_p']<>'0')  echo '<br />������� ��������� ��: '.$this->fact['cc_p'].'';

					echo '<br /><br /><font color=ff0000>������� �������:';
					if ($this->fact['oclevel']>'0')  echo '<br />�������: '.$this->fact['oclevel'].'';
					if ($this->fact['ostr']>'0')  echo '<br />����: '.$this->fact['ostr'].'';
					if ($this->fact['ontl']>'0')  echo '<br />���������: '.$this->fact['ontl'].'';
					if ($this->fact['opie']>'0')  echo '<br />��������: '.$this->fact['opie'].'';
					if ($this->fact['ovit']>'0')  echo '<br />������: '.$this->fact['ovit'].'';
					if ($this->fact['odex']>'0')  echo '<br />������������: '.$this->fact['odex'].'';
					if ($this->fact['ospd']>'0')  echo '<br />��������: '.$this->fact['ospd'].'';
					if ($this->fact['olucky']>'0')  echo '<br />�����: '.$this->fact['olucky'].'';
					echo '</font>';
					if ($check_up>0)
					{
						echo '<hr>';
						if ($can_up == 1)
						{
							echo '<br />�� ������ ����� ���� �������';
						}
						else
						{
							echo $can_up.'<br /><b><font color=red>�� �� ������� ����� ���� �������</font></b>';
						}
					}
				}
				else
				{
					if ($this->fact['type']==20)
					{
						$item_name = mysqlresult(myquery("SELECT name FROM game_items_factsheet WHERE id=".$this->fact['indx'].""),0,0);
						echo '<br />����� ��� ������������ ��������: <b><font color=red>'.$item_name.'</font></b><br /><br />����������:<br />';
						switch ($this->fact['oclevel'])
						{
							case 1:
							{
								echo '������� ������ = 8<br />������� \\\'����������\\\' = 0<br />����� = 120 ���.<br />';
							}
							break;
							case 2:
							{
								echo '������� ������ = 12<br />������� \\\'����������\\\' = 55<br />����� = 180 ���.<br />';
							}
							break;
							case 3:
							{
								echo '������� ������ = 16<br />������� \\\'����������\\\' = 85<br />����� = 240 ���.<br />';
							}
							break;
							case 4:
							{
								echo '������� ������ = 20<br />������� \\\'����������\\\' = 115<br />����� = 300 ���.<br />';
							}
							break;
							case 5:
							{
								echo '������� ������ = 24<br />������� \\\'����������\\\' = 145<br />����� = 420 ���.<br />';
							}
							break;
						}                          
						$sel_schema = myquery("SELECT game_items_schema.*,craft_resource.name FROM game_items_schema,craft_resource WHERE game_items_schema.item_id=".$this->fact['id']." AND game_items_schema.res_id=craft_resource.id");
						while ($schema = mysql_fetch_array($sel_schema))
						{
							echo '<br />������: <b>'.$schema['name'].'</b>, ����������: <b>'.$schema['col'].'</b>';    
						}                          
					}
					elseif ($this->fact['type']==21)
					{
						$item_name = mysqlresult(myquery("SELECT name FROM game_items_factsheet WHERE id=".$this->fact['quantity'].""),0,0);
						echo '<br />������������ � ���������: <b><font color=red>'.$item_name.'</font></b><br /><br />';
					}
					elseif ($this->fact['type']==13)
					{
						echo '<br><br>';
						list($dlit)=mysql_fetch_array(myquery("SELECT dlit FROM game_eliksir_dlit WHERE elik_id=".$this->fact['id'].""));
						$dlit=$dlit/60;
						if ($this->fact['id']==eliksir_mogushestva_item_id)
						{
							echo '<i>���������� 50 ������ � ������������� ������ ����� �� '.$dlit.' '.pluralForm($dlit,'������','������','�����').'.</i>';
						}
						elseif ($this->fact['id']==eliksir_bodrosti_item_id)
						{
							echo "<i>��� ��������� �������� �� �� ������ �������� (������� \'���������, �� ������\' �� �������� ���� ��� ���������). ��������� ".$dlit." ".pluralForm($dlit,'������','������','�����').".</i>";
						}
						elseif ($this->fact['id']==eliksir_zorkosti_item_id)
						{
							echo '<i>��� ������������� �������� �� ������� ������������ �������� �������� ����������� ������ ������� (������ ������ ���� ��������� �������). ��������� '.$dlit.' '.pluralForm($dlit,'������','������','�����').'.</i>';
						}
						elseif ($this->fact['id']==eliksir_nevidimka_item_id)
						{
							echo '<i>��� ��������� �������� �� ������� ��� ������ �������. ��������� '.$dlit.' '.pluralForm($dlit,'������','������','�����').'.</i>';
						}
						else
						{
							if ($dlit==0)
							{
								if ($this->hp_p>0)
								{
									echo '<i>�������� �������� �� '.$this->hp_p.' '.pluralForm($this->hp_p,'�������','�������','������').'.</i><br>';
								}
								if ($this->mp_p>0)
								{
									echo '<i>�������� ���� �� '.$this->mp_p.' '.pluralForm($this->mp_p,'�������','�������','������').'.</i><br>';
								}
								if ($this->stm_p>0)
								{
									echo '<i>�������� ������� �� '.$this->stm_p.' '.pluralForm($this->stm_p,'�������','�������','������').'.</i><br>';
								}
								if ($this->pr_p>0)
								{
									echo '<i>�������� ����� �� '.$this->pr_p.' '.pluralForm($this->pr_p,'�������','�������','������').'.</i><br>';
								}
							}
							else
							{
								if ($this->hp_p>0)
								{
									echo '<i>�������� ������������ ������� �������� �� '.$this->hp_p.' '.pluralForm($this->hp_p,'�������','�������','������').'.</i><br>';
								}
								if ($this->mp_p>0)
								{
									echo '<i>�������� ������������ ������� ���� �� '.$this->mp_p.' '.pluralForm($this->mp_p,'�������','�������','������').'.</i><br>';
								}
								if ($this->stm_p>0)
								{
									echo '<i>�������� ������������ ������� ������� �� '.$this->stm_p.' '.pluralForm($this->stm_p,'�������','�������','������').'.</i><br>';
								}
								if ($this->pr_p>0)
								{
									echo '<i>�������� ������������ ������� ����� �� '.$this->pr_p.' '.pluralForm($this->pr_p,'�������','�������','������').'.</i><br>';
								}
								if ($this->dstr>0)
								{
									echo '<i>�������� ���� �� '.$this->dstr.' '.pluralForm($this->dstr,'�������','�������','������').'.</i><br>';
								}
								if ($this->dntl>0)
								{
									echo '<i>�������� ��������� �� '.$this->dntl.' '.pluralForm($this->dntl,'�������','�������','������').'.</i><br>';
								}
								if ($this->dpie>0)
								{
									echo '<i>�������� �������� �� '.$this->dpie.' '.pluralForm($this->dpie,'�������','�������','������').'.</i><br>';
								}
								if ($this->dspd>0)
								{
									echo '<i>�������� �������� �� '.$this->dspd.' '.pluralForm($this->dspd,'�������','�������','������').'.</i><br>';
								}
								if ($this->dvit>0)
								{
									echo '<i>�������� ������ �� '.$this->dvit.' '.pluralForm($this->dvit,'�������','�������','������').'.</i><br>';
								}
								if ($this->ddex>0)
								{
									echo '<i>�������� ������������ �� '.$this->ddex.' '.pluralForm($this->ddex,'�������','�������','������').'.</i><br>';
								}
								if ($this->dlucky>0)
								{
									echo '<i>�������� ����� �� '.$this->dlucky.' '.pluralForm($this->dlucky,'�������','�������','������').'.</i><br>';
								}
								if ($this->cc_p>0)
								{
									echo '<i>�������� ������������ ��� �� '.$this->cc_p.' '.pluralForm($this->cc_p,'�������','�������','������').'.</i><br>';
								}
								echo '<i>��������� '.$dlit.' '.pluralForm($dlit,'������','������','�����').'.</i>';
							}
						}
					}
				}
			}
		}
		?>',0,1,event)" onmouseout="showhint('','',0,0,event)"
		<?php
		echo '>';
	}
	
	public function identify($id=0)
	{
		//������� ������ ���� � ������ � priznak=0 � used=0 � ref_id=1 � ����� ������ ���� �� ����������� �������� � �������� ������ ���������� ��������������
		$ar = array(0,'');
		if ($id>0)
		{
			$this->init_item($id);
		}
		if ($this->item['user_id']==$this->char['user_id'] AND $this->item['used']==0 AND $this->item['ref_id']==1 AND $this->item['priznak']==0)
		{
			if ($this->fact['oclevel']==0) $this->fact['oclevel']=$this->char['clevel'];
			if ($this->fact['oclevel']<=10) $cena = round($this->fact['oclevel']*0.5,0);
			else $cena = $this->fact['oclevel'];
			if ($this->char['GP']>=$cena)
			{
				if ($this->fact['type']==22)
				{
					$check1=myquery("SELECT * FROM game_items WHERE item_id=".$this->item['item_id']." and user_id=".$this->char['user_id']." and ref_id=0 and priznak=0");
					if (mysql_num_rows($check1)>0) $check2=1;
				}
				if (isset($check2))
				{
				$upd=myquery("UPDATE game_items set count_item=count_item+1 WHERE item_id=".$this->item['item_id']." and user_id=".$this->char['user_id']." and ref_id=0 and priznak=0 Limit 1");
				$del=myquery("DELETE FROM game_items WHERE id=".$this->item['id']."");
				}
				else
				{
					$upd=myquery("UPDATE game_items set ref_id='0' WHERE id=".$this->item['id']."");
				}
				$user=myquery("UPDATE game_users set GP=GP-$cena,CW=CW-'".($cena*money_weight)."' WHERE user_id=".$this->char['user_id']."");
				setGP($this->char['user_id'],-$cena,7);
				$ar[0]=$cena;
				$ar[1]=$this->fact['name'];
			}
			else echo '��������� �����';
		}
		else 
		{
			//echo '������ ����������������';
		}
		return $ar;
	}

	public function kleymo_return($id) //������� ��������� �������� ���������
	{
		$this->init_item($id);
		$error = 1;
		if ($this->item['kleymo']==1)
		{
			//������� ��������� ����������� ��������
			if (mysql_num_rows(myquery("SELECT clan_id FROM game_clans WHERE glava=".$this->char['user_id']." AND raz=0"))>0)
			{
				if ($this->item['kleymo_id']==$this->char['clan_id'])
				{
					if (($this->char['CC']-$this->char['CW'])>=$this->fact['weight'])
					{
						$error = 0;
					}
					else
					{
						echo '�� ������� ���������� ����� � ���������!<br />';
					}
				}
				else
				{
					echo '������� ��������� �� ����� ������!<br />';
				}    
			}
			else
			{
				echo '�� �� ����� �����!<br />';
			}
		}
		else
		{
			$error = 0;
			//������� ������� ����������� ��������
			if ($this->item['kleymo_id']==$this->char['user_id'])
			{
				if (($this->char['CC']-$this->char['CW'])>=$this->fact['weight'])
				{					
				}
				else
				{
					$error = 1;
					echo '�� ������� ���������� ����� � ���������!<br />';
				}
				if ($this->item['personal']>0 and $this->item['personal']!=$this->char['user_id'])
				{
					$error = 1;
					echo '������ ������� ������ ����� �������!<br/>';
				}
			}			
			else
			{
				$error = 1;
				echo '������� ��������� �� �����!<br />';
			}    
		}
		if ($error == 0)
		{
			myquery("UPDATE game_items SET user_id=".$this->char['user_id'].",priznak=0,map_name=0,map_xpos=0,map_ypos=0,town=0,sell_time=0,post_to=0,post_var=0 WHERE id=".$this->item['id']."");
			myquery("UPDATE game_users SET CW=CW+".$this->fact['weight']." WHERE user_id=".$this->char['user_id']."");
			myquery("UPDATE game_users_archive SET CW=CW+".$this->fact['weight']." WHERE user_id=".$this->char['user_id']."");
			if ($this->item['priznak']==0)
			{
				if ($this->item['used']>0)
				{
					$this->item_down();
				}    
				myquery("UPDATE game_users SET CW=CW-".$this->fact['weight']." WHERE user_id=".$this->item['user_id']."");
				myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES ('".$this->item['user_id']."', '0', '������� ����������� ��������', '�������� ������ ".$this->char['name']." �������� ".$this->fact['name']." ������ ���� �������, ������������ � ����','0','".time()."')");
			}
		}
	}
	
	public function kleymo_return_from($id, $user_id) //������� ��������� �������� �� ������
	{
		$this->init_item($id);
		$error = 1;
		if ($this->item['kleymo']==1)
		{
			//������� ��������� ����������� ��������
			$check_glava=myquery("SELECT glava FROM game_clans WHERE clan_id='".$this->item['kleymo_id']."' AND raz=0");
			if (mysql_num_rows($check_glava)>0)
			{
				list($komu)=mysql_fetch_array($check_glava);
				$error = 0;
			}
		}
		else
		{
			//������� ������� ����������� ��������
			$komu=$this->item['kleymo_id'];
			$error=0;
		}
		// �� ���������� ������ ������� ����������� ������
		if ($this->item['personal']>0 and $this->item['personal']!=$komu)
		{
			$error = 1;
		}
		if ($error == 0)
		{
			myquery("UPDATE game_items SET user_id='".$komu."',priznak=0,map_name=0,map_xpos=0,map_ypos=0,town=0,sell_time=0,post_to=0,post_var=0 WHERE id=".$this->item['id']."");
			myquery("UPDATE game_users SET CW=CW+".$this->fact['weight']." WHERE user_id='".$komu."'");
			myquery("UPDATE game_users_archive SET CW=CW+".$this->fact['weight']." WHERE user_id='".$komu."'");
			if ($this->item['priznak']==0)
			{
				if ($this->item['used']>0)
				{
					$this->item_down();
				}    
				myquery("UPDATE game_users SET CW=CW-".$this->fact['weight']." WHERE user_id='".$user_id."'");
				myquery("UPDATE game_users_archive SET CW=CW-".$this->fact['weight']." WHERE user_id='".$user_id."'");
				myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES ('".$komu."', '0', '������� ����������� ��������', '��� ��������� ������� ".$this->fact['name']."','0','".time()."')");
			}
			return 1;
		}
		else
		{
			return 0;
		}
	}
	
	public function kleymo($type_kleymo,$unset)
	{
		//������� ������ ���� � ������ � priznak=0 � used=0 � ref_id=1 � ����� ������ ���� �� ����������� �������� � �������� ������ ���������� ��������������
		$ar = array(0,'');
		if ($unset==0)
		{
			if ($this->item['user_id']==$this->char['user_id'] AND $this->item['used']==0 AND $this->item['ref_id']==0 AND $this->item['priznak']==0 AND $this->item['kleymo']==0 AND !$this->counted_item() AND $this->fact['personal']==0)
			{
				if ($type_kleymo==2) //������ ���������
				{
					$cena = $this->char['clevel'];
				}
				if ($type_kleymo==1) //�������� ���������
				{
					$cena = 20+$this->fact['oclevel'];
				}
				if ($this->char['GP']>=$cena)
				{
					if ($type_kleymo==1)
					{
						$kleymo_id = $this->char['clan_id'];
					}
					else
					{
						$kleymo_id = $this->char['user_id'];
					}
					$sel_kleymo_nomer = myquery("SELECT kleymo_nomer FROM game_items WHERE kleymo=$type_kleymo AND kleymo_id=$kleymo_id ORDER BY kleymo_nomer DESC LIMIT 1");
					if (mysql_num_rows($sel_kleymo_nomer)>0)
					{
						list($kleymo_nomer)= mysql_fetch_array($sel_kleymo_nomer);
						$kleymo_nomer++;
					}
					else
					{
						$kleymo_nomer = 1;
					}
					$upd=myquery("UPDATE game_items set kleymo='$type_kleymo',kleymo_nomer=$kleymo_nomer,kleymo_id=$kleymo_id WHERE id=".$this->item['id']."");
					$user=myquery("UPDATE game_users set GP=GP-$cena,CW=CW-'".($cena*money_weight)."' WHERE user_id=".$this->char['user_id']."");
					setGP($this->char['user_id'],-$cena,82);
					$ar[0]=$cena;
					$ar[1]=$this->fact['name'];
				}
				else echo '��������� �����';
			}
		}
		else
		{
			if ($this->item['user_id']==$this->char['user_id'] AND $this->item['used']==0 AND $this->item['ref_id']==0 AND $this->item['priznak']==0 AND $this->item['kleymo']!=0 AND !$this->counted_item())
			{
				$flag = 0;
				if ($this->item['kleymo']==1)
				{
					if ($this->item['kleymo_id']==$this->char['clan_id'])
					{
						$flag = 1;
					}
				}
				if ($this->item['kleymo']==2)
				{
					if ($this->item['kleymo_id']==$this->char['user_id'])
					{
						$flag = 1;
					}
				}
				if ($this->item['kleymo']==2) //������ ������ ������� ��������
				{
					$cena = $this->char['clevel']*2;
				}
				if ($this->item['kleymo']==1) //������ ������ ��������� �������� ��������
				{
					$cena = (20+$this->fact['oclevel'])*2;
				}
				if ($this->char['GP']>=$cena AND $flag==1)
				{
					$upd=myquery("UPDATE game_items set kleymo=0,kleymo_nomer=0,kleymo_id=0 WHERE id=".$this->item['id']."");
					$user=myquery("UPDATE game_users set GP=GP-$cena,CW=CW-'".($cena*money_weight)."' WHERE user_id=".$this->char['user_id']."");
					setGP($this->char['user_id'],-$cena,83);
					$ar[0]=$cena;
					$ar[1]=$this->fact['name'];
				}
				else echo '��������� �����';
			}
		}
		return $ar;
	}

	public function sell($id=0, $kol=1)
	{
		global $shop;
		$ar = array(-1,0,'');
		//������� ������ ���� � ������ � priznak=0 � used=0 � ref_id=0 � ����� ������ ���� �� ����������� �������� � �������� ������ �������� ��� ����
		if ($id>0)
		{
			$this->init_item($id);
		}
		$user_id = $this->char['user_id'];
		if ($this->item['count_item']<$kol)
		{			
			echo'<center><b><font color=ff0000 face=verdana size=2>� ��� ��� ������ ���������� ��������!</font></b></center>';
		}
		elseif ($this->item['user_id']==$user_id AND $this->item['used']==0 AND $this->item['ref_id']==0 AND $this->item['priznak']==0 AND $this->item['kleymo']==0)
		{
            $cena = round($this->fact['item_cost']/100*$shop['cena_pok'],2);
			if ($this->item['item_uselife']<0) $this->item['item_uselife']=0;
			if ($this->fact['item_uselife_max']==0) 
			{
				$this->item['item_uselife_max']=1;
				$this->fact['item_uselife_max']=1;
			}
			if ($this->item['kleymo']>0)
			{
				$cena = 0;
			}
			else
			{
				$cena = round($cena*(($this->item['item_uselife_max']-1+$this->item['item_uselife']/100)/$this->fact['item_uselife_max']),2)*$kol;
			}			
			echo'<center><b><font color=ff0000 face=verdana size=2>������ �������: '.$this->fact['name'].' �� '.$cena.' �������</font></b></center>';
			$this->admindelete(0, $kol, 3);
			myquery("UPDATE game_users set GP=GP+".$cena." WHERE user_id=".$this->char['user_id']."");
			setGP($this->char['user_id'],$cena,8);
			$ar[0]=$cena;
			$ar[1]=$this->fact['weight']*$kol;
			$ar[2]=$this->fact['name'];
		}
		else
		{
			//echo'<center><b><font color=ff0000 face=verdana size=2>������ �������</font></b></center>';
		}
		return $ar;
	}
	
	public function buy($id=0)
	{
		global $shop;
			
		$ar = array(0,0,'');
		//����� ������ ���� �� ����������� �������� � �������� ������ ��������� ��� ���� � ��� ���� ���� �� ������� � � ������ ���������� ���������� ����
		if ($id>0)
		{
			$this->init_fact($id);
		}
		else
		{
			return $ar;
		}
		$need_add = 1;

		if ($this->fact['personal'] == 1)
		{
			$personal = $this->char['user_id'];
		}
		else
		{
			$personal = 0;
		}
		
		$cena=round($this->fact['item_cost']/100*$shop["cena_prod"],2);
		$prov=mysql_result(myquery("SELECT count(*) FROM game_wm WHERE user_id=".$this->char['user_id']." AND type=1"),0,0);
		
		if ($need_add == 1 and $this->char['GP']>=$cena and ($this->char['CW']<=($this->char['CC']-$this->fact['weight']) OR $prov>0))
		{
			$error = '';
			$need_add = 1;

			$kol_inv = mysql_result(myquery("SELECT SUM(`count_item`) FROM game_items WHERE user_id=".$this->char['user_id']." AND item_id=".$this->fact['id']." AND priznak=0"),0,0);

			if (($this->fact['kol_per_user'] != 0) && ($kol_inv >= $this->fact['kol_per_user']))
			{
				$error = '������ ������ ����� '.$this->fact['kol_per_user'].' ���������.';
				$need_add = 0;
			}

			if ($this->counted_item())
			{
				if ($kol_inv > 0 && $error == '')
				{
					myquery("UPDATE game_items SET count_item=count_item+1, dead_time=(CASE WHEN ".$this->fact['life_time']."=0 THEN 0 ELSE ".($this->fact['life_time']+time())." END) WHERE priznak = 0 AND item_id=".$this->fact['id']." AND user_id=".$this->char['user_id']." LIMIT 1;");
					$need_add = 0;
				}
				else
				{
					$need_add = 1;
				}
				$item_uselife = 100;
			}
			else
			{
				$item_uselife = $this->fact['item_uselife'];
			}

			if ($this->fact['clan_id']>0 AND $this->fact['clan_id']!=$this->char['clan_id'])
			{
				$error = '���� ������� �� ��� ������ �����!';
			}
			if ($error=='')
			{
				$ar[0] = $cena;
				$ar[1] = $this->fact['weight'];
				$ar[2] = $this->fact['name'];				
				if ($need_add==1)
				{
					$count_item = 1;
					if ($this->fact['type']==3)
					{
						$count_item = $item_uselife;
						$item_uselife = 100;
					}
					myquery("INSERT INTO game_items (user_id, item_id, item_uselife, item_uselife_max, item_cost, ref_id, shop_FROM, count_item, dead_time, personal) values (".$this->char['user_id'].", ".$this->fact['id'].", $item_uselife, ".$this->fact['item_uselife_max'].", ".$this->fact['item_cost'].", 0, ".$shop['id'].", ".$count_item.", (CASE WHEN ".$this->fact['life_time']."=0 THEN 0 ELSE ".($this->fact['life_time']+time())." END),  ".$personal.")");
				}
				if ($this->char['clevel']<5)
				{
					if ($this->fact['type']==1)
					{   
						//���� ���������� ������ - ��� ����� ���� �����, ���������� ��������� �����
						 $step = mysqlresult(myquery("SELECT step FROM game_users_intro WHERE user_id=".$this->char['user_id'].""),0,0);
						 if ($step==6)
						 {
							 myquery("UPDATE game_users_intro SET step=7 WHERE user_id=".$this->char['user_id']."");
						 }
					}
					if ($this->fact['type']==4 OR $this->fact['type']==5)
					{   
						//���� ���������� ������ ��� ��� - ��� ����� ���� �����, ���������� ��������� �����
						 $step = mysqlresult(myquery("SELECT step FROM game_users_intro WHERE user_id=".$this->char['user_id'].""),0,0);
						 if ($step==8)
						 {
							 myquery("UPDATE game_users_intro SET step=9 WHERE user_id=".$this->char['user_id']."");
						 }
					}
				}
				
			}
			else
			{
				echo '<b><center><font color=ff0000><br> '.$error.'</font></center></b>';
			}
		}		
        return $ar;
	}

	public function repair($id=0)
	{
		$ar = array(0,'');
		//����� ������ ���� �� ����������� �������� � �������� ������ ������������� ��� ����
		if ($id>0)
		{
			$this->init_item($id);
		}
		if ($this->item['ref_id']==0 AND $this->item['priznak']==0 AND $this->item['user_id']==$this->char['user_id'] AND ($this->item['used']==0 OR $this->item['item_uselife']>=10) AND $this->fact['type']!=20 AND $this->fact['type']<90  AND !$this->counted_item() AND $this->item['item_uselife']<100)
		{
			if ($this->fact['type']==3)
			{
				$this->fact['item_uselife'] = 100;
			}
			if ($this->char['clevel']<5) 
			{
				$cena = 0;
			}
			elseif ($this->fact['oclevel']<15)
			{
				$cena = 5;
			}
			else 
			{
				$cena = 15;
			}
			
			if ($this->char['GP']>=$cena)
			{
				$ar[0] = $cena;
				$ar[1] = $this->name;
				$breakdown = 0;				
				if ($this->fact['breakdown']==1)
				{					
					$breakdown = 1;
					//���� ��������� �������� �������������, �� � 5% ������ ����� ��� �������������� �������������
					if ($this->item['item_uselife']<=0)
					{
						$crack = mt_rand(1,100);
						if ($crack<=5)
						{
							echo'<center><BR><h3>��, ��� ����� ����... ���� �� ���� ��� ������� - �� ���� ������� ������� �������������� �������������...</h3>';
							$breakdown = 2;
						}
					}
				}   
				if ($breakdown > 0 and $this->item['item_uselife_max']-$breakdown<=0)
				{
					$this->admindelete(0,1,2);
					echo'<center><BR><BR><h3>�� ������...���� ������� ��� ������ ��������<br>*������ ����������� ���� ��������� ����������� ����*</h3>';
				}
				else
				{
					$upd=myquery("UPDATE game_items set item_uselife=".$this->fact['item_uselife'].",item_uselife_max=item_uselife_max-$breakdown WHERE id=".$this->item['id']."");
					echo'<center><BR><h3>���������������</h3>';
				}
				$user=myquery("UPDATE game_users set GP=GP-$cena,CW=CW-'".($cena*money_weight)."' WHERE user_id=".$this->item['user_id']."");
				$user=myquery("UPDATE game_users_archive set GP=GP-$cena,CW=CW-'".($cena*money_weight)."' WHERE user_id=".$this->item['user_id']."");
				setGP($this->item['user_id'],-$cena,11);				
			}
			else echo'<center><BR><BR><h3>�� ������� �����</h3>';
		}
		return $ar;
	}
	
	public function use_item($id=0)
	{
		global $char;
		if ($id>0)
		{
			$this->init_item($id);
		}
		//����� ������������ ������ ��������
		if ($this->fact['type']==13)
		{
			if ($this->item['user_id']==$this->char['user_id'])
			{
				if ($this->item['priznak']==0)
				{
					list($dlit)=mysql_fetch_array(myquery("SELECT dlit FROM game_eliksir_dlit WHERE elik_id=".$this->fact['id'].""));
					//�������� ���������������� ��������
					if ($dlit>0)
					{
						if ($this->fact['id']==eliksir_bodrosti_item_id)
						{
							//����� ��������
							myquery("DELETE FROM game_obelisk_users WHERE user_id=".$this->char['user_id']." AND type=3");
							myquery("INSERT INTO game_obelisk_users (user_id,time_end,type) VALUES (".$this->char['user_id'].",".(time()+$dlit).",3)");
							$this->message = $this->message.'<b><font color=red>�� '.echo_sex('�����','������').' ����� "'.$this->fact['name'].'"</font></b><br>'; 
						}
						elseif ($this->fact['id']==eliksir_zorkosti_item_id)
						{
							//����� ��������
							myquery("DELETE FROM game_obelisk_users WHERE user_id=".$this->char['user_id']." AND type=4");
							myquery("INSERT INTO game_obelisk_users (user_id,time_end,type) VALUES (".$this->char['user_id'].",".(time()+$dlit).",4)");
							$this->message = $this->message.'<b><font color=red>�� '.echo_sex('�����','������').' ����� "'.$this->fact['name'].'"</font></b><br>'; 
						}
						elseif ($this->fact['id']==eliksir_nevidimka_item_id)
						{
							//����� �����������
							myquery("DELETE FROM game_obelisk_users WHERE user_id=".$this->char['user_id']." AND type=5");
							myquery("INSERT INTO game_obelisk_users (user_id,time_end,type) VALUES (".$this->char['user_id'].",".(time()+$dlit).",5)");
							$this->message = $this->message.'<b><font color=red>�� '.echo_sex('�����','������').' ����� "'.$this->fact['name'].'"</font></b><br>'; 
						}
						elseif ($this->fact['id']==eliksir_mogushestva_item_id)
						{
							//����� ��������
							//�������� �� ������� ������������ ��������
							$check = myquery("SELECT * FROM game_obelisk_users WHERE user_id=".$this->char['user_id']." AND type=2 AND harka='PR' AND time_end>".time()."");
							$effect = 50;
							if (mysql_num_rows($check)>0)
							{
								myquery("UPDATE game_obelisk_users SET time_end=time_end+".$dlit." WHERE user_id=".$this->char['user_id']." AND type=2 AND (harka='PR' or harka='PR_MAX') AND time_end>".time()."");
							}
							else
							{								
								myquery("UPDATE game_users SET PR=PR+".$effect.", PR_MAX=PR_MAX+".$effect." WHERE user_id=".$this->char['user_id']."");
								myquery("INSERT INTO game_obelisk_users (user_id,harka,type,time_end,value) VALUES (".$this->char['user_id'].",'PR',2,".(time()+$dlit).",".$effect."), (".$this->char['user_id'].",'PR_MAX',2,".(time()+$dlit).",".$effect.") ");
							}							
							$this->message = $this->message.'<b><font color=red>�� '.echo_sex('�����','������').' ����� "'.$this->fact['name'].'"</font></b><br>'; 
						}
						elseif ($this->fact['name']=="������� �����")
						{
							$r=mt_rand(1,7);
							$har2="";
							switch ($r)
							{
								case 1: {$har_sql="LUCKY=LUCKY+1"; $har1="LUCKY"; $har_name="�����"; break;}
								case 2: {$har_sql="STR=STR+1"; $har1="STR"; $har_name="����"; break;}
								case 3: {$har_sql="SPD=SPD+1"; $har1="SPD"; $har_name="��������"; break;}
								case 4: {$har_sql="NTL=NTL+1, MP_MAX=MP_MAX+15"; $har1="NTL"; $har2="MP_MAX"; $har_name="���������"; break;}
								case 5: {$har_sql="PIE=PIE+1, STM_MAX=STM_MAX+15"; $har1="PIE"; $har2="STM_MAX"; $har_name="��������"; break;}
								case 6: {$har_sql="VIT=VIT+1"; $har1="VIT"; $har_name="������"; break;}
								case 7: {$har_sql="DEX=DEX+1, HP_MAX=HP_MAX+15"; $har1="DEX"; $har2="HP_MAX"; $har_name="������������"; break;}
							}
							myquery("UPDATE game_users SET ".$har_sql." WHERE user_id=".$this->char['user_id']."");
							myquery("INSERT INTO game_obelisk_users (user_id,harka,type,time_end,value) VALUES (".$this->char['user_id'].",'".$har1."',2,".(time()+$dlit).",1)");
							if ($har2<>"") myquery("INSERT INTO game_obelisk_users (user_id,harka,type,time_end,value) VALUES (".$this->char['user_id'].",'".$har2."',2,".(time()+$dlit).",15)");
							$this->message = $this->message.'<b><font color=red>�� '.echo_sex('�����','������').' ����� "'.$this->fact['name'].'".<br>�������������� "'.$har_name.'" ���������.</font></b><br>'; 
						}
						else
						{
							// ��������� �����
							if ($this->fact['hp_p']>0)
							{
								myquery("UPDATE game_users SET HP_MAX=HP_MAX+".$this->fact['hp_p']." WHERE user_id=".$this->char['user_id']."");
								myquery("INSERT INTO game_obelisk_users (user_id,harka,type,time_end,value) VALUES (".$this->char['user_id'].",'HP_MAX',2,".(time()+$dlit).",".$this->fact['hp_p'].")");
							}
							if ($this->fact['mp_p']>0)
							{
								myquery("UPDATE game_users SET MP_MAX=MP_MAX+".$this->fact['mp_p']." WHERE user_id=".$this->char['user_id']."");
								myquery("INSERT INTO game_obelisk_users (user_id,harka,type,time_end,value) VALUES (".$this->char['user_id'].",'MP_MAX',2,".(time()+$dlit).",".$this->fact['mp_p'].")");
							}
							if ($this->fact['stm_p']>0)
							{
								myquery("UPDATE game_users SET STM_MAX=STM_MAX+".$this->fact['stm_p']." WHERE user_id=".$this->char['user_id']."");
								myquery("INSERT INTO game_obelisk_users (user_id,harka,type,time_end,value) VALUES (".$this->char['user_id'].",'STM_MAX',2,".(time()+$dlit).",".$this->fact['stm_p'].")");
							}
							if ($this->fact['pr_p']>0)
							{
								myquery("UPDATE game_users SET PR_MAX=PR_MAX+".$this->fact['pr_p']." WHERE user_id=".$this->char['user_id']."");
								myquery("INSERT INTO game_obelisk_users (user_id,harka,type,time_end,value) VALUES (".$this->char['user_id'].",'PR_MAX',2,".(time()+$dlit).",".$this->fact['pr_p'].")");
							}
							if ($this->fact['dstr']>0)
							{
								myquery("UPDATE game_users SET STR=STR+".$this->fact['dstr']." WHERE user_id=".$this->char['user_id']."");
								myquery("INSERT INTO game_obelisk_users (user_id,harka,type,time_end,value) VALUES (".$this->char['user_id'].",'STR',2,".(time()+$dlit).",".$this->fact['dstr'].")");
							} 
							if ($this->fact['dspd']>0)
							{
								myquery("UPDATE game_users SET SPD=SPD+".$this->fact['dspd']." WHERE user_id=".$this->char['user_id']."");
								myquery("INSERT INTO game_obelisk_users (user_id,harka,type,time_end,value) VALUES (".$this->char['user_id'].",'SPD',2,".(time()+$dlit).",".$this->fact['dspd'].")");
							} 
							if ($this->fact['dntl']>0)
							{
								myquery("UPDATE game_users SET NTL=NTL+".$this->fact['dntl']." WHERE user_id=".$this->char['user_id']."");
								myquery("INSERT INTO game_obelisk_users (user_id,harka,type,time_end,value) VALUES (".$this->char['user_id'].",'NTL',2,".(time()+$dlit).",".$this->fact['dntl'].")");
							} 
							if ($this->fact['dpie']>0)
							{
								myquery("UPDATE game_users SET PIE=PIE+".$this->fact['dpie']." WHERE user_id=".$this->char['user_id']."");
								myquery("INSERT INTO game_obelisk_users (user_id,harka,type,time_end,value) VALUES (".$this->char['user_id'].",'PIE',2,".(time()+$dlit).",".$this->fact['dpie'].")");
							} 
							if ($this->fact['dvit']>0)
							{
								myquery("UPDATE game_users SET VIT=VIT+".$this->fact['dvit']." WHERE user_id=".$this->char['user_id']."");
								myquery("INSERT INTO game_obelisk_users (user_id,harka,type,time_end,value) VALUES (".$this->char['user_id'].",'VIT',2,".(time()+$dlit).",".$this->fact['dvit'].")");
							} 
							if ($this->fact['ddex']>0)
							{
								myquery("UPDATE game_users SET DEX=DEX+".$this->fact['ddex']." WHERE user_id=".$this->char['user_id']."");
								myquery("INSERT INTO game_obelisk_users (user_id,harka,type,time_end,value) VALUES (".$this->char['user_id'].",'DEX',2,".(time()+$dlit).",".$this->fact['ddex'].")");
							} 
							if ($this->fact['cc_p']>0)
							{
								myquery("UPDATE game_users SET CC=CC+".$this->fact['cc_p']." WHERE user_id=".$this->char['user_id']."");
								myquery("INSERT INTO game_obelisk_users (user_id,harka,type,time_end,value) VALUES (".$this->char['user_id'].",'CC',2,".(time()+$dlit).",".$this->fact['cc_p'].")");
							} 
							if ($this->fact['dlucky']>0)
							{
								myquery("UPDATE game_users SET LUCKY=LUCKY+".$this->fact['dlucky']." WHERE user_id=".$this->char['user_id']."");
								myquery("INSERT INTO game_obelisk_users (user_id,harka,type,time_end,value) VALUES (".$this->char['user_id'].",'LUCKY',2,".(time()+$dlit).",".$this->fact['dlucky'].")");
							} 
							$this->message = $this->message.'<b><font color=red>�� '.echo_sex('�����','������').' ����� "'.$this->fact['name'].'"</font></b><br>'; 
						}
					}
					elseif ($dlit==0)
					{
						if ($this->fact['hp_p']>0 OR $this->fact['mp_p']>0 OR $this->fact['stm_p']>0 OR $this->fact['pr_p']>0)
						{
							$new_HP = min($this->char['HP_MAX'],$this->char['HP']+$this->fact['hp_p']);
							$new_MP = min($this->char['MP_MAX'],$this->char['MP']+$this->fact['mp_p']);
							$new_STM = min($this->char['STM_MAX'],$this->char['STM']+$this->fact['stm_p']);
							$new_PR = min($this->char['PR_MAX'],$this->char['PR']+$this->fact['stm_p']);
							myquery("UPDATE game_users SET HP=".$new_HP.",MP=".$new_MP.",STM=".$new_STM.",PR=".$new_PR." WHERE user_id=".$this->char['user_id']."");
							$char['HP']=$new_HP;
							$char['MP']=$new_MP;
							$char['STM']=$new_STM;
							$char['PR']=$new_PR;
							$this->message = $this->message.'<b><font color=red>�� '.echo_sex('�����','������').' ����� "'.$this->fact['name'].'"</font></b><br>'; 
						}
					}					
				}
			}
		}
		//��� ����� ������������ �������� � ������� ����������� � ���� ������ ������ :-)
		else
		{
			if ($this->item['user_id']==$this->char['user_id'])
			{
				if ($this->item['priznak']==0)
				{
					if ($this->fact['id']==shamp_item_id)
					{
						myquery("INSERT INTO game_log (FROMm,message,date,color) VALUES ('".$this->char['user_id']."','".iconv("Windows-1251","UTF-8//IGNORE","<img src=mag/shamp.gif border=0>&nbsp;[b]".echo_sex('������','�������')." ������� �����������[/b]")."','".time()."','yellow')");
					}
					elseif ($this->fact['id']==hlop_item_id)
					{
						myquery("INSERT INTO game_log (FROMm,message,date,color) VALUES ('".$this->char['user_id']."','".iconv("Windows-1251","UTF-8//IGNORE","<img src=mag/hlop.gif border=0>&nbsp;[b]������ ".echo_sex('���������','����������')." ���������[/b]")."','".time()."','#47F8EF')");
					}
					elseif ($this->fact['id']==beer_td_item_id)
					{
						myquery("INSERT INTO game_log (FROMm,message,date,color) VALUES ('".$this->char['user_id']."','".iconv("Windows-1251","UTF-8//IGNORE","<img src=mag/p_temn_beer_dwarf.jpg border=0>&nbsp;[b]  ".echo_sex('�����','������')." ������ ������� ������������ ����[/b]")."','".time()."','#47F8EF')");
					}
					elseif ($this->fact['id']==beer_t_item_id)
					{
						myquery("INSERT INTO game_log (FROMm,message,date,color) VALUES ('".$this->char['user_id']."','".iconv("Windows-1251","UTF-8//IGNORE","<img src=mag/p_temn_beer.jpg border=0>&nbsp;[b]  ".echo_sex('�����','������')." ������ ������� ����[/b]")."','".time()."','#47F8EF')");
					}
					elseif ($this->fact['id']==beer_s_item_id)
					{
						myquery("INSERT INTO game_log (FROMm,message,date,color) VALUES ('".$this->char['user_id']."','".iconv("Windows-1251","UTF-8//IGNORE","<img src=mag/p_svet_beer.jpg border=0>&nbsp;[b]  ".echo_sex('�����','������')." ������ �������� ����[/b]")."','".time()."','#47F8EF')");
					}
					elseif ($this->fact['id']==ell_item_id)
					{
						myquery("INSERT INTO game_log (FROMm,message,date,color) VALUES ('".$this->char['user_id']."','".iconv("Windows-1251","UTF-8//IGNORE","<img src=mag/p_ell.jpg border=0>&nbsp;[b]  ".echo_sex('�����','������')." ���[/b]")."','".time()."','#47F8EF')");
					}
					elseif ($this->fact['id']==berez_item_id)
					{
						myquery("INSERT INTO game_log (FROMm,message,date,color) VALUES ('".$this->char['user_id']."','".iconv("Windows-1251","UTF-8//IGNORE","<img src=mag/p_berez_sok.jpg border=0>&nbsp;[b]  ".echo_sex('�����','������')." ������ ������ ���������� ����[/b]")."','".time()."','#47F8EF')");
					}
					elseif ($this->fact['name']=="�������� ��������")
					{
						$bonus=100;
						$r=mt_rand(1,2);
						$item_name='������� �����';
						$Item = new Item();
						list($elik_id)=mysql_fetch_array(myquery("SELECT * FROM game_items_factsheet WHERE name like '".$item_name."'"));
						$ar = $Item->add_user($elik_id,$this->char['user_id'],0,0,0,$r);
						save_exp($this->char['user_id'],$bonus,14);
						$this->message = $this->message.'<b><font color=red>�� ������� ������� "'.$this->fact['name'].'".<br>��� ���� �������� �� '.$bonus.' ������.<br>�� �������� ������� '.$item_name.' ('.$r.' ��.)</font></b><br>'; 
					}
					elseif ($this->fact['name']=="������ ��������")
					{
						$bonus=300;
						$r=mt_rand(1,3);						
						$item_name='������� �����';
						$Item = new Item();
						list($elik_id)=mysql_fetch_array(myquery("SELECT * FROM game_items_factsheet WHERE name like '".$item_name."'"));
						$ar = $Item->add_user($elik_id,$this->char['user_id'],0,0,0,$r);
						save_exp($this->char['user_id'],$bonus,14);
						$this->message = $this->message.'<b><font color=red>�� ������� ������� "'.$this->fact['name'].'".<br>��� ���� �������� �� '.$bonus.' ������.<br>�� �������� ������� '.$item_name.' ('.$r.' ��.)</font></b><br>'; 
					}
					elseif ($this->fact['name']=="���������� ��������")
					{
						$bonus=1000;
						$r=mt_rand(1,4);						
						$item_name='������� �����';
						$Item = new Item();
						list($elik_id)=mysql_fetch_array(myquery("SELECT * FROM game_items_factsheet WHERE name like '".$item_name."'"));
						$ar = $Item->add_user($elik_id,$this->char['user_id'],0,0,0,$r);
						save_exp($this->char['user_id'],$bonus,14);
						$this->message = $this->message.'<b><font color=red>�� ������� ������� "'.$this->fact['name'].'".<br>��� ���� �������� �� '.$bonus.' ������.<br>�� �������� ������� '.$item_name.' ('.$r.' ��.)</font></b><br>'; 
					}
					elseif ($this->fact['name']=="������� ��������")
					{
						$bonus=2000;
						$r=mt_rand(1,5);						
						$item_name='������� �����';
						$Item = new Item();
						list($elik_id)=mysql_fetch_array(myquery("SELECT * FROM game_items_factsheet WHERE name like '".$item_name."'"));
						$ar = $Item->add_user($elik_id,$this->char['user_id'],0,0,0,$r);
						save_exp($this->char['user_id'],$bonus,14);
					}
					elseif ($this->fact['name']=="���������� ��������")
					{
						$bonus=5000;
						$r=mt_rand(2,5);						
						$item_name='������� �����';
						$Item = new Item();
						list($elik_id)=mysql_fetch_array(myquery("SELECT * FROM game_items_factsheet WHERE name like '".$item_name."'"));
						$ar = $Item->add_user($elik_id,$this->char['user_id'],0,0,0,$r);
						save_exp($this->char['user_id'],$bonus,14);
						$this->message = $this->message.'<b><font color=red>�� ������� ������� "'.$this->fact['name'].'".<br>��� ���� �������� �� "'.$bonus.'" ������.<br>�� �������� ������� "'.$item_name.'" ("'.$r.'" ��.)</font></b><br>'; 
					}
				}    
			}
		}
		$this->admindelete(0, 1, 4);
	}
	
	public function del_market($komu)
	{
		$this->move_item_to_user(0,$komu);    
	}
	
	public function sell_market($town_id,$price,$FROM_post=0,$post_to=0,$post_var=0,$kol_item=1, $damage=1)
	{
		$ar = array(0,$this->item['id']);
		
		if ($this->item['ref_id']==0 AND $this->item['priznak']==0 AND $this->item['user_id']==$this->char['user_id'] AND $this->item['used']==0 AND $this->fact['type']<=97 AND $this->item['item_for_quest']==0)
		{
			$kol = min($kol_item, $this->max_count());
			if (/*$this->fact['type']==19 OR $this->fact['type']==21*/ $this->counted_item())
			{
				if ($this->item['count_item'] < $kol)
				{
					$kol = $this->item['count_item'];
				}
			}
			else
			{
				$kol = 1;
			}

			$tax = round($price*$this->tax_market,2);
			if ($this->char['GP']<$tax AND $FROM_post==0)
			{
				echo '� ���� ������������ ����� ��� ������ ������';
			}    
			elseif ($this->char['GP']<$FROM_post AND $FROM_post!=0)
			{
				echo '� ���� ������������ ����� ��� ������ �������� �������';
			}    
			elseif ($price<1 AND $FROM_post==0)
			{
				echo '����������� ���� - 1 ������';
			}
			elseif ($price>10000 AND $FROM_post==0)
			{
				echo '������������ ���� - 10 000 �����';
			} 
			elseif ($this->item['personal']>0)
			{
				echo '������ �������� ��������� ���������';
			}
			elseif ($kol_item <= 0)
			{
				echo '�������� ���������� ��� �������';
			}
			else
			{
				//�������� �������� �������
				if ($damage==1) 
				{
					$res=$this->damage_item(1,25,6);
					if ($res==-1)
					{
						echo '<center><b>������� ��� ������ ��� ������� ��������</b></center><br>';			
						return 0;
					}
					elseif ($res==0)
					{
						echo '<center><b>������� ������� ������������� ��� ��������</b></center><br>';	
					}
				}
				$this->item['town']=$town_id;
				$this->item['item_cost']=(double)$price;
				if ($FROM_post==0)
				{
					$item_id = $this->move_item_to_market($town_id,$tax,0,0,1,$kol);
					echo'<b><font color=#FFFF00 size=3>���� ���������� �� �������. �� '.echo_sex('��������','���������').' �� ������ ��������� ����� '.$tax.' '.pluralForm($tax,'������','������','�����').'</font></b>';
				}
				else
				{
					$item_id = $this->move_item_to_market($town_id,$FROM_post,$post_to,$post_var,3,$kol);
					echo'<b><font color=#FFFF00 size=3>������� ������� � ��������. �� '.echo_sex('��������','���������').' �� �������� '.$FROM_post.' '.pluralForm($FROM_post,'������','������','�����').'</font></b>';
				}
				$ar[0]=$price;
				$ar[1]=$item_id;
			}
		}
		return $ar;
	}

	public function confirm_market($with_desc = 0)
	{
		if ($this->item['user_id']==$this->char['user_id'] AND $this->item['ref_id']==0 AND $this->item['used']==0 AND $this->fact['type']<=97 and $this->item['priznak']==0 AND $this->item['personal']==0)
		{   
			echo('<form action="" method="POST">');
			echo('<table border="0" cellpadding="1"><tr><td></td></tr></table><table border="1" cellpadding="0" style="border-collapse: collapse" width="98%" bordercolor="777777" bgcolor="223344" align=center><tr><td>');
			echo('<table cellpadding="0" cellspacing="4" border="0"><tr><td valign="left"><div align="center">');
			ImageItem($this->fact['img'],0,$this->item['kleymo']);
			echo'<br><font color="#ffff00">' . $this->fact['name'] . '</font></div></td><td valign="top"><div align="left"><img src="http://'.img_domain.'/nav/x.gif" width="0" height="0" hspace="40" border="0"><br>';
			if ($this->fact['type']!=99) echo'���: '.type_str($this->fact['type']).'<br>';
			if ($this->fact['indx']<>0)
				if ($this->fact['type'] == 1 or $this->fact['type'] == 3 or $this->fact['type'] == 19 or $this->fact['type'] == 21) echo '����: ' . $this->fact['indx'] . '&nbsp;&plusmn;&nbsp;' . $this->fact['deviation'] . '<br>';
			if ($this->fact['type'] == 4) echo '������: ' . $this->fact['indx'] . '<br>';
			if ($this->fact['type'] == 3) echo '���-�� �������: '.$this->item['count_item'].'<br>';
            
			if (!$this->counted_item() AND $this->fact['type'] != 95 AND $this->fact['type'] != 20)
			{
				$use=$this->item['item_uselife'];
				echo '���������: '.$use.'%';
			}
            
			if (!$this->counted_item() AND $this->fact['breakdown']==1)
			{
				echo '<br />�������������: '.$this->item['item_uselife_max'].'/'.$this->fact['item_uselife_max'];
			}
			
			echo '<br />';

			if ($this->fact['weight']>0) echo'���: ' . $this->fact['weight'] . '<br><br>';

			if (!$this->counted_item() AND $this->fact['type']!=20)
			{
				echo '������� ��������:<br>';
				if ($this->fact['dstr']<>'0')  echo '���� ��: '.$this->fact['dstr'].'<br>';
				if ($this->fact['dntl']<>'0')  echo '��������� ��: '.$this->fact['dntl'].'<br>';
				if ($this->fact['dpie']<>'0')  echo '�������� ��: '.$this->fact['dpie'].'<br>';
				if ($this->fact['dvit']<>'0')  echo '������ ��: '.$this->fact['dvit'].'<br>';
				if ($this->fact['ddex']<>'0')  echo '������������ ��: '.$this->fact['ddex'].'<br>';
				if ($this->fact['dspd']<>'0')  echo '�������� ��: '.$this->fact['dspd'].'<br>';

				if ($this->fact['hp_p']<>'0')  echo '����� ��: '.$this->fact['hp_p'].'<br>';				
				if ($this->fact['mp_p']<>'0')  echo '���� ��: '.$this->fact['mp_p'].'<br>';
				if ($this->fact['stm_p']<>'0')  echo '������� ��: '.$this->fact['stm_p'].'<br>';
				if ($this->fact['pr_p']<>'0')  echo '����� ��: '.$this->fact['pr_p'].'<br>';
				if ($this->fact['cc_p']<>'0')  echo '������� ����� ��: '.$this->fact['cc_p'].'<br>';
			}
			if ($this->counted_item())
			{
				echo'<br>�� ���� ����� ����� �������� �� <b>1</b> �� <b>'.($this->max_count()).'</b> ���������.<br/>';
				echo'<br>���-�� ���������:<input name="colitems" type="text" size="4" value="1"> (� ������� - '.$this->item['count_item'].' ��.)<br><br>';			
			}
			echo'<br>����:<input name="cena" type="text" size="6" value="1.00"> �������<br><br>';
			echo '<font color="#ffff00"><b><u>�� ������ ��������� ����� �� ����� �� '.echo_sex('������','������').' ������ ��������� 8% �� ���� ��������!</u></b></font><br><br>';
			if ($with_desc)
				echo '��������:<br><textarea name="opis" cols="15" rows="3"></textarea><br>';

			echo '<input name="" type="submit" value=���������>';
			echo'</div></td></tr></table>';
			echo'</td></tr><input name="see" type="hidden" value=""></table></form>';
		}
	}
	
	public function buy_market($id=0,$FROM_post=0)
	{
		$ar = array(0, '', '');
		if ($id>0)
		{
			$this->init_item($id);
		}
		if ($this->fact['kol_per_user']>0 AND $FROM_post==0)
		{
			$kol_inv = @mysql_result(@myquery("SELECT SUM(`count_item`) FROM game_items WHERE user_id=".$this->char['user_id']." AND item_id=".$this->fact['id']." AND priznak=0"),0,0);
			if ($kol_inv + $this->item['count_item'] > $this->fact['kol_per_user'])
			{
				echo '������ ������ ����� '.$this->fact['kol_per_user'].' ���������.';
				return $ar;
			}
		}
		if ($this->fact['clan_id']>0 AND $this->fact['clan_id']!=$this->char['clan_id'])
		{
			echo '���� ������� �� ��� ������ �����!';
			return $ar;
		}
		if ($this->item['priznak']==1 OR ($this->item['priznak']==3 AND $FROM_post>0))
		{
			if ($this->char['GP']<$this->item['item_cost'])
			{
				echo '� ���� �� ������ �����';
			}
			else
			{
				$prov=mysql_result(myquery("SELECT count(*) FROM game_wm WHERE user_id=".$this->char['user_id']." AND type=1"),0,0);
				$item_weight = $this->fact['weight'];
				if ($this->counted_item())
				{
					$item_weight = $item_weight * $this->item['count_item'];
				}
				if (($this->char['CW']+$item_weight)>$this->char['CC'] AND $prov==0)
				{
					echo '������������ ���������� ����� � ���������';
				}
				else
				{
					$this->move_item_to_user($this->item['item_cost'],$this->char['user_id']);
					$ar[0] = $this->item['item_cost'];
					if ($FROM_post>0)
					{
						$ar[0] = 1;
					}
					$ar[1] = $this->fact['name'];
					$ar[2] = $this->getOpis();    
				}
			}
		}
		return $ar;
	}
	
	public function take_post()
	{
		$ar = array(0,'','');
		if ($this->item['priznak']==3)
		{
			if ($this->char['user_id']!=$this->item['post_to'])
			{
				echo '�� ������� ����� ������ �������. �� ��!';
			}
			else
			{
				$prov=mysql_result(myquery("SELECT count(*) FROM game_wm WHERE user_id=".$this->char['user_id']." AND type=1"),0,0);
				if (($this->char['CW']+$this->fact['weight'])>$this->char['CC'] AND $prov==0)
				{
					echo '������������ ���������� ����� � ���������';
				}
				else
				{
					$this->move_item_to_user(0,$this->char['user_id']);
					$ar[0] = 1;
					$ar[1] = $this->fact['name'];
					$ar[2] = $this->getOpis();    
				}
			}
		}
		return $ar;
	}
	
	public function ImageUnidentItem()
	{
		switch ($this->fact['type'])
		{
			case 1:{echo '<img src="http://'.img_domain.'/item/unident/sword3.gif">';};break;
			case 2:{echo '<img src="http://'.img_domain.'/item/unident/ring3.gif">';};break;
			case 3:{echo '<img src="http://'.img_domain.'/item/unident/art3.gif">';};break;
			case 4:{echo '<img src="http://'.img_domain.'/item/unident/shield3.gif">';};break;
			case 5:{echo '<img src="http://'.img_domain.'/item/unident/armour3.gif">';};break;
			case 6:{echo '<img src="http://'.img_domain.'/item/unident/hemlet3.gif">';};break;
			case 7:{echo '<img src="http://'.img_domain.'/item/unident/magic3.gif">';};break;
			case 8:{echo '<img src="http://'.img_domain.'/item/unident/belt3.gif">';};break;
		}
	}

	public function up($id=0,$used,$check_whole=1)
	{
		global $user_time;
		global $_SESSION;
		$_SESSION['error_inv']='';
		if ($id>0)
		{
			$this->init_item($id);
		}
		if ($used==0)
		{
			switch ($this->fact['type'])
			{
				case 1: $used=1; break;
				case 2: 
				{
					$used=2; 
					if (isset($_GET['slot']) AND $_GET['slot']==2 AND $this->char['clevel']>=3)
					{
						$used = 19;
					}
					if (isset($_GET['slot']) AND $_GET['slot']==3 AND $this->char['clevel']>=7)
					{
						$used = 20;
					}
				}
				break;
				case 3: $used=3; break;
				case 4: $used=4; break;
				case 5: $used=5; break;
				case 6: $used=6; break;
				case 7: $used=7; break;
				case 8: $used=8; break;
				case 9: $used=9; break;
				case 10: $used=10; break;
				case 11: $used=11; break;
				case 12:
				{                     
					$used=12; 
					if (isset($_GET['slot']) AND $_GET['slot']==2)
					{
						$used = 13;
					}
					if (isset($_GET['slot']) AND $_GET['slot']==3)
					{
						$used = 14;
					}
				}
				break;
				case 13:
				{                     
					$used=12; 
					if (isset($_GET['slot']) AND $_GET['slot']==2)
					{
						$used = 13;
					}
					if (isset($_GET['slot']) AND $_GET['slot']==3)
					{
						$used = 14;
					}
				}
				break;
				case 14: $used=17; break;
				case 15: $used=18; break;
				case 16: 
				{
					$used=15; 
					if (isset($_GET['slot']) AND $_GET['slot']==2)
					{
						$used = 16;
					}
				}
				break;
				case 18: $used=4; break;
				case 18: $used=4; break;
				case 23: $used=22; break;
				case 24: $used=21; break;
			}
		}
		if ($check_whole==0)
		{
			$this->item_up($used);
		}
		elseif ($this->fact['can_up']==1 AND $this->item['priznak']==0 AND $this->item['user_id']==$this->char['user_id'] AND $this->item['used']==0 AND $used>0 AND (!$this->counted_item() OR $this->fact['type']==12 OR $this->fact['type']==13))
		{
			if (getFunc($this->item['user_id'])==2 and $this->fact['type']==24 and mysql_num_rows(myquery("SELECT * FROM game_items WHERE user_id=".$this->item['user_id']." and priznak=0 and used=21 and item_id=".$this->item['item_id'].""))==0)
			{
				return;
			}
			else
			{
				if ($this->item['ref_id']!=0)
				{
					$_SESSION['error_inv']='error_ident';
					setLocation("act.php?func=inv&error_ident");
					return;
				}
				if ($this->item['item_uselife']==0 AND $this->fact['type']!=12 AND $this->fact['type']!=13)
				{
					$_SESSION['error_inv']='error_broken';
					setLocation("act.php?func=inv&error_broken&reason=delay");
					return;
				}
				$check = $this->check_up();
				if ($check!=1)
				{
					$_SESSION['error_inv']='error_stat';
					$_SESSION['error_stat']=$check;
					setLocation("act.php?func=inv&error_stat");
					return;
				}
				$check_used = $this->check_used($used);
				if ($check_used!=1)
				{
					$_SESSION['error_inv']='error_broken';
					setLocation("act.php?func=inv&error_broken&reason=delay");
					return;
				}
				$check = myquery("SELECT id FROM game_items WHERE user_id=".$this->char['user_id']." AND priznak=0 AND used=$used");
				if (mysql_num_rows($check))
				{
					list($used_item) = mysql_fetch_array($check);
					$ItemUsed = new Item($used_item);
					$kol=$ItemUsed->item_down();
					if ($kol==1 and $used_item==$this->item['item_id']) $this->item['count_item']=$this->item['count_item']+1;
				}
				$this->item_up($used);
			}
		}       
	}
	
	public function all_down($id) //����� ��� �������� � ������
	{
		$items_check=myquery("SELECT id FROM game_items WHERE user_id='".$id."' and used>0 and priznak=0");
		if (mysql_num_rows($items_check)>0)
		{
			while (list($it)=mysql_fetch_array($items_check))
			{
				$this->init_item($it);
				$this->item_down();
			}
			return 1;
		}
		else
		{
			return 0;
		}
	}
	
	public function down($id=0)
	{
		if ($id>0)
		{
			$this->init_item($id);
		}
		if ($this->item['priznak']==0 AND $this->item['user_id']==$this->char['user_id'] AND $this->item['used']!=0 and (getFunc($this->item['user_id'])!=2 or $this->fact['type']!=24 or $this->item['item_uselife']<=0))
		{
			$this->item_down();
		}
	}
	
	public function check_up($id=0)
	{
		$ret_str = '';
		if ($id>0)
		{
			$this->init_item($id);
		}

		if ($this->item['item_uselife']<=0 && $this->fact['type'] != 13)
		{
			$ret_str.='<br />������� ������.'; 
		}

		if ($this->fact['can_up']==0 OR ($this->counted_item() AND $this->fact['type']!=12 AND $this->fact['type']!=13))
		{
			$ret_str.='<br />������� ������ �����';
		}
		if ($this->fact['oclevel']!=0)
		{
			if ($this->char['clevel']<$this->fact['oclevel'])
			{
				$ret_str.='<br />���� ������� ������: '.$this->fact['oclevel'];
			}
		}
		if ($this->fact['race']!=0)
		{
			if ($this->char['race']!=$this->fact['race'])
			{
				$ret_str.='<br />���� ������� �� ��� ����� ����';
			}
		}
		if ($this->char['clevel'] >= 15)
		{
			if ($this->fact['ostr']!=0)
			{
				if ($this->char['STR']<$this->fact['ostr'])
				{
					$ret_str.='<br />���� ���� ������: '.$this->fact['ostr'];
				}
			}
			if ($this->fact['ontl']!=0)
			{
				if ($this->char['NTL']<$this->fact['ontl'])
				{
					$ret_str.='<br />���� ��������� ������: '.$this->fact['ontl'];
				}
			}
			if ($this->fact['opie']!=0)
			{
				if ($this->char['PIE']<$this->fact['opie'])
				{
					$ret_str.='<br />���� �������� ������: '.$this->fact['opie'];
				}
			}
			if ($this->fact['ovit']!=0)
			{
				if ($this->char['VIT']<$this->fact['ovit'])
				{
					$ret_str.='<br />���� ������ ������: '.$this->fact['ovit'];
				}
			}
			if ($this->fact['odex']!=0)
			{
				if ($this->char['DEX']<$this->fact['odex'])
				{
					$ret_str.='<br />���� ������������ ������: '.$this->fact['odex'];
				}
			}
			if ($this->fact['ospd']!=0)
			{
				if ($this->char['SPD']<$this->fact['ospd'])
				{
					$ret_str.='<br />���� �������� ������: '.$this->fact['ospd'];
				}
			}
			if ($this->fact['olucky']!=0)
			{
				if ($this->char['lucky']<$this->fact['olucky'])
				{
					$ret_str.='<br />���� ����� ������: '.$this->fact['olucky'];
				}
			}
		}
		if ($this->fact['clan_id']!=0)
		{
			if ($this->char['clan_id']!=$this->fact['clan_id'])
			{
				$ret_str.='<br />���� ������� �� ��� ������ �����';
			}
		}
		if ($this->fact['type']==1 OR $this->fact['type']==18)
		{
			if ($this->fact['type_weapon_need']>0)
			{
				$skill_id=0;
				switch ($this->fact['type_weapon'])
				{
					case 0: { $skill_id=9; } break;
					case 1: { $skill_id=21; } break;
					case 2: { $skill_id=23; } break;
					case 3: { $skill_id=1; } break;
					case 4: { $skill_id=2; } break;
					case 5: { $skill_id=3; } break;
					case 6: { $skill_id=24; } break;					
				}
				
				if ($skill_id<>0)
				{
					$check_lev=myquery("SELECT gs.name, (CASE WHEN gus.level IS NULL THEN 0 ELSE gus.level END) as level FROM game_skills gs LEFT JOIN game_users_skills gus ON (gs.id=gus.skill_id AND gus.user_id=".$this->char['user_id'].") WHERE gs.id=".$skill_id."");
					if (mysql_num_rows($check_lev)>0)
					{					
						list($name,$level)=mysql_fetch_array($check_lev);											
						if ($level<$this->fact['type_weapon_need'])
						{
							$ret_str.='<br />���� ������� ������������� '.$name.' ������: '.$this->fact['type_weapon_need'];        
						}
					}
				}
			}
			if ($this->fact['in_two_hands']==1)
			{
				$used_shield = mysql_result(myquery("SELECT COUNT(*) FROM game_items WHERE user_id=".$this->char['user_id']." AND used=4 AND priznak=0"),0,0);
				if ($used_shield>0)
				{
					$ret_str.='<br />������ ����� ��������� ������. �������� ������ ����.';
				}
			}
		}
		if ($this->fact['type']==4)
		{
			$used_weapon = myquery("SELECT game_items_factsheet.in_two_hands FROM game_items,game_items_factsheet WHERE game_items.user_id=".$this->char['user_id']." AND game_items.used=1 AND game_items.priznak=0 AND game_items.item_id=game_items_factsheet.id AND game_items_factsheet.in_two_hands=1");
			if (mysql_num_rows($used_weapon)>0)
			{
				$ret_str.='<br />������ ����� ���. ����� ��������� ������.';
			}
		}
		if ($ret_str=='')
		{
			return 1;
		}
		else
		{
			return $ret_str;
		}
	}

	public function item_up($used)
	{
		global $user_time;
		$add_str = $add_pie = $add_ntl = $add_vit = $add_spd = $add_dex = $add_lucky = $add_cc = $add_hp = $add_mp = $add_stm = $add_pr = 0;
		if ($this->fact['type'] != 23)
		{
			//�������� ��������� ���������
			$sel_compl = myquery("SELECT complect_id FROM game_items_complect WHERE item_id=".$this->fact['id']."");
			while(list($complect_id) = mysql_fetch_array($sel_compl))
			{
				$est_complect = 0;
				$kol_item_in_complect = 0;
				$kol_item_complect_used = 0;
				$sel_check = myquery("SELECT item_id FROM game_items_complect WHERE complect_id=$complect_id");
				while (list($item_id) = mysql_fetch_array($sel_check))
				{
					$kol_item_in_complect++;
					if ($item_id != $this->fact['id'])
					{
						$check_used = myquery("SELECT id FROM game_items WHERE priznak=0 AND used>0 AND user_id=".$this->char['user_id']." AND item_id=$item_id");
						if (!mysql_num_rows($check_used))
						{
							$est_complect = 2;
							break;
						}
						else
							$kol_item_complect_used++;
					}
					else
					{
						$check_used = myquery("SELECT id FROM game_items WHERE priznak=0 AND used>0 AND user_id=".$this->char['user_id']." AND item_id=$item_id");
						if (mysql_num_rows($check_used))
								$kol_item_complect_used++;
					}
				}
				if (($kol_item_in_complect-$kol_item_complect_used)==1 AND $est_complect!=2)
					$est_complect = 1;

				$is_on_user = mysql_fetch_array(myquery("SELECT COUNT(*) FROM game_items WHERE item_id=$complect_id AND user_id=".$this->char['user_id'].";"));

				if ($est_complect == 1 && !$is_on_user[0])
				{
					myquery("INSERT INTO `game_items` (`user_id`, `item_id`, `ref_id`, `item_uselife`, `item_cost`, `map_name`, `map_xpos`, `map_ypos`, `item_for_quest`, `town`, `sell_time`, `priznak`, `post_to`, `post_var`, `used`, `item_uselife_max`, `for_town`, `shop_FROM`, `kleymo`, `kleymo_nomer`, `kleymo_id`, `count_item`) 
					VALUES (".$this->char['user_id'].", '$complect_id', '0', '100', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '22', '100', '0', '0', '0', '0', '0', '1');");
					
					$compl = mysql_fetch_array(myquery("SELECT * FROM game_items_factsheet WHERE id=$complect_id"));
					$add_str += $compl['dstr'];
					$add_pie += $compl['dpie'];
					$add_ntl += $compl['dntl'];
					$add_vit += $compl['dvit'];
					$add_dex += $compl['ddex'];
					$add_spd += $compl['dspd'];
					$add_lucky += $compl['dlucky'];
					$add_cc += $compl['cc_p'];
					$add_hp += $compl['hp_p'];
					$add_mp += $compl['mp_p'];
					$add_stm += $compl['stm_p'];
					$add_pr += $compl['pr_p'];
				}
			}
		}
		else
		{
			// ����� �������� ��� ����?
			$is_on_user = mysql_fetch_array(myquery("SELECT COUNT(*) FROM game_items WHERE item_id=".$this->fact['id']." AND user_id=".$this->char['user_id'].";"));
			if ($is_on_user[0])
				return;
			//���������� � ����� ��������� ����������?
			$complect_id = $this->fact['id'];
			$est_complect = 0;
			$kol_item_in_complect = 0;
			$kol_item_complect_used = 0;
			$sel_check = myquery("SELECT item_id FROM game_items_complect WHERE complect_id=$complect_id");
			while (list($item_id) = mysql_fetch_array($sel_check))
			{
				$kol_item_in_complect++;
				if ($item_id != $this->fact['id'])
				{
					$check_used = myquery("SELECT id FROM game_items WHERE priznak=0 AND used>0 AND user_id=".$this->char['user_id']." AND item_id=$item_id");
					if (!mysql_num_rows($check_used))
					{
						$est_complect = 2;
						break;
					}
					else
						$kol_item_complect_used++;
				}
				else
				{
					$check_used = myquery("SELECT id FROM game_items WHERE priznak=0 AND used>0 AND user_id=".$this->char['user_id']." AND item_id=$item_id");
					if (mysql_num_rows($check_used))
							$kol_item_complect_used++;
				}
			}
			if (($kol_item_in_complect-$kol_item_complect_used)==1 AND $est_complect!=2)
				$est_complect = 1;

			if ($est_complect != 1)
				return;
		}

		if ($this->fact['personal'] == 2)
		{
			$personal = $this->char['user_id'];
		}
		else
		{
			$personal = $this->item['personal'];
}

		// �� ����� � �� ������
		if ($this->fact['type']!=12 AND $this->fact['type']!=13)
		{
			myquery("UPDATE game_users SET
			STR=STR+".$this->fact['dstr']."+".$add_str.", 
			PIE=PIE+".$this->fact['dpie']."+".$add_pie.", 
			NTL=NTL+".$this->fact['dntl']."+".$add_ntl.", 
			VIT=VIT+".$this->fact['dvit']."+".$add_vit.", 
			DEX=DEX+".$this->fact['ddex']."+".$add_dex.", 
			SPD=SPD+".$this->fact['dspd']."+".$add_spd.", 
			STR_MAX=STR_MAX+".$this->fact['dstr']."+".$add_str.", 
			PIE_MAX=PIE_MAX+".$this->fact['dpie']."+".$add_pie.", 
			NTL_MAX=NTL_MAX+".$this->fact['dntl']."+".$add_ntl.", 
			VIT_MAX=VIT_MAX+".$this->fact['dvit']."+".$add_vit.", 
			DEX_MAX=DEX_MAX+".$this->fact['ddex']."+".$add_dex.", 
			SPD_MAX=SPD_MAX+".$this->fact['dspd']."+".$add_spd.",
			lucky=lucky+".$this->fact['dlucky']."+".$add_lucky.",
			lucky_max=lucky_max+".$this->fact['dlucky']."+".$add_lucky.", 
			CC=CC+".$this->fact['cc_p']."+".$add_cc.", 
			HP_MAX=HP_MAX+".$this->fact['hp_p']."+".$add_hp.",
			HP_MAXX=HP_MAXX+".$this->fact['hp_p']."+".$add_hp.", 
			MP_MAX=MP_MAX+".$this->fact['mp_p']."+".$add_mp.", 
			STM_MAX=STM_MAX+".$this->fact['stm_p']."+".$add_stm.",
			PR_MAX=PR_MAX+".$this->fact['pr_p']."+".$add_pr.",
			STM=LEAST(STM,STM_MAX), MP=LEAST(MP,MP_MAX), HP=LEAST(HP,HP_MAX), PR=LEAST(PR,PR_MAX)
			WHERE user_id=".$this->item['user_id']."");

			myquery("
			UPDATE game_users_archive SET 
			STR=STR+".$this->fact['dstr']."+".$add_str.", 
			PIE=PIE+".$this->fact['dpie']."+".$add_pie.", 
			NTL=NTL+".$this->fact['dntl']."+".$add_ntl.", 
			VIT=VIT+".$this->fact['dvit']."+".$add_vit.", 
			DEX=DEX+".$this->fact['ddex']."+".$add_dex.", 
			SPD=SPD+".$this->fact['dspd']."+".$add_spd.", 
			STR_MAX=STR_MAX+".$this->fact['dstr']."+".$add_str.", 
			PIE_MAX=PIE_MAX+".$this->fact['dpie']."+".$add_pie.", 
			NTL_MAX=NTL_MAX+".$this->fact['dntl']."+".$add_ntl.", 
			VIT_MAX=VIT_MAX+".$this->fact['dvit']."+".$add_vit.", 
			DEX_MAX=DEX_MAX+".$this->fact['ddex']."+".$add_dex.", 
			SPD_MAX=SPD_MAX+".$this->fact['dspd']."+".$add_spd.",
			lucky=lucky+".$this->fact['dlucky']."+".$add_lucky.",
			lucky_max=lucky_max+".$this->fact['dlucky']."+".$add_lucky.", 
			CC=CC+".$this->fact['cc_p']."+".$add_cc.", 
			HP_MAX=HP_MAX+".$this->fact['hp_p']."+".$add_hp.",
			HP_MAXX=HP_MAXX+".$this->fact['hp_p']."+".$add_hp.", 
			MP_MAX=MP_MAX+".$this->fact['mp_p']."+".$add_mp.", 
			STM_MAX=STM_MAX+".$this->fact['stm_p']."+".$add_stm.",
			PR_MAX=PR_MAX+".$this->fact['pr_p']."+".$add_pr.",
			STM=LEAST(STM,STM_MAX), MP=LEAST(MP,MP_MAX), HP=LEAST(HP,HP_MAX), PR=LEAST(PR,PR_MAX)
			WHERE user_id=".$this->item['user_id']."");
			
			//��������, � �� ���������� �� ��� ������������ ��������
			$check1=myquery("SELECT id FROM game_users_complects WHERE user_id='".$this->item['user_id']."' AND status=0");
			if (mysql_num_rows($check1)>0)
			{
				list($compl_id)=mysql_fetch_array($check1);
				$check2=myquery("SELECT item_id FROM game_users_complects_prepare WHERE complect_id='".$compl_id."' AND item_id='".$this->item['id']."'");
				if (mysql_num_rows($check2)==0)
				{
					myquery("INSERT INTO game_users_complects_prepare (complect_id, item_id) VALUES ('".$compl_id."', '".$this->item['id']."') ");
				}
			}
		}
		//set_delay_id($this->item['user_id'], $user_time + $this->fact['weight']); //������� �������� ��������
		//��� ��������� � ������� ���� ���������. ���� ���������� �� > 1 ����� ���� ������ ���������� �������
		if (($this->fact['type']==12 OR $this->fact['type']==13) AND $this->item['count_item']>1)
		{
			myquery("UPDATE game_items SET count_item=count_item-1 WHERE id=".$this->item['id']."");
			$up = myquery("INSERT INTO game_items SET
                            user_id = ".$this->item['user_id'].",
                            ref_id='0',
                            item_id=".$this->fact['id'].",
                            item_uselife=".$this->item['item_uselife'].",
                            item_uselife_max=".$this->item['item_uselife_max'].",
                            item_cost=".$this->item['item_cost'].",
                            priznak=0,
                            used=$used,
                            item_for_quest=0,
                            count_item=1,
							dead_time=".$this->item['dead_time'].",
							personal=".$personal."");
			$insert_id = mysql_insert_id();
		}
		else
		{
			myquery("UPDATE game_items SET used=".$used.", personal=".$personal." WHERE id=".$this->item['id']."");
		}
	}
	
	private function item_down()
	{
		global $user_time;
		//�������� ������ ���� �� ���������
		$add_str = $add_pie = $add_ntl = $add_vit = $add_spd = $add_dex = $add_lucky = $add_cc = $add_hp = $add_mp = $add_stm = $add_pr = 0;
		if ($this->fact['type'] != 23)
		{
			$sel_compl = myquery("SELECT complect_id FROM game_items_complect WHERE item_id=".$this->fact['id']."");
			while(list($complect_id) = mysql_fetch_array($sel_compl))
			{
				$complect_down = 0;
				$sel_check = myquery("SELECT item_id FROM game_items_complect WHERE complect_id=$complect_id");
				$kol_in_complect = 0;
				$kol_used_complect = 0;
				while (list($item_id) = mysql_fetch_array($sel_check))
				{
					$kol_in_complect++;
					if ($this->fact['id']==$item_id)
					{
						$checkused = myquery("SELECT id FROM game_items WHERE user_id=".$this->char['user_id']." AND used>0 AND item_id=$item_id AND priznak=0 AND id<>".$this->item['id']."");
						if (mysql_num_rows($checkused))
						{
							$kol_used_complect++;
						}                    
					}
						else
					{
						$checkused = myquery("SELECT id FROM game_items WHERE user_id=".$this->char['user_id']." AND used>0 AND item_id=$item_id AND priznak=0");
						if (mysql_num_rows($checkused))
							$kol_used_complect++;
					}
				}
				$is_on_user = mysql_fetch_array(myquery("SELECT COUNT(*) FROM game_items WHERE item_id=$complect_id AND user_id=".$this->char['user_id'].";"));

				if ($kol_in_complect-$kol_used_complect==1 && $is_on_user[0])
				{
					myquery("DELETE FROM game_items WHERE item_id=$complect_id AND user_id=".$this->char['user_id']." LIMIT 1;");
					$compl = mysql_fetch_array(myquery("SELECT * FROM game_items_factsheet WHERE id=$complect_id"));
					$add_str += $compl['dstr'];
					$add_pie += $compl['dpie'];
					$add_ntl += $compl['dntl'];
					$add_vit += $compl['dvit'];
					$add_dex += $compl['ddex'];
					$add_spd += $compl['dspd'];
					$add_lucky += $compl['dlucky'];
					$add_cc += $compl['cc_p'];
					$add_hp += $compl['hp_p'];
					$add_mp += $compl['mp_p'];
					$add_stm += $compl['stm_p'];
					$add_pr += $compl['pr_p'];
				}
			}
		}
		else
			myquery("DELETE FROM game_items WHERE item_id=".$this->fact['id']." AND user_id=".$this->char['user_id']." LIMIT 1;");

		//set_delay_id($this->item['user_id'],$user_time + $this->fact['weight']); //������� �������� ��������

		if ($this->fact['type']!=12 AND $this->fact['type']!=13)
		{
			myquery("
			UPDATE game_users SET 
			STR=STR-".$this->fact['dstr']."-'$add_str', 
			PIE=PIE-".$this->fact['dpie']."-'$add_pie', 
			NTL=NTL-".$this->fact['dntl']."-'$add_ntl', 
			VIT=VIT-".$this->fact['dvit']."-'$add_vit', 
			DEX=DEX-".$this->fact['ddex']."-'$add_dex', 
			SPD=SPD-".$this->fact['dspd']."-'$add_spd', 
			STR_MAX=STR_MAX-".$this->fact['dstr']."-'$add_str', 
			PIE_MAX=PIE_MAX-".$this->fact['dpie']."-'$add_pie', 
			NTL_MAX=NTL_MAX-".$this->fact['dntl']."-'$add_ntl', 
			VIT_MAX=VIT_MAX-".$this->fact['dvit']."-'$add_vit', 
			DEX_MAX=DEX_MAX-".$this->fact['ddex']."-'$add_dex', 
			SPD_MAX=SPD_MAX-".$this->fact['dspd']."-'$add_spd', 
			lucky=lucky-".$this->fact['dlucky']."-'$add_lucky',
			lucky_max=lucky_max-".$this->fact['dlucky']."-'$add_lucky',
			CC=CC-".$this->fact['cc_p']."-'$add_cc', 
			HP_MAX=HP_MAX-".$this->fact['hp_p']."-'$add_hp', 
			HP_MAXX=HP_MAXX-".$this->fact['hp_p']."-'$add_hp', 
			MP_MAX=MP_MAX-".$this->fact['mp_p']."-'$add_mp', 
			STM_MAX=STM_MAX-".$this->fact['stm_p']."-'$add_stm',
			PR_MAX=PR_MAX-".$this->fact['pr_p']."-'.$add_pr.',
			STM=LEAST(STM,STM_MAX), MP=LEAST(MP,MP_MAX), HP=LEAST(HP,HP_MAX), PR=LEAST(PR,PR_MAX)
			WHERE user_id=".$this->item['user_id']."");			

			myquery("
			UPDATE game_users_archive SET 
			STR=STR-".$this->fact['dstr']."-'$add_str', 
			PIE=PIE-".$this->fact['dpie']."-'$add_pie', 
			NTL=NTL-".$this->fact['dntl']."-'$add_ntl', 
			VIT=VIT-".$this->fact['dvit']."-'$add_vit', 
			DEX=DEX-".$this->fact['ddex']."-'$add_dex', 
			SPD=SPD-".$this->fact['dspd']."-'$add_spd', 
			STR_MAX=STR_MAX-".$this->fact['dstr']."-'$add_str', 
			PIE_MAX=PIE_MAX-".$this->fact['dpie']."-'$add_pie', 
			NTL_MAX=NTL_MAX-".$this->fact['dntl']."-'$add_ntl', 
			VIT_MAX=VIT_MAX-".$this->fact['dvit']."-'$add_vit', 
			DEX_MAX=DEX_MAX-".$this->fact['ddex']."-'$add_dex', 
			SPD_MAX=SPD_MAX-".$this->fact['dspd']."-'$add_spd', 
			lucky=lucky-".$this->fact['dlucky']."-'$add_lucky',
			lucky_max=lucky_max-".$this->fact['dlucky']."-'$add_lucky',
			CC=CC-".$this->fact['cc_p']."-'$add_cc', 
			HP_MAX=HP_MAX-".$this->fact['hp_p']."-'$add_hp', 
			HP_MAXX=HP_MAXX-".$this->fact['hp_p']."-'$add_hp', 
			MP_MAX=MP_MAX-".$this->fact['mp_p']."-'$add_mp', 
			STM_MAX=STM_MAX-".$this->fact['stm_p']."-'$add_stm',
			PR_MAX=PR_MAX-".$this->fact['pr_p']."-'.$add_pr.',
			STM=LEAST(STM,STM_MAX), MP=LEAST(MP,MP_MAX), HP=LEAST(HP,HP_MAX), PR=LEAST(PR,PR_MAX)
			WHERE user_id=".$this->item['user_id']."");
			
			myquery("
			UPDATE combat_users SET 
			STR=STR-".$this->fact['dstr']."-'$add_str', 
			PIE=PIE-".$this->fact['dpie']."-'$add_pie', 
			NTL=NTL-".$this->fact['dntl']."-'$add_ntl', 
			VIT=VIT-".$this->fact['dvit']."-'$add_vit', 
			DEX=DEX-".$this->fact['ddex']."-'$add_dex', 
			SPD=SPD-".$this->fact['dspd']."-'$add_spd', 
			lucky=lucky-".$this->fact['dlucky']."-'$add_lucky',
			HP_MAX=HP_MAX-".$this->fact['hp_p'].", 
			MP_MAX=MP_MAX-".$this->fact['mp_p'].", 
			STM_MAX=STM_MAX-".$this->fact['stm_p'].",			
			STM=LEAST(STM,STM_MAX), MP=LEAST(MP,MP_MAX), HP=LEAST(HP,HP_MAX)
			WHERE user_id=".$this->item['user_id']."");
		}
		//��� ������� � ��������� �������� ���� ������ ���������� �������
		if ($this->fact['type']==12 OR $this->fact['type']==13)
		{
			$sel_having = myquery("SELECT id, dead_time FROM game_items WHERE user_id=".$this->item['user_id']." AND used=0 AND priznak=0 AND item_id=".$this->item['item_id']."");
			if ($sel_having!=false AND mysql_num_rows($sel_having))
			{
				list($item_having_id, $dead_time) = mysql_fetch_array($sel_having);
				myquery("UPDATE game_items SET count_item=count_item+".$this->item['count_item'].", dead_time=GREATEST(".$this->item['dead_time'].", ".$dead_time.") WHERE id=$item_having_id");
				myquery("DELETE FROM game_items WHERE id=".$this->item['id'].""); 
				return 1;
			}
			else
			{
				myquery("UPDATE game_items SET used=0 WHERE id=".$this->item['id']."");  
			}
		}
		else
		{
			myquery("UPDATE game_items SET used=0 WHERE id=".$this->item['id']."");
		}
		return 0;
	}
	
	private function check_used($used)
	{
		switch ($this->fact['type'])
		{
			case 1:    //������
				if ($used!=1 AND $used!=4) return 0;
			break;
			case 2:    //������
				if ($used!=2 AND $used!=19 AND $used!=20) return 0;
			break;
			case 3:    //��������
				if ($used!=3 AND $used!=17) return 0;
			break;
			case 4:    //���
				if ($used!=4) return 0;
			break;
			case 5:    //������
				if ($used!=5) return 0;
			break;
			case 6:    //����
				if ($used!=6) return 0;
			break;
			case 7:    //�����
				if ($used!=7) return 0;
			break;
			case 8:    //����
				if ($used!=8) return 0;
			break;
			case 9:    //��������
				if ($used!=9) return 0;
			break;
			case 10:   //��������
				if ($used!=10) return 0;
			break;
			case 11:   //�����
				if ($used!=11) return 0;
			break;
			case 12:   //������
				if ($used!=12 AND $used!=13 AND $used!=14) return 0;
			break;
			case 13:   //�������� 
				if ($used!=12 AND $used!=13 AND $used!=14) return 0;
			break;
			case 14:   //������ 
				if ($used!=17) return 0;
			break;
			case 15:   //������ 
				if ($used!=18) return 0;
			break;
			case 16:   //��������� 
				if ($used!=15 AND $used!=16) return 0;
			break;
			case 17:   //��������� 
				if ($used!=4) return 0;
			break;
			case 18:   //���� 
				if ($used!=4) return 0;
			break;
			case 19:   //�����.������ 
				return 0;
			break;
			case 20:   //����� ��������� 
				return 0;
			break;
			case 21:   //������ 
				return 0;
			break;
			case 24:   //����������
				if ($used!=21) return 0;
			break;
			default:
				return 0;
			break;
		}
		return 1;
	}
	
	public function move_item_to_user($cost = 0, $komu, $kol_item = 1)
	{
		if ($this->fact['personal'] == 1)
		{
			$personal = $this->char['user_id'];
		}
		else
		{
			$personal = $this->item['personal'];
		}
		
		if (!$this->counted_item())
		{
			myquery("UPDATE game_items SET town=0,map_xpos=0,map_ypos=0,map_name=0,post_to=0,post_var=0,sell_time=0,used=0,item_cost=0,priznak=0,user_id=".$komu.", personal=".$personal." WHERE id=".$this->item['id']."");
		}
		else
		{
			$check = myquery("SELECT id FROM game_items WHERE user_id=".$komu." AND item_id=".$this->fact['id']." AND priznak=0 AND used=0 and ref_id=0");
			if (mysql_num_rows($check)>0)
			{
				if ($this->getItem('priznak')==4 and $this->getItem('type')==13)
				{
					myquery("UPDATE game_items SET count_item=count_item+$kol_item WHERE user_id=".$komu." AND item_id=".$this->fact['id']." AND priznak=0 AND used=0");
				}
				else
				{
					myquery("UPDATE game_items SET count_item=count_item+".$this->item['count_item']." WHERE user_id=".$komu." AND item_id=".$this->fact['id']." AND priznak=0 AND used=0");
				}
				if ($this->getItem('priznak')!=4 or $this->item['count_item']==$kol_item)
				{
					myquery("DELETE FROM game_items WHERE id=".$this->item['id']."");
				}
				else
				{
					myquery("UPDATE game_items SET count_item=count_item-$kol_item WHERE id=".$this->item['id']."");
				}
			}
			else
			{
				if ($this->getItem('priznak')!=4 or $this->item['count_item']==$kol_item)
				{
					myquery("UPDATE game_items SET town=0,map_xpos=0,map_ypos=0,map_name=0,post_to=0,post_var=0,sell_time=0,used=0,item_cost=0,priznak=0,user_id=".$komu.", personal=".$personal." WHERE id=".$this->item['id']."");
				}
				else
				{
					myquery("INSERT INTO game_items (user_id, item_id, count_item, dead_time, personal) VALUES ($komu, ".$this->item['item_id'].", $kol_item,  ".$this->item['dead_time'].", ".$personal.")");
					myquery("UPDATE game_items SET count_item=count_item-$kol_item WHERE id=".$this->item['id']."");
				}
			}
		}
		$weight_item = $this->fact['weight'];
		if ($this->counted_item())
		{
			if ($this->getItem('priznak')!=4) $kol_item=$this->item['count_item'];
			$weight_item = $weight_item*$kol_item;
		}
		if ($cost>0)
		{
			myquery("UPDATE game_users SET GP=GP-$cost,CW=CW-".($this->item['item_cost']*money_weight)."+".$weight_item." WHERE user_id=".$komu."");
			if (mysql_affected_rows() == 0)
			{
				myquery("UPDATE game_users_archive SET GP=GP-$cost,CW=CW-".($this->item['item_cost']*money_weight)."+".$weight_item." WHERE user_id=".$komu."");
			}
			set_delay_plus_id($komu,$weight_item);
			setGP($komu,-$cost,12);
			myquery("UPDATE game_users SET GP=GP+$cost,CW=CW+".($this->item['item_cost']*money_weight)." WHERE user_id=".$this->item['user_id']."");
			if (mysql_affected_rows() == 0)
			{
				myquery("UPDATE game_users_archive SET GP=GP+$cost,CW=CW+".($this->item['item_cost']*money_weight)." WHERE user_id=".$this->item['user_id']."");
			}
			setGP($this->item['user_id'],$cost,13);
		}
		else
		{
			myquery("UPDATE game_users SET CW=CW+".$weight_item." WHERE user_id=".$komu."");
			if (mysql_affected_rows() == 0)
			{
				myquery("UPDATE game_users_archive SET CW=CW+".$weight_item." WHERE user_id=".$komu."");
			}
			set_delay_plus_id($komu,$weight_item);
		}
	}
	
	public function move_item_to_market($town,$tax = 0, $post_to = 0,$post_var = 0,$priznak = 1, $kol_item = 1)
	{
		$item_id = $this->item['id'];
		if ($this->counted_item() AND $this->item['count_item']>$kol_item)
		{
			$item_id=0;
			if ($priznak==4 and $this->fact['type']==13)
			{
				$check=myquery("SELECT * FROM game_items WHERE user_id=".$this->item['user_id']." and item_id=".$this->item['item_id']." and town=".$town." and priznak=4");
				if (mysql_num_rows($check)>0) list($item_id)=mysql_fetch_array($check);
			}
			if ($item_id==0)
			{
				myquery("INSERT INTO game_items SET town=".$town.",map_xpos=0,map_ypos=0,map_name=0,post_to=$post_to,post_var=$post_var,sell_time=".time().",used=0,item_cost=".$this->item['item_cost'].",priznak=$priznak,user_id=".$this->char['user_id'].",count_item=$kol_item,item_id=".$this->fact['id'].", ref_id = 0, dead_time=".$this->item['dead_time']."");
				$item_id = mysql_insert_id();
			}
			else
			{
				myquery("UPDATE game_items SET count_item=count_item+$kol_item WHERE user_id=".$this->item['user_id']." and item_id=".$this->item['item_id']." and priznak=4");	
			}
			myquery("UPDATE game_items SET count_item=count_item-$kol_item WHERE id=".$this->item['id']."");
		}
		else
		{
			if ($priznak==4 and $this->fact['type']==13 and mysql_num_rows(myquery("SELECT * FROM game_items WHERE user_id=".$this->item['user_id']." and item_id=".$this->item['item_id']." and priznak=4"))>0)
			{
				myquery("DELETE FROM game_items WHERE id=".$this->item['id']."");
				myquery("UPDATE game_items SET count_item=count_item+$kol_item WHERE user_id=".$this->item['user_id']." and item_id=".$this->item['item_id']." and town=".$town." and priznak=4");	
			}
			else
			{
				myquery("UPDATE game_items SET town=".$town.",map_xpos=0,map_ypos=0,map_name=0,post_to=$post_to,post_var=$post_var,sell_time=".time().",used=0,item_cost=".$this->item['item_cost'].",priznak=$priznak,user_id=".$this->char['user_id']." WHERE id=".$this->item['id']."");
			}
		}
		$weight_item = $this->fact['weight'];
		if ($this->counted_item())
		{
			$weight_item = $weight_item*$kol_item;
		}
		myquery("UPDATE game_users SET GP=GP-$tax,CW=CW-".$weight_item."-".($tax*money_weight)." WHERE user_id=".$this->char['user_id']."");
		set_delay_plus_id($this->char['user_id'],$weight_item);
		setGP($this->char['user_id'],-$tax,14);
		return $item_id;
	}
	
	private function delete_item($all=0, $count=1, $reason=1)
	{
		switch ($this->item['priznak'])
		{
			case 0:
			{
				if ($this->item['used']>0 AND $this->item['used']!=12 AND $this->item['used']!=13 AND $this->item['used']!=14)
				{
					$this->item_down();
				}    
				if ($this->counted_item() AND $this->item['count_item']>$count AND $all==0)
				{
					myquery("UPDATE game_items SET count_item=count_item-'".$count."' WHERE id=".$this->item['id']."");
				}
				else
				{
					myquery("DELETE FROM game_items WHERE id=".$this->item['id']."");
					$check=myquery("SELECT * FROM quest_constructor WHERE item_id=".$this->item['id']."");
					if (mysql_num_rows($check)>0)
					{
						myquery("DELETE FROM quest_constructor WHERE item_id=".$this->item['id']."");
						myquery("DELETE FROM game_items_factsheet WHERE id=".$this->item['item_id']."");
					}
				}
				if($this->fact['type']!=95)
				{
					$weight = $this->fact['weight'];
					if ($all!=0 AND $this->counted_item() AND $this->item['count_item']>1) $weight = $weight*$this->item['count_item'];
					myquery("UPDATE game_users SET CW=CW-".($this->fact['weight']*$count)." WHERE user_id=".$this->item['user_id']."");
					myquery("UPDATE game_users_archive SET CW=CW-".($this->fact['weight']*$count)." WHERE user_id=".$this->item['user_id']."");
				}
				else
				{
					myquery("UPDATE game_users SET CW=CW-".$this->item['item_uselife']." WHERE user_id=".$this->item['user_id']."");
					myquery("UPDATE game_users_archive SET CW=CW-".$this->item['item_uselife']." WHERE user_id=".$this->item['user_id']."");
				}
			}
			break;
			default:
				myquery("DELETE FROM game_items WHERE id=".$this->item['id']."");
				$check=myquery("SELECT * FROM quest_constructor WHERE item_id=".$this->item['id']."");
				if (mysql_num_rows($check)>0)
				{
					myquery("DELETE FROM quest_constructor WHERE item_id=".$this->item['id']."");
					myquery("DELETE FROM game_items_factsheet WHERE id=".$this->item['item_id']."");
				}
			break;
		}
		if ($reason > 1)
		{
			/*  �������� reason:
				2 - ������� ��� �������
				3 - ������� � �������
				4 - ������������� ��������
				5 - ������� ��� ����������� �� �����
				6 - ������� ��� ������� �� �����
			*/
			myquery("INSERT INTO game_items_deleted (user_id, user_name, item_id, reason) VALUES (".$this->char['user_id'].", '".$this->char['name']."', ".$this->item['item_id'].", ".$reason.") ");
		}
	}
	
	public function admindelete($all=0,$count=1,$reason=1)
	{
		$this->delete_item($all,$count,$reason);
	}
	
	public function add_user($id,$user_id_add,$check_weight=0,$item_for_quest=0,$check_count=0,$kol_item=1,$max_uselife = 0)
	{
		$ar = array(0);
		if ($id>0)
		{
			$this->init_fact($id);
			if ($check_weight!=0)
			{
				$sel = myquery("SELECT CW,CC FROM game_users WHERE user_id=$user_id_add");
				if (!mysql_num_rows($sel)) $sel = myquery("SELECT CW,CC FROM game_users_archive WHERE user_id=$user_id_add");
				list($user_CW,$user_CC) = mysql_fetch_array($sel); 
				$prov = mysql_result(myquery("SELECT COUNT(*) FROM game_wm WHERE user_id=$user_id_add AND type=1"),0,0);
				if ($prov==0 AND ($user_CC-$user_CW)<$this->fact['weight'])
				{
					echo '������������ ���������� ����� � ��������� ��� ���������� ��������: '.$this->fact['name'].'!';
					return $ar;
				}
			}

			if ($check_count!=0)
			{
				$kol_inv = mysql_result(myquery("SELECT SUM(`count_item`) FROM game_items WHERE user_id=".$this->char['user_id']." AND item_id=".$this->fact['id']." AND priznak=0"),0,0);
				if (($this->fact['kol_per_user'] != 0) && ($kol_inv >= $this->fact['kol_per_user']))
				{
					echo '������ ������ � ����� ����� '.$this->fact['kol_per_user'].' ���������: '.$this->fact['name'].'!';
					return $ar;
				}
			}
			
			$up = myquery("UPDATE game_users SET CW=CW+'".($this->fact['weight']*$kol_item)."' WHERE user_id=$user_id_add");
			if (mysql_affected_rows() == 0) 
			{
				$up = myquery("UPDATE game_users_archive SET CW=CW+'".($this->fact['weight']*$kol_item)."' WHERE user_id=$user_id_add");
			}
			$need_add = 1;
			$insert_id = 0;
			
			if ($this->fact['personal'] == 1)
			{
				$personal = $this->char['user_id'];
			}
			else
			{
				$personal = 0;
			}

			if ($this->counted_item())
			{
				$chek = mysql_result(myquery("SELECT COUNT(*) FROM game_items WHERE user_id=$user_id_add AND item_id=".$this->fact['id']." AND priznak=0 AND used=0"),0,0);
				if ($chek==0) 
				{
					$up = myquery("INSERT INTO game_items SET
								user_id = $user_id_add,
								ref_id='0',
								item_id=".$this->fact['id'].",
								item_uselife=".$this->fact['item_uselife'].",
								item_uselife_max=".$this->fact['item_uselife_max'].",
								item_cost=".$this->fact['item_cost'].",
								priznak=0,
								item_for_quest=$item_for_quest,
								count_item='".$kol_item."',
								dead_time=(CASE WHEN ".$this->fact['life_time']."=0 THEN 0 ELSE ".($this->fact['life_time']+time())." END),
								used=0,
								personal=".$personal."");
					$insert_id = mysql_insert_id();
				}
				else
				{
					myquery("UPDATE game_items SET count_item=count_item+'".$kol_item."', dead_time=(CASE WHEN ".$this->fact['life_time']."=0 THEN 0 ELSE ".($this->fact['life_time']+time())." END) WHERE user_id='$user_id_add' AND item_id='".$this->fact['id']."' AND priznak=0 AND used=0");
				}
			}
			else
			{
				if ($max_uselife == 0) 
				{
					$max_uselife = $this->fact['item_uselife_max'];
				}
				$count_item = 1;
				if ($this->fact['type']==3)
				{
					$count_item = $this->fact['item_uselife'];
					$this->fact['item_uselife']=100;
				}
				if ($this->fact['life_time'] > 0) $dead_time = $this->fact['life_time'] + time();
				else $dead_time = 0;
				while ($kol_item>0)
				{
					
					$up = myquery("INSERT INTO game_items SET
									user_id = $user_id_add,
									ref_id='0',
									item_id=".$this->fact['id'].",
									item_uselife=".$this->fact['item_uselife'].",
									item_uselife_max=".$max_uselife.",
									item_cost=".$this->fact['item_cost'].",
									priznak=0,
									item_for_quest=$item_for_quest,
									count_item=$count_item,
									dead_time=$dead_time,
									used=0,
									personal=".$personal."");
					$insert_id = mysql_insert_id();
					$kol_item--;
				}
			}
			$ar[0]=1;
			$ar[1]=$insert_id;
			
			//���������� ������� ����� ��������� �� ����� �� ����
			if ($this->fact['life_time'] > 0 and $this->fact['set_id']>0)
			{
				list($max_dead_time) = mysql_fetch_array(myquery("SELECT max(dead_time) FROM game_items gi JOIN game_items_factsheet gif ON gi.item_id = gif.id
				                                                   WHERE gi.user_id = ".$user_id_add." AND gif.set_id = '".$this->fact['set_id']."' "));
				myquery("UPDATE game_items gi JOIN game_items_factsheet gif ON gi.item_id = gif.id
				            SET gi.dead_time = ".$max_dead_time."
						  WHERE gi.user_id = ".$user_id_add."
 						    AND gif.set_id = '".$this->fact['set_id']."'
							AND gi.id <> ".$insert_id." ");
			}
		}
		return $ar;
	}
}
?>