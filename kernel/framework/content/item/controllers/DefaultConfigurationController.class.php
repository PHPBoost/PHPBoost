<?php
/**
 * @package     Content
 * @subpackage  Item\controllers
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 14
 * @since       PHPBoost 5.3 - 2020 02 11
*/

class DefaultConfigurationController extends AbstractAdminItemController
{
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonSubmit
	 */
	private $submit_button;

	public function execute(HTTPRequestCustom $request)
	{
		$this->build_form();

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			if (self::get_module()->get_configuration()->has_rich_items())
				$this->form->get_field_by_id('items_per_row')->set_hidden($this->config->get_display_type() !== DefaultRichModuleConfig::GRID_VIEW);
			$this->hide_fields();
			
			$this->view->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 4));
		}

		$this->view->put('FORM', $this->form->display());

		return new DefaultAdminDisplayResponse($this->view);
	}

	private function build_form()
	{
		$item_class_name = self::get_module()->get_configuration()->get_item_name();
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTMLHeading('configuration', StringVars::replace_vars($this->lang['configuration.module.title'], array('module_name' => self::get_module()->get_configuration()->get_name())));
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldNumberEditor('items_per_page', $this->items_lang['config.items.per.page'], $this->config->get_items_per_page(),
			array('min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

		if (self::get_module()->get_configuration()->has_rich_items())
		{
			$fieldset->add_field(new FormFieldSimpleSelectChoice('items_default_sort_field', $this->items_lang['config.items.default.sort.field'], $this->config->get_items_default_sort_field(), $item_class_name::get_sorting_field_options(),
				array('select_to_list' => true)
			));

			$fieldset->add_field(new FormFieldSimpleSelectChoice('items_default_sort_mode', $this->items_lang['config.items.default.sort.mode'], $this->config->get_items_default_sort_mode(), $item_class_name::get_sorting_mode_options(),
				array('select_to_list' => true)
			));

			$fieldset->add_field(new FormFieldCheckbox('summary_displayed_to_guests', $this->items_lang['config.items.summary.displayed.to.guests'], $this->config->get_summary_displayed_to_guests(),
				array('class' => 'custom-checkbox')
			));

			$fieldset->add_field(new FormFieldNumberEditor('auto_cut_characters_number', $this->items_lang['config.auto.cut.characters.number'], $this->config->get_auto_cut_characters_number(),
				array('min' => 20, 'max' => 1000, 'description' => $this->items_lang['config.auto.cut.characters.number.explain'], 'required' => true),
				array(new FormFieldConstraintIntegerRange(20, 1000))
			));

			$fieldset->add_field(new FormFieldCheckbox('author_displayed', $this->lang['config.author_displayed'], $this->config->get_author_displayed(),
				array('class' => 'custom-checkbox')
			));

			$fieldset->add_field(new FormFieldCheckbox('views_number_enabled', $this->lang['config.views.number.enabled'], $this->config->get_views_number_enabled(),
				array('class' => 'custom-checkbox')
			));

			$fieldset->add_field(new FormFieldSimpleSelectChoice('display_type', $this->lang['config.display.type'], $this->config->get_display_type(),
				array(
					new FormFieldSelectChoiceOption($this->lang['config.display.type.grid'], DefaultRichModuleConfig::GRID_VIEW, array('data_option_icon' => 'far fa-id-card')),
					new FormFieldSelectChoiceOption($this->lang['config.display.type.list'], DefaultRichModuleConfig::LIST_VIEW, array('data_option_icon' => 'fa fa-list'))
				),
				array('select_to_list' => true,
					'events' => array('change' => '
					if (HTMLForms.getField("display_type").getValue() === \'' . DefaultRichModuleConfig::GRID_VIEW . '\') {
						HTMLForms.getField("items_per_row").enable();
					} else {
						HTMLForms.getField("items_per_row").disable();
					}'))
			));

			$fieldset->add_field(new FormFieldNumberEditor('items_per_row', $this->items_lang['config.items.per.row'], $this->config->get_items_per_row(),
				array(
					'hidden' => $this->config->get_display_type() !== DefaultRichModuleConfig::GRID_VIEW,
					'min' => 1, 'max' => 4,
					'required' => true
				),
				array(new FormFieldConstraintIntegerRange(1, 4))
			));

			$this->add_additional_fields($fieldset);

			$fieldset->add_field(new FormFieldRichTextEditor('default_content', $this->items_lang['config.item.default.content'], $this->config->get_default_content(),
				array('rows' => 8, 'cols' => 47)
			));

			if (self::get_module()->get_configuration()->has_categories())
			{
				$fieldset_categories = new FormFieldsetHTML('categories', LangLoader::get_message('categories', 'categories-common'));
				$form->add_fieldset($fieldset_categories);

				$fieldset_categories->add_field(new FormFieldNumberEditor('categories_per_page', $this->lang['config.categories_number_per_page'], $this->config->get_categories_per_page(),
					array('min' => 1, 'max' => 50, 'required' => true),
					array(new FormFieldConstraintIntegerRange(1, 50))
				));

				$fieldset_categories->add_field(new FormFieldNumberEditor('categories_per_row', $this->lang['config.categories.per.row'], $this->config->get_categories_per_row(),
					array('min' => 1, 'max' => 4, 'required' => true),
					array(new FormFieldConstraintIntegerRange(1, 4))
				));

				$fieldset_categories->add_field(new FormFieldRichTextEditor('root_category_description', $this->lang['config.root_category_description'], $this->config->get_root_category_description(),
					array('rows' => 8, 'cols' => 47)
				));
			}
		}
		else
			$this->add_additional_fields($fieldset);

		$fieldset_authorizations = new FormFieldsetHTML('authorizations', LangLoader::get_message('authorizations', 'common'),
			array('description' => $this->lang['config.authorizations.explain'])
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
		
		if (self::get_module()->get_configuration()->has_rich_items())
		{
			$this->config->set_items_default_sort_field($this->form->get_value('items_default_sort_field')->get_raw_value());
			$this->config->set_items_default_sort_mode($this->form->get_value('items_default_sort_mode')->get_raw_value());
			$this->config->set_summary_displayed_to_guests($this->form->get_value('summary_displayed_to_guests'));
			$this->config->set_auto_cut_characters_number($this->form->get_value('auto_cut_characters_number'));
			$this->config->set_author_displayed($this->form->get_value('author_displayed'));
			$this->config->set_views_number_enabled($this->form->get_value('views_number_enabled'));
			$this->config->set_display_type($this->form->get_value('display_type')->get_raw_value());
			if($this->config->get_display_type() == DefaultRichModuleConfig::GRID_VIEW)
				$this->config->set_items_per_row($this->form->get_value('items_per_row'));
			$this->config->set_default_content($this->form->get_value('default_content'));
			
			if (self::get_module()->get_configuration()->has_categories())
			{
				$this->config->set_categories_per_page($this->form->get_value('categories_per_page'));
				$this->config->set_categories_per_row($this->form->get_value('categories_per_row'));
				$this->config->set_root_category_description($this->form->get_value('root_category_description'));
			}
		}
		$this->save_additional_fields();

		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());

		$configuration_class_name = self::get_module()->get_configuration()->get_configuration_name();
		$configuration_class_name::save();
		
		if (self::get_module()->get_configuration()->has_categories())
			CategoriesService::get_categories_manager()->regenerate_cache();
	}

	protected function hide_fields() {}

	protected function add_additional_fields(&$fieldset) {}

	protected function save_additional_fields() {}
}
?>
