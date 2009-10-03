<?php
require_once 'header.php';

require_once PATH_TO_ROOT . '/kernel/begin.php';

$test = &new TestSuite('All tests for CORE directory');

$test->addFile('UT_core_application.php');
$test->addFile('UT_core_breadcrumb.php');
$test->addFile('UT_core_cache.php');
$test->addFile('UT_core_errors.php');
$test->addFile('UT_core_menu_service.php');
$test->addFile('UT_core_repository.php');
$test->addFile('UT_core_stats_saver.php');

$test->run(new HtmlReporter());
