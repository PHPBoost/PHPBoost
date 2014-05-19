<?php
/*##################################################
 *                         UpdateVersionExecuteController.class.php
 *                            -------------------
 *   begin                : March 12, 2012
 *   copyright            : (C) 2012 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

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
	 * @return InstallDisplayResponse
	 */
	private function create_response(Template $view)
	{
        $step_title = $this->lang['step.execute.title'];
		$response = new UpdateDisplayResponse(3, $step_title, $view);
		return $response;
	}

	private function add_navigation(Template $view)
    {
    	$form = new HTMLForm('continueForm', '#error');
    	$form->disable_captcha_protection();
		$action_fieldset = new FormFieldsetSubmit('actions');
		$back = new FormButtonLinkCssImg($this->lang['step.previous'], UpdateUrlBuilder::database(), 'fa fa-arrow-left');
		$action_fieldset->add_element($back);
		$this->submit = new FormButtonSubmitCssImg($this->lang['step.next'], 'fa fa-arrow-right', 'finish');
		$action_fieldset->add_element($this->submit);
		$form->add_fieldset($action_fieldset);
        $view->put('SERVER_FORM', $form->display());
    }
}
?>