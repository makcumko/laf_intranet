<?php
/**
 * Smarty plugin
 *
 * @package Smarty
 * @subpackage PluginsModifier
 */

/**
 * Делаем из суммы в копейках красивую строку в рублях
 *
 * Type:     modifier<br>
 * Name:     cop2ua<br>
 * Purpose:  Делаем из суммы в копейках красивую строку в гривнах
 *
 * @param string $
 * @return string
 */
function smarty_modifier_cop2ua($string, $style='default')
{
	if ($style == 'default') {
		$cop = intval($string);
		$rub = $cop / 100;

	    return number_format($rub, 2, '.', ' ');
    } elseif ($style == 'long') {
    	$result = "";
		$cop = intval($string);
		$rub = floor($cop / 100);
		$cop = $cop - $rub*100;

		$mil = floor($rub / 1000000);
		if ($mil > 0) {
			$result .= " ".__cop2ua_num2str($mil, "мільйон|мільйона|мільйонив");
			$rub -= $mil*1000000;
		}

		$thous = floor($rub / 1000);
		if ($thous > 0) {
			$result .= " ".__cop2ua_num2str($thous, "тисяча|тисячи|тисяч");
			$rub -= $thous*1000;
		}

		if (!$result || $rub > 0) {
			$result .= " ".__cop2ua_num2str($rub, "гривна|гривнi|гривень");
		}

		$result .= " ".__cop2ua_num2str($cop, "копійка|копійки|копійок", false);

		return trim($result);
    }
}


function __cop2ua_num2str($amount, $strings="", $makeText=true, $addZeros=true) {
	$result = '';
	$amount = intval($amount);
	$ed = array('ноль', 'одна', 'дви', 'три', 'чотири', 'п\'ять', 'шість', 'сім', 'вісім', 'дев\'ять');
	$el = array('десять', 'одинадцять', 'дванадцять', '	тринадцять', 'чотирнадцять', 'п\'ятнадцять', 'шістнадцять', 'сімнадцять', 'вісімнадцять', 'дев\'ятнадцять');
	$dc = array('', '', 'двадцять', 'тридцять', 'сорок', 'п\'ятдесят', 'шісдесят', 'сімдесят', 'вісімдесят', 'дев\'яносто');
	$hn = array('', 'сто', 'двісті', 'тристо', 'чотиристо', 'п\'ятсот', 'шістьсот', 'сімсот', 'вісісот', 'дев\'ятсот');

	// number
	if ($makeText) {
		$hundreds = floor($amount / 100);
		if ($hundreds) $result .= ' '.$hn[$hundreds];
		$rest = $amount - $hundreds*100;

		$decs = floor($rest / 10);
		if ($decs >= 2) $result .= ' '.$dc[$decs];
		elseif ($decs == 1) $result .= ' '.$el[$rest-10];
		$rest -= $decs*10;

		if ($decs != 1 && ($rest || !$amount)) $result .= ' '.$ed[$rest];
	} else {
		$dec = 0;
		$rest = $amount;
		if ($amount < 10 && $addZeros) $result .= '0';
		$result .= $amount;
	}

	// quantifier
	if ($strings) {
		$q = explode("|", $strings);
		$result .= " ";
		if ($rest == 1 && $decs != 1) $result .= $q[0];
		elseif (($rest == 2 || $rest == 3 || $rest == 4) && $decs != 1) $result .= $q[1];
		else $result .= $q[2];
	}

	return trim($result);
}

?>