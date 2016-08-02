<?php
/*##################################################
 *                          CLIUpdateCommand.class.php
 *                            -------------------
 *   begin                : July 31, 2016
 *   copyright            : (C) 2016 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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