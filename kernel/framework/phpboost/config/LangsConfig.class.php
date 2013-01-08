<?php
/*##################################################
 *                      	 LangsConfig.class.php
 *                            -------------------
 *   begin                : January 20, 2012
 *   copyright            : (C) 2012 Kevin MASSY
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

class LangsConfig extends AbstractConfigData
{
	private static $langs_property = 'langs';

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
			self::$langs_property => array()
		);
	}

	public function get_langs()
	{
		return $this->get_property(self::$langs_property);
	}

	public function get_lang($id)
	{
		$langs = $this->get_property(self::$langs_property);
		if (array_key_exists($id, $langs))
		{
			return $langs[$id];
		}
		return null;
	}

    public function set_langs(array $langs)
    {
        $this->set_property(self::$langs_property, $langs);
    }

    public function add_lang(Lang $lang)
    {
        $langs = $this->get_property(self::$langs_property);
        $langs[$lang->get_id()] = $lang;
        $this->set_property(self::$langs_property, $langs);
    }

    public function remove_lang(Lang $lang)
    {
        $langs = $this->get_property(self::$langs_property);
        unset($langs[$lang->get_id()]);
        $this->set_property(self::$langs_property, $langs);
    }

    public function remove_lang_by_id($id)
    {
        $langs = $this->get_property(self::$langs_property);
        unset($langs[$id]);
        $this->set_property(self::$langs_property, $langs);
    }
	
	public function update(Lang $lang)
	{
		$langs = $this->get_property(self::$langs_property);
        $langs[$lang->get_id()] = $lang;

        $this->set_property(self::$langs_property, $langs);
	}

	/**
	 * @desc Loads and returns the langs cached data.
	 * @return LangsConfig The cached data
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'kernel', 'langs');
	}

	/**
	 * Invalidates the current langs cached data.
	 */
	public static function save()
	{
		ConfigManager::save('kernel', self::load(), 'langs');
	}
}
?>