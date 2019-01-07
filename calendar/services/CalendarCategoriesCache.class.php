<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2016 02 11
 * @since   	PHPBoost 4.0 - 2013 02 25
*/

class CalendarCategoriesCache extends CategoriesCache
{
	public function get_table_name()
	{
		return CalendarSetup::$calendar_cats_table;
	}

	public function get_category_class()
	{
		return 'CalendarCategory';
	}

	public function get_module_identifier()
	{
		return 'calendar';
	}

	public function get_root_category()
	{
		$root = new RootCategory();
		$root->set_authorizations(CalendarConfig::load()->get_authorizations());

		return $root;
	}
}
?>
