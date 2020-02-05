<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 05
 * @since       PHPBoost 4.0 - 2013 02 27
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor xela <xela@phpboost.com>
*/

class AdminArticlesConfigController extends AdminModuleController
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

	/**
	 * @var ArticlesConfig
	 */
	private $config;
	private $comments_config;
	private $content_management_config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->build_form();

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->form->get_field_by_id('items_per_row')->set_hidden($this->config->get_display_type() !== ArticlesConfig::GRID_VIEW);
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 4));
		}

		$tpl->put('FORM', $this->form->display());

		return new DefaultAdminDisplayResponse($tpl);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'articles');
		$this->admin_common_lang = LangLoader::get('admin-common');
		$this->config = ArticlesConfig::load();
		$this->comments_config = CommentsConfig::load();
		$this->content_management_config = ContentManagementConfig::load();
	}

	private function build_form()
	{
		$item_class_name = self::get_module()->get_configuration()->get_item_name();
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTMLHeading('configuration', StringVars::replace_vars(LangLoader::get_message('configuration.module.title', 'admin-common'), array('module_name' => self::get_module()->get_configuration()->get_name())));
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldNumberEditor('items_per_page', $this->admin_common_lang['config.items_number_per_page'], $this->config->get_items_per_page(),
			array('min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('items_default_sort_field', $this->admin_common_lang['config.items.default.sort.field'], $this->config->get_items_default_sort_field(), $item_class_name::get_sorting_field_options(),
			array('select_to_list' => true)
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('items_default_sort_mode', $this->admin_common_lang['config.items.default.sort.mode'], $this->config->get_items_default_sort_mode(), $item_class_name::get_sorting_mode_options(),
			array('select_to_list' => true)
		));

		$fieldset->add_field(new FormFieldNumberEditor('auto_cut_characters_number', $this->lang['articles.characters.number.to.cut'], $this->config->get_auto_cut_characters_number(),
			array('min' => 20, 'max' => 1000, 'required' => true),
			array(new FormFieldConstraintIntegerRange(20, 1000))
		));

		$fieldset->add_field(new FormFieldCheckbox('summary_displayed_to_guests', $this->lang['articles.summary.displayed.to.guests'], $this->config->get_summary_displayed_to_guests(),
			array('class' => 'custom-checkbox')
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('display_type', $this->admin_common_lang['config.display.type'], $this->config->get_display_type(),
			array(
				new FormFieldSelectChoiceOption($this->admin_common_lang['config.display.type.grid'], ArticlesConfig::GRID_VIEW, array('data_option_icon' => 'far fa-id-card')),
				new FormFieldSelectChoiceOption($this->admin_common_lang['config.display.type.list'], ArticlesConfig::LIST_VIEW, array('data_option_icon' => 'fa fa-list'))
			),
			array('select_to_list' => true,
				'events' => array('change' => '
				if (HTMLForms.getField("display_type").getValue() === \'' . ArticlesConfig::GRID_VIEW . '\') {
					HTMLForms.getField("items_per_row").enable();
				} else {
					HTMLForms.getField("items_per_row").disable();
				}'))
		));

		$fieldset->add_field(new FormFieldNumberEditor('items_per_row', $this->admin_common_lang['config.items.per.row'], $this->config->get_items_per_row(),
			array(
				'hidden' => $this->config->get_display_type() !== ArticlesConfig::GRID_VIEW,
				'min' => 1, 'max' => 4,
				'required' => true
			),
			array(new FormFieldConstraintIntegerRange(1, 4))
		));

		$fieldset->add_field(new FormFieldRichTextEditor('default_content', $this->lang['articles.default.content'], $this->config->get_default_content(),
			array('rows' => 8, 'cols' => 47)
		));

		$fieldset_categories = new FormFieldsetHTML('categories', LangLoader::get_message('categories', 'categories-common'));
		$form->add_fieldset($fieldset_categories);

		$fieldset_categories->add_field(new FormFieldNumberEditor('categories_per_page', $this->admin_common_lang['config.categories_number_per_page'], $this->config->get_categories_per_page(),
			array('min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

		$fieldset_categories->add_field(new FormFieldNumberEditor('categories_per_row', $this->admin_common_lang['config.categories.per.row'], $this->config->get_categories_per_row(),
			array('min' => 1, 'max' => 4, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 4))
		));

		$fieldset_categories->add_field(new FormFieldRichTextEditor('root_category_description', $this->admin_common_lang['config.root_category_description'], $this->config->get_root_category_description(),
			array('rows' => 8, 'cols' => 47)
		));

		$fieldset_authorizations = new FormFieldsetHTML('authorizations', LangLoader::get_message('authorizations', 'common'),
			array('description' => $this->admin_common_lang['config.authorizations.explain'])
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
		$this->config->set_items_per_page($this->form->get_value('items_per_page'));
		$this->config->set_items_default_sort_field($this->form->get_value('items_default_sort_field')->get_raw_value());
		$this->config->set_items_default_sort_mode($this->form->get_value('items_default_sort_mode')->get_raw_value());
		$this->config->set_auto_cut_characters_number($this->form->get_value('auto_cut_characters_number', $this->config->get_auto_cut_characters_number()));
		$this->config->set_summary_displayed_to_guests($this->form->get_value('summary_displayed_to_guests'));
		$this->config->set_display_type($this->form->get_value('display_type')->get_raw_value());
		if($this->config->get_display_type() == ArticlesConfig::GRID_VIEW)
			$this->config->set_items_per_row($this->form->get_value('items_per_row'));
		$this->config->set_default_content($this->form->get_value('default_content'));

		$this->config->set_categories_per_page($this->form->get_value('categories_per_page'));
		$this->config->set_categories_per_row($this->form->get_value('categories_per_row'));
		$this->config->set_root_category_description($this->form->get_value('root_category_description'));

		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());

		ArticlesConfig::save();
		CategoriesService::get_categories_manager()->regenerate_cache();
	}
}
?>
