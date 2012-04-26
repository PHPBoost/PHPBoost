<?php
/*##################################################
 *                      	 HomePagePluginsCache.class.php
 *                            -------------------
 *   begin                : March 18, 2012
 *   copyright            : (C) 2012 Kevin MASSY
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
 * @author Kevin MASSY <soldier.weasel@gmail.com>
 */
class HomePagePluginsCache implements CacheData
{
	private $installed_plugins = array();
	
	/**
	 * {@inheritdoc}
	 */
	public function synchronize()
	{
		$db_querier = PersistenceContext::get_querier();
		
		$result = $db_querier->select_rows(HomePageSetup::$home_page_table, array('*'));
		while ($row = $result->fetch())
		{
			$this->installed_plugins[$row['block']] = array(
				'id' => $row['id'],
				'title' => $row['title'],
				'class' => $row['class'],
				'object' => unserialize($row['object']),
				'enabled' => $row['enabled'],
				'authorizations' => unserialize($row['authorizations']),
			);
		}
	}
	
	public function get_plugins()
	{
		return $this->installed_plugins;
	}

	public static function load()
	{
		return CacheManager::load(__CLASS__, 'HomePage', 'plugins');
	}
	
	public static function invalidate()
	{
		CacheManager::invalidate('HomePage', 'plugins');
	}
}