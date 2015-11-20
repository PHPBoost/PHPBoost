<?php
/*##################################################
 *                          CLIDumpCommand.class.php
 *                            -------------------
 *   begin                : February 06, 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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

class CLIDumpCommand implements CLICommand
{
	public function short_description()
	{
		return 'Dump database';
	}

	public function help(array $args)
	{
		CLIOutput::writeln('scenario: phpboost dump [file [tables ...]]');
		CLIOutput::writeln('Dumps the data base in a file (either the one which is in the first parameter or a default one).');
		CLIOutput::writeln('You can choose the tables you want to dump as of the second parameter. By default, the whole database will be dumped.');
	}

	public function execute(array $args)
	{
		if (count($args) == 0)
		{
			$date = new Date();
			$file_name = PATH_TO_ROOT . '/cache/backup/dump_' . $date->get_year() . '-' . $date->get_month() . '-' . $date->get_day() . '_' . $date->get_hours() . 'h' . $date->get_minutes() . '.sql';
		}
		else
		{
			$file_name = $args[0];
			array_shift($args);
		}
		$tables = null;
		foreach ($args as $arg)
		{
			$tables[] = $arg;
		}
		
		$this->dump($file_name, $tables);
	}
	
	private function dump($file_path, $tables)
	{
		Environment::try_to_increase_max_execution_time();
		$file = new File($file_path);
		$file_writer = new BufferedFileWriter($file);
		if ($tables == null)
		{
			PersistenceContext::get_dbms_utils()->dump_phpboost($file_writer, DBMSUtils::DUMP_STRUCTURE_AND_DATA);
		}
		else
		{
			PersistenceContext::get_dbms_utils()->dump_tables($file_writer, $tables, DBMSUtils::DUMP_STRUCTURE_AND_DATA);
		}
		CLIOutput::writeln('Tables dumped to file ' . $file_path);
	}
}
?>
