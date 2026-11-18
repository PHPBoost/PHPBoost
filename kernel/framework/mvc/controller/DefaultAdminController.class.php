<?php
/**
 * This class defines the minimalist controler pattern to initialize common variables
 * @package     MVC
 * @subpackage  Controller
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 15
 * @since       PHPBoost 6.0 - 2021 11 29
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class DefaultAdminController extends AdminController
{
	protected ?HTMLForm $form = null;
	protected ?FormButtonSubmit $submit_button = null;

	protected mixed $config = null;
	protected mixed $lang = null;
	protected mixed $view = null;

	public function __construct()
	{
		$this->lang = LangLoader::get_all_langs();

		$this->view = $this->get_template_to_use();

		$this->view->add_lang($this->lang);
	}

	protected function get_template_to_use(): FileTemplate|StringTemplate
	{
		return new StringTemplate($this->get_template_string_content());
	}

	protected function get_template_string_content(): string
	{
		return '# INCLUDE MESSAGE_HELPER # # INCLUDE CONTENT #';
	}
}
?>
