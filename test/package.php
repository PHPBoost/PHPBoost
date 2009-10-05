<?php
require_once './header.php';

$package = trim(isset($_REQUEST['package']) ? $_REQUEST['package'] : '', '/');
$recursive = (!empty($_REQUEST['recursive']) ? true : false);

if (!empty($package))
{
	$test = new PackageTestSuite(PATH_TO_ROOT . '/test/' . $package, $recursive);
	$test->run();
}

?>
