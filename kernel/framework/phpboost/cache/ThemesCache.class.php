<?php
/*##################################################
 *                           ThemesCache.class.php
 *                            -------------------
 *   begin                : 5 July, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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

/**
 * This class contains the cache data of the themes which are installed
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 *
 */
class ThemesCache implements CacheData
{
	private $themes = array();

	public function synchronize()
	{
		$this->themes = array();
		$db_connection = PersistenceContext::get_querier();
		
		$result = $db_connection->select("SELECT id, theme, left_column, right_column, secure, activ
		FROM " . DB_TABLE_THEMES . "
		WHERE activ = 1");

		foreach ($result as $theme)
		{
			$this->themes[$theme['theme']] = array(
				'id' => $theme['id'],
				'left_column' => (bool)$theme['left_column'],
				'right_column' => (bool)$theme['right_column'],
				'auth' => unserialize($theme['secure']),
				'enabled' => $theme['activ']
			);
		}
	}

	public function update_authorization_for_default_theme($theme)
	{
		$default_auth = array('r-1' => 1, 'r0' => 1, 'r1' => 1);
		PersistenceContext::get_sql()->query_inject("UPDATE " . DB_TABLE_THEMES . " SET secure = '". addslashes(serialize($default_auth)) ."' WHERE theme = '" . $theme . "'", __LINE__, __FILE__);
	}
	
	public function get_installed_themes()
	{
		return $this->themes;
	}

	public function get_theme_properties($identifier)
	{
		if (isset($this->themes[$identifier]))
		{
			return $this->themes[$identifier];
		}
		return null;
	}

	/**
	 * Loads and returns the Themes cached data.
	 * @return ThemesCache The cached data
	 */
	public static function load()
	{
		return CacheManager::load(__CLASS__, 'kernel', 'installed-themes');
	}

	/**
	 * Invalidates the current Themes cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate('kernel', 'installed-themes');
	}
}