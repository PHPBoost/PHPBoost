<?php
require_once 'header.php';

require_once PATH_TO_ROOT . '/kernel/begin.php';

$test = &new TestSuite('All tests for CONTENT directory');

$test->addFile('UT_content_categories_manager.php');
$test->addFile('UT_content_comments.php');
$test->addFile('UT_content_note.php');
$test->addFile('UT_content_search.php');

$test->run(new HtmlReporter());
