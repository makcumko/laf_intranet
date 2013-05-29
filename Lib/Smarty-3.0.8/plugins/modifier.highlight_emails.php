<?php
/**
 * @author Alexey Raksha <alexey@zzzaaa.ru>
 */
function smarty_modifier_highlight_emails($string){
	return preg_replace("/([-\w.]+@([A-z0-9\-_]+\.)+[A-z]{2,15})/i","<a href='mailto:$1'>$1</a>", $string);
}