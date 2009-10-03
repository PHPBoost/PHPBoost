<?php
require_once 'header.php';

require_once PATH_TO_ROOT . '/kernel/begin.php';

$test = &new TestSuite('All tests');

$test->addFile('all_io_tests.php');
$test->addFile('all_core_tests.php');

//$test->run(new HtmlReporter());
