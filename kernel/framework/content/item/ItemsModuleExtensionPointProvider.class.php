<?php
/**
 * @package     PHPBoost
 * @subpackage  Item
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 09
 * @since       PHPBoost 6.0 - 2020 05 10
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ItemsModuleExtensionPointProvider extends ModuleExtensionPointProvider
{
	public function connect_menu_link(): mixed
	{
		if ($class = $this->get_class('ConnectMenuLink'))
        {
			return $class;
        }
        return null;
	}

	public function feeds(): mixed
	{
		if ($class = $this->get_class('FeedProvider'))
        {
			return $class;
        }
		else
        {
			return $this->module && $this->module->get_configuration()->has_categories() && $this->module->get_configuration()->has_items() ? new DefaultCategoriesFeedProvider($this->get_id()) : false;
        }
	}

	public function home_page(): mixed
	{
		return new DefaultHomePageDisplay($this->get_id(), DefaultSeveralItemsController::get_view($this->get_id()));
	}

	public function lobby(): mixed
	{
		if ($class = $this->get_class('LobbyProvider'))
        {
			return $class;
        }
		elseif ($this->module && $this->module->get_configuration()->has_categories())
        {
			return [
                new DefaultItemsLobbyProvider($this->get_id()),
                new DefaultCategoryLobbyProvider($this->get_id()),
            ];
        }
        else
        {
            return new DefaultItemsLobbyProvider($this->get_id());
        }
	}

	public function search(): mixed
	{
		if ($class = $this->get_class('Searchable', 'SearchableExtensionPoint'))
        {
			return $class;
        }
		else
        {
			return $this->module && $this->module->get_configuration()->feature_is_enabled('search') && ($this->module->get_configuration()->has_categories() || $this->module->get_configuration()->has_items()) ? new DefaultSearchable($this->get_id()) : false;
        }
	}

	public function sitemap(): mixed
	{
		if ($class = $this->get_class('Sitemap', 'SitemapExtensionPoint'))
        {
			return $class;
        }
		else
        {
			return $this->module && $this->module->get_configuration()->has_categories() ? new DefaultSitemapCategoriesModule($this->get_id()) : new DefaultSitemapModule($this->get_id());
        }
	}

	public function scheduled_jobs(): mixed
	{
		if ($class = $this->get_class('ScheduledJobs', 'ScheduledJobExtensionPoint'))
        {
			return $class;
        }
		else
        {
			return new DefaultScheduledJobsModule($this->get_id());
        }
	}

	public function user(): mixed
	{
		if ($class = $this->get_class('User', 'UserExtensionPoint'))
        {
			return $class;
        }
		else
        {
			return new DefaultUserModule($this->get_id());
        }
	}
}
?>
