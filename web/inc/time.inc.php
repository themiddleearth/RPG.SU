<?php
//�������� �������
$user=myquery("delete from game_chat_nakaz where date_zak<=".time());

//�������� ����
$user=myquery("delete from game_ban where time<=".time());
?>