<?php

/**
 *
 * Пример плагина авторизации для phpBB3
 *
 */

if (!defined('IN_PHPBB'))
{
    exit;
}

/**
 * возвращает информацию о текущем пользователе
 */
function get_user_data()
{
    if (!empty($_COOKIE['userinfo_name'])) return [
        'username' => $_COOKIE['userinfo_name'],
        'user_email' => $_COOKIE['userinfo_login'],
        'user_password' => $_COOKIE['userinfo_password'],
    ];

    else return false;
}

/**
 * Функция, отвечающая за авторизацию.
 */
function login_simple(&$username, &$password)
{
    $auth = get_user_data();

    // если авторизация невозможна
    if (!is_array($auth) || empty($auth))
    {
        return array(
            'status' => LOGIN_ERROR_USERNAME,
            // сообщение по ключу ACCESS_DIRECTLY_DENIDED следует добавить в файлах language/<language>/common.php
            'error_msg' => 'ACCESS_DIRECTLY_DENIDED',
            'user_row' => array('user_id' => ANONYMOUS),
        );
    }

    global $db;
    $sql = 'SELECT user_id, username, user_password, user_passchg, user_email, user_type
           FROM ' . USERS_TABLE . "
           WHERE username_clean = '" . $db->sql_escape(utf8_clean_string($auth['username'])) . "'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $db->sql_freeresult($result);

//    var_dump($sql);

    if ($row){
        $res = array(
            'status'     => LOGIN_SUCCESS,
            'error_msg'  => false,
            'user_row'     => array(
                'user_id' => $row['user_id'],
                'username'       => $row['username'],  // Отображаемое имя пользователя
                'user_password'  => $row['user_password'],  // phpbb-хеш пароля
                'user_email'     => $row['user_email'],  // E-mail пользователя, если существует
                'user_type'      => 0,
                'group_id' => 2
            )
        );
        return $res;
    }

    // Сообщаем, что авторизация прошла успешно.
    $res = array(
        'status'     => LOGIN_SUCCESS_CREATE_PROFILE,
        'error_msg'  => false,
        'user_row'     => array(
            "username"       => $auth['username'],  // Отображаемое имя пользователя
            "user_password"  => phpbb_hash($auth['user_password']),  // phpbb-хеш пароля
            "user_email"     => $auth['user_email'],  // E-mail пользователя, если существует
            "user_type"      => 0,
            "group_id" => 2
        ),
    );
    return $res;
}

/**
 * Функция, отвечающая за регистрацию и авторизацию пользователя при первом посещении.
 */
function autologin_simple()
{
    // логин и пароль будет получен в самой функции, передаем заглушку
    $u = "";
    $user_row = login_simple($u, $u);
    // если пользователь еще не зарегистрирован
    if ($user_row['status'] == LOGIN_SUCCESS_CREATE_PROFILE)
    {
        global $phpbb_root_path, $phpEx;
        if (!function_exists('user_add'))
        {
            include($phpbb_root_path . 'includes/functions_user.' . $phpEx);
        }
        $user_row['user_row']['user_id'] = user_add($user_row['user_row']);
    }
    // возвращаем данные пользователя
    global $db;
    $sql = 'SELECT * FROM ' . USERS_TABLE . " WHERE user_id = '" . $db->sql_escape($user_row['user_row']['user_id']) . "'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $db->sql_freeresult($result);
    return $row;
}


?>