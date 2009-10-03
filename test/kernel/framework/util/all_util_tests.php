<?php
require_once 'header.php';

require_once PATH_TO_ROOT . '/kernel/begin.php';

$test = new TestSuite('All tests for UTIL directory');
$test->addFile('UT_util_bench.php');
$test->addFile('UT_util_captcha.php');
$test->run(new HtmlReporter());
