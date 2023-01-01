<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 01
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
		return array(
			self::SORT_FORM_DISPLAYED   => false,
			self::VIEWS_NUMBER_ENABLED  => true,
			self::DISPLAY_TYPE          => self::TABLE_VIEW,
			self::AUTHORIZATIONS        => array('r-1' => 33, 'r0' => 37, 'r1' => 61),
			self::LEFT_COLUMN_DISABLED  => false,
			self::RIGHT_COLUMN_DISABLED => false
		);
	}
}
?>
