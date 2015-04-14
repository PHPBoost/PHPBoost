<?php
/*##################################################
 *		                         KeywordsManager.class.php
 *                            -------------------
 *   begin                : August 28, 2013
 *   copyright            : (C) 2013 Kevin MASSY
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
class KeywordsManager
{
	/**
	 * @var string module identifier.
	 */
	private $module_id;
	private $db_querier;
	
	public function __construct($module_id)
	{
		$this->module_id = $module_id;
		$this->db_querier = PersistenceContext::get_querier();
	}

	public function get_form_field($id_in_module, $id, $label, array $field_options = array(), array $constraints = array())
	{
		$field_options['file'] = TPL_PATH_TO_ROOT . '/kernel/framework/ajax/dispatcher.php?url=/tags/';
		return new FormFieldMultipleAutocompleter($id, $label, array_keys($this->get_keywords($id_in_module)), $field_options, $constraints);
	}
	
	public function put_relations($id_in_module, $keywords)
	{
		$this->delete_relations($id_in_module);
		
		foreach ($keywords as $keyword)
		{
			if (!empty($keyword))
			{
				if (!$this->exists($keyword))
				{
					$result = $this->db_querier->insert(DB_TABLE_KEYWORDS, array('name' => TextHelper::htmlspecialchars($keyword), 'rewrited_name' => TextHelper::htmlspecialchars(Url::encode_rewrite($keyword))));
					$id_keyword = $result->get_last_inserted_id();
				}
				else
				{
					$id_keyword = $this->db_querier->get_column_value(DB_TABLE_KEYWORDS, 'id', 'WHERE name=:name OR rewrited_name=:rewrited_name', array('name' => TextHelper::htmlspecialchars($keyword), 'rewrited_name' => TextHelper::htmlspecialchars(Url::encode_rewrite($keyword))));
				}
				$this->db_querier->insert(DB_TABLE_KEYWORDS_RELATIONS, array('module_id' => $this->module_id, 'id_in_module' => $id_in_module, 'id_keyword' => $id_keyword));
			}
		}
	}
	
	public function get_keyword($condition, array $parameters = array())
	{
		$row = $this->db_querier->select_single_row(DB_TABLE_KEYWORDS, array('*'), $condition, $parameters);
		$keyword = new Keyword();
		$keyword->set_properties($row);
		return $keyword;
	}
	
	public function get_keywords($id_in_module)
	{
		$keywords = array();
		$result = $this->db_querier->select('SELECT relation.id_in_module, relation.id_keyword, keyword.*
			FROM '. DB_TABLE_KEYWORDS_RELATIONS .' relation
			LEFT JOIN '. DB_TABLE_KEYWORDS .' keyword ON keyword.id = relation.id_keyword
			WHERE relation.module_id = :module_id AND relation.id_in_module = :id_in_module
			ORDER BY relation.id_keyword', array(
				'module_id' => $this->module_id,
				'id_in_module' => $id_in_module,
		));
		while ($row = $result->fetch())
		{
			$keyword = new Keyword();
			$keyword->set_properties($row);
			$keywords[$row['name']] = $keyword;
		}
		$result->dispose();
		return $keywords;
	}
	
	public function delete_relations($id_in_module)
	{
		$this->db_querier->delete(DB_TABLE_KEYWORDS_RELATIONS, 'WHERE module_id = :module_id AND id_in_module=:id_in_module', array('module_id' => $this->module_id, 'id_in_module' => $id_in_module));
	}
	
	public function delete_module_relations()
	{
		$this->db_querier->delete(DB_TABLE_KEYWORDS_RELATIONS, 'WHERE module_id=:module_id', array('module_id' => $this->module_id));
	}

	private function exists($name)
	{
		return $this->db_querier->row_exists(DB_TABLE_KEYWORDS, 'WHERE name=:name OR rewrited_name=:rewrited_name', array('name' => TextHelper::htmlspecialchars($name), 'rewrited_name' => TextHelper::htmlspecialchars(Url::encode_rewrite($name))));
	}
}
?>