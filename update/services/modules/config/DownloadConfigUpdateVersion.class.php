<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 11 03
 * @since       PHPBoost 6.0 - 2020 05 06
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DownloadConfigUpdateVersion extends ConfigUpdateVersion
{

	public function __construct()
	{
		parent::__construct('download');

		$this->config_parameters_to_modify = array(
			'items_number_per_page'            => 'items_per_page',
			'categories_number_per_page'       => 'categories_per_page',
			'columns_number_per_line'          => 'items_per_row',
			'descriptions_displayed_to_guests' => 'summary_displayed_to_guests',
			'items_default_sort_field'         => array(
				'parameter_name' => 'items_default_sort_field',
				'values'         => array(
					'name'             => 'title',
					'updated_date'     => 'update_date',
					'number_downloads' => 'downloads_number',
					'number_view'      => 'views_number'
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
			'sort_type' => array(
				'parameter_name' => 'sort_type',
				'values'         => array(
					'name'             => 'title',
					'updated_date'     => 'update_date',
					'number_downloads' => 'downloads_number',
					'number_view'      => 'views_number'
				)
			),
			'root_category_description' => array(
				'parameter_name' => 'root_category_description',
				'value' => $this->get_parsed_old_content('DownloadConfig', 'root_category_description')
			)
		);
	}
}
?>
