<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 25
 * @since       PHPBoost 4.0 - 2014 08 24
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor Mipel <mipel@phpboost.com>
*/

class AdminDownloadConfigController extends AdminModuleController
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
	private $common_lang;
	private $admin_common_lang;

	/**
	 * @var DownloadConfig
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
			$this->form->get_field_by_id('display_summary_to_guests')->set_hidden($this->config->get_display_type() == DownloadConfig::TABLE_VIEW);
			$this->form->get_field_by_id('full_item_display')->set_hidden($this->config->get_display_type() !== DownloadConfig::LIST_VIEW);
			$this->form->get_field_by_id('items_per_row')->set_hidden($this->config->get_display_type() !== DownloadConfig::GRID_VIEW);
			$this->form->get_field_by_id('oldest_file_day_in_menu')->set_hidden(!$this->config->is_limit_oldest_file_day_in_menu_enabled());
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}

		$tpl->put('FORM', $this->form->display());

		return new DefaultAdminDisplayResponse($tpl);
	}

	private function init()
	{
		$this->config = DownloadConfig::load();
		$this->comments_config = CommentsConfig::load();
		$this->content_management_config = ContentManagementConfig::load();
		$this->lang = LangLoader::get('common', 'download');
		$this->common_lang = LangLoader::get('common');
		$this->admin_common_lang = LangLoader::get('admin-common');
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTMLHeading('configuration', StringVars::replace_vars($this->admin_common_lang['configuration.module.title'], array('module_name' => self::get_module()->get_configuration()->get_name())));
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldNumberEditor('categories_per_page', $this->admin_common_lang['config.categories_number_per_page'], $this->config->get_categories_per_page(),
			array('min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

		$fieldset->add_field(new FormFieldNumberEditor('categories_per_row', $this->admin_common_lang['config.categories.per.row'], $this->config->get_categories_per_row(),
			array('min' => 1, 'max' => 4, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 4))
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('items_default_sort', $this->admin_common_lang['config.items_default_sort'], $this->config->get_items_default_sort_field() . '-' . $this->config->get_items_default_sort_mode(), $this->get_sort_options()));

		$fieldset->add_field(new FormFieldCheckbox('author_displayed', $this->admin_common_lang['config.author_displayed'], $this->config->is_author_displayed(),
			array('class' => 'custom-checkbox')
		));

		$fieldset->add_field(new FormFieldCheckbox('nb_view_enabled', $this->lang['admin.config.download_views_number_enabled'], $this->config->get_enabled_views_number(),
			array('class' => 'custom-checkbox')
		));$fieldset->add_field(new FormFieldRichTextEditor('root_category_description', $this->admin_common_lang['config.root_category_description'], $this->config->get_root_category_description(),
			array('rows' => 8, 'cols' => 47)
		));

		$fieldset->add_field(new FormFieldNumberEditor('items_per_page', $this->admin_common_lang['config.items_number_per_page'], $this->config->get_items_per_page(),
			array('min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('display_type', $this->admin_common_lang['config.display.type'], $this->config->get_display_type(),
			array(
				new FormFieldSelectChoiceOption($this->admin_common_lang['config.display.type.grid'], DownloadConfig::GRID_VIEW, array('data_option_icon' => 'far fa-id-card')),
				new FormFieldSelectChoiceOption($this->admin_common_lang['config.display.type.list'], DownloadConfig::LIST_VIEW, array('data_option_icon' => 'fa fa-list')),
				new FormFieldSelectChoiceOption($this->admin_common_lang['config.display.type.table'], DownloadConfig::TABLE_VIEW, array('data_option_icon' => 'fa fa-table'))
			),
			array(
				'select_to_list' => true,
				'events' => array('change' => '
				if (HTMLForms.getField("display_type").getValue() == \'' . DownloadConfig::GRID_VIEW . '\') {
					HTMLForms.getField("items_per_row").enable();
					HTMLForms.getField("display_summary_to_guests").enable();
					HTMLForms.getField("full_item_display").disable();
				} else if (HTMLForms.getField("display_type").getValue() == \'' . DownloadConfig::LIST_VIEW . '\') {
					HTMLForms.getField("full_item_display").enable();
					HTMLForms.getField("display_summary_to_guests").enable();
					HTMLForms.getField("items_per_row").disable();
				} else {
					HTMLForms.getField("items_per_row").disable();
					HTMLForms.getField("display_summary_to_guests").disable();
					HTMLForms.getField("full_item_display").disable();
				}'
			))
		));

		$fieldset->add_field(new FormFieldNumberEditor('items_per_row', $this->admin_common_lang['config.items.per.row'], $this->config->get_items_per_row(),
			array(
				'hidden' => $this->config->get_display_type() !== DownloadConfig::GRID_VIEW,
				'min' => 1, 'max' => 4, 'required' => true),
				array(new FormFieldConstraintIntegerRange(1, 4))
		));

		$fieldset->add_field(new FormFieldCheckbox('full_item_display', $this->admin_common_lang['config.full.item.display'], $this->config->is_full_item_displayed(),
			array(
				'hidden' => $this->config->get_display_type() !== DownloadConfig::LIST_VIEW,
				'class' => 'custom-checkbox'
			)
		));

		$fieldset->add_field(new FormFieldCheckbox('display_summary_to_guests', $this->admin_common_lang['config.display.summary.to.guests'], $this->config->is_summary_displayed_to_guests(),
			array(
				'class' => 'custom-checkbox',
				'hidden' => $this->config->get_display_type() == DownloadConfig::TABLE_VIEW
			)
		));

        $fieldset->add_field(new FormFieldRichTextEditor('default_contents', $this->lang['download.default.contents'], $this->config->get_default_contents(),
			array('rows' => 8, 'cols' => 47)
		));

		$fieldset = new FormFieldsetHTML('menu', $this->lang['config.downloaded.files.menu']);
		$form->add_fieldset($fieldset);

		$sort_options = array(
			new FormFieldSelectChoiceOption($this->common_lang['form.date.update'], DownloadFile::SORT_UPDATED_DATE, array('data_option_icon' => 'far fa-calendar-plus')),
			new FormFieldSelectChoiceOption($this->common_lang['form.date.creation'], DownloadFile::SORT_DATE, array('data_option_icon' => 'far fa-calendar-alt')),
			new FormFieldSelectChoiceOption($this->common_lang['form.name'], DownloadFile::SORT_ALPHABETIC, array('data_option_icon' => 'fa fa-sort-alpha-up')),
			new FormFieldSelectChoiceOption($this->common_lang['author'], DownloadFile::SORT_AUTHOR, array('data_option_icon' => 'far fa-user')),
			new FormFieldSelectChoiceOption($this->lang['downloads.number'], DownloadFile::SORT_DOWNLOADS_NUMBER, array('data_option_icon' => 'fa fa-download')),
			new FormFieldSelectChoiceOption($this->common_lang['sort_by.views.number'], DownloadFile::SORT_VIEWS_NUMBERS, array('data_option_icon' => 'fa fa-eye')),
		);

		if ($this->comments_config->module_comments_is_enabled('download'))
			$sort_options[] = new FormFieldSelectChoiceOption($this->common_lang['sort_by.comments.number'], DownloadFile::SORT_NUMBER_COMMENTS, array('data_option_icon' => 'far fa-comments'));

		if ($this->content_management_config->module_notation_is_enabled('download'))
			$sort_options[] = new FormFieldSelectChoiceOption($this->common_lang['sort_by.best.note'], DownloadFile::SORT_NOTATION, array('data_option_icon' => 'far fa-star'));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_type', $this->lang['config.sort_type'], $this->config->get_sort_type(), $sort_options,
			array('select_to_list' => true, 'description' => $this->lang['config.sort_type.explain'])
		));

		$fieldset->add_field(new FormFieldNumberEditor('files_number_in_menu', $this->lang['config.files_number_in_menu'], $this->config->get_files_number_in_menu(),
			array('min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

		$fieldset->add_field(new FormFieldCheckbox('limit_oldest_file_day_in_menu', $this->lang['config.limit_oldest_file_day_in_menu'], $this->config->is_limit_oldest_file_day_in_menu_enabled(),
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

		$fieldset->add_field(new FormFieldNumberEditor('oldest_file_day_in_menu', $this->lang['config.oldest_file_day_in_menu'], $this->config->get_oldest_file_day_in_menu(),
			array('min' => 1, 'max' => 365, 'required' => true, 'hidden' => !$this->config->is_limit_oldest_file_day_in_menu_enabled()),
			array(new FormFieldConstraintIntegerRange(1, 365))
		));

		$fieldset_authorizations = new FormFieldsetHTML('authorizations_fieldset', $this->common_lang['authorizations'],
			array('description' => $this->admin_common_lang['config.authorizations.explain'])
		);
		$form->add_fieldset($fieldset_authorizations);

		$auth_settings = new AuthorizationsSettings(array_merge(RootCategory::get_authorizations_settings(), array(
			new ActionAuthorization($this->lang['authorizations.display_download_link'], DownloadAuthorizationsService::DISPLAY_DOWNLOAD_LINK_AUTHORIZATIONS)
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
			new FormFieldSelectChoiceOption($this->common_lang['form.date.update'] . ' - ' . $this->common_lang['sort.asc'], DownloadFile::SORT_UPDATED_DATE . '-' . DownloadFile::ASC),
			new FormFieldSelectChoiceOption($this->common_lang['form.date.update'] . ' - ' . $this->common_lang['sort.desc'], DownloadFile::SORT_UPDATED_DATE . '-' . DownloadFile::DESC),
			new FormFieldSelectChoiceOption($this->common_lang['form.date.creation'] . ' - ' . $this->common_lang['sort.asc'], DownloadFile::SORT_DATE . '-' . DownloadFile::ASC),
			new FormFieldSelectChoiceOption($this->common_lang['form.date.creation'] . ' - ' . $this->common_lang['sort.desc'], DownloadFile::SORT_DATE . '-' . DownloadFile::DESC),
			new FormFieldSelectChoiceOption($this->common_lang['sort_by.alphabetic'] . ' - ' . $this->common_lang['sort.asc'], DownloadFile::SORT_ALPHABETIC . '-' . DownloadFile::ASC),
			new FormFieldSelectChoiceOption($this->common_lang['sort_by.alphabetic'] . ' - ' . $this->common_lang['sort.desc'], DownloadFile::SORT_ALPHABETIC . '-' . DownloadFile::DESC),
			new FormFieldSelectChoiceOption($this->common_lang['author'] . ' - ' . $this->common_lang['sort.asc'], DownloadFile::SORT_AUTHOR . '-' . DownloadFile::ASC),
			new FormFieldSelectChoiceOption($this->common_lang['author'] . ' - ' . $this->common_lang['sort.desc'], DownloadFile::SORT_AUTHOR . '-' . DownloadFile::DESC),
			new FormFieldSelectChoiceOption($this->lang['downloads.number'] . ' - ' . $this->common_lang['sort.asc'], DownloadFile::SORT_DOWNLOADS_NUMBER . '-' . DownloadFile::ASC),
			new FormFieldSelectChoiceOption($this->lang['downloads.number'] . ' - ' . $this->common_lang['sort.desc'], DownloadFile::SORT_DOWNLOADS_NUMBER . '-' . DownloadFile::DESC),
			new FormFieldSelectChoiceOption($this->common_lang['sort_by.views.number'] . ' - ' . $this->common_lang['sort.asc'], DownloadFile::SORT_VIEWS_NUMBERS . '-' . DownloadFile::ASC),
			new FormFieldSelectChoiceOption($this->common_lang['sort_by.views.number'] . ' - ' . $this->common_lang['sort.desc'], DownloadFile::SORT_VIEWS_NUMBERS . '-' . DownloadFile::DESC)
		);

		if ($this->comments_config->module_comments_is_enabled('download'))
		{
			$sort_options[] = new FormFieldSelectChoiceOption($this->common_lang['sort_by.comments.number'] . ' - ' . $this->common_lang['sort.asc'], DownloadFile::SORT_NUMBER_COMMENTS . '-' . DownloadFile::ASC);
			$sort_options[] = new FormFieldSelectChoiceOption($this->common_lang['sort_by.comments.number'] . ' - ' . $this->common_lang['sort.desc'], DownloadFile::SORT_NUMBER_COMMENTS . '-' . DownloadFile::DESC);
		}

		if ($this->content_management_config->module_notation_is_enabled('download'))
		{
			$sort_options[] = new FormFieldSelectChoiceOption($this->common_lang['sort_by.best.note'] . ' - ' . $this->common_lang['sort.asc'], DownloadFile::SORT_NOTATION . '-' . DownloadFile::ASC);
			$sort_options[] = new FormFieldSelectChoiceOption($this->common_lang['sort_by.best.note'] . ' - ' . $this->common_lang['sort.desc'], DownloadFile::SORT_NOTATION . '-' . DownloadFile::DESC);
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

		$this->config->set_default_contents($this->form->get_value('default_contents'));
        $this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());

		DownloadConfig::save();
		CategoriesService::get_categories_manager()->regenerate_cache();
		DownloadCache::invalidate();
	}
}
?>
