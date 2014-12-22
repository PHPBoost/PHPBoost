<?php

class BenchTest extends PHPBoostUnitTestCase
{
	private $result_format_regex = '`[0-9]+\.[0-9]{%d}`';

	public function test___construct()
	{
		$bench = new Bench();
		self::assertNotNull($bench, 'bench is null');
	}

	public function test_to_string()
	{
        $this->_test_to_string();
        $this->_test_to_string5digits();
	}

	private function _test_to_string()
	{
		$bench = new Bench();
		$this->random_usleep();
		$time = $bench->to_string();

		self::assertRegExp(sprintf($this->result_format_regex, 3), $time);
	}

	private function _test_to_string5digits()
	{
		$digits = 5;

		$bench = new Bench();
		$this->random_usleep();
		$time = $bench->to_string($digits);

        self::assertRegExp(sprintf($this->result_format_regex, $digits), $time);
	}

	private function random_usleep()
	{
		usleep(rand(10, 1000));
	}
}
?>