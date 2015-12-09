<?php
class Chat
{
	// database handler
	private $mMysqli;
	private $maskarad;
	
	// constructor opens database connection
	function __construct()
	{
		// connect to the database
		$this->mMysqli = mysql_connect(PHPRPG_DB_HOST, PHPRPG_DB_USER, PHPRPG_DB_PASS, PHPRPG_DB_NAME);
		mysql_select_db(PHPRPG_DB_NAME,$this->mMysqli);
		$this->maskarad = 0;
		//maskarad:
		//1 - перемешивание букв в имени игрока
		//2 - замена всех букв имени игрока на "*"
		//3 - замена всех букв (кроме от 2 до 3 символов) в имени игрока на "*"
		//4 - замена имени на каждый раз случайное имя другого игрока из онлайна
    //5 - обратное отображение ника игрока
    //6 - замена имени на постоянное имя другого игрока из онлайна
	}
	// destructor closes database connection
	public function __destruct()
	{
		//mysql_close($this->mMysqli);
	}
  
  private function brightness($R, $G, $B)
  {
    return (int)sqrt(
        $R * $R * .241 + 
        $G * $G * .691 + 
        $B * $B * .068);
  }

  private function deleteMessage($name, $id, $channel = 0)
  {
    $id = (int)$id;

    list($ms_date, $ms_name, $ms, $ms_ptype, $ms_too) 
      = mysql_fetch_array(myquery("SELECT  `date`, `name`, `message`, `ptype`, `too` ".
                                  "FROM game_log LEFT JOIN game_users ON game_users.user_id = game_log.fromm ".
                                  "WHERE id=".$id.";",$this->mMysqli));
    if (is_null($ms_name))
      $ms_name = "Нафаня";

    switch($ms_ptype)
    {
    case 1:
      if ($ms_too == -1)
        $to_name = 'Нафаня';
      else
      {
        $tmp = myquery("SELECT name FROM game_users WHERE user_id=".$ms_too.";", $this->mMysqli);
        if (mysql_num_rows($tmp) > 0)
          list($to_name) = mysql_fetch_array($tmp);
        else
          $to_name = '???';
      }
      $to = ' (лично '.$to_name.')';  
      break;

    case 2:
      $tmp = myquery("SELECT nazv FROM game_clans WHERE clan_id = ".$ms_too.";",$this->mMysqli);

      if (mysql_num_rows($tmp)>0)
        list($to_name) = mysql_fetch_array($tmp);
      else
        $to_name = '???';

      $to = ' (клану '.$to_name.')';  
      break;

    case 3:
      switch($ms_too)
      {
      case 1:
        $to_name = "Нейтральной";
        break;
      case 2:
        $to_name = "Светлой";
        break;
      case 3:
        $to_name = "Тёмной";
        break;
      default:
        $to_name = "---";
        break;
      }
      $to = ' ('.$to_name.' склонности)';  
      break;

    default:
      $to = '';
    }

    $up = myquery("DELETE FROM game_log WHERE id=".$id.";",$this->mMysqli);

    $message = "#delete:".$id;
    myquery("INSERT INTO game_log (town,message,date,fromm) VALUES (".$channel.",'".$message."',".time().",-1)",$this->mMysqli);

    $da = getdate();
    $log=myquery("INSERT INTO game_log_adm (adm, dei, cur_time, day, month, year) VALUES (
                 '".iconv("UTF-8","Windows-1251//IGNORE", $name)."','Удалил фразу из чата: <br/>".
                 date('[H:i:s] ', $ms_date).$ms_name.$to.': '.iconv("UTF-8","Windows-1251//IGNORE",$ms)."','".
                 time()."','".$da['mday']."','".$da['mon']."','".$da['year']."')",$this->mMysqli);

  }

