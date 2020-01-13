<?php
/**
 * @package     PHPBoost
 * @subpackage  Module\tree-links
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 13
 * @since       PHPBoost 5.3 - 2019 12 20
*/

class DefaultTreeLinks implements ModuleTreeLinksExtensionPoint
{
	/**
	 * @var string the module identifier
	 */
	private $module_id;

	/**
	 * @var bool display of items links or not
	 */
	private $display_items_links;

	/**
	 * DefaultTreeLinks constructor
	 * @param string $module_id the module id.
	 * @param bool   $display_items_links the module id.
	 */
	public function __construct($module_id, $display_items_links = true)
	{
		$this->module_id = $module_id;
		$this->display_items_links = $display_items_links;
	}

	/**
	 * @return string Return the id of the module
	 */
	public function get_module_id()
	{
		return $this->module_id;
	}

	/**
	 * @return bool Return true if items links are displayed
	 */
	public function are_items_links_displayed()
	{
		return $this->display_items_links;
	}

	public function get_actions_tree_links()
	{
		$tree = new ModuleTreeLinks();
		$has_categories = ModulesManager::get_module($this->module_id)->get_configuration()->has_categories();
		$authorizations = $has_categories ? CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, $this->module_id) : ItemsAuthorizationsService::check_authorizations($this->module_id);
		
		if ($has_categories)
		{
			$manage_categories_link = new ModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), CategoriesUrlBuilder::manage_categories($this->module_id), $authorizations->manage_categories());
			$manage_categories_link->add_sub_link(new ModuleLink(LangLoader::get_message('categories.manage', 'categories-common'), CategoriesUrlBuilder::manage_categories($this->module_id), $authorizations->manage_categories()));
			$manage_categories_link->add_sub_link(new ModuleLink(LangLoader::get_message('category.add', 'categories-common'), CategoriesUrlBuilder::add_category(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY), $this->module_id), $authorizations->manage_categories()));
			$tree->add_link($manage_categories_link);
		}
		
		if ($this->display_items_links)
		{
			$lang = ItemsService::get_items_lang($this->module_id);
			
			$manage_items_link = new ModuleLink($lang['items.manage'], ItemsUrlBuilder::manage($this->module_id), $authorizations->moderation());
			$manage_items_link->add_sub_link(new ModuleLink($lang['items.manage'], ItemsUrlBuilder::manage($this->module_id), $authorizations->moderation()));
			$manage_items_link->add_sub_link(new ModuleLink($lang['item.add'], ItemsUrlBuilder::add(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY), $this->module_id), $authorizations->moderation()));
			$tree->add_link($manage_items_link);
			
			if (!$authorizations->moderation())
			{
				$tree->add_link(new ModuleLink($lang['item.add'], ItemsUrlBuilder::add(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY), $this->module_id), $authorizations->write() || $authorizations->contribution()));
			}
			
			$this->get_module_additional_items_actions_tree_links();
			
			$tree->add_link(new ModuleLink($lang['items.pending'], ItemsUrlBuilder::display_pending($this->module_id), $authorizations->write() || $authorizations->contribution() || $authorizations->moderation()));
		}
		
		$this->get_module_additional_actions_tree_links();
		
		if (ModulesManager::get_module($this->module_id)->get_configuration()->get_admin_main_page())
			$tree->add_link(new AdminModuleLink(LangLoader::get_message('configuration', 'admin-common'), ModulesUrlBuilder::configuration($this->module_id)));
		
		if (ModulesManager::get_module($this->module_id)->get_configuration()->get_documentation())
		{
			if ($has_categories)
				$tree->add_link(new ModuleLink(LangLoader::get_message('module.documentation', 'admin-modules-common'), ModulesManager::get_module($this->module_id)->get_configuration()->get_documentation(), $authorizations->write() || $authorizations->contribution() || $authorizations->moderation()));
			else
				$tree->add_link(new AdminModuleLink(LangLoader::get_message('module.documentation', 'admin-modules-common'), ModulesManager::get_module($this->module_id)->get_configuration()->get_documentation()));
		}
		
		return $tree;
	}

	protected function get_module_additional_actions_tree_links() {}

	protected function get_module_additional_items_actions_tree_links() {}
}
?>
