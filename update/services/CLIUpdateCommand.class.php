<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 08 02
 * @since       PHPBoost 5.0 - 2016 07 31
*/

class CLIUpdateCommand implements CLICommand
{
	/**
	 * @var UpdateServices
	 */
	private $update;

	/**
	 * @var ServerConfiguration
	 */
	private $server_configuration;

	/**
	 * @var CLIArgumentsReader
	 */
	private $arg_reader;

	public function __construct()
	{
		$this->server_configuration = new ServerConfiguration();
	}

	public function short_description()
	{
		return 'update phpboost development environment to the new major version';
	}

	public function help(array $args)
	{
		CLIOutput::writeln('this is the phpboost update command line manual.');
		CLIOutput::writeln('this commands does not have optionals parameters.');
	}

	public function execute(array $args)
	{
		$this->arg_reader = new CLIArgumentsReader($args);
		if (!($this->check_env() && $this->update()))
		{
			CLIOutput::writeln('update failed');
		}
	}

	private function check_env()
	{
		CLIOutput::writeln('environment check...');
		return $this->chech_php_version() && $this->check_folders_permissions();
	}

	private function update()
	{
		CLIOutput::writeln('update');
		$this->update = new UpdateServices();
		if ($this->update->database_config_file_checked())
		{
			CLIOutput::writeln("\t" . 'executing update...');
			$this->update->execute();
			CLIOutput::writeln('update successfull');
			return true;
		}
		else
		{
			CLIOutput::writeln("\t" . 'phpboost is not installed, please install it before trying to update');
			return false;
		}
	}

	private function chech_php_version()
	{
		CLIOutput::writeln("\t" . 'php version');
		if (!$this->server_configuration->is_php_compatible())
		{
			CLIOutput::writeln('PHP version (' . ServerConfiguration::get_phpversion() . ') is not compatible with PHPBoost.');
			CLIOutput::writeln('PHP ' . ServerConfiguration::MIN_PHP_VERSION . ' is needed!');
			return false;
		}
		return true;
	}

	private function check_folders_permissions()
	{
		CLIOutput::writeln("\t" . 'folder permissions...');
		if (!PHPBoostFoldersPermissions::validate())
		{
			foreach (PHPBoostFoldersPermissions::get_permissions() as $folder_name => $folder)
			{
				if (!$folder->is_writable())
				{
					CLIOutput::writeln('Folder ' . $folder_name . ' is not writable. Please change its rights');
				}
			}
			return false;
		}
		return true;
	}
}
?>
