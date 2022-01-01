<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 05
 * @since       PHPBoost 3.0 - 2012 10 08
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminBugtrackerAuthorizationsController extends DefaultAdminModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		// Form building
		$this->build_form();

		// Saving of the configuration if the submit button has been submitted
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.success.config'], MessageHelper::SUCCESS, 5));
		}

		// Display the form on the template
		$this->view->put('CONTENT', $this->form->display());

		// Display the generated page
		return new AdminBugtrackerDisplayResponse($this->view, $this->lang['bugtracker.authorizations.module.title']);
	}

	private function build_form()
	{
		// Creation of a new form
		$form = new HTMLForm(__CLASS__);

		// Add a fieldset to the form
		$fieldset_authorizations = new FormFieldsetHTML('authorizations', $this->lang['bugtracker.authorizations.module.title']);
		$form->add_fieldset($fieldset_authorizations);

		// Authorizations list
		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($this->lang['config.auth.read'], BugtrackerAuthorizationsService::READ_AUTHORIZATIONS),
			new ActionAuthorization($this->lang['config.auth.create'], BugtrackerAuthorizationsService::WRITE_AUTHORIZATIONS),
			new ActionAuthorization($this->lang['config.auth.create_advanced'], BugtrackerAuthorizationsService::ADVANCED_WRITE_AUTHORIZATIONS, $this->lang['config.auth.create_advanced_explain']),
			new MemberDisabledActionAuthorization($this->lang['config.auth.moderate'], BugtrackerAuthorizationsService::MODERATION_AUTHORIZATIONS)
		));

		//Load the authorizations in the configuration
		$auth_settings->build_from_auth_array(BugtrackerConfig::load()->get_authorizations());
		$fieldset_authorizations->add_field(new FormFieldAuthorizationsSetter('authorizations', $auth_settings));

		//Submit and reset buttons
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function save()
	{
		//Save the authorizations list
		BugtrackerConfig::load()->set_authorizations($this->form->get_value('authorizations')->build_auth_array());
		BugtrackerConfig::save();

		HooksService::execute_hook_action('edit_config', self::$module_id, array('title' => $this->lang['bugtracker.authorizations.module.title'], 'url' => BugtrackerUrlBuilder::authorizations()->rel()));
	}
}
?>
