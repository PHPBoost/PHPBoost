<?php
/*##################################################
 *                           LangsCache.class.php
 *                            -------------------
 *   begin                : July, 2010
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
 * This class contains the cache data of the langs which are installed
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 *
 */
class LangsCache implements CacheData
{
	private $langs = array();

	public function synchronize()
	{
		$this->langs = array();
		$db_connection = PersistenceContext::get_querier();

		$result = $db_connection->select("SELECT lang, secure, activ
		FROM " . PREFIX . "lang
		WHERE activ = 1");

		foreach ($result as $lang)
		{
			$this->langs[$lang['lang']] = array('enabled' => $lang['activ'], 'auth' => $lang['secure']);
		}
	}

	public function get_installed_langs()
	{
		return $this->langs;
	}

	public function get_lang_properties($identifier)
	{
		if (isset($this->langs[$identifier]))
		{
			return $this->langs[$identifier];
		}
		return null;
	}

	/**
	 * Loads and returns the Langs cached data.
	 * @return LangsCache The cached data
	 */
	public static function load()
	{
		return CacheManager::load(__CLASS__, 'kernel', 'installed-langs');
	}

	/**
	 * Invalidates the current Langs cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate('kernel', 'installed-langs');
	}
}