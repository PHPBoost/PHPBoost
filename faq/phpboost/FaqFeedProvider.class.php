<?php
/*##################################################
 *                               FaqFeedProvider.class.php
 *                            -------------------
 *   begin                : September 2, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */

class FaqFeedProvider implements FeedProvider
{
	public function get_feeds_list()
	{
		return FaqService::get_categories_manager()->get_feeds_categories_module()->get_feed_list();
	}
	
	public function get_feed_data_struct($idcat = 0, $name = '')
	{
		if (FaqService::get_categories_manager()->get_categories_cache()->category_exists($idcat))
		{
			$querier = PersistenceContext::get_querier();
			$category = FaqService::get_categories_manager()->get_categories_cache()->get_category($idcat);
			
			$site_name = GeneralConfig::load()->get_site_name();
			$site_name = $idcat != Category::ROOT_CATEGORY ? $site_name . ' : ' . $category->get_name() : $site_name;
			
			$feed_module_name = LangLoader::get_message('module_title', 'common', 'faq');
			$data = new FeedData();
			$data->set_title($feed_module_name . ' - ' . $site_name);
			$data->set_date(new Date());
			$data->set_link(SyndicationUrlBuilder::rss('faq', $idcat));
			$data->set_host(HOST);
			$data->set_desc($feed_module_name . ' - ' . $site_name);
			$data->set_lang(LangLoader::get_message('xml_lang', 'main'));
			$data->set_auth_bit(Category::READ_AUTHORIZATIONS);
			
			$categories = FaqService::get_categories_manager()->get_children($idcat, new SearchCategoryChildrensOptions(), true);
			$ids_categories = array_keys($categories);
			
			$results = $querier->select('SELECT faq.id, faq.id_category, faq.question, faq.answer, faq.creation_date, cat.rewrited_name AS rewrited_name_cat
				FROM ' . FaqSetup::$faq_table . ' faq
				LEFT JOIN '. FaqSetup::$faq_cats_table .' cat ON cat.id = faq.id_category
				WHERE approved = 1
				AND faq.id_category IN :ids_categories
				ORDER BY faq.creation_date DESC', array(
				'ids_categories' => $ids_categories
			));
	
			foreach ($results as $row)
			{
				$row['rewrited_name_cat'] = !empty($row['id_category']) ? $row['rewrited_name_cat'] : 'root';
				$link = FaqUrlBuilder::display($row['id_category'], $row['rewrited_name_cat'], $row['id']);
				
				$item = new FeedItem();
				$item->set_title($row['question']);
				$item->set_link($link);
				$item->set_guid($link);
				$item->set_desc(FormatingHelper::second_parse($row['answer']));
				$item->set_date(new Date($row['creation_date'], Timezone::SERVER_TIMEZONE));
				$item->set_auth(FaqService::get_categories_manager()->get_heritated_authorizations($row['id_category'], Category::READ_AUTHORIZATIONS, Authorizations::AUTH_PARENT_PRIORITY));
				$data->add_item($item);
			}
			$results->dispose();
			
			return $data;
		}
	}
}
?>