<?php
/**
 * This class defines the minimalist controler pattern to initialize common variables
 * @package     MVC
 * @subpackage  Controller
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 11 30
 * @since       PHPBoost 6.0 - 2021 11 29
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class DefaultAdminController extends AdminController
{
	/**
	 * @var HTMLForm
	 */
	protected $form;
	/**
	 * @var FormButtonDefaultSubmit
	 */
	protected $submit_button;

	protected $config;
	protected $lang;
	protected $view;

	public function __construct()
	{
		$this->lang = LangLoader::get_all_langs();

		$this->view = $this->get_template_to_use();

		$this->view->add_lang($this->lang);
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
