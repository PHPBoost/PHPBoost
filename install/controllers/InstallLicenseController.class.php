<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2010 09 12
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
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
        if (InstallationServices::check_server())
        {
            $redirect_url = InstallUrlBuilder::database()->rel();
        }
        else
        {
            $redirect_url = InstallUrlBuilder::server_configuration()->rel();
        }

        $this->form = new HTMLForm('licenseForm', $redirect_url, false);

		$fieldset = new FormFieldsetHTML('agreementFieldset', $this->lang['install.license.terms']);
		$this->form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldCheckbox('agree', $this->lang['install.license.agreement'], FormFieldCheckbox::UNCHECKED,
			[
                'class' => 'full-field custom-checkbox',
                'required' => $this->lang['install.license.warning.agreement']
            ]
		));

		$action_fieldset = new FormFieldsetSubmit('actions', ['css_class' => 'fieldset-submit next-step']);
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

		$license_content = file_get_contents('gpl-license.txt');
		$license_block = '<div class="license-container"><div class="license-content">' . $license_content . '</div></div>';

		$view->put_all([
            'LICENSE_FORM' => $this->form->display(),
            'LICENSE_CONTENT' => $license_content
        ]);
		$step_title = $this->lang['install.license.title'];
		$response = new InstallDisplayResponse(1, $step_title, $this->lang, $view);
		return $response;
	}
}
?>
