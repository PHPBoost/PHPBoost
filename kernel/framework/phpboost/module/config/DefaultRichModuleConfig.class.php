<?php
/**
 * @package     PHPBoost
 * @subpackage  Module\config
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 04
 * @since       PHPBoost 5.3 - 2020 01 10
*/

class DefaultRichModuleConfig extends DefaultModuleConfig
{
	const ITEMS_PER_ROW = 'items_per_row';
	const ITEMS_DEFAULT_SORT_FIELD = 'items_default_sort_field';
	const ITEMS_DEFAULT_SORT_MODE = 'items_default_sort_mode';

	const DEFAULT_CONTENT = 'default_content';
	const SUMMARY_DISPLAYED_TO_GUESTS = 'summary_displayed_to_guests';
	const AUTO_CUT_CHARACTERS_NUMBER = 'auto_cut_characters_number';
	const AUTHOR_DISPLAYED = 'author_displayed';
	const VIEWS_NUMBER_ENABLED = 'views_number_enabled';
	
	const CATEGORIES_PER_PAGE = 'categories_per_page';
	const CATEGORIES_PER_ROW = 'categories_per_row';
	const ROOT_CATEGORY_DESCRIPTION = 'root_category_description';

	const DISPLAY_TYPE = 'display_type';
	const GRID_VIEW = 'grid_view';
	const LIST_VIEW = 'list_view';
	const TABLE_VIEW = 'table_view';

	const DEFERRED_OPERATIONS = 'deferred_operations';

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array_merge(
			parent::get_default_values(),
			array(
				self::ITEMS_PER_ROW               => 2,
				self::ITEMS_DEFAULT_SORT_FIELD    => 'date',
				self::ITEMS_DEFAULT_SORT_MODE     => Item::DESC,
				self::DEFAULT_CONTENT             => '',
				self::SUMMARY_DISPLAYED_TO_GUESTS => false,
				self::AUTO_CUT_CHARACTERS_NUMBER  => 150,
				self::AUTHOR_DISPLAYED            => true,
				self::VIEWS_NUMBER_ENABLED        => false,
				self::CATEGORIES_PER_PAGE         => 10,
				self::CATEGORIES_PER_ROW          => 3,
				self::ROOT_CATEGORY_DESCRIPTION   => CategoriesService::get_default_root_category_description(self::$module_id),
				self::DISPLAY_TYPE                => self::GRID_VIEW,
				self::DEFERRED_OPERATIONS         => array()
			)
		);
	}
}
?>
