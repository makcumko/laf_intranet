<?php
/**
 * Smarty plugin
 * 
 * @package Smarty
 * @subpackage PluginsModifier
 */

/**
 * Переименовываем тариф в более благородный вид (если получается конечно)
 * 
 * Type:     modifier<br>
 * Name:     tarif_rename<br>
 * Purpose:  Добавляем количество операторов в название тарифа
 * 
 * @param string $ 
 * @return string 
 */
function smarty_modifier_tarif_rename($string, $count = 1, $showCount = true)
{
//	return $string." - ".$count." - ".mb_strtolower(mb_substr($string, 0, 8));

    if (!$showCount) return $string;

	if (mb_strtolower(mb_substr($string, 0, 8)) == "оператор") {
		$result = __cop2rub_num2str($count, "оператор|оператора|операторов", false, false);
		return trim($result.mb_substr($string, 8));
	} else return trim("{$string} ({$count})");
} 

?>