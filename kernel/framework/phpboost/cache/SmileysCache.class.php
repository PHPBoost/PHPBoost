<?php
/*##################################################
 *                      	 SmileysCache.class.php
 *                            -------------------
 *   begin                : August 09, 2010
 *   copyright            : (C) 2010 Kévin MASSY
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

/**
 * @author Kévin MASSY <soldier.weasel@gmail.com>
 */
class SmileysCache implements CacheData
{
	private $smileys = array();

	/**
	 * {@inheritdoc}
	 */
	public function synchronize()
	{
		$this->smileys = array();
		$db_connection = PersistenceContext::get_sql();
		
		$result = $db_connection->query_while("SELECT code_smiley, url_smiley
			FROM " . PREFIX . "smileys", __LINE__, __FILE__);
		
		while ($row = $db_connection->fetch_assoc($result))
		{
			$this->smileys[$row['code_smiley']] = $row['url_smiley'];
		}
		
		$db_connection->query_close($result);
	}

	public function get_smileys()
	{
		return $this->smileys;
	}
	
	public function get_url_smileys($code_smiley)
	{
		return $this->smileys[$code_smiley];
	}
	
	/**
	 * Loads and returns the smileys cached data.
	 * @return GroupsCache The cached data
	 */
	public static function load()
	{
		return CacheManager::load(__CLASS__, 'kernel', 'smileys');
	}
	
	/**
	 * Invalidates the current smileys cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate('kernel', 'smileys');
	}
}