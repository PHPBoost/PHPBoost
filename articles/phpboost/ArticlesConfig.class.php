<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 01
 * @since       PHPBoost 4.0 - 2013 02 27
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ArticlesConfig extends DefaultRichModuleConfig
{
	public function get_additional_default_values()
	{
		return array(
			self::DISPLAY_TYPE         => self::GRID_VIEW,
			self::ITEMS_PER_PAGE       => 10,
			self::CATEGORIES_PER_ROW   => 2,
			self::VIEWS_NUMBER_ENABLED => true
		);
	}
}
?>
