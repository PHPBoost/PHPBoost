<?php
/**
 * @package     Content
 * @subpackage  Item\controllers
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 03 01
 * @since       PHPBoost 6.0 - 2019 12 20
*/

abstract class AbstractItemController extends ModuleController
{
	/**
	 * @var HTTPRequestCustom
	 */
	protected $request;
	
	protected $config;
	protected $lang;
	protected $view;
	protected $enabled_features = array();
	protected $module_item;

	public function __construct($module_id = '')
	{
		parent::__construct($module_id);
		$this->request = AppContext::get_request();
		$this->config = self::get_module_configuration()->get_configuration_parameters();
		$this->lang = array_merge(LangLoader::get('common'), (LangLoader::filename_exists('common', self::get_module()->get_id()) ? LangLoader::get('common', self::get_module()->get_id()) : array()), ItemsService::get_items_lang(self::get_module()->get_id()));
		$this->view = $this->get_template_to_use();
		
		$this->view->add_lang($this->lang);
		
		if (self::get_module_configuration()->feature_is_enabled('comments') && CommentsConfig::load()->module_comments_is_enabled(self::get_module()->get_id()))
			$this->enabled_features[] = 'comments';
		if (self::get_module_configuration()->feature_is_enabled('idcard') && ContentManagementConfig::load()->module_id_card_is_enabled(self::get_module()->get_id()))
			$this->enabled_features[] = 'idcard';
		if (self::get_module_configuration()->feature_is_enabled('notation') && ContentManagementConfig::load()->module_notation_is_enabled(self::get_module()->get_id()))
			$this->enabled_features[] = 'notation';
		
		$this->view->put_all(array(
			'MODULE_ID'          => self::get_module()->get_id(),
			'MODULE_NAME'        => self::get_module_configuration()->get_name(),
			'ITEMS_PER_ROW'      => $this->config->get_items_per_row(),
			'C_ENABLED_COMMENTS' => in_array('comments', $this->enabled_features),
			'C_ENABLED_NOTATION' => in_array('notation', $this->enabled_features)
		));
		
		if (self::get_module_configuration()->has_rich_config_parameters())
		{
			$this->view->put_all(array(
				'C_GRID_VIEW'           => $this->config->get_display_type() == DefaultRichModuleConfig::GRID_VIEW,
				'C_LIST_VIEW'           => $this->config->get_display_type() == DefaultRichModuleConfig::LIST_VIEW,
				'C_TABLE_VIEW'          => $this->config->get_display_type() == DefaultRichModuleConfig::TABLE_VIEW,
				'C_AUTHOR_DISPLAYED'    => $this->config->get_author_displayed(),
				'C_ENABLED_DATE'        => $this->config->get_date_displayed(),
				'C_ENABLED_UPDATE_DATE' => $this->config->get_update_date_displayed(),
				'C_ENABLED_VIEWS'       => $this->config->get_views_number_enabled(),
				'CATEGORIES_PER_ROW'    => $this->config->get_categories_per_row()
			));
		}
		
		// Automatically add module dedicated configuration parameters to template
		$configuration_class_name = self::get_module_configuration()->get_configuration_name();
		if (!in_array($configuration_class_name, array('DefaultModuleConfig', 'DefaultRichModuleConfig')))
		{
			$configuration_variables = array();
			$kernel_configuration_class = new ReflectionClass('DefaultRichModuleConfig');
			$configuration_class = new ReflectionClass($configuration_class_name);
			
			foreach (array_diff($configuration_class->getConstants(), $kernel_configuration_class->getConstants()) as $parameter)
			{
				$parameter_get_method = 'get_' . $parameter;
				$type = gettype($configuration_class->getMethod('get_default_value')->invoke($this->config, $parameter));
				
				switch ($type) {
					case 'boolean':
						$configuration_variables['C_' . strtoupper($parameter)] = $this->config->$parameter_get_method();
					break;
					case 'integer':
					case 'string':
						$configuration_variables[strtoupper($parameter)] = $this->config->$parameter_get_method();
					break;
				}
			}
			
			if ($configuration_variables)
				$this->view->put_all($configuration_variables);
		}
		
		$this->view->put_all($this->get_additional_view_parameters());
		
		$item_class_name = self::get_module_configuration()->get_item_name();
		$this->module_item = new $item_class_name(self::$module_id);
		
		$this->view->put_all($this->module_item->get_global_template_vars());
	}

	/**
	 * @return ItemsManager
	 */
	protected static function get_items_manager()
	{
		return ItemsService::get_items_manager(self::get_module()->get_id());
	}

	protected function display_unexisting_page()
	{
		DispatchManager::redirect(PHPBoostErrors::unexisting_page());
	}

	protected function display_user_not_authorized_page()
	{
		DispatchManager::redirect(PHPBoostErrors::user_not_authorized());
	}

	protected function display_user_in_read_only_page()
	{
		DispatchManager::redirect(PHPBoostErrors::user_in_read_only());
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
		return new StringTemplate('');
	}

	/**
	 * @return boolean Authorization to display the controller
	 */
	abstract protected function check_authorizations();
}
?>
