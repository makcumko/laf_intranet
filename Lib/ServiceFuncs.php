<?php

function caseXx($text) {
//    if ($text) return strtoupper($text{0}).strtolower(substr($text, 1));
    if ($text) return mb_strtoupper(mb_substr($text, 0, 1)).mb_strtolower(mb_substr($text, 1));
}

/**
 * Возвращает дату в норм формате на русском языке
 *
 * @param   string       $string       Входящая дата
 * @param   string       $format        Формат даты
 * d - число
 * m - месяц короткий
 * M - месяц длинный
 * y - год коротко
 * Y - год целиком
 * w - день недели
 * итд
 *
 * @return  string
 */
function date_nice_format($string, $format = "d M Y") {
    $months = [
        1 => ["янв", "января"],
        2 => ["фев", "февраля"],
        3 => ["мар", "марта"],
        4 => ["апр", "апреля"],
        5 => ["май", "мая"],
        6 => ["июн", "июня"],
        7 => ["июл", "июля"],
        8 => ["авг", "августа"],
        9 => ["сен", "сентября"],
        10 => ["окт", "октября"],
        11 => ["ноя", "ноября"],
        12 => ["дек", "декабря"]
    ];

    $days = [
        0 => ["вс", "воскресенье"],
        1 => ["пн", "понедельник"],
        2 => ["вт", "вторник"],
        3 => ["ср", "среда"],
        4 => ["чт", "четверг"],
        5 => ["пт", "пятница"],
        6 => ["сб", "суббота"]
    ];

    if (is_numeric($string)) {
        $timestamp = intval($string);
    } elseif ($string != '') {
        if (!($timestamp = strtotime($string))) return;
    } else {
        return;
    }

    $replaces = [
        "Y" => date("Y", $timestamp),
        "y" => date("y", $timestamp),
        "M" => $months[date("n", $timestamp)][1],
        "m" => $months[date("n", $timestamp)][0],
        "d" => date("d", $timestamp),
        "W" => $days[date("w", $timestamp)][1],
        "w" => $days[date("w", $timestamp)][0],
        "H" => date("H", $timestamp),
        "i" => date("i", $timestamp),
        "s" => date("s", $timestamp),
    ];

    $str = str_replace(array_keys($replaces), array_values($replaces), $format);

    return $str;
}