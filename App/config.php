<?php
session_start();

define('ROOT_DIR', realpath($_SERVER['DOCUMENT_ROOT'].'/..').DIRECTORY_SEPARATOR);
define('LIB_DIR', ROOT_DIR.'Lib'.DIRECTORY_SEPARATOR);
define('DATA_DIR', ROOT_DIR.'Data'.DIRECTORY_SEPARATOR);
define('LOG_DIR', ROOT_DIR.'Logs'.DIRECTORY_SEPARATOR);
define('VIEW_DIR', ROOT_DIR.'App/View'.DIRECTORY_SEPARATOR);
define('LOG_REQUESTS', false);
define('FLAG_DEBUG', isset($_SERVER['HTTP_HOST']) && substr($_SERVER['HTTP_HOST'], -6) == '.local');

date_default_timezone_set("Europe/Moscow");
setlocale(LC_ALL, "ru_RU.UTF8");

ini_set('display_errors', true);
ini_set('error_log', LOG_DIR.date("Y.m.d").'.php_error.log');
//ini_set('error_reporting', E_ALL & !E_NOTICE);
//ini_set('error_reporting', FLAG_DEBUG ? E_ALL : E_ALL & !E_NOTICE);
ini_set('error_reporting', E_ALL);
ini_set('log_errors', true);
ini_set('default_charset', 'utf-8');

set_include_path(implode(PATH_SEPARATOR, [ROOT_DIR, DATA_DIR, LIB_DIR, ROOT_DIR . 'Lib/Smarty-3.0.8']));
require_once("ServiceFuncs.php");


spl_autoload_register(function($class) {
	if (strpos( $class, 'Smarty_' ) !== false) {
		$class = strtolower( $class );
	} else {
		$class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
        $class = str_replace('_', DIRECTORY_SEPARATOR, $class);
	}

    if (!include_once($class.'.php'))
    {
        throw new \Exception("Class '{$class}' not found");
    }
});

foreach (scandir(DATA_DIR.DIRECTORY_SEPARATOR."Database") as $dbconfig) {
    if (substr($dbconfig, -7) == "DB.json") {
        try {
            $config = new \App\Model\Data\DatabaseData($dbconfig);
            $adapterName = "\\App\\Model\\DB\\".caseXx($config['type'])."Adapter";
            foreach (['type', 'name', 'host', 'db', 'user'] as $field) {
                if (!isset($config[$field])) throw new Exception("Need to have field {$field}");
            }
            $db = new $adapterName($config);
            \App\Model\Registry::Set($config['name'], $db);
        } catch (Exception $e) {
            throw new Exception("Could not init database from config file {$dbconfig}: ".$e->getMessage());
        }
    }
}
