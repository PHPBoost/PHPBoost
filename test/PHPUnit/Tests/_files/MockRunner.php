<?php
require_once PATH_TO_ROOT . '/test/PHPUnit/Runner/BaseTestRunner.php';

class MockRunner extends PHPUnit_Runner_BaseTestRunner
{
    protected function runFailed($message)
    {
    }
}
?>
