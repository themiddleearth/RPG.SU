<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['items'] >= 1 and ($char['name']=='mrHawk' or $char['name']=='bruser'))
{
	$check_item=myquery("SELECT * FROM game_items_factsheet WHERE type NOT IN (12, 13, 20, 22, 95, 97, 98, 99) ORDER BY type, BINARY name");
	if (mysql_num_rows($check_item)>0)
	{
		echo '<table border="1"><tr>
		 <td width="200"><b>Предмет</b></td>
		 <td width="50"><b>Тип</b></td>
		 <td width="50"><b>Цена</b></td>
		 <td width="50"><b>Эффект</b></td>
		 <td width="50"><b>Ранд</b></td>
		 <td width="50"><b>Левел</b></td>
		 <td width="50"><b>Тсила</b></td>
		 <td width="50"><b>Тинт</b></td>
		 <td width="50"><b>Тловк</b></td>
		 <td width="50"><b>Тмудр</b></td>
		 <td width="50"><b>Твын</b></td>
		 <td width="50"><b>Тзащ</b></td>
		 <td width="50"><b>Тудач</b></td>
		 <td width="50"><b>Дсила</b></td>
		 <td width="50"><b>Динт</b></td>
		 <td width="50"><b>Дловк</b></td>
		 <td width="50"><b>Дмудр</b></td>
		 <td width="50"><b>Двын</b></td>
		 <td width="50"><b>Дзащ</b></td>
		 <td width="50"><b>Дудач</b></td>
		 <td width="50"><b>Хп</b></td>
		 <td width="50"><b>Мп</b></td>
		 <td width="50"><b>Стм</b></td>
		</tr>'; 
		while ($it=mysql_fetch_array($check_item))
		{
			echo '<tr align="center">';
			echo '<td>'.$it['name'].'</td>';
			echo '<td>'.$it['type'].'</td>';
			echo '<td>'.$it['item_cost'].'</td>';
			echo '<td>'.$it['indx'].'</td>';
			echo '<td>'.$it['deviation'].'</td>';
			echo '<td>'.$it['oclevel'].'</td>';
			echo '<td>'.$it['ostr'].'</td>';
			echo '<td>'.$it['ontl'].'</td>';
			echo '<td>'.$it['opie'].'</td>';
			echo '<td>'.$it['ospd'].'</td>';
			echo '<td>'.$it['odex'].'</td>';
			echo '<td>'.$it['ovit'].'</td>';
			echo '<td>'.$it['olucky'].'</td>';
			echo '<td>'.$it['dstr'].'</td>';
			echo '<td>'.$it['dntl'].'</td>';
			echo '<td>'.$it['dpie'].'</td>';
			echo '<td>'.$it['dspd'].'</td>';
			echo '<td>'.$it['ddex'].'</td>';
			echo '<td>'.$it['dvit'].'</td>';
			echo '<td>'.$it['dlucky'].'</td>';
			echo '<td>'.$it['hp_p'].'</td>';
			echo '<td>'.$it['mp_p'].'</td>';
			echo '<td>'.$it['stm_p'].'</td>';
			echo '</tr>';
		}
		echo '</table>';
	}	
}

if (function_exists("save_debug")) save_debug(); 
?>
</body>
</html>