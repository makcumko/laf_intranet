<?php
/**
 * Smarty plugin
 *
 * @package Smarty
 * @subpackage PluginsModifier
 */

/**
 * Smarty replace hashtags with links
 */
function smarty_modifier_parse_tags($str, $url) {
//    $str = htmlspecialchars($str);
    $url = str_replace("#", "$1", $url);
    $str = preg_replace('/\#(\S+)/is', "<a href='{$url}'>#$1</a>", $str);

    return $str;
}

?>