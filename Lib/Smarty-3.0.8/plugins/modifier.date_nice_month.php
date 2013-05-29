<?php
/**
 * Smarty plugin
 * 
 * @package Smarty
 * @subpackage PluginsModifier
 */

function smarty_modifier_date_nice_month($string, $rodit=false) {
	$months = array(
		1 => array("янв", "Январь"),
		2 => array("фев", "Февраль"),
		3 => array("мар", "Март"),
		4 => array("апр", "Апрель"),
		5 => array("май", "Май"),
		6 => array("июн", "Июнь"),
		7 => array("июл", "Июль"),
		8 => array("авг", "Август"),
		9 => array("сен", "Сентябрь"),
		10 => array("окт", "Октябрь"),
		11 => array("ноя", "Ноябрь"),
		12 => array("дек", "Декабрь"),
	);

    require_once(SMARTY_PLUGINS_DIR . 'shared.make_timestamp.php');
    if ($string != '') {
        $timestamp = smarty_make_timestamp($string);
    } else {
        return;
    } 

    return $months[date("n", $timestamp)][1]." ".date('Y г.', $timestamp);
} 

?>