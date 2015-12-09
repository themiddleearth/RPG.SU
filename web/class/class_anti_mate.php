<?php
class anti_mate {
	//latin equivalents for russian letters
  var $view = false;
	var $let_matches = array (
	"a" => "�",
	"c" => "�",
	"e" => "�",
	"k" => "�",
	"m" => "�",
	"o" => "�",
	"x" => "�",
	"y" => "�",
	"�" => "�");
	//bad words array. Regexp's symbols are readable !
	var $bad_words = array (".*��(�|�|�|�|�(�|�)).*", ".*��(�|�)�.*", "���(*|.)", "���(�|�|�).*", "(�|��)��(�|�|�).*", "��.*", ".*���.*", "����.*", ".*��(�|�)(�|�|�|�|�).*", ".*���(�|�).*", ".*���(�|�)�.*", ".*���.*", "�(�|�)����", ".*�����.*",".*�����.*",".*������.*",".*�������.*","���",".*�����.*", ".*ERRNO.*", "fu��");

  function __construct($view = false) {
    if ($view)
      $this->view = true;
  }

	function rand_replace ($text = "")
  {
    if (!empty($text) && $this->view)
      $output = "[censored=".$text."]";
    else
      $output = "[censored]";
		return $output;
	}
	function filter ($string){
		$counter = 0;
		@setlocale(LC_ALL, array ('ru_RU.CP1251', 'rus_RUS.1251'));

		//$pattern = "/\w{0,5}[�x]([�x\s\!@#\$%\^&*+-\|\/]{0,6})[�y]([�y\s\!@#\$%\^&*+-\|\/]{0,6})[�i�e�����]\w{0,7}|\w{0,6}[�p]([�p\s\!@#\$%\^&*+-\|\/]{0,6})[i��]([i��\s\!@#\$%\^&*+-\|\/]{0,6})[3��]([3��\s\!@#\$%\^&*+-\|\/]{0,6})[�d]\w{0,10}|[�cs][�y]([�y\!@#\$%\^&*+-\|\/]{0,6})[4�k�]\w{1,3}|\w{0,4}[b�]([b�\s\!@#\$%\^&*+-\|\/]{0,6})[l�]([l�\s\!@#\$%\^&*+-\|\/]{0,6})[y�]\w{0,10}|\w{0,8}[�][b�][����@e���a][���@���]\w{0,8}|\w{0,4}[�e]([�e\s\!@#\$%\^&*+-\|\/]{0,6})[�b]([�b\s\!@#\$%\^&*+-\|\/]{0,6})[u�]([u�\s\!@#\$%\^&*+-\|\/]{0,6})[�4�]\w{0,4}|\w{0,4}[�e�]([�e�\s\!@#\$%\^&*+-\|\/]{0,6})[�b]([�b\s\!@#\$%\^&*+-\|\/]{0,6})[�n]([�n\s\!@#\$%\^&*+-\|\/]{0,6})[�y]\w{0,4}|\w{0,4}[�e]([�e\s\!@#\$%\^&*+-\|\/]{0,6})[�b]([�b\s\!@#\$%\^&*+-\|\/]{0,6})[�o�a@]([�o�a@\s\!@#\$%\^&*+-\|\/]{0,6})[�n�t]\w{0,4}|\w{0,10}[�]([�\!@#\$%\^&*+-\|\/]{0,6})[�]\w{0,6}|\w{0,4}[p�]([p�\s\!@#\$%\^&*+-\|\/]{0,6})[�e�i]([�e�i\s\!@#\$%\^&*+-\|\/]{0,6})[�d]([�d\s\!@#\$%\^&*+-\|\/]{0,6})[o��a@�e�i]([o��a@�e�i\s\!@#\$%\^&*+-\|\/]{0,6})[�r]\w{0,12}/i";

		$replacement = "[color=red][b][censored][/b][/color]";

		//$string = preg_replace($pattern, $replacement, $string);   
		
		//return $string;
		 
		$elems = explode (" ", $string); //here we explode string to words
		$count_elems = count($elems);
		for ($i=0; $i<$count_elems; $i++)
		{
		$blocked = 0;
		/*formating word...*/
		$str_rep = preg_replace ("/[^a-zA-Z�-��-߸]/", "", strtolower($elems[$i]));
			for ($j=0; $j<strlen($str_rep); $j++)
			{
				foreach ($this->let_matches as $key => $value)
				{
					if ($str_rep[$j] == $key)
					$str_rep[$j] = $value;
				}
			}
		/*done*/

		/*here we are trying to find bad word*/
		/*match in the special array*/
			for ($k=0; $k<count($this->bad_words); $k++)
			{
				if (preg_match("/\*$/", $this->bad_words[$k]))
				{
					if (preg_match("/^".$this->bad_words[$k]."/", $str_rep))
					{
						$elems[$i] = $this->rand_replace($elems[$i]);
						$blocked = 1;
						$counter++;
						break;
					}
				}
				if ($str_rep == $this->bad_words[$k])
				{
					$elems[$i] = $this->rand_replace($elems[$i]);
					$blocked = 1;
					$counter++;
					break;
				}

			}
		}
		if ($counter != 0)
		$string = implode (" ", $elems); //here we implode words in the whole string
		return $string;
	}
}
?>
