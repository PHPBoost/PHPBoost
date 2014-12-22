<?php
/*##################################################
 *                          CLIClearCacheCommand.class.php
 *                            -------------------
 *   begin                : April 11, 2010
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