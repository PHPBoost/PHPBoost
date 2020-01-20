<?php
/**
 * @package     Content
 * @subpackage  Item\controllers
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 20
 * @since       PHPBoost 5.3 - 2019 12 20
*/

abstract class AbstractItemController extends ModuleController
{
	protected $config;
	protected $lang;
	protected $items_lang;
	protected $view;
	protected $enabled_features = array();

	public function __construct()
	{
		$this->config = self::get_module()->get_configuration()->get_configuration_parameters();
		$this->lang = LangLoader::get('common', self::get_module()->get_id());
		$this->items_lang = ItemsService::get_items_lang(self::get_module()->get_id());
		$this->view = $this->get_template_to_use();
		
		if ($this->view !== null)
			$this->view->add_lang($this->lang);
		
		if (self::get_module()->get_configuration()->feature_is_enabled('comments') && CommentsConfig::load()->module_comments_is_enabled(self::get_module()->get_id())
			$this->enabled_features[] = 'comments';
		if (self::get_module()->get_configuration()->feature_is_enabled('notation') && ContentManagementConfig::load()->module_notation_is_enabled(self::get_module()->get_id())
			$this->enabled_features[] = 'notation';
		
		$this->tpl->put_all(array(
			'MODULE_NAME'        => self::get_module()->get_configuration()->get_name(),
			'C_ENABLED_COMMENTS' => in_array('comments', $this->enabled_features),
			'C_ENABLED_NOTATION' => in_array('notation', $this->enabled_features)
		));
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

	/**
	 * @return Template
	 */
	abstract protected function get_template_to_use();

	/**
	 * @return boolean Authorization to display the controller
	 */
	abstract protected function check_authorizations();
}
?>
