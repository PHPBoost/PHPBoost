<?php
require_once '../../../header.php';

$test = new PackageTestSuite(dirname(__FILE__), 'mvc/modules');
$test->run();

?>
