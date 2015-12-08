<?php
/*##################################################
 *                     PHPBoostOfficialHomePageExtensionPoint.class.php
 *                            -------------------
 *   begin                : December 5, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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

class PHPBoostOfficialHomePageExtensionPoint implements HomePageExtensionPoint
{
	private $template;
	
	public function get_home_page()
	{
		$columns_disabled = ThemesManager::get_theme(AppContext::get_current_user()->get_theme())->get_columns_disabled();
		$columns_disabled->set_disable_left_columns(true);
		$columns_disabled->set_disable_right_columns(true);
		$columns_disabled->set_disable_top_central(true);
		$columns_disabled->set_disable_bottom_central(true);
		$columns_disabled->set_disable_top_footer(true);
		return new DefaultHomePage($this->get_title(), $this->get_view());
	}

	private function get_title()
	{
		return LangLoader::get_message('site_description', 'common', 'PHPBoostOfficial');
	}
	
	private function get_view()
	{
		$this->template = new FileTemplate('PHPBoostOfficial/home.tpl');
		$this->template->add_lang(LangLoader::get('common', 'PHPBoostOfficial'));
		
		$this->build_view();
		
		return $this->template;
	}
	
	private function build_view()
	{
		$now = new Date();
		$id_cats = array('37', '38', '42', '43');
		
		$querier = PersistenceContext::get_querier();
		$results = $querier->select('SELECT file.id, file.id_category, file.name, file.rewrited_name, file.short_contents, file.creation_date, file.picture_url, user.display_name
			FROM ' . PREFIX . 'download file
			LEFT JOIN ' . DB_TABLE_MEMBER . ' user ON user.user_id = file.author_user_id
			WHERE (approbation_type = 1 OR (approbation_type = 2 AND start_date < :timestamp_now AND (end_date > :timestamp_now OR end_date = 0))) AND id_category IN :children
			ORDER BY file.creation_date DESC', array(
			'timestamp_now' => $now->get_timestamp(),
				'children' => $id_cats
		));
		
		$this->build_modules_view($results);
		$this->build_themes_view($results);
		
		$this->build_feed_news_view();
		$this->build_last_news_view();
	}
	
	private function build_modules_view(SelectQueryResult $results)
	{
		$tpl = new FileTemplate('PHPBoostOfficial/modules.tpl');
		$i = 0;
		foreach ($results as $row)
		{
			if ($row['id_category'] == '38' || $row['id_category'] == '43')
			{
				if ($i >= 3)
				{
					break; 
				}
				
				$category = DownloadService::get_categories_manager()->get_categories_cache()->get_category($row['id_category']);
				$tpl->assign_block_vars('item', array(
					'U_LINK' => DownloadUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $row['id'], $row['rewrited_name'])->rel(),
					'U_IMG' => Url::to_rel($row['picture_url']),
					'C_IMG' => !empty($row['picture_url']),
					'TITLE' => $row['name'],
					'DESC' => $row['short_contents'],
					'PSEUDO' => $row['display_name']
				));
				$i++;
			}
		}
		$this->template->put('MODULES', $tpl);
	}
	
	private function build_themes_view(SelectQueryResult $results)
	{
		$tpl = new FileTemplate('PHPBoostOfficial/themes.tpl');
		$i = 0;
		foreach ($results as $row)
		{
			if ($row['id_category'] == '37' || $row['id_category'] == '42')
			{
				if ($i >= 3)
				{
					break; 
				}
				
				$category = DownloadService::get_categories_manager()->get_categories_cache()->get_category($row['id_category']);
				$tpl->assign_block_vars('item', array(
					'U_LINK' => DownloadUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $row['id'], $row['rewrited_name'])->rel(),
					'U_IMG' => Url::to_rel($row['picture_url']),
					'C_IMG' => !empty($row['picture_url']),
					'TITLE' => $row['name'],
					'DESC' => $row['short_contents'],
					'PSEUDO' => $row['display_name']
				));
				$i++;
			}
		}
		$this->template->put('THEMES', $tpl);
	}
	
	private function build_feed_news_view()
	{
		$feed_template = new FileTemplate('PHPBoostOfficial/feed_news.tpl');
		$this->template->put('FEED_NEWS', Feed::get_parsed('news', Feed::DEFAULT_FEED_NAME, 0, $feed_template, 5, 1));
	}
	
	private function build_last_news_view()
	{
		$news = NewsService::get_news('WHERE approbation_type=1 ORDER BY creation_date DESC LIMIT 0,1', array());
		$tpl = new FileTemplate('PHPBoostOfficial/last_news.tpl');
		
		$tpl->put_all($news->get_array_tpl_vars());
		
		$this->template->put('LAST_NEWS', $tpl);
	}
}
?>
