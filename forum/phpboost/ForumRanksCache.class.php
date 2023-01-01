<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 04 30
 * @since       PHPBoost 3.0 - 2010 08 09
 * @contributor Arnaud GENET <elenwii@phpboost.com>
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
				'id'      => $row['id'],
				'name'    => $row['name'],
				'icon'    => $row['icon'],
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
