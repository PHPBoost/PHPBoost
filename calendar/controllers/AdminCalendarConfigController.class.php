<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 03 23
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
			$this->form->get_field_by_id('characters_number_to_cut')->set_hidden(($this->config->is_full_item_displayed() && $this->config->get_display_type() == CalendarConfig::LIST_VIEW) || $this->config->get_display_type() == CalendarConfig::TABLE_VIEW);
			$this->form->get_field_by_id('full_item_display')->set_hidden($this->config->get_display_type() !== CalendarConfig::LIST_VIEW);
			$this->form->get_field_by_id('items_per_row')->set_hidden($this->config->get_display_type() !== CalendarConfig::GRID_VIEW);
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

		$fieldset = new FormFieldsetHTML('configuration', StringVars::replace_vars($this->admin_common_lang['configuration.module.title'], array('module_name' => self::get_module()->get_configuration()->get_name())));
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldNumberEditor('items_number_per_page', $this->lang['calendar.config.items.number.per.page'], $this->config->get_items_number_per_page(),
			array('class' => 'third-field', 'min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

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

		$fieldset->add_field(new FormFieldSpacer('display', ''));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('display_type', $this->admin_common_lang['config.display.type'], $this->config->get_display_type(),
			array(
				new FormFieldSelectChoiceOption($this->admin_common_lang['config.display.type.grid'], CalendarConfig::GRID_VIEW, array('data_option_icon' => 'fa fa-th-large')),
				new FormFieldSelectChoiceOption($this->admin_common_lang['config.display.type.list'], CalendarConfig::LIST_VIEW, array('data_option_icon' => 'fa fa-list')),
				new FormFieldSelectChoiceOption($this->admin_common_lang['config.display.type.table'], CalendarConfig::TABLE_VIEW, array('data_option_icon' => 'fa fa-table'))
			),
			array(
				'select_to_list' => true,
				'events' => array('change' => '
					if (HTMLForms.getField("display_type").getValue() == \'' . CalendarConfig::GRID_VIEW . '\') {
						HTMLForms.getField("items_per_row").enable();
						HTMLForms.getField("characters_number_to_cut").enable();
						HTMLForms.getField("full_item_display").disable();
					} else if (HTMLForms.getField("display_type").getValue() == \'' . CalendarConfig::LIST_VIEW . '\') {
						HTMLForms.getField("full_item_display").enable();
						HTMLForms.getField("items_per_row").disable();
						if (HTMLForms.getField("full_item_display").getValue()) {
							HTMLForms.getField("characters_number_to_cut").disable();
						} else {
							HTMLForms.getField("characters_number_to_cut").enable();
						}
					} else {
						HTMLForms.getField("items_per_row").disable();
						HTMLForms.getField("full_item_display").disable();
						HTMLForms.getField("characters_number_to_cut").disable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldNumberEditor('items_per_row', $this->admin_common_lang['config.items.per.row'], $this->config->get_items_per_row(),
			array(
				'hidden' => $this->config->get_display_type() !== CalendarConfig::GRID_VIEW,
				'min' => 1, 'max' => 4, 'required' => true),
				array(new FormFieldConstraintIntegerRange(1, 4))
		));

		$fieldset->add_field(new FormFieldCheckbox('full_item_display', $this->admin_common_lang['config.full.item.display'], $this->config->is_full_item_displayed(),
			array(
				'class' => 'custom-checkbox',
				'hidden' => $this->config->get_display_type() !== CalendarConfig::LIST_VIEW,
				'events' => array('click' => '
					if (HTMLForms.getField("full_item_display").getValue()) {
						HTMLForms.getField("characters_number_to_cut").disable();
					} else {
						HTMLForms.getField("characters_number_to_cut").enable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldNumberEditor('characters_number_to_cut', $this->admin_common_lang['config.characters.number.to.cut'], $this->config->get_characters_number_to_cut(),
			array(
				'min' => 20, 'max' => 1000, 'required' => true,
				'hidden' => $this->config->get_display_type() == CalendarConfig::TABLE_VIEW || ($this->config->get_display_type() == CalendarConfig::LIST_VIEW && $this->config->is_full_item_displayed())
			),
			array(new FormFieldConstraintIntegerRange(20, 1000)
		)));

        $fieldset->add_field(new FormFieldRichTextEditor('default_content', $this->lang['calendar.default.content'], $this->config->get_default_content(),
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

		if ($this->form->get_value('display_type') == CalendarConfig::GRID_VIEW)
		{
			$this->config->set_items_number_per_row($this->form->get_value('items_per_row'));
			$this->config->set_characters_number_to_cut($this->form->get_value('characters_number_to_cut'));
		}

		if ($this->form->get_value('display_type') == CalendarConfig::LIST_VIEW)
		{
			if ($this->form->get_value('full_item_display'))
				$this->config->display_full_item();
			else
			{
				$this->config->set_characters_number_to_cut($this->form->get_value('characters_number_to_cut'));
				$this->config->display_condensed_item();
			}
		}

		$this->config->set_display_type($this->form->get_value('display_type')->get_raw_value());
		$this->config->set_default_content($this->form->get_value('default_content'));
        $this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());

		CalendarConfig::save();
		CategoriesService::get_categories_manager()->regenerate_cache();
		CalendarService::clear_cache();
	}
}
?>
