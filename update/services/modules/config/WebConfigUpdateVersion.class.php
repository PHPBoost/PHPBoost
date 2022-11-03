<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 11 03
 * @since       PHPBoost 5.0 - 2017 04 05
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class WebConfigUpdateVersion extends ConfigUpdateVersion
{
	public function __construct()
	{
		parent::__construct('web');

		$this->config_parameters_to_modify = array(
			'items_number_per_page'            => 'items_per_page',
			'categories_number_per_page'       => 'categories_per_page',
			'columns_number_per_line'          => 'items_per_row',
			'items_default_sort_field'         => array(
				'parameter_name' => 'items_default_sort_field',
				'values'         => array(
					'name'         => 'title',
					'number_views' => 'views_number'
				)
			),
			'items_default_sort_mode' => array(
				'parameter_name' => 'items_default_sort_mode',
				'values'         => array(
					Item::ASC  => TextHelper::strtolower(Item::ASC),
					Item::DESC => TextHelper::strtolower(Item::DESC)
				)
			),
			'category_display_type' => array(
				'parameter_name' => 'display_type',
				'values'         => array(
					'summary'     => 'list_view',
					'all_content' => 'list_view',
					'table'       => 'table_view'
				)
			),
			'partners_sort_field' => array(
				'parameter_name' => 'partners_sort_field',
				'values'         => array(
					'name' => 'title'
				)
			),
			'partners_sort_mode' => array(
				'parameter_name' => 'partners_sort_mode',
				'values'         => array(
					Item::ASC  => TextHelper::strtolower(Item::ASC),
					Item::DESC => TextHelper::strtolower(Item::DESC)
				)
			),
			'root_category_description' => array(
				'parameter_name' => 'root_category_description',
				'value' => $this->get_parsed_old_content('WebConfig', 'root_category_description')
			)
		);
	}
}
?>
