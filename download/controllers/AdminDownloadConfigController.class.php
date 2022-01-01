<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 14
 * @since       PHPBoost 4.0 - 2014 08 24
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor Mipel <mipel@phpboost.com>
*/

class AdminDownloadConfigController extends DefaultAdminModuleController
{
	private $comments_config;
	private $content_management_config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->build_form();

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->form->get_field_by_id('display_summary_to_guests')->set_hidden($this->config->get_display_type() == DownloadConfig::TABLE_VIEW);
			$this->form->get_field_by_id('auto_cut_characters_number')->set_hidden($this->config->is_full_item_displayed() || $this->config->get_display_type() == DownloadConfig::TABLE_VIEW);
			$this->form->get_field_by_id('full_item_display')->set_hidden($this->config->get_display_type() !== DownloadConfig::LIST_VIEW);
			$this->form->get_field_by_id('items_per_row')->set_hidden($this->config->get_display_type() !== DownloadConfig::GRID_VIEW);
			$this->form->get_field_by_id('oldest_file_day_in_menu')->set_hidden(!$this->config->is_limit_oldest_file_day_in_menu_enabled());
			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.success.config'], MessageHelper::SUCCESS, 5));
		}

		$this->view->put('CONTENT', $this->form->display());

		return new DefaultAdminDisplayResponse($this->view);
	}

	private function init()
	{
		$this->comments_config = CommentsConfig::load();
		$this->content_management_config = ContentManagementConfig::load();
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

		$fieldset->add_field(new FormFieldSimpleSelectChoice('items_default_sort', $this->lang['form.items.default.sort'], $this->config->get_items_default_sort_field() . '-' . $this->config->get_items_default_sort_mode(), $this->get_sort_options()));

		$fieldset->add_field(new FormFieldNumberEditor('items_per_page', $this->lang['form.items.per.page'], $this->config->get_items_per_page(),
			array('min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

		$fieldset->add_field(new FormFieldSpacer('display', ''));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('display_type', $this->lang['form.display.type'], $this->config->get_display_type(),
			array(
				new FormFieldSelectChoiceOption($this->lang['form.display.type.grid'], DownloadConfig::GRID_VIEW, array('data_option_icon' => 'fa fa-th-large')),
				new FormFieldSelectChoiceOption($this->lang['form.display.type.list'], DownloadConfig::LIST_VIEW, array('data_option_icon' => 'fa fa-list')),
				new FormFieldSelectChoiceOption($this->lang['form.display.type.table'], DownloadConfig::TABLE_VIEW, array('data_option_icon' => 'fa fa-table'))
			),
			array(
				'select_to_list' => true,
				'events' => array('change' => '
				if (HTMLForms.getField("display_type").getValue() == \'' . DownloadConfig::GRID_VIEW . '\') {
					HTMLForms.getField("items_per_row").enable();
					HTMLForms.getField("display_summary_to_guests").enable();
						HTMLForms.getField("auto_cut_characters_number").enable();
					HTMLForms.getField("full_item_display").disable();
				} else if (HTMLForms.getField("display_type").getValue() == \'' . DownloadConfig::LIST_VIEW . '\') {
					HTMLForms.getField("full_item_display").enable();
					HTMLForms.getField("display_summary_to_guests").enable();
					HTMLForms.getField("items_per_row").disable();
					if (HTMLForms.getField("full_item_display").getValue()) {
						HTMLForms.getField("auto_cut_characters_number").disable();
					} else {
						HTMLForms.getField("auto_cut_characters_number").enable();
					}
				} else {
					HTMLForms.getField("items_per_row").disable();
					HTMLForms.getField("display_summary_to_guests").disable();
					HTMLForms.getField("full_item_display").disable();
					HTMLForms.getField("auto_cut_characters_number").disable();
				}'
			))
		));

		$fieldset->add_field(new FormFieldNumberEditor('items_per_row', $this->lang['form.items.per.row'], $this->config->get_items_per_row(),
			array(
				'hidden' => $this->config->get_display_type() !== DownloadConfig::GRID_VIEW,
				'min' => 1, 'max' => 4, 'required' => true),
				array(new FormFieldConstraintIntegerRange(1, 4))
		));

		$fieldset->add_field(new FormFieldCheckbox('full_item_display', $this->lang['form.display.full.item'], $this->config->is_full_item_displayed(),
			array(
				'class' => 'custom-checkbox',
				'hidden' => $this->config->get_display_type() !== DownloadConfig::LIST_VIEW,
				'events' => array('click' => '
					if (HTMLForms.getField("full_item_display").getValue()) {
						HTMLForms.getField("auto_cut_characters_number").disable();
					} else {
						HTMLForms.getField("auto_cut_characters_number").enable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldNumberEditor('auto_cut_characters_number', $this->lang['form.characters.number.to.cut'], $this->config->get_auto_cut_characters_number(),
			array(
				'min' => 20, 'max' => 1000, 'required' => true,
				'hidden' => $this->config->get_display_type() == DownloadConfig::LIST_VIEW && $this->config->is_full_item_displayed()
			),
			array(new FormFieldConstraintIntegerRange(20, 1000)
		)));

		$fieldset->add_field(new FormFieldCheckbox('display_summary_to_guests', $this->lang['form.display.summary.to.guests'], $this->config->is_summary_displayed_to_guests(),
			array(
				'class' => 'custom-checkbox',
				'hidden' => $this->config->get_display_type() == DownloadConfig::TABLE_VIEW
			)
		));$fieldset->add_field(new FormFieldRichTextEditor('root_category_description', $this->lang['form.root.category.description'], $this->config->get_root_category_description(),
			array('rows' => 8, 'cols' => 47)
		));

		$fieldset->add_field(new FormFieldCheckbox('author_displayed', $this->lang['form.display.author'], $this->config->is_author_displayed(),
			array('class' => 'custom-checkbox')
		));

		$fieldset->add_field(new FormFieldCheckbox('nb_view_enabled', $this->lang['form.display.views.number'], $this->config->get_enabled_views_number(),
			array('class' => 'custom-checkbox')
		));

        $fieldset->add_field(new FormFieldRichTextEditor('default_content', $this->lang['form.item.default.content'], $this->config->get_default_content(),
			array('rows' => 8, 'cols' => 47)
		));

		$fieldset = new FormFieldsetHTML('menu', $this->lang['download.config.mini.module']);
		$form->add_fieldset($fieldset);

		$sort_options = array(
			new FormFieldSelectChoiceOption($this->lang['common.sort.by.update'], DownloadItem::SORT_UPDATE_DATE, array('data_option_icon' => 'far fa-calendar-plus')),
			new FormFieldSelectChoiceOption($this->lang['common.sort.by.date'], DownloadItem::SORT_DATE, array('data_option_icon' => 'far fa-calendar-alt')),
			new FormFieldSelectChoiceOption($this->lang['common.sort.by.alphabetic'], DownloadItem::SORT_ALPHABETIC, array('data_option_icon' => 'fa fa-sort-alpha-up')),
			new FormFieldSelectChoiceOption($this->lang['common.sort.by.author'], DownloadItem::SORT_AUTHOR, array('data_option_icon' => 'far fa-user')),
			new FormFieldSelectChoiceOption($this->lang['download.downloads.number'], DownloadItem::SORT_DOWNLOADS_NUMBER, array('data_option_icon' => 'fa fa-download')),
			new FormFieldSelectChoiceOption($this->lang['common.sort.by.views.number'], DownloadItem::SORT_VIEWS_NUMBERS, array('data_option_icon' => 'fa fa-eye')),
		);

		if ($this->comments_config->module_comments_is_enabled('download'))
			$sort_options[] = new FormFieldSelectChoiceOption($this->lang['common.sort.by.comments.number'], DownloadItem::SORT_COMMENTS_NUMBER, array('data_option_icon' => 'far fa-comments'));

		if ($this->content_management_config->module_notation_is_enabled('download'))
			$sort_options[] = new FormFieldSelectChoiceOption($this->lang['common.sort.by.best.note'], DownloadItem::SORT_NOTATION, array('data_option_icon' => 'far fa-star'));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_type', $this->lang['common.sort.direction'], $this->config->get_sort_type(), $sort_options,
			array('select_to_list' => true, 'description' => $this->lang['download.config.sort.type.clue'])
		));

		$fieldset->add_field(new FormFieldNumberEditor('files_number_in_menu', $this->lang['download.config.items.number'], $this->config->get_files_number_in_menu(),
			array('min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

		$fieldset->add_field(new FormFieldCheckbox('limit_oldest_file_day_in_menu', $this->lang['download.config.limit.items.age'], $this->config->is_limit_oldest_file_day_in_menu_enabled(),
			array(
				'class' => 'custom-checkbox',
				'events' => array('click' => '
					if (HTMLForms.getField("limit_oldest_file_day_in_menu").getValue()) {
						HTMLForms.getField("oldest_file_day_in_menu").enable();
					} else {
						HTMLForms.getField("oldest_file_day_in_menu").disable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldNumberEditor('oldest_file_day_in_menu', $this->lang['download.config.max.items.age'], $this->config->get_oldest_file_day_in_menu(),
			array('min' => 1, 'max' => 365, 'required' => true, 'hidden' => !$this->config->is_limit_oldest_file_day_in_menu_enabled()),
			array(new FormFieldConstraintIntegerRange(1, 365))
		));

		$fieldset_authorizations = new FormFieldsetHTML('authorizations_fieldset', $this->lang['form.authorizations'],
			array('description' => $this->lang['form.authorizations.clue'])
		);
		$form->add_fieldset($fieldset_authorizations);

		$auth_settings = new AuthorizationsSettings(array_merge(RootCategory::get_authorizations_settings(), array(
			new ActionAuthorization($this->lang['download.config.download.link'], DownloadAuthorizationsService::DISPLAY_DOWNLOAD_LINK_AUTHORIZATIONS)
		)));
		$auth_settings->build_from_auth_array($this->config->get_authorizations());
		$fieldset_authorizations->add_field(new FormFieldAuthorizationsSetter('authorizations', $auth_settings));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function get_sort_options()
	{
		$sort_options = array(
			new FormFieldSelectChoiceOption($this->lang['common.sort.by.update'] . ' - ' . $this->lang['common.sort.asc'], DownloadItem::SORT_UPDATE_DATE . '-' . DownloadItem::ASC),
			new FormFieldSelectChoiceOption($this->lang['common.sort.by.update'] . ' - ' . $this->lang['common.sort.desc'], DownloadItem::SORT_UPDATE_DATE . '-' . DownloadItem::DESC),
			new FormFieldSelectChoiceOption($this->lang['common.sort.by.date'] . ' - ' . $this->lang['common.sort.asc'], DownloadItem::SORT_DATE . '-' . DownloadItem::ASC),
			new FormFieldSelectChoiceOption($this->lang['common.sort.by.date'] . ' - ' . $this->lang['common.sort.desc'], DownloadItem::SORT_DATE . '-' . DownloadItem::DESC),
			new FormFieldSelectChoiceOption($this->lang['common.sort.by.alphabetic'] . ' - ' . $this->lang['common.sort.asc'], DownloadItem::SORT_ALPHABETIC . '-' . DownloadItem::ASC),
			new FormFieldSelectChoiceOption($this->lang['common.sort.by.alphabetic'] . ' - ' . $this->lang['common.sort.desc'], DownloadItem::SORT_ALPHABETIC . '-' . DownloadItem::DESC),
			new FormFieldSelectChoiceOption($this->lang['common.sort.by.author'] . ' - ' . $this->lang['common.sort.asc'], DownloadItem::SORT_AUTHOR . '-' . DownloadItem::ASC),
			new FormFieldSelectChoiceOption($this->lang['common.sort.by.author'] . ' - ' . $this->lang['common.sort.desc'], DownloadItem::SORT_AUTHOR . '-' . DownloadItem::DESC),
			new FormFieldSelectChoiceOption($this->lang['download.downloads.number'] . ' - ' . $this->lang['common.sort.asc'], DownloadItem::SORT_DOWNLOADS_NUMBER . '-' . DownloadItem::ASC),
			new FormFieldSelectChoiceOption($this->lang['download.downloads.number'] . ' - ' . $this->lang['common.sort.desc'], DownloadItem::SORT_DOWNLOADS_NUMBER . '-' . DownloadItem::DESC),
			new FormFieldSelectChoiceOption($this->lang['common.sort.by.views.number'] . ' - ' . $this->lang['common.sort.asc'], DownloadItem::SORT_VIEWS_NUMBERS . '-' . DownloadItem::ASC),
			new FormFieldSelectChoiceOption($this->lang['common.sort.by.views.number'] . ' - ' . $this->lang['common.sort.desc'], DownloadItem::SORT_VIEWS_NUMBERS . '-' . DownloadItem::DESC)
		);

		if ($this->comments_config->module_comments_is_enabled('download'))
		{
			$sort_options[] = new FormFieldSelectChoiceOption($this->lang['common.sort.by.comments.number'] . ' - ' . $this->lang['common.sort.asc'], DownloadItem::SORT_COMMENTS_NUMBER . '-' . DownloadItem::ASC);
			$sort_options[] = new FormFieldSelectChoiceOption($this->lang['common.sort.by.comments.number'] . ' - ' . $this->lang['common.sort.desc'], DownloadItem::SORT_COMMENTS_NUMBER . '-' . DownloadItem::DESC);
		}

		if ($this->content_management_config->module_notation_is_enabled('download'))
		{
			$sort_options[] = new FormFieldSelectChoiceOption($this->lang['common.sort.by.best.note'] . ' - ' . $this->lang['common.sort.asc'], DownloadItem::SORT_NOTATION . '-' . DownloadItem::ASC);
			$sort_options[] = new FormFieldSelectChoiceOption($this->lang['common.sort.by.best.note'] . ' - ' . $this->lang['common.sort.desc'], DownloadItem::SORT_NOTATION . '-' . DownloadItem::DESC);
		}

		return $sort_options;
	}

	private function save()
	{
		$this->config->set_items_per_page($this->form->get_value('items_per_page'));

		if($this->form->get_value('display_type') == DownloadConfig::GRID_VIEW)
			$this->config->set_items_number_per_row($this->form->get_value('items_per_row'));

		if ($this->form->get_value('full_item_display'))
			$this->config->display_full_item();
		else
			$this->config->display_condensed_item();
		$this->config->set_categories_per_page($this->form->get_value('categories_per_page'));
		$this->config->set_categories_per_row($this->form->get_value('categories_per_row'));
		$this->config->set_display_type($this->form->get_value('display_type')->get_raw_value());

		$items_default_sort = $this->form->get_value('items_default_sort')->get_raw_value();
		$items_default_sort = explode('-', $items_default_sort);
		$this->config->set_items_default_sort_field($items_default_sort[0]);
		$this->config->set_items_default_sort_mode(TextHelper::strtolower($items_default_sort[1]));

		if ($this->config->get_display_type() != DownloadConfig::TABLE_VIEW)
		{
			if ($this->form->get_value('display_summary_to_guests'))
				$this->config->display_summary_to_guests();
			else
				$this->config->hide_summary_to_guests();
		}

		if ($this->form->get_value('author_displayed'))
			$this->config->display_author();
		else
			$this->config->hide_author();

		$this->config->set_auto_cut_characters_number($this->form->get_value('auto_cut_characters_number', $this->config->get_auto_cut_characters_number()));
		$this->config->set_enabled_views_number($this->form->get_value('nb_view_enabled'));
		$this->config->set_root_category_description($this->form->get_value('root_category_description'));
		$this->config->set_sort_type($this->form->get_value('sort_type')->get_raw_value());
		$this->config->set_files_number_in_menu($this->form->get_value('files_number_in_menu'));

		if ($this->form->get_value('limit_oldest_file_day_in_menu'))
		{
			$this->config->enable_limit_oldest_file_day_in_menu();
			$this->config->set_oldest_file_day_in_menu($this->form->get_value('oldest_file_day_in_menu'));
		}
		else
			$this->config->disable_limit_oldest_file_day_in_menu();

		$this->config->set_default_content($this->form->get_value('default_content'));
        $this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());

		DownloadConfig::save();
		CategoriesService::get_categories_manager()->regenerate_cache();
		DownloadCache::invalidate();

		HooksService::execute_hook_action('edit_config', self::$module_id, array('title' => StringVars::replace_vars($this->lang['form.module.title'], array('module_name' => self::get_module_configuration()->get_name())), 'url' => ModulesUrlBuilder::configuration()->rel()));
	}
}
?>
