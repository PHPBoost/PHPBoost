<?php
/*##################################################
 *                         InstallLicenseController.class.php
 *                            -------------------
 *   begin                : September 12 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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

class InstallLicenseController extends InstallController
{

	public function execute(HTTPRequest $request)
	{
        parent::load_lang($request);
		$view = new FileTemplate('install/license.tpl');
		$this->build_form($view);
		$this->add_navigation($view);
		return $this->create_response($view);
	}

	/**
	 * @param Template $view
	 * @return InstallDisplayResponse
	 */
	private function create_response(Template $view)
	{
        $step_title = $this->lang['step.license.title'];
		$response = new InstallDisplayResponse(1, $step_title, $view);
		return $response;
	}

	private function build_form(Template $view)
    {
    	$form = new HTMLForm('licenseForm', InstallUrlBuilder::server_configuration());
    	$fieldset = new FormFieldsetHTML('agreementFieldset', $this->lang['step.license.terms.title']);
    	$form->add_fieldset($fieldset);
    	$agreement = new FormFieldHTML('agreementExplanation', $this->lang['step.license.require.agreement'] . '<br /><br />');
    	$fieldset->add_field($agreement);
    	$license_content = file_get_contents_emulate('license.txt');
    	$license_block = '<div style="width:auto;height:340px;overflow-y:scroll;border:1px solid #DFDFDF;background-color:#F1F4F1">' . $license_content . '</div>';
    	$license = new FormFieldHTML('licenseContent', $license_block);
    	$fieldset->add_field($license);
    	$agree_checkbox = new FormFieldCheckbox('agree', $this->lang['step.license.please_agree']);
    	$agree_checkbox->add_constraint(new FormFieldConstraintNotEmpty());
    	$fieldset->add_field($agree_checkbox);
    	$navigation_bar = $this->add_navigation();
    	$form->add_button($navigation_bar);
		$view->put('LICENSE_FORM', $form->display());
    }

	private function add_navigation()
    {
    	$nav = new InstallNavigationBar();
    	$nav->set_previous_step_url(InstallUrlBuilder::welcome()->absolute());
        return $nav;
    }
}
?>