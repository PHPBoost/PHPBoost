<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 17
 * @since       PHPBoost 4.0 - 2013 02 27
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class ArticlesConfig extends DefaultRichModuleConfig
{
	const NUMBER_CHARACTER_TO_CUT = 'number_character_to_cut';

	const CATS_ICON_ENABLED = 'cats_icon_enabled';
	const DATE_UPDATED_DISPLAYED = 'date_updated_displayed';

	public function __construct()
	{
		parent::__construct('articles');
	}

	public function enable_cats_icon()
	{
		$this->set_property(self::CATS_ICON_ENABLED, true);
	}

	public function disable_cats_icon() {
		$this->set_property(self::CATS_ICON_ENABLED, false);
	}

	public function are_cats_icon_enabled()
	{
		return $this->get_property(self::CATS_ICON_ENABLED);
	}

	public function get_default_values()
	{
		return array_merge(
			parent::get_default_values(),
			array(
			self::CATEGORIES_PER_ROW       => 2,
			self::ITEMS_PER_PAGE           => 10,
			self::NUMBER_CHARACTER_TO_CUT  => 150,
			self::CATS_ICON_ENABLED        => false,
			self::DATE_UPDATED_DISPLAYED   => false
			)
		);
	}
}
?>
