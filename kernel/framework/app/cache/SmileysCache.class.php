<?php
/**
 * @package     PHPBoost
 * @subpackage  Cache
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 18
 * @since       PHPBoost 4.1 - 2014 08 09
* @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
*/

class SmileysCache implements CacheData
{
	private array $smileys = [];

	public function synchronize(): void
    {
		$this->smileys = [];

		$querier = PersistenceContext::get_querier();

		$columns = ['idsmiley', 'code_smiley', 'url_smiley'];
		$result = $querier->select_rows(PREFIX . 'smileys', $columns);
		while ($row = $result->fetch())
		{
			$this->smileys[$row['code_smiley']] = [
				'idsmiley' => $row['idsmiley'],
				'url_smiley' => $row['url_smiley']
			];
		}
		$result->dispose();
	}

	public function get_smileys(): array
	{
		return $this->smileys;
	}

	public function get_url_smiley(mixed $code_smiley): mixed
	{
		return $this->smileys[$code_smiley];
	}

	public static function load(): SmileysCache
	{
		return CacheManager::load(self::class, 'kernel', 'smileys');
	}

	public static function invalidate(): void
	{
		CacheManager::invalidate('kernel', 'smileys');
	}
}
?>
