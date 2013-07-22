<?php
/*##################################################
 *		             CalendarFeedProvider.class.php
 *                            -------------------
 *   begin                : February 25, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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
		$querier = PersistenceContext::get_querier();

		$category = CalendarService::get_categories_manager()->get_categories_cache()->get_category($idcat);

		$site_name = GeneralConfig::load()->get_site_name();
		$site_name = $idcat != Category::ROOT_CATEGORY ? $site_name . ' : ' . $category->get_name() : $site_name;

		$feed_module_name = LangLoader::get_message('calendar.feed.name', 'calendar_common', 'calendar');
		$data = new FeedData();
		$data->set_title($feed_module_name . ' - ' . $site_name);
		$data->set_date(new Date());
		$data->set_link(SyndicationUrlBuilder::rss('calendar', $idcat));
		$data->set_host(HOST);
		$data->set_desc($feed_module_name . ' - ' . $site_name);
		$data->set_lang(LangLoader::get_message('xml_lang', 'main'));
		$data->set_auth_bit(Category::READ_AUTHORIZATIONS);
		
		$ids_categories = array_keys($categories);
		if ($idcat != Category::ROOT_CATEGORY)
		{
			$ids_categories[] = $idcat;
		}
		else
		{
			$search_category_children_options = new SearchCategoryChildrensOptions();
			$categories = CalendarService::get_categories_manager()->get_childrens($idcat, $search_category_children_options);
			$ids_categories = array_keys($categories);
		}
		
		if (!empty($ids_categories))
		{
			$now = new Date();
			
			$results = $querier->select('SELECT calendar.id, calendar.id_category, calendar.title, calendar.contents, calendar.creation_date, cat.rewrited_name AS rewrited_name_cat
			 FROM ' . CalendarSetup::$calendar_table . ' calendar
			 LEFT JOIN '. CalendarSetup::$calendar_cats_table .' cat ON cat.id = calendar.id_category
			 WHERE calendar.start_date < :timestamp_now AND calendar.end_date > :timestamp_now AND calendar.id_category IN :cats_ids
			 ORDER BY calendar.start_date DESC', array(
				'cats_ids' => $ids_categories,
				'timestamp_now' => $now->get_timestamp()
			));

			foreach ($results as $row)
			{
				$row['rewrited_name_cat'] = !empty($row['id_category']) ? $row['rewrited_name_cat'] : 'root';
				$link = CalendarUrlBuilder::display_event($row['rewrited_name_cat'], $row['id'], $row['title'])->absolute();
				
				$item = new FeedItem();
				$item->set_title($row['title']);
				$item->set_link($link);
				$item->set_guid($link);
				$item->set_desc(FormatingHelper::second_parse($row['contents']));
				$item->set_date(new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $row['creation_date']));
				//$item->set_image_url($row['img']);
				$item->set_auth(CalendarService::get_categories_manager()->get_heritated_authorizations($row['id_category'], Category::READ_AUTHORIZATIONS, Authorizations::AUTH_PARENT_PRIORITY));
				$data->add_item($item);
			}
			$results->dispose();
		}

		return $data;
	}
}
?>