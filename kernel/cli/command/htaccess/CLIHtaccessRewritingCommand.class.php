<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2015 09 11
 * @since       PHPBoost 3.0 - 2011 10 11
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

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
