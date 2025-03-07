<?php
/**
 * @package     Content
 * @subpackage  Keyword
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 01 23
 * @since       PHPBoost 5.1 - 2018 11 09
*/

class KeywordsCache implements CacheData
{
	protected $keywords;

	public function synchronize()
	{
		$this->keywords = array();
		$result = PersistenceContext::get_querier()->select('SELECT relation.id_in_module, relation.id_keyword, keyword.*
			FROM ' . DB_TABLE_KEYWORDS_RELATIONS . ' relation
			LEFT JOIN ' . DB_TABLE_KEYWORDS . ' keyword ON keyword.id = relation.id_keyword
			WHERE relation.module_id = :module_id
			ORDER BY relation.id_keyword', array(
				'module_id' => self::get_module_identifier()
		));
		while ($row = $result->fetch())
		{
			$keyword = new Keyword();
			$keyword->set_properties($row);
			$this->keywords[$row['id_in_module']][$row['rewrited_name']] = $keyword;
		}
		$result->dispose();
	}

	public static function get_module_identifier()
	{
		return Environment::get_running_module_name();
	}

	public function get_keywords($id_in_module)
	{
		if ($this->has_keywords($id_in_module))
		{
			return $this->keywords[$id_in_module];
		}
		return array();
	}

	public function has_keywords($id)
	{
		return array_key_exists($id, $this->keywords);
	}

	/**
	 * Loads and returns the keywords cached data.
	 * @return KeywordsCache The cached data
	 */
	public static function load($module_id = '')
	{
		return CacheManager::load(__CLASS__, ($module_id ? $module_id : self::get_module_identifier()), 'keywords');
	}

	/**
	 * Invalidates keywords cached data.
	 */
	public static function invalidate($module_id = '')
	{
		CacheManager::invalidate(($module_id ? $module_id : self::get_module_identifier()), 'keywords');
	}
}
?>
