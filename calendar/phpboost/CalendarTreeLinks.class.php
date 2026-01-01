<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2025 01 12
 * @since       PHPBoost 4.0 - 2013 11 26
 * @contributor xela <xela@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CalendarTreeLinks extends DefaultTreeLinks
{
	public function __construct()
	{
		parent::__construct('calendar');
	}

	protected function get_add_item_url()
	{
		$requested_date = $this->get_requested_date();

		return CalendarUrlBuilder::add_item($requested_date['year'], $requested_date['month'], $requested_date['day']);
	}

	protected function get_module_additional_items_actions_tree_links(&$tree)
	{
		$current_user = AppContext::get_current_user()->get_id();
		$requested_date = $this->get_requested_date();
		$module_id = 'calendar';
		$lang = LangLoader::get_all_langs($module_id);

		$tree->add_link(new ModuleLink($lang['contribution.members.list'], CalendarUrlBuilder::display_member_items(), $this->get_authorizations()->read()));
		$tree->add_link(new ModuleLink($lang['calendar.my.items'], CalendarUrlBuilder::display_member_items($current_user), $this->check_write_authorization() || $this->get_authorizations()->moderation()));
		$tree->add_link(new ModuleLink($lang['calendar.items.list'], CalendarUrlBuilder::display_items_list($requested_date['year'], $requested_date['month'], $requested_date['day']), $this->get_authorizations()->read()));
	}

	private function get_requested_date()
	{
		$request = AppContext::get_request();
		return array(
			'year'  => $request->get_getint('year', date('Y')),
			'month' => $request->get_getint('month', date('n')),
			'day'   => $request->get_getint('day', date('j'))
		);
	}
}
?>
