<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 07 09
 * @since       PHPBoost 4.0 - 2013 02 25
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CalendarFeedProvider implements FeedProvider
{
	public function get_feeds_list()
	{
		return CategoriesService::get_categories_manager('calendar')->get_feeds_categories_module()->get_feed_list();
	}

	public function get_feed_data_struct($idcat = 0, $name = '')
	{
		$module_id = 'calendar';
		if (CategoriesService::get_categories_manager($module_id)->get_categories_cache()->category_exists($idcat))
		{
            $lang = LangLoader::get('common', 'calendar');
			$now = new Date();
			$querier = PersistenceContext::get_querier();

			$category = CategoriesService::get_categories_manager($module_id)->get_categories_cache()->get_category($idcat);

			$site_name = GeneralConfig::load()->get_site_name();
			$site_name = $idcat != Category::ROOT_CATEGORY ? $site_name . ' : ' . $category->get_name() : $site_name;

			$feed_module_name = $lang['calendar.feed.name'];
			$data = new FeedData();
			$data->set_title($feed_module_name . ' - ' . $site_name);
			$data->set_date(new Date());
			$data->set_link(SyndicationUrlBuilder::rss('calendar', $idcat));
			$data->set_host(HOST);
			$data->set_desc($feed_module_name . ' - ' . $site_name);
			$data->set_lang(LangLoader::get_message('common.xml.lang', 'common-lang'));
			$data->set_auth_bit(Category::READ_AUTHORIZATIONS);

			$categories = CategoriesService::get_categories_manager($module_id)->get_children($idcat, new SearchCategoryChildrensOptions(), true);
			$ids_categories = array_keys($categories);

			$result = $querier->select('SELECT *
                FROM ' . CalendarSetup::$calendar_events_table . ' event
                LEFT JOIN ' . CalendarSetup::$calendar_events_content_table . ' event_content ON event_content.id = event.content_id
                LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = event_content.author_user_id
                LEFT JOIN '. CalendarSetup::$calendar_cats_table .' cat ON cat.id = event_content.id_category
                WHERE approved = 1
                AND id_category IN :cats_ids
                AND start_date > :timestamp_now
                ORDER BY start_date ASC', array(
                    'cats_ids' => $ids_categories,
                    'timestamp_now' => $now->get_timestamp()
                )
            );

			while ($row = $result->fetch())
			{
				$item = new CalendarItem();
				$item->set_properties($row);

				if (!$item->get_content()->is_cancelled())
				{
					$category = $categories[$item->get_content()->get_id_category()];

					$link = CalendarUrlBuilder::display($category->get_id(), $category->get_rewrited_name() ? $category->get_rewrited_name() : 'root', $item->get_id(), $item->get_content()->get_rewrited_title());

					$feed_item = new FeedItem();
					$feed_item->set_title($item->get_content()->get_title());
					$feed_item->set_link($link);
					$feed_item->set_guid($link);
					$feed_item->set_desc(FormatingHelper::second_parse($item->get_content()->get_content()) . ($item->get_content()->get_location() ? '<br />' . $lang['calendar.location'] . ' : ' . $item->get_content()->get_location() . '<br />' : '') . '<br />' . $lang['calendar.start.date'] . ' : ' . $item->get_start_date()->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE) . '<br />' . $lang['calendar.end.date'] . ' : ' . $item->get_end_date()->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE));
					$feed_item->set_date($item->get_start_date());
					$feed_item->set_image_url($item->get_content()->get_thumbnail()->rel());
					$feed_item->set_auth(CategoriesService::get_categories_manager($module_id)->get_heritated_authorizations($category->get_id(), Category::READ_AUTHORIZATIONS, Authorizations::AUTH_PARENT_PRIORITY));
					$data->add_item($feed_item);
				}
			}
			$result->dispose();

			return $data;
		}
	}
}
?>
