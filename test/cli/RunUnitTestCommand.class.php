<?php
/*##################################################
 *                        RunUnitTestCommand.class.php
 *                            -------------------
 *   begin                : September 26, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

class RunUnitTestCommand implements CLICommand
{
	private $tests = null;

	public function short_description()
	{
		return "executes a unit test";
	}

	public function execute(array $args)
	{
		if (count($args) == 0)
		{
			CLIOutput::err('No test to run');
			return;
		}
		include_once(PATH_TO_ROOT . '/test/PHPUnit/TextUI/TestRunner.php');
		include_once(PATH_TO_ROOT . '/test/PHPUnit/TextUI/TestCase.php');
		foreach ($args as $class_to_test)
		{
			try
			{
				$this->run_test($class_to_test);
			}
			catch (TestNotFoundException $e)
			{
				CLIOutput::err('Test ' . $e->get_test_name() . ' not found. Please check that it exists.');
			}
		}
	}

	private function run_test($class_to_test)
	{
		$class_path = $this->get_class_to_test_path($class_to_test);
		CLIOutput::writeln('Running test ' . $class_to_test);
		include_once $class_path;
		$class = new ReflectionClass($class_to_test);
		PHPUnit_TextUI_TestRunner::run($class);
		CLIOutput::writeln();
	}

	private function get_class_to_test_path($class_name)
	{
		$tests = $this->get_all_tests();
		$name_to_find = '/' . $class_name . '.php';
		$name_to_find_length = strlen($name_to_find);
		foreach ($tests as $test_path)
		{
			// If path ends with $test
			if (substr_compare($test_path, $name_to_find, -$name_to_find_length, $name_to_find_length) === 0)
			{
				return $test_path;
			}
		}
		throw new TestNotFoundException($class_name);
	}

	private function get_all_tests()
	{
		if ($this->tests === null)
		{
			include_once(PATH_TO_ROOT . '/test/util/phpboost_unit_tests.inc.php');
			$this->tests = list_tu(PATH_TO_ROOT . '/test/kernel');
		}
		return $this->tests;
	}

	public function help(array $args)
	{
		return "Runs unit tests. Parameters are the test class names, there can be as much test as you want.\n" .
		'To know the available unit tests, run the list goal';
	}
}

?>