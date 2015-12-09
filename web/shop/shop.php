<?
//ob_start('ob_gzhandler',9);
$dirclass="../class";
require('../inc/config.inc.php');
require_once('../inc/lib.inc.php');
require_once('../inc/db.inc.php');
if (!defined("MODULE_ID"))
{
	define("MODULE_ID", '3');
//	define("MODULE_ID", '5');
}
else
{
	die();
}
require('../inc/lib_session.inc.php');

if (function_exists("start_debug")) start_debug(); 

$shoping=myquery("select * from game_shop where map='".$char['map_name']."' and pos_x='".$char['map_xpos']."' and pos_y='".$char['map_ypos']."' limit 1");
if(mysql_num_rows($shoping))
{
$shop=mysql_fetch_array($shoping);

 if (!isset($_GET['page']))
   $page = 1;
 else
   $page = (int)$_GET['page'];

?>
<HTML>
<HEAD>
<TITLE><?=$shop['name'];?></TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<meta name="Keywords" content="фэнтези ролевая онлайн игра Средиземье Эпоха сражений online game items предметы поединки бои гильдии rpg кланы магия бк таверна">
<style type="text/css">
<!--
body {
		background-color: #000000;
}
body,td,th {
		color: #CCCCCC;
}
-->
</style>
<style type="text/css">@import url("../style/shop.css");</style>
<style type="text/css">@import url("../style/global.css");</style>
</HEAD>
<script language="JavaScript">
function set(type){
location.href='?type='+type+''
}
</script>
<BODY LEFTMARGIN=0 TOPMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0>
<SCRIPT language=javascript src="../js/info.js"></SCRIPT><DIV id=hint  style="Z-INDEX: 100; LEFT: 0px; VISIBILITY: hidden; POSITION: absolute; TOP: 0px"></DIV>
<?
include("../lib/menu.php");
?>
<center>
<TABLE WIDTH=820 BORDER=0 CELLPADDING=0 CELLSPACING=0>
		<TR>
				<TD>
						<IMG SRC="http://<?php echo img_domain; ?>/shops/shop/it_01.jpg" WIDTH=109 HEIGHT=33 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo img_domain; ?>/shops/shop/it_02.jpg" WIDTH=214 HEIGHT=33 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo img_domain; ?>/shops/shop/it_03.jpg" WIDTH=53 HEIGHT=33 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo img_domain; ?>/shops/shop/it_04.jpg" WIDTH=207 HEIGHT=33 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo img_domain; ?>/shops/shop/it_05.jpg" WIDTH=56 HEIGHT=33 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo img_domain; ?>/shops/shop/it_06.jpg" WIDTH=135 HEIGHT=33 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo img_domain; ?>/shops/shop/it_07.jpg" WIDTH=46 HEIGHT=33 ALT=""></TD>
		</TR>
		<TR>
				<TD>
						<IMG SRC="http://<?php echo img_domain; ?>/shops/shop/it_08.jpg" WIDTH=109 HEIGHT=57 ALT=""></TD>
				<TD>

<?
if ($shop['prod']==1)
{
	echo'<a href="?sell"><IMG SRC="http://'.img_domain.'/shops/shop/it_09.jpg" ALT="" WIDTH=214 HEIGHT=57 border="0"></a>';
}
else
{
	echo'<IMG SRC="http://'.img_domain.'/shops/shop/it_1_09.jpg" ALT="" WIDTH=214 HEIGHT=57 border="0">';
}
?>
</TD><TD><IMG SRC="http://<?php echo img_domain; ?>/shops/shop/it_10.jpg" WIDTH=53 HEIGHT=57 ALT=""></TD>
		  <td COLSPAN=3 ROWSPAN=3 background="http://<?php echo img_domain; ?>/shops/shop/it_11.jpg">

<?
if ($shop['dosp']==1)
{
	echo'<a href=shop.php?type=5><img src="http://'.img_domain.'/shops/swf/bron.gif" width="95" height="115" border=0></a>';
}
if ($shop['shit']==1)
{
	echo'<a href=shop.php?type=4><img src="http://'.img_domain.'/shops/swf/shit.gif" width="95" height="115" border=0></a>';
}
if ($shop['oruj']==1)
{
	echo'<a href=shop.php?type=1><img src="http://'.img_domain.'/shops/swf/weapon.gif" width="95" height="115" border=0></a>';
}
if ($shop['shlem']==1)
{
	echo'<a href=shop.php?type=6><img src="http://'.img_domain.'/shops/swf/shlem.gif" width="95" height="115" border=0></a>';
}
if ($shop['artef']==1)
{
	echo'<a href=shop.php?type=3><img src="http://'.img_domain.'/shops/swf/art.gif" width="95" height="115" border=0></a>';
}
if ($shop['ring']==1)
{
	echo'<a href=shop.php?type=2><img src="http://'.img_domain.'/shops/swf/ring.gif" width="95" height="115" border=0></a>';
}
if ($shop['mag']==1)
{
	echo'<a href=shop.php?type=7><img src="http://'.img_domain.'/shops/swf/mag.gif" width="95" height="115" border=0></a>';
}
if ($shop['svitki']==1)
{
	echo'<a href=shop.php?type=12><img src="http://'.img_domain.'/shops/swf/svitki.gif" width="95" height="115" border=0></a>';
}
if ($shop['pojas']==1)
{
	echo'<a href=shop.php?type=8><img src="http://'.img_domain.'/shops/swf/pojas.gif" width="95" height="115" border=0></a>';
}
if ($shop['amulet']==1)
{
	echo'<a href=shop.php?type=9><img src="http://'.img_domain.'/shops/swf/amulet.gif" width="95" height="115" border=0></a>';
}
if ($shop['perch']==1)
{
	echo'<a href=shop.php?type=10><img src="http://'.img_domain.'/shops/swf/perch.gif" width="95" height="115" border=0></a>';
}
if ($shop['boots']==1)
{
	echo'<a href=shop.php?type=11><img src="http://'.img_domain.'/shops/swf/boots.gif" width="95" height="115" border=0></a>';
}
if ($shop['eliksir']==1)
{
	echo'<a href=shop.php?type=13><img src="http://'.img_domain.'/shops/swf/eliksir.gif" width="95" height="115" border=0></a>';
}

if ($shop['shtan']==1)
{
	echo'<a href=shop.php?type=14><img src="http://'.img_domain.'/shops/swf/shtan.gif" width="95" height="115" border=0></a>';
}
if ($shop['naruchi']==1)
{
	echo'<a href=shop.php?type=15><img src="http://'.img_domain.'/shops/swf/naruch.gif" width="95" height="115" border=0></a>';
}
if ($shop['ukrash']==1)
{
	echo'<a href=shop.php?type=16><img src="http://'.img_domain.'/shops/swf/ukrash.gif" width="95" height="115" border=0></a>';
}
if ($shop['magic_books']==1)
{
	echo'<a href=shop.php?type=17><img src="http://'.img_domain.'/shops/swf/magic_books.gif" width="95" height="115" border=0></a>';
}
if ($shop['schema']==1)
{
	echo'<a href=shop.php?type=20><img src="http://'.img_domain.'/shops/swf/schema.gif" width="95" height="115" border=0></a>';
}
if ($shop['luk']==1)
{
	echo'<a href=shop.php?type=18><img src="http://'.img_domain.'/shops/swf/luk.gif" width="95" height="115" border=0></a>';
}
if ($shop['instrument']==1)
{
	echo'<a href=shop.php?type=24><img src="http://'.img_domain.'/shops/swf/instrument.gif" width="95" height="115" border=0></a>';
}
if ($shop['others']==1)
{
	echo'<a href=shop.php?type=97><img src="http://'.img_domain.'/shops/swf/other.gif" width="95" height="115" border=0></a>';
}
?>
</td>
<TD ROWSPAN=3><IMG SRC="http://<?php echo img_domain; ?>/shops/shop/it_12.jpg" WIDTH=46 HEIGHT=159 ALT=""></TD></TR>
<TR><TD><IMG SRC="http://<?php echo img_domain; ?>/shops/shop/it_13.jpg" WIDTH=109 HEIGHT=51 ALT=""></TD><TD>
<?
if ($shop['ident']==1)
{
	echo'<a href="?ident"><IMG SRC="http://'.img_domain.'/shops/shop/it_14.jpg" ALT="" WIDTH=214 HEIGHT=51 border="0"></a>';
}
elseif ($shop['kleymo']==1)
{
	echo'<a href="?kleymo"><IMG SRC="http://'.img_domain.'/shops/shop/it_50.jpg" ALT="" WIDTH=214 HEIGHT=51 border="0"></a>';
}
else
{
	echo'<IMG SRC="http://'.img_domain.'/shops/shop/it_1_14.jpg" ALT="" WIDTH=214 HEIGHT=51 border="0">';
}
?>
</TD><TD>
						<IMG SRC="http://<?php echo img_domain; ?>/shops/shop/it_15.jpg" WIDTH=53 HEIGHT=51 ALT=""></TD>
		</TR>
		<TR>
				<TD>
						<IMG SRC="http://<?php echo img_domain; ?>/shops/shop/it_16.jpg" WIDTH=109 HEIGHT=51 ALT=""></TD>
				<TD>
<?
if ($shop['remont']==1)
{
	echo'<a href="?remont"><IMG SRC="http://'.img_domain.'/shops/shop/it_17.jpg" ALT="" WIDTH=214 HEIGHT=51 border="0"></a>';
}
else
{
	echo'<IMG SRC="http://'.img_domain.'/shops/shop/it_1_17.jpg" ALT="" WIDTH=214 HEIGHT=51 border="0">';
}
?>
</TD><TD>
						<IMG SRC="http://<?php echo img_domain; ?>/shops/shop/it_18.jpg" WIDTH=53 HEIGHT=51 ALT=""></TD>
		</TR>
		<TR>
				<TD>
						<IMG SRC="http://<?php echo img_domain; ?>/shops/shop/it_19.jpg" WIDTH=109 HEIGHT=42 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo img_domain; ?>/shops/shop/it_20.jpg" WIDTH=214 HEIGHT=42 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo img_domain; ?>/shops/shop/it_21.jpg" WIDTH=53 HEIGHT=42 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo img_domain; ?>/shops/shop/it_22.jpg" WIDTH=207 HEIGHT=42 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo img_domain; ?>/shops/shop/it_23.jpg" WIDTH=56 HEIGHT=42 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo img_domain; ?>/shops/shop/it_24.jpg" WIDTH=135 HEIGHT=42 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo img_domain; ?>/shops/shop/it_25.jpg" WIDTH=46 HEIGHT=42 ALT=""></TD>
	 </TR>
		 <TR>
				<TD>
						<IMG SRC="http://<?php echo img_domain; ?>/shops/shop/it_26.jpg" WIDTH=109 HEIGHT=115 ALT=""></TD>
						<TD COLSPAN=3 ROWSPAN=4 valign=top>

<?
if (isset($_GET['type']))
{
  $type = (int)$_GET['type'];
	if (
		($type==1 and $shop['oruj']==1 and $shop['oruj_store_current']>0) or
		($type==5 and $shop['dosp']==1 and $shop['dosp_store_current']>0) or
		($type==4 and $shop['shit']==1 and $shop['shit_store_current']>0) or
		($type==2 and $shop['ring']==1 and $shop['ring_store_current']>0) or
		($type==8 and $shop['pojas']==1 and $shop['pojas_store_current']>0) or
		($type==6 and $shop['shlem']==1 and $shop['shlem_store_current']>0) or
		($type==7 and $shop['mag']==1 and $shop['mag_store_current']>0) or
		($type==3 and $shop['artef']==1 and $shop['artef_store_current']>0) or
		($type==9 and $shop['amulet']==1 and $shop['amulet_store_current']>0) or
		($type==10 and $shop['perch']==1 and $shop['perch_store_current']>0) or
		($type==11 and $shop['boots']==1 and $shop['boots_store_current']>0) or
		($type==13 and $shop['eliksir']==1 and $shop['eliksir_store_current']>0) or
		($type==14 and $shop['shtan']==1 and $shop['shtan_store_current']>0) or
		($type==15 and $shop['naruchi']==1 and $shop['naruchi_store_current']>0) or
		($type==16 and $shop['ukrash']==1 and $shop['ukrash_store_current']>0) or
		($type==12 and $shop['svitki']==1 and $shop['svitki_store_current']>0) or
		($type==17 and $shop['magic_books']==1 and $shop['magic_books_store_current']>0) or
		($type==18 and $shop['luk']==1 and $shop['luk_store_current']>0) or
		($type==20 and $shop['schema']==1 and $shop['schema_store_current']>0) or
		($type==24 and $shop['instrument']==1 and $shop['instrument_store_current']>0) or
		($type==97 and $shop['others']==1 and $shop['others_store_current']>0) 
   )
   {
		if (isset($_POST['buy']))
		{
			$buy=(int)$_GET['buy'];		
			$kol=(int)$_POST['kol'];
			$result=myquery("SELECT gif.name FROM game_items_factsheet gif JOIN game_shop_items gsi ON gif.id = gsi.items_id WHERE gif.id='".$buy."' and gsi.shop_id='".$shop['id']."' ");
			if (mysql_num_rows($result)==1 and $kol>0)
			{				
				list($name) = mysql_fetch_array($result);				
				$i = 0;
				$gp = 0;
				while ($kol>0)
				{					
					$Item = new Item();
					$ar = $Item->buy($buy);
					if ($ar[0]>0)
					{
						$char['GP']-=$ar[0];
						$gp+=$ar[0];
						$char['CW']+=$ar[1];
						$kol--;
						$i++;
					}
					else
					{
						break;
					}
				}				
				
				if ($i>0)
				{					
					$result=myquery("UPDATE game_users SET gp=".$char['GP'].", CW=".$char['CW']." WHERE user_id=".$user_id."");
					setGP($user_id,-$gp,9);					
					$pismo = 'Вы купили предмет '.$name.' - '.$i.' шт. за '.$gp.' '.pluralform($gp, 'монету', 'монеты', 'монет').'!';
					echo '<b><center><font color=ff0000><br>'.$pismo.'</font></center></b>';
					$time=time();
					myquery('INSERT INTO game_pm (komu, otkogo, theme, post, view, time, folder) VALUES ("'.$user_id.'", "0", "Покупка в магазине", "'.$pismo.'","0","'.$time.'",5)');															
					save_stat($user_id,'','',10,$shop['id'],$name,'',$gp,'','','','');					
				}
				else
				{
					echo '<b><center><font color=ff0000><br>Ваш инвентарь переполнен или у Вас не хватает денег!</font></center></b>';
				}
				
				// Если делаем покупку в Черной Пещере - то сразу после покупки выкидываем игрока в Средиземье
				if ($shop['map']==id_black_map)
				{
					myquery("UPDATE game_users_map SET map_name=18, map_xpos=".mt_rand(0,49).", map_ypos=".mt_rand(0,49)." WHERE user_id='".$char['user_id']."' LIMIT 1");
				}				
			}			
	    }
       
	   ?>
	    <script type="text/javascript" src="../js/jquery.js"></script>
	    <script type="text/javascript" src="../suggest_new/jquery.autocomplete.js"></script>
	    <link href="../suggest_new/suggest.css" rel="stylesheet" type="text/css">
	    <script type="text/javascript">	    
		$(document).ready(function() {
			$('#in_name').autocomplete({
				serviceUrl: "../suggest_new/suggest.php?itemshop="+$('#shop_id').val()+"&all_sym",
				minChars: 3,
				matchSubset: 1,
				autoFill: true,			
				width: 150,
				id: '#in_id'
			});
		});	
	    </script>
	    <?
	   
	   echo'<table width="98%" cellpadding="2" cellspacing="2" border="0">';			
	   echo'<tr align="center"><td colspan="4"><form name="input_form" id="input_form" action="shop.php?type='.$type.'" method="POST" >	
	        Введите название предмета: <input id="in_name" name="in_name" type="text" size="20" value="" autocomplete="off"><input id="in_id" name="in_id" type="hidden" size="20" value="0"> 
			<input id="shop_id" name="shop_id" type="hidden" value="'.$shop['id'].'"> <input type="submit" name="find" value="Поиск"></form>	        
			</td></tr>';	    

	  echo'<tr align="center" bgcolor="#303A67"><td><font color=ffffff><b>Рисунок</b></font></td><td><font color=ffffff><b>Название</b></font></td><td><font color=ffffff><b>Цена</b></font></td><td><font color=ffffff><b>Купить</b></font></td></tr>';

	   $line=5;	   
	   if (isset($_POST['in_id']) and $_POST['in_id']>0 )
	   {			
			$pg = 1;
			$sql = "SELECT gif.id, gif.img, gif.name, gif.race AS race, gif.item_cost, gif.type FROM game_items_factsheet gif JOIN game_shop_items gsi ON gif.id=gsi.items_id WHERE gif.id='".$_POST['in_id']."' AND gsi.shop_id='".$shop['id']."' ORDER BY BINARY gif.name";
	   }
	   else
	   {
			$query="SELECT COUNT(*) FROM game_items_factsheet JOIN game_shop_items ON game_items_factsheet.id=game_shop_items.items_id WHERE game_items_factsheet.type='".$type."' AND game_shop_items.shop_id='".$shop['id']."'";
			$pg=mysql_result(myquery($query),0,0);
			$sql = "SELECT gif.id, gif.img AS img, gif.name, gif.race, gif.item_cost, gif.type FROM game_items_factsheet gif JOIN game_shop_items gsi ON gif.id=gsi.items_id WHERE gif.type='".$type."' AND gsi.shop_id='".$shop['id']."' ORDER BY BINARY gif.name ASC limit ".(($page-1)*$line).", $line";
	   }	   
	   $allpage=ceil($pg/$line);
	   if ($page>$allpage) $page=$allpage;
	   if ($page<1) $page=1;	   
	   $result=myquery($sql);
	   if ($result!=false AND mysql_num_rows($result)>0)
	   {			
			while($row=mysql_fetch_array($result))
		    {
				$type = $row['type'];				
				echo '<form method="POST" action="shop.php?type='.$type.'&buy='.$row["id"].'">';				
				$Item = new Item();
				echo'<tr><td align="center">';
				$Item->hint($row['id'],0,'<a href="http://'.domain_name.'/info/?item='.$row['id'].'" target="_blank" ',1); 
				echo '<img src="http://'.img_domain.'/item/'.$row["img"].'.gif" border="0" alt="Посмотреть характеристики"></a></td><td>'.$row["name"].'';
				if($row["race"] != 0)
				{
					echo' (Только для расы: <font color=ff0000><b>'.mysqlresult(myquery("SELECT name FROM game_har WHERE id=".$row['race'].""),0,0).'</b></font>)';
				}
				echo'</td><td align="center">'.(round(($row["item_cost"]/100)*$shop["cena_prod"],2)).'</td>';
				echo'<td align="center"><input type="text" value="1" size="1" maxlength="3" name="kol"> <input type="submit" value="Купить" name="buy"></td></tr>';
				echo '</form>';
		    }
	   }
	   echo '<tr align=center><td colspan=4>';
	   $href = '?type='.$type.'&';
	   echo'<center>Страница: ';
	   show_page($page,$allpage,$href);
	   $all=$pg;
	   echo'<br>(Всего предметов: '.$all.')</td></tr></table>';
   }
}

if (!isset($_GET['type']) and !isset($_GET['buy']) and !isset($_GET['sell']) and !isset($_GET['ident']) and !isset($_GET['kleymo']) and !isset($_GET['remont']))
{
	echo '<center>'.$shop['ind'].'</center>';
}

if (isset($_GET['sell']))
{
    if (isset($_GET['sellitem']) and is_numeric($_GET['sellitem']))
    {
        if (isset($_POST['sell']))
		{
			$it_id = (int)$_GET['sellitem'];
			$kol = (int)$_POST['kol'];
			if ($it_id>0 and $kol>0)
			{				
				$Item = new Item($it_id);
				$ar = $Item->sell(0, $kol);
				if ($ar[0]>=0)
				{
					if ($shop['view']==1 AND $ar[0]>0)
					{
						$time=time();
						save_stat($user_id,'','',11,$shop['id'],$ar[2],'',$ar[0],'','','','');
					}
					myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time, folder) VALUES ('".$user_id."', '0', 'Продажа в магазине', 'Ты ".echo_sex('продал','продала')." предмет <".$ar[2]."> - ".$kol." шт. торговцу ".$shop['name']." за ".$ar[0]." ".pluralform($ar[0], 'монету', 'монеты', 'монет')."','0','".time()."',5)");
					$char['GP']+=$ar[0];
					$char['CW']-=$ar[1];
				}
			}
		}
	
	    ?>
	    <script type="text/javascript" src="../js/jquery.js"></script>
	    <script type="text/javascript" src="../suggest_new/jquery.autocomplete.js"></script>
	    <link href="../suggest_new/suggest.css" rel="stylesheet" type="text/css">
	    <script type="text/javascript">	    
		$(document).ready(function() {
			$('#in_name').autocomplete({
				serviceUrl: "../suggest_new/suggest.php?iteminv="+$('#user_id').val(),
				minChars: 3,
				matchSubset: 1,
				autoFill: true,			
				width: 150,
				id: '#in_id'
			});
		});	
	    </script>
	    <?
	   
	   echo'<table width="98%" cellpadding="2" cellspacing="2" border="0">';			
	   echo'<tr align="center"><td colspan="4"><form name="input_form" id="input_form" action="shop.php?sell&sellitem=0" method="POST" >	
	        Введите название предмета: <input id="in_name" name="in_name" type="text" size="20" value="" autocomplete="off"><input id="in_id" name="in_id" type="hidden" size="20" value="0"> 
			<input id="user_id" name="user_id" type="hidden" value="'.$user_id.'"> <input type="submit" name="find" value="Поиск"></form>	        
			</td></tr>';	    
		
		echo '<tr><td colspan=4><font size=2 face=verdana><center>Покупаю на <b>'.$shop['cena_pok'].'%</b> дешевле</font></td></tr>';
        echo'<tr align="center" bgcolor="#303A67"><td><font color=ffffff><b>Рисунок</b></font></td><td><font color=ffffff><b>Название</b></font></td><td><font color=ffffff><b>Цена</b></font></td><td><font color=ffffff><b>Продать</b></font></td></tr>';	  

	   if (!isset($page)) $page=1;
	   $page=(int)$page;
	   $line=5;	   
	   if ((isset($_POST['in_id']) and $_POST['in_id']>0) or (isset($_GET['in_id']) and $_GET['in_id']>0))
	   {			
			if (isset($_POST['in_id']))
			{
				$it_id = $_POST['in_id'];
			}
			else
			{
				$it_id = $_GET['in_id'];
			}
			$query = "SELECT * FROM game_items gi JOIN game_items_factsheet gif ON gi.item_id = gif.id WHERE gi.user_id=".$user_id." AND gi.used=0 and gi.ref_id=0 and gi.priznak=0 AND gi.kleymo=0 AND gif.id='".$it_id."'";		
			$sql = "SELECT gi.id, gi.item_cost AS cena1, gif.oclevel, gif.name, gif.item_cost AS cena2, gif.img, gif.type, gif.item_uselife, gi.item_uselife, gi.kleymo, gi.item_uselife_max, gif.item_uselife_max as uselife_max, (CASE WHEN git.counts=1 THEN gi.count_item ELSE 1 END) as kol
			        FROM game_items gi JOIN game_items_factsheet gif ON gi.item_id = gif.id JOIN game_items_type git ON gif.type=git.id WHERE gi.user_id=".$user_id." AND gi.used=0 and gi.ref_id=0 and gi.priznak=0 AND gi.kleymo=0 AND gif.id='".$it_id."' LIMIT ".(($page-1)*$line).", ".$line."";
	   }
	   else
	   {
			$query = "SELECT * FROM game_items, game_items_factsheet WHERE game_items.user_id=".$user_id." AND game_items.used=0 and game_items.ref_id=0 and game_items.priznak=0 AND game_items_factsheet.id=game_items.item_id AND game_items.kleymo=0";		
			$sql = "SELECT gi.id, gi.item_cost AS cena1, gif.oclevel, gif.name, gif.item_cost AS cena2, gif.img, gif.type, gif.item_uselife, gi.item_uselife, gi.kleymo, gi.item_uselife_max, gif.item_uselife_max as uselife_max, (CASE WHEN git.counts=1 THEN gi.count_item ELSE 1 END) as kol
			        FROM game_items gi JOIN game_items_factsheet gif ON gi.item_id = gif.id JOIN game_items_type git ON gif.type=git.id WHERE gi.user_id=".$user_id." AND gi.used=0 and gi.ref_id=0 and gi.priznak=0 AND gi.kleymo=0 ORDER BY BINARY gif.name LIMIT ".(($page-1)*$line).", ".$line." ";
	   }	
		
		$pg = mysql_num_rows(myquery($query));
		if (!isset($page)) $page=1;
	    $page=(int)$page;
	    $line=5;
		
		$allpage=ceil($pg/$line);
	    if ($page>$allpage) $page=$allpage;
	    if ($page<1) $page=1;
	    
	    $result_items = myquery($sql);
	    if ($result_items!=false AND mysql_num_rows($result_items)>0)
	    {
		    while($items = mysql_fetch_array($result_items))
		    {                
				$cena = round($items['cena2']/100*$shop['cena_pok'],2);
				if ($items['item_uselife']<0) $items['item_uselife']=0;
				if ($items['uselife_max']==0) 
				{
					$items['item_uselife_max']=1;
					$items['uselife_max']=1;
				}
				if ($items['kleymo']>0)
				{
					$cena = 0;
				}
				else
				{
					$cena = round($cena*(($items['item_uselife_max']-1+$items['item_uselife']/100)/$items['uselife_max']),2);
				}                
			    echo '<form method="POST" action="shop.php?sell&sellitem='.$items["id"].'">';
				echo '<tr align="center"><td>';
			    $Item = new Item($items['id']);
			    $Item->hint(0,1,'<span '); 
			    ImageItem($Item->fact['img'],0,$Item->item['kleymo'],"middle","Продать","Продать");
			    echo '</td><td>'.$items['name'];
				$t = "hidden";
				if ($items['kol']>1) 
				{
					echo '<br>('.$items['kol'].' шт.)';
					$t = "text";
				}
				echo '</td><td>'.$cena.'</td>';
				echo '<td><input type="'.$t.'" value="1"  size="1" maxlength="3" name="kol"> <input type="submit" name="sell" value="Продать"></td></tr>';
				echo '</form>';
		    }
	    }
	    else
	    {
		    echo'<tr><td align=center><font size=2 face=verdana><b>В твоем инвентаре нет предметов.</td></tr>';
	    }
	    echo '<tr align=center><td colspan=4>';
	    $href = '?sell&sellitem=0&';
		if (isset($_POST['in_id']))
		{
			$href.='in_id='.$_POST['in_id'].'&';
		}
	    echo'<center>Страница: ';
	    show_page($page,$allpage,$href);
	    $all=$pg;
	    echo'<br>(Всего предметов в инвентаре: '.$all.')</td></tr></table>';
    }
    elseif (isset($_GET['sellres']) and is_numeric($_GET['sellres'])) //Продажа ресурса
    {
        if ($_GET['sellres']>0 and isset($_POST['sellcount']) and is_numeric($_POST['sellcount']))
        {
			$sellcount=(int)$_POST['sellcount'];
			$Res = new Res (0, $_GET['sellres']);
			$Res->sell($sellcount);		
			echo '<center>'.$Res->message.'</center>';				
		}
		
		if ($_GET['sellres']>0 and !isset($_POST['sellcount']))
		{                
			$result_items = myquery("SELECT craft_resource.id AS res_id, craft_resource.name, craft_resource.img1, craft_resource_user.col, craft_resource.incost cena FROM craft_resource, craft_resource_user WHERE craft_resource_user.user_id=".$user_id." AND craft_resource.id=craft_resource_user.res_id AND craft_resource.id=".$_GET['sellres']."");
			if (mysql_num_rows($result_items))
			{
				$items = mysql_fetch_array($result_items);
				echo '<center><br /><br />За 1 единицу ресурса - '.$items['cena'].' '.pluralForm($items['cena'],'монета','монеты','монет').'<br /><br /><br /><br /><form action="?sell&sellres='.$_GET['sellres'].'" method="post">';
				QuoteTable('open');
				echo '<br />&nbsp;<br /><img align="middle" src="http://'.img_domain.'/item/resources/'.$items["img1"].'.gif">&nbsp;&nbsp;&nbsp;&nbsp;'.$items['name'].'&nbsp;&nbsp;&nbsp;&nbsp;Продать: <input type="text" name="sellcount" value="0" size="4" maxlength="4">&nbsp;&nbsp;из&nbsp;&nbsp;'.$items['col'].' ед. <br />&nbsp;<br />';                    
				echo '<center><input type="submit" value="Продать"></form><br/><br>';
				QuoteTable('close');
				echo '<br><br><a href="shop.php?sell&sellres=0">Вернуться</a>';
			}   
        }
        else
        {
			?>
			<script type="text/javascript" src="../js/jquery.js"></script>
			<script type="text/javascript" src="../suggest_new/jquery.autocomplete.js"></script>
			<link href="../suggest_new/suggest.css" rel="stylesheet" type="text/css">
			<script type="text/javascript">	    
			$(document).ready(function() {
				$('#in_name').autocomplete({
					serviceUrl: "../suggest_new/suggest.php?resinv="+$('#user_id').val(),
					minChars: 3,
					matchSubset: 1,
					autoFill: true,			
					width: 150,
					id: '#in_id'
				});
			});	
			</script>
			<?
			   
		    echo'<table width="98%" cellpadding="2" cellspacing="2" border="0">';			
		    echo'<tr align="center"><td colspan="5"><form name="input_form" id="input_form" action="shop.php?sell&sellres=0" method="POST" >	
				 Введите название ресурса: <input id="in_name" name="in_name" type="text" size="20" value="" autocomplete="off"><input id="in_id" name="in_id" type="hidden" size="20" value="0"> 
				 <input id="user_id" name="user_id" type="hidden" value="'.$user_id.'"> <input type="submit" name="find" value="Поиск"></form>	        
				 </td></tr>';	    
			
			echo'<tr align="center" bgcolor="#303A67"><td><font color=ffffff><b>Рисунок</b></font></td><td><font color=ffffff><b>Название</b></font></td><td><font color=ffffff><b>Количество</b></font></td><td><font color=ffffff><b>Цена</b></font></td><td><font color=ffffff><b>Продать</b></font></td></tr>';	  

      $line=5;
			if (isset($_POST['in_id']) and $_POST['in_id']>0 )
		    {			
				$pg = 1;
				$sql = "SELECT cru.res_id, cr.name, cr.incost cena, cr.img1, cru.col FROM craft_resource cr JOIN craft_resource_user cru ON cr.id = cru.res_id WHERE cru.user_id=".$user_id." AND cr.id='".$_POST['in_id']."' ORDER BY binary cr.name";				
		    }
		    else
		    {
				$pg=myquery("SELECT * FROM craft_resource, craft_resource_user WHERE craft_resource_user.user_id=$user_id AND craft_resource.id=craft_resource_user.res_id ORDER BY craft_resource.name");
                $pg=mysql_num_rows($pg);
				$sql = "SELECT cru.res_id, cr.name, cr.incost cena, cr.img1, cru.col FROM craft_resource cr JOIN craft_resource_user cru ON cr.id = cru.res_id WHERE cru.user_id=".$user_id." ORDER BY binary cr.name LIMIT ".(($page-1)*$line).", ".$line." ";
		    }				
			
            $allpage=ceil($pg/$line);
            if ($page>$allpage) $page=$allpage;
            if ($page<1) $page=1;
            
            $result_items = myquery($sql);
            if ($result_items!=false AND mysql_num_rows($result_items)>0)
            {
                while($items = mysql_fetch_array($result_items))
                {
                    echo 
                    '<tr align="center"><td><br /><img src="http://'.img_domain.'/item/resources/'.$items["img1"].'.gif"></td>
                    <td>'.$items['name'].'</td>
                    <td>'.$items['col'].' ед.</td>
                    <td>'.$items['cena'].' '.pluralForm($items['cena'],'монета','монеты','монет').'</td>
                    <td><input type="button" value="Продать" onClick=location.href='."'shop.php?sell&sellres=".$items["res_id"]."'".'></td></tr>';
                }
            }
            else
            {
                echo'<tr><td align=center><font size=2 face=verdana><b>В твоем рюкзаке нет ресурсов.</td></tr>';
            }
            echo '<tr align=center><td colspan=5>';
            $href = '?sell&sellres=0&';
            echo'<center>Страница: ';
            show_page($page,$allpage,$href);
            $all=$pg;
            echo'<br>(Всего ресурсов: '.$all.')</td></tr></table>';
        }
    }
    else
    {
        echo '<br /><br /><br /><br /><br /><center>';
        QuoteTable('open');
        echo '<hr><br /><a href="?sell&sellitem=0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Продать предметы&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>';
        echo '<br /><br /><hr><br />';
        echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="?sell&sellres=0">Продать ресурсы</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br /><br /><hr>';
        QuoteTable('close');
    }
}

