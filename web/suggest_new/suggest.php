<?php
if (isset($_GET['query']))
{
	$dirclass="../class";
	include('../inc/config.inc.php');
	include('../inc/lib.inc.php');
	require_once('../inc/db.inc.php');
	DbConnect();
	
	$keyword = $_GET['query'];	
	$keyword = iconv("UTF-8","Windows-1251//IGNORE",$keyword);
	$keyword = mysql_escape_string($keyword);
	$keyword = trim($keyword);
	 
	if(strstr($keyword,','))
	{      
		$keyword = substr(strrpos($keyword,','), 0);
		$keyword = trim($keyword);
	}
	
	$k1 = "name";
	$k2 = "id";
	$k3 = "type";
	if (isset($_GET['all_sym']))
	{
		$find = "LIKE '%".$keyword."%'";
	}
	else
	{
		$find = "LIKE '".$keyword."%'";
	}
	if (isset($_GET['item']))
	{
		$que = "SELECT name, id, '0' as type FROM game_items_factsheet WHERE name ".$find." ORDER BY BINARY name";
	}
	elseif (isset($_GET['itemshop']))
	{		
		$que = "SELECT gif.name, gif.id, '0' as type FROM game_items_factsheet gif JOIN game_shop_items gsi ON gif.id = gsi.items_id WHERE gif.name ".$find." and gsi.shop_id = '".$_GET['itemshop']."' ORDER BY BINARY name";
	}
	elseif (isset($_GET['iteminv']))
	{		
		$que = "SELECT DISTINCT gif.name, gif.id, '0' as type FROM game_items_factsheet gif JOIN game_items gi ON gif.id = gi.item_id WHERE gif.name ".$find." and gi.user_id = '".$_GET['iteminv']."' ORDER BY BINARY name";
	}
	elseif (isset($_GET['res']))
	{
		$que = "SELECT name, id, '0' as type FROM craft_resource WHERE name ".$find." ORDER BY BINARY name";		
	}
	elseif (isset($_GET['resinv']))
	{
		$que = "SELECT cr.name, cr.id, '0' as type FROM craft_resource cr JOIN craft_resource_user cru ON cr.id = cru.res_id WHERE cr.name ".$find." and cru.user_id = '".$_GET['resinv']."' ORDER BY BINARY name";		
	}
	elseif (isset($_GET['npc']))
	{
		$que = "SELECT npc_name as name, npc_id as id, '0' as type FROM game_npc_template WHERE npc_name ".$find." ORDER BY BINARY name";
	}
	elseif (isset($_GET['itemres']))
	{
		$que = "(SELECT name, id, '0' as type FROM game_items_factsheet WHERE name ".$find.") 
	            UNION ALL 
				(SELECT name, id, '1' as type FROM craft_resource WHERE name ".$find.") 
				ORDER BY BINARY name";	
	}
	else
	{
		$que = "(SELECT name, user_id as id, '0' as type FROM game_users WHERE name ".$find.") 
	            UNION ALL 
				(SELECT name, user_id as id, '0' as type FROM game_users_archive WHERE name ".$find.") 
				ORDER BY BINARY name";		
	}
	$rs = myquery($que);
	$rs_cnt = mysql_num_rows($rs);
	
	$keyword = iconv("Windows-1251","UTF-8//IGNORE",$keyword);
	$json_str="{query:'".$keyword."', suggestions:[";
	if ($rs_cnt > 0)
	{
		$i = 0;
		while ($row = mysql_fetch_array($rs) )
		{
			$row[$k1] = iconv("Windows-1251","UTF-8//IGNORE",$row[$k1]);
			if ($i != 0) $json_str.=", ";
			$json_str.="{'name': '".$row[$k1]."', 'id': '".$row[$k2]."', 'type': '".$row[$k3]."'}";
			$i++;
		}
	}	
	$json_str.="]}";	 
	echo $json_str;
	
	mysql_close();
}
?>