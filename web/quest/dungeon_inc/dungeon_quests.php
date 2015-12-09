<?php

//создаем массив квестов для шахт Мории по уровням
$quests[1] = Array(); //квесты 1 уровня
$quests[2] = Array(); //квесты 2 уровня
$quests[3] = Array(); //квесты 3 уровня

$res = array();
$sel_res = myquery("SELECT * FROM craft_resource");
while ($res_arr = mysql_fetch_array($sel_res))
{
	$res[$res_arr['id']]['name'] = $res_arr['name'];
	$res[$res_arr['id']]['weight'] = $res_arr['weight'];
}

//$quests[уровень][номер][тип]|[ИД реса][тип для реса]
$sel_quest = myquery("SELECT * FROM dungeon_quests WHERE quest_id=".$quest_id." and quest_level=".$level."");
while ($q = mysql_fetch_array($sel_quest))
{
	$quests[$q['quest_level']][$quest_id]['name']=$q['name'];
	$quests[$q['quest_level']][$quest_id]['description']=$q['description'];
	$quests[$q['quest_level']][$quest_id]['id']=$q['id'];
	$sel_res = myquery("SELECT * FROM dungeon_quests_res WHERE quest_id=".$q['id']."");
	$nom = 0;
	while ($r = mysql_fetch_array($sel_res))
	{
		$nom++;
		$quests[$q['quest_level']][$quest_id]['res'][$nom]['id'] = $r['res_id'];
		$quests[$q['quest_level']][$quest_id]['res'][$nom]['kol'] = $r['col'];
	}
}

?>