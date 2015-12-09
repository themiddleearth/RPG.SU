<?
//Функция calculate() из class_combat.php		
		
		$da = getdate();
		$day = $da['mday'];
		$month = $da['mon'];

		$this->k_exp = 1;
		$this->k_gp = 1;
		if ($month==12 AND $day>=30) {$this->k_exp=1.5;};
		if ($month==1 AND $day<=5) {$this->k_exp=1.5;};
		if ($month==12 AND $day==31) {$this->k_exp=2.0;};
		if ($month==1 AND $day==1) {$this->k_exp=2.0;};

		if ($this->combat['combat_type']!=4) $this->k_exp=$this->k_exp/3;
		$prudence_effect = 2; // Эффект навыка "Расчётливость"
		
		// Запишем пропуск хода всем, кто не сходил в этом ходу
		myquery("INSERT INTO combat_actions (combat_id,hod,user_id,action_type,action_chem,action_kogo,action_kuda,action_proc,action_priem,action_rand,action_type_sort,position)
		         SELECT ".$this->combat['combat_id'].", ".$this->combat['hod'].", user_id, 92,1,1,1,100,1, ".mt_rand(0,999999999).", 10, 1
				 FROM combat_users_state 
				 WHERE state = 5 AND combat_id=".$this->combat['combat_id']."
		        ");			
				
		$sel_bron = myquery("SELECT game_items.user_id,game_items.used,game_items_factsheet.def_type,game_items_factsheet.def_index  FROM game_items,game_items_factsheet,combat_users WHERE game_items.priznak=0 AND combat_users.join=0 AND game_items.user_id=combat_users.user_id AND combat_users.combat_id=".$this->combat['combat_id']." AND game_items.item_id=game_items_factsheet.id");
		while ($bron = mysql_fetch_array($sel_bron))
		{
			$this->all[$bron['user_id']]['bron'][$bron['used']]['def_type'] = $bron['def_type'];    
			$this->all[$bron['user_id']]['bron'][$bron['used']]['def_index'] = $bron['def_index'];    
		} 
		
		if (isset($this->char))
		{
			$this->log[$this->char['user_id']][0]['action'] = 1;
		} 
		
		$npc_count = 0;
		
		foreach ($this->all as $key=>$value)
		{			
			$this->all[$key]['defense']['HP']['all']                 =0;
			$this->all[$key]['defense']['HP']['golova']              =0;
			$this->all[$key]['defense']['HP']['telo']                =0;
			$this->all[$key]['defense']['HP']['pah']                 =0;
			$this->all[$key]['defense']['HP']['plecho']              =0;
			$this->all[$key]['defense']['HP']['nogi']                =0;
			$this->all[$key]['defense']['MP']                        =0;
			$this->all[$key]['defense']['STM']                       =0;
			$this->all[$key]['defense_all']['HP']['all']             =0;
			$this->all[$key]['defense_all']['HP']['golova']          =0;
			$this->all[$key]['defense_all']['HP']['telo']            =0;
			$this->all[$key]['defense_all']['HP']['pah']             =0;
			$this->all[$key]['defense_all']['HP']['plecho']          =0;
			$this->all[$key]['defense_all']['HP']['nogi']            =0;
			$this->all[$key]['defense_all']['MP']                    =0;
			$this->all[$key]['defense_all']['STM']                   =0;
			$this->all[$key]['proc']                                 =0;
            $this->all[$key]['svit_usil']                            =1;
            $this->all[$key]['svit_sopr']                            =0;
			$this->all[$key]['miss']                                 =0;
            $check_svit_sopr = myquery("SELECT action_chem FROM combat_actions WHERE hod=".$this->combat['hod']." AND combat_id=".$this->combat['combat_id']." AND user_id=".$key." AND action_type=17 LIMIT 1");
            if (mysql_num_rows($check_svit_sopr)>0)
            {
                list($item_svitok) = mysql_fetch_array($check_svit_sopr);
                if ($item_svitok == item_id_svitok_light_sopr)
                {
                    $this->all[$key]['svit_sopr'] = 25;
                }
                if ($item_svitok == item_id_svitok_medium_sopr)
                {
                    $this->all[$key]['svit_sopr'] = 50;
                }
                if ($item_svitok == item_id_svitok_hard_sopr)
                {
                    $this->all[$key]['svit_sopr'] = 75;
                }
                if ($item_svitok == item_id_svitok_absolut_sopr)
                {
                    $this->all[$key]['svit_sopr'] = 100;
                }
            }
            $this->all[$key]['position']=mysqlresult(myquery("SELECT position FROM combat_actions WHERE hod=".$this->combat['hod']." AND combat_id=".$this->combat['combat_id']." AND user_id=".$key." LIMIT 1"));
            $this->log[$key][0]['action'] = 77+$this->all[$key]['position'];
			
			if ($this->all[$key]['npc']==1)
			{            
                // Составим список ботов, дополнительные особенности которых надо извлечь
				$npc_count++;
				if ($npc_count == 1)
				{
					$npc_id_list = $key;
				}
				else
				{
					$npc_id_list.= ', '.$key;
				}
				
				// Установим случайную позицию
				$this->all[$key]['position'] = mt_rand(1,3);
                $this->log[$key][0]['action'] = 77+$this->all[$key]['position'];
				$this->make_hod_npc($key);
			}
			
			//Обработаем кровотечение
			if ($this->all[$key]['injury']>0)
			{
				$krov = ceil($this->all[$key]['injury']*$this->all[$key]['HP_MAX']/100);
				$this->all[$key]['HP'] = $this->all[$key]['HP'] - $krov;				
				if ($this->all[$key]['HP']<0) 
				{
					$this->log[$key][]['action'] = 90;					
					$this->user_dead($key, 0);
				}
				else
				{
					$this->log[$key][]['action'] = 89;	
					$index = sizeof($this->log[$key])-1;
					$this->log[$key][$index]['add_hp'] = $krov;
				}
			}
		}
		
		// Выгружаем 
		if ($npc_count > 0)
		{
			$check_options = myquery("SELECT DISTINCT gn.npc_id, gnso.opt_id, gnsov.number, gnsov.value
										FROM game_npc gn JOIN game_npc_set_option gnso ON gn.npc_id = gnso.npc_id
										LEFT JOIN game_npc_set_option_value gnsov ON gnso.id = gnsov.id
				     				   WHERE gn.id in (".$npc_id_list.") 
									   ORDER BY npc_id, opt_id, number");									  
			while ($options = mysql_fetch_array($check_options))
			{
				$npc_options[$options['npc_id']][$options['opt_id']][$options['number']]['value'] = $options['value'];				
			}
		}
		
		$sort_log = array();
		$sel_action = myquery("SELECT *  FROM combat_actions WHERE hod=".$this->combat['hod']." AND combat_id=".$this->combat['combat_id']." ORDER BY action_type_sort DESC, action_rand ASC");
		while ($act = mysql_fetch_array($sel_action))
		{
			$kto = $act['user_id'];
			$kogo = $act['action_kogo'];
			$sort_log[$kto] = $act['action_rand'];
			if ($this->all[$kto]['HP']<=0)
			{
				$this->log[$kto][0]['action'] = 4;
				continue;
			}
			if ($act['action_type'] == 91)
			{				
				$this->all[$kto]['HP'] = 1;
				$this->all[$kto]['MP'] = 1;
				$this->all[$kto]['STM'] = 1;
				$this->user_out($kto);	
				$this->log[$kto][0]['action'] = 83;										
				continue;
			}
			// Пропуск хода
			if ($act['action_type'] == 92)
			{				
				$this->log[$kto][0]['action'] = 82;					
				$this->all[$kto]['miss'] = 1;
				continue;
			}

			if (isset($this->all[$kogo]))
			{
				if ($this->all[$kogo]['HP']<=0)
				{
					$kogo_name = $this->all[$kogo]['name']; 
					if ($this->all[$kogo]['pol']=='female')
					{
						$this->log[$kto][]['action'] = 61;
					}
					else
					{
						$this->log[$kto][]['action'] = 6;
					}
					$index = sizeof($this->log[$kto])-1;
					$this->log[$kto][$index]['na_kogo'] = $kogo;
					$this->log[$kto][$index]['na_kogo_name'] = $kogo_name;
					continue;
				}
			}
			else
			{
				$kogo_name = get_user("name",$kogo,0); 
				$this->log[$kto][]['action'] = 6;
				$index = sizeof($this->log[$kto])-1;
				$this->log[$kto][$index]['na_kogo'] = $kogo;
				$this->log[$kto][$index]['na_kogo_name'] = $kogo_name;
				continue;
			}

			if ($this->all[$kto]['side']!=$this->all[$kogo]['side'])
			{
				if ($act['action_type']>=30 AND $act['action_type']<40)
				{
					$this->log[$kto][]['action'] = 57;
					continue;
				}
				elseif ($act['action_type']>=20 AND $act['action_type']<30)
				{
					$this->log[$kto][]['action'] = 58;
					continue;
				}
			}
			else
			{
				if ($act['action_type']>=10 AND $act['action_type']<20)
				{
					$this->log[$kto][]['action'] = 60;
					continue;
				}
			}

			$kuda = '';
			if ($act['action_type']>=20 AND $act['action_type']<30)
			{  
				if ($act['action_type']<>22)
				{
					switch ($act['action_kuda'])
					{
						case 1:
						$kuda='голову и плечо';
						break;
						case 2:
						$kuda='тело и пах';
						break;
						case 3:
						$kuda='пах и ноги';
						break;
					}
				}
			}

			if ($act['action_type']>=10 AND $act['action_type']<20)
			{ 
				if ($this->all[$kogo]['HP']<=0)
				{
					continue;
				}
				if ($this->all[$kto]['HP']<=0)
				{
					continue;
				}
				switch ($act['action_kuda'])
				{
					case 1:
					$kuda='голову';
					break;
					case 2:
					$kuda='тело';
					break;
					case 3:
					$kuda='пах';
					break;
					case 4:
					$kuda='плечо';
					break;
					case 5:
					$kuda='ноги';
					break;
				}
			} 

			$damage_hp=0;
			$damage_mp=0;
			$damage_stm=0;
			$protect_hp=0;

			if ($this->all[$kto]['npc']==1)
			{
				$Npc = new Npc($kto);   
			}			
            
            $k_svitok_usil = 1;
            $k_svitok_sopr = 1;
			
			switch($act['action_type'])
			{
				//БЛОК действий до начала боя
				case 41:
				{
					//Работа навыка "Палладин"
					if ($act['action_chem']>0)
					{
						$defense_hp=$act['action_chem'];						
						
						$this->all[$kto]['defense']['HP']['all']+=$defense_hp;
						$this->all[$kto]['defense']['HP']['golova']+=$defense_hp;
						$this->all[$kto]['defense']['HP']['plecho']+=$defense_hp;
						$this->all[$kto]['defense']['HP']['telo']+=$defense_hp;
						$this->all[$kto]['defense']['HP']['pah']+=$defense_hp;
						$this->all[$kto]['defense']['HP']['nogi']+=$defense_hp;					;
						$this->all[$kto]['defense_all']['HP']['all']+=$defense_hp;
						$this->all[$kto]['defense_all']['HP']['golova']+=$defense_hp;
						$this->all[$kto]['defense_all']['HP']['plecho']+=$defense_hp;
						$this->all[$kto]['defense_all']['HP']['telo']+=$defense_hp;
						$this->all[$kto]['defense_all']['HP']['pah']+=$defense_hp;
						$this->all[$kto]['defense_all']['HP']['nogi']+=$defense_hp;
						
						$this->log[$kto][]['action'] = 87;
						$index = sizeof($this->log[$kto])-1;												
						$this->log[$kto][$index]['add_hp'] = $defense_hp;
					}
				}
				break;
				
				case 42:
				{
					//Работа навыка "Убийца"
					if ($act['action_chem']>0)
					{
						$damage_hp=min($this->all[$kogo]['HP']-1,$act['action_chem']);		
						
						$this->log[$kto][]['action'] = 88;
						$index = sizeof($this->log[$kto])-1;
						$this->log[$kto][$index]['na_kogo'] = $kogo;
						$this->log[$kto][$index]['na_kogo_name'] = $this->all[$kogo]['name'];											 
						$this->log[$kto][$index]['add_hp'] = $damage_hp;
					}					
				}
				break;				
				
				//БЛОК ЛЕЧЕНИЯ
				case 31:
				{					
					//лечение магическим заклинанием
					$select=myquery("select *  FROM game_spells WHERE id=".$act['action_chem']." AND type=2");
					if (mysql_num_rows($select))
					{
						$lech=mysql_fetch_array($select);
						$minus_mp=0;
						$minus_hp=0;
						$minus_stm=0;
						$minus_mp = ceil($act['action_proc']/100*$lech['mana']);
						if ($this->all[$kto]['MS_PRUDENCE']>0)
						{
							$rand=mt_rand(1,100);
							if ($rand<=75+$this->all[$kto]['MS_PRUDENCE'])
							{
								$minus_mp=ceil($minus_mp*(100-$this->all[$kto]['MS_PRUDENCE']*$prudence_effect)/100);
							}
						}
						if ($this->all[$kto]['MP']>=$minus_mp)
						{
							$lech_stm = 0;
							$lech_hp = 0;
							$lech_mp = 0;
							$lech_hp=floor(mt_rand($lech['effect']-$lech['rand']+$this->all[$kto]['NTL'],$lech['effect']+$lech['rand']+$this->all[$kto]['NTL'])*$act['action_proc']/100*$this->decrease);
							$lech_hp=$this->calc_position($lech_hp,$kto,$kogo);
							
							//проверим промах для мага
							$random = mt_rand(1,100);
							$level=$lech['level'];
							$promah=0;
							if ($this->all[$kto]['clevel'] == 0) { $this->all[$kto]['clevel'] = 80; }
							switch ($this->all[$kto]['class_type'])
							{
								case 11:
								{
									$check = 100;
								}
								break;
								case 12:
								{
									$check = 15 + $level*2 + min($this->all[$kto]['SPD']*2,50) + floor(80/$this->all[$kto]['clevel']);
								}
								break;
								case 13:
								{
									$check = 15 + $level*2 + min($this->all[$kto]['SPD']*2,50) + floor(80/$this->all[$kto]['clevel']);
								}
								break;
								default:
								{
									$check = $level*2 + min($this->all[$kto]['SPD']*2,50) + floor(80/$this->all[$kto]['clevel']);
								}
								break;
							}
							
							if ($random>$check OR $random<=5-$this->all[$kto]['lucky'])
							{
								$lech_hp=0;
								$lech_mp=0;
								$lech_stm=0;
								$minus_hp=0;
								$minus_mp=0;
								$minus_stm=0;
								$promah=1;
							}

							if ($promah==1)
							{
								$this->log[$kto][]['action'] = 37;
								$index = sizeof($this->log[$kto])-1;
								$this->log[$kto][$index]['name'] = $lech['name'];								
							}
							else
							{								
								$this->log[$kto][]['action'] = 7;
								$index = sizeof($this->log[$kto])-1;
								$this->log[$kto][$index]['na_kogo'] = $kogo;
								$this->log[$kto][$index]['na_kogo_name'] = $this->all[$kogo]['name'];
								$this->log[$kto][$index]['name'] = $lech['name'];								
								$this->log[$kto][$index]['procent'] = $act['action_proc'];
								$this->log[$kto][$index]['add_hp'] = $lech_hp;
								$this->log[$kto][$index]['add_mp'] = $lech_mp;
								$this->log[$kto][$index]['add_stm'] = $lech_stm;
								$this->log[$kto][$index]['minus_hp'] = $minus_hp;
								$this->log[$kto][$index]['minus_mp'] = $minus_mp;
								$this->log[$kto][$index]['minus_stm'] = $minus_stm;
							}
							
							$protect_hp = $lech_hp;
							$this->all[$kto]['HP']-=$minus_hp;
							$this->all[$kto]['MP']-=$minus_mp;
							$this->all[$kto]['STM']-=$minus_stm;
							$this->all[$kogo]['HP']+=$lech_hp;
							$this->all[$kogo]['MP']+=$lech_mp;
							$this->all[$kogo]['STM']+=$lech_stm;
						}
						else
						{
							$this->log[$kto][]['action'] = 8;
							$index = sizeof($this->log[$kto])-1;
							$this->log[$kto][$index]['chem'] = $lech['id'];
						}
					}
					else
					{
						$this->log[$kto][]['action'] = 9;
					}
				}
				break;

				case 32:
				{
					//лечение артефактом				
					$select=myquery("select game_items.id,game_items.item_uselife,game_items_factsheet.name,game_items_factsheet.id AS item_id,game_items_factsheet.indx,game_items.item_uselife,game_items.count_item  FROM game_items, game_items_factsheet WHERE game_items_factsheet.sv='Лечение' AND game_items.id=".$act['action_chem']." and game_items.user_id=$kto and game_items.item_uselife>0 and game_items_factsheet.type=3 and game_items.priznak=0 and game_items.used>0 and game_items.item_id=game_items_factsheet.id");
					if (mysql_num_rows($select))
					{
						$lech=mysql_fetch_array($select);
						if ($lech['item_uselife']>0 AND $lech['count_item']>0)
						{
							//$val=floor(($lech['indx']+$this->all[$kto]['MS_ART'])*$act['action_proc']/100*$this->decrease);
							$val=floor(($lech['indx']+$this->all[$kto]['MS_ART'])*$act['action_proc']/100);
							$val=$this->calc_position($val,$kto,$kogo);

							$protect_hp = $val;
							$this->log[$kto][]['action'] = 10;
							$index = sizeof($this->log[$kto])-1;
							$this->log[$kto][$index]['na_kogo'] = $kogo;
							$this->log[$kto][$index]['na_kogo_name'] = $this->all[$kogo]['name'];
							$this->log[$kto][$index]['name'] = $lech['name'];
							$this->log[$kto][$index]['procent'] = $act['action_proc'];
							$this->log[$kto][$index]['add_hp'] = $val;							

							if ($this->all[$kto]['npc']==0)
							{
								$polomka = round($act['action_proc']/100*mt_rand(10,100)/100,2);
								$up=myquery("update game_items set item_uselife=item_uselife-$polomka,count_item=count_item-1 WHERE user_id=$kto AND id=".$act['action_chem']." AND priznak=0");
								$this->check_item_down($act['action_chem'],$kto);
							}							
							$this->all[$kogo]['HP']+=$val;
						}
					}
					else
					{
						$this->log[$kto][]['action'] = 11;
					}					
				}
				break;

				case 33:
				{
					//лечение эликсиром
					$minus_STM = ceil($act['action_proc']/100*6);
					if ($this->all[$kto]['STM']>=$minus_STM AND $act['action_proc']==100)
					{
						$select=myquery("select game_items_factsheet.name as ident,game_items_factsheet.weight,game_items_factsheet.hp_p,game_items_factsheet.mp_p,game_items_factsheet.stm_p,game_items.id  FROM game_items_factsheet,game_items WHERE game_items_factsheet.type=13 AND game_items.id=".$act['action_chem']." and game_items.user_id=$kto and game_items.item_id=game_items_factsheet.id and game_items.priznak=0 AND game_items.used IN (12,13,14)");
						if (mysql_num_rows($select))
						{
							$lech=mysql_fetch_array($select);

							$this->log[$kto][]['action'] = 13;
							$index = sizeof($this->log[$kto])-1;
							$this->log[$kto][$index]['name'] = $lech['ident'];
							$this->log[$kto][$index]['na_kogo'] = $kogo;
							$this->log[$kto][$index]['na_kogo_name'] = $this->all[$kogo]['name'];
							$this->log[$kto][$index]['procent'] = $act['action_proc'];
							$this->log[$kto][$index]['minus_stm'] = $minus_STM;

							$Item = new Item($lech['id']);
							$Item->admindelete();
							
							$this->all[$kto]['STM']-=$minus_STM;
							//Отключим запрет на использование только 1 эликсира за бой
							//myquery("UPDATE combat_users SET eliksir=eliksir+1 WHERE user_id=$kto");
							$this->all[$kogo]['HP']+=floor($lech['hp_p']);
							$this->all[$kogo]['MP']+=floor($lech['mp_p']);
							$this->all[$kogo]['STM']+=floor($lech['stm_p']);
						}
						else
						{
							$this->log[$kto][]['action'] = 14;
						}
					}
					else
					{
						$this->log[$kto][]['action'] = 15;
					}
				}
				break;

				//БЛОК ЗАЩИТЫ
				case 21:
				{
					//защищался щитом
					$shit=myquery("SELECT game_items_factsheet.name,game_items_factsheet.indx,game_items_factsheet.deviation,game_items_factsheet.type,game_items_factsheet.mode  FROM game_items,game_items_factsheet WHERE game_items.item_id=game_items_factsheet.id AND game_items.used>0 AND game_items.priznak=0 AND game_items.user_id=$kto AND game_items.id=".$act['action_chem']."");
					if (mysql_num_rows($shit) OR $this->all[$kto]['npc']==1)
					{
						if ($this->all[$kto]['npc']==0)
						{
							$zashit=mysql_fetch_array($shit);
						}
						else
						{
							$shield_defense = $this->all[$kto]['clevel']*2+10;
							$zashit['indx'] = $shield_defense;
							$zashit['deviation'] = 0;
							$zashit['type'] = 0;
							$zashit['mode']='Все тело';
						}
						$stm_need = 4;
						if ($kto!=$kogo)
						{
							$stm_need = 6;
						}
						if($zashit['type']==1) {
							$stm_need = 8;
						}
                        $minus_MP = -10000;
                        $cont = 1;
                        if ($act['action_priem']==4)
                        {
                            $uspeh = mt_rand(0,100);
                            if ($uspeh>70) $cont = 0;
                            //Круговая защита оружием
                            if ($this->all[$kto]['STM_MAX']>=$this->all[$kto]['MP_MAX'])
                            {
                                $stm_need = 0.25*$this->all[$kto]['STM_MAX'];
                            }
                            else
                            {
                                $minus_MP = 0.25*$this->all[$kto]['MP_MAX'];
                                $stm_need = -10000;
                            }
							if ($this->all[$kto]['MS_PRUDENCE']>0)
							{
								$rand=mt_rand(1,100);
								if ($rand<=75+$this->all[$kto]['MS_PRUDENCE'])
								{
									$stm_need=ceil($stm_need*(100-$this->all[$kto]['MS_PRUDENCE']*$prudence_effect)/100);
									$minus_MP=ceil($minus_MP*(100-$this->all[$kto]['MS_PRUDENCE']*$prudence_effect)/100);
								}
							}
                        }
						$minus_STM = ceil($act['action_proc']/100*$stm_need);
						// Удачная попытка, энергии хватает
						if ($this->all[$kto]['STM']>=$minus_STM AND $this->all[$kto]['MP']>=$minus_MP AND $cont==1)
						{
                            if ($minus_MP>0)
                            {
                                $this->all[$kto]['MP']-=$minus_MP;
                            }
                            else
                            {
							    $this->all[$kto]['STM']-=$minus_STM;
                            }
                            if ($act['action_priem']!=4)
                            {
							    if($zashit['type']!=1) {
								    $defense=floor(mt_rand($zashit['indx']-$zashit['deviation']+$this->all[$kto]['VIT']+$this->all[$kto]['MS_PARIR']*5,$zashit['indx']+$zashit['deviation']+$this->all[$kto]['VIT']+$this->all[$kto]['MS_PARIR']*5)*$act['action_proc']/100);
							    }
							    else
							    {
								    $defense=floor(mt_rand($zashit['indx']-$zashit['deviation']+$this->all[$kto]['VIT']+$this->all[$kto]['MS_WEAPON'],$zashit['indx']+$zashit['deviation']+$this->all[$kto]['VIT']+$this->all[$kto]['MS_WEAPON'])*$act['action_proc']/100);
							    }
                            }
                            else
                            {
                                $defense=9999;
                            }

							$this->log[$kto][]['action'] = 16;
							$index = sizeof($this->log[$kto])-1;
							$this->log[$kto][$index]['mode'] = $zashit['mode'];
							$this->log[$kto][$index]['name'] = $kuda;
							$this->log[$kto][$index]['na_kogo'] = $kogo;
							$this->log[$kto][$index]['na_kogo_name'] = $this->all[$kogo]['name'];
							$this->log[$kto][$index]['procent'] = $act['action_proc'];
							$this->log[$kto][$index]['add_hp'] = $defense;
                            if ($act['action_priem']==4)
                            {
                                $this->log[$kto][$index]['minus_hp'] = 5;
                            }
                            if ($minus_MP>0)
                            {
                                $this->log[$kto][$index]['minus_mp'] = $minus_MP;
                                $this->log[$kto][$index]['minus_stm'] = 0;
                            }
                            else
                            {
								$this->log[$kto][$index]['minus_stm'] = $minus_STM;
                                $this->log[$kto][$index]['minus_mp'] = 0;
                            }

                            if ($act['action_priem']!=4)
                            {
							    switch ($act['action_kuda'])
							    {
								    case 1:
									    $this->all[$kogo]['defense']['HP']['golova']+=$defense;
									    $this->all[$kogo]['defense']['HP']['plecho']+=$defense;
									    $this->all[$kogo]['defense_all']['HP']['golova']+=$defense;
									    $this->all[$kogo]['defense_all']['HP']['plecho']+=$defense;
								    break;
								    case 2:
									    $this->all[$kogo]['defense']['HP']['telo']+=$defense;
									    $this->all[$kogo]['defense']['HP']['pah']+=$defense;
									    $this->all[$kogo]['defense_all']['HP']['telo']+=$defense;
									    $this->all[$kogo]['defense_all']['HP']['pah']+=$defense;
								    break;
								    case 3:
									    $this->all[$kogo]['defense']['HP']['pah']+=$defense;
									    $this->all[$kogo]['defense']['HP']['nogi']+=$defense;
									    $this->all[$kogo]['defense_all']['HP']['pah']+=$defense;
									    $this->all[$kogo]['defense_all']['HP']['nogi']+=$defense;
								    break;
							    }
                            }
                            else
                            {
                                $this->all[$kogo]['defense']['HP']['all']+=$defense;
                                $this->all[$kogo]['defense']['HP']['golova']+=$defense;
                                $this->all[$kogo]['defense']['HP']['plecho']+=$defense;
                                $this->all[$kogo]['defense']['HP']['telo']+=$defense;
                                $this->all[$kogo]['defense']['HP']['pah']+=$defense;
                                $this->all[$kogo]['defense']['HP']['nogi']+=$defense;
                                $this->all[$kogo]['defense_all']['HP']['all']+=$defense;
                                $this->all[$kogo]['defense_all']['HP']['golova']+=$defense;
                                $this->all[$kogo]['defense_all']['HP']['plecho']+=$defense;
                                $this->all[$kogo]['defense_all']['HP']['telo']+=$defense;
                                $this->all[$kogo]['defense_all']['HP']['pah']+=$defense;
                                $this->all[$kogo]['defense_all']['HP']['nogi']+=$defense;
                            }
						}
						else
						{
                            // Неудачная попытка, энергии хватает (ну, в этот раз не повезло..)
                            if ($cont==0 AND $this->all[$kto]['STM'] >= $minus_STM AND $this->all[$kto]['MP'] >= $minus_MP)
                            {
                                $this->log[$kto][]['action'] = 77;
                                $index = sizeof($this->log[$kto])-1;
                                if ($minus_MP > 0)
                                {
                                  $this->log[$kto][$index]['minus_mp'] = $minus_MP;
                                  $this->all[$kto]['MP']-=$minus_MP;
                                }
                                else
                                {
                                  $this->log[$kto][$index]['minus_stm'] = $minus_STM;
                                  $this->all[$kto]['STM']-=$minus_STM;
                                }


                            }
                            // Неудачная попытка, энергии не хватает
                            else
                            {
							    $this->log[$kto][]['action'] = 17;
							    $index = sizeof($this->log[$kto])-1;
							    $this->log[$kto][$index]['mode'] = $zashit['mode'];
                            }
						}
					}
					else
					{
						$this->log[$kto][]['action'] = 18;
					}
				}
				break;
				
				case 22:
				{
					 //защита навыком
					 $shit=myquery("SELECT *  FROM game_spells WHERE id=".$act['action_chem']." AND type=3");					
					 if (mysql_num_rows($shit))
					 {
					    $users_kto_NTL=$this->all[$kto]['NTL'];
						$zashit = mysql_fetch_array($shit);
						$minus_mp=0;
						$minus_hp=0;
						$minus_stm=0;
						$minus_mp = ceil($act['action_proc']/100*$zashit['mana']);
						if ($this->all[$kto]['MS_PRUDENCE']>0)
						{
							$rand=mt_rand(1,100);
							if ($rand<=75+$this->all[$kto]['MS_PRUDENCE'])
							{
								$minus_mp=ceil($minus_mp*(100-$this->all[$kto]['MS_PRUDENCE']*$prudence_effect)/100);
							}
						}						
						if ($this->all[$kto]['MP']>=$minus_mp )
						{							
							$defense_hp=0;
							$defense_mp=0;
							$defense_stm=0;
							$defense_hp=floor(mt_rand($zashit['effect']-$zashit['rand']+$users_kto_NTL,$zashit['effect']+$zashit['rand']+$users_kto_NTL)*$act['action_proc']/100*$this->decrease);
							$defense_hp=$this->calc_position($defense_hp,$kto,$kogo);
							
							//проверим промах для мага
							$random = mt_rand(1,100);							
							$level=$zashit['level'];
							$promah=0;
														
							if ($this->all[$kto]['clevel'] == 0) { $this->all[$kto]['clevel'] = 80; }
							if ($this->all[$kto]['class_type'] == 12 OR $this->all[$kto]['npc'] == 1)
							{
								$check = 100;
							}
							elseif ($this->all[$kto]['class_type'] == 11 OR $this->all[$kto]['class_type'] == 13)
							{
								$check = 15 + $level*2 + min($this->all[$kto]['SPD']*2,50) + floor(80/$this->all[$kto]['clevel']);
							}
							else
							{
								$check = $level*2 + min($this->all[$kto]['SPD']*2,50) + floor(80/$this->all[$kto]['clevel']);
							}
													
							if ($random>$check OR $random<=5-$this->all[$kto]['lucky'])
							{
								$defense_hp=0;
								$defense_mp=0;
								$defense_stm=0;
								$minus_hp=0;
								$minus_mp=0;
								$minus_stm=0;
								$promah=1;
							}

							if ($promah==1)
							{
								$this->log[$kto][]['action'] = 37;
								$index = sizeof($this->log[$kto])-1;
								$this->log[$kto][$index]['name'] = $zashit['name'];								
							}
							else
							{								
								$this->log[$kto][]['action'] = 19;
								$index = sizeof($this->log[$kto])-1;							
								$this->log[$kto][$index]['name'] = $zashit['name'];
								$this->log[$kto][$index]['na_kogo'] = $kogo;
								$this->log[$kto][$index]['na_kogo_name'] = $this->all[$kogo]['name'];
								$this->log[$kto][$index]['procent'] = $act['action_proc'];
								$this->log[$kto][$index]['add_hp'] = $defense_hp;
								$this->log[$kto][$index]['add_mp'] = $defense_mp;
								$this->log[$kto][$index]['add_stm'] = $defense_stm;
								$this->log[$kto][$index]['minus_mp'] = $minus_mp;
								$this->log[$kto][$index]['minus_hp'] = $minus_hp;
								$this->log[$kto][$index]['minus_stm'] = $minus_stm;
							}

							$protect_hp = $defense_hp;							
							$this->all[$kogo]['defense']['HP']['all']+=$defense_hp;
							$this->all[$kogo]['defense']['HP']['golova']+=$defense_hp;
							$this->all[$kogo]['defense']['HP']['plecho']+=$defense_hp;
							$this->all[$kogo]['defense']['HP']['telo']+=$defense_hp;
							$this->all[$kogo]['defense']['HP']['pah']+=$defense_hp;
							$this->all[$kogo]['defense']['HP']['nogi']+=$defense_hp;
							$this->all[$kogo]['defense']['MP']+=$defense_mp;
							$this->all[$kogo]['defense']['STM']+=$defense_stm;
							$this->all[$kogo]['defense_all']['HP']['all']+=$defense_hp;
							$this->all[$kogo]['defense_all']['HP']['golova']+=$defense_hp;
							$this->all[$kogo]['defense_all']['HP']['plecho']+=$defense_hp;
							$this->all[$kogo]['defense_all']['HP']['telo']+=$defense_hp;
							$this->all[$kogo]['defense_all']['HP']['pah']+=$defense_hp;
							$this->all[$kogo]['defense_all']['HP']['nogi']+=$defense_hp;
							$this->all[$kogo]['defense_all']['MP']+=$defense_mp;
							$this->all[$kogo]['defense_all']['STM']+=$defense_stm;
							$this->all[$kto]['HP']-=$minus_hp;
							$this->all[$kto]['MP']-=$minus_mp;
							$this->all[$kto]['STM']-=$minus_stm;
						}
						else
						{
							$this->log[$kto][]['action'] = 20;
							$index = sizeof($this->log[$kto])-1;							
							$this->log[$kto][$index]['name'] = $zashit['name'];
						}
					}
					else
					{
						$this->log[$kto][]['action'] = 21;
					}
				}
				break;
				
				case 23:
				{
					//защита артефактом					
					$shit=myquery("SELECT game_items.id,game_items_factsheet.mode,game_items_factsheet.name,game_items_factsheet.indx,game_items_factsheet.deviation,game_items.item_uselife,game_items.count_item  FROM game_items,game_items_factsheet WHERE game_items_factsheet.id=game_items.item_id AND game_items.user_id=$kto AND game_items.id=".$act['action_chem']." AND game_items_factsheet.sv='Защита' AND game_items.used>0 AND game_items.priznak=0 AND game_items.item_uselife>0");
					if (mysql_num_rows($shit))
					{
						$zashit=mysql_fetch_array($shit);
						if (($zashit['item_uselife']>0 AND $zashit['count_item']>0)OR($this->all[$kto]['npc']>0))
						{													
							if ($this->all[$kto]['npc']==0)
							{
								$polomka = round($act['action_proc']/100*mt_rand(10,100)/100,2);
								$up=myquery("update game_items set item_uselife=item_uselife-$polomka,count_item=count_item-1 WHERE user_id=$kto AND id=".$act['action_chem']." AND priznak=0");
								$this->check_item_down($act['action_chem'],$kto);
							}								
							
							$defense=floor(mt_rand($zashit['indx']-$zashit['deviation']+$this->all[$kto]['MS_ART'],$zashit['indx']+$zashit['deviation']+$this->all[$kto]['MS_ART'])*$act['action_proc']/100);
							$defense=$this->calc_position($defense,$kto,$kogo);
							
							$protect_hp = $defense;
							$this->log[$kto][]['action'] = 22;
							$index = sizeof($this->log[$kto])-1;
							$this->log[$kto][$index]['mode'] = $zashit['mode'];
							$this->log[$kto][$index]['name'] = $zashit['name'];
							$this->log[$kto][$index]['na_kogo'] = $kogo;
							$this->log[$kto][$index]['na_kogo_name'] = $this->all[$kogo]['name'];
							$this->log[$kto][$index]['procent'] = $act['action_proc'];
							$this->log[$kto][$index]['add_hp'] = $defense;							
						   
							$this->all[$kogo]['defense']['HP']['all']+=$defense;
							$this->all[$kogo]['defense']['HP']['golova']+=$defense;
							$this->all[$kogo]['defense']['HP']['plecho']+=$defense;
							$this->all[$kogo]['defense']['HP']['telo']+=$defense;
							$this->all[$kogo]['defense']['HP']['pah']+=$defense;
							$this->all[$kogo]['defense']['HP']['nogi']+=$defense;
							$this->all[$kogo]['defense_all']['HP']['all']+=$defense;
							$this->all[$kogo]['defense_all']['HP']['golova']+=$defense;
							$this->all[$kogo]['defense_all']['HP']['plecho']+=$defense;
							$this->all[$kogo]['defense_all']['HP']['telo']+=$defense;
							$this->all[$kogo]['defense_all']['HP']['pah']+=$defense;
							$this->all[$kogo]['defense_all']['HP']['nogi']+=$defense;
						}
					}
					else
					{
						$this->log[$kto][]['action'] = 23;
					}					
				}
				break;
				
				//БЛОК АТАКИ
				case 11:
				{
					//атака кулаком
                    $prob = false;					
					$k=2;
					
					/* Отключим кулачное оружие, до тех пор, пока она не введено
					$est_weapon = mysql_result(myquery("SELECT COUNT(*) FROM game_items WHERE user_id=$kto AND used=1 AND priznak=0"),0,0);
					if ($est_weapon==0)
					{
						$k=3;
					}
					*/
					
					if($this->all[$kogo]['npc']==1)
					{
						$bron = 0;
						$user_bu = 0;
					}					
					else
					{
						$bron = $this->get_bron($act['action_kuda'],0,$kogo);
						$user_bu = $this->all[$kogo]['MS_KULAK'];
					}
					//$damage_hp=max(0,floor($act['action_proc']/100*mt_rand($this->all[$kto]['STR']-1+$this->all[$kto]['MS_KULAK']*$k-$bron,$this->all[$kto]['STR']+1+$this->all[$kto]['MS_KULAK']*$k-$bron)));
					$damage_hp=max(0,floor($act['action_proc']/100*mt_rand(-1+$this->all[$kto]['MS_KULAK']*$k,+1+$this->all[$kto]['MS_KULAK']*$k)));
					
					if ($this->all[$kto]['MS_BERSERK']>0)
					{
						$rand=mt_rand(1,100);
						if ($rand<=15+$this->all[$kto]['MS_BERSERK']*3)
						{
							$this->log[$kto][]['action'] = 85;
							$damage_hp=$damage_hp+floor($act['action_proc']/100*(5+$this->all[$kto]['MS_BERSERK'])*$this->all[$kogo]['HP']/$this->all[$kogo]['HP_MAX']);
						}
					}
					
					$promah=0;
					$kritic=0;
					//промах при ударе кулаком
					$random = mt_rand(1,100);
					//$random_5=mt_rand(1,100);
					$krit=5+$this->all[$kto]['lucky'];
					if ($act['action_kuda']==1 OR $act['action_kuda']==3) $krit=8-$this->all[$kogo]['lucky'];
					//промахи при ударе оружием или кулаком
					//$prot_parir = $this->all[$kogo]['MS_PARIR'];
					//$user_bu = $this->all[$kto]['MS_KULAK'];

					//$check = 75+($this->all[$kto]['PIE']-$this->all[$kogo]['PIE'] + $user_bu - $prot_parir)*3;
					$check = 50+($this->all[$kto]['MS_KULAK']-$user_bu)*3;
					$type_attack = 'удар';
					if($act['action_priem']==2) {
						//Сила атаки уменьшается на 25%,  вероятность крита увеличивается на 2%
						$check+=25;
						$damage_hp=ceil($damage_hp*0.8);
						$type_attack = 'прицельный удар';
						$krit+=2;
					}
					if($act['action_priem']==3) {
						$check-=20;
						$damage_hp=ceil($damage_hp*1.25);
						$type_attack = 'мощный удар';
						$krit=$this->all[$kto]['lucky'];
					} 
					
                    $damage_hp=ceil($damage_hp*$this->all[$kto]['svit_usil']);
                    $damage_hp=$this->calc_position($damage_hp,$kto,$kogo);
					
					//if (($random>$check or $random_5<=5-$this->all[$kto]['lucky']) and $random>10 ) //проверка попали-ли и 10% шанс попасть в любом случае
					if ($random>$check) //проверка попали-ли
					{
						$damage_hp=0;
						$promah=1;
					}
					
					if ($promah==0)
					{
						if (($random<=$krit) AND (($this->all[$kto]['PIE']-$this->all[$kogo]['PIE'])>5))
						{
							$damage_hp = 1.5*$damage_hp;
							$kritic=1;
						}
						elseif ($random<=$krit/2)
						{
							$damage_hp = 1.5*$damage_hp;
							$kritic=1;
						}
				   }
					
					$damage_hp=max(0,floor($damage_hp*$this->decrease));
					if ($promah==1)
					{
						$this->log[$kto][]['action'] = 25;
					}
					elseif ($kritic==1)
					{
						$this->log[$kto][]['action'] = 26;
						$index = sizeof($this->log[$kto])-1;
						$this->log[$kto][$index]['na_kogo'] = $kogo;
						$this->log[$kto][$index]['na_kogo_name'] = $this->all[$kogo]['name'];
						$this->log[$kto][$index]['name'] = $type_attack;
						$this->log[$kto][$index]['mode'] = $kuda;
						$this->log[$kto][$index]['procent'] = $act['action_proc'];
						$this->log[$kto][$index]['add_hp'] = $damage_hp;
					}
					else
					{
						if ($damage_hp<=0 AND $act['action_proc']==100)
						{
							$damage_hp=1;
							$this->log[$kto][]['action'] = 27;
							$index = sizeof($this->log[$kto])-1;
							$this->log[$kto][$index]['na_kogo'] = $kogo;
							$this->log[$kto][$index]['na_kogo_name'] = $this->all[$kogo]['name'];
							$this->log[$kto][$index]['mode'] = $kuda;
							$this->log[$kto][$index]['procent'] = $act['action_proc'];
							$this->log[$kto][$index]['add_hp'] = $damage_hp;
						}
						else
						{
							$this->log[$kto][]['action'] = 28;
							$index = sizeof($this->log[$kto])-1;
							$this->log[$kto][$index]['na_kogo'] = $kogo;
							$this->log[$kto][$index]['na_kogo_name'] = $this->all[$kogo]['name'];
							$this->log[$kto][$index]['name'] = $type_attack;
							$this->log[$kto][$index]['mode'] = $kuda;
							$this->log[$kto][$index]['procent'] = $act['action_proc'];
							$this->log[$kto][$index]['add_hp'] = $damage_hp;
						}
					}
				}
				break;

				case 12:
				{
					//атака оружием
                    $prob = false;
					$no_decrease=false;
					$the=myquery("SELECT game_items.id, game_items_factsheet.name as ident, game_items_factsheet.mode, game_items_factsheet.indx, game_items_factsheet.deviation, game_items_factsheet.img, game_items.item_uselife, game_items.used, game_items_factsheet.type_weapon, game_items_factsheet.type_weapon_need  FROM game_items,game_items_factsheet WHERE game_items.user_id=$kto  AND game_items.id=".$act['action_chem']." AND game_items_factsheet.id=game_items.item_id AND game_items.used>0 AND game_items.priznak=0");
					if (mysql_num_rows($the) or $this->all[$kto]['npc']==1)
					{
						 if ($this->all[$kto]['npc']==1)
						 {
							$npc_temp=$this->all[$kto]['npc_id_template'];														
							$item_npc = $Npc->templ['item'];
							$weapon['ident']=$item_npc;
							$weapon['mode']=$item_npc;
							$weapon['indx']=0;
							$weapon['deviation']=0;
							$weapon['item_uselife']=100;
							$weapon['type_weapon'] = 0;
							$add = 0;
							$stm = 8;
							if($this->combat['map_name']==691 OR $this->combat['map_name']==692 OR $this->combat['map_name']==804)
							{
								$weapon['indx']=$this->all[$kogo]['STR'];
							}
							$r = mt_rand(1,10);
							if ($r<=1) {$act['action_priem']=2;}
							elseif ($r<=2) {$act['action_priem']=3;}
                            $minus_stm = ceil($act['action_proc']/100*$stm);
						 }
						 else
						 {
							 $weapon=mysql_fetch_array($the);
							 $stm = 8;
							 $add = $this->all[$kto]['clevel']/2;
						     $minus_stm = ceil($act['action_proc']/100*$stm);							
                             if ($act['action_priem']==5)
                             {
                                 $prob = true;
                                 $minus_stm = ceil($act['action_proc']/100*$this->all[$kto]['STM_MAX']*0.15);                                  
                             }
							 if ($this->all[$kto]['MS_PRUDENCE']>0)
							 {
								$rand=mt_rand(1,100);
								if ($rand<=75+$this->all[$kto]['MS_PRUDENCE'])
								{
									$minus_stm=ceil($minus_stm*(100-$this->all[$kto]['MS_PRUDENCE']*$prudence_effect)/100);
								}
							 }
                         }

						 if ($this->all[$kto]['STM']>=$minus_stm OR $this->all[$kto]['npc']==1)
						 {
							 if ($weapon['item_uselife']>0)
							 {
								$MS_kto = 0;
								$MS_kogo = 0;
								$STRENGTH = $this->all[$kto]['STR'];
								if($weapon['type_weapon']==0) 
								{
									$MS_kto = $this->all[$kto]['MS_WEAPON'];
								}
								elseif($weapon['type_weapon']==1) 
								{
									$MS_kto = $this->all[$kto]['MS_KULAK'];
								}
								elseif($weapon['type_weapon']==2) 
								{
									$MS_kto = $this->all[$kto]['MS_LUK'];
								}
								elseif ($this->check_weapon_class($this->char['class_type'], $weapon['type_weapon'])) 
								{
									$MS_kto = $this->all[$kto]['class_level'];
								}								
								$MS_kogo = $this->all[$kogo]['MS_PARIR'];
								$indx = $weapon['indx']+$add;
								if($act['action_priem']==2) {
									$indx=ceil($indx*0.8);
								}
								elseif($act['action_priem']==3) {
									$indx=ceil($indx*1.2);
								}
                                
								if($this->all[$kogo]['npc']==1)
								{
									$bron = 0;
								}
								else
								{
									$bron = $this->get_bron($act['action_kuda'],$weapon['type_weapon'],$kogo);
								}
								if ($weapon['type_weapon']==2 AND $this->combat['hod']==1)
								{
									$damage_hp=floor($act['action_proc']/100*(mt_rand($STRENGTH*3+$indx-$weapon['deviation']+$MS_kto*3-$this->all[$kogo]['VIT']-$MS_kogo-$bron,$STRENGTH*3+$indx+$weapon['deviation']+$MS_kto*3-$this->all[$kogo]['VIT']-$MS_kogo-$bron)));
								}
								else
								{
									$damage_hp=floor($act['action_proc']/100*(mt_rand($STRENGTH+$indx-$weapon['deviation']+$MS_kto-$this->all[$kogo]['VIT']-$MS_kogo-$bron,$STRENGTH+$indx+$weapon['deviation']+$MS_kto-$this->all[$kogo]['VIT']-$MS_kogo-$bron)));
								}
                                
								if ($this->all[$kto]['MS_BERSERK']>0)
								{
									$rand=mt_rand(1,100);
									if ($rand<=15+$this->all[$kto]['MS_BERSERK']*3)
									{
										$this->log[$kto][]['action'] = 85;
										$damage_hp=$damage_hp+floor($act['action_proc']/100*(5+$this->all[$kto]['MS_BERSERK'])*$this->all[$kogo]['HP']/$this->all[$kogo]['HP_MAX']);
									}
								}
								
								//Проверим промахи
								$random = mt_rand(1,100);
								$random_5=mt_rand(1,100);
								$krit=5+$this->all[$kto]['lucky'];
								$promah=0;
								$polomka=0;
								$kritic=0;
								if ($act['action_kuda']==1 OR $act['action_kuda']==3) $krit=8-$this->all[$kogo]['lucky'];

								//промахи при ударе оружием
								$prot_parir = $MS_kogo;
								$user_bu = $MS_kto;

								$check = 75+($this->all[$kto]['PIE']-$this->all[$kogo]['PIE'] + $user_bu - $prot_parir)*3;
								$type_attack = 'удар';
                                if (isset($prob) AND $prob) {
                                    $check+=15;
                                    $type_attack = 'пробивающий удар';
                                    //Пробивающий удар оружием
                                    //Урон - 50%
                                    $damage_hp = $damage_hp/2;
                                }
								if($act['action_priem']==2) {
									 $type_attack = 'прицельный удар';
									$check+=25;
									$krit+=2;
								}
								if($act['action_priem']==3) {
									$check-=20;
									$type_attack = 'мощный удар';
									$krit = $this->all[$kto]['lucky'];
								}
								
								if ($this->all[$kto]['npc']==1)
								{
									// Проверка на фиксированный удар бота
									if (isset($npc_options[$npc_temp][4]))
									{										
										$damage_hp = mt_rand($npc_options[$npc_temp][4][1]['value'], $npc_options[$npc_temp][4][2]['value']);
										$no_decrease = true;
									}
									// Проверка на удар бота в зависимости от уровня жизней игрока
									elseif (isset($npc_options[$npc_temp][5]))
									{										
										$damage_hp = max(1,$npc_options[$npc_temp][5][1]['value']*$this->all[$kogo]['HP_MAX']/100);
										$no_decrease = true;
										$prob = true;
										$type_attack = 'пробивающий удар';
									}
									elseif ($this->all[$kogo]['NPC_DEFENCE']>0)
									{
										$damage_hp=$damage_hp*(100-$this->all[$kogo]['NPC_DEFENCE'])/100;
									}									
								}
								
								if ($this->all[$kto]['npc']==1 and isset($npc_options[$npc_temp][1]))
								{									
									$promah=0;
								}
								elseif (($random>$check or $random_5<=5-$this->all[$kto]['lucky']) and $random>10 ) //Проверка промахов при ударе оружием
								{
									$damage_hp=0;
									$promah=1;
								}
								
								if ($promah==0)
								{
									if (($random<=$krit) AND (($this->all[$kto]['PIE']-$this->all[$kogo]['PIE'])>5))
									{
										$damage_hp = 1.5*$damage_hp;
										$kritic = 1;
									}
									elseif ($random<=$krit/2)
									{
										$damage_hp = 1.5*$damage_hp;
										$kritic = 1;
									}
								}	
								
                                $damage_hp=ceil($damage_hp*$this->all[$kto]['svit_usil']);
                                $damage_hp=$this->calc_position($damage_hp,$kto,$kogo);

								$this->all[$kto]['STM']-=$minus_stm;
								if ($damage_hp>0 AND $this->all[$kto]['npc']==0)
								{
									$polomka = round($act['action_proc']/100*mt_rand(10,100)/100,2);
									myquery("update game_items set item_uselife=item_uselife-$polomka WHERE user_id=$kto AND id=".$act['action_chem']." AND priznak=0");
                                    $this->check_item_down($act['action_chem'],$kto);
								}
								if (!$no_decrease)
								{
									$damage_hp = max(0,floor($damage_hp*$this->decrease));
								}

								if ($promah==1)
								{
									$this->log[$kto][]['action'] = 29;
									$index = sizeof($this->log[$kto])-1;
									$this->log[$kto][$index]['minus_stm'] = $minus_stm;
									$r = mt_rand(0,100);
									if ($r<=5 and $random_5>5-$this->all[$kto]['lucky'])
									{
										if($act['action_priem']==3)
										{
											$polomka = 100;
											if ($this->all[$kto]['npc']==0)
											{
												myquery("update game_items set item_uselife=0 WHERE user_id=$kto AND id=".$act['action_chem']." AND priznak=0");
                                                $this->check_item_down($act['action_chem'],$kto);
											}
											$this->log[$kto][]['action'] = 30;
										}
										else
										{
											$polomka = round(mt_rand(300,500)/100,2);
											if ($this->all[$kto]['npc']==0)
											{
												myquery("update game_items set item_uselife=item_uselife-$polomka WHERE user_id=$kto AND id=".$act['action_chem']." AND priznak=0");
                                                $this->check_item_down($act['action_chem'],$kto);
											}
											$this->log[$kto][]['action'] = 31;
											$index = sizeof($this->log[$kto])-1;
											$this->log[$kto][$index]['procent'] = $polomka;
										}
									}
								}
								elseif ($kritic==1)
								{
									$this->log[$kto][]['action'] = 32;
									$index = sizeof($this->log[$kto])-1;
									$this->log[$kto][$index]['na_kogo'] = $kogo;
									$this->log[$kto][$index]['na_kogo_name'] = $this->all[$kogo]['name'];
									$this->log[$kto][$index]['name'] = $type_attack;
									$this->log[$kto][$index]['mode'] = $weapon['mode'];
									$this->log[$kto][$index]['kuda'] = $kuda;
									$this->log[$kto][$index]['procent'] = $act['action_proc'];
									$this->log[$kto][$index]['add_hp'] = $damage_hp;
									$this->log[$kto][$index]['minus_stm'] = $minus_stm;
								}
								else
								{
									$this->log[$kto][]['action'] = 33;
									$index = sizeof($this->log[$kto])-1;
									$this->log[$kto][$index]['na_kogo'] = $kogo;
									$this->log[$kto][$index]['na_kogo_name'] = $this->all[$kogo]['name'];
									$this->log[$kto][$index]['name'] = $type_attack;
									$this->log[$kto][$index]['mode'] = $weapon['mode'];
									$this->log[$kto][$index]['kuda'] = $kuda;
									$this->log[$kto][$index]['procent'] = $act['action_proc'];
									$this->log[$kto][$index]['add_hp'] = $damage_hp;
									$this->log[$kto][$index]['minus_stm'] = $minus_stm;
								}
							 }
							 else
							 {
								$this->log[$kto][]['action'] = 34;
							 }
						 }
						 else
						 {
							$this->log[$kto][]['action'] = 35;
						 }
					 }
					 else
					 {
						$this->log[$kto][]['action'] = 36;
					 }
				}
				break;

				case 13:
				{
					///атака магией
                    $prob = false;
					$no_decrease = false;
					$the=myquery("SELECT *  FROM game_spells WHERE id=".$act['action_chem']." and type=1");

					if ($this->all[$kto]['npc']==1)					
                    {
						$npc_temp=$this->all[$kto]['npc_id_template'];													
                    }
					if (mysql_num_rows($the))
					{
						$weapon=mysql_fetch_array($the);
						$minus_mp=0;
						$minus_hp=0;
						$minus_stm=0;
						$mag_def = 0;
						$minus_mp = ceil($act['action_proc']/100*$weapon['mana']);
						if ($this->all[$kto]['MS_PRUDENCE']>0)
						{
							$rand=mt_rand(1,100);
							if ($rand<=75+$this->all[$kto]['MS_PRUDENCE'])
							{
								$minus_mp=ceil($minus_mp*(100-$this->all[$kto]['MS_PRUDENCE']*$prudence_effect)/100);
							}
						}
						
						/*Блок магической защиты
												
						{
							$mag_def = mysql_result(myquery("SELECT SUM( game_items_factsheet.magic_def_index ) AS mag_def  FROM game_items,game_items_factsheet WHERE game_items.item_id = game_items_factsheet.id AND game_items.user_id =$kogo AND game_items.priznak =0 AND game_items.used >0"),0,0);
						}
						*/
						
						if ($this->all[$kto]['MP']>=$minus_mp )
						{															
							$damage_hp=0;
							$damage_mp=0;
							$damage_stm=0;
							$indx_hp = $this->all[$kto]['NTL']+$weapon['effect']-$mag_def;
							$damage_hp=max(0,floor($act['action_proc']/100*mt_rand($indx_hp-$weapon['rand'],$indx_hp+$weapon['rand'])));
							
							if ($this->all[$kto]['MS_BERSERK']>0)
							{
								$rand=mt_rand(1,100);
								if ($rand<=15+$this->all[$kto]['MS_BERSERK']*3)
								{
									$this->log[$kto][]['action'] = 85;
									$damage_hp=$damage_hp+floor($act['action_proc']/100*(5+$this->all[$kto]['MS_BERSERK'])*$this->all[$kogo]['HP']/$this->all[$kogo]['HP_MAX']);
								}
							}
								
							$this->all[$kto]['MP']-=$minus_mp;
							$this->all[$kto]['STM']-=$minus_stm;
							$this->all[$kto]['HP']-=$minus_hp;

							//проверим промах для мага
							$random = mt_rand(1,100);	
							if ($this->all[$kto]['class_type'] == 10)							
							{
								$level=1;
							}
							else
							{
								$level=$weapon['level'];
							}
							$promah=0;
							
							$check = 85 + ($this->all[$kto]['SPD']-$this->all[$kogo]['SPD'])*2 - $level + $this->all[$kto]['lucky'] - $this->all[$kogo]['lucky'];
							
							if ($this->all[$kto]['npc']==1)
							{
								// Проверка на фиксированный удар бота
								if (isset($npc_options[$npc_temp][4]))
								{										
									$damage_hp = mt_rand($npc_options[$npc_temp][4][1]['value'], $npc_options[$npc_temp][4][2]['value']);
									$no_decrease = true;
								}
								// Проверка на удар бота в зависимости от уровня жизней игрока
								elseif (isset($npc_options[$npc_temp][5]))
								{										
									$damage_hp = max(1,$npc_options[$npc_temp][5][1]['value']*$this->all[$kogo]['HP_MAX']/100);
									$no_decrease = true;
									$prob = true;
									$type_attack = 'пробивающий удар';
								}						
								elseif ($this->all[$kogo]['NPC_DEFENCE']>0)
								{
									$damage_hp=$damage_hp*(100-$this->all[$kogo]['NPC_DEFENCE'])/100;
								}							
							}
							
							if (!$no_decrease)
							{
								$damage_hp=max(0,floor($damage_hp*$this->decrease));
							}
							if ($this->all[$kto]['npc']==1 and isset($npc_options[$npc_temp][1]))
							{								
								$promah=0;
							}
							elseif ($random>$check OR $random<=5-$this->all[$kto]['lucky'])
							{
								$damage_hp=0;
								$damage_mp=0;
								$damage_stm=0;
								$promah=1;
							}
							$damage_hp=ceil($damage_hp*$this->all[$kto]['svit_usil']);
                            $damage_hp=$this->calc_position($damage_hp,$kto,$kogo);
							$polomka = round(mt_rand(1,5) * $act['action_proc']/100, 2);
							$ok=1;
							if ($promah==1)
							{
								$this->log[$kto][]['action'] = 37;
								$index = sizeof($this->log[$kto])-1;
								$this->log[$kto][$index]['name'] = $weapon['name'];								
							}
							else
							{
								//сначала усложним жизнь магам
								if ($this->all[$kto]['npc']==1)
								{
								}
								else
								{
									$est = @mysql_result(@myquery("SELECT COUNT(*)  FROM game_items WHERE user_id=$kto AND used=1 AND priznak=0"),0,0);
									if ($est>0)
									{
										$r = mt_rand(1,100);
										if ($r<=5)
										{
											$polomka=$polomka*2;
											myquery("update game_items set item_uselife=item_uselife-$polomka WHERE user_id=$kto AND used=1 AND priznak=0");
                                            $this->check_item_down(-1,$kto);
											$ok=2;
										}
										elseif ($r<=10)
										{
											myquery("update game_items set item_uselife=item_uselife-$polomka WHERE user_id=$kto AND used=1 AND priznak=0");
                                            $this->check_item_down(-1,$kto);
											$ok=3;
										}
									}
								}

								$this->log[$kto][]['action'] = 38;
								$index = sizeof($this->log[$kto])-1;
								$this->log[$kto][$index]['na_kogo'] = $kogo;
								$this->log[$kto][$index]['na_kogo_name'] = $this->all[$kogo]['name'];
								$this->log[$kto][$index]['name'] = $weapon['name'];								
								$this->log[$kto][$index]['procent'] = $act['action_proc'];
								$this->log[$kto][$index]['add_hp'] = $damage_hp;
								$this->log[$kto][$index]['add_mp'] = $damage_mp;
								$this->log[$kto][$index]['add_stm'] = $damage_stm;
							}
							$this->log[$kto][$index]['minus_mp'] = $minus_mp;
							$this->log[$kto][$index]['minus_hp'] = $minus_hp;
							$this->log[$kto][$index]['minus_stm'] = $minus_stm;
							if ($ok==2)
							{
								$this->log[$kto][]['action'] = 59;
								$index = sizeof($this->log[$kto])-1;
								$this->log[$kto][$index]['procent'] = $polomka;
							}
							if ($ok==3)
							{
								$this->log[$kto][]['action'] = 39;
								$index = sizeof($this->log[$kto])-1;
								$this->log[$kto][$index]['procent'] = $polomka;
							} 
						}
						else
						{
							$this->log[$kto][]['action'] = 40;
							$index = sizeof($this->log[$kto])-1;
							$this->log[$kto][$index]['name'] = $weapon['name'];							
						}
					}
					else
					{
						$this->log[$kto][]['action'] = 41;
					}
				}
				break;

				case 14:
				{
					//атака артефактом
                    $prob = false;
					$no_decrease = false;
					$the=myquery("SELECT game_items.id, game_items_factsheet.name as ident, game_items_factsheet.mode, game_items_factsheet.indx, game_items_factsheet.deviation, game_items_factsheet.img, game_items.item_uselife, game_items.count_item  FROM game_items,game_items_factsheet WHERE game_items_factsheet.id=game_items.item_id AND game_items.user_id=$kto AND game_items.id=".$act['action_chem']." AND game_items.used>0 AND game_items.priznak=0");
					if (mysql_num_rows($the) or $this->all[$kto]['npc']==1)
					{
						if ($this->all[$kto]['npc']==1)
						{
							$npc_temp=$this->all[$kto]['npc_id_template'];							
							$item_npc = $Npc->templ['item'];
							$weapon['ident']=$item_npc;
							$weapon['mode']=$item_npc;
							$weapon['indx']=$this->all[$kto]['STR']-$this->all[$kto]['clevel'];
							$weapon['deviation']=0;
							$weapon['item_uselife']=100;
							$weapon['count_item']=1;
							$this->all[$kto]['STM']=200;      
						}
						else
						{
							$weapon=mysql_fetch_array($the);
						}						 
						if ($weapon['item_uselife']>0 AND $weapon['count_item']>0)
						{
							$damage_hp=max(0,floor($act['action_proc']/100*$this->decrease*mt_rand($weapon['indx']-$weapon['deviation']+$this->all[$kto]['clevel']+$this->all[$kto]['MS_ART']-$this->all[$kogo]['MS_ART'],$weapon['indx']+$weapon['deviation']+$this->all[$kto]['clevel']+$this->all[$kto]['MS_ART']-$this->all[$kogo]['MS_ART'])));                         
							if ($this->all[$kto]['MS_BERSERK']>0)
							{
								$rand=mt_rand(1,100);
								if ($rand<=15+$this->all[$kto]['MS_BERSERK']*3)
								{
									$this->log[$kto][]['action'] = 85;
									$damage_hp=$damage_hp+floor($act['action_proc']/100*(5+$this->all[$kto]['MS_BERSERK'])*$this->all[$kogo]['HP']/$this->all[$kogo]['HP_MAX']);
								}
							}							
							if ($this->all[$kto]['npc']==1)
							{
								// Проверка на фиксированный удар бота
								if (isset($npc_options[$npc_temp][4]))
								{										
									$damage_hp = mt_rand($npc_options[$npc_temp][4][1]['value'], $npc_options[$npc_temp][4][2]['value']);
									$no_decrease = true;
								}
								// Проверка на удар бота в зависимости от уровня жизней игрока
								elseif (isset($npc_options[$npc_temp][5]))
								{										
									$damage_hp = max(1,$npc_options[$npc_temp][5][1]['value']*$this->all[$kogo]['HP_MAX']/100);
									$no_decrease = true;
									$prob = true;
									$type_attack = 'пробивающий удар';
								}
								elseif ($this->all[$kogo]['NPC_DEFENCE']>0)
								{
									$damage_hp=$damage_hp*(100-$this->all[$kogo]['NPC_DEFENCE'])/100;
								}
							}
							$damage_hp=ceil($damage_hp*$this->all[$kto]['svit_usil']);
							$damage_hp=$this->calc_position($damage_hp,$kto,$kogo);
							if ($this->all[$kto]['npc']==0)
							{
								$polomka = round($act['action_proc']/100*mt_rand(10,100)/100,2);
								$up=myquery("update game_items set item_uselife=item_uselife-$polomka,count_item=count_item-1 WHERE user_id=$kto AND id=".$act['action_chem']." AND priznak=0");
								$this->check_item_down($act['action_chem'],$kto);
							}
							$this->log[$kto][]['action'] = 42;
							$index = sizeof($this->log[$kto])-1;
							$this->log[$kto][$index]['na_kogo'] = $kogo;
							$this->log[$kto][$index]['na_kogo_name'] = $this->all[$kogo]['name'];
							$this->log[$kto][$index]['mode'] = $weapon['mode'];
							$this->log[$kto][$index]['kuda'] = $kuda;
							$this->log[$kto][$index]['procent'] = $act['action_proc'];
							$this->log[$kto][$index]['add_hp'] = $damage_hp;
						 }
						 else
						 {
							 $this->log[$kto][]['action'] = 43;
						 }
					}
					else
					{
						$this->log[$kto][]['action'] = 45;
					}
				}
				break;
				
				case 15:
				{
					//выстрел из лука
                    $prob = false;
					$the=myquery("SELECT game_items.id, game_items_factsheet.name as ident, game_items_factsheet.mode, game_items_factsheet.indx, game_items_factsheet.deviation, game_items_factsheet.img, game_items.item_uselife  FROM game_items,game_items_factsheet WHERE game_items_factsheet.id=game_items.item_id AND game_items.user_id=$kto AND game_items.id=".$act['action_chem']." AND game_items.used=0 AND game_items.priznak=0");
					if (mysql_num_rows($the))
					{
						 $weapon=mysql_fetch_array($the);
						 $minus = 10;
						 if ($this->all[$kto]['MS_PRUDENCE']>0)
						 {
							$rand=mt_rand(1,100);
							if ($rand<=75+$this->all[$kto]['MS_PRUDENCE'])
							{
								$minus=ceil($minus*(100-$this->all[$kto]['MS_PRUDENCE']*$prudence_effect)/100);
							}
						 }
						 if ($this->all[$kto]['STM_MAX']>$this->all[$kto]['MP_MAX'])
						 {
							 $har = 'STM';
						 }
						 else
						 {
							 $har = 'MP';
						 }
						 if ($this->all[$kto][$har]>=$minus)
						 {
							$chance = mt_rand(0,100);
							if ($this->all[$kto]['npc']==0)
							{
								$Item = new Item($weapon['id']);
								$Item->admindelete();
							}
							if (domain_name == 'testing.rpg.su' or domain_name=='localhost') 
							{
								//$chance_archer=85 + ($this->all[$kto]['SPD']-$this->all[$kogo]['SPD'])*2 + $this->all[$kto]['lucky'] - $this->all[$kogo]['lucky'];;
								$chance_archer=(50+5*$this->all[$kto]['MS_LUK']);
							}
							else
							{
								$chance_archer=(50+5*$this->all[$kto]['MS_LUK']);
							}
							 if ($chance<=$chance_archer)
							 {
								if (domain_name == 'testing.rpg.su' or domain_name=='localhost') 
								{
									$dam=$this->all[$kto]['MS_LUK']+$weapon['indx']+$this->all[$kto]['PIE']+$this->all[$kto]['SPD']-$this->all[$kogo]['VIT'];
									$damage_hp=mt_rand($dam-$weapon['deviation'],$dam+$weapon['deviation']);
								}
								else
								{
									$damage_hp=mt_rand($weapon['indx']-$weapon['deviation'],$weapon['indx']+$weapon['deviation']);
								}
								 
                                 $damage_hp=ceil($damage_hp*$this->all[$kto]['svit_usil']);
                                 $damage_hp=$this->calc_position($damage_hp,$kto,$kogo);
								 if ($this->all[$kto]['STM_MAX']>$this->all[$kto]['MP_MAX'])
								 {
									$this->log[$kto][]['action'] = 64;
									$index = sizeof($this->log[$kto])-1;
									$this->log[$kto][$index]['minus_stm'] = $minus;
									$this->all[$kto]['STM']-=$minus; 
								 }
								 else
								 {
									$this->log[$kto][]['action'] = 65;
									$index = sizeof($this->log[$kto])-1;
									$this->log[$kto][$index]['minus_mp'] = $minus;
									$this->all[$kto]['MP']-=$minus; 
								 }
								 $this->log[$kto][$index]['na_kogo'] = $kogo;
								 $this->log[$kto][$index]['na_kogo_name'] = $this->all[$kogo]['name'];
								 $this->log[$kto][$index]['mode'] = $weapon['mode'];
								 $this->log[$kto][$index]['kuda'] = $kuda;
								 $this->log[$kto][$index]['procent'] = 0;
								 $this->log[$kto][$index]['add_hp'] = $damage_hp;
							 }
							 else
							 {
								 $this->log[$kto][]['action'] = 66;  
							 }
						 }
						 else
						 {
							 if ($this->all[$kto]['STM_MAX']>$this->all[$kto]['MP_MAX'])
							 {
								 $this->log[$kto][]['action'] = 61;
							 }
							 else
							 {
								 $this->log[$kto][]['action'] = 62;
							 }
						 }
					 }
					 else
					 {
						$this->log[$kto][]['action'] = 63;
					 }
				}
				break;
				
				case 16:
				{
					//бросок метательного предмета
                    $prob = false;
					$the=myquery("SELECT game_items.id, game_items_factsheet.name as ident, game_items_factsheet.mode, game_items_factsheet.indx, game_items_factsheet.deviation, game_items_factsheet.img, game_items.item_uselife  FROM game_items,game_items_factsheet WHERE game_items_factsheet.id=game_items.item_id AND game_items.user_id=$kto AND game_items.id=".$act['action_chem']." AND game_items.used=0 AND game_items.priznak=0");
					if (mysql_num_rows($the))
					{
						 $weapon=mysql_fetch_array($the);
						 $minus = 10;
						 if ($this->all[$kto]['STM_MAX']>$this->all[$kto]['MP_MAX'])
						 {
							 $har = 'STM';
						 }
						 else
						 {
							 $har = 'MP';
						 }
						 if ($this->all[$kto][$har]>=$minus)
						 {
							 $chance = mt_rand(0,100);
							 if ($this->all[$kto]['npc']==0)
							 {
								$Item = new Item($weapon['id']);
								$Item->admindelete();
							 }
							 if ($chance<=(50+5*$this->all[$kto]['MS_THROW']))
							 {
								 $damage_hp=mt_rand($weapon['indx']-$weapon['deviation'],$weapon['indx']+$weapon['deviation']);
                                 $damage_hp=ceil($damage_hp*$this->all[$kto]['svit_usil']);
                                 $damage_hp=$this->calc_position($damage_hp,$kto,$kogo);
								 if ($this->all[$kto]['STM_MAX']>$this->all[$kto]['MP_MAX'])
								 {
									$this->log[$kto][]['action'] = 70;
									$index = sizeof($this->log[$kto])-1;
									$this->log[$kto][$index]['minus_stm'] = $minus;
									$this->all[$kto]['STM']-=$minus; 
								 }
								 else
								 {
									$this->log[$kto][]['action'] = 71;
									$index = sizeof($this->log[$kto])-1;
									$this->log[$kto][$index]['minus_mp'] = $minus;
									$this->all[$kto]['MP']-=$minus; 
								 }
								 $this->log[$kto][$index]['na_kogo'] = $kogo;
								 $this->log[$kto][$index]['na_kogo_name'] = $this->all[$kogo]['name'];
								 $this->log[$kto][$index]['mode'] = $weapon['mode'];
								 $this->log[$kto][$index]['kuda'] = $kuda;
								 $this->log[$kto][$index]['procent'] = 0;
								 $this->log[$kto][$index]['add_hp'] = $damage_hp;
							 }
							 else
							 {
								 $this->log[$kto][]['action'] = 72;  
							 }
						 }
						 else
						 {
							 if ($this->all[$kto]['STM_MAX']>$this->all[$kto]['MP_MAX'])
							 {
								 $this->log[$kto][]['action'] = 67;
							 }
							 else
							 {
								 $this->log[$kto][]['action'] = 68;
							 }
						 }
					 }
					 else
					 {
						$this->log[$kto][]['action'] = 69;
					 }
				}
				break;

                case 17:
                {
                    //использование свитка усиления
                    $prob = false;
                    $the=myquery("SELECT game_items.id, game_items_factsheet.name as ident, game_items_factsheet.mode, game_items.item_id, game_items_factsheet.deviation, game_items_factsheet.img, game_items.item_uselife  FROM game_items,game_items_factsheet WHERE game_items_factsheet.id=game_items.item_id AND game_items.user_id=$kto AND game_items.id=".$act['action_chem']." AND game_items.used IN (12,13,14) AND game_items.priznak=0");
                    if (mysql_num_rows($the))
                    {
                         $weapon=mysql_fetch_array($the);
                         $minus = 10;
                         if ($this->all[$kto]['STM_MAX']>$this->all[$kto]['MP_MAX'])
                         {
                             $har = 'STM';
                         }
                         else
                         {
                             $har = 'MP';
                         }
                         if ($this->all[$kto][$har]>=$minus)
                         {
                             if ($this->all[$kto]['npc']==0)
                             {
                                $Item = new Item($weapon['id']);
                                $Item->admindelete();
                             }
                             $this->log[$kto][]['action'] = 73;
                             $index = sizeof($this->log[$kto])-1;
                             if ($weapon['item_id']==item_id_svitok_light_sopr OR $weapon['item_id']==item_id_svitok_medium_sopr OR $weapon['item_id']==item_id_svitok_hard_sopr OR $weapon['item_id']==item_id_svitok_absolut_sopr)
                             {
                                 if ($weapon['item_id']==item_id_svitok_light_sopr) $k_svitok_usil = 25;
                                 if ($weapon['item_id']==item_id_svitok_medium_sopr) $k_svitok_usil = 50;
                                 if ($weapon['item_id']==item_id_svitok_hard_sopr) $k_svitok_usil = 75;
                                 if ($weapon['item_id']==item_id_svitok_absolut_sopr) $k_svitok_usil = 100;
                                 $this->log[$kto][$index]['mode'] = $weapon['ident'].' (-'.$k_svitok_usil.'% повреждений)';
                             }
                             else
                             {
                                 if ($weapon['item_id']==item_id_svitok_light_usil) $k_svitok_usil = 1.25;
                                 if ($weapon['item_id']==item_id_svitok_medium_usil) $k_svitok_usil = 1.50;
                                 if ($weapon['item_id']==item_id_svitok_hard_usil) $k_svitok_usil = 1.75;
                                 if ($weapon['item_id']==item_id_svitok_absolut_usil) $k_svitok_usil = 2.00;
                                 $this->log[$kto][$index]['mode'] = $weapon['ident'].' (+'.(($k_svitok_usil-1)*100).'% урона)';
                                 $this->all[$kto]['svit_usil'] = $k_svitok_usil;
                             }
                         }
                         else
                         {
                             if ($this->all[$kto]['STM_MAX']>$this->all[$kto]['MP_MAX'])
                             {
                                 $this->log[$kto][]['action'] = 74;
                             }
                             else
                             {
                                 $this->log[$kto][]['action'] = 75;
                             }
                         }
                     }
                     else
                     {
                        $this->log[$kto][]['action'] = 76;
                     }
                }
                break;
            }
            //$damage_hp = $damage_hp * $k_svitok_usil;
			
            //Снимем сломанные предметы
            $selused = myquery("
            SELECT game_items.id, game_items_factsheet.type
             FROM game_items, combat_users, game_items_factsheet 
            WHERE game_items.user_id=combat_users.user_id
            AND combat_users.combat_id=".$this->combat['combat_id']." 
            AND game_items.used>0 
            AND game_items.item_uselife<=0
            AND game_items.item_id=game_items_factsheet.id
            AND game_items_factsheet.type NOT IN (12,13,21,19)
            AND game_items.priznak=0");
            while ($it = mysql_fetch_array($selused))
            {
                $Item = new Item($it['id']);
                $Item->down();
            }
			
			//ОБСЧЕТ РЕЗУЛЬТАТА ДЕЙСТВИЙ ХОДА
			if ($damage_hp>0 OR $damage_mp>0 OR $damage_stm>0)
			{
				$damage_hp_start = $damage_hp;
				if ($act['action_type']==13)
				{
					//при атаке магией
					$defense_hp = 0;
					$defense_mp = 0;
					$defense_stm = 0;
					if ($damage_hp>0)
					{
						$defense_hp=max(0,min($this->all[$kogo]['defense']['HP']['all'],$damage_hp));
						$this->all[$kogo]['defense']['HP']['all']-=$defense_hp;
						$damage_hp=max(0,$damage_hp-$defense_hp);
					}
					if ($damage_mp>0)
					{
						$defense_mp=max(0,min($this->all[$kogo]['defense']['MP'],$damage_mp));
						$this->all[$kogo]['defense']['MP']-=$defense_mp;
						$damage_mp=max(0,$damage_mp-$defense_mp);
					}
					if ($damage_stm>0)
					{
						$defense_stm=max(0,min($this->all[$kogo]['defense']['STM'],$damage_stm));
						$this->all[$kogo]['defense']['STM']-=$defense_stm;
						$damage_stm=max(0,$damage_stm-$defense_stm);
					}
					if (isset($prob) and $prob)
                    {
                        //Пробивающий удар оружием
                        //Активная защита не действует
                        $damage_hp = $damage_hp_start;
                        $defense_hp = 0;                        
                    }
					if ($defense_hp>0 OR $defense_mp>0 OR $defense_stm>0)
					{
						$this->log[$kto][]['action'] = 46;
						$index = sizeof($this->log[$kto])-1;
						$this->log[$kto][$index]['add_hp'] = $damage_hp;
						$this->log[$kto][$index]['add_mp'] = $damage_mp;
						$this->log[$kto][$index]['add_stm'] = $damage_stm;
						$this->log[$kto][$index]['minus_hp'] = $defense_hp;
						$this->log[$kto][$index]['minus_mp'] = $defense_mp;
						$this->log[$kto][$index]['minus_stm'] = $defense_stm;
					}
				}
				else
				{
					$defense_hp = 0;
					if ($damage_hp>0)
					{
						switch ($act['action_kuda'])
						{
							case 1:
							$defense_hp=max(0,min($this->all[$kogo]['defense']['HP']['golova'],$damage_hp));
							$this->all[$kogo]['defense']['HP']['golova']-=$defense_hp;
							$damage_hp=max(0,$damage_hp-$defense_hp);
							break;
							case 2:
							$defense_hp=max(0,min($this->all[$kogo]['defense']['HP']['telo'],$damage_hp));
							$this->all[$kogo]['defense']['HP']['telo']-=$defense_hp;
							$damage_hp=max(0,$damage_hp-$defense_hp);
							break;
							case 3:
							$defense_hp=max(0,min($this->all[$kogo]['defense']['HP']['pah'],$damage_hp));
							$this->all[$kogo]['defense']['HP']['pah']-=$defense_hp;
							$damage_hp=max(0,$damage_hp-$defense_hp);
							break;
							case 4:
							$defense_hp=max(0,min($this->all[$kogo]['defense']['HP']['plecho'],$damage_hp));
							$this->all[$kogo]['defense']['HP']['plecho']-=$defense_hp;
							$damage_hp=max(0,$damage_hp-$defense_hp);
							break;
							case 5:
							$defense_hp=max(0,min($this->all[$kogo]['defense']['HP']['nogi'],$damage_hp));
							$this->all[$kogo]['defense']['HP']['nogi']-=$defense_hp;
							$damage_hp=max(0,$damage_hp-$defense_hp);
							break;
						}
					}
                    if (isset($prob) and $prob)
                    {
                        //Пробивающий удар оружием
                        //Активная защита не действует
                        $damage_hp = $damage_hp_start;
                        $defense_hp = 0;                        
                    }
					if ($defense_hp>0)
					{
						$this->log[$kto][]['action'] = 46;
						$index = sizeof($this->log[$kto])-1;
						$this->log[$kto][$index]['add_hp'] = $damage_hp;
						$this->log[$kto][$index]['minus_hp'] = $defense_hp;
					}
				}

				$damage_hp=max(0,$damage_hp);
				$damage_mp=max(0,$damage_mp);
				$damage_stm=max(0,$damage_stm);
				
				//Рассчитаем опыт и деньги за удар
				$this->calculate_exp_gp($kto, $kogo, $damage_hp);
				
				$damage_mp = min($damage_mp,$this->all[$kogo]['MP']);
				$damage_stm = min($damage_stm,$this->all[$kogo]['STM']);
				
				$hp_before = $this->all[$kogo]['HP'];
				
				$this->all[$kogo]['HP']-=$damage_hp;
				$this->all[$kogo]['MP']-=$damage_mp;
				$this->all[$kogo]['STM']-=$damage_stm;
				$this->all[$kogo]['HP_start']=max(0,$this->all[$kogo]['HP_start']-$damage_hp);

				if($hp_before>0 AND $this->all[$kogo]['HP']<=0)
				{					
					$this->kill_user($kto, $kogo);
					
					//Отработаем навык "Вампиризм"
					if ($this->all[$kto]['MS_VAMPIRE']>0)
					{
						$regen_hp=ceil($damage_hp*$this->all[$kto]['MS_VAMPIRE']/100);
						$this->all[$kto]['HP']+=$regen_hp;
						$this->log[$kto][]['action'] = 84;
						$index = sizeof($this->log[$kto])-1;
						$this->log[$kto][$index]['add_hp'] = $regen_hp;
					}
				}
				
				//Отработаем навык "Шипы"
				if (($act['action_type']==11 OR $act['action_type']==12 OR $act['action_type']==13 OR $act['action_type']==14) AND $damage_hp_start>0 AND $this->all[$kogo]['MS_SPIKES']>0)
				{
					$r=mt_rand(1,100);
					if ($r<=50+$this->all[$kogo]['MS_SPIKES']*2)
					{
						$spikes_damage=ceil($this->all[$kogo]['MS_SPIKES']*$act['action_proc']/100);
						$this->all[$kto]['HP']-=$spikes_damage;
						
						//Рассчитаем опыт и деньги за удар шипов
						$this->calculate_exp_gp($kogo, $kto, $spikes_damage);
												
						$this->log[$kto][]['action'] = 86;
						$index = sizeof($this->log[$kto])-1;
						$this->log[$kto][$index]['add_hp'] = $spikes_damage;
						
						//Кто-то умер от шипов. Ха-ха!
						if ($this->all[$kto]['HP']<=0)
						{
							$this->kill_user($kogo, $kto);
						}
					}	
				}
			}	
				//Рассчитаем опыт и деньги за лечение/защиту
				if ($protect_hp > 0)
				{					
					$this->calculate_exp_gp_def($kto, $kogo, $protect_hp);
				}			
		}  	
		
		//ЛОГ ДЛЯ ВХОДЯЩИХ В БОЙ
		$selnew = myquery("SELECT *  FROM combat_users WHERE `join`=1 AND combat_id=".$this->combat['combat_id']."");
		$kol = mysql_num_rows($selnew);
		if ($kol>0)
		{
			$sort_log[0]=0;
			while ($newuser = mysql_fetch_array($selnew))
			{
				$this->log[0][]['action'] = 3;
				$index = sizeof($this->log[0])-1;
				$this->log[0][$index]['na_kogo'] = $newuser['user_id'];
				$this->log[0][$index]['na_kogo_name'] = $newuser['name'];
				if ($newuser['k_komu']>0)
				{
					$this->log[0][$index]['name'] = $this->all[$newuser['k_komu']]['name'];					
				}
			}
		}
		
		//ФОРМИРОВАНИЕ ЛОГА ХОДА
		myquery("UPDATE game_combats_log SET hod=".$this->combat['hod']." WHERE boy=".$this->combat['combat_id']."");
		$value_insert = "";
		asort($sort_log);
		foreach ($sort_log as $uid=>$sort)
		{
			$nomer = $sort;
			$key = $uid;
			$user_log_array = $this->log[$uid];
			foreach ($user_log_array as $index=>$value)
			{
				$text_id = 0;
				$na_kogo = 0;
				$kto = 0;
				$log = $user_log_array[$index];
				if (!isset($log['mode'])) $log['mode']='';
				if (!isset($log['name'])) $log['name']='';
				if (!isset($log['kuda'])) $log['kuda']='';
				if ($log['mode']!='' OR $log['name']!='' OR $log['kuda']!='')
				{
					$che = myquery("SELECT id  FROM game_combats_log_text WHERE (name='".$log['name']."' AND mode='".$log['mode']."' AND kuda='".$log['kuda']."')");
					if (mysql_num_rows($che))
					{
						list($text_id) = mysql_fetch_array($che);
					}
					else
					{
						myquery("INSERT INTO game_combats_log_text (combat_id,name,mode,kuda) VALUES (".$this->combat['combat_id'].", '".$log['name']."','".$log['mode']."','".$log['kuda']."')");
						$text_id = mysql_insert_id();
					}
				}
				if (!isset($log['na_kogo'])) $log['na_kogo']='';
				if (!isset($log['na_kogo_name'])) $log['na_kogo_name']='';
				if ($log['na_kogo']!='' OR $log['na_kogo_name']!='')
				{
					$log['na_kogo'] = ''.$log['na_kogo'].'';
					$che = myquery("SELECT id  FROM game_combats_log_text WHERE (name='".$log['na_kogo_name']."' AND mode='".$log['na_kogo']."')");
					if (mysql_num_rows($che))
					{
						list($na_kogo) = mysql_fetch_array($che);
					}
					else
					{
						myquery("INSERT INTO game_combats_log_text (combat_id,name,mode) VALUES (".$this->combat['combat_id'].", '".$log['na_kogo_name']."','".$log['na_kogo']."')");
						$na_kogo = mysql_insert_id();
					}
				}
				$kto_name = '*****';
				if (isset($this->all[$key]))
				{
					$kto_name = $this->all[$key]['name'];
				}
				$che = myquery("SELECT id  FROM game_combats_log_text WHERE (name='".$kto_name."' AND mode='".$key."')");
				if (mysql_num_rows($che))
				{
					list($kto) = mysql_fetch_array($che);
				}
				else
				{
					myquery("INSERT INTO game_combats_log_text (combat_id, name,mode) VALUES (".$this->combat['combat_id'].", '".$kto_name."','".$key."')");
					$kto = mysql_insert_id();
				}
				if (!isset($log['action'])) $log['action']=0;
				if (!isset($text_id)) $text_id=0;
				if (!isset($na_kogo)) $na_kogo=0;
				if (!isset($log['add_hp'])) $log['add_hp']=0;
				if (!isset($log['add_mp'])) $log['add_mp']=0;
				if (!isset($log['add_stm'])) $log['add_stm']=0;
				if (!isset($log['minus_hp'])) $log['minus_hp']=0;
				if (!isset($log['minus_mp'])) $log['minus_mp']=0;
				if (!isset($log['minus_stm'])) $log['minus_stm']=0;
				if (!isset($log['procent'])) $log['procent']=0;

				if (strlen($value_insert)>0)
				{
					$value_insert.=",";    
				}
				$value_insert.="(".$this->combat['combat_id'].",".$key.",".$kto.",".$this->combat['hod'].",".$log['action'].",".$text_id.",".$log['procent'].",".$na_kogo.",".$log['add_hp'].",".$log['add_mp'].",".$log['add_stm'].",".$log['minus_hp'].",".$log['minus_mp'].",".$log['minus_stm'].",".$nomer.")";
			}
		}
		if ($value_insert!='')
		{
			myquery("INSERT INTO game_combats_log_data (boy,user_id,kto,hod,action,text_id,procent,na_kogo,add_hp,add_mp,add_stm,minus_hp,minus_mp,minus_stm,sort) VALUES ".$value_insert."");
		}
		
		// Сделаем логирование данных по бою
		/*
		if ($this->combat['combat_type']==4)
		{
			myquery("INSERT INTO log_combat (combat_id, hod, combat_type, time_last_hod, map_name, map_xpos, map_ypos, start_time, turnir_type, extra, npc) 
			         SELECT combat_id, hod, combat_type, time_last_hod, map_name, map_xpos, map_ypos, start_time, turnir_type, extra, npc FROM combat WHERE combat_id=".$this->combat['combat_id']." AND hod = ".$this->combat['hod']." ");

			myquery("INSERT INTO log_combat_users (`user_id` , `npc` , `time_last_active` , `join` , `name` , `clevel` , `reinc` , `clan_id` , `combat_id` , `eliksir` , `call_clan` , `side` , `k_komu` , `HP` , `HP_MAX` ,
			         `MP` , `MP_MAX` , `STM` , `STM_MAX` , `STR` , `DEX` , `SPD` , `VIT` , `NTL` , `PIE` , `lucky` , `injury` , `pol` , `avatar` , `pass` , `sklon` , `k_exp` , `k_gp` , `race` , `HP_start` , `hod_start` ,
					 `class_type` , `class_level` , `MS_WEAPON` , `MS_KULAK` , `MS_PARIR` , `MS_ART` , `MS_LUK` , `MS_THROW` , `MS_BERSERK` , `MS_PRUDENCE` , `MS_VAMPIRE` , `MS_SPIKES` , `NPC_DEFENCE`) 
			         SELECT `user_id` , `npc` , `time_last_active` , `join` , `name` , `clevel` , `reinc` , `clan_id` , `combat_id` , `eliksir` , `call_clan` , `side` , `k_komu` , `HP` , `HP_MAX` ,
			         `MP` , `MP_MAX` , `STM` , `STM_MAX` , `STR` , `DEX` , `SPD` , `VIT` , `NTL` , `PIE` , `lucky` , `injury` , `pol` , `avatar` , `pass` , `sklon` , `k_exp` , `k_gp` , `race` , `HP_start` , `hod_start` ,
					 `class_type` , `class_level` , `MS_WEAPON` , `MS_KULAK` , `MS_PARIR` , `MS_ART` , `MS_LUK` , `MS_THROW` , `MS_BERSERK` , `MS_PRUDENCE` , `MS_VAMPIRE` , `MS_SPIKES` , `NPC_DEFENCE` FROM combat_users WHERE combat_id=".$this->combat['combat_id']." ");
			
			myquery("INSERT INTO log_combat_actions (combat_id, hod, user_id, action_type, action_chem, action_kogo, action_kuda, action_proc, action_priem, action_rand, action_type_sort, position, action_time) 
			         SELECT combat_id, hod, user_id, action_type, action_chem, action_kogo, action_kuda, action_proc, action_priem, action_rand, action_type_sort, position, action_time FROM combat_actions WHERE combat_id=".$this->combat['combat_id']." AND hod = ".$this->combat['hod']." ");
		}
		*/
		//обновим основную запись об игроке по результатам расчета
		$str_update = '';
		foreach($this->all AS $key=>$value)
		{           
			$this->all[$key]['HP']=max(0,min($this->all[$key]['HP'],$this->all[$key]['HP_MAX']));
			$this->all[$key]['MP']=max(0,min($this->all[$key]['MP'],$this->all[$key]['MP_MAX']));
			$this->all[$key]['STM']=max(0,min($this->all[$key]['STM'],$this->all[$key]['STM_MAX']));			
			$query = "UPDATE combat_users SET HP=".((int)$this->all[$key]['HP']).",MP=".((int)$this->all[$key]['MP']).",STM=".((int)$this->all[$key]['STM']).", missed_actions=missed_actions+".$this->all[$key]['miss']." WHERE user_id=".$key." ";						
			$str_update.='<br />'.$query;
			myquery($query);
			
			if ($this->combat['combat_type']==10)
			{
				$this->all[$key]['lose']=0;
				$this->all[$key]['win']=0;
			}			
			if ($this->all[$key]['win']>0)
			{
				myquery("UPDATE game_combats_users SET kills = kills +".$this->all[$key]['win']." WHERE user_id = ".$key." and boy= ".$this->combat['combat_id']." ");
			}			
			if ($this->all[$key]['npc']==0)
			{
				myquery("UPDATE game_users SET HP=".((int)$this->all[$key]['HP']).",MP=".((int)$this->all[$key]['MP']).",STM=".((int)$this->all[$key]['STM']).",win=win+".((int)$this->all[$key]['win']).",lose=lose+".((int)$this->all[$key]['lose']).",GP=GP+".((double)$this->all[$key]['gp']).",EXP=EXP+".((int)$this->all[$key]['exp']).",CW=CW+".((double)(money_weight*$this->all[$key]['gp']))." WHERE user_id=$key");
			}
			elseif ($this->all[$key]['HP']>0)
			{
				myquery("UPDATE game_npc SET HP=".$this->all[$key]['HP'].",MP=".$this->all[$key]['MP'].",WIN=WIN+".$this->all[$key]['win']." WHERE id=$key");
			}
		}

		//удалим записи из combat_actions по предпоследний ход
		myquery("DELETE FROM combat_actions WHERE combat_id=".$this->combat['combat_id']." AND hod<".$this->combat['hod']."");
		
		$win_side = $this->check_end();	
		if ($win_side == -1)
		{
			//бой еще не окончен, переводим всех игроков в след.ход
			$time = time();
			myquery("UPDATE combat_users SET `join`=0,time_last_active=$time WHERE combat_id=".$this->combat['combat_id']."");
			$sel_users = myquery("SELECT cus.user_id, cu.npc FROM combat_users_state cus JOIN combat_users cu ON cus.user_id=cu.user_id WHERE cus.combat_id=".$this->combat['combat_id']." AND cus.state=6");
            $kol_users = 0;
			while ($us = mysql_fetch_array($sel_users))
			{				
				if ($us['npc'] == 1)
				{
					$state = 6;
				}
                else
                {
                    $state = 5;
					$kol_users++;
                }
				combat_setFunc($us['user_id'],$state,$this->combat['combat_id']);
			}
            $extra = 1;
            if ($kol_users>=4 AND $this->combat['hod']>=3)
            {
                $extra = 2;
            }
			myquery("UPDATE combat SET hod=hod+1, time_last_hod=$time, extra=$extra WHERE combat_id=".$this->combat['combat_id']." AND hod = ".$this->combat['hod']." ");
			return 0;
		}
		// бой окончен, победила одна из сторон
		else
		{			
			// Выдача награды за многоклановый бой
			if ($this->combat['combat_type']==4)
			{				
				// Выдача награды за фраги, если тип боя - многоклан, количество сторон >= 3 и количество участников >= 5
				$check_frag = 0;
				$check = myquery("SELECT side, user_id, kills FROM game_combats_users WHERE boy = ".$this->combat['combat_id']." ORDER BY side");				
				$kol_sides = mysql_num_rows($check);

				$kol_users = 0;
				$kol_sides = 0;
				$prev_side = -1;
				$i = 0;
				while (list($side, $us_id, $kills) = mysql_fetch_array($check))
				{
					if ($prev_side <> $side)
					{
						$kol_sides++;
					}
					$kol_users++;
					if ($kills>0) 
					{
						$i++;
						$mas_u[$i]['user_id']=$us_id;
						$mas_u[$i]['kills']=$kills;
					}
				}
				if ($kol_users >= 5 and $kol_sides >= 3) $check_frag = 1;
				// Проверки пройдены - можно вручать награды
				if ($check_frag == 1)
				{					
					$i=1;
					while (isset($mas_u[$i]))
					{
						$item_id = 1278;
						$kol_kills = min(5,$mas_u[$i]['kills']);
						$kol_items[1] = 1;  $kol_items[2] = 3;  $kol_items[3] = 10;  $kol_items[4] = 30;  $kol_items[5] = 120;
												
						if (isset($kol_items[$kol_kills]) and $kol_items[$kol_kills] > 0)
						{
							$Item = new Item ();
							$Item->add_user($item_id, $mas_u[$i]['user_id'], 0, 0, 0, $kol_items[$kol_kills]);
						}
						$i++;
					}					
				}
				
				// Увеличение количества побед у клана победителя
				myquery("UPDATE game_clans SET cw_wins=cw_wins+1 WHERE clan_id = ".$win_side." ");
			}				
			// Завершилась Битва Хаоса
			elseif ($this->combat['combat_type']==12)
			{				
				$say = 'Завершилась Битва Хаоса! Имя победителя - [color=yellow][b]'.$this->all[$win_side]['name'].'![/b][/color]. Слава герою!!!';
				$say = iconv("Windows-1251","UTF-8//IGNORE","<span style=\"font-style:italic;font-size:12px;color:gold;font-family:Verdana,Tahoma,Arial,Helvetica,sans-serif\">".$say."</b></span>");
				myquery("INSERT INTO game_log (`message`,`date`,`fromm`) VALUES ('".mysql_real_escape_string($say)."',".time().",-1)");
				$hawk=28591; 
				$ma=myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES (".$hawk.", '0', 'Битва Хаоса', 'Завершилась Битва Хаоса! Победитель - ".$this->all[$win_side]['name']."','0','".time()."')");
                            
			}				
				
			$sel_users = myquery("SELECT user_id  FROM combat_users_state WHERE combat_id=".$this->combat['combat_id']." AND state IN (5,6)");
			while ($us = mysql_fetch_array($sel_users))
			{
				//Начислим опыт/деньги за лечебные/защитные действия в бою
				$this->nachisl_exp_gp_def($us['user_id']);
				combat_setFunc($us['user_id'],7,$this->combat['combat_id'],$this->combat['hod']);
			}
			
			// Удаляем ботов и шаблоны ботов, которые живут 1 бой
			if ($this->combat['npc']==1)
			{				
				myquery("DELETE gnt FROM combat_users cu 
				           JOIN combat_users_state cus ON cu.combat_id=cus.combat_id 
				           JOIN game_npc gn ON cu.user_id = gn.id
                           JOIN game_npc_template gnt ON gn.npc_id = gnt.npc_id
						  WHERE cu.npc = 1 AND cu.combat_id = ".$this->combat['combat_id']." AND cus.state = 7 AND gn.stay = 3
						");
						
				myquery("DELETE gn FROM combat_users cu 
				           JOIN combat_users_state cus ON cu.combat_id=cus.combat_id 
				           JOIN game_npc gn ON cu.user_id = gn.id                           
						  WHERE cu.npc = 1 AND cu.combat_id = ".$this->combat['combat_id']." AND cus.state = 7 AND gn.stay in (2, 3)
						");		
			}
			
			$this->clear_combat();
			return 1;
		}
?>