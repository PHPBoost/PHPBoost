<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 02 25
 * @since       PHPBoost 5.2 - 2020 06 15
*/

class AdminPagesConfigController extends DefaultConfigurationController
{
	protected function build_form()
	{
		$form = new HTMLForm(self::$module_id . '_config_form');

		$fieldset = new FormFieldsetHTML('configuration', StringVars::replace_vars($this->lang['configuration.module.title'], array('module_name' => self::get_module_configuration()->get_name())));
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldCheckbox('left_column_disabled', $this->lang['pages.config.left.column'], $this->config->get_left_column_disabled(),
			array('class' => 'custom-checkbox')
		));

		$fieldset->add_field(new FormFieldCheckbox('right_column_disabled', $this->lang['pages.config.right.column'], $this->config->get_right_column_disabled(),
			array('class' => 'custom-checkbox')
		));

		$fieldset->add_field(new FormFieldCheckbox('views_number_enabled', $this->lang['config.views.number.enabled'], $this->config->get_views_number_enabled(),
			array('class' => 'custom-checkbox')
		));

		$fieldset->add_field(new FormFieldRichTextEditor('root_category_description', $this->lang['config.root_category_description'], $this->config->get_root_category_description(),
			array('rows' => 8, 'cols' => 47)
		));

		$fieldset->add_field(new FormFieldRichTextEditor('default_content', $this->lang['pages.config.default.content'], $this->config->get_default_content(),
			array('rows' => 8, 'cols' => 47)
		));
		
		$fieldset_authorizations = new FormFieldsetHTML('authorizations', LangLoader::get_message('authorizations', 'common'),
			array('description' => $this->lang['config.authorizations.explain'])
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
		$this->config->set_left_column_disabled($this->form->get_value('left_column_disabled'));
		$this->config->set_right_column_disabled($this->form->get_value('right_column_disabled'));
		$this->config->set_views_number_enabled($this->form->get_value('views_number_enabled'));
		$this->config->set_root_category_description($this->form->get_value('root_category_description'));
		$this->config->set_default_content($this->form->get_value('default_content'));
		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());

		PagesConfig::save(self::$module_id);

		CategoriesService::get_categories_manager()->regenerate_cache();
	}
}
?>
