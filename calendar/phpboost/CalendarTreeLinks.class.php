<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 15
 * @since       PHPBoost 4.0 - 2013 11 26
 * @contributor xela <xela@phpboost.com>
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
		
		return CalendarUrlBuilder::add_event($requested_date['year'], $requested_date['month'], $requested_date['day']);
	}
	
	protected function get_module_additional_items_actions_tree_links(&$tree)
	{
		$requested_date = $this->get_requested_date();
		
		$tree->add_link(new ModuleLink(LangLoader::get_message('calendar.events.list', 'common', 'calendar'), CalendarUrlBuilder::events_list($requested_date['year'], $requested_date['month'], $requested_date['day']), $this->get_authorizations()->read()));
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
