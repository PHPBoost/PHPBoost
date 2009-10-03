<?php
require_once 'header.php';

require_once PATH_TO_ROOT . '/kernel/begin.php';

$test = new TestSuite('All tests for DB directory');

$test->addFile('UT_db_backup.php');
$test->addFile('UT_db_mysql.php');

$test->run(new HtmlReporter());
