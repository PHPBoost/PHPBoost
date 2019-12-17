<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 11
 * @since       PHPBoost 3.0 - 2012 11 22
*/

class CalendarModuleMiniMenu extends ModuleMiniMenu
{
	public function get_default_block()
	{
		return self::BLOCK_POSITION__LEFT;
	}

	public function get_menu_id()
	{
		return 'module-mini-calendar';
	}

	public function get_menu_title()
	{
		return LangLoader::get_message('module_title', 'common', 'calendar');
	}

	public function is_displayed()
	{
		return !Url::is_current_url('/calendar/') && CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, 'calendar')->read();
	}

	public function get_menu_content()
	{
		$tpl = new FileTemplate('calendar/CalendarModuleMiniMenu.tpl');
		$tpl->add_lang(LangLoader::get('common', 'calendar'));
		MenuService::assign_positions_conditions($tpl, $this->get_block());

		$tpl->put('CALENDAR', CalendarAjaxCalendarController::get_view(true));

		return $tpl->render();
	}
}
?>
