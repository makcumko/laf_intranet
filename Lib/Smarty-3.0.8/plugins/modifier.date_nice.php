<?php
/**
 * Smarty plugin
 * 
 * @package Smarty
 * @subpackage PluginsModifier
 */

function smarty_modifier_date_nice($string, $date=false, $short=false) {
	$months = array(
		1 => array("янв", "января"),
		2 => array("фев", "февраля"),
		3 => array("мар", "марта"),
		4 => array("апр", "апреля"),
		5 => array("май", "мая"),
		6 => array("июн", "июня"),
		7 => array("июл", "июля"),
		8 => array("авг", "августа"),
		9 => array("сен", "сентября"),
		10 => array("окт", "октября"),
		11 => array("ноя", "ноября"),
		12 => array("дек", "декабря"),
	);

    require_once(SMARTY_PLUGINS_DIR . 'shared.make_timestamp.php');
    if ($string != '') {
        $timestamp = smarty_make_timestamp($string);
    } else {
        return;
    } 

    $str = date("d ", $timestamp);
    $str .= $months[date("n", $timestamp)][$short?0:1]." ";
    $str .= date("Y", $timestamp);
    if (!$date) {
    	$str .= date(" H:i", $timestamp);
    }

    return $str;
} 

?>