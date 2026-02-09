<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2022 11 03
 * @since       PHPBoost 6.0 - 2021 04 06
*/

class MediaConfigUpdateVersion extends ConfigUpdateVersion
{
	public function __construct()
	{
		parent::__construct('media');

		$this->config_parameters_to_modify = array(
			'items_number_per_page' => 'items_per_page',
			'items_number_per_row' => 'items_per_row',
			'categories_number_per_page' => 'categories_per_page',
			'categories_number_per_row' => 'categories_per_row',
			'root_category_description' => array(
				'parameter_name' => 'root_category_description',
				'value' => $this->get_parsed_old_content('MediaConfig', 'root_category_description')
			)
		);
	}
}
?>
