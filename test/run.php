<?php
define('PATH_TO_ROOT', '..');
define('DEBUG', TRUE);

require_once PATH_TO_ROOT . '/kernel/framework/functions.inc.php';

req('/test/PHPUnit/Framework.php');
mimport('test/util/phpboost_unit_test_case');
if (!empty($_REQUEST['params'])) {
    // Fake command line environment
    $argv = $_REQUEST['params'];
    $_SERVER['argv'] = explode(' ', $argv);
} else {
    $_SERVER['argv'] = array();
}

if (!empty($_REQUEST['is_text'])) {
	echo '<pre>';
}
req('/test/phpunit.php');
if (!empty($_REQUEST['is_text'])) {
    echo '</pre>';
}
    
?>