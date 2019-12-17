<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 05 05
 * @since       PHPBoost 3.0 - 2012 03 11
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class UpdateIntroductionController extends UpdateController
{
	public function execute(HTTPRequestCustom $request)
	{
		parent::load_lang($request);
		$view = new FileTemplate('update/introduction.tpl');
		$this->add_navigation($view);
		return $this->create_response($view);
	}

	/**
	 * @param Template $view
	 * @return UpdateDisplayResponse
	 */
	private function create_response(Template $view)
	{
		$step_title = $this->lang['step.introduction.title'];
		$response = new UpdateDisplayResponse(0, $step_title, $view);
		return $response;
	}

	private function add_navigation(Template $view)
	{
		$form = new HTMLForm('preambleForm', UpdateUrlBuilder::server_configuration()->rel(), false);

		$action_fieldset = new FormFieldsetSubmit('actions');
		$next = new FormButtonSubmitCssImg($this->lang['step.next'], 'fa fa-arrow-right', 'introduction');
		$action_fieldset->add_element($next);
		$form->add_fieldset($action_fieldset);
		$view->put_all(array(
			'C_PUT_UNDER_MAINTENANCE' => !MaintenanceConfig::load()->is_under_maintenance(),
			'SERVER_FORM' => $form->display()
		));
	}
}
?>
