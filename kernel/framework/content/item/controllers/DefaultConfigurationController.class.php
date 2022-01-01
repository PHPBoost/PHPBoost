<?php
/**
 * @package     Content
 * @subpackage  Item\controllers
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 11 30
 * @since       PHPBoost 6.0 - 2020 02 11
 * @contributor xela <xela@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DefaultConfigurationController extends AbstractAdminItemController
{
	protected $additional_fields_list = array();

	public function execute(HTTPRequestCustom $request)
	{
		$this->build_form();

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->execute_edit_config_hook();
			if (self::get_module_configuration()->has_rich_items())
			{
				$this->form->get_field_by_id('items_per_row')->set_hidden($this->config->get_display_type() != DefaultRichModuleConfig::GRID_VIEW);
				if ($this->module_item->content_field_enabled() && $this->module_item->summary_field_enabled())
				{
					$this->form->get_field_by_id('full_item_display')->set_hidden($this->config->get_display_type() != DefaultRichModuleConfig::LIST_VIEW);
					$this->form->get_field_by_id('auto_cut_characters_number')->set_hidden(($this->config->get_display_type() == DefaultRichModuleConfig::LIST_VIEW && $this->config->get_full_item_display()) || ($this->config->get_display_type() == DefaultRichModuleConfig::TABLE_VIEW));
					$this->form->get_field_by_id('summary_displayed_to_guests')->set_hidden($this->config->get_display_type() == DefaultRichModuleConfig::TABLE_VIEW);
				}
			}
			$this->hide_fields();

			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.success.config'], MessageHelper::SUCCESS, 4));
		}

		$this->view->put('CONTENT', $this->form->display());

		return new DefaultAdminDisplayResponse($this->view);
	}

	protected function build_form()
	{
		$item_class_name = self::get_module_configuration()->get_item_name();
		$form = new HTMLForm(self::$module_id . '_config_form');

		$fieldset = new FormFieldsetHTML('configuration', StringVars::replace_vars($this->lang['form.module.title'], array('module_name' => self::get_module_configuration()->get_name())));
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldNumberEditor('items_per_page', $this->lang['config.items.per.page'], $this->config->get_items_per_page(),
			array('min' => 1, 'max' => 50, 'required' => true, 'class' => 'third-field'),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

		if (self::get_module_configuration()->has_rich_items())
		{
			$fieldset->add_field(new FormFieldSimpleSelectChoice('items_default_sort_field', $this->lang['config.items.default.sort'], $this->config->get_items_default_sort_field(), $this->module_item->get_sorting_field_options(),
				array('select_to_list' => true, 'class' => 'third-field')
			));

			$fieldset->add_field(new FormFieldSimpleSelectChoice('items_default_sort_mode', $this->lang['config.items.default.sort.mode'], $this->config->get_items_default_sort_mode(), $item_class_name::get_sorting_mode_options(),
				array('select_to_list' => true, 'class' => 'third-field')
			));

			$fieldset->add_field(new FormFieldSpacer('display', ''));

			$fieldset->add_field(new FormFieldSimpleSelectChoice('display_type', $this->lang['form.display.type'], $this->config->get_display_type(),
				array(
					new FormFieldSelectChoiceOption($this->lang['form.display.type.grid'], DefaultRichModuleConfig::GRID_VIEW, array('data_option_icon' => 'far fa-id-card')),
					new FormFieldSelectChoiceOption($this->lang['form.display.type.list'], DefaultRichModuleConfig::LIST_VIEW, array('data_option_icon' => 'fa fa-list')),
					new FormFieldSelectChoiceOption($this->lang['form.display.type.table'], DefaultRichModuleConfig::TABLE_VIEW, array('data_option_icon' => 'fa fa-table'))
				),
				array('select_to_list' => true,
					'events' => array('change' => '
					if (HTMLForms.getField("display_type").getValue() == \'' . DefaultRichModuleConfig::GRID_VIEW . '\') {
						HTMLForms.getField("items_per_row").enable();
						HTMLForms.getField("full_item_display").disable();
						HTMLForms.getField("auto_cut_characters_number").enable();
						HTMLForms.getField("summary_displayed_to_guests").enable();
					} else if (HTMLForms.getField("display_type").getValue() == \'' . DefaultRichModuleConfig::LIST_VIEW . '\') {
						HTMLForms.getField("items_per_row").disable();
						HTMLForms.getField("full_item_display").enable();
						HTMLForms.getField("summary_displayed_to_guests").enable();
						if (HTMLForms.getField("full_item_display").getValue()) {
							HTMLForms.getField("auto_cut_characters_number").disable();
						} else {
							HTMLForms.getField("auto_cut_characters_number").enable();
						}
					} else {
						HTMLForms.getField("items_per_row").disable();
						HTMLForms.getField("full_item_display").disable();
						HTMLForms.getField("auto_cut_characters_number").disable();
						HTMLForms.getField("summary_displayed_to_guests").disable();
					}'
				))
			));

			$fieldset->add_field(new FormFieldNumberEditor('items_per_row', $this->lang['config.items.per.row'], $this->config->get_items_per_row(),
				array(
					'hidden' => $this->config->get_display_type() != DefaultRichModuleConfig::GRID_VIEW,
					'min' => 1, 'max' => 4,
					'required' => true
				),
				array(new FormFieldConstraintIntegerRange(1, 4))
			));

			if ($this->module_item->content_field_enabled() && $this->module_item->summary_field_enabled())
			{
				$fieldset->add_field(new FormFieldCheckbox('full_item_display', $this->lang['config.full.item.display'], $this->config->get_full_item_display(),
					array(
						'class' => 'custom-checkbox',
						'hidden' => $this->config->get_display_type() != DefaultRichModuleConfig::LIST_VIEW,
						'events' => array('click' => '
							if (HTMLForms.getField("full_item_display").getValue()) {
								HTMLForms.getField("auto_cut_characters_number").disable();
							} else {
								HTMLForms.getField("auto_cut_characters_number").enable();
							}'
						)
					)
				));

				$fieldset->add_field(new FormFieldNumberEditor('auto_cut_characters_number', $this->lang['config.auto.cut.characters.number'], $this->config->get_auto_cut_characters_number(),
					array(
						'min' => 20, 'max' => 1000,
						'description' => $this->lang['config.auto.cut.characters.number.explain'],
						'required' => true,
						'hidden' => ($this->config->get_display_type() == DefaultRichModuleConfig::LIST_VIEW && $this->config->get_full_item_display()) || $this->config->get_display_type() == DefaultRichModuleConfig::TABLE_VIEW),
					array(new FormFieldConstraintIntegerRange(20, 1000))
				));

				$fieldset->add_field(new FormFieldCheckbox('summary_displayed_to_guests', $this->lang['config.items.summary.displayed.to.guests'], $this->config->get_summary_displayed_to_guests(),
					array(
						'class' => 'custom-checkbox',
						'hidden' => $this->config->get_display_type() == DefaultRichModuleConfig::TABLE_VIEW
					)
				));
			}

			$fieldset->add_field(new FormFieldSpacer('options', ''));

			$fieldset->add_field(new FormFieldCheckbox('sort_form_displayed', $this->lang['config.display.sort.form'], $this->config->get_sort_form_displayed(),
				array('class' => 'custom-checkbox')
			));

			$fieldset->add_field(new FormFieldCheckbox('author_displayed', $this->lang['form.display.author'], $this->config->get_author_displayed(),
				array('class' => 'custom-checkbox')
			));

			$fieldset->add_field(new FormFieldCheckbox('date_displayed', $this->lang['form.display.date'], $this->config->get_date_displayed(),
				array('class' => 'custom-checkbox')
			));

			$fieldset->add_field(new FormFieldCheckbox('update_date_displayed', $this->lang['form.display.update.date'], $this->config->get_update_date_displayed(),
				array('class' => 'custom-checkbox')
			));

			$fieldset->add_field(new FormFieldCheckbox('views_number_enabled', $this->lang['form.display.views.number'], $this->config->get_views_number_enabled(),
				array('class' => 'custom-checkbox')
			));

			$this->add_additional_fields($fieldset);

			if ($this->module_item->content_field_enabled())
			{
				$fieldset->add_field(new FormFieldRichTextEditor('default_content', $this->lang['config.item.default.content'], $this->config->get_default_content(),
					array('rows' => 8, 'cols' => 47)
				));
			}

			$this->add_additional_fieldsets($form);

			if (self::get_module_configuration()->has_categories())
			{
				$fieldset_categories = new FormFieldsetHTML('categories', $this->lang['category.categories']);
				$form->add_fieldset($fieldset_categories);

				if ($this->module_item->sub_categories_displayed())
				{
					$fieldset_categories->add_field(new FormFieldNumberEditor('categories_per_page', $this->lang['form.categories.per.page'], $this->config->get_categories_per_page(),
						array('min' => 1, 'max' => 50, 'required' => true),
						array(new FormFieldConstraintIntegerRange(1, 50))
					));

					$fieldset_categories->add_field(new FormFieldNumberEditor('categories_per_row', $this->lang['form.categories.per.row'], $this->config->get_categories_per_row(),
						array('min' => 1, 'max' => 4, 'required' => true),
						array(new FormFieldConstraintIntegerRange(1, 4))
					));
				}

				$fieldset_categories->add_field(new FormFieldRichTextEditor('root_category_description', $this->lang['form.root.category.description'], $this->config->get_root_category_description(),
					array('rows' => 8, 'cols' => 47)
				));
			}
		}
		else
		{
			$this->add_additional_fields($fieldset);
			$this->add_additional_fieldsets($form);
		}

		$fieldset_authorizations = new FormFieldsetHTML('authorizations', $this->lang['form.authorizations'],
			array('description' => $this->lang['form.authorizations.clue'])
		);

		$form->add_fieldset($fieldset_authorizations);

		$auth_settings = new AuthorizationsSettings(array_merge(RootCategory::get_authorizations_settings(self::get_module()->get_id()), $this->add_additional_actions_authorization()));
		$auth_settings->build_from_auth_array($this->config->get_authorizations());
		$fieldset_authorizations->add_field(new FormFieldAuthorizationsSetter('authorizations', $auth_settings));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	protected function save()
	{
		$this->config->set_items_per_page($this->form->get_value('items_per_page'));

		if (self::get_module_configuration()->has_rich_items())
		{
			$this->config->set_items_default_sort_field($this->form->get_value('items_default_sort_field')->get_raw_value());
			$this->config->set_items_default_sort_mode($this->form->get_value('items_default_sort_mode')->get_raw_value());
			$this->config->set_sort_form_displayed($this->form->get_value('sort_form_displayed'));
			$this->config->set_author_displayed($this->form->get_value('author_displayed'));
			$this->config->set_date_displayed($this->form->get_value('date_displayed'));
			$this->config->set_update_date_displayed($this->form->get_value('update_date_displayed'));
			$this->config->set_views_number_enabled($this->form->get_value('views_number_enabled'));
			$this->config->set_display_type($this->form->get_value('display_type')->get_raw_value());
			if ($this->config->get_display_type() == DefaultRichModuleConfig::GRID_VIEW)
				$this->config->set_items_per_row($this->form->get_value('items_per_row'));

			if ($this->module_item->content_field_enabled())
			{
				if ($this->module_item->summary_field_enabled())
				{
					if ($this->config->get_display_type() == DefaultRichModuleConfig::LIST_VIEW)
						$this->config->set_full_item_display($this->form->get_value('full_item_display'));
					if ($this->config->get_display_type() == DefaultRichModuleConfig::LIST_VIEW && !$this->config->get_full_item_display())
						$this->config->set_auto_cut_characters_number($this->form->get_value('auto_cut_characters_number'));
					if ($this->config->get_display_type() == DefaultRichModuleConfig::GRID_VIEW) {
						$this->config->set_auto_cut_characters_number($this->form->get_value('auto_cut_characters_number'));
						$this->config->set_full_item_display(false);
					}
					if ($this->config->get_display_type() != DefaultRichModuleConfig::TABLE_VIEW)
						$this->config->set_summary_displayed_to_guests($this->form->get_value('summary_displayed_to_guests'));
				}
				$this->config->set_default_content($this->form->get_value('default_content'));
			}

			if (self::get_module_configuration()->has_categories())
			{
				if ($this->module_item->sub_categories_displayed())
				{
					$this->config->set_categories_per_page($this->form->get_value('categories_per_page'));
					$this->config->set_categories_per_row($this->form->get_value('categories_per_row'));
				}
				$this->config->set_root_category_description($this->form->get_value('root_category_description'));
			}
		}
		$this->save_additional_fields();

		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());

		$configuration_class_name = self::get_module_configuration()->get_configuration_name();
		$configuration_class_name::save(self::$module_id);

		if (self::get_module_configuration()->has_categories())
			CategoriesService::get_categories_manager(self::$module_id)->regenerate_cache();
	}

	protected function execute_edit_config_hook()
	{
		HooksService::execute_hook_action('edit_config', self::$module_id, array('title' => StringVars::replace_vars($this->lang['form.module.title'], array('module_name' => self::get_module_configuration()->get_name())), 'url' => ModulesUrlBuilder::configuration()->rel()));
	}

	protected function hide_fields() {}

	protected function add_additional_fields(&$fieldset)
	{
		// Automatically add module dedicated configuration parameters to config form
		// Lang variable config.parameter.name (for a parameter PARAMETER_NAME in module config file) requested in lang file
		$configuration_class_name = self::get_module_configuration()->get_configuration_name();
		if (!in_array($configuration_class_name, array('DefaultModuleConfig', 'DefaultRichModuleConfig')))
		{
			$kernel_configuration_class = new ReflectionClass('DefaultRichModuleConfig');
			$configuration_class = new ReflectionClass($configuration_class_name);

			foreach (array_diff($configuration_class->getConstants(), $kernel_configuration_class->getConstants()) as $parameter)
			{
				$parameter_lang_variable = 'config.' . str_replace('_', '.', $parameter);
				if (isset($this->lang[$parameter_lang_variable]))
				{
					$this->additional_fields_list[] = $parameter;
					$parameter_get_method = 'get_' . $parameter;
					if (is_string($parameter))
					{
						$type = gettype($configuration_class->getMethod('get_default_value')->invoke($this->config, $parameter));

						switch ($type) {
							case 'boolean':
								$fieldset->add_field(new FormFieldCheckbox($parameter, $this->lang[$parameter_lang_variable], $this->config->$parameter_get_method(),
									array('class' => 'custom-checkbox', 'description' => (isset($this->lang[$parameter_lang_variable . '.explain']) ? $this->lang[$parameter_lang_variable . '.explain'] : ''))
								));
							break;
							case 'integer':
								$fieldset->add_field(new FormFieldNumberEditor($parameter, $this->lang[$parameter_lang_variable], $this->config->$parameter_get_method(),
									array('min' => 1, 'max' => 50, 'description' => (isset($this->lang[$parameter_lang_variable . '.explain']) ? $this->lang[$parameter_lang_variable . '.explain'] : ''), 'required' => true),
									array(new FormFieldConstraintIntegerRange(1, 50))
								));
							break;
							case 'string':
								$fieldset->add_field(new FormFieldTextEditor($parameter, $this->lang[$parameter_lang_variable], $this->config->$parameter_get_method(),
									array('maxlength' => 100, 'description' => (isset($this->lang[$parameter_lang_variable . '.explain']) ? $this->lang[$parameter_lang_variable . '.explain'] : ''), 'required' => true, 'class' => 'top-field')
								));
							break;
						}
					}
				}
			}
		}
	}

	protected function save_additional_fields()
	{
		foreach ($this->additional_fields_list as $parameter)
		{
			$parameter_set_method = 'set_' . $parameter;
			$this->config->$parameter_set_method($this->form->get_value($parameter));
		}
	}

	protected function add_additional_fieldsets(&$form) {}

	protected function add_additional_actions_authorization() { return array(); }
}
?>
