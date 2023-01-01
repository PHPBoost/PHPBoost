<?php
/**
 * This class defines the minimalist controler pattern to initialize common variables
 * @package     MVC
 * @subpackage  Controller
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 25
 * @since       PHPBoost 6.0 - 2021 11 30
*/

abstract class DefaultAdminModuleController extends AdminModuleController
{
	/**
	 * @var HTTPRequestCustom
	 */
	protected $request;
	
	/**
	 * @var HTMLForm
	 */
	protected $form;
	
	/**
	 * @var FormButtonSubmit
	 */
	protected $submit_button;
	
	protected $config;
	protected $lang;
	protected $view;
	
	public function __construct($module_id = '')
	{
		parent::__construct($module_id);
		
		if (!ModulesManager::is_module_installed(self::$module_id))
			return PHPBoostErrors::module_not_installed();
		
		$this->init_parameters();
		$this->init_view();
	}
	
	protected function init_parameters()
	{
		$this->request = AppContext::get_request();
		$this->config = self::get_module_configuration()->get_configuration_parameters();
		$this->init_lang();
	}
	
	protected function init_view()
	{
		$this->view = $this->get_template_to_use();

		$this->view->add_lang($this->lang);

		$this->view->put_all(array(
			'MODULE_ID'   => self::get_module()->get_id(),
			'MODULE_NAME' => self::get_module_configuration()->get_name()
		));
	}
	
	protected function init_lang()
	{
		$this->lang = LangLoader::get_all_langs(self::$module_id);
	}
	
	/**
	 * @return Template
	 */
	protected function get_template_to_use()
	{
		return new StringTemplate($this->get_template_string_content());
	}
	
	protected function get_template_string_content()
	{
		return '# INCLUDE MESSAGE_HELPER # # INCLUDE CONTENT #';
	}
}
?>
