<?php
/**
 * @package     Content
 * @subpackage  Keyword
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 01 23
 * @since       PHPBoost 4.0 - 2013 08 28
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class KeywordsManager
{
	/**
	 * @var string module identifier.
	 */
	private $module_id;

	private $db_querier;

	public function __construct($module_id = '')
	{
		$this->module_id = $module_id ? $module_id : KeywordsCache::get_module_identifier();

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

		foreach ($keywords as $keyword_list)
		{
			if (!empty($keyword_list))
			{
				$array_keywords = explode(',', str_replace(array(', ', '; ', ';'), ',', $keyword_list));

				foreach ($array_keywords as $keyword)
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
		return KeywordsCache::load($this->module_id)->get_keywords($id_in_module);
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

	public function regenerate_cache()
	{
		KeywordsCache::invalidate();
	}

	/**
	 * @return string module identifier.
	 */
	public function get_module_id() { return $this->module_id; }
}
?>
