<?php
/*##################################################
 *		             GraphicalEnvironmentConfig.class.php
 *                            -------------------
 *   begin                : July 8, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU GraphicalEnvironment Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU GraphicalEnvironment Public License for more details.
 *
 * You should have received a copy of the GNU GraphicalEnvironment Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 */
class GraphicalEnvironmentConfig extends AbstractConfigData
{
	const VISIT_COUNTER_ENABLED = 'visit_counter_enabled';
	const DISPLAY_THEME_AUTHOR = 'display_theme_author';
	const PAGE_BENCH_ENABLED = 'page_bench_enabled';

	public function is_visit_counter_enabled()
	{
		return $this->get_property(self::VISIT_COUNTER_ENABLED);
	}

	public function set_visit_counter_enabled($enabled)
	{
		$this->set_property(self::VISIT_COUNTER_ENABLED, $enabled);
	}
	
	public function get_display_theme_author()
	{
		return $this->get_property(self::DISPLAY_THEME_AUTHOR);
	}
	
	public function set_display_theme_author($display)
	{
		$this->set_property(self::DISPLAY_THEME_AUTHOR, $display);
	}
	
	public function is_page_bench_enabled()
	{
		return $this->get_property(self::PAGE_BENCH_ENABLED);
	}
	
	public function set_page_bench_enabled($enabled)
	{
		$this->set_property(self::PAGE_BENCH_ENABLED, $enabled);
	}

	public function get_default_values()
	{
		return array(
			self::VISIT_COUNTER_ENABLED => false,
			self::DISPLAY_THEME_AUTHOR => false,
			self::PAGE_BENCH_ENABLED => false,
		);
	}

	/**
	 * Returns the configuration.
	 * @return GraphicalEnvironmentConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'kernel', 'graphical-environment-config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('kernel', self::load(), 'graphical-environment-config');
	}
}
?>