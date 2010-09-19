<?php
/*##################################################
 *                        UnitTestCommand.class.php
 *                            -------------------
 *   begin                : September 11, 2010
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

class UnitTestCommand implements CLICommand
{
	public function short_description()
	{
		return "executes a unit test";
	}
	
	public function help(array $args)
	{
		return "";
	}
	
	public function execute(array $args)
	{
		if (count($args) !== 1)
		{
			CLIOutput::err('Must take 1 parameter');
			return;
		}
		$class_to_test = $args[0];
		$class_path = $this->get_class_to_test_path($class_to_test);
		CLIOutput::writeln('Running test ' . $class_to_test);
		import('/test/PHPUnit/TextUI/TestRunner', '.php');
		import('/test/PHPUnit/Framework/TestCase', '.php');
		include_once $class_path;
		$run = PHPUnit_TextUI_TestRunner::run(new $class_to_test, array());
		// TODO continue debugging here, the method doesn't seem to return
	}
	
	private function get_class_to_test_path($class_name)
	{
		import('/test/util/phpboost_unit_tests', INC_IMPORT);
		$tests = list_tu(PATH_TO_ROOT . '/test/kernel');
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
		throw new Exception('Test class ' . $class_name .  ' not found');
	}
}
?>