if (isset($_GET['ident']) AND $shop['ident']==1)
{
	$ident=(int)$_GET['ident'];

	if ($ident>0)
	{
		$Item = new Item($ident);
		$ar = (int)$Item->identify();
		if ($ar[0]>0)
		{
			$char['GP']-=$ar[0];
			if ($shop['view']==1)
			{
				$time=time();
				//$stat = myquery("INSERT DELAYED INTO game_stat (user_id,item_id,stat_id,gp,shop_id,time) VALUES ('$user_id','".$items['ident']."','12','$cena','".$shop['id']."','$time')");
				save_stat($char['user_id'],'','',12,$shop['id'],$ar[1],'',$ar[0],'','','','');
			}
		}
	}

	$result_items = myquery("SELECT game_items.id,game_items_factsheet.type,game_items_factsheet.img,game_items_factsheet.oclevel FROM game_items,game_items_factsheet WHERE game_items.user_id=".$char['user_id']." AND game_items.used=0 and game_items.ref_id=1 AND game_items.item_id=game_items_factsheet.id AND game_items.priznak=0 ORDER BY game_items_factsheet.type");
	echo'<table width="98%" cellpadding="2" cellspacing="2" border="0">';
	if(mysql_num_rows($result_items))
	{
		while($items = mysql_fetch_array($result_items))
		{
			switch ($items['type'])
			{
				case 1:
					$items['img']='unident/sword3';break;
				case 5:
					$items['img']='unident/armour3';break;
				case 3:
					$items['img']='unident/art3';break;
				case 8:
					$items['img']='unident/belt3';break;
				case 6:
					$items['img']='unident/helmet3';break;
				case 7:
					$items['img']='unident/magic3';break;
				case 2:
					$items['img']='unident/ring3';break;
				case 4:
					$items['img']='unident/shield3';break;
				case 9:
					$items['img']='unident/amulet3';break;
				case 10:
					$items['img']='unident/perch3';break;
				case 11:
					$items['img']='unident/boots3';break;
				case 13:
					$items['img']='unident/eliksir3';break;
				case 14:
					$items['img']='unident/shtan3';break;
				case 15:
					$items['img']='unident/naruchi3';break;
				case 17:
					$items['img']='unident/magic_books3';break;
				case 20:
					$items['img']='unident/schema3';break;
			}
			if ($items['oclevel']==0) $items['oclevel']=$char['clevel'];
			if ($items['oclevel']<=10) $cena = round($items['oclevel']*0.5,0);
			else $cena = $items['oclevel'];
			echo '<tr><td>';
			ImageItem($items['img'],0,0);
			echo '</td>
			<td>'.$items['type'].'</td>
			<td><input type="button" value="Идентифицировать за '.$cena.' монет" onClick=\'location.href="?ident='.$items["id"].'"\'></td></tr>';
		}
	}
	else
	{
		echo'<tr><td align=center><font size=2 face=verdana><b>У тебя нет не идентифицированных предметов</td></tr>';
	}
	echo'</table>';
}

