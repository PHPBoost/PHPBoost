<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 05
 * @since       PHPBoost 3.0 - 2012 11 22
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
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
		return LangLoader::get_message('calendar.module.title', 'common', 'calendar');
	}

	public function is_displayed()
	{
		return !Url::is_current_url('/calendar/') && CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, 'calendar')->read();
	}

	public function get_menu_content()
	{
		$view = new FileTemplate('calendar/CalendarModuleMiniMenu.tpl');
		$view->add_lang(LangLoader::get_all_langs('calendar'));
		MenuService::assign_positions_conditions($view, $this->get_block());

		$view->put('CALENDAR', CalendarAjaxCalendarController::get_view(true));

		return $view->render();
	}
}
?>