	/*
	The postMessages method inserts a message into the database
	- $name represents the name of the user that posted the message
	- $messsage is the posted message
	- $color contains the color chosen by the user
	*/
	public function postMessage($message, $color, $to, $user_id, $channel, $ptype = 0)
	{
		// escape the variable data for safely adding them to the database
		$mol = mysql_result(myquery("SELECT COUNT(*) FROM game_chat_nakaz WHERE town=0 AND user_id=$user_id AND (nakaz='mol' OR nakaz='slep') AND date_zak>".time()."",$this->mMysqli),0,0);
		if  ($mol>0) return;
		$name = mysql_result(myquery("SELECT name FROM game_users WHERE user_id=$user_id",$this->mMysqli),0,0);
		$name = iconv("Windows-1251","UTF-8//IGNORE",$name);

		if ($channel==1)
		{
			$sel = myquery("SELECT combat_id FROM combat_users WHERE user_id=".$user_id." LIMIT 1",$this->mMysqli); 
			if ($sel!=false AND mysql_num_rows($sel))
			{
				list($channel) = mysql_fetch_array($sel);   
			}
		}
		if ($channel==2)
		{
			$sel = myquery("SELECT arcomage_id FROM arcomage_users WHERE user_id=".$user_id." LIMIT 1",$this->mMysqli);    
			if ($sel!=false AND mysql_num_rows($sel))
			{
				list($channel) = mysql_fetch_array($sel);   
			}   
		}

		$message = htmlspecialchars($message);  
		if ((substr($message,0,5) == "#mol:") OR
        (substr($message,0,8) == "#delete:") OR
        (substr($message,0,5) == "#eat:") OR
        (substr($message,0,6) == "#slep:") OR
        (substr($message,0,5) == "#bot:") OR
        (substr($message,0,5) == "#obn:") OR
        (substr($message,0,4) == "#ok:") OR
        (substr($message,0,5) == "/adm "))
		{
			$admin = myquery("SELECT * FROM game_mag WHERE town=0 AND name='".iconv("UTF-8","Windows-1251//IGNORE",$name)."'",$this->mMysqli);
			if (mysql_num_rows($admin))
			{
				$adm = mysql_fetch_array($admin);
				if ((substr($message,0,5) == "#mol:") AND $adm['mol']=='1')
				{
          if (preg_match_all('/:/', $message, $t) == 3)
          {
          	list($a, $b, $c, $d) = explode(":", $message, 4);
            $this->deleteMessage($name, $d);
          }
          else
          	list($a, $b, $c)     = explode(":", $message, 3);

          $c = iconv("UTF-8","Windows-1251//IGNORE", mysql_escape_string(trim($c)));
					$time = (int)$b * 60;
					$sel_user_c = myquery("SELECT user_id FROM game_users WHERE name='".$c."'",$this->mMysqli);
					if ($sel_user_c!=false AND mysql_num_rows($sel_user_c)>0)
					{
						$c_user_id =mysql_result($sel_user_c,0,0);
						$mol=myquery("insert into game_chat_nakaz (town,user_id,nakaz,date_nak,date_zak,mag) values (0,'$c_user_id','mol','".time()."','".($time+time())."','$user_id')",$this->mMysqli);
					}
					$message = '<img src="http://'.domain_name.'/chat/mag/mol.gif" border=0>&nbsp;<font color=red><b>'.iconv("Windows-1251","UTF-8//IGNORE",'Наложил печать молчания на: '.$c.' (Время '.$b.' минут.)').'<b></font>';
          $ptype = 0;
          $to = 0;
					$da = getdate();
					$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
					 VALUES (
					 '".iconv("UTF-8","Windows-1251//IGNORE",$name)."',
					 '".iconv("UTF-8","Windows-1251//IGNORE",mysql_escape_string($message))."',
					 '".time()."',
					 '".$da['mday']."',
					 '".$da['mon']."',
					 '".$da['year']."')",$this->mMysqli);
				}
				elseif ((substr($message,0,6) == "#slep:") AND $adm['slep']=='1')
				{
					list($a,$b,$c)=explode(":",$message,3);
					$c = iconv("UTF-8","Windows-1251//IGNORE",$c);
					$c_user_id =@mysql_result(@myquery("SELECT user_id FROM game_users WHERE name='".$c."'",$this->mMysqli),0,0);
					$message = '<img src="http://'.domain_name.'/chat/mag/slep.gif" border=0>&nbsp;<font color=red><b>'.iconv("Windows-1251","UTF-8//IGNORE",'Наложил печать слепоты на: '.$c.' (Время '.$b.' минут.)').'<b></font>';
					$time=$b*60;
					$mol=myquery("insert into game_chat_nakaz (town,user_id,nakaz,date_nak,date_zak,mag) values (0,'$c_user_id','slep','".time()."','".($time+time())."','$user_id')",$this->mMysqli);
					$da = getdate();
					$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
					 VALUES (
					 '".iconv("UTF-8","Windows-1251//IGNORE",$name)."',
					 '".iconv("UTF-8","Windows-1251//IGNORE",mysql_escape_string($message))."',
					 '".time()."',
					 '".$da['mday']."',
					 '".$da['mon']."',
					 '".$da['year']."')",$this->mMysqli);
				}
				elseif ((substr($message,0,5) == "#bot:"))
				{
					list($a,$b,$c)=explode(":",$message,3);
					$b = htmlspecialchars(iconv("UTF-8","Windows-1251//IGNORE",$b));
					$c = htmlspecialchars(iconv("UTF-8","Windows-1251//IGNORE",$c));
					$up=myquery("insert into game_bot_chat (text,type) VALUES ('".$c."','".$b."')",$this->mMysqli);
					$message = iconv("Windows-1251","UTF-8//IGNORE",'Нафаня выучил новое слово: '.$b.'');
					$da = getdate();
					$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
					 VALUES (
					 '".iconv("UTF-8","Windows-1251//IGNORE",$name)."',
					 '".iconv("UTF-8","Windows-1251//IGNORE",mysql_escape_string($message))."',
					 '".time()."',
					 '".$da['mday']."',
					 '".$da['mon']."',
					 '".$da['year']."')",$this->mMysqli);
				}
				elseif ((substr($message,0,5) == "/adm "))
				{                
					$message=htmlspecialchars_decode(substr($message,5));
				}
				elseif ((substr($message,0,8) == "#delete:") AND $adm['obn']=='1')
				{
					list($a, $b)=explode(":", $message, 2); 
          $this->deleteMessage($name, $b, $channel);
          $message='';
				}
				elseif ((substr($message,0,5) == "#obn:") AND $adm['obn']=='1')
				{
					$up=myquery("DELETE FROM game_log WHERE town=$channel",$this->mMysqli);
					$message = "#obn:";
					$da = getdate();
					$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
					 VALUES (
					 '".iconv("UTF-8","Windows-1251//IGNORE",$name)."',
					 'Наложил печать обновления чата',
					 '".time()."',
					 '".$da['mday']."',
					 '".$da['mon']."',
					 '".$da['year']."')",$this->mMysqli);
				}
				elseif ((substr($message,0,5) == "#eat:") AND $adm['slep']=='1')
				{
					list($a,$b,$c)=explode(":",$message,3);
					$c = iconv("UTF-8","Windows-1251//IGNORE",$c);
					$b = iconv("UTF-8","Windows-1251//IGNORE",$b);
					list($race) = mysql_fetch_array(myquery("SELECT race FROM game_users WHERE user_id='".$user_id."'",$this->mMysqli));
					$message = '<img src="http://'.domain_name.'/chat/mag/lab.gif" border=0>&nbsp;<font color=red><b>'.iconv("Windows-1251","UTF-8//IGNORE",''.mysql_result(myquery("SELECT name FROM game_har WHERE id=$race",$this->mMysqli),0,0).' съел игрока: '.$b.'. '.$c.'').'<b></font>';
					myquery("UPDATE game_users SET HP=HP/2,MP=MP/2,STM=STM/2 WHERE name='".$b."'",$this->mMysqli);
					$da = getdate();
					$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
					 VALUES (
					 '".iconv("UTF-8","Windows-1251//IGNORE",$name)."',
					 '".iconv("UTF-8","Windows-1251//IGNORE",mysql_escape_string($message))."',
					 '".time()."',
					 '".$da['mday']."',
					 '".$da['mon']."',
					 '".$da['year']."')",$this->mMysqli);
				}
				elseif ((substr($message,0,4) == "#ok:"))
				{
					list($a, $id) = explode(":", $message, 2);
          $id = (int)$id;

          list($ms_town, $ms_fromm, $ms_date, $ms_name, $ms, $ms_ptype, $ms_too, $ms_color) =
            mysql_fetch_array(myquery("SELECT `town`, `fromm`, `date`, `name`, `message`, `ptype`, `too`, `color` ".
                                      "FROM game_log LEFT JOIN game_users ON game_users.user_id = game_log.fromm ".
                                      "WHERE id=".$id.";", $this->mMysqli));

          $ms = preg_replace("/\[censored=(.*?)\]/", "$1", $ms);
          myquery("UPDATE `game_log` SET `message` = '".$ms."' WHERE id=".$id.";", $this->mMysqli);

          $ms = "#ok:".$id.":".$ms;
          myquery("INSERT INTO `game_log` (`town`, `fromm`, `ptype`, `too`, `message`, `date`, `color`)".
                   "VALUES (".$ms_town.", ".$ms_fromm.", ".$ms_ptype.", ".$ms_too.", '".$ms."', ".$ms_date.", '".$ms_color."');", $this->mMysqli);
          $message = '';
        }
				else
					$message = '';
			}
			else
				$message = '';
		}
		$color = mysql_real_escape_string($color);

    $bright = 255;

 		if (($color=='black') OR (strpos($message,"=black]")!==false))
      $bright = 0;
    elseif (preg_match('/([\da-fA-F]{6})/', $color, $matches))
      $bright = min($bright,
                    $this->brightness(hexdec(substr($matches[1], 0, 2)),
                                      hexdec(substr($matches[1], 2, 2)),
                                      hexdec(substr($matches[1], 4, 2))));
    elseif (preg_match('/([\da-fA-F]{3})/', $color, $matches))
      $bright = min($bright,
                    $this->brightness(hexdec(substr($matches[1], 0, 1).'0'),
                                      hexdec(substr($matches[1], 1, 1).'0'),
                                      hexdec(substr($matches[1], 2, 1).'0')));

    if (preg_match_all('/\[color=([\da-fA-F]{6})\]/', $message, $matches, PREG_SET_ORDER))
      foreach($matches as $m)
        $bright = min($bright,
                      $this->brightness(hexdec(substr($m[1], 0, 2)),
                                        hexdec(substr($m[1], 2, 2)),
                                        hexdec(substr($m[1], 4, 2))));
    elseif (preg_match_all('/\[color=([\da-fA-F]{3})\]/', $message, $matches, PREG_SET_ORDER))
      foreach($matches as $m)
        $bright = min($bright,
                      $this->brightness(hexdec(substr($m[1], 0, 1).'0'),
                                        hexdec(substr($m[1], 1, 1).'0'),
                                        hexdec(substr($m[1], 2, 1).'0')));

    if ($bright < 34)
		{
			$time = 20 * 60;
			$mol=myquery("insert into game_chat_nakaz (town,user_id,nakaz,date_nak,date_zak,mag) values (0,'$user_id','mol','".time()."','".($time+time())."','-1')",$this->mMysqli);
			$message = '<img src="http://'.domain_name.'/chat/mag/mol.gif" border=0>&nbsp;<font color=red><b>'.iconv("Windows-1251","UTF-8//IGNORE",'Наложена печать молчания на: ').$name.iconv("Windows-1251","UTF-8//IGNORE",' (Время 20 минут.)').'<b></font>';
			$da = getdate();
			$user_id=-1;;
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
			 VALUES (
			 'Нафаня [бот]',
			 '".iconv("UTF-8","Windows-1251//IGNORE",mysql_escape_string($message)).", за цвет текста',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')",$this->mMysqli);
		}

		// build the SQL query that adds a new message to the server
		if ($message!='')
		{
			$query = "INSERT INTO game_log (town,message,date,fromm,too,color,ptype) VALUES (".$channel.",'".mysql_real_escape_string($message)."','".time()."','".$user_id."','".$to."','".$color."',".$ptype.")";
			// execute the SQL query
			$result = myquery($query,$this->mMysqli);
		}
		return 'post';
	}
	/*
	The retrieveNewMessages method retrieves the new messages that have
	been posted to the server.
	- the $id parameter is sent by the client and it
	represents the id of the last message received by the client. Messages
	more recent by $id will be fetched from the database and returned to
	the client in JSON format.
	*/
	public function retrieveNewMessages($user_id, $id = 0, $clan_id = 0, $sklon = 0)
	{
		$nakaz = @mysql_result(myquery("SELECT COUNT(*) FROM game_chat_nakaz WHERE town=0 AND user_id=$user_id AND nakaz='slep' AND date_zak>".time()."",$this->mMysqli),0,0);
    $is_admin = mysql_num_rows(myquery("SELECT `game_mag`.* FROM  `game_mag` LEFT JOIN  `game_users` ON  `game_users`.`name` =  `game_mag`.`name` WHERE user_id = ".$user_id.";"));

		// escape the variable data
		$id = (int)$id;
		// compose the SQL query that retrieves new messages
		$selprivat = myquery("SELECT privat FROM game_chat_option WHERE user_id=$user_id",$this->mMysqli);
		$privat = 0;
		if ($selprivat!=false AND mysql_num_rows($selprivat)>0)
		{
			$privat = mysql_result($selprivat,0,0);
		}
		if ($user_id==612 OR $user_id==1 OR $user_id==1016 OR $user_id==3500)
		{
			$query = 'SELECT * FROM game_log WHERE id > '.$id.' AND (fromm>=0 OR (fromm=-1 AND (too='.$user_id.' OR too=0)) OR (fromm=-1 AND (too=1 OR ptype=2))) ORDER BY id ASC';	
		}
		else
		{
			if ($clan_id != 0)
				$query_clan = " OR (too = ".$clan_id." AND ptype = 2)";
			else
				$query_clan = "";

			if ($sklon != 0)
				$query_sklon = " OR (too = ".$sklon." AND ptype = 3)";
			else
				$query_sklon = "";

			if($id>0)
			{
				// retrieve messages newer than $id
				$query = 'SELECT * FROM game_log ' .
				'WHERE (id > '.$id.') AND ( fromm='.$user_id.' OR ptype=0 OR (too = '.$user_id.' AND ptype IN (0,1))';
			}
			else
			{
				// on the first load only retrieve the last 50 messages from server
				$query =
				'SELECT * FROM game_log ' .
				'WHERE ( fromm='.$user_id.' OR ptype=0 OR (too = '.$user_id.' AND ptype IN (0,1))';
			}

			$query = $query.$query_clan.$query_sklon.') ORDER BY id ASC;';
		}
		// execute the query
		$result = myquery($query,$this->mMysqli);
		// initialize the response array
		$response = array();
		// initialize the results array
		$results = array();
		// check to see if we have any results
		$ar_ignore = array();
		$selign = myquery("SELECT ignore_id FROM game_chat_ignore WHERE user_id=$user_id",$this->mMysqli);
		if ($selign!=false AND mysql_num_rows($selign)>0)
		{
			while (list($ig_id) = mysql_fetch_array($selign))
			{
				$ar_ignore[] = $ig_id;
			}
		}
		
		if($result)
		{
			// loop through all the fetched messages to build the results array
			while ($row = mysql_fetch_array($result))
			{
				if (in_array($row['fromm'],$ar_ignore)) continue;

				if ($privat == 1 AND $row['ob']==0)
				{
					$flag =  0;
					if ($row['fromm']==$user_id) $flag = 1;
					if ($row['too']==$user_id) $flag = 1;
					$name = mysqlresult(myquery("SELECT name FROM game_users WHERE user_id=".$user_id."",$this->mMysqli),0,0); 
					if (strpos($row['message'],$name)!=FALSE) $flag = 1;
					if ($flag == 0) continue;
				}
				$id = $row['id'];
				$color = $row['color'];
				if ($row['fromm']==-1)
				{
					$userName = iconv("Windows-1251","UTF-8//IGNORE","Нафаня");
				}
				elseif ($row['fromm']>0)
				{
					if ($this->maskarad==4)
					{
						$selname = myquery("SELECT name FROM view_active_users",$this->mMysqli);						
						$all = mysql_num_rows($selname);
						$r = mt_rand(0,$all-1);
						mysql_data_seek($selname,$r);	
						$na = mysql_fetch_assoc($selname);
						$userName = $na['name'];
            if ($user_id==612 OR $user_id==1 OR $user_id==1016 OR $user_id==3500)
            {						
              $original_name = mysqlresult(myquery("SELECT name FROM game_users WHERE user_id=".$row['fromm']."",$this->mMysqli),0,0); 
            }
					}
          elseif ($this->maskarad==6)
          {
            $selname = myquery("SELECT name FROM view_active_users",$this->mMysqli);                        
            $original_name = mysqlresult(myquery("SELECT name FROM game_users WHERE user_id=".$row['fromm']."",$this->mMysqli),0,0);  
            $na = mysql_fetch_row($selname);
            $allname = count($na);
            $user_pos = array_search($original_name,$na);
            $userName = $na[($allname-1-$user_pos)];
          }
					else
					{
						$userName = mysqlresult(myquery("SELECT name FROM game_users WHERE user_id=".$row['fromm']."",$this->mMysqli),0,0);
						$original_name = $userName;
					}
					if ($this->maskarad>0 AND $this->maskarad!=4 AND $this->maskarad!=6)
					{
						//if ($user_id==612 OR $user_id==1 OR $user_id==1016 OR $user_id==3500)
						//{}
						//else
						//{
						$ar=array();
            switch ($this->maskarad)
            {
            case 1:
							for ($i = 0; $i < strlen($userName); $i++)
								$ar[] = $userName[$i];
							shuffle($ar);
              break;

            case 2:
							for ($i = 0; $i < strlen($userName); $i++)
								$ar[] = "*";
              break;

            case 3:
							for ($i = 0; $i < strlen($userName); $i++)
								$ar[]=$userName[$i];

              $r = mt_rand(2,3);
							$kol = strlen($userName)-$r;
							while ($kol > 0)
							{
								mt_srand(make_seed());  
								$pos = mt_rand(0,strlen($userName)-1);
								if ($ar[$pos]!="*")
								{
									$ar[$pos]="*";
									$kol--;
								}
							}
              break;

            case 5:
              $strlen = strlen($userName);
              for ($i = 0; $i < $strlen; $i++)
                  $ar[] = $userName[$strlen - ($i + 1)];
              break;
            }
						$userName = implode($ar);
						$userName = ucfirst(strtolower($userName));
						//}
					}
					if ($this->maskarad > 0)
					{
						if ($user_id==612 OR $user_id==1 OR $user_id==1016 OR $user_id==3500)
						{
							$userName.=" (".$original_name.")";
						}
					}
					$userName = iconv("Windows-1251","UTF-8//IGNORE",$userName);  
				}
				else
				{
					$userName = '';  
				}
				
				if ($this->maskarad>0)
				{
					//if ($user_id==612 OR $user_id==1 OR $user_id==1016 OR $user_id==3500)
					//{}
					//else
					//{
						$hex1 = dechex(mt_rand(0,hexdec("FF")));
						if (strlen($hex1)<2) $hex1="0".$hex1;
						$hex2 = dechex(mt_rand(0,hexdec("FF")));
						if (strlen($hex2)<2) $hex2="0".$hex2;
						$hex3 = dechex(mt_rand(0,hexdec("FF")));
						if (strlen($hex3)<2) $hex3="0".$hex3;
						$color = "#".$hex1.$hex2.$hex3;
					//} 
				}

        if ($is_admin)
          $row['message'] = preg_replace("/\[censored=(.*?)\]/",
            "<span style=\"color: red; font-weight: bold\">[ ".
            "<a class='censored' onclick=\"mol_message('".$userName."', ".$row['id'].");\" style=\"\">$1</a> | ".
            "<a class='censored' onclick=\"ok_message(".$row['id'].")\" style=\"color: #0f0;\">ok</a> ]</span>", $row['message']);
        else
          $row['message'] = preg_replace("/\[censored=(.*?)\]/", "<span style=\"font-weight: bold; color: red;\">[censored]</span>", $row['message']);

        $time = date("H:i",$row['date']);
				$message = ''.$row['message'].'';

				if ($row['ptype'] == 0)
				{
					$to = '';
				}
				elseif ($row['ptype'] == 1)
				{
					if ($row['too'] == -1) $to = 'Нафаня';
					else 
					{
						$to_name = myquery("SELECT name FROM game_users WHERE user_id=".$row['too']."",$this->mMysqli);
						if (mysql_num_rows($to_name)>0)
						{
							list($to) = mysql_fetch_array($to_name);
						}
						else
						{
							$to = '';
						}
					}
					$to = iconv("Windows-1251","UTF-8//IGNORE",$to);
				}
				elseif ($row['ptype'] == 2)
				{
					$to = "..";
					$to = mysql_result(myquery("SELECT nazv FROM game_clans WHERE clan_id = ".$row['too']."",$this->mMysqli),0,0);
					$to = iconv("Windows-1251","UTF-8//IGNORE",$to);
				}
				elseif ($row['ptype'] == 3)
				{
					switch($row['too'])
					{
					case 1:
						$to = "Нейтральной";
						break;
					case 2:
						$to = "Светлой";
						break;
					case 3:
						$to = "Тёмной";
						break;
					default:
						$to = "---";
						break;
					}

/*
					$to = mysql_result(myquery("SELECT name FROM game_users WHERE user_id=".$row['too']."",$this->mMysqli),0,0);
*/
					$to = iconv("Windows-1251","UTF-8//IGNORE",$to);
				}

				$pm_id = $row['pm_id'];
				if ($nakaz>0) $message='CLEAR';
				$dh = opendir('smile/');
				while($file = readdir($dh))
				{
					if ($file=='.') continue;
					if ($file=='..') continue;
					if ($file=='.svn') continue;
					$len = strlen($file)-4;
					$ext = substr($file,$len,4); 
					$smile = substr($file,0,$len);
					if ($ext!='.gif') continue; 
					$message = str_replace("%sm".$smile, " <img src=http://".domain_name."/chat/smile/$file border=0> ", $message);
				}
				$channel = 0;
				if ($row['town']>0)
				{
					$sel = myquery("SELECT combat_id FROM combat_users WHERE user_id=".$user_id."",$this->mMysqli);
					if ($sel!=false AND mysql_num_rows($sel)>0)
					{
						list($channel) = mysql_fetch_array($sel);
						if ($channel==$row['town'])
						{
							$channel = 1;
						}
						else
						{
							continue;
						}
					}
					else
					{
						$sel = myquery("SELECT arcomage_id FROM arcomage_users WHERE user_id=".$user_id." LIMIT 1",$this->mMysqli);
						if ($sel!=false AND mysql_num_rows($sel)>0)
						{
							list($channel) = mysql_fetch_array($sel);
							if ($channel==$row['town'])
							{
								$channel = 2;
							}
							else
							{
								continue;
							}
						}
					}
					if ($channel==0) continue;
				}
				array_push($results,array('id' => $id ,
				'color' => $color ,
				'time' => $time ,
				'name' => $userName,
				'message' => $message,
				'to' => $to,
				'pm_id' => $pm_id,
				'channel' => $channel,
				'ptype' => $row['ptype']));
				if ($nakaz>0) break;
			}
			// close the database connection as soon as possible
			mysql_close($this->mMysqli);
		
		}
		// add the results to the response
		array_push($response, array('results' => $results));
		return $response;
	}
}
?>