if (isset($_GET['kleymo']) AND $shop['kleymo']==1)
{
	$is_glava = false;
	if (mysql_num_rows(myquery("SELECT clan_id FROM game_clans WHERE glava=$user_id AND raz=0"))>0)
	{
		$is_glava = true;
	}
	
	$kleymo=(int)$_GET['kleymo'];
	
	if ($kleymo>0 AND (isset($_GET['user']) OR isset($_GET['clan'])))
	{
		$type_kleymo = 0;
		$unset = 0;
		if (isset($_GET['del_kleymo'])) $unset = 1;
		if (isset($_GET['user']))
		{
			$type_kleymo = 2;//личное клеймение
		}
		elseif (isset($_GET['clan']) AND $is_glava)
		{
			$type_kleymo = 1;//клановое клеймение
		}
		if ($type_kleymo>0)
		{
			$Item = new Item($kleymo);
			$ar = $Item->kleymo($type_kleymo,$unset);
			if ($ar[0]>0)
			{
				$char['GP']-=$ar[0];
			}
		}
	}
	
	$result_items = myquery("SELECT game_items.id,game_items_factsheet.type,game_items_factsheet.name,game_items_factsheet.img,game_items_factsheet.oclevel FROM game_items,game_items_factsheet WHERE game_items.user_id=".$char['user_id']." AND game_items.used=0 and game_items.ref_id=0 AND game_items.item_id=game_items_factsheet.id AND game_items.priznak=0 AND game_items_factsheet.personal=0 AND game_items.kleymo=0 AND game_items_factsheet.type NOT IN (12,13,19,21) ORDER BY game_items_factsheet.type, binary game_items_factsheet.name");
	$result_items_kleymo = myquery("SELECT game_items.id,game_items_factsheet.type,game_items_factsheet.name,game_items_factsheet.img,game_items_factsheet.oclevel,game_items.kleymo,game_items.kleymo_id FROM game_items,game_items_factsheet WHERE game_items.user_id=".$char['user_id']." AND game_items.used=0 and game_items.ref_id=0 AND game_items.item_id=game_items_factsheet.id AND game_items_factsheet.personal=0 AND game_items.priznak=0 AND game_items.kleymo<>0 AND game_items_factsheet.type NOT IN (12,13,19,21) ORDER BY game_items_factsheet.type, binary game_items_factsheet.name");
	echo'<table width="98%" cellpadding="2" cellspacing="1" border="1">';
	if(mysql_num_rows($result_items)>0 OR mysql_num_rows($result_items_kleymo)>0)
	{
		$lev_us=myquery("(SELECT game_users.clevel FROM game_users WHERE user_id=$user_id) UNION (SELECT game_users_archive.clevel FROM game_users_archive WHERE user_id=$user_id) LIMIT 1");
		$lev_us=mysql_fetch_array($lev_us);
		while($items = mysql_fetch_array($result_items))
		{

			$cena = $lev_us['clevel'];
			echo '<tr><td>';
			ImageItem($items['img'],0,0);
			echo '</td>
			<td>'.$items['name'].'</td>
			<td><input type="button" style="width:270px;" value="Поставить личное клеймо за '.$cena.' монет" onClick=\'location.href="?user&kleymo='.$items["id"].'"\'>';
			if ($is_glava)
			{
				$cena = 20+$items['oclevel'];
				echo '<br /><br /><input style="width:270px;" type="button" value="Поставить клановое клеймо за '.$cena.' монет" onClick=\'location.href="?clan&kleymo='.$items["id"].'"\'>';
			}
			echo '</td></tr>';
		}
		while($items = mysql_fetch_array($result_items_kleymo))
		{
			if (($items['kleymo']==2)AND($items['kleymo_id']!=$user_id)) continue;
			if (($items['kleymo']==1)AND((!$is_glava) OR ($items['kleymo_id']!=$char['clan_id']))) continue;
			$cena = $lev_us['clevel']*2;
			echo '<tr><td>';
			ImageItem($items['img'],0,0);
			echo '</td>
			<td>'.$items['name'].'</td>
			<td>';
			if ($items['kleymo']==2) echo '<input type="button" style="width:270px;" value="Снять личное клеймо за '.$cena.' монет" onClick=\'location.href="?user&del_kleymo&kleymo='.$items["id"].'"\'>';
			if ($is_glava AND $items['kleymo']==1)
			{
				$cena = (20+$items['oclevel'])*2;
				echo '<br /><br /><input style="width:270px;" type="button" value="Снять клановое клеймо за '.$cena.' монет" onClick=\'location.href="?clan&del_kleymo&kleymo='.$items["id"].'"\'>';
			}
			echo '</td></tr>';
		}
	}
	else
	{
		echo'<tr><td align=center><font size=2 face=verdana><b>У тебя нет незаклейменных предметов</td></tr>';
	}
	echo'</table>';
}

