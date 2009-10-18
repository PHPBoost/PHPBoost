<?php
define('PATH_TO_ROOT', '..');
define('DEBUG', TRUE);

require_once PATH_TO_ROOT . '/kernel/framework/core/environment/environment.class.php';
Environment::load_imports();
Environment::load_static_constants();
AppContext::init_sql_querier();
AppContext::init_session();
AppContext::init_user();

req('/test/PHPUnit/Framework.php');
mimport('test/util/phpboost_unit_test_case');
if (!empty($_REQUEST['params'])) {
    // Fake command line environment
    $argv = $_REQUEST['params'];
    $_SERVER['argv'] = explode(' ', '--configuration ./phpunit.cfg.xml ' . $argv);
} else {
    $_SERVER['argv'] = array();
}

if (empty($_REQUEST['is_html'])) {
	echo '<pre>';
}
req('/test/phpunit.php');
if (!empty($_REQUEST['is_html'])) {
    echo '</pre>';
}
AppContext::close_db_connection();
    
?>