<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 01 29
 * @since       PHPBoost 4.0 - 2014 07 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class UrlUpdaterExtensionPointProvider extends ExtensionPointProvider
{
	private $urls_mappings = array();

	public function __construct()
	{
		parent::__construct('UrlUpdater');
	}

	public function url_mappings()
	{
		$this->urls_mappings = array();

		$db_querier = PersistenceContext::get_querier();

		$phpboost_4_1_release_date = new Date('2014-07-15');
		$phpboost_5_0_release_date = new Date('2016-02-16');
		$phpboost_5_1_release_date = new Date('2017-07-18');

		if (GeneralConfig::load()->get_site_install_date()->is_anterior_to($phpboost_4_1_release_date))
		{
			// Articles
			if (ModulesManager::is_module_installed('articles') && ModulesManager::is_module_activated('articles') && class_exists('ArticlesService'))
			{
				$this->urls_mappings[] = new UrlMapping('^articles/articles.php$', '/articles/', 'L,R=301');

				$categories = ArticlesService::get_categories_manager()->get_categories_cache()->get_categories();

				foreach ($categories as $id => $category)
				{
					$this->urls_mappings[] = new UrlMapping('^articles/articles-' . $id . '\+([^.]*).php$', '/articles/' . $id . '-' . $category->get_rewrited_name() . '/', 'L,R=301');
					$this->urls_mappings[] = new UrlMapping('^articles/articles-' . $id . '-([0-9]*)\+([^.]*).php$', '/articles/' . $id . '-' . $category->get_rewrited_name() . '/$1-$2/', 'L,R=301');
				}
			}

			// Calendar
			if (ModulesManager::is_module_installed('calendar') && ModulesManager::is_module_activated('calendar') && class_exists('CalendarService'))
			{
				$this->urls_mappings[] = new UrlMapping('^calendar/calendar$', '/calendar/', 'L,R=301');
				$this->urls_mappings[] = new UrlMapping('^calendar/calendar-([0-9]+)-([0-9]+)-([0-9]+)-?([0-9]*).php$', '/calendar/$3-$2-$1/', 'L,R=301');
			}

			// Guestbook
			if (class_exists('GuestbookService'))
			{
				$this->urls_mappings[] = new UrlMapping('^guestbook/guestbook.php$', '/guestbook/', 'L,R=301');
			}

			// News
			if (ModulesManager::is_module_installed('news') && class_exists('NewsService'))
			{
				$this->urls_mappings[] = new UrlMapping('^news/news.php$', '/news/', 'L,R=301');

				$categories = NewsService::get_categories_manager()->get_categories_cache()->get_categories();

				foreach ($categories as $id => $category)
				{
					$this->urls_mappings[] = new UrlMapping('^news/news-' . $id . '\+([^.]*).php$', '/news/' . $id . '-' . $category->get_rewrited_name() . '/', 'L,R=301');
					$this->urls_mappings[] = new UrlMapping('^news/news-' . $id . '-([0-9]*)\+([^.]*).php$', '/news/' . $id . '-' . $category->get_rewrited_name() . '/$1-$2/', 'L,R=301');
				}
			}
		}

		if (GeneralConfig::load()->get_site_install_date()->is_anterior_to($phpboost_5_0_release_date))
		{
			// Download
			if (ModulesManager::is_module_installed('download') && ModulesManager::is_module_activated('download') && class_exists('DownloadService'))
			{
				$this->urls_mappings[] = new UrlMapping('^download/download\.php$', '/download/', 'L,R=301');

				$categories = DownloadService::get_categories_manager()->get_categories_cache()->get_categories();

				$result = $db_querier->select_rows(PREFIX . 'download', array('id', 'id_category', 'rewrited_name'));
				while ($row = $result->fetch())
				{
					$category = isset($categories[$row['id_category']]) ? $categories[$row['id_category']] : null;
					if ($category !== null)
					{
						$this->urls_mappings[] = new UrlMapping('^download/download-' . $row['id'] . '(\+?[^.]*)\.php$', '/download/' . $category->get_id() . '-' . $category->get_rewrited_name() . '/' . $row['id'] . '-' . $row['rewrited_name'], 'L,R=301');
						$this->urls_mappings[] = new UrlMapping('^download/file-' . $row['id'] . '(\+?[^.]*)\.php$', '/download/' . $category->get_id() . '-' . $category->get_rewrited_name() . '/' . $row['id'] . '-' . $row['rewrited_name'], 'L,R=301');
					}
				}
				$result->dispose();

				$this->urls_mappings[] = new UrlMapping('^download/count\.php?id=([0-9]*)$', '/download/download/$1', 'L,R=301');

				foreach ($categories as $id => $category)
				{
					$this->urls_mappings[] = new UrlMapping('^download/category-' . $id . '(-?[^.]*)\.php$', '/download/' . $id . '-' . $category->get_rewrited_name() . '/', 'L,R=301');
				}
			}

			// FAQ
			if (ModulesManager::is_module_installed('faq') && ModulesManager::is_module_activated('faq') && class_exists('FaqService'))
			{
				$this->urls_mappings[] = new UrlMapping('^faq/faq\.php$', '/faq/', 'L,R=301');

				$categories = FaqService::get_categories_manager()->get_categories_cache()->get_categories();

				foreach ($categories as $id => $category)
				{
					$this->urls_mappings[] = new UrlMapping('^faq/faq-' . $category->get_id() . '(\+?[^.]*)\.php$', '/faq/' . $id . '-' . $category->get_rewrited_name() . '/', 'L,R=301');
				}
			}

			// Shoutbox
			if (ModulesManager::is_module_installed('shoutbox') && ModulesManager::is_module_activated('shoutbox') && class_exists('ShoutboxService'))
			{
				$this->urls_mappings[] = new UrlMapping('^shoutbox/shoutbox\.php$', '/shoutbox/', 'L,R=301');
			}

			// Web
			if (ModulesManager::is_module_installed('web') && ModulesManager::is_module_activated('web') && class_exists('WebService'))
			{
				$this->urls_mappings[] = new UrlMapping('^web/web\.php$', '/web/', 'L,R=301');

				$categories = WebService::get_categories_manager()->get_categories_cache()->get_categories();

				$result = $db_querier->select_rows(PREFIX . 'web', array('id', 'id_category', 'rewrited_name'));
				while ($row = $result->fetch())
				{
					$category = isset($categories[$row['id_category']]) ? $categories[$row['id_category']] : null;
					if ($category !== null)
					{
						$this->urls_mappings[] = new UrlMapping('^web/web-' . $category->get_id() . '-' . $row['id'] . '([^.]*)\.php$', '/web/' . $category->get_id() . '-' . $category->get_rewrited_name() . '/' . $row['id'] . '-' . $row['rewrited_name'], 'L,R=301');
					}
				}
				$result->dispose();

				foreach ($categories as $id => $category)
				{
					$this->urls_mappings[] = new UrlMapping('^web/web-' . $category->get_id() . '(-?[^.]*)\.php$', '/web/' . $id . '-' . $category->get_rewrited_name() . '/', 'L,R=301');
				}
			}
		}

		if (GeneralConfig::load()->get_site_install_date()->is_anterior_to($phpboost_5_1_release_date))
		{
			//Old categories management urls replacement
			$this->urls_mappings[] = new UrlMapping('^([a-zA-Z/]+)/admin/categories([^.]*)?$', '$1/categories$2', 'L,R=301');
			//Old modules elements management urls replacement
			$this->urls_mappings[] = new UrlMapping('^([a-zA-Z/]+)/admin/manage([^.]*)?$', '$1/manage$2', 'L,R=301');
		}

		//Old user rewrited urls replacement
		$this->urls_mappings[] = new UrlMapping('^user/login/?$', '/login/', 'L,R=301');
		$this->urls_mappings[] = new UrlMapping('^user/aboutcookie/?$', '/aboutcookie/', 'L,R=301');
		$this->urls_mappings[] = new UrlMapping('^user/registration/?$', '/registration/', 'L,R=301');
		$this->urls_mappings[] = new UrlMapping('^user/registration/confirm/?([a-z0-9]+)?/?$', '/registration/confirm/$1', 'L,R=301');
		$this->urls_mappings[] = new UrlMapping('^user/password/lost/?$', '/password/lost/', 'L,R=301');
		$this->urls_mappings[] = new UrlMapping('^user/password/change/?([a-z0-9]+)?/?$', '/password/change/$1', 'L,R=301');
		$this->urls_mappings[] = new UrlMapping('^user/error/403/?$', '/error/403/', 'L,R=301');
		$this->urls_mappings[] = new UrlMapping('^user/error/404/?$', '/error/404/', 'L,R=301');

		return new UrlMappings($this->urls_mappings);
	}
}
?>
