<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 26
 * @since       PHPBoost 3.0 - 2012 01 29
 * @contributor xela <xela@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
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

		$view = new StringTemplate('# INCLUDE MESSAGE_HELPER # # INCLUDE FORM #');

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$view->put('MESSAGE_HELPER', MessageHelper::display(LangLoader::get_message('warning.success.config', 'warning-lang'), MessageHelper::SUCCESS, 4));
		}

		$view->put('FORM', $this->form->display());

		return new DefaultAdminDisplayResponse($view);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'online');
		$this->config = OnlineConfig::load();
	}

	private function build_form()
	{
		$form_lang = LangLoader::get('form-lang');
		$user_lang = LangLoader::get('user-lang');
		$form = new HTMLForm(__CLASS__);

		$fieldset_config = new FormFieldsetHTML('configuration', StringVars::replace_vars($form_lang['form.module.title'], array('module_name' => self::get_module()->get_configuration()->get_name())));
		$form->add_fieldset($fieldset_config);

		$fieldset_config->add_field(new FormFieldNumberEditor('number_member_displayed', $form_lang['form.items.in.menu'], $this->config->get_members_number_displayed(),
			array('min' => 1, 'max' => 1000, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 1000))
		));

		$fieldset_config->add_field(new FormFieldNumberEditor('number_members_per_page', $form_lang['form.items.per.page'], $this->config->get_number_members_per_page(),
			array('min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

		$fieldset_config->add_field(new FormFieldSimpleSelectChoice('display_order', $form_lang['form.items.default.sort'], $this->config->get_display_order(), array(
				new FormFieldSelectChoiceOption($user_lang['user.ranks'], OnlineConfig::LEVEL_DISPLAY_ORDER),
				new FormFieldSelectChoiceOption($user_lang['user.last.connection'], OnlineConfig::SESSION_TIME_DISPLAY_ORDER),
				new FormFieldSelectChoiceOption($user_lang['user.ranks'] . ' + ' . $user_lang['user.last.connection'], OnlineConfig::LEVEL_AND_SESSION_TIME_DISPLAY_ORDER)
			)
		));

		$fieldset_config->add_field(new FormFieldCheckbox('display_robots', $this->lang['online.display.robots'], $this->config->are_robots_displayed(),
			array('class' => 'custom-checkbox')
		));

		$fieldset_authorizations = new FormFieldsetHTML('authorizations', $form_lang['form.authorizations']);
		$form->add_fieldset($fieldset_authorizations);

		//Authorizations list
		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($form_lang['form.authorizations.read'], OnlineAuthorizationsService::READ_AUTHORIZATIONS)
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
