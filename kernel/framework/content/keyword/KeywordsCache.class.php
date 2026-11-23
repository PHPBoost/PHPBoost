<?php
/**
 * @package     Content
 * @subpackage  Keyword
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 11
 * @since       PHPBoost 5.1 - 2018 11 09
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class KeywordsCache implements CacheData
{
	protected array $keywords = [];

	public function synchronize(): void {
		$this->keywords = [];
		$result = PersistenceContext::get_querier()->select('
            SELECT relation.id_in_module, relation.id_keyword, keyword.*
			FROM ' . DB_TABLE_KEYWORDS_RELATIONS . ' relation
			LEFT JOIN ' . DB_TABLE_KEYWORDS . ' keyword ON keyword.id = relation.id_keyword
			WHERE relation.module_id = :module_id
			ORDER BY relation.id_keyword
            ', [
				'module_id' => self::get_module_identifier()
            ]
        );
		while ($row = $result->fetch())
		{
			$keyword = new Keyword();
			$keyword->set_properties($row);
			$this->keywords[$row['id_in_module']][$row['rewrited_name']] = $keyword;
		}
		$result->dispose();
	}

	public static function get_module_identifier(): string
	{
		return Environment::get_running_module_name();
	}

	public function get_keywords(int $id_in_module): array
	{
		if ($this->has_keywords($id_in_module))
		{
			return $this->keywords[$id_in_module];
		}
		return [];
	}

	public function has_keywords(int $id): bool
	{
		return array_key_exists($id ?? '', $this->keywords);
	}

	/**
	 * Loads and returns the keywords cached data.
	 * @return KeywordsCache The cached data
	 */
	public static function load(string $module_id = ''): KeywordsCache
	{
		return CacheManager::load(self::class, ($module_id ? $module_id : self::get_module_identifier()), 'keywords');
	}

	/**
	 * Invalidates keywords cached data.
	 */
	public static function invalidate(string $module_id = ''): void
	{
		CacheManager::invalidate(($module_id ? $module_id : self::get_module_identifier()), 'keywords');
	}
}
?>
