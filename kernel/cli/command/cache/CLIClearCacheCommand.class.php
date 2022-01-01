<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 04 11
*/

class CLIClearCacheCommand implements CLICommand
{
	public function short_description()
	{
		return 'clears phpboost cache';
	}

	public function help(array $args)
	{
		CLIOutput::writeln('scenario: phpboost cache clear');
		$this->print_commands_descriptions();
	}

	public function execute(array $args)
	{
		if (!empty($args))
		{
			$this->help($args);
		}
		else
		{
			$this->clear();
		}
	}

	public function print_commands_descriptions() { }

	private function clear()
	{
		$cache_service = AppContext::get_cache_service();
        CLIOutput::writeln('[clear] phpboost cache');
        $cache_service->clear_phpboost_cache();
        CLIOutput::writeln('[clear] templates cache');
        $cache_service->clear_template_cache();
        CLIOutput::writeln('[clear] syndication cache');
        $cache_service->clear_syndication_cache();
        CLIOutput::writeln('[clear] CSS cache');
        $cache_service->clear_css_cache();
        CLIOutput::writeln('cache has been successfully cleared');
	}
}
?>
