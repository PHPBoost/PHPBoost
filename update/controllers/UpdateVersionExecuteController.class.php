<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 05 05
 * @since   	PHPBoost 3.0 - 2012 03 12
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class UpdateVersionExecuteController extends UpdateController
{
	private $submit;

	public function execute(HTTPRequestCustom $request)
	{
		parent::load_lang($request);
		$view = new FileTemplate('update/execute.tpl');
		$this->add_navigation($view);
		if ($this->submit->has_been_submited())
		{
			$this->handle_form();
		}
		return $this->create_response($view);
	}

	private function handle_form()
	{
		$service = new UpdateServices();
		$service->execute();
		AppContext::get_response()->redirect(UpdateUrlBuilder::finish());
	}

	/**
	 * @param Template $view
	 * @return UpdateDisplayResponse
	 */
	private function create_response(Template $view)
	{
		$step_title = $this->lang['step.execute.title'];
		$response = new UpdateDisplayResponse(3, $step_title, $view);
		return $response;
	}

	private function add_navigation(Template $view)
	{
		$form = new HTMLForm('continueForm', '', false);

		$action_fieldset = new FormFieldsetSubmit('actions');
		$back = new FormButtonLinkCssImg($this->lang['step.previous'], UpdateServices::database_config_file_checked() ? UpdateUrlBuilder::server_configuration() : UpdateUrlBuilder::database(), 'fa fa-arrow-left');
		$action_fieldset->add_element($back);
		$this->submit = new FormButtonSubmitCssImg($this->lang['step.next'], 'fa fa-arrow-right', 'finish', 'jQuery(\'#update-in-progress-container\').show();');
		$action_fieldset->add_element($this->submit);
		$form->add_fieldset($action_fieldset);
		$view->put('SERVER_FORM', $form->display());
	}
}
?>
