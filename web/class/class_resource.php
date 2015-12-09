<?php
/*
Список методов:
1) Добавление/забирание ресурса игроку - add_user();
2) Добавление/забирание ресурса на карту - add_map();
3) Добавление/забирание ресурса на рынке - add_map();
4) Поднять ресурс с земли - take();
5) Выбросить ресурс на землю - drop();
6) Выставить ресурс на рынок - sell_market();
7) Купить ресурс на рынке - buy_market();
8) Продать ресурс торговцу - sell();

*/
class Res
{
	public $res;
	public $res_nonuser;
	private $char;
	public $message;
	public $message_type = 0;
	private $tax_market = 0.08;//налог на рынке
			
	public function __construct($res=0, $id=0, $user_id=0)
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
		$this->char = $char;
		$this->message = '';		
		$this->init_res($res, $id);
	}
	
	public function __destruct()
	{
	}
	
	private function init_res($res=0,$id=0)
	{
		if ($res==0 and $id==0) return;
		if ($res==0) 
		{
			$this->res = mysql_fetch_assoc(myquery("SELECT * FROM craft_resource WHERE id='".$id."'"));
		}
		else
		{
			$this->res['id'] = $res['id'];
			$this->res['name'] = $res['name'];
			$this->res['img1'] = $res['img1'];			
			$this->res['img2'] = $res['img2'];			
			$this->res['img3'] = $res['img3'];			
			$this->res['weight'] = $res['weight'];
			$this->res['incost'] = $res['incost'];
			$this->res['outcost'] = $res['outcost'];
			$this->res['spets'] = $res['spets'];
			$this->res['decrease_rab_time'] = $res['decrease_rab_time'];
			$this->res['need_count_for_level'] = $res['need_count_for_level'];
			$this->res['increase_chance'] = $res['increase_chance'];			
			$this->res['life_time'] = $res['life_time'];			
		}
		$this->res['weight'] = (double)$this->res['weight'];
	}
	
	private function init_res_nonuser($id = 0, $place_id=0, $res_id=0, $map_name=0, $map_xpos=0, $map_ypos=0)
	{
		if ($place_id > 0)
		{
			$res = myquery("SELECT * FROM craft_resource_market WHERE id='".$place_id."' LIMIT 1");
		}
		else
		{
			$res = myquery("SELECT * FROM craft_resource_market WHERE town=0 AND map_name='".$map_name."' AND map_xpos='".$map_xpos."' AND map_ypos='".$map_ypos."' AND res_id = '".$res_id."' LIMIT 1");
		}
		if (mysql_num_rows($res)>0)
		{
			$this->res_nonuser = mysql_fetch_assoc($res);
			if ($id==0) 
			{
				$this->init_res(0,$this->res_nonuser['res_id']);
			}
			return 1;			
		}		
		else return 0;
	}
	
	private function init_res_user ($place_id=0, $user_id)
	{
		if ($place_id > 0) 
		{
			$res = myquery("SELECT * FROM craft_resource_user WHERE id = '".$place_id."'");
		}
		else
		{
			$res = myquery("SELECT * FROM craft_resource_user WHERE res_id = '".$this->res['id']."' and user_id = '".$user_id."' ");
		}
		if (mysql_num_rows($res)>0)
		{
			$this->res_user = mysql_fetch_assoc($res);		
			if ($place_id > 0) $this->init_res(0,$this->res_user['res_id']);
		}		
		else exit;
	}
	
	public function add_user($id = 0, $user_id, $kol=1, $dead_time = 0, $archive_users = 0, $gp=0, $gp_reason=0)
	{
		$check = 0;
		if ($gp >= 0 or $this->char['GP']>-$gp)
		{			
			if ($id > 0) $this->init_res(0, $id);
			$weight = $kol * $this->res['weight'];
			if ($dead_time == 0 and $this->res['life_time'] > 0) $dead_time = $this->res['life_time']+time();
			//Добавляем ресурс игроку
			if ($kol > 0)
			{
				$prov = mysqlresult(myquery("SELECT count(*) FROM game_wm WHERE user_id='".$user_id."' AND type=1"),0,0);
				if ($this->char['CW']+$weight<=$this->char['CC'] or $prov>2)
				{
					myquery("INSERT INTO craft_resource_user (user_id,res_id,col,dead_time) VALUES ('".$user_id."','".$this->res['id']."','".$kol."','".$dead_time."') 
							 ON DUPLICATE KEY UPDATE col=col+'".$kol."', dead_time=GREATEST('".$dead_time."', dead_time)");		
					$check = 1;
				}
				else 
				{	
					$check = 0;
					$this->message = '<b><font color=#FF0000 size=3>У Вас недостаточно места в инвентаре!</font></b>';
					$this->message_type = 1;
				}
			}
			//Удаляем ресурс у игрока
			elseif ($kol<0)
			{			
				if (!isset($this->res_user['col'])) $this->init_res_user(0, $user_id);
				if (!isset($this->res_user['col']) or -$kol>$this->res_user['col'])
				{					
					$check = 0;
					$this->message = '<br><b><font color=#FF0000 size=3>У Вас нет ресурса <b>"'.$this->res['name'].'"</b> в нужном количестве!</font></b>';
				}
				else
				{
					if (-$kol==$this->res_user['col'])
					{
						myquery("DELETE FROM craft_resource_user WHERE user_id='".$user_id."' AND res_id='".$this->res['id']."'");
						$check = 1;
					}
					else
					{
						myquery("UPDATE craft_resource_user SET col=col+".$kol." WHERE user_id='".$user_id."' AND res_id='".$this->res['id']."' ");
						$check = 1;
					}		
				}
			}
		}
		else
		{
			$this->message = '<b><font color=#FF0000 size=3>У Вас недостаточно денег для свершения операции!</font></b>';
			$this->message_type = 2;
			$check = 0;
		}
		if ($check == 1)
		{			
			myquery("UPDATE game_users SET CW=CW +'".$weight."', GP=GP+'".$gp."' WHERE user_id='".$user_id."' LIMIT 1");	
			$this->char['CW'] = $this->char['CW'] + $weight;
			if ($gp_reason>0)
			{
				$this->char['GP'] = $this->char['GP'] + $gp;
				setGP($user_id,$gp,$gp_reason);
			}
			if ($archive_users == 1) 
			{
				myquery("UPDATE game_users_archive SET CW=CW +'".$weight."' WHERE user_id='".$user_id."' LIMIT 1");
			}
		}
		return $check;
	}
	
	//Перемещение ресурса на землю или на рынок
	public function add_map ($id = 0, $place_id = 0, $kol = 1, $dead_time = 0, $map_name = 0, $map_xpos = 0, $map_ypos = 0)
	{
		if ($id > 0) $this->init_res(0, $id);			
		if ($map_name == 0) 
		{
			$map_name = $this->char['map_name'];
			$map_xpos = $this->char['map_xpos'];
			$map_ypos = $this->char['map_ypos'];
		}			
		if (!isset($this->res_nonuser['id'])) $this->init_res_nonuser(1, $place_id, $this->res['id'], $map_name, $map_xpos, $map_ypos);
		
		if ($kol > 0) //Кладём ресурс на землю
		{			
			if (isset($this->res_nonuser['id'])) 
			{
				myquery("UPDATE craft_resource_market SET col=col+'".$kol."', user_id = '".$this->char['user_id']."', dead_time=GREATEST(dead_time, '".$dead_time."'), sell_time = '".time()."' WHERE id=".$this->res_nonuser['id']."");	
			}
			else
			{
				myquery("INSERT INTO craft_resource_market (user_id,col,map_name,map_xpos,map_ypos,res_id,sell_time,dead_time) VALUES 
				       ('".$this->char['user_id']."','".$kol."',".$map_name.",".$map_xpos.",".$map_ypos.",'".$this->res['id']."','".time()."','".$dead_time."') ");
			}
		}
		elseif ($kol < 0) //Поднимаем ресурс с земли
		{
			if ($this->res_nonuser['col']==-$kol)
			{
				myquery("DELETE FROM craft_resource_market WHERE id='".$this->res_nonuser['id']."' ");
			}
			else
			{
				myquery("UPDATE craft_resource_market SET col=col+".$kol.", user_id = '".$this->char['user_id']."', sell_time = '".time()."' WHERE id='".$this->res_nonuser['id']."' ");
			}
		}	
	}
	
	public function add_market ($id = 0, $place_id = 0, $kol = -1, $dead_time = 0, $town =0, $price = 0, $opis = 0)
	{
		if ($id > 0) $this->init_res(0, $id);				
		if ($kol > 0) //Выставляем ресурс на рынок
		{
			myquery("INSERT INTO craft_resource_market (user_id,col,town,price,opis,res_id,sell_time,dead_time) VALUES 
				   ('".$this->char['user_id']."','".$kol."',".$town.",".$price.",'".$opis."','".$this->res['id']."','".time()."','".$dead_time."') ");
		}
		elseif ($kol < 0) //Покупаем ресурс с рынка
		{
			if ($place_id > 0 and !isset($this->res_nonuser['id'])) $this->init_res_nonuser(0, $place_id);
			myquery("DELETE FROM craft_resource_market WHERE id='".$this->res_nonuser['id']."' ");
		}	
	}
	
	public function add_house ($id = 0, $kol = 1, $town =0, $dead_time = 0)
	{
		if ($id > 0) $this->init_res(0, $id);		
		if ($kol > 0) //Кладём ресурс в дом
		{
			$check_current = myquery("SELECT col FROM craft_resource_market WHERE user_id='".$this->char['user_id']."' and town='".$town."' and res_id='".$this->res['id']."' and priznak = 1");
			if (mysql_num_rows($check_current) == 0)
			{
				myquery("INSERT INTO craft_resource_market (user_id,col,town,res_id,sell_time,dead_time, priznak) VALUES 
					   ('".$this->char['user_id']."','".$kol."',".$town.",'".$this->res['id']."','".time()."','".$dead_time."', 1) ");
		    }
			else
			{
				myquery("UPDATE craft_resource_market SET col=col+'".$kol."', dead_time = GREATEST (dead_time, '".$dead_time."'), sell_time='".time()."'
					     WHERE user_id='".$this->char['user_id']."' and town='".$town."' and res_id='".$this->res['id']."' and priznak = 1");
			}
		}
		elseif ($kol < 0) //Забираем ресурс из дома
		{
			if ($this->res_nonuser['col']==-$kol)
			{
				myquery("DELETE FROM craft_resource_market WHERE id='".$this->res_nonuser['id']."' ");
			}
			else
			{
				myquery("UPDATE craft_resource_market SET col=col+".$kol.", sell_time = '".time()."' WHERE id='".$this->res_nonuser['id']."' ");
			}
		}	
	}
	
	public function take($id = 0, $place_id, $kol=1)
	{
		$check1 = $this->init_res_nonuser($id, $place_id);		
		if ($check1 == 1)
		{
			$kol = min($kol, $this->res_nonuser['col']);
			//Даём ресурс игроку
			$check2 = $this->add_user(0, $this->char['user_id'], $kol, $this->res_nonuser['dead_time']);	
			if ($check2 == 1)
			{
				//Забираем ресурс с карты
				$this->add_map(0, 0, -$kol);
			}		
			elseif  ($this->message_type == 1)
			{
				setLocation("act.php?errror=full_inv");
				{if (function_exists("save_debug")) save_debug(); exit;}		
			}
		}
	}  
	
	public function drop($kol=1)
	{		
		$this->init_res_user(0, $this->char['user_id']);
		$kol = min($kol, $this->res_user['col']);
		//Забираем ресурс у игрока
		$check=$this->add_user(0, $this->char['user_id'], -$kol);
		//Кладём ресурс на карту
		if ($check==1)
		{
			$this->add_map(0, 0, $kol, $this->res_user['dead_time']);			
			$this->message = 'Выброшено '.$kol.' ед. из '.$this->res_user['col'].' ед '.$this->res['name'].'';
		}
	}
	
	public function sell_market ($place_id=0,$town, $kol, $price, $opis, $max_weight=1000)	
	{		
		$this->init_res_user($place_id, $this->char['user_id']);
		if ($this->res['weight'] != 0) 
		{			
			$select = myquery("SELECT (CASE WHEN SUM(`col`*`weight`) is null THEN 0 ELSE SUM(`col`*`weight`) END) AS `sum` FROM `craft_resource_market` JOIN `craft_resource` ON `craft_resource_market`.`res_id` = `craft_resource`.`id` WHERE  `town` = '".$town."' ");
			$sum_weight = mysql_fetch_array($select);
			$sum_weight = round($sum_weight['sum'], 2);
	
			$max_kol = floor($max_weight-$sum_weight['sum']/$this->res['weight']);	
			$kol = min($kol, $max_kol, $this->res_user['col']);
		}
		if ($kol>0)
		{			
			$arenda = round($price*$this->tax_market, 2);
			$check=$this->add_user(0, $this->char['user_id'], -$kol, 0, 0, -$arenda, 50);			
			if ($check==1)
			{
				$this->add_market(0, 0, $kol, $this->res_user['dead_time'], $town, $price, $opis);
				$this->message = '<b><font color=#FFFF00 size=3>Ресурс выставлен на продажу ('.$kol.'шт). Ты '.echo_sex('заплатил','заплатила').' за аренду торгового места '.$arenda.' '.pluralForm($arenda,'монету','монеты','монет').'</font></b>';
			}			
			else $this->message = '<b><font color=#FF0000 size=3>У Вас недостаточно денег!</font></b>';
		}
		else
		{
			$this->message = '<b><font color=#FF0000 size=3>Рынок уже полон ресурсов!</font></b>';
		}		
	}
	
	function buy_market ($id = 0, $place_id, $dostup = 1)
	{
		$check1 = $this->init_res_nonuser($id, $place_id);		
		if ($check1 == 1)
		{
			if ($dostup == 2 and $this->res_nonuser['user_id'] <> $this->char['user_id'])
			{
				$this->message = "<b><font color=#FF0000 size=3>Операция недоступна!</font></b>";
			}
			else
			{
				$check=$this->add_user(0, $this->char['user_id'], $this->res_nonuser['col'], $this->res_nonuser['dead_time'], 0, -$this->res_nonuser['price'], 48);	
				if ($check==1)
				{
					$this->add_market(0, $place_id);
					$this->message = "<b><font color='#FFFF00'>Ресурс куплен!</font></b>";
					$userid = $this->res_nonuser['user_id'];
					$result = myquery("UPDATE game_users SET gp=gp+".$this->res_nonuser['price']." WHERE user_id=".$userid."");
					$result = myquery("UPDATE game_users_archive SET gp=gp+".$this->res_nonuser['price']." WHERE user_id=".$userid."");
					setGP($userid,$this->res_nonuser['price'],49);
					$town_select = myquery("SELECT rustown FROM game_gorod WHERE town='".$this->res_nonuser['town']."' ");
					list($rustown) = mysql_fetch_array($town_select);					
					$sell = myquery("SELECT name FROM game_users WHERE user_id='".$userid."'");
					if (!mysql_num_rows($sell)) $sell = myquery("SELECT name FROM game_users_archive WHERE user_id='".$userid."'");
					list($name) = mysql_fetch_array($sell);
					$ma = myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time,folder) VALUES ('".$this->char['user_id']."', '0', 'Рынок: Ты ".echo_sex('купил','купила')." ресурс ".$this->res['name']." у игрока ".$name."', 'Ты ".echo_sex('купил','купила')." ресурс ".$this->res['name']." выставленный на продажу на рынке в ".$rustown." у игрока ".$name.". в количестве ".$this->res_nonuser['col']."  за ".$this->res_nonuser['price']." ".pluralForm($this->res_nonuser['price'],'монету','монеты','монет').".','0','".time()."',4)");
					if ($this->char['user_id'] <> $userid)
					{
						$ma = myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time,folder) VALUES ('".$userid."', '0', 'Рынок: Твой ресурс ".$this->res['name']." куплен игроком ".$this->char['name']."', 'Твой ресурс ".$this->res['name'].", выставленный на продажу на рынке в ".$rustown.", в количестве ".$this->res_nonuser['col']." единиц куплен ".$this->char['name'].". за ".$this->res_nonuser['price']." ".pluralForm($this->res_nonuser['price'],'монету','монеты','монет').".','0','".time()."',4)");
					}		
					return 1;
				}
			}
		}
		else
		{
			$this->message = "<b><font color=#FF0000 size=3>Операция недоступна!</font></b>";
		}
		return 0;
	}
	
	public function sell($kol=1, $shop_id=0)
	{
		$this->init_res_user(0, $this->char['user_id']);
		$kol = min($kol, $this->res_user['col']);
		if ($kol>0)
		{
			$gp = $kol * $this->res['incost'];
			$check=$this->add_user(0, $this->char['user_id'], -$kol, 0, 0, $gp, 8);
			myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time, folder) VALUES ('".$this->char['user_id']."', '0', 'Продажа в магазине', 'Ты ".echo_sex('продал','продала')." ресурс <b>".$this->res['name']."</b> торговцу в количестве ".$kol." шт. за ".$gp." ".pluralForm($gp,'монету','монеты','монет')."','0','".time()."',5)");
			$this->message = "<b><font color=ff0000 face=verdana size=2>Продан ресурс: ".$this->res['name']." ".$kol." шт. за ".$gp." ".pluralForm($gp,'монету','монеты','монет')."</font></b>";		
			if ($shop_id > 0)
			{
				save_stat($this->char['user_id'],'','',11,$shop_id,$this->res['name'],'',$gp,'','','','');
			}
		}
		else
		{
			$this->message = "<b><font color=#FF0000 size=3>У Вас нет данного ресурса!</font></b>";
		}
	}
	
	public function put_house ($place_id, $kol, $town, $free_weight)	
	{			
		$this->init_res_user($place_id, $this->char['user_id']);		
		$kol = min($kol, $this->res_user['col']);
		$put_weight = 0;
		if ($this->res['weight'] != 0) 
		{		
			$put_weight = max(0, $this->res['weight'] * $kol);	
			if ($put_weight > $free_weight)
			{
				$kol = 0;
			}			
		}
		if ($kol > 0)
		{			
			$check=$this->add_user(0, $this->char['user_id'], -$kol);			
			if ($check == 1)
			{
				$this->add_house(0, $kol, $town, $this->res_user['dead_time']);
				$this->message = '<b><font color=#FFFF00 size=3>Вы положили ресурс в дом!</font></b>';
			}			
			else $this->message = '<b><font color=#FF0000 size=3>Что-то введено неверно!</font></b>';	
		}
		else
		{
			$this->message = '<b><font color=#FF0000 size=3>Что-то введено неверно!</font></b>';			
		}		
		return $put_weight;
	}
	
	function take_house ($id = 0, $place_id, $kol)
	{
		$weight = 0;
		$check1 = $this->init_res_nonuser($id, $place_id);		
		if ($check1 == 1 and $kol>0 and $kol<=$this->res_nonuser['col'])
		{			
			$check=$this->add_user(0, $this->char['user_id'], $kol, $this->res_nonuser['dead_time']);	
			if ($check==1)
			{
				$weight = $kol * $this->res['weight'];
				$this->add_house(0, -$kol);
				$this->message = '<b><font color=#FFFF00 size=3>Ресурс взят из хранилища!</font></b>';				
			}
		}
		else
		{
			$this->message = "<b><font color=#FF0000 size=3>Операция недоступна!</font></b>";
		}
		return $weight;
	}	
}
?>