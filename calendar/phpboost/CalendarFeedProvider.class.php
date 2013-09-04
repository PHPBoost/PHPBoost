<?php
/*##################################################
 *		             CalendarFeedProvider.class.php
 *                            -------------------
 *   begin                : February 25, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Comments Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Comments Public License for more details.
 *
 * You should have received a copy of the GNU Comments Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

class CalendarFeedProvider implements FeedProvider
{
	public function get_feeds_list()
	{
		return CalendarService::get_categories_manager()->get_feeds_categories_module()->get_feed_list();
	}
	
	public function get_feed_data_struct($idcat = 0, $name = '')
	{
		$now = new Date();
		$lang = LangLoader::get('common', 'calendar');
		$querier = PersistenceContext::get_querier();
		
		$categories = CalendarService::get_categories_manager()->get_categories_cache()->get_categories();
		$category = $categories[$idcat];
		
		$site_name = GeneralConfig::load()->get_site_name();
		$site_name = $idcat != Category::ROOT_CATEGORY ? $site_name . ' : ' . $category->get_name() : $site_name;
		
		$feed_module_name = $lang['calendar.feed.name'];
		$data = new FeedData();
		$data->set_title($feed_module_name . ' - ' . $site_name);
		$data->set_date(new Date());
		$data->set_link(SyndicationUrlBuilder::rss('calendar', $idcat));
		$data->set_host(HOST);
		$data->set_desc($feed_module_name . ' - ' . $site_name);
		$data->set_lang(LangLoader::get_message('xml_lang', 'main'));
		$data->set_auth_bit(Category::READ_AUTHORIZATIONS);
		
		if ($idcat == Category::ROOT_CATEGORY)
		{
			$ids_categories[] = $idcat;
		}
		else
		{
			$search_category_children_options = new SearchCategoryChildrensOptions();
			$categories = CalendarService::get_categories_manager()->get_childrens($idcat, $search_category_children_options);
			$ids_categories = array_keys($categories);
		}
		
		$result = $querier->select('SELECT *
		FROM ' . CalendarSetup::$calendar_table . ' calendar
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id=calendar.author_id
		LEFT JOIN '. CalendarSetup::$calendar_cats_table .' cat ON cat.id = calendar.id_category
		WHERE calendar.id_category IN :cats_ids
		ORDER BY calendar.start_date DESC', array(
			'cats_ids' => $ids_categories
		));
		
		while ($row = $result->fetch())
		{
			$event = new CalendarEvent();
			$event->set_properties($row);
			
			$category = $categories[$event->get_id_cat()];
			
			$link = CalendarUrlBuilder::display_event($category->get_id(), $category->get_rewrited_name() ? $category->get_rewrited_name() : 'root', $event->get_id(), $event->get_title())->absolute();
			
			$item = new FeedItem();
			$item->set_title($event->get_title());
			$item->set_link($link);
			$item->set_guid($link);
			$item->set_desc(FormatingHelper::second_parse($event->get_contents()) . ($event->get_location() ? '<br />' . $lang['calendar.labels.location'] . ' : ' . $event->get_location() . '<br />' : '') . '<br />' . $lang['calendar.labels.start_date'] . ' : ' . $event->get_start_date()->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE) . '<br />' . $lang['calendar.labels.end_date'] . ' : ' . $event->get_end_date()->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE));
			$item->set_date($event->get_creation_date());
			$item->set_image_url($category->get_image());
			$item->set_auth(CalendarService::get_categories_manager()->get_heritated_authorizations($category->get_id(), Category::READ_AUTHORIZATIONS, Authorizations::AUTH_PARENT_PRIORITY));
			$data->add_item($item);
		}
		$result->dispose();
		
		return $data;
	}
}
?>
