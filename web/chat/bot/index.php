<?
include("xml.php");

function addm($char, $mes, $priv)
{
    setlocale (LC_ALL, "ru_RU.CP1251");

    if (!isset($char['sex']) || $char['sex'] == "male")
      $mes = preg_replace("/\\{([\\d\\w\\s]*?)\\|([\\d\\w\\s]*?)\\}/i", "\\1", $mes);
    else
      $mes = preg_replace("/\\{([\\d\\w\\s]*?)\\|([\\d\\w\\s]*?)\\}/i", "\\2", $mes);

    $mes='<span style="color:#FF2828;font-size:12px;">'.iconv("Windows-1251","UTF-8//IGNORE",$mes).'</style>';
    $message = $mes;
    $message = mysql_real_escape_string($message);

    $sel = myquery("SELECT `count` FROM `game_bot_chat_resp` WHERE `id` = '".$char['name']."';");
    $n = mysql_fetch_array($sel);
    if ($n['count'] <= 4)
    {
      myquery("INSERT INTO `game_bot_chat_resp` (`id`,`count`) VALUES ('".$char['name']."','1') ON DUPLICATE KEY UPDATE `count` = `count` + 1;");
      $update_chat=myquery("insert into game_log (town,fromm,too,message,date,ptype) values (0,'-1','".$char['user_id']."','".$message."','".time()."',".$priv.")");
    }
    else
    {
      $sel = myquery("SELECT `text` FROM `game_bot_chat_annoy` ORDER BY RAND() ASC LIMIT 1");
      $mes = mysql_fetch_array($sel);

      if (!isset($char['sex']) || $char['sex'] == "male")
        $mes = preg_replace("/\\{([\\d\\w\\s]*?)\\|([\\d\\w\\s]*?)\\}/i", "\\1", $mes);
      else
        $mes = preg_replace("/\\{([\\d\\w\\s]*?)\\|([\\d\\w\\s]*?)\\}/i", "\\2", $mes);

      $message = mysql_real_escape_string('<span style="color:#FF2828;font-size:12px;">'.iconv("Windows-1251","UTF-8//IGNORE",$mes['text']).'</style>');
      $update_chat=myquery("insert into game_log (town,fromm,too,message,date,ptype) values (0,'-1','".$char['user_id']."','".$message."','".time()."',".$priv.")");
    }
}
/*
if ($char['user_id'] == 15109 && preg_match ("/ debug/i", "$message"))
{
      ob_start();

        echo("<pre>\n");

        $query = "SELECT UNIX_TIMESTAMP(`reg_time`) AS `reg`, UNIX_TIMESTAMP(`unreg_time`) AS `unreg` FROM `game_clans` WHERE clan_id = ".(1).";";
        echo("\$query: \"".$query."\"\n");
        $live_res=myquery($query);

        print_r($live_res);
        echo("\n");

        $live_res = mysql_fetch_array($live_res);

        print_r($live_res);
        echo("\n\$reg:".$live_res['reg']);

//        print_r( $char );
//        echo("\$message: \"".$message."\"");
//
//        $live_reg = date("j.m.Y", $live_res['reg']);

      echo("</pre>");
      $tmp = ob_get_clean();

    $tmp = iconv("Windows-1251","UTF-8//IGNORE","OK." . $tmp);

    myquery("insert into game_log (town,fromm,too,message,date) values (0,'-1','".$char['user_id']."','".$tmp."','".time()."')");
}
*/
if (!isset($message)) {if (function_exists("save_debug")) save_debug(); exit;}

// thanks

if (preg_match ("/ (спасибо|благодар|признат|мерси|здорово|пожалуйст|прости|молодец|аригато|%sm1_04_thank_you)/i", $message))
{
  global $name;

  myquery("UPDATE `game_bot_chat_resp` SET `count` = 0 WHERE `id` = '$name';");
}

// date modificator

$gdate_mod = -2;

if (preg_match ("/ сегодня/i", $message))
 $gdate_mod = 0;
elseif (preg_match ("/ вчера/i", $message))
 $gdate_mod = -1;
elseif (preg_match ("/ завтра/i", $message))
 $gdate_mod = 1;


