<?php
/**
 * @package     Content
 * @subpackage  Item\controllers
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 04 01
 * @since       PHPBoost 6.0 - 2020 02 08
*/

abstract class AbstractAdminItemController extends AdminModuleController
{
	/**
	 * @var HTTPRequestCustom
	 */
	protected $request;
	
	protected $config;
	protected $lang;
	protected $view;
	protected $module_item;

	public function __construct($module_id = '')
	{
		parent::__construct($module_id);
		$this->request = AppContext::get_request();
		$this->config = self::get_module_configuration()->get_configuration_parameters();
		$this->lang = array_merge(LangLoader::get('admin-common'), (LangLoader::filename_exists('common', self::get_module()->get_id()) ? LangLoader::get('common', self::get_module()->get_id()) : array()), ItemsService::get_items_lang(self::get_module()->get_id()));
		$this->view = $this->get_template_to_use();
		
		$this->view->add_lang($this->lang);
		
		$this->view->put_all(array(
			'MODULE_ID'   => self::get_module()->get_id(),
			'MODULE_NAME' => self::get_module_configuration()->get_name()
		));
		
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

	/**
	 * @return Template
	 */
	protected function get_template_to_use()
	{
		$class_name = get_called_class();
		$templates_module_folder = PATH_TO_ROOT . '/templates/' . AppContext::get_current_user()->get_theme() . '/modules/' . self::$module_id . '/';

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
			if (file_exists(PATH_TO_ROOT . '/' . self::$module_id . '/templates/' . basename($this->get_template_url())) || file_exists($templates_module_folder . basename($this->get_template_url())))
				return new FileTemplate(self::$module_id . '/' . basename($this->get_template_url()));
			// Otherwise if the module has a template with the name of the template put in url which begins with Default and Default is replace by module id we take it
			else if (preg_match('/^Default/', basename($this->get_template_url())) && (file_exists(PATH_TO_ROOT . '/' . self::$module_id . '/templates/' . str_replace('Default', TextHelper::ucfirst(self::$module_id), basename($this->get_template_url()))) || file_exists($templates_module_folder . str_replace('Default', TextHelper::ucfirst(self::$module_id), basename($this->get_template_url())))))
				return new FileTemplate(self::$module_id . '/' . str_replace('Default', TextHelper::ucfirst(self::$module_id), basename($this->get_template_url())));
			// Otherwise if the module has a template with the name of the template put in url which begins with Module and Module is replace by module id we take it
			else if (preg_match('/^Module/', basename($this->get_template_url())) && (file_exists(PATH_TO_ROOT . '/' . self::$module_id . '/templates/' . str_replace('Module', TextHelper::ucfirst(self::$module_id), basename($this->get_template_url()))) || file_exists($templates_module_folder . str_replace('Module', TextHelper::ucfirst(self::$module_id), basename($this->get_template_url())))))
				return new FileTemplate(self::$module_id . '/' . str_replace('Module', TextHelper::ucfirst(self::$module_id), basename($this->get_template_url())));
			// Otherwise if the module has a template with the name of the template put in url which does not begin with Default or Module but module id is added at the beginning f the template name we take it
			else if (!preg_match('/^Default/', basename($this->get_template_url())) && !preg_match('/^Module/', basename($this->get_template_url())) && (file_exists(PATH_TO_ROOT . '/' . self::$module_id . '/templates/' . TextHelper::ucfirst(self::$module_id) . basename($this->get_template_url())) || file_exists($templates_module_folder . TextHelper::ucfirst(self::$module_id) . basename($this->get_template_url()))))
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

	protected function get_template_string_content()
	{
		return '# INCLUDE MSG # # INCLUDE FORM #';
	}
}
?>
