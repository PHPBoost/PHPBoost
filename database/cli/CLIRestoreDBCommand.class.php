<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2019 04 03
 * @since       PHPBoost 3.0 - 2010 02 06
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
		CLIOutput::writeln('Restores the database from a dump (sql file or in a zip archive).');
		CLIOutput::writeln('Warning, a dump is DBMS specific. Be sure that the dump you are restoring corresponds to the one to which this server is linked to.');
	}

	public function execute(array $args)
	{
		if (count($args) == 0)
		{
			CLIOutput::writeln('Error: please enter an input file.');
			return;
		}
		if (count($args) > 1)
		{
			CLIOutput::writeln('Warning: there are several parameters to the command whereas it accepts only one. The other ones will be ignored.');
		}
		$file_name = $args[0];
		if (!preg_match('`[^/]+\.sql$`u', $file_name) && !preg_match('`[^/]+\.zip$`u', $file_name))
		{
			CLIOutput::writeln('Error: please enter a link to a sql or zip input file.');
		}
		try
		{
			if ($this->restore_db($file_name))
			{
				$this->optimize_tables();
				$this->clear_caches();
			}
			else
				CLIOutput::writeln('Error: the file you want to import is not the backup of this site, impossible to restore.');
		}
		catch (IOException $ex)
		{
			CLIOutput::writeln('Error: the file you want to import doesn\'t exist or is not readable.');
		}
	}

	private function restore_db($file_name)
	{
		$return = false;
		Environment::try_to_increase_max_execution_time();
		
		$original_file_compressed = false;
		
		if (preg_match('`[^/]+\.zip$`u', $file_name))
		{
			$original_file_compressed = true;
			$extract_filename = $file_to_restore = '';
			
			include_once(PATH_TO_ROOT . '/kernel/lib/php/pcl/pclzip.lib.php');
			$archive = new PclZip($file_name);
			
			foreach ($archive->listContent() as $element)
			{
				if (preg_match('`[^/]+\.sql$`u', $element['filename']))
				{
					$extract_filename = $element['filename'];
					break;
				}
			}
			
			if ($extract_filename && $archive->extract())
				$file_to_restore = $extract_filename;
			else
				CLIOutput::writeln('Error: the zip file linked does not contain any dump to restore.');
		}
		else
			$file_to_restore = $file_name;
		
		if ($file_to_restore)
		{
			$return = PersistenceContext::get_dbms_utils()->parse_file(new File($file_to_restore));
			
			if ($original_file_compressed)
			{
				$file = new File($file_to_restore);
				$file->delete();
			}
			
			if ($return)
				CLIOutput::writeln('Dump restored from file ' . $file_name);
		}
		
		return $return;
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
