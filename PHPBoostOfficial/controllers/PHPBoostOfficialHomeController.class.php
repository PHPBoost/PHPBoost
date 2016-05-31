<?php
/*##################################################
 *                         PHPBoostOfficialHomeController.class.php
 *                            -------------------
 *   begin                : December 26, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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

class PHPBoostOfficialHomeController extends ModuleController
{
	private $view;
	private $lang;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$this->build_view();
		
		return $this->generate_response();
	}
	
	private function build_view()
	{
		$now = new Date();
		$id_cats = array('37', '38', '42', '43', '46', '47');
		
		$querier = PersistenceContext::get_querier();
		$results = $querier->select('SELECT file.id, file.id_category, file.name, file.rewrited_name, file.short_contents, file.creation_date, file.picture_url, file.author_display_name, user.display_name
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
		
		$this->build_partners_view();
		
		if (ModulesManager::is_module_installed('GoogleAnalytics') && ModulesManager::is_module_activated('GoogleAnalytics'))
		{
			$identifier = GoogleAnalyticsConfig::load()->get_identifier();
			$this->view->put_all(array(
				'C_GOOGLEANALYTICS_IDENTIFIER' => !empty($identifier),
				'GOOGLEANALYTICS_IDENTIFIER' => $identifier
			));
		}
	}
	
	private function build_modules_view(SelectQueryResult $results)
	{
		$tpl = new FileTemplate('PHPBoostOfficial/modules.tpl');
		$i = 0;
		foreach ($results as $row)
		{
			if ($row['id_category'] == '38' || $row['id_category'] == '43' || $row['id_category'] == '47')
			{
				if ($i >= 3)
				{
					break; 
				}
				
				$category = DownloadService::get_categories_manager()->get_categories_cache()->get_category($row['id_category']);
				
				$pseudo = "";
				if ($row['author_display_name'] != "")
					$pseudo = $row['author_display_name'];
				else
					$pseudo = $row['display_name'];
				
				$tpl->assign_block_vars('item', array(
					'U_LINK' => DownloadUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $row['id'], $row['rewrited_name'])->rel(),
					'U_IMG' => Url::to_rel($row['picture_url']),
					'C_IMG' => !empty($row['picture_url']),
					'TITLE' => $row['name'],
					'DESC' => $row['short_contents'],
					'PSEUDO' => $pseudo
				));
				$i++;
			}
		}
		$this->view->put('MODULES', $tpl);
	}
	
	private function build_themes_view(SelectQueryResult $results)
	{
		$tpl = new FileTemplate('PHPBoostOfficial/themes.tpl');
		$i = 0;
		foreach ($results as $row)
		{
			if ($row['id_category'] == '37' || $row['id_category'] == '42' || $row['id_category'] == '46')
			{
				if ($i >= 3)
				{
					break; 
				}
				
				$category = DownloadService::get_categories_manager()->get_categories_cache()->get_category($row['id_category']);

				$pseudo = "";
				if ($row['author_display_name'] != "")
					$pseudo = $row['author_display_name'];
				else
					$pseudo = $row['display_name'];
				
				$tpl->assign_block_vars('item', array(
					'U_LINK' => DownloadUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $row['id'], $row['rewrited_name'])->rel(),
					'U_IMG' => Url::to_rel($row['picture_url']),
					'C_IMG' => !empty($row['picture_url']),
					'TITLE' => $row['name'],
					'DESC' => $row['short_contents'],
					'PSEUDO' => $pseudo
				));
				$i++;
			}
		}
		$this->view->put('THEMES', $tpl);
	}
	
	private function build_feed_news_view()
	{
		$feed_template = new FileTemplate('PHPBoostOfficial/feed_news.tpl');
		$this->view->put('FEED_NEWS', Feed::get_parsed('news', Feed::DEFAULT_FEED_NAME, 0, $feed_template, 5, 1));
	}
	
	private function build_last_news_view()
	{
		$now = new Date();
		$news = NewsService::get_news('WHERE (approbation_type = 1 OR (approbation_type = 2 AND start_date < :timestamp_now AND (end_date > :timestamp_now OR end_date = 0))) ORDER BY creation_date DESC LIMIT 0,1', array(
			'timestamp_now' => $now->get_timestamp()
		));
		$tpl = new FileTemplate('PHPBoostOfficial/last_news.tpl');
		
		$tpl->put_all($news->get_array_tpl_vars());
		
		$this->view->put('LAST_NEWS', $tpl);
	}
	
	private function build_partners_view()
	{
		$tpl = new FileTemplate('PHPBoostOfficial/partners.tpl');
		
		$partners_weblinks = WebCache::load()->get_partners_weblinks();
		
		$tpl->put('C_PARTNERS', !empty($partners_weblinks));
		
		foreach ($partners_weblinks as $partner)
		{
			$partner_picture = new Url($partner['partner_picture']);
			$picture = $partner_picture->rel();
			
			$tpl->assign_block_vars('partners', array(
				'C_HAS_PARTNER_PICTURE' => !empty($picture),
				'NAME' => $partner['name'],
				'U_PARTNER_PICTURE' => $picture,
				'U_VISIT' => WebUrlBuilder::visit($partner['id'])->rel()
			));
		}
		
		$this->view->put('PARTNERS', $tpl);
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'PHPBoostOfficial');
		$this->view = new FileTemplate('PHPBoostOfficial/home.tpl');
		$this->view->add_lang($this->lang);
	}
	
	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['site_description']);
		
		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['site_description'], PHPBoostOfficialUrlBuilder::home());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(PHPBoostOfficialUrlBuilder::home());
		
		return $response;
	}
	
	public static function get_view()
	{
		$object = new self();
		$object->init();
		$object->build_view();
		return $object->view;
	}
}
?>
