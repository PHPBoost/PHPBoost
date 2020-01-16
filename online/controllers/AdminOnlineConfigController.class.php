<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 16
 * @since       PHPBoost 3.0 - 2012 01 29
 * @contributor xela <xela@phpboost.com>
*/

class AdminOnlineConfigController extends AdminModuleController
{
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonSubmit
	 */
	private $submit_button;

	private $lang;

	/**
	 * @var OnlineConfig
	 */
	private $config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->build_form();

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 4));
		}

		$tpl->put('FORM', $this->form->display());

		return new DefaultAdminDisplayResponse($tpl);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'online');
		$this->config = OnlineConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset_config = new FormFieldsetHTMLHeading('configuration', StringVars::replace_vars(LangLoader::get_message('configuration.module.title', 'admin-common'), array('module_name' => self::get_module()->get_configuration()->get_name())));
		$form->add_fieldset($fieldset_config);

		$fieldset_config->add_field(new FormFieldNumberEditor('number_member_displayed', $this->lang['admin.nbr-displayed'], $this->config->get_number_member_displayed(),
			array('min' => 1, 'max' => 1000, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 1000))
		));

		$fieldset_config->add_field(new FormFieldNumberEditor('number_members_per_page', $this->lang['admin.nbr-members-per-page'], $this->config->get_number_members_per_page(),
			array('min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

		$fieldset_config->add_field(new FormFieldSimpleSelectChoice('display_order', $this->lang['admin.display-order'], $this->config->get_display_order(), array(
				new FormFieldSelectChoiceOption(LangLoader::get_message('ranks', 'main'), OnlineConfig::LEVEL_DISPLAY_ORDER),
				new FormFieldSelectChoiceOption($this->lang['online.last_update'], OnlineConfig::SESSION_TIME_DISPLAY_ORDER),
				new FormFieldSelectChoiceOption(LangLoader::get_message('ranks', 'main') . ' ' . LangLoader::get_message('and', 'main') . ' ' . $this->lang['online.last_update'], OnlineConfig::LEVEL_AND_SESSION_TIME_DISPLAY_ORDER)
			)
		));

		$fieldset_config->add_field(new FormFieldCheckbox('display_robots', $this->lang['admin.robots-displayed'], $this->config->are_robots_displayed(),
			array('class' => 'custom-checkbox')
		));

		$fieldset_authorizations = new FormFieldsetHTML('authorizations', LangLoader::get_message('authorizations', 'common'));
		$form->add_fieldset($fieldset_authorizations);

		//Authorizations list
		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($this->lang['admin.authorizations.read'], OnlineAuthorizationsService::READ_AUTHORIZATIONS)
		));

		$auth_settings->build_from_auth_array($this->config->get_authorizations());
		$fieldset_authorizations->add_field(new FormFieldAuthorizationsSetter('authorizations', $auth_settings));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function save()
	{
		$this->config->set_number_member_displayed($this->form->get_value('number_member_displayed'));
		$this->config->set_number_members_per_page($this->form->get_value('number_members_per_page'));
		$this->config->set_display_order($this->form->get_value('display_order')->get_raw_value());

		if ($this->form->get_value('display_robots'))
			$this->config->display_robots();
		else
			$this->config->hide_robots();

		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());
		OnlineConfig::save();
	}
}
?>
