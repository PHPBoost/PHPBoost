<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 04 07
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
		$response = new UpdateDisplayResponse(1, $step_title, $view);
		return $response;
	}

	private function add_navigation(Template $view)
	{
		$server_configuration = new ServerConfiguration();
		if ($server_configuration->is_php_compatible() && PHPBoostFoldersPermissions::validate() && $server_configuration->has_mbstring_library())
		{
			if (UpdateServices::database_config_file_checked())
			{
				$service = new UpdateServices();
				$service->generate_update_token();
				$redirect_url = UpdateUrlBuilder::update()->rel();
			}
			else
				$redirect_url = UpdateUrlBuilder::database()->rel();
		}
		else
			$redirect_url = UpdateUrlBuilder::server_configuration()->rel();
		
		$form = new HTMLForm('preambleForm', $redirect_url, false);

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
