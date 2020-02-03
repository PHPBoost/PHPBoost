<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 03
 * @since       PHPBoost 4.0 - 2013 02 25
*/

class CalendarCategoriesCache extends DefaultCategoriesCache
{
	public function get_category_class()
	{
		return 'CalendarCategory';
	}

	public function get_module_identifier()
	{
		return 'calendar';
	}

	protected function get_category_elements_number($id_category)
	{
		return 0;
	}

	protected function get_root_category_authorizations()
	{
		return CalendarConfig::load()->get_authorizations();
	}
}
?>