if (isset($_GET['remont']) AND $shop['remont']==1)
{
	$remont=(int)$remont;

	if (isset($remont1))
	{
		//выбрали предмет для ремонта
		if (isset($remont2))
		{
			$Item = new Item($remont);
			$ar = $Item->repair();
			if ($ar[0]>0)
			{
				if ($shop['view']==1)
				{
					$time=time();
					//$stat = myquery("INSERT DELAYED INTO game_stat (user_id,item_id,stat_id,gp,shop_id,time) VALUES ('$user_id','".$items['ident']."','15','$cena','".$shop['id']."','$time')");
					save_stat($user_id,'','',15,$shop['id'],$ar[1],'',$ar[0],'','','','');
				}
			}
		}
		else
		{
			//сначала получим подтверждение от игрока
			$it=myquery("select game_items_factsheet.name,game_items.item_uselife,game_items_factsheet.oclevel,game_items_factsheet.item_cost from game_items,game_items_factsheet where game_items.user_id='$user_id' and game_items_factsheet.type<90 and game_items_factsheet.type NOT IN (12,13,19,20,21) and game_items.item_uselife<100 and (game_items.used=0 or game_items.item_uselife>=10) and game_items.id=$remont and game_items_factsheet.id=game_items.item_id and game_items.ref_id=0 and game_items.priznak=0");
			if (mysql_num_rows($it))
			{
				$items=mysql_fetch_array($it);
				if ($char['clevel']<5) 
				{
					$cena = 0;
				}
				elseif ($items['oclevel']<15)
				{
					$cena = 5;
				}
				else 
				{
					$cena = 15;
				}
				//Старый расчёт стоимости починки предмета
				/* 
				$cena=100-$items['item_uselife'];
				if ($items['oclevel']<=0) $items['oclevel']=$char['clevel'];
				if ($items['item_uselife']>75) $cena=$cena*$items['oclevel']*0.1/2;
				elseif ($items['item_uselife']>50) $cena=$cena*$items['oclevel']*0.15/2;
				elseif ($items['item_uselife']>25) $cena=$cena*$items['oclevel']*0.2/2;
				elseif ($items['item_uselife']>0) $cena=$cena*$items['oclevel']*0.25/2;
				elseif ($items['item_uselife']<=0) $cena=$cena*$items['oclevel']*0.5/2;
				if ($cena>($items['item_cost']*0.75) and $items['item_cost']>0) $cena=$items['item_cost']*0.75;
				$cena = round($cena,2);
				if ($char['win']>$char['lose']*3)
				{
					$cena=round($cena*0.75,2);
				}
				elseif ($char['win']>$char['lose'])
				{
					$cena=round($cena*0.9,2);
				}
                if ($cena<1) $cena=1;
				$da = getdate();
				if ($da['mon']==7 AND $da['mday']==15)
				{
					$cena = 0;
				}
				if ($da['mon']==12 AND $da['mday']==31)
				{
					$cena = 0;
				}
				if ($da['mon']==1 AND $da['mday']>=1 AND $da['mday']<=7)
				{
					$cena = 0;
				}
				if ($char['clevel']<5)
				{
					$cena = 0;
				}
				*/
				echo "<center><br><font size = 2><b>Стоимость ремонта твоего предмета <br><font size = 2 color=#FF0000>&quot;$items[name]&quot;</font><br> составляет <font size = 2 color=#FF0000>$cena</font> монет</b></font>";

				
				if ($items['item_uselife']>75)
				{
					echo '<br><br><center>Твоя вещь почти новая, точно чинить?<br><br>';
					echo '<button onClick="location.href=\'?remont='.$remont.'&remont1&remont2\'">ДА, отремонтируйте</button>';
					echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
					echo '<button onClick="location.href=\'?remont\'">НЕТ, так похожу</button>';
				}
				elseif ($items['item_uselife']>50)
				{
					echo '<br><br><center>Потрепано, но ничего - поправим, даешь или еще поносишь?<br><br>';
					echo '<button onClick="location.href=\'?remont='.$remont.'&remont1&remont2\'">ДА, отремонтируйте</button>';
					echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
					echo '<button onClick="location.href=\'?remont\'">НЕТ, буду носить пока</button>';
				}
				elseif ($items['item_uselife']>25)
				{
					echo '<br><br><center>Видно настоящего воина, тут латать и латать... недешево тебе обойдется. Так что чинить?<br><br>';
					echo '<button onClick="location.href=\'?remont='.$remont.'&remont1&remont2\'">ДА, отремонтируйте</button>';
					echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
					echo '<button onClick="location.href=\'?remont\'">НЕТ, я передумал</button>';
				}
				elseif ($items['item_uselife']>0)
				{
					echo '<br><br><center>Где ж это тебя так угораздило? На мою наковальню и то меньше ударов пришлось, тут работать и работать... ну что мне браться за дело?<br><br>';
					echo '<button onClick="location.href=\'?remont='.$remont.'&remont1&remont2\'">ДА, отремонтируйте</button>';
					echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
					echo '<button onClick="location.href=\'?remont\'">НЕТ, не стоит</button>';
				}
				elseif ($items['item_uselife']<=0)
				{
					echo '<br><br><center><b>При ремонте предмета с отрицательной прочностью есть 5% шанс потерять дополнительную долговечность предмета!</b><br><br>';
					echo '<button onClick="location.href=\'?remont='.$remont.'&remont1&remont2\'">ДА, отремонтируйте</button>';
					echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
					echo '<button onClick="location.href=\'?remont\'">НЕТ, я лучше его выкину</button>';
				}
			}
		}
	}
	else
	{
		echo'<table width="98%" cellpadding="2" cellspacing="2" border="0">';

		$it_all=myquery("select game_items.id,game_items_factsheet.img,game_items_factsheet.name,game_items.item_uselife,game_items_factsheet.oclevel,game_items_factsheet.item_cost from game_items,game_items_factsheet where game_items.user_id='$user_id' and game_items_factsheet.type NOT IN (12,13,19,20,21) and game_items_factsheet.type<90 and game_items.item_uselife<100 and (game_items.used=0 OR game_items.item_uselife >=10) and game_items_factsheet.id=game_items.item_id and game_items.priznak=0 and game_items.ref_id=0 ORDER BY BINARY game_items.item_uselife, game_items_factsheet.name");
		if(mysql_num_rows($it_all))
		{
			while($items = mysql_fetch_array($it_all))
			{
				echo '<tr><td>';
				$Item = new Item($items['id']);
				$Item->hint(0,1,'<span '); 
				ImageItem($Item->fact['img'],0,$Item->item['kleymo'],"middle","Ремонтировать","Ремонтировать");
				echo '</td>
				<td ';
				if ($items['item_uselife']==0) echo ' bgcolor=#800000';
				echo'>'.$items['name'].' - Прочность '.$items['item_uselife'].'%';
				echo'</td>
				<td><input type="button" value="Ремонт" onClick=\'location.href="?remont1&remont='.$items["id"].'"\'></td></tr>';
			}
		}
		else
		{
			echo'<tr><td><center><font face=verdana size=2><b>У тебя нет сломаных предметов, или ты их не '.echo_sex('снял','сняла').' с себя</b></td></tr>';
		}
		echo'</table>';
	}
}
?>
</TD>
<TD><IMG SRC="http://<?php echo img_domain; ?>/shops/shop/it_28.jpg" WIDTH=56 HEIGHT=115 ALT=""></TD>

