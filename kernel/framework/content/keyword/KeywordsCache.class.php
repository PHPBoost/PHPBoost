<?php
/*##################################################
 *                        KeywordsCache.class.php
 *                            -------------------
 *   begin                : November 9, 2018
 *   copyright            : (C) 2018 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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

abstract class KeywordsCache implements CacheData
{
	protected $keywords;
	
	public function synchronize()
	{
		$this->keywords = array();
		$result = PersistenceContext::get_querier()->select('SELECT relation.id_in_module, relation.id_keyword, keyword.*
			FROM '. DB_TABLE_KEYWORDS_RELATIONS .' relation
			LEFT JOIN '. DB_TABLE_KEYWORDS .' keyword ON keyword.id = relation.id_keyword
			WHERE relation.module_id = :module_id
			ORDER BY relation.id_keyword', array(
				'module_id' => self::get_class()->get_module_identifier()
		));
		while ($row = $result->fetch())
		{
			$keyword = new Keyword();
			$keyword->set_properties($row);
			$this->keywords[$row['id_in_module']][$row['name']] = $keyword;
		}
		$result->dispose();
	}
	
	abstract public function get_module_identifier();
	
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
	public static function load()
	{
		return CacheManager::load(get_called_class(), self::get_class()->get_module_identifier(), 'keywords');
	}
	
	/**
	 * Invalidates keywords cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate(self::get_class()->get_module_identifier(), 'keywords');
	}
	
	public static function get_class()
	{
		$class_name = get_called_class();
		return new $class_name();
	}
}
?>
