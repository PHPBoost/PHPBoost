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
	private $cache;
	
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
		$modules_tpl = new FileTemplate('PHPBoostOfficial/modules.tpl');
		
		foreach ($this->cache->get_last_modules() as $module)
		{
			$modules_tpl->assign_block_vars('item', array(
				'U_LINK' => $module['link'],
				'U_IMG' => $module['picture'],
				'C_IMG' => !empty($module['picture']),
				'TITLE' => $module['title'],
				'DESC' => $module['description'],
				'VERSION' => $module['version'],
				'PSEUDO' => $module['author']
			));
		}
		
		$themes_tpl = new FileTemplate('PHPBoostOfficial/themes.tpl');
		
		foreach ($this->cache->get_last_themes() as $theme)
		{
			$themes_tpl->assign_block_vars('item', array(
				'U_LINK' => $theme['link'],
				'U_IMG' => $theme['picture'],
				'C_IMG' => !empty($theme['picture']),
				'TITLE' => $theme['title'],
				'DESC' => $theme['description'],
				'VERSION' => $theme['version'],
				'PSEUDO' => $theme['author']
			));
		}
		
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
		$tpl = new FileTemplate('PHPBoostOfficial/last_news.tpl');
		
		$tpl->put_all($this->cache->get_last_news());
		
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
		$this->cache = PHPBoostOfficialCache::load();
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
