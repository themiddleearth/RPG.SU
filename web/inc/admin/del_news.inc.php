<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['news'] >= 1)
{
	$text = @mysql_result(@myquery("SELECT text FROM game_news WHERE id='".(int)$_GET['id']."'"),0,0);
	$text = mysql_real_escape_string($text);				
	$da = getdate();
	
  $log = myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
                  VALUES ('".$char['name']."', '������ �������: <b>".$text."</b>',
                 '".time()."','".$da['mday']."','".$da['mon']."','".$da['year']."')") or die(mysql_error());

  $result =myquery("UPDATE game_news SET status='1' WHERE id='".(int)$_GET['id']."'");

  echo "������� �������";
	echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=news">';
}

if (function_exists("save_debug")) save_debug(); 

?>