<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 14
 * @since       PHPBoost 5.3 - 2019 08 20
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
	protected $field_content = 'content';
	
	protected $has_summary = false;
	protected $field_summary = 'summary';
	
	protected $has_approbation = true;
	protected $field_published = 'published';
	
	protected $has_validation_period = false;
	protected $field_validation_start_date = 'publishing_start_date';
	protected $field_validation_end_date = 'publishing_end_date';
	
	protected $group_by = 'id_content';
	
	protected $max_search_results = 100;
	
	public function __construct($module_id, $custom_link_end = '', $custom_all_link = '', $max_search_results = 100)
	{
		$this->module_id = $module_id;
		$module_configuration = ModulesManager::get_module($this->module_id)->get_configuration();
		$item_class_name = $module_configuration->has_items() ? $module_configuration->get_item_name() : '';
		
		$this->table_name = $module_configuration->get_items_table_name();
		$this->cats_table_name = $module_configuration->has_categories() ? $module_configuration->get_categories_table_name() : '';
		
		$this->read_authorization = $module_configuration->has_categories() ? CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, $this->module_id)->read() : ItemsAuthorizationsService::check_authorizations($this->module_id)->read();
		$this->authorized_categories = $module_configuration->has_categories() ? CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, ($module_configuration->has_rich_config_parameters() ? $module_configuration->get_configuration_parameters()->get_summary_displayed_to_guests() : true), $this->module_id) : array();
		
		$this->field_title = $module_configuration->has_items() ? $item_class_name::get_title_label() : 'title';
		$this->field_rewrited_title = 'rewrited_' . $this->field_title;
		$this->field_content = $module_configuration->has_items() ? $item_class_name::get_content_label() : 'content';
		$this->has_summary = $module_configuration->has_rich_items();
		$this->use_keywords = $module_configuration->feature_is_enabled('keywords');
		$this->has_validation_period = $module_configuration->feature_is_enabled('deferred_publication');
		
		$this->custom_link_end = $custom_link_end;
		$this->custom_all_link = $custom_all_link;
		$this->max_search_results = $max_search_results;
	}
	
	public function get_search_request($args)
	{
		$now = new Date();
		$weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;
		$table_label = $this->module_id . '_table_name';
		$this->field_summary = ($this->field_summary == 'description' ? $table_label . '.description' : $this->field_summary);
		
		if ($this->read_authorization !== false)
		{
			return "SELECT " . $args['id_search'] . " AS id_search,
				" . $table_label . "." . $this->field_id . " AS id_content,
				" . ($this->field_title == 'name' ? $table_label . "." : "") . $this->field_title . " AS title,
				( 2 * FT_SEARCH_RELEVANCE(" . ($this->field_title == 'name' ? $table_label . "." : "") . $this->field_title . ", '" . $args['search'] . "' IN BOOLEAN MODE) + (FT_SEARCH_RELEVANCE(" . $this->field_content . ", '" . $args['search'] . "' IN BOOLEAN MODE)" . ($this->has_summary ? " +
				FT_SEARCH_RELEVANCE(" . $this->field_summary . ", '" . $args['search'] . "' IN BOOLEAN MODE)) / 2 " : ")") . ") / 3 * " . $weight . " AS relevance,
				CONCAT(" . ($this->custom_all_link ? $this->custom_all_link : "'" . PATH_TO_ROOT . "/" . $this->module_id . "/" . (!ServerEnvironmentConfig::load()->is_url_rewriting_enabled() ? "index.php?url=/" : "") . "', " . ($this->cats_table_name ? "id_category, '-', IF(id_category != 0, cat.rewrited_name, 'root'), '/', " : "") . ($this->custom_link_end ? $this->custom_link_end : $table_label . "." . $this->field_id . ", '-', " . (!$this->has_second_table ? $table_label . "." : "") . $this->field_rewrited_title)) . ") AS link
				FROM " . $this->table_name . " " . $table_label . "
				" . ($this->has_second_table ? "LEFT JOIN " . $this->second_table_name . " " . $this->second_table_label . " ON " . $this->second_table_label . "." . $this->second_table_id . " = " . $table_label . "." . $this->second_table_foreign_id : "") . "
				" . ($this->cats_table_name ? "LEFT JOIN " . $this->cats_table_name . " cat ON id_category = cat.id" : "") . "
				" . ($this->use_keywords ? "LEFT JOIN " . DB_TABLE_KEYWORDS_RELATIONS . " relation ON relation.module_id = '" . $this->module_id . "' AND relation.id_in_module = " . $table_label . "." . $this->field_id . "
				LEFT JOIN " . DB_TABLE_KEYWORDS . " keyword ON keyword.id = relation.id_keyword" : "") . "
				WHERE ( FT_SEARCH(" . ($this->field_title == 'name' ? $table_label . "." : "") . $this->field_title . ", '" . $args['search'] . "*' IN BOOLEAN MODE) OR FT_SEARCH(" . $this->field_content . ", '" . $args['search'] . "*' IN BOOLEAN MODE)" . ($this->has_summary ? " OR FT_SEARCH_RELEVANCE(" . $this->field_summary . ", '" . $args['search'] . "*' IN BOOLEAN MODE)" : "") . " )" . ($this->use_keywords ? " OR keyword.rewrited_name = '" . Url::encode_rewrite($args['search']) . "'" : "") . "
				" . ($this->cats_table_name ? "AND id_category IN (" . implode(", ", $this->authorized_categories) . ")" : "") . "
				" . ($this->has_approbation ? "AND (" . $this->field_published . " = " . Item::PUBLISHED . ($this->has_validation_period ? " OR (" . $this->field_published . " = " . Item::DEFERRED_PUBLICATION . " AND " . $this->field_validation_start_date . " < '" . $now->get_timestamp() . "' AND (" . $this->field_validation_end_date . " > '" . $now->get_timestamp() . "' OR " . $this->field_validation_end_date . " = 0))" : "") . ")" : "") . "
				GROUP BY " . $this->group_by . "
				ORDER BY relevance DESC
				LIMIT " . $this->max_search_results . " OFFSET 0";
		}
		
		return '';
	}
}
?>
