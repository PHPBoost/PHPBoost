<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 04 05
 * @since       PHPBoost 6.0 - 2021 02 02
*/

class NewsConfigUpdateVersion extends ConfigUpdateVersion
{
	public function __construct()
	{
		parent::__construct('news');

		$this->config_parameters_to_modify = array(
			'number_news_per_page'             => 'items_per_page',
			'number_columns_display_news'      => 'items_per_row',
			'display_condensed_enabled'        => 'full_item_display',
			'descriptions_displayed_to_guests' => 'summary_displayed_to_guests',
			'number_character_to_cut'          => 'auto_cut_characters_number',
			'news_suggestions_enabled'         => 'items_suggestions_enabled',
			'nb_view_enabled'                  => 'views_number_enabled',
			'display_type'                     => array(
				'parameter_name' => 'display_type',
				'values'         => array(
					'block' => 'grid_view',
					'list'  => 'list_view'
				)
			)
		);
	}
}
?>
