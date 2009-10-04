<?php

define('FS_ROOT_DIRECTORY', str_replace('\\', '/', preg_replace('`^(.+)[\\\\/]test[\\\\/]?$`i',
    '$1', __DIR__)));

if (!defined('PATH_TO_ROOT'))
{
    $script = str_replace(FS_ROOT_DIRECTORY, '', str_replace('\\', '/', $_SERVER['SCRIPT_FILENAME']));
    $path_to_root = rtrim(str_repeat('../', substr_count(ltrim($script, '/'), '/')), '/');
    define('PATH_TO_ROOT', !empty($path_to_root) ? $path_to_root : '.');
}

define('DEBUG', true);

require_once PATH_TO_ROOT . '/kernel/framework/functions.inc.php';
require_once PATH_TO_ROOT . '/test/simpletest/autorun.php';

mimport('test/util/test_functions', INC_IMPORT);
mimport('test/util/phpboost_unit_test_case');
mimport('test/util/package_test_suite');

unset($Errorh);

define('ERROR_REPORTING',   E_ALL | E_NOTICE);
@error_reporting(ERROR_REPORTING);
set_error_handler('test_error_handler');

?>