<?php
require_once 'header.php';

require_once PATH_TO_ROOT . '/kernel/begin.php';

$test = new TestSuite('All tests for IO directory');
$test->addFile('UT_io_fse.php');
$test->addFile('UT_io_file.php');
$test->addFile('UT_io_folder.php');
$test->addFile('UT_io_mail.php');
$test->run(new HtmlReporter());
