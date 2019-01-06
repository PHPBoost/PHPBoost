<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2015 09 09
 * @since   	PHPBoost 3.0 - 2010 02 06
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class CLIRestoreDBCommand implements CLICommand
{
	public function short_description()
	{
		return 'Restore database';
	}

	public function help(array $args)
	{
		CLIOutput::writeln('scenario: phpboost restoredb file');
		CLIOutput::writeln('Restores the database from a dump.');
		CLIOutput::writeln('Warning, a dump is DBMS specific. Be sure that the dump you are restoring corresponds to the the one to which PHPBoost is linked to.');
	}

	public function execute(array $args)
	{
		if (count($args) == 0)
		{
			CLIOutput::writeln('Error: please enter an input file');
			return;
		}
		if (count($args) > 1)
		{
			CLIOutput::writeln('Warning: there are several paramters to the command whereas it accepts only one. The other ones will be ignored.');
		}
		$file_name = $args[0];
		try
		{
			$this->restore_db($file_name);
			$this->optimize_tables();
			$this->clear_caches();
		}
		catch (IOException $ex)
		{
			CLIOutput::writeln('Error: the file you want to import doesn\'t exist or is not readable.');
		}
	}

	private function restore_db($file_name)
	{
		Environment::try_to_increase_max_execution_time();
		PersistenceContext::get_dbms_utils()->parse_file(new File($file_name));
		CLIOutput::writeln('Dump restored from file ' . $file_name);
	}

	private function optimize_tables()
	{
		$db_utils = PersistenceContext::get_dbms_utils();
		$tables = $db_utils->list_tables();
		$db_utils->optimize($tables);
		$db_utils->repair($tables);
		CLIOutput::writeln('Database optimized');
	}

	private function clear_caches()
	{
		$cache_service = AppContext::get_cache_service();
        $cache_service->clear_phpboost_cache();
        $cache_service->clear_syndication_cache();
		CLIOutput::writeln('Caches cleared');
	}
}
?>
