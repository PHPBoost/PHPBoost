<?php
/*##################################################
 *                      	 SmileysCache.class.php
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
class SmileysCache implements CacheData
{
	private $smileys = array();

	/**
	 * {@inheritdoc}
	 */
	public function synchronize()
	{
		$this->smileys = array();
		
		$querier = PersistenceContext::get_querier();
		
		$columns = array('idsmiley', 'code_smiley', 'url_smiley');
		$result = $querier->select_rows(PREFIX . 'smileys', $columns);
		while ($row = $result->fetch())
		{
			$this->smileys[$row['code_smiley']] = array(
				'idsmiley' => $row['idsmiley'],
				'url_smiley' => $row['url_smiley']
			);
		}
		$result->dispose();
	}

	public function get_smileys()
	{
		return $this->smileys;
	}
	
	public function get_url_smiley($code_smiley)
	{
		return $this->smileys[$code_smiley];
	}
	
	/**
	 * Loads and returns the smileys cached data.
	 * @return SmileysCache The cached data
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
?>