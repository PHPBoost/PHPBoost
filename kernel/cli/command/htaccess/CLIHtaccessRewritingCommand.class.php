<?php
/*##################################################
 *                          CLIHtaccessRewritingCommand.class.php
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

class CLIHtaccessRewritingCommand implements CLICommand
{
	private $server_environment_config;
	private $arg_reader;

	public function __construct()
	{
		$this->server_environment_config = ServerEnvironmentConfig::load();	
	}
	public function short_description()
	{
		return 'Manage rewriting urls';
	}

	public function help(array $args)
	{
        CLIOutput::writeln('this is the urls rewrting management command line manual.');
		CLIOutput::writeln('this commands have parameters :');

		CLIOutput::writeln('enable');
		CLIOutput::writeln('or');
		CLIOutput::writeln('disable');
	}

	public function execute(array $args)
	{
		$this->arg_reader = new CLIArgumentsReader($args);
		
		if ($this->arg_reader->has_arg('enable'))
		{
			$this->enable_urls_rewriting();
			$this->regenerate_htaccess_file();
			$this->success_message();
		}
		else if ($this->arg_reader->has_arg('disable'))
		{
			$this->disable_urls_rewriting();
			HtaccessFileCache::regenerate();
			$this->success_message();
		}
		else
		{
			$this->help(array());
		}
	}

	private function enable_urls_rewriting()
	{
		$this->server_environment_config->set_url_rewriting_enabled(true);
		ServerEnvironmentConfig::save();
	}
	
	private function disable_urls_rewriting()
	{
		$this->server_environment_config->set_url_rewriting_enabled(false);
		ServerEnvironmentConfig::save();
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
	
	private function success_message()
	{
		CLIOutput::writeln('success');
	}
}
?>