<TD valign="top"><div align="center"><img src="http://<?php echo img_domain; ?>/shop/<? echo $shop['name_img']; ?>.gif"><br><font face=verdana size=1><? echo $shop['name']; ?></font></div></TD>
				<TD>
						<IMG SRC="http://<?php echo img_domain; ?>/shops/shop/it_30.jpg" WIDTH=46 HEIGHT=115 ALT=""></TD>
		</TR>
		<TR>
				<TD>
						<IMG SRC="http://<?php echo img_domain; ?>/shops/shop/it_31.jpg" WIDTH=109 HEIGHT=51 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo img_domain; ?>/shops/shop/it_32.jpg" WIDTH=56 HEIGHT=51 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo img_domain; ?>/shops/shop/it_33.jpg" WIDTH=135 HEIGHT=51 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo img_domain; ?>/shops/shop/it_34.jpg" WIDTH=46 HEIGHT=51 ALT=""></TD>
		</TR>
		<TR>
				<TD>
						<IMG SRC="http://<?php echo img_domain; ?>/shops/shop/it_35.jpg" WIDTH=109 HEIGHT=68 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo img_domain; ?>/shops/shop/it_36.jpg" WIDTH=56 HEIGHT=68 ALT=""></TD>
  <TD valign="middle"><div align="center">Вес: <b><? echo $char['CW'].' / '.$char['CC']; ?></b><br><b><img src="http://<?php echo img_domain; ?>/nav/gold.gif"><font color=ff0000>
				  <? echo $char['GP']; ?></b></font> золотых</div></TD>
				<TD>
						<IMG SRC="http://<?php echo img_domain; ?>/shops/shop/it_38.jpg" WIDTH=46 HEIGHT=68 ALT=""></TD>
		</TR>
		<TR>
				<TD>
						<IMG SRC="http://<?php echo img_domain; ?>/shops/shop/it_39.jpg" WIDTH=109 HEIGHT=145 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo img_domain; ?>/shops/shop/it_40.jpg" WIDTH=56 HEIGHT=145 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo img_domain; ?>/shops/shop/it_41.jpg" WIDTH=135 HEIGHT=145 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo img_domain; ?>/shops/shop/it_42.jpg" WIDTH=46 HEIGHT=145 ALT=""></TD>
		</TR>
		<TR>
				<TD>
						<IMG SRC="http://<?php echo img_domain; ?>/shops/shop/it_43.jpg" WIDTH=109 HEIGHT=69 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo img_domain; ?>/shops/shop/it_44.jpg" WIDTH=214 HEIGHT=69 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo img_domain; ?>/shops/shop/it_45.jpg" WIDTH=53 HEIGHT=69 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo img_domain; ?>/shops/shop/it_46.jpg" WIDTH=207 HEIGHT=69 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo img_domain; ?>/shops/shop/it_47.jpg" WIDTH=56 HEIGHT=69 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo img_domain; ?>/shops/shop/it_48.jpg" WIDTH=135 HEIGHT=69 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo img_domain; ?>/shops/shop/it_49.jpg" WIDTH=46 HEIGHT=69 ALT=""></TD>
		</TR>
</TABLE>
<?
	set_delay_reason_id($user_id,3);
	ForceFunc($user_id,3);
}
else
{
	echo'На этой гексе нет торговцев';
}

show_debug($char['name']);

?>
</BODY>
</HTML>
<?
mysql_close();

if (function_exists("save_debug")) save_debug(); 

?>