<?php
/**
 * @package     Content
 * @subpackage  Item\controllers
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 02 03
 * @since       PHPBoost 6.0 - 2020 02 08
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class AbstractAdminItemController extends DefaultAdminModuleController
{
	protected $module_item;

	public function __construct($module_id = '')
	{
		parent::__construct($module_id);

		$this->view->put_all($this->get_additional_view_parameters());

		$item_class_name = self::get_module_configuration()->get_item_name();
		$this->module_item = new $item_class_name(self::$module_id);
	}

	/**
	 * @return ItemsManager
	 */
	protected static function get_items_manager()
	{
		return ItemsService::get_items_manager(self::get_module()->get_id());
	}

	protected function get_additional_view_parameters()
	{
		return array();
	}
	
	protected function init_lang()
	{
		$this->lang = array_merge(
			LangLoader::get_all_langs(self::$module_id),
			ItemsService::get_items_lang(self::get_module()->get_id())
		);
	}

	/**
	 * @return Template
	 */
	protected function get_template_to_use()
	{
		$class_name = get_called_class();
		$current_user_theme = ThemesManager::get_theme(AppContext::get_current_user()->get_theme());
		$template_module_folder = PATH_TO_ROOT . '/templates/' . AppContext::get_current_user()->get_theme() . '/modules/' . self::$module_id . '/';
		$parent_template_module_folder = $current_user_theme ? PATH_TO_ROOT . '/templates/' . $current_user_theme->get_configuration()->get_parent_theme() . '/modules/' . self::$module_id . '/' : '';

		// If the module has a template with the name of the called class we take it
		if (file_exists(PATH_TO_ROOT . '/' . self::$module_id . '/templates/' . $class_name . '.tpl'))
			return new FileTemplate(self::$module_id . '/' . $class_name . '.tpl');
		// Otherwise if the module has a template with the name of the called class which begins with Default and Default is replace by module id we take it
		else if (preg_match('/^Default/', $class_name) && file_exists(PATH_TO_ROOT . '/' . self::$module_id . '/templates/' . str_replace('Default', TextHelper::ucfirst(self::$module_id), $class_name) . '.tpl'))
			return new FileTemplate(self::$module_id . '/' . str_replace('Default', TextHelper::ucfirst(self::$module_id), $class_name) . '.tpl');
		// Otherwise if a template url is defined and it ends with tpl extension we go further
		else if ($this->get_template_url() && preg_match('/\.tpl$/', $this->get_template_url()))
		{
			// If the module has a template with the name of the template put in url we take it
			if (file_exists(PATH_TO_ROOT . '/' . self::$module_id . '/templates/' . basename($this->get_template_url())) || file_exists($template_module_folder . basename($this->get_template_url())) || ($parent_template_module_folder && file_exists($parent_template_module_folder . basename($this->get_template_url()))))
				return new FileTemplate(self::$module_id . '/' . basename($this->get_template_url()));
			// Otherwise if the module has a template with the name of the template put in url which begins with Default and Default is replace by module id we take it
			else if (preg_match('/^Default/', basename($this->get_template_url())) && (file_exists(PATH_TO_ROOT . '/' . self::$module_id . '/templates/' . str_replace('Default', TextHelper::ucfirst(self::$module_id), basename($this->get_template_url()))) || file_exists($template_module_folder . str_replace('Default', TextHelper::ucfirst(self::$module_id), basename($this->get_template_url()))) || ($parent_template_module_folder && file_exists($parent_template_module_folder . str_replace('Default', TextHelper::ucfirst(self::$module_id), basename($this->get_template_url()))))))
				return new FileTemplate(self::$module_id . '/' . str_replace('Default', TextHelper::ucfirst(self::$module_id), basename($this->get_template_url())));
			// Otherwise if the module has a template with the name of the template put in url which begins with Module and Module is replace by module id we take it
			else if (preg_match('/^Module/', basename($this->get_template_url())) && (file_exists(PATH_TO_ROOT . '/' . self::$module_id . '/templates/' . str_replace('Module', TextHelper::ucfirst(self::$module_id), basename($this->get_template_url()))) || file_exists($template_module_folder . str_replace('Module', TextHelper::ucfirst(self::$module_id), basename($this->get_template_url()))) || ($parent_template_module_folder && file_exists($parent_template_module_folder . str_replace('Module', TextHelper::ucfirst(self::$module_id), basename($this->get_template_url()))))))
				return new FileTemplate(self::$module_id . '/' . str_replace('Module', TextHelper::ucfirst(self::$module_id), basename($this->get_template_url())));
			// Otherwise if the module has a template with the name of the template put in url which does not begin with Default or Module but module id is added at the beginning f the template name we take it
			else if (!preg_match('/^Default/', basename($this->get_template_url())) && !preg_match('/^Module/', basename($this->get_template_url())) && (file_exists(PATH_TO_ROOT . '/' . self::$module_id . '/templates/' . TextHelper::ucfirst(self::$module_id) . basename($this->get_template_url())) || file_exists($template_module_folder . TextHelper::ucfirst(self::$module_id) . basename($this->get_template_url())) || ($parent_template_module_folder && file_exists($parent_template_module_folder . TextHelper::ucfirst(self::$module_id) . basename($this->get_template_url())))))
				return new FileTemplate(self::$module_id . '/' . TextHelper::ucfirst(self::$module_id) . basename($this->get_template_url()));
			// Otherwise we take the default url defined for the default template
			else
				return new FileTemplate($this->get_template_url());
		}
		// Otherwise if no template url is defined we take the value of the template string content defined (blank per default but used by templates with only TABLE of FORM for instance)
		else
			return new StringTemplate($this->get_template_string_content());
	}

	protected function get_template_url()
	{
		return '';
	}
}
?>
