<?php
require_once PATH_TO_ROOT . '/test/PHPUnit/Extensions/PerformanceTestCase.php';

class SleepTest extends PHPUnit_Extensions_PerformanceTestCase
{
    public function testSleepTwoSeconds()
    {
        sleep(2);
    }
}
?>
