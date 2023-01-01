<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2019 04 03
 * @since       PHPBoost 3.0 - 2010 02 06
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

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
			$file_name = PATH_TO_ROOT . '/cache/backup/dump_' . $date->get_year() . '-' . $date->get_month() . '-' . $date->get_day_two_digits() . '_' . $date->get_hours() . 'h' . $date->get_minutes() . '.sql';
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
		
		$file_basename = str_replace('.sql', '', $file_path);
		
		include_once(PATH_TO_ROOT . '/kernel/lib/php/pcl/pclzip.lib.php');
		$archive = new PclZip($file_basename . '.zip');
		if ($archive->create($file_basename . '.sql'))
		{
			$file = new File($file_basename . '.sql');
			$file->delete();
			$file_link = $file_basename . '.zip';
		}
		else
		{
			$file = new File($file_basename . '.zip');
			$file->delete();
			$file_link = $file_basename . '.sql';
		}
		
		CLIOutput::writeln('Tables dumped to file ' . $file_link);
	}
}
?>
