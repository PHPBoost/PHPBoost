<?php
/**
 * @package     PHPBoost
 * @subpackage  Cache
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2015 12 17
 * @since       PHPBoost 4.1 - 2014 08 09
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
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
