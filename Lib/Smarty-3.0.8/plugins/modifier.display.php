<?php
/**
 * Smarty plugin
 *
 * @package Smarty
 * @subpackage PluginsModifier
 */

/**
 * Smarty variable secure display
 */
function smarty_modifier_display($str) {
    $str = htmlspecialchars($str);
    $str = preg_replace(
        [
            '/\[img=(.*?)\]/is',
            '/\[a=(.*?)\](.*?)\[\/a\]/is',
            '/\[a=(.*?)\]/is',
        ],
        [
            '<img src="$1"/>',
            '<a href="$1">$2</a>',
            '<a href="$1">$1</a>',
        ],
        $str
    );
    $str = nl2br($str);

    return $str;
}

?>