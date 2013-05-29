<?php
/**
 * Smarty plugin
 * 
 * @package Smarty
 * @subpackage PluginsModifier
 */

function smarty_modifier_date_nice_last($string, $date=false, $short=false) {


    require_once(SMARTY_PLUGINS_DIR . 'shared.make_timestamp.php');
    if ($string != '') {
        $timestamp = $_SERVER['REQUEST_TIME'] - smarty_make_timestamp($string);
    } else {
        return;
    } 

	if ($timestamp < 60) return '1 минуту назад';
	elseif ($timestamp < 60*10) return '10 минут назад';
	elseif ($timestamp < 60*60) return '1 час назад';
	elseif ($timestamp < 60*60*2) return '2 часа назад';
	elseif ($timestamp < 60*60*5) return '5 часов назад';
	elseif ($timestamp < 60*60*10) return '10 часов назад';
	elseif ($timestamp < 60*60*24) return '1 день назад';
	elseif ($timestamp < 60*60*24*2) return '2 дня назад';
	elseif ($timestamp < 60*60*24*3) return '3 дня назад';
	elseif ($timestamp < 60*60*24*7) return '1 неделю назад';
	elseif ($timestamp < 60*60*24*30) return '1 месяц назад';
	else return 'За прошедший год';



} 

?>