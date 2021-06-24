<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 03 31
 * @since       PHPBoost 5.2 - 2020 06 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class AdminPagesConfigController extends DefaultConfigurationController
{
	public function execute(HTTPRequestCustom $request)
	{
		$this->build_form();

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();

			$this->view->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 4));
		}

		$this->view->put('FORM', $this->form->display());

		return new DefaultAdminDisplayResponse($this->view);
	}

	protected function build_form()
	{
		$form = new HTMLForm(self::$module_id . '_config_form');

		$fieldset = new FormFieldsetHTML('configuration', StringVars::replace_vars($this->lang['configuration.module.title'], array('module_name' => self::get_module_configuration()->get_name())));
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldCheckbox('left_column_disabled', $this->lang['config.left.column.disabled'], $this->config->get_left_column_disabled(),
			array('class' => 'custom-checkbox')
		));

		$fieldset->add_field(new FormFieldCheckbox('right_column_disabled', $this->lang['config.right.column.disabled'], $this->config->get_right_column_disabled(),
			array('class' => 'custom-checkbox')
		));

		$fieldset->add_field(new FormFieldCheckbox('views_number_enabled', $this->lang['config.views.number.enabled'], $this->config->get_views_number_enabled(),
			array('class' => 'custom-checkbox')
		));

		$fieldset->add_field(new FormFieldRichTextEditor('root_category_description', $this->lang['config.root_category_description'], $this->config->get_root_category_description(),
			array('rows' => 8, 'cols' => 47)
		));

		$fieldset->add_field(new FormFieldRichTextEditor('default_content', $this->lang['config.item.default.content'], $this->config->get_default_content(),
			array('rows' => 8, 'cols' => 47)
		));

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
