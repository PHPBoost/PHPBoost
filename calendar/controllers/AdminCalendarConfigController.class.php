<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 16
 * @since       PHPBoost 3.0 - 2012 11 20
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor xela <xela@phpboost.com>
*/

class AdminCalendarConfigController extends AdminModuleController
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

	private $user_born_field;

	/**
	 * @var CalendarConfig
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
			if ($this->user_born_field['display'])
				$this->form->get_field_by_id('birthday_color')->set_hidden(!$this->config->is_members_birthday_enabled());
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}

		$tpl->put('FORM', $this->form->display());

		return new DefaultAdminDisplayResponse($tpl);
	}

	private function init()
	{
		$this->user_born_field = ExtendedFieldsCache::load()->get_extended_field_by_field_name('user_born');
		$this->lang = LangLoader::get('common', 'calendar');
		$this->config = CalendarConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTMLHeading('configuration', StringVars::replace_vars(LangLoader::get_message('configuration.module.title', 'admin-common'), array('module_name' => self::get_module()->get_configuration()->get_name())));
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldNumberEditor('items_number_per_page', $this->lang['calendar.config.items_number_per_page'], $this->config->get_items_number_per_page(),
			array('class' => 'top-field', 'min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

		$fieldset->add_field(new FormFieldColorPicker('event_color', $this->lang['calendar.config.event_color'], $this->config->get_event_color(),
			array('class' => 'top-field'),
			array(new FormFieldConstraintRegex('`^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$`iu'))
		));

		if (!empty($this->user_born_field) && !$this->user_born_field['display'])
		{
			$fieldset->add_field(new FormFieldHTML('user_born_disabled_msg', MessageHelper::display($this->lang['calendar.error.e_user_born_field_disabled'], MessageHelper::WARNING)->render()));
		}
		else
		{
			$fieldset->add_field(new FormFieldCheckbox('members_birthday_enabled', $this->lang['calendar.config.members_birthday_enabled'], $this->config->is_members_birthday_enabled(),
				array(
					'class' => 'custom-checkbox',
					'events' => array('click' => '
						if (HTMLForms.getField("members_birthday_enabled").getValue()) {
							HTMLForms.getField("birthday_color").enable();
						} else {
							HTMLForms.getField("birthday_color").disable();
						}'
					)
				)
			));

			$fieldset->add_field(new FormFieldColorPicker('birthday_color', $this->lang['calendar.config.birthday_color'], $this->config->get_birthday_color(),
				array('hidden' => !$this->config->is_members_birthday_enabled()),
				array(new FormFieldConstraintRegex('`^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$`iu'))
			));
		}
        
        $fieldset->add_field(new FormFieldRichTextEditor('default_contents', $this->lang['calendar.default.contents'], $this->config->get_default_contents(),
			array('rows' => 8, 'cols' => 47)
		));

		$fieldset = new FormFieldsetHTML('authorizations_fieldset', LangLoader::get_message('authorizations', 'common'),
			array('description' => LangLoader::get_message('config.authorizations.explain', 'admin-common'))
		);
		$form->add_fieldset($fieldset);

		$auth_settings = new AuthorizationsSettings(RootCategory::get_authorizations_settings());
		$auth_settings->build_from_auth_array($this->config->get_authorizations());
		$fieldset->add_field(new FormFieldAuthorizationsSetter('authorizations', $auth_settings));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function save()
	{
		$this->config->set_items_number_per_page($this->form->get_value('items_number_per_page'));

		$this->config->set_event_color($this->form->get_value('event_color'));

		if ($this->form->get_value('members_birthday_enabled'))
		{
			$this->config->enable_members_birthday();
			$this->config->set_birthday_color($this->form->get_value('birthday_color'));
		}
		else
			$this->config->disable_members_birthday();

		$this->config->set_default_contents($this->form->get_value('default_contents'));
        $this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());

		CalendarConfig::save();
		CategoriesService::get_categories_manager()->regenerate_cache();
		CalendarService::clear_cache();
	}
}
?>
