<?php
/**
 * @package     Content
 * @subpackage  Item\controllers
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 14
 * @since       PHPBoost 5.3 - 2020 02 08
*/

abstract class AbstractAdminItemController extends AdminModuleController
{
	protected $config;
	protected $lang;
	protected $items_lang;
	protected $view;

	public function __construct($module_id = '')
	{
		parent::__construct($module_id);
		$this->config = self::get_module()->get_configuration()->get_configuration_parameters();
		$this->lang = LangLoader::get('admin-common');
		$this->items_lang = ItemsService::get_items_lang(self::get_module()->get_id());
		$this->view = $this->get_template_to_use();
		
		$this->view->add_lang(array_merge($this->lang, $this->items_lang));
		
		$this->view->put_all(array(
			'MODULE_ID'   => self::get_module()->get_id(),
			'MODULE_NAME' => self::get_module()->get_configuration()->get_name()
		));
		
		$this->view->put_all($this->get_additional_view_parameters());
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
		return new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
	}
}
?>
