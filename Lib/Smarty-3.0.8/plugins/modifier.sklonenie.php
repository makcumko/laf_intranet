<?php
/**
 * @author Alexey Raksha <alexey@zzzaaa.ru>
 */

/**
 * склоняет слова
 * @param int $count число
 * @param string $str склоняемые слова, через запятую
 * @return string
 */
function smarty_modifier_sklonenie($count, $str = 'сообщение,сообщения,сообщений'){

	$array = explode(',', $str);
	$count = "$count";
	$lastNumber = $count[strlen($count) - 1];
	$lastTwo = $count[strlen($count) - 2] . $lastNumber;
//	1 - сообщение
//	2,3, 4 - сообщения
//	5, 6, 7, 8, 9, 10 - сообщений
	if (in_array($lastNumber, array(1)) && $lastTwo != 11) return $array[0];

	if (in_array($lastNumber, array(5,6,7,8,9,0)) ||
		in_array($lastTwo, array(11,12,13,14))) return $array[2];


	if (in_array($lastNumber, array(2,3,4))) return $array[1];
}

//echo smarty_modifier_sklonenie(111);