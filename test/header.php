<?php

define('PATH_TO_ROOT', str_replace('\\', '/', preg_replace('`^(.+)[\\\\/]test[\\\\/]?$`i',
    '$1', dirname(__FILE__))));

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