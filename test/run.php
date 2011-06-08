<?php
define('PATH_TO_ROOT', '..');
define('DEBUG', TRUE);
if (get_magic_quotes_gpc())
{
	//If magic_quotes_sybase is enabled
	if (ini_get('magic_quotes_sybase') &&
	(strtolower(ini_get('magic_quotes_sybase')) != "off"))
	{
		//We consider the magic quotes as disabled
		define('MAGIC_QUOTES', false);

		//We treat the content: it must be as if the magic_quotes option is disabled
		foreach ($_REQUEST as $var_name => $value)
		{
			$_REQUEST[$var_name] = str_replace('\'\'', '\'', $value);
		}
	}
	//Magic quotes GPC
	else
	{
		define('MAGIC_QUOTES', true);
	}
}
else
{
	define('MAGIC_QUOTES', false);
}


require_once PATH_TO_ROOT . '/kernel/framework/core/environment/Environment.class.php';
Environment::load_imports();
Environment::load_static_constants();

AppContext::set_request(new HTTPRequest());
AppContext::init_session();
AppContext::get_session()->load();
AppContext::get_session()->act();
AppContext::init_user();
require_file('/test/PHPUnit/Framework.php');

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

require_file('/test/phpunit.php');
if (!$is_html)
{
	echo '</pre>';
}
PersistenceContext::close_db_connection();

?>