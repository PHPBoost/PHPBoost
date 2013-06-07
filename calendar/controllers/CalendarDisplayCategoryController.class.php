<?php
/*##################################################
 *                              CalendarDisplayCategoryController.class.php
 *                            -------------------
 *   begin                : February 25, 2013
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
 *
 *  
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

class CalendarDisplayCategoryController extends ModuleController
{       
	private $lang;
	private $tpl;
	
	private $category;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();
		
		$this->init();
		
		$this->build_view();
		
		return $this->generate_response();
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('calendar_common', 'calendar');
		$this->tpl = new FileTemplate('calendar/CalendarDisplayCategoryController.tpl');
	}
	
	private function build_view()
	{
		$now = new Date(DATE_NOW, TIMEZONE_AUTO);
		
		$result = PersistenceContext::get_querier()->select('SELECT calendar.*, member.level, member.user_groups
		FROM '. CalendarSetup::$calendar_table .' calendar
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = calendar.author_id
		WHERE calendar.start_date < :timestamp_now AND calendar.end_date > :timestamp_now', array(
			'timestamp_now' => $now->get_timestamp()));

		while ($row = $result->fetch())
		{

		}
	}
	
	private function get_category()
	{
		if ($this->category === null)
		{
			$rewrited_name = AppContext::get_request()->get_getstring('rewrited_name', '');
			if (!empty($rewrited_name))
			{
				try {
					$row = PersistenceContext::get_querier()->select_single_row(CalendarSetup::$calendar_cats_table, array('*'), 'WHERE rewrited_name=:rewrited_name', array('rewrited_name' => $rewrited_name));

					$category = new CalendarRichCategory();
					$category->set_properties($row);
					$this->category = $category;
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->category = CalendarService::get_categories_manager()->get_categories_cache()->get_category(Category::ROOT_CATEGORY);
			}
		}
		return $this->category;
	}
	
	private function check_authorizations()
	{
		$id_cat = $this->get_category()->get_id();
		if ($id_cat !== Category::ROOT_CATEGORY)
		{
			if (!CalendarAuthorizationsService::check_authorizations($id_cat)->read())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
		   		DispatchManager::redirect($error_controller);
			}
		}
	}
	
	private function generate_response()
	{
		$response = new CalendarDisplayResponse();
		$response->set_page_title($this->get_category()->get_name());
		
		$response->add_breadcrumb_link($this->lang['calendar.module_title'], CalendarUrlBuilder::home());
		
		$categories = array_reverse(CalendarService::get_categories_manager()->get_parents($this->get_category()->get_id(), true));
		foreach ($categories as $id => $category)
		{
			if ($id != Category::ROOT_CATEGORY)
				$response->add_breadcrumb_link($category->get_name(), CalendarUrlBuilder::display_category($id, $category->get_rewrited_name()));
		}

		return $response->display($this->tpl);
	}
}
?>