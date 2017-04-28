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
		$this->build_modules_and_themes_view();
		
		$this->build_feed_news_view();
		
		$this->build_last_news_view();
		
		$this->build_partners_view();
		
		$this->add_google_analytics_identifier();
	}
	
	private function build_modules_and_themes_view()
	{
		$now = new Date();
		
		$modules_tpl = new FileTemplate('PHPBoostOfficial/modules.tpl');
		$themes_tpl = new FileTemplate('PHPBoostOfficial/themes.tpl');
		$modules_number = $themes_number = 0;
		
		$results = PersistenceContext::get_querier()->select('SELECT cats.rewrited_name as cat_rewrited_name, file.id, file.id_category, file.name, file.rewrited_name, file.short_contents, file.creation_date, file.picture_url, file.author_custom_name, user.display_name
			FROM ' . PREFIX . 'download file
			LEFT JOIN ' . PREFIX . 'download_cats cats ON cats.id = file.id_category
			LEFT JOIN ' . DB_TABLE_MEMBER . ' user ON user.user_id = file.author_user_id
			WHERE (approbation_type = 1 OR (approbation_type = 2 AND start_date < :timestamp_now AND (end_date > :timestamp_now OR end_date = 0))) AND (cats.rewrited_name LIKE "modules-phpboost-%" OR cats.rewrited_name LIKE "themes-phpboost-%")
			ORDER BY file.creation_date DESC', array(
			'timestamp_now' => $now->get_timestamp()
		));
		
		foreach ($results as $row)
		{
			if ($modules_number == 3 && $themes_number == 3)
				break;
			
			$item = array(
				'U_LINK' => DownloadUrlBuilder::display($row['id_category'], $row['cat_rewrited_name'], $row['id'], $row['rewrited_name'])->rel(),
				'U_IMG' => Url::to_rel($row['picture_url']),
				'C_IMG' => !empty($row['picture_url']),
				'TITLE' => $row['name'],
				'DESC' => $row['short_contents'],
				'PSEUDO' => !empty($row['author_custom_name']) ? $row['author_custom_name'] : $row['display_name']
			);
			
			if (strstr($row['cat_rewrited_name'], 'modules') && $modules_number < 3)
			{
				$modules_tpl->assign_block_vars('item', $item);
				$modules_number++;
			}
			else if (strstr($row['cat_rewrited_name'], 'themes') && $themes_number < 3)
			{
				$themes_tpl->assign_block_vars('item', $item);
				$themes_number++;
			}
		}
		$results->dispose();
		
		$this->view->put_all(array(
			'MODULES' => $modules_tpl,
			'THEMES' => $themes_tpl,
		));
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
	
	private function add_google_analytics_identifier()
	{
		if (ModulesManager::is_module_installed('GoogleAnalytics') && ModulesManager::is_module_activated('GoogleAnalytics'))
		{
			$cookiebar_config = CookieBarConfig::load();
			$identifier = GoogleAnalyticsConfig::load()->get_identifier();
			$this->view->put_all(array(
				'C_GOOGLEANALYTICS_IDENTIFIER' => !empty($identifier) && $cookiebar_config->is_cookiebar_enabled() && $cookiebar_config->get_cookiebar_tracking_mode() == CookieBarConfig::TRACKING_COOKIE && AppContext::get_request()->get_cookie('pbt-cookiebar-choice', 0) == 1,
				'GOOGLEANALYTICS_IDENTIFIER' => $identifier
			));
		}
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
