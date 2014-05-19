<?php
/*##################################################
 *                       ListUnitTestsCommand.class.php
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

class ListUnitTestsCommand implements CLICommand 
{
	public function short_description()
	{
		return "list all available unit tests";
	}
	
	public function execute(array $args)
	{
		$tests = $this->get_tests();
		foreach ($tests as $test)
		{
			CLIOutput::writeln($test);
		}
	}
	
	private function get_tests()
	{
		include_once(PATH_TO_ROOT . '/test/util/phpboost_unit_tests.inc.php');
		$tests = list_tu(PATH_TO_ROOT . '/test/kernel');
		return $this->extract_tests_names($tests);
	}
	
	private function extract_tests_names(array $tests)
	{
		$tests_names = array();
		foreach ($tests as $test)
		{
			$tests_names[] = preg_replace('`.+/([^./]+)\.php`', '$1', $test);
		}
		return $tests_names;
	}
	
	public function help(array $args)
	{
		return "Shows all available unit tests. Run the run goal to run one or more tests";
	}
}

?>