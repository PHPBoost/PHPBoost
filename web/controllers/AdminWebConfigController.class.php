<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 02 09
 * @since       PHPBoost 4.1 - 2014 08 21
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor Mipel <mipel@phpboost.com>
*/

class AdminWebConfigController extends AdminModuleController
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
	 * @var WebConfig
	 */
	private $config;
	private $comments_config;
	private $content_management_config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->build_form();

		$view = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$view->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->form->get_field_by_id('full_item_display')->set_hidden($this->config->get_display_type() !== WebConfig::LIST_VIEW);
			$this->form->get_field_by_id('characters_number_to_cut')->set_hidden($this->config->is_full_item_displayed() && $this->config->get_display_type() !== WebConfig::GRID_VIEW);
			$this->form->get_field_by_id('items_per_row')->set_hidden($this->config->get_display_type() !== WebConfig::GRID_VIEW);
			$view->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}

		$view->put('FORM', $this->form->display());

		return new DefaultAdminDisplayResponse($view);
	}

	private function init()
	{
		$this->config = WebConfig::load();
		$this->comments_config = CommentsConfig::load();
		$this->content_management_config = ContentManagementConfig::load();
		$this->lang = LangLoader::get('common', 'web');
		$this->admin_common_lang = LangLoader::get('admin-common');
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('configuration', StringVars::replace_vars(LangLoader::get_message('configuration.module.title', 'admin-common'), array('module_name' => self::get_module()->get_configuration()->get_name())));
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldNumberEditor('categories_per_page', $this->admin_common_lang['config.categories.per.page'], $this->config->get_categories_per_page(),
			array('min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

		$fieldset->add_field(new FormFieldNumberEditor('categories_per_row', $this->admin_common_lang['config.categories.per.row'], $this->config->get_categories_per_row(),
			array('min' => 1, 'max' => 4, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 4))
		));

		$fieldset->add_field(new FormFieldRichTextEditor('root_category_description', $this->admin_common_lang['config.root_category_description'], $this->config->get_root_category_description(),
			array('rows' => 8, 'cols' => 47)
		));

        $fieldset->add_field(new FormFieldNumberEditor('items_per_page', $this->admin_common_lang['config.items.per.page'], $this->config->get_items_per_page(),
			array('min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

        $fieldset->add_field(new FormFieldSimpleSelectChoice('items_default_sort', $this->admin_common_lang['config.items_default_sort'], $this->config->get_items_default_sort_field() . '-' . $this->config->get_items_default_sort_mode(), $this->get_sort_options()));

		$fieldset->add_field(new FormFieldCheckbox('display_descriptions_to_guests', $this->admin_common_lang['config.display.summary.to.guests'], $this->config->are_descriptions_displayed_to_guests(),
			array('class' => 'custom-checkbox')
		));

		$fieldset->add_field(new FormFieldSpacer('categories_display_type',''));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('display_type', $this->admin_common_lang['config.display.type'], $this->config->get_display_type(),
			array(
				new FormFieldSelectChoiceOption($this->admin_common_lang['config.display.type.grid'], WebConfig::GRID_VIEW, array('data_option_icon' => 'far fa-id-card')),
				new FormFieldSelectChoiceOption($this->admin_common_lang['config.display.type.list'], WebConfig::LIST_VIEW, array('data_option_icon' => 'fa fa-list')),
				new FormFieldSelectChoiceOption($this->admin_common_lang['config.display.type.table'], WebConfig::TABLE_VIEW, array('data_option_icon' => 'fa fa-table'))
			),
			array(
				'select_to_list' => true,
				'events' => array('change' => '
					if (HTMLForms.getField("display_type").getValue() == \'' . WebConfig::GRID_VIEW . '\') {
						HTMLForms.getField("items_per_row").enable();
						HTMLForms.getField("full_item_display").disable();
					} else if (HTMLForms.getField("display_type").getValue() == \'' . WebConfig::LIST_VIEW . '\') {
						HTMLForms.getField("full_item_display").enable();
						HTMLForms.getField("items_per_row").disable();
					} else {
						HTMLForms.getField("items_per_row").disable();
						HTMLForms.getField("full_item_display").disable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldNumberEditor('items_per_row', $this->admin_common_lang['config.items.per.row'], $this->config->get_items_per_row(),
			array(
				'hidden' => $this->config->get_display_type() !== WebConfig::GRID_VIEW,
				'min' => 1, 'max' => 4, 'required' => true),
				array(new FormFieldConstraintIntegerRange(1, 4))
		));

		$fieldset->add_field(new FormFieldCheckbox('full_item_display', $this->admin_common_lang['config.full.item.display'], $this->config->is_full_item_displayed(),
			array(
				'class' => 'custom-checkbox',
				'hidden' => $this->config->get_display_type() !== WebConfig::LIST_VIEW,
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
				'hidden' => $this->config->get_display_type() == WebConfig::LIST_VIEW && $this->config->is_full_item_displayed()
			),
			array(new FormFieldConstraintIntegerRange(20, 1000)
		)));

		$fieldset->add_field(new FormFieldRichTextEditor('default_content', $this->lang['web.default.content'], $this->config->get_default_content(),
			array('rows' => 8, 'cols' => 47)
		));

		$fieldset = new FormFieldsetHTML('menu', $this->lang['web.config.partners.menu']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldSimpleSelectChoice('partners_sort', $this->lang['web.config.partners.sort'], $this->config->get_partners_sort_field() . '-' . $this->config->get_partners_sort_mode(), $this->get_sort_options(),
			array('description' => $this->lang['web.config.partners.sort.description'])
		));

		$fieldset->add_field(new FormFieldNumberEditor('partners_number_in_menu', $this->lang['web.config.partners.number'], $this->config->get_partners_number_in_menu(),
			array('min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

		$fieldset_authorizations = new FormFieldsetHTML('authorizations_fieldset', LangLoader::get_message('authorizations', 'common'),
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

	private function get_sort_options()
	{
		$common_lang = LangLoader::get('common');

		$sort_options = array(
			new FormFieldSelectChoiceOption($common_lang['form.date.creation'] . ' - ' . $common_lang['sort.asc'], WebItem::SORT_DATE . '-' . WebItem::ASC),
			new FormFieldSelectChoiceOption($common_lang['form.date.creation'] . ' - ' . $common_lang['sort.desc'], WebItem::SORT_DATE . '-' . WebItem::DESC),
			new FormFieldSelectChoiceOption($common_lang['sort_by.alphabetic'] . ' - ' . $common_lang['sort.asc'], WebItem::SORT_ALPHABETIC . '-' . WebItem::ASC),
			new FormFieldSelectChoiceOption($common_lang['sort_by.alphabetic'] . ' - ' . $common_lang['sort.desc'], WebItem::SORT_ALPHABETIC . '-' . WebItem::DESC),
			new FormFieldSelectChoiceOption($this->lang['web.config.sort.type.visits'] . ' - ' . $common_lang['sort.asc'], WebItem::SORT_NUMBER_VISITS . '-' . WebItem::ASC),
			new FormFieldSelectChoiceOption($this->lang['web.config.sort.type.visits'] . ' - ' . $common_lang['sort.desc'], WebItem::SORT_NUMBER_VISITS . '-' . WebItem::DESC)
		);

		if ($this->comments_config->module_comments_is_enabled('web'))
		{
			$sort_options[] = new FormFieldSelectChoiceOption($common_lang['sort_by.comments.number'] . ' - ' . $common_lang['sort.asc'], WebItem::SORT_NUMBER_COMMENTS . '-' . WebItem::ASC);
			$sort_options[] = new FormFieldSelectChoiceOption($common_lang['sort_by.comments.number'] . ' - ' . $common_lang['sort.desc'], WebItem::SORT_NUMBER_COMMENTS . '-' . WebItem::DESC);
		}

		if ($this->content_management_config->module_notation_is_enabled('web'))
		{
			$sort_options[] = new FormFieldSelectChoiceOption($common_lang['sort_by.best.note'] . ' - ' . $common_lang['sort.asc'], WebItem::SORT_NOTATION . '-' . WebItem::ASC);
			$sort_options[] = new FormFieldSelectChoiceOption($common_lang['sort_by.best.note'] . ' - ' . $common_lang['sort.desc'], WebItem::SORT_NOTATION . '-' . WebItem::DESC);
		}

		return $sort_options;
	}

	private function save()
	{
		$this->config->set_items_per_page($this->form->get_value('items_per_page'));

		if($this->form->get_value('display_type') == WebConfig::GRID_VIEW)
			$this->config->set_items_per_row($this->form->get_value('items_per_row'));

		if ($this->form->get_value('full_item_display'))
			$this->config->display_full_item();
		else
			$this->config->display_condensed_item();

		$this->config->set_characters_number_to_cut($this->form->get_value('characters_number_to_cut', $this->config->get_characters_number_to_cut()));
		$this->config->set_categories_per_page($this->form->get_value('categories_per_page'));
		$this->config->set_categories_per_row($this->form->get_value('categories_per_row'));
		$this->config->set_display_type($this->form->get_value('display_type')->get_raw_value());

		$items_default_sort = $this->form->get_value('items_default_sort')->get_raw_value();
		$items_default_sort = explode('-', $items_default_sort);
		$this->config->set_items_default_sort_field($items_default_sort[0]);
		$this->config->set_items_default_sort_mode(TextHelper::strtolower($items_default_sort[1]));

		if ($this->form->get_value('display_descriptions_to_guests'))
			$this->config->display_descriptions_to_guests();
		else
			$this->config->hide_descriptions_to_guests();

		$this->config->set_root_category_description($this->form->get_value('root_category_description'));

		$partners_sort = $this->form->get_value('partners_sort')->get_raw_value();
		$partners_sort = explode('-', $partners_sort);
		$this->config->set_partners_sort_field($partners_sort[0]);
		$this->config->set_partners_sort_mode($partners_sort[1]);
		$this->config->set_partners_number_in_menu($this->form->get_value('partners_number_in_menu'));
                $this->config->set_default_content($this->form->get_value('default_content'));
		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());

		WebConfig::save();
		CategoriesService::get_categories_manager()->regenerate_cache();
		WebCache::invalidate();
	}
}
?>
