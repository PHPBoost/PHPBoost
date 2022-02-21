<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 02 22
 * @since       PHPBoost 3.0 - 2010 09 12
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class InstallLicenseController extends InstallController
{
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonSubmit
	 */
	private $submit;

	public function execute(HTTPRequestCustom $request)
	{
		parent::load_lang($request);
		$this->build_form();
		if ($this->submit->has_been_submited())
		{
			$this->handle_form();
		}
		return $this->create_response();
	}

	private function handle_form()
	{
		if ($this->form->validate())
		{
			AppContext::get_response()->redirect(InstallUrlBuilder::server_configuration());
		}
	}

	private function build_form()
	{
		$this->form = new HTMLForm('licenseForm', '', false);

		$fieldset = new FormFieldsetHTML('agreementFieldset', $this->lang['install.license.terms']);
		$this->form->add_fieldset($fieldset);

		$license_content = file_get_contents('gpl-license.txt');
		$license_block = '<div class="license-container"><div class="license-content">' . $license_content . '</div></div>';
		$fieldset->add_field(new FormFieldHTML('licenseContent', $license_block,
			array('class' => 'full-field')
		));

		$fieldset->add_field(new FormFieldCheckbox('agree', $this->lang['install.license.agreement'], FormFieldCheckbox::UNCHECKED,
			array('class' => 'full-field custom-checkbox', 'required' => $this->lang['install.license.warning.agreement'])
		));

		$action_fieldset = new FormFieldsetSubmit('actions', array('css_class' => 'fieldset-submit next-step'));
		$action_fieldset->add_element(new FormButtonLinkCssImg($this->lang['common.previous'], InstallUrlBuilder::welcome(), 'fa fa-arrow-left'));
		$this->submit = new FormButtonSubmitCssImg($this->lang['common.next'], 'fa fa-arrow-right', 'license');
		$action_fieldset->add_element($this->submit);
		$this->form->add_fieldset($action_fieldset);
	}

	/**
	 * @param Template $view
	 * @return InstallDisplayResponse
	 */
	private function create_response()
	{
		$view = new FileTemplate('install/license.tpl');
		$view->put('LICENSE_FORM', $this->form->display());
		$step_title = $this->lang['install.license.title'];
		$response = new InstallDisplayResponse(1, $step_title, $this->lang, $view);
		return $response;
	}
}
?>
