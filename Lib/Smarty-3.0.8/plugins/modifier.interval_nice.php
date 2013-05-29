<?php
/**
 * отображение интервалов в читабельном виде
 * @author Alexey Raksha <alexey@zzzaaa.ru>
 */
function smarty_modifier_interval_nice($interval){
	$text = '';
	if (preg_match("/(\d{2}):(\d{2}):(\d{2})\.*/iU", $interval, $math)){
		$hour = intval($math[1]);
		$min = intval($math[2]);
		$sec = intval($math[3]);

		if ($min && $sec > 30) $min++;

		if ($hour) $text = "{$hour} ч. ";
		if ($min) $text .= "{$min} мин.";

		if (!$hour && !$min && $sec) $text = 'менее минуты';

	}
	if (empty($text)) $text = 'менее секунды';
	return $text;
}