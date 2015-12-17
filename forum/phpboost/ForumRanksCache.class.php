<?php
/*##################################################
 *                      	 ForumRanksCache.class.php
 *                            -------------------
 *   begin                : August 09, 2010
 *   copyright            : (C) 2010 Kevin MASSY
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

/**
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 */
class ForumRanksCache implements CacheData
{
	private $ranks = array();

	/**
	 * {@inheritdoc}
	 */
	public function synchronize()
	{
		$this->ranks = array();
		
		$result = PersistenceContext::get_querier()->select_rows(PREFIX . 'forum_ranks', array('id', 'name', 'msg', 'icon', 'special'), 'ORDER BY msg ASC');
		while ($row = $result->fetch())
		{
			$this->ranks[$row['msg']] = array(
				'id' => $row['id'],
				'name' => $row['name'],
				'icon' => $row['icon'], 
				'special' => $row['special']
			);
		}
		$result->dispose();
	}

	public function get_ranks()
	{
		return $this->ranks;
	}
	
	public function get_rank($nbr_msg)
	{
		return $this->ranks[$nbr_msg];
	}
	
	/**
	 * Loads and returns the ranks cached data.
	 * @return ForumRanksCache The cached data
	 */
	public static function load()
	{
		return CacheManager::load(__CLASS__, 'forum', 'ranks');
	}
	
	/**
	 * Invalidates the current ranks cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate('forum', 'ranks');
	}
}
?>