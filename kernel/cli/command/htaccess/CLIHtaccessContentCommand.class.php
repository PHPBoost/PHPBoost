<?php
/*##################################################
 *                          CLIHtaccessContentCommand.class.php
 *                            -------------------
 *   begin                : October 11, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

class CLIHtaccessContentCommand implements CLICommand
{
	private $arg_reader;

	public function short_description()
	{
		return 'Set content for htaccess file';
	}

	public function help(array $args)
	{
        CLIOutput::writeln('this is the htaccess file content management command line manual.');
		CLIOutput::writeln('this commands have parameter :');

		CLIOutput::writeln('add [content]');
	}

	public function execute(array $args)
	{
		$this->arg_reader = new CLIArgumentsReader($args);
		
		if ($this->arg_reader->has_arg('add'))
		{
			$server_environment_config = ServerEnvironmentConfig::load();
			$content = $server_environment_config->get_htaccess_manual_content();
			$content .= $this->arg_reader->get('add');
			$server_environment_config->set_htaccess_manual_content($content);
			ServerEnvironmentConfig::save();
			$this->regenerate_htaccess_file();
			CLIOutput::writeln('success');
		}
		else
		{
			$this->help(array());
		}
	}
	
	private function regenerate_htaccess_file()
	{
		$apc_enabled = DataStoreFactory::is_apc_enabled();
		if ($apc_enabled)
		{
			DataStoreFactory::set_apc_enabled(false);
			HtaccessFileCache::regenerate();
			AppContext::get_cache_service()->clear_cache();
			DataStoreFactory::set_apc_enabled(true);
		}
		else
		{
			AppContext::get_cache_service()->clear_cache();
			HtaccessFileCache::regenerate();
		}
	}
}
?>