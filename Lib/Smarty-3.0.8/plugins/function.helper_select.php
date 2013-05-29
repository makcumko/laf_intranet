<?php
/**
 * Выводит select-список на экран с заданными параметрами из массива
 * 
 * @param array $params 
 * *[from]: 			array - массив строк, из которых берутся значения 
 * *[key]: 				название ячейки, которая является ключом
 * *[val]: 				название ячейки, которая является значением
 * [name, id, class]: 	HTML-атрибуты списка
 * [selid]: 			значение [key], которое является выбранным
 * [first_key]: 		ключ первой строки
 * [first_string]: 		значение первой строки		
 * * - обязательные поля
 * 
 * @param $template Не используется
 * @return string
 */
function smarty_function_helper_select($params, $template)
{	
	// Обязательные атрибуты	 
	if (!$params["key"] || !$params["val"]) trigger_error
	(
		"function.helper_select: not found attribute", 
		E_USER_NOTICE
	);
	
	// Первая строка может быть произвольной. Например - "все" с соответствущим ключом
	// Она также может быть текущей (selid)
	if ($params["first_val"]) $html_options = sprintf
	(
		'<option value="%s" %s >%s</option>', 
		$params['first_key'], 
		$params['selid'] == $params['first_key'] ? ' selected="selected" ' : '',
		$params['first_val']
	);
	
	// Выводим опции
	if (is_array( $params["from"] )) foreach($params["from"] as $row) $html_options .= sprintf
	(
		'<option value="%s" %s >%s</option>', 
		$row[$params["key"]],														// Ключ опции
		$params['selid'] == $row[$params['key']] ? ' selected="selected" ' : '',	// Выбран || не выбран
		implode																		// Значение/-я опции
		(
			' ', 
			array_map																// Может быть несколько значений
			(																		// опций, разделённых пробелами
				function($v) use ($row) { return $row[$v]; }, 						
				explode(" ", $params["val"])
			)
		)
	);	
	
	// Шапка Select	
	$html_output = sprintf
	(	
		'<select %s %s %s> %s </select>',
		$params['name'] 	? ' name="'. 	$params['name']	.'" ' 	: '',
		$params['id'] 		? ' id="'.		$params['id']	.'" ' 	: '',
		$params['class'] 	? ' class="'.	$params['class'].'" ' 	: '',
		$html_options
	);
	
	return $html_output;	
}