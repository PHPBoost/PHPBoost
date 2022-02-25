<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 02 25
 * @since       PHPBoost 4.1 - 2015 02 03
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor xela <xela@phpboost.com>
*/

class AdminMediaConfigController extends DefaultAdminModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		$this->build_form();

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->form->get_field_by_id('items_per_row')->set_hidden($this->config->get_display_type() !== MediaConfig::GRID_VIEW);
			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.success.config'], MessageHelper::SUCCESS, 5));
		}

		$this->view->put('CONTENT', $this->form->display());

		return new DefaultAdminDisplayResponse($this->view);
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('configuration', StringVars::replace_vars($this->lang['form.module.title'], array('module_name' => self::get_module()->get_configuration()->get_name())));
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldNumberEditor('categories_per_page', $this->lang['form.categories.per.page'], $this->config->get_categories_per_page(),
			array('min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

		$fieldset->add_field(new FormFieldNumberEditor('categories_per_row', $this->lang['form.categories.per.row'], $this->config->get_categories_per_row(),
			array('min' => 1, 'max' => 4, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 4))
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('root_category_content_type', $this->lang['media.root.content.type'], $this->config->get_root_category_content_type(),
			array(
				new FormFieldSelectChoiceOption($this->lang['media.content.type.music.and.video'], MediaConfig::CONTENT_TYPE_MUSIC_AND_VIDEO),
				new FormFieldSelectChoiceOption($this->lang['media.content.type.music'], MediaConfig::CONTENT_TYPE_MUSIC),
				new FormFieldSelectChoiceOption($this->lang['media.content.type.video'], MediaConfig::CONTENT_TYPE_VIDEO)
			)
		));

		$fieldset->add_field(new FormFieldRichTextEditor('root_category_description', $this->lang['form.root.category.description'], $this->config->get_root_category_description(),
			array('rows' => 8, 'cols' => 47)
		));

		$fieldset->add_field(new FormFieldNumberEditor('items_per_page', $this->lang['form.items.per.page'], $this->config->get_items_per_page(),
			array('min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

		$fieldset->add_field(new FormFieldNumberEditor('max_video_width', $this->lang['media.max.video.width'], $this->config->get_max_video_width(),
			array('min' => 50, 'max' => 2000, 'required' => true),
			array(new FormFieldConstraintIntegerRange(50, 2000))
		));

		$fieldset->add_field(new FormFieldNumberEditor('max_video_height', $this->lang['media.max.video.height'], $this->config->get_max_video_height(),
			array('min' => 50, 'max' => 2000, 'required' => true),
			array(new FormFieldConstraintIntegerRange(50, 2000))
		));

		$fieldset->add_field(new FormFieldCheckbox('author_displayed', $this->lang['form.display.author'], $this->config->is_author_displayed(),
			array('class' => 'custom-checkbox')
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('display_type', $this->lang['form.display.type'], $this->config->get_display_type(),
			array(
				new FormFieldSelectChoiceOption($this->lang['form.display.type.grid'], MediaConfig::GRID_VIEW, array('data_option_icon' => 'fa fa-th-large')),
				new FormFieldSelectChoiceOption($this->lang['form.display.type.list'], MediaConfig::LIST_VIEW, array('data_option_icon' => 'fa fa-list')),
			),
			array(
				'select_to_list' => true,
				'events' => array('change' => '
					if (HTMLForms.getField("display_type").getValue() == \'' . MediaConfig::GRID_VIEW . '\') {
						HTMLForms.getField("items_per_row").enable();
					} else {
						HTMLForms.getField("items_per_row").disable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldNumberEditor('characters_number_to_cut', $this->lang['form.characters.number.to.cut'], $this->config->get_characters_number_to_cut(),
			array('min' => 20, 'max' => 1000, 'required' => true),
			array(new FormFieldConstraintIntegerRange(20, 1000)
		)));

		$fieldset->add_field(new FormFieldNumberEditor('items_per_row', $this->lang['form.items.per.row'], $this->config->get_items_per_row(),
			array(
				'min' => 1, 'max' => 4, 'required' => true,
				'hidden' => $this->config->get_display_type() !== MediaConfig::GRID_VIEW
			),
			array(new FormFieldConstraintIntegerRange(1, 4))
		));

		$fieldset_constant = new FormFieldsetHTML('constant_host', $this->lang['media.constant.host']);
		$form->add_fieldset($fieldset_constant);

		$fieldset_constant->add_field(new FormFieldTextEditor('peertube_constant', $this->lang['media.constant.host.peertube'], $this->config->get_peertube_constant(),
			array('description' => $this->lang['media.constant.host.peertube.desc'])
		));

		$fieldset_authorizations = new FormFieldsetHTML('authorizations_fieldset', $this->lang['form.authorizations'],
			array('description' => $this->lang['form.authorizations.clue'])
		);
		$form->add_fieldset($fieldset_authorizations);

		$auth_settings = new AuthorizationsSettings(RootCategory::get_authorizations_settings());
		$auth_settings->build_from_auth_array($this->config->get_authorizations());
		$fieldset_authorizations->add_field(new FormFieldAuthorizationsSetter('authorizations', $auth_settings));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function save()
	{
		$this->config->set_categories_per_page($this->form->get_value('categories_per_page'));
		$this->config->set_categories_per_row($this->form->get_value('categories_per_row'));
		$this->config->set_items_per_page($this->form->get_value('items_per_page'));
		$this->config->set_items_per_row($this->form->get_value('items_per_row'));
		$this->config->set_display_type($this->form->get_value('display_type')->get_raw_value());
		$this->config->set_characters_number_to_cut($this->form->get_value('characters_number_to_cut'));

		if ($this->form->get_value('author_displayed'))
			$this->config->display_author();
		else
			$this->config->hide_author();

		$this->config->set_max_video_width($this->form->get_value('max_video_width'));
		$this->config->set_max_video_height($this->form->get_value('max_video_height'));
		$this->config->set_root_category_description($this->form->get_value('root_category_description'));
		$this->config->set_root_category_content_type($this->form->get_value('root_category_content_type')->get_raw_value());
		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());

		$this->config->set_peertube_constant($this->form->get_value('peertube_constant'));

		MediaConfig::save();
		CategoriesService::get_categories_manager('media')->regenerate_cache();

		HooksService::execute_hook_action('edit_config', self::$module_id, array('title' => StringVars::replace_vars($this->lang['form.module.title'], array('module_name' => self::get_module_configuration()->get_name())), 'url' => ModulesUrlBuilder::configuration()->rel()));
	}
}
?>
