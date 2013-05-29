<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage PluginsFunction
 */

/**
 * Smarty {plural} function plugin
 *
 * Type:     function<br>
 * Name:     plural<br>
 * Purpose:  outputs plural form
 * @author Anton Permyakov <anton.p@livetex.ru>
 * @param array parameters
 * @param Smarty
 * @param object $template template object
 * @return string|null
 * 
 * Russian language pluralization rules, taken from CLDR project, http://unicode.org/cldr/
 * 
 * one   = 1, 21, 31, 41, 51, 61...
 * few   = 2-4, 22-24, 32-34...
 * many  = 0, 5-20, 25-30, 35-40...
 * other = 1.31, 2.31, 5.31...
 *
 * one -> n mod 10 is 1 and n mod 100 is not 11;
 * few -> n mod 10 in 2..4 and n mod 100 not in 12..14;
 * many -> n mod 10 is 0 or n mod 10 in 5..9 or n mod 100 in 11..14;
 * other -> everything else
 * 
 * 
 */
function smarty_function_plural( $params, $template )
{
    if( !isset ( $params['n'] ))
    {
        $params['n'] = 0;
    }
    
    $key = smarty_get_russian_plural( (int) $params[ 'n' ] );

    if( !isset( $params[ $key ] ))
    {
        $params[ $key ] = '';
    }

    return $params[ $key ];
}

/**
 * Returns one of the plural keys
 * 
 * @param type $num 
 * @return string one, few, many, other
 */
function smarty_get_russian_plural( $n ) 
{
    $n = abs( $n );
    $m10 = $n % 10;
    $m100 = $n % 100;

    if( $m10 == 1 && $m100 != 11 ) {
        return 'one';
    }

    if( $m10 >= 2 && $m10 <= 4 && ( $m100 < 12 || $m100 > 14 )) {
        return 'few';
    }
    
    if( $m10 == 0 || ( $m10 >=5 && $m10 <= 9 ) || ( $m100 >= 11 && $m100 <= 14 )) {
        return 'many';
    }
    
    return 'other'; // not realy used
}
