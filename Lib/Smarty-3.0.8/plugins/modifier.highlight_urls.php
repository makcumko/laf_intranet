<?php
/**
 * @author Alexey Raksha <alexey@zzzaaa.ru>
 */
function smarty_modifier_highlight_urls($string){
	$returnValue = preg_replace_callback("/((www\.|https*:\/\/)+(\w|\d|[-\/\?\.\&\%\=\+\#\,\;\:\(\)\*\~])+)/i", function($math){
		$link = $math[1];
		if (strlen($link) > 38) $link = substr($link, 0, 35) . '...';
		if (strlen($link) < 4 || substr($link, 0, 4) != 'http') $math[1] = 'http://'.$math[1];
		return "<a href='{$math[1]}' target='_blank'>{$link}</a>";
	}, $string);
	return $returnValue;
}