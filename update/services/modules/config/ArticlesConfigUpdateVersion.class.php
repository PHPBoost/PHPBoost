<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 11 03
 * @since       PHPBoost 6.0 - 2020 05 03
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ArticlesConfigUpdateVersion extends ConfigUpdateVersion
{
	public function __construct()
	{
		parent::__construct('articles');

		$this->config_parameters_to_modify = array(
			'number_articles_per_page'         => 'items_per_page',
			'number_categories_per_page'       => 'categories_per_page',
			'number_cols_display_per_line'     => 'items_per_row',
			'number_character_to_cut'          => 'auto_cut_characters_number',
			'descriptions_displayed_to_guests' => 'summary_displayed_to_guests',
			'date_updated_displayed'           => 'update_date_displayed',
			'items_default_sort_field'         => array(
				'parameter_name' => 'items_default_sort_field',
				'values'         => array(
					'date_created'    => 'date',
					'display_name'    => 'author',
					'number_view'     => 'views',
					'average_notes'   => 'notes',
					'comments_number' => 'comments'
				)
			),
			'items_default_sort_mode' => array(
				'parameter_name' => 'items_default_sort_mode',
				'values'         => array(
					Item::ASC  => TextHelper::strtolower(Item::ASC),
					Item::DESC => TextHelper::strtolower(Item::DESC)
				)
			),
			'display_type' => array(
				'parameter_name' => 'display_type',
				'values'         => array(
					'mosaic' => 'grid_view',
					'list'   => 'list_view'
				)
			),
			'root_category_description' => array(
				'parameter_name' => 'root_category_description',
				'value' => $this->get_parsed_old_content('ArticlesConfig', 'root_category_description')
			)
		);
	}
}
?>
