<?php
require_once '../../../header.php';

$test = new PackageTestSuite(__DIR__, 'mvc/modules');
$test->run();

?>
