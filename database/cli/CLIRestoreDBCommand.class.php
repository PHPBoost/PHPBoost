<?php
/*##################################################
 *                          CLIRestoreDBCommand.class.php
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