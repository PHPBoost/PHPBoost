<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 11
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
	private $admin_common_lang;

	private $user_born_field;

	/**
	 * @var CalendarConfig
	 */
	private $config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->build_form();

		$view = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$view->add_lang(array_merge($this->lang, $this->admin_common_lang));

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			if ($this->user_born_field['display'])
				$this->form->get_field_by_id('birthday_color')->set_hidden(!$this->config->is_members_birthday_enabled());
			$view->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}

		$view->put('FORM', $this->form->display());

		return new DefaultAdminDisplayResponse($view);
	}

	private function init()
	{
		$this->user_born_field = ExtendedFieldsCache::load()->get_extended_field_by_field_name('user_born');
		$this->lang = LangLoader::get('common', 'calendar');
		$this->admin_common_lang = LangLoader::get('admin-common');
		$this->config = CalendarConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTMLHeading('configuration', StringVars::replace_vars($this->admin_common_lang['configuration.module.title'], array('module_name' => self::get_module()->get_configuration()->get_name())));
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldNumberEditor('items_number_per_page', $this->lang['calendar.config.items.number.per.page'], $this->config->get_items_number_per_page(),
			array('class' => 'third-field', 'min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

		$fieldset->add_field(new FormFieldNumberEditor('characters_number_to_cut', $this->admin_common_lang['config.characters.number.to.cut'], $this->config->get_characters_number_to_cut(),
			array(
				'class' => 'third-field', 'min' => 0, 'max' => 1000,
				'description' => $this->lang['calendar.config.set.to.zero']
			),
			array(new FormFieldConstraintIntegerRange(0, 1000)
		)));

		$fieldset->add_field(new FormFieldColorPicker('event_color', $this->lang['calendar.config.event.color'], $this->config->get_event_color(),
			array('class' => 'third-field'),
			array(new FormFieldConstraintRegex('`^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$`iu'))
		));

		$fieldset->add_field(new FormFieldSpacer('birthday_display', ''));

		if (!empty($this->user_born_field) && !$this->user_born_field['display'])
		{
			$fieldset->add_field(new FormFieldHTML('user_born_disabled_msg', MessageHelper::display($this->lang['calendar.error.user.born.field.disabled'], MessageHelper::WARNING)->render()));
		}
		else
		{
			$fieldset->add_field(new FormFieldCheckbox('members_birthday_enabled', $this->lang['calendar.config.members.birthday.enabled'], $this->config->is_members_birthday_enabled(),
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

			$fieldset->add_field(new FormFieldColorPicker('birthday_color', $this->lang['calendar.config.birthday.color'], $this->config->get_birthday_color(),
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
		$this->config->set_characters_number_to_cut($this->form->get_value('characters_number_to_cut', $this->config->get_characters_number_to_cut()));
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
