<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 02 18
 * @since       PHPBoost 4.0 - 2014 07 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
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

		$actual_major_version = GeneralConfig::load()->get_phpboost_major_version();
		$phpboost_5_1_release_date = new Date('2017-07-18');

		if (GeneralConfig::load()->get_site_install_date()->is_anterior_to($phpboost_5_1_release_date))
		{
			//Old categories management urls replacement
			$this->urls_mappings[] = new UrlMapping('^([a-zA-Z/]+)/admin/categories([^.]*)?$', '$1/categories$2', 'L,R=301');
			//Old modules elements management urls replacement
			$this->urls_mappings[] = new UrlMapping('^([a-zA-Z/]+)/admin/manage([^.]*)?$', '$1/manage$2', 'L,R=301');
		}

		// Pages
		if (ModulesManager::is_module_installed('pages') && ModulesManager::is_module_activated('pages') && ClassLoader::is_class_registered_and_valid('PagesService') && $actual_major_version >= '6.0')
		{
			$this->urls_mappings[] = new UrlMapping('^pages/pages\.php$', '/pages/', 'L,R=301');

			$categories = CategoriesService::get_categories_manager('pages')->get_categories_cache()->get_categories();

			foreach ($categories as $id => $category)
			{
				if ($id != Category::ROOT_CATEGORY && $category instanceof Category)
					$this->urls_mappings[] = new UrlMapping('^pages/' . $category->get_rewrited_name() . '$', '/pages/' . $id . '-' . $category->get_rewrited_name() . '/', 'L,R=301');
			}
		}

		// Poll
		if (ModulesManager::is_module_installed('poll') && ModulesManager::is_module_activated('poll') && $actual_major_version >= '6.0')
		{
			$this->urls_mappings[] = new UrlMapping('^poll/poll\.php$', '/poll/', 'L,R=301');
		}

		// Stats
		if (ModulesManager::is_module_installed('stats') && ModulesManager::is_module_activated('stats') && $actual_major_version >= '6.0')
		{
			$this->urls_mappings[] = new UrlMapping('^stats/admin_stats\.php$', '/stats/admin/', 'L,R=301');
			$this->urls_mappings[] = new UrlMapping('^stats/stats\.php$', '/stats/', 'L,R=301');
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
