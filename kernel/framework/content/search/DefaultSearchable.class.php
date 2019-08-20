<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.3 - last update: 2019 08 20
 * @since   	PHPBoost 5.3 - 2019 08 20
*/

class DefaultSearchable extends AbstractSearchableExtensionPoint
{
	private $module_id;
	
	protected $read_authorization = false;
	
	protected $table_name;
	
	protected $has_second_table = false;
	protected $second_table_name;
	protected $second_table_label;
	protected $second_table_id = 'id';
	protected $second_table_foreign_id;
	
	protected $cats_table_name;
	protected $authorized_categories = array();
	
	protected $use_keywords = false;
	
	protected $custom_link_end;
	protected $custom_all_link;
	
	protected $field_id = 'id';
	protected $field_title = 'title';
	protected $field_rewrited_title = 'rewrited_title';
	protected $field_contents = 'contents';
	
	protected $has_short_contents = false;
	protected $field_short_contents = 'short_contents';
	
	protected $has_approbation = true;
	protected $field_approbation_type = 'approbation_type';
	protected $approved_value = 1;
	
	protected $has_validation_period = false;
	protected $field_validation_start_date = 'start_date';
	protected $field_validation_end_date = 'end_date';
	protected $validation_period_value = 2;
	
	protected $max_search_results = 100;
	
	public function __construct($module_id)
	{
		$this->module_id = $module_id;
	}
	
	public function get_search_request($args)
	{
		$now = new Date();
		$weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;
		
		if ($this->read_authorization !== false)
		{
			return "SELECT " . $args['id_search'] . " AS id_search,
				table_name." . $this->field_id . " AS id_content,
				table_name." . $this->field_title . " AS title,
				( 2 * FT_SEARCH_RELEVANCE(table_name." . $this->field_title . ", '" . $args['search'] . "') + (FT_SEARCH_RELEVANCE(table_name." . $this->field_contents . ", '" . $args['search'] . "')" . ($this->has_short_contents ? " +
				FT_SEARCH_RELEVANCE(table_name." . $this->field_short_contents . ", '" . $args['search'] . "')) / 2 " : ")") . ") / 3 * " . $weight . " AS relevance,
				CONCAT(" . ($this->custom_all_link ? $this->custom_all_link : "'" . PATH_TO_ROOT . "/" . $this->module_id . "/" . (!ServerEnvironmentConfig::load()->is_url_rewriting_enabled() ? "index.php?url=/" : "") . "', " . ($this->cats_table_name ? "id_category, '-', IF(id_category != 0, cat.rewrited_name, 'root'), '/', " : "") . ($this->custom_link_end ? $this->custom_link_end : "table_name." . $this->field_id . ", '-', " . (!$this->has_second_table ? "table_name." : "") . $this->field_rewrited_title)) . ") AS link
				FROM " . $this->table_name . " table_name
				" . ($this->has_second_table ? "LEFT JOIN " . $this->second_table_name . " " . $this->second_table_label . " ON " . $this->second_table_label . "." . $this->second_table_id . " = table_name." . $this->second_table_foreign_id : "") . "
				" . ($this->cats_table_name ? "LEFT JOIN " . $this->cats_table_name . " cat ON table_name.id_category = cat.id" : "") . "
				" . ($this->use_keywords ? "LEFT JOIN " . DB_TABLE_KEYWORDS_RELATIONS . " relation ON relation.module_id = '" . $this->module_id . "' AND relation.id_in_module = table_name." . $this->field_id . "
				LEFT JOIN " . DB_TABLE_KEYWORDS . " keyword ON keyword.id = relation.id_keyword" : "") . "
				WHERE ( FT_SEARCH(table_name." . $this->field_title . ", '" . $args['search'] . "') OR FT_SEARCH(table_name." . $this->field_contents . ", '" . $args['search'] . "')" . ($this->has_short_contents ? " OR FT_SEARCH_RELEVANCE(table_name." . $this->field_short_contents . ", '" . $args['search'] . "')" : "") . " )" . ($this->use_keywords ? " OR keyword.rewrited_name = '" . Url::encode_rewrite($args['search']) . "'" : "") . "
				" . ($this->cats_table_name ? "AND id_category IN (" . implode(", ", $this->authorized_categories) . ")" : "") . "
				" . ($this->has_approbation ? "AND (" . $this->field_approbation_type . " = " . $this->approved_value . ($this->has_validation_period ? " OR (" . $this->field_approbation_type . " = " . $this->validation_period_value . " AND " . $this->field_validation_start_date . " < '" . $now->get_timestamp() . "' AND (" . $this->field_validation_end_date . " > '" . $now->get_timestamp() . "' OR " . $this->field_validation_end_date . " = 0))" : "") . ")" : "") . "
				GROUP BY id_content
				ORDER BY relevance DESC
				LIMIT " . $this->max_search_results . " OFFSET 0";
		}
		
		return '';
	}
}
?>
