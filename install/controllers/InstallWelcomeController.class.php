<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 02 22
 * @since       PHPBoost 3.0 - 2010 06 13
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class InstallWelcomeController extends InstallController
{
	public function execute(HTTPRequestCustom $request)
	{
		parent::load_lang($request);
		$view = new FileTemplate('install/welcome.tpl');
		$this->add_navigation($view);
		return $this->create_response($view);
	}

	/**
	 * @param Template $view
	 * @return InstallDisplayResponse
	 */
	private function create_response(Template $view)
	{
		$step_title = $this->lang['install.welcome.title'];
		$response = new InstallDisplayResponse(0, $step_title, $this->lang, $view);
		return $response;
	}

	private function add_navigation(Template $view)
	{
		$form = new HTMLForm('preambleForm', InstallUrlBuilder::license()->rel(), false);

		$action_fieldset = new FormFieldsetSubmit('actions');
		$next = new FormButtonSubmitCssImg($this->lang['common.next'], 'fa fa-arrow-right', 'welcome');
		$action_fieldset->add_element($next);
		$form->add_fieldset($action_fieldset);
		$view->put('LICENSE_FORM', $form->display());
	}
}
?>
