<?php
/**
 * @package     PHPBoost
 * @subpackage  Module\tree-links
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 21
 * @since       PHPBoost 6.0 - 2019 12 20
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DefaultTreeLinks implements ModuleTreeLinksExtensionPoint
{
	/**
	 * @var string the module identifier
	 */
	private $module_id;

	/**
	 * @var mixed[] authorizations checker
	 */
	private $authorizations;

	/**
	 * DefaultTreeLinks constructor
	 * @param string $module_id the module id.
	 */
	public function __construct($module_id)
	{
		$this->module_id = $module_id;
	}

	/**
	 * @return string Return the authorizations checker
	 */
	public function get_authorizations()
	{
		return $this->authorizations;
	}

	/**
	 * @return string Return the id of the module
	 */
	public function get_module_id()
	{
		return $this->module_id;
	}

	public function get_actions_tree_links()
	{
		$tree = new ModuleTreeLinks();
		$module_configuration = ModuleConfigurationManager::get($this->module_id);
		$has_categories = $module_configuration->has_categories();
		$this->authorizations = $has_categories ? CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, $this->module_id) : ItemsAuthorizationsService::check_authorizations($this->module_id);

		if ($has_categories)
		{
			$tree->add_link(new ModuleLink(LangLoader::get_message('category.categories.manage', 'category-lang'), CategoriesUrlBuilder::manage($this->module_id), $this->authorizations->manage()));
			$tree->add_link(new ModuleLink(LangLoader::get_message('category.add', 'category-lang'), CategoriesUrlBuilder::add(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY), $this->module_id), $this->authorizations->manage()));
		}

		if ($module_configuration->has_items() || ClassLoader::is_class_registered_and_valid(ucfirst($this->module_id) . 'Item'))
		{
			$lang = ItemsService::get_items_lang($this->module_id);

			$tree->add_link(new ModuleLink($lang['items.manage'], ItemsUrlBuilder::manage($this->module_id), $this->authorizations->moderation()));
			$tree->add_link(new ModuleLink($lang['item.add'], $this->get_add_item_url(), $this->check_write_authorization()));

			$this->get_module_additional_items_actions_tree_links($tree);

			if ($module_configuration->has_items())
				$tree->add_link(new ModuleLink($lang['my.items'], ItemsUrlBuilder::display_member_items(AppContext::get_current_user()->get_id(), $this->module_id), $this->check_write_authorization() || $this->authorizations->moderation()));

			if (!$module_configuration->feature_is_enabled('messages'))
				$tree->add_link(new ModuleLink($lang['pending.items'], ItemsUrlBuilder::display_pending($this->module_id), $this->check_write_authorization() || $this->authorizations->moderation()));
		}

		$this->get_module_additional_actions_tree_links($tree);

		if ($module_configuration->get_admin_main_page() && $this->display_configuration_link())
			$tree->add_link(new AdminModuleLink(LangLoader::get_message('form.configuration', 'form-lang'), ModulesUrlBuilder::configuration($this->module_id)));

		if ($module_configuration->get_documentation())
		{
			if ($has_categories)
				$tree->add_link(new ModuleLink(LangLoader::get_message('form.documentation', 'form-lang'), $module_configuration->get_documentation(), $this->check_write_authorization() || $this->authorizations->moderation()));
			else
				$tree->add_link(new AdminModuleLink(LangLoader::get_message('form.documentation', 'form-lang'), $module_configuration->get_documentation()));
		}

		return $tree;
	}

	protected function get_add_item_url()
	{
		return ItemsUrlBuilder::add(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY), $this->module_id);
	}

	protected function check_write_authorization()
	{
		return ModulesManager::get_module($this->module_id)->get_configuration()->has_contribution() ? ($this->authorizations->write() || $this->authorizations->contribution()) : $this->authorizations->write();
	}

	protected function display_configuration_link()
	{
		return true;
	}

	protected function get_module_additional_actions_tree_links(&$tree) {}

	protected function get_module_additional_items_actions_tree_links(&$tree) {}
}
?>
