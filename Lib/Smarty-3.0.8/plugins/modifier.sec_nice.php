<?php
/**
 * Smarty plugin
 * 
 * @package Smarty
 * @subpackage PluginsModifier
 */

function smarty_modifier_sec_nice( $num )
{
    $num = (int) $num;
    
    $min = 0;
    $sec = 0;
    
    if( $num >= 60 ) {
        $min = floor( $num / 60 );
    }
    
    $sec = $num - $min * 60;

    return sprintf( '%02d:%02d', $min, $sec );
}
