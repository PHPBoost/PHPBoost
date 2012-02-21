<?php
/*##################################################
 *                           HomePageConfig.class.php
 *                            -------------------
 *   begin                : February 21, 2012
 *   copyright            : (C) 2012 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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

class HomePageConfig extends AbstractConfigData
{
	private static $plugins_property = 'plugins';
	
	public function get_default_values()
	{
		return array(
			self::$plugins_property => array()
		);
	}
	
	public function get_plugins()
	{
		return $this->get_property(self::$plugins_property);
	}

	public function get_plugin($id)
	{
		$plugins = $this->get_property(self::$plugins_property);
		if (array_key_exists($id, $plugins))
		{
			return $plugins[$id];
		}
		return null;
	}

    public function set_plugins(array $plugins)
    {
        $this->set_property(self::$plugins_property, $plugins);
    }

    public function add_plugin(Plugin $plugin)
    {
        $plugins = $this->get_property(self::$plugins_property);
        $plugins[$plugin->get_id()] = $plugin;
        $this->set_property(self::$plugins_property, $plugins);
    }

    public function remove_plugin(Plugin $plugin)
    {
        $plugins = $this->get_property(self::$plugins_property);
        unset($plugins[$plugin->get_id()]);
        $this->set_property(self::$plugins_property, $plugins);
    }
	
	public function update(Plugin $plugin)
	{
		$plugins = $this->get_property(self::$plugins_property);
        $plugins[$plugin->get_id()] = $plugin;

        $this->set_property(self::$plugins_property, $plugins);
	}
	
	/**
	 * Returns the configuration.
	 * @return HomePageConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'modules', 'homepage-config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('modules', self::load(), 'homepage-config');
	}
}
?>