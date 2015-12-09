<?php
class Combat
{
	private $char;//"владелец" скрипта   
	private $combat; //запись из combat
	private $souz;//массив записей игроков и ботов - союзников
	private $prot;//массив записей игроков и ботов - противников
	public $all;//массив записей всех игроков и ботов боя, в том числе и "владелец" скрипта 
	private $str_type_boy;//строковое название типа боя
	public $timeout; //таймаут хода боя
	private $decrease;//коэффициент уменьшения урона в формулах
	private $map; //запись game_maps где проходит бой
	private $log; //массив значений для записи лога хода боя
	private $k_exp; //коэффициент изменения опыта
	private $k_gp; //коэффициент изменения денег
	private $lockfile; //файл для блокировки
	public $add_exp_for_sklon;
	
	public function __construct($combat_id,$user_id,$state='')
	{
		if (!empty($state))
		{
			if (empty($combat_id))
			{
				$combat_id = $state['combat_id'];			
			}
		}                                      
		$this->char = mysql_fetch_array(myquery("SELECT * FROM combat_users WHERE user_id=".$user_id."")); 
		$this->log = array();
		$this->combat = mysql_fetch_array(myquery("SELECT * FROM combat WHERE combat_id=".$combat_id.""));
		switch ($this->combat['combat_type'])
		{
			case 1: $this->str_type_boy = "Обычный бой"; break;
			case 2: $this->str_type_boy = "Дуэль"; break;
			case 3: $this->str_type_boy = "Общий бой"; break;
			case 4: $this->str_type_boy = "Многоклановый бой"; break;
			case 5: $this->str_type_boy = "Все против всех"; break;
			case 6: $this->str_type_boy = "Бой склонностей"; break;
			case 7: $this->str_type_boy = "Бой рас"; break;
			case 8: $this->str_type_boy = "Турнирная дуэль"; break;
			case 9: $this->str_type_boy = "Турнирный групповой бой"; break;
			case 10: $this->str_type_boy = "Бой с тенью"; break;
			case 11: $this->str_type_boy = "Турнирный хаотичный бой"; break;
			case 12: $this->str_type_boy = "Хаотичный бой"; break;
		}
		if (defined('domain_name') and (domain_name == 'testing.rpg.su' or domain_name=='localhost')) 
		{
			$this->decrease = 1;
		}
		else
		{
			$this->decrease = 1;
		}
		
		/*if ($this->combat['map_name']==691 OR $this->combat['map_name']==692 OR $this->combat['map_name']==804)
		{
			$this->decrease = 0.7;
		}*/
		$this->timeout = $this->get_timeout();		
		$this->low_timeout = 20;		
		if (defined("add_exp_for_sklon"))
		{
			$this->add_exp_for_sklon = add_exp_for_sklon;
		}
		else
		{
			$this->add_exp_for_sklon = -1;
		}
		$this->souz = array();
		$this->prot = array();
		$this->all  = array();
        
		if ($state!='' AND in_array($state['state'],array(1,2,5,6,10)))
		{
			$this->all[$this->char['user_id']] = $this->char;
			$this->all[$this->char['user_id']]['win']=0; //увеличение соотв.поля в game-users по результатам расчета хода
			$this->all[$this->char['user_id']]['lose']=0; // ---//---
			$this->all[$this->char['user_id']]['exp']=0; // ---//--- 
			$this->all[$this->char['user_id']]['gp']=0; // ---//---
			$this->all[$this->char['user_id']]['state']=$state['state'];
            if ($this->char['clan_id']>0)
            {
			    $this->all[$this->char['user_id']]['alies']=mysql_result(myquery("SELECT alies FROM game_clans WHERE clan_id=".$this->char['clan_id'].""),0,0);
            }
            else
            {
                $this->all[$this->char['user_id']]['alies'] = 0;
            }
            
			//союзники
			$userinboy=myquery("
			select combat_users.*,combat_users_state.state from combat_users,combat_users_state 
			where combat_users.combat_id=".$combat_id." 
			and combat_users.user_id<>".$this->char['user_id']." 
			AND combat_users.join=0
			AND combat_users.side=".$this->char['side']."
			AND combat_users.user_id=combat_users_state.user_id
			ORDER BY combat_users.clan_id ASC, BINARY combat_users.name ASC");
			while ($us=mysql_fetch_array($userinboy))
			{
				if ($us['HP']>0) $this->souz[$us['user_id']]=$us;
				$this->all[$us['user_id']]=$us;
				$this->all[$us['user_id']]['win']=0; //увеличение соотв.поля в game-users по результатам расчета хода
				$this->all[$us['user_id']]['lose']=0; // ---//---
				$this->all[$us['user_id']]['exp']=0; // ---//--- 
				$this->all[$us['user_id']]['gp']=0; // ---//--- 
                $this->all[$us['user_id']]['npc_id_template'] = -1;
                if ($us['npc']==1)
                {
                    $this->all[$us['user_id']]['npc_id_template'] = mysql_result(myquery("SELECT npc_id FROM game_npc WHERE id=".$us['user_id'].""),0,0);
                }
			}
			
			//противники
			$order_by = "combat_users.clan_id ASC, BINARY combat_users.name ASC";
			if (chaos_war==1 or $this->combat['combat_type'] == 12)
			{
				$order_by = "RAND()";
			}
			$userinboy=myquery("
			select combat_users.*,combat_users_state.state from combat_users,combat_users_state 
			where combat_users.combat_id=".$combat_id." 
			AND combat_users.join=0
			AND combat_users.side<>".$this->char['side']."
			AND combat_users.user_id=combat_users_state.user_id
			ORDER BY $order_by");
			while ($us=mysql_fetch_array($userinboy))
			{
				if ($us['HP']>0) $this->prot[$us['user_id']]=$us;
				$this->all[$us['user_id']]=$us;
				$this->all[$us['user_id']]['win']=0; //увеличение соотв.поля в game-users по результатам расчета хода
				$this->all[$us['user_id']]['lose']=0; // ---//---
				$this->all[$us['user_id']]['exp']=0; // ---//--- 
				$this->all[$us['user_id']]['gp']=0; // ---//--- 
                $this->all[$us['user_id']]['npc_id_template'] = -1;
                if ($us['npc']==1)
                {
                    $check=myquery("SELECT npc_id FROM game_npc WHERE id=".$us['user_id']."");
					if (mysql_num_rows($check)>0)
					{
						$this->all[$us['user_id']]['npc_id_template'] = mysql_result($check,0,0);
					}
                }
			}
		}
		else
		{
			//класс вызван из крона, нет "владельца"
			$userinboy=myquery("
			select combat_users.* from combat_users,combat_users_state 
			where combat_users.combat_id=".$this->combat['combat_id']." 
			AND combat_users.join=0
			AND combat_users.user_id=combat_users_state.user_id
			ORDER BY combat_users.clan_id ASC, BINARY combat_users.name ASC");
			while ($us=mysql_fetch_array($userinboy))
			{
				$this->all[$us['user_id']]=$us;
				$this->all[$us['user_id']]['win']=0; //увеличение соотв.поля в game-users по результатам расчета хода
				$this->all[$us['user_id']]['lose']=0; // ---//---
				$this->all[$us['user_id']]['exp']=0; // ---//--- 
				$this->all[$us['user_id']]['gp']=0; // ---//--- 
                $this->all[$us['user_id']]['npc_id_template'] = -1;
                if ($us['npc']==1)
                {
                    $check=myquery("SELECT npc_id FROM game_npc WHERE id=".$us['user_id']."");
					if (mysql_num_rows($check)>0)
					{
						$this->all[$us['user_id']]['npc_id_template'] = mysql_result($check,0,0);
					}
                }
			}
		}
		$this->map = mysql_fetch_array(myquery("SELECT * FROM game_maps WHERE id=".$this->combat['map_name'].""));
	}	
	
	public function get_timeout ()
	{
		
        if (defined('domain_name') AND (domain_name=='localhost' or domain_name=='testing.rpg.su'))
        {
            $timeout = 5000;  
        }
		//Если это Хаотический бой
		elseif ($this->combat['combat_type'] == 12)
		{
			if ($this->combat['hod'] <= 3)
			{
				$timeout = 120;
			}
			else
			{
				$timeout = 60;
			}
		}
		else		
		{			
			$timeout = 120;
		}
		return $timeout;
	}
	
	public function clear_combat()
	{
		ClearCombat($this->combat['combat_id']);
	}
	
	public function clear_user($user_id)
	{
		ClearCombatUser($user_id);
	}
	
	public function print_header()
	{
		PrintCombatHeader();
	}
	
	public function show_log()
	{
		ShowCombatLog($this->combat['combat_id'],$this->combat['hod']-1);
	}
	
	private function print_clan($clan_id)
	{
		if ($clan_id>0)
		{
			list($clan_name) = mysql_fetch_array(myquery("SELECT nazv FROM game_clans WHERE clan_id=$clan_id"));
			echo '<img src="http://'.img_domain.'/clan/'.$clan_id.'.gif" alt="'.$clan_name.'" title="'.$clan_name.'" border="0">';
		}
	}
	
	public function print_state1() //запрос подтверждения от игрока разрешения на начало боя
	{
		$prot = reset($this->prot);
		if (!isset($_GET['ok']) AND !isset($_GET['no']))
		{
			$this->print_header();
			?>           
			Тебя вызвали на поединок!<br />
			Твой противник: <?=$prot['name'];?> (<?=$prot['race'];?>  <?=$prot['clevel'];?> уровня)
			<?
			$this->print_clan($prot['clan_id']);
			print_sklon($prot);
			?>
			&nbsp;&nbsp;<br>Вызвал тебя на <b><font color=#FF0000><?=$this->str_type_boy;?></font></b>
			
			<div align="center">До конца выбора варианта осталось: 
			<font color=ff0000><b><span id="timerr1"><? echo $this->combat['time_last_hod']+$this->timeout-time();?></span></b></font> секунд
			</div>
			<script language="JavaScript" type="text/javascript">
			function tim()
			{
				timer = document.getElementById("timerr1");
				if (timer.innerHTML<=0)
				{
					location.replace("combat.php?no");
				}
				else
				{
					timer.innerHTML=timer.innerHTML-1;
					window.setTimeout("tim()",1000);
				}
			}
			tim();
			</script>
			<br><br><?=echo_sex('Согласен','Согласна');?> ли ты на бой?<br><br><br>
			<input type="button" class="button" value="&nbsp;&nbsp;&nbsp;Да&nbsp;&nbsp;&nbsp;" OnClick=location.href="combat.php?ok">
			<input type="button" class="button" value="&nbsp;&nbsp;&nbsp;Нет&nbsp;&nbsp;&nbsp;" OnClick=location.href="combat.php?no">
			<meta http-equiv="refresh" content="15">
			<?
		}
		if (isset($_GET['ok']))
		{
			//начинаем бой
			combat_setFunc($this->char['user_id'],5,$this->combat['combat_id']);
			combat_setFunc($prot['user_id'],5,$this->combat['combat_id']);

			if ($this->combat['combat_type']==4)
			{
				myquery("INSERT INTO game_log (message,date,fromm,ob) VALUES ('".iconv("Windows-1251","UTF-8//IGNORE","<span style=\"font-weight:900;font-size:14px;color:red;font-family:Verdana,Tahoma,Arial,Helvetica,sans-serif\">ВНИМАНИЕ! <img align=\"center\" src=\"http://".img_domain."/clan/".$this->char['clan_id'].".gif\"> ".mysql_result(myquery("SELECT nazv FROM game_clans WHERE clan_id=".$this->char['clan_id'].""),0,0)." и <img align=\"center\" src=\"http://".img_domain."/clan/".$this->all[$prot['user_id']]['clan_id'].".gif\"> ".mysql_result(myquery("SELECT nazv FROM game_clans WHERE clan_id=".$this->all[$prot['user_id']]['clan_id'].""),0,0)." начинают бой: ".$this->map['name']."(".$this->combat['map_xpos']."; ".$this->combat['map_ypos'].") </span>'").",".time().",-1,1)");
			}
			setLocation("combat.php");
		}
		if (isset($_GET['no']))
		{
			combat_setFunc($this->char['user_id'],3,$this->combat['combat_id']);
			combat_setFunc($prot['user_id'],4,$this->combat['combat_id']);
			$this->clear_combat();
			setLocation("combat.php");
		}
	}
	
	public function print_state2() //ожидание подтверждения от противника
	{
		$prot = reset($this->prot);
		if (!isset($_GET['no']))
		{
			$this->print_header();
			?>
			<center>Ожидание подтверждения противника<br><br>
			<input type="button" class="button" value="Отказаться от вызова на бой" OnClick=location.href="combat.php?no">
			<br><br><div align="center">До конца ожидания осталось: 
			<font color=ff0000><b><span id="timerr1"><? echo $this->combat['time_last_hod']+$this->timeout-time();?></span></b></font> секунд</div>
			<script language="JavaScript" type="text/javascript">
			function tim()
			{
				timer = document.getElementById("timerr1");
				if (timer.innerHTML<=0)
					location.replace("combat.php?no");
				else
				{
					timer.innerHTML=timer.innerHTML-1;
					window.setTimeout("tim()",1000);
				}
			}
			tim();
			</script>
			<meta http-equiv="refresh" content="15">
			<?
		}
		else
		{
			combat_setFunc($this->char['user_id'],3,$this->combat['combat_id']);
			combat_setFunc($prot['user_id'],4,$this->combat['combat_id']);
			$this->clear_combat();
			setLocation("combat.php");
		}
	}
	
	private function get_bron($attack_kuda,$type_weapon,$attack_kogo)
	{
		$used = 0;
		switch ($attack_kuda)
		{
			case 1: //в голову
			{
				$used = 6;
			}
			break;
			case 2: //в тело
			{
				$used = 5;
			}
			break;
			case 3: //в пах
			{
				$used = 8;
			}
			break;
			case 4: //в плечо
			{
				$used = 2;
			}
			break;
			case 5: //в ноги
			{
				$used = 5;
			}
			break;
		}
		if ($used==0) return 0;

		if (!isset($this->all[$attack_kogo]['bron'][$used])) return 0;
		$def_type = $this->all[$attack_kogo]['bron'][$used]['def_type'];
		$def_index = $this->all[$attack_kogo]['bron'][$used]['def_index']; 
		
		switch ($def_type)
		{
			case 0: //одежда
			{
				switch ($type_weapon)
				{
					case 1: //Кулачное
					{
						return $def_index*1;
					}
					break;
					case 2: //Стрелковое
					{
						return $def_index*1;
					}
					break;
					case 3: //Рубящее
					{
						return $def_index*0;
					}
					break;
					case 4: //Дробящее
					{
						return $def_index*0;
					}
					break;
					case 5: //Колющее
					{
						return $def_index*0;
					}
					break;
					case 6: //Метательное
					{
						return $def_index*1;
					}
					break;
				}
			}
			break;
			case 1: //кожанная
			{
				switch ($type_weapon)
				{
					case 1: //Кулачное
					{
						return $def_index*1.25;
					}
					break;
					case 2: //Стрелковое
					{
						return $def_index*1;
					}
					break;
					case 3: //Рубящее
					{
						return $def_index*0.5;
					}
					break;
					case 4: //Дробящее
					{
						return $def_index*1.5;
					}
					break;
					case 5: //Колющее
					{
						return $def_index*1;
					}
					break;
					case 6: //Метательное
					{
						return $def_index*1;
					}
					break;
				}
			}
			break;
			case 2: //кольчужная
			{
				switch ($type_weapon)
				{
					case 1: //Кулачное
					{
						return $def_index*1.5;
					}
					break;
					case 2: //Стрелковое
					{
						return $def_index*0.5;
					}
					break;
					case 3: //Рубящее
					{
						return $def_index*1.5;
					}
					break;
					case 4: //Дробящее
					{
						return $def_index*0.5;
					}
					break;
					case 5: //Колющее
					{
						return $def_index*1;
					}
					break;
					case 6: //Метательное
					{
						return $def_index*0.5;
					}
					break;
				}
			}
			break;
			case 3: //латы
			{
				switch ($type_weapon)
				{
					case 1: //Кулачное
					{
						return $def_index*2;
					}
					break;
					case 2: //Стрелковое
					{
						return $def_index*0.5;
					}
					break;
					case 3: //Рубящее
					{
						return $def_index*1.5;
					}
					break;
					case 4: //Дробящее
					{
						return $def_index*1;
					}
					break;
					case 5: //Колющее
					{
						return $def_index*0.5;
					}
					break;
					case 6: //Метательное
					{
						return $def_index*0.5;
					}
					break;
				}
			}
			break;
		}
		return 0;
	}

	private function del_combat_user()
	{
		//удаляет признак о переводе игрока на скрипт боя, чтобы при след.обновлении экрана игрока перекидывало бы на act.php
		combat_delFunc($this->char['user_id']);
		ForceFunc($this->char['user_id'],5); 
	}
	
	private function print_user($user) //вывод данных по игроку в виде строк HTML Table
	{
		if ($user['HP_MAX']==0)
		{
			$bar_percentage = 0;
		}
		else
		{
			$bar_percentage = $user['HP'] / $user['HP_MAX'] * 100;
		}
		if ($bar_percentage >= 100)
		{
			$append_string = '<img src="http://'.img_domain.'/bar/bar_green.gif" width="100" height="7" border="0">';
		}
		elseif ($bar_percentage <= 0)
		{
			$append_string = '<img src="http://'.img_domain.'/bar/bar_empty.gif" width="100" height="7" border="0">';
		}
		else
		{
			$append_string = '<img src="http://'.img_domain.'/bar/bar_empty.gif" width="'.(100 - $bar_percentage).'" height="7" border="0"><img src="http://'.img_domain.'/bar/bar_green.gif" width="'.$bar_percentage.'" height="7" border="0">';
		}

		echo '
		<tr><td>Здоровье</td><td width=70% align=right>'.$user['HP'].' / '.$user['HP_MAX'].'</td></tr>
		<tr><td colspan="2"><div align="right"><img src="http://'.img_domain.'/bar/bar_side.gif" width="1" height="7" border="0">'.$append_string.'<img src="http://'.img_domain.'/bar/bar_side.gif" width="1" height="7" border="0"></div></td></tr>';

		if ($user['MP_MAX']==0)
		{
			$bar_percentage = 0;
		}
		else
		{
			$bar_percentage = $user['MP'] / $user['MP_MAX'] * 100;
		}
		if ($bar_percentage >= 100)
		{
			$append_string = '<img src="http://'.img_domain.'/bar/bar_orange.gif" width="100" height="7" border="0">';
		}
		elseif ($bar_percentage <= 0)
		{
			$append_string = '<img src="http://'.img_domain.'/bar/bar_empty.gif" width="100" height="7" border="0">';
		}
		else
		{
			$append_string = '<img src="http://'.img_domain.'/bar/bar_empty.gif" width="'.(100 - $bar_percentage).'" height="7" border="0"><img src="http://'.img_domain.'/bar/bar_orange.gif" width="'.$bar_percentage.'" height="7" border="0">';
		}
		echo '
		<tr><td>Мана</td><td width=70% align=right>'.$user['MP'].' / '.$user['MP_MAX'].'</td></tr>
		<tr><td colspan="2"><div align="right"><img src="http://'.img_domain.'/bar/bar_side.gif" width="1" height="7" border="0">'.$append_string.'<img src="http://'.img_domain.'/bar/bar_side.gif" width="1" height="7" border="0"></td></tr>';

		if ($user['STM_MAX']==0)
		{
			$bar_percentage = 0;
		}
		else
		{
			$bar_percentage = $user['STM'] / $user['STM_MAX'] * 100;
		}
		if ($bar_percentage >= 100)
		{
			$append_string = '<img src="http://'.img_domain.'/bar/bar_yellow.gif" width="100" height="7" border="0">';
		}
		elseif ($bar_percentage <= 0)
		{
			$append_string = '<img src="http://'.img_domain.'/bar/bar_empty.gif" width="100" height="7" border="0">';
		}
		else
		{
			$append_string = '<img src="http://'.img_domain.'/bar/bar_empty.gif" width="'.(100 - $bar_percentage).'" height="7" border="0"><img src="http://'.img_domain.'/bar/bar_yellow.gif" width="'.$bar_percentage.'" height="7" border="0">';
		}
		echo '
		<tr><td>Энергия</td><td width=70% align=right>'.$user['STM'].' / '.$user['STM_MAX'].'</td></tr>
		<tr><td colspan="2"><div align="right"><img src="http://'.img_domain.'/bar/bar_side.gif" width="1" height="7" border="0">'.$append_string.'<img src="http://'.img_domain.'/bar/bar_side.gif" width="1" height="7" border="0"></div></td></tr>';

		echo '
		<tr><td>Сила</td><td><div align="right">'.$user['STR'].'</td></tr>
		<tr><td>Интеллект</td><td><div align="right">'.$user['NTL'].'</td></tr>
		<tr><td>Ловкость</td><td><div align="right">'.$user['PIE'].'</td></tr>
		<tr><td>Защита</td><td><div align="right">'.$user['VIT'].'</td></tr>
		<tr><td>Мудрость</td><td><div align="right">'.$user['SPD'].'</td></tr>
		<tr><td>Выносливость</td><td><div align="right">'.$user['DEX'].'</td></tr>';        
	}
	
	private function print_left() //вывод левой части интерфейса - данных по самому игроку
	{
		OpenTable('title');
		echo '
		<div align="right"><font face="Verdana" size="2" color="#f3f3f3"><b>'.$this->char['name'].'</b></font></div>
		<br><img src = "http://'.img_domain.'/avatar/'.$this->char['avatar'].'"><br />
		<table cellpadding="2" cellspacing="0" width="200" border="0" align="center">';

		$this->print_user($this->char);
	  
		$sel_exp = myquery("SELECT SUM(exp),SUM(gp) FROM combat_users_exp WHERE user_id='".$this->char['user_id']."' AND combat_id='".$this->combat['combat_id']."'");
		list($EXP,$GP) = mysql_fetch_array($sel_exp);
		if ($EXP>0)
			echo '<tr><td valign="center" colspan=2><font color = "#FFFF00">Пул очков опыта</font><div align="right"><b><font color = "#FFFF00">'.$EXP.' </font></b></td></tr>';
		if ($GP>0)
			echo '<tr><td valign="center" colspan=2><font color = "#FFFF00">Пул монет</font><div align="right"><b><font color = "#FFFF00">'.$GP.' </font></b></td></tr>';
		
		echo '</table>';
		OpenTable('close');

		echo '<div style="background-color:black;display:none;" id="div_newusers">';
		OpenTable('title','100%');
		echo '<span id="span_newusers"></span>';
		OpenTable('close');
		echo '</div>';
	}
	
	private function print_user_hint($user)
	{
		echo'<table cellpadding="2" cellspacing="0" width="97%" border="0" onClick=vkogo="'.$user['user_id'].'";clickn(this) align=center>';
		echo '<tr><td><div align="left">';
		if (chaos_war==0 and $this->combat['combat_type']!=12)
		{
            if ($this->combat['combat_type']==40)//ДЛя многоклана тип боя = 4, пока отключили скрытие информации в многокланах.
            {
                if ($user['clan_id']>0 and ($user['clan_id']==$this->all[$this->char['user_id']]['alies'])or($user['clan_id']==$this->all[$this->char['user_id']]['clan_id']))
                {
                    $this->print_clan($user['clan_id']);
                    print_sklon($user);
                    echo'<font face="Verdana" size="1">'.$user['name'].' [';
                    ?><a onmousemove=movehint(event,1) onmouseover="showhint(
                    '<? echo '<font color=000000>'.$user['race'].' '.$user['clevel'].' уровня '; ?>',
                    '<?
                }
                else
                {
                    $user['clevel'] = "XXX";
                    $user['race'] = "*****";
                    echo'<font face="Verdana" size="1">ПРОТИВНИК [';
                    ?><a onmousemove=movehint(event,1) onmouseover="showhint(
                    '<? echo '<font color=000000>'.$user['race'].' '; ?>',
                    '<?                    
                }
            }
            else
            {
			    $this->print_clan($user['clan_id']);
			    print_sklon($user);
                echo'<font face="Verdana" size="1">'.$user['name'].' [';
                ?><a onmousemove=movehint(event,1) onmouseover="showhint(
                '<? echo '<font color=000000>'.$user['race'].' '.$user['clevel'].' уровня '; ?>',
                '<?
            }
			echo '<font color=000000>Жизни: '.$user['HP'].'/'.$user['HP_MAX'].'<br>';
			echo 'Мана: '.$user['MP'].'/'.$user['MP_MAX'].'<br>';
			echo 'Энергия: '.$user['STM'].'/'.$user['STM_MAX'].'<br>';
			echo 'Сила: '.$user['STR'].'<br>';
			echo 'Интеллект '.$user['NTL'].'<br>';
			echo 'Ловкость '.$user['PIE'].'<br>';
			echo 'Защита '.$user['VIT'].'<br>';
			echo 'Мудрость '.$user['SPD'].'<br>';
			echo 'Выносливость '.$user['DEX'].'<br>';
			?>',0,1,event,1)"  onmouseout="showhint('','',0,0,event,1)"><?
		}
		else
		{
			$user['name'] = "***********";
			$user['clevel'] = "XXX";
			echo'<font face="Verdana" size="1">'.$user['name'].' ['; 
		}
		echo ''.$user['clevel'] . '</a>]</font></div></td></tr>';
		echo'</table>';
	}
	
	private function print_right()
	{
		$count_right = count($this->souz)+count($this->prot);//кол-во союзников и противников
		if ($count_right==1)
		{
			//Бой 1 на 1 против бота или игрока
			OpenTable('title');
			$prot = reset($this->prot);
			echo'<div align="right"><font face="Verdana" size="2" color="#f3f3f3"><b>'.$prot['name'].'</b></font>';
			$this->print_clan($prot['clan_id']);
			print_sklon($prot);
			if ($prot['npc']==1)
			{
				echo '<br><img style="max-width:200px;" src = "http://'.img_domain.'/npc/'.$prot['avatar'].'.gif">';
			}
			else
			{
				echo '<br><img src = "http://'.img_domain.'/avatar/'.$prot['avatar'].'">';
			}
			echo'</div>';

			echo '
			<script>var vkogo='.$prot['user_id'].';var prot_id='.$prot['user_id'].';</script>
			<table cellpadding="2" cellspacing="0" width="100%" border="0">';
			
			$this->print_user($prot);
			
			echo '</table>';
			OpenTable('close');
		}
		else
		{
			//Групповой бой
			OpenTable('title');
			echo'<SCRIPT language=javascript src="js/info.js"></SCRIPT>
			<DIV id=hint  style="Z-INDEX: 0; LEFT: 0px; VISIBILITY: hidden; POSITION: absolute; TOP: 0px"></DIV>
			<center><b><font face=verdana size=2>Групповой бой:</font></b></font><br>Выберите цель</center>
			<script language="JavaScript" type="text/javascript">
			old11="";vkogo=0;prot_id=0;
			function clickn(objName)
			{
				old11.bgColor="";
				objName.bgColor="555555";
				old11=objName;
			}
			</script>';
			echo'<br><center><font color=#00FF00>Союзники:</font><br>';
			// Союзники-игроки
			foreach ($this->souz as $key=>$value)
			{
				$user = $this->souz[$key];
				if ($user['npc']==1) continue;
				$this->print_user_hint($user);
			}
			// Союзники-боты
			foreach ($this->souz as $key=>$value)
			{
				$user = $this->souz[$key];
				if ($user['npc']==0) continue;
				$this->print_user_hint($user);
			}
			echo'<br><center><font color=#00FF00>Противники:</font><br>';
			// Противники-игроки
			foreach ($this->prot as $key=>$value)
			{
				$user = $this->prot[$key];
				if ($user['npc']==1) continue;
				$this->print_user_hint($user);
			}
			// Противники-боты
			foreach ($this->prot as $key=>$value)
			{
				$user = $this->prot[$key];
				if ($user['npc']==0) continue;
				$this->print_user_hint($user);
			}
			OpenTable('close');
		}
	}
	
	public function count_user($state=0)
	{
		//возвращает кол-во игроков боя
		$kol_user = 0;
		foreach ($this->all AS $key=>$value)
		{
			if ($this->all[$key]['npc']==0)
			{
				if ($state!=0)
				{
					if ($this->all[$key]['state']!=$state)
					{
						continue;
					}
				}
				$kol_user++;
			}
		}
		return $kol_user;
	}
	
	public function print_wait()
	{
		myquery("UPDATE combat_users SET time_last_active=".time()." WHERE user_id=".$this->char['user_id']."");
		//запуск расчета хода
		
		// Проверка многокланового боя по 3 ход
		$cont = 1;
		if ($this->combat['combat_type']==4)
		{
			if ($this->combat['hod']<=3)
			{
				$rest = $this->combat['time_last_hod']+$this->timeout-time();
				if ($rest>0)
				{
					$cont = 0;
				}
			}
		}
		if ($cont==1)
		{			
			$low_condition = 0;
			// Проверим, надо ли ожидать игроков, которые пропустили более 3-ёх ходов
			if ($this->combat['time_last_hod']+$this->low_timeout-time()<=0)
			{
				$low_condition = 1;
			}
			
			$all_users = mysql_result(myquery("SELECT COUNT(*) FROM combat_users WHERE combat_id=".$this->combat['combat_id']." and (".$low_condition."=0 OR (".$low_condition."=1 and missed_actions<3))"),0,0);			
			$wait_users = mysql_result(myquery("SELECT COUNT(*) FROM combat_users_state WHERE state=6 AND combat_id=".$this->combat['combat_id'].""),0,0);			
			
			if ($all_users==$wait_users or $this->combat['time_last_hod']+$this->timeout-time()<=0)
			{				
				if ($this->combat['time_last_hod']+$this->timeout-time()<=0)
				{
					// Завершаем бой, в котором один игрок ждёт подтверждения от другого
					$check=myquery("SELECT user_id, state FROM combat_users_state WHERE state=1 AND combat_id=".$this->combat['combat_id']." ");
					if (mysql_num_rows($check) > 0)
					{
						$this->clear_combat();
						setLocation("act.php");
						break;
					}
				}
				
				// Сперва попробуем создать строку. Если она уже есть, mysql_affected_rows() вернет 0.
				myquery("INSERT IGNORE INTO combat_locked (combat_id, hod) VALUES ('".$this->combat['combat_id']."',".$this->combat['hod'].")");
				if (mysql_affected_rows())
				{
					$this->calculate();  
					setLocation("combat.php");
					die();  
				}
			}
		}
		$this->print_header();
		?>
		<table style="width:100%" border="0" align="center" cellpadding="0" cellspacing="0">
			<tr>
				<td style="width:200px;" valign="top" bgcolor="#000000">
				<?
				$this->print_left();
				$rest_second = $this->combat['time_last_hod']-time()+$this->timeout;
				?>
				</td>
				<td valign="top" bgcolor="#000000">
				<meta http-equiv="refresh" content="15">
				<center>
				<span id="text_wait">Ожидание ходов противников<br></span>
				<span id="text_wait1">До конца хода осталось: <font color=ff0000><b>
				<span id="timerr1"><?=$rest_second;?></span></b></font> секунд</span>
				<script language="JavaScript" type="text/javascript">
				function tim()
				{
					timer = document.getElementById("timerr1");
					timer.innerHTML=timer.innerHTML-1;
					if (timer.innerHTML<=0)
					{
						timer.innerHTML = "";
						txt_wait = document.getElementById("text_wait1");
						txt_wait.innerHTML = "";
						txt_wait = document.getElementById("text_wait");
						txt_wait.innerHTML = "Подождите, выполняется расчет хода боя.";
					}
					else
					{
						window.setTimeout("tim()",1000);
					}
				}
				tim();
				</script>
				<br>
				<input type="button" class="button" value="Обновить" onClick="location.reload()"></center><br /><br /><br /><br /><br />
				<? $this->show_log(); ?>
				</td>
				<td style="width:200px;" valign="top" bgcolor="#000000">
				<?
				$this->print_right();
				?>
				</td>
			</tr>
		</table>
		<?		
	}

	public function print_boy()
	{
		require_once('combat_print.php');
	}
	
    private function check_alies($kogo,$proc)
    {
        if ($this->combat['combat_type']==4)
        {            
			//Проверка на союзный клан действует только в многоклановом бою
            /*
			if ($this->all[$this->char['user_id']]['clan_id']!=0)
            {
                if ($this->all[$this->char['user_id']]['alies']!=0)
                {
                    if ($this->all[$this->char['user_id']]['alies']==$this->all[$kogo]['clan_id'])
                    {
                        //Если атака по союзному клану, процент хода ставим = 0 только если в бою еще остались противники не из союзного клана
                        $kol_prot = 0;
                        foreach ($this->prot as $key=>$value)
                        {
                            if ($value['clan_id']!=$this->all[$this->char['user_id']]['alies'])
                            {
                                $kol_prot++;
                            }
                        }
                        if ($kol_prot!=0) $proc = 0;
                    }                                        
                }
                if (isset($this->all[$kogo]['clan_id']) and $this->all[$this->char['user_id']]['clan_id']==$this->all[$kogo]['clan_id'])
                {
                    //По своему клану бить нельзя
                    $proc = 0;
                }
            }
			*/
			if (isset($this->all[$kogo]['side']) and $this->all[$this->char['user_id']]['side']==$this->all[$kogo]['side'])
			{
				//По своему союзнику бить нельзя
				$proc = 0;
			}
        }
        return $proc;
    }
    
	public function otpravka_hoda($otprav)
	{
		//$otprav имеет формат "Тип действия:Процент хода:Куда направлено действие(атака или защита):ID на кого направлено действие(может быть "" или 0 - тогда на себя):Чем сходил(ID записи)):Тип боевого приема атаки", последним действием идет тип позиции
		//тип действия:
		// a1 - атака кулаком
		// a2 - атака оружием
		// a3 - атака магией
		// a4 - атака артефактом
		// a5 - стрельба из лука
		// a6 - бросок предмета
        // a7 - использование свитка
		// z1 - защита щитом
		// z2 - защита магией
		// z3 - защита артефактом
		// l1 - лечение магией
		// l2 - лечение артефактом
		// l3 - лечение эликсиром
        // c1 - игрок убегает		
		// c2 - игрок пропускает ход
		//куда направлено:
		//     атака:
		//            1 - голова
		//            2 - тело
		//            3 - пах
		//            4 - плечо
		//            5 - ноги
		//     защита:
		//            1 - голова и плечо
		//            2 - тело и пах
		//            3 - пах и ноги     
		//тип атаки: 1 - обычная атака, 2 - прицельный удар, 3 - мощный удар, 4 - круговая защита, 5 - пробивающий удар						
		$kol_defense_shit = 0;
		$kol_throw = 0;
        $kol_svitok = 0;
		$action_rand = mt_rand(0,999999999);		
		if (preg_match("/^[0-9;alzc:]+$/",$otprav))
		{
			$array_hod=explode(";",$otprav);
			$sum_proc = 0;
			$str_insert = '';
            $num = 0;
            $action_position = 1;
			
			foreach($array_hod as $key=>$hod)
			{              
				$num++;
				$ar_hod = explode(":",$array_hod[$key]);
                if ($num==1)
                {
                    //позиция игрока в ходе
                    $action_position = (int)$array_hod[$key];
                }
                elseif (isset($ar_hod[5]))
                {			    
					$action_type=trim($ar_hod[0]);
				    $action_proc=(int)$ar_hod[1];
				    $action_kuda=(int)$ar_hod[2];
				    $action_kogo=(int)$ar_hod[3];
				    $action_chem=(int)$ar_hod[4];
				    $action_priem=(int)$ar_hod[5];
				    if (($sum_proc+$action_proc)>100)
				    {
					    $action_proc = 100-$sum_proc;
				    }
                    if (($action_position!=2)AND($action_position!=3)) $action_position=1;
				    if ($this->char['user_id']==612)
				    {
					    //$say_chat =  ''.$action_type.':'.$action_proc.':'.$action_kuda.':'.$action_kogo.':'.$action_chem.':'.$action_priem;
					    //$say = iconv("Windows-1251","UTF-8//IGNORE","ОТЛАДКА: <span style=\"font-style:italic;font-size:12px;color:gold;font-family:Verdana,Tahoma,Arial,Helvetica,sans-serif\">".$say_chat."</b></span>");
					    //myquery("INSERT INTO game_log (`message`,`date`,`fromm`,`too`) VALUES ('".mysql_real_escape_string($say)."',".time().",-1,612)");
				    }

				    //делаем проверки на возможность конкретного действия
				    if ($action_type=="z1")
				    {
					    //защита щитом не более 2 раз
					    $kol_defense_shit++;
					    if ($kol_defense_shit>2)
					    {
						    $action_proc = 0;
					    }
				    }
				    $action_type_sort = 0;
				    if (($action_proc>0)OR($action_type=="a5")OR($action_type=="a6")OR($action_type=="a7"))
				    {
					    switch($action_type)
					    {
						    case "a1":
						    {
							    //атака кулаком
							    if ($action_priem<1) $action_priem = 1;
							    if ($action_priem>3) $action_priem = 1;
							    if ($action_priem==2 AND $this->char['MS_KULAK']<3) $action_priem = 1;
							    if ($action_priem==3 AND $this->char['MS_KULAK']<6) $action_priem = 1;
							    $action_type = 11;
							    $action_type_sort = 1;
                                $action_proc = $this->check_alies($action_kogo,$action_proc);
						    }
						    break;
						    
						    case "a2":
						    {
							    if ($action_priem<1) $action_priem = 1;
							    if ($action_priem>5) $action_priem = 1;
							    if ($action_priem==2 AND $this->char['MS_WEAPON']<2) $action_priem = 1;
							    if ($action_priem==3 AND $this->char['MS_WEAPON']<6) $action_priem = 1;
                                if ($action_priem==5 AND $this->char['MS_WEAPON']<4) $action_priem = 1;
							    if ($action_priem==4 AND $this->char['MS_WEAPON']<8) $action_priem = 1;
							    $selused = myquery("
							    SELECT id 
							    FROM game_items 
							    WHERE game_items.user_id=".$this->char['user_id']." 
							    AND game_items.id = ".$action_chem."
							    AND game_items.item_uselife>0
							    AND game_items.used=1
							    AND game_items.priznak=0");
							    if ($selused==false OR mysql_num_rows($selused)==0)
							    {
								    $action_proc = 0;
							    }
							    $action_type = 12;
							    $action_type_sort = 1; 
							    if ($action_priem == 4)
							    {
								    //круговая защита оружием - кидаем в защиту
                                    if ($action_proc != 100)
                                    {
                                        $action_proc = 0;
                                        $action_priem = 1;
                                    }
                                    else
                                    {
								        $action_type = 21; 
								        $action_kogo = 0; 
								        $action_type_sort = 2; 
                                    }
							    }
								else
								{
									$action_proc = $this->check_alies($action_kogo,$action_proc);
								}
						    }
						    break;
						    
						    case "a3":
						    {
                                $action_priem = 1;
							    $selspets = myquery("SELECT id FROM game_spells WHERE id=".$action_chem."");
							    if ($selspets==false OR mysql_num_rows($selspets)==0)
							    {
								    $action_proc = 0;
							    }
							    $action_type = 13;
							    $action_type_sort = 1; 
                                $action_proc = $this->check_alies($action_kogo,$action_proc);
						    }
						    break;
						    
						    case "a4":
						    {
                                $action_priem = 1;
							    $selused = myquery("
							    SELECT id 
							    FROM game_items 
							    WHERE game_items.user_id=".$this->char['user_id']." 
							    AND game_items.id = ".$action_chem."
							    AND game_items.item_uselife>0
							    AND game_items.used=3 
							    AND game_items.priznak=0");
							    if ($selused==false OR mysql_num_rows($selused)==0)
							    {
								    $action_proc = 0;
							    }
							    $action_type = 14;
							    $action_type_sort = 1; 
                                $action_proc = $this->check_alies($action_kogo,$action_proc);
						    }
						    break;
						    
						    case "a5":
						    {
							    $action_priem = 1;
							    $selused = myquery("
							    SELECT item_id 
							    FROM game_items 
							    WHERE game_items.user_id=".$this->char['user_id']." 
							    AND game_items.id = ".$action_chem."
							    AND game_items.priznak=0");
							    if ($selused==false OR mysql_num_rows($selused)==0)
							    {
								    $action_proc = -1;
							    }
							    else
							    {
								    list($item_id) = mysql_fetch_array($selused);
								    $check_luk = myquery("
								    SELECT game_items.item_id FROM game_items,game_items_factsheet WHERE
								    game_items.user_id=".$this->char['user_id']." 
								    AND game_items_factsheet.id = ".$item_id."
								    AND game_items_factsheet.quantity=game_items.item_id
								    AND game_items.priznak=0
								    AND game_items.item_uselife>0
								    AND game_items.used=4");
								    if ($check_luk==false OR mysql_num_rows($check_luk)==0)
								    {
									    $action_proc = -1;
								    }
								    else
								    {
									    list($item_id) = mysql_fetch_array($check_luk);
									    list($item_type)=mysql_fetch_array(myquery("SELECT type FROM game_items_factsheet WHERE id=".$item_id.""));
									    if ($item_type!=18) $action_proc = -1;
								    }
							    }
							    $kol_throw++;
							    if ($kol_throw>1) $action_proc = -1;
							    $action_type = 15;
							    $action_type_sort = 1; 
                                $action_proc = $this->check_alies($action_kogo,$action_proc);
						    }
						    break;
						    
						    case "a6":
						    {
							    $action_priem = 1;
							    $selused = myquery("
							    SELECT item_id 
							    FROM game_items 
							    WHERE game_items.user_id=".$this->char['user_id']." 
							    AND game_items.id = ".$action_chem."
							    AND game_items.priznak=0");
							    if ($selused==false OR mysql_num_rows($selused)==0)
							    {
								    $action_proc = -1;
							    }
							    else
							    {
								    list($item_id) = mysql_fetch_array($selused);
								    $check_throw = myquery("
								    SELECT type_weapon,type_weapon_need FROM game_items_factsheet WHERE id=".$item_id." AND type=19");
								    if ($check_throw==false OR mysql_num_rows($check_throw)==0)
								    {
									    $action_proc = -1;
								    }
								    else
								    {
									    list($type_weapon,$type_weapon_need) = mysql_fetch_array($check_throw);
									    if ($type_weapon!=0)
									    {
										    $MS = 0;
										    if($type_weapon==1) {
											    $MS = $this->char['MS_KULAK'];
										    }
										    if($type_weapon==2) {
											    $MS = $this->char['MS_LUK'];
										    }
										    if($type_weapon==3) {
											    $MS = $this->char['MS_SWORD'];
										    }
										    if($type_weapon==4) {
											    $MS = $this->char['MS_AXE'];
										    }
										    if($type_weapon==5) {
											    $MS = $this->char['MS_SPEAR'];
										    }
										    if($type_weapon==6) {
											    $MS = $this->char['MS_THROW'];
										    }
										    if ($MS<$type_weapon_need) $action_proc = -1; 
									    }
								    }
							    }
							    $kol_throw++;
							    if ($kol_throw>1) $action_proc = -1;
							    $action_type = 16;
							    $action_type_sort = 1; 
                                $action_proc = $this->check_alies($action_kogo,$action_proc);
						    }
						    break;
						    
                            case "a7":
                            {
                                $action_priem = 1;
                                $selused = myquery("
                                SELECT item_id 
                                FROM game_items 
                                WHERE user_id=".$this->char['user_id']." 
                                AND id = ".$action_chem." AND item_id IN (".item_id_svitok_light_usil.",".item_id_svitok_medium_usil.",".item_id_svitok_hard_usil.",".item_id_svitok_absolut_usil.",".item_id_svitok_light_sopr.",".item_id_svitok_medium_sopr.",".item_id_svitok_hard_sopr.",".item_id_svitok_absolut_sopr.")
                                AND priznak=0");
                                if ($selused==false OR mysql_num_rows($selused)==0)
                                {
                                    $action_proc = -1;
                                }
                                else
                                {
                                    list($item_id) = mysql_fetch_array($selused);
                                }
                                $kol_svitok++;
                                if ($kol_svitok>1) $action_proc = -1;
                                $action_type = 17;
                                $action_type_sort = 2; 
                                $action_proc = $this->check_alies($action_kogo,$action_proc);
                            }
                            break;
                            
						    case "z1":
						    {
							    $selused = myquery("
							    SELECT id 
							    FROM game_items 
							    WHERE game_items.user_id=".$this->char['user_id']." 
							    AND game_items.id = ".$action_chem."
							    AND game_items.item_uselife>0
							    AND game_items.used=4
							    AND game_items.priznak=0");
							    if ($selused==false OR mysql_num_rows($selused)==0)
							    {
								    $action_proc = 0;
							    }
							    $action_type = 21;
							    $action_type_sort = 3; 
						    }
						    break;
						    
						    case "z2":
						    {
							    $selspets = myquery("SELECT id FROM game_spells WHERE id=".$action_chem."");
							    if ($selspets==false OR mysql_num_rows($selspets)==0)
							    {
								    $action_proc = 0;
							    }
							    $action_type = 22;
							    $action_type_sort = 3; 
						    }
						    break;
						    
						    case "z3":
						    {
							    $selused = myquery("
							    SELECT id 
							    FROM game_items 
							    WHERE game_items.user_id=".$this->char['user_id']." 
							    AND game_items.id = ".$action_chem."
							    AND game_items.item_uselife>0
							    AND game_items.used=3 
							    AND game_items.priznak=0");
							    if ($selused==false OR mysql_num_rows($selused)==0)
							    {
								    $action_proc = 0;
							    }
							    $action_type = 23;
							    $action_type_sort = 3; 
						    }
						    break;
						    
						    case "l1":
						    {
							    $selspets = myquery("SELECT id FROM game_spells WHERE id=".$action_chem."");
							    if ($selspets==false OR mysql_num_rows($selspets)==0)
							    {
								    $action_proc = 0;
							    }
							    $action_type = 31;
							    $action_type_sort = 4; 
						    }
						    break;
						    
						    case "l2":
						    {
							    $selused = myquery("
							    SELECT game_items.id 
							    FROM game_items 
							    WHERE game_items.user_id=".$this->char['user_id']." 
							    AND game_items.id = ".$action_chem."
							    AND game_items.item_uselife>0
							    AND game_items.used=3
							    AND game_items.priznak=0");
							    if ($selused==false OR mysql_num_rows($selused)==0)
							    {
								    $action_proc = 0;
							    }
							    $action_type = 32;
							    $action_type_sort = 4; 
						    }
						    break;
						    
						    case "l3":
						    {
							    $already_eliksir = mysql_result(myquery("SELECT eliksir FROM combat_users WHERE user_id=".$this->char['user_id'].""),0,0);
							    if ($already_eliksir!=1)
							    {
								    $selused = myquery("
								    SELECT game_items.id 
								    FROM game_items,game_items_factsheet 
								    WHERE game_items.user_id=".$this->char['user_id']." 
								    AND game_items.id = ".$action_chem."
								    AND game_items_factsheet.id=game_items.item_id
								    AND game_items_factsheet.type=13
                                    AND game_items.used>0
								    AND game_items.item_uselife>0
								    AND game_items.used IN (12,13,14) 
								    AND game_items.priznak=0");
								    if ($selused==false OR mysql_num_rows($selused)==0)
								    {
									    $action_proc = 0;
								    }
								    $action_type = 33;
								    $action_type_sort = 4; 
								    if ($action_proc<100)
								    {
									    //эликсиры используются всегда на 100%
									    $action_proc = 0;
								    }
							    }
							    else
							    {
								    $action_proc = 0;
							    }
						    }
						    break;
						    
							case "c1":
							{
								$action_type = 91;								
								$action_type_sort = 0;
							}
							break;
							
							case "c2":
							{
								$action_type = 92;								
								$action_type_sort = 0;
							}
							break;
							
						    default:
						    {
							    $action_proc = 0;
						    }
						    break;
					    }
				    }
				    if ($action_type>=21 AND $action_type<=33)
				    {
					    //при лечении и защите если на кого = 0, значит на себя
					    //нельзя лечить и защищать ботов
					    if ($action_kogo==0 OR !isset($this->all[$action_kogo]))
					    {
						    $action_kogo = $this->char['user_id'];
					    }
					    if ($this->all[$action_kogo]['npc']==1)
					    {
						    //действие по боту
						    $action_proc = 0;
					    }
				    }
				    if ($action_type==21)
				    {
					    //если защищаем не себя - уменьшаем в 2 раза процент действий
					    if ($action_kogo!=$this->char['user_id'])
					    {
						    $action_proc=(int)$action_proc/2;
					    }
				    }
				    if ($action_proc>0 OR (($action_proc==0) AND (($action_type==15) OR ($action_type==16) OR ($action_type==17))))
				    {
					    //ход разрешен
					    //делаем запись в БД
					    $sum_proc+=$action_proc;
					    $str_insert.="(".$this->combat['combat_id'].",".$this->combat['hod'].",".$this->char['user_id'].",".$action_type.",".$action_chem.",".$action_kogo.",".$action_kuda.",".$action_proc.",".$action_priem.",".$action_rand.",".$action_type_sort.",".$action_position."),";
				    }
                }
			}
			if ($str_insert!="")
			{
				$str_insert = substr($str_insert,0,-1);
				myquery("INSERT INTO combat_actions (combat_id,hod,user_id,action_type,action_chem,action_kogo,action_kuda,action_proc,action_priem,action_rand,action_type_sort,position) VALUES ".$str_insert.";");
			}
			$tek_time = time();
			combat_setFunc($this->char['user_id'],6,$this->combat['combat_id']);
			myquery("update combat_users set time_last_active=$tek_time, missed_actions = 0 where user_id=".$this->char['user_id']."");			
		}
	}
	
	public function print_begin()
	{
		$rest_time = $this->combat['time_last_hod']-time();
		if ($rest_time<=0)
		{
			combat_setFunc($this->char['user_id'],5,$this->combat['combat_id']);
			foreach ($this->souz as $key=>$value)
			{
				combat_setFunc($key,5,$this->combat['combat_id']);    
			}
			foreach ($this->prot as $key=>$value)
			{
				combat_setFunc($key,5,$this->combat['combat_id']);    
			}
		}
		else
		{
			$this->print_header();
			?>
			<meta http-equiv="refresh" content="15">
			<center><br>До начала боя осталось <b><font color=#FF0000><?=$rest_time;?></font></b> секунд<br><br><br><br>
			<?
		}
	}
	
	public function check_weapon_class ($class, $weapon)
	{
		if ($class==1 AND $weapon==3) return true;
		elseif ($class==2 AND $weapon==4) return true;
		elseif ($class==3 AND $weapon==5) return true;
		return false;
	}
	
	//************************************************************************************
	// БЛОК ОБСЧЕТА ХОДА С ИЗМЕНЕНИЕМ СОСТОЯНИЙ ИГРОКОВ
	// при обсчете использовать prot* и souz* запрещено!
	//************************************************************************************	
	private function make_hod_npc($npc_id)
	{
		$npc_temp=$this->all[$npc_id]['npc_id_template'];
		$check_kol_attack=myquery("Select * From game_npc_set_option Where npc_id=$npc_temp and opt_id=3");
		$kol_attack=mysql_num_rows($check_kol_attack);
		$prot_npc=0;
		
        //Проверим, не с ботом НЕЧТО ли идет бой
		if ($kol_attack==1)
		{
			$prot_array = array();
            foreach ($this->all AS $key=>$value)
            {                                                                                                                                                         $action_rand = mt_rand(0,999999999);
                if ($this->all[$npc_id]['side']==$this->all[$key]['side']) continue;
                if ($this->all[$key]['join']==1) continue;
                if ($this->all[$npc_id]['HP']>0)
                {
                    $prot_array[] = $key;
                }
            }    
            if (sizeof($prot_array)>0)
            {
                $prot_npc = $prot_array[mt_rand(0,sizeof($prot_array)-1)];
            }     
		}

		// TODO
		// Игроков может быть несколько - для всех прогоняем алгоритм
		// Но это все хорошо бы переделать на функции + сделать функцию выбора кого атаковать
		// на выходе нее будет массив
		// (user_id => %-от атаки)
		foreach ($this->all AS $key=>$value)
		{   
			if ($kol_attack==1 and $key!=$prot_npc) continue; 
            $action_rand = mt_rand(0,999999999);
			if ($this->all[$npc_id]['side']==$this->all[$key]['side']) continue;
			if ($this->all[$key]['join']==1) continue;
			if ($this->all[$npc_id]['HP']>0)
			{
				if ($this->all[$npc_id]['PIE']==$this->all[$key]['PIE'] AND $this->all[$npc_id]['SPD']==$this->all[$key]['SPD'])
				{
					//Для Подземки хитрый алгоритм - усложнение существования игроков :-)
					$act[1]['action']=0;
					$act[1]['procent']=0;
					$act[2]['procent']=0;
					$act[2]['action']=0;
					if ($this->all[$npc_id]['STR']>$this->all[$npc_id]['NTL'])
					{
						//удар оружием
						$act[1]['action']=1;
						$act[1]['procent']=100;
					}
					elseif ($this->all[$npc_id]['NTL']>$this->all[$npc_id]['STR'])
					{
						//удар магией
						$act[1]['action']=2;
						$act[1]['procent']=100;
					}
					else
					{
						//удар артом
						$act[1]['action']=3;
						$act[1]['procent']=100;
					}
				}
				else
				{
					$shield_defense = $this->all[$npc_id]['clevel']*2+10;
					if ($this->all[$key]['PIE']>=1*$this->all[$npc_id]['PIE'])
					{
						$A = true;
					}
					else
					{
						$A = false;
					}
					if ($this->all[$key]['SPD']>=1*$this->all[$npc_id]['SPD'])
					{
						$B = true;
					}
					else
					{
						$B = false;
					}
					if (($this->all[$key]['VIT'])>$this->all[$npc_id]['STR']*0.5)
					{
						$D = true;
					}
					else
					{
						$D = false;
					}
					$check=myquery("Select * From game_npc_set_option Where npc_id=$npc_temp and opt_id=2");
					if (mysql_num_rows($check)==0)
					{
						if ($this->all[$npc_id]['HP']<=0.24*$this->all[$npc_id]['HP_MAX']) $E=0.8;
						elseif ($this->all[$npc_id]['HP']<=0.50*$this->all[$npc_id]['HP_MAX']) $E=0.6;
						elseif ($this->all[$npc_id]['HP']<=0.75*$this->all[$npc_id]['HP_MAX']) $E=0.4;
						else $E=0.2;
					}
					else
					{
						$E=0;	
					}
					

					$act[1]['action'] = 0;
					$act[1]['procent'] = 0;
					$act[1]['spell'] = 0;
					$act[2]['action'] = 0;
					$act[2]['procent'] = 0;
					$act[2]['spell'] = 0;

					$R = mt_rand(0,10);
					if ($A==true)
					{
						if ($B==false)
						{
							//магия
							$act[1]['action'] = 2;$act[1]['procent']=(1-$E)*100;
						}
						else
						{
							//арт
							$act[1]['action'] = 3;$act[1]['procent']=(1-$E)*100;
						}
					}
					else
					{
						if ($D==false)
						{
							//оружие
							$act[1]['action'] = 4;$act[1]['procent']=(1-$E)*100;
						}
						else
						{
							if ($B==false)
							{
								//магия
								$act[1]['action'] = 2;$act[1]['procent']=(1-$E)*100;
							}
							else
							{
								//арт
								$act[1]['action'] = 3;$act[1]['procent']=(1-$E)*100;
							}
						}
					}
					if ($R<=4 AND $E>0.5)       {$act[2]['action'] = 5;$act[2]['procent']=$E*100;}
					elseif ($R<=4 AND $E<=0.5)  {                      $act[1]['procent']=100;}
					elseif ($R>4 AND $R<=6 and mysql_num_rows($check)==0) {$act[2]['action'] = 5;$act[2]['procent']=$E*100;}
					elseif ($R>6 AND $R<=8)     {$act[2]['action'] = 6;$act[2]['procent']=$E*100;}
					elseif ($R>8 AND $R<=10)    {$act[2]['action'] = 7;$act[2]['procent']=$E*100;}
				}

				if ($this->all[$key]['clevel']<=10) {$spell_level_min = 1; $spell_level_max = 5;}
				elseif ($this->all[$key]['clevel']<=19) {$spell_level_min = 5; $spell_level_max = 12;}
				elseif ($this->all[$key]['clevel']<=25) {$spell_level_min = 8; $spell_level_max = 15;}
				elseif ($this->all[$key]['clevel']<=29) {$spell_level_min = 11; $spell_level_max = 15;}
				elseif ($this->all[$key]['clevel']<=31) {$spell_level_min = 13; $spell_level_max = 15;}
				else {$spell_level_min = 14; $spell_level_max = 15;};

				if ($act[1]['procent']>100)  $act[1]['procent']=100;
				if ($act[2]['procent']>100)  $act[2]['procent']=100;
				if (($act[1]['procent']+$act[2]['procent'])>100) $act[2]['procent']=100-$act[1]['procent'];

				if ($this->all[$key]['clevel']<=5)
				{
					$act[1]['action']=1;
					$act[1]['procent']=100;
					$act[2]['procent']=0;
					$act[2]['action']=0;
				}
                                          
				for ($i=1;$i<=2;$i++)
				{
					if ($act[$i]['procent']>0)
					{
						if ($act[$i]['action']==5) $act[$i]['action']=6;
						if ($act[$i]['action']==2 OR $act[$i]['action']==5 OR $act[$i]['action']==6)
						{
							if ($act[$i]['action']==2) $spell_type = 1;
							if ($act[$i]['action']==5) $spell_type = 2;
							if ($act[$i]['action']==6) $spell_type = 3;
							$sel_spell = myquery("SELECT * FROM game_spells WHERE type='".$spell_type."' AND level>=".$spell_level_min." AND level<=".$spell_level_max."");
							$all = mysql_num_rows($sel_spell);
							if ($all==0) 
							{
								$act[$i]['action'] = 3;
								$act[$i]['spell'] = 0;
							}
							else
							{
								$r = mt_rand(0,$all-1);
								mysql_data_seek($sel_spell,$r);							
								$spell = mysql_fetch_assoc($sel_spell);
								$act[$i]['spell'] = $spell['id'];
								if ($this->all[$npc_id]['MP']<$spell['mana']) 
								{
									$act[$i]['action'] = 3;
									$act[$i]['spell'] = 0;
								}
							}							
						}
						$kud_attack = mt_rand(1,5);
						$kud_defense = mt_rand(1,3);
						switch ($act[$i]['action'])
						{
							case 1:   //атака оружием
								myquery("INSERT INTO combat_actions (
								combat_id,hod,user_id,
								action_type,action_chem,action_kogo,action_kuda,action_proc,action_priem,action_rand,action_type_sort,position)
								VALUES
								(".$this->combat['combat_id'].",".$this->combat['hod'].",$npc_id,
								12,0,$key,$kud_attack,".$act[$i]['procent'].",0,$action_rand,1,".$this->all[$npc_id]['position'].")");
							break;
							case 2:   //магическая атака
								myquery("INSERT INTO combat_actions (
								combat_id,hod,user_id,
								action_type,action_chem,action_kogo,action_kuda,action_proc,action_priem,action_rand,action_type_sort,position)
								VALUES
								(".$this->combat['combat_id'].",".$this->combat['hod'].",$npc_id,
								13,".$act[$i]['spell'].",$key,0,".$act[$i]['procent'].",0,$action_rand,1,".$this->all[$npc_id]['position'].")");
							break;
							case 3:   // атака артом
								myquery("INSERT INTO combat_actions (
								combat_id,hod,user_id,
								action_type,action_chem,action_kogo,action_kuda,action_proc,action_priem,action_rand,action_type_sort,position)
								VALUES
								(".$this->combat['combat_id'].",".$this->combat['hod'].",$npc_id,
								14,0,$key,$kud_attack,".$act[$i]['procent'].",0,$action_rand,1,".$this->all[$npc_id]['position'].")");
							break;
							case 4:   //атака оружием
								myquery("INSERT INTO combat_actions (
								combat_id,hod,user_id,
								action_type,action_chem,action_kogo,action_kuda,action_proc,action_priem,action_rand,action_type_sort,position)
								VALUES
								(".$this->combat['combat_id'].",".$this->combat['hod'].",$npc_id,
								12,0,$key,$kud_attack,".$act[$i]['procent'].",0,$action_rand,1,".$this->all[$npc_id]['position'].")");
							break;
							case 5:   //лечение магией
								myquery("INSERT INTO combat_actions (
								combat_id,hod,user_id,
								action_type,action_chem,action_kogo,action_kuda,action_proc,action_priem,action_rand,action_type_sort,position)
								VALUES
								(".$this->combat['combat_id'].",".$this->combat['hod'].",$npc_id,
								31,".$act[$i]['spell'].",$key,0,".$act[$i]['procent'].",0,$action_rand,3,".$this->all[$npc_id]['position'].")");
							break;
							case 6:   //защита магией
								myquery("INSERT INTO combat_actions (
								combat_id,hod,user_id,
								action_type,action_chem,action_kogo,action_kuda,action_proc,action_priem,action_rand,action_type_sort,position)
								VALUES
								(".$this->combat['combat_id'].",".$this->combat['hod'].",$npc_id,
								22,".$act[$i]['spell'].",$npc_id,0,".$act[$i]['procent'].",0,$action_rand,3,".$this->all[$npc_id]['position'].")");
							break;
							case 7:   //защита щитом
								myquery("INSERT INTO combat_actions (
								combat_id,hod,user_id,
								action_type,action_chem,action_kogo,action_kuda,action_proc,action_priem,action_rand,action_type_sort,position)
								VALUES
								(".$this->combat['combat_id'].",".$this->combat['hod'].",$npc_id,
								21,0,$npc_id,$kud_defense,".$act[$i]['procent'].",0,$action_rand,3,".$this->all[$npc_id]['position'].")");
							break;
						}
					}
				}
			}
		}
	}	

	private function get_koeff_from_sklon($kto,$kogo,$clan_id)
	{
		$add = 0;
		if ($kto==0 AND $clan_id>0)
		{
			$clan_sklon = mysql_result(myquery("SELECT sklon FROM game_clans WHERE clan_id=$clan_id"),0,0);
			if ($clan_sklon==0)
			{
				return 0.8;
			}
		}
		if ($kto==$this->add_exp_for_sklon) $add=0.2;
		if ($kto==1)
		{
			switch ($kogo)
			{
				case 0: return 1.2+$add;break;
				case 1: return 0.8+$add;break;
				case 2: return 1.2+$add;break;
				case 3: return 1.2+$add;break;
			}
		}
		if ($kto==2)
		{
			switch ($kogo)
			{
				case 0: return 1+$add;break;
				case 1: return 1.2+$add;break;
				case 2: return 0.8+$add;break;
				case 3: return 1.4+$add;break;
			}
		}
		if ($kto==3)
		{
			switch ($kogo)
			{
				case 0: return 1+$add;break;
				case 1: return 1.2+$add;break;
				case 2: return 1.4+$add;break;
				case 3: return 0.8+$add;break;
			}
		}
		return 1+$add;
	}
    
    private function calc_position($damage_hp,$kto,$kogo)
    {
        switch ($this->all[$kto]['position'])
        {
            case 1:
            {
                if ($this->all[$kogo]['position']==2)
                {
                    $damage_hp=ceil($damage_hp*1.2);
                }
            }
            break;
            
            case 2:
            {
            }
            break;
            
            case 3:
            {
                if ($this->all[$kogo]['position']==2)
                {
                    $damage_hp=ceil($damage_hp*0.25);
                }
                else
                {
                    $damage_hp=ceil($damage_hp*1.4);                   
                }               
            }
            break;
            
        }
        if ($this->all[$kogo]['svit_sopr']>0)
        {
            $damage_hp=max(0,ceil($damage_hp*(1-$this->all[$kogo]['svit_sopr']/100)));
        }
        return $damage_hp;
    }

	private function calculate() //расчет хода
	{	
		require_once('combat_calculate.php');
	}
	
	private function break_items($user_id,$from_npc)
	{
		//уменьшим ресурс прочности предметов, кроме оружия
		$minus = 1;		
		if ($from_npc==1)
		{			
			$koef_b = 0.7;
		}
		else
		{
			$koef_b = 1;
		}
		myquery("UPDATE game_items,game_items_factsheet SET game_items.item_uselife=game_items.item_uselife-($minus+(CASE WHEN game_items.used>0 THEN 0.5 ELSE -0.2 END))*$koef_b WHERE game_items.user_id=$user_id AND game_items.priznak=0 AND game_items.item_id=game_items_factsheet.id AND game_items_factsheet.type NOT IN (12,13,19,20,21,22,23,92,97,99)");
		//если текущая поточная прочность = 0, то вещь снимаем
		$sel = myquery("SELECT game_items.id FROM game_items,game_items_factsheet WHERE game_items.used>0 AND game_items.priznak=0 AND game_items.user_id=$user_id AND game_items.item_uselife<=0 AND game_items.item_id=game_items_factsheet.id AND game_items_factsheet.type NOT IN (12,13,19,21)");
		while (list($it_id) = mysql_fetch_array($sel))
		{
			$Item = new Item($it_id);
			$Item->down();
		}
	}
    
    private function check_item_down($id_items,$user_id)
    {
        if ($id_items == -1)
        {
            $sel = myquery("SELECT id FROM game_items WHERE used=1 AND priznak=0 AND user_id=$user_id AND item_uselife<=0");
            while (list($it_id) = mysql_fetch_array($sel))
            {
                $Item = new Item($it_id);
                $Item->down();
            }
        }
        else
        {
            $sel = myquery("SELECT id FROM game_items WHERE used>0 AND priznak=0 AND user_id=$user_id AND item_uselife<=0 AND id=$id_items");
            while (list($it_id) = mysql_fetch_array($sel))
            {
                $Item = new Item($it_id);
                $Item->down();
            }
        }
    }
	
	private function against_npc($user_id)
	{
		//Проверяет - бой ли это с ботом
		
		if ($this->combat['npc'] == 1)
		{
			return true;
		}
		else
		{
		}
			return false;
		/*
		foreach ($this->all as $key=>$value)
		{
			if ($this->all[$key]['side']!=$this->all[$user_id]['side'])
			{
				if ($this->all[$key]['npc']==0)
				{
					return false;
				}
			}
		}
		return true;
		*/
	}
	
	private function user_dead($user_id,$user_win) //отработка смерти игрока или бота в бою, одновременно отрабатываем действия над его противниками
	{
		if ($this->all[$user_id]['npc']==1)//Умер бот
		{
			//удаляем бота из таблицы состояний боя
			combat_delFunc($user_id);
			
			$expsumm = 0;
			$sel = myquery("SELECT * FROM combat_users_exp WHERE combat_id=".$this->combat['combat_id']." AND prot_id=".$user_id." AND (exp>0 OR gp>0)");                
            //начисляем накопленный опыт за бота игрокам
			while ($userwin = mysql_fetch_array($sel))
			{
				if (!isset($this->all[$userwin['user_id']])) continue;
				
				//для 3го типа квестов движка
				myquery("UPDATE quest_engine_users SET par2_value=par2_value+".$userwin['exp']." WHERE user_id=".$userwin['user_id']." AND quest_type=3");    
				$this->all[$userwin['user_id']]['exp']+=$userwin['exp'];
				$this->all[$userwin['user_id']]['gp']+=$userwin['gp'];
				if ($userwin['user_id']==$user_win)
				{
					$expsumm+=$userwin['exp'];
				}
				if ($userwin['gp']!=0) setGP($userwin['user_id'],$userwin['gp'],22);
				if ($userwin['exp']!=0) setEXP($userwin['user_id'],$userwin['exp'],1);
				if (function_exists("save_stat"))
				{
					save_stat($userwin['user_id'],$user_id,"",5,"","","",$userwin['gp'],"",$userwin['exp'],"","");
				}
				$userwin_clan = (int)$this->all[$userwin['user_id']]['clan_id'];
				$da = getdate();
				$user_exp_store = 0;
				$npc_exp_store = (int)$userwin['exp'];
				myquery("INSERT INTO game_combats_exp (clan_id,year,month,npc_exp,user_exp) VALUES ($userwin_clan,".$da['year'].",".$da['mon'].",$npc_exp_store,$user_exp_store) ON DUPLICATE KEY UPDATE npc_exp=npc_exp+$npc_exp_store,user_exp=user_exp+$user_exp_store");
				if ($userwin['exp']>0 or $userwin['gp']>0)
				{
					$mes = '<font color=\"#eeeeee\">Ты '.echo_sex('выиграл','выиграла',$this->all[$userwin['user_id']]['pol']).' бой. ';
					$mes.= ''.$this->all[$user_id]['name'].' был побежден и бежал в неизвестном направлении!'; 
					$mes.= ' Ты получаешь <span style=\"font-weight:800;color:gold;\">'.$userwin['exp'].'</span> опыта';
					if ($userwin['gp']!=0)
					{
						$mes.=' и <span style=\"font-weight:800;color:gold;\">'.$userwin['gp'].'</span> монет';
					}
					$mes = $mes.'!</font>';
					$result = myquery("INSERT game_battles SET attacker_id=".$userwin['user_id'].", target_id=$user_id, map_name=".$this->combat['map_name'].", map_xpos=".$this->combat['map_xpos'].", map_ypos=".$this->combat['map_ypos'].", contents='".mysql_real_escape_string($mes)."', post_time=".time()."");
				}
			}
			myquery("DELETE FROM combat_users_exp WHERE combat_id=".$this->combat['combat_id']." AND prot_id=".$user_id."");
			//перемещение бота, выставление времени респауна, "лечение" бота
			$this->clear_user($user_id);
			$Npc = new Npc($user_id);
			$Npc->on_dead();
			//если убивается бот вводного квеста в Гильдии Новичков
			$sel = myquery("SELECT step FROM game_users_intro WHERE user_id=$user_win");
			if ($sel!=false AND mysql_num_rows($sel)>0)
			{
				list($step)=mysql_fetch_array($sel);
				if ($step==10 OR $step==11)
				{
					myquery("UPDATE game_users_intro SET step=step+1 WHERE user_id=$user_win");
				}
			}
			
			//проверка квестовых заданий на убийство бота            
			$selquest = myquery("SELECT * FROM game_quest_users WHERE user_id=$user_win AND quest_id=1 AND sost=2");
			if (mysql_num_rows($selquest))
			{
				if (isset($_SESSION['quest1_step']))
				{
					myquery("UPDATE game_quest_users SET sost=0,last_time=0 WHERE user_id=$user_win AND quest_id=1 AND sost=2");
					if ($_SESSION['quest1_step']==7) {$_SESSION['quest1_step']=8;};
					if ($_SESSION['quest1_step']==14) {$_SESSION['quest1_step']=15;};
					if ($_SESSION['quest1_step']==24) {$_SESSION['quest1_step']=25;};
					if ($_SESSION['quest1_step']==27) {$_SESSION['quest1_step']=28;};
					if ($_SESSION['quest1_step']==30) {$_SESSION['quest1_step']=31;};
					if ($_SESSION['quest1_step']==32) {$_SESSION['quest1_step']=33;};
					if ($_SESSION['quest1_step']==34) {$_SESSION['quest1_step']=35;};
					if ($_SESSION['quest1_step']==36) {$_SESSION['quest1_step']=37;};
					if ($_SESSION['quest1_step']==38) {$_SESSION['quest1_step']=39;};
					if ($_SESSION['quest1_step']==40) {$_SESSION['quest1_step']=41;};
					if ($_SESSION['quest1_step']==42) {$_SESSION['quest1_step']=43;};
					if ($_SESSION['quest1_step']==44) {$_SESSION['quest1_step']=45;};
					if ($_SESSION['quest1_step']==51) {$_SESSION['quest1_step']=52;};
				}
			}
			$selquest = myquery("SELECT * FROM game_quest_users WHERE user_id=$user_win AND quest_id=21 AND sost=2");
			if (mysql_num_rows($selquest))
			{
				if (isset($_SESSION['quest2_step']))
				{
					myquery("UPDATE game_quest_users SET sost=0,last_time=0 WHERE user_id=$user_win AND quest_id=21 AND sost=2");
					if ($_SESSION['quest2_step']==201) {$_SESSION['quest2_step']=202;};
					if ($_SESSION['quest2_step']==203) {$_SESSION['quest2_step']=204;};
					if ($_SESSION['quest2_step']==205) {$_SESSION['quest2_step']=206;};
					if ($_SESSION['quest2_step']==207) {$_SESSION['quest2_step']=208;};
					if ($_SESSION['quest2_step']==209) {$_SESSION['quest2_step']=210;};
					if ($_SESSION['quest2_step']==211) {$_SESSION['quest2_step']=212;};
				}
			}
			
			//движок квестов
			include("quest/quest_engine_types/quests_engine_wincheck.php");
			if ($Npc->npc['npc_quest_id']>=2 AND $Npc->npc['npc_quest_id']<=7)
			{
				$selquest = myquery("SELECT * FROM game_quest_users WHERE user_id=$user_win AND quest_id=".$Npc->npc['npc_quest_id']." AND sost=".$Npc->npc['npc_id']."");
				if (mysql_num_rows($selquest))
				{
					$proc = 100;
					if ($Npc->npc['EXP']==0) $proc=0;
					elseif ($expsumm<($Npc->npc['EXP']-5)) $proc = 100*($expsumm/$Npc->npc['EXP']);
					$proc = min(100,$proc);
					myquery("DELETE FROM game_items WHERE user_id=$user_win AND used=0 AND item_for_quest=".$Npc->npc['npc_quest_id']."");
					$npc_item = $Npc->npc['npc_quest_item'];
					$Item = new Item();
					$ar = $Item->add_user($npc_item,$user_win,1,$Npc->npc['npc_quest_id']);
					if ($ar[0]==1)
					{
						myquery("UPDATE game_items SET item_uselife=$proc WHERE id=".$ar[1]."");
					}
				}
			}
		}
		else   //УМЕР ИГРОК
		{
            if ($this->combat['combat_type']==10)
			//if (($this->combat['combat_type']==8)
			//OR($this->combat['combat_type']==9)
			//OR($this->combat['combat_type']==10)
			//OR($this->combat['combat_type']==11))
			{
				$this->map['not_lose']=1;
				$this->map['not_win']=1;
			}
			
			$this->break_items($user_id,$this->combat['npc']);//уменьшим текущий ресурс прочности предметов
			
			if ($this->against_npc($user_id))//если среди противников умершего есть только NPC
			{
				$this->check_quest_lose($user_id);
			}
			
            if ($this->combat['combat_type']<8 or $this->combat['combat_type']>11)
			//if (($this->combat['combat_type']!=8)
			//AND($this->combat['combat_type']!=9)
			//AND($this->combat['combat_type']!=10)
			//AND($this->combat['combat_type']!=11)) 
            {
                $this->user_teleport($user_id);
            }                
			
			//отразим смерть и на ездовом животном!
			$check = myquery("SELECT id,golod FROM game_users_horses WHERE used=1 AND user_id=$user_id");
			if (mysql_num_rows($check)>0)
			{
				list($id_horse,$golod) = mysql_fetch_array($check);
				$r = mt_rand(1,5);
				$k = 0;
				switch ($golod)
				{
					case 0: $k = 0; break;
					case 1: $k = 1; break;
					case 2: $k = 2; break;
					case 3: $k = 3; break;
					case 4: $k = 4; break;
					default: $k = 10; break;
				} 
				$add = $r*$k;
				myquery("UPDATE game_users_horses SET life=GREATEST(0,life-$add) WHERE id=$id_horse");
			}
			
			//переводим накопленный опыт в очки опыта			
			$this->nachisl_exp_gp($user_id,2,$user_win);
			
			if ($user_win > 0)
			{
				if ($this->all[$user_win]['npc']==0)
				{
					if ($this->map['not_lose']==0) $this->all[$user_id]['lose']++;
					save_stat($user_id,'','',6,'','',$user_win,0,$this->all[$user_win]['clan_id'],0,$this->all[$user_id]['clevel'],$this->all[$user_win]['clevel']);
				}			
				if ($this->map['not_win']==0) $this->all[$user_win]['win']++;
			}
			
			combat_setFunc($user_id,8,$this->combat['combat_id'],$this->combat['hod']);//установим состояние игрока в бою
			$this->clear_user($user_id);//очистим записи боя от этого игрока
		}
	}

	private function formula_exp($kogo,$kto,$dam_hp, $type=1)
	{ 
		$damage = (int)$dam_hp;
		if ($damage == -1)
		{
			$damage = 0.1*$kogo['HP_MAX'];
		}
		else
		{
			$damage = min($damage,$kogo['HP_start']);
		}
		$exp = max(0,round(($damage/2*5+max(0,($damage/4*($kogo['clevel']-$kto['clevel']))) )*$this->k_exp * $this->get_koeff_from_sklon($kto['sklon'],$kogo['sklon'],$kto['clan_id'])*$kto['k_exp']/100,2));
		if ($this->combat['turnir_type']!=0 AND $kto['clevel']>1)
		{
			$exp=$exp*0.7;
		}
        else
        {
            $exp=$exp*$this->combat['extra'];
        }
		if ($type == 2)
		{
			$exp = round ($exp / 2);
		}
		return $exp;
	}
	
	private function formula_gp($kogo,$kto,$dam_hp,$type=1)
	{
		$damage = (int)$dam_hp;
		if ($damage == -1)
		{
			$damage = 0.1*$kogo['HP_MAX'];
		}
		else
		{
			$damage = min($damage,$kogo['HP_start']);
		}
		//$k = $damage/$kogo['HP_MAX'];		
		//$gp = round($k*(11 + $kogo['clevel']-$kto['clevel'])*3.5 * $this->k_gp * $this->get_koeff_from_sklon($kto['sklon'],$kogo['sklon'],$kto['clan_id'])*$kto['k_gp']/100,2);
		$gp = round(($damage*(11+max(-10,$kogo['clevel']-$kto['clevel']))/131)*$this->k_gp * $this->get_koeff_from_sklon($kto['sklon'],$kogo['sklon'],$kto['clan_id'])*$kto['k_gp']/100,2);
		if ($this->combat['turnir_type']!=0)
		{
			$gp=$gp/10;
		}
		if ($type == 2)
		{
			$gp = round($gp / 2);
		}
		return $gp;
	}
	
	private function nachisl_exp_gp($user_id,$par,$user_win=0)
	{
		//Вначале дадим умирающему полный набранный опыт за проведённый бой
		$check_dead_exp = myquery("SELECT SUM(exp) as dead_exp FROM combat_users_exp WHERE combat_id=".$this->combat['combat_id']." AND exp>0 AND user_id='".$user_id."' AND prot_id<>'".$user_id."' HAVING dead_exp>0");
		if (mysql_num_rows($check_dead_exp)>0)
		{
			list($dead_exp)=mysql_fetch_array($check_dead_exp);
			save_exp($user_id, $dead_exp, 17);
		}
		
		//проверим, а не очередная ли это дуэль между одними участниками боя	
		$koef = $this->get_combat_koef();		
		
		//теперь дадим опыт за умершего всем кто его бил и еще жив на данный момент
		$sel = myquery("SELECT * FROM combat_users_exp WHERE combat_id=".$this->combat['combat_id']." AND prot_id=$user_id AND (exp>0 OR gp>0)");
		while ($userwin = mysql_fetch_array($sel))
		{
			//Опыт тем, кто атаковал умершего
			if ($userwin['user_id']<>$userwin['prot_id'])
			{				
				if ($koef < 1)
				{
					$userwin['exp']=max(1,$userwin['exp']*$koef);
					$userwin['gp']=max(1,$userwin['gp']*$koef);
					// if ($koef<0.5) myquery("UPDATE game_users SET win=win-1 WHERE user_id=".$user_win." ");
				}
				
				if ($this->all[$userwin['user_id']]['HP']<=0) continue;
				if ($this->all[$userwin['user_id']]['npc']==1) continue;
				if ($this->all[$userwin['user_id']]['side']==$this->all[$user_id]['side']) continue;
				
				$last_userwin_id = $userwin['user_id'];
				
				$mes = '';
				if ($userwin['exp']>0 or $userwin['gp']>0)
				{
					//для 3го типа квестов движка
					myquery("UPDATE quest_engine_users SET par2_value=par2_value+".$userwin['exp']." WHERE user_id=".$userwin['user_id']." AND quest_type=3");
					
					$mes.='<font color="#0080C0" size="2" face="Verdana">&nbsp;&nbsp;'.$this->all[$user_id]['name'].'';
					if ($par==1)
					{
						if ($this->all[$user_id]['pol']=='female')
						{
							$mes.=' сбежала с поля боя.';
							$this->log[$userwin['user_id']][]['action'] = 53; 
							$index = sizeof($this->log[$userwin['user_id']])-1;
							$this->log[$userwin['user_id']][$index]['na_kogo'] = $user_id;
							$this->log[$userwin['user_id']][$index]['na_kogo_name'] = $this->all[$user_id]['name'];
						}
						else 
						{
							$mes.=' сбежал с поля боя.';
							$this->log[$userwin['user_id']][]['action'] = 54; 
							$index = sizeof($this->log[$userwin['user_id']])-1;
							$this->log[$userwin['user_id']][$index]['na_kogo'] = $user_id;
							$this->log[$userwin['user_id']][$index]['na_kogo_name'] = $this->all[$user_id]['name'];
						}
						//при вылете по таймауту не вызывается calculate, поэтому обновляем БД здесь
						myquery("UPDATE game_users SET EXP=EXP+".$userwin['exp'].",GP=GP+".$userwin['gp']." WHERE user_id=".$last_userwin_id."");
					}
					elseif ($par==2)
					{
						if ($last_userwin_id==$user_win and $this->map['not_win']==0)
						{
							//запишем статистику
							if (function_exists("save_stat"))
							{
								save_stat($user_win,'','',7,'','',$user_id,$userwin['gp'],$this->all[$user_win]['clan_id'],$userwin['exp'],$this->all[$user_id]['clevel'],$this->all[$user_win]['clevel']);
							}
						}
						
						if ($user_win==$userwin['user_id'])
						{
							$mes = '<font color=\"#eeeeee\">Ты '.echo_sex('победил','победила',$this->all[$user_win]['pol']).' игрока <b>'.$this->all[$user_id]['name'].'</b> и он';
							if ($this->all[$user_id]['pol']=='female')
								$mes.='а бежала';
							else
								$mes.=' бежал';
							$mes.=' в неизвестном направлении!</font> ';
						 }
						else
						{
							$mes = '<font color=\"#eeeeee\">Игрок <b>'.$this->all[$user_id]['name'].'</b> ';
							if ($this->all[$user_id]['pol']=='female')
								$mes.='была побеждена и бежала';
							else
								$mes.='был побежден и бежал';
							$mes.=' в неизвестном направлении!</font> ';
						}

						//и сообщим об этом в логах
						if ($this->all[$user_id]['pol']=='female') 
						{
							$this->log[$userwin['user_id']][]['action'] = 50; 
							$index = sizeof($this->log[$userwin['user_id']])-1;
							$this->log[$userwin['user_id']][$index]['na_kogo'] = $user_id;
							$this->log[$userwin['user_id']][$index]['na_kogo_name'] = $this->all[$user_id]['name'];
						}
						else 
						{
							$this->log[$userwin['user_id']][]['action'] = 51; 
							$index = sizeof($this->log[$userwin['user_id']])-1;
							$this->log[$userwin['user_id']][$index]['na_kogo'] = $user_id;
							$this->log[$userwin['user_id']][$index]['na_kogo_name'] = $this->all[$user_id]['name'];
						}
						$this->all[$userwin['user_id']]['exp']+=$userwin['exp'];	
						$this->all[$userwin['user_id']]['gp']+=$userwin['gp'];
					}
					
					$this->log[$userwin['user_id']][]['action'] = 52; 
					$index = sizeof($this->log[$userwin['user_id']])-1;
					$this->log[$userwin['user_id']][$index]['add_hp'] = $userwin['exp'];
					$this->log[$userwin['user_id']][$index]['procent'] = $userwin['gp'];
					
					setGP($userwin['user_id'],$userwin['gp'],25);
					setEXP($userwin['user_id'],$userwin['exp'],2);
					$da = getdate();
					$userwin_clan = $this->all[$userwin['user_id']]['clan_id'];
					$user_exp_store = $userwin['exp'];
					$npc_exp_store = 0;
					myquery("INSERT INTO game_combats_exp (clan_id,year,month,npc_exp,user_exp) VALUES ($userwin_clan,".$da['year'].",".$da['mon'].",$npc_exp_store,$user_exp_store) ON DUPLICATE KEY UPDATE npc_exp=npc_exp+$npc_exp_store,user_exp=user_exp+$user_exp_store");

					//и сообщим об этом в логах					
					if ($userwin['exp']>0 or $userwin['gp']>0)
					{
						$mes.='    Ты получаешь ';
						$mes.='<b><font color="#FF0000">'.$userwin['exp'].'</font></b> очков опыта и <b><font color="#FF0000">'.$userwin['gp'].'</font></b> монет';
						$mes.='</font><br>';
						$result = myquery("INSERT game_battles SET attacker_id=".$userwin['user_id'].", target_id=0, map_name=".$this->combat['map_name'].", map_xpos=".$this->combat['map_xpos'].", map_ypos=".$this->combat['map_ypos'].", contents='".mysql_real_escape_string($mes)."', post_time=".time()."");
					}
				}
			}
			else
			{
				$this->nachisl_exp_gp_def($user_id,$userwin['exp'],$userwin['gp'],$koef);
			}
		}
		myquery("DELETE FROM combat_users_exp WHERE combat_id=".$this->combat['combat_id']." AND prot_id=$user_id");
	}
	
	private function nachisl_exp_gp_def($user_id,$exp=0,$gp=0,$k=0)
	{
		if ($exp==0 and $gp==0)
		{
			$check=myquery("SELECT exp,gp FROM combat_users_exp WHERE combat_id=".$this->combat['combat_id']." AND user_id = $user_id AND prot_id=$user_id AND (exp>0 OR gp>0)");
			if (mysql_num_rows($check)>0)
			{
				list($exp,$gp)=mysql_fetch_array($check);	
				//Проверим - были ли такие бои уже
				$k = $this->get_combat_koef();
			}			
		}		
		
		if ($exp > 0 or $gp > 0)
		{
			$exp = max(1,$exp*$k);
			$gp = max(1,$gp*$k);
			save_gp($user_id,$gp,109);
			save_exp($user_id,$exp,18);
			$mes='    За лечебные/защитные действия ты получаешь ';
			$mes.='<b><font color="#FF0000">'.$exp.'</font></b> очков опыта и <b><font color="#FF0000">'.$gp.'</font></b> монет';
			$mes.='</font><br>';
		}
	}
	
	private function get_combat_koef () 
	{
		$koef = 1;
		if ((($this->combat['combat_type']>=1 and $this->combat['combat_type']<=3) or $this->combat['combat_type']==5) and  $this->combat['npc']==0)
		{
			list($kol)=mysql_fetch_array(myquery("Select count(1) from combat_users where combat_id=".$this->combat['combat_id'].""));
			$test=myquery("Select game_combats_users.boy, count(game_combats_users.user_id) as tt
						   From game_combats_users 
						   Join game_combats_log on game_combats_log.boy=game_combats_users.boy
						   Where time>(UNIX_TIMESTAMP()-24*60*60) and type in (1,2,3,5)
						   Group By game_combats_users.boy
						   Having tt=$kol");
			if (mysql_num_rows($test)>0)
			{
				$kol_combats=0;
				while ($combat_id=mysql_fetch_array($test))
				{
					$f=1;
					$test2=myquery("Select user_id From game_combats_users Where boy=".$combat_id['boy']."");
					while ($f==1 and $us_id=mysql_fetch_array($test2))
					{
						$test3=myquery("Select * From combat_users where combat_id=".$this->combat['combat_id']." and user_id=".$us_id['user_id']."");
						if (mysql_num_rows($test3)==0) $f=0;
					}
					if ($f==1) $kol_combats++;
				}
				if ($kol_combats>2)
				{
					$koef=max(0,1-($kol_combats-2)*0.2);					
				}
			}
		}
		return $koef;
	}
	
	private function check_quest_lose($user_id)
	{
		//обработаем квесты
		$sel000 = myquery("SELECT * FROM game_quest_users WHERE quest_id=1 AND user_id=$user_id");
		if (mysql_num_rows($sel000)>0)
		{
			myquery("UPDATE game_quest_users SET sost=0,last_time=".time()." WHERE quest_id=1 AND user_id=$user_id");
		}
		$sel000 = myquery("SELECT * FROM game_quest_users WHERE (quest_id>=2 or quest_id<=7) AND user_id=$user_id");
		if (mysql_num_rows($sel000)>0)
		{
			foreach($this->prot AS $key=>$value)
			{
				myquery("UPDATE game_quest_users SET sost=-1 WHERE sost=".$key." AND user_id=$user_id");
			}
		}
	}
	
	public function user_out($user_id) //отработка вылета игрока из боя по таймауту, одновременно отрабатываем действия над его противниками
	{
		$this->break_items($user_id,0);//уменьшим текущий ресурс прочности предметов
		$this->user_teleport($user_id);
		$travma = 0;
		if ($this->combat['npc']==0)//если среди противников есть не только NPC
		{		
			if ($this->all[$user_id]['clevel'] >=15 )
			{
				$travma = 1;
			}
			myquery("UPDATE game_users_data SET last_timeout=".time().",last_timeout_boy=".$this->combat['combat_id']." WHERE user_id=$user_id");
			
			//!!!Отключено!!!
			//если среди противников только 1 живой игрок - тогда победу дадим ему, если несколько, то победу даем рандомно
			// $prot = array();
			// foreach($this->all AS $key=>$value)
			// {
				// if ($value['side']!=$this->all[$user_id]['side'])
				// {
					// $prot[] = $key;
				// }
			// }
			// $user_win_id = 0;
			// if (count($prot)==1)
			// {
				// $user_win_id = $prot[0];
			// }
			// else
			// {
				// $r = mt_rand(0,count($prot)-1);
				// $user_win_id = $prot[$r];                
			// }
			// if ($user_win_id>0)
			// {
				// if ($this->all[$user_win_id]['npc']==0)
				// {
					// myquery("UPDATE game_users SET WIN=WIN+1 WHERE user_id=$user_win_id");
				// }
				// else
				// {
					// myquery("UPDATE game_npc SET WIN=WIN+1 WHERE id=$user_win_id");
				// }
			// }
		}
		else
		{
			//таймаут в бою с ботами
			$this->check_quest_lose($user_id);
		}
		
		//статистика
		if (function_exists("save_stat"))
		{
			save_stat($user_id,'','',3,'','','','','','','','');
		}
		
		//за вылетевшего по тайму дадим опыта = опыт за полное убийство/количество противников, но не более 10% и если вылет идет на первом ходу
		$all = array();
		foreach($this->all AS $key=>$value)
		{
			if ($this->all[$key]['side']==$this->all[$user_id]['side']) continue;
			if ($this->all[$key]['npc']==0)
			{
				if ($this->all[$key]['HP']>0)
				{
					$all[]=$key;					
				}
			}
		}
		if (count($all)>0)
		{
			$k = min(0.1,1/count($all));
		}
		else
		{
			$k = 0;
		}
		$gp = 0;
		foreach($all AS $key=>$value)
		{
			$exp = $this->formula_exp($this->all[$user_id],$this->all[$value],-1);			
			
			if ($exp!=0 OR $gp!=0) myquery("INSERT INTO combat_users_exp (combat_id,user_id,prot_id,exp,gp) VALUES (".$this->combat['combat_id'].",".$value.",".$user_id.",$exp,$gp) ON DUPLICATE KEY UPDATE exp=exp+$exp,gp=gp+$gp");
		}
		
		//переводим накопленный опыт в очки опыта
		$this->nachisl_exp_gp($user_id,1);
		
		$query = "UPDATE game_users SET HP=1,MP=1,STM=1";
		
		//$travma = 0;//Временно убираем травмы!
		if ($travma == 1)
		{
			$query.=",injury=LEAST(1300,injury+13)";
		}
		if ($this->combat['npc']==0)
		{
			$query.=",LOSE=LOSE+1";
		}
		$query.=" WHERE user_id=$user_id";
		myquery($query);
		
		combat_setFunc($user_id,8,$this->combat['combat_id'],$this->combat['hod']);
		$this->clear_user($user_id);
		check_boy($this->combat['combat_id']);
		
		//запишем в лог хода
		if ($this->all[$user_id]['pol']=='female')
		{
			$log_id = 53;
			$mes = "Ты сбежала с поля боя";  
		}
		else                         
		{
			$log_id = 54;
			$mes = "Ты сбежал с поля боя";  
		}
		$text_id = 0;
		$sel_text_id = myquery("SELECT id FROM game_combats_log_text WHERE name='".$this->all[$user_id]['name']."' AND mode='".$user_id."'");
		if (mysql_num_rows($sel_text_id)==0)
		{
			myquery("INSERT INTO game_combats_log_text (combat_id, name,mode) VALUES ('".$this->combat['combat_id']."', '".$this->all[$user_id]['name']."', ".$user_id.")");  
			$text_id = mysql_insert_id();
		}
		else
		{
			list($text_id) = mysql_fetch_array($sel_text_id);
		}
		myquery("INSERT INTO game_combats_log_data (boy,user_id,hod,action,na_kogo) VALUES (".$this->combat['combat_id'].", 0, ".$this->combat['hod'].", ".$log_id.", ".$text_id.")");
		
		myquery("INSERT game_battles SET attacker_id=".$user_id.", target_id=0, map_name=".$this->combat['map_name'].", map_xpos=".$this->combat['map_xpos'].", map_ypos=".$this->combat['map_ypos'].", contents='".mysql_real_escape_string($mes)."', post_time=".time()."");
	}
	
	private function check_end() //проверка окончания боя по условию победы одной из сторон
	{
		$flag = -1;
		$sel_side = myquery("SELECT DISTINCT side FROM combat_users WHERE combat_id=".$this->combat['combat_id']."");
		if (mysql_num_rows($sel_side)==1)
		{
			//бой окончен, победила одна из сторон
			list($flag) = mysql_fetch_array($sel_side);
		}
		return $flag;
	}
	
	private function calculate_exp_gp ($kto, $kogo, $damage_hp) //Расчёт опыта и денег за удар
	{
		$exp = 0;
		$gp = 0;
		if ($this->all[$kto]['side']!=$this->all[$kogo]['side'] AND $this->all[$kto]['npc']==0)
		{
			//подсчитаем опыт и золото за нанесенный урон
			$dam_hp = min($damage_hp,$this->all[$kogo]['HP']);			
			if ($this->all[$kogo]['npc']==0)
			{
				$gp = $this->formula_gp($this->all[$kogo],$this->all[$kto],$dam_hp);
				$exp = $this->formula_exp($this->all[$kogo],$this->all[$kto],$dam_hp);
			}
			else
			{           
				$k = $dam_hp/$this->all[$kogo]['HP_MAX'];
				$Npc = new Npc($kogo);
				$gp = round($k*$Npc->templ['npc_gold']*$this->all[$kto]['k_gp']/100,2);
				$exp = round($k*$Npc->npc['EXP']*$this->all[$kto]['k_exp']/100);	
				//$exp = round($k*$Npc->npc['EXP']/100*$this->getProcentExpNpc($kto));  Отмена урезки опыта при большом уровне игрока				
			}
			$exp=max(0,$exp);
			$gp=max(0,$gp);
			$userban=myquery("select count(*) from game_ban where user_id=$kto and type=2 and time>".time()."");
			if (mysql_result($userban,0,0)>0)
			{
				$exp = round($exp/5);
				$gp = round($gp/5,2);
			}
		}

		if ($exp>0 or $gp>0)
		{					
			myquery("INSERT INTO combat_users_exp (combat_id,user_id,prot_id,exp,gp) VALUES (".$this->combat['combat_id'].",$kto,$kogo,$exp,$gp) ON DUPLICATE KEY UPDATE exp=exp+$exp,gp=gp+$gp");
		}
	}
	
	private function calculate_exp_gp_def ($kto, $kogo, $damage_hp) //Расчёт опыта и денег за лечение/защиту
	{
		$exp = 0;
		$gp = 0;
		if ($this->combat['npc']==0 and $this->all[$kto]['npc']==0)
		{			
			$dam_hp = min($damage_hp,$this->all[$kogo]['HP']);
			
			$gp = $this->formula_gp($this->all[$kogo],$this->all[$kto],$dam_hp, 2);
			$exp = $this->formula_exp($this->all[$kogo],$this->all[$kto],$dam_hp, 2);
			
			$exp=max(0,$exp);
			$gp=max(0,$gp);
			
			$userban=myquery("select count(*) from game_ban where user_id=$kto and type=2 and time>".time()."");
			if (mysql_result($userban,0,0)>0)
			{
				$exp = round($exp/5);
				$gp = round($gp/5,2);
			}
		}

		if ($exp>0 or $gp>0)
		{					
			myquery("INSERT INTO combat_users_exp (combat_id,user_id,prot_id,exp,gp) VALUES (".$this->combat['combat_id'].",$kto,$kto,$exp,$gp) ON DUPLICATE KEY UPDATE exp=exp+$exp,gp=gp+$gp");
		}
	}
	
	private function kill_user($kto, $kogo) //Отработка смерти участника боя
	{
		if ($this->all[$kogo]['npc']==1)
		{
			$Npc = new Npc($kogo);
			//АЙ-АЙ-ЯЙ... УБИЛИ БОТА....НИ ЗА ЧТО ЗАМОЧИЛИ....
			if ($Npc->templ['npc_id']==npc_id_nechto)
			{
				$mas = array();
				foreach ($this->all as $key => $value)
				{
					if ($this->all[$key]['npc']==0)
					{
						for ($j=$this->all[$key]['hod_start']; $j<=$this->combat['hod']; $j++)
						{
							$mas[]=$key;
							if ($key==$kto)
							{
								$mas[]=$key;
							}
						}
					}
				}
				if (sizeof($mas)>0)
				{
					$user_loot = $mas[mt_rand(0,sizeof($mas)-1)];
				}
				else
				{
					$user_loot = $kto;
				}
				$Npc->drop_loot($user_loot);
				$say = 'В Средиземье ('.$this->combat['map_xpos'].','.$this->combat['map_ypos'].') было повержено [b]НЕЧТО[/b]. Имя героя, одолевшего этого монстра - [color=yellow][b]'.$this->all[$kto]['name'].'![/b][/color]. Слава герою!!!';
				$say = iconv("Windows-1251","UTF-8//IGNORE","<span style=\"font-style:italic;font-size:12px;color:gold;font-family:Verdana,Tahoma,Arial,Helvetica,sans-serif\">".$say."</b></span>");
				myquery("INSERT INTO game_log (`message`,`date`,`fromm`) VALUES ('".mysql_real_escape_string($say)."',".time().",-1)");
			}
			else
			{
				$Npc->drop_loot($kto);
				$Npc->check_hunter($kto);
				$Npc->teleport($kto);
			}
		}
		//ВСЕ. КТО-ТО СЕЙЧАС СТАЛ ТРУПОМ.
		$this->user_dead($kogo,$kto);
		if ($this->all[$kto]['pol']=='female')
		{
			$this->log[$kto][]['action'] = 48;
		}
		else
		{
			$this->log[$kto][]['action'] = 49;
		}
		$index = sizeof($this->log[$kto])-1;
		$this->log[$kto][$index]['na_kogo'] = $kogo;
		$this->log[$kto][$index]['na_kogo_name'] = $this->all[$kogo]['name'];
		$this->log[$kto][$index]['name'] = $this->all[$kto]['name'];
		$this->log[$kto][$index]['mode'] = $kto;
		if ($this->all[$kto]['npc']==1 AND $this->all[$kogo]['npc']==0)
		{
			//бот убил игрока
			if (function_exists("save_stat"))
			{
				save_stat($kogo,$kto,'',2,'','','','','','','',''); 
			}
		}
	}
	
	private function user_teleport($user_id)  //перемещение проигравших игроков к рандомному городу
	{
        $not_maze_array = array(691,692,804,id_map_tuman);
		if ($this->map['maze']==1 AND !in_array($this->combat['map_name'],$not_maze_array))
		{
			//В лабиринте (но не в Подземельях Мории) при смерти от бота выкидываем в начало лабиринта
			$map_now=$this->map['id'];
			$xrandmap = 0;
			$yrandmap = 0;
		}
		else
		{
			if ($this->map['arena']==1 OR in_array($this->combat['map_name'],$not_maze_array))
				//Из Арены Смерти и Подземелий Мории и Туманных Гор выкидываем в Средиземье
				$map_now=@mysql_result(@myquery("SELECT id FROM game_maps WHERE name LIKE 'Средиземье'"),0,0);
			else
				$map_now=@mysql_result(@myquery("SELECT map_name FROM game_users_map WHERE user_id=$user_id"),0,0);

			$sel = myquery("SELECT game_map.town AS town,game_map.name AS map_name, game_map.xpos AS xpos, game_map.ypos AS ypos FROM game_map JOIN game_gorod ON game_map.town=game_gorod.town WHERE game_gorod.rustown<>'' AND game_map.name=$map_now AND game_map.to_map_name='' and game_gorod.clan=0");
			if ($sel==false OR !mysql_num_rows($sel))
			{
				$battle_map_query = myquery("SELECT xpos,ypos,name FROM game_map where name='$map_now' ORDER BY xpos DESC, ypos DESC LIMIT 1");
				$battle_map_result = mysql_fetch_array($battle_map_query, MYSQL_ASSOC);
				$xrandmap = mt_rand(0, $battle_map_result['xpos']);
				$yrandmap = mt_rand(0, $battle_map_result['ypos']);
			}
			else
			{
				$all = mysql_num_rows($sel);
				$r = mt_rand(0,$all-1);
				mysql_data_seek($sel,$r);
				$town=mysql_fetch_assoc($sel);
				$xrandmap = $town['xpos'];
				$yrandmap = $town['ypos'];
			}
			if (strstr($this->map['name'],"Подземелья")!=false)
			{
				myquery("UPDATE dungeon_users_data SET last_visit=".time()." WHERE user_id=$user_id");
			}
		}
		myquery("update game_users_map set map_name=$map_now,map_xpos=$xrandmap,map_ypos=$yrandmap where user_id=$user_id");
	}
	
	private function getProcentExpNpc($kto)
	{
		if ($this->all[$kto]['npc']==0)
		{
			list($currLevel,$currExp) = mysql_fetch_array(myquery("SELECT clevel,EXP FROM view_active_users WHERE user_id=$kto"));
			/*
			$allExp=$currExp;
			for($i=0;$i<=$currLevel-1;$i++)
			{
				if ($i == 0)
				{
					$exp = 200;
				}
				else
				{
					$exp = $i*($i+1)*200;
				}
				$allExp+=$exp;
			}
			$newLevel = floor((1+sqrt(($allExp+50)/50))/2);
			*/
            
            
			$newLevel = $currLevel;
			if ($newLevel<25)
			{
				$procent = 100;   
			}
			else
			{
				$procent = 100-3*($newLevel-24);
				$procent = max(10,min(100,$procent));
			}
            
            //$procent = 100;
			return $procent;
		}
		else
		{
			return 100;
		}
	}
}
?>