<?php
/**
 * Smarty plugin
 * 
 * @package Smarty
 * @subpackage PluginsModifier
 * @author Slava Yumin
 */

function smarty_modifier_sort(array $array, $field, $order = 'ASC')
{
	usort( $array, function($a, $b)use($order, $field){
		return $order === 'ASC' ? ($a[$field] > $b[$field]) : ($a[$field] < $b[$field]) ;
	} );

	return $array;
} 

?>