<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2015 09 11
 * @since       PHPBoost 3.0 - 2011 10 11
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

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
