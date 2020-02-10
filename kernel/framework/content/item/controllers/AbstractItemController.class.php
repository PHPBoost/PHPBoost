<?php
/**
 * @package     Content
 * @subpackage  Item\controllers
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 10
 * @since       PHPBoost 5.3 - 2019 12 20
*/

abstract class AbstractItemController extends ModuleController
{
	protected $config;
	protected $lang;
	protected $items_lang;
	protected $view;
	protected $enabled_features = array();

	public function __construct($module_id = '')
	{
		parent::__construct($module_id);
		$this->config = self::get_module()->get_configuration()->get_configuration_parameters();
		$this->lang = LangLoader::get('common', self::get_module()->get_id());
		$this->items_lang = ItemsService::get_items_lang(self::get_module()->get_id());
		$this->view = $this->get_template_to_use();
		
		$this->view->add_lang(array_merge($this->lang, $this->items_lang));
		
		if (self::get_module()->get_configuration()->feature_is_enabled('comments') && CommentsConfig::load()->module_comments_is_enabled(self::get_module()->get_id()))
			$this->enabled_features[] = 'comments';
		if (self::get_module()->get_configuration()->feature_is_enabled('notation') && ContentManagementConfig::load()->module_notation_is_enabled(self::get_module()->get_id()))
			$this->enabled_features[] = 'notation';
		
		$this->view->put_all(array(
			'MODULE_ID'          => self::get_module()->get_id(),
			'MODULE_NAME'        => self::get_module()->get_configuration()->get_name(),
			'ITEMS_PER_ROW'      => $this->config->get_items_per_row(),
			'C_ENABLED_COMMENTS' => in_array('comments', $this->enabled_features),
			'C_ENABLED_NOTATION' => in_array('notation', $this->enabled_features)
		));
		
		if (self::get_module()->get_configuration()->has_rich_config_parameters())
		{
			$this->view->put_all(array(
				'C_GRID_VIEW'        => $this->config->get_display_type() == DefaultRichModuleConfig::GRID_VIEW,
				'C_LIST_VIEW'        => $this->config->get_display_type() == DefaultRichModuleConfig::LIST_VIEW,
				'C_TABLE_VIEW'       => $this->config->get_display_type() == DefaultRichModuleConfig::TABLE_VIEW,
				'CATEGORIES_PER_ROW' => $this->config->get_categories_per_row()
			));
		}
		
		$this->view->put_all($this->get_additional_view_parameters());
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
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	protected function display_user_not_authorized_page()
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
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