if (preg_match ("/овен/i", "$message"))
{
    $gor='aries';
    include('bot/goroskop.php');
    addm($char, $nline, 1);
}
elseif (preg_match ("/телец/i", "$message"))
{
    $gor='taurus';
    include('bot/goroskop.php');
    addm($char, $nline, 1);
}    
elseif (preg_match ("/близнец/i", "$message"))
{
    $gor='gemini';
    include('bot/goroskop.php');
    addm($char, $nline, 1);
}
elseif (preg_match ("/рак/i", "$message"))
{
    $gor='cancer';
    include('bot/goroskop.php');
    addm($char, $nline, 1);
}
elseif (preg_match ("/лев/i", "$message"))
{
    $gor='leo';
    include('bot/goroskop.php');
    addm($char, $nline, 1);
}
elseif (preg_match ("/дева/i", "$message"))
{
    $gor='virgo';
    include('bot/goroskop.php');
    addm($char, $nline, 1);
}
elseif (preg_match ("/весы/i", "$message"))
{
    $gor= "libra";
    include('bot/goroskop.php');
    addm($char, $nline, 1);
}
elseif (preg_match ("/скорпион/i", "$message"))
{
    $gor='scorpio';
    include('bot/goroskop.php');
    addm($char, $nline, 1);
}
elseif (preg_match ("/стрелец/i", "$message"))
{
    $gor= 'sagittarius';
    include('bot/goroskop.php');
    addm($char, $nline, 1);
}
elseif (preg_match ("/козерог/i", "$message"))
{
    $gor= 'capricorn';
    include('bot/goroskop.php');
    addm($char, $nline, 1);
}
elseif (preg_match ("/водоле/i", "$message"))
{
    $gor= 'aquarius';
    include('bot/goroskop.php');
    addm($char, $nline, 1);
}
elseif (preg_match ("/рыб/i", "$message"))
{
    $gor= 'pisces';
    include('bot/goroskop.php');
    addm($char, $nline, 1);
}
elseif (preg_match ("/ [!]?кубик/i", "$message"))
{
  if (strpos($message, "!кубик") > 0)
	{
		$priv = 0;
	}
	else
	{
		$priv = 1;
	}
	$r = mt_rand(1, 6);
  addm($char, 'выкинул кубик: <img src="http://rpg.su/chat/dice/'.$r.'.gif" align="middle" alt="'.$r.'"/>', $priv);
}
elseif (preg_match ("/ [!]?рандом[(][0-9]+,[0-9]+[)]/i", "$message"))
{
  $start = strpos($message, "рандом(");
	
	if (substr($message, $start-1, 1) == '!')
		$priv = 0;
	else
		$priv = 1;

    $middle = strpos($message, ",", $start);
	$min = substr($message, $start+7, $middle-$start-7);
	$end = strpos($message, ")", $middle);
	$max = substr($message, $middle+1, $end-$middle-1);
	if ($min >= 0 and $min < $max)
    addm($char, 'выбросил число <b>'.mt_rand($min, $max).'</b>', $priv);
}
elseif (preg_match("/ \!?вики ([\\w\\s]+)/i" , $message, $matches))
{
  $start = strpos($message, "вики");
	if (substr($message, $start-1, 1) == '!')
		$priv = 0;
	else
		$priv = 1;

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'http://ru.wikipedia.org/w/api.php?action=opensearch&search='.rawurlencode($matches[1]).'&format=xml&limit=1');
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_USERAGENT, 'rpg.su chat helper');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $result = curl_exec($ch);
  curl_close($ch);

  $arr = xml2array($result);
  
  if (isset($arr['SearchSuggestion']['_c']['Section']['_c']['Item']))
  {
    $name = iconv("UTF-8//IGNORE", "Windows-1251", $arr['SearchSuggestion']['_c']['Section']['_c']['Item']['_c']['Text']['_v']);
    $link = $arr['SearchSuggestion']['_c']['Section']['_c']['Item']['_c']['Url']['_v'];
    if (isset($arr['SearchSuggestion']['_c']['Section']['_c']['Item']['_c']['Description']['_v']))
      $desc = ': <br />'.iconv("UTF-8//IGNORE", "Windows-1251//IGNORE", $arr['SearchSuggestion']['_c']['Section']['_c']['Item']['_c']['Description']['_v']);
    else
      $desc = "";
    addm($char, 'В Википедии найдена статья "<a href="'.$link.'" target="_blank">'.$name.'</a>"'. $desc, $priv);
  } else
    addm($char, 'Ничего не найдено', 1);

  
  //addm($char, print_r($arr, true), 1);

//  $json = json_decode(file_get_contents('http://ru.wikipedia.org/w/api.php?action=opensearch&search='.rawurlencode($matches[1]).'&format=json&limit=1'));

//  addm($char, print_r($result, true), 1);
/*
    $xml = file_get_contents('http://ru.wikipedia.org/w/api.php?action=opensearch&search=".rawurlencode($matches[1])."&format=xml&limit=1');
    $arr = xml2array($xml);
    $found = $arr['SearchSuggestion']['_c']['Section']['_c']['Item']['_c']['Text']['_v'];

    addm($char, 'В википедии найдена статья "'.$found.'": <br/>', 1);
*/
}
else
{
	$private = 1;
	$mes_chat = array();	
	if (substr($message,0,9) == "Нафаня, !")
	{		
		$private = 0;
	}
	$sel=myquery("SELECT text, type FROM game_bot_chat ORDER BY LENGTH(type) DESC, RAND() ASC;");
	while($txt=mysql_fetch_array($sel))
	{
		$txtt=$txt['type'];
		if (preg_match("/$txtt/i", "$message")>0)
		{
			$mes_chat[]=$txt['text'];
		}
 	}
	if (count($mes_chat) > 0)
	{
    addm($char, $mes_chat[0], $private);
	}
 }
?>