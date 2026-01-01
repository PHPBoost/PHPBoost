<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2025 01 10
 * @since       PHPBoost 3.0 - 2012 03 02
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class PagesConfig extends DefaultRichModuleConfig
{
	const LEFT_COLUMN_DISABLED      = 'left_column_disabled';
	const RIGHT_COLUMN_DISABLED     = 'right_column_disabled';

	/**
	 * {@inheritdoc}
	 */
	public function get_additional_default_values()
	{
		return [
			self::SORT_FORM_DISPLAYED   => false,
			self::VIEWS_NUMBER_ENABLED  => true,
			self::DISPLAY_TYPE          => self::TABLE_VIEW,
			self::AUTHORIZATIONS        => ['r-1' => 1, 'r0' => 3, 'r1' => 49],
			self::LEFT_COLUMN_DISABLED  => false,
			self::RIGHT_COLUMN_DISABLED => false
		];
	}
}
?>
