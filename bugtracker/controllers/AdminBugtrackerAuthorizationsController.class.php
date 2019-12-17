<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 11 05
 * @since       PHPBoost 3.0 - 2012 10 08
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class AdminBugtrackerAuthorizationsController extends AdminModuleController
{
	private $lang;
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonDefaultSubmit
	 */
	private $submit_button;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		//Form building
		$this->build_form();

		//Creation of the template
		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		//Saving of the configuration if the submit button has been submitted
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}

		//Display the form on the template
		$tpl->put('FORM', $this->form->display());

		//Display the generated page
		return new AdminBugtrackerDisplayResponse($tpl, $this->lang['titles.admin.module_authorizations']);
	}

	private function init()
	{
		//Load module lang
		$this->lang = LangLoader::get('common', 'bugtracker');
	}

	private function build_form()
	{
		//Creation of a new form
		$form = new HTMLForm(__CLASS__);

		//Add a fieldset
		$fieldset_authorizations = new FormFieldsetHTMLHeading('authorizations', LangLoader::get_message('authorizations', 'common'));
		$form->add_fieldset($fieldset_authorizations);

		//Authorizations list
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
	}
}
?>
