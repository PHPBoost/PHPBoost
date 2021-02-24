<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 02 25
 * @since       PHPBoost 3.0 - 2012 03 02
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class PagesConfig extends DefaultModuleConfig
{
	const DEFAULT_CONTENT           = 'default_content';
	const ROOT_CATEGORY_DESCRIPTION = 'root_category_description';
	const DEFERRED_OPERATIONS       = 'deferred_operations';
	const LEFT_COLUMN_DISABLED      = 'left_column_disabled';
	const RIGHT_COLUMN_DISABLED     = 'right_column_disabled';
	const VIEWS_NUMBER_ENABLED      = 'views_number_enabled';

	public static function __static()
	{
		self::$module_id = 'pages';
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
			self::DEFAULT_CONTENT => '',
			self::ROOT_CATEGORY_DESCRIPTION => CategoriesService::get_default_root_category_description('pages'),
			self::AUTHORIZATIONS => array('r-1' => 33, 'r0' => 37, 'r1' => 61),
			self::DEFERRED_OPERATIONS => array(),
			self::LEFT_COLUMN_DISABLED => false,
			self::RIGHT_COLUMN_DISABLED => false,
			self::VIEWS_NUMBER_ENABLED => true,
		);
	}
}
?>
