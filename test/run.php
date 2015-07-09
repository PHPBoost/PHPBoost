<?php
define('PATH_TO_ROOT', '..');
define('DEBUG', TRUE);

require_once PATH_TO_ROOT . '/kernel/framework/core/environment/Environment.class.php';
Environment::load_imports();
Environment::load_static_constants();

AppContext::set_request(new HTTPRequestCustom());
Session::gc();
$session_data = Session::start();
AppContext::set_session($session_data);
AppContext::init_current_user();
require_once(PATH_TO_ROOT . '/test/PHPUnit/Framework.php');

if (isset($argv))
{
	array_shift($argv);
	$_REQUEST['params'] = implode(' ', $argv);
	$_REQUEST['is_html'] = false;
}

if (!empty($_REQUEST['params']))
{
	// Fake command line environment
	$argv = $_REQUEST['params'];
	$_SERVER['argv'] = explode(' ', '--configuration ./phpunit.cfg.xml ' . $argv);
}
else
{
	$_SERVER['argv'] = array();
}

$is_html = isset($_REQUEST['is_html']) && $_REQUEST['is_html'] == true;
if (!$is_html)
{
	echo '<pre>';
}

//Debug::dump($_SERVER['argv']);

require_once(PATH_TO_ROOT . '/test/phpunit.php');
if (!$is_html)
{
	echo '</pre>';
}
PersistenceContext::close_db_connection();

?>