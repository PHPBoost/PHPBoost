<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author  	Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2019 10 27
 * @since       PHPBoost 5.2 - 2019 10 27
*/

class CLINginxContentCommand implements CLICommand
{
	private $arg_reader;

	public function short_description()
	{
		return 'Set content for nginx.conf file';
	}

	public function help(array $args)
	{
        CLIOutput::writeln('this is the nginx.conf file content management command line manual.');
		CLIOutput::writeln('this commands have parameter :');

		CLIOutput::writeln('add [content]');
	}

	public function execute(array $args)
	{
		$this->arg_reader = new CLIArgumentsReader($args);

		if ($this->arg_reader->has_arg('add'))
		{
			$server_environment_config = ServerEnvironmentConfig::load();
			$content = $server_environment_config->get_nginx_manual_content();
			$content .= $this->arg_reader->get('add');
			$server_environment_config->set_nginx_manual_content($content);
			ServerEnvironmentConfig::save();
			$this->regenerate_nginx_file();
			CLIOutput::writeln('success');
		}
		else
		{
			$this->help(array());
		}
	}

	private function regenerate_nginx_file()
	{
		$apc_enabled = DataStoreFactory::is_apc_enabled();
		if ($apc_enabled)
		{
			DataStoreFactory::set_apc_enabled(false);
			NginxFileCache::regenerate();
			AppContext::get_cache_service()->clear_cache();
			DataStoreFactory::set_apc_enabled(true);
		}
		else
		{
			AppContext::get_cache_service()->clear_cache();
			NginxFileCache::regenerate();
		}
	}
}
?>
