<?php
$id_item_ring_of_fire = 213;//'������ ����' � ������������
$id_item_monstr_balden = 376;//���������� �������
$id_item_part_monster = 377;//����� �������
$id_item_letter = 379;//������
$id_item_posylka = 381;//�������
$id_item_letter_complete = 380;//������ � ��������������

function kill_quest_npc($npc_id)
{
	$npcid=(int)$npc_id;
	myquery("DELETE FROM game_npc WHERE id=$npcid");
}
?>